<?php

namespace App\Http\Controllers;

use App\Mail\EnviarCredencialesDocente;
use App\Models\InfDocente;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class InfDocenteController extends Controller
{
    const PAGINATION = 10;


    public function index(Request $request)
    {
        $buscarpor = $request->get('buscarpor');

        $docente = InfDocente::where(function ($query) use ($buscarpor) {
            $query->where('dni', 'like', '%' . $buscarpor . '%')
                ->orWhere('apellidos', 'like', '%' . $buscarpor . '%');
        })
            ->where('estado', '=', 'Activo')
            ->orderBy('apellidos', 'asc')
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

    public function store(Request $request)
    {

        $data = request()->validate(
            [
                'dni' => 'required|digits:8|unique:profesores,dni',
                'nombres' => 'required|max:100|min:2',
                'apellidoPaterno' => 'required|max:100|min:2',
                'apellidoMaterno' => 'required|max:100|min:2',
                'fecha_nacimiento' => ['required', 'date', 'before:' . now()->subYears(20)->format('Y-m-d')],
                'genero' => ['required', 'in:M,F'],
                'direccion' => 'required|max:255|min:20',
                'telefono' => ['required', 'digits:9'],
                'email' => 'required|email|max:100|unique:profesores,email',
                'especialidad' => 'required|max:100|min:2',
                'fecha_contratacion' => ['required', 'date'],
                'foto_url' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            ],
            [
                'dni.required' => 'Ingrese el DNI del Docente',
                'dni.max' => 'Ingrese un DNI correcto',
                'dni.min' => 'Ingrese un DNI correcto',
                'dni.unique' => 'El N.° de DNI ya está asociado a un docente.',
                'nombres.required' => 'Ingrese el Nombre del Docente',
                'nombres.max' => 'Ingrese un nombre correcto',
                'nombres.min' => 'Ingrese un nombre correcto',
                'especialidad.required' => 'Ingrese la especialidad del Docente',
                'especialidad.max' => 'Ingrese una especialidad correcta',
                'especialidad.min' => 'Ingrese una especialidad correcta',
                'apellidoPaterno.required' => 'Ingrese el Apellido Paterno del Docente',
                'apellidoPaterno.max' => 'Ingrese un apellido correcto',
                'apellidoPaterno.min' => 'Ingrese un apellido correcto',
                'apellidoMaterno.required' => 'Ingrese el Apellido Materno del Docente',
                'apellidoMaterno.max' => 'Ingrese un apellido correcto',
                'apellidoMaterno.min' => 'Ingrese un apellido correcto',
                'fecha_nacimiento.required' => 'Ingrese la fecha de nacimiento del Docente',
                'fecha_nacimiento.before' => 'La fecha de nacimiento no es válida (min. 20 años).',
                'fecha_contratacion.required' => 'Ingrese la fecha de contratación del Docente',
                'fecha_contratacion.date' => 'La fecha de contratación no es válida (min. 20 años).',
                'genero.required' => 'Seleccione el género del docente.',
                'genero.in' => 'Seleccione el género válido.',
                'direccion.required' => 'Ingrese la direccion del Docente',
                'direccion.max' => 'Ingrese una direccion adecuada',
                'direccion.min' => 'Ingrese una direccion adecuada',
                'telefono.required' => 'Ingrese el numero de celular del docente',
                'telefono.digits' => 'El N.° debe tener exactamente 9 dígitos.',
                'email.required' => 'Ingrese la direccion de correo del Docente',
                'email.max' => 'Ingrese una direccion de correo adecuada (máx. 200)',
                'email.email' => 'Ingrese una dirección de correo válida',
                'email.unique' => 'Esta dirección de correo electrónico ya está asociada a un docente en el sistema.',


            ]
        );

        $dniBuscar = trim($request->dni);
        $dniDocente = trim($request->dni);

        // Buscar si ya existe un Docente registrado con ese dni. TRIM: elimina espacios en blanco al inicio y al final
        $docenteRegistrado = InfDocente::whereRaw('LOWER(TRIM(dni)) = ?', [strtolower($dniBuscar)])->first();

        if ($docenteRegistrado) {

            return back()->withErrors([
                'dni' => 'El Docente ya está registrado.',
            ])->withInput();
        }

        $apellidos = $request->apellidoPaterno . " " . $request->apellidoMaterno;

        $docente = new InfDocente();

        $docente->dni = $dniBuscar;
        $docente->nombres = $request->nombres;
        $docente->apellidos = $apellidos;
        $docente->fecha_nacimiento = $request->fecha_nacimiento;
        $docente->genero = $request->genero;
        $docente->direccion = $request->direccion;
        $docente->telefono = $request->telefono;
        $docente->email = $request->email;
        $docente->especialidad = $request->especialidad;
        $docente->fecha_contratacion = $request->fecha_contratacion;

        if ($request->hasFile('foto_url')) {
            $foto = $request->file('foto_url');
            $nombreFoto = $dniBuscar . '.' . $foto->getClientOriginalExtension();
            // Guarda en storage/app/public/fotos, disco 'public'
            $foto->storeAs('fotos', $nombreFoto, 'public');
            $docente->foto_url = $nombreFoto;
        }

        $docente->save();


         $existente2 = InfDocente::where('dni', $request->dni)->first();


        //Si el docente no está registrado, pasamos a enviar las credenciales de acceso
        $idDocenteRegistrado = $existente2->profesor_id;
        $AnadirUsuario = $idDocenteRegistrado;
        $usuarioExistente = Usuario::where('profesor_id', $idDocenteRegistrado)->first();

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
                'profesor_id' => $AnadirUsuario,
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

        return redirect()->route('docente.index')
            ->with('success', 'Docente registrado con éxito');
        //return redirect()->route('producto.index')->with('datos','Producto Nuevo Guardado...!');

        /*
        return redirect()
            ->route('registrorepresentanteDocente.index')
            ->with([
                'dniBuscar' => $dniBuscar,
                'success' => 'Docente registrado satisfactoriamente'
        ]);*/
    }


    public function verificarDniDocente(Request $request)
    {
        $dni = $request->query('dni');
        $existe = InfDocente::where('dni', $dni)->exists();
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
}
