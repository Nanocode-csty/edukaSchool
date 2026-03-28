<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Persona extends Model
{
    use HasFactory;

    protected $table = 'personas';
    protected $primaryKey = 'id_persona';
    public $timestamps = false;

    protected $fillable = [
        'dni',
        'nombres',
        'apellidoPaterno',
        'apellidoMaterno',
        'fecha_nacimiento',
        'genero',
        'telefono',
        'telefono_alternativo',
        'email',
        'fecha_registro',
        'estado',
        'foto_url',
        'observaciones'
    ];

    protected $casts = [
        'id_persona' => 'integer',
        #'fecha_nacimiento' => 'date',
    ];

    protected $appends = [
        'nombre_completo',
        'telefono_formato',
        'direccion_completa',
        'genero_convertido'
    ];

    //Asegurar que trabajamos con datos de una persona con estado ACTIVO
    public function scopeActiva($q)
    {
        $q->where('estado', 'Activo');
    }

    public function usuario()
    {
        return $this->hasOne(Usuario::class, 'persona_id', 'id_persona');
    }

    public function roles()
    {
        return $this->belongsToMany(Rol::class, 'persona_roles', 'id_persona', 'id_rol')
            ->withPivot('fecha_asignacion');
    }

    public function estudiante()
    {
        return $this->hasOne(InfEstudiante::class, 'persona_id', 'id_persona');
    }

    public function docente()
    {
        return $this->hasOne(InfDocente::class, 'persona_id', 'id_persona');
    }

    public function representante()
    {
        return $this->hasOne(InfRepresentante::class, 'persona_id', 'id_persona');
    }

    public function getNombreCompletoAttribute()
    {
        $apellidoPaterno = $this->apellidoPaterno ?? 'No especificado';
        $apellidoMaterno = $this->apellidoMaterno ?? 'No especificado';
        $nombres = $this->nombres ?? '';

        return trim("{$apellidoPaterno} {$apellidoMaterno}, {$nombres}");
    }

    public function getGeneroConvertidoAttribute()
    {
        return match ($this->genero) {
            'M' => 'Masculino',
            'F' => 'Femenino',
            default => 'No especificado',
        };
    }


    public function getTelefonoFormatoAttribute()
    {
        $tres = Str::substr($this->telefono, 0, 3);
        $seis = Str::substr($this->telefono, 3, 3);
        $nueve = Str::substr($this->telefono, 6, 3);

        return trim("{$tres} {$seis} {$nueve}");
    }


    public function getApellidosCompleatosAttribute()
    {
        $apellidoPaterno = $this->apellidoPaterno ?? 'No especificado';
        $apellidoMaterno = $this->apellidoMaterno ?? 'No especificado';
        //Unimos los dos apellidos
        return trim("{$apellidoPaterno} {$apellidoMaterno}");
    }

    public function direccion()
    {
        return $this->hasOne(
            DireccionPersona::class,
            'idPersona',
            'id_persona'
        );
    }

    public function getDireccionCompletaAttribute()
    {
        $direccion = $this->direccion;

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
