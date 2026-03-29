 <?php if($matricula->count() > 0): ?>
     <div class="table-responsive mt-2">
         
         <?php echo $__env->make('ccomponentes.loader', ['id' => 'loaderTablaMatriculas'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
         <table id="add-row" class="table-hover table align-middle"
             style="border: 1px solid #0A8CB3; border-radius: 10px; overflow: hidden;">
             <thead class="table-hover text-center estilo-info" style="background-color: #f9faf4; color:#26634e">
                 <tr>
                     <th>N.° Mat</th>
                     <th>N.° DNI</th>
                     <th>Nombre Completo</th>
                     <th>Fecha</th>
                     <!--<th>Estado</th>-->
                     <th>Opciones</th>
                 </tr>
             </thead>

             <tbody id="tabla-matriculas" style="font-family: 'Quicksand', sans-serif !important;">

                 <?php $__currentLoopData = $matricula; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                     <tr style="align-content: center; align-items:center; justify-content:center">
                         <td class="text-center"><?php echo e($mat->numero_matricula); ?></td>
                         <td class="text-center "><?php echo e($mat->estudiante->dni ?? 'N/A'); ?></td>
                         <td><?php echo e($mat->estudiante->apellidos ?? 'N/A'); ?>, <?php echo e($mat->estudiante->nombres ?? 'N/A'); ?></td>
                         <td class="text-center">
                             <?php echo e(\Carbon\Carbon::parse($mat->fecha_matricula)->format('d/m/Y')); ?>

                         </td>
                         <!--
                    <td class="text-center">
                        <?php if($mat->estado == 'Matriculado'): ?>
<span class="badge bg-succes fw-bold border-0" style="background-color: #2b8f47; color:white">
                                <?php echo e(Str::upper($mat->estado)); ?>

                            </span>
<?php elseif($mat->estado == 'Pre-inscrito'): ?>
<span class="badge fw-bold border-0" style="background-color: #ddda30; color:white">
                                <?php echo e(Str::upper($mat->estado)); ?>

                            </span>
<?php elseif($mat->estado == 'Anulado'): ?>
<span class="badge bg-danger fw-bold"><?php echo e(Str::upper($mat->estado)); ?></span>
<?php else: ?>
<span class="badge bg-secondary fw-bold"><?php echo e(Str::upper($mat->estado)); ?></span>
<?php endif; ?>
                    </td>

                     Botones de acción -->
                         <td class="text-center">
                             <?php if($mat->estado !== 'Matriculado'): ?>
                                 <div class="dropdown">
                                     <button class="btn eduka-btn btn-sm  px-3 w-100" type="button"
                                         id="accionesMenu<?php echo e($mat->matricula_id); ?>" data-bs-toggle="dropdown"
                                         aria-expanded="false" style="border-radius: 0.52rem">
                                         <div class="row"
                                             style="align-content: center; align-items:center; justify-content:center">
                                             <i class="fas fa-cogs mx-1" title="Acciones"></i>
                                             <span class="d-none d-md-block">Acciones</span>
                                         </div>
                                     </button>

                                     <ul class="dropdown-menu shadow border-0 rounded-3"
                                         aria-labelledby="accionesMenu<?php echo e($mat->matricula_id); ?>">
                                         <!-- Ver -->
                                         <li>
                                             <a class="dropdown-item d-flex align-items-center"
                                                 href="<?php echo e(route('matriculas.show', $mat->matricula_id)); ?>">
                                                 <i class="fas fa-eye mx-2 text-primary"></i> Ver detalle
                                             </a>
                                         </li>
                                         <!-- Editar -->
                                         <li>
                                             <?php if($mat->estado !== 'Matriculado'): ?>
                                                 <a class="dropdown-item d-flex align-items-center"
                                                     href="<?php echo e(route('matriculas.edit', $mat->matricula_id)); ?>">
                                                     <i class="fas fa-edit mx-2 text-warning"></i> Editar
                                                 </a>
                                             <?php endif; ?>
                                         </li>
                                         <!-- Anular -->
                                         <li>
                                             <?php if($mat->estado === 'Pre-inscrito'): ?>
                                                 <button type="button" class="dropdown-item d-flex align-items-center"
                                                     onclick="anularMatricula('<?php echo e($mat->matricula_id); ?>', '<?php echo e($mat->numero_matricula); ?>')">
                                                     <i class="fas fa-ban mx-2 text-danger"></i> Anular
                                                 </button>
                                             <?php endif; ?>
                                         </li>
                                     </ul>
                                 </div>
                             <?php else: ?>
                                 <a id="btn-nuevo" class="text-center dropdown-item fw-bold"
                                     href="<?php echo e(route('matriculas.show', $mat->matricula_id)); ?>"
                                     style="border: 1px solid #114a6b; border-radius: 0.52rem; transition: background 0.3s ease, transform 0.2s ease; font-size:11px">
                                     <div class="row"
                                         style="align-content: center; align-items:center; justify-content:center">
                                         <i id="icono" class="fas fa-eye mx-1 me-1 ms-1 text-primary"
                                             style="color: #114a6b !important" title="Ver detalle"></i>
                                         <span class="d-none d-md-block">Ver detalle</span>
                                     </div>

                                 </a>
                             <?php endif; ?>
                         </td>
                         <style>
                             #btn-nuevo:hover {
                                 background-color: #e8e5d4;
                                 transform: scale(1.035);


                             }
                         </style>
                     </tr>
                 <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
             </tbody>
         </table>

         <!-- Paginación -->
         <div class="d-flex justify-content-center mt-4">
             <?php echo e($matricula->onEachSide(1)->links()); ?>

         </div>
     </div>
 <?php else: ?>
     <div class="alert alert-warning text-center">
         <i class="fas fa-exclamation-triangle"></i>
         <strong>No se encontraron matrículas registradas.</strong>
         <?php if($buscarpor): ?>
             <br>No hay resultados para la búsqueda "<?php echo e($buscarpor); ?>".
         <?php endif; ?>
     </div>
 <?php endif; ?>

 <!-- 🎨 Estilos -->
 <style>
     #add-row td,
     #add-row th {
         padding: 4px 8px;
         font-size: 15.5px !important;
         vertical-align: middle;
         height: 49px;
         font-family: 'Quicksand', sans-serif !important;
     }

     .eduka-btn {
         background: #114a6b;
         color: #fff;
         border-radius: 8px;
         border: none;
         font-size: 11px !important;
         font-weight: bold;
         transition: background 0.3s ease, transform 0.2s ease;
     }

     .eduka-btn:hover {
         background: #005f8a;
         transform: scale(1.035);
     }

     .dropdown-menu {
         z-index: 3000;
         font-family: 'Quicksand', sans-serif;
         animation: fadeIn 0.2s ease-in-out;
     }

     @keyframes fadeIn {
         from {
             opacity: 0;
             transform: translateY(5px);
         }

         to {
             opacity: 1;
             transform: translateY(0);
         }
     }
 </style>
<?php /**PATH /Users/ronaldoroblesromero/Herd/edukaSchool/resources/views/cmatricula/matricula.blade.php ENDPATH**/ ?>