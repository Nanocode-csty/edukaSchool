@extends('cplantilla.bprincipal')

@section('titulo','Panel de Representante')
<x-breadcrumb :module="'asistencia'" :section="'representante-dashboard'" />
@section('contenidoplantilla')
<div class="container-fluid" id="contenido-principal">
    <div class="row mt-4 ml-1 mr-1">
        <div class="col-12">
            <div class="box_block">
                <!-- Collapse header -->
                <button class="btn btn-block text-left rounded-0 btn_header header_6" type="button" data-toggle="collapse" data-target="#collapseDashboardRepresentante" aria-expanded="true" aria-controls="collapseDashboardRepresentante" style="background: #0A8CB3 !important; font-weight: bold; color: white;">
                    <i class="fas fa-tachometer-alt m-1"></i>&nbsp;Panel de Representante
                    <div class="float-right"><i class="fas fa-chevron-down"></i></div>
                </button>
                <!-- Descripción (siempre visible, dentro del bloque) -->
                <div class="card-body rounded-0 border-0 pt-3 pb-3" style="background: #f3f3f3; border-bottom: 1px solid rgba(0,0,0,.125); border-top: 1px solid rgba(0,0,0,.125); color: #F59D24;">
                    <div class="row justify-content-center align-items-center flex-wrap">
                        <div class="col-auto text-center mb-2 mb-md-0" style="min-width:48px;">
                            <i class="fas fa-user-shield fa-2x"></i>
                        </div>
                        <div class="col px-2" style="text-align:justify;">
                            <p style="margin-bottom: 0px; font-family: 'Quicksand', sans-serif; font-weight: 600; color: #004a92;">
                                ¡Bienvenido, {{ auth()->user()->persona ? auth()->user()->persona->nombres . ' ' . auth()->user()->persona->apellidos : 'Representante' }}! En esta sección puedes acceder fácilmente a toda la información académica de tus estudiantes representados.
                            </p>
                            <p style="font-family: 'Quicksand', sans-serif; font-weight: 600; color: #004a92;">
                                Utiliza las opciones disponibles para consultar asistencias, calificaciones y gestionar justificaciones de manera organizada y eficiente.
                            </p>
                        </div>
                    </div>
                </div>
                <!-- Collapse: estadísticas y acciones -->
                <div class="collapse show" id="collapseDashboardRepresentante">
                    <div class="card card-body rounded-0 border-0 pt-0 pb-2" style="background: transparent;">
                        <!-- Estadísticas generales -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="stats-compact d-flex justify-content-between align-items-center p-3 rounded" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border: 1px solid #dee2e6;">
                                    <div class="stat-item text-center">
                                        <div class="stat-value">
                                            <span class="badge badge-success stat-badge">{{ $estadisticas['total_estudiantes'] ?? 0 }}</span>
                                        </div>
                                        <small class="text-muted d-block">Mis Estudiantes</small>
                                    </div>
                                    <div class="stat-item text-center">
                                        <div class="stat-value">
                                            <span class="badge badge-primary stat-badge">{{ $estadisticas['promedio_asistencia'] ?? 0 }}%</span>
                                        </div>
                                        <small class="text-muted d-block">Asistencia Promedio</small>
                                    </div>
                                    <div class="stat-item text-center">
                                        <div class="stat-value">
                                            <span class="badge badge-warning stat-badge">{{ $estadisticas['total_inasistencias'] ?? 0 }}</span>
                                        </div>
                                        <small class="text-muted d-block">Inasistencias</small>
                                    </div>
                                    <div class="stat-item text-center">
                                        <div class="stat-value">
                                            <span class="badge badge-danger stat-badge">{{ $estadisticas['justificaciones_pendientes'] ?? 0 }}</span>
                                        </div>
                                        <small class="text-muted d-block">Justificaciones Pendientes</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Acciones principales -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="d-flex justify-content-center gap-3 flex-wrap">
                                    <a href="{{ route('asistencia.representante.index') }}" class="btn btn-primary btn-lg px-4 py-3" style="min-width: 200px;">
                                        <i class="fas fa-calendar-check mr-2"></i>
                                        <div>Ver Asistencias</div>
                                    </a>
                                    <a href="{{ route('notas.misEstudiantes') }}" class="btn btn-warning btn-lg px-4 py-3" style="min-width: 200px;">
                                        <i class="fas fa-chart-bar mr-2"></i>
                                        <div>Ver Calificaciones</div>
                                    </a>
                                    <button type="button" class="btn btn-info btn-lg px-4 py-3" onclick="mostrarEstadisticas()" style="min-width: 200px;">
                                        <i class="fas fa-chart-pie mr-2"></i>
                                        <div>Estadísticas</div>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Acciones rápidas adicionales -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card" style="background: #f8f9fa; border: 2px solid #0A8CB3; border-radius: 10px;">
                                    <div class="card-header" style="background: #0A8CB3; color: white; border-radius: 8px 8px 0 0;">
                                        <h5 class="mb-0">
                                            <i class="fas fa-bolt mr-2"></i>Acciones Rápidas
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row text-center">
                                            <div class="col-md-3 col-sm-6 mb-3">
                                                <a href="{{ route('asistencia.representante.index') }}" class="btn btn-outline-primary btn-block py-3">
                                                    <i class="fas fa-list fa-2x mb-2"></i><br>
                                                    Lista de Estudiantes
                                                </a>
                                            </div>
                                            <div class="col-md-3 col-sm-6 mb-3">
                                                <a href="{{ route('notas.misEstudiantes') }}" class="btn btn-outline-success btn-block py-3">
                                                    <i class="fas fa-graduation-cap fa-2x mb-2"></i><br>
                                                    Ver Notas
                                                </a>
                                            </div>
                                            <div class="col-md-3 col-sm-6 mb-3">
                                                <a href="{{ route('notificaciones.index') }}" class="btn btn-outline-info btn-block py-3">
                                                    <i class="fas fa-bell fa-2x mb-2"></i><br>
                                                    Notificaciones
                                                </a>
                                            </div>
                                            <div class="col-md-3 col-sm-6 mb-3">
                                                <button type="button" class="btn btn-outline-secondary btn-block py-3" onclick="mostrarEstadisticas()">
                                                    <i class="fas fa-chart-pie fa-2x mb-2"></i><br>
                                                    Más Estadísticas
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div
            background: linear-gradient(135deg, #0e4067 0%, #28aece 100%);
            color: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
        }

        .welcome-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translate(-50%, -50%) rotate(0deg); }
            50% { transform: translate(-50%, -50%) rotate(180deg); }
        }

        .welcome-content {
            position: relative;
            z-index: 2;
        }
    </style>

    <div class="container-fluid margen-movil-2">
        <!-- Welcome Section -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="welcome-section">
                    <div class="welcome-content">
                        <h2 class="mb-2">
                            <i class="fas fa-user-shield mr-3"></i>
                            ¡Bienvenido, {{ auth()->user()->persona ? auth()->user()->persona->nombres . ' ' . auth()->user()->persona->apellidos : 'Representante' }}!
                        </h2>
                        <p class="mb-0 opacity-75">
                            Accede fácilmente a la información académica de tus estudiantes representados
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Row -->
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="dashboard-card h-100">
                    <div class="card-body text-center p-4">
                        <div class="stat-number">{{ $estadisticas['total_estudiantes'] ?? 0 }}</div>
                        <div class="stat-label">Estudiantes</div>
                        <i class="fas fa-users text-muted mt-2"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="dashboard-card h-100">
                    <div class="card-body text-center p-4">
                        <div class="stat-number">{{ $estadisticas['promedio_asistencia'] ?? 0 }}%</div>
                        <div class="stat-label">Asistencia Promedio</div>
                        <i class="fas fa-calendar-check text-success mt-2"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="dashboard-card h-100">
                    <div class="card-body text-center p-4">
                        <div class="stat-number">{{ $estadisticas['total_inasistencias'] ?? 0 }}</div>
                        <div class="stat-label">Inasistencias</div>
                        <i class="fas fa-calendar-times text-warning mt-2"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="dashboard-card h-100">
                    <div class="card-body text-center p-4">
                        <div class="stat-number">{{ $estadisticas['justificaciones_pendientes'] ?? 0 }}</div>
                        <div class="stat-label">Justificaciones Pendientes</div>
                        <i class="fas fa-clock text-info mt-2"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Actions Row -->
        <div class="row mb-4">
            <div class="col-md-6 mb-4">
                <div class="dashboard-card h-100">
                    <div class="card-body text-center p-4">
                        <div class="card-icon attendance">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <h4 class="card-title mb-3">Control de Asistencias</h4>
                        <p class="card-text text-muted mb-4">
                            Visualiza y gestiona las asistencias diarias de tus estudiantes, solicita justificaciones y genera reportes.
                        </p>
                        <a href="{{ route('asistencia.representante.index') }}" class="btn btn-primary btn-lg px-4 py-2">
                            <i class="fas fa-eye mr-2"></i>Ver Asistencias
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="dashboard-card h-100">
                    <div class="card-body text-center p-4">
                        <div class="card-icon grades">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h4 class="card-title mb-3">Calificaciones</h4>
                        <p class="card-text text-muted mb-4">
                            Consulta las notas y rendimiento académico de tus estudiantes en todas las asignaturas.
                        </p>
                        <a href="{{ route('notas.misEstudiantes') }}" class="btn btn-warning btn-lg px-4 py-2">
                            <i class="fas fa-chart-bar mr-2"></i>Ver Calificaciones
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row">
            <div class="col-12">
                <div class="quick-actions">
                    <h5 class="mb-3 text-center">
                        <i class="fas fa-bolt text-warning mr-2"></i>
                        Acciones Rápidas
                    </h5>
                    <div class="row">
                        <div class="col-md-3 col-sm-6 mb-3">
                            <a href="{{ route('asistencia.representante.index') }}" class="action-btn">
                                <i class="fas fa-list"></i>
                                <div>Lista de Estudiantes</div>
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <a href="{{ route('notas.misEstudiantes') }}" class="action-btn">
                                <i class="fas fa-graduation-cap"></i>
                                <div>Ver Notas</div>
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <a href="#" onclick="mostrarEstadisticas()" class="action-btn">
                                <i class="fas fa-chart-pie"></i>
                                <div>Estadísticas</div>
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <a href="{{ route('notificaciones.index') }}" class="action-btn">
                                <i class="fas fa-bell"></i>
                                <div>Notificaciones</div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity (if needed) -->
        @if(isset($actividad_reciente) && count($actividad_reciente) > 0)
        <div class="row mt-4">
            <div class="col-12">
                <div class="dashboard-card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-history mr-2"></i>
                            Actividad Reciente
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            @foreach($actividad_reciente as $actividad)
                            <div class="list-group-item px-0">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $actividad['titulo'] }}</h6>
                                    <small class="text-muted">{{ $actividad['fecha'] }}</small>
                                </div>
                                <p class="mb-1">{{ $actividad['descripcion'] }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Statistics Modal -->
    <div class="modal fade" id="modalEstadisticas" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-chart-pie mr-2"></i>
                        Estadísticas Generales
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="text-center p-3 bg-light rounded">
                                <h3 class="text-primary">{{ $estadisticas['total_estudiantes'] ?? 0 }}</h3>
                                <p class="mb-0">Total de Estudiantes</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-center p-3 bg-light rounded">
                                <h3 class="text-success">{{ $estadisticas['promedio_asistencia'] ?? 0 }}%</h3>
                                <p class="mb-0">Asistencia Promedio</p>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="text-center p-3 bg-light rounded">
                                <h3 class="text-warning">{{ $estadisticas['total_inasistencias'] ?? 0 }}</h3>
                                <p class="mb-0">Total Inasistencias</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-center p-3 bg-light rounded">
                                <h3 class="text-info">{{ $estadisticas['justificaciones_pendientes'] ?? 0 }}</h3>
                                <p class="mb-0">Justificaciones Pendientes</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
function mostrarEstadisticas() {
    $('#modalEstadisticas').modal('show');
}

// Auto-refresh statistics every 5 minutes
setInterval(function() {
    // Could add AJAX call to refresh statistics if needed
    console.log('Refreshing dashboard statistics...');
}, 300000);
</script>
@endsection
