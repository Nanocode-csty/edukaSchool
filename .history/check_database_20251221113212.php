<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    echo "=== CHECKING GRADOS TABLE ===\n";
    $grados = DB::table('grados')->get();
    foreach ($grados as $grado) {
        echo "Grado: {$grado->nombre} | grado_id: '{$grado->grado_id}' | nivel_id: '{$grado->nivel_id}'\n";
    }

    echo "\n=== CHECKING SECCIONES TABLE ===\n";
    $secciones = DB::table('secciones')->get();
    foreach ($secciones as $seccion) {
        echo "Seccion: {$seccion->nombre} | seccion_id: '{$seccion->seccion_id}'\n";
    }

    echo "\n=== CHECKING MATRICULAS TABLE ===\n";
    $matriculas = DB::table('matriculas')->limit(5)->get();
    foreach ($matriculas as $matricula) {
        echo "Matricula ID: {$matricula->matricula_id} | estudiante_id: {$matricula->estudiante_id} | idGrado: '{$matricula->idGrado}' | idSeccion: '{$matricula->idSeccion}'\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
