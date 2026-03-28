<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRepresentanteRequest;
use App\Models\InfEstudianteRepresentante;
use App\Models\InfRepresentante;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\DireccionPersona;
use App\Models\Persona;

class InfRepresentanteController extends Controller
{
    public function exportarPDF()
    {
        // Obtener todos los representantes sin paginar (o lo que necesites)
        $representante = InfRepresentante::all();

        $pdf = Pdf::loadView('ceinformacion.representantes.pdf', compact('representante'))->setPaper('A4', 'portrait');

        // Mostrar en navegador
        return $pdf->stream('representantes_legales.pdf');

        // para descargarlo inmediatamente
        //return $pdf->download('representantes_legales.pdf');
    }

    const PAGINATION = 10;

    public function create(Request $request)
    {
        return view('ceinformacion.representantes.nuevo.nuevo');
    }

    public function index(Request $request)
    {
        $buscarpor = $request->get('buscarpor');

        $representante = InfRepresentante::whereHas('persona', function ($q) use ($buscarpor) {
            $q->activa()
                ->where(function ($q) use ($buscarpor) {
                    $q->where('dni', 'like', "%{$buscarpor}%")
                        ->orWhere('nombres', 'like', "%{$buscarpor}%")
                        ->orWhere('apellidoPaterno', 'like', "%{$buscarpor}%");
                });
        })
            ->orderBy(
                Persona::select('apellidoPaterno')
                    ->whereColumn('personas.id_persona', 'persona_id')
            )
            ->paginate(self::PAGINATION);



        if ($request->ajax()) {
            return view('ceinformacion.representantes.representante', compact('representante'))->render();
        }

        return view('ceinformacion.representantes.registrar', compact('representante', 'buscarpor'));
    }

    public function buscar($dni)
    {
        return InfRepresentante::where('dni', $dni)
            ->first();
    }


    public function store(StoreRepresentanteRequest $request)
    {
        try {
            //TRAEMOS TODAS LAS VALIDACIONES DEL FORMULARIO DE StoreRepresentanteRequest
            $validated = $request->validated();

            //Iniciamos una transacción para asegurar la integridad de los datos
            DB::transaction(function () use ($request, $validated) {

                // Manejo de la foto (si se subió)
                $fotoPath = null;

                // Si se subió una foto, la guardamos y obtenemos su ruta
                if ($request->hasFile('foto')) {
                    $fotoPath = $request->file('foto')->store('fotos', 'public');
                }

                // Creamos la persona primero para obtener su ID y luego asociarla al representante y a la dirección
                $persona = Persona::create([
                    'dni' => $validated['dni'],
                    'nombres' => $validated['nombres'],
                    'apellidoPaterno' => $validated['apellidoPaterno'],
                    'apellidoMaterno' => $validated['apellidoMaterno'],
                    'email' => $validated['email'],
                    'telefono' => $validated['telefono'],
                    'fecha_nacimiento' => $validated['fecha_nacimiento'],
                    'genero' => $validated['genero'],
                    'foto' => $fotoPath,
                ]);

                DireccionPersona::create([
                    'idPersona' => $persona->id_persona,
                    'idRegion' => $validated['region'],
                    'idProvincia' => $validated['provincia'],
                    'idDistrito' => $validated['distrito'],
                    'nombreAvenida' => $validated['calle'],
                    'referencia' => $validated['referencia'] ?? null,
                ]);

                InfRepresentante::create([
                    'persona_id' => $persona->id_persona,
                    'ocupacion' => $validated['ocupacion'] ?? null,
                ]);
            });

            return redirect()
                ->route('representante.index')
                ->with('success', 'Representante registrado satisfactoriamente');
        } catch (\Throwable $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function edit($dni)
    {
        $infRepresentante = InfRepresentante::findOrFail($dni);
        return view('ceinformacion.estudianteRepresentante.registrar', compact('infRepresentante'));
    }


    public function verificarDniRepresentante(Request $request)
    {
        $dni = $request->query('dni');
        $existe = Persona::where('dni', $dni)->exists();
        return response()->json(['existe' => $existe]);
    }

    public function verificarCorreoRepresentante(Request $request)
    {
        $email = $request->query('email');
        $existe = Persona::where('email', $email)->exists();
        return response()->json(['existe' => $existe]);
    }


    public function buscarPorDni(Request $request)
    {
        $dni = $request->dni;

        $persona = Persona::with('representante.persona')
            ->where('dni', $dni)
            ->first();

        if (!$persona || !$persona->representante) {
            return response()->json([
                'success' => false,
                'message' => 'No es representante'
            ]);
        }

        return response()->json([
            'success' => true,
            'representante' => $persona->representante
        ]);
    }


    public function asignarRepresentante(Request $request)
    {
        $estudianteRepresentante = new InfEstudianteRepresentante();

        $estudianteRepresentante->estudiante_id = $request->input('idEstudiante');
        $estudianteRepresentante->representante_id = $request->input('idRepresentante');

        $estudianteRepresentante->save();

        return redirect()->route('registrarestudiante.index')
            ->with([
                'success' => 'Estudiante registrado y asignado a su Representante Legal satisfactoriamente'
            ]);
    }
}
