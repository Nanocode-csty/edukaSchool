<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfSeccion extends Model
{
    use HasFactory;
    protected $table = 'secciones';
    protected $primaryKey = 'seccion_id';
    public $timestamps = false;
    protected $fillable = ['nombre', 'capacidad_maxima', 'descripcion', 'estado', 'idGrado', 'anio_lectivo_id'];

    public function cursos()
    {
        return $this->hasMany('App\Models\InfCurso', 'seccion_id', 'seccion_id');
    }
}
