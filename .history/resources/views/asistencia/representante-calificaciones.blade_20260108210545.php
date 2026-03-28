@extends('cplantilla.bprincipal')

@section('titulo', 'Calificaciones de Mis Estudiantes')
{{-- Vista principal para representantes donde pueden ver las calificaciones de sus estudiantes --}}
{{-- Permite navegar fácilmente entre asistencias y calificaciones --}}

@section('contenidoplantilla')
<x-breadcrumb :module="'asistencia'" :section="'representante'" :dashboard="'asistencia.representante.dashboard'" />

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
                            <i class="fas fa-chart-bar"></i> Calificaciones de Mis Estudiantes
                        </h4>
                    </div>

            <div class="card-body">
                <!-- Navegación rápida -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="quick-nav d-flex justify-content-center">
                            <div class="nav nav-pills" role="tablist">
                                <a class="nav-link" href="{{ route('asistencia.representante.index') }}">
                                    <i class="fas fa-calendar-check mr-2"></i>Asistencias
                                </a>
                                <a class="nav-link active" href="{{ route('calificaciones.representante') }}">
                                    <i class="fas fa-chart-bar mr-2"></i>Calificaciones
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estadísticas generales -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="stats-compact d-flex justify-content-between align-items-center p-3 rounded" style="background: #f8f9fa; border: 1px solid #dee2e6;">
                            <div class="stat-item text-center">
                                <div class="stat-value">
                                    <span class="badge badge-success stat-badge">{{ $estudiantesRepresentados->count() }}</span>
                                </div>
                                <small class="text-muted d-block">Mis Estudiantes</small>
                            </div>
                            <div class="stat-item text-center">
                                <div class="stat-value">
                                    <span class="badge badge-primary stat-badge">{{ $estudiantesRepresentados->where('matricula', '!=', null)->count() }}</span>
                                </div>
                                <small class="text-muted d-block">Con Matrícula</small>
                            </div>
                            <div class="stat-item text-center">
                                <div class="stat-value">
                                    <span class="badge badge-warning stat-badge">{{ $estudiantesRepresentados->where('matricula.estado', 'Pre-inscrito')->count() }}</span>
                                </div>
                                <small class="text-muted d-block">Pre-inscritos</small>
                            </div>
                            <div class="stat-item text-center">
                                <div class="stat-value">
                                    <span class="badge badge-info stat-badge">{{ $estudiantesRepresentados->where('matricula.estado', 'Matriculado')->count() }}</span>
                                </div>
                                <small class="text-muted d-block">Matriculados</small>
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
                                <th>Estado Matrícula</th>
                                <th>Última Calificación</th>
                                <th>Promedio General</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyEstudiantes">
                            @forelse($estudiantesRepresentados as $item)
                            <tr>
                                <td>
                                    <div>
                                        <strong>{{ $item['estudiante']->persona ? trim(($item['estudiante']->persona->apellido_paterno ?? '') . ' ' . ($item['estudiante']->persona->apellido_materno ?? '')) : $item['estudiante']->apellidos }} {{ $item['estudiante']->persona ? $item['estudiante']->persona->nombres : $item['estudiante']->nombres }}</strong>
                                        <br><small class="text-muted">{{ $item['estudiante']->persona ? $item['estudiante']->persona->dni : $item['estudiante']->dni }}</small>
                                    </div>
                                </td>
                                <td>
                                    @if($item['curso'])
                                        {{ $item['curso']->grado->nombre }} {{ $item['curso']->grado->nivel->nombre }}
                                        "{{ $item['curso']->seccion->nombre }}"
                                    @else
                                        <span class="text-muted">Sin asignar</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item['matricula'])
                                        <span class="badge badge-{{ $item['matricula']->estado == 'Matriculado' ? 'success' : 'warning' }}">
                                            {{ $item['matricula']->estado }}
                                        </span>
                                    @else
                                        <span class="badge badge-secondary">Sin matrícula</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-muted">N/A</span>
                                </td>
                                <td>
                                    <span class="text-muted">N/A</span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        @if($item['matricula'] && $item['curso'])
                                            <button class="btn btn-info btn-sm" onclick="verCalificaciones({{ $item['estudiante']->estudiante_id }})" title="Ver calificaciones">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-secondary btn-sm" onclick="generarFicha({{ $item['estudiante']->estudiante_id }})" title="Generar ficha">
                                                <i class="fas fa-file-pdf"></i>
                                            </button>
                                        @else
                                            <button class="btn btn-secondary btn-sm" disabled title="No matriculado">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No tiene estudiantes asignados</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js-extra')
<script>
function verCalificaciones(estudianteId) {
    window.location.href = '{{ route("notas.estudiante", ":id") }}'.replace(':id', estudianteId);
}

function generarFicha(estudianteId) {
    window.open('{{ route("estudiantes.ficha", ":id") }}'.replace(':id', estudianteId), '_blank');
}
</script>

<style>
/* Navegación rápida */
.quick-nav .nav-pills .nav-link {
    background: #f8f9fa;
    color: #6c757d;
    border: 2px solid #dee2e6;
    border-radius: 25px;
    padding: 10px 20px;
    margin: 0 5px;
    font-weight: 600;
    transition: all 0.3s ease;
    text-decoration: none;
}

.quick-nav .nav-pills .nav-link:hover {
    background: #e9ecef;
    border-color: #28aece;
    color: #28aece;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(40, 174, 206, 0.2);
}

.quick-nav .nav-pills .nav-link.active {
    background: #ffc107;
    border-color: #ffc107;
    color: white;
    box-shadow: 0 4px 12px rgba(255, 193, 7, 0.4);
}

.quick-nav .nav-pills .nav-link i {
    margin-right: 8px;
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

/* Responsive navigation */
@media (max-width: 768px) {
    .quick-nav .nav-pills {
        flex-direction: column;
        align-items: center;
    }

    .quick-nav .nav-pills .nav-link {
        margin: 5px 0;
        width: 100%;
        max-width: 250px;
}
}
</style>
@endpush