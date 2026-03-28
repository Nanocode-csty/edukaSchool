<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$usuario = \App\Models\Usuario::where('username', 'docente')->first();

if ($usuario) {
    echo 'Usuario encontrado: ' . $usuario->username . PHP_EOL;
    echo 'Usuario ID: ' . $usuario->usuario_id . PHP_EOL;
    echo 'Persona ID: ' . ($usuario->persona_id ?? 'NULL') . PHP_EOL;
    echo 'Rol: ' . $usuario->rol . PHP_EOL;
    echo 'Tiene persona: ' . ($usuario->persona ? 'Sí' : 'No') . PHP_EOL;

    if ($usuario->persona) {
        echo 'Persona nombre: ' . $usuario->persona->nombres . ' ' . $usuario->persona->apellidos . PHP_EOL;
        echo 'Tiene docente: ' . ($usuario->persona->docente ? 'Sí' : 'No') . PHP_EOL;

        if ($usuario->persona->docente) {
            echo 'Docente ID: ' . $usuario->persona->docente->id . PHP_EOL;
            echo 'Docente código: ' . $usuario->persona->docente->codigo_docente . PHP_EOL;
        }

        echo 'Roles de persona: ' . $usuario->persona->roles->pluck('nombre')->join(', ') . PHP_EOL;
    }
} else {
    echo 'Usuario docente no encontrado' . PHP_EOL;
}
