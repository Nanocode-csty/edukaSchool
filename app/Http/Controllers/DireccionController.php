<?php

namespace App\Http\Controllers;

use App\Models\DireccionRegion;
use App\Models\DireccionProvincia;
use App\Models\DireccionDistrito;
use Illuminate\Http\Request;

class DireccionController extends Controller
{
       public function regiones()
    {
        return DireccionRegion::orderBy('nombre')->get();
    }

    public function provincias($idRegion)
    {
        return DireccionProvincia::where('idRegion', $idRegion)
            ->orderBy('nombre')
            ->get();
    }

    public function distritos($idProvincia)
    {
        return DireccionDistrito::where('idProvincia', $idProvincia)
            ->orderBy('nombre')
            ->get();
    }
}
