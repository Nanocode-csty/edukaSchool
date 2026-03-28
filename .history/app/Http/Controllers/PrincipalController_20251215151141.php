<?php

namespace App\Http\Controllers;

use App\Models\Comunicado;
use Illuminate\Http\Request;

class PrincipalController extends Controller
{
    public function index()
    {

        $flyer = Comunicado::all();

        return view('cplantilla.bprincipal', compact('flyer'));
    }

    public function verPerfil()
    {
        return view('cplantilla.cusuario.perfilUsuario');
    }
}
