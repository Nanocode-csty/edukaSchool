@extends('cplantilla.bprincipal')
@section('titulo', 'Registro y listado de matrículas')
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
    </style>

    <div class="container-fluid margen-movil-2" id="contenido-principal" style="position: relative;">
        <div class="row mt-4 ml-1 mr-1">
            <div class="col-12 col-md-12 col-sm-12 col-lg-12 col-xl-12">
                <div class="box_block">
                    <div class="estilo-info btn btn-block text-left rounded-0 btn_header header_6" data-toggle=""
                        data-target="" aria-expanded="true" aria-controls=""
                        style="background: #368569 !important; font-weight: bold; color: white;">
                        <i class="fas fa-file-signature m-1"></i>&nbsp;Registro e historial de Matrículas

                    </div>

                    <div class="collapse show" id="collapseExample0">
                        <div class="card card-body rounded-0 border-0 pt-3 pb-4 text-center">



                            <div class="row g-3 justify-content-center">

                                <!-- Botón Registrar Matrícula -->
                                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                    <button id="btnRegistrarMatricula" class="btn btn-matricula w-100 h-100">
                                        <img src="{{ asset('imagenes/imgMatriculas/img1.png') }}" alt="Registrar Matrícula"
                                            class="icono-img">
                                        <span class="texto-boton">Registrar Matrícula</span>
                                    </button>
                                </div>

                                <!-- Botón Consulta de Matrículas -->
                                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                    <button id="btnConsultarMatricula" class="btn btn-matricula w-100 h-100">
                                        <img src="{{ asset('imagenes/imgMatriculas/img2.png') }}" alt="Consulta Matrículas"
                                            class="icono-img">
                                        <span class="texto-boton">Consulta de Matrículas</span>
                                    </button>
                                </div>

                                <style>
                                    .icono-img {
                                        max-width: 50%;
                                        /* La imagen nunca será más grande del 60% del ancho */
                                        max-height: 65px;
                                        /* Controla la altura para que quede proporcional */
                                        object-fit: contain;
                                        /* Ajuste al contenedor sin deformarse */
                                        margin-bottom: 10px;
                                    }

                                    .texto-boton {
                                        font-size: 1rem;
                                        font-weight: bold;
                                        font-family: "Quicksand", sans-serif;
                                        letter-spacing: 0.5px;
                                        text-align: center;
                                    }
                                </style>

                            </div>

                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const btnConsultar1 = document.getElementById('btnConsultarMatricula');
                                    const btnRegistrar1 = document.getElementById('btnRegistrarMatricula');


                                    // Modal al hacer clic en "Registrar docente"
                                    btnRegistrar1.addEventListener('click', function(e) {
                                        e.preventDefault();
                                        btnRegistrar1.classList.remove('btn-highlight');
                                        btnConsultar1.classList.add('btn-highlight');

                                    });

                                    btnConsultar1.addEventListener('click', function(e) {
                                        e.preventDefault();
                                        btnConsultar1.classList.remove('btn-highlight');
                                        btnRegistrar1.classList.add('btn-highlight');
                                    });


                                });
                            </script>


                            <style>
                                .btn-matricula {
                                    background-color: white;
                                    /* Color institucional de matrículas */
                                    color: #368569;
                                    font-weight: 600;
                                    border: 1px solid;
                                    border-radius: 10px;
                                    min-height: 145px;
                                    /* Altura fija */
                                    display: flex;
                                    flex-direction: column;
                                    align-items: center;
                                    justify-content: center;
                                    transition: all 0.3s ease;
                                    box-shadow: 0px 2px 7px #368569;
                                }

                                .btn-highlight {

                                    color: #e8e8e8 !important;
                                    border: 2px solid #e8e8e8 !important;
                                    font-weight: normal !important;
                                    transition: none !important;
                                    box-shadow: 0px 2px 7px #e8e8e8;
                                    transform: none !important;
                                    box-shadow: none !important;

                                }

                                /* Imagen en gris cuando el botón está deshabilitado */
                                .btn-highlight .icono-img {
                                    filter: grayscale(100%) brightness(0.7);
                                    opacity: 0.6;
                                    /* más tenue */
                                }

                                .btn-matricula:hover {

                                    transform: translateY(-2px) scale(1.01);
                                    box-shadow: 0px 6px 18px rgba(78, 70, 70, 0.25);
                                }

                                .icono-boton {
                                    font-size: 2.7rem;
                                    margin-bottom: 12px;
                                }

                                .texto-boton {
                                    font-size: 1rem;
                                    font-family: "Quicksand", sans-serif;
                                    letter-spacing: 0.5px;
                                }
                            </style>



                        </div>

                    </div>


                    <div id="spa-wrapper">
                        <div id="spa-content" class="card card-body">
                            <h4><span class="flecha">⬇</span> HOLA MUNDO</h4>
                        </div>
                    </div>

                    <style>
                        .flecha {
                            display: inline-block;
                            transition: transform .25s ease;
                        }

                        .flecha.rotar {
                            transform: rotate(180deg);
                        }

                        .fade-in {
                            animation: fadeIn .4s ease;
                        }

                        @keyframes fadeIn {
                            from {
                                opacity: 0;
                                transform: translateY(-6px);
                            }

                            to {
                                opacity: 1;
                                transform: translateY(0);
                            }
                        }
                    </style>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const btnRegistrar = document.getElementById('btnRegistrarMatricula');
                            const btnConsultar = document.getElementById('btnConsultarMatricula');
                            const spaContent = document.getElementById('spa-content');
                            const flecha = spaContent.querySelector('.flecha');

                            const urlNuevo = "{{ route('matriculas.create') }}";
                            const urlConsulta = "{{ route('matriculas.index') }}";

                            function load(url, rotate = false) {
                                spaContent.innerHTML =
                                    `<div class="d-flex justify-content-center py-5"><div class="spinner-border text-secondary" role="status"></div></div>`;
                                fetch(url, {
                                        headers: {
                                            'X-Requested-With': 'XMLHttpRequest'
                                        }
                                    })
                                    .then(r => r.ok ? r.text() : Promise.reject('error'))
                                    .then(html => {
                                        spaContent.innerHTML = html;
                                        spaContent.classList.add('fade-in');
                                        if (flecha) {
                                            if (rotate) flecha.classList.add('rotar');
                                            else flecha.classList.remove('rotar');
                                        }
                                        // Inicializa aquí scripts necesarios de la vista cargada:
                                        // if(window.initCmNuevo) window.initCmNuevo();
                                    })
                                    .catch(err => {
                                        spaContent.innerHTML =
                                            `<div class="alert alert-danger">Error al cargar contenido.</div>`;
                                        console.error(err);
                                    });
                            }

                            btnRegistrar.addEventListener('click', e => {
                                e.preventDefault();
                                load(urlNuevo, false);
                            });
                            btnConsultar.addEventListener('click', e => {
                                e.preventDefault();
                                load(urlConsulta, true);
                            });
                        });
                    </script>

                </div>
            </div>
            <br>
        </div>
    </div>



    <!-- Mensajes de notificación -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show position-fixed"
            style="top: 20px; right: 20px; z-index: 1050;" role="alert">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
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


@endsection

@section('scripts')
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
        document.addEventListener('DOMContentLoaded', function() {

            // Detecta el clic en cualquier link de paginación
            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault(); // Evita recargar toda la página

                let url = $(this).attr('href');

                // Si la página está en HTTPS, corregimos los enlaces HTTP -> HTTPS
                if (window.location.protocol === "https:") {
                    url = url.replace("http://", "https://");
                }

                let buscarpor = $('#inputBuscar').val();

                const loader = document.getElementById('loaderTablaMatriculas');
                const contenedor = document.getElementById('tabla-matriculas');

                $.ajax({
                    url: url,
                    type: 'GET',
                    data: {
                        buscarpor: buscarpor
                    },

                    beforeSend: function() {
                        // Muestra el loader y atenúa la tabla
                        loader.style.display = 'flex';
                        contenedor.style.opacity = '0.5';
                    },

                    success: function(data) {
                        // Actualiza el contenido de la tabla con los nuevos datos
                        $('#tabla-matriculas').html(data);
                    },

                    complete: function() {
                        // Oculta el loader y restaura opacidad
                        loader.style.display = 'none';
                        contenedor.style.opacity = '1';
                    },

                    error: function() {
                        alert('Ocurrió un error al cargar los datos.');
                    }
                });
            });
        });
    </script>

@endsection
