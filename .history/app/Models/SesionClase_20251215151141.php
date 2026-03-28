<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SesionClase extends Model
{
    protected $table = 'sesiones_clases';

    protected $primaryKey = 'sesion_id';

    // La tabla no tiene columnas created_at/updated_at
    public $timestamps = false;

    protected $fillable = [
        'curso_asignatura_id',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'estado',
        'observaciones',
        'aula_id',
        'tipo_sesion',
        'usuario_registro',
    ];

    public function aula()
    {
        return $this->belongsTo(Aula::class, 'aula_id', 'aula_id');
    }

    public function cursoAsignatura()
    {
        return $this->belongsTo(CursoAsignatura::class, 'curso_asignatura_id', 'curso_asignatura_id');
    }

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class, 'sesion_id', 'sesion_id');
    }

    public function usuarioRegistro()
    {
        return $this->belongsTo(User::class, 'usuario_registro', 'id');
    }
}
