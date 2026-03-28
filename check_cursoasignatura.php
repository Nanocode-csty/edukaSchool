<?php
use App\Models\CursoAsignatura;
use Illuminate\Support\Facades\DB;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

echo "Total CursoAsignaturas: " . CursoAsignatura::count() . "\n";
$first = CursoAsignatura::first();
if ($first) {
    echo "First Entry: " . json_encode($first) . "\n";
    // Check relations
    echo "Curso: " . ($first->curso ? 'OK' : 'NULL') . "\n";
    echo "Asignatura: " . ($first->asignatura ? 'OK' : 'NULL') . "\n";
} else {
    echo "No entries found.\n";
}
