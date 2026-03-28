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

    if ($cursosAsignados > 0) {
        $cursos = \App\Models\CursoAsignatura::with(['curso.grado', 'curso.seccion', 'asignatura'])
            ->where('profesor_id', 27)
            ->get();
        foreach ($cursos as $curso) {
            echo '- ' . $curso->asignatura->nombre . ' en ' . $curso->curso->grado->nombre . ' ' . $curso->curso->seccion->nombre . PHP_EOL;
        }
    } else {
        echo 'No tiene cursos asignados. Creando datos de prueba...' . PHP_EOL;

        // Crear cursos de prueba para el docente
        $asignaturas = \App\Models\InfAsignatura::take(3)->get();
        $cursos = \App\Models\InfCurso::with(['grado', 'seccion'])->take(2)->get();

        foreach ($asignaturas as $asignatura) {
            foreach ($cursos as $curso) {
                \App\Models\CursoAsignatura::create([
                    'curso_id' => $curso->curso_id,
                    'asignatura_id' => $asignatura->asignatura_id,
                    'profesor_id' => 27,
                    'horas_semanales' => 4,
                    'estado' => 'Activo'
                ]);
                echo 'Creado: ' . $asignatura->nombre . ' en ' . $curso->grado->nombre . ' ' . $curso->seccion->nombre . PHP_EOL;
            }
        }
    }
} else {
    echo 'Docente no existe. Creando docente de prueba...' . PHP_EOL;

    // Crear persona
    $persona = \App\Models\Persona::create([
        'nombres' => 'Juan Carlos',
        'apellidos' => 'Pérez García',
        'dni' => '12345678',
        'fecha_nacimiento' => '1980-05-15',
        'genero' => 'M',
        'direccion' => 'Av. Principal 123',
        'telefono' => '987654321',
        'email' => 'juan.perez@educa.com'
    ]);

    // Crear docente
    $docente = \App\Models\InfDocente::create([
        'id_persona' => $persona->id_persona,
        'codigo_docente' => 'DOC001',
        'especialidad' => 'Matemáticas',
        'fecha_contratacion' => '2020-01-01',
        'estado' => 'Activo'
    ]);

    // Crear usuario
    $usuario = \App\Models\Usuario::create([
        'username' => 'docente27',
        'email' => 'juan.perez@educa.com',
        'password' => bcrypt('password'),
        'estado' => 'Activo',
        'id_persona' => $persona->id_persona
    ]);

    // Asignar rol docente
    $rolDocente = \App\Models\Rol::where('nombre', 'Docente')->first();
    if ($rolDocente) {
        \App\Models\PersonaRol::create([
            'id_persona' => $persona->id_persona,
            'rol_id' => $rolDocente->rol_id
        ]);
    }

    echo 'Docente creado con ID: ' . $docente->profesor_id . PHP_EOL;
}