@extends('cplantilla.bprincipal')

@section('titulo', 'Registrar Asistencia - Eduka')

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

.attendance-container {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    padding: 2.5rem;
    margin: 2rem 0;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.session-header {
    text-align: center;
    margin-bottom: 2.5rem;
    padding-bottom: 2rem;
    border-bottom: 2px solid #f0f0f0;
}

.session-title {
    font-size: 2rem;
    font-weight: 700;
    color: #495057;
    margin-bottom: 0.5rem;
}

.session-subtitle {
    color: #6c757d;
    font-size: 1.1rem;
}

.session-info {
    background: var(--primary-gradient);
    color: white;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
}

.info-item {
    text-align: center;
}

.info-label {
    font-size: 0.9rem;
    opacity: 0.8;
    margin-bottom: 0.5rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-value {
    font-size: 1.5rem;
    font-weight: 700;
}

.students-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.student-card {
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    padding: 1.5rem;
    transition: var(--transition);
    position: relative;
    overflow: hidden;
}

.student-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: var(--info-gradient);
}

.student-card.presente {
    border-color: #28a745;
    background: linear-gradient(135deg, rgba(40, 167, 69, 0.05), rgba(34, 197, 94, 0.05));
}

.student-card.presente::before {
    background: var(--success-gradient);
}

.student-card.ausente {
    border-color: #dc3545;
    background: linear-gradient(135deg, rgba(220, 53, 69, 0.05), rgba(220, 53, 69, 0.05));
}

.student-card.ausente::before {
    background: var(--danger-gradient);
}

.student-card.tardanza {
    border-color: #ffc107;
    background: linear-gradient(135deg, rgba(255, 193, 7, 0.05), rgba(255, 193, 7, 0.05));
}

.student-card.tardanza::before {
    background: var(--warning-gradient);
}

.student-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: var(--info-gradient);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    font-weight: 600;
    color: #495057;
    margin: 0 auto 1rem;
}

.student-name {
    font-size: 1.1rem;
    font-weight: 600;
    color: #495057;
    text-align: center;
    margin-bottom: 1rem;
}

.attendance-controls {
    display: flex;
    gap: 0.5rem;
    justify-content: center;
}

.attendance-btn {
    padding: 0.5rem 1rem;
    border: 2px solid #e9ecef;
    background: white;
    border-radius: 8px;
    font-weight: 500;
    font-size: 0.875rem;
    transition: var(--transition);
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.375rem;
}

.attendance-btn:hover {
    transform: translateY(-2px);
}

.attendance-btn.active {
    border-color: #667eea;
    background: var(--primary-gradient);
    color: white;
}

.attendance-btn.presente.active {
    border-color: #28a745;
    background: var(--success-gradient);
    color: white;
}

.attendance-btn.ausente.active {
    border-color: #dc3545;
    background: var(--danger-gradient);
    color: white;
}

.attendance-btn.tardanza.active {
    border-color: #ffc107;
    background: var(--warning-gradient);
    color: white;
}

.status-indicator {
    position: absolute;
    top: 10px;
    right: 10px;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    font-weight: bold;
    color: white;
}

.status-indicator.presente {
    background: #28a745;
}

.status-indicator.ausente {
    background: #dc3545;
}

.status-indicator.tardanza {
    background: #ffc107;
}

.summary-card {
    background: var(--primary-gradient);
    color: white;
    border-radius: 12px;
    padding: 2rem;
    margin-bottom: 2rem;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1.5rem;
}

.summary-item {
    text-align: center;
}

.summary-number {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.summary-label {
    font-size: 0.9rem;
    opacity: 0.9;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.btn-modern {
    padding: 0.875rem 2rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: 1rem;
    transition: var(--transition);
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
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

.btn-secondary-modern {
    background: #6c757d;
    color: white;
}

.btn-secondary-modern:hover {
    background: #5a6268;
    transform: translateY(-2px);
    color: white;
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
    .students-grid {
        grid-template-columns: 1fr;
    }

    .session-info {
        grid-template-columns: 1fr;
    }

    .summary-card {
        grid-template-columns: 1fr;
    }

    .attendance-container {
        padding: 1.5rem;
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
                <li class="breadcrumb-item">
                    <a href="{{ route('asistencia.index') }}">
                        <i class="fas fa-clipboard-check"></i>
                        <span>Asistencias</span>
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <i class="fas fa-edit"></i>
                    <span>Registrar Asistencia</span>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Header -->
    <div class="attendance-container">
        <div class="session-header">
            <h1 class="session-title">
                <i class="fas fa-edit mr-3 text-primary"></i>
                Registrar Asistencia
            </h1>
            <p class="session-subtitle">
                Registra la asistencia de tus estudiantes para la sesión seleccionada
            </p>
        </div>

        <!-- Información de la Sesión -->
        <div class="session-info">
            <div class="info-item">
                <div class="info-label">Asignatura</div>
                <div class="info-value">{{ $sesion->cursoAsignatura->asignatura->nombre }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Curso</div>
                <div class="info-value">{{ $sesion->cursoAsignatura->curso->grado->nombre }} {{ $sesion->cursoAsignatura->curso->seccion->nombre }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Fecha</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($fecha)->isoFormat('DD/MM/YYYY') }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Horario</div>
                <div class="info-value">{{ $sesion->hora_inicio }} - {{ $sesion->hora_fin }}</div>
            </div>
        </div>

        <!-- Resumen de Asistencia -->
        <div class="summary-card">
            <div class="summary-item">
                <div class="summary-number" id="totalEstudiantes">{{ $estudiantes->count() }}</div>
                <div class="summary-label">Total Estudiantes</div>
            </div>
            <div class="summary-item">
                <div class="summary-number" id="presentesCount">0</div>
                <div class="summary-label">Presentes</div>
            </div>
            <div class="summary-item">
                <div class="summary-number" id="ausentesCount">0</div>
                <div class="summary-label">Ausentes</div>
            </div>
            <div class="summary-item">
                <div class="summary-number" id="tardanzasCount">0</div>
                <div class="summary-label">Tardanzas</div>
            </div>
        </div>

        <form id="attendanceForm" action="{{ route('asistencia.store-asignatura', [$sesion->curso_asignatura_id, $fecha]) }}" method="POST">
            @csrf

            <!-- Lista de Estudiantes -->
            <div class="students-grid">
                @foreach($estudiantes as $estudiante)
                    @php
                        $asistenciaExistente = $asistencias->where('estudiante_id', $estudiante->estudiante_id)->first();
                        $estadoActual = $asistenciaExistente ? $asistenciaExistente->estado : '';
                    @endphp

                    <div class="student-card {{ $estadoActual }}" data-estudiante-id="{{ $estudiante->estudiante_id }}">
                        <div class="student-avatar">
                            {{ substr($estudiante->nombres, 0, 1) }}{{ substr($estudiante->apellidos, 0, 1) }}
                        </div>

                        <div class="student-name">
                            {{ $estudiante->apellidos }}, {{ $estudiante->nombres }}
                        </div>

                        @if($estadoActual)
                            <div class="status-indicator {{ $estadoActual }}">
                                @if($estadoActual == 'P')
                                    <i class="fas fa-check"></i>
                                @elseif($estadoActual == 'A')
                                    <i class="fas fa-times"></i>
                                @elseif($estadoActual == 'T')
                                    <i class="fas fa-clock"></i>
                                @endif
                            </div>
                        @endif

                        <div class="attendance-controls">
                            <button type="button" class="attendance-btn presente {{ $estadoActual == 'P' ? 'active' : '' }}"
                                    onclick="setAttendance({{ $estudiante->estudiante_id }}, 'P', this)">
                                <i class="fas fa-check"></i>
                                Presente
                            </button>
                            <button type="button" class="attendance-btn ausente {{ $estadoActual == 'A' ? 'active' : '' }}"
                                    onclick="setAttendance({{ $estudiante->estudiante_id }}, 'A', this)">
                                <i class="fas fa-times"></i>
                                Ausente
                            </button>
                            <button type="button" class="attendance-btn tardanza {{ $estadoActual == 'T' ? 'active' : '' }}"
                                    onclick="setAttendance({{ $estudiante->estudiante_id }}, 'T', this)">
                                <i class="fas fa-clock"></i>
                                Tardanza
                            </button>
                        </div>

                        <input type="hidden" name="asistencia[{{ $estudiante->estudiante_id }}]" id="asistencia_{{ $estudiante->estudiante_id }}"
                               value="{{ $estadoActual }}" required>
                    </div>
                @endforeach
            </div>

            <!-- Botones de Acción -->
            <div class="text-center">
                <button type="submit" class="btn-modern btn-primary-modern mr-3" id="submitBtn">
                    <i class="fas fa-save"></i>
                    Guardar Asistencia
                </button>
                <a href="{{ route('asistencia.index') }}" class="btn-modern btn-secondary-modern">
                    <i class="fas fa-arrow-left"></i>
                    Volver
                </a>
            </div>
        </form>
    </div>
</div>

<script>
let attendanceData = {};

function setAttendance(estudianteId, estado, button) {
    // Remove active class from all buttons in this student's card
    const card = button.closest('.student-card');
    card.querySelectorAll('.attendance-btn').forEach(btn => btn.classList.remove('active'));
    card.classList.remove('presente', 'ausente', 'tardanza');

    // Add active class to clicked button
    button.classList.add('active');

    // Update card status
    card.classList.add(estado.toLowerCase());

    // Update hidden input
    document.getElementById('asistencia_' + estudianteId).value = estado;

    // Update attendance data
    attendanceData[estudianteId] = estado;

    // Update summary
    updateSummary();
}

function updateSummary() {
    const total = {{ $estudiantes->count() }};
    let presentes = 0, ausentes = 0, tardanzas = 0;

    Object.values(attendanceData).forEach(estado => {
        if (estado === 'P') presentes++;
        else if (estado === 'A') ausentes++;
        else if (estado === 'T') tardanzas++;
    });

    // Add existing attendance data
    @foreach($asistencias as $asistencia)
        @if(!isset(attendanceData[{{ $asistencia->estudiante_id }}]))
            @if($asistencia->estado === 'P') presentes++; @endif
            @if($asistencia->estado === 'A') ausentes++; @endif
            @if($asistencia->estado === 'T') tardanzas++; @endif
        @endif
    @endforeach

    document.getElementById('presentesCount').textContent = presentes;
    document.getElementById('ausentesCount').textContent = ausentes;
    document.getElementById('tardanzasCount').textContent = tardanzas;
}

// Initialize with existing data
document.addEventListener('DOMContentLoaded', function() {
    // Load existing attendance data
    @foreach($asistencias as $asistencia)
        attendanceData[{{ $asistencia->estudiante_id }}] = '{{ $asistencia->estado }}';
    @endforeach

    updateSummary();

    // Form submission
    document.getElementById('attendanceForm').addEventListener('submit', function(e) {
        const totalRecords = Object.keys(attendanceData).length;
        const totalEstudiantes = {{ $estudiantes->count() }};

        if (totalRecords === 0) {
            e.preventDefault();
            alert('Debes registrar la asistencia de al menos un estudiante.');
            return false;
        }

        // Show loading overlay
        document.getElementById('loadingOverlay').classList.add('show');

        // Disable submit button
        document.getElementById('submitBtn').disabled = true;
        document.getElementById('submitBtn').innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
    });
});
</script>

@endsection
