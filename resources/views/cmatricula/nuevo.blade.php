@extends('cplantilla.bprincipal')
@section('titulo', 'Registrar Nueva Matrícula')
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

        .mensaje-estudiante {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .mensaje-nuevo {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
        }

        .mensaje-existente {
            background-color: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
        }
    </style>
    <style>
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


    <div class="container-fluid estilo-info margen-movil-2" id="contenido-principal">
        <div class="row mt-4 ml-1 mr-1">
            <div class="col-12 col-md-12 col-sm-12 col-lg-12 col-xl-12">
                <div class="box_block">
                    <button class="btn btn-block text-left rounded-0 btn_header header_6" type="button" data-toggle="collapse"
                        data-target="#collapseExample0" aria-expanded="true" aria-controls="collapseExample"
                        style="background: #347f65 !important; font-weight: bold; color: white;">
                        <i class="fas fa-plus-circle m-1"></i>&nbsp;Registrar Nueva Matrícula
                        <div class="float-right"><i class="fas fa-chevron-down"></i></div>
                    </button>

                    <div class="card-body info">
                        <div class="d-flex">
                            <div class="@flex-fill align-content-le@">
                                <i class="fas fa-exclamation-circle fa-2x"></i>
                            </div>
                            <div class="p-2 flex-fill">
                                <p>
                                    Busque al estudiante por DNI y complete los campos requeridos para registrar una nueva
                                    matrícula.
                                </p>
                                <p>
                                    Estimado Usuario: Asegúrese de que el estudiante esté previamente registrado en el
                                    sistema. El grado se asignará automáticamente según el historial académico del
                                    estudiante.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="collapse show " id="collapseExample0">
                        <div class="card card-body rounded-0 border-0 pt-0 pb-2 ">

                            <div class="row " style="padding:20px;">
                                <div class="col-12">

                                    <!-- Búsqueda de Estudiante -->
                                    <div class="card margen-movil" style="border: none">
                                        <div
                                            style="background: #f8f7ecdf; color: #266d54; font-weight: bold; border: 2px solid #2b8f47; border-bottom: 2px solid #2b8f47; padding: 6px 20px; border-radius:4px 4px 0px 0px;">
                                            <i class="icon-graduation mr-2"></i>
                                            Datos del estudiante
                                        </div>

                                        <div class="card-body"
                                            style="border: 2px solid #2b8f47; border-top: none; border-radius: 0px 0px 4px 4px !important;">
                                            <form id="formBuscar" method="GET" action="{{ route('matriculas.create') }}">
                                                <div class="row form-group">
                                                    <label class="col-md-2 col-form-label mt-1">N.° de DNI <span
                                                            style="color: #FF5A6A">(*)</span></label>
                                                    <div id="iptBuscar" class="col-md-10 mt-2">
                                                        <div class="input-group">
                                                            <input id="dniBuscar" type="text"
                                                                class="form-control inputEnabled" name="dni"
                                                                value="{{ $dni ?? '' }}"
                                                                placeholder="Ingrese un N° de DNI" maxlength="8"
                                                                autocomplete="off"
                                                                style="border: 1px solid #2b8f47 !important">
                                                            <button class="btn rounded-start-0"
                                                                type="submit"
                                                                style="background-color: #2b8f47; color:white; border-top-left-radius: 0 !important; border-bottom-left-radius: 0 !important; font-weight:bold">
                                                                <i class="fas fa-search mx-1"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <style>
                                                        .inputEnabled {
                                                            color: black;
                                                            font-weight: bold;
                                                        }
                                                    </style>
                                                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

                                                    <script>
                                                        document.addEventListener('DOMContentLoaded', function() {
                                                            const dniBuscar = document.getElementById('dniBuscar');

                                                            formBuscar.addEventListener('submit', function(e) {
                                                                const valor = dniBuscar.value.trim();
                                                                if (valor === '' || valor.length < 8) {
                                                                    e.preventDefault();
                                                                    Swal.fire({
                                                                        /*toast: true: PERMITE QUE SEA UN 'TOAST' MODEL PEQUEÑO'*/
                                                                        icon: 'warning',
                                                                        title: 'Busqueda denegada',
                                                                        text: 'Ingrese un N.° de DNI válido para realizar la búsqueda.',
                                                                        showConfirmButton: false,
                                                                        timer: 2300
                                                                    });

                                                                    //limpiamos el input
                                                                    dniBuscar.value = "";

                                                                    //dniBuscar.focus();


                                                                }

                                                            });

                                                        });
                                                    </script>
                                                    <div class="col-md-4">


                                                        @if ($error)
                                                            <!--SI EL ESTUDIANTE NO EXISTE O ESTÁ INHABILIDATO-->
                                                            <script>
                                                                document.addEventListener('DOMContentLoaded', function() {
                                                                    Swal.fire({
                                                                        icon: 'info',
                                                                        title: 'Estimado usuario',
                                                                        text: "{{ $error }}",
                                                                        confirmButtonColor: '#28a745',
                                                                        timer: 1200,
                                                                        showConfirmButton: false
                                                                    });

                                                                    dniBuscar.value = '';
                                                                });
                                                            </script>
                                                        @endif

                                                        @if ($estudiante && !$error)
                                                            <a href="{{ route('matriculas.create') }}"
                                                                class="btn btn-info w-100 mt-2" style="font-weight: bold">
                                                                <i class="fas fa-search mx-1"></i> Nueva búsqueda
                                                            </a>
                                                            <script>
                                                                iptBuscar.classList.replace('col-md-10', 'col-md-6');
                                                                dniBuscar.disabled = true; // desactiva
                                                                dniBuscar.classList.add('inputEnabled');
                                                                btnBuscar.hidden = true;
                                                               dniBuscar.style.borderRadius = "4px";   // todas las esquinas redondeadasas redondeadas en porcentaje

                                                            </script>
                                                        @endif

                                                        @if ($dni && !$estudiante && !$error)
                                                            <div class="text-muted mt-2">
                                                                <i class="fas fa-info-circle"></i> Estudiante encontrado
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    @if ($estudiante && !$error)
                                        <!-- Información del Estudiante -->
                                        <div class="card margen-movil" style="border: none">
                                            <div
                                                style="background: #E8F5E8; color: #2E7D32; font-weight: bold; border: 2px solid #A5D6A7; border-bottom: 2px solid #A5D6A7; padding: 6px 20px; border-radius:4px 4px 0px 0px;">
                                                Datos del Estudiante
                                            </div>

                                            <div class="card-body"
                                                style="border: 2px solid #A5D6A7; border-top: none; border-radius: 0px 0px 4px 4px !important;">
                                                <!-- Mensaje diferenciado -->
                                                @if ($mensaje)
                                                    <div
                                                        class="mensaje-estudiante {{ $esNuevo ? 'mensaje-nuevo' : 'mensaje-existente' }}">
                                                        <i class="fas fa-info-circle"></i> {{ $mensaje }}
                                                    </div>
                                                @endif

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <h6><strong>Información Personal:</strong></h6>
                                                        <ul class="list-unstyled">
                                                            <li><strong>DNI:</strong> {{ $estudiante->dni }}</li>
                                                            <li><strong>Apellidos:</strong> {{ $estudiante->apellidos }}
                                                            </li>
                                                            <li><strong>Nombres:</strong> {{ $estudiante->nombres }}</li>
                                                            <li><strong>Teléfono:</strong>
                                                                {{ $estudiante->telefono ?? 'No registrado' }}</li>
                                                            <li><strong>Email:</strong>
                                                                {{ $estudiante->email ?? 'No registrado' }}</li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h6><strong>Información Académica:</strong></h6>
                                                        <ul class="list-unstyled">
                                                            <li><strong>Grado a matricular:</strong>
                                                                {{ $grado->nombre ?? 'No disponible' }}</li>
                                                            <li><strong>Nivel:</strong>
                                                                {{ $grado->nivel->nombre ?? 'No disponible' }}</li>
                                                            <li><strong>Estado estudiante:</strong>
                                                                <span class="badge bg-success"
                                                                    style="border: none; color:white; font-weight:bold">{{ Str::upper($estudiante->estado) }}</span>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Formulario de Matrícula -->
                                        <form method="POST" action="{{ route('matriculas.store') }}" novalidate>
                                            @csrf

                                            <!-- Campos ocultos -->
                                            <input type="hidden" name="estudiante_id"
                                                value="{{ $estudiante->estudiante_id }}">
                                            <input type="hidden" name="idGrado" value="{{ $grado->grado_id ?? '' }}">

                                            <div class="card margen-movil" style="border: none">
                                                <div
                                                    style="background: #E0F7FA; color: #0A8CB3; font-weight: bold; border: 2px solid #86D2E3; border-bottom: 2px solid #86D2E3; padding: 6px 20px; border-radius:4px 4px 0px 0px;">
                                                    Datos de la Matrícula
                                                </div>

                                                <div class="card-body"
                                                    style="border: 2px solid #86D2E3; border-top: none; border-radius: 0px 0px 4px 4px !important;">

                                                    <div class="row form-group">
                                                        <label class="col-md-2 col-form-label">Grado <span
                                                                style="color: #FF5A6A">(*)</span></label>
                                                        <div class="col-md-4">
                                                            <input type="text" class="form-control"
                                                                value="{{ $grado->nombre ?? '' }} de {{ $grado->nivel->nombre ?? '' }}"
                                                                readonly
                                                                style="background-color: #f8f9fa; cursor: not-allowed;">
                                                            <span class="ml-1"
                                                                style="color:#FF5A6A; font-size:0.8rem">Grado y Nivel a
                                                                matricular.</span>
                                                        </div>

                                                        <label class="col-md-2 col-form-label">Sección <span
                                                                style="color: #FF5A6A">(*)</span></label>
                                                        <div class="col-md-4">
                                                            <select
                                                                class="form-control @error('idSeccion') is-invalid @enderror"
                                                                name="idSeccion" required>
                                                                <option value="">Seleccione una sección</option>
                                                                @foreach ($secciones as $seccionData)
                                                                    <option
                                                                        value="{{ $seccionData['seccion']->seccion_id }}"
                                                                        {{ old('idSeccion') == $seccionData['seccion']->seccion_id ? 'selected' : '' }}>
                                                                        {{ $seccionData['seccion']->nombre }}
                                                                        ({{ $seccionData['disponibles'] }} cupos
                                                                        disponibles)
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('idSeccion')
                                                                <div class="invalid-feedback d-block text-start">
                                                                    {{ $message }}</div>
                                                            @enderror
                                                            <span class="ml-1"
                                                                style="color:#FF5A6A; font-size:0.8rem">{{ $secciones->count() }}
                                                                secciones con
                                                                cupos disponibles.</span>

                                                        </div>
                                                    </div>

                                                    <div class="row form-group">
                                                        <label class="col-md-2 col-form-label">Observaciones</label>
                                                        <div class="col-md-10">
                                                            <textarea class="form-control @error('observaciones') is-invalid @enderror" name="observaciones" rows="3"
                                                                placeholder="Ingrese observaciones adicionales (opcional)" maxlength="500">{{ old('observaciones') }}</textarea>
                                                            @error('observaciones')
                                                                <div class="invalid-feedback d-block text-start">
                                                                    {{ $message }}</div>
                                                            @enderror

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Botones de acción -->
                                            <div
                                                class="d-flex justify-content-between align-items-center margen-movil g-2">
                                                <div class="w-100 mr-2">
                                                    <a href="{{ route('matriculas.index') }}"
                                                        class="btn btn-primary w-100">
                                                        <i class="fas fa-arrow-left"></i> Volver al listado
                                                    </a>

                                                </div>
                                                <div class="w-100 ml-2">
                                                    <button type="submit" class="btn btn-primary w-100"
                                                        style="background: #177ff5 !important; border: none;">
                                                        <i class="fas fa-save"></i> Registrar Matrícula
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    @endif

                                    @if (!$estudiante && !$dni)
                                        <!-- Mensaje inicial -->
                                        <div class="alert alert-info margen-movil" style="border-left-color: #347f65">
                                            <i class="fas fa-info-circle"></i>
                                            <strong>Instrucciones:</strong>
                                            <h5>
                                                Ingrese el N.° de DNI del estudiante en el campo de
                                                búsqueda para comenzar el proceso de matrícula.
                                            </h5>

                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
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
                    timer: 2000;
                });
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'warning', // más serio que 'info' para este tipo de aviso
                    title: 'Matricula Denegada',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#347f65',
                    confirmButtonText: 'Aceptar',
                    showConfirmButton: true
                });
            });
        </script>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show position-fixed"
            style="top: 20px; right: 20px; z-index: 1050;" role="alert">
            <i class="fas fa-exclamation-circle"></i>
            <strong>Error en el formulario:</strong>
            <ul class="mb-0 mt-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <style>
        .btn-primary {
            background: #F59617 !important;
            border: none;
            transition: background-color 0.2s ease, transform 0.1s ease;
        }

        .btn-primary:hover {
            background-color: #F59619 !important;
            transform: scale(1.01);
        }
    </style>

    <script>
        // Script simple para auto-hide mensajes
        setTimeout(function() {
            document.querySelectorAll('.alert').forEach(alert => {
                if (alert.classList.contains('fade')) {
                    alert.style.display = 'none';
                }
            });
        }, 5000);

        // Validación simple del DNI
        document.addEventListener('DOMContentLoaded', function() {
            const dniInput = document.querySelector('input[name="dni"]');
            if (dniInput) {
                dniInput.addEventListener('input', function() {
                    this.value = this.value.replace(/[^0-9]/g, '');
                    if (this.value.length > 8) {
                        this.value = this.value.slice(0, 8);
                    }
                });
            }
        });
    </script>
@endsection
