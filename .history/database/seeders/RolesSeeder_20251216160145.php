<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'nombre' => 'estudiante',
                'descripcion' => 'Estudiante del colegio'
            ],
            [
                'nombre' => 'docente',
                'descripcion' => 'Profesor del colegio'
            ],
            [
                'nombre' => 'representante',
                'descripcion' => 'Representante o apoderado de estudiante'
            ],
            [
                'nombre' => 'administrador',
                'descripcion' => 'Administrador del sistema'
            ],
            [
                'nombre' => 'secretaria',
                'descripcion' => 'Secretaria administrativa'
            ]
        ];

        foreach ($roles as $rol) {
            \App\Models\Rol::firstOrCreate(
                ['nombre' => $rol['nombre']],
                $rol
            );
        }
    }
}
