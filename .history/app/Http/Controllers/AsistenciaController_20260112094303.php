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
use App\Models\InfNivel;
use App\Models\InfCurso;
use App\Models\InfGrado;
use App\Models\InfSeccion;
use App\Models\InfDocente;
use App\Models\ReporteGenerado;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\AsistenciaExport;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AsistenciaController extends Controller
{
    // ========== MÉTODOS ADMINISTRATIVOS ==========

    /**
     * Vista de reportes de asistencias
     */
    public function reportes()
    {
        // Verificar autenticación
        if (!Auth::check()) {
            \Log::warning('Usuario no autenticado');
            return response()->json(['success' => false, 'message' => 'Debes iniciar sesión.'], 401);
        }

        // Verificar permisos
        $user = Auth::user();
        if (!$user->relationLoaded('persona')) {
            $user->load('persona.roles');
        }

        \Log::info('Verificando permisos admin para reportes', [
            'user_id' => $user->usuario_id,
            'user_roles' => $user->getRoleNames(),
            'has_admin_role' => $user->hasRole('Administrador'),
        ]);

        if (!$user->hasRole('Administrador')) {
            \Log::warning('Usuario sin permisos de administrador', [
                'user_id' => $user->usuario_id,
                'user_roles' => $user->getRoleNames(),
            ]);
            return response()->json(['success' => false, 'message' => 'No tienes permisos.'], 403);
        }

        $anioActual = InfAnioLectivo::where('estado', 'Activo')->first();

        return view('asistencia.reportes', compact('anioActual'));
    }

    /**
     * Vista administrativa de asistencias
     */
    public function adminIndex()
    {
        // Verificar autenticación primero
        \Log::info('Verificando autenticación', [
            'auth_check' => Auth::check(),
            'auth_user' => Auth::user(),
            'session_id' => session()->getId()
        ]);

        if (!Auth::check()) {
            \Log::warning('Usuario no autenticado');
            return response()->json(['success' => false, 'message' => 'Debes iniciar sesión.'], 401);
        }

        // Verificar permisos
        $user = Auth::user();

        // Forzar carga de la relación persona con roles si no está cargada
        if (!$user->relationLoaded('persona')) {
            $user->load('persona.roles');
        }

        \Log::info('Verificando permisos admin', [
            'user_id' => $user->usuario_id,
            'user_roles' => $user->getRoleNames(),
            'has_admin_role' => $user->hasRole('Administrador'),
            'persona_loaded' => $user->relationLoaded('persona'),
            'roles_count' => $user->persona?->roles?->count() ?? 0
        ]);

        if (!$user->hasRole('Administrador')) {
            \Log::warning('Usuario sin permisos de administrador', [
                'user_id' => $user->usuario_id,
                'user_roles' => $user->getRoleNames(),
                'persona_loaded' => $user->relationLoaded('persona'),
                'roles_data' => $user->persona?->roles?->toArray() ?? []
            ]);
            return response()->json(['success' => false, 'message' => 'No tienes permisos.'], 403);
        }

        $anioActual = InfAnioLectivo::where('estado', 'Activo')->first();

        // Obtener fechas mínimas y máximas de todas las asistencias para mostrar por defecto
        $fechaMinima = AsistenciaAsignatura::min('fecha');
        $fechaMaxima = AsistenciaAsignatura::max('fecha');

        // Guardar fechas por defecto en la sesión para que la API las use si no hay filtros
        session(['asistencia_fechas_default' => [
            'fecha_inicio' => $fechaMinima ? \Carbon\Carbon::parse($fechaMinima)->format('Y-m-d') : null,
            'fecha_fin' => $fechaMaxima ? \Carbon\Carbon::parse($fechaMaxima)->format('Y-m-d') : null
        ]]);

        return view('asistencia.admin-index', compact('anioActual', 'fechaMinima', 'fechaMaxima'));
    }

    /**
     * API para obtener tabla de asistencias filtrada (solo asistenciasasignatura)
     */
    public function getTablaAsistencias(Request $request)
    {
        try {
            \Log::info('getTablaAsistencias called', $request->all());
            // Usar solo la tabla asistenciasasignatura con relaciones optimizadas
            $query = AsistenciaAsignatura::with([
                'matricula.estudiante.persona',
                'matricula.grado',
                'matricula.seccion',
                'tipoAsistencia',
                'cursoAsignatura.asignatura'
            ]);

            // Aplicar filtros de fecha - usar fechas por defecto si no hay filtros específicos
            $fechasDefault = session('asistencia_fechas_default', []);
            $fechaInicio = $request->filled('fecha_inicio') ? $request->fecha_inicio : ($fechasDefault['fecha_inicio'] ?? null);
            $fechaFin = $request->filled('fecha_fin') ? $request->fecha_fin : ($fechasDefault['fecha_fin'] ?? null);

            if ($fechaInicio && $fechaFin) {
                $query->whereBetween('fecha', [$fechaInicio, $fechaFin]);
            } elseif ($fechaInicio) {
                $query->where('fecha', '>=', $fechaInicio);
            } elseif ($fechaFin) {
                $query->where('fecha', '<=', $fechaFin);
            }

            if ($request->filled('tipo_asistencia')) {
                $tipoAsistencia = TipoAsistencia::where('codigo', $request->tipo_asistencia)->first();
                if ($tipoAsistencia) {
                    $query->where('tipo_asistencia_id', $tipoAsistencia->tipo_asistencia_id);
                }
            }

            if ($request->filled('curso_id')) {
                $curso = InfCurso::find($request->curso_id);
                if ($curso) {
                    $query->whereHas('matricula', function($q) use ($curso) {
                        $q->where('idGrado', $curso->grado_id)
                          ->where('idSeccion', $curso->seccion_id);
                    });
                }
            }

            if ($request->filled('estudiante_id')) {
                $query->whereHas('matricula.estudiante', function($q) use ($request) {
                    $q->where('estudiante_id', $request->estudiante_id);
                });
            }

            if ($request->filled('docente_id')) {
                $query->whereHas('cursoAsignatura', function($q) use ($request) {
                    $q->where('profesor_id', $request->docente_id);
                });
            }

            if ($request->filled('nivel_id')) {
                $query->whereHas('matricula.grado', function($q) use ($request) {
                    $q->where('nivel_id', $request->nivel_id);
                });
            }

            if ($request->filled('grado_id')) {
                $query->whereHas('matricula', function($q) use ($request) {
                    $q->where('idGrado', $request->grado_id);
                });
            }

            if ($request->filled('seccion_id')) {
                $query->whereHas('matricula', function($q) use ($request) {
                    $q->where('idSeccion', $request->seccion_id);
                });
            }

            if ($request->filled('asignatura_id')) {
                $query->whereHas('cursoAsignatura', function($q) use ($request) {
                    $q->where('asignatura_id', $request->asignatura_id);
                });
            }

            // Solo matrículas activas (opcional para evitar problemas con datos históricos)
            // $query->whereHas('matricula', function($q) {
            //     $q->where('estado', 'Activo');
            // });

            // Si no hay filtros aplicados, mostrar TODAS las asistencias por defecto
            if (!$request->filled('fecha_inicio') && !$request->filled('fecha_fin') &&
                !$request->filled('tipo_asistencia') && !$request->filled('curso_id') &&
                !$request->filled('estudiante_id') && !$request->filled('docente_id') &&
                !$request->filled('nivel_id') && !$request->filled('grado_id') &&
                !$request->filled('seccion_id') && !$request->filled('asignatura_id')) {
                // No aplicar filtro de fecha - mostrar todo el historial disponible
                // Esto mantiene consistencia con las estadísticas que muestran el total completo
            }

            // Calcular estadísticas aplicando TODOS los filtros INCLUYENDO el de tipo de asistencia
            // Si hay filtro de tipo, las estadísticas serán para ese tipo específico
            $statsQuery = AsistenciaAsignatura::with(['tipoAsistencia']);

            // Aplicar filtros de fecha usando SOLO los valores del request (no valores por defecto)
            $statsFechaInicio = $request->filled('fecha_inicio') ? $request->fecha_inicio : null;
            $statsFechaFin = $request->filled('fecha_fin') ? $request->fecha_fin : null;

            if ($statsFechaInicio && $statsFechaFin) {
                $statsQuery->whereBetween('fecha', [$statsFechaInicio, $statsFechaFin]);
            } elseif ($statsFechaInicio) {
                $statsQuery->where('fecha', '>=', $statsFechaInicio);
            } elseif ($statsFechaFin) {
                $statsQuery->where('fecha', '<=', $statsFechaFin);
            }

            // Aplicar filtro de tipo de asistencia (SÍ incluirlo en las estadísticas)
            if ($request->filled('tipo_asistencia')) {
                $tipoAsistencia = TipoAsistencia::where('codigo', $request->tipo_asistencia)->first();
                if ($tipoAsistencia) {
                    $statsQuery->where('tipo_asistencia_id', $tipoAsistencia->tipo_asistencia_id);
                }
            }

            // Aplicar TODOS los otros filtros
            if ($request->filled('curso_id')) {
                $curso = InfCurso::find($request->curso_id);
                if ($curso) {
                    $statsQuery->whereHas('matricula', function($q) use ($curso) {
                        $q->where('idGrado', $curso->grado_id)
                          ->where('idSeccion', $curso->seccion_id);
                    });
                }
            }

            if ($request->filled('estudiante_id')) {
                $statsQuery->whereHas('matricula.estudiante', function($q) use ($request) {
                    $q->where('estudiante_id', $request->estudiante_id);
                });
            }

            if ($request->filled('docente_id')) {
                $statsQuery->whereHas('cursoAsignatura', function($q) use ($request) {
                    $q->where('profesor_id', $request->docente_id);
                });
            }

            if ($request->filled('nivel_id')) {
                $statsQuery->whereHas('matricula.grado', function($q) use ($request) {
                    $q->where('nivel_id', $request->nivel_id);
                });
            }

            if ($request->filled('grado_id')) {
                $statsQuery->whereHas('matricula', function($q) use ($request) {
                    $q->where('idGrado', $request->grado_id);
                });
            }

            if ($request->filled('seccion_id')) {
                $statsQuery->whereHas('matricula', function($q) use ($request) {
                    $q->where('idSeccion', $request->seccion_id);
                });
            }

            if ($request->filled('asignatura_id')) {
                $statsQuery->whereHas('cursoAsignatura', function($q) use ($request) {
                    $q->where('asignatura_id', $request->asignatura_id);
                });
            }

            // Calcular estadísticas del conjunto completo
            $totalRegistros = $statsQuery->count();

            // Debug: Verificar tipos de asistencia disponibles
            $tiposAsistencia = TipoAsistencia::all();
            \Log::info('Tipos de asistencia disponibles:', $tiposAsistencia->toArray());

            $totalPresentes = $statsQuery->whereHas('tipoAsistencia', function($q) {
                $q->where('computa_falta', 0);
            })->count();

            $totalAusentes = $statsQuery->whereHas('tipoAsistencia', function($q) {
                $q->where('computa_falta', 1);
            })->count();

            // Debug simple de las estadísticas
            \Log::info('Estadísticas calculadas:', [
                'total_registros' => $totalRegistros,
                'total_presentes' => $totalPresentes,
                'total_ausentes' => $totalAusentes,
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_fin' => $request->fecha_fin,
                'tipo_asistencia' => $request->tipo_asistencia
            ]);

            // Verificar tipos de asistencia disponibles
            $tiposDisponibles = TipoAsistencia::where('activo', 1)->get(['codigo', 'computa_falta']);
            \Log::info('Tipos de asistencia activos:', $tiposDisponibles->toArray());

            $stats = (object) [
                'total_registros' => $totalRegistros,
                'total_presentes' => $totalPresentes,
                'total_ausentes' => $totalAusentes
            ];

            // Obtener datos paginados para mostrar
            $asistencias = $query->orderBy('fecha', 'desc')
                                ->orderBy('hora_registro', 'desc')
                                ->paginate(20);

            // Calcular porcentaje de asistencia basado en estadísticas reales
            $totalRegistros = (int)$stats->total_registros;
            $totalPresentes = (int)$stats->total_presentes;
            $totalAusentes = (int)$stats->total_ausentes;

            $porcentajeAsistencia = $totalRegistros > 0
                ? round(($totalPresentes / $totalRegistros) * 100, 1) . '%'
                : '0%';

            $estadisticas = [
                'total_registros' => $totalRegistros,
                'total_presentes' => $totalPresentes,
                'total_ausentes' => $totalAusentes,
                'porcentaje_asistencia' => $porcentajeAsistencia
            ];

            return response()->json([
                'success' => true,
                'data' => $asistencias,
                'estadisticas' => $estadisticas,
                'estadisticas_adicionales' => [
                    'total_estudiantes_unicos' => 0,
                    'promedio_asistencia_diaria' => 0,
                    'dias_analizados' => 0,
                    'estudiantes_riesgo' => []
                ],
                'filtros_aplicados' => []
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

    // ========== MÉTODOS PARA DOCENTES ==========

    /**
     * Vista principal del docente (índice simple)
     */
    public function docenteIndex()
    {
        try {
            // Verificar permisos
            if (!Auth::user()->hasRole('Docente') && !Auth::user()->hasRole('Administrador')) {
                abort(403, 'No tienes permisos para acceder a esta sección.');
            }

            // Verificar que el usuario tenga relación con docente
            if (!Auth::user()->persona || !Auth::user()->persona->docente) {
                return view('asistencia.docente-index', [
                    'error' => 'Tu cuenta de docente no está completamente configurada. Contacta al administrador.',
                    'clases_hoy' => collect(),
                    'estadisticas' => [
                        'total_clases_hoy' => 0,
                        'total_estudiantes' => 0,
                        'asistencias_pendientes' => 0,
                        'inasistencias_hoy' => 0,
                    ]
                ]);
            }

            $docente = Auth::user()->persona->docente;

            // Obtener clases de hoy
            $clases_hoy = \App\Models\SesionClase::with(['cursoAsignatura.curso.grado', 'cursoAsignatura.curso.seccion', 'cursoAsignatura.asignatura', 'aula'])
                ->whereHas('cursoAsignatura', function($query) use ($docente) {
                    $query->where('profesor_id', $docente->profesor_id);
                })
                ->whereDate('fecha', today())
                ->orderBy('hora_inicio')
                ->get()
                ->map(function($sesion) {
                    $sesion->tiene_asistencia_hoy = AsistenciaAsignatura::whereHas('matricula', function($q) use ($sesion) {
                        $q->whereHas('curso', function($cq) use ($sesion) {
                            $cq->where('curso_id', $sesion->cursoAsignatura->curso_id);
                        });
                    })
                    ->whereDate('fecha', $sesion->fecha)
                    ->exists();
                    return $sesion;
                });

            // Estadísticas básicas
            $estadisticas = [
                'total_clases_hoy' => $clases_hoy->count(),
                'total_estudiantes' => $clases_hoy->sum(function($sesion) {
                    return $sesion->cursoAsignatura->curso->matriculas()->where('estado', 'Activo')->count();
                }),
                'asistencias_pendientes' => $clases_hoy->where('tiene_asistencia_hoy', false)->count(),
                'inasistencias_hoy' => 0, // Se puede calcular si es necesario
            ];

            return view('asistencia.docente-index', compact('clases_hoy', 'estadisticas'));

        } catch (\Exception $e) {
            \Log::error('Error en docenteIndex: ' . $e->getMessage());
            return view('asistencia.docente-index', [
                'error' => 'Error al cargar la página: ' . $e->getMessage(),
                'clases_hoy' => collect(),
                'estadisticas' => [
                    'total_clases_hoy' => 0,
                    'total_estudiantes' => 0,
                    'asistencias_pendientes' => 0,
                    'inasistencias_hoy' => 0,
                ]
            ]);
        }
    }

    // ========== MÉTODOS PARA REPRESENTANTES ==========

    /**
     * Vista principal para representantes
     */
    public function representanteIndex()
    {
        try {
            // Verificar autenticación
            if (!Auth::check()) {
                return redirect()->route('login.index')->with('error', 'Debes iniciar sesión para acceder a esta sección.');
            }

            $user = Auth::user();

            // Verificar permisos
            if (!$user->hasRole('representante')) {
                abort(403, 'No tienes permisos para acceder a esta sección. Rol requerido: representante. Tu rol actual: ' . implode(', ', $user->getRoleNames()));
            }

            // Verificar que el usuario tenga relación con persona
            if (!$user->persona) {
                abort(500, 'Tu cuenta no tiene una persona asociada. Contacta al administrador.');
            }

            // Verificar que la persona tenga relación con representante
            if (!$user->persona->representante) {
                abort(500, 'Tu cuenta de persona no tiene un representante asociado. Contacta al administrador.');
            }

            $representante = $user->persona->representante;

            // Obtener estudiantes del representante
            $estudiantes = $representante->estudiantes()
                ->with(['persona', 'matricula.grado', 'matricula.seccion'])
                ->get();

            // Debug: Log the query and results
            \Log::info('Representante ID: ' . $representante->representante_id);
            \Log::info('Estudiantes query count: ' . $estudiantes->count());
            foreach ($estudiantes as $est) {
                \Log::info('Estudiante: ' . ($est->persona ? $est->persona->nombres . ' ' . $est->persona->apellidos : 'Sin nombre') . ' (ID: ' . $est->estudiante_id . ')');
            }

            $estudiantes = $estudiantes->map(function($estudiante) {
                if (!$estudiante->matricula) {
                    $estudiante->asistencia_hoy = null;
                    $estudiante->porcentaje_asistencia = 0;
                    $estudiante->inasistencias_mes = 0;
                    return $estudiante;
                }

                // Calcular estadísticas del mes actual
                $mesActual = now()->month;
                $anioActual = now()->year;

                $asistenciasMes = AsistenciaAsignatura::where('matricula_id', $estudiante->matricula->matricula_id)
                    ->whereMonth('fecha', $mesActual)
                    ->whereYear('fecha', $anioActual)
                    ->get();

                $totalAsistencias = $asistenciasMes->count();
                $inasistencias = $asistenciasMes->where('tipo_asistencia_id', TipoAsistencia::where('codigo', 'A')->first()->id ?? 2)->count();

                $estudiante->asistencia_hoy = AsistenciaAsignatura::where('matricula_id', $estudiante->matricula->matricula_id)
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
                'justificaciones_pendientes' => JustificacionAsistencia::whereIn('matricula_id', $estudiantes->pluck('matricula.matricula_id'))
                    ->where('estado', 'pendiente')
                    ->count(),
                'total_asistencias_mes' => $estudiantes->sum(function($e) {
                    return AsistenciaAsignatura::where('matricula_id', $e->matricula->matricula_id ?? 0)
                        ->whereMonth('fecha', now()->month)
                        ->whereYear('fecha', now()->year)
                        ->where('tipo_asistencia_id', TipoAsistencia::where('codigo', 'P')->first()->id ?? 1)
                        ->count();
                }),
                'justificaciones_aprobadas' => JustificacionAsistencia::whereIn('matricula_id', $estudiantes->pluck('matricula.matricula_id'))
                    ->where('estado', 'aprobado')
                    ->count()
            ];

            return view('asistencia.representante-index', compact('estudiantes', 'estadisticasGenerales'));

        } catch (\Exception $e) {
            \Log::error('Error en representanteIndex: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());

            // Mostrar página de error con información de debug
            return view('asistencia.representante-index', [
                'error' => 'Error al cargar la página: ' . $e->getMessage(),
                'debug' => [
                    'user_authenticated' => Auth::check(),
                    'user_id' => Auth::id(),
                    'user_roles' => Auth::check() ? Auth::user()->getRoleNames() : [],
                    'has_persona' => Auth::check() && Auth::user()->persona ? true : false,
                    'has_representante' => Auth::check() && Auth::user()->persona && Auth::user()->persona->representante ? true : false,
                ],
                'estudiantes' => collect(),
                'estadisticasGenerales' => [
                    'total_estudiantes' => 0,
                    'promedio_asistencia' => 0,
                    'total_inasistencias_mes' => 0,
                    'justificaciones_pendientes' => 0,
                    'total_asistencias_mes' => 0,
                    'justificaciones_aprobadas' => 0
                ]
            ]);
        }
    }

    // ========== MÉTODOS PARA REPORTES ==========

    /**
     * Guardar reporte generado
     */
    public function guardarReporteGenerado(Request $request)
    {
        try {
            \Log::info('guardarReporteGenerado called', $request->all());

            // Verificar permisos
            if (!Auth::check()) {
                \Log::error('Usuario no autenticado');
                return response()->json(['success' => false, 'message' => 'Usuario no autenticado.'], 401);
            }

            if (!Auth::user()->hasRole('Administrador')) {
                \Log::error('Usuario sin permisos de administrador', ['user_id' => Auth::id(), 'roles' => Auth::user()->getRoleNames()]);
                return response()->json(['success' => false, 'message' => 'No tienes permisos de administrador.'], 403);
            }

            // Validar datos requeridos
            try {
                $request->validate([
                    'tipo_reporte' => 'required|string',
                    'formato' => 'required|in:pdf,excel',
                    'fecha_inicio' => 'required|date',
                    'fecha_fin' => 'required|date'
                ]);
                \Log::info('Validación pasada correctamente');
            } catch (\Illuminate\Validation\ValidationException $ve) {
                \Log::error('Error de validación', ['errors' => $ve->errors()]);
                return response()->json(['success' => false, 'message' => 'Datos de entrada inválidos.', 'errors' => $ve->errors()], 422);
            }

            // Crear registro del reporte
            try {
                $usuarioId = Auth::id();
                \Log::info('Usuario ID para reporte:', ['usuario_id' => $usuarioId, 'user' => Auth::user()]);

                // Crear nombre de archivo temporal
                $tempFileName = 'temp_' . time() . '.' . $request->formato;

                $reporte = ReporteGenerado::create([
                    'usuario_id' => $usuarioId,
                    'tipo_reporte' => $request->tipo_reporte,
                    'formato' => $request->formato,
                    'fecha_inicio' => $request->fecha_inicio,
                    'fecha_fin' => $request->fecha_fin,
                    'filtros_aplicados' => json_encode($request->except(['_token'])),
                    'archivo_nombre' => $tempFileName, // Nombre temporal requerido
                    'fecha_generacion' => now(),
                    'registros_totales' => 0
                ]);

                // Forzar refresh del modelo para obtener el ID
                $reporte->refresh();

                \Log::info('Reporte creado en BD', [
                    'id' => $reporte->id,
                    'reporte_id_field' => $reporte->reporte_id,
                    'exists' => $reporte->exists,
                    'was_recently_created' => $reporte->wasRecentlyCreated
                ]);
            } catch (\Illuminate\Database\QueryException $qe) {
                \Log::error('Database error creando reporte: ' . $qe->getMessage());
                \Log::error('SQL: ' . $qe->getSql());
                \Log::error('Bindings: ', $qe->getBindings());
                return response()->json(['success' => false, 'message' => 'Error de base de datos al crear el reporte.'], 500);
            } catch (\Exception $e) {
                \Log::error('Error general creando reporte en BD: ' . $e->getMessage());
                \Log::error('Stack trace: ' . $e->getTraceAsString());
                \Log::error('Datos enviados:', [
                    'usuario_id' => Auth::id(),
                    'tipo_reporte' => $request->tipo_reporte,
                    'formato' => $request->formato,
                    'fecha_inicio' => $request->fecha_inicio,
                    'fecha_fin' => $request->fecha_fin
                ]);
                return response()->json(['success' => false, 'message' => 'Error al crear el registro del reporte.'], 500);
            }

            // Generar el reporte
            try {
                if ($request->formato === 'pdf') {
                    \Log::info('Generando PDF');
                    $this->generarReportePDF($reporte, $request->all());
                } else {
                    \Log::info('Generando Excel');
                    $this->generarReporteExcel($reporte, $request->all());
                }

                $reporte->update(['estado' => 'completado']);
                \Log::info('Reporte completado exitosamente', [
                    'reporte_id' => $reporte->id,
                    'reporte_id_field' => $reporte->reporte_id,
                    'all_attributes' => $reporte->toArray()
                ]);

                return response()->json([
                    'success' => true,
                    'reporte_id' => $reporte->id, // Use 'id' instead of 'reporte_id'
                    'message' => 'Reporte generado exitosamente'
                ]);

            } catch (\Exception $e) {
                \Log::error('Error generando reporte: ' . $e->getMessage());
                \Log::error('Stack trace: ' . $e->getTraceAsString());
                $reporte->update(['estado' => 'error']);
                return response()->json(['success' => false, 'message' => 'Error al generar el reporte: ' . $e->getMessage()], 500);
            }

        } catch (\Exception $e) {
            \Log::error('Error general en guardarReporteGenerado: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json(['success' => false, 'message' => 'Error interno del servidor: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Descargar reporte histórico
     */
    public function descargarReporteHistorial($reporteId)
    {
        try {
            $reporte = ReporteGenerado::findOrFail($reporteId);

            // Verificar permisos
            if (!Auth::check() || !Auth::user()->hasRole('Administrador')) {
                abort(403, 'No tienes permisos.');
            }

            // Verificar que el usuario sea el propietario del reporte
            if ($reporte->usuario_id !== Auth::id()) {
                abort(403, 'No tienes permisos para descargar este reporte.');
            }

            $archivoPath = storage_path('app/public/reportes/' . $reporte->archivo_nombre);

            if (!file_exists($archivoPath)) {
                abort(404, 'Archivo no encontrado.');
            }

            $nombreDescarga = 'reporte_asistencia_' . $reporte->tipo_reporte . '_' . $reporte->fecha_inicio . '_a_' . $reporte->fecha_fin . '.' . $reporte->formato;

            return response()->download($archivoPath, $nombreDescarga);

        } catch (\Exception $e) {
            \Log::error('Error en descargarReporteHistorial: ' . $e->getMessage());
            abort(500, 'Error al descargar el reporte');
        }
    }

    /**
     * Generar reporte PDF
     */
    private function generarReportePDF($reporte, $filtros)
    {
        try {
            \Log::info('Generando reporte PDF', ['reporte_id' => $reporte->reporte_id, 'filtros' => $filtros]);

            // Obtener datos del reporte usando una consulta más simple para evitar problemas de relaciones
            $query = DB::table('asistenciasasignatura as aa')
                ->select([
                    'aa.fecha',
                    'aa.hora_registro',
                    DB::raw('CONCAT(p.nombres, " ", p.apellidos) as estudiante'),
                    'g.descripcion as grado',
                    's.nombre as seccion',
                    'a.nombre as asignatura',
                    'ta.nombre as tipo_asistencia',
                    'ta.computa_falta'
                ])
                ->join('matriculas as m', 'aa.matricula_id', '=', 'm.matricula_id')
                ->join('estudiantes as e', 'm.estudiante_id', '=', 'e.estudiante_id')
                ->join('personas as p', 'e.persona_id', '=', 'p.id_persona')
                ->join('grados as g', 'm.idGrado', '=', 'g.grado_id')
                ->join('secciones as s', 'm.idSeccion', '=', 's.seccion_id')
                ->join('cursoasignaturas as ca', 'aa.curso_asignatura_id', '=', 'ca.curso_asignatura_id')
                ->join('asignaturas as a', 'ca.asignatura_id', '=', 'a.asignatura_id')
                ->join('tiposasistencia as ta', 'aa.tipo_asistencia_id', '=', 'ta.tipo_asistencia_id');

            // Aplicar filtros
            if (isset($filtros['fecha_inicio']) && isset($filtros['fecha_fin'])) {
                $query->whereBetween('aa.fecha', [$filtros['fecha_inicio'], $filtros['fecha_fin']]);
                \Log::info('Filtro fecha aplicado', ['inicio' => $filtros['fecha_inicio'], 'fin' => $filtros['fecha_fin']]);
            }

            if (isset($filtros['tipo_asistencia'])) {
                $query->where('ta.codigo', $filtros['tipo_asistencia']);
                \Log::info('Filtro tipo asistencia aplicado', ['tipo' => $filtros['tipo_asistencia']]);
            }

            // Aplicar otros filtros según el tipo de reporte
            if (isset($filtros['estudiante_id'])) {
                $query->where('e.estudiante_id', $filtros['estudiante_id']);
                \Log::info('Filtro estudiante aplicado', ['estudiante_id' => $filtros['estudiante_id']]);
            }

            if (isset($filtros['grado_id'])) {
                $query->where('m.idGrado', $filtros['grado_id']);
                \Log::info('Filtro grado aplicado', ['grado_id' => $filtros['grado_id']]);
            }

            if (isset($filtros['docente_id'])) {
                $query->where('ca.profesor_id', $filtros['docente_id']);
                \Log::info('Filtro docente aplicado', ['docente_id' => $filtros['docente_id']]);
            }

            if (isset($filtros['asignatura_id'])) {
                $query->where('ca.asignatura_id', $filtros['asignatura_id']);
                \Log::info('Filtro asignatura aplicado', ['asignatura_id' => $filtros['asignatura_id']]);
            }

            // Limitar resultados para evitar agotamiento de memoria
            $maxRegistros = 500; // Máximo 500 registros por reporte PDF
            $asistencias = $query->orderBy('aa.fecha')->limit($maxRegistros)->get();
            \Log::info('Asistencias obtenidas', ['count' => $asistencias->count(), 'limit' => $maxRegistros]);

            // Verificar límite para PDF - prevenir generación si hay demasiados registros
            $querySinLimite = clone $query;
            $totalRegistrosQuery = $querySinLimite->count();

            if ($totalRegistrosQuery > $maxRegistros) {
                throw new \Exception("El reporte contiene $totalRegistrosQuery registros. Use Excel para reportes grandes o aplique filtros más específicos para generar PDF (máximo $maxRegistros registros).");
            }

            // Verificar si se alcanzó el límite
            $querySinLimite = clone $query;
            $totalRegistrosQuery = $querySinLimite->count();

            // Calcular estadísticas
            $totalRegistros = min($asistencias->count(), $totalRegistrosQuery);
            $totalPresentes = $asistencias->where('computa_falta', 0)->count();
            $totalAusentes = $asistencias->where('computa_falta', 1)->count();

            // Si se alcanzó el límite, ajustar estadísticas
            if ($totalRegistrosQuery > $maxRegistros) {
                \Log::warning('Límite de registros alcanzado', [
                    'total_real' => $totalRegistrosQuery,
                    'limite' => $maxRegistros,
                    'mostrados' => $asistencias->count()
                ]);
                $totalRegistros = $totalRegistrosQuery; // Mostrar total real en estadísticas
            }

            \Log::info('Estadísticas calculadas', [
                'total' => $totalRegistros,
                'presentes' => $totalPresentes,
                'ausentes' => $totalAusentes,
                'limite_alcanzado' => $totalRegistrosQuery > $maxRegistros
            ]);

            // Generar PDF con datos simples
            $pdf = Pdf::loadView('reportes.asistencia_pdf', compact(
                'asistencias',
                'filtros',
                'totalRegistros',
                'totalPresentes',
                'totalAusentes',
                'reporte'
            ));

            // Guardar archivo
            $nombreArchivo = 'reporte_' . $reporte->reporte_id . '.pdf';
            $rutaCompleta = storage_path('app/public/reportes/' . $nombreArchivo);

            // Crear directorio si no existe
            $directorio = dirname($rutaCompleta);
            if (!file_exists($directorio)) {
                mkdir($directorio, 0755, true);
                \Log::info('Directorio creado', ['path' => $directorio]);
            }

            $pdf->save($rutaCompleta);
            \Log::info('PDF guardado', ['path' => $rutaCompleta, 'size' => filesize($rutaCompleta)]);

            // Actualizar reporte con nombre de archivo
            $reporte->update(['archivo_nombre' => $nombreArchivo]);
            \Log::info('Reporte actualizado en BD', ['archivo_nombre' => $nombreArchivo]);

        } catch (\Exception $e) {
            \Log::error('Error generando PDF', [
                'reporte_id' => $reporte->reporte_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Generar reporte Excel
     */
    private function generarReporteExcel($reporte, $filtros)
    {
        try {
            \Log::info('Generando reporte Excel', ['reporte_id' => $reporte->reporte_id, 'filtros' => $filtros]);

            // Obtener datos del reporte usando la misma consulta SQL que PDF
            $query = DB::table('asistenciasasignatura as aa')
                ->select([
                    'aa.fecha',
                    'aa.hora_registro',
                    DB::raw('CONCAT(p.nombres, " ", p.apellidos) as estudiante'),
                    'g.descripcion as grado',
                    's.nombre as seccion',
                    'a.nombre as asignatura',
                    'ta.nombre as tipo_asistencia',
                    'ta.computa_falta'
                ])
                ->join('matriculas as m', 'aa.matricula_id', '=', 'm.matricula_id')
                ->join('estudiantes as e', 'm.estudiante_id', '=', 'e.estudiante_id')
                ->join('personas as p', 'e.persona_id', '=', 'p.id_persona')
                ->join('grados as g', 'm.idGrado', '=', 'g.grado_id')
                ->join('secciones as s', 'm.idSeccion', '=', 's.seccion_id')
                ->join('cursoasignaturas as ca', 'aa.curso_asignatura_id', '=', 'ca.curso_asignatura_id')
                ->join('asignaturas as a', 'ca.asignatura_id', '=', 'a.asignatura_id')
                ->join('tiposasistencia as ta', 'aa.tipo_asistencia_id', '=', 'ta.tipo_asistencia_id');

            // Aplicar filtros (igual que PDF)
            if (isset($filtros['fecha_inicio']) && isset($filtros['fecha_fin'])) {
                $query->whereBetween('aa.fecha', [$filtros['fecha_inicio'], $filtros['fecha_fin']]);
                \Log::info('Filtro fecha aplicado', ['inicio' => $filtros['fecha_inicio'], 'fin' => $filtros['fecha_fin']]);
            }

            if (isset($filtros['tipo_asistencia'])) {
                $query->where('ta.codigo', $filtros['tipo_asistencia']);
                \Log::info('Filtro tipo asistencia aplicado', ['tipo' => $filtros['tipo_asistencia']]);
            }

            if (isset($filtros['estudiante_id'])) {
                $query->where('e.estudiante_id', $filtros['estudiante_id']);
                \Log::info('Filtro estudiante aplicado', ['estudiante_id' => $filtros['estudiante_id']]);
            }

            if (isset($filtros['grado_id'])) {
                $query->where('m.idGrado', $filtros['grado_id']);
                \Log::info('Filtro grado aplicado', ['grado_id' => $filtros['grado_id']]);
            }

            if (isset($filtros['docente_id'])) {
                $query->where('ca.profesor_id', $filtros['docente_id']);
                \Log::info('Filtro docente aplicado', ['docente_id' => $filtros['docente_id']]);
            }

            if (isset($filtros['asignatura_id'])) {
                $query->where('ca.asignatura_id', $filtros['asignatura_id']);
                \Log::info('Filtro asignatura aplicado', ['asignatura_id' => $filtros['asignatura_id']]);
            }

            // Para Excel no hay límite de registros
            $asistencias = $query->orderBy('aa.fecha')->get();
            \Log::info('Registros para Excel obtenidos', ['count' => $asistencias->count()]);

            // Crear Excel
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Headers
            $sheet->setCellValue('A1', 'Fecha');
            $sheet->setCellValue('B1', 'Hora Registro');
            $sheet->setCellValue('C1', 'Estudiante');
            $sheet->setCellValue('D1', 'Grado');
            $sheet->setCellValue('E1', 'Sección');
            $sheet->setCellValue('F1', 'Asignatura');
            $sheet->setCellValue('G1', 'Tipo Asistencia');
            $sheet->setCellValue('H1', 'Estado');

            // Data
            $row = 2;
            foreach ($asistencias as $asistencia) {
                $sheet->setCellValue('A' . $row, $asistencia->fecha);
                $sheet->setCellValue('B' . $row, $asistencia->hora_registro ?? '');
                $sheet->setCellValue('C' . $row, $asistencia->estudiante ?? 'N/A');
                $sheet->setCellValue('D' . $row, $asistencia->grado ?? 'N/A');
                $sheet->setCellValue('E' . $row, $asistencia->seccion ?? 'N/A');
                $sheet->setCellValue('F' . $row, $asistencia->asignatura ?? 'N/A');
                $sheet->setCellValue('G' . $row, $asistencia->tipo_asistencia ?? 'N/A');
                $sheet->setCellValue('H' . $row, ($asistencia->computa_falta == 0) ? 'Presente' : 'Ausente');
                $row++;
            }

            // Guardar archivo
            $extension = $reporte->formato === 'excel' ? 'xlsx' : 'pdf';
            $nombreArchivo = 'reporte_' . $reporte->id . '.' . $extension;

            // Crear directorio si no existe
            $directorio = dirname($rutaCompleta);
            if (!file_exists($directorio)) {
                mkdir($directorio, 0755, true);
                \Log::info('Directorio creado para Excel', ['path' => $directorio]);
            }

            $writer = new Xlsx($spreadsheet);
            $writer->save($rutaCompleta);
            \Log::info('Excel guardado', ['path' => $rutaCompleta, 'size' => filesize($rutaCompleta)]);

            // Actualizar reporte con nombre de archivo y tamaño
            $reporte->update([
                'archivo_nombre' => $nombreArchivo,
                'tamano_archivo_kb' => round(filesize($rutaCompleta) / 1024, 2)
            ]);
            \Log::info('Reporte Excel actualizado en BD', ['archivo_nombre' => $nombreArchivo]);

        } catch (\Exception $e) {
            \Log::error('Error generando Excel', [
                'reporte_id' => $reporte->reporte_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}
