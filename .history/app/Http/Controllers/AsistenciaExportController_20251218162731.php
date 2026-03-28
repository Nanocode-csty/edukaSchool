<?php

namespace App\Http\Controllers;

use App\Exports\AsistenciaExport;
use App\Models\AsistenciaAsignatura;
use App\Models\InfCurso;
use App\Models\Matricula;
use App\Models\User;
use App\Notifications\AsistenciaNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Setting;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class AsistenciaExportController extends Controller
{
    /**
     * Exportar asistencias a Excel
     */
    public function exportarExcel(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio', Carbon::now()->startOfMonth());
        $fechaFin = $request->get('fecha_fin', Carbon::now());
        $cursoId = $request->get('curso_id');

        $filename = 'asistencias_'.date('Y-m-d_His').'.xlsx';

        return Excel::download(
            new AsistenciaExport($fechaInicio, $fechaFin, $cursoId),
            $filename
        );
    }

    /**
     * Exportar reporte individual a PDF
     */
    public function exportarPDF($matriculaId, Request $request)
    {
        $matricula = Matricula::with(['estudiante', 'curso'])->findOrFail($matriculaId);

        $fechaInicio = $request->get('fecha_inicio', Carbon::now()->startOfMonth());
        $fechaFin = $request->get('fecha_fin', Carbon::now());

        $asistencias = AsistenciaAsignatura::with(['tipoAsistencia', 'cursoAsignatura.asignatura'])
            ->where('matricula_id', $matriculaId)
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->orderBy('fecha', 'desc')
            ->get();

        $estadisticas = [
            'total' => $asistencias->count(),
            'por_tipo' => $asistencias->groupBy('tipo_asistencia_id')->map->count(),
            'porcentaje_asistencia' => $this->calcularPorcentajeAsistencia($asistencias),
        ];

        $pdf = PDF::loadView('asistencia.pdf.reporte-estudiante', compact(
            'matricula',
            'asistencias',
            'estadisticas',
            'fechaInicio',
            'fechaFin'
        ));

        return $pdf->download('reporte_asistencia_'.$matricula->estudiante->documento.'.pdf');
    }

    /**
     * Sistema de notificaciones automáticas
     */
    public function enviarNotificaciones($asistencia)
    {
        $matricula = $asistencia->matricula;
        $estudiante = $matricula->estudiante;

        // Verificar condiciones de alerta
        $ausenciasConsecutivas = $this->contarAusenciasConsecutivas($asistencia->matricula_id);
        $porcentajeMensual = $this->calcularPorcentajeAsistenciaMes($asistencia->matricula_id);

        // Notificar a representantes
        if ($ausenciasConsecutivas >= 3 || $porcentajeMensual < 75) {
            foreach ($estudiante->representantes as $representante) {
                // Email
                $representante->notify(new AsistenciaNotification([
                    'tipo' => 'alerta',
                    'estudiante' => $estudiante->nombres.' '.$estudiante->apellidos,
                    'ausencias_consecutivas' => $ausenciasConsecutivas,
                    'porcentaje_asistencia' => $porcentajeMensual,
                    'mensaje' => $this->generarMensajeAlerta($ausenciasConsecutivas, $porcentajeMensual),
                ]));

                // SMS (si está configurado)
                if ($representante->telefono) {
                    $this->enviarSMS($representante->telefono, [
                        'estudiante' => $estudiante->nombres,
                        'ausencias' => $ausenciasConsecutivas,
                    ]);
                }

                // WhatsApp (si está configurado)
                if (config('services.whatsapp.enabled')) {
                    $this->enviarWhatsApp($representante->telefono, [
                        'estudiante' => $estudiante->nombres.' '.$estudiante->apellidos,
                        'ausencias' => $ausenciasConsecutivas,
                        'porcentaje' => $porcentajeMensual,
                    ]);
                }
            }
        }

        // Notificar al coordinador si es crítico
        if ($ausenciasConsecutivas >= 5 || $porcentajeMensual < 60) {
            $coordinador = User::role('Coordinador')->first();
            if ($coordinador) {
                $coordinador->notify(new AsistenciaNotification([
                    'tipo' => 'critica',
                    'estudiante' => $estudiante->nombres.' '.$estudiante->apellidos,
                    'curso' => $matricula->curso->grado->nombre.' - '.$matricula->curso->seccion->nombre,
                    'ausencias_consecutivas' => $ausenciasConsecutivas,
                    'porcentaje_asistencia' => $porcentajeMensual,
                ]));
            }
        }
    }

    /**
     * Enviar SMS usando servicio externo (Twilio, etc.)
     */
    private function enviarSMS($telefono, $datos)
    {
        // Implementación con Twilio u otro proveedor
        // $twilio = new Client(config('services.twilio.sid'), config('services.twilio.token'));

        $mensaje = "Alerta Escolar: {$datos['estudiante']} tiene {$datos['ausencias']} ausencias consecutivas. Por favor contacte al colegio.";

        // $twilio->messages->create($telefono, [
        //     'from' => config('services.twilio.from'),
        //     'body' => $mensaje
        // ]);
    }

    /**
     * Enviar mensaje de WhatsApp
     */
    private function enviarWhatsApp($telefono, $datos)
    {
        // Implementación con WhatsApp Business API
        $mensaje = "🔔 *Alerta de Asistencia*\n\n";
        $mensaje .= "Estudiante: {$datos['estudiante']}\n";
        $mensaje .= "Ausencias consecutivas: {$datos['ausencias']}\n";
        $mensaje .= "Porcentaje de asistencia: {$datos['porcentaje']}%\n\n";
        $mensaje .= 'Por favor, comuníquese con la institución para más información.';

        // Aquí iría la integración con la API de WhatsApp
    }

    /**
     * Generar mensaje de alerta personalizado
     */
    private function generarMensajeAlerta($ausencias, $porcentaje)
    {
        if ($ausencias >= 5) {
            return "URGENTE: El estudiante ha acumulado {$ausencias} ausencias consecutivas. Se requiere intervención inmediata.";
        } elseif ($ausencias >= 3) {
            return "ATENCIÓN: Se han registrado {$ausencias} ausencias consecutivas. Por favor justificar o comunicarse con el colegio.";
        } elseif ($porcentaje < 60) {
            return "ALERTA: El porcentaje de asistencia ({$porcentaje}%) está por debajo del mínimo requerido (75%).";
        } elseif ($porcentaje < 75) {
            return "ADVERTENCIA: El porcentaje de asistencia ({$porcentaje}%) está cerca del límite mínimo.";
        }

        return 'Se ha registrado una situación que requiere su atención.';
    }

    /**
     * Reporte general para administradores
     */
    public function reporteGeneral(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio', Carbon::now()->startOfMonth());
        $fechaFin = $request->get('fecha_fin', Carbon::now());

        // Estadísticas globales
        $estadisticasGlobales = [
            'total_estudiantes' => Matricula::where('estado', 'Matriculado')->count(),
            'total_registros' => AsistenciaAsignatura::whereBetween('fecha', [$fechaInicio, $fechaFin])->count(),
            'promedio_asistencia' => $this->calcularPromedioGlobal($fechaInicio, $fechaFin),
        ];

        // Estudiantes con alertas
        $estudiantesRiesgo = $this->obtenerEstudiantesEnRiesgo($fechaInicio, $fechaFin);

        // Ranking de cursos por asistencia
        $rankingCursos = $this->obtenerRankingCursos($fechaInicio, $fechaFin);

        // Tendencias mensuales
        $tendenciasMensuales = $this->calcularTendenciasMensuales();

        return view('asistencia.reporte-general', compact(
            'estadisticasGlobales',
            'estudiantesRiesgo',
            'rankingCursos',
            'tendenciasMensuales',
            'fechaInicio',
            'fechaFin'
        ));
    }

    /**
     * Obtener estudiantes en riesgo
     */
    private function obtenerEstudiantesEnRiesgo($fechaInicio, $fechaFin)
    {
        $matriculas = Matricula::with(['estudiante', 'curso'])->where('estado', 'Matriculado')->get();
        $estudiantesRiesgo = [];

        foreach ($matriculas as $matricula) {
            $asistencias = AsistenciaAsignatura::where('matricula_id', $matricula->matricula_id)
                ->whereBetween('fecha', [$fechaInicio, $fechaFin])
                ->get();

            $porcentaje = $this->calcularPorcentajeAsistencia($asistencias);
            $ausenciasConsecutivas = $this->contarAusenciasConsecutivas($matricula->matricula_id);

            if ($porcentaje < 75 || $ausenciasConsecutivas >= 3) {
                $estudiantesRiesgo[] = [
                    'matricula' => $matricula,
                    'porcentaje' => $porcentaje,
                    'ausencias_consecutivas' => $ausenciasConsecutivas,
                    'nivel_riesgo' => $this->determinarNivelRiesgo($porcentaje, $ausenciasConsecutivas),
                ];
            }
        }

        // Ordenar por nivel de riesgo
        usort($estudiantesRiesgo, function ($a, $b) {
            return $b['nivel_riesgo'] <=> $a['nivel_riesgo'];
        });

        return collect($estudiantesRiesgo);
    }

    /**
     * Determinar nivel de riesgo (1-5)
     */
    private function determinarNivelRiesgo($porcentaje, $ausencias)
    {
        if ($ausencias >= 7 || $porcentaje < 50) {
            return 5;
        } // Crítico
        if ($ausencias >= 5 || $porcentaje < 60) {
            return 4;
        } // Muy alto
        if ($ausencias >= 3 || $porcentaje < 70) {
            return 3;
        } // Alto
        if ($porcentaje < 75) {
            return 2;
        } // Medio

        return 1; // Bajo
    }

    /**
     * Calcular ranking de cursos
     */
    private function obtenerRankingCursos($fechaInicio, $fechaFin)
    {
        $cursos = InfCurso::with(['grado', 'seccion'])->get();
        $ranking = [];

        foreach ($cursos as $curso) {
            $asistencias = AsistenciaAsignatura::whereHas('cursoAsignatura', function ($q) use ($curso) {
                $q->where('curso_id', $curso->curso_id);
            })
                ->whereBetween('fecha', [$fechaInicio, $fechaFin])
                ->get();

            $porcentaje = $this->calcularPorcentajeAsistencia($asistencias);

            $ranking[] = [
                'curso' => $curso,
                'porcentaje' => $porcentaje,
                'total_registros' => $asistencias->count(),
            ];
        }

        usort($ranking, function ($a, $b) {
            return $b['porcentaje'] <=> $a['porcentaje'];
        });

        return collect($ranking);
    }

    /**
     * Calcular tendencias mensuales
     */
    private function calcularTendenciasMensuales()
    {
        $meses = [];
        for ($i = 5; $i >= 0; $i--) {
            $fecha = Carbon::now()->subMonths($i);
            $inicio = $fecha->copy()->startOfMonth();
            $fin = $fecha->copy()->endOfMonth();

            $asistencias = AsistenciaAsignatura::whereBetween('fecha', [$inicio, $fin])->get();

            $meses[] = [
                'mes' => $fecha->isoFormat('MMMM YYYY'),
                'porcentaje' => $this->calcularPorcentajeAsistencia($asistencias),
                'total' => $asistencias->count(),
            ];
        }

        return collect($meses);
    }

    /**
     * Calcular promedio global
     */
    private function calcularPromedioGlobal($fechaInicio, $fechaFin)
    {
        $asistencias = AsistenciaAsignatura::whereBetween('fecha', [$fechaInicio, $fechaFin])->get();

        return $this->calcularPorcentajeAsistencia($asistencias);
    }

    /**
     * Configuración del sistema de asistencia
     */
    public function configuracion()
    {
        $config = [
            'umbral_alerta_porcentaje' => config('asistencia.umbral_alerta_porcentaje', 75),
            'umbral_ausencias_consecutivas' => config('asistencia.umbral_ausencias_consecutivas', 3),
            'notificaciones_email' => config('asistencia.notificaciones_email', true),
            'notificaciones_sms' => config('asistencia.notificaciones_sms', false),
            'notificaciones_whatsapp' => config('asistencia.notificaciones_whatsapp', false),
            'reconocimiento_facial' => config('asistencia.reconocimiento_facial', false),
            'justificacion_automatica_dias' => config('asistencia.justificacion_automatica_dias', 3),
        ];

        return view('asistencia.configuracion', compact('config'));
    }

    /**
     * Guardar configuración
     */
    public function guardarConfiguracion(Request $request)
    {
        $validated = $request->validate([
            'umbral_alerta_porcentaje' => 'required|integer|min:1|max:100',
            'umbral_ausencias_consecutivas' => 'required|integer|min:1|max:30',
            'notificaciones_email' => 'boolean',
            'notificaciones_sms' => 'boolean',
            'notificaciones_whatsapp' => 'boolean',
            'reconocimiento_facial' => 'boolean',
            'justificacion_automatica_dias' => 'required|integer|min:1|max:30',
        ]);

        // Guardar en archivo de configuración o base de datos
        foreach ($validated as $key => $value) {
            Setting::set("asistencia.{$key}", $value);
        }

        return back()->with('success', 'Configuración guardada correctamente');
    }

    /**
     * Calcular porcentaje de asistencia
     */
    private function calcularPorcentajeAsistencia($asistencias)
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

    /**
     * Contar ausencias consecutivas
     */
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

    /**
     * Calcular porcentaje de asistencia mensual
     */
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
}
