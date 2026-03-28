@extends('cplantilla.bprincipal')

@section('titulo', 'Registrar Asistencia')

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

        /* Class Info Card */
        .class-info-card {
            background: var(--primary-light);
            border: 1px solid var(--primary);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .class-info-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
        }

        .class-info-item:last-child {
            margin-bottom: 0;
        }

        .class-info-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .class-info-content strong {
            color: var(--gray-900);
            display: block;
            font-weight: 600;
        }

        .class-info-content small {
            color: var(--gray-600);
            font-weight: 500;
        }

        /* Statistics Mini Cards */
        .stats-mini {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.25rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }

        .stats-mini:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow);
        }

        /* Student Row Styling */
        .student-row {
            transition: var(--transition);
            border-left: 3px solid transparent;
            border-radius: 6px;
            margin-bottom: 0.5rem;
            padding: 0.75rem;
        }

        .student-row:hover {
            background: var(--gray-50);
            box-shadow: var(--shadow-sm);
        }

        .student-row.presente {
            border-left-color: var(--success);
            background: var(--success-light);
        }

        .student-row.ausente {
            border-left-color: var(--danger);
            background: var(--danger-light);
        }

        .student-row.tardanza {
            border-left-color: var(--warning);
            background: var(--warning-light);
        }

        .student-row.justificada {
            border-left-color: var(--gray-500);
            background: var(--gray-100);
        }

        .student-row.justificada-admin {
            border-left-color: var(--gray-500);
            background: var(--gray-100);
            position: relative;
        }

        .student-row.justificada-admin::after {
            content: 'Justificación Administrativa';
            position: absolute;
            top: 5px;
            right: 5px;
            background: var(--gray-600);
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        /* Attendance Type Buttons */
        .tipo-btn {
            border-radius: 6px;
            width: 44px;
            height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 2px solid;
            transition: var(--transition);
            cursor: pointer;
            font-size: 1rem;
            background: white;
            position: relative;
        }

        .tipo-btn:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow);
        }

        .tipo-btn.active {
            transform: scale(1.05);
            font-weight: 600;
            box-shadow: var(--shadow);
        }

        .tipo-btn.blocked {
            opacity: 0.6;
            cursor: not-allowed;
            pointer-events: none;
            filter: grayscale(50%);
        }

        .tipo-btn.blocked.active {
            background-color: var(--gray-500) !important;
            color: white !important;
            border-color: var(--gray-500) !important;
        }

        /* History Badges */
        .historial-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            border-radius: 4px;
            font-size: 0.7rem;
            font-weight: 600;
            color: white;
            margin: 0 1px;
            transition: var(--transition);
        }

        .historial-badge:hover {
            transform: scale(1.1);
        }

        /* Student Avatar */
        .student-avatar {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1rem;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }

        .student-avatar:hover {
            transform: scale(1.05);
        }

        /* Table Enhancements */
        .table-responsive {
            max-height: calc(100vh - 400px);
            overflow-y: auto;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
        }

        .table-modern {
            margin-bottom: 0;
        }

        .table-modern thead th {
            background: var(--primary);
            color: white;
            border: none;
            padding: 1rem 0.75rem;
            font-weight: 600;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .table-modern tbody tr {
            transition: var(--transition);
        }

        .table-modern tbody tr:hover {
            background: var(--gray-50);
        }

        /* Quick Actions Bar */
        .quick-actions-bar {
            background: white;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
            border: 1px solid var(--gray-200);
        }

        .quick-action-group {
            display: flex;
            gap: 0.5rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .bulk-action-btn {
            border-radius: 6px;
            padding: 0.5rem 1rem;
            font-weight: 500;
            transition: var(--transition);
            border: 2px solid;
        }

        .bulk-action-btn:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow);
        }

        /* Search Input */
        .search-input-modern {
            position: relative;
        }

        .search-input-modern input {
            border: 1px solid var(--gray-300);
            border-radius: 20px;
            padding: 0.5rem 1rem 0.5rem 2.5rem;
            transition: var(--transition);
            font-size: 0.875rem;
        }

        .search-input-modern input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .search-input-modern::before {
            content: '\f002';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400);
            z-index: 1;
        }

        /* Floating Save Button */
        .btn-guardar-flotante {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            z-index: 1000;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: var(--success);
            border: none;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            box-shadow: var(--shadow-lg);
            transition: var(--transition);
            cursor: pointer;
        }

        .btn-guardar-flotante:hover {
            transform: scale(1.1);
            box-shadow: 0 10px 25px rgba(22, 163, 74, 0.3);
        }

        .btn-guardar-flotante:active {
            transform: scale(0.95);
        }

        /* Action Buttons */
        .action-buttons-modern {
            background: white;
            border-radius: var(--border-radius);
            padding: 2rem;
            box-shadow: var(--shadow);
            border: 1px solid var(--gray-200);
            margin-top: 2rem;
        }

        .btn-modern-primary {
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            font-size: 1rem;
            transition: var(--transition);
            box-shadow: var(--shadow);
        }

        .btn-modern-primary:hover {
            background: #1d4ed8;
            transform: translateY(-1px);
            box-shadow: var(--shadow-lg);
        }

        .btn-modern-secondary {
            background: white;
            color: var(--gray-700);
            border: 1px solid var(--gray-300);
            border-radius: 8px;
            padding: 0.75rem 2rem;
            font-weight: 500;
            font-size: 1rem;
            transition: var(--transition);
        }

        .btn-modern-secondary:hover {
            background: var(--gray-50);
            border-color: var(--gray-400);
            transform: translateY(-1px);
            box-shadow: var(--shadow);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .quick-action-group {
                justify-content: center;
            }

            .stats-mini {
                font-size: 0.8rem;
                padding: 0.5rem 1rem;
            }

            .class-info-item {
                flex-direction: column;
                text-align: center;
                gap: 0.5rem;
            }
        }

        @media (max-width: 576px) {
            .table-responsive {
                max-height: calc(100vh - 500px);
            }

            .btn-guardar-flotante {
                bottom: 1.5rem;
                right: 1.5rem;
                width: 48px;
                height: 48px;
                font-size: 1rem;
            }
        }

        /* Loading States */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            backdrop-filter: blur(5px);
        }

        .loading-spinner {
            width: 40px;
            height: 40px;
            border: 3px solid var(--gray-300);
            border-left: 3px solid var(--primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Accessibility */
        .tipo-btn:focus {
            outline: 2px solid var(--primary);
            outline-offset: 2px;
        }

        .bulk-action-btn:focus {
            outline: 2px solid var(--primary);
            outline-offset: 2px;
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
                        <i class="fas fa-clipboard-check"></i>&nbsp;Registro de Asistencia
                        <div class="float-right"><i class="fas fa-chevron-down"></i></div>
                    </button>
                </div>
                <div class="collapse show" id="collapseAsistencia">
                    <div class="card card-body rounded-0 border-0 pt-0"
                        style="padding-left:0.966666666rem;padding-right:0.9033333333333333rem;">
                        <div class="row margen-movil" style="padding:20px;">
                            <div class="col-12">

                                <!-- Información de la clase -->
                                <div class="class-info-card">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <div class="class-info-icon">
                                            <i class="fas fa-chalkboard-teacher"></i>
                                        </div>
                                        <div>
                                            <h5 class="mb-0">Información de la Clase</h5>
                                            <small class="text-muted">Detalles de la sesión actual</small>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="class-info-item">
                                                <div class="class-info-icon">
                                                    <i class="fas fa-book"></i>
                                                </div>
                                                <div class="class-info-content">
                                                    <strong>{{ $cursoAsignatura->asignatura->nombre }}</strong>
                                                    <small>Asignatura</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="class-info-item">
                                                <div class="class-info-icon">
                                                    <i class="fas fa-graduation-cap"></i>
                                                </div>
                                                <div class="class-info-content">
                                                    <strong>{{ $cursoAsignatura->curso->grado->nombre }} - {{ $cursoAsignatura->curso->seccion->nombre }}</strong>
                                                    <small>Curso y Sección</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="class-info-item">
                                                <div class="class-info-icon">
                                                    <i class="fas fa-calendar-day"></i>
                                                </div>
                                                <div class="class-info-content">
                                                    <strong>{{ Carbon\Carbon::parse($fechaStr)->isoFormat('D [de] MMMM, YYYY') }}</strong>
                                                    <small>{{ Carbon\Carbon::parse($fechaStr)->isoFormat('dddd') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Estadísticas en tiempo real -->
                                <div class="modern-card">
                                    <div class="modern-card-header">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="session-avatar" style="background: rgba(255, 255, 255, 0.2);">
                                                <i class="fas fa-chart-bar"></i>
                                            </div>
                                            <div>
                                                <h5 class="mb-0" style="color: white; font-weight: 700;">
                                                    Estadísticas en Tiempo Real
                                                </h5>
                                                <small style="color: rgba(255, 255, 255, 0.8);">
                                                    Actualización automática del registro
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modern-card-body">
                                        <div class="d-flex gap-3 flex-wrap justify-content-center">
                                            <div class="stats-mini" style="background: var(--secondary-light); color: var(--secondary-color);">
                                                <i class="fas fa-users me-2"></i>
                                                <div>
                                                    <div class="fw-bold" id="total-estudiantes">{{ $matriculas->count() }}</div>
                                                    <small>Total</small>
                                                </div>
                                            </div>
                                            <div class="stats-mini" style="background: var(--success-color); color: white;">
                                                <i class="fas fa-check me-2"></i>
                                                <div>
                                                    <div class="fw-bold" id="count-presentes">0</div>
                                                    <small>Presentes</small>
                                                </div>
                                            </div>
                                            <div class="stats-mini" style="background: var(--danger-color); color: white;">
                                                <i class="fas fa-times me-2"></i>
                                                <div>
                                                    <div class="fw-bold" id="count-ausentes">0</div>
                                                    <small>Ausentes</small>
                                                </div>
                                            </div>
                                            <div class="stats-mini" style="background: var(--warning-color); color: white;">
                                                <i class="fas fa-clock me-2"></i>
                                                <div>
                                                    <div class="fw-bold" id="count-tardanzas">0</div>
                                                    <small>Tardanzas</small>
                                                </div>
                                            </div>
                                            <div class="stats-mini" style="background: var(--secondary-color); color: white;">
                                                <i class="fas fa-file-alt me-2"></i>
                                                <div>
                                                    <div class="fw-bold" id="count-justificadas">0</div>
                                                    <small>Justificadas</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

        <form id="form-asistencia" action="{{ route('asistencia.guardar-asignatura') }}" method="POST">
            @csrf
            <input type="hidden" name="curso_asignatura_id" value="{{ $cursoAsignatura->curso_asignatura_id }}">
            <input type="hidden" name="fecha" value="{{ $fechaStr }}">

            <!-- Controles de registro masivo -->
            <div class="quick-actions-bar">
                <div class="quick-action-group">
                    <h6 class="mb-3" style="color: var(--dark-color); font-weight: 700;">
                        <i class="fas fa-bolt me-2" style="color: var(--warning-color);"></i>
                        Acciones Rápidas
                    </h6>
                    @foreach ($tiposAsistencia as $tipo)
                        <button type="button"
                            class="bulk-action-btn"
                            style="border-color: {{ getColorTipo($tipo->codigo) }}; color: {{ getColorTipo($tipo->codigo) }};"
                            onclick="marcarTodos('{{ $tipo->tipo_asistencia_id }}', '{{ $tipo->codigo }}')"
                            title="Marcar todos como {{ $tipo->nombre }}">
                            <i class="fas fa-{{ getIcono($tipo->codigo) }} me-2"></i>
                            Todos {{ $tipo->nombre }}
                        </button>
                    @endforeach
                    <button type="button" class="bulk-action-btn"
                        style="border-color: var(--secondary-color); color: var(--secondary-color);"
                        onclick="limpiarTodos()"
                        title="Limpiar todas las selecciones">
                        <i class="fas fa-eraser me-2"></i> Limpiar Todo
                    </button>
                </div>

                <div class="search-input-modern" style="max-width: 350px;">
                    <input type="text" id="search-estudiante"
                        placeholder="Buscar estudiante por nombre..."
                        aria-label="Buscar estudiante">
                </div>
            </div>

            <!-- Tabla de Estudiantes -->
            <div class="modern-card">
                <div class="modern-card-header">
                    <div class="d-flex align-items-center gap-3">
                        <div class="session-avatar" style="background: rgba(255, 255, 255, 0.2);">
                            <i class="fas fa-users"></i>
                        </div>
                        <div>
                            <h5 class="mb-0" style="color: white; font-weight: 700;">
                                Lista de Estudiantes
                            </h5>
                            <small style="color: rgba(255, 255, 255, 0.8);">
                                Registra la asistencia de cada estudiante
                            </small>
                        </div>
                    </div>
                </div>
                <div class="modern-card-body p-0">
                    <div class="table-responsive">
                        <table class="table-modern table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 60px;">#</th>
                                    <th style="width: 80px;">Avatar</th>
                                    <th>Estudiante</th>
                                    <th style="width: 120px;">DNI</th>
                                    <th style="width: 200px;" class="text-center">Historial</th>
                                    <th style="width: 280px;" class="text-center">Asistencia</th>
                                    <th style="width: 250px;">Observación</th>
                                    <th style="width: 80px;" class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="estudiantes-container">
                                @foreach ($matriculas as $i => $matricula)
                                    @php
                                        $asistencia = $asistencias->get($matricula->matricula_id);
                                        $historial = $historialAsistencias->get($matricula->matricula_id) ?? collect();
                                        // Verificar si tiene justificación aprobada administrativa
                                        $tieneJustificacionAprobada = \App\Models\JustificacionAsistencia::where('matricula_id', $matricula->matricula_id)
                                            ->where('fecha', $fechaStr)
                                            ->where('estado', 'aprobado')
                                            ->exists();
                                    @endphp
                                    <tr class="estudiante-row {{ $asistencia ? getTipoClase(optional($asistencia->tipoAsistencia)->codigo) : '' }} {{ $tieneJustificacionAprobada ? 'justificada-admin' : '' }}"
                                        id="row-{{ $matricula->matricula_id }}"
                                        data-nombre="{{ strtolower($matricula->estudiante->nombres . ' ' . $matricula->estudiante->apellidos) }}"
                                        data-matricula="{{ $matricula->matricula_id }}"
                                        data-justificada-admin="{{ $tieneJustificacionAprobada ? 'true' : 'false' }}">

                                        <td class="align-middle">
                                            <span class="fw-bold text-muted">{{ $i + 1 }}</span>
                                        </td>

                                        <td class="align-middle">
                                            <div class="student-avatar"
                                                style="background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));">
                                                {{ substr($matricula->estudiante->nombres, 0, 1) }}{{ substr($matricula->estudiante->apellidos, 0, 1) }}
                                            </div>
                                        </td>

                                        <td class="align-middle">
                                            <div>
                                                <strong style="color: var(--dark-color);">{{ $matricula->estudiante->nombres }} {{ $matricula->estudiante->apellidos }}</strong>
                                            </div>
                                        </td>

                                        <td class="align-middle">
                                            <small class="text-muted fw-bold">{{ $matricula->estudiante->dni }}</small>
                                        </td>

                                        <td class="align-middle text-center">
                                            <div class="d-flex gap-1 justify-content-center flex-wrap">
                                                @foreach ($historial->take(5) as $hist)
                                                    <span class="historial-badge"
                                                        style="background-color: {{ getColorTipo(optional($hist->tipoAsistencia)->codigo) }};"
                                                        title="{{ $hist->fecha }}: {{ optional($hist->tipoAsistencia)->nombre }}">
                                                        {{ optional($hist->tipoAsistencia)->codigo }}
                                                    </span>
                                                @endforeach
                                                @if($historial->isEmpty())
                                                    <small class="text-muted">Sin registros</small>
                                                @endif
                                            </div>
                                        </td>

                                        <td class="align-middle">
                                            <div class="d-flex gap-2 justify-content-center flex-wrap">
                                                @foreach ($tiposAsistencia as $tipo)
                                                    @php
                                                        $isJustificada = $tipo->codigo === 'J';
                                                        $isChecked = ($asistencia && $asistencia->tipo_asistencia_id == $tipo->tipo_asistencia_id) ||
                                                                   ($tieneJustificacionAprobada && $isJustificada);
                                                        $isBlocked = $tieneJustificacionAprobada && !$isJustificada;
                                                    @endphp
                                                    <label class="tipo-btn {{ $isBlocked ? 'blocked' : '' }} {{ $isChecked ? 'active' : '' }}"
                                                        style="border-color: {{ getColorTipo($tipo->codigo) }};
                                                               {{ $isChecked ? 'background-color: ' . getColorTipo($tipo->codigo) . '; color: #fff;' : 'color: ' . getColorTipo($tipo->codigo) }};
                                                               {{ $isBlocked ? 'cursor: not-allowed; opacity: 0.6;' : '' }}"
                                                        title="{{ $tipo->nombre }} {{ $tieneJustificacionAprobada && $isJustificada ? '(Bloqueado - Justificación Administrativa)' : '' }}">
                                                        <input type="radio"
                                                            name="asistencias[{{ $matricula->matricula_id }}][tipo_asistencia_id]"
                                                            value="{{ $tipo->tipo_asistencia_id }}"
                                                            class="tipo-radio d-none"
                                                            data-matricula="{{ $matricula->matricula_id }}"
                                                            data-codigo="{{ $tipo->codigo }}"
                                                            {{ $isChecked ? 'checked' : '' }}
                                                            {{ $isBlocked ? 'disabled' : '' }}>
                                                        <i class="fas fa-{{ getIcono($tipo->codigo) }}"></i>
                                                    </label>
                                                @endforeach
                                                <input type="hidden"
                                                    name="asistencias[{{ $matricula->matricula_id }}][matricula_id]"
                                                    value="{{ $matricula->matricula_id }}">
                                            </div>
                                        </td>

                                        <td class="align-middle">
                                            <input type="text"
                                                name="asistencias[{{ $matricula->matricula_id }}][justificacion]"
                                                class="form-control form-control-sm justificacion-input"
                                                placeholder="Observación..."
                                                value="{{ $asistencia->justificacion ?? '' }}"
                                                style="display: {{ $asistencia && in_array(optional($asistencia->tipoAsistencia)->codigo, ['F', 'T', 'J']) ? 'block' : 'none' }}; border: 2px solid var(--primary-color);">
                                        </td>

                                        <td class="align-middle text-center">
                                            <a href="{{ route('asistencia.detalle-estudiante', $matricula->matricula_id) }}"
                                                class="btn btn-outline-modern btn-modern"
                                                target="_blank"
                                                title="Ver historial completo">
                                                <i class="fas fa-chart-line"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="action-buttons-modern">
                <div class="d-flex justify-content-center gap-4 flex-wrap">
                    <button type="submit" form="form-asistencia"
                        class="btn-modern-primary position-relative overflow-hidden">
                        <i class="fas fa-save me-2"></i>
                        <span>Guardar Asistencias</span>
                        <div class="position-absolute top-0 start-100 translate-middle" style="width: 20px; height: 20px; background: rgba(255,255,255,0.3); border-radius: 50%; animation: ripple 1.5s infinite;"></div>
                    </button>
                    <a href="{{ route('asistencia.index') }}"
                        class="btn-modern-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Volver al Dashboard
                    </a>
                </div>
                <div class="text-center mt-3">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Los cambios se guardarán automáticamente al enviar el formulario
                    </small>
                </div>
            </div>

            <!-- Floating Save Button -->
            <button type="submit" form="form-asistencia" class="btn-guardar-flotante" title="Guardar Asistencias">
                <i class="fas fa-save"></i>
            </button>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        </form>
    </div>

    <style>
        .form-control {
            border: 1px solid #DAA520;
        }
    </style>
@endsection

@push('js-extra')
    <script>
        // IMPORTANTE: Usar jQuery en lugar de vanilla JS para compatibilidad
        $(document).ready(function() {

            // Búsqueda en tiempo real
            $('#search-estudiante').on('input', function() {
                var searchTerm = $(this).val().toLowerCase();
                $('.estudiante-row').each(function() {
                    var nombre = $(this).data('nombre') || '';
                    if (nombre.indexOf(searchTerm) > -1) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

            // Función para actualizar la selección visual
            function seleccionarTipo(matriculaId, tipoId, codigo) {
                var $row = $('#row-' + matriculaId);
                if (!$row.length) return;

                // Actualizar todos los labels del row
                $row.find('.tipo-btn').each(function() {
                    var $label = $(this);
                    var $input = $label.find('input[type="radio"]');
                    var color = $label.data('color');

                    if ($input.val() == tipoId) {
                        // Este es el seleccionado
                        $label.addClass('active');
                        $label.css({
                            'backgroundColor': color,
                            'color': '#fff',
                            'borderColor': color
                        });
                    } else {
                        // Los demás
                        $label.removeClass('active');
                        $label.css({
                            'backgroundColor': '#fff',
                            'color': color,
                            'borderColor': color
                        });
                    }
                });

                // Actualizar clase de la fila
                $row.removeClass('presente ausente tardanza justificada');
                var claseNueva = getTipoClase(codigo);
                if (claseNueva) {
                    $row.addClass(claseNueva);
                }

                // Mostrar/ocultar campo de observación
                var $justificacionInput = $row.find('.justificacion-input');
                if (['F', 'T', 'J'].indexOf(codigo) > -1) {
                    $justificacionInput.show();
                } else {
                    $justificacionInput.hide();
                }

                actualizarContadores();
            }

            // Event listeners para los radio buttons usando delegación
            $(document).on('change', '.tipo-radio', function() {
                var matriculaId = $(this).data('matricula');
                var tipoId = $(this).val();
                var codigo = $(this).data('codigo');
                seleccionarTipo(matriculaId, tipoId, codigo);
            });

            // Click en labels para marcar radios
            $(document).on('click', '.tipo-btn', function(e) {
                e.preventDefault();
                // No permitir clicks en botones bloqueados
                if ($(this).hasClass('blocked')) {
                    return;
                }
                var $radio = $(this).find('input[type="radio"]');
                $radio.prop('checked', true).trigger('change');
            });

            // Inicializar los que ya están checked
            $('.tipo-radio:checked').each(function() {
                var matriculaId = $(this).data('matricula');
                var tipoId = $(this).val();
                var codigo = $(this).data('codigo');
                seleccionarTipo(matriculaId, tipoId, codigo);
            });

            // Marcar todos con un tipo específico
            window.marcarTodos = function(tipoId, codigo) {
                $('.estudiante-row').each(function() {
                    // Excluir filas justificadas administrativamente
                    if ($(this).attr('data-justificada-admin') === 'true') {
                        return; // Continuar con la siguiente fila
                    }

                    var matriculaId = $(this).data('matricula');
                    var $radios = $(this).find('.tipo-radio');

                    $radios.each(function() {
                        if ($(this).val() == tipoId) {
                            $(this).prop('checked', true);
                            seleccionarTipo(matriculaId, tipoId, codigo);
                        }
                    });
                });
            };

            // Limpiar todas las selecciones
            window.limpiarTodos = function() {
                var estudiantesEditables = $('.estudiante-row').filter(function() {
                    return $(this).attr('data-justificada-admin') !== 'true';
                }).length;

                if (estudiantesEditables === 0) {
                    swal("Información", "No hay asistencias que puedan ser limpiadas (todas están justificadas administrativamente)", {
                        icon: "info",
                        buttons: {
                            confirm: {
                                className: 'btn btn-info'
                            }
                        },
                    });
                    return;
                }

                swal({
                    title: "¿Limpiar todas las selecciones?",
                    text: "Esta acción limpiará todas las asistencias que no estén justificadas administrativamente (" + estudiantesEditables + " estudiantes)",
                    icon: "warning",
                    buttons: {
                        cancel: {
                            text: "Cancelar",
                            value: false,
                            visible: true,
                            className: "btn btn-secondary"
                        },
                        confirm: {
                            text: "Sí, limpiar",
                            value: true,
                            visible: true,
                            className: "btn btn-warning"
                        }
                    },
                    dangerMode: true,
                }).then((willClear) => {
                    if (willClear) {
                        $('.estudiante-row').each(function() {
                            // Excluir filas justificadas administrativamente
                            if ($(this).attr('data-justificada-admin') === 'true') {
                                return; // Continuar con la siguiente fila
                            }

                            var $row = $(this);

                            // Desmarcar radios
                            $row.find('.tipo-radio').prop('checked', false);

                            // Resetear estilos de labels
                            $row.find('.tipo-btn').each(function() {
                                var $label = $(this);
                                var color = $label.data('color');
                                $label.removeClass('active');
                                $label.css({
                                    'backgroundColor': '#fff',
                                    'color': color,
                                    'borderColor': color
                                });
                            });

                            // Limpiar clase de fila
                            $row.removeClass('presente ausente tardanza justificada');

                            // Ocultar y limpiar justificación
                            var $justificacionInput = $row.find('.justificacion-input');
                            $justificacionInput.hide().val('');
                        });

                        actualizarContadores();
                    }
                });
            };

            // Actualizar contadores
            function actualizarContadores() {
                var presentes = $('.estudiante-row.presente').length;
                var ausentes = $('.estudiante-row.ausente').length;
                var tardanzas = $('.estudiante-row.tardanza').length;
                var justificadas = $('.estudiante-row.justificada').length;

                $('#count-presentes').text(presentes);
                $('#count-ausentes').text(ausentes);
                $('#count-tardanzas').text(tardanzas);
                $('#count-justificadas').text(justificadas);
            }

            // Helper function
            function getTipoClase(codigo) {
                var map = {
                    'A': 'presente',
                    'F': 'ausente',
                    'T': 'tardanza',
                    'J': 'justificada'
                };
                return map[codigo] || '';
            }

            // Validación del formulario
            $('#form-asistencia').on('submit', function(e) {
                e.preventDefault();

                var seleccionados = $('.tipo-radio:checked').length;
                if (seleccionados === 0) {
                    swal("Error", "Debe registrar al menos una asistencia", {
                        icon: "error",
                        buttons: {
                            confirm: {
                                className: 'btn btn-danger'
                            }
                        },
                    });
                    return false;
                }

                // Modal de confirmación elegante
                var presentes = $('#count-presentes').text();
                var ausentes = $('#count-ausentes').text();
                var tardanzas = $('#count-tardanzas').text();
                var justificadas = $('#count-justificadas').text();

                var mensaje = '<div class="text-center">';
                mensaje += '<h5>¿Confirmar registro de asistencias?</h5>';
                mensaje += '<p class="mb-2">Total de estudiantes: ' + seleccionados + '</p>';
                mensaje += '<div class="row text-center">';
                mensaje += '<div class="col-3"><i class="fas fa-check text-success"></i><br><small>Presentes: ' + presentes + '</small></div>';
                mensaje += '<div class="col-3"><i class="fas fa-times text-danger"></i><br><small>Ausentes: ' + ausentes + '</small></div>';
                mensaje += '<div class="col-3"><i class="fas fa-clock text-warning"></i><br><small>Tardanzas: ' + tardanzas + '</small></div>';
                mensaje += '<div class="col-3"><i class="fas fa-file-alt text-secondary"></i><br><small>Justificadas: ' + justificadas + '</small></div>';
                mensaje += '</div></div>';

                swal({
                    title: "Confirmar Registro",
                    content: {
                        element: "div",
                        attributes: {
                            innerHTML: mensaje
                        }
                    },
                    icon: "question",
                    buttons: {
                        cancel: {
                            text: "Cancelar",
                            value: false,
                            visible: true,
                            className: "btn btn-secondary"
                        },
                        confirm: {
                            text: "Guardar Asistencias",
                            value: true,
                            visible: true,
                            className: "btn btn-primary"
                        }
                    },
                    dangerMode: false,
                }).then((willSave) => {
                    if (willSave) {
                        // Mostrar loading
                        swal({
                            title: "Guardando...",
                            text: "Por favor espere mientras se procesan las asistencias",
                            icon: "info",
                            buttons: false,
                            closeOnEsc: false,
                            closeOnClickOutside: false
                        });

                        // Enviar el formulario
                        $('#form-asistencia')[0].submit();
                    }
                });

                return false;
            });

            // Inicializar contadores
            actualizarContadores();
        });
    </script>

    @if (session('success'))
        <script>
            $(document).ready(function() {
                swal("Éxito", "{{ session('success') }}", {
                    icon: "success",
                    buttons: {
                        confirm: {
                            className: 'btn btn-success'
                        }
                    },
                });
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            $(document).ready(function() {
                swal("Error", "{{ session('error') }}", {
                    icon: "error",
                    buttons: {
                        confirm: {
                            className: 'btn btn-danger'
                        }
                    },
                });
            });
        </script>
    @endif
@endpush

@php
    function getColorBootstrap($codigo)
    {
        return match ($codigo) {
            'A' => 'success',
            'F' => 'danger',
            'T' => 'warning',
            'J' => 'secondary',
            default => 'primary',
        };
    }

    function getIcono($codigo)
    {
        return match ($codigo) {
            'A' => 'check',
            'F' => 'times',
            'T' => 'clock',
            'J' => 'file-alt',
            default => 'question',
        };
    }

    function getTipoClase($codigo)
    {
        return match ($codigo) {
            'A' => 'presente',
            'F' => 'ausente',
            'T' => 'tardanza',
            'J' => 'justificada',
            default => '',
        };
    }

@endphp
