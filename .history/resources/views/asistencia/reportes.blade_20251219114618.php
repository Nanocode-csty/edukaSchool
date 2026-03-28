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
                                                            <option value="html">Vista Web</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row" id="filtrosAdicionales" style="display: none;">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="nivel_id">Nivel Educativo</label>
                                                        <select class="form-control" id="nivel_id" name="nivel_id">
                                                            <option value="">Todos los niveles</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="curso_id">Curso</label>
                                                        <select class="form-control" id="curso_id" name="curso_id">
                                                            <option value="">Todos los cursos</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="estudiante_id">Estudiante</label>
                                                        <select class="form-control" id="estudiante_id" name="estudiante_id">
                                                            <option value="">Todos los estudiantes</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12 text-center">
                                                    <button type="button" class="btn btn-primary btn-lg" onclick="generarReporte()">
                                                        <i class="fas fa-chart-line mr-2"></i>Generar Reporte
                                                    </button>
                                                    <button type="button" class="btn btn-success btn-lg ml-2" onclick="exportarReporte()">
                                                        <i class="fas fa-download mr-2"></i>Exportar
                                                    </button>
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
                            <div class="col-md-3">
                                <div class="card text-center" style="border-left: 4px solid #28a745;">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-center mb-2">
                                            <i class="fas fa-user-check fa-2x text-success mr-3"></i>
                                            <div>
                                                <h3 class="mb-0 text-success">94.2%</h3>
                                                <small class="text-muted">Asistencia Promedio</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-center" style="border-left: 4px solid #dc3545;">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-center mb-2">
                                            <i class="fas fa-user-times fa-2x text-danger mr-3"></i>
                                            <div>
                                                <h3 class="mb-0 text-danger">127</h3>
                                                <small class="text-muted">Inasistencias Totales</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-center" style="border-left: 4px solid #ffc107;">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-center mb-2">
                                            <i class="fas fa-clock fa-2x text-warning mr-3"></i>
                                            <div>
                                                <h3 class="mb-0 text-warning">23</h3>
                                                <small class="text-muted">Llegadas Tarde</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-center" style="border-left: 4px solid #17a2b8;">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-center mb-2">
                                            <i class="fas fa-check-circle fa-2x text-info mr-3"></i>
                                            <div>
                                                <h3 class="mb-0 text-info">89</h3>
                                                <small class="text-muted">Justificaciones Aprobadas</small>
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
                                    <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                                        <h5 class="mb-0"><i class="fas fa-chart-area mr-2"></i>Tendencia de Asistencia Mensual</h5>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="tendenciaChart" height="300"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
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
                                    <div class="card-header" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
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
                                                    <tr>
                                                        <td>{{ now()->format('d/m/Y H:i') }}</td>
                                                        <td><span class="badge badge-primary">General</span></td>
                                                        <td>Noviembre 2025</td>
                                                        <td><span class="badge badge-success">PDF</span></td>
                                                        <td>Admin Sistema</td>
                                                        <td>
                                                            <button class="btn btn-sm btn-outline-primary">
                                                                <i class="fas fa-download"></i> Descargar
                                                            </button>
                                                            <button class="btn btn-sm btn-outline-info">
                                                                <i class="fas fa-eye"></i> Ver
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{ now()->subDays(1)->format('d/m/Y H:i') }}</td>
                                                        <td><span class="badge badge-info">Por Curso</span></td>
                                                        <td>Octubre 2025</td>
                                                        <td><span class="badge badge-warning">Excel</span></td>
                                                        <td>Admin Sistema</td>
                                                        <td>
                                                            <button class="btn btn-sm btn-outline-primary">
                                                                <i class="fas fa-download"></i> Descargar
                                                            </button>
                                                            <button class="btn btn-sm btn-outline-info">
                                                                <i class="fas fa-eye"></i> Ver
                                                            </button>
                                                        </td>
                                                    </tr>
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

                    // Toggle filtros adicionales
                    document.getElementById('tipo_reporte').addEventListener('change', function() {
                        const filtrosAdicionales = document.getElementById('filtrosAdicionales');
                        if (this.value !== 'general') {
                            filtrosAdicionales.style.display = 'flex';
                        } else {
                            filtrosAdicionales.style.display = 'none';
                        }
                    });

                    // Inicializar gráficos
                    initCharts();
                });

                function initCharts() {
                    // Tendencia mensual
                    const ctxTendencia = document.getElementById('tendenciaChart').getContext('2d');
                    new Chart(ctxTendencia, {
                        type: 'line',
                        data: {
                            labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                            datasets: [{
                                label: 'Asistencia Promedio (%)',
                                data: [92, 93, 91, 94, 95, 93, 96, 94, 95, 93, 94, 94],
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

                    // Distribución por tipo
                    const ctxDistribucion = document.getElementById('distribucionChart').getContext('2d');
                    new Chart(ctxDistribucion, {
                        type: 'doughnut',
                        data: {
                            labels: ['Presente', 'Ausente', 'Tarde', 'Justificado'],
                            datasets: [{
                                data: [78, 15, 4, 3],
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
                    // Mostrar vista previa
                    document.getElementById('reportePreview').style.display = 'block';

                    // Simular contenido del reporte
                    const reporteContent = document.getElementById('reporteContent');
                    reporteContent.innerHTML = `
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle mr-2"></i>
                            Reporte generado exitosamente. Utiliza el botón "Exportar" para descargar el archivo.
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Resumen Ejecutivo</h5>
                                <ul class="list-group">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Total de Registros Analizados
                                        <span class="badge badge-primary badge-pill">1,247</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Promedio de Asistencia General
                                        <span class="badge badge-success badge-pill">94.2%</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Mayor Tasa de Inasistencia
                                        <span class="badge badge-warning badge-pill">5.8%</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h5>Observaciones</h5>
                                <div class="alert alert-light">
                                    <small>La asistencia general se mantiene en niveles óptimos. Se recomienda seguimiento continuo en cursos con índices por debajo del 90%.</small>
                                </div>
                            </div>
                        </div>
                    `;

                    // Scroll suave hacia la vista previa
                    document.getElementById('reportePreview').scrollIntoView({ behavior: 'smooth' });
                }

                function exportarReporte() {
                    const formato = document.getElementById('formato').value;
                    const tipo = document.getElementById('tipo_reporte').value;

                    // Simular descarga
                    Swal.fire({
                        icon: 'success',
                        title: '¡Exportación Exitosa!',
                        text: `El reporte se ha generado en formato ${formato.toUpperCase()}`,
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
                </script>
            </div>
        </div>
    </div>
</div>
@endsection
