@extends('cplantilla.bprincipal')
@section('titulo', 'Conceptos de Pago')
@section('contenidoplantilla')

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

    .concepto-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .concepto-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 20px rgba(0,0,0,0.12);
    }

    .concepto-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 12px 12px 0 0;
    }

    .monto-badge {
        background: rgba(255,255,255,0.2);
        color: white;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 20px;
    }

    .status-recurrente {
        background-color: #28a745;
        color: white;
    }

    .status-no-recurrente {
        background-color: #6c757d;
        color: white;
    }
</style>

<div class="container-fluid margen-movil-2">
    <div class="row mt-4 ml-1 mr-1">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-white border-0 pt-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1" style="color: #2d3748; font-weight: 700;">
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
