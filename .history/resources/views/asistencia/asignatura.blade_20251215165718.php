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
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        .tipo-btn:hover::before {
            left: 100%;
        }

        .tipo-btn:hover {
            transform: translateY(-2px) scale(1.05);
            box-shadow: var(--shadow-md);
        }

        .tipo-btn.active {
            transform: scale(1.1);
            font-weight: bold;
            box-shadow: var(--shadow-md);
        }

        .tipo-btn.blocked {
            opacity: 0.6;
            cursor: not-allowed;
            pointer-events: none;
            filter: grayscale(50%);
        }

        .tipo-btn.blocked.active {
            background-color: var(--secondary-color) !important;
            color: white !important;
            border-color: var(--secondary-color) !important;
        }

        /* History Badges */
        .historial-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 700;
            color: white;
            margin: 0 1px;
            transition: var(--transition-fast);
        }

        .historial-badge:hover {
            transform: scale(1.1);
        }

        /* Statistics Mini Cards */
        .stats-mini {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.25rem;
            border-radius: 25px;
            font-size: 0.9rem;
            font-weight: 600;
            box-shadow: var(--shadow-sm);
            transition: var(--transition-fast);
        }

        .stats-mini:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        /* Student Avatar */
        .student-avatar {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.1rem;
            box-shadow: var(--shadow-sm);
            transition: var(--transition-fast);
        }

        .student-avatar:hover {
            transform: scale(1.05);
        }

        /* Table Enhancements */
        .table-responsive {
            max-height: calc(100vh - 400px);
            overflow-y: auto;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-sm);
        }

        .table-modern {
            margin-bottom: 0;
        }

        .table-modern thead th {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            border: none;
            padding: 1rem 0.75rem;
            font-weight: 600;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .table-modern tbody tr {
            transition: var(--transition-fast);
        }

        .table-modern tbody tr:hover {
            background: rgba(10, 140, 179, 0.02);
        }

        /* Quick Actions Bar */
        .quick-actions-bar {
            background: white;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: var(--shadow-sm);
            margin-bottom: 2rem;
            border: 1px solid rgba(0, 0, 0, 0.08);
        }

        .quick-action-group {
            display: flex;
            gap: 0.5rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .bulk-action-btn {
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-weight: 600;
            transition: var(--transition-fast);
            border: 2px solid;
        }

        .bulk-action-btn:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        /* Search Input */
        .search-input-modern {
            position: relative;
        }

        .search-input-modern input {
            border: 2px solid var(--primary-color);
            border-radius: 25px;
            padding: 0.5rem 1rem 0.5rem 3rem;
            transition: var(--transition-fast);
            font-family: "Quicksand", sans-serif;
        }

        .search-input-modern input:focus {
            outline: none;
            border-color: var(--primary-dark);
            box-shadow: 0 0 0 3px rgba(10, 140, 179, 0.1);
        }

        .search-input-modern::before {
            content: '\f002';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary-color);
            z-index: 1;
        }

        /* Floating Save Button */
        .btn-guardar-flotante {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--success-color), #20c997);
            border: none;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            box-shadow: var(--shadow-lg);
            transition: var(--transition-normal);
            cursor: pointer;
        }

        .btn-guardar-flotante:hover {
            transform: scale(1.1);
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
        }

        .btn-guardar-flotante:active {
            transform: scale(0.95);
        }

        /* Info Panel */
        .info-panel-modern {
            background: linear-gradient(135deg, var(--info-light), rgba(23, 162, 184, 0.1));
            border: 1px solid var(--info-color);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .info-panel-modern .info-icon {
            color: var(--info-color);
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        /* Class Info Card */
        .class-info-card {
            background: linear-gradient(135deg, var(--primary-light), rgba(10, 140, 179, 0.1));
            border: 1px solid var(--primary-color);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .class-info-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
        }

        .class-info-item:last-child {
            margin-bottom: 0;
        }

        .class-info-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .class-info-content strong {
            color: var(--dark-color);
            display: block;
            font-weight: 700;
        }

        .class-info-content small {
            color: var(--secondary-color);
            font-weight: 500;
        }

        /* Action Buttons */
        .action-buttons-modern {
            background: white;
            border-radius: var(--border-radius);
            padding: 2rem;
            box-shadow: var(--shadow-sm);
            border: 1px solid rgba(0, 0, 0, 0.08);
            margin-top: 2rem;
        }

        .btn-modern-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            border: none;
            border-radius: 12px;
            padding: 0.75rem 2rem;
            font-weight: 700;
            font-size: 1rem;
            transition: var(--transition-normal);
            box-shadow: var(--shadow-md);
        }

        .btn-modern-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
        }

        .btn-modern-secondary {
            background: white;
            color: var(--secondary-color);
            border: 2px solid var(--secondary-color);
            border-radius: 12px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            font-size: 1rem;
            transition: var(--transition-normal);
        }

        .btn-modern-secondary:hover {
            background: var(--secondary-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .quick-action-group {
                justify-content: center;
            }

            .stats-mini {
                font-size: 0.8rem;
                padding: 0.5rem 1rem;
            }

            .class-info-item {
                flex-direction: column;
                text-align: center;
                gap: 0.5rem;
            }
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

            .table-responsive {
                max-height: calc(100vh - 500px);
            }

            .btn-guardar-flotante {
                bottom: 20px;
                right: 20px;
                width: 60px;
                height: 60px;
                font-size: 1.2rem;
            }
        }

        /* Loading States */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            backdrop-filter: blur(5px);
        }

        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 4px solid rgba(10, 140, 179, 0.1);
            border-left: 4px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Accessibility */
        .tipo-btn:focus {
            outline: 2px solid var(--primary-color);
            outline-offset: 2px;
        }

        .bulk-action-btn:focus {
            outline: 2px solid var(--primary-color);
            outline-offset: 2px;
        }
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
