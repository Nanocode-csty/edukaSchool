@extends('cplantilla.bprincipal')
@section('titulo', 'Asignaciones Curso-Asignatura')
@section('contenidoplantilla')
    <style>
        .form-bordered {
            margin: 0;
            border: none;
            padding-top: 15px;
            padding-bottom: 15px;
            border-bottom: 1px dashed #eaedf1;
        }

        .card-body.info {
            background: #f3f3f3;
            border-bottom: 1px solid rgba(0, 0, 0, .125);
            border-top: 1px solid rgba(0, 0, 0, .125);
            color: #F59D24;
        }

        .card-body.info p {
            margin-bottom: 0px;
            font-family: "Quicksand", sans-serif;
            font-weight: 600;
            color: #004a92;
        }

        .estilo-info {
            margin-bottom: 0px;
            font-family: "Quicksand", sans-serif;
            font-weight: 700;
            font-size: clamp(0.9rem, 2.0vw, 0.9rem) !important;
        }

        .btn-primary {
            margin-top: 1rem;
            background: #007bff !important;
            border: none;
            transition: background-color 0.2s ease, transform 0.1s ease;
            margin-bottom: 0px;
            font-family: "Quicksand", sans-serif;
            font-weight: 700;
            font-size: clamp(0.9rem, 2.0vw, 0.9rem) !important;
        }

        .btn-primary:hover {
            background-color: #0056b3 !important;
            transform: scale(1.01);
        }

        .btn-action-group button,
        .btn-action-group a {
            margin-right: 5px;
        }

        .table-custom tbody tr:nth-of-type(odd) {
            background-color: #f5f5f5;
        }

        .table-custom tbody tr:nth-of-type(even) {
            background-color: #e0e0e0;
        }

        .table-hover tbody tr:hover {
            background-color: #eeffe7 !important;
        }

        .pagination {
            display: flex;
            justify-content: left;
            padding: 1rem 0;
            list-style: none;
            gap: 0.3rem;
        }

        .pagination li a,
        .pagination li span {
            color: var(--color-principal);
            border: 1px solid var(--color-principal);
            padding: 6px 12px;
            border-radius: 4px;
            text-decoration: none;
            transition: all 0.2s ease;
            font-size: 0.9rem;
        }

        .pagination li a:hover,
        .pagination li span:hover {
            background-color: #f1f1f1;
            color: #333;
        }

        .pagination .page-item.active .page-link {
            background-color: #0A8CB3 !important;
            color: white !important;
            border-color: #000000 !important;
        }

        .pagination .disabled .page-link {
            color: #ccc;
            border-color: #ccc;
        }

        .btn-action-group .btn-link {
            margin-right: 8px;
            padding: 0 6px;
            border: none;
            background: none;
            box-shadow: none;
        }

        .btn-action-group .btn-link:focus {
            outline: none;
            box-shadow: none;
        }

        #loaderPrincipal[style*="display: flex"] {
            display: flex !important;
            justify-content: center;
            align-items: center;
            position: absolute !important;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            width: 100%;
            height: 100%;
            z-index: 2000;
        }

        .badge-dia {
            padding: 0.4rem 0.6rem;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: 600;
        }
    </style>

    <div class="container-fluid" id="contenido-principal" style="position: relative;">
        @include('ccomponentes.loader', ['id' => 'loaderPrincipal'])

        <div class="row mt-4 ml-1 mr-1">
            <div class="col-12">
                <div class="box_block">
                    <button class="estilo-info btn btn-block text-left rounded-0 btn_header header_6" type="button"
                        data-toggle="collapse" data-target="#collapseExample0" aria-expanded="true"
                        aria-controls="collapseExample"
                        style="background: #0A8CB3 !important; font-weight: bold; color: white;">
                        <i class="fas fa-book-reader m-1"></i>&nbsp;Asignaciones Curso-Asignatura
                        <div class="float-right"><i class="fas fa-chevron-down"></i></div>
                    </button>

                    <div class="card-body info">
                        <div class="d-flex">
                            <div>
                                <i class="fas fa-exclamation-circle fa-2x"></i>
                            </div>
                            <div class="p-2 flex-fill">
                                <p>
                                    En esta sección, podrás asignar asignaturas a cursos específicos y vincularlas con
                                    profesores.
                                </p>
                                <p>
                                    Estimado Usuario: Asegúrate de revisar cuidadosamente los horarios y evitar conflictos
                                    en la programación académica.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="collapse show" id="collapseExample0">
                        <div class="card card-body rounded-0 border-0 pt-0 pb-2"
                            style="background-color: #fcfffc !important">

                            <!-- Botones y búsqueda -->
                            <div class="row align-items-center">
                                <div class="col-md-6 mb-md-0 d-flex justify-content-start">
                                    <a href="{{ route('cursoasignatura.create') }}" class="btn btn-primary w-100"
                                        id="nuevaAsignacionBtn" style="background: #007bff !important; border: none;">
                                        <i class="fa fa-plus mx-2"></i> Nueva Asignación
                                    </a>
                                </div>
                                <div class="col-md-6 d-flex justify-content-md-end justify-content-start estilo-info">
                                    <form id="formBuscar" method="GET" action="{{ route('cursoasignatura.index') }}"
                                        class="w-100" style="max-width: 100%;">
                                        <div class="input-group">
                                            <input name="buscarpor" id="inputBuscar" class="form-control mt-3"
                                                type="search" placeholder="Buscar por curso, asignatura o profesor"
                                                aria-label="Search" autocomplete="off" value="{{ request('buscarpor') }}"
                                                style="border-color: #007bff;">
                                            <button class="btn btn-primary nuevo-boton" type="submit"
                                                style="border-top-left-radius: 0 !important; border-bottom-left-radius: 0 !important; background: #007bff !important; border: none;">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            <div class="row form-bordered align-items-center"></div>

                            <!-- Tabla de asignaciones -->
                            <div class="table-responsive mt-2">
                                <table class="table-hover table table-custom text-center"
                                    style="border: 1px solid #0A8CB3; border-radius: 10px; overflow: hidden;">
                                    <thead class="table-hover estilo-info">
                                        <tr>
                                            <th scope="col">ID</th>
                                            <th scope="col">Curso</th>
                                            <th scope="col">Asignatura</th>
                                            <th scope="col">Profesor</th>
                                            <th scope="col">Día</th>
                                            <th scope="col">Horario</th>
                                            <th scope="col">Horas/Sem</th>
                                            <th scope="col">Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($items as $it)
                                            <tr>
                                                <td>{{ $it->curso_asignatura_id }}</td>
                                                <td>
                                                    <strong>{{ $it->curso->grado->nombre ?? 'Curso' }}
                                                        {{ $it->curso->seccion->nombre ?? '' }}</strong>
                                                    <br>
                                                    <small
                                                        class="text-muted">{{ $it->curso->anoLectivo->nombre ?? '' }}</small>
                                                </td>
                                                <td>
                                                    <strong
                                                        class="text-primary">{{ $it->asignatura->nombre ?? 'Sin asignatura' }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $it->asignatura->codigo ?? '' }}</small>
                                                </td>
                                                <td>
                                                    {{ $it->profesor->nombres ?? 'Sin' }}
                                                    {{ $it->profesor->apellidos ?? 'profesor' }}
                                                    <br>
                                                    <small
                                                        class="text-muted">{{ $it->profesor->especialidad ?? '' }}</small>
                                                </td>
                                                <td>
                                                    <span class="badge badge-dia"
                                                        style="background-color: {{ getDiaColor($it->dia_semana) }}; color: white;">
                                                        {{ $it->dia_semana }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <i class="far fa-clock"></i>
                                                    {{ substr($it->hora_inicio, 0, 5) }} -
                                                    {{ substr($it->hora_fin, 0, 5) }}
                                                </td>
                                                <td>
                                                    <span class="badge badge-info">{{ $it->horas_semanales }}h</span>
                                                </td>
                                                <td class="btn-action-group">
                                                    <a href="{{ route('cursoasignatura.show', $it->curso_asignatura_id) }}"
                                                        class="btn btn-link btn-sm p-0" title="Ver detalles">
                                                        <i class="fas fa-eye"
                                                            style="color: #17a2b8; font-size: 1.2rem;"></i>
                                                    </a>
                                                    <a href="{{ route('cursoasignatura.edit', $it->curso_asignatura_id) }}"
                                                        class="btn btn-link btn-sm p-0 btn-editar" title="Editar">
                                                        <i class="fas fa-pen"
                                                            style="color: #007bff; font-size: 1.2rem;"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-link btn-sm p-0 btn-eliminar"
                                                        data-id="{{ $it->curso_asignatura_id }}"
                                                        data-nombre="{{ $it->asignatura->nombre ?? 'esta asignación' }}"
                                                        data-sesiones="{{ \App\Models\SesionClase::where('curso_asignatura_id', $it->curso_asignatura_id)->count() }}"
                                                        data-asistencias="{{ \App\Models\AsistenciaAsignatura::where('curso_asignatura_id', $it->curso_asignatura_id)->count() }}"
                                                        title="Eliminar">
                                                        <i class="fas fa-times"
                                                            style="color: #dc3545; font-size: 1.3rem;"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center py-5">
                                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                                    <p class="text-muted">No hay asignaciones registradas</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Paginación -->
                            <div id="tabla-asignaciones">
                                {{ $items->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmación de Eliminación -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">
                        <i class="fas fa-exclamation-triangle"></i> Confirmar Eliminación
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <h6><i class="fas fa-exclamation-triangle"></i> ¡Advertencia!</h6>
                        <p>Esta acción eliminará permanentemente la asignación y todos los datos relacionados.</p>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <h6>Información de la Asignación:</h6>
                            <div id="assignment-info">
                                <!-- Se llenará dinámicamente -->
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6>Datos que se eliminarán:</h6>
                            <div id="deletion-info">
                                <!-- Se llenará dinámicamente -->
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="text-center">
                        <button type="button" class="btn btn-danger btn-lg" id="confirmDeleteBtn">
                            <i class="fas fa-trash"></i> Sí, Eliminar Definitivamente
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Form oculto para eliminar -->
    <form id="delete-form" action="" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
        <input type="checkbox" name="confirmar_eliminacion" value="1" checked style="display: none;">
    </form>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loader = document.getElementById('loaderPrincipal');
            const contenido = document.getElementById('contenido-principal');

            // Ocultar loader inicial
            if (loader) loader.style.display = 'none';
            if (contenido) contenido.style.opacity = '1';

            // Loader para ir a create (Nueva Asignación)
            const nuevaAsignacionBtn = document.getElementById('nuevaAsignacionBtn');
            if (nuevaAsignacionBtn) {
                nuevaAsignacionBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (loader) {
                        loader.style.display = 'flex';
                    }
                    setTimeout(() => {
                        window.location.href = this.href;
                    }, 800);
                });
            }

            // Búsqueda con loader
            document.getElementById('formBuscar').addEventListener('submit', function(e) {
                if (loader) {
                    loader.style.display = 'flex';
                }
            });

            // Loader para editar
            document.querySelectorAll('.btn-editar').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (loader) {
                        loader.style.display = 'flex';
                    }
                    setTimeout(() => {
                        window.location.href = this.href;
                    }, 800);
                });
            });

            // Loader para ir a asistencia
            document.querySelectorAll('.btn-asistencia').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (loader) {
                        loader.style.display = 'flex';
                    }
                    setTimeout(() => {
                        window.location.href = this.href;
                    }, 800);
                });
            });

            // Modal de eliminación
            let deleteId = null;

            document.querySelectorAll('.btn-eliminar').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();

                    const id = this.getAttribute('data-id');
                    const nombre = this.getAttribute('data-nombre');
                    const sesiones = parseInt(this.getAttribute('data-sesiones')) || 0;
                    const asistencias = parseInt(this.getAttribute('data-asistencias')) || 0;

                    deleteId = id;

                    // Llenar información de la asignación
                    document.getElementById('assignment-info').innerHTML = `
                        <p><strong>Asignatura:</strong> ${nombre}</p>
                        <p><strong>ID:</strong> ${id}</p>
                    `;

                    // Llenar información de eliminación
                    document.getElementById('deletion-info').innerHTML = `
                        <div class="list-group">
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                Sesiones de clase programadas
                                <span class="badge badge-danger badge-pill">${sesiones}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                Registros de asistencia
                                <span class="badge badge-danger badge-pill">${asistencias}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                Asignación de curso
                                <span class="badge badge-danger badge-pill">1</span>
                            </div>
                        </div>
                    `;

                    // Mostrar modal
                    $('#confirmDeleteModal').modal('show');
                });
            });

            // Confirmar eliminación desde el modal
            document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
                if (deleteId) {
                    // Cerrar modal
                    $('#confirmDeleteModal').modal('hide');

                    // Mostrar loader
                    if (loader) {
                        loader.style.display = 'flex';
                    }

                    // Enviar formulario
                    const form = document.getElementById('delete-form');
                    form.action = `/curso-asignatura/${deleteId}`;
                    form.submit();
                }
            });

            // Ocultar loader cuando vuelve con botón atrás
            window.addEventListener('pageshow', function(event) {
                if (loader) loader.style.display = 'none';
                if (contenido) contenido.style.opacity = '1';
            });
        });
    </script>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('error') }}',
                confirmButtonColor: '#dc3545'
            });
        </script>
    @endif

@endsection

@php
    function getDiaColor($dia)
    {
        return match ($dia) {
            'Lunes' => '#007bff',
            'Martes' => '#28a745',
            'Miércoles' => '#17a2b8',
            'Jueves' => '#ffc107',
            'Viernes' => '#dc3545',
            'Sábado' => '#6c757d',
            default => '#343a40',
        };
    }
@endphp
