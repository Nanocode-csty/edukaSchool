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

        // Notify administrators - get users with administrador role through persona relationship
        $admins = Usuario::whereHas('persona.roles', function($query) {
            $query->where('nombre', 'administrador');
        })->get();

        foreach ($admins as $admin) {
            static::create([
                'usuario_id' => $admin->usuario_id,
                'titulo' => 'Nueva justificación pendiente',
                'mensaje' => "El estudiante {$justificacion->matricula->estudiante->nombres} {$justificacion->matricula->estudiante->apellidos} ha solicitado una justificación de inasistencia.",
                'tipo' => 'justificacion_pendiente',
                'datos' => [
                    'justificacion_id' => $justificacion->id,
                    'estudiante_id' => $justificacion->matricula->estudiante->estudiante_id
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

    /**
     * Crear notificación para representante sobre calificaciones de sus estudiantes
     */
    public static function crearNotificacionCalificacionesActualizadas($estudianteId, $representanteUsuarioId)
    {
        $estudiante = \App\Models\InfEstudiante::find($estudianteId);
        if (!$estudiante) return null;

        static::create([
            'usuario_id' => $representanteUsuarioId,
            'titulo' => 'Calificaciones Actualizadas',
            'mensaje' => "Se han actualizado las calificaciones de {$estudiante->nombres} {$estudiante->apellidos}. Revisa el rendimiento académico de tu estudiante.",
            'tipo' => 'recordatorio',
            'datos' => [
                'estudiante_id' => $estudianteId
            ],
            'url_accion' => route('calificaciones.representante')
        ]);
    }

    /**
     * Crear notificación para representante sobre asistencias de sus estudiantes
     */
    public static function crearNotificacionAsistenciaPendiente($estudianteId, $representanteUsuarioId)
    {
        $estudiante = \App\Models\InfEstudiante::find($estudianteId);
        if (!$estudiante) return null;

        static::create([
            'usuario_id' => $representanteUsuarioId,
            'titulo' => 'Revisar Asistencia',
            'mensaje' => "Hay registros de asistencia pendientes para revisar de {$estudiante->nombres} {$estudiante->apellidos}.",
            'tipo' => 'asistencia_pendiente',
            'datos' => [
                'estudiante_id' => $estudianteId
            ],
            'url_accion' => route('asistencia.representante.index')
        ]);
    }

    /**
     * Crear notificación semanal para representantes sobre el estado de sus estudiantes
     */
    public static function crearNotificacionResumenSemanal($representanteUsuarioId, $datosResumen)
    {
        static::create([
            'usuario_id' => $representanteUsuarioId,
            'titulo' => 'Resumen Semanal de Estudiantes',
            'mensaje' => "Resumen de la semana: {$datosResumen['total_estudiantes']} estudiantes, {$datosResumen['justificaciones_pendientes']} justificaciones pendientes, {$datosResumen['calificaciones_actualizadas']} calificaciones actualizadas.",
            'tipo' => 'recordatorio',
            'datos' => $datosResumen,
            'url_accion' => route('calificaciones.representante')
        ]);
    }

    /**
     * Crear notificaciones para todos los representantes de un estudiante
     */
    public static function notificarRepresentantesEstudiante($estudianteId, $tipoNotificacion, $datosAdicionales = [])
    {
        $representantes = \App\Models\InfEstudianteRepresentante::where('estudiante_id', $estudianteId)
            ->with(['representante.persona.usuario'])
            ->get();

        foreach ($representantes as $relacion) {
            if ($relacion->representante && $relacion->representante->persona && $relacion->representante->persona->usuario) {
                $usuarioId = $relacion->representante->persona->usuario->usuario_id;

                switch ($tipoNotificacion) {
                    case 'calificaciones_actualizadas':
                        static::crearNotificacionCalificacionesActualizadas($estudianteId, $usuarioId);
                        break;
                    case 'asistencia_pendiente':
                        static::crearNotificacionAsistenciaPendiente($estudianteId, $usuarioId);
                        break;
                }
            }
        }
    }
}
