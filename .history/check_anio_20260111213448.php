<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Matricula;

echo "=== VERIFICANDO AÑO ACADÉMICO ===\n";

$matricula = Matricula::where('estado', 'Pre-inscrito')->first();

if ($matricula) {
    echo "Matrícula ID: {$matricula->matricula_id}\n";
    echo "Número: {$matricula->numero_matricula}\n";
    echo "Estado: {$matricula->estado}\n";
    echo "Año académico: {$matricula->anio_academico}\n";
    echo "Año actual: " . date('Y') . "\n";
    echo "Coincide: " . ($matricula->anio_academico == date('Y') ? 'SÍ' : 'NO') . "\n";
} else {
    echo "No se encontró matrícula Pre-inscrita\n";
}

echo "\n=== FIN ===\n";

?>
