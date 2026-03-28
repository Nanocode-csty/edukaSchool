<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Persona;
use App\Models\User;
use App\Models\Rol;
use App\Models\InfDocente;

class DocenteTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar si ya existe un docente de prueba
        $usuarioExistente = \App\Models\Usuario::where('username', 'docente')->first();

        if ($usuarioExistente) {
            $this->command->info('Usuario docente encontrado, actualizando relaciones...');
        }

        // Crear persona
        $persona = Persona::firstOrCreate(
            ['dni' => '66666666'],
            [
                'nombres' => 'Juan Carlos',
                'apellidos' => 'García López',
                'fecha_nacimiento' => '1980-01-01',
                'genero' => 'M',
                'telefono' => '987654321',
                'email' => 'docente@eduka.com',
                'direccion' => 'Lima, Perú'
            ]
        );

        // Actualizar usuario existente o crear uno nuevo
        if ($usuarioExistente) {
            $usuario = $usuarioExistente;
            $usuario->update([
                'persona_id' => $persona->id,
                'email' => 'docente@eduka.com',
                'estado' => 'Activo'
            ]);
        } else {
            $usuario = \App\Models\Usuario::create([
                'persona_id' => $persona->id,
                'username' => 'docente',
                'email' => 'docente@eduka.com',
                'password_hash' => bcrypt('password'),
                'estado' => 'Activo',
                'google_id' => null,
                'google_token' => null,
                'ultima_sesion' => null,
                'cambio_password_requerido' => false
            ]);
        }

        // Asignar rol docente
        $rolDocente = Rol::where('nombre', 'Docente')->first();
        if ($rolDocente) {
            $persona->roles()->attach($rolDocente->id, ['fecha_asignacion' => now()]);
        }

        // Crear docente
        $docente = InfDocente::firstOrCreate(
            ['persona_id' => $persona->id],
            [
                'especialidad' => 'Matemáticas',
                'fecha_contratacion' => now()->format('Y-m-d'),
                'estado' => 'Activo'
            ]
        );

        $this->command->info('Usuario docente de prueba creado exitosamente:');
        $this->command->info('Usuario: docente');
        $this->command->info('Contraseña: password');
        $this->command->info('Email: docente@eduka.com');
        $this->command->info('Docente ID: ' . $docente->id);
    }
}
