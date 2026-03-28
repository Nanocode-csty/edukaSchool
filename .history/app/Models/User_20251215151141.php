<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // ✅ Nombre personalizado de la tabla
    protected $table = 'usuarios';

    // ✅ Clave primaria personalizada
    protected $primaryKey = 'usuario_id';

    public $timestamps = false; // Si no tienes created_at y updated_at
    // ✅ Si la clave primaria no es autoincremental (por ejemplo UUIDs), agrega esto:
    // public $incrementing = false;

    // ✅ Si la clave primaria no es tipo entero:
    // protected $keyType = 'string';

    protected $fillable = [
        'username',
        'email',
        'password_hash',
        'nombres',
        'apellidos',
        'rol',
        'estado',
        'ultima_sesion',
        // ... otros campos permitidos
    ];

    // Ocultar campos al serializar
    protected $hidden = [
        'password_hash',
        'remember_token',
    ];

    // Casts de atributos
    protected $casts = [
        'ultima_sesion' => 'datetime',
    ];

    // Overriding password getter for authentication
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    public function docente()
    {
        return $this->belongsTo(InfDocente::class, 'profesor_id', 'profesor_id');
    }
}
