@extends('cplantilla.bprincipal')

@section('titulo', 'Editar Periodo de Evaluación')

@section('contenidoplantilla')

<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-warning text-dark">
            <h4 class="mb-0">
                <i class="fas fa-edit"></i> Editar Periodo de Evaluación
            </h4>
        </div>

        <div class="card-body">
            {{-- Alertas --}}
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

            {{-- Verificar si el periodo está finalizado --}}
            @php
                $finalizado = $periodo->estado === 'Finalizado';
            @endphp

            @if ($finalizado)
                <div class="alert alert-info">
                    Este periodo está <strong>Finalizado</strong> y no puede ser editado.
                </div>
            @endif

            {{-- Formulario --}}
            <form action="{{ route('periodos-evaluacion.update', $periodo->periodo_id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Año Lectivo --}}
                <div class="form-group mb-3">
                    <label for="ano_lectivo_id" class="fw-bold">Año Lectivo</label>
                    <input type="number" name="ano_lectivo_id" id="ano_lectivo_id" class="form-control"
                        value="{{ old('ano_lectivo_id', $periodo->ano_lectivo_id) }}"
                        {{ $finalizado ? 'disabled' : 'required' }}>
                </div>

                {{-- Nombre --}}
                <div class="form-group mb-3">
                    <label for="nombre" class="fw-bold">Nombre del Periodo</label>
                    <input type="text" name="nombre" id="nombre" class="form-control"
                        value="{{ old('nombre', $periodo->nombre) }}"
                        {{ $finalizado ? 'disabled' : 'required' }}>
                </div>

                {{-- Fecha Inicio --}}
                <div class="form-group mb-3">
                    <label for="fecha_inicio" class="fw-bold">Fecha de Inicio</label>
                    <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control"
                        value="{{ old('fecha_inicio', $periodo->fecha_inicio) }}"
                        {{ $finalizado ? 'disabled' : 'required' }}>
                </div>

                {{-- Fecha Fin --}}
                <div class="form-group mb-3">
                    <label for="fecha_fin" class="fw-bold">Fecha de Fin</label>
                    <input type="date" name="fecha_fin" id="fecha_fin" class="form-control"
                        value="{{ old('fecha_fin', $periodo->fecha_fin) }}"
                        {{ $finalizado ? 'disabled' : 'required' }}>
                </div>

                {{-- Estado --}}
                <div class="form-group mb-3">
                    <label for="estado" class="fw-bold">Estado</label>
                    <select name="estado" id="estado" class="form-control" {{ $finalizado ? 'disabled' : 'required' }}>
                        <option value="">-- Seleccione un estado --</option>
                        <option value="Planificado" {{ old('estado', $periodo->estado) == 'Planificado' ? 'selected' : '' }}>Planificado</option>
                        <option value="En curso" {{ old('estado', $periodo->estado) == 'En curso' ? 'selected' : '' }}>En curso</option>
                        <option value="Finalizado" {{ old('estado', $periodo->estado) == 'Finalizado' ? 'selected' : '' }}>Finalizado</option>
                        <option value="Cerrado" {{ old('estado', $periodo->estado) == 'Cerrado' ? 'selected' : '' }}>Cerrado</option>
                    </select>
                </div>

                {{-- ¿Es Final? --}}
                <div class="form-group mb-3">
                    <label for="es_final" class="fw-bold">¿Es Evaluación Final?</label>
                    <select name="es_final" id="es_final" class="form-control" {{ $finalizado ? 'disabled' : 'required' }}>
                        <option value="">-- Seleccione --</option>
                        <option value="1" {{ old('es_final', $periodo->es_final) == '1' ? 'selected' : '' }}>Sí</option>
                        <option value="0" {{ old('es_final', $periodo->es_final) == '0' ? 'selected' : '' }}>No</option>
                    </select>
                </div>

                {{-- Botones --}}
                <div class="d-flex justify-content-between">
                    <a href="{{ route('periodos-evaluacion.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                    @unless($finalizado)
                        <button type="submit" class="btn btn-warning text-white">
                            <i class="fas fa-save"></i> Actualizar
                        </button>
                    @endunless
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

