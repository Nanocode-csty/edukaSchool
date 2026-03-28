<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Simular autenticación del docente
$usuario = \App\Models\Usuario::where('username', 'docente')->first();

if ($usuario) {
    echo 'Usuario: ' . $usuario->username . PHP_EOL;
    echo 'Rol (accessor): ' . $usuario->rol . PHP_EOL;
    echo 'hasRole("Docente"): ' . ($usuario->hasRole('Docente') ? 'Sí' : 'No') . PHP_EOL;
    echo 'hasRole("Administrador"): ' . ($usuario->hasRole('Administrador') ? 'Sí' : 'No') . PHP_EOL;

    echo 'Roles: ' . implode(', ', $usuario->getRoleNames()) . PHP_EOL;

    // Simular Auth::user()
    \Illuminate\Support\Facades\Auth::login($usuario);
    $authUser = \Illuminate\Support\Facades\Auth::user();

    echo PHP_EOL . '=== Auth::user() ===' . PHP_EOL;
    echo 'Auth rol: ' . $authUser->rol . PHP_EOL;
    echo 'Auth hasRole("Docente"): ' . ($authUser->hasRole('Docente') ? 'Sí' : 'No') . PHP_EOL;
    echo 'Auth in_array check: ' . (in_array($authUser->rol, ['Docente']) ? 'Sí' : 'No') . PHP_EOL;
} else {
    echo 'Usuario docente no encontrado' . PHP_EOL;
}
