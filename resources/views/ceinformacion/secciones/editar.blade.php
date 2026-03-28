@extends('cplantilla.bprincipal')

@section('titulo', 'Editar Sección')

@section('contenidoplantilla')

<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-warning text-dark">
            <h4 class="mb-0">
                <i class="fas fa-edit"></i> Editar Sección
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
            <form action="{{ route('secciones.update', $seccion->seccion_id) }}" method="POST">
                @csrf
                @method('PUT')

            {{-- Nombre (solo lectura) --}}
                <div class="form-group mb-3">
                    <label for="nombre" class="fw-bold">Nombre de la Sección</label>
                    <input type="text" name="nombre" id="nombre" class="form-control"
                        value="{{ old('nombre', $seccion->nombre) }}" readonly>
                </div>

                {{-- Capacidad Máxima (editable) --}}
                <div class="form-group mb-3">
                    <label for="capacidad_maxima" class="fw-bold">Capacidad Máxima</label>
                    <input type="number" name="capacidad_maxima" id="capacidad_maxima" class="form-control"
                        value="{{ old('capacidad_maxima', $seccion->capacidad_maxima) }}" required>
                </div>

                {{-- Descripción (solo lectura) --}}
                <div class="form-group mb-3">
                    <label for="descripcion" class="fw-bold">Descripción</label>
                    <textarea name="descripcion" id="descripcion" class="form-control" rows="3" readonly>{{ old('descripcion', $seccion->descripcion) }}</textarea>
                </div>

                {{-- Estado (editable) --}}
                <div class="form-group mb-3">
                    <label for="estado" class="fw-bold">Estado</label>
                    <select name="estado" id="estado" class="form-control" required>
                        <option value="Activo" {{ old('estado', $seccion->estado) == 'Activo' ? 'selected' : '' }}>Activo</option>
                        <option value="Inactivo" {{ old('estado', $seccion->estado) == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>


                {{-- Botones --}}
                <div class="d-flex justify-content-between">
                    <a href="{{ route('secciones.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-warning text-white">
                        <i class="fas fa-save"></i> Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    function aplicarColorEstado() {
        const estado = document.getElementById('estado');
        const valor = estado.value;

        // Resetear clases
        estado.classList.remove('bg-success', 'bg-danger');

        // Aplicar clase según estado
        if (valor === 'Activo') {
            estado.classList.add('bg-success');
        } else if (valor === 'Inactivo') {
            estado.classList.add('bg-danger');
        }
    }

    // Ejecutar al cargar la página
    document.addEventListener('DOMContentLoaded', aplicarColorEstado);

    // Ejecutar al cambiar la opción
    document.getElementById('estado').addEventListener('change', aplicarColorEstado);
</script>

@endsection
