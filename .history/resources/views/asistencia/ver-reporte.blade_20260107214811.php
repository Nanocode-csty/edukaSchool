@extends('cplantilla.bprincipal')
@section('titulo','Ver Reporte de Asistencia')
@section('contenidoplantilla')

<x-breadcrumb :module="'asistencia'" :section="'ver-reporte'" />

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="card">
                <div class="card-header" style="background: #17a2b8; color: white;">
                    <h5 class="mb-0">
                        <i class="fas fa-eye mr-2"></i>
                        Detalles del Reporte: {{ $reporte->tipo_reporte_nombre }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-info"><i class="fas fa-info-circle mr-2"></i>Información del Reporte</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>ID:</strong></td>
                                    <td>{{ $reporte->id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tipo:</strong></td>
                                    <td>{{ $reporte->tipo_reporte_nombre }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Formato:</strong></td>
                                    <td>{{ $reporte->formato_nombre }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Período:</strong></td>
                                    <td>{{ $reporte->periodo }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Generado por:</strong></td>
                                    <td>{{ $reporte->generado_por }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Fecha de Generación:</strong></td>
                                    <td>{{ $reporte->fecha_generacion->format('d/m/Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Registros Totales:</strong></td>
                                    <td>{{ number_format($reporte->registros_totales) }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-success"><i class="fas fa-chart-bar mr-2"></i>Estadísticas del Reporte</h6>
                            <div class="row text-center">
                                <div class="col-md-6 col-6 mb-3">
                                    <div class="kpi-card p-3 rounded text-white" style="background: #28a745;">
                                        <i class="fas fa-check fa-2x mb-2"></i>
                                        <h4>{{ $estadisticas['total_presentes'] ?? 0 }}</h4>
                                        <small>Presentes</small>
                                    </div>
                                </div>
                                <div class="col-md-6 col-6 mb-3">
                                    <div class="kpi-card p-3 rounded text-white" style="background: #dc3545;">
                                        <i class="fas fa-times fa-2x mb-2"></i>
                                        <h4>{{ $estadisticas['total_ausentes'] ?? 0 }}</h4>
                                        <small>Ausentes</small>
                                    </div>
                                </div>
                                <div class="col-md-6 col-6 mb-3">
                                    <div class="kpi-card p-3 rounded text-white" style="background: #ffc107;">
                                        <i class="fas fa-clock fa-2x mb-2"></i>
                                        <h4>{{ $estadisticas['total_tardanzas'] ?? 0 }}</h4>
                                        <small>Tardanzas</small>
                                    </div>
                                </div>
                                <div class="col-md-6 col-6 mb-3">
                                    <div class="kpi-card p-3 rounded text-white" style="background: #17a2b8;">
                                        <i class="fas fa-shield-alt fa-2x mb-2"></i>
                                        <h4>{{ $estadisticas['total_justificados'] ?? 0 }}</h4>
                                        <small>Justificados</small>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center mt-3">
                                <div class="alert alert-info">
                                    <strong>Tasa de Asistencia:</strong> {{ $estadisticas['porcentaje_asistencia'] }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtros Aplicados -->
                    @php
                        $filtrosAplicados = is_string($reporte->filtros_aplicados)
                            ? json_decode($reporte->filtros_aplicados, true) ?? []
                            : ($reporte->filtros_aplicados ?? []);
                        $filtrosAplicados = array_filter($filtrosAplicados, function($value, $key) {
                            return $value && $key !== 'fecha_inicio' && $key !== 'fecha_fin';
                        }, ARRAY_FILTER_USE_BOTH);
                    @endphp
                    @if(!empty($filtrosAplicados))
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0"><i class="fas fa-filter mr-2"></i>Filtros Aplicados</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            @foreach($filtrosAplicados as $key => $value)
                                                <div class="col-md-3 col-6 mb-2">
                                                    <span class="badge badge-secondary">{{ ucfirst(str_replace('_', ' ', $key)) }}: {{ $value }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Tabla de Datos -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="fas fa-table mr-2"></i>Datos del Reporte</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th>Fecha</th>
                                                    <th>Estudiante</th>
                                                    <th>Apellidos</th>
                                                    <th>Curso</th>
                                                    <th>Sección</th>
                                                    <th>Tipo Asistencia</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($asistencias as $asistencia)
                                                <tr>
                                                    <td>{{ $asistencia->fecha->format('d/m/Y') }}</td>
                                                    <td>{{ $asistencia->matricula->estudiante->persona->nombres ?? 'N/A' }}</td>
                                                    <td>{{ ($asistencia->matricula->estudiante->persona->apellido_paterno ?? '') . ' ' . ($asistencia->matricula->estudiante->persona->apellido_materno ?? '') }}</td>
                                                    <td>{{ $asistencia->matricula->grado->nombre ?? 'N/A' }}</td>
                                                    <td>{{ $asistencia->matricula->seccion->nombre ?? 'N/A' }}</td>
                                                    <td>
                                                        @php
                                                            $tipoClass = match($asistencia->tipo_asistencia_id) {
                                                                1 => 'badge-danger', // Ausente
                                                                2 => 'badge-success', // Presente
                                                                3 => 'badge-warning', // Tarde
                                                                4 => 'badge-info', // Justificado
                                                                default => 'badge-secondary'
                                                            };
                                                        @endphp
                                                        <span class="badge {{ $tipoClass }}">{{ $asistencia->tipoAsistencia->nombre ?? 'N/A' }}</span>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="6" class="text-center text-muted">
                                                        <i class="fas fa-info-circle mr-2"></i>No hay registros para mostrar
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

                    <!-- Botones de Acción -->
                    <div class="row mt-4">
                        <div class="col-12 text-center">
                            <a href="{{ route('asistencia.reportes') }}" class="btn btn-secondary mr-2">
                                <i class="fas fa-arrow-left mr-2"></i>Volver a Reportes
                            </a>
                            @if($reporte->archivo_path)
                                <a href="{{ $reporte->archivo_url }}" target="_blank" class="btn btn-success mr-2">
                                    <i class="fas fa-download mr-2"></i>Descargar Archivo Original
                                </a>
                            @endif
                            <button onclick="window.print()" class="btn btn-primary">
                                <i class="fas fa-print mr-2"></i>Imprimir Reporte
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
@media print {
    .btn, .card-header, .breadcrumb, .alert {
        display: none !important;
    }
    .card {
        border: none !important;
    }
    .table {
        font-size: 12px;
    }
}
</style>
@endpush    }
