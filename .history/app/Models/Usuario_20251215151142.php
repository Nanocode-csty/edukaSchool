<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'usuario_id';
    public $timestamps = false;

    protected $fillable = [
        'username',
        'password_hash',
        'nombres',
        'apellidos',
        'email',
        'rol',
        'ultima_sesion',
        'estado',
        'cambio_password_requerido',
        'profesor_id',
        'estudiante_id',
        'representante_id',
        'google_id',
        'google_token',
    ];

    protected $casts = [
        'usuario_id' => 'integer',
        'profesor_id' => 'integer',
        'estudiante_id' => 'integer',
        'representante_id' => 'integer',
        'cambio_password_requerido' => 'boolean',
        'ultima_sesion' => 'datetime',
    ];

    public function docente()
    {
        return $this->belongsTo(InfDocente::class, 'profesor_id', 'profesor_id');
    }

    public function estudiante()
    {
        return $this->belongsTo(InfEstudiante::class, 'estudiante_id', 'estudiante_id');
    }

    public function representante()
    {
        return $this->belongsTo(InfRepresentante::class, 'representante_id', 'representante_id');
    }
}
