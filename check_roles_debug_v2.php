<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Usuario;
use Illuminate\Support\Facades\DB;

echo "Checking Roles...\n";

// Get all roles
$roles = DB::table('roles')->get();
echo "Roles found in DB:\n";
foreach($roles as $r) {
    echo "- ID: {$r->id_rol}, Nombre: '{$r->nombre}'\n";
}

echo "\nChecking Users...\n";
$users = Usuario::take(10)->get();
foreach($users as $u) {
    try {
        echo "User: {$u->username}\n";
        echo "  - Has 'Administrador'? " . ($u->hasRole('Administrador') ? 'YES' : 'NO') . "\n";
        echo "  - Roles: " . implode(', ', $u->getRoleNames()) . "\n";
    } catch (\Exception $e) {
        echo "Error: {$e->getMessage()}\n";
    }
}
