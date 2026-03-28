<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalificacionCompetencia extends Model
{
    use HasFactory;

    protected $table = 'calificaciones_competencias';
    protected $primaryKey = 'calificacion_competencia_id';

    protected $fillable = [
        'matricula_id',
        'competencia_id',
        'periodo_id',
        'calificacion',
        'usuario_registro'
    ];

    public function competencia()
    {
        return $this->belongsTo(Competencia::class, 'competencia_id', 'competencia_id');
    }

    public function matricula()
    {
        return $this->belongsTo(Matricula::class, 'matricula_id', 'matricula_id');
    }

    public function periodo()
    {
        return $this->belongsTo(InfPeriodosEvaluacion::class, 'periodo_id', 'periodo_id');
    }
}
