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

        }

        .session-meta {
            font-size: 0.875rem;
            color: var(--gray-600);
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
            background: var(--success-light);
            color: var(--success);
        }

        .session-status.pendiente {
            background: var(--gray-100);
            color: var(--gray-600);
        }

        .session-status.finalizado {
            background: var(--warning-light);
            color: var(--warning);
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
            color: var(--gray-900);
        }

        .stat-label {
            font-size: 0.75rem;
            color: var(--gray-600);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 500;
        }

        .progress-bar {
            height: 4px;
            background: var(--gray-200);
            border-radius: 2px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: var(--success);
            transition: width 0.3s ease;
        }

        .session-actions {
            display: flex;
            gap: 0.5rem;
        }

        /* Clean Buttons */
        .btn-clean {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            border: 1px solid transparent;
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: #1d4ed8;
            box-shadow: var(--shadow);
        }

        .btn-outline {
            background: white;
            color: var(--gray-700);
            border-color: var(--gray-300);
        }

        .btn-outline:hover {
            background: var(--gray-50);
            border-color: var(--gray-400);
        }

        /* Header Section */
        .page-header {
            background: linear-gradient(135deg, var(--primary) 0%, #1e40af 100%);
            color: white;
            padding: 2rem 0;
            margin: -2rem -1rem 2rem -1rem;
        }

        .page-title {
            font-size: 1.875rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            font-size: 1rem;
            opacity: 0.9;
        }

        /* Date Selector */
        .date-selector {
            background: white;
            border: 1px solid var(--gray-300);
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            transition: var(--transition);
        }

        .date-selector:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        /* Quick Actions */
        .quick-actions {
            display: flex;
            gap: 0.75rem;
            align-items: center;
        }

        .action-btn {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 6px;
            border: none;
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
        }

        .action-btn:hover {
            background: #1d4ed8;
            transform: scale(1.05);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: var(--gray-500);
        }

        .empty-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        /* Loading */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .spinner {
            width: 2rem;
            height: 2rem;
            border: 2px solid var(--gray-300);
            border-top: 2px solid var(--primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .session-grid {
                grid-template-columns: 1fr;
            }

            .session-stats {
                flex-direction: column;
                gap: 1rem;
            }

            .quick-actions {
                flex-wrap: wrap;
            }
        }

        @media (max-width: 640px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .page-header {
                padding: 1.5rem 0;
                margin: -1.5rem -1rem 1.5rem -1rem;
            }

            .page-title {
                font-size: 1.5rem;
            }
        }
    </style>

    <div class="container-clean" id="contenido-principal" style="position: relative;">
        @include('ccomponentes.loader', ['id' => 'loaderPrincipal'])

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

        <!-- Page Header -->
        <div class="page-header">
            <div class="container-clean">
                <div class="page-title">Control de Asistencia</div>
                <div class="page-subtitle">Gestiona la asistencia de tus estudiantes de manera eficiente</div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="space-y-8">
            <!-- Header Controls -->
            <div class="card-clean">
                <div class="card-body-clean">
                    <div class="space-y-6">
                        <!-- Date and Actions Row -->
                        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                            <div style="display: flex; align-items: center; gap: 1rem;">
                                <div style="width: 2.5rem; height: 2.5rem; border-radius: 50%; background: var(--primary); color: white; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <div>
                                    <div class="text-lg font-semibold text-gray-900">
                                        {{ Carbon\Carbon::parse($fecha)->isoFormat('dddd, D [de] MMMM') }}
                                    </div>
                                    <div class="text-sm text-gray-600">
                                        {{ Carbon\Carbon::parse($fecha)->isoFormat('YYYY') }}
                                    </div>
                                </div>
                            </div>

                            <div class="quick-actions">
                                <button class="action-btn" onclick="exportarExcel()" title="Exportar Reporte">
                                    <i class="fas fa-file-excel"></i>
                                </button>
                                <a href="{{ route('asistencia.reporte-general') }}" class="btn btn-outline" title="Ver Reportes">
                                    <i class="fas fa-chart-pie"></i>
                                    <span>Reportes</span>
                                </a>
                                <input type="date" id="fecha-selector" class="date-selector"
                                    value="{{ $fecha }}" onchange="cambiarFecha(this.value)"
                                    aria-label="Seleccionar fecha de asistencia">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-value">{{ number_format($estadisticas['total_registros']) }}</div>
                    <div class="stat-label">Total Registros</div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 100%"></div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon presentes">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-value">{{ number_format($estadisticas['presentes']) }}</div>
                    <div class="stat-label">Presentes</div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: {{ $estadisticas['porcentaje_asistencia'] }}%"></div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon ausentes">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div class="stat-value">{{ number_format($estadisticas['ausentes']) }}</div>
                    <div class="stat-label">Ausentes</div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: {{ $estadisticas['total_registros'] > 0 ? ($estadisticas['ausentes'] / $estadisticas['total_registros']) * 100 : 0 }}%"></div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon tardanzas">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-value">{{ number_format($estadisticas['tardanzas']) }}</div>
                    <div class="stat-label">Tardanzas</div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: {{ $estadisticas['total_registros'] > 0 ? ($estadisticas['tardanzas'] / $estadisticas['total_registros']) * 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Sessions Section -->
            <div class="card-clean">
                <div class="card-header-clean">
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div style="width: 2.5rem; height: 2.5rem; border-radius: 50%; background: var(--primary); color: white; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-0">Sesiones Programadas</h3>
                                <p class="text-sm text-gray-600 mb-0">Gestiona la asistencia de tus clases del día</p>
                            </div>
                        </div>
                        <div class="session-status pendiente">
                            <span class="status-dot pendiente"></span>
                            {{ $sesiones->count() }} sesiones
                        </div>
                    </div>
                </div>

                <div class="card-body-clean">
                    @if ($sesiones->isEmpty())
                        @php
                            $esFeriado = \App\Models\Feriado::esFeriado($fecha);
                            $feriadoInfo = $esFeriado ? \App\Models\Feriado::porFecha($fecha)->first() : null;
                        @endphp

                        @if($esFeriado)
                            <div class="empty-state">
                                <i class="fas fa-calendar-day empty-icon" style="color: var(--danger);"></i>
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">Día Feriado</h3>
                                <p class="text-gray-600 mb-2"><strong>{{ $feriadoInfo->nombre }}</strong></p>
                                @if($feriadoInfo->descripcion)
                                    <p class="text-gray-600 mb-4">{{ $feriadoInfo->descripcion }}</p>
                                @endif
                                <div style="background: var(--info-light); border: 1px solid var(--info); border-radius: 8px; padding: 1rem; color: var(--info);">
                                    <i class="fas fa-info-circle me-2"></i>
                                    No hay sesiones programadas porque es feriado. Si hay clases de recuperación programadas, aparecerán en fechas posteriores.
                                </div>
                            </div>
                        @else
                            <div class="empty-state">
                                <i class="fas fa-calendar-times empty-icon"></i>
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">No tienes sesiones programadas</h3>
                                <p class="text-gray-600 mb-4">Revisa el horario académico o selecciona otra fecha para ver las clases disponibles.</p>
                                <button class="btn btn-primary" onclick="document.getElementById('fecha-selector').focus()">
                                    <i class="fas fa-calendar-day me-2"></i>
                                    Cambiar Fecha
                                </button>
                            </div>
                        @endif
                    @else
                        <div class="session-grid">
                            @foreach ($sesiones as $index => $sesion)
                                <div class="session-card">
                                    <div class="session-header">
                                        <div class="session-title">{{ $sesion->cursoAsignatura->asignatura->nombre }}</div>
                                        <div class="session-meta">
                                            <i class="fas fa-graduation-cap me-1"></i>
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
                                        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem; color: var(--gray-600);">
                                            <i class="far fa-clock"></i>
                                            <span class="text-sm">{{ $sesion->hora_inicio }} - {{ $sesion->hora_fin }}</span>
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
                                                class="btn btn-primary btn-clean">
                                                <i class="fas fa-edit"></i>
                                                <span>{{ $asistenciasRegistradas > 0 ? 'Editar Asistencia' : 'Registrar Asistencia' }}</span>
                                            </a>

                                            @if ($asistenciasRegistradas > 0)
                                                <a href="{{ route('asistencia.reporte-curso', $sesion->curso_asignatura_id) }}"
                                                    class="btn btn-outline btn-clean"
                                                    title="Ver Reporte">
                                                    <i class="fas fa-chart-bar"></i>
                                                </a>
                                            @endif
                                        </div>

                                        @if ($estadoClase === 'activo')
                                            <div style="background: var(--success-light); border: 1px solid var(--success); border-radius: 6px; padding: 0.75rem; margin-top: 1rem; color: var(--success);">
                                                <i class="fas fa-play-circle me-2"></i>
                                                <strong>¡Clase en progreso!</strong> Registra la asistencia ahora.
                                            </div>
                                        @elseif($estadoClase === 'finalizado' && $asistenciasRegistradas == 0)
                                            <div style="background: var(--warning-light); border: 1px solid var(--warning); border-radius: 6px; padding: 0.75rem; margin-top: 1rem; color: var(--warning);">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                <strong>Asistencia pendiente</strong> - Clase ya finalizó.
                                            </div>
                                        @elseif($estadoClase === 'finalizado' && $asistenciasRegistradas > 0)
                                            <div style="background: var(--info-light); border: 1px solid var(--info); border-radius: 6px; padding: 0.75rem; margin-top: 1rem; color: var(--info);">
                                                <i class="fas fa-check-circle me-2"></i>
                                                Asistencia registrada correctamente.
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
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
