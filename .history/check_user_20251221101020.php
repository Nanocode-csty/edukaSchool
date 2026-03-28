<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Usuario;
use App\Models\Rol;
use App\Models\InfRepresentante;
use Illuminate\Support\Facades\DB;

try {
    echo "=== CURRENT USER STATE ===\n";

    $user = Usuario::find(4);
    echo "User ID: {$user->usuario_id}\n";
    echo "Persona ID: {$user->persona_id}\n";
    echo "Persona: " . ($user->persona ? $user->persona->nombres . ' ' . $user->persona->apellidos : 'No persona') . "\n";
    echo "Roles: " . implode(', ', $user->getRoleNames()) . "\n";
    echo "Has representante: " . ($user->persona && $user->persona->representante ? 'Yes (ID: ' . $user->persona->representante->representante_id . ')' : 'No') . "\n";

    echo "\n=== FIXING RELATIONSHIPS ===\n";

    // 1. Ensure persona_roles has the correct entry
    $representanteRole = Rol::where('nombre', 'representante')->first();
    if (!$representanteRole) {
        $representanteRole = Rol::where('nombre', 'Representante')->first();
    }

    if ($representanteRole) {
        echo "Found representante role ID: {$representanteRole->id_rol}\n";

        // Check if persona_roles entry exists
        $personaRoleExists = DB::table('persona_roles')
            ->where('persona_id', 3)
            ->where('rol_id', $representanteRole->id_rol)
            ->exists();

        if (!$personaRoleExists) {
            DB::table('persona_roles')->insert([
                'persona_id' => 3,
                'rol_id' => $representanteRole->id_rol,
                'fecha_asignacion' => now(),
                'estado' => 'Activo',
                'created_at' => now(),
                'updated_at' => now()
            ]);
            echo "✓ Added persona_roles entry\n";
        } else {
            echo "✓ persona_roles entry already exists\n";
        }
    }

    // 2. Ensure representante record exists
    $representante = InfRepresentante::where('persona_id', 3)->first();
    if (!$representante) {
        $representante = InfRepresentante::create([
            'persona_id' => 3,
            'parentesco' => 'Madre',
            'ocupacion' => 'Profesora'
        ]);
        echo "✓ Created representante record (ID: {$representante->representante_id})\n";
    } else {
        echo "✓ Representante record exists (ID: {$representante->representante_id})\n";
    }

    // 3. Assign students to this representative
    $estudiantes = \App\Models\InfEstudiante::take(3)->get();
    $assigned = 0;

    foreach ($estudiantes as $estudiante) {
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
            $assigned++;
            echo "✓ Assigned student: {$estudiante->nombres} {$estudiante->apellidos}\n";
        }
    }

    if ($assigned == 0) {
        echo "✓ All students already assigned\n";
    }

    echo "\n=== VERIFICATION ===\n";

    // Refresh user data
    $user->refresh();
    echo "Updated Roles: " . implode(', ', $user->getRoleNames()) . "\n";
    echo "Has representante: " . ($user->persona && $user->persona->representante ? 'Yes' : 'No') . "\n";

    if ($user->persona && $user->persona->representante) {
        $studentCount = $user->persona->representante->estudiantes()->count();
        echo "Students assigned: {$studentCount}\n";
    }

    echo "\n=== DONE ===\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
