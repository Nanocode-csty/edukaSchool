<?php $__env->startSection('titulo', 'Registrar Nivel Educativo'); ?>
<?php $__env->startSection('contenidoplantilla'); ?>

    <?php echo $__env->make('ccomponentes.loader', ['id' => 'loaderPrincipal'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <div class="container-fluid  estilo-info margen-movil-2" id="contenido-principal" style="position: relative;">
        <div class="row mt-4 ml-1 mr-1">
            <div class="col-12 mb-3">
                <div class="box_block">
                    <button class="btn btn-block estilo-info btn_header" type="button" data-toggle="collapse">
                        <i class="fas fa-file-signature"></i>&nbsp;Registrar Nuevo Nivel Educativo
                    </button>
                </div>
                <div class="collapse show">
                    <div class="card card-body rounded-0 border-0 pt-0">
                        <div class="row margen-movil" style="padding:20px;">
                            <div class="col-12">

                                <form id="formNivel" method="POST" action="<?php echo e(route('registrarnivel.store')); ?>"
                                    autocomplete="off" enctype="multipart/form-data">
                                    <?php echo csrf_field(); ?>
                                    <div class="card" style="border: none">
                                        <div class="card-header-custom">
                                            <i class="icon-user-following mr-2"></i>
                                            Datos para el Nuevo Nivel Educativo
                                        </div>

                                        <div class="card-body"
                                            style="border: 2px solid #86D2E3; border-top: none; border-radius: 0px 0px 6px 6px !important;">

                                            <?php if (isset($component)) { $__componentOriginalbbf5fc04dc579ee03c5cfd427426e106 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbbf5fc04dc579ee03c5cfd427426e106 = $attributes; } ?>
<?php $component = App\View\Components\Form\Field::resolve(['name' => 'nombre','label' => 'Nombre de Nivel Educativo'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
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

                                            <?php if (isset($component)) { $__componentOriginalbbf5fc04dc579ee03c5cfd427426e106 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbbf5fc04dc579ee03c5cfd427426e106 = $attributes; } ?>
<?php $component = App\View\Components\Form\Field::resolve(['name' => 'descripcion','label' => 'Descripción'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
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

                                    <div class="row  d-flex justify-content-between align-items-center gap-4">

                                        <a id="cancelar" href="<?php echo e(route('registrarnivel.index')); ?>"
                                            class="col-md-5 btn btn-color btn-lg ">
                                            <i class="fas fa-arrow-left"></i> Cancelar
                                        </a>
                                        <button id="btnAsignar" type="submit" class=" col-md-6 btn btn-color btn-lg">
                                            REGISTRAR NIVEL
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loader = document.getElementById('loaderPrincipal');
            const contenido = document.getElementById('contenido-principal');
            const form = document.getElementById('formNivel');
            if (loader) loader.style.display = 'none';
            if (contenido) contenido.style.opacity = '1';
            // Loader para cancelar (volver a registrar)
            const cancelarBtn = document.getElementById('cancelar');
            if (cancelarBtn) {
                cancelarBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (loader && contenido) {
                        loader.style.display = 'flex';
                        contenido.style.opacity = '0.5';
                    }
                    setTimeout(() => {
                        window.location.href = this.href;
                    }, 800);
                });
            }
            if (form) {
                form.addEventListener('submit', function() {
                    if (loader && contenido) {
                        loader.style.display = 'flex';
                        contenido.style.opacity = '0.5';
                    }
                });
            }
            window.addEventListener('pageshow', function(event) {
                if (loader) loader.style.display = 'none';
                if (contenido) contenido.style.opacity = '1';
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('cplantilla.bprincipal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/ronaldoroblesromero/Herd/edukaSchool/resources/views/ceinformacion/niveles/nuevo/create.blade.php ENDPATH**/ ?>