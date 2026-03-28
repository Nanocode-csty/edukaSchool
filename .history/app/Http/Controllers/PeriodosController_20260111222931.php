<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PeriodoMatricula;
use App\Models\NotificacionPeriodo;
use App\Models\DescuentoPeriodo;
use App\Models\Matricula;
use App\Models\InfAnioLectivo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PeriodosController extends Controller
{
    /**
     * Dashboard principal de períodos
     */
    public function dashboard()
    {
        $periodoActual = PeriodoMatricula::getPeriodoActual();
        $proximosPeriodos = PeriodoMatricula::getProximosPeriodos(30);
        $todosPeriodos = PeriodoMatricula::with('anoLectivo')->activos()->ordenado()->get();

        // Estadísticas
        $totalPeriodos = $todosPeriodos->count();
