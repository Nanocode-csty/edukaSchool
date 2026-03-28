<div class="card" style="border: none">
    <div class="card-header-custom">
        <i class="icon-user-following mr-2"></i>
        Datos Personales
    </div>

    <div class="card-body"
        style="border: 2px solid #86D2E3; border-top: none; border-radius: 0px 0px 6px 6px !important;">


        <x-form.field name="apellidoPaterno" label="Apellido Paterno" />
        <x-form.field name="apellidoMaterno" label="Apellido Materno" />
        <x-form.field name="nombres" label="Nombres" />
        <x-form.field name="genero" label="Sexo" type="select" :options="[
            'M' => 'Masculino',
            'F' => 'Femenino',
        ]" />

        <x-form.field name="dni" label="N.° DNI" maxlength="8" inputmode="numeric">

            <div id="dni-error" class="invalid-feedback">
            </div>

        </x-form.field>

        <div class="row mb-3 align-items-center">
            <label class="col-12 col-md-2 col-form-label">Fecha de Nacimiento </label>
            <div class="col-12 col-md-10">
                <input type="date" class="form-control custom-gold @error('fecha_nacimiento') is-invalid @enderror"
                    id="fecha_nacimiento" name="fecha_nacimiento" placeholder="YYYY-MM-DD"
                    value="{{ old('fecha_nacimiento') }}">
                @error('fecha_nacimiento')
                    <div class="invalid-feedback d-block text-start">
                        {{ $message }}
                    </div>
                @enderror
            </div>

        </div>

        <style>
            /* Borde dorado personalizado */
            .form-control.custom-gold {
                background-color: white !important;
                color: black;
            }
        </style>

        <!-- Flatpickr JS y Español -->
        <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>

        <script>
            flatpickr("#fecha_nacimiento", {
                dateFormat: "Y-m-d",
                maxDate: "today",
                locale: "es",
                disableMobile: true,

                onChange: function(selectedDates, dateStr, instance) {
                    const input = document.getElementById('fecha_nacimiento');
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

<!--PARA NO PERMITIR CARACTERES NI ESPACIOS-->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const apPaterno = document.getElementById('apellidoPaterno');
        const apMaterno = document.getElementById('apellidoMaterno');

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

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const inputs = {
            apellidoPaterno: document.getElementById('apellidoPaterno'),
            apellidoMaterno: document.getElementById('apellidoMaterno'),
            nombres: document.getElementById('nombres'),
            genero: document.getElementById('genero'),
            fechaNacimiento: document.getElementById('fecha_nacimiento'),
            fechaContratacion: document.getElementById('fecha_contratacion'),
        };

        function setInvalid(input, message) {

            input.classList.remove('is-valid');
            input.classList.add('is-invalid');

            let feedback = input.parentElement.querySelector('.invalid-feedback');

            if (!feedback) {
                feedback = document.createElement('div');
                feedback.className = 'invalid-feedback';
                input.parentElement.appendChild(feedback);
            }

            feedback.textContent = message;

            feedback.classList.remove('d-none');
            feedback.classList.add('d-block');

        }

        function clearInvalid(input) {

            input.classList.remove('is-invalid');
            input.classList.add('is-valid');

            const feedback = input.parentElement.querySelector('.invalid-feedback');

            if (feedback) {
                feedback.classList.remove('d-block');
                feedback.classList.add('d-none');
            }

        }

        inputs.apellidoPaterno.addEventListener('input', function() {
            const regex = /^[A-Za-zÁÉÍÓÚáéíóúÑñ]+$/;

            if (this.value.length < 2 || this.value.length > 100) {
                setInvalid(this, 'Debe tener entre 2 y 100 caracteres.');
            } else if (!regex.test(this.value)) {
                setInvalid(this, 'Solo se permiten letras.');
            } else {
                clearInvalid(this);
            }
        });

        inputs.apellidoMaterno.addEventListener('input', function() {
            const regex = /^[A-Za-zÁÉÍÓÚáéíóúÑñ]+$/;

            if (this.value.length < 2 || this.value.length > 100) {
                setInvalid(this, 'Debe tener entre 2 y 100 caracteres.');
            } else if (!regex.test(this.value)) {
                setInvalid(this, 'Solo se permiten letras.');
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

        inputs.genero.addEventListener('change', function() {
            if (!this.value) {
                setInvalid(this, 'Seleccione una opción.');
            } else {
                clearInvalid(this);
            }
        });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        const dniInput = document.getElementById('dni');
        const dniError = document.getElementById('dni-error');
        let timeout = null;

        function clearInvalid(input) {

            input.classList.remove('is-invalid');
            input.classList.add('is-valid');

            const feedback = input.parentElement.querySelector('.invalid-feedback');

            if (feedback) {
                feedback.classList.remove('d-block');
                feedback.classList.add('d-none');
            }

        }

        function setInvalid(message) {
            dniInput.classList.remove('is-valid');
            dniInput.classList.add('is-invalid');
            dniError.textContent = message;
        }

        function setValid() {
            dniInput.classList.remove('is-invalid');
            dniInput.classList.add('is-valid');
            dniError.textContent = '';
        }

        dniInput.addEventListener('input', function() {

            // solo números
            this.value = this.value.replace(/\D/g, '');

            const dni = this.value;

            clearTimeout(timeout);
            clearInvalid(this);
            if (dni.length < 8) {

                setInvalid('El N.° del DNI debe contener exactamente 8 dígitos.');
                return;
            } else {
                clearInvalid(this);
            }

            if (dni.length === 8) {

                timeout = setTimeout(function() {

                    $.ajax({
                        url: "{{ route('verificar.dni.representante') }}",
                        method: "GET",
                        data: {
                            dni: dni
                        },

                        success: function(response) {

                            if (response.existe) {
                                setInvalid('Este DNI ya está registrado.');
                            } else {
                                setValid();
                            }

                        },

                        error: function() {
                            console.error("Error verificando DNI");
                        }

                    });

                }, 400);

            }

        });

    });
</script>
