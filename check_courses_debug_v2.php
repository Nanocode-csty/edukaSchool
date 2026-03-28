<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\InfCurso;
use Illuminate\Support\Facades\DB;

echo "Checking Courses...\n";

try {
    $count = InfCurso::count();
    echo "Total courses in DB: $count\n";

    $active = InfCurso::where('estado', '<>', 'Finalizado')->get();
    echo "Active courses count: " . $active->count() . "\n";

    foreach($active->take(5) as $c) {
        $gradoName = $c->grado ? $c->grado->nombre : 'No Grado';
        $nivelName = $c->grado && $c->grado->nivel ? $c->grado->nivel->nombre : 'No Niv';
        $seccionName = $c->seccion ? $c->seccion->nombre : 'No Sec';
        $anoName = $c->anoLectivo ? $c->anoLectivo->nombre : 'No Ano';
        
        echo "ID: {$c->curso_id} | Grado: $gradoName | Nivel: $nivelName | Sec: $seccionName | Ano: $anoName\n";
    }

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
