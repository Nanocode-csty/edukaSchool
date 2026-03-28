<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InfAula extends Model
{
    protected $table = 'aulas';
    protected $primaryKey = 'aula_id';
    public $timestamps = false;
    protected $fillable = ['nombre', 'capacidad', 'ubicación', 'tipo'];

    public function cursos()
    {
        return $this->hasMany('App\Models\InfCurso', 'aula_id', 'aula_id');
    }

}