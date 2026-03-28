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

    // Generate appropriate filter HTML using pre-built options from PHP
    let html = '';
    switch(tipo) {
        case 'por_curso':
            html = '<div class="form-group"><label>Curso</label><select class="form-control filter-select" id="curso_id">' +
                   (window.reportesData.cursoOptions || '<option value="">Todos los cursos</option>') +
                   '</select></div>';
            break;
        case 'por_estudiante':
            html = '<div class="form-group"><label>Estudiante</label><select class="form-control filter-select" id="estudiante_id">' +
                   (window.reportesData.estudianteOptions || '<option value="">Todos los estudiantes</option>') +
                   '</select></div>';
            break;
        case 'por_docente':
            html = '<div class="form-group"><label>Docente</label><select class="form-control filter-select" id="docente_id">' +
                   (window.reportesData.docenteOptions || '<option value="">Todos los docentes</option>') +
                   '</select></div>';
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
            // Initialize with Select2
            $(select).select2({
                theme: 'bootstrap-5',
                width: '100%',
                allowClear: true,
                minimumResultsForSearch: 0,
                placeholder: function(){ return $(this).find('option:first').text(); }
            });

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
            console.log('Data structure check:', {
                hasSuccess: data.hasOwnProperty('success'),
                successValue: data.success,
                hasData: data.hasOwnProperty('data'),
                dataType: typeof data.data,
                dataKeys: data.data ? Object.keys(data.data) : 'no data'
            });

            if (data.success) {
                console.log('Calling populateDynamicOptions with:', data.data);
                populateDynamicOptions(data.data);
            } else {
                console.warn('API returned success=false:', data.message);
                document.getElementById('filtroContainer').innerHTML = '<div class="alert alert-warning"><i class="fas fa-exclamation-triangle mr-2"></i>No hay registros de asistencia para el período seleccionado.</div>';
            }
        })
        .catch(error => {
            console.error('Error loading dynamic filters:', error);
            $('#filtroContainer').html('<div class="alert alert-danger"><i class="fas fa-times mr-2"></i>Error al cargar filtros dinámicos. Revisa la consola para más detalles.</div>');
        });
}

function populateDynamicOptions(data) {
    console.log('Populating dynamic options with data:', data);

    // Get the container element
    const container = document.getElementById('filtroContainer');
    console.log('Container element found:', !!container);

    if (!container) {
        console.error('filtroContainer element not found!');
        return;
    }

    // Create the HTML content for filter dropdowns
    let html = `
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Nivel</label>
                    <select class="form-control filter-select" id="nivel_id">
                        <option value="">Seleccionar nivel...</option>`;

    // Populate levels
    if (data.niveles?.length) {
        data.niveles.forEach(n => {
            html += `<option value="${n.nivel_id}">${n.nombre}</option>`;
        });
        console.log('Populated niveles:', data.niveles.length);
    }

    html += `
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Curso</label>
                    <select class="form-control filter-select" id="curso_id">
                        <option value="">Seleccionar curso...</option>`;

    // Populate courses
    if (data.cursos?.length) {
        data.cursos.forEach(c => {
            const nombre = c.nombre_completo || `${c.grado?.nombre} ${c.seccion?.nombre}`;
            html += `<option value="${c.curso_id}" data-nivel="${c.grado?.nivel_id || ''}">${nombre}</option>`;
        });
        console.log('Populated cursos:', data.cursos.length);
    }

    html += `
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Estudiante</label>
                    <select class="form-control filter-select" id="estudiante_id">
                        <option value="">Seleccionar estudiante...</option>`;

    // Populate students
    if (data.estudiantes?.length) {
        data.estudiantes.forEach(e => {
            const nombre = `${e.persona?.apellidos || ''}, ${e.persona?.nombres || ''}`.trim();
            html += `<option value="${e.estudiante_id}" data-curso="${e.matricula?.curso_id || ''}" data-nivel="${e.matricula?.nivel_id || ''}">${nombre}</option>`;
        });
        console.log('Populated estudiantes:', data.estudiantes.length);
    }

    html += `
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Docente</label>
                    <select class="form-control filter-select" id="docente_id">
                        <option value="">Seleccionar docente...</option>`;

    // Populate teachers
    if (data.docentes?.length) {
        data.docentes.forEach(d => {
            const nombre = `${d.persona?.apellidos || ''}, ${d.persona?.nombres || ''}`.trim();
            html += `<option value="${d.profesor_id}">${nombre}</option>`;
        });
        console.log('Populated docentes:', data.docentes.length);
    }

    html += `
                    </select>
                </div>
            </div>
        </div>`;

    // Update the container with the new HTML
    console.log('Setting container HTML, length:', html.length);
    container.innerHTML = html;

    // Add event listeners to the new select elements
    setTimeout(() => {
        const filterSelects = container.querySelectorAll('.filter-select');
        console.log('Found filter selects:', filterSelects.length);

        filterSelects.forEach(select => {
            select.addEventListener('change', function() {
                console.log('Filter select changed:', this.id, this.value);
            });
        });

        // Setup cascading filters
        setupCascadingFilters();

        console.log('Filter selects initialized');
    }, 100);

    console.log('Dynamic options populated successfully');
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
        case 'anoActual':
            fechaInicio = new Date(hoy.getFullYear(), 0, 1); // January 1st of current year
            fechaFin = new Date(hoy.getFullYear(), 11, 31); // December 31st of current year
            break;
        case 'anoAnterior':
            fechaInicio = new Date(hoy.getFullYear() - 1, 0, 1); // January 1st of previous year
            fechaFin = new Date(hoy.getFullYear() - 1, 11, 31); // December 31st of previous year
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

    // Scroll to the report preview section
    setTimeout(() => {
        const previewElement = document.getElementById('reportePreview');
        if (previewElement) {
            previewElement.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
            console.log('Scrolled to report preview section');
        }
    }, 300); // Small delay to allow collapse animation to start

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

    // Save report to history
    saveReportToHistory(params, totalRegistros);

    console.log('Report preview rendered successfully');
}

// Load student ranking
function loadStudentRanking(params) {
    console.log('Loading student ranking from API...');

    const rankingContent = document.getElementById('rankingContent');
    rankingContent.innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border spinner-border-sm"></div>
            <p class="mt-2">Calculando ranking de estudiantes...</p>
        </div>
    `;

    // Build API URL with current filters
    const queryParams = new URLSearchParams(params);
    const rankingUrl = `/asistencia/api/ranking-estudiantes?${queryParams}`;

    console.log('Fetching ranking from:', rankingUrl);

    fetch(rankingUrl)
        .then(response => response.json())
        .then(data => {
            console.log('Ranking data received:', data);

            if (data.success && data.data && data.data.length > 0) {
                // Build ranking table
                let tableHtml = `
                    <div class="alert alert-info">
                        <i class="fas fa-trophy mr-2"></i>
                        Ranking de estudiantes por porcentaje de asistencia
                        <br><small class="text-muted">Período: ${params.fecha_inicio} - ${params.fecha_fin}</small>
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
                                    <th>Días Ausentes</th>
                                    <th>Días Totales</th>
                                </tr>
                            </thead>
                            <tbody>
                `;

                data.data.forEach((estudiante, index) => {
                    const position = index + 1;
                    const nombreCompleto = `${estudiante.nombres} ${estudiante.apellidos}`.trim();
                    const porcentajeClass = estudiante.porcentaje_asistencia >= 90 ? 'text-success' :
                                          estudiante.porcentaje_asistencia >= 80 ? 'text-warning' : 'text-danger';
                    const badgeClass = position === 1 ? 'badge-warning' :
                                     position === 2 ? 'badge-secondary' :
                                     position === 3 ? 'badge-danger' : '';

                    tableHtml += `
                        <tr>
                            <td>
                                ${badgeClass ? `<span class="badge ${badgeClass}">${position}</span>` : position}
                            </td>
                            <td>${nombreCompleto}</td>
                            <td>${estudiante.curso}</td>
                            <td><span class="${porcentajeClass}">${estudiante.porcentaje_asistencia}%</span></td>
                            <td>${estudiante.dias_presentes}</td>
                            <td>${estudiante.dias_ausentes}</td>
                            <td>${estudiante.dias_totales}</td>
                        </tr>
                    `;
                });

                tableHtml += `
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        <small class="text-muted">Los estudiantes con asistencia < 80% requieren atención especial</small>
                    </div>
                `;

                rankingContent.innerHTML = tableHtml;
            } else {
                rankingContent.innerHTML = `
                    <div class="alert alert-warning">
                        <i class="fas fa-info-circle mr-2"></i>
                        No hay suficientes datos de asistencia para generar el ranking en el período seleccionado.
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error loading student ranking:', error);
            rankingContent.innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Error al cargar el ranking de estudiantes: ${error.message}
                </div>
            `;
        });
}

// View report
function verReporte(reporteId) {
    console.log('Viewing report:', reporteId);
    // Show modal indicating they need to download for more details
    Swal.fire({
        icon: 'info',
        title: 'Información del Reporte',
        text: 'Para ver los detalles completos del reporte, por favor descargue el archivo.',
        confirmButtonText: 'Entendido'
    });
}

// Download report
function descargarReporte(reporteId) {
    console.log('Downloading report:', reporteId);

    // Redirect to the download URL
    const downloadUrl = `/asistencia/descargar-reporte/${reporteId}`;
    console.log('Redirecting to download URL:', downloadUrl);
    window.location.href = downloadUrl;
}

// Save complete report to history when generating preview (in selected format)
function saveReportToHistory(params, totalRegistros) {
    console.log('Saving complete report to history...');
    console.log('Report params:', params);
    console.log('Total registros:', totalRegistros);

    // Get selected format from the UI
    const formato = document.getElementById('formato').value;
    console.log('Selected format:', formato);

    // Generate and save complete report with physical file in selected format
    const saveData = {
        tipo_reporte: params.tipo_reporte || 'general',
        fecha_inicio: params.fecha_inicio,
        fecha_fin: params.fecha_fin,
        formato: formato, // Use selected format
        registros_totales: totalRegistros,
        filtros_aplicados: {
            nivel_id: params.nivel_id,
            curso_id: params.curso_id,
            estudiante_id: params.estudiante_id,
            docente_id: params.docente_id
        }
    };

    console.log('Sending complete report data:', saveData);

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    console.log('CSRF Token:', csrfToken ? 'Present' : 'Missing');

    // Save complete report with physical file in selected format
    fetch('/asistencia/guardar-reporte-exportado/' + formato, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken || ''
        },
        body: JSON.stringify(saveData)
    })
    .then(response => {
        console.log('Save complete report response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Save complete report response data:', data);
        if (data.success) {
            console.log('Complete report saved successfully:', data.reporte_id);
            // Refresh the reports list to show the new complete report
            refreshReportsList();
        } else {
            console.error('Error saving complete report:', data.message);
        }
    })
    .catch(error => {
        console.error('Error saving complete report:', error);
        // Don't show alert for saves, just log the error
    });
}

// Refresh the reports list
function refreshReportsList() {
    console.log('Refreshing reports list...');

    fetch('/asistencia/api/ultimos-reportes')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data) {
                updateReportsList(data.data);
            } else {
                console.error('Error refreshing reports list:', data.message);
            }
        })
        .catch(error => {
            console.error('Error refreshing reports list:', error);
        });
}

// Update the reports list in the UI
function updateReportsList(reportes) {
    console.log('Updating reports list UI...');

    const container = document.getElementById('ultimosReportesContent');

    if (!reportes || reportes.length === 0) {
        container.innerHTML = `
            <div class="text-center py-4">
                <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                <p class="text-muted">No hay reportes generados recientemente</p>
            </div>
        `;
        return;
    }

    let html = '<div class="list-group">';

    reportes.forEach(reporte => {
        const fechaInicio = new Date(reporte.fecha_inicio).toLocaleDateString('es-ES');
        const fechaFin = new Date(reporte.fecha_fin).toLocaleDateString('es-ES');
        const fechaGeneracion = new Date(reporte.fecha_generacion).toLocaleDateString('es-ES') +
                               ' ' + new Date(reporte.fecha_generacion).toLocaleTimeString('es-ES', {hour: '2-digit', minute: '2-digit'});

        html += `
            <div class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <strong>${reporte.tipo_reporte}</strong>
                    <br>
                    <small class="text-muted">
                        ${fechaInicio} - ${fechaFin}
                        | Generado: ${fechaGeneracion}
                    </small>
                </div>
                <div>
                    <button class="btn btn-sm btn-outline-primary mr-2" onclick="verReporte(${reporte.id})">
                        <i class="fas fa-eye"></i> Ver
                    </button>
                    <button class="btn btn-sm btn-outline-success" onclick="descargarReporte(${reporte.id})">
                        <i class="fas fa-download"></i> Descargar
                    </button>
                </div>
            </div>
        `;
    });

    html += '</div>';
    container.innerHTML = html;

    console.log('Reports list updated successfully');
}

// Export report (save to history and download)
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

    console.log('Export params:', params);
    console.log('Formato selected:', formato);

    // First, save the report to database and file system
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    fetch('/asistencia/guardar-reporte-exportado/' + formato, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken || ''
        },
        body: JSON.stringify(params)
    })
    .then(response => response.json())
    .then(data => {
        console.log('Save report response:', data);

        if (data.success) {
            console.log('Report saved successfully, now opening download...');

            // Build URL - map formato to route path
            const routeFormato = formato === 'xlsx' ? 'excel' : formato;
            let url = `/asistencia/exportar/${routeFormato}/admin?`;
            const queryParams = new URLSearchParams(params);
            url += queryParams.toString();

            // Add timestamp to prevent caching
            url += '&t=' + Date.now();

            console.log('Final Export URL:', url);
            console.log('Route formato:', routeFormato);

            // Open in new tab to ensure proper download handling
            console.log('Opening export URL in new tab...');
            window.open(url, '_blank');

            // Refresh reports list to show the new saved report
            refreshReportsList();
        } else {
            console.error('Error saving report:', data.message);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al guardar el reporte: ' + data.message,
                confirmButtonText: 'Entendido'
            });
        }
    })
    .catch(error => {
        console.error('Error saving report:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error al guardar el reporte: ' + error.message,
            confirmButtonText: 'Entendido'
        });
    });
}

            // Build URL - map formato to route path
