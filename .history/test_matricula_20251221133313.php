<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Matricula;

echo "Testing enrollment relationships...\n";

try {
    $matricula = Matricula::with(['estudiante', 'grado', 'seccion'])->first();

    if ($matricula) {
        echo "Relationships work!\n";
        echo "Estudiante: " . ($matricula->estudiante ? $matricula->estudiante->nombres : 'null') . "\n";
        echo "Grado: " . ($matricula->grado ? $matricula->grado->nombre : 'null') . "\n";
        echo "Seccion: " . ($matricula->seccion ? $matricula->seccion->nombre : 'null') . "\n";
    } else {
        echo "No matriculas found\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "Testing payment generation...\n";

try {
    // Test the static methods
    $numero = Matricula::generarNumeroMatricula();
    echo "Generated matricula number: $numero\n";

    $esNuevo = Matricula::esEstudianteNuevo(1); // Test with estudiante_id = 1
    echo "Student 1 is new: " . ($esNuevo ? 'Yes' : 'No') . "\n";

} catch (Exception $e) {
    echo "Error in static methods: " . $e->getMessage() . "\n";
}
