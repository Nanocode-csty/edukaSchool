<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfDocente extends Model
{
    use HasFactory;
    protected $table = 'profesores';
    protected $primaryKey = 'profesor_id';
    public $timestamps=false;
    protected $fillable=['dni','nombres','apellidos','fecha_nacimiento','genero','direccion','telefono','email','especialidad','fecha_contratacion','estado','foto_url'];

    public function cursos()
    {
        return $this->hasMany('App\Models\InfCurso', 'profesor_principal_id', 'profesor_id');
    }
}
