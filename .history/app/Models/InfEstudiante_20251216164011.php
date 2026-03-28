<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfEstudiante extends Model
{
    use HasFactory;

    protected $table = 'estudiantes';

    protected $primaryKey = 'estudiante_id';

    public $timestamps = false;

    protected $fillable = [
        'persona_id',
        'codigo_estudiante',
        'fecha_matricula',
        'grado_actual',
        'seccion_actual',
        'situacion_academica',
        'observaciones_estudiante'
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id', 'id_persona');
    }

    public function representante()
    {
        return $this->hasOne(InfEstudianteRepresentante::class, 'estudiante_id');
    }

    public function matriculas()
    {
        return $this->hasMany(Matricula::class, 'estudiante_id', 'estudiante_id');
    }
}
