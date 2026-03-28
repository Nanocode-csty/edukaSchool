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
        $this->command->info('Iniciando carga de datos de asistencia completos para 2025...');

        // Obtener tipos de asistencia
        $tiposAsistencia = TipoAsistencia::all()->keyBy('codigo');

        // Obtener docente específico (ID 27)
        $docente27 = InfDocente::find(27);
        if (!$docente27) {
            $this->command->error('Docente con ID 27 no encontrado');
            return;
        }

        $this->command->info("Trabajando con docente ID 27: {$docente27->persona->nombres} {$docente27->persona->apellidos}");

        // Obtener cursos asignados al docente 27
        $cursosDocente27 = CursoAsignatura::where('profesor_id', 27)
            ->with(['curso', 'asignatura'])
            ->get();

        $this->command->info("Docente 27 tiene " . $cursosDocente27->count() . " asignaturas");

        // Crear sesiones de clase y asistencias para 2025
        $fechaInicio = Carbon::create(2025, 3, 1); // Inicio típico de clases
        $fechaFin = Carbon::create(2025, 12, 15); // Fin del año escolar

        $diasLaborables = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

        foreach ($cursosDocente27 as $cursoAsignatura) {
            $this->command->info("Procesando curso-asignatura: {$cursoAsignatura->asignatura->nombre} - {$cursoAsignatura->curso->grado->nombre} {$cursoAsignatura->curso->seccion->nombre}");

            // Obtener estudiantes matriculados en este curso
            $estudiantes = Matricula::where('idGrado', $cursoAsignatura->curso->grado_id)
                ->where('idSeccion', $cursoAsignatura->curso->seccion_id)
                ->where('estado', 'Activo')
                ->with('estudiante')
                ->get();

            $this->command->info("  Estudiantes matriculados: " . $estudiantes->count());

            // Crear sesiones de clase para cada día laborable
            $fechaActual = $fechaInicio->copy();
            while ($fechaActual <= $fechaFin) {
                if (in_array($fechaActual->format('l'), $diasLaborables)) {
                    // Crear sesión de clase
                    $sesion = SesionClase::create([
                        'curso_asignatura_id' => $cursoAsignatura->id,
                        'fecha' => $fechaActual->format('Y-m-d'),
                        'hora_inicio' => '08:00:00',
                        'hora_fin' => '09:00:00',
                        'aula_id' => $cursoAsignatura->curso->aula_id ?? 1,
                        'tiene_asistencia_hoy' => true,
                        'observaciones' => 'Clase regular'
                    ]);

                    // Crear registros de asistencia para cada estudiante
                    foreach ($estudiantes as $matricula) {
                        // Generar asistencia aleatoria pero realista
                        $tipoAsistencia = $this->generarAsistenciaRealista($tiposAsistencia);

                        AsistenciaDiaria::create([
                            'matricula_id' => $matricula->matricula_id,
                            'sesion_clase_id' => $sesion->id,
                            'fecha' => $fechaActual->format('Y-m-d'),
                            'tipo_asistencia_id' => $tipoAsistencia->id,
                            'justificado' => $tipoAsistencia->codigo === 'J',
                            'observaciones' => $this->generarObservacionAleatoria($tipoAsistencia->codigo),
                            'usuario_registro' => 27 // Registrado por el docente 27
                        ]);
                    }
                }

                $fechaActual->addDay();
            }
        }

        // Ahora crear datos para otros docentes también
        $otrosDocentes = InfDocente::where('profesor_id', '!=', 27)->limit(10)->get();

        foreach ($otrosDocentes as $docente) {
            $cursosDocente = CursoAsignatura::where('profesor_id', $docente->profesor_id)
                ->with(['curso', 'asignatura'])
                ->get();

            foreach ($cursosDocente as $cursoAsignatura) {
                $estudiantes = Matricula::where('idGrado', $cursoAsignatura->curso->grado_id)
                    ->where('idSeccion', $cursoAsignatura->curso->seccion_id)
                    ->where('estado', 'Activo')
                    ->get();

                // Crear menos sesiones para otros docentes (solo algunas semanas)
                $fechaActual = $fechaInicio->copy();
                $semanasCreadas = 0;

                while ($fechaActual <= $fechaFin && $semanasCreadas < 4) {
                    if (in_array($fechaActual->format('l'), $diasLaborables)) {
                        $sesion = SesionClase::create([
                            'curso_asignatura_id' => $cursoAsignatura->id,
                            'fecha' => $fechaActual->format('Y-m-d'),
                            'hora_inicio' => '08:00:00',
                            'hora_fin' => '09:00:00',
                            'aula_id' => $cursoAsignatura->curso->aula_id ?? 1,
                            'tiene_asistencia_hoy' => true,
                            'observaciones' => 'Clase regular'
                        ]);

                        foreach ($estudiantes as $matricula) {
                            $tipoAsistencia = $this->generarAsistenciaRealista($tiposAsistencia);

                            AsistenciaDiaria::create([
                                'matricula_id' => $matricula->matricula_id,
                                'sesion_clase_id' => $sesion->id,
                                'fecha' => $fechaActual->format('Y-m-d'),
                                'tipo_asistencia_id' => $tipoAsistencia->id,
                                'justificado' => $tipoAsistencia->codigo === 'J',
                                'observaciones' => $this->generarObservacionAleatoria($tipoAsistencia->codigo),
                                'usuario_registro' => $docente->profesor_id
                            ]);
                        }
                    }

                    $fechaActual->addDay();

                    // Cambiar de semana cada 5 días
                    if ($fechaActual->format('N') == 1) { // Lunes
                        $semanasCreadas++;
                    }
                }
            }
        }

        $this->command->info('Datos de asistencia completos cargados exitosamente para 2025');

        // Mostrar estadísticas finales
        $totalSesiones = SesionClase::count();
        $totalAsistencias = AsistenciaDiaria::count();
        $asistenciasDocente27 = AsistenciaDiaria::where('usuario_registro', 27)->count();

        $this->command->info("Estadísticas finales:");
        $this->command->info("- Sesiones de clase creadas: {$totalSesiones}");
        $this->command->info("- Registros de asistencia: {$totalAsistencias}");
        $this->command->info("- Asistencias registradas por docente 27: {$asistenciasDocente27}");
    }

    private function generarAsistenciaRealista($tiposAsistencia)
    {
        // Probabilidades realistas de asistencia
        $probabilidades = [
            'P' => 85,  // 85% presente
            'A' => 10,  // 10% ausente
            'T' => 4,   // 4% tarde
            'J' => 1    // 1% justificado
        ];

        $rand = rand(1, 100);
        $acumulado = 0;

        foreach ($probabilidades as $codigo => $probabilidad) {
            $acumulado += $probabilidad;
            if ($rand <= $acumulado) {
                return $tiposAsistencia[$codigo];
            }
        }

        return $tiposAsistencia['P']; // Default
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
