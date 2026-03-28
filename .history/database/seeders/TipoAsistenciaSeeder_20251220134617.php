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
