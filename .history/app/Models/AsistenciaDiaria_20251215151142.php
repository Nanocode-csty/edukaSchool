<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AsistenciaDiaria extends Model
{
    use HasFactory;

    protected $table = 'asistenciasdiarias';
    protected $primaryKey = 'asistencia_id';
    public $timestamps = false;

    protected $fillable = [
        'matricula_id',
        'curso_id',
        'fecha',
        'tipo_asistencia_id',
        'hora_registro',
        'justificacion',
        'documento_justificacion',
        'estado',
        'usuario_registro'
    ];

    protected $casts = [
        'fecha' => 'date',
        'hora_registro' => 'datetime'
    ];

    // Relaciones
    public function matricula()
    {
        return $this->belongsTo(Matricula::class, 'matricula_id');
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'curso_id');
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

    public function scopePorCurso($query, $cursoId)
    {
        return $query->where('curso_id', $cursoId);
    }

    public function scopePorEstudiante($query, $matriculaId)
    {
        return $query->where('matricula_id', $matriculaId);
    }

    public function scopePorPeriodo($query, $fechaInicio, $fechaFin)
    {
        return $query->whereBetween('fecha', [$fechaInicio, $fechaFin]);
    }

    // Mutators
    public function setHoraRegistroAttribute($value)
    {
        $this->attributes['hora_registro'] = $value ?: now()->format('H:i:s');
    }

    // Accessors
    public function getEsFaltaAttribute()
    {
        return $this->tipoAsistencia->computa_falta ?? false;
    }

    public function getFactorAsistenciaAttribute()
    {
        return $this->tipoAsistencia->factor_asistencia ?? 1.00;
    }
}