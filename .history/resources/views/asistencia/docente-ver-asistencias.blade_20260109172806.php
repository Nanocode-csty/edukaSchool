@extends('cplantilla.bprincipal')

@section('titulo', 'Ver Asistencias Registradas')

@section('contenido')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-list mr-2"></i>Asistencias Registradas
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('asistencia.docente.dashboard') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left mr-1"></i>Volver al Dashboard
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <i class="icon fas fa-check"></i> {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <i class="icon fas fa-ban"></i> {{ session('error') }}
                        </div>
                    @endif

                    @if(isset($error))
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <i class="icon fas fa-ban"></i> {{ $error }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover" id="asistencias-table">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Estudiante</th>
                                    <th>Curso</th>
                                    <th>Asignatura</th>
                                    <th>Asistencia</th>
                                    <th>Observaciones</th>
                                    <th>Registrado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($asistencias ?? [] as $asistencia)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($asistencia->fecha)->format('d/m/Y') }}</td>
                                    <td>
                                        {{ $asistencia->matricula->estudiante->nombres ?? 'N/A' }}
                                        {{ $asistencia->matricula->estudiante->apellidos ?? '' }}
                                    </td>
                                    <td>
                                        {{ $asistencia->matricula->grado->nombre ?? 'N/A' }}
                                        {{ $asistencia->matricula->seccion->nombre ?? '' }}
                                    </td>
                                    <td>{{ $asistencia->cursoAsignatura->asignatura->nombre ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge badge-{{ $asistencia->tipoAsistencia->codigo == 'P' ? 'success' : ($asistencia->tipoAsistencia->codigo == 'A' ? 'danger' : ($asistencia->tipoAsistencia->codigo == 'T' ? 'warning' : 'info')) }}">
                                            {{ $asistencia->tipoAsistencia->nombre ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>{{ $asistencia->observaciones ?? '-' }}</td>
                                    <td>
                                        <small class="text-muted">
                                            {{ \Carbon\Carbon::parse($asistencia->hora_registro ?? $asistencia->created_at)->format('d/m/Y H:i') }}
                                        </small>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">
                                        <div class="py-4">
                                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                            <h5 class="text-muted">No hay asistencias registradas</h5>
                                            <p class="text-muted">Las asistencias que registres aparecerán aquí.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if(isset($asistencias) && $asistencias->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $asistencias->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Initialize DataTable if available
    if (typeof $.fn.DataTable !== 'undefined') {
        $('#asistencias-table').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
            },
            "order": [[ 0, "desc" ]], // Order by date descending
            "pageLength": 25,
            "responsive": true
        });
    }
});
</script>
@endsection