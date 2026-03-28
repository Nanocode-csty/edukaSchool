<?php

namespace App\Http\Controllers;

use App\Models\InfGrado;
use App\Models\InfNivel;
use Illuminate\Http\Request;

class InfGradoController extends Controller
{
    public function index(Request $request)
    {
        $buscarpor = $request->get('buscarpor');
        $nivel_id = $request->get('nivel_id');

        $query = InfGrado::join('niveleseducativos', 'grados.nivel_id', '=', 'niveleseducativos.nivel_id')
            ->select('grados.*', 'niveleseducativos.nombre as nivel_nombre');

        if ($buscarpor) {
            $query->where(function ($q) use ($buscarpor) {
                $q->where('grados.nombre', 'like', '%' . $buscarpor . '%')
                    ->orWhere('grados.descripcion', 'like', '%' . $buscarpor . '%');
            });
        }

        if ($nivel_id) {
            $query->where('grados.nivel_id', $nivel_id);
        }

        $grados = $query->orderBy('grados.descripcion')->paginate(6);
        $niveles = InfNivel::all();

        // Si la petición es AJAX (desde JavaScript), devolvemos solo la tabla parcial
        if ($request->ajax()) {
            return view('ceinformacion.grados.tabla', compact('grados'))->render();
        }

        return view('ceinformacion.grados.registrar', compact('grados', 'buscarpor', 'niveles', 'nivel_id'));
    }

    public function create()
    {
        $niveles = InfNivel::all();
        return view('ceinformacion.grados.nuevo', compact('niveles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nivel_id' => 'required|exists:niveleseducativos,nivel_id',
            'nombre' => 'required|integer|between:1,6',
        ]);

        $nivel = InfNivel::findOrFail($request->nivel_id);
        $nivelNombre = strtolower($nivel->nombre);
        $gradosValidos = str_contains($nivelNombre, 'primaria') ? range(1, 6) : range(1, 5);

        if (!in_array($request->nombre, $gradosValidos)) {
            return redirect()->route('grados.create')
                ->with('error', 'Grado no válido para el nivel seleccionado.')
                ->withInput();
        }

        $descripcion = $request->nombre . '° de ' . $nivelNombre;

        $existe = InfGrado::where('nivel_id', $request->nivel_id)
            ->where('descripcion', $descripcion)
            ->exists();

        if ($existe) {
            return redirect()->route('grados.create')
                ->with('error', 'Este grado ya está registrado.')
                ->withInput();
        }

        InfGrado::create([
            'nivel_id' => $request->nivel_id,
            'nombre' => $request->nombre,
            'descripcion' => $descripcion,
        ]);

        return redirect()->route('grados.index')->with('success', 'Grado registrado correctamente.');
    }

    
    public function destroy($id)
    {
        try {
            $grado = InfGrado::findOrFail($id);
            $grado->delete();

            return redirect()->route('grados.index')->with('success', 'Grado eliminado correctamente.');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('grados.index')
                ->with('error', 'No se puede eliminar el grado porque tiene cursos asociados.');
        }
    }
}
