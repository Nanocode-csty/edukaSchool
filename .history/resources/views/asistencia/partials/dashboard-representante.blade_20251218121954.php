<!-- Dashboard content for Representatives -->
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <div class="card-head-row">
                    <div class="card-title">Mis Estudiantes</div>
                    <div class="card-tools">
                        <a href="{{ route('asistencia.misEstudiantes') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-eye"></i> Ver Detalles
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if(isset($estudiantesRepresentados) && $estudiantesRepresentados->count() > 0)
                    <div class="row">
                        @foreach($estudiantesRepresentados->take(6) as $estudianteData)
                            <div class="col-md-6 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="avatar avatar-sm mr-3">
                                                <img src="{{ asset('imagenes/imgEstudiante.png') }}" alt="Avatar" class="avatar-img rounded-circle">
                                            </div>
                                            <div class="flex-1">
                                                <h6 class="card-title mb-0">{{ $estudianteData['estudiante']->nombres }} {{ $estudianteData['estudiante']->apellidos }}</h6>
                                                <small class="text-muted">
                                                    @if($estudianteData['matricula_principal'])
                                                        {{ $estudianteData['matricula_principal']->grado->nombre }} {{ $estudianteData['matricula_principal']->seccion->nombre }}
                                                    @else
                                                        Sin matrícula activa
                                                    @endif
                                                </small>
                                            </div>
                                        </div>

                                        @if(isset($estadisticas[$estudianteData['estudiante']->estudiante_id]))
                                            <div class="row text-center">
                                                <div class="col-4">
                                                    <small class="text-muted d-block">Asistencia</small>
                                                    <span class="badge badge-{{ $estadisticas[$estudianteData['estudiante']->estudiante_id]['porcentaje'] >= 90 ? 'success' : ($estadisticas[$estudianteData['estudiante']->estudiante_id]['porcentaje'] >= 80 ? 'warning' : 'danger') }}">
                                                        {{ $estadisticas[$estudianteData['estudiante']->estudiante_id]['porcentaje'] }}%
                                                    </span>
                                                </div>
                                                <div class="col-4">
                                                    <small class="text-muted d-block">Presentes</small>
                                                    <span class="text-success font-weight-bold">{{ $estadisticas[$estudianteData['estudiante']->estudiante_id]['positivas'] ?? 0 }}</span>
                                                </div>
                                                <div class="col-4">
                                                    <small class="text-muted d-block">Ausencias</small>
                                                    <span class="text-danger font-weight-bold">{{ $estadisticas[$estudianteData['estudiante']->estudiante_id]['total'] - $estadisticas[$estudianteData['estudiante']->estudiante_id]['positivas'] ?? 0 }}</span>
                                                </div>
                                            </div>
                                        @else
                                            <div class="text-center">
                                                <small class="text-muted">Sin datos de asistencia este mes</small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No tienes estudiantes asignados</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Justificaciones pendientes -->
        <div class="card">
            <div class="card-header">
                <div class="card-head-row">
                    <div class="card-title">Justificaciones</div>
                </div>
            </div>
            <div class="card-body">
                @if(isset($justificacionesPendientes) && $justificacionesPendientes->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($justificacionesPendientes->take(3) as $justificacion)
                            <div class="list-group-item px-0">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm mr-3">
                                        <span class="avatar-title rounded-circle bg-warning">
                                            <i class="fas fa-clock"></i>
                                        </span>
                                    </div>
                                    <div class="flex-1">
                                        <h6 class="text-truncate mb-0">{{ $justificacion->matricula->estudiante->nombres }}</h6>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($justificacion->fecha)->format('d/m/Y') }}</small>
                                        <br>
                                        <small class="text-warning">{{ $justificacion->estado }}</small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('asistencia.mis-justificaciones') }}" class="btn btn-sm btn-outline-warning">
                            Ver Todas
                        </a>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <p class="text-muted">No hay justificaciones pendientes</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Acciones rápidas -->
        <div class="card mt-3">
            <div class="card-header">
                <div class="card-head-row">
                    <div class="card-title">Acciones Rápidas</div>
                </div>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('asistencia.justificar') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-file-alt mr-2"></i>Justificar Inasistencia
                    </a>
                    <a href="{{ route('notas.misEstudiantes') }}" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-graduation-cap mr-2"></i>Ver Notas
                    </a>
                    <a href="mailto:rcroblesro@unitru.edu.pe?subject=Consulta%20Eduka" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-envelope mr-2"></i>Contactar Soporte
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Resumen de asistencia del mes -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-head-row">
                    <div class="card-title">Resumen de Asistencia - Este Mes</div>
                </div>
            </div>
            <div class="card-body">
                @if(isset($estudiantesRepresentados) && $estudiantesRepresentados->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Estudiante</th>
                                    <th>Curso</th>
                                    <th>Asistencia</th>
                                    <th>Presentes</th>
                                    <th>Ausencias</th>
                                    <th>Tendencia</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($estudiantesRepresentados as $estudianteData)
                                    @php
                                        $stats = $estadisticas[$estudianteData['estudiante']->estudiante_id] ?? ['total' => 0, 'positivas' => 0, 'porcentaje' => 0];
                                        $ausencias = $stats['total'] - $stats['positivas'];
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset('imagenes/imgEstudiante.png') }}" alt="Avatar" class="avatar avatar-xs mr-2">
                                                <div>
                                                    <div class="font-weight-bold">{{ $estudianteData['estudiante']->nombres }}</div>
                                                    <small class="text-muted">{{ $estudianteData['estudiante']->apellidos }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($estudianteData['matricula_principal'])
                                                {{ $estudianteData['matricula_principal']->grado->nombre }} {{ $estudianteData['matricula_principal']->seccion->nombre }}
                                            @else
                                                <span class="text-muted">Sin curso</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $stats['porcentaje'] >= 90 ? 'success' : ($stats['porcentaje'] >= 80 ? 'warning' : 'danger') }}">
                                                {{ $stats['porcentaje'] }}%
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-success font-weight-bold">{{ $stats['positivas'] }}</span>
                                        </td>
                                        <td>
                                            <span class="text-danger font-weight-bold">{{ $ausencias }}</span>
                                        </td>
                                        <td>
                                            @if($stats['porcentaje'] >= 90)
                                                <i class="fas fa-arrow-up text-success"></i> Excelente
                                            @elseif($stats['porcentaje'] >= 80)
                                                <i class="fas fa-minus text-warning"></i> Bueno
                                            @else
                                                <i class="fas fa-arrow-down text-danger"></i> Requiere atención
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('asistencia.detalle-estudiante', $estudianteData['matricula_principal']->matricula_id ?? 1) }}"
                                                   class="btn btn-outline-primary btn-sm" title="Ver detalle">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('asistencia.justificar') }}"
                                                   class="btn btn-outline-warning btn-sm" title="Justificar">
                                                    <i class="fas fa-file-alt"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No tienes estudiantes asignados para mostrar estadísticas</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
