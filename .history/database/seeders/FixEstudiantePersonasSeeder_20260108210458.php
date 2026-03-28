<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FixEstudiantePersonasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update personas with missing surnames
        $updates = [
            11 => ['nombres' => 'María', 'apellido_paterno' => 'Pérez', 'apellido_materno' => 'Hernández'],
            21 => ['nombres' => 'Elena', 'apellido_paterno' => 'Díaz', 'apellido_materno' => 'Morales'],
            31 => ['nombres' => 'Jorge', 'apellido_paterno' => 'Martínez', 'apellido_materno' => 'García'],
            41 => ['nombres' => 'Carmen', 'apellido_paterno' => 'Rodríguez', 'apellido_materno' => 'López'],
        ];

        foreach ($updates as $personaId => $data) {
            \DB::table('personas')->where('persona_id', $personaId)->update($data);
            echo "Updated persona ID {$personaId}: {$data['nombres']} {$data['apellido_paterno']} {$data['apellido_materno']}\n";
        }
    }
}