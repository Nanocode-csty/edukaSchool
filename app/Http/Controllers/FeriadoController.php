<?php

namespace App\Http\Controllers;

use App\Models\Feriado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class FeriadoController extends Controller
{
    public function __construct()
    {
        // Middleware se aplicará en las rutas
    }

    // Listado de feriados
    public function index(Request $request)
    {
        // Verificar que sea administrador
        if (Auth::user()->rol !== 'Administrador') {
            abort(403, 'Acceso denegado. Solo administradores pueden gestionar feriados.');
        }

        $query = Feriado::with('creador');

        // Filtros
        if ($request->filled('anio')) {
            $query->whereYear('fecha', $request->anio);
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('activo') && $request->activo !== '') {
            $query->where('activo', $request->activo === '1');
        }

        if ($request->filled('buscar')) {
            $search = $request->buscar;
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('descripcion', 'like', "%{$search}%")
                  ->orWhere('tipo', 'like', "%{$search}%");
            });
        }

        $items = $query->orderBy('fecha', 'desc')->paginate(15);

        // Estadísticas
        $totalFeriados = Feriado::count();
        $feriadosActivos = Feriado::activos()->count();
        $feriadosRecuperables = Feriado::recuperables()->count();
        $feriadosEsteAnio = Feriado::whereYear('fecha', date('Y'))->count();

        return view('feriados.index', compact(
            'items',
            'totalFeriados',
            'feriadosActivos',
            'feriadosRecuperables',
            'feriadosEsteAnio'
        ));
    }

    // Formulario crear
    public function create()
    {
        // Verificar que sea administrador
        if (Auth::user()->rol !== 'Administrador') {
            abort(403, 'Acceso denegado. Solo administradores pueden gestionar feriados.');
        }

        $tipos = [
            'Nacional' => 'Nacional',
            'Regional' => 'Regional',
            'Local' => 'Local',
            'Religioso' => 'Religioso',
            'Otro' => 'Otro'
        ];

        return view('feriados.create', compact('tipos'));
    }

    // Guardar
    public function store(Request $request)
    {
        // Verificar que sea administrador
        if (Auth::user()->rol !== 'Administrador') {
            abort(403, 'Acceso denegado. Solo administradores pueden gestionar feriados.');
        }
        $rules = [
            'nombre' => 'required|string|max:255',
            'fecha' => 'required|date|after:today',
            'tipo' => 'required|string|max:50',
            'recuperable' => 'nullable|boolean',
            'descripcion' => 'nullable|string|max:500',
            'ubicacion' => 'nullable|string|max:255',
            'activo' => 'nullable|boolean'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Verificar que no exista un feriado en la misma fecha
        $existe = Feriado::where('fecha', $request->fecha)->exists();
        if ($existe) {
            return redirect()->back()
                ->with('error', 'Ya existe un feriado registrado para esta fecha.')
                ->withInput();
        }

        Feriado::create([
            'nombre' => $request->nombre,
            'fecha' => $request->fecha,
            'tipo' => $request->tipo,
            'recuperable' => $request->has('recuperable'),
            'descripcion' => $request->descripcion,
            'ubicacion' => $request->ubicacion,
            'activo' => $request->has('activo'),
            'creado_por' => Auth::id()
        ]);

        return redirect()->route('feriados.index')
            ->with('success', 'Feriado creado correctamente.');
    }

    // Mostrar
    public function show($id)
    {
        // Verificar que sea administrador
        if (Auth::user()->rol !== 'Administrador') {
            abort(403, 'Acceso denegado. Solo administradores pueden gestionar feriados.');
        }

        $item = Feriado::with('creador')->findOrFail($id);

        return view('feriados.show', compact('item'));
    }

    // Formulario editar
    public function edit($id)
    {
        // Verificar que sea administrador
        if (Auth::user()->rol !== 'Administrador') {
            abort(403, 'Acceso denegado. Solo administradores pueden gestionar feriados.');
        }

        $item = Feriado::findOrFail($id);

        $tipos = [
            'Nacional' => 'Nacional',
            'Regional' => 'Regional',
            'Local' => 'Local',
            'Religioso' => 'Religioso',
            'Otro' => 'Otro'
        ];

        return view('feriados.edit', compact('item', 'tipos'));
    }

    // Actualizar
    public function update(Request $request, $id)
    {
        // Verificar que sea administrador
        if (Auth::user()->rol !== 'Administrador') {
            abort(403, 'Acceso denegado. Solo administradores pueden gestionar feriados.');
        }

        $item = Feriado::findOrFail($id);

        $rules = [
            'nombre' => 'required|string|max:255',
            'fecha' => 'required|date',
            'tipo' => 'required|string|max:50',
            'recuperable' => 'nullable|boolean',
            'descripcion' => 'nullable|string|max:500',
            'ubicacion' => 'nullable|string|max:255',
            'activo' => 'nullable|boolean'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Verificar que no exista otro feriado en la misma fecha (excluyendo el actual)
        $existe = Feriado::where('fecha', $request->fecha)
            ->where('id', '!=', $id)
            ->exists();

        if ($existe) {
            return redirect()->back()
                ->with('error', 'Ya existe otro feriado registrado para esta fecha.')
                ->withInput();
        }

        $item->update([
            'nombre' => $request->nombre,
            'fecha' => $request->fecha,
            'tipo' => $request->tipo,
            'recuperable' => $request->has('recuperable'),
            'descripcion' => $request->descripcion,
            'ubicacion' => $request->ubicacion,
            'activo' => $request->has('activo')
        ]);

        return redirect()->route('feriados.index')
            ->with('success', 'Feriado actualizado correctamente.');
    }

    // Eliminar
    public function destroy(Request $request, $id)
    {
        // Verificar que sea administrador
        if (Auth::user()->rol !== 'Administrador') {
            abort(403, 'Acceso denegado. Solo administradores pueden gestionar feriados.');
        }

        $item = Feriado::findOrFail($id);

        // Verificar si el feriado tiene sesiones de clase asociadas
        $sesionesAfectadas = \App\Models\SesionClase::whereDate('fecha', $item->fecha)->count();

        if ($sesionesAfectadas > 0) {
            return redirect()->back()
                ->with('error', "No se puede eliminar este feriado porque hay {$sesionesAfectadas} sesiones de clase programadas para esta fecha.");
        }

        $item->delete();

        return redirect()->route('feriados.index')
            ->with('success', 'Feriado eliminado correctamente.');
    }



    // API para obtener feriados por año
    public function getByAnio($anio)
    {
        $feriados = Feriado::activos()
            ->whereYear('fecha', $anio)
            ->orderBy('fecha')
            ->get(['id', 'nombre', 'fecha', 'tipo']);

        return response()->json($feriados);
    }
}
