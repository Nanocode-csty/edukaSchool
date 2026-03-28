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
            ['nombre' => 'Aula 101', 'capacidad' => 30, 'ubicación' => 'Primer piso'],
            ['nombre' => 'Aula 102', 'capacidad' => 25, 'ubicación' => 'Primer piso'],
            ['nombre' => 'Aula 201', 'capacidad' => 35, 'ubicación' => 'Segundo piso'],
            ['nombre' => 'Laboratorio 1', 'capacidad' => 20, 'ubicación' => 'Tercer piso'],
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
        // Buscar cursos asignados existentes al docente
        $cursosAsignatura = CursoAsignatura::where('profesor_id', $this->docente->profesor_id)->take(3)->get();

        if ($cursosAsignatura->isEmpty()) {
            $this->command->info('No hay cursos asignados al docente. Buscando cualquier curso asignatura existente...');
            // Si no hay cursos asignados al docente, usar cualquier curso asignatura existente
            $cursosAsignatura = CursoAsignatura::take(3)->get();
        }

        if ($cursosAsignatura->isEmpty()) {
            $this->command->info('No hay cursos asignatura en la base de datos. Creando algunos de prueba...');
            // Si no hay cursos asignatura, crear algunos de prueba
            $cursosAsignatura = $this->crearCursosAsignaturaDePrueba();
        }

        $this->command->info('Cursos asignatura encontrados/creados: ' . $cursosAsignatura->count());
        return $cursosAsignatura;
    }

    private function crearCursosAsignaturaDePrueba()
    {
        // Obtener cursos y asignaturas existentes
        $cursos = InfCurso::take(3)->get();
        $asignaturas = InfAsignatura::take(3)->get();

        if ($cursos->isEmpty() || $asignaturas->isEmpty()) {
            $this->command->error('No hay cursos o asignaturas. Necesitas datos básicos primero.');
            return collect();
        }

        $cursosAsignatura = [];
        $diasSemana = ['Lunes', 'Martes', 'Miércoles'];
        $horarios = [
            ['hora_inicio' => '08:00', 'hora_fin' => '09:30'],
            ['hora_inicio' => '09:45', 'hora_fin' => '11:15'],
            ['hora_inicio' => '11:30', 'hora_fin' => '13:00'],
        ];

        foreach ($cursos as $index => $curso) {
            if ($index >= 3) break;

            $asignatura = $asignaturas[$index % $asignaturas->count()];

            $cursoAsignatura = CursoAsignatura::firstOrCreate(
                [
                    'curso_id' => $curso->curso_id,
                    'asignatura_id' => $asignatura->asignatura_id,
                    'profesor_id' => $this->docente->profesor_id
                ],
                [
                    'horas_semanales' => 2,
                    'dia_semana' => $diasSemana[$index % count($diasSemana)],
                    'hora_inicio' => $horarios[$index % count($horarios)]['hora_inicio'],
                    'hora_fin' => $horarios[$index % count($horarios)]['hora_fin']
                ]
            );

            $cursosAsignatura[] = $cursoAsignatura;
        }

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
        $sesionesCreadas = 0;

        // Crear sesiones para toda la semana (Lunes a Sábado)
        $diasSemana = [
            'Monday' => 'Lunes',
            'Tuesday' => 'Martes',
            'Wednesday' => 'Miércoles',
            'Thursday' => 'Jueves',
            'Friday' => 'Viernes',
            'Saturday' => 'Sábado'
        ];

        // Horarios de clase típicos
        $horarios = [
            ['hora_inicio' => '08:00', 'hora_fin' => '09:30'],
            ['hora_inicio' => '09:45', 'hora_fin' => '11:15'],
            ['hora_inicio' => '11:30', 'hora_fin' => '13:00'],
            ['hora_inicio' => '14:00', 'hora_fin' => '15:30'],
        ];

        foreach ($diasSemana as $diaIngles => $diaEspanol) {
            $fecha = Carbon::parse("next {$diaIngles}")->startOfDay();

            foreach ($cursosAsignatura as $index => $cursoAsignatura) {
                // Crear máximo 2 sesiones por día por asignatura
                if ($index >= 2) break;

                $horario = $horarios[$index % count($horarios)];
                $aula = $aulas[$index % count($aulas)];

                // Verificar si ya existe una sesión para este curso en esta fecha y horario
                $sesionExistente = SesionClase::where('curso_asignatura_id', $cursoAsignatura->curso_asignatura_id)
                    ->where('fecha', $fecha->format('Y-m-d'))
                    ->where('hora_inicio', $horario['hora_inicio'])
                    ->first();

                if (!$sesionExistente) {
                    SesionClase::create([
                        'curso_asignatura_id' => $cursoAsignatura->curso_asignatura_id,
                        'fecha' => $fecha->format('Y-m-d'),
                        'hora_inicio' => $horario['hora_inicio'],
                        'hora_fin' => $horario['hora_fin'],
                        'aula_id' => $aula->aula_id,
                        'estado' => 'Programada',
                        'observaciones' => 'Clase programada automáticamente'
                    ]);

                    $sesionesCreadas++;
                    $this->command->info("Sesión creada: {$cursoAsignatura->asignatura->nombre} - {$diaEspanol} {$fecha->format('d/m')} - {$horario['hora_inicio']} en {$aula->nombre}");
                }
            }
        }

        $this->command->info("Sesiones de clase creadas para la semana: {$sesionesCreadas}");
    }
}
