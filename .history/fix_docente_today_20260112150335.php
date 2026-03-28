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

echo '=== AJUSTANDO HORARIO DEL DOCENTE 27 PARA HOY Y DÍAS SIGUIENTES ===' . PHP_EOL;

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

// 3. Crear horario realista centrado en hoy
$fechaActual = \Carbon\Carbon::now()->startOfDay(); // 2026-01-12
$fechaActual = \Carbon\Carbon::createFromFormat('Y-m-d', '2026-01-12'); // Forzar fecha específica

echo "Fecha actual: {$fechaActual->format('Y-m-d')} ({$fechaActual->locale('es')->dayName})" . PHP_EOL;

$horarioRealista = [
    // Hoy (lunes 12 enero 2026) - clases por la tarde
    $fechaActual->format('Y-m-d') => [
        ['hora_inicio' => '17:00', 'hora_fin' => '18:00', 'asignatura_index' => 0], // 5:00 PM
        ['hora_inicio' => '18:00', 'hora_fin' => '19:00', 'asignatura_index' => 1], // 6:00 PM
        ['hora_inicio' => '19:00', 'hora_fin' => '20:00', 'asignatura_index' => 2], // 7:00 PM
    ],

    // Mañana (martes 13 enero 2026)
    $fechaActual->copy()->addDay(1)->format('Y-m-d') => [
        ['hora_inicio' => '15:00', 'hora_fin' => '16:00', 'asignatura_index' => 3], // 3:00 PM
        ['hora_inicio' => '16:00', 'hora_fin' => '17:00', 'asignatura_index' => 4], // 4:00 PM
        ['hora_inicio' => '17:00', 'hora_fin' => '18:00', 'asignatura_index' => 5], // 5:00 PM
        ['hora_inicio' => '18:00', 'hora_fin' => '19:00', 'asignatura_index' => 6], // 6:00 PM
    ],

    // Miércoles 14 enero 2026
    $fechaActual->copy()->addDay(2)->format('Y-m-d') => [
        ['hora_inicio' => '14:00', 'hora_fin' => '15:00', 'asignatura_index' => 7], // 2:00 PM
        ['hora_inicio' => '15:00', 'hora_fin' => '16:00', 'asignatura_index' => 8], // 3:00 PM
        ['hora_inicio' => '16:00', 'hora_fin' => '17:00', 'asignatura_index' => 9], // 4:00 PM
        ['hora_inicio' => '17:00', 'hora_fin' => '18:00', 'asignatura_index' => 10], // 5:00 PM
        ['hora_inicio' => '18:00', 'hora_fin' => '19:00', 'asignatura_index' => 11], // 6:00 PM
    ],

    // Jueves 15 enero 2026
    $fechaActual->copy()->addDay(3)->format('Y-m-d') => [
        ['hora_inicio' => '16:00', 'hora_fin' => '17:00', 'asignatura_index' => 12], // 4:00 PM
        ['hora_inicio' => '17:00', 'hora_fin' => '18:00', 'asignatura_index' => 13], // 5:00 PM
        ['hora_inicio' => '18:00', 'hora_fin' => '19:00', 'asignatura_index' => 14], // 6:00 PM
        ['hora_inicio' => '19:00', 'hora_fin' => '20:00', 'asignatura_index' => 15], // 7:00 PM
    ],

    // Viernes 16 enero 2026
    $fechaActual->copy()->addDay(4)->format('Y-m-d') => [
        ['hora_inicio' => '15:00', 'hora_fin' => '16:00', 'asignatura_index' => 16], // 3:00 PM
        ['hora_inicio' => '16:00', 'hora_fin' => '17:00', 'asignatura_index' => 17], // 4:00 PM
        ['hora_inicio' => '17:00', 'hora_fin' => '18:00', 'asignatura_index' => 18], // 5:00 PM
        ['hora_inicio' => '18:00', 'hora_fin' => '19:00', 'asignatura_index' => 0], // 6:00 PM
    ],

    // Lunes 19 enero 2026 (semana siguiente)
    $fechaActual->copy()->addDay(7)->format('Y-m-d') => [
        ['hora_inicio' => '17:00', 'hora_fin' => '18:00', 'asignatura_index' => 1], // 5:00 PM
        ['hora_inicio' => '18:00', 'hora_fin' => '19:00', 'asignatura_index' => 2], // 6:00 PM
    ],

    // Martes 20 enero 2026
    $fechaActual->copy()->addDay(8)->format('Y-m-d') => [
        ['hora_inicio' => '17:00', 'hora_fin' => '18:00', 'asignatura_index' => 3], // 5:00 PM
        ['hora_inicio' => '18:00', 'hora_fin' => '19:00', 'asignatura_index' => 4], // 6:00 PM
    ],
];

$sesionesCreadas = 0;

echo "Creando sesiones para los próximos días:" . PHP_EOL;

foreach($horarioRealista as $fecha => $sesionesDia) {
    $fechaObj = \Carbon\Carbon::parse($fecha);
    echo PHP_EOL . "--- {$fecha} ({$fechaObj->locale('es')->dayName}) ---" . PHP_EOL;

    foreach($sesionesDia as $clase) {
        $asignaturaIndex = $clase['asignatura_index'];

        if (isset($cursoAsignaturas[$asignaturaIndex])) {
            $cursoAsignatura = $cursoAsignaturas[$asignaturaIndex];

            // Verificar que no sea feriado
            $esDiaLaborable = !\App\Models\Feriado::esFeriado($fecha);

            if ($esDiaLaborable) {
                SesionClase::create([
                    'curso_asignatura_id' => $cursoAsignatura->curso_asignatura_id,
                    'fecha' => $fecha,
                    'hora_inicio' => $clase['hora_inicio'],
                    'hora_fin' => $clase['hora_fin'],
                    'estado' => 'Programada',
                    'observaciones' => null,
                    'aula_id' => $cursoAsignatura->aula_id ?? 1,
                    'tipo_sesion' => 'Normal',
                    'usuario_registro' => 1,
                ]);

                echo sprintf('  %s - %s: %s (%s-%s)' . PHP_EOL,
                    $clase['hora_inicio'],
                    $clase['hora_fin'],
                    $cursoAsignatura->asignatura->nombre,
                    $cursoAsignatura->curso->grado->nombre,
                    $cursoAsignatura->curso->seccion->nombre
                );

                $sesionesCreadas++;
            } else {
                echo "  Feriado en {$fecha}" . PHP_EOL;
            }
        }
    }
}

echo PHP_EOL . "Sesiones creadas: $sesionesCreadas" . PHP_EOL;

// 4. Verificar clases de hoy específicamente
echo PHP_EOL . "=== CLASES DE HOY ({$fechaActual->format('Y-m-d')}) ===" . PHP_EOL;
$clasesHoy = SesionClase::with(['cursoAsignatura.curso.grado', 'cursoAsignatura.curso.seccion', 'cursoAsignatura.asignatura'])
    ->whereHas('cursoAsignatura', function($query) use ($docente) {
        $query->where('profesor_id', $docente->profesor_id);
    })
    ->whereDate('fecha', $fechaActual)
    ->orderBy('hora_inicio')
    ->get();

foreach($clasesHoy as $clase) {
    echo sprintf('%s - %s: %s (%s-%s)' . PHP_EOL,
        $clase->hora_inicio,
        $clase->hora_fin,
        $clase->cursoAsignatura->asignatura->nombre,
        $clase->cursoAsignatura->curso->grado->nombre,
        $clase->cursoAsignatura->curso->seccion->nombre
    );
}

echo PHP_EOL . '=== HORARIO AJUSTADO COMPLETADO ===' . PHP_EOL;
