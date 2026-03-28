@extends('cplantilla.bprincipal')
@section('titulo', 'Editar Estudiante')
@section('contenidoplantilla')
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

    <div class="container-fluid estilo-info margen-movil-2">
        <div class="row mt-4 ml-1 mr-1">
            <div class="col-12 mb-3">
                <div class="box_block">
                    <button style="background: #0A8CB3 !important; border:none"
                        class="btn btn-primary btn-block text-left rounded-0 btn_header header_6 estilo-info" type="button"
                        data-toggle="collapse" data-target="#collapseEstudianteNuevo" aria-expanded="true"
                        aria-controls="collapseEstudianteNuevo">
                        <i class="fas fa-file-signature"></i>&nbsp;Ficha del Estudiante
                        <div class="float-right"><i class="fas fa-chevron-down"></i></div>
                    </button>
                </div>
                <div class="collapse show" id="collapseEstudianteNuevo">
                    <div class="card card-body rounded-0 border-0 pt-0"
                        style="padding-left:0.966666666rem;padding-right:0.9033333333333333rem;">
                        <div class="row margen-movil" style="padding:20px;">
                            <div class="col-12">
                                <div class="card" style="border: none">
                                    <div class="card-header-custom">
                                        <i class="icon-user-following mr-2"></i>
                                        Datos Personales
                                    </div>

                                    <div class="card-body"
                                        style="border: 2px solid #86D2E3; border-top: none; border-radius: 0px 0px 6px 6px !important;">
                                        <form id="formularioEstudiante" method="POST" action="#" autocomplete="off"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="row mb-3 align-items-center">
                                                <label class="col-12 col-md-2 col-form-label">Apellido Paterno </label>
                                                <div class="col-12 col-md-4">
                                                    <input type="text"
                                                        class="form-control @error('apellidoPaterno') is-invalid @enderror disabled-format"
                                                        id="apellidoPaterno" name="apellidoPaterno"
                                                        placeholder="Apellido paterno" maxlength="100"
                                                        value="{{ $estudiante->persona->apellidoPaterno }}" readonly>
                                                    @if ($errors->has('apellidoPaterno'))
                                                        <span
                                                            class="invalid-feedback d-block text-start">{{ $errors->first('apellidoPaterno') }}</span>
                                                    @endif
                                                </div>
                                                <label class="col-12 col-md-2 col-form-label">Apellido Materno </label>
                                                <div class="col-12 col-md-4">
                                                    <input type="text"
                                                        class="form-control @error('apellidoMaterno') is-invalid @enderror disabled-format"
                                                        id="apellidoMaterno" name="apellidoMaterno"
                                                        placeholder="Apellido materno" maxlength="100"
                                                        value="{{ $estudiante->persona->apellidoMaterno }}" readonly>
                                                    @if ($errors->has('apellidoMaterno'))
                                                        <span
                                                            class="invalid-feedback d-block text-start">{{ $errors->first('apellidoMaterno') }}</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="row mb-3 align-items-center">
                                                <label class="col-12 col-md-2 col-form-label">
                                                    Nombres
                                                </label>
                                                <div class="col-12 col-md-10">
                                                    <input type="text"
                                                        class="form-control @error('nombres') is-invalid @enderror disabled-format"
                                                        id="nombres" name="nombres" placeholder="Nombres" maxlength="100"
                                                        value="{{ $estudiante->persona->nombres }}" readonly>
                                                    @if ($errors->has('nombres'))
                                                        <span
                                                            class="invalid-feedback d-block text-start">{{ $errors->first('nombres') }}</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="row mb-3 align-items-center">
                                                <label class="col-12 col-md-2 col-form-label">Sexo </label>
                                                <div class="col-12 col-md-4">
                                                    <input type="text"
                                                        value="{{ $estudiante->persona->genero == 'M' ? 'Masculino' : 'Femenino' }}"
                                                        class="form-control disabled-format" readonly>
                                                    <select
                                                        class="form-control @error('genero') is-invalid @enderror disabled-format"
                                                        id="genero" name="genero" hidden>
                                                        <option value="" disabled
                                                            {{ old('genero', $estudiante->persona->genero ?? '') == '' ? 'selected' : '' }}>
                                                            Seleccionar sexo
                                                        </option>

                                                        <option value="M"
                                                            {{ old('genero', $estudiante->persona->genero ?? '') == 'M' ? 'selected' : '' }}>
                                                            Masculino
                                                        </option>

                                                        <option value="F"
                                                            {{ old('genero', $estudiante->persona->genero ?? '') == 'F' ? 'selected' : '' }}>
                                                            Femenino
                                                        </option>
                                                    </select>

                                                    @error('genero')
                                                        <div class="invalid-feedback d-block text-start">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>

                                                <label class="col-12 col-md-2 col-form-label">N.° DNI </label>
                                                <div class="col-12 col-md-4">
                                                    <input type="text"
                                                        class="form-control @error('dni') is-invalid @enderror disabled-format"
                                                        id="dni" name="dni" maxlength="8" placeholder="N.° DNI"
                                                        value="{{ $estudiante->persona->dni }}" inputmode="numeric"
                                                        readonly>
                                                    @error('dni')
                                                        <div class="invalid-feedback d-block text-start">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                    <div id="dni-error" class="mt-1 text-danger d-none"
                                                        style="font-size:smaller; color:#DE2246 !important">Ya existe un
                                                        docente registrado con este N.° de DNI.</div>
                                                </div>
                                            </div>

                                            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

                                            <div class="row mb-3 align-items-center">
                                                <label class="col-12 col-md-2 col-form-label">Fecha de Nacimiento </label>
                                                <div class="col-12 col-md-10">
                                                    <input type="date"
                                                        class="form-control @error('fecha_nacimiento') is-invalid @enderror disabled-format"
                                                        id="fecha_nacimiento" name="fecha_nacimiento"
                                                        placeholder="YYYY-MM-DD"
                                                        value="{{ $estudiante->persona->fecha_nacimiento }}" readonly>
                                                    @if ($errors->has('fecha_nacimiento'))
                                                        <div class="invalid-feedback d-block text-start feedback-message">
                                                            {{ $errors->first('fecha_nacimiento') }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="mt-4">
                                                <div class="alert alert-info d-flex align-items-center" role="alert">
                                                    <i class="fas fa-info-circle me-2 mr-2"></i>
                                                    <span> Por motivos de control y consistencia de la información,
                                                        los campos anteriores no pueden ser modificados. Si crees que se
                                                        trata de un error, ponte en contacto con el Administrador.</span>
                                                </div>
                                            </div>
                                    </div>
                                </div>

                                <div class="card mt-4" style="border: none">
                                    <div class="card-header-custom">
                                        <i class="icon-camera mr-2"></i>
                                        Foto de Identificación
                                    </div>
                                    <div class="card-body"
                                        style="border: 2px solid #86D2E3; border-top: none; border-radius: 0px 0px 6px 6px !important;">

                                        <div class="row mb-3 align-items-center d-flex justify-content-center gap-4">

                                            <!-- DROPZONE -->
                                            <div class="col-12 col-md-7">
                                                <div class="dropzone" id="dropzone">
                                                    <input type="file" name="foto" id="foto" accept="image/*"
                                                        hidden onchange="previewImage(event)">

                                                    <div class="dropzone-content text-center">
                                                        <i class="bi bi-cloud-arrow-up-fill dropzone-icon"></i>
                                                        <p class="mb-1 fw-semibold">Arrastra y suelta tu imagen aquí</p>
                                                        <small class="text-muted">o haz clic para seleccionar</small>
                                                        <div class="text-muted mt-1" style="font-size: 12px;">
                                                            SVG, PNG o JPG <span style="color:#FF5A6A">(Tamaño
                                                                Carné)</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- PREVIEW -->
                                            <div class="col-12 col-md-4 d-flex justify-content-center">
                                                <div class="text-center">
                                                    @if (!empty($estudiante->persona->foto_url) || $estudiante->persona->foto_url != 0)
                                                        <img id="img-preview"
                                                            src="{{ asset('storage/estudiantes/' . $estudiante->persona->foto_url) }}"
                                                            alt="Foto del estudiante" class="img-fluid rounded mt-2"
                                                            data-original="{{ $estudiante->persona->foto_url ? asset('storage/estudiantes/' . $estudiante->persona->foto_url) : '' }}"
                                                            style="min-height:160px; max-height:180px; border:1px solid #DAA520; padding:4px; "
                                                            object-fit="cover" draggable="false"
                                                            oncontextmenu="return false">
                                                    @else
                                                        <img id="img-preview"
                                                            src="{{ asset('imagenes/imgEstudiante.png') }}"
                                                            alt="Vista previa" class="img-fluid rounded shadow-sm "
                                                            style="max-height: 190px; border: 1px solid #e0e0e0; padding: 6px; border-radius: 12px !important;">
                                                    @endif
                                                    <small class="mt-2 fw-bold">Foto del estudiante</small>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <style>
                                    .dropzone {
                                        border: 2px dashed #7eccde !important;
                                        border-radius: 12px;
                                        background-color: rgba(240, 248, 255, 0.407) !important;
                                        padding: 25px;
                                        cursor: pointer;
                                        transition: all 0.3s ease;
                                        min-height: 120px;
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                    }

                                    .dropzone:hover {
                                        background-color: rgba(240, 248, 255, 0.682) !important;
                                        border-color: #4bb8cc !important;
                                    }

                                    .dropzone.dragover {
                                        background-color: rgba(75, 184, 204, 0.15) !important;
                                        border-color: #0d6efd !important;
                                        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1) !important;
                                    }

                                    .dropzone-icon {
                                        font-size: 36px;
                                        color: #4bb8cc;
                                        margin-bottom: 5px;
                                    }
                                </style>
                                <script>
                                    const dropzone = document.getElementById('dropzone');
                                    const input = document.getElementById('foto');

                                    dropzone.addEventListener('click', () => input.click());

                                    dropzone.addEventListener('dragover', e => {
                                        e.preventDefault();
                                        dropzone.classList.add('dragover');
                                    });

                                    dropzone.addEventListener('dragleave', () => {
                                        dropzone.classList.remove('dragover');
                                    });

                                    dropzone.addEventListener('drop', e => {
                                        e.preventDefault();
                                        dropzone.classList.remove('dragover');

                                        input.files = e.dataTransfer.files;
                                        previewImage({
                                            target: input
                                        });
                                    });

                                    function previewImage(event) {

                                        //llamamos al div que muestra la imagen
                                        const preview = document.getElementById('img-preview');
                                        original = preview.getAttribute('data-original');

                                        //SOLO SI HAY UN ARCHIVO SE PROCEDE
                                        if (input.files && input.files[0]) {
                                            const file = input.files[0];

                                            // Validación estricta del tipo MIME
                                            if (!file.type.startsWith('image/')) {

                                                Swal.fire({
                                                    icon: 'error',
                                                    title: 'Archivo no permitido',
                                                    text: 'Solo se permiten archivos de imagen.',
                                                    showConfirmButton: false,
                                                    timer: 3200
                                                });
                                                // Restablecer la vista previa a la imagen predeterminada
                                                preview.src = original || "{{ asset('imagenes/imgEstudiante.png') }}";
                                                return;
                                            }

                                            //Si no error de tipo de imagen, mostrarla en vista previa
                                            const reader = new FileReader(); //nuevo objeto FileReader (lector de archivos)
                                            reader.onload = function(e) { //cuando se carga el archivo, se ejecuta esta función
                                                preview.src = e.target.result; //se establece la fuente de la imagen de vista previa
                                            };
                                            reader.readAsDataURL(file); //leer el archivo como una URL de datos
                                        }

                                    }
                                </script>
                                <style>
                                    .custom-file-label::after {
                                        content: "Seleccionar" !important;
                                        background-color: #DAA520;
                                        color: white;
                                        border-left: none;
                                    }
                                </style>



                                <div class="card mt-4" style="border: none">
                                    <div class="card-header-custom">
                                        <i class="icon-location-pin mr-2"></i>
                                        Información de Residencia
                                    </div>
                                    <div class="card-body"
                                        style="border: 2px solid #86D2E3; border-top: none; border-radius: 0px 0px 6px 6px !important;">

                                        <div class="row mb-3 align-items-center">
                                            <label class="col-12 col-md-2 col-form-label">
                                                Región <span style="color: #FF5A6A">(*)</span>
                                            </label>
                                            <div class="col-12 col-md-10">
                                                <select id="region" name="region"
                                                    class="form-control @error('region') is-invalid @enderror">
                                                    <option value="" disabled
                                                        {{ old('region') == '' ? 'selected' : '' }}>
                                                        Seleccionar
                                                        Región</option>
                                                </select>
                                                @error('region')
                                                    <div class="invalid-feedback d-block text-start">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-3 align-items-center">
                                            <label class="col-12 col-md-2 col-form-label">
                                                Provincia <span style="color: #FF5A6A">(*)</span>
                                            </label>
                                            <div class="col-12 col-md-4">
                                                <select id="provincia" name="provincia"
                                                    class="form-control @error('provincia') is-invalid @enderror">
                                                    <option value="" disabled
                                                        {{ old('provincia') == '' ? 'selected' : '' }}>
                                                        Seleccionar Provincia</option>
                                                </select>
                                                @error('provincia')
                                                    <div class="invalid-feedback d-block text-start">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <label class="col-12 col-md-2 col-form-label">
                                                Distrito <span style="color: #FF5A6A">(*)</span>
                                            </label>
                                            <div class="col-12 col-md-4">
                                                <select id="distrito" name="distrito"
                                                    class="form-control @error('distrito') is-invalid @enderror">
                                                    <option value="" disabled
                                                        {{ old('distrito') == '' ? 'selected' : '' }}>
                                                        Seleccionar
                                                        Distrito</option>
                                                </select>
                                                @error('distrito')
                                                    <div class="invalid-feedback d-block text-start">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-3 align-items-center">
                                            <label class="col-12 col-md-2 col-form-label">Avenida o calle <span
                                                    style="color: #FF5A6A">(*)</span></label>
                                            <div class="col-12 col-md-4">
                                                <input type="text"
                                                    class="form-control @error('calleEstudiante') is-invalid @enderror"
                                                    id="calleEstudiante" name="calleEstudiante"
                                                    placeholder="Avenida o calle" maxlength="20"
                                                    value="{{ old('calleEstudiante') }}">
                                                @error('calleEstudiante')
                                                    <div class="invalid-feedback d-block text-start">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <label class="col-12 col-md-2 col-form-label">Número</label>
                                            <div class="col-12 col-md-4">
                                                <input type="text" class="form-control" id="numeroEstudiante"
                                                    name="numeroEstudiante" placeholder="149" maxlength="5"
                                                    value="{{ old('numeroEstudiante') }}" inputmode="numeric">
                                            </div>
                                        </div>

                                        <div class="row mb-3 align-items-center">
                                            <label class="col-12 col-md-2 col-form-label">Urbanización</label>
                                            <div class="col-12 col-md-10">
                                                <input type="text" class="form-control" id="urbanizacionEstudiante"
                                                    name="urbanizacionEstudiante" placeholder="Urbanización"
                                                    maxlength="20" value="{{ old('urbanizacionEstudiante') }}">
                                            </div>
                                        </div>
                                        <div class="row mb-3 align-items-center">
                                            <label class="col-12 col-md-2 col-form-label">Referencia</label>
                                            <div class="col-12 col-md-10">
                                                <input type="text" class="form-control" id="referenciaEstudiante"
                                                    name="referenciaEstudiante" placeholder="Referencia" maxlength="20"
                                                    value="{{ old('referenciaEstudiante') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card mt-4 " style="border: none">
                                    <div class="card-header-custom">
                                        <i class="icon-phone mr-2"></i>
                                        Información de Contacto
                                    </div>
                                    <div class="card-body"
                                        style="border: 2px solid #86D2E3; border-top: none; border-radius: 0px 0px 6px 6px !important;">

                                        <div class="row mb-3 align-items-center">
                                            <label class="col-12 col-md-2 col-form-label">
                                                Celular actual <span style="color: #FF5A6A">(*)</span>
                                            </label>
                                            <div class="col-12 col-md-10">
                                                <div class="input-group">
                                                    <input type="telphone"
                                                        class="form-control @error('telefono') is-invalid @enderror"
                                                        id="telefono" name="telefono" placeholder="N.° celular"
                                                        value="{{ $estudiante->persona->telefono }}" inputmode="numeric"
                                                        maxlength="11">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text"
                                                            style="border-color: #DAA520 !important;"><i
                                                                class="fas fa-phone"></i></span>
                                                    </div>
                                                    @error('telefono')
                                                        <div class="invalid-feedback d-block text-start">{{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <!--PARA ENVIAR SOLO 9 DIGITOS (SIN SUS ESPACIOS)-->
                                        <script>
                                            document.getElementById('formularioEstudiante').addEventListener('submit', function() {
                                                const celularInput = document.getElementById('telefono');
                                                celularInput.value = celularInput.value.replace(/\s+/g, '');
                                            });
                                        </script>
                                        <!--PARA NO PERMITIR CARACTERES NI ESPACIOS-->
                                        <script>
                                            document.addEventListener('DOMContentLoaded', function() {
                                                const dniInput = document.getElementById('dni');
                                                const correoInput = document.getElementById('email');
                                                const apPaterno = document.getElementById('apellidoPaterno');
                                                const apMaterno = document.getElementById('apellidoMaterno');
                                                const celInput = document.getElementById('telefono');

                                                dniInput.addEventListener('input', function() {
                                                    // Reemplaza todo lo que no sea dígito con vacío
                                                    this.value = this.value.replace(/\D/g, '');
                                                });
                                                dniInput.addEventListener('keydown', function(e) {
                                                    if (e.key === ' ') {
                                                        e.preventDefault(); // No permite escribir espacio
                                                    }
                                                });

                                                celInput.addEventListener('keydown', function(e) {
                                                    if (e.key === ' ') {
                                                        e.preventDefault(); // No permite escribir espacio
                                                    }
                                                });

                                                correoInput.addEventListener('keydown', function(e) {
                                                    if (e.key === ' ') {
                                                        e.preventDefault(); // No permite escribir espacio
                                                    }
                                                });
                                                apPaterno.addEventListener('keydown', function(e) {
                                                    if (e.key === ' ') {
                                                        e.preventDefault(); // No permite escribir espacio
                                                    }
                                                });
                                                apMaterno.addEventListener('keydown', function(e) {
                                                    if (e.key === ' ') {
                                                        e.preventDefault(); // No permite escribir espacio
                                                    }
                                                });
                                            });
                                        </script>

                                        <div class="row mb-3 align-items-center">
                                            <label class="col-12 col-md-2 col-form-label">
                                                Correo electrónico <span style="color: #FF5A6A">(*)</span>
                                            </label>
                                            <div class="col-12 col-md-10">
                                                <div class="input-group">
                                                    <input type="email"
                                                        class="form-control @error('email') is-invalid @enderror"
                                                        id="email" name="email" placeholder="correo@docente.com"
                                                        maxlength="100" value="{{ $estudiante->persona->email }}"
                                                        inputmode="email">
                                                    <div class="input-group-append">
                                                        <span
                                                            class="input-group-text "style="border-color: #DAA520 !important;"><i
                                                                class="fas fa-envelope"></i></span>
                                                    </div>

                                                    @error('email')
                                                        <div class="invalid-feedback d-block text-start">{{ $message }}
                                                        </div>
                                                    @enderror

                                                </div>

                                            </div>
                                        </div>


                                        <script>
                                            $('#email').on('input', function() {
                                                const email = $(this).val();

                                                if (email.length <= 120) {
                                                    $.ajax({
                                                        url: "{{ route('verificar.email.docente') }}",
                                                        method: 'GET',
                                                        data: {
                                                            email
                                                        },
                                                        success: function(response) {
                                                            //validar si existe el estudiante
                                                            if (response.existe) {
                                                                //si no existe, quita los errores
                                                                $('#email-error').removeClass('d-none');
                                                                $('#email').addClass('is-invalid');
                                                                //$('#btnAsignar').prop('disabled', true);
                                                            } else {
                                                                //muestra el error
                                                                $('#email-error').addClass('d-none');
                                                                $('#email').removeClass('is-invalid');
                                                                //$('#btnAsignar').prop('disabled', false);
                                                            }
                                                        }
                                                    });
                                                } else {
                                                    //añade el (debe ser de 8 digitos)
                                                    $('#email-error').addClass('d-none');
                                                    $('#email').removeClass('is-invalid');
                                                    //$('#btnAsignar').prop('disabled', true);
                                                }
                                            });
                                        </script>

                                    </div>

                                </div>

                                <div class="d-flex justify-content-center mt-4">

                                    <a type="button" href="">

                                    </a>
                                    <button id="btnAsignar" type="submit" class="btn btn-primary btn-block"
                                        style="background: #2b92a4 !important; border: none; font-weight: bold !important">
                                        ACTUALIZAR INFORMACIÓN
                                    </button>

                                    <script>
                                        document.getElementById('formularioEstudiante').addEventListener('submit', function(e) {
                                            e.preventDefault(); // ⛔ detenemos el envío

                                            Swal.fire({
                                                title: 'Actualizar Información',
                                                text: 'Estás a punto de actualizar la información del estudiante. ¿Deseas continuar?',
                                                icon: 'info',

                                                confirmButtonText: 'Sí, actualizar',
                                                showCancelButton: true,
                                                cancelButtonText: 'No, cancelar',
                                                confirmButtonColor: '#2b92a4',

                                                cancelButtonColor: '#6b6762'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    this.submit(); // ✅ ahora sí enviamos
                                                }
                                            });
                                        });
                                    </script>


                                </div>

                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')

    <script>
        const regionEstudiante = @json($estudiante->persona?->direccion?->idRegion);
        const provinciaEstudiante = @json($estudiante->persona?->direccion?->idProvincia);
        const distritoEstudiante = @json($estudiante->persona?->direccion?->idDistrito);
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            cargarRegiones();

            document.getElementById('region').addEventListener('change', cargarProvincias);
            document.getElementById('provincia').addEventListener('change', cargarDistritos);
        });

        function cargarRegiones() {
            fetch('/regiones')
                .then(r => r.json())
                .then(data => {
                    const region = document.getElementById('region');
                    region.innerHTML = '<option value="">Seleccionar Región</option>';

                    data.forEach(r => {
                        const selected = r.idRegion == regionEstudiante ? 'selected' : '';
                        region.innerHTML += `
                    <option value="${r.idRegion}" ${selected}>
                        ${r.nombre}
                    </option>`;
                    });
                });
        }


        function cargarProvincias() {
            const idRegion = document.getElementById('region').value;
            const provincia = document.getElementById('provincia');
            const distrito = document.getElementById('distrito');

            provincia.disabled = true;
            distrito.disabled = true;

            if (!idRegion) return;

            fetch(`/provincias/${idRegion}`)
                .then(r => r.json())
                .then(data => {

                    provincia.innerHTML = '<option value="">Seleccionar Provincia</option>';
                    data.forEach(p => {
                        const selected = p.idProvincia == provinciaEstudiante ? 'selected' : '';
                        provincia.innerHTML += `
                    <option value="${p.idProvincia}" ${selected}>
                        ${p.nombre}
                    </option>`;
                    });
                    provincia.disabled = false;
                });
        }

        function cargarDistritos() {
            const idProvincia = document.getElementById('provincia').value;
            const distrito = document.getElementById('distrito');

            distrito.disabled = true;

            if (!idProvincia) return;

            fetch(`/distritos/${idProvincia}`)
                .then(r => r.json())
                .then(data => {
                    distrito.innerHTML = '<option value="">Seleccionar Distrito</option>';
                    data.forEach(d => {
                        const selected = d.idDistrito == distritoEstudiante ? 'selected' : '';
                        distrito.innerHTML +=
                            `<option value="${d.idDistrito}" ${selected}>${d.nombre}</option>`;
                    });
                    distrito.disabled = false;
                });
        }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const inputs = {
                apellidoPaterno: document.getElementById('apellidoPaterno'),
                apellidoMaterno: document.getElementById('apellidoMaterno'),
                nombres: document.getElementById('nombres'),
                genero: document.getElementById('genero'),
                dni: document.getElementById('dni'),
                fechaNacimiento: document.getElementById('fecha_nacimiento'),
                fechaContratacion: document.getElementById('fecha_contratacion'),
                telefono: document.getElementById('telefono'),
                direccion: document.getElementById('direccion'),
                correo: document.getElementById('email'),
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
                //removemos la etiqueta de INVÁLIDO y anadimos la de VÁLIDO
                input.classList.remove('is-invalid');
                input.classList.add('is-valid');
                const feedback = input.parentElement.querySelector('.invalid-feedback');
                if (feedback) feedback.remove();
            }

            inputs.apellidoPaterno.addEventListener('input', function() {
                if (this.value.length < 2 || this.value.length > 100) {
                    setInvalid(this, 'Debe tener entre 2 y 100 caracteres.');
                } else {
                    clearInvalid(this);
                }
            });

            inputs.apellidoMaterno.addEventListener('input', function() {
                if (this.value.length < 2 || this.value.length > 100) {
                    setInvalid(this, 'Debe tener entre 2 y 100 caracteres.');
                } else {
                    clearInvalid(this);
                }
            });

            inputs.nombres.addEventListener('input', function() {
                // Elimina espacios múltiples y los del inicio/final
                let valor = this.value.replace(/\s+/g, ' ').trimStart();

                // Si el primer carácter es espacio, lo borra automáticamente del input
                if (this.value[0] === ' ') {
                    this.value = this.value.trimStart(); // actualiza el input eliminando espacios al inicio
                }

                // Actualiza la variable 'valor' con lo que queda en el campo
                valor = this.value.replace(/\s+/g, ' ').trim();

                // Expresión: solo letras y espacios permitidos
                const soloLetras = /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/;

                if (valor.length < 2 || valor.length > 100) {
                    setInvalid(this, 'Debe tener entre 2 y 100 caracteres.');
                } else if (!soloLetras.test(valor)) {
                    setInvalid(this, 'Solo se permiten letras y espacios.');
                } else {
                    clearInvalid(this);
                }
            });


            inputs.direccion.addEventListener('input', function() {
                if (this.value.length < 2 || this.value.length > 100) {
                    setInvalid(this, 'Debe tener entre 2 y 100 caracteres.');
                } else {
                    clearInvalid(this);
                }
            });

            inputs.dni.addEventListener('input', function() {
                const regex = /^\d{8}$/;
                if (!regex.test(this.value)) {
                    setInvalid(this, 'El N.° del DNI debe contener exactamente 8 dígitos.');
                } else {
                    clearInvalid(this);
                }
            });

            inputs.genero.addEventListener('change', function() {
                if (!this.value) {
                    setInvalid(this, 'Seleccione una opción.');
                } else {
                    clearInvalid(this);
                }
            });

            inputs.telefono.addEventListener('input', function() {
                // Formatear en bloques de 3 dígitos
                let rawValue = this.value.replace(/\D/g, '').slice(0, 9); // Solo números, máximo 9 dígitos
                let formatted = rawValue.match(/.{1,3}/g);
                this.value = formatted ? formatted.join(' ') : '';

                // Validar que haya exactamente 9 dígitos (sin contar espacios)
                const digitsOnly = this.value.replace(/\s/g, '');
                const regex = /^\d{9}$/;
                if (!regex.test(digitsOnly)) {
                    setInvalid(this, 'El N.° de teléfono debe contener exactamente 9 dígitos.');
                } else {
                    clearInvalid(this);
                    input - group - text.classList.add('is-valid');
                }
            });


            inputs.correo.addEventListener('input', function() {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(this.value)) {
                    setInvalid(this, 'Por favor, ingrese un correo electrónico válido.');
                } else {
                    clearInvalid(this);
                }
            });
        }); <
        /scr>
    @endsection
