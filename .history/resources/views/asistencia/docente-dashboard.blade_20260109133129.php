@extends('cplantilla.bprincipal')
@section('titulo','Tomar Asistencia - Docente')
@section('contenidoplantilla')
<x-breadcrumb :module="'asistencia'" :section="'docente-tomar'" />
<div class="container-fluid" id="contenido-principal">
    <div class="row mt-4">
        <div class="col-12">
            <div class="box_block">
                <!-- Weekly Calendar Section -->
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">
                                    <i class="fas fa-calendar-week"></i> Calendario Semanal de Asistencias
                                </h6>
                                <small>Selecciona un día para ver su horario</small>
                            </div>
                            <div class="d-flex gap-2">
                                <button class="btn btn-light btn-sm" type="button" onclick="cambiarSemana(-1)">
                                    <i class="fas fa-chevron-left"></i> Semana Anterior
                                </button>
                                <button class="btn btn-light btn-sm" type="button" id="toggleCalendarBtn">
                                    <i class="fas fa-chevron-down"></i> Ver Calendario
                                </button>
                                <button class="btn btn-light btn-sm" type="button" onclick="cambiarSemana(1)">
                                    Semana Siguiente <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div id="weeklyCalendar" class="card-body p-0" style="display: none;">
                        <div id="weekly-calendar-container">
                            <!-- Calendar will be loaded here -->
                        </div>
                    </div>
                </div>

                <!-- Main Attendance Section -->
                <!-- Collapse header -->
                <button class="btn btn-block text-left rounded-0 btn_header header_6" type="button" data-toggle="collapse" data-target="#collapseTomarAsistencia" aria-expanded="true" aria-controls="collapseTomarAsistencia" style="background: #007bff !important; font-weight: bold; color: white;">
                    <i class="fas fa-clipboard-check m-1"></i>&nbsp;Tomar Asistencia -
                    <span id="current-date-display">{{ $fecha_seleccionada ? $fecha_seleccionada->locale('es')->dayName . ', ' . $fecha_seleccionada->format('d/m/Y') : now()->locale('es')->dayName . ', ' . date('d/m/Y') }}</span>
                    <div class="float-right"><i class="fas fa-chevron-down"></i></div>
                </button>
                <!-- Descripción (siempre visible, dentro del bloque) -->
                <div class="card-body rounded-0 border-0 pt-3 pb-3" style="background: #f3f3f3; border-bottom: 1px solid rgba(0,0,0,.125); border-top: 1px solid rgba(0,0,0,.125); color: #F59D24;">
                    <div class="row justify-content-center align-items-center flex-wrap">
                        <div class="col-auto text-center mb-2 mb-md-0" style="min-width:48px;">
                            <i class="fas fa-calendar-day fa-2x"></i>
                        </div>
                        <div class="col px-2" style="text-align:justify;">
                            <p style="margin-bottom: 0px; font-family: 'Quicksand', sans-serif; font-weight: 600; color: #004a92;">
                                Registra la asistencia de tus estudiantes directamente en esta vista. Selecciona el tipo de asistencia para cada estudiante y guarda los cambios.
                            </p>
                        </div>
                    </div>
                </div>
                <!-- Collapse: contenido del dashboard -->
                <div class="collapse show" id="collapseTomarAsistencia">
                    <div class="card card-body rounded-0 border-0 pt-0 pb-2" style="background: transparent;">

                        @if(isset($error))
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle"></i> {{ $error }}
                            </div>
                        @else

                            @if($clases_hoy->count() > 0)
                                <!-- Vista de Horario Organizada - Full Width -->
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card">
                                        <div class="card-header bg-primary text-white">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <div class="mr-3">
                                                        <h5 class="mb-0">
                                                            <i class="fas fa-calendar-alt"></i> Horario de Clases
                                                        </h5>
                                                        <small>{{ $clases_hoy->count() }} clases programadas</small>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <div class="text-right mr-3">
                                                        <div class="d-flex gap-3">
                                                            <div class="text-center">
                                                                <div class="h4 mb-0">{{ $clases_hoy->where('tiene_asistencia_hoy', true)->count() }}</div>
                                                                <small>Completadas</small>
                                                            </div>
                                                            <div class="text-center">
                                                                <div class="h4 mb-0">{{ $clases_hoy->where('tiene_asistencia_hoy', false)->count() }}</div>
                                                                <small>Pendientes</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button class="btn btn-outline-light btn-sm" type="button" data-toggle="collapse" data-target="#horarioClases" aria-expanded="true" aria-controls="horarioClases">
                                                        <i class="fas fa-chevron-down"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="collapse show" id="horarioClases">
                                        <div class="card-body p-0">
                                            <!-- Timeline de Clases -->
                                            <div class="schedule-timeline">
                                                @php
                                                    $clases_ordenadas = $clases_hoy->sortBy('hora_inicio');
                                                    $horarios = ['07:00', '08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00'];
                                                @endphp

                                                @foreach($horarios as $hora)
                                                @php
                                                    $clases_en_hora = $clases_ordenadas->filter(function($clase) use ($hora) {
                                                        return substr($clase->hora_inicio, 0, 2) === substr($hora, 0, 2);
                                                    });
                                                @endphp

                                                @if($clases_en_hora->count() > 0)
                                                <div class="time-slot">
                                                    <div class="time-label">
                                                        <div class="time-circle">{{ substr($hora, 0, 5) }}</div>
                                                    </div>
                                                    <div class="classes-container">
                                                        @foreach($clases_en_hora as $clase)
                                                        <div class="class-card-wrapper">
                                                            <div class="class-card {{ $clase->tiene_asistencia_hoy ? 'completed' : 'pending' }}"
                                                                 onclick="toggleClassDetails({{ $clase->sesion_id }})">
                                                                <div class="class-header">
                                                                    <div class="class-info">
                                                                        <div class="subject-name">
                                                                            <i class="fas fa-book"></i>
                                                                            {{ $clase->cursoAsignatura->asignatura->nombre }}
                                                                        </div>
                                                                        <div class="class-details">
                                                                            <span class="grade-section">
                                                                                {{ $clase->cursoAsignatura->curso->grado->nombre }} {{ $clase->cursoAsignatura->curso->seccion->nombre }}
                                                                            </span>
                                                                            <span class="time-range">
                                                                                {{ $clase->hora_inicio }} - {{ $clase->hora_fin }}
                                                                            </span>
                                                                            <span class="classroom">
                                                                                <i class="fas fa-map-marker-alt"></i> {{ $clase->aula ? $clase->aula->nombre : 'Sin aula' }}
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="class-status">
                                                                        @if($clase->tiene_asistencia_hoy)
                                                                            <div class="status-badge completed">
                                                                                <i class="fas fa-check-circle"></i>
                                                                                <span>Completada</span>
                                                                            </div>
                                                                        @else
                                                                            <div class="status-badge pending">
                                                                                <i class="fas fa-clock"></i>
                                                                                <span>Pendiente</span>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="class-actions">
                                                                    @if($clase->tiene_asistencia_hoy)
                                                                        <button class="btn btn-sm btn-outline-success" onclick="event.stopPropagation(); verAsistencia({{ $clase->sesion_id }})">
                                                                            <i class="fas fa-eye"></i> Ver
                                                                        </button>
                                                                    @else
                                                                        <button class="btn btn-sm btn-primary" onclick="event.stopPropagation(); marcarAsistenciaRapida({{ $clase->sesion_id }})">
                                                                            <i class="fas fa-edit"></i> Marcar
                                                                        </button>
                                                                    @endif
                                                                </div>
                                                            </div>

                                                            <!-- Panel Expandible de Asistencia -->
                                                            <div class="attendance-panel" id="attendance-panel-{{ $clase->sesion_id }}" style="display: none;">
                                                                <div class="attendance-content">
                                                                    @if($clase->tiene_asistencia_hoy)
                                                                        <div class="alert alert-success">
                                                                            <i class="fas fa-check-circle"></i> La asistencia para esta clase ya ha sido registrada.
                                                                            <a href="{{ route('asistencia.docente.ver', $clase->sesion_id) }}" class="alert-link">Ver detalles completos</a>
                                                                        </div>
                                                                    @else
                                                                        <!-- Filtros para estudiantes -->
                                                                        <div class="filters-section mb-3">
                                                                            <div class="row align-items-center">
                                                                                <div class="col-md-6">
                                                                                    <div class="input-group input-group-sm">
                                                                                        <div class="input-group-prepend">
                                                                                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                                                                                        </div>
                                                                                        <input type="text" class="form-control student-search" id="search_{{ $clase->sesion_id }}"
                                                                                               placeholder="Buscar estudiante..." data-sesion-id="{{ $clase->sesion_id }}">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <div class="d-flex gap-2">
                                                                                        <select class="form-control form-control-sm attendance-filter" id="filter_{{ $clase->sesion_id }}" data-sesion-id="{{ $clase->sesion_id }}">
                                                                                            <option value="all">Todos</option>
                                                                                            <option value="present">Presentes</option>
                                                                                            <option value="absent">Ausentes</option>
                                                                                            <option value="late">Tardes</option>
                                                                                            <option value="justified">Justificados</option>
                                                                                        </select>
                                                                                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="limpiarFiltros({{ $clase->sesion_id }})">
                                                                                            <i class="fas fa-times"></i>
                                                                                        </button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="mt-2">
                                                                                <small class="text-muted">
                                                                                    <span id="student-count-{{ $clase->sesion_id }}"></span>
                                                                                </small>
                                                                            </div>
                                                                        </div>

                                                                        <form id="form-asistencia-{{ $clase->sesion_id }}" class="attendance-form" data-sesion-id="{{ $clase->sesion_id }}">
                                                                            <div class="attendance-grid">
                                                                                @php
                                                                                    $estudiantes = $clase->cursoAsignatura->curso->matriculas()
                                                                                        ->with(['estudiante.persona'])
                                                                                        ->where('estado', 'Activo')
                                                                                        ->orderBy('matriculas.matricula_id')
                                                                                        ->get();
                                                                                @endphp

                                                                                @foreach($estudiantes as $index => $matricula)
                                                                                <div class="student-attendance-card student-row"
                                                                                     data-student-name="{{ strtolower($matricula->estudiante->persona->nombres . ' ' . $matricula->estudiante->persona->apellidos) }}"
                                                                                     data-attendance-type="present">
                                                                                    <div class="student-header">
                                                                                        <div class="student-avatar">
                                                                                            <div class="avatar-circle">
                                                                                                {{ substr($matricula->estudiante->persona->nombres, 0, 1) }}{{ substr($matricula->estudiante->persona->apellidos, 0, 1) }}
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="student-info">
                                                                                            <div class="student-name">{{ $matricula->estudiante->persona->nombres }}</div>
                                                                                            <div class="student-lastname">{{ $matricula->estudiante->persona->apellidos }}</div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="attendance-options">
                                                                                        <label class="attendance-option present active">
                                                                                            <input type="radio" name="asistencia_{{ $clase->sesion_id }}_{{ $matricula->matricula_id }}" value="P" checked
                                                                                                   class="attendance-radio" data-matricula-id="{{ $matricula->matricula_id }}" data-sesion-id="{{ $clase->sesion_id }}">
                                                                                            <i class="fas fa-check-circle"></i>
                                                                                            <span>Presente</span>
                                                                                        </label>
                                                                                        <label class="attendance-option absent">
                                                                                            <input type="radio" name="asistencia_{{ $clase->sesion_id }}_{{ $matricula->matricula_id }}" value="A"
                                                                                                   class="attendance-radio" data-matricula-id="{{ $matricula->matricula_id }}" data-sesion-id="{{ $clase->sesion_id }}">
                                                                                            <i class="fas fa-times-circle"></i>
                                                                                            <span>Ausente</span>
                                                                                        </label>
                                                                                        <label class="attendance-option late">
                                                                                            <input type="radio" name="asistencia_{{ $clase->sesion_id }}_{{ $matricula->matricula_id }}" value="T"
                                                                                                   class="attendance-radio" data-matricula-id="{{ $matricula->matricula_id }}" data-sesion-id="{{ $clase->sesion_id }}">
                                                                                            <i class="fas fa-clock"></i>
                                                                                            <span>Tarde</span>
                                                                                        </label>
                                                                                        <label class="attendance-option justified">
                                                                                            <input type="radio" name="asistencia_{{ $clase->sesion_id }}_{{ $matricula->matricula_id }}" value="J"
                                                                                                   class="attendance-radio" data-matricula-id="{{ $matricula->matricula_id }}" data-sesion-id="{{ $clase->sesion_id }}">
                                                                                            <i class="fas fa-file-medical"></i>
                                                                                            <span>Justificado</span>
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                                @endforeach
                                                                            </div>

                                                                            <div class="attendance-footer">
                                                                                <div class="row align-items-center">
                                                                                    <div class="col-md-8">
                                                                                        <div class="form-group mb-0">
                                                                                            <label for="observaciones_{{ $clase->sesion_id }}">
                                                                        <i class="fas fa-comment"></i> Observaciones generales:
                                                                    </label>
                                                                                            <textarea class="form-control form-control-sm" id="observaciones_{{ $clase->sesion_id }}" name="observaciones_generales"
                                                                                                      rows="2" placeholder="Observaciones para toda la clase..."></textarea>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-4 text-right">
                                                                                        <div class="d-flex gap-2 justify-content-end">
                                                                                            <button type="button" class="btn btn-outline-secondary btn-sm"
                                                                                                    onclick="marcarTodosPresentes({{ $clase->sesion_id }})">
                                                                                                <i class="fas fa-check-double"></i> Todos Presentes
                                                                                            </button>
                                                                                            <button type="submit" class="btn btn-success">
                                                                                                <i class="fas fa-save"></i> Guardar
                                                                                            </button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Resumen General -->
                                <div class="card border-info">
                                    <div class="card-header bg-info text-white">
                                        <h6 class="mb-0">
                                            <i class="fas fa-chart-bar"></i> Resumen del Día
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row text-center">
                                            <div class="col-md-3">
                                                <div class="p-3 bg-light rounded">
                                                    <h4 class="text-primary">{{ $clases_hoy->count() }}</h4>
                                                    <small class="text-muted">Clases Totales</small>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="p-3 bg-success rounded text-white">
                                                    <h4>{{ $clases_hoy->where('tiene_asistencia_hoy', true)->count() }}</h4>
                                                    <small>Completadas</small>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="p-3 bg-warning rounded text-white">
                                                    <h4>{{ $clases_hoy->where('tiene_asistencia_hoy', false)->count() }}</h4>
                                                    <small>Pendientes</small>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="p-3 bg-info rounded text-white">
                                                    <h4>{{ $clases_hoy->sum(function($c) { return $c->cursoAsignatura->curso->matriculas()->where('estado', 'Activo')->count(); }) }}</h4>
                                                    <small>Estudiantes Totales</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-calendar-times text-muted fa-4x mb-4"></i>
                                    <h4 class="text-muted mb-3">No hay clases programadas para hoy</h4>
                                    <p class="text-muted mb-4">No tienes sesiones de clase programadas para el día de hoy.</p>
                                    <a href="{{ route('asistencia.docente.dashboard') }}" class="btn btn-primary">
                                        <i class="fas fa-arrow-left"></i> Volver al Dashboard
                                    </a>
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
/* Timeline Schedule Styles */
.schedule-timeline {
    position: relative;
    padding-left: 20px;
}

.time-slot {
    display: flex;
    margin-bottom: 30px;
    position: relative;
}

.time-slot::before {
    content: '';
    position: absolute;
                <td>
                    <select class="form-control form-control-sm tipo-asistencia" data-index="${index}" name="asistencias[${index}][tipo_asistencia]">
                        <option value="P">Presente</option>
                        <option value="A">Ausente</option>
                        <option value="T">Tarde</option>
                        <option value="J">Justificado</option>
                    </select>
                    <input type="hidden" name="asistencias[${index}][matricula_id]" value="${estudiante.matricula_id}">
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm" name="asistencias[${index}][observaciones]"
                           placeholder="Observaciones opcionales">
                </td>
            </tr>
        `;
    });

    html += `
                    </tbody>
                </table>
            </div>
        </form>
    `;

    $('#modalContent').html(html);
}

function guardarAsistencia() {
    const formData = new FormData(document.getElementById('formAsistencia'));

    // Deshabilitar botón mientras se guarda
    $('#btnGuardarAsistencia').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');

    $.ajax({
        url: '{{ route("asistencia.docente.guardar") }}',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                $('#modalAsistencia').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            } else {
                mostrarError(response.message);
                $('#btnGuardarAsistencia').prop('disabled', false).html('<i class="fas fa-save"></i> Guardar Asistencia');
            }
        },
        error: function(xhr) {
            mostrarError('Error al guardar la asistencia');
            $('#btnGuardarAsistencia').prop('disabled', false).html('<i class="fas fa-save"></i> Guardar Asistencia');
        }
    });
}

function verAsistencia(sesionClaseId) {
    window.location.href = '{{ route("asistencia.docente.ver", ":id") }}'.replace(':id', sesionClaseId);
}

// Funciones de calificaciones
function gestionarCalificaciones(evaluacionId) {
    // Redirigir al módulo de calificaciones
    window.location.href = '{{ route("notas.editar") }}?evaluacion_id=' + evaluacionId;
}

// Funciones de reportes
function exportarAsistenciaHoy() {
    // Implementar exportación de asistencias del día
    Swal.fire({
        title: 'Exportar Asistencias del Día',
        text: '¿Deseas exportar un reporte con todas las asistencias tomadas hoy?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, exportar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Implementar la lógica de exportación
            window.open('{{ route("asistencia.docente.exportar-pdf", "hoy") }}', '_blank');
        }
    });
}

function verEstadisticasSemanales() {
    // Mostrar estadísticas semanales en un modal
    Swal.fire({
        title: 'Estadísticas Semanales',
        html: `
            <div class="text-center">
                <div class="row">
                    <div class="col-6">
                        <h4 style="color: #28a745;">${{{ $estadisticas['asistencias_semana'] ?? 0 }}}</h4>
                        <small>Asistencias</small>
                    </div>
                    <div class="col-6">
                        <h4 style="color: #dc3545;">${{{ $estadisticas['inasistencias_semana'] ?? 0 }}}</h4>
                        <small>Inasistencias</small>
                    </div>
                </div>
                <hr>
                <p class="text-muted">Datos de la semana actual</p>
            </div>
        `,
        confirmButtonText: 'Cerrar'
    });
}

function exportarBoletines() {
    window.location.href = '{{ route("notas.consulta") }}';
}

function verAnalisisRendimiento() {
    // Implementar análisis de rendimiento
    Swal.fire({
        title: 'Análisis de Rendimiento',
        text: 'Funcionalidad en desarrollo. Pronto podrás ver análisis detallados del rendimiento de tus estudiantes.',
        icon: 'info'
    });
}

function generarReporteAsistencia(tipo) {
    let url = '{{ route("asistencia.docente.exportar-pdf", "hoy") }}';

    switch(tipo) {
        case 'semanal':
            url = '{{ route("asistencia.docente.exportar-pdf", "semana") }}';
            break;
        case 'mensual':
            url = '{{ route("asistencia.docente.exportar-pdf", "mes") }}';
            break;
        case 'por_curso':
            // Mostrar selector de curso
            Swal.fire({
                title: 'Seleccionar Curso',
                input: 'select',
                inputOptions: {
                    @foreach($cursos_docente as $curso)
                    '{{ $curso->id }}': '{{ $curso->grado->nombre }} {{ $curso->seccion->nombre }}',
                    @endforeach
                },
                inputPlaceholder: 'Selecciona un curso',
                showCancelButton: true,
                inputValidator: (value) => {
                    return new Promise((resolve) => {
                        if (value) {
                            resolve();
                        } else {
                            resolve('Debes seleccionar un curso');
                        }
                    });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.open('{{ route("asistencia.docente.exportar-pdf", "curso") }}?curso_id=' + result.value, '_blank');
                }
            });
            return;
    }

    window.open(url, '_blank');
}

function generarReporteCalificaciones(tipo) {
    switch(tipo) {
        case 'boletin':
            window.location.href = '{{ route("notas.consulta") }}';
            break;
        case 'rendimiento':
            window.open('{{ route("notas.consulta") }}?tipo=rendimiento', '_blank');
            break;
        case 'comparativo':
            window.open('{{ route("notas.consulta") }}?tipo=comparativo', '_blank');
            break;
    }
}

function descargarReporte(reporteId) {
    window.open('{{ route("asistencia.descargar-reporte-historial", ":id") }}'.replace(':id', reporteId), '_blank');
}

function mostrarError(mensaje) {
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: mensaje
    });
}

$(document).ready(function() {
    // Inicialización adicional si es necesaria
    console.log('Panel integrado del docente cargado correctamente');
});
</script>
@endpush
