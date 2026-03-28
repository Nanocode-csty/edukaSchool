@extends('cplantilla.bprincipal')
@section('titulo', 'Detalle del Concepto de Pago')
@section('contenidoplantilla')

<style>
    .detail-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }

    .concepto-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px 15px 0 0;
    }

    .info-row {
        padding: 12px 0;
        border-bottom: 1px solid #f1f3f4;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        font-weight: 600;
        color: #495057;
        min-width: 200px;
    }

    .info-value {
        color: #212529;
    }

    .monto-principal {
        font-size: 2.5rem;
        font-weight: 700;
        color: #28a745;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .badge-recurrente {
        background: linear-gradient(45deg, #28a745, #20c997);
        color: white;
        font-weight: 600;
        padding: 8px 16px;
        border-radius: 20px;
    }

    .badge-unico {
        background: linear-gradient(45deg, #6c757d, #5a6268);
        color: white;
        font-weight: 600;
        padding: 8px 16px;
        border-radius: 20px;
    }
</style>

<div class="container-fluid">
    <div class="row mt-4 ml-1 mr-1">
        <div class="col-md-10 offset-md-1">
            <div class="card detail-card">
                <div class="card-header concepto-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-tag fa-2x mr-3"></i>
                            <div>
                                <h3 class="mb-0 font-weight-bold">{{ $concepto->nombre }}</h3>
                                <p class="mb-0 opacity-85">Concepto de Pago #{{ $concepto->concepto_id }}</p>
                            </div>
                        </div>
                        <div class="monto-principal">
                            S/ {{ number_format($concepto->monto, 2) }}
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- Información Principal -->
                        <div class="col-md-8">
                            <h5 class="mb-3" style="color: #2d3748; border-bottom: 2px solid #e9ecef; padding-bottom: 8px;">
                                <i class="fas fa-info-circle text-info mr-2"></i>
                                Información del Concepto
                            </h5>

                            @if($concepto->descripcion)
                            <div class="info-row">
                                <div class="row">
                                    <div class="col-sm-3 info-label">Descripción:</div>
                                    <div class="col-sm-9 info-value">{{ $concepto->descripcion }}</div>
                                </div>
                            </div>
                            @endif

                            <div class="info-row">
                                <div class="row">
                                    <div class="col-sm-3 info-label">Tipo:</div>
                                    <div class="col-sm-9 info-value">
                                        @if($concepto->recurrente)
                                            <span class="badge-recurrente">
                                                <i class="fas fa-sync mr-2"></i>
                                                Recurrente
                                            </span>
                                        @else
                                            <span class="badge-unico">
                                                <i class="fas fa-calendar-check mr-2"></i>
                                                Pago Único
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if($concepto->periodo)
                            <div class="info-row">
                                <div class="row">
                                    <div class="col-sm-3 info-label">Período:</div>
                                    <div class="col-sm-9 info-value">
                                        <i class="fas fa-calendar-alt text-warning mr-2"></i>
                                        {{ $concepto->periodo }}
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="info-row">
                                <div class="row">
                                    <div class="col-sm-3 info-label">ID del Concepto:</div>
                                    <div class="col-sm-9 info-value">
                                        <code class="bg-light px-2 py-1 rounded">#{{ $concepto->concepto_id }}</code>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Información Relacionada -->
                        <div class="col-md-4">
                            <h5 class="mb-3" style="color: #2d3748; border-bottom: 2px solid #e9ecef; padding-bottom: 8px;">
                                <i class="fas fa-link text-secondary mr-2"></i>
                                Información Relacionada
                            </h5>

                            @if($concepto->anoLectivo)
                            <div class="card border-0 bg-light mb-3">
                                <div class="card-body">
                                    <h6 class="card-title text-primary mb-2">
                                        <i class="fas fa-calendar mr-2"></i>
                                        Año Lectivo
                                    </h6>
                                    <p class="card-text">{{ $concepto->anoLectivo->nombre }}</p>
                                </div>
                            </div>
                            @endif

                            @if($concepto->nivel)
                            <div class="card border-0 bg-light mb-3">
                                <div class="card-body">
                                    <h6 class="card-title text-success mb-2">
                                        <i class="fas fa-graduation-cap mr-2"></i>
                                        Nivel Educativo
                                    </h6>
                                    <p class="card-text">{{ $concepto->nivel->nombre }}</p>
                                </div>
                            </div>
                            @endif

                            <!-- Estadísticas de uso -->
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <h6 class="card-title text-info mb-2">
                                        <i class="fas fa-chart-bar mr-2"></i>
                                        Estadísticas
                                    </h6>
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <div class="h4 mb-1 text-primary">{{ \App\Models\InfPago::where('concepto_id', $concepto->concepto_id)->count() }}</div>
                                            <small class="text-muted">Pagos Realizados</small>
                                        </div>
                                        <div class="col-6">
                                            <div class="h4 mb-1 text-success">
                                                {{ \App\Models\InfPago::where('concepto_id', $concepto->concepto_id)->where('estado', 'Pagado')->count() }}
                                            </div>
                                            <small class="text-muted">Pagos Completados</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="card-footer bg-white border-0">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('conceptospago.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Volver al Listado
                        </a>

                        <div>
                            <a href="{{ route('conceptospago.edit', $concepto->concepto_id) }}" class="btn btn-warning mr-2">
                                <i class="fas fa-edit mr-1"></i>
                                Editar Concepto
                            </a>

                            <form method="POST" action="{{ route('conceptospago.destroy', $concepto->concepto_id) }}"
                                  style="display: inline-block;"
                                  onsubmit="return confirm('¿Está seguro de que desea eliminar este concepto de pago? Esta acción no se puede deshacer.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash mr-1"></i>
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
