<?php

namespace App\Http\Controllers;

use App\Models\InfPeriodosEvaluacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;

class InfPeriodosEvaluacionController extends Controller
{
    const PAGINATION = 6;

    public function index(Request $request)
    {
        $this->actualizarEstadosAutomaticamente();

        $buscarpor = $request->get('buscarpor');
        $query = InfPeriodosEvaluacion::where(function ($q) use ($buscarpor) {
            $q->where('nombre', 'like', '%' . $buscarpor . '%')
              ->orWhere('estado', 'like', '%' . $buscarpor . '%');
        })
        ->orderBy('ano_lectivo_id', 'desc')
        ->orderBy('fecha_inicio', 'asc');

        $periodosEvaluacion = $query->paginate(self::PAGINATION);

        if ($request->ajax()) {
            return view('ceinformacion.periodosEvaluacion.tabla', compact('periodosEvaluacion'))->render();
        }

        return view('ceinformacion.periodosEvaluacion.registrar', compact('periodosEvaluacion', 'buscarpor'));
    }

    public function create()
        {
            // Solo traer años lectivos cuyo estado NO sea 'Finalizado'
            $anios = \App\Models\InfAnioLectivo::where('estado', '!=', 'Finalizado')->get();

            return view('ceinformacion.periodosEvaluacion.nuevo', compact('anios'));
        }

    public function store(Request $request)
    {
        $data = $this->validarDatos($request);

        // Validar duplicado final
        if ($request->es_final == 1) {
            $existeFinal = InfPeriodosEvaluacion::where('ano_lectivo_id', $request->ano_lectivo_id)
                ->where('es_final', true)
                ->whereIn('estado', ['Planificado', 'En curso'])
                ->exists();

            if ($existeFinal) {
                return back()->withErrors(['es_final' => 'Ya existe un periodo final activo en este año lectivo.'])->withInput();
            }
        }

        // Validar solapamiento de fechas
        $solapado = InfPeriodosEvaluacion::where('ano_lectivo_id', $request->ano_lectivo_id)
            ->where(function ($query) use ($request) {
                $query->whereBetween('fecha_inicio', [$request->fecha_inicio, $request->fecha_fin])
                      ->orWhereBetween('fecha_fin', [$request->fecha_inicio, $request->fecha_fin])
                      ->orWhere(function ($sub) use ($request) {
                          $sub->where('fecha_inicio', '<=', $request->fecha_inicio)
                              ->where('fecha_fin', '>=', $request->fecha_fin);
                      });
            })->exists();

        if ($solapado) {
            return back()->withErrors(['fecha_inicio' => 'Las fechas ingresadas se solapan con otro periodo.'])->withInput();
        }

        InfPeriodosEvaluacion::create($data);

        return redirect()->route('periodos-evaluacion.index')->with('success', '¡Periodo registrado correctamente!');
    }

    public function edit($id)
    {
        $periodo = InfPeriodosEvaluacion::findOrFail($id);
        return view('ceinformacion.periodosEvaluacion.editar', compact('periodo'));
    }

    public function update(Request $request, $id)
    {
        $periodo = InfPeriodosEvaluacion::findOrFail($id);
        $data = $this->validarDatos($request, $id);

        if ($request->es_final == 1) {
            $existeFinal = InfPeriodosEvaluacion::where('ano_lectivo_id', $request->ano_lectivo_id)
                ->where('es_final', true)
                ->whereIn('estado', ['Planificado', 'En curso'])
                ->where('periodo_id', '!=', $id)
                ->exists();

            if ($existeFinal) {
                return back()->withErrors(['es_final' => 'Ya existe otro periodo final activo en este año lectivo.'])->withInput();
            }
        }

        $solapado = InfPeriodosEvaluacion::where('ano_lectivo_id', $request->ano_lectivo_id)
            ->where('periodo_id', '!=', $id)
            ->where(function ($query) use ($request) {
                $query->whereBetween('fecha_inicio', [$request->fecha_inicio, $request->fecha_fin])
                      ->orWhereBetween('fecha_fin', [$request->fecha_inicio, $request->fecha_fin])
                      ->orWhere(function ($sub) use ($request) {
                          $sub->where('fecha_inicio', '<=', $request->fecha_inicio)
                              ->where('fecha_fin', '>=', $request->fecha_fin);
                      });
            })->exists();

        if ($solapado) {
            return back()->withErrors(['fecha_inicio' => 'Las fechas se solapan con otro periodo.'])->withInput();
        }

        if ($request->estado === 'Finalizado' && Carbon::now()->lt(Carbon::parse($request->fecha_fin))) {
            return back()->withErrors(['estado' => 'No se puede marcar como Finalizado si la fecha actual no ha pasado la fecha de fin.'])->withInput();
        }

        $periodo->update($data);

        return redirect()->route('periodos-evaluacion.index')->with('success', '¡Periodo actualizado exitosamente!');
    }

    public function show($id)
    {
        $periodo = InfPeriodosEvaluacion::findOrFail($id);
        return view('ceinformacion.periodosEvaluacion.mostrar', compact('periodo'));
    }

    public function destroy($id)
    {
        $periodo = InfPeriodosEvaluacion::findOrFail($id);

        if ($periodo->estado == 'Finalizado') {
            return redirect()->back()->with('error', 'No se puede eliminar un periodo finalizado.');
        }

        $periodo->delete();

        return redirect()->route('periodos-evaluacion.index')->with('success', '¡Periodo eliminado correctamente!');
    }

    private function actualizarEstadosAutomaticamente()
    {
        $hoy = Carbon::today();

        InfPeriodosEvaluacion::all()->each(function ($periodo) use ($hoy) {
            if (!in_array($periodo->estado, ['Finalizado', 'Cerrado'])) {
                if ($hoy->lt($periodo->fecha_inicio)) {
                    $periodo->estado = 'Planificado';
                } elseif ($hoy->between(Carbon::parse($periodo->fecha_inicio), Carbon::parse($periodo->fecha_fin))) {
                    $periodo->estado = 'En curso';
                } elseif ($hoy->gt($periodo->fecha_fin)) {
                    $periodo->estado = 'Finalizado';
                }
                $periodo->save();
            }
        });
    }

    private function validarDatos(Request $request, $id = null)
    {
        return $request->validate([
            'ano_lectivo_id' => 'required|integer|exists:anoslectivos,ano_lectivo_id',
            'nombre' => 'required|min:5|max:100',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'estado' => 'required|in:Planificado,En curso,Finalizado,Cerrado',
            'es_final' => 'required|boolean'
        ], [
            'ano_lectivo_id.required' => 'Seleccione el Año Lectivo.',
            'ano_lectivo_id.exists' => 'El Año Lectivo seleccionado no existe.',
            'nombre.required' => 'Ingrese un nombre para el periodo.',
            'nombre.min' => 'El nombre debe tener al menos 5 caracteres.',
            'nombre.max' => 'El nombre no puede exceder los 100 caracteres.',
            'fecha_inicio.required' => 'Ingrese la fecha de inicio.',
            'fecha_inicio.date' => 'La fecha de inicio debe ser válida.',
            'fecha_fin.required' => 'Ingrese la fecha de fin.',
            'fecha_fin.date' => 'La fecha de fin debe ser válida.',
            'fecha_fin.after' => 'La fecha de fin debe ser posterior a la de inicio y a la del año lectivo.',
            'estado.required' => 'Seleccione un estado.',
            'estado.in' => 'El estado debe ser válido.',
            'es_final.required' => 'Indique si es evaluación final.',
            'es_final.boolean' => 'Debe marcar si es o no evaluación final.'
        ]);
    }
}
