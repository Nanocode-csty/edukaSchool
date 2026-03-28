<?php

namespace App\Http\Controllers;

use App\Models\Matricula;
use App\Models\InfEstudiante;
use App\Models\InfGrado;
use App\Models\InfPago;
use App\Models\InfSeccion;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MatriculaController extends Controller
{
    const PAGINATION = 10;

    public function index(Request $request)
    {
        try {
            $buscarpor = $request->get('buscarpor');
            $filtro = $request->get('filtro');

            $matricula = Matricula::with(['estudiante', 'grado', 'seccion'])
                ->where('numero_matricula', 'like', "%{$buscarpor}%")
                ->delAnio();

            switch ($filtro) {
                case 'fecha_desc':
                    $matricula->orderBy('fecha_matricula', 'desc');
                    break;
                case 'fecha_asc':
                    $matricula->orderBy('fecha_matricula', 'asc');
                    break;
                case 'numero_desc':
                    $matricula->orderBy('numero_matricula', 'desc');
                    break;
                case 'numero_asc':
                    $matricula->orderBy('numero_matricula', 'asc');
                    break;
                case 'preinscrito':
                    $matricula->where('estado', 'PRE-INSCRITO');
                    break;
                case 'matriculado':
                    $matricula->where('estado', 'MATRICULADO');
                    break;
                default:
                    $matricula->orderBy('fecha_matricula', 'desc');
                    break;
            }

            $matricula = $matricula->paginate($this::PAGINATION);

            if ($request->ajax()) return view('cmatricula.matricula', compact('matricula'))->render();

            return view('cmatricula.index', compact('matricula', 'buscarpor'));
        } catch (\Exception $e) {
            return view('cmatricula.index')->with('error', 'Ups. Error al cargar las matrículas.');
        }
    }

    public function create(Request $request)
    {
        try {
            $dni = $request->get('dni');
            $estudiante = null;
            $grado = null;
            $secciones = collect();
            $mensaje = null;
            $esNuevo = false;
            $error = null;

            // Si hay DNI, buscar estudiante
            if ($dni) {
                $estudiante = InfEstudiante::where('dni', $dni)
                    ->where('estado', 'Activo')
                    ->first();

                if (!$estudiante) {

                    $error = 'DNI no existe o estudiante inactivo';
                    return back()->with('error', $error);
                } else {
                    // Verificar si es estudiante nuevo o tiene matrículas previas
                    $esNuevo = Matricula::esEstudianteNuevo($estudiante->estudiante_id);
                    $grado = Matricula::calcularSiguienteGrado($estudiante->estudiante_id);

                    if (!$grado) {
                        $error = 'No se pudo determinar el grado correspondiente para este estudiante';
                    } else {
                        // Obtener secciones disponibles para el grado
                        $secciones = Matricula::getSeccionesDisponibles($grado->grado_id);

                        if ($secciones->count() === 0) {
                            $error = 'No hay secciones disponibles para el grado ' . $grado->nombre;
                        } else {
                            $mensaje = $esNuevo
                                ? 'Verifica si todos tus documentos están en orden'
                                : "Estimado estudiante, pasas a {$grado->nombre}";
                        }
                    }
                }
            }
            if ($request->ajax()) {
                return view('cmatricula.registrarMatricula', compact(
                    'estudiante',
                    'grado',
                    'secciones',
                    'mensaje',
                    'esNuevo',
                    'error',
                    'dni'
                ));
            }

            return view('cmatricula.nuevo', compact(
                'estudiante',
                'grado',
                'secciones',
                'mensaje',
                'esNuevo',
                'error',
                'dni'
            ));
        } catch (Exception $e) {
            return redirect()->route('matriculas.index')
                ->with('error', 'Ups. Error al cargar el formulario. Estamos trabajando en ello.');
        }
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'estudiante_id' => 'required|exists:estudiantes,estudiante_id',
                'idGrado' => 'required|exists:grados,grado_id',
                'idSeccion' => 'required|exists:secciones,seccion_id',
                'observaciones' => 'nullable|string|max:500',
            ], [
                'estudiante_id.required' => 'Debe buscar y seleccionar un estudiante',
                'estudiante_id.exists' => 'El estudiante seleccionado no es válido',
                'idGrado.required' => 'Debe seleccionar un grado',
                'idGrado.exists' => 'El grado seleccionado no es válido',
                'idSeccion.required' => 'Debe seleccionar una sección',
                'idSeccion.exists' => 'La sección seleccionada no es válida',
                'observaciones.max' => 'Las observaciones son demasiado largas (máximo 500 caracteres)'
            ]);

            // Verificar que no tenga matrícula activa este año
            $matriculaExistente = Matricula::where('estudiante_id', $request->estudiante_id)
                ->where('anio_academico', date('Y'))
                ->whereIn('estado', ['Pre-inscrito', 'Matriculado'])
                ->first();

            if ($matriculaExistente) {
                return redirect()
                    ->route('matriculas.create') // va al formulario
                    ->with('error', 'Ya existe una matrícula activa para este estudiante.')
                    ->withInput([]); // vacía los campos, no mantiene nada
            }

            // Verificar capacidad de la sección
            $seccionesDisponibles = Matricula::getSeccionesDisponibles($request->idGrado);
            $seccionSeleccionada = $seccionesDisponibles->where('seccion.seccion_id', $request->idSeccion)->first();


            if (!$seccionSeleccionada || $seccionSeleccionada['disponibles'] <= 0) {
                return back()->withErrors([
                    'idSeccion' => 'La sección seleccionada ya no tiene cupos disponibles.',
                ])->withInput();
            }

            // Generar número de matrícula automático
            $numeroMatricula = Matricula::generarNumeroMatricula();

            // Crear nueva matrícula
            $matricula = new Matricula();
            $matricula->estudiante_id = $request->estudiante_id;
            $matricula->numero_matricula = $numeroMatricula;
            $matricula->fecha_matricula = now(); // Fecha automática
            $matricula->estado = 'Pre-inscrito';
            $matricula->observaciones = $request->observaciones;
            $matricula->usuario_registro = auth()->user()->usuario_id ?? 1;
            $matricula->idGrado = $request->idGrado;
            $matricula->idSeccion = $request->idSeccion;
            $matricula->anio_academico = date('Y');
            $matricula->save();

            /**
             * Crear el registro de pago automáticamente
             * Ejemplo: concepto = matrícula, monto fijo
             */
            $pago = new InfPago();
            $pago->matricula_id = $matricula->matricula_id;

            // Obtenemos el grado del estudiante
            $nivelEstudiante = InfGrado::where('grado_id', $matricula->idGrado)->first();

            if ($nivelEstudiante) {
                // Buscamos el concepto de pago según el nivel (Primaria / Secundaria)
                $concepto = DB::table('conceptospago')
                    ->where('nivel_id', $nivelEstudiante->nivel_id)
                    ->first();

                if ($concepto) {
                    $pago->concepto_id = $concepto->concepto_id; // ID del concepto correcto
                    $pago->monto = $concepto->monto;             // 💰 monto dinámico desde BD
                } else {
                    throw new \Exception("No existe un concepto de pago para el nivel {$nivelEstudiante->nivel_id}");
                }
            } else {
                throw new \Exception("No se encontró el grado del estudiante para generar el pago.");
            }

            $pago->fecha_vencimiento = Carbon::now()->addDays(7); // vence en 7 días
            $pago->estado = 'Pendiente';
            $pago->usuario_registro = auth()->user()->usuario_id ?? 1;
            $pago->observaciones = 'Pago por derecho de matrícula';
            $pago->save();

            return redirect()
                ->route('matriculas.index')
                ->with('success', "Matrícula {$numeroMatricula} registrada satisfactoriamente");
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        } catch (Exception $e) {
            return back()
                ->with('error', 'ERROR ESPECÍFICO: ' . $e->getMessage() . ' - Línea: ' . $e->getLine() . ' - Archivo: ' . basename($e->getFile()))
                ->withInput();
        }
    }

    public function show($id)
    {
        try {
            $matricula = Matricula::with(['estudiante', 'grado', 'seccion'])->findOrFail($id);
            return view('cmatricula.mostrar', compact('matricula'));
        } catch (Exception $e) {
            return redirect()->route('matriculas.index')
                ->with('error', 'Ups. Error al cargar los detalles. Estamos trabajando en ello.');
        }
    }
    public function edit($id)
    {
        try {
            $matricula = Matricula::with(['estudiante', 'grado', 'seccion'])->findOrFail($id);

            // No permitir editar si está matriculado
            if ($matricula->estado === 'Matriculado') {
                return redirect()->route('matriculas.index')
                    ->with('error', 'No se puede editar una matrícula con estado "Matriculado"');
            }

            $grados = InfGrado::with('nivel')->get();
            // Cargar todas las secciones disponibles para el grado actual
            $seccionesDisponibles = Matricula::getSeccionesDisponibles($matricula->idGrado);

            return view('cmatricula.editar', compact('matricula', 'grados', 'seccionesDisponibles'));
        } catch (\Exception $e) {
            return redirect()->route('matriculas.index')
                ->with('error', 'Ups. Error al cargar el formulario de edición. Estamos trabajando en ello.');
        }
    }


    public function update(Request $request, $id)
    {
        try {
            $matricula = Matricula::findOrFail($id);

            // No permitir actualizar si está matriculado
            if ($matricula->estado === 'Matriculado') {
                return redirect()->route('matriculas.index')
                    ->with('error', 'No se puede modificar una matrícula con estado "Matriculado"');
            }

            $data = $request->validate([
                'idGrado' => 'required|exists:grados,grado_id',
                'idSeccion' => 'required|exists:secciones,seccion_id',
                'estado' => 'required|in:Pre-inscrito,Matriculado,Anulado',
                'observaciones' => 'nullable|string|max:500',
            ], [
                'idGrado.required' => 'Debe seleccionar un grado',
                'idSeccion.required' => 'Debe seleccionar una sección',
                'estado.required' => 'Debe seleccionar un estado',
                'estado.in' => 'El estado seleccionado no es válido',
                'observaciones.max' => 'Las observaciones son demasiado largas (máximo 500 caracteres)'
            ]);

            $matricula->update([
                'idGrado' => $request->idGrado,
                'idSeccion' => $request->idSeccion,
                'estado' => $request->estado,
                'observaciones' => $request->observaciones,
            ]);

            return redirect()
                ->route('matriculas.index')
                ->with('success', 'Matrícula actualizada satisfactoriamente');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Ups. Error al actualizar la matrícula. Estamos trabajando en ello.')
                ->withInput();
        }
    }

    /**
     * Anular matrícula (reemplaza al destroy)
     */
    public function anular($id)
    {
        try {
            $matricula = Matricula::findOrFail($id);

            // Solo se puede anular si está en Pre-inscrito
            if ($matricula->estado !== 'Pre-inscrito') {
                return redirect()
                    ->route('matriculas.index')
                    ->with('error', 'Solo se pueden anular matrículas en estado "Pre-inscrito"');
            }

            $matricula->update(['estado' => 'Anulado']);

            return redirect()
                ->route('matriculas.index')
                ->with('success', "Matrícula N° {$matricula->numero_matricula} anulada satisfactoriamente");
        } catch (\Exception $e) {
            return redirect()
                ->route('matriculas.index')
                ->with('error', 'Ups. Error al anular la matrícula. Estamos trabajando en ello.');
        }
    }

    /**
     * Verificar disponibilidad de secciones para un grado
     */
    public function getSeccionesDisponibles(Request $request)
    {
        try {
            $gradoId = $request->get('grado_id');

            if (!$gradoId) {
                return response()->json([
                    'success' => false,
                    'message' => 'ID de grado requerido'
                ]);
            }

            $secciones = Matricula::getSeccionesDisponibles($gradoId);

            return response()->json([
                'success' => true,
                'secciones' => $secciones->values()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las secciones disponibles'
            ]);
        }
    }
}
