<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AsistenciaAsignatura;
use App\Models\TipoAsistencia;
use App\Models\Matricula;
use App\Models\CursoAsignatura;
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

        // Debug: mostrar qué tipos de asistencia existen
        $this->command->info("Tipos de asistencia encontrados:");
        foreach ($tiposAsistencia as $codigo => $tipo) {
            $this->command->info("{$codigo}: ID {$tipo->tipo_asistencia_id}");
        }

        // Si no hay tipos de asistencia, salir
        if ($tiposAsistencia->isEmpty()) {
            $this->command->error("No hay tipos de asistencia en la base de datos. Ejecuta el seeder de tipos de asistencia primero.");
            return;
        }

        // Obtener algunas matrículas para crear datos de ejemplo
        $matriculas = Matricula::take(20)->get();

        // Obtener algunos cursos asignatura
        $cursosAsignatura = CursoAsignatura::take(5)->get();

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

                    // Usar un curso asignatura aleatorio
                    $cursoAsignatura = $cursosAsignatura->random();

                    AsistenciaAsignatura::create([
                        'matricula_id' => $matricula->matricula_id,
                        'curso_asignatura_id' => $cursoAsignatura->curso_asignatura_id,
                        'fecha' => $fechaActual->format('Y-m-d'),
                        'tipo_asistencia_id' => $tipoAsistencia->tipo_asistencia_id,
                        'justificacion' => $tipoAsistencia->codigo === 'J'
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
        // Prioridades: P (Presente), A (Ausente), T (Tarde), J (Justificado)
        $preferencias = ['P', 'A', 'T', 'J'];

        // Probabilidades realistas:
        // 85% Presente, 10% Ausente, 3% Tarde, 2% Justificado
        $rand = rand(1, 100);

        $codigo = 'P'; // default
        if ($rand <= 85) {
            $codigo = 'P';
        } elseif ($rand <= 95) {
            $codigo = 'A';
        } elseif ($rand <= 98) {
            $codigo = 'T';
        } else {
            $codigo = 'J';
        }

        // Buscar el tipo de asistencia por código
        $tipo = $tiposAsistencia->first(function($tipo) use ($codigo) {
            return $tipo->codigo === $codigo;
        });

        // Si no se encuentra, usar el primero disponible
        if (!$tipo) {
            $tipo = $tiposAsistencia->first();
            $this->command->warn("Tipo de asistencia '{$codigo}' no encontrado, usando '{$tipo->codigo}'");
        }

        return $tipo;
    }
}
