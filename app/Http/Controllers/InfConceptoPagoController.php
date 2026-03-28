<?php

namespace App\Http\Controllers;

use App\Models\InfConceptoPago;
use App\Models\InfNivel;
use App\Models\InfAnioLectivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InfConceptoPagoController extends Controller
{
    const PAGINATION = 6;

    public function index(Request $request)
    {
        $buscarpor = $request->get('buscarpor');

        $conceptos = InfConceptoPago::with(['anoLectivo', 'nivel'])
            ->when($buscarpor, function ($query) use ($buscarpor) {
                return $query->where('nombre', 'like', '%' . $buscarpor . '%')
                    ->orWhere('descripcion', 'like', '%' . $buscarpor . '%');
            })
            ->paginate(self::PAGINATION);

        return view('cpagos.conceptospago.index', compact('conceptos', 'buscarpor'));
    }

    public function create()
    {
        $niveles = InfNivel::all();
        $aniosLectivos = InfAnioLectivo::all();
        return view('cpagos.conceptospago.create', compact('niveles', 'aniosLectivos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100|unique:conceptospago,nombre',
            'descripcion' => 'nullable|string|max:500',
            'monto' => 'required|numeric|min:0',
            'recurrente' => 'boolean',
            'periodo' => 'nullable|string|max:50',
            'ano_lectivo_id' => 'nullable|exists:anoslectivos,ano_lectivo_id',
            'nivel_id' => 'nullable|exists:niveles,nivel_id',
        ]);

        InfConceptoPago::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'monto' => $request->monto,
            'recurrente' => $request->has('recurrente'),
            'periodo' => $request->periodo,
            'ano_lectivo_id' => $request->ano_lectivo_id,
            'nivel_id' => $request->nivel_id,
        ]);

        return redirect()->route('conceptospago.index')
            ->with('success', 'Concepto de pago creado correctamente');
    }

    public function show($id)
    {
        $concepto = InfConceptoPago::with(['anoLectivo', 'nivel'])->findOrFail($id);
        return view('cpagos.conceptospago.show', compact('concepto'));
    }

    public function edit($id)
    {
        $concepto = InfConceptoPago::findOrFail($id);
        $niveles = InfNivel::all();
        $aniosLectivos = InfAnioLectivo::all();
        return view('cpagos.conceptospago.edit', compact('concepto', 'niveles', 'aniosLectivos'));
    }

    public function update(Request $request, $id)
    {
        $concepto = InfConceptoPago::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:100|unique:conceptospago,nombre,' . $id . ',concepto_id',
            'descripcion' => 'nullable|string|max:500',
            'monto' => 'required|numeric|min:0',
            'recurrente' => 'boolean',
            'periodo' => 'nullable|string|max:50',
            'ano_lectivo_id' => 'nullable|exists:anoslectivos,ano_lectivo_id',
            'nivel_id' => 'nullable|exists:niveles,nivel_id',
        ]);

        $concepto->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'monto' => $request->monto,
            'recurrente' => $request->has('recurrente'),
            'periodo' => $request->periodo,
            'ano_lectivo_id' => $request->ano_lectivo_id,
            'nivel_id' => $request->nivel_id,
        ]);

        return redirect()->route('conceptospago.index')
            ->with('success', 'Concepto de pago actualizado correctamente');
    }

    public function destroy($id)
    {
        try {
            $concepto = InfConceptoPago::findOrFail($id);

            // Verificar si el concepto está siendo usado en pagos
            $pagosAsociados = DB::table('pagos')->where('concepto_id', $id)->count();

            if ($pagosAsociados > 0) {
                return redirect()->route('conceptospago.index')
                    ->with('error', 'No se puede eliminar el concepto porque tiene pagos asociados');
            }

            $concepto->delete();

            return redirect()->route('conceptospago.index')
                ->with('success', 'Concepto de pago eliminado correctamente');
        } catch (\Exception $e) {
            return redirect()->route('conceptospago.index')
                ->with('error', 'Error al eliminar el concepto: ' . $e->getMessage());
        }
    }
}
