<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PersonaRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get roles
        $estudianteRole = \App\Models\Rol::where('nombre', 'estudiante')->first();
        $docenteRole = \App\Models\Rol::where('nombre', 'docente')->first();
        $representanteRole = \App\Models\Rol::where('nombre', 'representante')->first();
        $administradorRole = \App\Models\Rol::where('nombre', 'administrador')->first();

        // Get personas
        $personas = \App\Models\Persona::all();

        // Assign roles to personas
        foreach ($personas as $persona) {
            switch ($persona->dni) {
                case '12345678': // Juan Carlos García - Estudiante
                case '87654321': // María José Rodríguez - Estudiante
                case '11223344': // Pedro Antonio Martínez - Estudiante
                    $persona->roles()->attach($estudianteRole->rol_id);
                    break;
                case '44556677': // Ana María Fernández - Docente
                case '55667788': // Carlos Alberto Díaz - Docente
                    $persona->roles()->attach($docenteRole->rol_id);
                    break;
                case '66778899': // Rosa Elena Vargas - Representante
                case '77889900': // Luis Miguel Castro - Representante
                    $persona->roles()->attach($representanteRole->rol_id);
                    break;
                case '88990011': // Patricia Isabel Morales - Administrador
                    $persona->roles()->attach($administradorRole->rol_id);
                    break;
            }
        }
    }
}
