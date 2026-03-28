<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$persona = \App\Models\Persona::find(1);
dd([
    'persona' => $persona,
    'roles' => $persona ? $persona->roles->pluck('nombre')->toArray() : null
]);
