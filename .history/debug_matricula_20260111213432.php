<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Matricula;

echo "=== DEBUGGING MATRICULA QUERY ===\n";

try {
    // Consulta básica sin relaciones
    echo "1. Consulta básica sin relaciones...\n";
    $basicQuery = Matricula::where('numero_matricula', 'like', '%%')
        ->where('anio_academico', date('Y'))
        ->where('estado', 'Pre-inscrito');
    $count = $basicQuery->count();
    echo "   ✓ Básica: {$count} registros\n";

    // Agregar ordenamiento
    echo "2. Agregando ordenamiento...\n";
    $withOrder = $basicQuery->orderBy('fecha_matricula', 'desc');
    $count = $withOrder->count();
    echo "   ✓ Con ordenamiento: {$count} registros\n";

    // Agregar paginación
    echo "3. Agregando paginación...\n";
    $paginated = $withOrder->paginate(10);
    echo "   ✓ Con paginación: {$paginated->total()} registros\n";

    // Probar relaciones individualmente
    echo "4. Probando relaciones...\n";

    echo "   - estudiante: ";
    $withEstudiante = Matricula::with(['estudiante'])->where('estado', 'Pre-inscrito')->count();
    echo "{$withEstudiante} OK\n";

    echo "   - estudiante.persona: ";
    $withEstudiantePersona = Matricula::with(['estudiante.persona'])->where('estado', 'Pre-inscrito')->count();
    echo "{$withEstudiantePersona} OK\n";

    echo "   - grado: ";
    $withGrado = Matricula::with(['grado'])->where('estado', 'Pre-inscrito')->count();
    echo "{$withGrado} OK\n";

    echo "   - seccion: ";
    $withSeccion = Matricula::with(['seccion'])->where('estado', 'Pre-inscrito')->count();
    echo "{$withSeccion} OK\n";

    // Consulta completa
    echo "5. Consulta completa...\n";
    $complete = Matricula::with(['estudiante.persona', 'grado', 'seccion'])
        ->where('numero_matricula', 'like', '%%')
        ->where('anio_academico', date('Y'))
        ->where('estado', 'Pre-inscrito')
        ->orderBy('fecha_matricula', 'desc')
        ->paginate(10);
    echo "   ✓ Consulta completa exitosa: {$complete->total()} registros\n";

    echo "\n🎉 TODAS LAS CONSULTAS FUNCIONAN CORRECTAMENTE\n";

} catch (\Exception $e) {
    echo "\n❌ ERROR ENCONTRADO:\n";
    echo "Mensaje: " . $e->getMessage() . "\n";
    echo "Línea: " . $e->getLine() . "\n";
    echo "Archivo: " . $e->getFile() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== FIN DEBUG ===\n";

?>
