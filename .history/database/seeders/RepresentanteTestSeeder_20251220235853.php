<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Persona;
use App\Models\User;
use App\Models\Rol;
use App\Models\InfRepresentante;
use App\Models\InfEstudiante;

class RepresentanteTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar si ya existe un representante de prueba
        $usuarioExistente = \App\Models\Usuario::where('username', 'representante')->first();

        if ($usuarioExistente) {
            $this->command->info('Usuario representante encontrado, actualizando relaciones...');
        }

        // Crear o actualizar persona
        $persona = Persona::updateOrCreate(
            ['dni' => '77777777'],
            [
                'nombres' => 'María Elena',
                'apellidos' => 'Rodríguez Sánchez',
                'fecha_nacimiento' => '1975-03-15',
                'genero' => 'F',
                'telefono' => '988888888',
                'email' => 'representante@eduka.com',
                'direccion' => 'Lima, Perú'
            ]
        );

        // Actualizar usuario existente o crear uno nuevo
        if ($usuarioExistente) {
            $usuario = $usuarioExistente;
            $usuario->update([
                'persona_id' => $persona->id_persona,
                'email' => 'representante@eduka.com',
                'estado' => 'Activo'
            ]);
        } else {
            $usuario = \App\Models\Usuario::create([
                'persona_id' => $persona->id_persona,
                'username' => 'representante',
                'email' => 'representante@eduka.com',
                'password_hash' => bcrypt('password'),
                'estado' => 'Activo',
                'google_id' => null,
                'google_token' => null,
                'ultima_sesion' => null,
                'cambio_password_requerido' => false
            ]);
        }

        // Asignar rol representante
        $rolRepresentante = Rol::where('nombre', 'Representante')->first();
        if ($rolRepresentante) {
            $persona->roles()->attach($rolRepresentante->id, ['fecha_asignacion' => now()]);
        }

        // Crear representante
        $representante = InfRepresentante::firstOrCreate(
            ['persona_id' => $persona->id],
            [
                'parentesco' => 'Madre',
                'ocupacion' => 'Profesora'
            ]
        );

        // Asignar estudiantes al representante
        $this->asignarEstudiantesAlRepresentante($representante);

        $this->command->info('Usuario representante de prueba creado exitosamente:');
        $this->command->info('Usuario: representante');
        $this->command->info('Contraseña: password');
        $this->command->info('Email: representante@eduka.com');
        $this->command->info('Representante ID: ' . $representante->id);
    }

    private function asignarEstudiantesAlRepresentante($representante)
    {
        // Buscar estudiantes existentes y asignar algunos al representante
        $estudiantes = InfEstudiante::take(3)->get();

        if ($estudiantes->isEmpty()) {
            $this->command->info('No hay estudiantes para asignar al representante. Creando estudiantes de prueba...');
            // Crear estudiantes de prueba si no existen
            $this->crearEstudiantesDePrueba();
            $estudiantes = InfEstudiante::take(3)->get();
        }

        foreach ($estudiantes as $estudiante) {
            // Verificar si ya existe la relación
            $existeRelacion = \DB::table('estudiante_representante')
                ->where('estudiante_id', $estudiante->estudiante_id)
                ->where('representante_id', $representante->representante_id)
                ->exists();

            if (!$existeRelacion) {
                \DB::table('estudiante_representante')->insert([
                    'estudiante_id' => $estudiante->estudiante_id,
                    'representante_id' => $representante->representante_id,
                    'parentesco' => 'Madre',
                    'es_representante_principal' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                $this->command->info("Estudiante {$estudiante->nombres} {$estudiante->apellidos} asignado al representante");
            }
        }
    }

    private function crearEstudiantesDePrueba()
    {
        // Crear algunas personas para estudiantes
        $personasEstudiantes = [
            ['nombres' => 'Juan', 'apellidos' => 'Pérez López', 'dni' => '11111111'],
            ['nombres' => 'María', 'apellidos' => 'García Rodríguez', 'dni' => '22222222'],
            ['nombres' => 'Carlos', 'apellidos' => 'López Martínez', 'dni' => '33333333'],
        ];

        foreach ($personasEstudiantes as $datos) {
            $persona = Persona::firstOrCreate(
                ['dni' => $datos['dni']],
                array_merge($datos, [
                    'fecha_nacimiento' => '2010-01-01',
                    'genero' => 'M',
                    'telefono' => '999999999',
                    'email' => strtolower($datos['nombres']) . '@eduka.com',
                    'direccion' => 'Lima, Perú'
                ])
            );

            // Crear estudiante
            InfEstudiante::firstOrCreate(
                ['persona_id' => $persona->id_persona],
                [
                    'dni' => $datos['dni'],
                    'nombres' => $datos['nombres'],
                    'apellidos' => $datos['apellidos'],
                    'fecha_nacimiento' => '2010-01-01',
                    'estado' => 'Activo'
                ]
            );
        }
    }
}
