@extends('cplantilla.bprincipal')

@section('titulo', 'Administración de Asistencias')

@section('contenidoplantilla')
    <style>
        /* Minimalist Design System */
        :root {
            --primary: #2563eb;
            --primary-light: #dbeafe;
            --success: #16a34a;
            --success-light: #f0fdf4;
            --danger: #dc2626;
            --danger-light: #fef2f2;
            --warning: #d97706;
            --warning-light: #fffbeb;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
            --border-radius: 8px;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --transition: all 0.2s ease;
        }

        /* Clean Typography */
        .text-sm { font-size: 0.875rem; line-height: 1.25rem; }
        .text-base { font-size: 1rem; line-height: 1.5rem; }
        .text-lg { font-size: 1.125rem; line-height: 1.75rem; }
        .text-xl { font-size: 1.25rem; line-height: 1.75rem; }
        .text-2xl { font-size: 1.5rem; line-height: 2rem; }
        .font-medium { font-weight: 500; }
        .font-semibold { font-weight: 600; }
        .font-bold { font-weight: 700; }
        .text-gray-600 { color: var(--gray-600); }
        .text-gray-700 { color: var(--gray-700); }
        .text-gray-900 { color: var(--gray-900); }

        /* Clean Layout */
        .container-clean { max-width: 1200px; margin: 0 auto; padding: 0 1rem; }
        .space-y-4 > * + * { margin-top: 1rem; }
        .space-y-6 > * + * { margin-top: 1.5rem; }
        .space-y-8 > * + * { margin-top: 2rem; }

        /* Minimalist Cards */
        .card-clean {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--gray-200);
        }

        .card-header-clean {
            padding: 1.5rem;
            border-bottom: 1px solid var(--gray-200);
            background: var(--gray-50);
        }

        .card-body-clean {
            padding: 1.5rem;
        }

        /* Clean Stats */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .stat-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: var(--shadow);
            border: 1px solid var(--gray-200);
            transition: var(--transition);
        }

        .stat-card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-1px);
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 0.25rem;
        }

        .stat-label {
            font-size: 0.875rem;
            color: var(--gray-600);
            font-weight: 500;
        }

        .stat-icon {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin-bottom: 0.75rem;
        }

        .stat-icon.total { background: var(--primary); }
            color: white !important;
        }

        .select2-container--bootstrap4 .select2-results__option[aria-selected=true] {
            background-color: #E0F7FA !important;
            color: #0A8CB3 !important;
            font-weight: 500 !important;
        }

        .select2-container--bootstrap4 .select2-results__option--highlighted[aria-selected=true] {
            background-color: #0A8CB3 !important;
            color: white !important;
        }

        /* Estilos para el contenedor de búsqueda */
        .select2-search--dropdown .select2-search__field {
            border: 1px solid #DAA520 !important;
            border-radius: 0.25rem !important;
            padding: 4px 8px !important;
            font-size: 0.875rem !important;
        }

        .select2-search--dropdown .select2-search__field:focus {
            border-color: #0A8CB3 !important;
            box-shadow: 0 0 0 0.2rem rgba(10, 140, 179, 0.25) !important;
        }

        /* Mejorar el espaciado de las filas de filtros */
        .filter-section .row > div {
            margin-bottom: 1rem;
        }

        .filter-section .row > div:last-child {
            margin-bottom: 0;
        }

        /* Estilos para los botones de acción */
        .btn-group .btn {
            margin-right: 0.25rem;
        }

        .btn-group .btn:last-child {
            margin-right: 0;
        }
    </style>

    <div class="container-fluid estilo-info margen-movil-2">
        <div class="row mt-4 ml-1 mr-1">
            <div class="col-12 mb-3">
                <div class="box_block">
                    <button style="background: #0A8CB3 !important; border:none"
                        class="btn btn-primary btn-block text-left rounded-0 btn_header header_6 estilo-info" type="button"
                        data-toggle="collapse" data-target="#collapseAdminAsistencia" aria-expanded="true"
                        aria-controls="collapseAdminAsistencia">
                        <i class="fas fa-clipboard-check"></i>&nbsp;Administración de Asistencias
                        <div class="float-right"><i class="fas fa-chevron-down"></i></div>
                    </button>
                </div>
                <div class="collapse show" id="collapseAdminAsistencia">
                    <div class="card card-body rounded-0 border-0 pt-0"
                        style="padding-left:0.966666666rem;padding-right:0.9033333333333333rem;">
                        <div class="row margen-movil" style="padding:20px;">
                            <div class="col-12">

                                <!-- Estadísticas Rápidas -->
                                <div class="row mb-4">
                                    <div class="col-md-3">
                                        <div class="card stats-card text-center border-primary">
                                            <div class="card-body">
                                                <h5 class="card-title text-primary">
                                                    <i class="fas fa-list fa-2x"></i>
                                                </h5>
                                                <h3 class="text-primary">{{ number_format($estadisticas['total']) }}</h3>
                                                <p class="mb-0">Total Registros</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card stats-card text-center border-success">
                                            <div class="card-body">
                                                <h5 class="card-title text-success">
                                                    <i class="fas fa-check fa-2x"></i>
                                                </h5>
                                                <h3 class="text-success">{{ number_format($estadisticas['presentes']) }}</h3>
                                                <p class="mb-0">Presentes</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card stats-card text-center border-danger">
                                            <div class="card-body">
                                                <h5 class="card-title text-danger">
                                                    <i class="fas fa-times fa-2x"></i>
                                                </h5>
                                                <h3 class="text-danger">{{ number_format($estadisticas['ausentes']) }}</h3>
                                                <p class="mb-0">Ausentes</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card stats-card text-center border-warning">
                                            <div class="card-body">
                                                <h5 class="card-title text-warning">
                                                    <i class="fas fa-clock fa-2x"></i>
                                                </h5>
                                                <h3 class="text-warning">{{ number_format($estadisticas['tardanzas']) }}</h3>
                                                <p class="mb-0">Tardanzas</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Filtros -->
                                <div class="filter-section">
                                    <form method="GET" action="{{ route('asistencia.admin-index') }}" class="row g-3">
                                        <!-- Primera fila: Información básica -->
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="profesor_id" class="form-label estilo-info">
                                                        <i class="fas fa-user-tie mr-1"></i>Profesor
                                                    </label>
                                                    <select name="profesor_id" id="profesor_id" class="form-control form-control-sm">
                                                        <option value="">Todos los profesores</option>
                                                        @foreach($profesores as $profesor)
                                                            <option value="{{ $profesor->profesor_id }}" {{ ($filtros['profesor_id'] ?? '') == $profesor->profesor_id ? 'selected' : '' }}>
                                                                {{ $profesor->nombres }} {{ $profesor->apellidos }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="curso_id" class="form-label estilo-info">
                                                        <i class="fas fa-graduation-cap mr-1"></i>Curso
                                                    </label>
                                                    <select name="curso_id" id="curso_id" class="form-control form-control-sm">
                                                        <option value="">Todos los cursos</option>
                                                        @foreach($cursos as $curso)
                                                            <option value="{{ $curso->curso_id }}" {{ ($filtros['curso_id'] ?? '') == $curso->curso_id ? 'selected' : '' }}>
                                                                {{ $curso->grado->nombre }} - {{ $curso->seccion->nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="asignatura_id" class="form-label estilo-info">
                                                        <i class="fas fa-book mr-1"></i>Asignatura
                                                    </label>
                                                    <select name="asignatura_id" id="asignatura_id" class="form-control form-control-sm">
                                                        <option value="">Todas las asignaturas</option>
                                                        @foreach($asignaturas as $asignatura)
                                                            <option value="{{ $asignatura->asignatura_id }}" {{ ($filtros['asignatura_id'] ?? '') == $asignatura->asignatura_id ? 'selected' : '' }}>
                                                                {{ $asignatura->nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="tipo_asistencia_id" class="form-label estilo-info">
                                                        <i class="fas fa-check-circle mr-1"></i>Tipo Asistencia
                                                    </label>
                                                    <select name="tipo_asistencia_id" id="tipo_asistencia_id" class="form-control form-control-sm">
                                                        <option value="">Todos los tipos</option>
                                                        @foreach($tiposAsistencia as $tipo)
                                                            <option value="{{ $tipo->tipo_asistencia_id }}" {{ ($filtros['tipo_asistencia_id'] ?? '') == $tipo->tipo_asistencia_id ? 'selected' : '' }}>
                                                                {{ $tipo->nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Segunda fila: Fechas -->
                                        <div class="col-md-2">
                                            <label for="fecha_desde" class="form-label estilo-info">
                                                <i class="fas fa-calendar-alt mr-1"></i>Fecha Desde
                                            </label>
                                            <input type="date" name="fecha_desde" id="fecha_desde" class="form-control form-control-sm"
                                                   value="{{ $filtros['fecha_desde'] ?? '' }}">
                                        </div>

                                        <div class="col-md-2">
                                            <label for="fecha_hasta" class="form-label estilo-info">
                                                <i class="fas fa-calendar-alt mr-1"></i>Fecha Hasta
                                            </label>
                                            <input type="date" name="fecha_hasta" id="fecha_hasta" class="form-control form-control-sm"
                                                   value="{{ $filtros['fecha_hasta'] ?? '' }}">
                                        </div>

                                        <!-- Tercera fila: Búsqueda y ordenamiento -->
                                        <div class="col-md-4">
                                            <label for="buscar" class="form-label estilo-info">
                                                <i class="fas fa-search mr-1"></i>Buscar Estudiante
                                            </label>
                                            <input type="text" name="buscar" id="buscar" class="form-control form-control-sm"
                                                   placeholder="Nombre, apellido, DNI..." value="{{ $filtros['buscar'] ?? '' }}">
                                        </div>

                                        <div class="col-md-2">
                                            <label for="ordenar" class="form-label estilo-info">
                                                <i class="fas fa-sort mr-1"></i>Ordenar por
                                            </label>
                                            <select name="ordenar" id="ordenar" class="form-control form-control-sm">
                                                <option value="fecha" {{ ($ordenarPor ?? 'fecha') == 'fecha' ? 'selected' : '' }}>Fecha</option>
                                                <option value="estudiante" {{ ($ordenarPor ?? 'fecha') == 'estudiante' ? 'selected' : '' }}>Estudiante</option>
                                                <option value="profesor" {{ ($ordenarPor ?? 'fecha') == 'profesor' ? 'selected' : '' }}>Profesor</option>
                                                <option value="asignatura" {{ ($ordenarPor ?? 'fecha') == 'asignatura' ? 'selected' : '' }}>Asignatura</option>
                                            </select>
                                        </div>

                                        <div class="col-md-2">
                                            <label for="orden" class="form-label estilo-info">
                                                <i class="fas fa-arrow-up mr-1"></i>Orden
                                            </label>
                                            <select name="orden" id="orden" class="form-control form-control-sm">
                                                <option value="desc" {{ ($orden ?? 'desc') == 'desc' ? 'selected' : '' }}>Descendente</option>
                                                <option value="asc" {{ ($orden ?? 'desc') == 'asc' ? 'selected' : '' }}>Ascendente</option>
                                            </select>
                                        </div>

                                        <!-- Cuarta fila: Botones de acción -->
                                        <div class="col-12">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="btn-group" role="group">
                                                    <button type="submit" class="btn btn-primary btn-sm">
                                                        <i class="fas fa-search"></i> Filtrar
                                                    </button>
                                                    <a href="{{ route('asistencia.admin-index') }}" class="btn btn-secondary btn-sm">
                                                        <i class="fas fa-times"></i> Limpiar Filtros
                                                    </a>
                                                </div>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-info btn-sm" onclick="exportarPDF()">
                                                        <i class="fas fa-file-pdf"></i> Exportar PDF
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>

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
                                            <div class="table-responsive">
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
                                                                        <strong>{{ $asistencia->matricula->estudiante->nombres }}</strong><br>
                                                                        <small class="text-muted">{{ $asistencia->matricula->estudiante->apellidos }}</small><br>
                                                                        <small class="text-muted">DNI: {{ $asistencia->matricula->estudiante->dni }}</small>
                                                                    </div>
                                                                </td>

                                                                <td class="align-middle">
                                                                    <strong>{{ $asistencia->cursoAsignatura->asignatura->nombre }}</strong>
                                                                </td>

                                                                <td class="align-middle">
                                                                    <div>
                                                                        <strong>{{ $asistencia->cursoAsignatura->profesor->nombres }}</strong><br>
                                                                        <small class="text-muted">{{ $asistencia->cursoAsignatura->profesor->apellidos }}</small>
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
                                                                        <strong>{{ $asistencia->fecha->format('d/m/Y') }}</strong><br>
                                                                        <small class="text-muted">{{ $asistencia->fecha->format('l') }}</small>
                                                                    </div>
                                                                </td>

                                                                <td class="align-middle">
                                                                    <span class="badge badge-{{ $asistencia->tipoAsistencia->codigo == 'A' ? 'success' : ($asistencia->tipoAsistencia->codigo == 'F' ? 'danger' : ($asistencia->tipoAsistencia->codigo == 'T' ? 'warning' : 'secondary')) }} badge-estado">
                                                                        <i class="fas fa-{{ $asistencia->tipoAsistencia->codigo == 'A' ? 'check' : ($asistencia->tipoAsistencia->codigo == 'F' ? 'times' : ($asistencia->tipoAsistencia->codigo == 'T' ? 'clock' : 'file-alt')) }}"></i>
                                                                        {{ $asistencia->tipoAsistencia->nombre }}
                                                                    </span>
                                                                </td>

                                                                <td class="align-middle">
                                                                    <span class="badge badge-{{ $asistencia->estado == 'Registrada' ? 'success' : 'info' }} badge-estado">
                                                                        {{ $asistencia->estado }}
                                                                    </span>
                                                                </td>

                                                                <td class="align-middle">
                                                                    <div>
                                                                        <small class="text-muted">{{ $asistencia->hora_registro->format('H:i') }}</small><br>
                                                                        <small class="text-muted">{{ $asistencia->hora_registro->format('d/m/Y') }}</small>
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

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para ver justificación -->
    <div class="modal fade" id="justificacionModal" tabindex="-1" role="dialog" aria-labelledby="justificacionModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="justificacionModalLabel">Justificación de Asistencia</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="justificacionText"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .form-control {
            border: 1px solid #DAA520;
        }

        .table th {
            font-weight: 600;
            font-size: 0.875rem;
        }

        .table td {
            font-size: 0.875rem;
        }
    </style>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Inicializar Select2 para los selects con búsqueda
            $('.select-search').select2({
                placeholder: function() {
                    return $(this).data('placeholder') || 'Seleccionar...';
                },
                allowClear: true,
                width: '100%',
                theme: 'bootstrap4',
                language: {
                    noResults: function() {
                        return "No se encontraron resultados";
                    },
                    searching: function() {
                        return "Buscando...";
                    }
                }
            });

            // Configurar placeholders específicos
            $('#profesor_id').select2({
                placeholder: 'Buscar profesor...',
                allowClear: true
            });

            $('#curso_id').select2({
                placeholder: 'Buscar curso...',
                allowClear: true
            });

            $('#asignatura_id').select2({
                placeholder: 'Buscar asignatura...',
                allowClear: true
            });
        });

        function verJustificacion(justificacion) {
            document.getElementById('justificacionText').textContent = justificacion;
            $('#justificacionModal').modal('show');
        }



        function exportarPDF() {
            // Crear URL con los parámetros actuales
            const urlParams = new URLSearchParams(window.location.search);

            // Redirigir a la URL de exportación
            window.location.href = '{{ route("asistencia.exportar-pdf-admin") }}?' + urlParams.toString();
        }

        // Auto-submit del formulario cuando cambian los filtros principales
        document.getElementById('tipo_asistencia_id').addEventListener('change', function() {
            this.closest('form').submit();
        });

        // Filtros dependientes: curso y asignatura dependen del profesor
        $('#profesor_id').on('change', function() {
            const profesorId = $(this).val();

            // Limpiar y resetear selects dependientes usando Select2
            $('#curso_id').empty().append('<option value="">Todos los cursos</option>').trigger('change');
            $('#asignatura_id').empty().append('<option value="">Todas las asignaturas</option>').trigger('change');

            if (profesorId) {
                // Cargar cursos del profesor
                fetch(`/asistencia/api/cursos-por-profesor/${profesorId}`)
                    .then(response => response.json())
                    .then(data => {
                        console.log('Cursos cargados:', data); // Debug
                        data.forEach(curso => {
                            const option = new Option(`${curso.grado.nombre} - ${curso.seccion.nombre}`, curso.curso_id, false, false);
                            $('#curso_id').append(option);
                        });
                        $('#curso_id').trigger('change');
                    })
                    .catch(error => {
                        console.error('Error cargando cursos:', error);
                        alert('Error al cargar los cursos del profesor');
                    });

                // Cargar asignaturas del profesor
                fetch(`/asistencia/api/asignaturas-por-profesor/${profesorId}`)
                    .then(response => response.json())
                    .then(data => {
                        console.log('Asignaturas cargadas:', data); // Debug
                        data.forEach(asignatura => {
                            const option = new Option(asignatura.nombre, asignatura.asignatura_id, false, false);
                            $('#asignatura_id').append(option);
                        });
                        $('#asignatura_id').trigger('change');
                    })
                    .catch(error => {
                        console.error('Error cargando asignaturas:', error);
                        alert('Error al cargar las asignaturas del profesor');
                    });
            }
        });
    </script>
@endsection
