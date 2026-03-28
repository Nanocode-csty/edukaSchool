<style>
    .estilo-info {
        margin-bottom: 0px;
        font-family: "Quicksand", sans-serif;
        font-weight: 700;
        font-size: clamp(0.9rem, 2.0vw, 0.9rem) !important;

    }
</style>

<div class="container-fluid estilo-info" id="contenidoEstudiante">
    <div class="row mt-4 ml-1 mr-1">
        <div class="col-12 mb-3">
            <div class="box_block">
                <button style="background: #0A8CB3 !important"
                    class="btn btn-primary btn-block text-left rounded-0 btn_header header_6 estilo-info" type="button"
                    data-toggle="collapse" data-target="#collapseEstudianteNuevo" aria-expanded="true"
                    aria-controls="collapseEstudianteNuevo">
                    <i class="fas fa-file-signature"></i>&nbsp;Ficha del estudiante
                    <div class="float-right"><i class="fas fa-chevron-down"></i></div>
                </button>
            </div>
            <div class="collapse show" id="collapseEstudianteNuevo">
                <div class="card card-body rounded-0 border-0 pt-0"
                    style="padding-left:0.966666666rem;padding-right:0.9033333333333333rem;">
                    <div class="row" style="padding:20px;">
                        <div class="col-12">
                            <div class="card" style="border: none">
                                <div
                                    style="background: #E0F7FA; color: #0A8CB3; font-weight: bold; border: 2px solid #86D2E3; border-bottom: 2px solid #86D2E3; padding: 6px 20px; border-radius:4px 4px 0px 0px;">
                                    <i class="icon-graduation mr-2"></i>
                                    Datos Personales
                                </div>

                                <div class="card-body"
                                    style="border: 2px solid #86D2E3; border-top: none; border-radius: 0px 0px 4px 4px !important;">
                                    <form id="formularioEstudiante" method="POST"
                                        action="{{ route('estudiantes.store') }}" autocomplete="off"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="row form-group">
                                            <label class="col-md-2 col-form-label">Apellido Paterno <span
                                                    style="color: #FF5A6A">(*)</span></label>
                                            <div class="col-md-4">
                                                <input type="text"
                                                    class="form-control @error('apellidoPaternoEstudiante') is-invalid @enderror"
                                                    id="apellidoPaternoEstudiante" name="apellidoPaternoEstudiante"
                                                    placeholder="Apellido paterno" maxlength="100"
                                                    value="{{ old('apellidoPaternoEstudiante') }}">
                                                @if ($errors->has('apellidoPaternoEstudiante'))
                                                    <span
                                                        class="invalid-feedback d-block text-start">{{ $errors->first('apellidoPaternoEstudiante') }}</span>
                                                @endif
                                            </div>
                                            <label class="col-md-2 col-form-label">Apellido Materno <span
                                                    style="color: #FF5A6A">(*)</span></label>
                                            <div class="col-md-4">
                                                <input type="text"
                                                    class="form-control @error('apellidoMaternoEstudiante') is-invalid @enderror"
                                                    id="apellidoMaternoEstudiante" name="apellidoMaternoEstudiante"
                                                    placeholder="Apellido materno" maxlength="100"
                                                    value="{{ old('apellidoMaternoEstudiante') }}">
                                                @if ($errors->has('apellidoMaternoEstudiante'))
                                                    <span
                                                        class="invalid-feedback d-block text-start">{{ $errors->first('apellidoMaternoEstudiante') }}</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="row form-group">
                                            <label class="col-md-2 col-form-label">
                                                Nombres <span style="color: #FF5A6A">(*)</span>
                                            </label>
                                            <div class="col-md-10">
                                                <input type="text"
                                                    class="form-control @error('nombreEstudiante') is-invalid @enderror"
                                                    id="nombreEstudiante" name="nombreEstudiante" placeholder="Nombres"
                                                    maxlength="100" value="{{ old('nombreEstudiante') }}">
                                                @if ($errors->has('nombreEstudiante'))
                                                    <span
                                                        class="invalid-feedback d-block text-start">{{ $errors->first('nombreEstudiante') }}</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="row form-group">
                                            <label class="col-md-2 col-form-label">Sexo <span
                                                    style="color: #FF5A6A">(*)</span></label>
                                            <div class="col-md-4">
                                                <select
                                                    class="form-control @error('generoEstudiante') is-invalid @enderror"
                                                    id="generoEstudiante" name="generoEstudiante">
                                                    <option value="" disabled
                                                        {{ old('generoEstudiante') == '' ? 'selected' : '' }}>
                                                        Seleccionar sexo</option>
                                                    <option value="M"
                                                        {{ old('generoEstudiante') == 'M' ? 'selected' : '' }}>Masculino
                                                    </option>
                                                    <option value="F"
                                                        {{ old('generoEstudiante') == 'F' ? 'selected' : '' }}>Femenino
                                                    </option>
                                                </select>
                                                @error('generoEstudiante')
                                                    <div class="invalid-feedback d-block text-start">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>

                                            <label class="col-md-2 col-form-label">N.° DNI <span
                                                    style="color: #FF5A6A">(*)</span></label>
                                            <div class="col-md-4">
                                                <input type="text"
                                                    class="form-control @error('dniEstudiante') is-invalid @enderror"
                                                    id="dniEstudiante" name="dniEstudiante" maxlength="8"
                                                    placeholder="N.° DNI" value="{{ old('dniEstudiante') }}">
                                                @error('dniEstudiante')
                                                    <div class="invalid-feedback d-block text-start">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                                <div id="dni-error" class="mt-1 text-danger d-none"
                                                    style="font-size:smaller; color:#DE2246 !important">Ya existe un
                                                    estudiante registrado con este N.° de DNI.</div>
                                            </div>
                                        </div>

                                        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                                        <script>
                                            $('#dniEstudiante').on('input', function() {
                                                const dni = $(this).val();

                                                if (dni.length === 8) {
                                                    $.ajax({
                                                        url: '{{ route('verificar.dni') }}',
                                                        method: 'GET',
                                                        data: {
                                                            dni
                                                        },
                                                        success: function(response) {
                                                            //validar si existe el estudiante
                                                            if (response.existe) {
                                                                //si no existe, quita los errores
                                                                $('#dni-error').removeClass('d-none');
                                                                $('#dniEstudiante').addClass('is-invalid');
                                                                //$('#btnAsignar').prop('disabled', true);
                                                            } else {
                                                                //muestra el error
                                                                $('#dni-error').addClass('d-none');
                                                                $('#dniEstudiante').removeClass('is-invalid');
                                                                //$('#btnAsignar').prop('disabled', false);
                                                            }
                                                        }
                                                    });
                                                } else {
                                                    //añade el (debe ser de 8 digitos)
                                                    $('#dni-error').addClass('d-none');
                                                    $('#dniEstudiante').removeClass('is-invalid');
                                                    //$('#btnAsignar').prop('disabled', true);
                                                }
                                            });
                                        </script>

                                        <!-- Flatpickr CSS -->
                                        <link rel="stylesheet"
                                            href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

                                        <div class="row form-group">
                                            <label class="col-md-2 col-form-label">Fecha de Nacimiento <span
                                                    style="color: #FF5A6A">(*)</span></label>
                                            <div class="col-md-10">
                                                <input type="text"
                                                    class="form-control custom-gold @error('fechaNacimientoEstudiante') is-invalid @enderror"
                                                    id="fechaNacimientoEstudiante" name="fechaNacimientoEstudiante"
                                                    placeholder="YYYY-MM-DD"
                                                    value="{{ old('fechaNacimientoEstudiante') }}">
                                                @if ($errors->has('fechaNacimientoEstudiante'))
                                                    <div class="invalid-feedback d-block text-start feedback-message">
                                                        {{ $errors->first('fechaNacimientoEstudiante') }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="row form-group">
                                            <label class="col-md-2 col-form-label">
                                                Observaciones
                                            </label>
                                            <div class="col-md-10">
                                                <input type="text" class="form-control" id="referencia_"
                                                    name="referencia_" placeholder="Observaciones sobre el estudiante"
                                                    value="" maxlength="100">
                                            </div>
                                        </div>

                                        <style>
                                            /* Borde dorado personalizado */
                                            .form-control.custom-gold {
                                                border: 1.5px solid #DAA520 !important;
                                                background-color: white !important;
                                                color: black;
                                            }

                                            .form-control {
                                                border: 1px solid #DAA520;

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
                                                        input.classList.add('is-valid');
                                                    }
                                                }
                                            });
                                        </script>

                                </div>
                            </div>

                            <div class="card mt-4" style="border: none">
                                <div
                                    style="background: #E0F7FA; color: #0A8CB3; font-weight: bold; border: 2px solid #86D2E3; border-bottom: 2px solid #86D2E3; padding: 6px 20px; border-radius:4px 4px 0px 0px;">
                                    <i class="icon-camera mr-2"></i>
                                    Foto de Identificación
                                </div>
                                <div class="card-body"
                                    style="border: 2px solid #86D2E3; border-top: none; border-radius: 0px 0px 4px 4px !important;">
                                    <div class="row form-group align-items-center">
                                        <label class="col-md-2 col-form-label">
                                            Foto del estudiante
                                        </label>

                                        <div class="col-md-10" id="seleccionar">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" name="foto"
                                                    id="foto" accept="image/*" onchange="previewImage(event)">
                                                <label class="custom-file-label" for="foto" id="foto-label"
                                                    style="border-color: #DAA520;">Seleccionar o arrastrar
                                                    imagen</label>
                                                <span style="color:#FF5A6A; font-size:small">Tamaño carné y/o acorde a
                                                    los requisitos solicitados por la institución.</span>

                                            </div>
                                        </div>

                                        <div class="col-md-3 text-center">
                                            <img id="img-preview" src="#" alt="Vista previa"
                                                class="img-fluid rounded d-none mt-2"
                                                style="min-height: 160px; max-height: 160px; border: 1px solid #DAA520; padding: 4px;">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <style>
                                .custom-file-label::after {
                                    content: "Seleccionar" !important;
                                    background-color: #DAA520;
                                    color: white;
                                    border-left: none;
                                }
                            </style>

                            <script>
                                function previewImage(event) {
                                    const input = event.target;
                                    const label = document.getElementById('foto-label');
                                    const preview = document.getElementById('img-preview');
                                    const mostrar = document.getElementById('seleccionar');

                                    if (input.files && input.files[0]) {
                                        const file = input.files[0];

                                        // Validación estricta del tipo MIME
                                        if (!file.type.startsWith('image/')) {
                                            alert('Por favor, seleccione solo archivos de imagen.');
                                            input.value = ''; // Limpia el campo
                                            label.textContent = 'Seleccionar imagen';
                                            preview.classList.add('d-none');
                                            preview.src = '#';
                                            return;
                                        }

                                        label.textContent = file.name;

                                        // Mostrar imagen en vista previa
                                        const reader = new FileReader();
                                        reader.onload = function(e) {
                                            preview.src = e.target.result;
                                            preview.classList.remove('d-none');
                                        };
                                        reader.readAsDataURL(file);
                                    }
                                    //son para el campo de buscar, pasa de 10 a 7 columnas
                                    mostrar.classList.remove('col-md-10');
                                    mostrar.classList.add('col-md-7');
                                }
                            </script>

                            <div class="card mt-4" style="border: none">
                                <div
                                    style="background: #E0F7FA; color: #0A8CB3; font-weight: bold; border: 2px solid #86D2E3; border-bottom: 2px solid #86D2E3; padding: 6px 20px; border-radius:4px 4px 0px 0px;">
                                    <i class="icon-location-pin mr-2"></i>
                                    Información de Residencia
                                </div>
                                <div class="card-body"
                                    style="border: 2px solid #86D2E3; border-top: none; border-radius: 0px 0px 4px 4px !important;">
                                    <div class="row form-group">
                                        <label class="col-md-2 col-form-label">
                                            Región <span style="color: #FF5A6A">(*)</span>
                                        </label>
                                        <div class="col-md-10">
                                            <select id="regionEstudiante" name="regionEstudiante"
                                                class="form-control @error('regionEstudiante') is-invalid @enderror">
                                                <option value="" disabled
                                                    {{ old('regionEstudiante') == '' ? 'selected' : '' }}>Seleccionar
                                                    Región</option>
                                            </select>
                                            @error('regionEstudiante')
                                                <div class="invalid-feedback d-block text-start">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <label class="col-md-2 col-form-label">
                                            Provincia <span style="color: #FF5A6A">(*)</span>
                                        </label>
                                        <div class="col-md-4">
                                            <select id="provinciaEstudiante" name="provinciaEstudiante"
                                                class="form-control @error('provinciaEstudiante') is-invalid @enderror"
                                                disabled>
                                                <option value="" disabled
                                                    {{ old('provinciaEstudiante') == '' ? 'selected' : '' }}>
                                                    Seleccionar Provincia</option>
                                            </select>
                                            @error('provinciaEstudiante')
                                                <div class="invalid-feedback d-block text-start">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <label class="col-md-2 col-form-label">
                                            Distrito <span style="color: #FF5A6A">(*)</span>
                                        </label>
                                        <div class="col-md-4">
                                            <select id="distritoEstudiante" name="distritoEstudiante"
                                                class="form-control @error('distritoEstudiante') is-invalid @enderror"
                                                disabled>
                                                <option value="" disabled
                                                    {{ old('distritoEstudiante') == '' ? 'selected' : '' }}>Seleccionar
                                                    Distrito</option>
                                            </select>
                                            @error('distritoEstudiante')
                                                <div class="invalid-feedback d-block text-start">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <label class="col-md-2 col-form-label">Avenida o calle <span
                                                style="color: #FF5A6A">(*)</span></label>
                                        <div class="col-md-4">
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
                                        <label class="col-md-2 col-form-label">Número</label>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" id="numeroEstudiante"
                                                name="numeroEstudiante" placeholder="N.°  de Avenida o Calle"
                                                maxlength="5" value="{{ old('numeroEstudiante') }}">
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <label class="col-md-2 col-form-label">Urbanización</label>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" id="urbanizacionEstudiante"
                                                name="urbanizacionEstudiante" placeholder="Urbanización"
                                                maxlength="20" value="{{ old('urbanizacionEstudiante') }}">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="col-md-2 col-form-label">Referencia</label>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" id="referenciaEstudiante"
                                                name="referenciaEstudiante" placeholder="Referencia" maxlength="20"
                                                value="{{ old('referenciaEstudiante') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mt-4 " style="border: none">
                                <div
                                    style="background: #E0F7FA; color: #0A8CB3; font-weight: bold; border: 2px solid #86D2E3; border-bottom: 2px solid #86D2E3; padding: 6px 20px; border-radius:4px 4px 0px 0px;">
                                    <i class="icon-phone mr-2"></i>
                                    Información de Contacto
                                </div>
                                <div class="card-body"
                                    style="border: 2px solid #86D2E3; border-top: none; border-radius: 0px 0px 4px 4px !important;">

                                    <div class="row form-group">
                                        <label class="col-md-2 col-form-label">
                                            Celular actual <span style="color: #FF5A6A">(*)</span>
                                        </label>
                                        <div class="col-md-10">
                                            <div class="input-group">
                                                <input type="text"
                                                    class="form-control @error('numeroCelularEstudiante') is-invalid @enderror"
                                                    id="numeroCelularEstudiante" name="numeroCelularEstudiante"
                                                    placeholder="N.° celular"
                                                    value="{{ old('numeroCelularEstudiante') }}" inputmode="numeric">
                                                <div class="input-group-append">
                                                    <span class="input-group-text"
                                                        style="border-color: #DAA520 !important;"><i
                                                            class="fas fa-phone"></i></span>
                                                </div>
                                                @error('numeroCelularEstudiante')
                                                    <div class="invalid-feedback d-block text-start">{{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!--PARA ENVIAR SOLO 9 DIGITOS (SIN SUS ESPACIOS)-->
                                    <script>
                                        document.getElementById('formularioEstudiante').addEventListener('submit', function() {
                                            const celularInput = document.getElementById('numeroCelularEstudiante');
                                            celularInput.value = celularInput.value.replace(/\s+/g, '');
                                        });
                                    </script>
                                    <!--PARA NO PERMITIR CARACTERES NI ESPACIOS-->
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            const dniInput = document.getElementById('dniEstudiante');
                                            const correoInput = document.getElementById('correoEstudiante');
                                            const apPaterno = document.getElementById('apellidoPaternoEstudiante');
                                            const apMaterno = document.getElementById('apellidoMaternoEstudiante');
                                            const celInput = document.getElementById('numeroCelularEstudiante');

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

                                    <div class="row form-group">
                                        <label class="col-md-2 col-form-label">
                                            Correo electrónico <span style="color: #FF5A6A">(*)</span>
                                        </label>
                                        <div class="col-md-10">
                                            <div class="input-group">
                                                <input type="text"
                                                    class="form-control @error('correoEstudiante') is-invalid @enderror"
                                                    id="correoEstudiante" name="correoEstudiante"
                                                    placeholder="correo@estudiante.com" maxlength="100"
                                                    value="{{ old('correoEstudiante') }}">
                                                <div class="input-group-append">
                                                    <span
                                                        class="input-group-text "style="border-color: #DAA520 !important;"><i
                                                            class="fas fa-envelope"></i></span>
                                                </div>
                                                @error('correoEstudiante')
                                                    <div class="invalid-feedback d-block text-start">{{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>

                            <div class="d-flex justify-content-center mt-4">
                                <button id="btnAsignar" type="submit" class="btn btn-primary btn-block"
                                    style="background: #FF3F3F !important; border: none; font: bold !important">
                                    <span style="font:bold">Asignar Representante Legal al estudiante</span>
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

<!--SCRIPTS------------------------------------------------------------------------------->
<script>
    const oldRegion = "{{ old('regionEstudiante') }}";
    const oldProvincia = "{{ old('provinciaEstudiante') }}";
    const oldDistrito = "{{ old('distritoEstudiante') }}";
</script>
<script src="{{ asset('js/estudiante.js') }}"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const inputs = {
            apellidoPaterno: document.getElementById('apellidoPaternoEstudiante'),
            apellidoMaterno: document.getElementById('apellidoMaternoEstudiante'),
            nombres: document.getElementById('nombreEstudiante'),
            genero: document.getElementById('generoEstudiante'),
            dni: document.getElementById('dniEstudiante'),
            fechaNacimiento: document.getElementById('fechaNacimientoEstudiante'),
            telefono: document.getElementById('numeroCelularEstudiante'),
            region: document.getElementById('regionEstudiante'),
            provincia: document.getElementById('provinciaEstudiante'),
            distrito: document.getElementById('distritoEstudiante'),
            avenida: document.getElementById('calleEstudiante'),
            correo: document.getElementById('correoEstudiante'),
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


        inputs.avenida.addEventListener('input', function() {
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

        inputs.region.addEventListener('change', function() {
            if (!this.value) {
                setInvalid(this, 'Seleccione una opción.');
            } else {
                clearInvalid(this);
            }
        });

        inputs.provincia.addEventListener('change', function() {
            if (!this.value) {
                setInvalid(this, 'Seleccione una opción.');
            } else {
                clearInvalid(this);
            }
        });

        inputs.distrito.addEventListener('change', function() {
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
    });
</script>
