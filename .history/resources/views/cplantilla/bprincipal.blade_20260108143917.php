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
