
<?php $__env->startSection('titulo', 'Nuevo Grado'); ?>
<?php $__env->startSection('contenidoplantilla'); ?>

    <div class="container-fluid estilo-info margen-movil-2">
        <div class="row mt-4 ml-1 mr-1">
            <div class="col-12 mb-3">
                <div class="box_block">
                    <button class="btn btn-block estilo-info btn_header" type="button" data-toggle="collapse">
                        <i class="fas fa-file-signature"></i>&nbsp;Registrar Nuevo Grado
                    </button>
                </div>
                <div class="collapse show">
                    <div class="card card-body rounded-0 border-0 pt-0">
                        <div class="row margen-movil" style="padding:20px;">
                            <div class="col-12">

                                <form id="form-seccion" method="POST" action="<?php echo e(route('grados.store')); ?>"
                                    autocomplete="off" enctype="multipart/form-data">
                                    <?php echo csrf_field(); ?>
                                    <div class="card" style="border: none">
                                        <div class="card-header-custom">
                                            <i class="icon-user-following mr-2"></i>
                                            Datos para el Nuevo Grado
                                        </div>

                                        <div class="card-body"
                                            style="border: 2px solid #86D2E3; border-top: none; border-radius: 0px 0px 6px 6px !important;">


                                            <?php if (isset($component)) { $__componentOriginalbbf5fc04dc579ee03c5cfd427426e106 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbbf5fc04dc579ee03c5cfd427426e106 = $attributes; } ?>
<?php $component = App\View\Components\Form\Field::resolve(['name' => 'nivel_id','label' => 'Nivel Educativo','type' => 'select','options' => $niveles->pluck('nombre', 'nivel_id')->toArray()] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
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
<?php $component = App\View\Components\Form\Field::resolve(['name' => 'nombre','label' => 'Grado Académico','type' => 'select','options' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
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
<?php $component->withAttributes(['class' => 'disabled-format','readonly' => true]); ?>
                                                <small class="text-muted">
                                                    Campo generado automáticamente por el sistema.
                                                </small>
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

                                        <a href="<?php echo e(route('grados.index')); ?>" class="col-md-5 btn btn-color btn-lg ">
                                            <i class="fas fa-arrow-left"></i> Cancelar
                                        </a>
                                        <button id="btnAsignar" type="submit" class=" col-md-6 btn btn-color btn-lg ">
                                            REGISTRAR GRADO
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
        const niveles = <?php echo json_encode($niveles, 15, 512) ?>;

        document.addEventListener('DOMContentLoaded', function() {
            const nivelSelect = document.getElementById('nivel_id');
            const gradoSelect = document.getElementById('nombre');
            const descripcionInput = document.getElementById('descripcion');

            const gradosPrimaria = [1, 2, 3, 4, 5, 6];
            const gradosSecundaria = [1, 2, 3, 4, 5];

            function actualizarGrados() {
                gradoSelect.innerHTML = '<option value="">-- Seleccione un grado --</option>';
                descripcionInput.value = '';

                const nivelId = nivelSelect.value;
                const nivelNombre = niveles.find(n => n.nivel_id == nivelId)?.nombre;

                if (!nivelId || !nivelNombre) return;

                const grados = nivelNombre.toLowerCase().includes('primaria') ? gradosPrimaria : gradosSecundaria;

                grados.forEach(num => {
                    const opt = document.createElement('option');
                    opt.value = num;
                    opt.text = `${num}°`;
                    gradoSelect.appendChild(opt);
                });
            }

            function actualizarDescripcion() {
                const grado = gradoSelect.value;
                const nivelNombre = niveles.find(n => n.nivel_id == nivelSelect.value)?.nombre || '';
                descripcionInput.value = grado ? `${grado}° de ${nivelNombre.toLowerCase()}` : '';
            }

            nivelSelect.addEventListener('change', () => {
                actualizarGrados();
                actualizarDescripcion();
            });

            gradoSelect.addEventListener('change', actualizarDescripcion);

            // Inicializar si ya hay valores seleccionados (por ejemplo, al regresar con errores)
            if (nivelSelect.value) {
                actualizarGrados();
                if (gradoSelect.value) {
                    actualizarDescripcion();
                }
            }
        });
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('cplantilla.bprincipal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Ronaldo Robles\Music\eduka_11012026_Final_V6\resources\views/ceinformacion/grados/nuevo.blade.php ENDPATH**/ ?>