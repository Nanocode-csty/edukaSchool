<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegionSeeder extends Seeder
{
    public function run()
    {
        DB::table('direccion_region')->insert([
            ['idRegion' => 1, 'nombre' => 'AMAZONAS'],
            ['idRegion' => 2, 'nombre' => 'ANCASH'],
            ['idRegion' => 3, 'nombre' => 'APURIMAC'],
            ['idRegion' => 4, 'nombre' => 'AREQUIPA'],
            ['idRegion' => 5, 'nombre' => 'AYACUCHO'],
            ['idRegion' => 6, 'nombre' => 'CAJAMARCA'],
            ['idRegion' => 7, 'nombre' => 'CALLAO'],
            ['idRegion' => 8, 'nombre' => 'CUSCO'],
            ['idRegion' => 9, 'nombre' => 'HUANCAVELICA'],
            ['idRegion' => 10, 'nombre' => 'HUANUCO'],
            ['idRegion' => 11, 'nombre' => 'ICA'],
            ['idRegion' => 12, 'nombre' => 'JUNIN'],
            ['idRegion' => 13, 'nombre' => 'LA LIBERTAD'],
            ['idRegion' => 14, 'nombre' => 'LAMBAYEQUE'],
            ['idRegion' => 15, 'nombre' => 'LIMA'],
            ['idRegion' => 16, 'nombre' => 'LORETO'],
            ['idRegion' => 17, 'nombre' => 'MADRE DE DIOS'],
            ['idRegion' => 18, 'nombre' => 'MOQUEGUA'],
            ['idRegion' => 19, 'nombre' => 'PASCO'],
            ['idRegion' => 20, 'nombre' => 'PIURA'],
            ['idRegion' => 21, 'nombre' => 'PUNO'],
            ['idRegion' => 22, 'nombre' => 'SAN MARTIN'],
            ['idRegion' => 23, 'nombre' => 'TACNA'],
            ['idRegion' => 24, 'nombre' => 'TUMBES'],
            ['idRegion' => 25, 'nombre' => 'UCAYALI'],
        ]);
    }
}
