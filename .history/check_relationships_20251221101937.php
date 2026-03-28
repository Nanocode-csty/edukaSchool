<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    echo "=== ESTUDIANTE_REPRESENTANTE TABLE ===\n";
    $relations = DB::table('estudiante_representante')->get();
    foreach ($relations as $rel) {
        echo "Estudiante: {$rel->estudiante_id} -> Representante: {$rel->representante_id}\n";
    }

    echo "\n=== REPRESENTANTES TABLE ===\n";
    $representantes = DB::table('representantes')->get();
    foreach ($representantes as $rep) {
        echo "ID: {$rep->representante_id} -> Persona: {$rep->persona_id}\n";
    }

    echo "\n=== CURRENT USER (ID: 4) ===\n";
    $user = DB::table('usuarios')->where('usuario_id', 4)->first();
