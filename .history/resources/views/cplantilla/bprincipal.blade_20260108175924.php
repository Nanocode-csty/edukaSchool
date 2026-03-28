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

    <!-- Asegurar que Select2 esté disponible globalmente -->
    <script>
        window.initSelect2 = function(selector, options = {}) {
            if (typeof $ !== 'undefined' && $.fn.select2) {
                const defaultOptions = {
                    placeholder: options.placeholder || 'Seleccionar...',
                    allowClear: options.allowClear !== false,
                    width: options.width || '100%',
                    theme: options.theme || 'bootstrap4',
                    minimumResultsForSearch: 0, // Siempre mostrar caja de búsqueda
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
                console.log('Select2 inicializado para:', selector);
                return true;
            } else {
                console.error('Select2 no disponible');
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

                {{-- Corrección para alineación del icono topbar-toggler en vista de reportes --}}
                @if(request()->routeIs('asistencia.reportes'))
                <style>
                    /* Estilos ultra-específicos solo para vista de reportes */
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

                    /* Sobrescribir estilos del framework */
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
        <div class="main-header" style="margin: 0 !important; padding: 0 !important; border: none !important;">
            <!-- Logo Header -->
            <div class="logo-header" style="background-color: #0e4067; margin: 0 !important; padding: 0 !important; border: none !important; border-bottom: 1px solid #0e4067 !important; height: 70px !important; position: relative !important;">
                <style>
                    /* Eliminar cualquier línea blanca en el header */
                    .main-header,
                    .main-header *,
                    .logo-header,
                    .logo-header *,
                    .navbar,
                    .navbar *,
                    nav.navbar,
                    nav.navbar * {
                        border-bottom: none !important;
                        border-top: none !important;
                        border-left: none !important;
                        border-right: none !important;
                        border-color: transparent !important;
                        background-color: #0e4067 !important;
                        box-shadow: none !important;
                        outline: none !important;
                    }

                    /* Asegurar que el navbar tenga el mismo color de fondo */
                    .navbar.navbar-header {
                        background-color: #0e4067 !important;
                        border: none !important;
                        box-shadow: none !important;
                        margin: 0 !important;
                        padding: 0 !important;
                    }

                    /* Eliminar cualquier borde de Bootstrap o Atlantis */
                    .navbar.navbar-header.navbar-expand-lg {
                        border: none !important;
                        border-bottom: none !important;
                        border-top: none !important;
                        box-shadow: none !important;
                    }

                    /* Asegurar que no haya líneas blancas entre elementos */
                    .logo-header + nav,
                    .logo-header + .navbar,
                    .main-header > .navbar {
                        border-top: none !important;
                        margin-top: 0 !important;
                        padding-top: 0 !important;
                    }
                </style>

                <a class="logo" href="{{ route('rutarrr1') }}" style="margin: 0 !important; padding: 0 !important; display: inline-block; height: 100%;">

                    <img src="{{ asset('imagenes/Imagen1.png') }}" alt="Logo de la empresa" style="height: 70px; margin: 0 !important; padding: 0 !important; display: block;">
                </a>

                <!-- Sidebar Toggle Button (después del logo, todas las pantallas) -->
                <button class="btn btn-link text-white p-2 sidebar-toggle d-inline-block ml-3" onclick="toggleCustomSidebar()" title="Menú lateral" style="font-size: 1.2rem; cursor: pointer; background: rgba(14, 64, 103, 0.8); border-radius: 4px; vertical-align: middle; margin-top: 20px;">
                    <i class="fas fa-bars"></i>
                </button>

                <!-- Options Menu Toggle Button (derecha, desde 991px) -->
                <button class="btn btn-link text-white p-2 options-toggle d-lg-none" onclick="toggleMobileMenu()" title="Opciones" style="font-size: 1.2rem; position: absolute; top: 50%; right: 15px; transform: translateY(-50%); z-index: 1070; cursor: pointer; background: rgba(14, 64, 103, 0.8); border-radius: 4px;">
                    <i class="fas fa-ellipsis-v"></i>
                </button>

            </div>

            <!-- Mobile Navbar Bar -->
            <div id="mobile-navbar-bar" class="mobile-navbar-bar d-md-none mobile-navbar-hidden">
                <div class="mobile-navbar-content">
                    <!-- Ir a inicio -->
                    <a class="mobile-navbar-item evitar-recarga" href="{{ route('rutarrr1') }}" title="Ir a inicio">
                        <i class="fas fa-home"></i>
                        <span>Inicio</span>
                    </a>

                    <!-- Notificaciones -->
                    <div class="mobile-navbar-item" onclick="toggleMobileNotifications()" title="Notificaciones">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge badge badge-danger" id="mobile-notification-count" style="display: none;"></span>
                        <span>Notificaciones</span>
                    </div>

                    <!-- ¿Necesitas ayuda? -->
                    <div class="mobile-navbar-item" onclick="handleMobileContactClick(event)" title="¿Necesitas ayuda?">
                        <i class="fa fa-envelope"></i>
                        <span>Ayuda</span>
                    </div>

                    <!-- Operaciones Frecuentes (solo para Administrador) -->
                    @if (auth()->user()->hasRole('Administrador'))
                    <div class="mobile-navbar-item" onclick="toggleMobileOperations()" title="Operaciones Frecuentes">
                        <i class="fas fa-th-large"></i>
                        <span>Operaciones</span>
                    </div>
                    @endif

                    <!-- Foto de perfil (al final) -->
                    <div class="mobile-navbar-item" onclick="toggleMobileProfile()" title="Perfil">
                        <div class="mobile-navbar-profile">
                            <img src="{{ auth()->user()->foto_url ? asset('storage/' . auth()->user()->foto_url) . '?t=' . time() : asset('adminlte/assets/img/profile.jpg') }}"
                                class="avatar-img rounded-circle">
                        </div>
                        <span>Perfil</span>
                    </div>
                </div>
            </div>

            <!-- End Logo Header -->
            <style>
                /* Mobile Navbar Bar - Barra horizontal fija */
                .mobile-navbar-bar {
                    position: fixed;
                    top: 70px;
                    left: 0;
                    right: 0;
                    background: #0e4067;
                    z-index: 1000;
                    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                    display: none; /* Initially hidden */
                    align-items: center;
                    padding: 8px 0;
                    border-top: 1px solid rgba(255, 255, 255, 0.1);
                    opacity: 0;
                    transform: translateY(-30px) scale(0.9);
                    transition: all 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
                }

                .mobile-navbar-bar:not(.mobile-navbar-hidden) {
                    display: flex !important;
                    opacity: 1;
                    transform: translateY(0) scale(1);
                }

                /* Animación escalonada ultra suave para los elementos del navbar */
                .mobile-navbar-bar:not(.mobile-navbar-hidden) .mobile-navbar-item {
                    animation: gentleSlideIn 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
                    opacity: 0;
                    transform: translateY(40px) scale(0.7);
                }

                .mobile-navbar-bar:not(.mobile-navbar-hidden) .mobile-navbar-item:nth-child(1) { animation-delay: 0.15s; }
                .mobile-navbar-bar:not(.mobile-navbar-hidden) .mobile-navbar-item:nth-child(2) { animation-delay: 0.25s; }
                .mobile-navbar-bar:not(.mobile-navbar-hidden) .mobile-navbar-item:nth-child(3) { animation-delay: 0.35s; }
                .mobile-navbar-bar:not(.mobile-navbar-hidden) .mobile-navbar-item:nth-child(4) { animation-delay: 0.45s; }
                .mobile-navbar-bar:not(.mobile-navbar-hidden) .mobile-navbar-item:nth-child(5) { animation-delay: 0.55s; }
                .mobile-navbar-bar:not(.mobile-navbar-hidden) .mobile-navbar-item:nth-child(6) { animation-delay: 0.65s; }

                @keyframes gentleSlideIn {
                    from {
                        opacity: 0;
                        transform: translateY(40px) scale(0.7);
                    }
                    to {
                        opacity: 1;
                        transform: translateY(0) scale(1);
                    }
                }

                .mobile-navbar-content {
                    display: flex;
                    align-items: center;
                    justify-content: space-around;
                    width: 100%;
                    max-width: 100%;
                    padding: 0 10px;
                }

                .mobile-navbar-item {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    color: white;
                    text-decoration: none;
                    padding: 6px 8px;
                    border-radius: 8px;
                    transition: all 0.2s ease;
                    min-width: 60px;
                    text-align: center;
                    cursor: pointer;
                    position: relative;
                }

                .mobile-navbar-item:hover {
                    background: rgba(255, 255, 255, 0.1);
                    transform: translateY(-1px);
                }

                .mobile-navbar-item i {
                    font-size: 16px;
                    margin-bottom: 2px;
                    display: block;
                }

                .mobile-navbar-item span {
                    font-size: 10px;
                    font-weight: 500;
                    line-height: 1.2;
                    display: block;
                }

                .mobile-navbar-profile {
                    width: 24px;
                    height: 24px;
                    border-radius: 50%;
                    overflow: hidden;
                    margin-bottom: 2px;
                }

                .mobile-navbar-profile img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                }

                .notification-badge {
                    position: absolute;
                    top: 2px;
                    right: 2px;
                    background: #dc3545 !important;
                    color: white !important;
                    font-size: 8px !important;
                    padding: 1px 4px !important;
                    border-radius: 8px !important;
                    font-weight: bold;
                    min-width: 14px;
                    text-align: center;
                    border: 1px solid #0e4067;
                }

                /* Hide original navbar on tablets and smaller */
                @media (max-width: 991.98px) {
                    nav.navbar {
                        display: none !important;
                    }

                    /* Ajustar el contenido principal para que no se oculte detrás de la barra */
                    .main-panel {
                        padding-top: 50px !important;
                    }

                    .content {
                        padding-top: 20px !important;
                    }
                }

                /* Responsive para móviles muy pequeños */
                @media (max-width: 480px) {
                    .mobile-navbar-item {
                        min-width: 50px;
                        padding: 4px 6px;
                    }

                    .mobile-navbar-item i {
                        font-size: 14px;
                    }

                    .mobile-navbar-item span {
                        font-size: 9px;
                    }

                    .mobile-navbar-profile {
                        width: 20px;
                        height: 20px;
                    }
                }

                /* Mobile Dropdown Styles - High contrast white text on blue background */
                .mobile-dropdown-overlay {
                    position: fixed;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background: rgba(0, 0, 0, 0.5);
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    z-index: 10000;
                }

                .mobile-dropdown-content {
                    background: #0e4067;
                    border-radius: 12px;
                    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
                    max-width: 95vw; /* Más ancho */
                    width: 400px; /* Ancho fijo más grande */
                    max-height: 80vh;
                    overflow-y: auto;
                    color: white;
                    font-family: "Quicksand", sans-serif;
                }

                /* Responsive para perfil móvil */
                @media (max-width: 480px) {
                    .mobile-dropdown-content {
                        max-width: 90vw;
                        width: 350px;
                        margin: 20px;
                    }
                }

                @media (min-width: 481px) and (max-width: 768px) {
                    .mobile-dropdown-content {
                        width: 380px;
                    }
                }

                .mobile-dropdown-header {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    padding: 15px 20px;
                    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
                }

                .mobile-dropdown-header h6 {
                    margin: 0;
                    color: white;
                    font-weight: 600;
                }

                .mobile-dropdown-close {
                    background: none;
                    border: none;
                    color: white;
                    font-size: 24px;
                    cursor: pointer;
                    padding: 0;
                    width: 30px;
                    height: 30px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    border-radius: 50%;
                    transition: background 0.2s ease;
                }

                .mobile-dropdown-close:hover {
                    background: rgba(255, 255, 255, 0.1);
                }

                .mobile-dropdown-body {
                    padding: 20px;
                }

                .mobile-dropdown-footer {
                    padding: 15px 20px;
                    border-top: 1px solid rgba(255, 255, 255, 0.1);
                    text-align: center;
                }

                .mobile-dropdown-footer .btn {
                    background: #28a745;
                    border: none;
                    color: white;
                    font-weight: 500;
                }

                .mobile-dropdown-footer .btn:hover {
                    background: #218838;
                    color: white;
                }

                /* Mobile Profile Styles */
                .mobile-profile-info {
                    display: flex;
                    align-items: center;
                    gap: 15px;
                    margin-bottom: 15px;
                }

                .mobile-profile-avatar img {
                    width: 50px;
                    height: 50px;
                    border: 2px solid rgba(255, 255, 255, 0.3);
                }

                .mobile-profile-details h6 {
                    margin: 0 0 5px 0;
                    color: white;
                    font-size: 16px;
                }

                .mobile-profile-details .badge {
                    background: #347f65;
                    color: white;
                    font-size: 12px;
                }

                .mobile-dropdown-link {
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    padding: 12px 0;
                    color: white;
                    text-decoration: none;
                    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
                    transition: all 0.2s ease;
                }

                .mobile-dropdown-link:hover {
                    color: #28a745;
                    transform: translateX(5px);
                }

                .mobile-dropdown-link i {
                    width: 20px;
                    text-align: center;
                }

                .mobile-dropdown-link.text-danger:hover {
                    color: #dc3545 !important;
                }

                /* Mobile Notifications Styles */
                .mobile-notification-item {
                    display: flex;
                    gap: 12px;
                    padding: 15px 0;
                    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
                }

                .mobile-notification-item:last-child {
                    border-bottom: none;
                }

                .mobile-notification-item.unread {
                    border-left: 3px solid #28a745;
                    padding-left: 12px;
                }

                .mobile-notification-icon {
                    flex-shrink: 0;
                    width: 30px;
                    height: 30px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    border-radius: 50%;
                    background: rgba(255, 255, 255, 0.1);
                }

                .mobile-notification-content {
                    flex: 1;
                }

                .mobile-notification-title {
                    font-weight: 600;
                    color: white;
                    margin-bottom: 5px;
                    font-size: 14px;
                }

                .mobile-notification-message {
                    color: rgba(255, 255, 255, 0.8);
                    font-size: 13px;
                    line-height: 1.4;
                    margin-bottom: 5px;
                }

                .mobile-notification-time {
                    color: rgba(255, 255, 255, 0.6);
                    font-size: 11px;
                }

                /* Desktop navbar dropdown text colors - White text on blue background */
                .dropdown-menu.dropdown-menu-right.notification-dropdown .dropdown-item,
                .dropdown-menu.dropdown-menu-right.notification-dropdown .dropdown-item *,
                .dropdown-menu.dropdown-user .dropdown-item,
                .dropdown-menu.dropdown-user .dropdown-item *,
                .dropdown-menu.dropdown-user .user-box,
                .dropdown-menu.dropdown-user .user-box *,
                .dropdown-menu.dropdown-user .u-text,
                .dropdown-menu.dropdown-user .u-text * {
                    color: white !important;
                    background-color: transparent !important;
                }

                .dropdown-menu.dropdown-menu-right.notification-dropdown .dropdown-item:hover,
                .dropdown-menu.dropdown-user .dropdown-item:hover {
                    background-color: rgba(255, 255, 255, 0.1) !important;
                    color: white !important;
                }

                .dropdown-menu.dropdown-menu-right.notification-dropdown .dropdown-header,
                .dropdown-menu.dropdown-menu-right.notification-dropdown .dropdown-footer {
                    color: white !important;
                    font-weight: 600 !important;
                }

                .dropdown-menu.dropdown-user .user-box .u-text h4 {
                    color: white !important;
                    font-weight: 600 !important;
                }

                .dropdown-menu.dropdown-user .user-box .u-text .badge {
                    background-color: #28a745 !important;
                    color: white !important;
                }

                .dropdown-menu.dropdown-user .dropdown-divider {
                    border-top-color: rgba(255, 255, 255, 0.2) !important;
                }

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

        // Custom sidebar toggle functionality - Overlay mode
        let sidebarVisible = false; // Start hidden for overlay mode

        // Auto-close mobile navbar when screen becomes larger than 992px
        window.addEventListener('resize', function() {
            const mobileNavbar = document.getElementById('mobile-navbar-bar');
            if (window.innerWidth >= 992 && mobileNavbar && !mobileNavbar.classList.contains('mobile-navbar-hidden')) {
                console.log('Cerrando automáticamente la barra móvil - pantalla >= 992px');
                mobileNavbar.classList.add('mobile-navbar-hidden');
                mobileNavbar.style.setProperty('display', 'none', 'important');
            }
        });

        // Create custom sidebar overlay - Complete replacement approach
        document.addEventListener('DOMContentLoaded', function() {
            // Remove existing sidebar styles that might conflict
            const existingSidebar = document.querySelector('.sidebar');
            if (existingSidebar) {
                existingSidebar.style.display = 'none';
            }

            // Create custom overlay sidebar with collapsible sections
            const customSidebar = document.createElement('div');
            customSidebar.id = 'custom-sidebar-overlay';
            customSidebar.innerHTML = `
                <div class="custom-sidebar-header">
                    <div class="custom-user-info">
                        <img src="{{ asset('imagenes/imgDocente.png') }}" alt="User" class="custom-user-avatar">
                        <div class="custom-user-details">
                            <div class="custom-user-name">{{ auth()->user()->nombres }}</div>
                            <div class="custom-user-role">{{ auth()->user()->rol }}</div>
                        </div>
                    </div>
                </div>
                <nav class="custom-sidebar-nav">
                    @if (auth()->user()->hasRole('Administrador'))
                        <!-- TRANSACCIONES Section -->
                        <div class="custom-nav-group">
                            <div class="custom-group-title">TRANSACCIONES</div>

                            <!-- Matrículas -->
                            <div class="custom-nav-section">
                                <div class="custom-section-header matriculas-header" data-toggle="matriculas">
                                    <div class="custom-section-icon">
                                        <i class="fas fa-user-graduate"></i>
                                    </div>
                                    <span class="custom-section-title">Matrículas</span>
                                    <i class="fas fa-chevron-down custom-section-arrow"></i>
                                </div>
                                <div class="custom-section-content" id="matriculas-content">
                                    <a href="{{ route('matriculas.create') }}" class="custom-nav-link">
                                        <i class="fas fa-plus-circle"></i>
                                        <span>Nueva Matrícula</span>
                                    </a>
                                    <a href="{{ route('matriculas.index') }}" class="custom-nav-link">
                                        <i class="fas fa-list"></i>
                                        <span>Consultar Matrículas</span>
                                    </a>
                                </div>
                            </div>

                            <!-- Pagos -->
                            <div class="custom-nav-section">
                                <div class="custom-section-header pagos-header" data-toggle="pagos">
                                    <div class="custom-section-icon">
                                        <i class="fas fa-wallet"></i>
                                    </div>
                                    <span class="custom-section-title">Pagos</span>
                                    <i class="fas fa-chevron-down custom-section-arrow"></i>
                                </div>
                                <div class="custom-section-content" id="pagos-content">
                                    <a href="{{ route('conceptospago.index') }}" class="custom-nav-link">
                                        <i class="fas fa-tags"></i>
                                        <span>Conceptos de Pago</span>
                                    </a>
                                    <a href="{{ route('pagos.index') }}" class="custom-nav-link">
                                        <i class="fas fa-credit-card"></i>
                                        <span>Administrar Pagos</span>
                                    </a>
                                </div>
                            </div>

                            <!-- Asistencias -->
                            <div class="custom-nav-section">
                                <div class="custom-section-header asistencias-header" data-toggle="asistencias">
                                    <div class="custom-section-icon">
                                        <i class="fas fa-calendar-check"></i>
                                    </div>
                                    <span class="custom-section-title">Asistencias</span>
                                    <i class="fas fa-chevron-down custom-section-arrow"></i>
                                </div>
                                <div class="custom-section-content" id="asistencias-content">
                                    <a href="{{ route('asistencia.admin-index') }}" class="custom-nav-link">
                                        <i class="fas fa-chart-bar"></i>
                                        <span>Administrar</span>
                                    </a>
                                    <a href="{{ route('asistencia.reportes') }}" class="custom-nav-link">
                                        <i class="fas fa-file-alt"></i>
                                        <span>Reportes</span>
                                    </a>
                                    <a href="{{ route('asistencia.verificar') }}" class="custom-nav-link">
                                        <i class="fas fa-check-circle"></i>
                                        <span>Justificaciones</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- MANTENEDORES Section -->
                        <div class="custom-nav-group">
                            <div class="custom-group-title">MANTENEDORES</div>

                            <!-- Gestión Académica -->
                            <div class="custom-nav-section">
                                <div class="custom-section-header mantenedores-header" data-toggle="academica">
                                    <div class="custom-section-icon">
                                        <i class="fas fa-school"></i>
                                    </div>
                                    <span class="custom-section-title">Académico</span>
                                    <i class="fas fa-chevron-down custom-section-arrow"></i>
                                </div>
                                <div class="custom-section-content" id="academica-content">
                                    <a href="{{ route('asignaturas.index') }}" class="custom-nav-link mantenedores-link">
                                        <i class="fas fa-book"></i>
                                        <span>Asignaturas</span>
                                    </a>
                                    <a href="{{ route('periodos-evaluacion.index') }}" class="custom-nav-link mantenedores-link">
                                        <i class="fas fa-calendar-alt"></i>
                                        <span>Periodos</span>
                                    </a>
                                    <a href="{{ route('registrarcurso.index') }}" class="custom-nav-link mantenedores-link">
                                        <i class="fas fa-graduation-cap"></i>
                                        <span>Cursos</span>
                                    </a>
                                </div>
                            </div>

                            <!-- Gestión Estudiantil -->
                            <div class="custom-nav-section">
                                <div class="custom-section-header mantenedores-header" data-toggle="estudiantil">
                                    <div class="custom-section-icon">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <span class="custom-section-title">Estudiantil</span>
                                    <i class="fas fa-chevron-down custom-section-arrow"></i>
                                </div>
                                <div class="custom-section-content" id="estudiantil-content">
                                    <a href="{{ route('estudiante.index') }}" class="custom-nav-link mantenedores-link">
                                        <i class="fas fa-user-plus"></i>
                                        <span>Estudiantes</span>
                                    </a>
                                    <a href="{{ route('registrarrepresentante.index') }}" class="custom-nav-link mantenedores-link">
                                        <i class="fas fa-user-tie"></i>
                                        <span>Representantes</span>
                                    </a>
                                </div>
                            </div>

                            <!-- Gestión Docente -->
                            <div class="custom-nav-section">
                                <div class="custom-section-header mantenedores-header" data-toggle="docente">
                                    <div class="custom-section-icon">
                                        <i class="fas fa-chalkboard-teacher"></i>
                                    </div>
                                    <span class="custom-section-title">Docente</span>
                                    <i class="fas fa-chevron-down custom-section-arrow"></i>
                                </div>
                                <div class="custom-section-content" id="docente-content">
                                    <a href="{{ route('registrardocente.index') }}" class="custom-nav-link mantenedores-link">
                                        <i class="fas fa-user-graduate"></i>
                                        <span>Docentes</span>
                                    </a>
                                    <a href="{{ route('cursoasignatura.index') }}" class="custom-nav-link mantenedores-link">
                                        <i class="fas fa-clipboard-list"></i>
                                        <span>Carga Académica</span>
                                    </a>
                                </div>
                            </div>

                            <!-- Plan Educativo -->
                            <div class="custom-nav-section">
                                <div class="custom-section-header mantenedores-header" data-toggle="plan">
                                    <div class="custom-section-icon">
                                        <i class="fas fa-map"></i>
                                    </div>
                                    <span class="custom-section-title">Plan Educativo</span>
                                    <i class="fas fa-chevron-down custom-section-arrow"></i>
                                </div>
                                <div class="custom-section-content" id="plan-content">
                                    <a href="{{ route('registrarnivel.index') }}" class="custom-nav-link mantenedores-link">
                                        <i class="fas fa-layer-group"></i>
                                        <span>Niveles</span>
                                    </a>
                                    <a href="{{ route('aulas.index') }}" class="custom-nav-link mantenedores-link">
                                        <i class="fas fa-door-open"></i>
                                        <span>Aulas</span>
                                    </a>
                                    <a href="{{ route('grados.index') }}" class="custom-nav-link mantenedores-link">
                                        <i class="fas fa-sort-numeric-up"></i>
                                        <span>Grados</span>
                                    </a>
                                    <a href="{{ route('secciones.index') }}" class="custom-nav-link mantenedores-link">
                                        <i class="fas fa-th-large"></i>
                                        <span>Secciones</span>
                                    </a>
                                    <a href="{{ route('aniolectivo.index') }}" class="custom-nav-link mantenedores-link">
                                        <i class="fas fa-calendar"></i>
                                        <span>Año Lectivo</span>
                                    </a>
                                </div>
                            </div>

                            <!-- Sistema -->
                            <div class="custom-nav-section">
                                <div class="custom-section-header mantenedores-header" data-toggle="sistema">
                                    <div class="custom-section-icon">
                                        <i class="fas fa-cogs"></i>
                                    </div>
                                    <span class="custom-section-title">Sistema</span>
                                    <i class="fas fa-chevron-down custom-section-arrow"></i>
                                </div>
                                <div class="custom-section-content" id="sistema-content">
                                    <a href="{{ route('feriados.index') }}" class="custom-nav-link mantenedores-link">
                                        <i class="fas fa-calendar-times"></i>
                                        <span>Feriados</span>
                                    </a>
                                    <a href="{{ route('comunicado.index') }}" class="custom-nav-link mantenedores-link">
                                        <i class="fas fa-bullhorn"></i>
                                        <span>Comunicados</span>
                                    </a>
                                </div>
                            </div>

                            <!-- Usuarios -->
                            <div class="custom-nav-section">
                                <div class="custom-section-header mantenedores-header" data-toggle="usuarios">
                                    <div class="custom-section-icon">
                                        <i class="fas fa-user-cog"></i>
                                    </div>
                                    <span class="custom-section-title">Usuarios</span>
                                    <i class="fas fa-chevron-down custom-section-arrow"></i>
                                </div>
                                <div class="custom-section-content" id="usuarios-content">
                                    <a href="{{ route('usuarios.index') }}" class="custom-nav-link mantenedores-link">
                                        <i class="fas fa-users-cog"></i>
                                        <span>Detalle Usuario</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (auth()->user()->hasRole('Docente'))
                        <!-- Mis Asistencias Section -->
                        <div class="custom-nav-section">
                            <div class="custom-section-header docente-section-header" data-toggle="docente-asistencias">
                                <div class="custom-section-icon">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                </div>
                                <span class="custom-section-title">Mis Asistencias</span>
                                <i class="fas fa-chevron-down custom-section-arrow"></i>
                            </div>
                            <div class="custom-section-content" id="docente-asistencias-content">
                                <a href="{{ route('asistencia.docente.dashboard') }}" class="custom-nav-link">
                                    <i class="fas fa-tachometer-alt"></i>
                                    <span>Panel Integrado</span>
                                </a>
                                <a href="{{ route('asistencia.docente.index') }}" class="custom-nav-link">
                                    <i class="fas fa-clipboard-check"></i>
                                    <span>Tomar Asistencia</span>
                                </a>
                            </div>
                        </div>
                    @endif

                    @if (auth()->user()->hasRole('representante'))
                        <!-- Mis Estudiantes Section -->
                        <div class="custom-nav-section">
                            <div class="custom-section-header representante-header" data-toggle="representante-estudiantes">
                                <div class="custom-section-icon">
                                    <i class="fas fa-user-friends"></i>
                                </div>
                                <span class="custom-section-title">Mis Estudiantes</span>
                                <i class="fas fa-chevron-down custom-section-arrow"></i>
                            </div>
                            <div class="custom-section-content" id="representante-estudiantes-content">
                                <a href="{{ route('asistencia.representante.dashboard') }}" class="custom-nav-link">
                                    <i class="fas fa-home"></i>
                                    <span>Panel Principal</span>
                                </a>
                                <a href="{{ route('asistencia.representante.index') }}" class="custom-nav-link">
                                    <i class="fas fa-eye"></i>
                                    <span>Ver Asistencias</span>
                                </a>
                                <a href="{{ route('notas.misEstudiantes') }}" class="custom-nav-link">
                                    <i class="fas fa-chart-line"></i>
                                    <span>Ver Calificaciones</span>
                                </a>
                            </div>
                        </div>
                    @endif

                    @if (auth()->user()->hasRole('Estudiante'))
                        <!-- Mi Panel Section -->
                        <div class="custom-nav-section">
                            <div class="custom-section-header estudiante-header" data-toggle="estudiante-panel">
                                <div class="custom-section-icon">
                                    <i class="fas fa-user-graduate"></i>
                                </div>
                                <span class="custom-section-title">Mi Panel</span>
                                <i class="fas fa-chevron-down custom-section-arrow"></i>
                            </div>
                            <div class="custom-section-content" id="estudiante-panel-content">
                                <a href="{{ route('estudiante.dashboard') }}" class="custom-nav-link">
                                    <i class="fas fa-tachometer-alt"></i>
                                    <span>Dashboard</span>
                                </a>
                                <a href="{{ route('estudiante.asistencia') }}" class="custom-nav-link">
                                    <i class="fas fa-calendar-check"></i>
                                    <span>Mi Asistencia</span>
                                </a>
                                <a href="{{ route('estudiante.calificaciones') }}" class="custom-nav-link">
                                    <i class="fas fa-chart-bar"></i>
                                    <span>Mis Calificaciones</span>
                                </a>
                            </div>
                        </div>
                    @endif
                </nav>
            `;

            // Add custom styles
            const customStyles = document.createElement('style');
            customStyles.textContent = `
                #custom-sidebar-overlay {
                    position: fixed !important;
                    top: 70px !important;
                    left: -280px !important; /* Inicialmente oculto fuera de la pantalla */
                    width: 280px !important;
                    height: calc(100vh - 70px) !important;
                    background: linear-gradient(180deg, #1a4d7a 0%, #245a8d 100%) !important;
                    color: white !important;
                    display: block !important; /* Siempre visible para la transición */
                    z-index: 999999 !important; /* Z-index ultra alto */
                    overflow-y: auto !important;
                    box-shadow: 2px 0 10px rgba(0,0,0,0.1) !important;
                    margin: 0 !important;
                    padding: 0 !important;
                    border: none !important;
                    opacity: 1 !important;
                    visibility: visible !important;
                    transition: left 0.4s cubic-bezier(0.4, 0, 0.2, 1) !important; /* Transición suave */
                }

                /* Custom scrollbar styling */
                #custom-sidebar-overlay::-webkit-scrollbar {
                    width: 8px;
                }

                #custom-sidebar-overlay::-webkit-scrollbar-track {
                    background: rgba(255, 255, 255, 0.1);
                    border-radius: 10px;
                }

                #custom-sidebar-overlay::-webkit-scrollbar-thumb {
                    background: linear-gradient(180deg, #4a90e2 0%, #357abd 100%);
                    border-radius: 10px;
                    border: 2px solid rgba(255, 255, 255, 0.1);
                }

                #custom-sidebar-overlay::-webkit-scrollbar-thumb:hover {
                    background: linear-gradient(180deg, #5ba0f2 0%, #4a90e2 100%);
                }

                /* Firefox scrollbar */
                #custom-sidebar-overlay {
                    scrollbar-width: thin;
                    scrollbar-color: #4a90e2 rgba(255, 255, 255, 0.1);
                }

                #custom-sidebar-overlay.show {
                    transform: translateX(0);
                }

                .custom-sidebar-header {
                    padding: 20px;
                    border-bottom: 1px solid rgba(255,255,255,0.1);
                    background: rgba(255,255,255,0.05);
                }

                .custom-user-info {
                    display: flex;
                    align-items: center;
                    gap: 12px;
                }

                .custom-user-avatar {
                    width: 45px;
                    height: 45px;
                    border-radius: 50%;
                    border: 2px solid rgba(255,255,255,0.3);
                }

                .custom-user-name {
                    font-weight: 600;
                    font-size: 14px;
                    margin-bottom: 2px;
                }

                .custom-user-role {
                    font-size: 12px;
                    opacity: 0.8;
                }

                .custom-sidebar-nav {
                    padding: 10px 0;
                }

                /* Group Titles */
                .custom-nav-group {
                    margin-bottom: 20px;
                }

                .custom-group-title {
                    font-size: 11px;
                    font-weight: 700;
                    color: rgba(255,255,255,0.6);
                    text-transform: uppercase;
                    letter-spacing: 1px;
                    padding: 8px 20px;
                    margin: 15px 0 8px 0;
                    border-bottom: 1px solid rgba(255,255,255,0.1);
                }

                .custom-nav-section {
                    margin-bottom: 6px;
                }

                /* Section Headers with solid colors */
                .custom-section-header {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    padding: 12px 18px;
                    cursor: pointer;
                    transition: all 0.3s ease;
                    border-radius: 8px;
                    margin: 0 8px;
                    font-weight: 600;
                    font-size: 13px;
                    border: 2px solid transparent;
                }

                .custom-section-header:hover {
                    transform: translateX(2px);
                    border-color: rgba(255,255,255,0.3);
                }

                .custom-section-icon {
                    width: 18px;
                    text-align: center;
                    margin-right: 10px;
                    font-size: 14px;
                }

                .custom-section-title {
                    flex: 1;
                    font-weight: 600;
                }

                .custom-section-arrow {
                    transition: transform 0.3s ease;
                    font-size: 11px;
                    opacity: 0.8;
                }

                .custom-section-header.collapsed .custom-section-arrow {
                    transform: rotate(180deg);
                }

                /* TRANSACCIONES - Solid colors */
                .matriculas-header {
                    background: #28a745;
                    color: white;
                }

                .pagos-header {
                    background: #ffc107;
                    color: #333;
                }

                .asistencias-header {
                    background: #007bff;
                    color: white;
                }

                /* TRANSACCIONES - Solid colors */
                .matriculas-header {
                    background: #28a745 !important;
                    color: white !important;
                }

                .pagos-header {
                    background: #ffc107 !important;
                    color: #333 !important;
                }

                .asistencias-header {
                    background: #007bff !important;
                    color: white !important;
                }

                /* MANTENEDORES - Marrón claro para TODOS los headers de mantenedores */
                .custom-nav-group:nth-child(2) .custom-section-header,
                .academica-header,
                .estudiantil-header,
                .docente-header,
                .plan-header,
                .sistema-header,
                .usuarios-header,
                .mantenedores-header {
                    background: #deb887 !important;
                    color: #5d4e37 !important;
                    border: 2px solid #c69c6d !important;
                }

                /* Forzar color marrón claro en todos los headers de la segunda sección (MANTENEDORES) */
                .custom-nav-group:nth-child(2) .custom-section-header {
                    background: #deb887 !important;
                    color: #5d4e37 !important;
                    border: 2px solid #c69c6d !important;
                }

                /* Excepciones para headers que no son de mantenedores */
                .docente-section-header,
                .representante-header,
                .estudiante-header {
                    background: inherit !important;
                    color: inherit !important;
                    border: inherit !important;
                }

                /* Role-specific colors */
                .docente-section-header {
                    background: #28a745;
                    color: white;
                }

                .representante-header {
                    background: #ffc107;
                    color: #333;
                }

                .estudiante-header {
                    background: #007bff;
                    color: white;
                }

                /* Section Content */
                .custom-section-content {
                    max-height: 0;
                    overflow: hidden;
                    transition: max-height 0.4s ease, padding 0.4s ease;
                    background: rgba(255,255,255,0.03);
                    margin: 0 8px;
                    border-radius: 0 0 8px 8px;
                    border: 1px solid rgba(255,255,255,0.1);
                    border-top: none;
                }

                .custom-section-content.show {
                    max-height: 500px;
                    padding: 8px 0;
                }

                .custom-nav-item {
                    margin: 2px 0;
                }

                /* Mantenedores links - now white like transactions */
                .mantenedores-link {
                    color: rgba(255,255,255,0.9) !important;
                }

                .mantenedores-link:hover {
                    background: rgba(255,255,255,0.15) !important;
                    color: white !important;
                }

                .custom-nav-link {
                    display: flex;
                    align-items: center;
                    padding: 8px 20px;
                    color: rgba(255,255,255,0.9);
                    text-decoration: none;
                    transition: all 0.2s ease;
                    gap: 8px;
                    font-size: 12px;
                    border-radius: 4px;
                    margin: 1px 4px;
                }

                .custom-nav-link:hover {
                    background: rgba(255,255,255,0.15);
                    color: white;
                    text-decoration: none;
                    transform: translateX(2px);
                }

                .custom-nav-link i {
                    width: 14px;
                    text-align: center;
                    font-size: 11px;
                }

                .custom-nav-link span {
                    flex: 1;
                    font-weight: 500;
                }

                /* Responsive adjustments */
                @media (max-width: 768px) {
                    #custom-sidebar-overlay {
                        width: 250px;
                    }

                    .custom-section-header {
                        padding: 10px 15px;
                        font-size: 12px;
                    }

                    .custom-nav-link {
                        padding: 6px 15px;
                        font-size: 11px;
                    }

                    .custom-group-title {
                        font-size: 10px;
                        padding: 6px 15px;
                    }
                }
            `;

            // Add to page
            document.head.appendChild(customStyles);
            document.body.appendChild(customSidebar);

            // Add collapsible functionality
            const sectionHeaders = customSidebar.querySelectorAll('.custom-section-header');
            sectionHeaders.forEach(header => {
                header.addEventListener('click', function() {
                    const toggleId = this.getAttribute('data-toggle');
                    const content = document.getElementById(toggleId + '-content');

                    if (content) {
                        // Toggle collapsed class on header
                        this.classList.toggle('collapsed');

                        // Toggle show class on content
                        content.classList.toggle('show');

                        console.log('Toggled section:', toggleId);
                    }
                });
            });

            console.log('Custom sidebar overlay created successfully');
        });

        function toggleMobileMenu() {
            console.log('toggleMobileMenu called');

            try {
                const mobileNavbar = document.getElementById('mobile-navbar-bar');

                if (mobileNavbar) {
                    // Toggle the hidden class
                    if (mobileNavbar.classList.contains('mobile-navbar-hidden')) {
                        // Show the navbar
                        console.log('mostrando barra de navegación móvil');
                        mobileNavbar.classList.remove('mobile-navbar-hidden');
                        mobileNavbar.style.setProperty('display', 'flex', 'important');
                        console.log('barra de navegación móvil mostrada exitosamente');
                    } else {
                        // Hide the navbar
                        console.log('ocultando barra de navegación móvil');
                        mobileNavbar.classList.add('mobile-navbar-hidden');
                        mobileNavbar.style.setProperty('display', 'none', 'important');
                        console.log('barra de navegación móvil ocultada exitosamente');
                    }
                } else {
                    console.log('elemento mobile-navbar-bar no encontrado');
                }
            } catch (error) {
                console.error('Error en toggleMobileMenu:', error);
            }
        }

        function toggleCustomSidebar() {
            console.log('toggleCustomSidebar called, current state:', sidebarVisible);

            try {
                const sidebar = document.getElementById('custom-sidebar-overlay');

                if (sidebar) {
                    if (sidebarVisible) {
                        // Ocultar sidebar (deslizar hacia la izquierda)
                        console.log('ocultando sidebar overlay');
                        sidebar.style.setProperty('left', '-280px', 'important');
                        sidebarVisible = false;
                        console.log('sidebar ocultado exitosamente');
                    } else {
                        // Mostrar sidebar (deslizar hacia la derecha)
                        console.log('mostrando sidebar overlay');
                        sidebar.style.setProperty('left', '0px', 'important');
                        sidebarVisible = true;
                        console.log('sidebar mostrado exitosamente');
                    }
                } else {
                    console.log('elemento custom sidebar no encontrado - intentando fallback');
                    // Fallback al sidebar original si el custom no existe
                    const fallbackSidebar = document.querySelector('.sidebar');
                    if (fallbackSidebar) {
                        if (sidebarVisible) {
                            fallbackSidebar.style.setProperty('left', '-280px', 'important');
                            sidebarVisible = false;
                        } else {
                            fallbackSidebar.style.setProperty('left', '0px', 'important');
                            sidebarVisible = true;
                        }
                        console.log('fallback sidebar usado');
                    } else {
                        console.log('ningún sidebar encontrado');
                    }
                }
            } catch (error) {
                console.error('Error en toggleCustomSidebar:', error);
            }
        }

        // Mobile-specific functions for navbar items
        function toggleMobileNotifications() {
            console.log('toggleMobileNotifications called');
            // Create or toggle mobile notifications dropdown
            let mobileNotifications = document.getElementById('mobile-notifications-dropdown');

            if (!mobileNotifications) {
                // Create mobile notifications dropdown
                mobileNotifications = document.createElement('div');
                mobileNotifications.id = 'mobile-notifications-dropdown';
                mobileNotifications.className = 'mobile-dropdown-overlay';
                mobileNotifications.innerHTML = `
                    <div class="mobile-dropdown-content">
                        <div class="mobile-dropdown-header">
                            <h6>Notificaciones</h6>
                            <button class="mobile-dropdown-close" onclick="closeMobileDropdown('mobile-notifications-dropdown')">&times;</button>
                        </div>
                        <div class="mobile-dropdown-body" id="mobile-notification-list">
                            <div class="text-center text-muted">Cargando...</div>
                        </div>
                        <div class="mobile-dropdown-footer">
                            <a href="{{ route('notificaciones.index') }}" class="btn btn-primary btn-sm">Ver todas</a>
                        </div>
                    </div>
                `;
                document.body.appendChild(mobileNotifications);

                // Load notifications
                loadMobileNotifications();
            }

            // Toggle visibility
            mobileNotifications.style.display = mobileNotifications.style.display === 'flex' ? 'none' : 'flex';
            console.log('Mobile notifications toggled');
        }

        function handleMobileContactClick(event) {
            console.log('handleMobileContactClick called');
            // Same logic as desktop version
            event.preventDefault();
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

        function toggleMobileOperations() {
            console.log('toggleMobileOperations called');
            // Toggle the quick actions dropdown
            const quickElement = document.getElementById('quick');
            if (quickElement) {
                quickElement.classList.toggle('d-none');
                console.log('Operaciones toggled');
            } else {
                console.log('quick element not found');
            }
        }

        function toggleMobileProfile() {
            console.log('toggleMobileProfile called');
            // Create or toggle mobile profile dropdown
            let mobileProfile = document.getElementById('mobile-profile-dropdown');

            if (!mobileProfile) {
                // Create mobile profile dropdown
                mobileProfile = document.createElement('div');
                mobileProfile.id = 'mobile-profile-dropdown';
                mobileProfile.className = 'mobile-dropdown-overlay';
                mobileProfile.innerHTML = `
                    <div class="mobile-dropdown-content">
                        <div class="mobile-dropdown-header">
                            <h6>Perfil</h6>
                            <button class="mobile-dropdown-close" onclick="closeMobileDropdown('mobile-profile-dropdown')">&times;</button>
                        </div>
                        <div class="mobile-dropdown-body">
                            <div class="mobile-profile-info">
                                <div class="mobile-profile-avatar">
                                    <img src="{{ auth()->user()->foto_url ? asset('storage/' . auth()->user()->foto_url) . '?t=' . time() : asset('adminlte/assets/img/profile.jpg') }}"
                                         class="avatar-img rounded-circle">
                                </div>
                                <div class="mobile-profile-details">
                                    <h6>{{ auth()->user()->nombres }} {{ auth()->user()->apellidos }}</h6>
                                    <span class="badge">{{ auth()->user()->rol }}</span>
                                </div>
                            </div>
                            <hr>
                            <a href="{{ route('profile.show') }}" class="mobile-dropdown-link">
                                <i class="fas fa-user"></i> Ver Perfil
                            </a>
                            <a href="#" class="mobile-dropdown-link text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                            </a>
                        </div>
                    </div>
                `;
                document.body.appendChild(mobileProfile);
            }

            // Toggle visibility
            mobileProfile.style.display = mobileProfile.style.display === 'flex' ? 'none' : 'flex';
            console.log('Mobile profile toggled');
        }

        function closeMobileDropdown(dropdownId) {
            const dropdown = document.getElementById(dropdownId);
            if (dropdown) {
                dropdown.style.display = 'none';
            }
        }

        function loadMobileNotifications() {
            fetch('{{ route('notificaciones.recent') }}')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const list = document.getElementById('mobile-notification-list');
                        if (list && data.data.length > 0) {
                            let html = '';
                            data.data.forEach(notification => {
                                const isUnread = !notification.leido_en;
                                const timeAgo = new Date(notification.created_at).toLocaleDateString('es-ES', {
                                    day: 'numeric',
                                    month: 'short',
                                    hour: '2-digit',
                                    minute: '2-digit'
                                });

                                html += `
                                    <div class="mobile-notification-item ${isUnread ? 'unread' : ''}">
                                        <div class="mobile-notification-icon">
                                            <i class="${notification.icono}" style="color: ${notification.color}"></i>
                                        </div>
                                        <div class="mobile-notification-content">
                                            <div class="mobile-notification-title">${notification.titulo}</div>
                                            <div class="mobile-notification-message">${notification.mensaje.length > 60 ? notification.mensaje.substring(0, 60) + '...' : notification.mensaje}</div>
                                            <small class="mobile-notification-time">${timeAgo}</small>
                                        </div>
                                    </div>
                                `;
                            });
                            list.innerHTML = html;
                        } else if (list) {
                            list.innerHTML = '<div class="text-center text-muted">No hay notificaciones</div>';
                        }
                    }
                })
                .catch(error => console.error('Error loading mobile notifications:', error));
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

        /* Hide original sidebar completely */
        .sidebar {
            display: none !important;
            visibility: hidden !important;
            opacity: 0 !important;
        }

        .sidebar .sidebar-wrapper,
        .sidebar .sidebar-content,
        .sidebar .user,
        .sidebar .nav,
        .sidebar .nav .nav-item,
        .sidebar .nav .nav-item a {
            display: none !important;
            visibility: hidden !important;
            opacity: 0 !important;
        }

        /* Ensure main panel takes full width - no space reserved for sidebar */
        .main-panel {
            margin-left: 0 !important;
            width: 100% !important;
            padding-left: 0 !important;
        }

        /* Override any Atlantis framework styles that might reserve space */
        .sidebar ~ .main-panel,
        .sidebar-force-hidden ~ .main-panel,
        .sidebar:not(.sidebar-force-hidden) ~ .main-panel {
            margin-left: 0 !important;
            width: 100% !important;
            padding-left: 0 !important;
        }

        /* Remove any margin/padding that might be applied by framework */
        .wrapper.overlay-sidebar .main-panel {
            margin-left: 0 !important;
            width: 100% !important;
            padding-left: 0 !important;
        }
        </style>
    @endif
</body>

</html>
        .wrapper.overlay-sidebar .main-panel {
        .sidebar ~ .main-panel,

</html>
</html>
