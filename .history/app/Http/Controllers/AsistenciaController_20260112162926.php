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

        // Obtener los últimos 5 reportes generados por el usuario actual
        $ultimosReportes = ReporteGenerado::where('usuario_id', Auth::id())
            ->orderBy('fecha_generacion', 'desc')
            ->limit(5)
            ->get();

        return view('asistencia.reportes', compact('anioActual', 'ultimosReportes'));
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

    /**
     * Vista de verificación de asistencia
     */
    public function verificar()
    {
        try {
            // Verificar autenticación
            if (!Auth::check()) {
                return redirect()->route('login.index')->with('error', 'Debes iniciar sesión para acceder a esta sección.');
            }

            // Verificar permisos - permitir docentes y administradores
            $user = Auth::user();
            if (!$user->hasRole('Docente') && !$user->hasRole('Administrador')) {
                abort(403, 'No tienes permisos para acceder a esta sección.');
            }

            // Obtener año lectivo activo
            $anioActual = InfAnioLectivo::where('estado', 'Activo')->first();

            // Obtener justificaciones con relaciones necesarias
            $justificaciones = JustificacionAsistencia::with([
                'matricula.estudiante.persona',
                'matricula.grado',
                'matricula.seccion',
                'usuario.persona'
            ])
            ->orderBy('fecha', 'desc')
            ->paginate(15);

            return view('asistencia.verificar', compact('anioActual', 'justificaciones'));

        } catch (\Exception $e) {
            \Log::error('Error en verificar: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cargar la página de verificación: ' . $e->getMessage());
        }
    }

    /**
     * Procesar verificación de justificación (aprobar/rechazar)
     */
    public function procesarVerificacion(Request $request)
    {
        try {
            // Verificar autenticación
            if (!Auth::check()) {
                return response()->json(['success' => false, 'message' => 'Usuario no autenticado.'], 401);
            }

            // Verificar permisos
            $user = Auth::user();
            if (!$user->hasRole('Docente') && !$user->hasRole('Administrador')) {
                return response()->json(['success' => false, 'message' => 'No tienes permisos para esta acción.'], 403);
            }

            // Validar datos
            $request->validate([
                'justificacion_id' => 'required|integer|exists:justificaciones_asistencia,id',
                'accion' => 'required|in:Aprobar,Rechazar',
                'observaciones' => 'nullable|string|max:500'
            ]);

            // Buscar la justificación
            $justificacion = JustificacionAsistencia::with([
                'matricula.estudiante.persona',
                'matricula.grado',
                'matricula.seccion'
            ])->findOrFail($request->justificacion_id);

            // Verificar que la justificación esté pendiente
            if ($justificacion->estado !== 'pendiente') {
                return response()->json(['success' => false, 'message' => 'Esta justificación ya ha sido procesada.'], 400);
            }

            // Procesar la justificación
            $nuevoEstado = $request->accion === 'Aprobar' ? 'Aprobado' : 'Rechazado';
            $justificacion->update([
                'estado' => $nuevoEstado,
                'observaciones_admin' => $request->observaciones,
                'revisado_por' => Auth::id(),
                'fecha_revision' => now()
            ]);

            // Si se aprueba, crear registro de asistencia justificada
            // Nota: Para simplificar, por ahora no creamos registro automático de asistencia
            // Esto puede requerir más lógica para determinar la asignatura específica
            if ($nuevoEstado === 'aprobado') {
                \Log::info('Justificación aprobada', [
                    'justificacion_id' => $justificacion->id,
                    'matricula_id' => $justificacion->matricula_id,
                    'fecha_falta' => $justificacion->fecha_falta
                ]);
                // TODO: Implementar creación automática de registro de asistencia justificada
                // cuando se determine la lógica para identificar la asignatura específica
            }

            // Crear notificación para el representante si existe
            if ($justificacion->matricula && $justificacion->matricula->estudiante) {
                $estudiante = $justificacion->matricula->estudiante;
                if ($estudiante->representante) {
                    Notificacion::create([
                        'usuario_id' => $estudiante->representante->persona->usuario->usuario_id,
                        'tipo' => 'justificacion_procesada',
                        'titulo' => 'Justificación Procesada',
                        'mensaje' => "La justificación de {$estudiante->persona->nombres} {$estudiante->persona->apellidos} para la fecha {$justificacion->fecha_falta->format('d/m/Y')} ha sido {$nuevoEstado}.",
                        'datos' => json_encode([
                            'justificacion_id' => $justificacion->id,
                            'estudiante_id' => $estudiante->estudiante_id,
                            'estado' => $nuevoEstado,
                            'fecha_falta' => $justificacion->fecha_falta->format('Y-m-d')
                        ]),
                        'leida' => false
                    ]);
                }
            }

            \Log::info('Justificación procesada', [
                'justificacion_id' => $justificacion->id,
                'accion' => $request->accion,
                'estado' => $nuevoEstado,
                'procesado_por' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => "Justificación {$nuevoEstado} exitosamente.",
                'estado' => $nuevoEstado
            ]);

        } catch (\Illuminate\Validation\ValidationException $ve) {
            return response()->json([
                'success' => false,
                'message' => 'Datos inválidos.',
                'errors' => $ve->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error procesando verificación: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor al procesar la justificación.'
            ], 500);
        }
    }

    /**
     * Dashboard del docente para asistencia
     */
    public function docenteDashboard()
    {
        try {
            // Verificar permisos
            if (!Auth::user()->hasRole('Docente') && !Auth::user()->hasRole('Administrador')) {
                abort(403, 'No tienes permisos para acceder a esta sección.');
            }

            // Verificar que el usuario tenga relación con docente
            if (!Auth::user()->persona || !Auth::user()->persona->docente) {
                return redirect()->route('home')->with('error', 'Tu cuenta de docente no está completamente configurada.');
            }

            $docente = Auth::user()->persona->docente;

            // Obtener clases de hoy con información detallada
            $clases_hoy = \App\Models\SesionClase::with(['cursoAsignatura.curso.grado', 'cursoAsignatura.curso.seccion', 'cursoAsignatura.asignatura', 'aula'])
                ->whereHas('cursoAsignatura', function($query) use ($docente) {
                    $query->where('profesor_id', $docente->profesor_id);
                })
                ->whereDate('fecha', today())
                ->orderBy('hora_inicio')
                ->get()
                ->map(function($sesion) {
                    $sesion->tiene_asistencia_hoy = \App\Models\AsistenciaAsignatura::whereHas('matricula', function($q) use ($sesion) {
                        $q->whereHas('curso', function($cq) use ($sesion) {
                            $cq->where('curso_id', $sesion->cursoAsignatura->curso_id);
                        });
                    })
                    ->whereDate('fecha', $sesion->fecha)
                    ->exists();
                    return $sesion;
                });

            // Estadísticas básicas del docente
            $estadisticas = [
                'total_clases_hoy' => $clases_hoy->count(),
                'clases_completadas' => $clases_hoy->where('tiene_asistencia_hoy', true)->count(),
                'asistencias_pendientes' => $clases_hoy->where('tiene_asistencia_hoy', false)->count(),
                'total_estudiantes' => \App\Models\Matricula::whereHas('curso', function($query) use ($docente) {
                    $query->whereHas('cursoAsignaturas', function($q) use ($docente) {
                        $q->where('profesor_id', $docente->profesor_id);
                    });
                })->where('estado', 'Matriculado')->distinct('estudiante_id')->count(),
                'total_cursos' => \App\Models\CursoAsignatura::where('profesor_id', $docente->profesor_id)->distinct('curso_id')->count(),
                'total_asignaturas' => \App\Models\CursoAsignatura::where('profesor_id', $docente->profesor_id)->count(),
            ];

            return view('asistencia.docente-dashboard', compact('estadisticas', 'clases_hoy'));

        } catch (\Exception $e) {
            \Log::error('Error en docenteDashboard: ' . $e->getMessage());
            return redirect()->route('home')->with('error', 'Error al cargar el dashboard: ' . $e->getMessage());
        }
    }

    /**
     * Vista para tomar asistencia
     */
    public function docenteTomarAsistencia(Request $request)
    {
        try {
            if (!Auth::user()->hasRole('Docente') && !Auth::user()->hasRole('Administrador')) {
                abort(403, 'No tienes permisos.');
            }

            $docente = Auth::user()->persona->docente;

            // Check if a specific session is requested
            if ($request->has('sesion')) {
                \Log::info('Sesión solicitada:', ['sesion_id' => $request->sesion]);

                // Mode: Specific session attendance taking
                $sesion = \App\Models\SesionClase::with(['cursoAsignatura.curso.matriculas.estudiante.persona', 'aula'])
                    ->whereHas('cursoAsignatura', function($query) use ($docente) {
                        $query->where('profesor_id', $docente->profesor_id);
                    })
                    ->find($request->sesion);

                if (!$sesion) {
                    \Log::error('Sesión no encontrada o no pertenece al docente', [
                        'sesion_id' => $request->sesion,
                        'docente_id' => $docente->profesor_id
                    ]);
                    // Return view with error instead of redirecting
                    return view('asistencia.docente-tomar-asistencia', [
                        'error' => 'Sesión no encontrada o no tienes permisos para acceder a ella.',
                        'clases_hoy' => collect(),
                        'modo' => null
                    ])->with('modo', 'error');
                }

                \Log::info('Sesión encontrada - detalles completos:', [
                    'sesion_object' => $sesion->toArray(),
                    'sesion_id_value' => $sesion->sesion_id,
                    'id_value' => $sesion->id,
                    'primary_key' => $sesion->getKey(),
                    'attributes' => $sesion->getAttributes(),
                    'curso_asignatura_id' => $sesion->cursoAsignatura->curso_asignatura_id ?? 'N/A'
                ]);

                // Get students for this session
                $estudiantes = $sesion->cursoAsignatura->curso->matriculas()
                    ->with('estudiante.persona')
                    ->where('estado', 'Matriculado')
                    ->get()
                    ->map(function($matricula) use ($sesion) {
                        // Check existing attendance for this session
                        $asistenciaExistente = \App\Models\AsistenciaAsignatura::where('matricula_id', $matricula->matricula_id)
                            ->where('curso_asignatura_id', $sesion->cursoAsignatura->curso_asignatura_id)
                            ->where('fecha', $sesion->fecha)
                            ->first();

                        $matricula->asistencia_actual = $asistenciaExistente ? $asistenciaExistente->tipoAsistencia->codigo : 'P';
                        return $matricula;
                    });

                // Statistics for the session
                $estadisticas_sesion = [
                    'total_estudiantes' => $estudiantes->count(),
                    'asistencias_registradas' => $estudiantes->where('asistencia_actual', '!=', 'P')->count()
                ];

                // Also get sessions for today for consistency with the view
                $clases_hoy = collect(); // Empty collection for session mode

                return view('asistencia.docente-tomar-asistencia', compact('sesion', 'estudiantes', 'estadisticas_sesion', 'clases_hoy'))
                    ->with('modo', 'sesion');

            } else {
                // Mode: List of sessions for the day
                // Use requested date or today
                $fecha = $request->get('fecha', today());

                // Get sessions for the specified date
                $clases_hoy = \App\Models\SesionClase::with(['cursoAsignatura.curso.grado', 'cursoAsignatura.curso.seccion', 'cursoAsignatura.asignatura', 'aula'])
                    ->whereHas('cursoAsignatura', function($query) use ($docente) {
                        $query->where('profesor_id', $docente->profesor_id);
                    })
                    ->whereDate('fecha', $fecha)
                    ->orderBy('hora_inicio')
                    ->get()
                    ->map(function($sesion) {
                        $sesion->tiene_asistencia_hoy = \App\Models\AsistenciaAsignatura::whereHas('matricula', function($q) use ($sesion) {
                            $q->whereHas('curso', function($cq) use ($sesion) {
                                $cq->where('curso_id', $sesion->cursoAsignatura->curso_id);
                            });
                        })
                        ->whereDate('fecha', $sesion->fecha)
                        ->exists();
                        return $sesion;
                    });

                // Date information for display
                $fecha_seleccionada = \Carbon\Carbon::parse($fecha);

                return view('asistencia.docente-tomar-asistencia', compact('clases_hoy', 'fecha_seleccionada'));
            }

        } catch (\Exception $e) {
            \Log::error('Error en docenteTomarAsistencia: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cargar la vista: ' . $e->getMessage());
        }
    }

    /**
     * Vista para ver asistencias registradas (lista general con filtros)
     */
    public function docenteVerAsistencias()
    {
        try {
            if (!Auth::user()->hasRole('Docente') && !Auth::user()->hasRole('Administrador')) {
                abort(403, 'No tienes permisos.');
            }

            $docente = Auth::user()->persona->docente;

            // Obtener sesiones con asistencia registrada (lista general)
            $sesiones = \App\Models\SesionClase::with(['cursoAsignatura.curso.grado', 'cursoAsignatura.curso.seccion', 'cursoAsignatura.asignatura'])
                ->whereHas('cursoAsignatura', function($query) use ($docente) {
                    $query->where('profesor_id', $docente->profesor_id);
                })
                ->where('estado', 'Completada')
                ->orderBy('fecha', 'desc')
                ->paginate(15);

            return view('asistencia.docente-lista-asistencias', compact('sesiones'));

        } catch (\Exception $e) {
            \Log::error('Error en docenteVerAsistencias: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cargar las asistencias: ' . $e->getMessage());
        }
    }

    /**
     * Vista de reportes para docentes
     */
    public function docenteReportes()
    {
        try {
            if (!Auth::user()->hasRole('Docente') && !Auth::user()->hasRole('Administrador')) {
                abort(403, 'No tienes permisos.');
            }

            $docente = Auth::user()->persona->docente;

            // Estadísticas básicas para reportes
            $estadisticas = [
                'total_sesiones' => \App\Models\SesionClase::whereHas('cursoAsignatura', function($query) use ($docente) {
                    $query->where('profesor_id', $docente->profesor_id);
                })->count(),
                'sesiones_completadas' => \App\Models\SesionClase::whereHas('cursoAsignatura', function($query) use ($docente) {
                    $query->where('profesor_id', $docente->profesor_id);
                })->where('estado', 'Completada')->count(),
                'total_asistencias' => \App\Models\AsistenciaAsignatura::whereHas('cursoAsignatura', function($query) use ($docente) {
                    $query->where('profesor_id', $docente->profesor_id);
                })->count(),
            ];

            // Obtener cursos del docente para el filtro
            $cursos_docente = \App\Models\CursoAsignatura::with(['curso.grado', 'curso.seccion'])
                ->where('profesor_id', $docente->profesor_id)
                ->get()
                ->map(function($cursoAsignatura) {
                    return $cursoAsignatura->curso;
                })
                ->unique('curso_id')
                ->sortBy(['grado.nombre', 'seccion.nombre']);

            // Obtener los últimos 5 reportes generados por el docente actual
            $reportes_recientes = ReporteGenerado::where('usuario_id', Auth::id())
                ->orderBy('fecha_generacion', 'desc')
                ->limit(5)
                ->get();

            return view('asistencia.docente-reportes', compact('estadisticas', 'cursos_docente', 'reportes_recientes'));

        } catch (\Exception $e) {
            \Log::error('Error en docenteReportes: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cargar los reportes: ' . $e->getMessage());
        }
    }

    /**
     * Vista de estadísticas para docentes
     */
    public function docenteEstadisticas()
    {
        try {
            if (!Auth::user()->hasRole('Docente') && !Auth::user()->hasRole('Administrador')) {
                abort(403, 'No tienes permisos.');
            }

            $docente = Auth::user()->persona->docente;

            // Estadísticas detalladas
            $asistenciasDocente = \App\Models\AsistenciaAsignatura::whereHas('cursoAsignatura', function($query) use ($docente) {
                $query->where('profesor_id', $docente->profesor_id);
            })->with('tipoAsistencia')->get();

            $totalAsistencias = $asistenciasDocente->count();
            $totalPresentes = $asistenciasDocente->where('tipoAsistencia.computa_falta', 0)->count();
            $totalAusentes = $asistenciasDocente->where('tipoAsistencia.computa_falta', 1)->count();

            $porcentajeAsistencia = $totalAsistencias > 0 ? round(($totalPresentes / $totalAsistencias) * 100, 1) : 0;

            // Obtener estadísticas por curso
            $estadisticasPorCurso = \App\Models\CursoAsignatura::where('profesor_id', $docente->profesor_id)
                ->with(['curso.grado', 'curso.seccion', 'asignatura'])
                ->get()
                ->map(function($cursoAsignatura) {
                    $asistenciasCurso = \App\Models\AsistenciaAsignatura::where('curso_asignatura_id', $cursoAsignatura->curso_asignatura_id)
                        ->with('tipoAsistencia')
                        ->get();

                    $totalAsistenciasCurso = $asistenciasCurso->count();
                    $totalPresentesCurso = $asistenciasCurso->where('tipoAsistencia.computa_falta', 0)->count();

                    $porcentajeCurso = $totalAsistenciasCurso > 0 ? round(($totalPresentesCurso / $totalAsistenciasCurso) * 100, 1) : 0;

                    return [
                        'curso' => $cursoAsignatura->curso->grado->descripcion . ' ' . $cursoAsignatura->curso->seccion->nombre,
                        'asignatura' => $cursoAsignatura->asignatura->nombre,
                        'total_asistencias' => $totalAsistenciasCurso,
                        'presentes' => $totalPresentesCurso,
                        'porcentaje' => $porcentajeCurso
                    ];
                })
                ->sortByDesc('porcentaje')
                ->values()
                ->all();

            $estadisticas = [
                'asistencias_por_mes' => [], // Implementar lógica para estadísticas mensuales
                'asistencia_promedio' => $porcentajeAsistencia,
                'porcentaje_asistencia' => $porcentajeAsistencia, // Agregada la clave que busca la vista
                'estadisticas_por_curso' => $estadisticasPorCurso, // Agregada la clave que busca la vista
                'estudiantes_riesgo' => 0,
                'sesiones_mes_actual' => \App\Models\SesionClase::whereHas('cursoAsignatura', function($query) use ($docente) {
                    $query->where('profesor_id', $docente->profesor_id);
                })->whereMonth('fecha', now()->month)->count(),
                'total_asistencias' => $totalAsistencias,
                'total_presentes' => $totalPresentes,
                'total_ausentes' => $totalAusentes,
                'mes_actual' => now()->locale('es')->monthName,
                'anio_actual' => now()->year,
            ];

            return view('asistencia.docente-estadisticas', compact('estadisticas'));

        } catch (\Exception $e) {
            \Log::error('Error en docenteEstadisticas: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cargar las estadísticas: ' . $e->getMessage());
        }
    }

    /**
     * Ver asistencia específica de una sesión
     */
    public function docenteVerAsistencia($sesionClase)
    {
        try {
            if (!Auth::user()->hasRole('Docente') && !Auth::user()->hasRole('Administrador')) {
                abort(403, 'No tienes permisos.');
            }

            $docente = Auth::user()->persona->docente;

            $sesion = \App\Models\SesionClase::with(['cursoAsignatura.curso.matriculas.estudiante.persona', 'aula'])
                ->whereHas('cursoAsignatura', function($query) use ($docente) {
                    $query->where('profesor_id', $docente->profesor_id);
                })
                ->findOrFail($sesionClase);

            // Obtener asistencias de esta sesión (como colección simple, no paginada)
            $asistencias = \App\Models\AsistenciaAsignatura::with(['matricula.estudiante.persona', 'tipoAsistencia'])
                ->where('curso_asignatura_id', $sesion->cursoAsignatura->curso_asignatura_id)
                ->where('fecha', $sesion->fecha)
                ->get()
                ->keyBy('matricula.matricula_id');

            // Obtener cursos del docente para el filtro
            $cursos_docente = \App\Models\CursoAsignatura::with(['curso.grado', 'curso.seccion'])
                ->where('profesor_id', $docente->profesor_id)
                ->get()
                ->map(function($cursoAsignatura) {
                    return $cursoAsignatura->curso;
                })
                ->unique('curso_id')
                ->sortBy(['grado.nombre', 'seccion.nombre']);

            // Obtener asignaturas del docente para el filtro
            $asignaturas_docente = \App\Models\CursoAsignatura::with(['asignatura'])
                ->where('profesor_id', $docente->profesor_id)
                ->get()
                ->map(function($cursoAsignatura) {
                    return $cursoAsignatura->asignatura;
                })
                ->unique('asignatura_id')
                ->sortBy('nombre');

            return view('asistencia.docente-ver-asistencias', compact('sesion', 'asistencias', 'cursos_docente', 'asignaturas_docente'));

        } catch (\Exception $e) {
            \Log::error('Error en docenteVerAsistencia: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cargar la asistencia: ' . $e->getMessage());
        }
    }

    /**
     * Editar asistencia de una sesión específica
     */
    public function docenteEditarAsistencia($sesionClase)
    {
        try {
            if (!Auth::user()->hasRole('Docente') && !Auth::user()->hasRole('Administrador')) {
                abort(403, 'No tienes permisos.');
            }

            $docente = Auth::user()->persona->docente;

            $sesion = \App\Models\SesionClase::with(['cursoAsignatura.curso.matriculas.estudiante.persona', 'aula'])
                ->whereHas('cursoAsignatura', function($query) use ($docente) {
                    $query->where('profesor_id', $docente->profesor_id);
                })
                ->findOrFail($sesionClase);

            // Verificar si se puede editar (solo asistencias de los últimos 7 días o administradores)
            $diasDesdeSesion = now()->diffInDays($sesion->fecha);
            $puedeEditar = Auth::user()->hasRole('Administrador') || $diasDesdeSesion <= 7;

            // Obtener estudiantes matriculados en este curso específico con sus asistencias actuales
            $estudiantes = $sesion->cursoAsignatura->curso->matriculas()
                ->with('estudiante.persona')
                ->where('estado', 'Matriculado')
                ->get()
                ->map(function($matricula) use ($sesion) {
                    // Obtener asistencia existente para esta sesión
                    $asistenciaExistente = \App\Models\AsistenciaAsignatura::where('matricula_id', $matricula->matricula_id)
                        ->where('curso_asignatura_id', $sesion->cursoAsignatura->curso_asignatura_id)
                        ->where('fecha', $sesion->fecha)
                        ->with('tipoAsistencia')
                        ->first();

                    $matricula->asistencia_actual = $asistenciaExistente ? $asistenciaExistente->tipoAsistencia->codigo : null;
                    return $matricula;
                });

            // Estadísticas de la sesión
            $estadisticas_sesion = [
                'total_estudiantes' => $estudiantes->count(),
                'asistencias_registradas' => $estudiantes->where('asistencia_actual', '!=', null)->count(),
                'dias_desde_sesion' => $diasDesdeSesion,
                'puede_editar' => $puedeEditar
            ];

            return view('asistencia.docente-editar-asistencia', compact('sesion', 'estudiantes', 'estadisticas_sesion'));

        } catch (\Exception $e) {
            \Log::error('Error en docenteEditarAsistencia: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cargar la edición de asistencia: ' . $e->getMessage());
        }
    }

    /**
     * API para obtener estudiantes de una sesión
     */
    public function docenteObtenerEstudiantes(Request $request)
    {
        try {
            if (!Auth::user()->hasRole('Docente') && !Auth::user()->hasRole('Administrador')) {
                return response()->json(['error' => 'No autorizado'], 403);
            }

            $docente = Auth::user()->persona->docente;

            $request->validate([
                'sesion_clase_id' => 'required|exists:sesiones_clase,id'
            ]);

            $sesion = \App\Models\SesionClase::with('cursoAsignatura.curso.matriculas.estudiante.persona')
                ->whereHas('cursoAsignatura', function($query) use ($docente) {
                    $query->where('profesor_id', $docente->profesor_id);
                })
                ->findOrFail($request->sesion_clase_id);

            $estudiantes = $sesion->cursoAsignatura->curso->matriculas()
                ->with('estudiante.persona')
                ->where('estado', 'Matriculado')
                ->get()
                ->map(function($matricula) {
                    return [
                        'matricula_id' => $matricula->matricula_id,
                        'estudiante_id' => $matricula->estudiante->estudiante_id,
                        'numero_matricula' => $matricula->numero_matricula,
                        'nombres' => $matricula->estudiante->persona->nombres,
                        'apellidos' => $matricula->estudiante->persona->apellidos,
                        'dni' => $matricula->estudiante->persona->dni,
                    ];
                });

            return response()->json(['estudiantes' => $estudiantes]);

        } catch (\Exception $e) {
            \Log::error('Error en docenteObtenerEstudiantes: ' . $e->getMessage());
            return response()->json(['error' => 'Error al obtener estudiantes'], 500);
        }
    }

    /**
     * Guardar asistencia tomada por docente
     */
    public function docenteGuardarAsistencia(Request $request)
    {
        // Asegurar que siempre se devuelva JSON
        try {
            // Verificar datos básicos antes de proceder
            if (!$request->has('sesion_clase_id') || !$request->has('asistencias')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Datos incompletos: faltan sesion_clase_id o asistencias'
                ], 400);
            }
            // Verificar autenticación
            if (!Auth::check()) {
                \Log::error('Usuario no autenticado');
                return response()->json(['success' => false, 'message' => 'Usuario no autenticado'], 401);
            }

            // Verificar permisos
            if (!Auth::user()->hasRole('Docente') && !Auth::user()->hasRole('Administrador')) {
                \Log::error('Usuario sin permisos');
                return response()->json(['success' => false, 'message' => 'No tienes permisos para esta acción'], 403);
            }

            // Verificar que el usuario tenga relación con docente
            if (!Auth::user()->persona || !Auth::user()->persona->docente) {
                \Log::error('Usuario sin relación docente');
                return response()->json(['success' => false, 'message' => 'Tu cuenta de docente no está configurada correctamente'], 403);
            }

            $docente = Auth::user()->persona->docente;
            \Log::info('Docente autenticado:', ['docente_id' => $docente->profesor_id]);

            // Validar datos con manejo de errores detallado
            try {
                $validatedData = $request->validate([
                    'sesion_clase_id' => 'required|exists:sesiones_clases,sesion_id',
                    'asistencias' => 'required|array|min:1',
                    'asistencias.*.matricula_id' => 'required|exists:matriculas,matricula_id',
                    'asistencias.*.tipo_asistencia' => 'required|in:P,A,F,T,J',
                ]);
                \Log::info('Datos validados correctamente');
            } catch (\Illuminate\Validation\ValidationException $ve) {
                \Log::error('VALIDATION ERROR DETALLADO:', [
                    'errores' => $ve->errors(),
                    'datos_recibidos' => $request->all(),
                    'reglas_de_validacion' => [
                        'sesion_clase_id' => 'required|exists:sesiones_clase,id',
                        'asistencias' => 'required|array|min:1',
                        'asistencias.*.matricula_id' => 'required|exists:matriculas,matricula_id',
                        'asistencias.*.tipo_asistencia' => 'required|in:P,A,T,J',
                    ]
                ]);

                // Verificar específicamente qué está fallando
                if (!$request->has('sesion_clase_id') || empty($request->input('sesion_clase_id'))) {
                    \Log::error('sesion_clase_id faltante o vacío');
                }
                if (!$request->has('asistencias') || !is_array($request->input('asistencias'))) {
                    \Log::error('asistencias faltante o no es array', ['tipo' => gettype($request->input('asistencias'))]);
                }
                if (is_array($request->input('asistencias')) && count($request->input('asistencias')) === 0) {
                    \Log::error('asistencias es array vacío');
                }

                return response()->json([
                    'success' => false,
                    'message' => 'Datos inválidos.',
                    'errors' => $ve->errors(),
                    'debug' => [
                        'sesion_clase_id_present' => $request->has('sesion_clase_id'),
                        'sesion_clase_id_value' => $request->input('sesion_clase_id'),
                        'asistencias_present' => $request->has('asistencias'),
                        'asistencias_is_array' => is_array($request->input('asistencias')),
                        'asistencias_count' => is_array($request->input('asistencias')) ? count($request->input('asistencias')) : 'N/A'
                    ]
                ], 422);
            }

            // Verificar que la sesión pertenece al docente
            $sesion = \App\Models\SesionClase::with('cursoAsignatura')
                ->whereHas('cursoAsignatura', function($query) use ($docente) {
                    $query->where('profesor_id', $docente->profesor_id);
                })
                ->findOrFail($request->sesion_clase_id);
            \Log::info('Sesión encontrada:', ['sesion_id' => $sesion->id, 'curso_asignatura_id' => $sesion->cursoAsignatura->curso_asignatura_id]);

            DB::beginTransaction();
            \Log::info('Transacción iniciada');

            $asistenciasGuardadas = 0;

            // Verificar si es edición
            $esEdicion = $request->has('es_edicion') && $request->es_edicion;

            if ($esEdicion) {
                \Log::info('Modo edición activado');

                // En modo edición, procesar solo los estudiantes matriculados en este curso específico
                $estudiantesSesion = $sesion->cursoAsignatura->curso->matriculas()
                    ->where('estado', 'Matriculado')
                    ->get();

                \Log::info('Estudiantes en la sesión:', ['count' => $estudiantesSesion->count()]);

                foreach ($estudiantesSesion as $matricula) {
                    // Buscar si este estudiante tiene asistencia enviada en el formulario
                    $asistenciaData = collect($request->asistencias)->firstWhere('matricula_id', (string)$matricula->matricula_id);

                    if ($asistenciaData && isset($asistenciaData['tipo_asistencia']) && $asistenciaData['tipo_asistencia']) {
                        \Log::info("Procesando asistencia para estudiante {$matricula->matricula_id}:", $asistenciaData);

                        // Convertir código de asistencia a ID
                        $tipoAsistencia = TipoAsistencia::where('codigo', $asistenciaData['tipo_asistencia'])->first();
                        if (!$tipoAsistencia) {
                            \Log::error('Tipo de asistencia no encontrado:', ['codigo' => $asistenciaData['tipo_asistencia']]);
                            throw new \Exception('Tipo de asistencia no válido: ' . $asistenciaData['tipo_asistencia']);
                        }

                        // Actualizar o crear asistencia
                        $asistencia = \App\Models\AsistenciaAsignatura::updateOrCreate(
                            [
                                'matricula_id' => $matricula->matricula_id,
                                'curso_asignatura_id' => $sesion->cursoAsignatura->curso_asignatura_id,
                                'fecha' => $sesion->fecha,
                            ],
                            [
                                'tipo_asistencia_id' => $tipoAsistencia->tipo_asistencia_id,
                                'hora_registro' => now()->format('H:i:s'),
                                'estado' => 'Registrada',
                                'usuario_registro' => Auth::id(),
                                'justificacion' => isset($asistenciaData['justificacion']) ? $asistenciaData['justificacion'] : null,
                            ]
                        );
                        \Log::info('Asistencia guardada/actualizada:', ['asistencia_id' => $asistencia->asistencia_id]);
                        $asistenciasGuardadas++;
                    } else {
                        \Log::warning("Estudiante {$matricula->matricula_id} no tiene asistencia en el formulario o tipo_asistencia vacío");
                    }
                }
            } else {
                \Log::info('Modo creación normal');

                // Modo normal: procesar solo las asistencias enviadas
                foreach ($request->asistencias as $index => $asistenciaData) {
                    \Log::info("Procesando asistencia {$index}:", $asistenciaData);

                    // Convertir código de asistencia a ID
                    $tipoAsistencia = TipoAsistencia::where('codigo', $asistenciaData['tipo_asistencia'])->first();
                    if (!$tipoAsistencia) {
                        \Log::error('Tipo de asistencia no encontrado:', ['codigo' => $asistenciaData['tipo_asistencia']]);
                        throw new \Exception('Tipo de asistencia no válido: ' . $asistenciaData['tipo_asistencia']);
                    }
                    \Log::info('Tipo de asistencia convertido:', [
                        'codigo' => $asistenciaData['tipo_asistencia'],
                        'id' => $tipoAsistencia->tipo_asistencia_id
                    ]);

                    // Verificar que la matrícula pertenece al curso de la sesión
                    $matricula = \App\Models\Matricula::where('matricula_id', $asistenciaData['matricula_id'])
                        ->where('idGrado', $sesion->cursoAsignatura->curso->grado_id)
                        ->where('idSeccion', $sesion->cursoAsignatura->curso->seccion_id)
                        ->first();

                    if (!$matricula) {
                        \Log::error('Matrícula no pertenece al curso de la sesión', [
                            'matricula_id' => $asistenciaData['matricula_id'],
                            'grado_id' => $sesion->cursoAsignatura->curso->grado_id,
                            'seccion_id' => $sesion->cursoAsignatura->curso->seccion_id
                        ]);
                        continue; // Saltar esta asistencia
                    }

                    $asistencia = \App\Models\AsistenciaAsignatura::updateOrCreate(
                        [
                            'matricula_id' => $asistenciaData['matricula_id'],
                            'curso_asignatura_id' => $sesion->cursoAsignatura->curso_asignatura_id,
                            'fecha' => $sesion->fecha,
                        ],
                        [
                            'tipo_asistencia_id' => $tipoAsistencia->tipo_asistencia_id,
                            'hora_registro' => now()->format('H:i:s'),
                            'estado' => 'Registrada',
                            'usuario_registro' => Auth::id(),
                            'justificacion' => isset($asistenciaData['justificacion']) ? $asistenciaData['justificacion'] : null,
                        ]
                    );
                    \Log::info('Asistencia guardada:', ['asistencia_id' => $asistencia->asistencia_id]);
                    $asistenciasGuardadas++;
                }
            }

            // Marcar sesión como completada
            $sesion->update(['estado' => 'Completada']);
            \Log::info('Sesión marcada como completada');

            DB::commit();
            \Log::info('Transacción confirmada', ['asistencias_guardadas' => $asistenciasGuardadas]);

            return response()->json([
                'success' => true,
                'message' => "Asistencia registrada correctamente. {$asistenciasGuardadas} registros guardados."
            ]);

        } catch (\Illuminate\Validation\ValidationException $ve) {
            \Log::error('Error de validación:', $ve->errors());
            return response()->json([
                'success' => false,
                'message' => 'Datos inválidos.',
                'errors' => $ve->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error en docenteGuardarAsistencia:', [
                'mensaje' => $e->getMessage(),
                'linea' => $e->getLine(),
                'archivo' => $e->getFile(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exportar PDF de asistencia de una sesión
     */
    public function docenteExportarPDF($sesionClase)
    {
        try {
            if (!Auth::user()->hasRole('Docente') && !Auth::user()->hasRole('Administrador')) {
                abort(403, 'No tienes permisos.');
            }

            $docente = Auth::user()->persona->docente;

            $sesion = \App\Models\SesionClase::with(['cursoAsignatura.curso.matriculas.estudiante.persona', 'aula'])
                ->whereHas('cursoAsignatura', function($query) use ($docente) {
                    $query->where('profesor_id', $docente->profesor_id);
                })
                ->findOrFail($sesionClase);

            // Obtener asistencias indexadas por matricula_id para fácil acceso
            $asistencias = \App\Models\AsistenciaAsignatura::with(['matricula.estudiante.persona', 'tipoAsistencia'])
                ->where('curso_asignatura_id', $sesion->cursoAsignatura->curso_asignatura_id)
                ->where('fecha', $sesion->fecha)
                ->get()
                ->keyBy('matricula_id');

            // Generar PDF simple
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('asistencia.pdf-sesion', compact('sesion', 'asistencias'));

            $filename = 'asistencia_' . $sesion->cursoAsignatura->asignatura->codigo . '_' . $sesion->fecha->format('Y-m-d') . '.pdf';

            return $pdf->download($filename);

        } catch (\Exception $e) {
            \Log::error('Error en docenteExportarPDF: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al generar el PDF: ' . $e->getMessage());
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

    /**
     * Dashboard del representante para asistencia
     */
    public function representanteDashboard()
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

            // Obtener estudiantes del representante con estadísticas
            $estudiantes = $representante->estudiantes()
                ->with(['persona', 'matricula.grado', 'matricula.seccion'])
                ->get();

            // Estadísticas generales del dashboard
            $estadisticas = [
                'total_estudiantes' => $estudiantes->count(),
                'estudiantes_activos' => $estudiantes->where('matricula.estado', 'Matriculado')->count(),
                'promedio_asistencia_mes' => $estudiantes->avg(function($estudiante) {
                    if (!$estudiante->matricula) return 0;

                    $mesActual = now()->month;
                    $anioActual = now()->year;

                    $asistenciasMes = AsistenciaAsignatura::where('matricula_id', $estudiante->matricula->matricula_id)
                        ->whereMonth('fecha', $mesActual)
                        ->whereYear('fecha', $anioActual)
                        ->get();

                    $totalAsistencias = $asistenciasMes->count();
                    $inasistencias = $asistenciasMes->where('tipo_asistencia_id', TipoAsistencia::where('codigo', 'A')->first()->id ?? 2)->count();

                    return $totalAsistencias > 0 ? (($totalAsistencias - $inasistencias) / $totalAsistencias) * 100 : 0;
                }),
                'total_inasistencias_mes' => $estudiantes->sum(function($e) {
                    if (!$e->matricula) return 0;
                    return AsistenciaAsignatura::where('matricula_id', $e->matricula->matricula_id)
                        ->whereMonth('fecha', now()->month)
                        ->whereYear('fecha', now()->year)
                        ->where('tipo_asistencia_id', TipoAsistencia::where('codigo', 'A')->first()->id ?? 2)
                        ->count();
                }),
                'justificaciones_pendientes' => JustificacionAsistencia::whereIn('matricula_id', $estudiantes->pluck('matricula.matricula_id'))
                    ->where('estado', 'pendiente')
                    ->count(),
                'justificaciones_aprobadas_mes' => JustificacionAsistencia::whereIn('matricula_id', $estudiantes->pluck('matricula.matricula_id'))
                    ->where('estado', 'aprobado')
                    ->whereMonth('fecha', now()->month)
                    ->whereYear('fecha', now()->year)
                    ->count(),
                'mes_actual' => now()->locale('es')->monthName,
                'anio_actual' => now()->year,
            ];

            // Estudiantes con problemas de asistencia (menos del 80%)
            $estudiantesRiesgo = $estudiantes->filter(function($estudiante) use ($estadisticas) {
                if (!$estudiante->matricula) return false;

                $mesActual = now()->month;
                $anioActual = now()->year;

                $asistenciasMes = AsistenciaAsignatura::where('matricula_id', $estudiante->matricula->matricula_id)
                    ->whereMonth('fecha', $mesActual)
                    ->whereYear('fecha', $anioActual)
                    ->get();

                $totalAsistencias = $asistenciasMes->count();
                $inasistencias = $asistenciasMes->where('tipo_asistencia_id', TipoAsistencia::where('codigo', 'A')->first()->id ?? 2)->count();

                $porcentaje = $totalAsistencias > 0 ? (($totalAsistencias - $inasistencias) / $totalAsistencias) * 100 : 100;
                return $porcentaje < 80;
            });

            return view('asistencia.representante-dashboard', compact('estadisticas', 'estudiantesRiesgo', 'representante'));

        } catch (\Exception $e) {
            \Log::error('Error en representanteDashboard: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());

            // Mostrar página de error con información de debug
            return view('asistencia.representante-dashboard', [
                'error' => 'Error al cargar el dashboard: ' . $e->getMessage(),
                'estadisticas' => [
                    'total_estudiantes' => 0,
                    'estudiantes_activos' => 0,
                    'promedio_asistencia_mes' => 0,
                    'total_inasistencias_mes' => 0,
                    'justificaciones_pendientes' => 0,
                    'justificaciones_aprobadas_mes' => 0,
                    'mes_actual' => now()->locale('es')->monthName,
                    'anio_actual' => now()->year,
                ],
                'estudiantesRiesgo' => collect(),
                'representante' => null
            ]);
        }
    }

    /**
     * Detalle de asistencia de un estudiante específico para representantes
     */
    public function representanteDetalle(Request $request, $estudiante_id)
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

            // Verificar que el estudiante pertenezca al representante
            $estudiante = $representante->estudiantes()
                ->with(['persona', 'matricula.grado', 'matricula.seccion', 'matricula.curso.anioLectivo'])
                ->find($estudiante_id);

            if (!$estudiante) {
                \Log::warning('Estudiante no encontrado o no pertenece al representante', [
                    'representante_id' => $representante->representante_id,
                    'estudiante_id' => $estudiante_id,
                    'estudiantes_del_representante' => $representante->estudiantes()->pluck('estudiante_id')->toArray()
                ]);

                // Verificar si el estudiante existe en general
                $estudianteGeneral = \App\Models\InfEstudiante::with('persona')->find($estudiante_id);
                if ($estudianteGeneral) {
                    \Log::warning('Estudiante existe pero no pertenece a este representante', [
                        'estudiante_id' => $estudiante_id,
                        'estudiante_persona' => $estudianteGeneral->persona ? $estudianteGeneral->persona->nombres . ' ' . $estudianteGeneral->persona->apellidos : 'Sin persona',
                        'representante_id' => $representante->representante_id
                    ]);
                    abort(404, 'El estudiante existe pero no tienes permisos para ver su información.');
                } else {
                    \Log::warning('Estudiante no existe en la base de datos', ['estudiante_id' => $estudiante_id]);
                    abort(404, 'Estudiante no encontrado.');
                }
            }

            // Verificar que el estudiante tenga relación con persona
            if (!$estudiante->persona) {
                \Log::warning('Estudiante encontrado pero no tiene relación con persona', [
                    'estudiante_id' => $estudiante->estudiante_id,
                    'representante_id' => $representante->representante_id
                ]);
                abort(500, 'El estudiante no tiene información de persona asociada. Contacta al administrador.');
            }

            // Verificar que el estudiante tenga matrícula activa
            if (!$estudiante->matricula) {
                \Log::warning('Estudiante no tiene matrícula activa', [
                    'estudiante_id' => $estudiante->estudiante_id,
                    'representante_id' => $representante->representante_id
                ]);
                abort(404, 'El estudiante no tiene una matrícula activa.');
            }

            // Parámetros de filtro
            $mes = $request->get('mes', date('n'));
            $anio = $request->get('anio', date('Y'));

            // Obtener asistencias del estudiante para el período seleccionado
            $asistencias = AsistenciaAsignatura::with([
                'tipoAsistencia',
                'sesionClase.cursoAsignatura.asignatura',
                'sesionClase.cursoAsignatura.docente'
            ])
            ->where('matricula_id', $estudiante->matricula->matricula_id ?? 0)
            ->whereMonth('fecha', $mes)
            ->whereYear('fecha', $anio)
            ->orderBy('fecha', 'desc')
            ->orderBy('hora_registro', 'desc')
            ->paginate(20);

            // Calcular estadísticas del período
            $queryEstadisticas = AsistenciaAsignatura::where('matricula_id', $estudiante->matricula->matricula_id ?? 0)
                ->whereMonth('fecha', $mes)
                ->whereYear('fecha', $anio);

            $estadisticas = [
                'presentes' => (clone $queryEstadisticas)->whereHas('tipoAsistencia', function($q) {
                    $q->where('computa_falta', 0);
                })->count(),
                'ausentes' => (clone $queryEstadisticas)->whereHas('tipoAsistencia', function($q) {
                    $q->where('computa_falta', 1);
                })->count(),
                'tardes' => (clone $queryEstadisticas)->whereHas('tipoAsistencia', function($q) {
                    $q->where('codigo', 'T');
                })->count(),
                'justificados' => (clone $queryEstadisticas)->whereHas('tipoAsistencia', function($q) {
                    $q->where('codigo', 'J');
                })->count(),
                'total' => $queryEstadisticas->count(),
                'mes' => $mes,
                'anio' => $anio
            ];

            // Obtener justificaciones del estudiante
            $justificaciones = JustificacionAsistencia::with(['matricula.estudiante.persona'])
                ->where('matricula_id', $estudiante->matricula->matricula_id ?? 0)
                ->orderBy('created_at', 'desc')
                ->get();

            return view('asistencia.representante-detalle', compact('estudiante', 'asistencias', 'estadisticas', 'justificaciones'));

        } catch (\Exception $e) {
            \Log::error('Error en representanteDetalle: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());

            // Mostrar página de error con información de debug
            return view('asistencia.representante-detalle', [
                'error' => 'Error al cargar los detalles del estudiante: ' . $e->getMessage(),
                'estudiante' => null,
                'asistencias' => collect(),
                'estadisticas' => [
                    'presentes' => 0,
                    'ausentes' => 0,
                    'tardes' => 0,
                    'justificados' => 0,
                    'total' => 0,
                    'mes' => date('n'),
                    'anio' => date('Y')
                ],
                'justificaciones' => collect()
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

                // Calcular total de registros para actualizar el reporte
                $queryCount = DB::table('asistenciasasignatura as aa')
                    ->join('matriculas as m', 'aa.matricula_id', '=', 'm.matricula_id')
                    ->join('estudiantes as e', 'm.estudiante_id', '=', 'e.estudiante_id')
                    ->join('personas as p', 'e.persona_id', '=', 'p.id_persona')
                    ->join('grados as g', 'm.idGrado', '=', 'g.grado_id')
                    ->join('secciones as s', 'm.idSeccion', '=', 's.seccion_id')
                    ->join('cursoasignaturas as ca', 'aa.curso_asignatura_id', '=', 'ca.curso_asignatura_id')
                    ->join('asignaturas as a', 'ca.asignatura_id', '=', 'a.asignatura_id')
                    ->join('tiposasistencia as ta', 'aa.tipo_asistencia_id', '=', 'ta.tipo_asistencia_id');

                // Aplicar filtros para el conteo
                if (isset($request->fecha_inicio) && isset($request->fecha_fin)) {
                    $queryCount->whereBetween('aa.fecha', [$request->fecha_inicio, $request->fecha_fin]);
                }

                if (isset($request->tipo_asistencia)) {
                    $queryCount->where('ta.codigo', $request->tipo_asistencia);
                }

                if (isset($request->estudiante_id)) {
                    $queryCount->where('e.estudiante_id', $request->estudiante_id);
                }

                if (isset($request->grado_id)) {
                    $queryCount->where('m.idGrado', $request->grado_id);
                }

                if (isset($request->docente_id)) {
                    $queryCount->where('ca.profesor_id', $request->docente_id);
                }

                if (isset($request->asignatura_id)) {
                    $queryCount->where('ca.asignatura_id', $request->asignatura_id);
                }

                $totalRegistros = $queryCount->count();

                // Actualizar estadísticas del reporte
                $reporte->update([
                    'estado' => 'completado',
                    'registros_totales' => $totalRegistros
                ]);
                \Log::info('Reporte completado exitosamente', [
                    'reporte_id' => $reporte->id,
                    'registros_totales' => $totalRegistros,
                    'all_attributes' => $reporte->toArray()
                ]);

                return response()->json([
                    'success' => true,
                    'reporte_id' => $reporte->id, // Use 'id' instead of 'reporte_id'
                    'tipo_reporte' => $reporte->tipo_reporte,
                    'formato' => $reporte->formato,
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

            $extension = $reporte->formato === 'excel' ? 'xlsx' : 'pdf';
            $fechaInicio = \Carbon\Carbon::parse($reporte->fecha_inicio)->format('Y-m-d');
            $fechaFin = \Carbon\Carbon::parse($reporte->fecha_fin)->format('Y-m-d');
            $nombreDescarga = 'reporte_asistencia_' . $reporte->tipo_reporte . '_' . $fechaInicio . '_a_' . $fechaFin . '.' . $extension;

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
     * API para obtener cantidad de sesiones por día para el docente actual
     */
    public function getSesionesPorDia($fecha)
    {
        try {
            // Verificar autenticación
            if (!Auth::check()) {
                return response()->json(['error' => 'No autenticado'], 401);
            }

            // Verificar permisos
            if (!Auth::user()->hasRole('Docente') && !Auth::user()->hasRole('Administrador')) {
                return response()->json(['error' => 'No autorizado'], 403);
            }

            $docente = Auth::user()->persona->docente;

            // Contar sesiones del docente para la fecha específica
            $cantidadSesiones = \App\Models\SesionClase::whereHas('cursoAsignatura', function($query) use ($docente) {
                $query->where('profesor_id', $docente->profesor_id);
            })
            ->whereDate('fecha', $fecha)
            ->count();

            return response()->json([
                'cantidad' => $cantidadSesiones,
                'fecha' => $fecha
            ]);

        } catch (\Exception $e) {
            \Log::error('Error en getSesionesPorDia: ' . $e->getMessage());
            return response()->json(['error' => 'Error interno del servidor'], 500);
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

            // Calcular estadísticas
            $totalRegistros = $asistencias->count();
            $totalPresentes = $asistencias->where('computa_falta', 0)->count();
            $totalAusentes = $asistencias->where('computa_falta', 1)->count();

            \Log::info('Estadísticas calculadas para Excel', [
                'total' => $totalRegistros,
                'presentes' => $totalPresentes,
                'ausentes' => $totalAusentes
            ]);

            // Crear Excel
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Título del reporte
            $sheet->setCellValue('A1', 'REPORTE DE ASISTENCIA');
            $sheet->mergeCells('A1:H1');
            $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
            $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

            // Información del reporte
            $sheet->setCellValue('A3', 'Tipo de Reporte:');
            $sheet->setCellValue('B3', ucfirst($reporte->tipo_reporte));

            $sheet->setCellValue('A4', 'Período:');
            $fechaInicio = \Carbon\Carbon::parse($reporte->fecha_inicio)->format('d/m/Y');
            $fechaFin = \Carbon\Carbon::parse($reporte->fecha_fin)->format('d/m/Y');
            $sheet->setCellValue('B4', $fechaInicio . ' - ' . $fechaFin);

            $sheet->setCellValue('A5', 'Generado:');
            $sheet->setCellValue('B5', \Carbon\Carbon::parse($reporte->fecha_generacion)->format('d/m/Y H:i:s'));

            $sheet->setCellValue('A6', 'Usuario:');
            $usuario = Auth::user()->persona ? Auth::user()->persona->nombres . ' ' . Auth::user()->persona->apellidos : Auth::user()->username;
            $sheet->setCellValue('B6', $usuario);

            // Filtros aplicados
            $sheet->setCellValue('A8', 'FILTROS APLICADOS:');
            $sheet->getStyle('A8')->getFont()->setBold(true);

            $filtrosTexto = [];
            if (isset($filtros['estudiante_id']) && $filtros['estudiante_id']) {
                $estudiante = DB::table('estudiantes as e')
                    ->join('personas as p', 'e.persona_id', '=', 'p.id_persona')
                    ->where('e.estudiante_id', $filtros['estudiante_id'])
                    ->selectRaw("CONCAT(p.nombres, ' ', p.apellidos) as nombre_completo")
                    ->first();
                $filtrosTexto[] = 'Estudiante: ' . ($estudiante ? $estudiante->nombre_completo : 'ID ' . $filtros['estudiante_id']);
            }
            if (isset($filtros['grado_id']) && $filtros['grado_id']) {
                $grado = DB::table('grados')->where('grado_id', $filtros['grado_id'])->first();
                $filtrosTexto[] = 'Grado: ' . ($grado ? $grado->descripcion : 'ID ' . $filtros['grado_id']);
            }
            if (isset($filtros['docente_id']) && $filtros['docente_id']) {
                $docente = DB::table('profesores as pr')
                    ->join('personas as p', 'pr.persona_id', '=', 'p.id_persona')
                    ->where('pr.profesor_id', $filtros['docente_id'])
                    ->selectRaw("CONCAT(p.nombres, ' ', p.apellidos) as nombre_completo")
                    ->first();
                $filtrosTexto[] = 'Docente: ' . ($docente ? $docente->nombre_completo : 'ID ' . $filtros['docente_id']);
            }
            if (isset($filtros['asignatura_id']) && $filtros['asignatura_id']) {
                $asignatura = DB::table('asignaturas')->where('asignatura_id', $filtros['asignatura_id'])->first();
                $filtrosTexto[] = 'Asignatura: ' . ($asignatura ? $asignatura->nombre : 'ID ' . $filtros['asignatura_id']);
            }
            if (empty($filtrosTexto)) {
                $filtrosTexto[] = 'Sin filtros adicionales';
            }

            // Mostrar filtros en filas separadas para mejor legibilidad
            $rowFiltro = 9;
            foreach ($filtrosTexto as $filtro) {
                $sheet->setCellValue('A' . $rowFiltro, $filtro);
                $rowFiltro++;
            }

            // Estadísticas
            $sheet->setCellValue('A11', 'ESTADÍSTICAS:');
            $sheet->getStyle('A11')->getFont()->setBold(true);

            $sheet->setCellValue('A12', 'Total Registros:');
            $sheet->setCellValue('B12', $totalRegistros);

            $sheet->setCellValue('A13', 'Total Presentes:');
            $sheet->setCellValue('B13', $totalPresentes);

            $sheet->setCellValue('A14', 'Total Ausentes:');
            $sheet->setCellValue('B14', $totalAusentes);

            // Headers de datos (sin columna de hora)
            $sheet->setCellValue('A16', 'Fecha');
            $sheet->setCellValue('B16', 'Estudiante');
            $sheet->setCellValue('C16', 'Grado');
            $sheet->setCellValue('D16', 'Sección');
            $sheet->setCellValue('E16', 'Asignatura');
            $sheet->setCellValue('F16', 'Tipo Asistencia');
            $sheet->setCellValue('G16', 'Estado');

            // Estilo de headers
            $headerStyle = [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '0A8CB3']],
                'alignment' => ['horizontal' => 'center']
            ];
            $sheet->getStyle('A16:G16')->applyFromArray($headerStyle);

            // Data (sin columna de hora)
            $row = 17;
            foreach ($asistencias as $asistencia) {
                $sheet->setCellValue('A' . $row, $asistencia->fecha);
                $sheet->setCellValue('B' . $row, $asistencia->estudiante ?? 'N/A');
                $sheet->setCellValue('C' . $row, $asistencia->grado ?? 'N/A');
                $sheet->setCellValue('D' . $row, $asistencia->seccion ?? 'N/A');
                $sheet->setCellValue('E' . $row, $asistencia->asignatura ?? 'N/A');
                $sheet->setCellValue('F' . $row, $asistencia->tipo_asistencia ?? 'N/A');
                $sheet->setCellValue('G' . $row, ($asistencia->computa_falta == 0) ? 'Presente' : 'Ausente');
                $row++;
            }

            // Auto-size columns
            foreach (range('A', 'G') as $column) {
                $sheet->getColumnDimension($column)->setAutoSize(true);
            }

            // Bordes para toda la tabla
            $styleArray = [
                'borders' => [
                    'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
                ]
            ];
            $sheet->getStyle('A16:G' . ($row - 1))->applyFromArray($styleArray);

            // Guardar archivo
            $extension = $reporte->formato === 'excel' ? 'xlsx' : 'pdf';
            $nombreArchivo = 'reporte_' . $reporte->id . '.' . $extension;
            $rutaCompleta = storage_path('app/public/reportes/' . $nombreArchivo);

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
