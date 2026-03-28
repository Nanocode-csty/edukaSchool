@extends('cplantilla.bprincipal')

@section('titulo', 'Panel del Estudiante')

@section('contenidoplantilla')
<style>
    .student-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        margin-bottom: 20px;
    }

    .student-card:hover {
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

    .stat-card.today {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    }

    .progress-circle {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: white;
        margin: 0 auto;
        font-size: 18px;
    }

    .attendance-item {
        background: white;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 10px;
        border: 1px solid #dee2e6;
        transition: all 0.3s ease;
    }

    .attendance-item:hover {
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }

    .badge-attendance {
        font-size: 0.75rem;
        padding: 4px 8px;
        border-radius: 12px;
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
            <!-- Header de bienvenida -->
            <div class="alert alert-info-custom alert-custom mb-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="mb-1"><i class="fas fa-user-graduate"></i> ¡Hola, {{ $estudiante->nombres }}!</h4>
                        <p class="mb-0">Bienvenido a tu panel personal. Aquí puedes ver tu asistencia y calificaciones.</p>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="progress-circle" style="background: linear-gradient(135deg, #28a745, #20c997);">
                            {{ $estadisticas['porcentaje_asistencia'] }}%
                        </div>
                        <small class="text-white-50">Asistencia del Mes</small>
                    </div>
                </div>
            </div>

            <!-- Estadísticas principales -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="stat-card attendance">
                        <h3 class="mb-1">{{ $estadisticas['total_asistencias_mes'] }}</h3>
                        <small>Asistencias del Mes</small>
                        <div class="mt-2">
                            <i class="fas fa-calendar-check fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card grades">
                        <h3 class="mb-1">{{ $estadisticas['promedio_calificaciones'] ?: '-' }}</h3>
                        <small>Promedio General</small>
                        <div class="mt-2">
                            <i class="fas fa-graduation-cap fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card today">
                        <h3 class="mb-1">
                            @if($asistenciaHoy)
                                @switch($asistenciaHoy->tipoAsistencia->codigo)
                                    @case('P')
                                        <span class="text-success">Presente</span>
                                        @break
                                    @case('A')
                                        <span class="text-danger">Ausente</span>
                                        @break
                                    @case('T')
                                        <span class="text-warning">Tarde</span>
                                        @break
                                    @case('J')
                                        <span class="text-info">Justificado</span>
                                        @break
                                    @default
                                        <span class="text-muted">-</span>
                                @endswitch
                            @else
                                <span class="text-muted">Sin registrar</span>
                            @endif
                        </h3>
                        <small>Asistencia de Hoy</small>
                        <div class="mt-2">
                            <i class="fas fa-clock fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <h3 class="mb-1">{{ $estadisticas['materias_cursando'] }}</h3>
                        <small>Materias Cursando</small>
                        <div class="mt-2">
                            <i class="fas fa-book fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenido principal -->
            <div class="row">
                <!-- Columna izquierda - Asistencia reciente -->
                <div class="col-md-8">
                    <div class="student-card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-history"></i> Asistencia Reciente</h5>
                        </div>
                        <div class="card-body">
                            @forelse($ultimasAsistencias as $asistencia)
                            <div class="attendance-item">
                                <div class="row align-items-center">
                                    <div class="col-md-3">
                                        <strong>{{ \Carbon\Carbon::parse($asistencia->fecha)->format('d/m/Y') }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            {{ $asistencia->sesionClase->cursoAsignatura->asignatura->nombre ?? 'Asignatura' }}
                                        </small>
                                    </div>
                                    <div class="col-md-3">
                                        @switch($asistencia->tipoAsistencia->codigo)
                                            @case('P')
                                                <span class="badge badge-success badge-attendance">Presente</span>
                                                @break
                                            @case('A')
                                                <span class="badge badge-danger badge-attendance">Ausente</span>
                                                @break
                                            @case('T')
                                                <span class="badge badge-warning badge-attendance">Tarde</span>
                                                @break
                                            @case('J')
                                                <span class="badge badge-info badge-attendance">Justificado</span>
                                                @break
                                            @default
                                                <span class="badge badge-secondary badge-attendance">{{ $asistencia->tipoAsistencia->nombre }}</span>
                                        @endswitch
                                    </div>
                                    <div class="col-md-4">
                                        <small class="text-muted">
                                            {{ $asistencia->sesionClase->hora_inicio ?? '00:00' }} -
                                            {{ $asistencia->sesionClase->hora_fin ?? '00:00' }}
                                        </small>
                                    </div>
                                    <div class="col-md-2 text-right">
                                        <button class="btn btn-sm btn-outline-primary" onclick="verDetalleAsistencia({{ $asistencia->id }})">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-5">
                                <i class="fas fa-info-circle text-muted fa-3x mb-3"></i>
                                <h5 class="text-muted">No hay registros de asistencia</h5>
                                <p class="text-muted">Tu historial de asistencia aparecerá aquí.</p>
                            </div>
                            @endforelse
                        </div>
                        <div class="card-footer text-center">
                            <a href="{{ route('estudiante.asistencia') }}" class="btn btn-primary">
                                <i class="fas fa-calendar-alt"></i> Ver Toda la Asistencia
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Columna derecha - Acciones rápidas y estadísticas -->
                <div class="col-md-4">
                    <!-- Acciones rápidas -->
                    <div class="student-card">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="fas fa-bolt"></i> Acciones Rápidas</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('estudiante.asistencia') }}" class="btn btn-outline-primary btn-block">
                                    <i class="fas fa-calendar-check"></i> Ver Mi Asistencia
                                </a>
                                <a href="{{ route('estudiante.calificaciones') }}" class="btn btn-outline-success btn-block">
                                    <i class="fas fa-graduation-cap"></i> Ver Calificaciones
                                </a>
                                <a href="{{ route('profile.show') }}" class="btn btn-outline-info btn-block">
                                    <i class="fas fa-user"></i> Mi Perfil
                                </a>
                                <button class="btn btn-outline-warning btn-block" onclick="solicitarJustificacion()">
                                    <i class="fas fa-file-medical"></i> Solicitar Justificación
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Resumen del mes -->
                    <div class="student-card">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="fas fa-chart-pie"></i> Resumen del Mes</h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6">
                                    <h4 class="text-success">{{ $estadisticas['presentes_mes'] }}</h4>
                                    <small class="text-muted">Presente</small>
                                </div>
                                <div class="col-6">
                                    <h4 class="text-danger">{{ $estadisticas['ausentes_mes'] }}</h4>
                                    <small class="text-muted">Ausente</small>
                                </div>
                            </div>
                            <hr>
                            <div class="row text-center">
                                <div class="col-6">
                                    <h4 class="text-warning">{{ $estadisticas['tardes_mes'] }}</h4>
                                    <small class="text-muted">Tarde</small>
                                </div>
                                <div class="col-6">
                                    <h4 class="text-info">{{ $estadisticas['justificados_mes'] }}</h4>
                                    <small class="text-muted">Justificado</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
// Función para ver detalle de asistencia
function verDetalleAsistencia(asistenciaId) {
    // Por ahora solo mostramos un mensaje, se puede implementar modal con detalles
    Swal.fire({
        title: 'Detalle de Asistencia',
        text: 'Funcionalidad de detalle próximamente disponible.',
        icon: 'info',
        confirmButtonText: 'Entendido'
    });
}

// Función para solicitar justificación
function solicitarJustificacion() {
    Swal.fire({
        title: 'Solicitar Justificación',
        text: '¿Deseas solicitar una justificación de inasistencia?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, solicitar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Redirigir a la página de solicitud de justificación
            window.location.href = '{{ route("estudiante.asistencia") }}';
        }
    });
}

$(document).ready(function() {
    // Inicialización adicional si es necesaria
    console.log('Panel del estudiante cargado correctamente');
});
</script>
@endsection
