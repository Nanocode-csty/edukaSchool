@extends('cplantilla.bprincipal')
@section('titulo', 'Registro y listado de pagos')
@section('contenidoplantilla')


    <style>
        @media (max-width: 576px) {
            .margen-movil {
                margin-left: -29px !important;
                margin-right: -29px !important;
            }

            .margen-movil-2 {
                margin: 0 !important;
                padding: 0 !important;
            }
        }

        .form-bordered {
            margin: 0;
            border: none;

            padding-bottom: 14px;
            border-bottom: 1.6px dashed #e5e6e0;
        }
    </style>

    <div class="container-fluid margen-movil-2">
        <div class="row mt-4 ml-1 mr-1">
            <div class="col-md-12">
                <div class="card">
                    <div class=" estilo-info btn btn-block text-left rounded-0 btn_header header_6" data-toggle=""
                        data-target="" aria-expanded="true" aria-controls=""
                        style="background: #ad920e !important; font-weight: bold; color:white;">
                        <i class="fas fa-file-signature m-1"></i>&nbsp;Historial de Pagos Registrados

                    </div>

                    <div class="card-body">
                        <!-- Alert Interactiva -->
                        <div class="alert alert-interactive" id="alertMatricula">
                            <i class="fas fa-info-circle me-2"></i>
                            <div class="alert-content">
                                <strong>En esta sección podrás consultar matrículas del periodo actual.</strong>
                                <br>
                                <span>
                                    <b>Estimado Usuario:</b> Asegúrate de revisar cuidadosamente los datos antes de
                                    guardarlos. Esta información será utilizada para la gestión académica y administrativa
                                    del estudiante. Cualquier modificación posterior debe realizarse con responsabilidad y
                                    siguiendo los protocolos establecidos por la institución.
                                </span>
                            </div>
                            <button class="btn-close" onclick="cerrarAlert('alertMatricula')">&times;</button>

                        </div>

                        <style>
                            /* Contenedor de la alerta */
                            .alert-interactive {
                                position: relative;
                                border-left: 6px solid #ad920e;
                                /* verde Eduka */
                                background-color: #e6ecc622;
                                color: #3e4441;
                                font-family: 'Quicksand', sans-serif;
                                font-size: clamp(0.85rem, 1.0vw, 0.95rem);
                                padding: 1rem 1.2rem 1rem 1rem;
                                border-radius: 3px;
                                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
                                display: flex;
                                align-items: flex-start;
                                gap: 0.8rem;
                                animation: fadeIn 0.5s ease-out forwards;
                                margin-bottom: 1rem;
                            }

                            .alert-interactive i {
                                font-size: 1.2rem;
                                margin-top: 2px;
                                flex-shrink: 0;
                            }

                            .alert-interactive .alert-content p {
                                margin: 0.3rem 0 0 0;
                                line-height: 1.5;
                            }

                            /* X flotante */
                            .alert-interactive .btn-close {
                                position: absolute;
                                top: -10px;
                                right: -10px;
                                background: #ad920e;
                                border: none;
                                font-size: 1.4rem;
                                cursor: pointer;
                                color: #ffffff;
                                transition: transform 0.2s, background 0.2s;
                                border-radius: 30%;
                                width: 28px;
                                height: 28px;
                                display: flex;
                                justify-content: center;
                                align-items: center;
                                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
                            }

                            .alert-interactive .btn-close:hover {
                                color: #f2f2f2;
                                transform: translateY(-1px);
                            }

                            /* Animación entrada */
                            @keyframes fadeIn {
                                0% {
                                    opacity: 0;
                                    transform: translateY(-10px);
                                }

                                100% {
                                    opacity: 1;
                                    transform: translateY(0);
                                }
                            }

                            /* Animación salida */
                            @keyframes fadeOut {
                                0% {
                                    opacity: 1;
                                    transform: translateY(0);
                                }

                                100% {
                                    opacity: 0;
                                    transform: translateY(-10px);
                                }
                            }
                        </style>



                        <!-- Sección de búsqueda y botón de registro -->
                        <div class="row mb-4 align-items-center ">
                            <!-- Dropdown de filtros -->
                            <div class="col-md-6 mb-md-0 d-flex justify-content-start mt-3">
                                <div class="dropdown w-100">
                                    <button
                                        class="btn btn-outline-success w-100 fw-bold d-flex justify-content-between align-items-center"
                                        type="button" id="dropdownFiltro" data-bs-toggle="dropdown" aria-expanded="false"
                                        style="border-color: #a38a0d; color:#a38a0d">
                                        <i class="fas fa-filter me-2 mx-2"></i> Filtrar / Ordenar Pagos

                                    </button>

                                    <style>
                                        /* 🎨 Estilos personalizados para el dropdown */
                                        .dropdown-menu.custom-dropdown {
                                            border-radius: 1rem;
                                            padding: 0.5rem;
                                            background-color: #fff;
                                            border: 1px solid #e3e6f0;
                                        }

                                        .dropdown-menu.custom-dropdown .dropdown-item {
                                            display: flex;
                                            align-items: center;
                                            padding: 0.6rem 1rem;
                                            border-radius: 0.5rem;
                                            transition: all 0.2s ease;
                                        }

                                        .dropdown-menu.custom-dropdown .dropdown-item i {
                                            font-size: 1rem;
                                            opacity: 0.85;
                                        }

                                        .dropdown-menu.custom-dropdown .dropdown-item:hover {
                                            background-color: #f9f8f1;
                                            transform: translateX(3px);
                                        }

                                        .dropdown-menu.custom-dropdown hr {
                                            margin: 0.4rem 0;
                                        }
                                    </style>

                                    <ul class="dropdown-menu shadow w-100 custom-dropdown" aria-labelledby="dropdownFiltro">
                                        <!-- 🏫 Nivel educativo -->
                                        <li>
                                            <a class="dropdown-item filtro-opcion" data-valor="mat_primaria">
                                                <i class="fas fa-school text-primary me-2 mr-2"></i> Nivel: Primario
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item filtro-opcion" data-valor="mat_secu">
                                                <i class="fas fa-university text-info me-2 mr-2"></i> Nivel: Secundario
                                            </a>
                                        </li>

                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>

                                        <!-- 📅 Filtro por fecha -->
                                        <li>
                                            <a class="dropdown-item filtro-opcion" data-valor="fecha_desc">
                                                <i class="fas fa-arrow-down-short-wide text-success me-2 mr-2"></i> Fecha: Más
                                                reciente
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item filtro-opcion" data-valor="fecha_asc">
                                                <i class="fas fa-arrow-up-wide-short text-warning me-2 mr-2"></i> Fecha: Más
                                                antigua
                                            </a>
                                        </li>

                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>

                                        <!-- 💰 Filtro por estado de pago -->
                                        <li>
                                            <a class="dropdown-item filtro-opcion" data-valor="pendiente">
                                                <i class="fas fa-clock text-warning me-2 mr-2"></i> Estado: Pendiente de Pago
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item filtro-opcion" data-valor="pagado">
                                                <i class="fas fa-check-circle text-success me-2 mr-2"></i> Estado: Pagado
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item filtro-opcion" data-valor="sin_pago">
                                                <i class="fas fa-times-circle text-danger me-2 mr-2"></i> Estado: Sin Pago
                                            </a>
                                        </li>
                                    </ul>




                                </div>
                            </div>


                            <div class="col-md-6 d-flex justify-content-md-end justify-content-start estilo-info">
                                <form method="GET" action="{{ route('matriculas.index') }}" class="w-100"

                                    style="max-width: 100%;">
                                    <div class="input-group">
                                        <input id="buscarpor" type="text" class="form-control mt-3" name="buscarpor"
                                            autocomplete="off" placeholder="Buscar por número de matrícula"
                                            value="{{ $buscarpor }}" aria-label="Buscar por número de matrícula"
                                            style=" border-color: #a38a0d">
                                        <button class="btn btn-primary" type="submit"
                                            style="border:none; background-color: #a38a0d !important; border-top-left-radius: 0 !important; border-bottom-left-radius: 0 !important;">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <style>
                            .btn-primary {
                                margin-top: 1rem;
                                background: #F6820E !important;
                                border: none;
                                transition: background-color 0.2s ease, transform 0.5s ease;
                                margin-bottom: 0px;
                                font-family: "Quicksand", sans-serif;
                                font-weight: 700;
                                font-size: clamp(0.9rem, 2.0vw, 0.9rem) !important;

                            }

                            .btn-primary:hover {
                                background-color: #F59619 !important;
                                transform: scale(1.007);
                            }

                            /* Dropdown moderno */
                            .dropdown-toggle::after {
                                margin-left: auto;
                            }

                            #dropdownFiltro:hover {
                                background-color: #a38a0d !important;
                                color: white !important;
                            }

                            .dropdown-item i {
                                color: #a38a0d;
                            }

                            .dropdown-item:hover i {
                                color: #7b531f;
                            }
                        </style>

                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                // Función para prevenir espacios
                                function evitarEspacios(input) {
                                    input.addEventListener('keydown', function(e) {
                                        if (e.key === ' ') {
                                            e.preventDefault();
                                        }
                                    });
                                }

                                // Lista de IDs a los que se les aplicará la validación
                                const sinEspacios = [
                                    'buscarpor',
                                ];

                                // Aplicamos la función a todos los elementos por ID
                                sinEspacios.forEach(id => {
                                    const input = document.getElementById(id);
                                    if (input) {
                                        evitarEspacios(input);
                                    }
                                });


                            });
                        </script>

                        <div class="row form-bordered align-items-center"></div>

                        <!-- Tabla de matrículas -->
                        @if ($pagos->count() > 0)
                            <div class="table-responsive mt-2">
                                
                            </div>

                            <div id="tabla-pagos" style="position: relative;">
                                @include('cpagos.pagos.pagos', [
                                    'pagos' => $pagos,
                                ])
                            </div>
                        @else
                            <div class="alert alert-warning text-center">
                                <i class="fas fa-exclamation-triangle"></i>
                                <strong>No se encontraron matrículas registradas.</strong>
                                @if ($buscarpor)
                                    <br>No hay resultados para la búsqueda "{{ $buscarpor }}".
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mensajes de notificación -->
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success', // más serio que 'info' para este tipo de aviso
                    title: 'Matricula Registrada',
                    text: "{{ session('success') }}",
                    confirmButtonColor: '#347f65',
                    confirmButtonText: 'Aceptar',
                    showConfirmButton: false,
                    timer: 4000
                });
            });
        </script>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show position-fixed"
            style="top: 20px; right: 20px; z-index: 1050;" role="alert">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif


    <style>
        /* Botón dropdown moderno */
        .dropdown-toggle::after {
            margin-left: auto;
        }

        .dropdown-item i {
            color: #a38a0d;
        }

        .dropdown-item:hover i {
            color: rgb(83, 69, 50);
            transition: all 0.7s ease;
        }

        @media (max-width: 576px) {
            .margen-movil {
                margin-left: -29px !important;
                margin-right: -29px !important;
            }

            .margen-movil-2 {
                margin: 0 !important;
                padding: 0 !important;
            }
        }

        .estilo-info {
            margin-bottom: 0px;
            font-family: "Quicksand", sans-serif;
            font-weight: 700;
            font-size: clamp(0.9rem, 2.0vw, 0.9rem) !important;

        }
    </style>

    <style>
        /* Paginación */
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
            background-color: #489077bc;
            /* Color gris cuando el cursor pasa por encima */
            color: white;
        }

        /* Páginas activas con fondo negro */
        .pagination .page-item.active .page-link {
            background-color: #368569 !important;
            /* Fondo negro para la página activa */
            color: white !important;
            /* Texto blanco en la página activa */
            border-color: #000000 !important;
            /* Borde negro */
        }

        /* Páginas deshabilitadas */
        .pagination .disabled .page-link {
            color: #ccc;
            border-color: #ccc;
        }
    </style>

@endsection

@section('scripts')


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Cierra la alerta manualmente
        function cerrarAlert(id) {
            const alert = document.getElementById(id);
            alert.style.animation = 'fadeOut 0.5s forwards';
            setTimeout(() => alert.remove(), 500);
        }

        /*/ Auto-hide después de 8 segundos
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                const alert = document.getElementById('alertMatricula');
                if (alert) cerrarAlert('alertMatricula');
            }, 8000);
        }); */
    </script>
    <script>
        // Auto-hide success and error messages
        setTimeout(function() {
            $('.alert-success, .alert-danger').fadeOut();
        }, 5000);

        // Función para confirmar anulación
        function confirmarAnulacion(numeroMatricula) {
            return confirm('¿Estás seguro de que deseas ANULAR la matrícula N° ' + numeroMatricula +
                '?\n\nUna matrícula anulada no se puede reactivar.');
        }
    </script>



    <script>
        $(document).ready(function() {

            // ------------------------------
            // Variables principales
            // ------------------------------
            let filtroValor =
                ''; // Guarda el filtro seleccionado en el dropdown (ej: "pendiente", "pagado", "fecha_desc", etc.)
            const tabla = $(
                '#tabla-pagos'
            ); // Contenedor principal donde se mostrará la tabla y se actualizará dinámicamente
            const btnDropdown = $('#dropdownFiltro'); // Botón del dropdown

            // ------------------------------
            // Función para inicializar los dropdowns dinámicos de Bootstrap
            // ------------------------------
            function initDropdowns() {
                document.querySelectorAll('#tabla-pagos .dropdown-toggle').forEach(function(el) {
                    if (!el._bsDropdown) {
                        new bootstrap.Dropdown(el, {
                            autoClose: 'outside'
                        });
                    }
                });
            }

            // ------------------------------
            // URL base para AJAX
            // ------------------------------
            const url = window.location.origin + '/pagos'; // Ajusta '/matriculas' según tu ruta real

            // ------------------------------
            // Función principal: actualizarTabla()
            // ------------------------------
            function actualizarTabla(page = 1) {
                const buscar = $('#buscarpor').val();
                const loader = document.getElementById('loaderTablaPagos');

                $.ajax({
                    url: url,
                    type: 'GET',
                    data: {
                        buscarpor: buscar,
                        filtro: filtroValor,
                        page: page
                    },
                    beforeSend: function() {
                        loader.style.display = 'flex';
                        tabla.css('opacity', '0.3');
                    },
                    success: function(data) {
                        tabla.html(data);
                        tabla.css('opacity', '1');
                        loader.style.display = 'none';
                        initDropdowns();

                        $('#tabla-pagos tbody tr').each(function() {
                            const tr = $(this);
                            tr.hide().fadeIn(400);
                            const estado = tr.find('td:nth-child(5)').text().trim()
                                .toUpperCase();
                            tr.removeClass('estado-pendiente estado-pagado');
                            if (estado === 'PENDIENTE') tr.addClass('estado-pendiente');
                            if (estado === 'PAGADO') tr.addClass('estado-matripagadoculado');
                        });

                        if (filtroValor === 'fecha_desc') {
                            btnDropdown.html(
                                '<i class="fas fa-calendar-alt me-2 mx-2"></i> Ordenado por Fecha: Más reciente'
                            );
                        } else if (filtroValor === 'fecha_asc') {
                            btnDropdown.html(
                                '<i class="fas fa-calendar-alt me-2 mx-2"></i> Ordenado por Fecha: Más antiguo'
                            );
                        } else if (filtroValor === 'pendiente') {
                            btnDropdown.html(
                                '<i class="fas fa-user-clock me-2 mx-2"></i> Filtrando por Estado: Pendiente de Pago'
                            );
                        } else if (filtroValor === 'pagado') {
                            btnDropdown.html(
                                '<i class="fas fa-user-check me-2 mx-2"></i> Filtrando por Estado: Pagado'
                            );
                        } else if (filtroValor === 'mat_primaria') {
                            btnDropdown.html(
                                '<i class="fas fa-user-clock me-2 mx-2"></i> Filtrando por Nivel: Primaria'
                            );
                        } else if (filtroValor === 'mat_secu') {
                            btnDropdown.html(
                                '<i class="fas fa-user-check me-2 mx-2"></i> Filtrando por Nivel: Secundaria'
                            );
                        } else if (filtroValor === 'sin_pago') {
                            btnDropdown.html(
                                '<i class="fas fa-user-check me-2 mx-2"></i> Filtrando por Estado: SIN PAGAR'
                            );
                        } else {
                            btnDropdown.html(
                                '<i class="fas fa-filter me-2 mx-2"></i> Filtrar / Ordenar Pagos'
                            );
                        }
                    },
                    error: function() {
                        alert('Error al cargar las matrículas.');
                        tabla.css('opacity', '1');
                        loader.style.display = 'none';
                    }
                });
            }

            // ------------------------------
            // Eventos
            // ------------------------------
            $(document).on('submit', '#formBuscarMatricula', function(e) {
                e.preventDefault();
                actualizarTabla();
            });

            $(document).on('click', '.filtro-opcion', function() {
                filtroValor = $(this).data('valor');
                actualizarTabla();
            });

            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                const page = $(this).attr('href').split('page=')[1];
                actualizarTabla(page);
            });

            // Inicializa dropdowns cuando se carga la página por primera vez
            initDropdowns();
        });
    </script>


@endsection
