@extends('cplantilla.bprincipal')
@section('titulo', 'Editar usuario')
@section('contenidoplantilla')
<style>
    .form-bordered { margin: 0; border: none; padding-top: 15px; padding-bottom: 15px; border-bottom: 1px dashed #eaedf1; }
    .card-body.info { background: #f3f3f3; border-bottom: 1px solid rgba(0, 0, 0, .125); border-top: 1px solid rgba(0, 0, 0, .125); color: #F59D24; }
    .card-body.info p { margin-bottom: 0px; font-family: "Quicksand", sans-serif; font-weight: 600; color: #004a92; }
    .estilo-info { margin-bottom: 0px; font-family: "Quicksand", sans-serif; font-weight: 700; font-size: clamp(0.9rem, 2.0vw, 0.9rem) !important; }
    .btn-primary { margin-top: 1rem; background: #007bff !important; border: none; transition: background-color 0.2s ease, transform 0.1s ease; margin-bottom: 0px; font-family: "Quicksand", sans-serif; font-weight: 700; font-size: clamp(0.9rem, 2.0vw, 0.9rem) !important; }
    .btn-primary:hover { background-color: #0056b3 !important; transform: scale(1.01); }
    .btn-danger { margin-top: 1rem; background: #dc3545 !important; border: none; font-family: "Quicksand", sans-serif; font-weight: 700; font-size: clamp(0.9rem, 2.0vw, 0.9rem) !important; }
    .btn-danger:hover { background-color: #b52a37 !important; transform: scale(1.01); }
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
                    <i class="fas fa-user-edit m-1"></i>&nbsp;Editar Usuario
                    <div class="float-right"><i class="fas fa-chevron-down"></i></div>
                </button>
                <div class="card-body info">
                    <div class="d-flex align-items-center">
                        <div>
                            <i class="fas fa-exclamation-circle fa-2x" style="color: #0A8CB3;"></i>
                        </div>
                        <div class="p-2 flex-fill">
                            <p class="mb-0" style="font-size:1.1rem; color:#004a92; font-weight:700;">
                                Modifique los datos del usuario. Todos los campos marcados con * son obligatorios.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="collapse show" id="collapseExample0">
                    <div class="card card-body rounded-0 border-0 pt-3 pb-2" style="background-color: #f8fbff !important; border: 1px solid #0A8CB3; margin-top: 18px;">
                        <form method="POST" action="{{ route('usuarios.update', $usuario) }}" id="formUsuario">
                            @csrf
                            @method('PUT')
                            <div class="row mb-2">
                                <div class="col-md-6 mb-2">
                                    <div class="form-group">
                                        <label for="username"><i class="fas fa-user mr-2"></i>Usuario *</label>
                                        <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username', $usuario->username) }}" required maxlength="50" autocomplete="off">
                                        @error('username')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="form-group">
                                        <label for="password_hash"><i class="fas fa-lock mr-2"></i>Contraseña *</label>
                                        <input type="password" class="form-control @error('password_hash') is-invalid @enderror" id="password_hash" name="password_hash" value="{{ old('password_hash', $usuario->password_hash) }}" required maxlength="255" autocomplete="off">
                                        @error('password_hash')
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
                                        <label for="nombres"><i class="fas fa-user-tag mr-2"></i>Nombres *</label>
                                        <input type="text" class="form-control @error('nombres') is-invalid @enderror" id="nombres" name="nombres" value="{{ old('nombres', $usuario->nombres) }}" required maxlength="100" autocomplete="off">
                                        @error('nombres')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="form-group">
                                        <label for="apellidos"><i class="fas fa-user-tag mr-2"></i>Apellidos *</label>
                                        <input type="text" class="form-control @error('apellidos') is-invalid @enderror" id="apellidos" name="apellidos" value="{{ old('apellidos', $usuario->apellidos) }}" required maxlength="100" autocomplete="off">
                                        @error('apellidos')
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
                                        <label for="email"><i class="fas fa-envelope mr-2"></i>Email *</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $usuario->email) }}" required maxlength="100" autocomplete="off">
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="form-group">
                                        <label for="rol"><i class="fas fa-user-shield mr-2"></i>Rol *</label>
                                        <select class="form-control @error('rol') is-invalid @enderror" id="rol" name="rol" required>
                                            <option value="">Seleccione un rol</option>
                                            <option value="Administrador" {{ old('rol', $usuario->rol) == 'Administrador' ? 'selected' : '' }}>Administrador</option>
                                            <option value="Secretaría" {{ old('rol', $usuario->rol) == 'Secretaría' ? 'selected' : '' }}>Secretaría</option>
                                            <option value="Profesor" {{ old('rol', $usuario->rol) == 'Profesor' ? 'selected' : '' }}>Profesor</option>
                                            <option value="Contador" {{ old('rol', $usuario->rol) == 'Contador' ? 'selected' : '' }}>Contador</option>
                                            <option value="Estudiante" {{ old('rol', $usuario->rol) == 'Estudiante' ? 'selected' : '' }}>Estudiante</option>
                                            <option value="Representante" {{ old('rol', $usuario->rol) == 'Representante' ? 'selected' : '' }}>Representante</option>
                                        </select>
                                        @error('rol')
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
                                        <label for="estado"><i class="fas fa-flag mr-2"></i>Estado *</label>
                                        <select class="form-control @error('estado') is-invalid @enderror" id="estado" name="estado" required>
                                            <option value="Activo" {{ old('estado', $usuario->estado) == 'Activo' ? 'selected' : '' }}>Activo</option>
                                            <option value="Inactivo" {{ old('estado', $usuario->estado) == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                                            <option value="Bloqueado" {{ old('estado', $usuario->estado) == 'Bloqueado' ? 'selected' : '' }}>Bloqueado</option>
                                        </select>
                                        @error('estado')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="form-group">
                                        <label for="ultima_sesion"><i class="fas fa-clock mr-2"></i>Última Sesión</label>
                                        <input type="datetime-local" class="form-control @error('ultima_sesion') is-invalid @enderror" id="ultima_sesion" name="ultima_sesion" value="{{ old('ultima_sesion', $usuario->ultima_sesion ? \Carbon\Carbon::parse($usuario->ultima_sesion)->format('Y-m-d\TH:i') : '') }}">
                                        @error('ultima_sesion')
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
                                        <label for="cambio_password_requerido"><i class="fas fa-key mr-2"></i>Cambio de contraseña requerido</label>
                                        <select class="form-control @error('cambio_password_requerido') is-invalid @enderror" id="cambio_password_requerido" name="cambio_password_requerido" required>
                                            <option value="1" {{ old('cambio_password_requerido', $usuario->cambio_password_requerido) == 1 ? 'selected' : '' }}>Sí</option>
                                            <option value="0" {{ old('cambio_password_requerido', $usuario->cambio_password_requerido) == 0 ? 'selected' : '' }}>No</option>
                                        </select>
                                        @error('cambio_password_requerido')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-6 mb-2">
                                    <div class="form-group" id="profesor_group" style="display: none;">
                                        <label for="profesor_id"><i class="fas fa-chalkboard-teacher mr-2"></i>Profesor</label>
                                        <select class="form-control @error('profesor_id') is-invalid @enderror" id="profesor_id" name="profesor_id">
                                            <option value="">Seleccione un profesor</option>
                                            @foreach($profesores as $profesor)
                                                <option value="{{ $profesor->profesor_id }}" {{ old('profesor_id', $usuario->profesor_id ?? '') == $profesor->profesor_id ? 'selected' : '' }}>
                                                    {{ trim(($profesor->nombres ?? '') . ' ' . ($profesor->apellidos ?? '')) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('profesor_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group" id="estudiante_group" style="display: none;">
                                        <label for="estudiante_id"><i class="fas fa-user-graduate mr-2"></i>Estudiante</label>
                                        <select class="form-control @error('estudiante_id') is-invalid @enderror" id="estudiante_id" name="estudiante_id">
                                            <option value="">Seleccione un estudiante</option>
                                            @foreach($estudiantes as $estudiante)
                                                <option value="{{ $estudiante->estudiante_id }}" {{ old('estudiante_id', $usuario->estudiante_id ?? '') == $estudiante->estudiante_id ? 'selected' : '' }}>
                                                    {{ trim(($estudiante->nombres ?? '') . ' ' . ($estudiante->apellidos ?? '')) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('estudiante_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group" id="representante_group" style="display: none;">
                                        <label for="representante_id"><i class="fas fa-user-friends mr-2"></i>Representante</label>
                                        <select class="form-control @error('representante_id') is-invalid @enderror" id="representante_id" name="representante_id">
                                            <option value="">Seleccione un representante</option>
                                            @foreach($representantes as $representante)
                                                @php
                                                    $nombreCompleto = trim(($representante->nombres ?? '') . ' ' . ($representante->apellidos ?? ''));
                                                @endphp
                                                <option value="{{ $representante->representante_id }}" {{ old('representante_id', $usuario->representante_id ?? '') == $representante->representante_id ? 'selected' : '' }}>
                                                    {{ $nombreCompleto ?: 'Sin nombre' }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('representante_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <!-- Última Sesión field -->
                                </div>
                            </div>
                            <div class="form-row mt-3">
                                <div class="col-md-12 d-flex justify-content-start gap-2">
                                    <button type="submit" class="btn btn-primary mr-2"><i class="fas fa-save"></i> Guardar</button>
                                    <a href="{{ route('usuarios.index') }}" class="btn btn-danger" type="button"><i class="fas fa-ban"></i> Cancelar</a>
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
        const form = document.getElementById('formUsuario');
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
        function toggleRoleFields() {
            var rol = document.getElementById('rol').value;
            document.getElementById('profesor_group').style.display = rol === 'Profesor' ? 'block' : 'none';
            document.getElementById('estudiante_group').style.display = rol === 'Estudiante' ? 'block' : 'none';
            document.getElementById('representante_group').style.display = rol === 'Representante' ? 'block' : 'none';
        }
        document.getElementById('rol').addEventListener('change', toggleRoleFields);
        toggleRoleFields(); // inicializa al cargar
    });
</script>
@endsection
