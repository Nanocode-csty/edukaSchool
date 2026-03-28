<?php

use App\Models\InfCurso;
use App\Models\InfAsignatura;
use App\Models\CursoAsignatura;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

echo "Starting check for courses and subjects...\n";

$cursos = InfCurso::with(['grado', 'seccion', 'anoLectivo'])->get();
$asignaturas = InfAsignatura::all();

echo "Total Cursos: " . $cursos->count() . "\n";
echo "Total Asignaturas Disponibles: " . $asignaturas->count() . "\n";

$cursosSinAsignaturas = [];

foreach ($cursos as $curso) {
    $count = DB::table('cursoasignaturas')->where('curso_id', $curso->curso_id)->count();
    $nombreCurso = ($curso->grado->nombre ?? 'Sin Grado') . ' ' . 
                  ($curso->seccion->nombre ?? 'Sin Seccion') . ' - ' . 
                  ($curso->anoLectivo->nombre ?? 'Sin Año');
                  
    if ($count == 0) {
        echo "[VACIO] Curso ID {$curso->curso_id} ($nombreCurso) tiene 0 asignaturas.\n";
        $cursosSinAsignaturas[] = $curso;
    } else {
        echo "[OK] Curso ID {$curso->curso_id} ($nombreCurso) tiene $count asignaturas.\n";
    }
}

if (count($cursosSinAsignaturas) > 0) {
    echo "\nHay " . count($cursosSinAsignaturas) . " cursos sin asignaturas. Procediendo a crear asignaciones por defecto...\n";
    
    if ($asignaturas->count() == 0) {
        echo "Error: No hay asignaturas base en la tabla 'asignaturas'. No se puede seedear.\n";
        exit;
    }

    foreach ($cursosSinAsignaturas as $curso) {
        echo "Asignando asignaturas al curso ID {$curso->curso_id}...\n";
        foreach ($asignaturas as $asignatura) {
            // Check if exists just in case
            $exists = DB::table('cursoasignaturas')
                ->where('curso_id', $curso->curso_id)
                ->where('asignatura_id', $asignatura->asignatura_id)
                ->exists();
                
            if (!$exists) {
                // Determine a default professor if possible, or null
                // We might check if the course has a profesor_principal_id
                $profesorId = $curso->profesor_principal_id ?? null; // Can be null if not assigned
                
                DB::table('cursoasignaturas')->insert([
                    'curso_id' => $curso->curso_id,
                    'asignatura_id' => $asignatura->asignatura_id,
                    'profesor_id' => $profesorId, // Assign main teacher or null
                    'horas_semanales' => 2, // Default
                    'dia_semana' => 'Lunes', // Default placeholder
                    'hora_inicio' => '08:00:00',
                    'hora_fin' => '10:00:00'
                ]);
                echo " - Asignada: {$asignatura->nombre}\n";
            }
        }
    }
    echo "\nSeeding completado.\n";
} else {
    echo "\nTodos los cursos tienen asignaturas. No se requiere acción.\n";
}
