<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\PeriodoMatricula;
use App\Models\InfAnioLectivo;

echo "=== VERIFICACIÓN DE PERÍODOS ===\n\n";

$periodos = PeriodoMatricula::with('anoLectivo')->get();

echo "Períodos existentes:\n";
echo str_pad("Código", 20) . str_pad("Tipo", 15) . str_pad("Estado", 10) . str_pad("Fecha Inicio", 15) . str_pad("Fecha Fin", 15) . "\n";
echo str_repeat("-", 75) . "\n";

foreach ($periodos as $periodo) {
    echo str_pad($periodo->codigo, 20) .
         str_pad($periodo->tipo_periodo, 15) .
         str_pad($periodo->estado, 10) .
         str_pad($periodo->fecha_inicio->format('Y-m-d'), 15) .
         str_pad($periodo->fecha_fin->format('Y-m-d'), 15) . "\n";
}

echo "\n=== PRUEBA DE VALIDACIONES ===\n\n";

// Obtener año lectivo 2026
$anio2026 = InfAnioLectivo::where('nombre', 'like', '2026%')->first();
if ($anio2026) {
    echo "Probando validación de consistencia para año lectivo: {$anio2026->nombre}\n\n";

    // Caso 1: Intentar crear un segundo período PREINSCRIPCION activo (debería fallar)
    echo "Caso 1: Intentar crear segundo PREINSCRIPCION activo\n";
    $controller = new \App\Http\Controllers\PeriodosController();
    $conflicts = $controller->validarConsistenciaPeriodo('PREINSCRIPCION', '2026-01-01', '2026-02-28', $anio2026->ano_lectivo_id);
    if (empty($conflicts)) {
        echo "❌ ERROR: Debería haber detectado conflicto de PREINSCRIPCION duplicado\n";
    } else {
        echo "✅ CORRECTO: Detectó conflicto: " . implode(', ', $conflicts) . "\n";
    }

    // Caso 2: Intentar crear INSCRIPCION que empiece antes de que termine PREINSCRIPCION (debería fallar)
    echo "\nCaso 2: Intentar crear INSCRIPCION que se solape con PREINSCRIPCION\n";
    $conflicts = $controller->validarConsistenciaPeriodo('INSCRIPCION', '2026-01-15', '2026-03-15', $anio2026->ano_lectivo_id);
    if (empty($conflicts)) {
        echo "❌ ERROR: Debería haber detectado solapamiento\n";
    } else {
        echo "✅ CORRECTO: Detectó conflicto: " . implode(', ', $conflicts) . "\n";
    }

    // Caso 3: Crear un período válido (debería pasar)
    echo "\nCaso 3: Intentar crear período válido (CIERRE adicional)\n";
    $conflicts = $controller->validarConsistenciaPeriodo('CIERRE', '2026-12-01', '2026-12-15', $anio2026->ano_lectivo_id);
    if (empty($conflicts)) {
        echo "✅ CORRECTO: No detectó conflictos para período válido\n";
    } else {
        echo "❌ ERROR: Detectó conflicto innecesario: " . implode(', ', $conflicts) . "\n";
    }
}

echo "\n=== FIN DE PRUEBAS ===\n";
