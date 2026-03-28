{{-- resources/views/asistencia/index.blade.php --}}
@extends('cplantilla.bprincipal')

@section('titulo', 'Gestión de Asistencia')

@section('contenidoplantilla')
    <style>
        /* Modern Design System Variables */
        :root {
            --primary-color: #0A8CB3;
            --primary-dark: #087299;
            --primary-light: #E0F7FA;
            --success-color: #28a745;
            --success-light: #f0fff4;
            --danger-color: #dc3545;
            --danger-light: #fff5f5;
            --warning-color: #ffc107;
            --warning-light: #fffbf0;
            --info-color: #17a2b8;
            --info-light: #d1ecf1;
            --secondary-color: #6c757d;
            --secondary-light: #f8f9fa;
            --dark-color: #343a40;
            --light-color: #f8f9fa;
            --border-radius: 12px;
            --border-radius-sm: 8px;
            --border-radius-lg: 16px;
            --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.06);
            --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.15);
            --transition-fast: all 0.2s ease;
            --transition-normal: all 0.3s ease;
            --transition-slow: all 0.5s ease;
        }

        /* Typography */
        .estilo-info {
            font-family: "Quicksand", sans-serif;
            font-weight: 700;
            font-size: clamp(0.9rem, 2.0vw, 0.9rem) !important;
            color: var(--dark-color);
        }

        /* Modern Card Design */
        .modern-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-sm);
            border: 1px solid rgba(0, 0, 0, 0.08);
            transition: var(--transition-normal);
            overflow: hidden;
        }

        .modern-card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        .modern-card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 1.25rem 1.5rem;
            border: none;
            position: relative;
        }

        .modern-card-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }

        .modern-card-header > * {
            position: relative;
            z-index: 1;
        }

        .modern-card-body {
            padding: 1.5rem;
        }

        /* Statistics Cards */
        .stats-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-sm);
            border: none;
            transition: var(--transition-normal);
            overflow: hidden;
            position: relative;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--primary-color);
        }

        .stats-card.presentes::before { background: var(--success-color); }
        .stats-card.ausentes::before { background: var(--danger-color); }
        .stats-card.tardanzas::before { background: var(--warning-color); }

        .stats-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .stats-card .card-body {
            padding: 1.5rem;
            position: relative;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: white;
        }

        .stat-icon.presentes { background: var(--success-color); }
        .stat-icon.ausentes { background: var(--danger-color); }
        .stat-icon.tardanzas { background: var(--warning-color); }

        .stats-card h3 {
            font-weight: 700;
            margin-bottom: 0.25rem;
            font-size: 1.75rem;
        }

        .stats-card p {
            font-weight: 600;
            color: var(--secondary-color);
            margin-bottom: 0.5rem;
        }

        .progress-modern {
            height: 8px;
            border-radius: 4px;
            background: rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .progress-modern .progress-bar {
            background: linear-gradient(90deg, var(--primary-color), var(--primary-dark));
            transition: width 0.8s ease;
        }

        /* Session Cards */
        .session-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-sm);
            border: 1px solid rgba(0, 0, 0, 0.08);
            transition: var(--transition-normal);
            overflow: hidden;
            margin-bottom: 1rem;
        }

        .session-card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        .session-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 1.25rem 1.5rem;
            position: relative;
        }

        .session-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.1);
        }

        .session-header > * {
            position: relative;
            z-index: 1;
        }

        .session-status {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .session-status.activo {
            background: rgba(40, 167, 69, 0.2);
            color: var(--success-color);
        }

        .session-status.pendiente {
            background: rgba(108, 117, 125, 0.2);
            color: var(--secondary-color);
        }

        .session-status.finalizado {
            background: rgba(255, 193, 7, 0.2);
            color: var(--warning-color);
        }

        .session-time {
            background: rgba(255, 255, 255, 0.15);
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.85rem;
            backdrop-filter: blur(10px);
        }

        .session-body {
            padding: 1.5rem;
        }

        .session-info {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .session-avatar {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            background: var(--primary-light);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-weight: 700;
            font-size: 1.2rem;
        }

        .session-details h6 {
            margin: 0 0 0.25rem 0;
            font-weight: 700;
            color: var(--dark-color);
        }

        .session-details small {
            color: var(--secondary-color);
            font-weight: 500;
        }

        .progress-session {
            height: 6px;
            border-radius: 3px;
            background: rgba(0, 0, 0, 0.1);
            margin-bottom: 1rem;
        }

        .progress-session .progress-bar {
            background: linear-gradient(90deg, var(--success-color), #20c997);
            transition: width 0.6s ease;
        }

        .session-stats {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .stat-item {
            text-align: center;
            flex: 1;
        }

        .stat-item .stat-number {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--dark-color);
        }

        .stat-item .stat-label {
            font-size: 0.8rem;
            color: var(--secondary-color);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        .session-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .btn-modern {
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-weight: 600;
            font-size: 0.9rem;
            transition: var(--transition-fast);
            border: none;
            position: relative;
            overflow: hidden;
        }

        .btn-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-modern:hover::before {
            left: 100%;
        }

        .btn-modern:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .btn-primary-modern {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
        }

        .btn-outline-modern {
            background: transparent;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
        }

        .btn-outline-modern:hover {
            background: var(--primary-color);
            color: white;
        }

        /* Date Selector */
        .date-selector-modern {
            background: white;
            border-radius: var(--border-radius);
            padding: 1rem;
            box-shadow: var(--shadow-sm);
            border: 1px solid rgba(0, 0, 0, 0.08);
        }

        .date-selector-modern label {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
            display: block;
        }

        .date-selector-modern input {
            border: 2px solid var(--primary-color);
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-family: "Quicksand", sans-serif;
            font-weight: 600;
            transition: var(--transition-fast);
        }

        .date-selector-modern input:focus {
            outline: none;
            border-color: var(--primary-dark);
            box-shadow: 0 0 0 3px rgba(10, 140, 179, 0.1);
        }

        /* Info Panel */
        .info-panel {
            background: linear-gradient(135deg, var(--info-light), rgba(23, 162, 184, 0.1));
            border: 1px solid var(--info-color);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .info-panel .info-icon {
            color: var(--info-color);
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .info-panel h5 {
            color: var(--dark-color);
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .info-panel p {
            color: var(--secondary-color);
            margin-bottom: 0.5rem;
            line-height: 1.6;
        }

        /* Empty State */
        .empty-state-modern {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-sm);
        }

        .empty-state-modern .empty-icon {
            font-size: 4rem;
            color: var(--secondary-color);
            opacity: 0.5;
            margin-bottom: 1.5rem;
        }

        .empty-state-modern h5 {
            color: var(--dark-color);
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .empty-state-modern p {
            color: var(--secondary-color);
            font-size: 1.1rem;
            line-height: 1.6;
        }

        /* Loading States */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            backdrop-filter: blur(5px);
        }

        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 4px solid rgba(10, 140, 179, 0.1);
            border-left: 4px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }

            .session-stats {
                flex-direction: column;
                gap: 0.5rem;
            }

            .session-actions {
                justify-content: center;
            }

            .date-selector-modern {
                margin-bottom: 1rem;
            }
        }

        @media (max-width: 576px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .session-card {
                margin-bottom: 1rem;
            }

            .session-info {
                flex-direction: column;
                text-align: center;
                gap: 0.5rem;
            }
        }

        /* Accessibility */
        .btn-modern:focus {
            outline: 2px solid var(--primary-color);
            outline-offset: 2px;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease forwards;
        }

        /* Status Indicators */
        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 0.5rem;
        }

        .status-dot.activo {
            background: var(--success-color);
            animation: pulse 2s infinite;
        }

        .status-dot.pendiente {
            background: var(--secondary-color);
        }

        .status-dot.finalizado {
            background: var(--warning-color);
        }

        /* Quick Actions */
        .quick-actions-bar {
            background: white;
            border-radius: var(--border-radius);
            padding: 1rem;
            box-shadow: var(--shadow-sm);
            margin-bottom: 2rem;
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .quick-action-btn {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            border: none;
            background: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            transition: var(--transition-normal);
            cursor: pointer;
            box-shadow: var(--shadow-sm);
        }

        .quick-action-btn:hover {
            transform: translateY(-2px) scale(1.05);
            box-shadow: var(--shadow-md);
            background: var(--primary-dark);
        }

        .quick-action-btn:active {
            transform: translateY(0) scale(0.95);
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
