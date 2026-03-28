<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acceso al sistema | ROMERO'S Perú</title>
    <link rel="icon" type="image/png" href="{{ asset('images\favicon.png') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- AdminLTE -->
    <link rel="stylesheet" href="/adminlte/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="/adminlte/dist/css/adminlte.min.css">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css">

    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            background-color: #000;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container-login {
            width: 100%;
            max-width: 400px;
        }
        .login-card {
            
            background: #1c1c1c;
            padding: 2.5rem;
            border-radius: 0.7rem;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.6);
            width: 100%;
            max-width: 400px;
            text-align: center;
            color: #fff;
        }
        .login-card img {
            width: 150px;
            margin-bottom: 1.5rem;
        }
        .login-card h4 {
            margin-bottom: 2rem;
            font-weight: bold;
        }
        .input-group-text {
            background-color: #2c2c2c;
            border: none;
            color: #ffffff;
        }
        .form-control {
            background-color: #2c2c2c;
            border: none;
            color: #ffffff;
        }

        .form-control::placeholder {
            color: #999999;
        }

        .btn-primary {
        margin-top: 1rem;
        background-color: #FF4343 !important;
        border: none;
        transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn-primary:hover {
            background-color: #FF4343 !important;
            transform: scale(1.02);
        }

        .btn-outline-secondary:hover {
            background-color: #404040 !important;
            transform: scale(1.02);
        }

        .btn-primary:active {
            transform: scale(0.98);
            background-color: #FF2121 !important; /* aún más oscuro al presionar */
        }
        
        .copyright {
            text-align: center;
            margin-top: 2rem;
            font-size: 0.85rem;
            color: #aaaaaa;
        }
        
    </style>
</head>

<body>

    <div class="container-login">
        <div class="login-card">
            ROMEROS
            <h5>Iniciar Sesión</h5>

            <form method="POST" action="{{ route('identificacion') }}">
                @csrf

                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fas fa-user"></i>
                        </span>
                    </div>
                    <input type="text" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Correo" value="{{ old('email') }}">
                    @error('email')
                        <span class="invalid-feedback d-block text-start">{{ $message }}</span>
                    @enderror
                </div>

                <div class="input-group mb-3 position-relative">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fas fa-lock"></i>
                        </span>
                    </div>
                    <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Contraseña">
                    <div class="input-group-append">
                        <span class="input-group-text" onclick="togglePassword()" style="cursor: pointer;">
                            <i class="fas fa-eye" id="togglePasswordIcon"></i>
                        </span>
                    </div>
                    @error('password')
                        <span class="invalid-feedback d-block text-start">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="d-grid mb-3">
                    <div class="d-grid gap-2">
                    <!-- Botón Ingresar -->
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Ingresar</span>
                    </button>

                    <!-- Botón Regresar -->
                    <a href="#" class="btn btn-outline-secondary btn-block" style="display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Regresar</span>
                    </a>
                </div>
                </div>

                
            </form>

            <div class="copyright">
                &copy;2025 ROMERO'S Perú&trade;
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="/adminlte/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="/adminlte/dist/js/adminlte.min.js"></script>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('togglePasswordIcon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>

</body>
</html>
