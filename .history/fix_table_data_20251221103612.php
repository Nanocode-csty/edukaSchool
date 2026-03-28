<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    echo "=== CHECKING TABLES ===\n";

    // Check estudiante_representante table
    $estudianteRepresentanteCount = DB::table('estudiante_representante')->count();
    echo "estudiante_representante table has {$estudianteRepresentanteCount} records\n";

    // Check estudianterepresentante table
    $estudianterepresentanteCount = DB::table('estudianterepresentante')->count();
    echo "estudianterepresentante table has {$estudianterepresentanteCount} records\n";

    if ($estudianteRepresentanteCount > 0 && $estudianterepresentanteCount == 0) {
        echo "\n=== MIGRATING DATA ===\n";
        echo "Migrating data from estudiante_representante to estudianterepresentante\n";

        $records = DB::table('estudiante_representante')->get();
        foreach ($records as $record) {
            DB::table('estudianterepresentante')->insert([
                'estudiante_id' => $record->estudiante_id,
                'representante_id' => $record->representante_id,
                'es_principal' => $record->es_representante_principal ? 1 : 0,
                'viveConEstudiante' => 'Si' // Default value
            ]);
            echo "Migrated: estudiante {$record->estudiante_id} -> representante {$record->representante_id}\n";
        }

        echo "Migration completed!\n";
    } elseif ($estudianterepresentanteCount > 0) {
        echo "Data already exists in estudianterepresentante table\n";
    } else {
        echo "No data found in either table\n";
    }

    echo "\n=== FINAL CHECK ===\n";
    $finalCount = DB::table('estudianterepresentante')->count();
    echo "estudianterepresentante table now has {$finalCount} records\n";

    // Test the relationship
    echo "\n=== TESTING RELATIONSHIP ===\n";
    $representante = \App\Models\InfRepresentante::find(26);
    if ($representante) {
        $estudiantes = $representante->estudiantes;
        echo "Representante 26 has {$estudiantes->count()} students\n";
        foreach ($estudiantes as $est) {
            echo "- {$est->persona->nombres} {$est->persona->apellidos} (ID: {$est->estudiante_id})\n";
        }
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
