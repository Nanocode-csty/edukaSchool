<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEstudianteRequest;
use App\Mail\EnviarCredencialesRepresentante;
use App\Models\DireccionPersona;
use App\Models\InfEstudiante;
use App\Models\InfEstudianteRepresentante;
use App\Models\InfRepresentante;
use App\Models\Usuario;
use App\Models\Persona;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class InfEstudianteController extends Controller
{
    const PAGINATION = 10;

    public function index(Request $request)
    {
        $buscarpor = $request->get('buscarpor');

        $estudiante = InfEstudiante::whereHas('persona', function ($q) use ($buscarpor) {
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
            return view('ceinformacion.estudiantes.estudiante', compact('estudiante'))->render();
        }

        return view('ceinformacion.estudiantes.registrar', compact('estudiante', 'buscarpor'));
    }

    public function create(Request $request)
    {
        if ($request->ajax()) {
            return view('ceinformacion.estudiantes.create'); // solo el contenido del formulario
        }

        return view('ceinformacion.estudiantes.nuevo'); // para cuando se accede normalmente
    }

    public function edit($id)
    {
        $estudiante = InfEstudiante::findOrFail($id);
        if (!$estudiante) {
            abort(404);
        }

        return view('ceinformacion.estudiantes.editar.index', compact('estudiante'));
    }

    public function show(InfEstudiante $estudiante)
    {
        return view('ceinformacion.estudiantes.show', compact('estudiante'));
    }

    public function store(StoreEstudianteRequest $request)
    {

        try {
            $validated = $request->validated();

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


                //CREAR ESTUDIANTE
                $estudiante = InfEstudiante::Create(
                    [
                        'persona_id' => $persona->id_persona,
                        'codigo_estudiante' => 'EST' . str_pad($persona->id_persona, 6, '0', STR_PAD_LEFT),
                        'fecha_matricula' => now(),
                    ]
                );

                $estudiante_id = $estudiante->estudiante_id;

                // --- REPRESENTANTE 1, SOLO ASIGNAMOS AL ESTUDIANTE, NO CREAMOS --------------------------------------------
                if ($request->filled('idRepresentante1')) {
                    // Verificar si ya existe una persona con este DNI
                    $personaRep1 = InfRepresentante::find($request->idRepresentante1);
                    InfEstudianteRepresentante::Create(
                        [
                            'estudiante_id'     => $estudiante_id,
                            'representante_id' => $request->idRepresentante1
                        ],
                        [
                            'parentesco'        => $request->parentescoRepresentante1 ?? 'Padre',
                            'es_principal'      => 1,
                            'viveConEstudiante' => 'Si'
                        ]
                    );

                    // Crear usuario del representante principal SI NO EXISTE
                    // Nota: Ahora los usuarios están relacionados con personas, no con representantes directamente
                    $usuarioExistente = Usuario::where('persona_id', $personaRep1->persona_id)->first();
                    if (!$usuarioExistente && $request->filled('correoRepresentante1')) {
                        $passwordPlano = Str::random(8);
                        Usuario::Create([
                            'username' => $request->correoRepresentante1,
                            'password_hash' => Hash::make($passwordPlano),
                            'email' => $request->correoRepresentante1,
                            'estado' => 'Activo',
                            'cambio_password_requerido' => 1,
                            'persona_id' => $personaRep1->persona_id, // Ahora usa persona_id en lugar de representante_id
                        ]);

                        Mail::to($request->correoRepresentante1)
                            ->send(new EnviarCredencialesRepresentante(
                                $request->apellidoPaternoRepresentante1,
                                $request->correoRepresentante1,
                                $passwordPlano
                            ));
                    }
                }

                // --- REPRESENTANTE 2 -----------------------------------------------
                if ($request->filled('idRepresentante2')) {
                    // Verificar si ya existe una persona con este DNI
                    InfEstudianteRepresentante::Create(
                        [
                            'estudiante_id'     => $estudiante_id,
                            'representante_id' => $request->idRepresentante2
                        ],
                        [
                            'parentesco'        => $request->parentescoRepresentante2 ?? 'Madre',
                            'es_principal'      => 1,
                            'viveConEstudiante' => 'Si'
                        ]
                    );
                }
            });

            return redirect()->route('estudiantes.index')
                ->with('success', 'El estudiante y sus representantes fueron registrados correctamente.');
        } catch (ValidationException $ve) {
            // No deberíamos llegar aquí porque validamos arriba, pero por seguridad re-lanzamos
            throw $ve;
        } catch (\Exception $e) {
            DB::rollBack();
            // Error no relacionado con validación: mostramos un error general sin romper el formato de los campos
            return back()
                ->withInput()
                ->withErrors(['error_general' => 'Error al guardar: ' . $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        $docente = InfEstudiante::findOrFail($id);

        $docente->direccion = $request->input('direccion');
        $docente->telefono = $request->input('telefono');
        $docente->email = $request->input('email');
        $docente->especialidad = $request->input('especialidad');
        $docente->fecha_contratacion = $request->input('fecha_contratacion');
        $docente->estado = $request->input('estado');

        $docente->save();

        return redirect()->back()->with('modal_success_docente', 'Los datos del Docente fueron actualizado correctamente.');
    }

    public function verificarDni(Request $request)
    {
        $dni = $request->query('dni');
        $existe = Persona::where('dni', $dni)->exists();
        return response()->json(['existe' => $existe]);
    }

    public function generarFicha($id)
    {

        $estudiante = InfEstudiante::findOrFail($id);
        $codigo = 'EDU-' . date('Y') . '-' . $estudiante->estudiante_id . '-' . strtoupper(Str::random(6));
        $url = route('estudiantes.ficha', $estudiante->estudiante_id);
        $qr = base64_encode(
            QrCode::format('svg')->size(150)->generate($url)
        );

        $representantes = InfEstudiante::findOrFail($id)->representantes;

        $pdf = PDF::loadView('ceinformacion.estudiantes.ficha', compact('estudiante', 'representantes', 'qr', 'codigo'))->setPaper('A4', 'portrait');
        return $pdf->stream($codigo . '.pdf');
    }

    public function apiIndex(Request $request)
    {
        try {
            \Log::info('InfEstudianteController::apiIndex called', $request->all());

            // Only students who have attendance records
            $query = DB::table('estudiantes')
                ->select(
                    'estudiantes.estudiante_id',
                    'personas.nombres',
                    'personas.apellidos'
                )
                ->join('personas', 'estudiantes.persona_id', '=', 'personas.id_persona')
                ->join('asistenciasasignatura', 'estudiantes.estudiante_id', '=', 'asistenciasasignatura.matricula_id')
                ->where('personas.estado', 'Activo')
                ->distinct();

            \Log::info('Base query built');

            // Apply cascading filters
            if ($request->filled('nivel_id')) {
                \Log::info('Applying nivel_id filter:', ['nivel_id' => $request->nivel_id]);
                $query->join('matriculas', 'estudiantes.estudiante_id', '=', 'matriculas.estudiante_id')
                    ->join('grados', 'matriculas.idGrado', '=', 'grados.grado_id')
                    ->where('grados.nivel_id', $request->nivel_id);
            }

            if ($request->filled('grado_id')) {
                \Log::info('Applying grado_id filter:', ['grado_id' => $request->grado_id]);
                // Check if matriculas join already exists
                $hasMatriculasJoin = collect($query->getQuery()->joins ?? [])->pluck('table')->contains('matriculas');
                if (!$hasMatriculasJoin) {
                    \Log::info('Adding matriculas join');
                    $query->join('matriculas', 'estudiantes.estudiante_id', '=', 'matriculas.estudiante_id');
                }
                $query->where('matriculas.idGrado', $request->grado_id);
                \Log::info('Grado filter applied');
            }

            if ($request->filled('seccion_id')) {
                \Log::info('Applying seccion_id filter:', ['seccion_id' => $request->seccion_id]);
                $hasMatriculasJoin = collect($query->getQuery()->joins ?? [])->pluck('table')->contains('matriculas');
                if (!$hasMatriculasJoin) {
                    $query->join('matriculas', 'estudiantes.estudiante_id', '=', 'matriculas.estudiante_id');
                }
                $query->where('matriculas.idSeccion', $request->seccion_id);
            }

            if ($request->filled('docente_id')) {
                \Log::info('Applying docente_id filter:', ['docente_id' => $request->docente_id]);
                $query->join('cursoasignaturas', 'asistenciasasignatura.curso_asignatura_id', '=', 'cursoasignaturas.curso_asignatura_id')
                    ->where('cursoasignaturas.profesor_id', $request->docente_id);
            }

            if ($request->filled('asignatura_id')) {
                \Log::info('Applying asignatura_id filter:', ['asignatura_id' => $request->asignatura_id]);
                $hasCursoAsignaturasJoin = collect($query->getQuery()->joins ?? [])->pluck('table')->contains('cursoasignaturas');
                if (!$hasCursoAsignaturasJoin) {
                    $query->join('cursoasignaturas', 'asistenciasasignatura.curso_asignatura_id', '=', 'cursoasignaturas.curso_asignatura_id');
                }
                $query->where('cursoasignaturas.asignatura_id', $request->asignatura_id);
            }

            \Log::info('About to execute query');
            $estudiantes = $query->orderBy('personas.apellidos')
                ->orderBy('personas.nombres')
                ->get();

            \Log::info('Query executed, results count:', ['count' => $estudiantes->count()]);

            $estudiantes = $estudiantes->map(function ($estudiante) {
                return [
                    'id' => $estudiante->estudiante_id,
                    'nombres' => $estudiante->nombres ?? 'Sin nombre',
                    'apellidos' => $estudiante->apellidos ?? 'Sin apellidos'
                ];
            });

            \Log::info('Mapped results count:', ['count' => count($estudiantes)]);
            return response()->json($estudiantes);
        } catch (\Exception $e) {
            \Log::error('Error in InfEstudianteController::apiIndex: ' . $e->getMessage());
            \Log::error('Request params:', $request->all());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }
}
