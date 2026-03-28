<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CursoAsignatura extends Model
{
    use HasFactory;

    protected $table = 'cursoasignaturas';
    protected $primaryKey = 'curso_asignatura_id';
    public $timestamps = false;

    protected $fillable = [
        'curso_id',
        'asignatura_id',
        'profesor_id',
        'horas_semanales',
        'dia_semana',
        'hora_inicio',
        'hora_fin'
    ];

    // Relación con el curso
    public function curso()
    {
        return $this->belongsTo(InfCurso::class, 'curso_id', 'curso_id');
    }

    // Relación con la asignatura
    public function asignatura()
    {
        return $this->belongsTo(InfAsignatura::class, 'asignatura_id', 'asignatura_id');
    }

    // Relación con el profesor
    public function profesor()
    {
        return $this->belongsTo(InfDocente::class, 'profesor_id', 'profesor_id');
    }


    // Relación con las notas por período
    public function notasPeriodos()
    {
        return $this->hasMany(NotasFinalesPeriodo::class, 'curso_asignatura_id', 'curso_asignatura_id');
    }
}
