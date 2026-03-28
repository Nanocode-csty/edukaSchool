<div id="tabla-grados" class="table-responsive mt-2">
    <table id="add-row" class="table-hover table" style="border-radius: 8px; overflow: hidden;">
        <thead class="table-hover estilo-info" style="background-color: #f8f9fa; color: #0A8CB3;">
            <tr class="text-center">
                <th>Descripción</th>
                <th>Nivel Educativo</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $grados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grado): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($grado->descripcion); ?></td>
                    <td class="text-center">
                        <span class="badge fw-semibold px-3 py-1  rounded-pill"
                            style="background-color: #ede9fe; color: #6b21a8; font-size: 0.85rem; border:none; font-weight:bold">
                            <?php echo e(strtoupper($grado->nivel_nombre)); ?>

                        </span>
                    </td>
                    <td class="text-center">
                        <!-- Botón para desplegar detalles -->
                        <button class="btn btn-outline-primary btn-sm toggle-detalle" data-id="<?php echo e($grado->grado_id); ?>">
                            <i class="ti ti-text-plus"></i>
                        </button>

                        <!-- Botón eliminar -->
                        <form action="<?php echo e(route('grados.destroy', ['id' => $grado->grado_id])); ?>" method="POST"
                            class="d-inline-block form-eliminar">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-outline-primary btn-sm">
                                <i class="ti ti-trash-x-filled"></i>
                            </button>
                        </form>
                    </td>
                </tr>

                <!-- Fila desplegable oculta -->
                <tr class="detalle-fila" id="detalle-<?php echo e($grado->grado_id); ?>" style="display: none;">
                    <td colspan="3" class="bg-light text-start align-top" style="text-align: left !important;">
                        <div class="p-3 text-start">
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <p class="mb-1"><i class="fas fa-id-badge me-2"></i> <strong>ID:</strong>
                                        <?php echo e($grado->grado_id); ?></p>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <p class="mb-1"><i class="fas fa-layer-group me-2"></i> <strong>Grado:</strong>
                                        <?php echo e($grado->nombre); ?>°</p>
                                </div>
                                <div class="col-md-12">
                                    <p class="mb-1"><i class="fas fa-info-circle me-2"></i>
                                        <strong>Descripción:</strong>
                                        <?php echo e($grado->descripcion); ?>

                                    </p>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="3" class="text-center text-muted">
                        No se encontraron grados registrados.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Paginación -->
    <div class="tabla-grados d-flex justify-content-center">
        <?php echo e($grados->links()); ?>

    </div>
</div>

<!-- Script para detalles -->
<!-- Script para mostrar/ocultar detalles -->
<script>
    document.addEventListener('click', function(e) {
        const boton = e.target.closest('.toggle-detalle');
        if (!boton) return;

        const id = boton.getAttribute('data-id');
        const fila = document.getElementById('detalle-' + id);
        const icono = boton.querySelector('i');

        // Cerrar otras filas
        document.querySelectorAll('.detalle-fila').forEach(otraFila => {
            if (otraFila !== fila) {
                otraFila.style.display = 'none';
                const otroBoton = document.querySelector(
                    `.toggle-detalle[data-id="${otraFila.id.replace('detalle-', '')}"] i`);
                if (otroBoton) {
                    otroBoton.classList.remove('fa-chevron-up');
                    otroBoton.classList.add('fa-chevron-down');
                }
            }
        });

        // Alternar fila seleccionada
        const visible = fila.style.display !== 'none';
        fila.style.display = visible ? 'none' : '';
        icono.classList.toggle('fa-chevron-down', visible);
        icono.classList.toggle('fa-chevron-up', !visible);

        if (!visible) {
            setTimeout(() => {
                fila.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }, 200);
        }
    });
</script>
<?php /**PATH C:\Users\Ronaldo Robles\Music\eduka_11012026_Final_V6\resources\views/ceinformacion/grados/tabla.blade.php ENDPATH**/ ?>