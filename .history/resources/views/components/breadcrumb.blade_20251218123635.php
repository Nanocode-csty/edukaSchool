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
            @if(isset($module) && $module === 'asistencia')
                <li class="breadcrumb-item {{ !isset($section) ? 'active' : '' }}">
                    @if(isset($section))
                        @if(auth()->user()->rol == 'Administrador')
                            <a href="{{ route('asistencia.admin-index') }}" class="breadcrumb-link">
                                <i class="fas fa-calendar-check"></i>
                                <span class="breadcrumb-text">Asistencias</span>
                            </a>
                        @elseif(auth()->user()->rol == 'Profesor')
                            <a href="{{ route('asistencia.misEstudiantes') }}" class="breadcrumb-link">
                                <i class="fas fa-calendar-check"></i>
                                <span class="breadcrumb-text">Asistencias</span>
                            </a>
                        @else
                            <a href="{{ route('asistencia.misEstudiantes') }}" class="breadcrumb-link">
                                <i class="fas fa-calendar-check"></i>
                                <span class="breadcrumb-text">Asistencias</span>
                            </a>
                        @endif
                    @else
                        <i class="fas fa-calendar-check"></i>
                        <span class="breadcrumb-text">Asistencias</span>
                    @endif
                </li>

                {{-- Sub-sections --}}
                @if(isset($section))
                    @switch($section)
                        @case('admin')
                            <li class="breadcrumb-item {{ !isset($subsection) ? 'active' : '' }}">
                                @if(isset($subsection))
                                    <a href="{{ route('asistencia.admin-index') }}" class="breadcrumb-link">
                                        <i class="fas fa-cogs"></i>
                                        <span class="breadcrumb-text">Administración</span>
                                    </a>
                                @else
                                    <i class="fas fa-cogs"></i>
                                    <span class="breadcrumb-text">Administración</span>
                                @endif
                            </li>
                            @break

                        @case('estudiantes')
                            <li class="breadcrumb-item {{ !isset($subsection) ? 'active' : '' }}">
                                @if(isset($subsection))
                                    <a href="{{ route('asistencia.misEstudiantes') }}" class="breadcrumb-link">
                                        <i class="fas fa-users"></i>
                                        <span class="breadcrumb-text">Mis Estudiantes</span>
                                    </a>
                                @else
                                    <i class="fas fa-users"></i>
                                    <span class="breadcrumb-text">Mis Estudiantes</span>
                                @endif
                            </li>
                            @break

                        @case('justificar')
                            <li class="breadcrumb-item">
                                <a href="{{ route('asistencia.misEstudiantes') }}" class="breadcrumb-link">
                                    <i class="fas fa-users"></i>
                                    <span class="breadcrumb-text">Mis Estudiantes</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                <i class="fas fa-file-alt"></i>
                                <span class="breadcrumb-text">Justificar Inasistencia</span>
                            </li>
                            @break

                        @case('asignatura')
                            <li class="breadcrumb-item">
                                @if(auth()->user()->rol == 'Administrador')
                                    <a href="{{ route('asistencia.admin-index') }}" class="breadcrumb-link">
                                        <i class="fas fa-cogs"></i>
                                        <span class="breadcrumb-text">Administrar Asistencias</span>
                                    </a>
                                @else
                                    <a href="{{ route('asistencia.misEstudiantes') }}" class="breadcrumb-link">
                                        <i class="fas fa-users"></i>
                                        <span class="breadcrumb-text">Mis Estudiantes</span>
                                    </a>
                                @endif
                            </li>
                            <li class="breadcrumb-item active">
                                <i class="fas fa-edit"></i>
                                <span class="breadcrumb-text">Registrar Asistencia</span>
                            </li>
                            @break

                        @case('reporte-curso')
                            <li class="breadcrumb-item">
                                @if(auth()->user()->rol == 'Administrador')
                                    <a href="{{ route('asistencia.admin-index') }}" class="breadcrumb-link">
                                        <i class="fas fa-cogs"></i>
                                        <span class="breadcrumb-text">Administrar Asistencias</span>
                                    </a>
                                @else
                                    <a href="{{ route('asistencia.misEstudiantes') }}" class="breadcrumb-link">
                                        <i class="fas fa-users"></i>
                                        <span class="breadcrumb-text">Mis Estudiantes</span>
                                    </a>
                                @endif
                            </li>
                            <li class="breadcrumb-item active">
                                <i class="fas fa-chart-bar"></i>
                                <span class="breadcrumb-text">Reporte de Curso</span>
                            </li>
                            @break

                        @case('reporte-estudiante')
                            <li class="breadcrumb-item">
                                <a href="{{ route('asistencia.misEstudiantes') }}" class="breadcrumb-link">
                                    <i class="fas fa-users"></i>
                                    <span class="breadcrumb-text">Mis Estudiantes</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                <i class="fas fa-chart-line"></i>
                                <span class="breadcrumb-text">Reporte Individual</span>
                            </li>
                            @break

                        @case('reporte-general')
                            <li class="breadcrumb-item">
                                @if(auth()->user()->rol == 'Administrador')
                                    <a href="{{ route('asistencia.admin-index') }}" class="breadcrumb-link">
                                        <i class="fas fa-cogs"></i>
                                        <span class="breadcrumb-text">Administrar Asistencias</span>
                                    </a>
                                @else
                                    <a href="{{ route('asistencia.misEstudiantes') }}" class="breadcrumb-link">
                                        <i class="fas fa-users"></i>
                                        <span class="breadcrumb-text">Mis Estudiantes</span>
                                    </a>
                                @endif
                            </li>
                            <li class="breadcrumb-item active">
                                <i class="fas fa-chart-pie"></i>
                                <span class="breadcrumb-text">Reportes Generales</span>
                            </li>
                            @break

                        @case('verificar')
                            <li class="breadcrumb-item">
                                <a href="{{ route('asistencia.admin-index') }}" class="breadcrumb-link">
                                    <i class="fas fa-cogs"></i>
                                    <span class="breadcrumb-text">Administrar</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                <i class="fas fa-check-circle"></i>
                                <span class="breadcrumb-text">Verificar Justificaciones</span>
                            </li>
                            @break

                        @default
                            <li class="breadcrumb-item active">
                                <i class="fas fa-clipboard-check"></i>
                                <span class="breadcrumb-text">Panel de Asistencias</span>
                            </li>
                    @endswitch
                @endif
            @endif

            {{-- Custom items fallback --}}
            @if(!isset($module))
                @foreach($items ?? [] as $item)
                    @if($loop->last)
                        <li class="breadcrumb-item active" aria-current="page">
                            @if(isset($item['icon'])) <i class="{{ $item['icon'] }}"></i> @endif
                            <span class="breadcrumb-text">{{ $item['label'] }}</span>
                        </li>
                    @else
                        <li class="breadcrumb-item">
                            <a href="{{ $item['url'] ?? '#' }}" class="breadcrumb-link">
                                @if(isset($item['icon'])) <i class="{{ $item['icon'] }}"></i> @endif
                                <span class="breadcrumb-text">{{ $item['label'] }}</span>
                            </a>
                        </li>
                    @endif
                @endforeach
            @endif
        </ol>

        {{-- Quick Actions --}}
        @if(isset($module) && $module === 'asistencia')
            <div class="breadcrumb-actions">
                @if(request()->routeIs('asistencia.index'))
                    <a href="{{ route('asistencia.reporte-general') }}" class="btn-action" title="Ver Reportes">
                        <i class="fas fa-chart-pie"></i>
                    </a>
                @elseif(request()->routeIs('asistencia.admin-index'))
                    <a href="{{ route('asistencia.verificar') }}" class="btn-action" title="Verificar Justificaciones">
                        <i class="fas fa-check-circle"></i>
                    </a>
                @elseif(request()->routeIs('asistencia.misEstudiantes'))
                    <a href="{{ route('asistencia.justificar') }}" class="btn-action" title="Justificar Inasistencia">
                        <i class="fas fa-file-alt"></i>
                    </a>
                @endif
            </div>
        @endif
    </div>
</nav>

<style>
/* Eduka Breadcrumb Styles */
.breadcrumb-nav-eduka {
    background: linear-gradient(135deg, #ffffff 0%, #f0f8ff 25%, #e6f3ff 50%, #f0f8ff 75%, #ffffff 100%);
    border-radius: 0;
    padding: 0;
    margin-bottom: 2rem;
    box-shadow: 0 4px 16px rgba(14, 64, 103, 0.12), 0 2px 6px rgba(40, 174, 206, 0.08);
    border: 1px solid rgba(40, 174, 206, 0.15);
    position: relative;
    overflow: hidden;
}

.breadcrumb-nav-eduka::before {
    content: '';
    position: absolute;
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
    padding: 1rem 1.25rem;
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
    padding: 0.375rem 0.5rem;
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
