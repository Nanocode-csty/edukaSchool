<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 25px;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        header img {
            height: 40px;
        }

        .titulo h2 {
            font-size: 16px;
            margin: 0;
        }

        .titulo p {
            font-size: 10px;
            margin: 2px 0 0;
            text-align: right;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10.5px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            vertical-align: top;
        }

        th {
            background-color: #f0f0f0;
            text-align: center;
        }

        .direccion {
            font-style: italic;
            font-size: 10px;
        }

        /* Colores alternados para representantes */
        .fila-par {
            background-color: #fffff9;
        }

        .fila-impar {
            background-color: #ffffff;
        }

        footer {
            position: fixed;
            bottom: 10px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
        }

        .page-number:after {
            content: counter(page);
        }
    </style>
</head>

<body>

    <header>
        <img src="{{ public_path('img_eduka.png') }}" alt="Logo Eduka">
        <div class="titulo">
            <h2>Lista de Representantes Legales</h2>
            <p>Fecha: {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
        </div>
    </header>

    <table>
        <thead>
            <tr>
                <th>DNI</th>
                <th>Nombres</th>
                <th>Apellidos</th>
                <th>Parentesco</th>
                <th>Teléfono</th>
                <th>Tel. Alternativo</th>
                <th>Correo</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($representante as $index => $rep)
                <tr class="{{ $index % 2 == 0 ? 'fila-par' : 'fila-impar' }}">
                    <td>{{ $rep->dni }}</td>
                    <td>{{ $rep->nombres }}</td>
                    <td>{{ $rep->apellidos }}</td>
                    <td>{{ $rep->parentesco }}</td>
                    <td>{{ $rep->telefono }}</td>
                    <td>{{ !empty($rep->telefono_alternativo) ? $rep->telefono_alternativo : 'S/N' }}</td>

                    <td>{{ $rep->email }}</td>
                </tr>
                <tr class="{{ $index % 2 == 0 ? 'fila-par' : 'fila-impar' }}">
                    <td colspan="7" class="direccion"><strong>Dirección:</strong> {{ $rep->direccion }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <footer>
        Página <span class="page-number"></span>
    </footer>

</body>

</html>
