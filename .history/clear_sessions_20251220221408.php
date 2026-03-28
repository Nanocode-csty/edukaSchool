<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Limpiar todas las sesiones
\DB::table('sessions')->truncate();

echo 'Sesiones limpiadas exitosamente.' . PHP_EOL;
