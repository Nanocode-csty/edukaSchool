@extends('cplantilla.bprincipal')

@section('titulo', 'Control de Asistencia')

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

        .table-responsive {
            max-height: calc(100vh - 400px);
            overflow-y: auto;
        }

        .badge-estado {
            font-size: 0.8rem;
            padding: 0.3rem 0.6rem;
        }

        .stats-card {
            transition: all 0.3s ease;
            border-radius: 8px;
        }

        .stats-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .filter-section {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .form-control-sm {
            font-size: 0.875rem;
        }

        /* Estilos mejorados para Select2 */
        .select2-container--bootstrap4 .select2-selection--single {
            height: calc(2.25rem + 2px) !important;
            padding: 0.375rem 0.75rem !important;
            font-size: 0.875rem !important;
            font-weight: 400 !important;
            line-height: 1.5 !important;
            color: #495057 !important;
            background-color: #fff !important;
            background-clip: padding-box !important;
            border: 2px solid #DAA520 !important;
            border-radius: 0.375rem !important;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out !important;
        }

        .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
            color: #495057;
            line-height: 1.5;
            padding-left: 0;
            padding-right: 20px;
        }

        .select2-container--bootstrap4 .select2-selection--single .select2-selection__placeholder {
            color: #6c757d;
            font-style: italic;
        }

        .select2-container--bootstrap4 .select2-selection--single .select2-selection__arrow {
            height: calc(2.25rem - 2px) !important;
            right: 8px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
        }

        .select2-container--bootstrap4.select2-container--focus .select2-selection--single {
            border-color: #0A8CB3 !important;
            box-shadow: 0 0 0 0.2rem rgba(10, 140, 179, 0.25) !important;
            background-color: #fff !important;
        }

        .select2-container--bootstrap4.select2-container--open .select2-selection--single {
            border-color: #0A8CB3 !important;
            box-shadow: 0 0 0 0.2rem rgba(10, 140, 179, 0.25) !important;
        }

        .select2-dropdown {
            border: 2px solid #DAA520 !important;
            border-radius: 0.375rem !important;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
            margin-top: -2px !important;
        }

        .select2-container--bootstrap4 .select2-results__option {
            padding: 8px 12px !important;
            font-size: 0.875rem !important;
            color: #495057 !important;
        }

        .select2-container--bootstrap4 .select2-results__option--highlighted[aria-selected] {
            background-color: #0A8CB3 !important;
            color: white !important;
        }

        .select2-container--bootstrap4 .select2-results__option[aria-selected=true] {
            background-color: #E0F7FA !important;
            color: #0A8CB3 !important;
            font-weight: 500 !important;
        }

        .select2-container--bootstrap4 .select2-results__option--highlighted[aria-selected=true] {
            background-color: #0A8CB3 !important;
            color: white !important;
        }

        /* Estilos para el contenedor de búsqueda */
        .select2-search--dropdown .select2-search__field {
            border: 1px solid #DAA520 !important;
            border-radius: 0.25rem !important;
            padding: 4px 8px !important;
            font-size: 0.875rem !important;
        }

        .select2-search--dropdown .select2-search__field:focus {
            border-color: #0A8CB3 !important;
            box-shadow: 0 0 0 0.2rem rgba(10, 140, 179, 0.25) !important;
        }

        /* Mejorar el espaciado de las filas de filtros */
        .filter-section .row > div {
            margin-bottom: 1rem;
        }

        .filter-section .row > div:last-child {
            margin-bottom: 0;
        }

        /* Estilos para los botones de acción */
        .btn-group .btn {
            margin-right: 0.25rem;
        }

        .btn-group .btn:last-child {
            margin-right: 0;
        }

        /* Session Cards */
        .session-card {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            overflow: hidden;
            margin-bottom: 1rem;
        }

        .session-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .session-header {
            background: linear-gradient(135deg, #0A8CB3, #087299);
            color: white;
            padding: 1rem 1.5rem;
            border: none;
        }

        .session-title {
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .session-meta {
            font-size: 0.875rem;
            opacity: 0.9;
        }

        .session-status {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .session-status.activo {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .session-status.pendiente {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .session-status.finalizado {
            background: rgba(255, 193, 7, 0.2);
            color: #ffc107;
        }

        .session-body {
            padding: 1.5rem;
        }

        .session-stats {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 1rem;
        }

        .stat-item {
            text-align: center;
            flex: 1;
        }

        .stat-number {
            font-size: 1.25rem;
            font-weight: 700;
            color: #495057;
        }

        .stat-label {
            font-size: 0.75rem;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 500;
        }

        .progress-bar {
            height: 6px;
            background: #e9ecef;
            border-radius: 3px;
            overflow: hidden;
            margin-bottom: 1rem;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(135deg, #28a745, #20c997);
            transition: width 0.3s ease;
        }

        .session-actions {
            display: flex;
            gap: 0.5rem;
        }

        /* Breadcrumb Styles */
        .breadcrumb-container {
            background: white;
            border-radius: 8px;
            padding: 1rem 1.5rem;
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
            border: 1px solid #e5e7eb;
            margin-bottom: 2rem;
        }

        .breadcrumb {
            background: transparent;
            margin-bottom: 0;
            padding: 0;
        }

        .breadcrumb-item {
            display: flex;
            align-items: center;
            font-size: 0.875rem;
        }

        .breadcrumb-item + .breadcrumb-item::before {
            content: "/";
            color: #9ca3af;
            font-weight: 400;
            margin: 0 0.5rem;
        }

        .breadcrumb-item a {
            color: #2563eb;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .breadcrumb-item a:hover {
            color: #1d4ed8;
            text-decoration: underline;
        }

        .breadcrumb-item.active {
            color: #111827;
            font-weight: 600;
        }
    </style>

    <div class="container-fluid estilo-info margen-movil-2">
        <div class="row mt-4 ml-1 mr-1">
            <div class="col-12 mb-3">
                <div class="box_block">
                    <button style="background: #0A8CB3 !important; border:none"
                        class="btn btn-primary btn-block text-left rounded-0 btn_header header_6 estilo-info" type="button"
                        data-toggle="collapse" data-target="#collapseAsistencia" aria-expanded="true"
                        aria-controls="collapseAsistencia">
                        <i class="fas fa-clipboard-check"></i>&nbsp;Control de Asistencia
                        <div class="float-right"><i class="fas fa-chevron-down"></i></div>
                    </button>
                </div>
                <div class="collapse show" id="collapseAsistencia">
                    <div class="card card-body rounded-0 border-0 pt-0"
                        style="padding-left:0.966666666rem;padding-right:0.9033333333333333rem;">
                        <div class="row margen-movil" style="padding:20px;">
                            <div class="col-12">

                                <!-- Breadcrumb -->
                                <div class="breadcrumb-container">
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item">
                                                <a href="{{ route('rutarrr1') }}">
                                                    <i class="fas fa-home"></i>
                                                    <span>Inicio</span>
                                                </a>
                                            </li>
                                            <li class="breadcrumb-item active" aria-current="page">
                                                <i class="fas fa-clipboard-check"></i>
                                                <span>Asistencias</span>
                                            </li>
                                        </ol>
                                    </nav>
                                </div>

                                <!-- Estadísticas Rápidas -->
                                <div class="row mb-4">
                                    <div class="col-md-3">
                                        <div class="card stats-card text-center border-primary">
                                            <div class="card-body">
                                                <h5 class="card-title text-primary">
                                                    <i class="fas fa-list fa-2x"></i>
                                                </h5>
                                                <h3 class="text-primary">{{ number_format($estadisticas['total_registros']) }}</h3>
                                                <p class="mb-0">Total Registros</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card stats-card text-center border-success">
                                            <div class="card-body">
                                                <h5 class="card-title text-success">
                                                    <i class="fas fa-check fa-2x"></i>
                                                </h5>
                                                <h3 class="text-success">{{ number_format($estadisticas['presentes']) }}</h3>
                                                <p class="mb-0">Presentes</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card stats-card text-center border-danger">
                                            <div class="card-body">
                                                <h5 class="card-title text-danger">
                                                    <i class="fas fa-times fa-2x"></i>
                                                </h5>
                                                <h3 class="text-danger">{{ number_format($estadisticas['ausentes']) }}</h3>
                                                <p class="mb-0">Ausentes</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card stats-card text-center border-warning">
                                            <div class="card-body">
                                                <h5 class="card-title text-warning">
                                                    <i class="fas fa-clock fa-2x"></i>
                                                </h5>
                                                <h3 class="text-warning">{{ number_format($estadisticas['tardanzas']) }}</h3>
                                                <p class="mb-0">Tardanzas</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Controles de Fecha -->
                                <div class="filter-section">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="fecha-selector" class="form-label estilo-info">
                                                        <i class="fas fa-calendar-alt mr-1"></i>Seleccionar Fecha
                                                    </label>
                                                    <input type="date" id="fecha-selector" class="form-control form-control-sm"
                                                           value="{{ $fecha }}" onchange="cambiarFecha(this.value)">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label estilo-info">
                                                        <i class="fas fa-calendar-day mr-1"></i>Fecha Actual
                                                    </label>
                                                    <div class="form-control form-control-sm bg-light">
                                                        <strong>{{ Carbon\Carbon::parse($fecha)->isoFormat('dddd, D [de] MMMM, YYYY') }}</strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label estilo-info">
                                                <i class="fas fa-bolt mr-1"></i>Acciones Rápidas
                                            </label>
                                            <div class="d-flex gap-2">
                                                <button class="btn btn-success btn-sm" onclick="exportarExcel()" title="Exportar Reporte">
                                                    <i class="fas fa-file-excel"></i> Excel
                                                </button>
                                                <a href="{{ route('asistencia.reporte-general') }}" class="btn btn-info btn-sm" title="Ver Reportes">
                                                    <i class="fas fa-chart-pie"></i> Reportes
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Sesiones Programadas -->
                                <div class="card" style="border: none">
                                    <div
                                        style="background: #E0F7FA; color: #0A8CB3; font-weight: bold; border: 2px solid #86D2E3; border-bottom: 2px solid #86D2E3; padding: 6px 20px; border-radius:4px 4px 0px 0px;">
                                        <i class="fas fa-chalkboard-teacher mr-2"></i>
                                        Sesiones Programadas ({{ $sesiones->count() }})
                                    </div>
                                    <div class="card-body"
                                        style="border: 2px solid #86D2E3; border-top: none; border-radius: 0px 0px 4px 4px !important; padding: 0;">

                                        @if ($sesiones->isEmpty())
                                            @php
                                                $esFeriado = \App\Models\Feriado::esFeriado($fecha);
                                                $feriadoInfo = $esFeriado ? \App\Models\Feriado::porFecha($fecha)->first() : null;
                                            @endphp

                                            @if($esFeriado)
                                                <div class="text-center py-5">
                                                    <i class="fas fa-calendar-day fa-3x text-danger mb-3"></i>
                                                    <h5 class="text-danger">Día Feriado</h5>
                                                    <p class="mb-2"><strong>{{ $feriadoInfo->nombre }}</strong></p>
                                                    @if($feriadoInfo->descripcion)
                                                        <p class="text-muted mb-4">{{ $feriadoInfo->descripcion }}</p>
                                                    @endif
                                                    <div class="alert alert-info">
                                                        <i class="fas fa-info-circle mr-2"></i>
                                                        No hay sesiones programadas porque es feriado. Si hay clases de recuperación programadas, aparecerán en fechas posteriores.
                                                    </div>
                                                </div>
                                            @else
                                                <div class="text-center py-5">
                                                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                                    <h5 class="text-muted">No tienes sesiones programadas</h5>
                                                    <p class="text-muted mb-4">Revisa el horario académico o selecciona otra fecha para ver las clases disponibles.</p>
                                                    <button class="btn btn-primary" onclick="document.getElementById('fecha-selector').focus()">
                                                        <i class="fas fa-calendar-day mr-2"></i>
                                                        Cambiar Fecha
                                                    </button>
                                                </div>
                                            @endif
                                        @else
                                            <div class="p-4">
                                                <div class="row">
                                                    @foreach ($sesiones as $index => $sesion)
                                                        <div class="col-md-6 mb-4">
                                                            <div class="session-card">
                                                                <div class="session-header">
                                                                    <div class="session-title">{{ $sesion->cursoAsignatura->asignatura->nombre }}</div>
                                                                    <div class="session-meta">
                                                                        <i class="fas fa-graduation-cap mr-1"></i>
                                                                        {{ $sesion->cursoAsignatura->curso->grado->nombre }} -
                                                                        {{ $sesion->cursoAsignatura->curso->seccion->nombre }}
                                                                    </div>
                                                                    @php
                                                                        $ahora = now();
                                                                        if (str_contains($sesion->hora_inicio, ' ')) {
                                                                            $inicio = Carbon\Carbon::parse($sesion->hora_inicio);
                                                                        } else {
                                                                            $inicio = Carbon\Carbon::parse($fecha . ' ' . $sesion->hora_inicio);
                                                                        }

                                                                        if (str_contains($sesion->hora_fin, ' ')) {
                                                                            $fin = Carbon\Carbon::parse($sesion->hora_fin);
                                                                        } else {
                                                                            $fin = Carbon\Carbon::parse($fecha . ' ' . $sesion->hora_fin);
                                                                        }

                                                                        $estadoClase = 'pendiente';
                                                                        if ($ahora->between($inicio, $fin)) {
                                                                            $estadoClase = 'activo';
                                                                        } elseif ($ahora->gt($fin)) {
                                                                            $estadoClase = 'finalizado';
                                                                        }
                                                                    @endphp
                                                                    <div class="session-status {{ $estadoClase }}">
                                                                        <span class="status-dot {{ $estadoClase }}"></span>
                                                                        {{ ucfirst($estadoClase) }}
                                                                    </div>
                                                                </div>

                                                                <div class="session-body">
                                                                    <div class="d-flex align-items-center mb-3 text-muted">
                                                                        <i class="far fa-clock mr-2"></i>
                                                                        <span>{{ $sesion->hora_inicio }} - {{ $sesion->hora_fin }}</span>
                                                                    </div>

                                                                    @php
                                                                        $asistenciasRegistradas = \App\Models\AsistenciaAsignatura::where(
                                                                            'curso_asignatura_id', $sesion->curso_asignatura_id,
                                                                        )->where('fecha', $fecha)->count();
                                                                        $totalEstudiantes = \App\Models\Matricula::where(
                                                                            'curso_id', $sesion->cursoAsignatura->curso_id,
                                                                        )->where('estado', 'Matriculado')->count();
                                                                        $porcentajeRegistro = $totalEstudiantes > 0
                                                                            ? round(($asistenciasRegistradas / $totalEstudiantes) * 100)
                                                                            : 0;
                                                                    @endphp

                                                                    <div class="session-stats">
                                                                        <div class="stat-item">
                                                                            <div class="stat-number">{{ $totalEstudiantes }}</div>
                                                                            <div class="stat-label">Estudiantes</div>
                                                                        </div>
                                                                        <div class="stat-item">
                                                                            <div class="stat-number">{{ $asistenciasRegistradas }}</div>
                                                                            <div class="stat-label">Registrados</div>
                                                                        </div>
                                                                        <div class="stat-item">
                                                                            <div class="stat-number">{{ $porcentajeRegistro }}%</div>
                                                                            <div class="stat-label">Completado</div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="progress-bar">
                                                                        <div class="progress-fill" style="width: {{ $porcentajeRegistro }}%"></div>
                                                                    </div>

                                                                    <div class="session-actions">
                                                                        <a href="{{ route('asistencia.registrar-asignatura', [$sesion->curso_asignatura_id, $fecha]) }}"
                                                                            class="btn btn-primary btn-sm">
                                                                            <i class="fas fa-edit mr-1"></i>
                                                                            {{ $asistenciasRegistradas > 0 ? 'Editar Asistencia' : 'Registrar Asistencia' }}
                                                                        </a>

                                                                        @if ($asistenciasRegistradas > 0)
                                                                            <a href="{{ route('asistencia.reporte-curso', $sesion->curso_asignatura_id) }}"
                                                                                class="btn btn-outline-primary btn-sm"
                                                                                title="Ver Reporte">
                                                                                <i class="fas fa-chart-bar mr-1"></i>
                                                                                Reporte
                                                                            </a>
                                                                        @endif
                                                                    </div>

                                                                    @if ($estadoClase === 'activo')
                                                                        <div class="alert alert-success mt-3 mb-0">
                                                                            <i class="fas fa-play-circle mr-2"></i>
                                                                            <strong>¡Clase en progreso!</strong> Registra la asistencia ahora.
                                                                        </div>
                                                                    @elseif($estadoClase === 'finalizado' && $asistenciasRegistradas == 0)
                                                                        <div class="alert alert-warning mt-3 mb-0">
                                                                            <i class="fas fa-exclamation-triangle mr-2"></i>
                                                                            <strong>Asistencia pendiente</strong> - Clase ya finalizó.
                                                                        </div>
                                                                    @elseif($estadoClase === 'finalizado' && $asistenciasRegistradas > 0)
                                                                        <div class="alert alert-info mt-3 mb-0">
                                                                            <i class="fas fa-check-circle mr-2"></i>
                                                                            Asistencia registrada correctamente.
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
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
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loader = document.getElementById('loaderPrincipal');
            if (loader) loader.style.display = 'none';
        });

        function cambiarFecha(fecha) {
            const loader = document.getElementById('loaderPrincipal');
            if (loader) loader.style.display = 'flex';

            setTimeout(() => {
                window.location.href = '{{ route('asistencia.index') }}?fecha=' + fecha;
            }, 500);
        }

        function exportarExcel() {
            const fecha = document.getElementById('fecha-selector').value;
            window.location.href = `/asistencia/exportar?fecha=${fecha}`;
        }

        // Actualización automática cada 5 minutos
        setInterval(() => {
            location.reload();
        }, 300000);
    </script>

@endsection
