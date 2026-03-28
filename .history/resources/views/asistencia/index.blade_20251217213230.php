@extends('cplantilla.bprincipal')

@section('titulo', 'Panel de Asistencias - Eduka')

@section('contenidoplantilla')
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #17a2b8;
            --light-bg: #f8f9fa;
            --border-radius: 12px;
            --shadow: 0 2px 10px rgba(0,0,0,0.1);
            --transition: all 0.2s ease;
        }

        body {
            background: #f5f7fa;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            border: 1px solid rgba(255, 255, 255, 0.8);
        }

        .gradient-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 1.5rem;
            border-radius: var(--border-radius) var(--border-radius) 0 0;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin: 1.5rem 0;
        }

        .stat-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            text-align: center;
            box-shadow: var(--shadow);
            border-left: 4px solid var(--primary-color);
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.5rem;
            color: white;
        }

        .stat-presentes .stat-icon { background: var(--success-color); }
        .stat-ausentes .stat-icon { background: var(--danger-color); }
        .stat-tardanzas .stat-icon { background: var(--warning-color); }
        .stat-total .stat-icon { background: var(--info-color); }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin: 0.5rem 0;
        }

        .stat-label {
            font-size: 1rem;
            color: #6c757d;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .session-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 1.5rem;
        }

        .session-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            border: 1px solid #e9ecef;
            overflow: hidden;
        }

        .session-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 25px rgba(0,0,0,0.15);
        }

        .session-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 1.25rem;
        }

        .session-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .session-meta {
            font-size: 0.85rem;
            opacity: 0.9;
        }

        .session-status {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-activo {
            background: rgba(255, 255, 255, 0.3);
            color: white;
        }

        .status-pendiente {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .status-finalizado {
            background: rgba(255, 193, 7, 0.3);
            color: #856404;
        }

        .session-body {
            padding: 1.25rem;
        }

        .session-stats {
            display: flex;
            justify-content: space-around;
            margin-bottom: 1rem;
        }

        .stat-item {
            text-align: center;
        }

        .stat-value {
            font-size: 1.25rem;
            font-weight: 700;
            color: #495057;
        }

        .stat-name {
            font-size: 0.75rem;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .progress-bar {
            height: 6px;
            background: #e9ecef;
            border-radius: 3px;
            overflow: hidden;
            margin-bottom: 1rem;
        }

        .progress-fill {
            height: 100%;
            background: var(--success-color);
            transition: width 0.3s ease;
        }

        .session-actions {
            display: flex;
            gap: 0.5rem;
        }

        .btn-gradient {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-weight: 500;
            transition: var(--transition);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            font-size: 0.875rem;
        }

        .btn-gradient:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
            color: white;
        }

        .btn-outline-gradient {
            background: transparent;
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-weight: 500;
            transition: var(--transition);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            font-size: 0.875rem;
        }

        .btn-outline-gradient:hover {
            background: var(--primary-color);
            color: white;
        }

        .alert-modern {
            border-radius: var(--border-radius);
            padding: 1rem;
            border: 1px solid transparent;
        }

        .alert-modern.alert-success {
            background: rgba(40, 167, 69, 0.1);
            border-color: rgba(40, 167, 69, 0.2);
            color: #155724;
        }

        .alert-modern.alert-warning {
            background: rgba(255, 193, 7, 0.1);
            border-color: rgba(255, 193, 7, 0.2);
            color: #856404;
        }

        .alert-modern.alert-info {
            background: rgba(23, 162, 184, 0.1);
            border-color: rgba(23, 162, 184, 0.2);
            color: #0c5460;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
        }

        .empty-icon {
            font-size: 3rem;
            color: #6c757d;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .empty-title {
            font-size: 1.25rem;
            color: #495057;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .empty-text {
            color: #6c757d;
            margin-bottom: 1.5rem;
        }

        .date-selector {
            background: white;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: var(--shadow);
            margin-bottom: 1.5rem;
        }

        .date-input {
            border: 1px solid #ced4da;
            border-radius: 6px;
            padding: 0.5rem 0.75rem;
            font-size: 1rem;
        }

        .date-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.25);
            outline: none;
        }

        .date-display {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
            font-weight: 600;
            display: inline-block;
        }

        /* Breadcrumb styles removed - now using optimized component */

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .session-grid {
                grid-template-columns: 1fr;
            }

            .session-stats {
                flex-direction: column;
                gap: 0.5rem;
            }

            .session-actions {
                flex-direction: column;
            }

            .session-actions .btn {
                width: 100%;
                justify-content: center;
            }
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.2s ease;
        }

        .loading-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>

<div class="container-fluid" style="padding: 2rem;">
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="spinner"></div>
    </div>

    <!-- Breadcrumb -->
    <div class="breadcrumb-modern">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('rutarrr1') }}">
                        <i class="fas fa-home"></i>
                        <span>Inicio</span>
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <i class="fas fa-clipboard-check"></i>
                    <span>Panel de Asistencias</span>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Header -->
    <div class="glass-card mb-4">
        <div class="gradient-header">
            <div style="position: relative; z-index: 1;">
                <h1 class="mb-2">
                    <i class="fas fa-clipboard-check mr-3"></i>
                    Panel de Control de Asistencias
                </h1>
                <p class="mb-0 opacity-75">
                    Gestiona y monitorea la asistencia de tus estudiantes de manera eficiente
                </p>
            </div>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="stats-grid">
        <div class="stat-card stat-total">
            <div class="stat-icon">
                <i class="fas fa-list"></i>
            </div>
            <div class="stat-number">{{ number_format($estadisticas['total_registros']) }}</div>
            <div class="stat-label">Total Registros</div>
        </div>

        <div class="stat-card stat-presentes">
            <div class="stat-icon">
                <i class="fas fa-check"></i>
            </div>
            <div class="stat-number">{{ number_format($estadisticas['presentes']) }}</div>
            <div class="stat-label">Presentes</div>
        </div>

        <div class="stat-card stat-ausentes">
            <div class="stat-icon">
                <i class="fas fa-times"></i>
            </div>
            <div class="stat-number">{{ number_format($estadisticas['ausentes']) }}</div>
            <div class="stat-label">Ausentes</div>
        </div>

        <div class="stat-card stat-tardanzas">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-number">{{ number_format($estadisticas['tardanzas']) }}</div>
            <div class="stat-label">Tardanzas</div>
        </div>
    </div>

    <!-- Controles de Fecha -->
    <div class="date-selector">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-3">
                    <i class="fas fa-calendar-alt mr-2"></i>
                    Selección de Fecha
                </h5>
                <input type="date" id="fecha-selector" class="date-input form-control"
                       value="{{ $fecha }}" onchange="cambiarFecha(this.value)">
            </div>
            <div class="col-md-6">
                <h5 class="mb-3">
                    <i class="fas fa-calendar-day mr-2"></i>
                    Fecha Actual
                </h5>
                <div class="date-display">
                    <i class="fas fa-calendar mr-2"></i>
                    {{ Carbon\Carbon::parse($fecha)->isoFormat('dddd, D [de] MMMM, YYYY') }}
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12 text-center">
                <button class="btn-gradient mr-2" onclick="exportarExcel()">
                    <i class="fas fa-file-excel"></i>
                    Exportar Reporte
                </button>
                <a href="{{ route('asistencia.reporte-general') }}" class="btn-outline-gradient">
                    <i class="fas fa-chart-pie"></i>
                    Ver Reportes
                </a>
            </div>
        </div>
    </div>

    <!-- Sesiones Programadas -->
    <div class="glass-card">
        <div class="gradient-header">
            <h3 class="mb-0">
                <i class="fas fa-chalkboard-teacher mr-2"></i>
                Sesiones Programadas ({{ $sesiones->count() }})
            </h3>
        </div>

        <div class="card-body">
            @if ($sesiones->isEmpty())
                @php
                    $esFeriado = \App\Models\Feriado::esFeriado($fecha);
                    $feriadoInfo = $esFeriado ? \App\Models\Feriado::porFecha($fecha)->first() : null;
                @endphp

                @if($esFeriado)
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                        <h3 class="empty-title">Día Feriado</h3>
                        <p class="empty-text">
                            <strong>{{ $feriadoInfo->nombre }}</strong><br>
                            @if($feriadoInfo->descripcion)
                                {{ $feriadoInfo->descripcion }}
                            @endif
                        </p>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle mr-2"></i>
                            No hay sesiones programadas porque es feriado. Si hay clases de recuperación programadas, aparecerán en fechas posteriores.
                        </div>
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-calendar-times"></i>
                        </div>
                        <h3 class="empty-title">No tienes sesiones programadas</h3>
                        <p class="empty-text">
                            Revisa el horario académico o selecciona otra fecha para ver las clases disponibles.
                        </p>
                        <button class="btn-gradient" onclick="document.getElementById('fecha-selector').focus()">
                            <i class="fas fa-calendar-day mr-2"></i>
                            Cambiar Fecha
                        </button>
                    </div>
                @endif
            @else
                <div class="session-grid">
                    @foreach ($sesiones as $sesion)
                        <div class="session-card">
                            <div class="session-header">
                                <div class="session-title">{{ $sesion->cursoAsignatura->asignatura->nombre }}</div>
                                <div class="session-meta">
                                    <span>
                                        <i class="fas fa-graduation-cap mr-1"></i>
                                        {{ $sesion->cursoAsignatura->curso->grado->nombre }} {{ $sesion->cursoAsignatura->curso->seccion->nombre }}
                                    </span>
                                    <span>
                                        <i class="fas fa-clock mr-1"></i>
                                        {{ $sesion->hora_inicio }} - {{ $sesion->hora_fin }}
                                    </span>
                                </div>

                                @php
                                    $ahora = now();
                                    $inicio = Carbon\Carbon::parse($fecha . ' ' . $sesion->hora_inicio);
                                    $fin = Carbon\Carbon::parse($fecha . ' ' . $sesion->hora_fin);

                                    $estadoClase = 'pendiente';
                                    if ($ahora->between($inicio, $fin)) {
                                        $estadoClase = 'activo';
                                    } elseif ($ahora->gt($fin)) {
                                        $estadoClase = 'finalizado';
                                    }
                                @endphp

                                <div class="session-status status-{{ $estadoClase }}">
                                    <i class="fas fa-circle mr-1"></i>
                                    {{ ucfirst($estadoClase) }}
                                </div>
                            </div>

                            <div class="session-body">
                                @php
                                    $asistenciasRegistradas = \App\Models\AsistenciaAsignatura::where('curso_asignatura_id', $sesion->curso_asignatura_id)
                                        ->where('fecha', $fecha)->count();
                                    $totalEstudiantes = \App\Models\Matricula::where('curso_id', $sesion->cursoAsignatura->curso_id)
                                        ->where('estado', 'Matriculado')->count();
                                    $porcentajeRegistro = $totalEstudiantes > 0 ? round(($asistenciasRegistradas / $totalEstudiantes) * 100) : 0;
                                @endphp

                                <div class="session-stats">
                                    <div class="stat-item">
                                        <div class="stat-value">{{ $totalEstudiantes }}</div>
                                        <div class="stat-name">Estudiantes</div>
                                    </div>
                                    <div class="stat-item">
                                        <div class="stat-value">{{ $asistenciasRegistradas }}</div>
                                        <div class="stat-name">Registrados</div>
                                    </div>
                                    <div class="stat-item">
                                        <div class="stat-value">{{ $porcentajeRegistro }}%</div>
                                        <div class="stat-name">Completado</div>
                                    </div>
                                </div>

                                <div class="progress-container">
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: {{ $porcentajeRegistro }}%"></div>
                                    </div>
                                </div>

                                <div class="session-actions">
                                    <a href="{{ route('asistencia.registrar-asignatura', [$sesion->curso_asignatura_id, $fecha]) }}"
                                        class="btn-gradient">
                                        <i class="fas fa-edit"></i>
                                        {{ $asistenciasRegistradas > 0 ? 'Editar Asistencia' : 'Registrar Asistencia' }}
                                    </a>

                                    @if ($asistenciasRegistradas > 0)
                                        <a href="{{ route('asistencia.reporte-curso', $sesion->curso_asignatura_id) }}"
                                            class="btn-outline-gradient">
                                            <i class="fas fa-chart-bar"></i>
                                            Reporte
                                        </a>
                                    @endif
                                </div>

                                @if ($estadoClase === 'activo')
                                    <div class="alert alert-success alert-modern mt-3">
                                        <i class="fas fa-play-circle mr-2"></i>
                                        <strong>¡Clase en progreso!</strong> Registra la asistencia ahora.
                                    </div>
                                @elseif($estadoClase === 'finalizado' && $asistenciasRegistradas == 0)
                                    <div class="alert alert-warning alert-modern mt-3">
                                        <i class="fas fa-exclamation-triangle mr-2"></i>
                                        <strong>Asistencia pendiente</strong> - Clase ya finalizó.
                                    </div>
                                @elseif($estadoClase === 'finalizado' && $asistenciasRegistradas > 0)
                                    <div class="alert alert-info alert-modern mt-3">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        Asistencia registrada correctamente.
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function cambiarFecha(fecha) {
    const overlay = document.getElementById('loadingOverlay');
    overlay.classList.add('show');

    setTimeout(() => {
        window.location.href = '{{ route('asistencia.index') }}?fecha=' + fecha;
    }, 300);
}

function exportarExcel() {
    const fecha = document.getElementById('fecha-selector').value;
    window.open(`/asistencia/exportar?fecha=${fecha}`, '_blank');
}

// Hide loading overlay on page load
window.addEventListener('load', function() {
    const overlay = document.getElementById('loadingOverlay');
    overlay.classList.remove('show');
});
</script>

@endsection
