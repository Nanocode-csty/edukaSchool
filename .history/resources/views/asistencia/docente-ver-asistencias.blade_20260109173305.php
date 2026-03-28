@extends('cplantilla.bprincipal')
@section('titulo','Ver Asistencias Registradas - Docente')
@section('contenidoplantilla')
<x-breadcrumb :module="'asistencia'" :section="'docente-ver-asistencias'" />
<div class="container-fluid" id="contenido-principal">
    <div class="row mt-4 ml-1 mr-1">
        <div class="col-12">
            <div class="box_block">
                <!-- Collapse header -->
                <button class="btn btn-block text-left rounded-0 btn_header header_6" type="button" data-toggle="collapse" data-target="#collapseAsistencias" aria-expanded="true" aria-controls="collapseAsistencias" style="background: #28a745 !important; font-weight: bold; color: white;">
                    <i class="fas fa-list m-1"></i>&nbsp;Asistencias Registradas
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
                                Consulta todas las asistencias que has registrado. Filtra por curso, fecha, asignatura o tipo de asistencia. Exporta en PDF o Excel para análisis externos.
                            </p>
                        </div>
                    </div>
                </div>
                <!-- Collapse: contenido del dashboard -->
                <div class="collapse show" id="collapseAsistencias">
                    <div class="card card-body rounded-0 border-0 pt-0 pb-2" style="background: transparent;">

                        @if(isset($error))
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle"></i> {{ $error }}
                            </div>
                        @else
                            <!-- Filtros -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="mb-0"><i class="fas fa-filter"></i> Filtros de Búsqueda</h5>
                                        </div>
                                        <div class="card-body">
                                            <form method="GET" action="{{ route('asistencia.docente.ver-asistencias') }}" id="filtrosForm">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <label for="curso_id">Curso</label>
                                                        <select class="form-control" id="curso_id" name="curso_id">
                                                            <option value="">Todos los cursos</option>
                                                            @foreach($cursos_docente as $curso)
                                                            <option value="{{ $curso->curso_id }}" {{ request('curso_id') == $curso->curso_id ? 'selected' : '' }}>
                                                                {{ $curso->grado->nombre }} {{ $curso->seccion->nombre }}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="asignatura_id">Asignatura</label>
                                                        <select class="form-control" id="asignatura_id" name="asignatura_id">
                                                            <option value="">Todas las asignaturas</option>
                                                            @foreach($asignaturas_docente as $asignatura)
                                                            <option value="{{ $asignatura->asignatura_id }}" {{ request('asignatura_id') == $asignatura->asignatura_id ? 'selected' : '' }}>
                                                                {{ $asignatura->nombre }}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="fecha_inicio">Fecha Inicio</label>
                                                        <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="{{ request('fecha_inicio') }}">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="fecha_fin">Fecha Fin</label>
                                                        <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" value="{{ request('fecha_fin') }}">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="tipo_asistencia">Tipo Asistencia</label>
                                                        <select class="form-control" id="tipo_asistencia" name="tipo_asistencia">
                                                            <option value="">Todos los tipos</option>
                                                            <option value="P" {{ request('tipo_asistencia') == 'P' ? 'selected' : '' }}>Presente</option>
                                                            <option value="A" {{ request('tipo_asistencia') == 'A' ? 'selected' : '' }}>Ausente</option>
                                                            <option value="T" {{ request('tipo_asistencia') == 'T' ? 'selected' : '' }}>Tarde</option>
                                                            <option value="J" {{ request('tipo_asistencia') == 'J' ? 'selected' : '' }}>Justificado</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row mt-3">
                                                    <div class="col-12">
                                                        <button type="submit" class="btn btn-primary mr-2">
                                                            <i class="fas fa-search"></i> Filtrar
                                                        </button>
                                                        <a href="{{ route('asistencia.docente.ver-asistencias') }}" class="btn btn-secondary">
                                                            <i class="fas fa-eraser"></i> Limpiar Filtros
                                                        </a>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Estadísticas de Filtros Aplicados -->
                            @if(!empty($filtros))
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="alert alert-info">
                                        <h6><i class="fas fa-info-circle"></i> Filtros Aplicados:</h6>
                                        <ul class="mb-0">
                                            @if(isset($filtros['curso']))
                                                <li><strong>Curso:</strong> {{ $filtros['curso'] }}</li>
                                            @endif
                                            @if(isset($filtros['asignatura']))
                                                <li><strong>Asignatura:</strong> {{ $filtros['asignatura'] }}</li>
                                            @endif
                                            @if(isset($filtros['fecha_inicio']) || isset($filtros['fecha_fin']))
                                                <li><strong>Período:</strong>
                                                    {{ $filtros['fecha_inicio'] ?? 'Sin límite' }} -
                                                    {{ $filtros['fecha_fin'] ?? 'Sin límite' }}
                                                </li>
                                            @endif
                                            @if(isset($filtros['tipo_asistencia']))
                                                <li><strong>Tipo de Asistencia:</strong> {{ $filtros['tipo_asistencia'] }}</li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Tabla de Asistencias -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0"><i class="fas fa-table"></i> Registros de Asistencia</h5>
                                            <div>
                                                <button class="btn btn-danger btn-sm mr-2" onclick="exportarAsistencias('pdf')">
                                                    <i class="fas fa-file-pdf"></i> Exportar PDF
                                                </button>
                                                <button class="btn btn-success btn-sm" onclick="exportarAsistencias('excel')">
                                                    <i class="fas fa-file-excel"></i> Exportar Excel
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            @if($asistencias->count() > 0)
                                                <div class="table-responsive">
                                                    <table class="table table-hover" style="border: 1px solid #28a745; border-radius: 10px; overflow: hidden;">
                                                        <thead class="text-center" style="background-color: #f8f9fa; color: #28a745;">
                                                            <tr>
                                                                <th scope="col">Fecha</th>
                                                                <th scope="col">Estudiante</th>
                                                                <th scope="col">Curso</th>
                                                                <th scope="col">Asignatura</th>
                                                                <th scope="col">Asistencia</th>
                                                                <th scope="col">Observaciones</th>
                                                                <th scope="col">Registrado</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($asistencias as $asistencia)
                                                            <tr>
                                                                <td class="text-center">{{ \Carbon\Carbon::parse($asistencia->fecha)->format('d/m/Y') }}</td>
                                                                <td>
                                                                    {{ $asistencia->matricula->estudiante->nombres ?? 'N/A' }}
                                                                    {{ $asistencia->matricula->estudiante->apellidos ?? '' }}
                                                                </td>
                                                                <td class="text-center">
                                                                    {{ $asistencia->matricula->grado->nombre ?? 'N/A' }}
                                                                    {{ $asistencia->matricula->seccion->nombre ?? '' }}
                                                                </td>
                                                                <td>{{ $asistencia->cursoAsignatura->asignatura->nombre ?? 'N/A' }}</td>
                                                                <td class="text-center">
                                                                    <span class="badge badge-{{ $asistencia->tipoAsistencia->codigo == 'P' ? 'success' : ($asistencia->tipoAsistencia->codigo == 'A' ? 'danger' : ($asistencia->tipoAsistencia->codigo == 'T' ? 'warning' : 'info')) }}">
                                                                        {{ $asistencia->tipoAsistencia->nombre ?? 'N/A' }}
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

                                                <!-- Paginación -->
                                                <div class="d-flex justify-content-center mt-4">
                                                    {{ $asistencias->appends(request()->query())->links() }}
                                                </div>

                                                <!-- Información de resultados -->
                                                <div class="mt-3 text-center text-muted">
                                                    <small>
                                                        Mostrando {{ $asistencias->firstItem() ?? 0 }} a {{ $asistencias->lastItem() ?? 0 }}
                                                        de {{ $asistencias->total() }} registros
                                                    </small>
                                                </div>
                                            @else
                                                <div class="text-center py-5">
                                                    <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                                                    <h5 class="text-muted">No hay asistencias registradas</h5>
                                                    <p class="text-muted">
                                                        @if(!empty($filtros))
                                                            No se encontraron registros con los filtros aplicados.
                                                        @else
                                                            Las asistencias que registres aparecerán aquí.
                                                        @endif
                                                    </p>
                                                    @if(!empty($filtros))
                                                        <a href="{{ route('asistencia.docente.ver-asistencias') }}" class="btn btn-secondary">
                                                            <i class="fas fa-eraser"></i> Limpiar Filtros
                                                        </a>
                                                    @endif
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
                                        <a href="{{ route('asistencia.docente.dashboard') }}" class="btn btn-sm" style="background-color: #6c757d !important; color: white !important; border: none !important;">
                                            <i class="fas fa-arrow-left mr-1"></i>Volver al Dashboard
                                        </a>
                                        <a href="{{ route('asistencia.docente.reportes') }}" class="btn btn-sm" style="background-color: #007bff !important; color: white !important; border: none !important;">
                                            <i class="fas fa-chart-bar mr-1"></i>Ir a Reportes
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
