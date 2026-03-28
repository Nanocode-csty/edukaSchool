<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notificacion extends Model
{
    use HasFactory;

    protected $table = 'notificaciones';

    protected $fillable = [
        'usuario_id',
        'titulo',
        'mensaje',
        'tipo',
        'datos',
        'url_accion',
        'leido_en'
    ];

    protected $casts = [
        'datos' => 'array',
        'leido_en' => 'datetime'
    ];

    // Relationships
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    // Scopes
    public function scopeNoLeidas($query)
    {
        return $query->whereNull('leido_en');
    }

    public function scopeLeidas($query)
    {
        return $query->whereNotNull('leido_en');
    }

    public function scopeParaUsuario($query, $usuarioId)
    {
        return $query->where('usuario_id', $usuarioId);
    }

    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    // Helper methods
    public function marcarComoLeida()
    {
        $this->update(['leido_en' => now()]);
        return $this;
    }

    public function estaLeida()
    {
        return !is_null($this->leido_en);
    }

    public function getTiempoTranscurridoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getIconoAttribute()
    {
        return match($this->tipo) {
            'justificacion_pendiente' => 'fas fa-clipboard-check',
            'justificacion_aprobada' => 'fas fa-check-circle',
            'justificacion_rechazada' => 'fas fa-times-circle',
            'asistencia_pendiente' => 'fas fa-calendar-times',
            'sistema' => 'fas fa-cog',
            'recordatorio' => 'fas fa-bell',
            default => 'fas fa-bell'
        };
    }

    public function getColorAttribute()
    {
        return match($this->tipo) {
            'justificacion_pendiente' => 'warning',
            'justificacion_aprobada' => 'success',
            'justificacion_rechazada' => 'danger',
            'asistencia_pendiente' => 'info',
            'sistema' => 'secondary',
            'recordatorio' => 'primary',
            default => 'secondary'
        };
    }

    // Static methods for creating notifications
    public static function crearNotificacionJustificacionPendiente($justificacionId)
    {
        $justificacion = JustificacionAsistencia::with(['matricula.estudiante'])->find($justificacionId);

        if (!$justificacion) return null;

        // Notify administrators
        $admins = Usuario::where('rol', 'Administrador')->get();

        foreach ($admins as $admin) {
            static::create([
                'usuario_id' => $admin->id,
                'titulo' => 'Nueva justificación pendiente',
                'mensaje' => "El estudiante {$justificacion->matricula->estudiante->nombres} {$justificacion->matricula->estudiante->apellidos} ha solicitado una justificación de inasistencia.",
                'tipo' => 'justificacion_pendiente',
                'datos' => [
                    'justificacion_id' => $justificacion->id,
                    'estudiante_id' => $justificacion->matricula->estudiante->id
                ],
                'url_accion' => route('asistencia.verificar')
            ]);
        }
    }

    public static function crearNotificacionJustificacionResuelta($justificacionId, $aprobada = true)
    {
        $justificacion = JustificacionAsistencia::with(['matricula.estudiante', 'usuarioCreador'])->find($justificacionId);

        if (!$justificacion || !$justificacion->usuarioCreador) return null;

        $estado = $aprobada ? 'aprobada' : 'rechazada';
        $titulo = "Justificación " . ($aprobada ? 'aprobada' : 'rechazada');

        static::create([
            'usuario_id' => $justificacion->usuario_id,
            'titulo' => $titulo,
            'mensaje' => "Tu justificación de inasistencia para {$justificacion->matricula->estudiante->nombres} {$justificacion->matricula->estudiante->apellidos} ha sido {$estado}.",
            'tipo' => $aprobada ? 'justificacion_aprobada' : 'justificacion_rechazada',
            'datos' => [
                'justificacion_id' => $justificacion->id,
                'estudiante_id' => $justificacion->matricula->estudiante->id
            ],
            'url_accion' => route('asistencia.representante.index')
        ]);
    }
}
