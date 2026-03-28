        <div class="margen-movil-2">
        <div class="card" style="border: none">
            <div class="card-header-custom">
                <i class="icon-graduation mr-2"></i>
                Datos Personales
            </div>

            <div class="card-body"
                style="border: 2px solid #86D2E3; border-top: none; border-radius: 0 0 6px 6px !important;">

                {{-- Apellidos --}}
                <div class="row mb-3 align-items-center">
                    <label class="col-12 col-md-2 col-form-label">Apellido paterno <span
                            style="color:#FF5A6A;">(*)</span></label>
                    <div class="col-12 col-md-4">
                        <input type="text"
                            class="form-control @error('apellidoPaternoEstudiante') is-invalid @enderror"
                            id="apellidoPaternoEstudiante" name="apellidoPaternoEstudiante"
                            placeholder="Ingrese el apellido paterno" maxlength="100"
                            value="{{ old('apellidoPaternoEstudiante') }}">
                        @error('apellidoPaternoEstudiante')
                            <span class="invalid-feedback d-block text-start">{{ $message }}</span>
                        @enderror
                    </div>

                    <label class="col-12 col-md-2 col-form-label">Apellido materno <span
                            style="color:#FF5A6A;">(*)</span></label>
                    <div class="col-12 col-md-4">
                        <input type="text"
                            class="form-control @error('apellidoMaternoEstudiante') is-invalid @enderror"
                            id="apellidoMaternoEstudiante" name="apellidoMaternoEstudiante"
                            placeholder="Ingrese el apellido materno" maxlength="100"
                            value="{{ old('apellidoMaternoEstudiante') }}">
                        @error('apellidoMaternoEstudiante')
                            <span class="invalid-feedback d-block text-start">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Nombres --}}
                <div class="row  mb-3 align-items-center">
                    <label class="col-12 col-md-2 col-form-label">Nombres <span
                            style="color:#FF5A6A;">(*)</span></label>
                    <div class="col-12 col-md-4">
                        <input type="text" class="form-control @error('nombreEstudiante') is-invalid @enderror"
                            id="nombreEstudiante" name="nombreEstudiante" placeholder="Ingrese los nombres completos"
                            maxlength="100" value="{{ old('nombreEstudiante') }}">
                        @error('nombreEstudiante')
                            <span class="invalid-feedback d-block text-start">{{ $message }}</span>
                        @enderror
                    </div>
                    <label class="col-12 col-md-2 col-form-label">Sexo <span style="color:#FF5A6A;">(*)</span></label>
                    <div class="col-12 col-md-4">
                        <select class="form-control @error('generoEstudiante') is-invalid @enderror"
                            id="generoEstudiante" name="generoEstudiante">
                            <option value="" disabled {{ old('generoEstudiante') == '' ? 'selected' : '' }}>
                                Seleccione el sexo</option>
                            <option value="M" {{ old('generoEstudiante') == 'M' ? 'selected' : '' }}>Masculino
                            </option>
                            <option value="F" {{ old('generoEstudiante') == 'F' ? 'selected' : '' }}>Femenino
                            </option>
                        </select>
                        @error('generoEstudiante')
                            <div class="invalid-feedback d-block text-start">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Género y DNI --}}
                <div class="row  mb-3 align-items-center">

                    <label class="col-12 col-md-2 col-form-label">N.° DNI <span
                            style="color:#FF5A6A;">(*)</span></label>
                    <div class="col-12 col-md-4">
                        <input type="text" class="form-control @error('dniEstudiante') is-invalid @enderror"
                            id="dniEstudiante" name="dniEstudiante" maxlength="8" placeholder="Ingerese N.° DNI"
                            value="{{ old('dniEstudiante') }}" inputmode="numeric">
                        @error('dniEstudiante')
                            <div class="invalid-feedback d-block text-start">{{ $message }}</div>
                        @enderror
                        <div id="dni-error" class="mt-1 d-none" style="font-size: smaller; color: #dc3545;">
                            El N.° de DNI ingresado ya se encuentra registrado en el sistema.
                        </div>
                    </div>

                    <label class="col-12 col-md-2 col-form-label">Fecha de Nacimiento <span
                            style="color:#FF5A6A;">(*)</span></label>
                    <div class="col-12 col-md-4">
                        <input type="text"
                            class="form-control @error('fechaNacimientoEstudiante') is-invalid @enderror"
                            id="fechaNacimientoEstudiante" name="fechaNacimientoEstudiante" placeholder="YYYY-MM-DD"
                            value="{{ old('fechaNacimientoEstudiante') }}"
                            style="background-color: white !important; color:black !important">
                        @error('fechaNacimientoEstudiante')
                            <div class="invalid-feedback d-block text-start feedback-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>


                {{-- Observaciones --}}
                <div class="row  mb-3 align-items-center">
                    <label class="col-12 col-md-2 col-form-label">Observaciones</label>
                    <div class="col-12 col-md-10">
                        <input type="text" class="form-control" id="referencia_" name="referencia_"
                            placeholder="Agregue observaciones sobre el estudiante" maxlength="100">
                    </div>
                </div>

            </div>
        </div>

        {{-- Sección foto --}}
        <div class="card" style="border: none">
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
                            <input type="file" name="foto" id="foto" accept="image/*" hidden
                                onchange="previewImage(event)">

                            <div class="dropzone-content text-center">
                                <i class="bi bi-cloud-arrow-up-fill dropzone-icon"></i>
                                <p class="mb-1 fw-semibold">Arrastra y suelta tu imagen aquí</p>
                                <small class="text-muted">o haz clic para seleccionar</small>
                                <div class="text-muted mt-1" style="font-size: 12px;">
                                    SVG, PNG o JPG <span style="color:#FF5A6A">(Tamaño Carné)</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PREVIEW -->
                    <div class="col-12 col-md-4 text-center d-flex justify-content-center">
                        <img id="img-preview" src="{{ asset('imagenes/imgEstudiante.png') }}" alt="Vista previa"
                            class="img-fluid rounded shadow-sm "
                            style="max-height: 190px; border: 1px solid #e0e0e0; padding: 6px; border-radius: 12px !important;">
                    </div>
                </div>

            </div>
        </div>

    </div>

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
                    preview.src = "{{ asset('imagenes/imgEstudiante.png') }}";
                    return;
                }

                //Si no error de tipo de imagen, mostrarla en vista previa
                const reader = new FileReader();  //nuevo objeto FileReader (lector de archivos)
                reader.onload = function(e) { //cuando se carga el archivo, se ejecuta esta función
                    preview.src = e.target.result; //se establece la fuente de la imagen de vista previa
                };
                reader.readAsDataURL(file); //leer el archivo como una URL de datos
            }

        }
    </script>

    {{-- Scripts --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>

    <script>
        // Verificación DNI
        $('#dniEstudiante').on('input', function() {
            const dni = $(this).val();
            if (dni.length === 8) {
                $.get("{{ route('verificar.dni') }}", {
                    dni
                }, function(response) {
                    if (response.existe) {
                        $('#dni-error').removeClass('d-none');
                        $('#dniEstudiante').addClass('is-invalid');
                    } else {
                        $('#dni-error').addClass('d-none');
                        $('#dniEstudiante').removeClass('is-invalid');
                    }
                });
            } else {
                $('#dni-error').addClass('d-none');
                $('#dniEstudiante').removeClass('is-invalid');
            }
        });

        // Flatpickr
        flatpickr("#fechaNacimientoEstudiante", {
            dateFormat: "Y-m-d",
            maxDate: "today",
            locale: "es",
            disableMobile: true,

            onChange: (selectedDates, dateStr, instance) => {
                const input = instance.input;
                const feedback = input.parentElement.querySelector('.feedback-message');
                if (dateStr) {
                    input.classList.remove('is-invalid');
                    if (feedback) feedback.remove();
                    input.classList.add('is-valid');
                }
            }
        });
    </script>
