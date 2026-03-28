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
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creando horario de prueba para docente...');

        // Buscar el docente de prueba
        $docente = InfDocente::whereHas('persona', function($query) {
            $query->where('dni', '66666666');
        })->first();

        if (!$docente) {
            $this->command->error('No se encontró el docente de prueba. Ejecuta primero: php artisan db:seed --class=DocenteTestSeeder');
            return;
        }

        $this->command->info('Docente encontrado: ' . $docente->persona->nombres . ' ' . $docente->persona->apellidos);

        // Crear aulas si no existen
        $aulas = $this->crearAulas();

        // Obtener o crear cursos asignados al docente
        $cursosAsignatura = $this->crearCursosAsignaturaDocente($docente);

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

    private function crearCursosAsignaturaDocente($docente)
    {
        // Obtener algunos cursos existentes
        $cursos = InfCurso::take(4)->get();

        if ($cursos->isEmpty()) {
            $this->command->error('No hay cursos en la base de datos. Crea cursos primero.');
            return collect();
        }

        // Obtener algunas asignaturas
        $asignaturas = InfAsignatura::take(4)->get();

        if ($asignaturas->isEmpty()) {
            $this->command->error('No hay asignaturas en la base de datos. Crea asignaturas primero.');
            return collect();
        }

        $cursosAsignatura = [];

        // Asignar asignaturas a cursos para el docente
        foreach ($cursos->take(4) as $index => $curso) {
            $asignatura = $asignaturas->get($index % $asignaturas->count());

            $cursoAsignatura = CursoAsignatura::firstOrCreate(
                [
                    'curso_id' => $curso->id,
                    'asignatura_id' => $asignatura->id,
                    'profesor_id' => $docente->profesor_id
                ],
                [
                    'horas_semanales' => rand(2, 4)
                ]
            );

            $cursosAsignatura[] = $cursoAsignatura;
        }

        $this->command->info('Cursos asignados al docente: ' . count($cursosAsignatura));
        return collect($cursosAsignatura);
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
                    'docente_id' => $docente->profesor_id,
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
