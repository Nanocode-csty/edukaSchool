@extends('cplantilla.bprincipal')
@section('titulo','Conceptos de Pago')
@section('contenidoplantilla')
<x-breadcrumb :module="'conceptospago'" :section="'index'" />
<div class="container-fluid" id="contenido-principal">
    <div class="row mt-4 ml-1 mr-1">
        <div class="col-12">
            <div class="box_block">
                <!-- Collapse header -->
                <button class="btn btn-block text-left rounded-0 btn_header header_6" type="button" data-toggle="collapse" data-target="#collapseConceptosPago" aria-expanded="true" aria-controls="collapseConceptosPago" style="background: #28a745 !important; font-weight: bold; color: white;">
                    <i class="fas fa-tags m-1"></i>&nbsp;Conceptos de Pago
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
                                Gestiona los conceptos de pago del sistema educativo. Aquí puedes crear, editar y configurar los diferentes conceptos de cobro.
                            </p>
                        </div>
                    </div>
                </div>
                <!-- Collapse: filtros, estadísticas y lista -->
                <div class="collapse show" id="collapseConceptosPago">
                    <div class="card card-body rounded-0 border-0 pt-0 pb-2" style="background: transparent;">
                        <!-- Acciones Rápidas -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2 flex-wrap">
                                    <a href="{{ route('conceptospago.create') }}" class="btn btn-success btn-sm" title="Crear nuevo concepto de pago">
                                        <i class="fas fa-plus mr-1"></i>Nuevo Concepto
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Filtros y búsqueda -->
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <form method="GET" action="{{ route('conceptospago.index') }}" class="d-flex">
                                    <input type="text" name="buscarpor" class="form-control mr-2"
                                           placeholder="Buscar por nombre o descripción..."
                                           value="{{ $buscarpor }}">
                                    <button type="submit" class="btn btn-outline-primary mr-2">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    @if($buscarpor)
                                        <a href="{{ route('conceptospago.index') }}" class="btn btn-outline-secondary">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    @endif
                                </form>
                            </div>
                        </div>

                        <!-- Lista de conceptos -->
                        @if ($conceptos->count() > 0)
                            <div class="row">
                                <i class="fas fa-tags text-primary mr-2"></i>
                                Conceptos de Pago
                            </h4>
                            <p class="text-muted mb-0">Gestión de conceptos y tarifas de pago</p>
                        </div>
                        <a href="{{ route('conceptospago.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus mr-2"></i>
                            Nuevo Concepto
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Mensajes de notificación -->
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle mr-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Filtros y búsqueda -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <form method="GET" action="{{ route('conceptospago.index') }}" class="d-flex">
                                <input type="text" name="buscarpor" class="form-control mr-2"
                                       placeholder="Buscar por nombre o descripción..."
                                       value="{{ $buscarpor }}">
                                <button type="submit" class="btn btn-outline-primary mr-2">
                                    <i class="fas fa-search"></i>
                                </button>
                                @if($buscarpor)
                                    <a href="{{ route('conceptospago.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times"></i>
                                    </a>
                                @endif
                            </form>
                        </div>
                    </div>

                    <!-- Lista de conceptos -->
                    @if ($conceptos->count() > 0)
                        <div class="row">
                            @foreach ($conceptos as $concepto)
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card concepto-card h-100">
                                        <div class="card-header concepto-header">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h6 class="mb-0 font-weight-bold">{{ $concepto->nombre }}</h6>
                                                <span class="monto-badge">S/ {{ number_format($concepto->monto, 2) }}</span>
                                            </div>
                                        </div>

                                        <div class="card-body">
                                            @if($concepto->descripcion)
                                                <p class="text-muted small mb-2">{{ Str::limit($concepto->descripcion, 80) }}</p>
                                            @endif

                                            <div class="mb-2">
                                                <span class="badge {{ $concepto->recurrente ? 'status-recurrente' : 'status-no-recurrente' }}">
                                                    <i class="fas {{ $concepto->recurrente ? 'fa-sync' : 'fa-calendar' }} mr-1"></i>
                                                    {{ $concepto->recurrente ? 'Recurrente' : 'Único' }}
                                                </span>
                                                @if($concepto->periodo)
                                                    <span class="badge badge-info ml-1">{{ $concepto->periodo }}</span>
                                                @endif
                                            </div>

                                            @if($concepto->anoLectivo)
                                                <p class="text-sm text-muted mb-1">
                                                    <i class="fas fa-calendar-alt mr-1"></i>
                                                    Año: {{ $concepto->anoLectivo->nombre ?? 'N/A' }}
                                                </p>
                                            @endif

                                            @if($concepto->nivel)
                                                <p class="text-sm text-muted mb-0">
                                                    <i class="fas fa-graduation-cap mr-1"></i>
                                                    Nivel: {{ $concepto->nivel->nombre }}
                                                </p>
                                            @endif
                                        </div>

                                        <div class="card-footer bg-white border-0">
                                            <div class="d-flex justify-content-between">
                                                <a href="{{ route('conceptospago.show', $concepto->concepto_id) }}"
                                                   class="btn btn-sm btn-outline-info">
                                                    <i class="fas fa-eye mr-1"></i>
                                                    Ver
                                                </a>
                                                <div>
                                                    <a href="{{ route('conceptospago.edit', $concepto->concepto_id) }}"
                                                       class="btn btn-sm btn-outline-warning mr-1">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form method="POST" action="{{ route('conceptospago.destroy', $concepto->concepto_id) }}"
                                                          style="display: inline-block;"
                                                          onsubmit="return confirm('¿Está seguro de que desea eliminar este concepto?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Paginación -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $conceptos->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No se encontraron conceptos de pago</h5>
                            @if ($buscarpor)
                                <p class="text-muted">No hay resultados para la búsqueda "{{ $buscarpor }}".</p>
                                <a href="{{ route('conceptospago.index') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-times mr-1"></i>
                                    Limpiar búsqueda
                                </a>
                            @else
                                <p class="text-muted">Aún no hay conceptos de pago registrados.</p>
                                <a href="{{ route('conceptospago.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus mr-1"></i>
                                    Crear primer concepto
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
