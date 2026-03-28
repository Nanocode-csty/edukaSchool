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

        foreach ($estudiantesData as $estudianteData) {
            // Crear persona para el estudiante
            $personaEstudiante = Persona::firstOrCreate(
                ['dni' => $estudianteData['dni']],
                [
                    'nombres' => $estudianteData['nombres'],
                    'apellidos' => $estudianteData['apellidos'],
                    'fecha_nacimiento' => $estudianteData['fecha_nacimiento'],
                    'genero' => 'M',
                    'direccion' => 'Av. Principal 123, Lima',
                    'telefono' => $estudianteData['telefono'],
                    'email' => strtolower(str_replace(' ', '.', $estudianteData['nombres'])) . '@example.com',
                    'estado' => 'Activo'
                ]
            );

            // Crear estudiante
            $estudiante = InfEstudiante::firstOrCreate(
                ['persona_id' => $personaEstudiante->id_persona],
                [
                    'codigo_estudiante' => 'EST' . str_pad($personaEstudiante->id_persona, 6, '0', STR_PAD_LEFT),
                    'fecha_matricula' => now()
                ]
            );

            // Relacionar estudiante con representante
            InfEstudianteRepresentante::firstOrCreate(
                [
                    'estudiante_id' => $estudiante->estudiante_id,
                    'representante_id' => $representante->representante_id
                ],
                [
                    'es_principal' => true,
                    'viveConEstudiante' => 'Si'
                ]
            );

            // Crear matrícula para el estudiante (si no existe)
            $this->crearMatriculaParaEstudiante($estudiante);

            $this->command->info("Estudiante {$estudianteData['nombres']} {$estudianteData['apellidos']} asignado al representante");
        }
    }

    private function crearMatriculaParaEstudiante($estudiante)
    {
        // Buscar curso disponible
        $curso = InfCurso::first();

        if (!$curso) {
            // Crear un curso básico si no existe ninguno
            $grado = InfGrado::first();
            $seccion = InfSeccion::first();

            if (!$grado) {
                $grado = InfGrado::create([
                    'nombre' => 'Primero',
                    'nivel_id' => 1,
                    'estado' => 'Activo'
                ]);
            }

            if (!$seccion) {
                $seccion = InfSeccion::create([
                    'nombre' => 'A',
                    'estado' => 'Activo'
                ]);
            }

            $curso = InfCurso::create([
                'grado_id' => $grado->grado_id,
                'seccion_id' => $seccion->seccion_id,
                'ano_lectivo_id' => 1,
                'estado' => 'Activo'
            ]);
        }

        // Crear matrícula
        Matricula::firstOrCreate(
            ['estudiante_id' => $estudiante->estudiante_id],
            [
                'curso_id' => $curso->curso_id,
                'numero_matricula' => 'MAT' . str_pad($estudiante->estudiante_id, 6, '0', STR_PAD_LEFT),
                'fecha_matricula' => now(),
                'estado' => 'Matriculado',
                'idGrado' => $curso->grado_id,
                'idSeccion' => $curso->seccion_id
            ]
        );
    }

    private function crearNotificacionesDePrueba($usuario)
    {
        // Limpiar notificaciones existentes para este usuario
        Notificacion::where('usuario_id', $usuario->usuario_id)->delete();

        // Crear notificaciones de bienvenida y recordatorios
        $notificaciones = [
            [
                'titulo' => '¡Bienvenido al Sistema Educativo!',
                'mensaje' => 'Tu cuenta ha sido activada exitosamente. Ahora puedes acceder a las calificaciones, asistencias y reportes de tus estudiantes.',
                'tipo' => 'sistema',
                'url_accion' => route('calificaciones.representante'),
                'leido_en' => now() // Marcar como leída
            ],
            [
                'titulo' => 'Recordatorio: Revisar Calificaciones',
                'mensaje' => 'Recuerda revisar las calificaciones de tus estudiantes regularmente. Mantente al tanto de su rendimiento académico.',
                'tipo' => 'recordatorio',
                'url_accion' => route('calificaciones.representante')
            ],
            [
                'titulo' => 'Información sobre Asistencias',
                'mensaje' => 'Puedes consultar las asistencias diarias de tus estudiantes desde la sección correspondiente.',
                'tipo' => 'recordatorio',
                'url_accion' => route('asistencia.representante.index')
            ],
            [
                'titulo' => 'Sistema de Notificaciones Activado',
                'mensaje' => 'Recibirás notificaciones importantes sobre justificaciones, cambios en calificaciones y recordatorios del sistema.',
                'tipo' => 'sistema',
                'url_accion' => route('notificaciones.index')
            ]
        ];

        foreach ($notificaciones as $notifData) {
            Notificacion::create(array_merge($notifData, [
                'usuario_id' => $usuario->usuario_id,
                'datos' => []
            ]));
        }

        $this->command->info('Notificaciones de prueba creadas para el representante');
    }
}