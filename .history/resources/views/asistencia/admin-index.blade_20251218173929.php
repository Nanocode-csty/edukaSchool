@extends('cplantilla.bprincipal')
@section('titulo','Administrar Asistencias')
@section('contenidoplantilla')
<div class="container-fluid" id="contenido-principal">
    <div class="row mt-4 ml-1 mr-1">
        <div class="col-12">
            <div class="box_block">
                <!-- Collapse header -->
                <button class="btn btn-block text-left rounded-0 btn_header header_6" type="button" data-toggle="collapse" data-target="#collapseTablaAsistencias" aria-expanded="true" aria-controls="collapseTablaAsistencias" style="background: #0A8CB3 !important; font-weight: bold; color: white;">
                    <i class="fas fa-calendar-check m-1"></i>&nbsp;Administrar Asistencias
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
                                En esta sección puedes consultar y gestionar todas las asistencias registradas en el sistema. Utiliza los filtros para buscar por fecha, curso o sección específica.
                            </p>
                            <p style="font-family: 'Quicksand', sans-serif; font-weight: 600; color: #004a92;">
                                Recuerda que la información de asistencia es fundamental para el seguimiento académico de los estudiantes. Si detectas algún error o necesitas hacer cambios, comunícate con el docente correspondiente.
                            </p>
                        </div>
                    </div>
                </div>
                <!-- Collapse: solo la tabla y filtros -->
                <div class="collapse show" id="collapseTablaAsistencias">
                    <div class="card card-body rounded-0 border-0 pt-0 pb-2" style="background: transparent;">
            <div class="card-body">
                <!-- Filtros -->
                <div class="row mb-3" id="filtrosContainer">
                    <div class="col-md-3">
                        <label>Fecha Inicio</label>
                        <input type="date" class="form-control" id="fecha_inicio" value="{{ date('Y-m-d', strtotime('-30 days')) }}">
                    </div>
                    <div class="col-md-3">
                        <label>Fecha Fin</label>
                        <input type="date" class="form-control" id="fecha_fin" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-3">
                        <label>Curso</label>
                        <select class="form-control" id="curso_id">
                            <option value="">Todos los cursos</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Sección</label>
                        <select class="form-control" id="seccion_id">
                            <option value="">Todas las secciones</option>
                        </select>
                    </div>
                </div>

                <!-- Tabla de asistencias -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="tablaAsistencias">
                        <thead class="thead-dark">
                            <tr>
                                <th>Fecha</th>
                                <th>Estudiante</th>
                                <th>Curso</th>
                                <th>Sección</th>
                                <th>Tipo Asistencia</th>
                                <th>Justificado</th>
                                <th>Observaciones</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyAsistencias">
                            <!-- Los datos se cargarán vía AJAX -->
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div id="paginacionContainer" class="d-flex justify-content-center mt-3">
                    <!-- La paginación se generará dinámicamente -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Filtros -->
<div class="modal fade" id="modalFiltros" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Filtros Avanzados</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <label>Tipo de Asistencia</label>
                        <select class="form-control" id="tipo_asistencia">
                            <option value="">Todos los tipos</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>Estado Justificación</label>
                        <select class="form-control" id="justificado">
                            <option value="">Todos</option>
                            <option value="1">Justificado</option>
                            <option value="0">No justificado</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="aplicarFiltros()">Aplicar Filtros</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    cargarDatos();

    // Event listeners para filtros
    $('#fecha_inicio, #fecha_fin, #curso_id, #seccion_id').on('change', function() {
        cargarDatos();
    });
});

function cargarDatos(pagina = 1) {
    const filtros = {
        fecha_inicio: $('#fecha_inicio').val(),
        fecha_fin: $('#fecha_fin').val(),
        curso_id: $('#curso_id').val(),
        seccion_id: $('#seccion_id').val(),
        tipo_asistencia: $('#tipo_asistencia').val(),
        justificado: $('#justificado').val(),
        page: pagina
    };

    $.ajax({
        url: '{{ route("asistencia.api.tabla-asistencias") }}',
        method: 'GET',
        data: filtros,
        success: function(response) {
            if (response.success) {
                renderizarTabla(response.data);
                renderizarPaginacion(response.data);
            } else {
                mostrarError(response.message);
            }
        },
        error: function(xhr) {
            mostrarError('Error al cargar los datos');
        }
    });
}

function renderizarTabla(data) {
    let html = '';

    if (data.data && data.data.length > 0) {
        data.data.forEach(function(asistencia) {
            html += `
                <tr>
                    <td>${formatearFecha(asistencia.fecha)}</td>
                    <td>${asistencia.matricula?.estudiante?.nombres || ''} ${asistencia.matricula?.estudiante?.apellidos || ''}</td>
                    <td>${asistencia.matricula?.curso?.nombre || ''}</td>
                    <td>${asistencia.matricula?.seccion?.nombre || ''}</td>
                    <td>
                        <span class="badge badge-${getBadgeClass(asistencia.tipo_asistencia?.codigo || '')}">
                            ${asistencia.tipo_asistencia?.nombre || ''}
                        </span>
                    </td>
                    <td>
                        ${asistencia.justificado ?
                            '<i class="fas fa-check text-success"></i>' :
                            '<i class="fas fa-times text-danger"></i>'}
                    </td>
                    <td>${asistencia.observaciones || ''}</td>
                </tr>
            `;
        });
    } else {
        html = '<tr><td colspan="7" class="text-center">No se encontraron registros</td></tr>';
    }

    $('#tbodyAsistencias').html(html);
}

function renderizarPaginacion(data) {
    let html = '';

    if (data.last_page > 1) {
        html += '<nav><ul class="pagination">';

        // Anterior
        if (data.current_page > 1) {
            html += `<li class="page-item"><a class="page-link" href="#" onclick="cargarDatos(${data.current_page - 1})">Anterior</a></li>`;
        }

        // Páginas
        for (let i = Math.max(1, data.current_page - 2); i <= Math.min(data.last_page, data.current_page + 2); i++) {
            html += `<li class="page-item ${i === data.current_page ? 'active' : ''}">
                <a class="page-link" href="#" onclick="cargarDatos(${i})">${i}</a>
            </li>`;
        }

        // Siguiente
        if (data.current_page < data.last_page) {
            html += `<li class="page-item"><a class="page-link" href="#" onclick="cargarDatos(${data.current_page + 1})">Siguiente</a></li>`;
        }

        html += '</ul></nav>';
    }

    $('#paginacionContainer').html(html);
}

function aplicarFiltros() {
    $('#modalFiltros').modal('hide');
    cargarDatos();
}

function formatearFecha(fecha) {
    return new Date(fecha).toLocaleDateString('es-ES');
}

function getBadgeClass(codigo) {
    switch(codigo) {
        case 'P': return 'success';
        case 'A': return 'danger';
        case 'T': return 'warning';
        case 'J': return 'info';
        default: return 'secondary';
    }
}

function mostrarError(mensaje) {
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: mensaje
    });
}
</script>
@endsection
