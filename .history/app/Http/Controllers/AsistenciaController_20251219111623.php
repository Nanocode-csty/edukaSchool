<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AsistenciaAsignatura;
use App\Models\AsistenciaDiaria;
use App\Models\JustificacionAsistencia;
use App\Models\Notificacion;
use App\Models\InfEstudiante;
use App\Models\InfAnioLectivo;
use App\Models\TipoAsistencia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class AsistenciaController extends Controller
{
    /**
     * Vista administrativa de asistencias
     */
    public function adminIndex()
    {
        // Verificar permisos
        if (!in_array(Auth::user()->rol, ['Administrador'])) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        $anioActual = InfAnioLectivo::where('estado', 'Activo')->first();

        return view('asistencia.admin-index', compact('anioActual'));
    }

    /**
     * Vista para verificar justificaciones
     */
    public function verificar()
    {
        // Verificar permisos
        if (!in_array(Auth::user()->rol, ['Administrador'])) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        $justificaciones = JustificacionAsistencia::with(['matricula.estudiante', 'usuarioCreador'])
            ->orderBy('fecha', 'desc')
            ->paginate(15);

        return view('asistencia.verificar', compact('justificaciones'));
    }

    /**
     * Procesar verificación de justificación
     */
    public function procesarVerificacion(Request $request)
    {
        $request->validate([
            'justificacion_id' => 'required|exists:justificacion_asistencias,id',
            'accion' => 'required|in:Aprobar,Rechazar',
            'observaciones' => 'nullable|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            $justificacion = JustificacionAsistencia::findOrFail($request->justificacion_id);

            if ($request->accion === 'Aprobar') {
                // Aprobar justificación
                $justificacion->update([
                    'estado' => 'Aprobada',
                    'observaciones_admin' => $request->observaciones,
                    'fecha_revision' => now(),
                    'revisado_por' => Auth::id()
                ]);

                // Crear registro de asistencia justificada
                AsistenciaDiaria::updateOrCreate(
                    [
                        'matricula_id' => $justificacion->matricula_id,
                        'fecha' => $justificacion->fecha_falta
                    ],
                    [
                        'tipo_asistencia_id' => TipoAsistencia::where('codigo', 'J')->first()->id ?? 3,
                        'justificado' => true,
                        'observaciones' => 'Justificado: ' . $justificacion->motivo
                    ]
                );

                // Crear notificación para el usuario que solicitó la justificación
                Notificacion::crearNotificacionJustificacionResuelta($justificacion->id, true);

            } else {
                // Rechazar justificación
                $justificacion->update([
                    'estado' => 'Rechazada',
                    'observaciones_admin' => $request->observaciones,
                    'fecha_revision' => now(),
                    'revisado_por' => Auth::id()
                ]);

                // Crear notificación para el usuario que solicitó la justificación
                Notificacion::crearNotificacionJustificacionResuelta($justificacion->id, false);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Justificación ' . strtolower($request->accion) . 'ada correctamente.'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la justificación: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exportar PDF de asistencias administrativas
     */
    public function exportarPDFAdmin(Request $request)
    {
        // Verificar permisos
        if (!in_array(Auth::user()->rol, ['Administrador'])) {
            abort(403, 'No tienes permisos para acceder a esta función.');
        }

        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'curso_id' => 'nullable|exists:inf_cursos,id',
            'seccion_id' => 'nullable|exists:inf_secciones,id'
        ]);

        try {
            $query = AsistenciaDiaria::with(['matricula.estudiante', 'tipoAsistencia']);

            // Filtros
            if ($request->filled('curso_id')) {
                $query->whereHas('matricula', function($q) use ($request) {
                    $q->where('curso_id', $request->curso_id);
                });
            }

            if ($request->filled('seccion_id')) {
                $query->whereHas('matricula', function($q) use ($request) {
                    $q->where('seccion_id', $request->seccion_id);
                });
            }

            $asistencias = $query->whereBetween('fecha', [$request->fecha_inicio, $request->fecha_fin])
                ->orderBy('fecha')
                ->orderBy('matricula_id')
                ->get();

            $pdf = Pdf::loadView('asistencia.reportes.admin-pdf', compact('asistencias', 'request'));

            return $pdf->download('reporte_asistencias_admin_' . date('Y-m-d') . '.pdf');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al generar el reporte: ' . $e->getMessage());
        }
    }

    /**
     * API para tabla AJAX de asistencias con filtros avanzados
     */
    public function getTablaAsistencias(Request $request)
    {
        try {
            $query = AsistenciaAsignatura::with(['matricula.estudiante.persona', 'matricula.grado', 'matricula.seccion', 'tipoAsistencia', 'cursoAsignatura.asignatura']);

            // Filtros básicos
            if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
                $query->whereBetween('fecha', [$request->fecha_inicio, $request->fecha_fin]);
            }

            if ($request->filled('tipo_asistencia')) {
                // Map attendance type codes to expected IDs
                $tipoAsistenciaMap = [
                    'P' => TipoAsistencia::where('codigo', 'P')->first()->id ?? 1, // Presente
                    'A' => TipoAsistencia::where('codigo', 'A')->first()->id ?? 2, // Ausente
                    'T' => TipoAsistencia::where('codigo', 'T')->first()->id ?? 3, // Tarde
                    'J' => TipoAsistencia::where('codigo', 'J')->first()->id ?? 4  // Justificado
                ];

                if (isset($tipoAsistenciaMap[$request->tipo_asistencia])) {
                    $query->where('tipo_asistencia_id', $tipoAsistenciaMap[$request->tipo_asistencia]);
                }
            }

            if ($request->filled('justificado')) {
                $query->where('justificacion', '!=', null);
            }

            // Filtros avanzados
            if ($request->filled('nivel_id')) {
                $query->whereHas('matricula.grado', function($q) use ($request) {
                    $q->where('nivel_id', $request->nivel_id);
                });
            }

            if ($request->filled('grado_id')) {
                $query->whereHas('matricula.grado', function($q) use ($request) {
                    $q->where('grado_id', $request->grado_id);
                });
            }

            if ($request->filled('seccion_id')) {
                $query->whereHas('matricula', function($q) use ($request) {
                    $q->where('idSeccion', $request->seccion_id);
                });
            }

            if ($request->filled('estudiante_id')) {
                $query->whereHas('matricula', function($q) use ($request) {
                    $q->where('estudiante_id', $request->estudiante_id);
                });
            }

            // Filtro por docente
            if ($request->filled('docente_id')) {
                $query->whereHas('cursoAsignatura', function($q) use ($request) {
                    $q->where('profesor_id', $request->docente_id);
                });
            }

            // Filtro por asignatura
            if ($request->filled('asignatura_id')) {
                $query->whereHas('cursoAsignatura', function($q) use ($request) {
                    $q->where('asignatura_id', $request->asignatura_id);
                });
            }

            $asistencias = $query->orderBy('fecha', 'desc')
                ->orderBy('matricula_id')
                ->paginate(15);

            // Calcular estadísticas
            $totalRegistros = $asistencias->total();
            $totalPresentes = $asistencias->where('tipo_asistencia_id', TipoAsistencia::where('codigo', 'P')->first()->id ?? 1)->count();
            $totalAusentes = $asistencias->where('tipo_asistencia_id', TipoAsistencia::where('codigo', 'A')->first()->id ?? 2)->count();
            $porcentajeAsistencia = $totalRegistros > 0 ? round((($totalRegistros - $totalAusentes) / $totalRegistros) * 100, 1) : 0;

            $estadisticas = [
                'total_registros' => $totalRegistros,
                'total_presentes' => $totalPresentes,
                'total_ausentes' => $totalAusentes,
                'porcentaje_asistencia' => $porcentajeAsistencia . '%'
            ];

            return response()->json([
                'success' => true,
                'data' => $asistencias,
                'estadisticas' => $estadisticas
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in getTablaAsistencias: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Error al cargar los datos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API para buscar estudiantes AJAX
     */
    public function buscarEstudiantes(Request $request)
    {
        $request->validate([
            'q' => 'nullable|string|max:100'
        ]);

        try {
            $query = InfEstudiante::with(['matriculas.curso', 'matriculas.seccion']);

            if ($request->filled('q')) {
                $query->where(function($q) use ($request) {
                    $q->where('nombres', 'like', '%' . $request->q . '%')
                      ->orWhere('apellidos', 'like', '%' . $request->q . '%')
                      ->orWhere('dni', 'like', '%' . $request->q . '%');
                });
            }

            $estudiantes = $query->limit(20)->get();

            return response()->json([
                'success' => true,
                'data' => $estudiantes
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al buscar estudiantes: ' . $e->getMessage()
            ], 500);
        }
    }

    // ========== MÉTODOS PARA DOCENTES ==========

    /**
     * Vista principal para docentes
     */
    public function docenteIndex()
    {
        // Verificar permisos
        if (!in_array(Auth::user()->rol, ['Docente'])) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        $docente = Auth::user()->persona->docente;

        // Obtener sesiones de clase del día actual
        $sesionesHoy = \App\Models\SesionClase::with(['cursoAsignatura.curso', 'cursoAsignatura.asignatura', 'aula'])
            ->where('docente_id', $docente->id)
            ->whereDate('fecha', today())
            ->orderBy('hora_inicio')
            ->get()
            ->map(function($sesion) {
                // Agregar propiedad para verificar si ya tiene asistencia
                $sesion->tiene_asistencia_hoy = AsistenciaDiaria::where('matricula_id', '>', 0)
                    ->whereDate('fecha', $sesion->fecha)
                    ->exists();
                return $sesion;
            });

        // Estadísticas
        $estadisticas = [
            'total_clases_hoy' => $sesionesHoy->count(),
            'total_estudiantes' => $sesionesHoy->sum(function($sesion) {
                return $sesion->cursoAsignatura->curso->matriculas()->count();
            }),
            'asistencias_pendientes' => $sesionesHoy->where('tiene_asistencia_hoy', false)->count(),
            'inasistencias_hoy' => 0 // Se calculará después
        ];

        return view('asistencia.docente-index', compact('sesionesHoy', 'estadisticas'));
    }

    /**
     * Ver asistencia de una sesión específica
     */
    public function docenteVerAsistencia(\App\Models\SesionClase $sesionClase)
    {
        // Verificar permisos
        if (!in_array(Auth::user()->rol, ['Docente'])) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        $docente = Auth::user()->persona->docente;

        // Verificar que la sesión pertenece al docente
        if ($sesionClase->docente_id !== $docente->id) {
            abort(403, 'No tienes permisos para ver esta asistencia.');
        }

        // Obtener asistencias de la sesión
        $asistencias = AsistenciaDiaria::with(['matricula.estudiante', 'tipoAsistencia'])
            ->where('sesion_clase_id', $sesionClase->id)
            ->whereDate('fecha', $sesionClase->fecha)
            ->get();

        // Estadísticas
        $estadisticas = [
            'presentes' => $asistencias->where('tipo_asistencia_id', \App\Models\TipoAsistencia::where('codigo', 'P')->first()->id ?? 1)->count(),
            'ausentes' => $asistencias->where('tipo_asistencia_id', \App\Models\TipoAsistencia::where('codigo', 'A')->first()->id ?? 2)->count(),
            'tardes' => $asistencias->where('tipo_asistencia_id', \App\Models\TipoAsistencia::where('codigo', 'T')->first()->id ?? 3)->count(),
            'justificados' => $asistencias->where('tipo_asistencia_id', \App\Models\TipoAsistencia::where('codigo', 'J')->first()->id ?? 4)->count(),
        ];

        return view('asistencia.docente-ver-asistencia', compact('sesionClase', 'asistencias', 'estadisticas'));
    }

    /**
     * API para obtener estudiantes de una sesión
     */
    public function docenteObtenerEstudiantes(Request $request)
    {
        $request->validate([
            'sesion_clase_id' => 'required|exists:sesion_clases,id'
        ]);

        try {
            $sesionClase = \App\Models\SesionClase::findOrFail($request->sesion_clase_id);

            // Verificar permisos
            if (!in_array(Auth::user()->rol, ['Docente'])) {
                return response()->json(['success' => false, 'message' => 'No tienes permisos.'], 403);
            }

            $docente = Auth::user()->persona->docente;
            if ($sesionClase->docente_id !== $docente->id) {
                return response()->json(['success' => false, 'message' => 'No tienes permisos para esta sesión.'], 403);
            }

            // Obtener estudiantes matriculados en el curso
            $estudiantes = $sesionClase->cursoAsignatura->curso->matriculas()
                ->with('estudiante')
                ->where('estado', 'Activo')
                ->get()
                ->map(function($matricula) {
                    return [
                        'matricula_id' => $matricula->id,
                        'nombres' => $matricula->estudiante->nombres,
                        'apellidos' => $matricula->estudiante->apellidos,
                        'dni' => $matricula->estudiante->dni
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => [
                    'estudiantes' => $estudiantes,
                    'clase_info' => $sesionClase->cursoAsignatura->asignatura->nombre . ' - ' .
                                   $sesionClase->cursoAsignatura->curso->grado->nombre . ' ' .
                                   $sesionClase->cursoAsignatura->curso->seccion->nombre
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener estudiantes: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Guardar asistencia tomada por docente
     */
    public function docenteGuardarAsistencia(Request $request)
    {
        $request->validate([
            'sesion_clase_id' => 'required|exists:sesion_clases,id',
            'asistencias' => 'required|array',
            'asistencias.*.matricula_id' => 'required|exists:matriculas,id',
            'asistencias.*.tipo_asistencia' => 'required|in:P,A,T,J',
            'asistencias.*.observaciones' => 'nullable|string|max:255'
        ]);

        try {
            DB::beginTransaction();

            $sesionClase = \App\Models\SesionClase::findOrFail($request->sesion_clase_id);

            // Verificar permisos
            if (!in_array(Auth::user()->rol, ['Docente'])) {
                throw new \Exception('No tienes permisos.');
            }

            $docente = Auth::user()->persona->docente;
            if ($sesionClase->docente_id !== $docente->id) {
                throw new \Exception('No tienes permisos para esta sesión.');
            }

            $fecha = today();

            foreach ($request->asistencias as $asistenciaData) {
                $tipoAsistencia = TipoAsistencia::where('codigo', $asistenciaData['tipo_asistencia'])->first();

                AsistenciaDiaria::updateOrCreate(
                    [
                        'matricula_id' => $asistenciaData['matricula_id'],
                        'fecha' => $fecha,
                        'sesion_clase_id' => $sesionClase->id
                    ],
                    [
                        'tipo_asistencia_id' => $tipoAsistencia->id,
                        'justificado' => $asistenciaData['tipo_asistencia'] === 'J',
                        'observaciones' => $asistenciaData['observaciones'] ?? null
                    ]
                );
            }

            // Marcar que la asistencia ya fue tomada
            $sesionClase->update(['tiene_asistencia_hoy' => true]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Asistencia guardada correctamente.'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar asistencia: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exportar PDF de asistencia docente
     */
    public function docenteExportarPDF(\App\Models\SesionClase $sesionClase)
    {
        // Verificar permisos
        if (!in_array(Auth::user()->rol, ['Docente'])) {
            abort(403, 'No tienes permisos.');
        }

        $docente = Auth::user()->persona->docente;
        if ($sesionClase->docente_id !== $docente->id) {
            abort(403, 'No tienes permisos.');
        }

        // Obtener datos similares a docenteVerAsistencia
        $asistencias = AsistenciaDiaria::with(['matricula.estudiante', 'tipoAsistencia'])
            ->where('sesion_clase_id', $sesionClase->id)
            ->whereDate('fecha', $sesionClase->fecha)
            ->get();

        $pdf = Pdf::loadView('asistencia.reportes.docente-pdf', compact('sesionClase', 'asistencias'));
        return $pdf->download('asistencia_' . $sesionClase->cursoAsignatura->asignatura->nombre . '_' . date('Y-m-d') . '.pdf');
    }

    // ========== MÉTODOS PARA REPRESENTANTES ==========

    /**
     * Vista principal para representantes
     */
    public function representanteIndex()
    {
        // Verificar permisos
        if (!in_array(Auth::user()->rol, ['Representante'])) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        $representante = Auth::user()->persona->representante;

        // Obtener estudiantes del representante
        $estudiantes = $representante->estudiantes()
            ->with(['matricula.curso.grado', 'matricula.seccion', 'matricula.curso.anioLectivo'])
            ->whereHas('matricula', function($q) {
                $q->where('estado', 'Activo');
            })
            ->get()
            ->map(function($estudiante) {
                // Calcular estadísticas del mes actual
                $mesActual = now()->month;
                $anioActual = now()->year;

                $asistenciasMes = AsistenciaDiaria::where('matricula_id', $estudiante->matricula->id)
                    ->whereMonth('fecha', $mesActual)
                    ->whereYear('fecha', $anioActual)
                    ->get();

                $totalAsistencias = $asistenciasMes->count();
                $inasistencias = $asistenciasMes->where('tipo_asistencia_id', TipoAsistencia::where('codigo', 'A')->first()->id ?? 2)->count();

                $estudiante->asistencia_hoy = AsistenciaDiaria::where('matricula_id', $estudiante->matricula->id)
                    ->whereDate('fecha', today())
                    ->with('tipoAsistencia')
                    ->first();

                $estudiante->porcentaje_asistencia = $totalAsistencias > 0 ?
                    round((($totalAsistencias - $inasistencias) / $totalAsistencias) * 100, 1) : 0;

                $estudiante->inasistencias_mes = $inasistencias;

                return $estudiante;
            });

        // Estadísticas generales
        $estadisticasGenerales = [
            'total_estudiantes' => $estudiantes->count(),
            'promedio_asistencia' => $estudiantes->avg('porcentaje_asistencia'),
            'total_inasistencias_mes' => $estudiantes->sum('inasistencias_mes'),
            'justificaciones_pendientes' => JustificacionAsistencia::whereIn('matricula_id', $estudiantes->pluck('matricula.id'))
                ->where('estado', 'Pendiente')
                ->count(),
            'total_asistencias_mes' => $estudiantes->sum(function($e) {
                return AsistenciaDiaria::where('matricula_id', $e->matricula->id)
                    ->whereMonth('fecha', now()->month)
                    ->whereYear('fecha', now()->year)
                    ->where('tipo_asistencia_id', TipoAsistencia::where('codigo', 'P')->first()->id ?? 1)
                    ->count();
            }),
            'justificaciones_aprobadas' => JustificacionAsistencia::whereIn('matricula_id', $estudiantes->pluck('matricula.id'))
                ->where('estado', 'Aprobada')
                ->count()
        ];

        return view('asistencia.representante-index', compact('estudiantes', 'estadisticasGenerales'));
    }

    /**
     * Ver detalle de asistencia de un estudiante
     */
    public function representanteDetalle(InfEstudiante $estudiante, Request $request)
    {
        // Verificar permisos
        if (!in_array(Auth::user()->rol, ['Representante'])) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        $representante = Auth::user()->persona->representante;

        // Verificar que el estudiante pertenece al representante
        if (!$representante->estudiantes()->where('estudiantes.id', $estudiante->id)->exists()) {
            abort(403, 'No tienes permisos para ver este estudiante.');
        }

        $mes = $request->get('mes', date('n'));
        $anio = $request->get('anio', date('Y'));

        // Obtener asistencias del estudiante para el período
        $asistencias = AsistenciaDiaria::with(['sesionClase.cursoAsignatura.asignatura', 'sesionClase.cursoAsignatura.docente', 'tipoAsistencia'])
            ->where('matricula_id', $estudiante->matricula->id)
            ->whereMonth('fecha', $mes)
            ->whereYear('fecha', $anio)
            ->orderBy('fecha', 'desc')
            ->paginate(20);

        // Estadísticas del estudiante
        $estadisticas = [
            'presentes' => $asistencias->where('tipo_asistencia_id', TipoAsistencia::where('codigo', 'P')->first()->id ?? 1)->count(),
            'ausentes' => $asistencias->where('tipo_asistencia_id', TipoAsistencia::where('codigo', 'A')->first()->id ?? 2)->count(),
            'tardes' => $asistencias->where('tipo_asistencia_id', TipoAsistencia::where('codigo', 'T')->first()->id ?? 3)->count(),
            'justificados' => $asistencias->where('tipo_asistencia_id', TipoAsistencia::where('codigo', 'J')->first()->id ?? 4)->count(),
        ];

        // Justificaciones del estudiante
        $justificaciones = JustificacionAsistencia::where('matricula_id', $estudiante->matricula->id)
            ->orderBy('fecha_solicitud', 'desc')
            ->limit(10)
            ->get();

        return view('asistencia.representante-detalle', compact('estudiante', 'asistencias', 'estadisticas', 'justificaciones', 'mes', 'anio'));
    }

    /**
     * Solicitar justificación de inasistencia
     */
    public function representanteSolicitarJustificacion(Request $request)
    {
        $request->validate([
            'estudiante_id' => 'required|exists:estudiantes,id',
            'fecha_falta' => 'required|date|before_or_equal:today',
            'motivo' => 'required|string|max:500',
            'documento_adjunto' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        try {
            DB::beginTransaction();

            // Verificar permisos
            if (!in_array(Auth::user()->rol, ['Representante'])) {
                throw new \Exception('No tienes permisos.');
            }

            $representante = Auth::user()->persona->representante;
            $estudiante = InfEstudiante::findOrFail($request->estudiante_id);

            // Verificar que el estudiante pertenece al representante
            if (!$representante->estudiantes()->where('estudiantes.id', $estudiante->id)->exists()) {
                throw new \Exception('No tienes permisos para este estudiante.');
            }

            // Verificar que no existe una justificación pendiente para esa fecha
            $justificacionExistente = JustificacionAsistencia::where('matricula_id', $estudiante->matricula->id)
                ->where('fecha_falta', $request->fecha_falta)
                ->whereIn('estado', ['Pendiente', 'Aprobada'])
                ->exists();

            if ($justificacionExistente) {
                throw new \Exception('Ya existe una justificación para esta fecha.');
            }

            // Guardar documento si existe
            $documentoPath = null;
            if ($request->hasFile('documento_adjunto')) {
                $documentoPath = $request->file('documento_adjunto')->store('justificaciones', 'public');
            }

            // Crear justificación
            $justificacion = JustificacionAsistencia::create([
                'matricula_id' => $estudiante->matricula->id,
                'usuario_id' => Auth::id(),
                'fecha_solicitud' => now(),
                'fecha_falta' => $request->fecha_falta,
                'motivo' => $request->motivo,
                'documento_adjunto' => $documentoPath,
                'estado' => 'Pendiente'
            ]);

            // Crear notificación para administradores
            Notificacion::crearNotificacionJustificacionPendiente($justificacion->id);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Justificación solicitada correctamente. Será revisada por un administrador.'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Error al solicitar justificación: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exportar reporte de asistencia del estudiante
     */
    public function representanteExportarReporte(InfEstudiante $estudiante, Request $request)
    {
        // Verificar permisos
        if (!in_array(Auth::user()->rol, ['Representante'])) {
            abort(403, 'No tienes permisos.');
        }

        $representante = Auth::user()->persona->representante;

        // Verificar que el estudiante pertenece al representante
        if (!$representante->estudiantes()->where('estudiantes.id', $estudiante->id)->exists()) {
            abort(403, 'No tienes permisos para este estudiante.');
        }

        $mes = $request->get('mes', date('n'));
        $anio = $request->get('anio', date('Y'));

        // Obtener datos similares a representanteDetalle
        $asistencias = AsistenciaDiaria::with(['sesionClase.cursoAsignatura.asignatura', 'tipoAsistencia'])
            ->where('matricula_id', $estudiante->matricula->id)
            ->whereMonth('fecha', $mes)
            ->whereYear('fecha', $anio)
            ->orderBy('fecha')
            ->get();

        $pdf = Pdf::loadView('asistencia.reportes.representante-pdf', compact('estudiante', 'asistencias', 'mes', 'anio'));
        return $pdf->download('reporte_asistencia_' . $estudiante->nombres . '_' . $estudiante->apellidos . '_' . $mes . '_' . $anio . '.pdf');
    }
}
