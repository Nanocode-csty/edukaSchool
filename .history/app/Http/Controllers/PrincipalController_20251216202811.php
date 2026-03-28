<?php

namespace App\Http\Controllers;

use App\Models\Comunicado;
use Illuminate\Http\Request;

class PrincipalController extends Controller
{
    public function index()
    {
        // Optimización extrema para mejor performance
        $hoy = now();

        // Consulta ultra optimizada con solo campos necesarios
        $flyer = Comunicado::select(['idComunicado', 'descripcion', 'flyer_url', 'fecha_inicio', 'fecha_fin', 'publico'])
                          ->where('fecha_inicio', '<=', $hoy)
                          ->where('fecha_fin', '>=', $hoy)
                          ->where(function($query) {
                              $query->where('publico', 'General')
                                    ->orWhere('publico', auth()->user()->rol ?? 'General');
                          })
                          ->orderBy('fecha_inicio', 'desc')
                          ->limit(5) // Reducir aún más para performance
                          ->get();

        // Usuario ya está cargado por el middleware de auth, no hacer queries adicionales
        // Los roles se cargan automáticamente con el accessor

        return view('cplantilla.bprincipal', compact('flyer'));
    }

    public function verPerfil()
    {
        return view('cplantilla.cusuario.perfilUsuario');
    }
}
