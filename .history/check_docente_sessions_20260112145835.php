<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\SesionClase;
use App\Models\InfDocente;

$docente = InfDocente::find(27);
if (!$docente) {
    echo 'Docente no encontrado';
    exit;
}

echo '=== SESIONES DEL DOCENTE ' . $docente->profesor_id . ' (' . $docente->persona->nombres . ' ' . $docente->persona->apellidos . ') ===' . PHP_EOL;
echo PHP_EOL;

// Obtener todas las sesiones por día
$sesionesPorDia = SesionClase::with(['cursoAsignatura.curso.grado', 'cursoAsignatura.curso.seccion', 'cursoAsignatura.asignatura', 'aula'])
    ->whereHas('cursoAsignatura', function($query) use ($docente) {
        $query->where('profesor_id', $docente->profesor_id);
    })
    ->orderBy('fecha')
    ->orderBy('hora_inicio')
    ->get()
    ->groupBy(function($sesion) {
        return $sesion->fecha->format('Y-m-d') . ' (' . $sesion->fecha->locale('es')->dayName . ')';
    });

foreach($sesionesPorDia as $dia => $sesiones) {
    echo '--- ' . $dia . ' ---' . PHP_EOL;

    $horasTotalesDia = 0;
    foreach($sesiones as $sesion) {
        $horaInicio = strtotime($sesion->hora_inicio);
        $horaFin = strtotime($sesion->hora_fin);
        $duracionHoras = ($horaFin - $horaInicio) / 3600;
        $horasTotalesDia += $duracionHoras;

        echo sprintf('  %s - %s: %s (%s-%s) - %s hrs' . PHP_EOL,
            $sesion->hora_inicio,
            $sesion->hora_fin,
            $sesion->cursoAsignatura->asignatura->nombre,
            $sesion->cursoAsignatura->curso->grado->nombre,
            $sesion->cursoAsignatura->curso->seccion->nombre,
            number_format($duracionHoras, 2)
        );

        // Verificar conflictos de horario
        $conflicto = false;
        foreach($sesiones as $otraSesion) {
            if ($sesion->sesion_id != $otraSesion->sesion_id) {
                $inicio1 = strtotime($sesion->hora_inicio);
                $fin1 = strtotime($sesion->hora_fin);
                $inicio2 = strtotime($otraSesion->hora_inicio);
                $fin2 = strtotime($otraSesion->hora_fin);

                if (($inicio1 < $fin2 && $fin1 > $inicio2)) {
                    echo '    ⚠️  CONFLICTO: Se solapa con ' . $otraSesion->cursoAsignatura->asignatura->nombre . PHP_EOL;
                    $conflicto = true;
                }
            }
        }
    }

    echo '  TOTAL HORAS DIA: ' . number_format($horasTotalesDia, 2) . ' hrs' . PHP_EOL;

    if ($horasTotalesDia > 8) {
        echo '  ⚠️  EXCESO: Más de 8 horas por día (' . number_format($horasTotalesDia, 2) . ' hrs)' . PHP_EOL;
    }

    echo PHP_EOL;
}

// Resumen semanal
echo '=== RESUMEN SEMANAL ===' . PHP_EOL;
$sesionesPorDiaSemana = SesionClase::with(['cursoAsignatura'])
    ->whereHas('cursoAsignatura', function($query) use ($docente) {
        $query->where('profesor_id', $docente->profesor_id);
    })
    ->get()
    ->groupBy(function($sesion) {
        return $sesion->fecha->locale('es')->dayName;
    });

$totalHorasSemanales = 0;
foreach(['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'] as $dia) {
    $sesionesDia = $sesionesPorDiaSemana->get($dia, collect());
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
