@extends('cplantilla.bprincipal')

@section('titulo', 'Panel de Asistencias - Eduka')

@section('contenidoplantilla')
<style>
:root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    --warning-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    --danger-gradient: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
    --info-gradient: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
    --glass-bg: rgba(255, 255, 255, 0.25);
    --glass-border: rgba(255, 255, 255, 0.18);
    --shadow-light: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
    --shadow-dark: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
    --border-radius: 16px;
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

* {
    box-sizing: border-box;
}

body {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    min-height: 100vh;
}

.glass-card {
    background: var(--glass-bg);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid var(--glass-border);
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-light);
    transition: var(--transition);
}

.glass-card:hover {
    transform: translateY(-8px);
    box-shadow: var(--shadow-dark);
}

.gradient-header {
    background: var(--primary-gradient);
    color: white;
    border-radius: var(--border-radius) var(--border-radius) 0 0;
    padding: 2rem;
    position: relative;
    overflow: hidden;
}

.gradient-header::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: repeating-conic-gradient(
        from 0deg at 50% 50%,
        rgba(255, 255, 255, 0.1) 0deg,
        transparent 60deg
    );
    animation: rotate 20s linear infinite;
}

@keyframes rotate {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 2rem;
    margin: 2rem 0;
}

.stat-card {
    background: white;
    border-radius: var(--border-radius);
    padding: 2rem;
    text-align: center;
    position: relative;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    transition: var(--transition);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--primary-gradient);
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
}

.stat-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 2rem;
    color: white;
    position: relative;
    z-index: 1;
}

.stat-presentes .stat-icon { background: var(--success-gradient); }
.stat-ausentes .stat-icon { background: var(--danger-gradient); }
.stat-tardanzas .stat-icon { background: var(--warning-gradient); }
.stat-total .stat-icon { background: var(--info-gradient); }

.stat-number {
    font-size: 3rem;
    font-weight: 700;
    margin: 0.5rem 0;
    background: linear-gradient(135deg, #667eea, #764ba2);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.stat-label {
    font-size: 1.1rem;
    color: #6c757d;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.session-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 2rem;
    margin: 2rem 0;
}

.session-card {
    background: white;
    border-radius: var(--border-radius);
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    transition: var(--transition);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.session-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
}

.session-header {
    background: var(--primary-gradient);
    color: white;
    padding: 1.5rem;
    position: relative;
}

.session-title {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.session-meta {
    font-size: 0.9rem;
    opacity: 0.9;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.session-status {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-activo {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    animation: pulse 2s infinite;
}

.status-pendiente {
    background: rgba(255, 255, 255, 0.2);
    color: white;
}

.status-finalizado {
    background: rgba(255, 193, 7, 0.2);
    color: #ffc107;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

.session-body {
    padding: 1.5rem;
}

.session-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.stat-item {
    text-align: center;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 12px;
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #495057;
    margin-bottom: 0.25rem;
}

.stat-name {
    font-size: 0.8rem;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 500;
}

.progress-container {
    margin-bottom: 1.5rem;
}

.progress-bar {
    height: 8px;
    background: #e9ecef;
    border-radius: 4px;
    overflow: hidden;
    position: relative;
}

.progress-fill {
    height: 100%;
    background: var(--success-gradient);
    border-radius: 4px;
    transition: width 0.8s ease;
    position: relative;
}

.progress-fill::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    animation: shimmer 2s infinite;
}

@keyframes shimmer {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

.session-actions {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.btn-gradient {
    background: var(--primary-gradient);
    border: none;
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 500;
    transition: var(--transition);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-gradient:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    color: white;
    text-decoration: none;
}

.btn-outline-gradient {
    background: transparent;
    border: 2px solid;
    border-image: var(--primary-gradient) 1;
    color: #667eea;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 500;
    transition: var(--transition);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-outline-gradient:hover {
    background: var(--primary-gradient);
    color: white;
    transform: translateY(-2px);
    text-decoration: none;
}

.alert-modern {
    border: none;
    border-radius: var(--border-radius);
    padding: 1.5rem;
    position: relative;
    overflow: hidden;
}

.alert-modern::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: var(--primary-gradient);
}

.alert-modern.alert-success {
    background: linear-gradient(135deg, rgba(40, 167, 69, 0.1), rgba(34, 197, 94, 0.1));
    border-left: 4px solid #28a745;
}

.alert-modern.alert-warning {
    background: linear-gradient(135deg, rgba(255, 193, 7, 0.1), rgba(255, 193, 7, 0.1));
    border-left: 4px solid #ffc107;
}

.alert-modern.alert-info {
    background: linear-gradient(135deg, rgba(23, 162, 184, 0.1), rgba(23, 162, 184, 0.1));
    border-left: 4px solid #17a2b8;
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: var(--border-radius);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.empty-icon {
    font-size: 4rem;
    color: #6c757d;
    margin-bottom: 1.5rem;
    opacity: 0.5;
}

.empty-title {
    font-size: 1.5rem;
    color: #495057;
    margin-bottom: 1rem;
    font-weight: 600;
}

.empty-text {
    color: #6c757d;
    margin-bottom: 2rem;
    font-size: 1.1rem;
}

.date-selector {
    background: white;
    border-radius: var(--border-radius);
    padding: 2rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    margin-bottom: 2rem;
}

.date-input {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 0.75rem 1rem;
    font-size: 1rem;
    transition: var(--transition);
}

.date-input:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    outline: none;
}

.date-display {
    background: var(--primary-gradient);
    color: white;
    padding: 0.75rem 1rem;
    border-radius: 8px;
    font-weight: 600;
    display: inline-block;
}

.breadcrumb-modern {
    background: white;
    border-radius: var(--border-radius);
    padding: 1.5rem 2rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    margin-bottom: 2rem;
}

.breadcrumb {
    background: transparent;
    margin: 0;
    padding: 0;
}

.breadcrumb-item {
    display: flex;
    align-items: center;
    font-size: 0.95rem;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: "→";
    color: #667eea;
    font-weight: bold;
    margin: 0 0.5rem;
}

.breadcrumb-item a {
    color: #667eea;
    text-decoration: none;
    font-weight: 500;
    transition: var(--transition);
}

.breadcrumb-item a:hover {
    color: #764ba2;
    text-decoration: underline;
}

.breadcrumb-item.active {
    color: #495057;
    font-weight: 600;
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }

    .session-grid {
        grid-template-columns: 1fr;
    }

    .session-stats {
        grid-template-columns: 1fr;
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
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(4px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    opacity: 0;
    visibility: hidden;
    transition: var(--transition);
}

.loading-overlay.show {
    opacity: 1;
    visibility: visible;
}

.spinner {
    width: 60px;
    height: 60px;
    border: 4px solid #f3f3f3;
    border-top: 4px solid #667eea;
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
