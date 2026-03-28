<?php

namespace App\Http\Controllers;

use App\Models\InfPago;
use App\Models\Matricula;
use App\Models\InfConceptoPago;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\MercadoPagoConfig;

class InfPagoController extends Controller
{
    const PAGINATION = 10;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        try {

            // Obtenemos el valor del input "buscarpor" que viene en el request (formulario o query string)
            $buscarpor = $request->get('buscarpor');
            $filtro = $request->get('filtro');

            // Construimos la consulta a la tabla de pagos (modelo InfPago)
            $pagos = InfPago::with(['matricula', 'concepto']) // Eager loading: cargamos relaciones matricula y concepto
                ->when($buscarpor, function ($query, $buscarpor) {
                    // Solo se ejecuta este where si $buscarpor no es nulo ni vacío
                    return $query->where('codigo_transaccion', 'like', "%$buscarpor%");
                });

            switch ($filtro) {
                case 'fecha_desc':
                    $pagos->orderBy('fecha_pago', 'desc');
                    break;
                case 'fecha_asc':
                    $pagos->orderBy('fecha_pago', 'asc');
                    break;
                case 'mat_primaria':
                    $pagos->where('concepto_id', '1');
                    break;

                case 'sin_pago':
                    $pagos->where('fecha_pago', null);
                    break;

                case 'mat_secu':
                    $pagos->where('concepto_id', '2');
                    break;
                case 'pagado':
                    $pagos->where('estado', 'PAGADO');
                    break;
                case 'pendiente':
                    $pagos->where('estado', 'PENDIENTE');
                    break;
                default:
                    $pagos->orderBy('fecha_pago', 'desc');
                    break;
            }


            $pagos = $pagos->paginate(self::PAGINATION); // Paginamos resultados usando la constante PAGINATION

            if ($request->ajax())
                return  view('cpagos.pagos.pagos', compact('pagos', 'buscarpor'))->render();
            // Retornamos la vista con las variables necesarias (colección de pagos y el término buscado)
            return view('cpagos.pagos.index', compact('pagos', 'buscarpor'));
        } catch (\Exception $e) {
            return view('cpagos.pagos.index')->with('error', 'Ups. Error al cargar los pagos.');
        }
    }

    public function success(Request $request)
    {
        // Aquí podrías actualizar el estado del pago
        return view('pagos.resultado', ['status' => 'success']);
    }

    public function failure(Request $request)
    {
        return view('pagos.resultado', ['status' => 'failure']);
    }

    public function pending(Request $request)
    {
        return view('pagos.resultado', ['status' => 'pending']);
    }

    public function webhook(Request $request)
    {
        $data = $request->all();

        // Verifica el tipo de evento
        if (isset($data['type']) && $data['type'] === 'payment') {
            $payment_id = $data['data']['id'];

            // Aquí puedes consultar el estado del pago desde la API de Mercado Pago
            // y actualizar tu tabla pagos
        }

        return response()->json(['status' => 'received'], 200);
    }

    public function crearPreferencia(Request $request)
    {
        $request->validate([
            'pago_id' => 'required|integer',
            'monto' => 'required|numeric',
            'email' => 'nullable|email'
        ]);

        $pago = InfPago::find($request->pago_id);
        if (!$pago) {
            return response()->json(['message' => 'Pago no encontrado'], 404);
        }

        $accessToken = config('services.mercadopago.token') ?? env('MERCADOPAGO_ACCESS_TOKEN');

        $preference = [
            "items" => [
                [
                    "title" => "Pago Matrícula #" . $pago->pago_id,
                    "quantity" => 1,
                    "unit_price" => (float) $request->monto,
                ]
            ],
            "external_reference" => (string) $pago->pago_id, // lo guardas como referencia
            "payer" => [
                "email" => $request->email ?? "no-reply@example.com"
            ],
            "back_urls" => [
                "success" => route('pagos.success'),
                "failure" => route('pagos.failure'),
                "pending" => route('pagos.pending')
            ],
            "auto_return" => "approved"
        ];

        try {
            $response = Http::withToken($accessToken)
                ->post('https://api.mercadopago.com/checkout/preferences', $preference);

            if ($response->successful()) {
                $data = $response->json();
                // puedes guardar init_point, id, etc. si quieres
                Log::info('Preferencia creada: ' . json_encode($data));
                return response()->json(['id' => $data['id'], 'init_point' => $data['init_point']]);
            } else {
                Log::error('Error creando preferencia: ' . $response->body());
                return response()->json(['message' => 'Error con Mercado Pago'], 500);
            }
        } catch (\Exception $e) {
            Log::error('Excepción crearPreferencia: ' . $e->getMessage());
            return response()->json(['message' => 'Error interno'], 500);
        }
    }

    public function validar(Request $request)
    {
        $request->validate([
            'matricula_id' => 'required|integer'
        ]);

        $Nuevo = $request->matricula_id;

        $matricula = \App\Models\Matricula::find($Nuevo);

        if (!$matricula) {
            return response()->json([
                'success' => false,
                'message' => 'Matrícula no encontrada.'
            ]);
        }

        $pago_estado = InfPago::where('matricula_id', $Nuevo)->first();

        if (!$pago_estado) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró información de pago para esta matrícula.'
            ]);
        }

        try {
            $pago_estado->estado = 'Pagado';
            $pago_estado->fecha_pago = now();
            $pago_estado->metodo_pago = 'Efectivo';
            $pago_estado->codigo_transaccion = '111111';
            $usuario = Auth::user();
            $pago_estado->usuario_registro = $usuario->usuario_id;
            $pago_estado->save(); //

            $matricula->estado = 'matriculado';
            $matricula->save();

            return response()->json([
                'success' => true,
                'message' => 'Matrícula validada correctamente.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al validar matrícula: ' . $e->getMessage()
            ]);
        }
    }




    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $matriculas = Matricula::all();
        $conceptos = InfConceptoPago::all();
        return view('cpagos.pagos.create', compact('matriculas', 'conceptos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'matriculaId' => 'required|exists:matriculas,matricula_id',
            'conceptoId' => 'required|exists:conceptospago,concepto_id',
            'monto' => 'required|numeric|min:0',
            'fechaVencimiento' => 'required|date',
            'fechaPago' => 'nullable|date',
            'metodoPago' => 'nullable|string|max:50',
            'comprobanteUrl' => 'nullable|string|max:255',
            'estado' => 'required|in:Pendiente,Pagado,Anulado',
            'codigoTransaccion' => 'nullable|string|max:100|unique:pagos,codigo_transaccion',
            'observaciones' => 'nullable|string|max:500',
        ], [
            'matriculaId.required' => 'Seleccione una matrícula',
            'matriculaId.exists' => 'La matrícula seleccionada no existe',
            'conceptoId.required' => 'Seleccione un concepto',
            'conceptoId.exists' => 'El concepto seleccionado no existe',
            'monto.required' => 'Ingrese el monto',
            'monto.numeric' => 'El monto debe ser numérico',
            'fechaVencimiento.required' => 'Ingrese la fecha de vencimiento',
            'fechaVencimiento.date' => 'Ingrese una fecha válida',
            'fechaPago.date' => 'Ingrese una fecha de pago válida',
            'estado.required' => 'Seleccione el estado',
            'estado.in' => 'Estado no válido',
            'codigoTransaccion.unique' => 'El código de transacción ya existe',
            'observaciones.max' => 'Las observaciones son demasiado largas (máximo 500 caracteres)'
        ]);

        $pago = new InfPago();
        $pago->matricula_id = $request->matriculaId;
        $pago->concepto_id = $request->conceptoId;
        $pago->monto = $request->monto;
        $pago->fecha_vencimiento = $request->fechaVencimiento;
        $pago->fecha_pago = $request->fechaPago;
        $pago->metodo_pago = $request->metodoPago;
        $pago->comprobante_url = $request->comprobanteUrl;
        $pago->estado = $request->estado;
        $pago->codigo_transaccion = $request->codigoTransaccion;
        $pago->usuario_registro = 1; // Usuario por defecto
        $pago->observaciones = $request->observaciones;
        $pago->save();

        return redirect()
            ->route('pagos.index')
            ->with('success', 'Pago registrado satisfactoriamente');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $pago = InfPago::with(['matricula', 'concepto'])->findOrFail($id);
        return view('cpago.mostrar', compact('pago'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $pago = InfPago::findOrFail($id);
        $matriculas = Matricula::all();
        $conceptos = InfConceptoPago::all();
        return view('cpagos.pagos.edit', compact('pago', 'matriculas', 'conceptos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $pago = InfPago::findOrFail($id);

        $data = $request->validate([
            'matriculaId' => 'required|exists:matriculas,matricula_id',
            'conceptoId' => 'required|exists:conceptospago,concepto_id',
            'monto' => 'required|numeric|min:0',
            'fechaVencimiento' => 'required|date',
            'fechaPago' => 'nullable|date',
            'metodoPago' => 'nullable|string|max:50',
            'comprobanteUrl' => 'nullable|string|max:255',
            'estado' => 'required|in:Pendiente,Pagado,Anulado',
            'codigoTransaccion' => 'nullable|string|max:100|unique:pagos,codigo_transaccion,' . $id . ',pago_id',
            'observaciones' => 'nullable|string|max:500',
        ]);

        $pago->update([
            'matricula_id' => $request->matriculaId,
            'concepto_id' => $request->conceptoId,
            'monto' => $request->monto,
            'fecha_vencimiento' => $request->fechaVencimiento,
            'fecha_pago' => $request->fechaPago,
            'metodo_pago' => $request->metodoPago,
            'comprobante_url' => $request->comprobanteUrl,
            'estado' => $request->estado,
            'codigo_transaccion' => $request->codigoTransaccion,
            'observaciones' => $request->observaciones,
        ]);

        return redirect()
            ->route('pagos.index')
            ->with('success', 'Pago actualizado satisfactoriamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $pago = InfPago::findOrFail($id);

            if ($pago->estado == 'Pagado') {
                return redirect()
                    ->route('pagos.index')
                    ->with('error', 'No se puede eliminar un pago con estado "Pagado"');
            }

            $codigo = $pago->codigo_transaccion;
            $pago->delete();

            return redirect()
                ->route('pagos.index')
                ->with('success', "Pago {$codigo} eliminado satisfactoriamente");
        } catch (\Exception $e) {
            return redirect()
                ->route('pagos.index')
                ->with('error', 'Error al eliminar el pago: ' . $e->getMessage());
        }
    }
}
