@extends('cplantilla.bprincipal')

@section('titulo', 'Detalle de Asistencia - Estudiante')
{{-- Vista detallada de asistencia de un estudiante específico --}}
{{-- Muestra historial de asistencias, estadísticas y justificaciones --}}

@section('contenidoplantilla')
<x-breadcrumb :module="'asistencia'" :section="'representante-detalle'" />
    <style>
        @media (max-width: 576px) {
            .margen-movil {
                margin-left: -29px !important;
                margin-right: -29px !important;
            }

            .margen-movil-2 {
                margin: 0 !important;
                padding: 0 !important;
            }
        }
    </style>

    <div class="container-fluid margen-movil-2">
        <div class="row mt-4 ml-1 mr-1">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-primary text-white" style="background-color: #1e5981 !important;">
                        <h4 class="mb-0">
                            <i class="fas fa-chart-line"></i> Asistencia de {{ $estudiante->persona ? $estudiante->persona->nombres . ' ' . $estudiante->persona->apellidos : 'Estudiante' }}
                        </h4>
                    </div>
            <div class="card-body">
                <!-- Información del estudiante -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card border-primary">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <strong>Estudiante:</strong><br>
                                        <span style="color: #007bff;">{{ $estudiante->persona ? $estudiante->persona->nombres . ' ' . $estudiante->persona->apellidos : 'Sin nombre' }}</span>
                                    </div>
                                    <div class="col-md-2">
                                        <strong>DNI:</strong><br>
                                        <span style="color: #28a745;">{{ $estudiante->persona ? $estudiante->persona->dni : 'Sin DNI' }}</span>
                                    </div>
                                    <div class="col-md-2">
                                        <strong>Curso:</strong><br>
                                        <span style="color: #6f42c1;">{{ $estudiante->matricula && $estudiante->matricula->curso ? $estudiante->matricula->curso->grado->nombre : 'Sin asignar' }}</span>
                                    </div>
                                    <div class="col-md-2">
                                        <strong>Sección:</strong><br>
                                        <span style="color: #fd7e14;">{{ $estudiante->matricula && $estudiante->matricula->seccion ? $estudiante->matricula->seccion->nombre : 'Sin asignar' }}</span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Año Lectivo:</strong><br>
                                        <span style="color: #20c997;">{{ $estudiante->matricula && $estudiante->matricula->curso && $estudiante->matricula->curso->anioLectivo ? $estudiante->matricula->curso->anioLectivo->nombre : 'Sin asignar' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estadísticas del estudiante -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="stats-compact d-flex justify-content-between align-items-center p-3 rounded" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border: 1px solid #dee2e6;">
                            <div class="stat-item text-center">
                                <div class="stat-value">
                                    <span class="badge badge-success stat-badge">{{ $estadisticas['presentes'] }}</span>
                                </div>
                                <small class="text-muted d-block">Presente</small>
                            </div>
                            <div class="stat-item text-center">
                                <div class="stat-value">
                                    <span class="badge badge-danger stat-badge">{{ $estadisticas['ausentes'] }}</span>
                                </div>
                                <small class="text-muted d-block">Ausente</small>
                            </div>
                            <div class="stat-item text-center">
                                <div class="stat-value">
                                    <span class="badge badge-warning stat-badge">{{ $estadisticas['tardes'] }}</span>
                                </div>
                                <small class="text-muted d-block">Tarde</small>
                            </div>
                            <div class="stat-item text-center">
                                <div class="stat-value">
                                    <span class="badge badge-info stat-badge">{{ $estadisticas['justificados'] }}</span>
                                </div>
                                <small class="text-muted d-block">Justificado</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filtros -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label>Mes:</label>
                        <select class="form-control" id="mes_filtro" onchange="filtrarAsistencias()">
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ $i == date('n') ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($i)->format('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Año:</label>
                        <select class="form-control" id="anio_filtro" onchange="filtrarAsistencias()">
                            @for($i = date('Y') - 1; $i <= date('Y'); $i++)
                                <option value="{{ $i }}" {{ $i == date('Y') ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>&nbsp;</label><br>
                        <button class="btn btn-outline-primary" onclick="filtrarAsistencias()">
                            <i class="fas fa-search"></i> Filtrar
                        </button>
                    </div>
                </div>

                <!-- Tabla de asistencias -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>Fecha</th>
                                <th>Día</th>
                                <th>Asignatura</th>
                                <th>Docente</th>
                                <th>Asistencia</th>
                                <th>Observaciones</th>
                                <th>Estado Justificación</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyAsistencias">
                            @forelse($asistencias as $asistencia)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($asistencia->fecha)->format('d/m/Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($asistencia->fecha)->format('l') }}</td>
                                <td>{{ $asistencia->sesionClase && $asistencia->sesionClase->cursoAsignatura && $asistencia->sesionClase->cursoAsignatura->asignatura ? $asistencia->sesionClase->cursoAsignatura->asignatura->nombre : 'N/A' }}</td>
                                <td>{{ $asistencia->sesionClase && $asistencia->sesionClase->cursoAsignatura && $asistencia->sesionClase->cursoAsignatura->docente ? ($asistencia->sesionClase->cursoAsignatura->docente->nombres ?? '') . ' ' . ($asistencia->sesionClase->cursoAsignatura->docente->apellidos ?? '') : 'N/A' }}</td>
                                <td>
                                    @if($asistencia->tipo_asistencia)
                                        <span class="badge badge-{{ getBadgeClass($asistencia->tipo_asistencia->codigo) }}">
                                            {{ $asistencia->tipo_asistencia->nombre }}
                                        </span>
                                    @else
                                        <span class="badge badge-secondary">Sin tipo</span>
                                    @endif
                                </td>
                                <td>{{ $asistencia->observaciones ?? '-' }}</td>
                                <td>
                                    @if($asistencia->justificado)
                                        <span class="badge badge-success">Justificado</span>
                                    @else
                                        @if($asistencia->tipo_asistencia && $asistencia->tipo_asistencia->codigo == 'A')
                                            <span class="badge badge-warning">Sin justificar</span>
                                        @else
                                            <span class="badge badge-secondary">-</span>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">No hay registros de asistencia para el período seleccionado</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                @if($asistencias->hasPages())
                    <div class="d-flex justify-content-center mt-3">
                        {{ $asistencias->links() }}
                    </div>
                @endif

                <!-- Justificaciones del estudiante -->
                @if($justificaciones->count() > 0)
                    <div class="mt-4">
                        <h5>Historial de Justificaciones</h5>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Fecha Solicitud</th>
                                        <th>Fecha Falta</th>
                                        <th>Motivo</th>
                                        <th>Estado</th>
                                        <th>Fecha Revisión</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($justificaciones as $justificacion)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($justificacion->created_at)->format('d/m/Y H:i') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($justificacion->fecha_falta)->format('d/m/Y') }}</td>
                                        <td>{{ $justificacion->motivo }}</td>
                                        <td>
                                            <span class="badge badge-{{
                                                $justificacion->estado == 'aprobado' ? 'success' :
                                                ($justificacion->estado == 'rechazado' ? 'danger' :
                                                ($justificacion->estado == 'pendiente' ? 'warning' : 'secondary'))
                                            }}">
                                                {{ $justificacion->estado }}
                                            </span>
                                        </td>
                                        <td>{{ $justificacion->fecha_revision ? \Carbon\Carbon::parse($justificacion->fecha_revision)->format('d/m/Y') : '-' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
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
    const mes = $('#mes_filtro').val();
    const anio = $('#anio_filtro').val();
    window.open('{{ route("asistencia.representante.exportar-reporte", $estudiante) }}?mes=' + mes + '&anio=' + anio, '_blank');
}

function filtrarAsistencias() {
    const mes = $('#mes_filtro').val();
    const anio = $('#anio_filtro').val();
    const url = '{{ route("asistencia.representante.detalle", ["estudiante_id" => $estudiante->estudiante_id]) }}?mes=' + mes + '&anio=' + anio;
    window.location.href = url;
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

function getJustificacionBadgeClass(estado) {
    switch(estado) {
        case 'Aprobada': return 'success';
        case 'Rechazada': return 'danger';
        case 'Pendiente': return 'warning';
        default: return 'secondary';
    }
}
</script>
<style>
/* Estadísticas Compactas */
.stats-compact {
    border-radius: 8px !important;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
}

.stat-item {
    flex: 1;
    padding: 10px;
}

.stat-badge {
    font-size: 1.2rem !important;
    padding: 8px 12px !important;
    font-weight: bold !important;
    border-radius: 6px !important;
    display: inline-block;
    min-width: 60px;
    text-align: center;
}
</style>
@endsection
