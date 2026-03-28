@extends('cplantilla.bprincipal')

@section('titulo','Panel de Representante')
@section('contenidoplantilla')
<x-breadcrumb :module="'asistencia'" :section="'representante-dashboard'" />
<div class="container-fluid" id="contenido-principal">
    <div class="row mt-4 ml-1 mr-1">
        <div class="col-12">
            <div class="box_block">
                <!-- Collapse header -->
                <button class="btn btn-block text-left rounded-0 btn_header header_6" type="button" data-toggle="collapse" data-target="#collapseDashboardRepresentante" aria-expanded="true" aria-controls="collapseDashboardRepresentante" style="background: #0A8CB3 !important; font-weight: bold; color: white;">
                    <i class="fas fa-tachometer-alt m-1"></i>&nbsp;Panel de Representante
                    <div class="float-right"><i class="fas fa-chevron-down"></i></div>
                </button>
                <!-- Descripción (siempre visible, dentro del bloque) -->
                <div class="card-body rounded-0 border-0 pt-3 pb-3" style="background: #f3f3f3; border-bottom: 1px solid rgba(0,0,0,.125); border-top: 1px solid rgba(0,0,0,.125); color: #F59D24;">
                    <div class="row justify-content-center align-items-center flex-wrap">
                        <div class="col-auto text-center mb-2 mb-md-0" style="min-width:48px;">
                            <i class="fas fa-user-shield fa-2x"></i>
                        </div>
                        <div class="col px-2" style="text-align:justify;">
                            <p style="margin-bottom: 0px; font-family: 'Quicksand', sans-serif; font-weight: 600; color: #004a92;">
                                ¡Bienvenido, {{ auth()->user()->persona ? auth()->user()->persona->nombres . ' ' . auth()->user()->persona->apellidos : 'Representante' }}! En esta sección puedes acceder fácilmente a toda la información académica de tus estudiantes representados.
                            </p>
                            <p style="font-family: 'Quicksand', sans-serif; font-weight: 600; color: #004a92;">
                                Utiliza las opciones disponibles para consultar asistencias, calificaciones y gestionar justificaciones de manera organizada y eficiente.
                            </p>
                        </div>
                    </div>
                </div>
                <!-- Collapse: contenido organizado del dashboard -->
                <div class="collapse show" id="collapseDashboardRepresentante">
                    <div class="card card-body rounded-0 border-0 pt-0 pb-2" style="background: transparent;">

                        <!-- Resumen Ejecutivo - Estadísticas Clave -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card border-primary" style="border-width: 2px;">
                                    <div class="card-header" style="background: linear-gradient(135deg, #0A8CB3 0%, #28aece 100%); color: white;">
                                        <h5 class="mb-0">
                                            <i class="fas fa-chart-bar mr-2"></i>Resumen Ejecutivo
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="stats-compact d-flex justify-content-between align-items-center p-3 rounded" style="background: #f8f9fa; border: 1px solid #dee2e6;">
                                            <div class="stat-item text-center">
                                                <div class="stat-value">
                                                    <span class="badge badge-success stat-badge">{{ $estadisticas['total_estudiantes'] ?? 0 }}</span>
                                                </div>
                                                <small class="text-muted d-block">Mis Estudiantes</small>
                                            </div>
                                            <div class="stat-item text-center">
                                                <div class="stat-value">
                                                    <span class="badge badge-primary stat-badge">{{ $estadisticas['promedio_asistencia'] ?? 0 }}%</span>
                                                </div>
                                                <small class="text-muted d-block">Asistencia Promedio</small>
                                            </div>
                                            <div class="stat-item text-center">
                                                <div class="stat-value">
                                                    <span class="badge badge-warning stat-badge">{{ $estadisticas['total_inasistencias'] ?? 0 }}</span>
                                                </div>
                                                <small class="text-muted d-block">Inasistencias</small>
                                            </div>
                                            <div class="stat-item text-center">
                                                <div class="stat-value">
                                                    <span class="badge badge-danger stat-badge">{{ $estadisticas['justificaciones_pendientes'] ?? 0 }}</span>
                                                </div>
                                                <small class="text-muted d-block">Justificaciones Pendientes</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Acciones Rápidas - Funciones Comunes -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card border-secondary" style="border-width: 2px;">
                                    <div class="card-header" style="background: linear-gradient(135deg, #6c757d 0%, #495057 100%); color: white;">
                                        <h5 class="mb-0">
                                            <i class="fas fa-bolt mr-2"></i>Acciones Rápidas
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row text-center">
                                            <div class="col-md-3 col-sm-6 mb-2">
                                                <a href="{{ route('asistencia.representante.index') }}" class="btn btn-outline-primary btn-block py-2">
                                                    <i class="fas fa-list fa-lg mb-1"></i><br>
                                                    <small><strong>Lista de Estudiantes</strong></small>
                                                </a>
                                            </div>
                                            <div class="col-md-3 col-sm-6 mb-2">
                                                <a href="{{ route('notas.misEstudiantes') }}" class="btn btn-outline-success btn-block py-2">
                                                    <i class="fas fa-graduation-cap fa-lg mb-1"></i><br>
                                                    <small><strong>Ver Notas</strong></small>
                                                </a>
                                            </div>
                                            <div class="col-md-3 col-sm-6 mb-2">
                                                <a href="{{ route('notificaciones.index') }}" class="btn btn-outline-info btn-block py-2">
                                                    <i class="fas fa-bell fa-lg mb-1"></i><br>
                                                    <small><strong>Notificaciones</strong></small>
                                                </a>
                                            </div>
                                            <div class="col-md-3 col-sm-6 mb-2">
                                                <button type="button" class="btn btn-outline-secondary btn-block py-2" onclick="mostrarEstadisticas()">
                                                    <i class="fas fa-chart-pie fa-lg mb-1"></i><br>
                                                    <small><strong>Más Estadísticas</strong></small>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Análisis Visual - Gráficos -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card border-info" style="border-width: 2px;">
                                    <div class="card-header" style="background: linear-gradient(135deg, #17a2b8 0%, #28aece 100%); color: white;">
                                        <h5 class="mb-0">
                                            <i class="fas fa-chart-area mr-2"></i>Análisis Visual de Rendimiento
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <div class="card h-100">
                                                    <div class="card-header" style="background: #0A8CB3; color: white;">
                                                        <h6 class="mb-0">
                                                            <i class="fas fa-chart-line mr-2"></i>Asistencia por Mes
                                                        </h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="chart-container" style="position: relative; height: 250px;">
                                                            <canvas id="chartAsistenciaMes"></canvas>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <div class="card h-100">
                                                    <div class="card-header" style="background: #ffc107; color: white;">
                                                        <h6 class="mb-0">
                                                            <i class="fas fa-chart-pie mr-2"></i>Distribución de Asistencia
                                                        </h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="chart-container" style="position: relative; height: 250px;">
                                                            <canvas id="chartDistribucion"></canvas>
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
                $(document).ready(function() {
                    // Collapse icon toggle
                    const btn = $('[data-target="#collapseDashboardRepresentante"]');
                    const icon = btn.find('.fas.fa-chevron-down, .fas.fa-chevron-up');

                    $('#collapseDashboardRepresentante').on('show.bs.collapse', function () {
                        icon.removeClass('fa-chevron-down').addClass('fa-chevron-up');
                    });

                    $('#collapseDashboardRepresentante').on('hide.bs.collapse', function () {
                        icon.removeClass('fa-chevron-up').addClass('fa-chevron-down');
                    });
                });
                </script>
            </div>
        </div>
    </div>

    <!-- Modal de Estadísticas -->
    <div class="modal fade" id="modalEstadisticas" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #0A8CB3 0%, #28aece 100%); color: white;">
                    <h5 class="modal-title">
                        <i class="fas fa-chart-pie mr-2"></i>Estadísticas Detalladas
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="text-center p-3 bg-light rounded">
                                <h3 class="text-primary">{{ $estadisticas['total_estudiantes'] ?? 0 }}</h3>
                                <p class="mb-0">Total de Estudiantes</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-center p-3 bg-light rounded">
                                <h3 class="text-success">{{ $estadisticas['promedio_asistencia'] ?? 0 }}%</h3>
                                <p class="mb-0">Asistencia Promedio</p>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="text-center p-3 bg-light rounded">
                                <h3 class="text-warning">{{ $estadisticas['total_inasistencias'] ?? 0 }}</h3>
                                <p class="mb-0">Total Inasistencias</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-center p-3 bg-light rounded">
                                <h3 class="text-info">{{ $estadisticas['justificaciones_pendientes'] ?? 0 }}</h3>
                                <p class="mb-0">Justificaciones Pendientes</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
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

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Initialize charts
    initializeCharts();
});

function mostrarEstadisticas() {
    $('#modalEstadisticas').modal('show');
}

function initializeCharts() {
    // Sample data for attendance by month (last 6 months)
    const meses = ['Ago', 'Sep', 'Oct', 'Nov', 'Dic', 'Ene'];
    const asistenciaData = [85, 87, 82, 89, 91, 88]; // Sample percentages

    // Chart for attendance by month
    const ctxMes = document.getElementById('chartAsistenciaMes');
    if (ctxMes) {
        new Chart(ctxMes.getContext('2d'), {
            type: 'line',
            data: {
                labels: meses,
                datasets: [{
                    label: 'Asistencia (%)',
                    data: asistenciaData,
                    borderColor: '#0A8CB3',
                    backgroundColor: 'rgba(10, 140, 179, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#0A8CB3',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `Asistencia: ${context.parsed.y}%`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    }
                }
            }
        });
    }

    // Chart for attendance distribution
    const ctxDistribucion = document.getElementById('chartDistribucion');
    if (ctxDistribucion) {
        new Chart(ctxDistribucion.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Presentes', 'Ausentes', 'Tardanzas', 'Justificados'],
                datasets: [{
                    data: [75, 15, 5, 5], // Sample percentages
                    backgroundColor: [
                        '#28a745', // Presentes - green
                        '#dc3545', // Ausentes - red
                        '#ffc107', // Tardanzas - yellow
                        '#17a2b8'  // Justificados - blue
                    ],
                    borderWidth: 2,
                    borderColor: '#fff',
                    hoverBorderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                return `${label}: ${value}%`;
                            }
                        }
                    }
                },
                cutout: '60%'
            }
        });
    }
}
</script>
@endsection
                        '#ffc107', // Tardanzas - yellow
