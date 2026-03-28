<?php

use App\Models\InfCurso;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

// Simulate the logic in NotasController::getAsignaturasPorCurso
// We'll test with course ID 15 which we know was seeded
$curso_id = 15; 

echo "Testing getAsignaturasPorCurso logic for Curso ID: $curso_id\n";

$query = DB::table('cursoasignaturas')
    ->join('asignaturas', 'cursoasignaturas.asignatura_id', '=', 'asignaturas.asignatura_id')
    ->where('cursoasignaturas.curso_id', $curso_id)
    ->select('asignaturas.asignatura_id', 'asignaturas.nombre', 'asignaturas.codigo');

// Simulate Admin (no profesor_id filter)
$asignaturas = $query->orderBy('asignaturas.nombre')->get();

echo "Found " . $asignaturas->count() . " subjects.\n";

if ($asignaturas->count() > 0) {
    echo "First subject: " . $asignaturas->first()->nombre . " (ID: " . $asignaturas->first()->asignatura_id . ")\n";
} else {
    echo "NO SUBJECTS FOUND! Query might be wrong.\n";
}

// Check raw table content
$rawCount = DB::table('cursoasignaturas')->where('curso_id', $curso_id)->count();
echo "Raw count in 'cursoasignaturas' table for curso_id $curso_id: $rawCount\n";
