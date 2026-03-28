<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class InfAnioLectivo extends Model
{
    use HasFactory;

    protected $table = 'anoslectivos';
    protected $primaryKey = 'ano_lectivo_id';
    public $timestamps = false; // Deshabilitar timestamps porque la tabla no los tiene

    protected $fillable = [
        'nombre',
        'fecha_inicio',
        'fecha_fin',
        'estado',
        'descripcion'
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    // Accessor para obtener la duración en días
    public function getDuracionAttribute()
    {
        return $this->fecha_inicio->diffInDays($this->fecha_fin);
    }

    // Scope para obtener solo años activos
    public function scopeActivos($query)
    {
        return $query->where('estado', 'Activo');
    }

    // Scope para obtener años del año actual
    public function scopeAñoActual($query)
    {
        $añoActual = date('Y');
        return $query->where('nombre', 'like', $añoActual . '%');
    }

    // Método para verificar si está en curso
    public function estaEnCurso()
    {
        $hoy = Carbon::now();
        return $hoy->between($this->fecha_inicio, $this->fecha_fin) && $this->estado === 'Activo';
    }

     public function cursos()
    {
        return $this->hasMany(InfCurso::class, 'ano_lectivo_id', 'ano_lectivo_id');
    }

}