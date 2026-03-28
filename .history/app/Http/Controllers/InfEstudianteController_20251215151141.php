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

        $estudiante = InfEstudiante::where(function ($query) use ($buscarpor) {
            $query->where('dni', 'like', '%' . $buscarpor . '%')
                ->orWhere('apellidos', 'like', '%' . $buscarpor . '%');
        })
            ->where('estado', 'Activo')
            ->orderBy('apellidos', 'asc')
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



    /*
    public function store(Request $request)
    {
        $data = request()->validate([
            'dniEstudiante' => 'required|digits:8|unique:estudiantes,dni',
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

        ], [
            'dniEstudiante.required' => 'Ingrese N.° de DNI',
            'dniEstudiante.digits' => 'El DNI debe tener exactamente 8 dígitos.',
            'dniEstudiante.unique' => 'Este DNI ya está registrado.',

            'nombreEstudiante.required' => 'Ingrese nombre(s)',
            'nombreEstudiante.min' => 'Debe ingresar al menos 2 caracteres.',

            'apellidoPaternoEstudiante.required' => 'Ingrese apellido paterno.',
            'apellidoMaternoEstudiante.required' => 'Ingrese apellido materno.',

            'generoEstudiante.required' => 'Seleccione el género del estudiante.',
            'generoEstudiante.in' => 'Seleccione un género válido.',

            'fechaNacimientoEstudiante.required' => 'Ingrese la fecha de nacimiento.',
            'fechaNacimientoEstudiante.before' => 'La fecha de nacimiento no es válida.',

            'regionEstudiante.required' => 'Seleccione Región de procedencia',

            'provinciaEstudiante.required' => 'Seleccione Provincia de procedencia',

            'distritoEstudiante.required' => 'Seleccione Distrito de procedencia',

            'calleEstudiante.required' => 'Ingrese Avenida o Calle',
            'calleEstudiante.min' => 'Debe ingresar al menos 2 caracteres.',

            'numeroCelularEstudiante.required' => 'Ingrese N.° de celular',
            'numeroCelularEstudiante.digits' => 'El N.° debe tener exactamente 9 dígitos.',

            'correoEstudiante.required' => 'Ingrese la dirección de correo electrónico',
            'correoEstudiante.email' => 'Ingrese una dirección de correo válida',
            'correoEstudiante.max' => 'La dirección de correo es demasiado larga'
        ]);

        $partesDireccion = array_filter([
            $request->calleEstudiante,
            $request->numeroEstudiante,
            $request->urbanizacionEstudiante,
            $request->distritoEstudiante,
            $request->provinciaEstudiante,
            $request->regionEstudiante,
            $request->referenciaEstudiante,
        ]);

        $dniBuscar = trim($request->dniEstudiante);

        // Buscar si ya existe un estudiante registrado con ese dni. TRIM: elimina espacios en blanco al inicio y al final
        $estudianteRegistrado = InfEstudiante::whereRaw('LOWER(TRIM(dni)) = ?', [strtolower($dniBuscar)])->first();

        if ($estudianteRegistrado) {

            return back()->withErrors([
                'dniEstudiante' => 'El estudiante ya está registrado.',
            ])->withInput();
        }

        $apellidoEstudiante = $request->apellidoPaternoEstudiante . " " . $request->apellidoMaternoEstudiante;
        $estudiante = new InfEstudiante();

        $estudiante->dni = $dniBuscar;
        $estudiante->nombres = $request->nombreEstudiante;
        $estudiante->apellidos = $apellidoEstudiante;
        $estudiante->fecha_nacimiento = $request->fechaNacimientoEstudiante;
        $estudiante->genero = $request->generoEstudiante;
        $estudiante->direccion = implode(', ', $partesDireccion);
        $estudiante->telefono = $request->numeroCelularEstudiante;
        $estudiante->email = $request->correoEstudiante;
        $estudiante->fecha_registro = now();

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $nombreFoto = $dniBuscar . '.' . $foto->getClientOriginalExtension();
            // Guarda en storage/app/public/fotos, disco 'public'
            $foto->storeAs('estudiantes', $nombreFoto, 'public');
            $estudiante->foto_url = $nombreFoto;
        }

        $estudiante->save();


        $estudiante = InfEstudiante::where('dni', $dniBuscar)->first();

        if ($estudiante) {
            // Guardar variables separadas en sesión
            session([
                'idEstudiante' => $estudiante->estudiante_id,
                'dniEstudiante' => $estudiante->dni,
                'nombresCompletosEstudiante' => $estudiante->apellidos . ' ' . $estudiante->nombres,
            ]);

            return redirect()->route('registrorepresentanteestudiante.index')
                ->with('success', 'El estudiante ha sido registrado. Asignar a sus Representantes legales.');
        }

        // Si no se encontró el estudiante, limpiar sesión y mostrar error
        session()->forget(['idEstudiante', 'dniEstudiante', 'nombresCompletosEstudiante']);
        return redirect()->back()->with('error', 'No se encontró al estudiante con ese DNI');
        /*
        return redirect()
            ->route('registrorepresentanteestudiante.index')
            ->with([
                'dniBuscar' => $dniBuscar,
                'success' => 'Estudiante registrado satisfactoriamente'
        ]);
    } */
public function store(Request $request)
{
    // 1) REGLAS DE VALIDACIÓN
    $rules = [
        // Estudiante (obligatorios)
        'dniEstudiante' => 'required|digits:8|unique:estudiantes,dni',
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

        // --- CREAR/GUARDAR ESTUDIANTE ---
        $dniBuscar = trim($request->dniEstudiante);

        // doble-check por seguridad (aunque la regla unique ya lo validó)
        $existente = InfEstudiante::whereRaw('LOWER(TRIM(dni)) = ?', [strtolower($dniBuscar)])->first();
        if ($existente) {
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

        $estudiante = new InfEstudiante();
        $estudiante->dni = $dniBuscar;
        $estudiante->nombres = $request->nombreEstudiante;
        $estudiante->apellidos = $request->apellidoPaternoEstudiante . ' ' . $request->apellidoMaternoEstudiante;
        $estudiante->fecha_nacimiento = $request->fechaNacimientoEstudiante;
        $estudiante->genero = $request->generoEstudiante;
        $estudiante->direccion = implode(', ', $partesDireccion);
        $estudiante->telefono = $request->numeroCelularEstudiante;
        $estudiante->email = $request->correoEstudiante;
        $estudiante->fecha_registro = now();

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $nombreFoto = $dniBuscar . '.' . $foto->getClientOriginalExtension();
            $foto->storeAs('estudiantes', $nombreFoto, 'public');
            $estudiante->foto_url = $nombreFoto;
        }

        $estudiante->save();
        $estudiante_id = $estudiante->estudiante_id;

        // --- REPRESENTANTE 1 ---
        if ($request->filled('dniRepresentante1')) {
            $rep1 = InfRepresentante::where('dni', $request->dniRepresentante1)->first();
            if ($rep1) {
                $representante1_id = $rep1->representante_id;
            } else {
                $nuevo1 = InfRepresentante::create([
                    'dni' => $request->dniRepresentante1,
                    'nombres' => $request->nombreRepresentante1,
                    'apellidoPaterno' => $request->apellidoPaternoRepresentante1,
                    'apellidoMaterno' => $request->apellidoMaternoRepresentante1,
                    'telefono' => $request->celularRepresentante1,
                    'telefono_alternativo' => $request->celularAlternativoRepresentante1,
                    'email' => $request->correoRepresentante1,
                    'ocupacion' => $request->ocupacionRepresentante1,
                    'direccion' => $request->direccionRepresentante1,
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
            $usuarioExistente = Usuario::where('representante_id', $representante1_id)->first();
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
                    'representante_id' => $representante1_id,
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
            $rep2 = InfRepresentante::where('dni', $request->dniRepresentante2)->first();
            if ($rep2) {
                $representante2_id = $rep2->representante_id;
            } else {
                $nuevo2 = InfRepresentante::create([
                    'dni' => $request->dniRepresentante2,
                    'nombres' => $request->nombreRepresentante2,
                    'apellidoPaterno' => $request->apellidoPaternoRepresentante2,
                    'apellidoMaterno' => $request->apellidoMaternoRepresentante2,
                    'telefono' => $request->celularRepresentante2,
                    'telefono_alternativo' => $request->celularAlternativoRepresentante2,
                    'email' => $request->correoRepresentante2,
                    'ocupacion' => $request->ocupacionRepresentante2,
                    'direccion' => $request->direccionRepresentante2,
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
        $existe = InfEstudiante::where('dni', $dni)->exists();
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
}
