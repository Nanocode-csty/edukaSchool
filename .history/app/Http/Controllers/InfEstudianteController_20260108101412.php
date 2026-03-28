<?php

namespace App\Http\Controllers;

use App\Mail\EnviarCredencialesRepresentante;
use App\Models\InfEstudiante;
use App\Models\InfEstudianteRepresentante;
use App\Models\InfRepresentante;
use App\Models\Usuario;
use Barryvdh\DomPDF\Facade\Pdf;
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

        $estudiante = InfEstudiante::whereHas('persona', function ($query) use ($buscarpor) {
            $query->where('dni', 'like', '%' . $buscarpor . '%')
                ->orWhere('apellidos', 'like', '%' . $buscarpor . '%')
                ->where('estado', 'Activo');
        })
            ->with('persona')
            ->orderByRaw('(SELECT apellidos FROM personas WHERE personas.id_persona = estudiantes.persona_id) ASC')
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



    public function show(InfEstudiante $estudiante)
    {
        return view('ceinformacion.estudiantes.show', compact('estudiante'));
    }

    public function store(Request $request)
{
    // 1) REGLAS DE VALIDACIÓN
    $rules = [
        // Estudiante (obligatorios)
        'dniEstudiante' => 'required|digits:8|unique:personas,dni',
        'numeroCelularEstudiante' => ['required', 'digits:9'],
        'nombreEstudiante' => ['required', 'string', 'max:100', 'min:2'],
        'apellidoPaternoEstudiante' => ['required', 'string', 'max:100', 'min:2'],
        'apellidoMaternoEstudiante' => ['required', 'string', 'max:100', 'min:2'],
        'generoEstudiante' => ['required', 'in:M,F'],
        'fechaNacimientoEstudiante' => ['required', 'date', 'before:' . now()->subYears(5)->format('Y-m-d')],
        'regionEstudiante' => ['required'],
        'provinciaEstudiante' => ['required'],
        'distritoEstudiante' => ['required'],
        'calleEstudiante' => ['required', 'string', 'max:100', 'min:2'],
        'correoEstudiante' => 'required|email|max:100',
        'numeroEstudiante' => 'nullable|string|max:10',
        'urbanizacionEstudiante' => 'nullable|string|max:100',
        'referenciaEstudiante' => 'nullable|string|max:255',
        'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ];

    // 2) Reglas condicionales para REPRESENTANTE 1
    // Si el usuario completa alguno de los campos del representante, entonces validamos el conjunto mínimo.
    if ($request->filled('dniRepresentante1') || $request->filled('nombreRepresentante1') || $request->filled('correoRepresentante1')) {
        $rules = array_merge($rules, [
            'dniRepresentante1' => 'required|digits:8',
            'nombreRepresentante1' => 'required|string|min:2|max:100',
            'apellidoPaternoRepresentante1' => 'nullable|string|max:100',
            'apellidoMaternoRepresentante1' => 'nullable|string|max:100',
            'celularRepresentante1' => 'nullable|digits:9',
            'celularAlternativoRepresentante1' => 'nullable|digits:9',
            'correoRepresentante1' => 'nullable|email|max:100',
            'ocupacionRepresentante1' => 'nullable|string|max:100',
            'direccionRepresentante1' => 'nullable|string|max:255',
            'parentescoRepresentante1' => 'nullable|string|max:50',
        ]);
    } else {
        // si no puso nada del representante 1, permitimos que todo sea nullable (no añadido)
    }

    // 3) Reglas condicionales para REPRESENTANTE 2 (mismo criterio)
    if ($request->filled('dniRepresentante2') || $request->filled('nombreRepresentante2') || $request->filled('correoRepresentante2')) {
        $rules = array_merge($rules, [
            'dniRepresentante2' => 'required|digits:8',
            'nombreRepresentante2' => 'required|string|min:2|max:100',
            'apellidoPaternoRepresentante2' => 'nullable|string|max:100',
            'apellidoMaternoRepresentante2' => 'nullable|string|max:100',
            'celularRepresentante2' => 'nullable|digits:9',
            'celularAlternativoRepresentante2' => 'nullable|digits:9',
            'correoRepresentante2' => 'nullable|email|max:100',
            'ocupacionRepresentante2' => 'nullable|string|max:100',
            'direccionRepresentante2' => 'nullable|string|max:255',
            'parentescoRepresentante2' => 'nullable|string|max:50',
        ]);
    }

    // 4) Mensajes personalizados (puedes añadir más si lo deseas)
    $messages = [
        'dniEstudiante.required' => 'Ingrese N.° de DNI',
        'dniEstudiante.digits' => 'El DNI debe tener exactamente 8 dígitos.',
        'dniEstudiante.unique' => 'Este DNI ya está registrado.',
        'nombreEstudiante.required' => 'Ingrese nombre(s)',
        'apellidoPaternoEstudiante.required' => 'Ingrese apellido paterno.',
        'apellidoMaternoEstudiante.required' => 'Ingrese apellido materno.',
        'generoEstudiante.required' => 'Seleccione el género del estudiante.',
        'fechaNacimientoEstudiante.required' => 'Ingrese la fecha de nacimiento.',
        'fechaNacimientoEstudiante.before' => 'La fecha de nacimiento no es válida.',
        'calleEstudiante.required' => 'Ingrese Avenida o Calle',
        'numeroCelularEstudiante.required' => 'Ingrese N.° de celular',
        'numeroCelularEstudiante.digits' => 'El N.° debe tener exactamente 9 dígitos.',
        'correoEstudiante.required' => 'Ingrese la dirección de correo electrónico',
        'correoEstudiante.email' => 'Ingrese una dirección de correo válida',
    ];

    // 5) Ejecutar validación: esto lanzará ValidationException y Laravel REDIRECCIONARÁ
    // con los errores por campo para que se muestren debajo de cada input (como antes).
    $validator = Validator::make($request->all(), $rules, $messages);
    $validator->validate(); // si falla, no continuará y Laravel mostrará errores por campo

    // 6) Si validación OK, continuamos con la transacción de DB
    try {
        DB::beginTransaction();

        // --- CREAR/GUARDAR PERSONA ---
        $dniBuscar = trim($request->dniEstudiante);

        // doble-check por seguridad (aunque la regla unique ya lo validó)
        $personaExistente = \App\Models\Persona::where('dni', $dniBuscar)->first();
        if ($personaExistente) {
            // devolver error específico por campo
            return back()->withInput()->withErrors(['dniEstudiante' => 'El estudiante ya está registrado.']);
        }

        $partesDireccion = array_filter([
            $request->calleEstudiante,
            $request->numeroEstudiante,
            $request->urbanizacionEstudiante,
            $request->distritoEstudiante,
            $request->provinciaEstudiante,
            $request->regionEstudiante,
            $request->referenciaEstudiante,
        ]);

        // Handle foto upload first
        $nombreFoto = null;
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $nombreFoto = $dniBuscar . '.' . $foto->getClientOriginalExtension();
            $foto->storeAs('estudiantes', $nombreFoto, 'public');
        }

        $persona = new \App\Models\Persona();
        $persona->dni = $dniBuscar;
        $persona->nombres = $request->nombreEstudiante;
        $persona->apellidos = $request->apellidoPaternoEstudiante . ' ' . $request->apellidoMaternoEstudiante;
        $persona->fecha_nacimiento = $request->fechaNacimientoEstudiante;
        $persona->genero = $request->generoEstudiante;
        $persona->direccion = implode(', ', $partesDireccion);
        $persona->telefono = $request->numeroCelularEstudiante;
        $persona->email = $request->correoEstudiante;
        $persona->estado = 'Activo';
        // Note: foto_url column may not exist in personas table based on current database structure
        $persona->save();

        // --- CREAR ESTUDIANTE ---
        $estudiante = new InfEstudiante();
        $estudiante->persona_id = $persona->id_persona;
        $estudiante->codigo_estudiante = 'EST' . str_pad($persona->id_persona, 6, '0', STR_PAD_LEFT);
        $estudiante->fecha_matricula = now();

        $estudiante->save();
        $estudiante_id = $estudiante->estudiante_id;

        // --- REPRESENTANTE 1 ---
        if ($request->filled('dniRepresentante1')) {
            // Verificar si ya existe una persona con este DNI
            $personaRepExistente1 = \App\Models\Persona::where('dni', $request->dniRepresentante1)->first();
            if ($personaRepExistente1) {
                // Si existe la persona, buscar si ya es representante
                $rep1 = InfRepresentante::where('persona_id', $personaRepExistente1->id_persona)->first();
                if ($rep1) {
                    $representante1_id = $rep1->representante_id;
                } else {
                    // La persona existe pero no es representante, crear registro de representante
                    $nuevo1 = InfRepresentante::create([
                        'persona_id' => $personaRepExistente1->id_persona,
                        'ocupacion' => $request->ocupacionRepresentante1,
                        'parentesco' => $request->parentescoRepresentante1,
                        'fecha_registro' => now()
                    ]);
                    $representante1_id = $nuevo1->representante_id;
                }
            } else {
                // Create persona for representante
                $personaRep1 = new \App\Models\Persona();
                $personaRep1->dni = $request->dniRepresentante1;
                $personaRep1->nombres = $request->nombreRepresentante1;
                $personaRep1->apellidos = ($request->apellidoPaternoRepresentante1 ?? '') . ' ' . ($request->apellidoMaternoRepresentante1 ?? '');
                $personaRep1->telefono = $request->celularRepresentante1;
                // Note: telefono_alternativo column may not exist in personas table
                $personaRep1->email = $request->correoRepresentante1;
                $personaRep1->direccion = $request->direccionRepresentante1 ?? '';
                $personaRep1->estado = 'Activo';
                $personaRep1->save();

                // Create representante record
                $nuevo1 = InfRepresentante::create([
                    'persona_id' => $personaRep1->id_persona,
                    'ocupacion' => $request->ocupacionRepresentante1,
                    'parentesco' => $request->parentescoRepresentante1,
                    'fecha_registro' => now()
                ]);
                $representante1_id = $nuevo1->representante_id;
            }

            InfEstudianteRepresentante::updateOrCreate(
                ['estudiante_id' => $estudiante_id, 'representante_id' => $representante1_id],
                ['es_principal' => 1, 'viveConEstudiante' => 'Si']
            );

            // Crear usuario del representante principal si no existe
            // Nota: Ahora los usuarios están relacionados con personas, no con representantes directamente
            $usuarioExistente = Usuario::where('persona_id', $personaRep1->id_persona)->first();
            if (!$usuarioExistente && $request->filled('correoRepresentante1')) {
                $passwordPlano = Str::random(8);
                Usuario::create([
                    'username' => $request->correoRepresentante1,
                    'password_hash' => Hash::make($passwordPlano),
                    'nombres' => $request->nombreRepresentante1,
                    'apellidos' => ($request->apellidoPaternoRepresentante1 ?? '') . ' ' . ($request->apellidoMaternoRepresentante1 ?? ''),
                    'email' => $request->correoRepresentante1,
                    'rol' => 'Representante',
                    'estado' => 'Activo',
                    'cambio_password_requerido' => 1,
                    'persona_id' => $personaRep1->id_persona, // Ahora usa persona_id en lugar de representante_id
                ]);

                Mail::to($request->correoRepresentante1)
                    ->send(new EnviarCredencialesRepresentante(
                        $request->nombreRepresentante1,
                        $request->correoRepresentante1,
                        $passwordPlano
                    ));
            }
        }

        // --- REPRESENTANTE 2 ---
        if ($request->filled('dniRepresentante2')) {
            // Verificar si ya existe una persona con este DNI
            $personaRepExistente2 = \App\Models\Persona::where('dni', $request->dniRepresentante2)->first();
            if ($personaRepExistente2) {
                // Si existe la persona, buscar si ya es representante
                $rep2 = InfRepresentante::where('persona_id', $personaRepExistente2->id_persona)->first();
                if ($rep2) {
                    $representante2_id = $rep2->representante_id;
                } else {
                    // La persona existe pero no es representante, crear registro de representante
                    $nuevo2 = InfRepresentante::create([
                        'persona_id' => $personaRepExistente2->id_persona,
                        'ocupacion' => $request->ocupacionRepresentante2,
                        'parentesco' => $request->parentescoRepresentante2,
                        'fecha_registro' => now()
                    ]);
                    $representante2_id = $nuevo2->representante_id;
                }
            } else {
                // Create persona for representante
                $personaRep2 = new \App\Models\Persona();
                $personaRep2->dni = $request->dniRepresentante2;
                $personaRep2->nombres = $request->nombreRepresentante2;
                $personaRep2->apellidos = ($request->apellidoPaternoRepresentante2 ?? '') . ' ' . ($request->apellidoMaternoRepresentante2 ?? '');
                $personaRep2->telefono = $request->celularRepresentante2;
                // Note: telefono_alternativo column may not exist in personas table
                $personaRep2->email = $request->correoRepresentante2;
                $personaRep2->direccion = $request->direccionRepresentante2 ?? '';
                $personaRep2->estado = 'Activo';
                $personaRep2->save();

                // Create representante record
                $nuevo2 = InfRepresentante::create([
                    'persona_id' => $personaRep2->id_persona,
                    'ocupacion' => $request->ocupacionRepresentante2,
                    'parentesco' => $request->parentescoRepresentante2,
                    'fecha_registro' => now()
                ]);
                $representante2_id = $nuevo2->representante_id;
            }

            InfEstudianteRepresentante::updateOrCreate(
                ['estudiante_id' => $estudiante_id, 'representante_id' => $representante2_id],
                ['es_principal' => 0, 'viveConEstudiante' => 'Si']
            );
        }

        DB::commit();


        return redirect()->route('estudiante.index')
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

    public function verificarDni(Request $request)
    {
        $dni = $request->query('dni');
        $existe = InfEstudiante::whereHas('persona', function($query) use ($dni) {
            $query->where('dni', $dni);
        })->exists();
        return response()->json(['existe' => $existe]);
    }

    public function generarFicha($id)
    {
        $estudiante = InfEstudiante::findOrFail($id);

        $representantes = DB::table('estudianterepresentante as er')
            ->join('representantes as r', 'er.representante_id', '=', 'r.representante_id')
            ->where('er.estudiante_id', $id)
            ->select('r.*')
            ->get();

        $pdf = PDF::loadView('ceinformacion.estudiantes.ficha', compact('estudiante', 'representantes'))->setPaper('A4', 'portrait');
        return $pdf->stream('ficha_estudiante.pdf');
    }

    public function apiIndex(Request $request)
    {
        try {
            $query = DB::table('estudiantes')
                ->select(
                    'estudiantes.estudiante_id',
                    'personas.nombres',
                    'personas.apellidos'
                )
                ->join('personas', 'estudiantes.persona_id', '=', 'personas.id_persona')
                ->where('personas.estado', 'Activo'); // Active persons

            // Filter by seccion if provided (cascading)
            if ($request->filled('seccion_id')) {
                $query->join('matriculas', 'estudiantes.estudiante_id', '=', 'matriculas.estudiante_id')
                      ->where('matriculas.idSeccion', $request->seccion_id)
                      ->where('matriculas.estado', 'Matriculado'); // Only enrolled students for section filter
            }

            $estudiantes = $query->orderBy('personas.apellidos')
                ->orderBy('personas.nombres')
                ->get()
                ->map(function($estudiante) {
                    return [
                        'id' => $estudiante->estudiante_id,
                        'nombres' => $estudiante->nombres ?? 'Sin nombre',
                        'apellidos' => $estudiante->apellidos ?? 'Sin apellidos'
                    ];
                });

            return response()->json($estudiantes);
        } catch (\Exception $e) {
            \Log::error('Error in InfEstudianteController::apiIndex: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }
}
