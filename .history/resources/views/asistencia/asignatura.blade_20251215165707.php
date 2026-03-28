@extends('cplantilla.bprincipal')

@section('titulo', 'Registrar Asistencia')

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

        /* Student Row Styling */
        .estudiante-row {
            transition: var(--transition-normal);
            border-left: 4px solid transparent;
            border-radius: 8px;
            margin-bottom: 0.5rem;
        }

        .estudiante-row:hover {
            background: linear-gradient(90deg, rgba(10, 140, 179, 0.02), rgba(10, 140, 179, 0.05));
            box-shadow: var(--shadow-sm);
        }

        .estudiante-row.presente {
            border-left-color: var(--success-color);
            background: linear-gradient(90deg, var(--success-light), rgba(40, 167, 69, 0.05));
        }

        .estudiante-row.ausente {
            border-left-color: var(--danger-color);
            background: linear-gradient(90deg, var(--danger-light), rgba(220, 53, 69, 0.05));
        }

        .estudiante-row.tardanza {
            border-left-color: var(--warning-color);
            background: linear-gradient(90deg, var(--warning-light), rgba(255, 193, 7, 0.05));
        }

        .estudiante-row.justificada {
            border-left-color: var(--secondary-color);
            background: linear-gradient(90deg, var(--secondary-light), rgba(108, 117, 125, 0.05));
        }

        .estudiante-row.justificada-admin {
            border-left-color: var(--secondary-color);
            background: linear-gradient(90deg, var(--secondary-light), rgba(108, 117, 125, 0.05));
            position: relative;
        }

        .estudiante-row.justificada-admin::after {
            content: 'Justificación Administrativa';
            position: absolute;
            top: 5px;
            right: 5px;
            background: var(--info-color);
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        /* Attendance Type Buttons */
        .tipo-btn {
            border-radius: var(--border-radius-sm);
            width: 48px;
            height: 48px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 2px solid;
            transition: var(--transition-normal);
            cursor: pointer;
            font-size: 1.1rem;
            background: white;
            position: relative;
            overflow: hidden;
        }

        .tipo-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;

                                <!-- Información de la clase -->
                                <div class="card" style="border: none">
                                    <div
                                        style="background: #E0F7FA; color: #0A8CB3; font-weight: bold; border: 2px solid #86D2E3; border-bottom: 2px solid #86D2E3; padding: 6px 20px; border-radius:4px 4px 0px 0px;">
                                        <i class="fas fa-info-circle mr-2"></i>
                                        Información de la Clase
                                    </div>
                                    <div class="card-body"
                                        style="border: 2px solid #86D2E3; border-top: none; border-radius: 0px 0px 4px 4px !important;">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <strong>Asignatura:</strong> {{ $cursoAsignatura->asignatura->nombre }}
                                            </div>
                                            <div class="col-md-4">
                                                <strong>Curso:</strong> {{ $cursoAsignatura->curso->grado->nombre }} - {{ $cursoAsignatura->curso->seccion->nombre }}
                                            </div>
                                            <div class="col-md-4">
                                                <strong>Fecha:</strong> {{ Carbon\Carbon::parse($fechaStr)->isoFormat('D [de] MMMM, YYYY') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Estadísticas en tiempo real -->
                                <div class="card mt-4" style="border: none">
                                    <div
                                        style="background: #E0F7FA; color: #0A8CB3; font-weight: bold; border: 2px solid #86D2E3; border-bottom: 2px solid #86D2E3; padding: 6px 20px; border-radius:4px 4px 0px 0px;">
                                        <i class="fas fa-chart-bar mr-2"></i>
                                        Estadísticas en Tiempo Real
                                    </div>
                                    <div class="card-body"
                                        style="border: 2px solid #86D2E3; border-top: none; border-radius: 0px 0px 4px 4px !important;">
                                        <div class="d-flex gap-2 flex-wrap">
                                            <div class="stats-mini bg-light">
                                                <i class="fas fa-users text-primary"></i>
                                                <span>Total: <strong id="total-estudiantes">{{ $matriculas->count() }}</strong></span>
                                            </div>
                                            <div class="stats-mini bg-success text-white">
                                                <i class="fas fa-check"></i>
                                                <span>Presentes: <strong id="count-presentes">0</strong></span>
                                            </div>
                                            <div class="stats-mini bg-danger text-white">
                                                <i class="fas fa-times"></i>
                                                <span>Ausentes: <strong id="count-ausentes">0</strong></span>
                                            </div>
                                            <div class="stats-mini bg-warning text-white">
                                                <i class="fas fa-clock"></i>
                                                <span>Tardanzas: <strong id="count-tardanzas">0</strong></span>
                                            </div>
                                            <div class="stats-mini bg-secondary text-white">
                                                <i class="fas fa-file-alt"></i>
                                                <span>Justificadas: <strong id="count-justificadas">0</strong></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

        <form id="form-asistencia" action="{{ route('asistencia.guardar-asignatura') }}" method="POST">
            @csrf
            <input type="hidden" name="curso_asignatura_id" value="{{ $cursoAsignatura->curso_asignatura_id }}">
            <input type="hidden" name="fecha" value="{{ $fechaStr }}">

            <!-- Controles de registro masivo -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                <div>
                                    <h6 class="mb-2">Acciones Rápidas</h6>
                                    <div class="btn-group" role="group">
                                        @foreach ($tiposAsistencia as $tipo)
                                            <button type="button"
                                                class="btn btn-outline-{{ getColorBootstrap($tipo->codigo) }}"
                                                onclick="marcarTodos('{{ $tipo->tipo_asistencia_id }}', '{{ $tipo->codigo }}')">
                                                <i class="fas fa-{{ getIcono($tipo->codigo) }}"></i>
                                                Todos {{ $tipo->nombre }}
                                            </button>
                                        @endforeach
                                        <button type="button" class="btn btn-outline-dark" onclick="limpiarTodos()">
                                            <i class="fas fa-eraser"></i> Limpiar
                                        </button>
                                    </div>
                                </div>

                                <div class="input-group" style="max-width: 300px;">
                                    <span class="input-group-text bg-white">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="text" id="search-estudiante" class="form-control border-start-0"
                                        placeholder="Buscar estudiante...">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de Estudiantes -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light" style="position: sticky; top: 0; z-index: 10;">
                                        <tr>
                                            <th style="width: 50px;">#</th>
                                            <th style="width: 60px;"></th>
                                            <th>Estudiante</th>
                                            <th style="width: 120px;">DNI</th>
                                            <th style="width: 200px;" class="text-center">Últimos 5 días</th>
                                            <th style="width: 250px;" class="text-center">Asistencia</th>
                                            <th style="width: 250px;">Observación</th>
                                            <th style="width: 60px;"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="estudiantes-container">
                                        @foreach ($matriculas as $i => $matricula)
                                            @php
                                                $asistencia = $asistencias->get($matricula->matricula_id);
                                                $historial =
                                                    $historialAsistencias->get($matricula->matricula_id) ?? collect();
                                                // Verificar si tiene justificación aprobada administrativa
                                                $tieneJustificacionAprobada = \App\Models\JustificacionAsistencia::where('matricula_id', $matricula->matricula_id)
                                                    ->where('fecha', $fechaStr)
                                                    ->where('estado', 'aprobado')
                                                    ->exists();
                                            @endphp
                                            <tr class="estudiante-row {{ $asistencia ? getTipoClase(optional($asistencia->tipoAsistencia)->codigo) : '' }} {{ $tieneJustificacionAprobada ? 'justificada-admin' : '' }}"
                                                id="row-{{ $matricula->matricula_id }}"
                                                data-nombre="{{ strtolower($matricula->estudiante->nombres . ' ' . $matricula->estudiante->apellidos) }}"
                                                data-matricula="{{ $matricula->matricula_id }}"
                                                data-justificada-admin="{{ $tieneJustificacionAprobada ? 'true' : 'false' }}">

                                                <td class="align-middle">{{ $i + 1 }}</td>

                                                <td class="align-middle">
                                                    <div class="student-avatar bg-primary text-white">
                                                        {{ substr($matricula->estudiante->nombres, 0, 1) }}{{ substr($matricula->estudiante->apellidos, 0, 1) }}
                                                    </div>
                                                </td>

                                                <td class="align-middle">
                                                    <strong>{{ $matricula->estudiante->nombres }}
                                                        {{ $matricula->estudiante->apellidos }}</strong>
                                                </td>

                                                <td class="align-middle">
                                                    <small class="text-muted">{{ $matricula->estudiante->dni }}</small>
                                                </td>

                                                <td class="align-middle text-center">
                                                    <div class="d-flex gap-1 justify-content-center">
                                                        @foreach ($historial->take(5) as $hist)
                                                            <span class="historial-badge"
                                                                style="background-color: {{ getColorTipo(optional($hist->tipoAsistencia)->codigo) }}; color: white;"
                                                                title="{{ $hist->fecha }}: {{ optional($hist->tipoAsistencia)->nombre }}">
                                                                {{ optional($hist->tipoAsistencia)->codigo }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                </td>

                                                <td class="align-middle">
                                                    <div class="d-flex gap-2 justify-content-center">
                                                        @foreach ($tiposAsistencia as $tipo)
                                                            @php
                                                                $isJustificada = $tipo->codigo === 'J';
                                                                $isChecked = ($asistencia && $asistencia->tipo_asistencia_id == $tipo->tipo_asistencia_id) ||
                                                                           ($tieneJustificacionAprobada && $isJustificada);
                                                                $isBlocked = $tieneJustificacionAprobada && !$isJustificada;
                                                            @endphp
                                                            <label class="tipo-btn mb-0 {{ $isBlocked ? 'blocked' : '' }} {{ $isChecked ? 'active' : '' }}"
                                                                style="border-color: {{ getColorTipo($tipo->codigo) }};
                                                                       {{ $isChecked ? 'background-color: ' . getColorTipo($tipo->codigo) . '; color: #fff;' : 'color: ' . getColorTipo($tipo->codigo) }};
                                                                       {{ $isBlocked ? 'cursor: not-allowed; opacity: 0.6;' : '' }}"
                                                                title="{{ $tipo->nombre }} {{ $tieneJustificacionAprobada && $isJustificada ? '(Bloqueado - Justificación Administrativa)' : '' }}">
                                                                <input type="radio"
                                                                    name="asistencias[{{ $matricula->matricula_id }}][tipo_asistencia_id]"
                                                                    value="{{ $tipo->tipo_asistencia_id }}"
                                                                    class="tipo-radio d-none"
                                                                    data-matricula="{{ $matricula->matricula_id }}"
                                                                    data-codigo="{{ $tipo->codigo }}"
                                                                    {{ $isChecked ? 'checked' : '' }}
                                                                    {{ $isBlocked ? 'disabled' : '' }}>
                                                                <i class="fas fa-{{ getIcono($tipo->codigo) }}"></i>
                                                            </label>
                                                        @endforeach
                                                        <input type="hidden"
                                                            name="asistencias[{{ $matricula->matricula_id }}][matricula_id]"
                                                            value="{{ $matricula->matricula_id }}">
                                                    </div>
                                                </td>

                                                <td class="align-middle">
                                                    <input type="text"
                                                        name="asistencias[{{ $matricula->matricula_id }}][justificacion]"
                                                        class="form-control form-control-sm justificacion-input"
                                                        placeholder="Observación..."
                                                        value="{{ $asistencia->justificacion ?? '' }}"
                                                        style="display: {{ $asistencia && in_array(optional($asistencia->tipoAsistencia)->codigo, ['F', 'T', 'J']) ? 'block' : 'none' }}">
                                                </td>

                                                <td class="align-middle text-center">
                                                    <a href="{{ route('asistencia.detalle-estudiante', $matricula->matricula_id) }}"
                                                        class="btn btn-sm btn-outline-info" target="_blank"
                                                        title="Ver historial">
                                                        <i class="fas fa-chart-line"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="card mt-4" style="border: none">
                <div class="card-body text-center"
                    style="border: 2px solid #86D2E3; border-radius: 4px !important;">
                    <div class="d-flex justify-content-center gap-3">
                        <button type="submit" form="form-asistencia"
                            class="btn btn-primary btn-lg px-5"
                            style="background: #FF3F3F !important; border: none; font: bold !important">
                            <i class="fas fa-save mr-2"></i>
                            <span style="font:bold">Guardar Asistencias</span>
                        </button>
                        <a href="{{ route('asistencia.index') }}"
                            class="btn btn-secondary btn-lg px-5">
                            <i class="fas fa-times mr-2"></i>
                            Cancelar
                        </a>
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

        </form>
    </div>

    <style>
        .form-control {
            border: 1px solid #DAA520;
        }
    </style>
@endsection

@push('js-extra')
    <script>
        // IMPORTANTE: Usar jQuery en lugar de vanilla JS para compatibilidad
        $(document).ready(function() {

            // Búsqueda en tiempo real
            $('#search-estudiante').on('input', function() {
                var searchTerm = $(this).val().toLowerCase();
                $('.estudiante-row').each(function() {
                    var nombre = $(this).data('nombre') || '';
                    if (nombre.indexOf(searchTerm) > -1) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

            // Función para actualizar la selección visual
            function seleccionarTipo(matriculaId, tipoId, codigo) {
                var $row = $('#row-' + matriculaId);
                if (!$row.length) return;

                // Actualizar todos los labels del row
                $row.find('.tipo-btn').each(function() {
                    var $label = $(this);
                    var $input = $label.find('input[type="radio"]');
                    var color = $label.data('color');

                    if ($input.val() == tipoId) {
                        // Este es el seleccionado
                        $label.addClass('active');
                        $label.css({
                            'backgroundColor': color,
                            'color': '#fff',
                            'borderColor': color
                        });
                    } else {
                        // Los demás
                        $label.removeClass('active');
                        $label.css({
                            'backgroundColor': '#fff',
                            'color': color,
                            'borderColor': color
                        });
                    }
                });

                // Actualizar clase de la fila
                $row.removeClass('presente ausente tardanza justificada');
                var claseNueva = getTipoClase(codigo);
                if (claseNueva) {
                    $row.addClass(claseNueva);
                }

                // Mostrar/ocultar campo de observación
                var $justificacionInput = $row.find('.justificacion-input');
                if (['F', 'T', 'J'].indexOf(codigo) > -1) {
                    $justificacionInput.show();
                } else {
                    $justificacionInput.hide();
                }

                actualizarContadores();
            }

            // Event listeners para los radio buttons usando delegación
            $(document).on('change', '.tipo-radio', function() {
                var matriculaId = $(this).data('matricula');
                var tipoId = $(this).val();
                var codigo = $(this).data('codigo');
                seleccionarTipo(matriculaId, tipoId, codigo);
            });

            // Click en labels para marcar radios
            $(document).on('click', '.tipo-btn', function(e) {
                e.preventDefault();
                // No permitir clicks en botones bloqueados
                if ($(this).hasClass('blocked')) {
                    return;
                }
                var $radio = $(this).find('input[type="radio"]');
                $radio.prop('checked', true).trigger('change');
            });

            // Inicializar los que ya están checked
            $('.tipo-radio:checked').each(function() {
                var matriculaId = $(this).data('matricula');
                var tipoId = $(this).val();
                var codigo = $(this).data('codigo');
                seleccionarTipo(matriculaId, tipoId, codigo);
            });

            // Marcar todos con un tipo específico
            window.marcarTodos = function(tipoId, codigo) {
                $('.estudiante-row').each(function() {
                    // Excluir filas justificadas administrativamente
                    if ($(this).attr('data-justificada-admin') === 'true') {
                        return; // Continuar con la siguiente fila
                    }

                    var matriculaId = $(this).data('matricula');
                    var $radios = $(this).find('.tipo-radio');

                    $radios.each(function() {
                        if ($(this).val() == tipoId) {
                            $(this).prop('checked', true);
                            seleccionarTipo(matriculaId, tipoId, codigo);
                        }
                    });
                });
            };

            // Limpiar todas las selecciones
            window.limpiarTodos = function() {
                var estudiantesEditables = $('.estudiante-row').filter(function() {
                    return $(this).attr('data-justificada-admin') !== 'true';
                }).length;

                if (estudiantesEditables === 0) {
                    swal("Información", "No hay asistencias que puedan ser limpiadas (todas están justificadas administrativamente)", {
                        icon: "info",
                        buttons: {
                            confirm: {
                                className: 'btn btn-info'
                            }
                        },
                    });
                    return;
                }

                swal({
                    title: "¿Limpiar todas las selecciones?",
                    text: "Esta acción limpiará todas las asistencias que no estén justificadas administrativamente (" + estudiantesEditables + " estudiantes)",
                    icon: "warning",
                    buttons: {
                        cancel: {
                            text: "Cancelar",
                            value: false,
                            visible: true,
                            className: "btn btn-secondary"
                        },
                        confirm: {
                            text: "Sí, limpiar",
                            value: true,
                            visible: true,
                            className: "btn btn-warning"
                        }
                    },
                    dangerMode: true,
                }).then((willClear) => {
                    if (willClear) {
                        $('.estudiante-row').each(function() {
                            // Excluir filas justificadas administrativamente
                            if ($(this).attr('data-justificada-admin') === 'true') {
                                return; // Continuar con la siguiente fila
                            }

                            var $row = $(this);

                            // Desmarcar radios
                            $row.find('.tipo-radio').prop('checked', false);

                            // Resetear estilos de labels
                            $row.find('.tipo-btn').each(function() {
                                var $label = $(this);
                                var color = $label.data('color');
                                $label.removeClass('active');
                                $label.css({
                                    'backgroundColor': '#fff',
                                    'color': color,
                                    'borderColor': color
                                });
                            });

                            // Limpiar clase de fila
                            $row.removeClass('presente ausente tardanza justificada');

                            // Ocultar y limpiar justificación
                            var $justificacionInput = $row.find('.justificacion-input');
                            $justificacionInput.hide().val('');
                        });

                        actualizarContadores();
                    }
                });
            };

            // Actualizar contadores
            function actualizarContadores() {
                var presentes = $('.estudiante-row.presente').length;
                var ausentes = $('.estudiante-row.ausente').length;
                var tardanzas = $('.estudiante-row.tardanza').length;
                var justificadas = $('.estudiante-row.justificada').length;

                $('#count-presentes').text(presentes);
                $('#count-ausentes').text(ausentes);
                $('#count-tardanzas').text(tardanzas);
                $('#count-justificadas').text(justificadas);
            }

            // Helper function
            function getTipoClase(codigo) {
                var map = {
                    'A': 'presente',
                    'F': 'ausente',
                    'T': 'tardanza',
                    'J': 'justificada'
                };
                return map[codigo] || '';
            }

            // Validación del formulario
            $('#form-asistencia').on('submit', function(e) {
                e.preventDefault();

                var seleccionados = $('.tipo-radio:checked').length;
                if (seleccionados === 0) {
                    swal("Error", "Debe registrar al menos una asistencia", {
                        icon: "error",
                        buttons: {
                            confirm: {
                                className: 'btn btn-danger'
                            }
                        },
                    });
                    return false;
                }

                // Modal de confirmación elegante
                var presentes = $('#count-presentes').text();
                var ausentes = $('#count-ausentes').text();
                var tardanzas = $('#count-tardanzas').text();
                var justificadas = $('#count-justificadas').text();

                var mensaje = '<div class="text-center">';
                mensaje += '<h5>¿Confirmar registro de asistencias?</h5>';
                mensaje += '<p class="mb-2">Total de estudiantes: ' + seleccionados + '</p>';
                mensaje += '<div class="row text-center">';
                mensaje += '<div class="col-3"><i class="fas fa-check text-success"></i><br><small>Presentes: ' + presentes + '</small></div>';
                mensaje += '<div class="col-3"><i class="fas fa-times text-danger"></i><br><small>Ausentes: ' + ausentes + '</small></div>';
                mensaje += '<div class="col-3"><i class="fas fa-clock text-warning"></i><br><small>Tardanzas: ' + tardanzas + '</small></div>';
                mensaje += '<div class="col-3"><i class="fas fa-file-alt text-secondary"></i><br><small>Justificadas: ' + justificadas + '</small></div>';
                mensaje += '</div></div>';

                swal({
                    title: "Confirmar Registro",
                    content: {
                        element: "div",
                        attributes: {
                            innerHTML: mensaje
                        }
                    },
                    icon: "question",
                    buttons: {
                        cancel: {
                            text: "Cancelar",
                            value: false,
                            visible: true,
                            className: "btn btn-secondary"
                        },
                        confirm: {
                            text: "Guardar Asistencias",
                            value: true,
                            visible: true,
                            className: "btn btn-primary"
                        }
                    },
                    dangerMode: false,
                }).then((willSave) => {
                    if (willSave) {
                        // Mostrar loading
                        swal({
                            title: "Guardando...",
                            text: "Por favor espere mientras se procesan las asistencias",
                            icon: "info",
                            buttons: false,
                            closeOnEsc: false,
                            closeOnClickOutside: false
                        });

                        // Enviar el formulario
                        $('#form-asistencia')[0].submit();
                    }
                });

                return false;
            });

            // Inicializar contadores
            actualizarContadores();
        });
    </script>

    @if (session('success'))
        <script>
            $(document).ready(function() {
                swal("Éxito", "{{ session('success') }}", {
                    icon: "success",
                    buttons: {
                        confirm: {
                            className: 'btn btn-success'
                        }
                    },
                });
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            $(document).ready(function() {
                swal("Error", "{{ session('error') }}", {
                    icon: "error",
                    buttons: {
                        confirm: {
                            className: 'btn btn-danger'
                        }
                    },
                });
            });
        </script>
    @endif
@endpush

@php
    function getColorBootstrap($codigo)
    {
        return match ($codigo) {
            'A' => 'success',
            'F' => 'danger',
            'T' => 'warning',
            'J' => 'secondary',
            default => 'primary',
        };
    }

    function getIcono($codigo)
    {
        return match ($codigo) {
            'A' => 'check',
            'F' => 'times',
            'T' => 'clock',
            'J' => 'file-alt',
            default => 'question',
        };
    }

    function getTipoClase($codigo)
    {
        return match ($codigo) {
            'A' => 'presente',
            'F' => 'ausente',
            'T' => 'tardanza',
            'J' => 'justificada',
            default => '',
        };
    }

@endphp
