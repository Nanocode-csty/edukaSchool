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
        return $this->belongsTo(User::class, 'usuario_id')->with('persona');
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
            'por_docente' => 'Por Docente',
            'comparativo' => 'Comparativo',
            default => ucfirst($this->tipo_reporte)
        };
    }

    /**
     * Obtener el nombre del formato en español
     */
    public function getFormatoNombreAttribute(): string
    {
        return match($this->formato) {
            'pdf' => 'PDF',
            'excel' => 'Excel',
            default => strtoupper($this->formato)
        };
    }

    /**
     * Obtener el período del reporte formateado
     */
    public function getPeriodoAttribute(): string
    {
        return $this->fecha_inicio->format('d/m/Y') . ' - ' . $this->fecha_fin->format('d/m/Y');
    }

    /**
     * Obtener el nombre del usuario que generó el reporte
     */
    public function getGeneradoPorAttribute(): string
    {
        if ($this->usuario) {
            // Intentar obtener el nombre completo de la persona asociada
            if ($this->usuario->persona) {
                return $this->usuario->persona->nombre_completo;
            }
            // Si no hay persona, usar el username
            return $this->usuario->username ?? 'Usuario';
        }
        return 'Sistema';
    }

    /**
     * Verificar si el archivo del reporte existe
     */
    public function archivoExiste(): bool
    {
        return $this->archivo_path && file_exists(storage_path('app/public/' . $this->archivo_path));
    }

    /**
     * Obtener la URL completa del archivo
     */
    public function getArchivoUrlAttribute(): ?string
    {
        return $this->archivo_path ? asset('storage/' . $this->archivo_path) : null;
    }

    /**
     * Scope para filtrar reportes por usuario
     */
    public function scopePorUsuario($query, $usuarioId)
    {
        return $query->where('usuario_id', $usuarioId);
    }

    /**
     * Scope para filtrar reportes por tipo
     */
    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo_reporte', $tipo);
    }

    /**
     * Scope para filtrar reportes por formato
     */
    public function scopePorFormato($query, $formato)
    {
        return $query->where('formato', $formato);
    }

    /**
     * Scope para obtener reportes recientes (últimos 30 días)
     */
    public function scopeRecientes($query, $dias = 30)
    {
        return $query->where('fecha_generacion', '>=', now()->subDays($dias));
    }

    /**
     * Scope para ordenar por fecha de generación descendente
     */
    public function scopeOrdenadoPorFecha($query)
    {
        return $query->orderBy('fecha_generacion', 'desc');
    }
}
