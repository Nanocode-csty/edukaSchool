<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\SesionClase;
use App\Models\InfDocente;
use Illuminate\Support\Facades\DB;

$docente = InfDocente::find(27);
if (!$docente) {
    echo 'Docente no encontrado';
    exit;
}

echo '=== CORRIGIENDO SESIONES DEL DOCENTE ' . $docente->profesor_id . ' ===' . PHP_EOL;

// 1. Eliminar todas las sesiones actuales del docente
$sesionesEliminadas = SesionClase::whereHas('cursoAsignatura', function($query) use ($docente) {
    $query->where('profesor_id', $docente->profesor_id);
})->delete();

echo "Sesiones eliminadas: $sesionesEliminadas" . PHP_EOL;

// 2. Obtener las asignaciones de cursos del docente
$cursoAsignaturas = \App\Models\CursoAsignatura::with(['curso.grado', 'curso.seccion', 'asignatura'])
    ->where('profesor_id', $docente->profesor_id)
    ->get();

echo "Asignaciones encontradas: " . $cursoAsignaturas->count() . PHP_EOL;

foreach($cursoAsignaturas as $index => $asignatura) {
    echo "  [$index] " . $asignatura->asignatura->nombre . " - " . $asignatura->curso->grado->nombre . $asignatura->curso->seccion->nombre . PHP_EOL;
}
echo PHP_EOL;

// 3. Crear horario coherente distribuyendo todas las asignaturas
$totalAsignaturas = $cursoAsignaturas->count();
$horasPorDia = 8; // Máximo 8 horas por día
$horasPorSesion = 1; // 1 hora por sesión
$sesionesPorDia = min(8, floor($horasPorDia / $horasPorSesion)); // Máximo 8 sesiones por día

$horarioBase = [];

// Distribuir asignaturas equitativamente por día
$diaIndex = 0;
$diasSemana = ['lunes', 'martes', 'miércoles', 'jueves', 'viernes'];

foreach($diasSemana as $dia) {
    $horarioBase[$dia] = [];
    $horaActual = 8; // Empezar a las 8:00

    for($sesion = 0; $sesion < $sesionesPorDia; $sesion++) {
        $asignaturaIndex = ($diaIndex * $sesionesPorDia + $sesion) % $totalAsignaturas;

        if (isset($cursoAsignaturas[$asignaturaIndex])) {
            $horarioBase[$dia][] = [
                'hora_inicio' => sprintf('%02d:00', $horaActual),
                'hora_fin' => sprintf('%02d:00', $horaActual + 1),
                'asignatura_index' => $asignaturaIndex
            ];
            $horaActual++;
        }
    }

    $diaIndex++;
}

echo "Horario generado:" . PHP_EOL;
foreach($horarioBase as $dia => $sesiones) {
    echo "$dia: " . count($sesiones) . " sesiones" . PHP_EOL;
}
echo PHP_EOL;

// Obtener año lectivo activo
$anioActual = \App\Models\InfAnioLectivo::where('estado', 'Activo')->first();
if (!$anioActual) {
    echo 'No hay año lectivo activo';
    exit;
}

echo "Año lectivo: {$anioActual->fecha_inicio} - {$anioActual->fecha_fin}" . PHP_EOL;

$sesionesCreadas = 0;
$diasProcesados = 0;

// 4. Crear sesiones para cada día del año lectivo
$fechaInicio = \Carbon\Carbon::parse($anioActual->fecha_inicio);
$fechaFin = \Carbon\Carbon::parse($anioActual->fecha_fin);

echo "Procesando desde {$fechaInicio->format('Y-m-d')} hasta {$fechaFin->format('Y-m-d')}" . PHP_EOL;

$fecha = $fechaInicio->copy();

while ($fecha->lte($fechaFin)) {
    $diaSemana = $fecha->locale('es')->dayName;
    $diasProcesados++;

    // Debug: mostrar qué día está procesando
    if ($diasProcesados <= 10) { // Solo mostrar primeros 10 días
        echo "Procesando {$fecha->format('Y-m-d')} - Día: '$diaSemana'" . PHP_EOL;
        echo "  Existe en horarioBase: " . (isset($horarioBase[$diaSemana]) ? 'SÍ' : 'NO') . PHP_EOL;
    }

    // Solo crear sesiones para días laborables con horario definido
    if (isset($horarioBase[$diaSemana])) {
        $sesionesDia = 0;
        foreach ($horarioBase[$diaSemana] as $clase) {
            $asignaturaIndex = $clase['asignatura_index'];

            // Solo crear si hay asignatura disponible
            if (isset($cursoAsignaturas[$asignaturaIndex])) {
                $cursoAsignatura = $cursoAsignaturas[$asignaturaIndex];

                // Verificar que no sea feriado
                $esDiaLaborable = !\App\Models\Feriado::esFeriado($fecha->format('Y-m-d'));

                if ($esDiaLaborable) {
                    SesionClase::create([
                        'curso_asignatura_id' => $cursoAsignatura->curso_asignatura_id,
                        'fecha' => $fecha->toDateString(),
                        'hora_inicio' => $clase['hora_inicio'],
                        'hora_fin' => $clase['hora_fin'],
                        'estado' => 'Programada',
                        'observaciones' => null,
                        'aula_id' => $cursoAsignaturas->aula_id ?? 1, // Usar aula por defecto si no tiene
                        'tipo_sesion' => 'Normal',
                        'usuario_registro' => 1, // Usuario admin por defecto
                    ]);

                    $sesionesCreadas++;
                    $sesionesDia++;
                } else {
                    if ($diasProcesados <= 10) {
                        echo "  Feriado en {$fecha->format('Y-m-d')} ($diaSemana)" . PHP_EOL;
                    }
                }
            } else {
                if ($diasProcesados <= 10) {
                    echo "  Asignatura index $asignaturaIndex no existe" . PHP_EOL;
                }
            }
        }

        if ($sesionesDia > 0 && $diasProcesados <= 5) { // Solo mostrar primeros 5 días
            echo "  {$fecha->format('Y-m-d')} ($diaSemana): $sesionesDia sesiones creadas" . PHP_EOL;
        }
    }

    $fecha->addDay();
}

echo "Total días procesados: $diasProcesados" . PHP_EOL;

echo "Sesiones creadas: $sesionesCreadas" . PHP_EOL;

// 5. Verificar el resultado
$sesionesPorDia = SesionClase::with(['cursoAsignatura.curso.grado', 'cursoAsignatura.curso.seccion', 'cursoAsignatura.asignatura'])
    ->whereHas('cursoAsignatura', function($query) use ($docente) {
        $query->where('profesor_id', $docente->profesor_id);
    })
    ->get()
    ->groupBy(function($sesion) {
        return $sesion->fecha->locale('es')->dayName;
    });

$totalHorasSemanales = 0;
foreach(['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'] as $dia) {
    $sesionesDia = $sesionesPorDia->get($dia, collect());
    $horasDia = 0;

    foreach($sesionesDia as $sesion) {
        $horaInicio = strtotime($sesion->hora_inicio);
        $horaFin = strtotime($sesion->hora_fin);
        $duracionHoras = ($horaFin - $horaInicio) / 3600;
        $horasDia += $duracionHoras;
    }

    $totalHorasSemanales += $horasDia;

    echo sprintf('%s: %d sesiones, %.2f horas' . PHP_EOL, $dia, $sesionesDia->count(), $horasDia);
}

echo PHP_EOL . 'TOTAL HORAS SEMANALES: ' . number_format($totalHorasSemanales, 2) . ' hrs' . PHP_EOL;

if ($totalHorasSemanales > 24) {
    echo '⚠️  EXCESO: Más de 24 horas semanales permitidas' . PHP_EOL;
} elseif ($totalHorasSemanales < 20) {
    echo 'ℹ️  BAJO: Menos de 20 horas semanales' . PHP_EOL;
} else {
    echo '✅ ACEPTABLE: Entre 20-24 horas semanales' . PHP_EOL;
}

echo PHP_EOL . '=== CORRECCIÓN COMPLETADA ===' . PHP_EOL;
