@extends('cplantilla.bprincipal')
@section('titulo','Tomar Asistencia - Docente')
@section('contenidoplantilla')
<x-breadcrumb :module="'asistencia'" :section="'docente'" />
<div class="container-fluid" id="contenido-principal">
    <div class="row mt-4 ml-1 mr-1">
        <div class="col-12">
            <div class="box_block">
                <!-- Collapse header -->
                <button class="btn btn-block text-left rounded-0 btn_header header_6" type="button" data-toggle="collapse" data-target="#collapseTomarAsistencia" aria-expanded="true" aria-controls="collapseTomarAsistencia" style="background: #007bff !important; font-weight: bold; color: white;">
                    <i class="fas fa-clipboard-check m-1"></i>&nbsp;Tomar Asistencia - {{ date('d/m/Y') }}
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
                                Registra la asistencia de tus estudiantes para las clases programadas hoy. Haz clic en "Tomar" para cada sesión y marca la asistencia de cada estudiante.
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
                            <!-- Clases de Hoy -->
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <h5 class="mb-3"><i class="fas fa-clock"></i> Clases Programadas para Hoy</h5>

                                    @if($clases_hoy->count() > 0)
                                        <div class="row">
                                            @foreach($clases_hoy as $clase)
                                            <div class="col-md-6 mb-3">
                                                <div class="card {{ $clase->tiene_asistencia_hoy ? 'border-success' : 'border-warning' }}">
                                                    <div class="card-header {{ $clase->tiene_asistencia_hoy ? 'bg-success text-white' : 'bg-warning text-dark' }}">
                                                        <h6 class="mb-0">
                                                            <i class="fas fa-chalkboard-teacher"></i>
                                                            {{ $clase->cursoAsignatura->asignatura->nombre }}
                                                        </h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <p class="mb-1"><strong>Curso:</strong></p>
                                                                <p class="mb-1">{{ $clase->cursoAsignatura->curso->grado->nombre }} {{ $clase->cursoAsignatura->curso->seccion->nombre }}</p>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <p class="mb-1"><strong>Hora:</strong></p>
                                                                <p class="mb-1">{{ $clase->hora_inicio }} - {{ $clase->hora_fin }}</p>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <p class="mb-1"><strong>Aula:</strong></p>
                                                                <p class="mb-1">{{ $clase->aula ? $clase->aula->nombre : 'Sin asignar' }}</p>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <p class="mb-1"><strong>Estado:</strong></p>
                                                                <p class="mb-1">
                                                                    @if($clase->tiene_asistencia_hoy)
                                                                        <span class="badge badge-success">Completada</span>
                                                                    @else
                                                                        <span class="badge badge-warning">Pendiente</span>
                                                                    @endif
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="mt-3">
                                                            @if($clase->tiene_asistencia_hoy)
                                                                <button class="btn btn-success btn-block" onclick="verAsistencia({{ $clase->sesion_id }})">
                                                                    <i class="fas fa-eye"></i> Ver Asistencia
                                                                </button>
                                                            @else
                                                                <button class="btn btn-primary btn-block" onclick="tomarAsistencia({{ $clase->sesion_id }})">
                                                                    <i class="fas fa-clipboard-check"></i> Tomar Asistencia
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-center py-5">
                                            <i class="fas fa-calendar-times text-muted fa-3x mb-3"></i>
                                            <h5 class="text-muted">No hay clases programadas para hoy</h5>
                                            <p class="text-muted">No tienes sesiones de clase programadas para el día de hoy.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Acciones Rápidas -->
                            <div class="row">
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

<!-- Modal para tomar asistencia -->
<div class="modal fade" id="modalAsistencia" tabindex="-1" role="dialog" aria-labelledby="modalAsistenciaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAsistenciaLabel">Tomar Asistencia</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="modalContent">
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Cargando...</span>
                        </div>
                        <p>Cargando estudiantes...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardarAsistencia()">Guardar Asistencia</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js-extra')
<script>
let estudiantesData = [];
let sesionClaseId = null;

document.addEventListener('DOMContentLoaded', function () {
    // Collapse icon toggle
    const btn = document.querySelector('[data-target="#collapseTomarAsistencia"]');
    const icon = btn.querySelector('.fas.fa-chevron-down');
    const collapse = document.getElementById('collapseTomarAsistencia');
    collapse.addEventListener('show.bs.collapse', function () {
        icon.classList.remove('fa-chevron-down');
        icon.classList.add('fa-chevron-up');
    });
    collapse.addEventListener('hide.bs.collapse', function () {
        icon.classList.remove('fa-chevron-up');
        icon.classList.add('fa-chevron-down');
    });
});

function tomarAsistencia(sesionClaseIdParam) {
    sesionClaseId = sesionClaseIdParam;

    // Mostrar loading en el modal
    $('#modalContent').html(`
        <div class="text-center">
            <div class="spinner-border" role="status">
                <span class="sr-only">Cargando...</span>
            </div>
            <p>Cargando estudiantes...</p>
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
        <div class="mb-3">
            <h6>Estudiantes del curso</h6>
            <small class="text-muted">Marca la asistencia de cada estudiante</small>
        </div>
        <form id="formAsistencia">
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Estudiante</th>
                            <th class="text-center">Presente</th>
                            <th class="text-center">Ausente</th>
                            <th class="text-center">Tarde</th>
                            <th class="text-center">Justificado</th>
                            <th>Observaciones</th>
                        </tr>
                    </thead>
                    <tbody>
    `;

    estudiantesData.forEach(function(estudiante, index) {
        html += `
            <tr>
                <td>
                    <strong>${estudiante.nombres} ${estudiante.apellidos}</strong>
                    <br><small class="text-muted">DNI: ${estudiante.dni}</small>
                </td>
                <td class="text-center">
                    <input type="radio" name="asistencia_${estudiante.matricula_id}" value="P" checked>
                </td>
                <td class="text-center">
                    <input type="radio" name="asistencia_${estudiante.matricula_id}" value="A">
                </td>
                <td class="text-center">
                    <input type="radio" name="asistencia_${estudiante.matricula_id}" value="T">
                </td>
                <td class="text-center">
                    <input type="radio" name="asistencia_${estudiante.matricula_id}" value="J">
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm" name="observaciones_${estudiante.matricula_id}" placeholder="Observaciones">
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
    const asistencias = [];

    estudiantesData.forEach(function(estudiante) {
        const tipoAsistencia = $(`input[name="asistencia_${estudiante.matricula_id}"]:checked`).val();
        const observaciones = $(`input[name="observaciones_${estudiante.matricula_id}"]`).val();

        asistencias.push({
            matricula_id: estudiante.matricula_id,
            tipo_asistencia: tipoAsistencia,
            observaciones: observaciones || null
        });
    });

    // Mostrar loading
    const saveBtn = $('#modalAsistencia .btn-primary');
    const originalText = saveBtn.html();
    saveBtn.html('<i class="fas fa-spinner fa-spin"></i> Guardando...').prop('disabled', true);

    $.ajax({
        url: '{{ route("asistencia.docente.guardar") }}',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            sesion_clase_id: sesionClaseId,
            asistencias: asistencias
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
        },
        complete: function() {
            saveBtn.html(originalText).prop('disabled', false);
        }
    });
}

function verAsistencia(sesionId) {
    window.location.href = '{{ route("asistencia.docente.ver", ":id") }}'.replace(':id', sesionId);
}

function mostrarError(mensaje) {
    $('#modalContent').html(`
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle"></i> ${mensaje}
        </div>
    `);
}
</script>
@endpush