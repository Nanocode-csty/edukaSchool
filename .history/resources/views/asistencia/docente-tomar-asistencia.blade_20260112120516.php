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
                                                                    <button type="button" class="btn btn-sm btn-outline-success attendance-btn {{ $estudiante->asistencia_actual === 'P' ? 'active btn-success' : '' }}"
                                                                            data-matricula-id="{{ $estudiante->matricula_id }}"
                                                                            data-value="P"
                                                                            title="Marcar como Presente">
                                                                        <i class="fas fa-check"></i>
                                                                        <input type="radio" name="asistencia_{{ $estudiante->matricula_id }}" value="P"
                                                                               {{ $estudiante->asistencia_actual === 'P' ? 'checked' : '' }}
                                                                               class="attendance-radio d-none"
                                                                               data-matricula-id="{{ $estudiante->matricula_id }}">
                                                                    </button>
                                                                </td>
                                                                <td class="text-center">
                                                                    <button type="button" class="btn btn-sm btn-outline-danger attendance-btn {{ $estudiante->asistencia_actual === 'A' ? 'active btn-danger' : '' }}"
                                                                            data-matricula-id="{{ $estudiante->matricula_id }}"
                                                                            data-value="A"
                                                                            title="Marcar como Ausente">
                                                                        <i class="fas fa-times"></i>
                                                                        <input type="radio" name="asistencia_{{ $estudiante->matricula_id }}" value="A"
                                                                               {{ $estudiante->asistencia_actual === 'A' ? 'checked' : '' }}
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
