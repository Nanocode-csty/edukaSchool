<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== AÑOS LECTIVOS EXISTENTES ===\n\n";

$anios = DB::table('inf_anio_lectivo')->get();

echo "ID\tNombre\t\tFecha Inicio\tFecha Fin\tEstado\t\tDescripción\n";
echo str_repeat("-", 100) . "\n";

foreach ($anios as $anio) {
    echo $anio->ano_lectivo_id . "\t" .
         str_pad($anio->nombre, 15) . "\t" .
         $anio->fecha_inicio . "\t" .
         $anio->fecha_fin . "\t" .
         str_pad($anio->estado, 10) . "\t" .
         ($anio->descripcion ?? 'N/A') . "\n";
}

echo "\n=== FIN ===";
