<?php

namespace App\Http\Controllers;

use App\Models\Comunicado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PrincipalController extends Controller
{
    public function index()
    {
        // Optimización con caché para mejor performance
        $hoy = now();
        $userRole = auth()->user()->rol ?? 'General';

        // Cache key basado en fecha y rol de usuario
        $cacheKey = "comunicados.{$userRole}." . $hoy->format('Y-m-d');

        // Cache por 1 hora (3600 segundos) ya que los comunicados cambian con frecuencia
        $flyer = Cache::remember($cacheKey, 3600, function () use ($hoy, $userRole) {
            return Comunicado::select(['idComunicado', 'descripcion', 'flyer_url', 'fecha_inicio', 'fecha_fin', 'publico'])
                          ->where('fecha_inicio', '<=', $hoy)
                          ->where('fecha_fin', '>=', $hoy)
                          ->where(function($query) use ($userRole) {
                              $query->where('publico', 'General')
                                    ->orWhere('publico', $userRole);
                          })
                          ->orderBy('fecha_inicio', 'desc')
                          ->limit(5)
                          ->get();
        });

        // Usuario ya está cargado por el middleware de auth, no hacer queries adicionales
        // Los roles se cargan automáticamente con el accessor

        return view('cplantilla.bprincipal', compact('flyer'));
    }

    public function verPerfil()
    {
        return view('cplantilla.cusuario.perfilUsuario');
    }
}
