<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Persona;
use App\Models\Rol;
use Illuminate\Support\Facades\DB;

class FixUserRolesSeeder extends Seeder
{
    public function run()
    {
        echo "=== CORRIENDO FIX USER ROLES SEEDER ===\n";

        // Obtener todos los usuarios
        $usuarios = User::all();
        echo "Total usuarios encontrados: " . $usuarios->count() . "\n";

        foreach ($usuarios as $usuario) {
            echo "\nProcesando usuario: {$usuario->username}\n";

            // Verificar si el usuario tiene persona asignada
            if (!$usuario->persona_id) {
                echo "  ❌ Usuario sin persona asignada\n";

                // Buscar persona por email o crear una básica
                $persona = Persona::where('email', $usuario->email)->first();

                if (!$persona) {
                    echo "  📝 Creando persona básica para usuario\n";
                    $persona = Persona::create([
                        'dni' => '00000000',
                        'nombres' => explode('@', $usuario->email)[0] ?? 'Usuario',
                        'apellidos' => 'Sistema',
                        'fecha_nacimiento' => now()->subYears(25),
                        'genero' => 'M',
                        'direccion' => 'Sin especificar',
                        'telefono' => null,
                        'email' => $usuario->email,
                        'estado' => 'Activo'
                    ]);
                }

                $usuario->persona_id = $persona->id_persona;
                $usuario->save();
                echo "  ✅ Persona asignada\n";
            }

            // Limpiar roles existentes y reasignar correctamente
            DB::table('persona_roles')
                ->where('id_persona', $usuario->persona_id)
                ->delete();

            echo "  🔄 Limpiando roles existentes y reasignando...\n";

            // Asignar rol basado en el nombre de usuario o email
            $rolNombre = $this->determinarRol($usuario);

            $rol = Rol::where('nombre', $rolNombre)->first();

            if (!$rol) {
                echo "  ❌ Rol '{$rolNombre}' no encontrado, omitiendo creación...\n";
                continue;
            }

            // Asignar rol a la persona
            DB::table('persona_roles')->insert([
                'id_persona' => $usuario->persona_id,
                'id_rol' => $rol->id_rol,
                'fecha_asignacion' => now()
            ]);

            echo "  ✅ Rol '{$rolNombre}' asignado\n";
        }

        echo "\n=== VERIFICACIÓN FINAL ===\n";

        // Verificar resultados
        $usuariosConRoles = User::with('persona.roles')->get();
        foreach ($usuariosConRoles as $usuario) {
            $rol = $usuario->rol ?? 'SIN ROL';
            echo "{$usuario->username}: {$rol}\n";
        }

        echo "\n=== PROCESO COMPLETADO ===\n";
    }

    private function determinarRol(User $usuario): string
    {
        $username = strtolower($usuario->username);
        $email = strtolower($usuario->email ?? '');

        // Lógica para determinar rol basado en el nombre de usuario
        if (str_contains($username, 'admin') || str_contains($email, 'admin')) {
            return 'Administrador';
        }

        if (str_contains($username, 'prof') || str_contains($username, 'docente') || str_contains($email, 'prof')) {
            return 'Profesor';
        }

        if (str_contains($username, 'rep') || str_contains($username, 'representante') || str_contains($email, 'rep')) {
            return 'Representante';
        }

        // Por defecto, asignar como Administrador para usuarios existentes
        return 'Administrador';
    }
}
