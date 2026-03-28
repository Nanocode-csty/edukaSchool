<!-- Tabla de Asistencias -->
<div class="card" style="border: none">
    <div
        style="background: #E0F7FA; color: #0A8CB3; font-weight: bold; border: 2px solid #86D2E3; border-bottom: 2px solid #86D2E3; padding: 6px 20px; border-radius:4px 4px 0px 0px;">
        <i class="fas fa-table mr-2"></i>
        Registros de Asistencia ({{ $asistencias->total() }})
    </div>
    <div class="card-body"
        style="border: 2px solid #86D2E3; border-top: none; border-radius: 0px 0px 4px 4px !important; padding: 0;">

        @if($asistencias->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No hay registros de asistencia para mostrar</h5>
                <p class="text-muted">Prueba cambiando los filtros de búsqueda</p>
            </div>
        @else
            <table class="table table-hover table-striped mb-0">
                    <thead class="table-light" style="position: sticky; top: 0; z-index: 10;">
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Estudiante</th>
                            <th>Asignatura</th>
                            <th>Profesor</th>
                            <th>Curso</th>
                            <th>Fecha</th>
                            <th>Asistencia</th>
                            <th>Estado</th>
                            <th>Registro</th>
                            <th style="width: 100px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($asistencias as $index => $asistencia)
                            <tr>
                                <td class="align-middle">{{ $asistencias->firstItem() + $index }}</td>

                                <td class="align-middle">
                                    <div>
                                        <strong>{{ $asistencia->matricula->estudiante->persona->nombres ?? $asistencia->matricula->estudiante->nombres ?? 'N/A' }}</strong><br>
                                        <small class="text-muted">{{ $asistencia->matricula->estudiante->persona->apellidos ?? $asistencia->matricula->estudiante->apellidos ?? 'N/A' }}</small><br>
                                        <small class="text-muted">DNI: {{ $asistencia->matricula->estudiante->persona->dni ?? $asistencia->matricula->estudiante->dni ?? 'N/A' }}</small>
                                    </div>
                                </td>

                                <td class="align-middle">
                                    <strong>{{ $asistencia->cursoAsignatura->asignatura->nombre }}</strong>
                                </td>

                                <td class="align-middle">
                                    <div>
                                        <strong>{{ $asistencia->cursoAsignatura->profesor->persona->nombres ?? $asistencia->cursoAsignatura->profesor->nombres ?? 'N/A' }}</strong><br>
                                        <small class="text-muted">{{ $asistencia->cursoAsignatura->profesor->persona->apellidos ?? $asistencia->cursoAsignatura->profesor->apellidos ?? 'N/A' }}</small>
                                    </div>
                                </td>

                                <td class="align-middle">
                                    <div>
                                        <strong>{{ $asistencia->matricula->curso->grado->nombre }}</strong><br>
                                        <small class="text-muted">{{ $asistencia->matricula->curso->seccion->nombre }}</small>
                                    </div>
                                </td>

                                <td class="align-middle">
                                    <div>
                                        <strong>{{ $asistencia->fecha ? $asistencia->fecha->format('d/m/Y') : 'N/A' }}</strong><br>
                                        <small class="text-muted">{{ $asistencia->fecha ? $asistencia->fecha->format('l') : '' }}</small>
                                    </div>
                                </td>

                                <td class="align-middle">
                                    <span class="status-badge {{ $asistencia->tipoAsistencia->codigo == 'A' ? 'presente' : ($asistencia->tipoAsistencia->codigo == 'F' ? 'ausente' : ($asistencia->tipoAsistencia->codigo == 'T' ? 'tardanza' : 'justificada')) }}">
                                        <i class="fas fa-{{ $asistencia->tipoAsistencia->codigo == 'A' ? 'check' : ($asistencia->tipoAsistencia->codigo == 'F' ? 'times' : ($asistencia->tipoAsistencia->codigo == 'T' ? 'clock' : 'file-alt')) }}"></i>
                                        {{ $asistencia->tipoAsistencia->nombre }}
                                    </span>
                                </td>

                                <td class="align-middle">
                                    <span class="status-badge {{ $asistencia->estado == 'Registrada' ? 'registrada' : 'pendiente' }}">
                                        {{ $asistencia->estado }}
                                    </span>
                                </td>

                                <td class="align-middle">
                                    <div>
                                        <small class="text-muted">{{ $asistencia->hora_registro ? $asistencia->hora_registro->format('H:i') : 'N/A' }}</small><br>
                                        <small class="text-muted">{{ $asistencia->hora_registro ? $asistencia->hora_registro->format('d/m/Y') : '' }}</small>
                                    </div>
                                </td>

                                <td class="align-middle">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('asistencia.detalle-estudiante', $asistencia->matricula_id) }}"
                                            class="btn btn-sm btn-outline-primary"
                                            title="Ver detalle del estudiante"
                                            target="_blank">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($asistencia->justificacion)
                                            <button class="btn btn-sm btn-outline-info"
                                                    title="Ver justificación"
                                                    onclick="verJustificacion('{{ $asistencia->justificacion }}')">
                                                <i class="fas fa-comment"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="d-flex justify-content-center mt-3 p-3">
                {{ $asistencias->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
