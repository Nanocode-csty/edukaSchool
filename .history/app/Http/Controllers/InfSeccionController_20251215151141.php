<?php

namespace App\Http\Controllers;

use App\Models\InfSeccion;
use Illuminate\Http\Request;

class InfSeccionController extends Controller
{
    const PAGINATION = 6;

    public function index(Request $request)
    {
        $buscarpor = $request->get('buscarpor');

        $secciones = InfSeccion::where(function ($query) use ($buscarpor) {
            $query->where('nombre', 'like', "%$buscarpor%")
                ->orWhere('descripcion', 'like', "%$buscarpor%")
                ->orWhere('capacidad_maxima', 'like', "%$buscarpor%")
                ->orWhere('seccion_id', 'like', "%$buscarpor%");
        })
        ->orderBy('estado', 'desc') // Opcional: activa primero
        ->paginate(self::PAGINATION);

        if ($request->ajax()) {
            return view('ceinformacion.secciones.tabla', ['secciones' => $secciones])->render();
        }

        return view('ceinformacion.secciones.registrar', ['secciones' => $secciones, 'buscarpor' => $buscarpor]);
    }

    public function create()
    {
        return view('ceinformacion.secciones.nuevo');
    }

    public function store(Request $request)
{
    $data = $request->validate([
        'nombre' => ['required', 'regex:/^[A-L]$/', 'unique:secciones,nombre'],
        'capacidad_maxima' => ['required', 'integer', 'max:300'],
        'descripcion' => ['required', 'max:255'],
    ], [
        'nombre.required' => 'El campo nombre de sección es obligatorio.',
        'nombre.regex' => 'Solo se permite una letra de la A a la L.',
        'nombre.unique' => 'Ya existe una sección con esta letra.',
        'capacidad_maxima.required' => 'La capacidad máxima es obligatoria.',
        'capacidad_maxima.integer' => 'La capacidad debe ser un número.',
        'capacidad_maxima.max' => 'La capacidad no puede ser mayor a 300.',
        'descripcion.required' => 'La descripción es obligatoria.',
    ]);

    $seccion = new InfSeccion();
    $seccion->nombre = $data['nombre'];
    $seccion->capacidad_maxima = $data['capacidad_maxima'];
    $seccion->descripcion = $data['descripcion'];
    $seccion->estado = 'Activo';

    if ($seccion->save()) {
        return redirect()->route('secciones.index')->with('success', 'Sección registrada correctamente.');
    } else {
        return back()->with('error', 'Error al registrar la sección.');
    }
}

    public function edit($id)
    {
        $seccion = InfSeccion::findOrFail($id);
        return view('ceinformacion.secciones.editar', compact('seccion'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'capacidad_maxima' => 'required|integer',
            'estado' => 'required|in:Activo,Inactivo',
        ]);

        $seccion = InfSeccion::findOrFail($id);
        $seccion->capacidad_maxima = $data['capacidad_maxima'];
        $seccion->estado = $data['estado'];

        if ($seccion->save()) {
            return redirect()->route('secciones.index')->with('success', 'Sección actualizada correctamente.');
        } else {
            return back()->with('error', 'Error al actualizar la sección.');
        }
    }

    public function destroy($id)
    {
        $seccion = InfSeccion::findOrFail($id);
        $seccion->estado = 'Inactivo';
        $seccion->save();
        return redirect()->route('secciones.index')->with('success', 'Sección eliminada correctamente.');
    }
}
