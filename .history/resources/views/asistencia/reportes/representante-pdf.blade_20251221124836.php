<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Asistencia - {{ $estudiante->nombres }} {{ $estudiante->apellidos }}</title>
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
            border-bottom: 2px solid #0e4067;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #0e4067;
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }

        .header h2 {
            color: #28aece;
            margin: 5px 0;
            font-size: 18px;
        }

        .info-section {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }

        .info-row {
            display: table-row;
        }

        .info-label {
            display: table-cell;
            width: 150px;
            font-weight: bold;
            padding: 5px 10px 5px 0;
            background-color: #f8f9fa;
        }

        .info-value {
            display: table-cell;
            padding: 5px 0;
        }

        .stats-section {
            display: flex;
            justify-content: space-around;
            margin: 20px 0;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 8px;
        }

        .stat-item {
            text-align: center;
            flex: 1;
        }

        .stat-number {
            font-size: 24px;
            font-weight: bold;
            display: block;
        }

        .stat-presentes { color: #28a745; }
        .stat-ausentes { color: #dc3545; }
        .stat-tardes { color: #ffc107; }
        .stat-justificados { color: #17a2b8; }

        .stat-label {
            font-size: 11px;
            color: #6c757d;
            margin-top: 5px;
        }

        .periodo-info {
            background-color: #e9ecef;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #dee2e6;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #0e4067;
            color: white;
            font-weight: bold;
            text-align: center;
        }

        tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .badge-success { background-color: #28a745; color: white; }
        .badge-danger { background-color: #dc3545; color: white; }
        .badge-warning { background-color: #ffc107; color: black; }
        .badge-info { background-color: #17a2b8; color: white; }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            font-size: 10px;
            color: #6c757d;
        }

        .observaciones {
            font-style: italic;
            color: #6c757d;
        }

        .justificaciones-section {
            margin-top: 30px;
            page-break-before: always;
        }

        .justificaciones-section h3 {
            color: #0e4067;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Eduka Perú</h1>
        <h2>Reporte de Asistencia del Estudiante</h2>
    </div>

    <div class="info-section">
        <div class="info-row">
            <div class="info-label">Estudiante:</div>
            <div class="info-value">{{ $estudiante->nombres }} {{ $estudiante->apellidos }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">DNI:</div>
            <div class="info-value">{{ $estudiante->dni }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Curso:</div>
            <div class="info-value">{{ $estudiante->matricula->curso->grado->nombre ?? 'N/A' }} {{ $estudiante->matricula->seccion->nombre ?? 'N/A' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Año Lectivo:</div>
            <div class="info-value">{{ $estudiante->matricula->curso->anoLectivo->nombre ?? 'N/A' }}</div>
        </div>
    </div>

    <div class="periodo-info">
        Período: {{ \Carbon\Carbon::create()->month($mes)->format('F') }} {{ $anio }}
    </div>

    <div class="stats-section">
        <div class="stat-item">
            <span class="stat-number stat-presentes">{{ $asistencias->where('tipo_asistencia_id', \App\Models\TipoAsistencia::where('codigo', 'P')->first()->id ?? 1)->count() }}</span>
            <div class="stat-label">Presente</div>
        </div>
        <div class="stat-item">
            <span class="stat-number stat-ausentes">{{ $asistencias->where('tipo_asistencia_id', \App\Models\TipoAsistencia::where('codigo', 'A')->first()->id ?? 2)->count() }}</span>
            <div class="stat-label">Ausente</div>
        </div>
        <div class="stat-item">
            <span class="stat-number stat-tardes">{{ $asistencias->where('tipo_asistencia_id', \App\Models\TipoAsistencia::where('codigo', 'T')->first()->id ?? 3)->count() }}</span>
            <div class="stat-label">Tarde</div>
        </div>
        <div class="stat-item">
            <span class="stat-number stat-justificados">{{ $asistencias->where('tipo_asistencia_id', \App\Models\TipoAsistencia::where('codigo', 'J')->first()->id ?? 4)->count() }}</span>
            <div class="stat-label">Justificado</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 50px;">#</th>
                <th style="width: 100px;">Fecha</th>
                <th style="width: 80px;">Día</th>
                <th>Asignatura</th>
                <th>Docente</th>
                <th style="width: 120px;">Asistencia</th>
                <th>Observaciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($asistencias as $index => $asistencia)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td style="text-align: center;">{{ \Carbon\Carbon::parse($asistencia->fecha)->format('d/m/Y') }}</td>
                <td style="text-align: center;">{{ \Carbon\Carbon::parse($asistencia->fecha)->format('D') }}</td>
                <td>{{ $asistencia->sesionClase->cursoAsignatura->asignatura->nombre ?? 'N/A' }}</td>
                <td>{{ $asistencia->sesionClase->cursoAsignatura->docente->nombres ?? '' }} {{ $asistencia->sesionClase->cursoAsignatura->docente->apellidos ?? '' }}</td>
                <td style="text-align: center;">
                    <span class="badge badge-{{ getBadgeClass($asistencia->tipo_asistencia->codigo) }}">
                        {{ $asistencia->tipo_asistencia->nombre }}
                    </span>
                </td>
                <td class="observaciones">{{ $asistencia->observaciones ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center;">No hay registros de asistencia para el período seleccionado</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if(isset($justificaciones) && $justificaciones->count() > 0)
    <div class="justificaciones-section">
        <h3>Historial de Justificaciones</h3>
        <table>
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th style="width: 120px;">Fecha Solicitud</th>
                    <th style="width: 100px;">Fecha Falta</th>
                    <th>Motivo</th>
                    <th style="width: 120px;">Estado</th>
                    <th style="width: 120px;">Fecha Revisión</th>
                </tr>
            </thead>
            <tbody>
                @foreach($justificaciones as $index => $justificacion)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td style="text-align: center;">{{ \Carbon\Carbon::parse($justificacion->fecha_solicitud)->format('d/m/Y') }}</td>
                    <td style="text-align: center;">{{ \Carbon\Carbon::parse($justificacion->fecha_falta)->format('d/m/Y') }}</td>
                    <td>{{ $justificacion->motivo }}</td>
                    <td style="text-align: center;">
                        <span class="badge badge-{{ getJustificacionBadgeClass($justificacion->estado) }}">
                            {{ $justificacion->estado }}
                        </span>
                    </td>
                    <td style="text-align: center;">{{ $justificacion->fecha_revision ? \Carbon\Carbon::parse($justificacion->fecha_revision)->format('d/m/Y') : '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <div class="footer">
        <p>Reporte generado el {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
        <p>Sistema Eduka Perú - Gestión Educativa</p>
    </div>
</body>
</html>

@php
function getBadgeClass($codigo) {
    switch($codigo) {
        case 'P': return 'success';
        case 'A': return 'danger';
        case 'T': return 'warning';
        case 'J': return 'info';
        default: return 'secondary';
    }
}

function getJustificacionBadgeClass($estado) {
    switch($estado) {
        case 'Aprobada': return 'success';
        case 'Rechazada': return 'danger';
        case 'Pendiente': return 'warning';
        default: return 'secondary';
    }
}
@endphp
