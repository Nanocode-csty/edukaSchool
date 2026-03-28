<?php

namespace App\Http\Controllers;

use App\Models\InfAula;
use Illuminate\Http\Request;

class InfAulaController extends Controller
{
    const PAGINATION = 6;

    public function index(Request $request)
    {
        $buscarpor = $request->get('buscarpor');

        $query = InfAula::query();

        if ($buscarpor) {
            $query->where(function ($q) use ($buscarpor) {
                $q->where('nombre', 'like', '%' . $buscarpor . '%')
                  ->orWhere('ubicacion', 'like', '%' . $buscarpor . '%')
                  ->orWhere('tipo', 'like', '%' . $buscarpor . '%')
                  ->orWhere('capacidad', 'like', '%' . $buscarpor . '%');
            });
        }

        $aulas = $query->orderBy('nombre')->paginate(self::PAGINATION);

        if ($request->ajax()) {
            return view('ceinformacion.aulas.tabla', compact('aulas'))->render();
        }

        return view('ceinformacion.aulas.registrar', compact('aulas', 'buscarpor'));
    }

    public function create()
    {
        return view('ceinformacion.aulas.nuevo');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'    => 'required|string|max:100',
            'capacidad' => 'required|integer|min:1',
            'ubicacion' => 'nullable|string|max:150',
            'tipo'      => 'required|string|max:50',
        ]);

        // Evitar duplicado exacto de nombre y ubicación
        $existe = InfAula::where('nombre', $request->nombre)
                         ->where('ubicacion', $request->ubicacion)
                         ->exists();

        if ($existe) {
            return redirect()->route('aulas.create')
                ->with('error', 'Ya existe un aula con ese nombre y ubicación.')
                ->withInput();
        }

        InfAula::create($request->all());

        return redirect()->route('aulas.index')
            ->with('success', 'Aula registrada correctamente.');
    }


    public function edit($id)
    {
        $aula = InfAula::findOrFail($id);
        return view('ceinformacion.aulas.editar', compact('aula'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre'    => 'required|string|max:100',
            'capacidad' => 'required|integer|min:1',
            'ubicacion' => 'nullable|string|max:150',
            'tipo'      => 'required|string|max:50',
        ]);

        $aula = InfAula::findOrFail($id);

        $aula->update($request->all());

        return redirect()->route('aulas.index')
            ->with('success', 'Aula actualizada correctamente.');
    }

    public function destroy($id)
    {
        try {
            $aula = InfAula::findOrFail($id);
            $aula->delete();

            return redirect()->route('aulas.index')
                ->with('success', 'Aula eliminada correctamente.');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('aulas.index')
                ->with('error', 'No se puede eliminar el aula porque tiene cursos asociados.');
        }
    }
}
