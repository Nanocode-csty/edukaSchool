@extends('cplantilla.bprincipal')

@section('titulo', 'Mis Estudiantes - Panel Representante')

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

.student-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(380px, 1fr));
    gap: 2rem;
    margin: 2rem 0;
}

.student-card {
    background: white;
    border-radius: var(--border-radius);
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    transition: var(--transition);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.student-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
}

.student-header {
    background: var(--primary-gradient);
    color: white;
    padding: 1.5rem;
    position: relative;
}

.student-name {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.student-info {
    font-size: 0.9rem;
    opacity: 0.9;
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.student-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    font-weight: 600;
    color: white;
    margin: 0 auto 1rem;
    border: 3px solid rgba(255, 255, 255, 0.3);
}

.student-body {
    padding: 1.5rem;
}

.academic-info {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 1rem;
    margin-bottom: 1.5rem;
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.academic-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.academic-item:last-child {
    border-bottom: none;
}

.academic-label {
    font-weight: 500;
    color: #495057;
}

.academic-value {
    font-weight: 600;
    color: #667eea;
}

.stats-overview {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.stat-box {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    padding: 1rem;
    border-radius: 12px;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.stat-box::before {
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
    animation: rotate 10s linear infinite;
}

.stat-box-content {
    position: relative;
    z-index: 1;
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
}

.stat-label {
    font-size: 0.8rem;
    opacity: 0.9;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.student-actions {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.btn-modern {
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-weight: 500;
    font-size: 0.875rem;
    transition: var(--transition);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    border: none;
    cursor: pointer;
}

.btn-primary-modern {
    background: var(--primary-gradient);
    color: white;
}

.btn-primary-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    color: white;
}

.btn-success-modern {
    background: var(--success-gradient);
    color: white;
}

.btn-success-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(79, 172, 254, 0.4);
    color: white;
}

.btn-warning-modern {
    background: var(--warning-gradient);
    color: white;
}

.btn-warning-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(240, 147, 251, 0.4);
    color: white;
}

.btn-outline-modern {
    background: transparent;
    border: 2px solid #667eea;
    color: #667eea;
}

.btn-outline-modern:hover {
    background: var(--primary-gradient);
    color: white;
    transform: translateY(-2px);
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.375rem 0.75rem;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-activo {
    background: rgba(40, 167, 69, 0.1);
    color: #28a745;
    border: 1px solid rgba(40, 167, 69, 0.2);
}

.status-inactivo {
    background: rgba(108, 117, 125, 0.1);
    color: #6c757d;
    border: 1px solid rgba(108, 117, 125, 0.2);
}

.tab-container {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    margin-bottom: 2rem;
}

.tab-header {
    background: var(--primary-gradient);
    padding: 0;
    display: flex;
}

.tab-button {
    flex: 1;
    background: transparent;
    border: none;
    color: rgba(255, 255, 255, 0.7);
    padding: 1rem;
    font-weight: 500;
    transition: var(--transition);
    position: relative;
}

.tab-button.active {
    color: white;
    background: rgba(255, 255, 255, 0.1);
}

.tab-button.active::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 30px;
    height: 3px;
    background: white;
    border-radius: 2px;
}

.tab-content {
    padding: 2rem;
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
    .student-grid {
        grid-template-columns: 1fr;
    }

    .stats-overview {
        grid-template-columns: 1fr;
    }

    .student-actions {
        flex-direction: column;
    }

    .student-actions .btn-modern {
        width: 100%;
        justify-content: center;
    }

    .tab-header {
        flex-direction: column;
    }
}

@keyframes rotate {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

<div class="container-fluid" style="padding: 2rem;">
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
                    <i class="fas fa-users"></i>
                    <span>Mis Estudiantes Representados</span>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Header -->
    <div class="glass-card mb-4">
        <div class="gradient-header">
            <div style="position: relative; z-index: 1;">
                <h1 class="mb-2">
                    <i class="fas fa-user-friends mr-3"></i>
                    Panel de Representante
                </h1>
                <p class="mb-0 opacity-75">
                    Gestiona las asistencias y calificaciones de tus estudiantes representados
                </p>
            </div>
        </div>
    </div>

    @if ($estudiantesRepresentados->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-user-slash"></i>
            </div>
            <h3 class="empty-title">No tienes estudiantes asignados</h3>
            <p class="empty-text">
                Actualmente no tienes estudiantes asignados como representante.
                Si crees que esto es un error, contacta a la administración.
            </p>
        </div>
    @else
        <!-- Estadísticas Generales -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stat-box">
                    <div class="stat-box-content">
                        <div class="stat-number">{{ $estudiantesRepresentados->count() }}</div>
                        <div class="stat-label">Estudiantes</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-box">
                    <div class="stat-box-content">
                        <div class="stat-number">{{ $estudiantesRepresentados->where('matricula.estado', 'Matriculado')->count() }}</div>
                        <div class="stat-label">Matriculados</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-box">
                    <div class="stat-box-content">
                        <div class="stat-number">{{ $estudiantesRepresentados->where('es_principal', true)->count() }}</div>
                        <div class="stat-label">Principales</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-box">
                    <div class="stat-box-content">
                        <div class="stat-number">{{ $estudiantesRepresentados->where('es_principal', false)->count() }}</div>
                        <div class="stat-label">Secundarios</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs para Asistencias y Notas -->
        <div class="tab-container">
            <div class="tab-header">
                <button class="tab-button active" onclick="showTab('asistencias')">
                    <i class="fas fa-clipboard-check mr-2"></i>
                    Asistencias
                </button>
                <button class="tab-button" onclick="showTab('notas')">
                    <i class="fas fa-chart-line mr-2"></i>
                    Calificaciones
                </button>
                <button class="tab-button" onclick="showTab('justificaciones')">
                    <i class="fas fa-file-alt mr-2"></i>
                    Justificaciones
                </button>
            </div>

            <!-- Contenido de Asistencias -->
            <div id="asistencias-tab" class="tab-content">
                <div class="student-grid">
                    @foreach ($estudiantesRepresentados as $item)
                        <div class="student-card">
                            <div class="student-header">
                                <div class="student-avatar">
                                    {{ substr($item['estudiante']->nombres, 0, 1) }}{{ substr($item['estudiante']->apellidos, 0, 1) }}
                                </div>
                                <div class="student-name">
                                    @if ($item['es_principal'])
                                        <i class="fas fa-star text-warning mr-1"></i>
                                    @endif
                                    {{ $item['estudiante']->apellidos }}, {{ $item['estudiante']->nombres }}
                                </div>
                                <div class="student-info">
                                    <span><i class="fas fa-id-card mr-1"></i>{{ $item['estudiante']->dni }}</span>
                                    <span><i class="fas fa-envelope mr-1"></i>{{ $item['estudiante']->email }}</span>
                                </div>
                            </div>

                            <div class="student-body">
                                @if ($item['matricula'] && $item['curso'])
                                    <div class="academic-info">
                                        <div class="academic-item">
                                            <span class="academic-label">Grado:</span>
                                            <span class="academic-value">{{ $item['curso']->grado->nombre }} {{ $item['curso']->seccion->nombre }}</span>
                                        </div>
                                        <div class="academic-item">
                                            <span class="academic-label">Año:</span>
                                            <span class="academic-value">{{ $item['curso']->anoLectivo->nombre }}</span>
                                        </div>
                                        <div class="academic-item">
                                            <span class="academic-label">Matrícula:</span>
                                            <span class="academic-value">{{ $item['matricula']->numero_matricula }}</span>
                                        </div>
                                        <div class="academic-item">
                                            <span class="academic-label">Estado:</span>
                                            <span class="status-badge status-{{ strtolower($item['matricula']->estado) }}">
                                                {{ $item['matricula']->estado }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="stats-overview">
                                        @php
                                            // Calcular estadísticas de asistencia del último mes
                                            $fechaInicio = now()->subDays(30);
                                            $asistencias = \App\Models\AsistenciaAsignatura::whereHas('matricula', function($q) use ($item) {
                                                $q->where('estudiante_id', $item['estudiante']->estudiante_id);
                                            })
                                            ->where('fecha', '>=', $fechaInicio)
                                            ->selectRaw('estado, COUNT(*) as total')
                                            ->groupBy('estado')
                                            ->pluck('total', 'estado')
                                            ->toArray();

                                            $totalAsistencias = array_sum($asistencias);
                                            $presentes = $asistencias['P'] ?? 0;
                                            $ausentes = $asistencias['A'] ?? 0;
                                            $tardanzas = $asistencias['T'] ?? 0;
                                            $porcentajeAsistencia = $totalAsistencias > 0 ? round(($presentes / $totalAsistencias) * 100) : 0;
                                        @endphp

                                        <div class="stat-box">
                                            <div class="stat-box-content">
                                                <div class="stat-number">{{ $porcentajeAsistencia }}%</div>
                                                <div class="stat-label">Asistencia</div>
                                            </div>
                                        </div>

                                        <div class="stat-box">
                                            <div class="stat-box-content">
                                                <div class="stat-number">{{ $ausentes }}</div>
                                                <div class="stat-label">Faltas</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="student-actions">
                                        <a href="{{ route('asistencia.detalle-estudiante', $item['matricula']->matricula_id) }}"
                                            class="btn-modern btn-primary-modern">
                                            <i class="fas fa-eye"></i>
                                            Ver Asistencia
                                        </a>
                                        <a href="{{ route('asistencia.exportar-pdf', $item['matricula']->matricula_id) }}"
                                            class="btn-modern btn-success-modern">
                                            <i class="fas fa-file-pdf"></i>
                                            PDF
                                        </a>
                                        <a href="{{ route('asistencia.justificar') }}?estudiante_id={{ $item['estudiante']->estudiante_id }}"
                                            class="btn-modern btn-warning-modern">
                                            <i class="fas fa-plus"></i>
                                            Justificar
                                        </a>
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <i class="fas fa-exclamation-triangle text-warning fa-2x mb-2"></i>
                                        <p class="text-muted mb-0">Estudiante sin matrícula activa</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Contenido de Notas -->
            <div id="notas-tab" class="tab-content" style="display: none;">
                <div class="student-grid">
                    @foreach ($estudiantesRepresentados as $item)
                        <div class="student-card">
                            <div class="student-header">
                                <div class="student-avatar">
                                    {{ substr($item['estudiante']->nombres, 0, 1) }}{{ substr($item['estudiante']->apellidos, 0, 1) }}
                                </div>
                                <div class="student-name">
                                    @if ($item['es_principal'])
                                        <i class="fas fa-star text-warning mr-1"></i>
                                    @endif
                                    {{ $item['estudiante']->apellidos }}, {{ $item['estudiante']->nombres }}
                                </div>
                                <div class="student-info">
                                    <span><i class="fas fa-id-card mr-1"></i>{{ $item['estudiante']->dni }}</span>
                                </div>
                            </div>

                            <div class="student-body">
                                @if ($item['matricula'] && $item['curso'])
                                    <div class="academic-info">
                                        <div class="academic-item">
                                            <span class="academic-label">Grado:</span>
                                            <span class="academic-value">{{ $item['curso']->grado->nombre }} {{ $item['curso']->seccion->nombre }}</span>
                                        </div>
                                        <div class="academic-item">
                                            <span class="academic-label">Año:</span>
                                            <span class="academic-value">{{ $item['curso']->anoLectivo->nombre }}</span>
                                        </div>
                                    </div>

                                    <div class="stats-overview">
                                        @php
                                            // Calcular promedio general (simulado)
                                            $promedioGeneral = rand(85, 98);
                                            $materiasAprobadas = rand(5, 8);
                                            $materiasTotal = 8;
                                        @endphp

                                        <div class="stat-box">
                                            <div class="stat-box-content">
                                                <div class="stat-number">{{ $promedioGeneral }}%</div>
                                                <div class="stat-label">Promedio</div>
                                            </div>
                                        </div>

                                        <div class="stat-box">
                                            <div class="stat-box-content">
                                                <div class="stat-number">{{ $materiasAprobadas }}/{{ $materiasTotal }}</div>
                                                <div class="stat-label">Aprobadas</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="student-actions">
                                        <a href="{{ route('notas.estudiante', $item['estudiante']->estudiante_id) }}"
                                            class="btn-modern btn-primary-modern">
                                            <i class="fas fa-chart-bar"></i>
                                            Ver Calificaciones
                                        </a>
                                        <a href="{{ route('estudiantes.ficha', $item['estudiante']->estudiante_id) }}"
                                            class="btn-modern btn-success-modern">
                                            <i class="fas fa-file-pdf"></i>
                                            Ficha Escolar
                                        </a>
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <i class="fas fa-exclamation-triangle text-warning fa-2x mb-2"></i>
                                        <p class="text-muted mb-0">Estudiante sin matrícula activa</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Contenido de Justificaciones -->
            <div id="justificaciones-tab" class="tab-content" style="display: none;">
                <div class="text-center py-5">
                    <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">Justificaciones de Asistencia</h4>
                    <p class="text-muted mb-4">Aquí podrás gestionar las justificaciones de inasistencia de tus estudiantes representados.</p>
                    <a href="{{ route('asistencia.justificar') }}" class="btn-modern btn-warning-modern">
                        <i class="fas fa-plus mr-2"></i>
                        Nueva Justificación
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
function showTab(tabName) {
    // Hide all tab contents
    document.getElementById('asistencias-tab').style.display = 'none';
    document.getElementById('notas-tab').style.display = 'none';
    document.getElementById('justificaciones-tab').style.display = 'none';

    // Remove active class from all buttons
    document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));

    // Show selected tab and activate button
    document.getElementById(tabName + '-tab').style.display = 'block';
