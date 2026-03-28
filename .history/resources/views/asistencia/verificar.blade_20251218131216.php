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

                                <!-- Estadísticas Compactas -->
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="stats-compact">
                                            <div class="stats-item">
                                                <span class="stats-number text-warning">{{ number_format($estadisticas['pendientes']) }}</span>
                                                <span class="stats-label">Pendientes</span>
                                            </div>
                                            <div class="stats-item">
                                                <span class="stats-number text-success">{{ number_format($estadisticas['aprobadas']) }}</span>
                                                <span class="stats-label">Aprobadas</span>
                                            </div>
                                            <div class="stats-item">
                                                <span class="stats-number text-danger">{{ number_format($estadisticas['rechazadas']) }}</span>
                                                <span class="stats-label">Rechazadas</span>
                                            </div>
                                            <div class="stats-item">
                                                <span class="stats-number text-info">{{ number_format($estadisticas['total']) }}</span>
                                                <span class="stats-label">Total</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Barra de Filtros Mejorada -->
                                <div class="filter-toolbar mb-4">
                                    <div class="card shadow-sm" style="border: 1px solid #e1e5e9; border-radius: 12px;">
                                        <div class="card-body p-4">
                                            <!-- Filtros Principales -->
                                            <div class="row g-4 mb-4">
                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label class="form-label fw-bold text-primary mb-2" style="font-size: 0.85rem;">
                                                            <i class="fas fa-tasks me-2"></i>ESTADO
                                                        </label>
                                                        <select name="estado" id="estado" class="form-control"
                                                                style="border-radius: 8px; border: 2px solid #e3f2fd;">
                                                            <option value="">Todos los estados</option>
                                                            <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>⏳ Pendientes</option>
                                                            <option value="aprobado" {{ request('estado') == 'aprobado' ? 'selected' : '' }}>✅ Aprobadas</option>
                                                            <option value="rechazado" {{ request('estado') == 'rechazado' ? 'selected' : '' }}>❌ Rechazadas</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label class="form-label fw-bold text-primary mb-2" style="font-size: 0.85rem;">
                                                            <i class="fas fa-calendar-plus me-2"></i>DESDE
                                                        </label>
                                                        <input type="date" name="fecha_desde" id="fecha_desde" class="form-control"
                                                               value="{{ request('fecha_desde') }}" max="{{ date('Y-m-d') }}"
                                                               style="border-radius: 8px; border: 2px solid #e3f2fd;">
                                                    </div>
                                                </div>

                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label class="form-label fw-bold text-primary mb-2" style="font-size: 0.85rem;">
                                                            <i class="fas fa-calendar-minus me-2"></i>HASTA
                                                        </label>
                                                        <input type="date" name="fecha_hasta" id="fecha_hasta" class="form-control"
                                                               value="{{ request('fecha_hasta') }}" max="{{ date('Y-m-d') }}"
                                                               style="border-radius: 8px; border: 2px solid #e3f2fd;">
                                                    </div>
                                                </div>

                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label class="form-label fw-bold text-primary mb-2" style="font-size: 0.85rem;">
                                                            <i class="fas fa-user-graduate me-2"></i>ESTUDIANTE
                                                        </label>
                                                        <select name="estudiante_id" id="estudiante_id" class="form-control"
                                                                style="border-radius: 8px; border: 2px solid #e3f2fd;">
                                                            <option value="">Todos los estudiantes</option>
                                                            @foreach($estudiantes ?? [] as $estudiante)
                                                                <option value="{{ $estudiante->estudiante_id }}" {{ ($filtros['estudiante_id'] ?? '') == $estudiante->estudiante_id ? 'selected' : '' }}>
                                                                    {{ $estudiante->persona->nombres ?? 'N/A' }} {{ $estudiante->persona->apellidos ?? 'N/A' }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Botones de Acción -->
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="d-flex justify-content-center gap-3 flex-wrap">
                                                        <button type="button" class="btn btn-outline-secondary btn-lg px-4" data-toggle="collapse" data-target="#advancedFiltersVerificar">
                                                            <i class="fas fa-filter me-2"></i>Filtros Avanzados
                                                        </button>
                                                        <button type="button" class="btn btn-primary btn-lg px-5" onclick="aplicarFiltrosVerificar()">
                                                            <i class="fas fa-search me-2"></i>Buscar Registros
                                                        </button>
                                                        <a href="{{ route('asistencia.verificar') }}" class="btn btn-outline-danger btn-lg px-4">
                                                            <i class="fas fa-times me-2"></i>Limpiar Filtros
                                                        </a>
                                                        <button type="button" class="btn btn-outline-success btn-lg px-4" onclick="exportarJustificaciones()">
                                                            <i class="fas fa-file-pdf me-2"></i>Exportar PDF
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Filtros Avanzados Colapsables -->
                                    <div class="collapse mt-3" id="advancedFiltersVerificar">
                                        <form method="GET" action="{{ route('asistencia.verificar') }}" id="advancedFiltersFormVerificar" class="row g-3">
                                            <!-- Filtros Académicos -->
                                            <div class="col-12">
                                                <div class="filter-group-card">
                                                    <div class="filter-group-header">
                                                        <i class="fas fa-graduation-cap"></i>
                                                        <span>Filtros Académicos</span>
                                                    </div>
                                                    <div class="filter-group-body">
                                                        <div class="row g-3">
                                                            <div class="col-md-6">
                                                                <label for="grado_id" class="form-label estilo-info">
                                                                    <i class="fas fa-graduation-cap mr-1"></i>Grado
                                                                </label>
                                                                <select name="grado_id" id="grado_id" class="form-control select-search" style="width: 100% !important;">
                                                                    <option value="">Todos los grados</option>
                                                                    @foreach(\App\Models\InfGrado::all() as $grado)
                                                                        <option value="{{ $grado->grado_id }}" {{ ($filtros['grado_id'] ?? '') == $grado->grado_id ? 'selected' : '' }}>
                                                                            {{ $grado->nombre }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="seccion_id" class="form-label estilo-info">
                                                                    <i class="fas fa-users mr-1"></i>Sección
                                                                </label>
                                                                <select name="seccion_id" id="seccion_id" class="form-control select-search" style="width: 100% !important;">
                                                                    <option value="">Todas las secciones</option>
                                                                    @foreach(\App\Models\InfSeccion::all() as $seccion)
                                                                        <option value="{{ $seccion->seccion_id }}" {{ ($filtros['seccion_id'] ?? '') == $seccion->seccion_id ? 'selected' : '' }}>
                                                                            {{ $seccion->nombre }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Acciones Avanzadas -->
                                            <div class="col-12">
                                                <div class="d-flex justify-content-end gap-2">
                                                    <button type="button" class="btn btn-primary btn-sm" onclick="aplicarFiltrosVerificar()">
                                                        <i class="fas fa-search me-1"></i>Aplicar Filtros Avanzados
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
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

        /* New Dashboard Styles */
        .metric-item {
            position: relative;
            padding: 1rem 0;
        }

        .metric-number {
            font-size: 2.5rem;
            line-height: 1;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .metric-label {
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            opacity: 0.9;
        }

        .metric-icon {
            position: absolute;
            top: 10px;
            right: 10px;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #0A8CB3 0%, #28aece 100%);
        }

        .filter-form .form-label {
            font-size: 0.75rem;
            margin-bottom: 0.25rem;
        }

        .filter-form .form-control {
            border-radius: 6px;
            border: 2px solid transparent;
            transition: all 0.2s ease;
        }

        .filter-form .form-control:focus {
            border-color: #0A8CB3;
            box-shadow: 0 0 0 0.2rem rgba(10, 140, 179, 0.25);
        }

        .filter-form .btn {
            border-radius: 6px;
            font-weight: 600;
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

            .metric-number {
                font-size: 2rem;
            }

            .metric-number.h3 {
                font-size: 1.5rem;
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
