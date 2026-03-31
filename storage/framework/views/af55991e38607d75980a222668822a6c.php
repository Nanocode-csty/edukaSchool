<link rel="stylesheet" href="<?php echo e(asset('css/estilos-modulos.css')); ?>">
<?php $__env->startSection('titulo', 'Nueva Aula'); ?>

<?php $__env->startSection('contenidoplantilla'); ?>

<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">
                <i class="fas fa-plus-circle"></i> Registrar Nueva Aula
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

            
            <form action="<?php echo e(route('aulas.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>

                
                <div class="form-group mb-3">
                    <label for="nombre" class="fw-bold">Nombre del Aula</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" value="<?php echo e(old('nombre')); ?>" required>
                </div>

                
                <div class="form-group mb-3">
                    <label for="capacidad" class="fw-bold">Capacidad</label>
                    <input type="number" name="capacidad" id="capacidad" class="form-control" value="<?php echo e(old('capacidad')); ?>" required>
                </div>

                
                <div class="form-group mb-3">
                    <label for="ubicacion" class="fw-bold">Ubicación</label>
                    <input type="text" name="ubicacion" id="ubicacion" class="form-control" value="<?php echo e(old('ubicacion')); ?>">
                </div>

                
                <div class="form-group mb-3">
                    <label for="tipo" class="fw-bold">Tipo</label>
                    <select name="tipo" id="tipo" class="form-control" required>
                        <option value="">-- Seleccione un tipo --</option>
                        <option value="Regular" <?php echo e(old('tipo') == 'Regular' ? 'selected' : ''); ?>>Aula regular</option>
                        <option value="Laboratorio" <?php echo e(old('tipo') == 'Laboratorio' ? 'selected' : ''); ?>>Laboratorio</option>
                        <option value="Taller" <?php echo e(old('tipo') == 'Taller' ? 'selected' : ''); ?>>Sala de cómputo</option>
                        <option value="Auditorio" <?php echo e(old('tipo') == 'Auditorio' ? 'selected' : ''); ?>>Auditorio</option>
                        <option value="Otro" <?php echo e(old('tipo') == 'Otro' ? 'selected' : ''); ?>>Otro</option>
                    </select>
                </div>

                
                <div class="d-flex justify-content-between">
                    <a href="<?php echo e(route('aulas.index')); ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Registrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('cplantilla.bprincipal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/ronaldoroblesromero/Herd/edukaSchool/resources/views/ceinformacion/aulas/nuevo.blade.php ENDPATH**/ ?>