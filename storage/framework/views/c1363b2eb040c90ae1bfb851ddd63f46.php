
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo $__env->yieldContent('title', 'Eduka'); ?></title>
    <link rel="icon" href="<?php echo e(asset('imagenes/imgLogo.png')); ?>" type="image/png">
    <link rel="shortcut icon" href="<?php echo e(asset('imagenes/imgLogo.png')); ?>" type="image/png">
    <!-- 🎨 Bootstrap 5 SOLO para Eduka -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Fuente Eduka -->
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Quicksand', sans-serif !important;
        }
    </style>
</head>
<body>
    <div class="eduka-wrapper">
        <?php echo $__env->yieldContent('content'); ?>
    </div>

    <!-- 🚀 Bootstrap 5 Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH /Users/ronaldoroblesromero/Herd/edukaSchool/resources/views/layout/eduka.blade.php ENDPATH**/ ?>