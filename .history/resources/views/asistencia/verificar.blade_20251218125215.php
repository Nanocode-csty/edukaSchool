@extends('cplantilla.bprincipal')

@section('titulo', 'Verificar Justificaciones de Asistencia - Eduka Perú')

@section('contenidoplantilla')
    @php
        $module = 'asistencia';
        $section = 'verificar';
    @endphp
    @include('components.breadcrumb')
    <!-- Welcome Header with Peruvian Context -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #0e4067 0%, #28aece 100%); color: white; border-radius: 15px;">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-2">
                                <i class="fas fa-gavel mr-3"></i>
                                Tribunal de Justificaciones
                            </h2>
                            <p class="mb-0 opacity-90">
                                <i class="fas fa-school mr-2"></i>
                                Sistema Educativo Peruano - Gestión de Inasistencias Escolares
                            </p>
                            <small class="opacity-75">
                                <i class="fas fa-calendar-alt mr-1"></i>
                                {{ now()->locale('es')->isoFormat('dddd, D [de] MMMM [del] YYYY') }}
                            </small>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="stats-highlight">
                                <div class="display-4 font-weight-bold">{{ $estadisticas['pendientes'] }}</div>
                                <small class="text-uppercase">Pendientes de Revisión</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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

    <!-- Justifications List -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-file-alt mr-2"></i>
                        Tribunal de Justificaciones ({{ $justificaciones->total() }})
                    </h5>
                    <div class="d-flex align-items-center">
                        <div class="badge badge-light mr-2">
                            <i class="fas fa-gavel mr-1"></i>
                            Sistema Educativo Peruano
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                                <i class="fas fa-cog"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#" onclick="exportarJustificaciones()">
                                    <i class="fas fa-file-pdf mr-2"></i>Exportar Reporte
                                </a>
                                <a class="dropdown-item" href="#" onclick="enviarNotificaciones()">
                                    <i class="fas fa-bell mr-2"></i>Enviar Recordatorios
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">

                    @if($justificaciones->isEmpty())
                        <div class="text-center py-5">
                            <div class="empty-state">
                                <i class="fas fa-balance-scale fa-4x text-muted mb-4"></i>
                                <h4 class="text-muted mb-2">Tribunal en Receso</h4>
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
                        <div class="list-group list-group-flush">
                            @foreach($justificaciones as $justificacion)
                                <div class="list-group-item px-4 py-4 {{ $justificacion->estado === 'pendiente' ? 'border-left-warning bg-light' : '' }}">
                                    <div class="row align-items-center">
                                        <!-- Student Info -->
                                        <div class="col-lg-4 col-md-6">
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="avatar avatar-lg mr-3">
                                                    <div class="avatar-title rounded-circle bg-primary text-white">
                                                        <i class="fas fa-user-graduate"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 font-weight-bold">
                                                        {{ $justificacion->matricula->estudiante->nombres }}
                                                        {{ $justificacion->matricula->estudiante->apellidos }}
                                                    </h6>
                                                    <small class="text-muted">
                                                        <i class="fas fa-id-card mr-1"></i>
                                                        DNI: {{ $justificacion->matricula->estudiante->persona->dni ?? 'N/A' }}
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="student-details">
                                                <small class="text-muted d-block">
                                                    <i class="fas fa-graduation-cap mr-1"></i>
                                                    {{ $justificacion->matricula->grado->nombre }} - {{ $justificacion->matricula->seccion->nombre }}
                                                </small>
                                                <small class="text-muted d-block">
                                                    <i class="fas fa-user-tie mr-1"></i>
                                                    Representante: {{ $justificacion->usuarioCreador ? $justificacion->usuarioCreador->nombres . ' ' . $justificacion->usuarioCreador->apellidos : 'N/A' }}
                                                </small>
                                            </div>
                                        </div>

                                        <!-- Justification Details -->
                                        <div class="col-lg-5 col-md-6">
                                            <div class="justification-content">
                                                <div class="d-flex align-items-center mb-2">
                                                    <span class="badge badge-{{ $justificacion->estado === 'pendiente' ? 'warning' : ($justificacion->estado === 'aprobado' ? 'success' : 'danger') }} mr-2 px-3 py-1">
                                                        <i class="fas fa-{{ $justificacion->estado === 'pendiente' ? 'clock' : ($justificacion->estado === 'aprobado' ? 'check' : 'times') }} mr-1"></i>
                                                        {{ ucfirst($justificacion->estado) }}
                                                    </span>
                                                    <small class="text-muted">
                                                        <i class="fas fa-calendar mr-1"></i>
                                                        {{ $justificacion->fecha->format('d/m/Y') }}
                                                    </small>
                                                </div>

                                                <div class="mb-2">
                                                    <strong class="text-primary">{{ $justificacion->motivo }}</strong>
                                                </div>

                                                <p class="text-muted mb-2 small">
                                                    {{ Str::limit($justificacion->descripcion, 120) }}
                                                </p>

                                                @if($justificacion->documento_justificacion)
                                                    <a href="{{ asset('storage/justificaciones/' . basename($justificacion->documento_justificacion)) }}"
                                                       target="_blank" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-file-pdf mr-1"></i>Ver Documento Adjunto
                                                    </a>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Actions -->
                                        <div class="col-lg-3 text-lg-right">
                                            @if($justificacion->estado === 'pendiente')
                                                <div class="action-buttons">
                                                    <button type="button" class="btn btn-success btn-sm btn-block mb-2"
                                                            onclick="verificarJustificacion({{ $justificacion->id }}, 'aprobado')">
                                                        <i class="fas fa-check mr-2"></i>Aprobar Justificación
                                                    </button>
                                                    <button type="button" class="btn btn-danger btn-sm btn-block"
                                                            onclick="verificarJustificacion({{ $justificacion->id }}, 'rechazado')">
                                                        <i class="fas fa-times mr-2"></i>Rechazar Justificación
                                                    </button>
                                                </div>
                                            @else
                                                <div class="review-info">
                                                    <small class="text-muted d-block">
                                                        <i class="fas fa-user-check mr-1"></i>
                                                        Revisado por: {{ $justificacion->usuarioRevisor ? $justificacion->usuarioRevisor->nombres : 'Sistema' }}
                                                    </small>
                                                    <small class="text-muted d-block">
                                                        <i class="fas fa-calendar-check mr-1"></i>
                                                        {{ $justificacion->fecha_revision ? $justificacion->fecha_revision->format('d/m/Y H:i') : 'Fecha no disponible' }}
                                                    </small>
                                                    @if($justificacion->observaciones_revision)
                                                        <div class="mt-2 p-2 bg-light rounded">
                                                            <small class="font-weight-bold text-primary">Observaciones:</small><br>
                                                            <small class="text-muted">{{ $justificacion->observaciones_revision }}</small>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="card-footer bg-light">
                            <div class="d-flex justify-content-center">
                                {{ $justificaciones->appends(request()->query())->links() }}
                            </div>
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
