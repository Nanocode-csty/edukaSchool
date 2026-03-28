@extends('cplantilla.bprincipal')

@section('titulo', 'Administración de Asistencias')

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

        /* Removed table-responsive scrolling - now using pagination */

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
            min-width: 300px !important;
        }

        /* Make advanced filter selects even wider */
        .filter-group-body .select2-container--bootstrap4 .select2-selection--single {
            min-width: 700px !important;
            width: 100% !important;
            max-width: none !important;
        }

        /* Ensure the Select2 container itself is full width */
        .filter-group-body .select2-container--bootstrap4 {
            width: 100% !important;
            max-width: none !important;
        }

        /* Force the Select2 dropdown to be wide too */
        .filter-group-body .select2-dropdown {
            min-width: 700px !important;
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

        /* Status Badges */
        .status-badge {
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

        .status-badge.presente {
            background: #d1fae5;
            color: #16a34a;
        }

        .status-badge.ausente {
            background: #fee2e2;
            color: #dc2626;
        }

        .status-badge.tardanza {
            background: #fef3c7;
            color: #d97706;
        }

        .status-badge.justificada {
            background: #f3f4f6;
            color: #6b7280;
        }

        .status-badge.registrada {
            background: #d1fae5;
            color: #16a34a;
        }

        .status-badge.pendiente {
            background: #fef3c7;
            color: #d97706;
        }



        /* Filter Group Styles */
        .filter-group-card {
            background: white;
            border: 1px solid #e1e5e9;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: all 0.2s ease;
        }

        .filter-group-card:hover {
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            border-color: #0A8CB3;
        }

        .filter-group-header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 0.75rem 1rem;
            font-weight: 600;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .filter-group-header i {
            font-size: 1rem;
            opacity: 0.9;
        }

        .filter-group-body {
            padding: 1rem;
            background: #fafbfc;
        }

        /* Responsive adjustments for filter groups */
        @media (max-width: 768px) {
            .filter-group-body {
                padding: 0.75rem;
            }

            .filter-group-header {
                padding: 0.5rem 0.75rem;
                font-size: 0.85rem;
            }
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

        /* Four Colored Dots Loading Animation */
        .loading-dots {
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .loading-dots .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            animation: loadingDots 1.4s infinite ease-in-out both;
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

        /* Improved Filter Button Spacing */
        .filter-actions {
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
            flex-wrap: wrap;
            align-items: center;
        }

        .filter-actions .btn {
            min-width: 40px;
            white-space: nowrap;
        }

        .filter-actions .btn-primary {
            min-width: 80px;
        }

        @media (max-width: 768px) {
            .filter-actions {
                gap: 0.5rem;
                justify-content: center;
            }

            .filter-actions .btn {
                padding: 0.25rem 0.5rem;
                font-size: 0.8rem;
            }
        }

        /* Estilos para el dropdown de sugerencias de estudiantes */
        .suggestions-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 2px solid #DAA520;
            border-top: none;
            border-radius: 0 0 0.375rem 0.375rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
        }

        .suggestion-item {
            padding: 8px 12px;
            cursor: pointer;
            border-bottom: 1px solid #f8f9fa;
            transition: background-color 0.2s ease;
        }

        .suggestion-item:hover,
        .suggestion-item.active {
            background-color: #0A8CB3;
            color: white;
        }

        .suggestion-item:last-child {
            border-bottom: none;
        }

        .suggestion-highlight {
            font-weight: bold;
            color: #0A8CB3;
        }

        .suggestion-item:hover .suggestion-highlight {
            color: white;
        }

        .no-suggestions {
            padding: 12px;
            color: #6c757d;
            font-style: italic;
            text-align: center;
        }
    </style>

    <div class="container-fluid estilo-info margen-movil-2">
        <div class="row mt-4 ml-1 mr-1">
            <div class="col-12 mb-3">
                <!-- Breadcrumb - Above the collapsible section -->
                @include('components.breadcrumb', [
                    'module' => 'asistencia',
                    'section' => 'admin'
                ])

                <div class="box_block">
                    <button style="background: #0A8CB3 !important; border:none"
                        class="btn btn-primary btn-block text-left rounded-0 btn_header header_6 estilo-info" type="button"
                        data-toggle="collapse" data-target="#collapseAdminAsistencia" aria-expanded="true"
                        aria-controls="collapseAdminAsistencia">
                        <i class="fas fa-clipboard-check"></i>&nbsp;Administración de Asistencias
                        <div class="float-right"><i class="fas fa-chevron-down"></i></div>
                    </button>
                </div>

                <div class="collapse show" id="collapseAdminAsistencia">
                    <div class="card card-body rounded-0 border-0 pt-0"
                        style="padding-left:0.966666666rem;padding-right:0.9033333333333333rem;">
                        <div class="row margen-movil" style="padding:20px;">
                            <div class="col-12">

                                <!-- Estadísticas Compactas -->
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="stats-compact">
                                            <div class="stats-item">
                                                <span class="stats-number">{{ number_format($estadisticas['total']) }}</span>
                                                <span class="stats-label">Total</span>
                                            </div>
                                            <div class="stats-item">
                                                <span class="stats-number text-success">{{ number_format($estadisticas['presentes']) }}</span>
                                                <span class="stats-label">Presentes</span>
                                            </div>
                                            <div class="stats-item">
                                                <span class="stats-number text-danger">{{ number_format($estadisticas['ausentes']) }}</span>
                                                <span class="stats-label">Ausentes</span>
                                            </div>
                                            <div class="stats-item">
                                                <span class="stats-number text-warning">{{ number_format($estadisticas['tardanzas']) }}</span>
                                                <span class="stats-label">Tardanzas</span>
                                            </div>
                                            <div class="stats-item">
                                                <span class="stats-number text-info">{{ number_format($estadisticas['justificadas']) }}</span>
                                                <span class="stats-label">Justificadas</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Barra de Filtros Mejorada -->
                                <div class="filter-toolbar mb-4">
                                    <div class="card shadow-sm" style="border: 1px solid #e1e5e9; border-radius: 12px;">
                                        <div class="card-body p-4">
                                            <!-- Filtros Principales -->
                                            <div class="row g-4 mb-4">
                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label class="form-label fw-bold text-primary mb-2" style="font-size: 0.85rem;">
                                                            <i class="fas fa-search me-2"></i>BUSCAR ESTUDIANTE
                                                        </label>
                                                        <input type="text" name="buscar_estudiante" id="buscar_estudiante" class="form-control"
                                                               placeholder="DNI, nombre o apellido..." value="{{ $filtros['buscar_estudiante'] ?? '' }}"
                                                               style="border-radius: 8px; border: 2px solid #e3f2fd;"
                                                               autocomplete="off">
                                                        <div id="estudiante-suggestions" class="suggestions-dropdown" style="display: none;">
                                                            <!-- Suggestions will be populated here -->
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label class="form-label fw-bold text-primary mb-2" style="font-size: 0.85rem;">
                                                            <i class="fas fa-check-circle me-2"></i>TIPO ASISTENCIA
                                                        </label>
                                                        <select name="tipo_asistencia_id" id="tipo_asistencia_id" class="form-control"
                                                                style="border-radius: 8px; border: 2px solid #e3f2fd;">
                                                            <option value="">Todos los tipos</option>
                                                            @foreach($tiposAsistencia as $tipo)
                                                                <option value="{{ $tipo->tipo_asistencia_id }}" {{ ($filtros['tipo_asistencia_id'] ?? '') == $tipo->tipo_asistencia_id ? 'selected' : '' }}>
                                                                    {{ $tipo->nombre }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label class="form-label fw-bold text-primary mb-2" style="font-size: 0.85rem;">
                                                            <i class="fas fa-calendar-plus me-2"></i>DESDE
                                                        </label>
                                                        <input type="date" name="fecha_desde" id="fecha_desde" class="form-control"
                                                               value="{{ $filtros['fecha_desde'] ?? '' }}"
                                                               style="border-radius: 8px; border: 2px solid #e3f2fd;">
                                                    </div>
                                                </div>

                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label class="form-label fw-bold text-primary mb-2" style="font-size: 0.85rem;">
                                                            <i class="fas fa-calendar-minus me-2"></i>HASTA
                                                        </label>
                                                        <input type="date" name="fecha_hasta" id="fecha_hasta" class="form-control"
                                                               value="{{ $filtros['fecha_hasta'] ?? '' }}"
                                                               style="border-radius: 8px; border: 2px solid #e3f2fd;">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Botones de Acción -->
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="d-flex justify-content-center gap-3 flex-wrap">
                                                        <button type="button" class="btn btn-outline-secondary btn-lg px-4" data-toggle="collapse" data-target="#advancedFilters">
                                                            <i class="fas fa-filter me-2"></i>Filtros Avanzados
                                                        </button>
                                                        <button type="button" class="btn btn-primary btn-lg px-5" onclick="aplicarFiltros()">
                                                            <i class="fas fa-search me-2"></i>Buscar Registros
                                                        </button>
                                                        <a href="{{ route('asistencia.admin-index') }}" class="btn btn-outline-danger btn-lg px-4">
                                                            <i class="fas fa-times me-2"></i>Limpiar Filtros
                                                        </a>
                                                        <button type="button" class="btn btn-outline-success btn-lg px-4" onclick="exportarPDF()">
                                                            <i class="fas fa-file-pdf me-2"></i>Exportar PDF
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Filtros Avanzados Colapsables -->
                                    <div class="collapse mt-3" id="advancedFilters">
                                        <form method="GET" action="{{ route('asistencia.admin-index') }}" id="advancedFiltersForm" class="row g-3">
                                            <!-- Filtros Académicos -->
                                            <div class="col-12">
                                                <div class="filter-group-card">
                                                    <div class="filter-group-header">
                                                        <i class="fas fa-graduation-cap"></i>
                                                        <span>Filtros Académicos</span>
                                                    </div>
                                                    <div class="filter-group-body">
                                                        <div class="row g-3">
                                                            <div class="col-md-12">
                                                                <label for="profesor_id" class="form-label estilo-info">
                                                                    <i class="fas fa-user-tie mr-1"></i>Profesor
                                                                </label>
                                                                <select name="profesor_id" id="profesor_id" class="form-control select-search" style="width: 100% !important;">
                                                                    <option value="">Todos los profesores</option>
                                                                    @foreach($profesores as $profesor)
                                                                        <option value="{{ $profesor->profesor_id }}" {{ ($filtros['profesor_id'] ?? '') == $profesor->profesor_id ? 'selected' : '' }}>
                                                                            {{ $profesor->persona->nombres ?? 'N/A' }} {{ $profesor->persona->apellidos ?? 'N/A' }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-12 mt-3">
                                                                <label for="curso_id" class="form-label estilo-info">
                                                                    <i class="fas fa-graduation-cap mr-1"></i>Curso
                                                                </label>
                                                                <select name="curso_id" id="curso_id" class="form-control select-search" style="width: 100% !important;">
                                                                    <option value="">Todos los cursos</option>
                                                                    @foreach($cursos as $curso)
                                                                        <option value="{{ $curso->curso_id }}" {{ ($filtros['curso_id'] ?? '') == $curso->curso_id ? 'selected' : '' }}>
                                                                            {{ $curso->grado->nombre }} - {{ $curso->seccion->nombre }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-12 mt-3">
                                                                <label for="asignatura_id" class="form-label estilo-info">
                                                                    <i class="fas fa-book mr-1"></i>Asignatura
                                                                </label>
                                                                <select name="asignatura_id" id="asignatura_id" class="form-control select-search" style="width: 100% !important;">
                                                                    <option value="">Todas las asignaturas</option>
                                                                    @foreach($asignaturas as $asignatura)
                                                                        <option value="{{ $asignatura->asignatura_id }}" {{ ($filtros['asignatura_id'] ?? '') == $asignatura->asignatura_id ? 'selected' : '' }}>
                                                                            {{ $asignatura->nombre }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Ordenamiento -->
                                            <div class="col-12">
                                                <div class="filter-group-card">
                                                    <div class="filter-group-header">
                                                        <i class="fas fa-sort"></i>
                                                        <span>Ordenamiento</span>
                                                    </div>
                                                    <div class="filter-group-body">
                                                        <div class="row g-3">
                                                            <div class="col-md-6">
                                                                <label for="ordenar" class="form-label estilo-info">
                                                                    <i class="fas fa-sort-amount-down mr-1"></i>Ordenar por
                                                                </label>
                                                                <select name="ordenar" id="ordenar" class="form-control form-control-sm">
                                                                    <option value="fecha" {{ ($ordenarPor ?? 'fecha') == 'fecha' ? 'selected' : '' }}>Fecha</option>
                                                                    <option value="estudiante" {{ ($ordenarPor ?? 'fecha') == 'estudiante' ? 'selected' : '' }}>Estudiante</option>
                                                                    <option value="profesor" {{ ($ordenarPor ?? 'fecha') == 'profesor' ? 'selected' : '' }}>Profesor</option>
                                                                    <option value="asignatura" {{ ($ordenarPor ?? 'fecha') == 'asignatura' ? 'selected' : '' }}>Asignatura</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="orden" class="form-label estilo-info">
                                                                    <i class="fas fa-arrow-up mr-1"></i>Dirección
                                                                </label>
                                                                <select name="orden" id="orden" class="form-control form-control-sm">
                                                                    <option value="desc" {{ ($orden ?? 'desc') == 'desc' ? 'selected' : '' }}>Más recientes primero</option>
                                                                    <option value="asc" {{ ($orden ?? 'desc') == 'asc' ? 'selected' : '' }}>Más antiguos primero</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Acciones Avanzadas -->
                                            <div class="col-12">
                                                <div class="d-flex justify-content-end gap-2">
                                                    <button type="button" class="btn btn-primary btn-sm" onclick="aplicarFiltros()">
                                                        <i class="fas fa-search me-1"></i>Aplicar Filtros Avanzados
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <!-- Tabla de Asistencias - Elemento Principal -->
                                <div class="card" style="border: none">
                                    <div
                                        style="background: #E0F7FA; color: #0A8CB3; font-weight: bold; border: 2px solid #86D2E3; border-bottom: 2px solid #86D2E3; padding: 6px 20px; border-radius:4px 4px 0px 0px;">
                                        <i class="fas fa-table mr-2"></i>
                                        Registros de Asistencia ({{ $asistencias->total() }})
                                    </div>
                                    <div class="card-body"
                                        style="border: 2px solid #86D2E3; border-top: none; border-radius: 0px 0px 4px 4px !important; padding: 0;">

                                        @if($asistencias->isEmpty())
                                            <div class="text-center py-5">
                                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">No hay registros de asistencia para mostrar</h5>
                                                <p class="text-muted">Prueba cambiando los filtros de búsqueda</p>
                                            </div>
                                        @else
                                            <table class="table table-hover table-striped mb-0">
                                                    <thead class="table-light" style="position: sticky; top: 0; z-index: 10;">
                                                        <tr>
                                                            <th style="width: 50px;">#</th>
                                                            <th>Estudiante</th>
                                                            <th>Asignatura</th>
                                                            <th>Profesor</th>
                                                            <th>Curso</th>
                                                            <th>Fecha</th>
                                                            <th>Asistencia</th>
                                                            <th>Estado</th>
                                                            <th>Registro</th>
                                                            <th style="width: 100px;">Acciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($asistencias as $index => $asistencia)
                                                            <tr>
                                                                <td class="align-middle">{{ $asistencias->firstItem() + $index }}</td>

                                                                <td class="align-middle">
                                                                    <div>
                                                                        <strong>{{ $asistencia->matricula->estudiante->persona->nombres ?? $asistencia->matricula->estudiante->nombres ?? 'N/A' }}</strong><br>
                                                                        <small class="text-muted">{{ $asistencia->matricula->estudiante->persona->apellidos ?? $asistencia->matricula->estudiante->apellidos ?? 'N/A' }}</small><br>
                                                                        <small class="text-muted">DNI: {{ $asistencia->matricula->estudiante->persona->dni ?? $asistencia->matricula->estudiante->dni ?? 'N/A' }}</small>
                                                                    </div>
                                                                </td>

                                                                <td class="align-middle">
                                                                    <strong>{{ $asistencia->cursoAsignatura->asignatura->nombre }}</strong>
                                                                </td>

                                                                <td class="align-middle">
                                                                    <div>
                                                                        <strong>{{ $asistencia->cursoAsignatura->profesor->persona->nombres ?? $asistencia->cursoAsignatura->profesor->nombres ?? 'N/A' }}</strong><br>
                                                                        <small class="text-muted">{{ $asistencia->cursoAsignatura->profesor->persona->apellidos ?? $asistencia->cursoAsignatura->profesor->apellidos ?? 'N/A' }}</small>
                                                                    </div>
                                                                </td>

                                                                <td class="align-middle">
                                                                    <div>
                                                                        <strong>{{ $asistencia->matricula->curso->grado->nombre }}</strong><br>
                                                                        <small class="text-muted">{{ $asistencia->matricula->curso->seccion->nombre }}</small>
                                                                    </div>
                                                                </td>

                                                                <td class="align-middle">
                                                                    <div>
                                                                        <strong>{{ $asistencia->fecha ? $asistencia->fecha->format('d/m/Y') : 'N/A' }}</strong><br>
                                                                        <small class="text-muted">{{ $asistencia->fecha ? $asistencia->fecha->format('l') : '' }}</small>
                                                                    </div>
                                                                </td>

                                                                <td class="align-middle">
                                                                    <span class="status-badge {{ $asistencia->tipoAsistencia->codigo == 'A' ? 'presente' : ($asistencia->tipoAsistencia->codigo == 'F' ? 'ausente' : ($asistencia->tipoAsistencia->codigo == 'T' ? 'tardanza' : 'justificada')) }}">
                                                                        <i class="fas fa-{{ $asistencia->tipoAsistencia->codigo == 'A' ? 'check' : ($asistencia->tipoAsistencia->codigo == 'F' ? 'times' : ($asistencia->tipoAsistencia->codigo == 'T' ? 'clock' : 'file-alt')) }}"></i>
                                                                        {{ $asistencia->tipoAsistencia->nombre }}
                                                                    </span>
                                                                </td>

                                                                <td class="align-middle">
                                                                    <span class="status-badge {{ $asistencia->estado == 'Registrada' ? 'registrada' : 'pendiente' }}">
                                                                        {{ $asistencia->estado }}
                                                                    </span>
                                                                </td>

                                                                <td class="align-middle">
                                                                    <div>
                                                                        <small class="text-muted">{{ $asistencia->hora_registro ? $asistencia->hora_registro->format('H:i') : 'N/A' }}</small><br>
                                                                        <small class="text-muted">{{ $asistencia->hora_registro ? $asistencia->hora_registro->format('d/m/Y') : '' }}</small>
                                                                    </div>
                                                                </td>

                                                                <td class="align-middle">
                                                                    <div class="btn-group" role="group">
                                                                        <a href="{{ route('asistencia.detalle-estudiante', $asistencia->matricula_id) }}"
                                                                            class="btn btn-sm btn-outline-primary"
                                                                            title="Ver detalle del estudiante"
                                                                            target="_blank">
                                                                            <i class="fas fa-eye"></i>
                                                                        </a>
                                                                        @if($asistencia->justificacion)
                                                                            <button class="btn btn-sm btn-outline-info"
                                                                                    title="Ver justificación"
                                                                                    onclick="verJustificacion('{{ $asistencia->justificacion }}')">
                                                                                <i class="fas fa-comment"></i>
                                                                            </button>
                                                                        @endif
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>

                                            <!-- Paginación -->
                                            <div class="d-flex justify-content-center mt-3 p-3">
                                                {{ $asistencias->appends(request()->query())->links() }}
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

    <!-- Modal para ver justificación -->
    <div class="modal fade" id="justificacionModal" tabindex="-1" role="dialog" aria-labelledby="justificacionModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="justificacionModalLabel">Justificación de Asistencia</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="justificacionText"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .form-control {
            border: 1px solid #DAA520;
        }

        .table th {
            font-weight: 600;
            font-size: 0.875rem;
        }

        .table td {
            font-size: 0.875rem;
        }
    </style>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Inicializar Select2 para los selects con búsqueda
            $('.select-search').select2({
                placeholder: function() {
                    return $(this).data('placeholder') || 'Seleccionar...';
                },
                allowClear: true,
                width: '100%',
                theme: 'bootstrap4',
                language: {
                    noResults: function() {
                        return "No se encontraron resultados";
                    },
                    searching: function() {
                        return "Buscando...";
                    }
                }
            });

            // Configurar placeholders específicos
            $('#profesor_id').select2({
                placeholder: 'Buscar profesor...',
                allowClear: true
            });

            $('#curso_id').select2({
                placeholder: 'Buscar curso...',
                allowClear: true
            });

            $('#asignatura_id').select2({
                placeholder: 'Buscar asignatura...',
                allowClear: true
            });

            // Inicializar funcionalidad de búsqueda de estudiantes
            inicializarBusquedaEstudiantes();

            // Cargar estudiantes inicialmente
            cargarEstudiantes();
        });

        function verJustificacion(justificacion) {
            document.getElementById('justificacionText').textContent = justificacion;
            $('#justificacionModal').modal('show');
        }



        function aplicarFiltros() {
            // Mostrar loading en toda la tabla
            const tablaCard = document.querySelector('.card[style*="border: none"]');
            if (tablaCard) {
                tablaCard.innerHTML = `
                    <div style="background: #E0F7FA; color: #0A8CB3; font-weight: bold; border: 2px solid #86D2E3; border-bottom: 2px solid #86D2E3; padding: 6px 20px; border-radius:4px 4px 0px 0px;">
                        <i class="fas fa-table mr-2"></i>
                        Registros de Asistencia
                    </div>
                    <div class="card-body" style="border: 2px solid #86D2E3; border-top: none; border-radius: 0px 0px 4px 4px !important; padding: 0;">
                        <div class="text-center py-5">
                            <div class="loading-dots">
                                <div class="dot"></div>
                                <div class="dot"></div>
                                <div class="dot"></div>
                                <div class="dot"></div>
                            </div>
                            <p class="mt-2 text-muted">Aplicando filtros...</p>
                        </div>
                    </div>
                `;
            }

            // Recopilar todos los parámetros de filtros
            const params = new URLSearchParams();

            // Filtros rápidos
            const estudianteIdElement = document.getElementById('buscar_estudiante');
            const tipoAsistenciaElement = document.getElementById('tipo_asistencia_id');
            const fechaDesdeElement = document.getElementById('fecha_desde');
            const fechaHastaElement = document.getElementById('fecha_hasta');

            const estudianteId = estudianteIdElement ? estudianteIdElement.value : '';
            const tipoAsistencia = tipoAsistenciaElement ? tipoAsistenciaElement.value : '';
            const fechaDesde = fechaDesdeElement ? fechaDesdeElement.value : '';
            const fechaHasta = fechaHastaElement ? fechaHastaElement.value : '';

            if (estudianteId) params.set('estudiante_id', estudianteId);
            if (tipoAsistencia) params.set('tipo_asistencia_id', tipoAsistencia);
            if (fechaDesde) params.set('fecha_desde', fechaDesde);
            if (fechaHasta) params.set('fecha_hasta', fechaHasta);

            // Filtros avanzados (si están visibles)
            const profesorId = document.getElementById('profesor_id')?.value;
            const cursoId = document.getElementById('curso_id')?.value;
            const asignaturaId = document.getElementById('asignatura_id')?.value;
            const ordenar = document.getElementById('ordenar')?.value;
            const orden = document.getElementById('orden')?.value;

            if (profesorId) params.set('profesor_id', profesorId);
            if (cursoId) params.set('curso_id', cursoId);
            if (asignaturaId) params.set('asignatura_id', asignaturaId);
            if (ordenar) params.set('ordenar', ordenar);
            if (orden) params.set('orden', orden);

            // Resetear página a 1
            params.set('page', '1');

            // Hacer petición AJAX
            fetch('/asistencia/api/tabla-asistencias?' + params.toString(), {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                }
            })
            .then(response => response.text())
            .then(html => {
                // Reemplazar toda la tabla
                const tablaCard = document.querySelector('.card[style*="border: none"]');
                if (tablaCard) {
                    tablaCard.outerHTML = html;
                }

                // Actualizar estadísticas si están disponibles en la respuesta
                // Las estadísticas se mantienen igual ya que no cambian con los filtros

                // Actualizar URL sin recargar página
                const newUrl = '{{ route("asistencia.admin-index") }}?' + params.toString();
                window.history.pushState({}, '', newUrl);
            })
            .catch(error => {
                console.error('Error:', error);
                // Mostrar mensaje de error
                const tablaCard = document.querySelector('.card[style*="border: none"]');
                if (tablaCard) {
                    tablaCard.innerHTML = `
                        <div style="background: #E0F7FA; color: #0A8CB3; font-weight: bold; border: 2px solid #86D2E3; border-bottom: 2px solid #86D2E3; padding: 6px 20px; border-radius:4px 4px 0px 0px;">
                            <i class="fas fa-table mr-2"></i>
                            Registros de Asistencia
                        </div>
                        <div class="card-body" style="border: 2px solid #86D2E3; border-top: none; border-radius: 0px 0px 4px 4px !important; padding: 0;">
                            <div class="text-center py-5">
                                <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                                <h5 class="text-danger">Error al cargar los datos</h5>
                                <p class="text-muted">Por favor, intenta nuevamente</p>
                                <button onclick="aplicarFiltros()" class="btn btn-primary">Reintentar</button>
                            </div>
                        </div>
                    `;
                }
            });
        }

        function exportarPDF() {
            // Crear URL con los parámetros actuales
            const urlParams = new URLSearchParams(window.location.search);

            // Redirigir a la URL de exportación
            window.location.href = '{{ route("asistencia.exportar-pdf-admin") }}?' + urlParams.toString();
        }

        // Auto-submit del formulario cuando cambian los filtros principales
        document.getElementById('tipo_asistencia_id').addEventListener('change', function() {
            aplicarFiltros();
        });

        // Permitir búsqueda con Enter
        document.getElementById('buscar').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                aplicarFiltros();
            }
        });

        // Filtros dependientes: curso y asignatura dependen del profesor
        $('#profesor_id').on('change', function() {
            const profesorId = $(this).val();

            // Limpiar y resetear selects dependientes usando Select2
            $('#curso_id').empty().append('<option value="">Todos los cursos</option>').trigger('change');
            $('#asignatura_id').empty().append('<option value="">Todas las asignaturas</option>').trigger('change');

            if (profesorId) {
                // Cargar cursos del profesor
                fetch(`/asistencia/api/cursos-por-profesor/${profesorId}`)
                    .then(response => response.json())
                    .then(data => {
                        console.log('Cursos cargados:', data); // Debug
                        data.forEach(curso => {
                            const option = new Option(`${curso.grado.nombre} - ${curso.seccion.nombre}`, curso.curso_id, false, false);
                            $('#curso_id').append(option);
                        });
                        $('#curso_id').trigger('change');
                    })
                    .catch(error => {
                        console.error('Error cargando cursos:', error);
                        alert('Error al cargar los cursos del profesor');
                    });

                // Cargar asignaturas del profesor
                fetch(`/asistencia/api/asignaturas-por-profesor/${profesorId}`)
                    .then(response => response.json())
                    .then(data => {
                        console.log('Asignaturas cargadas:', data); // Debug
                        data.forEach(asignatura => {
                            const option = new Option(asignatura.nombre, asignatura.asignatura_id, false, false);
                            $('#asignatura_id').append(option);
                        });
                        $('#asignatura_id').trigger('change');
                    })
                    .catch(error => {
                        console.error('Error cargando asignaturas:', error);
                        alert('Error al cargar las asignaturas del profesor');
                    });
            }
        });

        // Función para cargar estudiantes basados en filtros
        function cargarEstudiantes() {
            const profesorId = $('#profesor_id').val();
            const cursoId = $('#curso_id').val();
            const asignaturaId = $('#asignatura_id').val();

            // Limpiar estudiantes del campo principal
            $('#buscar_estudiante').empty().append('<option value="">Todos los estudiantes</option>');

            // Preparar parámetros para la consulta
            const params = new URLSearchParams();
            if (profesorId) params.set('profesor_id', profesorId);
            if (cursoId) params.set('curso_id', cursoId);
            if (asignaturaId) params.set('asignatura_id', asignaturaId);

            // Solo cargar estudiantes si hay algún filtro aplicado
            if (profesorId || cursoId || asignaturaId) {
                fetch(`/asistencia/api/estudiantes-por-filtros?${params.toString()}`)
                    .then(response => response.json())
                    .then(data => {
                        console.log('Estudiantes cargados:', data);
                        data.forEach(estudiante => {
                            const nombreCompleto = `${estudiante.persona?.nombres || 'N/A'} ${estudiante.persona?.apellidos || 'N/A'}`;
                            const dni = estudiante.persona?.dni || 'N/A';
                            const gradoSeccion = `${estudiante.curso?.grado?.nombre || 'N/A'} - ${estudiante.curso?.seccion?.nombre || 'N/A'}`;
                            const optionText = `${nombreCompleto} - ${dni} (${gradoSeccion})`;
                            const option = new Option(optionText, estudiante.matricula_id, false, false);
                            $('#buscar_estudiante').append(option);
                        });
                        $('#buscar_estudiante').trigger('change');
                    })
                    .catch(error => {
                        console.error('Error cargando estudiantes:', error);
                        // No mostrar alerta para evitar molestar al usuario
                    });
            }
        }

        // Función para inicializar la búsqueda de estudiantes
        function inicializarBusquedaEstudiantes() {
            const input = document.getElementById('buscar_estudiante');
            const suggestionsContainer = document.getElementById('estudiante-suggestions');
            let currentSuggestions = [];
            let selectedIndex = -1;
            let searchTimeout;

            // Evento de input para búsqueda
            input.addEventListener('input', function(e) {
                const query = e.target.value.trim();
                clearTimeout(searchTimeout);

                if (query.length < 2) {
                    suggestionsContainer.style.display = 'none';
                    return;
                }

                searchTimeout = setTimeout(() => {
                    buscarEstudiantes(query);
                }, 300);
            });

            // Eventos de teclado para navegación
            input.addEventListener('keydown', function(e) {
                if (suggestionsContainer.style.display === 'none') return;

                switch(e.key) {
                    case 'ArrowDown':
                        e.preventDefault();
                        selectedIndex = Math.min(selectedIndex + 1, currentSuggestions.length - 1);
                        updateSelection();
                        break;
                    case 'ArrowUp':
                        e.preventDefault();
                        selectedIndex = Math.max(selectedIndex - 1, -1);
                        updateSelection();
                        break;
                    case 'Enter':
                        e.preventDefault();
                        if (selectedIndex >= 0 && currentSuggestions[selectedIndex]) {
                            seleccionarEstudiante(currentSuggestions[selectedIndex]);
                        }
                        break;
                    case 'Escape':
                        suggestionsContainer.style.display = 'none';
                        selectedIndex = -1;
                        break;
                }
            });

            // Ocultar sugerencias al hacer click fuera
            document.addEventListener('click', function(e) {
                if (!input.contains(e.target) && !suggestionsContainer.contains(e.target)) {
                    suggestionsContainer.style.display = 'none';
                    selectedIndex = -1;
                }
            });

            function buscarEstudiantes(query) {
                // Preparar filtros adicionales
                const profesorId = $('#profesor_id').val();
                const cursoId = $('#curso_id').val();
                const asignaturaId = $('#asignatura_id').val();

                const params = new URLSearchParams();
                params.set('query', query);
                if (profesorId) params.set('profesor_id', profesorId);
                if (cursoId) params.set('curso_id', cursoId);
                if (asignaturaId) params.set('asignatura_id', asignaturaId);

                fetch(`/asistencia/api/buscar-estudiantes?${params.toString()}`)
                    .then(response => response.json())
                    .then(data => {
                        mostrarSugerencias(data, query);
                    })
                    .catch(error => {
                        console.error('Error buscando estudiantes:', error);
                        suggestionsContainer.innerHTML = '<div class="no-suggestions">Error al buscar estudiantes</div>';
                        suggestionsContainer.style.display = 'block';
                    });
            }

            function mostrarSugerencias(estudiantes, query) {
                if (estudiantes.length === 0) {
                    suggestionsContainer.innerHTML = '<div class="no-suggestions">No se encontraron estudiantes</div>';
                    suggestionsContainer.style.display = 'block';
                    return;
                }

                currentSuggestions = estudiantes;
                selectedIndex = -1;

                const html = estudiantes.map((estudiante, index) => {
                    const nombreCompleto = `${estudiante.persona?.nombres || 'N/A'} ${estudiante.persona?.apellidos || 'N/A'}`;
                    const dni = estudiante.persona?.dni || 'N/A';
                    const gradoSeccion = `${estudiante.curso?.grado?.nombre || 'N/A'} - ${estudiante.curso?.seccion?.nombre || 'N/A'}`;

                    // Resaltar el texto coincidente
                    const highlightedNombre = resaltarCoincidencia(nombreCompleto, query);
                    const highlightedDni = resaltarCoincidencia(dni, query);

                    return `
                        <div class="suggestion-item" data-index="${index}" data-id="${estudiante.matricula_id}">
                            <div>
                                <strong>${highlightedNombre}</strong>
                                ${dni !== highlightedDni ? ` - <span class="suggestion-highlight">${highlightedDni}</span>` : ` - ${dni}`}
                            </div>
                            <small class="text-muted">${gradoSeccion}</small>
                        </div>
                    `;
                }).join('');

                suggestionsContainer.innerHTML = html;
                suggestionsContainer.style.display = 'block';

                // Agregar event listeners a las sugerencias
                suggestionsContainer.querySelectorAll('.suggestion-item').forEach((item, index) => {
                    item.addEventListener('click', () => {
                        seleccionarEstudiante(estudiantes[index]);
                    });
                    item.addEventListener('mouseenter', () => {
                        selectedIndex = index;
                        updateSelection();
                    });
                });
            }

            function resaltarCoincidencia(texto, query) {
                if (!query) return texto;
                const regex = new RegExp(`(${query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
                return texto.replace(regex, '<span class="suggestion-highlight">$1</span>');
            }

            function updateSelection() {
                suggestionsContainer.querySelectorAll('.suggestion-item').forEach((item, index) => {
                    item.classList.toggle('active', index === selectedIndex);
                });
            }

            function seleccionarEstudiante(estudiante) {
                const nombreCompleto = `${estudiante.persona?.nombres || 'N/A'} ${estudiante.persona?.apellidos || 'N/A'}`;
                const dni = estudiante.persona?.dni || 'N/A';
                input.value = `${nombreCompleto} - ${dni}`;

                // Guardar el ID seleccionado en un atributo data
                input.setAttribute('data-selected-id', estudiante.matricula_id);

                suggestionsContainer.style.display = 'none';
                selectedIndex = -1;

                // Opcional: aplicar filtros automáticamente
                // aplicarFiltros();
            }
        }

        // Función para obtener el ID del estudiante seleccionado
        function getEstudianteSeleccionadoId() {
            const input = document.getElementById('buscar_estudiante');
            return input.getAttribute('data-selected-id') || '';
        }

        // Cargar estudiantes cuando cambien los filtros
        $('#profesor_id, #curso_id, #asignatura_id').on('change', function() {
            cargarEstudiantes();
        });

        // Manejar clicks en enlaces de paginación con AJAX
        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();

            const url = new URL($(this).attr('href'));
            const page = url.searchParams.get('page');

            // Mostrar loading en toda la tabla
            const tablaCard = document.querySelector('.card[style*="border: none"]');
            if (tablaCard) {
                tablaCard.innerHTML = `
                    <div style="background: #E0F7FA; color: #0A8CB3; font-weight: bold; border: 2px solid #86D2E3; border-bottom: 2px solid #86D2E3; padding: 6px 20px; border-radius:4px 4px 0px 0px;">
                        <i class="fas fa-table mr-2"></i>
                        Registros de Asistencia
                    </div>
                    <div class="card-body" style="border: 2px solid #86D2E3; border-top: none; border-radius: 0px 0px 4px 4px !important; padding: 0;">
                        <div class="text-center py-5">
                            <div class="loading-dots">
                                <div class="dot"></div>
                                <div class="dot"></div>
                                <div class="dot"></div>
                                <div class="dot"></div>
                            </div>
                            <p class="mt-2 text-muted">Cargando página...</p>
                        </div>
                    </div>
                `;
            }

            // Recopilar parámetros actuales
            const currentParams = new URLSearchParams(window.location.search);
            currentParams.set('page', page);

            // Hacer petición AJAX
            fetch('/asistencia/api/tabla-asistencias?' + currentParams.toString(), {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                }
            })
            .then(response => response.text())
            .then(html => {
                // Reemplazar toda la tabla
                const tablaCard = document.querySelector('.card[style*="border: none"]');
                if (tablaCard) {
                    tablaCard.outerHTML = html;
                }

                // Actualizar URL sin recargar página
                const newUrl = '{{ route("asistencia.admin-index") }}?' + currentParams.toString();
                window.history.pushState({}, '', newUrl);
            })
            .catch(error => {
                console.error('Error:', error);
                // Mostrar mensaje de error
                const tablaCard = document.querySelector('.card[style*="border: none"]');
                if (tablaCard) {
                    tablaCard.innerHTML = `
                        <div style="background: #E0F7FA; color: #0A8CB3; font-weight: bold; border: 2px solid #86D2E3; border-bottom: 2px solid #86D2E3; padding: 6px 20px; border-radius:4px 4px 0px 0px;">
                            <i class="fas fa-table mr-2"></i>
                            Registros de Asistencia
                        </div>
                        <div class="card-body" style="border: 2px solid #86D2E3; border-top: none; border-radius: 0px 0px 4px 4px !important; padding: 0;">
                            <div class="text-center py-5">
                                <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                                <h5 class="text-danger">Error al cargar la página</h5>
                                <p class="text-muted">Por favor, intenta nuevamente</p>
                                <button onclick="location.reload()" class="btn btn-primary">Recargar página</button>
                            </div>
                        </div>
                    `;
                }
            });
        });
    </script>
@endsection
