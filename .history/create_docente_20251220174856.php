<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$persona = \App\Models\Persona::where('dni', '66666666')->first();

if ($persona) {
    // Crear docente
    $docente = \App\Models\InfDocente::firstOrCreate(
        ['persona_id' => $persona->id_persona],
        [
            'especialidad' => 'Matemáticas',
            'fecha_contratacion' => now()->format('Y-m-d'),
            'estado' => 'Activo'
        ]
    );

    echo 'Docente creado con ID: ' . $docente->profesor_id . PHP_EOL;

    // Asignar rol docente
    $rolDocente = \App\Models\Rol::where('nombre', 'Docente')->first();
    if ($rolDocente) {
        $persona->roles()->detach(); // Remover roles existentes
        $persona->roles()->attach($rolDocente->id_rol, ['fecha_asignacion' => now()]);
        echo 'Rol docente asignado' . PHP_EOL;
    } else {
        echo 'Rol docente no encontrado' . PHP_EOL;
    }
} else {
    echo 'Persona no encontrada' . PHP_EOL;
}
