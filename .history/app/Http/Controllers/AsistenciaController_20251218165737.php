<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AsistenciaDiaria;
use App\Models\AsistenciaAsignatura;
use App\Models\JustificacionAsistencia;
use App\Models\InfEstudiante;
use App\Models\CursoAsignatura;
use App\Models\InfCurso;
use App\Models\InfSeccion;
use App\Models\InfGrado;
use App\Models\InfNivel;
use App\Models\InfAnioLectivo;
use App\Models\TipoAsistencia;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class AsistenciaController extends Controller
{

    /**
     * Dashboard principal de asistencia
     */
    public function dashboard()
    {
        $user = Auth::user();

        if ($user->rol == 'Administrador') {
            return $this->dashboardAdmin();
        } elseif ($user->rol == 'Profesor') {
            return $this->dashboardProfesor();
        } elseif ($user->rol == 'Representante') {
            return $this->dashboardRepresentante();
        }

        abort(403, 'No tienes permisos para acceder a esta sección.');
    }

    /**
     * Dashboard para administradores
     */
    private function dashboardAdmin()
    {
        $anioActual = InfAnioLectivo::where('estado', 'Activo')->first();

        // Estadísticas generales
        $estadisticas = [
            'total_estudiantes' => InfEstudiante::count(),
            'total_profesores' => User::where('rol', 'Profesor')->count(),
            'asistencias_hoy' => AsistenciaDiaria::where('fecha', today())->where('tipo_asistencia_id', 1)->count(),
            'inasistencias_hoy' => AsistenciaDiaria::where('fecha', today())->where('tipo_asistencia_id', 2)->count(),
        ];

        return view('asistencia.dashboard-admin', compact('anioActual', 'estadisticas'));
    }

    /**
     * Dashboard para profesores
     */
    private function dashboardProfesor()
    {
        $profesor = Auth::user();

        // Cursos asignados al profesor
        $cursosAsignados = CursoAsignatura::with(['curso', 'asignatura'])
            ->where('profesor_id', $profesor->persona_id)
            ->get();

        // Estadísticas del profesor
        $estadisticas = [
            'cursos_activos' => $cursosAsignados->count(),
            'asistencias_hoy' => AsistenciaAsignatura::where('profesor_id', $profesor->persona_id)
                ->where('fecha', today())
                ->sum('total_presentes'),
            'inasistencias_hoy' => AsistenciaAsignatura::where('profesor_id', $profesor->persona_id)
                ->where('fecha', today())
                ->sum('total_ausentes'),
        ];

        return view('asistencia.dashboard-profesor', compact('cursosAsignados', 'estadisticas'));
    }

    /**
     * Dashboard para representantes
     */
    private function dashboardRepresentante()
    {
        $representante = Auth::user();

        // Hijos del representante
        $hijos = InfEstudiante::where('representante_id', $representante->persona_id)->get();

        // Estadísticas de asistencia de los hijos
        $estadisticas = [];
        foreach ($hijos as $hijo) {
            $matricula = $hijo->matriculas()->where('anio_lectivo_id', InfAnioLectivo::where('estado', 'Activo')->first()->id ?? null)->first();

            if ($matricula) {
                $asistencias_mes = AsistenciaDiaria::where('matricula_id', $matricula->id)
                    ->whereBetween('fecha', [now()->startOfMonth(), now()->endOfMonth()])
                    ->get();

                $estadisticas[$hijo->id] = [
                    'total_dias' => $asistencias_mes->count(),
                    'presentes' => $asistencias_mes->where('tipo_asistencia_id', 1)->count(),
                    'ausentes' => $asistencias_mes->where('tipo_asistencia_id', 2)->count(),
                    'tardanzas' => $asistencias_mes->where('tipo_asistencia_id', 3)->count(),
                ];
            }
        }

        return view('asistencia.dashboard-representante', compact('hijos', 'estadisticas'));
    }

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
     * Registro de asistencia por asignatura
     */
    public function registrarAsignatura($cursoAsignaturaId, $fecha = null)
    {
        $user = Auth::user();

        // Verificar permisos
        if (!in_array($user->rol, ['Administrador', 'Profesor'])) {
            abort(403, 'No tienes permisos para acceder a esta función.');
        }

        $cursoAsignatura = CursoAsignatura::with(['curso', 'asignatura', 'profesor'])->findOrFail($cursoAsignaturaId);

        // Si es profesor, verificar que sea su curso
        if ($user->rol == 'Profesor' && $cursoAsignatura->profesor_id != $user->persona_id) {
            abort(403, 'No tienes permisos para registrar asistencia en este curso.');
        }

        $fecha = $fecha ? Carbon::parse($fecha) : today();

        // Obtener estudiantes matriculados en el curso
        $estudiantes = InfEstudiante::whereHas('matriculas', function($query) use ($cursoAsignatura) {
            $query->where('curso_id', $cursoAsignatura->curso_id)
                  ->where('seccion_id', $cursoAsignatura->seccion_id)
                  ->where('anio_lectivo_id', $cursoAsignatura->anio_lectivo_id);
        })->with(['matriculas' => function($query) use ($cursoAsignatura) {
            $query->where('curso_id', $cursoAsignatura->curso_id)
                  ->where('seccion_id', $cursoAsignatura->seccion_id)
                  ->where('anio_lectivo_id', $cursoAsignatura->anio_lectivo_id);
        }])->get();

        // Verificar si ya existe registro de asistencia para esta fecha
        $asistenciaExistente = AsistenciaAsignatura::where('curso_asignatura_id', $cursoAsignaturaId)
            ->where('fecha', $fecha)
            ->first();

        return view('asistencia.asignatura', compact('cursoAsignatura', 'estudiantes', 'fecha', 'asistenciaExistente'));
    }

    /**
     * Guardar asistencia de asignatura
     */
    public function guardarAsignatura(Request $request)
    {
        $request->validate([
            'curso_asignatura_id' => 'required|exists:curso_asignaturas,id',
            'fecha' => 'required|date',
            'asistencias' => 'required|array',
            'asistencias.*.matricula_id' => 'required|exists:matriculas,id',
            'asistencias.*.tipo_asistencia_id' => 'required|exists:tipo_asistencias,id',
        ]);

        try {
            DB::beginTransaction();

            $cursoAsignatura = CursoAsignatura::findOrFail($request->curso_asignatura_id);
            $user = Auth::user();

            // Verificar permisos
            if ($user->rol == 'Profesor' && $cursoAsignatura->profesor_id != $user->persona_id) {
                throw new \Exception('No tienes permisos para registrar asistencia en este curso.');
            }

            // Crear o actualizar registro de asistencia de asignatura
            $asistenciaAsignatura = AsistenciaAsignatura::updateOrCreate(
                [
                    'curso_asignatura_id' => $request->curso_asignatura_id,
                    'fecha' => $request->fecha,
                ],
                [
                    'profesor_id' => $cursoAsignatura->profesor_id,
                    'total_estudiantes' => count($request->asistencias),
                    'total_presentes' => collect($request->asistencias)->where('tipo_asistencia_id', 1)->count(),
                    'total_ausentes' => collect($request->asistencias)->where('tipo_asistencia_id', 2)->count(),
                    'total_tardanzas' => collect($request->asistencias)->where('tipo_asistencia_id', 3)->count(),
                ]
            );

            // Guardar asistencias individuales
            foreach ($request->asistencias as $asistenciaData) {
                AsistenciaDiaria::updateOrCreate(
                    [
                        'matricula_id' => $asistenciaData['matricula_id'],
                        'fecha' => $request->fecha,
                    ],
                    [
                        'tipo_asistencia_id' => $asistenciaData['tipo_asistencia_id'],
                        'asistencia_asignatura_id' => $asistenciaAsignatura->id,
                        'justificado' => false,
                        'observaciones' => $asistenciaData['observaciones'] ?? null,
                    ]
                );
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Asistencia registrada correctamente.'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar la asistencia: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Vista para justificar inasistencias
     */
    public function justificar()
    {
        $user = Auth::user();

        if ($user->rol == 'Representante') {
            // Hijos del representante
            $hijos = InfEstudiante::where('representante_id', $user->persona_id)->get();
            return view('asistencia.justificar', compact('hijos'));
        }

        abort(403, 'No tienes permisos para acceder a esta función.');
    }

    /**
     * Guardar justificación de asistencia
     */
    public function guardarJustificacion(Request $request)
    {
        $request->validate([
            'matricula_id' => 'required|exists:matriculas,id',
            'fecha_falta' => 'required|date',
            'motivo' => 'required|string|max:500',
            'documento_adjunto' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $matricula = \App\Models\Matricula::findOrFail($request->matricula_id);
            $user = Auth::user();

            // Verificar que el estudiante pertenezca al representante
            if ($user->rol == 'Representante' && $matricula->estudiante->representante_id != $user->persona_id) {
                throw new \Exception('No tienes permisos para justificar asistencias de este estudiante.');
            }

            // Verificar que la fecha no sea futura
            if (Carbon::parse($request->fecha_falta)->isFuture()) {
                throw new \Exception('No puedes justificar asistencias futuras.');
            }

            $documentoPath = null;
            if ($request->hasFile('documento_adjunto')) {
                $documentoPath = $request->file('documento_adjunto')->store('justificaciones', 'public');
            }

            JustificacionAsistencia::create([
                'matricula_id' => $request->matricula_id,
                'fecha_falta' => $request->fecha_falta,
                'motivo' => $request->motivo,
                'documento_adjunto' => $documentoPath,
                'estado' => 'Pendiente',
                'usuario_id' => $user->id,
                'fecha_solicitud' => now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Justificación enviada correctamente. Será revisada por un administrador.'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Error al enviar la justificación: ' . $e->getMessage()
            ], 500);
        }
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

        $justificaciones = JustificacionAsistencia::with(['estudiante', 'usuario'])
            ->where('estado', 'Pendiente')
            ->orderBy('fecha_solicitud', 'desc')
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

            } else {
                // Rechazar justificación
                $justificacion->update([
                    'estado' => 'Rechazada',
                    'observaciones_admin' => $request->observaciones,
                    'fecha_revision' => now(),
                    'revisado_por' => Auth::id()
                ]);
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
     * Vista de mis estudiantes (para representantes)
     */
    public function misEstudiantes()
    {
        $user = Auth::user();

        if ($user->rol != 'Representante') {
            abort(403, 'Esta función es solo para representantes.');
        }

        $hijos = InfEstudiante::where('representante_id', $user->persona_id)->get();

        return view('asistencia.mis-estudiantes', compact('hijos'));
    }

    /**
     * Vista de mis justificaciones
     */
    public function misJustificaciones()
    {
        $user = Auth::user();

        $justificaciones = JustificacionAsistencia::with(['estudiante'])
            ->where('usuario_id', $user->id)
            ->orderBy('fecha_solicitud', 'desc')
            ->paginate(15);

        return view('asistencia.mis-justificaciones', compact('justificaciones'));
    }

    // Métodos de reportes y APIs...

    /**
     * Reporte de curso
     */
    public function reporteCurso($cursoAsignaturaId)
    {
        $cursoAsignatura = CursoAsignatura::with(['curso', 'asignatura', 'profesor'])->findOrFail($cursoAsignaturaId);

        // Verificar permisos
        $user = Auth::user();
        if ($user->rol == 'Profesor' && $cursoAsignatura->profesor_id != $user->persona_id) {
            abort(403, 'No tienes permisos para ver este reporte.');
        }

        return view('asistencia.reporte-curso', compact('cursoAsignatura'));
    }

    /**
     * Detalle de estudiante
     */
    public function detalleEstudiante($matriculaId)
    {
        $matricula = \App\Models\Matricula::with(['estudiante', 'curso', 'seccion'])->findOrFail($matriculaId);

        // Verificar permisos
        $user = Auth::user();
        if ($user->rol == 'Representante' && $matricula->estudiante->representante_id != $user->persona_id) {
            abort(403, 'No tienes permisos para ver este reporte.');
        }

        return view('asistencia.reporte-estudiante', compact('matricula'));
    }

    /**
     * Exportar PDF de curso
     */
    public function exportarPDFCurso($cursoAsignaturaId)
    {
        $cursoAsignatura = CursoAsignatura::with(['curso', 'asignatura', 'profesor'])->findOrFail($cursoAsignaturaId);

        // Verificar permisos
        $user = Auth::user();
        if ($user->rol == 'Profesor' && $cursoAsignatura->profesor_id != $user->persona_id) {
            abort(403, 'No tienes permisos para exportar este reporte.');
        }

        $pdf = Pdf::loadView('asistencia.reportes.curso-pdf', compact('cursoAsignatura'));
        return $pdf->download('reporte_curso_' . $cursoAsignatura->id . '.pdf');
    }

    /**
     * Exportar PDF individual
     */
    public function exportarPDF($matriculaId)
    {
        $matricula = \App\Models\Matricula::with(['estudiante', 'curso', 'seccion'])->findOrFail($matriculaId);

        // Verificar permisos
        $user = Auth::user();
        if ($user->rol == 'Representante' && $matricula->estudiante->representante_id != $user->persona_id) {
            abort(403, 'No tienes permisos para exportar este reporte.');
        }

        $pdf = Pdf::loadView('asistencia.reportes.estudiante-pdf', compact('matricula'));
        return $pdf->download('reporte_estudiante_' . $matricula->estudiante->dni . '.pdf');
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

    // APIs para funcionalidades AJAX

    /**
     * Obtener estadísticas
     */
    public function obtenerEstadisticas($profesorId, $fecha)
    {
        try {
            $estadisticas = AsistenciaAsignatura::where('profesor_id', $profesorId)
                ->where('fecha', $fecha)
                ->selectRaw('SUM(total_presentes) as presentes, SUM(total_ausentes) as ausentes, SUM(total_tardanzas) as tardanzas')
                ->first();

            return response()->json([
                'success' => true,
                'data' => $estadisticas
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener estadísticas: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener historial de estudiante
     */
    public function obtenerHistorial($matriculaId)
    {
        try {
            $historial = AsistenciaDiaria::with('tipoAsistencia')
                ->where('matricula_id', $matriculaId)
                ->orderBy('fecha', 'desc')
                ->limit(30)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $historial
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener historial: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reconocimiento facial (placeholder)
     */
    public function reconocimientoFacial(Request $request)
    {
        // Implementar lógica de reconocimiento facial
        return response()->json([
            'success' => true,
            'message' => 'Funcionalidad no implementada aún.'
        ]);
    }

    /**
     * Obtener alertas
     */
    public function obtenerAlertas($cursoId)
    {
        try {
            // Lógica para obtener alertas de asistencia baja
            $alertas = AsistenciaAsignatura::where('curso_asignatura_id', $cursoId)
                ->where('fecha', '>=', now()->startOfWeek())
                ->whereRaw('(total_ausentes / total_estudiantes) > 0.3')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $alertas
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener alertas: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API para tabla AJAX de asistencias
     */
    public function getTablaAsistencias(Request $request)
    {
        $request->validate([
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date',
            'curso_id' => 'nullable|exists:inf_cursos,id',
            'seccion_id' => 'nullable|exists:inf_secciones,id',
            'tipo_asistencia' => 'nullable|exists:tipo_asistencias,id'
        ]);

        try {
            $query = AsistenciaDiaria::with(['matricula.estudiante', 'tipoAsistencia']);

            // Filtros
            if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
                $query->whereBetween('fecha', [$request->fecha_inicio, $request->fecha_fin]);
            }

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

            if ($request->filled('tipo_asistencia')) {
                $query->where('tipo_asistencia_id', $request->tipo_asistencia);
            }

            $asistencias = $query->orderBy('fecha', 'desc')
                ->orderBy('matricula_id')
                ->paginate(25);

            return response()->json([
                'success' => true,
                'data' => $asistencias
            ]);

        } catch (\Exception $e) {
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

    /**
     * Obtener cursos por profesor
     */
    public function getCursosPorProfesor($profesorId)
    {
        try {
            $cursos = CursoAsignatura::with(['curso', 'asignatura'])
                ->where('profesor_id', $profesorId)
                ->whereHas('anioLectivo', function($q) {
                    $q->where('estado', 'Activo');
                })
                ->get()
                ->map(function($cursoAsignatura) {
                    return [
                        'id' => $cursoAsignatura->id,
                        'nombre' => $cursoAsignatura->curso->nombre . ' - ' . $cursoAsignatura->asignatura->nombre
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $cursos
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener cursos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener asignaturas por profesor
     */
    public function getAsignaturasPorProfesor($profesorId)
    {
        try {
            $asignaturas = CursoAsignatura::with('asignatura')
                ->where('profesor_id', $profesorId)
                ->whereHas('anioLectivo', function($q) {
                    $q->where('estado', 'Activo');
                })
                ->get()
                ->pluck('asignatura')
                ->unique('id')
                ->map(function($asignatura) {
                    return [
                        'id' => $asignatura->id,
                        'nombre' => $asignatura->nombre
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $asignaturas
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener asignaturas: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener estudiantes por filtros
     */
    public function getEstudiantesPorFiltros(Request $request)
    {
        $request->validate([
            'curso_id' => 'nullable|exists:inf_cursos,id',
            'seccion_id' => 'nullable|exists:inf_secciones,id',
            'asignatura_id' => 'nullable|exists:inf_asignaturas,id'
        ]);

        try {
            $query = InfEstudiante::with(['matriculas.curso', 'matriculas.seccion']);

            if ($request->filled('curso_id')) {
                $query->whereHas('matriculas', function($q) use ($request) {
                    $q->where('curso_id', $request->curso_id);
                });
            }

            if ($request->filled('seccion_id')) {
                $query->whereHas('matriculas', function($q) use ($request) {
                    $q->where('seccion_id', $request->seccion_id);
                });
            }

            $estudiantes = $query->get();

            return response()->json([
                'success' => true,
                'data' => $estudiantes
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener estudiantes: ' . $e->getMessage()
            ], 500);
        }
    }

    // Métodos adicionales para notificaciones y configuración...

    /**
     * Vista de notificaciones
     */
    public function notificaciones()
    {
        $user = Auth::user();

        // Lógica para obtener notificaciones de asistencia
        $notificaciones = []; // Implementar según necesidades

        return view('asistencia.notificaciones', compact('notificaciones'));
    }

    /**
     * Marcar notificación como leída
     */
    public function marcarNotificacionLeida(Request $request)
    {
        // Implementar lógica para marcar notificación como leída
        return response()->json(['success' => true]);
    }

    /**
     * Marcar todas las notificaciones como leídas
     */
    public function marcarTodasNotificacionesLeidas(Request $request)
    {
        // Implementar lógica para marcar todas las notificaciones como leídas
        return response()->json(['success' => true]);
    }

    /**
     * Vista de configuración
     */
    public function configuracion()
    {
        // Verificar permisos
        if (!in_array(Auth::user()->rol, ['Administrador'])) {
            abort(403, 'No tienes permisos para acceder a esta configuración.');
        }

        return view('asistencia.configuracion');
    }

    /**
     * Guardar configuración
     */
    public function guardarConfiguracion(Request $request)
    {
        // Verificar permisos
        if (!in_array(Auth::user()->rol, ['Administrador'])) {
            abort(403, 'No tienes permisos para modificar esta configuración.');
        }

        // Implementar lógica para guardar configuración
        return redirect()->back()->with('success', 'Configuración guardada correctamente.');
    }
}
