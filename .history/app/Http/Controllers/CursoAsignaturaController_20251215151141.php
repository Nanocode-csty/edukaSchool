<?php

namespace App\Http\Controllers;

use App\Models\AsistenciaAsignatura;
use App\Models\CursoAsignatura;
use App\Models\InfAnioLectivo;
use App\Models\InfAsignatura;
use App\Models\InfAula;
use App\Models\InfCurso;
use App\Models\InfDocente;
use App\Models\SesionClase;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CursoAsignaturaController extends Controller
{
    // Listado
    public function index()
    {
        $items = CursoAsignatura::with(['curso', 'asignatura', 'profesor'])->paginate(20);

        return view('ccursoasignatura.index', compact('items'));
    }

    // Formulario crear
    public function create()
    {
        // Mostrar cursos que estén en estados válidos para asignaciones y que no hayan alcanzado su capacidad máxima
        $cursos = InfCurso::with(['grado', 'seccion'])
            ->whereIn('estado', ['Disponible', 'En Curso'])
            ->withCount(['matriculas' => function ($query) {
                $query->where('estado', 'Matriculado');
            }])
            ->get()
            ->filter(function ($curso) {
                return $curso->matriculas_count < $curso->cupo_maximo;
            });

        $asignaturas = InfAsignatura::all();
        $profesores = InfDocente::all();
        $aulas = InfAula::all();
        $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];

        // Obtener feriados del año actual
        $anioActual = date('Y');
        $feriados = \App\Models\Feriado::activos()
            ->whereYear('fecha', $anioActual)
            ->orderBy('fecha')
            ->get()
            ->map(function ($feriado) {
                return [
                    'fecha' => $feriado->fecha->format('Y-m-d'),
                    'dia_semana' => $this->obtenerDiaSemana($feriado->fecha),
                    'nombre' => $feriado->nombre,
                    'tipo' => $feriado->tipo
                ];
            });

        return view('ccursoasignatura.create', compact('cursos', 'asignaturas', 'profesores', 'aulas', 'dias', 'feriados'));
    }

    // Guardar
    public function store(Request $request)
    {
        $rules = [
            'curso_id' => 'required|exists:cursos,curso_id',
            'asignatura_id' => 'required|exists:asignaturas,asignatura_id',
            'profesor_id' => 'required|exists:profesores,profesor_id',
            'horas_semanales' => 'required|integer|min:1|max:24',
            'dia_semana' => 'required|string',
            'aula_id' => 'required|exists:aulas,aula_id',
            'hora_inicio' => ['required', 'regex:/^([01]?\d|2[0-3]):[0-5]\d$/'],
            'hora_fin' => ['required', 'regex:/^([01]?\d|2[0-3]):[0-5]\d$/'],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $profesorId = $request->input('profesor_id');
        $horasNueva = (int) $request->input('horas_semanales');

        $horasExistentes = CursoAsignatura::where('profesor_id', $profesorId)->sum('horas_semanales');
        if ($horasExistentes + $horasNueva > 24) {
            return redirect()->back()->with('error', 'El profesor excede las 24 horas semanales permitidas.')->withInput();
        }

        $dia = $request->input('dia_semana');
        $inicio = $request->input('hora_inicio');
        $fin = $request->input('hora_fin');

        try {
            $tInicio = Carbon::parse(trim($inicio));
            $tFin = Carbon::parse(trim($fin));
            if (! $tFin->greaterThan($tInicio)) {
                return redirect()->back()->with('error', 'La hora fin debe ser posterior a la hora inicio.')->withInput();
            }
            $duracionMinutos = (int) (($tFin->getTimestamp() - $tInicio->getTimestamp()) / 60);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Formato de hora inválido. Use HH:MM o H:MM.')->withInput();
        }

        if (abs($duracionMinutos - ($horasNueva * 60)) > 1) {
            // Preserve all inputs except the problematic time fields
            $input = $request->all();
            unset($input['hora_inicio'], $input['hora_fin']);
            return redirect()->back()
                ->with('error', 'La diferencia entre hora inicio y hora fin debe coincidir con las horas semanales (en minutos).')
                ->withInput($input);
        }

        $conflicto = CursoAsignatura::where('profesor_id', $profesorId)
            ->where('dia_semana', $dia)
            ->where(function ($q) use ($inicio, $fin) {
                $q->whereBetween('hora_inicio', [$inicio, $fin])
                    ->orWhereBetween('hora_fin', [$inicio, $fin])
                    ->orWhereRaw('? BETWEEN hora_inicio AND hora_fin', [$inicio])
                    ->orWhereRaw('? BETWEEN hora_inicio AND hora_fin', [$fin]);
            })->exists();

        if ($conflicto) {
            return redirect()->back()->with('error', 'El profesor tiene un solapamiento de horario ese día.')->withInput();
        }

        $cursoId = $request->input('curso_id');
        $confCurso = CursoAsignatura::where('curso_id', $cursoId)
            ->where('dia_semana', $dia)
            ->where(function ($q) use ($inicio, $fin) {
                $q->whereBetween('hora_inicio', [$inicio, $fin])
                    ->orWhereBetween('hora_fin', [$inicio, $fin])
                    ->orWhereRaw('? BETWEEN hora_inicio AND hora_fin', [$inicio])
                    ->orWhereRaw('? BETWEEN hora_inicio AND hora_fin', [$fin]);
            })->exists();

        if ($confCurso) {
            return redirect()->back()->with('error', 'En este curso ya existe una asignatura en ese horario.')->withInput();
        }

        // Validar que no se programe en fines de semana
        if (in_array($dia, ['Domingo'])) {
            return redirect()->back()
                ->with('error', "No se puede programar clases en fines de semana. Los días laborables son de Lunes a Sábado.")
                ->withInput();
        }

        // Crear asignación y sesiones dentro de una transacción
        $item = null;
        DB::transaction(function () use ($request, &$item, $cursoId, $dia, $inicio, $fin) {
            $item = CursoAsignatura::create($request->only(['curso_id', 'asignatura_id', 'profesor_id', 'horas_semanales', 'dia_semana', 'hora_inicio', 'hora_fin']));

            // Determinar año lectivo del curso
            $curso = InfCurso::find($cursoId);
            $anio = null;
            if ($curso && $curso->ano_lectivo_id) {
                $anio = InfAnioLectivo::find($curso->ano_lectivo_id);
            }
            if (! $anio) {
                $anio = InfAnioLectivo::activos()->first();
            }

            if ($anio) {
                // Mapear nombre del día (e.g., 'Lunes') a número Carbon (1=Mon..7=Sun)
                $diasMap = [
                    'Lunes' => 1,
                    'Martes' => 2,
                    'Miércoles' => 3,
                    'Miercoles' => 3,
                    'Jueves' => 4,
                    'Viernes' => 5,
                    'Sábado' => 6,
                    'Sabado' => 6,
                    'Domingo' => 7,
                ];
                $diaNumero = $diasMap[$dia] ?? null;
                if ($diaNumero) {
                    $fechaInicio = Carbon::parse($anio->fecha_inicio)->startOfDay();
                    $fechaFin = Carbon::parse($anio->fecha_fin)->endOfDay();

                    // Buscar la primera fecha >= fechaInicio que corresponda al día de la semana
                    $fecha = $fechaInicio->copy();
                    if ($fecha->dayOfWeekIso !== $diaNumero) {
                        $delta = ($diaNumero - $fecha->dayOfWeekIso + 7) % 7;
                        $fecha->addDays($delta);
                    }

                    // Crear sesiones cada semana, excluyendo feriados y fines de semana
                    while ($fecha->lte($fechaFin)) {
                        // Verificar si NO es feriado y NO es fin de semana
                        $esDiaLaborable = !$this->esDiaNoLaborable($fecha);

                        if ($esDiaLaborable) {
                            SesionClase::create([
                                'curso_asignatura_id' => $item->curso_asignatura_id,
                                'fecha' => $fecha->toDateString(),
                                'hora_inicio' => $inicio,
                                'hora_fin' => $fin,
                                'estado' => 'Programada',
                                'observaciones' => null,
                                'aula_id' => $request->input('aula_id'),
                                'tipo_sesion' => 'Normal',
                                'usuario_registro' => Auth::id() ?? null,
                            ]);
                        }

                        $fecha->addWeek();
                    }
                }
            }
        });

        return redirect()->route('cursoasignatura.index')->with('success', 'Asignación creada correctamente y sesiones programadas.');
    }

    public function show($id)
    {
        $item = CursoAsignatura::with(['curso', 'asignatura', 'profesor'])->findOrFail($id);

        return view('ccursoasignatura.show', compact('item'));
    }

    public function edit($id)
    {
        $item = CursoAsignatura::findOrFail($id);
        // Mostrar cursos que estén en estados válidos para asignaciones; además, incluir el curso actual del item
        $cursos = InfCurso::with(['grado', 'seccion'])
            ->whereIn('estado', ['Disponible', 'En Curso'])
            ->withCount(['matriculas' => function ($query) {
                $query->where('estado', 'Matriculado');
            }])
            ->get()
            ->filter(function ($curso) {
                return $curso->matriculas_count < $curso->cupo_maximo;
            });

        // Asegurar que el curso actual esté incluido, incluso si no cumple los criterios
        $cursoActual = InfCurso::with(['grado', 'seccion'])->find($item->curso_id);
        if ($cursoActual && ! $cursos->contains('curso_id', $cursoActual->curso_id)) {
            $cursos->push($cursoActual);
        }

        $asignaturas = InfAsignatura::all();
        $profesores = InfDocente::all();
        $aulas = InfAula::all();
        $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];

        return view('ccursoasignatura.edit', compact('item', 'cursos', 'asignaturas', 'profesores', 'aulas', 'dias'));
    }

    public function update(Request $request, $id)
    {
        $item = CursoAsignatura::findOrFail($id);

        $rules = [
            'curso_id' => 'required|exists:cursos,curso_id',
            'asignatura_id' => 'required|exists:asignaturas,asignatura_id',
            'profesor_id' => 'required|exists:profesores,profesor_id',
            'horas_semanales' => 'required|integer|min:1|max:24',
            'dia_semana' => 'required|string',
            'aula_id' => 'required|exists:aulas,aula_id',
            'hora_inicio' => ['required', 'regex:/^([01]?\d|2[0-3]):[0-5]\d$/'],
            'hora_fin' => ['required', 'regex:/^([01]?\d|2[0-3]):[0-5]\d$/'],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $profesorId = $request->input('profesor_id');
        $horasNueva = (int) $request->input('horas_semanales');

        $horasExistentes = CursoAsignatura::where('profesor_id', $profesorId)
            ->where('curso_asignatura_id', '!=', $item->curso_asignatura_id)
            ->sum('horas_semanales');
        if ($horasExistentes + $horasNueva > 24) {
            return redirect()->back()->with('error', 'El profesor excede las 24 horas semanales permitidas.')->withInput();
        }

        $dia = $request->input('dia_semana');
        $inicio = $request->input('hora_inicio');
        $fin = $request->input('hora_fin');

        try {
            $tInicio = Carbon::parse(trim($inicio));
            $tFin = Carbon::parse(trim($fin));
            if (! $tFin->greaterThan($tInicio)) {
                return redirect()->back()->with('error', 'La hora fin debe ser posterior a la hora inicio.')->withInput();
            }
            $duracionMinutos = (int) (($tFin->getTimestamp() - $tInicio->getTimestamp()) / 60);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Formato de hora inválido. Use HH:MM o H:MM.')->withInput();
        }

        if (abs($duracionMinutos - ($horasNueva * 60)) > 1) {
            // Preserve all inputs except the problematic time fields
            $input = $request->all();
            unset($input['hora_inicio'], $input['hora_fin']);
            return redirect()->back()
                ->with('error', 'La diferencia entre hora inicio y hora fin debe coincidir con las horas semanales (en minutos).')
                ->withInput($input);
        }

        $conflicto = CursoAsignatura::where('profesor_id', $profesorId)
            ->where('dia_semana', $dia)
            ->where('curso_asignatura_id', '!=', $item->curso_asignatura_id)
            ->where(function ($q) use ($inicio, $fin) {
                $q->whereBetween('hora_inicio', [$inicio, $fin])
                    ->orWhereBetween('hora_fin', [$inicio, $fin])
                    ->orWhereRaw('? BETWEEN hora_inicio AND hora_fin', [$inicio])
                    ->orWhereRaw('? BETWEEN hora_inicio AND hora_fin', [$fin]);
            })->exists();

        if ($conflicto) {
            return redirect()->back()->with('error', 'El profesor tiene un solapamiento de horario ese día.')->withInput();
        }

        $cursoId = $request->input('curso_id');
        $confCurso = CursoAsignatura::where('curso_id', $cursoId)
            ->where('dia_semana', $dia)
            ->where('curso_asignatura_id', '!=', $item->curso_asignatura_id)
            ->where(function ($q) use ($inicio, $fin) {
                $q->whereBetween('hora_inicio', [$inicio, $fin])
                    ->orWhereBetween('hora_fin', [$inicio, $fin])
                    ->orWhereRaw('? BETWEEN hora_inicio AND hora_fin', [$inicio])
                    ->orWhereRaw('? BETWEEN hora_inicio AND hora_fin', [$fin]);
            })->exists();

        if ($confCurso) {
            return redirect()->back()->with('error', 'En este curso ya existe una asignatura en ese horario.')->withInput();
        }



        $item->update($request->only(['curso_id', 'asignatura_id', 'profesor_id', 'horas_semanales', 'dia_semana', 'hora_inicio', 'hora_fin']));

        return redirect()->route('cursoasignatura.index')->with('success', 'Asignación actualizada correctamente.');
    }



    public function destroy(Request $request, $id)
    {
        $request->validate([
            'confirmar_eliminacion' => 'required|accepted'
        ]);

        $item = CursoAsignatura::findOrFail($id);

        // Contar registros que se eliminarán
        $sesionesCount = SesionClase::where('curso_asignatura_id', $id)->count();
        $asistenciasCount = AsistenciaAsignatura::where('curso_asignatura_id', $id)->count();

        DB::transaction(function () use ($item, $id) {
            // Eliminar asistencias primero
            AsistenciaAsignatura::where('curso_asignatura_id', $id)->delete();

            // Eliminar sesiones de clase
            SesionClase::where('curso_asignatura_id', $id)->delete();

            // Finalmente eliminar la asignación
            $item->delete();
        });

        $mensaje = 'Asignación eliminada correctamente.';
        if ($sesionesCount > 0 || $asistenciasCount > 0) {
            $mensaje .= " Se eliminaron {$sesionesCount} sesiones de clase y {$asistenciasCount} registros de asistencia.";
        }

        return redirect()->route('cursoasignatura.index')->with('success', $mensaje);
    }

    // Auxiliares
    public function getByCurso($curso)
    {
        $items = CursoAsignatura::with(['asignatura', 'profesor'])->where('curso_id', $curso)->get();

        return response()->json($items);
    }

    public function horarioProfesor($profesor)
    {
        $items = CursoAsignatura::with(['curso', 'asignatura'])->where('profesor_id', $profesor)->get();

        return response()->json($items);
    }

    /**
     * Verifica si una fecha es día no laborable (fin de semana o feriado)
     */
    private function esDiaNoLaborable(Carbon $fecha)
    {
        // Verificar si es fin de semana (sábado = 6, domingo = 7)
        if ($fecha->isWeekend()) {
            return true;
        }

        // Verificar si es feriado
        return \App\Models\Feriado::esFeriado($fecha->format('Y-m-d'));
    }

    /**
     * Obtiene el nombre del día de la semana en español
     */
    private function obtenerDiaSemana(Carbon $fecha)
    {
        $dias = [
            1 => 'Lunes',
            2 => 'Martes',
            3 => 'Miércoles',
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sábado',
            7 => 'Domingo',
        ];

        return $dias[$fecha->dayOfWeekIso] ?? '';
    }
}
