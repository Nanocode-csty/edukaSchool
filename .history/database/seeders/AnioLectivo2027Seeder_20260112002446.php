<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AnioLectivo2027Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear año lectivo 2027 si no existe
        DB::table('anoslectivos')->updateOrInsert(
            ['nombre' => '2027'],
            [
                'fecha_inicio' => '2027-01-01',
                'fecha_fin' => '2027-12-31',
                'estado' => 'Planificación',
                'descripcion' => 'Año lectivo 2027 - En planificación',
                'created_at' => now(),
                'updated_at' => now()
            ]
        );

        $this->command->info('Año lectivo 2027 creado o actualizado exitosamente.');
    }
}
