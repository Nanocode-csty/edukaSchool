<!DOCTYPE html>
<html lang="en">

<head>

    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>@yield('titulo', 'Inicio | Intranet Eduka Perú')</title>
    <link rel="icon" href="{{ asset('imagenes/imgLogo.png') }}" type="image/png">
    <link rel="shortcut icon" href="{{ asset('imagenes/imgLogo.png') }}" type="image/png">

    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS (local) -->
    <link rel="stylesheet" href="{{ asset('adminlte/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/assets/css/atlantis.min.css') }}">

    <!-- Font Awesome (local) -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.css') }}">

    <!-- Google Fonts (preload) -->
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- jQuery (local) -->
    <script src="{{ asset('adminlte/assets/js/core/jquery.3.2.1.min.js') }}"></script>

    <!-- SweetAlert2 (local fallback) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!--

<link href="{{ asset('pace/themes/nuna_int/css/style_asistencia.css') }}" rel="stylesheet">
<link href="{{ asset('pace/themes/nuna_int/plugins/alertifyjs/css/themes/bootstrap.css') }}" rel="stylesheet">
<link href="{{ asset('pace/themes/nuna_int/plugins/alertifyjs/css/alertify.css') }}" rel="stylesheet">
<link href="{{ asset('pace/themes/nuna_int/plugins/fancy/jquery.fancybox.css') }}" rel="stylesheet">
<link href="{{ asset('pace/themes/nuna_int/plugins/magnific/magnific-popup.css') }}" rel="stylesheet">
<link href="{{ asset('pace/themes/nuna_int/css/style_elements.css') }}" rel="stylesheet">
<link href="{{ asset('Content/themes/nuna_int/css/style_actividad.css') }}" rel="stylesheet">
<link href="{{ asset('Content/themes/nuna_int/css/style_ReporteSubvenciones.css') }}" rel="stylesheet">
 -->

    <!-- Script para manejar errores de recursos -->
    <script>
        // Manejar errores de carga de imágenes y recursos
        window.addEventListener('error', function(e) {
            // Solo registrar errores de recursos, no errores de JavaScript
            if (e.target && e.target.tagName) {
                console.warn('Recurso no cargado:', e.target.src || e.target.href);
                // No mostrar alertas al usuario, solo loggear
            }
        }, true);

        // Manejar errores de carga de scripts
        window.addEventListener('unhandledrejection', function(e) {
            console.warn('Error de promesa no manejada:', e.reason);
        });
    </script>

    <!--
    <script>
        (function(h, o, t, j, a, r) {
            h.hj = h.hj || function() {
                (h.hj.q = h.hj.q || []).push(arguments)
            };
            h._hjSettings = {
                hjid: 1431523,
                hjsv: 6
            };
            a = o.getElementsByTagName('head')[0];
            r = o.createElement('script');
            r.async = 1;
            r.src = t + h._hjSettings.hjid + j + h._hjSettings.hjsv;
            a.appendChild(r);
        })(window, document, 'https://static.hotjar.com/c/hotjar-', '.js?sv=');
    </script>
    <script async="" src="https://static.hotjar.com/c/hotjar-1431523.js?sv=6"></script>
     Hotjar Tracking Code for http://localhost:2254/ -->


    @stack('css-extra')
</head>

<body>

    <style>
        body {
            background-image: url("{{ asset('imagenes/imgFondoIntranet.png') }}");
            z-index: -1;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            background-attachment: fixed;
            font-family: "Quicksand", sans-serif !important;

        }

        /* Ensure sidebar toggle buttons are visible on all screen sizes */
        .topbar-toggler.more,
        .nav-toggle,
        .navbar-toggler.sidenav-toggler {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
            position: relative !important;
            z-index: 1000 !important;
            pointer-events: auto !important;
            margin-left: 10px !important;
        }

        /* Override Atlantis theme hiding behavior */
        .topbar-toggler.more.hide,
        .nav-toggle.hide,
        .navbar-toggler.sidenav-toggler.hide {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
        }

        /* Force show the 3 dots button specifically */
        .topbar-toggler.more {
            display: inline-block !important;
            visibility: visible !important;
            opacity: 1 !important;
            background: transparent !important;
            border: none !important;
            color: white !important;
            font-size: 18px !important;
            padding: 8px !important;
            cursor: pointer !important;
            position: relative !important;
            z-index: 1000 !important;
        }

        .topbar-toggler.more i {
            color: white !important;
        }

        /* Make sure the button is not hidden by Atlantis */
        .topbar-toggler.more.hide,
        .topbar-toggler.more[style*="display: none"],
        .topbar-toggler.more[style*="visibility: hidden"] {
            display: inline-block !important;
            visibility: visible !important;
            opacity: 1 !important;
        }

        /* Ensure logo doesn't overlap buttons */
        .logo-header {
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            position: relative !important;
        }

        .logo-header .logo {
            flex-shrink: 0 !important;
        }

        .logo-header .navbar-toggler,
        .logo-header .topbar-toggler,
        .logo-header .nav-toggle {
            flex-shrink: 0 !important;
            margin-left: auto !important;
        }

        body,
        input,
        button,
        select,
        textarea,
        a,
        td,
        li,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        br,
        h6 {
            font-family: "Quicksand", sans-serif !important;
        }

        p,
        th,
        strong {
            font-family: "Quicksand", sans-serif !important;
            font-weight: 800 !important;
            /* negrita ligera (puedes usar 700 para más intensidad) */

            /* ligeramente más grande que el tamaño base */
        }
    </style>


    <div class="wrapper overlay-sidebar">
        <div class="main-header">
            <!-- Logo Header -->
            <div class="logo-header" style="background-color: #0e4067;">

                <a class="logo" href="{{ route('rutarrr1') }}">

                    <img src="{{ asset('imagenes/Imagen1.png') }}" alt="Logo de la empresa" style="height: 70px;">
                </a>

                <button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse"
                    data-target="collapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon">
                        <i class="icon-menu"></i>
                    </span>
                </button>
                <button class="topbar-toggler more"><i class="icon-options-vertical"></i></button>
                <div class="nav-toggle">
                    <button class="btn btn-toggle sidenav-overlay-toggler toggled"><i
                            class="icon-options-vertical"></i></button>
                </div>


            </div>

            <!-- End Logo Header -->
            <style>
                .tooltip-wrapper {
                    position: relative;
                    display: inline-block;
                }

                .tooltip-wrapper .custom-tooltip {
                    visibility: hidden;
                    background-color: #DD1558;
                    color: white;
                    text-align: center;
                    border-radius: 6px;
                    padding: 6px 10px;
                    position: absolute;
                    top: 125%;
                    left: 50%;
                    transform: translateX(-50%);
                    white-space: nowrap;
                    opacity: 0;
                    transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
                    z-index: 9999;
                }

                .tooltip-wrapper .custom-tooltip::after {
                    content: "";
                    position: absolute;
                    top: -6px;
                    left: 50%;
                    transform: translateX(-50%);
                    border-width: 6px;
                    border-style: solid;
                    border-color: transparent transparent #DD1558 transparent;
                }

                .tooltip-wrapper:hover .custom-tooltip {
                    visibility: visible;
                    opacity: 1;
                    transform: translateX(-50%) translateY(2px);
                }
            </style>
            <!-- Navbar Header -->
            <nav class="navbar navbar-header navbar-expand-lg" style="background-color: #0e4067;">
                <div class="container-fluid">

                    <ul class="navbar-nav topbar-nav ml-md-auto align-items-center">
                        <li class="nav-item dropdown hidden-caret">
                            <div class="tooltip-wrapper">
                                <a class="nav-link evitar-recarga" href="{{ route('rutarrr1') }}">
                                    <i class="fas fa-home" style="color: white"></i>
                                </a>
                                <div class="custom-tooltip">Ir a inicio</div>
                            </div>
                        </li>

                        <style>
                            .nav-link:hover i {
                                color: #114d7b !important;
                                transition: color 0.3s ease;
                            }
                        </style>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                document.querySelectorAll('.evitar-recarga').forEach(link => {
                                    link.addEventListener('click', function(e) {
                                        const destino = this.href;
                                        const actual = window.location.href;

                                        if (actual === destino || actual.split('?')[0] === destino) {
                                            e.preventDefault();

                                            Swal.fire({
                                                icon: 'info',
                                                title: 'Ya estás aquí',
                                                text: 'Actualmente estás visualizando esta sección.',
                                                toast: true,
                                                position: 'top-end',
                                                showConfirmButton: false,
                                                timer: 1000,
                                                timerProgressBar: true
                                            });
                                        }
                                    });
                                });
                            });
                        </script>

                        <!-- BOTÓN DE APERTURA -->
                        <li class="nav-item dropdown hidden-caret">
                            <div class="tooltip-wrapper">
                                <a href="#correo" class="nav-link" onclick="handleContactClick(event)">
                                    <i class="fa fa-envelope" style="color: white"></i>
                                </a>
                                <div class="custom-tooltip">¿Necesitas ayuda?</div>
                            </div>
                        </li>

                        <script>
                            function handleContactClick(event) {
                                event.preventDefault(); // evita salto al hash

                                // Detectar tamaño de pantalla
                                const ancho = window.innerWidth;

                                // Si es móvil (menor a 768px), abrir mail
                                if (ancho < 1068) {
                                    window.location.href =
                                        'mailto:rcroblesro@unitru.edu.pe?subject=Soporte Eduka&body=Hola, necesito ayuda con...';
                                } else {
                                    // En pantallas grandes, abrir el modal normal
                                    abrirModalMensaje();
                                }
                            }
                        </script>


                        <!-- MODAL BOTÓN AYUDA-->
                        <div id="modalMensaje" class="modal-overlay" style="display: none;">
                            <div class="modal-content">
                                <a class="logo mb-3" style="text-align: center;">
                                    <img src="{{ asset('img_eduka.png') }}" alt="Logo de la empresa"
                                        style="height: 44px;">
                                </a>

                                <button class="cerrar" onclick="cerrarModalMensaje()">x</button>

                                <h2>
                                    Comunícate con nuestros Asesores
                                </h2>

                                <p class="text-center mb-2">
                                    Soy tu tío Eduka, tu asistente virtual de Eduka Perú. Estoy aquí para ayudarte con
                                    tus consultas sobre registros, reportes y demás.
                                </p>

                                <!-- Línea divisoria ajustada -->
                                <hr style="border: none; border-top: 2px solid #28AECE; margin: 8px 0;">

                                <form method="POST" action="{{ route('send.email') }}">
                                    @csrf
                                    <div class="form-group mb-1">
                                        <label for="name" class="font-weight-bold">Nombre</label>
                                        <input type="text" name="name" id="name" class="form-control"
                                            placeholder="Nombre de Usuario" autocomplete="off"
                                            value="{{ auth()->user()->nombres }}">
                                    </div>

                                    <div class="form-group mb-1">
                                        <label for="email" class="font-weight-bold">Correo electrónico</label>
                                        <input type="email" name="email" id="email" class="form-control"
                                            placeholder="tucorreo@ejemplo.com" autocomplete="off"
                                            value="{{ auth()->user()->email }}">
                                    </div>

                                    <div class="form-group mb-1">
                                        <label for="message" class="font-weight-bold">Mensaje</label>
                                        <textarea name="message" id="message" class="form-control" rows="5" placeholder="Escribe tu mensaje aquí"
                                            required></textarea>
                                    </div>

                                    <div class="modal-actions text-center">
                                        <button type="button" onclick="cerrarModalMensaje()"
                                            class="btn btn_blue">Cancelar</button>
                                        <button type="submit" class="btn btn_blue px-4 py-2"
                                            style="border-radius: 5px;">
                                            <i class="fas fa-paper-plane"></i> Enviar
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- ESTILOS -->
                        <style>
                            .modal-overlay {
                                position: fixed;
                                top: 0;
                                left: 0;
                                right: 0;
                                bottom: 0;
                                background: rgba(0, 0, 0, 0.5);
                                z-index: 99999;
                                display: flex;
                                justify-content: center;
                                align-items: center;
                                padding: 1rem;
                                overflow-y: auto;
                                width: auto;
                            }

                            .modal-content {
                                background: white;
                                width: auto;
                                max-width: 600px;
                                padding: 20px;
                                border-radius: 10px;
                                position: relative;
                                box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
                                font-family: "Quicksand", sans-serif;
                                z-index: 100000;
                            }

                            /* Responsive para móviles */
                            @media (max-width: 576px) {
                                .modal-content {
                                    padding: 15px;
                                }

                                .modal-content h2 {
                                    font-size: 18px;
                                }

                                .modal-content p {
                                    font-size: 14px;
                                }
                            }

                            .modal-content h2 {
                                font-size: 22px;
                                color: #28AECE;
                                margin-bottom: 15px;
                                font-weight: bold;
                                text-align: center;
                                font-family: "Quicksand", sans-serif;
                            }

                            .modal-content p {
                                font-size: 15px;
                                margin-bottom: 15px;
                                color: #5a5a5a;
                                text-align: center;
                                font-family: "Quicksand", sans-serif;
                            }

                            .modal-content label {
                                display: block;
                                margin-top: 10px;
                                font-weight: bold;
                            }

                            .modal-content input,
                            .modal-content textarea {
                                width: 100%;
                                padding: 8px;
                                margin-top: 2px;
                                border: 1px solid #F49414 !important;
                                border-radius: 5px;
                            }

                            .modal-actions {
                                display: flex;
                                justify-content: flex-end;
                                gap: 10px;
                                margin-top: 20px;
                            }

                            .modal-actions button:first-child {
                                background: #ccc;
                                color: white;
                            }

                            .modal-actions button:last-child {
                                background: #e91e63;
                                color: white;
                            }

                            .cerrar {
                                position: absolute;
                                top: 10px;
                                right: 15px;
                                border: none;
                                background: transparent;
                                font-size: 24px;
                                font-weight: bold;
                                cursor: pointer;
                                color: #aaa;
                            }
                        </style>

                        <!-- SCRIPTS -->
                        <script>
                            function abrirModalMensaje() {
                                document.getElementById("modalMensaje").style.display = "flex";
                                document.body.style.overflow = "hidden";
                            }

                            function cerrarModalMensaje() {
                                document.getElementById("modalMensaje").style.display = "none";
                                document.body.style.overflow = "auto";
                            }
                        </script>


                        <li class="nav-item dropdown hidden-caret d-none d-md-block">

                            <div class="tooltip-wrapper">
                                <a class="nav-link" data-toggle="dropdown" href="#frecuente" aria-expanded="false">
                                    <i class="fas fa-th-large" style="color: white"></i>
                                </a>

                                <div id="quick"
                                    class="dropdown-menu quick-actions quick-actions-primary animated fadeIn d-none">
                                    <div class="quick-actions-header" style="background: #e91e63;">
                                        <span class="title mb-1">Operaciones Frecuentes</span>
                                        <span class="subtitle op-8">Registre adecuadamente los datos</span>

                                        @if (auth()->user()->rol == 'Administrador')
                                            <script>
                                                document.getElementById('quick').classList.remove('d-none');
                                            </script>
                                        @endif
                                    </div>
                                    @if (auth()->user()->rol == 'Administrador')
                                        <div class="quick-actions-scroll scrollbar-outer">
                                            <div class="quick-actions-items">
                                                <div class="row m-0">
                                                    <a class="col-6 col-md-4 p-0"
                                                        href="{{ route('matriculas.create') }}">
                                                        <div class="quick-actions-item">
                                                            <i class="flaticon-file-1"></i>
                                                            <span class="text">Matrícula Regular</span>
                                                        </div>
                                                    </a>
                                                    <a class="col-6 col-md-4 p-0" href="{{ route('notas.inicio') }}">
                                                        <div class="quick-actions-item">
                                                            <i class="flaticon-pen"></i>
                                                            <span class="text">Registrar Notas</span>
                                                        </div>
                                                    </a>
                                                    <a class="col-6 col-md-4 p-0"
                                                        href="{{ route('notas.consulta') }}">
                                                        <div class="quick-actions-item">
                                                            <i class="flaticon-list"></i>
                                                            <span class="text">Ver Reporte de Notas</span>
                                                        </div>
                                                    </a>
                                                    <a class="col-6 col-md-4 p-0"
                                                        href="{{ route('registrarcurso.index') }}">
                                                        <div class="quick-actions-item">
                                                            <i class="flaticon-file-1"></i>
                                                            <span class="text">Registrar Cursos</span>
                                                        </div>
                                                    </a>
                                                    <a class="col-6 col-md-4 p-0"
                                                        href="{{ route('registrardocente.index') }}">
                                                        <div class="quick-actions-item">
                                                            <i class="flaticon-user-3"></i>
                                                            <span class="text">Registrar Docentes</span>
                                                        </div>
                                                    </a>
                                                    <a class="col-6 col-md-4 p-0"
                                                        href="{{ route('estudiante.index') }}">
                                                        <div class="quick-actions-item">
                                                            <i class="flaticon-user-1"></i>
                                                            <span class="text">Registrar Estudiantes</span>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="custom-tooltip">Operaciones Frecuentes</div>
                            </div>

                        </li>
                        <li class="nav-item dropdown hidden-caret">
                            <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#"
                                aria-expanded="false">
                                <div class="avatar-sm">
                                    <img src="{{ asset('adminlte/assets/img/profile.jpg') }}"
                                        class="avatar-img rounded-circle">
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-user animated fadeIn">
                                <div class="dropdown-user-scroll scrollbar-outer">
                                    <li>
                                        <div class="user-box">
                                            <div class="avatar-lg"><img
                                                    src="{{ asset('adminlte/assets/img/profile.jpg') }}"
                                                    class="avatar-img rounded"></div>
                                            <div class="u-text">
                                                <h4>{{ auth()->user()->nombres }}<br>{{ auth()->user()->apellidos }}
                                                </h4>

                                                <a class="badge fw-bold"
                                                    style="background-color: #0c639d !important; border: none; color:white;">{{ auth()->user()->username }}</a>
                                            </div>
                                        </div>

                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>

                                        <a class="dropdown-item" href="{{ route('perfil.index') }}">
                                            Ver Perfil
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>


                                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                            style="display: none;">
                                            @csrf
                                        </form>

                                        <a class="dropdown-item" href="#"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            Cerrar sesión
                                        </a>
                                    </li>
                                </div>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
            <!-- End Navbar -->
        </div>

        <!-- Sidebar -->
        <div class="sidebar sidebar-style-2">
            <div class="sidebar-wrapper scrollbar scrollbar-inner">
                <div class="sidebar-content">
                    <div class="user nuevo-user">
                        <div class="avatar-sm float-left mr-2">
                            <img src="{{ asset('imagenes/imgDocente.png') }}" alt="..."
                                class="avatar-img rounded-circle" style="border: 1px solid #4b4e51">
                        </div>
                        <div class="info">
                            <a data-toggle="collapse" aria-expanded="true">

                                <span>
                                    {{ auth()->user()->nombres }}
                                    <span class="user-level">{{ auth()->user()->rol }}</span>

                                </span>
                            </a>
                            <div class="clearfix"></div>
                        </div>
                    </div>

                    <ul class="nav nav-primary">

                        <!--
                        <li class="nav-item active">
                            <a data-toggle="collapse" href="#dashboard" class="collapsed" aria-expanded="false">
                                <i class="fas fa-home"></i>
                                <p>OPCIONES</p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse" id="dashboard">
                                <ul class="nav nav-collapse">
                                    <li>
                                        <a href="adminlte/demo1/index.html">
                                            <span class="sub-item">Matrículas</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="adminlte/demo2/index.html">
                                            <span class="sub-item">Notas</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="adminlte/demo2/index.html">
                                            <span class="sub-item">Asistencia</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li> -->

                        @if (auth()->user()->rol == 'Administrador')
                            <li class="nav-item active mt-3">
                                <a data-toggle="collapse" href="#matriculas1"
                                    class="collapsed d-flex align-items-center justify-content-between btn-matricula"
                                    aria-expanded="false"
                                    style="background-color: #347f65  !important; border-radius: 9px; color: white;  ">
                                    <div class="d-flex align-items-center">
                                        <i style="font-weight: bold" class="fas fa-user-graduate"></i>
                                        <p class="mb-0 ms-2">Matrículas</p>
                                    </div>
                                    <i class="fas fa-angle-down rotate-icon d-none "></i>
                                </a>

                                <div class="collapse" id="matriculas1">
                                    <ul class="nav nav-collapse">
                                        <li>
                                            <a href="{{ route('matriculas.create') }}">
                                                <span class="sub-item">Matrícula Regular</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('matriculas.index') }}">
                                                <span class="sub-item">Consultar Matrículas</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>

                            <li class="nav-item active mt-3">
                                <a data-toggle="collapse" href="#pagos"
                                    class="collapsed d-flex align-items-center justify-content-between"
                                    aria-expanded="false"
                                    style="background-color: #b68a39 !important; border-radius: 9px;">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-wallet"></i>
                                        <p>Pagos</p>
                                    </div>
                                    <i class="fas fa-angle-down rotate-icon d-none "></i>
                                </a>
                                <div class="collapse" id="pagos">
                                    <ul class="nav nav-collapse">
                                        <li>
                                            <a href="{{ route('conceptospago.index') }}">
                                                <span class="sub-item">Conceptos de Pagos</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('pagos.index') }}">
                                                <span class="sub-item">Administrar Pagos</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li class="nav-item active mt-3">
                                <a data-toggle="collapse" href="#asistencia-admin"
                                    class="collapsed d-flex align-items-center justify-content-between"
                                    aria-expanded="false"
                                    style="background-color: #3a6f92 !important ; border-radius: 9px;">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-calendar-check"></i>
                                        <p>Asistencias</p>
                                    </div>
                                    <!--<i class="fas fa-angle-down rotate-icon"></i>-->
                                </a>
                                <div class="collapse" id="asistencia-admin">
                                    <ul class="nav nav-collapse">
                                        <li>
                                            <a href="{{ route('asistencia.admin-index') }}">
                                                <span class="sub-item">Administrar Asistencias</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('asistencia.verificar') }}">
                                                <span class="sub-item">Justificaciones</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('asistencia.reporte-general') }}">
                                                <span class="sub-item">Reportes Generales</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        @endif
                        @if (auth()->user()->rol == 'Profesor')
                            <li class="nav-item active mt-3">
                                <a data-toggle="collapse" href="#asistencia"
                                    class="collapsed d-flex align-items-center justify-content-between"
                                    aria-expanded="false" style="background-color: #3a6f92 !important">
                                    <div class="d-flex align-items-center" style=" border-radius: 9px; ">
                                        <i class="fas fa-file-invoice-dollar me-2"></i>
                                        <p>Asistencias</p>
                                    </div>
                                    <!--<i class="fas fa-angle-down rotate-icon"></i>-->
                                </a>
                                <div class="collapse" id="asistencia">
                                    <ul class="nav nav-collapse">
                                        <li>
                                            <a href="{{ route('asistencia.index') }}">
                                                <span class="sub-item">Asistencia Diaria</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        @endif

                        <li class="nav-item active mt-3">
                            <a data-toggle="collapse" href="#notas"
                                class="collapsed d-flex align-items-center justify-content-between btn-nuevo"
                                aria-expanded="false"
                                style="background-color: #7a2e40 !important; border-radius: 9px; ">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-paste"></i>
                                    <p class="mb-0">Notas</p>
                                </div>
                                <i class="fas fa-angle-down rotate-icon d-none "></i>
                            </a>

                            <div class="collapse" id="notas">
                                <ul class="nav nav-collapse">
                                    @if (auth()->user()->rol == 'Administrador' || auth()->user()->rol == 'Profesor')
                                        <li>
                                            <a href="{{ route('notas.inicio') }}">
                                                <span class="sub-item">Registrar Notas</span>
                                            </a>
                                        </li>
                                    @endif
                                    @if (auth()->user()->rol == 'Representante')
                                        <li>
                                            <a href="{{ route('notas.misEstudiantes') }}">
                                                <span class="sub-item">Mis Estudiantes</span>
                                            </a>
                                        </li>
                                    @endif
                                    @if (auth()->user()->rol == 'Administrador')
                                        <li>
                                            <a href="{{ route('notas.consulta') }}">
                                                <span class="sub-item">Reporte de Notas</span>
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </li>

                        @if (auth()->user()->rol == 'Representante')
                            <li class="nav-item active mt-3">
                                <a data-toggle="collapse" href="#asistencia-representante"
                                    class="collapsed d-flex align-items-center justify-content-between"
                                    aria-expanded="false" style="background-color: #3a6f92 !important">
                                    <div class="d-flex align-items-center" style=" border-radius: 9px; ">
                                        <i class="fas fa-calendar-check me-2"></i>
                                        <p class="mb-0">Asistencia</p>
                                    </div>
                                    <!--<i class="fas fa-angle-down rotate-icon"></i>-->
                                </a>
                                <div class="collapse" id="asistencia-representante">
                                    <ul class="nav nav-collapse">
                                        <li>
                                            <a href="{{ route('asistencia.misEstudiantes') }}">
                                                <span class="sub-item">Mis Estudiantes</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        @endif
                        <style>
                            .rotate-icon {
                                transition: transform 0.3s ease;
                            }

                            a[aria-expanded="true"] .rotate-icon {
                                transform: rotate(180deg);
                            }
                        </style>
                        @if (auth()->user()->rol == 'Administrador')
                            <li class="nav-section">
                                <span class="sidebar-mini-icon">
                                    <i class="fa fa-ellipsis-h"></i>
                                </span>
                                <h4 class="text-section">OPCIONES</h4>
                            </li>


                            <li class="nav-item active mb-2">
                                <a data-toggle="collapse" href="#base" class="collapsed" aria-expanded="false"
                                    style="background-color: #a9a97d !important; border-color: #ccc; color: #333;">
                                    <i class="fas fa-school" style="color: #333;"></i>
                                    <p style="margin-bottom: 0;">Gestión Académica</p>
                                    <span class="caret"></span>
                                </a>

                                <div class="collapse" id="base">

                                    <ul class="nav nav-collapse">

                                        <li>
                                            <a href="{{ route('asignaturas.index') }}" class="evitar-recarga">
                                                <span class="sub-item">Asignaturas</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('periodos-evaluacion.index') }}"
                                                class="evitar-recarga">
                                                <span class="sub-item">Periodos de Evaluación</span>
                                            </a>
                                        </li>

                                        <li>
                                            <a href="{{ route('registrarcurso.index') }}" class="evitar-recarga">
                                                <span class="sub-item">Registrar Cursos</span>
                                            </a>
                                        </li>

                                    </ul>
                                </div>
                            </li>

                            <li class="nav-item active mb-2">
                                <a data-toggle="collapse" href="#Estudiantil" class="collapsed"
                                    aria-expanded="false"
                                    style="background-color: #a9a97d !important; border-color: #ccc; color: #333;">
                                    <i class="fas fa-user-graduate" style="color: white"></i>
                                    <p>Gestión Estudiantil</p>
                                    <span class="caret"></span>
                                </a>

                                <div class="collapse" id="Estudiantil">

                                    <ul class="nav nav-collapse">
                                        <li>
                                            <a href="{{ route('estudiante.index') }}" class="evitar-recarga">
                                                <span class="sub-item">Estudiantes</span>
                                            </a>
                                        </li>

                                        <li>
                                            <a href="{{ route('registrarrepresentante.index') }}"
                                                class="evitar-recarga">
                                                <span class="sub-item">Representantes</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li class="nav-item active mb-2">
                                <a data-toggle="collapse" href="#Personal" class="collapsed" aria-expanded="false"
                                    style="background-color: #a9a97d !important; border-color: #ccc; color: #333;">
                                    <i class="fas fa-briefcase" style="color: white"></i>
                                    <p>Gestión Docentes</p>
                                    <span class="caret"></span>
                                </a>

                                <div class="collapse" id="Personal">

                                    <ul class="nav nav-collapse">

                                        <li>
                                            <a href="{{ route('registrardocente.index') }}" class="evitar-recarga">
                                                <span class="sub-item">Docentes</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('cursoasignatura.index') }}" class="evitar-recarga">
                                                <span class="sub-item">Carga Académica</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>

                            </li>

                            <li class="nav-item active mb-2">
                                <a data-toggle="collapse" href="#Educativa" class="collapsed" aria-expanded="false"
                                    style="background-color: #a9a97d !important; border-color: #ccc; color: #333;">
                                    <i class="fas fa-door-open" style="color: white"></i>
                                    <p>Plan Educativo</p>
                                    <span class="caret"></span>
                                </a>

                                <div class="collapse" id="Educativa">

                                    <ul class="nav nav-collapse">
                                        <li>
                                            <a href="{{ route('registrarnivel.index') }}" class="evitar-recarga">
                                                <span class="sub-item">Niveles Educativos</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('aulas.index') }}" class="evitar-recarga">
                                                <span class="sub-item">Aulas</span>
                                            </a>
                                        </li>

                                        <li>
                                            <a href="{{ route('grados.index') }}" class="evitar-recarga">
                                                <span class="sub-item">Grados</span>
                                            </a>
                                        </li>

                                        <li>
                                            <a href="{{ route('secciones.index') }}" class="evitar-recarga">
                                                <span class="sub-item">Secciones</span>
                                            </a>
                                        </li>

                                        <li>
                                            <a href="{{ route('aniolectivo.index') }}" class="evitar-recarga">
                                                <span class="sub-item">Año Lectivo</span>
                                            </a>
                                        </li>

                                    </ul>
                                </div>
                            </li>
                            <!--
      <li class="nav-item">
       <a data-toggle="collapse" href="#matriculas">
        <i class="far fa-id-card"></i>
        <p>Matrículas</p>
        <span class="caret"></span>
       </a>
       <div class="collapse" id="matriculas">
        <ul class="nav nav-collapse">
         <li>
          <a href="{{ route('matriculas.index') }}">
           <span class="sub-item">Matrícula Regular</span>
          </a>
         </li>
         <li>
          <a href="{{ route('conceptospago.index') }}">
           <span class="sub-item">Conceptos de Pagos</span>
          </a>
         </li>
         <li>
          <a href="{{ route('pagos.index') }}">
           <span class="sub-item">Pagos</span>
          </a>
         </li>
        </ul>
       </div>
      </li>
-->
                            <li class="nav-item active mb-2">
                                <a data-toggle="collapse" href="#Sistema" class="collapsed" aria-expanded="false"
                                    style="background-color: #a9a97d !important; border-color: #ccc; color: #333;">
                                    <i class="fas fa-cogs" style="color: #333;"></i>
                                    <p>Sistema</p>
                                    <span class="caret"></span>
                                </a>

                                <div class="collapse" id="Sistema">
                                    <ul class="nav nav-collapse">
                                        <li>
                                            <a href="{{ route('feriados.index') }}" class="evitar-recarga">
                                                <span class="sub-item">Feriados</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('comunicado.index') }}" class="evitar-recarga">
                                                <span class="sub-item">Comunicados/Avisos</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>

                            <li class="nav-item active mb-2">
                                <a data-toggle="collapse" href="#Usuarios" class="collapsed" aria-expanded="false"
                                    style="background-color: #a9a97d !important; border-color: #ccc; color: #333;">
                                    <i class="fas fa-users" style="color: white"></i>
                                    <p>Usuarios</p>
                                    <span class="caret"></span>
                                </a>

                                <div class="collapse" id="Usuarios">

                                    <ul class="nav nav-collapse">

                                        <li>
                                            <a href="{{ route('usuarios.index') }}">
                                                <span class="sub-item">Detalle Usuario</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
        <!-- End Sidebar -->

        <div class="main-panel">
            @if (Request::is('rutarrr1'))
                <div class="content">
                    <div class="panel-header"
                        style="
        background: linear-gradient(135deg, #0e4067, #145082, #0e4067);
        background-size: 200% 200%;
        animation: gradientMove 11s ease infinite;
     ">

                        <style>
                            @keyframes gradientMove {
                                0% {
                                    background-position: 0% 50%;
                                }

                                50% {
                                    background-position: 100% 50%;
                                }

                                100% {
                                    background-position: 0% 50%;
                                }
                            }
                        </style>

                        <div class="page-inner py-5">
                            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                                <div>
                                    <h2 class="text-white pb-2 fw-bold">Eduka Perú S.R.L Oficial</h2>
                                    <h5 class="text-white op-7 mb-2">Sistema Intranet del {{ Auth()->user()->rol }}
                                    </h5>
                                </div>
                                <div class="ml-md-auto py-2 py-md-0">
                                    <a href="" class="btn btn-white btn-border btn-round mr-2"
                                        style="font-weight: bold">Contáctanos</a>
                                    <a href="https://romeros-pe.web.app" target="_blanck"
                                        class="btn btn-secondary btn-round"
                                        style="background-color: rgba(255, 255, 255, 0.915) !important; border:none; color:#0e4067; font-weight:bold">Visita
                                        Nuestra Web</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="page-inner mt--5">
                        <div class="row row-card-no-pd mt--2">
                            <div class="col-sm-6 col-md-3">
                                <div class="card card-stats card-round">
                                    <div class="card-body ">
                                        <div class="row">
                                            <div class="col-5">
                                                <div class="icon-big text-center">
                                                    <img src="{{ asset('imagenes/logo-icon.png') }}"
                                                        style="height: 60px" oncontextmenu="return false;"
                                                        ondragstart="return false;">
                                                </div>
                                            </div>
                                            <div class="col-7 col-stats">
                                                <div class="numbers">
                                                    <p class="card-category">Universidad</p>
                                                    <h4 class="card-title">UNT</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Facilidad -->
                            <div class="col-sm-6 col-md-3">
                                <div class="card card-stats card-round">
                                    <div class="card-body ">
                                        <div class="row">
                                            <div class="col-5">
                                                <div class="icon-big text-center">
                                                    <i class="flaticon-interface-6 text-success"></i>
                                                </div>
                                            </div>
                                            <div class="col-7 col-stats">
                                                <div class="numbers">
                                                    <p class="card-category">Facilidad</p>
                                                    <h4 class="card-title">Uso Intuitivo</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Organización -->
                            <div class="col-sm-6 col-md-3">
                                <div class="card card-stats card-round">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-5">
                                                <div class="icon-big text-center">
                                                    <i class="flaticon-list text-primary"></i>
                                                </div>
                                            </div>
                                            <div class="col-7 col-stats">
                                                <div class="numbers">
                                                    <p class="card-category">Organización</p>
                                                    <h4 class="card-title">Datos Claros</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Eficiencia -->
                            <div class="col-sm-6 col-md-3">
                                <div class="card card-stats card-round">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-5">
                                                <div class="icon-big text-center">
                                                    <i class="flaticon-analytics text-warning"></i>
                                                </div>
                                            </div>
                                            <div class="col-7 col-stats">
                                                <div class="numbers">
                                                    <p class="card-category">Eficiencia</p>
                                                    <h4 class="card-title">Gestión Ágil</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-9">
                                <div class="card" style="background-color:#fffcf6b3">
                                    <div class="card-header">
                                        <div class="card-head-row">
                                            <div class="card-title" style="font-weight: bold; color:#0F3E61">
                                                Comunicados</div>
                                            <div class="card-tools">
                                                <button id="btn-export"
                                                    class="btn btn-info btn-border btn-round btn-sm"
                                                    style="color:#0e4067 !important; border-color:#0e4067 !important; font-weight:bold;">
                                                    <span class="btn-label">
                                                        <i class="fa fa-download mx-2"></i>
                                                    </span>
                                                    Descargar Comunicado
                                                </button>

                                            </div>
                                            <script>
                                                document.addEventListener("DOMContentLoaded", function() {

                                                    const carousel = document.getElementById('flyerCarousel');

                                                    // Obtener la imagen del flyer activo
                                                    function getActiveFlyer() {
                                                        let activeItem = carousel.querySelector('.carousel-item.active img');
                                                        return activeItem ? activeItem.src : null;
                                                    }

                                                    // BOTÓN EXPORTAR
                                                    document.getElementById('btn-export').addEventListener('click', function() {

                                                        const flyerSrc = getActiveFlyer();
                                                        if (!flyerSrc) return;

                                                        // Crear descarga de imagen
                                                        const link = document.createElement("a");
                                                        link.href = flyerSrc;
                                                        link.download = "comunicado.png";
                                                        link.click();
                                                    });

                                                });
                                            </script>

                                        </div>
                                    </div>
                                    <div class="card-body " style="align-items: center !important; ">

                                        <div class="carousel-container d-block "
                                            style="width:auto; display:flex; justify-content:center; align-items:center; height:100%;">

                                            <div id="flyerCarousel" class="carousel slide" data-ride="carousel"
                                                data-interval="7000">
                                                <div class="carousel-inner">

                                                    {{-- Primer ítem: activo --}}
                                                    @php $firstShown = false; @endphp

                                                    @forelse ($flyer as $item)
                                                        @php
                                                            $hoy = \Carbon\Carbon::now();
                                                            $fechaInicio = \Carbon\Carbon::parse($item->fecha_inicio);
                                                            $fechaLimite = \Carbon\Carbon::parse($item->fecha_fin);

                                                            $rutaFlyer = storage_path(
                                                                'app/public/comunicados/' . ($item->flyer_url ?? ''),
                                                            );
                                                            $existe =
                                                                isset($item->flyer_url) && file_exists($rutaFlyer);
                                                            $publico = $item->publico;

                                                            $foto = $existe
                                                                ? asset('storage/comunicados/' . $item->flyer_url)
                                                                : asset('storage/estudiantes/imgDocente.png');
                                                        @endphp


                                                        @if (($hoy->between($fechaInicio, $fechaLimite) && $publico == auth()->user()->rol) || $publico == 'General')
                                                            <div
                                                                class="carousel-item {{ !$firstShown ? 'active' : '' }} ">
                                                                <img src="{{ $foto }}" alt="Flyer"
                                                                    class="flyer-img" oncontextmenu="return false;"
                                                                    ondragstart="return false;"
                                                                    onerror="this.src='{{ asset('imagenes/imgDocente.png') }}'; this.onerror=null;">

                                                            </div>
                                                            @php $firstShown = true; @endphp
                                                        @endif
                                                    @empty
                                                        <div class="carousel-item ">
                                                            <img src="\public\imagenes\flyer1.png" alt="Flyer"
                                                                class="flyer-img" oncontextmenu="return false;"
                                                                ondragstart="return false;">

                                                        </div>
                                                    @endforelse
                                                </div>
                                            </div>
                                        </div>
                                        <style>
                                            /* Contenedor general del carrusel */
                                            .carousel-container {
                                                width: 100%;
                                                display: flex;
                                                justify-content: center;
                                                align-items: center;
                                                align-content: center;
                                                justify-items: center;
                                            }

                                            /* Mantener flyer contenido, responsivo y redondeado */
                                            .flyer-img {
                                                max-width: 100%;
                                                width: auto;
                                                height: auto;
                                                max-height: 550px;
                                                /* Controla el límite vertical */
                                                object-fit: contain !important;
                                                border-radius: 8px !important;
                                                align-items: center;
                                                align-content: center;
                                                user-select: none;
                                            }

                                            /* Evitar que el flyer se estire en pantallas chicas */
                                            @media (max-width: 768px) {
                                                .flyer-img {
                                                    max-height: 350px;
                                                }
                                            }
                                        </style>




                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">

                                <!-- Resumen Académico -->
                                <div class="card card-primary" style="background-color: #0e4067f7 !important">
                                    <div class="card-header">
                                        <div class="card-title">Resumen Académico</div>
                                        <div class="card-category">Semana Actual</div>
                                    </div>
                                    <div class="card-body pb-0">
                                        <div class="mb-4 mt-2">
                                            <h1>Estado General</h1>
                                        </div>
                                        <div class="pull-in">
                                            <canvas id="dailySalesChart"></canvas>
                                        </div>
                                    </div>
                                </div>

                                <!-- Progreso del Sistema -->
                                <div class="card card-primary"
                                    style="
            background: linear-gradient(135deg, #0e4067, #145082, #0e4067) !important;
            background-size: 200% 200%;
            animation: gradientMove 11s ease infinite;
        ">
                                    <div class="card-body">
                                        <h4 class="mb-1 fw-bold">Progreso del Sistema</h4>
                                        <small class="text-white-50">Gestión académica completada</small>

                                        <div id="task-complete" class="chart-circle mt-4 mb-3">
                                            <div class="circles-wrp"
                                                style="position: relative; display: inline-block;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="100"
                                                    height="100">
                                                    <path fill="transparent" stroke="#36a3f7" stroke-width="5"
                                                        d="M 49.99032552282448 2.500000985215891 A 47.5 47.5 0 1 1 49.9340234529416 2.5000458200722235 Z"
                                                        class="circles-maxValueStroke"></path>
                                                    <path fill="transparent" stroke="#fff" stroke-width="5"
                                                        d="M 49.99032552282448 2.500000985215891 A 47.5 47.5 0 1 1 4.81379135029583 35.355664990673006 "
                                                        class="circles-valueStroke"></path>
                                                </svg>
                                                <div class="circles-text"
                                                    style="position: absolute; top: 0; left: 0; text-align: center; width: 100%; font-size: 35px; height: 100px; line-height: 100px;">
                                                    80%
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                        <div class="row g-3">

                            <!-- Actividad en tiempo real -->
                            <div class="col-md-4">
                                <div class="card shadow-sm h-100 border-0"
                                    style="background: linear-gradient(135deg, #0e4067, #1b5fa7); color:white;">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <p class="mb-1 text-white-50">Actividad en tiempo real</p>
                                                <h2 class="fw-bold mb-0">Conectados</h2>
                                            </div>
                                            <i class="flaticon-network fs-1 opacity-75"></i>
                                        </div>

                                        <div class="mt-3">
                                            <h1 class="fw-bold">17</h1>
                                            <span class="badge bg-success"
                                                style="border: none; font-weight:bold; font-size:medium">Sistema
                                                activo</span>
                                        </div>

                                        <div class="mt-3 small text-white-50">
                                            Estudiantes y docentes usando Eduka ahora
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Crecimiento académico -->
                            <div class="col-md-4">
                                <div class="card shadow-sm h-100 border-0">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <p class="mb-1 text-muted">Crecimiento académico</p>
                                                <h2 class="fw-bold mb-0">Nuevos registros</h2>
                                            </div>
                                            <i class="flaticon-add-user text-primary fs-1"></i>
                                        </div>

                                        <div class="mt-3">
                                            <h1 class="fw-bold">27</h1>
                                            <span class="text-success fw-semibold"
                                                style="border: none; font-weight:bold; font-size:medium">↗ Tendencia
                                                positiva</span>
                                        </div>

                                        <div class="mt-3 small text-muted">
                                            Estudiantes registrados recientemente
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Impacto del sistema -->
                            <div class="col-md-4">
                                <div class="card shadow-sm h-100 border-0"
                                    style="background: linear-gradient(135deg, #d0bc90, #f39c12); color:#222;">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <p class="mb-1 text-dark-50">Impacto del sistema</p>
                                                <h2 class="fw-bold mb-0">Procesos gestionados</h2>
                                            </div>
                                            <i class="flaticon-analytics fs-1 opacity-75"></i>
                                        </div>

                                        <div class="mt-3">
                                            <h1 class="fw-bold">213</h1>
                                            <span class="badge bg-warning"
                                                style="border: none; font-weight:bold; font-size:medium">Alta
                                                eficiencia</span>
                                        </div>

                                        <div class="mt-3 small">
                                            Matrículas, notas y registros procesados
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

        </div>
        @endif
        <div class="content">
            @yield('contenidoplantilla')
            @if (session('modal_success'))
                <!-- Modal -->
                <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-success shadow-lg">
                            <div class="modal-header bg-light border-0 justify-content-center">
                                <img src="{{ asset('img_eduka.png') }}" alt="Logo" style="height: 60px;">
                            </div>
                            <div class="modal-body text-center">
                                <h5 class="text-success fw-bold d-flex justify-content-center align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        fill="#198754" class="bi bi-check-circle-fill me-2" viewBox="0 0 16 16">
                                        <path
                                            d="M16 8A8 8 0 11.001 8a8 8 0 0115.998 0zM6.97 11.03a.75.75 0 001.07 0l3.992-3.992a.75.75 0 00-1.06-1.06L7.5 9.44 6.1 8.03a.75.75 0 00-1.06 1.06l1.93 1.94z" />
                                    </svg>
                                    ¡Éxito!
                                </h5>
                                <p class="mt-2">{{ session('modal_success') }}</p>
                            </div>
                            <div class="modal-footer justify-content-center border-0">
                                <button type="button" class="btn btn-outline-success px-4"
                                    data-bs-dismiss="modal">Aceptar</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Script para mostrar el modal y cerrarlo automáticamente -->
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const modalElement = document.getElementById('successModal');
                        const modal = new bootstrap.Modal(modalElement);
                        modal.show();

                        setTimeout(() => {
                            modal.hide();
                        }, 5000); // 5000 ms = 5 segundos
                    });
                </script>
            @endif


        </div>
    </div>

    </div>

    <!-- Custom template | don't include it in your project!
  <div class="custom-template">
   <div class="title">Settings</div>
   <div class="custom-content">
    <div class="switcher">
     <div class="switch-block">
      <h4>Logo Header</h4>
      <div class="btnSwitch">
       <button type="button" class="changeLogoHeaderColor" data-color="dark"></button>
       <button type="button" class="selected changeLogoHeaderColor" data-color="blue"></button>
       <button type="button" class="changeLogoHeaderColor" data-color="purple"></button>
       <button type="button" class="changeLogoHeaderColor" data-color="light-blue"></button>
       <button type="button" class="changeLogoHeaderColor" data-color="green"></button>
       <button type="button" class="changeLogoHeaderColor" data-color="orange"></button>
       <button type="button" class="changeLogoHeaderColor" data-color="red"></button>
       <button type="button" class="changeLogoHeaderColor" data-color="white"></button>
       <br/>
       <button type="button" class="changeLogoHeaderColor" data-color="dark2"></button>
       <button type="button" class="changeLogoHeaderColor" data-color="blue2"></button>
       <button type="button" class="changeLogoHeaderColor" data-color="purple2"></button>
       <button type="button" class="changeLogoHeaderColor" data-color="light-blue2"></button>
       <button type="button" class="changeLogoHeaderColor" data-color="green2"></button>
       <button type="button" class="changeLogoHeaderColor" data-color="orange2"></button>
       <button type="button" class="changeLogoHeaderColor" data-color="red2"></button>
      </div>
     </div>
     <div class="switch-block">
      <h4>Navbar Header</h4>
      <div class="btnSwitch">
       <button type="button" class="changeTopBarColor" data-color="dark"></button>
       <button type="button" class="changeTopBarColor" data-color="blue"></button>
       <button type="button" class="changeTopBarColor" data-color="purple"></button>
       <button type="button" class="changeTopBarColor" data-color="light-blue"></button>
       <button type="button" class="changeTopBarColor" data-color="green"></button>
       <button type="button" class="changeTopBarColor" data-color="orange"></button>
       <button type="button" class="changeTopBarColor" data-color="red"></button>
       <button type="button" class="changeTopBarColor" data-color="white"></button>
       <br/>
       <button type="button" class="changeTopBarColor" data-color="dark2"></button>
       <button type="button" class="selected changeTopBarColor" data-color="blue2"></button>
       <button type="button" class="changeTopBarColor" data-color="purple2"></button>
       <button type="button" class="changeTopBarColor" data-color="light-blue2"></button>
       <button type="button" class="changeTopBarColor" data-color="green2"></button>
       <button type="button" class="changeTopBarColor" data-color="orange2"></button>
       <button type="button" class="changeTopBarColor" data-color="red2"></button>
      </div>
     </div>
     <div class="switch-block">
      <h4>Sidebar</h4>
      <div class="btnSwitch">
       <button type="button" class="selected changeSideBarColor" data-color="white"></button>
       <button type="button" class="changeSideBarColor" data-color="dark"></button>
       <button type="button" class="changeSideBarColor" data-color="dark2"></button>
      </div>
     </div>
     <div class="switch-block">
      <h4>Background</h4>
      <div class="btnSwitch">
       <button type="button" class="changeBackgroundColor" data-color="bg2"></button>
       <button type="button" class="changeBackgroundColor selected" data-color="bg1"></button>
       <button type="button" class="changeBackgroundColor" data-color="bg3"></button>
       <button type="button" class="changeBackgroundColor" data-color="dark"></button>
      </div>
     </div>
    </div>
   </div>

   <div class="custom-toggle">
    <i class="flaticon-settings"></i>
   </div>
  </div>-->
    <!-- End Custom template -->
    </div>
    <!-- Core JS Files -->
    <script src="{{ asset('adminlte/assets/js/core/jquery.3.2.1.min.js') }}"></script>
    <script src="{{ asset('adminlte/assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('adminlte/assets/js/core/bootstrap.min.js') }}"></script>

    <!-- jQuery UI -->
    <script src="{{ asset('adminlte/assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('adminlte/assets/js/plugin/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js') }}"></script>

    <!-- jQuery Scrollbar -->
    <script src="{{ asset('adminlte/assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>

    <!-- Chart JS -->
    <script src="{{ asset('adminlte/assets/js/plugin/chart.js/chart.min.js') }}"></script>

    <!-- jQuery Sparkline -->
    <script src="{{ asset('adminlte/assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js') }}"></script>

    <!-- Chart Circle -->
    <script src="{{ asset('adminlte/assets/js/plugin/chart-circle/circles.min.js') }}"></script>

    <!-- Datatables -->
    <script src="{{ asset('adminlte/assets/js/plugin/datatables/datatables.min.js') }}"></script>

    <!-- Bootstrap Notify -->


    <!-- jQuery Vector Maps -->
    <script src="{{ asset('adminlte/assets/js/plugin/jqvmap/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('adminlte/assets/js/plugin/jqvmap/maps/jquery.vmap.world.js') }}"></script>

    <!-- Sweet Alert -->
    <script src="{{ asset('adminlte/assets/js/plugin/sweetalert/sweetalert.min.js') }}"></script>

    <!-- Atlantis JS -->
    <script src="{{ asset('adminlte/assets/js/atlantis.min.js') }}"></script>

    <!-- Atlantis DEMO methods, don't include it in your project! -->
    <script src="{{ asset('adminlte/assets/js/setting-demo.js') }}"></script>
    @yield('scripts')

    <script>
        if (performance.getEntriesByType("navigation")[0]?.type === "back_forward") {
            location.href = "{{ route('login') }}"; // redirige directo si vuelve con botón atrás
        }

        // Manual sidebar toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const toggleButtons = document.querySelectorAll('.sidenav-toggler');
            const customToggle = document.getElementById('custom-sidebar-toggle');

            // Function to toggle sidebar
            function toggleSidebar() {
                const sidebar = document.querySelector('.sidebar');
                const mainPanel = document.querySelector('.main-panel');

                if (sidebar && mainPanel) {
                    // Toggle sidebar visibility
                    sidebar.classList.toggle('sidebar_minimize');
                    mainPanel.classList.toggle('main-panel_minimize');
                }
            }

            // Add click event to all toggle buttons
            toggleButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    toggleSidebar();
                });
            });

            // Add click event to custom toggle button
            if (customToggle) {
                customToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    toggleSidebar();
                });
            }

            // Force show the Atlantis button after a delay
            setTimeout(function() {
                const atlantisButton = document.querySelector('.topbar-toggler.more');
                if (atlantisButton) {
                    atlantisButton.style.display = 'inline-block !important';
                    atlantisButton.style.visibility = 'visible !important';
                    atlantisButton.style.opacity = '1 !important';
                    atlantisButton.style.position = 'relative !important';
                    atlantisButton.style.zIndex = '1000 !important';
                    atlantisButton.style.background = 'transparent !important';
                    atlantisButton.style.border = 'none !important';
                    atlantisButton.style.color = 'white !important';
                    atlantisButton.style.fontSize = '18px !important';
                    atlantisButton.style.padding = '8px !important';
                    atlantisButton.style.cursor = 'pointer !important';

                    // Remove any hide class
                    atlantisButton.classList.remove('hide');

                    // Add click event
                    atlantisButton.addEventListener('click', function(e) {
                        e.preventDefault();
                        toggleSidebar();
                    });

                    console.log('Atlantis button forced visible');
                } else {
                    console.log('Atlantis button not found');
                }
            }, 1000);
        });
    </script>

    <link href="https://unpkg.com/intro.js/minified/introjs.min.css" rel="stylesheet" />
    <script src="https://unpkg.com/intro.js/minified/intro.min.js"></script>

    <style>
        /* ========================
     PANTALLA DE BIENVENIDA
  =========================*/
        #welcomeSplash {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #143853;
            /* Fondo EDUKA sobrio */
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 99999;
            opacity: 0;
            pointer-events: none;
            transform: scale(1.05);
            transition: opacity 1.2s ease, transform 1.2s ease;
        }

        #welcomeSplash.show {
            opacity: 1;
            pointer-events: auto;
            transform: scale(1);
        }

        #welcomeSplash .content {
            text-align: center;
            animation: fadeSlide 2s ease forwards;
        }

        #welcomeSplash img {
            width: 300px;
            opacity: 0;
            animation: fadeZoom 2s ease forwards;
        }

        @keyframes fadeZoom {
            0% {
                opacity: 0;
                transform: scale(0.7) rotate(-10deg);
            }

            60% {
                opacity: 1;
                transform: scale(1.2) rotate(2deg);
            }

            100% {
                opacity: 1;
                transform: scale(1) rotate(0);
            }
        }

        @keyframes fadeSlide {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ========================
     ESTILOS DEL TOUR INTROJS
  =========================*/
        .introjs-tooltip {
            background: #1c1c1c !important;
            color: #fff !important;
            border-radius: 12px !important;
            padding: 18px !important;
            font-family: "Quicksand", sans-serif !important;
            font-size: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
        }

        .introjs-tooltip-title {
            font-weight: 700;
            font-size: 16px;
            margin-bottom: 5px;
            color: #b68a39 !important;
            font-family: "Quicksand", sans-serif !important;
            /* Dorado EDUKA */
        }

        .introjs-overlay {
            background: rgba(0, 0, 0, 0.75) !important;
        }

        .introjs-helperLayer {
            background: transparent !important;
            border: 2px solid #b68a39 !important;
            border-radius: 12px !important;
        }

        .introjs-progressbar {
            background: #7a2e40 !important;
        }

        .introjs-button {
            border-radius: 8px !important;
            padding: 8px 14px !important;
            font-weight: 600 !important;
            border: none !important;
            font-family: "Quicksand", sans-serif !important;
        }

        .introjs-nextbutton {
            background: #368557 !important;
            color: #fff !important;
        }

        .introjs-prevbutton {
            background: #295471 !important;
            color: #fff !important;
        }

        .introjs-donebutton {
            background: #832f42 !important;
            color: #fff !important;
        }
    </style>

    <!-- Splash -->
    <div id="welcomeSplash">
        <div class="content">
            <img src="/imagenes/Imagen1.png" alt="Eduka Logo">
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // ✅ Revisamos si ya se mostró el tour antes
            if (!localStorage.getItem("tourCompletadoEduka")) {
                // Usar la nueva API de introJs
                const intro = introJs();

                intro.setOptions({
                    steps: [{
                            element: document.querySelector('[href="#matriculas1"]'),
                            intro: "🎓 <b>Matrículas:</b> gestiona la inscripción de tus estudiantes."
                        },
                        {
                            element: document.querySelector('[href="#pagos"]'),
                            intro: "💰 <b>Pagos:</b> administra los pagos fácilmente."
                        },
                        {
                            element: document.querySelector('[href="#asistencia-admin"]'),
                            intro: "📋 <b>Asistencia:</b> controla la asistencia de los estudiantes."
                        },
                        {
                            element: document.querySelector('[href="#notas"]'),
                            intro: "📊 <b>Notas:</b> registra y consulta el rendimiento académico."
                        },
                        {
                            element: document.querySelector('[href="#frecuente"]'),
                            intro: "⚡ <b>Operaciones frecuentes:</b> accesos rápidos para ahorrar tiempo."
                        },
                        {
                            element: document.querySelector('[href="#correo"]'),
                            intro: "✉️ <b>Solicitudes:</b> envía peticiones al Administrador fácilmente."
                        }
                    ],
                    showProgress: true,
                    showBullets: false,
                    disableInteraction: true,
                    nextLabel: 'Siguiente →',
                    prevLabel: '← Atrás',
                    doneLabel: '¡Finalizar!'
                });

                function mostrarSplash() {
                    let splash = document.getElementById('welcomeSplash');
                    if (splash) {
                        splash.classList.add('show');
                        setTimeout(() => {
                            splash.classList.remove('show');
                        }, 4000);
                    }
                }

                // ✅ Cuando finalice o salga, guardamos la marca en localStorage
                intro.oncomplete(() => {
                    localStorage.setItem("tourCompletadoEduka", "true");
                    mostrarSplash();
                });

                intro.onexit(() => {
                    localStorage.setItem("tourCompletadoEduka", "true");
                    mostrarSplash();
                });

                // Verificar que los elementos existan antes de iniciar
                setTimeout(() => {
                    const firstElement = document.querySelector('[href="#matriculas1"]');
                    if (firstElement) {
                        intro.start();
                    } else {
                        // Si no hay elementos, marcar como completado
                        localStorage.setItem("tourCompletadoEduka", "true");
                        mostrarSplash();
                    }
                }, 1000);
            }
        });
    </script>

    @stack('js-extra')

    @if (Request::is('rutarrr1'))
        <!-- ChatBot Widget - Optimizado para mejor performance -->
        <script>
            // Cargar chatbot de forma diferida para no bloquear la carga inicial
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(function() {
                    if (typeof window.botmanWidget === 'undefined') {
                        window.botmanWidget = {
                            frameEndpoint: '/botman',
                            chatServer: '/botman',
                            title: '🤖 Tío Edú',
                            introMessage: '👋 ¡Hola! Soy tu asistente virtual.',
                            placeholderText: 'Escribe tu mensaje...',
                            mainColor: '#28AECE',
                            bubbleBackground: '#f8f9fa',
                            headerTextColor: '#ffffff',
                            bubbleAvatarUrl: '{{ asset('imagenes/imgTioEduka.png') }}',
                            displayMessageTime: false, // Deshabilitado para mejor performance
                            desktopHeight: 400,
                            desktopWidth: 350,
                            mobileHeight: '90%',
                            mobileWidth: '90%',
                            alwaysUseFloatingButton: true,
                            aboutText: 'Eduka Perú',
                            aboutLink: '{{ url('/') }}'
                        };

                        // Cargar script del chatbot
                        const script = document.createElement('script');
                        script.src = 'https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/widget.js';
                        script.async = true;
                        document.head.appendChild(script);
                    }
                }, 3000); // Cargar después de 3 segundos
            });
        </script>
    @endif
</body>

</html>
