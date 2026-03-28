@extends('cplantilla.bprincipal')
@section('titulo','Panel de Representante')
@section('contenidoplantilla')
<x-breadcrumb :module="'asistencia'" :section="'representante-dashboard'" />

<div class="container-fluid" id="contenido-principal">
    <div class="row mt-4 ml-1 mr-1">
        <div class="col-12">
            <div class="box_block">
                <!-- Collapse header -->
                <button class="btn btn-block text-left rounded-0 btn_header header_6" type="button" data-toggle="collapse" data-target="#collapseRepresentanteDashboard" aria-expanded="true" aria-controls="collapseRepresentanteDashboard" style="background: #0A8CB3 !important; font-weight: bold; color: white;">
                    <i class="fas fa-user-shield m-1"></i>&nbsp;Panel de Representante
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
                                ¡Bienvenido, {{ auth()->user()->persona ? auth()->user()->persona->nombres . ' ' . auth()->user()->persona->apellidos : 'Representante' }}!
                                Gestiona la información académica de tus estudiantes de manera eficiente.
                            </p>
                        </div>
                    </div>
                </div>
                <!-- Collapse: contenido del dashboard -->
                <div class="collapse show" id="collapseRepresentanteDashboard">
                    <div class="card card-body rounded-0 border-0 pt-0 pb-2" style="background: transparent;">

    <!-- Quick Actions Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light border-0">
                    <h5 class="mb-0 text-primary">
                        <i class="fas fa-bolt mr-2"></i>Acciones Rápidas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('asistencia.representante.index') }}" class="btn btn-outline-primary btn-lg w-100 h-100 d-flex flex-column align-items-center justify-content-center p-4 action-card">
                                <i class="fas fa-list fa-2x mb-2"></i>
                                <span class="fw-bold">Lista de Estudiantes</span>
                                <small class="text-muted">Ver asistencia detallada</small>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('notas.misEstudiantes') }}" class="btn btn-outline-success btn-lg w-100 h-100 d-flex flex-column align-items-center justify-content-center p-4 action-card">
                                <i class="fas fa-graduation-cap fa-2x mb-2"></i>
                                <span class="fw-bold">Ver Calificaciones</span>
                                <small class="text-muted">Rendimiento académico</small>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('notificaciones.index') }}" class="btn btn-outline-info btn-lg w-100 h-100 d-flex flex-column align-items-center justify-content-center p-4 action-card">
                                <i class="fas fa-bell fa-2x mb-2"></i>
                                <span class="fw-bold">Notificaciones</span>
                                <small class="text-muted">Mensajes importantes</small>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <button type="button" class="btn btn-outline-secondary btn-lg w-100 h-100 d-flex flex-column align-items-center justify-content-center p-4 action-card" onclick="mostrarEstadisticas()">
                                <i class="fas fa-chart-pie fa-2x mb-2"></i>
                                <span class="fw-bold">Más Estadísticas</span>
                                <small class="text-muted">Análisis detallado</small>
                            </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js-extra')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Collapse icon toggle
    const btn = document.querySelector('[data-target="#collapseRepresentanteDashboard"]');
    const icon = btn.querySelector('.fas.fa-chevron-down');
    const collapse = document.getElementById('collapseRepresentanteDashboard');
    collapse.addEventListener('show.bs.collapse', function () {
        icon.classList.remove('fa-chevron-down');
        icon.classList.add('fa-chevron-up');
    });
    collapse.addEventListener('hide.bs.collapse', function () {
        icon.classList.remove('fa-chevron-up');
        icon.classList.add('fa-chevron-down');
    });
});
</script>

<style>
/* Animación de entrada */
@keyframes slideInLeft {
    from { opacity: 0; transform: translateX(-50px);}
    to { opacity: 1; transform: translateX(0);}
}
.animate-slide-in { animation: slideInLeft 0.8s ease-out; }

/* Tabla y paginación */
#add-row td, #add-row th {
    padding: 4px 8px;
    font-size: 14px;
    vertical-align: middle;
    height: 52px;
}
.table-hover tbody tr:hover {
    background-color: #FFF4E7 !important;
}
.badge-success {
    background-color: #28a745;
    color: #fff;
}
.badge-secondary {
    background-color: #6c757d;
    color: #fff;
}
/* Paginación */
.pagination {
    display: flex;
    justify-content: left;
    padding: 1rem 0;
    list-style: none;
    gap: 0.3rem;
}
.pagination li a, .pagination li span {
    color: #0A8CB3;
    border: 1px solid #0A8CB3;
    padding: 6px 12px;
    border-radius: 4px;
    text-decoration: none;
    transition: all 0.2s ease;
    font-size: 0.9rem;
}
.pagination li a:hover, .pagination li span:hover {
    background-color: #f1f1f1;
    color: #333;
}
.pagination .page-item.active .page-link {
    background-color: #0A8CB3 !important;
    color: white !important;
    border-color: #0A8CB3 !important;
}
.pagination .disabled .page-link {
    color: #ccc;
    border-color: #ccc;
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
@endpush

    <!-- Statistics Overview -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white border-0">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar mr-2"></i>Resumen Ejecutivo
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-lg-3 col-md-6">
                            <div class="stats-card text-center p-3 bg-light rounded">
                                <div class="stats-icon mb-2">
                                    <i class="fas fa-users fa-2x text-success"></i>
                                </div>
                                <div class="stats-value">
                                    <h3 class="text-success mb-1">{{ $estadisticas['total_estudiantes'] ?? 0 }}</h3>
                                    <small class="text-muted">Mis Estudiantes</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="stats-card text-center p-3 bg-light rounded">
                                <div class="stats-icon mb-2">
                                    <i class="fas fa-percentage fa-2x text-primary"></i>
                                </div>
                                <div class="stats-value">
                                    <h3 class="text-primary mb-1">{{ $estadisticas['promedio_asistencia'] ?? 0 }}%</h3>
                                    <small class="text-muted">Asistencia Promedio</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="stats-card text-center p-3 bg-light rounded">
                                <div class="stats-icon mb-2">
                                    <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
                                </div>
                                <div class="stats-value">
                                    <h3 class="text-warning mb-1">{{ $estadisticas['total_inasistencias'] ?? 0 }}</h3>
                                    <small class="text-muted">Inasistencias</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="stats-card text-center p-3 bg-light rounded">
                                <div class="stats-icon mb-2">
                                    <i class="fas fa-clock fa-2x text-danger"></i>
                                </div>
                                <div class="stats-value">
                                    <h3 class="text-danger mb-1">{{ $estadisticas['justificaciones_pendientes'] ?? 0 }}</h3>
                                    <small class="text-muted">Justificaciones Pendientes</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white border-0">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-area mr-2"></i>Análisis Visual de Rendimiento
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-lg-6">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0">
                                        <i class="fas fa-chart-line mr-2"></i>Asistencia por Mes
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="chart-container" style="position: relative; height: 300px;">
                                        <canvas id="chartAsistenciaMes"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-header bg-warning text-white">
                                    <h6 class="mb-0">
                                        <i class="fas fa-chart-pie mr-2"></i>Distribución de Asistencia
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="chart-container" style="position: relative; height: 300px;">
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
/* Action Cards */
.action-card {
    transition: all 0.3s ease;
    border: 2px solid transparent;
    min-height: 140px;
}

.action-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    border-color: currentColor;
}

/* Statistics Cards */
.stats-card {
    transition: all 0.3s ease;
    border: 1px solid rgba(0, 0, 0, 0.1);
}

.stats-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.stats-icon {
    opacity: 0.8;
}

.stats-value h3 {
    font-weight: 700;
    margin: 0;
}

/* Cards general styling */
.card {
    border-radius: 12px;
}

.card-header {
    border-radius: 12px 12px 0 0 !important;
}

/* Chart containers */
.chart-container {
    border-radius: 8px;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .action-card {
        min-height: 120px;
        padding: 1rem !important;
    }

    .action-card i {
        font-size: 1.5rem !important;
        margin-bottom: 0.5rem !important;
    }

    .stats-card {
        margin-bottom: 1rem;
    }
}

/* Hide any debug text or unwanted content */
.debug, .botman, [class*="botman"],
.botmanChatWindow, .botmanChatWindow__submit,
.botmanChatWindow__input, .botmanChatWindow__messages,
.botmanWidgetButton, .botmanWidgetBadge {
    display: none !important;
    visibility: hidden !important;
    opacity: 0 !important;
}

/* Ensure FontAwesome icons are visible - but don't override sidebar button */
.fas, .far, .fab, .fa {
    font-family: "Font Awesome 6 Free", "Font Awesome 6 Brands" !important;
    font-weight: 900;
    display: inline-block !important;
    visibility: visible !important;
    opacity: 1 !important;
}

/* Specifically ensure sidebar button is visible */
.navbar-toggler.sidenav-toggler .fas.fa-bars {
    display: inline-block !important;
    visibility: visible !important;
    opacity: 1 !important;
    font-family: "Font Awesome 6 Free", "Font Awesome 6 Brands" !important;
    font-weight: 900 !important;
}
</style>

@endsection

@push('js-extra')
<!-- FontAwesome fallback in case CDN fails -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">

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
@endpush
