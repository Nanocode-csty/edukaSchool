@extends('cplantilla.bprincipal')
@section('titulo','Gestión de Períodos Académicos')
@section('contenidoplantilla')
<x-breadcrumb :module="'periodos'" :section="'gestion'" />
<div class="container-fluid" id="contenido-principal">
    <div class="row mt-4 ml-1 mr-1">
        <div class="col-12">
            <div class="box_block">
                <!-- Collapse header -->
                <button class="btn btn-block text-left rounded-0 btn_header header_6" type="button" data-toggle="collapse" data-target="#collapseTablaPeriodos" aria-expanded="true" aria-controls="collapseTablaPeriodos" style="background: #0A8CB3 !important; font-weight: bold; color: white;">
                    <i class="fas fa-calendar-alt m-1"></i>&nbsp;Gestión de Períodos Académicos
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
                                Gestiona los períodos académicos del sistema. Aquí puedes crear, editar y configurar los diferentes períodos de matrícula, pre-inscripción y año académico.
                            </p>
                            <p style="font-family: 'Quicksand', sans-serif; font-weight: 600; color: #004a92;">
                                Los períodos controlan automáticamente las fechas de matrículas, notificaciones y descuentos aplicables a los estudiantes.
                            </p>
                        </div>
                    </div>
                </div>
                <!-- Collapse: filtros, estadísticas y tabla -->
                <div class="collapse show" id="collapseTablaPeriodos">
                    <div class="card card-body rounded-0 border-0 pt-0 pb-2" style="background: transparent;">
                        <!-- Acciones Rápidas -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2 flex-wrap">
                                    <a href="{{ route('periodos.dashboard') }}" class="btn btn-outline-primary btn-sm" title="Ver dashboard de períodos">
                                        <i class="fas fa-tachometer-alt mr-1"></i>Dashboard
                                    </a>
                                    <a href="{{ route('periodos.descuentos') }}" class="btn btn-outline-success btn-sm" title="Gestionar descuentos">
                                        <i class="fas fa-percent mr-1"></i>Descuentos
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Botón Crear Nuevo -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <a href="{{ route('periodos.create') }}" class="btn btn-success">
                                    <i class="fas fa-plus"></i> Nuevo Período Académico
                                </a>
                            </div>
                        </div>

                        <!-- Filtros -->
                        <div class="row mb-3 align-items-end" id="filtrosContainer">
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-primary">Estado</label>
                                <select class="form-control filtro-input" id="estado" name="estado">
                                    <option value="">Todos los estados</option>
                                    <option value="ACTIVO">Activo</option>
                                    <option value="INACTIVO">Inactivo</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-primary">Tipo de Período</label>
                                <select class="form-control filtro-input" id="tipo_periodo" name="tipo_periodo">
                                    <option value="">Todos los tipos</option>
                                    <option value="PREINSCRIPCION">Pre-inscripción</option>
                                    <option value="INSCRIPCION">Inscripción</option>
                                    <option value="MATRICULA">Matrícula</option>
                                    <option value="ACADEMICO">Académico</option>
                                    <option value="CIERRE">Cierre</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex gap-1">
                                    <button type="button" class="btn btn-primary flex-fill" id="btnAplicarFiltros" title="Aplicar filtros">
                                        <i class="fas fa-search"></i> Aplicar
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary flex-fill" id="btnLimpiarFiltros" title="Limpiar filtros">
                                        <i class="fas fa-eraser"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

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
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('periodos.edit', $periodo) }}" class="btn btn-sm btn-info" title="Editar período">
                                                        <i class="fas fa-edit"></i>
                                                    </a>

                                                    @if($periodo->estado === 'ACTIVO')
                                                        <button type="button" class="btn btn-sm btn-warning" title="Desactivar período"
                                                                onclick="cambiarEstadoPeriodo({{ $periodo->periodo_id }}, 'INACTIVO')">
                                                            <i class="fas fa-pause"></i>
                                                        </button>
                                                    @else
                                                        <button type="button" class="btn btn-sm btn-success" title="Activar período"
                                                                onclick="cambiarEstadoPeriodo({{ $periodo->periodo_id }}, 'ACTIVO')">
                                                            <i class="fas fa-play"></i>
                                                        </button>
                                                    @endif

                                                    <button type="button" class="btn btn-sm btn-danger" title="Eliminar período"
                                                            onclick="eliminarPeriodo({{ $periodo->periodo_id }}, '{{ $periodo->nombre }}')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center py-4">
                                                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">No se encontraron períodos académicos</h5>
                                                <p class="text-muted mb-3">Aún no has creado ningún período académico.</p>
                                                <a href="{{ route('periodos.create') }}" class="btn btn-success">
                                                    <i class="fas fa-plus"></i> Crear Primer Período
                                                </a>
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
                    </div>
                </div>
                <script>
                document.addEventListener('DOMContentLoaded', function () {
                    // Collapse icon toggle
                    const btn = document.querySelector('[data-target="#collapseTablaPeriodos"]');
                    const icon = btn.querySelector('.fas.fa-chevron-down');
                    const collapse = document.getElementById('collapseTablaPeriodos');
                    collapse.addEventListener('show.bs.collapse', function () {
                        icon.classList.remove('fa-chevron-down');
                        icon.classList.add('fa-chevron-up');
                    });
                    collapse.addEventListener('hide.bs.collapse', function () {
                        icon.classList.remove('fa-chevron-up');
                        icon.classList.add('fa-chevron-down');
                    });
                });

                function cambiarEstadoPeriodo(periodoId, nuevoEstado) {
                    const action = nuevoEstado === 'ACTIVO' ? 'activar' : 'desactivar';
                    const actionText = nuevoEstado === 'ACTIVO' ? 'Activar' : 'Desactivar';

                    Swal.fire({
                        title: `${actionText} Período`,
                        text: `¿Está seguro de ${action} este período?`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: nuevoEstado === 'ACTIVO' ? '#28a745' : '#ffc107',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: `Sí, ${action}`,
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = `{{ url('periodos') }}/${periodoId}`;

                            const methodField = document.createElement('input');
                            methodField.type = 'hidden';
                            methodField.name = '_method';
                            methodField.value = 'PUT';

                            const estadoField = document.createElement('input');
                            estadoField.type = 'hidden';
                            estadoField.name = 'estado';
                            estadoField.value = nuevoEstado;

                            const csrfField = document.createElement('input');
                            csrfField.type = 'hidden';
                            csrfField.name = '_token';
                            csrfField.value = '{{ csrf_token() }}';

                            form.appendChild(methodField);
                            form.appendChild(estadoField);
                            form.appendChild(csrfField);
                            document.body.appendChild(form);
                            form.submit();
                        }
                    });
                }

                function eliminarPeriodo(periodoId, nombrePeriodo) {
                    Swal.fire({
                        title: 'Eliminar Período',
                        text: `¿Está seguro de eliminar el período "${nombrePeriodo}"? Esta acción marcará el período como inactivo.`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = `{{ url('periodos') }}/${periodoId}`;

                            const methodField = document.createElement('input');
                            methodField.type = 'hidden';
                            methodField.name = '_method';
                            methodField.value = 'DELETE';

                            const csrfField = document.createElement('input');
                            csrfField.type = 'hidden';
                            csrfField.name = '_token';
                            csrfField.value = '{{ csrf_token() }}';

                            form.appendChild(methodField);
                            form.appendChild(csrfField);
                            document.body.appendChild(form);
                            form.submit();
                        }
                    });
                }
                </script>
            </div>
        </div>
    </div>

    <!-- Información de Tipos de Período -->
    <div class="row mt-4 ml-1 mr-1">
        <div class="col-12">
            <div class="box_block">
                <button class="btn btn-block text-left rounded-0 btn_header header_6" type="button" data-toggle="collapse" data-target="#collapseInformacion" aria-expanded="false" aria-controls="collapseInformacion" style="background: #28a745 !important; font-weight: bold; color: white;">
                    <i class="fas fa-info-circle m-1"></i>&nbsp;Información de Tipos de Período
                    <div class="float-right"><i class="fas fa-chevron-down"></i></div>
                </button>
                <div class="collapse" id="collapseInformacion">
                    <div class="card card-body rounded-0 border-0 pt-2 pb-2" style="background: transparent;">
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="text-primary mb-3"><i class="fas fa-list"></i> Tipos de Período Disponibles</h5>
                                <div class="list-group">
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong class="text-info">PREINSCRIPCION</strong>
                                            <br><small class="text-muted">Período para pre-inscripciones anticipadas</small>
                                        </div>
                                        <span class="badge badge-info">Pre-matrícula</span>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong class="text-success">INSCRIPCION</strong>
                                            <br><small class="text-muted">Período regular de inscripciones</small>
                                        </div>
                                        <span class="badge badge-success">Inscripción</span>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong class="text-primary">MATRICULA</strong>
                                            <br><small class="text-muted">Período de matrículas oficiales</small>
                                        </div>
                                        <span class="badge badge-primary">Matrícula</span>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong class="text-warning">ACADEMICO</strong>
                                            <br><small class="text-muted">Año académico regular</small>
                                        </div>
                                        <span class="badge badge-warning">Académico</span>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong class="text-secondary">CIERRE</strong>
                                            <br><small class="text-muted">Período de cierre y fin de año</small>
                                        </div>
                                        <span class="badge badge-secondary">Cierre</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h5 class="text-primary mb-3"><i class="fas fa-cogs"></i> Funcionalidades Automáticas</h5>
                                <div class="alert alert-info">
                                    <h6><i class="fas fa-lightbulb"></i> ¿Cómo funcionan los períodos?</h6>
                                    <ul class="mb-0">
                                        <li><strong>Control de Matrículas:</strong> Los períodos determinan cuándo se pueden crear pre-inscripciones y matrículas oficiales</li>
                                        <li><strong>Notificaciones:</strong> El sistema envía alertas automáticas cuando los períodos cambian</li>
                                        <li><strong>Descuentos:</strong> Los descuentos se aplican automáticamente según el período activo</li>
                                        <li><strong>Reportes:</strong> Los filtros de matrículas respetan los períodos configurados</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Animación de entrada */
    @keyframes slideInLeft {
        from { opacity: 0; transform: translateX(-50px);}
        to { opacity: 1; transform: translateX(0);}
    }
    .animate-slide-in { animation: slideInLeft 0.8s ease-out; }

    /* Tabla y paginación */
    #add-row td, #add-row th {
        padding: 8px 12px;
        font-size: 14px;
        vertical-align: middle;
        height: 60px;
    }
    .table-hover tbody tr:hover {
        background-color: #FFF4E7 !important;
    }
    .badge-success {
        background-color: #28a745;
        color: #fff;
    }
    .badge-secondary {
        background-color: #6c757d;
        color: #fff;
    }
    /* Paginación */
    .pagination {
        display: flex;
        justify-content: left;
        padding: 1rem 0;
        list-style: none;
        gap: 0.3rem;
    }
    .pagination li a, .pagination li span {
        color: #0A8CB3;
        border: 1px solid #0A8CB3;
        padding: 6px 12px;
        border-radius: 4px;
        text-decoration: none;
        transition: all 0.2s ease;
        font-size: 0.9rem;
    }
    .pagination li a:hover, .pagination li span:hover {
        background-color: #f1f1f1;
        color: #333;
    }
    .pagination .page-item.active .page-link {
        background-color: #0A8CB3 !important;
        color: white !important;
        border-color: #0A8CB3 !important;
    }
    .pagination .disabled .page-link {
        color: #ccc;
        border-color: #ccc;
    }
    /* Botón header estilo estudiantes */
    .btn_header.header_6 {
        margin-bottom: 0;
        border-radius: 0;
        font-size: 1.1rem;
        padding: 0.75rem 1rem;
        background: #0A8CB3 !important;
        color: white;
        border: none;
        box-shadow: none;
    }
    .btn_header .float-right {
        float: right;
    }
    .btn_header i.fas.fa-chevron-down,
    .btn_header i.fas.fa-chevron-up {
        transition: transform 0.2s;
    }

    /* Estadísticas Compactas */
    .stats-compact {
        border-radius: 8px !important;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
    }

    .stat-item {
        flex: 1;
        padding: 10px;
    }

    .stat-badge {
        font-size: 1.2rem !important;
        padding: 8px 12px !important;
        font-weight: bold !important;
        border-radius: 6px !important;
        display: inline-block;
        min-width: 50px;
        text-align: center;
    }

    /* Filtros */
    .filtro-input {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        transition: border-color 0.2s ease;
    }

    .filtro-input:focus {
        border-color: #0A8CB3;
        box-shadow: 0 0 0 0.2rem rgba(10, 139, 179, 0.25);
    }

    /* Lista de tipos de período */
    .list-group-item {
        border: 1px solid #dee2e6;
        border-radius: 8px !important;
        margin-bottom: 8px;
    }

    .list-group-item:last-child {
        margin-bottom: 0;
    }
</style>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Aplicar filtros
    $('#btnAplicarFiltros').on('click', function() {
        const estado = $('#estado').val();
        const tipo_periodo = $('#tipo_periodo').val();

        let url = '{{ route("periodos.index") }}';
        const params = [];

        if (estado) params.push('estado=' + estado);
        if (tipo_periodo) params.push('tipo_periodo=' + tipo_periodo);

        if (params.length > 0) {
            url += '?' + params.join('&');
        }

        window.location.href = url;
    });

    // Limpiar filtros
    $('#btnLimpiarFiltros').on('click', function() {
        window.location.href = '{{ route("periodos.index") }}';
    });

    // Toggle para información adicional
    const infoBtn = document.querySelector('[data-target="#collapseInformacion"]');
    const infoIcon = infoBtn.querySelector('.fas.fa-chevron-down');
    const infoCollapse = document.getElementById('collapseInformacion');

    infoCollapse.addEventListener('show.bs.collapse', function () {
        infoIcon.classList.remove('fa-chevron-down');
        infoIcon.classList.add('fa-chevron-up');
    });
    infoCollapse.addEventListener('hide.bs.collapse', function () {
        infoIcon.classList.remove('fa-chevron-up');
        infoIcon.classList.add('fa-chevron-down');
    });
});

// Mostrar alertas de SweetAlert2
@if (session('success'))
    Swal.fire({
        title: '¡Éxito!',
        text: '{{ session('success') }}',
        icon: 'success',
        confirmButtonColor: '#28a745',
        timer: 3000,
        timerProgressBar: true
    });
@endif

@if (session('error'))
    Swal.fire({
        title: 'Error',
        text: '{{ session('error') }}',
        icon: 'error',
        confirmButtonColor: '#dc3545'
    });
@endif
</script>
@endsection
