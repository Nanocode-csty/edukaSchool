<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Asistencia - {{ $reporte->tipo_reporte }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', 'Arial', sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #0A8CB3;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header h1 {
            color: #0A8CB3;
            font-size: 18px;
            margin: 0;
            font-weight: bold;
        }

        .header p {
            margin: 5px 0;
            font-size: 12px;
        }

        .info-section {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .info-label {
            font-weight: bold;
            width: 120px;
        }

        .stats-section {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #e9ecef;
            border-radius: 5px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }

        .stat-item {
            text-align: center;
            padding: 8px;
            background-color: white;
            border-radius: 3px;
            border: 1px solid #dee2e6;
        }

        .stat-value {
            font-size: 16px;
            font-weight: bold;
            color: #0A8CB3;
        }

        .stat-label {
            font-size: 10px;
            color: #6c757d;
            margin-top: 2px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 8px;
        }

        th, td {
            border: 1px solid #dee2e6;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #0A8CB3;
            color: white;
            font-weight: bold;
            text-align: center;
        }

        tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        tr:hover {
            background-color: #e9ecef;
        }

        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            font-size: 8px;
            color: #6c757d;
        }

        .page-break {
            page-break-before: always;
        }

        @media print {
            body {
                margin: 0;
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Asistencia</h1>
        <p><strong>Tipo:</strong> {{ ucfirst($reporte->tipo_reporte) }}</p>
        <p><strong>Período:</strong> {{ \Carbon\Carbon::parse($filtros['fecha_inicio'])->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($filtros['fecha_fin'])->format('d/m/Y') }}</p>
        <p><strong>Generado:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
    </div>

    <div class="info-section">
        <div class="info-row">
            <span class="info-label">Usuario:</span>
            <span>{{ Auth::user()->persona ? Auth::user()->persona->nombres . ' ' . Auth::user()->persona->apellidos : Auth::user()->username }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Formato:</span>
            <span>PDF</span>
        </div>
