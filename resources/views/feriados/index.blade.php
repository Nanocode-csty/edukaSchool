@extends('cplantilla.bprincipal')
@section('titulo', 'Gestión de Feriados')
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

        .badge-activo {
            padding: 0.4rem 0.6rem;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .badge-tipo {
            padding: 0.4rem 0.6rem;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .stats-card {
            background: #0A8CB3;
            color: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            border: 2px solid #86D2E3;
            box-shadow: 0 4px 6px rgba(10, 140, 179, 0.1);
        }

        .stats-card:nth-child(2) {
            background: #28a745;
            border-color: #20c997;
            box-shadow: 0 4px 6px rgba(40, 167, 69, 0.1);
        }

        .stats-card:nth-child(3) {
            background: #ffc107;
            color: #212529;
            border-color: #fd7e14;
            box-shadow: 0 4px 6px rgba(255, 193, 7, 0.1);
        }

        .stats-card:nth-child(4) {
            background: #dc3545;
            border-color: #e83e8c;
            box-shadow: 0 4px 6px rgba(220, 53, 69, 0.1);
        }

        .stats-card h3 {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .stats-card p {
            margin: 0;
            opacity: 0.9;
        }
    </style>

    <div class="container-fluid" id="contenido-principal" style="position: relative;">
        @include('ccomponentes.loader', ['id' => 'loaderPrincipal'])

        <div class="row mt-4 ml-1 mr-1">
            <div class="col-12">
                <div class="box_block">
                    <button class="estilo-info btn btn-block text-left rounded-0 btn_header header_6" type="button"
                        data-toggle="collapse" data-target="#collapseFeriados" aria-expanded="true"
                        aria-controls="collapseFeriados"
                        style="background: #0A8CB3 !important; font-weight: bold; color: white;">
                        <i class="fas fa-calendar-times m-1"></i>&nbsp;Gestión de Feriados
                        <div class="float-right"><i class="fas fa-chevron-down"></i></div>
                    </button>

                    <div class="card-body info">
                        <div class="d-flex">
                            <div>
                                <i class="fas fa-info-circle fa-2x"></i>
                            </div>
                            <div class="p-2 flex-fill">
                                <p>
                                    Administre los días feriados del sistema. Los feriados activos afectan la programación
                                    automática de clases y sesiones.
                                </p>
                                <p>
                                    <strong>Nota:</strong> Solo los administradores pueden gestionar feriados.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="collapse show" id="collapseFeriados">
                        <div class="card card-body rounded-0 border-0 pt-0 pb-2"
                            style="background-color: #fcfffc !important">

                            <!-- Estadísticas -->
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <div class="stats-card">
                                        <h3>{{ $totalFeriados }}</h3>
                                        <p>Total de Feriados</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="stats-card">
                                        <h3>{{ $feriadosActivos }}</h3>
                                        <p>Feriados Activos</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="stats-card">
                                        <h3>{{ $feriadosRecuperables }}</h3>
                                        <p>Feriados Recuperables</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="stats-card">
                                        <h3>{{ $feriadosEsteAnio }}</h3>
                                        <p>Feriados {{ date('Y') }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Botones y búsqueda -->
                            <div class="row align-items-center mb-3">
                                <div class="col-md-6 mb-md-0 d-flex justify-content-start">
                                    <a href="{{ route('feriados.create') }}" class="btn btn-primary w-100"
                                        id="nuevoFeriadoBtn" style="background: #007bff !important; border: none;">
                                        <i class="fa fa-plus mx-2"></i> Nuevo Feriado
                                    </a>
                                </div>
                                <div class="col-md-6 d-flex justify-content-md-end justify-content-start estilo-info">
                                    <form id="formBuscar" method="GET" action="{{ route('feriados.index') }}"
                                        class="w-100" style="max-width: 100%;">
                                        <div class="input-group">
                                            <input name="buscar" id="inputBuscar" class="form-control mt-3"
                                                type="search" placeholder="Buscar por nombre, descripción o tipo"
                                                aria-label="Search" autocomplete="off" value="{{ request('buscar') }}"
                                                style="border-color: #007bff;">
                                            <button class="btn btn-primary nuevo-boton" type="submit"
                                                style="border-top-left-radius: 0 !important; border-bottom-left-radius: 0 !important; background: #007bff !important; border: none;">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Filtros -->
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <select name="anio" class="form-control" onchange="filtrar()">
                                        <option value="">Todos los años</option>
                                        @for($i = date('Y') - 2; $i <= date('Y') + 2; $i++)
                                            <option value="{{ $i }}" {{ request('anio') == $i ? 'selected' : '' }}>
                                                Año {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <select name="tipo" class="form-control" onchange="filtrar()">
                                        <option value="">Todos los tipos</option>
                                        <option value="Nacional" {{ request('tipo') == 'Nacional' ? 'selected' : '' }}>Nacional</option>
                                        <option value="Regional" {{ request('tipo') == 'Regional' ? 'selected' : '' }}>Regional</option>
                                        <option value="Local" {{ request('tipo') == 'Local' ? 'selected' : '' }}>Local</option>
                                        <option value="Religioso" {{ request('tipo') == 'Religioso' ? 'selected' : '' }}>Religioso</option>
                                        <option value="Otro" {{ request('tipo') == 'Otro' ? 'selected' : '' }}>Otro</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <select name="activo" class="form-control" onchange="filtrar()">
                                        <option value="">Todos los estados</option>
                                        <option value="1" {{ request('activo') === '1' ? 'selected' : '' }}>Activos</option>
                                        <option value="0" {{ request('activo') === '0' ? 'selected' : '' }}>Inactivos</option>
                                    </select>
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

                            <!-- Tabla de feriados -->
                            <div class="table-responsive mt-2">
                                <table class="table-hover table table-custom text-center"
                                    style="border: 1px solid #0A8CB3; border-radius: 10px; overflow: hidden;">
                                    <thead class="table-hover estilo-info">
                                        <tr>
                                            <th scope="col">ID</th>
                                            <th scope="col">Nombre</th>
                                            <th scope="col">Fecha</th>
                                            <th scope="col">Tipo</th>
                                            <th scope="col">Recuperable</th>
                                            <th scope="col">Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($items as $item)
                                            <tr>
                                                <td>{{ $item->id }}</td>
                                                <td>
                                                    <strong class="text-primary">{{ $item->nombre }}</strong>
                                                    @if($item->descripcion)
                                                        <br>
                                                        <small class="text-muted">{{ Str::limit($item->descripcion, 50) }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <i class="far fa-calendar"></i>
                                                    {{ \Carbon\Carbon::parse($item->fecha)->format('d/m/Y') }}
                                                    <br>
                                                    <small class="text-muted">{{ \Carbon\Carbon::parse($item->fecha)->locale('es')->dayName }}</small>
                                                </td>
                                                <td>
                                                    <span class="badge badge-tipo"
                                                        style="background-color: {{ $item->tipo === 'Nacional' ? '#28a745' : ($item->tipo === 'Regional' ? '#ffc107' : '#6c757d') }}; color: white;">
                                                        {{ $item->tipo }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($item->recuperable)
                                                        <i class="fas fa-check text-success"></i> Sí
                                                    @else
                                                        <i class="fas fa-times text-muted"></i> No
                                                    @endif
                                                </td>
                                                <td class="btn-action-group">
                                                    <a href="{{ route('feriados.show', $item->id) }}"
                                                        class="btn btn-link btn-sm p-0" title="Ver detalles">
                                                        <i class="fas fa-eye" style="color: #17a2b8; font-size: 1.2rem;"></i>
                                                    </a>
                                                    <a href="{{ route('feriados.edit', $item->id) }}"
                                                        class="btn btn-link btn-sm p-0 btn-editar" title="Editar">
                                                        <i class="fas fa-pen" style="color: #007bff; font-size: 1.2rem;"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-link btn-sm p-0 btn-eliminar"
                                                        data-id="{{ $item->id }}"
                                                        data-nombre="{{ $item->nombre }}"
                                                        title="Eliminar">
                                                        <i class="fas fa-times" style="color: #dc3545; font-size: 1.3rem;"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-5">
                                                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                                    <p class="text-muted">No hay feriados registrados</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Paginación -->
                            <div id="tabla-feriados">
                                {{ $items->appends(request()->query())->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmación de Eliminación -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
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
                        <p>Esta acción eliminará permanentemente el feriado.</p>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <h6>Información del Feriado:</h6>
                            <div id="feriado-info">
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



    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loader = document.getElementById('loaderPrincipal');
            const contenido = document.getElementById('contenido-principal');

            // Ocultar loader inicial
            if (loader) loader.style.display = 'none';
            if (contenido) contenido.style.opacity = '1';

            // Loader para ir a create
            const nuevoFeriadoBtn = document.getElementById('nuevoFeriadoBtn');
            if (nuevoFeriadoBtn) {
                nuevoFeriadoBtn.addEventListener('click', function(e) {
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



            // Modal de eliminación
            let deleteId = null;

            document.querySelectorAll('.btn-eliminar').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();

                    const id = this.getAttribute('data-id');
                    const nombre = this.getAttribute('data-nombre');

                    deleteId = id;

                    // Llenar información del feriado
                    document.getElementById('feriado-info').innerHTML = `
                        <p><strong>Nombre:</strong> ${nombre}</p>
                        <p><strong>ID:</strong> ${id}</p>
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

                    // Crear formulario para eliminación
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/feriados/${deleteId}`;

                    // Agregar método DELETE
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    form.appendChild(methodInput);

                    // Agregar token CSRF
                    const tokenInput = document.createElement('input');
                    tokenInput.type = 'hidden';
                    tokenInput.name = '_token';
                    tokenInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    form.appendChild(tokenInput);

                    document.body.appendChild(form);
                    form.submit();
                }
            });

            // Ocultar loader cuando vuelve con botón atrás
            window.addEventListener('pageshow', function(event) {
                if (loader) loader.style.display = 'none';
                if (contenido) contenido.style.opacity = '1';
            });
        });

        function filtrar() {
            const anio = document.querySelector('select[name="anio"]').value;
            const tipo = document.querySelector('select[name="tipo"]').value;
            const activo = document.querySelector('select[name="activo"]').value;

            const url = new URL(window.location);
            if (anio) url.searchParams.set('anio', anio); else url.searchParams.delete('anio');
            if (tipo) url.searchParams.set('tipo', tipo); else url.searchParams.delete('tipo');
            if (activo !== '') url.searchParams.set('activo', activo); else url.searchParams.delete('activo');

            window.location.href = url.toString();
        }
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
