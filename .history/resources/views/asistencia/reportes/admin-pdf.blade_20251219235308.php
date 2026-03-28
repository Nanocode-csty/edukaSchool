<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Asistencias - Administrador</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #007bff;
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }

        .header p {
            margin: 5px 0;
            color: #666;
        }

        .info-section {
            margin-bottom: 30px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }

        .info-section h3 {
            margin: 0 0 10px 0;
            color: #007bff;
            font-size: 16px;
        }

        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }

        .stats-row {
            display: table-row;
        }

        .stats-cell {
            display: table-cell;
            padding: 8px;
            text-align: center;
            border: 1px solid #dee2e6;
        }

        .stats-cell strong {
            font-size: 18px;
            color: #007bff;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table th,
        .table td {
            border: 1px solid #dee2e6;
            padding: 8px;
            text-align: left;
        }

        .table th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }

        .table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .status-presente {
            color: #28a745;
            font-weight: bold;
        }

        .status-ausente {
            color: #dc3545;
            font-weight: bold;
        }

        .status-tarde {
            color: #ffc107;
            font-weight: bold;
        }

        .status-justificado {
            color: #17a2b8;
            font-weight: bold;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            font-size: 10px;
            color: #666;
        }

        .periodo-info {
            background-color: #e9ecef;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Asistencias</h1>
        <p><strong>Institución Educativa</strong></p>
        <p>Reporte generado el {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="periodo-info">
        <strong>Período del Reporte:</strong> {{ \Carbon\Carbon::parse($request->fecha_inicio)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($request->fecha_fin)->format('d/m/Y') }}
        <br>
        <strong>Tipo de Reporte:</strong> General
        <br>
        <strong>Generado por:</strong> Administrador del Sistema
    </div>

    @if($asistencias->isNotEmpty())
        @php
            // Calcular estadísticas basadas en los datos del período seleccionado
            $totalRegistros = $asistencias->count();
            $totalPresentes = 0;
            $totalAusentes = 0;
            $totalTardanzas = 0;

            foreach($asistencias as $asistencia) {
                $codigo = isset($asistencia->tipoAsistencia->codigo) ? $asistencia->tipoAsistencia->codigo : 'N/A';
                switch($codigo) {
                    case 'A':
                        $totalPresentes++;
                        break;
                    case 'F':
                        $totalAusentes++;
                        break;
                    case 'T':
                        $totalTardanzas++;
                        break;
                }
            }

            $tasaAsistencia = $totalRegistros > 0 ? round((($totalRegistros - $totalAusentes) / $totalRegistros) * 100, 1) : 0;
        @endphp
        <div class="info-section">
            <h3>Resumen Estadístico</h3>
            <div class="stats-grid">
                <div class="stats-row">
                    <div class="stats-cell">
                        <strong>{{ $totalRegistros }}</strong><br>
                        <small>Total de Registros</small>
                    </div>
                    <div class="stats-cell">
                        <strong>{{ $totalPresentes }}</strong><br>
                        <small>Presentes</small>
                    </div>
                    <div class="stats-cell">
                        <strong>{{ $totalAusentes }}</strong><br>
                        <small>Ausentes</small>
                    </div>
                    <div class="stats-cell">
                        <strong>{{ $tasaAsistencia }}%</strong><br>
                        <small>Tasa de Asistencia</small>
                    </div>
                </div>
            </div>
        </div>

        <h3>Detalle de Asistencias</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Estudiante</th>
                    <th>Grado</th>
                    <th>Sección</th>
                    <th>Asignatura</th>
                    <th>Asistencia</th>
                </tr>
            </thead>
            <tbody>
                @foreach($asistencias as $index => $asistencia)
                @php
                    $codigoActual = isset($asistencia->tipoAsistencia->codigo) ? $asistencia->tipoAsistencia->codigo : 'N/A';
                @endphp
                <tr>
                    <td>{{ \Carbon\Carbon::parse($asistencia->fecha)->format('d/m/Y') }}</td>
                    <td>{{ $asistencia->matricula->estudiante->persona->nombres }} {{ $asistencia->matricula->estudiante->persona->apellidos }}</td>
                    <td>{{ $asistencia->matricula->grado->nombre }}</td>
                    <td>{{ $asistencia->matricula->seccion->nombre }}</td>
                    <td>{{ $asistencia->cursoAsignatura->asignatura->nombre }}</td>
                    <td>
                        @if($codigoActual == 'A')
                            <span class="status-presente">✓ Asistió</span>
                        @elseif($codigoActual == 'F')
                            <span class="status-ausente">✗ Falta</span>
                        @elseif($codigoActual == 'T')
                            <span class="status-tarde">⚠ Tarde</span>
                        @elseif($codigoActual == 'J')
                            <span class="status-justificado">✓ Justificado</span>
                        @else
                            <span style="color: red;">{{ $codigoActual }} (Desconocido)</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">
            <h3>No se encontraron registros de asistencia</h3>
            <p>Para el período seleccionado no existen datos de asistencia en el sistema.</p>
        </div>
    @endif

    <div class="footer">
        <p>Reporte generado automáticamente por el Sistema de Gestión Educativa</p>
        <p>Reporte completo - {{ $asistencias->count() }} registros</p>
    </div>
</body>
</html>
