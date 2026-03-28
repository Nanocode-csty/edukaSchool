@extends('cplantilla.bprincipal')
@section('titulo','Detalle del Concepto de Pago')
@section('contenidoplantilla')
<x-breadcrumb :module="'conceptospago'" :section="'show'" />
<div class="container-fluid" id="contenido-principal">
    <div class="row mt-4 ml-1 mr-1">
        <div class="col-12">
            <div class="box_block">
                <!-- Collapse header -->
                <button class="btn btn-block text-left rounded-0 btn_header header_6" type="button" data-toggle="collapse" data-target="#collapseDetalleConcepto" aria-expanded="true" aria-controls="collapseDetalleConcepto" style="background: #28a745 !important; font-weight: bold; color: white;">
                    <i class="fas fa-tag m-1"></i>&nbsp;Detalle del Concepto de Pago
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
                                Visualiza toda la información detallada del concepto de pago seleccionado.
                            </p>
                        </div>
                    </div>
                </div>
                <!-- Collapse: detalles del concepto -->
                <div class="collapse show" id="collapseDetalleConcepto">
                    <div class="card card-body rounded-0 border-0 pt-0 pb-2" style="background: transparent;">
                        <!-- Acciones Rápidas -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2 flex-wrap">
                                    <a href="{{ route('conceptospago.index') }}" class="btn btn-outline-primary btn-sm" title="Ver lista de conceptos de pago">
                                        <i class="fas fa-list mr-1"></i>Lista de Conceptos
                                    </a>
                                    <a href="{{ route('conceptospago.edit', $concepto->concepto_id) }}" class="btn btn-outline-warning btn-sm" title="Editar este concepto">
                                        <i class="fas fa-edit mr-1"></i>Editar Concepto
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Información del Concepto -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="card" style="border: 2px solid #e9ecef; border-radius: 10px;">
                                    <div class="card-header" style="background: #f8f9fa; border-bottom: 1px solid #dee2e6;">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-tag fa-lg mr-3" style="color: #0A8CB3;"></i>
                                                <div>
                                                    <h4 class="mb-0 font-weight-bold" style="color: #0A8CB3;">{{ $concepto->nombre }}</h4>
                                                    <small class="text-muted">Concepto de Pago #{{ $concepto->concepto_id }}</small>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <div class="h2 mb-0 text-success font-weight-bold">S/ {{ number_format($concepto->monto, 2) }}</div>
                                                <small class="text-muted">Monto</small>
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

                                                <div class="row mb-3">
                                                    <div class="col-sm-4">
                                                        <strong class="text-primary">Tipo:</strong>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        @if($concepto->recurrente)
                                                            <span class="badge badge-success">
                                                                <i class="fas fa-sync mr-1"></i>
                                                                Recurrente
                                                            </span>
                                                        @else
                                                            <span class="badge badge-secondary">
                                                                <i class="fas fa-calendar-check mr-1"></i>
                                                                Pago Único
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                @if($concepto->descripcion)
                                                <div class="row mb-3">
                                                    <div class="col-sm-4">
                                                        <strong class="text-primary">Descripción:</strong>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        {{ $concepto->descripcion }}
                                                    </div>
                                                </div>
                                                @endif

                                                @if($concepto->periodo)
                                                <div class="row mb-3">
                                                    <div class="col-sm-4">
                                                        <strong class="text-primary">Período:</strong>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <i class="fas fa-calendar-alt text-warning mr-2"></i>
                                                        {{ $concepto->periodo }}
                                                    </div>
                                                </div>
                                                @endif

                                                <div class="row mb-3">
                                                    <div class="col-sm-4">
                                                        <strong class="text-primary">ID del Concepto:</strong>
                                                    </div>
                                                    <div class="col-sm-8">
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
