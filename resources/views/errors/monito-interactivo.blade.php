<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>¡Ups! Error {{ $error }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
            text-align: center;
            padding: 50px;
        }
        .monito {
            width: 200px;
            margin: 20px auto;
        }
        h1 {
            font-size: 60px;
            color: #dc3545;
        }
        p {
            font-size: 20px;
        }
        a.btn {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h1>{{ $error }}</h1>
    <img class="monito" src="{{ asset('imagenes/monito_travieso.png') }}" alt="Monito Travieso">
    <p>Parece que algo salió mal 😅</p>
    <a href="{{ url('/') }}" class="btn btn-primary">Ir al inicio</a>
</body>
</html>
