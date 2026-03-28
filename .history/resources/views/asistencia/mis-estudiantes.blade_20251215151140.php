@extends('cplantilla.bprincipal')
@section('titulo', 'Mis Estudiantes - Asistencia')
@section('contenidoplantilla')

<div class="container-fluid">
        <div class="row mt-4 mr-1 ml-1">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center" style="background-color: #3a6f92 !important;">
                        <h4 class="mb-0">
                            <i class="fas fa-calendar-check"></i> Mis Estudiantes Representados - Asistencia
                        </h4>
                        <div>
                            <a href="{{ route('asistencia.mis-justificaciones') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-file-alt"></i> Ver Justificaciones
                            </a>
                            <a href="{{ route('asistencia.justificar') }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-plus"></i> Nueva Justificación
                            </a>
                        </div>
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
         style="width: 130px; height: 130px; margin: 0 auto; background-color: #3a6f92; color: white; display: flex; align-items: center; justify-content: center; font-size: 48px; border-radius: 50%;">
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


                                                <!-- Datos del estudiante y matrículas -->
                                                @if ($item['matriculas']->isNotEmpty())
                                                    <div class="info-estudiante mb-3">
                                                        <p class="mb-2">
                                                            <i class="fas fa-graduation-cap text-primary"></i>
                                                            <strong>Cursos matriculados:</strong>
                                                        </p>
                                                        @foreach ($item['matriculas'] as $matricula)
                                                            <div class="curso-item mb-2 p-2 border rounded">
                                                                <small class="text-muted d-block">
                                                                    <i class="fas fa-school"></i>
                                                                    {{ $matricula->grado->nivel->nombre }} {{ $matricula->grado->nombre }} - {{ $matricula->seccion->nombre }}
                                                                </small>
                                                                @php
                                                                    $asignaturas = \App\Models\CursoAsignatura::where('curso_id', $matricula->curso_id)
                                                                        ->with('asignatura')
                                                                        ->get()
                                                                        ->pluck('asignatura.nombre')
                                                                        ->unique()
                                                                        ->values();
                                                                @endphp
                                                                @if($asignaturas->count() > 0)
                                                                    <small class="text-muted d-block">
                                                                        <i class="fas fa-book"></i>
                                                                        <strong>Asignaturas:</strong> {{ $asignaturas->implode(', ') }}
                                                                    </small>
                                                                @endif
                                                                <small class="text-muted d-block">
                                                                    <i class="fas fa-calendar"></i>
                                                                    Año: {{ $matricula->curso->anoLectivo->nombre ?? 'N/A' }}
                                                                </small>
                                                                <small class="text-muted d-block">
                                                                    <i class="fas fa-id-card"></i>
                                                                    Matrícula: {{ $matricula->numero_matricula }}
                                                                </small>
                                                                <span class="badge {{ $matricula->estado == 'Matriculado' ? 'bg-success' : 'bg-warning' }} badge-sm">
                                                                    {{ $matricula->estado }}
                                                                </span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <div class="alert alert-warning">
                                                        <i class="fas fa-exclamation-triangle"></i> Sin matrícula activa
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="card-footer text-center">
                                                @if ($item['matriculas']->isNotEmpty())
                                                    <div class="mb-2">
                                                        <small class="text-muted"><strong>Reportes de Asistencia:</strong></small>
                                                    </div>
                                                    <div class="d-flex gap-2 justify-content-center">
                                                        <a href="{{ route('asistencia.detalle-estudiante', $item['matricula_principal']->matricula_id) }}"
                                                            class="btn btn-primary btn-sm"
                                                            title="Ver asistencia detallada">
                                                            <i class="fas fa-eye"></i> Ver Reporte
                                                        </a>
                                                        <a href="{{ route('asistencia.exportar-pdf', $item['matricula_principal']->matricula_id) }}"
                                                            class="btn btn-outline-danger btn-sm"
                                                            target="_blank"
                                                            title="Descargar PDF de asistencia">
                                                            <i class="fas fa-file-pdf"></i> Exportar PDF
                                                        </a>
                                                    </div>
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

        .btn-xs {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            line-height: 1.2;
            border-radius: 0.2rem;
        }

        .badge-sm {
            font-size: 0.7rem;
            padding: 0.2rem 0.4rem;
        }

        .curso-item {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6 !important;
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
