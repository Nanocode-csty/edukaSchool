<?php

namespace App\Http\Controllers;

use App\Models\Comunicado;
use Illuminate\Http\Request;

class PrincipalController extends Controller
{
    public function index()
    {
        // Optimizar consulta de comunicados con eager loading si es necesario
        $hoy = now();
        $flyer = Comunicado::where('fecha_inicio', '<=', $hoy)
                          ->where('fecha_fin', '>=', $hoy)
                          ->orderBy('fecha_inicio', 'desc')
                          ->limit(10) // Limitar resultados para mejor performance
                          ->get();

        // Verificar que el usuario tenga rol cargado (evitar N+1 queries)
        $usuario = auth()->user();
        if ($usuario && !$usuario->relationLoaded('persona')) {
            $usuario->load('persona.roles');
        }

        return view('cplantilla.bprincipal', compact('flyer'));
    }

    public function verPerfil()
    {
        return view('cplantilla.cusuario.perfilUsuario');
    }
}
