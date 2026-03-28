@extends('cplantilla.bprincipal')
@section('titulo', 'Mi Perfil')
@section('contenidoplantilla')

<style>
    .profile-header {
        background: linear-gradient(135deg, #0F3E61, #2378ba);
        border-radius: 15px;
        padding: 30px;
        color: white;
        margin-bottom: 30px;
    }

    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 4px solid rgba(255, 255, 255, 0.8);
        object-fit: cover;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.25);
    }

    .info-card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        transition: transform 0.2s;
    }

    .info-card:hover {
        transform: translateY(-2px);
    }

    .info-label {
        font-weight: 600;
        color: #003f77;
        font-size: 0.9rem;
    }

    .info-value {
        color: #495057;
        font-size: 0.95rem;
    }

    .role-badge {
        background: linear-gradient(45deg, #28a745, #20c997);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 600;
        display: inline-block;
        margin: 5px;
    }

    .section-title {
        color: #003f77;
        font-weight: 700;
        margin-bottom: 20px;
        border-left: 5px solid #0d6efd;
        padding-left: 15px;
    }

    .stats-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .btn-edit {
        background: linear-gradient(45deg, #007bff, #0056b3);
        border: none;
        padding: 12px 30px;
        border-radius: 25px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-edit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,123,255,0.4);
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Profile Header -->
            <div class="profile-header">
                <div class="row align-items-center">
                    <div class="col-md-3 text-center">
                            <img src="{{ $user->foto_url ? asset('storage/' . $user->foto_url) : asset('adminlte/assets/img/profile.jpg') }}"
                                 alt="Foto de perfil"
                                 class="profile-avatar mb-3">
                        <h5 class="mb-0">{{ $persona->nombre_completo }}</h5>
                        <div class="mt-2">
                            @foreach($user->getRoles() as $role)
                                <span class="role-badge">{{ $role->nombre }}</span>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="mb-3">Información Personal</h4>
                                <div class="mb-2">
                                    <strong>DNI:</strong> {{ $persona->dni ?? 'No registrado' }}
                                </div>
                                <div class="mb-2">
                                    <strong>Email:</strong> {{ $user->email }}
                                </div>
                                <div class="mb-2">
                                    <strong>Teléfono:</strong> {{ $persona->telefono ?? 'No registrado' }}
                                </div>
                                <div class="mb-2">
                                    <strong>Fecha de Nacimiento:</strong>
                                    {{ $persona->fecha_nacimiento ? $persona->fecha_nacimiento->format('d/m/Y') : 'No registrada' }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h4 class="mb-3">Información del Sistema</h4>
                                <div class="mb-2">
                                    <strong>Usuario:</strong> {{ $user->username }}
                                </div>
                                <div class="mb-2">
                                    <strong>Última Sesión:</strong>
                                    {{ $user->ultima_sesion ? $user->ultima_sesion->format('d/m/Y H:i') : 'Nunca' }}
                                </div>
                                <div class="mb-2">
                                    <strong>Estado:</strong>
                                    <span class="badge {{ $user->estado == 'Activo' ? 'bg-success' : 'bg-danger' }}">
                                        {{ $user->estado }}
                                    </span>
                                </div>
                                <div class="mb-2">
                                    <strong>Miembro desde:</strong>
                                    {{ $persona->created_at ? $persona->created_at->format('d/m/Y') : 'N/A' }}
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('profile.edit') }}" class="btn btn-light btn-edit">
                                <i class="fas fa-edit me-2"></i>Editar Perfil
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Información Básica -->
                <div class="col-md-6">
                    <div class="card info-card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-user me-2"></i>Información Básica</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <div class="info-label">Nombres</div>
                                        <div class="info-value">{{ $persona->nombres }}</div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <div class="info-label">Apellidos</div>
                                        <div class="info-value">{{ $persona->apellidos }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <div class="info-label">Género</div>
                                        <div class="info-value">
                                            @if($persona->genero == 'M')
                                                Masculino
                                            @elseif($persona->genero == 'F')
                                                Femenino
                                            @else
                                                No especificado
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <div class="info-label">Dirección</div>
                                        <div class="info-value">{{ $persona->direccion ?? 'No registrada' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información de Contacto -->
                <div class="col-md-6">
                    <div class="card info-card">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="fas fa-address-book me-2"></i>Información de Contacto</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="info-label">Email</div>
                                <div class="info-value">{{ $user->email }}</div>
                            </div>
                            <div class="mb-3">
                                <div class="info-label">Teléfono</div>
                                <div class="info-value">{{ $persona->telefono ?? 'No registrado' }}</div>
                            </div>
                            <div class="mb-3">
                                <div class="info-label">Dirección</div>
                                <div class="info-value">{{ $persona->direccion ?? 'No registrada' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información Específica por Rol -->
            @if(isset($roleSpecificData['docente']) && $roleSpecificData['docente'])
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card info-card">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Información Docente</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <div class="info-label">Especialidad</div>
                                            <div class="info-value">{{ $roleSpecificData['docente']->especialidad ?? 'No especificada' }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <div class="info-label">Grado Académico</div>
                                            <div class="info-value">{{ $roleSpecificData['docente']->grado_academico ?? 'No especificado' }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <div class="info-label">Años de Experiencia</div>
                                            <div class="info-value">{{ $roleSpecificData['docente']->experiencia ?? 'No especificada' }} años</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if(isset($roleSpecificData['representante']) && $roleSpecificData['representante'])
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card info-card">
                            <div class="card-header bg-warning text-dark">
                                <h5 class="mb-0"><i class="fas fa-user-tie me-2"></i>Información de Representante</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <div class="info-label">Ocupación</div>
                                            <div class="info-value">{{ $roleSpecificData['representante']->ocupacion ?? 'No especificada' }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <div class="info-label">Empresa</div>
                                            <div class="info-value">{{ $roleSpecificData['representante']->empresa ?? 'No especificada' }}</div>
                                        </div>
                                    </div>
                                </div>
                                @if(isset($roleSpecificData['estudiantes']) && $roleSpecificData['estudiantes']->count() > 0)
                                    <div class="mt-4">
                                        <h6 class="section-title">Estudiantes a Cargo</h6>
                                        <div class="row">
                                            @foreach($roleSpecificData['estudiantes'] as $estudiante)
                                                <div class="col-md-4 mb-3">
                                                    <div class="card border-primary">
                                                        <div class="card-body">
                                                            <h6 class="card-title">{{ $estudiante->persona->nombre_completo }}</h6>
                                                            <p class="card-text small text-muted">
                                                                DNI: {{ $estudiante->persona->dni }}<br>
                                                                Estado: <span class="badge bg-{{ $estudiante->estado == 'Activo' ? 'success' : 'secondary' }}">{{ $estudiante->estado }}</span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if(isset($roleSpecificData['estudiante']) && $roleSpecificData['estudiante'])
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card info-card">
                            <div class="card-header bg-secondary text-white">
                                <h5 class="mb-0"><i class="fas fa-school me-2"></i>Información de Estudiante</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <div class="info-label">Código de Estudiante</div>
                                            <div class="info-value">{{ $roleSpecificData['estudiante']->estudiante_id ?? 'No asignado' }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <div class="info-label">Estado Académico</div>
                                            <div class="info-value">
                                                <span class="badge bg-{{ $roleSpecificData['estudiante']->estado == 'Activo' ? 'success' : 'danger' }}">
                                                    {{ $roleSpecificData['estudiante']->estado }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>

@endsection
