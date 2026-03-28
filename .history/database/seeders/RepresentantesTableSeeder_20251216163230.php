<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RepresentantesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get personas with representante role
        $representanteRole = \App\Models\Rol::where('nombre', 'representante')->first();

        if ($representanteRole) {
            $representantesPersonas = \App\Models\Persona::whereHas('roles', function($query) use ($representanteRole) {
                $query->where('roles.id_rol', $representanteRole->id_rol);
            })->get();

            $parentescos = ['Padre', 'Madre', 'Tío', 'Abuelo', 'Tutor'];
            $ocupaciones = ['Ingeniero', 'Profesora', 'Comerciante', 'Médico', 'Abogado'];

            foreach ($representantesPersonas as $index => $persona) {
                // Create representante record linked to persona with specific representante data
                \App\Models\InfRepresentante::create([
                    'persona_id' => $persona->id_persona,
                    'parentesco' => $parentescos[$index % count($parentescos)],
                    'ocupacion' => $ocupaciones[$index % count($ocupaciones)]
                ]);
            }
        }
    }
}
