<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== PERÍODOS POR AÑO LECTIVO ===\n\n";

$periodos = DB::table('periodos_matricula')
    ->select('ano_lectivo_id', 'tipo_periodo', 'estado')
    ->get();

$porAnio = [];
foreach ($periodos as $periodo) {
    $anioId = $periodo->ano_lectivo_id;
    if (!isset($porAnio[$anioId])) {
        $porAnio[$anioId] = [
            'tipos' => [],
            'activos' => 0
        ];
    }

    $porAnio[$anioId]['tipos'][] = $periodo->tipo_periodo;
    if ($periodo->estado === 'ACTIVO') {
        $porAnio[$anioId]['activos']++;
    }
}

echo "Año Lectivo\tTipos de Período\tPeríodos Activos\t¿Debe Aparecer?\n";
echo str_repeat("-", 80) . "\n";

foreach ($porAnio as $anioId => $data) {
    $tiposUnicos = array_unique($data['tipos']);
    $totalTiposCreados = count($tiposUnicos);
    $tiposDisponibles = 5; // PREINSCRIPCION, INSCRIPCION, MATRICULA, ACADEMICO, CIERRE
    $debeAparecer = $totalTiposCreados < $tiposDisponibles; // Nueva lógica

    echo $anioId . "\t\t" . implode(',', $tiposUnicos) . "\t\t" . $data['activos'] . "\t\t" .
         ($debeAparecer ? 'SÍ' : 'NO') . "\t\t(" . $totalTiposCreados . "/" . $tiposDisponibles . " tipos)\n";
}

echo "\n=== FIN ===";
