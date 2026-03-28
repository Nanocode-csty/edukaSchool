<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class DescuentoPeriodo extends Model
{
    use HasFactory;

    protected $table = 'descuentos_periodos';
    protected $primaryKey = 'descuento_id';

    protected $fillable = [
        'nombre',
        'descripcion',
        'periodo_id',
        'porcentaje_descuento',
        'monto_fijo_descuento',
        'tipo_descuento',
        'aplicable_a',
        'fecha_inicio_vigencia',
        'fecha_fin_vigencia',
        'limite_usos',
        'usos_actuales',
        'estado',
        'condiciones',
        'prioridad'
    ];

    protected $casts = [
        'porcentaje_descuento' => 'decimal:2',
        'monto_fijo_descuento' => 'decimal:2',
        'fecha_inicio_vigencia' => 'date',
        'fecha_fin_vigencia' => 'date',
        'limite_usos' => 'integer',
        'usos_actuales' => 'integer',
        'prioridad' => 'integer',
        'condiciones' => 'array'
    ];

    // Relaciones
    public function periodo()
    {
        return $this->belongsTo(PeriodoMatricula::class, 'periodo_id', 'periodo_id');
    }

    // Scopes
    public function scopeActivos($query)
    {
        return $query->where('estado', 'ACTIVO');
    }

    public function scopeVigentes($query)
    {
        $hoy = Carbon::today();
        return $query->where('fecha_inicio_vigencia', '<=', $hoy)
                    ->where('fecha_fin_vigencia', '>=', $hoy);
    }

    public function scopeDisponibles($query)
    {
        return $query->activos()
                    ->vigentes()
                    ->where(function($q) {
                        $q->whereNull('limite_usos')
                          ->orWhereRaw('usos_actuales < limite_usos');
                    });
    }

    public function scopePorPeriodo($query, $periodoId)
    {
        return $query->where('periodo_id', $periodoId);
    }

    public function scopeOrdenadosPorPrioridad($query)
    {
        return $query->orderBy('prioridad', 'desc');
    }

    // Métodos
    public function estaVigente()
    {
        $hoy = Carbon::today();
        return $this->estado === 'ACTIVO' &&
               $hoy->between($this->fecha_inicio_vigencia, $this->fecha_fin_vigencia);
    }

    public function puedeAplicarse()
    {
        return $this->estaVigente() &&
               (is_null($this->limite_usos) || $this->usos_actuales < $this->limite_usos);
    }

    public function incrementarUso()
    {
        $this->increment('usos_actuales');
    }

    public function calcularDescuento($montoBase)
    {
        $descuento = 0;

        if ($this->tipo_descuento === 'PORCENTAJE' || $this->tipo_descuento === 'AMBOS') {
            $descuento += ($montoBase * $this->porcentaje_descuento) / 100;
        }

        if ($this->tipo_descuento === 'FIJO' || $this->tipo_descuento === 'AMBOS') {
            $descuento += $this->monto_fijo_descuento;
        }

        return min($descuento, $montoBase); // No puede ser mayor al monto base
    }

    public function esAplicableA(Matricula $matricula)
    {
        switch ($this->aplicable_a) {
            case 'TODOS':
                return true;

            case 'PREINSCRITOS':
                return $matricula->estado === 'Pre-inscrito';

            case 'MATRICULADOS':
                return $matricula->estado === 'Matriculado';

            case 'NUEVOS':
                return Matricula::esEstudianteNuevo($matricula->estudiante_id);

            case 'REPITENTES':
                return !Matricula::esEstudianteNuevo($matricula->estudiante_id);

            default:
                return true;
        }
    }

    // Métodos estáticos
    public static function obtenerDescuentosAplicables(Matricula $matricula)
    {
        // Obtener período actual
        $periodoActual = PeriodoMatricula::getPeriodoActual();

        if (!$periodoActual) {
            return collect();
        }

        return self::disponibles()
            ->porPeriodo($periodoActual->periodo_id)
            ->ordenadosPorPrioridad()
            ->get()
            ->filter(function($descuento) use ($matricula) {
                return $descuento->esAplicableA($matricula);
            });
    }

    public static function aplicarDescuentoAutomatico(Matricula $matricula, $montoBase)
    {
        $descuentos = self::obtenerDescuentosAplicables($matricula);

        if ($descuentos->isEmpty()) {
            return [
                'monto_descuento' => 0,
                'monto_final' => $montoBase,
                'descuentos_aplicados' => []
            ];
        }

        $totalDescuento = 0;
        $descuentosAplicados = [];

        foreach ($descuentos as $descuento) {
            $descuentoMonto = $descuento->calcularDescuento($montoBase - $totalDescuento);

            if ($descuentoMonto > 0) {
                $totalDescuento += $descuentoMonto;
                $descuentosAplicados[] = [
                    'descuento_id' => $descuento->descuento_id,
                    'nombre' => $descuento->nombre,
                    'monto_descuento' => $descuentoMonto
                ];

                // Incrementar contador de usos
                $descuento->incrementarUso();
            }
        }

        return [
            'monto_descuento' => $totalDescuento,
            'monto_final' => max(0, $montoBase - $totalDescuento),
            'descuentos_aplicados' => $descuentosAplicados
        ];
    }
}
