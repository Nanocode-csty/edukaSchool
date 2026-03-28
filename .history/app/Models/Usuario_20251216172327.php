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
        'ultima_sesion',
        'estado',
        'cambio_password_requerido',
        'google_id',
        'google_token',
    ];

    protected $casts = [
        'usuario_id' => 'integer',
        'cambio_password_requerido' => 'boolean',
        'ultima_sesion' => 'datetime',
    ];

    public function persona()
    {
        return $this->hasOne(Persona::class, 'usuario_id', 'usuario_id');
    }
}
