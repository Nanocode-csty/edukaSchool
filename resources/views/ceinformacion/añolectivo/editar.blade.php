@extends('cplantilla.bprincipal')

@section('titulo', 'Editar Año Lectivo')

@section('contenidoplantilla')

@php
    use Carbon\Carbon;
    $esFinalizado = $anolectivo->estado === 'Finalizado';
@endphp

<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-warning text-dark">
            <h4 class="mb-0">
                <i class="fas fa-edit"></i> Editar Año Lectivo
            </h4>
        </div>

        <div class="card-body">
            {{-- Alertas --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if ($esFinalizado)
                <div class="alert alert-info">
                    Este año lectivo está <strong>Finalizado</strong> y no puede ser editado.
                </div>
            @endif

            {{-- Formulario --}}
            <form action="{{ route('aniolectivo.update', $anolectivo->ano_lectivo_id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group mb-3">
                    <label for="nombre" class="fw-bold">Nombre</label>
                    <input type="text" name="nombre" id="nombre" class="form-control"
                        value="{{ old('nombre', $anolectivo->nombre) }}" required @disabled($esFinalizado)>
                </div>

                <div class="form-group mb-3">
                    <label for="fecha_inicio" class="fw-bold">Fecha de Inicio</label>
                    <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control"
                        value="{{ old('fecha_inicio', $anolectivo->fecha_inicio) }}" required @disabled($esFinalizado)>
                </div>

                <div class="form-group mb-3">
                    <label for="fecha_fin" class="fw-bold">Fecha de Fin</label>
                    <input type="date" name="fecha_fin" id="fecha_fin" class="form-control"
                        value="{{ old('fecha_fin', $anolectivo->fecha_fin) }}" required @disabled($esFinalizado)>
                </div>

                <div class="form-group mb-3">
                    <label for="estado" class="fw-bold">Estado</label>
                    <select name="estado" id="estado" class="form-control" required @disabled($esFinalizado)>
                        <option value="">-- Seleccione un estado --</option>
                        <option value="Activo" {{ old('estado', $anolectivo->estado) == 'Activo' ? 'selected' : '' }}>Activo</option>
                        <option value="Planificación" {{ old('estado') == 'Planificación' ? 'selected' : '' }}>Planificación</option>
                        <option value="Finalizado" {{ old('estado', $anolectivo->estado) == 'Finalizado' ? 'selected' : '' }}>Finalizado</option>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label for="descripcion" class="fw-bold">Descripción (opcional)</label>
                    <textarea name="descripcion" id="descripcion" rows="3" class="form-control" @disabled($esFinalizado)>{{ old('descripcion', $anolectivo->descripcion) }}</textarea>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('aniolectivo.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>

                    @if (!$esFinalizado)
                        <button type="submit" class="btn btn-warning text-white">
                            <i class="fas fa-save"></i> Actualizar
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
