<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProfesoresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get personas with docente role
        $docenteRole = \App\Models\Rol::where('nombre', 'docente')->first();

        if ($docenteRole) {
            $docentesPersonas = \App\Models\Persona::whereHas('roles', function($query) use ($docenteRole) {
                $query->where('roles.id_rol', $docenteRole->id_rol);
            })->get();

            $especialidades = ['Matemáticas', 'Lenguaje', 'Ciencias', 'Historia', 'Inglés'];

            foreach ($docentesPersonas as $index => $persona) {
                // Create profesor record linked to persona with specific docente data
                \App\Models\InfDocente::create([
                    'persona_id' => $persona->id_persona,
                    'especialidad' => $especialidades[$index % count($especialidades)],
                    'fecha_contratacion' => now()->subYears(rand(1, 10))->toDateString(),
                    'estado' => 'Activo',
                    'foto_url' => null
                ]);
            }
        }
    }
}
