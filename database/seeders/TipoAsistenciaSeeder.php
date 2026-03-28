<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TipoAsistencia;

class TipoAsistenciaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tiposAsistencia = [
            [
                'codigo' => 'A',
                'nombre' => 'Asistió',
                'descripcion' => 'Presente en clase',
                'factor_asistencia' => 1.00,
                'computa_falta' => false,
                'activo' => true
            ],
            [
                'codigo' => 'F',
                'nombre' => 'Falta',
                'descripcion' => 'Ausencia injustificada',
                'factor_asistencia' => 0.00,
                'computa_falta' => true,
                'activo' => true
            ],
            [
                'codigo' => 'T',
                'nombre' => 'Tardanza',
                'descripcion' => 'Llegó tarde a clase',
                'factor_asistencia' => 0.50,
                'computa_falta' => false,
                'activo' => true
            ],
            [
                'codigo' => 'J',
                'nombre' => 'Falta Justificada',
                'descripcion' => 'Ausencia con justificación médica o familiar',
                'factor_asistencia' => 0.75,
                'computa_falta' => false,
                'activo' => true
            ],
            [
                'codigo' => 'P',
                'nombre' => 'Permiso',
                'descripcion' => 'Salida o ausencia autorizada',
                'factor_asistencia' => 0.75,
                'computa_falta' => false,
                'activo' => true
            ]
        ];

        foreach ($tiposAsistencia as $tipo) {
            TipoAsistencia::updateOrCreate(
                ['codigo' => $tipo['codigo']],
                $tipo
            );
        }

        $this->command->info('Tipos de asistencia creados/actualizados correctamente con factores de asistencia.');
    }
}
