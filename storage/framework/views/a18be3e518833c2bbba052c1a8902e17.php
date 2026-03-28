<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Acceso al sistema | Eduka Perú</title>
    <link rel="icon" href="<?php echo e(asset('imagenes/imgLogo.png')); ?>" type="image/png">
    <link rel="shortcut icon" href="<?php echo e(asset('imagenes/imgLogo.png')); ?>" type="image/png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Google Fonts: Roboto -->
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo e(asset('css/login.css')); ?>">
</head>

<body>

    <div class="login-wrapper">
        <!-- Left Panel -->
        <div class="left-panel mt-1">
            <img src="img_eduka.png" alt="Eduka" class="img-fluid no-copy" style="max-height: 54px;"
                draggable="false" oncontextmenu="return false;" ondragstart="return false;" style="user-select: none;">
            <h2 class="mt-3">Bienvenido a tu intranet</h2>

            <div class="d-inline-flex align-items-center border rounded-pill px-3 py-1 mt-2"
                style="max-width: 100%; border-radius: 0.7rem !important">
                <i class="fas fa-user-tie me-2"></i>
                <span aria-valuetext="<?php echo e(session('email')); ?>"><?php echo e(session('email')); ?></span>

            </div>

        </div>

        <!-- Right Panel -->
        <div class="right-panel">
            <form method="POST" action="<?php echo e(route('password')); ?>" autocomplete="off">
                <?php echo csrf_field(); ?>

                <div class="google-input mt-1 mb-1">
                    <input id="password" type="password" name="password"
                        class="<?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder=" " value="">
                    <label for="password">Ingresa tu contraseña</label>

                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span id="passwordError" class="invalid-feedback d-block text-start"
                            style="font-size: small;"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                </div>
                <input type="checkbox" id="showPassword" onclick="togglePassword()" class="mx-1">
                <label for="showPassword" class="ms-2 mb-4" style="font-size: small">Mostrar contraseña</label>

                <script>
                    function togglePassword() {
                        const passwordInput = document.getElementById('password');
                        const checkbox = document.getElementById('showPassword');

                        // Sincronizar el estado del input con el checkbox
                        if (checkbox.checked) {
                            passwordInput.type = 'text';
                        } else {
                            passwordInput.type = 'password';
                        }

                    }
                </script>

                
                <div class="g-recaptcha" data-sitekey="<?php echo e(config('services.recaptcha.site_key')); ?>"></div>
                <?php if($errors->has('g-recaptcha-response')): ?>
                    <span
                        class="invalid-feedback d-block text-start"><?php echo e($errors->first('g-recaptcha-response')); ?></span>
                <?php endif; ?>

                <div class="mt-3 d-flex justify-content-end align-items-center gap-4 d-grid">
                    <div class="text-muted">
                        <a href="<?php echo e(route('forgot')); ?>" style="color: #0E4678 !important">¿Olvidaste tu contraseña?</a>
                    </div>
                    <button id="btnAcces" type="submit" class="btn next-btn">
                        <span>Ingresar</span>
                    </button>
                </div>

            </form>
        </div>
    </div>
    
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</body>

</html>
<?php /**PATH C:\Users\Ronaldo Robles\Music\eduka_11012026_Final_V6\resources\views/clogin/password.blade.php ENDPATH**/ ?>