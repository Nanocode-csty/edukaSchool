@extends('cplantilla.bprincipal')
@section('titulo', 'Perfil del Usuario')
@section('contenidoplantilla')

    <style>
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

        @media (max-width: 576px) {
            .margen-movil {
                margin-left: -30px !important;
                margin-right: -29px !important;
            }

            .margen-movil-2 {
                margin: 0 !important;
                padding: 0 !important;
            }
        }
    </style>

    <div class="container-fluid estilo-info margen-movil-2" >
        <div class="row mt-4 ml-1 mr-1" >
            <div class="col-12" >
                <div class="card card-body rounded-0 border-0 pt-0 pb-2" style="background-color: #fffdfa85; border-radius:10px !important; align-items:center !important; justify-content:center !important; justify-items:center !important;">
                    <div class="row" style="padding:20px;">
                        <div class="container-fluid d-none d-md-block">
                            <!-- HEADER DEL PERFIL -->
                            <div class="profile-header mb-4">
                                <div class="row align-items-center">
                                    <div class="col-md-3">
                                        <div class="avatar avatar-xl">
                                            <img src="{{ asset('adminlte/assets/img/profile.jpg') }}"
                                                class="rounded-circle">
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <h2 class="fw-bold mb-0">
                                            {{ auth()->user()->nombres . ' ' . auth()->user()->apellidos }}
                                        </h2>
                                        <div style="font-size: 1rem; opacity: .9;">
                                            {{ auth()->user()->rol }} – Eduka Perú S.R.L
                                        </div>

                                        <div class="mt-2">
                                            <span class="badge bg-light text-dark" style="color:#003f77; font-weight:bold">
                                                USUARIO: {{ auth()->user()->username }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                        </div>
                        <div class="col-12">
                            <!-- Búsqueda de Estudiante -->
                            <div class="card margen-movil " style="border: none">

                                <div
                                    style="background: #ecf8f7cf; color: #264d6d; font-weight: bold; border: 2px solid #264d6d; border-bottom: 2px solid #264d6d; padding: 6px 20px; border-radius:4px 4px 0px 0px; font-size:large">
                                    <i class="icon-graduation mr-2"></i>
                                    Datos del Usuario
                                </div>

                                <div class="card-body "
                                    style="border: 2px solid #264d6d; border-top: none; border-radius: 0px 0px 4px 4px !important;">

                                    <div class="estilo-info margen-movil-2">

                                        <style>
                                            .profile-header {
                                                background: linear-gradient(135deg, #0F3E61, #2378ba);
                                                border-radius: 10px;
                                                padding: 30px;
                                                color: white;
                                            }

                                            .profile-header .avatar-xl img {
                                                border: 3px solid rgba(255, 255, 255, 0.8);
                                                box-shadow: 0 4px 14px rgba(0, 0, 0, 0.25);
                                            }

                                            .profile-info-item svg {
                                                width: 20px;
                                                height: 20px;
                                                margin-right: 6px;
                                                color: #0d6efd;
                                            }

                                            .card-title {
                                                font-weight: 700;
                                                color: #003f77;
                                            }

                                            .info-label {
                                                font-weight: 600;
                                                color: #003f77;
                                            }

                                            .info-value {
                                                color: #495057;
                                            }

                                            .section-title {
                                                font-weight: 700;
                                                font-size: 1.1rem;
                                                margin-bottom: 15px;
                                                border-left: 5px solid #0d6efd;
                                                padding-left: 10px;
                                                color: #003f77;
                                            }
                                        </style>

                                        <!-- BEGIN PAGE HEADER -->
                                        <div class="page-header d-block d-md-none">
                                            <div class="container">
                                                <div class="row align-items-center">
                                                    <div class="col-auto">
                                                        <div class="avatar avatar-xl"><img
                                                                src="{{ asset('adminlte/assets/img/profile.jpg') }}"
                                                                class="avatar-img rounded"></div>

                                                    </div>
                                                    <div class="col">
                                                        <h1 class="fw-bold m-0">
                                                            {{ auth()->user()->nombres . ' ' . auth()->user()->apellidos }}
                                                        </h1>
                                                        <div class="my-2">
                                                            {{ auth()->user()->rol . ' ' . 'de Eduka Perú S.R.L' }}
                                                        </div>
                                                        <div class="list-inline list-inline-dots text-secondary">
                                                            <div class="list-inline-item">
                                                                <!-- Download SVG icon from http://tabler.io/icons/icon/map -->
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="icon icon-inline icon-2">
                                                                    <path d="M3 7l6 -3l6 3l6 -3v13l-6 3l-6 -3l-6 3v-13">
                                                                    </path>
                                                                    <path d="M9 4v13"></path>
                                                                    <path d="M15 7v13"></path>
                                                                </svg>
                                                                <a href="https://www.google.com/maps/search/{{ 'Universidad Nacional de Trujillo, Trujillo - Perú' }}"
                                                                    target="_blank">
                                                                    Universidad Nacional de Trujillo, Trujillo -
                                                                    Perú
                                                                </a>

                                                            </div>
                                                            <div class="list-inline-item">
                                                                <!-- Download SVG icon from http://tabler.io/icons/icon/mail -->
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="icon icon-inline icon-2">
                                                                    <path
                                                                        d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z">
                                                                    </path>
                                                                    <path d="M3 7l9 6l9 -6"></path>
                                                                </svg>

                                                                <a href=" mailito: {{ auth()->user()->email }}"
                                                                    class="text-reset">{{ auth()->user()->email }}</a>
                                                            </div>
                                                            <div class="list-inline-item">
                                                                <!-- Download SVG icon from http://tabler.io/icons/icon/cake -->
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="icon icon-inline icon-2">
                                                                    <path
                                                                        d="M3 20h18v-8a3 3 0 0 0 -3 -3h-12a3 3 0 0 0 -3 3v8z">
                                                                    </path>
                                                                    <path
                                                                        d="M3 14.803c.312 .135 .654 .204 1 .197a2.4 2.4 0 0 0 2 -1a2.4 2.4 0 0 1 2 -1a2.4 2.4 0 0 1 2 1a2.4 2.4 0 0 0 2 1a2.4 2.4 0 0 0 2 -1a2.4 2.4 0 0 1 2 -1a2.4 2.4 0 0 1 2 1a2.4 2.4 0 0 0 2 1c.35 .007 .692 -.062 1 -.197">
                                                                    </path>
                                                                    <path
                                                                        d="M12 4l1.465 1.638a2 2 0 1 1 -3.015 .099l1.55 -1.737z">
                                                                    </path>
                                                                </svg>
                                                                15/10/1972
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <!-- BEGIN PAGE BODY -->

                                        <div class="container-xl">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="row row-cards">
                                                        <div class="col-12">
                                                            <div class="card">
                                                                <div class="card-title"
                                                                    style="background-color: #eee9dd65; color:rgb(19, 13, 13); border-top-right-radius:5px; border-top-left-radius:5px">
                                                                    <span class="ml-3 "
                                                                        style="font-size:medium !important">Información
                                                                        Básica:</span>
                                                                </div>
                                                                <div class="card-body">

                                                                    <div class="mb-2">
                                                                        <!-- Download SVG icon from http://tabler.io/icons/icon/book -->
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            width="24" height="24"
                                                                            viewBox="0 0 24 24" fill="none"
                                                                            stroke="currentColor" stroke-width="2"
                                                                            stroke-linecap="round" stroke-linejoin="round"
                                                                            class="icon me-2 text-secondary icon-2">
                                                                            <path d="M3 19a9 9 0 0 1 9 0a9 9 0 0 1 9 0">
                                                                            </path>
                                                                            <path d="M3 6a9 9 0 0 1 9 0a9 9 0 0 1 9 0">
                                                                            </path>
                                                                            <path d="M3 6l0 13"></path>
                                                                            <path d="M12 6l0 13"></path>
                                                                            <path d="M21 6l0 13"></path>
                                                                        </svg>
                                                                        Went to: <strong>University of
                                                                            Ljubljana</strong>
                                                                    </div>
                                                                    <div class="mb-2">
                                                                        <!-- Download SVG icon from http://tabler.io/icons/icon/briefcase -->
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            width="24" height="24"
                                                                            viewBox="0 0 24 24" fill="none"
                                                                            stroke="currentColor" stroke-width="2"
                                                                            stroke-linecap="round" stroke-linejoin="round"
                                                                            class="icon me-2 text-secondary icon-2">
                                                                            <path
                                                                                d="M3 7m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v9a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z">
                                                                            </path>
                                                                            <path
                                                                                d="M8 7v-2a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v2">
                                                                            </path>
                                                                            <path d="M12 12l0 .01"></path>
                                                                            <path d="M3 13a20 20 0 0 0 18 0">
                                                                            </path>
                                                                        </svg>
                                                                        Worked at: <strong>Devpulse</strong>
                                                                    </div>
                                                                    <div class="mb-2">
                                                                        <!-- Download SVG icon from http://tabler.io/icons/icon/home -->
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            width="24" height="24"
                                                                            viewBox="0 0 24 24" fill="none"
                                                                            stroke="currentColor" stroke-width="2"
                                                                            stroke-linecap="round" stroke-linejoin="round"
                                                                            class="icon me-2 text-secondary icon-2">
                                                                            <path d="M5 12l-2 0l9 -9l9 9l-2 0">
                                                                            </path>
                                                                            <path
                                                                                d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7">
                                                                            </path>
                                                                            <path
                                                                                d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6">
                                                                            </path>
                                                                        </svg>
                                                                        Lives in: <strong>Šentilj v Slov.
                                                                            Goricah, Slovenia</strong>
                                                                    </div>
                                                                    <div class="mb-2">
                                                                        <!-- Download SVG icon from http://tabler.io/icons/icon/map-pin -->
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            width="24" height="24"
                                                                            viewBox="0 0 24 24" fill="none"
                                                                            stroke="currentColor" stroke-width="2"
                                                                            stroke-linecap="round" stroke-linejoin="round"
                                                                            class="icon me-2 text-secondary icon-2">
                                                                            <path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0">
                                                                            </path>
                                                                            <path
                                                                                d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z">
                                                                            </path>
                                                                        </svg>
                                                                        From: <strong><span
                                                                                class="flag flag-xs flag-country-si"></span>
                                                                            Slovenia</strong>
                                                                    </div>
                                                                    <div class="mb-2">
                                                                        <!-- Download SVG icon from http://tabler.io/icons/icon/calendar -->
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            width="24" height="24"
                                                                            viewBox="0 0 24 24" fill="none"
                                                                            stroke="currentColor" stroke-width="2"
                                                                            stroke-linecap="round" stroke-linejoin="round"
                                                                            class="icon me-2 text-secondary icon-2">
                                                                            <path
                                                                                d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z">
                                                                            </path>
                                                                            <path d="M16 3v4"></path>
                                                                            <path d="M8 3v4"></path>
                                                                            <path d="M4 11h16"></path>
                                                                            <path d="M11 15h1"></path>
                                                                            <path d="M12 15v3"></path>
                                                                        </svg>
                                                                        Birth date: <strong>13/01/1985</strong>
                                                                    </div>
                                                                    <div>
                                                                        <!-- Download SVG icon from http://tabler.io/icons/icon/clock -->
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            width="24" height="24"
                                                                            viewBox="0 0 24 24" fill="none"
                                                                            stroke="currentColor" stroke-width="2"
                                                                            stroke-linecap="round" stroke-linejoin="round"
                                                                            class="icon me-2 text-secondary icon-2">
                                                                            <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0">
                                                                            </path>
                                                                            <path d="M12 7v5l3 3"></path>
                                                                        </svg>
                                                                        Time zone:
                                                                        <strong>Europe/Ljubljana</strong>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="card">
                                                        <div class="card-title"
                                                            style="background-color: #eee9dd65; color:rgb(19, 13, 13); border-top-right-radius:5px; border-top-left-radius:5px">
                                                            <span class="ml-3" style="font-size:medium !important">Sobre
                                                                Mí:</span>
                                                        </div>
                                                        <div class="card-body">
                                                            <div>
                                                                <p>
                                                                    Lorem ipsum dolor sit amet, consectetur adipisicing
                                                                    elit. Accusantium aliquid beatae eaque
                                                                    eius esse fugit, hic id illo itaque
                                                                    modi
                                                                    molestias nemo perferendis quae
                                                                    rerum soluta. Blanditiis laborum
                                                                    minima molestiae molestias nemo
                                                                    nesciunt nisi pariatur quae
                                                                    sapiente ut. Aut consectetur
                                                                    doloremque, error impedit, ipsum
                                                                    labore laboriosam minima non omnis
                                                                    perspiciatis possimus praesentium
                                                                    provident quo recusandae suscipit
                                                                    tempore totam.
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
