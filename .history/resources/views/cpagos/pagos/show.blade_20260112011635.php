@extends('cplantilla.bprincipal')
@section('titulo', 'Detalle del Pago')
@section('contenidoplantilla')

<style>
    .detail-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }

    .status-badge {
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .status-pendiente {
        background-color: #fff3cd;
        color: #856404;
        border: 1px solid #ffeaa7;
    }

    .status-pagado {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .status-vencido {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .status-cancelado {
        background-color: #e2e3e5;
        color: #383d41;
        border: 1px solid #d6d8db;
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
</style>

<div class="container-fluid">
    <div class="row mt-4 ml-1 mr-1">
        <div class="col-md-12">
            <div class="card detail-card">
                <div class="card-header bg-white border-0 pt-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1" style="color: #2d3748; font-weight: 700;">
                                <i class="fas fa-receipt text-primary mr-2"></i>
                                Detalle del Pago #{{ $pago->pago_id }}
                            </h4>
                            <p class="text-muted mb-0">Información completa del registro de pago</p>
                        </div>
                        <div class="text-right">
                            <span class="status-badge status-{{ strtolower($pago->estado) }}">
                                <i class="fas fa-circle mr-1" style="font-size: 0.7rem;"></i>
                                {{ $pago->estado }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Información del Pago -->
                    <div class="row">
                        <div class="col-md-8">
                            <h5 class="mb-3" style="color: #2d3748; border-bottom: 2px solid #e9ecef; padding-bottom: 8px;">
                                <i class="fas fa-info-circle text-info mr-2"></i>
                                Información del Pago
                            </h5>

                            <div class="info-row">
                                <div class="row">
                                    <div class="col-sm-4 info-label">ID del Pago:</div>
                                    <div class="col-sm-8 info-value">#{{ $pago->pago_id }}</div>
                                </div>
                            </div>

                            <div class="info-row">
                                <div class="row">
                                    <div class="col-sm-4 info-label">Código de Transacción:</div>
                                    <div class="col-sm-8 info-value">
                                        @if($pago->codigo_transaccion)
                                            <code class="bg-light px-2 py-1 rounded">{{ $pago->codigo_transaccion }}</code>
                                        @else
                                            <span class="text-muted">No asignado</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="info-row">
                                <div class="row">
                                    <div class="col-sm-4 info-label">Monto:</div>
                                    <div class="col-sm-8 info-value">
                                        <span class="h5 text-success font-weight-bold">
                                            S/ {{ number_format($pago->monto, 2) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="info-row">
                                <div class="row">
                                    <div class="col-sm-4 info-label">Fecha de Vencimiento:</div>
                                    <div class="col-sm-8 info-value">
                                        <i class="fas fa-calendar-alt text-warning mr-2"></i>
                                        {{ \Carbon\Carbon::parse($pago->fecha_vencimiento)->format('d/m/Y') }}
                                        @if($pago->fecha_vencimiento < now() && $pago->estado === 'Pendiente')
                                            <span class="badge badge-danger ml-2">Vencido</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="info-row">
                                <div class="row">
                                    <div class="col-sm-4 info-label">Fecha de Pago:</div>
                                    <div class="col-sm-8 info-value">
                                        @if($pago->fecha_pago)
                                            <i class="fas fa-calendar-check text-success mr-2"></i>
                                            {{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y H:i') }}
                                        @else
                                            <span class="text-muted">Pendiente de pago</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="info-row">
                                <div class="row">
                                    <div class="col-sm-4 info-label">Método de Pago:</div>
                                    <div class="col-sm-8 info-value">
                                        @if($pago->metodo_pago)
                                            <i class="fas fa-credit-card text-primary mr-2"></i>
                                            {{ $pago->metodo_pago }}
                                        @else
                                            <span class="text-muted">No especificado</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="info-row">
                                <div class="row">
                                    <div class="col-sm-4 info-label">Estado:</div>
                                    <div class="col-sm-8 info-value">
                                        <span class="status-badge status-{{ strtolower($pago->estado) }}">
                                            {{ $pago->estado }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            @if($pago->comprobante_url)
                            <div class="info-row">
                                <div class="row">
                                    <div class="col-sm-4 info-label">Comprobante:</div>
                                    <div class="col-sm-8 info-value">
                                        <a href="{{ $pago->comprobante_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-external-link-alt mr-1"></i>
                                            Ver Comprobante
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if($pago->observaciones)
                            <div class="info-row">
                                <div class="row">
                                    <div class="col-sm-4 info-label">Observaciones:</div>
                                    <div class="col-sm-8 info-value">{{ $pago->observaciones }}</div>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Información Relacionada -->
                        <div class="col-md-4">
                            <h5 class="mb-3" style="color: #2d3748; border-bottom: 2px solid #e9ecef; padding-bottom: 8px;">
                                <i class="fas fa-link text-secondary mr-2"></i>
                                Información Relacionada
                            </h5>

                            <!-- Concepto de Pago -->
                            <div class="card border-0 bg-light mb-3">
                                <div class="card-body">
                                    <h6 class="card-title text-primary mb-2">
                                        <i class="fas fa-tag mr-2"></i>
                                        Concepto de Pago
                                    </h6>
                                    <p class="card-text mb-1"><strong>{{ $pago->concepto->nombre }}</strong></p>
                                    @if($pago->concepto->descripcion)
                                        <p class="card-text small text-muted">{{ $pago->concepto->descripcion }}</p>
                                    @endif
                                    <p class="card-text small">
                                        <span class="badge badge-info">{{ $pago->concepto->periodo }}</span>
                                        @if($pago->concepto->recurrente)
                                            <span class="badge badge-success">Recurrente</span>
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <!-- Matrícula -->
                            <div class="card border-0 bg-light mb-3">
                                <div class="card-body">
                                    <h6 class="card-title text-success mb-2">
                                        <i class="fas fa-graduation-cap mr-2"></i>
                                        Matrícula
                                    </h6>
                                    <p class="card-text mb-1">
                                        <strong>Número:</strong> {{ $pago->matricula->numero_matricula }}
                                    </p>
                                    <p class="card-text mb-1">
                                        <strong>Estudiante:</strong> {{ $pago->matricula->estudiante->persona->nombres }} {{ $pago->matricula->estudiante->persona->apellidos }}
                                    </p>
                                    <p class="card-text mb-1">
                                        <strong>DNI:</strong> {{ $pago->matricula->estudiante->persona->dni }}
                                    </p>
                                    <p class="card-text small">
                                        <span class="badge badge-secondary">{{ $pago->matricula->estado }}</span>
                                    </p>
                                </div>
                            </div>

                            <!-- Usuario que registró -->
                            @if($pago->usuarioRegistro)
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <h6 class="card-title text-info mb-2">
                                        <i class="fas fa-user mr-2"></i>
                                        Registrado por
                                    </h6>
                                    <p class="card-text">{{ $pago->usuarioRegistro->persona->nombres ?? 'Usuario del Sistema' }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="card-footer bg-white border-0">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('pagos.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Volver al Listado
                        </a>

                        <div>
                            @if($pago->estado !== 'Pagado')
                                <a href="{{ route('pagos.edit', $pago->pago_id) }}" class="btn btn-warning mr-2">
                                    <i class="fas fa-edit mr-1"></i>
                                    Editar
                                </a>

                                <form method="POST" action="{{ route('pagos.destroy', $pago->pago_id) }}"
                                      style="display: inline-block;"
                                      onsubmit="return confirm('¿Está seguro de que desea eliminar este pago?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash mr-1"></i>
                                        Eliminar
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
