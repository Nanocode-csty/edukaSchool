@extends('cplantilla.bprincipal')

@section('titulo', 'Gestionar Justificaciones - Eduka Perú')

@section('contenidoplantilla')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Justificaciones Pendientes de Revisión</h4>
            </div>
            <div class="card-body">
                @if($justificaciones->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Fecha Solicitud</th>
                                    <th>Estudiante</th>
                                    <th>Fecha Falta</th>
                                    <th>Motivo</th>
                                    <th>Documento</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($justificaciones as $justificacion)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($justificacion->fecha_solicitud)->format('d/m/Y H:i') }}</td>
                                    <td>
                                        {{ $justificacion->estudiante->nombres }} {{ $justificacion->estudiante->apellidos }}
                                        <br><small class="text-muted">{{ $justificacion->estudiante->dni }}</small>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($justificacion->fecha_falta)->format('d/m/Y') }}</td>
                                    <td>{{ $justificacion->motivo }}</td>
                                    <td>
                                        @if($justificacion->documento_adjunto)
                                            <a href="{{ asset('storage/justificaciones/' . $justificacion->documento_adjunto) }}"
                                               target="_blank" class="btn btn-sm btn-info">
                                                <i class="fas fa-file-pdf"></i> Ver Documento
                                            </a>
                                        @else
                                            <span class="text-muted">Sin documento</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-warning">Pendiente</span>
                                    </td>
                                    <td>
                                        <button class="btn btn-success btn-sm" onclick="procesarJustificacion({{ $justificacion->id }}, 'Aprobar')">
                                            <i class="fas fa-check"></i> Aprobar
                                        </button>
                                        <button class="btn btn-danger btn-sm" onclick="procesarJustificacion({{ $justificacion->id }}, 'Rechazar')">
                                            <i class="fas fa-times"></i> Rechazar
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <div class="d-flex justify-content-center mt-3">
                        {{ $justificaciones->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                        <h4 class="mt-3">¡Todo al día!</h4>
                        <p class="text-muted">No hay justificaciones pendientes de revisión.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal para procesar justificación -->
<div class="modal fade" id="modalProcesar" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Procesar Justificación</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formProcesar">
                    @csrf
                    <input type="hidden" id="justificacion_id" name="justificacion_id">
                    <input type="hidden" id="accion" name="accion">

                    <div class="form-group">
                        <label for="observaciones">Observaciones (opcional)</label>
                        <textarea class="form-control" id="observaciones" name="observaciones"
                                  rows="3" placeholder="Ingrese observaciones adicionales..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnConfirmar" onclick="confirmarProcesamiento()">
                    Confirmar
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
function procesarJustificacion(justificacionId, accion) {
    $('#justificacion_id').val(justificacionId);
    $('#accion').val(accion);
    $('#observaciones').val('');

    const titulo = accion === 'Aprobar' ? 'Aprobar Justificación' : 'Rechazar Justificación';
    const btnTexto = accion === 'Aprobar' ? 'Aprobar' : 'Rechazar';
    const btnClass = accion === 'Aprobar' ? 'btn-success' : 'btn-danger';

    $('#modalTitle').text(titulo);
    $('#btnConfirmar').text(btnTexto).removeClass('btn-success btn-danger').addClass(btnClass);

    $('#modalProcesar').modal('show');
}

function confirmarProcesamiento() {
    const formData = new FormData(document.getElementById('formProcesar'));

    $.ajax({
        url: '{{ route("asistencia.procesar-verificacion") }}',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                $('#modalProcesar').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
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
            let message = 'Error al procesar la justificación';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }
            mostrarError(message);
        }
    });
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
