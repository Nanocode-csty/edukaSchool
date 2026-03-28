<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Persona;

echo "=== PRUEBA DE ACTUALIZACIÓN ===\n";

// Buscar un estudiante específico
$persona = Persona::where('dni', 'EST0014')->first();

if (!$persona) {
    echo "Estudiante no encontrado\n";
    exit;
}

echo "Estudiante encontrado: {$persona->dni}\n";
echo "Nombre actual: {$persona->nombres} {$persona->apellidos}\n";

// Intentar actualizar
$nuevoNombre = 'TestNombre';
$nuevoApellido = 'TestApellido';

echo "Actualizando a: {$nuevoNombre} {$nuevoApellido}\n";

$resultado = $persona->update([
    'nombres' => $nuevoNombre,
    'apellidos' => $nuevoApellido
]);

echo "Resultado del update: " . ($resultado ? 'true' : 'false') . "\n";

// Verificar el cambio
$personaActualizada = Persona::where('dni', 'EST0014')->first();
echo "Nombre después del update: {$personaActualizada->nombres} {$personaActualizada->apellidos}\n";

// Verificar si hay algún problema con el modelo
echo "Modelo: " . get_class($persona) . "\n";
echo "Tabla: " . $persona->getTable() . "\n";
echo "Primary Key: " . $persona->getKey() . "\n";

echo "\n=== FIN DE PRUEBA ===\n";

?>
