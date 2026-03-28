<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Asistencia - {{ $estudiante->persona->nombres }} {{ $estudiante->persona->apellidos }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #007bff;
            margin-bottom: 10px;
        }
        .info-section {
            margin-bottom: 30px;
        }
        .info-section h3 {
            color: #495057;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 5px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }
        .info-item {
            display: flex;
        }
        .info-label {
            font-weight: bold;
            width: 150px;
            color: #495057;
        }
        .stats-section {
            margin-bottom: 30px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }
        .stat-card {
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            text-align: center;
            background: #f8f9fa;
        }
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
        }
        .stat-label {
            font-size: 14px;
            color: #6c757d;
            margin-top: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #dee2e6;
            padding: 8px 12px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #495057;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Asistencia</h1>
        <h2>{{ $estudiante->persona->nombres }} {{ $estudiante->persona->apellidos }}</h2>
        <p>Período: {{ $mes }}/{{ $anio }}</p>
    </div>

    <div class="info-section">
        <h3>Información del Estudiante</h3>
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Estudiante:</span>
                <span>{{ $estudiante->persona->nombres }} {{ $estudiante->persona->apellidos }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">DNI:</span>
                <span>{{ $estudiante->persona->dni ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Curso:</span>
                <span>{{ $estudiante->matricula && $estudiante->matricula->curso ? $estudiante->matricula->curso->grado->nombre . ' ' . $estudiante->matricula->curso->seccion->nombre : 'N/A' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Año Lectivo:</span>
                <span>{{ $estudiante->matricula && $estudiante->matricula->curso && $estudiante->matricula->curso->anoLectivo ? $estudiante->matricula->curso->anoLectivo->nombre : 'N/A' }}</span>
            </div>
        </div>
    </div>

    <div class="stats-section">
        <h3>Estadísticas del Período</h3>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">{{ $estadisticas['presentes'] }}</div>
                <div class="stat-label">Presente</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $estadisticas['ausentes'] }}</div>
                <div class="stat-label">Ausente</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $estadisticas['tardes'] }}</div>
                <div class="stat-label">Tarde</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $estadisticas['justificados'] }}</div>
                <div class="stat-label">Justificado</div>
            </div>
        </div>
        <p><strong>Total de registros:</strong> {{ $estadisticas['total'] }}</p>
    </div>

    <div class="info-section">
        <h3>Resumen Ejecutivo</h3>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">{{ $estadisticas['total'] }}</div>
                <div class="stat-label">Total Días</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ number_format(($estadisticas['presentes'] / max($estadisticas['total'], 1)) * 100, 1) }}%</div>
                <div class="stat-label">Asistencia</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $estadisticas['ausentes'] }}</div>
                <div class="stat-label">Días Ausente</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $estadisticas['justificados'] }}</div>
                <div class="stat-label">Justificados</div>
            </div>
        </div>
    </div>

    <div class="info-section">
        <h3>Análisis del Período</h3>
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Período evaluado:</span>
                <span>{{ $mes }}/{{ $anio }} ({{ $estadisticas['total'] }} días)</span>
            </div>
            <div class="info-item">
                <span class="info-label">Días con asistencia:</span>
                <span>{{ $estadisticas['presentes'] }} ({{ number_format(($estadisticas['presentes'] / max($estadisticas['total'], 1)) * 100, 1) }}%)</span>
            </div>
            <div class="info-item">
                <span class="info-label">Días ausentes:</span>
                <span>{{ $estadisticas['ausentes'] }} ({{ number_format(($estadisticas['ausentes'] / max($estadisticas['total'], 1)) * 100, 1) }}%)</span>
            </div>
            <div class="info-item">
                <span class="info-label">Días justificados:</span>
                <span>{{ $estadisticas['justificados'] }} ({{ number_format(($estadisticas['justificados'] / max($estadisticas['total'], 1)) * 100, 1) }}%)</span>
            </div>
        </div>

        @if($estadisticas['tardes'] > 0)
        <div class="info-item" style="margin-top: 15px;">
            <span class="info-label">Días con tardanza:</span>
            <span>{{ $estadisticas['tardes'] }}</span>
        </div>
        @endif
    </div>

    @if($justificaciones->count() > 0)
    <div class="info-section">
        <h3>Historial de Justificaciones</h3>
        <table>
            <thead>
                <tr>
                    <th>Fecha Solicitud</th>
                    <th>Fecha Falta</th>
                    <th>Motivo</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($justificaciones as $justificacion)
                <tr>
                    <td>{{ $justificacion->created_at ? \Carbon\Carbon::parse($justificacion->created_at)->format('d/m/Y H:i') : 'N/A' }}</td>
                    <td>{{ $justificacion->fecha ? \Carbon\Carbon::parse($justificacion->fecha)->format('d/m/Y') : 'N/A' }}</td>
                    <td>{{ $justificacion->motivo ?? 'N/A' }}</td>
                    <td>{{ ucfirst($justificacion->estado ?? 'pendiente') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <div class="footer">
        <p>Reporte generado el {{ now()->format('d/m/Y H:i:s') }} por {{ $representante->persona->nombres ?? 'Sistema' }}</p>
    </div>
</body>
</html>
