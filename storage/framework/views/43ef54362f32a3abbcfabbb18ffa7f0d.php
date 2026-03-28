<div class="card mt-4 " style="border: none">
    <div class="card-header-custom">
        <i class="icon-phone mr-2"></i>
        Información de Contacto
    </div>
    <div class="card-body"
        style="border: 2px solid #86D2E3; border-top: none; border-radius: 0px 0px 6px 6px !important;">

        <?php if (isset($component)) { $__componentOriginalbbf5fc04dc579ee03c5cfd427426e106 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbbf5fc04dc579ee03c5cfd427426e106 = $attributes; } ?>
<?php $component = App\View\Components\Form\Field::resolve(['name' => 'telefono','label' => 'Teléfono','type' => 'text'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Form\Field::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['inputmode' => 'numeric','maxlength' => '11']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbbf5fc04dc579ee03c5cfd427426e106)): ?>
<?php $attributes = $__attributesOriginalbbf5fc04dc579ee03c5cfd427426e106; ?>
<?php unset($__attributesOriginalbbf5fc04dc579ee03c5cfd427426e106); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbbf5fc04dc579ee03c5cfd427426e106)): ?>
<?php $component = $__componentOriginalbbf5fc04dc579ee03c5cfd427426e106; ?>
<?php unset($__componentOriginalbbf5fc04dc579ee03c5cfd427426e106); ?>
<?php endif; ?>

        <?php if (isset($component)) { $__componentOriginalbbf5fc04dc579ee03c5cfd427426e106 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbbf5fc04dc579ee03c5cfd427426e106 = $attributes; } ?>
<?php $component = App\View\Components\Form\Field::resolve(['name' => 'email','label' => 'Correo electrónico','type' => 'email'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Form\Field::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['inputmode' => 'email','maxlength' => '100']); ?>

            <div id="email-error" class="invalid-feedback"></div>

         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbbf5fc04dc579ee03c5cfd427426e106)): ?>
<?php $attributes = $__attributesOriginalbbf5fc04dc579ee03c5cfd427426e106; ?>
<?php unset($__attributesOriginalbbf5fc04dc579ee03c5cfd427426e106); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbbf5fc04dc579ee03c5cfd427426e106)): ?>
<?php $component = $__componentOriginalbbf5fc04dc579ee03c5cfd427426e106; ?>
<?php unset($__componentOriginalbbf5fc04dc579ee03c5cfd427426e106); ?>
<?php endif; ?>


        <div class="mt-4">
            <div class="alert alert-info d-flex align-items-center" role="alert">
                <i class="fas fa-info-circle me-2 mr-2"></i>
                <span>Asegurate de registrar un correo válido; este se usará para tu acceso a la Intranet.</span>
            </div>
        </div>
        <!-- Mensaje informativo -->
    </div>
</div>

<script>
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

    $('#email').on('input', function() {

        const email = $(this).val();
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        clearTimeout(timeout);
        clearInvalid(this);
        if (!regex.test(email)) {

            $('#email').removeClass('is-valid').addClass('is-invalid');
            $('#email-error').text('Ingrese un correo electrónico válido.');

            return;
        }

        if (email.length > 120) {

            $('#email').removeClass('is-valid').addClass('is-invalid');
            $('#email-error').text('El correo no puede superar los 120 caracteres.');

            return;
        }

        timeout = setTimeout(function() {

            $.ajax({
                url: "<?php echo e(route('verificar.email.representante')); ?>",
                method: 'GET',
                data: {
                    email
                },

                success: function(response) {

                    if (response.existe) {

                        $('#email').removeClass('is-valid').addClass('is-invalid');
                        $('#email-error').text('Este correo ya está registrado.');

                    } else {

                        $('#email').removeClass('is-invalid').addClass('is-valid');
                        $('#email-error').text('');

                    }

                },

                error: function() {
                    console.error('Error verificando el email');
                }

            });

        }, 400);

    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const correoInput = document.getElementById('email');
        const celInput = document.getElementById('telefono');

        celInput.addEventListener('keydown', function(e) {
            this.value = this.value.replace(/\s/g, '');
        });

        correoInput.addEventListener('keydown', function(e) {
            this.value = this.value.replace(/\s/g, '');
        });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const inputs = {
            telefono: document.getElementById('telefono'),
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

        inputs.telefono.addEventListener('input', function() {

            let rawValue = this.value.replace(/[^\d]/g, '').slice(0, 9);
            let formatted = rawValue.match(/.{1,3}/g);

            this.value = formatted ? formatted.join(' ') : '';

            const digitsOnly = rawValue;

            if (digitsOnly.length === 0) {
                this.classList.remove('is-valid', 'is-invalid');
                return;
            }

            if (!digitsOnly.startsWith('9')) {
                setInvalid(this, 'El teléfono debe iniciar con 9.');
                return;
            }

            if (digitsOnly.length !== 9) {
                setInvalid(this, 'El N.° de teléfono debe contener exactamente 9 dígitos.');
            } else {
                clearInvalid(this);
            }

        });
    });
</script>
<?php /**PATH /Users/ronaldoroblesromero/Herd/edukaSchool/resources/views/components/persona/formularioDatosContacto.blade.php ENDPATH**/ ?>