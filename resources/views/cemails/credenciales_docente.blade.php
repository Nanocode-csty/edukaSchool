<div style="font-family: Arial, sans-serif; max-width: 600px; margin: auto; background: #ffffff; border-radius: 10px; border: 1px solid #ccc; padding: 30px;">
    <div style="text-align: center;">
        <img src="{{ $message->embed($logoPath) }}" alt="Eduka Perú" style="max-width: 160px;">


    </div>

    <h2 style="text-align: center; color: #2b2b2b;">Bienvenido a Eduka</h2>

    <p>Estimado/a docente <strong>{{ $nombre }}</strong>,</p>

    <p>Su acceso al sistema ha sido creado exitosamente. A continuación encontrará sus credenciales de ingreso:</p>

    <ul style="font-size: 15px; line-height: 1.6;">
        <li><strong>📧 Usuario:</strong> {{ $correo }}</li>
        <li><strong>🔐 Contraseña temporal:</strong> {{ $password }}</li>
    </ul>

    <p>Por motivos de seguridad, le recomendamos cambiar su contraseña al ingresar al sistema por primera vez.</p>

    <p style="margin-top: 30px;">Si tiene algún inconveniente, puede contactarnos directamente.</p>

    <p style="text-align: center; margin-top: 35px; color: #777;">
        © {{ date('Y') }} Eduka Perú. Todos los derechos reservados.
    </p>
</div>
