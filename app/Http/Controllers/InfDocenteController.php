<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDocenteRequest;
use App\Mail\EnviarCredencialesDocente;
use App\Models\DireccionPersona;
use App\Models\InfDocente;
use App\Models\Usuario;
use Illuminate\Http\Request;
use App\Models\Persona;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InfDocenteController extends Controller
{
    const PAGINATION = 10;


    public function index(Request $request)
    {
        $buscarpor = $request->get('buscarpor');

        $docente = InfDocente::whereHas('persona', function ($q) use ($buscarpor) {
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

        // Si es AJAX, devuelve solo el contenido (como para paginación)
        if ($request->ajax()) {
            return view('ceinformacion.docentes.docente', compact('docente'))->render();
        }

        return view('ceinformacion.docentes.registrar', compact('docente', 'buscarpor'));
    }


    public function create()
    {
        return view('ceinformacion.docentes.nuevo'); // para cuando se accede normalment
    }

    public function store(StoreDocenteRequest $request)
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

                //CREAMOS AL DOCENTE
                $docente = InfDocente::create([
                    'persona_id'  => $persona->id_persona,
                    'especialidad' => $validated['especialidad'],
                    'fecha_contratacion' => $validated['fecha_contratacion'],
                    'tipo_contrato' => $validated['tipo_contrato'],
                ]);


                $usuarioExistente = Usuario::where('profesor_id', $docente->profesor_id)->first();

                if (!$usuarioExistente) {
                    $passwordPlano = Str::random(8); //creamos una contraseña aleatoria

                    //creamos al usuario en la tabla correspondiente (Usuarios)
                    Usuario::create([
                        'username' => $request->email,
                        'password_hash' => Hash::make($passwordPlano),
                        'nombres' => $request->nombres,
                        'apellidos' => $request->apellidoPaterno . ' ' . $request->apellidoMaterno,
                        'email' => $request->email,
                        'rol' => 'Profesor',
                        'estado' => 'Activo',
                        'cambio_password_requerido' => 1,
                        'profesor_id' => $docente->profesor_id,
                    ]);

                    // Enviar correo con credenciales usando el Mailable
                    Mail::to($request->email)
                        //Llamamos a la clase que envía el correo, definido corresctamente
                        ->send(new EnviarCredencialesDocente(
                            $request->nombres,
                            $request->email,
                            $passwordPlano
                        ));
                }
            });

            return redirect()->route('docente.index')
                ->with('success', 'Docente registrado satisfactoriamente.');

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


    public function verificarDniDocente(Request $request)
    {
        $dni = $request->query('dni');
        $existe = DB::table('personas')->where('dni', $dni)->exists();
        return response()->json(['existe' => $existe]);
    }

    public function verificarCorreoDocente(Request $request)
    {
        $email = $request->query('email');
        $existe = DB::table('personas')->where('email', $email)->exists();
        return response()->json(['existe' => $existe]);
    }

    public function edit($id)
    {
        $docente = InfDocente::findOrFail($id);
        return view('ceinformacion.docentes.editar', compact('docente'));
    }

    public function update(Request $request, $id)
    {
        $docente = InfDocente::findOrFail($id);

        $docente->direccion = $request->input('direccion');
        $docente->telefono = $request->input('telefono');
        $docente->email = $request->input('email');
        $docente->especialidad = $request->input('especialidad');
        $docente->fecha_contratacion = $request->input('fecha_contratacion');
        $docente->estado = $request->input('estado');

        $docente->save();

        return redirect()->back()->with('modal_success_docente', 'Los datos del Docente fueron actualizado correctamente.');
    }

    public function destroy($id)
    {
        $docente = InfDocente::findOrFail($id);
        $docente->estado = 'Inactivo';
        $docente->save();

        return redirect()->back()->with('modal_success_docente', 'Docente eliminado correctamente.');
    }

    public function apiIndex(Request $request)
    {
        try {
            $query = InfDocente::select('profesores.profesor_id as id', 'personas.nombres', 'personas.apellidos')
                ->join('personas', 'profesores.persona_id', '=', 'personas.id_persona')
                ->join('cursoasignaturas', 'profesores.profesor_id', '=', 'cursoasignaturas.profesor_id')
                ->join('asistenciasasignatura', 'cursoasignaturas.curso_asignatura_id', '=', 'asistenciasasignatura.curso_asignatura_id')
                ->where('personas.estado', 'Activo')
                ->distinct();

            // Apply cascading filters
            if ($request->filled('nivel_id')) {
                $query->join('matriculas', 'asistenciasasignatura.matricula_id', '=', 'matriculas.estudiante_id')
                    ->join('grados', 'matriculas.idGrado', '=', 'grados.grado_id')
                    ->where('grados.nivel_id', $request->nivel_id);
            }

            if ($request->filled('grado_id')) {
                if (!$query->getQuery()->joins || !collect($query->getQuery()->joins)->pluck('table')->contains('matriculas')) {
                    $query->join('matriculas', 'asistenciasasignatura.matricula_id', '=', 'matriculas.estudiante_id');
                }
                $query->where('matriculas.idGrado', $request->grado_id);
            }

            if ($request->filled('seccion_id')) {
                if (!$query->getQuery()->joins || !collect($query->getQuery()->joins)->pluck('table')->contains('matriculas')) {
                    $query->join('matriculas', 'asistenciasasignatura.matricula_id', '=', 'matriculas.estudiante_id');
                }
                $query->where('matriculas.idSeccion', $request->seccion_id);
            }

            if ($request->filled('estudiante_id')) {
                $query->where('asistenciasasignatura.matricula_id', $request->estudiante_id);
            }

            if ($request->filled('asignatura_id')) {
                $query->where('cursoasignaturas.asignatura_id', $request->asignatura_id);
            }

            $docentes = $query->orderBy('personas.apellidos')
                ->orderBy('personas.nombres')
                ->get()
                ->map(function ($docente) {
                    return [
                        'id' => $docente->id,
                        'nombres' => $docente->nombres,
                        'apellidos' => $docente->apellidos
                    ];
                });

            return response()->json($docentes);
        } catch (\Exception $e) {
            \Log::error('Error in InfDocenteController::apiIndex: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }
}
