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
