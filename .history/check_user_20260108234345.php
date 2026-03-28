<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = App\Models\Usuario::find(27);
echo 'User ID: ' . $user->usuario_id . PHP_EOL;
echo 'Username: ' . $user->username . PHP_EOL;
echo 'Has persona: ' . ($user->persona ? 'Yes' : 'No') . PHP_EOL;

if ($user->persona) {
    echo 'Persona ID: ' . $user->persona->id_persona . PHP_EOL;
    echo 'Has docente: ' . ($user->persona->docente ? 'Yes' : 'No') . PHP_EOL;
    if ($user->persona->docente) {
        echo 'Docente ID: ' . $user->persona->docente->profesor_id . PHP_EOL;
    }
}

echo 'Roles: ' . implode(', ', $user->getRoleNames()->toArray()) . PHP_EOL;
echo 'Has docente role: ' . ($user->hasRole('docente') ? 'Yes' : 'No') . PHP_EOL;
echo 'Has administrador role: ' . ($user->hasRole('administrador') ? 'Yes' : 'No') . PHP_EOL;

// Check persona_roles table
$personaRoles = DB::table('persona_roles')->where('persona_id', $user->persona_id ?? 0)->get();
echo 'Persona roles in table:' . PHP_EOL;
foreach ($personaRoles as $pr) {
    $role = App\Models\Rol::find($pr->rol_id);
    echo '  - Role ID: ' . $pr->rol_id . ', Name: ' . ($role ? $role->nombre : 'Unknown') . PHP_EOL;
}