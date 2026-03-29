<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DistritoSeeder extends Seeder
{
    public function run()
    {
        DB::table('direccion_distrito')->insert([

            // CHACHAPOYAS (1)
            ['nombre' => 'CHACHAPOYAS', 'idProvincia' => 1],
            ['nombre' => 'ASUNCION', 'idProvincia' => 1],

            // BAGUA (2)
            ['nombre' => 'BAGUA', 'idProvincia' => 2],
            ['nombre' => 'LA PECA', 'idProvincia' => 2],

            // BONGARA (3)
            ['nombre' => 'JUMBILLA', 'idProvincia' => 3],
            ['nombre' => 'COROSHA', 'idProvincia' => 3],

            // CONDORCANQUI (4)
            ['nombre' => 'NIEVA', 'idProvincia' => 4],
            ['nombre' => 'EL CENEPA', 'idProvincia' => 4],

            // HUARAZ (5)
            ['nombre' => 'HUARAZ', 'idProvincia' => 5],
            ['nombre' => 'INDEPENDENCIA', 'idProvincia' => 5],

            // SANTA (6)
            ['nombre' => 'CHIMBOTE', 'idProvincia' => 6],
            ['nombre' => 'NUEVO CHIMBOTE', 'idProvincia' => 6],

            // CASMA (7)
            ['nombre' => 'CASMA', 'idProvincia' => 7],
            ['nombre' => 'BUENA VISTA ALTA', 'idProvincia' => 7],

            // HUARI (8)
            ['nombre' => 'HUARI', 'idProvincia' => 8],
            ['nombre' => 'ANRA', 'idProvincia' => 8],

            // ABANCAY (9)
            ['nombre' => 'ABANCAY', 'idProvincia' => 9],
            ['nombre' => 'TAMBURCO', 'idProvincia' => 9],

            // ANDAHUAYLAS (10)
            ['nombre' => 'ANDAHUAYLAS', 'idProvincia' => 10],
            ['nombre' => 'SAN JERONIMO', 'idProvincia' => 10],

            // AREQUIPA (13)
            ['nombre' => 'AREQUIPA', 'idProvincia' => 13],
            ['nombre' => 'YANAHUARA', 'idProvincia' => 13],

            // HUAMANGA (17)
            ['nombre' => 'AYACUCHO', 'idProvincia' => 17],
            ['nombre' => 'SAN JUAN BAUTISTA', 'idProvincia' => 17],

            // CAJAMARCA (21)
            ['nombre' => 'CAJAMARCA', 'idProvincia' => 21],
            ['nombre' => 'BAÑOS DEL INCA', 'idProvincia' => 21],

            // CALLAO (25)
            ['nombre' => 'CALLAO', 'idProvincia' => 25],
            ['nombre' => 'VENTANILLA', 'idProvincia' => 25],

            // CUSCO (29)
            ['nombre' => 'CUSCO', 'idProvincia' => 29],
            ['nombre' => 'SAN SEBASTIAN', 'idProvincia' => 29],

            // HUANCAVELICA (33)
            ['nombre' => 'HUANCAVELICA', 'idProvincia' => 33],
            ['nombre' => 'ASCENSION', 'idProvincia' => 33],

            // HUANUCO (37)
            ['nombre' => 'HUANUCO', 'idProvincia' => 37],
            ['nombre' => 'AMARILIS', 'idProvincia' => 37],

            // ICA (41)
            ['nombre' => 'ICA', 'idProvincia' => 41],
            ['nombre' => 'PARCONA', 'idProvincia' => 41],

            // HUANCAYO (45)
            ['nombre' => 'HUANCAYO', 'idProvincia' => 45],
            ['nombre' => 'EL TAMBO', 'idProvincia' => 45],

            // TRUJILLO (49)
            ['nombre' => 'TRUJILLO', 'idProvincia' => 49],
            ['nombre' => 'LA ESPERANZA', 'idProvincia' => 49],

            // CHICLAYO (53)
            ['nombre' => 'CHICLAYO', 'idProvincia' => 53],
            ['nombre' => 'JOSE LEONARDO ORTIZ', 'idProvincia' => 53],

            // LIMA (57)
            ['nombre' => 'LIMA', 'idProvincia' => 57],
            ['nombre' => 'MIRAFLORES', 'idProvincia' => 57],

        ]);
    }
}