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
        echo "=== POPULATING DATABASE WITH CONSISTENT DATA ===\n";

        // Step 1: Ensure we have basic data
        echo "Step 1: Creating basic data...\n";

        $grado = InfGrado::firstOrCreate([
            'nombre' => 'Primero',
            'nivel_id' => 1
        ], [
            'descripcion' => 'Primer grado de primaria'
        ]);

        $seccion = InfSeccion::firstOrCreate([
            'nombre' => 'A'
        ], [
            'descripcion' => 'Sección A',
            'capacidad_maxima' => 30
        ]);

        echo "✓ Grado: {$grado->nombre} (ID: {$grado->id_grado})\n";
        echo "✓ Sección: {$seccion->nombre} (ID: {$seccion->id_seccion})\n";

        // Step 2: Get students assigned to representante 26
        $estudiantesIds = DB::table('estudianterepresentante')
            ->where('representante_id', 26)
            ->pluck('estudiante_id');

        $estudiantes = InfEstudiante::whereIn('estudiante_id', $estudiantesIds)->get();
        echo "✓ Found {$estudiantes->count()} students assigned to representante 26\n";

        // Step 3: Create or update matriculas for each student
        echo "Step 3: Creating matriculas...\n";
        foreach ($estudiantes as $estudiante) {
            $matricula = Matricula::updateOrCreate(
                ['estudiante_id' => $estudiante->estudiante_id],
                [
                    'idGrado' => $grado->id_grado,
                    'idSeccion' => $seccion->id_seccion,
                    'fecha_matricula' => now(),
                    'estado' => 'Activo'
                ]
            );

            echo "✓ Matricula for {$estudiante->persona->nombres} {$estudiante->persona->apellidos}: ";
            echo "Grado {$matricula->idGrado}, Sección {$matricula->idSeccion}\n";
        }

        // Step 4: Verify relationships work
        echo "Step 4: Verifying relationships...\n";
        $matriculas = Matricula::whereIn('estudiante_id', $estudiantesIds)->with(['grado', 'seccion'])->get();

        foreach ($matriculas as $matricula) {
            $estudiante = $matricula->estudiante;
            echo "✓ {$estudiante->persona->nombres} {$estudiante->persona->apellidos}: ";
            echo "Grado '{$matricula->grado->nombre}', Sección '{$matricula->seccion->nombre}'\n";
        }

        // Step 5: Create some attendance data
        echo "Step 5: Creating attendance data...\n";

        $tipoPresente = TipoAsistencia::firstOrCreate([
            'codigo' => 'P'
        ], [
            'nombre' => 'Presente',
            'factor_asistencia' => 1.0
        ]);

        $tipoAusente = TipoAsistencia::firstOrCreate([
            'codigo' => 'A'
        ], [
            'nombre' => 'Ausente',
            'factor_asistencia' => 0.0
        ]);

        // Generate attendance for the last 10 school days
        $diasCreados = 0;
        for ($i = 0; $i < 10; $i++) {
            $fecha = now()->subDays($i);

            // Skip weekends
            if ($fecha->isWeekend()) continue;

            foreach ($matriculas as $matricula) {
                $asistenciaExists = AsistenciaDiaria::where('matricula_id', $matricula->id)
                    ->where('fecha', $fecha->toDateString())
                    ->exists();

                if (!$asistenciaExists) {
                    // 80% chance of being present, 20% chance of being absent
                    $tipoAsistencia = rand(1, 100) <= 80 ? $tipoPresente : $tipoAusente;

                    AsistenciaDiaria::create([
                        'matricula_id' => $matricula->id,
                        'fecha' => $fecha->toDateString(),
                        'tipo_asistencia_id' => $tipoAsistencia->id,
                        'sesion_clase_id' => null, // No session required for daily attendance
                        'justificado' => false,
                        'observaciones' => null
                    ]);
                    $diasCreados++;
                }
            }
        }

        echo "✓ Created attendance data for {$diasCreados} student-days\n";

        echo "\n=== DATABASE POPULATION COMPLETE ===\n";
        echo "✓ All students now have matriculas with proper grade/section assignments\n";
        echo "✓ Attendance data created for consistent statistics\n";
        echo "✓ Relationships verified and working\n";

        echo "\n=== FINAL VERIFICATION ===\n";
        $representante = InfRepresentante::find(26);
        $estudiantesFinal = $representante->estudiantes()->with(['persona', 'matricula.grado', 'matricula.seccion'])->get();

        echo "Representante 26 has {$estudiantesFinal->count()} students:\n";
        foreach ($estudiantesFinal as $est) {
            $matricula = $est->matricula;
            echo "- {$est->persona->nombres} {$est->persona->apellidos} | ";
            echo "Grado: {$matricula->grado->nombre} | ";
            echo "Sección: {$matricula->seccion->nombre}\n";
        }
    }
}
