<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Checking representative user role...\n";

$user = \App\Models\Usuario::where('username', 'representante')->first();

if ($user) {
    echo "User found: {$user->username}\n";
    echo "Role (accessor): {$user->rol}\n";
    echo "HasRole Representante: " . ($user->hasRole('representante') ? 'yes' : 'no') . "\n";

    if ($user->persona) {
        echo "Persona exists: {$user->persona->nombres} {$user->persona->apellidos}\n";
        $roles = $user->persona->roles;
        echo "Persona roles count: {$roles->count()}\n";
        foreach ($roles as $role) {
            echo "  - Role: {$role->nombre}\n";
        }
    } else {
        echo "No persona relation\n";
    }
} else {
    echo "User not found\n";
}

echo "\nTesting role conditions:\n";
echo "auth()->user()->rol == 'representante': " . (($user && $user->rol == 'representante') ? 'true' : 'false') . "\n";
echo "auth()->user()->hasRole('representante'): " . (($user && $user->hasRole('representante')) ? 'true' : 'false') . "\n";
