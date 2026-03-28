@extends('cplantilla.bprincipal')

@section('titulo', 'Panel Integrado - Docente')

@section('contenidoplantilla')
<style>
    .dashboard-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        margin-bottom: 20px;
    }

    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .stat-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        margin-bottom: 20px;
    }

    .stat-card.attendance {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    .stat-card.grades {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    .stat-card.classes {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    }

    .tab-content {
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .nav-tabs .nav-link {
        border: none;
        border-radius: 8px 8px 0 0;
        color: #6c757d;
        font-weight: 600;
        padding: 12px 24px;
        margin-right: 5px;
    }

    .nav-tabs .nav-link.active {
        background: linear-gradient(135deg, #0F3E61, #2378ba);
        color: white;
    }

    .class-item {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 10px;
        border-left: 4px solid #007bff;
        transition: all 0.3s ease;
    }

    .class-item:hover {
        background: #e9ecef;
        transform: translateX(5px);
    }

    .class-item.completed {
        border-left-color: #28a745;
        background: linear-gradient(90deg, #f8f9fa 0%, #d4edda 100%);
    }

    .badge-status {
        font-size: 0.75rem;
        padding: 4px 8px;
        border-radius: 12px;
    }

    .quick-action-btn {
        border-radius: 25px;
        padding: 8px 16px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .quick-action-btn:hover {
        transform: scale(1.05);
    }

    .grade-item {
        background: white;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 10px;
        border: 1px solid #dee2e6;
        transition: all 0.3s ease;
    }

    .grade-item:hover {
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }

    .progress-circle {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: white;
        margin: 0 auto;
    }

    .alert-custom {
        border-radius: 10px;
        border: none;
        padding: 15px;
    }

    .alert-info-custom {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .table-responsive {
        border-radius: 8px;
        overflow: hidden;
    }

    .table thead th {
        background: linear-gradient(135deg, #0F3E61, #2378ba);
        color: white;
        border: none;
        font-weight: 600;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Mostrar mensaje de error si existe -->
            @if(isset($error))
                <div class="alert alert-danger alert-custom mb-4">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <h4 class="mb-1"><i class="fas fa-exclamation-triangle"></i> Error de Configuración</h4>
                            <p class="mb-0">{{ $error }}</p>

                            <!-- Mostrar información de debug si existe -->
                            @if(isset($debug))
                                <div class="mt-3">
                                    <h6 class="text-white"><i class="fas fa-bug"></i> Información de Debug:</h6>
                                    <div class="bg-dark text-light p-3 rounded mt-2" style="font-family: monospace; font-size: 0.85em;">
                                        <strong>User ID:</strong> {{ $debug['user_id'] ?? 'N/A' }}<br>
                                        <strong>User Type:</strong> {{ $debug['user_type'] ?? 'N/A' }}<br>
                                        <strong>User Rol:</strong> {{ $debug['user_rol'] ?? 'N/A' }}<br>
                                        <strong>Persona ID:</strong> {{ $debug['persona_id'] ?? 'N/A' }}<br>
                                        <strong>Has Persona:</strong> {{ $debug['has_persona'] ?? 'N/A' }}<br>
                                        <strong>Has Docente Relation:</strong> {{ $debug['has_docente_relation'] ?? 'N/A' }}<br>
                                        <strong>Has Role Docente:</strong> {{ $debug['hasRole_Docente'] ?? 'N/A' }}<br>
                                        <strong>Roles:</strong> {{ implode(', ', $debug['roles'] ?? []) }}<br>
                                    </div>
                                </div>
                            @endif

                            <div class="mt-3">
                                <a href="{{ route('rutarrr1') }}" class="btn btn-primary">
                                    <i class="fas fa-home"></i> Ir al Inicio
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Header -->
            <div class="alert alert-info-custom alert-custom mb-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="mb-1"><i class="fas fa-chalkboard-teacher"></i> Panel Integrado del Docente</h4>
                        <p class="mb-0">Gestiona tus asistencias y calificaciones desde un solo lugar</p>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="progress-circle" style="background: linear-gradient(135deg, #28a745, #20c997);">
                            {{ $estadisticas['completitud_general'] }}%
                        </div>
                        <small class="text-white-50">Completitud General</small>
                    </div>
                </div>
            </div>

            <!-- Estadísticas Rápidas -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="stat-card classes">
                        <h3 class="mb-1">{{ $estadisticas['total_clases_hoy'] }}</h3>
                        <small>Clases Hoy</small>
                        <div class="mt-2">
                            <i class="fas fa-calendar-alt fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card attendance">
                        <h3 class="mb-1">{{ $estadisticas['asistencias_pendientes'] }}</h3>
                        <small>Asistencias Pendientes</small>
                        <div class="mt-2">
                            <i class="fas fa-clipboard-check fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card grades">
                        <h3 class="mb-1">{{ $estadisticas['calificaciones_pendientes'] }}</h3>
                        <small>Calificaciones Pendientes</small>
                        <div class="mt-2">
                            <i class="fas fa-graduation-cap fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <h3 class="mb-1">{{ $estadisticas['total_estudiantes'] }}</h3>
                        <small>Estudiantes Total</small>
                        <div class="mt-2">
                            <i class="fas fa-users fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenido con pestañas -->
            <div class="dashboard-card">
                <ul class="nav nav-tabs" id="dashboardTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="attendance-tab" data-toggle="tab" data-target="#attendance" type="button" role="tab">
                            <i class="fas fa-clipboard-check mr-2"></i>Asistencias
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="grades-tab" data-toggle="tab" data-target="#grades" type="button" role="tab">
                            <i class="fas fa-graduation-cap mr-2"></i>Calificaciones
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="reports-tab" data-toggle="tab" data-target="#reports" type="button" role="tab">
                            <i class="fas fa-chart-bar mr-2"></i>Reportes
                        </button>
                    </li>
                </ul>

                <div class="tab-content p-4" id="dashboardTabsContent">
                    <!-- Pestaña de Asistencias -->
                    <div class="tab-pane fade show active" id="attendance" role="tabpanel">
                        <div class="row">
                            <div class="col-md-8">
                                <h5 class="mb-3"><i class="fas fa-calendar-day text-primary"></i> Clases de Hoy</h5>

                                @forelse($clases_hoy as $clase)
                                <div class="class-item {{ $clase->tiene_asistencia_hoy ? 'completed' : '' }}">
                                    <div class="row align-items-center">
                                        <div class="col-md-4">
                                            <strong>{{ $clase->cursoAsignatura->asignatura->nombre }}</strong>
                                            <br>
                                            <small class="text-muted">
                                                {{ $clase->cursoAsignatura->curso->grado->nombre }} {{ $clase->cursoAsignatura->curso->seccion->nombre }}
                                            </small>
                                        </div>
                                        <div class="col-md-3">
                                            <i class="fas fa-clock text-info"></i> {{ $clase->hora_inicio }} - {{ $clase->hora_fin }}
                                            <br>
                                            <small class="text-muted">
                                                <i class="fas fa-map-marker-alt"></i> {{ $clase->aula->nombre ?? 'Sin asignar' }}
                                            </small>
                                        </div>
                                        <div class="col-md-2">
                                            @if($clase->tiene_asistencia_hoy)
                                                <span class="badge badge-success badge-status">Completada</span>
                                            @else
                                                <span class="badge badge-warning badge-status">Pendiente</span>
                                            @endif
                                        </div>
                                        <div class="col-md-3 text-right">
                                            @if(!$clase->tiene_asistencia_hoy)
                                                <button class="btn btn-primary btn-sm quick-action-btn" onclick="tomarAsistencia({{ $clase->sesion_id }})">
                                                    <i class="fas fa-clipboard-check"></i> Tomar
                                                </button>
                                            @else
                                                <a href="{{ route('asistencia.docente.ver', $clase->sesion_id) }}" class="btn btn-info btn-sm quick-action-btn">
                                                    <i class="fas fa-eye"></i> Ver
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="text-center py-5">
                                    <i class="fas fa-calendar-times text-muted fa-3x mb-3"></i>
                                    <h5 class="text-muted">No tienes clases programadas para hoy</h5>
                                    <p class="text-muted">¡Disfruta tu día libre!</p>
                                </div>
                                @endforelse
                            </div>

                            <div class="col-md-4">
                                <h5 class="mb-3"><i class="fas fa-chart-pie text-success"></i> Resumen de Hoy</h5>

                                <div class="card border-success">
                                    <div class="card-body">
                                        <div class="row text-center">
                                            <div class="col-6">
                                                <h4 class="text-success">{{ $estadisticas['clases_completadas'] }}</h4>
                                                <small>Completadas</small>
                                            </div>
                                            <div class="col-6">
                                                <h4 class="text-warning">{{ $estadisticas['asistencias_pendientes'] }}</h4>
                                                <small>Pendientes</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <h6>Acciones Rápidas</h6>
                                    <button class="btn btn-outline-primary btn-block mb-2" onclick="exportarAsistenciaHoy()">
                                        <i class="fas fa-download"></i> Exportar Asistencias del Día
                                    </button>
                                    <button class="btn btn-outline-info btn-block" onclick="verEstadisticasSemanales()">
                                        <i class="fas fa-chart-line"></i> Ver Estadísticas Semanales
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pestaña de Calificaciones -->
                    <div class="tab-pane fade" id="grades" role="tabpanel">
                        <div class="row">
                            <div class="col-md-8">
                                <h5 class="mb-3"><i class="fas fa-graduation-cap text-warning"></i> Calificaciones Pendientes</h5>

                                @forelse($calificaciones_pendientes as $pendiente)
                                <div class="grade-item">
                                    <div class="row align-items-center">
                                        <div class="col-md-5">
                                            <strong>{{ $pendiente->cursoAsignatura->asignatura->nombre }}</strong>
                                            <br>
                                            <small class="text-muted">
                                                {{ $pendiente->cursoAsignatura->curso->grado->nombre }} {{ $pendiente->cursoAsignatura->curso->seccion->nombre }}
                                            </small>
                                        </div>
                                        <div class="col-md-3">
                                            <span class="badge badge-warning">{{ $pendiente->tipo_evaluacion }}</span>
                                            <br>
                                            <small class="text-muted">{{ $pendiente->fecha_evaluacion ? \Carbon\Carbon::parse($pendiente->fecha_evaluacion)->format('d/m/Y') : 'Sin fecha' }}</small>
                                        </div>
                                        <div class="col-md-2">
                                            <span class="badge badge-info">{{ $pendiente->estudiantes_pendientes }} pendientes</span>
                                        </div>
                                        <div class="col-md-2 text-right">
                                            <button class="btn btn-warning btn-sm quick-action-btn" onclick="gestionarCalificaciones({{ $pendiente->id }})">
                                                <i class="fas fa-edit"></i> Gestionar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="text-center py-5">
                                    <i class="fas fa-check-circle text-success fa-3x mb-3"></i>
                                    <h5 class="text-success">¡Todas las calificaciones están al día!</h5>
                                    <p class="text-muted">No tienes calificaciones pendientes por registrar.</p>
                                </div>
                                @endforelse
                            </div>

                            <div class="col-md-4">
                                <h5 class="mb-3"><i class="fas fa-chart-line text-info"></i> Rendimiento General</h5>

                                <div class="card border-info">
                                    <div class="card-body">
                                        <div class="row text-center">
                                            <div class="col-6">
                                                <h4 class="text-info">{{ $estadisticas['promedio_general'] }}%</h4>
                                                <small>Promedio General</small>
                                            </div>
                                            <div class="col-6">
                                                <h4 class="text-danger">{{ $estadisticas['estudiantes_riesgo'] }}</h4>
                                                <small>En Riesgo</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <h6>Acciones Rápidas</h6>
                                    <button class="btn btn-outline-warning btn-block mb-2" onclick="exportarBoletines()">
                                        <i class="fas fa-file-pdf"></i> Exportar Boletines
                                    </button>
                                    <button class="btn btn-outline-success btn-block" onclick="verAnalisisRendimiento()">
                                        <i class="fas fa-chart-bar"></i> Análisis de Rendimiento
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pestaña de Reportes -->
                    <div class="tab-pane fade" id="reports" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="mb-3"><i class="fas fa-file-alt text-primary"></i> Reportes de Asistencia</h5>

                                <div class="list-group">
                                    <a href="#" class="list-group-item list-group-item-action" onclick="generarReporteAsistencia('semanal')">
                                        <i class="fas fa-calendar-week text-primary mr-3"></i>
                                        <div>
                                            <strong>Reporte Semanal</strong>
                                            <br><small class="text-muted">Asistencias de la semana actual</small>
                                        </div>
                                    </a>
                                    <a href="#" class="list-group-item list-group-item-action" onclick="generarReporteAsistencia('mensual')">
                                        <i class="fas fa-calendar-alt text-success mr-3"></i>
                                        <div>
                                            <strong>Reporte Mensual</strong>
                                            <br><small class="text-muted">Asistencias del mes actual</small>
                                        </div>
                                    </a>
                                    <a href="#" class="list-group-item list-group-item-action" onclick="generarReporteAsistencia('por_curso')">
                                        <i class="fas fa-users text-warning mr-3"></i>
                                        <div>
                                            <strong>Por Curso</strong>
                                            <br><small class="text-muted">Asistencias por curso específico</small>
                                        </div>
                                    </a>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h5 class="mb-3"><i class="fas fa-graduation-cap text-success"></i> Reportes de Calificaciones</h5>

                                <div class="list-group">
                                    <a href="#" class="list-group-item list-group-item-action" onclick="generarReporteCalificaciones('boletin')">
                                        <i class="fas fa-file-pdf text-danger mr-3"></i>
                                        <div>
                                            <strong>Boletines de Calificaciones</strong>
                                            <br><small class="text-muted">Reportes individuales por estudiante</small>
                                        </div>
                                    </a>
                                    <a href="#" class="list-group-item list-group-item-action" onclick="generarReporteCalificaciones('rendimiento')">
                                        <i class="fas fa-chart-line text-info mr-3"></i>
                                        <div>
                                            <strong>Análisis de Rendimiento</strong>
                                            <br><small class="text-muted">Estadísticas de rendimiento por curso</small>
                                        </div>
                                    </a>
                                    <a href="#" class="list-group-item list-group-item-action" onclick="generarReporteCalificaciones('comparativo')">
                                        <i class="fas fa-balance-scale text-secondary mr-3"></i>
                                        <div>
                                            <strong>Reporte Comparativo</strong>
                                            <br><small class="text-muted">Comparación entre períodos</small>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <h5 class="mb-3"><i class="fas fa-history text-muted"></i> Reportes Recientes Generados</h5>

                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Tipo</th>
                                                <th>Descripción</th>
                                                <th>Fecha</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($reportes_recientes as $reporte)
                                            <tr>
                                                <td>
                                                    @if(str_contains($reporte->tipo_reporte, 'asistencia'))
                                                        <span class="badge badge-primary">Asistencia</span>
                                                    @else
                                                        <span class="badge badge-success">Calificación</span>
                                                    @endif
                                                </td>
                                                <td>{{ $reporte->descripcion ?? 'Reporte generado' }}</td>
                                                <td>{{ $reporte->created_at->format('d/m/Y H:i') }}</td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary" onclick="descargarReporte({{ $reporte->id }})">
                                                        <i class="fas fa-download"></i> Descargar
                                                    </button>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">
                                                    <i class="fas fa-info-circle mr-2"></i>No has generado reportes recientemente
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para tomar asistencia -->
<div class="modal fade" id="modalAsistencia" tabindex="-1" role="dialog" aria-labelledby="modalAsistenciaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalAsistenciaLabel">
                    <i class="fas fa-clipboard-check mr-2"></i>Tomar Asistencia
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="modalContent">
                    <!-- Contenido dinámico del modal -->
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Cargando...</span>
                        </div>
                        <p class="mt-2">Cargando estudiantes...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Cancelar
                </button>
                <button type="button" class="btn btn-success" id="btnGuardarAsistencia" onclick="guardarAsistencia()">
                    <i class="fas fa-save"></i> Guardar Asistencia
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
// Variables globales
let estudiantesData = [];

// Funciones de asistencia
function tomarAsistencia(sesionClaseId) {
    // Mostrar loading en el modal
    $('#modalContent').html(`
        <div class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Cargando...</span>
            </div>
            <p class="mt-2">Cargando estudiantes...</p>
        </div>
    `);

    $('#modalAsistencia').modal('show');

    $.ajax({
        url: '{{ route("asistencia.docente.obtener-estudiantes") }}',
        method: 'GET',
        data: { sesion_clase_id: sesionClaseId },
        success: function(response) {
            if (response.success) {
                estudiantesData = response.data.estudiantes;
                renderizarModalAsistencia(sesionClaseId, response.data);
            } else {
                mostrarError(response.message);
            }
        },
        error: function(xhr) {
            mostrarError('Error al cargar los estudiantes');
        }
    });
}

function renderizarModalAsistencia(sesionClaseId, data) {
    let html = `
        <form id="formAsistencia">
            @csrf
            <input type="hidden" id="sesion_clase_id" name="sesion_clase_id" value="${sesionClaseId}">

            <div class="form-group mb-3">
                <label><strong>Fecha de la clase:</strong></label>
                    </select>
                    <input type="hidden" name="asistencias[${index}][matricula_id]" value="${estudiante.matricula_id}">
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm" name="asistencias[${index}][observaciones]"
                           placeholder="Observaciones opcionales">
                </td>
            </tr>
        `;
    });

    $('#tbodyEstudiantes').html(html);
}

function guardarAsistencia() {
    const formData = new FormData(document.getElementById('formAsistencia'));

    $.ajax({
        url: '{{ route("asistencia.docente.guardar") }}',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                $('#modalAsistencia').modal('hide');
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
            mostrarError('Error al guardar la asistencia');
        }
    });
}

function verAsistencia(sesionClaseId) {
    window.location.href = '{{ route("asistencia.docente.ver", ":id") }}'.replace(':id', sesionClaseId);
}

// Funciones de calificaciones
function gestionarCalificaciones(evaluacionId) {
    // Redirigir al módulo de calificaciones
    window.location.href = '{{ route("notas.editar") }}?evaluacion_id=' + evaluacionId;
}

// Funciones de reportes
function exportarAsistenciaHoy() {
    // Implementar exportación de asistencias del día
    Swal.fire({
        title: 'Exportar Asistencias del Día',
        text: '¿Deseas exportar un reporte con todas las asistencias tomadas hoy?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, exportar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Implementar la lógica de exportación
            window.open('{{ route("asistencia.docente.exportar-pdf", "hoy") }}', '_blank');
        }
    });
}

function verEstadisticasSemanales() {
    // Mostrar estadísticas semanales en un modal
    Swal.fire({
        title: 'Estadísticas Semanales',
        html: `
            <div class="text-center">
                <div class="row">
                    <div class="col-6">
                        <h4 style="color: #28a745;">${{{ $estadisticas['asistencias_semana'] ?? 0 }}}</h4>
                        <small>Asistencias</small>
                    </div>
                    <div class="col-6">
                        <h4 style="color: #dc3545;">${{{ $estadisticas['inasistencias_semana'] ?? 0 }}}</h4>
                        <small>Inasistencias</small>
                    </div>
                </div>
                <hr>
                <p class="text-muted">Datos de la semana actual</p>
            </div>
        `,
        confirmButtonText: 'Cerrar'
    });
}

function exportarBoletines() {
    window.location.href = '{{ route("notas.consulta") }}';
}

function verAnalisisRendimiento() {
    // Implementar análisis de rendimiento
    Swal.fire({
        title: 'Análisis de Rendimiento',
        text: 'Funcionalidad en desarrollo. Pronto podrás ver análisis detallados del rendimiento de tus estudiantes.',
        icon: 'info'
    });
}

function generarReporteAsistencia(tipo) {
    let url = '{{ route("asistencia.docente.exportar-pdf", "hoy") }}';

    switch(tipo) {
        case 'semanal':
            url = '{{ route("asistencia.docente.exportar-pdf", "semana") }}';
            break;
        case 'mensual':
            url = '{{ route("asistencia.docente.exportar-pdf", "mes") }}';
            break;
        case 'por_curso':
            // Mostrar selector de curso
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
                    window.open('{{ route("asistencia.docente.exportar-pdf", "curso") }}?curso_id=' + result.value, '_blank');
                }
            });
            return;
    }

    window.open(url, '_blank');
}

function generarReporteCalificaciones(tipo) {
    switch(tipo) {
        case 'boletin':
            window.location.href = '{{ route("notas.consulta") }}';
            break;
        case 'rendimiento':
            window.open('{{ route("notas.consulta") }}?tipo=rendimiento', '_blank');
            break;
        case 'comparativo':
            window.open('{{ route("notas.consulta") }}?tipo=comparativo', '_blank');
            break;
    }
}

function descargarReporte(reporteId) {
    window.open('{{ route("asistencia.descargar-reporte-historial", ":id") }}'.replace(':id', reporteId), '_blank');
}

function mostrarError(mensaje) {
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: mensaje
    });
}

$(document).ready(function() {
    // Inicialización adicional si es necesaria
    console.log('Panel integrado del docente cargado correctamente');
});
</script>
@endsection
