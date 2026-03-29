<!-- Vista parcial para la tabla de representantes legales -->
<div id="tabla-representantes" class="table-responsive mt-2">
    <table id="add-row" class="table-hover table" style="border-radius: 8px; overflow: hidden;">
        <thead class="table-hover estilo-info" style="background-color: #f8f9fa; color: #0A8CB3;">
            <tr>
                <th class="text-center">DNI</th>
                <th class="text-center">Nombre Completo</th>
                <th class="text-center">Teléfono</th>
                <th class="text-center" scope="col">Acciones</th>
            </tr>
        </thead>

        <tbody style="font-family: 'Quicksand', sans-serif !important;">
            <?php $__empty_1 = true; $__currentLoopData = $representante; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $itemRepresentante): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="<?php echo e($index % 2 == 0 ? 'even' : 'odd'); ?>">

                    <td class="text-center">
                        <?php echo e($itemRepresentante->persona->dni); ?></td>
                    <td>
                        <?php echo e($itemRepresentante->persona->nombre_completo); ?>

                    </td>

                    <td class="text-center">
                        <span class="badge badge-info"
                            style="background-color: #3091a4; font-weight:bold"><?php echo e(Str::substr($itemRepresentante->persona->telefono, 0, 3) . ' ' . Str::substr($itemRepresentante->persona->telefono, 3, 3) . ' ' . Str::substr($itemRepresentante->persona->telefono, 6, 3)); ?></span>
                    </td>

                    <td class="text-center btn-action-group">
                        <button type="button" class="btn btn-link btn-sm" title="Editar">
                            <i class="fa fa-edit text-warning"></i>
                        </button>
                        <button type="button" class="btn btn-link btn-danger btn-sm" title="Eliminar">
                            <i class="fa fa-times text-danger"></i>
                        </button>
                    </td>
                </tr>
                <tr class="collapse-row <?php echo e($index % 2 == 0 ? 'even' : 'odd'); ?>" id="collapseRow<?php echo e($index); ?>">
                    <td colspan="6">
                        <div class="p-3 d-flex flex-wrap gap-3">
                            <div class="flex-grow-1">
                                <p><i class="fa fa-phone mr-2 text-success"></i><strong>Teléfono:</strong>
                                    <?php echo e($itemRepresentante->persona->telefono); ?></p>
                                <p><i class="fa fa-phone-alt mr-2 text-success"></i><strong>Teléfono
                                        Alternativo:</strong>
                                    <?php echo e(!empty($itemRepresentante->persona->telefono_alternativo) ? $itemRepresentante->persona->telefono_alternativo : 'S/N'); ?>

                                </p>
                                <p><i class="fa fa-envelope mr-2 text-primary"></i><strong>Correo:</strong>
                                    <?php echo e($itemRepresentante->persona->email); ?></p>
                                <p><i class="fa fa-map-marker-alt mr-2 text-danger"></i><strong>Dirección:</strong>
                                    <?php echo e($itemRepresentante->persona->direccion); ?></p>
                                <p><i class="fa fa-briefcase mr-2 text-info"></i><strong>Ocupación:</strong>
                                    <?php echo e($itemRepresentante->persona->ocupacion); ?></p>
                                <p><i class="fa fa-calendar-alt mr-2 text-secondary"></i><strong>Fecha de
                                        Registro:</strong> <?php echo e($itemRepresentante->persona->fecha_registro); ?></p>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
             <tr>
                    <td  colspan="4" class="text-center">
                        <span>No se encontraron representantes registrados.</span>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div id="tabla-representantes" class="d-flex justify-content-center mt-3">
        <?php echo e($representante->onEachSide(1)->links()); ?>

    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tablaRepresentantes = document.getElementById('tabla-representantes');
        tablaRepresentantes.addEventListener('click', function(e) {
            const btn = e.target.closest('.toggle-btn');
            if (!btn) return;
            e.stopPropagation();
            const icon = btn.querySelector('i');
            const targetId = btn.getAttribute('data-target');
            const targetRow = document.querySelector(targetId);
            if (!targetRow) return;
            document.querySelectorAll('.collapse-row.show').forEach(row => {
                if (row !== targetRow) {
                    row.classList.remove('show');
                    const iconBtn = row.previousElementSibling.querySelector('.toggle-btn i');
                    if (iconBtn) {
                        iconBtn.classList.remove('fa-chevron-up');
                        iconBtn.classList.add('fa-chevron-down');
                    }
                }
            });
            const isVisible = targetRow.classList.contains('show');
            if (isVisible) {
                targetRow.classList.remove('show');
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
            } else {
                targetRow.classList.add('show');
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
                setTimeout(() => {
                    targetRow.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }, 150);
            }
        });
        document.addEventListener('click', function(event) {
            const tabla = document.getElementById('tabla-representantes');
            const isClickInside = tabla.contains(event.target);
            if (!isClickInside) {
                document.querySelectorAll('.collapse-row.show').forEach(row => {
                    row.classList.remove('show');
                    const iconBtn = row.previousElementSibling.querySelector('.toggle-btn i');
                    if (iconBtn) {
                        iconBtn.classList.remove('fa-chevron-up');
                        iconBtn.classList.add('fa-chevron-down');
                    }
                });
            }
        });
    });
</script>



<style>
    .collapse-row {
        display: none;
        transition: all 0.3s ease;
    }

    .collapse-row.show {
        display: table-row;
        animation: fadeIn 0.8s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    .fa {
        vertical-align: middle;
    }

    .mr-1 {
        margin-right: 0.25rem;
    }

    .mr-2 {
        margin-right: 0.5rem;
    }

    .gap-3 {
        gap: 1.5rem;
    }
</style>
<?php /**PATH /Users/ronaldoroblesromero/Herd/edukaSchool/resources/views/ceinformacion/representantes/representante.blade.php ENDPATH**/ ?>