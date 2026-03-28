<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InfAnioLectivo;
use App\Models\InfPeriodosEvaluacion;
use Carbon\Carbon;

class PeriodosEvaluacionRealSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creando períodos de evaluación para el año académico activo...');

        // Obtener el año académico activo
        $anioActual = InfAnioLectivo::where('estado', 'Activo')->first();

        if (!$anioActual) {
            $this->command->error('No hay año académico activo. Ejecuta primero el PresentationSeeder.');
            return;
        }

        $this->command->info("Año académico: {$anioActual->nombre}");

        // Definir los 4 bimestres del año escolar
        $periodos = [
            [
                'nombre' => 'Primer Bimestre',
                'fecha_inicio' => Carbon::create($anioActual->fecha_inicio->year, 3, 1), // Marzo
                'fecha_fin' => Carbon::create($anioActual->fecha_inicio->year, 5, 31), // Mayo
                'estado' => 'Finalizado' // Para pruebas, marcamos como finalizado
            ],
            [
                'nombre' => 'Segundo Bimestre',
                'fecha_inicio' => Carbon::create($anioActual->fecha_inicio->year, 6, 1), // Junio
                'fecha_fin' => Carbon::create($anioActual->fecha_inicio->year, 8, 31), // Agosto
                'estado' => 'Finalizado'
            ],
            [
                'nombre' => 'Tercer Bimestre',
                'fecha_inicio' => Carbon::create($anioActual->fecha_inicio->year, 9, 1), // Septiembre
                'fecha_fin' => Carbon::create($anioActual->fecha_inicio->year, 11, 30), // Noviembre
                'estado' => 'Finalizado'
            ],
            [
                'nombre' => 'Cuarto Bimestre',
                'fecha_inicio' => Carbon::create($anioActual->fecha_inicio->year, 12, 1), // Diciembre
                'fecha_fin' => Carbon::create($anioActual->fecha_inicio->year + 1, 1, 31), // Enero
                'estado' => 'En Curso' // El último bimestre está en curso
            ]
        ];

        $periodosCreados = 0;

        foreach ($periodos as $periodoData) {
            $periodo = InfPeriodosEvaluacion::updateOrCreate(
                [
                    'ano_lectivo_id' => $anioActual->ano_lectivo_id,
                    'nombre' => $periodoData['nombre']
                ],
                [
                    'fecha_inicio' => $periodoData['fecha_inicio'],
                    'fecha_fin' => $periodoData['fecha_fin'],
                    'estado' => $periodoData['estado'],
                    'descripcion' => "Evaluación del {$periodoData['nombre']} del año académico {$anioActual->nombre}"
                ]
            );

            $periodosCreados++;
            $this->command->info("✓ {$periodo->nombre}: {$periodo->fecha_inicio->format('d/m/Y')} - {$periodo->fecha_fin->format('d/m/Y')} ({$periodo->estado})");
        }

        $this->command->info("=== PERÍODOS DE EVALUACIÓN CREADOS: {$periodosCreados} ===");
    }
}
