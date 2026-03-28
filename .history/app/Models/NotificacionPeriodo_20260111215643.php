<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class NotificacionPeriodo extends Model
{
    use HasFactory;

    protected $table = 'notificaciones_periodos';
    protected $primaryKey = 'notificacion_periodo_id';

    protected $fillable = [
        'titulo',
        'mensaje',
        'tipo_notificacion',
        'periodo_id',
        'usuario_id',
        'datos_adicionales',
        'fecha_programada',
        'fecha_enviada',
        'estado'
    ];

    protected $casts = [
        'datos_adicionales' => 'array',
        'fecha_programada' => 'datetime',
        'fecha_enviada' => 'datetime'
    ];

    // Relaciones
    public function periodo()
    {
        return $this->belongsTo(PeriodoMatricula::class, 'periodo_id', 'periodo_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'usuario_id');
    }

    // Scopes
    public function scopePendientes($query)
    {
        return $query->where('estado', 'PENDIENTE');
    }

    public function scopeEnviadas($query)
    {
        return $query->where('estado', 'ENVIADA');
    }

    public function scopeProgramadas($query)
    {
        return $query->where('estado', 'PENDIENTE')
                    ->whereNotNull('fecha_programada')
                    ->where('fecha_programada', '<=', now());
    }

    // Métodos
    public function marcarComoEnviada()
    {
        $this->update([
            'estado' => 'ENVIADA',
            'fecha_enviada' => now()
        ]);
    }

    public function cancelar()
    {
        $this->update(['estado' => 'CANCELADA']);
    }

    // Métodos estáticos para crear notificaciones
    public static function crearNotificacionInicioPeriodo(PeriodoMatricula $periodo)
    {
        return self::create([
            'titulo' => "Período {$periodo->tipo_periodo} Iniciado",
            'mensaje' => "El período '{$periodo->nombre}' ha iniciado. Del {$periodo->fecha_inicio->format('d/m/Y')} al {$periodo->fecha_fin->format('d/m/Y')}.",
            'tipo_notificacion' => 'PERIODO_INICIADO',
            'periodo_id' => $periodo->periodo_id,
            'fecha_programada' => $periodo->fecha_inicio,
            'datos_adicionales' => [
                'periodo_tipo' => $periodo->tipo_periodo,
                'fecha_inicio' => $periodo->fecha_inicio->format('Y-m-d'),
                'fecha_fin' => $periodo->fecha_fin->format('Y-m-d')
            ]
        ]);
    }

    public static function crearNotificacionFinPeriodo(PeriodoMatricula $periodo)
    {
        return self::create([
            'titulo' => "Período {$periodo->tipo_periodo} Finalizado",
            'mensaje' => "El período '{$periodo->nombre}' ha finalizado. Próximas acciones requeridas.",
            'tipo_notificacion' => 'PERIODO_TERMINADO',
            'periodo_id' => $periodo->periodo_id,
            'fecha_programada' => $periodo->fecha_fin->addDay(),
            'datos_adicionales' => [
                'periodo_tipo' => $periodo->tipo_periodo,
                'fecha_fin' => $periodo->fecha_fin->format('Y-m-d')
            ]
        ]);
    }

    public static function crearNotificacionPeriodoProximo(PeriodoMatricula $periodo, $diasAnticipacion = 7)
    {
        $fechaNotificacion = $periodo->fecha_inicio->copy()->subDays($diasAnticipacion);

        return self::create([
            'titulo' => "Período {$periodo->tipo_periodo} Próximo",
            'mensaje' => "El período '{$periodo->nombre}' iniciará en {$diasAnticipacion} días (el {$periodo->fecha_inicio->format('d/m/Y')}).",
            'tipo_notificacion' => 'PERIODO_PROXIMO',
            'periodo_id' => $periodo->periodo_id,
            'fecha_programada' => $fechaNotificacion,
            'datos_adicionales' => [
                'periodo_tipo' => $periodo->tipo_periodo,
                'dias_anticipacion' => $diasAnticipacion,
                'fecha_inicio' => $periodo->fecha_inicio->format('Y-m-d')
            ]
        ]);
    }

    public static function crearRecordatorioPeriodoActivo(PeriodoMatricula $periodo)
    {
        $mitadPeriodo = $periodo->fecha_inicio->copy()->addDays(
            $periodo->fecha_inicio->diffInDays($periodo->fecha_fin) / 2
        );

        return self::create([
            'titulo' => "Recordatorio: Período {$periodo->tipo_periodo} Activo",
            'mensaje' => "Recordatorio: El período '{$periodo->nombre}' está activo. Finaliza el {$periodo->fecha_fin->format('d/m/Y')}.",
            'tipo_notificacion' => 'RECORDATORIO',
            'periodo_id' => $periodo->periodo_id,
            'fecha_programada' => $mitadPeriodo,
            'datos_adicionales' => [
                'periodo_tipo' => $periodo->tipo_periodo,
                'fecha_fin' => $periodo->fecha_fin->format('Y-m-d')
            ]
        ]);
    }
}
