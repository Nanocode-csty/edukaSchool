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
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Collapse icon toggle
    const btn = document.querySelector('[data-target="#collapseDocenteDashboard"]');
    const icon = btn.querySelector('.fas.fa-chevron-down');
    const collapse = document.getElementById('collapseDocenteDashboard');
    collapse.addEventListener('show.bs.collapse', function () {
        icon.classList.remove('fa-chevron-down');
        icon.classList.add('fa-chevron-up');
    });
    collapse.addEventListener('hide.bs.collapse', function () {
        icon.classList.remove('fa-chevron-up');
        icon.classList.add('fa-chevron-down');
    });
});
</script>

<style>
/* Animación de entrada */
@keyframes slideInLeft {
    from { opacity: 0; transform: translateX(-50px);}
    to { opacity: 1; transform: translateX(0);}
}
.animate-slide-in { animation: slideInLeft 0.8s ease-out; }

/* Tabla y paginación */
#add-row td, #add-row th {
    padding: 4px 8px;
    font-size: 14px;
    vertical-align: middle;
    height: 52px;
}
.table-hover tbody tr:hover {
    background-color: #FFF4E7 !important;
}
.badge-success {
    background-color: #28a745;
    color: #fff;
}
.badge-secondary {
    background-color: #6c757d;
    color: #fff;
}
/* Paginación */
.pagination {
    display: flex;
    justify-content: left;
    padding: 1rem 0;
    list-style: none;
    gap: 0.3rem;
}
.pagination li a, .pagination li span {
    color: #0A8CB3;
    border: 1px solid #0A8CB3;
    padding: 6px 12px;
    border-radius: 4px;
    text-decoration: none;
    transition: all 0.2s ease;
    font-size: 0.9rem;
}
.pagination li a:hover, .pagination li span:hover {
    background-color: #f1f1f1;
    color: #333;
}
.pagination .page-item.active .page-link {
    background-color: #0A8CB3 !important;
    color: white !important;
    border-color: #0A8CB3 !important;
}
.pagination .disabled .page-link {
    color: #ccc;
    border-color: #ccc;
}
/* Botón header estilo estudiantes */
.btn_header.header_6 {
    margin-bottom: 0;
    border-radius: 0;
    font-size: 1.1rem;
    padding: 0.75rem 1rem;
    background: #0A8CB3 !important;
    color: white;
    border: none;
    box-shadow: none;
}
.btn_header .float-right {
    float: right;
}
.btn_header i.fas.fa-chevron-down,
.btn_header i.fas.fa-chevron-up {
    transition: transform 0.2s;
}

/* Estadísticas Compactas */
.stats-compact {
    border-radius: 8px !important;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
}

.stat-item {
    flex: 1;
    padding: 10px;
}

.stat-badge {
    font-size: 1.2rem !important;
    padding: 8px 12px !important;
    font-weight: bold !important;
    border-radius: 6px !important;
    display: inline-block;
    min-width: 60px;
    text-align: center;
}

/* Filtros */
.filtro-input {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    transition: border-color 0.2s ease;
}

.filtro-input:focus {
    border-color: #0A8CB3;
    box-shadow: 0 0 0 0.2rem rgba(10, 139, 179, 0.25);
}

/* Select2 customizations */
.select2-container--bootstrap-5 .select2-selection {
    border: 2px solid #e9ecef;
    border-radius: 8px;
}

.select2-container--bootstrap-5 .select2-selection:focus {
    border-color: #0A8CB3;
    box-shadow: 0 0 0 0.2rem rgba(10, 139, 179, 0.25);
}
</style>
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
