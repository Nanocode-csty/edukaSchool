<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Matricula;

echo "=== ESTUDIANTES EXISTENTES ===\n";

$matriculas = Matricula::with(['estudiante.persona', 'grado', 'seccion'])->get();

foreach ($matriculas as $m) {
    echo $m->numero_matricula . "\t" .
         $m->estudiante->persona->dni . "\t" .
         $m->estudiante->persona->nombres . " " . $m->estudiante->persona->apellidos . "\t" .
         $m->estado . "\t" .
         $m->anio_academico . "\t" .
         $m->fecha_matricula->format('d/m/Y') . "\n";
}

