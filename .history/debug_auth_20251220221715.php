<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Simular autenticación del docente
$usuario = \App\Models\Usuario::where('username', 'docente')->first();

if ($usuario) {
    echo 'Usuario encontrado: ' . $usuario->username . PHP_EOL;
    echo 'Tipo de modelo: ' . get_class($usuario) . PHP_EOL;
    echo 'Tiene método rol: ' . (method_exists($usuario, 'rol') ? 'Sí' : 'No') . PHP_EOL;
    echo 'Rol: ' . $usuario->rol . PHP_EOL;
    echo 'Tiene persona: ' . ($usuario->persona ? 'Sí' : 'No') . PHP_EOL;

    if ($usuario->persona) {
        echo 'Tipo de persona: ' . get_class($usuario->persona) . PHP_EOL;
        echo 'Tiene docente: ' . ($usuario->persona->docente ? 'Sí' : 'No') . PHP_EOL;

        if ($usuario->persona->docente) {
            echo 'Tipo de docente: ' . get_class($usuario->persona->docente) . PHP_EOL;
        }
    }

    // Simular Auth::user()
    \Illuminate\Support\Facades\Auth::login($usuario);
    $authUser = \Illuminate\Support\Facades\Auth::user();

    echo PHP_EOL . '=== Auth::user() ===' . PHP_EOL;
    echo 'Auth user tipo: ' . get_class($authUser) . PHP_EOL;
    echo 'Auth user rol: ' . $authUser->rol . PHP_EOL;
    echo 'Auth user persona: ' . ($authUser->persona ? 'Sí' : 'No') . PHP_EOL;

    if ($authUser->persona) {
        echo 'Auth user persona docente: ' . ($authUser->persona->docente ? 'Sí' : 'No') . PHP_EOL;
    }
} else {
    echo 'Usuario docente no encontrado' . PHP_EOL;
}
