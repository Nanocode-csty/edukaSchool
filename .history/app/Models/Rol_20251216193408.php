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
        'descripcion',
        'estado'
    ];

    protected $casts = [
        'id_rol' => 'integer',
    ];

    public function personas()
    {
        return $this->belongsToMany(Persona::class, 'persona_roles', 'id_rol', 'id_persona')
                    ->withPivot('fecha_asignacion');
    }
}
