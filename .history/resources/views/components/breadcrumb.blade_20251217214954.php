{{-- Advanced Breadcrumb Component for Eduka System --}}
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
                        <a href="{{ route('asistencia.index') }}" class="breadcrumb-link">
                            <i class="fas fa-calendar-check"></i>
                            <span class="breadcrumb-text">Asistencias</span>
                        </a>
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
                                        <span class="breadcrumb-text">Administrar</span>
                                    </a>
                                @else
                                    <i class="fas fa-cogs"></i>
                                    <span class="breadcrumb-text">Administrar</span>
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
}

.breadcrumb-item i {
    font-size: 0.75rem;
    opacity: 0.8;
}

@media (max-width: 768px) {
    .breadcrumb-nav {
        padding: 0.5rem 0.75rem;
    }

    .breadcrumb {
        font-size: 0.8rem;
    }

    .breadcrumb-item span {
        display: none;
    }

    .breadcrumb-item.active span {
        display: inline;
    }
}
</style>
