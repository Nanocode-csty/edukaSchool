<?php

namespace App\Http\Controllers;

use App\Models\InfNivel;
use Illuminate\Http\Request;

class InfNivelController extends Controller
{
    const PAGINATION = 6;

    public function index(Request $request)
    {
        $buscarpor = $request->get('buscarpor');

        $niveles = InfNivel::where('nombre', 'like', '%'.$buscarpor.'%')
                    ->orWhere('descripcion', 'like', '%'.$buscarpor.'%')
                    ->paginate(10);

        return view('ceinformacion.niveles.registrar', compact('niveles', 'buscarpor'));
    }

    public function create()
    {
        return view('ceinformacion.niveles.create');
    }

    public function store(Request $request)
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
}
