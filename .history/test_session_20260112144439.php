<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\SesionClase;

$sesion = SesionClase::with(['cursoAsignatura.curso.matriculas.estudiante.persona'])->find(100);

if (!$sesion) {
    echo "Sesión 100 no encontrada\n";
    exit;
}

echo "Sesión ID: " . $sesion->id . "\n";
echo "Curso: " . $sesion->cursoAsignatura->curso->grado->nombre . " " . $sesion->cursoAsignatura->curso->seccion->nombre . "\n";
echo "Asignatura: " . $sesion->cursoAsignatura->asignatura->nombre . "\n";
echo "Estudiantes matriculados:\n";

$estudiantes = $sesion->cursoAsignatura->curso->matriculas()->with('estudiante.persona')->where('estado', 'Matriculado')->get();

foreach($estudiantes as $matricula) {
    echo "- " . $matricula->estudiante->persona->nombres . " " . $matricula->estudiante->persona->apellidos . " (DNI: " . $matricula->estudiante->persona->dni . ")\n";
}

echo "\nTotal estudiantes: " . $estudiantes->count() . "\n";
