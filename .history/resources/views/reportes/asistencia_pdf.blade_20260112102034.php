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
        <p><strong>Generado:</strong> {{ \Carbon\Carbon::parse($reporte->fecha_generacion)->format('d/m/Y H:i:s') }}</p>
    </div>

    <div class="filters-section">
        <h3 style="margin: 0 0 8px 0; color: #0A8CB3; font-size: 12px;">Filtros Aplicados:</h3>
        <div style="font-size: 9px; line-height: 1.3;">
            @php
                $filtrosAplicados = [];
                if (isset($filtros['estudiante_id']) && $filtros['estudiante_id']) {
                    $estudiante = DB::table('estudiantes as e')
                        ->join('personas as p', 'e.persona_id', '=', 'p.id_persona')
                        ->where('e.estudiante_id', $filtros['estudiante_id'])
                        ->selectRaw("CONCAT(p.nombres, ' ', p.apellidos) as nombre_completo")
                        ->first();
                    $filtrosAplicados[] = 'Estudiante: ' . ($estudiante ? $estudiante->nombre_completo : 'ID ' . $filtros['estudiante_id']);
                }
                if (isset($filtros['grado_id']) && $filtros['grado_id']) {
                    $grado = DB::table('grados')->where('grado_id', $filtros['grado_id'])->first();
                    $filtrosAplicados[] = 'Grado: ' . ($grado ? $grado->descripcion : 'ID ' . $filtros['grado_id']);
                }
                if (isset($filtros['docente_id']) && $filtros['docente_id']) {
                    $docente = DB::table('profesores as pr')
                        ->join('personas as p', 'pr.persona_id', '=', 'p.id_persona')
                        ->where('pr.profesor_id', $filtros['docente_id'])
                        ->selectRaw("CONCAT(p.nombres, ' ', p.apellidos) as nombre_completo")
                        ->first();
                    $filtrosAplicados[] = 'Docente: ' . ($docente ? $docente->nombre_completo : 'ID ' . $filtros['docente_id']);
                }
                if (isset($filtros['asignatura_id']) && $filtros['asignatura_id']) {
                    $asignatura = DB::table('asignaturas')->where('asignatura_id', $filtros['asignatura_id'])->first();
                    $filtrosAplicados[] = 'Asignatura: ' . ($asignatura ? $asignatura->nombre : 'ID ' . $filtros['asignatura_id']);
                }
                if (empty($filtrosAplicados)) {
                    $filtrosAplicados[] = 'Sin filtros adicionales';
                }
            @endphp
            @foreach($filtrosAplicados as $filtro)
                <strong>{{ $filtro }}</strong><br>
            @endforeach
        </div>
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
        <div class="info-row">
            <span class="info-label">Total Registros:</span>
            <span>{{ $totalRegistros }}</span>
        </div>
    </div>

    @if(isset($filtros['incluir_estadisticas']) && $filtros['incluir_estadisticas'])
    <div class="stats-section">
        <h3 style="margin: 0 0 10px 0; color: #0A8CB3; font-size: 14px;">Estadísticas del Período</h3>
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-value">{{ $totalRegistros }}</div>
                <div class="stat-label">Total Registros</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $totalPresentes }}</div>
                <div class="stat-label">Presentes</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $totalAusentes }}</div>
                <div class="stat-label">Ausentes</div>
            </div>
        </div>
        <div style="margin-top: 15px; font-size: 11px; color: #666;">
            <strong>Porcentaje de asistencia:</strong> {{ $totalRegistros > 0 ? round(($totalPresentes / $totalRegistros) * 100, 1) : 0 }}%
        </div>
    </div>
    @endif

    @if(isset($filtros['incluir_graficos']) && $filtros['incluir_graficos'])
    <div style="page-break-before: always; margin-top: 20px;">
        <h3 style="margin: 0 0 15px 0; color: #0A8CB3; font-size: 14px;">Gráfico de Asistencia</h3>
        <div style="text-align: center; margin: 20px 0;">
            <div style="display: inline-block; border: 1px solid #ddd; padding: 20px; background: #f9f9f9;">
                <div style="font-size: 12px; margin-bottom: 10px;">Distribución de Asistencia</div>
                <div style="display: flex; align-items: end; justify-content: center; height: 150px; gap: 40px;">
                    <div style="text-align: center;">
                        <div style="width: 60px; height: {{ $totalRegistros > 0 ? round(($totalPresentes / $totalRegistros) * 120) : 0 }}px; background: #28a745; margin: 0 auto 5px auto; border-radius: 3px 3px 0 0;"></div>
                        <div style="font-size: 10px; color: #28a745; font-weight: bold;">Presentes</div>
                        <div style="font-size: 9px;">{{ $totalPresentes }}</div>
                    </div>
                    <div style="text-align: center;">
                        <div style="width: 60px; height: {{ $totalRegistros > 0 ? round(($totalAusentes / $totalRegistros) * 120) : 0 }}px; background: #dc3545; margin: 0 auto 5px auto; border-radius: 3px 3px 0 0;"></div>
                        <div style="font-size: 10px; color: #dc3545; font-weight: bold;">Ausentes</div>
                        <div style="font-size: 9px;">{{ $totalAusentes }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div style="font-size: 10px; color: #666; text-align: center; margin-top: 10px;">
            * Este gráfico es una representación visual simple de los datos de asistencia
        </div>
    </div>
    @endif

    <table>
        <thead>
            <tr>
                <th style="width: 8%;">Fecha</th>
                <th style="width: 25%;">Estudiante</th>
                <th style="width: 12%;">Grado</th>
                <th style="width: 10%;">Sección</th>
                <th style="width: 20%;">Asignatura</th>
                <th style="width: 15%;">Tipo Asistencia</th>
                <th style="width: 10%;">Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($asistencias as $asistencia)
            <tr>
                <td style="text-align: center;">{{ \Carbon\Carbon::parse($asistencia->fecha)->format('d/m/Y') }}</td>
                <td>{{ $asistencia->estudiante ?? 'N/A' }}</td>
                <td style="text-align: center;">{{ $asistencia->grado ?? 'N/A' }}</td>
                <td style="text-align: center;">{{ $asistencia->seccion ?? 'N/A' }}</td>
                <td>{{ $asistencia->asignatura ?? 'N/A' }}</td>
                <td style="text-align: center;">{{ $asistencia->tipo_asistencia ?? 'N/A' }}</td>
                <td style="text-align: center;">
                    @if(isset($asistencia->computa_falta) && $asistencia->computa_falta == 0)
                        <span style="color: #28a745; font-weight: bold;">✓</span>
                    @else
                        <span style="color: #dc3545; font-weight: bold;">✗</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Reporte generado por el Sistema Educativo EDUKA</p>
        <p>Fecha de generación: {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>
