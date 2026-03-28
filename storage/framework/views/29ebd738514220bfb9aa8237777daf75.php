<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Acceso al sistema | Eduka Perú</title>
    <link rel="icon" href="<?php echo e(asset('imagenes/imgLogo.png')); ?>" type="image/png">
    <link rel="shortcut icon" href="<?php echo e(asset('imagenes/imgLogo.png')); ?>" type="image/png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Para el icono de Google -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts: Roboto -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400" rel="stylesheet">
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
            <h2 class="mt-3">Inicia sesión</h2>
            <p>Usa tu Cuenta Institucional</p>
        </div>

        <!-- Right Panel -->
        <div class="right-panel">
            <form method="POST" action="<?php echo e(route('identificacion')); ?>">
                <?php echo csrf_field(); ?>

                <div class="google-input mt-1">
                    <input id="email" type="text" name="email" class="<?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        placeholder=" " value="<?php echo e(old('email')); ?>" autocomplete="username">
                    <label for="email">Correo electrónico o usuario</label>

                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span id="emailError" class="invalid-feedback d-block text-start"
                            style="font-size: small;"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>


                <div class="mb-5 text-muted">
                    <a href="https://romeros-pe.web.app" target="_blank"
                        style="color: #084e90 !important; font-size:small">Si olvidaste tu correo, comunícate con el
                        administrador.</a>
                </div>

                <p class="text-muted" style="font-size: 0.86rem;">
                    ¡Estimado Usuario! Bienvenido al <b>Sistema Intranet</b> de Eduka Perú Oficial.
                    <a href="https://romeros-pe.web.app" target="_blank" style="color: #084e90 !important;">Explora
                        nuestra plataforma y descubre los servicios que tenemos para ti.</a>
                </p>

                <div class="d-flex justify-content-end gap-3 align-items-center">

                    <a id="google-btn" href="<?php echo e(route('google.login')); ?>" class="btn google-btn">
                        <i class="fab fa-google mx-2"></i> Acceder con Google
                    </a>

                    <button id="btnSiguiente" type="submit" class="btn next-btn">
                        <span>Siguiente</span>
                    </button>
                </div>

            </form>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Función para prevenir espacios
            function evitarEspacios(input) {
                input.addEventListener('keydown', function(e) {
                    if (e.key === ' ') {
                        e.preventDefault();
                    }
                });
            }

            // Lista de IDs a los que se les aplicará la validación
            const sinEspacios = [
                'email',
            ];

            // Aplicamos la función a todos los elementos por ID
            sinEspacios.forEach(id => {
                const input = document.getElementById(id);
                if (input) {
                    evitarEspacios(input);
                }
            });
        });
    </script>
    <?php if(session('error')): ?>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: "info",
                    title: "¡Estimado Usuario!",
                    text: "<?php echo e(session('error')); ?>",
                    footer: '<a href="https://romeros-pe.web.app" target="_blank" rel="noopener noreferrer">eduka.edu.pe</a>',
                    scrollbarPadding: false, // evita cambios por el scrollbar
                    heightAuto: false, // evita que SweetAlert cambie el tamaño/alto del body
                    backdrop: true,
                    allowEscapeKey: true,
                    allowOutsideClick: true,
                    didOpen: () => {
                        // opcional: si quieres quitar el foco automático en inputs
                        const input = document.querySelector('.swal2-input');
                        if (input) input.blur();
                    }
                });
            });
        </script>
    <?php endif; ?>
</body>

</html>
<?php /**PATH /Users/ronaldoroblesromero/Herd/edukaSchool/resources/views/clogin/usuario.blade.php ENDPATH**/ ?>