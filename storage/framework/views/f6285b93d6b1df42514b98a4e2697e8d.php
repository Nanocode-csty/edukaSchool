<div id="paginacion-estudiantes" class="table-responsive mt-2">
    <table id="add-row" class="table-hover table" style="border-radius: 8px; overflow: hidden;">
        <thead class="table-hover estilo-info" style="background-color: #f8f9fa; color: #0A8CB3;">
            <tr>
                <th class="text-center" scope="col">DNI</th>
                <th class="text-center" scope="col">Nombres Completos</th>
                <th class="text-center" scope="col">Teléfono</th>
                <th class="text-center" scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody style="font-family: 'Quicksand', sans-serif !important;">

            <?php $__empty_1 = true; $__currentLoopData = $estudiante; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $itemEstudiante): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="<?php echo e($index % 2 == 0 ? 'even' : 'odd'); ?>">

                    <td class="text-center"><?php echo e($itemEstudiante->persona->dni); ?></td>
                    <td>
                        <?php echo e(ucwords(strtolower($itemEstudiante->persona->nombrecompleto))); ?>

                    </td>
                    <td class="text-center">
                        <span class="badge badge-info"
                            style="background-color: #eee3d465; font-weight:bold; color:black; font-size:13px"><?php echo e($itemEstudiante->persona->telefono_formato); ?></span>
                    </td>
                    <td class="text-center g-4 ">

                        <a href="<?php echo e(route('estudiantes.ficha', $itemEstudiante->estudiante_id)); ?>"
                            class=" btn btn-sm btn-outline-primary"
                            target="_blanck">
                            <i class="ti ti-file-text mx-1" ></i>
                        </a>

                        <a class="btn btn-sm btn-outline-primary toggle-action"
                            data-target="#collapseRow<?php echo e($index); ?>">
                            <i class="ti ti-list-details mx-1"></i>
                        </a>

                        <a class="btn btn-sm btn-outline-primary"
                            href="<?php echo e(route('estudiantes.edit', $itemEstudiante->estudiante_id)); ?>">
                            <i class="ti ti-edit mx-1" ></i>

                        </a>
                    </td>
                </tr>

                
                <tr class="collapse-row <?php echo e($index % 2 == 0 ? 'even' : 'odd'); ?>" id="collapseRow<?php echo e($index); ?>">
                    <td colspan="4">
                        <div class="p-3 d-flex flex-wrap gap-3">

                            
                            <div class="flex-grow-1" style="min-width: 300px;">
                                <p><i class="icon-calendar mr-2"></i><strong>Fecha de Nacimiento:</strong>
                                    <?php echo e($itemEstudiante->persona->fecha_nacimiento); ?></p>
                                <p><i class="icon-information mr-2"></i><strong>Género:</strong>
                                    <?php echo e($itemEstudiante->persona->genero_convertido); ?>

                                </p>
                                <p><i class="icon-envelope mr-2"></i><strong>Correo:</strong>
                                    <a
                                        href="mailto:<?php echo e($itemEstudiante->persona->email); ?>"><?php echo e($itemEstudiante->persona->email); ?></a>
                                </p>
                                <p><i class="icon-location-pin mr-2"></i><strong>Dirección:</strong>
                                    <a href="https://www.google.com/maps/search/<?php echo e(urlencode($itemEstudiante->persona->direccion_completa)); ?>"
                                        target="_blank">
                                        <?php echo e($itemEstudiante->persona->direccion_completa); ?>

                                    </a>
                                </p>
                                <p><i class="icon-calendar mr-2"></i><strong>Fecha de Registro:</strong>
                                    <?php echo e($itemEstudiante->fecha_matricula); ?>

                                </p>
                            </div>

                            
                            <div class="text-center" style="min-width: 160px;">

                                <?php
                                    // Ruta física del archivo dentro del storage
                                    $rutaFoto = storage_path(
                                        'app/public/estudiantes/' . ($itemEstudiante->foto_url ?? ''),
                                    );

                                    //viene de PHP Verificar si el archivo existe realmente isset verifica si existe y no es nula y file_exists, si está guardada en esa ruta
                                    $fotoExiste = isset($itemEstudiante->foto_url) && file_exists($rutaFoto);

                                    //Viene de laravel  Si existe, usarla; de lo contrario, mostrar imagen por defecto
                                    //asset Genera la URL pública hacia un archivo dentro de public/
                                    $foto = $fotoExiste
                                        ? asset('storage/estudiantes/' . $itemEstudiante->foto_url)
                                        : asset('storage/estudiantes/imgDocente.png');
                                ?>
                                <!--
                                object-fit: cover hace que la imagen mantenga su proporción y se recorte si es necesario (sin deformarse).
                                draggable="false" Evita que el usuario pueda arrastrar la imagen desde la página.
                                oncontextmenu="return false;" Desactiva el clic derecho sobre la imagen, evitando que alguien la descargue fácilmente o vea sus propiedades.
                                -->
                                <img src="<?php echo e($foto); ?>" alt="Foto del estudiante" class="img-thumbnail rounded"
                                    style="width: 150px; height: 160px; object-fit: cover;" draggable="false"
                                    oncontextmenu="return false;">
                                <p class="mt-2 fw-bold">Foto del estudiante</p>
                            </div>

                        </div>
                    </td>
                </tr>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="4" class="text-center">
                        <span>No se encontraron estudiantes registrados.</span>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <!--onEachSide(1) limita los números de página mostrados alrededor de la página actual.-->
    <div id="tabla-estudiantes" class="d-flex justify-content-center mt-3">
        <?php echo e($estudiante->onEachSide(1)->links()); ?>

    </div>
</div>
<style>

</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tablaEstudiantes = document.getElementById('tabla-estudiantes');

        tablaEstudiantes.addEventListener('click', function(e) {
            const btn = e.target.closest('.toggle-action');
            if (!btn) return;

            e.stopPropagation();


            const targetId = btn.getAttribute('data-target');
            const targetRow = document.querySelector(targetId);
            if (!targetRow) return;

            // Cierra cualquier otra fila abierta
            document.querySelectorAll('.collapse-row.show').forEach(row => {
                if (row !== targetRow) {
                    row.classList.remove('show');

                }
            });

            const isVisible = targetRow.classList.contains('show');

            if (isVisible) {
                // Oculta la fila
                targetRow.classList.remove('show');

            } else {
                // Muestra la fila
                targetRow.classList.add('show');

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
            const tabla = document.getElementById('tabla-estudiantes');
            const isClickInside = tabla.contains(event.target);

            // Si el clic fue fuera de la tabla, cerrar cualquier fila abierta
            if (!isClickInside) {
                document.querySelectorAll('.collapse-row.show').forEach(row => {
                    row.classList.remove('show');
                });
            }
        });
    });
</script>
<style>

</style>



<style>
    /* Oculta por defecto */
    .collapse-row {
        display: none;
        transition: all 0.3s ease;
        font-family: 'Quicksand', sans-serif;
        /* Tamaño de letra fluido */
        font-size: clamp(13px, 1.6vw, 16.5px) !important;
    }

    /* Al mostrar, se vuelve visible con animación */
    .collapse-row.show {
        display: table-row;
        animation: fadeIn 1.4s ease;
        font-family: 'Quicksand', sans-serif;
        /* Tamaño de letra fluido */
        font-size: clamp(13px, 1.6vw, 16.5px) !important;
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
<?php /**PATH C:\Users\Ronaldo Robles\Music\eduka_11012026_Final_V6\resources\views/ceinformacion/estudiantes/estudiante.blade.php ENDPATH**/ ?>