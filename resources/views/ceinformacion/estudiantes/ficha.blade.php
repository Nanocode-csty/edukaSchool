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
            text-align: left;
            font-size: 12px;
            color: #7f7c7c;
            border-top: 1px solid #999;
            padding-top: 5px;

        }
    </style>
</head>

<body>


    <div class="encabezado">
        <img src="{{ public_path('imagenes/imgLogo.png') }}" alt="Logo" style="width: 36px; height: auto;">

        <div style="flex: 1; text-align: center;">
            <div style="font-size: 16px; font-weight: bold;">Institución Educativa Eduka Perú S. A.</div>
            <div style="font-size: 13px;">Los Paujiles, Trujillo - Tel: 963150918 - soporte@eduka.edu.pe</div>
        </div>
        <div style="width: 80px;"></div>
    </div>

    <div class="titulo">FICHA PERSONAL DEL ESTUDIANTE</div>
    @php
        use Carbon\Carbon;
        use Illuminate\Support\Str;

        // --- FOTO DEL ESTUDIANTE ---
        $rutaFotoStorage = storage_path('app/public/estudiantes/' . ($estudiante->foto_url ?? ''));
        $fotoExiste = isset($estudiante->foto_url) && file_exists($rutaFotoStorage);
        $fotoUrl = $fotoExiste
            ? public_path('storage/estudiantes/' . $estudiante->foto_url)
            : public_path('imagenes/imgEstudiante.png');
    @endphp


    <table class="info-table">
        <tr>
            <td style="width: 70%;">
                <div class="subtitulo">Datos Personales</div>
                <p><span class="label">DNI:</span> {{ $estudiante->persona->dni }}</p>
                <p><span class="label">Nombres:</span> {{ $estudiante->persona->nombres }}</p>
                <p><span class="label">Apellidos:</span> {{ $estudiante->persona->apellidos_compleatos }}</p>
                <p><span class="label">Fecha de Nacimiento:</span> {{ $estudiante->persona->fecha_nacimiento }}</p>
                <p><span class="label">Género:</span> {{ $estudiante->persona->genero_convertido }}</p>
                <p><span class="label">Dirección:</span> {{ $estudiante->persona->direccion_completa ?? '-' }}</p>
                <p><span class="label">Celular:</span>{{ $estudiante->persona->telefono_formato ?? '-' }}</p>
                <p><span class="label">Correo:</span> {{ $estudiante->persona->email ?? '-' }}</p>
            </td>
            <td style="width: 30%; text-align: right;">
                <img src="{{ $fotoUrl }}" class="foto" alt="Foto del estudiante">


            </td>

        </tr>
    </table>


    <div class="subtitulo">Representantes Registrados</div>
    <table class="info-table">
        @forelse ($representantes as $index => $r)
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
                    <p><span class="label">Nombre:</span> {{ $r->persona->nombre_completo }}</p>
                    <p><span class="label">Parentesco:</span> {{ $r->parentesco }}</p>
                    <p><span class="label">DNI:</span> {{ $r->persona->dni }}</p>
                </td>
                <td style="width: 50%;">
                    <p><span class="label">Teléfono:</span>
                        {{ $r->persona->telefono_formato }}
                    </p>
                    <p><span class="label">Correo:</span> {{ $r->persona->email ?? '-' }}</p>
                    <p><span class="label">Dirección:</span> {{ $r->persona->direccion_completa?? '-' }}</p>
                </td>
            </tr>
        @empty
            <tr>
                <td>
                    No se registraron representantes para este estudiante.
                </td>
            </tr>
        @endforelse
    </table>


    {{-- FOOTER FIJO --}}
    <div class="footer "
        style="position:fixed; bottom:0; left:0; right:0; height:90px; border-top:1px solid #ccc; font-size:9px;">

        <!-- Texto institucional -->
        <div style="position:absolute; left:20px;  line-height:1.4;">
            <div><strong>Firmado digitalmente por Eduka Perú S. A.</strong></div>

            <div>Fecha de emisión: {{ Carbon::now()->format('d/m/Y H:i') }}</div>

            Código de seguridad: <b>{{ $codigo }}</b><br>

        </div>

        <!-- QR perfectamente alineado -->
        <div style="position:absolute; right:15px;  text-align:center;">
            <img class="mt-2" src="data:image/svg+xml;base64,{{ $qr }}" style="width:70px; height:70px;">
            <div style="font-size:7px; margin-top:2px; color:#444;">Ficha Verificada</div>
        </div>

    </div>

</body>

</html>
