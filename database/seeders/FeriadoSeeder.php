<?php

namespace Database\Seeders;

use App\Models\Feriado;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class FeriadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $anioActual = Carbon::now()->year;

        // Feriados nacionales fijos en Perú
        $feriadosFijos = [
            [
                'nombre' => 'Año Nuevo',
                'fecha' => Carbon::create($anioActual, 1, 1),
                'tipo' => 'nacional',
                'recuperable' => false,
                'descripcion' => 'Primer día del año'
            ],
            [
                'nombre' => 'Día del Trabajo',
                'fecha' => Carbon::create($anioActual, 5, 1),
                'tipo' => 'nacional',
                'recuperable' => false,
                'descripcion' => 'Celebración del día del trabajo'
            ],
            [
                'nombre' => 'Día de las Fuerzas Armadas',
                'fecha' => Carbon::create($anioActual, 9, 24),
                'tipo' => 'nacional',
                'recuperable' => false,
                'descripcion' => 'Aniversario de la Batalla de Junín'
            ],
            [
                'nombre' => 'Día de Todos los Santos',
                'fecha' => Carbon::create($anioActual, 11, 1),
                'tipo' => 'religioso',
                'recuperable' => false,
                'descripcion' => 'Día de Todos los Santos'
            ],
            [
                'nombre' => 'Día de la Inmaculada Concepción',
                'fecha' => Carbon::create($anioActual, 12, 8),
                'tipo' => 'religioso',
                'recuperable' => false,
                'descripcion' => 'Inmaculada Concepción'
            ],
            [
                'nombre' => 'Navidad',
                'fecha' => Carbon::create($anioActual, 12, 25),
                'tipo' => 'religioso',
                'recuperable' => false,
                'descripcion' => 'Natividad del Señor'
            ]
        ];

        // Feriados movibles (calculados dinámicamente)
        $feriadosMovibles = $this->calcularFeriadosMovibles($anioActual);

        $todosLosFeriados = array_merge($feriadosFijos, $feriadosMovibles);

        foreach ($todosLosFeriados as $feriadoData) {
            Feriado::updateOrCreate(
                [
                    'nombre' => $feriadoData['nombre'],
                    'fecha' => $feriadoData['fecha']->format('Y-m-d')
                ],
                [
                    'tipo' => $feriadoData['tipo'],
                    'recuperable' => $feriadoData['recuperable'],
                    'descripcion' => $feriadoData['descripcion'],
                    'ubicacion' => null,
                    'activo' => true,
                    'creado_por' => 1 // Asumiendo que el usuario admin tiene ID 1
                ]
            );
        }
    }

    /**
     * Calcula los feriados movibles de Perú
     */
    private function calcularFeriadosMovibles($anio)
    {
        $feriadosMovibles = [];

        // Jueves Santo (Jueves anterior al Domingo de Pascua)
        $juevesSanto = $this->calcularJuevesSanto($anio);
        if ($juevesSanto) {
            $feriadosMovibles[] = [
                'nombre' => 'Jueves Santo',
                'fecha' => $juevesSanto,
                'tipo' => 'religioso',
                'recuperable' => false,
                'descripcion' => 'Jueves Santo de la Semana Santa'
            ];
        }

        // Viernes Santo (Viernes anterior al Domingo de Pascua)
        $viernesSanto = $this->calcularViernesSanto($anio);
        if ($viernesSanto) {
            $feriadosMovibles[] = [
                'nombre' => 'Viernes Santo',
                'fecha' => $viernesSanto,
                'tipo' => 'religioso',
                'recuperable' => false,
                'descripcion' => 'Viernes Santo de la Semana Santa'
            ];
        }

        // Corpus Christi (60 días después del Domingo de Pascua)
        $corpusChristi = $this->calcularCorpusChristi($anio);
        if ($corpusChristi) {
            $feriadosMovibles[] = [
                'nombre' => 'Corpus Christi',
                'fecha' => $corpusChristi,
                'tipo' => 'religioso',
                'recuperable' => true,
                'descripcion' => 'Corpus Christi'
            ];
        }

        return $feriadosMovibles;
    }

    /**
     * Calcula la fecha del Jueves Santo
     */
    private function calcularJuevesSanto($anio)
    {
        // Algoritmo para calcular el Domingo de Pascua (Algoritmo de Butcher)
        $a = $anio % 19;
        $b = floor($anio / 100);
        $c = $anio % 100;
        $d = floor($b / 4);
        $e = $b % 4;
        $f = floor(($b + 8) / 25);
        $g = floor(($b - $f + 1) / 3);
        $h = (19 * $a + $b - $d - $g + 15) % 30;
        $i = floor($c / 4);
        $k = $c % 4;
        $l = (32 + 2 * $e + 2 * $i - $h - $k) % 7;
        $m = floor(($a + 11 * $h + 22 * $l) / 451);
        $mes = floor(($h + $l - 7 * $m + 114) / 31);
        $dia = (($h + $l - 7 * $m + 114) % 31) + 1;

        $domingoPascua = Carbon::create($anio, $mes, $dia);

        // Jueves Santo es 3 días antes del Domingo de Pascua
        return $domingoPascua->copy()->subDays(3);
    }

    /**
     * Calcula la fecha del Viernes Santo
     */
    private function calcularViernesSanto($anio)
    {
        $juevesSanto = $this->calcularJuevesSanto($anio);
        return $juevesSanto->copy()->addDay(); // Viernes es un día después del Jueves Santo
    }

    /**
     * Calcula la fecha del Corpus Christi
     */
    private function calcularCorpusChristi($anio)
    {
        // Algoritmo para calcular el Domingo de Pascua
        $a = $anio % 19;
        $b = floor($anio / 100);
        $c = $anio % 100;
        $d = floor($b / 4);
        $e = $b % 4;
        $f = floor(($b + 8) / 25);
        $g = floor(($b - $f + 1) / 3);
        $h = (19 * $a + $b - $d - $g + 15) % 30;
        $i = floor($c / 4);
        $k = $c % 4;
        $l = (32 + 2 * $e + 2 * $i - $h - $k) % 7;
        $m = floor(($a + 11 * $h + 22 * $l) / 451);
        $mes = floor(($h + $l - 7 * $m + 114) / 31);
        $dia = (($h + $l - 7 * $m + 114) % 31) + 1;

        $domingoPascua = Carbon::create($anio, $mes, $dia);

        // Corpus Christi es 60 días después del Domingo de Pascua
        return $domingoPascua->copy()->addDays(60);
    }
}
