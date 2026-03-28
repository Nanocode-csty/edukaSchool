<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Persona;

echo "=== VERIFICANDO ESTUDIANTES MÁS RECIENTES ===\n";

// Obtener estudiantes con DNIs más altos (más recientes)
$estudiantesRecientes = Persona::where('dni', 'like', 'EST%')
    ->orderBy('dni', 'desc')
    ->take(10)
    ->get();

echo "Estudiantes más recientes (DNIs más altos):\n";
foreach ($estudiantesRecientes as $estudiante) {
    echo "- {$estudiante->dni}: {$estudiante->nombres} {$estudiante->apellidos}\n";
}

echo "\n=== VERIFICACIÓN DE DATOS PLACEHOLDER ===\n";

// Contar estudiantes con datos placeholder
$totalEstudiantes = Persona::where('dni', 'like', 'EST%')->count();
$conDel = Persona::where('dni', 'like', 'EST%')->where('apellidos', 'like', '%del %')->count();
$conEstudiante = Persona::where('dni', 'like', 'EST%')->where('nombres', 'like', '%Estudiante%')->count();

echo "Total estudiantes con DNI EST: {$totalEstudiantes}\n";
echo "Con 'del' en apellidos: {$conDel}\n";
echo "Con 'Estudiante' en nombres: {$conEstudiante}\n";

if ($conDel > 0 || $conEstudiante > 0) {
    echo "\n⚠️ AÚN HAY ESTUDIANTES CON DATOS PLACEHOLDER\n";
} else {
    echo "\n✅ TODOS LOS ESTUDIANTES TIENEN NOMBRES REALES\n";
}

?>
