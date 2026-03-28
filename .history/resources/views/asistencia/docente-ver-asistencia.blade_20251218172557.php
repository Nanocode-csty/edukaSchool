@extends('cplantilla.bprincipal')

@section('titulo', 'Ver Asistencia - Docente')

@section('contenidoplantilla')
@php
    $module = 'asistencia';
    $section = 'docente-ver';
@endphp
<x-breadcrumb />

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center justify-content-between">
                    <h4 class="card-title">Asistencia de Clase</h4>
                    <div>
                        <button class="btn btn-secondary btn-sm" onclick="exportarPDF()">
                            <i class="fas fa-file-pdf"></i> Exportar PDF
                        </button>
                        <a href="{{ route('asistencia.docente.index') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Información de la clase -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card border-info">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <strong>Curso:</strong><br>
                                        <span style="color: #007bff;">{{ $sesionClase->cursoAsignatura->curso->grado->nombre }} {{ $sesionClase->cursoAsignatura->curso->seccion->nombre }}</span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Asignatura:</strong><br>
                                        <span style="color: #28a745;">{{ $sesionClase->cursoAsignatura->asignatura->nombre }}</span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Fecha:</strong><br>
                                        <span style="color: #6f42c1;">{{ \Carbon\Carbon::parse($sesionClase->fecha)->format('d/m/Y') }}</span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Horario:</strong><br>
                                        <span style="color: #fd7e14;">{{ $sesionClase->hora_inicio }} - {{ $sesionClase->hora_fin }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estadísticas de asistencia -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="text-center p-3" style="background-color: #f8f9fa; border-radius: 8px; border-left: 4px solid #28a745;">
                            <h5 style="color: #28a745; margin-bottom: 5px;">{{ $estadisticas['presentes'] }}</h5>
                            <small style="color: #6c757d;">Presentes</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3" style="background-color: #f8f9fa; border-radius: 8px; border-left: 4px solid #dc3545;">
                            <h5 style="color: #dc3545; margin-bottom: 5px;">{{ $estadisticas['ausentes'] }}</h5>
                            <small style="color: #6c757d;">Ausentes</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3" style="background-color: #f8f9fa; border-radius: 8px; border-left: 4px solid #ffc107;">
                            <h5 style="color: #ffc107; margin-bottom: 5px;">{{ $estadisticas['tardes'] }}</h5>
                            <small style="color: #6c757d;">Tardes</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3" style="background-color: #f8f9fa; border-radius: 8px; border-left: 4px solid #17a2b8;">
                            <h5 style="color: #17a2b8; margin-bottom: 5px;">{{ $estadisticas['justificados'] }}</h5>
                            <small style="color: #6c757d;">Justificados</small>
                        </div>
                    </div>
                </div>

                <!-- Lista de asistencia -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Estudiante</th>
                                <th>DNI</th>
                                <th>Asistencia</th>
                                <th>Observaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($asistencias as $index => $asistencia)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $asistencia->matricula->estudiante->nombres }} {{ $asistencia->matricula->estudiante->apellidos }}</td>
                                <td>{{ $asistencia->matricula->estudiante->dni }}</td>
                                <td>
                                    <span class="badge badge-{{ getBadgeClass($asistencia->tipo_asistencia->codigo) }}">
                                        {{ $asistencia->tipo_asistencia->nombre }}
                                    </span>
                                </td>
                                <td>{{ $asistencia->observaciones ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($asistencias->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-info-circle text-info" style="font-size: 4rem;"></i>
                        <h4 class="mt-3">No hay registros de asistencia</h4>
                        <p class="text-muted">Aún no se ha tomado asistencia para esta clase.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
function exportarPDF() {
    window.open('{{ route("asistencia.docente.exportar-pdf", $sesionClase->id) }}', '_blank');
}

function getBadgeClass(codigo) {
    switch(codigo) {
        case 'P': return 'success';
        case 'A': return 'danger';
        case 'T': return 'warning';
        case 'J': return 'info';
        default: return 'secondary';
    }
}
</script>
@endsection
                        <a href="{{ route('asistencia.docente.index') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Información de la clase -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card border-info">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <strong>Curso:</strong><br>
                                        <span style="color: #007bff;">{{ $sesionClase->cursoAsignatura->curso->grado->nombre }} {{ $sesionClase->cursoAsignatura->curso->seccion->nombre }}</span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Asignatura:</strong><br>
                                        <span style="color: #28a745;">{{ $sesionClase->cursoAsignatura->asignatura->nombre }}</span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Fecha:</strong><br>
                                        <span style="color: #6f42c1;">{{ \Carbon\Carbon::parse($sesionClase->fecha)->format('d/m/Y') }}</span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Horario:</strong><br>
                                        <span style="color: #fd7e14;">{{ $sesionClase->hora_inicio }} - {{ $sesionClase->hora_fin }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estadísticas de asistencia -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="text-center p-3" style="background-color: #f8f9fa; border-radius: 8px; border-left: 4px solid #28a745;">
                            <h5 style="color: #28a745; margin-bottom: 5px;">{{ $estadisticas['presentes'] }}</h5>
                            <small style="color: #6c757d;">Presentes</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3" style="background-color: #f8f9fa; border-radius: 8px; border-left: 4px solid #dc3545;">
                            <h5 style="color: #dc3545; margin-bottom: 5px;">{{ $estadisticas['ausentes'] }}</h5>
                            <small style="color: #6c757d;">Ausentes</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3" style="background-color: #f8f9fa; border-radius: 8px; border-left: 4px solid #ffc107;">
                            <h5 style="color: #ffc107; margin-bottom: 5px;">{{ $estadisticas['tardes'] }}</h5>
                            <small style="color: #6c757d;">Tardes</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3" style="background-color: #f8f9fa; border-radius: 8px; border-left: 4px solid #17a2b8;">
                            <h5 style="color: #17a2b8; margin-bottom: 5px;">{{ $estadisticas['justificados'] }}</h5>
                            <small style="color: #6c757d;">Justificados</small>
                        </div>
                    </div>
                </div>

                <!-- Lista de asistencia -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Estudiante</th>
                                <th>DNI</th>
                                <th>Asistencia</th>
                                <th>Observaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($asistencias as $index => $asistencia)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $asistencia->matricula->estudiante->nombres }} {{ $asistencia->matricula->estudiante->apellidos }}</td>
                                <td>{{ $asistencia->matricula->estudiante->dni }}</td>
                                <td>
                                    <span class="badge badge-{{ getBadgeClass($asistencia->tipo_asistencia->codigo) }}">
                                        {{ $asistencia->tipo_asistencia->nombre }}
                                    </span>
                                </td>
                                <td>{{ $asistencia->observaciones ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($asistencias->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-info-circle text-info" style="font-size: 4rem;"></i>
                        <h4 class="mt-3">No hay registros de asistencia</h4>
                        <p class="text-muted">Aún no se ha tomado asistencia para esta clase.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
function exportarPDF() {
    window.open('{{ route("asistencia.docente.exportar-pdf", $sesionClase->sesion_id) }}', '_blank');
}

function getBadgeClass(codigo) {
    switch(codigo) {
        case 'P': return 'success';
        case 'A': return 'danger';
        case 'T': return 'warning';
        case 'J': return 'info';
        default: return 'secondary';
    }
}
</script>
@endsection
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Información de la clase -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card border-info">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <strong>Curso:</strong><br>
                                        <span style="color: #007bff;">{{ $sesionClase->cursoAsignatura->curso->grado->nombre }} {{ $sesionClase->cursoAsignatura->curso->seccion->nombre }}</span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Asignatura:</strong><br>
                                        <span style="color: #28a745;">{{ $sesionClase->cursoAsignatura->asignatura->nombre }}</span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Fecha:</strong><br>
                                        <span style="color: #6f42c1;">{{ \Carbon\Carbon::parse($asistencia->fecha)->format('d/m/Y') }}</span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Horario:</strong><br>
                                        <span style="color: #fd7e14;">{{ $sesionClase->hora_inicio }} - {{ $sesionClase->hora_fin }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estadísticas de asistencia -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="text-center p-3" style="background-color: #f8f9fa; border-radius: 8px; border-left: 4px solid #28a745;">
                            <h5 style="color: #28a745; margin-bottom: 5px;">{{ $estadisticas['presentes'] }}</h5>
                            <small style="color: #6c757d;">Presentes</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3" style="background-color: #f8f9fa; border-radius: 8px; border-left: 4px solid #dc3545;">
                            <h5 style="color: #dc3545; margin-bottom: 5px;">{{ $estadisticas['ausentes'] }}</h5>
                            <small style="color: #6c757d;">Ausentes</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3" style="background-color: #f8f9fa; border-radius: 8px; border-left: 4px solid #ffc107;">
                            <h5 style="color: #ffc107; margin-bottom: 5px;">{{ $estadisticas['tardes'] }}</h5>
                            <small style="color: #6c757d;">Tardes</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3" style="background-color: #f8f9fa; border-radius: 8px; border-left: 4px solid #17a2b8;">
                            <h5 style="color: #17a2b8; margin-bottom: 5px;">{{ $estadisticas['justificados'] }}</h5>
                            <small style="color: #6c757d;">Justificados</small>
                        </div>
                    </div>
                </div>

                <!-- Lista de asistencia -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Estudiante</th>
                                <th>DNI</th>
                                <th>Asistencia</th>
                                <th>Observaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($asistencias as $index => $asistencia)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $asistencia->matricula->estudiante->nombres }} {{ $asistencia->matricula->estudiante->apellidos }}</td>
                                <td>{{ $asistencia->matricula->estudiante->dni }}</td>
                                <td>
                                    <span class="badge badge-{{ getBadgeClass($asistencia->tipo_asistencia->codigo) }}">
                                        {{ $asistencia->tipo_asistencia->nombre }}
                                    </span>
                                </td>
                                <td>{{ $asistencia->observaciones ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($asistencias->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-info-circle text-info" style="font-size: 4rem;"></i>
                        <h4 class="mt-3">No hay registros de asistencia</h4>
                        <p class="text-muted">Aún no se ha tomado asistencia para esta clase.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
function exportarPDF() {
    window.open('{{ route("asistencia.docente.exportar-pdf", $sesionClase->id) }}', '_blank');
}

function getBadgeClass(codigo) {
    switch(codigo) {
        case 'P': return 'success';
        case 'A': return 'danger';
        case 'T': return 'warning';
        case 'J': return 'info';
        default: return 'secondary';
    }
}
</script>
@endsection
