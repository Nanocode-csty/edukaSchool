@extends('cplantilla.bprincipal')
@section('titulo','Gestionar Justificaciones')
@section('contenidoplantilla')
<div class="container-fluid" id="contenido-principal">
    <div class="row mt-4 ml-1 mr-1">
        <div class="col-12">
            <div class="box_block">
                <!-- Collapse header -->
                <button class="btn btn-block text-left rounded-0 btn_header header_6" type="button" data-toggle="collapse" data-target="#collapseTablaJustificaciones" aria-expanded="true" aria-controls="collapseTablaJustificaciones" style="background: #0A8CB3 !important; font-weight: bold; color: white;">
                    <i class="fas fa-clipboard-check m-1"></i>&nbsp;Gestionar Justificaciones
                    <div class="float-right"><i class="fas fa-chevron-down"></i></div>
                </button>
                <!-- Descripción (siempre visible, dentro del bloque) -->
                <div class="card-body rounded-0 border-0 pt-3 pb-3" style="background: #f3f3f3; border-bottom: 1px solid rgba(0,0,0,.125); border-top: 1px solid rgba(0,0,0,.125); color: #F59D24;">
                    <div class="row justify-content-center align-items-center flex-wrap">
                        <div class="col-auto text-center mb-2 mb-md-0" style="min-width:48px;">
                            <i class="fas fa-exclamation-circle fa-2x"></i>
                        </div>
                        <div class="col px-2" style="text-align:justify;">
                            <p style="margin-bottom: 0px; font-family: 'Quicksand', sans-serif; font-weight: 600; color: #004a92;">
                                En esta sección puedes revisar y gestionar las justificaciones de inasistencia solicitadas por los representantes. Cada solicitud debe ser evaluada cuidadosamente antes de aprobarla o rechazarla.
                            </p>
                            <p style="font-family: 'Quicksand', sans-serif; font-weight: 600; color: #004a92;">
                                Recuerda que las justificaciones aprobadas generan automáticamente registros de asistencia justificada. Si detectas algún documento sospechoso o información inconsistente, comunícate con el área correspondiente para verificar la autenticidad.
                            </p>
                        </div>
                    </div>
                </div>
                <!-- Collapse: solo la tabla -->
                <div class="collapse show" id="collapseTablaJustificaciones">
                    <div class="card card-body rounded-0 border-0 pt-0 pb-2" style="background: transparent;">
                        @if($justificaciones->count() > 0)
                            <div class="table-responsive">
                                <table id="add-row" class="table-hover table" style="border: 1px solid #0A8CB3; border-radius: 10px; overflow: hidden;">
                                    <thead class="text-center table-hover" style="background-color: #f8f9fa; color: #0A8CB3; border:#0A8CB3 !important">
                                        <tr>
                                            <th scope="col">Fecha Solicitud</th>
                                            <th scope="col">Estudiante</th>
                                            <th scope="col">Fecha Falta</th>
                                            <th scope="col">Motivo</th>
                                            <th scope="col">Documento</th>
                                            <th scope="col">Estado</th>
                                            <th scope="col">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($justificaciones as $justificacion)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($justificacion->fecha)->format('d/m/Y H:i') }}</td>
                                            <td>
                                                {{ $justificacion->matricula->estudiante->nombres }} {{ $justificacion->matricula->estudiante->apellidos }}
                                                <br><small class="text-muted">{{ $justificacion->matricula->estudiante->dni }}</small>
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($justificacion->fecha_falta)->format('d/m/Y') }}</td>
                                            <td>{{ $justificacion->motivo }}</td>
                                            <td>
                                                @if($justificacion->documento_justificacion)
                                                    <a href="{{ asset('storage/justificaciones/' . $justificacion->documento_justificacion) }}"
                                                       target="_blank" class="btn btn-sm btn-info">
                                                        <i class="fas fa-file-pdf"></i> Ver Documento
                                                    </a>
                                                @else
                                                    <span class="text-muted">Sin documento</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $justificacion->getEstadoColorAttribute() }}">
                                                    {{ $justificacion->estado }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($justificacion->estado === 'pendiente')
                                                    <button class="btn btn-success btn-sm" onclick="procesarJustificacion({{ $justificacion->id }}, 'Aprobar')">
                                                        <i class="fas fa-check"></i> Aprobar
                                                    </button>
                                                    <button class="btn btn-danger btn-sm" onclick="procesarJustificacion({{ $justificacion->id }}, 'Rechazar')">
                                                        <i class="fas fa-times"></i> Rechazar
                                                    </button>
                                                @else
                                                    <span class="text-muted">Procesada</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Paginación -->
                            <div class="mt-3">
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
                <script>
                document.addEventListener('DOMContentLoaded', function () {
                    // Collapse icon toggle
                    const btn = document.querySelector('[data-target="#collapseTablaJustificaciones"]');
                    const icon = btn.querySelector('.fas.fa-chevron-down');
                    const collapse = document.getElementById('collapseTablaJustificaciones');
                    collapse.addEventListener('show.bs.collapse', function () {
                        icon.classList.remove('fa-chevron-down');
                        icon.classList.add('fa-chevron-up');
                    });
                    collapse.addEventListener('hide.bs.collapse', function () {
                        icon.classList.remove('fa-chevron-up');
                        icon.classList.add('fa-chevron-down');
                    });
                });
                </script>
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
