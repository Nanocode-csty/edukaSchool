@extends('cplantilla.bprincipal')

@section('titulo', 'Asistencias de Mis Estudiantes')
{{-- Vista principal para representantes donde pueden ver la asistencia de sus estudiantes --}}
{{-- Permite solicitar justificaciones y acceder a reportes detallados --}}

@section('contenidoplantilla')
    @if(isset($error))
    <div class="container-fluid margen-movil-2">
        <div class="row mt-4 ml-1 mr-1">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-danger text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-exclamation-triangle"></i> Error al cargar la página
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-danger">
                            <strong>Error:</strong> {{ $error }}
                        </div>

                        @if(isset($debug))
                        <div class="mt-3">
                            <h5>Información de depuración:</h5>
                            <ul class="list-group">
                                <li class="list-group-item"><strong>Usuario autenticado:</strong> {{ $debug['user_authenticated'] ? 'Sí' : 'No' }}</li>
                                <li class="list-group-item"><strong>ID de usuario:</strong> {{ $debug['user_id'] ?? 'N/A' }}</li>
                                <li class="list-group-item"><strong>Roles del usuario:</strong> {{ implode(', ', $debug['user_roles']) }}</li>
                                <li class="list-group-item"><strong>Tiene persona:</strong> {{ $debug['has_persona'] ? 'Sí' : 'No' }}</li>
                                <li class="list-group-item"><strong>Tiene representante:</strong> {{ $debug['has_representante'] ? 'Sí' : 'No' }}</li>
                            </ul>
                        </div>
                        @endif

                        @if(isset($estudiantes) && $estudiantes->count() > 0)
                        <div class="mt-3">
                            <h5>Estudiantes encontrados ({{ $estudiantes->count() }}):</h5>
                            <ul class="list-group">
                                @foreach($estudiantes as $estudiante)
                                <li class="list-group-item">
                                    {{ $estudiante->nombres }} {{ $estudiante->apellidos }} (ID: {{ $estudiante->estudiante_id }})
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
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
                            <i class="fas fa-user-friends"></i> Asistencias de Mis Estudiantes
                        </h4>
                    </div>
            <div class="card-body">
                <!-- Estadísticas generales -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="stats-compact d-flex justify-content-between align-items-center p-3 rounded" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border: 1px solid #dee2e6;">
                            <div class="stat-item text-center">
                                <div class="stat-value">
                                    <span class="badge badge-success stat-badge">{{ $estadisticasGenerales['total_estudiantes'] }}</span>
                                </div>
                                <small class="text-muted d-block">Mis Estudiantes</small>
                            </div>
                            <div class="stat-item text-center">
                                <div class="stat-value">
                                    <span class="badge badge-primary stat-badge">{{ $estadisticasGenerales['promedio_asistencia'] }}%</span>
                                </div>
                                <small class="text-muted d-block">Asistencia Promedio</small>
                            </div>
                            <div class="stat-item text-center">
                                <div class="stat-value">
                                    <span class="badge badge-warning stat-badge">{{ $estadisticasGenerales['total_inasistencias_mes'] }}</span>
                                </div>
                                <small class="text-muted d-block">Inasistencias (Mes)</small>
                            </div>
                            <div class="stat-item text-center">
                                <div class="stat-value">
                                    <span class="badge badge-danger stat-badge">{{ $estadisticasGenerales['justificaciones_pendientes'] }}</span>
                                </div>
                                <small class="text-muted d-block">Justificaciones Pendientes</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lista de estudiantes -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="tablaEstudiantes">
                        <thead class="thead-dark">
                            <tr>
                                <th>Estudiante</th>
                                <th>Curso</th>
                                <th>Sección</th>
                                <th>Asistencia Hoy</th>
                                <th>% Asistencia (Mes)</th>
                                <th>Inasistencias (Mes)</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyEstudiantes">
                            @forelse($estudiantes as $estudiante)
                            <tr>
                                <td>
                                    <div>
                                        <strong>{{ $estudiante->persona ? $estudiante->persona->nombres . ' ' . $estudiante->persona->apellidos : 'Sin nombre' }}</strong>
                                        <br><small class="text-muted">{{ $estudiante->persona ? $estudiante->persona->dni : 'Sin DNI' }}</small>
                                    </div>
                                </td>
                                <td>{{ $estudiante->matricula && $estudiante->matricula->grado ? $estudiante->matricula->grado->nombre : 'Sin asignar' }}</td>
                                <td>{{ $estudiante->matricula && $estudiante->matricula->seccion ? $estudiante->matricula->seccion->nombre : 'Sin asignar' }}</td>
                                <td>
                                    @if($estudiante->asistencia_hoy && $estudiante->asistencia_hoy->tipo_asistencia)
                                        <span class="badge badge-{{ getBadgeClass($estudiante->asistencia_hoy->tipo_asistencia->codigo) }}">
                                            {{ $estudiante->asistencia_hoy->tipo_asistencia->nombre }}
                                        </span>
                                    @else
                                        <span class="badge badge-secondary">Sin registro</span>
                                    @endif
                                </td>
                                <td>
                                    <span style="color: {{ $estudiante->porcentaje_asistencia >= 85 ? '#28a745' : ($estudiante->porcentaje_asistencia >= 70 ? '#ffc107' : '#dc3545') }};">
                                        {{ number_format($estudiante->porcentaje_asistencia, 1) }}%
                                    </span>
                                </td>
                                <td>
                                    <span style="color: {{ $estudiante->inasistencias_mes > 5 ? '#dc3545' : '#6c757d' }};">
                                        {{ $estudiante->inasistencias_mes }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-info btn-sm" onclick="verDetalle({{ $estudiante->estudiante_id }})" title="Ver detalle">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-warning btn-sm" onclick="solicitarJustificacion({{ $estudiante->estudiante_id }})" title="Solicitar justificación">
                                            <i class="fas fa-file-medical"></i>
                                        </button>
                                        <button class="btn btn-secondary btn-sm" onclick="exportarReporte({{ $estudiante->estudiante_id }})" title="Exportar reporte">
                                            <i class="fas fa-file-pdf"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">No tiene estudiantes asignados</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para solicitar justificación -->
<div class="modal fade" id="modalJustificacion" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Solicitar Justificación de Inasistencia</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formJustificacion">
                    @csrf
                    <input type="hidden" id="estudiante_id" name="estudiante_id">

                    <div class="form-group">
                        <label for="fecha_falta">Fecha de la falta *</label>
                        <input type="date" class="form-control" id="fecha_falta" name="fecha_falta" required
                               max="{{ date('Y-m-d') }}">
                    </div>

                    <div class="form-group">
                        <label for="motivo">Motivo de la inasistencia *</label>
                        <textarea class="form-control" id="motivo" name="motivo" rows="3"
                                  placeholder="Describa el motivo de la inasistencia..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="documento_adjunto">Documento adjunto (opcional)</label>
                        <input type="file" class="form-control-file" id="documento_adjunto" name="documento_adjunto"
                               accept=".pdf,.jpg,.jpeg,.png">
                        <small class="form-text text-muted">Formatos permitidos: PDF, JPG, PNG. Máximo 2MB.</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="enviarJustificacion()">
                    <i class="fas fa-paper-plane"></i> Enviar Solicitud
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
function verDetalle(estudianteId) {
    window.location.href = '{{ url("asistencia/representante/detalle") }}/' + estudianteId;
}

function solicitarJustificacion(estudianteId) {
    $('#estudiante_id').val(estudianteId);
    $('#fecha_falta').val('');
    $('#motivo').val('');
    $('#documento_adjunto').val('');
    $('#modalJustificacion').modal('show');
}

function enviarJustificacion() {
    const formData = new FormData(document.getElementById('formJustificacion'));

    $.ajax({
        url: '{{ route("asistencia.representante.solicitar-justificacion") }}',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                $('#modalJustificacion').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: '¡Solicitud enviada!',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            } else {
                mostrarError(response.message);
            }
        },
        error: function(xhr) {
            let message = 'Error al enviar la solicitud';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }
            mostrarError(message);
        }
    });
}

function exportarReporte(estudianteId) {
    window.open('{{ route("asistencia.representante.exportar-reporte", ":id") }}'.replace(':id', estudianteId), '_blank');
}

function mostrarEstadisticasGenerales() {
    Swal.fire({
        title: 'Estadísticas Generales',
        html: `
            <div class="text-center">
                <div class="row mb-3">
                    <div class="col-6">
                        <h4 style="color: #28a745;">${{{ $estadisticasGenerales['total_asistencias_mes'] ?? 0 }}}</h4>
                        <small>Asistencias del Mes</small>
                    </div>
                    <div class="col-6">
                        <h4 style="color: #dc3545;">${{{ $estadisticasGenerales['total_inasistencias_mes'] ?? 0 }}}</h4>
                        <small>Inasistencias del Mes</small>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <h4 style="color: #ffc107;">${{{ $estadisticasGenerales['justificaciones_aprobadas'] ?? 0 }}}</h4>
                        <small>Justificaciones Aprobadas</small>
                    </div>
                    <div class="col-6">
                        <h4 style="color: #17a2b8;">${{{ $estadisticasGenerales['justificaciones_pendientes'] ?? 0 }}}</h4>
                        <small>Justificaciones Pendientes</small>
                    </div>
                </div>
            </div>
        `,
        confirmButtonText: 'Cerrar'
    });
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

function mostrarError(mensaje) {
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: mensaje
    });
}
</script>
@endsection
    @endif
