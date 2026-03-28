<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PeriodoMatricula;
use App\Models\NotificacionPeriodo;
use App\Models\DescuentoPeriodo;
use App\Models\Matricula;
use App\Models\InfAnioLectivo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PeriodosController extends Controller
{
    /**
     * Dashboard principal de períodos
     */
    public function dashboard()
    {
        $periodoActual = PeriodoMatricula::getPeriodoActual();
        $proximosPeriodos = PeriodoMatricula::getProximosPeriodos(30);
        $todosPeriodos = PeriodoMatricula::with('anoLectivo')->activos()->ordenado()->get();

        // Estadísticas
        $totalPeriodos = $todosPeriodos->count();
        $periodosActivos = $todosPeriodos->filter(fn($p) => $p->estaActivo())->count();
        $matriculasPeriodoActual = $periodoActual ?
            Matricula::whereBetween('created_at', [$periodoActual->fecha_inicio, $periodoActual->fecha_fin])->count() : 0;

        // Notificaciones recientes
        $notificacionesRecientes = NotificacionPeriodo::with('periodo')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Descuentos activos
        $descuentosActivos = DescuentoPeriodo::disponibles()
            ->with('periodo')
            ->get();

        return view('periodos.dashboard', compact(
            'periodoActual',
            'proximosPeriodos',
            'todosPeriodos',
            'totalPeriodos',
            'periodosActivos',
            'matriculasPeriodoActual',
            'notificacionesRecientes',
            'descuentosActivos'
        ));
    }

    /**
     * Gestión de períodos académicos
     */
    public function index()
    {
        $query = PeriodoMatricula::with('anoLectivo')->ordenado();

        // Aplicar filtros
        if (request('estado')) {
            $query->where('estado', request('estado'));
        }

        if (request('tipo_periodo')) {
            $query->where('tipo_periodo', request('tipo_periodo'));
        }

        if (request('anio_lectivo')) {
            $query->where('ano_lectivo_id', request('anio_lectivo'));
        }

        $periodos = $query->paginate(15);
        $aniosLectivos = InfAnioLectivo::activos()->get();

        // Si es una petición AJAX, devolver solo el contenido dinámico
        if (request()->ajax()) {
            return view('periodos.partials.table-content', compact('periodos'));
        }

        return view('periodos.index', compact('periodos', 'aniosLectivos'));
    }

    public function create()
    {
        $aniosLectivos = InfAnioLectivo::activos()->get();

        return view('periodos.create', compact('aniosLectivos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:50|unique:periodos_matricula',
            'descripcion' => 'nullable|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'ano_lectivo_id' => 'required|exists:inf_anio_lectivo,ano_lectivo_id',
            'tipo_periodo' => 'required|in:PREINSCRIPCION,INSCRIPCION,MATRICULA,ACADEMICO,CIERRE',
            'configuracion' => 'nullable|array'
        ]);

        // Asignar orden automáticamente según el tipo de período
        $ordenes = [
            'PREINSCRIPCION' => 1,
            'INSCRIPCION' => 2,
            'MATRICULA' => 3,
            'ACADEMICO' => 4,
            'CIERRE' => 5
        ];

        $request->merge(['orden' => $ordenes[$request->tipo_periodo] ?? 99]);

        try {
            DB::beginTransaction();

            $periodo = PeriodoMatricula::create($request->all());

            // Crear notificaciones automáticas
            NotificacionPeriodo::crearNotificacionPeriodoProximo($periodo, 7);
            NotificacionPeriodo::crearRecordatorioPeriodoActivo($periodo);

            DB::commit();

            return redirect()->route('periodos.index')
                ->with('success', 'Período creado exitosamente');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creando período: ' . $e->getMessage());

            return back()->withInput()
                ->with('error', 'Error al crear el período: ' . $e->getMessage());
        }
    }

    public function edit(PeriodoMatricula $periodo)
    {
        $aniosLectivos = InfAnioLectivo::activos()->get();

        return view('periodos.edit', compact('periodo', 'aniosLectivos'));
    }

    public function update(Request $request, PeriodoMatricula $periodo)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:50|unique:periodos_matricula,codigo,' . $periodo->periodo_id . ',periodo_id',
            'descripcion' => 'nullable|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'ano_lectivo_id' => 'required|exists:inf_anio_lectivo,ano_lectivo_id',
            'tipo_periodo' => 'required|in:PREINSCRIPCION,INSCRIPCION,MATRICULA,ACADEMICO,CIERRE',
            'orden' => 'nullable|integer',
            'configuracion' => 'nullable|array'
        ]);

        try {
            // Validar consistencia antes de actualizar el período
            $conflicts = $this->validarConsistenciaPeriodo(
                $request->tipo_periodo,
                $request->fecha_inicio,
                $request->fecha_fin,
                $request->ano_lectivo_id,
                $periodo->periodo_id
            );

            if (!empty($conflicts)) {
                return back()->withInput()
                    ->with('error', 'No se puede actualizar el período porque hay conflictos: ' . implode(', ', $conflicts));
            }

            // Actualizar solo los campos permitidos (excluir estado y orden)
            $periodo->update($request->except(['estado', 'orden']));

            return redirect()->route('periodos.index')
                ->with('success', 'Período actualizado exitosamente');

        } catch (\Exception $e) {
            Log::error('Error actualizando período: ' . $e->getMessage());

            return back()->withInput()
                ->with('error', 'Error al actualizar el período: ' . $e->getMessage());
        }
    }

    public function destroy(PeriodoMatricula $periodo)
    {
        try {
            // Verificar si hay matrículas asociadas
            $matriculasAsociadas = Matricula::whereBetween('created_at', [
                $periodo->fecha_inicio,
                $periodo->fecha_fin
            ])->count();

            if ($matriculasAsociadas > 0) {
                return back()->with('error', 'No se puede eliminar el período porque tiene matrículas asociadas');
            }

            // Eliminación lógica: marcar como inactivo
            $periodo->update(['estado' => 'INACTIVO']);

            return redirect()->route('periodos.index')
                ->with('success', 'Período eliminado exitosamente (marcado como inactivo)');

        } catch (\Exception $e) {
            Log::error('Error eliminando período: ' . $e->getMessage());

            return back()->with('error', 'Error al eliminar el período');
        }
    }

    /**
     * Validar consistencia antes de activar un período
     */
    private function validarConsistenciaPeriodo($tipoPeriodo, $fechaInicio, $fechaFin, $anioLectivoId, $excluirPeriodoId = null)
    {
        $conflicts = [];

        // 1. Verificar que no haya otro período del mismo tipo activo en el mismo año lectivo
        $periodoActivoMismoTipo = PeriodoMatricula::where('tipo_periodo', $tipoPeriodo)
            ->where('ano_lectivo_id', $anioLectivoId)
            ->where('estado', 'ACTIVO')
            ->when($excluirPeriodoId, fn($q) => $q->where('periodo_id', '!=', $excluirPeriodoId))
            ->first();

        if ($periodoActivoMismoTipo) {
            $conflicts[] = "Ya existe un período {$tipoPeriodo} activo en el año lectivo";
        }

        // 2. Verificar solapamiento de fechas con otros períodos activos
        $periodosSolapados = PeriodoMatricula::where('ano_lectivo_id', $anioLectivoId)
            ->where('estado', 'ACTIVO')
            ->where(function($query) use ($fechaInicio, $fechaFin) {
                $query->where(function($q) use ($fechaInicio, $fechaFin) {
                    // Caso 1: El nuevo período está dentro de un período existente
                    $q->where('fecha_inicio', '<=', $fechaInicio)
                      ->where('fecha_fin', '>=', $fechaFin);
                })->orWhere(function($q) use ($fechaInicio, $fechaFin) {
                    // Caso 2: El nuevo período solapa al inicio de un período existente
                    $q->where('fecha_inicio', '<=', $fechaInicio)
                      ->where('fecha_fin', '>=', $fechaInicio)
                      ->where('fecha_fin', '<=', $fechaFin);
                })->orWhere(function($q) use ($fechaInicio, $fechaFin) {
                    // Caso 3: El nuevo período solapa al final de un período existente
                    $q->where('fecha_inicio', '>=', $fechaInicio)
                      ->where('fecha_inicio', '<=', $fechaFin)
                      ->where('fecha_fin', '>=', $fechaFin);
                })->orWhere(function($q) use ($fechaInicio, $fechaFin) {
                    // Caso 4: Un período existente está completamente dentro del nuevo período
                    $q->where('fecha_inicio', '>=', $fechaInicio)
                      ->where('fecha_fin', '<=', $fechaFin);
                });
            })
            ->when($excluirPeriodoId, fn($q) => $q->where('periodo_id', '!=', $excluirPeriodoId))
            ->get();

        if ($periodosSolapados->count() > 0) {
            $nombresSolapados = $periodosSolapados->pluck('nombre')->join(', ');
            $conflicts[] = "Las fechas se solapan con los siguientes períodos activos: {$nombresSolapados}";
        }

        // 3. Validaciones específicas por tipo de período
        switch ($tipoPeriodo) {
            case 'PREINSCRIPCION':
                // Las pre-inscripciones deben ser antes de las inscripciones
                $inscripcionActiva = PeriodoMatricula::where('tipo_periodo', 'INSCRIPCION')
                    ->where('ano_lectivo_id', $anioLectivoId)
                    ->where('estado', 'ACTIVO')
                    ->first();

                if ($inscripcionActiva && $fechaFin >= $inscripcionActiva->fecha_inicio) {
                    $conflicts[] = "Las pre-inscripciones deben terminar antes de que comiencen las inscripciones";
                }
                break;

            case 'INSCRIPCION':
                // Las inscripciones deben ser antes de las matrículas
                $matriculaActiva = PeriodoMatricula::where('tipo_periodo', 'MATRICULA')
                    ->where('ano_lectivo_id', $anioLectivoId)
                    ->where('estado', 'ACTIVO')
                    ->first();

                if ($matriculaActiva && $fechaFin >= $matriculaActiva->fecha_inicio) {
                    $conflicts[] = "Las inscripciones deben terminar antes de que comiencen las matrículas";
                }
                break;

            case 'MATRICULA':
                // Las matrículas deben ser antes del período académico
                $academicoActivo = PeriodoMatricula::where('tipo_periodo', 'ACADEMICO')
                    ->where('ano_lectivo_id', $anioLectivoId)
                    ->where('estado', 'ACTIVO')
                    ->first();

                if ($academicoActivo && $fechaFin >= $academicoActivo->fecha_inicio) {
                    $conflicts[] = "Las matrículas deben terminar antes de que comience el período académico";
                }
                break;
        }

        return $conflicts;
    }

    /**
     * Gestión de notificaciones
     */
    public function notificaciones()
    {
        $notificaciones = NotificacionPeriodo::with(['periodo', 'usuario'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('periodos.notificaciones', compact('notificaciones'));
    }

    public function crearNotificacion(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'mensaje' => 'required|string',
            'tipo_notificacion' => 'required|in:PERIODO_INICIADO,PERIODO_TERMINADO,RECORDATORIO,PERIODO_PROXIMO',
            'periodo_id' => 'required|exists:periodos_matricula,periodo_id',
            'fecha_programada' => 'nullable|date|after:now'
        ]);

        try {
            NotificacionPeriodo::create([
                'titulo' => $request->titulo,
                'mensaje' => $request->mensaje,
                'tipo_notificacion' => $request->tipo_notificacion,
                'periodo_id' => $request->periodo_id,
                'fecha_programada' => $request->fecha_programada,
                'estado' => $request->fecha_programada ? 'PENDIENTE' : 'ENVIADA'
            ]);

            return redirect()->route('periodos.notificaciones')
                ->with('success', 'Notificación creada exitosamente');

        } catch (\Exception $e) {
            Log::error('Error creando notificación: ' . $e->getMessage());

            return back()->withInput()
                ->with('error', 'Error al crear la notificación');
        }
    }

    /**
     * Gestión de descuentos
     */
    public function descuentos()
    {
        $descuentos = DescuentoPeriodo::with('periodo')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $periodos = PeriodoMatricula::activos()->get();

        return view('periodos.descuentos', compact('descuentos', 'periodos'));
    }

    public function crearDescuento(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'periodo_id' => 'required|exists:periodos_matricula,periodo_id',
            'porcentaje_descuento' => 'nullable|numeric|min:0|max:100',
            'monto_fijo_descuento' => 'nullable|numeric|min:0',
            'tipo_descuento' => 'required|in:PORCENTAJE,FIJO,AMBOS',
            'aplicable_a' => 'required|in:TODOS,PREINSCRITOS,MATRICULADOS,NUEVOS,REPITENTES',
            'fecha_inicio_vigencia' => 'required|date',
            'fecha_fin_vigencia' => 'required|date|after:fecha_inicio_vigencia',
            'limite_usos' => 'nullable|integer|min:1',
            'condiciones' => 'nullable|array'
        ]);

        try {
            DescuentoPeriodo::create($request->all());

            return redirect()->route('periodos.descuentos')
                ->with('success', 'Descuento creado exitosamente');

        } catch (\Exception $e) {
            Log::error('Error creando descuento: ' . $e->getMessage());

            return back()->withInput()
                ->with('error', 'Error al crear el descuento');
        }
    }

    /**
     * Historial de matrículas por período
     */
    public function historialMatriculas(Request $request)
    {
        $periodoId = $request->get('periodo_id');
        $fechaInicio = $request->get('fecha_inicio');
        $fechaFin = $request->get('fecha_fin');

        $query = Matricula::with(['estudiante', 'representante']);

        // Filtros
        if ($periodoId) {
            $periodo = PeriodoMatricula::find($periodoId);
            if ($periodo) {
                $query->whereBetween('created_at', [$periodo->fecha_inicio, $periodo->fecha_fin]);
            }
        } elseif ($fechaInicio && $fechaFin) {
            $query->whereBetween('created_at', [$fechaInicio, $fechaFin]);
        }

        $matriculas = $query->orderBy('created_at', 'desc')->paginate(20);

        $periodos = PeriodoMatricula::activos()->get();

        // Estadísticas
        $totalMatriculas = $matriculas->total();
        $matriculasPreinscritas = $matriculas->where('estado', 'Pre-inscrito')->count();
        $matriculasOficiales = $matriculas->where('estado', 'Matriculado')->count();

        return view('periodos.historial-matriculas', compact(
            'matriculas',
            'periodos',
            'totalMatriculas',
            'matriculasPreinscritas',
            'matriculasOficiales',
            'periodoId',
            'fechaInicio',
            'fechaFin'
        ));
    }

    /**
     * Ejecutar verificación manual de períodos
     */
    public function verificarPeriodos()
    {
        try {
            // Ejecutar el comando de verificación
            \Artisan::call('periodos:verificar', ['--notificar' => true]);

            $output = \Artisan::output();

            return redirect()->back()
                ->with('success', 'Verificación de períodos completada')
                ->with('output', $output);

        } catch (\Exception $e) {
            Log::error('Error verificando períodos: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Error al verificar períodos: ' . $e->getMessage());
        }
    }
}
