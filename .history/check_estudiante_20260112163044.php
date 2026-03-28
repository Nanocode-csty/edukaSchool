<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

// Verificar estudiante ID 1
$estudiante = \App\Models\InfEstudiante::with('persona')->find(1);

echo "=== DIAGNÓSTICO ESTUDIANTE ID 1 ===\n";
if ($estudiante) {
    echo "✓ Estudiante encontrado\n";
    echo "  ID: {$estudiante->estudiante_id}\n";
    echo "  Persona ID: {$estudiante->persona_id}\n";

    if ($estudiante->persona) {
        echo "✓ Persona encontrada\n";
        echo "  Nombre: {$estudiante->persona->nombres} {$estudiante->persona->apellidos}\n";
        echo "  DNI: {$estudiante->persona->dni}\n";
    } else {
        echo "✗ Persona NO encontrada\n";
    }

    // Verificar matrícula
    if ($estudiante->matricula) {
        echo "✓ Matrícula encontrada\n";
        echo "  Estado: {$estudiante->matricula->estado}\n";
    } else {
        echo "✗ Matrícula NO encontrada\n";
    }

} else {
    echo "✗ Estudiante NO encontrado\n";
}

// Verificar representantes
$representantes = \App\Models\InfRepresentante::all();
echo "\n=== REPRESENTANTES EN SISTEMA ===\n";
echo "Total representantes: " . $representantes->count() . "\n";

foreach ($representantes as $rep) {
    echo "Representante ID: {$rep->representante_id}\n";
    if ($rep->persona) {
        echo "  Nombre: {$rep->persona->nombres} {$rep->persona->apellidos}\n";
    }
    $estudiantesCount = $rep->estudiantes()->count();
    echo "  Estudiantes asociados: {$estudiantesCount}\n";
    echo "  IDs de estudiantes: " . implode(', ', $rep->estudiantes()->pluck('estudiante_id')->toArray()) . "\n\n";
}

echo "\n=== TABLA ESTUDIANTE_REPRESENTANTE ===\n";
$relaciones = DB::table('estudiante_representante')->get();
echo "Total relaciones: " . $relaciones->count() . "\n";

foreach ($relaciones as $rel) {
    echo "Estudiante ID: {$rel->estudiante_id} -> Representante ID: {$rel->representante_id}\n";
}
