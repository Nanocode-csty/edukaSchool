@extends('cplantilla.bprincipal')
@section('titulo','Detalles de Asistencia - Docente')
@section('contenidoplantilla')
<x-breadcrumb :module="'asistencia'" :section="'docente-ver-asistencia'" />
<div class="container-fluid" id="contenido-principal">
    <div class="row mt-4 ml-1 mr-1">
        <div class="col-12">
            <div class="box_block">
                <!-- Collapse header -->
                <button class="btn btn-block text-left rounded-0 btn_header header_6" type="button" data-toggle="collapse" data-target="#collapseAsistenciaSesion" aria-expanded="true" aria-controls="collapseAsistenciaSesion" style="background: #28a745 !important; font-weight: bold; color: white;">
                    <i class="fas fa-eye m-1"></i>&nbsp;Detalles de Asistencia - {{ $sesion->cursoAsignatura->asignatura->nombre }}
                    <div class="float-right"><i class="fas fa-chevron-down"></i></div>
                </button>
                <!-- Descripción (siempre visible, dentro del bloque) -->
                <div class="card-body rounded-0 border-0 pt-3 pb-3" style="background: #f3f3f3; border-bottom: 1px solid rgba(0,0,0,.125); border-top: 1px solid rgba(0,0,0,.125); color: #F59D24;">
                    <div class="row justify-content-center align-items-center flex-wrap">
                        <div class="col-auto text-center mb-2 mb-md-0" style="min-width:48px;">
                            <i class="fas fa-calendar-check fa-2x"></i>
                        </div>
                        <div class="col px-2" style="text-align:justify;">
                            <p style="margin-bottom: 0px; font-family: 'Quicksand', sans-serif; font-weight: 600; color: #004a92;">
                                Consulta los detalles de asistencia registrados para esta sesión específica. Revisa el estado de cada estudiante y las observaciones realizadas.
                            </p>
                        </div>
                    </div>
                </div>
                <!-- Collapse: contenido de la sesión -->
                <div class="collapse show" id="collapseAsistenciaSesion">
                    <div class="card card-body rounded-0 border-0 pt-0 pb-2" style="background: transparent;">

                        @if(isset($error))
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle"></i> {{ $error }}
                            </div>
                        @else
                            <!-- Información de la sesión -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header bg-primary text-white">
                                            <h5 class="mb-0">
                                                <i class="fas fa-info-circle"></i> Información de la Sesión
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="text-center">
                                                        <h6 class="text-muted mb-2">Fecha</h6>
                                                        <strong>{{ $sesion->fecha->locale('es')->dayName }}, {{ $sesion->fecha->format('d/m/Y') }}</strong>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="text-center">
                                                        <h6 class="text-muted mb-2">Horario</h6>
                                                        <strong>{{ substr($sesion->hora_inicio, 0, 5) }} - {{ substr($sesion->hora_fin, 0, 5) }}</strong>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="text-center">
                                                        <h6 class="text-muted mb-2">Asignatura</h6>
                                                        <strong>{{ $sesion->cursoAsignatura->asignatura->nombre }}</strong>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="text-center">
                                                        <h6 class="text-muted mb-2">Curso</h6>
                                                        <strong>{{ $sesion->cursoAsignatura->curso->grado->nombre }} {{ $sesion->cursoAsignatura->curso->seccion->nombre }}</strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Estadísticas de la sesión -->
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <div class="card border-info">
                                        <div class="card-body text-center">
                                            <h6 class="text-info mb-2">
                                                <i class="fas fa-users"></i> Total Estudiantes
                                            </h6>
                                            <h3 class="text-primary">{{ $asistencias->count() }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card border-success">
                                        <div class="card-body text-center">
                                            <h6 class="text-success mb-2">
                                                <i class="fas fa-check-circle"></i> Presentes
                                            </h6>
                                            <h3 class="text-success">{{ $asistencias->where('tipoAsistencia.codigo', 'P')->count() }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card border-danger">
                                        <div class="card-body text-center">
                                            <h6 class="text-danger mb-2">
                                                <i class="fas fa-times-circle"></i> Ausentes
                                            </h6>
                                            <h3 class="text-danger">{{ $asistencias->where('tipoAsistencia.codigo', 'A')->count() }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tabla de Asistencias de la Sesión -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="mb-0"><i class="fas fa-table"></i> Registro de Asistencia</h5>
                                        </div>
                                        <div class="card-body">
                                            @if($asistencias->count() > 0)
                                                <div class="table-responsive">
                                                    <table class="table table-hover" style="border: 1px solid #28a745; border-radius: 10px; overflow: hidden;">
                                                        <thead class="text-center" style="background-color: #f8f9fa; color: #28a745;">
                                                            <tr>
                                                                <th scope="col">#</th>
                                                                <th scope="col">Estudiante</th>
                                                                <th scope="col">DNI</th>
                                                                <th scope="col">Tipo de Asistencia</th>
                                                                <th scope="col">Observaciones</th>
                                                                <th scope="col">Hora Registro</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($asistencias as $index => $asistencia)
                                                            <tr>
                                                                <td class="text-center font-weight-bold">{{ $index + 1 }}</td>
                                                                <td>
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="avatar-circle-small mr-3" style="width: 35px; height: 35px; border-radius: 50%; background: #007bff; color: white; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: bold;">
                                                                            {{ substr($asistencia->matricula->estudiante->nombres, 0, 1) }}{{ substr($asistencia->matricula->estudiante->apellidos, 0, 1) }}
                                                                        </div>
                                                                        <div>
                                                                            <div class="font-weight-bold">{{ $asistencia->matricula->estudiante->nombres }}</div>
                                                                            <small class="text-muted">{{ $asistencia->matricula->estudiante->apellidos }}</small>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>{{ $asistencia->matricula->estudiante->dni }}</td>
                                                                <td class="text-center">
                                                                    <span class="badge badge-{{ $asistencia->tipoAsistencia->codigo == 'P' ? 'success' : ($asistencia->tipoAsistencia->codigo == 'A' ? 'danger' : ($asistencia->tipoAsistencia->codigo == 'T' ? 'warning' : 'info')) }} badge-lg">
                                                                        <i class="fas {{ $asistencia->tipoAsistencia->codigo == 'P' ? 'fa-check' : ($asistencia->tipoAsistencia->codigo == 'A' ? 'fa-times' : ($asistencia->tipoAsistencia->codigo == 'T' ? 'fa-clock' : 'fa-file-medical')) }} mr-1"></i>
                                                                        {{ $asistencia->tipoAsistencia->nombre }}
                                                                    </span>
                                                                </td>
                                                                <td>{{ $asistencia->observaciones ?? '-' }}</td>
                                                                <td class="text-center">
                                                                    <small class="text-muted">
                                                                        {{ \Carbon\Carbon::parse($asistencia->hora_registro ?? $asistencia->created_at)->format('d/m/Y H:i') }}
                                                                    </small>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <!-- Información de resultados -->
                                                <div class="mt-3 text-center text-muted">
                                                    <small>
                                                        Mostrando {{ $asistencias->count() }} estudiantes registrados en esta sesión
                                                    </small>
                                                </div>
                                            @else
                                                <div class="text-center py-5">
                                                    <i class="fas fa-calendar-times text-muted fa-4x mb-3"></i>
                                                    <h5 class="text-muted">No hay registros de asistencia</h5>
                                                    <p class="text-muted">
                                                        No se encontraron registros de asistencia para esta sesión.
                                                    </p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Acciones Rápidas -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-end gap-2 flex-wrap">
                                        <button type="button" class="btn btn-warning btn-sm" onclick="editarAsistenciaSesion({{ $sesion->sesion_id }})">
                                            <i class="fas fa-edit mr-1"></i>Editar Asistencia
                                        </button>
                                        <a href="{{ route('asistencia.docente.ver-asistencias') }}" class="btn btn-sm" style="background-color: #28a745 !important; color: white !important; border: none !important;">
                                            <i class="fas fa-list mr-1"></i>Ver Todas las Asistencias
                                        </a>
                                        <a href="{{ route('asistencia.docente.dashboard') }}" class="btn btn-sm" style="background-color: #6c757d !important; color: white !important; border: none !important;">
                                            <i class="fas fa-arrow-left mr-1"></i>Volver al Dashboard
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js-extra')
<script>
// Funciones para acciones de sesiones
function editarAsistenciaSesion(sesionId) {
    console.log('Editar asistencia sesión:', sesionId);
    // Show confirmation dialog using Swal
    Swal.fire({
        title: '¿Editar asistencia?',
        text: '¿Estás seguro de que deseas editar la asistencia? Esto puede afectar reportes y estadísticas.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ffc107',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, editar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Redirect to edit attendance page
            const url = `{{ route('asistencia.docente.editar', ':id') }}`.replace(':id', sesionId);
            console.log('URL de redirección editar:', url);
            window.location.href = url;
        }
    });
}
</script>
@endpush

@push('js-extra')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Collapse icon toggle
    const btn = document.querySelector('[data-target="#collapseAsistencias"]');
    const icon = btn.querySelector('.fas.fa-chevron-down');
    const collapse = document.getElementById('collapseAsistencias');
    collapse.addEventListener('show.bs.collapse', function () {
        icon.classList.remove('fa-chevron-down');
        icon.classList.add('fa-chevron-up');
    });
    collapse.addEventListener('hide.bs.collapse', function () {
        icon.classList.remove('fa-chevron-up');
        icon.classList.add('fa-chevron-down');
    });
});

function exportarAsistencias(formato) {
    // Build query string from current filters
    const params = new URLSearchParams(window.location.search);
    params.set('formato', formato);

    // Create download URL
    const url = `/asistencia/exportar-asistencias?${params.toString()}`;

    // Open in new tab for download
    window.open(url, '_blank');
}
</script>
@endpush
