<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\InfAnioLectivo;
use App\Models\InfPeriodosEvaluacion;
use App\Models\Matricula;
use App\Models\CursoAsignatura;
use App\Models\NotasFinalesPeriodo;
use App\Models\NotasFinalesAnuales;
use App\Models\CalificacionCompetencia;
use App\Models\Competencia;

class NotasTestSeeder extends Seeder
{
    /**
     * Ejecutar el seeder para crear datos de prueba de notas
     */
    public function run(): void
    {
        $this->command->info('=== INICIANDO CREACIÓN DE DATOS DE PRUEBA PARA NOTAS ===');

        // Obtener el año académico activo
        $anioActual = InfAnioLectivo::where('estado', 'Activo')->first();
        if (!$anioActual) {
            $this->command->error('No hay año académico activo');
            return;
        }

        // Obtener períodos de evaluación
        $periodos = InfPeriodosEvaluacion::where('ano_lectivo_id', $anioActual->ano_lectivo_id)
            ->orderBy('fecha_inicio', 'asc')
            ->get();

        if ($periodos->isEmpty()) {
            $this->command->error('No hay períodos de evaluación configurados');
            return;
        }

        // Obtener cursos asignaturas que tienen profesor asignado
        $cursosAsignaturas = CursoAsignatura::whereNotNull('profesor_id')
            ->with(['curso.matriculas', 'asignatura'])
            ->get();

        if ($cursosAsignaturas->isEmpty()) {
            $this->command->error('No hay cursos asignaturas con profesores asignados');
            return;
        }

        $this->command->info("Procesando {$cursosAsignaturas->count()} cursos asignaturas");
        $this->command->info("Procesando {$periodos->count()} períodos");

        $notasCreadas = 0;
        $competenciasCreadas = 0;

        foreach ($cursosAsignaturas as $cursoAsignatura) {
            $this->command->info("Procesando asignatura: {$cursoAsignatura->asignatura->nombre}");

            // Obtener competencias de la asignatura
            $competencias = Competencia::where('asignatura_id', $cursoAsignatura->asignatura->asignatura_id)->get();

            // Obtener matrículas activas del curso
            $matriculas = $cursoAsignatura->curso->matriculas()
                ->where('estado', 'Matriculado')
                ->get();

            $this->command->info("  - {$matriculas->count()} estudiantes matriculados");

            foreach ($matriculas as $matricula) {
                foreach ($periodos as $periodo) {
                    // Generar nota numérica realista (0-20)
                    $notaNumerica = $this->generarNotaRealista();

                    // Crear registro de nota por período
                    $nota = NotasFinalesPeriodo::updateOrCreate(
                        [
                            'matricula_id' => $matricula->matricula_id,
                            'curso_asignatura_id' => $cursoAsignatura->curso_asignatura_id,
                            'periodo_id' => $periodo->periodo_id,
                        ],
                        [
                            'promedio' => $notaNumerica,
                            'promedio_letra' => null, // Usando sistema numérico
                            'observaciones' => $this->generarObservacionAleatoria(),
                            'estado' => 'Calculado',
                            'fecha_calculo' => now(),
                            'usuario_registro' => 1
                        ]
                    );

                    // Si hay competencias, crear calificaciones para cada una
                    if ($competencias->count() > 0) {
                        foreach ($competencias as $competencia) {
                            // Generar calificación de competencia (AD, A, B, C)
                            $calificacionCompetencia = $this->convertirNotaANivelCompetencia($notaNumerica);

                            CalificacionCompetencia::updateOrCreate(
                                [
                                    'matricula_id' => $matricula->matricula_id,
                                    'competencia_id' => $competencia->competencia_id,
                                    'periodo_id' => $periodo->periodo_id,
                                ],
                                [
                                    'calificacion' => $calificacionCompetencia,
                                    'usuario_registro' => 1
                                ]
                            );

                            $competenciasCreadas++;
                        }
                    }

                    $notasCreadas++;
                }

                // Después de crear todas las notas por período, calcular la nota final anual
                $this->calcularNotaFinalAnual($matricula, $cursoAsignatura, $periodos);
            }
        }

        $this->command->info('=== CREACIÓN DE DATOS COMPLETADA ===');
        $this->command->info("✓ Notas por período creadas: {$notasCreadas}");
        $this->command->info("✓ Calificaciones de competencias creadas: {$competenciasCreadas}");

        // Ejecutar el seeder de competencias para asegurar que existan
        $this->command->info('Ejecutando seeder de competencias...');
        $this->call(CompetenciasSeeder::class);
    }

    /**
     * Genera una nota numérica realista entre 0 y 20
     */
    private function generarNotaRealista(): float
    {
        // Distribución realista: más notas entre 10-18, algunas bajas y altas
        $rand = rand(1, 100);

        if ($rand <= 5) {
            // 5% - notas bajas (0-7)
            return rand(0, 7) + rand(0, 99) / 100;
        } elseif ($rand <= 20) {
            // 15% - notas medias-bajas (8-11)
            return rand(8, 11) + rand(0, 99) / 100;
        } elseif ($rand <= 80) {
            // 60% - notas medias-altas (12-17)
            return rand(12, 17) + rand(0, 99) / 100;
        } else {
            // 20% - notas altas (18-20)
            return rand(18, 20) + rand(0, 99) / 100;
        }
    }

    /**
     * Convierte una nota numérica al nivel de competencia correspondiente
     */
    private function convertirNotaANivelCompetencia(float $nota): string
    {
        if ($nota >= 18) {
            return 'AD'; // Logro Destacado
        } elseif ($nota >= 15) {
            return 'A'; // Logro Esperado
        } elseif ($nota >= 12) {
            return 'B'; // En Proceso
        } else {
            return 'C'; // En Inicio
        }
    }

    /**
     * Genera una observación aleatoria para la nota
     */
    private function generarObservacionAleatoria(): ?string
    {
        $observaciones = [
            'Buen desempeño en las actividades propuestas.',
            'Presenta dificultades en algunos conceptos que requieren reforzamiento.',
            'Excelente participación en clase.',
            'Necesita mejorar su organización y método de estudio.',
            'Demuestra interés y motivación por la asignatura.',
            'Presenta avances significativos en su aprendizaje.',
            'Requiere apoyo adicional en temas específicos.',
            'Mantiene un ritmo constante de trabajo.',
            'Ha mejorado considerablemente su rendimiento.',
            null, // Sin observación
        ];

        return collect($observaciones)->random();
    }

    /**
     * Calcula y guarda la nota final anual para un estudiante en una asignatura
     */
    private function calcularNotaFinalAnual($matricula, $cursoAsignatura, $periodos)
    {
        // Obtener todas las notas por período para este estudiante y asignatura
        $notasPeriodo = NotasFinalesPeriodo::where('matricula_id', $matricula->matricula_id)
            ->where('curso_asignatura_id', $cursoAsignatura->curso_asignatura_id)
            ->whereIn('periodo_id', $periodos->pluck('periodo_id'))
            ->get();

        // Si no tenemos 4 notas (uno por bimestre), no podemos calcular
        if ($notasPeriodo->count() !== 4) {
            return;
        }

        // Calcular promedio final
        $sumaNotas = $notasPeriodo->sum('promedio');
        $promedioFinal = $sumaNotas / 4;

        // Determinar estado (aprobado/reprobado)
