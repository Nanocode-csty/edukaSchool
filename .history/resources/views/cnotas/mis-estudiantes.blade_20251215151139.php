@extends('cplantilla.bprincipal')
@section('titulo', 'Mis Estudiantes')
@section('contenidoplantilla')

<div class="container-fluid">
        <div class="row mt-4 mr-1 ml-1">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-primary text-white" style="background-color: #1e5981 !important;">
                        <h4 class="mb-0">
                            <i class="fas fa-users"></i> Mis Estudiantes Representados
                        </h4>
                    </div>
                    <div class="card-body">
                        @if ($estudiantesRepresentados->isEmpty())
                            <div class="alert alert-info text-center">
                                <i class="fas fa-info-circle fa-2x mb-2"></i>
                                <p class="mb-0">No tiene estudiantes asignados como representante.</p>
                            </div>
                        @else
                            <div class="row">
                                @foreach ($estudiantesRepresentados as $item)
                                    <div class="col-md-4 mb-4">
                                        <div class="card h-100 border {{ $item['es_principal'] ? 'border-primary' : '' }}">
                                            <div
                                                class="card-header {{ $item['es_principal'] ? 'bg-primary text-white' : 'bg-light' }}">
                                                <h5 class="mb-0">
                                                    @if ($item['es_principal'])
                                                        <i class="fas fa-star text-warning"></i>
                                                    @endif
                                                    {{ $item['estudiante']->apellidos }}, {{ $item['estudiante']->nombres }}
                                                </h5>
                                                <small>{{ $item['estudiante']->dni }}</small>
                                            </div>
                                            <div class="card-body text-center">
                                                <!-- Foto del estudiante -->
                                                <div class="mb-4 text-center">

                                                    {{-- Foto del estudiante o avatar con iniciales --}}
                                                    @if ($item['estudiante']->foto_url)
    <img src="{{ asset('storage/fotos/' . $item['estudiante']->foto_url) }}"
         alt="Foto de {{ $item['estudiante']->nombres }}"
         class="img-thumbnail rounded-circle mb-3"
         style="width: 130px; height: 130px; object-fit: cover;">
@else
    <div class="avatar-circle mb-3"
         style="width: 130px; height: 130px; margin: 0 auto; background-color: #0A8CB3; color: white; display: flex; align-items: center; justify-content: center; font-size: 48px; border-radius: 50%;">
        {{ substr($item['estudiante']->nombres, 0, 1) }}{{ substr($item['estudiante']->apellidos, 0, 1) }}
    </div>
@endif


                                                    {{-- Botón de ficha escolar --}}
                                                    <br>
                                                    <a href="{{ route('estudiantes.ficha', $item['estudiante']->estudiante_id) }}"
                                                        target="_blank"
                                                        class="btn btn-outline-primary btn-lg px-4 py-2 rounded-pill shadow-sm w-100">
                                                        <i class="fas fa-file-pdf me-2"></i> Generar Ficha
                                                    </a>

                                                </div>


                                                <!-- Datos del estudiante y matrícula -->
                                                @if ($item['matricula'] && $item['curso'])
                                                    <div class="info-estudiante mb-3">
                                                        <p class="mb-1">
                                                            <i class="fas fa-school text-primary"></i>
                                                            <strong>Grado:</strong> {{ $item['curso']->grado->nombre }}
                                                            {{ $item['curso']->grado->nivel->nombre }}
                                                            "{{ $item['curso']->seccion->nombre }}"
                                                        </p>
                                                        <p class="mb-1">
                                                            <i class="fas fa-calendar text-primary"></i>
                                                            <strong>Año:</strong> {{ $item['curso']->anoLectivo->nombre }}
                                                        </p>
                                                        <p class="mb-0">
                                                            <i class="fas fa-id-card text-primary"></i>
                                                            <strong>Matrícula:</strong>
                                                            {{ $item['matricula']->numero_matricula }}
                                                        </p>
                                                    </div>
                                                    <div class="estado-matricula">
                                                        <span
                                                            class="badge {{ $item['matricula']->estado == 'Matriculado' ? 'bg-success' : 'bg-warning' }}">
                                                            {{ $item['matricula']->estado }}
                                                        </span>
                                                    </div>
                                                @else
                                                    <div class="alert alert-warning">
                                                        <i class="fas fa-exclamation-triangle"></i> Sin matrícula activa
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="card-footer text-center">
                                                @if ($item['matricula'] && $item['curso'])
                                                    <a href="{{ route('notas.estudiante', $item['estudiante']->estudiante_id) }}"
                                                        class="btn btn-primary">
                                                        <i class="fas fa-chart-bar"></i> Ver Calificaciones
                                                    </a>
                                                @else
                                                    <button class="btn btn-secondary w-100" disabled>
                                                        <i class="fas fa-ban"></i> No matriculado
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
    </style>
@endsection

@section('scripts')
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mensajes de éxito
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: '{{ session('success') }}',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            @endif

            // Mensajes de error
            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: '¡Error!',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Entendido'
                });
            @endif

            // Mensajes de información
            @if (session('info'))
                Swal.fire({
                    icon: 'info',
                    title: 'Información',
                    text: '{{ session('info') }}',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Aceptar'
                });
            @endif
        });
    </script>
@endsection
