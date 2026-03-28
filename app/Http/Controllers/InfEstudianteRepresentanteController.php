<?php

namespace App\Http\Controllers;

use App\Models\InfEstudianteRepresentante;
use Illuminate\Http\Request;


class InfEstudianteRepresentanteController extends Controller
{
    const PAGINATION = 6;

    public function index(Request $request)
    {
        $buscarpor = $request->get('buscarpor');
        $estudianteRepresentante=InfEstudianteRepresentante::where('estudiante_id','like','%'.$buscarpor.'%')->orwhere('representante_id','like','%'.$buscarpor.'%')->paginate($this::PAGINATION);

        return view('ceinformacion.estudianteRepresentante.registrar',compact('estudianteRepresentante','buscarpor'));
    }

    public function store(Request $request)
    {
        $data=request()->validate([
            'dniEstudiante'=>'required|max:8|min:8'
        ],
        [
            'dniEstudiante.required'=>'Ingrese el DNI del Estudiante',
            'dniEstudiante.max'=>'Ingrese un DNI correcto',
            'dniEstudiante.min'=>'Ingrese un DNI correcto'
        ]);

        $data=request()->validate([
            'nombreEstudiante'=>'required|max:100|min:4'
        ],
        [
            'nombreEstudiante.required'=>'Ingrese el Nombre del Estudiante',
            'nombreEstudiante.max'=>'Ingrese un nombre correcto',
            'nombreEstudiante.min'=>'Ingrese un nombre correcto'
        ]);

        $data=request()->validate([
            'apellidoEstudiante'=>'required|max:100|min:4'
        ],
        [
            'apellidoEstudiante.required'=>'Ingrese el Apellido del Estudiante',
            'apellidoEstudiante.max'=>'Ingrese un apellido correcto',
            'apellidoEstudiante.min'=>'Ingrese un apellido correcto'
        ]);

        $data=request()->validate([
            'fechaNacimientoEstudiante'=>'required|date'
        ],
        [
            'fechaNacimientoEstudiante.required'=>'Ingrese la fecha de nacimiento del Estudiante',
            'fechaNacimientoEstudiante.date'=>'Ingrese un fecha correcta',
        ]);

        $data=request()->validate([
            'generoEstudiante'=>'required|max:1|min:1'
        ],
        [
            'generoEstudiante.required'=>'Ingrese el genero del Estudiante',
            'generoEstudiante.max'=>'Ingrese un genero correcto (M o F)',
            'generoEstudiante.min'=>'Ingrese un genero correcto (M o F)'
        ]);

        $data=request()->validate([
            'direccionEstudiante'=>'required|max:255|min:20'
        ],
        [
            'direccionEstudiante.required'=>'Ingrese la direccion del Estudiante',
            'direccionEstudiante.max'=>'Ingrese una direccion adecuada',
            'direccionEstudiante.min'=>'Ingrese una direccion adecuada'
        ]);

        $data = request()->validate([
            'numeroCelularEstudiante' => 'required|numeric|between:900000000'
        ], [
            'numeroCelularEstudiante.required' => 'Ingrese el numero de celular del estudiante',
            'numeroCelularEstudiante.numeric' => 'El numero de celular debe ser valido',
            'numeroCelularEstudiante.between' => 'El numero debe empezar en 9'
        ]);

        $data=request()->validate([
            'correoEstudiante'=>'required|max:100|min:10'
        ],
        [
            'correoEstudiante.required'=>'Ingrese la direccion de correo del Estudiante',
            'correoEstudiante.max'=>'Ingrese una direccion de correo adecuada',
            'correoEstudiante.min'=>'Ingrese una direccion de correo adecuada'
        ]);

        $data=request()->validate([
            'estadoEstudiante'=>'required'
        ],
        [
            'estadoEstudiante.required'=>'Ingrese el estad del estudiante',
        ]);

        $data=request()->validate([
            'fotoEstudiante'=>'required|max:255|min:20'
        ],
        [
            'fotoEstudiante.required'=>'Ingrese la URL de la foto del Estudiante',
            'fotoEstudiante.max'=>'Ingrese una URL de correo adecuada',
            'fotoEstudiante.min'=>'Ingrese una URL de correo adecuada'
        ]);

        $data=request()->validate([
            'observacionEstudiante'=>'required'
        ],
        [
            'observacionEstudiante.required'=>'Ingrese la observaciòn realizada',
        ]);


        $estudianteRepresentante=new InfEstudianteRepresentante();

        $estudianteRepresentante->dniEstudiante=$request->dniEstudiante;
        $estudianteRepresentante->nombreEstudiante=$request->nombreEstudiante;
        $estudianteRepresentante->apellidoEstudiante=$request->apellidoEstudiante;
        $estudianteRepresentante->fechaNacimientoEstudiante=$request->fechaNacimientoEstudiante;
        $estudianteRepresentante->generoEstudiante=$request->generoEstudiante;
        $estudianteRepresentante->direccionEstudiante=$request->direccionEstudiante;
        $estudianteRepresentante->numeroCelularEstudiante=$request->numeroCelularEstudiante;
        $estudianteRepresentante->correoEstudiante=$request->correoEstudiante;
        $estudianteRepresentante->fechaRegistroEstudiante=$request->fechaRegistroEstudiante;
        $estudianteRepresentante->estadoEstudiante=$request->estadoEstudiante;
        $estudianteRepresentante->fotoEstudiante=$request->fotoEstudiante;
        $estudianteRepresentante->observacionEstudiante=$request->observacionEstudiante;
        $estudianteRepresentante->save();

        //return redirect()->route('producto.index')->with('datos','Producto Nuevo Guardado...!');
    }
}
