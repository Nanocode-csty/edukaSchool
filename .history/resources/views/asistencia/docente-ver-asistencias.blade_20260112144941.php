@extends('cplantilla.bprincipal')
@section('titulo','Ver Asistencia de Sesión - Docente')
@section('contenidoplantilla')
<x-breadcrumb :module="'asistencia'" :section="'docente-ver-asistencias'" />

<div class="container-fluid" id="contenido-principal">
    <div class="row mt-4 ml-1 mr-1">
        <div class="col-12">
            <div class="box_block">
                <!-- Collapse header -->
                <button class="btn btn-block text-left rounded-0 btn_header header_6" type="button" data-toggle="collapse" data-target="#collapseVerAsistencia" aria-expanded="true" aria-controls="collapseVerAsistencia" style="background: #28a745 !important; font-weight: bold; color: white;">
                    <i class="fas fa-eye m-1"></i>&nbsp;Asistencia Registrada - {{ $sesion->cursoAsignatura->asignatura->nombre ?? 'N/A' }}
                    <div class="float-right"><i class="fas fa-chevron-down"></i></div>
                </button>

                <!-- Información de la sesión -->
                <div class="card-body rounded-0 border-0 pt-3 pb-3" style="background: #f3f3f3; border-bottom: 1px solid rgba(0,0,0,.125); border-top: 1px solid rgba(0,0,0,.125); color: #F59D24;">
                    <div class="row justify-content-center align-items-center flex-wrap">
                        <div class="col-auto text-center mb-2 mb-md-0" style="min-width:48px;">
                            <i class="fas fa-calendar-alt fa-2x"></i>
                        </div>
                        <div class="col px-2" style="text-align:justify;">
                            <p style="margin-bottom: 5px; font-family: 'Quicksand', sans-serif; font-weight: 600; color: #004a92;">
                                <strong>Sesión:</strong> {{ \Carbon\Carbon::parse($sesion->fecha)->format('d/m/Y') }} - {{ substr($sesion->hora_inicio, 0, 5) }} a {{ substr($sesion->hora_fin, 0, 5) }}
                            </p>
                            <p style="margin-bottom: 5px; font-family: 'Quicksand', sans-serif; font-weight: 600; color: #004a92;">
                                <strong>Curso:</strong> {{ $sesion->cursoAsignatura->curso->grado->nombre ?? 'N/A' }} {{ $sesion->cursoAsignatura->curso->seccion->nombre ?? '' }}
                            </p>
                            <p style="margin-bottom: 0px; font-family: 'Quicksand', sans-serif; font-weight: 600; color: #004a92;">
                                <strong>Asignatura:</strong> {{ $sesion->cursoAsignatura->asignatura->nombre ?? 'N/A' }} |
                                <strong>Aula:</strong> {{ $sesion->aula->nombre ?? 'N/A' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Collapse: contenido de la asistencia -->
                <div class="collapse show" id="collapseVerAsistencia">
                    <div class="card card-body rounded-0 border-0 pt-0 pb-2" style="background: transparent;">

                        @if(isset($error))
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle"></i> {{ $error }}
                            </div>
                        @else
                            <!-- Tabla de asistencia de la sesión -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0"><i class="fas fa-users"></i> Asistencia Registrada</h5>
                                            <div>
                                                <button class="btn btn-success btn-sm mr-2" onclick="exportarPDFSesion({{ $sesion->sesion_id ?? 'null' }})">
                                                    <i class="fas fa-file-pdf"></i> Exportar PDF
                                                </button>
                                                <button class="btn btn-primary btn-sm" onclick="editarAsistenciaSesion({{ $sesion->sesion_id ?? 'null' }})">
                                                    <i class="fas fa-edit"></i> Editar Asistencia
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            @php
                                                $totalEstudiantes = $sesion->cursoAsignatura->curso->matriculas()->where('estado', 'Matriculado')->count();
                                                $asistenciasRegistradas = $asistencias->count();
                                                $presentes = $asistencias->where('tipoAsistencia.computa_falta', 0)->count();
                                                $ausentes = $asistencias->where('tipoAsistencia.computa_falta', 1)->count();
                                            @endphp

                                            <!-- Estadísticas -->
                                            <div class="row mb-4">
                                                <div class="col-md-3">
                                                    <div class="card bg-light">
                                                        <div class="card-body text-center">
                                                            <h4 class="text-primary">{{ $totalEstudiantes }}</h4>
                                                            <small class="text-muted">Total Estudiantes</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="card bg-success text-white">
                                                        <div class="card-body text-center">
                                                            <h4>{{ $presentes }}</h4>
                                                            <small>Presentes</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="card bg-danger text-white">
                                                        <div class="card-body text-center">
                                                            <h4>{{ $ausentes }}</h4>
                                                            <small>Ausentes</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="card bg-warning">
                                                        <div class="card-body text-center">
                                                            <h4>{{ $totalEstudiantes - $asistenciasRegistradas }}</h4>
                                                            <small>Sin Registrar</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            @if($asistencias->count() > 0)
                                                <div class="table-responsive">
                                                    <table class="table table-hover" style="border: 1px solid #28a745; border-radius: 10px; overflow: hidden;">
                                                        <thead class="text-center" style="background-color: #f8f9fa; color: #28a745;">
                                                            <tr>
                                                                <th scope="col">#</th>
                                                                <th scope="col">Estudiante</th>
                                                                <th scope="col">Tipo Asistencia</th>
                                                                <th scope="col">Estado</th>
                                                                <th scope="col">Hora Registro</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($sesion->cursoAsignatura->curso->matriculas()->with('estudiante.persona')->where('estado', 'Matriculado')->get() as $matricula)
                                                            <tr>
                                                                <td class="text-center">{{ $matricula->numero_matricula }}</td>
                                                                <td>{{ $matricula->estudiante->persona->nombres }} {{ $matricula->estudiante->persona->apellidos }}</td>
                                                                <td class="text-center">
                                                                    @php
                                                                        $asistenciaEstudiante = $asistencias->get($matricula->matricula_id);
                                                                    @endphp
                                                                    @if($asistenciaEstudiante)
                                                                        <span class="badge badge-{{ $asistenciaEstudiante->tipoAsistencia->computa_falta == 0 ? 'success' : 'danger' }}">
                                                                            {{ $asistenciaEstudiante->tipoAsistencia->nombre }}
                                                                        </span>
                                                                    @else
                                                                        <span class="badge badge-warning">No Registrada</span>
                                                                    @endif
                                                                </td>
                                                                <td class="text-center">
                                                                    @if($asistenciaEstudiante)
                                                                        @if($asistenciaEstudiante->tipoAsistencia->computa_falta == 0)
                                                                            <i class="fas fa-check-circle text-success"></i> Presente
                                                                        @else
                                                                            <i class="fas fa-times-circle text-danger"></i> Ausente
                                                                        @endif
                                                                    @else
                                                                        <i class="fas fa-question-circle text-warning"></i> Pendiente
                                                                    @endif
                                                                </td>
                                                                <td class="text-center">
                                                                    {{ $asistenciaEstudiante ? \Carbon\Carbon::parse($asistenciaEstudiante->hora_registro)->format('H:i:s') : '-' }}
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <!-- Información adicional -->
                                                <div class="mt-3 text-center text-muted">
                                                    <small>
                                                        Sesión completada el {{ \Carbon\Carbon::parse($sesion->updated_at)->format('d/m/Y H:i:s') }}
                                                    </small>
                                                </div>
                                            @else
                                                <div class="text-center py-5">
                                                    <i class="fas fa-exclamation-triangle text-warning fa-4x mb-3"></i>
                                                    <h5 class="text-warning">No hay asistencia registrada</h5>
                                                    <p class="text-muted">
                                                        Esta sesión no tiene registros de asistencia. Puede editarla para agregar asistencia.
                                                    </p>
                                                    <button class="btn btn-primary" onclick="editarAsistenciaSesion({{ $sesion->id }})">
                                                        <i class="fas fa-edit"></i> Editar Asistencia
                                                    </button>
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
                                        <a href="{{ route('asistencia.docente.ver-asistencias') }}" class="btn btn-sm" style="background-color: #6c757d !important; color: white !important; border: none !important;">
                                            <i class="fas fa-arrow-left mr-1"></i>Volver a Lista
                                        </a>
                                        <a href="{{ route('asistencia.docente.dashboard') }}" class="btn btn-sm" style="background-color: #007bff !important; color: white !important; border: none !important;">
                                            <i class="fas fa-home mr-1"></i>Dashboard
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
// Funciones para acciones de la sesión
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
            window.location.href = `/asistencia/docente/editar/${sesionId}`;
        }
    });
}

function exportarPDFSesion(sesionId) {
    console.log('Exportar PDF sesión:', sesionId);
    // Create download URL
    window.open(`/asistencia/docente/exportar-pdf/${sesionId}`, '_blank');
}
</script>
@endpush
