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
    echo "Persona ID: {$user->persona_id}\n";

    echo "\n=== TESTING RELATIONSHIP QUERY ===\n";
    $representante = DB::table('representantes')->where('persona_id', 3)->first();
    if ($representante) {
        echo "Found representante ID: {$representante->representante_id}\n";

        $estudiantes = DB::table('estudiante_representante')
            ->join('estudiantes', 'estudiante_representante.estudiante_id', '=', 'estudiantes.estudiante_id')
            ->where('estudiante_representante.representante_id', $representante->representante_id)
            ->select('estudiantes.*')
            ->get();

        echo "Found " . $estudiantes->count() . " students for representante\n";
        foreach ($estudiantes as $est) {
            echo "- {$est->nombres} {$est->apellidos} (ID: {$est->estudiante_id})\n";
        }
    } else {
        echo "No representante found for persona_id = 3\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
