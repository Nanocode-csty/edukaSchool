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
