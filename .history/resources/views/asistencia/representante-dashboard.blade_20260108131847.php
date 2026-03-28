@extends('cplantilla.bprincipal')

@section('titulo', 'Panel de Representante')
{{-- Dashboard principal para representantes con acceso unificado a asistencias y calificaciones --}}
{{-- Proporciona navegación intuitiva y estadísticas generales --}}

@section('contenidoplantilla')
    <style>
        @media (max-width: 576px) {
            .margen-movil {
                margin-left: -29px !important;
                margin-right: -29px !important;
            }

            .margen-movil-2 {
                margin: 0 !important;
                padding: 0 !important;
            }
        }

        /* Dashboard Cards */
        .dashboard-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            overflow: hidden;
            position: relative;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        .dashboard-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #28aece, #0e4067);
        }

        .card-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin: 0 auto 15px;
        }

        .card-icon.attendance {
            background: linear-gradient(135deg, #28aece, #20c997);
            color: white;
        }

        .card-icon.grades {
            background: linear-gradient(135deg, #ffc107, #fd7e14);
            color: white;
        }

        .card-icon.notifications {
            background: linear-gradient(135deg, #dc3545, #e83e8c);
            color: white;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: #0e4067;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 0.9rem;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Quick Actions */
        .quick-actions {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 12px;
            padding: 20px;
            margin-top: 20px;
        }

        .action-btn {
            background: white;
            border: 2px solid #dee2e6;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            text-decoration: none;
            color: #495057;
            transition: all 0.3s ease;
            display: block;
            margin-bottom: 10px;
        }

        .action-btn:hover {
            border-color: #28aece;
            background: #28aece;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 174, 206, 0.3);
        }

        .action-btn i {
            font-size: 20px;
            margin-bottom: 8px;
            display: block;
        }

        /* Welcome Section */
        .welcome-section {
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