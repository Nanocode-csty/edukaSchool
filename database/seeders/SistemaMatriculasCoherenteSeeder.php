<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Matricula;
use App\Models\PeriodoMatricula;

class SistemaMatriculasCoherenteSeeder extends Seeder
{
    /**
     * Ejecutar el seeder para crear un sistema coherente de matrículas
     */
    public function run(): void
    {
        $this->command->info('=== CREANDO SISTEMA COHERENTE DE MATRÍCULAS ===');

        // Paso 1: Asegurar que los períodos estén configurados correctamente
        $this->command->info('Paso 1: Configurando períodos de matrícula...');
        $this->call(PeriodosMatriculaSeeder::class);

        // Paso 2: Ajustar fechas de matriculas existentes según períodos
        $this->command->info('Paso 2: Ajustando fechas de matrículas existentes...');
        $this->ajustarFechasMatriculasExistentes();

        // Paso 3: Crear estudiantes preinscritos para 2026
        $this->command->info('Paso 3: Creando preinscripciones para 2026...');
        $this->call(Preinscritos2026Seeder::class);

        // Paso 4: Crear estudiantes matriculados con fechas coherentes
        $this->command->info('Paso 4: Creando matrículas oficiales con fechas coherentes...');
        $this->crearMatriculasOficialesCoherentes();

        $this->command->info('=== SISTEMA DE MATRÍCULAS COHERENTE CREADO ===');
        $this->mostrarResumenFinal();
    }

    /**
     * Ajustar fechas de matrículas existentes para que sean coherentes con los períodos
     */
    private function ajustarFechasMatriculasExistentes()
    {
        $anioActual = date('Y'); // 2026

        // Obtener períodos activos
        $periodoPreinscripcion = PeriodoMatricula::where('codigo', 'PREINSCRIPCION_' . $anioActual)->first();
        $periodoInscripcion = PeriodoMatricula::where('codigo', 'INSCRIPCION_' . $anioActual)->first();
        $periodoMatricula = PeriodoMatricula::where('codigo', 'MATRICULA_' . $anioActual)->first();

        if (!$periodoPreinscripcion || !$periodoInscripcion || !$periodoMatricula) {
            $this->command->error('Períodos de matrícula no encontrados');
            return;
        }

        // Ajustar fechas de matriculados de 2026 que están fuera de los períodos
        $matriculas2026 = Matricula::where('anio_academico', $anioActual)
            ->where('estado', 'Matriculado')
            ->get();

        $ajustadas = 0;
        foreach ($matriculas2026 as $matricula) {
            $fechaActual = Carbon::parse($matricula->fecha_matricula);

            // Si la fecha está antes del período de matrículas, ajustarla
            if ($fechaActual->lt($periodoMatricula->fecha_inicio)) {
                $nuevaFecha = $periodoMatricula->fecha_inicio->copy()->addDays(rand(0, 30));
                if ($nuevaFecha->lte($periodoMatricula->fecha_fin)) {
                    $matricula->update(['fecha_matricula' => $nuevaFecha]);
                    $ajustadas++;
                }
            }
        }

        $this->command->info("✓ Fechas de matrículas ajustadas: {$ajustadas}");
    }

    /**
     * Crear matrículas oficiales con fechas coherentes
     */
    private function crearMatriculasOficialesCoherentes()
    {
        $anioActual = date('Y'); // 2026

        // Obtener período de matrículas
        $periodoMatricula = PeriodoMatricula::where('codigo', 'MATRICULA_' . $anioActual)->first();

        if (!$periodoMatricula) {
            $this->command->error('Período de matrículas no encontrado');
            return;
        }

        // Contar matrículas ya existentes en el período de matrículas
        $matriculasOficialesExistentes = Matricula::where('anio_academico', $anioActual)
            ->where('estado', 'Matriculado')
            ->whereBetween('fecha_matricula', [
                $periodoMatricula->fecha_inicio,
                $periodoMatricula->fecha_fin
            ])
            ->count();

        // Si ya hay suficientes matrículas oficiales, no crear más
        if ($matriculasOficialesExistentes >= 50) {
            $this->command->info("✓ Ya existen suficientes matrículas oficiales ({$matriculasOficialesExistentes})");
            return;
        }

        // Convertir algunas preinscripciones a matrículas oficiales
        $preinscripciones = Matricula::where('anio_academico', $anioActual)
            ->where('estado', 'Pre-inscrito')
            ->with(['estudiante.persona', 'grado', 'seccion'])
            ->take(20) // Convertir máximo 20 preinscripciones a matrículas
            ->get();

        $convertidas = 0;
        foreach ($preinscripciones as $preinscripcion) {
            // Generar fecha dentro del período de matrículas
            $fechaMatricula = $periodoMatricula->fecha_inicio->copy()->addDays(rand(0, 30));
            if ($fechaMatricula->gt($periodoMatricula->fecha_fin)) {
                $fechaMatricula = $periodoMatricula->fecha_fin;
            }

            $preinscripcion->update([
                'estado' => 'Matriculado',
                'fecha_matricula' => $fechaMatricula,
                'observaciones' => 'Matrícula oficial confirmada'
            ]);

            $convertidas++;
        }

        $this->command->info("✓ Preinscripciones convertidas a matrículas oficiales: {$convertidas}");

        // Crear algunas matrículas oficiales nuevas si es necesario
        $matriculasFaltantes = max(0, 30 - ($matriculasOficialesExistentes + $convertidas));

        if ($matriculasFaltantes > 0) {
            $this->command->info("Creando {$matriculasFaltantes} matrículas oficiales adicionales...");

            // Obtener estudiantes que no tienen matrícula para 2026
            $estudiantesSinMatricula = DB::table('estudiantes')
                ->leftJoin('matriculas', function($join) use ($anioActual) {
                    $join->on('estudiantes.estudiante_id', '=', 'matriculas.estudiante_id')
                         ->where('matriculas.anio_academico', '=', $anioActual);
                })
                ->whereNull('matriculas.matricula_id')
                ->take($matriculasFaltantes)
                ->get();

            $nuevasCreadas = 0;
            foreach ($estudiantesSinMatricula as $estudianteData) {
                $fechaMatricula = $periodoMatricula->fecha_inicio->copy()->addDays(rand(0, 30));
                if ($fechaMatricula->gt($periodoMatricula->fecha_fin)) {
                    $fechaMatricula = $periodoMatricula->fecha_fin;
                }

                // Asignar grado y sección aleatorios
                $gradoId = DB::table('grados')->inRandomOrder()->first()->grado_id;
                $seccionId = DB::table('secciones')->inRandomOrder()->first()->seccion_id;

                $numeroMatricula = $this->generarNumeroMatriculaUnico();

                Matricula::create([
                    'estudiante_id' => $estudianteData->estudiante_id,
                    'numero_matricula' => $numeroMatricula,
                    'fecha_matricula' => $fechaMatricula,
                    'estado' => 'Matriculado',
                    'observaciones' => 'Matrícula oficial creada automáticamente',
                    'usuario_registro' => 1,
                    'idGrado' => $gradoId,
                    'idSeccion' => $seccionId,
                    'anio_academico' => $anioActual
                ]);

                $nuevasCreadas++;
            }

            $this->command->info("✓ Nuevas matrículas oficiales creadas: {$nuevasCreadas}");
        }
    }

    /**
     * Mostrar resumen final del sistema de matrículas
     */
    private function mostrarResumenFinal()
    {
        $anioActual = date('Y'); // 2026

        $this->command->info("\n=== RESUMEN FINAL DEL SISTEMA DE MATRÍCULAS ===");

        // Resumen por estado
        $resumenEstado = Matricula::selectRaw('estado, COUNT(*) as total')
            ->where('anio_academico', $anioActual)
            ->groupBy('estado')
            ->get();

        foreach ($resumenEstado as $item) {
            $this->command->info("{$item->estado}: {$item->total} estudiantes");
        }

        // Mostrar algunas preinscripciones recientes
        $this->command->info("\n=== PREINSCRIPCIONES PARA 2026 (Muestra) ===");
        $preinscripciones = Matricula::where('estado', 'Pre-inscrito')
            ->where('anio_academico', $anioActual)
            ->with(['estudiante.persona', 'grado'])
            ->orderBy('fecha_matricula', 'desc')
            ->take(5)
            ->get();

        foreach ($preinscripciones as $matricula) {
            $estudiante = $matricula->estudiante;
            $this->command->info("- {$matricula->numero_matricula}\t{$estudiante->persona->dni}\t{$estudiante->persona->nombres} {$estudiante->persona->apellidos}\t{$matricula->fecha_matricula->format('d/m/Y')}");
        }

        // Mostrar algunas matrículas oficiales recientes
        $this->command->info("\n=== MATRÍCULAS OFICIALES PARA 2026 (Muestra) ===");
        $matriculasOficiales = Matricula::where('estado', 'Matriculado')
            ->where('anio_academico', $anioActual)
            ->with(['estudiante.persona', 'grado'])
            ->orderBy('fecha_matricula', 'desc')
            ->take(5)
            ->get();

        foreach ($matriculasOficiales as $matricula) {
            $estudiante = $matricula->estudiante;
            $this->command->info("- {$matricula->numero_matricula}\t{$estudiante->persona->dni}\t{$estudiante->persona->nombres} {$estudiante->persona->apellidos}\t{$matricula->fecha_matricula->format('d/m/Y')}");
        }

        $this->command->info("\n=== PERÍODOS DE MATRÍCULA ACTIVOS ===");
        $periodos = PeriodoMatricula::where('estado', 'ACTIVO')
            ->orderBy('orden')
            ->get();

        foreach ($periodos as $periodo) {
            $this->command->info("- {$periodo->nombre}: {$periodo->fecha_inicio->format('d/m/Y')} - {$periodo->fecha_fin->format('d/m/Y')}");
        }
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
