console.log('Reportes Asistencia - Loading...');

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM ready - Initializing reports system...');
    initReports();
});

function initReports() {
    console.log('Initializing reports...');

    try {
        // Set default period
        setPeriodo('mesAnterior');

        // Initialize UI
        initUI();

        // Setup events
        setupEvents();

        // Initialize charts
        initCharts();

        console.log('Reports initialized successfully');

    } catch (error) {
        console.error('Error initializing reports:', error);
    }
}

function initUI() {
    console.log('Initializing UI components...');

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

    // Initialize Select2 for main selects (only if jQuery and Select2 are available)
    if (typeof $ !== 'undefined' && typeof $.fn !== 'undefined' && typeof $.fn.select2 !== 'undefined') {
        console.log('jQuery and Select2 available, initializing...');
        $('#tipo_reporte, #formato').select2({
            theme: 'bootstrap-5',
            width: '100%',
            minimumResultsForSearch: 0
        });
        console.log('Select2 initialized successfully');
    } else {
        console.log('jQuery or Select2 not available, skipping Select2 initialization');
        console.log('jQuery available:', typeof $ !== 'undefined');
        console.log('jQuery.fn available:', typeof $.fn !== 'undefined');
        console.log('Select2 available:', typeof $.fn !== 'undefined' && typeof $.fn.select2 !== 'undefined');
    }

    console.log('UI components initialized');
}

function setupEvents() {
    console.log('Setting up event handlers...');

    // Report type change handler - use direct event listener
    const tipoReporteSelect = document.getElementById('tipo_reporte');
    if (tipoReporteSelect) {
        tipoReporteSelect.addEventListener('change', function() {
            console.log('Tipo de reporte changed:', this.value);
            handleReportTypeChange();
        });
    }

    // Dynamic filters update on date change - use direct event listeners
    const fechaInicioInput = document.getElementById('fecha_inicio');
    const fechaFinInput = document.getElementById('fecha_fin');

    if (fechaInicioInput) {
        fechaInicioInput.addEventListener('change', function() {
            console.log('Fecha inicio changed');
            loadDynamicFilters();
        });
    }

    if (fechaFinInput) {
        fechaFinInput.addEventListener('change', function() {
            console.log('Fecha fin changed');
            loadDynamicFilters();
        });
    }

    // Quick period buttons - use direct event listeners
    const periodoButtons = document.querySelectorAll('.periodo-btn');
    periodoButtons.forEach(button => {
        const periodo = button.getAttribute('data-periodo');
        button.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Quick period button clicked:', periodo);
            setPeriodo(periodo);
        });
    });

    // Clear dates button
    const limpiarBtn = document.querySelector('.limpiar-btn');
    if (limpiarBtn) {
        limpiarBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Clear dates button clicked');
            limpiarFechas();
        });
    }

    // Generate report button
    const generarBtn = document.getElementById('generarReporteBtn');
    if (generarBtn) {
        generarBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Generate report button clicked');
            generarReporte();
        });
    }

    console.log('Event handlers set up successfully');
}

function handleReportTypeChange() {
    const tipoSelect = document.getElementById('tipo_reporte');
    const tipo = tipoSelect ? tipoSelect.value : 'general';
    const filtrosDiv = document.getElementById('filtrosAdicionales');
    const container = document.getElementById('filtroContainer');

    console.log('Handling report type change:', tipo);

    if (tipo === 'general') {
        if (filtrosDiv) filtrosDiv.style.display = 'none';
        return;
    }

    if (filtrosDiv) filtrosDiv.style.display = 'block';

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

    if (container) {
        container.innerHTML = tipo === 'comparativo' ?
            '<div class="alert alert-info"><i class="fas fa-calendar-alt mr-2"></i>Selecciona fechas para cargar filtros con datos de asistencia.</div>' : html;
    }

    // Initialize Select2 for filter selects
    setTimeout(() => {
        const filterSelects = document.querySelectorAll('.filter-select');
        filterSelects.forEach(select => {
            // Basic select functionality without Select2 for now
            select.addEventListener('change', function() {
                console.log('Filter select changed:', this.id, this.value);
            });
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

    console.log('Loading dynamic filters for dates:', fechaInicio, 'to', fechaFin);

    $('#filtroContainer').html('<div class="text-center py-3"><div class="spinner-border spinner-border-sm"></div><small> Cargando opciones con registros de asistencia...</small></div>');

    // Fetch only options that have attendance records for the selected period
    fetch(`/asistencia/api/filtros-dinamicos?fecha_inicio=${fechaInicio}&fecha_fin=${fechaFin}`)
        .then(response => {
            console.log('API response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('API response data:', data);
            if (data.success) {
                populateDynamicOptions(data.data);
            } else {
                console.warn('API returned success=false:', data.message);
                $('#filtroContainer').html('<div class="alert alert-warning"><i class="fas fa-exclamation-triangle mr-2"></i>No hay registros de asistencia para el período seleccionado.</div>');
            }
        })
        .catch(error => {
            console.error('Error loading dynamic filters:', error);
            $('#filtroContainer').html('<div class="alert alert-danger"><i class="fas fa-times mr-2"></i>Error al cargar filtros dinámicos. Revisa la consola para más detalles.</div>');
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

    // Reinitialize Select2 with proper theming (only if available)
    if (typeof $ !== 'undefined' && typeof $.fn !== 'undefined' && typeof $.fn.select2 !== 'undefined') {
        $('.filter-select').select2({
            theme: 'bootstrap-5',
            width: '100%',
            allowClear: true,
            minimumResultsForSearch: 0,
            placeholder: function(){ return $(this).find('option:first').text(); }
        });
        console.log('Filter Select2 initialized successfully');
    } else {
        console.log('Select2 not available for filter selects, using basic functionality');
    }
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

function initCharts() {
    console.log('Initializing charts...');

    try {
        // Get data from window.reportesData (passed from PHP)
        const tendenciaData = (window.reportesData && window.reportesData.tendenciaMensual) ? window.reportesData.tendenciaMensual : [];
        const distribucionData = (window.reportesData && window.reportesData.distribucionTipos) ? window.reportesData.distribucionTipos : {presente: 0, ausente: 0, tarde: 0, justificado: 0};

        console.log('Tendencia data:', tendenciaData);
        console.log('Distribucion data:', distribucionData);

        // Initialize tendency chart
        const ctxTendencia = document.getElementById('tendenciaChart');
        if (ctxTendencia) {
            const ctx = ctxTendencia.getContext('2d');

            // Ensure we have valid data for the chart
            const labels = tendenciaData.length > 0 ? tendenciaData.map(item => item.mes || 'N/A') : ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'];
            const data = tendenciaData.length > 0 ? tendenciaData.map(item => item.porcentaje || 0) : [0, 0, 0, 0, 0, 0];

            window.tendenciaChartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Asistencia Promedio (%)',
                        data: data,
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
            console.log('Tendency chart initialized successfully');
        } else {
            console.error('Tendency chart canvas not found');
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
                            distribucionData.presente || 0,
                            distribucionData.ausente || 0,
                            distribucionData.tarde || 0,
                            distribucionData.justificado || 0
                        ],
                        backgroundColor: ['#28a745', '#dc3545', '#ffc107', '#17a2b8']
                    }]
                },
                options: { responsive: true }
            });
            console.log('Distribution chart initialized successfully');
        } else {
            console.error('Distribution chart canvas not found');
        }

        console.log('Charts initialization completed');

    } catch (error) {
        console.error('Error initializing charts:', error);
    }
}

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
    console.log('Setting period:', tipo);
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

    console.log('Setting dates:', fechaInicioStr, 'to', fechaFinStr);

    const fechaInicioInput = document.getElementById('fecha_inicio');
    const fechaFinInput = document.getElementById('fecha_fin');

    if (fechaInicioInput && fechaFinInput) {
        fechaInicioInput.value = fechaInicioStr;
        fechaFinInput.value = fechaFinStr;

        // Trigger change events
        fechaInicioInput.dispatchEvent(new Event('change'));
        fechaFinInput.dispatchEvent(new Event('change'));

        console.log('Dates set successfully');
    } else {
        console.error('Date inputs not found');
    }
}

// Clear dates
function limpiarFechas() {
    console.log('Clearing dates');
    document.getElementById('fecha_inicio').value = '';
    document.getElementById('fecha_fin').value = '';
}

// Generate report preview
function generarReporte() {
    console.log('Generating report...');

    const fechaInicio = document.getElementById('fecha_inicio').value;
    const fechaFin = document.getElementById('fecha_fin').value;

    if (!fechaInicio || !fechaFin) {
        console.warn('Missing dates, showing warning');
        Swal.fire({
            icon: 'warning',
            title: 'Período requerido',
            text: 'Por favor selecciona las fechas de inicio y fin para generar el reporte.',
            confirmButtonText: 'Entendido'
        });
        return;
    }

    // Show preview section
    console.log('Showing preview section');
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

    console.log('Report params:', params);

    // Generate report content
    generateReportContent(params);
}

// Generate report content
function generateReportContent(params) {
    console.log('Generating report content...');

    // Build API URL
    const queryParams = new URLSearchParams(params);
    const estadisticasUrl = `/asistencia/api/estadisticas-filtradas?${queryParams}`;
    const tablaUrl = `/asistencia/api/tabla-asistencias?${queryParams}&per_page=1000`;

    // Fetch statistics
    fetch(estadisticasUrl)
        .then(r => r.json())
        .then(estadisticasData => {
            console.log('Statistics data received:', estadisticasData);
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
            console.log('Table data received:', tablaData);
            if (tablaData.success) {
                renderReportPreview(tablaData, params);
            } else {
                throw new Error(tablaData.message || 'Error al obtener datos de tabla');
            }
        })
        .catch(error => {
            console.error('Error generating report:', error);
            document.getElementById('reporteContent').innerHTML =
                `<div class="alert alert-danger"><i class="fas fa-exclamation-triangle mr-2"></i>Error al generar la vista previa: ${error.message}</div>`;
        });
}

// Render report preview
function renderReportPreview(tablaData, params) {
    console.log('Rendering report preview...');

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

    document.getElementById('reporteContent').innerHTML = `
        <div class="alert alert-info">
            <i class="fas fa-info-circle mr-2"></i>
            Vista previa generada exitosamente para el período ${params.fecha_inicio} - ${params.fecha_fin}.
        </div>
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

    // Show ranking section if there are students
    if (estadisticasAdicionales.total_estudiantes_unicos > 0) {
        document.getElementById('rankingEstudiantes').style.display = 'block';
        loadStudentRanking(params);
    }

    console.log('Report preview rendered successfully');
}

// Load student ranking
function loadStudentRanking(params) {
    console.log('Loading student ranking...');

    // For demo purposes, create a mock ranking based on available data
    // In a real implementation, this would come from a dedicated API endpoint
    const rankingContent = document.getElementById('rankingContent');
    rankingContent.innerHTML = `
        <div class="alert alert-info">
            <i class="fas fa-trophy mr-2"></i>
            Ranking basado en asistencia del período seleccionado
        </div>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Estudiante</th>
                        <th>Curso</th>
                        <th>% Asistencia</th>
                        <th>Días Presentes</th>
                        <th>Días Totales</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="badge badge-warning">1</span></td>
                        <td>Estudiante Ejemplo 1</td>
                        <td>1° Básico A</td>
                        <td><span class="text-success">95%</span></td>
                        <td>19</td>
                        <td>20</td>
                    </tr>
                    <tr>
                        <td><span class="badge badge-secondary">2</span></td>
                        <td>Estudiante Ejemplo 2</td>
                        <td>1° Básico B</td>
                        <td><span class="text-success">92%</span></td>
                        <td>18</td>
                        <td>20</td>
                    </tr>
                    <tr>
                        <td><span class="badge badge-danger">3</span></td>
                        <td>Estudiante Ejemplo 3</td>
                        <td>1° Básico A</td>
                        <td><span class="text-warning">85%</span></td>
                        <td>17</td>
                        <td>20</td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>Estudiante Ejemplo 4</td>
                        <td>1° Básico C</td>
                        <td><span class="text-warning">80%</span></td>
                        <td>16</td>
                        <td>20</td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>Estudiante Ejemplo 5</td>
                        <td>1° Básico B</td>
                        <td><span class="text-danger">75%</span></td>
                        <td>15</td>
                        <td>20</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="text-center mt-3">
            <small class="text-muted">Los estudiantes con asistencia < 80% requieren atención especial</small>
        </div>
    `;
}

// View report
function verReporte(reporteId) {
    console.log('Viewing report:', reporteId);
    // In a real implementation, this would open the report in a new tab or modal
    Swal.fire({
        icon: 'info',
        title: 'Ver Reporte',
        text: `Funcionalidad para ver el reporte ${reporteId} próximamente disponible.`,
        confirmButtonText: 'Entendido'
    });
}

// Download report
function descargarReporte(reporteId) {
    console.log('Downloading report:', reporteId);
    // In a real implementation, this would download the report file
    Swal.fire({
        icon: 'success',
        title: 'Descarga Iniciada',
        text: `Descargando reporte ${reporteId}...`,
        timer: 2000,
        showConfirmButton: false
    });
}

// Export report
function exportarReporte(event) {
    console.log('Exporting report...');

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

    console.log('Export URL:', url);

    if (formato === 'pdf') {
        window.open(url, '_blank');
    } else {
        window.location.href = url;
    }
}
