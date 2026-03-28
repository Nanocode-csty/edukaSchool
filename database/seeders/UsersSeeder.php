<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\InfEstudiante;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        \App\Models\User::factory(1)->create();
        //Cliente::factory(20)->create();
        //InfEstudiante::factory(20)->create();

    }

}  
