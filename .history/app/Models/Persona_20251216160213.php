<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;

    protected $table = 'personas';
    protected $primaryKey = 'id_persona';
    public $timestamps = false;

    protected $fillable = [
        'dni',
        'nombres',
        'apellidos',
        'fecha_nacimiento',
        'genero',
        'direccion',
        'telefono',
        'email',
        'estado'
    ];

    protected $casts = [
        'id_persona' => 'integer',
        'fecha_nacimiento' => 'date',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'persona_id', 'persona_id');
    }

    public function roles()
    {
        return $this->belongsToMany(Rol::class, 'persona_roles', 'persona_id', 'rol_id')
                    ->withPivot('fecha_asignacion', 'estado')
                    ->withTimestamps();
    }

    public function estudiante()
    {
        return $this->hasOne(InfEstudiante::class, 'persona_id', 'persona_id');
    }

    public function docente()
    {
        return $this->hasOne(InfDocente::class, 'persona_id', 'persona_id');
    }

    public function representante()
    {
        return $this->hasOne(InfRepresentante::class, 'persona_id', 'persona_id');
    }

    public function getNombreCompletoAttribute()
    {
        return trim($this->nombres . ' ' . $this->apellido_paterno . ' ' . $this->apellido_materno);
    }
}
