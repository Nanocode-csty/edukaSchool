{{-- resources/views/asistencia/index.blade.php --}}
@extends('cplantilla.bprincipal')

@section('titulo', 'Gestión de Asistencia')

@section('contenidoplantilla')
    <style>
        /* Modern Design System Variables */
        :root {
            --primary-color: #0A8CB3;
            --primary-dark: #087299;
            --primary-light: #E0F7FA;
            --success-color: #28a745;
            --success-light: #f0fff4;
            --danger-color: #dc3545;
            --danger-light: #fff5f5;
            --warning-color: #ffc107;
            --warning-light: #fffbf0;
            --info-color: #17a2b8;
            --info-light: #d1ecf1;
            --secondary-color: #6c757d;
            --secondary-light: #f8f9fa;
            --dark-color: #343a40;
            --light-color: #f8f9fa;
            --border-radius: 12px;
            --border-radius-sm: 8px;
            --border-radius-lg: 16px;
            --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.06);
            --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.15);
            --transition-fast: all 0.2s ease;
            --transition-normal: all 0.3s ease;
            --transition-slow: all 0.5s ease;
        }

        /* Typography */
        .estilo-info {
            font-family: "Quicksand", sans-serif;
            font-weight: 700;
            font-size: clamp(0.9rem, 2.0vw, 0.9rem) !important;
            color: var(--dark-color);
        }

        /* Modern Card Design */
        .modern-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-sm);
            border: 1px solid rgba(0, 0, 0, 0.08);
            transition: var(--transition-normal);
            overflow: hidden;
        }

        .modern-card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        .modern-card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 1.25rem 1.5rem;
            border: none;
            position: relative;
        }

        .modern-card-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }

        .modern-card-header > * {
            position: relative;
            z-index: 1;
        }

        .modern-card-body {
            padding: 1.5rem;
        }

        /* Statistics Cards */
        .stats-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-sm);
            border: none;
            transition: var(--transition-normal);
            overflow: hidden;
            position: relative;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--primary-color);
        }

        .stats-card.presentes::before { background: var(--success-color); }
        .stats-card.ausentes::before { background: var(--danger-color); }
        .stats-card.tardanzas::before { background: var(--warning-color); }

        .stats-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .stats-card .card-body {
            padding: 1.5rem;
            position: relative;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: white;
        }

        .stat-icon.presentes { background: var(--success-color); }
        .stat-icon.ausentes { background: var(--danger-color); }
        .stat-icon.tardanzas { background: var(--warning-color); }

        .stats-card h3 {
            font-weight: 700;
            margin-bottom: 0.25rem;
            font-size: 1.75rem;
        }

        .stats-card p {
            font-weight: 600;
            color: var(--secondary-color);
            margin-bottom: 0.5rem;
        }

        .progress-modern {
            height: 8px;
            border-radius: 4px;
            background: rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .progress-modern .progress-bar {
            background: linear-gradient(90deg, var(--primary-color), var(--primary-dark));
            transition: width 0.8s ease;
        }

        /* Session Cards */
        .session-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-sm);
            border: 1px solid rgba(0, 0, 0, 0.08);
            transition: var(--transition-normal);
            overflow: hidden;
            margin-bottom: 1rem;
        }

        .session-card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        .session-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 1.25rem 1.5rem;
            position: relative;
        }

        .session-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.1);
        }

        .session-header > * {
            position: relative;
            z-index: 1;
        }

        .session-status {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .session-status.activo {
            background: rgba(40, 167, 69, 0.2);
            color: var(--success-color);
        }

        .session-status.pendiente {
            background: rgba(108, 117, 125, 0.2);
            color: var(--secondary-color);
        }

        .session-status.finalizado {
            background: rgba(255, 193, 7, 0.2);
            color: var(--warning-color);
        }

        .session-time {
            background: rgba(255, 255, 255, 0.15);
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.85rem;
            backdrop-filter: blur(10px);
        }

        .session-body {
            padding: 1.5rem;
        }

        .session-info {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

                        <div class="card card-body rounded-0 border-0 pt-3 pb-2"
                            style="background-color: #fcfffc !important">

                            <!-- Header con fecha y acciones -->
                            <div class="row align-items-center mb-3">
                                <div class="col-md-4">
                                    <h5 class="estilo-info mb-2">
                                        <i class="far fa-calendar text-primary"></i>
                                        {{ Carbon\Carbon::parse($fecha)->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                                    </h5>
                                </div>
                                <div class="col-md-4 d-flex justify-content-center">
                                    <a href="{{ route('asistencia.reporte-general') }}" class="btn btn-outline-primary">
                                        <i class="fas fa-chart-pie"></i> Reporte General
                                    </a>
                                </div>
                                <div class="col-md-4 d-flex justify-content-md-end justify-content-start">
                                    <div class="fecha-selector-container">
                                        <label class="mb-0 estilo-info">Seleccionar fecha:</label>
                                        <input type="date" id="fecha-selector" class="form-control"
                                            value="{{ $fecha }}" onchange="cambiarFecha(this.value)">
                                    </div>
                                </div>
                            </div>

                            <!-- Estadísticas del Día -->
                            <div class="row mb-3">
                                <div class="col-lg-3 col-md-6 mb-2">
                                    <div class="card stats-card" style="background: #495057; color: white;">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <p class="mb-1" style="color: rgba(255,255,255,0.8);">Total Registros
                                                    </p>
                                                    <h3 class="mb-0">{{ $estadisticas['total_registros'] }}</h3>
                                                </div>
                                                <i class="fas fa-users stat-icon"></i>
                                            </div>
                                            <div class="progress progress-custom mt-2">
                                                <div class="progress-bar"
                                                    style="width: 100%; background: rgba(255,255,255,0.3);"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-6 mb-2">
                                    <div class="card stats-card" style="background: #28a745; color: white;">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <p class="mb-1" style="color: rgba(255,255,255,0.8);">Presentes</p>
                                                    <h3 class="mb-0">{{ $estadisticas['presentes'] }}</h3>
                                                </div>
                                                <i class="fas fa-check-circle stat-icon"></i>
                                            </div>
                                            <div class="progress progress-custom mt-2">
                                                <div class="progress-bar"
                                                    style="width: {{ $estadisticas['porcentaje_asistencia'] }}%; background: rgba(255,255,255,0.3);">
                                                </div>
                                            </div>
                                            <small
                                                style="color: rgba(255,255,255,0.9);">{{ $estadisticas['porcentaje_asistencia'] }}%
                                                del total</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-6 mb-2">
                                    <div class="card stats-card" style="background: #dc3545; color: white;">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <p class="mb-1" style="color: rgba(255,255,255,0.8);">Ausentes</p>
                                                    <h3 class="mb-0">{{ $estadisticas['ausentes'] }}</h3>
                                                </div>
                                                <i class="fas fa-times-circle stat-icon"></i>
                                            </div>
                                            <div class="progress progress-custom mt-2">
                                                <div class="progress-bar"
                                                    style="width: {{ $estadisticas['total_registros'] > 0 ? ($estadisticas['ausentes'] / $estadisticas['total_registros']) * 100 : 0 }}%; background: rgba(255,255,255,0.3);">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-6 mb-2">
                                    <div class="card stats-card" style="background: #ffc107; color: white;">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <p class="mb-1" style="color: rgba(255,255,255,0.8);">Tardanzas</p>
                                                    <h3 class="mb-0">{{ $estadisticas['tardanzas'] }}</h3>
                                                </div>
                                                <i class="fas fa-clock stat-icon"></i>
                                            </div>
                                            <div class="progress progress-custom mt-2">
                                                <div class="progress-bar"
                                                    style="width: {{ $estadisticas['total_registros'] > 0 ? ($estadisticas['tardanzas'] / $estadisticas['total_registros']) * 100 : 0 }}%; background: rgba(255,255,255,0.3);">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sesiones Programadas -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="card shadow-sm">
                                        <div class="card-header"
                                            style="background: #f8f9fa; border-bottom: 2px solid #0A8CB3;">
                                            <h6 class="mb-0 estilo-info">
                                                <i class="fas fa-chalkboard-teacher text-primary"></i>
                                                Sesiones Programadas
                                                <span class="badge bg-primary ms-2">{{ $sesiones->count() }}</span>
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            @if ($sesiones->isEmpty())
                                                @php
                                                    $esFeriado = \App\Models\Feriado::esFeriado($fecha);
                                                    $feriadoInfo = $esFeriado ? \App\Models\Feriado::porFecha($fecha)->first() : null;
                                                @endphp

                                                @if($esFeriado)
                                                    <div class="empty-state">
                                                        <i class="fas fa-calendar-day" style="color: #dc3545;"></i>
                                                        <h5 class="text-danger mb-2">Día Feriado</h5>
                                                        <p class="text-muted mb-3">
                                                            <strong>{{ $feriadoInfo->nombre }}</strong><br>
                                                            @if($feriadoInfo->descripcion)
                                                                {{ $feriadoInfo->descripcion }}
                                                            @endif
                                                        </p>
                                                        <div class="alert alert-info">
                                                            <i class="fas fa-info-circle"></i>
                                                            <strong>No hay sesiones programadas porque es feriado.</strong><br>
                                                            Si hay clases de recuperación programadas, aparecerán en fechas posteriores.
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="empty-state">
                                                        <i class="fas fa-calendar-times"></i>
                                                        <h5 class="text-muted mb-2">No tienes sesiones programadas para hoy</h5>
                                                        <p class="text-muted">Revisa el horario o selecciona otra fecha</p>
                                                    </div>
                                                @endif
                                            @else
                                                <div class="row">
                                                    @foreach ($sesiones as $sesion)
                                                        <div class="col-lg-6 col-xl-4 mb-3">
                                                            <div class="sesion-card">
                                                                <div class="sesion-header">
                                                                    <div
                                                                        class="d-flex justify-content-between align-items-start mb-2">
                                                                        <div style="flex: 1;">
                                                                            <h6 class="mb-1" style="font-weight: 700;">
                                                                                {{ $sesion->cursoAsignatura->asignatura->nombre }}
                                                                            </h6>
                                                                            <small style="opacity: 0.85;">
                                                                                {{ $sesion->cursoAsignatura->curso->grado->nombre }}
                                                                                -
                                                                                {{ $sesion->cursoAsignatura->curso->seccion->nombre }}
                                                                            </small>
                                                                        </div>
                                                                        @php
                                                                            $ahora = now();
                                                                            if (
                                                                                str_contains($sesion->hora_inicio, ' ')
                                                                            ) {
                                                                                $inicio = Carbon\Carbon::parse(
                                                                                    $sesion->hora_inicio,
                                                                                );
                                                                            } else {
                                                                                $inicio = Carbon\Carbon::parse(
                                                                                    $fecha . ' ' . $sesion->hora_inicio,
                                                                                );
                                                                            }

                                                                            if (str_contains($sesion->hora_fin, ' ')) {
                                                                                $fin = Carbon\Carbon::parse(
                                                                                    $sesion->hora_fin,
                                                                                );
                                                                            } else {
                                                                                $fin = Carbon\Carbon::parse(
                                                                                    $fecha . ' ' . $sesion->hora_fin,
                                                                                );
                                                                            }

                                                                            $estadoClase = 'pendiente';
                                                                            if ($ahora->between($inicio, $fin)) {
                                                                                $estadoClase = 'activo';
                                                                            } elseif ($ahora->gt($fin)) {
                                                                                $estadoClase = 'finalizado';
                                                                            }
                                                                        @endphp
                                                                        <span
                                                                            class="status-indicator {{ $estadoClase }}"></span>
                                                                    </div>
                                                                    <div class="badge-hora">
                                                                        <i class="far fa-clock"></i>
                                                                        {{ $sesion->hora_inicio }} -
                                                                        {{ $sesion->hora_fin }}
                                                                    </div>
                                                                </div>

                                                                <div class="card-body">
                                                                    @php
                                                                        $asistenciasRegistradas = \App\Models\AsistenciaAsignatura::where(
                                                                            'curso_asignatura_id',
                                                                            $sesion->curso_asignatura_id,
                                                                        )
                                                                            ->where('fecha', $fecha)
                                                                            ->count();
                                                                        $totalEstudiantes = \App\Models\Matricula::where(
                                                                            'curso_id',
                                                                            $sesion->cursoAsignatura->curso_id,
                                                                        )
                                                                            ->where('estado', 'Matriculado')
                                                                            ->count();
                                                                        $porcentajeRegistro =
                                                                            $totalEstudiantes > 0
                                                                                ? round(
                                                                                    ($asistenciasRegistradas /
                                                                                        $totalEstudiantes) *
                                                                                        100,
                                                                                )
                                                                                : 0;
                                                                    @endphp

                                                                    <div class="mb-3">
                                                                        <div class="d-flex justify-content-between mb-2">
                                                                            <small class="text-muted estilo-info">
                                                                                <i class="fas fa-user-graduate"></i>
                                                                                {{ $totalEstudiantes }} estudiantes
                                                                            </small>
                                                                            <small class="text-muted estilo-info">
                                                                                {{ $asistenciasRegistradas }}/{{ $totalEstudiantes }}
                                                                                registrados
                                                                            </small>
                                                                        </div>
                                                                        <div class="progress progress-custom">
                                                                            <div class="progress-bar {{ $porcentajeRegistro == 100 ? 'bg-success' : 'bg-info' }}"
                                                                                style="width: {{ $porcentajeRegistro }}%">
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="d-flex gap-2">
                                                                        <a href="{{ route('asistencia.registrar-asignatura', [$sesion->curso_asignatura_id, $fecha]) }}"
                                                                            class="btn btn-primary btn-sm flex-grow-1">
                                                                            <i class="fas fa-edit"></i>
                                                                            {{ $asistenciasRegistradas > 0 ? 'Editar' : 'Registrar' }}
                                                                        </a>

                                                                        @if ($asistenciasRegistradas > 0)
                                                                            <a href="{{ route('asistencia.reporte-curso', $sesion->curso_asignatura_id) }}"
                                                                                class="btn btn-outline-info btn-sm">
                                                                                <i class="fas fa-chart-bar"></i>
                                                                            </a>
                                                                        @endif
                                                                    </div>

                                                                    @if ($estadoClase === 'activo')
                                                                        <div class="alert alert-success mt-3 mb-0 py-2">
                                                                            <i class="fas fa-info-circle"></i>
                                                                            <small>Clase en progreso</small>
                                                                        </div>
                                                                    @elseif($estadoClase === 'finalizado' && $asistenciasRegistradas == 0)
                                                                        <div class="alert alert-warning mt-3 mb-0 py-2">
                                                                            <i class="fas fa-exclamation-triangle"></i>
                                                                            <small>Asistencia pendiente</small>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loader = document.getElementById('loaderPrincipal');
            if (loader) loader.style.display = 'none';
        });

        function cambiarFecha(fecha) {
            const loader = document.getElementById('loaderPrincipal');
            if (loader) loader.style.display = 'flex';

            setTimeout(() => {
                window.location.href = '{{ route('asistencia.index') }}?fecha=' + fecha;
            }, 500);
        }

        function exportarExcel() {
            const fecha = document.getElementById('fecha-selector').value;
            window.location.href = `/asistencia/exportar?fecha=${fecha}`;
        }

        // Actualización automática cada 5 minutos
        setInterval(() => {
            location.reload();
        }, 300000);
    </script>

@endsection
