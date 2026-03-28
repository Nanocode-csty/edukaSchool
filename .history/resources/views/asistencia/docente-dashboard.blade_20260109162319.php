@extends('cplantilla.bprincipal')

@section('titulo','Dashboard de Asistencia - Docente')

@section('contenidoplantilla')

<x-breadcrumb :module="'asistencia'" :section="'docente-dashboard'" />

<div class="container-fluid" id="contenido-principal">
    <div class="row mt-4">
        <div class="col-12">
            <div class="box_block">
                <!-- Dashboard Header -->
                <div class="card mb-4 bg-gradient-primary text-white">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h4 class="mb-2">
                                    <i class="fas fa-tachometer-alt"></i> Dashboard de Asistencia
                                </h4>
                                <p class="mb-0 opacity-75">Panel de control integral para gestionar la asistencia de tus estudiantes</p>
                            </div>
                            <div class="col-md-4 text-right">
                                <div class="d-flex justify-content-end gap-2">
                                    <small class="text-white-50">Última actualización:</small>
                                    <strong>{{ now()->format('d/m/Y H:i') }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Key Metrics Cards -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Clases Hoy
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $estadisticas['total_clases_hoy'] ?? 0 }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-calendar-day fa-2x text-primary"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Asistencias Completadas
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $estadisticas['clases_completadas'] ?? 0 }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-check-circle fa-2x text-success"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Pendientes
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $estadisticas['asistencias_pendientes'] ?? 0 }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-clock fa-2x text-warning"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                            Estudiantes Totales
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $estadisticas['total_estudiantes'] ?? 0 }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-users fa-2x text-info"></i>
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

                <!-- Quick Actions & Today's Schedule -->
                <div class="row mb-4">
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-bolt"></i> Acciones Rápidas
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-6 mb-3">
                                        <a href="{{ route('asistencia.docente.tomar-asistencia') }}" class="btn btn-primary btn-block">
                                            <i class="fas fa-edit fa-2x mb-2"></i>
                                            <br>Tomar Asistencia
                                        </a>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <a href="{{ route('asistencia.docente.reportes') }}" class="btn btn-success btn-block">
                                            <i class="fas fa-file-alt fa-2x mb-2"></i>
                                            <br>Generar Reportes
                                        </a>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <a href="{{ route('asistencia.docente.estadisticas') }}" class="btn btn-info btn-block">
                                            <i class="fas fa-chart-bar fa-2x mb-2"></i>
                                            <br>Ver Estadísticas
                                        </a>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <a href="{{ route('asistencia.docente.ver-asistencias') }}" class="btn btn-warning btn-block">
                                            <i class="fas fa-eye fa-2x mb-2"></i>
                                            <br>Historial
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 mb-4">
                        <div class="card shadow">
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
    </div>
</div>
@endsection

@push('css-extra')
<style>
/* Weekly Calendar Styles */
.weekly-calendar {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 8px;
    padding: 15px;
}

.calendar-day {
    aspect-ratio: 0.8;
    border-radius: 6px;
    padding: 6px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid #e9ecef;
    background: white;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    position: relative;
    overflow: hidden;
    min-height: 70px;
    max-height: 80px;
}

/* Responsive calendar */
@media (max-width: 768px) {
    .weekly-calendar {
        grid-template-columns: repeat(7, 1fr);
        gap: 4px;
        padding: 10px;
    }

    .calendar-day {
        padding: 6px;
        min-height: 60px;
    }

    .day-number {
        font-size: 18px;
    }

    .day-name {
        font-size: 10px;
    }
}

@media (max-width: 576px) {
    .weekly-calendar {
        grid-template-columns: repeat(7, 1fr);
        gap: 2px;
        padding: 8px;
    }

    .calendar-day {
        padding: 4px;
        min-height: 50px;
    }

    .day-number {
        font-size: 16px;
    }

    .day-name {
        font-size: 9px;
    }
}

.calendar-day:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.calendar-day.today {
    border-color: #007bff;
    background: #f8f9ff;
}

.calendar-day.selected {
    border-color: #007bff;
    background: #f0f8ff;
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.3);
    transform: scale(1.02);
}

.calendar-day.selected .day-number,
.calendar-day.selected .day-name {
    color: #007bff;
    font-weight: bold;
}

.calendar-day.has-classes {
    border-color: #17a2b8;
}

.calendar-day.has-classes.completed {
    border-color: #28a745;
}

.calendar-day.has-classes.pending {
    border-color: #ffc107;
}

.day-number {
    font-size: 24px;
    font-weight: bold;
    color: #2c3e50;
    margin-bottom: 5px;
}

.day-name {
    font-size: 12px;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 10px;
}

.day-stats {
    display: flex;
    justify-content: space-around;
    align-items: center;
    margin-top: auto;
}

.day-stat {
    display: flex;
    flex-direction: column;
    align-items: center;
    font-size: 10px;
}

.day-stat.completed {
    color: #28a745;
}

.day-stat.pending {
    color: #ffc107;
}

.day-stat-number {
    font-weight: bold;
    font-size: 12px;
}

.day-stat-label {
    font-size: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.calendar-header {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 10px;
    margin-bottom: 10px;
    padding: 0 20px;
}

.calendar-header-day {
    text-align: center;
    font-weight: 600;
    color: #495057;
    font-size: 14px;
    padding: 10px 0;
}

.calendar-navigation {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 20px;
    background: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
}

.calendar-week-info {
    font-weight: 600;
    color: #2c3e50;
}

/* Calendar hidden class */
.calendar-hidden {
    display: none !important;
}

/* Gradient backgrounds */
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

/* Enhanced Schedule Timeline Styles */
.schedule-timeline {
    position: relative;
    padding: 15px 0;
}

.time-slot {
    display: flex;
    align-items: flex-start;
    margin-bottom: 20px;
    position: relative;
}

.time-label {
    flex-shrink: 0;
    width: 70px;
    margin-right: 15px;
    text-align: center;
}

.time-circle {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: #6c757d;
    color: white;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: bold;
    margin: 0 auto;
}

.time-circle i {
    font-size: 14px;
    margin-bottom: 1px;
}

.classes-container {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.class-card-wrapper {
    position: relative;
}

.class-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 1px 6px rgba(0,0,0,0.06);
    border: 1px solid #e9ecef;
    transition: all 0.2s ease;
    overflow: hidden;
}

.class-card:hover {
    box-shadow: 0 3px 12px rgba(0,0,0,0.1);
    border-color: #dee2e6;
}

.class-card.completed {
    border-left: 3px solid #28a745;
}

.class-card.pending {
    border-left: 3px solid #ffc107;
}

.class-header {
    display: flex;
    align-items: center;
    padding: 12px;
    gap: 12px;
}

.subject-icon {
    flex-shrink: 0;
    width: 40px;
    height: 40px;
    border-radius: 8px;
    background: #6c757d;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 16px;
}

.class-content {
    flex: 1;
    min-width: 0;
}

.subject-title {
    font-size: 15px;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 6px;
    line-height: 1.3;
}

.course-info {
    margin-bottom: 6px;
}

.grade-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: #f8f9fa;
    color: #495057;
    padding: 3px 8px;
    border-radius: 15px;
    font-size: 12px;
    font-weight: 500;
    border: 1px solid #dee2e6;
}

.grade-badge i {
    font-size: 11px;
}

.schedule-info {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.time-info,
.room-info {
    display: flex;
    align-items: center;
    gap: 4px;
    color: #6c757d;
    font-size: 12px;
    font-weight: 500;
}

.time-info i,
.room-info i {
    font-size: 11px;
    width: 12px;
}

.class-status {
    flex-shrink: 0;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 8px;
    border-radius: 15px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.status-badge.completed {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.status-badge.pending {
    background: #fff3cd;
    color: #856404;
    border: 1px solid #ffeaa7;
}

.status-badge i {
    font-size: 9px;
}

.class-actions {
    padding: 0 12px 12px 12px;
    border-top: 1px solid #f8f9fa;
    margin-top: 12px;
}

.action-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 10px 20px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
    border: none;
    cursor: pointer;
    width: 100%;
    justify-content: center;
}

.action-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.action-btn i {
    font-size: 14px;
}

.action-btn span {
    font-weight: 600;
}

.main-action-btn {
    font-size: 15px !important;
    padding: 12px 24px !important;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .time-slot {
        flex-direction: column;
        align-items: center;
        gap: 12px;
    }

    .time-label {
        width: 100%;
        margin-right: 0;
        margin-bottom: 8px;
    }

    .class-header {
        flex-direction: column;
        text-align: center;
        gap: 10px;
    }

    .subject-icon {
        align-self: center;
    }

    .schedule-info {
        justify-content: center;
    }

    .class-status {
        align-self: center;
    }

    .class-actions {
        text-align: center;
    }
}

@media (max-width: 576px) {
    .class-header {
        padding: 10px;
    }

    .subject-title {
        font-size: 14px;
    }

    .schedule-info {
        flex-direction: column;
        gap: 6px;
        align-items: center;
    }

    .class-actions {
        padding: 0 10px 10px 10px;
    }

    .action-btn {
        padding: 8px 16px;
        font-size: 13px;
    }
}
</style>
@endpush

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
@endpush>
