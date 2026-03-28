<!-- Dashboard content for Professors -->
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <div class="card-head-row">
                    <div class="card-title">Mis Clases de Hoy</div>
                    <div class="card-tools">
                        <a href="{{ route('asistencia.index') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Registrar Asistencia
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if(isset($sesionesHoy) && $sesionesHoy->count() > 0)
                    <div class="row">
                        @foreach($sesionesHoy as $sesion)
                            <div class="col-md-6 mb-3">
                                <div class="card border-left-primary">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="card-title mb-1">{{ $sesion->cursoAsignatura->asignatura->nombre }}</h6>
                                                <p class="card-text small text-muted mb-1">
                                                    {{ $sesion->cursoAsignatura->curso->grado->nombre }} - {{ $sesion->cursoAsignatura->curso->seccion->nombre }}
                                                </p>
                                                <p class="card-text small">
                                                    <i class="fas fa-clock"></i> {{ \Carbon\Carbon::parse($sesion->hora_inicio)->format('H:i') }} - {{ \Carbon\Carbon::parse($sesion->hora_fin)->format('H:i') }}
                                                </p>
                                            </div>
                                            <div class="text-right">
                                                <span class="badge badge-primary">{{ $sesion->aula ?? 'Sin aula' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No tienes clases programadas para hoy</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <div class="card-head-row">
                    <div class="card-title">Estadísticas de Hoy</div>
                </div>
            </div>
            <div class="card-body">
                @if(isset($estadisticas))
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="card card-stats card-round">
                                <div class="card-body p-2">
                                    <div class="numbers">
                                        <p class="card-category">Presentes</p>
                                        <h4 class="card-title text-success">{{ $estadisticas['presentes'] ?? 0 }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card card-stats card-round">
                                <div class="card-body p-2">
                                    <div class="numbers">
                                        <p class="card-category">Ausentes</p>
                                        <h4 class="card-title text-danger">{{ $estadisticas['ausentes'] ?? 0 }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="progress mb-2">
                            <div class="progress-bar bg-success" role="progressbar"
                                 style="width: {{ $estadisticas['porcentaje_asistencia'] ?? 0 }}%"
                                 aria-valuenow="{{ $estadisticas['porcentaje_asistencia'] ?? 0 }}"
                                 aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                        <small class="text-muted">Asistencia: {{ $estadisticas['porcentaje_asistencia'] ?? 0 }}%</small>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No hay estadísticas disponibles</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Próximas sesiones -->
        <div class="card mt-3">
            <div class="card-header">
                <div class="card-head-row">
                    <div class="card-title">Próximas Clases</div>
                </div>
            </div>
            <div class="card-body">
                @if(isset($proximasSesiones) && $proximasSesiones->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($proximasSesiones->take(3) as $sesion)
                            <div class="list-group-item px-0">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-1">
                                        <h6 class="mb-1">{{ $sesion->cursoAsignatura->asignatura->nombre }}</h6>
                                        <small class="text-muted">
                                            {{ $sesion->cursoAsignatura->curso->grado->nombre }} {{ $sesion->cursoAsignatura->curso->seccion->nombre }}
                                        </small>
                                        <br>
                                        <small class="text-primary">
                                            <i class="fas fa-calendar"></i> {{ \Carbon\Carbon::parse($sesion->fecha)->format('d/m/Y') }}
                                            <i class="fas fa-clock ml-2"></i> {{ \Carbon\Carbon::parse($sesion->hora_inicio)->format('H:i') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-3">
                        <small class="text-muted">No hay clases programadas próximamente</small>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Mis cursos y estadísticas -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-head-row">
                    <div class="card-title">Mis Cursos</div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Asignatura</th>
                                <th>Curso</th>
                                <th>Estudiantes</th>
                                <th>Asistencia Promedio</th>
                                <th>Última Clase</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Sample data - in real implementation, this would come from the controller -->
                            <tr>
                                <td>Matemáticas</td>
                                <td>5to A</td>
                                <td>28</td>
                                <td>
                                    <span class="badge badge-success">94%</span>
                                </td>
                                <td>Hoy, 10:00</td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-chart-bar"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>Lenguaje</td>
                                <td>5to B</td>
                                <td>26</td>
                                <td>
                                    <span class="badge badge-success">91%</span>
                                </td>
                                <td>Ayer, 14:30</td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-chart-bar"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>Ciencias</td>
                                <td>4to A</td>
                                <td>30</td>
                                <td>
                                    <span class="badge badge-warning">87%</span>
                                </td>
                                <td>Hoy, 08:00</td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-chart-bar"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
