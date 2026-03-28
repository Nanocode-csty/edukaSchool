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
        'estado',
        'ultima_sesion',
        'persona_id',
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

    protected $with = ['persona.roles'];

    // Overriding password getter for authentication
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id', 'id_persona');
    }

    // Helper methods to get roles through persona relationship
    public function getRoles()
    {
        return $this->persona?->roles ?? collect();
    }

    public function hasRole($roleName)
    {
        return $this->getRoles()->contains('nombre', $roleName);
    }

    public function getPrimaryRole()
    {
        return $this->getRoles()->first();
    }

    public function getRoleNames()
    {
        return $this->getRoles()->pluck('nombre')->toArray();
    }

    // Accessor para mantener compatibilidad con código existente
    public function getRolAttribute()
    {
        return $this->getPrimaryRole()?->nombre;
    }
}
