<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class ChatbotAnalyticsService
{
    /**
     * Registrar una interacción del chatbot
     */
    public function registrarInteraccion($userId, $comando, $rol = null)
    {
        try {
            $cacheKey = "chatbot_analytics_interaccion_{$userId}_" . now()->format('YmdHis');
            
            $data = [
                'user_id' => $userId,
                'comando' => $comando,
                'rol' => $rol,
                'timestamp' => now()->toDateTimeString(),
                'fecha' => now()->toDateString(),
                'hora' => now()->format('H:i:s'),
            ];
            
            // Guardar en cache por 7 días
            Cache::put($cacheKey, $data, now()->addDays(7));
            
            // También guardar en una lista de interacciones del día
            $dailyKey = "chatbot_analytics_daily_" . now()->format('Ymd');
            $interactions = Cache::get($dailyKey, []);
            $interactions[] = $data;
            Cache::put($dailyKey, $interactions, now()->addDays(7));
            
        } catch (\Exception $e) {
            \Log::error('Error al registrar interacción: ' . $e->getMessage());
        }
    }

    /**
     * Obtener estadísticas generales del chatbot
     */
    public function obtenerEstadisticasGenerales($dias = 7)
    {
        try {
            $stats = [
                'periodo' => "{$dias} días",
                'total_interacciones' => 0,
                'usuarios_unicos' => [],
                'comandos_mas_usados' => [],
                'interacciones_por_dia' => [],
                'interacciones_por_rol' => [],
                'horario_pico' => [],
            ];

            // Recopilar datos de los últimos N días
            for ($i = 0; $i < $dias; $i++) {
                $fecha = now()->subDays($i)->format('Ymd');
                $dailyKey = "chatbot_analytics_daily_{$fecha}";
                $interactions = Cache::get($dailyKey, []);
                
                if (!empty($interactions)) {
                    foreach ($interactions as $interaction) {
                        // Total de interacciones
                        $stats['total_interacciones']++;
                        
                        // Usuarios únicos
                        if (!in_array($interaction['user_id'], $stats['usuarios_unicos'])) {
                            $stats['usuarios_unicos'][] = $interaction['user_id'];
                        }
                        
                        // Comandos más usados
                        $comando = $interaction['comando'];
                        if (!isset($stats['comandos_mas_usados'][$comando])) {
                            $stats['comandos_mas_usados'][$comando] = 0;
                        }
                        $stats['comandos_mas_usados'][$comando]++;
                        
                        // Interacciones por día
                        $dia = $interaction['fecha'];
                        if (!isset($stats['interacciones_por_dia'][$dia])) {
                            $stats['interacciones_por_dia'][$dia] = 0;
                        }
                        $stats['interacciones_por_dia'][$dia]++;
                        
                        // Interacciones por rol
                        if ($interaction['rol']) {
                            $rol = $interaction['rol'];
                            if (!isset($stats['interacciones_por_rol'][$rol])) {
                                $stats['interacciones_por_rol'][$rol] = 0;
                            }
                            $stats['interacciones_por_rol'][$rol]++;
                        }
                        
                        // Horario pico (por hora)
                        $hora = substr($interaction['hora'], 0, 2); // Solo la hora
                        if (!isset($stats['horario_pico'][$hora])) {
                            $stats['horario_pico'][$hora] = 0;
                        }
                        $stats['horario_pico'][$hora]++;
                    }
                }
            }

            // Ordenar comandos más usados
            arsort($stats['comandos_mas_usados']);
            
            // Ordenar horario pico
            arsort($stats['horario_pico']);
            
            // Contar usuarios únicos
            $stats['total_usuarios_unicos'] = count($stats['usuarios_unicos']);
            unset($stats['usuarios_unicos']); // No necesitamos la lista completa

            return $stats;
            
        } catch (\Exception $e) {
            \Log::error('Error al obtener estadísticas: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Obtener estadísticas de un usuario específico
     */
    public function obtenerEstadisticasUsuario($userId, $dias = 30)
    {
        try {
            $stats = [
                'user_id' => $userId,
                'periodo' => "{$dias} días",
                'total_interacciones' => 0,
                'comandos_usados' => [],
                'ultimo_acceso' => null,
                'primer_acceso' => null,
                'dias_activos' => [],
                'comando_favorito' => null,
            ];

            for ($i = 0; $i < $dias; $i++) {
                $fecha = now()->subDays($i)->format('Ymd');
                $dailyKey = "chatbot_analytics_daily_{$fecha}";
                $interactions = Cache::get($dailyKey, []);
                
                if (!empty($interactions)) {
                    foreach ($interactions as $interaction) {
                        if ($interaction['user_id'] == $userId) {
                            $stats['total_interacciones']++;
                            
                            // Comandos usados
                            $comando = $interaction['comando'];
                            if (!isset($stats['comandos_usados'][$comando])) {
                                $stats['comandos_usados'][$comando] = 0;
                            }
                            $stats['comandos_usados'][$comando]++;
                            
                            // Último y primer acceso
                            $timestamp = Carbon::parse($interaction['timestamp']);
                            if (!$stats['ultimo_acceso'] || $timestamp->gt($stats['ultimo_acceso'])) {
                                $stats['ultimo_acceso'] = $timestamp;
                            }
                            if (!$stats['primer_acceso'] || $timestamp->lt($stats['primer_acceso'])) {
                                $stats['primer_acceso'] = $timestamp;
                            }
                            
                            // Días activos
                            if (!in_array($interaction['fecha'], $stats['dias_activos'])) {
                                $stats['dias_activos'][] = $interaction['fecha'];
                            }
                        }
                    }
                }
            }

            // Comando favorito
            if (!empty($stats['comandos_usados'])) {
                arsort($stats['comandos_usados']);
                $stats['comando_favorito'] = array_key_first($stats['comandos_usados']);
            }
            
            // Contar días activos
            $stats['total_dias_activos'] = count($stats['dias_activos']);
            
            // Formatear fechas
            if ($stats['ultimo_acceso']) {
                $stats['ultimo_acceso_formatted'] = $stats['ultimo_acceso']->format('d/m/Y H:i');
            }
            if ($stats['primer_acceso']) {
                $stats['primer_acceso_formatted'] = $stats['primer_acceso']->format('d/m/Y H:i');
            }

            return $stats;
            
        } catch (\Exception $e) {
            \Log::error('Error al obtener estadísticas de usuario: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Obtener comandos más populares
     */
    public function obtenerComandosPopulares($limite = 5, $dias = 7)
    {
        $stats = $this->obtenerEstadisticasGenerales($dias);
        
        if (!$stats || empty($stats['comandos_mas_usados'])) {
            return [];
        }

        $comandos = array_slice($stats['comandos_mas_usados'], 0, $limite, true);
        
        $resultado = [];
        foreach ($comandos as $comando => $count) {
            $resultado[] = [
                'comando' => $comando,
                'usos' => $count,
                'porcentaje' => round(($count / $stats['total_interacciones']) * 100, 1)
            ];
        }

        return $resultado;
    }

    /**
     * Obtener horario con más actividad
     */
    public function obtenerHorarioPico($dias = 7)
    {
        $stats = $this->obtenerEstadisticasGenerales($dias);
        
        if (!$stats || empty($stats['horario_pico'])) {
            return null;
        }

        $horarioPico = array_slice($stats['horario_pico'], 0, 3, true);
        
        $resultado = [];
        foreach ($horarioPico as $hora => $count) {
            $resultado[] = [
                'hora' => $hora . ':00 - ' . $hora . ':59',
                'interacciones' => $count
            ];
        }

        return $resultado;
    }

    /**
     * Obtener tendencias de uso
     */
    public function obtenerTendencias($dias = 7)
    {
        $stats = $this->obtenerEstadisticasGenerales($dias);
        
        if (!$stats) {
            return null;
        }

        $tendencias = [
            'crecimiento' => null,
            'promedio_diario' => 0,
            'dia_mas_activo' => null,
            'uso_por_rol' => [],
        ];

        // Promedio diario
        if ($stats['total_interacciones'] > 0) {
            $tendencias['promedio_diario'] = round($stats['total_interacciones'] / $dias, 1);
        }

        // Día más activo
        if (!empty($stats['interacciones_por_dia'])) {
            arsort($stats['interacciones_por_dia']);
            $diaMasActivo = array_key_first($stats['interacciones_por_dia']);
            $tendencias['dia_mas_activo'] = [
                'fecha' => Carbon::parse($diaMasActivo)->format('d/m/Y'),
                'interacciones' => $stats['interacciones_por_dia'][$diaMasActivo]
            ];
        }

        // Uso por rol
        if (!empty($stats['interacciones_por_rol'])) {
            $total = array_sum($stats['interacciones_por_rol']);
            foreach ($stats['interacciones_por_rol'] as $rol => $count) {
                $tendencias['uso_por_rol'][] = [
                    'rol' => ucfirst($rol),
                    'interacciones' => $count,
                    'porcentaje' => round(($count / $total) * 100, 1)
                ];
            }
        }

        // Calcular crecimiento (comparar primera mitad vs segunda mitad del periodo)
        $mitad = floor($dias / 2);
        $primerasMitad = 0;
        $segundaMitad = 0;
        
        foreach ($stats['interacciones_por_dia'] as $fecha => $count) {
            $fechaCarbon = Carbon::parse($fecha);
            $diasAtras = now()->startOfDay()->diffInDays($fechaCarbon->startOfDay());
            
            if ($diasAtras >= $mitad) {
                $primerasMitad += $count;
            } else {
                $segundaMitad += $count;
            }
        }

        if ($primerasMitad > 0) {
            $cambio = (($segundaMitad - $primerasMitad) / $primerasMitad) * 100;
            $tendencias['crecimiento'] = [
                'porcentaje' => round($cambio, 1),
                'direccion' => $cambio > 0 ? 'aumento' : ($cambio < 0 ? 'disminución' : 'estable')
            ];
        }

        return $tendencias;
    }

    /**
     * Generar reporte completo
     */
    public function generarReporte($dias = 7)
    {
        return [
            'periodo' => $dias,
            'fecha_generacion' => now()->format('d/m/Y H:i'),
            'estadisticas_generales' => $this->obtenerEstadisticasGenerales($dias),
            'comandos_populares' => $this->obtenerComandosPopulares(5, $dias),
            'horario_pico' => $this->obtenerHorarioPico($dias),
            'tendencias' => $this->obtenerTendencias($dias),
        ];
    }

    /**
     * Limpiar datos antiguos (mantener solo últimos N días)
     */
    public function limpiarDatosAntiguos($diasMantener = 30)
    {
        try {
            $eliminados = 0;
            
            for ($i = $diasMantener + 1; $i <= $diasMantener + 60; $i++) {
                $fecha = now()->subDays($i)->format('Ymd');
                $dailyKey = "chatbot_analytics_daily_{$fecha}";
                
                if (Cache::has($dailyKey)) {
                    Cache::forget($dailyKey);
                    $eliminados++;
                }
            }

            return $eliminados;
            
        } catch (\Exception $e) {
            \Log::error('Error al limpiar datos antiguos: ' . $e->getMessage());
            return 0;
        }
    }
}
