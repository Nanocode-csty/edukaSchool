<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\InfRepresentante;
use App\Models\InfEstudiante;
use Illuminate\Support\Facades\DB;

try {
    // Get the representative we just created
    $representante = InfRepresentante::where('persona_id', 3)->first();

    if (!$representante) {
        echo "Representante not found\n";
        exit(1);
    }

    echo "Found representative ID: {$representante->representante_id}\n";

    // Get some students
    $estudiantes = InfEstudiante::take(3)->get();

    if ($estudiantes->isEmpty()) {
        echo "No students found\n";
        exit(1);
    }

    echo "Found {$estudiantes->count()} students\n";

    foreach ($estudiantes as $estudiante) {
        // Check if relationship already exists
        $exists = DB::table('estudiante_representante')
            ->where('estudiante_id', $estudiante->estudiante_id)
            ->where('representante_id', $representante->representante_id)
            ->exists();

        if (!$exists) {
            DB::table('estudiante_representante')->insert([
                'estudiante_id' => $estudiante->estudiante_id,
                'representante_id' => $representante->representante_id,
                'parentesco' => 'Madre',
                'es_representante_principal' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            echo "Assigned student {$estudiante->nombres} {$estudiante->apellidos} to representative\n";
        } else {
            echo "Student {$estudiante->nombres} {$estudiante->apellidos} already assigned\n";
        }
    }

    echo "Students assignment completed successfully!\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
