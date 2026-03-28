{{-- resources/views/asistencia/index.blade.php --}}
@extends('cplantilla.bprincipal')

@section('titulo', 'Gestión de Asistencia')

@section('contenidoplantilla')
    <style>
        /* Minimalist Design System */
        :root {
            --primary: #2563eb;
            --primary-light: #dbeafe;
            --success: #16a34a;
            --success-light: #f0fdf4;
            --danger: #dc2626;
            --danger-light: #fef2f2;
            --warning: #d97706;
            --warning-light: #fffbeb;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
            --border-radius: 8px;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --transition: all 0.2s ease;
        }

        /* Clean Typography */
        .text-sm { font-size: 0.875rem; line-height: 1.25rem; }
        .text-base { font-size: 1rem; line-height: 1.5rem; }
        .text-lg { font-size: 1.125rem; line-height: 1.75rem; }
        .text-xl { font-size: 1.25rem; line-height: 1.75rem; }
        .text-2xl { font-size: 1.5rem; line-height: 2rem; }
        .font-medium { font-weight: 500; }
        .font-semibold { font-weight: 600; }
        .font-bold { font-weight: 700; }
        .text-gray-600 { color: var(--gray-600); }
        .text-gray-700 { color: var(--gray-700); }
        .text-gray-900 { color: var(--gray-900); }

        /* Clean Layout */
        .container-clean { max-width: 1200px; margin: 0 auto; padding: 0 1rem; }
        .space-y-4 > * + * { margin-top: 1rem; }
        .space-y-6 > * + * { margin-top: 1.5rem; }
        .space-y-8 > * + * { margin-top: 2rem; }

        /* Minimalist Cards */
        .card-clean {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--gray-200);
        }

        .card-header-clean {
            padding: 1.5rem;
            border-bottom: 1px solid var(--gray-200);
            background: var(--gray-50);
        }

        .card-body-clean {
            padding: 1.5rem;
        }

        /* Clean Stats */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .stat-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: var(--shadow);
            border: 1px solid var(--gray-200);
            transition: var(--transition);
        }

        .stat-card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-1px);
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 0.25rem;
        }

        .stat-label {
            font-size: 0.875rem;
            color: var(--gray-600);
            font-weight: 500;
        }

        .stat-icon {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin-bottom: 0.75rem;
        }

        .stat-icon.presentes { background: var(--success); }
        .stat-icon.ausentes { background: var(--danger); }
        .stat-icon.tardanzas { background: var(--warning); }

        /* Session Cards - Minimalist */
        .session-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 1.5rem;
        }

        .session-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--gray-200);
            transition: var(--transition);
            overflow: hidden;
        }

        .session-card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-2px);
        }

        .session-header {
            padding: 1rem 1.5rem;
            background: var(--gray-50);
            border-bottom: 1px solid var(--gray-200);
        }

        .session-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--gray-900);
            margin-bottom: 0.25rem;
        }

        .session-meta {
            font-size: 0.875rem;
            color: var(--gray-600);
        }

        .session-status {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .session-status.activo {
            background: var(--success-light);
            color: var(--success);
        }

        .session-status.pendiente {
            background: var(--gray-100);
            color: var(--gray-600);
        }

        .session-status.finalizado {
            background: var(--warning-light);
            color: var(--warning);
        }

        .session-body {
            padding: 1.5rem;
        }

        .session-stats {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 1rem;
        }

        .stat-item {
            text-align: center;
            flex: 1;
        }

        .stat-number {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--gray-900);
        }

        .stat-label {
            font-size: 0.75rem;
            color: var(--gray-600);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 500;
        }

        .progress-bar {
            height: 4px;
            background: var(--gray-200);
            border-radius: 2px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: var(--success);
            transition: width 0.3s ease;
        }

        .session-actions {
            display: flex;
            gap: 0.5rem;
        }

        /* Clean Buttons */
        .btn-clean {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            border: 1px solid transparent;
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: #1d4ed8;
            box-shadow: var(--shadow);
        }

        .btn-outline {
            background: white;
            color: var(--gray-700);
            border-color: var(--gray-300);
        }

        .btn-outline:hover {
            background: var(--gray-50);
            border-color: var(--gray-400);
        }

        /* Header Section */
        .page-header {
            background: linear-gradient(135deg, var(--primary) 0%, #1e40af 100%);
            color: white;
            padding: 2rem 0;
            margin: -2rem -1rem 2rem -1rem;
        }

        .page-title {
            font-size: 1.875rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            font-size: 1rem;
            opacity: 0.9;
        }

        /* Date Selector */
        .date-selector {
            background: white;
            border: 1px solid var(--gray-300);
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            transition: var(--transition);
        }

        .date-selector:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        /* Quick Actions */
        .quick-actions {
            display: flex;
            gap: 0.75rem;
            align-items: center;
        }

        .action-btn {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 6px;
            border: none;
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
        }

        .action-btn:hover {
            background: #1d4ed8;
            transform: scale(1.05);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: var(--gray-500);
        }

        .empty-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        /* Loading */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .spinner {
            width: 2rem;
            height: 2rem;
            border: 2px solid var(--gray-300);
            border-top: 2px solid var(--primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .session-grid {
                grid-template-columns: 1fr;
            }

            .session-stats {
                flex-direction: column;
                gap: 1rem;
            }

            .quick-actions {
                flex-wrap: wrap;
            }
        }

        @media (max-width: 640px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .page-header {
                padding: 1.5rem 0;
                margin: -1.5rem -1rem 1.5rem -1rem;
            }

            .page-title {
                font-size: 1.5rem;
            }
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

                    <!-- Modern Info Panel -->
                    <div class="info-panel animate-fade-in-up">
                        <div class="d-flex align-items-start gap-3">
                            <i class="fas fa-info-circle info-icon"></i>
                            <div class="flex-fill">
                                <h5><i class="fas fa-clipboard-check text-primary me-2"></i>Gestión de Asistencia</h5>
                                <p>Registra y gestiona la asistencia de tus estudiantes de forma eficiente y organizada.</p>
                                <p>Utiliza las herramientas disponibles para mantener un control preciso del desempeño estudiantil y generar reportes detallados.</p>
                            </div>
                        </div>
                    </div>

                    <div class="collapse show" id="collapseAsistencia">
                        <div class="modern-card animate-fade-in-up" style="background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);">

                            <!-- Header con fecha y acciones -->
                            <div class="row align-items-center mb-4">
                                <div class="col-lg-4 col-md-6 mb-3 mb-md-0">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="session-avatar">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                        <div>
                                            <h5 class="estilo-info mb-0">
                                                {{ Carbon\Carbon::parse($fecha)->isoFormat('dddd, D [de] MMMM') }}
                                            </h5>
                                            <small class="text-muted">{{ Carbon\Carbon::parse($fecha)->isoFormat('YYYY') }}</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 mb-3 mb-md-0">
                                    <div class="quick-actions-bar">
                                        <button class="quick-action-btn" onclick="exportarExcel()" title="Exportar Reporte">
                                            <i class="fas fa-file-excel"></i>
                                        </button>
                                        <a href="{{ route('asistencia.reporte-general') }}" class="btn btn-outline-modern btn-modern" title="Ver Reportes">
                                            <i class="fas fa-chart-pie me-2"></i>Reportes
                                        </a>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-12">
                                    <div class="date-selector-modern">
                                        <label><i class="fas fa-calendar-day me-2"></i>Seleccionar Fecha</label>
                                        <input type="date" id="fecha-selector" class="form-control"
                                            value="{{ $fecha }}" onchange="cambiarFecha(this.value)"
                                            aria-label="Seleccionar fecha de asistencia">
                                    </div>
                                </div>
                            </div>

                            <!-- Estadísticas del Día -->
                            <div class="stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
                                <div class="stats-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="flex-fill">
                                                <div class="stat-icon">
                                                    <i class="fas fa-users"></i>
                                                </div>
                                                <h3>{{ number_format($estadisticas['total_registros']) }}</h3>
                                                <p class="mb-2">Total Registros</p>
                                                <div class="progress-modern">
                                                    <div class="progress-bar" style="width: 100%"></div>
                                                </div>
                                                <small class="text-muted mt-1 d-block">Registros completados</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="stats-card presentes">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="flex-fill">
                                                <div class="stat-icon presentes">
                                                    <i class="fas fa-check-circle"></i>
                                                </div>
                                                <h3>{{ number_format($estadisticas['presentes']) }}</h3>
                                                <p class="mb-2">Presentes</p>
                                                <div class="progress-modern">
                                                    <div class="progress-bar" style="width: {{ $estadisticas['porcentaje_asistencia'] }}%"></div>
                                                </div>
                                                <small class="text-muted mt-1 d-block">{{ $estadisticas['porcentaje_asistencia'] }}% del total</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="stats-card ausentes">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="flex-fill">
                                                <div class="stat-icon ausentes">
                                                    <i class="fas fa-times-circle"></i>
                                                </div>
                                                <h3>{{ number_format($estadisticas['ausentes']) }}</h3>
                                                <p class="mb-2">Ausentes</p>
                                                <div class="progress-modern">
                                                    <div class="progress-bar" style="width: {{ $estadisticas['total_registros'] > 0 ? ($estadisticas['ausentes'] / $estadisticas['total_registros']) * 100 : 0 }}%"></div>
                                                </div>
                                                <small class="text-muted mt-1 d-block">{{ $estadisticas['total_registros'] > 0 ? round(($estadisticas['ausentes'] / $estadisticas['total_registros']) * 100, 1) : 0 }}% del total</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="stats-card tardanzas">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="flex-fill">
                                                <div class="stat-icon tardanzas">
                                                    <i class="fas fa-clock"></i>
                                                </div>
                                                <h3>{{ number_format($estadisticas['tardanzas']) }}</h3>
                                                <p class="mb-2">Tardanzas</p>
                                                <div class="progress-modern">
                                                    <div class="progress-bar" style="width: {{ $estadisticas['total_registros'] > 0 ? ($estadisticas['tardanzas'] / $estadisticas['total_registros']) * 100 : 0 }}%"></div>
                                                </div>
                                                <small class="text-muted mt-1 d-block">{{ $estadisticas['total_registros'] > 0 ? round(($estadisticas['tardanzas'] / $estadisticas['total_registros']) * 100, 1) : 0 }}% del total</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sesiones Programadas -->
                            <div class="modern-card animate-fade-in-up" style="margin-bottom: 0;">
                                <div class="modern-card-header">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="session-avatar" style="background: rgba(255, 255, 255, 0.2);">
                                                <i class="fas fa-chalkboard-teacher"></i>
                                            </div>
                                            <div>
                                                <h5 class="mb-0" style="color: white; font-weight: 700;">
                                                    Sesiones Programadas
                                                </h5>
                                                <small style="color: rgba(255, 255, 255, 0.8);">
                                                    Gestiona la asistencia de tus clases del día
                                                </small>
                                            </div>
                                        </div>
                                        <div class="session-status" style="background: rgba(255, 255, 255, 0.2); color: white;">
                                            <span class="status-dot"></span>
                                            {{ $sesiones->count() }} sesiones
                                        </div>
                                    </div>
                                </div>
                                <div class="modern-card-body">
                                    @if ($sesiones->isEmpty())
                                        @php
                                            $esFeriado = \App\Models\Feriado::esFeriado($fecha);
                                            $feriadoInfo = $esFeriado ? \App\Models\Feriado::porFecha($fecha)->first() : null;
                                        @endphp

                                        @if($esFeriado)
                                            <div class="empty-state-modern">
                                                <i class="fas fa-calendar-day empty-icon" style="color: var(--danger-color);"></i>
                                                <h5 style="color: var(--danger-color);">Día Feriado</h5>
                                                <p><strong>{{ $feriadoInfo->nombre }}</strong></p>
                                                @if($feriadoInfo->descripcion)
                                                    <p>{{ $feriadoInfo->descripcion }}</p>
                                                @endif
                                                <div class="alert alert-info" style="background: var(--info-light); border-color: var(--info-color); color: var(--info-color);">
                                                    <i class="fas fa-info-circle me-2"></i>
                                                    No hay sesiones programadas porque es feriado. Si hay clases de recuperación programadas, aparecerán en fechas posteriores.
                                                </div>
                                            </div>
                                        @else
                                            <div class="empty-state-modern">
                                                <i class="fas fa-calendar-times empty-icon"></i>
                                                <h5>No tienes sesiones programadas</h5>
                                                <p>Revisa el horario académico o selecciona otra fecha para ver las clases disponibles.</p>
                                                <button class="btn btn-primary-modern btn-modern mt-3" onclick="document.getElementById('fecha-selector').focus()">
                                                    <i class="fas fa-calendar-day me-2"></i>Cambiar Fecha
                                                </button>
                                            </div>
                                        @endif
                                    @else
                                        <div class="row g-4">
                                            @foreach ($sesiones as $index => $sesion)
                                                <div class="col-lg-6 col-xl-4">
                                                    <div class="session-card animate-fade-in-up" style="animation-delay: {{ $index * 0.1 }}s;">
                                                        <div class="session-header">
                                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                                <div class="session-info">
                                                                    <div class="session-avatar">
                                                                        <i class="fas fa-book"></i>
                                                                    </div>
                                                                    <div class="session-details">
                                                                        <h6>{{ $sesion->cursoAsignatura->asignatura->nombre }}</h6>
                                                                        <small>
                                                                            <i class="fas fa-graduation-cap me-1"></i>
                                                                            {{ $sesion->cursoAsignatura->curso->grado->nombre }} -
                                                                            {{ $sesion->cursoAsignatura->curso->seccion->nombre }}
                                                                        </small>
                                                                    </div>
                                                                </div>
                                                                @php
                                                                    $ahora = now();
                                                                    if (str_contains($sesion->hora_inicio, ' ')) {
                                                                        $inicio = Carbon\Carbon::parse($sesion->hora_inicio);
                                                                    } else {
                                                                        $inicio = Carbon\Carbon::parse($fecha . ' ' . $sesion->hora_inicio);
                                                                    }

                                                                    if (str_contains($sesion->hora_fin, ' ')) {
                                                                        $fin = Carbon\Carbon::parse($sesion->hora_fin);
                                                                    } else {
                                                                        $fin = Carbon\Carbon::parse($fecha . ' ' . $sesion->hora_fin);
                                                                    }

                                                                    $estadoClase = 'pendiente';
                                                                    if ($ahora->between($inicio, $fin)) {
                                                                        $estadoClase = 'activo';
                                                                    } elseif ($ahora->gt($fin)) {
                                                                        $estadoClase = 'finalizado';
                                                                    }
                                                                @endphp
                                                                <div class="session-status {{ $estadoClase }}">
                                                                    <span class="status-dot {{ $estadoClase }}"></span>
                                                                    {{ ucfirst($estadoClase) }}
                                                                </div>
                                                            </div>
                                                            <div class="session-time">
                                                                <i class="far fa-clock me-2"></i>
                                                                {{ $sesion->hora_inicio }} - {{ $sesion->hora_fin }}
                                                            </div>
                                                        </div>

                                                        <div class="session-body">
                                                            @php
                                                                $asistenciasRegistradas = \App\Models\AsistenciaAsignatura::where(
                                                                    'curso_asignatura_id', $sesion->curso_asignatura_id,
                                                                )->where('fecha', $fecha)->count();
                                                                $totalEstudiantes = \App\Models\Matricula::where(
                                                                    'curso_id', $sesion->cursoAsignatura->curso_id,
                                                                )->where('estado', 'Matriculado')->count();
                                                                $porcentajeRegistro = $totalEstudiantes > 0
                                                                    ? round(($asistenciasRegistradas / $totalEstudiantes) * 100)
                                                                    : 0;
                                                            @endphp

                                                            <div class="session-stats">
                                                                <div class="stat-item">
                                                                    <div class="stat-number">{{ $totalEstudiantes }}</div>
                                                                    <div class="stat-label">Estudiantes</div>
                                                                </div>
                                                                <div class="stat-item">
                                                                    <div class="stat-number">{{ $asistenciasRegistradas }}</div>
                                                                    <div class="stat-label">Registrados</div>
                                                                </div>
                                                                <div class="stat-item">
                                                                    <div class="stat-number">{{ $porcentajeRegistro }}%</div>
                                                                    <div class="stat-label">Completado</div>
                                                                </div>
                                                            </div>

                                                            <div class="progress-session">
                                                                <div class="progress-bar" style="width: {{ $porcentajeRegistro }}%"></div>
                                                            </div>

                                                            <div class="session-actions">
                                                                <a href="{{ route('asistencia.registrar-asignatura', [$sesion->curso_asignatura_id, $fecha]) }}"
                                                                    class="btn btn-primary-modern btn-modern flex-fill">
                                                                    <i class="fas fa-edit me-2"></i>
                                                                    {{ $asistenciasRegistradas > 0 ? 'Editar Asistencia' : 'Registrar Asistencia' }}
                                                                </a>

                                                                @if ($asistenciasRegistradas > 0)
                                                                    <a href="{{ route('asistencia.reporte-curso', $sesion->curso_asignatura_id) }}"
                                                                        class="btn btn-outline-modern btn-modern"
                                                                        title="Ver Reporte">
                                                                        <i class="fas fa-chart-bar"></i>
                                                                    </a>
                                                                @endif
                                                            </div>

                                                            @if ($estadoClase === 'activo')
                                                                <div class="alert alert-success mt-3 mb-0 py-2" style="background: var(--success-light); border-color: var(--success-color); color: var(--success-color);">
                                                                    <i class="fas fa-play-circle me-2"></i>
                                                                    <strong>¡Clase en progreso!</strong> Registra la asistencia ahora.
                                                                </div>
                                                            @elseif($estadoClase === 'finalizado' && $asistenciasRegistradas == 0)
                                                                <div class="alert alert-warning mt-3 mb-0 py-2" style="background: var(--warning-light); border-color: var(--warning-color); color: var(--warning-color);">
                                                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                                                    <strong>Asistencia pendiente</strong> - Clase ya finalizó.
                                                                </div>
                                                            @elseif($estadoClase === 'finalizado' && $asistenciasRegistradas > 0)
                                                                <div class="alert alert-info mt-3 mb-0 py-2" style="background: var(--info-light); border-color: var(--info-color); color: var(--info-color);">
                                                                    <i class="fas fa-check-circle me-2"></i>
                                                                    Asistencia registrada correctamente.
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
                                        @php
                                            $esFeriado = \App\Models\Feriado::esFeriado($fecha);
                                            $feriadoInfo = $esFeriado ? \App\Models\Feriado::porFecha($fecha)->first() : null;
                                        @endphp

                                        @if($esFeriado)
                                            <div class="empty-state-modern">
                                                <i class="fas fa-calendar-day empty-icon" style="color: var(--danger-color);"></i>
                                                <h5 style="color: var(--danger-color);">Día Feriado</h5>
                                                <p><strong>{{ $feriadoInfo->nombre }}</strong></p>
                                                @if($feriadoInfo->descripcion)
                                                    <p>{{ $feriadoInfo->descripcion }}</p>
                                                @endif
                                                <div class="alert alert-info" style="background: var(--info-light); border-color: var(--info-color); color: var(--info-color);">
                                                    <i class="fas fa-info-circle me-2"></i>
                                                    No hay sesiones programadas porque es feriado. Si hay clases de recuperación programadas, aparecerán en fechas posteriores.
                                                </div>
                                            </div>
                                        @else
                                            <div class="empty-state-modern">
                                                <i class="fas fa-calendar-times empty-icon"></i>
                                                <h5>No tienes sesiones programadas</h5>
                                                <p>Revisa el horario académico o selecciona otra fecha para ver las clases disponibles.</p>
                                                <button class="btn btn-primary-modern btn-modern mt-3" onclick="document.getElementById('fecha-selector').focus()">
                                                    <i class="fas fa-calendar-day me-2"></i>Cambiar Fecha
                                                </button>
                                            </div>
                                        @endif
                                    @else
                                        <div class="row g-4">
                                            @foreach ($sesiones as $index => $sesion)
                                                <div class="col-lg-6 col-xl-4">
                                                    <div class="session-card animate-fade-in-up" style="animation-delay: {{ $index * 0.1 }}s;">
                                                        <div class="session-header">
                                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                                <div class="session-info">
                                                                    <div class="session-avatar">
                                                                        <i class="fas fa-book"></i>
                                                                    </div>
                                                                    <div class="session-details">
                                                                        <h6>{{ $sesion->cursoAsignatura->asignatura->nombre }}</h6>
                                                                        <small>
                                                                            <i class="fas fa-graduation-cap me-1"></i>
                                                                            {{ $sesion->cursoAsignatura->curso->grado->nombre }} -
                                                                            {{ $sesion->cursoAsignatura->curso->seccion->nombre }}
                                                                        </small>
                                                                    </div>
                                                                </div>
                                                                @php
                                                                    $ahora = now();
                                                                    if (str_contains($sesion->hora_inicio, ' ')) {
                                                                        $inicio = Carbon\Carbon::parse($sesion->hora_inicio);
                                                                    } else {
                                                                        $inicio = Carbon\Carbon::parse($fecha . ' ' . $sesion->hora_inicio);
                                                                    }

                                                                    if (str_contains($sesion->hora_fin, ' ')) {
                                                                        $fin = Carbon\Carbon::parse($sesion->hora_fin);
                                                                    } else {
                                                                        $fin = Carbon\Carbon::parse($fecha . ' ' . $sesion->hora_fin);
                                                                    }

                                                                    $estadoClase = 'pendiente';
                                                                    if ($ahora->between($inicio, $fin)) {
                                                                        $estadoClase = 'activo';
                                                                    } elseif ($ahora->gt($fin)) {
                                                                        $estadoClase = 'finalizado';
                                                                    }
                                                                @endphp
                                                                <div class="session-status {{ $estadoClase }}">
                                                                    <span class="status-dot {{ $estadoClase }}"></span>
                                                                    {{ ucfirst($estadoClase) }}
                                                                </div>
                                                            </div>
                                                            <div class="session-time">
                                                                <i class="far fa-clock me-2"></i>
                                                                {{ $sesion->hora_inicio }} - {{ $sesion->hora_fin }}
                                                            </div>
                                                        </div>

                                                        <div class="session-body">
                                                            @php
                                                                $asistenciasRegistradas = \App\Models\AsistenciaAsignatura::where(
                                                                    'curso_asignatura_id', $sesion->curso_asignatura_id,
                                                                )->where('fecha', $fecha)->count();
                                                                $totalEstudiantes = \App\Models\Matricula::where(
                                                                    'curso_id', $sesion->cursoAsignatura->curso_id,
                                                                )->where('estado', 'Matriculado')->count();
                                                                $porcentajeRegistro = $totalEstudiantes > 0
                                                                    ? round(($asistenciasRegistradas / $totalEstudiantes) * 100)
                                                                    : 0;
                                                            @endphp

                                                            <div class="session-stats">
                                                                <div class="stat-item">
                                                                    <div class="stat-number">{{ $totalEstudiantes }}</div>
                                                                    <div class="stat-label">Estudiantes</div>
                                                                </div>
                                                                <div class="stat-item">
                                                                    <div class="stat-number">{{ $asistenciasRegistradas }}</div>
                                                                    <div class="stat-label">Registrados</div>
                                                                </div>
                                                                <div class="stat-item">
                                                                    <div class="stat-number">{{ $porcentajeRegistro }}%</div>
                                                                    <div class="stat-label">Completado</div>
                                                                </div>
                                                            </div>

                                                            <div class="progress-session">
                                                                <div class="progress-bar" style="width: {{ $porcentajeRegistro }}%"></div>
                                                            </div>

                                                            <div class="session-actions">
                                                                <a href="{{ route('asistencia.registrar-asignatura', [$sesion->curso_asignatura_id, $fecha]) }}"
                                                                    class="btn btn-primary-modern btn-modern flex-fill">
                                                                    <i class="fas fa-edit me-2"></i>
                                                                    {{ $asistenciasRegistradas > 0 ? 'Editar Asistencia' : 'Registrar Asistencia' }}
                                                                </a>

                                                                @if ($asistenciasRegistradas > 0)
                                                                    <a href="{{ route('asistencia.reporte-curso', $sesion->curso_asignatura_id) }}"
                                                                        class="btn btn-outline-modern btn-modern"
                                                                        title="Ver Reporte">
                                                                        <i class="fas fa-chart-bar"></i>
                                                                    </a>
                                                                @endif
                                                            </div>

                                                            @if ($estadoClase === 'activo')
                                                                <div class="alert alert-success mt-3 mb-0 py-2" style="background: var(--success-light); border-color: var(--success-color); color: var(--success-color);">
                                                                    <i class="fas fa-play-circle me-2"></i>
                                                                    <strong>¡Clase en progreso!</strong> Registra la asistencia ahora.
                                                                </div>
                                                            @elseif($estadoClase === 'finalizado' && $asistenciasRegistradas == 0)
                                                                <div class="alert alert-warning mt-3 mb-0 py-2" style="background: var(--warning-light); border-color: var(--warning-color); color: var(--warning-color);">
                                                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                                                    <strong>Asistencia pendiente</strong> - Clase ya finalizó.
                                                                </div>
                                                            @elseif($estadoClase === 'finalizado' && $asistenciasRegistradas > 0)
                                                                <div class="alert alert-info mt-3 mb-0 py-2" style="background: var(--info-light); border-color: var(--info-color); color: var(--info-color);">
                                                                    <i class="fas fa-check-circle me-2"></i>
                                                                    Asistencia registrada correctamente.
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
