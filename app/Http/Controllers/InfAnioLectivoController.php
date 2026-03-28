<?php

namespace App\Http\Controllers;

use App\Models\InfAnioLectivo;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Carbon\Carbon;

class InfAnioLectivoController extends Controller
{
    const PAGINATION = 6;

    public function index(Request $request)
    {
        $buscarpor = $request->buscarpor;
        $query = InfAnioLectivo::query();

        if ($buscarpor) {
            $query->where('nombre', 'like', "%$buscarpor%")
                ->orWhere('estado', 'like', "%$buscarpor%");
        }

        $anoslectivos = $query->orderBy('ano_lectivo_id', 'desc')->paginate(5);

        if ($request->ajax()) {
            return view('ceinformacion.añolectivo.tabla', compact('anoslectivos'))->render();
        }

        return view('ceinformacion.añolectivo.registrar', compact('anoslectivos', 'buscarpor'));
    }
    public function create()
    {
        return view('ceinformacion.añolectivo.nuevo.nuevo');
    }

    public function store(Request $request)
    {
        $data = $request->validate(
            [
                'nombre' => 'required|max:100|min:4|unique:anoslectivos,nombre',
                'fecha_inicio' => 'required|date',
                'fecha_fin' => 'required|date|after:fecha_inicio',
                'estado' => 'required|in:Activo,Finalizado,Planificación',
                'descripcion' => 'nullable|max:500'
            ],
            [
                // Mensajes para nombre
                'nombre.required' => 'Ingrese el nombre del Año Lectivo',
                'nombre.max' => 'El nombre no puede exceder los 100 caracteres',
                'nombre.min' => 'El nombre debe tener al menos 4 caracteres',
                'nombre.unique' => 'Ya existe un año lectivo con ese nombre',

                // Mensajes para fecha_inicio
                'fecha_inicio.required' => 'Ingrese la fecha de inicio del Año Lectivo',
                'fecha_inicio.date' => 'Ingrese una fecha válida para el inicio',

                // Mensajes para fecha_fin
                'fecha_fin.required' => 'Ingrese la fecha de fin del Año Lectivo',
                'fecha_fin.date' => 'Ingrese una fecha válida para el fin',
                'fecha_fin.after' => 'La fecha de fin debe ser posterior a la fecha de inicio',

                // Mensajes para estado
                'estado.required' => 'Seleccione el estado del Año Lectivo',
                'estado.in' => 'El estado debe ser Activo o Inactivo',

                // Mensajes para descripcion
                'descripcion.max' => 'La descripción no puede exceder los 500 caracteres',
            ]
        );

        $anio = new InfAnioLectivo();
        $anio->nombre = $data['nombre'];
        $anio->fecha_inicio = $data['fecha_inicio'];
        $anio->fecha_fin = $data['fecha_fin'];
        $anio->estado = $data['estado'];
        $anio->descripcion = $data['descripcion'];
        $anio->save();

        return redirect()->route('aniolectivo.index')->with('success', 'Año Lectivo registrado exitosamente.');
    }

    public function edit($id)
    {
        $anolectivo = InfAnioLectivo::findOrFail($id);
        return view('ceinformacion.añolectivo.editar', compact('añolectivo'));
    }

    public function update(Request $request, $id)
    {
        $anio = InfAnioLectivo::findOrFail($id);

        // No permitir editar un año ya finalizado
        if ($anio->estado === 'Finalizado') {
            return redirect()->route('aniolectivo.index')->with('error', 'No se puede editar un año lectivo que ya está finalizado.');
        }

        $data = $request->validate([
            'nombre' => 'required|max:100|min:4|unique:anoslectivos,nombre,' . $anio->ano_lectivo_id . ',ano_lectivo_id',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'estado' => 'required|in:Activo,Finalizado,Planificación',
            'descripcion' => 'nullable|max:500'
        ]);

        // Validar si intenta marcar como finalizado antes de la fecha de fin
        if ($data['estado'] === 'Finalizado') {
            $fechaFin = \Carbon\Carbon::parse($data['fecha_fin']);
            if (now()->lt($fechaFin)) {
                return redirect()->back()->with('error', 'No se puede finalizar el año lectivo antes de la fecha de fin.');
            }
        }

        $anio->update($data);

        return redirect()->route('aniolectivo.index')->with('success', 'Año Lectivo actualizado correctamente.');
    }



    public function destroy($id)
    {
        try {
            $anio = InfAnioLectivo::findOrFail($id);
            $anio->delete();

            return redirect()->route('aniolectivo.index')->with('success', 'Año lectivo eliminado correctamente.');
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) {
                return redirect()->route('aniolectivo.index')->with('error', 'No se puede eliminar este año lectivo porque tiene registros relacionados (como conceptos de pago).');
            }

            return redirect()->route('aniolectivo.index')->with('error', 'Ocurrió un error al intentar eliminar el año lectivo.');
        }
    }
}
