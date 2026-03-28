<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\Persona;

echo "=== ACTUALIZANDO DATOS DE ESTUDIANTES ===\n";

// Nombres y apellidos realistas
$nombresMasculinos = ['Juan', 'Pedro', 'Carlos', 'Luis', 'Miguel', 'José', 'Antonio', 'Francisco', 'Javier', 'Manuel'];
$nombresFemeninos = ['María', 'Ana', 'Laura', 'Carmen', 'Isabel', 'Pilar', 'Dolores', 'Cristina', 'Mónica', 'Rosa'];
$apellidosPaternos = ['García', 'Rodríguez', 'González', 'Fernández', 'López', 'Martínez', 'Sánchez', 'Pérez', 'Martín', 'Ruiz'];
$apellidosMaternos = ['Gómez', 'Jiménez', 'Hernández', 'Díaz', 'Moreno', 'Álvarez', 'Romero', 'Navarro', 'Torres', 'Ramos'];

$estudiantes = Persona::where('dni', 'like', 'EST%')->get();
$contador = 0;

foreach ($estudiantes as $persona) {
    // Verificar si tiene datos placeholder
    if (str_contains($persona->apellidos, 'del ') || str_contains($persona->nombres, 'Estudiante ')) {
        $genero = collect(['M', 'F'])->random();
        $nombres = $genero === 'M' ? collect($nombresMasculinos)->random() : collect($nombresFemeninos)->random();
        $apellidoPaterno = collect($apellidosPaternos)->random();
        $apellidoMaterno = collect($apellidosMaternos)->random();

        $persona->update([
            'nombres' => $nombres,
            'apellidos' => $apellidoPaterno . ' ' . $apellidoMaterno,
            'genero' => $genero
        ]);

        echo "✓ Actualizado: {$persona->dni} - {$nombres} {$apellidoPaterno} {$apellidoMaterno}\n";
        $contador++;
    }
}

echo "\n=== RESUMEN ===\n";
echo "Estudiantes actualizados: {$contador}\n";
echo "Total estudiantes con DNI EST: " . $estudiantes->count() . "\n";

?>
