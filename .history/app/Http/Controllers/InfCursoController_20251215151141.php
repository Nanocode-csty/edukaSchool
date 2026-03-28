<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InfCurso;
use App\Models\InfGrado;
use App\Models\InfSeccion;
use App\Models\InfAnioLectivo;
use App\Models\InfAula;
use App\Models\InfDocente;
use App\Models\InfNivel;

class InfCursoController extends Controller
{
    const PAGINATION = 10;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cursos = InfCurso::paginate($this::PAGINATION);
        return view('ceinformacion.cursos.index', compact('cursos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $grados = InfGrado::all();
        $secciones = InfSeccion::all();
        $aniosLectivos = InfAnioLectivo::where('estado', 'Activo')->get();
        $aulas = InfAula::all();
        $profesores = InfDocente::all();

        return view('ceinformacion.cursos.create', compact('grados', 'secciones', 'aniosLectivos', 'aulas', 'profesores'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = request()->validate([
            'grado_id' => 'required',
            'seccion_id' => 'required',
            'ano_lectivo_id' => 'required',
            'aula_id' => 'required',
            'profesor_principal_id' => 'required',
            'cupo_maximo' => 'required|integer|min:1',
            'estado' => 'required|in:En Planificación,Disponible,Completo,En Curso,Finalizado',
        ],
        [
            'grado_id.required' => 'El grado es obligatorio.',
            'seccion_id.required' => 'La sección es obligatoria.',
            'ano_lectivo_id.required' => 'El año lectivo es obligatorio.',
            'aula_id.required' => 'El aula es obligatoria.',
            'profesor_principal_id.required' => 'El profesor principal es obligatorio.',
            'cupo_maximo.required' => 'El cupo máximo es obligatorio.',
            'cupo_maximo.integer' => 'El cupo máximo debe ser un número entero.',
            'cupo_maximo.min' => 'El cupo máximo debe ser al menos 1.',
            'estado.required' => 'El estado es obligatorio.',
            'estado.in' => 'El estado debe ser uno de los valores permitidos.',
        ]);

        $curso = new InfCurso();
        $curso->grado_id = $request->grado_id;
        $curso->seccion_id = $request->seccion_id;
        $curso->ano_lectivo_id = $request->ano_lectivo_id;
        $curso->aula_id = $request->aula_id;
        $curso->profesor_principal_id = $request->profesor_principal_id;
        $curso->cupo_maximo = $request->cupo_maximo;
        $curso->estado = $request->estado;
        $curso->save();

        return redirect()->route('registrarcurso.index')->with('datos', 'Curso registrado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $curso_id)
    {
        $curso = InfCurso::findOrFail($curso_id);
        $grados = InfGrado::all();
        $secciones = InfSeccion::all();
        $aniosLectivos = InfAnioLectivo::all();
        $aulas = InfAula::all();
        $profesores = InfDocente::all();

        return view('ceinformacion.cursos.edit', compact('curso', 'grados', 'secciones', 'aniosLectivos', 'aulas', 'profesores'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $curso_id)
    {
        $data = request()->validate([
            'grado_id' => 'required',
            'seccion_id' => 'required',
            'ano_lectivo_id' => 'required',
            'aula_id' => 'required',
            'profesor_principal_id' => 'required',
            'cupo_maximo' => 'required|integer|min:1',
            'estado' => 'required|in:En Planificación,Disponible,Completo,En Curso,Finalizado',
        ],
        [
            'grado_id.required' => 'El grado es obligatorio.',
            'seccion_id.required' => 'La sección es obligatoria.',
            'ano_lectivo_id.required' => 'El año lectivo es obligatorio.',
            'aula_id.required' => 'El aula es obligatoria.',
            'profesor_principal_id.required' => 'El profesor principal es obligatorio.',
            'cupo_maximo.required' => 'El cupo máximo es obligatorio.',
            'cupo_maximo.integer' => 'El cupo máximo debe ser un número entero.',
            'cupo_maximo.min' => 'El cupo máximo debe ser al menos 1.',
            'estado.required' => 'El estado es obligatorio.',
            'estado.in' => 'El estado debe ser uno de los valores permitidos.',
        ]);

        $curso = InfCurso::findOrFail($curso_id);
        $curso->grado_id = $request->grado_id;
        $curso->seccion_id = $request->seccion_id;
        $curso->ano_lectivo_id = $request->ano_lectivo_id;
        $curso->aula_id = $request->aula_id;
        $curso->profesor_principal_id = $request->profesor_principal_id;
        $curso->cupo_maximo = $request->cupo_maximo;
        $curso->estado = $request->estado;
        $curso->save();

        return redirect()->route('registrarcurso.index')->with('datos', 'Curso actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $curso_id)
    {
        $curso = InfCurso::findOrFail($curso_id);
        $curso->delete();

        return redirect()->route('registrarcurso.index')->with('datos', 'Curso eliminado correctamente.');
    }

    public function confirmar($curso_id)
    {
        $curso = InfCurso::findOrFail($curso_id);
        return view('ceinformacion.cursos.confirmar', compact('curso'));
    }
}
