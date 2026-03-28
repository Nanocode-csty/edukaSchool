<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Artisan;
use App\Models\PeriodoMatricula;

echo "=== CONFIGURACIÓN DE PERÍODOS ACADÉMICOS ===\n";

// Ejecutar migración
echo "1. Ejecutando migración...\n";
Artisan::call('migrate --path=database/migrations/2026_01_12_000001_create_periodos_matricula_table.php');
echo "   ✓ Migración completada\n";

// Ejecutar seeder
echo "2. Ejecutando seeder de períodos...\n";
Artisan::call('db:seed --class=PeriodosMatriculaSeeder');
echo "   ✓ Seeder completado\n";

// Verificar períodos creados
echo "3. Verificando períodos creados...\n";
$periodos = PeriodoMatricula::getPeriodosAnioActual();

echo "Períodos del año " . date('Y') . ":\n";
foreach ($periodos as $periodo) {
    $estado = $periodo->estaActivo() ? '🟢 ACTIVO' : '⚪ INACTIVO';
    echo "- {$periodo->nombre}: {$periodo->fecha_inicio->format('d/m/Y')} - {$periodo->fecha_fin->format('d/m/Y')} [{$estado}]\n";
}

// Verificar período actual
echo "\n4. Período actual:\n";
$periodoActual = PeriodoMatricula::getPeriodoActual();

if ($periodoActual) {
    echo "✓ {$periodoActual->nombre} ({$periodoActual->tipo_periodo})\n";
    echo "  Desde: {$periodoActual->fecha_inicio->format('d/m/Y')}\n";
    echo "  Hasta: {$periodoActual->fecha_fin->format('d/m/Y')}\n";
    echo "  Estado: " . ($periodoActual->estaActivo() ? 'ACTIVO' : 'INACTIVO') . "\n";
} else {
    echo "⚠️ No hay período activo actualmente\n";
}

echo "\n🎉 CONFIGURACIÓN COMPLETADA\n";
echo "Los períodos académicos están listos para usar.\n";

?>
