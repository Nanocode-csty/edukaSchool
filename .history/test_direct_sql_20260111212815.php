<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== PRUEBA DE ACTUALIZACIÓN DIRECTA CON SQL ===\n";

// Verificar valores iniciales
$personaInicial = DB::select("SELECT * FROM personas WHERE dni = 'EST0014' LIMIT 1");
if ($personaInicial) {
    $personaInicial = $personaInicial[0];
    echo "Valores iniciales:\n";
    echo "DNI: {$personaInicial->dni}\n";
    echo "Nombres: {$personaInicial->nombres}\n";
    echo "Apellidos: {$personaInicial->apellidos}\n\n";
}

// Intentar actualizar directamente con SQL
echo "Actualizando con SQL directo...\n";
$resultado = DB::update("UPDATE personas SET apellidos = 'TestApellidoSQL' WHERE dni = 'EST0014'");

echo "Filas afectadas: {$resultado}\n";

// Verificar valores después de la actualización
$personaFinal = DB::select("SELECT * FROM personas WHERE dni = 'EST0014' LIMIT 1");
if ($personaFinal) {
    $personaFinal = $personaFinal[0];
    echo "\nValores finales:\n";
    echo "DNI: {$personaFinal->dni}\n";
    echo "Nombres: {$personaFinal->nombres}\n";
    echo "Apellidos: {$personaFinal->apellidos}\n";
}

echo "\n=== FIN DE PRUEBA ===\n";

?>
