@extends('layouts.app')

@section('title', 'Períodos Académicos')

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
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('matriculas.index') }}">Matrículas</a></li>
                        <li class="breadcrumb-item active">Períodos Académicos</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">

            <!-- Dashboard de Estado Actual -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box {{ $periodoActual ? 'bg-success' : 'bg-warning' }}">
                        <div class="inner">
                            <h3>{{ $periodoActual ? $periodoActual->tipo_periodo : 'NINGUNO' }}</h3>
                            <p>Período Actual</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <a href="#periodo-actual" class="small-box-footer">
                            Más info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box {{ $puedeCrearPreinscripcion ? 'bg-info' : 'bg-secondary' }}">
                        <div class="inner">
                            <h3>{{ $puedeCrearPreinscripcion ? 'SÍ' : 'NO' }}</h3>
                            <p>Pre-inscripciones</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div class="small-box-footer">&nbsp;</div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box {{ $puedeCrearMatricula ? 'bg-primary' : 'bg-secondary' }}">
                        <div class="inner">
                            <h3>{{ $puedeCrearMatricula ? 'SÍ' : 'NO' }}</h3>
                            <p>Matrículas Oficiales</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div class="small-box-footer">&nbsp;</div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $todosPeriodos->count() }}</h3>
                            <p>Períodos Configurados</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-list"></i>
                        </div>
                        <div class="small-box-footer">&nbsp;</div>
                    </div>
                </div>
            </div>
            <!-- Período Actual -->
            @if($periodoActual)
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-calendar-check"></i>
                            Período Actual: {{ $periodoActual->nombre }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <strong>Tipo:</strong> {{ $periodoActual->tipo_periodo }}
                            </div>
                            <div class="col-md-3">
                                <strong>Inicio:</strong> {{ $periodoActual->fecha_inicio->format('d/m/Y') }}
                            </div>
                            <div class="col-md-3">
                                <strong>Fin:</strong> {{ $periodoActual->fecha_fin->format('d/m/Y') }}
                            </div>
                            <div class="col-md-3">
                                <strong>Estado:</strong>
                                <span class="badge badge-success">ACTIVO</span>
                            </div>
                        </div>
                        @if($periodoActual->descripcion)
                            <div class="row mt-2">
                                <div class="col-12">
                                    <strong>Descripción:</strong> {{ $periodoActual->descripcion }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="card card-warning">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-exclamation-triangle"></i>
                            No hay período activo
                        </h3>
                    </div>
                    <div class="card-body">
                        <p>No hay ningún período académico activo en este momento.</p>
                    </div>
                </div>
            @endif

            <!-- Información de permisos -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card {{ $puedeCrearPreinscripcion ? 'card-success' : 'card-secondary' }}">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-user-plus"></i>
                                Pre-inscripciones
                            </h3>
                        </div>
                        <div class="card-body">
                            <p>Estado: <strong>{{ $puedeCrearPreinscripcion ? 'PERMITIDO' : 'NO PERMITIDO' }}</strong></p>
                            @if($puedeCrearPreinscripcion)
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle"></i>
                                    Actualmente se pueden crear pre-inscripciones
                                </div>
                            @else
                                <div class="alert alert-secondary">
                                    <i class="fas fa-times-circle"></i>
                                    Actualmente NO se pueden crear pre-inscripciones
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card {{ $puedeCrearMatricula ? 'card-success' : 'card-secondary' }}">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-graduation-cap"></i>
                                Matrículas Oficiales
                            </h3>
                        </div>
                        <div class="card-body">
                            <p>Estado: <strong>{{ $puedeCrearMatricula ? 'PERMITIDO' : 'NO PERMITIDO' }}</strong></p>
                            @if($puedeCrearMatricula)
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle"></i>
                                    Actualmente se pueden crear matrículas oficiales
                                </div>
                            @else
                                <div class="alert alert-secondary">
                                    <i class="fas fa-times-circle"></i>
                                    Actualmente NO se pueden crear matrículas oficiales
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Todos los períodos -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-list"></i>
                        Todos los Períodos - {{ $anioLectivoActual ? $anioLectivoActual->nombre : date('Y') }}
                    </h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Tipo</th>
                                <th>Fecha Inicio</th>
                                <th>Fecha Fin</th>
                                <th>Estado</th>
                                <th>Activo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($todosPeriodos as $periodo)
                                <tr>
                                    <td>{{ $periodo->nombre }}</td>
                                    <td>
                                        <span class="badge badge-info">{{ $periodo->tipo_periodo }}</span>
                                    </td>
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
                                        @else
                                            <span class="badge badge-secondary">
                                                <i class="fas fa-times"></i> NO
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No hay períodos configurados</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Períodos próximos -->
            @if($proximosPeriodos->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-clock"></i>
                            Períodos Próximos (60 días)
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            @foreach($proximosPeriodos as $periodo)
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1">{{ $periodo->nombre }}</h5>
                                        <small>
                                            Faltan {{ now()->diffInDays($periodo->fecha_inicio) }} días
                                        </small>
                                    </div>
                                    <p class="mb-1">
                                        <strong>Tipo:</strong> {{ $periodo->tipo_periodo }} |
                                        <strong>Inicio:</strong> {{ $periodo->fecha_inicio->format('d/m/Y') }}
                                    </p>
                                    @if($periodo->descripcion)
                                        <small class="text-muted">{{ $periodo->descripcion }}</small>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Configuración -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-cogs"></i>
                        Configuración de Períodos
                    </h3>
                </div>
                <div class="card-body">
                    <p>Los períodos académicos se configuran automáticamente cuando se crea un año lectivo.</p>
                    <p>Para modificar los períodos, edite la tabla <code>periodos_matricula</code> o actualice el seeder <code>PeriodosMatriculaSeeder</code>.</p>

                    <div class="row">
                        <div class="col-md-6">
                            <h5>Tipos de Período:</h5>
                            <ul>
                                <li><strong>PREINSCRIPCION:</strong> Período para pre-inscripciones anticipadas</li>
                                <li><strong>INSCRIPCION:</strong> Período regular de inscripciones</li>
                                <li><strong>MATRICULA:</strong> Período de matrículas oficiales</li>
                                <li><strong>ACADEMICO:</strong> Año académico regular</li>
                                <li><strong>CIERRE:</strong> Período de cierre y fin de año</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h5>Acciones Automáticas:</h5>
                            <ul>
                                <li><strong>Pre-inscripciones:</strong> Solo permitidas en períodos PREINSCRIPCION/INSCRIPCION</li>
                                <li><strong>Matrículas:</strong> Solo permitidas en período MATRICULA</li>
                                <li><strong>Filtros:</strong> Los filtros de matrículas respetan los períodos activos</li>
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
    // Actualizar información de período cada 5 minutos
    setInterval(function() {
        // Podríamos agregar una actualización automática aquí
        console.log('Verificando período actual...');
    }, 300000); // 5 minutos
</script>
@endsection
