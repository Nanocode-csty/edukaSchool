<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Matricula extends Model
{
    use HasFactory;

    protected $table = 'matriculas';

    protected $primaryKey = 'matricula_id';

    public $timestamps = false;

    protected $fillable = [
        'estudiante_id',
        'fecha_matricula',
        'estado',
        'numero_matricula',
        'observaciones',
        'usuario_registro',
        'idGrado',
        'idSeccion',
        'anio_academico',
    ];

    protected $dates = [
        'fecha_matricula',
    ];

    protected $casts = [
        'fecha_matricula' => 'datetime',
        'anio_academico' => 'integer',
    ];

    public function estudiante()
    {
        return $this->belongsTo(InfEstudiante::class, 'estudiante_id', 'estudiante_id');
    }

    public function grado()
    {
        return $this->belongsTo(InfGrado::class, 'idGrado', 'grado_id');
    }

    public function seccion()
    {
        return $this->belongsTo(InfSeccion::class, 'idSeccion', 'seccion_id');
    }

    public function usuarioRegistro()
    {
        return $this->belongsTo(User::class, 'usuario_registro', 'usuario_id');
    }

    public function curso()
    {
        return $this->belongsTo(InfCurso::class, 'curso_id', 'curso_id');
    }

    /**
     * Generar número de matrícula con formato M001-2025

    public static function generarNumeroMatricula($anio = null)
    {
        $anio = $anio ?? date('Y');

        $ultimoNumero = self::where('anio_academico', $anio)
            ->whereRaw("numero_matricula LIKE 'M%-%'")
            ->max(DB::raw('CAST(SUBSTRING(numero_matricula, 2, 3) AS UNSIGNED)'));

        $nuevoNumero = str_pad(($ultimoNumero * 1), 3, '0', STR_PAD_LEFT);
        return "M{$nuevoNumero}-{$anio}";
    }
     */
    public static function generarNumeroMatricula($anio = null)
    {
        // Tomar el año actual si no se pasa como parámetro
        $anio = $anio ?? date('Y');

        // Contar todas las matrículas registradas en ese año
        $totalMatriculas = self::where('anio_academico', $anio)->count();

        // El número será TotalMatriculas + 1
        $nuevoNumero = $totalMatriculas + 1;

        // Ajustar a 5 dígitos, por ejemplo: 46 → 46000
        $numeroBase = str_pad($nuevoNumero, 5, '0', STR_PAD_RIGHT);

        // Tomar los últimos 2 dígitos del año
        $anioCorto = substr($anio, -2);

        // Concatenar todo
        return 'M'.$numeroBase.$anioCorto;
    }

    /**
     * Calcular el siguiente grado para un estudiante
     */
    public static function calcularSiguienteGrado($estudiante_id)
    {
        $ultimaMatricula = self::where('estudiante_id', $estudiante_id)
            ->where('estado', 'Matriculado')
            ->latest('fecha_matricula')
            ->with('grado')
            ->first();

        if (! $ultimaMatricula) {
            // Estudiante nuevo - empezar en 1er grado de primaria
            return InfGrado::where('nivel_id', 1)
                ->where('nombre', 'LIKE', '1%')
                ->first();
        }

        $gradoActual = $ultimaMatricula->grado;
        $numeroActual = (int) filter_var($gradoActual->nombre, FILTER_SANITIZE_NUMBER_INT);
        $siguienteNumero = $numeroActual + 1;

        // Lógica de transición primaria -> secundaria
        if ($gradoActual->nivel_id == 1 && $siguienteNumero > 6) {
            // De 6° primaria pasa a 1° secundaria
            return InfGrado::where('nivel_id', 2)
                ->where('nombre', 'LIKE', '1%')
                ->first();
        }

        // Buscar siguiente grado en el mismo nivel
        return InfGrado::where('nivel_id', $gradoActual->nivel_id)
            ->where('nombre', 'LIKE', $siguienteNumero.'%')
            ->first();
    }

    /**
     * Verificar si un estudiante es nuevo (sin matrículas previas)
     */
    public static function esEstudianteNuevo($estudiante_id)
    {
        return ! self::where('estudiante_id', $estudiante_id)
            ->where('estado', 'Matriculado')
            ->exists();
    }

    /**
     * Obtener secciones disponibles para un grado específico
     */
    public static function getSeccionesDisponibles($grado_id, $anio_academico = null)
    {
        $anio_academico = $anio_academico ?? date('Y');

        $seccionesOcupadas = self::where('idGrado', $grado_id)
            ->where('anio_academico', $anio_academico)
            ->where('estado', '!=', 'Anulado')
            ->select('idSeccion', DB::raw('COUNT(*) as total'))
            ->groupBy('idSeccion')
            ->with('seccion')
            ->get();

        $todasLasSecciones = InfSeccion::where('estado', 'Activo')->get();

        return $todasLasSecciones->map(function ($seccion) use ($seccionesOcupadas) {
            $ocupadas = $seccionesOcupadas->where('idSeccion', $seccion->seccion_id)->first();
            $disponibles = $seccion->capacidad_maxima - ($ocupadas->total ?? 0);

            return [
                'seccion' => $seccion,
                'disponibles' => max(0, $disponibles),
                'ocupadas' => $ocupadas->total ?? 0,
            ];
        })->where('disponibles', '>', 0);
    }

    /**
     * Scopes para filtros comunes.
     * ¿Que es un scope?
     * Son métodos especiales para crear consultas reutilizables.
     */
    public function scopeActivas($query)
    {
        return $query->where('estado', '!=', 'Anulado');
    }

    public function scopeDelAnio($query, $anio = null)
    {
        $anio = $anio ?? date('Y');

        return $query->where('anio_academico', $anio);
    }

    public function scopeMatriculadas($query)
    {
        return $query->where('estado', 'Matriculado');
    }

    public function scopePreInscritas($query)
    {
        return $query->where('estado', 'Pre-inscrito');
    }
}
