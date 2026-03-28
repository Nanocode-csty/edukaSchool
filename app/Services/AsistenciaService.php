<?php

namespace App\Services;

use App\Models\AsistenciaAsignatura;
use App\Models\ClaseRecuperacion;
use App\Models\Feriado;
use App\Models\NotificacionAsistencia;
use App\Models\TipoAsistencia;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AsistenciaService
{
    /**
     * Verifica y genera alertas automáticas para una asistencia
     */
    public function verificarAlertas($asistencia)
    {
        $alertas = [];

        // Alerta por ausencias consecutivas
        $ausenciasConsecutivas = $this->contarAusenciasConsecutivas($asistencia->matricula_id);
        if ($ausenciasConsecutivas >= 3) {
            $alerta = NotificacionAsistencia::crearNotificacionAusenciasConsecutivas($asistencia->matricula, $ausenciasConsecutivas);
            if ($alerta) {
                $alertas[] = $alerta;
            }
        }

        // Alerta por porcentaje bajo de asistencia
        $porcentaje = $this->calcularPorcentajeAsistenciaMes($asistencia->matricula_id);
        if ($porcentaje < 75) {
            $alerta = NotificacionAsistencia::crearNotificacionPorcentajeBajo($asistencia->matricula, $porcentaje);
            if ($alerta) {
                $alertas[] = $alerta;
            }
        }

        return $alertas;
    }

    /**
     * Programa automáticamente clases de recuperación para ausencias por feriados
     */
    public function programarClasesRecuperacion($fechaFeriado, $cursoAsignaturaId)
    {
        $feriado = Feriado::porFecha($fechaFeriado)->first();

        if (!$feriado || !$feriado->recuperable) {
            return null;
        }

        // Buscar fecha disponible para recuperación (próximo día no feriado)
        $fechaRecuperacion = $this->encontrarFechaRecuperacionDisponible($fechaFeriado);

        return ClaseRecuperacion::create([
            'curso_asignatura_id' => $cursoAsignaturaId,
            'fecha_original' => $fechaFeriado,
            'fecha_recuperacion' => $fechaRecuperacion,
            'motivo' => 'feriado',
            'observaciones' => "Recuperación por feriado: {$feriado->nombre}",
            'profesor_id' => auth()->id(), // O el profesor asignado al curso
            'estado' => 'programada',
            'duracion_minutos' => 60,
            'creado_por' => auth()->id()
        ]);
    }

    /**
     * Encuentra una fecha disponible para clase de recuperación
     */
    private function encontrarFechaRecuperacionDisponible($fechaOriginal)
    {
        $fecha = Carbon::parse($fechaOriginal)->addDay();

        // Buscar hasta 30 días después
        for ($i = 0; $i < 30; $i++) {
            if (!$this->esDiaNoLaborable($fecha) && !$this->tieneClaseRecuperacion($fecha)) {
                return $fecha;
            }
            $fecha->addDay();
        }

        // Si no encuentra, devolver la fecha más próxima posible
        return Carbon::parse($fechaOriginal)->addDays(7);
    }

    /**
     * Verifica si una fecha es no laborable (fin de semana o feriado)
     */
    private function esDiaNoLaborable($fecha)
    {
        // Verificar si es fin de semana
        if ($fecha->isWeekend()) {
            return true;
        }

        // Verificar si es feriado
        return Feriado::esFeriado($fecha->format('Y-m-d'));
    }

    /**
     * Verifica si ya hay una clase de recuperación programada en esa fecha
     */
    private function tieneClaseRecuperacion($fecha)
    {
        return ClaseRecuperacion::porFecha($fecha->format('Y-m-d'))->exists();
    }

    /**
     * Obtiene estadísticas avanzadas de asistencia para un estudiante
     */
    public function obtenerEstadisticasAvanzadas($matriculaId, $fechaInicio = null, $fechaFin = null)
    {
        $fechaInicio = $fechaInicio ?: Carbon::now()->startOfYear();
        $fechaFin = $fechaFin ?: Carbon::now();

        $asistencias = AsistenciaAsignatura::where('matricula_id', $matriculaId)
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->with('tipoAsistencia')
            ->get();

        $totalDias = $asistencias->count();
        $ausencias = $asistencias->filter(function ($a) {
            return optional($a->tipoAsistencia)->computa_falta;
        })->count();

        $porcentajeAsistencia = $totalDias > 0 ? round((($totalDias - $ausencias) / $totalDias) * 100, 1) : 0;

        // Calcular racha actual
        $rachaActual = $this->calcularRachaAsistencia($matriculaId);

        // Calcular tendencia
        $tendencia = $this->calcularTendencia($asistencias);

        // Estadísticas por mes
        $estadisticasMensuales = $this->calcularEstadisticasMensuales($asistencias);

        return [
            'total_dias' => $totalDias,
            'ausencias' => $ausencias,
            'porcentaje_asistencia' => $porcentajeAsistencia,
            'racha_actual' => $rachaActual,
            'tendencia' => $tendencia,
            'estadisticas_mensuales' => $estadisticasMensuales,
            'alertas_activas' => $this->contarAlertasActivas($matriculaId)
        ];
    }

    /**
     * Calcula estadísticas mensuales de asistencia
     */
    private function calcularEstadisticasMensuales($asistencias)
    {
        return $asistencias->groupBy(function ($asistencia) {
            return Carbon::parse($asistencia->fecha)->format('Y-m');
        })->map(function ($asistenciasMes) {
            $total = $asistenciasMes->count();
            $ausencias = $asistenciasMes->filter(function ($a) {
                return optional($a->tipoAsistencia)->computa_falta;
            })->count();

            return [
                'total' => $total,
                'ausencias' => $ausencias,
                'porcentaje' => $total > 0 ? round((($total - $ausencias) / $total) * 100, 1) : 0
            ];
        });
    }

    /**
     * Cuenta alertas activas para un estudiante
     */
    private function contarAlertasActivas($matriculaId)
    {
        return NotificacionAsistencia::where('matricula_id', $matriculaId)
            ->whereIn('estado', ['pendiente', 'enviada'])
            ->count();
    }

    /**
     * Procesa operaciones masivas de asistencia considerando feriados
     */
    public function procesarOperacionMasiva($operacion, $parametros)
    {
        switch ($operacion) {
            case 'marcar_feriado':
                return $this->procesarMarcadoFeriado($parametros);
            case 'justificar_ausencias_feriado':
                return $this->procesarJustificacionAusenciasFeriado($parametros);
            case 'programar_recuperaciones':
                return $this->procesarProgramacionRecuperaciones($parametros);
            default:
                throw new \InvalidArgumentException("Operación no válida: {$operacion}");
        }
    }

    /**
     * Procesa el marcado de un día como feriado
     */
    private function procesarMarcadoFeriado($parametros)
    {
        $feriado = Feriado::create([
            'nombre' => $parametros['nombre'],
            'fecha' => $parametros['fecha'],
            'tipo' => $parametros['tipo'] ?? 'nacional',
            'recuperable' => $parametros['recuperable'] ?? true,
            'descripcion' => $parametros['descripcion'] ?? null,
            'ubicacion' => $parametros['ubicacion'] ?? null,
            'activo' => true,
            'creado_por' => auth()->id()
        ]);

        // Programar recuperaciones si es recuperable
        if ($feriado->recuperable) {
            $this->programarRecuperacionesPorFeriado($feriado);
        }

        return $feriado;
    }

    /**
     * Programa recuperaciones para todas las asignaturas afectadas por un feriado
     */
    private function programarRecuperacionesPorFeriado($feriado)
    {
        // Obtener todas las sesiones de clase programadas para ese día
        $sesiones = SesionClase::whereDate('fecha', $feriado->fecha)->get();

        foreach ($sesiones as $sesion) {
            $this->programarClasesRecuperacion($feriado->fecha, $sesion->curso_asignatura_id);
        }
    }

    /**
     * Procesa justificación automática de ausencias por feriado
     */
    private function procesarJustificacionAusenciasFeriado($parametros)
    {
        $fecha = $parametros['fecha'];
        $tipoJustificada = TipoAsistencia::where('codigo', 'J')->first();

        if (!$tipoJustificada) {
            throw new \Exception('Tipo de asistencia "Justificada" no encontrado');
        }

        $asistenciasActualizadas = 0;

        // Obtener todas las asistencias de ese día que sean ausencias
        $asistenciasAusentes = AsistenciaAsignatura::where('fecha', $fecha)
            ->whereHas('tipoAsistencia', function ($q) {
                $q->where('computa_falta', true);
            })
            ->get();

        foreach ($asistenciasAusentes as $asistencia) {
            $asistencia->update([
                'tipo_asistencia_id' => $tipoJustificada->tipo_asistencia_id,
                'justificacion' => 'Ausencia justificada por feriado',
                'estado' => 'Justificada',
                'fecha_justificacion' => now(),
                'usuario_justificacion' => auth()->id()
            ]);

            $asistenciasActualizadas++;
        }

        return $asistenciasActualizadas;
    }

    /**
     * Procesa programación masiva de clases de recuperación
     */
    private function procesarProgramacionRecuperaciones($parametros)
    {
        $fechaInicio = $parametros['fecha_inicio'];
        $fechaFin = $parametros['fecha_fin'];
        $cursoAsignaturaIds = $parametros['curso_asignatura_ids'] ?? null;

        $clasesProgramadas = 0;

        // Obtener ausencias en el período
        $query = AsistenciaAsignatura::whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->whereHas('tipoAsistencia', function ($q) {
                $q->where('computa_falta', true);
            });

        if ($cursoAsignaturaIds) {
            $query->whereIn('curso_asignatura_id', $cursoAsignaturaIds);
        }

        $ausencias = $query->with('cursoAsignatura')->get();

        foreach ($ausencias as $ausencia) {
            $fechaRecuperacion = $this->encontrarFechaRecuperacionDisponible($ausencia->fecha);

            ClaseRecuperacion::create([
                'curso_asignatura_id' => $ausencia->curso_asignatura_id,
                'fecha_original' => $ausencia->fecha,
                'fecha_recuperacion' => $fechaRecuperacion,
                'motivo' => 'inasistencia',
                'observaciones' => 'Recuperación programada automáticamente',
                'profesor_id' => $ausencia->cursoAsignatura->profesor_id ?? auth()->id(),
                'estado' => 'programada',
                'duracion_minutos' => 60,
                'creado_por' => auth()->id()
            ]);

            $clasesProgramadas++;
        }

        return $clasesProgramadas;
    }

    // Métodos auxiliares (extraídos del controlador original)

    private function contarAusenciasConsecutivas($matriculaId)
    {
        $asistencias = AsistenciaAsignatura::where('matricula_id', $matriculaId)
            ->orderBy('fecha', 'desc')
            ->take(10)
            ->get();

        $consecutivas = 0;
        foreach ($asistencias as $asistencia) {
            if (optional($asistencia->tipoAsistencia)->computa_falta) {
                $consecutivas++;
            } else {
                break;
            }
        }

        return $consecutivas;
    }

    private function calcularPorcentajeAsistenciaMes($matriculaId)
    {
        $asistencias = AsistenciaAsignatura::where('matricula_id', $matriculaId)
            ->whereMonth('fecha', Carbon::now()->month)
            ->get();

        if ($asistencias->isEmpty()) {
            return 100;
        }

        $presentes = $asistencias->filter(function ($a) {
            return ! optional($a->tipoAsistencia)->computa_falta;
        })->count();

        $total = $asistencias->count();

        return $total > 0 ? round(($presentes / $total) * 100, 1) : 0;
    }

    private function calcularRachaAsistencia($matriculaId)
    {
        $asistencias = AsistenciaAsignatura::where('matricula_id', $matriculaId)
            ->orderBy('fecha', 'desc')
            ->take(30)
            ->get();

        $racha = 0;
        foreach ($asistencias as $asistencia) {
            if (! optional($asistencia->tipoAsistencia)->computa_falta) {
                $racha++;
            } else {
                break;
            }
        }

        return $racha;
    }

    private function calcularTendencia($asistencias)
    {
        if ($asistencias->isEmpty()) {
            return 'estable';
        }

        $asistenciasOrdenadas = $asistencias->sortBy('fecha');
        $totalRegistros = $asistenciasOrdenadas->count();
        $mitad = (int) ($totalRegistros / 2);

        $primerPeriodo = $asistenciasOrdenadas->take($mitad);
        $segundoPeriodo = $asistenciasOrdenadas->skip($mitad);

        $porcentajePrimerPeriodo = $this->calcularPorcentajePeriodo($primerPeriodo);
        $porcentajeSegundoPeriodo = $this->calcularPorcentajePeriodo($segundoPeriodo);

        $diferencia = $porcentajeSegundoPeriodo - $porcentajePrimerPeriodo;

        if ($diferencia > 5) {
            return 'mejorando';
        } elseif ($diferencia < -5) {
            return 'empeorando';
        } else {
            return 'estable';
        }
    }

    private function calcularPorcentajePeriodo($asistencias)
    {
        if ($asistencias->isEmpty()) {
            return 0;
        }

        $presentes = $asistencias->filter(function ($a) {
            return ! optional($a->tipoAsistencia)->computa_falta;
        })->count();

        $total = $asistencias->count();

        return $total > 0 ? round(($presentes / $total) * 100, 1) : 0;
    }
}
