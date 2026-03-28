@extends('cplantilla.bprincipal')
@section('titulo','Dashboard de Asistencia - Representante')
@section('contenidoplantilla')

<x-breadcrumb :module="'asistencia'" :section="'representante-dashboard'" />

<div class="container-fluid" id="contenido-principal">
    <div class="row mt-4 ml-1 mr-1">
        <div class="col-12">
            <div class="box_block">
                <!-- Collapse header -->
                <button class="btn btn-block text-left rounded-0 btn_header header_6" type="button" data-toggle="collapse" data-target="#collapseDashboard" aria-expanded="true" aria-controls="collapseDashboard" style="background: #17a2b8 !important; font-weight: bold; color: white;">
                    <i class="fas fa-tachometer-alt m-1"></i>&nbsp;Dashboard de Asistencia
                    <div class="float-right"><i class="fas fa-chevron-down"></i></div>
                </button>
                <!-- Descripción (siempre visible, dentro del bloque) -->
                <div class="card-body rounded-0 border-0 pt-3 pb-3" style="background: #f3f3f3; border-bottom: 1px solid rgba(0,0,0,.125); border-top: 1px solid rgba(0,0,0,.125); color: #F59D24;">
                    <div class="row justify-content-center align-items-center flex-wrap">
                        <div class="col-auto text-center mb-2 mb-md-0" style="min-width:48px;">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                        <div class="col px-2" style="text-align:justify;">
                            <p style="margin-bottom: 0px; font-family: 'Quicksand', sans-serif; font-weight: 600; color: #004a92;">
                                Monitorea la asistencia de tus estudiantes. Revisa estadísticas generales, solicita justificaciones y accede a reportes detallados.
                            </p>
                        </div>
                    </div>
                </div>
                <!-- Collapse: contenido del dashboard -->
                <div class="collapse show" id="collapseDashboard">
                    <div class="card card-body rounded-0 border-0 pt-0 pb-2" style="background: transparent;">

                        @if(isset($error))
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle"></i> {{ $error }}
                            </div>
                        @else
                            <!-- Estadísticas Generales -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <h5 class="mb-3"><i class="fas fa-chart-bar"></i> Estadísticas Generales del Mes</h5>
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <div class="card border-primary">
                                                <div class="card-body text-center">
                                                    <div class="h3 text-primary">{{ $estadisticas['total_estudiantes'] ?? 0 }}</div>
                                                    <div class="text-muted">Estudiantes</div>
                                                    <small class="text-primary"><i class="fas fa-users"></i> Total</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <div class="card border-success">
                                                <div class="card-body text-center">
                                                    <div class="h3 text-success">{{ $estadisticas['promedio_asistencia'] ?? 0 }}%</div>
                                                    <div class="text-muted">Asistencia Promedio</div>
                                                    <small class="text-success"><i class="fas fa-check-circle"></i> Mensual</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <div class="card border-danger">
                                                <div class="card-body text-center">
                                                    <div class="h3 text-danger">{{ $estadisticas['total_inasistencias'] ?? 0 }}</div>
                                                    <div class="text-muted">Inasistencias</div>
                                                    <small class="text-danger"><i class="fas fa-times-circle"></i> Total</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <div class="card border-warning">
                                                <div class="card-body text-center">
                                                    <div class="h3 text-warning">{{ $estadisticas['justificaciones_pendientes'] ?? 0 }}</div>
                                                    <div class="text-muted">Justificaciones</div>
                                                    <small class="text-warning"><i class="fas fa-clock"></i> Pendientes</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Lista de Estudiantes -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="mb-0"><i class="fas fa-user-graduate"></i> Mis Estudiantes</h5>
                                        </div>
                                        <div class="card-body">
                                            @if($estadisticas['total_estudiantes'] > 0)
                                                <div class="table-responsive">
                                                    <table class="table table-hover" style="border: 1px solid #17a2b8; border-radius: 10px; overflow: hidden;">
                                                        <thead class="text-center" style="background-color: #f8f9fa; color: #17a2b8;">
                                                            <tr>
                                                                <th scope="col">Estudiante</th>
                                                                <th scope="col">Curso</th>
                                                                <th scope="col">Asistencia Hoy</th>
                                                                <th scope="col">% Asistencia</th>
                                                                <th scope="col">Inasistencias</th>
                                                                <th scope="col">Acciones</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php
                                                                $representante = Auth::user()->persona->representante;
                                                                $estudiantes = $representante->estudiantes()
                                                                    ->with(['persona', 'matricula.grado', 'matricula.seccion'])
                                                                    ->get()
                                                                    ->map(function($estudiante) {
                                                                        if (!$estudiante->matricula) {
                                                                            $estudiante->asistencia_hoy = null;
                                                                            $estudiante->porcentaje_asistencia = 0;
                                                                            $estudiante->inasistencias_mes = 0;
                                                                            return $estudiante;
                                                                        }

                                                                        // Calcular estadísticas del mes actual
                                                                        $mesActual = now()->month;
                                                                        $anioActual = now()->year;

                                                                        $asistenciasMes = \App\Models\AsistenciaAsignatura::where('matricula_id', $estudiante->matricula->matricula_id)
                                                                            ->whereMonth('fecha', $mesActual)
                                                                            ->whereYear('fecha', $anioActual)
                                                                            ->get();

                                                                        $totalAsistencias = $asistenciasMes->count();
                                                                        $inasistencias = $asistenciasMes->where('tipo_asistencia_id', \App\Models\TipoAsistencia::where('codigo', 'A')->first()->id ?? 2)->count();

                                                                        $estudiante->asistencia_hoy = \App\Models\AsistenciaAsignatura::where('matricula_id', $estudiante->matricula->matricula_id)
                                                                            ->whereDate('fecha', today())
                                                                            ->with('tipoAsistencia')
                                                                            ->first();

                                                                        $estudiante->porcentaje_asistencia = $totalAsistencias > 0 ?
                                                                            round((($totalAsistencias - $inasistencias) / $totalAsistencias) * 100, 1) : 0;

                                                                        $estudiante->inasistencias_mes = $inasistencias;

                                                                        return $estudiante;
                                                                    });
                                                            @endphp

                                                            @foreach($estudiantes as $estudiante)
                                                            <tr>
                                                                <td>
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="avatar-circle mr-2" style="width: 35px; height: 35px; border-radius: 50%; background: #17a2b8; color: white; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                                                                            {{ substr($estudiante->persona->nombres ?? 'N', 0, 1) }}{{ substr($estudiante->persona->apellidos ?? 'A', 0, 1) }}
                                                                        </div>
                                                                        <div>
                                                                            <div class="font-weight-bold">{{ $estudiante->persona->nombres ?? 'N/A' }}</div>
                                                                            <small class="text-muted">{{ $estudiante->persona->apellidos ?? '' }}</small>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td class="text-center">
                                                                    @if($estudiante->matricula)
                                                                        {{ $estudiante->matricula->grado->nombre ?? 'N/A' }}
                                                                        {{ $estudiante->matricula->seccion->nombre ?? '' }}
                                                                    @else
                                                                        <span class="text-muted">Sin matrícula</span>
                                                                    @endif
                                                                </td>
                                                                <td class="text-center">
                                                                    @if($estudiante->asistencia_hoy)
                                                                        <span class="badge badge-{{ $estudiante->asistencia_hoy->tipoAsistencia->codigo == 'P' ? 'success' : ($estudiante->asistencia_hoy->tipoAsistencia->codigo == 'A' ? 'danger' : ($estudiante->asistencia_hoy->tipoAsistencia->codigo == 'T' ? 'warning' : 'info')) }}">
                                                                            {{ $estudiante->asistencia_hoy->tipoAsistencia->nombre }}
                                                                        </span>
                                                                    @else
                                                                        <span class="badge badge-secondary">Sin registro</span>
                                                                    @endif
                                                                </td>
                                                                <td class="text-center">
                                                                    <span class="font-weight-bold {{ $estudiante->porcentaje_asistencia >= 90 ? 'text-success' : ($estudiante->porcentaje_asistencia >= 80 ? 'text-warning' : 'text-danger') }}">
                                                                        {{ $estudiante->porcentaje_asistencia }}%
                                                                    </span>
                                                                </td>
                                                                <td class="text-center">
                                                                    <span class="font-weight-bold text-danger">{{ $estudiante->inasistencias_mes }}</span>
                                                                </td>
                                                                <td class="text-center">
                                                                    <div class="btn-group" role="group">
                                                                        <a href="{{ route('asistencia.representante.detalle', $estudiante->estudiante_id) }}"
                                                                           class="btn btn-sm btn-outline-primary" title="Ver detalles">
                                                                            <i class="fas fa-eye"></i>
                                                                        </a>
                                                                        <button class="btn btn-sm btn-outline-success"
                                                                                onclick="solicitarJustificacion({{ $estudiante->estudiante_id }}, '{{ addslashes($estudiante->persona->nombres . ' ' . $estudiante->persona->apellidos) }}')"
                                                                                title="Solicitar justificación">
                                                                            <i class="fas fa-file-medical"></i>
                                                                        </button>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @else
                                                <div class="text-center py-5">
                                                    <i class="fas fa-users text-muted fa-4x mb-3"></i>
                                                    <h5 class="text-muted">No tienes estudiantes asignados</h5>
                                                    <p class="text-muted">Contacta al administrador si crees que esto es un error.</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Acciones Rápidas -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                                        <div class="d-flex gap-2 flex-wrap">
                                            <button class="btn btn-outline-primary" onclick="actualizarEstadisticas()">
                                                <i class="fas fa-sync-alt"></i> Actualizar Estadísticas
                                            </button>
                                            @if($estadisticas['justificaciones_pendientes'] > 0)
                                            <span class="badge badge-warning">
                                                <i class="fas fa-clock"></i> {{ $estadisticas['justificaciones_pendientes'] }} justificaciones pendientes
                                            </span>
                                            @endif
                                        </div>
                                        <div class="d-flex gap-2 flex-wrap">
                                            <a href="{{ route('asistencia.representante.index') }}" class="btn btn-primary">
                                                <i class="fas fa-list"></i> Ver Todos los Estudiantes
                                            </a>
                                            <a href="{{ route('rutarrr1') }}" class="btn btn-secondary">
                                                <i class="fas fa-arrow-left"></i> Volver al Inicio
                                            </a>
                                        </div>
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

<!-- Modal para solicitar justificación -->
<div class="modal fade" id="justificacionModal" tabindex="-1" role="dialog" aria-labelledby="justificacionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="justificacionModalLabel">
                    <i class="fas fa-file-medical"></i> Solicitar Justificación
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="justificacionForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="estudiante_id">Estudiante</label>
                        <input type="text" class="form-control" id="estudianteNombre" readonly>
                        <input type="hidden" id="estudiante_id" name="estudiante_id">
                    </div>
                    <div class="form-group">
                        <label for="fecha_falta">Fecha de la falta *</label>
                        <input type="date" class="form-control" id="fecha_falta" name="fecha_falta" required max="{{ date('Y-m-d') }}">
                    </div>
                    <div class="form-group">
                        <label for="motivo">Motivo de la justificación *</label>
                        <textarea class="form-control" id="motivo" name="motivo" rows="3" required
                                  placeholder="Describe el motivo de la inasistencia..."></textarea>
                    </div>
                    <div class="form-group">
                        <label for="documento_adjunto">Documento adjunto (opcional)</label>
                        <input type="file" class="form-control-file" id="documento_adjunto" name="documento_adjunto"
                               accept=".pdf,.jpg,.jpeg,.png">
                        <small class="form-text text-muted">Formatos permitidos: PDF, JPG, PNG. Máximo 2MB.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Enviar Solicitud
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js-extra')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Collapse icon toggle
    const btn = document.querySelector('[data-target="#collapseDashboard"]');
    const icon = btn.querySelector('.fas.fa-chevron-down');
    const collapse = document.getElementById('collapseDashboard');
    collapse.addEventListener('show.bs.collapse', function () {
        icon.classList.remove('fa-chevron-down');
        icon.classList.add('fa-chevron-up');
    });
    collapse.addEventListener('hide.bs.collapse', function () {
        icon.classList.remove('fa-chevron-up');
        icon.classList.add('fa-chevron-down');
    });
});

function solicitarJustificacion(estudianteId, estudianteNombre) {
    document.getElementById('estudiante_id').value = estudianteId;
    document.getElementById('estudianteNombre').value = estudianteNombre;
    document.getElementById('fecha_falta').value = '';
    document.getElementById('motivo').value = '';
    document.getElementById('documento_adjunto').value = '';

    $('#justificacionModal').modal('show');
}

function actualizarEstadisticas() {
    // Reload the page to refresh statistics
    location.reload();
}

// Handle justificación form submission
document.getElementById('justificacionForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch('{{ route("asistencia.representante.solicitar-justificacion") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            $('#justificacionModal').modal('hide');
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: data.message,
                timer: 3000,
                showConfirmButton: false
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Error', 'Error al enviar la solicitud', 'error');
    });
});
</script>
@endpush
