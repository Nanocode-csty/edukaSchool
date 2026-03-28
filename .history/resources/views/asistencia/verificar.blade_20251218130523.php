@extends('cplantilla.bprincipal')

@section('titulo', 'Revisión de Justificaciones de Asistencia - Eduka Perú')

@section('contenidoplantilla')
    @php
        $module = 'asistencia';
        $section = 'verificar';
    @endphp
    @include('components.breadcrumb')
    <div class="container-fluid estilo-info margen-movil-2">
        <div class="row mt-4 ml-1 mr-1">
            <div class="col-12 mb-3">
                <div class="box_block">
                    <button style="background: #0A8CB3 !important; border:none"
                        class="btn btn-primary btn-block text-left rounded-0 btn_header header_6 estilo-info" type="button"
                        data-toggle="collapse" data-target="#collapseVerificar" aria-expanded="true"
                        aria-controls="collapseVerificar">
                        <i class="fas fa-clipboard-check"></i>&nbsp;Revisión de Justificaciones de Asistencia
                        <div class="float-right"><i class="fas fa-chevron-down"></i></div>
                    </button>
                </div>
                <div class="collapse show" id="collapseVerificar">
                    <div class="card card-body rounded-0 border-0 pt-0"
                        style="padding-left:0.966666666rem;padding-right:0.9033333333333333rem;">
                        <div class="row margen-movil" style="padding:20px;">
                            <div class="col-12">

    <!-- Quick Stats Dashboard -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card h-100 border-left-warning shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <div class="text-warning mb-1">
                                <i class="fas fa-clock fa-2x"></i>
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-warning">{{ $estadisticas['pendientes'] }}</div>
                            <div class="text-xs text-muted">PENDIENTES</div>
                        </div>
                        <div class="col-4 text-right">
                            <div class="progress-circle" data-value="{{ $estadisticas['total'] > 0 ? round(($estadisticas['pendientes'] / $estadisticas['total']) * 100) : 0 }}">
                                <span class="progress-text">{{ $estadisticas['total'] > 0 ? round(($estadisticas['pendientes'] / $estadisticas['total']) * 100) : 0 }}%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card h-100 border-left-success shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <div class="text-success mb-1">
                                <i class="fas fa-check-circle fa-2x"></i>
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-success">{{ $estadisticas['aprobadas'] }}</div>
                            <div class="text-xs text-muted">APROBADAS</div>
                        </div>
                        <div class="col-4 text-right">
                            <div class="progress-circle success" data-value="{{ $estadisticas['total'] > 0 ? round(($estadisticas['aprobadas'] / $estadisticas['total']) * 100) : 0 }}">
                                <span class="progress-text">{{ $estadisticas['total'] > 0 ? round(($estadisticas['aprobadas'] / $estadisticas['total']) * 100) : 0 }}%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card h-100 border-left-danger shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <div class="text-danger mb-1">
                                <i class="fas fa-times-circle fa-2x"></i>
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-danger">{{ $estadisticas['rechazadas'] }}</div>
                            <div class="text-xs text-muted">RECHAZADAS</div>
                        </div>
                        <div class="col-4 text-right">
                            <div class="progress-circle danger" data-value="{{ $estadisticas['total'] > 0 ? round(($estadisticas['rechazadas'] / $estadisticas['total']) * 100) : 0 }}">
                                <span class="progress-text">{{ $estadisticas['total'] > 0 ? round(($estadisticas['rechazadas'] / $estadisticas['total']) * 100) : 0 }}%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card h-100 border-left-info shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <div class="text-info mb-1">
                                <i class="fas fa-chart-pie fa-2x"></i>
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-info">{{ $estadisticas['total'] }}</div>
                            <div class="text-xs text-muted">TOTAL</div>
                        </div>
                        <div class="col-4 text-right">
                            <div class="text-info">
                                <i class="fas fa-chart-line fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Filters Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-filter text-primary mr-2"></i>
                        Filtros Avanzados
                    </h5>
                    <button class="btn btn-sm btn-outline-secondary" type="button" data-toggle="collapse" data-target="#filtersCollapse">
                        <i class="fas fa-chevron-down"></i>
                    </button>
                </div>
                <div class="collapse show" id="filtersCollapse">
                    <div class="card-body">
                        <form method="GET" action="{{ route('asistencia.verificar') }}" class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-tasks text-warning mr-1"></i>Estado
                                </label>
                                <select name="estado" class="form-control form-control-lg">
                                    <option value="">📋 Todas las justificaciones</option>
                                    <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>⏳ Pendientes</option>
                                    <option value="aprobado" {{ request('estado') == 'aprobado' ? 'selected' : '' }}>✅ Aprobadas</option>
                                    <option value="rechazado" {{ request('estado') == 'rechazado' ? 'selected' : '' }}>❌ Rechazadas</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-calendar-plus text-success mr-1"></i>Fecha Desde
                                </label>
                                <input type="date" name="fecha_desde" class="form-control form-control-lg"
                                       value="{{ request('fecha_desde') }}" max="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-calendar-minus text-danger mr-1"></i>Fecha Hasta
                                </label>
                                <input type="date" name="fecha_hasta" class="form-control form-control-lg"
                                       value="{{ request('fecha_hasta') }}" max="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <div class="btn-group w-100">
                                    <button type="submit" class="btn btn-primary btn-lg flex-fill">
                                        <i class="fas fa-search mr-2"></i>Buscar
                                    </button>
                                    <a href="{{ route('asistencia.verificar') }}" class="btn btn-outline-secondary btn-lg">
                                        <i class="fas fa-undo mr-2"></i>Limpiar
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Processing Summary & Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-bolt mr-2"></i>
                        Acciones Rápidas del Tribunal
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="quick-action-icon bg-success text-white mr-3">
                                    <i class="fas fa-check-double fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Aprobar Todas las Pendientes</h6>
                                    <small class="text-muted">Aprueba automáticamente todas las justificaciones pendientes de hoy</small>
                                </div>
                            </div>
                            <button type="button" class="btn btn-success btn-sm" onclick="aprobarTodasPendientes()">
                                <i class="fas fa-check-double mr-1"></i>Aprobar Todas
                            </button>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="quick-action-icon bg-warning text-white mr-3">
                                    <i class="fas fa-clock fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Marcar como Urgentes</h6>
                                    <small class="text-muted">Prioriza justificaciones que requieren atención inmediata</small>
                                </div>
                            </div>
                            <button type="button" class="btn btn-warning btn-sm" onclick="marcarUrgentes()">
                                <i class="fas fa-exclamation-triangle mr-1"></i>Marcar Urgentes
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Statistics Chart -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-line text-primary mr-2"></i>
                        Tendencia Mensual de Justificaciones
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="h4 text-primary mb-1">{{ $estadisticas['total'] }}</div>
                                <small class="text-muted">Este Mes</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="h4 text-success mb-1">{{ $estadisticas['aprobadas'] }}</div>
                                <small class="text-muted">Aprobadas</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="h4 text-danger mb-1">{{ $estadisticas['rechazadas'] }}</div>
                                <small class="text-muted">Rechazadas</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="h4 text-warning mb-1">{{ $estadisticas['pendientes'] }}</div>
                                <small class="text-muted">Pendientes</small>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="text-center">
                        <small class="text-muted">
                            <i class="fas fa-info-circle mr-1"></i>
                            Estadísticas actualizadas en tiempo real
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Justifications List - Table Format -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header"
                     style="background: #E0F7FA; color: #0A8CB3; font-weight: bold; border: 2px solid #86D2E3; border-bottom: 2px solid #86D2E3; padding: 6px 20px; border-radius:4px 4px 0px 0px;">
                    <i class="fas fa-clipboard-check mr-2"></i>
                    Revisión de Justificaciones ({{ $justificaciones->total() }})
                </div>
                <div class="card-body"
                     style="border: 2px solid #86D2E3; border-top: none; border-radius: 0px 0px 4px 4px !important; padding: 0;">

                    @if($justificaciones->isEmpty())
                        <div class="text-center py-5">
                            <div class="empty-state">
                                <i class="fas fa-clipboard-list fa-4x text-muted mb-4"></i>
                                <h4 class="text-muted mb-2">Sin Justificaciones Pendientes</h4>
                                <p class="text-muted mb-4">No hay justificaciones pendientes de revisión en este momento.</p>
                                <div class="text-center">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Las justificaciones aparecerán aquí cuando los representantes las envíen.
                                    </small>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover table-striped mb-0">
                                <thead class="table-light" style="position: sticky; top: 0; z-index: 10;">
                                    <tr>
                                        <th style="width: 50px;">#</th>
                                        <th>Estudiante</th>
                                        <th>Motivo</th>
                                        <th>Fecha</th>
                                        <th>Estado</th>
                                        <th>Documento</th>
                                        <th style="width: 150px;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($justificaciones as $index => $justificacion)
                                        <tr class="{{ $justificacion->estado === 'pendiente' ? 'table-warning' : '' }}">
                                            <td class="align-middle">{{ $justificaciones->firstItem() + $index }}</td>

                                            <td class="align-middle">
                                                <div>
                                                    <strong>{{ $justificacion->matricula->estudiante->nombres }} {{ $justificacion->matricula->estudiante->apellidos }}</strong><br>
                                                    <small class="text-muted">
                                                        <i class="fas fa-graduation-cap mr-1"></i>
                                                        {{ $justificacion->matricula->grado->nombre }} - {{ $justificacion->matricula->seccion->nombre }}
                                                    </small><br>
                                                    <small class="text-muted">
                                                        <i class="fas fa-user-tie mr-1"></i>
                                                        {{ $justificacion->usuarioCreador ? $justificacion->usuarioCreador->nombres . ' ' . $justificacion->usuarioCreador->apellidos : 'N/A' }}
                                                    </small>
                                                </div>
                                            </td>

                                            <td class="align-middle">
                                                <div>
                                                    <strong class="text-primary">{{ $justificacion->motivo }}</strong><br>
                                                    <small class="text-muted">{{ Str::limit($justificacion->descripcion, 80) }}</small>
                                                </div>
                                            </td>

                                            <td class="align-middle">
                                                <div>
                                                    <strong>{{ $justificacion->fecha->format('d/m/Y') }}</strong><br>
                                                    <small class="text-muted">{{ $justificacion->fecha->format('l') }}</small>
                                                </div>
                                            </td>

                                            <td class="align-middle">
                                                <span class="status-badge {{ $justificacion->estado == 'pendiente' ? 'pendiente' : ($justificacion->estado == 'aprobado' ? 'aprobadas' : 'rechazadas') }}">
                                                    <i class="fas fa-{{ $justificacion->estado == 'pendiente' ? 'clock' : ($justificacion->estado == 'aprobado' ? 'check' : 'times') }} mr-1"></i>
                                                    {{ ucfirst($justificacion->estado) }}
                                                </span>
                                            </td>

                                            <td class="align-middle">
                                                @if($justificacion->documento_justificacion)
                                                    <a href="{{ asset('storage/justificaciones/' . basename($justificacion->documento_justificacion)) }}"
                                                       target="_blank" class="btn btn-sm btn-outline-primary" title="Ver documento">
                                                        <i class="fas fa-file-pdf"></i>
                                                    </a>
                                                @else
                                                    <span class="text-muted small">Sin documento</span>
                                                @endif
                                            </td>

                                            <td class="align-middle">
                                                @if($justificacion->estado === 'pendiente')
                                                    <div class="btn-group-vertical btn-group-sm" role="group">
                                                        <button type="button" class="btn btn-success mb-1"
                                                                onclick="verificarJustificacion({{ $justificacion->id }}, 'aprobado')">
                                                            <i class="fas fa-check mr-1"></i>Aprobar
                                                        </button>
                                                        <button type="button" class="btn btn-danger"
                                                                onclick="verificarJustificacion({{ $justificacion->id }}, 'rechazado')">
                                                            <i class="fas fa-times mr-1"></i>Rechazar
                                                        </button>
                                                    </div>
                                                @else
                                                    <div class="text-center">
                                                        <small class="text-muted">
                                                            <i class="fas fa-user-check mr-1"></i>
                                                            {{ $justificacion->usuarioRevisor ? $justificacion->usuarioRevisor->nombres : 'Sistema' }}
                                                        </small>
                                                        @if($justificacion->observaciones_revision)
                                                            <br><small class="text-primary" title="{{ $justificacion->observaciones_revision }}">
                                                                <i class="fas fa-comment mr-1"></i>Observaciones
                                                            </small>
                                                        @endif
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-3 p-3">
                            {{ $justificaciones->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        .estilo-info {
            font-family: "Quicksand", sans-serif !important;
        }

        .stats-highlight {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            padding: 20px;
            backdrop-filter: blur(10px);
        }

        .border-left-warning {
            border-left: 4px solid #ffc107 !important;
        }

        .border-left-success {
            border-left: 4px solid #28a745 !important;
        }

        .border-left-danger {
            border-left: 4px solid #dc3545 !important;
        }

        .border-left-info {
            border-left: 4px solid #17a2b8 !important;
        }

        .progress-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: conic-gradient(#ffc107 0% var(--progress), #e9ecef var(--progress) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .progress-circle.success {
            background: conic-gradient(#28a745 0% var(--progress), #e9ecef var(--progress) 100%);
        }

        .progress-circle.danger {
            background: conic-gradient(#dc3545 0% var(--progress), #e9ecef var(--progress) 100%);
        }

        .progress-circle::before {
            content: '';
            width: 40px;
            height: 40px;
            background: white;
            border-radius: 50%;
            position: absolute;
        }

        .progress-text {
            position: relative;
            z-index: 1;
            font-size: 0.7rem;
            font-weight: bold;
            color: #495057;
        }

        .justification-content {
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
            border-left: 3px solid #0e4067;
        }

        .student-details {
            margin-left: 60px;
        }

        .action-buttons .btn {
            border-radius: 25px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }

        .action-buttons .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        .empty-state {
            padding: 60px 20px;
        }

        .review-info {
            background: #e8f5e8;
            padding: 15px;
            border-radius: 10px;
            border: 1px solid #c3e6c3;
        }

        .quick-action-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        @media (max-width: 768px) {
            .student-details {
                margin-left: 0;
                margin-top: 10px;
            }

            .stats-highlight {
                margin-top: 20px;
            }

            .progress-circle {
                width: 40px;
                height: 40px;
            }

            .progress-circle::before {
                width: 30px;
                height: 30px;
            }

            .progress-text {
                font-size: 0.6rem;
            }
        }
    </style>

    <script>
        // Set progress circle values
        document.addEventListener('DOMContentLoaded', function() {
            const circles = document.querySelectorAll('.progress-circle');
            circles.forEach(circle => {
                const value = circle.dataset.value;
                circle.style.setProperty('--progress', value + '%');
            });
        });

        // Quick Actions Functions
        function aprobarTodasPendientes() {
            alert('Función "Aprobar Todas las Pendientes" próximamente disponible. Esta función permitirá procesar múltiples justificaciones de forma masiva para mayor eficiencia administrativa.');
        }

        function marcarUrgentes() {
            alert('Función "Marcar como Urgentes" próximamente disponible. Esta función permitirá priorizar justificaciones que requieren atención inmediata.');
        }

        function exportarJustificaciones() {
            alert('Función "Exportar Reporte" próximamente disponible. Podrá descargar un reporte PDF con todas las justificaciones filtradas para archivar o compartir.');
        }

        function enviarNotificaciones() {
            alert('Función "Enviar Recordatorios" próximamente disponible. Se enviarán notificaciones automáticas a representantes con justificaciones pendientes.');
        }
    </script>

    <!-- Modal para observaciones -->
    <div class="modal fade" id="observacionesModal" tabindex="-1" role="dialog" aria-labelledby="observacionesModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="observacionesModalLabel">Agregar Observaciones</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="verificarForm" method="POST">
                    @csrf
                    <input type="hidden" name="justificacion_id" id="justificacion_id">
                    <input type="hidden" name="accion" id="accion">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="observaciones">Observaciones (opcional)</label>
                            <textarea name="observaciones" id="observaciones" class="form-control" rows="3"
                                      placeholder="Agregue observaciones sobre la decisión..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" id="confirmarBtn">Confirmar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .form-control {
            border: 1px solid #DAA520;
        }
    </style>
@endsection

@section('scripts')
    <script>
        function verificarJustificacion(justificacionId, accion) {
            document.getElementById('justificacion_id').value = justificacionId;
            document.getElementById('accion').value = accion;
            document.getElementById('observaciones').value = '';

            const titulo = accion === 'aprobado' ? 'Aprobar Justificación' : 'Rechazar Justificación';
            const btnText = accion === 'aprobado' ? 'Aprobar' : 'Rechazar';
            const btnClass = accion === 'aprobado' ? 'btn-success' : 'btn-danger';

            document.getElementById('observacionesModalLabel').textContent = titulo;
            document.getElementById('confirmarBtn').textContent = btnText;
            document.getElementById('confirmarBtn').className = `btn ${btnClass}`;

            $('#observacionesModal').modal('show');
        }

        document.getElementById('verificarForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const justificacionId = formData.get('justificacion_id');
            const accion = formData.get('accion');
            const observaciones = formData.get('observaciones');

            // Enviar la solicitud AJAX
            fetch(`{{ route('asistencia.procesar-verificacion') }}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    justificacion_id: justificacionId,
                    accion: accion,
                    observaciones: observaciones
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    $('#observacionesModal').modal('hide');
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Ocurrió un error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Ocurrió un error al procesar la solicitud');
            });
        });
    </script>
@endsection
