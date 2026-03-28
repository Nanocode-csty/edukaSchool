@extends('cplantilla.bprincipal')
@section('titulo','Confirmar Eliminación')
@section('contenidoplantilla')
<style>
    .form-bordered {
        margin: 0;
        border: none;
        padding-top: 15px;
        padding-bottom: 15px;
        border-bottom: 1px dashed #eaedf1;
    }
    .card-body.info {
        background: #f3f3f3;
        border-bottom: 1px solid rgba(0, 0, 0, .125);
        border-top: 1px solid rgba(0, 0, 0, .125);
        color: #F59D24;
    }
    .card-body.info p {
        margin-bottom: 0px;
        font-family: "Quicksand", sans-serif;
        font-weight: 600;
        color: #004a92;
    }
    .estilo-info {
        margin-bottom: 0px;
        font-family: "Quicksand", sans-serif;
        font-weight: 700;
        font-size: clamp(0.9rem, 2.0vw, 0.9rem) !important;
    }
    .btn-primary {
        margin-top: 1rem;
        background: #007bff !important;
        border: none;
        transition: background-color 0.2s ease, transform 0.1s ease;
        margin-bottom: 0px;
        font-family: "Quicksand", sans-serif;
        font-weight: 700;
        font-size: clamp(0.9rem, 2.0vw, 0.9rem) !important;
    }
    .btn-primary:hover {
        background-color: #0056b3 !important;
        transform: scale(1.01);
    }
    .btn-danger {
        margin-top: 1rem;
        background: #dc3545 !important;
        border: none;
        font-family: "Quicksand", sans-serif;
        font-weight: 700;
        font-size: clamp(0.9rem, 2.0vw, 0.9rem) !important;
    }
    .btn-danger:hover {
        background-color: #b52a37 !important;
        transform: scale(1.01);
    }
</style>
<div class="container-fluid" id="contenido-principal" style="position: relative;">
    @include('ccomponentes.loader', ['id' => 'loaderPrincipal'])
    <div class="row mt-4 ml-1 mr-1">
        <div class="col-12">
            <div class="box_block">
                <button class="estilo-info btn btn-block text-left rounded-0 btn_header header_6" type="button"
                    data-toggle="collapse" data-target="#collapseExample0" aria-expanded="true"
                    aria-controls="collapseExample"
                    style="background: #dc3545 !important; font-weight: bold; color: white;">
                    <i class="fas fa-exclamation-triangle m-1"></i>&nbsp;Confirmar Eliminación de Curso
                    <div class="float-right"><i class="fas fa-chevron-down"></i></div>
                </button>
                <div class="card-body info">
                    <div class="d-flex align-items-center">
                        <div>
                            <i class="fas fa-exclamation-circle fa-2x" style="color: #dc3545;"></i>
                        </div>
                        <div class="p-2 flex-fill">
                            <p class="mb-0" style="font-size:1.1rem; color:#b52a37; font-weight:700;">
                                ¿Está seguro que desea eliminar este curso? <span style="color:#dc3545;">Esta acción no se puede deshacer.</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="collapse show" id="collapseExample0">
                    <div class="card card-body rounded-0 border-0 pt-3 pb-2" style="background-color: #fff7f7 !important; border: 1px solid #dc3545; margin-top: 18px;">
                        <div class="mb-3 estilo-info">
                            <div class="row mb-2">
                                <div class="col-md-6 mb-2">
                                    <div class="p-3 border rounded bg-light h-100">
                                        <span class="font-weight-bold text-secondary"><i class="fas fa-barcode mr-2"></i>Código:</span>
                                        <span class="ml-2 text-dark">{{ $curso->curso_id }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="p-3 border rounded bg-light h-100">
                                        <span class="font-weight-bold text-secondary"><i class="fas fa-graduation-cap mr-2"></i>Grado:</span>
                                        <span class="ml-2 text-dark">{{ $curso->grado->nombre ?? 'No asignado' }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-6 mb-2">
                                    <div class="p-3 border rounded bg-light h-100">
                                        <span class="font-weight-bold text-secondary"><i class="fas fa-layer-group mr-2"></i>Sección:</span>
                                        <span class="ml-2 text-dark">{{ $curso->seccion->nombre ?? 'No asignado' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="p-3 border rounded bg-light h-100">
                                        <span class="font-weight-bold text-secondary"><i class="fas fa-calendar-alt mr-2"></i>Año Lectivo:</span>
                                        <span class="ml-2 text-dark">{{ $curso->anoLectivo->nombre ?? $curso->anoLectivo->descripcion ?? 'No asignado' }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-6 mb-2">
                                    <div class="p-3 border rounded bg-light h-100">
                                        <span class="font-weight-bold text-secondary"><i class="fas fa-door-open mr-2"></i>Aula:</span>
                                        <span class="ml-2 text-dark">{{ $curso->aula->nombre ?? 'No asignado' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="p-3 border rounded bg-light h-100">
                                        <span class="font-weight-bold text-secondary"><i class="fas fa-chalkboard-teacher mr-2"></i>Profesor Principal:</span>
                                        <span class="ml-2 text-dark">{{ $curso->profesor->nombres ?? 'No asignado' }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-6 mb-2">
                                    <div class="p-3 border rounded bg-light h-100">
                                        <span class="font-weight-bold text-secondary"><i class="fas fa-users mr-2"></i>Cupo Máximo:</span>
                                        <span class="ml-2 text-dark">{{ $curso->cupo_maximo }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="p-3 border rounded bg-light h-100">
                                        <span class="font-weight-bold text-secondary"><i class="fas fa-flag mr-2"></i>Estado:</span>
                                        <span class="ml-2 text-dark">{{ $curso->estado }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('registrarcurso.destroy', $curso->curso_id)}}" id="formEliminarCurso">
                            @method('DELETE')
                            @csrf
                            <div class="form-row mt-3">
                                <div class="col-md-12 d-flex justify-content-start gap-2">
                                    <button type="submit" class="btn btn-danger mr-2"><i class="fas fa-check-square"></i> SÍ, Eliminar</button>
                                    <a href="{{ route('registrarcurso.index')}}" class="btn btn-primary" type="button"><i class="fas fa-times-circle"></i> NO, Cancelar</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const loader = document.getElementById('loaderPrincipal');
        const contenido = document.getElementById('contenido-principal');
        const form = document.getElementById('formEliminarCurso');
        if (loader) loader.style.display = 'none';
        if (contenido) contenido.style.opacity = '1';
        if (form) {
            form.addEventListener('submit', function() {
                if (loader && contenido) {
                    loader.style.display = 'flex';
                    contenido.style.opacity = '0.5';
                }
            });
        }
        // Loader para cancelar
        document.querySelectorAll('a.btn-primary').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                if (loader && contenido) {
                    loader.style.display = 'flex';
                    contenido.style.opacity = '0.5';
                }
            });
        });
        window.addEventListener('pageshow', function(event) {
            if (loader) loader.style.display = 'none';
            if (contenido) contenido.style.opacity = '1';
        });
    });
</script>
@endsection
