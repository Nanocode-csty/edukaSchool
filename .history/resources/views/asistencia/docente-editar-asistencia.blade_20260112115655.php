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
