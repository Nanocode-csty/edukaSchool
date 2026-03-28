@extends('cplantilla.bprincipal')
@section('titulo','Estadísticas de Asistencia - Docente')
@section('contenidoplantilla')
<x-breadcrumb :module="'asistencia'" :section="'docente-estadisticas'" />
<div class="container-fluid" id="contenido-principal">
    <div class="row mt-4 ml-1 mr-1">
        <div class="col-12">
            <div class="box_block">
                <!-- Collapse header -->
                <button class="btn btn-block text-left rounded-0 btn_header header_6" type="button" data-toggle="collapse" data-target="#collapseEstadisticas" aria-expanded="true" aria-controls="collapseEstadisticas" style="background: #17a2b8 !important; font-weight: bold; color: white;">
                    <i class="fas fa-chart-line m-1"></i>&nbsp;Estadísticas de Asistencia
                    <div class="float-right"><i class="fas fa-chevron-down"></i></div>
                </button>
                <!-- Descripción (siempre visible, dentro del bloque) -->
                <div class="card-body rounded-0 border-0 pt-3 pb-3" style="background: #f3f3f3; border-bottom: 1px solid rgba(0,0,0,.125); border-top: 1px solid rgba(0,0,0,.125); color: #F59D24;">
                    <div class="row justify-content-center align-items-center flex-wrap">
                        <div class="col-auto text-center mb-2 mb-md-0" style="min-width:48px;">
                            <i class="fas fa-chart-pie fa-2x"></i>
                        </div>
                        <div class="col px-2" style="text-align:justify;">
                            <p style="margin-bottom: 0px; font-family: 'Quicksand', sans-serif; font-weight: 600; color: #004a92;">
                                Visualiza estadísticas detalladas de asistencia por curso y asignatura. Analiza tendencias y patrones de asistencia.
                            </p>
                        </div>
                    </div>
                </div>
                <!-- Collapse: contenido del dashboard -->
                <div class="collapse show" id="collapseEstadisticas">
                    <div class="card card-body rounded-0 border-0 pt-0 pb-2" style="background: transparent;">

                        @if(isset($error))
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle"></i> {{ $error }}
                            </div>
                        @else
                            <!-- Estadísticas Generales -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <h5 class="mb-3"><i class="fas fa-chart-bar"></i> Estadísticas del Mes: {{ $estadisticas['mes_actual'] }} {{ $estadisticas['anio_actual'] }}</h5>

                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="card text-center" style="border: 2px solid #28a745;">
                                                <div class="card-body">
                                                    <h3 class="text-success">{{ $estadisticas['total_asistencias'] }}</h3>
                                                    <p class="mb-0">Total Asistencias</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card text-center" style="border: 2px solid #007bff;">
                                                <div class="card-body">
                                                    <h3 class="text-primary">{{ $estadisticas['total_presentes'] }}</h3>
                                                    <p class="mb-0">Presentes</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card text-center" style="border: 2px solid #dc3545;">
                                                <div class="card-body">
                                                    <h3 class="text-danger">{{ $estadisticas['total_ausentes'] }}</h3>
                                                    <p class="mb-0">Ausentes</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card text-center" style="border: 2px solid {{ $estadisticas['porcentaje_asistencia'] >= 80 ? '#28a745' : ($estadisticas['porcentaje_asistencia'] >= 60 ? '#ffc107' : '#dc3545') }};">
                                                <div class="card-body">
                                                    <h3 class="{{ $estadisticas['porcentaje_asistencia'] >= 80 ? 'text-success' : ($estadisticas['porcentaje_asistencia'] >= 60 ? 'text-warning' : 'text-danger') }}">{{ $estadisticas['porcentaje_asistencia'] }}%</h3>
                                                    <p class="mb-0">Porcentaje Asistencia</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Gráfico de Distribución -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="mb-0"><i class="fas fa-chart-pie"></i> Distribución por Tipo</h5>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="tipoAsistenciaChart" width="400" height="300"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="mb-0"><i class="fas fa-chart-line"></i> Tendencia Mensual</h5>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="tendenciaChart" width="400" height="300"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Estadísticas por Curso -->
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <h5 class="mb-3"><i class="fas fa-graduation-cap"></i> Estadísticas por Curso</h5>

                                    <div class="table-responsive">
                                        <table class="table table-hover" style="border: 1px solid #17a2b8; border-radius: 10px; overflow: hidden;">
                                            <thead class="text-center" style="background-color: #f8f9fa; color: #17a2b8;">
                                                <tr>
                                                    <th scope="col">Curso</th>
                                                    <th scope="col">Asignatura</th>
                                                    <th scope="col">Total Asistencias</th>
                                                    <th scope="col">Presentes</th>
                                                    <th scope="col">Porcentaje</th>
                                                    <th scope="col">Estado</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($estadisticas['estadisticas_por_curso'] as $curso)
                                                <tr>
                                                    <td>{{ $curso['curso'] }}</td>
                                                    <td>{{ $curso['asignatura'] }}</td>
                                                    <td class="text-center">{{ $curso['total_asistencias'] }}</td>
                                                    <td class="text-center">{{ $curso['presentes'] }}</td>
                                                    <td class="text-center">
                                                        <span class="badge badge-{{ $curso['porcentaje'] >= 80 ? 'success' : ($curso['porcentaje'] >= 60 ? 'warning' : 'danger') }}">
                                                            {{ $curso['porcentaje'] }}%
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        @if($curso['porcentaje'] >= 80)
                                                            <span class="text-success"><i class="fas fa-check-circle"></i> Excelente</span>
                                                        @elseif($curso['porcentaje'] >= 60)
                                                            <span class="text-warning"><i class="fas fa-exclamation-triangle"></i> Regular</span>
                                                        @else
                                                            <span class="text-danger"><i class="fas fa-times-circle"></i> Crítico</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="6" class="text-center">
                                                        <i class="fas fa-chart-line text-muted fa-2x mb-2"></i>
                                                        <br>No hay estadísticas disponibles para este período
                                                    </td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Resumen Ejecutivo -->
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="mb-0"><i class="fas fa-clipboard-check"></i> Resumen Ejecutivo</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6><i class="fas fa-trophy text-warning"></i> Mejor Rendimiento</h6>
                                                    @php
                                                        $mejorCurso = collect($estadisticas['estadisticas_por_curso'])->sortByDesc('porcentaje')->first();
                                                    @endphp
                                                    @if($mejorCurso)
                                                        <p class="mb-1"><strong>{{ $mejorCurso['curso'] }}</strong></p>
                                                        <p class="text-success mb-0">{{ $mejorCurso['porcentaje'] }}% de asistencia</p>
                                                    @else
                                                        <p class="text-muted">No hay datos disponibles</p>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <h6><i class="fas fa-exclamation-triangle text-danger"></i> Requiere Atención</h6>
                                                    @php
                                                        $peorCurso = collect($estadisticas['estadisticas_por_curso'])->sortBy('porcentaje')->first();
                                                    @endphp
                                                    @if($peorCurso && $peorCurso['porcentaje'] < 70)
                                                        <p class="mb-1"><strong>{{ $peorCurso['curso'] }}</strong></p>
                                                        <p class="text-danger mb-0">{{ $peorCurso['porcentaje'] }}% de asistencia</p>
                                                    @else
                                                        <p class="text-success">Todos los cursos mantienen buen rendimiento</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Acciones Rápidas -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-end gap-2 flex-wrap">
                                        <button class="btn btn-sm" onclick="exportarEstadisticas()" style="background-color: #28a745 !important; color: white !important; border: none !important;">
                                            <i class="fas fa-download mr-1"></i>Exportar Reporte
                                        </button>
                                        <a href="{{ route('asistencia.docente.dashboard') }}" class="btn btn-sm" style="background-color: #6c757d !important; color: white !important; border: none !important;">
                                            <i class="fas fa-arrow-left mr-1"></i>Volver al Dashboard
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js-extra')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Collapse icon toggle
    const btn = document.querySelector('[data-target="#collapseEstadisticas"]');
    const icon = btn.querySelector('.fas.fa-chevron-down');
    const collapse = document.getElementById('collapseEstadisticas');
    collapse.addEventListener('show.bs.collapse', function () {
        icon.classList.remove('fa-chevron-down');
        icon.classList.add('fa-chevron-up');
    });
    collapse.addEventListener('hide.bs.collapse', function () {
        icon.classList.remove('fa-chevron-up');
        icon.classList.add('fa-chevron-down');
    });

    // Inicializar gráficos
    inicializarGraficos();
});

function inicializarGraficos() {
    // Datos para el gráfico de tipos de asistencia
    const tiposData = {
        labels: ['Presentes', 'Ausentes', 'Tardes', 'Justificados'],
        datasets: [{
            data: [
                {{ $estadisticas['total_presentes'] }},
                {{ $estadisticas['total_ausentes'] }},
                0, // Tardes - no calculado en el método actual
                0  // Justificados - no calculado en el método actual
            ],
            backgroundColor: [
                '#28a745',
                '#dc3545',
                '#ffc107',
                '#17a2b8'
            ],
            borderWidth: 1
        }]
    };

    // Configuración del gráfico de tipos
    const tiposConfig = {
        type: 'doughnut',
        data: tiposData,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = total > 0 ? Math.round((context.parsed / total) * 100) : 0;
                            return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    };

    // Crear gráfico de tipos
    const tiposChart = new Chart(
        document.getElementById('tipoAsistenciaChart'),
        tiposConfig
    );

    // Datos para el gráfico de tendencia (simulado - necesitarías datos históricos reales)
    const tendenciaData = {
        labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
        datasets: [{
            label: 'Porcentaje de Asistencia',
            data: [85, 87, 82, 89, 91, {{ $estadisticas['porcentaje_asistencia'] }}],
            borderColor: '#007bff',
            backgroundColor: 'rgba(0, 123, 255, 0.1)',
            tension: 0.4,
            fill: true
        }]
    };

    // Configuración del gráfico de tendencia
    const tendenciaConfig = {
        type: 'line',
        data: tendenciaData,
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: false,
                    min: 70,
                    max: 100,
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Asistencia: ' + context.parsed.y + '%';
                        }
                    }
                }
            }
        }
    };

    // Crear gráfico de tendencia
    const tendenciaChart = new Chart(
        document.getElementById('tendenciaChart'),
        tendenciaConfig
    );
}

function exportarEstadisticas() {
    Swal.fire({
        title: 'Exportar Estadísticas',
        text: '¿Deseas exportar un reporte con las estadísticas actuales?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, exportar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Implementar la lógica de exportación
            window.open('{{ route("asistencia.docente.reportes") }}?tipo=estadisticas&formato=pdf', '_blank');
        }
    });
}
</script>
@endpush
