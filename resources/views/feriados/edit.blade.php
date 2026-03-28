@extends('cplantilla.bprincipal')
@section('titulo', 'Editar Feriado')
@section('contenidoplantilla')
    <style>
        .estilo-info {
            margin-bottom: 0px;
            font-family: "Quicksand", sans-serif;
            font-weight: 700;
            font-size: clamp(0.9rem, 2.0vw, 0.9rem) !important;
        }

        @media (max-width: 576px) {
            .margen-movil {
                margin-left: -29px !important;
                margin-right: -29px !important;
            }

            .margen-movil-2 {
                margin: 0 !important;
                padding: 0 !important;
            }
        }
    </style>

    <div class="container-fluid estilo-info margen-movil-2">
        <div class="row mt-4 ml-1 mr-1">
            <div class="col-12 mb-3">
                <div class="box_block">
                    <button style="background: #0A8CB3 !important; border:none"
                        class="btn btn-primary btn-block text-left rounded-0 btn_header header_6 estilo-info" type="button"
                        data-toggle="collapse" data-target="#collapseEditarFeriado" aria-expanded="true"
                        aria-controls="collapseEditarFeriado">
                        <i class="fas fa-edit"></i>&nbsp;Editar Feriado: {{ $item->nombre }}
                        <div class="float-right"><i class="fas fa-chevron-down"></i></div>
                    </button>
                </div>
                <div class="collapse show" id="collapseEditarFeriado">
                    <div class="card card-body rounded-0 border-0 pt-0"
                        style="padding-left:0.966666666rem;padding-right:0.9033333333333333rem;">
                        <div class="row margen-movil" style="padding:20px;">
                            <div class="col-12">
                                @if (session('error'))
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        {{ session('error') }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif

                                @if (session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <i class="fas fa-check-circle"></i>
                                        {{ session('success') }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif

                                <div class="card" style="border: none">
                                    <div
                                        style="background: #E0F7FA; color: #0A8CB3; font-weight: bold; border: 2px solid #86D2E3; border-bottom: 2px solid #86D2E3; padding: 6px 20px; border-radius:4px 4px 0px 0px;">
                                        <i class="fas fa-calendar-times mr-2"></i>
                                        Información del Feriado
                                    </div>

                                    <div class="card-body"
                                        style="border: 2px solid #86D2E3; border-top: none; border-radius: 0px 0px 4px 4px !important;">
                                        <form id="formularioFeriado" method="POST"
                                            action="{{ route('feriados.update', $item->id) }}" autocomplete="off">
                                            @csrf
                                            @method('PUT')

                                            <div class="row form-group">
                                                <label class="col-md-2 col-form-label">Nombre <span
                                                        style="color: #FF5A6A">(*)</span></label>
                                                <div class="col-md-10">
                                                    <input type="text" name="nombre" id="nombre"
                                                        class="form-control @error('nombre') is-invalid @enderror"
                                                        value="{{ old('nombre', $item->nombre) }}" placeholder="Ej: Día de la Independencia"
                                                        required>
                                                    @error('nombre')
                                                        <div class="invalid-feedback d-block text-start">{{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="row form-group">
                                                <label class="col-md-2 col-form-label">Fecha <span
                                                        style="color: #FF5A6A">(*)</span></label>
                                                <div class="col-md-4">
                                                    <input type="date" name="fecha" id="fecha"
                                                        class="form-control @error('fecha') is-invalid @enderror"
                                                        value="{{ old('fecha', $item->fecha->format('Y-m-d')) }}" required>
                                                    @error('fecha')
                                                        <div class="invalid-feedback d-block text-start">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <label class="col-md-2 col-form-label">Tipo <span
                                                        style="color: #FF5A6A">(*)</span></label>
                                                <div class="col-md-4">
                                                    <select name="tipo" id="tipo"
                                                        class="form-control @error('tipo') is-invalid @enderror" required>
                                                        <option value="">Seleccione un tipo</option>
                                                        @foreach ($tipos as $key => $tipo)
                                                            <option value="{{ $key }}"
                                                                {{ old('tipo', $item->tipo) == $key ? 'selected' : '' }}>
                                                                {{ $tipo }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('tipo')
                                                        <div class="invalid-feedback d-block text-start">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="row form-group">
                                                <label class="col-md-2 col-form-label">Descripción</label>
                                                <div class="col-md-10">
                                                    <textarea name="descripcion" id="descripcion"
                                                        class="form-control @error('descripcion') is-invalid @enderror"
                                                        rows="3" placeholder="Descripción opcional del feriado">{{ old('descripcion', $item->descripcion) }}</textarea>
                                                    @error('descripcion')
                                                        <div class="invalid-feedback d-block text-start">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="row form-group">
                                                <label class="col-md-2 col-form-label">Ubicación</label>
                                                <div class="col-md-4">
                                                    <input type="text" name="ubicacion" id="ubicacion"
                                                        class="form-control @error('ubicacion') is-invalid @enderror"
                                                        value="{{ old('ubicacion', $item->ubicacion) }}" placeholder="Ej: Nacional, Lima, etc.">
                                                    @error('ubicacion')
                                                        <div class="invalid-feedback d-block text-start">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <label class="col-md-2 col-form-label">¿Recuperable?</label>
                                                <div class="col-md-4">
                                                    <div class="form-check">
                                                        <input type="checkbox" name="recuperable" id="recuperable"
                                                            class="form-check-input" value="1"
                                                            {{ old('recuperable', $item->recuperable) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="recuperable">
                                                            Sí, este feriado se puede recuperar
                                                        </label>
                                                    </div>
                                                    <small class="form-text text-muted">
                                                        Indica si las clases perdidas por este feriado pueden recuperarse
                                                    </small>
                                                </div>
                                            </div>

                                            <div class="row form-group">
                                                <label class="col-md-2 col-form-label">Estado</label>
                                                <div class="col-md-10">
                                                    <div class="form-check">
                                                        <input type="checkbox" name="activo" id="activo"
                                                            class="form-check-input" value="1"
                                                            {{ old('activo', $item->activo) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="activo">
                                                            Feriado activo (afecta la programación de clases)
                                                        </label>
                                                    </div>
                                                    <small class="form-text text-muted">
                                                        Los feriados inactivos no afectan la programación automática de clases
                                                    </small>
                                                </div>
                                            </div>

                                            <div class="row form-group">
                                                <label class="col-md-2 col-form-label">Información adicional</label>
                                                <div class="col-md-10">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <small class="text-muted">
                                                                <strong>Creado por:</strong> {{ $item->creador->name ?? 'Sistema' }}<br>
                                                                <strong>Fecha de creación:</strong> {{ $item->created_at->format('d/m/Y H:i') }}
                                                            </small>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <small class="text-muted">
                                                                <strong>Última modificación:</strong> {{ $item->updated_at->format('d/m/Y H:i') }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                    </div>
                                </div>

                                <div class="d-flex justify-content-center mt-4">
                                    <button type="submit" class="btn btn-primary btn-block"
                                        style="background: #FF3F3F !important; border: none; font: bold !important">
                                        <span style="font:bold"><i class="fas fa-save"></i> Actualizar Feriado</span>
                                    </button>
                                    <a href="{{ route('feriados.index') }}"
                                        class="btn btn-secondary ml-2">Cancelar</a>
                                </div>

                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .form-control {
            border: 1px solid #DAA520;
        }
    </style>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Validación básica del formulario
            const form = document.getElementById('formularioFeriado');

            form.addEventListener('submit', function(e) {
                const requiredFields = form.querySelectorAll('[required]');
                let isValid = true;

                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        field.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        field.classList.remove('is-invalid');
                        field.classList.add('is-valid');
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    alert('Por favor, complete todos los campos obligatorios.');
                }
            });
        });
    </script>
@endsection
