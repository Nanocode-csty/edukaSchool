<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Persona;
use App\Models\Usuario;
use App\Models\InfRepresentante;
use App\Models\InfEstudiante;
use App\Models\InfEstudianteRepresentante;
use App\Models\Notificacion;
use App\Models\Matricula;
use App\Models\InfCurso;
use App\Models\InfGrado;
use App\Models\InfSeccion;

class RepresentanteNotificationsSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('Creando datos para representante ID 26 y mejorando sistema de notificaciones...');

        // Buscar o crear representante con ID específico (26)
        $representante = InfRepresentante::find(26);

        if (!$representante) {
            // Crear persona para el representante
            $personaRepresentante = Persona::create([
                'dni' => '87654321',
                'nombres' => 'María Elena',
                'apellidos' => 'García López',
                'fecha_nacimiento' => '1980-05-15',
                'genero' => 'F',
                'direccion' => 'Av. Principal 123, Lima',
                'telefono' => '987654321',
                'email' => 'representante26@eduka.com',
                'estado' => 'Activo'
            ]);

            // Crear representante con ID específico
            $representante = InfRepresentante::create([
                'representante_id' => 26,
                'persona_id' => $personaRepresentante->id_persona,
                'ocupacion' => 'Profesora',
                'parentesco' => 'Madre',
                'fecha_registro' => now()
            ]);

            // Crear usuario para el representante
            $usuario = Usuario::create([
                'username' => 'representante26',
                'password_hash' => Hash::make('password'),
                'nombres' => 'María Elena',
                'apellidos' => 'García López',
                'email' => 'representante26@eduka.com',
                'rol' => 'Representante',
                'estado' => 'Activo',
                'cambio_password_requerido' => 0,
                'persona_id' => $personaRepresentante->id_persona
            ]);

            // Asignar rol de representante al usuario
            DB::table('persona_roles')->insert([
                'persona_id' => $personaRepresentante->id_persona,
                'rol_id' => DB::table('roles')->where('nombre', 'representante')->first()->rol_id,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $this->command->info('Representante ID 26 creado exitosamente');
        } else {
            $usuario = Usuario::where('persona_id', $representante->persona_id)->first();
            $this->command->info('Representante ID 26 ya existe');
        }

        // Crear estudiantes para el representante
        $this->crearEstudiantesParaRepresentante($representante, $usuario);

        // Crear notificaciones de prueba
        $this->crearNotificacionesDePrueba($usuario);

        $this->command->info('Datos poblados exitosamente para representante ID 26');
    }

    private function crearEstudiantesParaRepresentante($representante, $usuario)
    {
        $estudiantesData = [
            [
                'dni' => '12345678',
                'nombres' => 'Juan Carlos',
                'apellidos' => 'García Pérez',
                'fecha_nacimiento' => '2010-03-10',
                'telefono' => '987654322'
            ],
            [
                'dni' => '12345679',
                'nombres' => 'Ana María',
                'apellidos' => 'García Pérez',
                'fecha_nacimiento' => '2012-07-20',
                'telefono' => '987654323'
            ],
            [
                'dni' => '12345680',
                'nombres' => 'Carlos Alberto',
                'apellidos' => 'García Pérez',
                'fecha_nacimiento' => '2014-11-05',
                'telefono' => '987654324'
            ]
        ];

