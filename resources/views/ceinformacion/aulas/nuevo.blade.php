@extends('cplantilla.bprincipal')
<link rel="stylesheet" href="{{ asset('css/estilos-modulos.css') }}">
@section('titulo', 'Nueva Aula')

@section('contenidoplantilla')

<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">
                <i class="fas fa-plus-circle"></i> Registrar Nueva Aula
            </h4>
        </div>

        <div class="card-body">
            {{-- Alertas de éxito o error --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            @endif

            {{-- Formulario --}}
            <form action="{{ route('aulas.store') }}" method="POST">
                @csrf

                {{-- Nombre --}}
                <div class="form-group mb-3">
                    <label for="nombre" class="fw-bold">Nombre del Aula</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre') }}" required>
                </div>

                {{-- Capacidad --}}
                <div class="form-group mb-3">
                    <label for="capacidad" class="fw-bold">Capacidad</label>
                    <input type="number" name="capacidad" id="capacidad" class="form-control" value="{{ old('capacidad') }}" required>
                </div>

                {{-- Ubicación --}}
                <div class="form-group mb-3">
                    <label for="ubicacion" class="fw-bold">Ubicación</label>
                    <input type="text" name="ubicacion" id="ubicacion" class="form-control" value="{{ old('ubicacion') }}">
                </div>

                {{-- Tipo --}}
                <div class="form-group mb-3">
                    <label for="tipo" class="fw-bold">Tipo</label>
                    <select name="tipo" id="tipo" class="form-control" required>
                        <option value="">-- Seleccione un tipo --</option>
                        <option value="Regular" {{ old('tipo') == 'Regular' ? 'selected' : '' }}>Aula regular</option>
                        <option value="Laboratorio" {{ old('tipo') == 'Laboratorio' ? 'selected' : '' }}>Laboratorio</option>
                        <option value="Taller" {{ old('tipo') == 'Taller' ? 'selected' : '' }}>Sala de cómputo</option>
                        <option value="Auditorio" {{ old('tipo') == 'Auditorio' ? 'selected' : '' }}>Auditorio</option>
                        <option value="Otro" {{ old('tipo') == 'Otro' ? 'selected' : '' }}>Otro</option>
                    </select>
                </div>

                {{-- Botones --}}
                <div class="d-flex justify-content-between">
                    <a href="{{ route('aulas.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Registrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

