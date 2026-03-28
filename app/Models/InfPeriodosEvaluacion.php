<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class InfPeriodosEvaluacion extends Model
{
    use HasFactory;

    protected $table = 'periodosevaluacion';
    protected $primaryKey = 'periodo_id';
    public $timestamps = false; // Deshabilitar timestamps porque la tabla no los tiene

    protected $fillable = [
        'ano_lectivo_id',
        'nombre',
        'fecha_inicio',
        'fecha_fin',
        'estado',
        'es_final'
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'es_final' => 'boolean',
        'ano_lectivo_id' => 'integer',
        'periodo_id' => 'integer'
    ];

    /**
     * Relación con el modelo InfAnioLectivo
     * Un periodo de evaluación pertenece a un año lectivo
     */
    public function anoLectivo()
    {
        return $this->belongsTo(InfAnioLectivo::class, 'ano_lectivo_id', 'ano_lectivo_id');
    }

    // Accessor para obtener la duración en días
    public function getDuracionAttribute()
    {
        if ($this->fecha_inicio && $this->fecha_fin) {
            return $this->fecha_inicio->diffInDays($this->fecha_fin);
        }
        return 0;
    }

    // Accessor para obtener el rango de fechas formateado
    public function getRangoFechasAttribute()
    {
        if ($this->fecha_inicio && $this->fecha_fin) {
            return $this->fecha_inicio->format('d/m/Y') . ' - ' . $this->fecha_fin->format('d/m/Y');
        }
        return 'Sin fechas definidas';
    }

    // Scope para obtener solo periodos activos
    public function scopeActivos($query)
    {
        return $query->whereIn('estado', ['Planificado', 'En curso']);
    }

    // Scope para obtener periodos por año lectivo
    public function scopePorAnoLectivo($query, $anoLectivoId)
    {
        return $query->where('ano_lectivo_id', $anoLectivoId);
    }

    // Scope para obtener solo evaluaciones finales
    public function scopeFinales($query)
    {
        return $query->where('es_final', true);
    }

    // Scope para obtener solo evaluaciones parciales
    public function scopeParciales($query)
    {
        return $query->where('es_final', false);
    }

    // Scope para obtener periodos en curso
    public function scopeEnCurso($query)
    {
        return $query->where('estado', 'En curso');
    }

    // Método para verificar si está en curso
    public function estaEnCurso()
    {
        if (!$this->fecha_inicio || !$this->fecha_fin) {
            return false;
        }
        
        $hoy = Carbon::now();
        return $hoy->between($this->fecha_inicio, $this->fecha_fin) && $this->estado === 'En curso';
    }

    // Método para verificar si el periodo está activo
    public function estaActivo()
    {
        return in_array($this->estado, ['Planificado', 'En curso']);
    }

    // Método para obtener el tipo de evaluación
    public function getTipoEvaluacion()
    {
        return $this->es_final ? 'Final' : 'Parcial';
    }

    // Método estático para obtener los estados disponibles
    public static function getEstadosDisponibles()
    {
        return [
            'Planificado' => 'Planificado',
            'En curso' => 'En curso',
            'Finalizado' => 'Finalizado',
            'Cerrado' => 'Cerrado'
        ];
    }

    // Método estático para obtener los bimestres disponibles
    public static function getBimestresDisponibles()
    {
        return [
            'Primer Bimestre' => 'Primer Bimestre',
            'Segundo Bimestre' => 'Segundo Bimestre',
            'Tercer Bimestre' => 'Tercer Bimestre',
            'Cuarto Bimestre' => 'Cuarto Bimestre'
        ];
    }
    // Método para verificar si las fechas se solapan con otro periodo
    public function seSolapaConOtroPeriodo($fechaInicio, $fechaFin, $anoLectivoId, $excluirPeriodoId = null)
    {
        $query = self::where('ano_lectivo_id', $anoLectivoId)
            ->where(function($q) use ($fechaInicio, $fechaFin) {
                $q->whereBetween('fecha_inicio', [$fechaInicio, $fechaFin])
                  ->orWhereBetween('fecha_fin', [$fechaInicio, $fechaFin])
                  ->orWhere(function($subQ) use ($fechaInicio, $fechaFin) {
                      $subQ->where('fecha_inicio', '<=', $fechaInicio)
                           ->where('fecha_fin', '>=', $fechaFin);
                  });
            });

        if ($excluirPeriodoId) {
            $query->where('periodo_id', '!=', $excluirPeriodoId);
        }

        return $query->exists();
    }
}