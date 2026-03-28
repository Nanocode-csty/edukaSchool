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

    echo "\n=== TESTING REVERSE RELATIONSHIP ===\n";

