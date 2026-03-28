@extends('cplantilla.bprincipal')
@section('titulo','Reportes de Asistencia')
@section('contenidoplantilla')
<x-breadcrumb :module="'asistencia'" :section="'reportes'" />
<div class="container-fluid" id="contenido-principal">
    <div class="row mt-4 ml-1 mr-1">
        <div class="col-12">
            <div class="box_block">
                <!-- Collapse header -->
                <button class="btn btn-block text-left rounded-0 btn_header header_6" type="button" data-toggle="collapse" data-target="#collapseReportes" aria-expanded="true" aria-controls="collapseReportes" style="background: #0A8CB3 !important; font-weight: bold; color: white;">
                    <i class="fas fa-chart-line m-1"></i>&nbsp;Reportes de Asistencia
                    <div class="float-right"><i class="fas fa-chevron-down"></i></div>
                </button>
                <!-- Descripción -->
                <div class="card-body rounded-0 border-0 pt-3 pb-3" style="background: #f3f3f3; border-bottom: 1px solid rgba(0,0,0,.125); border-top: 1px solid rgba(0,0,0,.125); color: #F59D24;">
                    <div class="row justify-content-center align-items-center flex-wrap">
                        <div class="col-auto text-center mb-2 mb-md-0" style="min-width:48px;">
                            <i class="fas fa-chart-bar fa-2x"></i>
                        </div>
                        <div class="col px-2" style="text-align:justify;">
                            <p style="margin-bottom: 0px; font-family: 'Quicksand', sans-serif; font-weight: 600; color: #004a92;">
                                Genera reportes detallados de asistencia por período, curso, estudiante o docente. Analiza tendencias y patrones de asistencia para tomar decisiones informadas.
                            </p>
                            <p style="font-family: 'Quicksand', sans-serif; font-weight: 600; color: #004a92;">
                                Utiliza los filtros avanzados para obtener información específica y exporta los reportes en diferentes formatos según tus necesidades.
                            </p>
                        </div>
                    </div>
                </div>
                <!-- Collapse: filtros y contenido -->
                <div class="collapse show" id="collapseReportes">
                    <div class="card card-body rounded-0 border-0 pt-0 pb-2" style="background: transparent;">
                        <!-- Filtros de Reporte -->
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
                                                        <label for="fecha_inicio">Fecha Inicio</label>
                                                        <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="fecha_fin">Fecha Fin</label>
                                                        <input type="date" class="form-control" id="fecha_fin" name="fecha_fin">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="tipo_reporte">Tipo de Reporte</label>
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
                                                        <label for="formato">Formato</label>
                                                        <select class="form-control" id="formato" name="formato">
                                                            <option value="pdf">PDF</option>
                                                            <option value="excel">Excel</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row" id="filtrosAdicionales" style="display: none;">
                                                <div class="col-md-12" id="filtroContainer">
                                                    <!-- Los filtros se cargarán dinámicamente aquí -->
                                                </div>
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

                        <!-- Vista Previa del Reporte -->
                        <div class="row mb-4" id="reportePreview" style="display: none;">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header" style="background: #17a2b8; color: white;">
                                        <h5 class="mb-0"><i class="fas fa-eye mr-2"></i>Vista Previa del Reporte</h5>
                                    </div>
                                    <div class="card-body">
                                        <div id="reporteContent">
                                            <!-- Contenido dinámico del reporte -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Estadísticas Rápidas -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body p-3">
                                        <div class="row text-center">
                                            <div class="col-md-3 col-6 mb-3 mb-md-0">
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <div class="stats-icon mr-2" style="background: rgba(40, 167, 69, 0.1); color: #28a745; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                        <i class="fas fa-user-check"></i>
                                                    </div>
                                                    <div class="text-left">
                                                        <div class="h5 mb-0 text-success font-weight-bold">{{ $estadisticasRapidas['porcentaje_asistencia'] }}%</div>
                                                        <small class="text-muted" style="font-size: 0.75rem;">Asistencia</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-6 mb-3 mb-md-0">
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <div class="stats-icon mr-2" style="background: rgba(220, 53, 69, 0.1); color: #dc3545; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                        <i class="fas fa-user-times"></i>
                                                    </div>
                                                    <div class="text-left">
                                                        <div class="h5 mb-0 text-danger font-weight-bold">{{ number_format($estadisticasRapidas['total_inasistencias']) }}</div>
                                                        <small class="text-muted" style="font-size: 0.75rem;">Ausencias</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-6">
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <div class="stats-icon mr-2" style="background: rgba(255, 193, 7, 0.1); color: #ffc107; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                        <i class="fas fa-clock"></i>
                                                    </div>
                                                    <div class="text-left">
                                                        <div class="h5 mb-0 text-warning font-weight-bold">{{ number_format($estadisticasRapidas['total_tardanzas']) }}</div>
                                                        <small class="text-muted" style="font-size: 0.75rem;">Tardanzas</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-6">
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <div class="stats-icon mr-2" style="background: rgba(23, 162, 184, 0.1); color: #17a2b8; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                        <i class="fas fa-check-circle"></i>
                                                    </div>
                                                    <div class="text-left">
                                                        <div class="h5 mb-0 text-info font-weight-bold">{{ number_format($estadisticasRapidas['justificaciones_aprobadas']) }}</div>
                                                        <small class="text-muted" style="font-size: 0.75rem;">Justificadas</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Gráficos de Tendencias -->
                        <div class="row">
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header" style="background: #28a745; color: white;">
                                        <h5 class="mb-0"><i class="fas fa-chart-area mr-2"></i>Tendencia de Asistencia Mensual</h5>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="tendenciaChart" height="300"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header" style="background: #dc3545; color: white;">
                                        <h5 class="mb-0"><i class="fas fa-chart-pie mr-2"></i>Distribución por Tipo</h5>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="distribucionChart" height="300"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Reportes Recientes -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header" style="background: #ffc107; color: white;">
                                        <h5 class="mb-0"><i class="fas fa-history mr-2"></i>Reportes Generados Recientemente</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead class="table-primary">
                                                    <tr>
                                                        <th>Fecha Generación</th>
                                                        <th>Tipo de Reporte</th>
                                                        <th>Período</th>
                                                        <th>Formato</th>
                                                        <th>Generado por</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($reportesRecientes as $reporte)
                                                    <tr>
                                                        <td>{{ $reporte['fecha'] }}</td>
                                                        <td><span class="badge badge-primary">{{ $reporte['tipo'] }}</span></td>
                                                        <td>{{ $reporte['periodo'] }}</td>
                                                        <td><span class="badge badge-success">{{ $reporte['formato'] }}</span></td>
                                                        <td>{{ $reporte['generado_por'] }}</td>
                                                        <td>
                                                            <button class="btn btn-sm btn-outline-primary">
                                                                <i class="fas fa-download"></i> Descargar
                                                            </button>
                                                            <button class="btn btn-sm btn-outline-info">
                                                                <i class="fas fa-eye"></i> Ver
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    @empty
                                                    <tr>
                                                        <td colspan="6" class="text-center text-muted">
                                                            <i class="fas fa-info-circle mr-2"></i>No hay reportes generados recientemente
                                                        </td>
                                                    </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                document.addEventListener('DOMContentLoaded', function () {
                    // Collapse icon toggle
                    const btn = document.querySelector('[data-target="#collapseReportes"]');
                    const icon = btn.querySelector('.fas.fa-chevron-down');
                    const collapse = document.getElementById('collapseReportes');
                    collapse.addEventListener('show.bs.collapse', function () {
                        icon.classList.remove('fa-chevron-down');
                        icon.classList.add('fa-chevron-up');
                    });
                    collapse.addEventListener('hide.bs.collapse', function () {
                        icon.classList.remove('fa-chevron-up');
                        icon.classList.add('fa-chevron-down');
                    });

                    // Toggle filtros adicionales basados en tipo de reporte
                    document.getElementById('tipo_reporte').addEventListener('change', function() {
                        const filtrosAdicionales = document.getElementById('filtrosAdicionales');
                        const filtroContainer = document.getElementById('filtroContainer');
                        const tipoReporte = this.value;

                        // Limpiar el contenedor
                        filtroContainer.innerHTML = '';

                        // Mostrar filtros según el tipo de reporte seleccionado
                        if (tipoReporte === 'general') {
                            filtrosAdicionales.style.display = 'none';
                        } else {
                            filtrosAdicionales.style.display = 'flex';

                            let filterHtml = '';

                            switch(tipoReporte) {
                                case 'por_curso':
                                    filterHtml = `
                                        <div class="form-group">
                                            <label for="curso_id">Curso</label>
                                            <select class="form-control" id="curso_id" name="curso_id">
                                                <option value="">Todos los cursos</option>
                                                @foreach($cursos as $curso)
                                                <option value="{{ $curso->curso_id }}">{{ $curso->grado->nombre }} {{ $curso->seccion->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    `;
                                    break;
                                case 'por_estudiante':
                                    filterHtml = `
                                        <div class="form-group">
                                            <label for="estudiante_id">Estudiante</label>
                                            <select class="form-control" id="estudiante_id" name="estudiante_id">
                                                <option value="">Todos los estudiantes</option>
                                                @foreach($estudiantes as $estudiante)
                                                <option value="{{ $estudiante->estudiante_id }}">{{ $estudiante->persona->apellidos }}, {{ $estudiante->persona->nombres }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    `;
                                    break;
                                case 'por_docente':
                                    filterHtml = `
                                        <div class="form-group">
                                            <label for="docente_id">Docente</label>
                                            <select class="form-control" id="docente_id" name="docente_id">
                                                <option value="">Todos los docentes</option>
                                                @foreach($docentes as $docente)
                                                <option value="{{ $docente->docente_id }}">{{ $docente->persona->apellidos }}, {{ $docente->persona->nombres }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    `;
                                    break;
                                case 'comparativo':
                                    filterHtml = `
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="nivel_id">Nivel Educativo</label>
                                                    <select class="form-control" id="nivel_id" name="nivel_id">
                                                        <option value="">Todos los niveles</option>
                                                        @foreach($niveles as $nivel)
                                                        <option value="{{ $nivel->nivel_id }}">{{ $nivel->nombre }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="curso_id">Curso</label>
                                                    <select class="form-control" id="curso_id" name="curso_id">
                                                        <option value="">Todos los cursos</option>
                                                        @foreach($cursos as $curso)
                                                        <option value="{{ $curso->curso_id }}">{{ $curso->grado->nombre }} {{ $curso->seccion->nombre }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="estudiante_id">Estudiante</label>
                                                    <select class="form-control" id="estudiante_id" name="estudiante_id">
                                                        <option value="">Todos los estudiantes</option>
                                                        @foreach($estudiantes as $estudiante)
                                                        <option value="{{ $estudiante->estudiante_id }}">{{ $estudiante->persona->apellidos }}, {{ $estudiante->persona->nombres }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="docente_id">Docente</label>
                                                    <select class="form-control" id="docente_id" name="docente_id">
                                                        <option value="">Todos los docentes</option>
                                                        @foreach($docentes as $docente)
                                                        <option value="{{ $docente->docente_id }}">{{ $docente->persona->apellidos }}, {{ $docente->persona->nombres }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    `;
                                    break;
                            }

                            filtroContainer.innerHTML = filterHtml;

                            // Re-inicializar select2 para los nuevos elementos
                            setTimeout(() => {
                                $('.form-control').each(function() {
                                    if ($(this).attr('id') !== 'tipo_reporte' && $(this).attr('id') !== 'formato') {
                                        $(this).select2({
                                            placeholder: $(this).find('option:first').text() || 'Seleccionar...',
                                            allowClear: true,
                                            width: '100%',
                                            theme: 'bootstrap4',
                                            language: {
                                                noResults: function() {
                                                    return "No se encontraron resultados";
                                                },
                                                searching: function() {
                                                    return "Buscando...";
                                                }
                                            }
                                        });
                                    }
                                });
                            }, 100);
                        }
                    });

                    // Inicializar gráficos
                    initCharts();
                });

                function initCharts() {
                    // Datos de tendencia mensual desde PHP
                    const tendenciaData = @json($tendenciaMensual);
                    const labels = tendenciaData.map(item => item.mes);
                    const porcentajes = tendenciaData.map(item => item.porcentaje);

                    // Tendencia mensual
                    const ctxTendencia = document.getElementById('tendenciaChart').getContext('2d');
                    new Chart(ctxTendencia, {
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

                    // Datos de distribución desde PHP
                    const distribucionData = @json($distribucionTipos);

                    // Distribución por tipo
                    const ctxDistribucion = document.getElementById('distribucionChart').getContext('2d');
                    new Chart(ctxDistribucion, {
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

                    // Mostrar loading en la vista previa
                    document.getElementById('reportePreview').style.display = 'block';
                    const reporteContent = document.getElementById('reporteContent');
                    reporteContent.innerHTML = `
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Cargando...</span>
                            </div>
                            <p class="mt-2">Generando vista previa del reporte...</p>
                        </div>
                    `;

                    // Hacer petición AJAX para obtener estadísticas filtradas
                    fetch(`/asistencia/api/tabla-asistencias?fecha_inicio=${encodeURIComponent(fechaInicio)}&fecha_fin=${encodeURIComponent(fechaFin)}&per_page=1000`, {
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
                            const estadisticas = data.estadisticas;
                            const totalRegistros = estadisticas.total_registros;
                            const presentes = estadisticas.total_presentes;
                            const ausentes = estadisticas.total_ausentes;

                            reporteContent.innerHTML = `
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Vista previa generada exitosamente para el período ${fechaInicio} - ${fechaFin}. Utiliza el botón "Exportar" para descargar el archivo completo.
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5>Resumen Ejecutivo</h5>
                                        <ul class="list-group">
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                Total de Registros Analizados
                                                <span class="badge badge-primary badge-pill">${totalRegistros}</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                Promedio de Asistencia General
                                                <span class="badge badge-success badge-pill">${estadisticas.porcentaje_asistencia}</span>
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
                                                <span class="badge badge-warning badge-pill">0</span>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <h5>Observaciones</h5>
                                        <div class="alert alert-light">
                                            <small>
                                                Se analizaron ${totalRegistros} registros de asistencia para el período seleccionado (${fechaInicio} - ${fechaFin}).
                                                La tasa de asistencia es del ${estadisticas.porcentaje_asistencia}.
                                                Los datos mostrados corresponden a su base de datos real filtrados por el período seleccionado.
                                            </small>
                                        </div>
                                        <div class="mt-3">
                                            <h6>Distribución por Tipo:</h6>
                                            <div class="row text-center">
                                                <div class="col-6">
                                                    <div class="card border-success">
                                                        <div class="card-body p-2">
                                                            <h5 class="text-success">${presentes}</h5>
                                                            <small class="text-muted">Presentes</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="card border-danger">
                                                        <div class="card-body p-2">
                                                            <h5 class="text-danger">${ausentes}</h5>
                                                            <small class="text-muted">Ausentes</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                        } else {
                            reporteContent.innerHTML = `
                                <div class="alert alert-danger">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    Error al generar la vista previa: ${data.message || 'Error desconocido'}
                                </div>
                            `;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        reporteContent.innerHTML = `
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                Error al conectar con el servidor. Por favor intenta nuevamente.
                            </div>
                        `;
                    });

                    // Scroll suave hacia la vista previa
                    document.getElementById('reportePreview').scrollIntoView({ behavior: 'smooth' });
                }

                function exportarReporte() {
                    const formato = document.getElementById('formato').value;
                    const tipo = document.getElementById('tipo_reporte').value;
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

                    if (formato === 'pdf') {
                        // URL directa para descarga
                        const url = `/asistencia/exportar/pdf/admin?fecha_inicio=${encodeURIComponent(fechaInicio)}&fecha_fin=${encodeURIComponent(fechaFin)}&formato=${encodeURIComponent(formato)}&tipo_reporte=${encodeURIComponent(tipo)}`;

                        // Abrir en nueva ventana para descarga
                        window.open(url, '_blank');
                    } else {
                        // Para otros formatos, mostrar mensaje
                        Swal.fire({
                            icon: 'info',
                            title: 'Funcionalidad en desarrollo',
                            text: `La exportación a ${formato.toUpperCase()} estará disponible próximamente.`,
                            confirmButtonText: 'Entendido'
                        });
                    }
                }
                // Inicializar select2 para los dropdowns
                document.addEventListener('DOMContentLoaded', function() {
                    // Inicializar select2 para todos los selects relevantes
                    $('.form-control').each(function() {
                        if ($(this).attr('id') !== 'tipo_reporte' && $(this).attr('id') !== 'formato') {
                            $(this).select2({
                                placeholder: $(this).find('option:first').text() || 'Seleccionar...',
                                allowClear: true,
                                width: '100%',
                                theme: 'bootstrap4',
                                language: {
                                    noResults: function() {
                                        return "No se encontraron resultados";
                                    },
                                    searching: function() {
                                        return "Buscando...";
                                    }
                                }
                            });
                        }
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>
@endsection
