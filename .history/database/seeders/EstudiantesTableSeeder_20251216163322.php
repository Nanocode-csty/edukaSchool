<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EstudiantesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get personas with estudiante role
        $estudianteRole = \App\Models\Rol::where('nombre', 'estudiante')->first();

        if ($estudianteRole) {
            $estudiantesPersonas = \App\Models\Persona::whereHas('roles', function($query) use ($estudianteRole) {
                $query->where('roles.id_rol', $estudianteRole->id_rol);
            })->get();

            foreach ($estudiantesPersonas as $persona) {
                // Create estudiante record linked to persona if it doesn't exist
                \App\Models\InfEstudiante::firstOrCreate([
                    'persona_id' => $persona->id_persona
                ]);
            }
        }
    }
}
