@extends('cplantilla.bprincipal')

@section('titulo', 'Reporte General de Asistencia')

@section('contenidoplantilla')
    @php
        $module = 'asistencia';
        $section = 'reporte-general';
    @endphp
    @include('components.breadcrumb')
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

        /* Compact Statistics Styles */
        .stats-compact {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
            border: 1px solid #e1e5e9;
            border-radius: 8px;
            padding: 1rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            flex-wrap: wrap;
            gap: 1rem;
        }

        .stats-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            min-width: 80px;
        }

        .stats-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1a365d;
            line-height: 1;
            margin-bottom: 0.25rem;
        }

        .stats-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* Responsive stats */
        @media (max-width: 768px) {
            .stats-compact {
                padding: 0.75rem;
                gap: 0.75rem;
            }

            .stats-item {
                min-width: 60px;
            }

            .stats-number {
                font-size: 1.25rem;
            }

            .stats-label {
                font-size: 0.7rem;
            }
        }

        @media (max-width: 576px) {
            .stats-compact {
                flex-direction: column;
                align-items: stretch;
            }

            .stats-item {
                flex-direction: row;
                justify-content: space-between;
                padding: 0.5rem 0;
                border-bottom: 1px solid #f1f5f9;
            }

            .stats-item:last-child {
                border-bottom: none;
            }

            .stats-number {
                font-size: 1.1rem;
            }

            .stats-label {
                font-size: 0.75rem;
            }
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

                                <!-- Filtros Mejorados -->
                                <div class="filter-toolbar mb-4">
                                    <div class="card shadow-sm" style="border: 1px solid #e1e5e9; border-radius: 12px;">
                                        <div class="card-body p-4">
                                            <!-- Filtros Rápidos -->
                                            <div class="row mb-3">
                                                <div class="col-12">
                                                    <div class="d-flex justify-content-center gap-2 flex-wrap">
                                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="setFechaRapida('hoy')">
                                                            <i class="fas fa-calendar-day me-1"></i>Hoy
                                                        </button>
                                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="setFechaRapida('semana')">
                                                            <i class="fas fa-calendar-week me-1"></i>Esta Semana
                                                        </button>
                                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="setFechaRapida('mes')">
                                                            <i class="fas fa-calendar-alt me-1"></i>Este Mes
                                                        </button>
                                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="setFechaRapida('30dias')">
                                                            <i class="fas fa-calendar-check me-1"></i>Últimos 30 Días
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Filtros Principales -->
                                            <div class="row g-4 mb-4">
                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label class="form-label fw-bold text-primary mb-2" style="font-size: 0.85rem;">
                                                            <i class="fas fa-calendar-plus me-2"></i>FECHA INICIO
                                                        </label>
                                                        <input type="date" name="fecha_inicio" id="fecha_inicio"
                                                               class="form-control"
                                                               value="{{ request('fecha_inicio', \Carbon\Carbon::now()->subDays(30)->format('Y-m-d')) }}"
                                                               style="border-radius: 8px; border: 2px solid #e3f2fd;">
                                                    </div>
                                                </div>

                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label class="form-label fw-bold text-primary mb-2" style="font-size: 0.85rem;">
                                                            <i class="fas fa-calendar-minus me-2"></i>FECHA FIN
                                                        </label>
                                                        <input type="date" name="fecha_fin" id="fecha_fin" class="form-control"
                                                               value="{{ request('fecha_fin', \Carbon\Carbon::now()->format('Y-m-d')) }}"
                                                               style="border-radius: 8px; border: 2px solid #e3f2fd;">
                                                    </div>
                                                </div>

                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label class="form-label fw-bold text-primary mb-2" style="font-size: 0.85rem;">
                                                            <i class="fas fa-graduation-cap me-2"></i>CURSO (OPCIONAL)
                                                        </label>
                                                        <select name="curso_id" id="curso_id" class="form-control select-search"
                                                                style="border-radius: 8px; border: 2px solid #e3f2fd; width: 100% !important;">
                                                            <option value="">Todos los cursos</option>
                                                            @foreach ($cursos as $curso)
                                                                <option value="{{ $curso->curso_id }}"
                                                                    {{ request('curso_id') == $curso->curso_id ? 'selected' : '' }}>
                                                                    {{ $curso->grado->nombre }} - {{ $curso->seccion->nombre }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label class="form-label fw-bold text-primary mb-2" style="font-size: 0.85rem;">
                                                            <i class="fas fa-chart-bar me-2"></i>TIPO DE REPORTE
                                                        </label>
                                                        <select name="tipo_reporte" id="tipo_reporte" class="form-control"
                                                                style="border-radius: 8px; border: 2px solid #e3f2fd;">
                                                            <option value="general" {{ request('tipo_reporte', 'general') == 'general' ? 'selected' : '' }}>General</option>
                                                            <option value="detallado" {{ request('tipo_reporte') == 'detallado' ? 'selected' : '' }}>Detallado</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Filtros Avanzados -->
                                            <div class="row g-4 mb-4">
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label class="form-label fw-bold text-primary mb-2" style="font-size: 0.85rem;">
                                                            <i class="fas fa-chalkboard-teacher me-2"></i>PROFESOR
                                                            @if(auth()->user()->rol === 'Profesor')
                                                                <small class="text-muted">(Solo tú)</small>
                                                            @else
                                                                <small class="text-muted">(Opcional)</small>
                                                            @endif
                                                        </label>
                                                        @if(auth()->user()->rol === 'Profesor')
                                                            <input type="text" class="form-control"
                                                                   value="{{ auth()->user()->docente ? auth()->user()->docente->persona->apellidos . ', ' . auth()->user()->docente->persona->nombres : 'No asignado' }}"
                                                                   readonly
                                                                   style="border-radius: 8px; border: 2px solid #e3f2fd; background-color: #f8f9fa;">
                                                            <input type="hidden" name="profesor_id" id="profesor_id" value="{{ auth()->user()->docente ? auth()->user()->docente->profesor_id : '' }}">
                                                        @else
                                                            <select name="profesor_id" id="profesor_id" class="form-control select-search"
                                                                    style="border-radius: 8px; border: 2px solid #e3f2fd; width: 100% !important;">
                                                                <option value="">Todos los profesores</option>
                                                                @php
                                                                    $profesores = \App\Models\InfDocente::with('persona')
                                                                        ->join('personas', 'profesores.persona_id', '=', 'personas.id_persona')
                                                                        ->where('profesores.estado', 'Activo')
                                                                        ->whereExists(function ($query) {
                                                                            $query->select(\DB::raw(1))
                                                                                  ->from('cursoasignaturas')
                                                                                  ->whereColumn('cursoasignaturas.profesor_id', 'profesores.profesor_id')
                                                                                  ->whereExists(function ($subQuery) {
                                                                                      $subQuery->select(\DB::raw(1))
                                                                                               ->from('asistenciasasignatura')
                                                                                               ->whereColumn('asistenciasasignatura.curso_asignatura_id', 'cursoasignaturas.curso_asignatura_id');
                                                                                  });
                                                                        })
                                                                        ->orderBy('personas.apellidos')
                                                                        ->orderBy('personas.nombres')
                                                                        ->select('profesores.*')
                                                                        ->get();
                                                                @endphp
                                                                @foreach ($profesores as $profesor)
                                                                    <option value="{{ $profesor->profesor_id }}"
                                                                        {{ request('profesor_id') == $profesor->profesor_id ? 'selected' : '' }}>
                                                                        {{ $profesor->persona->apellidos }}, {{ $profesor->persona->nombres }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label class="form-label fw-bold text-primary mb-2" style="font-size: 0.85rem;">
                                                            <i class="fas fa-book me-2"></i>ASIGNATURA (OPCIONAL)
                                                        </label>
                                                        <select name="asignatura_id" id="asignatura_id" class="form-control select-search"
                                                                style="border-radius: 8px; border: 2px solid #e3f2fd; width: 100% !important;">
                                                            <option value="">Todas las asignaturas</option>
                                                            @php
                                                                $asignaturas = \App\Models\InfAsignatura::whereExists(function ($query) {
                                                                        $query->select(\DB::raw(1))
                                                                              ->from('cursoasignaturas')
                                                                              ->whereColumn('cursoasignaturas.asignatura_id', 'asignaturas.asignatura_id')
                                                                              ->whereExists(function ($subQuery) {
                                                                                  $subQuery->select(\DB::raw(1))
                                                                                           ->from('asistenciasasignatura')
                                                                                           ->whereColumn('asistenciasasignatura.curso_asignatura_id', 'cursoasignaturas.curso_asignatura_id');
                                                                              });
                                                                    })
                                                                    ->distinct()
                                                                    ->orderBy('nombre')
                                                                    ->get();
                                                            @endphp
                                                            @foreach ($asignaturas as $asignatura)
                                                                <option value="{{ $asignatura->asignatura_id }}"
                                                                    {{ request('asignatura_id') == $asignatura->asignatura_id ? 'selected' : '' }}>
                                                                    {{ $asignatura->nombre }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label class="form-label fw-bold text-primary mb-2" style="font-size: 0.85rem;">
                                                            <i class="fas fa-filter me-2"></i>TIPO DE ASISTENCIA
                                                        </label>
                                                        <select name="tipo_asistencia" id="tipo_asistencia" class="form-control"
                                                                style="border-radius: 8px; border: 2px solid #e3f2fd;">
                                                            <option value="" {{ request('tipo_asistencia', '') == '' ? 'selected' : '' }}>Todos los tipos</option>
                                                            <option value="A" {{ request('tipo_asistencia') == 'A' ? 'selected' : '' }}>Solo Presentes</option>
                                                            <option value="F" {{ request('tipo_asistencia') == 'F' ? 'selected' : '' }}>Solo Ausentes</option>
                                                            <option value="T" {{ request('tipo_asistencia') == 'T' ? 'selected' : '' }}>Solo Tardanzas</option>
                                                            <option value="J" {{ request('tipo_asistencia') == 'J' ? 'selected' : '' }}>Solo Justificadas</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Botones de Acción -->
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="d-flex justify-content-center gap-3 flex-wrap">
                                                        <button type="button" class="btn btn-primary btn-lg px-5" onclick="aplicarFiltrosReporte()">
                                                            <i class="fas fa-search me-2"></i>Generar Reporte
                                                        </button>
                                                        <button type="button" class="btn btn-outline-danger btn-lg px-4" onclick="limpiarFiltrosReporte()">
                                                            <i class="fas fa-times me-2"></i>Limpiar Filtros
                                                        </button>
                                                        <button type="button" class="btn btn-outline-success btn-lg px-4" onclick="exportarReporte()">
                                                            <i class="fas fa-file-pdf me-2"></i>Exportar PDF
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Estadísticas Compactas -->
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="stats-compact">
                                            <div class="stats-item">
                                                <span class="stats-number">{{ number_format($estadisticas['total_estudiantes']) }}</span>
                                                <span class="stats-label">Estudiantes</span>
                                            </div>
                                            <div class="stats-item">
                                                <span class="stats-number text-success">{{ number_format($estadisticas['total_presentes']) }}</span>
                                                <span class="stats-label">Presentes</span>
                                            </div>
                                            <div class="stats-item">
                                                <span class="stats-number text-danger">{{ number_format($estadisticas['total_ausentes']) }}</span>
                                                <span class="stats-label">Ausentes</span>
                                            </div>
                                            <div class="stats-item">
                                                <span class="stats-number text-warning">{{ number_format($estadisticas['total_tardanzas']) }}</span>
                                                <span class="stats-label">Tardanzas</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tabla de Cursos -->
                                <div class="card mb-4" style="border: none">
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

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(document).ready(function() {
            // Inicializar Select2 para los filtros
            if (typeof $ !== 'undefined' && $.fn.select2) {
                $('#curso_id').select2({
                    placeholder: 'Buscar curso...',
                    allowClear: true,
                    width: '100%',
                    theme: 'bootstrap4',
                    language: {
                        noResults: function() {
                            return "No se encontraron cursos";
                        },
                        searching: function() {
                            return "Buscando...";
                        }
                    }
                });

                $('#profesor_id').select2({
                    placeholder: 'Buscar profesor...',
                    allowClear: true,
                    width: '100%',
                    theme: 'bootstrap4',
                    language: {
                        noResults: function() {
                            return "No se encontraron profesores";
                        },
                        searching: function() {
                            return "Buscando...";
                        }
                    }
                });

                $('#asignatura_id').select2({
                    placeholder: 'Buscar asignatura...',
                    allowClear: true,
                    width: '100%',
                    theme: 'bootstrap4',
                    language: {
                        noResults: function() {
                            return "No se encontraron asignaturas";
                        },
                        searching: function() {
                            return "Buscando...";
                        }
                    }
                });
            } else {
                console.warn('Select2 no está disponible');
            }
        });

        // Función para configurar fechas rápidas
        function setFechaRapida(tipo) {
            const hoy = new Date();
            let fechaInicio, fechaFin;

            switch(tipo) {
                case 'hoy':
                    fechaInicio = fechaFin = hoy;
                    break;
                case 'semana':
                    const inicioSemana = new Date(hoy);
                    inicioSemana.setDate(hoy.getDate() - hoy.getDay() + 1); // Lunes de esta semana
                    fechaInicio = inicioSemana;
                    fechaFin = hoy;
                    break;
                case 'mes':
                    fechaInicio = new Date(hoy.getFullYear(), hoy.getMonth(), 1);
                    fechaFin = hoy;
                    break;
                case '30dias':
                    fechaInicio = new Date(hoy);
                    fechaInicio.setDate(hoy.getDate() - 30);
                    fechaFin = hoy;
                    break;
            }

            // Formatear fechas para input type="date"
            const fechaInicioStr = fechaInicio.toISOString().split('T')[0];
            const fechaFinStr = fechaFin.toISOString().split('T')[0];

            document.getElementById('fecha_inicio').value = fechaInicioStr;
            document.getElementById('fecha_fin').value = fechaFinStr;

            // Aplicar filtros automáticamente
            aplicarFiltrosReporte();
        }

        // Filter Application Function - AJAX update of statistics and table
        function aplicarFiltrosReporte() {
            // Save current form values before AJAX update
            const formValues = {
                fechaInicio: document.getElementById('fecha_inicio')?.value || '',
                fechaFin: document.getElementById('fecha_fin')?.value || '',
                cursoId: document.getElementById('curso_id')?.value || '',
                tipoReporte: document.getElementById('tipo_reporte')?.value || 'general',
                profesorId: document.getElementById('profesor_id')?.value || '',
                asignaturaId: document.getElementById('asignatura_id')?.value || '',
                tipoAsistencia: document.getElementById('tipo_asistencia')?.value || ''
            };

            // Show loading in statistics section
            const statsCompact = document.querySelector('.stats-compact');
            if (statsCompact) {
                statsCompact.innerHTML = `
                    <div class="text-center py-4">
                        <div class="loading-dots">
                            <div class="dot"></div>
                            <div class="dot"></div>
                            <div class="dot"></div>
                            <div class="dot"></div>
                        </div>
                        <p class="mt-2 text-muted">Generando estadísticas...</p>
                    </div>
                `;
            }

            // Show loading in charts section
            const chartsRow = document.querySelector('.row.mb-4');
            if (chartsRow) {
                chartsRow.innerHTML = `
                    <div class="col-12 text-center py-5">
                        <div class="loading-dots">
                            <div class="dot"></div>
                            <div class="dot"></div>
                            <div class="dot"></div>
                            <div class="dot"></div>
                        </div>
                        <p class="mt-2 text-muted">Generando gráficos...</p>
                    </div>
                `;
            }

            // Show loading in table section
            const tableCard = document.querySelector('.card.mb-4[style*="border: none"]');
            if (tableCard) {
                const tableBody = tableCard.querySelector('.card-body[style*="border: 2px solid #86D2E3"]');
                if (tableBody) {
                    tableBody.innerHTML = `
                        <div class="text-center py-5">
                            <div class="loading-dots">
                                <div class="dot"></div>
                                <div class="dot"></div>
                                <div class="dot"></div>
                                <div class="dot"></div>
                            </div>
                            <p class="mt-2 text-muted">Generando tabla de rendimiento...</p>
                        </div>
                    `;
                }
            }

            // Collect filter parameters
            const params = new URLSearchParams();

            if (formValues.fechaInicio) params.set('fecha_inicio', formValues.fechaInicio);
            if (formValues.fechaFin) params.set('fecha_fin', formValues.fechaFin);
            if (formValues.cursoId) params.set('curso_id', formValues.cursoId);
            if (formValues.tipoReporte) params.set('tipo_reporte', formValues.tipoReporte);
            if (formValues.profesorId) params.set('profesor_id', formValues.profesorId);
            if (formValues.asignaturaId) params.set('asignatura_id', formValues.asignaturaId);
            if (formValues.tipoAsistencia) params.set('tipo_asistencia', formValues.tipoAsistencia);

            // Make AJAX request to get updated data
            fetch('/asistencia/reporte/general?' + params.toString(), {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                }
            })
            .then(response => response.text())
            .then(html => {
                // Parse the HTML response
                const parser = new DOMParser();
                const newDoc = parser.parseFromString(html, 'text/html');

                // Update statistics section
                const newStats = newDoc.querySelector('.stats-compact');
                if (newStats && statsCompact) {
                    statsCompact.innerHTML = newStats.innerHTML;
                }

                // Update charts section
                const newCharts = newDoc.querySelector('.row.mb-4');
                if (newCharts && chartsRow) {
                    chartsRow.innerHTML = newCharts.innerHTML;
                }

                // Update table section
                const newTable = newDoc.querySelector('.card.mb-4[style*="border: none"] .card-body[style*="border: 2px solid #86D2E3"]');
                if (newTable && tableCard) {
                    const currentTableBody = tableCard.querySelector('.card-body[style*="border: 2px solid #86D2E3"]');
                    if (currentTableBody) {
                        currentTableBody.innerHTML = newTable.innerHTML;
                    }
                }

                // Restore form values after AJAX update
                setTimeout(() => {
                    document.getElementById('fecha_inicio').value = formValues.fechaInicio;
                    document.getElementById('fecha_fin').value = formValues.fechaFin;
                    document.getElementById('tipo_reporte').value = formValues.tipoReporte;
                    document.getElementById('tipo_asistencia').value = formValues.tipoAsistencia;

                    // Handle Select2 elements specially
                    if (formValues.cursoId) {
                        $('#curso_id').val(formValues.cursoId).trigger('change');
                    } else {
                        $('#curso_id').val('').trigger('change');
                    }

                    if (formValues.profesorId) {
                        $('#profesor_id').val(formValues.profesorId).trigger('change');
                    } else {
                        $('#profesor_id').val('').trigger('change');
                    }

                    if (formValues.asignaturaId) {
                        $('#asignatura_id').val(formValues.asignaturaId).trigger('change');
                    } else {
                        $('#asignatura_id').val('').trigger('change');
                    }

                    // Re-initialize charts and form elements after updating DOM
                    initializeCharts();
                    reinitializeFormElements();
                }, 100);

                // Update URL without reloading page
                const newUrl = '{{ route("asistencia.reporte-general") }}?' + params.toString();
                window.history.pushState({}, '', newUrl);
            })
            .catch(error => {
                console.error('Error:', error);
                // Show error messages in each section
                const errorHtml = `
                    <div class="text-center py-4">
                        <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                        <h5 class="text-danger">Error al cargar</h5>
                        <p class="text-muted">Por favor, intenta nuevamente</p>
                        <button onclick="aplicarFiltrosReporte()" class="btn btn-primary">Reintentar</button>
                    </div>
                `;

                if (statsCompact) statsCompact.innerHTML = errorHtml;
                if (chartsRow) chartsRow.innerHTML = `<div class="col-12">${errorHtml}</div>`;
                if (tableCard) {
                    const tableBody = tableCard.querySelector('.card-body');
                    if (tableBody) tableBody.innerHTML = errorHtml;
                }

                // Restore form values even on error
                document.getElementById('fecha_inicio').value = formValues.fechaInicio;
                document.getElementById('fecha_fin').value = formValues.fechaFin;
                document.getElementById('tipo_reporte').value = formValues.tipoReporte;
                if (formValues.cursoId) {
                    $('#curso_id').val(formValues.cursoId).trigger('change');
                } else {
                    $('#curso_id').val('').trigger('change');
                }
            });
        }

        // Function to reinitialize form elements after AJAX update
        function reinitializeFormElements() {
            // Re-initialize Select2 for the curso filter
            if (typeof $ !== 'undefined' && $.fn.select2) {
                // Destroy existing Select2 instance if it exists
                if ($('#curso_id').hasClass('select2-hidden-accessible')) {
                    $('#curso_id').select2('destroy');
                }

                // Re-initialize Select2
                $('#curso_id').select2({
                    placeholder: 'Buscar curso...',
                    allowClear: true,
                    width: '100%',
                    theme: 'bootstrap4',
                    language: {
                        noResults: function() {
                            return "No se encontraron cursos";
                        },
                        searching: function() {
                            return "Buscando...";
                        }
                    }
                });
            } else {
                console.warn('Select2 no está disponible para reinicialización');
            }
        }

        // Function to initialize charts after AJAX update
        function initializeCharts() {
            // Datos para gráficos
            const datosGrafico = @json($datosGrafico);

            // Gráfico de Distribución - mostrar siempre, incluso con datos vacíos
            const ctxDistribucion = document.getElementById('distribucionChart');
            if (ctxDistribucion) {
                const distribucionData = [
                    {{ $estadisticas['total_presentes'] }},
                    {{ $estadisticas['total_ausentes'] }},
                    {{ $estadisticas['total_tardanzas'] }}
                ];

                // Si todos los valores son 0, mostrar datos de ejemplo para que el gráfico se vea
                const hasData = distribucionData.some(value => value > 0);
                const chartData = hasData ? distribucionData : [1, 1, 1]; // Datos de ejemplo

                new Chart(ctxDistribucion.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Presentes', 'Ausentes', 'Tardanzas'],
                        datasets: [{
                            data: chartData,
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

                // Si no hay datos reales, mostrar mensaje
                if (!hasData) {
                    const canvas = ctxDistribucion;
                    const ctx = canvas.getContext('2d');
                    ctx.font = '16px Quicksand';
                    ctx.fillStyle = '#666';
                    ctx.textAlign = 'center';
                    ctx.fillText('No hay datos de asistencia', canvas.width / 2, canvas.height / 2);
                }
            }

            // Gráfico de Tendencia - mostrar siempre con el rango de fechas
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
                            fill: true,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: Math.max(100, Math.max(...datosGrafico.porcentajes) + 10),
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
        }

        function limpiarFiltrosReporte() {
            // Limpiar todos los campos del formulario
            document.getElementById('fecha_inicio').value = '';
            document.getElementById('fecha_fin').value = '';
            document.getElementById('curso_id').value = '';
            document.getElementById('tipo_reporte').value = 'general';
            document.getElementById('profesor_id').value = '';
            document.getElementById('asignatura_id').value = '';
            document.getElementById('tipo_asistencia').value = '';

            // Trigger change events for Select2 elements to update their display
            $('#curso_id').trigger('change');
            $('#profesor_id').trigger('change');
            $('#asignatura_id').trigger('change');

            // Aplicar filtros con parámetros vacíos (esto recargará el reporte vía AJAX)
            aplicarFiltrosReporte();
        }

        function exportarReporte() {
            alert('Función "Exportar PDF" próximamente disponible. Podrás descargar un reporte PDF con todas las estadísticas filtradas para archivar o compartir.');
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Datos para gráficos
            const datosGrafico = @json($datosGrafico);

            // Gráfico de Distribución - mostrar siempre, incluso con datos vacíos
            const ctxDistribucion = document.getElementById('distribucionChart');
            if (ctxDistribucion) {
                const distribucionData = [
                    {{ $estadisticas['total_presentes'] }},
                    {{ $estadisticas['total_ausentes'] }},
                    {{ $estadisticas['total_tardanzas'] }}
                ];

                // Si todos los valores son 0, mostrar datos de ejemplo para que el gráfico se vea
                const hasData = distribucionData.some(value => value > 0);
                const chartData = hasData ? distribucionData : [1, 1, 1]; // Datos de ejemplo

                new Chart(ctxDistribucion.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Presentes', 'Ausentes', 'Tardanzas'],
                        datasets: [{
                            data: chartData,
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

                // Si no hay datos reales, mostrar mensaje
                if (!hasData) {
                    const canvas = ctxDistribucion;
                    const ctx = canvas.getContext('2d');
                    ctx.font = '16px Quicksand';
                    ctx.fillStyle = '#666';
                    ctx.textAlign = 'center';
                    ctx.fillText('No hay datos de asistencia', canvas.width / 2, canvas.height / 2);
                }
            }

            // Gráfico de Tendencia - mostrar siempre con el rango de fechas
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
                            fill: true,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: Math.max(100, Math.max(...datosGrafico.porcentajes) + 10),
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

        /* Four Colored Dots Loading Animation */
        .loading-dots {
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            gap: 4px;
            width: 100%;
        }

        .loading-dots .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            animation: loadingDots 1.4s infinite ease-in-out both;
            background-color: #007bff;
        }

        .loading-dots .dot:nth-child(1) {
            background-color: #007bff;
            animation-delay: -0.32s;
        }

        .loading-dots .dot:nth-child(2) {
            background-color: #28a745;
            animation-delay: -0.16s;
        }

        .loading-dots .dot:nth-child(3) {
            background-color: #ffc107;
            animation-delay: 0s;
        }

        .loading-dots .dot:nth-child(4) {
            background-color: #dc3545;
            animation-delay: 0.16s;
        }

        @keyframes loadingDots {
            0%, 80%, 100% {
                transform: scale(0);
                opacity: 0.5;
            }
            40% {
                transform: scale(1);
                opacity: 1;
            }
        }
    </style>
@endsection
