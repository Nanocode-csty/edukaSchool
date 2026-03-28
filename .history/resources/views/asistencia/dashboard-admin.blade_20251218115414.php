@extends('cplantilla.bprincipal')

@section('titulo', 'Dashboard de Asistencias - Administrador')

@section('contenidoplantilla')
    <style>
        .dashboard-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            color: white;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 35px rgba(0,0,0,0.2);
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: #2d3748;
            margin: 0.5rem 0;
        }

        .stat-label {
            font-size: 0.9rem;
            color: #718096;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .recent-activity {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border: 1px solid #e9ecef;
        }

        .activity-item {
            padding: 1rem;
            border-bottom: 1px solid #f7fafc;
            transition: all 0.2s ease;
        }

        .activity-item:hover {
            background-color: #f8fafc;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-presente {
            background: #d1fae5;
            color: #065f46;
        }

        .status-ausente {
            background: #fee2e2;
            color: #dc2626;
        }

        .status-tardanza {
            background: #fef3c7;
            color: #d97706;
        }

        .status-justificada {
            background: #e0e7ff;
            color: #3730a3;
        }

        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 2rem;
        }

        .action-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .action-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .action-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            margin: 0 auto 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .action-title {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 0.5rem;
        }

        .action-description {
            font-size: 0.9rem;
            color: #718096;
        }

        .alert-card {
            background: linear-gradient(135deg, #fed7d7 0%, #feb2b2 100%);
            border: 1px solid #fbb6ce;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1rem;
            color: #c53030;
        }

        .alert-icon {
            background: #c53030;
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.75rem;
        }

        @media (max-width: 768px) {
            .quick-actions {
                grid-template-columns: 1fr;
            }

            .stat-card {
                margin-bottom: 1rem;
            }
        }
    </style>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12 mb-4">
                @include('components.breadcrumb', [
                    'module' => 'asistencia',
                    'section' => 'dashboard'
                ])

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="mb-1" style="color: #2d3748; font-weight: 700;">Dashboard de Asistencias</h2>
                        <p class="text-muted mb-0">Vista general del sistema de asistencia académica</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary" onclick="location.reload()">
                            <i class="fas fa-sync-alt me-2"></i>Actualizar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas Principales -->
        <div class="row mb-4">
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-3">
                <div class="stat-card">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <i class="fas fa-users text-white"></i>
                        </div>
                        <div class="ms-3 flex-grow-1">
                            <div class="stat-number">{{ number_format($estadisticas['total_estudiantes']) }}</div>
                            <div class="stat-label">Estudiantes Activos</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-3">
                <div class="stat-card">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                            <i class="fas fa-chalkboard-teacher text-white"></i>
                        </div>
                        <div class="ms-3 flex-grow-1">
                            <div class="stat-number">{{ number_format($estadisticas['total_profesores']) }}</div>
                            <div class="stat-label">Profesores Activos</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-3">
                <div class="stat-card">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                            <i class="fas fa-calendar-check text-white"></i>
                        </div>
                        <div class="ms-3 flex-grow-1">
                            <div class="stat-number">{{ number_format($estadisticas['total_asistencias_hoy']) }}</div>
                            <div class="stat-label">Asistencias Hoy</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-3">
                <div class="stat-card">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                            <i class="fas fa-exclamation-triangle text-white"></i>
                        </div>
                        <div class="ms-3 flex-grow-1">
                            <div class="stat-number">{{ number_format($estadisticas['total_justificaciones_pendientes']) }}</div>
                            <div class="stat-label">Justificaciones Pendientes</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alertas y Acciones Rápidas -->
        <div class="row mb-4">
            <div class="col-lg-8">
                <!-- Alertas Recientes -->
                <div class="recent-activity">
                    <div class="p-3 border-bottom">
                        <h5 class="mb-0" style="color: #2d3748; font-weight: 600;">
                            <i class="fas fa-bell me-2 text-warning"></i>
                            Justificaciones Pendientes
                        </h5>
                    </div>
                    <div style="max-height: 400px; overflow-y: auto;">
                        @forelse($alertasRecientes as $alerta)
                            <div class="activity-item">
                                <div class="d-flex align-items-center">
                                    <div class="activity-avatar me-3">
                                        {{ substr($alerta->matricula->estudiante->persona->nombres ?? 'N', 0, 1) }}
                                        {{ substr($alerta->matricula->estudiante->persona->apellidos ?? 'A', 0, 1) }}
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1" style="color: #2d3748; font-weight: 600;">
                                                    {{ $alerta->matricula->estudiante->persona->nombres ?? 'N/A' }}
                                                    {{ $alerta->matricula->estudiante->persona->apellidos ?? 'N/A' }}
                                                </h6>
                                                <p class="mb-1 text-muted small">
                                                    Fecha: {{ \Carbon\Carbon::parse($alerta->fecha)->format('d/m/Y') }} |
                                                    Motivo: {{ $alerta->motivo }}
                                                </p>
                                            </div>
                                            <a href="{{ route('asistencia.verificar') }}" class="btn btn-sm btn-outline-primary">
                                                Revisar
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                <h6 class="text-muted">¡Excelente!</h6>
                                <p class="text-muted small">No hay justificaciones pendientes</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Acciones Rápidas -->
                <div class="recent-activity h-100">
                    <div class="p-3 border-bottom">
                        <h5 class="mb-0" style="color: #2d3748; font-weight: 600;">
                            <i class="fas fa-bolt me-2 text-primary"></i>
                            Acciones Rápidas
                        </h5>
                    </div>
                    <div class="p-3">
                        <div class="d-grid gap-2">
                            <a href="{{ route('asistencia.admin-index') }}" class="btn btn-outline-primary">
                                <i class="fas fa-search me-2"></i>Buscar Asistencias
                            </a>
                            <a href="{{ route('asistencia.verificar') }}" class="btn btn-outline-warning">
                                <i class="fas fa-clipboard-check me-2"></i>Revisar Justificaciones
                            </a>
                            <a href="{{ route('asistencia.reporte-general') }}" class="btn btn-outline-success">
                                <i class="fas fa-chart-bar me-2"></i>Ver Reportes
                            </a>
                            <button class="btn btn-outline-info" onclick="exportarDatos()">
                                <i class="fas fa-download me-2"></i>Exportar Datos
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Asistencias Recientes -->
        <div class="row">
            <div class="col-12">
                <div class="recent-activity">
                    <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="mb-0" style="color: #2d3748; font-weight: 600;">
                            <i class="fas fa-clock me-2 text-info"></i>
                            Asistencias Registradas Hoy
                        </h5>
                        <a href="{{ route('asistencia.admin-index') }}" class="btn btn-sm btn-outline-primary">
                            Ver Todas
                        </a>
                    </div>
                    <div style="max-height: 500px; overflow-y: auto;">
                        @forelse($asistenciasHoy as $asistencia)
                            <div class="activity-item">
                                <div class="d-flex align-items-center">
                                    <div class="activity-avatar me-3">
                                        {{ substr($asistencia->matricula->estudiante->persona->nombres ?? 'N', 0, 1) }}
                                        {{ substr($asistencia->matricula->estudiante->persona->apellidos ?? 'A', 0, 1) }}
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1" style="color: #2d3748; font-weight: 600;">
                                                    {{ $asistencia->matricula->estudiante->persona->nombres ?? 'N/A' }}
                                                    {{ $asistencia->matricula->estudiante->persona->apellidos ?? 'N/A' }}
                                                </h6>
                                                <p class="mb-1 text-muted small">
                                                    {{ $asistencia->cursoAsignatura->asignatura->nombre ?? 'N/A' }} |
                                                    {{ $asistencia->cursoAsignatura->profesor->persona->nombres ?? 'N/A' }}
                                                    {{ $asistencia->cursoAsignatura->profesor->persona->apellidos ?? 'N/A' }}
                                                </p>
                                                <small class="text-muted">
                                                    Registrado: {{ $asistencia->hora_registro ? $asistencia->hora_registro->format('H:i') : 'N/A' }}
                                                </small>
                                            </div>
                                            <span class="status-badge status-{{ strtolower($asistencia->tipoAsistencia->codigo ?? 'presente') }}">
                                                {{ $asistencia->tipoAsistencia->nombre ?? 'Presente' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                <h6 class="text-muted">Sin asistencias registradas hoy</h6>
                                <p class="text-muted small">Las asistencias aparecerán aquí cuando sean registradas</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function exportarDatos() {
            if (confirm('¿Desea exportar un reporte general de asistencias?')) {
                window.location.href = '{{ route("asistencia.exportar-pdf-admin") }}';
            }
        }
    </script>
@endsection
