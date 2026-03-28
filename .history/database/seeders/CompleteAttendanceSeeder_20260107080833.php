<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\InfDocente;
use App\Models\InfEstudiante;
use App\Models\Matricula;
use App\Models\InfCurso;
use App\Models\CursoAsignatura;
use App\Models\SesionClase;
use App\Models\AsistenciaDiaria;
use App\Models\TipoAsistencia;
use Carbon\Carbon;

class CompleteAttendanceSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('Iniciando carga de datos de asistencia para sesiones existentes...');

        // Obtener tipos de asistencia
        $tiposAsistencia = TipoAsistencia::all()->keyBy('codigo');

        // Obtener todas las sesiones existentes
        $sesiones = SesionClase::with(['cursoAsignatura.curso', 'cursoAsignatura.asignatura'])->get();

        $this->command->info("Procesando " . $sesiones->count() . " sesiones existentes");

        foreach ($sesiones as $sesion) {
            // Obtener estudiantes matriculados en este curso
            $estudiantes = Matricula::where('idGrado', $sesion->cursoAsignatura->curso->grado_id)
                ->where('idSeccion', $sesion->cursoAsignatura->curso->seccion_id)
                ->where('estado', 'Matriculado')
                ->with('estudiante')
                ->get();

            $this->command->info("Sesión {$sesion->sesion_id}: {$estudiantes->count()} estudiantes");

            // Crear registros de asistencia para cada estudiante
            foreach ($estudiantes as $matricula) {
                // Verificar si ya existe asistencia para este estudiante en esta sesión
                $asistenciaExistente = AsistenciaDiaria::where('matricula_id', $matricula->matricula_id)
                    ->where('sesion_clase_id', $sesion->sesion_id)
                    ->first();

                if ($asistenciaExistente) {
                    continue; // Ya existe, saltar
                }

                // Generar asistencia aleatoria pero realista
                $tipoAsistencia = $this->generarAsistenciaRealista($tiposAsistencia);

                if (!$tipoAsistencia) {
                    $this->command->error("No se pudo generar tipo de asistencia para estudiante {$matricula->matricula_id}");
                    continue;
                }

                AsistenciaDiaria::create([
                    'matricula_id' => $matricula->matricula_id,
                    'curso_id' => $sesion->cursoAsignatura->curso_id,
                    'sesion_clase_id' => $sesion->sesion_id,
                    'fecha' => $sesion->fecha,
                    'tipo_asistencia_id' => $tipoAsistencia->id,
                    'justificacion' => $tipoAsistencia->codigo === 'J',
                    'usuario_registro' => $sesion->docente_id ?? $sesion->usuario_registro ?? 1
                ]);
            }
        }

        $this->command->info('Datos de asistencia completos cargados exitosamente');

        // Mostrar estadísticas finales
        $totalSesiones = SesionClase::count();
        $totalAsistencias = AsistenciaDiaria::count();
        $asistenciasDocente27 = AsistenciaDiaria::where('usuario_registro', 27)->count();

        $this->command->info("Estadísticas finales:");
        $this->command->info("- Sesiones de clase totales: {$totalSesiones}");
        $this->command->info("- Registros de asistencia creados: {$totalAsistencias}");
        $this->command->info("- Asistencias registradas por docente 27: {$asistenciasDocente27}");
    }

    private function generarAsistenciaRealista($tiposAsistencia)
    {
        // Probabilidades realistas de asistencia
        $probabilidades = [
            'P' => 80,  // 80% presente
            'A' => 10,  // 10% ausente
            'T' => 5,   // 5% tarde
            'F' => 3,   // 3% falta
            'J' => 2    // 2% justificado
        ];

        $rand = rand(1, 100);
        $acumulado = 0;

        foreach ($probabilidades as $codigo => $probabilidad) {
            $acumulado += $probabilidad;
            if ($rand <= $acumulado && isset($tiposAsistencia[$codigo])) {
                return $tiposAsistencia[$codigo];
            }
        }

        return $tiposAsistencia['P'] ?? $tiposAsistencia->first(); // Default
    }

    private function generarObservacionAleatoria($codigo)
    {
        $observaciones = [
            'P' => ['Presente', 'Asistió puntualmente', 'Presente en clase', ''],
            'A' => ['Ausente sin justificación', 'Faltó a clase', 'No asistió', 'Ausencia'],
            'T' => ['Llegó tarde', 'Retraso de 15 minutos', 'Entrada tardía', 'Tardanza'],
            'J' => ['Certificado médico', 'Justificación presentada', 'Ausencia justificada', 'Documento válido']
        ];

        $opciones = $observaciones[$codigo] ?? [''];
        return $opciones[array_rand($opciones)];
    }
}
