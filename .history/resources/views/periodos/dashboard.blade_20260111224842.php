@extends('layout.eduka')

@section('title', 'Dashboard de Períodos Académicos')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        <i class="fas fa-tachometer-alt"></i>
                        Dashboard de Períodos Académicos
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('rutarrr1') }}">Inicio</a></li>
                        <li class="breadcrumb-item active">Dashboard Períodos</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">

            <!-- Período Actual -->
            <div class="row">
                <div class="col-lg-12">
                    @if($periodoActual)
                        <div class="card card-success">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-calendar-check"></i>
                                    Período Actual: {{ $periodoActual->nombre }}
                                </h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <strong>Tipo:</strong>
                                        <span class="badge badge-info">{{ $periodoActual->tipo_periodo }}</span>
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

                                <!-- Progreso del período -->
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <div class="progress">
                                            @php
                                                $totalDias = $periodoActual->fecha_inicio->diffInDays($periodoActual->fecha_fin);
                                                $diasTranscurridos = $periodoActual->fecha_inicio->diffInDays(now());
                                                $porcentaje = $totalDias > 0 ? min(100, ($diasTranscurridos / $totalDias) * 100) : 0;
                                            @endphp
                                            <div class="progress-bar bg-success" role="progressbar"
                                                 style="width: {{ $porcentaje }}%"
                                                 aria-valuenow="{{ $porcentaje }}"
                                                 aria-valuemin="0"
                                                 aria-valuemax="100">
                                                {{ number_format($porcentaje, 1) }}% completado
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
                                <a href="{{ route('periodos.create') }}" class="btn btn-warning">
                                    <i class="fas fa-plus"></i> Crear Nuevo Período
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Estadísticas -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $totalPeriodos }}</h3>
                            <p>Total de Períodos</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-calendar"></i>
                        </div>
                        <a href="{{ route('periodos.index') }}" class="small-box-footer">
                            Ver todos <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $periodosActivos }}</h3>
                            <p>Períodos Activos</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="small-box-footer">&nbsp;</div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3>{{ $matriculasPeriodoActual }}</h3>
                            <p>Matrículas en Período Actual</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <a href="{{ route('periodos.historial-matriculas') }}" class="small-box-footer">
                            Ver historial <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $descuentosActivos->count() }}</h3>
                            <p>Descuentos Activos</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-percent"></i>
                        </div>
                        <a href="{{ route('periodos.descuentos') }}" class="small-box-footer">
                            Gestionar <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Períodos Próximos -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-clock"></i>
                                Períodos Próximos
                            </h3>
                        </div>
                        <div class="card-body">
                            @if($proximosPeriodos->count() > 0)
                                <div class="list-group">
                                    @foreach($proximosPeriodos as $periodo)
                                        <div class="list-group-item">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h6 class="mb-1">{{ $periodo->nombre }}</h6>
                                                <small class="text-muted">
                                                    {{ now()->diffInDays($periodo->fecha_inicio) }} días
                                                </small>
                                            </div>
                                            <p class="mb-1">
                                                <strong>Tipo:</strong> {{ $periodo->tipo_periodo }} |
                                                <strong>Inicio:</strong> {{ $periodo->fecha_inicio->format('d/m/Y') }}
                                            </p>
                                            <div class="progress mt-2" style="height: 6px;">
                                                @php
                                                    $diasHastaInicio = now()->diffInDays($periodo->fecha_inicio);
                                                    $diasTotales = 30; // Mostrar progreso para próximos 30 días
                                                    $progreso = $diasTotales > 0 ? (($diasTotales - $diasHastaInicio) / $diasTotales) * 100 : 0;
                                                @endphp
                                                <div class="progress-bar bg-warning" role="progressbar"
                                                     style="width: {{ max(0, min(100, $progreso)) }}%"
                                                     aria-valuenow="{{ $progreso }}"
                                                     aria-valuemin="0"
                                                     aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted">No hay períodos próximos en los próximos 30 días.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Notificaciones Recientes -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-bell"></i>
                                Notificaciones Recientes
                            </h3>
                        </div>
                        <div class="card-body">
                            @if($notificacionesRecientes->count() > 0)
                                <div class="list-group">
                                    @foreach($notificacionesRecientes as $notificacion)
                                        <div class="list-group-item">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h6 class="mb-1">{{ $notificacion->titulo }}</h6>
                                                <small class="text-muted">
                                                    {{ $notificacion->created_at->diffForHumans() }}
                                                </small>
                                            </div>
                                            <p class="mb-1">{{ Str::limit($notificacion->mensaje, 100) }}</p>
                                            <small class="text-muted">
                                                Estado:
                                                <span class="badge badge-{{ $notificacion->estado === 'ENVIADA' ? 'success' : 'warning' }}">
                                                    {{ $notificacion->estado }}
                                                </span>
                                            </small>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-3">
                                    <a href="{{ route('periodos.notificaciones') }}" class="btn btn-primary btn-sm">
                                        Ver todas las notificaciones
                                    </a>
                                </div>
                            @else
                                <p class="text-muted">No hay notificaciones recientes.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Descuentos Activos -->
            @if($descuentosActivos->count() > 0)
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-percent"></i>
                                    Descuentos Activos
                                </h3>
                            </div>
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Período</th>
                                            <th>Tipo</th>
                                            <th>Descuento</th>
                                            <th>Aplicable a</th>
                                            <th>Usos</th>
                                            <th>Vigencia</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($descuentosActivos as $descuento)
                                            <tr>
                                                <td>{{ $descuento->nombre }}</td>
                                                <td>{{ $descuento->periodo->nombre ?? 'N/A' }}</td>
                                                <td>
                                                    <span class="badge badge-info">{{ $descuento->tipo_descuento }}</span>
                                                </td>
                                                <td>
                                                    @if($descuento->tipo_descuento === 'PORCENTAJE' || $descuento->tipo_descuento === 'AMBOS')
                                                        {{ $descuento->porcentaje_descuento }}%
                                                    @endif
                                                    @if($descuento->tipo_descuento === 'AMBOS')
                                                        +
                                                    @endif
                                                    @if($descuento->tipo_descuento === 'FIJO' || $descuento->tipo_descuento === 'AMBOS')
                                                        S/ {{ number_format($descuento->monto_fijo_descuento, 2) }}
                                                    @endif
                                                </td>
                                                <td>{{ $descuento->aplicable_a }}</td>
                                                <td>
                                                    {{ $descuento->usos_actuales }} /
                                                    {{ $descuento->limite_usos ?? '∞' }}
                                                </td>
                                                <td>
                                                    {{ $descuento->fecha_inicio_vigencia->format('d/m/Y') }} -
                                                    {{ $descuento->fecha_fin_vigencia->format('d/m/Y') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Acciones Rápidas -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-bolt"></i>
                                Acciones Rápidas
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <a href="{{ route('periodos.create') }}" class="btn btn-success btn-block">
                                        <i class="fas fa-plus"></i>
                                        <br>Nuevo Período
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="{{ route('periodos.notificaciones') }}" class="btn btn-info btn-block">
                                        <i class="fas fa-bell"></i>
                                        <br>Ver Notificaciones
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="{{ route('periodos.descuentos') }}" class="btn btn-warning btn-block">
                                        <i class="fas fa-percent"></i>
                                        <br>Gestionar Descuentos
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <form method="POST" action="{{ route('periodos.verificar') }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-block">
                                            <i class="fas fa-sync"></i>
                                            <br>Verificar Períodos
                                        </button>
                                    </form>
                                </div>
                            </div>
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
    // Auto-refresh cada 5 minutos
    setInterval(function() {
        // Opcional: recargar datos automáticamente
        console.log('Actualizando dashboard de períodos...');
    }, 300000);
</script>
@endsection
