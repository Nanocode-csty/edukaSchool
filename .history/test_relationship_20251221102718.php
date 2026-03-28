<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\InfRepresentante;

try {
    echo "=== TESTING RELATIONSHIP ===\n";

    $representante = InfRepresentante::find(26);
    echo "Representante found: " . ($representante ? 'Yes' : 'No') . "\n";

    if ($representante) {
        $estudiantes = $representante->estudiantes;
        echo "Students count: " . $estudiantes->count() . "\n";

        foreach ($estudiantes as $est) {
            echo "- {$est->nombres} {$est->apellidos} (ID: {$est->estudiante_id})\n";
        }
    }

    echo "\n=== ESTUDIANTES TABLE CHECK ===\n";

    $estudiantes = \Illuminate\Support\Facades\DB::table('estudiantes')->take(5)->get();
    foreach ($estudiantes as $e) {
        echo "ID: {$e->estudiante_id} | Nombres: " . ($e->nombres ?? 'NULL') . " | Apellidos: " . ($e->apellidos ?? 'NULL') . " | Persona ID: {$e->persona_id}\n";
    }

    echo "\n=== TESTING REVERSE RELATIONSHIP ===\n";

    // Test the reverse relationship
    $estudiante = \App\Models\InfEstudiante::find(1);
    if ($estudiante) {
        $representantes = $estudiante->representantes;
        echo "Student 1 has {$representantes->count()} representantes\n";
        foreach ($representantes as $rep) {
            echo "- Representante ID: {$rep->representante_id} (Persona: {$rep->persona->nombres})\n";
        }
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
