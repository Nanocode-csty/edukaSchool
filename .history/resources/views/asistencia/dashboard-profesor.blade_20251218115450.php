@extends('cplantilla.bprincipal')

@section('titulo', 'Dashboard de Asistencias - Profesor')

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

        .session-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
            margin-bottom: 1rem;
        }

        .session-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }

        .session-header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .session-title {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 0.25rem;
        }

        .session-info {
            color: #718096;
            font-size: 0.9rem;
        }

        .session-time {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .upcoming-session {
            border-left: 4px solid #667eea;
        }

        .today-session {
            border-left: 4px solid #48bb78;
        }

        .action-btn {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .action-btn:hover {
            transform: translateY(-1px);
            text-decoration: none;
        }

        .quick-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-top: 2rem;
        }

        .quick-stat-item {
            background: white;
            border-radius: 12px;
            padding: 1rem;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border: 1px solid #e9ecef;
        }

        .quick-stat-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2d3748;
            margin: 0.5rem 0;
        }

        .quick-stat-label {
            font-size: 0.8rem;
            color: #718096;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .welcome-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        @media (max-width: 768px) {
            .session-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }

            .quick-stats {
                grid-template-columns: 1fr;
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
                        <h2 class="mb-1" style="color: #2d3748; font-weight: 700;">Panel de Profesor</h2>
                        <p class="text-muted mb-0">Gestión de asistencias académicas</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary" onclick="location.reload()">
                            <i class="fas fa-sync-alt me-2"></i>Actualizar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas Rápidas -->
        <div class="quick-stats">
            <div class="quick-stat-item">
                <div class="stat-icon mx-auto" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); width: 40px; height: 40px; margin-bottom: 0.5rem;">
                    <i class="fas fa-check-circle text-white"></i>
                </div>
                <div class="quick-stat-number text-success">{{ $estadisticas['presentes'] ?? 0 }}</div>
                <div class="quick-stat-label">Presentes Hoy</div>
            </div>

            <div class="quick-stat-item">
                <div class="stat-icon mx-auto" style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); width: 40px; height: 40px; margin-bottom: 0.5rem;">
                    <i class="fas fa-clock text-white"></i>
                </div>
                <div class="quick-stat-number text-warning">{{ $estadisticas['tardanzas'] ?? 0 }}</div>
                <div class="quick-stat-label">Tardanzas Hoy</div>
            </div>

            <div class="quick-stat-item">
                <div class="stat-icon mx-auto" style="background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%); width: 40px; height: 40px; margin-bottom: 0.5rem;">
                    <i class="fas fa-times-circle text-white"></i>
                </div>
                <div class="quick-stat-number text-danger">{{ $estadisticas['ausentes'] ?? 0 }}</div>
                <div class="quick-stat-label">Ausentes Hoy</div>
            </div>

            <div class="quick-stat-item">
                <div class="stat-icon mx-auto" style="background: linear-gradient(135deg, #805ad5 0%, #6b46c1 100%); width: 40px; height: 40px; margin-bottom: 0.5rem;">
                    <i class="fas fa-percentage text-white"></i>
                </div>
                <div class="quick-stat-number text-primary">{{ $estadisticas['porcentaje_asistencia'] ?? 0 }}%</div>
                <div class="quick-stat-label">Asistencia Hoy</div>
            </div>
        </div>

        <!-- Sesiones de Hoy -->
        <div class="row mt-4">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; border-radius: 12px 12px 0 0;">
                        <h5 class="mb-0">
                            <i class="fas fa-calendar-day me-2"></i>
                            Sesiones de Hoy
                        </h5>
                    </div>
                    <div class="card-body">
                        @forelse($sesionesHoy as $sesion)
                            <div class="session-card today-session">
                                <div class="session-header">
                                    <div class="flex-grow-1">
                                        <div class="session-title">
                                            {{ $sesion->cursoAsignatura->asignatura->nombre ?? 'N/A' }}
                                        </div>
                                        <div class="session-info">
                                            {{ $sesion->cursoAsignatura->curso->grado->nombre ?? 'N/A' }} -
                                            {{ $sesion->cursoAsignatura->curso->seccion->nombre ?? 'N/A' }}
                                        </div>
                                    </div>
                                    <div class="session-time">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ \Carbon\Carbon::parse($sesion->hora_inicio)->format('H:i') }} -
                                        {{ \Carbon\Carbon::parse($sesion->hora_fin)->format('H:i') }}
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-muted small">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        Aula: {{ $sesion->aula ?? 'Por asignar' }}
                                    </div>
                                    <div>
                                        <a href="{{ route('asistencia.registrar-asignatura', [$sesion->cursoAsignatura->curso_asignatura_id, today()->format('Y-m-d')]) }}"
                                           class="action-btn btn btn-primary btn-sm">
                                            <i class="fas fa-edit"></i>
                                            Registrar Asistencia
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                <h6 class="text-muted">No hay sesiones programadas para hoy</h6>
                                <p class="text-muted small">Tus sesiones aparecerán aquí cuando estén programadas</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Próximas Sesiones -->
                <div class="card">
                    <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 12px 12px 0 0;">
                        <h5 class="mb-0">
                            <i class="fas fa-calendar-alt me-2"></i>
                            Próximas Sesiones
                        </h5>
                    </div>
                    <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                        @forelse($proximasSesiones as $sesion)
                            <div class="session-card upcoming-session">
                                <div class="session-header">
                                    <div class="flex-grow-1">
                                        <div class="session-title">
                                            {{ $sesion->cursoAsignatura->asignatura->nombre ?? 'N/A' }}
                                        </div>
                                        <div class="session-info">
                                            {{ \Carbon\Carbon::parse($sesion->fecha)->format('d/m') }} -
                                            {{ $sesion->cursoAsignatura->curso->grado->nombre ?? 'N/A' }}
                                        </div>
                                    </div>
                                    <div class="session-time">
                                        {{ \Carbon\Carbon::parse($sesion->hora_inicio)->format('H:i') }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <i class="fas fa-calendar-check fa-2x text-muted mb-2"></i>
                                <p class="text-muted small">No hay sesiones próximas programadas</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Acciones Rápidas -->
                <div class="card mt-3">
                    <div class="card-header" style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); color: white; border-radius: 12px 12px 0 0;">
                        <h5 class="mb-0">
                            <i class="fas fa-bolt me-2"></i>
                            Acciones Rápidas
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('asistencia.index') }}" class="btn btn-outline-primary">
                                <i class="fas fa-calendar-week me-2"></i>Asistencia Diaria
                            </a>
                            <a href="{{ route('notas.inicio') }}" class="btn btn-outline-success">
                                <i class="fas fa-clipboard-list me-2"></i>Registrar Notas
                            </a>
                            <button class="btn btn-outline-info" onclick="verReportes()">
                                <i class="fas fa-chart-bar me-2"></i>Ver Reportes
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información Adicional -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header" style="background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); color: white; border-radius: 12px 12px 0 0;">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            Información y Consejos
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-lightbulb me-2"></i>
                                    Consejos para una buena asistencia
                                </h6>
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        Registra la asistencia al inicio de cada clase
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        Verifica los estudiantes ausentes regularmente
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        Mantén un registro preciso de tardanzas
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-warning mb-3">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Recordatorios importantes
                                </h6>
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <i class="fas fa-clock text-warning me-2"></i>
                                        Las justificaciones deben revisarse semanalmente
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-users text-info me-2"></i>
                                        Reporta ausencias prolongadas al coordinador
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-file-alt text-primary me-2"></i>
                                        Mantén respaldos de tus registros
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function verReportes() {
            // Redirigir a la página de reportes del profesor
            window.location.href = '{{ route("asistencia.index") }}';
        }
    </script>
@endsection
