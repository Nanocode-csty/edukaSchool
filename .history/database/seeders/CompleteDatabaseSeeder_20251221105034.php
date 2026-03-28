<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\InfEstudiante;
use App\Models\InfRepresentante;
use App\Models\Matricula;
use App\Models\InfCurso;
use App\Models\InfGrado;
use App\Models\InfSeccion;
use App\Models\AsistenciaDiaria;
use App\Models\TipoAsistencia;

class CompleteDatabaseSeeder extends Seeder
{
    public function run()
    {
        // Get existing students that are assigned to representante 26
        $estudiantesIds = DB::table('estudianterepresentante')
            ->where('representante_id', 26)
            ->pluck('estudiante_id');

        $estudiantes = InfEstudiante::whereIn('estudiante_id', $estudiantesIds)->get();

        // Get or create basic data
        $grado = InfGrado::first() ?? InfGrado::create([
            'nombre' => 'Primero',
            'descripcion' => 'Primer grado de primaria'
        ]);

        $seccion = InfSeccion::first() ?? InfSeccion::create([
            'nombre' => 'A',
            'descripcion' => 'Sección A'
        ]);

        // Create matriculas for each student (simplified)
        foreach ($estudiantes as $estudiante) {
            $matriculaExists = Matricula::where('estudiante_id', $estudiante->estudiante_id)->exists();

            if (!$matriculaExists) {
                Matricula::create([
                    'estudiante_id' => $estudiante->estudiante_id,
                    'idGrado' => $grado->id_grado,
                    'idSeccion' => $seccion->id_seccion,
                    'idCurso' => 1, // Default course ID
                    'anio_lectivo_id' => 1,
                    'fecha_matricula' => now(),
                    'estado' => 'Activo'
                ]);

                echo "Created matricula for student: {$estudiante->persona->nombres} {$estudiante->persona->apellidos}\n";
            }
        }

        // Create some attendance data for the current month
        $tipoPresente = TipoAsistencia::where('codigo', 'P')->first() ?? TipoAsistencia::create([
            'codigo' => 'P',
            'nombre' => 'Presente',
            'factor_asistencia' => 1.0
        ]);

        $tipoAusente = TipoAsistencia::where('codigo', 'A')->first() ?? TipoAsistencia::create([
            'codigo' => 'A',
            'nombre' => 'Ausente',
            'factor_asistencia' => 0.0
        ]);

        // Generate attendance for the last 20 school days
        $matriculas = Matricula::whereIn('estudiante_id', $estudiantesIds)->get();

        for ($i = 0; $i < 20; $i++) {
            $fecha = now()->subDays($i);

            // Skip weekends
            if ($fecha->isWeekend()) continue;

            foreach ($matriculas as $matricula) {
                $asistenciaExists = AsistenciaDiaria::where('matricula_id', $matricula->id)
                    ->where('fecha', $fecha->toDateString())
                    ->exists();

                if (!$asistenciaExists) {
                    // 85% chance of being present, 15% chance of being absent
                    $tipoAsistencia = rand(1, 100) <= 85 ? $tipoPresente : $tipoAusente;

                    AsistenciaDiaria::create([
                        'matricula_id' => $matricula->id,
                        'fecha' => $fecha->toDateString(),
                        'tipo_asistencia_id' => $tipoAsistencia->id,
                        'sesion_clase_id' => 1, // Default session
                        'justificado' => false,
                        'observaciones' => null
                    ]);
                }
            }
        }

        echo "Database populated successfully!\n";
        echo "Students now have matriculas and attendance data.\n";
    }
}
