@extends('cplantilla.bprincipal')
@section('titulo', 'Calificaciones del Estudiante')
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
    </style>
    <div class="container-fluid margen-movil-2" id="imprimir">
        <div class="row mt-4 mr-1 ml-1">
            <div class="col-md-12">
                <div class="card">
                    <!-- Encabezado de la tarjeta con información básica y botón para buscar otro estudiante -->
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center" style="background-color: #1e5981 !important;">
                        <h4 class="mb-0">
                            <i class="fas fa-user-graduate"></i> Calificaciones del Estudiante
                        </h4>
                        @if (auth()->user()->rol == 'Administrador')
                            <a href="{{ route('notas.consulta') }}" class="btn btn-light btn-sm d-print-none">
                                <i class="fas fa-search mr-1"></i> Nueva búsqueda
                            </a>
                        @endif
                    </div>
                    <div class="card-body">
                        <!-- Sección de información del estudiante -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <!-- Avatar con iniciales del estudiante -->
                                    <div class="avatar-lg mr-3"
                                        style="width: 80px; height: 80px; border-radius: 50%; background-color: #0A8CB3; color: white; display: flex; align-items: center; justify-content: center; font-size: 36px;">
                                        <!-- Extrae la primera letra del nombre y apellido para formar las iniciales -->
                                        {{ substr($estudiante->nombres, 0, 1) }}{{ substr($estudiante->apellidos, 0, 1) }}
                                    </div>
                                    <!-- Información personal del estudiante -->
                                    <div class="ml-3">
                                        <h4 class="text-primary mb-1" style="color:#0a7c9e !important; font-weight:bold">{{ $estudiante->apellidos }},
                                            {{ $estudiante->nombres }}</h4>
                                        <p class="mb-0"><strong>N.° de DNI:</strong> {{ $estudiante->dni }}</p>
                                        <p class="mb-0"><strong>N.° de Matrícula:</strong>
                                            {{ $matriculaActual->numero_matricula }}</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Información académica del estudiante: grado, sección y año lectivo -->
                            <div class="col-md-6 text-md-end ">
                                <div class="mb-1"><strong>Grado y Sección:</strong> {{ $curso->grado->nombre }}
                                    {{ $curso->grado->nivel->nombre }} "{{ $curso->seccion->nombre }}"</div>
                                <div class="mb-1"><strong>Año Lectivo:</strong> {{ $curso->anoLectivo->nombre }}</div>
                                <div><strong>Estado:</strong>
                                    <span
                                        class="badge {{ $matriculaActual->estado == 'Matriculado' ? 'bg-success' : 'bg-warning' }}" style="border: none; color:#fff; font-weight:bold">
                                        {{ $matriculaActual->estado }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Mensaje informativo sobre las calificaciones -->
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Se muestran las calificaciones del año lectivo actual. Los
                            bimestres sin calificación aparecen como "-". Pase el mouse sobre las notas para ver
                            observaciones.
                        </div>

                        <!-- Tabla de calificaciones -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th style="width: 30%">Asignatura</th>
                                        <!-- Genera dinámicamente una columna para cada período académico (bimestres) -->
                                        @foreach ($periodos as $periodo)
                                            <th class="text-center">
                                                {{ $periodo->nombre }}
                                            </th>
                                        @endforeach
                                        <th class="text-center bg-light">Promedio</th>
                                        <th class="text-center bg-light">Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Itera sobre cada asignatura con sus notas -->
                                    @forelse($asignaturasNotas as $item)
                                        <tr>
                                            <!-- Nombre y código de la asignatura -->
                                            <td>
                                                <strong>{{ $item['asignatura']->nombre }}</strong>
                                                <div class="small text-muted">{{ $item['asignatura']->codigo }}</div>
                                            </td>

                                            <!-- Muestra las notas de cada período para esta asignatura -->
                                            @foreach ($item['notas_periodos'] as $notaPeriodo)
                                                <td class="text-center">
                                                    <!-- Si existe nota, la muestra con color según aprobado o reprobado -->
                                                    @if ($notaPeriodo['nota'] !== null)
                                                        @php
                                                            // Buscar la observación específica para esta nota de período
                                                            $observacionPeriodo = \App\Models\NotasFinalesPeriodo::where(
                                                                'matricula_id',
                                                                $matriculaActual->matricula_id,
                                                            )
                                                                ->whereHas('cursoAsignatura', function ($query) use (
                                                                    $item,
                                                                ) {
                                                                    $query->where(
                                                                        'asignatura_id',
                                                                        $item['asignatura']->asignatura_id,
                                                                    );
                                                                })
                                                                ->where('periodo_id', $notaPeriodo['periodo_id'])
                                                                ->first();
                                                            $observacionTexto =
                                                                $observacionPeriodo &&
                                                                $observacionPeriodo->observaciones
                                                                    ? $observacionPeriodo->observaciones
                                                                    : 'Sin observaciones para este período';
                                                        @endphp
                                                        <span
                                                            class="nota-valor {{ $notaPeriodo['nota'] >= 11 ? 'text-success' : 'text-danger' }} fw-bold nota-tooltip"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            data-bs-html="true"
                                                            title="{{ htmlspecialchars($observacionTexto) }}">
                                                            {{ $notaPeriodo['nota'] }}
                                                        </span>
                                                    @else
                                                        <!-- Si no hay nota, muestra un guión -->
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            @endforeach

                                            <!-- Muestra el promedio o "Pendiente" si no hay promedio calculado -->
                                            <td
                                                class="text-center fw-bold {{ $item['promedio'] >= 11 ? 'bg-success bg-opacity-10' : ($item['promedio'] ? 'bg-danger bg-opacity-10' : '') }}">
                                                @if ($item['promedio'] !== null)
                                                    @php
                                                        // Buscar observaciones de la nota final anual para esta asignatura
                                                        $notaAnual = isset(
                                                            $notasFinalesAnuales[$item['asignatura']->asignatura_id],
                                                        )
                                                            ? $notasFinalesAnuales[$item['asignatura']->asignatura_id]
                                                            : null;
                                                        $observacionAnual =
                                                            $notaAnual && $notaAnual->observaciones
                                                                ? $notaAnual->observaciones
                                                                : 'Promedio calculado automáticamente';
                                                    @endphp
                                                    <span class="promedio-tooltip" data-bs-toggle="tooltip"
                                                        data-bs-placement="top" data-bs-html="true"
                                                        title="{{ htmlspecialchars($observacionAnual) }}">
                                                        {{ $item['promedio'] }}
                                                    </span>
                                                @else
                                                    Pendiente
                                                @endif
                                            </td>

                                            <!-- Lógica para mostrar el estado de aprobación -->
                                            <td class="text-center">
                                                @php
                                                    // Cuenta cuántos períodos tienen calificación para esta asignatura
                                                    $periodosCalificados = collect($item['notas_periodos'])
                                                        ->filter(function ($periodo) {
                                                            return $periodo['nota'] !== null;
                                                        })
                                                        ->count();

                                                    // Determina si se han completado todos los períodos (4 bimestres)
                                                    // Solo se muestra "Aprobado" o "Reprobado" cuando todos los bimestres tienen nota
                                                    $todosLosPeriodosCalificados =
                                                        $periodosCalificados === count($periodos) &&
                                                        count($periodos) === 4;
                                                @endphp

                                                <!-- Muestra estado según si todos los períodos están calificados -->
                                                @if ($todosLosPeriodosCalificados && $item['promedio'] !== null)
                                                    <!-- Si existe un registro oficial en notasFinalesAnuales, usa ese estado -->
                                                    @if (isset($notasFinalesAnuales[$item['asignatura']->asignatura_id]))
                                                        @php $estado = $notasFinalesAnuales[$item['asignatura']->asignatura_id]->estado; @endphp
                                                        <span
                                                            class="badge {{ $estado == 'Aprobado' ? 'bg-success' : ($estado == 'Reprobado' ? 'bg-danger' : 'bg-warning') }}" style="font-weight: bold; color:#fff; border:none">
                                                            {{ $estado }}
                                                        </span>
                                                    @else
                                                        <!-- Si no hay registro oficial, calcula el estado basado en el promedio -->
                                                        <span
                                                            class="badge {{ $item['promedio'] >= 11 ? 'bg-success' : 'bg-danger' }}"  style="font-weight: bold; color:#fff; border:none">
                                                            {{ $item['promedio'] >= 11 ? 'Aprobado' : 'Reprobado' }}
                                                        </span>
                                                    @endif
                                                @else
                                                    <!-- Si faltan calificaciones, muestra "Pendiente" -->
                                                    <span class="badge bg-secondary"  style="font-weight: bold; color:#fff; border:none">Pendiente</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <!-- Mensaje cuando no hay asignaturas -->
                                        <tr>
                                            <td colspan="{{ count($periodos) + 3 }}" class="text-center">No hay asignaturas
                                                registradas para este estudiante</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Sección de resumen y estadísticas -->
                        <div class="mt-4">
                            <div class="card">
                                <div class="card-header" style="background-color: #e5c44d6f">
                                    <h5 class="mb-0" style="font-weight: bold">Resumen de progreso del Estudiante</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <!-- Gráfico visual de notas -->
                                        <div class="col-md-6 d-print-none">
                                            <div class="canvas-container" style="position: relative; height: 300px;">
                                                <canvas id="graficoNotas"></canvas>
                                            </div>
                                        </div>
                                        <!-- Estadísticas numéricas -->
                                        <div class="col-md-6">
                                            <h6 class="mb-3">Estadísticas</h6>
                                            <div class="table-responsive">
                                                <table class="table table-sm table-bordered">
                                                    <tr>
                                                        <td class="bg-light">Asignaturas Totales:</td>
                                                        <td>{{ count($asignaturasNotas) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <!-- Cuenta asignaturas aprobadas: promedio existe y es >= 11 -->
                                                        <td class="bg-light">Asignaturas Aprobadas:</td>
                                                        <td>{{ collect($asignaturasNotas)->filter(function ($item) {return $item['promedio'] !== null && $item['promedio'] >= 11;})->count() }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <!-- Cuenta asignaturas reprobadas: promedio existe y es < 11 -->
                                                        <td class="bg-light">Asignaturas Reprobadas:</td>
                                                        <td>{{ collect($asignaturasNotas)->filter(function ($item) {return $item['promedio'] !== null && $item['promedio'] < 11;})->count() }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <!-- Calcula el promedio general de todas las asignaturas -->
                                                        <td class="bg-light">Promedio General:</td>
                                                        <td>{{ number_format(collect($asignaturasNotas)->avg('promedio') ?? 0, 2) }}
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Botones de acción al pie de la tarjeta -->
                    <div class="card-footer bg-light d-print-none">
                        <div class="row">
                            <div class="col-md-6 w-100 mb-2 mt-1">
                                @if (auth()->user()->rol == 'Administrador')
                                    <a href="{{ route('notas.consulta') }}" class="btn btn-secondary w-100" style="font-weight: bold">
                                        <i class="fas fa-arrow-left mx-2"></i> Volver a la búsqueda
                                    </a>
                                @elseif (auth()->user()->rol == 'Representante')
                                    <a href="{{ route('notas.misEstudiantes') }}" class="btn btn-secondary w-100 " style="font-weight: bold">
                                        <i class="fas fa-arrow-left mx-2"></i> Volver a mis estudiantes
                                    </a>
                                @else
                                    <a href="{{ route('rutarrr1') }}" class="btn btn-secondary w-100" style="font-weight: bold">
                                        <i class="fas fa-arrow-left mx-2"></i> Volver al inicio
                                    </a>
                                @endif
                            </div>
                            <div class="col-md-6 text-md-end w-100 mt-1">
                                <button class="btn btn-primary w-100" onclick="imprimirCalificaciones()" style="font-weight: bold">
                                    <i class="fas fa-print mx-2"></i> Imprimir Calificaciones
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <iframe id="iframe" width="0" height="0"></iframe>
    <style>
        .nota-valor {
            font-size: 16px;
        }

        /* Estilos para tooltips personalizados */
        .nota-tooltip,
        .promedio-tooltip {
            cursor: help;
            position: relative;
        }

        /* Estilos para tooltips de Bootstrap */
        .tooltip {
            font-size: 13px;
        }

        .tooltip-inner {
            max-width: 300px;
            text-align: left;
            background-color: #333;
            color: #fff;
            padding: 8px 12px;
            border-radius: 4px;
        }

        /* Ocultar tooltips en impresión */
        @media print {
            .tooltip {
                display: none !important;
            }
        }
    </style>

@endsection

@section('scripts')
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Script para mensajes globales con SweetAlert2 -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mensajes de éxito
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: '{{ session('success') }}',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            @endif

            // Mensajes de error
            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: '¡Error!',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Entendido'
                });
            @endif

            // Mensajes de información
            @if (session('info'))
                Swal.fire({
                    icon: 'info',
                    title: 'Información',
                    text: '{{ session('info') }}',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Aceptar'
                });
            @endif
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar tooltips de Bootstrap
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl, {
                    trigger: 'hover focus',
                    delay: {
                        show: 300,
                        hide: 100
                    }
                });
            });

            // Prepara los datos para el gráfico
            const asignaturas = @json(array_map(fn($item) => $item['asignatura']->nombre, $asignaturasNotas));
            const promedios = @json(array_map(fn($item) => $item['promedio'] ?? 0, $asignaturasNotas));

            // Comprobar si los datos y el elemento canvas están disponibles
            console.log('Datos para gráfico:', {
                asignaturas,
                promedios
            });
            const canvas = document.getElementById('graficoNotas');
            if (!canvas) {
                console.error('Elemento canvas no encontrado');
                return;
            }

            // Crea el gráfico de barras
            new Chart(canvas.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: asignaturas,
                    datasets: [{
                        label: 'Promedio',
                        data: promedios,
                        backgroundColor: promedios.map(nota => nota >= 11 ?
                            'rgba(40, 167, 69, 0.6)' : 'rgba(220, 53, 69, 0.6)'),
                        borderColor: promedios.map(nota => nota >= 11 ? 'rgba(40, 167, 69, 1)' :
                            'rgba(220, 53, 69, 1)'),
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 20,
                            ticks: {
                                stepSize: 2
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: context =>
                                    `Nota: ${context.parsed.y} - ${context.parsed.y >= 11 ? 'Aprobado' : 'Reprobado'}`
                            }
                        }
                    }
                }
            });
        });



        // Función para imprimir
        function imprimirCalificaciones() {
            const imprimir = document.getElementById('imprimir').innerHTML;
            // Incluye los estilos principales del sistema y los de vernotas
            const printHtml = `
			<html>
				<head>
					<meta charset="utf-8">
					<title>Calificaciones del Estudiante</title>
					<link rel="stylesheet" href="{{ asset('adminlte/assets/css/bootstrap.min.css') }}">
					<link rel="stylesheet" href="{{ asset('adminlte/assets/css/atlantis.min.css') }}">
					<link rel="stylesheet" href="{{ asset('adminlte/assets/css/demo.css') }}">
					<link href="{{ asset('Content/themes/nuna_int/css/bootstrap.min.css') }}" rel="stylesheet">
					<link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,700" rel="stylesheet">
					<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400" rel="stylesheet">
					<link rel="stylesheet" href="{{ asset('Content/themes/nuna_int/fontawesome/css/all.css') }}">
					<link href="{{ asset('Content/themes/nuna_int/css/style_asistencia.css?f=202003171852') }}" rel="stylesheet">
					<link href="{{ asset('Content/themes/nuna_int/plugins/alertifyjs/css/themes/bootstrap.css?f=202003171852') }}" rel="stylesheet">
					<link href="{{ asset('Content/themes/nuna_int/plugins/alertifyjs/css/alertify.css?f=202003171852') }}" rel="stylesheet">
					<style>
						body { background: white !important; }
						.nota-valor { font-size: 16px; }
					</style>
				</head>
				<body>${imprimir}
				</body>
			</html>`;
            const iframe = document.getElementById('iframe');
            iframe.srcdoc = printHtml;
            iframe.onload = function() {
                iframe.contentWindow.focus();
                iframe.contentWindow.print();
            };
        }
    </script>
@endsection
