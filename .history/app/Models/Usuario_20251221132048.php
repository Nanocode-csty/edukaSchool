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
        'persona_id',
        'google_id',
        'google_token',
        'email',
    ];

    protected $casts = [
        'usuario_id' => 'integer',
        'cambio_password_requerido' => 'boolean',
        'ultima_sesion' => 'datetime',
    ];

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
        $roles = $this->getRoles();
        $hasRole = $roles->contains('nombre', $roleName);

        // Debug logging
        \Log::info('Checking hasRole', [
            'user_id' => $this->usuario_id,
            'roleName' => $roleName,
            'roles_count' => $roles->count(),
            'role_names' => $roles->pluck('nombre')->toArray(),
            'hasRole' => $hasRole
        ]);

        return $hasRole;
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
