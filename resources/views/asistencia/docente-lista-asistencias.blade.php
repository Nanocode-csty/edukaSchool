@extends('cplantilla.bprincipal')
@section('titulo','Lista de Asistencias Registradas - Docente')
@section('contenidoplantilla')
<x-breadcrumb :module="'asistencia'" :section="'docente-lista-asistencias'" />
<div class="container-fluid" id="contenido-principal">
    <div class="row mt-4 ml-1 mr-1">
        <div class="col-12">
            <div class="box_block">
                <!-- Collapse header -->
                <button class="btn btn-block text-left rounded-0 btn_header header_6" type="button" data-toggle="collapse" data-target="#collapseListaAsistencias" aria-expanded="true" aria-controls="collapseListaAsistencias" style="background: #28a745 !important; font-weight: bold; color: white;">
                    <i class="fas fa-list m-1"></i>&nbsp;Lista de Asistencias Registradas
                    <div class="float-right"><i class="fas fa-chevron-down"></i></div>
                </button>
                <!-- Descripción (siempre visible, dentro del bloque) -->
                <div class="card-body rounded-0 border-0 pt-3 pb-3" style="background: #f3f3f3; border-bottom: 1px solid rgba(0,0,0,.125); border-top: 1px solid rgba(0,0,0,.125); color: #F59D24;">
                    <div class="row justify-content-center align-items-center flex-wrap">
                        <div class="col-auto text-center mb-2 mb-md-0" style="min-width:48px;">
                            <i class="fas fa-clipboard-check fa-2x"></i>
                        </div>
                        <div class="col px-2" style="text-align:justify;">
                            <p style="margin-bottom: 0px; font-family: 'Quicksand', sans-serif; font-weight: 600; color: #004a92;">
                                Lista de todas las sesiones de clase completadas. Haz clic en "Ver" para consultar los detalles de asistencia de cada sesión.
                            </p>
                        </div>
                    </div>
                </div>
                <!-- Collapse: contenido de la lista -->
                <div class="collapse show" id="collapseListaAsistencias">
                    <div class="card card-body rounded-0 border-0 pt-0 pb-2" style="background: transparent;">

                        @if(isset($error))
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle"></i> {{ $error }}
                            </div>
                        @else
                            <!-- Tabla de sesiones completadas -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0"><i class="fas fa-calendar-check"></i> Sesiones Completadas</h5>
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
                                            @if($sesiones->count() > 0)
                                                <div class="table-responsive">
                                                    <table class="table table-hover" style="border: 1px solid #28a745; border-radius: 10px; overflow: hidden;">
                                                        <thead class="text-center" style="background-color: #f8f9fa; color: #28a745;">
                                                            <tr>
                                                                <th scope="col">Fecha</th>
                                                                <th scope="col">Asignatura</th>
                                                                <th scope="col">Curso</th>
                                                                <th scope="col">Horario</th>
                                                                <th scope="col">Estado</th>
                                                                <th scope="col">Acciones</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($sesiones as $sesion)
                                                            <tr>
                                                                <td class="text-center">{{ \Carbon\Carbon::parse($sesion->fecha)->format('d/m/Y') }}</td>
                                                                <td>{{ $sesion->cursoAsignatura->asignatura->nombre ?? 'N/A' }}</td>
                                                                <td class="text-center">
                                                                    {{ $sesion->cursoAsignatura->curso->grado->nombre ?? 'N/A' }}
                                                                    {{ $sesion->cursoAsignatura->curso->seccion->nombre ?? '' }}
                                                                </td>
                                                                <td class="text-center">
                                                                    {{ substr($sesion->hora_inicio, 0, 5) }} - {{ substr($sesion->hora_fin, 0, 5) }}
                                                                </td>
                                                                <td class="text-center">
                                                                    <span class="badge badge-success">Completada</span>
                                                                </td>
                                                                <td class="text-center">
                                                                    <div class="btn-group btn-group-sm" role="group">
                                                                        <button type="button" class="btn btn-primary" title="Ver asistencia registrada"
                                                                                onclick="verAsistenciaSesion({{ $sesion->sesion_id }})">
                                                                            <i class="fas fa-eye"></i> Ver
                                                                        </button>
                                                                        <button type="button" class="btn btn-warning" title="Editar asistencia registrada"
                                                                                onclick="editarAsistenciaSesion({{ $sesion->sesion_id }})">
                                                                            <i class="fas fa-edit"></i> Editar
                                                                        </button>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <!-- Paginación -->
                                                <div class="d-flex justify-content-center mt-4">
                                                    {{ $sesiones->links() }}
                                                </div>

                                                <!-- Información de resultados -->
                                                <div class="mt-3 text-center text-muted">
                                                    <small>
                                                        Mostrando {{ $sesiones->firstItem() ?? 0 }} a {{ $sesiones->lastItem() ?? 0 }}
                                                        de {{ $sesiones->total() }} sesiones completadas
                                                    </small>
                                                </div>
                                            @else
                                                <div class="text-center py-5">
                                                    <i class="fas fa-calendar-times text-muted fa-4x mb-3"></i>
                                                    <h5 class="text-muted">No hay sesiones completadas</h5>
                                                    <p class="text-muted">
                                                        Las sesiones que completes aparecerán aquí para consulta.
                                                    </p>
                                                    <a href="{{ route('asistencia.docente.tomar-asistencia') }}" class="btn btn-primary">
                                                        <i class="fas fa-plus"></i> Tomar Asistencia
                                                    </a>
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
                                        <a href="{{ route('asistencia.docente.tomar-asistencia') }}" class="btn btn-sm" style="background-color: #007bff !important; color: white !important; border: none !important;">
                                            <i class="fas fa-plus mr-1"></i>Tomar Asistencia
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
function verAsistenciaSesion(sesionId) {
    console.log('Ver asistencia sesión:', sesionId);
    // Redirect to view attendance page
    const url = `{{ route('asistencia.docente.ver', ':id') }}`.replace(':id', sesionId);
    console.log('URL de redirección:', url);
    window.location.href = url;
}

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
