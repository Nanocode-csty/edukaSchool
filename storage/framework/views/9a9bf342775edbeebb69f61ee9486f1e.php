<div id="tabla-docentes" class="table-responsive mt-2">
    <table id="add-row" class="table-hover table" style="border-radius: 8px; overflow: hidden;">
        <thead class="  table-hover estilo-info"
            style="background-color: #f8f9fa; color: #0A8CB3; border:#0A8CB3 !important">
            <tr>
                <th class="text-center" style="width: 30px;"></th>
                <th class="text-center" scope="col">N.° DNI</th>
                <th class="text-center" scope="col">Nombre Completo</th>
                <th class="text-center" scope="col">Especialidad</th>
                <th class="text-center" scope="col">Opciones</th>

            </tr>
        </thead>

        <tbody style="font-family: 'Quicksand', sans-serif !important;">
            <?php $__currentLoopData = $docente; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $itemDocente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="<?php echo e($index % 2 == 0 ? 'even' : 'odd'); ?>">
                    <td class="text-center align-middle d-none d-md-block">
                        <button type="button" class="toggle-btn" data-target="#collapseRow<?php echo e($index); ?>"
                            title="Ver más">
                            <i class="fas fa-chevron-down" style="color: #0A8CB3 !important;"></i>
                        </button>
                    </td>
                    <td class="text-center"><?php echo e($itemDocente->persona->dni); ?></td>
                    <td><?php echo e($itemDocente->persona->apellidos . ', ' . $itemDocente->persona->nombres); ?></td>
                    <td class=" text-center py-2">
                        <div data-bind="html:DescripcionEstado"><span class="badge badge-info"
                                style="background-color:#f2be65 !important; color:black; font:bolder">
                                <?php echo e($itemDocente->persona->especialidad); ?></span>
                        </div>
                    </td>
                    <td class="text-center btn-action-group d-none d-md-block">
                        <!-- Botón -->
                        <button type="button" class="btn btn-link btn-sm" data-toggle="modal"
                            data-target="#modalEditar<?php echo e($itemDocente->persona->profesor_id); ?>">
                            <i class="fa fa-edit"></i>
                        </button>
                        <div class="modal fade" id="modalEditar<?php echo e($itemDocente->profesor_id); ?>" tabindex="-1"
                            role="dialog">
                            <div class="modal-dialog" role="document">
                                <form action="<?php echo e(route('registrardocente.update', $itemDocente->profesor_id)); ?>"
                                    method="POST" autocomplete="off">
                                    <?php echo csrf_field(); ?>
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title">Editar Docente</h5>
                                            <button type="button" class="close text-white"
                                                data-dismiss="modal">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Dirección</label>
                                                <input type="text" name="direccion" class="form-control"
                                                    value="<?php echo e($itemDocente->persona->direccion); ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Teléfono</label>
                                                <input type="number" name="telefono" class="form-control"
                                                    value="<?php echo e($itemDocente->persona->telefono); ?>" maxlength="9"
                                                    required>
                                            </div>
                                            <div class="form-group">
                                                <label>Email</label>
                                                <input type="email" name="email" class="form-control"
                                                    value="<?php echo e($itemDocente->persona->email); ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Especialidad</label>
                                                <input type="text" name="especialidad" class="form-control"
                                                    value="<?php echo e($itemDocente->persona->especialidad); ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Fecha de Contratación</label>
                                                <input type="date" name="fecha_contratacion" class="form-control"
                                                    value="<?php echo e($itemDocente->persona->fecha_contratacion); ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Estado</label>
                                                <select name="estado" class="form-control" required>
                                                    <option
                                                        <?php echo e($itemDocente->persona->estado == 'Activo' ? 'selected' : ''); ?>>
                                                        Activo</option>
                                                    <option
                                                        <?php echo e($itemDocente->persona->estado == 'Inactivo' ? 'selected' : ''); ?>>
                                                        Inactivo</option>
                                                    <option
                                                        <?php echo e($itemDocente->persona->estado == 'Licencia' ? 'selected' : ''); ?>>
                                                        Licencia</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-success">Actualizar</button>
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Cancelar</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>


                        <form class="form-eliminar-docente"
                            data-nombre="<?php echo e($itemDocente->persona->apellidos . ', ' . $itemDocente->persona->nombres); ?>"
                            action="<?php echo e(route('registrardocente.destroy', $itemDocente->profesor_id)); ?>" method="POST"
                            style="display:inline;">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-link btn-danger btn-sm" title="Eliminar">
                                <i class="fa fa-times"></i>
                            </button>
                        </form>


                    </td>
                </tr>

                <tr class="collapse-row <?php echo e($index % 2 == 0 ? 'even' : 'odd'); ?>" id="collapseRow<?php echo e($index); ?>">
                    <td colspan="5">
                        <div class="p-3 d-flex justify-content-between align-items-start" style="gap: 2rem;">

                            
                            <div class="flex-grow-1">
                                <p>
                                    <i class="icon-calendar mr-4"></i>
                                    <strong>Fecha de Nacimiento:</strong> <?php echo e($itemDocente->persona->fecha_nacimiento); ?>

                                </p>
                                <p><i class="icon-information mr-4"></i><strong>Género:
                                    </strong><?php echo e($itemDocente->persona->genero == 'M' ? 'Masculino' : ($itemDocente->persona->genero == 'F' ? 'Femenino' : 'No especificado')); ?>

                                </p>
                                <p>
                                    <i class="icon-location-pin mr-4"></i>
                                    <strong>Dirección:</strong> <?php echo e($itemDocente->persona->direccion); ?>

                                </p>
                                <p>
                                    <i class="icon-phone mr-4"></i>
                                    <strong>Teléfono:</strong> <?php echo e($itemDocente->persona->telefono); ?>

                                </p>
                                <p>
                                    <i class="icon-envelope mr-4"></i>
                                    <strong>Correo:</strong> <?php echo e($itemDocente->persona->email); ?>

                                </p>
                                <p>
                                    <i class="icon-calendar mr-4"></i>
                                    <strong>Fecha de Contratación:</strong>
                                    <?php echo e($itemDocente->persona->fecha_contratacion); ?>

                                </p>
                            </div>

                            
                            <div class="text-center"
                                style="min-height: 160px; max-height: 160px; border: 1px solid #DF294C; padding: 2px;">
                                <?php
                                    // Verifica si tiene imagen, si no usa una por defecto
                                    $foto = $itemDocente->persona->foto_url
                                        ? asset('storage/fotos/' . $itemDocente->persona->foto_url)
                                        : asset('storage/fotos/imgDocente.png');
                                ?>

                                <img src="<?php echo e($foto); ?>" alt="Foto del docente" class="img-thumbnail rounded"
                                    style="min-height: 150px; max-height: 150px; user-select: none;" draggable="false"
                                    oncontextmenu="return false;">
                                <p class="mt-2 fw-bold">Foto del docente</p>
                            </div>

                        </div>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <!--onEachSide(1) limita los números de página mostrados alrededor de la página actual.-->
    <div id="tabla-docentes" class="d-flex justify-content-center mt-3">
        <?php echo e($docente->onEachSide(1)->links()); ?>

    </div>
</div>

<style>
    /* Oculta por defecto */
    .collapse-row {
        display: none;
        transition: all 0.3s ease;
        font-family: quicksand !important;
    }

    /* Al mostrar, se vuelve visible con animación */
    .collapse-row.show {
        display: table-row;
        animation: fadeIn 0.8s ease;
        font-family: quicksand !important;
    }

    /* Efecto de desvanecimiento suave */
    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }
</style>




<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tablaEstudiantes = document.getElementById('tabla-docentes');

        tablaEstudiantes.addEventListener('click', function(e) {
            const btn = e.target.closest('.toggle-btn');
            if (!btn) return;

            e.stopPropagation();

            const icon = btn.querySelector('i');
            const targetId = btn.getAttribute('data-target');
            const targetRow = document.querySelector(targetId);
            if (!targetRow) return;

            // Cierra cualquier otra fila abierta
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
                // Oculta la fila
                targetRow.classList.remove('show');
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
            } else {
                // Muestra la fila
                targetRow.classList.add('show');
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');

                // 🔽 Desplazamiento suave a la fila
                setTimeout(() => {
                    targetRow.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }, 150); // Espera un poco para que se aplique .show
            }
        });

        // Cierra la fila activa al hacer clic fuera de la tabla
        document.addEventListener('click', function(event) {
            const tabla = document.getElementById('tabla-docentes');
            const isClickInside = tabla.contains(event.target);

            // Si el clic fue fuera de la tabla, cerrar cualquier fila abierta
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const formularios = document.querySelectorAll('.form-eliminar-docente');

        formularios.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault(); // Evita el envío inmediato

                const nombreDocente = form.getAttribute('data-nombre');

                Swal.fire({
                    title: '¿Estás seguro?',
                    html: `¿Deseas eliminar al docente <strong>${nombreDocente}</strong>?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit(); // Enviar el formulario si el usuario confirma
                    }

                });
            });
        });
    });
</script>
<?php /**PATH /Users/ronaldoroblesromero/Herd/edukaSchool/resources/views/ceinformacion/docentes/docente.blade.php ENDPATH**/ ?>