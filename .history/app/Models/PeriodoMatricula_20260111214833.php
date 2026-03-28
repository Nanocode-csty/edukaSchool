<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PeriodoMatricula extends Model
{
    use HasFactory;

    protected $table = 'periodos_matricula';
    protected $primaryKey = 'periodo_id';

    protected $fillable = [
        'nombre',
        'codigo',
        'descripcion',
        'fecha_inicio',
        'fecha_fin',
        'ano_lectivo_id',
        'tipo_periodo',
        'estado',
        'orden',
        'configuracion'
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'anio_academico' => 'integer',
        'orden' => 'integer',
        'configuracion' => 'array'
    ];

    // Scopes útiles
    public function scopeActivos($query)
    {
        return $query->where('estado', 'ACTIVO');
    }

    public function scopeDelAnio($query, $anio = null)
    {
        $anio = $anio ?? date('Y');
        return $query->where('anio_academico', $anio);
    }

    public function scopeTipo($query, $tipo)
    {
        return $query->where('tipo_periodo', $tipo);
    }

    public function scopeOrdenado($query)
    {
        return $query->orderBy('orden')->orderBy('fecha_inicio');
    }

    // Métodos útiles
    public function estaActivo()
    {
        $hoy = Carbon::today();
        return $this->estado === 'ACTIVO' &&
               $hoy->between($this->fecha_inicio, $this->fecha_fin);
    }

    public function haTerminado()
    {
        return Carbon::today()->isAfter($this->fecha_fin);
    }

    public function estaProximo()
    {
        $hoy = Carbon::today();
        $proximoInicio = Carbon::parse($this->fecha_inicio)->subDays(30);
        return $hoy->between($proximoInicio, $this->fecha_inicio);
    }

    // Métodos estáticos para obtener períodos actuales
    public static function getPeriodoActual($tipo = null)
    {
        $query = self::activos()->where(function($q) {
            $hoy = Carbon::today();
            $q->where('fecha_inicio', '<=', $hoy)
              ->where('fecha_fin', '>=', $hoy);
        });

        if ($tipo) {
            $query->where('tipo_periodo', $tipo);
        }

        return $query->first();
    }

    public static function getPeriodosAnioActual()
    {
        return self::activos()->delAnio()->ordenado()->get();
    }

    public static function puedeCrearMatricula($tipoEstado = null)
    {
        $periodoActual = self::getPeriodoActual();

        if (!$periodoActual) {
            return false; // No hay período activo
        }

        // Dependiendo del tipo de estado que se quiere crear
        switch ($tipoEstado) {
            case 'Pre-inscrito':
                return in_array($periodoActual->tipo_periodo, ['PREINSCRIPCION', 'INSCRIPCION']);
            case 'Matriculado':
                return $periodoActual->tipo_periodo === 'MATRICULA';
            default:
                return true; // Para otros estados, permitir siempre
        }
    }

    public static function getProximosPeriodos($dias = 30)
    {
        $fechaLimite = Carbon::today()->addDays($dias);

        return self::activos()
            ->where('fecha_inicio', '>', Carbon::today())
            ->where('fecha_inicio', '<=', $fechaLimite)
            ->ordenado()
            ->get();
    }
}
