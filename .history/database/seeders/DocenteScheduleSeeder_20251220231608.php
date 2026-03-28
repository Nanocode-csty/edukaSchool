<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InfDocente;
use App\Models\CursoAsignatura;
use App\Models\InfAula;
use App\Models\SesionClase;
use App\Models\InfCurso;
use App\Models\InfAsignatura;
use Carbon\Carbon;

class DocenteScheduleSeeder extends Seeder
{
    private $docente;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creando horario de prueba para docente...');

        // Buscar el docente de prueba
        $this->docente = InfDocente::whereHas('persona', function($query) {
            $query->where('dni', '66666666');
        })->first();

        if (!$this->docente) {
            $this->command->error('No se encontró el docente de prueba. Ejecuta primero: php artisan db:seed --class=DocenteTestSeeder');
            return;
        }

        $this->command->info('Docente encontrado: ' . $this->docente->persona->nombres . ' ' . $this->docente->persona->apellidos);

        // Crear aulas si no existen
        $aulas = $this->crearAulas();

        // Obtener o crear cursos asignados al docente
        $cursosAsignatura = $this->crearCursosAsignaturaDocente();

        // Crear sesiones de clase para hoy
        $this->crearSesionesClaseHoy($cursosAsignatura, $aulas);

        $this->command->info('Horario de docente creado exitosamente.');
        $this->command->info('Usuario: docente');
        $this->command->info('Contraseña: password');
    }

    private function crearAulas()
    {
        $aulasData = [
            ['nombre' => 'Aula 101', 'capacidad' => 30, 'ubicacion' => 'Primer piso'],
            ['nombre' => 'Aula 102', 'capacidad' => 25, 'ubicacion' => 'Primer piso'],
            ['nombre' => 'Aula 201', 'capacidad' => 35, 'ubicacion' => 'Segundo piso'],
            ['nombre' => 'Laboratorio 1', 'capacidad' => 20, 'ubicacion' => 'Tercer piso'],
        ];

        $aulas = [];
        foreach ($aulasData as $aulaData) {
            $aula = InfAula::firstOrCreate(
                ['nombre' => $aulaData['nombre']],
                $aulaData
            );
            $aulas[] = $aula;
        }

        $this->command->info('Aulas creadas/verficadas: ' . count($aulas));
        return $aulas;
    }

    private function crearCursosAsignaturaDocente()
    {
        // Obtener algunos cursos existentes
        $cursos = InfCurso::take(4)->get();

        if ($cursos->isEmpty()) {
            $this->command->info('No hay cursos en la base de datos. Creando cursos de prueba...');
            $cursos = $this->crearCursosDePrueba();
        }

        // Obtener algunas asignaturas
        $asignaturas = InfAsignatura::take(4)->get();

        if ($asignaturas->isEmpty()) {
            $this->command->info('No hay asignaturas en la base de datos. Creando asignaturas de prueba...');
            $asignaturas = $this->crearAsignaturasDePrueba();
        }

        $cursosAsignatura = [];

        // Días de la semana disponibles
        $diasSemana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];

        // Asignar asignaturas a cursos para el docente
        foreach ($cursos->take(4) as $index => $curso) {
            $asignatura = $asignaturas->get($index % $asignaturas->count());

            // Usar diferentes días y horarios para evitar conflictos de unicidad
            $diaSemana = $diasSemana[$index % count($diasSemana)];
            $horaInicio = sprintf('%02d:00:00', 8 + ($index * 2)); // 08:00, 10:00, 12:00, etc.
            $horaFin = sprintf('%02d:30:00', 9 + ($index * 2)); // 09:30, 11:30, 13:30, etc.

            $cursoAsignatura = CursoAsignatura::firstOrCreate(
                [
                    'curso_id' => $curso->curso_id,
                    'asignatura_id' => $asignatura->asignatura_id,
                    'profesor_id' => $this->docente->profesor_id
                ],
                [
                    'horas_semanales' => rand(2, 4),
                    'dia_semana' => $diaSemana,
                    'hora_inicio' => $horaInicio,
                    'hora_fin' => $horaFin
                ]
            );

            $cursosAsignatura[] = $cursoAsignatura;
        }

        $this->command->info('Cursos asignados al docente: ' . count($cursosAsignatura));
        return collect($cursosAsignatura);
    }

    private function crearCursosDePrueba()
    {
        // Obtener grados y secciones existentes
        $grados = \App\Models\InfGrado::take(2)->get();
        $secciones = \App\Models\InfSeccion::take(2)->get();
        $anioLectivo = \App\Models\InfAnioLectivo::first();

        if ($grados->isEmpty() || $secciones->isEmpty()) {
            $this->command->error('No hay grados o secciones. Necesitas datos básicos primero.');
            return collect();
        }

        $cursos = [];
        foreach ($grados as $grado) {
            foreach ($secciones as $seccion) {
                $curso = InfCurso::firstOrCreate(
                    [
                        'grado_id' => $grado->grado_id,
                        'seccion_id' => $seccion->seccion_id,
                        'ano_lectivo_id' => $anioLectivo ? $anioLectivo->ano_lectivo_id : 1
                    ],
                    [
                        'cupo_maximo' => 30,
                        'estado' => 'Disponible'
                    ]
                );
                $cursos[] = $curso;
            }
        }

        return collect($cursos);
    }

    private function crearAsignaturasDePrueba()
    {
        $asignaturasData = [
            ['nombre' => 'Matemáticas', 'codigo' => 'MAT', 'estado' => 'Activo'],
            ['nombre' => 'Lenguaje', 'codigo' => 'LEN', 'estado' => 'Activo'],
            ['nombre' => 'Ciencias', 'codigo' => 'CIE', 'estado' => 'Activo'],
            ['nombre' => 'Historia', 'codigo' => 'HIS', 'estado' => 'Activo'],
        ];

        $asignaturas = [];
        foreach ($asignaturasData as $data) {
            $asignatura = \App\Models\InfAsignatura::firstOrCreate(
                ['codigo' => $data['codigo']],
                $data
            );
            $asignaturas[] = $asignatura;
        }

        return collect($asignaturas);
    }

    private function crearSesionesClaseHoy($cursosAsignatura, $aulas)
    {
        $hoy = Carbon::today();
        $sesionesCreadas = 0;

        // Horarios de clase típicos
        $horarios = [
            ['hora_inicio' => '08:00', 'hora_fin' => '09:30'],
            ['hora_inicio' => '09:45', 'hora_fin' => '11:15'],
            ['hora_inicio' => '11:30', 'hora_fin' => '13:00'],
            ['hora_inicio' => '14:00', 'hora_fin' => '15:30'],
        ];

        foreach ($cursosAsignatura as $index => $cursoAsignatura) {
            // Solo crear algunas sesiones (no todas)
            if ($index >= 3) break;

            $horario = $horarios[$index % count($horarios)];
            $aula = $aulas[$index % count($aulas)];

            // Verificar si ya existe una sesión para este curso en esta fecha y horario
            $sesionExistente = SesionClase::where('curso_asignatura_id', $cursoAsignatura->curso_asignatura_id)
                ->where('fecha', $hoy->format('Y-m-d'))
                ->where('hora_inicio', $horario['hora_inicio'])
                ->first();

            if (!$sesionExistente) {
                SesionClase::create([
                    'curso_asignatura_id' => $cursoAsignatura->curso_asignatura_id,
                    'docente_id' => $this->docente->profesor_id,
                    'fecha' => $hoy->format('Y-m-d'),
                    'hora_inicio' => $horario['hora_inicio'],
                    'hora_fin' => $horario['hora_fin'],
                    'aula_id' => $aula->id,
                    'estado' => 'Programada',
                    'observaciones' => 'Clase programada automáticamente'
                ]);

                $sesionesCreadas++;
                $this->command->info("Sesión creada: {$cursoAsignatura->asignatura->nombre} - {$horario['hora_inicio']} en {$aula->nombre}");
            }
        }

        $this->command->info("Sesiones de clase creadas para hoy: {$sesionesCreadas}");
    }
}
