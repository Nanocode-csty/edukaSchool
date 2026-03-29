<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvinciaSeeder extends Seeder
{
    public function run()
    {
        DB::table('direccion_provincia')->insert([

            // AMAZONAS
            ['nombre' => 'CHACHAPOYAS', 'idRegion' => 1],
            ['nombre' => 'BAGUA', 'idRegion' => 1],
            ['nombre' => 'BONGARA', 'idRegion' => 1],
            ['nombre' => 'CONDORCANQUI', 'idRegion' => 1],

            // ANCASH
            ['nombre' => 'HUARAZ', 'idRegion' => 2],
            ['nombre' => 'SANTA', 'idRegion' => 2],
            ['nombre' => 'CASMA', 'idRegion' => 2],
            ['nombre' => 'HUARI', 'idRegion' => 2],

            // APURIMAC
            ['nombre' => 'ABANCAY', 'idRegion' => 3],
            ['nombre' => 'ANDAHUAYLAS', 'idRegion' => 3],
            ['nombre' => 'ANTABAMBA', 'idRegion' => 3],
            ['nombre' => 'AYMARAES', 'idRegion' => 3],

            // AREQUIPA
            ['nombre' => 'AREQUIPA', 'idRegion' => 4],
            ['nombre' => 'CAMANA', 'idRegion' => 4],
            ['nombre' => 'CARAVELI', 'idRegion' => 4],
            ['nombre' => 'CASTILLA', 'idRegion' => 4],

            // AYACUCHO
            ['nombre' => 'HUAMANGA', 'idRegion' => 5],
            ['nombre' => 'CANGALLO', 'idRegion' => 5],
            ['nombre' => 'HUANTA', 'idRegion' => 5],
            ['nombre' => 'LA MAR', 'idRegion' => 5],

            // CAJAMARCA
            ['nombre' => 'CAJAMARCA', 'idRegion' => 6],
            ['nombre' => 'CAJABAMBA', 'idRegion' => 6],
            ['nombre' => 'CELENDIN', 'idRegion' => 6],
            ['nombre' => 'CHOTA', 'idRegion' => 6],

            // CALLAO
            ['nombre' => 'CALLAO', 'idRegion' => 7],
            ['nombre' => 'VENTANILLA', 'idRegion' => 7],
            ['nombre' => 'BELLAVISTA', 'idRegion' => 7],
            ['nombre' => 'LA PERLA', 'idRegion' => 7],

            // CUSCO
            ['nombre' => 'CUSCO', 'idRegion' => 8],
            ['nombre' => 'URUBAMBA', 'idRegion' => 8],
            ['nombre' => 'CALCA', 'idRegion' => 8],
            ['nombre' => 'CANCHIS', 'idRegion' => 8],

            // HUANCAVELICA
            ['nombre' => 'HUANCAVELICA', 'idRegion' => 9],
            ['nombre' => 'ACOBAMBA', 'idRegion' => 9],
            ['nombre' => 'ANGARAES', 'idRegion' => 9],
            ['nombre' => 'CASTROVIRREYNA', 'idRegion' => 9],

            // HUANUCO
            ['nombre' => 'HUANUCO', 'idRegion' => 10],
            ['nombre' => 'AMBO', 'idRegion' => 10],
            ['nombre' => 'DOS DE MAYO', 'idRegion' => 10],
            ['nombre' => 'HUACAYBAMBA', 'idRegion' => 10],

            // ICA
            ['nombre' => 'ICA', 'idRegion' => 11],
            ['nombre' => 'CHINCHA', 'idRegion' => 11],
            ['nombre' => 'NAZCA', 'idRegion' => 11],
            ['nombre' => 'PISCO', 'idRegion' => 11],

            // JUNIN
            ['nombre' => 'HUANCAYO', 'idRegion' => 12],
            ['nombre' => 'CHANCHAMAYO', 'idRegion' => 12],
            ['nombre' => 'JAUJA', 'idRegion' => 12],
            ['nombre' => 'SATIPO', 'idRegion' => 12],

            // LA LIBERTAD
            ['nombre' => 'TRUJILLO', 'idRegion' => 13],
            ['nombre' => 'ASCOPE', 'idRegion' => 13],
            ['nombre' => 'CHEPEN', 'idRegion' => 13],
            ['nombre' => 'PACASMAYO', 'idRegion' => 13],

            // LAMBAYEQUE
            ['nombre' => 'CHICLAYO', 'idRegion' => 14],
            ['nombre' => 'FERREÑAFE', 'idRegion' => 14],
            ['nombre' => 'LAMBAYEQUE', 'idRegion' => 14],
            ['nombre' => 'MOTUPE', 'idRegion' => 14],

            // LIMA
            ['nombre' => 'LIMA', 'idRegion' => 15],
            ['nombre' => 'CAÑETE', 'idRegion' => 15],
            ['nombre' => 'HUARAL', 'idRegion' => 15],
            ['nombre' => 'BARRANCA', 'idRegion' => 15],

            // LORETO
            ['nombre' => 'MAYNAS', 'idRegion' => 16],
            ['nombre' => 'ALTO AMAZONAS', 'idRegion' => 16],
            ['nombre' => 'LORETO', 'idRegion' => 16],
            ['nombre' => 'REQUENA', 'idRegion' => 16],

            // MADRE DE DIOS
            ['nombre' => 'TAMBOPATA', 'idRegion' => 17],
            ['nombre' => 'MANU', 'idRegion' => 17],
            ['nombre' => 'TAHUAMANU', 'idRegion' => 17],
            ['nombre' => 'LABERINTO', 'idRegion' => 17],

            // MOQUEGUA
            ['nombre' => 'MARISCAL NIETO', 'idRegion' => 18],
            ['nombre' => 'ILO', 'idRegion' => 18],
            ['nombre' => 'GENERAL SANCHEZ CERRO', 'idRegion' => 18],
            ['nombre' => 'OMATE', 'idRegion' => 18],

            // PASCO
            ['nombre' => 'PASCO', 'idRegion' => 19],
            ['nombre' => 'OXAPAMPA', 'idRegion' => 19],
            ['nombre' => 'DANIEL ALCIDES CARRION', 'idRegion' => 19],
            ['nombre' => 'YANAHUANCA', 'idRegion' => 19],

            // PIURA
            ['nombre' => 'PIURA', 'idRegion' => 20],
            ['nombre' => 'SULLANA', 'idRegion' => 20],
            ['nombre' => 'PAITA', 'idRegion' => 20],
            ['nombre' => 'TALARA', 'idRegion' => 20],

            // PUNO
            ['nombre' => 'PUNO', 'idRegion' => 21],
            ['nombre' => 'JULIACA', 'idRegion' => 21],
            ['nombre' => 'AZANGARO', 'idRegion' => 21],
            ['nombre' => 'LAMPA', 'idRegion' => 21],

            // SAN MARTIN
            ['nombre' => 'MOYOBAMBA', 'idRegion' => 22],
            ['nombre' => 'RIOJA', 'idRegion' => 22],
            ['nombre' => 'TARAPOTO', 'idRegion' => 22],
            ['nombre' => 'LAMAS', 'idRegion' => 22],

            // TACNA
            ['nombre' => 'TACNA', 'idRegion' => 23],
            ['nombre' => 'JORGE BASADRE', 'idRegion' => 23],
            ['nombre' => 'TARATA', 'idRegion' => 23],
            ['nombre' => 'CANDARAVE', 'idRegion' => 23],

            // TUMBES
            ['nombre' => 'TUMBES', 'idRegion' => 24],
            ['nombre' => 'ZARUMILLA', 'idRegion' => 24],
            ['nombre' => 'CONTRALMIRANTE VILLAR', 'idRegion' => 24],
            ['nombre' => 'AGUAS VERDES', 'idRegion' => 24],

            // UCAYALI
            ['nombre' => 'CORONEL PORTILLO', 'idRegion' => 25],
            ['nombre' => 'ATALAYA', 'idRegion' => 25],
            ['nombre' => 'PADRE ABAD', 'idRegion' => 25],
            ['nombre' => 'PURUS', 'idRegion' => 25],

        ]);
    }
}
