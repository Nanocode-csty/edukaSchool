<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$docente = \App\Models\InfDocente::find(27);
echo 'Docente ID 27: ' . ($docente ? 'Existe' : 'No existe') . PHP_EOL;

if ($docente) {
    echo 'Nombre: ' . ($docente->persona ? $docente->persona->nombres . ' ' . $docente->persona->apellidos : 'Sin persona') . PHP_EOL;
    echo 'Usuario ID: ' . ($docente->persona && $docente->persona->usuario ? $docente->persona->usuario->usuario_id : 'Sin usuario') . PHP_EOL;

    $cursosAsignados = \App\Models\CursoAsignatura::where('profesor_id', 27)->count();
    echo 'Cursos asignados: ' . $cursosAsignados . PHP_EOL;

    $cursosActivos = \App\Models\CursoAsignatura::where('profesor_id', 27)
        ->whereHas('curso', function($q) {
            $q->where('estado', 'Activo');
        })->count();
    echo 'Cursos asignados ACTIVOS: ' . $cursosActivos . PHP_EOL;

    if ($cursosAsignados > 0) {
        $cursos = \App\Models\CursoAsignatura::with(['curso.grado', 'curso.seccion', 'asignatura'])
            ->where('profesor_id', 27)
            ->get();

        echo PHP_EOL . 'Detalle de cursos asignados:' . PHP_EOL;
        foreach ($cursos as $index => $curso) {
            echo ($index + 1) . '. ' . $curso->asignatura->nombre . ' en ' . $curso->curso->grado->nombre . ' ' . $curso->curso->seccion->nombre;
            echo ' - Estado curso: ' . $curso->curso->estado . PHP_EOL;
        }

        // Verificar cuáles pasan el filtro de activos
        echo PHP_EOL . 'Cursos que pasan filtro de activos:' . PHP_EOL;
        $cursosFiltrados = $cursos->filter(function($curso) {
            return $curso->curso->estado === 'Activo';
        });

        foreach ($cursosFiltrados as $index => $curso) {
            echo ($index + 1) . '. ' . $curso->asignatura->nombre . ' en ' . $curso->curso->grado->nombre . ' ' . $curso->curso->seccion->nombre . PHP_EOL;
        }

        // Obtener los cursos únicos como hace el método
        $cursosUnicos = $cursosFiltrados->map(function($cursoAsignatura) {
            return $cursoAsignatura->curso;
        })->unique('id');

        echo PHP_EOL . 'Cursos únicos para el select: ' . $cursosUnicos->count() . PHP_EOL;
        foreach ($cursosUnicos as $curso) {
            echo '- ' . $curso->grado->nombre . ' ' . $curso->seccion->nombre . ' (ID: ' . $curso->curso_id . ')' . PHP_EOL;
        }
    }
}