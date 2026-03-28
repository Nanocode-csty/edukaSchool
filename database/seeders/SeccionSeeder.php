<?php

namespace Database\Seeders;

use App\Models\InfRepresentante;
use App\Models\InfSeccion;
use Illuminate\Database\Seeder;

class SeccionSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        InfSeccion::factory(10)->create();

    }

}  
