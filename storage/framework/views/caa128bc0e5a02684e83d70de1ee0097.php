<div id="tabla-niveles" class="table-responsive mt-2">
    <table id="add-row" class="table-hover table" style="border-radius: 8px; overflow: hidden;">
        <thead class="table-hover estilo-info" style="background-color: #f8f9fa; color: #0A8CB3;">
            <tr class="text-center">
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $niveles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nivel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($nivel->nombre); ?></td>
                    <td><?php echo e($nivel->descripcion); ?></td>

                    <td class="text-center">

                        <a href="<?php echo e(route('registrarnivel.edit', $nivel->nivel_id)); ?>"
                            class="btn btn-outline-primary btn-sm  btn-editar-nivel" title="Editar">
                            <i class="ti ti-edit" style=" font-size: 1.2rem;"></i>
                        </a>
                        <a href="<?php echo e(route('registrarnivel.confirmar', $nivel->nivel_id)); ?>"
                            class="btn btn-outline-primary btn-sm  btn-eliminar-nivel " title="Eliminar">
                            <i class="ti ti-trash-x" style=" font-size: 1.3rem;"></i>
                        </a>

                    </td>
                </tr>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="3" class="text-center text-muted">
                        No se encontraron niveles registrados.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Paginación -->
    <div class="tabla-niveles d-flex justify-content-center">
        <?php echo e($niveles->links()); ?>

    </div>
</div>
<?php /**PATH C:\Users\Ronaldo Robles\Music\eduka_11012026_Final_V6\resources\views/ceinformacion/niveles/tabla.blade.php ENDPATH**/ ?>