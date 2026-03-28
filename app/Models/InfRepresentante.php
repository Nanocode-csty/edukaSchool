<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfRepresentante extends Model
{
    use HasFactory;
    protected $table = 'representantes';
    protected $primaryKey = 'representante_id';
    protected $with = ['persona']; //PARA SIEMPRE VINCULAR CON PERSONA
    public $timestamps = false;
    protected $fillable = ['persona_id', 'parentesco', 'ocupacion'];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id', 'id_persona');
    }

    public function estudiantes()
    {
        return $this->belongsToMany(InfEstudiante::class, 'estudiante_representante', 'representante_id', 'estudiante_id')->withPivot(['viveConEstudiante', 'es_principal']);;
    }

     public function getDireccionCompletaAttribute()
    {
        $direccion = $this->persona?->direccion;

        if (!$direccion) {
            return 'No especificado.';
        }

        $partes = [];

        // Calle / avenida
        if ($direccion->nombreAvenida) {
            $partes[] = $direccion->nombreAvenida;
        }

        // Referencia
        if ($direccion->referencia) {
            $partes[] = $direccion->referencia;
        }

        // Distrito
        if ($direccion->distrito?->nombre) {
            $partes[] = $direccion->distrito->nombre;
        }

        // Provincia
        if ($direccion->provincia?->nombre) {
            $partes[] = $direccion->provincia->nombre;
        }

        // Región
        if ($direccion->region?->nombre) {
            $partes[] = $direccion->region->nombre;
        }

        return implode(', ', $partes);
    }
}
