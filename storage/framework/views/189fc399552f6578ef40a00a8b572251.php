<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['name', 'label', 'type' => 'text', 'options' => []]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['name', 'label', 'type' => 'text', 'options' => []]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div class="row mb-3 align-items-center">

    <label class="col-12 col-md-2 col-form-label">
        <?php echo e($label); ?>

    </label>

    <div class="col-12 col-md-10">

        
        <?php if($type === 'text' || $type === 'email' || $type === 'number'): ?>

            <input type="<?php echo e($type); ?>" name="<?php echo e($name); ?>" id="<?php echo e($name); ?>"
                value="<?php echo e(old($name)); ?>" placeholder="<?php echo e('Ingrese' . ' ' . $label); ?>"
                <?php echo e($attributes->merge(['class' => 'form-control ' . ($errors->has($name) ? 'is-invalid' : '')])); ?>>

            
        <?php elseif($type === 'select'): ?>
            <select name="<?php echo e($name); ?>" id="<?php echo e($name); ?>"
                <?php echo e($attributes->merge(['class' => 'form-control ' . ($errors->has($name) ? 'is-invalid' : '')])); ?>>

                <option value=""><?php echo e('Seleccione' . ' ' . $label); ?></option>

                <?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $text): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($value); ?>" <?php echo e(old($name) == $value ? 'selected' : ''); ?>>

                        <?php echo e($text); ?>


                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </select>

            
        <?php elseif($type === 'textarea'): ?>
            <textarea name="<?php echo e($name); ?>" id="<?php echo e($name); ?>"
                <?php echo e($attributes->merge(['class' => 'form-control ' . ($errors->has($name) ? 'is-invalid' : '')])); ?>><?php echo e(old($name)); ?></textarea>

            
        <?php elseif($type === 'file'): ?>
            <input type="file" name="<?php echo e($name); ?>" id="<?php echo e($name); ?>"
                <?php echo e($attributes->merge(['class' => 'form-control ' . ($errors->has($name) ? 'is-invalid' : '')])); ?>>

        <?php endif; ?>

        
        <?php $__errorArgs = [$name];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="invalid-feedback d-block text-start">
                <?php echo e($message); ?>

            </div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

        
        <?php echo e($slot); ?>


    </div>
</div>
<?php /**PATH /Users/ronaldoroblesromero/Herd/edukaSchool/resources/views/components/form/field.blade.php ENDPATH**/ ?>