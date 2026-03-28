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

        // Assign roles to personas using existing table structure
        $roleAssignments = [
            '12345678' => $estudianteRole->id_rol, // Juan Carlos García - Estudiante
            '87654321' => $estudianteRole->id_rol, // María José Rodríguez - Estudiante
            '11223344' => $estudianteRole->id_rol, // Pedro Antonio Martínez - Estudiante
            '44556677' => $docenteRole->id_rol, // Ana María Fernández - Docente
            '55667788' => $docenteRole->id_rol, // Carlos Alberto Díaz - Docente
            '66778899' => $representanteRole->id_rol, // Rosa Elena Vargas - Representante
            '77889900' => $representanteRole->id_rol, // Luis Miguel Castro - Representante
            '88990011' => $administradorRole->id_rol, // Patricia Isabel Morales - Administrador
        ];

        foreach ($personas as $persona) {
            if (isset($roleAssignments[$persona->dni])) {
                \DB::table('persona_roles')->insert([
                    'id_persona' => $persona->persona_id,
                    'id_rol' => $roleAssignments[$persona->dni],
                    'fecha_asignacion' => now()->toDateString()
                ]);
            }
        }
    }
}
