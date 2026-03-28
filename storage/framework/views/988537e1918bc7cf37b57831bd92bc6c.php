<div id="tabla-aniolectivo" class="table-responsive mt-2">
    <table id="add-row" class="table-hover table" style="border-radius: 8px; overflow: hidden;">
        <thead class="table-hover estilo-info" style="background-color: #f8f9fa; color: #0A8CB3;">
            <tr class="text-center">
                <th>Nombre</th>
                <th>Inicio</th>
                <th>Fin</th>
                <th>Estado</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $anoslectivos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $anio): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($anio->nombre); ?></td>
                    <td><?php echo e(\Carbon\Carbon::parse($anio->fecha_inicio)->format('d/m/Y')); ?></td>
                    <td><?php echo e(\Carbon\Carbon::parse($anio->fecha_fin)->format('d/m/Y')); ?></td>
                    <td class="text-center">
                        <?php if($anio->estado === 'Activo'): ?>
                            <span class="badge fw-bold px-3 py-1 rounded-pill"
                                style="background-color:#82d94077; border:none; color:#026f0b;">
                                ACTIVO
                            </span>
                        <?php elseif($anio->estado === 'Planificación'): ?>
                            <span class="badge text-white fw-bold px-3 py-1 rounded-pill"
                                style="background-color: #64748b; border:none;">
                                EN PROCESO
                            </span>
                        <?php elseif($anio->estado === 'Finalizado'): ?>
                            <span class="badge fw-bold px-3 py-1 rounded-pill"
                                style="background-color:#d9484070; border:none; color:#7b1206;">
                                FINALIZADO
                            </span>
                        <?php else: ?>
                            <span
                                class="badge bg-secondary text-white fw-bold px-3 py-1 rounded-pill"><?php echo e($anio->estado); ?></span>
                        <?php endif; ?>
                    </td>
                    <td class="text-center">
                        <button class="btn btn-outline-primary btn-sm toggle-detalle"
                            data-id="<?php echo e($anio->ano_lectivo_id); ?>">
                            <i class="ti ti-text-plus"></i>
                        </button>

                        <?php if($anio->estado === 'Finalizado'): ?>
                            <button class="btn btn-sm btn-outline-primary" disabled><i class="ti ti-edit"></i></button>
                            <button class="btn btn-sm btn-outline-primary" disabled><i class="ti ti-trash-x-filled"></i></button>
                        <?php else: ?>
                            <a href="<?php echo e(route('aniolectivo.edit', $anio->ano_lectivo_id)); ?>"
                                class="btn btn-sm btn-outline-primary" title="Editar">
                                <i class="ti ti-edit"></i>
                            </a>
                            <form action="<?php echo e(route('aniolectivo.destroy', $anio->ano_lectivo_id)); ?>" method="POST"
                                class="d-inline-block form-eliminar">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-sm btn-outline-primary" title="Eliminar">
                                    <i class="ti ti-trash-x-filled"></i>
                                </button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>

                <!-- Fila desplegable -->
                <tr class="detalle-fila" id="detalle-<?php echo e($anio->ano_lectivo_id); ?>" style="display: none;">
                    <td colspan="5" class="bg-light text-start align-top">
                        <div class="p-3 text-start">
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <p class="mb-1"><i class="fas fa-calendar me-2"></i> <strong>Nombre:</strong>
                                        <?php echo e($anio->nombre); ?></p>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <p class="mb-1"><i class="fas fa-id-badge me-2"></i> <strong>ID:</strong>
                                        <?php echo e($anio->ano_lectivo_id); ?></p>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <p class="mb-1"><i class="fas fa-clock me-2"></i> <strong>Fecha de
                                            Inicio:</strong>
                                        <?php echo e(\Carbon\Carbon::parse($anio->fecha_inicio)->format('d/m/Y')); ?></p>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <p class="mb-1"><i class="fas fa-clock me-2"></i> <strong>Fecha de Fin:</strong>
                                        <?php echo e(\Carbon\Carbon::parse($anio->fecha_fin)->format('d/m/Y')); ?></p>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <p class="mb-1"><i class="fas fa-hourglass-half me-2"></i>
                                        <strong>Duración:</strong>
                                        <?php echo e($anio->fecha_inicio->diffInDays($anio->fecha_fin)); ?> días
                                    </p>
                                </div>
                                <div class="col-md-12">
                                    <p class="mb-1"><i class="fas fa-info-circle me-2"></i>
                                        <strong>Descripción:</strong>
                                        <?php echo e($anio->descripcion ?? 'Sin descripción'); ?>

                                    </p>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <!-- Paginación -->
    <div class="tabla-aniolectivo d-flex justify-content-center">
        <?php echo e($anoslectivos->links()); ?>

    </div>
</div>

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
<?php /**PATH C:\Users\Ronaldo Robles\Music\eduka_11012026_Final_V6\resources\views/ceinformacion/añolectivo/tabla.blade.php ENDPATH**/ ?>