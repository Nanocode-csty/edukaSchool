<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PeriodoMatricula;
use Carbon\Carbon;

class PeriodosMatriculaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener el año lectivo activo actual
        $anioLectivo = \App\Models\InfAnioLectivo::activos()->first();

        if (!$anioLectivo) {
            $this->command->error('No se encontró un año lectivo activo. Ejecuta primero el PresentationSeeder.');
            return;
        }

        $this->command->info('Usando año lectivo: ' . $anioLectivo->nombre);

        // Extraer el año del nombre del año lectivo (ej: "2026-2027" -> 2026)
        $anioBase = intval(explode('-', $anioLectivo->nombre)[0]);

        $periodos = [
            [
                'nombre' => 'Pre-inscripción ' . $anioLectivo->nombre,
                'codigo' => 'PREINSCRIPCION_' . $anioBase,
                'descripcion' => 'Período para pre-inscripciones anticipadas',
                'fecha_inicio' => $anioLectivo->fecha_inicio->copy()->subMonths(2), // 2 meses antes del inicio
                'fecha_fin' => $anioLectivo->fecha_inicio->copy()->subDays(1),     // 1 día antes del inicio
                'ano_lectivo_id' => $anioLectivo->ano_lectivo_id,
                'tipo_periodo' => 'PREINSCRIPCION',
                'orden' => 1,
                'configuracion' => [
                    'permite_preinscripcion' => true,
                    'requiere_documentos_basicos' => true,
                    'descuento_temprano' => 10
                ]
            ],
            [
                'nombre' => 'Inscripciones ' . $anioLectivo->nombre,
                'codigo' => 'INSCRIPCION_' . $anioBase,
                'descripcion' => 'Período regular de inscripciones',
                'fecha_inicio' => $anioLectivo->fecha_inicio->copy(), // Inicio del año lectivo
                'fecha_fin' => $anioLectivo->fecha_inicio->copy()->addDays(14), // 2 semanas después
                'ano_lectivo_id' => $anioLectivo->ano_lectivo_id,
                'tipo_periodo' => 'INSCRIPCION',
                'orden' => 2,
                'configuracion' => [
                    'permite_inscripcion' => true,
                    'requiere_todos_documentos' => true,
                    'descuento_temprano' => 5
                ]
            ],
            [
                'nombre' => 'Matrículas ' . $anioLectivo->nombre,
                'codigo' => 'MATRICULA_' . $anioBase,
                'descripcion' => 'Período de matrículas oficiales',
                'fecha_inicio' => $anioLectivo->fecha_inicio->copy()->addDays(15), // Después de inscripciones
                'fecha_fin' => $anioLectivo->fecha_inicio->copy()->addDays(30),   // 30 días después del inicio
                'ano_lectivo_id' => $anioLectivo->ano_lectivo_id,
                'tipo_periodo' => 'MATRICULA',
                'orden' => 3,
                'configuracion' => [
                    'permite_matricula' => true,
                    'requiere_pago_completo' => true,
                    'descuento_temprano' => 0
                ]
            ],
            [
                'nombre' => 'Período Académico ' . $anioLectivo->nombre,
                'codigo' => 'ACADEMICO_' . $anioBase,
                'descripcion' => 'Año académico regular',
                'fecha_inicio' => $anioLectivo->fecha_inicio->copy()->addDays(31), // Después de matrículas
                'fecha_fin' => $anioLectivo->fecha_fin->copy()->subDays(30),       // 30 días antes del fin
                'ano_lectivo_id' => $anioLectivo->ano_lectivo_id,
                'tipo_periodo' => 'ACADEMICO',
                'orden' => 4,
                'configuracion' => [
                    'clases_activas' => true,
                    'permite_matriculas_extraordinarias' => false
                ]
            ],
            [
                'nombre' => 'Cierre Académico ' . $anioLectivo->nombre,
                'codigo' => 'CIERRE_' . $anioBase,
                'descripcion' => 'Período de cierre y fin de año',
                'fecha_inicio' => $anioLectivo->fecha_fin->copy()->subDays(29), // Últimos 30 días
                'fecha_fin' => $anioLectivo->fecha_fin->copy(),                  // Fin del año lectivo
                'ano_lectivo_id' => $anioLectivo->ano_lectivo_id,
                'tipo_periodo' => 'CIERRE',
                'orden' => 5,
                'configuracion' => [
                    'cierre_notas' => true,
                    'generar_certificados' => true,
                    'preparar_siguiente_anio' => true
                ]
            ]
        ];

        foreach ($periodos as $periodo) {
            PeriodoMatricula::updateOrCreate(
                ['codigo' => $periodo['codigo']],
                $periodo
            );
        }

        $this->command->info('Períodos de matrícula creados exitosamente para ' . $anioActual);
    }
}
