<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo para almacenar el historial de reportes de asistencia generados
 */
class ReporteGenerado extends Model
{
    // Nombre de la tabla
    protected $table = 'reportes_generados';

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'tipo_reporte',
        'formato',
        'fecha_inicio',
        'fecha_fin',
        'filtros_aplicados',
        'archivo_path',
        'archivo_nombre',
        'registros_totales',
        'tamano_archivo_kb',
        'usuario_id',
        'fecha_generacion'
    ];

    // Campos que deben ser tratados como fechas
    protected $dates = [
        'fecha_inicio',
        'fecha_fin',
        'fecha_generacion',
        'created_at',
        'updated_at'
    ];

    // Campos que deben ser tratados como JSON
    protected $casts = [
        'filtros_aplicados' => 'array',
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'fecha_generacion' => 'datetime',
        'tamano_archivo_kb' => 'decimal:2'
    ];

    /**
     * Relación con el usuario que generó el reporte
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    /**
     * Obtener el nombre del tipo de reporte en español
     */
    public function getTipoReporteNombreAttribute(): string
    {
        return match($this->tipo_reporte) {
            'general' => 'General',
            'por_curso' => 'Por Curso',
            'por_estudiante' => 'Por Estudiante',
