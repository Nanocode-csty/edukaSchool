<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

// Verificar relaciones estudiante-representante para estudiante ID 1
$relaciones = DB::table('estudiante_representante')->where('estudiante_id', 1)->get();

echo "=== RELACIONES PARA ESTUDIANTE ID 1 ===\n";
if ($relaciones->count() > 0) {
    foreach ($relaciones as $rel) {
        echo "Representante ID: {$rel->representante_id}\n";
    }
} else {
    echo "No hay relaciones encontradas para el estudiante ID 1\n";
}

// Verificar todas las relaciones
echo "\n=== TODAS LAS RELACIONES ESTUDIANTE-REPRESENTANTE ===\n";
$todas = DB::table('estudiante_representante')->get();
echo "Total relaciones: " . $todas->count() . "\n";

foreach ($todas as $rel) {
    echo "Estudiante {$rel->estudiante_id} -> Representante {$rel->representante_id}\n";
}

// Verificar qué estudiantes tiene el representante autenticado
echo "\n=== ESTUDIANTES DEL REPRESENTANTE AUTENTICADO ===\n";
$user = auth()->user();
if ($user && $user->persona && $user->persona->representante) {
    $rep = $user->persona->representante;
    echo "Representante ID: {$rep->representante_id}\n";

    $estudiantes = DB::table('estudiante_representante')
        ->where('representante_id', $rep->representante_id)
        ->join('estudiantes', 'estudiante_representante.estudiante_id', '=', 'estudiantes.estudiante_id')
        ->join('personas', 'estudiantes.persona_id', '=', 'personas.id_persona')
        ->select('estudiantes.estudiante_id', 'personas.nombres', 'personas.apellidos')
        ->get();

    echo "Estudiantes asociados: " . $estudiantes->count() . "\n";
    foreach ($estudiantes as $est) {
        echo "  - ID {$est->estudiante_id}: {$est->nombres} {$est->apellidos}\n";
    }
} else {
    echo "No hay representante autenticado\n";
}
