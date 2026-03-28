<?php

use App\Models\InfPeriodosEvaluacion;
use App\Models\InfAnioLectivo;

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

echo "Checking Periods...\n";

$anios = InfAnioLectivo::all();

foreach($anios as $anio) {
    echo "Anio: {$anio->nombre} (ID: {$anio->ano_lectivo_id}) - Estado: {$anio->estado}\n";
    $periodos = InfPeriodosEvaluacion::where('ano_lectivo_id', $anio->ano_lectivo_id)->get();
    
    if($periodos->isEmpty()) {
        echo "  No periods found.\n";
    } else {
        foreach($periodos as $p) {
            echo "  Periodo: {$p->nombre} (ID: {$p->periodo_id}) - Estado: {$p->estado} - Inicio: {$p->fecha_inicio} - Fin: {$p->fecha_fin}\n";
        }
    }
}
