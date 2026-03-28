@extends('cplantilla.bprincipal')

@section('titulo','Tomar Asistencia - Docente')

@section('contenidoplantilla')

<x-breadcrumb :module="'asistencia'" :section="'docente-tomar'" />

<div class="container-fluid" id="contenido-principal">
    <div class="row mt-4">
        <div class="col-12">
            <div class="box_block">
                <!-- Weekly Calendar Section - Only show when not in session mode -->
                @if(!isset($modo) || $modo !== 'sesion')
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
                @endif

                <!-- Main Attendance Section -->
                @if(isset($modo) && $modo === 'sesion')
                    <!-- Vista específica para tomar asistencia de una sesión - siguiendo el patrón del sistema -->
                    <!-- Collapse header -->
                    <button class="btn btn-block text-left rounded-0 btn_header header_6" type="button" data-toggle="collapse" data-target="#collapseTomarAsistenciaSesion" aria-expanded="true" aria-controls="collapseTomarAsistenciaSesion" style="background: #007bff !important; font-weight: bold; color: white;">
                        <i class="fas fa-edit m-1"></i>&nbsp;Tomar Asistencia -
                        <span>{{ $sesion->cursoAsignatura->asignatura->nombre }} - {{ $sesion->cursoAsignatura->curso->grado->nombre }} {{ $sesion->cursoAsignatura->curso->seccion->nombre }}</span>
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
                                    Registra la asistencia de los estudiantes para esta sesión de clase. Selecciona el tipo de asistencia para cada estudiante y guarda los cambios.
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- Collapse: contenido de la sesión -->
                    <div class="collapse show" id="collapseTomarAsistenciaSesion">
                        <div class="card card-body rounded-0 border-0 pt-0 pb-2" style="background: transparent;">

                        @if(isset($error))
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle"></i> {{ $error }}
                            </div>
                        @else
                            <!-- Información de la sesión -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header bg-primary text-white">
                                            <h5 class="mb-0">
                                                <i class="fas fa-info-circle"></i> Información de la Sesión
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="text-center">
                                                        <h6 class="text-muted mb-2">Fecha</h6>
                                                        <strong>{{ $sesion->fecha->locale('es')->dayName }}, {{ $sesion->fecha->format('d/m/Y') }}</strong>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="text-center">
                                                        <h6 class="text-muted mb-2">Horario</h6>
                                                        <strong>{{ substr($sesion->hora_inicio, 0, 5) }} - {{ substr($sesion->hora_fin, 0, 5) }}</strong>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="text-center">
                                                        <h6 class="text-muted mb-2">Asignatura</h6>
                                                        <strong>{{ $sesion->cursoAsignatura->asignatura->nombre }}</strong>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="text-center">
                                                        <h6 class="text-muted mb-2">Curso</h6>
                                                        <strong>{{ $sesion->cursoAsignatura->curso->grado->nombre }} {{ $sesion->cursoAsignatura->curso->seccion->nombre }}</strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Estadísticas de la sesión -->
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
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="mb-0"><i class="fas fa-edit"></i> Registro de Asistencia</h5>
                                        </div>
                                        <div class="card-body">
                                            <form id="form-asistencia-sesion" class="attendance-form">
                                                <input type="hidden" name="sesion_clase_id" value="{{ $sesion->sesion_id }}">

                                                <!-- Tabla de registro de asistencia en lista -->
                                                <div class="table-responsive">
                                                    <table class="table table-striped table-hover border">
                                                        <thead class="table-dark">
                                                            <tr>
                                                                <th class="text-center" style="width: 60px;">#</th>
                                                                <th style="min-width: 200px;">Estudiante</th>
                                                                <th style="min-width: 150px;">DNI</th>
                                                                <th class="text-center" style="min-width: 100px;">Presente</th>
                                                                <th class="text-center" style="min-width: 100px;">Ausente</th>
                                                                <th class="text-center" style="min-width: 100px;">Tarde</th>
                                                                <th class="text-center" style="min-width: 120px;">Justificado</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($estudiantes as $index => $estudiante)
                                                            <tr data-student-name="{{ strtolower($estudiante->estudiante->persona->nombres . ' ' . $estudiante->estudiante->persona->apellidos) }}">
                                                                <td class="text-center font-weight-bold">{{ $index + 1 }}</td>
                                                                <td>
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="avatar-circle-small mr-3" style="width: 35px; height: 35px; border-radius: 50%; background: #007bff; color: white; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: bold;">
                                                                            {{ substr($estudiante->estudiante->persona->nombres, 0, 1) }}{{ substr($estudiante->estudiante->persona->apellidos, 0, 1) }}
                                                                        </div>
                                                                        <div>
                                                                            <div class="font-weight-bold">{{ $estudiante->estudiante->persona->nombres }}</div>
                                                                            <small class="text-muted">{{ $estudiante->estudiante->persona->apellidos }}</small>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>{{ $estudiante->estudiante->persona->dni }}</td>
                                                                <td class="text-center">
                                                                        <button type="button" class="btn btn-sm btn-outline-success attendance-btn {{ $estudiante->asistencia_actual === 'A' ? 'active btn-success' : '' }}"
                                                                                data-matricula-id="{{ $estudiante->matricula_id }}"
                                                                        data-value="A"
                                                                            title="Marcar como Presente">
                                                                        <i class="fas fa-check"></i>
                                                                        <input type="radio" name="asistencia_{{ $estudiante->matricula_id }}" value="A"
                                                                               {{ $estudiante->asistencia_actual === 'A' ? 'checked' : '' }}
                                                                               class="attendance-radio d-none"
                                                                               data-matricula-id="{{ $estudiante->matricula_id }}">
                                                                    </button>
                                                                </td>
                                                                <td class="text-center">
                                                                    <button type="button" class="btn btn-sm btn-outline-danger attendance-btn {{ $estudiante->asistencia_actual === 'F' ? 'active btn-danger' : '' }}"
                                                                            data-matricula-id="{{ $estudiante->matricula_id }}"
                                                                            data-value="F"
                                                                            title="Marcar como Falta">
                                                                        <i class="fas fa-times"></i>
                                                                        <input type="radio" name="asistencia_{{ $estudiante->matricula_id }}" value="F"
                                                                               {{ $estudiante->asistencia_actual === 'F' ? 'checked' : '' }}
                                                                               class="attendance-radio d-none"
                                                                               data-matricula-id="{{ $estudiante->matricula_id }}">
                                                                    </button>
                                                                </td>
                                                                <td class="text-center">
                                                                    <button type="button" class="btn btn-sm btn-outline-warning attendance-btn {{ $estudiante->asistencia_actual === 'T' ? 'active btn-warning' : '' }}"
                                                                            data-matricula-id="{{ $estudiante->matricula_id }}"
                                                                            data-value="T"
                                                                            title="Marcar como Tarde">
                                                                        <i class="fas fa-clock"></i>
                                                                        <input type="radio" name="asistencia_{{ $estudiante->matricula_id }}" value="T"
                                                                               {{ $estudiante->asistencia_actual === 'T' ? 'checked' : '' }}
                                                                               class="attendance-radio d-none"
                                                                               data-matricula-id="{{ $estudiante->matricula_id }}">
                                                                    </button>
                                                                </td>
                                                                <td class="text-center">
                                                                    <button type="button" class="btn btn-sm btn-outline-info attendance-btn {{ $estudiante->asistencia_actual === 'J' ? 'active btn-info' : '' }}"
                                                                            data-matricula-id="{{ $estudiante->matricula_id }}"
                                                                            data-value="J"
                                                                            title="Marcar como Justificado">
                                                                        <i class="fas fa-file-medical"></i>
                                                                        <input type="radio" name="asistencia_{{ $estudiante->matricula_id }}" value="J"
                                                                               {{ $estudiante->asistencia_actual === 'J' ? 'checked' : '' }}
                                                                               class="attendance-radio d-none"
                                                                               data-matricula-id="{{ $estudiante->matricula_id }}">
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
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
                                                    <button type="button" class="btn btn-success" onclick="guardarAsistenciaSesion(document.getElementById('form-asistencia-sesion'))">
                                                        <i class="fas fa-save"></i> Guardar Asistencia
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Acciones Rápidas -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="d-flex justify-content-end gap-2 flex-wrap">

                                        <a href="{{ route('asistencia.docente.dashboard') }}" class="btn btn-sm" style="background-color: #6c757d !important; color: white !important; border: none !important;">
                                            <i class="fas fa-arrow-left mr-1"></i>Volver al Dashboard
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @else
                    <!-- Vista general - Lista de sesiones disponibles -->
                    <!-- Collapse header -->
                    <button class="btn btn-block text-left rounded-0 btn_header header_6" type="button" data-toggle="collapse" data-target="#collapseTomarAsistencia" aria-expanded="true" aria-controls="collapseTomarAsistencia" style="background: #007bff !important; font-weight: bold; color: white;">
                        <i class="fas fa-clipboard-check m-1"></i>&nbsp;Tomar Asistencia
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
                                    Selecciona una sesión de clase para tomar la asistencia de los estudiantes. Solo puedes tomar asistencia de las clases que tienes asignadas.
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- Collapse: contenido del listado -->
                    <div class="collapse show" id="collapseTomarAsistencia">
                        <div class="card card-body rounded-0 border-0 pt-0 pb-2" style="background: transparent;">

                        @if(isset($error))
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle"></i> {{ $error }}
                            </div>
                        @else

                            @if($clases_hoy->count() > 0)
                                <!-- Lista de Sesiones Disponibles -->
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header bg-primary text-white">
                                                <h5 class="mb-0">
                                                    <i class="fas fa-list"></i> Sesiones Disponibles - {{ $fecha_seleccionada ? $fecha_seleccionada->locale('es')->dayName . ', ' . $fecha_seleccionada->format('d/m/Y') : now()->locale('es')->dayName . ', ' . date('d/m/Y') }}
                                                </h5>
                                                <small>{{ $clases_hoy->count() }} sesiones programadas</small>
                                            </div>
                                            <div class="card-body p-0">
                                                <div class="list-group list-group-flush">
                                                    @foreach($clases_hoy as $clase)
                                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                                        <div class="d-flex align-items-center">
                                                            <!-- Icono de la asignatura -->
                                                            <div class="mr-3">
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
                                                                <i class="{{ $icon }} fa-2x text-primary"></i>
                                                            </div>
                                                            <div>
                                                                <h6 class="mb-1">{{ $clase->cursoAsignatura->asignatura->nombre }}</h6>
                                                                <p class="mb-1 text-muted">
                                                                    <i class="fas fa-graduation-cap"></i> {{ $clase->cursoAsignatura->curso->grado->nombre }} {{ $clase->cursoAsignatura->curso->seccion->nombre }}
                                                                    @if($clase->aula)
                                                                        <br><i class="fas fa-map-marker-alt"></i> {{ $clase->aula->nombre }}
                                                                    @endif
                                                                </p>
                                                                <small class="text-muted">
                                                                    <i class="fas fa-clock"></i> {{ substr($clase->hora_inicio, 0, 5) }} - {{ substr($clase->hora_fin, 0, 5) }}
                                                                </small>
                                                            </div>
                                                        </div>
                                                        <div class="text-right">
                                                            @if($clase->tiene_asistencia_hoy)
                                                                <div class="btn-group btn-group-sm" role="group">
                                                                    <button type="button" class="btn btn-success" title="Ver asistencia registrada"
                                                                            onclick="verAsistenciaSesion({{ $clase->sesion_id }})">
                                                                        <i class="fas fa-eye"></i> Ver
                                                                    </button>
                                                                    <button type="button" class="btn btn-warning" title="Editar asistencia registrada"
                                                                            onclick="editarAsistenciaSesion({{ $clase->sesion_id }})">
                                                                        <i class="fas fa-edit"></i> Editar
                                                                    </button>
                                                                </div>
                                                            @else
                                                                <button type="button" class="btn btn-primary" title="Tomar asistencia"
                                                                        onclick="tomarAsistenciaSesion({{ $clase->sesion_id }})">
                                                                    <i class="fas fa-edit"></i> Tomar
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                </div>
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
                                                    <small class="text-muted">Sesiones Totales</small>
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
                @else
                                <div class="text-center py-5">
                                    <i class="fas fa-calendar-times text-muted fa-4x mb-4"></i>
                                    <h4 class="text-muted mb-3">No hay sesiones programadas</h4>
                                    <p class="text-muted mb-4">No tienes sesiones de clase programadas para la fecha seleccionada.</p>
                                    <a href="{{ route('asistencia.docente.dashboard') }}" class="btn btn-primary">
                                        <i class="fas fa-arrow-left"></i> Volver al Dashboard
                                    </a>
                                </div>
                            @endif

                            <!-- Acciones Rápidas -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="d-flex justify-content-end gap-2 flex-wrap">
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

@push('js-extra')
<script>
// Weekly Calendar functionality
let currentWeekStart = null;
let calendarVisible = true;

document.addEventListener('DOMContentLoaded', function () {
    // Initialize calendar
    initializeWeeklyCalendar();

    // Setup toggle calendar button
    const toggleBtn = document.getElementById('toggleCalendarBtn');
    if (toggleBtn) {
        toggleBtn.addEventListener('click', toggleCalendar);
    }
});

function initializeWeeklyCalendar() {
    // Set current week start to Monday of current week
    const today = new Date();
    const dayOfWeek = today.getDay(); // 0 = Sunday, 1 = Monday, etc.
    const diff = today.getDate() - dayOfWeek + (dayOfWeek === 0 ? -6 : 1); // Adjust for Sunday
    currentWeekStart = new Date(today.setDate(diff));
    currentWeekStart.setHours(0, 0, 0, 0);

    loadWeeklyCalendar();

    // Auto-select today after calendar loads
    setTimeout(() => {
        const today = new Date();
        const todayCard = document.querySelector(`.day-card[data-date="${today.toISOString().split('T')[0]}"]`);
        if (todayCard) {
            selectDay(today, todayCard);
        }
    }, 500); // Small delay to ensure calendar is loaded
}

function loadWeeklyCalendar() {
    const container = document.getElementById('weekly-calendar-container');
    if (!container) return;

    // Clear existing content
    container.innerHTML = '';

    // Create calendar grid
    const calendarGrid = document.createElement('div');
    calendarGrid.className = 'weekly-calendar-grid';
    calendarGrid.style.cssText = `
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 10px;
        padding: 20px;
        max-width: 1200px;
        margin: 0 auto;
    `;

    // Days of the week in Spanish
    const daysOfWeek = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];

    // Generate calendar cards for each day
    for (let i = 0; i < 7; i++) {
        const currentDate = new Date(currentWeekStart);
        currentDate.setDate(currentWeekStart.getDate() + i);

        const dayCard = createDayCard(currentDate, daysOfWeek[i]);
        calendarGrid.appendChild(dayCard);
    }

    container.appendChild(calendarGrid);
}

function createDayCard(date, dayName) {
    const card = document.createElement('div');
    card.className = 'day-card';
    card.dataset.date = date.toISOString().split('T')[0];

    // Check if this date is currently selected (from URL or today)
    const urlParams = new URLSearchParams(window.location.search);
    const selectedDateParam = urlParams.get('fecha');
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    const dateOnly = new Date(date.getFullYear(), date.getMonth(), date.getDate());
    const todayOnly = new Date(today.getFullYear(), today.getMonth(), today.getDate());

    const isToday = dateOnly.getTime() === todayOnly.getTime();
    const isSelected = selectedDateParam ? date.toISOString().split('T')[0] === selectedDateParam : isToday;

    card.style.cssText = `
        border: 2px solid ${isSelected ? '#28a745' : isToday ? '#007bff' : '#dee2e6'};
        border-radius: 8px;
        padding: 15px;
        text-align: center;
        background: ${isSelected ? '#f8fff9' : isToday ? '#f8f9fa' : 'white'};
        transition: all 0.3s ease;
        cursor: pointer;
        min-height: 120px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        box-shadow: ${isSelected ? '0 0 15px rgba(40, 167, 69, 0.3)' : isToday ? '0 0 10px rgba(0, 123, 255, 0.1)' : 'none'};
        position: relative;
    `;

    // Add special indicators
    if (isToday) {
        card.style.borderWidth = '3px';
        // Add "Hoy" indicator
        const todayIndicator = document.createElement('div');
        todayIndicator.style.cssText = `
            position: absolute;
            top: -8px;
            left: 50%;
            transform: translateX(-50%);
            background: #007bff;
            color: white;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 0.7rem;
            font-weight: bold;
            text-transform: uppercase;
        `;
        todayIndicator.textContent = 'Hoy';
        card.appendChild(todayIndicator);
    }

    if (isSelected && !isToday) {
        // Add "Seleccionado" indicator
        const selectedIndicator = document.createElement('div');
        selectedIndicator.style.cssText = `
            position: absolute;
            top: -8px;
            left: 50%;
            transform: translateX(-50%);
            background: #28a745;
            color: white;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 0.7rem;
            font-weight: bold;
            text-transform: uppercase;
        `;
        selectedIndicator.textContent = 'Seleccionado';
        card.appendChild(selectedIndicator);
    }

    // Highlight past days
    if (date < today && !isSelected) {
        card.style.opacity = '0.6';
    }

    const dayHeader = document.createElement('div');
    dayHeader.innerHTML = `
        <div style="font-weight: bold; color: #495057; margin-bottom: 5px;">${dayName}</div>
        <div style="font-size: 1.5rem; font-weight: bold; color: ${isSelected ? '#28a745' : isToday ? '#007bff' : '#007bff'}; margin-top: ${isToday || isSelected ? '10px' : '0'};">${date.getDate()}</div>
    `;

    const sessionsContainer = document.createElement('div');
    sessionsContainer.className = 'sessions-container';
    sessionsContainer.style.cssText = `
        margin-top: 10px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
    `;

    // Add click handler to filter sessions
    card.addEventListener('click', function() {
        selectDay(date, card);
    });

    // Load sessions for this day
    loadSessionsForDay(date, sessionsContainer);

    card.appendChild(dayHeader);
    card.appendChild(sessionsContainer);

    return card;
}

function loadSessionsForDay(date, container) {
    const dateStr = date.toISOString().split('T')[0];
    container.innerHTML = `<small style="color: #6c757d;">Cargando sesiones...</small>`;

    // Create AbortController for timeout
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), 10000); // 10 second timeout

    // Make AJAX request to get real session count
    fetch(`{{ route('asistencia.api.sesiones-por-dia', ':fecha') }}`.replace(':fecha', dateStr), {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        signal: controller.signal
    })
    .then(response => {
        clearTimeout(timeoutId);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        // Always show a result, never leave in loading state
        const sessionCount = data.cantidad || 0;
        if (sessionCount > 0) {
            container.innerHTML = `<div style="color: #28a745; font-weight: bold;">${sessionCount} clases</div>`;
        } else {
            container.innerHTML = `<small style="color: #6c757d;">Sin clases</small>`;
        }
    })
    .catch(error => {
        clearTimeout(timeoutId);
        console.error('Error loading sessions for day:', error);

        // Always show some result, never leave in loading state
        if (error.name === 'AbortError') {
            container.innerHTML = `<small style="color: #dc3545;">Timeout</small>`;
        } else {
            container.innerHTML = `<small style="color: #dc3545;">Error</small>`;
        }
    });
}

function cambiarSemana(direction) {
    currentWeekStart.setDate(currentWeekStart.getDate() + (direction * 7));
    loadWeeklyCalendar();
}

function toggleCalendar() {
    const calendar = document.getElementById('weeklyCalendar');
    const btn = document.getElementById('toggleCalendarBtn');
    const btnText = document.getElementById('calendarBtnText');

    if (calendar) {
        calendarVisible = !calendarVisible;
        calendar.style.display = calendarVisible ? 'block' : 'none';
        btnText.textContent = calendarVisible ? 'Ver Calendario' : 'Ocultar Calendario';

        const icon = btn.querySelector('i');
        if (icon) {
            icon.className = calendarVisible ? 'fas fa-chevron-down' : 'fas fa-chevron-up';
        }
    }
}

function selectDay(date, clickedCard) {
    const dateStr = date.toISOString().split('T')[0];

    // Update visual selection
    updateDaySelection(clickedCard, dateStr);

    // Filter sessions without page reload
    filterSessionsByDateAjax(dateStr);
}

function updateDaySelection(selectedCard, selectedDate) {
    // Remove selection from all cards first
    document.querySelectorAll('.day-card').forEach(card => {
        // Remove all selection indicators (both "Seleccionado" and "Hoy" badges)
        const allIndicators = card.querySelectorAll('div[style*="position: absolute"][style*="top: -8px"]');
        allIndicators.forEach(indicator => indicator.remove());

        // Reset all cards to base state
        const dateStr = card.dataset.date;
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        const cardDate = new Date(dateStr + 'T00:00:00');
        const isTodayCard = cardDate.getTime() === today.getTime();

        // Reset styles - only today gets special styling
        card.style.borderColor = isTodayCard ? '#007bff' : '#dee2e6';
        card.style.borderWidth = isTodayCard ? '3px' : '2px';
        card.style.backgroundColor = isTodayCard ? '#f8f9fa' : 'white';
        card.style.boxShadow = isTodayCard ? '0 0 10px rgba(0, 123, 255, 0.1)' : 'none';

        // Re-add "Hoy" indicator for today if it was removed
        if (isTodayCard) {
            const todayIndicator = document.createElement('div');
            todayIndicator.style.cssText = `
                position: absolute;
                top: -8px;
                left: 50%;
                transform: translateX(-50%);
                background: #007bff;
                color: white;
                padding: 2px 8px;
                border-radius: 10px;
                font-size: 0.7rem;
                font-weight: bold;
                text-transform: uppercase;
                z-index: 10;
            `;
            todayIndicator.textContent = 'Hoy';
            card.appendChild(todayIndicator);
        }
    });

    // Now add selection to the clicked card (only if it's not today)
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    const selectedDateObj = new Date(selectedDate + 'T00:00:00');
    const isToday = selectedDateObj.getTime() === today.getTime();

    // Only add "Seleccionado" if it's not today (today already has "Hoy")
    if (!isToday) {
        // Remove any existing indicators first
        const existingIndicators = selectedCard.querySelectorAll('div[style*="position: absolute"][style*="top: -8px"]');
        existingIndicators.forEach(indicator => indicator.remove());

        // Add "Seleccionado" indicator
        const selectedIndicator = document.createElement('div');
        selectedIndicator.className = 'selection-indicator';
        selectedIndicator.style.cssText = `
            position: absolute;
            top: -8px;
            left: 50%;
            transform: translateX(-50%);
            background: #28a745;
            color: white;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 0.7rem;
            font-weight: bold;
            text-transform: uppercase;
            z-index: 10;
        `;
        selectedIndicator.textContent = 'Seleccionado';
        selectedCard.appendChild(selectedIndicator);

        // Update card styles for selection
        selectedCard.style.borderColor = '#28a745';
        selectedCard.style.borderWidth = '3px';
        selectedCard.style.backgroundColor = '#f8fff9';
        selectedCard.style.boxShadow = '0 0 15px rgba(40, 167, 69, 0.3)';
    }
}

function filterSessionsByDateAjax(dateStr) {
    // Show loading state only for sessions list, not header
    const sessionsContainer = document.querySelector('.card-header h5');
    const currentSessionsList = document.querySelector('.list-group');
    const currentStats = document.querySelector('.card.border-info .card-body');

    // Update header immediately with selected date
    updateHeaderWithSelectedDate(dateStr);

    // Show loading state for sessions list
    if (currentSessionsList) {
        currentSessionsList.innerHTML = '<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x text-primary"></i><p class="mt-2">Cargando sesiones...</p></div>';
    }

    // Make AJAX request to get sessions for the selected date
    fetch(`{{ route('asistencia.docente.tomar-asistencia') }}?fecha=${dateStr}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'
        }
    })
    .then(response => response.text())
    .then(html => {
        // Parse the HTML response to extract session data
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');

        // Update the sessions list
        const newSessionsList = doc.querySelector('.list-group');
        if (newSessionsList && currentSessionsList && newSessionsList.children.length > 0) {
            // Has sessions - update with real content
            currentSessionsList.innerHTML = newSessionsList.innerHTML;
        } else if (currentSessionsList) {
            // No sessions found - show appropriate message
            const dateObj = new Date(dateStr + 'T00:00:00'); // Ensure proper date parsing
            const formattedDate = dateObj.toLocaleDateString('es-ES', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            currentSessionsList.innerHTML = `
                <div class="text-center py-5">
                    <i class="fas fa-calendar-times text-muted fa-4x mb-4"></i>
                    <h4 class="text-muted mb-3">No hay sesiones programadas</h4>
                    <p class="text-muted mb-4">No tienes sesiones de clase programadas para ${formattedDate}.</p>
                </div>
            `;
        }

        // Update statistics if they exist
        const newStats = doc.querySelector('.card.border-info .card-body');
        if (newStats && currentStats) {
            currentStats.innerHTML = newStats.innerHTML;
        } else if (currentStats) {
            // Update stats to show zero sessions
            currentStats.innerHTML = `
                <div class="row text-center">
                    <div class="col-md-3">
                        <div class="p-3 bg-light rounded">
                            <h4 class="text-primary">0</h4>
                            <small class="text-muted">Sesiones Totales</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 bg-success rounded text-white">
                            <h4>0</h4>
                            <small>Completadas</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 bg-warning rounded text-white">
                            <h4>0</h4>
                            <small>Pendientes</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 bg-info rounded text-white">
                            <h4>0</h4>
                            <small>Estudiantes Totales</small>
                        </div>
                    </div>
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Error loading sessions:', error);

        // Show error message for sessions list
        if (currentSessionsList) {
            const dateObj = new Date(dateStr + 'T00:00:00');
            const formattedDate = dateObj.toLocaleDateString('es-ES', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            currentSessionsList.innerHTML = `
                <div class="text-center py-5">
                    <i class="fas fa-exclamation-triangle text-danger fa-4x mb-4"></i>
                    <h4 class="text-danger mb-3">Error al cargar sesiones</h4>
                    <p class="text-muted mb-4">No se pudieron cargar las sesiones para ${formattedDate}.</p>
                    <button class="btn btn-primary" onclick="filterSessionsByDateAjax('${dateStr}')">
                        <i class="fas fa-redo"></i> Reintentar
                    </button>
                </div>
            `;
        }

        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error al cargar las sesiones. Inténtalo de nuevo.'
        });
    });
}

function updateHeaderWithSelectedDate(dateStr) {
    const sessionsContainer = document.querySelector('.card-header h5');
    if (sessionsContainer) {
        const dateObj = new Date(dateStr + 'T00:00:00');
        const formattedDate = dateObj.toLocaleDateString('es-ES', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });

        // Update header with selected date
        sessionsContainer.innerHTML = `<i class="fas fa-list"></i> Sesiones Disponibles - ${formattedDate.charAt(0).toUpperCase() + formattedDate.slice(1)}`;
    }
}

// Session action functions
function verAsistenciaSesion(sesionId) {
    console.log('Ver asistencia sesión:', sesionId);
    // Redirect to view attendance page
    const url = `{{ route('asistencia.docente.ver', ':id') }}`.replace(':id', sesionId);
    console.log('URL de redirección:', url);
    window.location.href = url;
}

function editarAsistenciaSesion(sesionId) {
    console.log('Editar asistencia sesión:', sesionId);
    // Show confirmation dialog using Swal
    Swal.fire({
        title: '¿Editar asistencia?',
        text: '¿Estás seguro de que deseas editar la asistencia? Esto puede afectar reportes y estadísticas.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ffc107',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, editar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Redirect to edit attendance page
            const url = `{{ route('asistencia.docente.editar', ':id') }}`.replace(':id', sesionId);
            console.log('URL de redirección editar:', url);
            window.location.href = url;
        }
    });
}

function tomarAsistenciaSesion(sesionId) {
    console.log('Tomar asistencia sesión:', sesionId);
    // Redirect to take attendance page for specific session
    const url = `{{ route('asistencia.docente.tomar-asistencia') }}?sesion=${sesionId}`;
    console.log('URL de redirección tomar:', url);
    window.location.href = url;
}

// Attendance form handling functions
document.addEventListener('DOMContentLoaded', function () {
    // Handle attendance option clicks
    document.querySelectorAll('.attendance-option').forEach(option => {
        option.addEventListener('click', function() {
            const radio = this.querySelector('input[type="radio"]');
            if (radio) {
                radio.checked = true;

                const matriculaId = radio.getAttribute('data-matricula-id');
                const attendanceType = radio.value.toLowerCase();

                updateAttendanceOptionVisualsForStudent(matriculaId, radio.value);
            }
        });
    });

    // Handle new attendance button clicks
    document.querySelectorAll('.attendance-btn').forEach(button => {
        button.addEventListener('click', function() {
            handleAttendanceButtonClick(this);
        });
    });
});

// Function to mark all students as present
function marcarTodosPresentesSesion() {
    console.log('=== MARCANDO TODOS COMO PRESENTES ===');

    // Mark all radio buttons as "A" (Presente/Asistió)
    const radios = document.querySelectorAll('input[name^="asistencia_"][value="A"]');
    console.log('Radio buttons encontrados:', radios.length);
    radios.forEach(radio => {
        radio.checked = true;
    });

    // Update visual state of all attendance buttons
    const allAttendanceButtons = document.querySelectorAll('.attendance-btn');
    console.log('Botones encontrados:', allAttendanceButtons.length);

    // Group buttons by student (matricula_id)
    const buttonsByStudent = {};
    allAttendanceButtons.forEach(button => {
        const matriculaId = button.getAttribute('data-matricula-id');
        if (!buttonsByStudent[matriculaId]) {
            buttonsByStudent[matriculaId] = [];
        }
        buttonsByStudent[matriculaId].push(button);
    });

    // Process each student
    Object.keys(buttonsByStudent).forEach(matriculaId => {
        const studentButtons = buttonsByStudent[matriculaId];

        // First, reset all buttons for this student to outline style
        studentButtons.forEach(btn => {
            // Remove active class
            btn.classList.remove('active');

            // Convert solid colors back to outline
            if (btn.classList.contains('btn-success')) {
                btn.classList.remove('btn-success');
                btn.classList.add('btn-outline-success');
            }
            if (btn.classList.contains('btn-danger')) {
                btn.classList.remove('btn-danger');
                btn.classList.add('btn-outline-danger');
            }
            if (btn.classList.contains('btn-warning')) {
                btn.classList.remove('btn-warning');
                btn.classList.add('btn-outline-warning');
            }
            if (btn.classList.contains('btn-info')) {
                btn.classList.remove('btn-info');
                btn.classList.add('btn-outline-info');
            }
        });

        // Then, activate the "Presente" button
        const presenteButton = studentButtons.find(btn => btn.getAttribute('data-value') === 'A');
        if (presenteButton) {
            presenteButton.classList.add('active');
            if (presenteButton.classList.contains('btn-outline-success')) {
                presenteButton.classList.remove('btn-outline-success');
                presenteButton.classList.add('btn-success');
            }
        }
    });

    console.log('=== TODOS MARCADOS COMO PRESENTES ===');

    // Show success message
    Swal.fire({
        icon: 'success',
        title: '¡Listo!',
        text: 'Todos los estudiantes marcados como presentes',
        timer: 1500,
        showConfirmButton: false
    });
}

// Function to handle attendance button clicks
function handleAttendanceButtonClick(button) {
    const matriculaId = button.getAttribute('data-matricula-id');
    const value = button.getAttribute('data-value');

    console.log(`Cambiando asistencia para estudiante ${matriculaId} a ${value}`);

    // Remove active class and reset to outline style for all buttons for this student
    const studentButtons = document.querySelectorAll(`.attendance-btn[data-matricula-id="${matriculaId}"]`);
    studentButtons.forEach(btn => {
        // Remove active class
        btn.classList.remove('active');

        // Convert solid colors back to outline
        if (btn.classList.contains('btn-success')) {
            btn.classList.remove('btn-success');
            btn.classList.add('btn-outline-success');
        }
        if (btn.classList.contains('btn-danger')) {
            btn.classList.remove('btn-danger');
            btn.classList.add('btn-outline-danger');
        }
        if (btn.classList.contains('btn-warning')) {
            btn.classList.remove('btn-warning');
            btn.classList.add('btn-outline-warning');
        }
        if (btn.classList.contains('btn-info')) {
            btn.classList.remove('btn-info');
            btn.classList.add('btn-outline-info');
        }
    });

    // Add active class and solid color to clicked button
    button.classList.add('active');
    if (button.classList.contains('btn-outline-success')) {
        button.classList.remove('btn-outline-success');
        button.classList.add('btn-success');
    } else if (button.classList.contains('btn-outline-danger')) {
        button.classList.remove('btn-outline-danger');
        button.classList.add('btn-danger');
    } else if (button.classList.contains('btn-outline-warning')) {
        button.classList.remove('btn-outline-warning');
        button.classList.add('btn-warning');
    } else if (button.classList.contains('btn-outline-info')) {
        button.classList.remove('btn-outline-info');
        button.classList.add('btn-info');
    }

    // Update the corresponding radio button - find by name and value
    const radioName = `asistencia_${matriculaId}`;
    const radio = document.querySelector(`input[name="${radioName}"][value="${value}"]`);
    if (radio) {
        // First, uncheck all radios for this student
        const allRadiosForStudent = document.querySelectorAll(`input[name="${radioName}"]`);
        allRadiosForStudent.forEach(r => r.checked = false);

        // Then check the selected one
        radio.checked = true;
        console.log(`Radio button marcado: ${radioName} = ${value}`);
    } else {
        console.error(`Radio button no encontrado: ${radioName} con valor ${value}`);
    }

    console.log(`Asistencia actualizada: Estudiante ${matriculaId} - Tipo ${value}`);
}

// Function to update attendance option visuals for a specific student
function updateAttendanceOptionVisualsForStudent(matriculaId, selectedType) {
    // Remove active class from all options for this student
    const studentOptions = document.querySelectorAll(`input[data-matricula-id="${matriculaId}"]`);
    studentOptions.forEach(input => {
        const option = input.closest('.attendance-option');
        if (option) {
            option.classList.remove('active', 'present', 'absent', 'late', 'justified');
        }
    });

    // Add active class to selected option
    const selectedOption = document.querySelector(`input[data-matricula-id="${matriculaId}"][value="${selectedType}"]`);
    if (selectedOption) {
        const option = selectedOption.closest('.attendance-option');
        if (option) {
            const type = selectedType.toLowerCase();
            option.classList.add('active', type);
        }
    }
}

// Function to save attendance
function guardarAsistenciaSesion(form) {
    if (!form) {
        console.error('Form is null');
        return;
    }

    const sesionId = form.querySelector('input[name="sesion_clase_id"]');
    if (!sesionId) {
        console.error('sesion_clase_id input not found');
        return;
    }

    const sesionIdValue = sesionId.value;
    const asistencias = [];
    let hasSelections = false;

    // Collect attendance data from checked radio buttons
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

    // Show loading state
    const submitBtn = form.querySelector('button[onclick*="guardarAsistenciaSesion"]');
    if (!submitBtn) {
        console.error('Save button not found');
        return;
    }

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
            sesion_clase_id: sesionIdValue,
            asistencias: asistencias,
            observaciones_generales: form.querySelector('#observaciones_generales')?.value || ''
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            Swal.fire({
                icon: 'success',
                title: '¡Asistencia guardada!',
                text: data.message,
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                // Reload page to show updated data
                window.location.reload();
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
        if (submitBtn) {
            submitBtn.innerHTML = originalHtml;
            submitBtn.disabled = false;
        }
    });
}
</script>
@endpush
