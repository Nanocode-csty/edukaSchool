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

                <button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse"
                    data-target="collapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon">
                        <i class="icon-menu"></i>
                    </span>
                </button>
                <div class="topbar-toggler-container" style="display: flex !important; align-items: center !important; justify-content: center !important; height: 100% !important; min-height: 70px !important; position: relative !important; vertical-align: middle !important;">
                    <button class="topbar-toggler more" style="display: flex !important; align-items: center !important; justify-content: center !important; width: 32px !important; height: 32px !important; min-width: 32px !important; min-height: 32px !important; padding: 0 !important; margin: 0 !important; border: none !important; background: transparent !important; box-shadow: none !important; outline: none !important; position: relative !important; vertical-align: middle !important; line-height: 1 !important;">
                        <span class="icon-wrapper" style="display: flex !important; align-items: center !important; justify-content: center !important; width: 100% !important; height: 100% !important; position: relative !important; margin: 0 !important; padding: 0 !important;">
                            <i class="icon-options-vertical" style="display: flex !important; align-items: center !important; justify-content: center !important; margin: 0 !important; padding: 0 !important; font-size: 1.1em !important; position: absolute !important; top: 50% !important; left: 50% !important; transform: translate(-50%, -50%) !important; line-height: 1 !important; vertical-align: middle !important;"></i>
                        </span>
                    </button>
                </div>
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
