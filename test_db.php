<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Checking database data...\n";

$rep = \App\Models\InfRepresentante::first();
if($rep) {
    echo "Representante: " . $rep->representante_id . "\n";
    $est = \App\Models\InfEstudianteRepresentante::where('representante_id', $rep->representante_id)->count();
    echo "Estudiantes relacionados: " . $est . "\n";

    $estudiantes = \App\Models\InfEstudianteRepresentante::where('representante_id', $rep->representante_id)
        ->with(['estudiante.persona'])
        ->get();

    foreach($estudiantes as $rel) {
        if($rel->estudiante) {
            echo "Estudiante ID: " . $rel->estudiante->estudiante_id . "\n";
            echo "Persona ID: " . $rel->estudiante->persona_id . "\n";
            if($rel->estudiante->persona) {
                echo "Persona data: " . $rel->estudiante->persona->apellido_paterno . " " . $rel->estudiante->persona->apellido_materno . ", " . $rel->estudiante->persona->nombres . "\n";
            } else {
                echo "No persona relationship\n";
            }
            echo "---\n";
        }
    }
} else {
    echo "No representantes found\n";
}

echo "Done.\n";