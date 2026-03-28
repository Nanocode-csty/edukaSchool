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
        'docente_id',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'estado',
        'observaciones',
        'aula_id',
        'tipo_sesion',
        'usuario_registro',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function aula()
    {
        return $this->belongsTo(\App\Models\InfAula::class, 'aula_id', 'aula_id');
    }

    public function cursoAsignatura()
    {
        return $this->belongsTo(CursoAsignatura::class, 'curso_asignatura_id', 'curso_asignatura_id');
    }

    public function usuarioRegistro()
    {
        return $this->belongsTo(Usuario::class, 'usuario_registro', 'usuario_id');
    }
}
