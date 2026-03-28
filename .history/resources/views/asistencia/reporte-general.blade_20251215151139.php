@extends('cplantilla.bprincipal')

@section('titulo', 'Reporte General de Asistencia')

@section('contenidoplantilla')
    <style>
        .estilo-info {
            margin-bottom: 0px;
            font-family: "Quicksand", sans-serif;
            font-weight: 700;
            font-size: clamp(0.9rem, 2.0vw, 0.9rem) !important;
        }

        @media (max-width: 576px) {
            .margen-movil {
                margin-left: -29px !important;
                margin-right: -29px !important;
            }

            .margen-movil-2 {
                margin: 0 !important;
                padding: 0 !important;
            }
        }

        .stats-card-large {
            border-radius: 15px;
            transition: transform 0.3s ease;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
            margin-bottom: 2rem;
            height: 100%;
        }

        .stats-card-large:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .chart-container-large {
            position: relative;
            height: 400px;
            margin-bottom: 2rem;
        }

        .progress-custom {
            height: 20px;
            border-radius: 10px;
        }

        .table-responsive {
            max-height: 600px;
            overflow-y: auto;
        }
    </style>

    <div class="container-fluid estilo-info margen-movil-2">
        <div class="row mt-4 ml-1 mr-1">
            <div class="col-12 mb-3">
                <div class="box_block">
                    <button style="background: #0A8CB3 !important; border:none"
                        class="btn btn-primary btn-block text-left rounded-0 btn_header header_6 estilo-info" type="button"
                        data-toggle="collapse" data-target="#collapseReporteGeneral" aria-expanded="true"
                        aria-controls="collapseReporteGeneral">
                        <i class="fas fa-chart-pie"></i>&nbsp;Reporte General de Asistencia
                        <div class="float-right"><i class="fas fa-chevron-down"></i></div>
                    </button>
                </div>
                <div class="collapse show" id="collapseReporteGeneral">
                    <div class="card card-body rounded-0 border-0 pt-0"
                        style="padding-left:0.966666666rem;padding-right:0.9033333333333333rem;">
                        <div class="row margen-movil" style="padding:20px;">
                            <div class="col-12">

                                <!-- Filtros -->
                                <div class="card mb-4" style="border: none">
                                    <div
                                        style="background: #E0F7FA; color: #0A8CB3; font-weight: bold; border: 2px solid #86D2E3; border-bottom: 2px solid #86D2E3; padding: 6px 20px; border-radius:4px 4px 0px 0px;">
                                        <i class="fas fa-filter mr-2"></i>
                                        Filtros del Reporte
                                    </div>
                                    <div class="card-body"
                                        style="border: 2px solid #86D2E3; border-top: none; border-radius: 0px 0px 4px 4px !important; display: flow-root !important;">
                                        <form method="GET" action="{{ route('asistencia.reporte-general') }}"
                                            class="row g-2">

                                            <div class="col-md-2 " style="display: flow-root !important;">
                                                <label for="fecha_inicio" class="form-label estilo-info">Fecha
                                                    Inicio</label>
                                                <input type="date" name="fecha_inicio" id="fecha_inicio"
                                                    class="form-control" value="{{ $fechaInicio->format('Y-m-d') }}">
                                            </div>
                                            <div class="col-md-2 " style="display: flow-root !important;">
                                                <label for="fecha_fin" class="form-label estilo-info">Fecha Fin</label>
                                                <input type="date" name="fecha_fin" id="fecha_fin" class="form-control"
                                                    value="{{ $fechaFin->format('Y-m-d') }}">
                                            </div>

                                            <div class="col-md-4">
                                                <label for="curso_id" class="form-label estilo-info">Curso
                                                    (Opcional)</label>
                                                <select name="curso_id" id="curso_id" class="form-control">
                                                    <option value="">Todos los cursos</option>
                                                    @foreach ($cursos as $curso)
                                                        <option value="{{ $curso->curso_id }}"
                                                            {{ request('curso_id') == $curso->curso_id ? 'selected' : '' }}>
                                                            {{ $curso->grado->nombre }} - {{ $curso->seccion->nombre }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class=" col-md-2 mt-3">
                                                <button type="submit" class="btn btn-primary w-100">
                                                    <i class="fas fa-search"></i> Generar Reporte
                                                </button>
                                            </div>
                                            <div class=" col-md-2 mt-3">
                                                <a href="{{ route('asistencia.reporte-general') }}"
                                                    class="btn btn-secondary w-100">
                                                    <i class="fas fa-times"></i> Limpiar
                                                </a>
                                            </div>

                                        </form>
                                    </div>
                                </div>

                                <!-- Estadísticas Generales -->
                                <div class="row mb-4">
                                    <div class="col-md-3">
                                        <div class="card stats-card-large text-center"
                                            style="background: #0A8CB3; color: white;">
                                            <div class="card-body d-flex flex-column justify-content-center">
                                                <i class="fas fa-users fa-3x mb-3"></i>
                                                <h2 class="mb-1">{{ number_format($estadisticas['total_estudiantes']) }}
                                                </h2>
                                                <h6>Total Estudiantes</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card stats-card-large text-center"
                                            style="background: #28a745; color: white;">
                                            <div class="card-body d-flex flex-column justify-content-center">
                                                <i class="fas fa-check-circle fa-3x mb-3"></i>
                                                <h2 class="mb-1">{{ number_format($estadisticas['total_presentes']) }}
                                                </h2>
                                                <h6>Total Presentes</h6>
                                                <small>{{ $estadisticas['total_estudiantes'] > 0 ? round(($estadisticas['total_presentes'] / $estadisticas['total_estudiantes']) * 100, 1) : 0 }}%</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card stats-card-large text-center"
                                            style="background: #dc3545; color: white;">
                                            <div class="card-body d-flex flex-column justify-content-center">
                                                <i class="fas fa-times-circle fa-3x mb-3"></i>
                                                <h2 class="mb-1">{{ number_format($estadisticas['total_ausentes']) }}
                                                </h2>
                                                <h6>Total Ausentes</h6>
                                                <small>{{ $estadisticas['total_estudiantes'] > 0 ? round(($estadisticas['total_ausentes'] / $estadisticas['total_estudiantes']) * 100, 1) : 0 }}%</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card stats-card-large text-center"
                                            style="background: #ffc107; color: black;">
                                            <div class="card-body d-flex flex-column justify-content-center">
                                                <i class="fas fa-clock fa-3x mb-3"></i>
                                                <h2 class="mb-1">{{ number_format($estadisticas['total_tardanzas']) }}
                                                </h2>
                                                <h6>Total Tardanzas</h6>
                                                <small>{{ $estadisticas['total_estudiantes'] > 0 ? round(($estadisticas['total_tardanzas'] / $estadisticas['total_estudiantes']) * 100, 1) : 0 }}%</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Gráficos -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header"
                                                style="background: #f8f9fa; border-bottom: 2px solid #0A8CB3;">
                                                <h6 class="mb-0 estilo-info">
                                                    <i class="fas fa-chart-pie text-primary"></i>
                                                    Distribución General
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="chart-container-large">
                                                    <canvas id="distribucionChart"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header"
                                                style="background: #f8f9fa; border-bottom: 2px solid #0A8CB3;">
                                                <h6 class="mb-0 estilo-info">
                                                    <i class="fas fa-chart-line text-primary"></i>
                                                    Tendencia por Días
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="chart-container-large">
                                                    <canvas id="tendenciaChart"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tabla de Cursos -->
                                <div class="card" style="border: none">
                                    <div
                                        style="background: #E0F7FA; color: #0A8CB3; font-weight: bold; border: 2px solid #86D2E3; border-bottom: 2px solid #86D2E3; padding: 6px 20px; border-radius:4px 4px 0px 0px;">
                                        <i class="fas fa-graduation-cap mr-2"></i>
                                        Rendimiento por Curso
                                    </div>
                                    <div class="card-body"
                                        style="border: 2px solid #86D2E3; border-top: none; border-radius: 0px 0px 4px 4px !important;">

                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th>Curso</th>
                                                        <th>Estudiantes</th>
                                                        <th>% Asistencia</th>
                                                        <th>Presentes</th>
                                                        <th>Ausentes</th>
                                                        <th>Tardanzas</th>
                                                        <th>Progreso</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($estadisticasPorCurso as $curso)
                                                        <tr>
                                                            <td>
                                                                <strong>{{ $curso['grado'] }} -
                                                                    {{ $curso['seccion'] }}</strong>
                                                            </td>
                                                            <td>{{ $curso['total_estudiantes'] }}</td>
                                                            <td>
                                                                <span
                                                                    class="badge badge-{{ $curso['porcentaje'] >= 80 ? 'success' : ($curso['porcentaje'] >= 60 ? 'warning' : 'danger') }}">
                                                                    {{ round($curso['porcentaje'], 1) }}%
                                                                </span>
                                                            </td>
                                                            <td>{{ $curso['presentes'] }}</td>
                                                            <td>{{ $curso['ausentes'] }}</td>
                                                            <td>{{ $curso['tardanzas'] }}</td>
                                                            <td>
                                                                <div class="progress progress-custom">
                                                                    <div class="progress-bar bg-{{ $curso['porcentaje'] >= 80 ? 'success' : ($curso['porcentaje'] >= 60 ? 'warning' : 'danger') }}"
                                                                        style="width: {{ $curso['porcentaje'] }}%">
                                                                        {{ round($curso['porcentaje'], 1) }}%
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
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

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Datos para gráficos
            const datosGrafico = @json($datosGrafico);

            // Gráfico de Distribución
            const ctxDistribucion = document.getElementById('distribucionChart');
            if (ctxDistribucion) {
                new Chart(ctxDistribucion.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Presentes', 'Ausentes', 'Tardanzas'],
                        datasets: [{
                            data: [
                                {{ $estadisticas['total_presentes'] }},
                                {{ $estadisticas['total_ausentes'] }},
                                {{ $estadisticas['total_tardanzas'] }}
                            ],
                            backgroundColor: ['#28a745', '#dc3545', '#ffc107'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            }

            // Gráfico de Tendencia
            const ctxTendencia = document.getElementById('tendenciaChart');
            if (ctxTendencia && datosGrafico.labels && datosGrafico.labels.length > 0) {
                new Chart(ctxTendencia.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: datosGrafico.labels,
                        datasets: [{
                            label: '% Asistencia',
                            data: datosGrafico.porcentajes,
                            borderColor: '#0A8CB3',
                            backgroundColor: 'rgba(10, 140, 179, 0.1)',
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
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
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            }
        });
    </script>

    <style>
        .form-control {
            border: 1px solid #DAA520;
            display: flow-root !important;
        }
    </style>
@endsection
