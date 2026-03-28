<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InfEstudiante;
use App\Models\AsistenciaAsignatura;
use App\Models\TipoAsistencia;
use Illuminate\Support\Facades\Auth;

class EstudianteController extends Controller
{
    /**
     * Panel principal del estudiante
     */
    public function dashboard()
    {
        // Verificar permisos
        if (!in_array(Auth::user()->rol, ['Estudiante'])) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        $estudiante = Auth::user()->persona->estudiante;

        // Obtener estadísticas de asistencia del mes actual
        $mesActual = now()->month;
        $anioActual = now()->year;

        $asistenciasMes = AsistenciaDiaria::where('matricula_id', $estudiante->matricula->id)
            ->whereMonth('fecha', $mesActual)
            ->whereYear('fecha', $anioActual)
            ->with('tipoAsistencia')
            ->get();

        $totalAsistencias = $asistenciasMes->count();
        $presentes = $asistenciasMes->where('tipo_asistencia_id', TipoAsistencia::where('codigo', 'P')->first()->id ?? 1)->count();
        $ausentes = $asistenciasMes->where('tipo_asistencia_id', TipoAsistencia::where('codigo', 'A')->first()->id ?? 2)->count();
        $tardes = $asistenciasMes->where('tipo_asistencia_id', TipoAsistencia::where('codigo', 'T')->first()->id ?? 3)->count();
        $justificados = $asistenciasMes->where('tipo_asistencia_id', TipoAsistencia::where('codigo', 'J')->first()->id ?? 4)->count();

        $porcentajeAsistencia = $totalAsistencias > 0 ? round((($presentes + $justificados) / $totalAsistencias) * 100, 1) : 0;

        // Asistencia de hoy
        $asistenciaHoy = AsistenciaDiaria::where('matricula_id', $estudiante->matricula->id)
            ->whereDate('fecha', today())
            ->with('tipoAsistencia')
            ->first();

        // Últimas 5 asistencias
        $ultimasAsistencias = AsistenciaDiaria::where('matricula_id', $estudiante->matricula->id)
            ->with(['tipoAsistencia', 'sesionClase.cursoAsignatura.asignatura'])
            ->orderBy('fecha', 'desc')
            ->limit(5)
            ->get();

        // Calificaciones del período actual (simulado por ahora)
        $calificaciones = collect([
            // Aquí se integrarían las calificaciones reales del sistema de notas
        ]);

        $estadisticas = [
            'total_asistencias_mes' => $totalAsistencias,
            'presentes_mes' => $presentes,
            'ausentes_mes' => $ausentes,
            'tardes_mes' => $tardes,
            'justificados_mes' => $justificados,
            'porcentaje_asistencia' => $porcentajeAsistencia,
            'promedio_calificaciones' => 0, // Se calculará cuando se integre con notas
            'materias_cursando' => 1, // Simulado
        ];

        return view('estudiante.dashboard', compact(
            'estudiante',
            'estadisticas',
            'asistenciaHoy',
            'ultimasAsistencias',
            'calificaciones'
        ));
    }

    /**
     * Ver detalle de asistencia del estudiante
     */
    public function verAsistencia(Request $request)
    {
        // Verificar permisos
        if (!in_array(Auth::user()->rol, ['Estudiante'])) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        $estudiante = Auth::user()->persona->estudiante;

        $mes = $request->get('mes', date('n'));
        $anio = $request->get('anio', date('Y'));

        // Obtener asistencias del estudiante para el período
        $asistencias = AsistenciaDiaria::where('matricula_id', $estudiante->matricula->id)
            ->whereMonth('fecha', $mes)
            ->whereYear('fecha', $anio)
            ->with(['sesionClase.cursoAsignatura.asignatura', 'tipoAsistencia'])
            ->orderBy('fecha', 'desc')
            ->paginate(20);

        // Estadísticas del período
        $estadisticas = [
            'presentes' => $asistencias->where('tipo_asistencia_id', TipoAsistencia::where('codigo', 'P')->first()->id ?? 1)->count(),
            'ausentes' => $asistencias->where('tipo_asistencia_id', TipoAsistencia::where('codigo', 'A')->first()->id ?? 2)->count(),
            'tardes' => $asistencias->where('tipo_asistencia_id', TipoAsistencia::where('codigo', 'T')->first()->id ?? 3)->count(),
            'justificados' => $asistencias->where('tipo_asistencia_id', TipoAsistencia::where('codigo', 'J')->first()->id ?? 4)->count(),
        ];

        return view('estudiante.asistencia', compact('estudiante', 'asistencias', 'estadisticas', 'mes', 'anio'));
    }

    /**
     * Ver calificaciones del estudiante
     */
    public function verCalificaciones()
    {
        // Verificar permisos
        if (!in_array(Auth::user()->rol, ['Estudiante'])) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        $estudiante = Auth::user()->persona->estudiante;

        // Calificaciones por asignatura (simulado por ahora)
        $calificaciones = collect([
            // Aquí se integrarían las calificaciones reales del sistema de notas
        ]);

        // Estadísticas de rendimiento
        $estadisticas = [
            'promedio_general' => 0,
            'materias_aprobadas' => 0,
            'materias_reprobadas' => 0,
            'total_materias' => 0,
        ];

        return view('estudiante.calificaciones', compact('estudiante', 'calificaciones', 'estadisticas'));
    }
}
