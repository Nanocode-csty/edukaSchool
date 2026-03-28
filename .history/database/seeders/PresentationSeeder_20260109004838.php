<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\Persona;
use App\Models\Usuario;
use App\Models\InfDocente;
use App\Models\InfEstudiante;
use App\Models\InfRepresentante;
use App\Models\Matricula;
use App\Models\InfCurso;
use App\Models\InfGrado;
use App\Models\InfSeccion;
use App\Models\InfNivel;
use App\Models\InfAsignatura;
use App\Models\CursoAsignatura;
use App\Models\InfAula;
use App\Models\SesionClase;
use App\Models\AsistenciaAsignatura;
use App\Models\TipoAsistencia;
use App\Models\InfAnioLectivo;

class PresentationSeeder extends Seeder
{
    /**
     * Ejecutar el seeder para la presentación del 12 de diciembre
     * Este seeder crea datos coherentes y consistentes para el profesor ID 27
     */
    public function run(): void
    {
        $this->command->info('=== INICIANDO PREPARACIÓN DE DATOS PARA PRESENTACIÓN ===');
        $this->command->info('Fecha de presentación: 12 de diciembre de ' . date('Y'));
        $this->command->info('Profesor principal: ID 27');

        // Paso 1: Configurar el año académico actual
        $this->configurarAnioAcademico();

        // Paso 2: Preparar datos del profesor 27
        $this->prepararProfesor27();

        // Paso 3: Crear cursos y estudiantes para el profesor
        $this->crearCursosYEstudiantes();

        // Paso 4: Crear sesiones de clase para la semana actual
        $this->crearSesionesClase();

        // Paso 5: Generar datos de asistencia realistas
        $this->generarDatosAsistencia();

        // Paso 6: Crear datos para representantes
        $this->crearDatosRepresentantes();

        $this->command->info('=== PREPARACIÓN COMPLETADA EXITOSAMENTE ===');
        $this->command->info('El sistema de asistencias está listo para la presentación.');
    }

    /**
     * Configura el año académico actual
     */
    private function configurarAnioAcademico()
    {
        $this->command->info('Configurando año académico...');

        $anioActual = date('Y');
        $fechaInicio = Carbon::create($anioActual, 3, 1); // Inicio en marzo
        $fechaFin = Carbon::create($anioActual, 12, 20); // Fin en diciembre

        $anioLectivo = InfAnioLectivo::firstOrCreate(
            ['nombre' => $anioActual . '-' . ($anioActual + 1)],
            [
                'fecha_inicio' => $fechaInicio,
                'fecha_fin' => $fechaFin,
                'estado' => 'Activo',
                'descripcion' => 'Año académico ' . $anioActual . '-' . ($anioActual + 1)
            ]
        );

        $this->command->info('✓ Año académico configurado: ' . $anioLectivo->nombre);
        $this->command->info('  Inicio: ' . $fechaInicio->format('d/m/Y'));
        $this->command->info('  Fin: ' . $fechaFin->format('d/m/Y'));
    }

    /**
     * Prepara los datos del profesor con ID 27
     */
    private function prepararProfesor27()
    {
        $this->command->info('Preparando datos del profesor ID 27...');

        $docente = InfDocente::find(27);
        if (!$docente) {
            $this->command->error('Profesor con ID 27 no encontrado');
            return;
        }

        $this->command->info('✓ Profesor encontrado: ' . $docente->persona->nombres . ' ' . $docente->persona->apellidos);

        // Asegurar que tenga usuario y rol de docente
        if (!$docente->persona->usuario) {
            $usuario = Usuario::firstOrCreate(
                ['persona_id' => $docente->persona->id_persona],
                [
                    'username' => 'docente27',
                    'password_hash' => Hash::make('password'),
                    'email' => $docente->persona->email,
                    'estado' => 'Activo',
                    'foto_url' => null
                ]
            );

            // Asignar rol de docente
            $rolDocente = \App\Models\Rol::where('nombre', 'Docente')->first();
            if ($rolDocente) {
                $usuario->roles()->sync([$rolDocente->id]);
            }

            $this->command->info('✓ Usuario creado para docente: docente27 / password');
        }
    }

    /**
     * Crea cursos y estudiantes para el profesor 27
     */
    private function crearCursosYEstudiantes()
    {
        $this->command->info('Creando cursos y estudiantes para el profesor 27...');

        $docente = InfDocente::find(27);
        $anioLectivo = InfAnioLectivo::where('estado', 'Activo')->first();

        // Obtener grados de primaria disponibles (1° a 6°)
        $gradosPrimaria = InfGrado::whereIn('nombre', ['1°', '2°', '3°', '4°', '5°', '6°'])
            ->orderBy('grado_id')
            ->take(6)
            ->get();

        if ($gradosPrimaria->isEmpty()) {
            // Si no hay grados específicos, usar los primeros grados disponibles
            $gradosPrimaria = InfGrado::orderBy('grado_id')->take(6)->get();
        }

        // Asignaturas básicas
        $asignaturas = [
            ['nombre' => 'Matemáticas', 'codigo' => 'MAT'],
            ['nombre' => 'Lenguaje', 'codigo' => 'LEN'],
            ['nombre' => 'Ciencias Naturales', 'codigo' => 'CIE'],
            ['nombre' => 'Historia', 'codigo' => 'HIS'],
            ['nombre' => 'Educación Física', 'codigo' => 'EFI'],
        ];

        $cursosCreados = 0;
        $estudiantesCreados = 0;

        foreach ($gradosPrimaria as $grado) {
            // Crear sección A para cada grado
            $seccion = InfSeccion::firstOrCreate(
                ['nombre' => 'A'],
                ['descripcion' => 'Sección A']
            );

            // Crear curso
            $curso = InfCurso::firstOrCreate(
                [
                    'grado_id' => $grado->grado_id,
                    'seccion_id' => $seccion->seccion_id,
                    'ano_lectivo_id' => $anioLectivo->ano_lectivo_id
                ],
                [
                    'cupo_maximo' => 25,
                    'estado' => 'Activo'
                ]
            );

            // Asignar 2-3 asignaturas al docente en este curso
            $asignaturasParaCurso = array_slice($asignaturas, 0, rand(2, 3));

            foreach ($asignaturasParaCurso as $asignaturaData) {
                $asignatura = InfAsignatura::firstOrCreate(
                    ['codigo' => $asignaturaData['codigo']],
                    $asignaturaData
                );

                $cursoAsignatura = CursoAsignatura::firstOrCreate(
                    [
                        'curso_id' => $curso->curso_id,
                        'asignatura_id' => $asignatura->asignatura_id,
                        'profesor_id' => $docente->profesor_id
                    ],
                    [
                        'horas_semanales' => 2,
                        'dia_semana' => collect(['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'])->random(),
                        'hora_inicio' => collect(['08:00', '09:00', '10:00', '11:00'])->random(),
                        'hora_fin' => collect(['09:00', '10:00', '11:00', '12:00'])->random()
                    ]
                );

                $cursosCreados++;

                // Crear estudiantes para este curso (15-20 por curso)
                $numEstudiantes = rand(15, 20);
                for ($i = 1; $i <= $numEstudiantes; $i++) {
                    $this->crearEstudianteParaCurso($curso, $i);
                    $estudiantesCreados++;
                }
            }
        }

        $this->command->info("✓ Cursos asignados al profesor: {$cursosCreados}");
        $this->command->info("✓ Estudiantes creados: {$estudiantesCreados}");
    }

    /**
     * Crea un estudiante para un curso específico
     */
    private function crearEstudianteParaCurso($curso, $numero)
    {
        $dni = 'EST' . str_pad($numero, 3, '0', STR_PAD_LEFT) . $curso->curso_id;

        // Verificar si ya existe un estudiante con este DNI
        $personaExistente = Persona::where('dni', $dni)->first();

        if ($personaExistente) {
            // Si ya existe, verificar si ya está matriculado en este curso
            $estudiante = $personaExistente->estudiante;
            if ($estudiante) {
                $matriculaExistente = Matricula::where('estudiante_id', $estudiante->estudiante_id)
                    ->where('idGrado', $curso->grado_id)
                    ->where('idSeccion', $curso->seccion_id)
                    ->first();

                if ($matriculaExistente) {
                    // Ya existe matrícula, continuar
                    return;
                }
            }
        }

        // Crear persona del estudiante
        $persona = Persona::firstOrCreate(
            ['dni' => $dni],
            [
                'nombres' => 'Estudiante ' . $numero,
                'apellido_paterno' => 'del ' . $curso->grado->nombre,
                'apellido_materno' => $curso->seccion->nombre,
                'fecha_nacimiento' => Carbon::now()->subYears(rand(6, 12))->subDays(rand(0, 365)),
                'genero' => collect(['M', 'F'])->random(),
                'direccion' => 'Dirección de prueba ' . $numero,
                'telefono' => '999999' . str_pad($numero, 3, '0', STR_PAD_LEFT),
                'email' => $this->generarEmailEstudianteUnico($numero),
                'estado' => 'Activo'
            ]
        );

        // Crear estudiante si no existe
        $estudiante = InfEstudiante::where('persona_id', $persona->id_persona)->first();

        if (!$estudiante) {
            // Generar código único para el estudiante
            $codigoEstudiante = $this->generarCodigoEstudianteUnico();
            $estudiante = InfEstudiante::create([
                'persona_id' => $persona->id_persona,
                'codigo_estudiante' => $codigoEstudiante,
                'estado' => 'Activo'
            ]);
        }

        // Crear matrícula si no existe
        $numeroMatricula = $this->generarNumeroMatriculaUnico();
        Matricula::firstOrCreate(
            [
                'estudiante_id' => $estudiante->estudiante_id,
                'idGrado' => $curso->grado_id,
                'idSeccion' => $curso->seccion_id
            ],
            [
                'fecha_matricula' => Carbon::now()->startOfYear(),
                'estado' => 'Matriculado',
                'numero_matricula' => $numeroMatricula,
                'anio_academico' => date('Y')
            ]
        );

        // Crear usuario para el estudiante (opcional)
        Usuario::firstOrCreate(
            ['persona_id' => $persona->id_persona],
            [
                'username' => $this->generarUsernameEstudianteUnico($numero),
                'password_hash' => Hash::make('password'),
                'email' => $persona->email,
                'estado' => 'Activo'
            ]
        );
    }

    /**
     * Genera un código único para estudiante
     */
    private function generarCodigoEstudianteUnico()
    {
        do {
            $codigo = 'EST' . date('Y') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (InfEstudiante::where('codigo_estudiante', $codigo)->exists());

        return $codigo;
    }

    /**
     * Genera un número de matrícula único
     */
    private function generarNumeroMatriculaUnico()
    {
        do {
            $numero = 'M-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (Matricula::where('numero_matricula', $numero)->exists());

        return $numero;
    }

    /**
     * Genera un username único para estudiante
     */
    private function generarUsernameEstudianteUnico($numero)
    {
        do {
            $username = 'estudiante' . rand(1, 9999);
        } while (Usuario::where('username', $username)->exists());

        return $username;
    }

    /**
     * Genera un email único para estudiante
     */
    private function generarEmailEstudianteUnico($numero)
    {
        do {
            $email = 'estudiante' . rand(1, 9999) . '@educa.com';
        } while (Persona::where('email', $email)->exists());

        return $email;
    }

    /**
     * Crea sesiones de clase para la semana actual
     */
    private function crearSesionesClase()
    {
        $this->command->info('Creando sesiones de clase para la semana actual...');

        $docente = InfDocente::find(27);
        $cursosAsignatura = CursoAsignatura::where('profesor_id', $docente->profesor_id)->get();

        // Crear aulas si no existen
        $aulas = $this->crearAulasBasicas();

        $sesionesCreadas = 0;

        // Crear sesiones para los próximos 7 días (incluyendo hoy)
        for ($dia = 0; $dia < 7; $dia++) {
            $fecha = Carbon::now()->addDays($dia);

            // Solo días de semana
            if ($fecha->isWeekend()) continue;

            foreach ($cursosAsignatura as $cursoAsignatura) {
                // Crear máximo 1 sesión por día por asignatura
                $sesionExistente = SesionClase::where('curso_asignatura_id', $cursoAsignatura->curso_asignatura_id)
                    ->where('fecha', $fecha->format('Y-m-d'))
                    ->first();

                if (!$sesionExistente) {
                    $aula = $aulas->random();

                    SesionClase::create([
                        'curso_asignatura_id' => $cursoAsignatura->curso_asignatura_id,
                        'fecha' => $fecha->format('Y-m-d'),
                        'hora_inicio' => $cursoAsignatura->hora_inicio ?: '08:00',
                        'hora_fin' => $cursoAsignatura->hora_fin ?: '09:30',
                        'aula_id' => $aula->aula_id,
                        'estado' => 'Programada',
                        'observaciones' => 'Sesión creada para presentación'
                    ]);

                    $sesionesCreadas++;
                }
            }
        }

        $this->command->info("✓ Sesiones de clase creadas: {$sesionesCreadas}");
    }

    /**
     * Crea aulas básicas si no existen
     */
    private function crearAulasBasicas()
    {
        $aulasData = [
            ['nombre' => 'Aula 101', 'capacidad' => 25, 'ubicacion' => 'Primer piso'],
            ['nombre' => 'Aula 102', 'capacidad' => 25, 'ubicacion' => 'Primer piso'],
            ['nombre' => 'Aula 201', 'capacidad' => 30, 'ubicacion' => 'Segundo piso'],
            ['nombre' => 'Aula 202', 'capacidad' => 30, 'ubicacion' => 'Segundo piso'],
        ];

        $aulas = collect();
        foreach ($aulasData as $data) {
            $aula = InfAula::firstOrCreate(
                ['nombre' => $data['nombre']],
                $data
            );
            $aulas->push($aula);
        }

        return $aulas;
    }

    /**
     * Genera datos de asistencia realistas
     */
    private function generarDatosAsistencia()
    {
        $this->command->info('Generando datos de asistencia realistas...');

        $docente = InfDocente::find(27);
        $cursosAsignatura = CursoAsignatura::where('profesor_id', $docente->profesor_id)->get();

        // Tipos de asistencia
        $tipoPresente = TipoAsistencia::firstOrCreate(['codigo' => 'P'], [
            'nombre' => 'Presente', 'factor_asistencia' => 1.0, 'computa_falta' => 0
        ]);
        $tipoAusente = TipoAsistencia::firstOrCreate(['codigo' => 'A'], [
            'nombre' => 'Ausente', 'factor_asistencia' => 0.0, 'computa_falta' => 1
        ]);
        $tipoTarde = TipoAsistencia::firstOrCreate(['codigo' => 'T'], [
            'nombre' => 'Tarde', 'factor_asistencia' => 0.5, 'computa_falta' => 0
        ]);
        $tipoJustificado = TipoAsistencia::firstOrCreate(['codigo' => 'J'], [
            'nombre' => 'Justificado', 'factor_asistencia' => 1.0, 'computa_falta' => 0
        ]);

        $registrosCreados = 0;

        // Generar asistencia para los últimos 30 días escolares
        for ($dia = 30; $dia >= 0; $dia--) {
            $fecha = Carbon::now()->subDays($dia);

            // Solo días de semana
            if ($fecha->isWeekend()) continue;

            foreach ($cursosAsignatura as $cursoAsignatura) {
                // Obtener estudiantes del curso
                $estudiantes = Matricula::where('idGrado', $cursoAsignatura->curso->grado_id)
                    ->where('idSeccion', $cursoAsignatura->curso->seccion_id)
                    ->where('estado', 'Activo')
                    ->with('estudiante')
                    ->get();

                foreach ($estudiantes as $matricula) {
                    // Verificar si ya existe asistencia para esta fecha y asignatura
                    $asistenciaExistente = AsistenciaAsignatura::where('matricula_id', $matricula->matricula_id)
                        ->where('curso_asignatura_id', $cursoAsignatura->curso_asignatura_id)
                        ->where('fecha', $fecha->format('Y-m-d'))
                        ->first();

                    if (!$asistenciaExistente) {
                        // Generar asistencia realista (85% presente, 10% ausente, 3% tarde, 2% justificado)
                        $rand = rand(1, 100);
                        if ($rand <= 85) {
                            $tipoAsistencia = $tipoPresente;
                        } elseif ($rand <= 95) {
                            $tipoAsistencia = $tipoAusente;
                        } elseif ($rand <= 98) {
                            $tipoAsistencia = $tipoTarde;
                        } else {
                            $tipoAsistencia = $tipoJustificado;
                        }

                        AsistenciaAsignatura::create([
                            'matricula_id' => $matricula->matricula_id,
                            'curso_asignatura_id' => $cursoAsignatura->curso_asignatura_id,
                            'fecha' => $fecha->format('Y-m-d'),
                            'tipo_asistencia_id' => $tipoAsistencia->id,
                            'hora_registro' => Carbon::now()->format('H:i:s'),
                            'justificado' => $tipoAsistencia->codigo === 'J',
                            'observaciones' => null,
                            'estado' => 'Activo',
                            'usuario_registro' => 1 // Admin por defecto
                        ]);

                        $registrosCreados++;
                    }
                }
            }
        }

        $this->command->info("✓ Registros de asistencia creados: {$registrosCreados}");
    }

    /**
     * Crea datos para representantes
     */
    private function crearDatosRepresentantes()
    {
        $this->command->info('Creando datos para representantes...');

        // Obtener algunos estudiantes para asignar representantes
        $estudiantes = InfEstudiante::take(50)->get();

        $representantesCreados = 0;

        foreach ($estudiantes as $estudiante) {
            // Crear representante si no existe
            $dniRepresentante = 'REP' . str_pad($representantesCreados + 1, 6, '0', STR_PAD_LEFT);

            $personaRepresentante = Persona::firstOrCreate(
                ['dni' => $dniRepresentante],
                [
                    'nombres' => 'Representante',
                    'apellidos' => 'de ' . $estudiante->persona->apellidos,
                    'fecha_nacimiento' => Carbon::now()->subYears(rand(25, 45)),
                    'genero' => collect(['M', 'F'])->random(),
                    'direccion' => 'Dirección representante',
                    'telefono' => '988888' . str_pad($representantesCreados + 1, 3, '0', STR_PAD_LEFT),
                    'email' => 'representante' . ($representantesCreados + 1) . '@educa.com',
                    'estado' => 'Activo'
                ]
            );

            $representante = InfRepresentante::firstOrCreate(
                ['persona_id' => $personaRepresentante->id_persona],
                [
                    'codigo_representante' => 'REP' . date('Y') . str_pad($representantesCreados + 1, 4, '0', STR_PAD_LEFT),
                    'estado' => 'Activo'
                ]
            );

            // Asignar estudiante al representante
            DB::table('estudianterepresentante')->updateOrInsert(
                [
                    'estudiante_id' => $estudiante->estudiante_id,
                    'representante_id' => $representante->representante_id
                ],
                ['parentesco' => 'Padre/Madre']
            );

            // Crear usuario para representante
            Usuario::firstOrCreate(
                ['persona_id' => $personaRepresentante->id_persona],
                [
                    'username' => 'representante' . ($representanteCreados + 1),
                    'password_hash' => Hash::make('password'),
                    'email' => $personaRepresentante->email,
                    'estado' => 'Activo'
                ]
            );

            $representantesCreados++;
        }

        $this->command->info("✓ Representantes creados: {$representantesCreados}");

        // Asignar roles de representante
        $rolRepresentante = \App\Models\Rol::where('nombre', 'representante')->first();
        if ($rolRepresentante) {
            $representantes = InfRepresentante::with('persona.usuario')->get();
            foreach ($representantes as $rep) {
                if ($rep->persona->usuario) {
                    $rep->persona->usuario->roles()->sync([$rolRepresentante->id]);
                }
            }
        }
    }
}
