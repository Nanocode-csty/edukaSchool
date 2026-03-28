@extends('cplantilla.bprincipal')

@section('titulo', 'Dashboard Administrativo - Eduka Perú')

@section('contenidoplantilla')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Dashboard Administrativo de Asistencias</h4>
                <p class="card-category">Año Lectivo: {{ $anioActual ? $anioActual->nombre : 'No definido' }}</p>
            </div>
            <div class="card-body">
                <!-- Estadísticas principales -->
                <div class="row">
                    <div class="col-md-3">
                        <div class="card card-stats card-round">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-5">
                                        <div class="icon-big text-center">
                                            <i class="fas fa-users text-primary"></i>
                                        </div>
                                    </div>
                                    <div class="col-7 col-stats">
                                        <div class="numbers">
                                            <p class="card-category">Total Estudiantes</p>
                                            <h4 class="card-title">{{ number_format($estadisticas['total_estudiantes']) }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card card-stats card-round">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-5">
                                        <div class="icon-big text-center">
                                            <i class="fas fa-chalkboard-teacher text-success"></i>
                                        </div>
                                    </div>
                                    <div class="col-7 col-stats">
                                        <div class="numbers">
                                            <p class="card-category">Total Profesores</p>
                                            <h4 class="card-title">{{ number_format($estadisticas['total_profesores']) }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card card-stats card-round">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-5">
                                        <div class="icon-big text-center">
                                            <i class="fas fa-check-circle text-info"></i>
                                        </div>
                                    </div>
                                    <div class="col-7 col-stats">
                                        <div class="numbers">
                                            <p class="card-category">Asistencias Hoy</p>
                                            <h4 class="card-title">{{ number_format($estadisticas['asistencias_hoy']) }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card card-stats card-round">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-5">
                                        <div class="icon-big text-center">
                                            <i class="fas fa-times-circle text-danger"></i>
                                        </div>
                                    </div>
                                    <div class="col-7 col-stats">
                                        <div class="numbers">
                                            <p class="card-category">Inasistencias Hoy</p>
                                            <h4 class="card-title">{{ number_format($estadisticas['inasistencias_hoy']) }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Acciones rápidas -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Acciones Administrativas</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <a href="{{ route('asistencia.admin-index') }}" class="btn btn-primary btn-block">
                                            <i class="fas fa-chart-line"></i>
                                            <br>Administrar Asistencias
                                        </a>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="{{ route('asistencia.verificar') }}" class="btn btn-warning btn-block">
                                            <i class="fas fa-clipboard-check"></i>
                                            <br>Gestionar Justificaciones
                                        </a>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="#" class="btn btn-success btn-block" onclick="exportarReportes()">
                                            <i class="fas fa-download"></i>
                                            <br>Exportar Reportes
                                        </a>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="#" class="btn btn-info btn-block" onclick="verConfiguracion()">
                                            <i class="fas fa-cogs"></i>
                                            <br>Configuración
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gráfico de asistencias recientes -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Asistencias de los Últimos 7 Días</h4>
                            </div>
                            <div class="card-body">
                                <canvas id="chartAsistencias" width="400" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    cargarGraficoAsistencias();
});

function cargarGraficoAsistencias() {
    // Datos de ejemplo - en producción se cargarían desde la API
    const ctx = document.getElementById('chartAsistencias').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'],
            datasets: [{
                label: 'Presentes',
                data: [85, 90, 88, 92, 87, 0, 0],
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                tension: 0.4
            }, {
                label: 'Ausentes',
                data: [15, 10, 12, 8, 13, 0, 0],
                borderColor: '#dc3545',
                backgroundColor: 'rgba(220, 53, 69, 0.1)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Tendencia de Asistencias'
                }
            }
        }
    });
}

function exportarReportes() {
    Swal.fire({
        title: 'Exportar Reportes',
        text: 'Selecciona el tipo de reporte que deseas exportar',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Reporte General',
        cancelButtonText: 'Por Curso',
        showDenyButton: true,
        denyButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '{{ route("asistencia.exportar-pdf-admin") }}';
        } else if (result.isDismissed && result.dismiss === 'cancel') {
            // Acción para reporte por curso
            Swal.fire('Funcionalidad próximamente', 'El reporte por curso estará disponible pronto.', 'info');
        }
    });
}

function verConfiguracion() {
    window.location.href = '{{ route("asistencia.configuracion") }}';
}
</script>
@endsection
