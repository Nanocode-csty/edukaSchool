
<?php $__env->startSection('titulo','Conceptos de Pago'); ?>
<?php $__env->startSection('contenidoplantilla'); ?>
<?php if (isset($component)) { $__componentOriginale19f62b34dfe0bfdf95075badcb45bc2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.breadcrumb','data' => ['module' => 'conceptospago','section' => 'index']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['module' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('conceptospago'),'section' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('index')]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2)): ?>
<?php $attributes = $__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2; ?>
<?php unset($__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale19f62b34dfe0bfdf95075badcb45bc2)): ?>
<?php $component = $__componentOriginale19f62b34dfe0bfdf95075badcb45bc2; ?>
<?php unset($__componentOriginale19f62b34dfe0bfdf95075badcb45bc2); ?>
<?php endif; ?>
<div class="container-fluid" id="contenido-principal">
    <div class="row mt-4 ml-1 mr-1">
        <div class="col-12">
            <div class="box_block">
                <!-- Collapse header -->
                <button class="btn btn-block text-left rounded-0 btn_header header_6" type="button" data-toggle="collapse" data-target="#collapseConceptosPago" aria-expanded="true" aria-controls="collapseConceptosPago" style="background: #28a745 !important; font-weight: bold; color: white;">
                    <i class="fas fa-tags m-1"></i>&nbsp;Conceptos de Pago
                    <div class="float-right"><i class="fas fa-chevron-down"></i></div>
                </button>
                <!-- Descripción (siempre visible, dentro del bloque) -->
                <div class="card-body rounded-0 border-0 pt-3 pb-3" style="background: #f3f3f3; border-bottom: 1px solid rgba(0,0,0,.125); border-top: 1px solid rgba(0,0,0,.125); color: #F59D24;">
                    <div class="row justify-content-center align-items-center flex-wrap">
                        <div class="col-auto text-center mb-2 mb-md-0" style="min-width:48px;">
                            <i class="fas fa-exclamation-circle fa-2x"></i>
                        </div>
                        <div class="col px-2" style="text-align:justify;">
                            <p style="margin-bottom: 0px; font-family: 'Quicksand', sans-serif; font-weight: 600; color: #004a92;">
                                Gestiona los conceptos de pago del sistema educativo. Aquí puedes crear, editar y configurar los diferentes conceptos de cobro.
                            </p>
                        </div>
                    </div>
                </div>
                <!-- Collapse: filtros, estadísticas y lista -->
                <div class="collapse show" id="collapseConceptosPago">
                    <div class="card card-body rounded-0 border-0 pt-0 pb-2" style="background: transparent;">
                        <!-- Acciones Rápidas -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2 flex-wrap">
                                    <a href="<?php echo e(route('conceptospago.create')); ?>" class="btn btn-success btn-sm" title="Crear nuevo concepto de pago">
                                        <i class="fas fa-plus mr-1"></i>Nuevo Concepto
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Filtros y búsqueda -->
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <form method="GET" action="<?php echo e(route('conceptospago.index')); ?>" class="d-flex">
                                    <input type="text" name="buscarpor" class="form-control mr-2"
                                           placeholder="Buscar por nombre o descripción..."
                                           value="<?php echo e($buscarpor); ?>">
                                    <button type="submit" class="btn btn-outline-primary mr-2">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <?php if($buscarpor): ?>
                                        <a href="<?php echo e(route('conceptospago.index')); ?>" class="btn btn-outline-secondary">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    <?php endif; ?>
                                </form>
                            </div>
                        </div>

                        <!-- Lista de conceptos -->
                        <?php if($conceptos->count() > 0): ?>
                            <div class="row">
                                <?php $__currentLoopData = $conceptos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $concepto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-md-6 col-lg-4 mb-4">
                                        <div class="card h-100" style="border: 2px solid #e9ecef; border-radius: 10px;">
                                            <div class="card-header" style="background: #f8f9fa; border-bottom: 1px solid #dee2e6;">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h6 class="mb-0 font-weight-bold" style="color: #0A8CB3;"><?php echo e($concepto->nombre); ?></h6>
                                                    <span class="badge badge-primary" style="font-size: 0.9rem;">S/ <?php echo e(number_format($concepto->monto, 2)); ?></span>
                                                </div>
                                            </div>

                                            <div class="card-body">
                                                <?php if($concepto->descripcion): ?>
                                                    <p class="text-muted small mb-2"><?php echo e(Str::limit($concepto->descripcion, 80)); ?></p>
                                                <?php endif; ?>

                                                <div class="mb-2">
                                                    <span class="badge <?php echo e($concepto->recurrente ? 'badge-success' : 'badge-secondary'); ?>">
                                                        <i class="fas <?php echo e($concepto->recurrente ? 'fa-sync' : 'fa-calendar'); ?> mr-1"></i>
                                                        <?php echo e($concepto->recurrente ? 'Recurrente' : 'Único'); ?>

                                                    </span>
                                                    <?php if($concepto->periodo): ?>
                                                        <span class="badge badge-info ml-1"><?php echo e($concepto->periodo); ?></span>
                                                    <?php endif; ?>
                                                </div>

                                                <?php if($concepto->anoLectivo): ?>
                                                    <p class="text-sm text-muted mb-1">
                                                        <i class="fas fa-calendar-alt mr-1"></i>
                                                        Año: <?php echo e($concepto->anoLectivo->nombre ?? 'N/A'); ?>

                                                    </p>
                                                <?php endif; ?>

                                                <?php if($concepto->nivel): ?>
                                                    <p class="text-sm text-muted mb-0">
                                                        <i class="fas fa-graduation-cap mr-1"></i>
                                                        Nivel: <?php echo e($concepto->nivel->nombre); ?>

                                                    </p>
                                                <?php endif; ?>
                                            </div>

                                            <div class="card-footer bg-white border-0">
                                                <div class="d-flex justify-content-between">
                                                    <a href="<?php echo e(route('conceptospago.show', $concepto->concepto_id)); ?>"
                                                       class="btn btn-sm btn-outline-info">
                                                        <i class="fas fa-eye mr-1"></i>
                                                        Ver
                                                    </a>
                                                    <div>
                                                        <a href="<?php echo e(route('conceptospago.edit', $concepto->concepto_id)); ?>"
                                                           class="btn btn-sm btn-outline-warning mr-1">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form method="POST" action="<?php echo e(route('conceptospago.destroy', $concepto->concepto_id)); ?>"
                                                              style="display: inline-block;"
                                                              onsubmit="return confirm('¿Está seguro de que desea eliminar este concepto?')">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>

                            <!-- Paginación -->
                            <div class="d-flex justify-content-center mt-4">
                                <?php echo e($conceptos->links()); ?>

                            </div>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No se encontraron conceptos de pago</h5>
                                <?php if($buscarpor): ?>
                                    <p class="text-muted">No hay resultados para la búsqueda "<?php echo e($buscarpor); ?>".</p>
                                    <a href="<?php echo e(route('conceptospago.index')); ?>" class="btn btn-outline-primary">
                                        <i class="fas fa-times mr-1"></i>
                                        Limpiar búsqueda
                                    </a>
                                <?php else: ?>
                                    <p class="text-muted">Aún no hay conceptos de pago registrados.</p>
                                    <a href="<?php echo e(route('conceptospago.create')); ?>" class="btn btn-success">
                                        <i class="fas fa-plus mr-1"></i>
                                        Crear primer concepto
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <script>
                document.addEventListener('DOMContentLoaded', function () {
                    // Collapse icon toggle
                    const btn = document.querySelector('[data-target="#collapseConceptosPago"]');
                    const icon = btn.querySelector('.fas.fa-chevron-down');
                    const collapse = document.getElementById('collapseConceptosPago');
                    collapse.addEventListener('show.bs.collapse', function () {
                        icon.classList.remove('fa-chevron-down');
                        icon.classList.add('fa-chevron-up');
                    });
                    collapse.addEventListener('hide.bs.collapse', function () {
                        icon.classList.remove('fa-chevron-up');
                        icon.classList.add('fa-chevron-down');
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>

<style>
    /* Animación de entrada */
    @keyframes slideInLeft {
        from { opacity: 0; transform: translateX(-50px);}
        to { opacity: 1; transform: translateX(0);}
    }
    .animate-slide-in { animation: slideInLeft 0.8s ease-out; }

    /* Cards */
    .card {
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    /* Botón header estilo estudiantes */
    .btn_header.header_6 {
        margin-bottom: 0;
        border-radius: 0;
        font-size: 1.1rem;
        padding: 0.75rem 1rem;
        background: #28a745 !important;
        color: white;
        border: none;
        box-shadow: none;
    }
    .btn_header .float-right {
        float: right;
    }
    .btn_header i.fas.fa-chevron-down,
    .btn_header i.fas.fa-chevron-up {
        transition: transform 0.2s;
    }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('cplantilla.bprincipal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Ronaldo Robles\Music\eduka_11012026_Final_V6\resources\views/cpagos/conceptospago/index.blade.php ENDPATH**/ ?>