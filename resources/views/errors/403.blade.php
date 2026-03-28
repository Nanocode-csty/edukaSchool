<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>¡Ups! Página no encontrada</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg,#f5f7fa,#c3cfe2);
            text-align: center;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
        }
        .monito-container {
            width: 80%;
            max-width: 500px;
        }
        .monito {
            width: 100%;
            height: auto;
            animation: bounce 1.2s infinite alternate;
        }
        @keyframes bounce {
            0% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0); }
        }
        .mensaje-error {
            font-size: 1.8rem;
            font-weight: bold;
            margin-top: 20px;
            color: #333;
        }
        .submensaje {
            color: #555;
            margin-top: 10px;
        }
        .btn-home {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="monito-container">
        <img class="monito" src="{{ asset('imagenes/monito.svg') }}" alt="Monito travieso">
        <div class="mensaje-error">¡Página no encontrada!</div>
        <div class="submensaje">La ruta que buscas no existe.</div>
        <a href="{{ url('/') }}" class="btn btn-primary btn-home">Volver al inicio</a>
    </div>
</body>
</html>
