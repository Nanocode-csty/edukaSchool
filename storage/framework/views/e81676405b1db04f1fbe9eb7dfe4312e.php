<?php $__env->startSection('titulo', 'Nueva Sección'); ?>
<?php $__env->startSection('contenidoplantilla'); ?>

    <div class="container-fluid estilo-info margen-movil-2">
        <div class="row mt-4 ml-1 mr-1">
            <div class="col-12 mb-3">
                <div class="box_block">
                    <button class="btn btn-block estilo-info btn_header" type="button" data-toggle="collapse">
                        <i class="fas fa-file-signature"></i>&nbsp;Registrar Nueva Sección
                    </button>
                </div>
                <div class="collapse show">
                    <div class="card card-body rounded-0 border-0 pt-0">
                        <div class="row margen-movil" style="padding:20px;">
                            <div class="col-12">

                                <form id="form-seccion" method="POST" action="<?php echo e(route('secciones.store')); ?>"
                                    autocomplete="off" enctype="multipart/form-data">
                                    <?php echo csrf_field(); ?>
                                    <div class="card" style="border: none">
                                        <div class="card-header-custom">
                                            <i class="icon-user-following mr-2"></i>
                                            Datos para la Nueva Sección
                                        </div>

                                        <div class="card-body"
                                            style="border: 2px solid #86D2E3; border-top: none; border-radius: 0px 0px 6px 6px !important;">
                                            <div class="row mb-3">
                                                <label class="col-12 col-md-2 col-form-label">Año Lectivo</label>
                                                <div class="col-12 col-md-10">
                                                    <input class="form-control disabled-format" type="text"
                                                        value="<?php echo e($anio_lectivo->nombre); ?>" readonly>
                                                    <input class="form-control disabled-format" name="anio_lectivo_id"
                                                        type="hidden" value="<?php echo e($anio_lectivo->ano_lectivo_id); ?>">
                                                    <small class="text-muted">
                                                        El año lectivo se asigna automáticamente según el periodo activo.
                                                    </small>
                                                </div>

                                            </div>

                                            <?php if (isset($component)) { $__componentOriginalbbf5fc04dc579ee03c5cfd427426e106 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbbf5fc04dc579ee03c5cfd427426e106 = $attributes; } ?>
<?php $component = App\View\Components\Form\Field::resolve(['name' => 'idGrado','label' => 'Nombre del grado','type' => 'select','options' => $grado->pluck('descripcion', 'grado_id')->toArray()] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
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
<?php $component = App\View\Components\Form\Field::resolve(['name' => 'nombre','label' => 'Nombre de la Sección','type' => 'select','options' => [
                                                    'A' => 'Sección A',
                                                    'B' => 'Sección B',
                                                    'C' => 'Sección C',
                                                    'D' => 'Sección D',
                                                    'E' => 'Sección E',
                                                ]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
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
<?php $component = App\View\Components\Form\Field::resolve(['name' => 'capacidad_maxima','label' => 'Capacidad Máxima','type' => 'number'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Form\Field::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['max' => '30','min' => '1']); ?>
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
<?php $component->withAttributes(['class' => 'disabled-format','readonly' => true]); ?>
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
                                            REGISTRAR SECCIÓN
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
        const nombreInput = document.getElementById('nombre');
        const descripcionInput = document.getElementById('descripcion');
        const nombreGrado = document.getElementById('idGrado');
        const form = document.getElementById('form-seccion');

        // Lista de secciones ya existentes (simulación desde backend con Blade)
        const seccionesExistentes = <?php echo json_encode($seccionesExistentes ?? [], 15, 512) ?>; // Asegúrate de pasar esto desde el controlador

        nombreInput.addEventListener('change', () => {
            descripcionInput.value = nombreInput.value ?
                `${nombreGrado.options[nombreGrado.selectedIndex].text} Sección "${nombreInput.value}"` :
                '';
        });

        form.addEventListener('submit', function(e) {
            const nombreSeleccionado = nombreInput.value.trim();
            const capacidad = parseInt(document.getElementById('capacidad_maxima').value);

            // Validar si ya existe
            if (seccionesExistentes.includes(nombreSeleccionado)) {
                e.preventDefault();
                alert(`La sección "${nombreSeleccionado}" ya está registrada.`);
                return;
            }

            // Validar capacidad máxima
            if (capacidad > 30) {
                e.preventDefault();
                alert('La capacidad máxima no puede superar los 30 estudiantes.');
                return;
            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('cplantilla.bprincipal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/ronaldoroblesromero/Herd/edukaSchool/resources/views/ceinformacion/secciones/nuevo.blade.php ENDPATH**/ ?>