@extends('cplantilla.bprincipal')

@section('titulo','Tomar Asistencia - Docente')

@section('contenidoplantilla')

<x-breadcrumb :module="'asistencia'" :section="'docente-tomar'" />

<div class="container-fluid" id="contenido-principal">
    <div class="row mt-4">
        <div class="col-12">
            <div class="box_block">
                <!-- Weekly Calendar Section -->
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">
                                    <i class="fas fa-calendar-week"></i> Calendario Semanal de Asistencias
                                </h6>
                                <small>Selecciona un día para ver su horario</small>
                            </div>
                            <div class="d-flex gap-2">
                                <button class="btn btn-light btn-sm" type="button" onclick="cambiarSemana(-1)">
                                    <i class="fas fa-chevron-left"></i> Semana Anterior
                                </button>
                                <button class="btn btn-light btn-sm" type="button" id="toggleCalendarBtn">
                                    <i class="fas fa-chevron-down"></i> <span id="calendarBtnText">Ver Calendario</span>
                                </button>
                                <button class="btn btn-light btn-sm" type="button" onclick="cambiarSemana(1)">
                                    Semana Siguiente <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div id="weeklyCalendar" class="card-body p-0" style="display: block;">
                        <div id="weekly-calendar-container">
                            <!-- Calendar will be loaded here -->
                        </div>
                    </div>
                </div>

                <!-- Main Attendance Section -->
                <!-- Collapse header -->
                <button class="btn btn-block text-left rounded-0 btn_header header_6" type="button" data-toggle="collapse" data-target="#collapseTomarAsistencia" aria-expanded="true" aria-controls="collapseTomarAsistencia" style="background: #007bff !important; font-weight: bold; color: white;">
                    <i class="fas fa-clipboard-check m-1"></i>&nbsp;Tomar Asistencia -
                    <span id="current-date-display">{{ $fecha_seleccionada ? $fecha_seleccionada->locale('es')->dayName . ', ' . $fecha_seleccionada->format('d/m/Y') : now()->locale('es')->dayName . ', ' . date('d/m/Y') }}</span>
                    <div class="float-right"><i class="fas fa-chevron-down"></i></div>
                </button>
                <!-- Descripción (siempre visible, dentro del bloque) -->
                <div class="card-body rounded-0 border-0 pt-3 pb-3" style="background: #f3f3f3; border-bottom: 1px solid rgba(0,0,0,.125); border-top: 1px solid rgba(0,0,0,.125); color: #F59D24;">
                    <div class="row justify-content-center align-items-center flex-wrap">
                        <div class="col-auto text-center mb-2 mb-md-0" style="min-width:48px;">
                            <i class="fas fa-calendar-day fa-2x"></i>
                        </div>
                        <div class="col px-2" style="text-align:justify;">
                            <p style="margin-bottom: 0px; font-family: 'Quicksand', sans-serif; font-weight: 600; color: #004a92;">
                                Registra la asistencia de tus estudiantes directamente en esta vista. Selecciona el tipo de asistencia para cada estudiante y guarda los cambios.
                            </p>
                        </div>
                    </div>
                </div>
                <!-- Collapse: contenido del dashboard -->
                <div class="collapse show" id="collapseTomarAsistencia">
                    <div class="card card-body rounded-0 border-0 pt-0 pb-2" style="background: transparent;">

                        @if(isset($error))
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle"></i> {{ $error }}
                            </div>
                        @else

                            @if($clases_hoy->count() > 0)
                                <!-- Vista de Horario Organizada - Full Width -->
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card">
                                        <div class="card-header bg-primary text-white">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <div class="mr-3">
                                                        <h5 class="mb-0">
                                                            <i class="fas fa-calendar-alt"></i> Horario de Clases
                                                        </h5>
                                                        <small>{{ $clases_hoy->count() }} clases programadas</small>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <div class="text-right mr-3">
                                                        <div class="d-flex gap-3">
                                                            <div class="text-center">
                                                                <div class="h4 mb-0">{{ $clases_hoy->where('tiene_asistencia_hoy', true)->count() }}</div>
                                                                <small>Completadas</small>
                                                            </div>
                                                            <div class="text-center">
                                                                <div class="h4 mb-0">{{ $clases_hoy->where('tiene_asistencia_hoy', false)->count() }}</div>
                                                                <small>Pendientes</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button class="btn btn-outline-light btn-sm" type="button" data-toggle="collapse" data-target="#horarioClases" aria-expanded="true" aria-controls="horarioClases">
                                                        <i class="fas fa-chevron-down"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="collapse show" id="horarioClases">
                                        <div class="card-body p-0">
                                            <!-- Timeline de Clases -->
                                            <div class="schedule-timeline">
                                                @php
                                                    $clases_ordenadas = $clases_hoy->sortBy('hora_inicio');
                                                    $horarios = ['07:00', '08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00'];
                                                @endphp

                                                @foreach($horarios as $hora)
                                                @php
                                                    $clases_en_hora = $clases_ordenadas->filter(function($clase) use ($hora) {
                                                        return substr($clase->hora_inicio, 0, 2) === substr($hora, 0, 2);
                                                    });
                                                @endphp

                                                @if($clases_en_hora->count() > 0)
                                                <div class="time-slot">
                                                    <div class="time-label">
                                                        <div class="time-circle">
                                                            <i class="fas fa-clock"></i>
                                                            <span>{{ substr($hora, 0, 5) }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="classes-container">
                                                        @foreach($clases_en_hora as $clase)
                                                        <div class="class-card-wrapper">
                                                            <div class="class-card {{ $clase->tiene_asistencia_hoy ? 'completed' : 'pending' }}">
                                                                <div class="class-header">
                                                                    <!-- Icono de la asignatura -->
                                                                    <div class="subject-icon">
                                                                        @php
                                                                            $subjectIcons = [
                                                                                'Matemáticas' => 'fas fa-calculator',
                                                                                'Lenguaje' => 'fas fa-book-open',
                                                                                'Ciencia' => 'fas fa-flask',
                                                                                'Historia' => 'fas fa-landmark',
                                                                                'Geografía' => 'fas fa-globe-americas',
                                                                                'Inglés' => 'fas fa-language',
                                                                                'Educación Física' => 'fas fa-running',
                                                                                'Arte' => 'fas fa-palette',
                                                                                'Música' => 'fas fa-music',
                                                                                'Tecnología' => 'fas fa-laptop-code',
                                                                                'default' => 'fas fa-book'
                                                                            ];
                                                                            $subjectName = $clase->cursoAsignatura->asignatura->nombre;
                                                                            $icon = 'fas fa-book'; // default
                                                                            foreach($subjectIcons as $key => $value) {
                                                                                if(strpos(strtolower($subjectName), strtolower($key)) !== false) {
                                                                                    $icon = $value;
                                                                                    break;
                                                                                }
                                                                            }
                                                                        @endphp
                                                                        <i class="{{ $icon }}"></i>
                                                                    </div>

                                                                    <div class="class-content">
                                                                        <!-- Nombre de la asignatura -->
                                                                        <div class="subject-title">
                                                                            {{ $clase->cursoAsignatura->asignatura->nombre }}
                                                                        </div>

                                                                        <!-- Información del curso -->
                                                                        <div class="course-info">
                                                                            <div class="grade-badge">
                                                                                <i class="fas fa-graduation-cap"></i>
                                                                                {{ $clase->cursoAsignatura->curso->grado->nombre }} {{ $clase->cursoAsignatura->curso->seccion->nombre }}
                                                                            </div>
                                                                        </div>

                                                                        <!-- Horario y aula -->
                                                                        <div class="schedule-info">
                                                                            <div class="time-info">
                                                                                <i class="fas fa-clock"></i>
                                                                                {{ substr($clase->hora_inicio, 0, 5) }} - {{ substr($clase->hora_fin, 0, 5) }}
                                                                            </div>
                                                                            <div class="room-info">
                                                                                <i class="fas fa-map-marker-alt"></i>
                                                                                {{ $clase->aula ? $clase->aula->nombre : 'Sin aula' }}
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Estado de asistencia -->
                                                                    <div class="class-status">
                                                                        @if($clase->tiene_asistencia_hoy)
                                                                            <div class="status-badge completed">
                                                                                <i class="fas fa-check-circle"></i>
                                                                                <span>Completada</span>
                                                                            </div>
                                                                        @else
                                                                            <div class="status-badge pending">
                                                                                <i class="fas fa-clock"></i>
                                                                                <span>Pendiente</span>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </div>

                                                                <!-- Botón principal de acción -->
                                                                <div class="class-actions">
                                                                    @if($clase->tiene_asistencia_hoy)
                                                                        <button class="btn btn-success action-btn main-action-btn" onclick="verAsistencia({{ $clase->sesion_id }})">
                                                                            <i class="fas fa-eye"></i>
                                                                            <span>Ver Asistencia</span>
                                                                        </button>
                                                                    @else
                                                                        <button class="btn btn-primary action-btn main-action-btn" onclick="marcarAsistenciaRapida({{ $clase->sesion_id }})">
                                                                            <i class="fas fa-edit"></i>
                                                                            <span>Marcar Asistencia</span>
                                                                        </button>
                                                                    @endif
                                                                </div>
                                                            </div>

                                                            <!-- Panel Expandible de Asistencia -->
                                                            <div class="attendance-panel" id="attendance-panel-{{ $clase->sesion_id }}" style="display: none;">
                                                                <div class="attendance-content">
                                                                    @if($clase->tiene_asistencia_hoy)
                                                                        <div class="alert alert-success">
                                                                            <i class="fas fa-check-circle"></i> La asistencia para esta clase ya ha sido registrada.
                                                                            <a href="{{ route('asistencia.docente.ver', $clase->sesion_id) }}" class="alert-link">Ver detalles completos</a>
                                                                        </div>
                                                                    @else
                                                                        <!-- Filtros para estudiantes -->
                                                                        <div class="filters-section mb-3">
                                                                            <div class="row align-items-center">
                                                                                <div class="col-md-6">
                                                                                    <div class="input-group input-group-sm">
                                                                                        <div class="input-group-prepend">
                                                                                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                                                                                        </div>
                                                                                        <input type="text" class="form-control student-search" id="search_{{ $clase->sesion_id }}"
                                                                                               placeholder="Buscar estudiante..." data-sesion-id="{{ $clase->sesion_id }}">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <div class="d-flex gap-2">
                                                                                        <select class="form-control form-control-sm attendance-filter" id="filter_{{ $clase->sesion_id }}" data-sesion-id="{{ $clase->sesion_id }}">
                                                                                            <option value="all">Todos</option>
                                                                                            <option value="present">Presentes</option>
                                                                                            <option value="absent">Ausentes</option>
                                                                                            <option value="late">Tardes</option>
                                                                                            <option value="justified">Justificados</option>
                                                                                        </select>
                                                                                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="limpiarFiltros({{ $clase->sesion_id }})">
                                                                                            <i class="fas fa-times"></i>
                                                                                        </button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="mt-2">
                                                                                <small class="text-muted">
                                                                                    <span id="student-count-{{ $clase->sesion_id }}"></span>
                                                                                </small>
                                                                            </div>
                                                                        </div>

                                                                        <form id="form-asistencia-{{ $clase->sesion_id }}" class="attendance-form" data-sesion-id="{{ $clase->sesion_id }}">
                                                                            <div class="attendance-grid">
                                                                                @php
                                                                                    $estudiantes = $clase->cursoAsignatura->curso->matriculas()
                                                                                        ->with(['estudiante.persona'])
                                                                                        ->where('estado', 'Activo')
                                                                                        ->orderBy('matriculas.matricula_id')
                                                                                        ->get();
                                                                                @endphp

                                                                                @foreach($estudiantes as $index => $matricula)
                                                                                <div class="student-attendance-card student-row"
                                                                                     data-student-name="{{ strtolower($matricula->estudiante->persona->nombres . ' ' . $matricula->estudiante->persona->apellidos) }}"
                                                                                     data-attendance-type="present">
                                                                                    <div class="student-header">
                                                                                        <div class="student-avatar">
                                                                                            <div class="avatar-circle">
                                                                                                {{ substr($matricula->estudiante->persona->nombres, 0, 1) }}{{ substr($matricula->estudiante->persona->apellidos, 0, 1) }}
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="student-info">
                                                                                            <div class="student-name">{{ $matricula->estudiante->persona->nombres }}</div>
                                                                                            <div class="student-lastname">{{ $matricula->estudiante->persona->apellidos }}</div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="attendance-options">
                                                                                        <label class="attendance-option present active">
                                                                                            <input type="radio" name="asistencia_{{ $clase->sesion_id }}_{{ $matricula->matricula_id }}" value="P" checked
                                                                                                   class="attendance-radio" data-matricula-id="{{ $matricula->matricula_id }}" data-sesion-id="{{ $clase->sesion_id }}">
                                                                                            <i class="fas fa-check-circle"></i>
                                                                                            <span>Presente</span>
                                                                                        </label>
                                                                                        <label class="attendance-option absent">
                                                                                            <input type="radio" name="asistencia_{{ $clase->sesion_id }}_{{ $matricula->matricula_id }}" value="A"
                                                                                                   class="attendance-radio" data-matricula-id="{{ $matricula->matricula_id }}" data-sesion-id="{{ $clase->sesion_id }}">
                                                                                            <i class="fas fa-times-circle"></i>
                                                                                            <span>Ausente</span>
                                                                                        </label>
                                                                                        <label class="attendance-option late">
                                                                                            <input type="radio" name="asistencia_{{ $clase->sesion_id }}_{{ $matricula->matricula_id }}" value="T"
                                                                                                   class="attendance-radio" data-matricula-id="{{ $matricula->matricula_id }}" data-sesion-id="{{ $clase->sesion_id }}">
                                                                                            <i class="fas fa-clock"></i>
                                                                                            <span>Tarde</span>
                                                                                        </label>
                                                                                        <label class="attendance-option justified">
                                                                                            <input type="radio" name="asistencia_{{ $clase->sesion_id }}_{{ $matricula->matricula_id }}" value="J"
                                                                                                   class="attendance-radio" data-matricula-id="{{ $matricula->matricula_id }}" data-sesion-id="{{ $clase->sesion_id }}">
                                                                                            <i class="fas fa-file-medical"></i>
                                                                                            <span>Justificado</span>
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                                @endforeach
                                                                            </div>

                                                                            <div class="attendance-footer">
                                                                                <div class="row align-items-center">
                                                                                    <div class="col-md-8">
                                                                                        <div class="form-group mb-0">
                                                                                            <label for="observaciones_{{ $clase->sesion_id }}">
                                                                        <i class="fas fa-comment"></i> Observaciones generales:
                                                                    </label>
                                                                                            <textarea class="form-control form-control-sm" id="observaciones_{{ $clase->sesion_id }}" name="observaciones_generales"
                                                                                                      rows="2" placeholder="Observaciones para toda la clase..."></textarea>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-4 text-right">
                                                                                        <div class="d-flex gap-2 justify-content-end">
                                                                                            <button type="button" class="btn btn-outline-secondary btn-sm"
                                                                                                    onclick="marcarTodosPresentes({{ $clase->sesion_id }})">
                                                                                                <i class="fas fa-check-double"></i> Todos Presentes
                                                                                            </button>
                                                                                            <button type="submit" class="btn btn-success">
                                                                                                <i class="fas fa-save"></i> Guardar
                                                                                            </button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Resumen General -->
                                <div class="card border-info">
                                    <div class="card-header bg-info text-white">
                                        <h6 class="mb-0">
                                            <i class="fas fa-chart-bar"></i> Resumen del Día
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row text-center">
                                            <div class="col-md-3">
                                                <div class="p-3 bg-light rounded">
                                                    <h4 class="text-primary">{{ $clases_hoy->count() }}</h4>
                                                    <small class="text-muted">Clases Totales</small>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="p-3 bg-success rounded text-white">
                                                    <h4>{{ $clases_hoy->where('tiene_asistencia_hoy', true)->count() }}</h4>
                                                    <small>Completadas</small>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="p-3 bg-warning rounded text-white">
                                                    <h4>{{ $clases_hoy->where('tiene_asistencia_hoy', false)->count() }}</h4>
                                                    <small>Pendientes</small>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="p-3 bg-info rounded text-white">
                                                    <h4>{{ $clases_hoy->sum(function($c) { return $c->cursoAsignatura->curso->matriculas()->where('estado', 'Activo')->count(); }) }}</h4>
                                                    <small>Estudiantes Totales</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-calendar-times text-muted fa-4x mb-4"></i>
                                    <h4 class="text-muted mb-3">No hay clases programadas para hoy</h4>
                                    <p class="text-muted mb-4">No tienes sesiones de clase programadas para el día de hoy.</p>
                                    <a href="{{ route('asistencia.docente.dashboard') }}" class="btn btn-primary">
                                        <i class="fas fa-arrow-left"></i> Volver al Dashboard
                                    </a>
                                </div>
                            @endif

                            <!-- Acciones Rápidas -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="d-flex justify-content-end gap-2 flex-wrap">
                                        <a href="{{ route('asistencia.docente.ver-asistencias') }}" class="btn btn-sm" style="background-color: #28a745 !important; color: white !important; border: none !important;">
                                            <i class="fas fa-eye mr-1"></i>Ver Todas las Asistencias
                                        </a>
                                        <a href="{{ route('asistencia.docente.dashboard') }}" class="btn btn-sm" style="background-color: #6c757d !important; color: white !important; border: none !important;">
                                            <i class="fas fa-arrow-left mr-1"></i>Volver al Dashboard
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('css-extra')
<style>
/* Weekly Calendar Styles */
.weekly-calendar {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 8px;
    padding: 15px;
}

.calendar-day {
    aspect-ratio: 0.8;
    border-radius: 6px;
    padding: 6px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid #e9ecef;
    background: white;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    position: relative;
    overflow: hidden;
    min-height: 70px;
    max-height: 80px;
}

/* Responsive calendar */
@media (max-width: 768px) {
    .weekly-calendar {
        grid-template-columns: repeat(7, 1fr);
        gap: 4px;
        padding: 10px;
    }

    .calendar-day {
        padding: 6px;
        min-height: 60px;
    }

    .day-number {
        font-size: 18px;
    }

    .day-name {
        font-size: 10px;
    }
}

@media (max-width: 576px) {
    .weekly-calendar {
        grid-template-columns: repeat(7, 1fr);
        gap: 2px;
        padding: 8px;
    }

    .calendar-day {
        padding: 4px;
        min-height: 50px;
    }

    .day-number {
        font-size: 16px;
    }

    .day-name {
        font-size: 9px;
    }
}

.calendar-day:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.calendar-day.today {
    border-color: #007bff;
    background: #f8f9ff;
}

.calendar-day.selected {
    border-color: #007bff;
    background: #f0f8ff;
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.3);
    transform: scale(1.02);
}

.calendar-day.selected .day-number,
.calendar-day.selected .day-name {
    color: #007bff;
    font-weight: bold;
}

.calendar-day.has-classes {
    border-color: #17a2b8;
}

.calendar-day.has-classes.completed {
    border-color: #28a745;
}

.calendar-day.has-classes.pending {
    border-color: #ffc107;
}

.day-number {
    font-size: 24px;
    font-weight: bold;
    color: #2c3e50;
    margin-bottom: 5px;
}

.day-name {
    font-size: 12px;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 10px;
}

.day-stats {
    display: flex;
    justify-content: space-around;
    align-items: center;
    margin-top: auto;
}

.day-stat {
    display: flex;
    flex-direction: column;
    align-items: center;
    font-size: 10px;
}

.day-stat.completed {
    color: #28a745;
}

.day-stat.pending {
    color: #ffc107;
}

.day-stat-number {
    font-weight: bold;
    font-size: 12px;
}

.day-stat-label {
    font-size: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.calendar-header {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 10px;
    margin-bottom: 10px;
    padding: 0 20px;
}

.calendar-header-day {
    text-align: center;
    font-weight: 600;
    color: #495057;
    font-size: 14px;
    padding: 10px 0;
}

.calendar-navigation {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 20px;
    background: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
}

.calendar-week-info {
    font-weight: 600;
    color: #2c3e50;
}

/* Calendar hidden class */
.calendar-hidden {
    display: none !important;
}

/* Gradient backgrounds */
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

/* Enhanced Schedule Timeline Styles */
.schedule-timeline {
    position: relative;
    padding: 15px 0;
}

.time-slot {
    display: flex;
    align-items: flex-start;
    margin-bottom: 20px;
    position: relative;
}

.time-label {
    flex-shrink: 0;
    width: 70px;
    margin-right: 15px;
    text-align: center;
}

.time-circle {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: #6c757d;
    color: white;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: bold;
    margin: 0 auto;
}

.time-circle i {
    font-size: 14px;
    margin-bottom: 1px;
}

.classes-container {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.class-card-wrapper {
    position: relative;
}

.class-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 1px 6px rgba(0,0,0,0.06);
    border: 1px solid #e9ecef;
    transition: all 0.2s ease;
    overflow: hidden;
}

.class-card:hover {
    box-shadow: 0 3px 12px rgba(0,0,0,0.1);
    border-color: #dee2e6;
}

.class-card.completed {
    border-left: 3px solid #28a745;
}

.class-card.pending {
    border-left: 3px solid #ffc107;
}

.class-header {
    display: flex;
    align-items: center;
    padding: 12px;
    gap: 12px;
}

.subject-icon {
    flex-shrink: 0;
    width: 40px;
    height: 40px;
    border-radius: 8px;
    background: #6c757d;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 16px;
}

.class-content {
    flex: 1;
    min-width: 0;
}

.subject-title {
    font-size: 15px;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 6px;
    line-height: 1.3;
}

.course-info {
    margin-bottom: 6px;
}

.grade-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: #f8f9fa;
    color: #495057;
    padding: 3px 8px;
    border-radius: 15px;
    font-size: 12px;
    font-weight: 500;
    border: 1px solid #dee2e6;
}

.grade-badge i {
    font-size: 11px;
}

.schedule-info {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.time-info,
.room-info {
    display: flex;
    align-items: center;
    gap: 4px;
    color: #6c757d;
    font-size: 12px;
    font-weight: 500;
}

.time-info i,
.room-info i {
    font-size: 11px;
    width: 12px;
}

.class-status {
    flex-shrink: 0;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 8px;
    border-radius: 15px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.status-badge.completed {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.status-badge.pending {
    background: #fff3cd;
    color: #856404;
    border: 1px solid #ffeaa7;
}

.status-badge i {
    font-size: 9px;
}

.class-actions {
    padding: 0 12px 12px 12px;
    border-top: 1px solid #f8f9fa;
    margin-top: 12px;
}

.action-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 10px 20px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
    border: none;
    cursor: pointer;
    width: 100%;
    justify-content: center;
}

.action-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.action-btn i {
    font-size: 14px;
}

.action-btn span {
    font-weight: 600;
}

.main-action-btn {
    font-size: 15px !important;
    padding: 12px 24px !important;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .time-slot {
        flex-direction: column;
        align-items: center;
        gap: 12px;
    }

    .time-label {
        width: 100%;
        margin-right: 0;
        margin-bottom: 8px;
    }

    .class-header {
        flex-direction: column;
        text-align: center;
        gap: 10px;
    }

    .subject-icon {
        align-self: center;
    }

    .schedule-info {
        justify-content: center;
    }

    .class-status {
        align-self: center;
    }

    .class-actions {
        text-align: center;
    }
}

@media (max-width: 576px) {
    .class-header {
        padding: 10px;
    }

    .subject-title {
        font-size: 14px;
    }

    .schedule-info {
        flex-direction: column;
        gap: 6px;
        align-items: center;
    }

    .class-actions {
        padding: 0 10px 10px 10px;
    }

    .action-btn {
        padding: 8px 16px;
        font-size: 13px;
    }
}
</style>
@endpush

@push('js-extra')
<script>
// Function to toggle weekly calendar visibility
function toggleWeeklyCalendar() {
    const calendarSection = document.getElementById('weeklyCalendar');
    const toggleBtn = document.getElementById('toggleCalendarBtn');
    const icon = toggleBtn ? toggleBtn.querySelector('.fas') : null;

    if (calendarSection) {
        if (calendarSection.style.display === 'none' || calendarSection.style.display === '') {
            // Show calendar
            console.log('Showing calendar inline');

            // Get current date from the display
            const currentDateDisplay = document.getElementById('current-date-display');
            let currentDate = new Date();

            if (currentDateDisplay) {
                const currentDateText = currentDateDisplay.textContent;
                const dateMatch = currentDateText.match(/(\d{1,2})\/(\d{1,2})\/(\d{4})/);

                if (dateMatch) {
                    const day = parseInt(dateMatch[1]);
                    const month = parseInt(dateMatch[2]) - 1;
                    const year = parseInt(dateMatch[3]);
                    currentDate = new Date(year, month, day);
                }
            }

            // Calculate start of week (Monday)
            const startOfWeek = new Date(currentDate);
            const dayOfWeek = startOfWeek.getDay();
            const diff = startOfWeek.getDate() - dayOfWeek + (dayOfWeek === 0 ? -6 : 1);
            startOfWeek.setDate(diff);

            // Create calendar content
            let calendarHtml = `
                <div id="weekly-calendar-container">
                    <div class="calendar-header">
                        <div class="calendar-header-day">Lun</div>
                        <div class="calendar-header-day">Mar</div>
                        <div class="calendar-header-day">Mié</div>
                        <div class="calendar-header-day">Jue</div>
                        <div class="calendar-header-day">Vie</div>
                        <div class="calendar-header-day">Sáb</div>
                        <div class="calendar-header-day">Dom</div>
                    </div>
                    <div class="weekly-calendar">
            `;

            // Generate 7 days
            for (let i = 0; i < 7; i++) {
                const dayDate = new Date(startOfWeek);
                dayDate.setDate(startOfWeek.getDate() + i);

                const isToday = dayDate.toDateString() === new Date().toDateString();
                const isSelected = dayDate.toDateString() === currentDate.toDateString();
                const dayNumber = dayDate.getDate();
                const dayName = dayDate.toLocaleDateString('es-ES', { weekday: 'short' });
                const dateString = `${dayDate.getFullYear()}-${String(dayDate.getMonth() + 1).padStart(2, '0')}-${String(dayDate.getDate()).padStart(2, '0')}`;

                // Mock attendance data - in production this would come from server
                const hasClasses = Math.random() > 0.5; // Increased probability to show more stats
                const completedClasses = hasClasses ? Math.floor(Math.random() * 3) + 1 : 0; // At least 1 if has classes
                const pendingClasses = hasClasses ? Math.floor(Math.random() * 2) + 1 : 0; // At least 1 if has classes

                let dayClasses = '';
                if (hasClasses) {
                    dayClasses = 'has-classes';
                    if (completedClasses > pendingClasses) {
                        dayClasses += ' completed';
                    } else if (pendingClasses > 0) {
                        dayClasses += ' pending';
                    }
                }

                calendarHtml += `
                    <div class="calendar-day ${isToday ? 'today' : ''} ${isSelected ? 'selected' : ''} ${dayClasses}"
                         data-date="${dateString}">
                        <div class="day-number">${dayNumber}</div>
                        <div class="day-name">${dayName}</div>
                        ${hasClasses ? `
                            <div class="day-stats">
                                <div class="day-stat completed">
                                    <div class="day-stat-number">${completedClasses}</div>
                                    <div class="day-stat-label">OK</div>
                                </div>
                                <div class="day-stat pending">
                                    <div class="day-stat-number">${pendingClasses}</div>
                                    <div class="day-stat-label">PEN</div>
                                </div>
                            </div>
                        ` : ''}
                    </div>
                `;
            }

            calendarHtml += `
                    </div>
                    <div class="calendar-navigation">
                        <div class="calendar-week-info">
                            Semana del ${startOfWeek.getDate()}/${startOfWeek.getMonth() + 1} al ${new Date(startOfWeek.getTime() + 6 * 24 * 60 * 60 * 1000).getDate()}/${new Date(startOfWeek.getTime() + 6 * 24 * 60 * 60 * 1000).getMonth() + 1}
                        </div>
                        <div>
                            <small class="text-muted">
                                <i class="fas fa-circle text-success"></i> Completado
                                <i class="fas fa-circle text-warning"></i> Pendiente
                                <i class="fas fa-circle text-info"></i> Con clases
                            </small>
                        </div>
                    </div>
                </div>
            `;

            // Replace the content and show
            calendarSection.innerHTML = calendarHtml;
            calendarSection.style.display = 'block';
            calendarSection.style.visibility = 'visible';
            calendarSection.style.opacity = '1';

            // Assign event listeners to calendar days
            asignarEventListenersCalendario();

            // Update button icon
            if (icon) {
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
            }
        } else {
            // Hide calendar
            console.log('Hiding calendar inline');
            calendarSection.style.display = 'none';
            calendarSection.style.visibility = 'hidden';

            // Update button icon
            if (icon) {
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
            }
        }
    } else {
        console.log('ERROR: Calendar section not found');
    }
}

// Function to assign event listeners to calendar days
function asignarEventListenersCalendario() {
    console.log('=== ASIGNANDO EVENT LISTENERS AL CALENDARIO ===');

    const calendarDays = document.querySelectorAll('.calendar-day');
    console.log('Encontrados días del calendario:', calendarDays.length);

    calendarDays.forEach((day, index) => {
        const date = day.getAttribute('data-date');
        console.log(`Configurando listener para día ${index + 1}, fecha: ${date}`);

        // Remove existing listeners to avoid duplicates
        day.removeEventListener('click', day._calendarClickHandler);

        // Create new click handler
        day._calendarClickHandler = function() {
            console.log('=== CLICK EN DÍA DEL CALENDARIO ===');
            console.log('Fecha del día clickeado:', date);
            if (date) {
                seleccionarDia(date);
            } else {
                console.error('ERROR: Día clickeado no tiene data-date');
            }
        };

        // Add the event listener
        day.addEventListener('click', day._calendarClickHandler);
        day.style.cursor = 'pointer';

        console.log(`Listener asignado al día ${index + 1}`);
    });

    console.log('=== EVENT LISTENERS ASIGNADOS ===');
}

document.addEventListener('DOMContentLoaded', function () {
    console.log('=== DOM LOADED - INICIALIZANDO PÁGINA DE ASISTENCIA ===');
    console.log('✅ CAMBIOS APLICADOS:');
    console.log('  - Cards del calendario más pequeños (aspect-ratio: 0.8)');
    console.log('  - Estadísticas OK/PEN visibles en cada día');
    console.log('  - Calendario se mantiene abierto al cambiar semana');
    console.log('=== INICIALIZACIÓN COMPLETA ===');

    // Initialize date click handler
    const dateDisplay = document.querySelector('.text-center.mx-4 h4');
    if (dateDisplay) {
        dateDisplay.addEventListener('click', function() {
            console.log('Date clicked, toggling calendar...');
            toggleWeeklyCalendar();
        });
    }

    // Initialize calendar button
    const calendarBtn = document.getElementById('toggleCalendarBtn');
    if (calendarBtn) {
        calendarBtn.addEventListener('click', function() {
            console.log('Calendar button clicked...');
            toggleWeeklyCalendar();
        });
    }

    // Initialize attendance forms
    document.querySelectorAll('.attendance-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            guardarAsistenciaForm(this);
        });
    });

    // Initialize attendance options
    document.querySelectorAll('.attendance-option').forEach(option => {
        option.addEventListener('click', function() {
            const radio = this.querySelector('input[type="radio"]');
            if (radio) {
                radio.checked = true;
                const sesionId = radio.getAttribute('data-sesion-id');
                updateAttendanceOptionVisuals(sesionId);
            }
        });
    });

    // Load calendar initially since it's now visible by default
    console.log('Loading calendar initially...');
    // Since calendar is already visible (display: block), just load the content
    const calendarSection = document.getElementById('weeklyCalendar');
    if (calendarSection) {
        // Get current date from the display
        const currentDateDisplay = document.getElementById('current-date-display');
        let currentDate = new Date();

        if (currentDateDisplay) {
            const currentDateText = currentDateDisplay.textContent;
            const dateMatch = currentDateText.match(/(\d{1,2})\/(\d{1,2})\/(\d{4})/);

            if (dateMatch) {
                const day = parseInt(dateMatch[1]);
                const month = parseInt(dateMatch[2]) - 1;
                const year = parseInt(dateMatch[3]);
                currentDate = new Date(year, month, day);
            }
        }

        // Calculate start of week (Monday)
        const startOfWeek = new Date(currentDate);
        const dayOfWeek = startOfWeek.getDay();
        const diff = startOfWeek.getDate() - dayOfWeek + (dayOfWeek === 0 ? -6 : 1);
        startOfWeek.setDate(diff);

        // Create calendar content
        let calendarHtml = `
            <div id="weekly-calendar-container">
                <div class="calendar-header">
                    <div class="calendar-header-day">Lun</div>
                    <div class="calendar-header-day">Mar</div>
                    <div class="calendar-header-day">Mié</div>
                    <div class="calendar-header-day">Jue</div>
                    <div class="calendar-header-day">Vie</div>
                    <div class="calendar-header-day">Sáb</div>
                    <div class="calendar-header-day">Dom</div>
                </div>
                <div class="weekly-calendar">
        `;

        // Generate 7 days
        for (let i = 0; i < 7; i++) {
            const dayDate = new Date(startOfWeek);
            dayDate.setDate(startOfWeek.getDate() + i);

            const isToday = dayDate.toDateString() === new Date().toDateString();
            const isSelected = dayDate.toDateString() === currentDate.toDateString();
            const dayNumber = dayDate.getDate();
            const dayName = dayDate.toLocaleDateString('es-ES', { weekday: 'short' });
            const dateString = `${dayDate.getFullYear()}-${String(dayDate.getMonth() + 1).padStart(2, '0')}-${String(dayDate.getDate()).padStart(2, '0')}`;

            // Mock attendance data - in production this would come from server
            const hasClasses = Math.random() > 0.5; // Increased probability to show more stats
            const completedClasses = hasClasses ? Math.floor(Math.random() * 3) + 1 : 0; // At least 1 if has classes
            const pendingClasses = hasClasses ? Math.floor(Math.random() * 2) + 1 : 0; // At least 1 if has classes

            let dayClasses = '';
            if (hasClasses) {
                dayClasses = 'has-classes';
                if (completedClasses > pendingClasses) {
                    dayClasses += ' completed';
                } else if (pendingClasses > 0) {
                    dayClasses += ' pending';
                }
            }

            calendarHtml += `
                <div class="calendar-day ${isToday ? 'today' : ''} ${isSelected ? 'selected' : ''} ${dayClasses}"
                     data-date="${dateString}">
                    <div class="day-number">${dayNumber}</div>
                    <div class="day-name">${dayName}</div>
                    ${hasClasses ? `
                        <div class="day-stats">
                            <div class="day-stat completed">
                                <div class="day-stat-number">${completedClasses}</div>
                                <div class="day-stat-label">OK</div>
                            </div>
                            <div class="day-stat pending">
                                <div class="day-stat-number">${pendingClasses}</div>
                                <div class="day-stat-label">PEN</div>
                            </div>
                        </div>
                    ` : ''}
                </div>
            `;
        }

        calendarHtml += `
                </div>
                <div class="calendar-navigation">
                    <div class="calendar-week-info">
                        Semana del ${startOfWeek.getDate()}/${startOfWeek.getMonth() + 1} al ${new Date(startOfWeek.getTime() + 6 * 24 * 60 * 60 * 1000).getDate()}/${new Date(startOfWeek.getTime() + 6 * 24 * 60 * 60 * 1000).getMonth() + 1}
                    </div>
                    <div>
                        <small class="text-muted">
                            <i class="fas fa-circle text-success"></i> Completado
                            <i class="fas fa-circle text-warning"></i> Pendiente
                            <i class="fas fa-circle text-info"></i> Con clases
                        </small>
                    </div>
                </div>
            </div>
        `;

        // Replace the content and show
        calendarSection.innerHTML = calendarHtml;
        calendarSection.style.display = 'block';
        calendarSection.style.visibility = 'visible';
        calendarSection.style.opacity = '1';

        // Assign event listeners to calendar days
        asignarEventListenersCalendario();

        // Update button icon to show it's expanded
        const toggleBtn = document.getElementById('toggleCalendarBtn');
        const icon = toggleBtn ? toggleBtn.querySelector('.fas') : null;
        if (icon) {
            icon.classList.remove('fa-chevron-down');
            icon.classList.add('fa-chevron-up');
        }

        console.log('Calendar loaded and visible with stats!');
    }
});

// Function to close calendar modal
function cerrarCalendarioModal() {
    const modal = document.getElementById('calendar-modal');
    if (modal) {
        modal.remove();
    }
}

// Function to toggle class details panel
function toggleClassDetails(sesionId) {
    const panel = document.getElementById(`attendance-panel-${sesionId}`);
    if (panel) {
        if (panel.style.display === 'none' || panel.style.display === '') {
            panel.style.display = 'block';
        } else {
            panel.style.display = 'none';
        }
    }
}

// Function for quick attendance marking - redirects to specific attendance view
function marcarAsistenciaRapida(sesionId) {
    console.log('=== REDIRIGIENDO A VISTA DE TOMA DE ASISTENCIA ===');
    console.log('Sesión ID:', sesionId);

    // Redirect to the specific attendance taking view for this session
    const url = `{{ route('asistencia.docente.tomar-asistencia') }}?sesion=${sesionId}`;
    console.log('Redirecting to:', url);
    window.location.href = url;
}

// Function to update attendance option visuals
function updateAttendanceOptionVisuals(sesionId) {
    // Remove active class from all options in this session
    const allOptions = document.querySelectorAll(`input[data-sesion-id="${sesionId}"]`);
    allOptions.forEach(input => {
        const option = input.closest('.attendance-option');
        if (option) {
            option.classList.remove('active', 'present', 'absent', 'late', 'justified');
        }
    });

    // Add active class to checked options
    const checkedRadios = document.querySelectorAll(`input[data-sesion-id="${sesionId}"]:checked`);
    checkedRadios.forEach(radio => {
        const option = radio.closest('.attendance-option');
        if (option) {
            const type = radio.value.toLowerCase();
            option.classList.add('active', type);
        }
    });
}

// Function to mark all students as present for a specific class
function marcarTodosPresentes(sesionId) {
    const radios = document.querySelectorAll(`input[name^="asistencia_${sesionId}_"][value="P"]`);
    radios.forEach(radio => {
        radio.checked = true;
    });

    // Show success message
    Swal.fire({
        icon: 'success',
        title: '¡Listo!',
        text: 'Todos los estudiantes marcados como presentes',
        timer: 1500,
        showConfirmButton: false
    });
}

// Function to save attendance for a specific form
function guardarAsistenciaForm(form) {
    const sesionId = form.getAttribute('data-sesion-id');
    const asistencias = [];
    let hasSelections = false;

    // Collect attendance data
    const radios = form.querySelectorAll('input[type="radio"]:checked');
    radios.forEach(radio => {
        hasSelections = true;
        const matriculaId = radio.getAttribute('data-matricula-id');
        const tipoAsistencia = radio.value;

        asistencias.push({
            matricula_id: matriculaId,
            tipo_asistencia: tipoAsistencia,
            observaciones: null
        });
    });

    if (!hasSelections) {
        Swal.fire({
            icon: 'warning',
            title: 'Atención',
            text: 'Debes seleccionar al menos un tipo de asistencia para un estudiante.'
        });
        return;
    }

    // Get general observations
    const observacionesGenerales = form.querySelector(`#observaciones_${sesionId}`).value;

    // Show loading state
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalHtml = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
    submitBtn.disabled = true;

    // Send data to server
    fetch('{{ route("asistencia.docente.guardar") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            sesion_clase_id: sesionId,
            asistencias: asistencias,
            observaciones_generales: observacionesGenerales
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: data.message,
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                // Reload the page to show updated status
                location.reload();
            });
        } else {
            // Show error message
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'Error al guardar la asistencia'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error al guardar la asistencia. Inténtalo de nuevo.'
        });
    })
    .finally(() => {
        // Restore button state
        submitBtn.innerHTML = originalHtml;
        submitBtn.disabled = false;
    });
}

// Function to view attendance details
function verAsistencia(sesionId) {
    window.location.href = '{{ route("asistencia.docente.ver", ":id") }}'.replace(':id', sesionId);
}

// Function to apply filters to student list
function aplicarFiltros(sesionId) {
    const searchInput = document.getElementById(`search_${sesionId}`);
    const filterSelect = document.getElementById(`filter_${sesionId}`);
    const attendanceGrid = document.getElementById(`attendance-grid-${sesionId}`);

    if (!attendanceGrid) return;

    const searchTerm = searchInput ? searchInput.value.toLowerCase().trim() : '';
    const filterValue = filterSelect ? filterSelect.value : 'all';

    const studentCards = attendanceGrid.querySelectorAll('.student-attendance-card');
    let visibleCount = 0;
    let totalCount = studentCards.length;

    studentCards.forEach((card, index) => {
        const studentName = card.getAttribute('data-student-name') || '';
        const attendanceType = card.getAttribute('data-attendance-type') || 'present';

        // Apply search filter
        const matchesSearch = searchTerm === '' ||
            studentName.includes(searchTerm) ||
            studentName.replace(/\s+/g, '').includes(searchTerm.replace(/\s+/g, ''));

        // Apply attendance filter
        let matchesFilter = true;
        switch(filterValue) {
            case 'present':
                matchesFilter = attendanceType === 'present';
                break;
            case 'absent':
                matchesFilter = attendanceType === 'absent';
                break;
            case 'late':
                matchesFilter = attendanceType === 'late';
                break;
            case 'justified':
                matchesFilter = attendanceType === 'justified';
                break;
            case 'unmarked':
                // This would require checking if attendance was actually saved
                // For now, show all since we're in the marking phase
                matchesFilter = true;
                break;
            case 'all':
            default:
                matchesFilter = true;
                break;
        }

        // Show/hide card based on filters
        if (matchesSearch && matchesFilter) {
            card.style.display = '';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });

    // Update student count display
    actualizarConteoEstudiantes(sesionId, visibleCount, totalCount);
}

// Function to clear all filters for a specific class
function limpiarFiltros(sesionId) {
    const searchInput = document.getElementById(`search_${sesionId}`);
    const filterSelect = document.getElementById(`filter_${sesionId}`);

    if (searchInput) {
        searchInput.value = '';
    }

    if (filterSelect) {
        filterSelect.value = 'all';
    }

    // Re-apply filters (which will show all students)
    aplicarFiltros(sesionId);
}

// Function to update student count display
function actualizarConteoEstudiantes(sesionId, visibleCount = null, totalCount = null) {
    const countElement = document.getElementById(`student-count-${sesionId}`);

    if (!countElement) return;

    if (visibleCount === null || totalCount === null) {
        // Calculate counts if not provided
        const attendanceGrid = document.getElementById(`attendance-grid-${sesionId}`);
        if (attendanceGrid) {
            const cards = attendanceGrid.querySelectorAll('.student-attendance-card');
            totalCount = cards.length;
            visibleCount = Array.from(cards).filter(card => card.style.display !== 'none').length;
        } else {
            visibleCount = 0;
            totalCount = 0;
        }
    }

    if (visibleCount === totalCount) {
        countElement.textContent = `Mostrando ${totalCount} estudiante${totalCount !== 1 ? 's' : ''}`;
    } else {
        countElement.textContent = `Mostrando ${visibleCount} de ${totalCount} estudiante${totalCount !== 1 ? 's' : ''}`;
    }
}

// Function to show quick stats for a class
function mostrarEstadisticasRapidas(sesionId) {
    const form = document.querySelector(`form[data-sesion-id="${sesionId}"]`);
    const radios = form.querySelectorAll('input[type="radio"]:checked');

    let presentes = 0, ausentes = 0, tardes = 0, justificados = 0;

    radios.forEach(radio => {
        switch(radio.value) {
            case 'P': presentes++; break;
            case 'A': ausentes++; break;
            case 'T': tardes++; break;
            case 'J': justificados++; break;
        }
    });

    const total = radios.length;
    const porcentajeAsistencia = total > 0 ? Math.round(((presentes + justificados) / total) * 100) : 0;

    Swal.fire({
        title: 'Estadísticas Rápidas',
        html: `
            <div class="text-center">
                <div class="row">
                    <div class="col-6">
                        <div class="p-2 bg-success text-white rounded mb-2">
                            <div class="h4 mb-0">${presentes}</div>
                            <small>Presentes</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 bg-danger text-white rounded mb-2">
                            <div class="h4 mb-0">${ausentes}</div>
                            <small>Ausentes</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 bg-warning text-white rounded mb-2">
                            <div class="h4 mb-0">${tardes}</div>
                            <small>Tardes</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 bg-info text-white rounded mb-2">
                            <div class="h4 mb-0">${justificados}</div>
                            <small>Justificados</small>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="h5 text-primary">${porcentajeAsistencia}% Asistencia</div>
                <small class="text-muted">Total estudiantes: ${total}</small>
            </div>
        `,
        showConfirmButton: false,
        showCloseButton: true
    });
}

// Function to change day (previous/next)
function cambiarDia(dias) {
    // Get current date from the display
    const currentDateDisplay = document.getElementById('current-date-display');
    if (!currentDateDisplay) return;

    // Parse current date (assuming format: "día, dd/mm/yyyy")
    const currentDateText = currentDateDisplay.textContent;
    const dateMatch = currentDateText.match(/(\d{1,2})\/(\d{1,2})\/(\d{4})/);

    if (dateMatch) {
        const day = parseInt(dateMatch[1]);
        const month = parseInt(dateMatch[2]) - 1; // JavaScript months are 0-based
        const year = parseInt(dateMatch[3]);

        const currentDate = new Date(year, month, day);
        currentDate.setDate(currentDate.getDate() + dias);

        // Format new date
        const newDay = currentDate.getDate();
        const newMonth = currentDate.getMonth() + 1;
        const newYear = currentDate.getFullYear();

        // Navigate to new date
        const newDateStr = `${newYear}-${String(newMonth).padStart(2, '0')}-${String(newDay).padStart(2, '0')}`;
        window.location.href = `{{ route('asistencia.docente.tomar-asistencia') }}?fecha=${newDateStr}`;
    }
}



// Function to load weekly calendar
function cargarCalendarioSemanal() {
    const container = document.getElementById('weekly-calendar-container');
    if (!container) return;

    // Get current date
    const currentDateDisplay = document.getElementById('current-date-display');
    let currentDate = new Date();

    if (currentDateDisplay) {
        const currentDateText = currentDateDisplay.textContent;
        const dateMatch = currentDateText.match(/(\d{1,2})\/(\d{1,2})\/(\d{4})/);

        if (dateMatch) {
            const day = parseInt(dateMatch[1]);
            const month = parseInt(dateMatch[2]) - 1;
            const year = parseInt(dateMatch[3]);
            currentDate = new Date(year, month, day);
        }
    }

    // Calculate start of week (Monday)
    const startOfWeek = new Date(currentDate);
    const dayOfWeek = startOfWeek.getDay();
    const diff = startOfWeek.getDate() - dayOfWeek + (dayOfWeek === 0 ? -6 : 1); // Adjust for Sunday
    startOfWeek.setDate(diff);

    // Create calendar HTML
    let calendarHtml = `
        <div class="calendar-header">
            <div class="calendar-header-day">Lun</div>
            <div class="calendar-header-day">Mar</div>
            <div class="calendar-header-day">Mié</div>
            <div class="calendar-header-day">Jue</div>
            <div class="calendar-header-day">Vie</div>
            <div class="calendar-header-day">Sáb</div>
            <div class="calendar-header-day">Dom</div>
        </div>
        <div class="weekly-calendar">
    `;

    // Generate 7 days
    for (let i = 0; i < 7; i++) {
        const dayDate = new Date(startOfWeek);
        dayDate.setDate(startOfWeek.getDate() + i);

        const isToday = dayDate.toDateString() === new Date().toDateString();
        const isSelected = dayDate.toDateString() === currentDate.toDateString();
        const dayNumber = dayDate.getDate();
        const dayName = dayDate.toLocaleDateString('es-ES', { weekday: 'short' });

        // Mock attendance data (in real implementation, this would come from server)
        const hasClasses = Math.random() > 0.3; // Random for demo
        const completedClasses = hasClasses ? Math.floor(Math.random() * 3) : 0;
        const pendingClasses = hasClasses ? Math.floor(Math.random() * 2) : 0;

        let dayClasses = '';
        if (hasClasses) {
            dayClasses = 'has-classes';
            if (completedClasses > pendingClasses) {
                dayClasses += ' completed';
            } else if (pendingClasses > 0) {
                dayClasses += ' pending';
            }
        }

        calendarHtml += `
            <div class="calendar-day ${isToday ? 'today' : ''} ${isSelected ? 'selected' : ''} ${dayClasses}"
                 data-date="${dayDate.getFullYear()}-${String(dayDate.getMonth() + 1).padStart(2, '0')}-${String(dayDate.getDate()).padStart(2, '0')}">
                <div class="day-number">${dayNumber}</div>
                <div class="day-name">${dayName}</div>
                ${hasClasses ? `
                    <div class="day-stats">
                        <div class="day-stat completed">
                            <div class="day-stat-number">${completedClasses}</div>
                            <div class="day-stat-label">OK</div>
                        </div>
                        <div class="day-stat pending">
                            <div class="day-stat-number">${pendingClasses}</div>
                            <div class="day-stat-label">PEN</div>
                        </div>
                    </div>
                ` : ''}
            </div>
        `;
    }

    calendarHtml += `
        </div>
        <div class="calendar-navigation">
            <div class="calendar-week-info">
                Semana del ${startOfWeek.getDate()}/${startOfWeek.getMonth() + 1} al ${new Date(startOfWeek.getTime() + 6 * 24 * 60 * 60 * 1000).getDate()}/${new Date(startOfWeek.getTime() + 6 * 24 * 60 * 60 * 1000).getMonth() + 1}
            </div>
            <div>
                <small class="text-muted">
                    <i class="fas fa-circle text-success"></i> Completado
                    <i class="fas fa-circle text-warning"></i> Pendiente
                    <i class="fas fa-circle text-info"></i> Con clases
                </small>
            </div>
        </div>
    `;

    container.innerHTML = calendarHtml;

    // Add click event listeners to calendar days
    const calendarDays = container.querySelectorAll('.calendar-day');
    console.log('=== ASIGNANDO EVENT LISTENERS ===');
    console.log('Found calendar days for event listeners:', calendarDays.length);
    calendarDays.forEach((day, index) => {
        const date = day.getAttribute('data-date');
        console.log(`Setting up click listener for day ${index + 1}, date: ${date}`);
        day.addEventListener('click', function() {
            console.log('=== DÍA DEL CALENDARIO CLICKEADO ===');
            console.log('Clicked day date:', date);
            if (date) {
                seleccionarDia(date);
            } else {
                console.error('ERROR: Clicked day has no data-date attribute');
            }
        });
        // Add visual feedback
        day.style.cursor = 'pointer';
        console.log(`Event listener assigned to day ${index + 1}`);
    });
    console.log('=== EVENT LISTENERS ASIGNADOS ===');
}

// Function to select a day from calendar
function seleccionarDia(fecha) {
    console.log('=== SELECCIONANDO FECHA ===');
    console.log('Fecha seleccionada:', fecha);

    // Update calendar visual selection immediately
    actualizarSeleccionCalendario(fecha);

    // Show loading state
    const attendanceSection = document.getElementById('collapseTomarAsistencia');
    if (attendanceSection) {
        attendanceSection.innerHTML = `
            <div class="text-center py-5">
                <i class="fas fa-spinner fa-spin fa-3x text-primary mb-3"></i>
                <h4>Cargando horario de clases...</h4>
                <p class="text-muted">Por favor espera mientras se carga el horario para la fecha seleccionada.</p>
            </div>
        `;
    }

    // Make AJAX request to get attendance data for selected date
    const url = `{{ route('asistencia.docente.tomar-asistencia') }}?fecha=${fecha}`;
    console.log('Making AJAX request to:', url);

    fetch(url, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.text();
    })
    .then(html => {
        console.log('HTML response received, parsing...');

        // Parse the HTML to extract the content we need to update
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');

        // Update the date display in the header
        const newDateDisplay = doc.querySelector('#current-date-display');
        if (newDateDisplay) {
            const currentDateDisplay = document.getElementById('current-date-display');
            if (currentDateDisplay) {
                currentDateDisplay.textContent = newDateDisplay.textContent;
            }
        }

        // Update the main date display in the navigation section
        const newMainDateDisplay = doc.querySelector('.text-center.mx-4 h4');
        if (newMainDateDisplay) {
            const currentMainDateDisplay = document.querySelector('.text-center.mx-4 h4');
            if (currentMainDateDisplay) {
                // Keep the click functionality and icon, just update the text
                const icon = currentMainDateDisplay.querySelector('i');
                const small = currentMainDateDisplay.querySelector('small');
                currentMainDateDisplay.innerHTML = '';
                currentMainDateDisplay.textContent = newMainDateDisplay.textContent;
                if (icon) currentMainDateDisplay.appendChild(icon);
                if (small) currentMainDateDisplay.appendChild(small);
            }
        }

        // Extract and update the attendance content
        const newAttendanceContent = doc.querySelector('#collapseTomarAsistencia');
        if (newAttendanceContent && attendanceSection) {
            attendanceSection.innerHTML = newAttendanceContent.innerHTML;

            // Re-initialize event listeners for the new content
            initializeAttendanceEvents();

            // Scroll to the attendance section
            setTimeout(() => {
                attendanceSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }, 300);
        }

        console.log('Horario de clases actualizado dinámicamente para fecha:', fecha);
    })
    .catch(error => {
        console.error('Error al cargar horario de clases:', error);
        if (attendanceSection) {
            attendanceSection.innerHTML = `
                <div class="alert alert-danger text-center">
                    <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                    <h5>Error al cargar el horario de clases</h5>
                    <p>Error: ${error.message}</p>
                    <button class="btn btn-primary" onclick="location.reload()">Recargar página</button>
                </div>
            `;
        }
    });
}

// Function to assign event listeners to calendar days
function asignarEventListenersCalendario() {
    console.log('=== ASIGNANDO EVENT LISTENERS AL CALENDARIO ===');

    const calendarDays = document.querySelectorAll('.calendar-day');
    console.log('Encontrados días del calendario:', calendarDays.length);

    calendarDays.forEach((day, index) => {
        const date = day.getAttribute('data-date');
        console.log(`Configurando listener para día ${index + 1}, fecha: ${date}`);

        // Remove existing listeners to avoid duplicates
        day.removeEventListener('click', day._calendarClickHandler);

        // Create new click handler
        day._calendarClickHandler = function() {
            console.log('=== CLICK EN DÍA DEL CALENDARIO ===');
            console.log('Fecha del día clickeado:', date);
            if (date) {
                seleccionarDia(date);
            } else {
                console.error('ERROR: Día clickeado no tiene data-date');
            }
        };

        // Add the event listener
        day.addEventListener('click', day._calendarClickHandler);
        day.style.cursor = 'pointer';

        console.log(`Listener asignado al día ${index + 1}`);
    });

    console.log('=== EVENT LISTENERS ASIGNADOS ===');
}

// Function to update calendar visual selection
function actualizarSeleccionCalendario(fechaSeleccionada) {
    console.log('Actualizando selección visual del calendario para fecha:', fechaSeleccionada);

    // Remove selected class from all calendar days
    const allCalendarDays = document.querySelectorAll('.calendar-day');
    allCalendarDays.forEach(day => {
        day.classList.remove('selected');
    });

    // Add selected class to the clicked day
    const selectedDay = document.querySelector(`.calendar-day[data-date="${fechaSeleccionada}"]`);
    if (selectedDay) {
        selectedDay.classList.add('selected');
        console.log('Día seleccionado visualmente:', fechaSeleccionada);
    } else {
        console.log('No se encontró el día en el calendario:', fechaSeleccionada);
    }
}

// Function to initialize event listeners for attendance section
function initializeAttendanceEvents() {
    // Re-initialize form submissions
    document.querySelectorAll('.attendance-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            guardarAsistenciaForm(this);
        });
    });

    // Re-initialize filters
    document.querySelectorAll('.attendance-filter').forEach(filter => {
        const sesionId = filter.getAttribute('data-sesion-id');
        filter.addEventListener('change', function() {
            aplicarFiltros(sesionId);
        });
    });

    // Re-initialize search
    document.querySelectorAll('.student-search').forEach(search => {
        const sesionId = search.getAttribute('data-sesion-id');
        search.addEventListener('input', function() {
            aplicarFiltros(sesionId);
        });
    });

    // Re-initialize attendance radio buttons
    document.querySelectorAll('.attendance-radio').forEach(radio => {
        radio.addEventListener('change', function() {
            const sesionId = this.getAttribute('data-sesion-id');
            const matriculaId = this.getAttribute('data-matricula-id');
            const attendanceType = this.value.toLowerCase();

            const row = this.closest('tr');
            row.setAttribute('data-attendance-type', attendanceType);

            const filterSelect = document.getElementById(`filter_${sesionId}`);
            if (filterSelect && filterSelect.value !== 'all') {
                aplicarFiltros(sesionId);
            }
        });
    });

    // Re-initialize student counts
    document.querySelectorAll('.attendance-grid').forEach(grid => {
        const sesionId = grid.id.replace('attendance-grid-', '');
        actualizarConteoEstudiantes(sesionId);
    });

    // Re-initialize attendance option click handlers
    document.querySelectorAll('.attendance-option').forEach(option => {
        option.addEventListener('click', function() {
            const radio = this.querySelector('input[type="radio"]');
            if (radio) {
                radio.checked = true;

                const sesionId = radio.getAttribute('data-sesion-id');
                updateAttendanceOptionVisuals(sesionId);

                radio.dispatchEvent(new Event('change'));
            }
        });
    });

    console.log('Eventos de asistencia re-inicializados');
}

// Function to change week (previous/next) with AJAX
function cambiarSemana(direccion) {
    console.log('=== CAMBIANDO SEMANA CON AJAX ===');
    console.log('Dirección:', direccion);

    // Get current date from the display
    const currentDateDisplay = document.getElementById('current-date-display');
    if (!currentDateDisplay) return;

    // Parse current date (assuming format: "día, dd/mm/yyyy")
    const currentDateText = currentDateDisplay.textContent;
    const dateMatch = currentDateText.match(/(\d{1,2})\/(\d{1,2})\/(\d{4})/);

    if (dateMatch) {
        const day = parseInt(dateMatch[1]);
        const month = parseInt(dateMatch[2]) - 1; // JavaScript months are 0-based
        const year = parseInt(dateMatch[3]);

        const currentDate = new Date(year, month, day);

        // Calculate start of current week (Monday)
        const dayOfWeek = currentDate.getDay();
        const diff = currentDate.getDate() - dayOfWeek + (dayOfWeek === 0 ? -6 : 1);
        const startOfCurrentWeek = new Date(currentDate);
        startOfCurrentWeek.setDate(diff);

        // Calculate new week start
        const newWeekStart = new Date(startOfCurrentWeek);
        newWeekStart.setDate(startOfCurrentWeek.getDate() + (direccion * 7));

        // Navigate to the first day of the new week
        const newDay = newWeekStart.getDate();
        const newMonth = newWeekStart.getMonth() + 1;
        const newYear = newWeekStart.getFullYear();

        const newDateStr = `${newYear}-${String(newMonth).padStart(2, '0')}-${String(newDay).padStart(2, '0')}`;
        console.log('Cambiando a nueva semana, fecha:', newDateStr);

        // Show loading state for calendar
        const calendarContainer = document.getElementById('weekly-calendar-container');
        if (calendarContainer) {
            calendarContainer.innerHTML = `
                <div class="text-center py-4">
                    <i class="fas fa-spinner fa-spin fa-2x text-primary mb-2"></i>
                    <p class="text-muted">Cargando nueva semana...</p>
                </div>
            `;
        }

        // Make AJAX request to get data for new week
        const url = `{{ route('asistencia.docente.tomar-asistencia') }}?fecha=${newDateStr}`;

        fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.text();
        })
        .then(html => {
            console.log('HTML response received for new week, parsing...');

            // Parse the HTML to extract the content we need to update
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');

            // Update the date display in the header
            const newDateDisplay = doc.querySelector('#current-date-display');
            if (newDateDisplay) {
                const currentDateDisplay = document.getElementById('current-date-display');
                if (currentDateDisplay) {
                    currentDateDisplay.textContent = newDateDisplay.textContent;
                }
            }

            // Update the main date display in the navigation section
            const newMainDateDisplay = doc.querySelector('.text-center.mx-4 h4');
            if (newMainDateDisplay) {
                const currentMainDateDisplay = document.querySelector('.text-center.mx-4 h4');
                if (currentMainDateDisplay) {
                    // Keep the click functionality and icon, just update the text
                    const icon = currentMainDateDisplay.querySelector('i');
                    const small = currentMainDateDisplay.querySelector('small');
                    currentMainDateDisplay.innerHTML = '';
                    currentMainDateDisplay.textContent = newMainDateDisplay.textContent;
                    if (icon) currentMainDateDisplay.appendChild(icon);
                    if (small) currentMainDateDisplay.appendChild(small);
                }
            }

            // Update the attendance content
            const newAttendanceContent = doc.querySelector('#collapseTomarAsistencia');
            if (newAttendanceContent) {
                const attendanceSection = document.getElementById('collapseTomarAsistencia');
                if (attendanceSection) {
                    attendanceSection.innerHTML = newAttendanceContent.innerHTML;

                    // Re-initialize event listeners for the new content
                    initializeAttendanceEvents();
                }
            }

            // Update the calendar content
            const newCalendarContent = doc.querySelector('#weekly-calendar-container');
            if (newCalendarContent && calendarContainer) {
                calendarContainer.innerHTML = newCalendarContent.innerHTML;

                // Re-assign event listeners to calendar days
                asignarEventListenersCalendario();

                // Automatically show the calendar since we're changing weeks
                const calendarSection = document.getElementById('weeklyCalendar');
                if (calendarSection && calendarSection.style.display === 'none') {
                    calendarSection.style.display = 'block';
                    calendarSection.style.visibility = 'visible';
                    calendarSection.style.opacity = '1';

                    // Update button icon
                    const toggleBtn = document.getElementById('toggleCalendarBtn');
                    const icon = toggleBtn ? toggleBtn.querySelector('.fas') : null;
                    if (icon) {
                        icon.classList.remove('fa-chevron-down');
                        icon.classList.add('fa-chevron-up');
                    }
                }
            }

            console.log('Semana cambiada dinámicamente a fecha:', newDateStr);
        })
        .catch(error => {
            console.error('Error al cambiar semana:', error);
            if (calendarContainer) {
                calendarContainer.innerHTML = `
                    <div class="alert alert-danger text-center">
                        <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                        <h6>Error al cargar la nueva semana</h6>
                        <p class="small">${error.message}</p>
                        <button class="btn btn-sm btn-primary" onclick="location.reload()">Recargar</button>
                    </div>
                `;
            }
        });
    } else {
        console.error('No se pudo parsear la fecha actual:', currentDateText);
    }
}
</script>
@endpush
