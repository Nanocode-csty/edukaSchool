<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotasFinalesPeriodo extends Model
{
    use HasFactory;

    protected $table = 'notasfinalesperiodo';
    protected $primaryKey = 'nota_final_id';
    public $timestamps = false;

    protected $fillable = [
        'matricula_id',
        'curso_asignatura_id',
        'periodo_id',
        'promedio',
        'promedio_letra',
        'observaciones',
        'estado',
        'fecha_calculo',
        'fecha_publicacion',
        'usuario_registro'
    ];

    protected $dates = [
        'fecha_calculo',
        'fecha_publicacion'
    ];

    // Relación con la matrícula
    public function matricula()
    {
        return $this->belongsTo(Matricula::class, 'matricula_id', 'matricula_id');
    }

    // Relación con el curso-asignatura
    public function cursoAsignatura()
    {
        return $this->belongsTo(CursoAsignatura::class, 'curso_asignatura_id', 'curso_asignatura_id');
    }

    // Relación con el periodo de evaluación
    public function periodo()
    {
        return $this->belongsTo(InfPeriodosEvaluacion::class, 'periodo_id', 'periodo_id');
    }
}
