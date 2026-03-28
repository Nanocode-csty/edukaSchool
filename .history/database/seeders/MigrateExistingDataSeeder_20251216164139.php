<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MigrateExistingDataSeeder extends Seeder
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

        // Migrate existing estudiantes without persona_id
        $estudiantesSinPersona = \App\Models\InfEstudiante::whereNull('persona_id')->orWhere('persona_id', 0)->get();

        $nombresPeruanos = [
            'Carlos', 'María', 'José', 'Ana', 'Luis', 'Carmen', 'Pedro', 'Rosa', 'Juan', 'Isabel',
            'Miguel', 'Patricia', 'Jorge', 'Lucía', 'Fernando', 'Elena', 'Ricardo', 'Victoria', 'Diego', 'Sofia'
        ];

        $apellidosPeruanos = [
            'García', 'Rodríguez', 'González', 'Fernández', 'López', 'Martínez', 'Sánchez', 'Pérez', 'Gómez', 'Díaz',
            'Torres', 'Ruiz', 'Hernández', 'Jiménez', 'Morales', 'Ortiz', 'Ramos', 'Castro', 'Vargas', 'Mendoza'
        ];

        $distritosLima = [
            'Miraflores', 'San Isidro', 'Surco', 'La Molina', 'Barranco', 'Jesús María', 'Lince', 'Pueblo Libre',
            'San Miguel', 'Callao', 'Bellavista', 'Cercado de Lima', 'Breña', 'Rimac'
        ];

        foreach ($estudiantesSinPersona as $index => $estudiante) {
            // Generate realistic Peruvian data
            $nombre = $nombresPeruanos[array_rand($nombresPeruanos)];
            $apellidoPaterno = $apellidosPeruanos[array_rand($apellidosPeruanos)];
            $apellidoMaterno = $apellidosPeruanos[array_rand($apellidosPeruanos)];

            // Generate DNI (8 digits)
            $dni = str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT);

            // Generate birth date (between 6 and 18 years ago)
            $birthDate = now()->subYears(rand(6, 18))->subDays(rand(0, 365));

            // Generate address
            $distrito = $distritosLima[array_rand($distritosLima)];
            $direccion = "Jr. " . $nombresPeruanos[array_rand($nombresPeruanos)] . " " . rand(100, 999) . ", " . $distrito . ", Lima";

            // Create persona
            $persona = \App\Models\Persona::create([
                'dni' => $dni,
                'nombres' => $nombre,
                'apellidos' => $apellidoPaterno . ' ' . $apellidoMaterno,
                'fecha_nacimiento' => $birthDate->toDateString(),
                'genero' => rand(0, 1) ? 'M' : 'F',
                'direccion' => $direccion,
                'telefono' => '9' . rand(10000000, 99999999),
                'email' => strtolower($nombre . '.' . $apellidoPaterno . '@colegio.edu.pe'),
                'estado' => 'Activo'
            ]);

            // Update estudiante with persona_id
            $estudiante->update(['persona_id' => $persona->id_persona]);

            // Assign role
            if ($estudianteRole) {
                \DB::table('persona_roles')->updateOrInsert(
                    ['id_persona' => $persona->id_persona, 'id_rol' => $estudianteRole->id_rol],
                    ['fecha_asignacion' => now()->toDateString()]
                );
            }

            // Update estudiante with specific data
            $grados = ['1ro', '2do', '3ro', '4to', '5to', '6to'];
            $secciones = ['A', 'B', 'C', 'D'];

            $estudiante->update([
                'codigo_estudiante' => 'EST' . str_pad($estudiante->estudiante_id, 6, '0', STR_PAD_LEFT),
                'fecha_matricula' => now()->subMonths(rand(1, 12))->toDateString(),
                'grado_actual' => $grados[array_rand($grados)],
                'seccion_actual' => $secciones[array_rand($secciones)],
                'situacion_academica' => 'Regular'
            ]);
        }

        // Migrate existing profesores without persona_id
        $profesoresSinPersona = \App\Models\InfDocente::whereNull('persona_id')->orWhere('persona_id', 0)->get();

        foreach ($profesoresSinPersona as $index => $profesor) {
            $nombre = $nombresPeruanos[array_rand($nombresPeruanos)];
            $apellidoPaterno = $apellidosPeruanos[array_rand($apellidosPeruanos)];
            $apellidoMaterno = $apellidosPeruanos[array_rand($apellidosPeruanos)];

            $dni = str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT);
            $birthDate = now()->subYears(rand(25, 60))->subDays(rand(0, 365));

            $distrito = $distritosLima[array_rand($distritosLima)];
            $direccion = "Av. " . $nombresPeruanos[array_rand($nombresPeruanos)] . " " . rand(100, 999) . ", " . $distrito . ", Lima";

            $persona = \App\Models\Persona::create([
                'dni' => $dni,
                'nombres' => $nombre,
                'apellidos' => $apellidoPaterno . ' ' . $apellidoMaterno,
                'fecha_nacimiento' => $birthDate->toDateString(),
                'genero' => rand(0, 1) ? 'M' : 'F',
                'direccion' => $direccion,
                'telefono' => '9' . rand(10000000, 99999999),
                'email' => strtolower($nombre . '.' . $apellidoPaterno . '@colegio.edu.pe'),
                'estado' => 'Activo'
            ]);

            $profesor->update(['persona_id' => $persona->id_persona]);

            if ($docenteRole) {
                \DB::table('persona_roles')->updateOrInsert(
                    ['id_persona' => $persona->id_persona, 'id_rol' => $docenteRole->id_rol],
                    ['fecha_asignacion' => now()->toDateString()]
                );
            }
        }

        // Migrate existing representantes without persona_id
        $representantesSinPersona = \App\Models\InfRepresentante::whereNull('persona_id')->orWhere('persona_id', 0)->get();

        foreach ($representantesSinPersona as $index => $representante) {
            $nombre = $nombresPeruanos[array_rand($nombresPeruanos)];
            $apellidoPaterno = $apellidosPeruanos[array_rand($apellidosPeruanos)];
            $apellidoMaterno = $apellidosPeruanos[array_rand($apellidosPeruanos)];

            $dni = str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT);
            $birthDate = now()->subYears(rand(25, 70))->subDays(rand(0, 365));

            $distrito = $distritosLima[array_rand($distritosLima)];
            $direccion = "Calle " . $nombresPeruanos[array_rand($nombresPeruanos)] . " " . rand(100, 999) . ", " . $distrito . ", Lima";

            $persona = \App\Models\Persona::create([
                'dni' => $dni,
                'nombres' => $nombre,
                'apellidos' => $apellidoPaterno . ' ' . $apellidoMaterno,
                'fecha_nacimiento' => $birthDate->toDateString(),
                'genero' => rand(0, 1) ? 'M' : 'F',
                'direccion' => $direccion,
                'telefono' => '9' . rand(10000000, 99999999),
                'email' => strtolower($nombre . '.' . $apellidoPaterno . '@gmail.com'),
                'estado' => 'Activo'
            ]);

            $representante->update(['persona_id' => $persona->id_persona]);

            if ($representanteRole) {
                \DB::table('persona_roles')->updateOrInsert(
                    ['id_persona' => $persona->id_persona, 'id_rol' => $representanteRole->id_rol],
                    ['fecha_asignacion' => now()->toDateString()]
                );
            }
        }
    }
}
