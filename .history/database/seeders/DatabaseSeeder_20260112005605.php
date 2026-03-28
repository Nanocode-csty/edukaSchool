<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\InfEstudiante;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        // Seed roles first
        $this->call(RolesSeeder::class);

        // Seed personas
        $this->call(PersonasSeeder::class);

        // Seed persona roles
        $this->call(PersonaRolesSeeder::class);

        // Migrate existing data to new normalized structure
        $this->call(MigrateExistingDataSeeder::class);

        // Seed specific role tables (for new data)
        $this->call(EstudiantesTableSeeder::class);
        $this->call(ProfesoresTableSeeder::class);
        $this->call(RepresentantesTableSeeder::class);

        // Seed presentation data for teacher ID 27
        $this->call(PresentationSeeder::class);

        // Seed periods for matricula system
        $this->call(PeriodosMatriculaSeeder::class);

        // Seed future academic year
        $this->call(AnioLectivo2027Seeder::class);

        // Seed coherent enrollment system for 2026
        $this->call(SistemaMatriculasCoherenteSeeder::class);

        //\App\Models\User::factory(1)->create();
        //Cliente::factory(20)->create();
        //InfEstudiante::factory(100)->create();

    }

}
