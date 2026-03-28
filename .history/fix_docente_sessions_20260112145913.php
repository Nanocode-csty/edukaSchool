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

// 3. Crear horario coherente
$horarioBase = [
    'Lunes' => [
        ['hora_inicio' => '08:00', 'hora_fin' => '09:00', 'asignatura_index' => 0],
        ['hora_inicio' => '09:00', 'hora_fin' => '10:00', 'asignatura_index' => 1],
        ['hora_inicio' => '10:00', 'hora_fin' => '11:00', 'asignatura_index' => 2],
        ['hora_inicio' => '11:00', 'hora_fin' => '12:00', 'asignatura_index' => 3],
    ],
    'Martes' => [
        ['hora_inicio' => '08:00', 'hora_fin' => '09:00', 'asignatura_index' => 1],
        ['hora_inicio' => '09:00', 'hora_fin' => '10:00', 'asignatura_index' => 2],
        ['hora_inicio' => '10:00', 'hora_fin' => '11:00', 'asignatura_index' => 3],
        ['hora_inicio' => '11:00', 'hora_fin' => '12:00', 'asignatura_index' => 0],
    ],
    'Miércoles' => [
        ['hora_inicio' => '08:00', 'hora_fin' => '09:00', 'asignatura_index' => 2],
        ['hora_inicio' => '09:00', 'hora_fin' => '10:00', 'asignatura_index' => 3],
        ['hora_inicio' => '10:00', 'hora_fin' => '11:00', 'asignatura_index' => 0],
        ['hora_inicio' => '11:00', 'hora_fin' => '12:00', 'asignatura_index' => 1],
    ],
    'Jueves' => [
        ['hora_inicio' => '08:00', 'hora_fin' => '09:00', 'asignatura_index' => 3],
        ['hora_inicio' => '09:00', 'hora_fin' => '10:00', 'asignatura_index' => 0],
        ['hora_inicio' => '10:00', 'hora_fin' => '11:00', 'asignatura_index' => 1],
        ['hora_inicio' => '11:00', 'hora_fin' => '12:00', 'asignatura_index' => 2],
    ],
    'Viernes' => [
        ['hora_inicio' => '08:00', 'hora_fin' => '09:00', 'asignatura_index' => 0],
        ['hora_inicio' => '09:00', 'hora_fin' => '10:00', 'asignatura_index' => 1],
        ['hora_inicio' => '10:00', 'hora_fin' => '11:00', 'asignatura_index' => 2],
    ]
];

// Obtener año lectivo activo
$anioActual = \App\Models\InfAnioLectivo::where('estado', 'Activo')->first();
if (!$anioActual) {
    echo 'No hay año lectivo activo';
    exit;
}

$sesionesCreadas = 0;

// 4. Crear sesiones para cada día del año lectivo
$fechaInicio = \Carbon\Carbon::parse($anioActual->fecha_inicio);
$fechaFin = \Carbon\Carbon::parse($anioActual->fecha_fin);

$fecha = $fechaInicio->copy();

while ($fecha->lte($fechaFin)) {
    $diaSemana = $fecha->locale('es')->dayName;

    // Solo crear sesiones para días laborables con horario definido
    if (isset($horarioBase[$diaSemana])) {
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
                        'aula_id' => $cursoAsignatura->aula_id ?? 1, // Usar aula por defecto si no tiene
                        'tipo_sesion' => 'Normal',
                        'usuario_registro' => 1, // Usuario admin por defecto
                    ]);

                    $sesionesCreadas++;
                }
            }
        }
    }

    $fecha->addDay();
}

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
