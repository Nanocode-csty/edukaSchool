<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Persona;

echo "=== CORRECCIÓN DEFINITIVA DE TODOS LOS ESTUDIANTES ===\n";

// Nombres y apellidos realistas
$nombresMasculinos = ['Juan', 'Pedro', 'Carlos', 'Luis', 'Miguel', 'José', 'Antonio', 'Francisco', 'Javier', 'Manuel', 'David', 'Alejandro', 'Daniel', 'Pablo', 'Sergio', 'Fernando', 'Jorge', 'Alberto', 'Rubén', 'Adrián'];
$nombresFemeninos = ['María', 'Ana', 'Laura', 'Carmen', 'Isabel', 'Pilar', 'Dolores', 'Cristina', 'Mónica', 'Rosa', 'Sofía', 'Lucía', 'Paula', 'Elena', 'Sara', 'Clara', 'Teresa', 'Julia', 'Patricia', 'Andrea'];
$apellidosPaternos = ['García', 'Rodríguez', 'González', 'Fernández', 'López', 'Martínez', 'Sánchez', 'Pérez', 'Martín', 'Ruiz', 'Hernández', 'Jiménez', 'Díaz', 'Moreno', 'Muñoz', 'Álvarez', 'Romero', 'Navarro', 'Torres', 'Ramos', 'Gil', 'Ramírez', 'Serrano', 'Blanco', 'Suárez'];
$apellidosMaternos = ['Gómez', 'Jiménez', 'Hernández', 'Díaz', 'Moreno', 'Álvarez', 'Romero', 'Navarro', 'Torres', 'Ramos', 'Gil', 'Ramírez', 'Serrano', 'Blanco', 'Suárez', 'Vega', 'Castro', 'Ortega', 'Delgado', 'Cabrera', 'Reyes', 'Nieto', 'Herrera', 'Medina', 'Cortés'];

// Obtener TODOS los estudiantes que tienen datos placeholder
$estudiantes = Persona::whereHas('estudiante')
    ->where(function($query) {
        $query->where('apellidos', 'like', '%del %')
              ->orWhere('nombres', 'like', '%Estudiante%')
              ->orWhere('apellidos', 'like', '%[0-9]°%');
    })
    ->get();

$contador = 0;

echo "Encontrados " . $estudiantes->count() . " estudiantes con datos placeholder\n\n";

foreach ($estudiantes as $persona) {
    $genero = collect(['M', 'F'])->random();
    $nombres = $genero === 'M' ? collect($nombresMasculinos)->random() : collect($nombresFemeninos)->random();
    $apellidoPaterno = collect($apellidosPaternos)->random();
    $apellidoMaterno = collect($apellidosMaternos)->random();

    $antiguoNombre = $persona->nombres . ' ' . $persona->apellidos;
    $nuevoNombre = $nombres . ' ' . $apellidoPaterno . ' ' . $apellidoMaterno;

    $persona->update([
        'nombres' => $nombres,
        'apellidos' => $apellidoPaterno . ' ' . $apellidoMaterno,
        'genero' => $genero
    ]);

    echo "✓ {$persona->dni}: '{$antiguoNombre}' → '{$nuevoNombre}'\n";
    $contador++;
}

echo "\n=== RESULTADOS ===\n";
echo "Estudiantes corregidos: {$contador}\n";

// Verificar que ya no queden estudiantes con datos placeholder
$restantes = Persona::whereHas('estudiante')
    ->where(function($query) {
        $query->where('apellidos', 'like', '%del %')
              ->orWhere('nombres', 'like', '%Estudiante%')
              ->orWhere('apellidos', 'like', '%[0-9]°%');
    })
    ->count();

echo "Estudiantes restantes con datos placeholder: {$restantes}\n";

// Verificar algunos estudiantes al azar
echo "\n=== MUESTRA DE ESTUDIANTES CORREGIDOS ===\n";
$muestra = Persona::whereHas('estudiante')->inRandomOrder()->take(10)->get();
foreach ($muestra as $persona) {
    echo "- {$persona->dni}: {$persona->nombres} {$persona->apellidos}\n";
}

if ($restantes == 0) {
    echo "\n🎉 ¡CORRECCIÓN COMPLETADA EXITOSAMENTE! 🎉\n";
    echo "Todos los estudiantes ahora tienen nombres y apellidos reales.\n";
} else {
    echo "\n⚠️ AÚN QUEDAN ESTUDIANTES POR CORREGIR\n";
}

echo "\n=== FIN DEL PROCESO ===\n";

?>
