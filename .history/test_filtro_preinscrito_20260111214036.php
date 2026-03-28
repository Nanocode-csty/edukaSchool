<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Matricula;

echo "=== PRUEBA DEL FILTRO PRE-INSCRITO ===\n";

// Simular la consulta del controlador
$matriculaQuery = Matricula::with(['estudiante.persona', 'grado', 'seccion'])
    ->where('numero_matricula', 'like', '%%')
    ->where('anio_academico', date('Y'));

// Aplicar filtro pre-inscrito (como en el controlador)
$matriculaQuery->where('estado', 'Pre-inscrito')
    ->where(function($query) {
        $query->where('anio_academico', date('Y'))
              ->orWhere('anio_academico', date('Y') - 1);
    });

$resultado = $matriculaQuery->paginate(10);

echo "Resultados encontrados: {$resultado->total()}\n";

if ($resultado->total() > 0) {
    echo "\nMatrículas encontradas:\n";
    foreach ($resultado->items() as $matricula) {
        echo "- {$matricula->numero_matricula}: {$matricula->estado} (Año: {$matricula->anio_academico})\n";
    }
}

echo "\n=== FIN DE PRUEBA ===\n";

?>
