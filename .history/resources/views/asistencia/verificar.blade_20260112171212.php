@extends('cplantilla.bprincipal')
@section('titulo','Gestionar Justificaciones')
@section('contenidoplantilla')
<x-breadcrumb :module="'asistencia'" :section="'verificar'" />
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
                <!-- Collapse: filtros, estadísticas y tabla -->
                <div class="collapse show" id="collapseTablaJustificaciones">
                    <div class="card card-body rounded-0 border-0 pt-0 pb-2" style="background: transparent;">
                        <!-- Estadísticas Compactas -->
                        <div class="row mb-3" id="estadisticasContainer">
                            <div class="col-md-12">
                                <div class="stats-compact d-flex justify-content-between align-items-center p-3 rounded" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border: 1px solid #dee2e6;">
                                    <div class="stat-item text-center">
                                        <div class="stat-value">
                                            <span class="badge badge-info stat-badge">{{ $justificaciones->total() }}</span>
                                        </div>
                                        <small class="text-muted d-block">Total</small>
                                    </div>
                                    <div class="stat-item text-center">
                                        <div class="stat-value">
                                            <span class="badge badge-warning stat-badge">{{ \App\Models\JustificacionAsistencia::where('estado', 'pendiente')->count() }}</span>
                                        </div>
                                        <small class="text-muted d-block">Pendientes</small>
                                    </div>
                                    <div class="stat-item text-center">
                                        <div class="stat-value">
                                            <span class="badge badge-success stat-badge">{{ \App\Models\JustificacionAsistencia::where('estado', 'aprobado')->count() }}</span>
                                        </div>
                                        <small class="text-muted d-block">Aprobadas</small>
                                    </div>
                                    <div class="stat-item text-center">
                                        <div class="stat-value">
                                            <span class="badge badge-danger stat-badge">{{ \App\Models\JustificacionAsistencia::where('estado', 'rechazado')->count() }}</span>
                                        </div>
                                        <small class="text-muted d-block">Rechazadas</small>
                                    </div>
                                </div>
                            </div>
                        </div>

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
                                            <td>{{ \Carbon\Carbon::parse($justificacion->fecha_solicitud)->format('d/m/Y H:i') }}</td>
                                            <td>
                                                {{ $justificacion->matricula->estudiante->nombres }} {{ $justificacion->matricula->estudiante->apellidos }}
                                                <br><small class="text-muted">{{ $justificacion->matricula->estudiante->dni }}</small>
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($justificacion->fecha_falta)->format('d/m/Y') }}</td>
                                            <td>{{ $justificacion->motivo }}</td>
                                            <td>
                                                @if($justificacion->documento_justificacion)
                                                    <a href="{{ asset('storage/' . $justificacion->documento_justificacion) }}"
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
    <style>
        /* Animación de entrada */
        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-50px);}
            to { opacity: 1; transform: translateX(0);}
        }
        .animate-slide-in { animation: slideInLeft 0.8s ease-out; }

        /* Tabla y paginación */
        #add-row td, #add-row th {
            padding: 4px 8px;
            font-size: 14px;
            vertical-align: middle;
            height: 52px;
        }
        .table-hover tbody tr:hover {
            background-color: #FFF4E7 !important;
        }
        .badge-success {
            background-color: #28a745;
            color: #fff;
        }
        .badge-secondary {
            background-color: #6c757d;
            color: #fff;
        }
        /* Paginación */
        .pagination {
            display: flex;
            justify-content: left;
            padding: 1rem 0;
            list-style: none;
            gap: 0.3rem;
        }
        .pagination li a, .pagination li span {
            color: #0A8CB3;
            border: 1px solid #0A8CB3;
            padding: 6px 12px;
            border-radius: 4px;
            text-decoration: none;
            transition: all 0.2s ease;
            font-size: 0.9rem;
        }
        .pagination li a:hover, .pagination li span:hover {
            background-color: #f1f1f1;
            color: #333;
        }
        .pagination .page-item.active .page-link {
            background-color: #0A8CB3 !important;
            color: white !important;
            border-color: #0A8CB3 !important;
        }
        .pagination .disabled .page-link {
            color: #ccc;
            border-color: #ccc;
        }
        /* Botón header estilo estudiantes */
        .btn_header.header_6 {
            margin-bottom: 0;
            border-radius: 0;
            font-size: 1.1rem;
            padding: 0.75rem 1rem;
            background: #0A8CB3 !important;
            color: white;
            border: none;
            box-shadow: none;
        }
        .btn_header .float-right {
            float: right;
        }
        .btn_header i.fas.fa-chevron-down,
        .btn_header i.fas.fa-chevron-up {
            transition: transform 0.2s;
        }

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

        /* Loading Overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .loading-content {
            text-align: center;
            color: white;
        }

        .loading-dots {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
        }

        .dot {
            width: 15px;
            height: 15px;
            border-radius: 50%;
            margin: 0 5px;
            animation: bounce 1.4s ease-in-out infinite both;
        }

        .dot-1 { background-color: #ff6b6b; animation-delay: -0.32s; }
        .dot-2 { background-color: #4ecdc4; animation-delay: -0.16s; }
        .dot-3 { background-color: #45b7d1; animation-delay: 0s; }
        .dot-4 { background-color: #f9ca24; animation-delay: 0.16s; }
        .dot-5 { background-color: #f0932b; animation-delay: 0.32s; }

        @keyframes bounce {
            0%, 80%, 100% {
                transform: scale(0);
            }
            40% {
                transform: scale(1);
            }
        }

        .loading-text {
            font-size: 18px;
            font-weight: bold;
            margin: 0;
        }
    </style>

<!-- Loading Indicator -->
<div class="loading-overlay" id="loadingOverlay" style="display: none;">
    <div class="loading-content">
        <div class="loading-dots">
            <div class="dot dot-1"></div>
            <div class="dot dot-2"></div>
            <div class="dot dot-3"></div>
            <div class="dot dot-4"></div>
            <div class="dot dot-5"></div>
        </div>
        <div class="loading-text">Procesando justificación...</div>
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
function mostrarLoading() {
    $('#loadingOverlay').fadeIn(200);
}

function ocultarLoading() {
    $('#loadingOverlay').fadeOut(200);
}

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
    mostrarLoading();

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
            ocultarLoading();
            if (response.success) {
                $('#modalProcesar').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    // Reload only the table and statistics via AJAX
                    reloadJustificacionesTable();
                });
            } else {
                mostrarError(response.message);
            }
        },
        error: function(xhr) {
            ocultarLoading();
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

function reloadJustificacionesTable() {
    // Show loading indicator
    mostrarLoading();

    // Reload the statistics
    $.ajax({
        url: '{{ route("asistencia.verificar") }}',
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        success: function(response) {
            // Update statistics
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = response;

            // Update the statistics container
            const newStats = tempDiv.querySelector('#estadisticasContainer');
            if (newStats) {
                document.getElementById('estadisticasContainer').innerHTML = newStats.innerHTML;
            }

            // Update the table content
            const newTable = tempDiv.querySelector('.table-responsive');
            const newPagination = tempDiv.querySelector('.mt-3');
            const currentTable = document.querySelector('.table-responsive');
            const currentPagination = document.querySelector('.mt-3');

            if (newTable && currentTable) {
                currentTable.innerHTML = newTable.innerHTML;
            }

            if (newPagination && currentPagination) {
                currentPagination.innerHTML = newPagination.innerHTML;
            }

            // Re-bind click events for the new buttons
            bindTableEvents();

            // Hide loading indicator
            ocultarLoading();
        },
        error: function(xhr) {
            console.error('Error reloading table:', xhr);
            // Hide loading indicator
            ocultarLoading();
            // Fallback to full page reload if AJAX fails
            location.reload();
        }
    });
}

function bindTableEvents() {
    // Re-bind click events for approve/reject buttons
    document.querySelectorAll('button[onclick*="procesarJustificacion"]').forEach(button => {
        const onclickAttr = button.getAttribute('onclick');
        const match = onclickAttr.match(/procesarJustificacion\((\d+),\s*'([^']+)'\)/);
        if (match) {
            const justificacionId = match[1];
            const accion = match[2];
            button.onclick = function() {
                procesarJustificacion(justificacionId, accion);
            };
        }
    });
}

// Bind events on page load
document.addEventListener('DOMContentLoaded', function() {
    bindTableEvents();
});
</script>
@endsection
