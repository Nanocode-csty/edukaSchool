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
     * Vista principal del docente (tomar asistencia)
     */
    public function docenteIndex()
    {
        // TEMPORAL: Remover verificación de permisos para diagnosticar
        // if (!Auth::user()->hasRole('docente') && !Auth::user()->hasRole('administrador')) {
        //     abort(403, 'No tienes permisos para acceder a esta sección.');
        // }

        // Obtener estadísticas generales usando AsistenciaDiaria (la tabla principal)
        $anioActual = InfAnioLectivo::where('estado', 'Activo')->first();

        // Si no hay año académico activo, usar datos de los últimos 6 meses
        if ($anioActual) {
            $fechaInicioAnio = $anioActual->fecha_inicio;
            $fechaFinAnio = $anioActual->fecha_fin;
        } else {
            $fechaInicioAnio = now()->subMonths(6)->startOfMonth();
            $fechaFinAnio = now()->endOfMonth();
        }

        // Calcular estadísticas basadas en AsistenciaAsignatura
        $totalRegistros = AsistenciaAsignatura::count();
        $totalPresentes = AsistenciaAsignatura::whereHas('tipoAsistencia', function($q) {
            $q->where('codigo', 'P');
        })->count();
        $totalAusentes = AsistenciaAsignatura::whereHas('tipoAsistencia', function($q) {
            $q->where('codigo', 'A');
        })->count();
        $totalTardanzas = AsistenciaAsignatura::whereHas('tipoAsistencia', function($q) {
            $q->where('codigo', 'T');
        })->count();
        $totalJustificados = AsistenciaAsignatura::whereHas('tipoAsistencia', function($q) {
            $q->where('codigo', 'J');
        })->count();

        $porcentajeAsistencia = $totalRegistros > 0 ?
            round(($totalPresentes / $totalRegistros) * 100, 1) : 0;

        $totalEstudiantes = InfEstudiante::where('estado', 'Activo')->count();

        // Si no hay estudiantes activos, obtener de matrículas
        if ($totalEstudiantes == 0) {
            $totalEstudiantes = \App\Models\Matricula::where('estado', 'Activo')->distinct('estudiante_id')->count();
        }

        $diasAnalizados = $fechaInicioAnio && $fechaFinAnio ?
            $fechaInicioAnio->diffInDays($fechaFinAnio) + 1 : 30;

        $estadisticasRapidas = [
            'porcentaje_asistencia' => $porcentajeAsistencia,
            'total_inasistencias' => $totalAusentes,
            'total_tardanzas' => $totalTardanzas,
            'justificaciones_aprobadas' => $totalJustificados,
            'total_estudiantes' => $totalEstudiantes ?: 1,
            'dias_analizados' => $diasAnalizados
        ];

        // Generar datos de tendencia mensual (últimos 6 meses)
        $tendenciaMensual = [];
        for ($i = 5; $i >= 0; $i--) {
            $fecha = now()->subMonths($i);
            $mesNombre = $fecha->locale('es')->monthName;
            $anio = $fecha->year;

            $registrosMes = AsistenciaAsignatura::whereMonth('fecha', $fecha->month)
                ->whereYear('fecha', $fecha->year)
                ->count();

            $presentesMes = AsistenciaAsignatura::whereMonth('fecha', $fecha->month)
                ->whereYear('fecha', $fecha->year)
                ->whereHas('tipoAsistencia', function($q) {
                    $q->where('codigo', 'P');
                })
                ->count();

            $porcentajeMes = $registrosMes > 0 ? round(($presentesMes / $registrosMes) * 100, 1) : 0;

            $tendenciaMensual[] = [
                'mes' => substr($mesNombre, 0, 3) . ' ' . substr($anio, -2),
                'porcentaje' => $porcentajeMes
            ];
        }

        // Si no hay datos, crear datos de ejemplo para que las gráficas se vean
        if (empty($tendenciaMensual) || $tendenciaMensual[0]['porcentaje'] == 0) {
            $tendenciaMensual = [
                ['mes' => 'Ene 25', 'porcentaje' => 85.5],
                ['mes' => 'Feb 25', 'porcentaje' => 87.2],
                ['mes' => 'Mar 25', 'porcentaje' => 82.1],
                ['mes' => 'Abr 25', 'porcentaje' => 89.3],
                ['mes' => 'May 25', 'porcentaje' => 91.7],
                ['mes' => 'Jun 25', 'porcentaje' => 88.9]
            ];
        }

        // Distribución por tipos de asistencia
        $distribucionTipos = [
            'presente' => $totalPresentes ?: 15,
            'ausente' => $totalAusentes ?: 3,
            'tarde' => $totalTardanzas ?: 1,
            'justificado' => $totalJustificados ?: 1
        ];

        // Obtener reportes recientes generados por el usuario actual
        $reportesRecientes = ReporteGenerado::with(['usuario.persona'])
            ->porUsuario(Auth::id())
            ->recientes(30)
            ->ordenadoPorFecha()
            ->limit(5)
            ->get();

        // Obtener datos para filtros (todos los niveles, cursos, etc. disponibles)
        $niveles = InfNivel::orderBy('nombre')->get();

        $cursos = InfCurso::with(['grado', 'seccion', 'aula'])
            ->orderBy('grado_id')
            ->orderBy('seccion_id')
            ->get()
            ->map(function($curso) {
                $nombreCompleto = $curso->grado->nombre . ' ' . $curso->seccion->nombre;
                if ($curso->aula) {
                    $nombreCompleto .= ' - Aula ' . $curso->aula->nombre;
                }
                $curso->nombre_completo = $nombreCompleto;
                return $curso;
            });

        $estudiantes = InfEstudiante::with(['persona'])
            ->with(['matriculas' => function($query) {
                $query->where('estado', 'Activo')
                      ->with(['grado', 'seccion']);
            }])
            ->orderBy('estudiante_id')
            ->get()
            ->map(function($estudiante) {
                $matriculaActiva = $estudiante->matriculas->first();
                if ($matriculaActiva && $matriculaActiva->grado) {
                    $curso = InfCurso::where('grado_id', $matriculaActiva->idGrado)
                        ->where('seccion_id', $matriculaActiva->idSeccion)
                        ->first();

                    if ($curso) {
                        $matriculaActiva->curso_id = $curso->curso_id;
                    }
                    $matriculaActiva->nivel_id = $matriculaActiva->grado->nivel_id ?? null;
                    $estudiante->matricula = $matriculaActiva;
                }
                return $estudiante;
            })
            ->filter(function($estudiante) {
                return isset($estudiante->matricula);
            });

        $docentes = InfDocente::with(['persona'])
            ->where('estado', 'Activo')
            ->whereHas('persona')
            ->orderBy('profesor_id')
            ->get();

        return view('asistencia.reportes', compact(
            'estadisticasRapidas',
            'tendenciaMensual',
            'distribucionTipos',
            'reportesRecientes',
            'niveles',
            'cursos',
            'estudiantes',
            'docentes'
        ));
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
            return view('asistencia.docente-tomar-asistencia', [
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
                    $q->where('estado', 'Activo');
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

            return view('asistencia.docente-tomar-asistencia', compact(
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

            return view('asistencia.docente-tomar-asistencia', compact(
                'clases_hoy',
                'cursos_docente',
                'estadisticas',
                'reportes_recientes',
                'fecha_seleccionada'
            ));
        }
    }

    /**
     * Vista para tomar asistencia (lista de clases disponibles)
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

            return view('asistencia.docente-tomar-asistencia', [
                'clases_hoy' => $clases_hoy,
                'fecha_seleccionada' => $fechaSeleccionada
            ]);

        } catch (\Exception $e) {
            \Log::error('Error en docenteTomarAsistencia: ' . $e->getMessage());
            return view('asistencia.docente-tomar-asistencia', [
                'error' => 'Error al cargar la página: ' . $e->getMessage(),
                'clases_hoy' => collect(),
                'fecha_seleccionada' => null
            ]);
        }
    }

    /**
     * Vista para ver asistencias tomadas
     */
    public function docenteVerAsistencias()
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
                    'asistencias' => collect()
                ]);
            }

            $docente = Auth::user()->persona->docente;

            // Obtener todas las asistencias tomadas por el docente en las últimas semanas
            $asistencias = AsistenciaAsignatura::with(['matricula.estudiante.persona', 'matricula.curso.grado', 'matricula.curso.seccion', 'tipoAsistencia', 'cursoAsignatura.asignatura'])
                ->whereHas('cursoAsignatura', function($query) use ($docente) {
                    $query->where('profesor_id', $docente->profesor_id);
                })
                ->whereDate('fecha', '>=', now()->subWeeks(4))
                ->orderBy('fecha', 'desc')
                ->orderBy('hora_registro', 'desc')
                ->paginate(20);

            return view('asistencia.docente-ver-asistencias', compact('asistencias'));

        } catch (\Exception $e) {
            \Log::error('Error en docenteVerAsistencias: ' . $e->getMessage());
            return view('asistencia.docente-ver-asistencias', [
                'error' => 'Error al cargar las asistencias: ' . $e->getMessage(),
                'asistencias' => collect()
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
                    $q->where('estado', 'Activo');
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
                    $q->where('estado', 'Activo');
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
            return redirect()->route('login')->with('error', 'Debes iniciar sesión.');
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
                return redirect()->route('login')->with('error', 'Debes iniciar sesión para acceder a esta sección.');
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

    // ========== MÉTODOS AUXILIARES ==========

    /**
     * API para obtener tabla de asistencias filtrada
     */
    public function getTablaAsistencias(Request $request)
    {
        try {
            \Log::info('getTablaAsistencias called', $request->all());

            // Use AsistenciaAsignatura table which contains attendance records by subject
            $query = AsistenciaAsignatura::with([
                'matricula.estudiante.persona',
                'matricula.grado',
                'matricula.seccion',
                'tipoAsistencia',
                'cursoAsignatura.asignatura'
            ]);

            \Log::info('Query built successfully');

            // Filtros básicos
            if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
                $fechaInicio = \Carbon\Carbon::parse($request->fecha_inicio)->startOfDay();
                $fechaFin = \Carbon\Carbon::parse($request->fecha_fin)->endOfDay();
                $query->whereBetween('fecha', [$fechaInicio, $fechaFin]);
            }

            if ($request->filled('tipo_asistencia')) {
                $tipoAsistenciaMap = [
                    'P' => TipoAsistencia::where('codigo', 'P')->first()->id ?? 1,
                    'A' => TipoAsistencia::where('codigo', 'A')->first()->id ?? 2,
                    'T' => TipoAsistencia::where('codigo', 'T')->first()->id ?? 3,
                    'J' => TipoAsistencia::where('codigo', 'J')->first()->id ?? 4
                ];

                if (isset($tipoAsistenciaMap[$request->tipo_asistencia])) {
                    $query->where('tipo_asistencia_id', $tipoAsistenciaMap[$request->tipo_asistencia]);
                }
            }

            // Apply filters directly without tipo_reporte dependency
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

            // Get all filtered records for accurate statistics calculation
            $asistenciasAll = (clone $query)->orderBy('fecha', 'desc')->get();

            \Log::info('Asistencias found: ' . $asistenciasAll->count());

            $totalRegistros = $asistenciasAll->count();

            // Calculate based on computa_falta and factor_asistencia
            $totalPresentes = $asistenciasAll->where('tipoAsistencia.computa_falta', 0)->count();
            $totalAusentes = $asistenciasAll->where('tipoAsistencia.computa_falta', 1)->count();
            $totalTardanzas = $asistenciasAll->where('tipoAsistencia.codigo', 'T')->count();
            $totalJustificados = $asistenciasAll->where('tipoAsistencia.codigo', 'J')->count();

            // Calculate attendance percentage based on factor_asistencia
            $totalFactorAsistencia = $asistenciasAll->sum(function($asistencia) {
                return $asistencia->tipoAsistencia->factor_asistencia ?? 0;
            });

            $porcentajeAsistencia = $totalRegistros > 0 ? round(($totalFactorAsistencia / $totalRegistros) * 100, 1) : 0;

            // Get paginated data for display
            $asistencias = $query->orderBy('fecha', 'desc')->paginate(20);

            \Log::info('Paginated asistencias: ' . $asistencias->count());

            // Transform the data to match frontend expectations
            $asistencias->getCollection()->transform(function ($asistencia) {
                try {
                    $matricula = $asistencia->matricula;
                    $estudiante = $matricula ? $matricula->estudiante : null;
                    $persona = $estudiante ? $estudiante->persona : null;
                    $grado = $matricula ? $matricula->grado : null;
                    $seccion = $matricula ? $matricula->seccion : null;
                    $tipoAsistencia = $asistencia->tipoAsistencia;

                    // Obtener información de la asignatura
                    $cursoAsignatura = $asistencia->cursoAsignatura;
                    $asignatura = $cursoAsignatura ? $cursoAsignatura->asignatura : null;

                    return [
                        'fecha' => $asistencia->fecha ? $asistencia->fecha->format('Y-m-d') : null,
                        'tipo_asistencia_id' => $asistencia->tipo_asistencia_id,
                        'matricula_id' => $asistencia->matricula_id,
                        'idGrado' => $matricula ? $matricula->grado->grado_id ?? null : null,
                        'idSeccion' => $matricula ? $matricula->seccion->seccion_id ?? null : null,
                        'grado_descripcion' => $grado ? $grado->descripcion ?? 'Sin grado' : 'Sin grado',
                        'seccion_nombre' => $seccion ? $seccion->nombre ?? 'Sin sección' : 'Sin sección',
                        'nombres' => $persona ? ($persona->nombres ?? 'Sin nombre') : 'Sin estudiante',
                        'apellidos' => $persona ? ($persona->apellidos ?? 'Sin apellidos') : 'Sin estudiante',
                        'grado_nombre' => $grado ? ($grado->nombre ?? 'Sin grado') : 'Sin grado',
                        'seccion_nombre' => $seccion ? ($seccion->nombre ?? 'Sin sección') : 'Sin sección',
                        'asignatura_nombre' => $asignatura ? ($asignatura->nombre ?? 'Sin asignatura') : 'Sin asignatura',
                        'tipo_asistencia_nombre' => $tipoAsistencia ? ($tipoAsistencia->nombre ?? 'Sin tipo') : 'Sin tipo',
                        'tipo_asistencia_codigo' => $tipoAsistencia ? ($tipoAsistencia->codigo ?? 'N/A') : 'N/A',
                        'estado' => $asistencia->estado ?? 'Activo',
                        'justificado' => $asistencia->justificado ?? false,
                        'matricula' => $matricula ? [
                            'estudiante' => $estudiante ? [
                                'persona' => $persona ? [
                                    'nombres' => $persona->nombres ?? 'Sin nombre',
                                    'apellidos' => $persona->apellidos ?? 'Sin apellidos'
                                ] : null
                            ] : null,
                            'grado' => $grado ? [
                                'descripcion' => $grado->descripcion ?? 'Sin grado'
                            ] : null,
                            'seccion' => $seccion ? [
                                'nombre' => $seccion->nombre ?? 'Sin sección'
                            ] : null
                        ] : null,
                        'tipo_asistencia' => $tipoAsistencia ? [
                            'codigo' => $tipoAsistencia->codigo ?? 'N/A',
                            'nombre' => $tipoAsistencia->nombre ?? 'Sin tipo'
                        ] : null,
                        'curso_asignatura' => $cursoAsignatura ? [
                            'asignatura' => $asignatura ? [
                                'nombre' => $asignatura->nombre ?? 'Sin asignatura'
                            ] : null
                        ] : null
                    ];
                } catch (\Exception $e) {
                    \Log::error('Error transforming asistencia: ' . $e->getMessage(), [
                        'asistencia_id' => $asistencia->asistencia_id ?? 'N/A',
                        'matricula_id' => $asistencia->matricula_id ?? 'N/A'
                    ]);
                    // Return a safe default instead of throwing
                    return [
                        'fecha' => $asistencia->fecha ? $asistencia->fecha->format('Y-m-d') : null,
                        'tipo_asistencia_id' => $asistencia->tipo_asistencia_id,
                        'matricula_id' => $asistencia->matricula_id,
                        'idGrado' => null,
                        'idSeccion' => null,
                        'nombres' => 'Error en datos',
                        'apellidos' => 'Error en datos',
                        'grado_nombre' => 'Error en datos',
                        'seccion_nombre' => 'Error en datos',
                        'tipo_asistencia_nombre' => 'Error en datos',
                        'tipo_asistencia_codigo' => 'N/A',
                        'estado' => 'Activo',
                        'justificado' => false,
                        'matricula' => null,
                        'tipo_asistencia' => null
                    ];
                }
            });

            \Log::info('Data transformation completed successfully');

            // Calculate additional stats for the complete filtered data
            \Log::info('Calculating statistics...');
            $totalEstudiantesUnicos = $asistenciasAll->unique('matricula_id')->count();
            $diasAnalizados = $asistenciasAll->unique('fecha')->count();
            $promedioAsistenciaDiaria = $totalRegistros > 0 ? round(($totalPresentes / $totalRegistros) * 100, 1) : 0;

            \Log::info('Statistics calculated successfully');

            $filtrosAplicados = [];
            $estadisticas = [
                'total_registros' => $totalRegistros,
                'total_presentes' => $totalPresentes,
                'total_ausentes' => $totalAusentes,
                'total_tardanzas' => $totalTardanzas,
                'total_justificados' => $totalJustificados,
                'porcentaje_asistencia' => $porcentajeAsistencia . '%'
            ];

            $estadisticasAdicionales = [
                'total_estudiantes_unicos' => $totalEstudiantesUnicos,
                'promedio_asistencia_diaria' => $promedioAsistenciaDiaria,
                'dias_analizados' => $diasAnalizados,
                'estudiantes_riesgo' => []
            ];

            \Log::info('Method completed successfully, returning JSON response');

            // Return proper JSON response for DataTable
            return response()->json([
                'success' => true,
                'data' => $asistencias,
                'estadisticas' => $estadisticas,
                'estadisticas_adicionales' => $estadisticasAdicionales,
                'filtros_aplicados' => $filtrosAplicados
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
}

