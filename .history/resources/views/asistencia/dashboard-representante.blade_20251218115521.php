@extends('cplantilla.bprincipal')

@section('titulo', 'Dashboard de Asistencias - Representante')

@section('contenidoplantilla')
    <style>
        .student-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
            margin-bottom: 1.5rem;
        }

        .student-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .student-header {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }

        .student-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1.2rem;
            margin-right: 1rem;
        }

        .student-info h5 {
            margin: 0;
            color: #2d3748;
            font-weight: 600;
        }

        .student-grade {
            color: #718096;
            font-size: 0.9rem;
            margin: 0.25rem 0;
        }

        .attendance-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .stat-item {
            text-align: center;
            padding: 0.75rem;
            border-radius: 8px;
            background: #f8fafc;
        }

        .stat-number {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .stat-label {
            font-size: 0.8rem;
            color: #718096;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .attendance-good {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            color: white;
        }

        .attendance-warning {
            background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%);
            color: white;
        }

        .attendance-danger {
            background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%);
            color: white;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
            flex-wrap: wrap;
        }

        .btn-action {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
        }

        .btn-action:hover {
            transform: translateY(-1px);
            text-decoration: none;
        }

        .pending-justifications {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border: 1px solid #e9ecef;
            margin-bottom: 2rem;
        }

        .justification-item {
            padding: 1rem;
            border-bottom: 1px solid #f7fafc;
            transition: all 0.2s ease;
        }

        .justification-item:hover {
            background-color: #f8fafc;
        }

        .justification-item:last-child {
            border-bottom: none;
        }

        .justification-header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .justification-title {
            font-weight: 600;
            color: #2d3748;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pendiente {
            background: #fef3c7;
            color: #d97706;
        }

        .status-aprobado {
            background: #d1fae5;
            color: #065f46;
        }

        .status-rechazado {
            background: #fee2e2;
            color: #dc2626;
        }

        .welcome-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
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

        @media (max-width: 768px) {
            .student-header {
                flex-direction: column;
                text-align: center;
            }

            .student-avatar {
                margin-right: 0;
                margin-bottom: 1rem;
            }

            .attendance-stats {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                justify-content: center;
            }

            .quick-actions {
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
                        <h2 class="mb-1" style="color: #2d3748; font-weight: 700;">Panel de Representante</h2>
                        <p class="text-muted mb-0">Monitoreo de asistencia de tus estudiantes</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary" onclick="location.reload()">
                            <i class="fas fa-sync-alt me-2"></i>Actualizar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones Rápidas -->
        <div class="quick-actions">
            <div class="action-card" onclick="location.href='{{ route('asistencia.misEstudiantes') }}'">
                <div class="action-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <i class="fas fa-users text-white"></i>
                </div>
                <div class="action-title">Mis Estudiantes</div>
                <div class="action-description">Ver lista completa de estudiantes</div>
            </div>

            <div class="action-card" onclick="location.href='{{ route('asistencia.justificar') }}'">
                <div class="action-icon" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);">
                    <i class="fas fa-file-signature text-white"></i>
                </div>
                <div class="action-title">Justificar Inasistencia</div>
                <div class="action-description">Enviar solicitud de justificación</div>
            </div>

            <div class="action-card" onclick="location.href='{{ route('asistencia.mis-justificaciones') }}'">
                <div class="action-icon" style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%);">
                    <i class="fas fa-clipboard-list text-white"></i>
                </div>
                <div class="action-title">Mis Justificaciones</div>
                <div class="action-description">Ver estado de justificaciones enviadas</div>
            </div>

            <div class="action-card" onclick="location.href='{{ route('notas.misEstudiantes') }}'">
                <div class="action-icon" style="background: linear-gradient(135deg, #805ad5 0%, #6b46c1 100%);">
                    <i class="fas fa-chart-line text-white"></i>
                </div>
                <div class="action-title">Ver Notas</div>
                <div class="action-description">Consultar rendimiento académico</div>
            </div>
        </div>

        <!-- Justificaciones Pendientes -->
        @if($justificacionesPendientes->count() > 0)
        <div class="pending-justifications">
            <div class="p-3 border-bottom" style="background: linear-gradient(135deg, #fed7d7 0%, #feb2b2 100%); color: #c53030;">
                <h5 class="mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Justificaciones Pendientes de Revisión ({{ $justificacionesPendientes->count() }})
                </h5>
            </div>
            <div style="max-height: 300px; overflow-y: auto;">
                @foreach($justificacionesPendientes as $justificacion)
                    <div class="justification-item">
                        <div class="justification-header">
                            <div class="justification-title">
                                {{ $justificacion->matricula->estudiante->persona->nombres ?? 'N/A' }}
                                {{ $justificacion->matricula->estudiante->persona->apellidos ?? 'N/A' }}
                            </div>
                            <span class="status-badge status-pendiente">Pendiente</span>
                        </div>
                        <div class="text-muted small">
                            Fecha: {{ \Carbon\Carbon::parse($justificacion->fecha)->format('d/m/Y') }} |
                            Motivo: {{ $justificacion->motivo }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Lista de Estudiantes -->
        <div class="row">
            <div class="col-12">
                <h4 class="mb-4" style="color: #2d3748; font-weight: 600;">
                    <i class="fas fa-user-graduate me-2 text-primary"></i>
                    Mis Estudiantes ({{ $estudiantesRepresentados->count() }})
                </h4>

                @forelse($estudiantesRepresentados as $estudianteData)
                    <div class="student-card">
                        <div class="student-header">
                            <div class="student-avatar">
                                {{ substr($estudianteData['estudiante']->persona->nombres ?? 'N', 0, 1) }}
                                {{ substr($estudianteData['estudiante']->persona->apellidos ?? 'A', 0, 1) }}
                            </div>
                            <div class="student-info flex-grow-1">
                                <h5>{{ $estudianteData['estudiante']->persona->nombres ?? 'N/A' }} {{ $estudianteData['estudiante']->persona->apellidos ?? 'N/A' }}</h5>
                                <div class="student-grade">
                                    <i class="fas fa-graduation-cap me-1"></i>
                                    {{ $estudianteData['matricula_principal']->grado->nombre ?? 'N/A' }} -
                                    {{ $estudianteData['matricula_principal']->seccion->nombre ?? 'N/A' }}
                                    @if($estudianteData['es_principal'])
                                        <span class="badge bg-primary ms-2">Principal</span>
                                    @endif
                                </div>
                                <div class="text-muted small">
                                    <i class="fas fa-id-card me-1"></i>
                                    DNI: {{ $estudianteData['estudiante']->persona->dni ?? 'N/A' }}
                                </div>
                            </div>
                        </div>

                        <!-- Estadísticas de Asistencia -->
                        <div class="attendance-stats">
                            <div class="stat-item {{ $estadisticas[$estudianteData['estudiante']->estudiante_id]['porcentaje'] >= 85 ? 'attendance-good' : ($estadisticas[$estudianteData['estudiante']->estudiante_id]['porcentaje'] >= 70 ? 'attendance-warning' : 'attendance-danger') }}">
                                <div class="stat-number">{{ $estadisticas[$estudianteData['estudiante']->estudiante_id]['porcentaje'] }}%</div>
                                <div class="stat-label">Asistencia</div>
                            </div>

                            <div class="stat-item">
                                <div class="stat-number">{{ $estadisticas[$estudianteData['estudiante']->estudiante_id]['positivas'] }}</div>
                                <div class="stat-label">Días Presente</div>
                            </div>

                            <div class="stat-item">
                                <div class="stat-number">{{ $estadisticas[$estudianteData['estudiante']->estudiante_id]['total'] - $estadisticas[$estudianteData['estudiante']->estudiante_id]['positivas'] }}</div>
                                <div class="stat-label">Días Ausente</div>
                            </div>
                        </div>

                        <!-- Acciones -->
                        <div class="action-buttons">
                            <a href="{{ route('asistencia.detalle-estudiante', $estudianteData['matricula_principal']->matricula_id) }}"
                               class="btn-action btn btn-outline-primary">
                                <i class="fas fa-eye"></i>
                                Ver Detalle
                            </a>

                            <a href="{{ route('asistencia.justificar') }}"
                               class="btn-action btn btn-outline-success">
                                <i class="fas fa-file-signature"></i>
                                Justificar
                            </a>

                            <a href="{{ route('notas.estudiante', $estudianteData['matricula_principal']->matricula_id) }}"
                               class="btn-action btn btn-outline-info">
                                <i class="fas fa-chart-line"></i>
                                Ver Notas
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                        <h6 class="text-muted">No tienes estudiantes asignados</h6>
                        <p class="text-muted small">Si crees que esto es un error, contacta al administrador</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Consejos y Recordatorios -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header" style="background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); color: white; border-radius: 12px 12px 0 0;">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            Consejos para Representantes
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-lightbulb me-2"></i>
                                    Mantén una buena comunicación
                                </h6>
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        Revisa regularmente la asistencia de tus estudiantes
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        Justifica las ausencias lo antes posible
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        Mantente informado sobre el rendimiento académico
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
                                        Las justificaciones deben enviarse dentro de los 7 días
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-file-alt text-info me-2"></i>
                                        Adjunta documentos que respalden las justificaciones
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-bell text-danger me-2"></i>
                                        Monitorea las ausencias prolongadas
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
        // Función para mostrar notificaciones
        function mostrarNotificacion(tipo, mensaje) {
            // Implementar notificaciones si es necesario
            console.log(tipo + ': ' + mensaje);
        }
    </script>
@endsection
