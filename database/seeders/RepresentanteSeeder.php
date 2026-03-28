<?php

namespace Database\Seeders;

use App\Models\InfRepresentante;
use Illuminate\Database\Seeder;

class RepresentanteSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        InfRepresentante::factory(10)->create();

    }

}  
