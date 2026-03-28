<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InfCurso;
use App\Models\InfAsignatura;
use App\Models\CursoAsignatura;
use App\Models\Matricula;
use App\Models\InfEstudiante;
use App\Models\InfPeriodosEvaluacion;
use App\Models\NotasFinalesPeriodo;
use App\Models\NotasFinalesAnuales;
use App\Models\InfEstudianteRepresentante;
use Illuminate\Support\Facades\DB;

class NotasController extends Controller
{
    public function index()
    {
        if (auth()->user()->rol === 'Profesor') {
            // Si es profesor, obtener solo los cursos donde está asignado
            $profesor_id = auth()->user()->profesor_id;

            // Obtener los cursos asignados al profesor a través de cursoasignaturas
            $cursos = InfCurso::whereHas('cursoAsignaturas', function ($query) use ($profesor_id) {
                $query->where('profesor_id', $profesor_id);
            })->where('estado', '<>', 'Finalizado')->distinct()->get();
        } else {
            // Si es admin u otro rol, obtener todos los cursos
            $cursos = InfCurso::where('estado', '<>', 'Finalizado')->get();
        }

        $asignaturas = InfAsignatura::all();

        return view('cnotas.inicio', compact('cursos', 'asignaturas'));
    }

    public function listado(Request $request)
    {
        $request->validate([
            'curso_id' => 'required|exists:cursos,curso_id',
            'asignatura_id' => 'required|exists:asignaturas,asignatura_id',
        ]);

        $curso = InfCurso::findOrFail($request->curso_id);
        $asignatura = InfAsignatura::findOrFail($request->asignatura_id);

        // Obtener el curso_asignatura_id
        $cursoAsignatura = DB::table('cursoasignaturas')
            ->where('curso_id', $curso->curso_id)
            ->where('asignatura_id', $asignatura->asignatura_id)
            ->first();

        if (!$cursoAsignatura) {
            return redirect()->back()->with('error', 'La asignatura no está asociada a este curso.');
        }

        // Obtener todos los periodos del año lectivo actual
        $periodos = InfPeriodosEvaluacion::where('ano_lectivo_id', $curso->ano_lectivo_id)
            ->orderBy('fecha_inicio', 'asc')
            ->get();

        // Identificar el periodo actual (En Curso)
        $periodoActual = $periodos->where('estado', 'En Curso')->first();

        // Si no hay periodo en curso, mostrar mensaje
        if (!$periodoActual) {
            return redirect()->back()->with('error', 'No hay un período de evaluación en curso actualmente.');
        }

        // Obtener estudiantes matriculados en el curso
        $matriculas = Matricula::with('estudiante')
            ->where('curso_id', $curso->curso_id)
            ->whereIn('estado', ['Matriculado', 'Pre-inscrito'])
            ->get();

        // Obtener notas por período para cada estudiante
        $notasEstudiantes = [];

        foreach ($matriculas as $matricula) {
            $notasEstudiante = [
                'matricula_id' => $matricula->matricula_id,
                'numero_matricula' => $matricula->numero_matricula,
                'estudiante' => $matricula->estudiante->apellidos . ', ' . $matricula->estudiante->nombres,
                'notas_periodos' => [],
                'promedio' => null
            ];

            $sumaNotas = 0;
            $periodosConcalificacion = 0;

            foreach ($periodos as $periodo) {
                // Buscar nota para este periodo
                $nota = NotasFinalesPeriodo::where('matricula_id', $matricula->matricula_id)
                    ->where('curso_asignatura_id', $cursoAsignatura->curso_asignatura_id)
                    ->where('periodo_id', $periodo->periodo_id)
                    ->first();

                $notaValor = $nota ? $nota->promedio : null;

                $notasEstudiante['notas_periodos'][] = [
                    'periodo_id' => $periodo->periodo_id,
                    'nombre' => $periodo->nombre,
                    'nota' => $notaValor,
                    'editable' => $periodo->periodo_id === $periodoActual->periodo_id,
                    'observaciones' => $nota ? $nota->observaciones : null // Aseguramos que las observaciones se pasen correctamente
                ];

                if ($notaValor !== null) {
                    $sumaNotas += $notaValor;
                    $periodosConcalificacion++;
                }
            }

            // Calcular promedio SOLO si se tienen las notas de todos los periodos (4 bimestres)
            $notasEstudiante['promedio'] = ($periodosConcalificacion === count($periodos) && count($periodos) === 4) ?
                number_format($sumaNotas / 4, 2) : null;

            $notasEstudiantes[] = $notasEstudiante;
        }

        // Guardar los datos del curso y asignatura en la sesión para la validación
        // de los envíos POST subsecuentes - esto es clave para la seguridad
        session(['edicion_notas' => [
            'curso_id' => $curso->curso_id,
            'asignatura_id' => $asignatura->asignatura_id,
            'timestamp' => now()->timestamp  // Timestamp para verificar expiración
        ]]);

        return view('cnotas.editar', compact(
            'curso',
            'asignatura',
            'cursoAsignatura',
            'periodos',
            'periodoActual',
            'notasEstudiantes'
        ));
    }

    /**
     * Eliminamos la función verNotas ya que solo vamos a permitir acceso por POST
     */

    public function guardar(Request $request)
    {
        $request->validate([
            'curso_asignatura_id' => 'required|exists:cursoasignaturas,curso_asignatura_id',
            'periodo_id' => 'required|exists:periodosevaluacion,periodo_id',
            'notas' => 'required|array',
            'notas.*.matricula_id' => 'required|exists:matriculas,matricula_id',
            'notas.*.calificacion' => 'nullable|numeric|min:0|max:20',
            'notas.*.observaciones' => 'nullable|string|max:255',
        ]);

        // Verificamos si existe una sesión de edición de notas válida
        // Esto impide que se envíen formularios sin haber pasado por la selección de curso/asignatura
        $edicionNotas = session('edicion_notas');
        if (!$edicionNotas) {
            return redirect()->route('notas.inicio')->with('error', 'No se puede acceder directamente a esta función. Por favor, seleccione un curso y una asignatura.');
        }

        // Verificamos que la sesión no haya expirado (15 minutos)
        // Esto añade una capa de seguridad temporal
        if (now()->timestamp - $edicionNotas['timestamp'] > 900) {
            // Eliminamos la sesión expirada
            session()->forget('edicion_notas');
            return redirect()->route('notas.inicio')->with('error', 'La sesión de edición ha expirado. Por favor, inicie nuevamente.');
        }

        // Verificar si el periodo está en curso
        $periodo = InfPeriodosEvaluacion::findOrFail($request->periodo_id);
        if ($periodo->estado !== 'En Curso') {
            return redirect()->route('notas.inicio')->with('error', 'Solo se pueden registrar notas para el periodo actual.');
        }

        DB::beginTransaction();
        try {
            foreach ($request->notas as $notaData) {
                if (isset($notaData['calificacion']) && $notaData['calificacion'] !== null) {
                    $nota = NotasFinalesPeriodo::updateOrCreate(
                        [
                            'matricula_id' => $notaData['matricula_id'],
                            'curso_asignatura_id' => $request->curso_asignatura_id,
                            'periodo_id' => $request->periodo_id,
                        ],
                        [
                            'promedio' => $notaData['calificacion'],
                            'observaciones' => $notaData['observaciones'] ?? null,
                            'estado' => 'Calculado',
                            'fecha_calculo' => now(),
                            'usuario_registro' => auth()->id(),
                        ]
                    );

                    // Verificar si todos los períodos tienen calificaciones para actualizar nota anual
                    $this->actualizarNotaAnualSiCompleto($notaData['matricula_id'], $request->curso_asignatura_id, $periodo, $notaData['observaciones'] ?? null);
                }
            }

            DB::commit();

            // Preparamos los datos para volver a mostrar la misma vista
            return $this->listado(new Request([
                'curso_id' => $edicionNotas['curso_id'],
                'asignatura_id' => $edicionNotas['asignatura_id']
            ]))->with('success', 'Calificaciones registradas correctamente.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('notas.inicio')->with('error', 'Error al guardar las calificaciones: ' . $e->getMessage());
        }
    }

    /**
     * Verifica si todas las notas de los períodos están registradas y actualiza la nota final anual
     * Ahora incluye el parámetro para las observaciones y el objeto del periodo actual
     */
    private function actualizarNotaAnualSiCompleto($matricula_id, $curso_asignatura_id, $periodoActual = null, $observaciones = null)
    {
        // Obtener la matrícula para conocer el curso
        $matricula = Matricula::findOrFail($matricula_id);
        $curso = InfCurso::findOrFail($matricula->curso_id);

        // Obtener todos los períodos del año lectivo
        $periodos = InfPeriodosEvaluacion::where('ano_lectivo_id', $curso->ano_lectivo_id)
            ->orderBy('fecha_inicio', 'asc')
            ->get();

        // Si no hay 4 períodos (bimestres), no podemos calcular nota final
        if ($periodos->count() != 4) {
            return;
        }

        // Verificar si hay notas registradas para todos los períodos
        $notasPorPeriodo = NotasFinalesPeriodo::where('matricula_id', $matricula_id)
            ->where('curso_asignatura_id', $curso_asignatura_id)
            ->get();

        // Si no tenemos exactamente 4 notas (una por cada período), no podemos calcular
        if ($notasPorPeriodo->count() != 4) {
            return;
        }

        // Calcular promedio final
        $sumaNotas = $notasPorPeriodo->sum('promedio');
        $promedioFinal = $sumaNotas / 4;

        // Determinar si aprobó o reprobó (nota mínima aprobatoria: 11)
        $estado = $promedioFinal >= 11 ? 'Aprobado' : 'Reprobado';

        // Determinar qué observación usar:
        // 1. Si estamos en el cuarto período y hay observaciones, usamos las del período actual
        // 2. Si no, generamos una observación automática
        $observacionFinal = null;

        if ($periodoActual && $periodoActual->nombre === $periodos->last()->nombre && !empty($observaciones)) {
            // Es el último período y tenemos observaciones, las usamos
            $observacionFinal = $observaciones;
        } else {
            // Generamos observación automática
            $observacionFinal = 'Calculado automáticamente con el promedio de los 4 bimestres';
        }

        // Actualizar o crear registro en notasfinalesanuales
        NotasFinalesAnuales::updateOrCreate(
            [
                'matricula_id' => $matricula_id,
                'curso_asignatura_id' => $curso_asignatura_id,
            ],
            [
                'promedio_final' => $promedioFinal,
                'estado' => $estado,
                'observaciones' => $observacionFinal,
                'fecha_registro' => now(),
                'usuario_registro' => auth()->id(),
            ]
        );
    }

    // Agregar este nuevo método para obtener asignaturas por curso
    public function getAsignaturasPorCurso(Request $request)
    {
        $request->validate([
            'curso_id' => 'required|exists:cursos,curso_id'
        ]);

        $query = DB::table('cursoasignaturas')
            ->join('asignaturas', 'cursoasignaturas.asignatura_id', '=', 'asignaturas.asignatura_id')
            ->where('cursoasignaturas.curso_id', $request->curso_id)
            ->select('asignaturas.asignatura_id', 'asignaturas.nombre', 'asignaturas.codigo');

        // Si el usuario es profesor, filtrar solo sus asignaturas
        if (auth()->user()->rol === 'Profesor') {
            $profesor_id = auth()->user()->profesor_id;
            $query->where('cursoasignaturas.profesor_id', $profesor_id);
        }

        $asignaturas = $query->orderBy('asignaturas.nombre')->get();

        return response()->json($asignaturas);
    }

    /**
     * Muestra la vista para buscar estudiantes - SOLO ADMINISTRADORES
     */
    public function buscarEstudiante()
    {
        // Verificar que solo los administradores puedan acceder
        if (auth()->user()->rol !== 'Administrador') {
            return redirect()->route('rutarrr1')
                ->with('error', 'No tiene permisos para acceder a la consulta general de notas. Esta función es exclusiva para administradores.');
        }

        return view('cnotas.consulta');
    }

    /**
     * Busca estudiantes por nombre o DNI mediante AJAX - SOLO ADMINISTRADORES
     */
    public function buscarEstudianteAjax(Request $request)
    {
        // Verificar que solo los administradores puedan hacer búsquedas
        if (auth()->user()->rol !== 'Administrador') {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $query = $request->get('query');

        if(strlen($query) < 3) {
            return response()->json([]);
        }

        $estudiantes = InfEstudiante::where('estado', 'Activo')
            ->where(function($q) use ($query) {
                $q->where('nombres', 'like', "%{$query}%")
                  ->orWhere('apellidos', 'like', "%{$query}%")
                  ->orWhere('dni', 'like', "%{$query}%");
            })
            ->limit(10)
            ->get(['estudiante_id', 'nombres', 'apellidos', 'dni']);

        return response()->json($estudiantes);
    }

    /**
     * Autoriza la visualización de notas para un estudiante - SOLO ADMINISTRADORES
     * y establece la sesión para permitir acceso
     */
    public function autorizarVerEstudiante(Request $request)
    {
        // Verificar que solo los administradores puedan autorizar
        if (auth()->user()->rol !== 'Administrador') {
            return redirect()->route('rutarrr1')
                ->with('error', 'No tiene permisos para realizar esta acción.');
        }

        $request->validate([
            'estudiante_id' => 'required|exists:estudiantes,estudiante_id',
        ]);

        // Guardar en sesión que se ha autorizado la visualización de este estudiante
        session(['ver_notas_estudiante' => [
            'estudiante_id' => $request->estudiante_id,
            'timestamp' => now()->timestamp
        ]]);

        return redirect()->route('notas.estudiante', ['id' => $request->estudiante_id]);
    }

    /**
     * Muestra las notas del estudiante seleccionado
     * Verifica que exista una sesión válida de autorización o que sea un representante autorizado
     */
    public function verNotasEstudiante($id)
    {
        $esAccesoAutorizado = false;
        $rolUsuario = auth()->user()->rol;

        // Si el usuario es representante, verificar si tiene permiso para este estudiante
        if ($rolUsuario === 'Representante' && auth()->user()->representante_id) {
            $esRepresentanteDelEstudiante = InfEstudianteRepresentante::where('representante_id', auth()->user()->representante_id)
                ->where('estudiante_id', $id)
                ->exists();

            if ($esRepresentanteDelEstudiante) {
                $esAccesoAutorizado = true;
            } else {
                // El representante no tiene acceso a este estudiante
                return redirect()->route('rutarrr1')
                    ->with('error', 'No tiene permisos para ver las calificaciones de este estudiante. Solo puede ver las notas de los estudiantes que representa.');
            }
        }

        // Si el usuario es administrador, verificar la sesión de autorización
        elseif ($rolUsuario === 'Administrador') {
            // Verificar que exista una sesión válida
            $autorizacion = session('ver_notas_estudiante');
            if (!$autorizacion || $autorizacion['estudiante_id'] != $id) {
                return redirect()->route('notas.consulta')
                    ->with('info', 'Por favor, busque y seleccione un estudiante para ver sus calificaciones.');
            }

            // Verificar que la sesión no haya expirado (15 minutos)
            if (now()->timestamp - $autorizacion['timestamp'] > 900) {
                session()->forget('ver_notas_estudiante');
                return redirect()->route('notas.consulta')
                    ->with('error', 'La sesión ha expirado. Por favor, busque nuevamente al estudiante.');
            }

            $esAccesoAutorizado = true;
        }

        // Si el usuario es docente u otro rol sin permisos
        else {
            return redirect()->route('rutarrr1')
                ->with('error', 'No tiene permisos para ver las calificaciones de estudiantes. Esta función está disponible solo para administradores y representantes.');
        }

        // Si no tiene acceso autorizado, redirigir al home
        if (!$esAccesoAutorizado) {
            return redirect()->route('rutarrr1')
                ->with('error', 'No tiene permisos para acceder a esta información.');
        }

        // Obtener datos del estudiante
        $estudiante = InfEstudiante::findOrFail($id);

        // Obtener las matrículas del estudiante
        $matriculas = Matricula::where('estudiante_id', $id)
            ->whereIn('estado', ['Matriculado', 'Pre-inscrito'])
            ->orderBy('fecha_matricula', 'desc')
            ->get();

        if($matriculas->isEmpty()) {
            $redirectRoute = ($rolUsuario === 'Administrador') ? 'notas.consulta' : 'rutarrr1';
            return redirect()->route($redirectRoute)
                ->with('error', 'El estudiante no tiene matrículas registradas');
        }

        // Obtener la matrícula más reciente
        $matriculaActual = $matriculas->first();
        $curso = InfCurso::findOrFail($matriculaActual->curso_id);

        // Obtener los periodos de evaluación
        $periodos = InfPeriodosEvaluacion::where('ano_lectivo_id', $curso->ano_lectivo_id)
            ->orderBy('fecha_inicio', 'asc')
            ->get();

        // Obtener todas las asignaturas del curso
        $cursoAsignaturas = CursoAsignatura::with('asignatura')
            ->where('curso_id', $curso->curso_id)
            ->get();

        // Preparar el array para almacenar las notas
        $asignaturasNotas = [];

        foreach($cursoAsignaturas as $cursoAsignatura) {
            $notasPeriodo = [];
            $sumaNotas = 0;
            $periodosConcalificacion = 0;

            foreach($periodos as $periodo) {
                // Buscar la nota para este periodo
                $nota = NotasFinalesPeriodo::where('matricula_id', $matriculaActual->matricula_id)
                    ->where('curso_asignatura_id', $cursoAsignatura->curso_asignatura_id)
                    ->where('periodo_id', $periodo->periodo_id)
                    ->first();

                $notaValor = $nota ? $nota->promedio : null;

                $notasPeriodo[] = [
                    'periodo_id' => $periodo->periodo_id,
                    'nombre' => $periodo->nombre,
                    'nota' => $notaValor,
                    'observaciones' => $nota ? $nota->observaciones : null // Incluir observaciones
                ];

                if($notaValor !== null) {
                    $sumaNotas += $notaValor;
                    $periodosConcalificacion++;
                }
            }

            // Calcular promedio SOLO si se tienen las notas de todos los periodos (4 bimestres)
            $promedio = ($periodosConcalificacion === count($periodos) && count($periodos) === 4) ?
                number_format($sumaNotas / 4, 2) : null;

            $asignaturasNotas[] = [
                'asignatura' => $cursoAsignatura->asignatura,
                'notas_periodos' => $notasPeriodo,
                'promedio' => $promedio
            ];
        }

        // Obtener la nota final anual si existe - indexar por asignatura_id en lugar de curso_asignatura_id
        $notasFinalesAnuales = NotasFinalesAnuales::where('matricula_id', $matriculaActual->matricula_id)
            ->join('cursoasignaturas', 'notasfinalesanuales.curso_asignatura_id', '=', 'cursoasignaturas.curso_asignatura_id')
            ->get()
            ->keyBy('asignatura_id'); // Cambiar el índice a asignatura_id

        return view('cnotas.estudiante', compact(
            'estudiante',
            'matriculaActual',
            'curso',
            'periodos',
            'asignaturasNotas',
            'notasFinalesAnuales'
        ));
    }

    /**
     * Maneja el acceso directo a la URL de edición de notas
     */
    public function redireccionarEditar()
    {
        return redirect()->route('notas.inicio')
            ->with('info', 'Para editar notas, primero seleccione un curso y una asignatura.');
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

        // Obtener los estudiantes representados con sus datos de matrícula más reciente
        $estudiantesRepresentados = InfEstudianteRepresentante::where('representante_id', $representante_id)
            ->with(['estudiante' => function ($query) {
                $query->where('estado', 'Activo');
            }])
            ->get()
            ->map(function ($relacion) {
                // Si el estudiante no existe o no está activo, omitirlo
                if (!$relacion->estudiante) {
                    return null;
                }

                // Obtener la matrícula más reciente del estudiante
                $matricula = Matricula::where('estudiante_id', $relacion->estudiante_id)
                    ->whereIn('estado', ['Matriculado', 'Pre-inscrito'])
                    ->orderBy('fecha_matricula', 'desc')
                    ->first();

                // Obtener el curso de la matrícula
                $curso = $matricula ? InfCurso::find($matricula->curso_id) : null;

                // Devolver datos del estudiante y su matrícula
                return [
                    'estudiante' => $relacion->estudiante,
                    'es_principal' => $relacion->es_principal,
                    'viveConEstudiante' => $relacion->viveConEstudiante,
                    'matricula' => $matricula,
                    'curso' => $curso
                ];
            })
            ->filter() // Eliminar valores nulos
            ->sortBy(function ($item) {
                // Ordenar por apellido del estudiante
                return $item['estudiante']->apellidos;
            });

        return view('cnotas.mis-estudiantes', compact('estudiantesRepresentados'));
    }
}
