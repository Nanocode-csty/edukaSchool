@extends('cplantilla.bprincipal')

@section('titulo', 'Nuevo Periodo de Evaluación')

@section('contenidoplantilla')

<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">
                <i class="fas fa-plus-circle"></i> Registrar Nuevo Periodo de Evaluación
            </h4>
        </div>

        <div class="card-body">

            {{-- Alertas de sesión --}}
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

            {{-- Alertas de validación --}}
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
            <form action="{{ route('periodos-evaluacion.store') }}" method="POST">
                @csrf

                {{-- Año Lectivo --}}
                <div class="form-group mb-3">
                    <label for="ano_lectivo_id" class="fw-bold">Año Lectivo</label>
                    <select name="ano_lectivo_id" id="ano_lectivo_id" class="form-control" required>
                        <option value="">-- Seleccione un año lectivo --</option>
                        @foreach ($anios as $anio)
                            <option value="{{ $anio->ano_lectivo_id }}" {{ old('ano_lectivo_id') == $anio->ano_lectivo_id ? 'selected' : '' }}>

                                {{ $anio->nombre }} ({{ \Carbon\Carbon::parse($anio->fecha_inicio)->format('Y') }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Nombre del Periodo --}}
                <div class="form-group mb-3">
                    <label for="nombre" class="fw-bold">Nombre del Periodo</label>
                    <input type="text" name="nombre" id="nombre"
                        class="form-control" value="{{ old('nombre') }}" required>
                </div>

                {{-- Fecha de Inicio --}}
                <div class="form-group mb-3">
                    <label for="fecha_inicio" class="fw-bold">Fecha de Inicio</label>
                    <input type="date" name="fecha_inicio" id="fecha_inicio"
                        class="form-control" value="{{ old('fecha_inicio') }}" required>
                </div>

                {{-- Fecha de Fin --}}
                <div class="form-group mb-3">
                    <label for="fecha_fin" class="fw-bold">Fecha de Fin</label>
                    <input type="date" name="fecha_fin" id="fecha_fin"
                        class="form-control" value="{{ old('fecha_fin') }}" required>
                </div>

                {{-- Estado --}}
                <div class="form-group mb-3">
                    <label for="estado" class="fw-bold">Estado</label>
                    <select name="estado" id="estado" class="form-control" required>
                        <option value="">-- Seleccione un estado --</option>
                        <option value="Planificado" {{ old('estado') == 'Planificado' ? 'selected' : '' }}>Planificado</option>
                        <option value="En curso" {{ old('estado') == 'En curso' ? 'selected' : '' }}>En curso</option>
                        <option value="Finalizado" {{ old('estado') == 'Finalizado' ? 'selected' : '' }}>Finalizado</option>
                        <option value="Cerrado" {{ old('estado') == 'Cerrado' ? 'selected' : '' }}>Cerrado</option>
                    </select>
                </div>

                {{-- ¿Es Final? --}}
                <div class="form-group mb-3">
                    <label for="es_final" class="fw-bold">¿Es Evaluación Final?</label>
                    <select name="es_final" id="es_final" class="form-control" required>
                        <option value="">-- Seleccione --</option>
                        <option value="1" {{ old('es_final') == '1' ? 'selected' : '' }}>Sí</option>
                        <option value="0" {{ old('es_final') == '0' ? 'selected' : '' }}>No</option>
                    </select>
                </div>

                {{-- Botones --}}
                <div class="d-flex justify-content-between">
                    <a href="{{ route('periodos-evaluacion.index') }}" class="btn btn-secondary">
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
