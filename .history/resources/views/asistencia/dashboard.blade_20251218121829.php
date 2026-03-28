@extends('cplantilla.bprincipal')

@section('titulo', 'Dashboard de Asistencia - Eduka Perú')

@section('contenidoplantilla')
    @php
        $module = 'asistencia';
        $section = 'dashboard';
    @endphp
    @include('components.breadcrumb')

    <div class="row">
        <!-- Estadísticas principales -->
        <div class="col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-5">
                            <div class="icon-big text-center icon-primary bubble-shadow-small">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                        <div class="col-7 col-stats">
                            <div class="numbers">
                                <p class="card-category">Total Estudiantes</p>
                                <h4 class="card-title">{{ number_format($estadisticas['total_estudiantes'] ?? 0) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-5">
                            <div class="icon-big text-center icon-success bubble-shadow-small">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                        </div>
                        <div class="col-7 col-stats">
                            <div class="numbers">
                                <p class="card-category">Asistencias Hoy</p>
                                <h4 class="card-title">{{ number_format($estadisticas['total_asistencias_hoy'] ?? 0) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-5">
                            <div class="icon-big text-center icon-warning bubble-shadow-small">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                        <div class="col-7 col-stats">
                            <div class="numbers">
                                <p class="card-category">Justificaciones</p>
                                <h4 class="card-title">{{ number_format($estadisticas['total_justificaciones_pendientes'] ?? 0) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-5">
                            <div class="icon-big text-center icon-info bubble-shadow-small">
                                <i class="fas fa-user-tie"></i>
                            </div>
                        </div>
                        <div class="col-7 col-stats">
                            <div class="numbers">
                                <p class="card-category">Profesores</p>
                                <h4 class="card-title">{{ number_format($estadisticas['total_profesores'] ?? 0) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido específico por rol -->
    @if(auth()->user()->rol == 'Administrador')
        @include('asistencia.partials.dashboard-admin')
    @elseif(auth()->user()->rol == 'Profesor')
        @include('asistencia.partials.dashboard-profesor')
    @else
        @include('asistencia.partials.dashboard-representante')
    @endif

    <!-- Accesos rápidos -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-head-row">
                        <div class="card-title">Accesos Rápidos</div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if(auth()->user()->rol == 'Administrador')
                            <div class="col-md-3">
                                <a href="{{ route('asistencia.admin-index') }}" class="btn btn-primary btn-block">
                                    <i class="fas fa-cogs"></i> Administrar Asistencias
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('asistencia.verificar') }}" class="btn btn-warning btn-block">
                                    <i class="fas fa-check-circle"></i> Verificar Justificaciones
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('asistencia.reporte-general') }}" class="btn btn-info btn-block">
                                    <i class="fas fa-chart-pie"></i> Reportes Generales
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('asistencia.dashboard') }}" class="btn btn-success btn-block">
                                    <i class="fas fa-tachometer-alt"></i> Dashboard Completo
                                </a>
                            </div>
                        @elseif(auth()->user()->rol == 'Profesor')
                            <div class="col-md-4">
                                <a href="{{ route('asistencia.index') }}" class="btn btn-primary btn-block">
                                    <i class="fas fa-calendar-plus"></i> Registrar Asistencia
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="{{ route('asistencia.reporte-curso', ['cursoAsignaturaId' => 1]) }}" class="btn btn-info btn-block">
                                    <i class="fas fa-chart-bar"></i> Reportes de Clase
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="{{ route('asistencia.dashboard') }}" class="btn btn-success btn-block">
                                    <i class="fas fa-tachometer-alt"></i> Mi Dashboard
                                </a>
                            </div>
                        @else
                            <div class="col-md-4">
                                <a href="{{ route('asistencia.misEstudiantes') }}" class="btn btn-primary btn-block">
                                    <i class="fas fa-users"></i> Mis Estudiantes
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="{{ route('asistencia.justificar') }}" class="btn btn-warning btn-block">
                                    <i class="fas fa-file-alt"></i> Justificar Inasistencia
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="{{ route('asistencia.dashboard') }}" class="btn btn-success btn-block">
                                    <i class="fas fa-tachometer-alt"></i> Mi Dashboard
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .card-stats {
            margin-bottom: 1.5rem;
        }

        .icon-big {
            font-size: 2.5rem;
            line-height: 1;
        }

        .bubble-shadow-small {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-block {
            margin-bottom: 0.5rem;
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 600;
        }

        .card-category {
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
        }
    </style>
@endsection
