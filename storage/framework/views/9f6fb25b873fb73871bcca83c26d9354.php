<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Recuperar Contraseña | Eduka Perú</title>
    <link rel="icon" href="<?php echo e(asset('imagenes/imgLogo.png')); ?>" type="image/png">
    <link rel="shortcut icon" href="<?php echo e(asset('imagenes/imgLogo.png')); ?>" type="image/png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo e(asset('css/login.css')); ?>">
</head>

<body>

    <div class="login-wrapper">
        <!-- LEFT PANEL -->
        <div class="left-panel mt-1">
            <img src="img_eduka.png" alt="Eduka" class="img-fluid no-copy" style="max-height: 54px;"
                draggable="false" oncontextmenu="return false;" ondragstart="return false;">

            <h2 class="mt-3">Recuperar Contraseña</h2>

            <div class="d-inline-flex align-items-center border rounded-pill px-3 py-1 mt-2"
                style="border-radius: 0.7rem !important">
                <i class="fas fa-user-tie me-2"></i>
                <span aria-valuetext="<?php echo e(session('email')); ?>"><?php echo e(session('email')); ?></span>
            </div>
        </div>

        <!-- RIGHT PANEL -->
        <div class="right-panel">

            <!-- Banner informativo -->
            <div class="alert alert-primary d-flex align-items-center rounded-3 shadow-sm py-3 px-4 mb-4"
                style="background-color:#e9f2ff; border-left:5px solid #104E87;">
                <i class="fas fa-envelope-open-text me-3" style="font-size:1.5rem; color:#104E87;"></i>
                <div>
                    <strong>Estimado Usuario:</strong><br>
                    Usaremos tu dirección de correo para enviarte una nueva contraseña con la que podras acceder
                    nuevamente
                    a tu Sistema Intranet.
                </div>
            </div>

            <form method="POST" action="<?php echo e(route('sendpassword')); ?>" autocomplete="off">
                <?php echo csrf_field(); ?>
                <!-- Botón -->
                <div class="mt-2 d-flex justify-content-end">
                    <button id="btnEnviar" type="submit" class="btn btn-primary px-4 py-2">
                        <i class="fas fa-paper-plane me-2"></i> Enviar nueva contraseña
                    </button>
                </div>

            </form>

        </div>
    </div>

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

</body>

</html>
<?php /**PATH /Users/ronaldoroblesromero/Herd/edukaSchool/resources/views/clogin/recuperar.blade.php ENDPATH**/ ?>