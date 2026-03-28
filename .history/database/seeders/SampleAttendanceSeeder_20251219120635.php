<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AsistenciaDiaria;
use App\Models\TipoAsistencia;
use App\Models\Matricula;
use Carbon\Carbon;

class SampleAttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener tipos de asistencia
        $tiposAsistencia = TipoAsistencia::all()->keyBy('codigo');

        // Obtener algunas matrículas para crear datos de ejemplo
        $matriculas = Matricula::take(20)->get();

        // Crear registros de asistencia para los últimos 3 meses
        $fechaInicio = Carbon::now()->subMonths(3)->startOfMonth();
        $fechaFin = Carbon::now()->endOfMonth();

        $registrosCreados = 0;

        // Para cada matrícula, crear registros de asistencia
        foreach ($matriculas as $matricula) {
            $fechaActual = $fechaInicio->copy();

            while ($fechaActual <= $fechaFin) {
                // Solo crear registros para días laborables (lunes a viernes)
                if ($fechaActual->isWeekday()) {
                    // Generar asistencia aleatoria pero realista
                    $tipoAsistencia = $this->generarTipoAsistenciaAleatorio($tiposAsistencia);

                    AsistenciaDiaria::create([
                        'matricula_id' => $matricula->matricula_id,
                        'curso_id' => null, // Will set this later if needed
                        'fecha' => $fechaActual->format('Y-m-d'),
                        'tipo_asistencia_id' => $tipoAsistencia->id,
                        'justificacion' => $tipoAsistencia->codigo === 'J',
                        'estado' => 'Activo'
                    ]);

                    $registrosCreados++;
                }

                $fechaActual->addDay();
            }
        }

        $this->command->info("Se crearon {$registrosCreados} registros de asistencia de ejemplo.");
    }

    /**
     * Genera un tipo de asistencia aleatorio pero realista
     */
    private function generarTipoAsistenciaAleatorio($tiposAsistencia)
    {
        // Probabilidades realistas:
        // 85% Presente, 10% Ausente, 3% Tarde, 2% Justificado
        $rand = rand(1, 100);

        if ($rand <= 85) {
            return $tiposAsistencia['P'];
        } elseif ($rand <= 95) {
            return $tiposAsistencia['A'];
        } elseif ($rand <= 98) {
            return $tiposAsistencia['T'];
        } else {
            return $tiposAsistencia['J'];
        }
    }
}
