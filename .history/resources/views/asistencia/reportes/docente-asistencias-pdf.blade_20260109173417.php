<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Asistencias Filtradas</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 15px;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #0e4067;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .header h1 {
            color: #0e4067;
            margin: 0;
            font-size: 20px;
            font-weight: bold;
        }

        .header h2 {
            color: #28aece;
            margin: 5px 0;
            font-size: 16px;
        }

        .info-section {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }

        .info-row {
            display: table-row;
        }

        .info-label {
            display: table-cell;
            width: 120px;
            font-weight: bold;
            padding: 3px 8px 3px 0;
            background-color: #f8f9fa;
            font-size: 9px;
        }

        .info-value {
            display: table-cell;
            padding: 3px 0;
            font-size: 9px;
        }

        .stats-section {
            display: flex;
            justify-content: space-around;
            margin: 15px 0;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 6px;
        }

        .stat-item {
            text-align: center;
            flex: 1;
        }

        .stat-number {
            font-size: 18px;
            font-weight: bold;
            display: block;
        }

        .stat-presentes { color: #28a745; }
        .stat-ausentes { color: #dc3545; }
        .stat-tardes { color: #ffc107; }
        .stat-justificados { color: #17a2b8; }

        .stat-label {
            font-size: 8px;
            color: #6c757d;
            margin-top: 3px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 8px;
        }

        th, td {
            border: 1px solid #dee2e6;
            padding: 4px 6px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #0e4067;
            color: white;
            font-weight: bold;
            text-align: center;
            font-size: 9px;
        }

        tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .badge {
            padding: 2px 4px;
            border-radius: 3px;
            font-size: 7px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .badge-success { background-color: #28a745; color: white; }
        .badge-danger { background-color: #dc3545; color: white; }
        .badge-warning { background-color: #ffc107; color: black; }
        .badge-info { background-color: #17a2b8; color: white; }

        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            font-size: 8px;
            color: #6c757d;
        }

        .observaciones {
            font-style: italic;
            color: #6c757d;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Eduka Perú</h1>
        <h2>Reporte de Asistencias Filtradas</h2>
    </div>

    <div class="info-section">
        <div class="info-row">
            <div class="info-label">Fecha de Generación:</div>
            <div class="info-value">{{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Total de Registros:</div>
            <div class="info-value">{{ $asistencias->count() }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Docente:</div>
            <div class="info-value">{{ Auth::user()->persona->nombres ?? 'N/A' }} {{ Auth::user()->persona->apellidos ?? '' }}</div>
        </div>
    </div>

    <div class="stats-section">
        <div class="stat-item">
            <span class="stat-number stat-presentes">{{ $asistencias->where('tipo_asistencia_id', \App\Models\TipoAsistencia::where('codigo', 'P')->first()->id ?? 1)->count() }}</span>
            <div class="stat-label">Presentes</div>
        </div>
        <div class="stat-item">
            <span class="stat-number stat-ausentes">{{ $asistencias->where('tipo_asistencia_id', \App\Models\TipoAsistencia::where('codigo', 'A')->first()->id ?? 2)->count() }}</span>
            <div class="stat-label">Ausentes</div>
        </div>
        <div class="stat-item">
            <span class="stat-number stat-tardes">{{ $asistencias->where('tipo_asistencia_id', \App\Models\TipoAsistencia::where('codigo', 'T')->first()->id ?? 3)->count() }}</span>
            <div class="stat-label">Tardanzas</div>
        </div>
        <div class="stat-item">
            <span class="stat-number stat-justificados">{{ $asistencias->where('tipo_asistencia_id', \App\Models\TipoAsistencia::where('codigo', 'J')->first()->id ?? 4)->count() }}</span>
            <div class="stat-label">Justificados</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 35px;">#</th>
                <th style="width: 70px;">Fecha</th>
                <th>Estudiante</th>
                <th style="width: 60px;">Curso</th>
                <th>Asignatura</th>
                <th style="width: 70px;">Asistencia</th>
                <th>Observaciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($asistencias as $index => $asistencia)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td style="text-align: center;">{{ \Carbon\Carbon::parse($asistencia->fecha)->format('d/m/Y') }}</td>
                <td>{{ $asistencia->matricula->estudiante->nombres ?? 'N/A' }} {{ $asistencia->matricula->estudiante->apellidos ?? '' }}</td>
                <td style="text-align: center;">
                    {{ $asistencia->matricula->grado->nombre ?? 'N/A' }}
                    {{ $asistencia->matricula->seccion->nombre ?? '' }}
                </td>
                <td>{{ $asistencia->cursoAsignatura->asignatura->nombre ?? 'N/A' }}</td>
                <td style="text-align: center;">
                    <span class="badge badge-{{ $asistencia->tipoAsistencia->codigo == 'P' ? 'success' : ($asistencia->tipoAsistencia->codigo == 'A' ? 'danger' : ($asistencia->tipoAsistencia->codigo == 'T' ? 'warning' : 'info')) }}">
                        {{ $asistencia->tipoAsistencia->nombre ?? 'N/A' }}
                    </span>
                </td>
                <td class="observaciones">{{ $asistencia->observaciones ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center;">No hay registros de asistencia para los filtros aplicados</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Reporte generado por {{ Auth::user()->persona->nombres ?? 'N/A' }} {{ Auth::user()->persona->apellidos ?? '' }}</p>
        <p>Sistema Eduka Perú - Gestión Educativa</p>
    </div>
</body>
</html>