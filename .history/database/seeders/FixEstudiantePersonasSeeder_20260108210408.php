<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\InfEstudiante;
use App\Models\Persona;

class FixEstudiantePersonasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all estudiantes that have persona relationships
        $estudiantes = InfEstudiante::with('persona')->get();

        $nombresData = [
            11 => ['nombres' => 'María', 'apellido_paterno' => 'Pérez', 'apellido_materno' => 'Hernández'],
            21 => ['nombres' => 'Elena', 'apellido_paterno' => 'Díaz', 'apellido_materno' => 'Morales'],
            31 => ['nombres' => 'Jorge', 'apellido_paterno' => 'Martínez', 'apellido_materno' => 'García'],
            41 => ['nombres' => 'Carmen', 'apellido_paterno' => 'Rodríguez', 'apellido_materno' => 'López'],
        ];

        foreach ($estudiantes as $estudiante) {
            if ($estudiante->persona) {
                $personaId = $estudiante->persona->id_persona;

                if (isset($nombresData[$personaId])) {
                    $estudiante->persona->update($nombresData[$personaId]);
                    echo "Updated persona ID {$personaId}: {$nombresData[$personaId]['nombres']} {$nombresData[$personaId]['apellido_paterno']} {$nombresData[$personaId]['apellido_materno']}\n";
                }
            }
        }
    }
}