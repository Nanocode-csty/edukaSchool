<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\Persona;

echo "=== CORRIGIENDO ESTUDIANTES RESTANTES CON 'DEL' EN APELLIDOS ===\n";

// Nombres y apellidos realistas
$nombresMasculinos = ['Juan', 'Pedro', 'Carlos', 'Luis', 'Miguel', 'José', 'Antonio', 'Francisco', 'Javier', 'Manuel', 'David', 'Alejandro', 'Daniel', 'Pablo', 'Sergio', 'Fernando', 'Jorge', 'Alberto', 'Rubén', 'Adrián'];
$nombresFemeninos = ['María', 'Ana', 'Laura', 'Carmen', 'Isabel', 'Pilar', 'Dolores', 'Cristina', 'Mónica', 'Rosa', 'Sofía', 'Lucía', 'Paula', 'Elena', 'Sara', 'Clara', 'Teresa', 'Julia', 'Patricia', 'Andrea'];
$apellidosPaternos = ['García', 'Rodríguez', 'González', 'Fernández', 'López', 'Martínez', 'Sánchez', 'Pérez', 'Martín', 'Ruiz', 'Hernández', 'Jiménez', 'Díaz', 'Moreno', 'Muñoz', 'Álvarez', 'Romero', 'Navarro', 'Torres', 'Ramos', 'Gil', 'Ramírez', 'Serrano', 'Blanco', 'Suárez'];
$apellidosMaternos = ['Gómez', 'Jiménez', 'Hernández', 'Díaz', 'Moreno', 'Álvarez', 'Romero', 'Navarro', 'Torres', 'Ramos', 'Gil', 'Ramírez', 'Serrano', 'Blanco', 'Suárez', 'Vega', 'Castro', 'Ortega', 'Delgado', 'Cabrera', 'Reyes', 'Nieto', 'Herrera', 'Medina', 'Cortés'];

// Buscar estudiantes que aún tengan "del" en los apellidos
$estudiantes = Persona::whereHas('estudiante')
    ->where('apellidos', 'like', '%del %')
    ->get();

$contador = 0;

echo "Estudiantes encontrados con 'del' en apellidos: " . $estudiantes->count() . "\n\n";

foreach ($estudiantes as $persona) {
    $genero = collect(['M', 'F'])->random();
    $nombres = $genero === 'M' ? collect($nombresMasculinos)->random() : collect($nombresFemeninos)->random();
    $apellidoPaterno = collect($apellidosPaternos)->random();
    $apellidoMaterno = collect($apellidosMaternos)->random();

    $persona->update([
        'nombres' => $nombres,
        'apellidos' => $apellidoPaterno . ' ' . $apellidoMaterno,
        'genero' => $genero
    ]);

    echo "✓ Corregido: {$persona->dni} - {$nombres} {$apellidoPaterno} {$apellidoMaterno} (antes: {$persona->nombres} {$persona->apellidos})\n";
    $contador++;
}

echo "\n=== RESUMEN ===\n";
echo "Estudiantes corregidos: {$contador}\n";

// Verificar que ya no queden estudiantes con "del" en apellidos
$restantes = Persona::whereHas('estudiante')
    ->where('apellidos', 'like', '%del %')
    ->count();

echo "Estudiantes restantes con 'del' en apellidos: {$restantes}\n";

// Verificar algunos estudiantes al azar para confirmar
echo "\n=== VERIFICACIÓN ===\n";
$muestra = Persona::whereHas('estudiante')->inRandomOrder()->take(10)->get();
foreach ($muestra as $persona) {
    echo "- {$persona->dni}: {$persona->nombres} {$persona->apellidos}\n";
}

echo "\n=== PROCESO COMPLETADO ===\n";

?>
