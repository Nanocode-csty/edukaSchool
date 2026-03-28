
    <style>
        .estilo-info {
            margin-bottom: 0px;
            font-family: "Quicksand", sans-serif;
            font-weight: 700;
            font-size: clamp(0.9rem, 2.0vw, 0.9rem) !important;

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
    </style>

    <div class="container-fluid estilo-info margen-movil-2" id="contenido-principal">
        <div class="row mt-4 mr-1 ml-1">
            <div class="col-12">
                <div class="box_block">

                    <!-- Botón colapsable -->
                    <button class="btn btn-block text-left rounded-0 btn_header header_6" type="button" data-toggle="collapse"
                        data-target="#collapseExample1" aria-expanded="true" aria-controls="collapseExample1"
                        style="background: #0A8CB3 !important; font-weight: bold; color: white;">
                        <i class="fas fa-file-signature m-1"></i>&nbsp;Estudiante a asignar Representante Legal
                        <div class="float-right"><i class="fas fa-chevron-down"></i></div>
                    </button>

                    <!-- Información extra -->
                    <div class="card-body info">
                        <div class="d-flex">
                            <div class="@*flex-fill align-content-le*@">
                                <i class="fas fa-exclamation-circle fa-2x"></i>
                            </div>
                            <div class="p-2 flex-fill">
                                <p>Este es el estudiante al que se le asignará su Representante Legal.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Contenido colapsable simplificado -->
                    <div class="collapse show" id="collapseExample1">
                        <div class="card card-body rounded-0 border-0 pt-0 pb-2">
                            <div class="card-body">

                                <div class="row form-group">
                                    <label class="col-md-2 col-form-label">N.° de DNI <span
                                            style="color: #FF5A6A">(*)</span></label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control campo-deshabilitado"
                                            value="{{ session('dniEstudiante') }}" disabled>
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <label class="col-md-2 col-form-label">Nombres y Apellidos <span
                                            style="color: #FF5A6A">(*)</span></label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control campo-deshabilitado"
                                            value="{{ session('nombresCompletosEstudiante') }}" disabled>
                                    </div>
                                </div>

                                <!-- Campo oculto con ID para uso posterior -->
                                <input type="hidden" id="idEstudiante" name="idEstudiante"
                                    value="{{ session('idEstudiante') }}">
                            </div>
                        </div>
                    </div>
                </div> <!-- /.box_block -->
            </div> <!-- /.col -->
        </div> <!-- /.row -->
    </div> <!-- /.container-fluid -->

    <!-- Estilo para el campo deshabilitado -->
    <style>
        .campo-deshabilitado {
            background-color: #f0f0f0 !important;
            color: #000000 !important;
            font-weight: bold !important;
            cursor: not-allowed;
        }
    </style>

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
    </style>

    <style type="text/css" data-glamor=""></style>
    <meta name="react-film" content="version=1.2.1-master.db29968">
    <meta name="botframework-webchat:bundle:variant" content="full">
    <meta name="botframework-webchat:bundle:version" content="4.3.1-master.98c662f">
    <meta name="botframework-webchat:core:version" content="4.3.1-master.98c662f">
    <meta name="botframework-webchat:ui:version" content="4.3.1-master.98c662f">
    <style type="text/css">
        .fancybox-margin {
            margin-right: 10px;
        }
    </style>

    <div class="mt-2 container-fluid estilo-info margen-movil-2" id="contenido-principal">
        <div class="row mr-1 ml-1 ">
            <div class="col-12 col-md-12 col-sm-12 col-lg-12 col-xl-12">
                <div class="box_block">

                    <button class="btn btn-block text-left rounded-0 btn_header header_6" type="button"
                        data-toggle="collapse" data-target="#collapseExample0" aria-expanded="true"
                        aria-controls="collapseExample"
                        style="background: #0A8CB3 !important; font-weight: bold; color:white">
                        <i class="fas fa-file-signature m-1"></i>&nbsp;Asignación de representante(s) al estudiante
                        <!--TITULO PRINCIPAL DEL CARD-->
                        <div class="float-right"><i class="fas fa-chevron-down"></i></div>
                    </button>

                    <div class="card-body info">
                        <div class="d-flex ">
                            <div class="@*flex-fill align-content-le*@">
                                <i class="fas fa-exclamation-circle fa-2x"></i>
                            </div>
                            <div class="p-2 flex-fill">
                                <!--TEXTOS DEL CARD, SIEMPRE SE MUESTRA-->
                                <p>
                                    En esta sección, podrás asignar Representantes Legales a los estudiantes, así como
                                    consultar información de los registrados.
                                </p>
                                <p>
                                    Estimado Usuario: Asegúrate de revisar cuidadosamente los datos antes de guardarlos, ya
                                    que esta información será utilizada para la gestión académica y administrativa del
                                    estudiante. Cualquier modificación posterior debe ser realizada con responsabilidad y
                                    siguiendo los protocolos establecidos por la institución.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="collapse show " id="collapseExample0">
                        <div class="card card-body rounded-0 border-0 pt-0 pb-2">

                            <div class="container-fluid  mb-4">
                                <div class="row margen-movil">
                                    <div class="col-12 ">

                                        <div class="row mt-4">
                                            <div class="col-12 ">
                                                <div class="card" style="border: none">
                                                    <div class="btn btn-block text-left rounded-0 btn_header header_6"
                                                        type="button" data-toggle="collapse"
                                                        data-target="#collapseExample4" aria-expanded="true"
                                                        aria-controls="collapseExample"
                                                        style="background: #0A8CB3 !important; font-weight: bold; color: white;"
                                                        style="background: #E0F7FA; color: #0A8CB3; font-weight: bold; border: 2px solid #86D2E3; border-bottom: 2px solid #86D2E3; padding: 6px 20px; border-radius:4px 4px 0px 0px;">
                                                        Datos del Padre
                                                    </div>

                                                    <div class="card-body collapse show" id="collapseExample4"
                                                        style="border: 2px solid #86D2E3; border-top: none; border-radius: 0px 0px 4px 4px !important;">
                                                        <div class="row align-items-center">
                                                            <!-- Botón a la izquierda -->
                                                            <div class="col-md-6 mb-md-0 d-flex justify-content-start">
                                                                <a class="btn btn-primary w-100" id="Repre1RegistroBtn"
                                                                    style="color: #f1f1f1; font-weight: bold">
                                                                    <i class="fa fa-plus mx-2"></i> Registrar al padre de
                                                                    familia
                                                                </a>
                                                            </div>

                                                            <!-- Formulario de búsqueda a la derecha -->
                                                            <div
                                                                class="col-md-6 d-flex justify-content-md-end justify-content-center">
                                                                <form id="formBuscar" class=" w-100"
                                                                    style="max-width: 100%;" autocomplete="off">
                                                                    <div class="input-group">
                                                                        <input maxlength="8" id="inputBuscar"
                                                                            name="buscarpor" class="form-control mt-1"
                                                                            type="search"
                                                                            placeholder="Buscar al padre de familiar por N.º DNI"
                                                                            aria-label="Search"
                                                                            style="border-color: #F59617;">
                                                                        <button id="btnBuscar"
                                                                            class="btn btn-primary nuevo-boton mt-1"
                                                                            type="submit"
                                                                            style="border-top-left-radius: 0 !important; border-bottom-left-radius: 0 !important;">
                                                                            <i class="fas fa-search"></i>
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </div>

                                                            <script>
                                                                document.addEventListener('DOMContentLoaded', function() {
                                                                    const btnRegistrar = document.getElementById('Repre1RegistroBtn');
                                                                    const inputBuscar = document.getElementById('inputBuscar');
                                                                    const btnBuscar = document.getElementById('btnBuscar');

                                                                    // 1. Si se escribe en el buscador, desactiva el botón "Registrar"
                                                                    inputBuscar.addEventListener('input', function() {
                                                                        if (inputBuscar.value.trim().length > 0) {
                                                                            btnRegistrar.classList.add('disabled');
                                                                            btnRegistrar.style.pointerEvents = 'none';
                                                                        } else {
                                                                            btnRegistrar.classList.remove('disabled');
                                                                            btnRegistrar.style.pointerEvents = 'auto';
                                                                        }
                                                                    });

                                                                    // 2. Si se hace clic en "Registrar Representante", se bloquea el buscador
                                                                    btnRegistrar.addEventListener('click', function(e) {
                                                                        // Simula acción, puedes quitar esto si lleva a otra página
                                                                        e.preventDefault();

                                                                        inputBuscar.disabled = true;
                                                                        btnBuscar.disabled = true;
                                                                    });
                                                                });
                                                            </script>
                                                            <!-- SweetAlert2 -->
                                                            <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

                                                            <script>
                                                                document.getElementById('formBuscar').addEventListener('submit', function(e) {
                                                                    e.preventDefault();

                                                                    const dni = document.getElementById('inputBuscar').value.trim();

                                                                    if (dni.length === 8 && /^\d+$/.test(dni)) {
                                                                        fetch("{{ route('buscar.representante') }}", {
                                                                                method: 'POST',
                                                                                headers: {
                                                                                    'Content-Type': 'application/json',
                                                                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                                                                },
                                                                                body: JSON.stringify({
                                                                                    dni: dni
                                                                                })
                                                                            })
                                                                            .then(response => response.json())
                                                                            .then(data => {
                                                                                const inputBuscar = document.getElementById('inputBuscar');
                                                                                const btnBuscar = document.getElementById('btnBuscar');
                                                                                const btnAsignar = document.getElementById('btnAsignar');
                                                                                const select = document.getElementById('parentescoRepresentante1');

                                                                                if (data.success) {
                                                                                    e.preventDefault();
                                                                                    Swal.fire({
                                                                                        icon: 'success',
                                                                                        title: 'Representante registrado',
                                                                                        text: 'El Representante se encuentra registrado correctamente.',
                                                                                        showConfirmButton: false,
                                                                                        timer: 1200
                                                                                    });

                                                                                    // Habilitar/deshabilitar botones y campos
                                                                                    inputBuscar.disabled = true;
                                                                                    btnBuscar.disabled = true;
                                                                                    btnAsignar.disabled = false;
                                                                                    select.disabled = false;

                                                                                    // Seleccionar el parentesco solo si existe la opción
                                                                                    const parentesco = data.representante.parentesco;
                                                                                    for (let option of select.options) {
                                                                                        if (option.value === parentesco) {
                                                                                            select.value = parentesco;
                                                                                            break;
                                                                                        }
                                                                                    }

                                                                                    // Asignar valores a inputs
                                                                                    document.getElementById('idRepresentante1').value = data.representante
                                                                                        .representante_id || "";
                                                                                    document.getElementById('apellidoPaternoRepresentante1').value = data.representante
                                                                                        .apellidoPaterno || "";
                                                                                    document.getElementById('apellidoMaternoRepresentante1').value = data.representante
                                                                                        .apellidoMaterno || "";
                                                                                    document.getElementById('nombreRepresentante1').value = data.representante
                                                                                        .nombres ||
                                                                                        "";
                                                                                    document.getElementById('dniRepresentante1').value = data.representante.dni || "";
                                                                                    document.getElementById('ocupacionRepresentante1').value = data.representante
                                                                                        .ocupacion || "";
                                                                                    document.getElementById('celularRepresentante1').value = data.representante
                                                                                        .telefono || "";
                                                                                    document.getElementById('celularAlternativoRepresentante1').value = data
                                                                                        .representante
                                                                                        .telefono_alternativo || "";
                                                                                    document.getElementById('correoRepresentante1').value = data.representante.email ||
                                                                                        "";
                                                                                    document.getElementById('direccionRepresentante1').value = data.representante
                                                                                        .direccion ||
                                                                                        "";
                                                                                    select.disabled = false; // asegúrate de no desactivarlo
                                                                                    select.setAttribute('readonly',
                                                                                        true
                                                                                    ); // este atributo no existe por defecto, pero puedes usarlo para simular estilo

                                                                                    // Opcionalmente: bloquear con CSS
                                                                                    select.style.pointerEvents = 'none';
                                                                                    select.style.backgroundColor = '#e9ecef'; // estilo visual de campo inactivo

                                                                                } else {

                                                                                    e.preventDefault();
                                                                                    Swal.fire({
                                                                                        icon: 'warning',
                                                                                        title: 'Representante NO registrado',
                                                                                        text: 'Debe registra al representante en "Registrar al padre/madre de familia" para continuar',
                                                                                        showConfirmButton: false,
                                                                                        timer: 2200
                                                                                    });

                                                                                    // Limpiar inputs
                                                                                    document.getElementById('apellidoPaternoRepresentante1').value = "";
                                                                                    document.getElementById('apellidoMaternoRepresentante1').value = "";
                                                                                    document.getElementById('nombreRepresentante1').value = "";
                                                                                    document.getElementById('dniRepresentante1').value = "";
                                                                                    select.selectedIndex = 0; // Seleccionar opción por defecto
                                                                                    document.getElementById('ocupacionRepresentante1').value = "";
                                                                                    document.getElementById('celularRepresentante1').value = "";
                                                                                    document.getElementById('celularAlternativoRepresentante1').value = "";
                                                                                    document.getElementById('correoRepresentante1').value = "";
                                                                                    document.getElementById('direccionRepresentante1').value = "";

                                                                                    const btnRegistrar = document.getElementById('Repre1RegistroBtn');
                                                                                    btnRegistrar.classList.remove('disabled');
                                                                                    btnRegistrar.style.pointerEvents = 'auto';

                                                                                    inputBuscar.value = "";
                                                                                    inputBuscar.disabled = false;
                                                                                    btnBuscar.disabled = false;
                                                                                    btnAsignar.disabled = true;
                                                                                    select.disabled = true;
                                                                                    $('#inputBuscar').focus();
                                                                                }
                                                                            })
                                                                            .catch(error => {
                                                                                console.error(error);
                                                                                alert('Ocurrió un error al buscar el representante.');
                                                                            });
                                                                    } else {
                                                                        e.preventDefault();
                                                                        Swal.fire({
                                                                            icon: 'warning',
                                                                            title: 'Caracteres Inválidos',
                                                                            text: 'Ingrese un N.° de DNI válido para realizar la búsqueda.',
                                                                            showConfirmButton: false,
                                                                            timer: 1200
                                                                        });
                                                                        document.getElementById('inputBuscar').value = "";
                                                                    }

                                                                });
                                                            </script>

                                                            <div id="resultadoRepresentante" class="mt-3"></div>
                                                        </div>
                                                        @if ($errors->has('error_general'))
                                                            <script>
                                                                document.addEventListener('DOMContentLoaded', function() {
                                                                    Swal.fire({
                                                                        icon: 'warning',
                                                                        title: 'Estimado Usuario',
                                                                        text: @json($errors->first('error_general')),
                                                                        confirmButtonColor: '#3085d6',
                                                                        confirmButtonText: 'Entendido'
                                                                    });
                                                                });
                                                            </script>
                                                        @endif

                                                        <!--PARA MOSTRAR LA EXCEPCIÓN
                                                                @if ($errors->has('error_general'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'warning',
                title: 'Estimado Usuario',
                text: @json($errors->first('error_general')),
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Entendido'
            });
        });
    </script>
    @endif
        -->


                                                        <form action="{{ route('registrorepresentanteestudiante.store') }}"
                                                            method="POST" autocomplete="off">
                                                            @csrf
                                                            <div class="container-fluid">
                                                                <div class="row margen-movil">
                                                                    <div class="col-12">

                                                                        <!--PADDIG PARA DARLE ESPACIO ENTRE EL CONTENEDOR Y EL CONTENIDO-->
                                                                        <div class="row mt-3" style="padding:0px;">

                                                                            <div class="col-12">

                                                                                <div class="card" style="border: none">
                                                                                    <div
                                                                                        style="background: #E0F7FA; color: #0A8CB3; font-weight: bold; border: 2px solid #86D2E3; border-bottom: 2px solid #86D2E3; padding: 6px 20px; border-radius:4px 4px 0px 0px;">
                                                                                        Datos del representante
                                                                                    </div>

                                                                                    <div class="card-body "
                                                                                        style="border: 2px solid #86D2E3; border-top: none; border-radius: 0px 0px 4px 4px !important;">

                                                                                        <!--DEFINIMOS PRIMERO, QUE SE CREEN O NO A LOS REPRESENTANTES-->

                                                                                        <!-- Campo oculto con ID para uso posterior -->
                                                                                        <input type="hidden"
                                                                                            id="idEstudiante"
                                                                                            name="idEstudiante"
                                                                                            value="{{ session('idEstudiante') }}">
                                                                                        <div class="row form-group">
                                                                                            <label
                                                                                                class="col-md-2 col-form-label">Apellido
                                                                                                Paterno
                                                                                                <span
                                                                                                    style="color: #FF5A6A">(*)</span></label>
                                                                                            <div class="col-md-4">
                                                                                                <input type="text"
                                                                                                    class="form-control @error('apellidoPaternoRepresentante1') is-invalid @enderror"
                                                                                                    id="apellidoPaternoRepresentante1"
                                                                                                    name="apellidoPaternoRepresentante1"
                                                                                                    placeholder="Apellido paterno"
                                                                                                    maxlength="100"
                                                                                                    value="{{ old('apellidoPaternoRepresentante1') }}"
                                                                                                    readonly>
                                                                                                @if ($errors->has('apellidoPaternoRepresentante1'))
                                                                                                    <span
                                                                                                        class="invalid-feedback d-block text-start">{{ $errors->first('apellidoPaternoRepresentante1') }}</span>
                                                                                                @endif
                                                                                            </div>
                                                                                            <label
                                                                                                class="col-md-2 col-form-label">Apellido
                                                                                                Materno
                                                                                                <span
                                                                                                    style="color: #FF5A6A">(*)</span></label>
                                                                                            <div class="col-md-4">
                                                                                                <input type="text"
                                                                                                    class="form-control @error('apellidoMaternoRepresentante1') is-invalid @enderror"
                                                                                                    id="apellidoMaternoRepresentante1"
                                                                                                    name="apellidoMaternoRepresentante1"
                                                                                                    placeholder="Apellido materno"
                                                                                                    maxlength="100"
                                                                                                    value="{{ old('apellidoMaternoRepresentante1') }}"
                                                                                                    readonly>
                                                                                                @if ($errors->has('apellidoMaternoRepresentante1'))
                                                                                                    <span
                                                                                                        class="invalid-feedback d-block text-start">{{ $errors->first('apellidoMaternoRepresentante1') }}</span>
                                                                                                @endif
                                                                                            </div>


                                                                                        </div>

                                                                                        <div class="row form-group">
                                                                                            <label
                                                                                                class="col-md-2 col-form-label">
                                                                                                Nombres <span
                                                                                                    style="color: #FF5A6A">(*)</span>
                                                                                            </label>
                                                                                            <div class="col-md-10">
                                                                                                <input type="text"
                                                                                                    class="form-control @error('nombreRepresentante1') is-invalid @enderror"
                                                                                                    id="nombreRepresentante1"
                                                                                                    name="nombreRepresentante1"
                                                                                                    placeholder="Nombres"
                                                                                                    maxlength="100"
                                                                                                    value="{{ old('nombreRepresentante1') }}"
                                                                                                    readonly>
                                                                                                @if ($errors->has('nombreRepresentante1'))
                                                                                                    <span
                                                                                                        class="invalid-feedback d-block text-start">{{ $errors->first('nombreRepresentante1') }}</span>
                                                                                                @endif
                                                                                            </div>
                                                                                        </div>


                                                                                        <div class="row form-group">
                                                                                            <label
                                                                                                class="col-md-2 col-form-label">Parentesco
                                                                                                <span
                                                                                                    style="color: #FF5A6A">(*)</span>
                                                                                            </label>
                                                                                            <div class="col-md-4">
                                                                                                <select
                                                                                                    class="form-control @error('parentescoRepresentante1') is-invalid @enderror"
                                                                                                    id="parentescoRepresentante1"
                                                                                                    name="parentescoRepresentante1"
                                                                                                    readonly>
                                                                                                    <option value=""
                                                                                                        disabled
                                                                                                        {{ old('parentescoRepresentante1') == '' ? 'selected' : '' }}>
                                                                                                        Seleccionar
                                                                                                        parentesco</option>
                                                                                                    <option value="Padre"
                                                                                                        {{ old('parentescoRepresentante1') == 'Padre' ? 'selected' : '' }}>
                                                                                                        Padre</option>
                                                                                                    <option value="Madre"
                                                                                                        {{ old('parentescoRepresentante1') == 'Madre' ? 'selected' : '' }}>
                                                                                                        Madre</option>

                                                                                                </select>
                                                                                                @error('parentescoRepresentante1')
                                                                                                    <div
                                                                                                        class="invalid-feedback d-block text-start">
                                                                                                        {{ $message }}
                                                                                                    </div>
                                                                                                @enderror
                                                                                            </div>

                                                                                            <label
                                                                                                class="col-md-2 col-form-label">N.°
                                                                                                DNI <span
                                                                                                    style="color: #FF5A6A">(*)</span></label>
                                                                                            <div class="col-md-4">
                                                                                                <input type="text"
                                                                                                    class="form-control"
                                                                                                    id="dniRepresentante1"
                                                                                                    name="dniRepresentante1"
                                                                                                    maxlength="8"
                                                                                                    placeholder="N.° DNI"
                                                                                                    value="{{ old('dniRepresentante1') }}"
                                                                                                    readonly>
                                                                                                @error('dniRepresentante1')
                                                                                                    <div
                                                                                                        class="invalid-feedback d-block text-start">
                                                                                                        {{ $message }}
                                                                                                    </div>
                                                                                                @enderror

                                                                                            </div>
                                                                                        </div>

                                                                                        <!-- Flatpickr CSS -->
                                                                                        <link rel="stylesheet"
                                                                                            href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

                                                                                        <div class="row form-group">
                                                                                            <label
                                                                                                class="col-md-2 col-form-label">
                                                                                                Ocupación
                                                                                            </label>
                                                                                            <div class="col-md-10">
                                                                                                <input type="text"
                                                                                                    class="form-control"
                                                                                                    id="ocupacionRepresentante1"
                                                                                                    name="ocupacionRepresentante1"
                                                                                                    placeholder="Ocupación del representante"
                                                                                                    value="{{ old('ocupacionRepresentante1') }}"
                                                                                                    maxlength="100"
                                                                                                    readonly>
                                                                                                @error('ocupacionRepresentante1')
                                                                                                    <div
                                                                                                        class="invalid-feedback d-block text-start">
                                                                                                        {{ $message }}
                                                                                                    </div>
                                                                                                @enderror
                                                                                            </div>

                                                                                        </div>

                                                                                        <style>
                                                                                            /* Borde dorado personalizado */
                                                                                            .form-control.custom-gold {
                                                                                                border: 2px solid #DAA520 !important;
                                                                                                background-color: white !important;
                                                                                                color: black;
                                                                                            }
                                                                                        </style>




                                                                                        <!-- Flatpickr JS y Español -->
                                                                                        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
                                                                                        <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
                                                                                        <script>
                                                                                            flatpickr("#fechaNacimientoEstudiante", {
                                                                                                dateFormat: "Y-m-d",
                                                                                                maxDate: "today",
                                                                                                locale: "es",
                                                                                                onChange: function(selectedDates, dateStr, instance) {
                                                                                                    const input = document.getElementById('fechaNacimientoEstudiante');
                                                                                                    const feedback = input.parentElement.querySelector('.feedback-message');

                                                                                                    if (dateStr) {
                                                                                                        input.classList.remove('is-invalid');
                                                                                                        if (feedback) feedback.remove(); // Borra el mensaje si ya había uno
                                                                                                    }
                                                                                                }
                                                                                            });
                                                                                        </script>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="card" style="border: none">
                                                                                    <div
                                                                                        style="background: #E0F7FA; color: #0A8CB3; font-weight: bold; border: 2px solid #86D2E3; border-bottom: 2px solid #86D2E3; padding: 6px 20px; border-radius:4px 4px 0px 0px;">
                                                                                        Datos de contacto y residencia
                                                                                    </div>

                                                                                    <div class="card-body"
                                                                                        style="border: 2px solid #86D2E3; border-top: none; border-radius: 0px 0px 4px 4px !important;">

                                                                                        <div class="row form-group">
                                                                                            <label
                                                                                                class="col-md-2 col-form-label">
                                                                                                Celular actual <span
                                                                                                    style="color: #FF5A6A">(*)</span>
                                                                                            </label>
                                                                                            <div class="col-md-4">
                                                                                                <div class="input-group">

                                                                                                    <input type="text"
                                                                                                        class="form-control @error('celularRepresentante1') is-invalid @enderror"
                                                                                                        id="celularRepresentante1"
                                                                                                        name="celularRepresentante1"
                                                                                                        placeholder="N.° celular"
                                                                                                        maxlength="9"
                                                                                                        value="{{ old('celularRepresentante1') }}"
                                                                                                        inputmode="numeric"
                                                                                                        readonly>

                                                                                                    <div
                                                                                                        class="input-group-append">
                                                                                                        <span
                                                                                                            class="input-group-text"><i
                                                                                                                class="fas fa-phone"></i></span>
                                                                                                    </div>
                                                                                                    @error('celularRepresentante1')
                                                                                                        <div
                                                                                                            class="invalid-feedback d-block text-start">
                                                                                                            {{ $message }}
                                                                                                        </div>
                                                                                                    @enderror
                                                                                                </div>
                                                                                            </div>

                                                                                            <label
                                                                                                class="col-md-2 col-form-label">Celular
                                                                                                alternativo</label>
                                                                                            <div class="col-md-4">
                                                                                                <div class="input-group">

                                                                                                    <input type="text"
                                                                                                        class="form-control @error('celularRepresentante1') is-invalid @enderror"
                                                                                                        id="celularAlternativoRepresentante1"
                                                                                                        name="celularAlternativoRepresentante1"
                                                                                                        placeholder="N.° celular"
                                                                                                        maxlength="9"
                                                                                                        value="{{ old('celularRepresentante1') }}"
                                                                                                        inputmode="numeric"
                                                                                                        readonly>

                                                                                                    <div
                                                                                                        class="input-group-append">
                                                                                                        <span
                                                                                                            class="input-group-text"><i
                                                                                                                class="fas fa-phone"></i></span>
                                                                                                    </div>
                                                                                                    @error('celularRepresentante1')
                                                                                                        <div
                                                                                                            class="invalid-feedback d-block text-start">
                                                                                                            {{ $message }}
                                                                                                        </div>
                                                                                                    @enderror
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <script>
                                                                                            document.addEventListener('DOMContentLoaded', function() {
                                                                                                const celularInput = document.getElementById('celularRepresentante1');
                                                                                                const dniInput = document.getElementById('dniRepresentante1');

                                                                                                celularInput.addEventListener('input', function() {
                                                                                                    // Reemplaza todo lo que no sea dígito con vacío
                                                                                                    this.value = this.value.replace(/\D/g, '');
                                                                                                });

                                                                                                dniInput.addEventListener('input', function() {
                                                                                                    // Reemplaza todo lo que no sea dígito con vacío
                                                                                                    this.value = this.value.replace(/\D/g, '');
                                                                                                });
                                                                                            });
                                                                                        </script>


                                                                                        <div class="row form-group">
                                                                                            <label
                                                                                                class="col-md-2 col-form-label">
                                                                                                Correo electrónico <span
                                                                                                    style="color: #FF5A6A">(*)</span>
                                                                                            </label>
                                                                                            <div class="col-md-10">
                                                                                                <div class="input-group">
                                                                                                    <input type="text"
                                                                                                        class="form-control @error('correoRepresentante1') is-invalid @enderror"
                                                                                                        id="correoRepresentante1"
                                                                                                        name="correoRepresentante1"
                                                                                                        placeholder="correo@estudiante.com"
                                                                                                        maxlength="100"
                                                                                                        value="{{ old('correoRepresentante1') }}"
                                                                                                        readonly>

                                                                                                    <div
                                                                                                        class="input-group-append">
                                                                                                        <span
                                                                                                            class="input-group-text"><i
                                                                                                                class="fas fa-envelope"></i></span>
                                                                                                    </div>
                                                                                                    @error('correoRepresentante1')
                                                                                                        <div
                                                                                                            class="invalid-feedback d-block text-start">
                                                                                                            {{ $message }}
                                                                                                        </div>
                                                                                                    @enderror
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="row form-group">
                                                                                            <label
                                                                                                class="col-md-2 col-form-label">Dirección
                                                                                                completa <span
                                                                                                    style="color: #FF5A6A">(*)</span></label>
                                                                                            <div class="col-md-10">
                                                                                                <input type="text"
                                                                                                    class="form-control @error('direccionRepresentante1') is-invalid @enderror"
                                                                                                    id="direccionRepresentante1"
                                                                                                    name="direccionRepresentante1"
                                                                                                    placeholder="Dirección completa"
                                                                                                    maxlength="20"
                                                                                                    value="{{ old('direccionRepresentante1') }}"
                                                                                                    readonly>
                                                                                                @error('direccionRepresentante1')
                                                                                                    <div
                                                                                                        class="invalid-feedback d-block text-start">
                                                                                                        {{ $message }}
                                                                                                    </div>
                                                                                                @enderror
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

                                            <!--TODO ESTO ES PARA LOS DATOS DEL REPRESENTANTE 2 (MADRE DEL ESTUDIANTE)-->
                                            <div class="container-fluid">
                                                <div class="row">
                                                    <div class="col-12 ">

                                                        <div class="row">
                                                            <div class="col-12 ">
                                                                <div class="card" style="border: none">
                                                                    <div class="btn btn-block text-left rounded-0 btn_header header_6"
                                                                        type="button" data-toggle="collapse"
                                                                        data-target="#collapseExample5"
                                                                        aria-expanded="true"
                                                                        aria-controls="collapseExample"
                                                                        style="background: #0A8CB3 !important; font-weight: bold; color: white;"
                                                                        style="background: #E0F7FA; color: #0A8CB3; font-weight: bold; border: 2px solid #86D2E3; border-bottom: 2px solid #86D2E3; padding: 6px 20px; border-radius:4px 4px 0px 0px;">
                                                                        Datos de la Madre
                                                                    </div>

                                                                    <div class="card-body collapse show"
                                                                        id="collapseExample5"
                                                                        style="border: 2px solid #86D2E3; border-top: none; border-radius: 0px 0px 4px 4px !important;">
                                                                        <div class="row align-items-center">
                                                                            <!-- Botón a la izquierda -->
                                                                            <div
                                                                                class="col-md-6 mb-md-0 d-flex justify-content-start">
                                                                                <a class="btn btn-primary w-100"
                                                                                    id="Repre2RegistroBtn"
                                                                                    style="color: #f1f1f1">
                                                                                    <i class="fa fa-plus mx-2"></i>
                                                                                    Registrar a la madre de familia
                                                                                </a>
                                                                            </div>

                                                                            <!-- Formulario de búsqueda a la derecha -->
                                                                            <div
                                                                                class="col-md-6 d-flex justify-content-md-end justify-content-start">
                                                                                <div class="input-group w-100 mt-1 h-5"
                                                                                    style="max-width: 100%;">
                                                                                    <input maxlength="8"
                                                                                        id="inputBuscar2" name="buscarpor"
                                                                                        class="form-control"
                                                                                        type="search"
                                                                                        placeholder="Buscar a la madre de familia por N.° de DNI"
                                                                                        aria-label="Search"
                                                                                        style="border-color: #F59617;">
                                                                                    <button id="btnBuscar2"
                                                                                        class="btn btn-primary nuevo-boton"
                                                                                        type="button"
                                                                                        style="border-top-left-radius: 0 !important; border-bottom-left-radius: 0 !important;">
                                                                                        <i class="fas fa-search"></i>
                                                                                    </button>
                                                                                </div>
                                                                            </div>

                                                                            <script>
                                                                                document.addEventListener('DOMContentLoaded', function() {
                                                                                    const btnRegistrar2 = document.getElementById('Repre2RegistroBtn');
                                                                                    const inputBuscar2 = document.getElementById('inputBuscar2');
                                                                                    const btnBuscar2 = document.getElementById('btnBuscar2');

                                                                                    // 1. Si se escribe en el buscador, desactiva el botón "Registrar"
                                                                                    inputBuscar2.addEventListener('input', function() {
                                                                                        if (inputBuscar2.value.trim().length > 0) {
                                                                                            btnRegistrar2.classList.add('disabled');
                                                                                            btnRegistrar2.style.pointerEvents = 'none';
                                                                                        } else {
                                                                                            btnRegistrar2.classList.remove('disabled');
                                                                                            btnRegistrar2.style.pointerEvents = 'auto';
                                                                                        }
                                                                                    });

                                                                                    // 2. Si se hace clic en "Registrar Representante", se bloquea el buscador
                                                                                    btnRegistrar2.addEventListener('click', function(e) {
                                                                                        // Simula acción, puedes quitar esto si lleva a otra página
                                                                                        e.preventDefault();

                                                                                        inputBuscar2.disabled = true;
                                                                                        btnBuscar2.disabled = true;
                                                                                    });
                                                                                });
                                                                            </script>
                                                                            <script>
                                                                                document.getElementById('btnBuscar2').addEventListener('click', function(e) {
                                                                                    e.preventDefault(); // ✅ Ahora sí funcionará

                                                                                    const dniRep2 = document.getElementById('inputBuscar2').value.trim();
                                                                                    const dniRep1 = document.getElementById('dniRepresentante1').value.trim();
                                                                                    const parentesco1 = document.getElementById('parentescoRepresentante1').value.trim();

                                                                                    // Validar si el DNI ya fue usado como Representante 1
                                                                                    if (dniRep2 === dniRep1 && dniRep2 !== '') {
                                                                                        Swal.fire({
                                                                                            icon: 'info',
                                                                                            title: 'Ya registrado',
                                                                                            text: 'Ya registró al representante con ese N.° de DNI.',
                                                                                            showConfirmButton: false,
                                                                                            timer: 1500
                                                                                        });
                                                                                        document.getElementById('inputBuscar2').value = "";
                                                                                        return;

                                                                                    }

                                                                                    if (dniRep2.length === 8 && /^\d+$/.test(dniRep2)) {
                                                                                        fetch("{{ route('buscar.representante') }}", {
                                                                                                method: 'POST',
                                                                                                headers: {
                                                                                                    'Content-Type': 'application/json',
                                                                                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                                                                                },
                                                                                                body: JSON.stringify({
                                                                                                    dni: dniRep2
                                                                                                })
                                                                                            })
                                                                                            .then(response => response.json())
                                                                                            .then(data => {
                                                                                                const inputBuscar2 = document.getElementById('inputBuscar2');
                                                                                                const btnBuscar2 = document.getElementById('btnBuscar2');
                                                                                                const btnAsignar = document.getElementById('btnAsignar');
                                                                                                const select2 = document.getElementById('parentescoRepresentante2');

                                                                                                if (data.success) {
                                                                                                    const r = data.representante;

                                                                                                    // ⚠ Validar que el parentesco no se repita
                                                                                                    if (r.parentesco === parentesco1 && r.parentesco !== '') {
                                                                                                        Swal.fire({
                                                                                                            icon: 'warning',
                                                                                                            title: 'Parentesco duplicado',
                                                                                                            text: `Ya registró un representante con parentesco "${r.parentesco}".`,
                                                                                                            showConfirmButton: false,
                                                                                                            timer: 1500
                                                                                                        });
                                                                                                        document.getElementById('inputBuscar2').value = "";
                                                                                                        return;

                                                                                                    }

                                                                                                    Swal.fire({
                                                                                                        icon: 'success',
                                                                                                        title: 'Representante registrado',
                                                                                                        text: 'El Representante se encuentra registrado correctamente.',
                                                                                                        showConfirmButton: false,
                                                                                                        timer: 1200
                                                                                                    });

                                                                                                    // Asignar datos
                                                                                                    inputBuscar2.disabled = true;
                                                                                                    btnBuscar2.disabled = true;
                                                                                                    btnAsignar.disabled = false;
                                                                                                    select2.disabled = false;

                                                                                                    document.getElementById('idRepresentante2').value = r.representante_id || "";
                                                                                                    document.getElementById('apellidoPaternoRepresentante2').value = r
                                                                                                        .apellidoPaterno || "";
                                                                                                    document.getElementById('apellidoMaternoRepresentante2').value = r
                                                                                                        .apellidoMaterno || "";
                                                                                                    document.getElementById('nombreRepresentante2').value = r.nombres || "";
                                                                                                    document.getElementById('dniRepresentante2').value = r.dni || "";
                                                                                                    document.getElementById('ocupacionRepresentante2').value = r.ocupacion || "";
                                                                                                    document.getElementById('celularRepresentante2').value = r.telefono || "";
                                                                                                    document.getElementById('celularAlternativoRepresentante2').value = r
                                                                                                        .telefono_alternativo || "";
                                                                                                    document.getElementById('correoRepresentante2').value = r.email || "";
                                                                                                    document.getElementById('direccionRepresentante2').value = r.direccion || "";

                                                                                                    // Preseleccionar parentesco si existe la opción
                                                                                                    for (let option of select2.options) {
                                                                                                        if (option.value === r.parentesco) {
                                                                                                            select2.value = r.parentesco;
                                                                                                            break;
                                                                                                        }
                                                                                                    }

                                                                                                    select2.disabled = false; // asegúrate de no desactivarlo
                                                                                                    select2.setAttribute('readonly',
                                                                                                        true
                                                                                                    ); // este atributo no existe por defecto, pero puedes usarlo para simular estilo

                                                                                                    // Opcionalmente: bloquear con CSS
                                                                                                    select2.style.pointerEvents = 'none';
                                                                                                    select2.style.backgroundColor = '#e9ecef'; // estilo visual de campo inactivo

                                                                                                } else {
                                                                                                    // No encontrado: limpiar campos
                                                                                                    [
                                                                                                        'apellidoPaternoRepresentante2',
                                                                                                        'apellidoMaternoRepresentante2',
                                                                                                        'nombreRepresentante2',
                                                                                                        'dniRepresentante2',
                                                                                                        'ocupacionRepresentante2',
                                                                                                        'celularRepresentante2',
                                                                                                        'celularAlternativoRepresentante2',
                                                                                                        'correoRepresentante2',
                                                                                                        'direccionRepresentante2'
                                                                                                    ].forEach(id => document.getElementById(id).value = '');

                                                                                                    document.getElementById('parentescoRepresentante2').selectedIndex = 0;

                                                                                                    const btnRegistrar2 = document.getElementById('Repre2RegistroBtn');
                                                                                                    btnRegistrar2.classList.remove('disabled');
                                                                                                    btnRegistrar2.style.pointerEvents = 'auto';

                                                                                                    inputBuscar2.value = "";
                                                                                                    inputBuscar2.disabled = false;
                                                                                                    btnBuscar2.disabled = false;
                                                                                                    btnAsignar.disabled = true;
                                                                                                    select2.disabled = true;
                                                                                                }
                                                                                            })
                                                                                            .catch(error => {
                                                                                                console.error(error);
                                                                                                alert('Ocurrió un error al buscar el representante.');
                                                                                            });

                                                                                    } else {
                                                                                        Swal.fire({
                                                                                            icon: 'warning',
                                                                                            title: 'DNI inválido',
                                                                                            text: 'Ingrese un N.° de DNI válido para realizar la búsqueda.',
                                                                                            showConfirmButton: false,
                                                                                            timer: 1200
                                                                                        });
                                                                                        document.getElementById('inputBuscar2').value = "";
                                                                                    }
                                                                                });
                                                                            </script>



                                                                            <div id="resultadoRepresentante"
                                                                                class="mt-3"></div>
                                                                        </div>

                                                                        <!-- Loader oculto -->
                                                                        <div id="loaderAnimado" style="display: none;">
                                                                            <div class="overlay-local">
                                                                                <div class="spinner-container">
                                                                                    <span class="circle c1"></span>
                                                                                    <span class="circle c2"></span>
                                                                                    <span class="circle c3"></span>
                                                                                    <span class="circle c4"></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="row form-bordered align-items-center">
                                                                        </div>
                                                                        <div class="container-fluid">
                                                                            <div class="row margen-movil">
                                                                                <div class="col-12">

                                                                                    <!--PADDIG PARA DARLE ESPACIO ENTRE EL CONTENEDOR Y EL CONTENIDO-->
                                                                                    <div class="row"
                                                                                        style="padding:0px;">

                                                                                        <div class="col-12 mt-3">

                                                                                            <div class="card"
                                                                                                style="border: none">
                                                                                                <div
                                                                                                    style="background: #E0F7FA; color: #0A8CB3; font-weight: bold; border: 2px solid #86D2E3; border-bottom: 2px solid #86D2E3; padding: 6px 20px; border-radius:4px 4px 0px 0px;">
                                                                                                    Datos del representante
                                                                                                </div>

                                                                                                <div class="card-body"
                                                                                                    style="border: 2px solid #86D2E3; border-top: none; border-radius: 0px 0px 4px 4px !important;">

                                                                                                    <div
                                                                                                        class="row form-group">
                                                                                                        <label
                                                                                                            class="col-md-2 col-form-label">Apellido
                                                                                                            Paterno
                                                                                                            <span
                                                                                                                style="color: #FF5A6A">(*)</span></label>
                                                                                                        <div
                                                                                                            class="col-md-4">
                                                                                                            <input
                                                                                                                type="text"
                                                                                                                class="form-control @error('apellidoPaternoRepresentante2') is-invalid @enderror"
                                                                                                                id="apellidoPaternoRepresentante2"
                                                                                                                name="apellidoPaternoRepresentante2"
                                                                                                                placeholder="Apellido paterno"
                                                                                                                maxlength="100"
                                                                                                                value="{{ old('apellidoPaternoRepresentante2') }}"
                                                                                                                readonly>
                                                                                                            @if ($errors->has('apellidoPaternoRepresentante2'))
                                                                                                                <span
                                                                                                                    class="invalid-feedback d-block text-start">{{ $errors->first('apellidoPaternoRepresentante2') }}</span>
                                                                                                            @endif
                                                                                                        </div>
                                                                                                        <label
                                                                                                            class="col-md-2 col-form-label">Apellido
                                                                                                            Materno
                                                                                                            <span
                                                                                                                style="color: #FF5A6A">(*)</span></label>
                                                                                                        <div
                                                                                                            class="col-md-4">
                                                                                                            <input
                                                                                                                type="text"
                                                                                                                class="form-control @error('apellidoMaternoRepresentante2') is-invalid @enderror"
                                                                                                                id="apellidoMaternoRepresentante2"
                                                                                                                name="apellidoMaternoRepresentante2"
                                                                                                                placeholder="Apellido materno"
                                                                                                                maxlength="100"
                                                                                                                value="{{ old('apellidoMaternoRepresentante2') }}"
                                                                                                                readonly>
                                                                                                            @if ($errors->has('apellidoMaternoRepresentante2'))
                                                                                                                <span
                                                                                                                    class="invalid-feedback d-block text-start">{{ $errors->first('apellidoMaternoRepresentante2') }}</span>
                                                                                                            @endif
                                                                                                        </div>


                                                                                                        <input
                                                                                                            type="hidden"
                                                                                                            class="form-control @error('idRepresentante1') is-invalid @enderror"
                                                                                                            id="idRepresentante1"
                                                                                                            name="idRepresentante1"
                                                                                                            value="{{ old('idRepresentante1') }}">


                                                                                                        <input
                                                                                                            type="hidden"
                                                                                                            class="form-control @error('idRepresentante2') is-invalid @enderror"
                                                                                                            id="idRepresentante2"
                                                                                                            name="idRepresentante2"
                                                                                                            value="{{ old('idRepresentante2') }}">

                                                                                                    </div>

                                                                                                    <div
                                                                                                        class="row form-group">
                                                                                                        <label
                                                                                                            class="col-md-2 col-form-label">
                                                                                                            Nombres
                                                                                                            <span
                                                                                                                style="color: #FF5A6A">(*)</span>
                                                                                                        </label>
                                                                                                        <div
                                                                                                            class="col-md-10">
                                                                                                            <input
                                                                                                                type="text"
                                                                                                                class="form-control @error('nombreRepresentante2') is-invalid @enderror"
                                                                                                                id="nombreRepresentante2"
                                                                                                                name="nombreRepresentante2"
                                                                                                                placeholder="Nombres"
                                                                                                                maxlength="100"
                                                                                                                value="{{ old('nombreRepresentante2') }}"
                                                                                                                readonly>
                                                                                                            @if ($errors->has('nombreRepresentante2'))
                                                                                                                <span
                                                                                                                    class="invalid-feedback d-block text-start">{{ $errors->first('nombreRepresentante2') }}</span>
                                                                                                            @endif
                                                                                                        </div>
                                                                                                    </div>



                                                                                                    <div
                                                                                                        class="row form-group">
                                                                                                        <label
                                                                                                            class="col-md-2 col-form-label">Parentesco
                                                                                                            <span
                                                                                                                style="color: #FF5A6A">(*)</span></label>
                                                                                                        <div
                                                                                                            class="col-md-4">
                                                                                                            <select
                                                                                                                class="form-control @error('parentescoRepresentante2') is-invalid @enderror"
                                                                                                                id="parentescoRepresentante2"
                                                                                                                name="parentescoRepresentante2"
                                                                                                                readonly>
                                                                                                                <option
                                                                                                                    value=""
                                                                                                                    disabled
                                                                                                                    {{ old('parentescoRepresentante2') == '' ? 'selected' : '' }}>
                                                                                                                    Seleccionar
                                                                                                                    parentesco
                                                                                                                </option>
                                                                                                                <option
                                                                                                                    value="Padre"
                                                                                                                    {{ old('parentescoRepresentante2') == 'Padre' ? 'selected' : '' }}>
                                                                                                                    Padre
                                                                                                                </option>
                                                                                                                <option
                                                                                                                    value="Madre"
                                                                                                                    {{ old('parentescoRepresentante2') == 'Madre' ? 'selected' : '' }}>
                                                                                                                    Madre
                                                                                                                </option>
                                                                                                            </select>
                                                                                                            @error('parentescoRepresentante2')
                                                                                                                <div
                                                                                                                    class="invalid-feedback d-block text-start">
                                                                                                                    {{ $message }}
                                                                                                                </div>
                                                                                                            @enderror
                                                                                                        </div>


                                                                                                        <label
                                                                                                            class="col-md-2 col-form-label">N.°
                                                                                                            DNI <span
                                                                                                                style="color: #FF5A6A">(*)</span></label>
                                                                                                        <div
                                                                                                            class="col-md-4">
                                                                                                            <input
                                                                                                                type="text"
                                                                                                                class="form-control"
                                                                                                                id="dniRepresentante2"
                                                                                                                name="dniRepresentante2"
                                                                                                                maxlength="8"
                                                                                                                placeholder="N.° DNI"
                                                                                                                value="{{ old('dniRepresentante2') }}"
                                                                                                                readonly>
                                                                                                            @error('dniRepresentante2')
                                                                                                                <div
                                                                                                                    class="invalid-feedback d-block text-start">
                                                                                                                    {{ $message }}
                                                                                                                </div>
                                                                                                            @enderror
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <!-- Flatpickr CSS -->
                                                                                                    <link rel="stylesheet"
                                                                                                        href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

                                                                                                    <div
                                                                                                        class="row form-group">
                                                                                                        <label
                                                                                                            class="col-md-2 col-form-label">
                                                                                                            Ocupación
                                                                                                        </label>
                                                                                                        <div
                                                                                                            class="col-md-10">
                                                                                                            <input
                                                                                                                type="text"
                                                                                                                class="form-control"
                                                                                                                id="ocupacionRepresentante2"
                                                                                                                name="ocupacionRepresentante2"
                                                                                                                placeholder="Ocupación del representante"
                                                                                                                value="{{ old('dniRepresentante2') }}"
                                                                                                                maxlength="100"
                                                                                                                readonly>
                                                                                                            @error('ocupacionRepresentante2')
                                                                                                                <div
                                                                                                                    class="invalid-feedback d-block text-start">
                                                                                                                    {{ $message }}
                                                                                                                </div>
                                                                                                            @enderror
                                                                                                        </div>
                                                                                                    </div>

                                                                                                    <style>
                                                                                                        /* Borde dorado personalizado */
                                                                                                        .form-control.custom-gold {
                                                                                                            border: 2px solid #DAA520 !important;
                                                                                                            background-color: white !important;
                                                                                                            color: black;
                                                                                                        }
                                                                                                    </style>


                                                                                                    <!-- Flatpickr JS y Español -->
                                                                                                    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
                                                                                                    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
                                                                                                    <script>
                                                                                                        flatpickr("#fechaNacimientoEstudiante", {
                                                                                                            dateFormat: "Y-m-d",
                                                                                                            maxDate: "today",
                                                                                                            locale: "es",
                                                                                                            onChange: function(selectedDates, dateStr, instance) {
                                                                                                                const input = document.getElementById('fechaNacimientoEstudiante');
                                                                                                                const feedback = input.parentElement.querySelector('.feedback-message');

                                                                                                                if (dateStr) {
                                                                                                                    input.classList.remove('is-invalid');
                                                                                                                    if (feedback) feedback.remove(); // Borra el mensaje si ya había uno
                                                                                                                }
                                                                                                            }
                                                                                                        });
                                                                                                    </script>
                                                                                                </div>
                                                                                            </div>

                                                                                            <div class="card"
                                                                                                style="border: none">
                                                                                                <div
                                                                                                    style="background: #E0F7FA; color: #0A8CB3; font-weight: bold; border: 2px solid #86D2E3; border-bottom: 2px solid #86D2E3; padding: 6px 20px; border-radius:4px 4px 0px 0px;">
                                                                                                    Datos de contacto y
                                                                                                    residencia
                                                                                                </div>

                                                                                                <div class="card-body"
                                                                                                    style="border: 2px solid #86D2E3; border-top: none; border-radius: 0px 0px 4px 4px !important;">

                                                                                                    <div
                                                                                                        class="row form-group">
                                                                                                        <label
                                                                                                            class="col-md-2 col-form-label">
                                                                                                            Celular actual
                                                                                                            <span
                                                                                                                style="color: #FF5A6A">(*)</span>
                                                                                                        </label>
                                                                                                        <div
                                                                                                            class="col-md-4">
                                                                                                            <div
                                                                                                                class="input-group">

                                                                                                                <input
                                                                                                                    type="text"
                                                                                                                    class="form-control @error('celularRepresentante2') is-invalid @enderror"
                                                                                                                    id="celularRepresentante2"
                                                                                                                    name="celularRepresentante2"
                                                                                                                    placeholder="N.° celular"
                                                                                                                    maxlength="9"
                                                                                                                    value="{{ old('celularRepresentante2') }}"
                                                                                                                    inputmode="numeric"
                                                                                                                    readonly>

                                                                                                                <div
                                                                                                                    class="input-group-append">
                                                                                                                    <span
                                                                                                                        class="input-group-text"><i
                                                                                                                            class="fas fa-phone"></i></span>
                                                                                                                </div>
                                                                                                                @error('celularRepresentante2')
                                                                                                                    <div
                                                                                                                        class="invalid-feedback d-block text-start">
                                                                                                                        {{ $message }}
                                                                                                                    </div>
                                                                                                                @enderror
                                                                                                            </div>
                                                                                                        </div>

                                                                                                        <label
                                                                                                            class="col-md-2 col-form-label">Celular
                                                                                                            alternativo</label>
                                                                                                        <div
                                                                                                            class="col-md-4">
                                                                                                            <div
                                                                                                                class="input-group">

                                                                                                                <input
                                                                                                                    type="text"
                                                                                                                    class="form-control @error('celularAlternativoRepresentante2') is-invalid @enderror"
                                                                                                                    id="celularAlternativoRepresentante2"
                                                                                                                    name="celularAlternativoRepresentante2"
                                                                                                                    placeholder="N.° celular"
                                                                                                                    maxlength="9"
                                                                                                                    value="{{ old('celularAlternativoRepresentante2') }}"
                                                                                                                    inputmode="numeric"
                                                                                                                    readonly>

                                                                                                                <div
                                                                                                                    class="input-group-append">
                                                                                                                    <span
                                                                                                                        class="input-group-text"><i
                                                                                                                            class="fas fa-phone"></i></span>
                                                                                                                </div>
                                                                                                                @error('celularAlternativoRepresentante2')
                                                                                                                    <div
                                                                                                                        class="invalid-feedback d-block text-start">
                                                                                                                        {{ $message }}
                                                                                                                    </div>
                                                                                                                @enderror
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>

                                                                                                    <script>
                                                                                                        document.addEventListener('DOMContentLoaded', function() {
                                                                                                            const celularInput = document.getElementById('celularRepresentante1');
                                                                                                            const dniInput = document.getElementById('dniEstudiante');

                                                                                                            celularInput.addEventListener('input', function() {
                                                                                                                // Reemplaza todo lo que no sea dígito con vacío
                                                                                                                this.value = this.value.replace(/\D/g, '');
                                                                                                            });

                                                                                                            dniInput.addEventListener('input', function() {
                                                                                                                // Reemplaza todo lo que no sea dígito con vacío
                                                                                                                this.value = this.value.replace(/\D/g, '');
                                                                                                            });
                                                                                                        });
                                                                                                    </script>


                                                                                                    <div
                                                                                                        class="row form-group">
                                                                                                        <label
                                                                                                            class="col-md-2 col-form-label">
                                                                                                            Correo
                                                                                                            electrónico
                                                                                                            <span
                                                                                                                style="color: #FF5A6A">(*)</span>
                                                                                                        </label>
                                                                                                        <div
                                                                                                            class="col-md-10">
                                                                                                            <div
                                                                                                                class="input-group">

                                                                                                                <input
                                                                                                                    type="text"
                                                                                                                    class="form-control @error('correoRepresentante2') is-invalid @enderror"
                                                                                                                    id="correoRepresentante2"
                                                                                                                    name="correoRepresentante2"
                                                                                                                    placeholder="correo@estudiante.com"
                                                                                                                    maxlength="100"
                                                                                                                    value="{{ old('correoRepresentante2') }}"
                                                                                                                    readonly>

                                                                                                                <div
                                                                                                                    class="input-group-append">
                                                                                                                    <span
                                                                                                                        class="input-group-text"><i
                                                                                                                            class="fas fa-envelope"></i></span>
                                                                                                                </div>
                                                                                                                @error('correoRepresentante2')
                                                                                                                    <div
                                                                                                                        class="invalid-feedback d-block text-start">
                                                                                                                        {{ $message }}
                                                                                                                    </div>
                                                                                                                @enderror
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>


                                                                                                    <div
                                                                                                        class="row form-group">
                                                                                                        <label
                                                                                                            class="col-md-2 col-form-label">Dirección
                                                                                                            completa <span
                                                                                                                style="color: #FF5A6A">(*)</span></label>
                                                                                                        <div
                                                                                                            class="col-md-10">
                                                                                                            <input
                                                                                                                type="text"
                                                                                                                class="form-control @error('direccionRepresentante2') is-invalid @enderror"
                                                                                                                id="direccionRepresentante2"
                                                                                                                name="direccionRepresentante2"
                                                                                                                placeholder="Dirección completa"
                                                                                                                maxlength="20"
                                                                                                                value="{{ old('direccionRepresentante2') }}"
                                                                                                                readonly>
                                                                                                            @error('direccionRepresentante2')
                                                                                                                <div
                                                                                                                    class="invalid-feedback d-block text-start">
                                                                                                                    {{ $message }}
                                                                                                                </div>
                                                                                                            @enderror
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

                                                                <div class="d-flex justify-content-center">
                                                                    <button id="btnAsignar" type="submit"
                                                                        class="btn btn-primary btn-block"
                                                                        style="background: #FF3F3F !important; border: none;">
                                                                        <span>Asignar Representante Legal</span>
                                                                    </button>
                                                                </div>
                                                                </form>
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
                        <br>
                    </div>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        function habilitarCamposRepre(ids) {
                            ids.forEach(function(id) {
                                const input = document.getElementById(id);
                                if (input) input.readOnly = false;
                            });
                        }

                        document.getElementById('Repre1RegistroBtn')?.addEventListener('click', function(e) {
                            e.preventDefault(); // evitar que el <a> recargue la página
                            const camposRepre1 = [
                                'dniRepresentante1',
                                'apellidoPaternoRepresentante1',
                                'apellidoMaternoRepresentante1',
                                'nombreRepresentante1',
                                'parentescoRepresentante1',
                                'direccionRepresentante1',
                                'correoRepresentante1',
                                'ocupacionRepresentante1',
                                'celularRepresentante1',
                                'celularAlternativoRepresentante1'
                            ];
                            habilitarCamposRepre(camposRepre1);
                        });

                        document.getElementById('Repre2RegistroBtn')?.addEventListener('click', function(e) {
                            e.preventDefault();
                            const camposRepre2 = [
                                'dniRepresentante2',
                                'apellidoPaternoRepresentante2',
                                'apellidoMaternoRepresentante2',
                                'nombreRepresentante2',
                                'parentescoRepresentante2',
                                'direccionRepresentante2',
                                'correoRepresentante2',
                                'ocupacionRepresentante2',
                                'celularRepresentante2',
                                'celularAlternativoRepresentante2'
                            ];
                            habilitarCamposRepre(camposRepre2);
                        });
                    });
                </script>


                <style>
                    .btn-primary {

                        background: #F59617 !important;
                        border: none;
                        transition: background-color 0.2s ease, transform 0.1s ease;
                        margin-bottom: 0px;
                        font-family: "Quicksand", sans-serif;
                        font-weight: 700;
                        font-size: clamp(0.9rem, 2.0vw, 0.9rem) !important;
                    }

                    .btn-primary:hover {
                        background-color: #F59619 !important;
                        transform: scale(1.007);
                    }
                </style>

                <!-- Modal de éxito -->
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

                @if (session('success'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Éxito!',
                                text: '{{ session('success') }}',
                                confirmButtonColor: '#28a745',
                                timer: 2200,
                                showConfirmButton: false
                            });
                        });
                    </script>
                @endif


                <style>
                    .btn-action-group button {
                        margin-right: 5px;
                    }

                    .collapse-row {
                        transition: all 0.3s ease-in-out;
                        display: none;
                    }

                    .collapse-row.show {
                        display: table-row;
                    }

                    .toggle-btn {
                        padding: 0;
                        border: none;
                        background: none;
                        cursor: pointer;
                        /* Solo aquí */
                    }

                    .toggle-btn i {
                        font-size: 0.9rem;
                        color: #007bff;
                        pointer-events: none;
                        /* Para que el icono no capture eventos y se maneje en el botón */
                    }

                    /* Bicolor de filas */
                    .table-custom tbody tr:nth-of-type(odd) {
                        background-color: #f5f5f5;
                    }

                    .table-custom tbody tr:nth-of-type(even) {
                        background-color: #e0e0e0;
                    }

                    /* El detalle tendrá el mismo color que la fila anterior DETALLE DE IMPARES*/
                    .collapse-row.odd {
                        background-color: #f5f5f5;
                    }

                    /* El detalle tendrá el mismo color que la fila anterior DETALLE DE PARES*/
                    .collapse-row.even {
                        background-color: #e0e0e0;
                    }

                    /* Hover en toda la fila (sin cambiar cursor) */
                    .table-hover tbody tr:hover {
                        background-color: #FFF4E7 !important;
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
                        background-color: #f1f1f1;
                        /* Color gris cuando el cursor pasa por encima */
                        color: #333;
                    }

                    /* Páginas activas con fondo negro */
                    .pagination .page-item.active .page-link {
                        background-color: #0A8CB3 !important;
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

                <!--VALIDA TANTO PARA REP1 COMO REP2 LA CONSISTENCIA DE DATOS
                                                                    Si SE coloca el <script>
                                                                        antes de que existan los botones en el DOM, no encontrará los IDs y no enlazará los eventos.
                                                                        Asegurarse de envolver el script en: DOCUMENT.ADDEVENT...-- >


                                                                            <script >
                                                                            document.addEventListener("DOMContentLoaded", function() {
                                                                                const representantes = [1, 2];

                                                                                representantes.forEach(function(num) {
                                                                                    const inputs = {
                                                                                        dni: document.getElementById(`dniRepresentante${num}`),
                                                                                        apellidoPaterno: document.getElementById(`apellidoPaternoRepresentante${num}`),
                                                                                        apellidoMaterno: document.getElementById(`apellidoMaternoRepresentante${num}`),
                                                                                        nombres: document.getElementById(`nombreRepresentante${num}`),
                                                                                        parentesco: document.getElementById(`parentescoRepresentante${num}`),
                                                                                        ocupacion: document.getElementById(`ocupacionRepresentante${num}`),
                                                                                        direccion: document.getElementById(`direccionRepresentante${num}`),
                                                                                        celular: document.getElementById(`celularRepresentante${num}`),
                                                                                        celularAlternativo: document.getElementById(
                                                                                            `celularAlternativoRepresentante${num}`),
                                                                                        correo: document.getElementById(`correoRepresentante${num}`)
                                                                                    };

                                                                                    function setInvalid(input, message) {
                                                                                        input.classList.add('is-invalid');
                                                                                        let feedback = input.parentElement.querySelector('.invalid-feedback');
                                                                                        if (!feedback) {
                                                                                            feedback = document.createElement('div');
                                                                                            feedback.className = 'invalid-feedback d-block text-start';
                                                                                            input.parentElement.appendChild(feedback);
                                                                                        }
                                                                                        feedback.textContent = message;
                                                                                    }

                                                                                    function clearInvalid(input) {
                                                                                        input.classList.remove('is-invalid');
                                                                                        const feedback = input.parentElement.querySelector('.invalid-feedback');
                                                                                        if (feedback) feedback.remove();
                                                                                    }

                                                                                    // Validaciones
                                                                                    inputs.dni?.addEventListener('input', function() {
                                                                                        const regex = /^\d{8}$/;
                                                                                        if (!regex.test(this.value)) {
                                                                                            setInvalid(this, 'El DNI debe contener exactamente 8 números.');
                                                                                        } else {
                                                                                            clearInvalid(this);
                                                                                        }
                                                                                    });

                                                                                    inputs.apellidoPaterno?.addEventListener('input', function() {
                                                                                        if (this.value.length < 2 || this.value.length > 100) {
                                                                                            setInvalid(this, 'Debe tener entre 2 y 100 caracteres.');
                                                                                        } else {
                                                                                            clearInvalid(this);
                                                                                        }
                                                                                    });

                                                                                    inputs.apellidoMaterno?.addEventListener('input', function() {
                                                                                        if (this.value.length < 2 || this.value.length > 100) {
                                                                                            setInvalid(this, 'Debe tener entre 2 y 100 caracteres.');
                                                                                        } else {
                                                                                            clearInvalid(this);
                                                                                        }
                                                                                    });

                                                                                    inputs.nombres?.addEventListener('input', function() {
                                                                                        if (this.value.length < 2 || this.value.length > 100) {
                                                                                            setInvalid(this, 'Debe tener entre 2 y 100 caracteres.');
                                                                                        } else {
                                                                                            clearInvalid(this);
                                                                                        }
                                                                                    });

                                                                                    inputs.parentesco?.addEventListener('change', function() {
                                                                                        if (!['Padre', 'Madre'].includes(this.value)) {
                                                                                            setInvalid(this, 'Seleccione un parentesco válido.');
                                                                                        } else {
                                                                                            clearInvalid(this);
                                                                                        }
                                                                                    });

                                                                                    inputs.ocupacion?.addEventListener('input', function() {
                                                                                        if (this.value.length < 2 || this.value.length > 100) {
                                                                                            setInvalid(this, 'Debe tener entre 2 y 100 caracteres.');
                                                                                        } else {
                                                                                            clearInvalid(this);
                                                                                        }
                                                                                    });

                                                                                    inputs.direccion?.addEventListener('input', function() {
                                                                                        if (this.value.length < 5 || this.value.length > 200) {
                                                                                            setInvalid(this, 'Debe tener entre 5 y 200 caracteres.');
                                                                                        } else {
                                                                                            clearInvalid(this);
                                                                                        }
                                                                                    });

                                                                                    inputs.celular?.addEventListener('input', function() {
                                                                                        const regex = /^\d{9}$/;
                                                                                        if (!regex.test(this.value)) {
                                                                                            setInvalid(this, 'El celular debe tener exactamente 9 dígitos.');
                                                                                        } else {
                                                                                            clearInvalid(this);
                                                                                        }
                                                                                    });

                                                                                    inputs.celularAlternativo?.addEventListener('input', function() {
                                                                                        const regex = /^\d{9}$/;
                                                                                        if (this.value && !regex.test(this.value)) {
                                                                                            setInvalid(this,
                                                                                                'El celular alternativo debe tener exactamente 9 dígitos.');
                                                                                        } else {
                                                                                            clearInvalid(this);
                                                                                        }
                                                                                    });

                                                                                    inputs.correo?.addEventListener('input', function() {
                                                                                        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                                                                                        if (!regex.test(this.value)) {
                                                                                            setInvalid(this, 'Ingrese un correo electrónico válido.');
                                                                                        } else {
                                                                                            clearInvalid(this);
                                                                                        }
                                                                                    });
                                                                                });
                                                                            });
                                                                    </script>

                                                                <script>
                                                                    document.addEventListener('DOMContentLoaded', function() {
                                                                        const formularios = [{
                                                                                formId: 'formBuscar',
                                                                                inputId: 'inputBuscar'
                                                                            },
                                                                            {
                                                                                formId: 'formBuscar2',
                                                                                inputId: 'inputBuscar2'
                                                                            }
                                                                        ];

                                                                        formularios.forEach(({
                                                                            formId,
                                                                            inputId
                                                                        }) => {
                                                                            const form = document.getElementById(formId);
                                                                            const input = document.getElementById(inputId);

                                                                            if (form && input) {
                                                                                form.addEventListener('submit', function(e) {
                                                                                    if (input.value.trim() === '') {
                                                                                        e.preventDefault();
                                                                                        Swal.fire({
                                                                                            icon: 'warning',
                                                                                            title: 'Campo vacío',
                                                                                            text: 'Ingrese el N.° de DNI para realizar la búsqueda.',
                                                                                            showConfirmButton: false,
                                                                                            timer: 1000
                                                                                        });
                                                                                    }
                                                                                });
                                                                            }
                                                                        });
                                                                    });
                                                                </script>
