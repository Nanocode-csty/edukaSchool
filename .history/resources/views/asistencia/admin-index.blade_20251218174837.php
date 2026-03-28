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
                <!-- Collapse: filtros, estadísticas y tabla -->
                <div class="collapse show" id="collapseTablaAsistencias">
                    <div class="card card-body rounded-0 border-0 pt-0 pb-2" style="background: transparent;">
                        <!-- Filtros Rápidos -->
                        <div class="row mb-3 align-items-end" id="filtrosContainer">
                            <div class="col-md-2">
                                <label class="form-label fw-bold text-primary">Fecha Inicio</label>
                                <input type="date" class="form-control filtro-input" id="fecha_inicio" value="{{ date('Y-m-d', strtotime('-30 days')) }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-bold text-primary">Fecha Fin</label>
                                <input type="date" class="form-control filtro-input" id="fecha_fin" value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-bold text-primary">Tipo Asistencia</label>
                                <select class="form-control filtro-input select2-basic" id="tipo_asistencia">
                                    <option value="">Todos los tipos</option>
                                    <option value="P">Presente</option>
                                    <option value="A">Ausente</option>
                                    <option value="T">Tarde</option>
                                    <option value="J">Justificado</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-bold text-primary">Estado</label>
                                <select class="form-control filtro-input select2-basic" id="justificado">
                                    <option value="">Todos</option>
                                    <option value="1">Justificado</option>
                                    <option value="0">No justificado</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-outline-primary w-100" id="btnFiltrosAvanzados" data-toggle="modal" data-target="#modalFiltrosAvanzados">
                                    <i class="fas fa-sliders-h"></i> Filtros Avanzados
                                </button>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-success w-100" id="btnAplicarFiltros">
                                    <i class="fas fa-search"></i> Aplicar Filtros
                                </button>
                            </div>
                        </div>

                        <!-- Estadísticas Elegantes -->
                        <div class="row mb-4" id="estadisticasContainer">
                            <div class="col-md-12">
                                <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                                    <div class="card-body">
                                        <div class="row text-center">
                                            <div class="col-md-3">
                                                <div class="stat-card">
                                                    <div class="stat-icon">
                                                        <i class="fas fa-calendar-check text-success"></i>
                                                    </div>
                                                    <div class="stat-content">
                                                        <h4 class="stat-number text-success" id="totalRegistros">0</h4>
                                                        <p class="stat-label mb-0">Total Registros</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="stat-card">
                                                    <div class="stat-icon">
                                                        <i class="fas fa-user-check text-primary"></i>
                                                    </div>
                                                    <div class="stat-content">
                                                        <h4 class="stat-number text-primary" id="totalPresentes">0</h4>
                                                        <p class="stat-label mb-0">Presentes</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="stat-card">
                                                    <div class="stat-icon">
                                                        <i class="fas fa-user-times text-danger"></i>
                                                    </div>
                                                    <div class="stat-content">
                                                        <h4 class="stat-number text-danger" id="totalAusentes">0</h4>
                                                        <p class="stat-label mb-0">Ausentes</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="stat-card">
                                                    <div class="stat-icon">
                                                        <i class="fas fa-percentage text-info"></i>
                                                    </div>
                                                    <div class="stat-content">
                                                        <h4 class="stat-number text-info" id="porcentajeAsistencia">0%</h4>
                                                        <p class="stat-label mb-0">% Asistencia</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tabla de asistencias -->
                        <div class="table-responsive">
                            <table id="add-row" class="table-hover table" style="border: 1px solid #0A8CB3; border-radius: 10px; overflow: hidden;">
                                <thead class="text-center table-hover" style="background-color: #f8f9fa; color: #0A8CB3; border:#0A8CB3 !important">
                                    <tr>
                                        <th scope="col">Fecha</th>
                                        <th scope="col">Estudiante</th>
                                        <th scope="col">Curso</th>
                                        <th scope="col">Sección</th>
                                        <th scope="col">Tipo Asistencia</th>
                                        <th scope="col">Justificado</th>
                                        <th scope="col">Observaciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyAsistencias">
                                    <!-- Los datos se cargarán vía AJAX -->
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginación -->
                        <div id="paginacionContainer" class="mt-3">
                            <!-- La paginación se generará dinámicamente -->
                        </div>
                    </div>
                </div>
                <script>
                document.addEventListener('DOMContentLoaded', function () {
                    // Collapse icon toggle
                    const btn = document.querySelector('[data-target="#collapseTablaAsistencias"]');
                    const icon = btn.querySelector('.fas.fa-chevron-down');
                    const collapse = document.getElementById('collapseTablaAsistencias');
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

<!-- Modal de Filtros Avanzados -->
<div class="modal fade" id="modalFiltrosAvanzados" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #0A8CB3 0%, #28aece 100%); color: white;">
                <h5 class="modal-title">
                    <i class="fas fa-sliders-h"></i> Filtros Avanzados de Asistencia
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label fw-bold text-primary">
                                <i class="fas fa-school"></i> Nivel Educativo
                            </label>
                            <select class="form-control select2-advanced" id="nivel_id">
                                <option value="">Todos los niveles</option>
                                <!-- Se cargarán dinámicamente -->
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label fw-bold text-primary">
                                <i class="fas fa-graduation-cap"></i> Grado
                            </label>
                            <select class="form-control select2-advanced" id="grado_id">
                                <option value="">Todos los grados</option>
                                <!-- Se cargarán dinámicamente -->
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label fw-bold text-primary">
                                <i class="fas fa-chalkboard"></i> Curso
                            </label>
                            <select class="form-control select2-advanced" id="curso_filtro_id">
                                <option value="">Todos los cursos</option>
                                <!-- Se cargarán dinámicamente -->
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label fw-bold text-primary">
                                <i class="fas fa-users"></i> Sección
                            </label>
                            <select class="form-control select2-advanced" id="seccion_filtro_id">
                                <option value="">Todas las secciones</option>
                                <!-- Se cargarán dinámicamente -->
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label fw-bold text-primary">
                                <i class="fas fa-user-graduate"></i> Estudiante
                            </label>
                            <select class="form-control select2-advanced" id="estudiante_id">
                                <option value="">Todos los estudiantes</option>
                                <!-- Se cargarán dinámicamente -->
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label fw-bold text-primary">
                                <i class="fas fa-chalkboard-teacher"></i> Docente
                            </label>
                            <select class="form-control select2-advanced" id="docente_id">
                                <option value="">Todos los docentes</option>
                                <!-- Se cargarán dinámicamente -->
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label fw-bold text-primary">
                                <i class="fas fa-book"></i> Asignatura
                            </label>
                            <select class="form-control select2-advanced" id="asignatura_id">
                                <option value="">Todas las asignaturas</option>
                                <!-- Se cargarán dinámicamente -->
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label fw-bold text-primary">
                                <i class="fas fa-clock"></i> Horario
                            </label>
                            <select class="form-control select2-advanced" id="horario_id">
                                <option value="">Todos los horarios</option>
                                <!-- Se cargarán dinámicamente -->
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" onclick="limpiarFiltros()">
                    <i class="fas fa-eraser"></i> Limpiar Filtros
                </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Cerrar
                </button>
                <button type="button" class="btn btn-success" id="btnAplicarFiltrosAvanzados">
                    <i class="fas fa-check"></i> Aplicar Filtros
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Loading Indicator -->
<div class="loading-overlay" id="loadingOverlay" style="display: none;">
    <div class="loading-content">
        <div class="loading-dots">
            <div class="dot dot-1"></div>
            <div class="dot dot-2"></div>
            <div class="dot dot-3"></div>
            <div class="dot dot-4"></div>
            <div class="dot dot-5"></div>
        </div>
        <div class="loading-text">Aplicando filtros...</div>
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
