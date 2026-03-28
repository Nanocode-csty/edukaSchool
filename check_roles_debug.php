<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\DB;

echo "Checking Roles...\n";

$roles = DB::table('roles')->get();
foreach($roles as $r) {
    echo "Role: {$r->name} (guard: {$r->guard_name})\n";
}

// Check first/some users
$users = User::with('roles')->take(5)->get();
foreach($users as $u) {
    echo "User: {$u->username} ({$u->nombres}) - Roles: " . $u->getRoleNames() . "\n";
}
