<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\Persona;
use App\Models\Usuario;
use App\Models\InfEstudiante;
use App\Models\InfRepresentante;
use App\Models\Matricula;
use App\Models\InfGrado;
use App\Models\InfSeccion;
use App\Models\InfAnioLectivo;

class Preinscritos2026Seeder extends Seeder
{
    /**
     * Ejecutar el seeder para crear 10 estudiantes preinscritos para 2026
     */
    public function run(): void
    {
        $this->command->info('=== CREANDO 10 ESTUDIANTES PREINSCRITOS PARA 2026 ===');

        // Obtener el año académico actual (2026)
        $anioActual = date('Y'); // 2026
        $anioLectivo = InfAnioLectivo::where('estado', 'Activo')->first();

        if (!$anioLectivo) {
            $this->command->error('No se encontró un año lectivo activo');
            return;
        }

        $this->command->info('Año académico: ' . $anioLectivo->nombre);

        // Obtener grados disponibles (preferentemente de primaria)
        $grados = InfGrado::whereIn('nombre', ['1°', '2°', '3°', '4°', '5°', '6°'])
            ->orderBy('grado_id')
            ->get();

        if ($grados->isEmpty()) {
            $grados = InfGrado::orderBy('grado_id')->take(6)->get();
        }

        // Obtener sección A
        $seccion = InfSeccion::firstOrCreate(
            ['nombre' => 'A'],
            ['descripcion' => 'Sección A']
        );

        // Datos de estudiantes preinscritos
        $estudiantesData = [
            ['dni' => 'PRE0012026', 'nombres' => 'Ana', 'apellidos' => 'García López', 'genero' => 'F', 'fecha_nac' => '2015-03-15'],
            ['dni' => 'PRE0022026', 'nombres' => 'Carlos', 'apellidos' => 'Rodríguez Martínez', 'genero' => 'M', 'fecha_nac' => '2014-07-22'],
            ['dni' => 'PRE0032026', 'nombres' => 'María', 'apellidos' => 'Fernández Gómez', 'genero' => 'F', 'fecha_nac' => '2015-01-10'],
            ['dni' => 'PRE0042026', 'nombres' => 'Luis', 'apellidos' => 'González Díaz', 'genero' => 'M', 'fecha_nac' => '2014-11-05'],
            ['dni' => 'PRE0052026', 'nombres' => 'Carmen', 'apellidos' => 'López Sánchez', 'genero' => 'F', 'fecha_nac' => '2015-05-18'],
            ['dni' => 'PRE0062026', 'nombres' => 'Miguel', 'apellidos' => 'Martínez Ruiz', 'genero' => 'M', 'fecha_nac' => '2014-09-30'],
            ['dni' => 'PRE0072026', 'nombres' => 'Isabel', 'apellidos' => 'Sánchez Moreno', 'genero' => 'F', 'fecha_nac' => '2015-02-28'],
            ['dni' => 'PRE0082026', 'nombres' => 'Antonio', 'apellidos' => 'Pérez Álvarez', 'genero' => 'M', 'fecha_nac' => '2014-12-12'],
            ['dni' => 'PRE0092026', 'nombres' => 'Rosa', 'apellidos' => 'Martín Romero', 'genero' => 'F', 'fecha_nac' => '2015-04-07'],
            ['dni' => 'PRE0102026', 'nombres' => 'Francisco', 'apellidos' => 'Ruiz Navarro', 'genero' => 'M', 'fecha_nac' => '2014-08-25'],
        ];

        $estudiantesCreados = 0;
        $matriculasCreadas = 0;

        foreach ($estudiantesData as $index => $data) {
            // Asignar grado de manera rotativa
            $grado = $grados[$index % $grados->count()];

            // Crear persona del estudiante
            $persona = Persona::firstOrCreate(
                ['dni' => $data['dni']],
                [
                    'nombres' => $data['nombres'],
                    'apellidos' => $data['apellidos'],
                    'fecha_nacimiento' => $data['fecha_nac'],
                    'genero' => $data['genero'],
                    'direccion' => 'Dirección de prueba ' . ($index + 1),
                    'telefono' => '999999' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                    'email' => 'preinscrito' . ($index + 1) . '@educa.com',
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
                $estudiantesCreados++;
            }

            // Crear matrícula preinscrita si no existe
            $matriculaExistente = Matricula::where('estudiante_id', $estudiante->estudiante_id)
                ->where('anio_academico', $anioActual)
                ->first();

            if (!$matriculaExistente) {
                $numeroMatricula = $this->generarNumeroMatriculaUnico();
                Matricula::create([
                    'estudiante_id' => $estudiante->estudiante_id,
                    'numero_matricula' => $numeroMatricula,
                    'fecha_matricula' => Carbon::now(),
                    'estado' => 'Pre-inscrito',
                    'observaciones' => 'Preinscripción para el año ' . $anioActual,
                    'usuario_registro' => 1, // Admin por defecto
                    'idGrado' => $grado->grado_id,
                    'idSeccion' => $seccion->seccion_id,
                    'anio_academico' => $anioActual
                ]);
                $matriculasCreadas++;
            }

            // Crear usuario para el estudiante (opcional)
            Usuario::firstOrCreate(
                ['persona_id' => $persona->id_persona],
                [
                    'username' => 'preinscrito' . ($index + 1),
                    'password_hash' => Hash::make('password'),
                    'email' => $persona->email,
                    'estado' => 'Activo'
                ]
            );

            $this->command->info("✓ Procesado: {$data['nombres']} {$data['apellidos']} - Grado: {$grado->nombre}");
        }

        $this->command->info("=== PREINSCRIPCIONES CREADAS EXITOSAMENTE ===");
        $this->command->info("✓ Estudiantes creados: {$estudiantesCreados}");
        $this->command->info("✓ Matrículas preinscritas: {$matriculasCreadas}");
        $this->command->info("✓ Año académico: {$anioActual}");
        $this->command->info("✓ Estado: Pre-inscrito");

        // Mostrar resumen final
        $this->command->info("\n=== RESUMEN DE PREINSCRIPCIONES ===");
        $matriculasPreinscritas = Matricula::where('estado', 'Pre-inscrito')
            ->where('anio_academico', $anioActual)
            ->with(['estudiante.persona', 'grado'])
            ->get();

        foreach ($matriculasPreinscritas as $matricula) {
            $estudiante = $matricula->estudiante;
            $this->command->info("- {$matricula->numero_matricula}\t{$estudiante->persona->dni}\t{$estudiante->persona->nombres} {$estudiante->persona->apellidos}\t{$matricula->fecha_matricula->format('d/m/Y')}");
        }
    }

    /**
     * Genera un código único para estudiante
     */
    private function generarCodigoEstudianteUnico()
    {
        do {
            $codigo = 'PRE' . date('Y') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
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
}
