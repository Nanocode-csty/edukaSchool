<?php

namespace App\Services;

use App\Models\InfPago;
use App\Models\InfRepresentante;
use App\Models\InfEstudianteRepresentante;
use App\Models\Matricula;
use App\Models\NotasFinalesPeriodo;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class NotificacionProactivaService
{
    /**
     * Verificar pagos próximos a vencer para un representante
     * 
     * @param string $email Email del representante
     * @param int $diasAnticipacion Días de anticipación para la alerta
     * @return array
     */
    public function verificarPagosProximosVencer($email, $diasAnticipacion = 7)
    {
        try {
            $representante = InfRepresentante::where('email', $email)->first();
            
            if (!$representante) {
                return [];
            }

            // Obtener IDs de estudiantes
            $estudiantesIds = InfEstudianteRepresentante::where('representante_id', $representante->representante_id)
                ->pluck('estudiante_id');

            if ($estudiantesIds->isEmpty()) {
                return [];
            }

            // Fecha límite para notificaciones
            $fechaLimite = now()->addDays($diasAnticipacion);

            // Buscar pagos pendientes próximos a vencer
            $pagosPorVencer = InfPago::whereHas('matricula', function ($q) use ($estudiantesIds) {
                $q->whereIn('estudiante_id', $estudiantesIds)
                    ->where('estado', '!=', 'Anulado');
            })
                ->where('estado', 'pendiente')
                ->where('fecha_vencimiento', '<=', $fechaLimite)
                ->where('fecha_vencimiento', '>=', now())
                ->with(['matricula.estudiante', 'concepto'])
                ->orderBy('fecha_vencimiento', 'asc')
                ->get();

            return $pagosPorVencer->map(function ($pago) {
                $estudiante = $pago->matricula->estudiante ?? null;
                $diasRestantes = now()->diffInDays($pago->fecha_vencimiento, false);
                
                return [
                    'tipo' => 'pago_proximo_vencer',
                    'prioridad' => $diasRestantes <= 3 ? 'alta' : 'media',
                    'estudiante' => $estudiante ? "{$estudiante->nombres} {$estudiante->apellidos}" : 'Estudiante',
                    'concepto' => $pago->concepto->nombre ?? 'Pago',
                    'monto' => $pago->monto,
                    'fecha_vencimiento' => $pago->fecha_vencimiento->format('d/m/Y'),
                    'dias_restantes' => max(0, (int)$diasRestantes),
                    'mensaje' => $this->generarMensajePago($pago, $diasRestantes),
                ];
            })->toArray();

        } catch (\Exception $e) {
            Log::error('Error en verificarPagosProximosVencer: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Verificar pagos vencidos para un representante
     * 
     * @param string $email Email del representante
     * @return array
     */
    public function verificarPagosVencidos($email)
    {
        try {
            $representante = InfRepresentante::where('email', $email)->first();
            
            if (!$representante) {
                return [];
            }

            $estudiantesIds = InfEstudianteRepresentante::where('representante_id', $representante->representante_id)
                ->pluck('estudiante_id');

            if ($estudiantesIds->isEmpty()) {
                return [];
            }

            // Buscar pagos vencidos
            $pagosVencidos = InfPago::whereHas('matricula', function ($q) use ($estudiantesIds) {
                $q->whereIn('estudiante_id', $estudiantesIds)
                    ->where('estado', '!=', 'Anulado');
            })
                ->where('estado', 'pendiente')
                ->where('fecha_vencimiento', '<', now())
                ->with(['matricula.estudiante', 'concepto'])
                ->orderBy('fecha_vencimiento', 'asc')
                ->limit(5)
                ->get();

            return $pagosVencidos->map(function ($pago) {
                $estudiante = $pago->matricula->estudiante ?? null;
                $diasVencidos = now()->diffInDays($pago->fecha_vencimiento);
                
                return [
                    'tipo' => 'pago_vencido',
                    'prioridad' => 'alta',
                    'estudiante' => $estudiante ? "{$estudiante->nombres} {$estudiante->apellidos}" : 'Estudiante',
                    'concepto' => $pago->concepto->nombre ?? 'Pago',
                    'monto' => $pago->monto,
                    'fecha_vencimiento' => $pago->fecha_vencimiento->format('d/m/Y'),
                    'dias_vencidos' => (int)$diasVencidos,
                    'mensaje' => "⚠️ Pago vencido hace {$diasVencidos} día(s): {$pago->concepto->nombre} - S/ " . number_format($pago->monto, 2),
                ];
            })->toArray();

        } catch (\Exception $e) {
            Log::error('Error en verificarPagosVencidos: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Verificar nuevas calificaciones publicadas
     * 
     * @param string $email Email del representante
     * @param int $diasAtras Días hacia atrás para buscar
     * @return array
     */
    public function verificarNuevasCalificaciones($email, $diasAtras = 7)
    {
        try {
            $representante = InfRepresentante::where('email', $email)->first();
            
            if (!$representante) {
                return [];
            }

            $estudiantesIds = InfEstudianteRepresentante::where('representante_id', $representante->representante_id)
                ->pluck('estudiante_id');

            if ($estudiantesIds->isEmpty()) {
                return [];
            }

            $fechaLimite = now()->subDays($diasAtras);

            // Buscar notas actualizadas recientemente
            $notasRecientes = NotasFinalesPeriodo::whereHas('matricula', function ($q) use ($estudiantesIds) {
                $q->whereIn('estudiante_id', $estudiantesIds)
                    ->where('estado', '!=', 'Anulado');
            })
                ->where('updated_at', '>=', $fechaLimite)
                ->with(['matricula.estudiante', 'asignatura'])
                ->orderBy('updated_at', 'desc')
                ->limit(10)
                ->get();

            return $notasRecientes->map(function ($nota) {
                $estudiante = $nota->matricula->estudiante ?? null;
                $asignatura = $nota->asignatura->nombre ?? 'Asignatura';
                
                return [
                    'tipo' => 'nueva_calificacion',
                    'prioridad' => 'media',
                    'estudiante' => $estudiante ? "{$estudiante->nombres} {$estudiante->apellidos}" : 'Estudiante',
                    'asignatura' => $asignatura,
                    'nota' => $nota->nota_final,
                    'fecha_publicacion' => $nota->updated_at->format('d/m/Y'),
                    'mensaje' => "📊 Nueva calificación publicada: {$asignatura} - Nota: {$nota->nota_final}/20",
                ];
            })->toArray();

        } catch (\Exception $e) {
            Log::error('Error en verificarNuevasCalificaciones: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener todas las notificaciones para un usuario
     * 
     * @param string $email Email del usuario
     * @return array
     */
    public function obtenerTodasNotificaciones($email)
    {
        $notificaciones = [];

        // Pagos vencidos (prioridad alta)
        $pagosVencidos = $this->verificarPagosVencidos($email);
        $notificaciones = array_merge($notificaciones, $pagosVencidos);

        // Pagos próximos a vencer (prioridad media/alta)
        $pagosPorVencer = $this->verificarPagosProximosVencer($email, 7);
        $notificaciones = array_merge($notificaciones, $pagosPorVencer);

        // Nuevas calificaciones (prioridad media)
        $nuevasCalificaciones = $this->verificarNuevasCalificaciones($email, 7);
        $notificaciones = array_merge($notificaciones, $nuevasCalificaciones);

        // Ordenar por prioridad
        usort($notificaciones, function ($a, $b) {
            $prioridades = ['alta' => 3, 'media' => 2, 'baja' => 1];
            $prioridadA = $prioridades[$a['prioridad']] ?? 0;
            $prioridadB = $prioridades[$b['prioridad']] ?? 0;
            return $prioridadB - $prioridadA;
        });

        return $notificaciones;
    }

    /**
     * Generar mensaje personalizado para pago
     * 
     * @param InfPago $pago
     * @param int $diasRestantes
     * @return string
     */
    private function generarMensajePago($pago, $diasRestantes)
    {
        $concepto = $pago->concepto->nombre ?? 'Pago';
        $monto = number_format($pago->monto, 2);

        if ($diasRestantes <= 1) {
            return "🔴 URGENTE: {$concepto} vence mañana - S/ {$monto}";
        } elseif ($diasRestantes <= 3) {
            return "🟡 ATENCIÓN: {$concepto} vence en {$diasRestantes} días - S/ {$monto}";
        } else {
            return "🟢 RECORDATORIO: {$concepto} vence en {$diasRestantes} días - S/ {$monto}";
        }
    }

    /**
     * Contar notificaciones por prioridad
     * 
     * @param array $notificaciones
     * @return array
     */
    public function contarPorPrioridad($notificaciones)
    {
        $contador = [
            'alta' => 0,
            'media' => 0,
            'baja' => 0,
            'total' => count($notificaciones),
        ];

        foreach ($notificaciones as $notif) {
            $prioridad = $notif['prioridad'] ?? 'baja';
            if (isset($contador[$prioridad])) {
                $contador[$prioridad]++;
            }
        }

        return $contador;
    }
}
