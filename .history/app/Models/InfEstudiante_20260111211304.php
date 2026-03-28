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

    public function representantes()
    {
        return $this->belongsToMany(InfRepresentante::class, 'estudiante_representante', 'estudiante_id', 'representante_id');
    }

    public function matriculas()
    {
        return $this->hasMany(Matricula::class, 'estudiante_id', 'estudiante_id');
    }

    public function matricula()
    {
        return $this->hasOne(Matricula::class, 'estudiante_id', 'estudiante_id')->where('estado', 'Matriculado');
    }

    // Accessors para campos de persona
    public function getDniAttribute()
    {
        return $this->persona?->dni;
    }

    public function getNombresAttribute()
    {
        return $this->persona?->nombres;
    }

    public function getApellidosAttribute()
    {
        // Adaptar al esquema actual de la base de datos que usa 'apellidos' en lugar de 'apellido_paterno' y 'apellido_materno'
        return $this->persona?->apellidos ?? '';
    }

    public function getTelefonoAttribute()
    {
        return $this->persona?->telefono;
    }

    public function getEmailAttribute()
    {
        return $this->persona?->email;
    }

    public function getEstadoAttribute()
    {
        return $this->persona?->estado ?? 'Inactivo';
    }

    public function getNombreCompletoAttribute()
    {
        return $this->persona?->nombre_completo;
    }
}
