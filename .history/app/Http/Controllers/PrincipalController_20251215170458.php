<?php

namespace App\Http\Controllers;

use App\Models\Comunicado;
use Illuminate\Http\Request;

class PrincipalController extends Controller
{
    public function index()
    {
        // Filtrar comunicados por fecha_inicio y fecha_fin
        // Solo mostrar comunicados que estén vigentes (fecha actual entre fecha_inicio y fecha_fin)
        $hoy = now();
        $flyer = Comunicado::where('fecha_inicio', '<=', $hoy)
                          ->where('fecha_fin', '>=', $hoy)
                          ->orderBy('fecha_inicio', 'desc')
                          ->get();

        return view('cplantilla.bprincipal', compact('flyer'));
    }

    public function verPerfil()
    {
        return view('cplantilla.cusuario.perfilUsuario');
    }
}
