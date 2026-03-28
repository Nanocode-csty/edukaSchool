<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\Persona;

echo "=== CORRIGIENDO TODOS LOS ESTUDIANTES CON DATOS PLACEHOLDER ===\n";

// Nombres y apellidos realistas
$nombresMasculinos = ['Juan', 'Pedro', 'Carlos', 'Luis', 'Miguel', 'José', 'Antonio', 'Francisco', 'Javier', 'Manuel', 'David', 'Alejandro', 'Daniel', 'Pablo', 'Sergio', 'Fernando', 'Jorge', 'Alberto', 'Rubén', 'Adrián'];
$nombresFemeninos = ['María', 'Ana', 'Laura', 'Carmen', 'Isabel', 'Pilar', 'Dolores', 'Cristina', 'Mónica', 'Rosa', 'Sofía', 'Lucía', 'Paula', 'Elena', 'Sara', 'Clara', 'Teresa', 'Julia', 'Patricia', 'Andrea'];
$apellidosPaternos = ['García', 'Rodríguez', 'González', 'Fernández', 'López', 'Martínez', 'Sánchez', 'Pérez', 'Martín', 'Ruiz', 'Hernández', 'Jiménez', 'Díaz', 'Moreno', 'Muñoz', 'Álvarez', 'Romero', 'Navarro', 'Torres', 'Ramos', 'Gil', 'Ramírez', 'Serrano', 'Blanco', 'Suárez'];
$apellidosMaternos = ['Gómez', 'Jiménez', 'Hernández', 'Díaz', 'Moreno', 'Álvarez', 'Romero', 'Navarro', 'Torres', 'Ramos', 'Gil', 'Ramírez', 'Serrano', 'Blanco', 'Suárez', 'Vega', 'Castro', 'Ortega', 'Delgado', 'Cabrera', 'Reyes', 'Nieto', 'Herrera', 'Medina', 'Cortés'];

// Obtener TODOS los estudiantes (no solo con DNI EST%)
$estudiantes = Persona::whereHas('estudiante')->get();
$contador = 0;

echo "Total de estudiantes encontrados: " . $estudiantes->count() . "\n\n";

foreach ($estudiantes as $persona) {
    // Verificar si tiene datos placeholder
    $needsUpdate = false;

    // Criterios para detectar datos placeholder
    if (str_contains(strtolower($persona->apellidos), 'del ')) {
        $needsUpdate = true;
    }

    if (str_contains(strtolower($persona->nombres), 'estudiante ')) {
        $needsUpdate = true;
    }

    if (preg_match('/[0-9°]/', $persona->apellidos)) {
        $needsUpdate = true;
    }

    if (strlen($persona->apellidos) < 4) {
        $needsUpdate = true;
    }

    // Verificar si los apellidos parecen ser grados/secciones
    if (preg_match('/[1-6]°|seccion|grado/i', $persona->apellidos)) {
        $needsUpdate = true;
    }

    if ($needsUpdate) {
        $genero = collect(['M', 'F'])->random();
        $nombres = $genero === 'M' ? collect($nombresMasculinos)->random() : collect($nombresFemeninos)->random();
        $apellidoPaterno = collect($apellidosPaternos)->random();
        $apellidoMaterno = collect($apellidosMaternos)->random();

        $persona->update([
            'nombres' => $nombres,
            'apellidos' => $apellidoPaterno . ' ' . $apellidoMaterno,
            'genero' => $genero
        ]);

        echo "✓ Actualizado: {$persona->dni} - {$nombres} {$apellidoPaterno} {$apellidoMaterno} (antes: {$persona->nombres} {$persona->apellidos})\n";
        $contador++;
    }
}

echo "\n=== RESUMEN ===\n";
echo "Estudiantes corregidos: {$contador}\n";
echo "Total estudiantes procesados: " . $estudiantes->count() . "\n";

// Verificar algunos estudiantes al azar para confirmar
echo "\n=== VERIFICACIÓN ===\n";
$muestra = Persona::whereHas('estudiante')->inRandomOrder()->take(5)->get();
foreach ($muestra as $persona) {
    echo "- {$persona->dni}: {$persona->nombres} {$persona->apellidos}\n";
}

echo "\n=== PROCESO COMPLETADO ===\n";

?>
