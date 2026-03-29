<?php $__env->startSection('titulo', 'Registrar Estudiante'); ?>
<?php $__env->startSection('contenidoplantilla'); ?>

    <style>

        /* ===== Contenedor general ===== */
        .wizard-container {
            display: flow-root;
            /* ✅ evita desbordes y colapso de márgenes */
            justify-content: center;
            align-items: center;
            position: relative;
            background: #faf8f4;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            padding: 20px 30px;
            width: 100%;
            margin: 20px auto;
        }

        /* ===== Contenedor general ===== */
        .wizard-container {
            display: flow-root;
            /* ✅ evita desbordes y colapso de márgenes */
            justify-content: center;
            align-items: center;
            position: relative;
            background: #faf8f4;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            padding: 20px 30px;
            width: 100%;
            margin: 20px auto;
        }

        /* ===== Barra de progreso ===== */
        .progressbar {
            display: flex;
            justify-content: space-between;
            position: relative;
            counter-reset: step;
            padding: 0;
            margin: 0;
        }

        .progressbar::before {
            content: '';
            position: absolute;
            top: 22px;
            left: 0;
            width: 100%;
            height: 4px;
            background-color: #e0e0e0;
            border-radius: 4px;
            z-index: 0;
        }

        .progressbar li {
            list-style: none;
            flex: 1;
            text-align: center;
            color: #999;
            font-weight: 600;
            position: relative;
            transition: all 0.4s ease;
            z-index: 1;
        }

        .progressbar li::before {
            content: attr(data-icon);
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            font-size: 18px;
            width: 45px;
            height: 45px;
            line-height: 45px;
            border: 3px solid #0A8CB3;
            display: block;
            margin: 0 auto 10px;
            border-radius: 50%;
            background-color: #fff;
            color: #0A8CB3;
            transition: all 0.4s ease;
        }

        .progressbar li.active {
            color: #0A8CB3;
        }

        .progressbar li.active::before {
            background-color: #0A8CB3;
            color: #fff;
            transform: scale(1.1);
            box-shadow: 0 0 8px rgba(10, 140, 179, 0.4);
        }


        /* ===== Pasos ===== */
        .form-step {
            display: none;
            opacity: 0;
            transform: translateX(50px);
            transition: all 0.5s ease;
        }

        .form-step-active {
            display: block;
            opacity: 1;
            transform: translateX(0);
            animation: fadeInUp 0.5s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ===== Botones ===== */
        .wizard-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }

        .btn-step {
            font-weight: 600;
            border-radius: 8px;
            padding: 10px 25px;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background-color: #0A8CB3 !important;
            border: none;
        }

        .btn-primary:hover {
            background-color: #09779A !important;
            transform: translateY(-1px);
        }

        .btn-secondary {
            background-color: #adb5bd !important;
            border: none;
        }

        .btn-success {
            background-color: #198754 !important;
            border: none;
        }



        .form-control.custom-gold2 {
            border: 1px solid #dc3545 !important;
            background-color: white !important;
        }
    </style>


    <div class="container-fluid estilo-info margen-movil-2">
        <div class="row mt-4 ml-1 mr-1">
            <div class="col-12 mb-3">
                <div class="box_block">
                    <button class="btn btn-primary btn-block btn_header header_6 estilo-info" type="button"
                        data-toggle="collapse" aria-expanded="true">
                        <i class="fas fa-file-signature"></i>&nbsp;Ficha del estudiante
                        <div class="float-right"><i class="fas fa-chevron-down"></i></div>
                    </button>
                </div>

                <div class="collapse show">
                    <div class="card card-body rounded-0 border-0 pt-0" style="padding:1.5rem;">

                        <!-- Barra de progreso profesional -->
                        <!-- Barra de progreso -->
                        <div class="wizard-container">
                            <div class="progressbar-container">
                                <ul class="progressbar">
                                    <li class="active" data-icon="&#xf007;"></li>
                                    <li data-icon="&#xf015;"></li>
                                    <li data-icon="&#xf095;"></li>
                                    <li data-icon="&#xf500;"></li>
                                </ul>
                            </div>
                        </div>

                        <!-- Formulario -->
                        <form id="formularioEstudiante" method="POST" action="<?php echo e(route('estudiantes.store')); ?>"
                            enctype="multipart/form-data" autocomplete="off">
                            <?php echo csrf_field(); ?>

                            <!-- Paso 1 -->
                            <div class="form-step form-step-active">
                                <?php if (isset($component)) { $__componentOriginal183d7538fe6395df0e8795da8114e844 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal183d7538fe6395df0e8795da8114e844 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.persona.formularioDatosPersonales','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('persona.formularioDatosPersonales'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal183d7538fe6395df0e8795da8114e844)): ?>
<?php $attributes = $__attributesOriginal183d7538fe6395df0e8795da8114e844; ?>
<?php unset($__attributesOriginal183d7538fe6395df0e8795da8114e844); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal183d7538fe6395df0e8795da8114e844)): ?>
<?php $component = $__componentOriginal183d7538fe6395df0e8795da8114e844; ?>
<?php unset($__componentOriginal183d7538fe6395df0e8795da8114e844); ?>
<?php endif; ?>
                                <?php if (isset($component)) { $__componentOriginalf6e13bfa463bfa8f3cf224d8728874fa = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf6e13bfa463bfa8f3cf224d8728874fa = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.persona.formularioFotoIdentificacion','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('persona.formularioFotoIdentificacion'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf6e13bfa463bfa8f3cf224d8728874fa)): ?>
<?php $attributes = $__attributesOriginalf6e13bfa463bfa8f3cf224d8728874fa; ?>
<?php unset($__attributesOriginalf6e13bfa463bfa8f3cf224d8728874fa); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf6e13bfa463bfa8f3cf224d8728874fa)): ?>
<?php $component = $__componentOriginalf6e13bfa463bfa8f3cf224d8728874fa; ?>
<?php unset($__componentOriginalf6e13bfa463bfa8f3cf224d8728874fa); ?>
<?php endif; ?>
                                <div class="wizard-buttons">
                                    <a href="<?php echo e(route('estudiantes.index')); ?>" type="button" class="btn btn-step prev-step"
                                        style="background-color:goldenrod; color: white; border-radius: 12px;">
                                        <i class="fas fa-arrow-left mx-2"></i> Regresar al listado
                                    </a>
                                    <button id="sgtPrimer" type="button" class="btn btn-primary btn-step next-step">
                                        Siguiente <i class="fas fa-arrow-right mx-2"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Paso 2 -->
                            <div class="form-step">
                                <?php if (isset($component)) { $__componentOriginal3664ccd228e31cc7f3bca87da51b5806 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3664ccd228e31cc7f3bca87da51b5806 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.persona.formularioDatosDireccion','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('persona.formularioDatosDireccion'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3664ccd228e31cc7f3bca87da51b5806)): ?>
<?php $attributes = $__attributesOriginal3664ccd228e31cc7f3bca87da51b5806; ?>
<?php unset($__attributesOriginal3664ccd228e31cc7f3bca87da51b5806); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3664ccd228e31cc7f3bca87da51b5806)): ?>
<?php $component = $__componentOriginal3664ccd228e31cc7f3bca87da51b5806; ?>
<?php unset($__componentOriginal3664ccd228e31cc7f3bca87da51b5806); ?>
<?php endif; ?>
                                <div class="wizard-buttons">
                                    <button type="button" class="btn btn-secondary btn-step prev-step">
                                        <i class="fas fa-arrow-left"></i> Anterior
                                    </button>
                                    <button type="button" class="btn btn-primary btn-step next-step">
                                        Siguiente <i class="fas fa-arrow-right mx-2"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Paso 3 -->
                            <div class="form-step">
                                <?php if (isset($component)) { $__componentOriginal1a1bbc0301dca4692716dab3a992f819 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1a1bbc0301dca4692716dab3a992f819 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.persona.formularioDatosContacto','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('persona.formularioDatosContacto'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1a1bbc0301dca4692716dab3a992f819)): ?>
<?php $attributes = $__attributesOriginal1a1bbc0301dca4692716dab3a992f819; ?>
<?php unset($__attributesOriginal1a1bbc0301dca4692716dab3a992f819); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1a1bbc0301dca4692716dab3a992f819)): ?>
<?php $component = $__componentOriginal1a1bbc0301dca4692716dab3a992f819; ?>
<?php unset($__componentOriginal1a1bbc0301dca4692716dab3a992f819); ?>
<?php endif; ?>
                                <div class="wizard-buttons">
                                    <button type="button" class="btn btn-secondary btn-step prev-step">
                                        <i class="fas fa-arrow-left mx-20"></i> Anterior
                                    </button>

                                    <button type="button" class="btn btn-primary btn-step next-step">
                                        Siguiente <i class="fas fa-arrow-right mx-2"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Paso 4 -->
                            <div class="form-step">
                                <?php echo $__env->make('ceinformacion.estudiantes.nuevo.datosRepresentante', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                <div class="wizard-buttons">
                                    <button type="button" class="btn btn-secondary btn-step prev-step">
                                        <i class="fas fa-arrow-left mx-2"></i> Anterior
                                    </button>

                                    <button type="submit" class="btn btn-success btn-step">
                                        <i class="fas fa-save mx-2"></i> Guardar Estudiante
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const steps = document.querySelectorAll(".form-step");
            const progressSteps = document.querySelectorAll(".progressbar li");
            let currentStep = 0;

            function showStep(index) {
                // Ocultar todos los pasos
                steps.forEach((step, i) => {
                    if (i === index) {
                        step.classList.add("form-step-active");
                    } else {
                        step.classList.remove("form-step-active");
                    }
                });

                // Actualizar barra de progreso
                progressSteps.forEach((step, i) => {
                    step.classList.toggle("active", i <= index);
                });
            }

            // Botón siguiente
            document.querySelectorAll(".next-step").forEach(btn => {
                btn.addEventListener("click", () => {
                    if (currentStep < steps.length - 1) {
                        currentStep++;
                        showStep(currentStep);
                    }
                });
            });

            // Botón anterior
            document.querySelectorAll(".prev-step").forEach(btn => {
                btn.addEventListener("click", () => {
                    if (currentStep > 0) {
                        currentStep--;
                        showStep(currentStep);
                    }
                });
            });

            // Inicializar
            showStep(currentStep);
        });
    </script>

    <!--PARA NO PERMITIR CARACTERES NI ESPACIOS-->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Función para prevenir espacios
            function evitarEspacios(input) {
                input.addEventListener('keydown', function(e) {
                    if (e.key === ' ') {
                        e.preventDefault();
                    }
                });
            }

            // Lista de IDs a los que se les aplicará la validación
            const sinEspacios = [
                'dniEstudiante',
                'correoEstudiante',
                'apellidoPaternoEstudiante',
                'apellidoMaternoEstudiante',
                'numeroCelularEstudiante',
                'numeroEstudiante'
            ];

            // Aplicamos la función a todos los elementos por ID
            sinEspacios.forEach(id => {
                const input = document.getElementById(id);
                if (input) {
                    evitarEspacios(input);
                }
            });

            // Evita que se ingresen caracteres que no sean números
            function evitarCaracteres(input) {
                input.addEventListener('input', function() {
                    this.value = this.value.replace(/\D/g, ''); // Solo deja dígitos
                });
            }

            // IDs que no deben permitir espacios
            const sinCaracteres = [
                'dniEstudiante',
                'numeroEstudiante'
            ];

            // Aplicamos la función a todos los elementos por ID
            sinCaracteres.forEach(id => {
                const input = document.getElementById(id);
                if (input) {
                    evitarCaracteres(input);
                }
            });

        });
    </script>

    <!--PARA ENVIAR SOLO 9 DIGITOS (SIN SUS ESPACIOS)-->
    <script>
        document.getElementById('formularioEstudiante').addEventListener('submit', function() {
            const celularInput = document.getElementById('numeroCelularEstudiante');
            celularInput.value = celularInput.value.replace(/\s+/g, '');

        });
    </script>

    <!--VALIDACIONES DE CAMPOS-->
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
                correo: document.getElementById('correoEstudiante'),
            };

            var contador = 0;
            const btnSiguiente = document.getElementById('sgtPrimer');

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


            inputs.fechaNacimiento.addEventListener('input', function() {
                const hoy = new Date();
                const fechaIngresada = new Date(this.value);

                // Calcular la diferencia de años
                const edad = hoy.getFullYear() - fechaIngresada.getFullYear();
                const mes = hoy.getMonth() - fechaIngresada.getMonth();
                const dia = hoy.getDate() - fechaIngresada.getDate();

                // Ajustar si aún no ha cumplido años este año
                const edadReal = (mes < 0 || (mes === 0 && dia < 0)) ? edad - 1 : edad;

                // Validar edad mínima (5 años)
                if (edadReal < 5) {
                    setInvalid(this, 'El estudiante debe tener al menos 5 años.');
                    fechaNacimientoEstudiante.classList.add('custom-gold2');
                } else {
                    clearInvalid(this);
                    fechaNacimientoEstudiante.classList.add('custom-gold');
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
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
      <!--PARA ENVIAR SOLO 9 DIGITOS (SIN SUS ESPACIOS)-->
    <script>
        document.getElementById('formularioEstudiante').addEventListener('submit', function() {
            const celularInput = document.getElementById('telefono');
            celularInput.value = celularInput.value.replace(/\s+/g, '');
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('cplantilla.bprincipal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/ronaldoroblesromero/Herd/edukaSchool/resources/views/ceinformacion/estudiantes/nuevo.blade.php ENDPATH**/ ?>