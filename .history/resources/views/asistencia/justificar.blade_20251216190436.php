@extends('cplantilla.bprincipal')

@section('titulo', 'Justificar Asistencia - Eduka')

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

.form-container {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    padding: 2.5rem;
    margin: 2rem 0;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.form-header {
    text-align: center;
    margin-bottom: 2.5rem;
    padding-bottom: 2rem;
    border-bottom: 2px solid #f0f0f0;
}

.form-title {
    font-size: 2rem;
    font-weight: 700;
    color: #495057;
    margin-bottom: 0.5rem;
}

.form-subtitle {
    color: #6c757d;
    font-size: 1.1rem;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin-bottom: 2rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    display: block;
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
    font-size: 0.95rem;
}

.form-control-modern {
    width: 100%;
    padding: 0.875rem 1rem;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    font-size: 1rem;
    transition: var(--transition);
    background: white;
}

.form-control-modern:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    outline: none;
}

.form-control-modern.is-invalid {
    border-color: #dc3545;
    box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
}

.form-text {
    font-size: 0.875rem;
    color: #6c757d;
    margin-top: 0.25rem;
}

.textarea-modern {
    min-height: 120px;
    resize: vertical;
    font-family: inherit;
}

.select-modern {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 1rem center;
    background-size: 16px 12px;
    padding-right: 3rem;
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

.btn-secondary-modern {
    background: #6c757d;
    color: white;
}

.btn-secondary-modern:hover {
    background: #5a6268;
    transform: translateY(-2px);
    color: white;
}

.alert-modern {
    border: none;
    border-radius: var(--border-radius);
    padding: 1.5rem;
    position: relative;
    overflow: hidden;
    margin-bottom: 2rem;
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

.alert-modern.alert-info {
    background: linear-gradient(135deg, rgba(23, 162, 184, 0.1), rgba(23, 162, 184, 0.1));
    border-left: 4px solid #17a2b8;
}

.alert-modern.alert-warning {
    background: linear-gradient(135deg, rgba(255, 193, 7, 0.1), rgba(255, 193, 7, 0.1));
    border-left: 4px solid #ffc107;
}

.alert-modern.alert-danger {
    background: linear-gradient(135deg, rgba(220, 53, 69, 0.1), rgba(220, 53, 69, 0.1));
    border-left: 4px solid #dc3545;
}

.student-selector {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.student-card {
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    padding: 1rem;
    margin-bottom: 0.5rem;
    cursor: pointer;
    transition: var(--transition);
    position: relative;
}

.student-card:hover {
    border-color: #667eea;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
}

.student-card.selected {
    border-color: #667eea;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.05), rgba(118, 75, 162, 0.05));
}

.student-card.selected::after {
    content: '✓';
    position: absolute;
    top: 10px;
    right: 10px;
    background: #28a745;
    color: white;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}

.student-name {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.25rem;
}

.student-info {
    font-size: 0.875rem;
    color: #6c757d;
}

.date-selector {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.date-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.date-input-group {
    position: relative;
}

.date-input-group input {
    padding-right: 3rem;
}

.date-input-group::after {
    content: '📅';
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    pointer-events: none;
}

.time-constraint {
    background: linear-gradient(135deg, rgba(255, 193, 7, 0.1), rgba(255, 193, 7, 0.1));
    border: 1px solid rgba(255, 193, 7, 0.3);
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1.5rem;
}

.time-constraint-title {
    font-weight: 600;
    color: #856404;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.time-constraint-text {
    color: #856404;
    font-size: 0.9rem;
    margin: 0;
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
    .form-grid {
        grid-template-columns: 1fr;
    }

    .date-grid {
        grid-template-columns: 1fr;
    }

    .form-container {
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
                    <a href="{{ route('asistencia.mis-estudiantes') }}">
                        <i class="fas fa-users"></i>
                        <span>Mis Estudiantes</span>
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <i class="fas fa-file-alt"></i>
                    <span>Justificar Asistencia</span>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Header -->
    <div class="form-container">
        <div class="form-header">
            <h1 class="form-title">
                <i class="fas fa-file-alt mr-3 text-primary"></i>
                Justificar Inasistencia
            </h1>
            <p class="form-subtitle">
                Registra justificaciones médicas o personales para las faltas de tus estudiantes representados
            </p>
        </div>

        <!-- Restricción de tiempo -->
        <div class="alert alert-warning alert-modern">
            <div class="time-constraint">
                <div class="time-constraint-title">
                    <i class="fas fa-clock"></i>
                    <span>Restricción de Tiempo</span>
                </div>
                <p class="time-constraint-text">
                    Las justificaciones solo pueden registrarse hasta 1 día antes de la fecha de la clase programada.
                    Después de esa fecha, el profesor deberá registrar la asistencia directamente.
                </p>
            </div>
        </div>

        <form id="justificationForm" action="{{ route('asistencia.store-justification') }}" method="POST">
            @csrf

            <!-- Selector de Estudiante -->
            <div class="student-selector">
                <h5 class="mb-3">
                    <i class="fas fa-user mr-2"></i>
                    Seleccionar Estudiante
                </h5>

                @if($estudiantesRepresentados->isEmpty())
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        No tienes estudiantes asignados como representante.
                    </div>
                @else
                    <div class="row">
                        @foreach($estudiantesRepresentados as $item)
                            <div class="col-md-6 mb-3">
                                <div class="student-card" onclick="selectStudent({{ $item['estudiante']->estudiante_id }}, this)">
                                    <div class="student-name">
                                        @if ($item['es_principal'])
                                            <i class="fas fa-star text-warning mr-1"></i>
                                        @endif
                                        {{ $item['estudiante']->apellidos }}, {{ $item['estudiante']->nombres }}
                                    </div>
                                    <div class="student-info">
                                        <span><i class="fas fa-id-card mr-1"></i>{{ $item['estudiante']->dni }}</span>
                                        @if($item['matricula'] && $item['curso'])
                                            <br>
                                            <span><i class="fas fa-graduation-cap mr-1"></i>{{ $item['curso']->grado->nombre }} {{ $item['curso']->seccion->nombre }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <input type="hidden" name="estudiante_id" id="estudiante_id" required>
            </div>

            <!-- Información de la Justificación -->
            <div class="form-grid">
                <div class="form-group">
                    <label for="fecha_falta" class="form-label">
                        <i class="fas fa-calendar-times mr-1"></i>
                        Fecha de la Falta
                    </label>
                    <div class="date-input-group">
                        <input type="date" class="form-control-modern" id="fecha_falta" name="fecha_falta"
                               min="{{ date('Y-m-d') }}" required onchange="validateFecha(this.value)">
                    </div>
                    <div class="form-text">
                        Selecciona la fecha en la que el estudiante faltó a clases
                    </div>
                </div>

                <div class="form-group">
                    <label for="tipo_justificacion" class="form-label">
                        <i class="fas fa-tag mr-1"></i>
                        Tipo de Justificación
                    </label>
                    <select class="form-control-modern select-modern" id="tipo_justificacion" name="tipo_justificacion" required>
                        <option value="">Seleccionar tipo</option>
                        <option value="Médica">Médica</option>
                        <option value="Personal">Personal</option>
                        <option value="Familiar">Familiar</option>
                        <option value="Transporte">Transporte</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="descripcion" class="form-label">
                    <i class="fas fa-comment-alt mr-1"></i>
                    Descripción Detallada
                </label>
                <textarea class="form-control-modern textarea-modern" id="descripcion" name="descripcion"
                          placeholder="Describe detalladamente el motivo de la inasistencia..." required></textarea>
                <div class="form-text">
                    Proporciona detalles específicos sobre el motivo de la falta
                </div>
            </div>

            <!-- Documentos de respaldo (opcional) -->
            <div class="form-group">
                <label for="documento" class="form-label">
                    <i class="fas fa-paperclip mr-1"></i>
                    Documento de Respaldo (Opcional)
                </label>
                <input type="file" class="form-control-modern" id="documento" name="documento"
                       accept=".pdf,.jpg,.jpeg,.png">
                <div class="form-text">
                    Sube un certificado médico, constancia u otro documento que respalde la justificación
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="text-center mt-4">
                <button type="submit" class="btn-modern btn-primary-modern mr-3" id="submitBtn">
                    <i class="fas fa-paper-plane"></i>
                    Enviar Justificación
                </button>
                <a href="{{ route('asistencia.mis-estudiantes') }}" class="btn-modern btn-secondary-modern">
                    <i class="fas fa-arrow-left"></i>
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<script>
let selectedStudentId = null;

function selectStudent(studentId, element) {
    // Remove selected class from all cards
    document.querySelectorAll('.student-card').forEach(card => {
        card.classList.remove('selected');
    });

    // Add selected class to clicked card
    element.classList.add('selected');

    // Set selected student ID
    selectedStudentId = studentId;
    document.getElementById('estudiante_id').value = studentId;
}

function validateFecha(fechaSeleccionada) {
    const hoy = new Date();
    const fechaFalta = new Date(fechaSeleccionada);
    const fechaLimite = new Date(hoy);
    fechaLimite.setDate(hoy.getDate() + 1); // Máximo 1 día antes

    if (fechaFalta > fechaLimite) {
        alert('Solo puedes justificar faltas hasta 1 día antes de la fecha actual.');
        document.getElementById('fecha_falta').value = '';
        return false;
    }

    return true;
}

document.getElementById('justificationForm').addEventListener('submit', function(e) {
    if (!selectedStudentId) {
        e.preventDefault();
        alert('Por favor selecciona un estudiante.');
        return false;
    }

    const fechaFalta = document.getElementById('fecha_falta').value;
    if (!validateFecha(fechaFalta)) {
        e.preventDefault();
        return false;
    }

    // Show loading overlay
    document.getElementById('loadingOverlay').classList.add('show');

    // Disable submit button
    document.getElementById('submitBtn').disabled = true;
    document.getElementById('submitBtn').innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enviando...';
});

// Pre-select student if provided in URL
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const estudianteId = urlParams.get('estudiante_id');

    if (estudianteId) {
        const studentCard = document.querySelector(`[onclick*="selectStudent(${estudianteId}"]`);
        if (studentCard) {
            selectStudent(estudianteId, studentCard);
        }
    }
});
</script>

@endsection
