@extends('cplantilla.bprincipal')

@section('titulo','Editar Asistencia - Docente')

@section('contenidoplantilla')

<x-breadcrumb :module="'asistencia'" :section="'docente-editar'" />

<div class="container-fluid" id="contenido-principal">
    <div class="row mt-4">
        <div class="col-12">
            <div class="box_block">
                <!-- Collapse header -->
                <button class="btn btn-block text-left rounded-0 btn_header header_6" type="button" data-toggle="collapse" data-target="#collapseEditarAsistencia" aria-expanded="true" aria-controls="collapseEditarAsistencia" style="background: #ffc107 !important; font-weight: bold; color: white;">
                    <i class="fas fa-edit m-1"></i>&nbsp;Editar Asistencia -
                    <span>{{ $sesion->cursoAsignatura->asignatura->nombre }} - {{ $sesion->cursoAsignatura->curso->grado->nombre }} {{ $sesion->cursoAsignatura->curso->seccion->nombre }}</span>
                    <div class="float-right"><i class="fas fa-chevron-down"></i></div>
                </button>

                <!-- Información de restricciones -->
                <div class="alert alert-warning alert-dismissible fade show" role="alert" style="border-radius: 0;">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Restricciones de Edición:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Solo puedes editar asistencias de los últimos 7 días</li>
                        <li>Los administradores pueden editar asistencias más antiguas</li>
                        <li>Esta acción afecta reportes y estadísticas</li>
                        <li>Todas las modificaciones quedan registradas</li>
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- Collapse: contenido de la edición -->
                <div class="collapse show" id="collapseEditarAsistencia">
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
                                        <div class="card-header bg-warning text-dark">
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
                                                        <br><small class="text-muted">Hace {{ $estadisticas_sesion['dias_desde_sesion'] }} días</small>
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
                                <div class="col-md-4">
                                    <div class="card border-info">
                                        <div class="card-body text-center">
                                            <h6 class="text-info mb-2">
                                                <i class="fas fa-users"></i> Estudiantes Totales
                                            </h6>
                                            <h3 class="text-primary">{{ $estadisticas_sesion['total_estudiantes'] }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card border-success">
                                        <div class="card-body text-center">
                                            <h6 class="text-success mb-2">
                                                <i class="fas fa-check-circle"></i> Asistencias Registradas
                                            </h6>
                                            <h3 class="text-success">{{ $estadisticas_sesion['asistencias_registradas'] }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card border-warning">
                                        <div class="card-body text-center">
                                            <h6 class="text-warning mb-2">
                                                <i class="fas fa-edit"></i> Permiso de Edición
                                            </h6>
                                            <h3 class="{{ $estadisticas_sesion['puede_editar'] ? 'text-success' : 'text-danger' }}">
                                                {{ $estadisticas_sesion['puede_editar'] ? 'Permitido' : 'Denegado' }}
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($estadisticas_sesion['puede_editar'])
                                <!-- Formulario de edición de asistencia -->
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header bg-warning text-dark">
                                                <h5 class="mb-0"><i class="fas fa-edit"></i> Editar Asistencia</h5>
                                                <small class="text-muted">Modifica los registros de asistencia existentes</small>
                                            </div>
                                            <div class="card-body">
                                                <form id="form-editar-asistencia" class="attendance-form">
                                                    <input type="hidden" name="sesion_clase_id" value="{{ $sesion->sesion_id }}">

                                                    <!-- Tabla de edición de asistencia -->
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
                                                                    <th class="text-center" style="min-width: 120px;">Estado Actual</th>
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
                                                                        </button>
                                                                        <input type="radio" name="asistencia_{{ $estudiante->matricula_id }}" value="P"
                                                                               {{ $estudiante->asistencia_actual === 'P' ? 'checked' : '' }}
                                                                               class="attendance-radio d-none"
                                                                               data-matricula-id="{{ $estudiante->matricula_id }}">
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <button type="button" class="btn btn-sm btn-outline-danger attendance-btn {{ $estudiante->asistencia_actual === 'A' ? 'active btn-danger' : '' }}"
                                                                                data-matricula-id="{{ $estudiante->matricula_id }}"
                                                                                data-value="A"
                                                                                title="Marcar como Ausente">
                                                                            <i class="fas fa-times"></i>
                                                                        </button>
                                                                        <input type="radio" name="asistencia_{{ $estudiante->matricula_id }}" value="A"
                                                                               {{ $estudiante->asistencia_actual === 'A' ? 'checked' : '' }}
                                                                               class="attendance-radio d-none"
                                                                               data-matricula-id="{{ $estudiante->matricula_id }}">
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <button type="button" class="btn btn-sm btn-outline-warning attendance-btn {{ $estudiante->asistencia_actual === 'T' ? 'active btn-warning' : '' }}"
                                                                                data-matricula-id="{{ $estudiante->matricula_id }}"
                                                                                data-value="T"
                                                                                title="Marcar como Tarde">
                                                                            <i class="fas fa-clock"></i>
                                                                        </button>
                                                                        <input type="radio" name="asistencia_{{ $estudiante->matricula_id }}" value="T"
                                                                               {{ $estudiante->asistencia_actual === 'T' ? 'checked' : '' }}
                                                                               class="attendance-radio d-none"
                                                                               data-matricula-id="{{ $estudiante->matricula_id }}">
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <button type="button" class="btn btn-sm btn-outline-info attendance-btn {{ $estudiante->asistencia_actual === 'J' ? 'active btn-info' : '' }}"
                                                                                data-matricula-id="{{ $estudiante->matricula_id }}"
                                                                                data-value="J"
                                                                                title="Marcar como Justificado">
                                                                            <i class="fas fa-file-medical"></i>
