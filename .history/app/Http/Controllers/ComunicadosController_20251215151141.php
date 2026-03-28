<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comunicado;

class ComunicadosController extends Controller
{
    const cont = 6;

    public function index()
    {


        $items = Comunicado::all();
        return view('csistema.comunicado.index', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'descripcion'   => 'nullable|string|max:100',
            'flyer'         => 'required|image|mimes:jpg,jpeg,png,webp,avif|max:4096',
            'fecha_inicio'  => 'required|date',
            'fecha_fin'     => 'required|date|after_or_equal:fecha_inicio',
            'publico'       => 'required|in:Profesor,Representante,General',
        ]);

        $data = $request->only([
            'descripcion',
            'fecha_inicio',
            'fecha_fin',
            'publico'
        ]);

        // Guardar imagen
        if ($request->hasFile('flyer')) {
            $file = $request->file('flyer');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('flyer', $filename, 'public');

            $data['flyer_url'] = $filename;
        }

        Comunicado::create($data);

        return redirect()->route('comunicado.index')
            ->with('success', 'Comunicado creado correctamente.');
    }
}
