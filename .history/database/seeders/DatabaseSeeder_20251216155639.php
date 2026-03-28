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

        //\App\Models\User::factory(1)->create();
        //Cliente::factory(20)->create();
        //InfEstudiante::factory(100)->create();

    }

}
