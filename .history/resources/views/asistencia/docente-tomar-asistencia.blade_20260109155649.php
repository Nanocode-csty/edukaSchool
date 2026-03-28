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
                @if(isset($modo) && $modo === 'sesion')
                    <!-- Vista específica para tomar asistencia de una sesión -->
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-0">
                                        <i class="fas fa-edit"></i> Tomar Asistencia
                                    </h5>
                                    <small>{{ $sesion->cursoAsignatura->asignatura->nombre }} - {{ $sesion->cursoAsignatura->curso->grado->nombre }} {{ $sesion->cursoAsignatura->curso->seccion->nombre }}</small>
                                </div>
                                <div class="text-right">
                                    <div class="mb-1">
                                        <small class="text-white-50">Fecha:</small>
                                        <strong>{{ $sesion->fecha->locale('es')->dayName }}, {{ $sesion->fecha->format('d/m/Y') }}</strong>
                                    </div>
                                    <div>
                                        <small class="text-white-50">Horario:</small>
                                        <strong>{{ substr($sesion->hora_inicio, 0, 5) }} - {{ substr($sesion->hora_fin, 0, 5) }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Información de la sesión -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="card border-info">
                                        <div class="card-body text-center">
                                            <h6 class="text-info mb-2">
                                                <i class="fas fa-users"></i> Estudiantes Totales
                                            </h6>
                                            <h3 class="text-primary">{{ $estadisticas_sesion['total_estudiantes'] }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card border-success">
                                        <div class="card-body text-center">
                                            <h6 class="text-success mb-2">
                                                <i class="fas fa-check-circle"></i> Asistencias Registradas
                                            </h6>
                                            <h3 class="text-success">{{ $estadisticas_sesion['asistencias_registradas'] }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Formulario de asistencia -->
                            <form id="form-asistencia-sesion" class="attendance-form">
                                <input type="hidden" name="sesion_clase_id" value="{{ $sesion->id }}">

                                <div class="attendance-grid">
                                    @foreach($estudiantes as $estudiante)
                                    <div class="student-attendance-card student-row"
                                         data-student-name="{{ strtolower($estudiante->estudiante->persona->nombres . ' ' . $estudiante->estudiante->persona->apellidos) }}"
                                         data-attendance-type="{{ $estudiante->asistencia_actual }}">
                                        <div class="student-header">
                                            <div class="student-avatar">
                                                <div class="avatar-circle">
                                                    {{ substr($estudiante->estudiante->persona->nombres, 0, 1) }}{{ substr($estudiante->estudiante->persona->apellidos, 0, 1) }}
                                                </div>
                                            </div>
                                            <div class="student-info">
                                                <div class="student-name">{{ $estudiante->estudiante->persona->nombres }}</div>
                                                <div class="student-lastname">{{ $estudiante->estudiante->persona->apellidos }}</div>
                                            </div>
                                        </div>
                                        <div class="attendance-options">
                                            <label class="attendance-option present {{ $estudiante->asistencia_actual === 'P' ? 'active' : '' }}">
                                                <input type="radio" name="asistencia_{{ $estudiante->matricula_id }}" value="P"
                                                       {{ $estudiante->asistencia_actual === 'P' ? 'checked' : '' }}
                                                       class="attendance-radio" data-matricula-id="{{ $estudiante->matricula_id }}">
                                                <i class="fas fa-check-circle"></i>
                                                <span>Presente</span>
                                            </label>
                                            <label class="attendance-option absent {{ $estudiante->asistencia_actual === 'A' ? 'active' : '' }}">
                                                <input type="radio" name="asistencia_{{ $estudiante->matricula_id }}" value="A"
                                                       {{ $estudiante->asistencia_actual === 'A' ? 'checked' : '' }}
                                                       class="attendance-radio" data-matricula-id="{{ $estudiante->matricula_id }}">
                                                <i class="fas fa-times-circle"></i>
                                                <span>Ausente</span>
                                            </label>
                                            <label class="attendance-option late {{ $estudiante->asistencia_actual === 'T' ? 'active' : '' }}">
                                                <input type="radio" name="asistencia_{{ $estudiante->matricula_id }}" value="T"
                                                       {{ $estudiante->asistencia_actual === 'T' ? 'checked' : '' }}
                                                       class="attendance-radio" data-matricula-id="{{ $estudiante->matricula_id }}">
                                                <i class="fas fa-clock"></i>
                                                <span>Tarde</span>
                                            </label>
                                            <label class="attendance-option justified {{ $estudiante->asistencia_actual === 'J' ? 'active' : '' }}">
                                                <input type="radio" name="asistencia_{{ $estudiante->matricula_id }}" value="J"
                                                       {{ $estudiante->asistencia_actual === 'J' ? 'checked' : '' }}
                                                       class="attendance-radio" data-matricula-id="{{ $estudiante->matricula_id }}">
                                                <i class="fas fa-file-medical"></i>
                                                <span>Justificado</span>
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                <!-- Observaciones generales -->
                                <div class="mt-4">
                                    <div class="form-group">
                                        <label for="observaciones_generales">
                                            <i class="fas fa-comment"></i> Observaciones generales (opcional):
                                        </label>
                                        <textarea class="form-control" id="observaciones_generales" name="observaciones_generales"
                                                  rows="3" placeholder="Observaciones para toda la clase..."></textarea>
                                    </div>
                                </div>

                                <!-- Botones de acción -->
                                <div class="text-center mt-4">
                                    <button type="button" class="btn btn-outline-secondary mr-2" onclick="window.history.back()">
                                        <i class="fas fa-arrow-left"></i> Volver
                                    </button>
                                    <button type="button" class="btn btn-outline-info mr-2" onclick="marcarTodosPresentesSesion()">
                                        <i class="fas fa-check-double"></i> Todos Presentes
                                    </button>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save"></i> Guardar Asistencia
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- Vista general del dashboard -->
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
                @endif
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

/* Attendance Grid Styles */
.attendance-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 15px;
    margin-bottom: 20px;
}

.student-attendance-card {
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 15px;
    transition: all 0.2s ease;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

.student-attendance-card:hover {
    box-shadow: 0 3px 12px rgba(0,0,0,0.1);
    border-color: #dee2e6;
}

.student-header {
    display: flex;
    align-items: center;
    gap: 12px;
