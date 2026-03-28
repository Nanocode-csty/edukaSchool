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

<script>
    // Optimized and consolidated initialization
    document.addEventListener('DOMContentLoaded', function() {
        console.log('🚀 Inicializando reportes de asistencia...');

        // Wait for libraries
        const waitForLibs = setInterval(() => {
            if (typeof $ !== 'undefined' && $.fn.select2 && typeof Chart !== 'undefined') {
                clearInterval(waitForLibs);
                initApp();
            }
        }, 100);

        setTimeout(() => {
            clearInterval(waitForLibs);
            console.warn('⚠️ Timeout - inicializando sin verificar librerías');
            initApp();
        }, 3000);
    });

    function initApp() {
        // Set default dates (previous month)
        setPeriodo('mesAnterior');

        // Initialize UI
        initUI();

        // Initialize charts
        initCharts();

        // Setup event listeners
        setupEvents();

        console.log('✅ Reportes inicializados');
    }

    function initUI() {
        // Collapse functionality
        const collapseBtn = document.querySelector('[data-target="#collapseReportes"]');
        const collapseIcon = collapseBtn?.querySelector('.fas');
        const collapse = document.getElementById('collapseReportes');

        if (collapse) {
            collapse.addEventListener('show.bs.collapse', () => {
                collapseIcon?.classList.replace('fa-chevron-down', 'fa-chevron-up');
            });
            collapse.addEventListener('hide.bs.collapse', () => {
                collapseIcon?.classList.replace('fa-chevron-up', 'fa-chevron-down');
            });
        }
    }

    function setupEvents() {
        // Report type change
        document.getElementById('tipo_reporte')?.addEventListener('change', handleReportTypeChange);

        // Dynamic filters
        setupDynamicFilters();

        // Chart filters
        setupChartFilters();
    }

    function handleReportTypeChange() {
        const tipo = this.value;
        const filtrosDiv = document.getElementById('filtrosAdicionales');
        const container = document.getElementById('filtroContainer');

        if (!filtrosDiv || !container) return;

        if (tipo === 'general') {
            filtrosDiv.style.display = 'none';
            return;
        }

        filtrosDiv.style.display = 'flex';

        // Generate filter HTML based on type
        let html = '';
        switch(tipo) {
            case 'por_curso':
                html = `<div class="form-group"><label>Curso</label><select class="form-control filter-select" id="curso_id"><option value="">Todos los cursos</option></select></div>`;
                break;
            case 'por_estudiante':
                html = `<div class="form-group"><label>Estudiante</label><select class="form-control filter-select" id="estudiante_id"><option value="">Todos los estudiantes</option></select></div>`;
                break;
            case 'por_docente':
                html = `<div class="form-group"><label>Docente</label><select class="form-control filter-select" id="docente_id"><option value="">Todos los docentes</option></select></div>`;
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
                break;
        }

        container.innerHTML = tipo === 'comparativo' ?
            '<div class="alert alert-info"><i class="fas fa-calendar-alt mr-2"></i>Selecciona fechas para cargar filtros.</div>' : html;

        // Initialize Select2 for filter selects
        setTimeout(() => {
            $('.filter-select').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: function(){ return $(this).find('option:first').text(); },
                allowClear: true,
                minimumResultsForSearch: 0
            });

            if (tipo === 'comparativo') {
                setupDynamicFilters();
            }
        }, 100);
    }

    function setupDynamicFilters() {
        const fechaInicio = document.getElementById('fecha_inicio');
        const fechaFin = document.getElementById('fecha_fin');

        if (!fechaInicio || !fechaFin) return;

        function updateFilters() {
            if (!fechaInicio.value || !fechaFin.value) return;

            // Show loading
            const container = document.getElementById('filtroContainer');
            if (container) {
                container.innerHTML = '<div class="text-center py-3"><div class="spinner-border spinner-border-sm"></div><small> Actualizando filtros...</small></div>';
            }

            // Fetch dynamic filter data
            fetch(`/asistencia/api/filtros-dinamicos?fecha_inicio=${fechaInicio.value}&fecha_fin=${fechaFin.value}`)
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        updateFilterOptions(data.data);
                        setupCascadingFilters();
                    }
                })
                .catch(console.error);
        }

        fechaInicio.addEventListener('change', updateFilters);
        fechaFin.addEventListener('change', updateFilters);

        // Auto-update if dates are already set
        if (fechaInicio.value && fechaFin.value && document.getElementById('tipo_reporte').value === 'comparativo') {
            updateFilters();
        }
    }

    function updateFilterOptions(data) {
        // Update levels
        if (data.niveles) {
            const select = document.getElementById('nivel_id');
            if (select) {
                select.innerHTML = '<option value="">Seleccionar nivel...</option>';
                data.niveles.forEach(n => {
                    select.innerHTML += `<option value="${n.nivel_id}">${n.nombre}</option>`;
                });
            }
        }

        // Update courses
        if (data.cursos) {
            const select = document.getElementById('curso_id');
            if (select) {
                select.innerHTML = '<option value="">Seleccionar curso...</option>';
                data.cursos.forEach(c => {
                    const nombre = c.nombre_completo || `${c.grado?.nombre} ${c.seccion?.nombre}`;
                    select.innerHTML += `<option value="${c.curso_id}" data-nivel="${c.grado?.nivel_id}">${nombre}</option>`;
                });
            }
        }

        // Update students
        if (data.estudiantes) {
            const select = document.getElementById('estudiante_id');
            if (select) {
                select.innerHTML = '<option value="">Seleccionar estudiante...</option>';
                data.estudiantes.forEach(e => {
                    const nombre = `${e.persona?.apellidos}, ${e.persona?.nombres}`;
                    select.innerHTML += `<option value="${e.estudiante_id}" data-curso="${e.matricula?.curso_id}" data-nivel="${e.matricula?.nivel_id}">${nombre}</option>`;
                });
            }
        }

        // Update teachers
        if (data.docentes) {
            const select = document.getElementById('docente_id');
            if (select) {
                select.innerHTML = '<option value="">Seleccionar docente...</option>';
                data.docentes.forEach(d => {
                    const nombre = `${d.persona?.apellidos}, ${d.persona?.nombres}`;
                    select.innerHTML += `<option value="${d.profesor_id}">${nombre}</option>`;
                });
            }
        }

        // Reinitialize Select2
        $('.filter-select').select2({
            theme: 'bootstrap-5',
            width: '100%',
            allowClear: true,
            minimumResultsForSearch: 0
        });
    }

    function setupCascadingFilters() {
        // Level -> Course filtering
        $('#nivel_id').on('change', function() {
            const nivelId = $(this).val();
            const cursoSelect = $('#curso_id');

            cursoSelect.find('option').each(function() {
                const option = $(this);
                if (option.val()) {
                    const show = !nivelId || option.data('nivel') == nivelId;
                    option.toggle(show);
                }
            });
            cursoSelect.val('').trigger('change');
        });

        // Course -> Student filtering
        $('#curso_id').on('change', function() {
            const cursoId = $(this).val();
            const estudianteSelect = $('#estudiante_id');

            estudianteSelect.find('option').each(function() {
                const option = $(this);
                if (option.val()) {
                    const show = !cursoId || option.data('curso') == cursoId;
                    option.toggle(show);
                }
            });
            estudianteSelect.val('').trigger('change');
        });
    }

    function setupChartFilters() {
        ['fecha_inicio', 'fecha_fin', 'tipo_reporte', 'nivel_id', 'curso_id', 'estudiante_id', 'docente_id']
            .forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    el.addEventListener('change', updateCharts);
                    if (el.type === 'date') {
                        el.addEventListener('input', updateCharts);
                    }
                }
            });
    }

    function updateCharts() {
        const params = {
            fecha_inicio: document.getElementById('fecha_inicio')?.value,
            fecha_fin: document.getElementById('fecha_fin')?.value,
            tipo_reporte: document.getElementById('tipo_reporte')?.value,
            nivel_id: document.getElementById('nivel_id')?.value,
            curso_id: document.getElementById('curso_id')?.value,
            estudiante_id: document.getElementById('estudiante_id')?.value,
            docente_id: document.getElementById('docente_id')?.value
        };

        if (!params.fecha_inicio || !params.fecha_fin) return;

        const query = new URLSearchParams(params).toString();
        fetch(`/asistencia/api/estadisticas-filtradas?${query}`)
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    updateChartData(data.tendencia_mensual, data.distribucion_tipos);
                }
            })
            .catch(console.error);
    }

                // Función global para configurar event listeners de filtros dinámicos
                function agregarEventListenersFiltrosDinamicos() {
                    // Escuchar cambios en las fechas para actualizar filtros dinámicos
                    const fechaInicioInput = document.getElementById('fecha_inicio');
                    const fechaFinInput = document.getElementById('fecha_fin');

                    // Función para actualizar filtros cuando cambien las fechas
                    function actualizarFiltrosDinamicos() {
                        const fechaInicio = fechaInicioInput.value;
                        const fechaFin = fechaFinInput.value;

                        console.log('=== actualizarFiltrosDinamicos LLAMADA ===');
                        console.log('fechaInicioInput exists:', !!fechaInicioInput);
                        console.log('fechaFinInput exists:', !!fechaFinInput);
                        console.log('fechaInicio value:', fechaInicio, 'length:', fechaInicio.length);
                        console.log('fechaFin value:', fechaFin, 'length:', fechaFin.length);

                        // Solo actualizar si ambas fechas están seleccionadas
                        if (!fechaInicio || !fechaFin) {
                            console.log('Fechas no completas, no se actualizan filtros');
                            return;
                        }

                        console.log('Actualizando filtros dinámicos para fechas:', fechaInicio, 'a', fechaFin);

                        // Mostrar loading en los filtros (sin reemplazar el contenido completo)
                        const filtroContainer = document.getElementById('filtroContainer');
                        if (filtroContainer) {
                            // Agregar overlay de loading sin reemplazar los filtros
                            const loadingOverlay = document.createElement('div');
                            loadingOverlay.id = 'loading-overlay';
                            loadingOverlay.className = 'position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-white bg-opacity-75';
                            loadingOverlay.style.cssText = 'z-index: 10;';
                            loadingOverlay.innerHTML = '<div class="text-center"><div class="spinner-border spinner-border-sm text-primary" role="status"></div><small class="text-muted ml-2">Actualizando filtros...</small></div>';

                            filtroContainer.style.position = 'relative';
                            filtroContainer.appendChild(loadingOverlay);
                        }

                        // Llamar a la API para obtener filtros dinámicos
                        const filtrosUrl = `/asistencia/api/filtros-dinamicos?fecha_inicio=${encodeURIComponent(fechaInicio)}&fecha_fin=${encodeURIComponent(fechaFin)}`;

                        fetch(filtrosUrl, {
                            method: 'GET',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                console.log('Filtros dinámicos obtenidos:', data.data);
                                console.log('Niveles:', data.data.niveles?.length || 0, 'Cursos:', data.data.cursos?.length || 0, 'Estudiantes:', data.data.estudiantes?.length || 0, 'Docentes:', data.data.docentes?.length || 0);
                                actualizarOpcionesFiltros(data.data);
                            } else {
                                console.error('Error al obtener filtros dinámicos:', data.message);
                                // Mostrar mensaje de error al usuario
                                const filtroContainer = document.getElementById('filtroContainer');
                                if (filtroContainer) {
                                    filtroContainer.innerHTML = '<div class="alert alert-warning"><i class="fas fa-exclamation-triangle mr-2"></i>No se pudieron cargar los filtros dinámicos. Verifica que hayas seleccionado fechas válidas y que existan registros de asistencia en ese período.</div>';
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Error al actualizar filtros dinámicos:', error);
                        });
                    }

                    console.log('Configurando event listeners para fechas...');
                    console.log('fechaInicioInput:', fechaInicioInput);
                    console.log('fechaFinInput:', fechaFinInput);

                    // Agregar event listeners para cambios en fechas
                    fechaInicioInput.addEventListener('change', function(e) {
                        console.log('🎯 Evento change en fechaInicio:', e.target.value);
                        actualizarFiltrosDinamicos();
                    });

                    fechaFinInput.addEventListener('change', function(e) {
                        console.log('🎯 Evento change en fechaFin:', e.target.value);
                        actualizarFiltrosDinamicos();
                    });

                    console.log('Event listeners configurados exitosamente');

                    // También escuchar input para actualizaciones en tiempo real
                    fechaInicioInput.addEventListener('input', function(e) {
                        console.log('Evento input en fechaInicio:', e.target.value);
                        // Solo actualizar si la fecha fin también está seleccionada
                        if (fechaFinInput.value) {
                            actualizarFiltrosDinamicos();
                        }
                    });

                    fechaFinInput.addEventListener('input', function(e) {
                        console.log('Evento input en fechaFin:', e.target.value);
                        // Solo actualizar si la fecha inicio también está seleccionada
                        if (fechaInicioInput.value) {
                            actualizarFiltrosDinamicos();
                        }
                    });

                    console.log('Event listeners configurados. Verificando fechas actuales...');

                    // Verificar si ya hay fechas seleccionadas y llamar inmediatamente
                    const fechaInicioActual = fechaInicioInput.value;
                    const fechaFinActual = fechaFinInput.value;
                    console.log('Fechas actuales - Inicio:', fechaInicioActual, 'Fin:', fechaFinActual);

                    if (fechaInicioActual && fechaFinActual) {
                        console.log('Fechas ya seleccionadas, llamando actualizarFiltrosDinamicos inmediatamente');
                        // Only call if we're on comparative report type (where filters exist)
                        const tipoReporte = document.getElementById('tipo_reporte').value;
                        if (tipoReporte === 'comparativo') {
                            actualizarFiltrosDinamicos();
                        }
                    }
                }

                // Variable global para almacenar los datos de la API
                let datosFiltrosDinamicos = null;

                // Función global para actualizar opciones de filtros
                function actualizarOpcionesFiltros(data) {
                    console.log('Actualizando opciones de filtros con data:', data);

                    // Guardar los datos para usar en filtros en cascada
                    datosFiltrosDinamicos = data;

                    // Si es reporte comparativo, insertar el HTML de filtros primero
                    if (document.getElementById('tipo_reporte').value === 'comparativo') {
                        console.log('Insertando HTML de filtros comparativos');
                        const filtroContainer = document.getElementById('filtroContainer');
                        if (filtroContainer) {
                            // Insertar el HTML de filtros comparativos
                            filtroContainer.innerHTML = `
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nivel_id">Nivel Educativo</label>
                                            <select class="form-control" id="nivel_id" name="nivel_id">
                                                <option value="">Seleccionar nivel...</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="curso_id">Curso</label>
                                            <select class="form-control" id="curso_id" name="curso_id">
                                                <option value="">Seleccionar curso...</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="estudiante_id">Estudiante</label>
                                            <select class="form-control" id="estudiante_id" name="estudiante_id">
                                                <option value="">Seleccionar estudiante...</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="docente_id">Docente</label>
                                            <select class="form-control" id="docente_id" name="docente_id">
                                                <option value="">Seleccionar docente...</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            `;

                            // Inicializar Select2 para los nuevos elementos solo si existen
                            setTimeout(() => {
                                $('.form-control').each(function() {
                                    const elementId = $(this).attr('id');
                                    if ($(this).length > 0 && elementId !== 'tipo_reporte' && elementId !== 'formato' && elementId !== 'fecha_inicio' && elementId !== 'fecha_fin') {
                                        if (typeof $ !== 'undefined' && $.fn.select2) {
                                            $(this).select2({
                                                theme: 'bootstrap-5',
                                                width: '100%',
                                                placeholder: function() {
                                                    return $(this).find('option:first').text() || 'Seleccionar...';
                                                },
                                                allowClear: true,
                                                minimumResultsForSearch: 0
                                            });
                                        } else {
                                            console.warn('Select2 not available for element:', elementId);
                                        }
                                    }
                                });
                                console.log('Select2 inicializado para filtros comparativos');
                            }, 100);
                        }
                    }

                    // Actualizar opciones de niveles
                    if (data.niveles) {
                        const nivelSelect = document.getElementById('nivel_id');
                        if (nivelSelect) {
                            console.log('Actualizando select de niveles, opciones actuales:', nivelSelect.options.length);
                            // Limpiar opciones existentes (excepto la primera)
                            while (nivelSelect.options.length > 1) {
                                nivelSelect.remove(1);
                            }

                            // Agregar nuevas opciones
                            data.niveles.forEach(nivel => {
                                const option = document.createElement('option');
                                option.value = nivel.nivel_id;
                                option.textContent = nivel.nombre;
                                nivelSelect.appendChild(option);
                            });

                            // Select nativo, no necesita reinicialización
                            console.log('Niveles actualizados correctamente');
                        } else {
                            console.warn('Select de niveles no encontrado');
                        }
                    }

                    // Actualizar opciones de cursos
                    if (data.cursos) {
                        const cursoSelect = document.getElementById('curso_id');
                        if (cursoSelect) {
                            console.log('Actualizando select de cursos, opciones actuales:', cursoSelect.options.length);
                            // Limpiar opciones existentes (excepto la primera)
                            while (cursoSelect.options.length > 1) {
                                cursoSelect.remove(1);
                            }

                            // Agregar nuevas opciones
                            data.cursos.forEach(curso => {
                                const option = document.createElement('option');
                                option.value = curso.curso_id;
                                option.setAttribute('data-nivel', curso.grado?.nivel_id || '');
                                option.textContent = curso.nombre_completo || (curso.grado?.nombre + ' ' + curso.seccion?.nombre);
                                cursoSelect.appendChild(option);
                            });

                            console.log('Cursos actualizados correctamente');
                        } else {
                            console.warn('Select de cursos no encontrado');
                        }
                    }

                    // Actualizar opciones de estudiantes
                    if (data.estudiantes) {
                        const estudianteSelect = document.getElementById('estudiante_id');
                        if (estudianteSelect) {
                            console.log('Actualizando select de estudiantes, opciones actuales:', estudianteSelect.options.length);
                            // Limpiar opciones existentes (excepto la primera)
                            while (estudianteSelect.options.length > 1) {
                                estudianteSelect.remove(1);
                            }

                            // Agregar nuevas opciones
                            data.estudiantes.forEach(estudiante => {
                                const option = document.createElement('option');
                                option.value = estudiante.estudiante_id;
                                option.setAttribute('data-curso', estudiante.matricula?.curso_id || '');
                                option.setAttribute('data-nivel', estudiante.matricula?.nivel_id || '');
                                option.textContent = estudiante.persona?.apellidos + ', ' + estudiante.persona?.nombres;
                                estudianteSelect.appendChild(option);
                            });

                            console.log('Estudiantes actualizados correctamente');
                        } else {
                            console.warn('Select de estudiantes no encontrado');
                        }
                    }

                    // Actualizar opciones de docentes
                    if (data.docentes) {
                        const docenteSelect = document.getElementById('docente_id');
                        if (docenteSelect) {
                            console.log('Actualizando select de docentes, opciones actuales:', docenteSelect.options.length);
                            // Limpiar opciones existentes (excepto la primera)
                            while (docenteSelect.options.length > 1) {
                                docenteSelect.remove(1);
                            }

                            // Agregar nuevas opciones
                            data.docentes.forEach(docente => {
                                const option = document.createElement('option');
                                option.value = docente.profesor_id;
                                option.textContent = docente.persona?.apellidos + ', ' + docente.persona?.nombres;
                                docenteSelect.appendChild(option);
                            });

                            console.log('Docentes actualizados correctamente');
                        } else {
                            console.warn('Select de docentes no encontrado');
                        }
                    }

                    // Re-inicializar filtros en cascada después de actualizar las opciones
                    if (document.getElementById('tipo_reporte').value === 'comparativo') {
                        console.log('Re-inicializando filtros en cascada después de actualización dinámica');
                        setTimeout(() => {
                            configurarFiltrosCascada();
                        }, 100);
                    }

                    console.log('Filtros dinámicos actualizados exitosamente');
                }

                // Función global para configurar filtros en cascada
                function configurarFiltrosCascada() {
                    console.log('Configurando filtros en cascada...');

                    const nivelSelect = document.getElementById('nivel_id');
                    const cursoSelect = document.getElementById('curso_id');
                    const estudianteSelect = document.getElementById('estudiante_id');
                    const docenteSelect = document.getElementById('docente_id');

                    console.log('Elementos encontrados:', {
                        nivel: !!nivelSelect,
                        curso: !!cursoSelect,
                        estudiante: !!estudianteSelect,
                        docente: !!docenteSelect
                    });

                    // Si no se encontraron los elementos, salir
                    if (!nivelSelect || !cursoSelect || !estudianteSelect || !docenteSelect) {
                        console.warn('No se encontraron todos los elementos select para filtros en cascada');
                        return;
                    }

                    // Función para obtener opciones actuales (dinámicas)
                    function getOpcionesActuales(selectElement) {
                        return Array.from(selectElement.options).filter(option => option.value !== '');
                    }

                    // Función para filtrar cursos por nivel
                    function filtrarCursosPorNivel() {
                        console.log('Ejecutando filtrarCursosPorNivel');
                        if (!nivelSelect || !cursoSelect) {
                            console.warn('Elementos select no disponibles para filtrar cursos');
                            return;
                        }

                        const nivelId = nivelSelect.value;
                        console.log('Filtrando cursos por nivel:', nivelId, 'usando datos API:', !!datosFiltrosDinamicos);

                        // Limpiar opciones actuales (excepto la primera)
                        while (cursoSelect.options.length > 1) {
                            cursoSelect.remove(1);
                        }

                        // Usar los datos de la API para filtrar cursos por nivel
                        console.log('datosFiltrosDinamicos existe:', !!datosFiltrosDinamicos);
                        console.log('datosFiltrosDinamicos.cursos existe:', !!(datosFiltrosDinamicos && datosFiltrosDinamicos.cursos));
                        console.log('datosFiltrosDinamicos.cursos length:', datosFiltrosDinamicos?.cursos?.length || 0);

                        if (datosFiltrosDinamicos && datosFiltrosDinamicos.cursos) {
                            console.log('Filtrando cursos desde API data...');
                            // Agregar opciones filtradas por nivel desde los datos de la API
                            datosFiltrosDinamicos.cursos.forEach(curso => {
                                const cursoNivel = curso.grado?.nivel_id || '';
                                const coincide = !nivelId || cursoNivel == nivelId;
                                console.log('Curso:', curso.nombre_completo || (curso.grado?.nombre + ' ' + curso.seccion?.nombre), 'nivel:', cursoNivel, 'coincide:', coincide);

                                if (coincide) {
                                    const option = document.createElement('option');
                                    option.value = curso.curso_id;
                                    option.setAttribute('data-nivel', cursoNivel);
                                    option.textContent = curso.nombre_completo || (curso.grado?.nombre + ' ' + curso.seccion?.nombre);
                                    cursoSelect.appendChild(option);
                                    console.log('✅ Agregado curso:', option.text);
                                } else {
                                    console.log('❌ Curso no coincide con filtro:', curso.nombre_completo);
                                }
                            });
                        } else {
                            console.warn('❌ No hay datos de la API disponibles para filtrar cursos');
                            console.log('datosFiltrosDinamicos:', datosFiltrosDinamicos);
                        }

                        // Limpiar estudiante si el curso seleccionado ya no está disponible
                        const cursoSeleccionado = cursoSelect.value;
                        if (cursoSeleccionado) {
                            const cursoExiste = Array.from(cursoSelect.options).some(opt => opt.value === cursoSeleccionado);
                            if (!cursoExiste) {
                                cursoSelect.value = '';
                                filtrarEstudiantesPorCurso();
                            }
                        }

                        reinicializarSelect2(cursoSelect, 'Seleccionar curso...');
                    }

                    // Función para filtrar estudiantes por curso y nivel
                    function filtrarEstudiantesPorCurso() {
                        console.log('Ejecutando filtrarEstudiantesPorCurso');
                        if (!cursoSelect || !estudianteSelect || !nivelSelect) {
                            console.warn('Elementos select no disponibles para filtrar estudiantes');
                            return;
                        }

                        const cursoId = cursoSelect.value;
                        const nivelId = nivelSelect.value;

                        console.log('Filtrando estudiantes - cursoId:', cursoId, 'nivelId:', nivelId);

                        // Limpiar opciones actuales (excepto la primera)
                        while (estudianteSelect.options.length > 1) {
                            estudianteSelect.remove(1);
                        }

                        // Obtener opciones actuales de estudiantes (filtradas por fecha)
                        const estudiantesDisponibles = getOpcionesActuales(estudianteSelect);

                        // Agregar opciones filtradas
                        estudiantesDisponibles.forEach(option => {
                            if (option.value === '') return; // Mantener opción vacía

                            const estudianteCurso = option.getAttribute('data-curso');
                            const estudianteNivel = option.getAttribute('data-nivel');
                            let incluir = true;
                            let razonExclusión = '';

                            // Si hay curso seleccionado, filtrar por curso directo
                            if (cursoId && estudianteCurso !== cursoId) {
                                incluir = false;
                                razonExclusión = `curso no coincide (${estudianteCurso} !== ${cursoId})`;
                            }
                            // Si no hay curso seleccionado pero hay nivel, filtrar por nivel
                            else if (!cursoId && nivelId && estudianteNivel !== nivelId) {
                                incluir = false;
                                razonExclusión = `nivel no coincide (${estudianteNivel} !== ${nivelId})`;
                            }
                            // Si hay curso Y nivel seleccionados, verificar que coincidan ambos
                            else if (cursoId && nivelId) {
                                if (estudianteCurso !== cursoId || estudianteNivel !== nivelId) {
                                    incluir = false;
                                    razonExclusión = `curso o nivel no coinciden`;
                                }
                            }

                            console.log(`Estudiante ${option.text}: curso=${estudianteCurso}, nivel=${estudianteNivel}, incluir=${incluir}${razonExclusión ? ', razón: ' + razonExclusión : ''}`);

                            if (incluir) {
                                const nuevaOption = option.cloneNode(true);
                                estudianteSelect.appendChild(nuevaOption);
                            }
                        });

                        reinicializarSelect2(estudianteSelect, 'Seleccionar estudiante...');
                    }

                    // Función para filtrar docentes por curso y nivel
                    function filtrarDocentesPorCursoYNivel() {
                        console.log('Ejecutando filtrarDocentesPorCursoYNivel');
                        if (!docenteSelect) {
                            console.warn('Elemento select de docentes no disponible');
                            return;
                        }

                        const cursoId = cursoSelect?.value || '';
                        const nivelId = nivelSelect?.value || '';

                        console.log('Filtrando docentes - cursoId:', cursoId, 'nivelId:', nivelId);

                        // Limpiar opciones actuales (excepto la primera)
                        while (docenteSelect.options.length > 1) {
                            docenteSelect.remove(1);
                        }

                        // Obtener opciones actuales de docentes (filtradas por fecha)
                        const docentesDisponibles = getOpcionesActuales(docenteSelect);

                        // Agregar opciones filtradas (todos los docentes disponibles pueden enseñar en cualquier curso/nivel)
                        docentesDisponibles.forEach(option => {
                            if (option.value === '') return; // Mantener opción vacía

                            // En un sistema real, aquí se filtraría por docentes que enseñan en cursos específicos
                            // Por ahora, todos los docentes disponibles pueden aparecer en cualquier combinación
                            const nuevaOption = option.cloneNode(true);
                            docenteSelect.appendChild(nuevaOption);
                        });

                        reinicializarSelect2(docenteSelect, 'Seleccionar docente...');
                    }

                    // Función para reinicializar Select2
                    function reinicializarSelect2(selectElement, placeholder) {
                        if ($(selectElement).hasClass('select2-hidden-accessible')) {
                            $(selectElement).select2('destroy');
                        }
                        $(selectElement).select2({
                            theme: 'bootstrap-5',
                            width: '100%',
                            placeholder: placeholder,
                            allowClear: true,
                            minimumResultsForSearch: 0
                        });
                    }

                    // Event listeners - Usar jQuery para Select2
                    $(nivelSelect).on('change', function() {
                        console.log('Nivel changed, value:', $(this).val());
                        filtrarCursosPorNivel();
                        filtrarEstudiantesPorCurso(); // Re-filtrar estudiantes considerando el nuevo nivel
                        filtrarDocentesPorCursoYNivel(); // Re-filtrar docentes considerando el nuevo nivel
                    });

                    $(cursoSelect).on('change', function() {
                        console.log('Curso changed, value:', $(this).val());
                        filtrarEstudiantesPorCurso();
                        filtrarDocentesPorCursoYNivel(); // Re-filtrar docentes considerando el nuevo curso
                    });

                    console.log('Filtros en cascada configurados correctamente');
                }



                function initCharts() {
                    // Datos de tendencia mensual desde PHP (ya filtrados)
                    const tendenciaData = @json($tendenciaMensual);
                    const labels = tendenciaData.map(item => item.mes);
                    const porcentajes = tendenciaData.map(item => item.porcentaje);

                    // Tendencia mensual
                    const ctxTendencia = document.getElementById('tendenciaChart').getContext('2d');
                    window.tendenciaChartInstance = new Chart(ctxTendencia, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Asistencia Promedio (%)',
                                data: porcentajes,
                                borderColor: '#667eea',
                                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                                tension: 0.4,
                                fill: true
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'top',
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: false,
                                    max: 100
                                }
                            }
                        }
                    });

                    // Datos de distribución desde PHP (ya filtrados)
                    const distribucionData = @json($distribucionTipos);

                    // Distribución por tipo
                    const ctxDistribucion = document.getElementById('distribucionChart').getContext('2d');
                    window.distribucionChartInstance = new Chart(ctxDistribucion, {
                        type: 'doughnut',
                        data: {
                            labels: ['Presente', 'Ausente', 'Tarde', 'Justificado'],
                            datasets: [{
                                data: [distribucionData.presente, distribucionData.ausente, distribucionData.tarde, distribucionData.justificado],
                                backgroundColor: [
                                    '#28a745',
                                    '#dc3545',
                                    '#ffc107',
                                    '#17a2b8'
                                ],
                                borderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                }
                            }
                        }
                    });

                    // Inicializar gráficos adicionales
                    initGraficoSemanal();
                    initRankingEstudiantes();

                // Agregar event listeners para actualizar gráficos y filtros dinámicos
                agregarEventListenersFiltros();
                agregarEventListenersFiltrosDinamicos();

                function agregarEventListenersFiltrosDinamicos() {
                    // Escuchar cambios en las fechas para actualizar filtros dinámicos
                    const fechaInicioInput = document.getElementById('fecha_inicio');
                    const fechaFinInput = document.getElementById('fecha_fin');

                    // Función para actualizar filtros cuando cambien las fechas
                    function actualizarFiltrosDinamicos() {
                        const fechaInicio = fechaInicioInput.value;
                        const fechaFin = fechaFinInput.value;

                        console.log('=== actualizarFiltrosDinamicos LLAMADA ===');
                        console.log('fechaInicioInput exists:', !!fechaInicioInput);
                        console.log('fechaFinInput exists:', !!fechaFinInput);
                        console.log('fechaInicio value:', fechaInicio, 'length:', fechaInicio.length);
                        console.log('fechaFin value:', fechaFin, 'length:', fechaFin.length);

                        // Solo actualizar si ambas fechas están seleccionadas
                        if (!fechaInicio || !fechaFin) {
                            console.log('Fechas no completas, no se actualizan filtros');
                            return;
                        }

                        console.log('Actualizando filtros dinámicos para fechas:', fechaInicio, 'a', fechaFin);

                        // Mostrar loading en los filtros
                        const filtroContainer = document.getElementById('filtroContainer');
                        if (filtroContainer) {
                            filtroContainer.innerHTML = '<div class="text-center py-3"><div class="spinner-border spinner-border-sm text-primary" role="status"></div><small class="text-muted ml-2">Actualizando filtros...</small></div>';
                        }

                        // Llamar a la API para obtener filtros dinámicos
                        const filtrosUrl = `/asistencia/api/filtros-dinamicos?fecha_inicio=${encodeURIComponent(fechaInicio)}&fecha_fin=${encodeURIComponent(fechaFin)}`;

                        fetch(filtrosUrl, {
                            method: 'GET',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                console.log('Filtros dinámicos obtenidos:', data.data);
                                console.log('Niveles:', data.data.niveles?.length || 0, 'Cursos:', data.data.cursos?.length || 0, 'Estudiantes:', data.data.estudiantes?.length || 0, 'Docentes:', data.data.docentes?.length || 0);
                                actualizarOpcionesFiltros(data.data);
                            } else {
                                console.error('Error al obtener filtros dinámicos:', data.message);
                                // Mostrar mensaje de error al usuario
                                const filtroContainer = document.getElementById('filtroContainer');
                                if (filtroContainer) {
                                    filtroContainer.innerHTML = '<div class="alert alert-warning"><i class="fas fa-exclamation-triangle mr-2"></i>No se pudieron cargar los filtros dinámicos. Verifica que hayas seleccionado fechas válidas y que existan registros de asistencia en ese período.</div>';
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Error al actualizar filtros dinámicos:', error);
                        });
                    }

                    console.log('Configurando event listeners para fechas...');
                    console.log('fechaInicioInput:', fechaInicioInput);
                    console.log('fechaFinInput:', fechaFinInput);

                    // Agregar event listeners para cambios en fechas
                    fechaInicioInput.addEventListener('change', function(e) {
                        console.log('🎯 Evento change en fechaInicio:', e.target.value);
                        actualizarFiltrosDinamicos();
                    });

                    fechaFinInput.addEventListener('change', function(e) {
                        console.log('🎯 Evento change en fechaFin:', e.target.value);
                        actualizarFiltrosDinamicos();
                    });

                    console.log('Event listeners configurados exitosamente');

                    // También escuchar input para actualizaciones en tiempo real
                    fechaInicioInput.addEventListener('input', function(e) {
                        console.log('Evento input en fechaInicio:', e.target.value);
                        // Solo actualizar si la fecha fin también está seleccionada
                        if (fechaFinInput.value) {
                            actualizarFiltrosDinamicos();
                        }
                    });

                    fechaFinInput.addEventListener('input', function(e) {
                        console.log('Evento input en fechaFin:', e.target.value);
                        // Solo actualizar si la fecha inicio también está seleccionada
                        if (fechaInicioInput.value) {
                            actualizarFiltrosDinamicos();
                        }
                    });

                    console.log('Event listeners configurados. Verificando fechas actuales...');

                    // Verificar si ya hay fechas seleccionadas y llamar inmediatamente
                    const fechaInicioActual = fechaInicioInput.value;
                    const fechaFinActual = fechaFinInput.value;
                    console.log('Fechas actuales - Inicio:', fechaInicioActual, 'Fin:', fechaFinActual);

                    if (fechaInicioActual && fechaFinActual) {
                        console.log('Fechas ya seleccionadas, llamando actualizarFiltrosDinamicos inmediatamente');
                        actualizarFiltrosDinamicos();
                    }
                }

                function actualizarOpcionesFiltros(data) {
                    // Actualizar opciones de niveles
                    if (data.niveles) {
                        const nivelSelect = document.getElementById('nivel_id');
                        if (nivelSelect) {
                            // Limpiar opciones existentes (excepto la primera)
                            while (nivelSelect.options.length > 1) {
                                nivelSelect.remove(1);
                            }

                            // Agregar nuevas opciones
                            data.niveles.forEach(nivel => {
                                const option = document.createElement('option');
                                option.value = nivel.nivel_id;
                                option.textContent = nivel.nombre;
                                nivelSelect.appendChild(option);
                            });

                            // Select nativo - no necesita reinicialización
                        }
                    }

                    // Actualizar opciones de cursos
                    if (data.cursos) {
                        const cursoSelect = document.getElementById('curso_id');
                        if (cursoSelect) {
                            // Limpiar opciones existentes (excepto la primera)
                            while (cursoSelect.options.length > 1) {
                                cursoSelect.remove(1);
                            }

                            // Agregar nuevas opciones
                            data.cursos.forEach(curso => {
                                const option = document.createElement('option');
                                option.value = curso.curso_id;
                                option.setAttribute('data-nivel', curso.grado?.nivel_id || '');
                                option.textContent = curso.nombre_completo || (curso.grado?.nombre + ' ' + curso.seccion?.nombre);
                                cursoSelect.appendChild(option);
                            });

                            // Select nativo - no necesita reinicialización
                        }
                    }

                    // Actualizar opciones de estudiantes
                    if (data.estudiantes) {
                        const estudianteSelect = document.getElementById('estudiante_id');
                        if (estudianteSelect) {
                            // Limpiar opciones existentes (excepto la primera)
                            while (estudianteSelect.options.length > 1) {
                                estudianteSelect.remove(1);
                            }

                            // Agregar nuevas opciones
                            data.estudiantes.forEach(estudiante => {
                                const option = document.createElement('option');
                                option.value = estudiante.estudiante_id;
                                option.setAttribute('data-curso', estudiante.matricula?.curso_id || '');
                                option.setAttribute('data-nivel', estudiante.matricula?.nivel_id || '');
                                option.textContent = estudiante.persona?.apellidos + ', ' + estudiante.persona?.nombres;
                                estudianteSelect.appendChild(option);
                            });

                            // Re-inicializar Select2
                            $(estudianteSelect).select2('destroy').select2({
                                theme: 'bootstrap-5',
                                width: '100%',
                                placeholder: 'Seleccionar estudiante...',
                                allowClear: true,
                                minimumResultsForSearch: 0
                            });
                        }
                    }

                    // Actualizar opciones de docentes
                    if (data.docentes) {
                        const docenteSelect = document.getElementById('docente_id');
                        if (docenteSelect) {
                            // Limpiar opciones existentes (excepto la primera)
                            while (docenteSelect.options.length > 1) {
                                docenteSelect.remove(1);
                            }

                            // Agregar nuevas opciones
                            data.docentes.forEach(docente => {
                                const option = document.createElement('option');
                                option.value = docente.profesor_id;
                                option.textContent = docente.persona?.apellidos + ', ' + docente.persona?.nombres;
                                docenteSelect.appendChild(option);
                            });

                            // Re-inicializar Select2
                            $(docenteSelect).select2('destroy').select2({
                                theme: 'bootstrap-5',
                                width: '100%',
                                placeholder: 'Seleccionar docente...',
                                allowClear: true,
                                minimumResultsForSearch: 0
                            });
                        }
                    }

                    // Re-inicializar filtros en cascada después de actualizar las opciones
                    if (document.getElementById('tipo_reporte').value === 'comparativo') {
                        console.log('Re-inicializando filtros en cascada después de actualización dinámica');
                        setTimeout(() => {
                            configurarFiltrosCascada();
                        }, 100);
                    }

                    console.log('Filtros dinámicos actualizados exitosamente');
                }
                }

                function initGraficoSemanal() {
                    // Datos simulados para asistencia por día de la semana
                    // En un sistema real, estos datos vendrían de la API
                    const diasSemana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];
                    const asistenciaSemanal = [92, 88, 95, 90, 87]; // Datos de ejemplo

                    const ctxSemanal = document.getElementById('semanalChart').getContext('2d');
                    window.semanalChartInstance = new Chart(ctxSemanal, {
                        type: 'bar',
                        data: {
                            labels: diasSemana,
                            datasets: [{
                                label: 'Asistencia (%)',
                                data: asistenciaSemanal,
                                backgroundColor: [
                                    'rgba(54, 162, 235, 0.8)',
                                    'rgba(255, 206, 86, 0.8)',
                                    'rgba(75, 192, 192, 0.8)',
                                    'rgba(153, 102, 255, 0.8)',
                                    'rgba(255, 159, 64, 0.8)'
                                ],
                                borderColor: [
                                    'rgba(54, 162, 235, 1)',
                                    'rgba(255, 206, 86, 1)',
                                    'rgba(75, 192, 192, 1)',
                                    'rgba(153, 102, 255, 1)',
                                    'rgba(255, 159, 64, 1)'
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'top',
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    max: 100
                                }
                            }
                        }
                    });
                }

                function initRankingEstudiantes() {
                    // Simular ranking de estudiantes (en un sistema real vendría de la API)
                    const rankingContainer = document.getElementById('rankingEstudiantes');

                    // Datos de ejemplo para ranking
                    const estudiantesRanking = [
                        { nombre: 'Ana García', curso: '1° Básico A', porcentaje: 98.5 },
                        { nombre: 'Carlos López', curso: '2° Básico B', porcentaje: 97.2 },
                        { nombre: 'María Rodríguez', curso: '3° Básico A', porcentaje: 96.8 },
                        { nombre: 'Juan Pérez', curso: '1° Básico B', porcentaje: 95.9 },
                        { nombre: 'Laura Martínez', curso: '2° Básico A', porcentaje: 95.1 }
                    ];

                    let rankingHtml = '<div class="list-group">';

                    estudiantesRanking.forEach((estudiante, index) => {
                        const medalClass = index === 0 ? 'text-warning' :
                                         index === 1 ? 'text-secondary' :
                                         index === 2 ? 'text-warning' : 'text-muted';
                        const medalIcon = index === 0 ? 'fa-trophy' :
                                        index === 1 ? 'fa-medal' :
                                        index === 2 ? 'fa-award' : 'fa-star';

                        rankingHtml += `
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <span class="badge badge-pill badge-primary mr-3">${index + 1}</span>
                                    <i class="fas ${medalIcon} ${medalClass} mr-2"></i>
                                    <div>
                                        <div class="font-weight-bold">${estudiante.nombre}</div>
                                        <small class="text-muted">${estudiante.curso}</small>
                                    </div>
                                </div>
                                <span class="badge badge-success">${estudiante.porcentaje}%</span>
                            </div>
                        `;
                    });

                    rankingHtml += '</div>';
                    rankingContainer.innerHTML = rankingHtml;
                }



                function agregarEventListenersFiltros() {
                    // Elementos de filtro que deben actualizar los gráficos
                    const elementosFiltro = [
                        'fecha_inicio',
                        'fecha_fin',
                        'tipo_reporte',
                        'nivel_id',
                        'curso_id',
                        'estudiante_id',
                        'docente_id'
                    ];

                    elementosFiltro.forEach(id => {
                        const elemento = document.getElementById(id);
                        if (elemento) {
                            elemento.addEventListener('change', function() {
                                actualizarGraficosDesdeFiltros();
                            });

                            // Para inputs de fecha también escuchar input
                            if (elemento.type === 'date') {
                                elemento.addEventListener('input', function() {
                                    actualizarGraficosDesdeFiltros();
                                });
                            }
                        }
                    });
                }

                function actualizarGraficosDesdeFiltros() {
                    // Recopilar valores actuales de filtros
                    const fechaInicio = document.getElementById('fecha_inicio').value;
                    const fechaFin = document.getElementById('fecha_fin').value;
                    const tipoReporte = document.getElementById('tipo_reporte').value;
                    const nivelId = document.getElementById('nivel_id')?.value || '';
                    const cursoId = document.getElementById('curso_id')?.value || '';
                    const estudianteId = document.getElementById('estudiante_id')?.value || '';
                    const docenteId = document.getElementById('docente_id')?.value || '';

                    // Solo actualizar si tenemos fechas válidas
                    if (!fechaInicio || !fechaFin) {
                        return;
                    }

                    // Llamar a la API para obtener estadísticas filtradas
                    const estadisticasUrl = `/asistencia/api/estadisticas-filtradas?fecha_inicio=${encodeURIComponent(fechaInicio)}&fecha_fin=${encodeURIComponent(fechaFin)}&tipo_reporte=${encodeURIComponent(tipoReporte)}&nivel_id=${encodeURIComponent(nivelId)}&curso_id=${encodeURIComponent(cursoId)}&estudiante_id=${encodeURIComponent(estudianteId)}&docente_id=${encodeURIComponent(docenteId)}`;

                    fetch(estadisticasUrl, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                        }
                    })
                    .then(response => response.json())
                    .then(estadisticasData => {
                        if (estadisticasData.success) {
                            actualizarGraficos(estadisticasData.tendencia_mensual, estadisticasData.distribucion_tipos);
                        }
                    })
                    .catch(error => {
                        console.error('Error al actualizar gráficos:', error);
                    });
                }

                function generarReporte() {
                    // Validar que se haya seleccionado un período
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

                    // Recopilar todos los filtros adicionales
                    const tipoReporte = document.getElementById('tipo_reporte').value;
                    const nivelId = document.getElementById('nivel_id')?.value || '';
                    const cursoId = document.getElementById('curso_id')?.value || '';
                    const estudianteId = document.getElementById('estudiante_id')?.value || '';
                    const docenteId = document.getElementById('docente_id')?.value || '';

                    // Mostrar loading en la vista previa y expandir la sección
                    document.getElementById('reportePreview').style.display = 'block';
                    const collapsePreview = document.getElementById('collapsePreview');
                    if (collapsePreview && !collapsePreview.classList.contains('show')) {
                        const previewCollapse = new bootstrap.Collapse(collapsePreview, {
                            show: true
                        });
                    }
                    const reporteContent = document.getElementById('reporteContent');
                    reporteContent.innerHTML = `
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Cargando...</span>
                            </div>
                            <p class="mt-2 mb-3">Generando vista previa del reporte...</p>

                            <!-- Indicadores de Carga (5 puntos de colores animados) -->
                            <div class="mt-3">
                                <div class="d-flex justify-content-center align-items-center">
                                    <div class="loading-dots">
                                        <span class="dot dot-1"></span>
                                        <span class="dot dot-2"></span>
                                        <span class="dot dot-3"></span>
                                        <span class="dot dot-4"></span>
                                        <span class="dot dot-5"></span>
                                    </div>
                                </div>
                            </div>

                            <style>
                            .loading-dots {
                                display: flex;
                                gap: 8px;
                                align-items: center;
                            }

                            .dot {
                                width: 12px;
                                height: 12px;
                                border-radius: 50%;
                                background-color: #e9ecef;
                                animation: loadingPulse 1.5s ease-in-out infinite;
                            }

                            .dot-1 { animation-delay: 0s; }
                            .dot-2 { animation-delay: 0.2s; }
                            .dot-3 { animation-delay: 0.4s; }
                            .dot-4 { animation-delay: 0.6s; }
                            .dot-5 { animation-delay: 0.8s; }

                            @keyframes loadingPulse {
                                0%, 80%, 100% {
                                    transform: scale(0.8);
                                    background-color: #e9ecef;
                                }
                                40% {
                                    transform: scale(1.2);
                                    background-color: #007bff;
                                }
                            }
                            </style>
                        </div>
                    `;

                    // Hacer petición AJAX para obtener estadísticas filtradas
                    const estadisticasUrl = `/asistencia/api/estadisticas-filtradas?fecha_inicio=${encodeURIComponent(fechaInicio)}&fecha_fin=${encodeURIComponent(fechaFin)}&tipo_reporte=${encodeURIComponent(tipoReporte)}&nivel_id=${encodeURIComponent(nivelId)}&curso_id=${encodeURIComponent(cursoId)}&estudiante_id=${encodeURIComponent(estudianteId)}&docente_id=${encodeURIComponent(docenteId)}`;

                    fetch(estadisticasUrl, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                        }
                    })
                    .then(response => response.json())
                    .then(estadisticasData => {
                        if (estadisticasData.success) {
                            // Actualizar gráficos con datos filtrados
                            actualizarGraficos(estadisticasData.tendencia_mensual, estadisticasData.distribucion_tipos);

                            // Construir URL para obtener datos de tabla
                            let tablaUrl = `/asistencia/api/tabla-asistencias?fecha_inicio=${encodeURIComponent(fechaInicio)}&fecha_fin=${encodeURIComponent(fechaFin)}&per_page=1000&tipo_reporte=${encodeURIComponent(tipoReporte)}&nivel_id=${encodeURIComponent(nivelId)}&curso_id=${encodeURIComponent(cursoId)}&estudiante_id=${encodeURIComponent(estudianteId)}&docente_id=${encodeURIComponent(docenteId)}`;

                            // Obtener datos de tabla para estadísticas detalladas
                            return fetch(tablaUrl, {
                                method: 'GET',
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                                }
                            });
                        } else {
                            throw new Error(estadisticasData.message || 'Error al obtener estadísticas');
                        }
                    })
                    .then(response => response.json())
                    .then(tablaData => {
                        if (tablaData.success) {
                            const estadisticas = tablaData.estadisticas;
                            const estadisticasAdicionales = tablaData.estadisticas_adicionales || {};
                            const estudiantesRiesgo = estadisticasAdicionales.estudiantes_riesgo || [];

                            const totalRegistros = estadisticas.total_registros;
                            const totalEstudiantesUnicos = estadisticas.total_estudiantes_unicos || 0;
                            const diasAnalizados = estadisticas.dias_analizados || 0;

                            // Check if no records exist
                            if (totalRegistros === 0) {
                                reporteContent.innerHTML = `
                                    <div class="alert alert-warning">
                                        <i class="fas fa-info-circle mr-2"></i>
                                        No hay registros de asistencia para el período seleccionado (${fechaInicio} - ${fechaFin}).
                                    </div>
                                    <div class="text-center py-4">
                                        <div class="card">
                                            <div class="card-body">
                                                <i class="fas fa-database fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">Sin Registros</h5>
                                                <p class="text-muted">No se encontraron registros de asistencia en el período especificado.</p>
                                                <p class="text-muted small">Intenta seleccionar un período diferente o verifica que existan registros de asistencia.</p>
                                            </div>
                                        </div>
                                    </div>
                                `;

                                // Disable export button when no records
                                document.getElementById('exportLink').classList.add('disabled');
                                document.getElementById('exportLink').style.pointerEvents = 'none';
                                document.getElementById('exportLink').style.opacity = '0.5';
                                return;
                            }

                            // Enable export button when records exist
                            document.getElementById('exportLink').classList.remove('disabled');
                            document.getElementById('exportLink').style.pointerEvents = '';
                            document.getElementById('exportLink').style.opacity = '';

                            const presentes = estadisticas.total_presentes;
                            const ausentes = estadisticas.total_ausentes;
                            const tardanzas = estadisticas.total_tardanzas || 0;
                            const justificados = estadisticas.total_justificados || 0;

                            // Build filter information
                            let filtroInfo = '';
                            if (tipoReporte !== 'general') {
                                filtroInfo = '<div class="alert alert-primary mt-2"><strong>Filtros aplicados:</strong><br>';

                                switch(tipoReporte) {
                                    case 'por_estudiante':
                                        if (estudianteId) {
                                            const estudianteOption = document.querySelector('#estudiante_id option[value="' + estudianteId + '"]');
                                            if (estudianteOption) {
                                                filtroInfo += 'Estudiante: ' + estudianteOption.textContent;
                                            }
                                        }
                                        break;
                                    case 'por_curso':
                                        if (cursoId) {
                                            const cursoOption = document.querySelector('#curso_id option[value="' + cursoId + '"]');
                                            if (cursoOption) {
                                                filtroInfo += 'Curso: ' + cursoOption.textContent;
                                            }
                                        }
                                        break;
                                    case 'por_docente':
                                        if (docenteId) {
                                            const docenteOption = document.querySelector('#docente_id option[value="' + docenteId + '"]');
                                            if (docenteOption) {
                                                filtroInfo += 'Docente: ' + docenteOption.textContent;
                                            }
                                        }
                                        break;
                                    case 'comparativo':
                                        let filtrosComparativo = [];
                                        if (nivelId) {
                                            const nivelOption = document.querySelector('#nivel_id option[value="' + nivelId + '"]');
                                            if (nivelOption) {
                                                filtrosComparativo.push('Nivel: ' + nivelOption.textContent);
                                            }
                                        }
                                        if (cursoId) {
                                            const cursoOption = document.querySelector('#curso_id option[value="' + cursoId + '"]');
                                            if (cursoOption) {
                                                filtrosComparativo.push('Curso: ' + cursoOption.textContent);
                                            }
                                        }
                                        if (estudianteId) {
                                            const estudianteOption = document.querySelector('#estudiante_id option[value="' + estudianteId + '"]');
                                            if (estudianteOption) {
                                                filtrosComparativo.push('Estudiante: ' + estudianteOption.textContent);
                                            }
                                        }
                                        if (docenteId) {
                                            const docenteOption = document.querySelector('#docente_id option[value="' + docenteId + '"]');
                                            if (docenteOption) {
                                                filtrosComparativo.push('Docente: ' + docenteOption.textContent);
                                            }
                                        }
                                        filtroInfo += filtrosComparativo.join('<br>');
                                        break;
                                }

                                filtroInfo += '</div>';
                            }

                            reporteContent.innerHTML = `
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Vista previa generada exitosamente para el período ${fechaInicio} - ${fechaFin}. Utiliza el botón "Exportar" para descargar el archivo completo.
                                </div>
                                ${filtroInfo}
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5><i class="fas fa-chart-bar mr-2"></i>Resumen Ejecutivo</h5>
                                        <ul class="list-group">
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                Total de Registros Analizados
                                                <span class="badge badge-primary badge-pill">${totalRegistros}</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                Estudiantes Únicos
                                                <span class="badge badge-secondary badge-pill">${estadisticasAdicionales.total_estudiantes_unicos || 0}</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                Promedio de Asistencia General
                                                <span class="badge badge-success badge-pill">${estadisticas.porcentaje_asistencia}</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                Asistencia Diaria Promedio
                                                <span class="badge badge-info badge-pill">${estadisticasAdicionales.promedio_asistencia_diaria || 0}%</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                Total de Presentes
                                                <span class="badge badge-success badge-pill">${presentes}</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                Total de Ausentes
                                                <span class="badge badge-danger badge-pill">${ausentes}</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                Total de Tardanzas
                                                <span class="badge badge-warning badge-pill">${tardanzas}</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                Total de Justificados
                                                <span class="badge badge-info badge-pill">${justificados}</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                Días Analizados
                                                <span class="badge badge-light badge-pill">${estadisticasAdicionales.dias_analizados || 0}</span>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <h5><i class="fas fa-exclamation-triangle mr-2 text-warning"></i>Alertas de Riesgo</h5>
                                        ${estudiantesRiesgo.length > 0 ?
                                            `<div class="alert alert-warning">
                                                <strong>${estudiantesRiesgo.length} estudiante(s) en riesgo</strong> (asistencia < 70%)
                                                <ul class="mb-0 mt-2">
                                                    ${estudiantesRiesgo.slice(0, 5).map(est =>
                                                        `<li><small>${est.nombre} (${est.curso}) - ${est.porcentaje}%</small></li>`
                                                    ).join('')}
                                                    ${estudiantesRiesgo.length > 5 ? `<li><small>...y ${estudiantesRiesgo.length - 5} más</small></li>` : ''}
                                                </ul>
                                            </div>` :
                                            `<div class="alert alert-success">
                                                <i class="fas fa-check-circle mr-2"></i>
                                                No hay estudiantes en riesgo de deserción por asistencia baja.
                                            </div>`
                                        }

                                        <h6 class="mt-3"><i class="fas fa-chart-pie mr-2"></i>Distribución por Tipo:</h6>
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

                                        <div class="mt-3">
                                            <h6><i class="fas fa-lightbulb mr-2 text-primary"></i>Insights Inteligentes:</h6>
                                            <div class="alert alert-light">
                                                <small>
                                                    ${generarInsights(totalRegistros, estadisticasAdicionales, estudiantesRiesgo.length)}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                        } else {
                            throw new Error(tablaData.message || 'Error al obtener datos de tabla');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        reporteContent.innerHTML = `
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                Error al generar la vista previa: ${error.message || 'Error desconocido'}
                            </div>
                        `;
                    });

                    // Scroll suave hacia la vista previa
                    document.getElementById('reportePreview').scrollIntoView({ behavior: 'smooth' });
                }

                function actualizarGraficos(tendenciaMensual, distribucionTipos) {
                    // Actualizar gráfico de tendencia mensual
                    const ctxTendencia = document.getElementById('tendenciaChart').getContext('2d');
                    const labels = tendenciaMensual.map(item => item.mes);
                    const porcentajes = tendenciaMensual.map(item => item.porcentaje);

                    if (window.tendenciaChartInstance) {
                        window.tendenciaChartInstance.destroy();
                    }

                    window.tendenciaChartInstance = new Chart(ctxTendencia, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Asistencia Promedio (%)',
                                data: porcentajes,
                                borderColor: '#667eea',
                                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                                tension: 0.4,
                                fill: true
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'top',
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: false,
                                    max: 100
                                }
                            }
                        }
                    });

                    // Actualizar gráfico de distribución por tipo
                    const ctxDistribucion = document.getElementById('distribucionChart').getContext('2d');

                    if (window.distribucionChartInstance) {
                        window.distribucionChartInstance.destroy();
                    }

                    window.distribucionChartInstance = new Chart(ctxDistribucion, {
                        type: 'doughnut',
                        data: {
                            labels: ['Presente', 'Ausente', 'Tarde', 'Justificado'],
                            datasets: [{
                                data: [
                                    distribucionTipos.presente || 0,
                                    distribucionTipos.ausente || 0,
                                    distribucionTipos.tarde || 0,
                                    distribucionTipos.justificado || 0
                                ],
                                backgroundColor: [
                                    '#28a745',
                                    '#dc3545',
                                    '#ffc107',
                                    '#17a2b8'
                                ],
                                borderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                }
                            }
                        }
                    });
                }

                function generarInsights(totalRegistros, estadisticasAdicionales, estudiantesRiesgo) {
                    let insights = [];

                    if (totalRegistros === 0) {
                        return "No hay suficientes datos para generar insights.";
                    }

                    const promedioDiario = estadisticasAdicionales.promedio_asistencia_diaria || 0;
                    const estudiantesUnicos = estadisticasAdicionales.total_estudiantes_unicos || 0;
                    const diasAnalizados = estadisticasAdicionales.dias_analizados || 0;

                    if (promedioDiario >= 90) {
                        insights.push("Excelente tasa de asistencia general (≥90%).");
                    } else if (promedioDiario >= 80) {
                        insights.push("Buena tasa de asistencia general (80-89%).");
                    } else if (promedioDiario >= 70) {
                        insights.push("Tasa de asistencia aceptable (70-79%), se recomienda monitoreo.");
                    } else {
                        insights.push("Tasa de asistencia baja (<70%), requiere atención inmediata.");
                    }

                    if (estudiantesRiesgo > 0) {
                        insights.push(`${estudiantesRiesgo} estudiante(s) identificado(s) con riesgo de deserción.`);
                    }

                    if (diasAnalizados > 30) {
                        insights.push("Análisis a largo plazo permite identificar tendencias estacionales.");
                    }

                    if (estudiantesUnicos > 0 && diasAnalizados > 0) {
                        const registrosPromedio = totalRegistros / estudiantesUnicos;
                        if (registrosPromedio > diasAnalizados * 0.8) {
                            insights.push("Cobertura de registro muy buena (>80% de días analizados).");
                        }
                    }

                    return insights.length > 0 ? insights.join(" ") : "Análisis completado exitosamente.";
                }

                function setPeriodo(tipo) {
                    console.log('setPeriodo called with:', tipo);
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
                            fechaInicio.setDate(hoy.getDate() - 6); // 7 días incluyendo hoy
                            fechaFin = hoy;
                            break;
                        case 'ultimos30':
                            fechaInicio = new Date(hoy);
                            fechaInicio.setDate(hoy.getDate() - 29); // 30 días incluyendo hoy
                            fechaFin = hoy;
                            break;
                        case 'ultimos90':
                            fechaInicio = new Date(hoy);
                            fechaInicio.setDate(hoy.getDate() - 89); // 90 días incluyendo hoy
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
                        case 'esteAnio':
                            fechaInicio = new Date(hoy.getFullYear(), 0, 1);
                            fechaFin = new Date(hoy.getFullYear(), 11, 31);
                            break;
                        case 'anioAnterior':
                            fechaInicio = new Date(hoy.getFullYear() - 1, 0, 1);
                            fechaFin = new Date(hoy.getFullYear() - 1, 11, 31);
                            break;
                    }

                    // Formatear fechas para inputs HTML (YYYY-MM-DD)
                    const fechaInicioStr = fechaInicio.toISOString().split('T')[0];
                    const fechaFinStr = fechaFin.toISOString().split('T')[0];

                    console.log('Setting dates - Inicio:', fechaInicioStr, 'Fin:', fechaFinStr);

                    const fechaInicioInput = document.getElementById('fecha_inicio');
                    const fechaFinInput = document.getElementById('fecha_fin');

                    if (fechaInicioInput && fechaFinInput) {
                        fechaInicioInput.value = fechaInicioStr;
                        fechaFinInput.value = fechaFinStr;
                        console.log('Dates set successfully');

                        // Trigger change event to activate dynamic filters
                        fechaInicioInput.dispatchEvent(new Event('change'));
                        fechaFinInput.dispatchEvent(new Event('change'));
                    } else {
                        console.error('Date inputs not found');
                    }
                }

                function limpiarFechas() {
                    document.getElementById('fecha_inicio').value = '';
                    document.getElementById('fecha_fin').value = '';
                }

                function configurarFiltrosCascada() {
                    const nivelSelect = document.getElementById('nivel_id');
                    const cursoSelect = document.getElementById('curso_id');
                    const estudianteSelect = document.getElementById('estudiante_id');
                    const docenteSelect = document.getElementById('docente_id');

                    // Función para obtener opciones actuales (dinámicas)
                    function getOpcionesActuales(selectElement) {
                        return Array.from(selectElement.options).filter(option => option.value !== '');
                    }

                    // Capturar las opciones originales para cascada
                    let cursosOriginales = [];
                    let estudiantesOriginales = [];
                    let docentesOriginales = [];

                    function actualizarOpcionesOriginales() {
                        cursosOriginales = getOpcionesActuales(cursoSelect);
                        estudiantesOriginales = getOpcionesActuales(estudianteSelect);
                        docentesOriginales = getOpcionesActuales(docenteSelect);
                        console.log('Opciones originales actualizadas:', {
                            cursos: cursosOriginales.length,
                            estudiantes: estudiantesOriginales.length,
                            docentes: docentesOriginales.length
                        });
                    }

                    // Función para filtrar cursos por nivel
                    function filtrarCursosPorNivel() {
                        console.log('=== INICIO filtrarCursosPorNivel ===');
                        const nivelId = nivelSelect.value;
                        console.log('Filtrando cursos por nivel:', nivelId, 'usando datos API:', !!datosFiltrosDinamicos);
                        console.log('datosFiltrosDinamicos completo:', datosFiltrosDinamicos);

                        if (!datosFiltrosDinamicos || !datosFiltrosDinamicos.cursos) {
                            console.warn('No hay datos de la API disponibles para filtrar cursos');
                            return;
                        }

                        // Limpiar opciones actuales (excepto la primera)
                        while (cursoSelect.options.length > 1) {
                            cursoSelect.remove(1);
                        }

                        // Usar los datos de la API para filtrar cursos por nivel
                        if (datosFiltrosDinamicos && datosFiltrosDinamicos.cursos) {
                            console.log('Filtrando cursos desde API data... Total cursos:', datosFiltrosDinamicos.cursos.length);
                            // Agregar opciones filtradas por nivel desde los datos de la API
                            let cursosAgregados = 0;
                            datosFiltrosDinamicos.cursos.forEach((curso, index) => {
                                console.log(`Procesando curso ${index + 1}:`, curso);
                                const cursoNivel = curso.grado?.nivel_id || '';
                                const nombreCurso = curso.nombre_completo || (curso.grado?.nombre + ' ' + curso.seccion?.nombre);
                                const coincide = !nivelId || cursoNivel == nivelId;

                                console.log(`Curso "${nombreCurso}": nivel_id=${cursoNivel}, nivelId=${nivelId}, coincide=${coincide}`);

                                if (coincide) {
                                    const option = document.createElement('option');
                                    option.value = curso.curso_id;
                                    option.setAttribute('data-nivel', cursoNivel);
                                    option.textContent = nombreCurso;
                                    cursoSelect.appendChild(option);
                                    cursosAgregados++;
                                    console.log(`✅ Agregado curso ${cursosAgregados}:`, nombreCurso);
                                } else {
                                    console.log('❌ Curso filtrado:', nombreCurso);
                                }
                            });
                            console.log(`Total cursos agregados: ${cursosAgregados}`);
                        } else {
                            console.warn('❌ No hay datos de la API disponibles para filtrar cursos');
                            console.log('datosFiltrosDinamicos:', datosFiltrosDinamicos);
                        }

                        // Limpiar estudiante si el curso seleccionado ya no está disponible
                        const cursoSeleccionado = cursoSelect.value;
                        if (cursoSeleccionado) {
                            const cursoExiste = Array.from(cursoSelect.options).some(opt => opt.value === cursoSeleccionado);
                            if (!cursoExiste) {
                                cursoSelect.value = '';
                                filtrarEstudiantesPorCurso();
                            }
                        }

                        reinicializarSelect2(cursoSelect, 'Seleccionar curso...');
                    }

                    // Función para filtrar docentes por curso y nivel
                    function filtrarDocentesPorCursoYNivel() {
                        const cursoId = cursoSelect.value;
                        const nivelId = nivelSelect.value;

                        console.log('Filtrando docentes - cursoId:', cursoId, 'nivelId:', nivelId);

                        // Limpiar opciones actuales (excepto la primera)
                        while (docenteSelect.options.length > 1) {
                            docenteSelect.remove(1);
                        }

                        // Obtener opciones actuales de docentes (filtradas por fecha)
                        const docentesDisponibles = getOpcionesActuales(docenteSelect);

                        // Agregar opciones filtradas (todos los docentes disponibles pueden enseñar en cualquier curso/nivel)
                        docentesDisponibles.forEach(option => {
                            if (option.value === '') return; // Mantener opción vacía

                            // En un sistema real, aquí se filtraría por docentes que enseñan en cursos específicos
                            // Por ahora, todos los docentes disponibles pueden aparecer en cualquier combinación
                            const nuevaOption = option.cloneNode(true);
                            docenteSelect.appendChild(nuevaOption);
                        });

                        reinicializarSelect2(docenteSelect, 'Seleccionar docente...');
                    }

                    // Función para reinicializar Select2
                    function reinicializarSelect2(selectElement, placeholder) {
                        $(selectElement).select2('destroy').select2({
                            theme: 'bootstrap-5',
                            width: '100%',
                            placeholder: placeholder,
                            allowClear: true,
                            minimumResultsForSearch: 0
                        });
                    }

                    // Event listeners - Usar jQuery para Select2
                    $(nivelSelect).on('change', function() {
                        console.log('Nivel changed, value:', $(this).val());
                        filtrarCursosPorNivel();
                        filtrarEstudiantesPorCurso(); // Re-filtrar estudiantes considerando el nuevo nivel
                        filtrarDocentesPorCursoYNivel(); // Re-filtrar docentes considerando el nuevo nivel
                    });

                    $(cursoSelect).on('change', function() {
                        console.log('Curso changed, value:', $(this).val());
                        filtrarEstudiantesPorCurso();
                        filtrarDocentesPorCursoYNivel(); // Re-filtrar docentes considerando el nuevo curso
                    });

                    // Inicializar filtros
                    filtrarCursosPorNivel();
                    filtrarEstudiantesPorCurso();
                    filtrarDocentesPorCursoYNivel();
                }

                function exportarReporte() {
                    const formato = document.getElementById('formato').value;
                    const tipoReporte = document.getElementById('tipo_reporte').value;
                    const fechaInicio = document.getElementById('fecha_inicio').value;
                    const fechaFin = document.getElementById('fecha_fin').value;

                    if (!fechaInicio || !fechaFin) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Fechas requeridas',
                            text: 'Por favor selecciona las fechas de inicio y fin para exportar el reporte.',
                            confirmButtonText: 'Entendido'
                        });
                        return;
                    }

                    // Recopilar todos los filtros adicionales
                    const nivelId = document.getElementById('nivel_id')?.value || '';
                    const cursoId = document.getElementById('curso_id')?.value || '';
                    const estudianteId = document.getElementById('estudiante_id')?.value || '';
                    const docenteId = document.getElementById('docente_id')?.value || '';

                    if (formato === 'pdf') {
                        // Mostrar loading
                        Swal.fire({
                            title: 'Generando reporte PDF...',
                            text: 'Por favor espera mientras se genera el PDF',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            willOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Construir URL con todos los parámetros
                        let url = `/asistencia/exportar/pdf/admin?fecha_inicio=${encodeURIComponent(fechaInicio)}&fecha_fin=${encodeURIComponent(fechaFin)}&formato=${encodeURIComponent(formato)}&tipo_reporte=${encodeURIComponent(tipoReporte)}`;

                        if (nivelId) url += `&nivel_id=${encodeURIComponent(nivelId)}`;
                        if (cursoId) url += `&curso_id=${encodeURIComponent(cursoId)}`;
                        if (estudianteId) url += `&estudiante_id=${encodeURIComponent(estudianteId)}`;
                        if (docenteId) url += `&docente_id=${encodeURIComponent(docenteId)}`;

                        // Hacer petición AJAX para generar el reporte
                        fetch(url, {
                            method: 'GET',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                            }
                        })
                        .then(response => {
                            if (response.redirected) {
                                // Si fue redirigido, verificar si hay mensaje de éxito
                                return response.text().then(text => {
                                    if (text.includes('success')) {
                                        throw new Error('Respuesta inesperada del servidor');
                                    } else {
                                        throw new Error('Error desconocido en la respuesta');
                                    }
                                });
                            } else {
                                return response.json();
                            }
                        })
                        .then(data => {
                            if (data.success && data.archivo_url) {
                                // Abrir el PDF en una nueva ventana/pestaña usando la URL del servidor
                                window.open(data.archivo_url, '_blank');

                                // Mostrar mensaje de éxito
                                Swal.fire({
                                    icon: 'success',
                                    title: '¡Reporte PDF generado!',
                                    text: 'El reporte se ha generado exitosamente y se abrirá en una nueva pestaña.',
                                    confirmButtonText: 'Entendido'
                                });
                            } else {
                                throw new Error(data.message || 'Error desconocido en la respuesta');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error al generar reporte PDF',
                                text: 'Ocurrió un error al generar el reporte. Por favor intenta nuevamente.',
                                confirmButtonText: 'Entendido'
                            });
                        });
                    } else if (formato === 'excel') {
                        // Mostrar loading para Excel
                        Swal.fire({
                            title: 'Generando reporte Excel...',
                            text: 'Por favor espera mientras se genera el archivo Excel',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            willOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Construir URL con todos los parámetros para Excel
                        let url = `/asistencia/exportar/excel/admin?fecha_inicio=${encodeURIComponent(fechaInicio)}&fecha_fin=${encodeURIComponent(fechaFin)}&formato=${encodeURIComponent(formato)}&tipo_reporte=${encodeURIComponent(tipoReporte)}`;

                        if (nivelId) url += `&nivel_id=${encodeURIComponent(nivelId)}`;
                        if (cursoId) url += `&curso_id=${encodeURIComponent(cursoId)}`;
                        if (estudianteId) url += `&estudiante_id=${encodeURIComponent(estudianteId)}`;
                        if (docenteId) url += `&docente_id=${encodeURIComponent(docenteId)}`;

                        // Para Excel, usamos una descarga directa ya que Laravel Excel maneja la respuesta automáticamente
                        window.location.href = url;

                        // Cerrar el loading después de un breve delay
                        setTimeout(() => {
                            Swal.close();
                        }, 1000);
                    } else {
                        // Para otros formatos, mostrar mensaje
                        Swal.fire({
                            icon: 'info',
                            title: 'Formato no soportado',
                            text: `El formato ${formato.toUpperCase()} no está disponible actualmente.`,
                            confirmButtonText: 'Entendido'
                        });
                    }
                }

                // Función para ver detalles de un reporte generado
                function verDetallesReporte(reporteId) {
                    // Mostrar loading
                    Swal.fire({
                        title: 'Cargando detalles...',
                        text: 'Obteniendo información del reporte',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        willOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // En un sistema real, aquí haríamos una petición AJAX para obtener los detalles
                    // Por ahora, simulamos con un mensaje informativo
                    setTimeout(() => {
                        Swal.fire({
                            title: 'Detalles del Reporte',
                            html: `
                                <div class="text-left">
                                    <p><strong>ID del Reporte:</strong> ${reporteId}</p>
                                    <p><strong>Estado:</strong> <span class="badge badge-success">Completado</span></p>
                                    <p><strong>Información:</strong> Los detalles completos del reporte están disponibles en el archivo descargado.</p>
                                    <hr>
                                    <p class="text-muted small">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Para ver información detallada del reporte, descarga el archivo usando el botón correspondiente.
                                    </p>
                                </div>
                            `,
                            icon: 'info',
                            confirmButtonText: 'Entendido'
                        });
                    }, 1000);
                }

                </script>
            </div>
        </div>
    </div>
</div>

@endsection
                            console.log(`Total cursos agregados: ${cursosAgregados}`);
