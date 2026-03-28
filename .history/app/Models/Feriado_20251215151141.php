<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Feriado extends Model
{
    use HasFactory;

    protected $table = 'feriados';

    protected $fillable = [
        'nombre',
        'fecha',
        'tipo',
        'recuperable',
        'descripcion',
        'ubicacion',
        'activo',
        'creado_por'
    ];

    protected $casts = [
        'fecha' => 'date',
        'recuperable' => 'boolean',
        'activo' => 'boolean'
    ];

    // Relaciones
    public function creador()
    {
        return $this->belongsTo(User::class, 'creado_por');
    }

    // Scopes
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopeRecuperables($query)
    {
        return $query->where('recuperable', true);
    }

    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    public function scopePorAnio($query, $anio)
    {
        return $query->whereYear('fecha', $anio);
    }

    public function scopePorFecha($query, $fecha)
    {
        return $query->where('fecha', $fecha);
    }

    // Accessors
    public function getEsHoyAttribute()
    {
        return $this->fecha->isToday();
    }

    public function getEsProximoAttribute()
    {
        $hoy = Carbon::today();
        $proximo = $hoy->copy()->addDays(7);
        return $this->fecha->between($hoy, $proximo);
    }

    // Métodos estáticos
    public static function esFeriado($fecha)
    {
        return static::activos()
            ->where('fecha', $fecha)
            ->exists();
    }

    public static function obtenerFeriadosPorRango($fechaInicio, $fechaFin)
    {
        return static::activos()
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->orderBy('fecha')
            ->get();
    }

    public static function feriadosRecuperables()
    {
        return static::activos()
            ->recuperables()
            ->get();
    }
}
