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
                                <input type="date" class="form-control filtro-input" id="fecha_inicio">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-bold text-primary">Fecha Fin</label>
                                <input type="date" class="form-control filtro-input" id="fecha_fin">
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
                                <button type="button" class="btn btn-outline-primary w-100" id="btnFiltrosAvanzados" data-toggle="collapse" data-target="#filtrosAvanzadosCollapse" aria-expanded="false">
                                    <i class="fas fa-sliders-h"></i> Filtros Avanzados
                                    <i class="fas fa-chevron-down float-right" id="chevronIcon"></i>
                                </button>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-success w-100" id="btnAplicarFiltros">
                                    <i class="fas fa-search"></i> Aplicar Filtros
                                </button>
                            </div>
                        </div>

                        <!-- Estadísticas Compactas -->
                        <div class="row mb-3" id="estadisticasContainer">
                            <div class="col-md-12">
                                <div class="stats-compact d-flex justify-content-between align-items-center p-3 rounded" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border: 1px solid #dee2e6;">
                                    <div class="stat-item text-center">
                                        <div class="stat-value">
                                            <span class="badge badge-success stat-badge" id="totalRegistros">0</span>
                                        </div>
                                        <small class="text-muted d-block">Total</small>
                                    </div>
                                    <div class="stat-item text-center">
                                        <div class="stat-value">
                                            <span class="badge badge-primary stat-badge" id="totalPresentes">0</span>
                                        </div>
                                        <small class="text-muted d-block">Presentes</small>
                                    </div>
                                    <div class="stat-item text-center">
                                        <div class="stat-value">
                                            <span class="badge badge-danger stat-badge" id="totalAusentes">0</span>
                                        </div>
                                        <small class="text-muted d-block">Ausentes</small>
                                    </div>
                                    <div class="stat-item text-center">
                                        <div class="stat-value">
                                            <span class="badge badge-info stat-badge" id="porcentajeAsistencia">0%</span>
                                        </div>
                                        <small class="text-muted d-block">% Asistencia</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Filtros Avanzados Expandibles -->
                        <div class="collapse mb-3" id="filtrosAvanzadosCollapse">
                            <div class="card card-body" style="background: #f8f9fa; border: 2px solid #0A8CB3; border-radius: 10px;">
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
                                    <div class="col-md-12">
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
                                </div>
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <button type="button" class="btn btn-outline-secondary mr-2" onclick="limpiarFiltros()">
                                            <i class="fas fa-eraser"></i> Limpiar Filtros
                                        </button>
                                        <button type="button" class="btn btn-success" id="btnAplicarFiltrosAvanzados">
                                            <i class="fas fa-check"></i> Aplicar Filtros Avanzados
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

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
                    <div class="col-md-12">
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
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
let currentPage = 1;
let currentFilters = {};

$(document).ready(function() {
    // Inicializar Select2
    $('.select2-basic').select2({
        theme: 'bootstrap-5',
        width: '100%',
        placeholder: function() {
            return $(this).data('placeholder') || 'Seleccionar...';
        }
    });

    $('.select2-advanced').select2({
        theme: 'bootstrap-5',
        width: '100%',
        placeholder: function() {
            return $(this).data('placeholder') || 'Seleccionar...';
        },
        allowClear: true,
        minimumResultsForSearch: 0  // Always show search box
    });

    // Cargar datos iniciales
    cargarDatos();

    // Event listeners para filtros rápidos
    $('#btnAplicarFiltros').on('click', function() {
        aplicarFiltrosRapidos();
    });

    $('#btnAplicarFiltrosAvanzados').on('click', function() {
        aplicarFiltrosAvanzados();
    });

    // Cargar opciones de filtros avanzados cuando se abre el modal
    $('#modalFiltrosAvanzados').on('show.bs.modal', function() {
        cargarOpcionesFiltrosAvanzados();
    });
});

function mostrarLoading() {
    $('#loadingOverlay').fadeIn(200);
}

function ocultarLoading() {
    $('#loadingOverlay').fadeOut(200);
}

function aplicarFiltrosRapidos() {
    currentFilters = {
        fecha_inicio: $('#fecha_inicio').val(),
        fecha_fin: $('#fecha_fin').val(),
        tipo_asistencia: $('#tipo_asistencia').val(),
        justificado: $('#justificado').val()
    };
    currentPage = 1;
    cargarDatos();
}

function aplicarFiltrosAvanzados() {
    currentFilters = {
        fecha_inicio: $('#fecha_inicio').val(),
        fecha_fin: $('#fecha_fin').val(),
        tipo_asistencia: $('#tipo_asistencia').val(),
        justificado: $('#justificado').val(),
        nivel_id: $('#nivel_id').val(),
        grado_id: $('#grado_id').val(),
        curso_id: $('#curso_filtro_id').val(),
        seccion_id: $('#seccion_filtro_id').val(),
        estudiante_id: $('#estudiante_id').val(),
        docente_id: $('#docente_id').val(),
        asignatura_id: $('#asignatura_id').val()
    };
    currentPage = 1;
    $('#modalFiltrosAvanzados').modal('hide');
    cargarDatos();
}

function limpiarFiltros() {
    // Limpiar filtros avanzados
    $('#modalFiltrosAvanzados select').val('').trigger('change');
    // Limpiar filtros rápidos
    $('#fecha_inicio').val('');
    $('#fecha_fin').val('');
    $('#tipo_asistencia').val('').trigger('change');
    $('#justificado').val('').trigger('change');

    currentFilters = {};
    currentPage = 1;
    cargarDatos();
}

function cargarDatos(pagina = currentPage) {
    mostrarLoading();

    const filtros = {
        ...currentFilters,
        page: pagina
    };

    $.ajax({
        url: '{{ route("asistencia.api.tabla-asistencias") }}',
        method: 'GET',
        data: filtros,
        success: function(response) {
            ocultarLoading();

            if (response.success) {
                renderizarTabla(response.data);
                renderizarPaginacion(response.data);
                actualizarEstadisticas(response.estadisticas);
            } else {
                mostrarError(response.message);
            }
        },
        error: function(xhr) {
            ocultarLoading();
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
                    <td>${asistencia.matricula?.grado?.descripcion || ''}</td>
                    <td>${asistencia.matricula?.seccion?.nombre || ''}</td>
                    <td>
                        <span class="badge badge-${getBadgeClass(asistencia.tipo_asistencia?.codigo || '')}">
                            ${asistencia.tipo_asistencia?.nombre || ''}
                        </span>
                    </td>
                    <td>
                        ${asistencia.justificacion ?
                            '<i class="fas fa-check text-success"></i>' :
                            '<i class="fas fa-times text-danger"></i>'}
                    </td>
                    <td>${asistencia.estado || ''}</td>
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
        html += '<nav><ul class="pagination justify-content-center">';

        // Anterior
        if (data.current_page > 1) {
            html += `<li class="page-item"><a class="page-link" href="#" onclick="cambiarPagina(${data.current_page - 1})">Anterior</a></li>`;
        }

        // Páginas
        for (let i = Math.max(1, data.current_page - 2); i <= Math.min(data.last_page, data.current_page + 2); i++) {
            html += `<li class="page-item ${i === data.current_page ? 'active' : ''}">
                <a class="page-link" href="#" onclick="cambiarPagina(${i})">${i}</a>
            </li>`;
        }

        // Siguiente
        if (data.current_page < data.last_page) {
            html += `<li class="page-item"><a class="page-link" href="#" onclick="cambiarPagina(${data.current_page + 1})">Siguiente</a></li>`;
        }

        html += '</ul></nav>';
    }

    $('#paginacionContainer').html(html);
}

function actualizarEstadisticas(estadisticas) {
    if (estadisticas) {
        $('#totalRegistros').text(estadisticas.total_registros || 0);
        $('#totalPresentes').text(estadisticas.total_presentes || 0);
        $('#totalAusentes').text(estadisticas.total_ausentes || 0);
        $('#porcentajeAsistencia').text(estadisticas.porcentaje_asistencia || '0%');
    }
}

function cambiarPagina(pagina) {
    currentPage = pagina;
    cargarDatos();
}

function cargarOpcionesFiltrosAvanzados() {
    // Cargar niveles educativos
    $.get('{{ route("asistencia.api.niveles") }}', function(data) {
        $('#nivel_id').html('<option value="">Todos los niveles</option>');
        data.forEach(function(nivel) {
            $('#nivel_id').append(`<option value="${nivel.id}">${nivel.nombre}</option>`);
        });
        reinicializarSelect2('#nivel_id');
    });

    // Cargar grados
    $.get('{{ route("asistencia.api.grados") }}', function(data) {
        $('#grado_id').html('<option value="">Todos los grados</option>');
        data.forEach(function(grado) {
            $('#grado_id').append(`<option value="${grado.id}">${grado.descripcion}</option>`);
        });
        reinicializarSelect2('#grado_id');
    });

    // Cargar cursos
    $.get('{{ route("asistencia.api.cursos") }}', function(data) {
        $('#curso_filtro_id').html('<option value="">Todos los cursos</option>');
        data.forEach(function(curso) {
            $('#curso_filtro_id').append(`<option value="${curso.id}">${curso.nombre}</option>`);
        });
        reinicializarSelect2('#curso_filtro_id');
    });

    // Cargar secciones
    $.get('{{ route("asistencia.api.secciones") }}', function(data) {
        $('#seccion_filtro_id').html('<option value="">Todas las secciones</option>');
        data.forEach(function(seccion) {
            $('#seccion_filtro_id').append(`<option value="${seccion.id}">${seccion.nombre}</option>`);
        });
        reinicializarSelect2('#seccion_filtro_id');
    });

    // Cargar estudiantes
    $.get('{{ route("asistencia.api.estudiantes") }}', function(data) {
        console.log('Estudiantes cargados:', data);
        $('#estudiante_id').html('<option value="">Todos los estudiantes</option>');
        data.forEach(function(estudiante) {
            $('#estudiante_id').append(`<option value="${estudiante.id}">${estudiante.nombres} ${estudiante.apellidos}</option>`);
        });
        reinicializarSelect2('#estudiante_id');
    }).fail(function(xhr, status, error) {
        console.error('Error al cargar estudiantes:', error);
        console.log('Status:', status);
        console.log('Response:', xhr.responseText);
        console.log('URL:', '{{ route("asistencia.api.estudiantes") }}');
    });

    // Cargar docentes
    $.get('{{ route("asistencia.api.docentes") }}', function(data) {
        console.log('Docentes cargados:', data);
        $('#docente_id').html('<option value="">Todos los docentes</option>');
        data.forEach(function(docente) {
            $('#docente_id').append(`<option value="${docente.id}">${docente.nombres} ${docente.apellidos}</option>`);
        });
        reinicializarSelect2('#docente_id');
    }).fail(function(xhr, status, error) {
        console.error('Error al cargar docentes:', error);
        console.log('Status:', status);
        console.log('Response:', xhr.responseText);
        console.log('URL:', '{{ route("asistencia.api.docentes") }}');
    });

    // Cargar asignaturas
    $.get('{{ route("asistencia.api.asignaturas") }}', function(data) {
        console.log('Asignaturas cargadas:', data);
        $('#asignatura_id').html('<option value="">Todas las asignaturas</option>');
        data.forEach(function(asignatura) {
            $('#asignatura_id').append(`<option value="${asignatura.id}">${asignatura.nombre}</option>`);
        });
        reinicializarSelect2('#asignatura_id');
    }).fail(function(xhr, status, error) {
        console.error('Error al cargar asignaturas:', error);
        console.log('Status:', status);
        console.log('Response:', xhr.responseText);
        console.log('URL:', '{{ route("asistencia.api.asignaturas") }}');
    });
}

function reinicializarSelect2(selector) {
    // Destruir la instancia anterior si existe
    if ($(selector).hasClass('select2-hidden-accessible')) {
        $(selector).select2('destroy');
    }

    // Reinicializar Select2
    $(selector).select2({
        theme: 'bootstrap-5',
        width: '100%',
        placeholder: 'Seleccionar...',
        allowClear: true,
        minimumResultsForSearch: 0
    });
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

<style>
/* Loading Overlay */
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.loading-content {
    text-align: center;
    color: white;
}

.loading-dots {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 20px;
}

.dot {
    width: 15px;
    height: 15px;
    border-radius: 50%;
    margin: 0 5px;
    animation: bounce 1.4s ease-in-out infinite both;
}

.dot-1 { background-color: #ff6b6b; animation-delay: -0.32s; }
.dot-2 { background-color: #4ecdc4; animation-delay: -0.16s; }
.dot-3 { background-color: #45b7d1; animation-delay: 0s; }
.dot-4 { background-color: #f9ca24; animation-delay: 0.16s; }
.dot-5 { background-color: #f0932b; animation-delay: 0.32s; }

@keyframes bounce {
    0%, 80%, 100% {
        transform: scale(0);
    }
    40% {
        transform: scale(1);
    }
}

.loading-text {
    font-size: 18px;
    font-weight: bold;
    margin: 0;
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
@endsection
