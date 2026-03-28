@extends('cplantilla.bprincipal')
@section('titulo', 'Editar Perfil')
@section('contenidoplantilla')

<style>
    .profile-edit-header {
        background: linear-gradient(135deg, #0F3E61, #2378ba);
        border-radius: 15px;
        padding: 30px;
        color: white;
        margin-bottom: 30px;
    }

    .profile-avatar-edit {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        border: 3px solid rgba(255, 255, 255, 0.8);
        object-fit: cover;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.25);
        cursor: pointer;
        transition: transform 0.2s;
    }

    .profile-avatar-edit:hover {
        transform: scale(1.05);
    }

    .form-card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }

    .form-section-title {
        color: #003f77;
        font-weight: 700;
        margin-bottom: 20px;
        border-left: 5px solid #0d6efd;
        padding-left: 15px;
    }

    .form-label {
        font-weight: 600;
        color: #003f77;
    }

    .btn-save {
        background: #007bff;
        border: none;
        padding: 12px 40px;
        border-radius: 25px;
        font-weight: 600;
        font-size: 1.1rem;
        color: white;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(0, 123, 255, 0.4);
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 123, 255, 0.6);
        background: #0056b3;
    }

    .btn-cancel {
        background: #6c757d;
        border: none;
        padding: 12px 40px;
        border-radius: 25px;
        font-weight: 600;
        font-size: 1.1rem;
        color: white;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(108, 117, 125, 0.4);
    }

    .btn-cancel:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(108, 117, 125, 0.6);
        background: #545b62;
    }

    .password-section {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-top: 20px;
    }

    .photo-upload-area {
        border: 2px dashed #dee2e6;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        background: #f8f9fa;
        transition: all 0.3s;
        cursor: pointer;
    }

    .photo-upload-area:hover {
        border-color: #0d6efd;
        background: #e7f3ff;
    }

    .role-specific-section {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        border-radius: 10px;
        padding: 20px;
        margin-top: 20px;
    }

    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }

    .invalid-feedback {
        display: block;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Profile Edit Header -->
            <div class="profile-edit-header">
                <div class="row align-items-center">
                    <div class="col-md-3 text-center">
                        <div class="position-relative">
                            <img src="{{ $user->foto_url ? asset('storage/' . $user->foto_url) . '?v=' . $user->updated_at->timestamp : asset('adminlte/assets/img/profile.jpg') }}"
                                 alt="Foto de perfil"
                                 class="profile-avatar-edit mb-3"
                                 id="profile-avatar-preview">
                            <div class="photo-upload-area mt-2" onclick="document.getElementById('photo-input').click()">
                                <i class="fas fa-camera fa-2x text-muted mb-2"></i>
                                <div class="small">Cambiar foto</div>
                            </div>
                            @if($user->foto_url)
                                <div class="mt-2">
                                    <small class="text-muted">Foto actual: {{ basename($user->foto_url) }}</small>
                                </div>
                            @endif
                            @if(session('success'))
                                <div class="mt-2">
                                    <small class="text-success fw-bold">✓ Foto actualizada correctamente</small>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-9">
                        <h3 class="mb-3">Editar Perfil</h3>
                        <p class="mb-0">Actualiza tu información personal y configura tu perfil según tus necesidades.</p>
                        <div class="mt-3">
                            @foreach($user->getRoles() as $role)
                                <span class="badge bg-light text-dark me-2">{{ $role->nombre }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" id="profile-form">
                @csrf
                @method('PUT')

                <!-- Campo de foto (dentro del formulario) -->
                <input type="file" id="photo-input" name="photo" class="d-none" accept="image/*">

                <!-- Información Personal -->
                <div class="card form-card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-user me-2"></i>Información Personal</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nombres" class="form-label">Nombres *</label>
                                    <input type="text" class="form-control @error('nombres') is-invalid @enderror"
                                           id="nombres" name="nombres" value="{{ old('nombres', $persona->nombres) }}" required>
                                    @error('nombres')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="apellidos" class="form-label">Apellidos *</label>
                                    <input type="text" class="form-control @error('apellidos') is-invalid @enderror"
                                           id="apellidos" name="apellidos" value="{{ old('apellidos', $persona->apellidos) }}" required>
                                    @error('apellidos')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="dni" class="form-label">DNI</label>
                                    <input type="text" class="form-control" id="dni" value="{{ $persona->dni }}" readonly>
                                    <div class="form-text">El DNI no se puede modificar</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                                    <input type="date" class="form-control @error('fecha_nacimiento') is-invalid @enderror"
                                           id="fecha_nacimiento" name="fecha_nacimiento"
                                           value="{{ old('fecha_nacimiento', $persona->fecha_nacimiento ? $persona->fecha_nacimiento->format('Y-m-d') : '') }}">
                                    @error('fecha_nacimiento')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="genero" class="form-label">Género</label>
                                    <select class="form-control @error('genero') is-invalid @enderror" id="genero" name="genero">
                                        <option value="">Seleccionar...</option>
                                        <option value="M" {{ old('genero', $persona->genero) == 'M' ? 'selected' : '' }}>Masculino</option>
                                        <option value="F" {{ old('genero', $persona->genero) == 'F' ? 'selected' : '' }}>Femenino</option>
                                    </select>
                                    @error('genero')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="telefono" class="form-label">Teléfono</label>
                                    <input type="tel" class="form-control @error('telefono') is-invalid @enderror"
                                           id="telefono" name="telefono" value="{{ old('telefono', $persona->telefono) }}">
                                    @error('telefono')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="direccion" class="form-label">Dirección</label>
                                    <input type="text" class="form-control @error('direccion') is-invalid @enderror"
                                           id="direccion" name="direccion" value="{{ old('direccion', $persona->direccion) }}">
                                    @error('direccion')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información de Cuenta -->
                <div class="card form-card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-shield-alt me-2"></i>Información de Cuenta</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="username" class="form-label">Nombre de Usuario *</label>
                                    <input type="text" class="form-control @error('username') is-invalid @enderror"
                                           id="username" name="username" value="{{ old('username', $user->username) }}" required>
                                    @error('username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Correo Electrónico *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                           id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cambio de Contraseña -->
                <div class="card form-card">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0"><i class="fas fa-key me-2"></i>Cambio de Contraseña (Opcional)</h5>
                    </div>
                    <div class="card-body password-section">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Deja estos campos vacíos si no deseas cambiar tu contraseña.
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Contraseña Actual</label>
                                    <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                                           id="current_password" name="current_password">
                                    @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="password" class="form-label">Nueva Contraseña</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                           id="password" name="password">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Mínimo 8 caracteres</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
                                    <input type="password" class="form-control"
                                           id="password_confirmation" name="password_confirmation">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información Específica por Rol -->
                @if($user->hasRole('Docente') && isset($roleSpecificData['docente']))
                    <div class="card form-card role-specific-section">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Información Profesional (Docente)</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="especialidad" class="form-label text-white">Especialidad</label>
                                        <input type="text" class="form-control @error('especialidad') is-invalid @enderror"
                                               id="especialidad" name="especialidad"
                                               value="{{ old('especialidad', $roleSpecificData['docente']->especialidad) }}">
                                        @error('especialidad')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="grado_academico" class="form-label text-white">Grado Académico</label>
                                        <input type="text" class="form-control @error('grado_academico') is-invalid @enderror"
                                               id="grado_academico" name="grado_academico"
                                               value="{{ old('grado_academico', $roleSpecificData['docente']->grado_academico) }}">
                                        @error('grado_academico')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="experiencia" class="form-label text-white">Años de Experiencia</label>
                                        <input type="number" class="form-control @error('experiencia') is-invalid @enderror"
                                               id="experiencia" name="experiencia" min="0"
                                               value="{{ old('experiencia', $roleSpecificData['docente']->experiencia) }}">
                                        @error('experiencia')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if($user->hasRole('Representante') && isset($roleSpecificData['representante']))
                    <div class="card form-card">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0"><i class="fas fa-user-tie me-2"></i>Información Profesional (Representante)</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="ocupacion" class="form-label">Ocupación</label>
                                        <input type="text" class="form-control @error('ocupacion') is-invalid @enderror"
                                               id="ocupacion" name="ocupacion"
                                               value="{{ old('ocupacion', $roleSpecificData['representante']->ocupacion) }}">
                                        @error('ocupacion')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="empresa" class="form-label">Empresa</label>
                                        <input type="text" class="form-control @error('empresa') is-invalid @enderror"
                                               id="empresa" name="empresa"
                                               value="{{ old('empresa', $roleSpecificData['representante']->empresa) }}">
                                        @error('empresa')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Botones de Acción -->
                <div class="card form-card">
                    <div class="card-body text-center">
                        <a href="{{ route('profile.show') }}" class="btn btn-cancel me-3">
                            <i class="fas fa-times me-2"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-save">
                            <i class="fas fa-save me-2"></i>Guardar Cambios
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Preview de imagen de perfil
    document.getElementById('photo-input').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profile-avatar-preview').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    // Validación de formulario
    const form = document.getElementById('profile-form');
    form.addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('password_confirmation').value;
        const currentPassword = document.getElementById('current_password').value;

        if (password || confirmPassword || currentPassword) {
            if (!currentPassword) {
                e.preventDefault();
                alert('Debes ingresar tu contraseña actual para cambiarla.');
                document.getElementById('current_password').focus();
                return false;
            }

            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Las contraseñas nuevas no coinciden.');
                document.getElementById('password_confirmation').focus();
                return false;
            }

            if (password.length < 8) {
                e.preventDefault();
                alert('La nueva contraseña debe tener al menos 8 caracteres.');
                document.getElementById('password').focus();
                return false;
            }
        }
    });
});
</script>

@endsection
