@extends('cplantilla.bprincipal')
@section('titulo', 'Registro y listado de cursos')
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
                    style="background: #0A8CB3 !important; font-weight: bold; color: white;">
                    <i class="fas fa-file-signature m-1"></i>&nbsp;Registrar Nuevo Curso
                    <div class="float-right"><i class="fas fa-chevron-down"></i></div>
                </button>
                <div class="card-body info">
                    <div class="d-flex align-items-center">
                        <div>
                            <i class="fas fa-exclamation-circle fa-2x" style="color: #0A8CB3;"></i>
                        </div>
                        <div class="p-2 flex-fill">
                            <p class="mb-0" style="font-size:1.1rem; color:#004a92; font-weight:700;">
                                Complete el siguiente formulario para registrar un nuevo curso. Todos los campos marcados con * son obligatorios.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="collapse show" id="collapseExample0">
                    <div class="card card-body rounded-0 border-0 pt-3 pb-2" style="background-color: #f8fbff !important; border: 1px solid #0A8CB3; margin-top: 18px;">
                        <form method="POST" action="{{ route('registrarcurso.store') }}" id="formCurso">
                            @csrf
                            <div class="row mb-2">
                                <div class="col-md-6 mb-2">
                                    <div class="form-group">
                                        <label for="grado_id"><i class="fas fa-graduation-cap mr-2"></i>Grado</label>
                                        <select class="form-control @error('grado_id') is-invalid @enderror" id="grado_id" name="grado_id" required>
                                            <option value="">Seleccione un grado</option>
                                            @foreach($grados as $grado)
                                                <option value="{{ $grado->grado_id }}">{{ $grado->descripcion }}</option>
                                            @endforeach
                                        </select>
                                        @error('grado_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="form-group">
                                        <label for="seccion_id"><i class="fas fa-layer-group mr-2"></i>Sección</label>
                                        <select class="form-control @error('seccion_id') is-invalid @enderror" id="seccion_id" name="seccion_id" required>
                                            <option value="">Seleccione una sección</option>
                                            @foreach($secciones as $seccion)
                                                <option value="{{ $seccion->seccion_id }}">{{ $seccion->nombre }}</option>
                                            @endforeach
                                        </select>
                                        @error('seccion_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-6 mb-2">
                                    <div class="form-group">
                                        <label for="ano_lectivo_id"><i class="fas fa-calendar-alt mr-2"></i>Año Lectivo</label>
                                        <select class="form-control @error('ano_lectivo_id') is-invalid @enderror" id="ano_lectivo_id" name="ano_lectivo_id" required>
                                            <option value="">Seleccione un año lectivo</option>
                                            @foreach($aniosLectivos as $anio)
                                                <option value="{{ $anio->ano_lectivo_id }}">{{ $anio->nombre }}</option>
                                            @endforeach
                                        </select>
                                        @error('ano_lectivo_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="form-group">
                                        <label for="aula_id"><i class="fas fa-door-open mr-2"></i>Aula</label>
                                        <select class="form-control" id="aula_id" name="aula_id" required>
                                            <option value="">Seleccione un aula</option>
                                            @foreach($aulas as $aula)
                                                <option value="{{ $aula->aula_id }}">{{ $aula->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-6 mb-2">
                                    <div class="form-group">
                                        <label for="profesor_principal_id"><i class="fas fa-chalkboard-teacher mr-2"></i>Profesor Principal</label>
                                        <select class="form-control" id="profesor_principal_id" name="profesor_principal_id" required>
                                            <option value="">Seleccione un profesor</option>
                                            @foreach($profesores as $profesor)
                                                <option value="{{ $profesor->profesor_id }}">{{ $profesor->nombres }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="form-group">
                                        <label for="cupo_maximo"><i class="fas fa-users mr-2"></i>Cupo Máximo</label>
                                        <input type="number" class="form-control @error('cupo_maximo') is-invalid @enderror" id="cupo_maximo" name="cupo_maximo" value="30" required>
                                        @error('cupo_maximo')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-6 mb-2">
                                    <div class="form-group">
                                        <label for="estado"><i class="fas fa-flag mr-2"></i>Estado</label>
                                        <select class="form-control @error('estado') is-invalid @enderror" id="estado" name="estado" required>
                                            <option value="" disabled selected>Seleccione un estado</option>
                                            <option value="En Planificación">En Planificación</option>
                                            <option value="Disponible">Disponible</option>
                                            <option value="Completo">Completo</option>
                                            <option value="En Curso">En Curso</option>
                                            <option value="Finalizado">Finalizado</option>
                                        </select>
                                        @error('estado')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-row mt-3">
                                <div class="col-md-12 d-flex justify-content-start gap-2">
                                    <button type="submit" class="btn btn-primary mr-2"><i class="fas fa-save"></i> Guardar</button>
                                    <a href="{{ route('registrarcurso.index') }}" class="btn btn-danger" type="button"><i class="fas fa-ban"></i> Cancelar</a>
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
        const form = document.getElementById('formCurso');
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
        window.addEventListener('pageshow', function(event) {
            if (loader) loader.style.display = 'none';
            if (contenido) contenido.style.opacity = '1';
        });
    });
</script>
@endsection
