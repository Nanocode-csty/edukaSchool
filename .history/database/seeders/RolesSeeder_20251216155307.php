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
                'descripcion' => 'Estudiante del colegio',
                'estado' => 'Activo'
            ],
            [
                'nombre' => 'docente',
                'descripcion' => 'Profesor del colegio',
                'estado' => 'Activo'
            ],
            [
                'nombre' => 'representante',
                'descripcion' => 'Representante o apoderado de estudiante',
                'estado' => 'Activo'
            ],
            [
                'nombre' => 'administrador',
                'descripcion' => 'Administrador del sistema',
                'estado' => 'Activo'
            ],
            [
                'nombre' => 'secretaria',
                'descripcion' => 'Secretaria administrativa',
                'estado' => 'Activo'
            ]
        ];

        foreach ($roles as $rol) {
            \App\Models\Rol::create($rol);
        }
    }
}
