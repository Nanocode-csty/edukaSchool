@extends('cplantilla.bprincipal')

@section('titulo','Editar Asistencia - Docente')

@section('contenidoplantilla')

<x-breadcrumb :module="'asistencia'" :section="'docente-editar'" />

<div class="container-fluid" id="contenido-principal">
    <div class="row mt-4">
        <div class="col-12">
            <div class="box_block">
                <!-- Collapse header -->
                <button class="btn btn-block text-left rounded-0 btn_header header_6" type="button" data-toggle="collapse" data-target="#collapseEditarAsistencia" aria-expanded="true" aria-controls="collapseEditarAsistencia" style="background: #ffc107 !important; font-weight: bold; color: white;">
                    <i class="fas fa-edit m-1"></i>&nbsp;Editar Asistencia -
                    <span>{{ $sesion->cursoAsignatura->asignatura->nombre }} - {{ $sesion->cursoAsignatura->curso->grado->nombre }} {{ $sesion->cursoAsignatura->curso->seccion->nombre }}</span>
                    <div class="float-right"><i class="fas fa-chevron-down"></i></div>
                </button>

                <!-- Información de restricciones -->
                <div class="alert alert-warning alert-dismissible fade show" role="alert" style="border-radius: 0;">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Restricciones de Edición:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Solo puedes editar asistencias de los últimos 7 días</li>
                        <li>Los administradores pueden editar asistencias más antiguas</li>
                        <li>Esta acción afecta reportes y estadísticas</li>
                        <li>Todas las modificaciones quedan registradas</li>
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- Collapse: contenido de la edición -->
                <div class="collapse show" id="collapseEditarAsistencia">
                    <div class="card card-body rounded-0 border-0 pt-0 pb-2" style="background: transparent;">

                        @if(isset($error))
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle"></i> {{ $error }}
                            </div>
                        @else
                            <!-- Información de la sesión -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header bg-warning text-dark">
                                            <h5 class="mb-0">
                                                <i class="fas fa-info-circle"></i> Información de la Sesión
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="text-center">
                                                        <h6 class="text-muted mb-2">Fecha</h6>
                                                        <strong>{{ $sesion->fecha->locale('es')->dayName }}, {{ $sesion->fecha->format('d/m/Y') }}</strong>
                                                        <br><small class="text-muted">Hace {{ $estadisticas_sesion['dias_desde_sesion'] }} días</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="text-center">
                                                        <h6 class="text-muted mb-2">Horario</h6>
                                                        <strong>{{ substr($sesion->hora_inicio, 0, 5) }} - {{ substr($sesion->hora_fin, 0, 5) }}</strong>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="text-center">
                                                        <h6 class="text-muted mb-2">Asignatura</h6>
                                                        <strong>{{ $sesion->cursoAsignatura->asignatura->nombre }}</strong>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="text-center">
                                                        <h6 class="text-muted mb-2">Curso</h6>
                                                        <strong>{{ $sesion->cursoAsignatura->curso->grado->nombre }} {{ $sesion->cursoAsignatura->curso->seccion->nombre }}</strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Estadísticas de la sesión -->
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <div class="card border-info">
                                        <div class="card-body text-center">
                                            <h6 class="text-info mb-2">
                                                <i class="fas fa-users"></i> Estudiantes Totales
                                            </h6>
                                            <h3 class="text-primary">{{ $estadisticas_sesion['total_estudiantes'] }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card border-success">
                                        <div class="card-body text-center">
                                            <h6 class="text-success mb-2">
                                                <i class="fas fa-check-circle"></i> Asistencias Registradas
                                            </h6>
                                            <h3 class="text-success">{{ $estadisticas_sesion['asistencias_registradas'] }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card border-warning">
                                        <div class="card-body text-center">
                                            <h6 class="text-warning mb-2">
                                                <i class="fas fa-edit"></i> Permiso de Edición
                                            </h6>
                                            <h3 class="{{ $estadisticas_sesion['puede_editar'] ? 'text-success' : 'text-danger' }}">
                                                {{ $estadisticas_sesion['puede_editar'] ? 'Permitido' : 'Denegado' }}
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($estadisticas_sesion['puede_editar'])
                                <!-- Formulario de edición de asistencia -->
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header bg-warning text-dark">
                                                <h5 class="mb-0"><i class="fas fa-edit"></i> Editar Asistencia</h5>
                                                <small class="text-muted">Modifica los registros de asistencia existentes</small>
                                            </div>
                                            <div class="card-body">
                                                <form id="form-editar-asistencia" class="attendance-form">
                                                    <input type="hidden" name="sesion_clase_id" value="{{ $sesion->sesion_id }}">

                                                    <!-- Tabla de edición de asistencia -->
                                                    <div class="table-responsive">
                                                        <table class="table table-striped table-hover border">
                                                            <thead class="table-dark">
                                                                <tr>
                                                                    <th class="text-center" style="width: 60px;">#</th>
                                                                    <th style="min-width: 200px;">Estudiante</th>
                                                                    <th style="min-width: 150px;">DNI</th>
                                                                    <th class="text-center" style="min-width: 100px;">Presente</th>
                                                                    <th class="text-center" style="min-width: 100px;">Falta</th>
                                                                    <th class="text-center" style="min-width: 100px;">Tarde</th>
                                                                    <th class="text-center" style="min-width: 120px;">Justificado</th>
                                                                    <th class="text-center" style="min-width: 120px;">Estado Actual</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($estudiantes as $index => $estudiante)
                                                                <tr data-student-name="{{ strtolower($estudiante->estudiante->persona->nombres . ' ' . $estudiante->estudiante->persona->apellidos) }}">
                                                                    <td class="text-center font-weight-bold">{{ $index + 1 }}</td>
                                                                    <td>
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="avatar-circle-small mr-3" style="width: 35px; height: 35px; border-radius: 50%; background: #007bff; color: white; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: bold;">
                                                                                {{ substr($estudiante->estudiante->persona->nombres, 0, 1) }}{{ substr($estudiante->estudiante->persona->apellidos, 0, 1) }}
                                                                            </div>
                                                                            <div>
                                                                                <div class="font-weight-bold">{{ $estudiante->estudiante->persona->nombres }}</div>
                                                                                <small class="text-muted">{{ $estudiante->estudiante->persona->apellidos }}</small>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td>{{ $estudiante->estudiante->persona->dni }}</td>
                                                                    <td class="text-center">
                                                                        <button type="button" class="btn btn-sm btn-outline-success attendance-btn {{ $estudiante->asistencia_actual === 'P' ? 'active btn-success' : '' }}"
                                                                                data-matricula-id="{{ $estudiante->matricula_id }}"
                                                                                data-value="P"
                                                                                title="Marcar como Presente">
                                                                            <i class="fas fa-check"></i>
                                                                        </button>
                                                                        <input type="radio" name="asistencia_{{ $estudiante->matricula_id }}" value="P"
                                                                               {{ $estudiante->asistencia_actual === 'P' ? 'checked' : '' }}
                                                                               class="attendance-radio d-none"
                                                                               data-matricula-id="{{ $estudiante->matricula_id }}">
                                                                    </td>
                                                                    <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-danger attendance-btn {{ $estudiante->asistencia_actual === 'F' ? 'active btn-danger' : '' }}"
                                                                                data-matricula-id="{{ $estudiante->matricula_id }}"
                                                                        data-value="F"
                                        title="Marcar como Falta">
                                                                            <i class="fas fa-times"></i>
                                                                        </button>
                                                                        <input type="radio" name="asistencia_{{ $estudiante->matricula_id }}" value="F"
                                                                               {{ $estudiante->asistencia_actual === 'F' ? 'checked' : '' }}
                                                                               class="attendance-radio d-none"
                                                                               data-matricula-id="{{ $estudiante->matricula_id }}">
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <button type="button" class="btn btn-sm btn-outline-warning attendance-btn {{ $estudiante->asistencia_actual === 'T' ? 'active btn-warning' : '' }}"
                                                                                data-matricula-id="{{ $estudiante->matricula_id }}"
                                                                                data-value="T"
                                                                                title="Marcar como Tarde">
                                                                            <i class="fas fa-clock"></i>
                                                                        </button>
                                                                        <input type="radio" name="asistencia_{{ $estudiante->matricula_id }}" value="T"
                                                                               {{ $estudiante->asistencia_actual === 'T' ? 'checked' : '' }}
                                                                               class="attendance-radio d-none"
                                                                               data-matricula-id="{{ $estudiante->matricula_id }}">
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <button type="button" class="btn btn-sm btn-outline-info attendance-btn {{ $estudiante->asistencia_actual === 'J' ? 'active btn-info' : '' }}"
                                                                                data-matricula-id="{{ $estudiante->matricula_id }}"
                                                                                data-value="J"
                                                                                title="Marcar como Justificado">
                                                                            <i class="fas fa-file-medical"></i>
                                                                        </button>
                                                                        <input type="radio" name="asistencia_{{ $estudiante->matricula_id }}" value="J"
                                                                               {{ $estudiante->asistencia_actual === 'J' ? 'checked' : '' }}
                                                                               class="attendance-radio d-none"
                                                                               data-matricula-id="{{ $estudiante->matricula_id }}">
                                                                    </td>
                                                                    <td class="text-center">
                                                                        @if($estudiante->asistencia_actual)
                                                                            <span class="badge badge-{{ $estudiante->asistencia_actual === 'P' ? 'success' : ($estudiante->asistencia_actual === 'F' ? 'danger' : ($estudiante->asistencia_actual === 'T' ? 'warning' : 'info')) }}">
                                                                                {{ $estudiante->asistencia_actual === 'P' ? 'Presente' : ($estudiante->asistencia_actual === 'F' ? 'Falta' : ($estudiante->asistencia_actual === 'T' ? 'Tarde' : 'Justificado')) }}
                                                                            </span>
                                                                        @else
                                                                            <span class="badge badge-secondary">Sin Registro</span>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                    <!-- Observaciones generales -->
                                                    <div class="mt-4">
                                                        <div class="form-group">
                                                            <label for="observaciones_edicion">
                                                                <i class="fas fa-comment"></i> Observaciones de la edición (requerido):
                                                            </label>
                                                            <textarea class="form-control" id="observaciones_edicion" name="observaciones_edicion"
                                                                      rows="3" placeholder="Explica el motivo de los cambios realizados..." required></textarea>
                                                            <small class="form-text text-muted">Esta información se registra para auditoría</small>
                                                        </div>
                                                    </div>

                                                    <!-- Botones de acción -->
                                                    <div class="text-center mt-4">
                                                        <button type="button" class="btn btn-outline-secondary mr-2" onclick="window.history.back()">
                                                            <i class="fas fa-arrow-left"></i> Volver
                                                        </button>
                                                        <button type="button" class="btn btn-outline-info mr-2" onclick="marcarTodosPresentesEdicion()">
                                                            <i class="fas fa-check-double"></i> Todos Presentes
                                                        </button>
                                                        <button type="button" class="btn btn-warning" onclick="guardarEdicionAsistencia(document.getElementById('form-editar-asistencia'))">
                                                            <i class="fas fa-save"></i> Guardar Cambios
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <!-- Mensaje de restricción -->
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card border-danger">
                                            <div class="card-body text-center py-5">
                                                <i class="fas fa-lock fa-4x text-danger mb-4"></i>
                                                <h4 class="text-danger mb-3">Edición Restringida</h4>
                                                <p class="text-muted mb-4">
                                                    No tienes permisos para editar esta asistencia. Solo se pueden editar asistencias de los últimos 7 días, o contacta al administrador para ediciones más antiguas.
                                                </p>
                                                <div class="mb-3">
                                                    <strong>Días transcurridos desde la sesión:</strong> {{ $estadisticas_sesion['dias_desde_sesion'] }} días
                                                </div>
                                                <a href="{{ route('asistencia.docente.tomar-asistencia') }}" class="btn btn-primary">
                                                    <i class="fas fa-arrow-left"></i> Volver a Toma de Asistencia
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Acciones Rápidas -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="d-flex justify-content-end gap-2 flex-wrap">
                                        <a href="{{ route('asistencia.docente.ver-asistencias') }}" class="btn btn-sm" style="background-color: #28a745 !important; color: white !important; border: none !important;">
                                            <i class="fas fa-eye mr-1"></i>Ver Todas las Asistencias
                                        </a>
                                        <a href="{{ route('asistencia.docente.dashboard') }}" class="btn btn-sm" style="background-color: #6c757d !important; color: white !important; border: none !important;">
                                            <i class="fas fa-arrow-left mr-1"></i>Volver al Dashboard
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css-extra')
<style>
/* Attendance Button Styles */
.attendance-btn {
    border: 2px solid transparent !important;
    transition: all 0.2s ease !important;
    font-weight: 600 !important;
    min-height: 35px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 5px !important;
    cursor: pointer !important;
    user-select: none !important;
}

.attendance-btn:hover {
    transform: translateY(-1px) !important;
    box-shadow: 0 3px 8px rgba(0,0,0,0.15) !important;
}

.attendance-btn.active {
    border-color: #fff !important;
    box-shadow: 0 0 0 3px rgba(0,0,0,0.2) !important;
    transform: scale(1.05) !important;
}

.attendance-btn i {
    font-size: 14px !important;
}

/* Specific button styles */
.attendance-btn.btn-success.active {
    background-color: #28a745 !important;
    border-color: #1e7e34 !important;
}

.attendance-btn.btn-danger.active {
    background-color: #dc3545 !important;
    border-color: #bd2130 !important;
}

.attendance-btn.btn-warning.active {
    background-color: #ffc107 !important;
    border-color: #d39e00 !important;
    color: #212529 !important;
}

.attendance-btn.btn-info.active {
    background-color: #17a2b8 !important;
    border-color: #138496 !important;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .attendance-btn {
        min-height: 40px !important;
        font-size: 12px !important;
    }
}

@media (max-width: 576px) {
    .attendance-btn {
        padding: 8px 12px !important;
        min-height: 45px !important;
        font-size: 11px !important;
    }
}
</style>
@endpush

@push('js-extra')
<script>
// Global object to store selected attendance for each student
let selectedAttendance = {};

document.addEventListener('DOMContentLoaded', function () {
    console.log('=== INICIALIZANDO SISTEMA DE ASISTENCIA ===');

    // Initialize selected attendance from ALL students (checked radios or default)
    const allRadios = document.querySelectorAll('input[type="radio"][name^="asistencia_"]');
    console.log(`Total radio buttons en formulario: ${allRadios.length}`);

    // Group radios by student
    const radiosByStudent = {};
    allRadios.forEach(radio => {
        const matriculaId = radio.getAttribute('data-matricula-id');
        if (!radiosByStudent[matriculaId]) {
            radiosByStudent[matriculaId] = [];
        }
        radiosByStudent[matriculaId].push(radio);
    });

    console.log(`Estudiantes encontrados en formulario: ${Object.keys(radiosByStudent).length}`);

    // Initialize selectedAttendance for each student
    Object.keys(radiosByStudent).forEach(matriculaId => {
        const studentRadios = radiosByStudent[matriculaId];
        const checkedRadio = studentRadios.find(radio => radio.checked);

        if (checkedRadio) {
            selectedAttendance[matriculaId] = checkedRadio.value;
            console.log(`Asistencia inicial: Estudiante ${matriculaId} = ${checkedRadio.value} (marcado)`);
        } else {
            // If no radio is checked, use a default or mark as needing attention
            selectedAttendance[matriculaId] = null; // Will be handled in saving logic
            console.log(`Asistencia inicial: Estudiante ${matriculaId} = sin marcar`);
        }
    });

    console.log('Asistencia inicial completa:', selectedAttendance);

    // Handle edit attendance form submission
    const editForm = document.getElementById('form-editar-asistencia');
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            e.preventDefault();
            guardarEdicionAsistencia(this);
        });
        console.log('✓ Event listener asignado al formulario de edición');
    } else {
        console.error('✗ Formulario de edición no encontrado');
    }

    // Handle new attendance button clicks for edit
    const attendanceButtons = document.querySelectorAll('.attendance-btn');
    console.log(`Encontrados ${attendanceButtons.length} botones de asistencia`);

    attendanceButtons.forEach((button, index) => {
        const matriculaId = button.getAttribute('data-matricula-id');
        const value = button.getAttribute('data-value');

        if (index < 10) { // Log only first 10 buttons to avoid spam
            console.log(`Botón ${index}: matricula=${matriculaId}, value=${value}`);
        }

        button.addEventListener('click', function() {
            console.log(`Clic en botón: matricula=${matriculaId}, value=${value}`);
            handleAttendanceButtonClickEdit(this);
        });
    });

    console.log('✓ Event listeners asignados a todos los botones');
    console.log('=== INICIALIZACIÓN COMPLETADA ===');
});

// Function to mark all students as present in edit mode
function marcarTodosPresentesEdicion() {
    console.log('=== MARCANDO TODOS COMO PRESENTES EN EDICIÓN ===');

    // Update selectedAttendance for all students
    const allAttendanceButtons = document.querySelectorAll('.attendance-btn');
    console.log('Botones encontrados:', allAttendanceButtons.length);

    // Get unique student IDs
    const studentIds = new Set();
    allAttendanceButtons.forEach(button => {
        const matriculaId = button.getAttribute('data-matricula-id');
        studentIds.add(matriculaId);
    });

    console.log('Estudiantes únicos encontrados:', studentIds.size);

    // Mark all students as present in selectedAttendance
    studentIds.forEach(matriculaId => {
        selectedAttendance[matriculaId] = 'P';
        console.log(`Marcando estudiante ${matriculaId} como Presente`);
    });

    console.log('selectedAttendance actualizado:', selectedAttendance);

    // Update visual state of all attendance buttons
    const buttonsByStudent = {};
    allAttendanceButtons.forEach(button => {
        const matriculaId = button.getAttribute('data-matricula-id');
        if (!buttonsByStudent[matriculaId]) {
            buttonsByStudent[matriculaId] = [];
        }
        buttonsByStudent[matriculaId].push(button);
    });

    // Process each student
    Object.keys(buttonsByStudent).forEach(matriculaId => {
        const studentButtons = buttonsByStudent[matriculaId];

        // First, reset all buttons for this student to outline style
        studentButtons.forEach(btn => {
            // Remove active class
            btn.classList.remove('active');

            // Convert solid colors back to outline
            if (btn.classList.contains('btn-success')) {
                btn.classList.remove('btn-success');
                btn.classList.add('btn-outline-success');
            }
            if (btn.classList.contains('btn-danger')) {
                btn.classList.remove('btn-danger');
                btn.classList.add('btn-outline-danger');
            }
            if (btn.classList.contains('btn-warning')) {
                btn.classList.remove('btn-warning');
                btn.classList.add('btn-outline-warning');
            }
            if (btn.classList.contains('btn-info')) {
                btn.classList.remove('btn-info');
                btn.classList.add('btn-outline-info');
            }
        });

        // Then, activate the "Presente" button
        const presenteButton = studentButtons.find(btn => btn.getAttribute('data-value') === 'P');
        if (presenteButton) {
            presenteButton.classList.add('active');
            if (presenteButton.classList.contains('btn-outline-success')) {
                presenteButton.classList.remove('btn-outline-success');
                presenteButton.classList.add('btn-success');
            }
        }
    });

    console.log('=== TODOS MARCADOS COMO PRESENTES EN EDICIÓN ===');

    // Show success message
    Swal.fire({
        icon: 'success',
        title: '¡Listo!',
        text: 'Todos los estudiantes marcados como presentes',
        timer: 1500,
        showConfirmButton: false
    });
}

// Function to handle attendance button clicks in edit mode
function handleAttendanceButtonClickEdit(button) {
    const matriculaId = button.getAttribute('data-matricula-id');
    const value = button.getAttribute('data-value');

    console.log(`Cambiando asistencia para estudiante ${matriculaId} a ${value} (EDICIÓN)`);

    // Update selected attendance object
    selectedAttendance[matriculaId] = value;
    console.log(`selectedAttendance actualizado:`, selectedAttendance);

    // Remove active class and reset to outline style for all buttons for this student
    const studentButtons = document.querySelectorAll(`.attendance-btn[data-matricula-id="${matriculaId}"]`);
    studentButtons.forEach(btn => {
        // Remove active class
        btn.classList.remove('active');

        // Convert solid colors back to outline
        if (btn.classList.contains('btn-success')) {
            btn.classList.remove('btn-success');
            btn.classList.add('btn-outline-success');
        }
        if (btn.classList.contains('btn-danger')) {
            btn.classList.remove('btn-danger');
            btn.classList.add('btn-outline-danger');
        }
        if (btn.classList.contains('btn-warning')) {
            btn.classList.remove('btn-warning');
            btn.classList.add('btn-outline-warning');
        }
        if (btn.classList.contains('btn-info')) {
            btn.classList.remove('btn-info');
            btn.classList.add('btn-outline-info');
        }
    });

    // Add active class and solid color to clicked button
    button.classList.add('active');
    if (button.classList.contains('btn-outline-success')) {
        button.classList.remove('btn-outline-success');
        button.classList.add('btn-success');
    } else if (button.classList.contains('btn-outline-danger')) {
        button.classList.remove('btn-outline-danger');
        button.classList.add('btn-danger');
    } else if (button.classList.contains('btn-outline-warning')) {
        button.classList.remove('btn-outline-warning');
        button.classList.add('btn-warning');
    } else if (button.classList.contains('btn-outline-info')) {
        button.classList.remove('btn-outline-info');
        button.classList.add('btn-info');
    }

    // Update the corresponding radio button - find by name and value (radio is in same cell, after button)
    const radioName = `asistencia_${matriculaId}`;
    const radio = document.querySelector(`input[name="${radioName}"][value="${value}"]`);
    if (radio) {
        // First, uncheck all radios for this student
        const allRadiosForStudent = document.querySelectorAll(`input[name="${radioName}"]`);
        allRadiosForStudent.forEach(r => r.checked = false);

        // Then check the selected one
        radio.checked = true;
        console.log(`Radio button marcado: ${radioName} = ${value}`);
    } else {
        console.error(`Radio button no encontrado: ${radioName} con valor ${value}`);
    }

    console.log(`Asistencia actualizada: Estudiante ${matriculaId} - Tipo ${value} (EDICIÓN)`);
}

// Function to update attendance option visuals for a specific student
function updateAttendanceOptionVisualsForStudent(matriculaId, selectedType) {
    // Remove active class from all options for this student
    const studentOptions = document.querySelectorAll(`input[data-matricula-id="${matriculaId}"]`);
    studentOptions.forEach(input => {
        const option = input.closest('.attendance-option');
        if (option) {
            option.classList.remove('active', 'present', 'absent', 'late', 'justified');
        }
    });

    // Add active class to selected option
    const selectedOption = document.querySelector(`input[data-matricula-id="${matriculaId}"][value="${selectedType}"]`);
    if (selectedOption) {
        const option = selectedOption.closest('.attendance-option');
        if (option) {
            const type = selectedType.toLowerCase();
            option.classList.add('active', type);
        }
    }
}

// Function to save edited attendance
function guardarEdicionAsistencia(form) {
    if (!form) {
        console.error('Form is null');
        return;
    }

    const sesionId = form.querySelector('input[name="sesion_clase_id"]');
    if (!sesionId) {
        console.error('sesion_clase_id input not found');
        return;
    }

    const sesionIdValue = sesionId.value;
    const asistencias = [];
    let hasSelections = false;

    console.log('=== INICIANDO GUARDADO DE EDICIÓN ===');
    console.log('Sesion ID:', sesionIdValue);
    console.log('selectedAttendance actual:', selectedAttendance);
    console.log('Número de estudiantes en selectedAttendance:', Object.keys(selectedAttendance).length);

    // Check all radio buttons status first
    console.log('--- ESTADO ACTUAL DE TODOS LOS RADIO BUTTONS ---');
    const allRadios = form.querySelectorAll('input[type="radio"][name^="asistencia_"]');
    const radioStatus = {};
    allRadios.forEach(radio => {
        const matriculaId = radio.getAttribute('data-matricula-id');
        const value = radio.value;
        const isChecked = radio.checked;

        if (!radioStatus[matriculaId]) {
            radioStatus[matriculaId] = {};
        }
        radioStatus[matriculaId][value] = isChecked;

        if (isChecked) {
            console.log(`✓ Radio marcado: Estudiante ${matriculaId} = ${value}`);
        }
    });

    // Check which students have any radio checked
    Object.keys(radioStatus).forEach(matriculaId => {
        const studentRadios = radioStatus[matriculaId];
        const hasAnyChecked = Object.values(studentRadios).some(checked => checked);
        const hasInSelectedAttendance = selectedAttendance[matriculaId] !== undefined;

        console.log(`Estudiante ${matriculaId}: Radio marcado=${hasAnyChecked}, En selectedAttendance=${hasInSelectedAttendance}, Valor=${selectedAttendance[matriculaId]}`);
    });

    // Use selectedAttendance object - this is more reliable than radio buttons
    Object.keys(selectedAttendance).forEach(matriculaId => {
        const tipoAsistencia = selectedAttendance[matriculaId];
        if (tipoAsistencia && tipoAsistencia.trim() !== '') {
            console.log(`✓ ENVIANDO - Estudiante ${matriculaId}: ${tipoAsistencia}`);

            asistencias.push({
                matricula_id: matriculaId,
                tipo_asistencia: tipoAsistencia,
                observaciones: null
            });
            hasSelections = true;
        } else {
            console.log(`✗ OMITIENDO - Estudiante ${matriculaId}: asistencia vacía (${tipoAsistencia})`);
        }
    });

    // Fallback: Also check radio buttons in case selectedAttendance is incomplete
    const checkedRadios = form.querySelectorAll('input[type="radio"]:checked');
    console.log('Radio buttons marcados encontrados:', checkedRadios.length);

    checkedRadios.forEach(radio => {
        const matriculaId = radio.getAttribute('data-matricula-id');
        const tipoAsistencia = radio.value;

        // Only add if not already in asistencias
        const alreadyIncluded = asistencias.some(a => a.matricula_id === matriculaId);
        if (!alreadyIncluded && tipoAsistencia && tipoAsistencia.trim() !== '') {
            console.log(`Fallback - Agregando estudiante ${matriculaId}: ${tipoAsistencia}`);
            asistencias.push({
                matricula_id: matriculaId,
                tipo_asistencia: tipoAsistencia,
                observaciones: null
            });
            hasSelections = true;
        }
    });

    console.log(`Estudiantes con asistencia: ${asistencias.length}`);
    console.log('Datos a enviar:', asistencias);
    console.log('=== FIN GUARDADO DE EDICIÓN ===');

    if (!hasSelections) {
        Swal.fire({
            icon: 'warning',
            title: 'Atención',
            text: 'Debes seleccionar al menos un tipo de asistencia para un estudiante.'
        });
        return;
    }

    // Get edit observations (required for edit)
    const observacionesEdicion = form.querySelector('#observaciones_edicion');
    if (!observacionesEdicion || !observacionesEdicion.value.trim()) {
        Swal.fire({
            icon: 'warning',
            title: 'Observaciones requeridas',
            text: 'Debes proporcionar observaciones explicando los cambios realizados.'
        });
        observacionesEdicion.focus();
        return;
    }

    // Show loading state
    const submitBtn = form.querySelector('button[onclick*="guardarEdicionAsistencia"]');
    if (!submitBtn) {
        console.error('Save button not found');
        return;
    }

    const originalHtml = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando Cambios...';
    submitBtn.disabled = true;

    // Show confirmation dialog for editing
    Swal.fire({
        title: '¿Confirmar edición?',
        text: 'Esta acción modificará los registros de asistencia existentes. ¿Estás seguro de continuar?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ffc107',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, guardar cambios',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Prepare data to send
            const dataToSend = {
                sesion_clase_id: sesionIdValue,
                asistencias: asistencias,
                observaciones_generales: observacionesEdicion.value.trim(),
                es_edicion: true // Flag to indicate this is an edit operation
            };

            console.log('=== DATOS A ENVIAR AL SERVIDOR ===');
            console.log('URL:', '{{ route("asistencia.docente.guardar") }}');
            console.log('Data:', JSON.stringify(dataToSend, null, 2));
            console.log('Asistencias array length:', asistencias.length);
            asistencias.forEach((asistencia, index) => {
                console.log(`Asistencia ${index}: matricula_id=${asistencia.matricula_id}, tipo=${asistencia.tipo_asistencia}`);
            });

            // Send data to server
            fetch('{{ route("asistencia.docente.guardar") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(dataToSend)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: '¡Cambios guardados!',
                        text: data.message,
                        timer: 3000,
                        showConfirmButton: false
                    }).then(() => {
                        // Redirect back to attendance list
                        window.location.href = '{{ route("asistencia.docente.ver-asistencias") }}';
                    });
                } else {
                    // Show error message
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error al guardar los cambios'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al guardar los cambios. Inténtalo de nuevo.'
                });
            })
            .finally(() => {
                // Restore button state
                if (submitBtn) {
                    submitBtn.innerHTML = originalHtml;
                    submitBtn.disabled = false;
                }
            });
        } else {
            // User cancelled, restore button state
            if (submitBtn) {
                submitBtn.innerHTML = originalHtml;
                submitBtn.disabled = false;
            }
        }
    });
}
</script>
@endpush
