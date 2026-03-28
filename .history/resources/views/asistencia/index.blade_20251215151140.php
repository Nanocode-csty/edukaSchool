{{-- resources/views/asistencia/index.blade.php --}}
@extends('cplantilla.bprincipal')

@section('titulo', 'Gestión de Asistencia')

@section('contenidoplantilla')
    <style>
        .form-bordered {
            margin: 0;
            border: none;
            padding-top: 15px;
            padding-bottom: 15px;
            border-bottom: 1px dashed #eaedf1;
        }

        .card-body.info {
            background: #f3f3f3;
            border-bottom: 1px solid rgba(0, 0, 0, .125);
            border-top: 1px solid rgba(0, 0, 0, .125);
            color: #F59D24;
        }

        .card-body.info p {
            margin-bottom: 0px;
            font-family: "Quicksand", sans-serif;
            font-weight: 600;
            color: #004a92;
        }

        .estilo-info {
            margin-bottom: 0px;
            font-family: "Quicksand", sans-serif;
            font-weight: 700;
            font-size: clamp(0.9rem, 2.0vw, 0.9rem) !important;
        }

        .btn-primary {
            background: #007bff !important;
            border: none;
            transition: background-color 0.2s ease, transform 0.1s ease;
            font-family: "Quicksand", sans-serif;
            font-weight: 700;
            font-size: clamp(0.9rem, 2.0vw, 0.9rem) !important;
        }

        .btn-primary:hover {
            background-color: #0056b3 !important;
            transform: scale(1.01);
        }

        .stats-card {
            border-radius: 10px;
            transition: transform 0.2s, box-shadow 0.2s;
            border: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            margin-bottom: 1rem;
        }

        .stats-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        }

        .stats-card .card-body {
            padding: 1.25rem;
        }

        .stats-card h3 {
            font-weight: 700;
            margin-bottom: 0;
        }

        .stats-card p {
            font-weight: 600;
            color: #6c757d;
        }

        .stat-icon {
            font-size: 2.5rem;
            opacity: 0.15;
        }

        .progress-custom {
            height: 6px;
            border-radius: 10px;
            background: #e9ecef;
        }

        .sesion-card {
            border-radius: 10px;
            transition: all 0.3s ease;
            border: 1px solid #dee2e6;
            overflow: hidden;
            margin-bottom: 1rem;
        }

        .sesion-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .sesion-header {
            background: #0A8CB3;
            color: white;
            padding: 1rem;
        }

        .badge-hora {
            background: rgba(255, 255, 255, 0.2);
            padding: 0.4rem 0.8rem;
            border-radius: 15px;
            font-size: 0.85rem;
            display: inline-block;
        }

        .status-indicator {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 8px;
        }

        .status-indicator.activo {
            background: #28a745;
            animation: pulse 2s infinite;
        }

        .status-indicator.pendiente {
            background: #6c757d;
        }

        .status-indicator.finalizado {
            background: #ffc107;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        .quick-action-btn {
            border-radius: 50%;
            width: 55px;
            height: 55px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
            transition: all 0.3s;
            border: none;
        }

        .quick-action-btn:hover {
            transform: scale(1.08);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .fecha-selector-container {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .fecha-selector-container input {
            border: 2px solid #0A8CB3;
            border-radius: 5px;
            padding: 0.5rem;
            font-family: "Quicksand", sans-serif;
            font-weight: 600;
        }

        .table-custom tbody tr:nth-of-type(odd) {
            background-color: #f5f5f5;
        }

        .table-custom tbody tr:nth-of-type(even) {
            background-color: #e0e0e0;
        }

        #loaderPrincipal[style*="display: flex"] {
            display: flex !important;
            justify-content: center;
            align-items: center;
            position: absolute !important;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            width: 100%;
            height: 100%;
            z-index: 2000;
        }

        .empty-state {
            padding: 3rem 1rem;
            text-align: center;
        }

        .empty-state i {
            font-size: 4rem;
            color: #dee2e6;
            margin-bottom: 1rem;
        }
    </style>

    <div class="container-fluid" id="contenido-principal" style="position: relative;">
        @include('ccomponentes.loader', ['id' => 'loaderPrincipal'])

        <div class="row mt-4 ml-1 mr-1">
            <div class="col-12">
                <div class="box_block">
                    <button class="estilo-info btn btn-block text-left rounded-0 btn_header header_6" type="button"
                        data-toggle="collapse" data-target="#collapseAsistencia" aria-expanded="true"
                        style="background: #0A8CB3 !important; font-weight: bold; color: white;">
                        <i class="fas fa-clipboard-check m-1"></i>&nbsp;Control de Asistencia
                        <div class="float-right"><i class="fas fa-chevron-down"></i></div>
                    </button>

                    <div class="card-body info">
                        <div class="d-flex">
                            <div>
                                <i class="fas fa-exclamation-circle fa-2x"></i>
                            </div>
                            <div class="p-2 flex-fill">
                                <p>
                                    En esta sección, podrás registrar y gestionar la asistencia de tus estudiantes para cada
                                    sesión programada.
                                </p>
                                <p>
                                    Asegúrate de registrar la asistencia de manera oportuna para mantener un control preciso
                                    del desempeño estudiantil.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="collapse show" id="collapseAsistencia">
                        <div class="card card-body rounded-0 border-0 pt-3 pb-2"
                            style="background-color: #fcfffc !important">

                            <!-- Header con fecha y acciones -->
                            <div class="row align-items-center mb-3">
                                <div class="col-md-4">
                                    <h5 class="estilo-info mb-2">
                                        <i class="far fa-calendar text-primary"></i>
                                        {{ Carbon\Carbon::parse($fecha)->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                                    </h5>
                                </div>
                                <div class="col-md-4 d-flex justify-content-center">
                                    <a href="{{ route('asistencia.reporte-general') }}" class="btn btn-outline-primary">
                                        <i class="fas fa-chart-pie"></i> Reporte General
                                    </a>
                                </div>
                                <div class="col-md-4 d-flex justify-content-md-end justify-content-start">
                                    <div class="fecha-selector-container">
                                        <label class="mb-0 estilo-info">Seleccionar fecha:</label>
                                        <input type="date" id="fecha-selector" class="form-control"
                                            value="{{ $fecha }}" onchange="cambiarFecha(this.value)">
                                    </div>
                                </div>
                            </div>

                            <!-- Estadísticas del Día -->
                            <div class="row mb-3">
                                <div class="col-lg-3 col-md-6 mb-2">
                                    <div class="card stats-card" style="background: #495057; color: white;">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <p class="mb-1" style="color: rgba(255,255,255,0.8);">Total Registros
                                                    </p>
                                                    <h3 class="mb-0">{{ $estadisticas['total_registros'] }}</h3>
                                                </div>
                                                <i class="fas fa-users stat-icon"></i>
                                            </div>
                                            <div class="progress progress-custom mt-2">
                                                <div class="progress-bar"
                                                    style="width: 100%; background: rgba(255,255,255,0.3);"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-6 mb-2">
                                    <div class="card stats-card" style="background: #28a745; color: white;">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <p class="mb-1" style="color: rgba(255,255,255,0.8);">Presentes</p>
                                                    <h3 class="mb-0">{{ $estadisticas['presentes'] }}</h3>
                                                </div>
                                                <i class="fas fa-check-circle stat-icon"></i>
                                            </div>
                                            <div class="progress progress-custom mt-2">
                                                <div class="progress-bar"
                                                    style="width: {{ $estadisticas['porcentaje_asistencia'] }}%; background: rgba(255,255,255,0.3);">
                                                </div>
                                            </div>
                                            <small
                                                style="color: rgba(255,255,255,0.9);">{{ $estadisticas['porcentaje_asistencia'] }}%
                                                del total</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-6 mb-2">
                                    <div class="card stats-card" style="background: #dc3545; color: white;">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <p class="mb-1" style="color: rgba(255,255,255,0.8);">Ausentes</p>
                                                    <h3 class="mb-0">{{ $estadisticas['ausentes'] }}</h3>
                                                </div>
                                                <i class="fas fa-times-circle stat-icon"></i>
                                            </div>
                                            <div class="progress progress-custom mt-2">
                                                <div class="progress-bar"
                                                    style="width: {{ $estadisticas['total_registros'] > 0 ? ($estadisticas['ausentes'] / $estadisticas['total_registros']) * 100 : 0 }}%; background: rgba(255,255,255,0.3);">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-6 mb-2">
                                    <div class="card stats-card" style="background: #ffc107; color: white;">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <p class="mb-1" style="color: rgba(255,255,255,0.8);">Tardanzas</p>
                                                    <h3 class="mb-0">{{ $estadisticas['tardanzas'] }}</h3>
                                                </div>
                                                <i class="fas fa-clock stat-icon"></i>
                                            </div>
                                            <div class="progress progress-custom mt-2">
                                                <div class="progress-bar"
                                                    style="width: {{ $estadisticas['total_registros'] > 0 ? ($estadisticas['tardanzas'] / $estadisticas['total_registros']) * 100 : 0 }}%; background: rgba(255,255,255,0.3);">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sesiones Programadas -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="card shadow-sm">
                                        <div class="card-header"
                                            style="background: #f8f9fa; border-bottom: 2px solid #0A8CB3;">
                                            <h6 class="mb-0 estilo-info">
                                                <i class="fas fa-chalkboard-teacher text-primary"></i>
                                                Sesiones Programadas
                                                <span class="badge bg-primary ms-2">{{ $sesiones->count() }}</span>
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            @if ($sesiones->isEmpty())
                                                @php
                                                    $esFeriado = \App\Models\Feriado::esFeriado($fecha);
                                                    $feriadoInfo = $esFeriado ? \App\Models\Feriado::porFecha($fecha)->first() : null;
                                                @endphp

                                                @if($esFeriado)
                                                    <div class="empty-state">
                                                        <i class="fas fa-calendar-day" style="color: #dc3545;"></i>
                                                        <h5 class="text-danger mb-2">Día Feriado</h5>
                                                        <p class="text-muted mb-3">
                                                            <strong>{{ $feriadoInfo->nombre }}</strong><br>
                                                            @if($feriadoInfo->descripcion)
                                                                {{ $feriadoInfo->descripcion }}
                                                            @endif
                                                        </p>
                                                        <div class="alert alert-info">
                                                            <i class="fas fa-info-circle"></i>
                                                            <strong>No hay sesiones programadas porque es feriado.</strong><br>
                                                            Si hay clases de recuperación programadas, aparecerán en fechas posteriores.
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="empty-state">
                                                        <i class="fas fa-calendar-times"></i>
                                                        <h5 class="text-muted mb-2">No tienes sesiones programadas para hoy</h5>
                                                        <p class="text-muted">Revisa el horario o selecciona otra fecha</p>
                                                    </div>
                                                @endif
                                            @else
                                                <div class="row">
                                                    @foreach ($sesiones as $sesion)
                                                        <div class="col-lg-6 col-xl-4 mb-3">
                                                            <div class="sesion-card">
                                                                <div class="sesion-header">
                                                                    <div
                                                                        class="d-flex justify-content-between align-items-start mb-2">
                                                                        <div style="flex: 1;">
                                                                            <h6 class="mb-1" style="font-weight: 700;">
                                                                                {{ $sesion->cursoAsignatura->asignatura->nombre }}
                                                                            </h6>
                                                                            <small style="opacity: 0.85;">
                                                                                {{ $sesion->cursoAsignatura->curso->grado->nombre }}
                                                                                -
                                                                                {{ $sesion->cursoAsignatura->curso->seccion->nombre }}
                                                                            </small>
                                                                        </div>
                                                                        @php
                                                                            $ahora = now();
                                                                            if (
                                                                                str_contains($sesion->hora_inicio, ' ')
                                                                            ) {
                                                                                $inicio = Carbon\Carbon::parse(
                                                                                    $sesion->hora_inicio,
                                                                                );
                                                                            } else {
                                                                                $inicio = Carbon\Carbon::parse(
                                                                                    $fecha . ' ' . $sesion->hora_inicio,
                                                                                );
                                                                            }

                                                                            if (str_contains($sesion->hora_fin, ' ')) {
                                                                                $fin = Carbon\Carbon::parse(
                                                                                    $sesion->hora_fin,
                                                                                );
                                                                            } else {
                                                                                $fin = Carbon\Carbon::parse(
                                                                                    $fecha . ' ' . $sesion->hora_fin,
                                                                                );
                                                                            }

                                                                            $estadoClase = 'pendiente';
                                                                            if ($ahora->between($inicio, $fin)) {
                                                                                $estadoClase = 'activo';
                                                                            } elseif ($ahora->gt($fin)) {
                                                                                $estadoClase = 'finalizado';
                                                                            }
                                                                        @endphp
                                                                        <span
                                                                            class="status-indicator {{ $estadoClase }}"></span>
                                                                    </div>
                                                                    <div class="badge-hora">
                                                                        <i class="far fa-clock"></i>
                                                                        {{ $sesion->hora_inicio }} -
                                                                        {{ $sesion->hora_fin }}
                                                                    </div>
                                                                </div>

                                                                <div class="card-body">
                                                                    @php
                                                                        $asistenciasRegistradas = \App\Models\AsistenciaAsignatura::where(
                                                                            'curso_asignatura_id',
                                                                            $sesion->curso_asignatura_id,
                                                                        )
                                                                            ->where('fecha', $fecha)
                                                                            ->count();
                                                                        $totalEstudiantes = \App\Models\Matricula::where(
                                                                            'curso_id',
                                                                            $sesion->cursoAsignatura->curso_id,
                                                                        )
                                                                            ->where('estado', 'Matriculado')
                                                                            ->count();
                                                                        $porcentajeRegistro =
                                                                            $totalEstudiantes > 0
                                                                                ? round(
                                                                                    ($asistenciasRegistradas /
                                                                                        $totalEstudiantes) *
                                                                                        100,
                                                                                )
                                                                                : 0;
                                                                    @endphp

                                                                    <div class="mb-3">
                                                                        <div class="d-flex justify-content-between mb-2">
                                                                            <small class="text-muted estilo-info">
                                                                                <i class="fas fa-user-graduate"></i>
                                                                                {{ $totalEstudiantes }} estudiantes
                                                                            </small>
                                                                            <small class="text-muted estilo-info">
                                                                                {{ $asistenciasRegistradas }}/{{ $totalEstudiantes }}
                                                                                registrados
                                                                            </small>
                                                                        </div>
                                                                        <div class="progress progress-custom">
                                                                            <div class="progress-bar {{ $porcentajeRegistro == 100 ? 'bg-success' : 'bg-info' }}"
                                                                                style="width: {{ $porcentajeRegistro }}%">
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="d-flex gap-2">
                                                                        <a href="{{ route('asistencia.registrar-asignatura', [$sesion->curso_asignatura_id, $fecha]) }}"
                                                                            class="btn btn-primary btn-sm flex-grow-1">
                                                                            <i class="fas fa-edit"></i>
                                                                            {{ $asistenciasRegistradas > 0 ? 'Editar' : 'Registrar' }}
                                                                        </a>

                                                                        @if ($asistenciasRegistradas > 0)
                                                                            <a href="{{ route('asistencia.reporte-curso', $sesion->curso_asignatura_id) }}"
                                                                                class="btn btn-outline-info btn-sm">
                                                                                <i class="fas fa-chart-bar"></i>
                                                                            </a>
                                                                        @endif
                                                                    </div>

                                                                    @if ($estadoClase === 'activo')
                                                                        <div class="alert alert-success mt-3 mb-0 py-2">
                                                                            <i class="fas fa-info-circle"></i>
                                                                            <small>Clase en progreso</small>
                                                                        </div>
                                                                    @elseif($estadoClase === 'finalizado' && $asistenciasRegistradas == 0)
                                                                        <div class="alert alert-warning mt-3 mb-0 py-2">
                                                                            <i class="fas fa-exclamation-triangle"></i>
                                                                            <small>Asistencia pendiente</small>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loader = document.getElementById('loaderPrincipal');
            if (loader) loader.style.display = 'none';
        });

        function cambiarFecha(fecha) {
            const loader = document.getElementById('loaderPrincipal');
            if (loader) loader.style.display = 'flex';

            setTimeout(() => {
                window.location.href = '{{ route('asistencia.index') }}?fecha=' + fecha;
            }, 500);
        }

        function exportarExcel() {
            const fecha = document.getElementById('fecha-selector').value;
            window.location.href = `/asistencia/exportar?fecha=${fecha}`;
        }

        // Actualización automática cada 5 minutos
        setInterval(() => {
            location.reload();
        }, 300000);
    </script>

@endsection
