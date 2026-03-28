<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$usuario = \App\Models\Usuario::where('username', 'docente')->first();

if ($usuario) {
    $persona = \App\Models\Persona::where('dni', '66666666')->first();

    if ($persona) {
        $usuario->update(['persona_id' => $persona->id_persona]);
        echo 'Usuario actualizado con persona_id: ' . $persona->id_persona . PHP_EOL;
    } else {
        echo 'Persona no encontrada' . PHP_EOL;
    }
} else {
    echo 'Usuario no encontrado' . PHP_EOL;
}
