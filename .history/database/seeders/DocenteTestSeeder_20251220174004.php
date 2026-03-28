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
        $usuarioExistente = User::where('username', 'docente')->first();

        if ($usuarioExistente) {
            $this->command->info('Usuario docente ya existe: ' . $usuarioExistente->username);
            return;
        }

        // Crear persona
        $persona = Persona::create([
            'nombres' => 'Juan Carlos',
            'apellidos' => 'Pérez García',
            'dni' => '66666666',
            'fecha_nacimiento' => '1980-01-01',
            'genero' => 'M',
            'telefono' => '987654321',
            'email' => 'docente@eduka.com',
            'direccion' => 'Lima, Perú'
        ]);

        // Crear usuario
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

        // Asignar rol docente
        $rolDocente = Rol::where('nombre', 'Docente')->first();
        if ($rolDocente) {
            $persona->roles()->attach($rolDocente->id, ['fecha_asignacion' => now()]);
        }

        // Crear docente
        $docente = InfDocente::create([
            'persona_id' => $persona->id,
            'codigo_docente' => 'DOC001',
            'especialidad' => 'Matemáticas',
            'estado' => 'Activo',
            'fecha_ingreso' => now()->format('Y-m-d')
        ]);

        $this->command->info('Usuario docente de prueba creado exitosamente:');
        $this->command->info('Usuario: docente');
        $this->command->info('Contraseña: password');
        $this->command->info('Email: docente@eduka.com');
        $this->command->info('Docente ID: ' . $docente->id);
    }
}
