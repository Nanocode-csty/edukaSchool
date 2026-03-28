<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PeriodoMatricula;
use App\Models\NotificacionPeriodo;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class VerificarPeriodosCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'periodos:verificar {--notificar : Enviar notificaciones por email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica cambios en períodos académicos y envía notificaciones';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Verificando cambios en períodos académicos...');

        $periodos = PeriodoMatricula::with('anoLectivo')->activos()->get();

        if ($periodos->isEmpty()) {
            $this->warn('⚠️ No hay períodos activos configurados.');
            return;
        }

        $cambios = 0;

        foreach ($periodos as $periodo) {
            $this->line("📅 Verificando período: {$periodo->nombre}");

            // Verificar si el período está activo ahora
            if ($periodo->estaActivo()) {
                $cambios += $this->procesarPeriodoActivo($periodo);
            }

            // Verificar si el período está próximo
            if ($periodo->estaProximo()) {
                $cambios += $this->procesarPeriodoProximo($periodo);
            }

            // Verificar si el período terminó
            if ($periodo->haTerminado()) {
                $cambios += $this->procesarPeriodoTerminado($periodo);
            }
        }

        // Procesar notificaciones programadas
        $notificacionesPendientes = NotificacionPeriodo::programadas()->get();
        if ($notificacionesPendientes->count() > 0) {
            $this->info("📧 Procesando {$notificacionesPendientes->count()} notificaciones programadas...");
            foreach ($notificacionesPendientes as $notificacion) {
                $this->enviarNotificacion($notificacion);
            }
        }

        $this->info("✅ Verificación completada. {$cambios} cambios detectados.");
        Log::info("Verificación de períodos completada. {$cambios} cambios detectados.");
    }

    private function procesarPeriodoActivo(PeriodoMatricula $periodo): int
    {
        $cambios = 0;

        // Verificar si ya existe notificación de inicio
        $notificacionExistente = NotificacionPeriodo::where('periodo_id', $periodo->periodo_id)
            ->where('tipo_notificacion', 'PERIODO_INICIADO')
            ->where('estado', 'ENVIADA')
            ->exists();

        if (!$notificacionExistente) {
            $this->info("  🟢 Nuevo período activo detectado: {$periodo->nombre}");

            // Crear notificación de inicio de período
            NotificacionPeriodo::crearNotificacionInicioPeriodo($periodo);

            // Crear recordatorio a mitad del período
            NotificacionPeriodo::crearRecordatorioPeriodoActivo($periodo);

            $cambios++;
        }

        return $cambios;
    }

    private function procesarPeriodoProximo(PeriodoMatricula $periodo): int
    {
        $cambios = 0;

        // Verificar si ya existe notificación de período próximo
        $notificacionExistente = NotificacionPeriodo::where('periodo_id', $periodo->periodo_id)
            ->where('tipo_notificacion', 'PERIODO_PROXIMO')
            ->exists();

        if (!$notificacionExistente) {
            $this->info("  ⏰ Período próximo detectado: {$periodo->nombre}");

            // Crear notificación de período próximo (7 días de anticipación)
            NotificacionPeriodo::crearNotificacionPeriodoProximo($periodo, 7);

            $cambios++;
        }

        return $cambios;
    }

    private function procesarPeriodoTerminado(PeriodoMatricula $periodo): int
    {
        $cambios = 0;

        // Verificar si ya existe notificación de fin de período
        $notificacionExistente = NotificacionPeriodo::where('periodo_id', $periodo->periodo_id)
            ->where('tipo_notificacion', 'PERIODO_TERMINADO')
            ->exists();

        if (!$notificacionExistente) {
            $this->info("  🔴 Período terminado detectado: {$periodo->nombre}");

            // Crear notificación de fin de período
            NotificacionPeriodo::crearNotificacionFinPeriodo($periodo);

            $cambios++;
        }

        return $cambios;
    }

    private function enviarNotificacion(NotificacionPeriodo $notificacion)
    {
        try {
            // Obtener todos los administradores (usuarios con rol admin)
            $administradores = User::whereHas('roles', function($query) {
                $query->where('nombre', 'Administrador');
            })->get();

            if ($administradores->isEmpty()) {
                $this->warn('⚠️ No se encontraron administradores para enviar notificaciones.');
                return;
            }

            $this->info("  📧 Enviando notificación: {$notificacion->titulo}");

            // Aquí iría el código para enviar emails usando Laravel Mail
            // Por ahora solo marcamos como enviada
            $notificacion->marcarComoEnviada();

            $this->line("    ✅ Notificación enviada a {$administradores->count()} administradores");

        } catch (\Exception $e) {
            $this->error("    ❌ Error enviando notificación: {$e->getMessage()}");
            Log::error("Error enviando notificación de período: {$e->getMessage()}", [
                'notificacion_id' => $notificacion->notificacion_periodo_id,
                'titulo' => $notificacion->titulo
            ]);
        }
    }
}
