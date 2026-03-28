<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Ficha del Estudiante</title>
    <style>
        .form-bordered {
            margin: 0;
            border: none;
            padding-top: 5px;
            padding-bottom: 15px;
            border-bottom: 1px dashed #eaedf1;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #000;
            margin: 40px;
        }

        .encabezado {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .logo {
            width: 80px;
        }

        .titulo {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin: 20px 0;
        }

        .subtitulo {
            font-weight: bold;
            font-size: 14px;
            background-color: #f0f0f0;
            padding: 5px;
            margin-top: 10px;
            margin-bottom: 5px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .info-table td {
            padding: 5px 10px;
            vertical-align: top;
        }

        .foto {
            width: 120px;
            height: 150px;
            object-fit: cover;
            border: 1px solid #000;
        }

        .label {
            font-weight: bold;
        }

        /* ===== FOOTER FIJO ===== */
        .footer {
            position: fixed;
            bottom: 20px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 12px;
            color: #555;
            border-top: 1px solid #999;
            padding-top: 5px;

        }
    </style>
</head>

<body>
    <div class="encabezado">
        <div style="width: 80px; text-align: center; font-weight: bold; font-size: 18px;">EDUKA</div>

        <div style="flex: 1; text-align: center;">
            <div style="font-size: 16px; font-weight: bold;">Institución Educativa Eduka Perú S. A.</div>
            <div style="font-size: 13px;">San Borja, Lima - Tel: 963150918 - asistencia@eduka.edu.pe</div>
        </div>
        <div style="width: 80px;"></div>
    </div>

    <div class="titulo">FICHA PERSONAL DEL ESTUDIANTE</div>

    @php
        use Carbon\Carbon;
    @endphp

    <table class="info-table">
        <tr>
            <td style="width: 70%;">
                <div class="subtitulo">Datos Personales</div>
                <p><span class="label">DNI:</span> {{ $estudiante->dni }}</p>
                <p><span class="label">Nombres:</span> {{ $estudiante->nombres }}</p>
                <p><span class="label">Apellidos:</span> {{ $estudiante->apellidos }}</p>
                <p><span class="label">Fecha de Nacimiento:</span> {{ $estudiante->fecha_nacimiento }}</p>
                <p><span class="label">Género:</span> {{ $estudiante->genero == 'M' ? 'Masculino' : 'Femenino' }}</p>
                <p><span class="label">Dirección:</span> {{ $estudiante->direccion ?? '-' }}</p>
                <p><span class="label">Celular:</span>
                    {{ Str::substr($estudiante->telefono, 0, 3) . ' ' . Str::substr($estudiante->telefono, 3, 3) . ' ' . Str::substr($estudiante->telefono, 6, 3) ?? '-' }}
                </p>
                <p><span class="label">Correo:</span> {{ $estudiante->email ?? '-' }}</p>
            </td>
            <td style="width: 30%; text-align: center; vertical-align: middle;">
                <div style="width: 120px; height: 150px; border: 1px solid #000; display: flex; align-items: center; justify-content: center; background-color: #f5f5f5; font-size: 12px;">
                    FOTO DEL ESTUDIANTE
                </div>
            </td>
        </tr>
    </table>

    @if (count($representantes) > 0)
        <div class="subtitulo">Representantes Registrados</div>
        <table class="info-table">
            @foreach ($representantes as $index => $r)
                <tr class="">

                    <td style=" width: 100%; background-color:rgba(241, 246, 250, 0.769) !important">
                        <b>Representante
                                {{ $index + 1 }}</b>
                    </td>
                    <td style="width: 100%; background-color:rgba(241, 246, 250, 0.769)  !important">
                        <b></b>

                    </td>
                </tr>
                <tr>
                    <td style="width: 50%;">
                        <p><span class="label">Nombre:</span> {{ $r->nombres }} {{ $r->apellidos }}</p>
                        <p><span class="label">Parentesco:</span> {{ $r->parentesco }}</p>
                        <p><span class="label">DNI:</span> {{ $r->dni }}</p>
                    </td>
                    <td style="width: 50%;">
                        <p><span class="label">Teléfono:</span>
                            {{ Str::substr($r->telefono, 0, 3) . ' ' . Str::substr($r->telefono, 3, 3) . ' ' . Str::substr($r->telefono, 6, 3) }}
                        </p>
                        <p><span class="label">Correo:</span> {{ $r->email ?? '-' }}</p>
                        <p><span class="label">Dirección:</span> {{ $r->direccion ?? '-' }}</p>
                    </td>
                </tr>
            @endforeach
        </table>
    @endif

    {{-- FOOTER FIJO --}}
    <div class="footer">
        <p>© {{ date('Y') }} - Sistema Educativo Eduka</p>
        <p>Generado el {{ Carbon::now()->format('d/m/Y H:i') }}</p>
    </div>
</body>

</html>


