@extends('cplantilla.bprincipal')

@section('titulo', 'Registrar Asistencia')

@section('contenidoplantilla')
    <style>
        .estilo-info {
            margin-bottom: 0px;
            font-family: "Quicksand", sans-serif;
            font-weight: 700;
            font-size: clamp(0.9rem, 2.0vw, 0.9rem) !important;
        }

        @media (max-width: 576px) {
            .margen-movil {
                margin-left: -29px !important;
                margin-right: -29px !important;
            }

            .margen-movil-2 {
                margin: 0 !important;
                padding: 0 !important;
            }
        }

        .estudiante-row {
            transition: all 0.2s;
            border-left: 4px solid transparent;
        }

        .estudiante-row:hover {
            background-color: #f8f9fa;
        }

        .estudiante-row.presente {
            border-left-color: #28a745;
            background-color: #f0fff4;
        }

        .estudiante-row.ausente {
            border-left-color: #dc3545;
            background-color: #fff5f5;
        }

        .estudiante-row.tardanza {
            border-left-color: #ffc107;
            background-color: #fffbf0;
        }

        .estudiante-row.justificada {
            border-left-color: #6c757d;
            background-color: #f8f9fa;
        }

        .estudiante-row.justificada-admin {
            border-left-color: #6c757d;
            background-color: #f8f9fa;
            position: relative;
        }

        .tipo-btn.blocked {
            opacity: 0.6;
            cursor: not-allowed;
            pointer-events: none;
        }

        .tipo-btn.blocked.active {
            background-color: #6c757d !important;
            color: white !important;
            border-color: #6c757d !important;
        }

        .tipo-btn {
            border-radius: 6px;
            width: 40px;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 2px solid;
            transition: all 0.2s;
            cursor: pointer;
            font-size: 1rem;
            background: #fff;
        }

        .tipo-btn:hover {
            transform: scale(1.05);
        }

        .tipo-btn.active {
            transform: scale(1.1);
            font-weight: bold;
        }

        .historial-badge {
            display: inline-block;
            width: 24px;
            height: 24px;
            line-height: 24px;
            text-align: center;
            border-radius: 3px;
            font-size: 0.7rem;
            font-weight: bold;
        }

        .stats-mini {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
        }

        .table-responsive {
            max-height: calc(100vh - 400px);
            overflow-y: auto;
        }

        .student-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1rem;
        }

        .btn-guardar-flotante {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
            width: 70px;
            height: 70px;
            border-radius: 50%;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }
    </style>

    <div class="container-fluid estilo-info margen-movil-2">
        <div class="row mt-4 ml-1 mr-1">
            <div class="col-12 mb-3">
                <div class="box_block">
                    <button style="background: #0A8CB3 !important; border:none"
                        class="btn btn-primary btn-block text-left rounded-0 btn_header header_6 estilo-info" type="button"
                        data-toggle="collapse" data-target="#collapseAsistencia" aria-expanded="true"
                        aria-controls="collapseAsistencia">
                        <i class="fas fa-clipboard-check"></i>&nbsp;Registro de Asistencia
                        <div class="float-right"><i class="fas fa-chevron-down"></i></div>
                    </button>
                </div>
                <div class="collapse show" id="collapseAsistencia">
                    <div class="card card-body rounded-0 border-0 pt-0"
                        style="padding-left:0.966666666rem;padding-right:0.9033333333333333rem;">
                        <div class="row margen-movil" style="padding:20px;">
                            <div class="col-12">

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
