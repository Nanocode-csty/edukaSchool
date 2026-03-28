@extends('cplantilla.bprincipal')
@section('titulo','Reportes de Asistencia')
@section('contenidoplantilla')

<x-breadcrumb :module="'asistencia'" :section="'reportes'" />

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <button class="btn btn-block text-left rounded-0 btn_header header_6" type="button" data-toggle="collapse" data-target="#collapseReportes" style="background: #0A8CB3 !important; color: white;">
                <i class="fas fa-chart-line m-1"></i>&nbsp;Reportes de Asistencia
                <div class="float-right"><i class="fas fa-chevron-down"></i></div>
            </button>

            <div class="collapse show" id="collapseReportes">
                <div class="card card-body rounded-0 border-0 pt-0 pb-2">

                    <!-- Filtros -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header" style="background: #0e4067; color: white;">
                                    <h5 class="mb-0"><i class="fas fa-filter mr-2"></i>Filtros de Reporte</h5>
                                </div>
                                <div class="card-body">
                                    <form id="reportForm">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Fecha Inicio</label>
                                                    <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Fecha Fin</label>
                                                    <input type="date" class="form-control" id="fecha_fin" name="fecha_fin">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Tipo de Reporte</label>
                                                    <select class="form-control" id="tipo_reporte" name="tipo_reporte">
                                                        <option value="general">General</option>
                                                        <option value="por_curso">Por Curso</option>
                                                        <option value="por_estudiante">Por Estudiante</option>
                                                        <option value="por_docente">Por Docente</option>
                                                        <option value="comparativo">Comparativo</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Formato</label>
                                                    <select class="form-control" id="formato" name="formato">
                                                        <option value="pdf">PDF</option>
                                                        <option value="excel">Excel</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Períodos Rápidos -->
                                        <div class="row mb-3">
                                            <div class="col-12">
                                                <label class="form-label fw-bold text-primary">Períodos Rápidos:</label>
                                                <div class="btn-group-sm d-flex flex-wrap gap-1">
                                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="setPeriodo('hoy')">Hoy</button>
                                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="setPeriodo('ayer')">Ayer</button>
                                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="setPeriodo('ultimos7')">Últimos 7 días</button>
                                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="setPeriodo('ultimos30')">Últimos 30 días</button>
                                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="setPeriodo('esteMes')">Este mes</button>
                                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="setPeriodo('mesAnterior')">Mes anterior</button>
                                                    <button type="button" class="btn btn-outline-secondary btn-sm ml-2" onclick="limpiarFechas()">
                                                        <i class="fas fa-eraser"></i> Limpiar
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row" id="filtrosAdicionales" style="display: none;">
                                            <div class="col-md-12" id="filtroContainer"></div>
                                        </div>

                                        <div class="row">
                                            <div class="col-12 text-center">
                                                <button type="button" class="btn btn-primary btn-lg" onclick="generarReporte()">
                                                    <i class="fas fa-chart-line mr-2"></i>Generar Reporte
                                                </button>
                                                <a id="exportLink" href="#" class="btn btn-success btn-lg ml-2" onclick="exportarReporte(event)">
                                                    <i class="fas fa-download mr-2"></i>Exportar
                                                </a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Vista Previa -->
                    <div class="row mb-4" id="reportePreview" style="display: none;">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header" style="background: #17a2b8; color: white;">
                                    <button class="btn btn-block text-left p-0" type="button" data-toggle="collapse" data-target="#collapsePreview" style="background: transparent; border: none; color: white;">
                                        <h5 class="mb-0"><i class="fas fa-eye mr-2"></i>Vista Previa del Reporte</h5>
                                    </button>
                                </div>
                                <div class="collapse" id="collapsePreview">
                                    <div class="card-body">
                                        <div id="reporteContent"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Estadísticas -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row text-center">
                                        @php
                                            $stats = [
                                                ['icon' => 'fa-user-check', 'color' => '#28a745', 'value' => $estadisticasRapidas['porcentaje_asistencia'] . '%', 'label' => 'Asistencia'],
                                                ['icon' => 'fa-user-times', 'color' => '#dc3545', 'value' => number_format($estadisticasRapidas['total_inasistencias']), 'label' => 'Ausencias'],
                                                ['icon' => 'fa-clock', 'color' => '#ffc107', 'value' => number_format($estadisticasRapidas['total_tardanzas']), 'label' => 'Tardanzas'],
                                                ['icon' => 'fa-check-circle', 'color' => '#17a2b8', 'value' => number_format($estadisticasRapidas['justificaciones_aprobadas']), 'label' => 'Justificadas'],
                                                ['icon' => 'fa-users', 'color' => '#6c757d', 'value' => number_format($estadisticasRapidas['total_estudiantes'] ?? 0), 'label' => 'Estudiantes'],
                                                ['icon' => 'fa-calendar-alt', 'color' => '#343a40', 'value' => number_format($estadisticasRapidas['dias_analizados'] ?? 0), 'label' => 'Días']
                                            ];
                                        @endphp
                                        @foreach($stats as $stat)
                                        <div class="col-md-2 col-6 mb-3">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <div class="stats-icon mr-2" style="background: rgba({{ hexdec(substr($stat['color'], 1, 2)) }}, {{ hexdec(substr($stat['color'], 3, 2)) }}, {{ hexdec(substr($stat['color'], 5, 2)) }}, 0.1); color: {{ $stat['color'] }}; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas {{ $stat['icon'] }}"></i>
                                                </div>
                                                <div class="text-left">
                                                    <div class="h5 mb-0 font-weight-bold" style="color: {{ $stat['color'] }}">{{ $stat['value'] }}</div>
                                                    <small class="text-muted">{{ $stat['label'] }}</small>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Gráficos -->
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header" style="background: #28a745; color: white;">
                                    <h5 class="mb-0"><i class="fas fa-chart-area mr-2"></i>Tendencia de Asistencia</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="tendenciaChart"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header" style="background: #dc3545; color: white;">
                                    <h5 class="mb-0"><i class="fas fa-chart-pie mr-2"></i>Distribución por Tipo</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="distribucionChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- KPIs -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header" style="background: #20c997; color: white;">
                                    <h5 class="mb-0"><i class="fas fa-tachometer-alt mr-2"></i>KPIs de Rendimiento</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-md-3 col-6 mb-3">
                                            <div class="kpi-card p-3 rounded text-white" style="background: #007bff;">
                                                <i class="fas fa-users fa-2x mb-2"></i>
                                                <h3>{{ number_format($estadisticasRapidas['total_estudiantes'] ?? 0) }}</h3>
                                                <small>Estudiantes Activos</small>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-6 mb-3">
                                            <div class="kpi-card p-3 rounded text-white" style="background: #6c757d;">
                                                <i class="fas fa-clock fa-2x mb-2"></i>
                                                <h3>{{ number_format($estadisticasRapidas['dias_analizados'] ?? 0) }}</h3>
                                                <small>Días Analizados</small>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-6 mb-3">
                                            <div class="kpi-card p-3 rounded text-white" style="background: #28a745;">
                                                <i class="fas fa-chart-line fa-2x mb-2"></i>
                                                <h3>{{ $estadisticasRapidas['porcentaje_asistencia'] }}%</h3>
                                                <small>Tasa Promedio</small>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-6 mb-3">
                                            <div class="kpi-card p-3 rounded text-white" style="background: #17a2b8;">
                                                <i class="fas fa-shield-alt fa-2x mb-2"></i>
                                                <h3>{{ number_format($estadisticasRapidas['justificaciones_aprobadas'] ?? 0) }}</h3>
                                                <small>Justificaciones</small>
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

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
.select2-container--bootstrap-5 .select2-selection {
    border-radius: 0.375rem;
}
</style>
@endpush

@push('scripts')
<script>
// Initialize when DOM is ready
$(document).ready(function() {
    initReportsPage();
});

function initReports() {
    // Set default dates to previous month (has data)
    setPeriodo('mesAnterior');

    // Initialize UI components
    initUI();

    // Setup event handlers
    setupEvents();

    console.log('Reports initialized');
}

function initUI() {
    // Collapse functionality
    const collapse = document.getElementById('collapseReportes');
    const icon = document.querySelector('[data-target="#collapseReportes"] .fas');

    if (collapse && icon) {
        collapse.addEventListener('show.bs.collapse', () => {
            icon.classList.replace('fa-chevron-down', 'fa-chevron-up');
        });
        collapse.addEventListener('hide.bs.collapse', () => {
            icon.classList.replace('fa-chevron-up', 'fa-chevron-down');
        });
    }

    // Initialize Select2 for main selects
    $('#tipo_reporte, #formato').select2({
        theme: 'bootstrap-5',
        width: '100%',
        minimumResultsForSearch: 0
    });
}

function setupEvents() {
    // Report type change handler
    $('#tipo_reporte').on('change', handleReportTypeChange);

    // Dynamic filters update on date change
    $('#fecha_inicio, #fecha_fin').on('change', loadDynamicFilters);

    // Chart updates
    $('input, select').on('change', updateCharts);
}

function handleReportTypeChange() {
    const tipo = $(this).val();
    const filtrosDiv = $('#filtrosAdicionales');
    const container = $('#filtroContainer');

    if (tipo === 'general') {
        filtrosDiv.hide();
        return;
    }

    filtrosDiv.show();

    // Generate appropriate filter HTML
    let html = '';
    switch(tipo) {
        case 'por_curso':
            html = '<div class="form-group"><label>Curso</label><select class="form-control filter-select" id="curso_id"><option value="">Todos los cursos</option></select></div>';
            break;
        case 'por_estudiante':
            html = '<div class="form-group"><label>Estudiante</label><select class="form-control filter-select" id="estudiante_id"><option value="">Todos los estudiantes</option></select></div>';
            break;
        case 'por_docente':
            html = '<div class="form-group"><label>Docente</label><select class="form-control filter-select" id="docente_id"><option value="">Todos los docentes</option></select></div>';
            break;
        case 'comparativo':
            html = `
                <div class="row">
                    <div class="col-md-6"><div class="form-group"><label>Nivel</label><select class="form-control filter-select" id="nivel_id"><option value="">Seleccionar nivel...</option></select></div></div>
                    <div class="col-md-6"><div class="form-group"><label>Curso</label><select class="form-control filter-select" id="curso_id"><option value="">Seleccionar curso...</option></select></div></div>
                    <div class="col-md-6"><div class="form-group"><label>Estudiante</label><select class="form-control filter-select" id="estudiante_id"><option value="">Seleccionar estudiante...</option></select></div></div>
                    <div class="col-md-6"><div class="form-group"><label>Docente</label><select class="form-control filter-select" id="docente_id"><option value="">Seleccionar docente...</option></select></div></div>
                </div>
            `;
            // Load dynamic filters for comparative reports
            setTimeout(() => loadDynamicFilters(), 200);
            break;
    }

    container.html(tipo === 'comparativo' ?
        '<div class="alert alert-info"><i class="fas fa-calendar-alt mr-2"></i>Selecciona fechas para cargar filtros con datos de asistencia.</div>' : html);

    // Initialize Select2 for filter selects
    setTimeout(() => {
        $('.filter-select').select2({
            theme: 'bootstrap-5',
            width: '100%',
            allowClear: true,
            minimumResultsForSearch: 0
        });

        // Setup cascading filters for comparative reports
        if (tipo === 'comparativo') {
            setupCascadingFilters();
        }
    }, 100);
}

function loadDynamicFilters() {
    const fechaInicio = $('#fecha_inicio').val();
    const fechaFin = $('#fecha_fin').val();

    // Only load for comparative reports and when dates are selected
    if (!fechaInicio || !fechaFin || $('#tipo_reporte').val() !== 'comparativo') return;

    $('#filtroContainer').html('<div class="text-center py-3"><div class="spinner-border spinner-border-sm"></div><small> Cargando opciones con registros de asistencia...</small></div>');

    // Fetch only options that have attendance records for the selected period
    fetch(`/asistencia/api/filtros-dinamicos?fecha_inicio=${fechaInicio}&fecha_fin=${fechaFin}`)
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                populateDynamicOptions(data.data);
            } else {
                $('#filtroContainer').html('<div class="alert alert-warning"><i class="fas fa-exclamation-triangle mr-2"></i>No hay registros de asistencia para el período seleccionado.</div>');
            }
        })
        .catch(error => {
            console.error('Error loading dynamic filters:', error);
            $('#filtroContainer').html('<div class="alert alert-danger"><i class="fas fa-times mr-2"></i>Error al cargar filtros dinámicos.</div>');
        });
}

function populateDynamicOptions(data) {
    // Populate levels with attendance data
    if (data.niveles?.length) {
        const select = $('#nivel_id');
        select.html('<option value="">Seleccionar nivel...</option>');
        data.niveles.forEach(n => select.append(`<option value="${n.nivel_id}">${n.nombre}</option>`));
    }

    // Populate courses with attendance data
    if (data.cursos?.length) {
        const select = $('#curso_id');
        select.html('<option value="">Seleccionar curso...</option>');
        data.cursos.forEach(c => {
            const nombre = c.nombre_completo || `${c.grado?.nombre} ${c.seccion?.nombre}`;
            select.append(`<option value="${c.curso_id}" data-nivel="${c.grado?.nivel_id}">${nombre}</option>`);
        });
    }

    // Populate students with attendance data
    if (data.estudiantes?.length) {
        const select = $('#estudiante_id');
        select.html('<option value="">Seleccionar estudiante...</option>');
        data.estudiantes.forEach(e => {
            const nombre = `${e.persona?.apellidos}, ${e.persona?.nombres}`;
            select.append(`<option value="${e.estudiante_id}" data-curso="${e.matricula?.curso_id}" data-nivel="${e.matricula?.nivel_id}">${nombre}</option>`);
        });
    }

    // Populate teachers with attendance data
    if (data.docentes?.length) {
        const select = $('#docente_id');
        select.html('<option value="">Seleccionar docente...</option>');
        data.docentes.forEach(d => {
            const nombre = `${d.persona?.apellidos}, ${d.persona?.nombres}`;
            select.append(`<option value="${d.profesor_id}">${nombre}</option>`);
        });
    }

    // Reinitialize Select2 with proper theming
    $('.filter-select').select2({
        theme: 'bootstrap-5',
        width: '100%',
        allowClear: true,
        minimumResultsForSearch: 0,
        placeholder: function(){ return $(this).find('option:first').text(); }
    });
}

function setupCascadingFilters() {
    // Level -> Course filtering
    $('#nivel_id').on('change', function() {
        const nivelId = $(this).val();
        $('#curso_id option').each(function() {
            const show = !nivelId || $(this).data('nivel') == nivelId;
            $(this).toggle(show);
        });
        $('#curso_id').val('').trigger('change');
    });

    // Course -> Student filtering
    $('#curso_id').on('change', function() {
        const cursoId = $(this).val();
        $('#estudiante_id option').each(function() {
            const show = !cursoId || $(this).data('curso') == cursoId;
            $(this).toggle(show);
        });
        $('#estudiante_id').val('').trigger('change');
    });
}

function updateCharts() {
    const params = {
        fecha_inicio: $('#fecha_inicio').val(),
        fecha_fin: $('#fecha_fin').val(),
        tipo_reporte: $('#tipo_reporte').val(),
        nivel_id: $('#nivel_id').val(),
        curso_id: $('#curso_id').val(),
        estudiante_id: $('#estudiante_id').val(),
        docente_id: $('#docente_id').val()
    };

    if (!params.fecha_inicio || !params.fecha_fin) return;

    const query = new URLSearchParams(params);
    fetch(`/asistencia/api/estadisticas-filtradas?${query}`)
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                updateChartData(data.tendencia_mensual, data.distribucion_tipos);
            }
        })
        .catch(console.error);
}

// Initialize charts on page load
document.addEventListener('DOMContentLoaded', function() {
    // Charts initialization with data from PHP
    const tendenciaData = @json($tendenciaMensual);
    const distribucionData = @json($distribucionTipos);

    // Initialize tendency chart
    const ctxTendencia = document.getElementById('tendenciaChart');
    if (ctxTendencia) {
        const ctx = ctxTendencia.getContext('2d');
        window.tendenciaChartInstance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: tendenciaData.map(item => item.mes),
                datasets: [{
                    label: 'Asistencia Promedio (%)',
                    data: tendenciaData.map(item => item.porcentaje),
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: false, max: 100 }
                }
            }
        });
    }

    // Initialize distribution chart
    const ctxDistribucion = document.getElementById('distribucionChart');
    if (ctxDistribucion) {
        const ctx = ctxDistribucion.getContext('2d');
        window.distribucionChartInstance = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Presente', 'Ausente', 'Tarde', 'Justificado'],
                datasets: [{
                    data: [
                        distribucionData.presente,
                        distribucionData.ausente,
                        distribucionData.tarde,
                        distribucionData.justificado
                    ],
                    backgroundColor: ['#28a745', '#dc3545', '#ffc107', '#17a2b8']
                }]
            },
            options: { responsive: true }
        });
    }
});

// Function to update chart data
function updateChartData(tendenciaMensual, distribucionTipos) {
    // Update tendency chart
    if (window.tendenciaChartInstance && tendenciaMensual) {
        window.tendenciaChartInstance.data.labels = tendenciaMensual.map(item => item.mes);
        window.tendenciaChartInstance.data.datasets[0].data = tendenciaMensual.map(item => item.porcentaje);
        window.tendenciaChartInstance.update();
    }

    // Update distribution chart
    if (window.distribucionChartInstance && distribucionTipos) {
        window.distribucionChartInstance.data.datasets[0].data = [
            distribucionTipos.presente || 0,
            distribucionTipos.ausente || 0,
            distribucionTipos.tarde || 0,
            distribucionTipos.justificado || 0
        ];
        window.distribucionChartInstance.update();
    }
}

// Set period dates
function setPeriodo(tipo) {
    const hoy = new Date();
    let fechaInicio, fechaFin;

    switch(tipo) {
        case 'hoy':
            fechaInicio = fechaFin = hoy;
            break;
        case 'ayer':
            const ayer = new Date(hoy);
            ayer.setDate(hoy.getDate() - 1);
            fechaInicio = fechaFin = ayer;
            break;
        case 'ultimos7':
            fechaInicio = new Date(hoy);
            fechaInicio.setDate(hoy.getDate() - 6);
            fechaFin = hoy;
            break;
        case 'ultimos30':
            fechaInicio = new Date(hoy);
            fechaInicio.setDate(hoy.getDate() - 29);
            fechaFin = hoy;
            break;
        case 'esteMes':
            fechaInicio = new Date(hoy.getFullYear(), hoy.getMonth(), 1);
            fechaFin = new Date(hoy.getFullYear(), hoy.getMonth() + 1, 0);
            break;
        case 'mesAnterior':
            fechaInicio = new Date(hoy.getFullYear(), hoy.getMonth() - 1, 1);
            fechaFin = new Date(hoy.getFullYear(), hoy.getMonth(), 0);
            break;
    }

    const fechaInicioStr = fechaInicio.toISOString().split('T')[0];
    const fechaFinStr = fechaFin.toISOString().split('T')[0];

    const fechaInicioInput = document.getElementById('fecha_inicio');
    const fechaFinInput = document.getElementById('fecha_fin');

    if (fechaInicioInput && fechaFinInput) {
        fechaInicioInput.value = fechaInicioStr;
        fechaFinInput.value = fechaFinStr;

        // Trigger change events
        fechaInicioInput.dispatchEvent(new Event('change'));
        fechaFinInput.dispatchEvent(new Event('change'));
    }
}

// Clear dates
function limpiarFechas() {
    document.getElementById('fecha_inicio').value = '';
    document.getElementById('fecha_fin').value = '';
}

// Generate report preview
function generarReporte() {
    const fechaInicio = document.getElementById('fecha_inicio').value;
    const fechaFin = document.getElementById('fecha_fin').value;

    if (!fechaInicio || !fechaFin) {
        Swal.fire({
            icon: 'warning',
            title: 'Período requerido',
            text: 'Por favor selecciona las fechas de inicio y fin para generar el reporte.',
            confirmButtonText: 'Entendido'
        });
        return;
    }

    // Show preview section
    document.getElementById('reportePreview').style.display = 'block';
    const collapsePreview = document.getElementById('collapsePreview');
    if (collapsePreview && !collapsePreview.classList.contains('show')) {
        const previewCollapse = new bootstrap.Collapse(collapsePreview, {
            show: true
        });
    }

    const reporteContent = document.getElementById('reporteContent');
    reporteContent.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary"></div><p class="mt-2">Generando vista previa del reporte...</p></div>';

    // Collect filter parameters
    const params = {
        fecha_inicio: fechaInicio,
        fecha_fin: fechaFin,
        tipo_reporte: document.getElementById('tipo_reporte').value,
        nivel_id: document.getElementById('nivel_id')?.value || '',
        curso_id: document.getElementById('curso_id')?.value || '',
        estudiante_id: document.getElementById('estudiante_id')?.value || '',
        docente_id: document.getElementById('docente_id')?.value || ''
    };

    // Generate report content
    generateReportContent(params);
}

// Generate report content
function generateReportContent(params) {
    // Build API URL
    const queryParams = new URLSearchParams(params);
    const estadisticasUrl = `/asistencia/api/estadisticas-filtradas?${queryParams}`;
    const tablaUrl = `/asistencia/api/tabla-asistencias?${queryParams}&per_page=1000`;

    // Fetch statistics
    fetch(estadisticasUrl)
        .then(r => r.json())
        .then(estadisticasData => {
            if (estadisticasData.success) {
                // Update charts
                updateChartData(estadisticasData.tendencia_mensual, estadisticasData.distribucion_tipos);

                // Fetch table data
                return fetch(tablaUrl).then(r => r.json());
            } else {
                throw new Error(estadisticasData.message || 'Error al obtener estadísticas');
            }
        })
        .then(tablaData => {
            if (tablaData.success) {
                renderReportPreview(tablaData, params);
            } else {
                throw new Error(tablaData.message || 'Error al obtener datos de tabla');
            }
        })
        .catch(error => {
            document.getElementById('reporteContent').innerHTML =
                `<div class="alert alert-danger"><i class="fas fa-exclamation-triangle mr-2"></i>Error al generar la vista previa: ${error.message}</div>`;
        });
}

// Render report preview
function renderReportPreview(tablaData, params) {
    const estadisticas = tablaData.estadisticas;
    const estadisticasAdicionales = tablaData.estadisticas_adicionales || {};

    const totalRegistros = estadisticas.total_registros;

    if (totalRegistros === 0) {
        document.getElementById('reporteContent').innerHTML = `
            <div class="alert alert-warning">
                <i class="fas fa-info-circle mr-2"></i>No hay registros de asistencia para el período seleccionado.
            </div>
            <div class="text-center py-4">
                <div class="card">
                    <div class="card-body">
                        <i class="fas fa-database fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Sin Registros</h5>
                        <p class="text-muted">No se encontraron registros de asistencia en el período especificado.</p>
                    </div>
                </div>
            </div>
        `;
        document.getElementById('exportLink').classList.add('disabled');
        return;
    }

    // Enable export button
    document.getElementById('exportLink').classList.remove('disabled');

    const presentes = estadisticas.total_presentes;
    const ausentes = estadisticas.total_ausentes;
    const tardanzas = estadisticas.total_tardanzas || 0;
    const justificados = estadisticas.total_justificados || 0;

    // Build filter info
    let filtroInfo = '';
    if (params.tipo_reporte !== 'general') {
        filtroInfo = '<div class="alert alert-primary mt-2"><strong>Filtros aplicados:</strong><br>';
        // Add filter details...
        filtroInfo += '</div>';
    }

    document.getElementById('reporteContent').innerHTML = `
        <div class="alert alert-info">
            <i class="fas fa-info-circle mr-2"></i>
            Vista previa generada exitosamente para el período ${params.fecha_inicio} - ${params.fecha_fin}. Utiliza el botón "Exportar" para descargar el archivo completo.
        </div>
        ${filtroInfo}
        <div class="row">
            <div class="col-md-6">
                <h5><i class="fas fa-chart-bar mr-2"></i>Resumen Ejecutivo</h5>
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between">
                        Total de Registros <span class="badge badge-primary">${totalRegistros}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        Estudiantes Únicos <span class="badge badge-secondary">${estadisticasAdicionales.total_estudiantes_unicos || 0}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        Asistencia Promedio <span class="badge badge-success">${estadisticas.porcentaje_asistencia}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        Total Presentes <span class="badge badge-success">${presentes}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        Total Ausentes <span class="badge badge-danger">${ausentes}</span>
                    </li>
                </ul>
            </div>
            <div class="col-md-6">
                <h5><i class="fas fa-chart-pie mr-2"></i>Distribución por Tipo</h5>
                <div class="row text-center">
                    <div class="col-3">
                        <div class="card border-success">
                            <div class="card-body p-2">
                                <h6 class="text-success">${presentes}</h6>
                                <small class="text-muted">Presentes</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="card border-danger">
                            <div class="card-body p-2">
                                <h6 class="text-danger">${ausentes}</h6>
                                <small class="text-muted">Ausentes</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="card border-warning">
                            <div class="card-body p-2">
                                <h6 class="text-warning">${tardanzas}</h6>
                                <small class="text-muted">Tardanzas</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="card border-info">
                            <div class="card-body p-2">
                                <h6 class="text-info">${justificados}</h6>
                                <small class="text-muted">Justif.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
}

// Export report
function exportarReporte(event) {
    event.preventDefault();

    const formato = document.getElementById('formato').value;
    const params = {
        fecha_inicio: document.getElementById('fecha_inicio').value,
        fecha_fin: document.getElementById('fecha_fin').value,
        tipo_reporte: document.getElementById('tipo_reporte').value,
        formato: formato,
        nivel_id: document.getElementById('nivel_id')?.value || '',
        curso_id: document.getElementById('curso_id')?.value || '',
        estudiante_id: document.getElementById('estudiante_id')?.value || '',
        docente_id: document.getElementById('docente_id')?.value || ''
    };

    if (!params.fecha_inicio || !params.fecha_fin) {
        Swal.fire({
            icon: 'warning',
            title: 'Fechas requeridas',
            text: 'Por favor selecciona las fechas de inicio y fin.',
            confirmButtonText: 'Entendido'
        });
        return;
    }

    // Build URL
    let url = `/asistencia/exportar/${formato}/admin?`;
    const queryParams = new URLSearchParams(params);
    url += queryParams.toString();

    if (formato === 'pdf') {
        window.open(url, '_blank');
    } else {
        window.location.href = url;
    }
}
</script>
@endpush    const queryParams = new URLSearchParams(params);
