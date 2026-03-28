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
    protected $fillable=['persona_id', 'especialidad','fecha_contratacion'];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id', 'id_persona');
    }

    public function cursos()
    {
        return $this->hasMany('App\Models\InfCurso', 'profesor_principal_id', 'profesor_id');
    }
}
