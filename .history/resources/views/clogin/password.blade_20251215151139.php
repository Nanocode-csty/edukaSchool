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
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css">

    <style>
        body {
            background-color: #f6f4f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: 'Poppins', sans-serif;

        }

        /* Cuando el ancho de pantalla sea menor o igual a 576px (Bootstrap sm) */
        @media (max-width: 576px) {
            body {
                background-color: #ffffff;
            }
        }

        .no-copy {
            pointer-events: none;
            user-select: none;
        }

        .login-wrapper {
            background-color: white;
            border-radius: 1.5rem;
            width: 100%;
            max-width: 1100px;
            display: flex;
            flex-direction: row;
            padding: 3rem;
        }

        .left-panel {
            flex: 1;
            padding-right: 2rem;
        }

        .right-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-control {
            border-radius: 0.5rem;
            height: 3rem;
        }

        .btn-primary {
            border-radius: 0.7rem;
            padding: 0.56rem 2.4rem;
            background-color: #104E87 !important;
            border: none;
            font-weight: bold;
            margin-right: 0.1%;
        }

        .btn-primary:hover {
            background-color: #0E4678;
        }

        .text-muted a {
            text-decoration: none;
            font-size: 0.9rem;
        }

        .text-muted a:hover {
            text-decoration: underline;
        }

        .bottom-links {
            font-size: 0.85rem;
            margin-top: 2rem;
            color: #5f6368;
        }

        @media (max-width: 768px) {
            .login-wrapper {
                flex-direction: column;
                padding: 2rem;
            }

            .left-panel {
                padding-right: 0;
                margin-bottom: 2rem;
            }
        }
    </style>
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

                <style>
                    /* Contenedor personalizado estilo Google */
                    .google-input {
                        position: relative;
                        margin-bottom: 0.1rem;
                        font-family: 'Poppins', sans-serif;
                    }

                    /* Input base */
                    .google-input input {
                        width: 100%;
                        padding: 1rem 0.75rem 0.25rem 0.75rem;
                        font-size: 16px;
                        border: 1px solid #ccc;
                        border-radius: .5rem;
                        background: #fff;
                        outline: none;
                        transition: border-color 0.3s ease;
                    }

                    /* Borde cuando está en focus */
                    .google-input input:focus {
                        border-color: #084e90;
                        box-shadow: 0 0 0 1px #084e90;
                    }

                    /* Label flotante */
                    .google-input label {
                        position: absolute;
                        top: 50%;
                        left: 0.75rem;
                        transform: translateY(-50%);
                        color: #777;
                        font-size: 0.9rem;
                        pointer-events: none;
                        transition: all 0.2s ease;

                    }

                    /* Efecto al escribir o enfocar */
                    .google-input input:focus+label,
                    .google-input input:not(:placeholder-shown)+label {
                        top: 0.1rem;
                        font-size: 0.75rem;
                        color: #084e90;
                        background: #fff;
                        padding: 0 0.25rem;
                        font-weight: bold;
                    }
                </style>

                <div class="google-input mt-1 mb-1">
                    <input id="password" type="password" name="password"
                        class="@error('password') is-invalid @enderror" placeholder=" " value="{{ old('password') }}">
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

                <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                @if ($errors->has('g-recaptcha-response'))
                    <span
                        class="invalid-feedback d-block text-start">{{ $errors->first('g-recaptcha-response') }}</span>
                @endif

                <div class="mt-3 d-flex justify-content-end align-items-center gap-4 d-grid">
                    <div class="text-muted">
                        <a href="{{ route('forgot') }}" style="color: #0E4678 !important">¿Olvidaste tu contraseña?</a>
                    </div>
                    <button id="btnAcces" type="submit" class="btn btn-primary next-btn" hidden>
                        <span>Ingresar</span>
                    </button>
                </div>
                <script>
                    const password2 = document.getElementById('password');
                    const btnNext = document.getElementById('btnAcces');
                    const passwordError1 = document.getElementById('passwordError');

                    password2.addEventListener('input', () => {
                        if (password2.value.trim() !== "") {

                            btnNext.hidden = false;
                            if (passwordError1) { // Verifica que exista antes de remover
                                passwordError1.remove();

                            }

                        } else {
                            btnNext.hidden = true;
                        }
                    });
                </script>
            </form>
            <style>
                /* Animación Google */
                .next-btn {
                    opacity: 1;
                    transform: translateY(0);
                    transition: all 0.4s ease-in-out;
                }

                /* Botón Siguiente Pro */
                .next-btn {
                    border-radius: 0.7rem;
                    font-weight: bold;

                    transition: all 0.3s ease;

                }

                .next-btn.hide {
                    opacity: 0;
                    transform: translateY(-15px);
                    pointer-events: none;
                }

                .next-btn:hover {
                    background-color: #1a3e65 !important;
                    transform: scale(1.01);

                }

                /* Efecto pulso */
                .next-btn:hover span {
                    animation: pulse 1s infinite;
                }

                @keyframes pulse {
                    0% {
                        text-shadow: 0 0 0px #fff;
                    }

                    50% {
                        text-shadow: 0 0 8px #fff;
                    }

                    100% {
                        text-shadow: 0 0 0px #fff;
                    }
                }
            </style>
        </div>
    </div>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</body>

</html>
