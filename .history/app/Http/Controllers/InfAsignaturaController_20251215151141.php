<?php

namespace App\Http\Controllers;

use App\Models\InfAsignatura;
use Illuminate\Http\Request;

class InfAsignaturaController extends Controller
{
    const PAGINATION = 6;

    public function index(Request $request)
    {
        $buscarpor = $request->get('buscarpor');
        $asignaturas = InfAsignatura::where('nombre', 'like', '%'.$buscarpor.'%')
                            ->orWhere('codigo', 'like', '%'.$buscarpor.'%')
                            ->orderBy('asignatura_id', 'desc')
                            ->paginate(self::PAGINATION);

        if ($request->ajax()) {
            return view('ceinformacion.asignaturas.tabla', compact('asignaturas'));
        }

        return view('ceinformacion.asignaturas.registrar', compact('asignaturas', 'buscarpor'));
    }

    public function create()
    {
        return view('ceinformacion.asignaturas.nuevo');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'codigo' => 'required|string|max:20|unique:inf_asignaturas,codigo'
        ], [
            'nombre.required' => 'El nombre de la asignatura es obligatorio.',
            'codigo.required' => 'El código es obligatorio.',
            'codigo.unique' => 'Este código ya está registrado.'
        ]);

        InfAsignatura::create($request->all());

        return redirect()->route('asignaturas.index')->with('success', 'Asignatura registrada correctamente.');
    }

    public function edit(InfAsignatura $asignatura)
    {
        return view('ceinformacion.asignaturas.editar', compact('asignatura'));
    }

    public function update(Request $request, InfAsignatura $asignatura)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'codigo' => 'required|string|max:20|unique:inf_asignaturas,codigo,' . $asignatura->asignatura_id . ',asignatura_id'
        ], [
            'nombre.required' => 'El nombre de la asignatura es obligatorio.',
            'codigo.required' => 'El código es obligatorio.',
            'codigo.unique' => 'Este código ya está registrado con otra asignatura.'
        ]);

        $asignatura->update($request->all());

        return redirect()->route('asignaturas.index')->with('success', 'Asignatura actualizada correctamente.');
    }

    public function destroy(InfAsignatura $asignatura)
    {
        $asignatura->delete();
        return redirect()->route('asignaturas.index')->with('success', 'Asignatura eliminada correctamente.');
    }
}
