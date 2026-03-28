<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "=== COMPLETANDO MIGRACIÓN DE CONFIGURACIÓN ===\n\n";

// Verificar columnas existentes
$columns = Schema::getColumnListing('periodos_matricula');
echo "Columnas actuales en periodos_matricula:\n";
foreach ($columns as $col) {
    echo "- $col\n";
}
echo "\n";

// Si existe la columna configuracion, eliminarla
if (Schema::hasColumn('periodos_matricula', 'configuracion')) {
    echo "Eliminando columna 'configuracion'...\n";
    Schema::table('periodos_matricula', function($table) {
        $table->dropColumn('configuracion');
    });
    echo "✅ Columna 'configuracion' eliminada\n\n";
} else {
    echo "ℹ️  Columna 'configuracion' ya no existe\n\n";
}

// Verificar que las columnas booleanas existen
$booleanColumns = ['permite_preinscripcion', 'permite_inscripcion', 'permite_matricula', 'clases_activas'];
$missingColumns = [];

foreach ($booleanColumns as $col) {
    if (!Schema::hasColumn('periodos_matricula', $col)) {
        $missingColumns[] = $col;
    }
}

if (!empty($missingColumns)) {
    echo "❌ Faltan las siguientes columnas booleanas:\n";
    foreach ($missingColumns as $col) {
        echo "- $col\n";
    }
} else {
    echo "✅ Todas las columnas booleanas existen\n\n";

    // Configurar valores por defecto según tipo de período
    echo "Configurando valores por defecto...\n";

    DB::statement("
        UPDATE periodos_matricula
        SET permite_preinscripcion = true
        WHERE tipo_periodo = 'PREINSCRIPCION' AND permite_preinscripcion = false
    ");

    DB::statement("
        UPDATE periodos_matricula
        SET permite_inscripcion = true
        WHERE tipo_periodo = 'INSCRIPCION' AND permite_inscripcion = false
    ");

    DB::statement("
        UPDATE periodos_matricula
        SET permite_matricula = true
        WHERE tipo_periodo = 'MATRICULA' AND permite_matricula = false
    ");

    DB::statement("
        UPDATE periodos_matricula
        SET clases_activas = true
        WHERE tipo_periodo = 'ACADEMICO' AND clases_activas = false
    ");

    echo "✅ Valores por defecto configurados\n\n";
}

// Verificar datos de muestra
echo "Verificando datos de muestra...\n";
$sample = DB::table('periodos_matricula')->select('tipo_periodo', 'permite_preinscripcion', 'permite_inscripcion', 'permite_matricula', 'clases_activas')->first();
if ($sample) {
    echo "Ejemplo de registro:\n";
    echo "- Tipo: {$sample->tipo_periodo}\n";
    echo "- Pre-inscripciones: " . ($sample->permite_preinscripcion ? '✅' : '❌') . "\n";
    echo "- Inscripciones: " . ($sample->permite_inscripcion ? '✅' : '❌') . "\n";
    echo "- Matrículas: " . ($sample->permite_matricula ? '✅' : '❌') . "\n";
    echo "- Clases activas: " . ($sample->clases_activas ? '✅' : '❌') . "\n";
}

echo "\n=== MIGRACIÓN COMPLETADA ===\n";
