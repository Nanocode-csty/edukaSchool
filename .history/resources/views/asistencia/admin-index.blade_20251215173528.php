@extends('cplantilla.bprincipal')

@section('titulo', 'Administración de Asistencias')

@section('contenidoplantilla')
    <style>
        /* Minimalist Design System */
        :root {
            --primary: #2563eb;
            --primary-light: #dbeafe;
            --success: #16a34a;
            --success-light: #f0fdf4;
            --danger: #dc2626;
            --danger-light: #fef2f2;
            --warning: #d97706;
            --warning-light: #fffbeb;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
            --border-radius: 8px;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --transition: all 0.2s ease;
        }

        /* Clean Typography */
        .text-sm { font-size: 0.875rem; line-height: 1.25rem; }
        .text-base { font-size: 1rem; line-height: 1.5rem; }
        .text-lg { font-size: 1.125rem; line-height: 1.75rem; }
        .text-xl { font-size: 1.25rem; line-height: 1.75rem; }
        .text-2xl { font-size: 1.5rem; line-height: 2rem; }
        .font-medium { font-weight: 500; }
        .font-semibold { font-weight: 600; }
        .font-bold { font-weight: 700; }
        .text-gray-600 { color: var(--gray-600); }
        .text-gray-700 { color: var(--gray-700); }
        .text-gray-900 { color: var(--gray-900); }

        /* Clean Layout */
        .container-clean { max-width: 1200px; margin: 0 auto; padding: 0 1rem; }
        .space-y-4 > * + * { margin-top: 1rem; }
        .space-y-6 > * + * { margin-top: 1.5rem; }
        .space-y-8 > * + * { margin-top: 2rem; }

        /* Minimalist Cards */
        .card-clean {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--gray-200);
        }

        .card-header-clean {
            padding: 1.5rem;
            border-bottom: 1px solid var(--gray-200);
            background: var(--gray-50);
        }

        .card-body-clean {
            padding: 1.5rem;
        }

        /* Clean Stats */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .stat-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: var(--shadow);
            border: 1px solid var(--gray-200);
            transition: var(--transition);
        }

        .stat-card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-1px);
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 0.25rem;
        }

        .stat-label {
            font-size: 0.875rem;
            color: var(--gray-600);
            font-weight: 500;
        }

        .stat-icon {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin-bottom: 0.75rem;
        }

        .stat-icon.total { background: var(--primary); }
        .stat-icon.presentes { background: var(--success); }
        .stat-icon.ausentes { background: var(--danger); }
        .stat-icon.tardanzas { background: var(--warning); }

        /* Filter Section */
        .filter-section {
            background: var(--gray-50);
            border: 1px solid var(--gray-200);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .filter-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--gray-700);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .filter-input {
            padding: 0.5rem 0.75rem;
            border: 1px solid var(--gray-300);
            border-radius: 6px;
            font-size: 0.875rem;
            transition: var(--transition);
        }

        .filter-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .filter-select {
            padding: 0.5rem 2.5rem 0.5rem 0.75rem;
            border: 1px solid var(--gray-300);
            border-radius: 6px;
            font-size: 0.875rem;
            background: white;
            transition: var(--transition);
            cursor: pointer;
        }

        .filter-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        /* Table Styles */
        .table-container {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--gray-200);
            overflow: hidden;
        }

        .table-header {
            background: var(--gray-50);
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .table-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--gray-900);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .table-responsive {
            max-height: calc(100vh - 400px);
            overflow-y: auto;
        }

        .table-modern {
            margin-bottom: 0;
            width: 100%;
        }

        .table-modern thead th {
            background: var(--primary);
            color: white;
            border: none;
            padding: 1rem 0.75rem;
            font-weight: 600;
            font-size: 0.875rem;
            position: sticky;
            top: 0;
            z-index: 10;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .table-modern tbody tr {
            transition: var(--transition);
        }

        .table-modern tbody tr:hover {
            background: var(--gray-50);
        }

        .table-modern tbody td {
            padding: 1rem 0.75rem;
            border-bottom: 1px solid var(--gray-200);
            font-size: 0.875rem;
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
            background: var(--success-light);
            color: var(--success);
        }

        .status-badge.ausente {
            background: var(--danger-light);
            color: var(--danger);
        }

        .status-badge.tardanza {
            background: var(--warning-light);
            color: var(--warning);
        }

        .status-badge.justificada {
            background: var(--gray-100);
            color: var(--gray-600);
        }

        .status-badge.registrada {
            background: var(--success-light);
            color: var(--success);
        }

        .status-badge.pendiente {
            background: var(--warning-light);
            color: var(--warning);
        }

        /* Action Buttons */
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

        .btn-info {
            background: #0ea5e9;
            color: white;
        }

        .btn-info:hover {
            background: #0284c7;
        }

        /* Pagination */
        .pagination-container {
            padding: 1.5rem;
            background: var(--gray-50);
            border-top: 1px solid var(--gray-200);
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

            .filter-grid {
                grid-template-columns: 1fr;
            }

            .table-responsive {
                max-height: calc(100vh - 500px);
            }
        }

        @media (max-width: 640px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .table-header {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }
        }

        /* Breadcrumb Styles */
        .breadcrumb-container {
            background: white;
            border-radius: var(--border-radius);
            padding: 1rem 1.5rem;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--gray-200);
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
            color: var(--gray-400);
            font-weight: 400;
            margin: 0 0.5rem;
        }

        .breadcrumb-item a {
            color: var(--primary);
            text-decoration: none;
            transition: var(--transition);
        }

        .breadcrumb-item a:hover {
            color: #1d4ed8;
            text-decoration: underline;
        }

        .breadcrumb-item.active {
            color: var(--gray-900);
            font-weight: 600;
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
                    <li class="breadcrumb-item">
                        <a href="{{ route('asistencia.index') }}">
                            <i class="fas fa-clipboard-check"></i>
                            <span>Asistencias</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <i class="fas fa-cogs"></i>
                        <span>Administración</span>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Page Header -->
        <div class="page-header">
            <div class="container-clean">
                <div class="page-title">Administración de Asistencias</div>
                <div class="page-subtitle">Gestión completa de registros de asistencia del sistema</div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="space-y-8">
            <!-- Statistics Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon total">
                        <i class="fas fa-list"></i>
                    </div>
                    <div class="stat-value">{{ number_format($estadisticas['total']) }}</div>
                    <div class="stat-label">Total Registros</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon presentes">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-value">{{ number_format($estadisticas['presentes']) }}</div>
                    <div class="stat-label">Presentes</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon ausentes">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div class="stat-value">{{ number_format($estadisticas['ausentes']) }}</div>
                    <div class="stat-label">Ausentes</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon tardanzas">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-value">{{ number_format($estadisticas['tardanzas']) }}</div>
                    <div class="stat-label">Tardanzas</div>
                </div>
            </div>

            <!-- Filters Section -->
            <div class="filter-section">
                <form method="GET" action="{{ route('asistencia.admin-index') }}">
                    <div class="filter-grid">
                        <div class="filter-group">
                            <label class="filter-label">
                                <i class="fas fa-user-tie"></i>
                                <span>Profesor</span>
                            </label>
                            <select name="profesor_id" id="profesor_id" class="filter-select">
                                <option value="">Todos los profesores</option>
                                @foreach($profesores as $profesor)
                                    <option value="{{ $profesor->profesor_id }}" {{ ($filtros['profesor_id'] ?? '') == $profesor->profesor_id ? 'selected' : '' }}>
                                        {{ $profesor->nombres }} {{ $profesor->apellidos }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="filter-group">
                            <label class="filter-label">
                                <i class="fas fa-graduation-cap"></i>
                                <span>Curso</span>
                            </label>
                            <select name="curso_id" id="curso_id" class="filter-select">
                                <option value="">Todos los cursos</option>
                                @foreach($cursos as $curso)
                                    <option value="{{ $curso->curso_id }}" {{ ($filtros['curso_id'] ?? '') == $curso->curso_id ? 'selected' : '' }}>
                                        {{ $curso->grado->nombre }} - {{ $curso->seccion->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="filter-group">
                            <label class="filter-label">
                                <i class="fas fa-book"></i>
                                <span>Asignatura</span>
                            </label>
                            <select name="asignatura_id" id="asignatura_id" class="filter-select">
                                <option value="">Todas las asignaturas</option>
                                @foreach($asignaturas as $asignatura)
                                    <option value="{{ $asignatura->asignatura_id }}" {{ ($filtros['asignatura_id'] ?? '') == $asignatura->asignatura_id ? 'selected' : '' }}>
                                        {{ $asignatura->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="filter-group">
                            <label class="filter-label">
                                <i class="fas fa-check-circle"></i>
                                <span>Tipo Asistencia</span>
                            </label>
                            <select name="tipo_asistencia_id" id="tipo_asistencia_id" class="filter-select">
                                <option value="">Todos los tipos</option>
                                @foreach($tiposAsistencia as $tipo)
                                    <option value="{{ $tipo->tipo_asistencia_id }}" {{ ($filtros['tipo_asistencia_id'] ?? '') == $tipo->tipo_asistencia_id ? 'selected' : '' }}>
                                        {{ $tipo->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="filter-group">
                            <label class="filter-label">
                                <i class="fas fa-calendar-alt"></i>
                                <span>Fecha Desde</span>
                            </label>
                            <input type="date" name="fecha_desde" id="fecha_desde" class="filter-input"
                                   value="{{ $filtros['fecha_desde'] ?? '' }}">
                        </div>

                        <div class="filter-group">
                            <label class="filter-label">
                                <i class="fas fa-calendar-alt"></i>
                                <span>Fecha Hasta</span>
                            </label>
                            <input type="date" name="fecha_hasta" id="fecha_hasta" class="filter-input"
                                   value="{{ $filtros['fecha_hasta'] ?? '' }}">
                        </div>

                        <div class="filter-group">
                            <label class="filter-label">
                                <i class="fas fa-search"></i>
                                <span>Buscar Estudiante</span>
                            </label>
                            <input type="text" name="buscar" id="buscar" class="filter-input"
                                   placeholder="Nombre, apellido, DNI..." value="{{ $filtros['buscar'] ?? '' }}">
                        </div>

                        <div class="filter-group">
                            <label class="filter-label">
                                <i class="fas fa-sort"></i>
                                <span>Ordenar por</span>
                            </label>
                            <select name="ordenar" id="ordenar" class="filter-select">
                                <option value="fecha" {{ ($ordenarPor ?? 'fecha') == 'fecha' ? 'selected' : '' }}>Fecha</option>
                                <option value="estudiante" {{ ($ordenarPor ?? 'fecha') == 'estudiante' ? 'selected' : '' }}>Estudiante</option>
                                <option value="profesor" {{ ($ordenarPor ?? 'fecha') == 'profesor' ? 'selected' : '' }}>Profesor</option>
                                <option value="asignatura" {{ ($ordenarPor ?? 'fecha') == 'asignatura' ? 'selected' : '' }}>Asignatura</option>
                            </select>
                        </div>
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1.5rem; flex-wrap: wrap; gap: 1rem;">
                        <div style="display: flex; gap: 0.75rem; align-items: center;">
                            <button type="submit" class="btn btn-primary btn-clean">
                                <i class="fas fa-search"></i>
                                <span>Filtrar</span>
                            </button>
                            <a href="{{ route('asistencia.admin-index') }}" class="btn btn-outline btn-clean">
                                <i class="fas fa-times"></i>
                                <span>Limpiar Filtros</span>
                            </a>
                        </div>
                        <button type="button" class="btn btn-info btn-clean" onclick="exportarPDF()">
                            <i class="fas fa-file-pdf"></i>
                            <span>Exportar PDF</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Data Table -->
            <div class="table-container">
                <div class="table-header">
                    <div class="table-title">
                        <i class="fas fa-table"></i>
                        <span>Registros de Asistencia ({{ $asistencias->total() }})</span>
                    </div>
                </div>

                @if($asistencias->isEmpty())
                    <div class="empty-state">
                        <i class="fas fa-inbox empty-icon"></i>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">No hay registros de asistencia para mostrar</h3>
                        <p class="text-gray-600 mb-4">Prueba cambiando los filtros de búsqueda</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table-modern">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Estudiante</th>
                                    <th>Asignatura</th>
                                    <th>Profesor</th>
                                    <th>Curso</th>
                                    <th>Fecha</th>
                                    <th>Asistencia</th>
                                    <th>Estado</th>
                                    <th>Registro</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($asistencias as $index => $asistencia)
                                    <tr>
                                        <td>{{ $asistencias->firstItem() + $index }}</td>

                                        <td>
                                            <div>
                                                <div class="font-semibold text-gray-900">{{ $asistencia->matricula->estudiante->nombres }}</div>
                                                <div class="text-sm text-gray-600">{{ $asistencia->matricula->estudiante->apellidos }}</div>
                                                <div class="text-sm text-gray-500">DNI: {{ $asistencia->matricula->estudiante->dni }}</div>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="font-semibold text-gray-900">{{ $asistencia->cursoAsignatura->asignatura->nombre }}</div>
                                        </td>

                                        <td>
                                            <div>
                                                <div class="font-semibold text-gray-900">{{ $asistencia->cursoAsignatura->profesor->nombres }}</div>
                                                <div class="text-sm text-gray-600">{{ $asistencia->cursoAsignatura->profesor->apellidos }}</div>
                                            </div>
                                        </td>

                                        <td>
                                            <div>
                                                <div class="font-semibold text-gray-900">{{ $asistencia->matricula->curso->grado->nombre }}</div>
                                                <div class="text-sm text-gray-600">{{ $asistencia->matricula->curso->seccion->nombre }}</div>
                                            </div>
                                        </td>

                                        <td>
                                            <div>
                                                <div class="font-semibold text-gray-900">{{ $asistencia->fecha ? $asistencia->fecha->format('d/m/Y') : 'N/A' }}</div>
                                                <div class="text-sm text-gray-600">{{ $asistencia->fecha ? $asistencia->fecha->format('l') : '' }}</div>
                                            </div>
                                        </td>

                                        <td>
                                            <span class="status-badge {{ $asistencia->tipoAsistencia->codigo == 'A' ? 'presente' : ($asistencia->tipoAsistencia->codigo == 'F' ? 'ausente' : ($asistencia->tipoAsistencia->codigo == 'T' ? 'tardanza' : 'justificada')) }}">
                                                <i class="fas fa-{{ $asistencia->tipoAsistencia->codigo == 'A' ? 'check' : ($asistencia->tipoAsistencia->codigo == 'F' ? 'times' : ($asistencia->tipoAsistencia->codigo == 'T' ? 'clock' : 'file-alt')) }}"></i>
                                                {{ $asistencia->tipoAsistencia->nombre }}
                                            </span>
                                        </td>

                                        <td>
                                            <span class="status-badge {{ $asistencia->estado == 'Registrada' ? 'registrada' : 'pendiente' }}">
                                                {{ $asistencia->estado }}
                                            </span>
                                        </td>

                                        <td>
                                            <div>
                                                <div class="text-sm text-gray-600">{{ $asistencia->hora_registro ? $asistencia->hora_registro->format('H:i') : 'N/A' }}</div>
                                                <div class="text-sm text-gray-500">{{ $asistencia->hora_registro ? $asistencia->hora_registro->format('d/m/Y') : '' }}</div>
                                            </div>
                                        </td>

                                        <td>
                                            <div style="display: flex; gap: 0.5rem;">
                                                <a href="{{ route('asistencia.detalle-estudiante', $asistencia->matricula_id) }}"
                                                    class="btn btn-outline btn-clean"
                                                    title="Ver detalle del estudiante"
                                                    target="_blank">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($asistencia->justificacion)
                                                    <button class="btn btn-outline btn-clean"
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

                    <!-- Pagination -->
                    <div class="pagination-container">
                        {{ $asistencias->appends(request()->query())->links() }}
                    </div>
                @endif
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
        });

        function verJustificacion(justificacion) {
            document.getElementById('justificacionText').textContent = justificacion;
            $('#justificacionModal').modal('show');
        }



        function exportarPDF() {
            // Crear URL con los parámetros actuales
            const urlParams = new URLSearchParams(window.location.search);

            // Redirigir a la URL de exportación
            window.location.href = '{{ route("asistencia.exportar-pdf-admin") }}?' + urlParams.toString();
        }

        // Auto-submit del formulario cuando cambian los filtros principales
        document.getElementById('tipo_asistencia_id').addEventListener('change', function() {
            this.closest('form').submit();
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
    </script>
@endsection
