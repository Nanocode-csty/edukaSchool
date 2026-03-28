<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JustificacionAsistencia extends Model
{
    protected $table = 'justificaciones_asistencia';

    protected $fillable = [
        'matricula_id',
        'fecha',
        'motivo',
        'descripcion',
        'documento_justificacion',
        'estado',
        'usuario_creador_id',
        'usuario_revisor_id',
        'fecha_revision',
        'observaciones_revision'
    ];

    protected $casts = [
        'fecha' => 'date',
        'fecha_revision' => 'datetime',
    ];

    // Relaciones
    public function matricula(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Matricula::class, 'matricula_id');
    }

    public function estudiante()
    {
        return $this->hasOneThrough(\App\Models\InfEstudiante::class, \App\Models\Matricula::class, 'id', 'id', 'matricula_id', 'estudiante_id');
    }

    public function usuarioCreador(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'usuario_id');
    }

    public function usuarioRevisor(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'usuario_revisor_id');
    }

    public function usuario()
    {
        return $this->belongsTo(\App\Models\User::class, 'usuario_id');
        return $query->where('estado', 'aprobado');
    }

    public function scopeRechazadas($query)
    {
        return $query->where('estado', 'rechazado');
    }

    // Métodos helper
    public function getEstadoBadgeAttribute()
    {
        return match($this->estado) {
            'pendiente' => '<span class="badge badge-warning">Pendiente</span>',
            'aprobado' => '<span class="badge badge-success">Aprobado</span>',
            'rechazado' => '<span class="badge badge-danger">Rechazado</span>',
            default => '<span class="badge badge-secondary">Desconocido</span>'
        };
    }

    public function getEstadoColorAttribute()
    {
        return match($this->estado) {
            'pendiente' => 'warning',
            'aprobado' => 'success',
            'rechazado' => 'danger',
            default => 'secondary'
        };
    }
}
