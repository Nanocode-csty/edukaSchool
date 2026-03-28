<?php

// app/Models/TipoAsistencia.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoAsistencia extends Model
{
    use HasFactory;

    protected $table = 'tiposasistencia';
    protected $primaryKey = 'tipo_asistencia_id';
    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'computa_falta',
        'factor_asistencia',
        'activo'
    ];

    protected $casts = [
        'computa_falta' => 'boolean',
        'activo' => 'boolean',
        'factor_asistencia' => 'decimal:2'
    ];

    public function asistenciasDiarias()
    {
        return $this->hasMany(AsistenciaDiaria::class, 'tipo_asistencia_id');
    }

    public function asistenciasAsignatura()
    {
        return $this->hasMany(AsistenciaAsignatura::class, 'tipo_asistencia_id');
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }
}
