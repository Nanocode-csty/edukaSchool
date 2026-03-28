<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Acceso al sistema | Eduka Perú</title>
    <link rel="icon" href="{{ asset('imagenes/imgLogo.png') }}" type="image/png">
    <link rel="shortcut icon" href="{{ asset('imagenes/imgLogo.png') }}" type="image/png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Google Fonts: Roboto -->
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
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
                <span aria-valuetext="{{ session('email') }}">{{ session('email') }}</span>

            </div>

        </div>

        <!-- Right Panel -->
        <div class="right-panel">
            <form method="POST" action="{{ route('password') }}" autocomplete="off">
                @csrf

                <div class="google-input mt-1 mb-1">
                    <input id="password" type="password" name="password"
                        class="@error('password') is-invalid @enderror" placeholder=" " value="">
                    <label for="password">Ingresa tu contraseña</label>

                    @error('password')
                        <span id="passwordError" class="invalid-feedback d-block text-start"
                            style="font-size: small;">{{ $message }}</span>
                    @enderror

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

                {{-- Comentado para desarrollo en localhost --}}
                <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                @if ($errors->has('g-recaptcha-response'))
                    <span
                        class="invalid-feedback d-block text-start">{{ $errors->first('g-recaptcha-response') }}</span>
                @endif

                <div class="mt-3 d-flex justify-content-end align-items-center gap-4 d-grid">
                    <div class="text-muted">
                        <a href="{{ route('forgot') }}" style="color: #0E4678 !important">¿Olvidaste tu contraseña?</a>
                    </div>
                    <button id="btnAcces" type="submit" class="btn next-btn">
                        <span>Ingresar</span>
                    </button>
                </div>

            </form>
        </div>
    </div>
    {{-- Comentado para desarrollo en localhost --}}
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</body>

</html>
