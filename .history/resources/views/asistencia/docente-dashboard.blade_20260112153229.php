@extends('cplantilla.bprincipal')

@section('titulo','Dashboard de Asistencia - Docente')

@section('contenidoplantilla')

<x-breadcrumb :module="'asistencia'" :section="'docente-dashboard'" />

<div class="container-fluid" id="contenido-principal">
    <div class="row mt-4 ml-1 mr-1">
        <div class="col-12">
            <div class="box_block">
                <!-- Collapse header -->
                <button class="btn btn-block text-left rounded-0 btn_header header_6" type="button" data-toggle="collapse" data-target="#collapseDashboard" aria-expanded="true" aria-controls="collapseDashboard" style="background: #0A8CB3 !important; font-weight: bold; color: white;">
                    <i class="fas fa-tachometer-alt m-1"></i>&nbsp;Dashboard de Asistencia - Panel de Control
                    <div class="float-right"><i class="fas fa-chevron-down"></i></div>
                </button>
                <!-- Descripción (siempre visible, dentro del bloque) -->
                <div class="card-body rounded-0 border-0 pt-3 pb-3" style="background: #f3f3f3; border-bottom: 1px solid rgba(0,0,0,.125); border-top: 1px solid rgba(0,0,0,.125); color: #F59D24;">
                    <div class="row justify-content-center align-items-center flex-wrap">
                        <div class="col-auto text-center mb-2 mb-md-0" style="min-width:48px;">
                            <i class="fas fa-chart-line fa-2x"></i>
                        </div>
                        <div class="col px-2" style="text-align:justify;">
                            <p style="margin-bottom: 0px; font-family: 'Quicksand', sans-serif; font-weight: 600; color: #004a92;">
                                Bienvenido a tu panel de control de asistencia. Aquí encontrarás métricas clave, gráficos de tendencias y acceso rápido a todas las funcionalidades relacionadas con la gestión de asistencia de tus estudiantes.
                            </p>
                            <p style="font-family: 'Quicksand', sans-serif; font-weight: 600; color: #004a92;">
                                Utiliza las acciones rápidas para navegar directamente a las diferentes secciones: tomar asistencia, generar reportes, ver estadísticas detalladas o consultar el historial de asistencias.
                            </p>
                        </div>
                    </div>
                </div>
                <!-- Collapse: contenido del dashboard -->
                <div class="collapse show" id="collapseDashboard">
                    <div class="card card-body rounded-0 border-0 pt-0 pb-2" style="background: transparent;">

                        <!-- Key Metrics Cards -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="text-center p-3" style="background-color: #f8f9fa; border-radius: 8px;">
                                    <h5 style="color: #28a745; margin-bottom: 5px;">{{ $estadisticas['total_clases_hoy'] ?? 0 }}</h5>
                                    <small style="color: #6c757d;">Clases Hoy</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center p-3" style="background-color: #f8f9fa; border-radius: 8px;">
                                    <h5 style="color: #007bff; margin-bottom: 5px;">{{ $estadisticas['clases_completadas'] ?? 0 }}</h5>
                                    <small style="color: #6c757d;">Asistencias Completadas</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center p-3" style="background-color: #f8f9fa; border-radius: 8px;">
                                    <h5 style="color: #ffc107; margin-bottom: 5px;">{{ $estadisticas['asistencias_pendientes'] ?? 0 }}</h5>
                                    <small style="color: #6c757d;">Pendientes</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center p-3" style="background-color: #f8f9fa; border-radius: 8px;">
                                    <h5 style="color: #17a2b8; margin-bottom: 5px;">{{ $estadisticas['total_estudiantes'] ?? 0 }}</h5>
                                    <small style="color: #6c757d;">Estudiantes Totales</small>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions Row -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">
                                            <i class="fas fa-bolt"></i> Acciones Rápidas
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3 mb-3">
                                                <a href="{{ route('asistencia.docente.tomar-asistencia') }}" class="btn btn-primary btn-block">
                                                    <i class="fas fa-edit fa-2x mb-2"></i>
                                                    <br>Tomar Asistencia
                                                </a>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <a href="{{ route('asistencia.docente.estadisticas') }}" class="btn btn-info btn-block">
                                                    <i class="fas fa-chart-bar fa-2x mb-2"></i>
                                                    <br>Ver Estadísticas
                                                </a>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <a href="{{ route('asistencia.docente.index') }}" class="btn btn-warning btn-block">
                                                    <i class="fas fa-eye fa-2x mb-2"></i>
                                                    <br>Historial
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Charts Row -->
                        <div class="row mb-4">
                            <div class="col-xl-8 col-lg-7">
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                        <h6 class="m-0 font-weight-bold text-primary">
                                            <i class="fas fa-chart-line"></i> Tendencia de Asistencia - Últimos 6 Meses
                                        </h6>
                                        <div class="dropdown no-arrow">
                                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                                                <div class="dropdown-header">Opciones:</div>
                                                <a class="dropdown-item" href="{{ route('asistencia.docente.estadisticas') }}">
                                                    <i class="fas fa-chart-bar fa-sm fa-fw mr-2 text-gray-400"></i>
                                                    Ver Estadísticas Detalladas
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-area">
                                            <canvas id="attendanceChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-4 col-lg-5">
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">
                                            <i class="fas fa-chart-pie"></i> Distribución por Tipo
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-pie pt-4">
                                            <canvas id="attendancePieChart"></canvas>
                                        </div>
                                        <hr>
                                        <div class="text-center small">
                                            <span class="mr-2">
                                                <i class="fas fa-circle text-success"></i> Presente
                                            </span>
                                            <span class="mr-2">
                                                <i class="fas fa-circle text-danger"></i> Ausente
                                            </span>
                                            <span class="mr-2">
                                                <i class="fas fa-circle text-warning"></i> Tarde
                                            </span>
                                            <span class="mr-2">
                                                <i class="fas fa-circle text-info"></i> Justificado
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Today's Schedule - Full Width -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                        <h6 class="m-0 font-weight-bold text-primary">
                                            <i class="fas fa-calendar-day"></i> Clases de Hoy
                                        </h6>
                                        <div class="dropdown no-arrow">
                                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink2">
                                                <div class="dropdown-header">Opciones:</div>
                                                <a class="dropdown-item" href="{{ route('asistencia.docente.tomar-asistencia') }}">
                                                    <i class="fas fa-edit fa-sm fa-fw mr-2 text-gray-400"></i>
                                                    Ver Todas las Clases
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        @if($clases_hoy->count() > 0)
                                            <div class="timeline timeline-xs">
                                                @foreach($clases_hoy->take(5) as $clase)
                                                <div class="timeline-item">
                                                    <div class="timeline-marker {{ $clase->tiene_asistencia_hoy ? 'bg-success' : 'bg-warning' }}"></div>
                                                    <div class="timeline-content">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div>
                                                                <h6 class="mb-0">{{ $clase->cursoAsignatura->asignatura->nombre }}</h6>
                                                                <small class="text-muted">
                                                                    {{ $clase->cursoAsignatura->curso->grado->nombre }} {{ $clase->cursoAsignatura->curso->seccion->nombre }}
                                                                    • {{ substr($clase->hora_inicio, 0, 5) }} - {{ substr($clase->hora_fin, 0, 5) }}
                                                                </small>
                                                            </div>
                                                            <span class="badge {{ $clase->tiene_asistencia_hoy ? 'badge-success' : 'badge-warning' }}">
                                                                {{ $clase->tiene_asistencia_hoy ? 'Completada' : 'Pendiente' }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                            @if($clases_hoy->count() > 5)
                                                <div class="text-center mt-3">
                                                    <small class="text-muted">Y {{ $clases_hoy->count() - 5 }} clases más...</small>
                                                </div>
                                            @endif
                                        @else
                                            <div class="text-center py-4">
                                                <i class="fas fa-calendar-times fa-3x text-gray-300 mb-3"></i>
                                                <h6 class="text-muted">No hay clases programadas para hoy</h6>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <script>
                document.addEventListener('DOMContentLoaded', function () {
                    // Collapse icon toggle
                    const btn = document.querySelector('[data-target="#collapseDashboard"]');
                    const icon = btn.querySelector('.fas.fa-chevron-down');
                    const collapse = document.getElementById('collapseDashboard');
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
            </div>
        </div>
    </div>
</div>
@endsection

@push('js-extra')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Initialize charts when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize attendance trend chart
    const attendanceChartCanvas = document.getElementById('attendanceChart');
    if (attendanceChartCanvas) {
        const ctx = attendanceChartCanvas.getContext('2d');

        // Sample data - in production this would come from the server
        const attendanceData = {
            labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
            datasets: [{
                label: 'Porcentaje de Asistencia',
                data: [85, 87, 82, 89, 91, 88],
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#007bff',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 8
            }]
        };

        new Chart(ctx, {
            type: 'line',
            data: attendanceData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y + '% de asistencia';
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
                        },
                        grid: {
                            color: 'rgba(0,0,0,0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                elements: {
                    point: {
                        hoverBorderWidth: 3
                    }
                }
            }
        });
    }

    // Initialize attendance pie chart
    const pieChartCanvas = document.getElementById('attendancePieChart');
    if (pieChartCanvas) {
        const ctx = pieChartCanvas.getContext('2d');

        // Sample data - in production this would come from the server
        const pieData = {
            labels: ['Presente', 'Ausente', 'Tarde', 'Justificado'],
            datasets: [{
                data: [75, 15, 5, 5], // Percentages
                backgroundColor: [
                    '#28a745', // Success - Present
                    '#dc3545', // Danger - Absent
                    '#ffc107', // Warning - Late
                    '#17a2b8'  // Info - Justified
                ],
                borderColor: [
                    '#1e7e34',
                    '#bd2130',
                    '#d39e00',
                    '#138496'
                ],
                borderWidth: 2,
                hoverBorderWidth: 3,
                hoverOffset: 10
            }]
        };

        new Chart(ctx, {
            type: 'doughnut',
            data: pieData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false // Hide legend since we show it below
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                return label + ': ' + value + '%';
                            }
                        }
                    }
                },
                cutout: '60%',
                elements: {
                    arc: {
                        borderRadius: 4
                    }
                }
            }
        });
    }
});
</script>
@endpush
