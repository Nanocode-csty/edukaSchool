<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>¡Ups! Página no encontrada</title>
    <link rel="icon" href="{{ asset('imagenes/imgLogo.png') }}" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Hace que el SVG sea el fondo */
        body {
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', sans-serif;
            text-align: center;
            background: linear-gradient(135deg,#f5f7fa,#c3cfe2);
            position: relative;
            overflow: hidden;
        }

        /* Fondo SVG */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: url('{{ asset("imagenes/monito.svg") }}') no-repeat center center;
            background-size: cover; /* Se ajusta a toda la pantalla */
            z-index: -1; /* Detrás del contenido */
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
            margin-top: 540px;
        }
    </style>
</head>
<body>
    <div class="monito-container">
        <a href="{{ route('rutarrr1') }}" class="btn btn-primary btn-home" style="background-color: rgb(234, 185, 23); color: white; font-weight:bolder; border:none; border-radius:20px!important">Volver al inicio</a>
    </div>
</body>
</html>
