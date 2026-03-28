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

