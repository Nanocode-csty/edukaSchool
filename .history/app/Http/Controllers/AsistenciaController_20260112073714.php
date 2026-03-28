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

            // Solo matrículas activas (opcional para evitar problemas con datos históricos)
            // $query->whereHas('matricula', function($q) {
            //     $q->where('estado', 'Activo');
            // });

            // Si no hay filtros aplicados, mostrar asistencias recientes por defecto
            if (!$request->filled('fecha_inicio') && !$request->filled('fecha_fin') &&
                !$request->filled('tipo_asistencia') && !$request->filled('curso_id') &&
                !$request->filled('estudiante_id') && !$request->filled('docente_id') &&
                !$request->filled('nivel_id') && !$request->filled('grado_id') &&
                !$request->filled('seccion_id') && !$request->filled('asignatura_id')) {
                // Mostrar asistencias de los últimos 30 días por defecto
                $query->where('fecha', '>=', now()->subDays(30));
            }

            // Calcular estadísticas del conjunto completo de datos filtrados (antes de paginar)
            $statsQuery = AsistenciaAsignatura::select([
                'asistenciasasignatura.asistencia_asignatura_id',
                'asistenciasasignatura.fecha',
                'asistenciasasignatura.tipo_asistencia_id',
                'asistenciasasignatura.matricula_id',
                'asistenciasasignatura.estado',
                'asistenciasasignatura.justificacion',
                'tiposasistencia.nombre as tipo_asistencia_nombre',
                'tiposasistencia.codigo as tipo_asistencia_codigo',
                'tiposasistencia.computa_falta',
                'tiposasistencia.factor_asistencia'
            ])
            ->join('tiposasistencia', 'asistenciasasignatura.tipo_asistencia_id', '=', 'tiposasistencia.tipo_asistencia_id')
            ->join('matriculas', 'asistenciasasignatura.matricula_id', '=', 'matriculas.matricula_id')
            ->join('estudiantes', 'matriculas.estudiante_id', '=', 'estudiantes.estudiante_id')
            ->join('personas', 'estudiantes.persona_id', '=', 'personas.id_persona')
            ->join('grados', 'matriculas.idGrado', '=', 'grados.grado_id')
            ->join('secciones', 'matriculas.idSeccion', '=', 'secciones.seccion_id')
            ->join('cursoasignaturas', 'asistenciasasignatura.curso_asignatura_id', '=', 'cursoasignaturas.curso_asignatura_id')
            ->join('asignaturas', 'cursoasignaturas.asignatura_id', '=', 'asignaturas.asignatura_id');

            // Aplicar los mismos filtros que la query principal
            if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
                $statsQuery->whereBetween('asistenciasasignatura.fecha', [$request->fecha_inicio, $request->fecha_fin]);
            } elseif ($request->filled('fecha_inicio')) {
                $statsQuery->where('asistenciasasignatura.fecha', '>=', $request->fecha_inicio);
            } elseif ($request->filled('fecha_fin')) {
                $statsQuery->where('asistenciasasignatura.fecha', '<=', $request->fecha_fin);
            }

            if ($request->filled('tipo_asistencia')) {
                $tipoAsistencia = TipoAsistencia::where('codigo', $request->tipo_asistencia)->first();
                if ($tipoAsistencia) {
                    $statsQuery->where('asistenciasasignatura.tipo_asistencia_id', $tipoAsistencia->tipo_asistencia_id);
                }
            }

            if ($request->filled('curso_id')) {
                $curso = InfCurso::find($request->curso_id);
                if ($curso) {
                    $statsQuery->where('matriculas.idGrado', $curso->grado_id)
                              ->where('matriculas.idSeccion', $curso->seccion_id);
                }
            }

            if ($request->filled('estudiante_id')) {
                $statsQuery->where('estudiantes.estudiante_id', $request->estudiante_id);
            }

            if ($request->filled('docente_id')) {
                $statsQuery->where('cursoasignaturas.profesor_id', $request->docente_id);
            }

            if ($request->filled('nivel_id')) {
                $statsQuery->where('grados.nivel_id', $request->nivel_id);
            }

            if ($request->filled('grado_id')) {
                $statsQuery->where('matriculas.idGrado', $request->grado_id);
            }

            if ($request->filled('seccion_id')) {
                $statsQuery->where('matriculas.idSeccion', $request->seccion_id);
            }

            if ($request->filled('asignatura_id')) {
                $statsQuery->where('cursoasignaturas.asignatura_id', $request->asignatura_id);
            }

            // Aplicar filtro por defecto si no hay filtros
            if (!$request->filled('fecha_inicio') && !$request->filled('fecha_fin') &&
                !$request->filled('tipo_asistencia') && !$request->filled('curso_id') &&
                !$request->filled('estudiante_id') && !$request->filled('docente_id') &&
                !$request->filled('nivel_id') && !$request->filled('grado_id') &&
                !$request->filled('seccion_id') && !$request->filled('asignatura_id')) {
                $statsQuery->where('asistenciasasignatura.fecha', '>=', now()->subDays(30));
            }

            $stats = $statsQuery->selectRaw('
                COUNT(*) as total_registros,
                SUM(CASE WHEN tiposasistencia.computa_falta = 0 THEN 1 ELSE 0 END) as total_presentes,
                SUM(CASE WHEN tiposasistencia.computa_falta = 1 THEN 1 ELSE 0 END) as total_ausentes
            ')->first();

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
}
