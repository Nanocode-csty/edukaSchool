<?php

namespace App\Http\Controllers;

use App\Models\InfAnioLectivo;
use App\Models\InfGrado;
use App\Models\InfSeccion;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
        $grado = InfGrado::orderBy('nivel_id', 'asc')->get();
        $anio_lectivo = InfAnioLectivo::where('activo', 1)->firstOrFail();
        return view('ceinformacion.secciones.nuevo', compact('grado', 'anio_lectivo'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => [
                'required',
                'regex:/^[A-L]$/',
                Rule::unique('secciones')
                    ->where(function ($query) use ($request) {
                        return $query->where('idGrado', $request->idGrado)
                            ->where('anio_lectivo_id', $request->anio_lectivo_id);
                    }),
            ],

            'capacidad_maxima' => ['required', 'integer', 'max:300'],
            'descripcion' => ['required', 'max:255'],
            'anio_lectivo_id' => ['required', 'exists:anoslectivos,ano_lectivo_id'],
            'idGrado' => ['required', 'exists:grados,grado_id'],

        ], [
            'nombre.required' => 'El campo nombre de sección es obligatorio.',
            'nombre.regex' => 'Solo se permite una letra de la A a la L.',
            'nombre.unique' => 'Ya existe esta sección para este grado en el año lectivo actual.',
            'capacidad_maxima.required' => 'La capacidad máxima es obligatoria.',
            'capacidad_maxima.integer' => 'La capacidad debe ser un número.',
            'capacidad_maxima.max' => 'La capacidad no puede ser mayor a 300.',
            'descripcion.required' => 'La descripción es obligatoria.',
        ]);

        InfSeccion::create([
            'nombre' => $data['nombre'],
            'capacidad_maxima' => $data['capacidad_maxima'],
            'descripcion' => $data['descripcion'],
            'estado' => 'Activo',
            'idGrado' => $data['idGrado'],
            'anio_lectivo_id' => $data['anio_lectivo_id']
        ]);

        return redirect()
            ->route('secciones.index')
            ->with('success', 'Sección registrada correctamente.');
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

    public function apiIndex(Request $request)
    {
        $query = InfSeccion::select('secciones.seccion_id as id', 'secciones.nombre')
            ->join('matriculas', 'secciones.seccion_id', '=', 'matriculas.idSeccion')
            ->join('asistenciasasignatura', 'matriculas.matricula_id', '=', 'asistenciasasignatura.matricula_id')
            ->distinct();

        // Filter by grado if provided (cascading)
        if ($request->filled('grado_id')) {
            $query->where('matriculas.idGrado', $request->grado_id);
        }

        $secciones = $query->orderBy('secciones.nombre', 'asc')->get();

        return response()->json($secciones);
    }
}
