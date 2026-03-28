<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PeriodoMatricula;
use App\Models\InfAnioLectivo;
use Carbon\Carbon;

class PeriodosMatriculaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creando períodos de matrícula para los años 2025 y 2026...');

        // Obtener años lectivos existentes o crearlos sin el campo estado problemático
        $anio2025 = InfAnioLectivo::where('nombre', 'like', '2025%')->first();
        $anio2026 = InfAnioLectivo::where('nombre', 'like', '2026%')->first();

        // Si no existen años lectivos, crear algunos básicos
        if (!$anio2025) {
            $anio2025 = InfAnioLectivo::create([
                'nombre' => '2025-2026',
                'descripcion' => 'Año Lectivo 2025-2026',
                'fecha_inicio' => Carbon::create(2025, 3, 1),
                'fecha_fin' => Carbon::create(2025, 12, 20),
            ]);
        }

        if (!$anio2026) {
            $anio2026 = InfAnioLectivo::create([
                'nombre' => '2026-2027',
                'descripcion' => 'Año Lectivo 2026-2027',
                'fecha_inicio' => Carbon::create(2026, 3, 1),
                'fecha_fin' => Carbon::create(2026, 12, 20),
            ]);
        }

        // Períodos para 2025
        if ($anio2025) {
            $this->crearPeriodosParaAnio($anio2025, 2025);
        }

        // Períodos para 2026 (con fechas corregidas según requerimiento del usuario)
        if ($anio2026) {
            $this->crearPeriodosParaAnio2026($anio2026);
        }

        $this->command->info('Períodos de matrícula creados exitosamente para los años 2025 y 2026');
    }

    /**
     * Crear períodos para un año específico
     */
    private function crearPeriodosParaAnio(InfAnioLectivo $anioLectivo, int $anio): void
    {
        $periodos = [
            [
                'nombre' => 'Pre-inscripción ' . $anioLectivo->nombre,
                'codigo' => 'PREINSCRIPCION_' . $anio,
                'descripcion' => 'Período para pre-inscripciones anticipadas',
                'fecha_inicio' => $anioLectivo->fecha_inicio->copy()->subMonths(2), // 2 meses antes del inicio
                'fecha_fin' => $anioLectivo->fecha_inicio->copy()->subDays(1),     // 1 día antes del inicio
                'ano_lectivo_id' => $anioLectivo->ano_lectivo_id,
                'tipo_periodo' => 'PREINSCRIPCION',
                'orden' => 1,
                'estado' => $anio === 2026 ? 'ACTIVO' : 'INACTIVO', // Solo 2026 activo
                'configuracion' => [
                    'permite_preinscripcion' => true,
                    'requiere_documentos_basicos' => true,
                    'descuento_temprano' => 10
                ]
            ],
            [
                'nombre' => 'Inscripciones ' . $anioLectivo->nombre,
                'codigo' => 'INSCRIPCION_' . $anio,
                'descripcion' => 'Período regular de inscripciones',
                'fecha_inicio' => $anioLectivo->fecha_inicio->copy(), // Inicio del año lectivo
                'fecha_fin' => $anioLectivo->fecha_inicio->copy()->addDays(14), // 2 semanas después
                'ano_lectivo_id' => $anioLectivo->ano_lectivo_id,
                'tipo_periodo' => 'INSCRIPCION',
                'orden' => 2,
                'estado' => $anio === 2026 ? 'ACTIVO' : 'INACTIVO',
                'configuracion' => [
                    'permite_inscripcion' => true,
                    'requiere_todos_documentos' => true,
                    'descuento_temprano' => 5
                ]
            ],
            [
                'nombre' => 'Matrículas ' . $anioLectivo->nombre,
                'codigo' => 'MATRICULA_' . $anio,
                'descripcion' => 'Período de matrículas oficiales',
                'fecha_inicio' => $anioLectivo->fecha_inicio->copy()->addDays(15), // Después de inscripciones
                'fecha_fin' => $anioLectivo->fecha_inicio->copy()->addDays(45),   // 45 días después del inicio
                'ano_lectivo_id' => $anioLectivo->ano_lectivo_id,
                'tipo_periodo' => 'MATRICULA',
                'orden' => 3,
                'estado' => $anio === 2026 ? 'ACTIVO' : 'INACTIVO',
                'configuracion' => [
                    'permite_matricula' => true,
                    'requiere_pago_completo' => true,
                    'descuento_temprano' => 0
                ]
            ],
            [
                'nombre' => 'Período Académico ' . $anioLectivo->nombre,
                'codigo' => 'ACADEMICO_' . $anio,
                'descripcion' => 'Año académico regular',
                'fecha_inicio' => $anioLectivo->fecha_inicio->copy()->addDays(46), // Después de matrículas
                'fecha_fin' => $anioLectivo->fecha_fin->copy()->subDays(30),       // 30 días antes del fin
                'ano_lectivo_id' => $anioLectivo->ano_lectivo_id,
                'tipo_periodo' => 'ACADEMICO',
                'orden' => 4,
                'estado' => $anio === 2026 ? 'ACTIVO' : 'INACTIVO',
                'configuracion' => [
                    'clases_activas' => true,
                    'permite_matriculas_extraordinarias' => false
                ]
            ],
            [
                'nombre' => 'Cierre Académico ' . $anioLectivo->nombre,
                'codigo' => 'CIERRE_' . $anio,
                'descripcion' => 'Período de cierre y fin de año',
                'fecha_inicio' => $anioLectivo->fecha_fin->copy()->subDays(29), // Últimos 30 días
                'fecha_fin' => $anioLectivo->fecha_fin->copy(),                  // Fin del año lectivo
                'ano_lectivo_id' => $anioLectivo->ano_lectivo_id,
                'tipo_periodo' => 'CIERRE',
                'orden' => 5,
                'estado' => $anio === 2026 ? 'ACTIVO' : 'INACTIVO',
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

        $this->command->info('Períodos creados para el año ' . $anio);
    }
}
