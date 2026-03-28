@extends('cplantilla.bprincipal')
@section('titulo', 'Editar Nivel Educativo')
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
                    <i class="fas fa-file-signature m-1"></i>&nbsp;Editar Nivel Educativo
                    <div class="float-right"><i class="fas fa-chevron-down"></i></div>
                </button>
                <div class="card-body info">
                    <div class="d-flex align-items-center">
                        <div>
                            <i class="fas fa-exclamation-circle fa-2x" style="color: #0A8CB3;"></i>
                        </div>
                        <div class="p-2 flex-fill">
                            <p class="mb-0" style="font-size:1.1rem; color:#004a92; font-weight:700;">
                                Modifique los datos del nivel educativo según sea necesario. Los campos marcados con * son obligatorios.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="collapse show" id="collapseExample0">
                    <div class="card card-body rounded-0 border-0 pt-3 pb-2" style="background-color: #f8fbff !important; border: 1px solid #0A8CB3; margin-top: 18px;">
                        <form method="POST" action="{{ route('registrarnivel.update', $nivel->nivel_id) }}" id="formNivel">
                            @method('PUT')
                            @csrf
                            <div class="row mb-2">
                                <div class="col-md-6 mb-2">
                                    <div class="p-3 border rounded bg-light h-100">
                                        <span class="font-weight-bold text-secondary"><i class="fas fa-barcode mr-2"></i>ID:</span>
                                        <span class="ml-2 text-dark">{{ $nivel->nivel_id }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-6 mb-2">
                                    <div class="form-group">
                                        <label for="nombre"><i class="fas fa-graduation-cap mr-2"></i>Nombre *</label>
                                        <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre', $nivel->nombre) }}" required maxlength="50" minlength="2">
                                        @error('nombre')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="form-group">
                                        <label for="descripcion"><i class="fas fa-align-left mr-2"></i>Descripción</label>
                                        <input type="text" class="form-control @error('descripcion') is-invalid @enderror" id="descripcion" name="descripcion" value="{{ old('descripcion', $nivel->descripcion) }}" maxlength="65535">
                                        @error('descripcion')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-row mt-3">
                                <div class="col-md-12 d-flex justify-content-start gap-2">
                                    <button type="submit" class="btn btn-primary mr-2"><i class="fas fa-save"></i> Actualizar</button>
                                    <a href="{{ route('registrarnivel.index') }}" class="btn btn-danger" type="button"><i class="fas fa-ban"></i> Cancelar</a>
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
        const form = document.getElementById('formNivel');
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
        // Loader para cancelar (volver a registrar)
        const cancelarBtn = document.querySelector('a.btn-danger[href="{{ route('registrarnivel.index') }}"]');
        if (cancelarBtn) {
            cancelarBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (loader && contenido) {
                    loader.style.display = 'flex';
                    contenido.style.opacity = '0.5';
                }
                setTimeout(() => {
                    window.location.href = this.href;
                }, 800);
            });
        }
        window.addEventListener('pageshow', function(event) {
            if (loader) loader.style.display = 'none';
            if (contenido) contenido.style.opacity = '1';
        });
    });
</script>
@endsection
