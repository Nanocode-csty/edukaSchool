<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotasFinalesAnuales extends Model
{
    use HasFactory;

    protected $table = 'notasfinalesanuales';
    protected $primaryKey = 'nota_anual_id';
    public $timestamps = false;

    protected $fillable = [
        'matricula_id',
        'curso_asignatura_id',
        'promedio_final',
        'estado',
        'observaciones',
        'fecha_registro',
        'usuario_registro'
    ];

    protected $casts = [
        'promedio_final' => 'decimal:2',
        'fecha_registro' => 'datetime'
    ];

    // Relationships
    public function matricula()
    {
        return $this->belongsTo(Matricula::class, 'matricula_id', 'matricula_id');
    }

    public function cursoAsignatura()
    {
        return $this->belongsTo(CursoAsignatura::class, 'curso_asignatura_id', 'curso_asignatura_id');
    }
}
