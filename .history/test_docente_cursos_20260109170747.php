<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\CursoAsignatura;

echo "Testing docente courses query...\n";

$cursos = CursoAsignatura::with(['curso.grado', 'curso.seccion'])
    ->where('profesor_id', 27)
    ->whereHas('curso', function($q) {
        $q->whereIn('estado', ['Activo', 'En Curso']);
    })
    ->get()
    ->map(function($cursoAsignatura) {
        return $cursoAsignatura->curso;
    })
    ->unique('id');

echo "Cursos encontrados: " . $cursos->count() . "\n";

foreach ($cursos as $curso) {
    echo "- " . $curso->grado->nombre . " " . $curso->seccion->nombre . "\n";
}