<?php

namespace App\Http\Controllers;

use App\Models\AsistenciaAsignatura;
use App\Models\CursoAsignatura;
use App\Models\InfCurso;
use App\Models\Matricula;
use App\Models\SesionClase;
use App\Models\TipoAsistencia;
use App\Services\AsistenciaService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AsistenciaController extends Controller
{
    protected $asistenciaService;

    public function __construct(AsistenciaService $asistenciaService)
    {
        $this->asistenciaService = $asistenciaService;
    }

    public function index(Request $request)
    {
        $fecha = $request->get('fecha', Carbon::today()->format('Y-m-d'));
        $fechaHoy = Carbon::parse($fecha);
        // dd($fechaHoy);
        $user = auth()->user();
        $docente = $user ? $user->docente : null;

        if (! $docente) {
            // No es docente: pasamos colecciones vacías y un mensaje en vez de abortar o hacer back(),
            // así la vista puede renderizarse para otros roles (ej. Admin) sin lanzar excepciones.
            $sesiones = collect();
            $estadisticas = [
                'total_registros' => 0,
                'presentes' => 0,
                'ausentes' => 0,
                'tardanzas' => 0,
                'porcentaje_asistencia' => 0,
            ];

            return view('asistencia.index', compact('sesiones', 'fecha', 'estadisticas'))
                ->with('info', 'No se encontró vinculación de docente para el usuario autenticado.');
        }

        $sesiones = SesionClase::with(['cursoAsignatura.curso.grado', 'cursoAsignatura.curso.seccion', 'cursoAsignatura.asignatura'])
            ->whereHas('cursoAsignatura', function ($q) use ($docente) {
                $q->where('profesor_id', $docente->profesor_id);
            })
            ->whereDate('fecha', $fechaHoy)
            ->orderBy('hora_inicio')
            ->get();

        // Calcular estadísticas rápidas
        $estadisticas = $this->calcularEstadisticasDia($docente->profesor_id, $fecha);

        return view('asistencia.index', compact('sesiones', 'fecha', 'estadisticas'));
    }

    public function registrarAsignatura($cursoAsignaturaId, $fecha = null)
    {
        $fecha = $fecha ? Carbon::parse($fecha) : now();
        $fechaStr = $fecha->format('Y-m-d');

        $cursoAsignatura = CursoAsignatura::with([
            'curso.grado',
            'curso.seccion',
            'asignatura',
        ])->findOrFail($cursoAsignaturaId);

        // Verificar permisos
        $user = auth()->user();
        $docente = $user ? $user->docente : null;
        if (! $docente) {
            abort(403, 'No tienes permiso para registrar asistencia en esta asignatura');
        }

        if ($cursoAsignatura->profesor_id != ($docente->profesor_id ?? null)) {
            abort(403, 'No tienes permiso para registrar asistencia en esta asignatura');
        }

        $matriculas = Matricula::with(['estudiante', 'estudiante.representante'])
            ->join('estudiantes', 'matriculas.estudiante_id', '=', 'estudiantes.estudiante_id')
            ->where('curso_id', $cursoAsignatura->curso_id)
            ->where('anio_academico', date('Y'))
            ->where('matriculas.estado', 'Matriculado')
            ->orderBy('estudiantes.apellidos', 'asc') // ahora funciona
            ->select('matriculas.*') // importante para no traer columnas repetidas
            ->get();

        $asistencias = AsistenciaAsignatura::where('curso_asignatura_id', $cursoAsignaturaId)
            ->where('fecha', $fechaStr)
            ->get()
            ->keyBy('matricula_id');

        // Buscar justificaciones aprobadas para esta fecha y estudiantes del curso
        $justificacionesAprobadas = \App\Models\JustificacionAsistencia::where('estado', 'aprobado')
            ->where('fecha', $fechaStr)
            ->whereHas('matricula', function ($query) use ($cursoAsignatura) {
                $query->where('curso_id', $cursoAsignatura->curso_id);
            })
            ->with(['matricula', 'usuarioRevisor'])
            ->get()
            ->keyBy('matricula_id');

        // Si hay justificaciones aprobadas pero no hay asistencia registrada, crear asistencia automática
        if ($justificacionesAprobadas->isNotEmpty()) {
            $tipoJustificada = TipoAsistencia::where('codigo', 'J')->first();
            if ($tipoJustificada) {
                foreach ($justificacionesAprobadas as $justificacion) {
                    // Solo crear si no existe asistencia para esta matrícula y fecha
                    if (!$asistencias->has($justificacion->matricula_id)) {
                        $nuevaAsistencia = AsistenciaAsignatura::create([
                            'matricula_id' => $justificacion->matricula_id,
                            'curso_asignatura_id' => $cursoAsignaturaId,
                            'fecha' => $fechaStr,
                            'tipo_asistencia_id' => $tipoJustificada->tipo_asistencia_id,
                            'justificacion' => $justificacion->descripcion,
                            'estado' => 'Justificada',
                            'usuario_registro' => auth()->id(),
                            'hora_registro' => now(),
                        ]);
                        $asistencias->put($justificacion->matricula_id, $nuevaAsistencia);
                    }
                }
            }
        }

        $tiposAsistencia = TipoAsistencia::where('activo', 1)->get();

        // Historial de asistencia de los últimos 30 días
        $historialAsistencias = $this->obtenerHistorial($cursoAsignaturaId, $fechaStr);

        return view('asistencia.asignatura', compact(
            'cursoAsignatura',
            'fechaStr',
            'matriculas',
            'asistencias',
            'tiposAsistencia',
            'historialAsistencias'
        ));
    }

    public function guardarAsignatura(Request $request)
    {
        \Illuminate\Support\Facades\Log::info('guardarAsignatura payload', $request->all());

        // Validaciones adicionales antes del procesamiento
        $fecha = $request->input('fecha');
        $cursoAsignaturaId = $request->input('curso_asignatura_id');

        // TEMPORALMENTE COMENTADO: Validar que no sea fecha futura
        // if (Carbon::parse($fecha)->isFuture()) {
        //     return back()->with('error', 'No se puede registrar asistencia para fechas futuras.');
        // }

        // TEMPORALMENTE COMENTADO: Validar que no haya pasado demasiado tiempo desde la clase (máximo 7 días)
        // $diasDiferencia = Carbon::parse($fecha)->diffInDays(now());
        // if ($diasDiferencia > 7) {
        //     return back()->with('error', 'No se puede registrar asistencia para clases con más de 7 días de antigüedad.');
        // }

        // TEMPORALMENTE COMENTADO: Validar que haya una sesión programada para esa fecha
        // $sesionExiste = SesionClase::where('curso_asignatura_id', $cursoAsignaturaId)
        //     ->where('fecha', $fecha)
        //     ->exists();

        // if (!$sesionExiste) {
        //     return back()->with('error', 'No hay sesión programada para esta asignatura en la fecha seleccionada.');
        // }

        // TEMPORALMENTE COMENTADO: Validar que la asistencia no haya sido registrada previamente (evitar duplicados)
        // $asistenciaYaRegistrada = AsistenciaAsignatura::where('curso_asignatura_id', $cursoAsignaturaId)
        //     ->where('fecha', $fecha)
        //     ->exists();

        // if ($asistenciaYaRegistrada) {
        //     return back()->with('error', 'La asistencia para esta sesión ya ha sido registrada anteriormente.');
        // }

        // Pre-process asistencias: normalize empty strings to null and reindex
        $asistenciasRaw = $request->input('asistencias', []);
        $normalized = [];
        foreach ($asistenciasRaw as $row) {
            // if matricula_id missing or empty, skip
            if (empty($row['matricula_id'])) {
                continue;
            }

            // normalize empty tipo_asistencia_id to null
            $row['tipo_asistencia_id'] = isset($row['tipo_asistencia_id']) && $row['tipo_asistencia_id'] !== '' ? $row['tipo_asistencia_id'] : null;
            $normalized[] = $row;
        }
        // replace request input for validation
        $request->merge(['asistencias' => $normalized]);

        $request->validate([
            'fecha' => 'required|date',
            // tabla correcta: cursoasignaturas
            'curso_asignatura_id' => 'required|exists:cursoasignaturas,curso_asignatura_id',
            'asistencias' => 'required|array',
            // tabla correcta: matriculas
            'asistencias.*.matricula_id' => 'required|exists:matriculas,matricula_id',
            // tipo_asistencia_id puede ser nullable en la validación, lo filtramos al guardar
            'asistencias.*.tipo_asistencia_id' => 'nullable|exists:tiposasistencia,tipo_asistencia_id',
        ]);

        DB::beginTransaction();
        try {
            $fecha = $request->input('fecha');
            $cursoAsignaturaId = $request->input('curso_asignatura_id');
            $asistencias = $request->input('asistencias', []);

            $registrosCreados = 0;
            $registrosActualizados = 0;
            $alertasEnviadas = [];

            foreach ($asistencias as $item) {
                // Protecciones: asegurar que las referencias existen en BD antes de crear/actualizar
                // Usar empty() para evitar tratar cadenas vacías ('') como valores válidos
                if (empty($item['matricula_id']) || empty($item['tipo_asistencia_id'])) {
                    continue;
                }

                // Normalizar tipo_asistencia_id a entero
                $item['tipo_asistencia_id'] = (int) $item['tipo_asistencia_id'];

                // Determinar si el tipo de asistencia requiere justificación
                $tipoAsistencia = TipoAsistencia::find($item['tipo_asistencia_id']);
                $requiereJustificacion = $tipoAsistencia && $tipoAsistencia->codigo !== 'A'; // Solo 'A' (Presente) no requiere justificación

                // Validar justificación cuando sea requerida
                if ($requiereJustificacion && empty(trim($item['justificacion'] ?? ''))) {
                    return back()->with('error', 'Debe proporcionar una justificación para el tipo de asistencia seleccionado.');
                }

                // Preparar datos para actualizar/crear
                $datosActualizar = [
                    'tipo_asistencia_id' => $item['tipo_asistencia_id'],
                    'hora_registro' => now(),
                    'usuario_registro' => auth()->id(),
                    'estado' => 'Registrada',
                ];

                // Solo incluir justificación si el tipo de asistencia la requiere
                if ($requiereJustificacion) {
                    $datosActualizar['justificacion'] = trim($item['justificacion']);
                } else {
                    // Si es presente, limpiar cualquier justificación anterior
                    $datosActualizar['justificacion'] = null;
                }

                $asistencia = AsistenciaAsignatura::updateOrCreate(
                    [
                        'matricula_id' => $item['matricula_id'],
                        'curso_asignatura_id' => $cursoAsignaturaId,
                        'fecha' => $fecha,
                    ],
                    $datosActualizar
                );

                // Additional safeguard: ensure Present attendance never has justification
                if ($tipoAsistencia && $tipoAsistencia->codigo === 'A' && $asistencia->justificacion) {
                    $asistencia->update(['justificacion' => null]);
                }

                if ($asistencia->wasRecentlyCreated) {
                    $registrosCreados++;
                } else {
                    $registrosActualizados++;
                }

                // Sistema de alertas automáticas usando el servicio
                $alertasEnviadas = array_merge($alertasEnviadas, $this->asistenciaService->verificarAlertas($asistencia));
            }

            // Actualizar resumen diario
            $this->actualizarResumenDiario($cursoAsignaturaId, $fecha);

            DB::commit();

            $mensaje = "Asistencias guardadas: {$registrosCreados} nuevas, {$registrosActualizados} actualizadas.";
            if (! empty($alertasEnviadas)) {
                $mensaje .= ' Se enviaron '.count($alertasEnviadas).' alertas a representantes.';
            }

            return redirect()->route('asistencia.index')
                ->with('success', $mensaje);
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Error al guardar asistencias: '.$e->getMessage());
        }
    }

    public function detalleEstudiante($matriculaId, Request $request)
    {
        $matricula = Matricula::with('estudiante')->findOrFail($matriculaId);
        $estudiante = $matricula->estudiante;

        // Obtener todas las matrículas activas del estudiante
        $todasMatriculas = Matricula::where('estudiante_id', $estudiante->estudiante_id)
            ->whereIn('estado', ['Matriculado', 'Pre-inscrito'])
            ->with(['grado.nivel', 'seccion', 'curso.anoLectivo'])
            ->orderBy('fecha_matricula', 'desc')
            ->get();

        // Determinar qué matrículas usar (filtrar por curso si se especifica)
        $cursoFiltro = $request->get('curso_id');
        $matriculasFiltradas = $cursoFiltro
            ? $todasMatriculas->where('curso_id', $cursoFiltro)
            : $todasMatriculas;

        $fechaInicio = $request->get('fecha_inicio')
            ? Carbon::parse($request->get('fecha_inicio'))
            : Carbon::now()->startOfYear();
        $fechaFin = $request->get('fecha_fin')
            ? Carbon::parse($request->get('fecha_fin'))
            : Carbon::now()->addDays(30);

        // Obtener asistencias de todas las matrículas filtradas
        $asistencias = AsistenciaAsignatura::with(['tipoAsistencia', 'cursoAsignatura.asignatura', 'matricula'])
            ->whereIn('matricula_id', $matriculasFiltradas->pluck('matricula_id'))
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->orderBy('fecha', 'asc') // Changed to ascending for proper chart order
            ->get();

        // Calcular estadísticas
        $estadisticas = [
            'total' => $asistencias->count(),
            'por_tipo' => $asistencias->groupBy('tipo_asistencia_id')->map->count(),
            'porcentaje_asistencia' => $this->calcularPorcentajeAsistencia($asistencias),
            'racha_actual' => $this->calcularRachaAsistenciaMultiple($matriculasFiltradas->pluck('matricula_id')),
            'tendencia' => $this->calcularTendencia($asistencias),
        ];

        // Datos para gráficos
        $datosGrafico = $this->prepararDatosGrafico($asistencias);

        // Estadísticas por asignatura
        $estadisticasPorAsignatura = $asistencias->groupBy('cursoAsignatura.asignatura.nombre')
            ->map(function ($asistenciasAsignatura) {
                return [
                    'total' => $asistenciasAsignatura->count(),
                    'presentes' => $asistenciasAsignatura->filter(function ($a) {
                        return optional($a->tipoAsistencia)->codigo === 'A';
                    })->count(),
                    'ausencias' => $asistenciasAsignatura->filter(function ($a) {
                        return optional($a->tipoAsistencia)->codigo === 'F';
                    })->count(),
                    'tardanzas' => $asistenciasAsignatura->filter(function ($a) {
                        return optional($a->tipoAsistencia)->codigo === 'T';
                    })->count(),
                    'justificadas' => $asistenciasAsignatura->filter(function ($a) {
                        return optional($a->tipoAsistencia)->codigo === 'J';
                    })->count(),
                    'porcentaje' => $asistenciasAsignatura->count() > 0
                        ? round(($asistenciasAsignatura->filter(function ($a) {
                            return !optional($a->tipoAsistencia)->computa_falta;
                        })->count() / $asistenciasAsignatura->count()) * 100, 1)
                        : 0,
                ];
            });

        return view('asistencia.reporte-estudiante', compact(
            'matricula',
            'estudiante',
            'todasMatriculas',
            'matriculasFiltradas',
            'cursoFiltro',
            'asistencias',
            'estadisticas',
            'datosGrafico',
            'estadisticasPorAsignatura',
            'fechaInicio',
            'fechaFin'
        ));
    }

    public function justificarInasistencia(Request $request)
    {
        $request->validate([
            'asistencia_id' => 'required|exists:asistenciasasignatura,asistencia_asignatura_id',
            'justificacion' => 'required|string|min:10',
            'documento' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        ]);

        $asistencia = AsistenciaAsignatura::findOrFail($request->asistencia_id);

        // Verificar permisos
        $this->authorize('justificar', $asistencia);

        $asistencia->justificacion = $request->justificacion;
        $asistencia->estado = 'Justificada';
        $asistencia->fecha_justificacion = now();
        $asistencia->usuario_justificacion = auth()->id();

        if ($request->hasFile('documento')) {
            $fileName = time().'_'.$request->file('documento')->getClientOriginalName();
            $request->file('documento')->storeAs('public/justificaciones', $fileName);
            $asistencia->documento_justificacion = $fileName;
        }

        $asistencia->save();

        return back()->with('success', 'Inasistencia justificada correctamente');
    }

    public function verificarInasistencia(Request $request)
    {
        $request->validate([
            'asistencia_id' => 'required|exists:asistenciasasignatura,asistencia_asignatura_id',
            'observacion' => 'nullable|string',
        ]);

        $asistencia = AsistenciaAsignatura::findOrFail($request->asistencia_id);
        $asistencia->estado = 'Verificada';
        $asistencia->fecha_verificacion = now();
        $asistencia->usuario_verificacion = auth()->id();
        $asistencia->observacion_verificacion = $request->observacion;
        $asistencia->save();

        return back()->with('success', 'Inasistencia verificada correctamente');
    }

    // Métodos auxiliares privados

    private function calcularEstadisticasDia($profesorId, $fecha)
    {
        $asistencias = AsistenciaAsignatura::whereHas('cursoAsignatura', function ($q) use ($profesorId) {
            $q->where('profesor_id', $profesorId);
        })
            ->whereDate('fecha', $fecha)
            ->with('tipoAsistencia')
            ->get();

        // Usar optional para evitar errores cuando tipoAsistencia es null
        $presentes = $asistencias->filter(function ($a) {
            return optional($a->tipoAsistencia)->codigo === 'A';
        })->count();

        $ausentes = $asistencias->filter(function ($a) {
            return optional($a->tipoAsistencia)->codigo === 'F';
        })->count();

        $tardanzas = $asistencias->filter(function ($a) {
            return optional($a->tipoAsistencia)->codigo === 'T';
        })->count();

        $total = $asistencias->count();

        return [
            'total_registros' => $total,
            'presentes' => $presentes,
            'ausentes' => $ausentes,
            'tardanzas' => $tardanzas,
            'porcentaje_asistencia' => $total > 0 ? round(($presentes / $total) * 100, 1) : 0,
        ];
    }

    private function obtenerHistorial($cursoAsignaturaId, $fechaActual)
    {
        return AsistenciaAsignatura::where('curso_asignatura_id', $cursoAsignaturaId)
            ->where('fecha', '<', $fechaActual)
            ->where('fecha', '>=', Carbon::parse($fechaActual)->subDays(30))
            ->with(['tipoAsistencia', 'matricula.estudiante'])
            ->orderBy('fecha', 'desc')
            ->get()
            ->groupBy('matricula_id');
    }

    private function verificarAlertas($asistencia)
    {
        $alertas = [];
        $matricula = $asistencia->matricula;

        // Alerta por ausencias consecutivas
        $ausenciasConsecutivas = $this->contarAusenciasConsecutivas($asistencia->matricula_id);
        if ($ausenciasConsecutivas >= 3) {
            $alertas[] = $this->enviarAlertaRepresentante($matricula, 'ausencias_consecutivas', $ausenciasConsecutivas);
        }

        // Alerta por porcentaje bajo de asistencia
        $porcentaje = $this->calcularPorcentajeAsistenciaMes($asistencia->matricula_id);
        if ($porcentaje < 75) {
            $alertas[] = $this->enviarAlertaRepresentante($matricula, 'porcentaje_bajo', $porcentaje);
        }

        return array_filter($alertas);
    }

    private function contarAusenciasConsecutivas($matriculaId)
    {
        $asistencias = AsistenciaAsignatura::where('matricula_id', $matriculaId)
            ->orderBy('fecha', 'desc')
            ->take(10)
            ->get();

        $consecutivas = 0;
        foreach ($asistencias as $asistencia) {
            if (optional($asistencia->tipoAsistencia)->computa_falta) {
                $consecutivas++;
            } else {
                break;
            }
        }

        return $consecutivas;
    }

    private function calcularPorcentajeAsistenciaMes($matriculaId)
    {
        $asistencias = AsistenciaAsignatura::where('matricula_id', $matriculaId)
            ->whereMonth('fecha', Carbon::now()->month)
            ->get();

        if ($asistencias->isEmpty()) {
            return 100;
        }

        $presentes = $asistencias->filter(function ($a) {
            return ! optional($a->tipoAsistencia)->computa_falta;
        })->count();

        $total = $asistencias->count();

        return $total > 0 ? round(($presentes / $total) * 100, 1) : 0;
    }

    private function calcularPorcentajeAsistencia($asistencias)
    {
        if ($asistencias->isEmpty()) {
            return 0;
        }

        $presentes = $asistencias->filter(function ($a) {
            return ! optional($a->tipoAsistencia)->computa_falta;
        })->count();

        $total = $asistencias->count();

        return $total > 0 ? round(($presentes / $total) * 100, 1) : 0;
    }

    private function calcularRachaAsistencia($matriculaId)
    {
        $asistencias = AsistenciaAsignatura::where('matricula_id', $matriculaId)
            ->orderBy('fecha', 'desc')
            ->take(30)
            ->get();

        $racha = 0;
        foreach ($asistencias as $asistencia) {
            if (! optional($asistencia->tipoAsistencia)->computa_falta) {
                $racha++;
            } else {
                break;
            }
        }

        return $racha;
    }

    private function calcularRachaAsistenciaMultiple($matriculaIds)
    {
        $asistencias = AsistenciaAsignatura::whereIn('matricula_id', $matriculaIds)
            ->orderBy('fecha', 'desc')
            ->take(30)
            ->get();

        $racha = 0;
        foreach ($asistencias as $asistencia) {
            if (! optional($asistencia->tipoAsistencia)->computa_falta) {
                $racha++;
            } else {
                break;
            }
        }

        return $racha;
    }

    private function calcularTendencia($asistencias)
    {
        if ($asistencias->isEmpty()) {
            return 'estable';
        }

        // Ordenar asistencias por fecha
        $asistenciasOrdenadas = $asistencias->sortBy('fecha');

        // Dividir en dos períodos: primera mitad vs segunda mitad
        $totalRegistros = $asistenciasOrdenadas->count();
        $mitad = (int) ($totalRegistros / 2);

        $primerPeriodo = $asistenciasOrdenadas->take($mitad);
        $segundoPeriodo = $asistenciasOrdenadas->skip($mitad);

        // Calcular porcentaje de asistencia en cada período
        $porcentajePrimerPeriodo = $this->calcularPorcentajeAsistencia($primerPeriodo);
        $porcentajeSegundoPeriodo = $this->calcularPorcentajeAsistencia($segundoPeriodo);

        // Determinar tendencia
        $diferencia = $porcentajeSegundoPeriodo - $porcentajePrimerPeriodo;

        if ($diferencia > 5) {
            return 'mejorando';
        } elseif ($diferencia < -5) {
            return 'empeorando';
        } else {
            return 'estable';
        }
    }

    private function prepararDatosGrafico($asistencias)
    {
        // Preparar datos para el gráfico de tendencia
        $fechas = $asistencias->pluck('fecha')->unique()->sort()->values();

        // Formatear labels para mostrar solo día/mes (sin hora)
        $labelsFormateadas = $fechas->map(function ($fecha) {
            return Carbon::parse($fecha)->format('d/m');
        })->toArray();

        // Contar solo asistencias positivas (no ausencias) por fecha
        $asistenciasPositivasPorFecha = $asistencias->groupBy('fecha')->map(function ($asistenciasDia) {
            return $asistenciasDia->filter(function ($asistencia) {
                return $asistencia->tipoAsistencia && !$asistencia->tipoAsistencia->computa_falta;
            })->count();
        })->toArray();

        // Preparar datos para el gráfico de distribución por tipo
        $tiposAsistencia = $asistencias->groupBy('tipoAsistencia.nombre')->map->count()->toArray();

        return [
            'labels' => $labelsFormateadas,
            'datasets' => [
                'asistencias' => $asistenciasPositivasPorFecha,
                'por_tipo' => $tiposAsistencia,
            ],
        ];
    }

    private function actualizarResumenDiario($cursoAsignaturaId, $fecha)
    {
        // Implementar lógica para actualizar tabla de resúmenes diarios
        // Útil para reportes rápidos y dashboard
    }

    private function enviarAlertaRepresentante($matricula, $tipo, $valor)
    {
        // Implementar lógica de notificaciones (email, SMS, notificación en app)
        // Por ahora retornamos info de la alerta
        return [
            'tipo' => $tipo,
            'valor' => $valor,
            'matricula_id' => $matricula->matricula_id,
        ];
    }

    public function reporteCurso(Request $request, $cursoAsignaturaId)
    {
        $cursoAsignatura = CursoAsignatura::with([
            'asignatura',
            'curso.grado',
            'curso.seccion',
            'profesor',
        ])->findOrFail($cursoAsignaturaId);

        // Fechas del filtro
        $fechaInicio = $request->fecha_inicio
            ? Carbon::parse($request->fecha_inicio)
            : Carbon::now()->startOfMonth();

        $fechaFin = $request->fecha_fin
            ? Carbon::parse($request->fecha_fin)
            : Carbon::now()->endOfMonth();

        // Obtener estudiantes matriculados
        $matriculas = Matricula::where('curso_id', $cursoAsignatura->curso_id)
            ->where('estado', 'Matriculado')
            ->with('estudiante')
            ->get();

        // Tipos de asistencia
        $tipoPresente = TipoAsistencia::where('codigo', 'A')->first();
        $tipoAusente = TipoAsistencia::where('codigo', 'F')->first();
        $tipoTardanza = TipoAsistencia::where('codigo', 'T')->first();
        $tipoJustificada = TipoAsistencia::where('codigo', 'J')->first();

        // Estadísticas por estudiante
        $estudiantes = [];
        $totalPresentes = 0;
        $totalAusencias = 0;
        $totalTardanzas = 0;
        $totalJustificadas = 0;

        foreach ($matriculas as $matricula) {
            $asistencias = AsistenciaAsignatura::where('matricula_id', $matricula->matricula_id)
                ->where('curso_asignatura_id', $cursoAsignaturaId)
                ->whereBetween('fecha', [$fechaInicio, $fechaFin])
                ->get();

            $presentes = $asistencias->where('tipo_asistencia_id', $tipoPresente?->tipo_asistencia_id)->count();
            $ausencias = $asistencias->where('tipo_asistencia_id', $tipoAusente?->tipo_asistencia_id)->count();
            $tardanzas = $asistencias->where('tipo_asistencia_id', $tipoTardanza?->tipo_asistencia_id)->count();
            $justificadas = $asistencias->where('tipo_asistencia_id', $tipoJustificada?->tipo_asistencia_id)->count();

            $totalRegistros = $asistencias->count();
            $porcentaje = $totalRegistros > 0
                ? round(($presentes / $totalRegistros) * 100)
                : 0;

            $estudiantes[] = [
                'matricula_id' => $matricula->matricula_id,
                'estudiante' => $matricula->estudiante,
                'presentes' => $presentes,
                'ausencias' => $ausencias,
                'tardanzas' => $tardanzas,
                'justificadas' => $justificadas,
                'total' => $totalRegistros,
                'porcentaje' => $porcentaje,
            ];

            $totalPresentes += $presentes;
            $totalAusencias += $ausencias;
            $totalTardanzas += $tardanzas;
            $totalJustificadas += $justificadas;
        }

        // Estadísticas generales
        $totalRegistros = $totalPresentes + $totalAusencias + $totalTardanzas + $totalJustificadas;
        $porcentajeAsistencia = $totalRegistros > 0
            ? round(($totalPresentes / $totalRegistros) * 100)
            : 0;

        $estadisticas = [
            'total_estudiantes' => $matriculas->count(),
            'porcentaje_asistencia' => $porcentajeAsistencia,
            'total_ausencias' => $totalAusencias,
            'total_tardanzas' => $totalTardanzas,
        ];

        // Datos para gráficos
        $datosGrafico = [
            'labels' => [],
            'asistencias' => [],
            'presentes' => $totalPresentes,
            'ausencias' => $totalAusencias,
            'tardanzas' => $totalTardanzas,
            'justificadas' => $totalJustificadas,
        ];

        // Generar datos de tendencia por día
        $periodo = Carbon::parse($fechaInicio);
        while ($periodo->lte($fechaFin)) {
            $datosGrafico['labels'][] = $periodo->format('d/m');

            $asistenciasDia = AsistenciaAsignatura::where('curso_asignatura_id', $cursoAsignaturaId)
                ->whereDate('fecha', $periodo)
                ->where('tipo_asistencia_id', $tipoPresente?->tipo_asistencia_id)
                ->count();

            $datosGrafico['asistencias'][] = $asistenciasDia;

            $periodo->addDay();
        }

        return view('asistencia.reporte-curso', compact(
            'cursoAsignatura',
            'fechaInicio',
            'fechaFin',
            'estudiantes',
            'estadisticas',
            'datosGrafico'
        ));
    }

    /**
     * Muestra los estudiantes representados por el usuario actual (para rol Representante)
     */
    public function misEstudiantes()
    {
        // Verificar que el usuario tenga rol de Representante
        if (auth()->user()->rol !== 'Representante') {
            return redirect()->route('rutarrr1')
                ->with('error', 'No tiene permisos para acceder a esta sección. Esta función es exclusiva para representantes.');
        }

        // Obtener el ID del representante asociado al usuario
        $representante_id = auth()->user()->representante_id;

        if (!$representante_id) {
            return redirect()->route('rutarrr1')
                ->with('error', 'No tiene estudiantes asignados a su cuenta.');
        }

        // Obtener los estudiantes representados con todas sus matrículas activas
        $estudiantesRepresentados = \App\Models\InfEstudianteRepresentante::where('representante_id', $representante_id)
            ->with(['estudiante' => function ($query) {
                $query->where('estado', 'Activo');
            }])
            ->get()
            ->map(function ($relacion) {
                // Si el estudiante no existe o no está activo, omitirlo
                if (!$relacion->estudiante) {
                    return null;
                }

                // Obtener todas las matrículas activas del estudiante
                $matriculas = \App\Models\Matricula::where('estudiante_id', $relacion->estudiante_id)
                    ->whereIn('estado', ['Matriculado', 'Pre-inscrito'])
                    ->with(['grado.nivel', 'seccion'])
                    ->orderBy('fecha_matricula', 'desc')
                    ->get()
                    ->map(function ($matricula) {
                        // Obtener el curso para cada matrícula
                        $curso = $matricula->curso_id ? \App\Models\InfCurso::with(['anoLectivo'])->find($matricula->curso_id) : null;
                        $matricula->curso = $curso;
                        return $matricula;
                    });

                // Devolver datos del estudiante con todas sus matrículas
                return [
                    'estudiante' => $relacion->estudiante,
                    'es_principal' => $relacion->es_principal,
                    'viveConEstudiante' => $relacion->viveConEstudiante,
                    'matriculas' => $matriculas, // Ahora es una colección de matrículas
                    'matricula_principal' => $matriculas->first(), // Primera matrícula como principal
                ];
            })
            ->filter() // Eliminar valores nulos
            ->sortBy(function ($item) {
                // Ordenar por apellido del estudiante
                return $item['estudiante']->apellidos;
            });

        return view('asistencia.mis-estudiantes', compact('estudiantesRepresentados'));
    }

    /**
     * Exportar reporte de asistencia por curso a PDF
     */
    public function exportarPDFCurso($cursoAsignaturaId, Request $request)
    {
        // Aumentar límite de memoria y tiempo de ejecución
        ini_set('memory_limit', '256M');
        ini_set('max_execution_time', 120);

        $cursoAsignatura = CursoAsignatura::with([
            'asignatura',
            'curso.grado',
            'curso.seccion',
            'profesor',
        ])->findOrFail($cursoAsignaturaId);

        // Fechas del filtro
        $fechaInicio = $request->fecha_inicio
            ? Carbon::parse($request->fecha_inicio)
            : Carbon::now()->startOfMonth();

        $fechaFin = $request->fecha_fin
            ? Carbon::parse($request->fecha_fin)
            : Carbon::now()->endOfMonth();

        // Obtener estudiantes matriculados
        $matriculas = Matricula::where('curso_id', $cursoAsignatura->curso_id)
            ->where('estado', 'Matriculado')
            ->with('estudiante')
            ->get();

        // Tipos de asistencia
        $tipoPresente = TipoAsistencia::where('codigo', 'A')->first();
        $tipoAusente = TipoAsistencia::where('codigo', 'F')->first();
        $tipoTardanza = TipoAsistencia::where('codigo', 'T')->first();
        $tipoJustificada = TipoAsistencia::where('codigo', 'J')->first();

        // Estadísticas por estudiante
        $estudiantes = [];
        $totalPresentes = 0;
        $totalAusencias = 0;
        $totalTardanzas = 0;
        $totalJustificadas = 0;

        foreach ($matriculas as $matricula) {
            $asistencias = AsistenciaAsignatura::where('matricula_id', $matricula->matricula_id)
                ->where('curso_asignatura_id', $cursoAsignaturaId)
                ->whereBetween('fecha', [$fechaInicio, $fechaFin])
                ->get();

            $presentes = $asistencias->where('tipo_asistencia_id', $tipoPresente?->tipo_asistencia_id)->count();
            $ausencias = $asistencias->where('tipo_asistencia_id', $tipoAusente?->tipo_asistencia_id)->count();
            $tardanzas = $asistencias->where('tipo_asistencia_id', $tipoTardanza?->tipo_asistencia_id)->count();
            $justificadas = $asistencias->where('tipo_asistencia_id', $tipoJustificada?->tipo_asistencia_id)->count();

            $totalRegistros = $asistencias->count();
            $porcentaje = $totalRegistros > 0
                ? round(($presentes / $totalRegistros) * 100)
                : 0;

            $estudiantes[] = [
                'matricula_id' => $matricula->matricula_id,
                'estudiante' => $matricula->estudiante,
                'presentes' => $presentes,
                'ausencias' => $ausencias,
                'tardanzas' => $tardanzas,
                'justificadas' => $justificadas,
                'total' => $totalRegistros,
                'porcentaje' => $porcentaje,
            ];

            $totalPresentes += $presentes;
            $totalAusencias += $ausencias;
            $totalTardanzas += $tardanzas;
            $totalJustificadas += $justificadas;
        }

        // Estadísticas generales
        $totalRegistros = $totalPresentes + $totalAusencias + $totalTardanzas + $totalJustificadas;
        $porcentajeAsistencia = $totalRegistros > 0
            ? round(($totalPresentes / $totalRegistros) * 100)
            : 0;

        $estadisticas = [
            'total_estudiantes' => $matriculas->count(),
            'porcentaje_asistencia' => $porcentajeAsistencia,
            'total_ausencias' => $totalAusencias,
            'total_tardanzas' => $totalTardanzas,
        ];

        // Configurar DomPDF para mejor rendimiento
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => false,
            'defaultFont' => 'DejaVu Sans',
            'dpi' => 96,
            'defaultPaperSize' => 'a4',
            'defaultPaperOrientation' => 'landscape',
            'isFontSubsettingEnabled' => true,
            'isJavascriptEnabled' => false,
        ]);

        // Determinar el nombre del archivo
        $nombreArchivo = 'reporte_asistencia_curso_' . $cursoAsignatura->asignatura->nombre . '_' .
                        $cursoAsignatura->curso->grado->nombre . '_' . $cursoAsignatura->curso->seccion->nombre . '.pdf';

        $pdf->loadView('asistencia.pdf.reporte-curso', compact(
            'cursoAsignatura',
            'fechaInicio',
            'fechaFin',
            'estudiantes',
            'estadisticas'
        ));

        return $pdf->download($nombreArchivo);
    }

    /**
     * Exportar reporte de asistencia individual a PDF
     */
    public function exportarPDF($matriculaId, Request $request)
    {
        // Aumentar límite de memoria y tiempo de ejecución
        ini_set('memory_limit', '256M');
        ini_set('max_execution_time', 120);

        $matricula = \App\Models\Matricula::with(['estudiante', 'grado.nivel', 'seccion'])->findOrFail($matriculaId);

        // Obtener el curso completo
        $curso = $matricula->curso_id ? \App\Models\InfCurso::with(['anoLectivo'])->find($matricula->curso_id) : null;

        // Limitar el rango de fechas para evitar datasets muy grandes
        // Por defecto: últimos 6 meses para asegurar que se muestren registros existentes
        $fechaInicio = $request->get('fecha_inicio', \Carbon\Carbon::now()->subMonths(6)->startOfMonth());
        $fechaFin = $request->get('fecha_fin', \Carbon\Carbon::now());

        // Validar que el rango no sea mayor a 3 meses para evitar memory exhaustion
        $diasDiferencia = \Carbon\Carbon::parse($fechaInicio)->diffInDays(\Carbon\Carbon::parse($fechaFin));
        if ($diasDiferencia > 90) { // 3 meses máximo
            $fechaInicio = \Carbon\Carbon::now()->subDays(90)->startOfDay();
            $fechaFin = \Carbon\Carbon::now()->endOfDay();
        }

        // Optimizar la consulta: cargar solo datos necesarios y limitar resultados
        $query = \App\Models\AsistenciaAsignatura::select([
                'asistencia_asignatura_id',
                'matricula_id',
                'curso_asignatura_id',
                'tipo_asistencia_id',
                'fecha',
                'justificacion',
                'estado'
            ])
            ->with([
                'tipoAsistencia:tipo_asistencia_id,nombre,codigo,computa_falta',
                'cursoAsignatura:curso_asignatura_id,asignatura_id',
                'cursoAsignatura.asignatura:asignatura_id,nombre'
            ])
            ->where('matricula_id', $matriculaId)
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->orderBy('fecha', 'desc')
            ->limit(500); // Limitar a 500 registros máximo para evitar memory issues

        // Filtrar por asignatura si se especifica
        $asignaturaFiltro = $request->get('asignatura');
        if ($asignaturaFiltro) {
            $query->whereHas('cursoAsignatura.asignatura', function ($q) use ($asignaturaFiltro) {
                $q->where('nombre', $asignaturaFiltro);
            });
        }

        $asistencias = $query->get();

        // Calcular estadísticas de manera más eficiente
        $estadisticas = $this->calcularEstadisticasPDF($asistencias, $matriculaId);

        // Calcular tendencia usando el método completo
        $estadisticas['tendencia'] = $this->calcularTendencia($asistencias);

        // Configurar DomPDF para mejor rendimiento
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => false,
            'defaultFont' => 'DejaVu Sans',
            'dpi' => 96,
            'defaultPaperSize' => 'a4',
            'defaultPaperOrientation' => 'portrait',
            'isFontSubsettingEnabled' => true,
            'isJavascriptEnabled' => false,
        ]);

        // Determinar el nombre del archivo basado en el filtro
        $nombreArchivo = 'reporte_asistencia_' . $matricula->estudiante->documento;
        if ($asignaturaFiltro) {
            $nombreArchivo .= '_' . \Illuminate\Support\Str::slug($asignaturaFiltro);
        }
        $nombreArchivo .= '.pdf';

        $pdf->loadView('asistencia.pdf.reporte-estudiante', compact(
            'matricula',
            'curso',
            'asistencias',
            'estadisticas',
            'fechaInicio',
            'fechaFin',
            'asignaturaFiltro'
        ));

        return $pdf->download($nombreArchivo);
    }

    /**
     * Calcular estadísticas de manera optimizada para PDF
     */
    private function calcularEstadisticasPDF($asistencias, $matriculaId)
    {
        $total = $asistencias->count();

        // Inicializar contadores por tipo
        $tiposAsistencia = \App\Models\TipoAsistencia::where('activo', 1)->get()->keyBy('tipo_asistencia_id');
        $porTipo = [];
        $presentes = 0;
        $totalRegistros = 0;

        // Contar asistencias por tipo
        foreach ($asistencias as $asistencia) {
            $tipoId = $asistencia->tipo_asistencia_id;

            if (!isset($porTipo[$tipoId])) {
                $porTipo[$tipoId] = 0;
            }
            $porTipo[$tipoId]++;

            // Contar presentes (tipos que no computan falta)
            if ($asistencia->tipoAsistencia && !$asistencia->tipoAsistencia->computa_falta) {
                $presentes++;
            }
            $totalRegistros++;
        }

        $porcentajeAsistencia = $totalRegistros > 0 ? round(($presentes / $totalRegistros) * 100, 1) : 0;

        // Calcular racha de asistencia de manera más eficiente
        $rachaActual = $this->calcularRachaAsistenciaOptimizada($matriculaId);

        return [
            'total' => $total,
            'por_tipo' => $porTipo,
            'porcentaje_asistencia' => $porcentajeAsistencia,
            'racha_actual' => $rachaActual,
            'tendencia' => 'estable', // Will be overridden by proper calculation
            'tipos_asistencia' => $tiposAsistencia, // Para referencia en la vista
        ];
    }

    /**
     * Calcular racha de asistencia de manera optimizada
     */
    private function calcularRachaAsistenciaOptimizada($matriculaId)
    {
        $asistenciasRecientes = \App\Models\AsistenciaAsignatura::select(['tipo_asistencia_id', 'fecha'])
            ->with(['tipoAsistencia:tipo_asistencia_id,computa_falta'])
            ->where('matricula_id', $matriculaId)
            ->orderBy('fecha', 'desc')
            ->take(30)
            ->get();

        $racha = 0;
        foreach ($asistenciasRecientes as $asistencia) {
            if ($asistencia->tipoAsistencia && !$asistencia->tipoAsistencia->computa_falta) {
                $racha++;
            } else {
                break;
            }
        }

        return $racha;
    }

    /**
     * Mostrar reporte general de asistencia
     */
    public function reporteGeneral(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio', Carbon::now()->startOfMonth());
        $fechaFin = $request->get('fecha_fin', Carbon::now()->endOfMonth());
        $cursoId = $request->get('curso_id');

        // Convertir fechas si son strings
        if (is_string($fechaInicio)) {
            $fechaInicio = Carbon::parse($fechaInicio);
        }
        if (is_string($fechaFin)) {
            $fechaFin = Carbon::parse($fechaFin);
        }

        // Obtener estadísticas generales
        $query = AsistenciaAsignatura::whereBetween('fecha', [$fechaInicio, $fechaFin]);

        if ($cursoId) {
            $query->whereHas('matricula', function ($q) use ($cursoId) {
                $q->where('curso_id', $cursoId);
            });
        }

        $asistencias = $query->with(['tipoAsistencia', 'matricula'])->get();

        // Calcular estadísticas
        $estadisticas = [
            'total_estudiantes' => $asistencias->pluck('matricula.matricula_id')->unique()->count(),
            'total_presentes' => $asistencias->filter(function ($a) {
                return optional($a->tipoAsistencia)->codigo === 'A';
            })->count(),
            'total_ausentes' => $asistencias->filter(function ($a) {
                return optional($a->tipoAsistencia)->codigo === 'F';
            })->count(),
            'total_tardanzas' => $asistencias->filter(function ($a) {
                return optional($a->tipoAsistencia)->codigo === 'T';
            })->count(),
        ];

        // Estadísticas por curso
        $estadisticasPorCurso = [];
        if ($cursoId) {
            $curso = \App\Models\InfCurso::with(['grado', 'seccion'])->find($cursoId);
            if ($curso) {
                $asistenciasCurso = $asistencias->filter(function ($a) use ($cursoId) {
                    return $a->matricula->curso_id == $cursoId;
                });

                $totalEstudiantes = $asistenciasCurso->pluck('matricula.matricula_id')->unique()->count();
                $presentes = $asistenciasCurso->filter(function ($a) {
                    return optional($a->tipoAsistencia)->codigo === 'A';
                })->count();

                $estadisticasPorCurso[] = [
                    'grado' => $curso->grado->nombre,
                    'seccion' => $curso->seccion->nombre,
                    'total_estudiantes' => $totalEstudiantes,
                    'presentes' => $presentes,
                    'ausentes' => $asistenciasCurso->filter(function ($a) {
                        return optional($a->tipoAsistencia)->codigo === 'F';
                    })->count(),
                    'tardanzas' => $asistenciasCurso->filter(function ($a) {
                        return optional($a->tipoAsistencia)->codigo === 'T';
                    })->count(),
                    'porcentaje' => $totalEstudiantes > 0 ? round(($presentes / $totalEstudiantes) * 100, 1) : 0,
                ];
            }
        } else {
            // Estadísticas por todos los cursos
            $cursos = $asistencias->groupBy('matricula.curso_id');
            foreach ($cursos as $cursoId => $asistenciasCurso) {
                $curso = \App\Models\InfCurso::with(['grado', 'seccion'])->find($cursoId);
                if ($curso) {
                    $totalEstudiantes = $asistenciasCurso->pluck('matricula.matricula_id')->unique()->count();
                    $presentes = $asistenciasCurso->filter(function ($a) {
                        return optional($a->tipoAsistencia)->codigo === 'A';
                    })->count();

                    $estadisticasPorCurso[] = [
                        'grado' => $curso->grado->nombre,
                        'seccion' => $curso->seccion->nombre,
                        'total_estudiantes' => $totalEstudiantes,
                        'presentes' => $presentes,
                        'ausentes' => $asistenciasCurso->filter(function ($a) {
                            return optional($a->tipoAsistencia)->codigo === 'F';
                        })->count(),
                        'tardanzas' => $asistenciasCurso->filter(function ($a) {
                            return optional($a->tipoAsistencia)->codigo === 'T';
                        })->count(),
                        'porcentaje' => $totalEstudiantes > 0 ? round(($presentes / $totalEstudiantes) * 100, 1) : 0,
                    ];
                }
            }
        }

        // Datos para gráficos
        $datosGrafico = [
            'labels' => [],
            'porcentajes' => [],
        ];

        // Generar datos de tendencia por día
        $periodo = Carbon::parse($fechaInicio);
        while ($periodo->lte($fechaFin)) {
            $datosGrafico['labels'][] = $periodo->format('d/m');

            $asistenciasDia = $asistencias->filter(function ($a) use ($periodo) {
                return Carbon::parse($a->fecha)->isSameDay($periodo);
            });

            $totalDia = $asistenciasDia->count();
            $presentesDia = $asistenciasDia->filter(function ($a) {
                return optional($a->tipoAsistencia)->codigo === 'A';
            })->count();

            $datosGrafico['porcentajes'][] = $totalDia > 0 ? round(($presentesDia / $totalDia) * 100, 1) : 0;

            $periodo->addDay();
        }

        // Obtener cursos para el filtro
        $cursos = \App\Models\InfCurso::with(['grado', 'seccion'])
            ->whereHas('matriculas', function ($q) {
                $q->where('estado', 'Matriculado');
            })
            ->get();

        return view('asistencia.reporte-general', compact(
            'estadisticas',
            'estadisticasPorCurso',
            'datosGrafico',
            'fechaInicio',
            'fechaFin',
            'cursos',
            'cursoId'
        ));
    }

    /**
     * Mostrar formulario para justificar inasistencia
     */
    public function justificar(Request $request)
    {
        // Solo representantes pueden justificar asistencias
        if (auth()->user()->rol !== 'Representante') {
            abort(403, 'No tiene permisos para justificar asistencias. Solo los representantes pueden realizar esta acción.');
        }

        // Obtener matrículas de los estudiantes representados por el usuario actual
        $estudiantesIds = \App\Models\InfEstudianteRepresentante::where('representante_id', auth()->user()->representante_id)
            ->pluck('estudiante_id');

        $matriculas = Matricula::whereIn('estudiante_id', $estudiantesIds)
            ->where('estado', 'Matriculado')
            ->with(['estudiante', 'grado.nivel', 'seccion'])
            ->get();

        return view('asistencia.justificar', compact('matriculas'));
    }

    /**
     * Procesar justificación de inasistencia
     */
    public function guardarJustificacion(Request $request)
    {
        // Solo representantes pueden justificar
        if (auth()->user()->rol !== 'Representante') {
            abort(403, 'No tiene permisos para justificar asistencias. Solo los representantes pueden realizar esta acción.');
        }

        $request->validate([
            'matricula_id' => 'required|exists:matriculas,matricula_id',
            'fecha' => 'required|date|after:yesterday|before_or_equal:' . now()->addDays(30)->format('Y-m-d'),
            'motivo' => 'required|string|max:255',
            'descripcion' => 'required|string|min:10|max:1000',
            'documento_justificacion' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:2048',
        ]);

        // Verificar que el estudiante pertenezca al representante
        $matricula = Matricula::findOrFail($request->matricula_id);
        $esRepresentante = \App\Models\InfEstudianteRepresentante::where('representante_id', auth()->user()->representante_id)
            ->where('estudiante_id', $matricula->estudiante_id)
            ->exists();

        if (!$esRepresentante) {
            return back()->with('error', 'No tiene permisos para justificar la asistencia de este estudiante');
        }

        // Verificar que no exista una justificación previa para la misma fecha
        $justificacionExistente = \App\Models\JustificacionAsistencia::where('matricula_id', $request->matricula_id)
            ->where('fecha', $request->fecha)
            ->exists();

        if ($justificacionExistente) {
            return back()->with('error', 'Ya existe una justificación para esta fecha');
        }

        // Procesar archivo si se adjuntó
        $documentoPath = null;
        if ($request->hasFile('documento_justificacion')) {
            $fileName = time() . '_' . $request->file('documento_justificacion')->getClientOriginalName();
            $documentoPath = $request->file('documento_justificacion')->storeAs('justificaciones', $fileName, 'public');
        }

        // Crear justificación
        \App\Models\JustificacionAsistencia::create([
            'matricula_id' => $request->matricula_id,
            'fecha' => $request->fecha,
            'motivo' => $request->motivo,
            'descripcion' => $request->descripcion,
            'documento_justificacion' => $documentoPath,
            'usuario_creador_id' => auth()->id(),
            'estado' => 'pendiente',
        ]);

        return redirect()->route('asistencia.misEstudiantes')->with('success', 'Justificación enviada correctamente. Será revisada por un administrador.');
    }

    /**
     * Vista administrativa de todas las asistencias (solo administradores)
     */
    public function adminIndex(Request $request)
    {
        // Solo administradores pueden acceder
        if (auth()->user()->rol !== 'Administrador') {
            abort(403, 'No tiene permisos para acceder a esta sección');
        }

        $query = AsistenciaAsignatura::with([
            'matricula.estudiante',
            'cursoAsignatura.asignatura',
            'cursoAsignatura.profesor',
            'tipoAsistencia'
        ]);

        // Filtros
        $filtros = [];

        // Filtro por profesor
        if ($request->filled('profesor_id')) {
            $query->whereHas('cursoAsignatura', function ($q) use ($request) {
                $q->where('profesor_id', $request->profesor_id);
            });
            $filtros['profesor_id'] = $request->profesor_id;
        }

        // Filtro por curso
        if ($request->filled('curso_id')) {
            $query->whereHas('matricula', function ($q) use ($request) {
                $q->where('curso_id', $request->curso_id);
            });
            $filtros['curso_id'] = $request->curso_id;
        }

        // Filtro por estudiante
        if ($request->filled('estudiante_id')) {
            $query->where('matricula_id', $request->estudiante_id);
            $filtros['estudiante_id'] = $request->estudiante_id;
        }

        // Filtro por asignatura
        if ($request->filled('asignatura_id')) {
            $query->whereHas('cursoAsignatura', function ($q) use ($request) {
                $q->where('asignatura_id', $request->asignatura_id);
            });
            $filtros['asignatura_id'] = $request->asignatura_id;
        }

        // Filtro por tipo de asistencia
        if ($request->filled('tipo_asistencia_id')) {
            $query->where('tipo_asistencia_id', $request->tipo_asistencia_id);
            $filtros['tipo_asistencia_id'] = $request->tipo_asistencia_id;
        }

        // Filtro por fecha
        if ($request->filled('fecha_desde')) {
            $query->where('fecha', '>=', $request->fecha_desde);
            $filtros['fecha_desde'] = $request->fecha_desde;
        }

        if ($request->filled('fecha_hasta')) {
            $query->where('fecha', '<=', $request->fecha_hasta);
            $filtros['fecha_hasta'] = $request->fecha_hasta;
        }

        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
            $filtros['estado'] = $request->estado;
        }

        // Búsqueda general
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function ($q) use ($buscar) {
                $q->whereHas('matricula.estudiante', function ($sq) use ($buscar) {
                    $sq->where('nombres', 'like', "%{$buscar}%")
                       ->orWhere('apellidos', 'like', "%{$buscar}%")
                       ->orWhere('dni', 'like', "%{$buscar}%");
                })
                ->orWhereHas('cursoAsignatura.asignatura', function ($sq) use ($buscar) {
                    $sq->where('nombre', 'like', "%{$buscar}%");
                })
                ->orWhereHas('cursoAsignatura.profesor', function ($sq) use ($buscar) {
                    $sq->where('nombres', 'like', "%{$buscar}%")
                       ->orWhere('apellidos', 'like', "%{$buscar}%");
                });
            });
            $filtros['buscar'] = $request->buscar;
        }

        // Ordenamiento
        $ordenarPor = $request->get('ordenar', 'fecha');
        $orden = $request->get('orden', 'desc');

        switch ($ordenarPor) {
            case 'estudiante':
                $query->leftJoin('matriculas', 'asistencia_asignatura.matricula_id', '=', 'matriculas.matricula_id')
                      ->leftJoin('estudiantes', 'matriculas.estudiante_id', '=', 'estudiantes.estudiante_id')
                      ->orderBy('estudiantes.apellidos', $orden)
                      ->orderBy('estudiantes.nombres', $orden)
                      ->select('asistencia_asignatura.*');
                break;
            case 'profesor':
                $query->leftJoin('cursoasignaturas', 'asistencia_asignatura.curso_asignatura_id', '=', 'cursoasignaturas.curso_asignatura_id')
                      ->leftJoin('docentes', 'cursoasignaturas.profesor_id', '=', 'docentes.profesor_id')
                      ->orderBy('docentes.apellidos', $orden)
                      ->orderBy('docentes.nombres', $orden)
                      ->select('asistencia_asignatura.*');
                break;
            case 'asignatura':
                $query->leftJoin('cursoasignaturas', 'asistencia_asignatura.curso_asignatura_id', '=', 'cursoasignaturas.curso_asignatura_id')
                      ->leftJoin('asignaturas', 'cursoasignaturas.asignatura_id', '=', 'asignaturas.asignatura_id')
                      ->orderBy('asignaturas.nombre', $orden)
                      ->select('asistencia_asignatura.*');
                break;
            default:
                $query->orderBy('fecha', $orden)
                      ->orderBy('hora_registro', $orden);
                break;
        }

        $asistencias = $query->paginate(20);

        // Datos para los filtros
        $profesores = \App\Models\InfDocente::where('estado', 'Activo')
            ->orderBy('apellidos')
            ->orderBy('nombres')
            ->get();

        $cursos = \App\Models\InfCurso::with(['grado', 'seccion'])
            ->join('grados', 'cursos.grado_id', '=', 'grados.grado_id')
            ->join('secciones', 'cursos.seccion_id', '=', 'secciones.seccion_id')
            ->whereHas('matriculas', function ($q) {
                $q->where('estado', 'Matriculado');
            })
            ->orderBy('grados.nombre')
            ->orderBy('secciones.nombre')
            ->select('cursos.*')
            ->get();

        $asignaturas = \App\Models\InfAsignatura::orderBy('nombre')->get();

        $tiposAsistencia = TipoAsistencia::where('activo', 1)->orderBy('nombre')->get();

        // Estadísticas rápidas
        $estadisticas = [
            'total' => $query->count(),
            'presentes' => (clone $query)->whereHas('tipoAsistencia', function ($q) {
                $q->where('codigo', 'A');
            })->count(),
            'ausentes' => (clone $query)->whereHas('tipoAsistencia', function ($q) {
                $q->where('codigo', 'F');
            })->count(),
            'tardanzas' => (clone $query)->whereHas('tipoAsistencia', function ($q) {
                $q->where('codigo', 'T');
            })->count(),
            'justificadas' => (clone $query)->whereHas('tipoAsistencia', function ($q) {
                $q->where('codigo', 'J');
            })->count(),
        ];

        return view('asistencia.admin-index', compact(
            'asistencias',
            'profesores',
            'cursos',
            'asignaturas',
            'tiposAsistencia',
            'estadisticas',
            'filtros',
            'ordenarPor',
            'orden'
        ));
    }

    /**
     * Mostrar justificaciones enviadas por el representante
     */
    public function misJustificaciones(Request $request)
    {
        // Solo representantes pueden ver sus justificaciones
        if (auth()->user()->rol !== 'Representante') {
            abort(403, 'No tiene permisos para acceder a esta sección');
        }

        // Obtener IDs de estudiantes representados por el usuario actual
        $estudiantesIds = \App\Models\InfEstudianteRepresentante::where('representante_id', auth()->user()->representante_id)
            ->pluck('estudiante_id');

        $query = \App\Models\JustificacionAsistencia::with(['matricula.estudiante', 'usuarioCreador', 'usuarioRevisor'])
            ->whereHas('matricula', function ($q) use ($estudiantesIds) {
                $q->whereIn('estudiante_id', $estudiantesIds);
            });

        // Filtros
        if ($request->estado) {
            $query->where('estado', $request->estado);
        }

        if ($request->fecha_desde) {
            $query->where('fecha', '>=', $request->fecha_desde);
        }

        if ($request->fecha_hasta) {
            $query->where('fecha', '<=', $request->fecha_hasta);
        }

        if ($request->estudiante_id) {
            $query->whereHas('matricula', function ($q) use ($request) {
                $q->where('estudiante_id', $request->estudiante_id);
            });
        }

        $justificaciones = $query->orderBy('created_at', 'desc')->paginate(15);

        // Estadísticas
        $estadisticas = [
            'total' => $query->count(),
            'pendientes' => (clone $query)->where('estado', 'pendiente')->count(),
            'aprobadas' => (clone $query)->where('estado', 'aprobado')->count(),
            'rechazadas' => (clone $query)->where('estado', 'rechazado')->count(),
        ];

        // Obtener estudiantes para el filtro
        $estudiantes = \App\Models\InfEstudiante::whereIn('estudiante_id', $estudiantesIds)
            ->with(['matriculas' => function ($q) {
                $q->where('estado', 'Matriculado')
                  ->with(['grado', 'seccion']);
            }])
            ->get()
            ->map(function ($estudiante) {
                $matricula = $estudiante->matriculas->first();
                return [
                    'estudiante_id' => $estudiante->estudiante_id,
                    'nombre_completo' => $estudiante->nombres . ' ' . $estudiante->apellidos,
                    'grado_seccion' => $matricula ? $matricula->grado->nombre . ' - ' . $matricula->seccion->nombre : 'Sin matrícula',
                ];
            });

        return view('asistencia.mis-justificaciones', compact('justificaciones', 'estadisticas', 'estudiantes'));
    }

    /**
     * Mostrar justificaciones para verificar (solo administradores)
     */
    public function verificar(Request $request)
    {
        if (auth()->user()->rol !== 'Administrador') {
            abort(403, 'No tiene permisos para acceder a esta sección');
        }

        $query = \App\Models\JustificacionAsistencia::with(['matricula.estudiante', 'usuarioCreador']);

        // Filtros
        if ($request->estado) {
            $query->where('estado', $request->estado);
        }

        if ($request->fecha_desde) {
            $query->where('fecha', '>=', $request->fecha_desde);
        }

        if ($request->fecha_hasta) {
            $query->where('fecha', '<=', $request->fecha_hasta);
        }

        $justificaciones = $query->orderBy('created_at', 'desc')->paginate(15);

        // Estadísticas
        $estadisticas = [
            'pendientes' => \App\Models\JustificacionAsistencia::where('estado', 'pendiente')->count(),
            'aprobadas' => \App\Models\JustificacionAsistencia::where('estado', 'aprobado')->count(),
            'rechazadas' => \App\Models\JustificacionAsistencia::where('estado', 'rechazado')->count(),
        ];

        return view('asistencia.verificar', compact('justificaciones', 'estadisticas'));
    }

    /**
     * Procesar verificación de justificación (aprobar/rechazar)
     */
    public function procesarVerificacion(Request $request)
    {
        if (auth()->user()->rol !== 'Administrador') {
            abort(403, 'No tiene permisos para realizar esta acción');
        }

        $request->validate([
            'justificacion_id' => 'required|exists:justificaciones_asistencia,id',
            'accion' => 'required|in:aprobado,rechazado',
            'observaciones' => 'nullable|string|max:500',
        ]);

        $justificacion = \App\Models\JustificacionAsistencia::findOrFail($request->justificacion_id);

        $justificacion->update([
            'estado' => $request->accion,
            'usuario_revisor_id' => auth()->id(),
            'fecha_revision' => now(),
            'observaciones_revision' => $request->observaciones,
        ]);

        // Si se aprueba, actualizar la asistencia correspondiente
        if ($request->accion === 'aprobado') {
            $tipoJustificada = TipoAsistencia::where('codigo', 'J')->first();
            if ($tipoJustificada) {
                // Buscar si ya existe un registro de asistencia para esta fecha y matrícula
                $asistenciaExistente = AsistenciaAsignatura::where('matricula_id', $justificacion->matricula_id)
                    ->where('fecha', $justificacion->fecha)
                    ->first();

                if ($asistenciaExistente) {
                    // Si existe, actualizar el registro existente
                    $asistenciaExistente->update([
                        'tipo_asistencia_id' => $tipoJustificada->tipo_asistencia_id,
                        'justificacion' => $justificacion->descripcion,
                        'estado' => 'Justificada',
                        'usuario_registro' => auth()->id(),
                        'hora_registro' => now(),
                    ]);
                } else {
                    // Si no existe, buscar las sesiones de clase programadas para esa fecha
                    $sesiones = SesionClase::whereHas('cursoAsignatura', function ($query) use ($justificacion) {
                        $query->where('curso_id', $justificacion->matricula->curso_id);
                    })
                    ->where('fecha', $justificacion->fecha)
                    ->with('cursoAsignatura')
                    ->get();

                    if ($sesiones->isNotEmpty()) {
                        // Crear registros de asistencia justificada para cada sesión
                        foreach ($sesiones as $sesion) {
                            AsistenciaAsignatura::create([
                                'matricula_id' => $justificacion->matricula_id,
                                'curso_asignatura_id' => $sesion->curso_asignatura_id,
                                'fecha' => $justificacion->fecha,
                                'tipo_asistencia_id' => $tipoJustificada->tipo_asistencia_id,
                                'justificacion' => $justificacion->descripcion,
                                'estado' => 'Justificada',
                                'usuario_registro' => auth()->id(),
                                'hora_registro' => now(),
                            ]);
                        }
                    } else {
                        // Si no hay sesiones programadas, crear un registro genérico
                        // Usar la primera asignatura del curso como fallback
                        $primeraAsignatura = \App\Models\CursoAsignatura::where('curso_id', $justificacion->matricula->curso_id)
                            ->first();

                        if ($primeraAsignatura) {
                            AsistenciaAsignatura::create([
                                'matricula_id' => $justificacion->matricula_id,
                                'curso_asignatura_id' => $primeraAsignatura->curso_asignatura_id,
                                'fecha' => $justificacion->fecha,
                                'tipo_asistencia_id' => $tipoJustificada->tipo_asistencia_id,
                                'justificacion' => $justificacion->descripcion,
                                'estado' => 'Justificada',
                                'usuario_registro' => auth()->id(),
                                'hora_registro' => now(),
                            ]);
                        }
                    }
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Justificación ' . ($request->accion === 'aprobado' ? 'aprobada' : 'rechazada') . ' correctamente'
        ]);
    }

    /**
     * Mostrar notificaciones de asistencia
     */
    public function notificaciones(Request $request)
    {
        $query = \App\Models\NotificacionAsistencia::where('usuario_id', auth()->id());

        // Filtros
        if ($request->estado === 'no_leida') {
            $query->where('leida', false);
        } elseif ($request->estado === 'leida') {
            $query->where('leida', true);
        }

        if ($request->tipo) {
            $query->where('tipo', $request->tipo);
        }

        $notificaciones = $query->orderBy('created_at', 'desc')->paginate(20);

        // Estadísticas
        $estadisticas = [
            'total' => \App\Models\NotificacionAsistencia::where('usuario_id', auth()->id())->count(),
            'no_leidas' => \App\Models\NotificacionAsistencia::where('usuario_id', auth()->id())->where('leida', false)->count(),
            'leidas' => \App\Models\NotificacionAsistencia::where('usuario_id', auth()->id())->where('leida', true)->count(),
            'urgentes' => \App\Models\NotificacionAsistencia::where('usuario_id', auth()->id())->where('urgente', true)->count(),
        ];

        return view('asistencia.notificaciones', compact('notificaciones', 'estadisticas'));
    }

    /**
     * Marcar notificación como leída
     */
    public function marcarNotificacionLeida(Request $request)
    {
        $request->validate([
            'notificacion_id' => 'required|exists:notificaciones_asistencia,id',
        ]);

        $notificacion = \App\Models\NotificacionAsistencia::where('id', $request->notificacion_id)
            ->where('usuario_id', auth()->id())
            ->firstOrFail();

        $notificacion->update(['leida' => true]);

        return response()->json(['success' => true]);
    }

    /**
     * Marcar todas las notificaciones como leídas
     */
    public function marcarTodasNotificacionesLeidas(Request $request)
    {
        \App\Models\NotificacionAsistencia::where('usuario_id', auth()->id())
            ->where('leida', false)
            ->update(['leida' => true]);

        return response()->json(['success' => true]);
    }



    /**
     * Exportar asistencias a PDF desde vista administrativa
     */
    public function exportarPDFAdmin(Request $request)
    {
        // Solo administradores pueden exportar
        if (auth()->user()->rol !== 'Administrador') {
            abort(403, 'No tiene permisos para exportar datos');
        }

        // Aplicar los mismos filtros que en adminIndex
        $query = AsistenciaAsignatura::with([
            'matricula.estudiante',
            'cursoAsignatura.asignatura',
            'cursoAsignatura.profesor',
            'tipoAsistencia'
        ]);

        // Filtros (mismo código que en exportarExcel)
        if ($request->filled('profesor_id')) {
            $query->whereHas('cursoAsignatura', function ($q) use ($request) {
                $q->where('profesor_id', $request->profesor_id);
            });
        }

        if ($request->filled('curso_id')) {
            $query->whereHas('matricula', function ($q) use ($request) {
                $q->where('curso_id', $request->curso_id);
            });
        }

        if ($request->filled('estudiante_id')) {
            $query->where('matricula_id', $request->estudiante_id);
        }

        if ($request->filled('asignatura_id')) {
            $query->whereHas('cursoAsignatura', function ($q) use ($request) {
                $q->where('asignatura_id', $request->asignatura_id);
            });
        }

        if ($request->filled('tipo_asistencia_id')) {
            $query->where('tipo_asistencia_id', $request->tipo_asistencia_id);
        }

        if ($request->filled('fecha_desde')) {
            $query->where('fecha', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->where('fecha', '<=', $request->fecha_hasta);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // Búsqueda general
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function ($q) use ($buscar) {
                $q->whereHas('matricula.estudiante', function ($sq) use ($buscar) {
                    $sq->where('nombres', 'like', "%{$buscar}%")
                       ->orWhere('apellidos', 'like', "%{$buscar}%")
                       ->orWhere('dni', 'like', "%{$buscar}%");
                })
                ->orWhereHas('cursoAsignatura.asignatura', function ($sq) use ($buscar) {
                    $sq->where('nombre', 'like', "%{$buscar}%");
                })
                ->orWhereHas('cursoAsignatura.profesor', function ($sq) use ($buscar) {
                    $sq->where('nombres', 'like', "%{$buscar}%")
                       ->orWhere('apellidos', 'like', "%{$buscar}%");
                });
            });
        }

        // Ordenamiento
        $ordenarPor = $request->get('ordenar', 'fecha');
        $orden = $request->get('orden', 'desc');

        switch ($ordenarPor) {
            case 'estudiante':
                $query->join('matriculas', 'asistencia_asignatura.matricula_id', '=', 'matriculas.matricula_id')
                      ->join('estudiantes', 'matriculas.estudiante_id', '=', 'estudiantes.estudiante_id')
                      ->orderBy('estudiantes.apellidos', $orden)
                      ->orderBy('estudiantes.nombres', $orden)
                      ->select('asistencia_asignatura.*');
                break;
            case 'profesor':
                $query->join('cursoasignaturas', 'asistencia_asignatura.curso_asignatura_id', '=', 'cursoasignaturas.curso_asignatura_id')
                      ->join('docentes', 'cursoasignaturas.profesor_id', '=', 'docentes.profesor_id')
                      ->orderBy('docentes.apellidos', $orden)
                      ->orderBy('docentes.nombres', $orden)
                      ->select('asistencia_asignatura.*');
                break;
            case 'asignatura':
                $query->join('cursoasignaturas', 'asistencia_asignatura.curso_asignatura_id', '=', 'cursoasignaturas.curso_asignatura_id')
                      ->join('asignaturas', 'cursoasignaturas.asignatura_id', '=', 'asignaturas.asignatura_id')
                      ->orderBy('asignaturas.nombre', $orden)
                      ->select('asistencia_asignatura.*');
                break;
            default:
                $query->orderBy('fecha', $orden)
                      ->orderBy('hora_registro', $orden);
                break;
        }

        $asistencias = $query->get();

        // Configurar DomPDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => false,
            'defaultFont' => 'DejaVu Sans',
            'dpi' => 96,
            'defaultPaperSize' => 'a4',
            'defaultPaperOrientation' => 'landscape',
            'isFontSubsettingEnabled' => true,
            'isJavascriptEnabled' => false,
        ]);

        $fileName = 'reporte_asistencias_' . date('Y-m-d_H-i-s') . '.pdf';

        $pdf->loadView('asistencia.pdf.reporte-admin', compact('asistencias'));

        return $pdf->download($fileName);
    }

    /**
     * API: Obtener cursos por profesor
     */
    public function getCursosPorProfesor($profesorId)
    {
        $cursos = \App\Models\CursoAsignatura::select([
                'cursoasignaturas.curso_id',
                'grados.nombre as grado_nombre',
                'secciones.nombre as seccion_nombre'
            ])
            ->join('cursos', 'cursoasignaturas.curso_id', '=', 'cursos.curso_id')
            ->join('grados', 'cursos.grado_id', '=', 'grados.grado_id')
            ->join('secciones', 'cursos.seccion_id', '=', 'secciones.seccion_id')
            ->where('cursoasignaturas.profesor_id', $profesorId)
            ->whereExists(function ($query) {
                $query->select(\DB::raw(1))
                      ->from('matriculas')
                      ->whereColumn('matriculas.curso_id', 'cursoasignaturas.curso_id')
                      ->where('matriculas.estado', 'Matriculado');
            })
            ->distinct()
            ->orderBy('grados.nombre')
            ->orderBy('secciones.nombre')
            ->get()
            ->map(function ($curso) {
                return [
                    'curso_id' => $curso->curso_id,
                    'grado' => [
                        'nombre' => $curso->grado_nombre
                    ],
                    'seccion' => [
                        'nombre' => $curso->seccion_nombre
                    ]
                ];
            });

        return response()->json($cursos);
    }

    /**
     * API: Obtener asignaturas por profesor
     */
    public function getAsignaturasPorProfesor($profesorId)
    {
        $asignaturas = \App\Models\InfAsignatura::join('cursoasignaturas', 'asignaturas.asignatura_id', '=', 'cursoasignaturas.asignatura_id')
            ->where('cursoasignaturas.profesor_id', $profesorId)
            ->select('asignaturas.*')
            ->distinct()
            ->orderBy('asignaturas.nombre')
            ->get();

        return response()->json($asignaturas);
    }
}
