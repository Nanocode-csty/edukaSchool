<?php $__env->startSection('titulo', 'Añadir Docente'); ?>
<?php $__env->startSection('contenidoplantilla'); ?>

    <div class="container-fluid estilo-info margen-movil-2">
        <div class="row mt-4 ml-1 mr-1">
            <div class="col-12 mb-3">
                <div class="box_block">
                    <button class="btn btn-primary btn-block btn_header header_6 estilo-info" type="button"
                        data-toggle="collapse" aria-expanded="true">
                        <i class="fas fa-file-signature"></i>&nbsp;Ficha del docente
                        <div class="float-right"><i class="fas fa-chevron-down"></i></div>
                    </button>
                </div>
                <div class="collapse show">
                    <div class="card card-body rounded-0 border-0 pt-0"
                        style="padding-left:0.966666666rem;padding-right:0.9033333333333333rem;">
                        <div class="row margen-movil" style="padding:20px;">
                            <div class="col-12">
                                <!-- Formulario -->
                                <form id="formularioDocente" method="POST" action="<?php echo e(route('docente.store')); ?>"
                                    enctype="multipart/form-data" autocomplete="off">
                                    <?php echo csrf_field(); ?>

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


                                    <div class="card mt-4" style="border: none">
                                        <div class="card-header-custom">
                                            <i class="icon-briefcase mr-2"></i>
                                            Información para el puesto Laboral
                                        </div>
                                        <div class="card-body"
                                            style="border: 2px solid #86D2E3; border-top: none; border-radius: 0px 0px 6px 6px !important;">

                                            <?php if (isset($component)) { $__componentOriginalbbf5fc04dc579ee03c5cfd427426e106 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbbf5fc04dc579ee03c5cfd427426e106 = $attributes; } ?>
<?php $component = App\View\Components\Form\Field::resolve(['name' => 'especialidad','label' => 'Especialidad','type' => 'text'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Form\Field::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
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

                                            <!-- Flatpickr CSS -->
                                            <div class="row mb-3 align-items-center">
                                                <label class="col-12 col-md-2 col-form-label">Fecha de Contrato <span
                                                        style="color: #FF5A6A">(*)</span></label>
                                                <div class="col-12 col-md-10">
                                                    <input type="date"
                                                        class="form-control custom-gold <?php $__errorArgs = ['fecha_contratacion'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                        id="fecha_contratacion" name="fecha_contratacion"
                                                        placeholder="YYYY-MM-DD" value="<?php echo e(old('fecha_contratacion')); ?>">
                                                    <?php if($errors->has('fecha_contratacion')): ?>
                                                        <div class="invalid-feedback d-block text-start feedback-message">
                                                            <?php echo e($errors->first('fecha_contratacion')); ?>

                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>

                                            <script>
                                                flatpickr("#fecha_contratacion", {
                                                    dateFormat: "Y-m-d",
                                                    maxDate: "today",
                                                    locale: "es",
                                                    defaultDate: "today",
                                                    disableMobile: true,
                                                    onChange: function(selectedDates, dateStr, instance) {
                                                        const input = document.getElementById('fecha_contratacion');
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

                                    <div class="d-flex justify-content-center mt-4">
                                        <button id="btnAsignar" type="submit" class="btn btn-primary btn-block"
                                            style="background: #FF3F3F !important; border: none; font-weight: bold !important">
                                            REGISTRAR DOCENTE
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
<?php $__env->stopSection(); ?>
<!--PARA ENVIAR SOLO 9 DIGITOS (SIN SUS ESPACIOS)-->
<script>
    document.getElementById('formularioDocente').addEventListener('submit', function() {
        const celularInput = document.getElementById('telefono');
        celularInput.value = celularInput.value.replace(/\s+/g, '');
    });
</script>

<?php echo $__env->make('cplantilla.bprincipal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Ronaldo Robles\Music\eduka_11012026_Final_V6\resources\views/ceinformacion/docentes/nuevo.blade.php ENDPATH**/ ?>