{{-- Simplified Breadcrumb Component for Eduka System --}}
<nav aria-label="breadcrumb" class="breadcrumb-nav-eduka">
    <div class="breadcrumb-container">
        <ol class="breadcrumb">
            {{-- Always show Home --}}
            <li class="breadcrumb-item">
                <a href="{{ route('rutarrr1') }}" class="breadcrumb-link">
                    <i class="fas fa-home"></i>
                    <span class="breadcrumb-text">Inicio</span>
                </a>
            </li>



            {{-- Module Section --}}
            @if(isset($module))
                @switch($module)
                    @case('asistencia')
                        @php
                            $canAccessAsistencias = auth()->check() && (
                                auth()->user()->hasRole('Administrador') ||
                                auth()->user()->hasRole('Docente') ||
                                auth()->user()->hasRole('representante')
                            );
                        @endphp

                        @if($canAccessAsistencias)
                            <li class="breadcrumb-item">
                                <i class="fas fa-school"></i>
                                <span class="breadcrumb-text">Sistema Educativo</span>
                            </li>
                        @else
                            <li class="breadcrumb-item">
                                <span class="breadcrumb-text text-muted">
                                    <i class="fas fa-school"></i>
                                    Sistema Educativo
                                </span>
                            </li>
                        @endif
                        @break

                    @case('admin')
                        <li class="breadcrumb-item">
                            <i class="fas fa-cogs"></i>
                            <span class="breadcrumb-text">Administración</span>
                        </li>
                        @break

                    @case('docente')
                        <li class="breadcrumb-item">
                            <i class="fas fa-chalkboard-teacher"></i>
                            <span class="breadcrumb-text">Docente</span>
                        </li>
                        @break

                    @case('representante')
                        <li class="breadcrumb-item">
                            <i class="fas fa-user-friends"></i>
                            <span class="breadcrumb-text">Representante</span>
                        </li>
                        @break

                    @case('general')
                        <li class="breadcrumb-item">
                            <i class="fas fa-school"></i>
                            <span class="breadcrumb-text">Sistema Educativo</span>
                        </li>
                        @break

                    @case('conceptospago')
                        <li class="breadcrumb-item">
                            <i class="fas fa-money-bill-wave"></i>
                            <span class="breadcrumb-text">Pagos</span>
                        </li>
                        @break

                    @case('periodos')
                        <li class="breadcrumb-item">
                            <i class="fas fa-calendar-alt"></i>
                            <span class="breadcrumb-text">Períodos Académicos</span>
                        </li>
                        @break
                @endswitch
            @elseif(request()->is('/'))
                {{-- Home route breadcrumb --}}
                <li class="breadcrumb-item active">
                    <i class="fas fa-home"></i>
                    <span class="breadcrumb-text">Inicio</span>
                </li>
            @elseif(request()->is('rutarrr1'))
                {{-- Home route breadcrumb --}}
                <li class="breadcrumb-item active">
                    <i class="fas fa-home"></i>
                    <span class="breadcrumb-text">Inicio</span>
                </li>
            @endif

            @if(isset($module) && $module === 'asistencia')

                {{-- Parent Asistencias Section - Always plain text, never clickable --}}
                <li class="breadcrumb-item">
                    <span class="breadcrumb-text">
                        <i class="fas fa-calendar-check"></i>
                        Asistencias
                    </span>
                </li>

                {{-- Sub-sections --}}
                @if(isset($section))
                    @switch($section)
                        @case('admin')
                            <li class="breadcrumb-item active">
                                <i class="fas fa-table"></i>
                                <span class="breadcrumb-text">Administrar Asistencias</span>
                            </li>
                            @break

                        @case('reportes')
                            <li class="breadcrumb-item active">
                                <i class="fas fa-chart-line"></i>
                                <span class="breadcrumb-text">Reportes de Asistencia</span>
                            </li>
                            @break

                        @case('verificar')
                            <li class="breadcrumb-item active">
                                <i class="fas fa-clipboard-check"></i>
                                <span class="breadcrumb-text">Gestionar Justificaciones</span>
                            </li>
                            @break

                        @case('historial')
                            <li class="breadcrumb-item active">
                                <i class="fas fa-history"></i>
                                <span class="breadcrumb-text">Historial de Eventos</span>
                            </li>
                            @break

                        @case('docente-dashboard')
                            <li class="breadcrumb-item">
                                <a href="{{ route('asistencia.docente.dashboard') }}" class="breadcrumb-link">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                    <span class="breadcrumb-text">Docente</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                <i class="fas fa-tachometer-alt"></i>
                                <span class="breadcrumb-text">Dashboard</span>
                            </li>
                            @break

                        @case('docente')
                            <li class="breadcrumb-item">
                                <a href="{{ route('asistencia.docente.dashboard') }}" class="breadcrumb-link">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                    <span class="breadcrumb-text">Docente</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                <i class="fas fa-history"></i>
                                <span class="breadcrumb-text">Historial de Asistencias</span>
                            </li>
                            @break

                        @case('docente-tomar')
                            <li class="breadcrumb-item">
                                <a href="{{ route('asistencia.docente.dashboard') }}" class="breadcrumb-link">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                    <span class="breadcrumb-text">Docente</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                <i class="fas fa-calendar-plus"></i>
                                <span class="breadcrumb-text">Tomar Asistencia</span>
                            </li>
                            @break

                        @case('docente-ver')
                            <li class="breadcrumb-item">
                                <a href="{{ route('asistencia.docente.dashboard') }}" class="breadcrumb-link">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                    <span class="breadcrumb-text">Docente</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                <i class="fas fa-eye"></i>
                                <span class="breadcrumb-text">Ver Asistencias</span>
                            </li>
                            @break

                        @case('docente-reportes')
                            <li class="breadcrumb-item">
                                <a href="{{ route('asistencia.docente.dashboard') }}" class="breadcrumb-link">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                    <span class="breadcrumb-text">Docente</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                <i class="fas fa-chart-line"></i>
                                <span class="breadcrumb-text">Reportes</span>
                            </li>
                            @break

                        @case('docente-estadisticas')
                            <li class="breadcrumb-item">
                                <a href="{{ route('asistencia.docente.dashboard') }}" class="breadcrumb-link">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                    <span class="breadcrumb-text">Docente</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                <i class="fas fa-chart-bar"></i>
                                <span class="breadcrumb-text">Estadísticas</span>
                            </li>
                            @break

                        @case('docente-ver-asistencia')
                            <li class="breadcrumb-item">
                                <a href="{{ route('asistencia.docente.dashboard') }}" class="breadcrumb-link">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                    <span class="breadcrumb-text">Docente</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('asistencia.docente.ver-asistencias') }}" class="breadcrumb-link">
                                    <i class="fas fa-eye"></i>
                                    <span class="breadcrumb-text">Ver Asistencias</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                <i class="fas fa-calendar-day"></i>
                                <span class="breadcrumb-text">Detalle de Sesión</span>
                            </li>
                            @break

                        @case('representante')
                            @if(isset($dashboard) && $dashboard === 'asistencia.representante.dashboard')
                                <li class="breadcrumb-item">
                                    <a href="{{ route('asistencia.representante.dashboard') }}" class="breadcrumb-link">
                                        <i class="fas fa-tachometer-alt"></i>
                                        <span class="breadcrumb-text">Panel Principal</span>
                                    </a>
                                </li>
                            @endif
                            <li class="breadcrumb-item active">
                                <i class="fas fa-user-friends"></i>
                                <span class="breadcrumb-text">Mis Estudiantes</span>
                            </li>
                            @break

                        @case('representante-detalle')
                            <li class="breadcrumb-item">
                                <a href="{{ route('asistencia.representante.index') }}" class="breadcrumb-link">
                                    <i class="fas fa-user-friends"></i>
                                    <span class="breadcrumb-text">Mis Estudiantes</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                <i class="fas fa-user-graduate"></i>
                                <span class="breadcrumb-text">Detalle de Estudiante</span>
                            </li>
                            @break

                        @case('representante-notas')
                            <li class="breadcrumb-item active">
                                <i class="fas fa-chart-bar"></i>
                                <span class="breadcrumb-text">Calificaciones</span>
                            </li>
                            @break

                        @case('representante-dashboard')
                            <li class="breadcrumb-item active">
                                <i class="fas fa-tachometer-alt"></i>
                                <span class="breadcrumb-text">Panel Principal</span>
                            </li>
                            @break

                        @case('representante-estudiante')
                            <li class="breadcrumb-item">
                                <a href="{{ route('notas.misEstudiantes') }}" class="breadcrumb-link">
                                    <i class="fas fa-chart-bar"></i>
                                    <span class="breadcrumb-text">Calificaciones</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                <i class="fas fa-user-graduate"></i>
                                <span class="breadcrumb-text">Detalle de Estudiante</span>
                            </li>
                            @break

                        @default
                            <li class="breadcrumb-item active">
                                <i class="fas fa-tachometer-alt"></i>
                                <span class="breadcrumb-text">Panel Principal</span>
                            </li>
                    @endswitch
                @endif
            @endif

            @if(isset($module) && $module === 'conceptospago')
                {{-- Parent Conceptos de Pago Section --}}
                <li class="breadcrumb-item">
                    <span class="breadcrumb-text">
                        <i class="fas fa-money-bill-wave"></i>
                        Conceptos de Pago
                    </span>
                </li>

                {{-- Sub-sections --}}
                @if(isset($section))
                    @switch($section)
                        @case('index')
                            <li class="breadcrumb-item active">
                                <i class="fas fa-list"></i>
                                <span class="breadcrumb-text">Lista de Conceptos</span>
                            </li>
                            @break

                        @case('crear')
                            <li class="breadcrumb-item active">
                                <i class="fas fa-plus"></i>
                                <span class="breadcrumb-text">Crear Concepto</span>
                            </li>
                            @break

                        @case('editar')
                            <li class="breadcrumb-item active">
                                <i class="fas fa-edit"></i>
                                <span class="breadcrumb-text">Editar Concepto</span>
                            </li>
                            @break

                        @case('show')
                            <li class="breadcrumb-item active">
                                <i class="fas fa-eye"></i>
                                <span class="breadcrumb-text">Ver Concepto</span>
                            </li>
                            @break

                        @default
                            <li class="breadcrumb-item active">
                                <i class="fas fa-money-bill-wave"></i>
                                <span class="breadcrumb-text">Conceptos de Pago</span>
                            </li>
                    @endswitch
                @endif
            @endif

    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #0e4067, #28aece, #0e4067);
}

.breadcrumb-container {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.5rem 0.75rem;
}

.breadcrumb {
    background: transparent;
    margin: 0;
    padding: 0;
    font-size: 0.9rem;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
}

.breadcrumb-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    position: relative;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: '';
    display: inline-block;
    width: 16px;
    height: 16px;
    background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%236c757d' viewBox='0 0 16 16'%3E%3Cpath fill-rule='evenodd' d='M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z'/%3E%3C/svg%3E") no-repeat center;
    margin: 0 0.5rem;
    opacity: 0.6;
}

.breadcrumb-link {
    color: #0e4067;
    text-decoration: none;
    font-weight: 600;
    padding: 0.25rem 0.375rem;
    border-radius: 6px;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    gap: 0.375rem;
}

.breadcrumb-link:hover {
    color: #28aece;
    background-color: rgba(40, 174, 206, 0.1);
    text-decoration: none;
    transform: translateY(-1px);
}

.breadcrumb-item.active {
    color: #495057;
    font-weight: 700;
}

.breadcrumb-item.active .breadcrumb-text {
    color: #28aece;
}

.breadcrumb-item i {
    font-size: 0.875rem;
    opacity: 0.8;
}

.breadcrumb-text {
    font-weight: 500;
    letter-spacing: 0.025em;
}

/* Quick Actions */
.breadcrumb-actions {
    display: flex;
    gap: 0.5rem;
}

.btn-action {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border-radius: 8px;
    background: rgba(14, 64, 103, 0.1);
    color: #0e4067;
    border: 1px solid rgba(14, 64, 103, 0.2);
    text-decoration: none;
    transition: all 0.2s ease;
    font-size: 0.875rem;
}

.btn-action:hover {
    background: #0e4067;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(14, 64, 103, 0.3);
}

/* Responsive Design */
@media (max-width: 768px) {
    .breadcrumb-container {
        padding: 0.75rem 1rem;
        flex-direction: column;
        align-items: flex-start;
        gap: 0.75rem;
    }

    .breadcrumb {
        font-size: 0.8rem;
        width: 100%;
    }

    .breadcrumb-item span:not(.breadcrumb-text) {
        display: none;
    }

    .breadcrumb-item.active span.breadcrumb-text {
        display: inline;
    }

    .breadcrumb-actions {
        width: 100%;
        justify-content: center;
    }

    .btn-action {
        flex: 1;
        max-width: 120px;
    }
}

@media (max-width: 576px) {
    .breadcrumb-nav-eduka {
        margin-bottom: 1.5rem;
    }

    .breadcrumb-container {
        padding: 0.5rem 0.75rem;
    }

    .breadcrumb-item {
        gap: 0.25rem;
    }

    .breadcrumb-link {
        padding: 0.25rem 0.375rem;
        font-size: 0.8rem;
    }

    .breadcrumb-text {
        font-size: 0.8rem;
    }
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    .breadcrumb-nav-eduka {
        background: linear-gradient(135deg, #2d3748 0%, #1a202c 100%);
        border-color: rgba(40, 174, 206, 0.2);
    }

    .breadcrumb-link {
        color: #e2e8f0;
    }

    .breadcrumb-link:hover {
        color: #28aece;
        background-color: rgba(40, 174, 206, 0.1);
    }

    .breadcrumb-item.active .breadcrumb-text {
        color: #28aece;
    }
}
</style>
