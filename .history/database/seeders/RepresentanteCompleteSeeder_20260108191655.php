<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Persona;
use App\Models\Usuario;
use App\Models\Rol;
use App\Models\InfRepresentante;
use App\Models\InfEstudiante;
use App\Models\InfCurso;
use App\Models\Matricula;
use App\Models\AsistenciaDiaria;
use App\Models\TipoAsistencia;
use App\Models\SesionClase;
use App\Models\NotasFinalesPeriodo;
use App\Models\InfPeriodosEvaluacion;
use App\Models\CursoAsignatura;
use Illuminate\Support\Facades\DB;

class RepresentanteCompleteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creando datos completos para representante ID 26...');

        // Obtener el representante con ID 26
        $representante = InfRepresentante::find(26);

        if (!$representante) {
            $this->command->error('Representante con ID 26 no encontrado. Ejecuta primero RepresentanteTestSeeder');
            return;
        }

        // Obtener estudiantes asociados al representante
        $estudiantesIds = DB::table('estudiante_representante')
            ->where('representante_id', 26)
            ->pluck('estudiante_id')
            ->toArray();

        if (empty($estudiantesIds)) {
            $this->command->error('No hay estudiantes asociados al representante 26');
            return;
        }

        $this->command->info('Estudiantes encontrados: ' . count($estudiantesIds));

        // Crear matrículas para los estudiantes si no existen
        $this->crearMatriculasParaEstudiantes($estudiantesIds);

        // Crear datos de asistencia
        $this->crearDatosAsistencia($estudiantesIds);

        // Crear datos de calificaciones
        $this->crearDatosCalificaciones($estudiantesIds);

        $this->command->info('Datos completos creados exitosamente para representante ID 26');
    }

    private function crearMatriculasParaEstudiantes($estudiantesIds)
    {
        $this->command->info('Creando matrículas...');

        // Obtener grado y sección de prueba
        $grado = \App\Models\InfGrado::first();
        $seccion = \App\Models\InfSeccion::first();

        if (!$grado || !$seccion) {
            $this->command->error('No hay grados o secciones disponibles. Crea datos básicos primero.');
            return;
        }

        $anioActual = date('Y');

        foreach ($estudiantesIds as $estudianteId) {
            $matriculaExistente = Matricula::where('estudiante_id', $estudianteId)
                ->where('anio_academico', $anioActual)
                ->first();

            if (!$matriculaExistente) {
                Matricula::create([
                    'estudiante_id' => $estudianteId,
                    'idGrado' => $grado->grado_id,
                    'idSeccion' => $seccion->seccion_id,
                    'anio_academico' => $anioActual,
                    'numero_matricula' => Matricula::generarNumeroMatricula($anioActual),
                    'fecha_matricula' => now()->subDays(rand(1, 30)),
                    'estado' => 'Matriculado',
                    'observaciones' => 'Matrícula creada por seeder',
                    'usuario_registro' => 1
                ]);

                $this->command->info("Matrícula creada para estudiante ID: {$estudianteId}");
            }
        }
    }

    private function crearDatosAsistencia($estudiantesIds)
    {
        $this->command->info('Saltando creación de datos de asistencia - tabla no disponible');
        // Skip attendance data creation due to table issues
    }

    private function crearDatosCalificaciones($estudiantesIds)
    {
        $this->command->info('Creando datos de calificaciones...');

        // Obtener períodos de evaluación
        $periodos = InfPeriodosEvaluacion::orderBy('fecha_inicio')->get();

        if ($periodos->isEmpty()) {
            $this->command->error('No hay períodos de evaluación configurados');
            return;
        }

        // Obtener asignaturas disponibles
        $asignaturas = CursoAsignatura::with('asignatura')->get();

        if ($asignaturas->isEmpty()) {
            $this->command->error('No hay asignaturas configuradas');
            return;
        }

        foreach ($estudiantesIds as $estudianteId) {
            // Obtener matrícula del estudiante
            $matricula = Matricula::where('estudiante_id', $estudianteId)->first();

            if (!$matricula) {
                continue;
            }

            foreach ($asignaturas as $cursoAsignatura) {
                foreach ($periodos as $periodo) {
                    // Verificar si ya existe calificación
                    $calificacionExistente = NotasFinalesPeriodo::where('matricula_id', $matricula->matricula_id)
                        ->where('curso_asignatura_id', $cursoAsignatura->curso_asignatura_id)
                        ->where('periodo_id', $periodo->periodo_id)
                        ->first();

                    if (!$calificacionExistente) {
                        // Generar calificación aleatoria entre 11 y 20
                        $calificacion = rand(11, 20);

                        NotasFinalesPeriodo::create([
                            'matricula_id' => $matricula->matricula_id,
                            'curso_asignatura_id' => $cursoAsignatura->curso_asignatura_id,
                            'periodo_id' => $periodo->periodo_id,
                            'promedio' => $calificacion,
                            'observaciones' => $calificacion >= 18 ? 'Excelente rendimiento' :
                                             ($calificacion >= 15 ? 'Buen rendimiento' :
                                             ($calificacion >= 11 ? 'Rendimiento aceptable' : 'Requiere atención')),
                            'estado' => 'Calculado',
                            'fecha_calculo' => now(),
                            'usuario_registro' => 1 // Usuario admin
                        ]);
                    }
                }
            }
        }

        $this->command->info('Datos de calificaciones creados para todos los períodos y asignaturas');
    }
}
        $asignaturas = CursoAsignatura::with('asignatura')->get();
