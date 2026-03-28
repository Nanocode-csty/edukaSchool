<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\InfAnioLectivo;

class AnioLectivoSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        \App\Models\InfAnioLectivo::factory(2)->create();
    }
}