@extends('cplantilla.bprincipal')

@section('titulo', 'Panel de Asistencias - Docente')

@section('contenidoplantilla')
@php
    $module = 'asistencia';
    $section = 'docente';
@endphp
<x-breadcrumb />

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">Mis Clases - Control de Asistencias</h4>
                    <button class="btn btn-primary btn-round ml-auto" onclick="mostrarEstadisticas()">
                        <i class="fa fa-chart-bar"></i>
                        Estadísticas
                    </button>
                </div>
            </div>
            <div class="card-body">
                <!-- Estadísticas rápidas -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="text-center p-3" style="background-color: #f8f9fa; border-radius: 8px; border-left: 4px solid #28a745;">
                            <h5 style="color: #28a745; margin-bottom: 5px;">{{ $estadisticas['total_clases_hoy'] ?? 0 }}</h5>
                            <small style="color: #6c757d;">Clases Hoy</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3" style="background-color: #f8f9fa; border-radius: 8px; border-left: 4px solid #007bff;">
                            <h5 style="color: #007bff; margin-bottom: 5px;">{{ $estadisticas['total_estudiantes'] ?? 0 }}</h5>
                            <small style="color: #6c757d;">Estudiantes Total</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3" style="background-color: #f8f9fa; border-radius: 8px; border-left: 4px solid #ffc107;">
                            <h5 style="color: #ffc107; margin-bottom: 5px;">{{ $estadisticas['asistencias_pendientes'] ?? 0 }}</h5>
                            <small style="color: #6c757d;">Asistencias Pendientes</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3" style="background-color: #f8f9fa; border-radius: 8px; border-left: 4px solid #dc3545;">
                            <h5 style="color: #dc3545; margin-bottom: 5px;">{{ $estadisticas['inasistencias_hoy'] ?? 0 }}</h5>
                            <small style="color: #6c757d;">Inasistencias Hoy</small>
                        </div>
                    </div>
                </div>

                <!-- Lista de clases -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="tablaClases">
                        <thead class="thead-dark">
                            <tr>
                                <th>Curso</th>
                                <th>Asignatura</th>
                                <th>Horario</th>
                                <th>Aula</th>
                                <th>Estado Asistencia</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyClases">
                            @forelse($clases as $clase)
                            <tr>
                                <td>{{ $clase->cursoAsignatura->curso->grado->nombre }} {{ $clase->cursoAsignatura->curso->seccion->nombre }}</td>
                                <td>{{ $clase->cursoAsignatura->asignatura->nombre }}</td>
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
                                        <button class="btn btn-primary btn-sm" onclick="tomarAsistencia({{ $clase->id }})">
                                            <i class="fas fa-clipboard-check"></i> Tomar Asistencia
                                        </button>
                                    @else
                                        <button class="btn btn-info btn-sm" onclick="verAsistencia({{ $clase->id }})">
                                            <i class="fas fa-eye"></i> Ver Asistencia
                                        </button>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No tiene clases asignadas para hoy</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para tomar asistencia -->
<div class="modal fade" id="modalAsistencia" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tomar Asistencia - <span id="claseInfo"></span></h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formAsistencia">
                    @csrf
                    <input type="hidden" id="sesion_clase_id" name="sesion_clase_id">

                    <div class="form-group">
                        <label>Fecha de la clase:</label>
                        <input type="date" class="form-control" id="fecha_clase" readonly>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Estudiante</th>
                                    <th>DNI</th>
                                    <th>Asistencia</th>
                                    <th>Observaciones</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyEstudiantes">
                                <!-- Los estudiantes se cargarán aquí -->
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" onclick="guardarAsistencia()">
                    <i class="fas fa-save"></i> Guardar Asistencia
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
let estudiantesData = [];

function tomarAsistencia(sesionClaseId) {
    $.ajax({
        url: '{{ route("asistencia.docente.obtener-estudiantes") }}',
        method: 'GET',
        data: { sesion_clase_id: sesionClaseId },
        success: function(response) {
            if (response.success) {
                estudiantesData = response.data.estudiantes;
                $('#sesion_clase_id').val(sesionClaseId);
                $('#fecha_clase').val(new Date().toISOString().split('T')[0]);
                $('#claseInfo').text(response.data.clase_info);

                renderizarEstudiantes();
                $('#modalAsistencia').modal('show');
            } else {
                mostrarError(response.message);
            }
        },
        error: function(xhr) {
            mostrarError('Error al cargar los estudiantes');
        }
    });
}

function renderizarEstudiantes() {
    let html = '';

    estudiantesData.forEach(function(estudiante, index) {
        html += `
            <tr>
                <td>${estudiante.nombres} ${estudiante.apellidos}</td>
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

    $('#tbodyEstudiantes').html(html);
}

function guardarAsistencia() {
    const formData = new FormData(document.getElementById('formAsistencia'));

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
            }
        },
        error: function(xhr) {
            mostrarError('Error al guardar la asistencia');
        }
    });
}

function verAsistencia(sesionClaseId) {
    // Implementar vista de asistencia ya tomada
    window.location.href = '{{ route("asistencia.docente.ver", ":id") }}'.replace(':id', sesionClaseId);
}

function mostrarEstadisticas() {
    // Implementar modal con estadísticas detalladas
    Swal.fire({
        title: 'Estadísticas del Mes',
        html: `
            <div class="text-center">
                <div class="row">
                    <div class="col-6">
                        <h4 style="color: #28a745;">${{{ $estadisticas['total_asistencias_mes'] ?? 0 }}}</h4>
                        <small>Asistencias</small>
                    </div>
                    <div class="col-6">
                        <h4 style="color: #dc3545;">${{{ $estadisticas['total_inasistencias_mes'] ?? 0 }}}</h4>
                        <small>Inasistencias</small>
                    </div>
                </div>
            </div>
        `,
        confirmButtonText: 'Cerrar'
    });
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
});
</script>
@endsection
