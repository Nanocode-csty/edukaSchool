<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asistencia - {{ $sesion->cursoAsignatura->asignatura->nombre }}</title>
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
            border-bottom: 2px solid #28a745;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .header h1 {
            color: #28a745;
            font-size: 18px;
            margin: 0;
            font-weight: bold;
        }

        .header p {
            margin: 5px 0;
            font-size: 11px;
        }

        .session-info {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
            border-left: 4px solid #28a745;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
        }

        .info-label {
            font-weight: bold;
            color: #495057;
        }

        .stats-section {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #e9ecef;
            border-radius: 5px;
        }

        .stats-simple {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin: 15px 0;
        }

        .stat-simple {
            text-align: center;
            font-size: 11px;
        }

        .stat-simple strong {
            color: #28a745;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 9px;
        }

        th, td {
            border: 1px solid #dee2e6;
            padding: 8px 6px;
            text-align: left;
        }

        th {
            background-color: #28a745;
            color: white;
            font-weight: bold;
            text-align: center;
            font-size: 10px;
        }

        tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .attendance-present {
            color: #28a745;
            font-weight: bold;
            text-align: center;
        }

        .attendance-absent {
            color: #dc3545;
            font-weight: bold;
            text-align: center;
        }

        .attendance-late {
            color: #ffc107;
            font-weight: bold;
            text-align: center;
        }

        .attendance-justified {
            color: #17a2b8;
            font-weight: bold;
            text-align: center;
        }

        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            font-size: 8px;
            color: #6c757d;
        }

        .signature-section {
            margin-top: 40px;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 50px;
        }

        .signature-box {
            text-align: center;
            border-top: 1px solid #333;
            padding-top: 10px;
            width: 200px;
            margin: 0 auto;
        }

        .signature-label {
            font-size: 10px;
            margin-bottom: 30px;
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
        <p><strong>Asignatura:</strong> {{ $sesion->cursoAsignatura->asignatura->nombre }}</p>
        <p><strong>Curso:</strong> {{ $sesion->cursoAsignatura->curso->grado->nombre }} {{ $sesion->cursoAsignatura->curso->seccion->nombre }}</p>
        <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($sesion->fecha)->locale('es')->dayName }}, {{ \Carbon\Carbon::parse($sesion->fecha)->format('d/m/Y') }}</p>
        <p><strong>Hora:</strong> {{ substr($sesion->hora_inicio, 0, 5) }} - {{ substr($sesion->hora_fin, 0, 5) }}</p>
    </div>

    <div class="session-info">
        <h3 style="margin: 0 0 15px 0; color: #28a745; font-size: 14px;">Información de la Sesión</h3>
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Profesor:</span>
                <span>{{ $sesion->cursoAsignatura->profesor->persona->nombres }} {{ $sesion->cursoAsignatura->profesor->persona->apellidos }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Aula:</span>
                <span>{{ $sesion->aula->nombre ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Tipo de Sesión:</span>
                <span>{{ $sesion->tipo_sesion ?? 'Normal' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Estado:</span>
                <span>{{ $sesion->estado }}</span>
            </div>
        </div>
    </div>

    @php
        $totalEstudiantes = $sesion->cursoAsignatura->curso->matriculas()->where('estado', 'Matriculado')->count();
        $asistenciasRegistradas = $asistencias->count();
        $presentes = $asistencias->where('tipoAsistencia.computa_falta', 0)->count();
        $ausentes = $asistencias->where('tipoAsistencia.computa_falta', 1)->count();
        $tarde = $asistencias->where('tipoAsistencia.codigo', 'T')->count();
        $justificados = $asistencias->where('tipoAsistencia.codigo', 'J')->count();
    @endphp

    <div class="stats-section">
        <div class="stats-simple">
            <div class="stat-simple">
                <strong>{{ $presentes }}</strong><br>Presentes
            </div>
            <div class="stat-simple">
                <strong>{{ $ausentes }}</strong><br>Ausentes
            </div>
            <div class="stat-simple">
                <strong>{{ max(0, $totalEstudiantes - $asistenciasRegistradas) }}</strong><br>Sin Registrar
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 30%;">Estudiante</th>
                <th style="width: 15%;">DNI</th>
                <th style="width: 20%;">Tipo Asistencia</th>
                <th style="width: 15%;">Estado</th>
                <th style="width: 15%;">Observaciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sesion->cursoAsignatura->curso->matriculas()->with('estudiante.persona')->where('estado', 'Matriculado')->get() as $index => $matricula)
            <tr>
                <td style="text-align: center; font-weight: bold;">{{ $index + 1 }}</td>
                <td>
                    {{ $matricula->estudiante->persona->nombres }} {{ $matricula->estudiante->persona->apellidos }}
                </td>
                <td style="text-align: center;">{{ $matricula->estudiante->persona->dni }}</td>
                <td style="text-align: center;">
                    @php
                        $asistenciaEstudiante = $asistencias->get($matricula->matricula_id);
                    @endphp
                    @if($asistenciaEstudiante)
                        <span class="
                            @if($asistenciaEstudiante->tipoAsistencia->codigo == 'A' || $asistenciaEstudiante->tipoAsistencia->codigo == 'P')
                                attendance-present
                            @elseif($asistenciaEstudiante->tipoAsistencia->codigo == 'F')
                                attendance-absent
                            @elseif($asistenciaEstudiante->tipoAsistencia->codigo == 'T')
                                attendance-late
                            @elseif($asistenciaEstudiante->tipoAsistencia->codigo == 'J')
                                attendance-justified
                            @endif
                        ">
                            {{ $asistenciaEstudiante->tipoAsistencia->nombre }}
                        </span>
                    @endif
                </td>
                <td style="text-align: center;">
                    @if($asistenciaEstudiante)
                        @if($asistenciaEstudiante->tipoAsistencia->computa_falta == 0)
                            <span class="attendance-present">✓ Presente</span>
                        @else
                            <span class="attendance-absent">✗ Ausente</span>
                        @endif
                    @endif
                </td>
                <td>
                    {{ $asistenciaEstudiante ? ($asistenciaEstudiante->justificacion ?? '') : '' }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="signature-section">
        <div class="signature-box">
            <div class="signature-label">Profesor</div>
            <div style="margin-top: 20px; font-size: 11px;">
                {{ $sesion->cursoAsignatura->profesor->persona->nombres }} {{ $sesion->cursoAsignatura->profesor->persona->apellidos }}
            </div>
        </div>
        <div class="signature-box">
            <div class="signature-label">Director/Coordinador</div>
            <div style="margin-top: 20px; font-size: 11px; color: #999;">
                _________________________
            </div>
        </div>
    </div>

    <div class="footer">
        <p>Reporte generado por el Sistema Educativo EDUKA</p>
        <p>Sesión registrada el: {{ \Carbon\Carbon::parse($sesion->created_at)->format('d/m/Y H:i:s') }}</p>
        <p>Reporte generado el: {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>
