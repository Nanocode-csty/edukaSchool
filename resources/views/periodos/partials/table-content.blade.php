<!-- Estadísticas Compactas -->
<div class="row mb-3" id="estadisticasContainer">
    <div class="col-md-12">
        <div class="stats-compact d-flex justify-content-between align-items-center p-3 rounded" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border: 1px solid #dee2e6;">
            <div class="stat-item text-center">
                <div class="stat-value">
                    <span class="badge badge-info stat-badge">{{ $periodos->total() }}</span>
                </div>
                <small class="text-muted d-block">Total Períodos</small>
            </div>
            <div class="stat-item text-center">
                <div class="stat-value">
                    <span class="badge badge-success stat-badge">{{ $periodos->filter(fn($p) => $p->estaActivo())->count() }}</span>
                </div>
                <small class="text-muted d-block">Activos</small>
            </div>
            <div class="stat-item text-center">
                <div class="stat-value">
                    <span class="badge badge-warning stat-badge">{{ $periodos->filter(fn($p) => $p->estaProximo())->count() }}</span>
                </div>
                <small class="text-muted d-block">Próximos</small>
            </div>
            <div class="stat-item text-center">
                <div class="stat-value">
                    <span class="badge badge-secondary stat-badge">{{ $periodos->filter(fn($p) => $p->haTerminado())->count() }}</span>
                </div>
                <small class="text-muted d-block">Terminados</small>
            </div>
        </div>
    </div>
</div>

<!-- Tabla de períodos -->
<div class="table-responsive">
    <table id="add-row" class="table-hover table" style="border: 1px solid #0A8CB3; border-radius: 10px; overflow: hidden;">
        <thead class="text-center table-hover" style="background-color: #f8f9fa; color: #0A8CB3; border:#0A8CB3 !important">
            <tr>
                <th scope="col">Nombre</th>
                <th scope="col">Código</th>
                <th scope="col">Tipo</th>
                <th scope="col">Año Lectivo</th>
                <th scope="col">Fecha Inicio</th>
                <th scope="col">Fecha Fin</th>
                <th scope="col">Estado</th>
                <th scope="col">Estado Actual</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody id="tbodyPeriodos">
            @forelse($periodos as $periodo)
                <tr>
                    <td>
                        <strong>{{ $periodo->nombre }}</strong>
                        @if($periodo->descripcion)
                            <br><small class="text-muted">{{ Str::limit($periodo->descripcion, 40) }}</small>
                        @endif
                    </td>
                    <td><code>{{ $periodo->codigo }}</code></td>
                    <td>
                        @if($periodo->tipo_periodo === 'PREINSCRIPCION')
                            <span class="badge badge-info">{{ $periodo->tipo_periodo }}</span>
                        @elseif($periodo->tipo_periodo === 'INSCRIPCION')
                            <span class="badge badge-success">{{ $periodo->tipo_periodo }}</span>
                        @elseif($periodo->tipo_periodo === 'MATRICULA')
                            <span class="badge badge-primary">{{ $periodo->tipo_periodo }}</span>
                        @elseif($periodo->tipo_periodo === 'ACADEMICO')
                            <span class="badge badge-warning">{{ $periodo->tipo_periodo }}</span>
                        @elseif($periodo->tipo_periodo === 'CIERRE')
                            <span class="badge badge-secondary">{{ $periodo->tipo_periodo }}</span>
                        @else
                            <span class="badge badge-secondary">{{ $periodo->tipo_periodo }}</span>
                        @endif
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
                                <i class="fas fa-play"></i> ACTIVO
                            </span>
                        @elseif($periodo->estaProximo())
                            <span class="badge badge-warning">
                                <i class="fas fa-clock"></i> PRÓXIMO
                            </span>
                        @elseif($periodo->haTerminado())
                            <span class="badge badge-secondary">
                                <i class="fas fa-stop"></i> TERMINADO
                            </span>
                        @else
                            <span class="badge badge-light">
                                <i class="fas fa-pause"></i> INACTIVO
                            </span>
                        @endif
                    </td>
                    <td>
                        <div class="text-center">
                            @if($periodo->permite_preinscripcion)
                                <span class="badge badge-info badge-sm" title="Permite Pre-inscripciones">
                                    <i class="fas fa-user-plus"></i>
                                </span>
                            @endif
                            @if($periodo->permite_inscripcion)
                                <span class="badge badge-success badge-sm" title="Permite Inscripciones">
                                    <i class="fas fa-clipboard-check"></i>
                                </span>
                            @endif
                            @if($periodo->permite_matricula)
                                <span class="badge badge-primary badge-sm" title="Permite Matrículas">
                                    <i class="fas fa-graduation-cap"></i>
                                </span>
                            @endif
                            @if($periodo->clases_activas)
                                <span class="badge badge-warning badge-sm" title="Clases Activas">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                </span>
                            @endif
                            @if(!$periodo->permite_preinscripcion && !$periodo->permite_inscripcion && !$periodo->permite_matricula && !$periodo->clases_activas)
                                <small class="text-muted">Sin permisos</small>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="{{ route('periodos.edit', $periodo) }}" class="btn btn-sm btn-info" title="Editar período">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center py-4">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No se encontraron períodos académicos</h5>
                        <p class="text-muted mb-3">No hay períodos que coincidan con los filtros aplicados.</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Paginación -->
<div id="paginacionContainer" class="mt-3">
    {{ $periodos->links() }}
</div>
