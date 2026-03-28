@extends('cplantilla.bprincipal')

@section('titulo', 'Panel de Asistencias - Docente')

@section('contenidoplantilla')
<x-breadcrumb :module="'asistencia'" :section="'docente'" />
<div class="container-fluid" id="contenido-principal">
    <div class="row mt-4 ml-1 mr-1">
        <div class="col-12">
            <div class="box_block">
                <!-- Collapse header -->
                <button class="btn btn-block text-left rounded-0 btn_header header_6" type="button" data-toggle="collapse" data-target="#collapseTablaClases" aria-expanded="true" aria-controls="collapseTablaClases" style="background: #0A8CB3 !important; font-weight: bold; color: white;">
                    <i class="fas fa-chalkboard-teacher m-1"></i>&nbsp;Mis Clases - Control de Asistencias
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
                                En esta sección puedes ver todas tus clases programadas para el día de hoy y gestionar la asistencia de tus estudiantes. Utiliza los botones para tomar asistencia o ver registros ya completados.
                            </p>
                            <p style="font-family: 'Quicksand', sans-serif; font-weight: 600; color: #004a92;">
                                Recuerda que la asistencia es fundamental para el seguimiento académico. Asegúrate de registrar correctamente la asistencia de cada estudiante en cada una de tus clases.
                            </p>
                        </div>
                    </div>
                </div>
                <!-- Collapse: estadísticas y tabla -->
                <div class="collapse show" id="collapseTablaClases">
                    <div class="card card-body rounded-0 border-0 pt-0 pb-2" style="background: transparent;">
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
                            <table id="add-row" class="table-hover table" style="border: 1px solid #0A8CB3; border-radius: 10px; overflow: hidden;">
                                <thead class="text-center table-hover" style="background-color: #f8f9fa; color: #0A8CB3; border:#0A8CB3 !important">
                                    <tr>
                                        <th scope="col">Curso</th>
                                        <th scope="col">Asignatura</th>
                                        <th scope="col">Horario</th>
                                        <th scope="col">Aula</th>
                                        <th scope="col">Estado Asistencia</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($clases_hoy as $clase)
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
                                                <button class="btn btn-primary btn-sm" onclick="tomarAsistencia({{ $clase->sesion_id }})">
                                                    <i class="fas fa-clipboard-check"></i> Tomar Asistencia
                                                </button>
                                            @else
                                                <button class="btn btn-info btn-sm" onclick="verAsistencia({{ $clase->sesion_id }})">
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
                <script>
                document.addEventListener('DOMContentLoaded', function () {
                    // Collapse icon toggle
                    const btn = document.querySelector('[data-target="#collapseTablaClases"]');
                    const icon = btn.querySelector('.fas.fa-chevron-down');
                    const collapse = document.getElementById('collapseTablaClases');
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
            </div>
        </div>
    </div>
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
    </style>

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
