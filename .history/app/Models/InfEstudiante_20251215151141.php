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

    protected $fillable = ['dni', 'nombres', 'apellidos', 'fecha_nacimiento', 'genero', 'direccion', 'telefono', 'email', 'fecha_registro',	'estado', 'foto_url', 'observaciones'];

    public function representante()
    {
        return $this->hasOne(InfEstudianteRepresentante::class, 'estudiante_id');
    }

    public function matriculas()
    {
        return $this->hasMany(Matricula::class, 'estudiante_id', 'estudiante_id');
    }
}
