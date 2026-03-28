@extends('cplantilla.bprincipal')
@section('titulo','Reportes de Asistencia - Docente')
@section('contenidoplantilla')
<x-breadcrumb :module="'asistencia'" :section="'docente-reportes'" />
<div class="container-fluid" id="contenido-principal">
    <div class="row mt-4 ml-1 mr-1">
        <div class="col-12">
            <div class="box_block">
                <!-- Collapse header -->
                <button class="btn btn-block text-left rounded-0 btn_header header_6" type="button" data-toggle="collapse" data-target="#collapseReportes" aria-expanded="true" aria-controls="collapseReportes" style="background: #28a745 !important; font-weight: bold; color: white;">
                    <i class="fas fa-file-alt m-1"></i>&nbsp;Reportes de Asistencia
                    <div class="float-right"><i class="fas fa-chevron-down"></i></div>
                </button>
                <!-- Descripción (siempre visible, dentro del bloque) -->
                <div class="card-body rounded-0 border-0 pt-3 pb-3" style="background: #f3f3f3; border-bottom: 1px solid rgba(0,0,0,.125); border-top: 1px solid rgba(0,0,0,.125); color: #F59D24;">
                    <div class="row justify-content-center align-items-center flex-wrap">
                        <div class="col-auto text-center mb-2 mb-md-0" style="min-width:48px;">
                            <i class="fas fa-chart-bar fa-2x"></i>
                        </div>
                        <div class="col px-2" style="text-align:justify;">
                            <p style="margin-bottom: 0px; font-family: 'Quicksand', sans-serif; font-weight: 600; color: #004a92;">
                                Genera reportes detallados de asistencia por curso, asignatura o período. Exporta en PDF o Excel para análisis externos.
                            </p>
                        </div>
                    </div>
                </div>
                <!-- Collapse: contenido del dashboard -->
                <div class="collapse show" id="collapseReportes">
                    <div class="card card-body rounded-0 border-0 pt-0 pb-2" style="background: transparent;">

                        @if(isset($error))
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle"></i> {{ $error }}
                            </div>
                        @else
                            <!-- Generar Nuevo Reporte -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="mb-0"><i class="fas fa-plus-circle"></i> Generar Nuevo Reporte</h5>
                                        </div>
                                        <div class="card-body">
                                            <form id="reporteForm" method="POST" action="{{ route('asistencia.api.guardar-reporte') }}">
                                                @csrf
                                                <input type="hidden" name="tipo_reporte" value="docente_asistencia">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <label for="curso_id">Curso</label>
                                                        <select class="form-control" id="curso_id" name="curso_id" required>
                                                            <option value="">Seleccionar curso</option>
                                                            @foreach($cursos_docente as $curso)
                                                            <option value="{{ $curso->curso_id }}">{{ $curso->grado->nombre }} {{ $curso->seccion->nombre }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="fecha_inicio">Fecha Inicio</label>
                                                        <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="fecha_fin">Fecha Fin</label>
                                                        <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" required>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="formato">Formato</label>
                                                        <select class="form-control" id="formato" name="formato" required>
                                                            <option value="pdf">PDF</option>
                                                            <option value="excel">Excel</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row mt-3">
                                                    <div class="col-12">
                                                        <button type="submit" class="btn btn-success btn-block">
                                                            <i class="fas fa-file-export"></i> Generar Reporte
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Reportes Rápidos -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <h5 class="mb-3"><i class="fas fa-bolt"></i> Reportes Rápidos</h5>
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <button class="btn btn-outline-primary btn-block" onclick="generarReporteRapido('hoy')">
                                                <i class="fas fa-calendar-day"></i><br>Hoy
                                            </button>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <button class="btn btn-outline-success btn-block" onclick="generarReporteRapido('semana')">
                                                <i class="fas fa-calendar-week"></i><br>Esta Semana
                                            </button>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <button class="btn btn-outline-warning btn-block" onclick="generarReporteRapido('mes')">
                                                <i class="fas fa-calendar-alt"></i><br>Este Mes
                                            </button>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <button class="btn btn-outline-info btn-block" onclick="generarReporteRapido('todo')">
                                                <i class="fas fa-calendar-check"></i><br>Todo el Año
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Reportes Recientes -->
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <h5 class="mb-3"><i class="fas fa-history"></i> Reportes Recientes</h5>

                                    <div class="table-responsive">
                                        <table class="table table-hover" style="border: 1px solid #28a745; border-radius: 10px; overflow: hidden;">
                                            <thead class="text-center" style="background-color: #f8f9fa; color: #28a745;">
                                                <tr>
                                                    <th scope="col">Fecha</th>
                                                    <th scope="col">Tipo</th>
                                                    <th scope="col">Curso</th>
                                                    <th scope="col">Período</th>
                                                    <th scope="col">Formato</th>
                                                    <th scope="col">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($reportes_recientes as $reporte)
                                                <tr>
                                                    <td>{{ $reporte->created_at->format('d/m/Y H:i') }}</td>
                                                    <td>{{ $reporte->tipo_reporte }}</td>
                                                    <td>{{ $reporte->parametros['curso'] ?? 'Todos' }}</td>
                                                    <td>{{ $reporte->parametros['fecha_inicio'] ?? '-' }} - {{ $reporte->parametros['fecha_fin'] ?? '-' }}</td>
                                                    <td>
                                                        <span class="badge badge-{{ $reporte->formato == 'pdf' ? 'danger' : 'success' }}">
                                                            {{ strtoupper($reporte->formato) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-sm btn-primary" onclick="descargarReporte({{ $reporte->id }})">
                                                            <i class="fas fa-download"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="6" class="text-center">
                                                        <i class="fas fa-file-alt text-muted fa-2x mb-2"></i>
                                                        <br>No has generado reportes aún
                                                    </td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Acciones Rápidas -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-end gap-2 flex-wrap">
                                        <a href="{{ route('asistencia.docente.ver-asistencias') }}" class="btn btn-sm" style="background-color: #007bff !important; color: white !important; border: none !important;">
                                            <i class="fas fa-eye mr-1"></i>Ver Todas las Asistencias
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
document.addEventListener('DOMContentLoaded', function () {
    // Collapse icon toggle
    const btn = document.querySelector('[data-target="#collapseReportes"]');
    const icon = btn.querySelector('.fas.fa-chevron-down');
    const collapse = document.getElementById('collapseReportes');
    collapse.addEventListener('show.bs.collapse', function () {
        icon.classList.remove('fa-chevron-down');
        icon.classList.add('fa-chevron-up');
    });
    collapse.addEventListener('hide.bs.collapse', function () {
        icon.classList.remove('fa-chevron-up');
        icon.classList.add('fa-chevron-down');
    });
});

function generarReporteRapido(tipo) {
    let fechaInicio, fechaFin;

    switch(tipo) {
        case 'hoy':
            fechaInicio = fechaFin = new Date().toISOString().split('T')[0];
            break;
        case 'semana':
            const hoy = new Date();
            const inicioSemana = new Date(hoy);
            inicioSemana.setDate(hoy.getDate() - hoy.getDay());
            fechaInicio = inicioSemana.toISOString().split('T')[0];
            fechaFin = hoy.toISOString().split('T')[0];
            break;
        case 'mes':
            const inicioMes = new Date();
            inicioMes.setDate(1);
            fechaInicio = inicioMes.toISOString().split('T')[0];
            fechaFin = new Date().toISOString().split('T')[0];
            break;
        case 'todo':
            fechaInicio = new Date().getFullYear() + '-01-01';
            fechaFin = new Date().toISOString().split('T')[0];
            break;
    }

    // Mostrar modal para seleccionar curso
    Swal.fire({
        title: 'Seleccionar Curso',
        input: 'select',
        inputOptions: {
            @foreach($cursos_docente as $curso)
            '{{ $curso->id }}': '{{ $curso->grado->nombre }} {{ $curso->seccion->nombre }}',
            @endforeach
        },
        inputPlaceholder: 'Selecciona un curso',
        showCancelButton: true,
        inputValidator: (value) => {
            return new Promise((resolve) => {
                if (value) {
                    resolve();
                } else {
                    resolve('Debes seleccionar un curso');
                }
            });
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Enviar formulario con datos
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('tipo_reporte', 'docente_asistencia');
            formData.append('curso_id', result.value);
            formData.append('fecha_inicio', fechaInicio);
            formData.append('fecha_fin', fechaFin);
            formData.append('formato', 'pdf');

            fetch('{{ route("asistencia.api.guardar-reporte") }}', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: 'Reporte generado correctamente',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(error => {
                Swal.fire('Error', 'Error al generar el reporte', 'error');
            });
        }
    });
}

function descargarReporte(reporteId) {
    window.open('{{ route("asistencia.descargar-reporte-historial", ":id") }}'.replace(':id', reporteId), '_blank');
}

// Manejar envío del formulario
document.getElementById('reporteForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch(this.action, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: 'Reporte generado correctamente',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    })
    .catch(error => {
        Swal.fire('Error', 'Error al generar el reporte', 'error');
    });
});
</script>
@endpush