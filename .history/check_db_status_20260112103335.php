<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\InfCurso;
use App\Models\InfAsignatura;
use App\Models\InfEstudiante;
use App\Models\InfDocente;
use App\Models\Matricula;
use App\Models\InfPeriodosEvaluacion;
use App\Models\NotasFinalesPeriodo;
use App\Models\NotasFinalesAnuales;
use App\Models\Competencia;

echo "=== ESTADO ACTUAL DE LA BASE DE DATOS ===\n";

$tables = [
    'Cursos' => 'cursos',
    'Asignaturas' => 'asignaturas',
    'Estudiantes' => 'estudiantes',
    'Profesores' => 'profesores',
    'Matriculas' => 'matriculas',
    'Periodos Evaluacion' => 'periodosevaluacion',
    'Notas Periodo' => 'notasfinalesperiodo',
    'Notas Anuales' => 'notasfinalesanuales',
    'Competencias' => 'competencias',
    'Asistencias' => 'asistenciasasignatura'
];

foreach ($tables as $name => $table) {
    try {
        $count = DB::table($table)->count();
        echo "$name: $count\n";
    } catch (Exception $e) {
        echo "$name: ERROR - {$e->getMessage()}\n";
    }
}

echo "\n=== VERIFICANDO MODELOS ===\n";

$models = [
    'InfCurso' => InfCurso::class,
    'InfAsignatura' => InfAsignatura::class,
    'InfEstudiante' => InfEstudiante::class,
    'InfDocente' => InfDocente::class,
    'Matricula' => Matricula::class,
    'InfPeriodosEvaluacion' => InfPeriodosEvaluacion::class,
    'NotasFinalesPeriodo' => NotasFinalesPeriodo::class,
    'NotasFinalesAnuales' => NotasFinalesAnuales::class,
    'Competencia' => Competencia::class,
];

foreach ($models as $name => $model) {
    try {
        $count = $model::count();
        echo "$name: $count registros\n";
    } catch (Exception $e) {
        echo "$name: ERROR - {$e->getMessage()}\n";
    }
}
