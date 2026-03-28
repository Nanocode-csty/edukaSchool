<?php

namespace App\Http\Controllers;

use App\Models\InfNivel;
use Illuminate\Http\Request;

class InfNivelController extends Controller
{
    const PAGINATION = 2;

    public function index(Request $request)
    {
        $buscarpor = $request->get('buscarpor');

        $niveles = InfNivel::where('nombre', 'like', '%' . $buscarpor . '%')
            ->orWhere('descripcion', 'like', '%' . $buscarpor . '%')
            ->paginate(10);

        // Si la petición es AJAX (desde JavaScript), devolvemos solo la tabla parcial
        if ($request->ajax()) {
            return view('ceinformacion.niveles.tabla', compact('niveles'))->render();
        }

        return view('ceinformacion.niveles.registrar', compact('niveles', 'buscarpor'));
    }

    public function create()
    {
        return view('ceinformacion.niveles.nuevo.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|max:50|min:2|unique:niveleseducativos,nombre',
            'descripcion' => 'nullable|max:65535',
        ], [
            'nombre.required' => 'Ingrese el nombre del nivel educativo',
            'nombre.max' => 'El nombre es demasiado largo',
            'nombre.min' => 'El nombre es demasiado corto',
            'nombre.unique' => 'Este nivel educativo ya existe',
            'descripcion.max' => 'La descripción es demasiado larga',
        ]);

        $nivel = new InfNivel();
        $nivel->nombre = $request->nombre;
        $nivel->descripcion = $request->descripcion;
        $nivel->save();

        return redirect()->route('registrarnivel.index')->with('datos', 'Nivel educativo registrado exitosamente');
    }

    public function edit($nivel_id)
    {
        $nivel = InfNivel::findOrFail($nivel_id);
        return view('ceinformacion.niveles.edit', compact('nivel'));
    }

    public function update(Request $request, $nivel_id)
    {
        $data = $request->validate([
            'nombre' => 'required|max:50|min:2',
            'descripcion' => 'nullable|max:65535',
        ], [
            'nombre.required' => 'Ingrese el nombre del nivel educativo',
            'nombre.max' => 'El nombre es demasiado largo',
            'nombre.min' => 'El nombre es demasiado corto',
            'descripcion.max' => 'La descripción es demasiado larga',
        ]);

        $nivel = InfNivel::findOrFail($nivel_id);
        $nivel->nombre = $request->nombre;
        $nivel->descripcion = $request->descripcion;
        $nivel->save();

        return redirect()->route('registrarnivel.index')->with('datos', 'Nivel educativo actualizado exitosamente');
    }

    public function destroy($nivel_id)
    {
        $nivel = InfNivel::findOrFail($nivel_id);
        $nivel->delete();
        return redirect()->route('registrarnivel.index')->with('datos', 'Nivel educativo eliminado exitosamente');
    }

    public function confirmar($nivel_id)
    {
        $nivel = InfNivel::findOrFail($nivel_id);
        return view('ceinformacion.niveles.confirmar', compact('nivel'));
    }

    public function apiIndex()
    {
        // Filter niveles that have attendance records
        $niveles = InfNivel::select('niveleseducativos.nivel_id as id', 'niveleseducativos.nombre')
            ->join('grados', 'niveleseducativos.nivel_id', '=', 'grados.nivel_id')
            ->join('matriculas', 'grados.grado_id', '=', 'matriculas.idGrado')
            ->join('asistenciasasignatura', 'matriculas.matricula_id', '=', 'asistenciasasignatura.matricula_id')
            ->distinct()
            ->orderBy('niveleseducativos.nombre')
            ->get();

        return response()->json($niveles);
    }
}
