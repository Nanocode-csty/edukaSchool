<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Checking representative user...\n";

$user = \App\Models\Usuario::where('username', 'representante')->first();

if ($user) {
    echo "User found: {$user->username}, Role: {$user->rol}\n";

    $persona = $user->persona;
    if ($persona) {
        echo "Persona: {$persona->nombres} {$persona->apellidos}\n";

        $representante = $persona->representante;
        if ($representante) {
            echo "Representante ID: {$representante->representante_id}\n";

            $estudiantes = $representante->estudiantes;
            echo "Estudiantes count: {$estudiantes->count()}\n";

            if ($estudiantes->count() > 0) {
                echo "Estudiantes:\n";
                foreach ($estudiantes as $estudiante) {
                    echo "  - {$estudiante->nombres} {$estudiante->apellidos} (ID: {$estudiante->id})\n";
                }
            }
        } else {
            echo "No representante relation\n";
        }
    } else {
        echo "No persona relation\n";
    }
} else {
    echo "User not found\n";
}

echo "\nChecking routes...\n";
$routes = \Illuminate\Support\Facades\Route::getRoutes();
$representanteRoutes = [];

foreach ($routes as $route) {
    if (strpos($route->getName(), 'asistencia.representante') !== false) {
        $representanteRoutes[] = $route->getName();
    }
}

echo "Representative routes found: " . count($representanteRoutes) . "\n";
foreach ($representanteRoutes as $route) {
    echo "  - {$route}\n";
}
