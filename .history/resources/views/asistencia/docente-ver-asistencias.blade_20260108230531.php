@extends('cplantilla.bprincipal')
@section('titulo','Ver Asistencias - Docente')
@section('contenidoplantilla')
<x-breadcrumb :module="'asistencia'" :section="'docente'" />
<div class="container-fluid" id="contenido-principal">
    <div class="row mt-4 ml-1 mr-1">
        <div class="col-12">
            <div class="box_block">
                <!-- Collapse header -->
                <button class="btn btn-block text-left rounded-0 btn_header header_6" type="button" data-toggle="collapse" data-target="#collapseVerAsistencias" aria-expanded="true" aria-controls="collapseVerAsistencias" style="background: #007bff !important; font-weight: bold; color: white;">
                    <i class="fas fa-eye m-1"></i>&nbsp;Ver Asistencias Tomadas
                    <div class="float-right"><i class="fas fa-chevron-down"></i></div>
                </button>
                <!-- Descripción (siempre visible, dentro del bloque) -->
                <div class="card-body rounded-0 border-0 pt-3 pb-3" style="background: #f3f3f3; border-bottom: 1px solid rgba(0,0,0,.125); border-top: 1px solid rgba(0,0,0,.125); color: #F59D24;">
                    <div class="row justify-content-center align-items-center flex-wrap">
                        <div class="col-auto text-center mb-2 mb-md-0" style="min-width:48px;">
                            <i class="fas fa-clipboard-list fa-2x"></i>
                        </div>
                        <div class="col px-2" style="text-align:justify;">
                            <p style="margin-bottom: 0px; font-family: 'Quicksand', sans-serif; font-weight: 600; color: #004a92;">
                                Consulta todas las asistencias que has tomado en tus clases. Filtra por fecha, curso o asignatura para encontrar información específica.
                            </p>
                        </div>
                    </div>
                </div>
                <!-- Collapse: contenido del dashboard -->
                <div class="collapse show" id="collapseVerAsistencias">
                    <div class="card card-body rounded-0 border-0 pt-0 pb-2" style="background: transparent;">

                        @if(isset($error))
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle"></i> {{ $error }}
                            </div>
                        @else
                            <!-- Filtros -->
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="mb-0"><i class="fas fa-filter"></i> Filtros</h5>
                                        </div>
                                        <div class="card-body">
                                            <form method="GET" action="{{ route('asistencia.docente.ver-asistencias') }}" id="filtroForm">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <label for="fecha_inicio">Fecha Inicio</label>
                                                        <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="{{ request('fecha_inicio') }}">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="fecha_fin">Fecha Fin</label>
                                                        <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" value="{{ request('fecha_fin') }}">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="tipo_asistencia">Tipo de Asistencia</label>
                                                        <select class="form-control" id="tipo_asistencia" name="tipo_asistencia">
                                                            <option value="">Todos</option>
                                                            <option value="P" {{ request('tipo_asistencia') == 'P' ? 'selected' : '' }}>Presente</option>
                                                            <option value="A" {{ request('tipo_asistencia') == 'A' ? 'selected' : '' }}>Ausente</option>
                                                            <option value="T" {{ request('tipo_asistencia') == 'T' ? 'selected' : '' }}>Tarde</option>
                                                            <option value="J" {{ request('tipo_asistencia') == 'J' ? 'selected' : '' }}>Justificado</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>&nbsp;</label>
                                                        <div>
                                                            <button type="submit" class="btn btn-primary btn-block">
                                                                <i class="fas fa-search"></i> Filtrar
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Lista de asistencias -->
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <h5 class="mb-3"><i class="fas fa-list"></i> Registro de Asistencias</h5>

                                    <div class="table-responsive">
                                        <table id="add-row" class="table-hover table" style="border: 1px solid #007bff; border-radius: 10px; overflow: hidden;">
                                            <thead class="text-center table-hover" style="background-color: #f8f9fa; color: #007bff; border:#007bff !important">
                                                <tr>
                                                    <th scope="col">Fecha</th>
                                                    <th scope="col">Estudiante</th>
                                                    <th scope="col">Curso</th>
                                                    <th scope="col">Asignatura</th>
                                                    <th scope="col">Asistencia</th>
                                                    <th scope="col">Observaciones</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbodyAsistencias">
                                                @forelse($asistencias as $asistencia)
                                                <tr>
                                                    <td>{{ $asistencia->fecha->format('d/m/Y') }}</td>
                                                    <td>
                                                        <strong>{{ $asistencia->matricula->estudiante->persona->nombres }} {{ $asistencia->matricula->estudiante->persona->apellidos }}</strong>
                                                    </td>
                                                    <td>{{ $asistencia->matricula->curso->grado->nombre }} {{ $asistencia->matricula->curso->seccion->nombre }}</td>
                                                    <td>{{ $asistencia->cursoAsignatura->asignatura->nombre }}</td>
                                                    <td>
                                                        @if($asistencia->tipoAsistencia->codigo == 'P')
                                                            <span class="badge badge-success">Presente</span>
                                                        @elseif($asistencia->tipoAsistencia->codigo == 'A')
                                                            <span class="badge badge-danger">Ausente</span>
                                                        @elseif($asistencia->tipoAsistencia->codigo == 'T')
                                                            <span class="badge badge-warning">Tarde</span>
                                                        @elseif($asistencia->tipoAsistencia->codigo == 'J')
                                                            <span class="badge badge-info">Justificado</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $asistencia->observaciones ?? '-' }}</td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="6" class="text-center">
                                                        <i class="fas fa-calendar-times text-muted fa-2x mb-2"></i>
                                                        <br>No se encontraron registros de asistencia con los filtros aplicados
                                                    </td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Paginación -->
                                    <div class="d-flex justify-content-center mt-3">
                                        {{ $asistencias->appends(request()->query())->links() }}
                                    </div>
                                </div>
                            </div>

                            <!-- Acciones Rápidas -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-end gap-2 flex-wrap">
                                        <button class="btn btn-sm" onclick="exportarAsistencias()" style="background-color: #28a745 !important; color: white !important; border: none !important;">
                                            <i class="fas fa-download mr-1"></i>Exportar Reporte
                                        </button>
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
document.addEventListener('DOMContentLoaded', function () {
    // Collapse icon toggle
    const btn = document.querySelector('[data-target="#collapseVerAsistencias"]');
    const icon = btn.querySelector('.fas.fa-chevron-down');
    const collapse = document.getElementById('collapseVerAsistencias');
    collapse.addEventListener('show.bs.collapse', function () {
        icon.classList.remove('fa-chevron-down');
        icon.classList.add('fa-chevron-up');
    });
    collapse.addEventListener('hide.bs.collapse', function () {
        icon.classList.remove('fa-chevron-up');
        icon.classList.add('fa-chevron-down');
    });
});

function exportarAsistencias() {
    const formData = new FormData(document.getElementById('filtroForm'));
    const params = new URLSearchParams(formData);

    Swal.fire({
        title: 'Exportar Asistencias',
        text: '¿Deseas exportar un reporte con los filtros aplicados?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, exportar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            window.open('{{ route("asistencia.docente.reportes") }}?' + params.toString(), '_blank');
        }
    });
}
</script>
@endpush