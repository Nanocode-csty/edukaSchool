<div style="font-family: Arial, sans-serif; ...">
    <div style="text-align: center;">
        <img src="{{ $message->embed($logoPath) }}" alt="Eduka Perú" style="max-width: 160px;">
    </div>

    <h2>Solicitud desde Eduka</h2>

    <p><strong>Nombre:</strong> {{ $data['name'] }}</p>
    <p><strong>Email:</strong> {{ $data['email'] }}</p>
    <p><strong>Mensaje:</strong><br> {!! nl2br(e($data['message'])) !!}</p>

    <hr>
    <p style="font-size: 12px; color: #999;">© {{ date('Y') }} Eduka Perú</p>
</div>
