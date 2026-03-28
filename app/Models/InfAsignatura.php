<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfAsignatura extends Model
{
    use HasFactory;
    protected $table = 'asignaturas';
    protected $primaryKey = 'asignatura_id';
    public $timestamps = false;
    protected $fillable = ['codigo', 'nombre', 'descripcion', 'horas_semanales'];
}