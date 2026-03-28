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
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold text-primary">Seleccionar Estudiante</label>
                                                <select class="form-control" id="estudiante_reporte" style="display: none;">
                                                    <option value="">Todos los estudiantes</option>
                                                    <!-- Se cargarán dinámicamente -->
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold text-primary">Seleccionar Grado</label>
                                                <select class="form-control" id="grado_reporte" style="display: none;">
                                                    <option value="">Todos los grados</option>
                                                    <!-- Se cargarán dinámicamente -->
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
});

function generarReporte() {
    const filtros = {
        fecha_inicio: $('#fecha_inicio_reporte').val(),
        fecha_fin: $('#fecha_fin_reporte').val(),
        tipo_reporte: $('#tipo_reporte').val(),
        formato: $('#formato_reporte').val(),
        incluir_estadisticas: $('#incluir_estadisticas').is(':checked'),
        incluir_graficos: $('#incluir_graficos').is(':checked')
    };

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

    // Simular generación de reporte (aquí iría la llamada AJAX real)
    setTimeout(function() {
        $('#btnGenerarReporte').prop('disabled', false).html('<i class="fas fa-file-export mr-1"></i>Generar Reporte');

        // Mostrar vista previa
        $('#vista-previa-reporte').html(`
            <div class="alert alert-success">
                <h5><i class="fas fa-check-circle mr-2"></i>Reporte generado exitosamente</h5>
                <p>El reporte de tipo "${filtros.tipo_reporte}" en formato ${filtros.formato.toUpperCase()} ha sido generado.</p>
                <p><strong>Período:</strong> ${filtros.fecha_inicio} - ${filtros.fecha_fin}</p>
                <div class="mt-3">
                    <a href="#" class="btn btn-primary btn-sm mr-2">
                        <i class="fas fa-download mr-1"></i>Descargar Reporte
                    </a>
                    <button class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-envelope mr-1"></i>Enviar por Email
                    </button>
                </div>
            </div>
        `);

        Swal.fire({
            icon: 'success',
            title: 'Reporte generado',
            text: `El reporte se ha generado correctamente en formato ${filtros.formato.toUpperCase()}`
        });
    }, 2000);
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
