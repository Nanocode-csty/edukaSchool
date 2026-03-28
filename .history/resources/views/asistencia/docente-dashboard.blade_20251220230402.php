@extends('cplantilla.bprincipal')
@section('titulo','Panel Integrado del Docente')
@section('contenidoplantilla')
<x-breadcrumb :module="'asistencia'" :section="'docente'" />
<div class="container-fluid" id="contenido-principal">
    <div class="row mt-4 ml-1 mr-1">
        <div class="col-12">
            <div class="box_block">
                <!-- Collapse header -->
                <button class="btn btn-block text-left rounded-0 btn_header header_6" type="button" data-toggle="collapse" data-target="#collapseDocenteDashboard" aria-expanded="true" aria-controls="collapseDocenteDashboard" style="background: #0A8CB3 !important; font-weight: bold; color: white;">
                    <i class="fas fa-chalkboard-teacher m-1"></i>&nbsp;Panel Integrado del Docente
                    <div class="float-right"><i class="fas fa-chevron-down"></i></div>
                </button>
                <!-- Descripción (siempre visible, dentro del bloque) -->
                <div class="card-body rounded-0 border-0 pt-3 pb-3" style="background: #f3f3f3; border-bottom: 1px solid rgba(0,0,0,.125); border-top: 1px solid rgba(0,0,0,.125); color: #F59D24;">
                    <div class="row justify-content-center align-items-center flex-wrap">
                        <div class="col-auto text-center mb-2 mb-md-0" style="min-width:48px;">
                            <i class="fas fa-exclamation-circle fa-2x"></i>
                        </div>
                        <div class="col px-2" style="text-align:justify;">
                            <p style="margin-bottom: 0px; font-family: 'Quicksand', sans-serif; font-weight: 600; color: #004a92;">
                                Gestiona tus asistencias y calificaciones desde un solo lugar. Toma asistencia de tus clases programadas y revisa el rendimiento de tus estudiantes.
                            </p>
                        </div>
                    </div>
                </div>
                <!-- Collapse: contenido del dashboard -->
                <div class="collapse show" id="collapseDocenteDashboard">
                    <div class="card card-body rounded-0 border-0 pt-0 pb-2" style="background: transparent;">

                        <!-- Estadísticas Rápidas -->
                        <div class="row mb-3" id="estadisticasContainer">
                            <div class="col-md-12">
                                <div class="stats-compact d-flex justify-content-between align-items-center p-3 rounded" style="background: #f8f9fa; border: 1px solid #dee2e6;">
                                    <div class="stat-item text-center">
                                        <div class="stat-value">
                                            <span class="badge badge-success stat-badge" id="totalClases">{{ $estadisticas['total_clases_hoy'] }}</span>
                                        </div>
                                        <small class="text-muted d-block">Clases Hoy</small>
                                    </div>
                                    <div class="stat-item text-center">
                                        <div class="stat-value">
                                            <span class="badge badge-primary stat-badge" id="asistenciasPendientes">{{ $estadisticas['asistencias_pendientes'] }}</span>
                                        </div>
                                        <small class="text-muted d-block">Pendientes</small>
                                    </div>
                                    <div class="stat-item text-center">
                                        <div class="stat-value">
                                            <span class="badge badge-info stat-badge" id="clasesCompletadas">{{ $estadisticas['clases_completadas'] }}</span>
                                        </div>
                                        <small class="text-muted d-block">Completadas</small>
                                    </div>
                                    <div class="stat-item text-center">
                                        <div class="stat-value">
                                            <span class="badge badge-warning stat-badge" id="totalEstudiantes">{{ $estadisticas['total_estudiantes'] }}</span>
                                        </div>
                                        <small class="text-muted d-block">Estudiantes</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Lista de clases de hoy -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <h5 class="mb-3"><i class="fas fa-calendar-day text-primary"></i> Clases de Hoy</h5>

                                <div class="table-responsive">
                                    <table id="add-row" class="table-hover table" style="border: 1px solid #0A8CB3; border-radius: 10px; overflow: hidden;">
                                        <thead class="text-center table-hover" style="background-color: #f8f9fa; color: #0A8CB3; border:#0A8CB3 !important">
                                            <tr>
                                                <th scope="col">Asignatura</th>
                                                <th scope="col">Curso</th>
                                                <th scope="col">Horario</th>
                                                <th scope="col">Aula</th>
                                                <th scope="col">Estado</th>
                                                <th scope="col">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbodyClases">
                                            @forelse($clases_hoy as $clase)
                                            <tr>
                                                <td>{{ $clase->cursoAsignatura->asignatura->nombre }}</td>
                                                <td>{{ $clase->cursoAsignatura->curso->grado->nombre }} {{ $clase->cursoAsignatura->curso->seccion->nombre }}</td>
                                                <td>{{ $clase->hora_inicio }} - {{ $clase->hora_fin }}</td>
                                                <td>{{ $clase->aula->nombre ?? 'Sin asignar' }}</td>
                                                <td>
                                                    @if($clase->tiene_asistencia_hoy)
                                                        <span class="badge badge-success">Completada</span>
                                                    @else
                                                        <span class="badge badge-warning">Pendiente</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if(!$clase->tiene_asistencia_hoy)
                                                        <button class="btn btn-primary btn-sm" onclick="tomarAsistencia({{ $clase->sesion_id }})">
                                                            <i class="fas fa-clipboard-check"></i> Tomar
                                                        </button>
                                                    @else
                                                        <a href="{{ route('asistencia.docente.ver', $clase->sesion_id) }}" class="btn btn-info btn-sm">
                                                            <i class="fas fa-eye"></i> Ver
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="6" class="text-center">
                                                    <i class="fas fa-calendar-times text-muted fa-2x mb-2"></i>
                                                    <br>No tienes clases programadas para hoy
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Acciones Rápidas -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2 flex-wrap">
                                    <button class="btn btn-outline-success btn-sm" onclick="exportarAsistenciaHoy()">
                                        <i class="fas fa-download mr-1"></i>Exportar Asistencias del Día
                                    </button>
                                    <button class="btn btn-outline-info btn-sm" onclick="verEstadisticasSemanales()">
                                        <i class="fas fa-chart-line mr-1"></i>Ver Estadísticas
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para tomar asistencia -->
<div class="modal fade" id="modalAsistencia" tabindex="-1" role="dialog" aria-labelledby="modalAsistenciaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalAsistenciaLabel">
                    <i class="fas fa-clipboard-check mr-2"></i>Tomar Asistencia
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="modalContent">
                    <!-- Contenido dinámico del modal -->
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Cargando...</span>
                        </div>
                        <p class="mt-2">Cargando estudiantes...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Cancelar
                </button>
                <button type="button" class="btn btn-success" id="btnGuardarAsistencia" onclick="guardarAsistencia()">
                    <i class="fas fa-save"></i> Guardar Asistencia
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
// Variables globales
let estudiantesData = [];

// Funciones de asistencia
function tomarAsistencia(sesionClaseId) {
    // Mostrar loading en el modal
    $('#modalContent').html(`
        <div class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Cargando...</span>
            </div>
            <p class="mt-2">Cargando estudiantes...</p>
        </div>
    `);

    $('#modalAsistencia').modal('show');

    $.ajax({
        url: '{{ route("asistencia.docente.obtener-estudiantes") }}',
        method: 'GET',
        data: { sesion_clase_id: sesionClaseId },
        success: function(response) {
            if (response.success) {
                estudiantesData = response.data.estudiantes;
                renderizarModalAsistencia(sesionClaseId, response.data);
            } else {
                mostrarError(response.message);
            }
        },
        error: function(xhr) {
            mostrarError('Error al cargar los estudiantes');
        }
    });
}

function renderizarModalAsistencia(sesionClaseId, data) {
    let html = `
        <form id="formAsistencia">
            @csrf
            <input type="hidden" id="sesion_clase_id" name="sesion_clase_id" value="${sesionClaseId}">

            <div class="form-group mb-3">
                <label><strong>Fecha de la clase:</strong></label>
                <input type="date" class="form-control" id="fecha_clase" name="fecha_clase" value="${new Date().toISOString().split('T')[0]}" readonly>
            </div>

            <div class="form-group mb-3">
                <label><strong>Clase:</strong></label>
                <p class="form-control-plaintext">${data.clase_info}</p>
            </div>

            <div class="table-responsive">
                <table class="table table-sm table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Estudiante</th>
                            <th>DNI</th>
                            <th>Asistencia</th>
                            <th>Observaciones</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyEstudiantes">
    `;

    estudiantesData.forEach(function(estudiante, index) {
        html += `
            <tr>
                <td>${index + 1}</td>
                <td>
                    <strong>${estudiante.nombres} ${estudiante.apellidos}</strong>
                </td>
                <td>${estudiante.dni}</td>
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
@endsection
        <div class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Cargando...</span>
            </div>
            <p class="mt-2">Cargando estudiantes...</p>
        </div>
    `);

    $('#modalAsistencia').modal('show');

    $.ajax({
        url: '{{ route("asistencia.docente.obtener-estudiantes") }}',
        method: 'GET',
        data: { sesion_clase_id: sesionClaseId },
        success: function(response) {
            if (response.success) {
                estudiantesData = response.data.estudiantes;
                renderizarModalAsistencia(sesionClaseId, response.data);
            } else {
                mostrarError(response.message);
            }
        },
        error: function(xhr) {
            mostrarError('Error al cargar los estudiantes');
        }
    });
}

function renderizarModalAsistencia(sesionClaseId, data) {
    let html = `
        <form id="formAsistencia">
            @csrf
            <input type="hidden" id="sesion_clase_id" name="sesion_clase_id" value="${sesionClaseId}">

            <div class="form-group mb-3">
                <label><strong>Fecha de la clase:</strong></label>
                <input type="date" class="form-control" id="fecha_clase" name="fecha_clase" value="${new Date().toISOString().split('T')[0]}" readonly>
            </div>

            <div class="form-group mb-3">
                <label><strong>Clase:</strong></label>
                <p class="form-control-plaintext">${data.clase_info}</p>
            </div>

            <div class="table-responsive">
                <table class="table table-sm table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Estudiante</th>
                            <th>DNI</th>
                            <th>Asistencia</th>
                            <th>Observaciones</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyEstudiantes">
    `;

    estudiantesData.forEach(function(estudiante, index) {
        html += `
            <tr>
                <td>${index + 1}</td>
                <td>
                    <strong>${estudiante.nombres} ${estudiante.apellidos}</strong>
                </td>
                <td>${estudiante.dni}</td>
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
@endsection
