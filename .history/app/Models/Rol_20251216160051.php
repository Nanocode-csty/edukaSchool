<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    use HasFactory;

    protected $table = 'roles';
    protected $primaryKey = 'id_rol';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion'
    ];

    protected $casts = [
        'id_rol' => 'integer',
    ];

    public function personas()
    {
        return $this->belongsToMany(Persona::class, 'persona_roles', 'rol_id', 'persona_id')
                    ->withPivot('fecha_asignacion', 'estado')
                    ->withTimestamps();
    }
}
