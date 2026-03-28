<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== COLUMNAS DE LA TABLA PERSONAS ===\n";

$columns = DB::select("DESCRIBE personas");

foreach ($columns as $column) {
    echo "{$column->Field}: {$column->Type}\n";
}

echo "\n=== VALORES DE UNA PERSONA ESPECÍFICA ===\n";

$persona = DB::select("SELECT * FROM personas WHERE dni = 'EST0014' LIMIT 1");

if ($persona) {
    $persona = $persona[0];
    echo "DNI: {$persona->dni}\n";
    echo "Nombres: {$persona->nombres}\n";
    echo "Apellidos: {$persona->apellidos}\n";
    echo "Apellido paterno: " . ($persona->apellido_paterno ?? 'NULL') . "\n";
    echo "Apellido materno: " . ($persona->apellido_materno ?? 'NULL') . "\n";
}

echo "\n=== FIN ===\n";

?>
