<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Usuario;

try {
    echo "=== USUARIOS DISPONIBLES ===\n\n";

    $usuarios = Usuario::with('persona')->get();

    foreach ($usuarios as $usuario) {
        $roles = implode(', ', $usuario->getRoleNames());
        $persona = $usuario->persona ?
            $usuario->persona->nombres . ' ' . $usuario->persona->apellidos :
            'Sin persona';

        echo "Usuario: {$usuario->username}\n";
        echo "Email: {$usuario->email}\n";
        echo "Roles: {$roles}\n";
        echo "Persona: {$persona}\n";
        echo "Contraseña: password (por defecto)\n";
        echo str_repeat("-", 50) . "\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
