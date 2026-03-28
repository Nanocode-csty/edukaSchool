<!-- Dashboard content for Administrators -->
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <div class="card-head-row">
                    <div class="card-title">Asistencias Recientes</div>
                    <div class="card-tools">
                        <a href="{{ route('asistencia.admin-index') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-eye"></i> Ver Todas
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if(isset($asistenciasHoy) && $asistenciasHoy->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Estudiante</th>
                                    <th>Asignatura</th>
                                    <th>Profesor</th>
                                    <th>Tipo</th>
                                    <th>Hora</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($asistenciasHoy->take(5) as $asistencia)
                                    <tr>
                                        <td>{{ $asistencia->matricula->estudiante->persona->nombres }} {{ $asistencia->matricula->estudiante->persona->apellidos }}</td>
                                        <td>{{ $asistencia->cursoAsignatura->asignatura->nombre }}</td>
                                        <td>{{ $asistencia->cursoAsignatura->profesor->persona->nombres }} {{ $asistencia->cursoAsignatura->profesor->persona->apellidos }}</td>
                                        <td>
                                            <span class="badge badge-{{ $asistencia->tipoAsistencia->codigo == 'A' ? 'success' : ($asistencia->tipoAsistencia->codigo == 'F' ? 'danger' : 'warning') }}">
                                                {{ $asistencia->tipoAsistencia->nombre }}
                                            </span>
                                        </td>
                                        <td>{{ $asistencia->hora_registro ? \Carbon\Carbon::parse($asistencia->hora_registro)->format('H:i') : '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-calendar-check fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No hay asistencias registradas hoy</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <div class="card-head-row">
                    <div class="card-title">Alertas Recientes</div>
                </div>
            </div>
            <div class="card-body">
                @if(isset($alertasRecientes) && $alertasRecientes->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($alertasRecientes->take(3) as $alerta)
                            <div class="list-group-item px-0">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm mr-3">
                                        <span class="avatar-title rounded-circle bg-warning">
                                            <i class="fas fa-exclamation-triangle"></i>
                                        </span>
                                    </div>
                                    <div class="flex-1">
                                        <h6 class="text-truncate mb-0">{{ $alerta->matricula->estudiante->persona->nombres }}</h6>
                                        <small class="text-muted">Justificación pendiente</small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('asistencia.verificar') }}" class="btn btn-sm btn-outline-primary">
                            Ver Todas las Alertas
                        </a>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <p class="text-muted">No hay alertas pendientes</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Estadísticas del mes -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-head-row">
                    <div class="card-title">Estadísticas del Mes</div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card card-stats card-round">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="icon-big text-center">
                                            <i class="fas fa-user-check text-success"></i>
                                        </div>
                                    </div>
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="card-category">Presentes</p>
                                            <h4 class="card-title">1,245</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-stats card-round">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="icon-big text-center">
                                            <i class="fas fa-user-times text-danger"></i>
                                        </div>
                                    </div>
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="card-category">Ausentes</p>
                                            <h4 class="card-title">89</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-stats card-round">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="icon-big text-center">
                                            <i class="fas fa-clock text-warning"></i>
                                        </div>
                                    </div>
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="card-category">Tardanzas</p>
                                            <h4 class="card-title">156</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-stats card-round">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="icon-big text-center">
                                            <i class="fas fa-percentage text-info"></i>
                                        </div>
                                    </div>
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="card-category">Promedio</p>
                                            <h4 class="card-title">92.3%</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
