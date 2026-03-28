<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsistenciaAsignatura extends Model
{
    use HasFactory;

    protected $table = 'asistenciasasignatura';

    protected $primaryKey = 'asistencia_asignatura_id';

    public $timestamps = false;

    protected $fillable = [
        'matricula_id',
        'curso_asignatura_id',
        'fecha',
        'tipo_asistencia_id',
        'hora_registro',
        'justificacion',
        'estado',
        'usuario_registro',
    ];

    protected $casts = [
        'fecha' => 'date',
        'hora_registro' => 'datetime',
    ];

    // Relaciones
    public function matricula()
    {
        return $this->belongsTo(Matricula::class, 'matricula_id');
    }

    public function cursoAsignatura()
    {
        return $this->belongsTo(CursoAsignatura::class, 'curso_asignatura_id');
    }

    public function tipoAsistencia()
    {
        return $this->belongsTo(TipoAsistencia::class, 'tipo_asistencia_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_registro');
    }

    // Scopes
    public function scopePorFecha($query, $fecha)
    {
        return $query->where('fecha', $fecha);
    }

    public function scopePorAsignatura($query, $cursoAsignaturaId)
    {
        return $query->where('curso_asignatura_id', $cursoAsignaturaId);
    }

    public function scopePorEstudiante($query, $matriculaId)
    {
        return $query->where('matricula_id', $matriculaId);
    }
}
