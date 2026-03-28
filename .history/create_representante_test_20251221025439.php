<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Persona;
use App\Models\Usuario;
use App\Models\InfRepresentante;
use App\Models\InfEstudiante;
use Illuminate\Support\Facades\DB;

try {
    // Crear o obtener una persona
    $persona = Persona::firstOrCreate(
        ['dni' => '99999999'],
        [
            'nombres' => 'Test',
            'apellidos' => 'Representante',
            'email' => 'rep@test.com',
            'telefono' => '1234567890',
            'estado' => 'Activo'
        ]
    );

    echo "Persona: " . $persona->id_persona . "\n";

    // Crear o obtener un usuario
    $usuario = Usuario::firstOrCreate(
        ['username' => 'representante'],
        [
            'persona_id' => $persona->id_persona,
            'password' => bcrypt('password'),
            'estado' => 'Activo'
        ]
    );

    echo "Usuario: " . $usuario->id . "\n";

    // Crear o obtener un representante
    $representante = InfRepresentante::firstOrCreate(
        ['persona_id' => $persona->id_persona],
        [
            'parentesco' => 'Padre',
            'ocupacion' => 'Profesional'
        ]
    );

    echo "Representante: " . $representante->representante_id . "\n";

    // Verificar si hay estudiantes
    $estudiantes = InfEstudiante::where('estado', 'Activo')->limit(1)->get();
    
    if ($estudiantes->count() > 0) {
        $estudiante = $estudiantes->first();
        echo "Estudiante encontrado: " . $estudiante->estudiante_id . "\n";
        
        // Asociar el estudiante al representante si no está
        $exists = DB::table('estudiante_representante')
            ->where('representante_id', $representante->representante_id)
            ->where('estudiante_id', $estudiante->estudiante_id)
            ->exists();

        if (!$exists) {
            $representante->estudiantes()->attach($estudiante->estudiante_id);
            echo "Estudiante asociado al representante\n";
        } else {
            echo "Estudiante ya está asociado\n";
        }
    } else {
        echo "No hay estudiantes para asociar\n";
    }

    echo "Representante de prueba creado/actualizado exitosamente\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
