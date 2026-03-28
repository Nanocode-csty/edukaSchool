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

        $periodos = [
            [
                'nombre' => 'Pre-inscripción ' . $anioActual,
                'codigo' => 'PREINSCRIPCION_' . $anioActual,
                'descripcion' => 'Período para pre-inscripciones anticipadas',
                'fecha_inicio' => Carbon::create($anioActual, 1, 15), // 15 enero
                'fecha_fin' => Carbon::create($anioActual, 2, 28),    // 28 febrero
                'anio_academico' => $anioActual,
                'tipo_periodo' => 'PREINSCRIPCION',
                'orden' => 1,
                'configuracion' => [
                    'permite_preinscripcion' => true,
                    'requiere_documentos_basicos' => true,
                    'descuento_temprano' => 10
                ]
            ],
            [
                'nombre' => 'Inscripciones ' . $anioActual,
                'codigo' => 'INSCRIPCION_' . $anioActual,
                'descripcion' => 'Período regular de inscripciones',
                'fecha_inicio' => Carbon::create($anioActual, 3, 1),  // 1 marzo
                'fecha_fin' => Carbon::create($anioActual, 3, 31),    // 31 marzo
                'anio_academico' => $anioActual,
                'tipo_periodo' => 'INSCRIPCION',
                'orden' => 2,
                'configuracion' => [
                    'permite_inscripcion' => true,
                    'requiere_todos_documentos' => true,
                    'descuento_temprano' => 5
                ]
            ],
            [
                'nombre' => 'Matrículas ' . $anioActual,
                'codigo' => 'MATRICULA_' . $anioActual,
                'descripcion' => 'Período de matrículas oficiales',
                'fecha_inicio' => Carbon::create($anioActual, 4, 1),  // 1 abril
                'fecha_fin' => Carbon::create($anioActual, 4, 15),    // 15 abril
                'anio_academico' => $anioActual,
                'tipo_periodo' => 'MATRICULA',
                'orden' => 3,
                'configuracion' => [
                    'permite_matricula' => true,
                    'requiere_pago_completo' => true,
                    'descuento_temprano' => 0
                ]
            ],
            [
                'nombre' => 'Período Académico ' . $anioActual,
                'codigo' => 'ACADEMICO_' . $anioActual,
                'descripcion' => 'Año académico regular',
                'fecha_inicio' => Carbon::create($anioActual, 4, 16), // 16 abril
                'fecha_fin' => Carbon::create($anioActual, 12, 20),   // 20 diciembre
                'anio_academico' => $anioActual,
                'tipo_periodo' => 'ACADEMICO',
                'orden' => 4,
                'configuracion' => [
                    'clases_activas' => true,
                    'permite_matriculas_extraordinarias' => false
                ]
            ],
            [
                'nombre' => 'Cierre Académico ' . $anioActual,
                'codigo' => 'CIERRE_' . $anioActual,
                'descripcion' => 'Período de cierre y fin de año',
                'fecha_inicio' => Carbon::create($anioActual, 12, 21), // 21 diciembre
                'fecha_fin' => Carbon::create($anioActual, 12, 31),    // 31 diciembre
                'anio_academico' => $anioActual,
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
