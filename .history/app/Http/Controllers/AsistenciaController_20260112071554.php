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

        return view('asistencia.admin-index', compact('anioActual'));
    }



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

    /**
     * Vista para verificar justificaciones
     */
    public function verificar()
    {
        // TEMPORAL: Remover verificación de permisos para diagnosticar
        // Verificar permisos - permitir también docentes por ahora para testing
        // if (!Auth::user()->hasRole('administrador') && !Auth::user()->hasRole('docente')) {
        //     abort(403, 'No tienes permisos para acceder a esta sección. Rol actual: ' . implode(', ', Auth::user()->getRoleNames()));
        // }

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
            'justificacion_id' => 'required|exists:justificaciones_asistencia,id',
            'accion' => 'required|in:Aprobar,Rechazar',
            'observaciones' => 'nullable|string|max=500'
        ]);

        try {
            DB::beginTransaction();

            $justificacion = JustificacionAsistencia::findOrFail($request->justificacion_id);

            if ($request->accion === 'Aprobar') {
                $justificacion->update([
                    'estado' => 'aprobado',
                    'observaciones_revision' => $request->observaciones,
                    'fecha_revision' => now(),
                    'usuario_revisor_id' => Auth::id()
                ]);

                // Find the curso_id from the matricula's grado and seccion
                $matricula = $justificacion->matricula;
                \Log::info('Procesando justificación', [
                    'justificacion_id' => $justificacion->id,
                    'matricula_id' => $matricula->matricula_id,
                    'idGrado' => $matricula->idGrado,
                    'idSeccion' => $matricula->idSeccion
                ]);

                $curso = \App\Models\InfCurso::where('grado_id', $matricula->idGrado)
                    ->where('seccion_id', $matricula->idSeccion)
                    ->first();

                \Log::info('Resultado búsqueda curso', [
                    'curso_encontrado' => $curso ? 'SI' : 'NO',
                    'curso_id' => $curso ? $curso->curso_id : null
                ]);

                if ($curso) {
                    AsistenciaDiaria::updateOrCreate(
                        [
                            'matricula_id' => $justificacion->matricula_id,
                            'fecha' => $justificacion->fecha
                        ],
                        [
                            'curso_id' => $curso->curso_id,
                            'tipo_asistencia_id' => TipoAsistencia::where('codigo', 'J')->first()->id ?? 3,
                            'justificado' => true,
                            'observaciones' => 'Justificado: ' . $justificacion->motivo,
                            'usuario_registro' => Auth::id()
                        ]
                    );
                } else {
                    \Log::warning('No se pudo crear registro de asistencia diaria: curso no encontrado', [
                        'justificacion_id' => $justificacion->id,
                        'matricula_id' => $matricula->matricula_id,
                        'idGrado' => $matricula->idGrado,
                        'idSeccion' => $matricula->idSeccion
                    ]);
                }

                Notificacion::crearNotificacionJustificacionResuelta($justificacion->id, true);
            } else {
                $justificacion->update([
                    'estado' => 'rechazado',
                    'observaciones_revision' => $request->observaciones,
                    'fecha_revision' => now(),
                    'usuario_revisor_id' => Auth::id()
                ]);

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
}

    // ========== MÉTODOS PARA DOCENTES ==========

    /**
     * Panel integrado del docente
     */
    public function docenteDashboard(Request $request)
    {
        try {
            // Verificar permisos - TEMPORAL: Permitir también administradores para testing
            if (!Auth::user()->hasRole('Docente') && !Auth::user()->hasRole('Administrador')) {
                abort(403, 'No tienes permisos para acceder a esta sección.');
            }

            // Verificar que el usuario tenga relación con docente
            if (!Auth::user()->persona || !Auth::user()->persona->docente) {
            return view('asistencia.docente-dashboard', [
                'error' => 'Tu cuenta de docente no está completamente configurada. Contacta al administrador.',
                'clases_hoy' => collect(),
                'fecha_seleccionada' => null
            ]);
            }

            $docente = Auth::user()->persona->docente;

            // Obtener fecha seleccionada del parámetro URL o usar hoy por defecto
            $fechaSeleccionada = $request->get('fecha');
            if ($fechaSeleccionada) {
                try {
                    $fechaSeleccionada = \Carbon\Carbon::parse($fechaSeleccionada)->startOfDay();
                } catch (\Exception $e) {
                    $fechaSeleccionada = today();
                }
            } else {
                $fechaSeleccionada = today();
            }

            // Obtener sesiones de clase de la fecha seleccionada
            $clases_hoy = \App\Models\SesionClase::with(['cursoAsignatura.curso.grado', 'cursoAsignatura.curso.seccion', 'cursoAsignatura.asignatura', 'aula'])
                ->whereHas('cursoAsignatura', function($query) use ($docente) {
                    $query->where('profesor_id', $docente->profesor_id);
                })
                ->whereDate('fecha', $fechaSeleccionada)
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

            // Obtener cursos del docente
            $cursos_docente = \App\Models\CursoAsignatura::with(['curso.grado', 'curso.seccion'])
                ->where('profesor_id', $docente->profesor_id)
                ->whereHas('curso', function($q) {
                    $q->whereIn('estado', ['Activo', 'En Curso']);
                })
                ->get()
                ->map(function($cursoAsignatura) {
                    return $cursoAsignatura->curso;
                })
                ->unique('id');

            // Estadísticas
            $total_clases_hoy = $clases_hoy->count();
            $clases_completadas = $clases_hoy->where('tiene_asistencia_hoy', true)->count();
            $asistencias_pendientes = $total_clases_hoy - $clases_completadas;

            $total_estudiantes = $clases_hoy->sum(function($sesion) {
                return $sesion->cursoAsignatura->curso->matriculas()->where('estado', 'Activo')->count();
            });

            $estadisticas = [
                'total_clases_hoy' => $total_clases_hoy,
                'clases_completadas' => $clases_completadas,
                'asistencias_pendientes' => $asistencias_pendientes,
                'total_estudiantes' => $total_estudiantes,
                'calificaciones_pendientes' => 0,
                'promedio_general' => 85,
                'estudiantes_riesgo' => 2,
                'completitud_general' => $total_clases_hoy > 0 ? round(($clases_completadas / $total_clases_hoy) * 100) : 100,
                'asistencias_semana' => 45,
                'inasistencias_semana' => 5,
            ];

            $reportes_recientes = \App\Models\ReporteGenerado::where('usuario_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            return view('asistencia.docente-dashboard', compact(
                'clases_hoy',
                'cursos_docente',
                'estadisticas',
                'reportes_recientes',
                'fecha_seleccionada'
            ));
        } catch (\Exception $e) {
            \Log::error('Error en docenteDashboard: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());

            // Definir variables por defecto para el error
            $clases_hoy = collect();
            $cursos_docente = collect();
            $estadisticas = [
                'total_clases_hoy' => 0,
                'clases_completadas' => 0,
                'asistencias_pendientes' => 0,
                'total_estudiantes' => 0,
                'calificaciones_pendientes' => 0,
                'promedio_general' => 0,
                'estudiantes_riesgo' => 0,
                'completitud_general' => 0,
                'asistencias_semana' => 0,
                'inasistencias_semana' => 0,
            ];
            $reportes_recientes = collect();
            $fecha_seleccionada = null;

            return view('asistencia.docente-dashboard', compact(
                'clases_hoy',
                'cursos_docente',
                'estadisticas',
                'reportes_recientes',
                'fecha_seleccionada'
            ));
        }
    }

    /**
     * Vista para tomar asistencia (lista de clases disponibles o sesión específica)
     */
    public function docenteTomarAsistencia(Request $request)
    {
        try {
            // Verificar permisos
            if (!Auth::user()->hasRole('Docente') && !Auth::user()->hasRole('Administrador')) {
                abort(403, 'No tienes permisos para acceder a esta sección.');
            }

            // Verificar que el usuario tenga relación con docente
            if (!Auth::user()->persona || !Auth::user()->persona->docente) {
                return view('asistencia.docente-tomar-asistencia', [
                    'error' => 'Tu cuenta de docente no está completamente configurada. Contacta al administrador.',
                    'clases_hoy' => collect(),
                    'fecha_seleccionada' => null
                ]);
            }

            $docente = Auth::user()->persona->docente;

            // Verificar si se especificó una sesión específica
            $sesionId = $request->get('sesion');
            if ($sesionId) {
                // Mostrar vista específica para tomar asistencia de una sesión
                return $this->docenteTomarAsistenciaSesion($sesionId, $docente);
            }

            // Vista general: Obtener fecha seleccionada del parámetro URL o usar hoy por defecto
            $fechaSeleccionada = $request->get('fecha');
            if ($fechaSeleccionada) {
                try {
                    $fechaSeleccionada = \Carbon\Carbon::parse($fechaSeleccionada)->startOfDay();
                } catch (\Exception $e) {
                    $fechaSeleccionada = today();
                }
            } else {
                $fechaSeleccionada = today();
            }

            // Obtener sesiones de clase de la fecha seleccionada
            $clases_hoy = \App\Models\SesionClase::with(['cursoAsignatura.curso.grado', 'cursoAsignatura.curso.seccion', 'cursoAsignatura.asignatura', 'aula'])
                ->whereHas('cursoAsignatura', function($query) use ($docente) {
                    $query->where('profesor_id', $docente->profesor_id);
                })
                ->whereDate('fecha', $fechaSeleccionada)
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

            return view('asistencia.docente-tomar-asistencia', [
                'clases_hoy' => $clases_hoy,
                'fecha_seleccionada' => $fechaSeleccionada,
                'modo' => 'general'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error en docenteTomarAsistencia: ' . $e->getMessage());
            return view('asistencia.docente-tomar-asistencia', [
                'error' => 'Error al cargar la página: ' . $e->getMessage(),
                'clases_hoy' => collect(),
                'fecha_seleccionada' => null,
                'modo' => 'general'
            ]);
        }
    }

    /**
     * Vista específica para tomar asistencia de una sesión
     */
    private function docenteTomarAsistenciaSesion($sesionId, $docente)
    {
        try {
            // Obtener la sesión específica
            $sesion = \App\Models\SesionClase::with([
                'cursoAsignatura.curso.grado',
                'cursoAsignatura.curso.seccion',
                'cursoAsignatura.asignatura',
                'aula'
            ])->findOrFail($sesionId);

            // Verificar que la sesión pertenece al docente
            if ($sesion->cursoAsignatura->profesor_id !== $docente->profesor_id) {
                abort(403, 'No tienes permisos para acceder a esta sesión.');
            }

            // Obtener estudiantes matriculados en el curso de esta sesión
            $matriculaIds = $sesion->cursoAsignatura->curso->matriculas()
                ->where('estado', 'Activo')
                ->pluck('matricula_id');

            // Obtener asistencias existentes para todos los estudiantes de una vez (evitar N+1 queries)
            $asistenciasExistentes = AsistenciaAsignatura::with('tipoAsistencia')
                ->whereIn('matricula_id', $matriculaIds)
                ->where('curso_asignatura_id', $sesion->cursoAsignatura->id)
                ->whereDate('fecha', $sesion->fecha)
                ->get()
                ->keyBy('matricula_id');

            $estudiantes = $sesion->cursoAsignatura->curso->matriculas()
                ->with(['estudiante.persona'])
                ->where('estado', 'Activo')
                ->orderBy('matriculas.matricula_id')
                ->get()
                ->map(function($matricula) use ($asistenciasExistentes) {
                    $asistenciaExistente = $asistenciasExistentes->get($matricula->matricula_id);

                    $matricula->asistencia_actual = $asistenciaExistente ? $asistenciaExistente->tipoAsistencia->codigo : 'P';
                    $matricula->asistencia_id = $asistenciaExistente ? $asistenciaExistente->asistencia_asignatura_id : null;

                    return $matricula;
                });

            // Estadísticas de la sesión
            $estadisticasSesion = [
                'total_estudiantes' => $estudiantes->count(),
                'asistencias_registradas' => $estudiantes->whereNotNull('asistencia_id')->count(),
                'presentes' => $estudiantes->where('asistencia_actual', 'P')->count(),
                'ausentes' => $estudiantes->where('asistencia_actual', 'A')->count(),
                'tardes' => $estudiantes->where('asistencia_actual', 'T')->count(),
                'justificados' => $estudiantes->where('asistencia_actual', 'J')->count(),
            ];

            return view('asistencia.docente-tomar-asistencia', [
                'sesion' => $sesion,
                'estudiantes' => $estudiantes,
                'estadisticas_sesion' => $estadisticasSesion,
                'modo' => 'sesion',
                'fecha_seleccionada' => $sesion->fecha
            ]);

        } catch (\Exception $e) {
            \Log::error('Error en docenteTomarAsistenciaSesion: ' . $e->getMessage());
            return view('asistencia.docente-tomar-asistencia', [
                'error' => 'Error al cargar la sesión: ' . $e->getMessage(),
                'modo' => 'general',
                'clases_hoy' => collect(),
                'fecha_seleccionada' => null
            ]);
        }
    }

    /**
     * Vista para ver asistencias tomadas con filtros
     */
    public function docenteVerAsistencias(Request $request)
    {
        try {
            // Verificar permisos
            if (!Auth::user()->hasRole('Docente') && !Auth::user()->hasRole('Administrador')) {
                abort(403, 'No tienes permisos para acceder a esta sección.');
            }

            // Verificar que el usuario tenga relación con docente
            if (!Auth::user()->persona || !Auth::user()->persona->docente) {
                return view('asistencia.docente-ver-asistencias', [
                    'error' => 'Tu cuenta de docente no está completamente configurada. Contacta al administrador.',
                    'asistencias' => collect(),
                    'cursos_docente' => collect(),
                    'filtros' => []
                ]);
            }

            $docente = Auth::user()->persona->docente;

            // Obtener cursos del docente para filtros
            $cursos_docente = \App\Models\CursoAsignatura::with(['curso.grado', 'curso.seccion'])
                ->where('profesor_id', $docente->profesor_id)
                ->whereHas('curso', function($q) {
                    $q->whereIn('estado', ['Activo', 'En Curso']);
                })
                ->get()
                ->map(function($cursoAsignatura) {
                    return $cursoAsignatura->curso;
                })
                ->unique('id');

            // Construir query con filtros
            $query = AsistenciaAsignatura::with(['matricula.estudiante.persona', 'matricula.curso.grado', 'matricula.curso.seccion', 'tipoAsistencia', 'cursoAsignatura.asignatura'])
                ->whereHas('cursoAsignatura', function($query) use ($docente) {
                    $query->where('profesor_id', $docente->profesor_id);
                });

            // Aplicar filtros
            $filtros = [];

            if ($request->filled('curso_id')) {
                $curso = \App\Models\InfCurso::find($request->curso_id);
                if ($curso) {
                    $query->whereHas('matricula', function($q) use ($curso) {
                        $q->where('idGrado', $curso->grado_id)
                          ->where('idSeccion', $curso->seccion_id);
                    });
                    $filtros['curso'] = $curso->grado->nombre . ' ' . $curso->seccion->nombre;
                }
            }

            if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
                $query->whereBetween('fecha', [$request->fecha_inicio, $request->fecha_fin]);
                $filtros['fecha_inicio'] = $request->fecha_inicio;
                $filtros['fecha_fin'] = $request->fecha_fin;
            } elseif ($request->filled('fecha_inicio')) {
                $query->whereDate('fecha', '>=', $request->fecha_inicio);
                $filtros['fecha_inicio'] = $request->fecha_inicio;
            } elseif ($request->filled('fecha_fin')) {
                $query->whereDate('fecha', '<=', $request->fecha_fin);
                $filtros['fecha_fin'] = $request->fecha_fin;
            }

            if ($request->filled('tipo_asistencia')) {
                $tipoAsistencia = TipoAsistencia::where('codigo', $request->tipo_asistencia)->first();
                if ($tipoAsistencia) {
                    $query->where('tipo_asistencia_id', $tipoAsistencia->id);
                    $filtros['tipo_asistencia'] = $tipoAsistencia->nombre;
                }
            }

            if ($request->filled('asignatura_id')) {
                $query->whereHas('cursoAsignatura', function($q) use ($request) {
                    $q->where('asignatura_id', $request->asignatura_id);
                });
                $asignatura = \App\Models\InfAsignatura::find($request->asignatura_id);
                if ($asignatura) {
                    $filtros['asignatura'] = $asignatura->nombre;
                }
            }

            // Si no hay filtros aplicados, mostrar últimas 4 semanas por defecto
            if (empty($filtros)) {
                $query->whereDate('fecha', '>=', now()->subWeeks(4));
            }

            $asistencias = $query->orderBy('fecha', 'desc')
                ->orderBy('hora_registro', 'desc')
                ->paginate(25);

            // Obtener asignaturas para el filtro
            $asignaturas_docente = \App\Models\CursoAsignatura::with('asignatura')
                ->where('profesor_id', $docente->profesor_id)
                ->whereHas('curso', function($q) {
                    $q->whereIn('estado', ['Activo', 'En Curso']);
                })
                ->get()
                ->pluck('asignatura')
                ->unique('id')
                ->sortBy('nombre');

            return view('asistencia.docente-ver-asistencias', compact('asistencias', 'cursos_docente', 'asignaturas_docente', 'filtros'));

        } catch (\Exception $e) {
            \Log::error('Error en docenteVerAsistencias: ' . $e->getMessage());
            return view('asistencia.docente-ver-asistencias', [
                'error' => 'Error al cargar las asistencias: ' . $e->getMessage(),
                'asistencias' => collect(),
                'cursos_docente' => collect(),
                'asignaturas_docente' => collect(),
                'filtros' => []
            ]);
        }
    }

    /**
     * Vista para reportes de asistencia del docente
     */
    public function docenteReportes()
    {
        try {
            // Verificar permisos
            if (!Auth::user()->hasRole('Docente') && !Auth::user()->hasRole('Administrador')) {
                abort(403, 'No tienes permisos para acceder a esta sección.');
            }

            // Verificar que el usuario tenga relación con docente
            if (!Auth::user()->persona || !Auth::user()->persona->docente) {
                return view('asistencia.docente-reportes', [
                    'error' => 'Tu cuenta de docente no está completamente configurada. Contacta al administrador.',
                    'cursos_docente' => collect(),
                    'reportes_recientes' => collect()
                ]);
            }

            $docente = Auth::user()->persona->docente;

            // Obtener cursos del docente
            $cursos_docente = \App\Models\CursoAsignatura::with(['curso.grado', 'curso.seccion'])
                ->where('profesor_id', $docente->profesor_id)
                ->whereHas('curso', function($q) {
                    $q->whereIn('estado', ['Activo', 'En Curso']);
                })
                ->get()
                ->map(function($cursoAsignatura) {
                    return $cursoAsignatura->curso;
                })
                ->unique('id');

            // Obtener reportes recientes generados por el docente
            $reportes_recientes = \App\Models\ReporteGenerado::where('usuario_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            return view('asistencia.docente-reportes', compact('cursos_docente', 'reportes_recientes'));

        } catch (\Exception $e) {
            \Log::error('Error en docenteReportes: ' . $e->getMessage());
            return view('asistencia.docente-reportes', [
                'error' => 'Error al cargar los reportes: ' . $e->getMessage(),
                'cursos_docente' => collect(),
                'reportes_recientes' => collect()
            ]);
        }
    }

    /**
     * Vista para estadísticas de asistencia del docente
     */
    public function docenteEstadisticas()
    {
        try {
            // Verificar permisos
            if (!Auth::user()->hasRole('Docente') && !Auth::user()->hasRole('Administrador')) {
                abort(403, 'No tienes permisos para acceder a esta sección.');
            }

            // Verificar que el usuario tenga relación con docente
            if (!Auth::user()->persona || !Auth::user()->persona->docente) {
                return view('asistencia.docente-estadisticas', [
                    'error' => 'Tu cuenta de docente no está completamente configurada. Contacta al administrador.',
                    'estadisticas' => []
                ]);
            }

            $docente = Auth::user()->persona->docente;

            // Calcular estadísticas del mes actual
            $mesActual = now()->month;
            $anioActual = now()->year;

            $totalAsistencias = AsistenciaAsignatura::whereHas('cursoAsignatura', function($query) use ($docente) {
                $query->where('profesor_id', $docente->profesor_id);
            })
            ->whereMonth('fecha', $mesActual)
            ->whereYear('fecha', $anioActual)
            ->count();

            $totalPresentes = AsistenciaAsignatura::whereHas('cursoAsignatura', function($query) use ($docente) {
                $query->where('profesor_id', $docente->profesor_id);
            })
            ->whereMonth('fecha', $mesActual)
            ->whereYear('fecha', $anioActual)
            ->whereHas('tipoAsistencia', function($q) {
                $q->where('codigo', 'P');
            })
            ->count();

            $totalAusentes = AsistenciaAsignatura::whereHas('cursoAsignatura', function($query) use ($docente) {
                $query->where('profesor_id', $docente->profesor_id);
            })
            ->whereMonth('fecha', $mesActual)
            ->whereYear('fecha', $anioActual)
            ->whereHas('tipoAsistencia', function($q) {
                $q->where('codigo', 'A');
            })
            ->count();

            $porcentajeAsistencia = $totalAsistencias > 0 ? round(($totalPresentes / $totalAsistencias) * 100, 1) : 0;

            // Estadísticas por curso
            $estadisticasPorCurso = \App\Models\CursoAsignatura::with(['curso.grado', 'curso.seccion'])
                ->where('profesor_id', $docente->profesor_id)
                ->whereHas('curso', function($q) {
                    $q->whereIn('estado', ['Activo', 'En Curso']);
                })
                ->get()
                ->map(function($cursoAsignatura) use ($mesActual, $anioActual) {
                    $asistenciasCurso = AsistenciaAsignatura::where('curso_asignatura_id', $cursoAsignatura->id)
                        ->whereMonth('fecha', $mesActual)
                        ->whereYear('fecha', $anioActual)
                        ->count();

                    $presentesCurso = AsistenciaAsignatura::where('curso_asignatura_id', $cursoAsignatura->id)
                        ->whereMonth('fecha', $mesActual)
                        ->whereYear('fecha', $anioActual)
                        ->whereHas('tipoAsistencia', function($q) {
                            $q->where('codigo', 'P');
                        })
                        ->count();

                    return [
                        'curso' => $cursoAsignatura->curso->grado->nombre . ' ' . $cursoAsignatura->curso->seccion->nombre,
                        'asignatura' => $cursoAsignatura->asignatura->nombre,
                        'total_asistencias' => $asistenciasCurso,
                        'presentes' => $presentesCurso,
                        'porcentaje' => $asistenciasCurso > 0 ? round(($presentesCurso / $asistenciasCurso) * 100, 1) : 0
                    ];
                });

            $estadisticas = [
                'mes_actual' => now()->locale('es')->monthName,
                'anio_actual' => $anioActual,
                'total_asistencias' => $totalAsistencias,
                'total_presentes' => $totalPresentes,
                'total_ausentes' => $totalAusentes,
                'porcentaje_asistencia' => $porcentajeAsistencia,
                'estadisticas_por_curso' => $estadisticasPorCurso
            ];

            return view('asistencia.docente-estadisticas', compact('estadisticas'));

        } catch (\Exception $e) {
            \Log::error('Error en docenteEstadisticas: ' . $e->getMessage());
            return view('asistencia.docente-estadisticas', [
                'error' => 'Error al cargar las estadísticas: ' . $e->getMessage(),
                'estadisticas' => []
            ]);
        }
    }

    /**
     * Ver asistencia de una sesión específica
     */
    public function docenteVerAsistencia(\App\Models\SesionClase $sesionClase)
    {
            // Verificar permisos - TEMPORAL: Permitir también administradores para testing
            if (!Auth::user()->hasRole('Docente') && !Auth::user()->hasRole('Administrador')) {
                abort(403, 'No tienes permisos para acceder a esta sección.');
            }

        $docente = Auth::user()->persona->docente;

        // TEMPORAL: Remover verificación de permisos para diagnosticar
        // Verificar que la sesión pertenece al docente
        // if (!$sesionClase->cursoAsignatura || $sesionClase->cursoAsignatura->profesor_id !== $docente->profesor_id) {
        //     abort(403, 'No tienes permisos para ver esta asistencia.');
        // }

        // Obtener asistencias de la sesión
        $asistencias = AsistenciaAsignatura::with(['matricula.estudiante', 'tipoAsistencia'])
            ->whereHas('matricula', function($q) use ($sesionClase) {
                $q->whereHas('curso', function($cq) use ($sesionClase) {
                    $cq->where('curso_id', $sesionClase->cursoAsignatura->curso_id);
                });
            })
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
            $sesionClase = \App\Models\SesionClase::with(['cursoAsignatura.curso.matriculas.estudiante.persona'])
                ->findOrFail($request->sesion_clase_id);

            // Verificar permisos - TEMPORAL: Permitir también administradores para testing
            if (!Auth::user()->hasRole('docente') && !Auth::user()->hasRole('administrador')) {
                throw new \Exception('No tienes permisos.');
            }

            // TEMPORAL: Remover verificación de permisos para diagnosticar
            // $docente = Auth::user()->persona->docente;
            // if (!$sesionClase->cursoAsignatura || $sesionClase->cursoAsignatura->profesor_id !== $docente->profesor_id) {
            //     throw new \Exception('No tienes permisos para esta sesión.');
            // }

            // Obtener estudiantes matriculados en el curso
            $estudiantes = $sesionClase->cursoAsignatura->curso->matriculas()
                ->with(['estudiante.persona'])
                ->where('estado', 'Activo')
                ->get()
                ->map(function($matricula) {
                    return [
                        'matricula_id' => $matricula->matricula_id,
                        'estudiante_id' => $matricula->estudiante->estudiante_id,
                        'nombres' => $matricula->estudiante->persona->nombres ?? 'Sin nombre',
                        'apellidos' => $matricula->estudiante->persona->apellidos ?? 'Sin apellidos',
                        'dni' => $matricula->estudiante->persona->dni ?? '',
                    ];
                });

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
            'asistencias.*.observaciones' => 'nullable|string|max=255'
        ]);

        try {
            DB::beginTransaction();

            $sesionClase = \App\Models\SesionClase::findOrFail($request->sesion_clase_id);

            // Verificar permisos - TEMPORAL: Permitir también administradores para testing
            if (!Auth::user()->hasRole('docente') && !Auth::user()->hasRole('administrador')) {
                throw new \Exception('No tienes permisos.');
            }

            $docente = Auth::user()->persona->docente;
            if (!$sesionClase->cursoAsignatura || $sesionClase->cursoAsignatura->profesor_id !== $docente->profesor_id) {
                throw new \Exception('No tienes permisos para esta sesión.');
            }

            $fecha = today();

            foreach ($request->asistencias as $asistenciaData) {
                $tipoAsistencia = TipoAsistencia::where('codigo', $asistenciaData['tipo_asistencia'])->first();

                AsistenciaAsignatura::updateOrCreate(
                    [
                        'matricula_id' => $asistenciaData['matricula_id'],
                        'fecha' => $fecha,
                        'curso_asignatura_id' => $sesionClase->cursoAsignatura->id
                    ],
                    [
                        'tipo_asistencia_id' => $tipoAsistencia->id,
                        'justificado' => $asistenciaData['tipo_asistencia'] === 'J',
                        'observaciones' => $asistenciaData['observaciones'] ?? null
                    ]
                );
            }

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
        // Verificar permisos - TEMPORAL: Permitir también administradores para testing
        if (!Auth::user()->hasRole('docente') && !Auth::user()->hasRole('administrador')) {
            abort(403, 'No tienes permisos.');
        }

        // TEMPORAL: Remover verificación de permisos para diagnosticar
        // $docente = Auth::user()->persona->docente;
        // if (!$sesionClase->cursoAsignatura || $sesionClase->cursoAsignatura->profesor_id !== $docente->profesor_id) {
        //     abort(403, 'No tienes permisos.');
        // }

        $asistencias = AsistenciaAsignatura::with(['matricula.estudiante', 'tipoAsistencia'])
            ->whereHas('matricula', function($q) use ($sesionClase) {
                $q->whereHas('curso', function($cq) use ($sesionClase) {
                    $cq->where('curso_id', $sesionClase->cursoAsignatura->curso_id);
                });
            })
            ->whereDate('fecha', $sesionClase->fecha)
            ->get();

        $pdf = Pdf::loadView('asistencia.reportes.docente-pdf', compact('sesionClase', 'asistencias'));
        return $pdf->download('asistencia_' . $sesionClase->cursoAsignatura->asignatura->nombre . '_' . date('Y-m-d') . '.pdf');
    }

    // ========== MÉTODOS PARA REPRESENTANTES ==========

    /**
     * Dashboard principal para representantes
     */
    public function representanteDashboard()
    {
        // Verificar permisos
        if (!Auth::user()->hasRole('representante')) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        // Check if user is logged in and has representative role
        if (!Auth::check()) {
            return redirect()->route('login.index')->with('error', 'Debes iniciar sesión.');
        }

        $user = Auth::user();

        // Check if user has representative role
        if (!$user->hasRole('representante')) {
            return redirect()->route('rutarrr1')->with('error', 'No tienes permisos para acceder a esta sección.');
        }

        // Check if user has persona and representante relationship
        if (!$user->persona) {
            return view('asistencia.representante-dashboard', [
                'error' => 'Tu cuenta no tiene una persona asociada. Contacta al administrador.',
                'debug' => ['user_id' => $user->usuario_id]
            ]);
        }

        if (!$user->persona->representante) {
            return view('asistencia.representante-dashboard', [
                'error' => 'Tu cuenta de persona no tiene un representante asociado. Contacta al administrador.',
                'debug' => [
                    'user_id' => $user->usuario_id,
                    'persona_id' => $user->persona->id_persona,
                    'username' => $user->username
                ]
            ]);
        }

        $representante = $user->persona->representante;

        // Obtener estadísticas generales
        $estudiantes = $representante->estudiantes()
            ->with(['persona', 'matricula.grado', 'matricula.seccion'])
            ->get();

        $estadisticas = [
            'total_estudiantes' => $estudiantes->count(),
            'promedio_asistencia' => 0,
            'total_inasistencias' => 0,
            'justificaciones_pendientes' => 0
        ];

        if ($estudiantes->count() > 0) {
            // Calcular estadísticas del mes actual
            $mesActual = now()->month;
            $anioActual = now()->year;

            $totalAsistencias = 0;
            $totalInasistencias = 0;
            $totalJustificacionesPendientes = 0;

            foreach ($estudiantes as $estudiante) {
                if ($estudiante->matricula) {
                    $asistenciasMes = AsistenciaAsignatura::where('matricula_id', $estudiante->matricula->matricula_id)
                        ->whereMonth('fecha', $mesActual)
                        ->whereYear('fecha', $anioActual)
                        ->get();

                    $totalAsistencias += $asistenciasMes->count();
                    $totalInasistencias += $asistenciasMes->where('tipo_asistencia_id', TipoAsistencia::where('codigo', 'A')->first()->id ?? 2)->count();
                }
            }

            $estadisticas['promedio_asistencia'] = $totalAsistencias > 0 ? round((($totalAsistencias - $totalInasistencias) / $totalAsistencias) * 100, 1) : 0;
            $estadisticas['total_inasistencias'] = $totalInasistencias;
            $estadisticas['justificaciones_pendientes'] = JustificacionAsistencia::whereIn('matricula_id', $estudiantes->pluck('matricula.matricula_id'))
                ->where('estado', 'pendiente')
                ->count();
        }

        return view('asistencia.representante-dashboard', compact('estadisticas'));
    }

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
     * Ver detalle de asistencia de un estudiante
     */
    public function representanteDetalle($estudiante_id, Request $request)
    {
        // Verificar permisos
        if (!Auth::user()->hasRole('representante')) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        $representante = Auth::user()->persona->representante;

        // Buscar el estudiante
        $estudiante = InfEstudiante::with(['persona', 'matricula.grado', 'matricula.seccion'])->findOrFail($estudiante_id);

        // Verificar que el estudiante pertenece al representante
        if (!$representante->estudiantes()->where('estudiantes.estudiante_id', $estudiante->estudiante_id)->exists()) {
            abort(403, 'No tienes permisos para ver este estudiante.');
        }

        // Verificar que el estudiante tenga matrícula activa
        if (!$estudiante->matricula) {
            abort(404, 'El estudiante no tiene matrícula activa.');
        }

        $mes = $request->get('mes', date('n'));
        $anio = $request->get('anio', date('Y'));

        // Obtener asistencias del estudiante para el período
        $asistencias = AsistenciaAsignatura::with(['cursoAsignatura.asignatura', 'cursoAsignatura.docente', 'tipoAsistencia'])
            ->where('matricula_id', $estudiante->matricula->matricula_id)
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
        $justificaciones = JustificacionAsistencia::where('matricula_id', $estudiante->matricula->matricula_id)
            ->orderBy('created_at', 'desc')
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
            'estudiante_id' => 'required|exists:estudiantes,estudiante_id',
            'fecha_falta' => 'required|date|before_or_equal:today',
            'motivo' => 'required|string|max=500',
            'documento_adjunto' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        try {
            DB::beginTransaction();

            // Verificar permisos
            if (!Auth::user()->hasRole('representante')) {
                throw new \Exception('No tienes permisos.');
            }

            $representante = Auth::user()->persona->representante;
            $estudiante = InfEstudiante::findOrFail($request->estudiante_id);

            // Verificar que el estudiante pertenece al representante
            if (!$representante->estudiantes()->where('estudiantes.estudiante_id', $estudiante->estudiante_id)->exists()) {
                throw new \Exception('No tienes permisos para este estudiante.');
            }

            // Verificar que el estudiante tenga matrícula activa
            if (!$estudiante->matricula) {
                throw new \Exception('El estudiante no tiene matrícula activa.');
            }

            // Verificar que no existe una justificación pendiente para esa fecha
            $justificacionExistente = JustificacionAsistencia::where('matricula_id', $estudiante->matricula->matricula_id)
                ->where('fecha', $request->fecha_falta)
                ->whereIn('estado', ['pendiente', 'aprobado'])
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
            $justificacion = new JustificacionAsistencia();
            $justificacion->matricula_id = $estudiante->matricula->matricula_id;
            $justificacion->usuario_creador_id = Auth::id();
            $justificacion->fecha = $request->fecha_falta;
            $justificacion->motivo = $request->motivo;
            $justificacion->documento_justificacion = $documentoPath;
            $justificacion->estado = 'Pendiente';
            $justificacion->descripcion = $request->motivo; // Some tables might need this field
            $justificacion->save();

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
        if (!Auth::user()->hasRole('representante')) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        $representante = Auth::user()->persona->representante;

        // Verificar que el estudiante pertenece al representante
        if (!$representante->estudiantes()->where('estudiantes.estudiante_id', $estudiante->estudiante_id)->exists()) {
            abort(403, 'No tienes permisos para este estudiante.');
        }

        // Load estudiante with all necessary relationships
        $estudiante = InfEstudiante::with([
            'persona',
            'matricula.grado',
            'matricula.seccion',
            'matricula.curso.anoLectivo'
        ])->findOrFail($estudiante->estudiante_id);

        // Verificar que el estudiante tenga matrícula activa
        if (!$estudiante->matricula) {
            abort(404, 'El estudiante no tiene matrícula activa.');
        }

        $mes = $request->get('mes', date('n'));
        $anio = $request->get('anio', date('Y'));

        $asistencias = AsistenciaAsignatura::with(['cursoAsignatura.asignatura', 'tipoAsistencia'])
            ->where('matricula_id', $estudiante->matricula->matricula_id)
            ->whereMonth('fecha', $mes)
            ->whereYear('fecha', $anio)
            ->orderBy('fecha')
            ->get();

        $pdf = Pdf::loadView('asistencia.reportes.representante-pdf', compact('estudiante', 'asistencias', 'mes', 'anio'));
        return $pdf->download('reporte_asistencia_' . $estudiante->nombres . '_' . $estudiante->apellidos . '_' . $mes . '_' . $anio . '.pdf');
    }

    // ========== MÉTODOS PARA REPORTES ==========

    /**
     * Guardar y exportar reporte de asistencia
     */
    public function guardarReporteExportado(Request $request, $formato)
    {
        try {
            \Log::info('guardarReporteExportado called', ['formato' => $formato, 'request' => $request->all()]);

            $request->validate([
                'tipo_reporte' => 'required|string',
                'fecha_inicio' => 'required|date',
                'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
                'formato' => 'required|in:pdf,excel'
            ]);

            // Verificar permisos
            if (!Auth::user()->hasRole('Docente') && !Auth::user()->hasRole('Administrador')) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permisos para generar reportes.'
                ], 403);
            }

            // Obtener datos del reporte
            $asistencias = AsistenciaAsignatura::with([
                'matricula.estudiante.persona',
                'tipoAsistencia',
                'cursoAsignatura.asignatura'
            ])
            ->whereBetween('fecha', [$request->fecha_inicio, $request->fecha_fin])
            ->orderBy('fecha')
            ->get();

            // Generar el reporte según el formato
            if ($formato === 'pdf') {
                $pdf = Pdf::loadView('asistencia.reportes.admin-pdf', compact('asistencias'));
                $contenido = $pdf->output();
                $filename = 'reporte_asistencia_' . date('Y-m-d') . '.pdf';
            } else {
                // Para Excel, usar el export existente
                $export = new AsistenciaExport($asistencias, $request->fecha_inicio, $request->fecha_fin);
                $filename = 'reporte_asistencia_' . date('Y-m-d') . '.xlsx';

                // Guardar temporalmente el archivo Excel
                $tempPath = storage_path('app/temp/' . $filename);
                if (!file_exists(storage_path('app/temp'))) {
                    mkdir(storage_path('app/temp'), 0755, true);
                }

                $writer = new Xlsx($export);
                $writer->save($tempPath);
                $contenido = file_get_contents($tempPath);
                unlink($tempPath); // Eliminar archivo temporal
            }

            // Guardar el reporte en la base de datos
            $reporte = new ReporteGenerado();
            $reporte->usuario_id = Auth::id();
            $reporte->tipo_reporte = $request->tipo_reporte;
            $reporte->formato = $formato;
            $reporte->parametros = [
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_fin' => $request->fecha_fin
            ];
            $reporte->archivo_nombre = $filename;
            $reporte->archivo_contenido = $contenido;
            $reporte->save();

            return response()->json([
                'success' => true,
                'message' => 'Reporte generado correctamente.',
                'reporte_id' => $reporte->id
            ]);

        } catch (\Exception $e) {
            \Log::error('Error al generar reporte exportado: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al generar el reporte: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Guardar y generar reporte de asistencia
     */
    public function guardarReporteGenerado(Request $request)
    {
        \Log::info('guardarReporteGenerado method STARTED');
        try {
            \Log::info('guardarReporteGenerado called', $request->all());

            $request->validate([
                'tipo_reporte' => 'required|string',
                'curso_id' => 'required|exists:cursos,curso_id',
                'fecha_inicio' => 'required|date',
                'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
                'formato' => 'required|in:pdf,excel'
            ]);

            \Log::info('Validation passed');

            // Verificar permisos
            if (!Auth::user()->hasRole('Docente') && !Auth::user()->hasRole('Administrador')) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permisos para generar reportes.'
                ], 403);
            }

            \Log::info('Permissions checked');

            // Verificar que el docente tenga acceso al curso
            $docente = Auth::user()->persona->docente;
            $curso = \App\Models\InfCurso::with(['grado', 'seccion'])->findOrFail($request->curso_id);

            \Log::info('Curso found', ['curso_id' => $curso->curso_id]);

            $tieneAcceso = \App\Models\CursoAsignatura::where('profesor_id', $docente->profesor_id)
                ->where('curso_id', $request->curso_id)
                ->exists();

            if (!$tieneAcceso && !Auth::user()->hasRole('Administrador')) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes acceso a este curso.'
                ], 403);
            }

            // Obtener datos del reporte
            $asistencias = AsistenciaAsignatura::with([
                'matricula.estudiante.persona',
                'tipoAsistencia',
                'cursoAsignatura.asignatura'
            ])
            ->whereHas('matricula', function($q) use ($request) {
                $q->where('idGrado', $curso->grado_id)
                  ->where('idSeccion', $curso->seccion_id);
            })
            ->whereBetween('fecha', [$request->fecha_inicio, $request->fecha_fin])
            ->orderBy('fecha')
            ->get();

            // Generar el reporte según el formato
            if ($request->formato === 'pdf') {
                $fecha_inicio = $request->fecha_inicio;
                $fecha_fin = $request->fecha_fin;

                \Log::info('Generating PDF with variables', [
                    'curso_id' => $curso->curso_id ?? 'N/A',
                    'fecha_inicio' => $fecha_inicio,
                    'fecha_fin' => $fecha_fin,
                    'asistencias_count' => $asistencias->count()
                ]);

                // Generate PDF
                $pdf = Pdf::loadView('asistencia.reportes.docente-curso-pdf', [
                    'curso' => $curso,
                    'asistencias' => $asistencias,
                    'fecha_inicio' => $fecha_inicio,
                    'fecha_fin' => $fecha_fin
                ]);
                $contenido = $pdf->output();

                $filename = 'reporte_asistencia_' . $curso->grado->nombre . '_' . $curso->seccion->nombre . '_' . date('Y-m-d') . '.pdf';
            } else {
                // Para Excel, usar el export existente
                $export = new AsistenciaExport($asistencias, $request->fecha_inicio, $request->fecha_fin);
                $filename = 'reporte_asistencia_' . $curso->grado->nombre . '_' . $curso->seccion->nombre . '_' . date('Y-m-d') . '.xlsx';

                // Guardar temporalmente el archivo Excel
                $tempPath = storage_path('app/temp/' . $filename);
                if (!file_exists(storage_path('app/temp'))) {
                    mkdir(storage_path('app/temp'), 0755, true);
                }

                $writer = new Xlsx($export);
                $writer->save($tempPath);
                $contenido = file_get_contents($tempPath);
                unlink($tempPath); // Eliminar archivo temporal
            }

            // Guardar el reporte en la base de datos
            $reporte = new ReporteGenerado();
            $reporte->usuario_id = Auth::id();
            $reporte->tipo_reporte = $request->tipo_reporte;
            $reporte->formato = $request->formato;
            $reporte->parametros = [
                'curso_id' => $request->curso_id,
                'curso' => $curso->grado->nombre . ' ' . $curso->seccion->nombre,
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_fin' => $request->fecha_fin
            ];
            $reporte->archivo_nombre = $filename;
            $reporte->archivo_contenido = $contenido;
            $reporte->save();

            return response()->json([
                'success' => true,
                'message' => 'Reporte generado correctamente.',
                'reporte_id' => $reporte->id
            ]);

        } catch (\Exception $e) {
            \Log::error('Error al generar reporte: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Error al generar el reporte: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exportar asistencias filtradas
     */
    public function exportarAsistenciasFiltradas(Request $request)
    {
        try {
            // Verificar permisos
            if (!Auth::user()->hasRole('Docente') && !Auth::user()->hasRole('Administrador')) {
                abort(403, 'No tienes permisos para acceder a esta sección.');
            }

            // Verificar que el usuario tenga relación con docente
            if (!Auth::user()->persona || !Auth::user()->persona->docente) {
                abort(403, 'Tu cuenta de docente no está completamente configurada.');
            }

            $docente = Auth::user()->persona->docente;
            $formato = $request->get('formato', 'pdf');

            // Construir query con filtros (igual que en docenteVerAsistencias)
            $query = AsistenciaAsignatura::with(['matricula.estudiante.persona', 'matricula.curso.grado', 'matricula.curso.seccion', 'tipoAsistencia', 'cursoAsignatura.asignatura'])
                ->whereHas('cursoAsignatura', function($query) use ($docente) {
                    $query->where('profesor_id', $docente->profesor_id);
                });

            // Aplicar filtros
            if ($request->filled('curso_id')) {
                $curso = \App\Models\InfCurso::find($request->curso_id);
                if ($curso) {
                    $query->whereHas('matricula', function($q) use ($curso) {
                        $q->where('idGrado', $curso->grado_id)
                          ->where('idSeccion', $curso->seccion_id);
                    });
                }
            }

            if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
                $query->whereBetween('fecha', [$request->fecha_inicio, $request->fecha_fin]);
            } elseif ($request->filled('fecha_inicio')) {
                $query->whereDate('fecha', '>=', $request->fecha_inicio);
            } elseif ($request->filled('fecha_fin')) {
                $query->whereDate('fecha', '<=', $request->fecha_fin);
            }

            if ($request->filled('tipo_asistencia')) {
                $tipoAsistencia = TipoAsistencia::where('codigo', $request->tipo_asistencia)->first();
                if ($tipoAsistencia) {
                    $query->where('tipo_asistencia_id', $tipoAsistencia->id);
                }
            }

            if ($request->filled('asignatura_id')) {
                $query->whereHas('cursoAsignatura', function($q) use ($request) {
                    $q->where('asignatura_id', $request->asignatura_id);
                });
            }

            // Si no hay filtros aplicados, mostrar últimas 4 semanas por defecto
            if (!$request->filled('curso_id') && !$request->filled('fecha_inicio') && !$request->filled('fecha_fin') && !$request->filled('tipo_asistencia') && !$request->filled('asignatura_id')) {
                $query->whereDate('fecha', '>=', now()->subWeeks(4));
            }

            $asistencias = $query->orderBy('fecha', 'desc')
                ->orderBy('hora_registro', 'desc')
                ->get();

            // Generar archivo según formato
            if ($formato === 'pdf') {
                $pdf = Pdf::loadView('asistencia.reportes.docente-asistencias-pdf', compact('asistencias'));
                $filename = 'asistencias_filtradas_' . date('Y-m-d_H-i-s') . '.pdf';
                return $pdf->download($filename);
            } else {
                // Para Excel
                $export = new AsistenciaExport($asistencias, $request->fecha_inicio ?? null, $request->fecha_fin ?? null);
                $filename = 'asistencias_filtradas_' . date('Y-m-d_H-i-s') . '.xlsx';

                // Guardar temporalmente el archivo Excel
                $tempPath = storage_path('app/temp/' . $filename);
                if (!file_exists(storage_path('app/temp'))) {
                    mkdir(storage_path('app/temp'), 0755, true);
                }

                $writer = new Xlsx($export);
                $writer->save($tempPath);

                return response()->download($tempPath, $filename)->deleteFileAfterSend(true);
            }

        } catch (\Exception $e) {
            \Log::error('Error al exportar asistencias filtradas: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al exportar las asistencias: ' . $e->getMessage());
        }
    }

    /**
     * Descargar reporte del historial
     */
    public function descargarReporteHistorial($reporteId)
    {
        try {
            $reporte = ReporteGenerado::where('usuario_id', Auth::id())
                ->findOrFail($reporteId);

            $headers = [
                'Content-Type' => $reporte->formato === 'pdf' ? 'application/pdf' : 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="' . $reporte->archivo_nombre . '"'
            ];

            return response($reporte->archivo_contenido, 200, $headers);

        } catch (\Exception $e) {
            abort(404, 'Reporte no encontrado.');
        }
    }

    // ========== MÉTODOS AUXILIARES ==========

    /**
     * API para obtener tabla de asistencias filtrada (solo asistenciasasignatura)
     */
    public function getTablaAsistencias(Request $request)
    {
        try {
            // Usar solo la tabla asistenciasasignatura con relaciones optimizadas
            $query = AsistenciaAsignatura::with([
                'matricula.estudiante.persona',
                'matricula.grado',
                'matricula.seccion',
                'tipoAsistencia',
                'cursoAsignatura.asignatura'
            ]);

            // Aplicar filtros
            if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
                $query->whereBetween('fecha', [$request->fecha_inicio, $request->fecha_fin]);
            } elseif ($request->filled('fecha_inicio')) {
                $query->where('fecha', '>=', $request->fecha_inicio);
            } elseif ($request->filled('fecha_fin')) {
                $query->where('fecha', '<=', $request->fecha_fin);
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

            // Solo matrículas activas
            $query->whereHas('matricula', function($q) {
                $q->where('estado', 'Activo');
            });

            // Si no hay filtros aplicados, mostrar últimas 4 semanas por defecto
            if (!$request->filled('fecha_inicio') && !$request->filled('fecha_fin') &&
                !$request->filled('tipo_asistencia') && !$request->filled('curso_id') &&
                !$request->filled('estudiante_id') && !$request->filled('docente_id') &&
                !$request->filled('nivel_id') && !$request->filled('grado_id') &&
                !$request->filled('seccion_id') && !$request->filled('asignatura_id')) {
                $query->where('fecha', '>=', now()->subWeeks(4));
            }

            $asistencias = $query->orderBy('fecha', 'desc')
                                ->orderBy('hora_registro', 'desc')
                                ->paginate(20);

            // Calcular estadísticas de manera más eficiente
            $totalRegistros = $asistencias->total();
            $totalPresentes = $asistencias->where('tipoAsistencia.computa_falta', 0)->count();
            $totalAusentes = $asistencias->where('tipoAsistencia.computa_falta', 1)->count();
            $totalTardanzas = $asistencias->where('tipoAsistencia.codigo', 'T')->count();
            $totalJustificados = $asistencias->where('tipoAsistencia.codigo', 'J')->count();

            $porcentajeAsistencia = $totalRegistros > 0
                ? round(($totalPresentes / $totalRegistros) * 100, 1) . '%'
                : '0%';

            $estadisticas = [
                'total_registros' => $totalRegistros,
                'total_presentes' => $totalPresentes,
                'total_ausentes' => $totalAusentes,
                'total_tardanzas' => $totalTardanzas,
                'total_justificados' => $totalJustificados,
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
