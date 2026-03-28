@extends('cplantilla.bprincipal')
@section('titulo','Reportes de Asistencia')
@section('contenidoplantilla')
<x-breadcrumb :module="'asistencia'" :section="'reportes'" />
<div class="container-fluid" id="contenido-principal">
    <div class="row mt-4 ml-1 mr-1">
        <div class="col-12">
            <div class="box_block">
                <!-- Collapse header -->
                <button class="btn btn-block text-left rounded-0 btn_header header_6" type="button" data-toggle="collapse" data-target="#collapseReportesAsistencia" aria-expanded="true" aria-controls="collapseReportesAsistencia" style="background: #0A8CB3 !important; font-weight: bold; color: white;">
                    <i class="fas fa-chart-bar m-1"></i>&nbsp;Reportes de Asistencia
                    <div class="float-right"><i class="fas fa-chevron-down"></i></div>
                </button>
                <!-- Descripción -->
                <div class="card-body rounded-0 border-0 pt-3 pb-3" style="background: #f3f3f3; border-bottom: 1px solid rgba(0,0,0,.125); border-top: 1px solid rgba(0,0,0,.125); color: #F59D24;">
                    <div class="row justify-content-center align-items-center flex-wrap">
                        <div class="col-auto text-center mb-2 mb-md-0" style="min-width:48px;">
                            <i class="fas fa-chart-line fa-2x"></i>
                        </div>
                        <div class="col px-2" style="text-align:justify;">
                            <p style="margin-bottom: 0px; font-family: 'Quicksand', sans-serif; font-weight: 600; color: #004a92;">
                                Genera reportes detallados de asistencia para análisis académico y administrativo.
                            </p>
                            <p style="font-family: 'Quicksand', sans-serif; font-weight: 600; color: #004a92;">
                                Utiliza los filtros para obtener reportes específicos por fechas, grados, secciones o estudiantes.
                            </p>
                        </div>
                    </div>
                </div>
                <!-- Collapse: contenido de reportes -->
                <div class="collapse show" id="collapseReportesAsistencia">
                    <div class="card card-body rounded-0 border-0 pt-0 pb-2" style="background: transparent;">
                        <!-- Acciones Rápidas -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2 flex-wrap">
                                    <a href="{{ route('asistencia.admin-index') }}" class="btn btn-outline-primary btn-sm" title="Volver a administración">
                                        <i class="fas fa-arrow-left mr-1"></i>Volver
                                    </a>
                                    <button type="button" class="btn btn-success btn-sm" id="btnGenerarReporte" title="Generar reporte">
                                        <i class="fas fa-file-export mr-1"></i>Generar Reporte
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Filtros de Reporte -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header" style="background: #0A8CB3; color: white;">
                                        <h5 class="mb-0">
                                            <i class="fas fa-filter mr-2"></i>Filtros del Reporte
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label class="form-label fw-bold text-primary">Fecha Inicio</label>
                                                <input type="date" class="form-control" id="fecha_inicio_reporte">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label fw-bold text-primary">Fecha Fin</label>
                                                <input type="date" class="form-control" id="fecha_fin_reporte">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label fw-bold text-primary">Tipo de Reporte</label>
                                                <select class="form-control" id="tipo_reporte">
                                                    <option value="general">General</option>
                                                    <option value="comparativo">Comparativo</option>
                                                    <option value="por_estudiante">Por Estudiante</option>
                                                    <option value="por_grado">Por Grado</option>
                                                    <option value="por_docente">Por Docente</option>
                                                    <option value="por_asignatura">Por Asignatura</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label fw-bold text-primary">Formato</label>
                                                <select class="form-control" id="formato_reporte">
                                                    <option value="pdf">PDF</option>
                                                    <option value="excel">Excel</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Filtros adicionales dinámicos -->
                                        <div class="row mt-3" id="filtros-adicionales" style="display: none;">
                                            <div class="col-md-6" id="contenedor-estudiante" style="display: none;">
                                                <label class="form-label fw-bold text-primary">Seleccionar Estudiante</label>
                                                <select class="form-control select2-basic" id="estudiante_reporte">
                                                    <option value="">Todos los estudiantes</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6" id="contenedor-grado" style="display: none;">
                                                <label class="form-label fw-bold text-primary">Seleccionar Grado</label>
                                                <select class="form-control select2-basic" id="grado_reporte">
                                                    <option value="">Todos los grados</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6" id="contenedor-docente" style="display: none;">
                                                <label class="form-label fw-bold text-primary">Seleccionar Docente</label>
                                                <select class="form-control select2-basic" id="docente_reporte">
                                                    <option value="">Todos los docentes</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6" id="contenedor-asignatura" style="display: none;">
                                                <label class="form-label fw-bold text-primary">Seleccionar Asignatura</label>
                                                <select class="form-control select2-basic" id="asignatura_reporte">
                                                    <option value="">Todas las asignaturas</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="incluir_estadisticas" checked>
                                                    <label class="form-check-label fw-bold" for="incluir_estadisticas">
                                                        Incluir estadísticas detalladas
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="incluir_graficos" checked>
                                                    <label class="form-check-label fw-bold" for="incluir_graficos">
                                                        Incluir gráficos (solo PDF)
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Vista Previa del Reporte -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header" style="background: #f8f9fa;">
                                        <h5 class="mb-0">
                                            <i class="fas fa-eye mr-2"></i>Vista Previa del Reporte
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div id="vista-previa-reporte">
                                            <div class="text-center text-muted">
                                                <i class="fas fa-chart-bar fa-3x mb-3"></i>
                                                <p>Configura los filtros y genera el reporte para ver la vista previa</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
$(document).ready(function() {
    // Configurar fechas por defecto
    const hoy = new Date();
    const primerDiaMes = new Date(hoy.getFullYear(), hoy.getMonth(), 1);
    const ultimoDiaMes = new Date(hoy.getFullYear(), hoy.getMonth() + 1, 0);

    $('#fecha_inicio_reporte').val(primerDiaMes.toISOString().split('T')[0]);
    $('#fecha_fin_reporte').val(ultimoDiaMes.toISOString().split('T')[0]);

    // Event listener para generar reporte
    $('#btnGenerarReporte').on('click', function() {
        generarReporte();
    });

    // Event listener para cambios en tipo de reporte
    $('#tipo_reporte').on('change', function() {
        manejarCambioTipoReporte();
    });

    // Inicializar filtros adicionales
    manejarCambioTipoReporte();
});

function generarReporte() {
    const filtros = {
        fecha_inicio: $('#fecha_inicio_reporte').val(),
        fecha_fin: $('#fecha_fin_reporte').val(),
        tipo_reporte: $('#tipo_reporte').val(),
        formato: $('#formato_reporte').val(),
        incluir_estadisticas: $('#incluir_estadisticas').is(':checked'),
        incluir_graficos: $('#incluir_graficos').is(':checked'),
        // Incluir filtros adicionales si están seleccionados
        estudiante_id: $('#estudiante_reporte').val() || undefined,
        grado_id: $('#grado_reporte').val() || undefined,
        docente_id: $('#docente_reporte').val() || undefined,
        asignatura_id: $('#asignatura_reporte').val() || undefined
    };

    // Limpiar valores undefined
    Object.keys(filtros).forEach(key => {
        if (filtros[key] === undefined || filtros[key] === '') {
            delete filtros[key];
        }
    });

    // Validar fechas
    if (!filtros.fecha_inicio || !filtros.fecha_fin) {
        Swal.fire({
            icon: 'warning',
            title: 'Fechas requeridas',
            text: 'Debes seleccionar fecha de inicio y fin'
        });
        return;
    }

    // Mostrar loading
    $('#btnGenerarReporte').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Generando...');

    Swal.fire({
        icon: 'info',
        title: 'Generando reporte...',
        text: 'El reporte se está generando, por favor espere.',
        allowOutsideClick: false,
        showConfirmButton: false
    });

    // Hacer petición AJAX para generar el reporte
    $.ajax({
        url: '{{ route("asistencia.api.guardar-reporte") }}',
        method: 'POST',
        data: filtros,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            $('#btnGenerarReporte').prop('disabled', false).html('<i class="fas fa-file-export mr-1"></i>Generar Reporte');

            console.log('Respuesta del servidor:', response);

            if (response.success && response.reporte_id) {
                // Mostrar mensaje de éxito y vista previa
                Swal.fire({
                    icon: 'success',
                    title: 'Reporte generado exitosamente',
                    text: 'El reporte se ha generado correctamente.',
                    showConfirmButton: true,
                    confirmButtonText: 'Descargar Reporte'
                }).then(function(result) {
                    if (result.isConfirmed) {
                        // Descargar el reporte generado
                        var downloadUrl = '{{ route("asistencia.reportes.descargar", ":reporteId") }}'.replace(':reporteId', response.reporte_id);
                        console.log('URL de descarga:', downloadUrl);
                        window.location.href = downloadUrl;
                    }
                });

                // Mostrar vista previa del reporte en la página
                var formatoTexto = response.formato === 'pdf' ? 'PDF' : 'Excel';
                $('#vista-previa-reporte').html(`
                    <div class="alert alert-success">
                        <h5><i class="fas fa-check-circle mr-2"></i>Reporte generado exitosamente</h5>
                        <p><strong>Tipo:</strong> ${response.tipo_reporte}</p>
                        <p><strong>Formato:</strong> ${formatoTexto}</p>
                        <p><strong>Fecha:</strong> ${new Date().toLocaleDateString()}</p>
                        <div class="mt-3">
                            <a href="{{ url('/') }}/asistencia/reportes/descargar/${response.reporte_id}" class="btn btn-primary btn-sm mr-2" target="_blank">
                                <i class="fas fa-download mr-1"></i>Descargar Reporte
                            </a>
                            <button class="btn btn-outline-secondary btn-sm" onclick="generarReporte()">
                                <i class="fas fa-plus mr-1"></i>Generar Otro Reporte
                            </button>
                        </div>
                    </div>
                `);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message || 'Error al generar el reporte'
                });
            }
        },
        error: function(xhr) {
            $('#btnGenerarReporte').prop('disabled', false).html('<i class="fas fa-file-export mr-1"></i>Generar Reporte');
            console.error('Error AJAX:', xhr.status, xhr.responseText);

            var errorMessage = xhr.responseJSON?.message || 'Error desconocido';

            // Verificar si es error de límite de registros para PDF
            if (errorMessage.includes('Use Excel') || errorMessage.includes('registros') || errorMessage.includes('máximo')) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Demasiados registros para PDF',
                    html: errorMessage + '<br><br><strong>¿Deseas generar el reporte en Excel en su lugar?</strong>',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, generar en Excel',
                    cancelButtonText: 'No, aplicar filtros',
                    confirmButtonColor: '#28a745'
                }).then(function(result) {
                    if (result.isConfirmed) {
                        // Cambiar formato a Excel y volver a intentar
                        $('#formato_reporte').val('excel');
                        generarReporte();
                    } else {
                        // Mantener filtros para que el usuario pueda ajustarlos
                        console.log('Usuario decidió aplicar filtros manualmente');
                    }
                });
            } else {
                // Error general
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al generar el reporte: ' + errorMessage
                });
            }
        }
    });
}

function manejarCambioTipoReporte() {
    const tipoReporte = $('#tipo_reporte').val();

    // Limpiar event handlers previos para evitar duplicados
    $('#docente_reporte').off('change.cascada');
    $('#grado_reporte').off('change.cascada');

    // Ocultar todos los contenedores adicionales primero
    $('#filtros-adicionales').hide();
    $('#contenedor-estudiante').hide();
    $('#contenedor-grado').hide();
    $('#contenedor-docente').hide();
    $('#contenedor-asignatura').hide();

    // Mostrar filtros según el tipo de reporte seleccionado
    if (tipoReporte === 'por_estudiante') {
        $('#filtros-adicionales').show();
        $('#contenedor-estudiante').show();
        cargarEstudiantesReporte();
    } else if (tipoReporte === 'por_grado') {
        $('#filtros-adicionales').show();
        $('#contenedor-grado').show();
        cargarGradosReporte();
    } else if (tipoReporte === 'por_docente') {
        $('#filtros-adicionales').show();
        $('#contenedor-docente').show();
        cargarDocentesReporte();
    } else if (tipoReporte === 'por_asignatura') {
        $('#filtros-adicionales').show();
        $('#contenedor-asignatura').show();
        cargarAsignaturasReporte();
    } else if (tipoReporte === 'comparativo') {
        // Mostrar todos los filtros con filtrado en cascada
        $('#filtros-adicionales').show();
        $('#contenedor-estudiante').show();
        $('#contenedor-grado').show();
        $('#contenedor-docente').show();
        $('#contenedor-asignatura').show();
        cargarEstudiantesReporte();
        cargarGradosReporte();
        cargarDocentesReporte();
        cargarAsignaturasReporte();
        configurarCascadaComparativo();
    }
    // Para tipo "general", no mostrar filtros adicionales
}

function cargarEstudiantesReporte(params = {}) {
    // Cargar estudiantes que tienen registros de asistencia
    const url = '{{ route("asistencia.api.estudiantes") }}';
    const queryParams = new URLSearchParams(params).toString();
    const fullUrl = queryParams ? `${url}?${queryParams}` : url;

    $.get(fullUrl, function(data) {
        $('#estudiante_reporte').html('<option value="">Seleccionar estudiante</option>');
        // Filtrar duplicados por ID
        const uniqueData = data.filter((item, index, self) =>
            index === self.findIndex(t => t.id === item.id)
        );
        uniqueData.forEach(function(estudiante) {
            $('#estudiante_reporte').append(`<option value="${estudiante.id}">${estudiante.nombres} ${estudiante.apellidos}</option>`);
        });
        // Reinicializar Select2
        if ($('#estudiante_reporte').hasClass('select2-hidden-accessible')) {
            $('#estudiante_reporte').select2('destroy');
        }
        $('#estudiante_reporte').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: 'Seleccionar estudiante',
            allowClear: true,
            minimumResultsForSearch: 0
        });
    }).fail(function(xhr) {
        console.error('Error al cargar estudiantes:', xhr.responseText);
    });
}

function cargarGradosReporte() {
    // Cargar grados que tienen registros de asistencia
    $.get('{{ route("asistencia.api.grados") }}', function(data) {
        $('#grado_reporte').html('<option value="">Seleccionar grado</option>');
        // Filtrar duplicados por ID
        const uniqueData = data.filter((item, index, self) =>
            index === self.findIndex(t => t.id === item.id)
        );
        uniqueData.forEach(function(grado) {
            $('#grado_reporte').append(`<option value="${grado.id}">${grado.descripcion}</option>`);
        });
        // Reinicializar Select2
        if ($('#grado_reporte').hasClass('select2-hidden-accessible')) {
            $('#grado_reporte').select2('destroy');
        }
        $('#grado_reporte').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: 'Seleccionar grado',
            allowClear: true,
            minimumResultsForSearch: 0
        });
    }).fail(function(xhr) {
        console.error('Error al cargar grados:', xhr.responseText);
    });
}

function cargarDocentesReporte() {
    // Cargar docentes que tienen registros de asistencia
    $.get('{{ route("asistencia.api.docentes") }}', function(data) {
        $('#docente_reporte').html('<option value="">Seleccionar docente</option>');
        // Filtrar duplicados por ID
        const uniqueData = data.filter((item, index, self) =>
            index === self.findIndex(t => t.id === item.id)
        );
        uniqueData.forEach(function(docente) {
            $('#docente_reporte').append(`<option value="${docente.id}">${docente.nombres} ${docente.apellidos}</option>`);
        });
        // Reinicializar Select2
        if ($('#docente_reporte').hasClass('select2-hidden-accessible')) {
            $('#docente_reporte').select2('destroy');
        }
        $('#docente_reporte').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: 'Seleccionar docente',
            allowClear: true,
            minimumResultsForSearch: 0
        });
    }).fail(function(xhr) {
        console.error('Error al cargar docentes:', xhr.responseText);
    });
}

function cargarAsignaturasReporte() {
    // Cargar asignaturas que tienen registros de asistencia
    $.get('{{ route("asistencia.api.asignaturas") }}', function(data) {
        $('#asignatura_reporte').html('<option value="">Seleccionar asignatura</option>');
        // Filtrar duplicados por ID
        const uniqueData = data.filter((item, index, self) =>
            index === self.findIndex(t => t.id === item.id)
        );
        uniqueData.forEach(function(asignatura) {
            $('#asignatura_reporte').append(`<option value="${asignatura.id}">${asignatura.nombre}</option>`);
        });
        // Reinicializar Select2
        if ($('#asignatura_reporte').hasClass('select2-hidden-accessible')) {
            $('#asignatura_reporte').select2('destroy');
        }
        $('#asignatura_reporte').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: 'Seleccionar asignatura',
            allowClear: true,
            minimumResultsForSearch: 0
        });
    }).fail(function(xhr) {
        console.error('Error al cargar asignaturas:', xhr.responseText);
    });
}

function configurarCascadaComparativo() {
    console.log('Configurando cascada comparativo');

    // Docente → Estudiantes, Asignaturas
    $('#docente_reporte').on('change.cascada', function() {
        const docenteId = $(this).val();
        console.log('Docente cambiado:', docenteId);

        // Recargar estudiantes filtrados por docente
        if (docenteId) {
            console.log('Cargando estudiantes filtrados por docente:', docenteId);
            $.get('{{ route("asistencia.api.estudiantes") }}', { docente_id: docenteId }, function(data) {
                console.log('Estudiantes filtrados por docente:', data.length);
                $('#estudiante_reporte').html('<option value="">Seleccionar estudiante</option>');
                // Filtrar duplicados
                const uniqueData = data.filter((item, index, self) =>
                    index === self.findIndex(t => t.id === item.id)
                );
                uniqueData.forEach(function(estudiante) {
                    $('#estudiante_reporte').append(`<option value="${estudiante.id}">${estudiante.nombres} ${estudiante.apellidos}</option>`);
                });
                // Reinicializar Select2
                if ($('#estudiante_reporte').hasClass('select2-hidden-accessible')) {
                    $('#estudiante_reporte').select2('destroy');
                }
                $('#estudiante_reporte').select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    placeholder: 'Seleccionar estudiante',
                    allowClear: true,
                    minimumResultsForSearch: 0
                });
            }).fail(function(xhr) {
                console.error('Error al filtrar estudiantes por docente:', xhr.responseText);
            });
        } else {
            cargarEstudiantesReporte();
        }

        // Recargar asignaturas filtradas por docente
        if (docenteId) {
            console.log('Cargando asignaturas filtradas por docente:', docenteId);
            $.get('{{ route("asistencia.api.asignaturas") }}', { docente_id: docenteId }, function(data) {
                console.log('Asignaturas filtradas por docente:', data.length);
                $('#asignatura_reporte').html('<option value="">Seleccionar asignatura</option>');
                // Filtrar duplicados
                const uniqueData = data.filter((item, index, self) =>
                    index === self.findIndex(t => t.id === item.id)
                );
                uniqueData.forEach(function(asignatura) {
                    $('#asignatura_reporte').append(`<option value="${asignatura.id}">${asignatura.nombre}</option>`);
                });
                // Reinicializar Select2
                if ($('#asignatura_reporte').hasClass('select2-hidden-accessible')) {
                    $('#asignatura_reporte').select2('destroy');
                }
                $('#asignatura_reporte').select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    placeholder: 'Seleccionar asignatura',
                    allowClear: true,
                    minimumResultsForSearch: 0
                });
            }).fail(function(xhr) {
                console.error('Error al filtrar asignaturas por docente:', xhr.responseText);
            });
        } else {
            cargarAsignaturasReporte();
        }
    });

    // Grado → Estudiantes
    $('#grado_reporte').on('change.cascada', function() {
        const gradoId = $(this).val();
        const docenteId = $('#docente_reporte').val();
        console.log('Grado cambiado:', gradoId, 'Docente:', docenteId);

        if (!gradoId) {
            // Si no hay grado seleccionado, recargar todos los estudiantes (filtrados por docente si existe)
            if (docenteId) {
                cargarEstudiantesReporte({ docente_id: docenteId });
            } else {
                cargarEstudiantesReporte();
            }
            return;
        }

        $.get('{{ route("asistencia.api.estudiantes") }}', {
            grado_id: gradoId,
            docente_id: docenteId || undefined
        }, function(data) {
            console.log('Estudiantes filtrados por grado y docente:', data.length, data);
            $('#estudiante_reporte').html('<option value="">Seleccionar estudiante</option>');
            // Filtrar duplicados
            const uniqueData = data.filter((item, index, self) =>
                index === self.findIndex(t => t.id === item.id)
            );
            console.log('Estudiantes únicos:', uniqueData.length);
            uniqueData.forEach(function(estudiante) {
                $('#estudiante_reporte').append(`<option value="${estudiante.id}">${estudiante.nombres} ${estudiante.apellidos}</option>`);
            });
            // Reinicializar Select2
            if ($('#estudiante_reporte').hasClass('select2-hidden-accessible')) {
                $('#estudiante_reporte').select2('destroy');
            }
            $('#estudiante_reporte').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: 'Seleccionar estudiante',
                allowClear: true,
                minimumResultsForSearch: 0
            });
            console.log('Select2 reinicializado para estudiantes');
        }).fail(function(xhr, status, error) {
            console.error('Error al filtrar estudiantes por grado:', status, error, xhr.responseText);
        });
    });

    console.log('Cascada comparativo configurada');
}

function generarReporte() {
    const filtros = {
        fecha_inicio: $('#fecha_inicio_reporte').val(),
        fecha_fin: $('#fecha_fin_reporte').val(),
        tipo_reporte: $('#tipo_reporte').val(),
        formato: $('#formato_reporte').val(),
        incluir_estadisticas: $('#incluir_estadisticas').is(':checked'),
        incluir_graficos: $('#incluir_graficos').is(':checked'),
        // Incluir filtros adicionales si están seleccionados
        estudiante_id: $('#estudiante_reporte').val() || undefined,
        grado_id: $('#grado_reporte').val() || undefined,
        docente_id: $('#docente_reporte').val() || undefined,
        asignatura_id: $('#asignatura_reporte').val() || undefined
    };

    // Limpiar valores undefined
    Object.keys(filtros).forEach(key => {
        if (filtros[key] === undefined || filtros[key] === '') {
            delete filtros[key];
        }
    });

    // Validar fechas
    if (!filtros.fecha_inicio || !filtros.fecha_fin) {
        Swal.fire({
            icon: 'warning',
            title: 'Fechas requeridas',
            text: 'Debes seleccionar fecha de inicio y fin'
        });
        return;
    }

    // Mostrar loading
    $('#btnGenerarReporte').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Generando...');

    Swal.fire({
        icon: 'info',
        title: 'Generando reporte...',
        text: 'El reporte se está generando, por favor espere.',
        allowOutsideClick: false,
        showConfirmButton: false
    });

    // Hacer petición AJAX para generar el reporte
    $.ajax({
        url: '{{ route("asistencia.api.guardar-reporte") }}',
        method: 'POST',
        data: filtros,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            $('#btnGenerarReporte').prop('disabled', false).html('<i class="fas fa-file-export mr-1"></i>Generar Reporte');

            console.log('Respuesta del servidor:', response);

            if (response.success && response.reporte_id) {
                // Mostrar mensaje de éxito y descargar
                Swal.fire({
                    icon: 'success',
                    title: 'Reporte generado',
                    text: 'El reporte se ha generado correctamente. Iniciando descarga...',
                    timer: 1500,
                    showConfirmButton: false
                }).then(function() {
                    // Descargar el reporte generado
                    var downloadUrl = '{{ route("asistencia.reportes.descargar", ":reporteId") }}'.replace(':reporteId', response.reporte_id);
                    console.log('URL de descarga:', downloadUrl);
                    window.location.href = downloadUrl;
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message || 'Error al generar el reporte'
                });
            }
        },
        error: function(xhr) {
            $('#btnGenerarReporte').prop('disabled', false).html('<i class="fas fa-file-export mr-1"></i>Generar Reporte');
            console.error('Error AJAX:', xhr.status, xhr.responseText);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al generar el reporte: ' + (xhr.responseJSON?.message || 'Error desconocido')
            });
        }
    });
}

function descargarReporte() {
    // Esta función ahora es un alias para generarReporte()
    // Ya que el flujo es: generar -> descargar automáticamente
    generarReporte();
}

function enviarPorEmail() {
    // Mostrar modal o formulario para enviar por email
    Swal.fire({
        title: 'Enviar Reporte por Email',
        html: `
            <div class="form-group text-left">
                <label for="email-destino" class="form-label">Correo electrónico destino:</label>
                <input type="email" id="email-destino" class="form-control" placeholder="ejemplo@correo.com" required>
            </div>
            <div class="form-group text-left mt-3">
                <label for="asunto-email" class="form-label">Asunto:</label>
                <input type="text" id="asunto-email" class="form-control" value="Reporte de Asistencia" required>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Enviar',
        cancelButtonText: 'Cancelar',
        preConfirm: () => {
            const email = document.getElementById('email-destino').value;
            const asunto = document.getElementById('asunto-email').value;

            if (!email || !asunto) {
                Swal.showValidationMessage('Por favor complete todos los campos');
                return false;
            }

            return { email, asunto };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Simular envío por email
            Swal.fire({
                icon: 'info',
                title: 'Enviando...',
                text: 'El reporte se está enviando por email.',
                timer: 2000,
                showConfirmButton: false
            });

            setTimeout(function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Email enviado',
                    text: `El reporte ha sido enviado exitosamente a ${result.value.email}`
                });
            }, 2000);
        }
    });
}
</script>

<style>
/* Animación de entrada */
@keyframes slideInLeft {
    from { opacity: 0; transform: translateX(-50px);}
    to { opacity: 1; transform: translateX(0);}
}
.animate-slide-in { animation: slideInLeft 0.8s ease-out; }

/* Cards */
.card {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.card-header {
    border-bottom: 1px solid #dee2e6;
    border-radius: 8px 8px 0 0 !important;
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
</style>
@endsection
