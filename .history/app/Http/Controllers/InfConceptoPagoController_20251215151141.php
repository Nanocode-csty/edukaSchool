<?php

namespace App\Http\Controllers;

use App\Models\InfConceptoPago;
use Illuminate\Http\Request;

class InfConceptoPagoController extends Controller
{
    const PAGINATION = 6;

    public function index(Request $request)
    {
        $buscarpor = $request->get('buscarpor');

        $conceptos = InfConceptoPago::join('niveleseducativos', 'conceptospago.nivel_id', '=', 'niveleseducativos.nivel_id')
            ->select('conceptospago.*', 'niveleseducativos.nombre as nivel_nombre')
            ->where('conceptospago.nombre', 'like', '%'.$buscarpor.'%')
            ->orWhere('conceptospago.descripcion', 'like', '%'.$buscarpor.'%')
            ->paginate(self::PAGINATION);

        return view('cpagos.conceptospago.registrar', compact('conceptos', 'buscarpor'));
    }

}