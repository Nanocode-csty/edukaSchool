@extends('layouts.app')

@section('title', 'Gestión de Períodos Académicos')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        <i class="fas fa-calendar-alt"></i>
                        Gestión de Períodos Académicos
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('rutarrr1') }}">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('periodos.dashboard') }}">Períodos</a></li>
                        <li class="breadcrumb-item active">Gestión</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">

            <!-- Mensajes de éxito/error -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <i class="icon fas fa-check"></i> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <i class="icon fas fa-ban"></i> {{ session('error') }}
                </div>
            @endif

            <!-- Botón crear nuevo -->
            <div class="row mb-3">
                <div class="col-12">
                    <a href="{{ route('periodos.create') }}" class="btn btn-success">
                        <i class="fas fa-plus"></i> Nuevo Período
                    </a>
                    <a href="{{ route('periodos.dashboard') }}" class="btn btn-primary">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </div>
            </div>

            <!-- Filtros -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-filter"></i>
                        Filtros
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('periodos.index') }}" class="form-inline">
                        <div class="form-group mr-3">
                            <label for="estado" class="mr-2">Estado:</label>
                            <select name="estado" id="estado" class="form-control">
                                <option value="">Todos</option>
                                <option value="ACTIVO" {{ request('estado') === 'ACTIVO' ? 'selected' : '' }}>Activo</option>
                                <option value="INACTIVO" {{ request('estado') === 'INACTIVO' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>

                        <div class="form-group mr-3">
                            <label for="tipo_periodo" class="mr-2">Tipo:</label>
                            <select name="tipo_periodo" id="tipo_periodo" class="form-control">
                                <option value="">Todos</option>
                                <option value="PREINSCRIPCION" {{ request('tipo_periodo') === 'PREINSCRIPCION' ? 'selected' : '' }}>Pre-inscripción</option>
                                <option value="INSCRIPCION" {{ request('tipo_periodo') === 'INSCRIPCION' ? 'selected' : '' }}>Inscripción</option>
                                <option value="MATRICULA" {{ request('tipo_periodo') === 'MATRICULA' ? 'selected' : '' }}>Matrícula</option>
                                <option value="ACADEMICO" {{ request('tipo_periodo') === 'ACADEMICO' ? 'selected' : '' }}>Académico</option>
                                <option value="CIERRE" {{ request('tipo_periodo') === 'CIERRE' ? 'selected' : '' }}>Cierre</option>
                            </select>
                        </div>

                        <div class="form-group mr-3">
                            <label for="anio_lectivo" class="mr-2">Año Lectivo:</label>
                            <select name="anio_lectivo" id="anio_lectivo" class="form-control">
                                <option value="">Todos</option>
                                @foreach($aniosLectivos as $anio)
                                    <option value="{{ $anio->ano_lectivo_id }}" {{ request('anio_lectivo') == $anio->ano_lectivo_id ? 'selected' : '' }}>
                                        {{ $anio->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary mr-2">
                            <i class="fas fa-search"></i> Filtrar
                        </button>

                        <a href="{{ route('periodos.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Limpiar
                        </a>
                    </form>
                </div>
            </div>

            <!-- Tabla de períodos -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-list"></i>
                        Períodos Académicos
                    </h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Código</th>
                                <th>Tipo</th>
                                <th>Año Lectivo</th>
                                <th>Fecha Inicio</th>
                                <th>Fecha Fin</th>
                                <th>Estado</th>
                                <th>Activo</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($periodos as $periodo)
                                <tr>
                                    <td>
                                        <strong>{{ $periodo->nombre }}</strong>
                                        @if($periodo->descripcion)
                                            <br><small class="text-muted">{{ Str::limit($periodo->descripcion, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <code>{{ $periodo->codigo }}</code>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ $periodo->tipo_periodo }}</span>
                                    </td>
                                    <td>{{ $periodo->anoLectivo->nombre ?? 'N/A' }}</td>
                                    <td>{{ $periodo->fecha_inicio->format('d/m/Y') }}</td>
                                    <td>{{ $periodo->fecha_fin->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge {{ $periodo->estado === 'ACTIVO' ? 'badge-success' : 'badge-secondary' }}">
                                            {{ $periodo->estado }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($periodo->estaActivo())
                                            <span class="badge badge-success">
                                                <i class="fas fa-check"></i> SÍ
                                            </span>
                                        @elseif($periodo->estaProximo())
                                            <span class="badge badge-warning">
                                                <i class="fas fa-clock"></i> PRÓXIMO
                                            </span>
                                        @elseif($periodo->haTerminado())
                                            <span class="badge badge-secondary">
                                                <i class="fas fa-times"></i> TERMINADO
                                            </span>
                                        @else
                                            <span class="badge badge-light">
                                                <i class="fas fa-pause"></i> INACTIVO
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('periodos.edit', $periodo) }}" class="btn btn-sm btn-info" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            @if($periodo->estado === 'ACTIVO')
                                                <form method="POST" action="{{ route('periodos.update', $periodo) }}" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="estado" value="INACTIVO">
                                                    <button type="submit" class="btn btn-sm btn-warning"
                                                            onclick="return confirm('¿Está seguro de desactivar este período?')"
                                                            title="Desactivar">
                                                        <i class="fas fa-pause"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <form method="POST" action="{{ route('periodos.update', $periodo) }}" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="estado" value="ACTIVO">
                                                    <button type="submit" class="btn btn-sm btn-success"
                                                            onclick="return confirm('¿Está seguro de activar este período?')"
                                                            title="Activar">
                                                        <i class="fas fa-play"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            <form method="POST" action="{{ route('periodos.destroy', $periodo) }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('¿Está seguro de eliminar este período? Esta acción no se puede deshacer.')"
                                                        title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">
                                        <div class="py-4">
                                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">No se encontraron períodos académicos</p>
                                            <a href="{{ route('periodos.create') }}" class="btn btn-success">
                                                <i class="fas fa-plus"></i> Crear Primer Período
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($periodos->hasPages())
                    <div class="card-footer">
                        {{ $periodos->links() }}
                    </div>
                @endif
            </div>

            <!-- Información adicional -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-info-circle"></i>
                                Tipos de Período
                            </h3>
                        </div>
                        <div class="card-body">
                            <dl class="row">
                                <dt class="col-sm-5">PREINSCRIPCION:</dt>
                                <dd class="col-sm-7">Período para pre-inscripciones anticipadas</dd>

                                <dt class="col-sm-5">INSCRIPCION:</dt>
                                <dd class="col-sm-7">Período regular de inscripciones</dd>

                                <dt class="col-sm-5">MATRICULA:</dt>
                                <dd class="col-sm-7">Período de matrículas oficiales</dd>

                                <dt class="col-sm-5">ACADEMICO:</dt>
                                <dd class="col-sm-7">Año académico regular</dd>

                                <dt class="col-sm-5">CIERRE:</dt>
                                <dd class="col-sm-7">Período de cierre y fin de año</dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-cogs"></i>
                                Funcionalidades Automáticas
                            </h3>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success mr-2"></i> Control automático de matrículas por período</li>
                                <li><i class="fas fa-check text-success mr-2"></i> Notificaciones automáticas de cambios</li>
                                <li><i class="fas fa-check text-success mr-2"></i> Aplicación automática de descuentos</li>
                                <li><i class="fas fa-check text-success mr-2"></i> Filtros automáticos en reportes</li>
                                <li><i class="fas fa-check text-success mr-2"></i> Historial organizado por períodos</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>
@endsection

@section('scripts')
<script>
    // Confirmación para cambios de estado
    $('form').on('submit', function(e) {
        if ($(this).find('input[name="estado"]').length > 0) {
            var newState = $(this).find('input[name="estado"]').val();
            var action = newState === 'ACTIVO' ? 'activar' : 'desactivar';

            if (!confirm('¿Está seguro de ' + action + ' este período?')) {
                e.preventDefault();
                return false;
            }
        }
    });
</script>
@endsection
