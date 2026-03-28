@extends('cplantilla.bprincipal')

@section('titulo', 'Nueva Asignatura')

@section('contenidoplantilla')

<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">
                <i class="fas fa-plus-circle"></i> Registrar Nueva Asignatura
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
            <form action="{{ route('asignaturas.store') }}" method="POST">
                @csrf

                {{-- Código --}}
                <div class="form-group mb-3">
                    <label for="codigo" class="fw-bold">Código</label>
                    <input type="text" name="codigo" id="codigo" class="form-control" value="{{ old('codigo') }}" required>
                </div>

                {{-- Nombre --}}
                <div class="form-group mb-3">
                    <label for="nombre" class="fw-bold">Nombre de la Asignatura</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre') }}" required>
                </div>

                {{-- Horas Semanales --}}
                <div class="form-group mb-3">
                    <label for="horas_semanales" class="fw-bold">Horas Semanales</label>
                    <input type="number" name="horas_semanales" id="horas_semanales" class="form-control" value="{{ old('horas_semanales') }}" required min="1">
                </div>

                {{-- Descripción --}}
                <div class="form-group mb-3">
                    <label for="descripcion" class="fw-bold">Descripción (opcional)</label>
                    <textarea name="descripcion" id="descripcion" rows="3" class="form-control">{{ old('descripcion') }}</textarea>
                </div>

                {{-- Botones --}}
                <div class="d-flex justify-content-between">
                    <a href="{{ route('asignaturas.index') }}" class="btn btn-secondary">
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
