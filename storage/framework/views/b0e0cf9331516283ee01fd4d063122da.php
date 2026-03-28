

<?php $__env->startSection('titulo', 'Nuevo Año Lectivo'); ?>

<?php $__env->startSection('contenidoplantilla'); ?>
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">
                <i class="fas fa-calendar-plus me-2"></i> Registrar Año Lectivo
            </h4>
        </div>

        <div class="card-body">
            
            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo e(session('error')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            <?php endif; ?>

            <?php if($errors->any()): ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            <?php endif; ?>

            
            <form action="<?php echo e(route('aniolectivo.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>

                
                <div class="form-group mb-3">
                    <label for="nombre" class="fw-bold">Nombre del Año Lectivo</label>
                    <input type="text" name="nombre" id="nombre" class="form-control"
                        value="<?php echo e(old('nombre')); ?>" required maxlength="100">
                </div>

                
                <div class="form-group mb-3">
                    <label for="fecha_inicio" class="fw-bold">Fecha de Inicio</label>
                    <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control"
                        value="<?php echo e(old('fecha_inicio')); ?>" required>
                </div>

                
                <div class="form-group mb-3">
                    <label for="fecha_fin" class="fw-bold">Fecha de Fin</label>
                    <input type="date" name="fecha_fin" id="fecha_fin" class="form-control"
                        value="<?php echo e(old('fecha_fin')); ?>" required>
                </div>

                
                <div class="form-group mb-3">
                    <label for="estado" class="fw-bold">Estado</label>
                    <select name="estado" id="estado" class="form-control" required>
                         <option value="">-- Seleccione un estado --</option>
                        <option value="Activo" <?php echo e(old('estado', $anolectivo->estado ?? '') == 'Activo' ? 'selected' : ''); ?>>Activo</option>
                        <option value="Planificación" <?php echo e(old('estado') == 'Planificación' ? 'selected' : ''); ?>>Planificación</option>
                        <option value="Finalizado" <?php echo e(old('estado', $anolectivo->estado ?? '') == 'Finalizado' ? 'selected' : ''); ?>>Finalizado</option>
                    </select>
                </div>

                
                <div class="form-group mb-4">
                    <label for="descripcion" class="fw-bold">Descripción (opcional)</label>
                    <textarea name="descripcion" id="descripcion" rows="3" class="form-control"
                        maxlength="500"><?php echo e(old('descripcion')); ?></textarea>
                </div>

                
                <div class="d-flex justify-content-between">
    <a href="<?php echo e(route('aniolectivo.index')); ?>" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Cancelar
    </a>
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-save"></i> Registrar Año Lectivo
    </button>
</div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('cplantilla.bprincipal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Ronaldo Robles\Music\eduka_11012026_Final_V6\resources\views/ceinformacion/añolectivo/nuevo.blade.php ENDPATH**/ ?>