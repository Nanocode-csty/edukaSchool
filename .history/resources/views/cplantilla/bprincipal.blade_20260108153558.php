<!DOCTYPE html>
<html lang="en">

<head>

    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>@yield('titulo', 'Inicio | Intranet Eduka Perú')</title>
    <link rel="icon" href="{{ asset('imagenes/imgLogo.png') }}" type="image/png">
    <link rel="shortcut icon" href="{{ asset('imagenes/imgLogo.png') }}" type="image/png">

    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- 🔹 jQuery primero -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Ensure Select2 is available globally -->
    <script>
        window.initSelect2 = function(selector, options = {}) {
            if (typeof $ !== 'undefined' && $.fn.select2) {
                const defaultOptions = {
                    placeholder: options.placeholder || 'Seleccionar...',
                    allowClear: options.allowClear !== false,
                    width: options.width || '100%',
                    theme: options.theme || 'bootstrap4',
                    minimumResultsForSearch: 0, // Always show search box
                    language: {
                        noResults: function() {
                            return "No se encontraron resultados";
                        },
                        searching: function() {
                            return "Buscando...";
                        }
                    }
                };

                const finalOptions = Object.assign({}, defaultOptions, options);
                $(selector).select2(finalOptions);
                console.log('Select2 initialized for:', selector);
                return true;
            } else {
                console.error('Select2 not available');
                return false;
            }
        };
    </script>


    <!-- Fonts and icons -->
    <script src="{{ asset('adminlte/assets/js/plugin/webfont/webfont.min.js') }}"></script>
    <script>
        WebFont.load({
            google: {
                "families": ["Lato:300,400,700,900"]
            },
            custom: {
                "families": ["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands",
                    "simple-line-icons"
                ],
                urls: ["{{ asset('adminlte/assets/css/fonts.min.css') }}"]
            },
            active: function() {
                sessionStorage.fonts = true;
            }
        });
    </script>

    <!-- Bootstrap 5 CSS -->
    <!-- ============================= -->
    <!--  Bootstrap 5.3.3 - CSS Oficial -->
    <!-- ============================= -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('adminlte/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/assets/css/atlantis.min.css') }}">

    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link rel="stylesheet" href="{{ asset('adminlte/assets/css/demo.css') }}">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.css') }}">
    <!-- jQuery (requerido) -->
    <!-- PARA EL CALENDAR-->
    <script src="{{ asset('https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js') }}"></script>

    <script src="{{ asset('https://cdn.jsdelivr.net/npm/flatpickr') }}"></script>
    <!-- Bootstrap Notify -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-notify/0.2.0/js/bootstrap-notify.min.js"></script>

    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="{{ asset('https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css') }}">

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

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script type="text/javascript" async=""
        src="https://www.google-analytics.com/gtm/js?id=GTM-KP42DWZ&t=gtag_UA_125387370_2&cid=979822549.1745649107">
    </script>
    <script type="text/javascript" async="" src="https://www.google-analytics.com/analytics.js"></script>
    <script type="text/javascript" async=""
        src="https://www.googletagmanager.com/gtag/js?id=G-ZLTZ3530TL&cx=c&gtm=457e55r0za200&tag_exp=101509157~102015666~103116026~103130498~103130500~103200004~103233427~103252644~103252646~104481633~104481635">
    </script>
    <script async="" src="https://www.googletagmanager.com/gtag/js?id=UA-125387370-2"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-125387370-2', {
            'optimize_id': 'GTM-KP42DWZ'
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

    {{-- Fix for topbar-toggler icon alignment in reports view --}}
    @if(request()->routeIs('asistencia.reportes'))
    <style>
        /* Ultra-specific styles for reports view only */
        .main-header .logo-header .topbar-toggler-container {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            height: 100% !important;
            min-height: 70px !important;
            position: relative !important;
            vertical-align: middle !important;
        }

        .main-header .logo-header .topbar-toggler.more {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 32px !important;
            height: 32px !important;
            min-width: 32px !important;
            min-height: 32px !important;
            padding: 0 !important;
            margin: 0 !important;
            border: none !important;
            background: transparent !important;
            box-shadow: none !important;
            outline: none !important;
            position: relative !important;
            vertical-align: middle !important;
            line-height: 1 !important;
        }

        .main-header .logo-header .topbar-toggler.more:focus {
            outline: none !important;
            box-shadow: none !important;
        }

        .main-header .logo-header .topbar-toggler.more .icon-wrapper {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 100% !important;
            height: 100% !important;
            position: relative !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .main-header .logo-header .topbar-toggler.more i.icon-options-vertical {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            margin: 0 !important;
            padding: 0 !important;
            font-size: 1.1em !important;
            position: absolute !important;
            top: 50% !important;
            left: 50% !important;
            transform: translate(-50%, -50%) !important;
            line-height: 1 !important;
            vertical-align: middle !important;
        }

        /* Override any framework styles */
        .main-header .logo-header .topbar-toggler.more,
        .main-header .logo-header .topbar-toggler.more *,
        .main-header .logo-header .topbar-toggler.more i.icon-options-vertical {
            box-sizing: border-box !important;
        }
    </style>
    @endif
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

                <!-- Sidebar Toggle Button -->
                <button class="btn btn-link text-white ml-3" id="sidebarToggleBtn" title="Toggle Sidebar">
                    <i class="fas fa-bars fa-lg"></i>
                </button>

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
                        <!-- Notificaciones -->
                        <li class="nav-item dropdown hidden-caret">
                            <div class="tooltip-wrapper">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-bell" style="color: white"></i>
                                    <span class="notification-badge badge badge-danger" id="notificationCount" style="display: none;"></span>
                                </a>
                                <div class="custom-tooltip">Notificaciones</div>
                                <ul class="dropdown-menu dropdown-menu-right notification-dropdown" id="notificationDropdown" aria-labelledby="navbarDropdown">
                                    <li class="dropdown-header">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span>Notificaciones</span>
                                            <a href="{{ route('notificaciones.index') }}" class="text-primary">Ver todas</a>
                                        </div>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <div id="notificationList">
                                        <li class="dropdown-item text-center">
                                            <small class="text-muted">Cargando...</small>
                                        </li>
                                    </div>
                                    <li><hr class="dropdown-divider"></li>
                                    <li class="dropdown-footer text-center">
                                        <a href="{{ route('notificaciones.index') }}" class="btn btn-sm btn-primary">Ver todas las notificaciones</a>
                                    </li>
                                </ul>
                            </div>
                        </li>

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
                                    <img src="{{ asset('img_eduka.png') }}" alt="Logo" style="height: 60px;">
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
                                        <label for="modal-email" class="font-weight-bold">Correo electrónico</label>
                                        <input type="email" name="email" id="modal-email" class="form-control"
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

                                        @if (auth()->user()->hasRole('Administrador'))
                                            <script>
                                                document.getElementById('quick').classList.remove('d-none');
                                            </script>
                                        @endif
                                    </div>
                                        @if (auth()->user()->hasRole('Administrador'))
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
                                    <img src="{{ auth()->user()->foto_url ? asset('storage/' . auth()->user()->foto_url) . '?t=' . time() : asset('adminlte/assets/img/profile.jpg') }}"
                                        class="avatar-img rounded-circle">
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-user animated fadeIn">
                                <div class="dropdown-user-scroll scrollbar-outer">
                                    <li>
                                        <div class="user-box">
                                            <div class="avatar-lg"><img
                                                    src="{{ auth()->user()->foto_url ? asset('storage/' . auth()->user()->foto_url) . '?t=' . time() : asset('adminlte/assets/img/profile.jpg') }}"
                                                    class="avatar-img rounded"></div>
                                            <div class="u-text">
                                                <h4>{{ auth()->user()->nombres }}<br>{{ auth()->user()->apellidos }}
                                                </h4>

                                                <a class="badge fw-bold"
                                                    style="background-color: #347f65 !important; border: none; color:white;">{{ auth()->user()->username }}</a>
                                            </div>
                                        </div>

                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>

                                        <a class="dropdown-item" href="{{ route('profile.show') }}">
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

                        <!-- SECCIONES POR ROL -->
                        @if (auth()->user()->hasRole('Administrador'))
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
                                    </div>                                </a>
                                <div class="collapse" id="asistencia-admin">
                                    <ul class="nav nav-collapse">
                                        <li>
                                            <a href="{{ route('asistencia.admin-index') }}">
                                                <span class="sub-item">Administrar Asistencias</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('asistencia.reportes') }}">
                                                <span class="sub-item">Reportes de Asistencia</span>
                                            </a>
                                        </li>

                                        <li>
                                            <a href="{{ route('asistencia.verificar') }}">
                                                <span class="sub-item">Gestionar Justificaciones</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        @endif

                                        @if (auth()->user()->hasRole('Docente'))
                            <li class="nav-item active mt-3">
                                <a data-toggle="collapse" href="#asistencia-docente"
                                    class="collapsed d-flex align-items-center justify-content-between"
                                    aria-expanded="false"
                                    style="background-color: #2e8b57 !important ; border-radius: 9px;">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-chalkboard-teacher"></i>
                                        <p>Mis Asistencias</p>
                                    </div>                                </a>
                                <div class="collapse" id="asistencia-docente">
                                    <ul class="nav nav-collapse">
                                        <li>
                                            <a href="{{ route('asistencia.docente.dashboard') }}">
                                                <span class="sub-item">Panel Integrado</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('asistencia.docente.index') }}">
                                                <span class="sub-item">Tomar Asistencia</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        @endif

                        @if (auth()->user()->hasRole('representante'))
                            <li class="nav-item active mt-3">
                                <a data-toggle="collapse" href="#asistencia-representante"
                                    class="collapsed d-flex align-items-center justify-content-between"
                                    aria-expanded="false"
                                    style="background-color: #8b4513 !important ; border-radius: 9px;">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-user-friends"></i>
                                        <p>Mis Estudiantes</p>
                                    </div>
                                </a>
                                <div class="collapse" id="asistencia-representante">
                                    <ul class="nav nav-collapse">
                                        <li>
                                            <a href="{{ route('asistencia.representante.dashboard') }}">
                                                <span class="sub-item">Panel Principal</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('asistencia.representante.index') }}">
                                                <span class="sub-item">Ver Asistencias</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('notas.misEstudiantes') }}">
                                                <span class="sub-item">Ver Calificaciones</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        @endif

                        @if (auth()->user()->hasRole('Estudiante'))
                            <li class="nav-item active mt-3">
                                <a data-toggle="collapse" href="#estudiante-dashboard"
                                    class="collapsed d-flex align-items-center justify-content-between"
                                    aria-expanded="false"
                                    style="background-color: #28a745 !important ; border-radius: 9px;">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-user-graduate"></i>
                                        <p>Mi Panel</p>
                                    </div>                                </a>
                                <div class="collapse" id="estudiante-dashboard">
                                    <ul class="nav nav-collapse">
                                        <li>
                                            <a href="{{ route('estudiante.dashboard') }}">
                                                <span class="sub-item">Dashboard</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('estudiante.asistencia') }}">
                                                <span class="sub-item">Mi Asistencia</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('estudiante.calificaciones') }}">
                                                <span class="sub-item">Mis Calificaciones</span>
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
                        @if (auth()->user()->hasRole('Administrador'))
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
                                                                    ondragstart="return false;">

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
       <button type="button" class="changeLogoHeaderColor" data-color="blue"></button>
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
       <button type="button" class="changeTopBarColor" data-color="blue2"></button>
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

    <script>
        if (performance.getEntriesByType("navigation")[0]?.type === "back_forward") {
            location.href = "{{ route('login') }}"; // redirige directo si vuelve con botón atrás
        }
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
            if (!localStorage.getItem("tourCompletado")) {
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
                    splash.classList.add('show');
                    setTimeout(() => {
                        splash.classList.remove('show');
                    }, 4000);
                }

                // ✅ Cuando finalice o salga, guardamos la marca en localStorage
                intro.oncomplete(() => {
                    localStorage.setItem("tourCompletado", "true");
                    mostrarSplash();
                });

                intro.onexit(() => {
                    localStorage.setItem("tourCompletado", "true");
                    mostrarSplash();
                });

                intro.start();
            }
        });
    </script>

    <!-- Notification Scripts -->
    <script>
        // Load notifications when page loads
        document.addEventListener('DOMContentLoaded', function() {
            loadNotifications();
            // Refresh notifications every 30 seconds
            setInterval(loadNotifications, 30000);

            // Initialize sidebar toggle functionality
            initSidebarToggle();
        });

        function loadNotifications() {
            fetch('{{ route('notificaciones.recent') }}')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateNotificationDropdown(data.data);
                        updateNotificationBadge(data.data.filter(n => !n.leido_en).length);
                    }
                })
                .catch(error => console.error('Error loading notifications:', error));
        }

        function updateNotificationDropdown(notifications) {
            const list = document.getElementById('notificationList');
            if (!list) return;

            if (notifications.length === 0) {
                list.innerHTML = '<li class="dropdown-item text-center"><small class="text-muted">No hay notificaciones</small></li>';
                return;
            }

            let html = '';
            notifications.forEach(notification => {
                const isUnread = !notification.leido_en;
                const timeAgo = new Date(notification.created_at).toLocaleDateString('es-ES', {
                    day: 'numeric',
                    month: 'short',
                    hour: '2-digit',
                    minute: '2-digit'
                });

                html += `
                    <li class="dropdown-item ${isUnread ? 'bg-light' : ''}" style="border-left: 3px solid ${notification.color};">
                        <div class="d-flex align-items-start">
                            <i class="${notification.icono} mr-2 mt-1" style="color: ${notification.color};"></i>
                            <div class="flex-grow-1">
                                <div class="font-weight-bold ${isUnread ? '' : 'text-muted'}" style="font-size: 0.9rem;">
                                    ${notification.titulo}
                                </div>
                                <div class="text-muted" style="font-size: 0.8rem; line-height: 1.2;">
                                    ${notification.mensaje.length > 80 ? notification.mensaje.substring(0, 80) + '...' : notification.mensaje}
                                </div>
                                <small class="text-muted">${timeAgo}</small>
                            </div>
                        </div>
                    </li>
                `;
            });
            list.innerHTML = html;
        }

        function updateNotificationBadge(count) {
            const badge = document.getElementById('notificationCount');
            if (!badge) return;

            if (count > 0) {
                badge.textContent = count > 99 ? '99+' : count;
                badge.style.display = 'inline-block';
            } else {
                badge.style.display = 'none';
            }
        }

        // Mark notification as read when clicked
        document.addEventListener('click', function(e) {
            if (e.target.closest('.dropdown-item') && e.target.closest('#notificationDropdown')) {
                const item = e.target.closest('.dropdown-item');
                // You can add click handling here if needed
            }
        });

        // Sidebar toggle functionality
        function initSidebarToggle() {
            const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');

            if (sidebarToggleBtn) {
                sidebarToggleBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    toggleSidebar();
                });
            }
        }

        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const mainPanel = document.querySelector('.main-panel');

            if (sidebar) {
                const isHidden = sidebar.style.display === 'none';

                if (isHidden) {
                    // Show sidebar
                    sidebar.style.display = 'block';
                    if (mainPanel) {
                        mainPanel.style.marginLeft = '';
                        mainPanel.style.width = '';
                    }
                } else {
                    // Hide sidebar
                    sidebar.style.display = 'none';
                    if (mainPanel) {
                        mainPanel.style.marginLeft = '0';
                        mainPanel.style.width = '100%';
                    }
                }
            }
        }
    </script>

    @stack('js-extra')

    @if (Request::is('rutarrr1'))
        <!-- Botman Web Widget - Only on home page -->
        <script>
            window.botmanWidget = {
                frameEndpoint: '/botman',
                chatServer: '/botman',
                title: '🤖 Tío Edú',
                introMessage: '👋 <b>¡Hola {{ auth()->user()->nombres }}!</b> <br> Soy tu Tío Edú, asistente virtual de Eduka Perú. Escribe "menu" para ver lo que puedo hacer por ti.',

                placeholderText: 'Escribe tu mensaje aquí...',
                mainColor: '#28AECE',
                bubbleBackground: '#f8f9fa',

                headerTextColor: '#ffffff',
                bubbleAvatarUrl: '{{ asset('imagenes/imgTioEduka.png') }}',
                displayMessageTime: true,
                desktopHeight: 450,
                desktopWidth: 370,
                mobileHeight: '100%',
                mobileWidth: '100%',
                alwaysUseFloatingButton: true,
                aboutText: 'Powered by Eduka Perú',
                aboutLink: '{{ url('/') }}',

            };
        </script>

        <script src='https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/widget.js'></script>

        <!-- Script para mejorar el comportamiento del scroll -->
        <script>
            // Esperar a que el widget se cargue completamente
            window.addEventListener('load', function() {
                setTimeout(function() {
                    // Prevenir scroll automático al final
                    const chatMessages = document.querySelector('.botmanChatWindow__messages');
                    if (chatMessages) {
                        // Configurar observer para nuevos mensajes
                        const observer = new MutationObserver(function(mutations) {
                            mutations.forEach(function(mutation) {
                                if (mutation.addedNodes.length) {
                                    // Solo hacer scroll si el usuario ya está cerca del final
                                    const isNearBottom = chatMessages.scrollHeight -
                                        chatMessages.scrollTop - chatMessages.clientHeight <
                                        100;

                                    if (isNearBottom) {
                                        // Scroll suave al final solo si está cerca
                                        setTimeout(() => {
                                            chatMessages.scrollTo({
                                                top: chatMessages.scrollHeight,
                                                behavior: 'smooth'
                                            });
                                        }, 100);
                                    }
                                }
                            });
                        });

                        observer.observe(chatMessages, {
                            childList: true,
                            subtree: true
                        });

                        // Mantener la posición inicial arriba al abrir el chat
                        chatMessages.scrollTop = 0;
                    }
                }, 2000);
            });
        </script>

        <!-- Estilos personalizados para el widget - Only on home page -->
        <style>
            /* Personalización del botón flotante del chat - CORREGIDO */
            .botmanWidgetButton,
            .mobile-closed-message-avatar,
            .desktop-closed-message-avatar {
                background: #fff;
                box-shadow: 0 6px 25px rgba(255, 255, 255, 0.8) !important;
                transition: all 0.3s ease !important;
                border: 6px solid #0F3E61 !important;
                width: clamp(70px, 10vw, 107px) !important;
                height: clamp(70px, 10vw, 107px) !important;
                cursor: pointer !important;
                position: fixed !important;
                bottom: 20px !important;
                right: clamp(4px, 2.5vw, 31px) !important;
                left: auto !important;
                top: auto !important;
                z-index: 9999 !important;
            }

            .botmanWidgetButton:hover,
            .mobile-closed-message-avatar:hover,
            .desktop-closed-message-avatar:hover {
                transform: scale(1.03) translateY(-5px) !important;
                box-shadow: 0 10px 30px #fff !important;
                border-color: #0e4d7d !important;
            }

            /* Imagen dentro del botón - centrada y contrastada */
            .mobile-closed-message-avatar img,
            .desktop-closed-message-avatar img,
            .botmanWidgetButton img {
                filter: drop-shadow(0 2px 6px rgba(0, 0, 0, 0.5)) !important;
                width: 100% !important;
                height: 100% !important;
                object-fit: contain !important;
                padding: 0px !important;
                position: absolute !important;
                top: 50% !important;
                left: 50% !important;
                transform: translate(-50%, -50%) !important;
            }

            /* Asegurar que el contenedor sea relativo para el posicionamiento absoluto */
            .mobile-closed-message-avatar,
            .desktop-closed-message-avatar {
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                overflow: hidden !important;
            }

            /* Icono SVG dentro del botón - más visible */
            .botmanWidgetButton svg {
                filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3)) !important;
            }

            /* Badge de notificación más visible */
            .botmanWidgetButton .botmanWidgetBadge,
            .botmanWidgetBadge {
                background-color: #DD1558 !important;
                color: white !important;
                font-weight: bold !important;
                box-shadow: 0 2px 8px rgba(221, 21, 88, 0.5) !important;
            }

            /* Animación pulsante para llamar la atención */
            @keyframes pulse {

                0%,
                100% {
                    box-shadow: 0 6px 25px rgba(40, 174, 206, 0.8);
                }

                50% {
                    box-shadow: 0 6px 35px rgba(40, 174, 206, 1);
                }
            }

            .mobile-closed-message-avatar,
            .desktop-closed-message-avatar {
                animation: pulse 2s ease-in-out infinite !important;
            }

            /* Personalización del header del chat */
            .botmanWidgetHeader {
                background: linear-gradient(135deg, #0e4067 0%, #114871 100%) !important;
                font-family: 'Quicksand', sans-serif !important;
                font-weight: 700 !important;
            }

            /* Chat más fluido - scroll suave */
            .botmanChatWindow,
            .botmanChatWindow__message-container,
            .botmanChatWindow__messages {
                scroll-behavior: smooth !important;
                overflow-y: auto !important;
            }

            /* Barra de scroll personalizada - más elegante */
            .botmanChatWindow__messages::-webkit-scrollbar {
                width: 8px !important;
            }

            .botmanChatWindow__messages::-webkit-scrollbar-track {
                background: #f1f1f1 !important;
                border-radius: 10px !important;
            }

            .botmanChatWindow__messages::-webkit-scrollbar-thumb {
                background: linear-gradient(135deg, #0e4067 0%, #114871 100%) !important;
                border-radius: 10px !important;
                transition: all 0.3s ease !important;
            }

            .botmanChatWindow__messages::-webkit-scrollbar-thumb:hover {
                background: linear-gradient(135deg, #114871 0%, #0e4067 100%) !important;
            }

            /* Contenedor de mensajes - sin auto-scroll forzado */
            .botmanChatWindow__messages {
                display: flex !important;
                flex-direction: column !important;
                padding: 15px !important;
            }

            /* Mensajes con animación de entrada suave */
            .botmanChatMessageContainer {
                animation: messageSlideIn 0.3s ease-out !important;
                margin-bottom: 12px !important;
                transition: all 0.2s ease !important;
            }

            @keyframes messageSlideIn {
                from {
                    opacity: 0;
                    transform: translateY(10px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* Mensajes del bot */
            .botmanChatMessageContainer.botmanIncomingMessage .botmanChatMessage {
                background-color: #f0f0f0 !important;
                color: #333 !important;
                font-family: "Quicksand", sans-serif !important;
                border-radius: 15px !important;
                padding: 10px 15px !important;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1) !important;
                transition: all 0.2s ease !important;
            }

            .botmanChatMessageContainer.botmanIncomingMessage .botmanChatMessage:hover {
                box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15) !important;
                transform: translateY(-1px) !important;
            }

            /* Mensajes del usuario */
            .botmanChatMessageContainer.botmanOutgoingMessage .botmanChatMessage {
                background: linear-gradient(135deg, #0e4067 0%, #114871 100%) !important;
                color: white !important;
                font-family: "Quicksand", sans-serif !important;
                border-radius: 15px !important;
                padding: 10px 15px !important;
                box-shadow: 0 2px 5px rgba(40, 174, 206, 0.3) !important;
                transition: all 0.2s ease !important;
            }

            .botmanChatMessageContainer.botmanOutgoingMessage .botmanChatMessage:hover {
                box-shadow: 0 3px 8px rgba(40, 174, 206, 0.4) !important;
                transform: translateY(-1px) !important;
            }

            /* Input del chat */
            .botmanChatWindow__input {
                font-family: 'Quicksand', sans-serif !important;
                border: 2px solid #28AECE !important;
                border-radius: 20px !important;
                padding: 10px 15px !important;
            }

            .botmanChatWindow__input:focus {
                outline: none !important;
                border-color: #114871 !important;
                box-shadow: 0 0 0 3px rgba(40, 174, 206, 0.1) !important;
            }

            /* Botón de enviar */
            .botmanChatWindow__submit {
                background-color: #28AECE !important;
                border-radius: 50% !important;
                transition: all 0.3s ease !important;
            }

            .botmanChatWindow__submit:hover {
                background-color: #114871 !important;
                transform: scale(1.1) !important;
            }

            /* Animación de carga */
            @keyframes botmanTyping {

                0%,
                60%,
                100% {
                    transform: translateY(0);
                }

                30% {
                    transform: translateY(-10px);
                }
            }

            /* Badge de notificación */
            .botmanWidgetBadge {
                background-color: #DD1558 !important;
                font-family: 'Quicksand', sans-serif !important;
                font-weight: 700 !important;
            }
        </style>
    @endif
</body>

</html>
