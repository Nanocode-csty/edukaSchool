<?php $__env->startSection('titulo', 'Registrar Representante'); ?>
<?php $__env->startSection('contenidoplantilla'); ?>

    <div class="container-fluid estilo-info margen-movil-2">
        <div class="row mt-4 ml-1 mr-1">
            <div class="col-12 mb-3">
                <div class="box_block">
                    <button class="btn btn-block estilo-info btn_header" type="button" data-toggle="collapse">
                        <i class="fas fa-file-signature"></i>&nbsp;Ficha del Representante
                    </button>
                </div>
                <div class="collapse show">
                    <div class="card card-body rounded-0 border-0 pt-0">
                        <div class="row margen-movil" style="padding:20px;">
                            <div class="col-12">

                                <form id="formularioRepresentante" method="POST"
                                    action="<?php echo e(route('representante.store')); ?>" autocomplete="off"
                                    enctype="multipart/form-data">
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

                                    <div class="card" style="border: none">
                                        <div class="card-header-custom">
                                            <i class="icon-user-following mr-2"></i>
                                            Datos del Representante
                                        </div>

                                        <div class="card-body"
                                            style="border: 2px solid #86D2E3; border-top: none; border-radius: 0px 0px 6px 6px !important;">
                                            <?php if (isset($component)) { $__componentOriginalbbf5fc04dc579ee03c5cfd427426e106 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbbf5fc04dc579ee03c5cfd427426e106 = $attributes; } ?>
<?php $component = App\View\Components\Form\Field::resolve(['name' => 'ocupacion','label' => 'Ocupación'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
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
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-center mt-4">
                                        <button id="btnAsignar" type="submit" class="btn btn-color btn-lg w-100">
                                            REGISTRAR REPRESENTANTE
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

<?php $__env->startSection('scripts'); ?>

    <!--PARA ENVIAR SOLO 9 DIGITOS (SIN SUS ESPACIOS)-->
    <script>
        document.getElementById('formularioRepresentante').addEventListener('submit', function() {
            const celularInput = document.getElementById('telefono');
            celularInput.value = celularInput.value.replace(/\s+/g, '');
        });
    </script>

    <?php if(session('error')): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error al registrar',
                text: "<?php echo e(session('error')); ?>",
                confirmButtonColor: '#3085d6'
            });
        </script>
    <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('cplantilla.bprincipal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/ronaldoroblesromero/Herd/edukaSchool/resources/views/ceinformacion/representantes/nuevo/nuevo.blade.php ENDPATH**/ ?>