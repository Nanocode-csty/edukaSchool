@extends('cplantilla.bprincipal')
@section('titulo', 'Registro de Calificaciones')
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
    </style>

    <div class="container-fluid margen-movil-2">
        <div class="row mt-4 mr-1 ml-1">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-primary text-white" style="background-color: #1e5981 !important;">
                        <h4 class="mb-0">
                            <i class="fas fa-edit"></i> Registro de Calificaciones
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('notas.editar') }}" method="POST">
                            @csrf
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="curso_id" class="form-label mr-2">Seleccione Curso:</label>
                                        <select name="curso_id" id="curso_id"
                                            class="form-select @error('curso_id') is-invalid @enderror w-100" required
                                            style="border: 1px solid #DAA520;">
                                            <option value="">Seleccione un curso</option>
                                            @foreach ($cursos as $curso)
                                                <option value="{{ $curso->curso_id }}">
                                                    {{ $curso->grado->nombre }} {{ $curso->grado->nivel->nombre }}
                                                    "{{ $curso->seccion->nombre }}" - {{ $curso->anoLectivo->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('curso_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="asignatura_id" class="form-label mr-2">Seleccione Asignatura:</label>
                                        <select name="asignatura_id" id="asignatura_id"
                                            class="form-select @error('asignatura_id') is-invalid @enderror w-100" required
                                            disabled style="border: 1px solid #DAA520;">
                                            <option value="">Seleccione primero un curso</option>
                                        </select>
                                        @error('asignatura_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-center mt-3">
                                <button type="submit" id="btnConsultar" class="btn btn-primary px-4 w-100"
                                    style="font-weight: bold; font-size:medium; background-color: #bd9123 !important; border:none"  disabled>
                                    <i class="fas fa-search mx-2"></i> Consultar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <style>
        /* Estilo personalizado para los select */
        .form-select {
            height: calc(1.5em + 0.75rem + 2px);
            padding: 0.375rem 1.75rem 0.375rem 0.75rem;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #DAA520;
            border-radius: 0.25rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 16px 12px;
        }

        .form-select:focus {
            border-color: #DAA520;
            outline: 0;
            box-shadow: 0 0 0 0.25rem rgba(218, 165, 32, 0.25);
        }

        .form-select.is-valid {
            border-color: #198754;
        }

        .form-select.is-invalid {
            border-color: #dc3545;
        }

        /* Estilo para el botón */
        #btnConsultar {
            background-color: #0A8CB3;
            border-color: #0A8CB3;
        }

        #btnConsultar:hover {
            background-color: #086f8e;
            border-color: #086f8e;
        }

        #btnConsultar:disabled {
            background-color: #6c757d;
            border-color: #6c757d;
            cursor: not-allowed;
        }

        /* Estilo para las etiquetas */
        .form-label {
            font-weight: bold;
            color: #0A8CB3;
        }
    </style>
@endsection
@section('scripts')
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Script para mensajes globales con SweetAlert2 -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mensajes de éxito
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: '{{ session('success') }}',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            @endif

            // Mensajes de error
            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: '¡Error!',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Entendido'
                });
            @endif

            // Mensajes de información
            @if (session('info'))
                Swal.fire({
                    icon: 'info',
                    title: 'Información',
                    text: '{{ session('info') }}',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Aceptar'
                });
            @endif
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Cuando cambia el curso, cargar las asignaturas correspondientes
            $('#curso_id').change(function() {
                const cursoId = $(this).val();

                // Deshabilitar botón por defecto
                $('#btnConsultar').prop('disabled', true);

                if (cursoId) {
                    // Mostrar indicador de carga
                    $('#asignatura_id').html('<option value="">Cargando asignaturas...</option>');

                    // Realizar petición AJAX para obtener las asignaturas
                    $.ajax({
                        url: '{{ route('notas.asignaturas') }}',
                        type: 'GET',
                        data: {
                            curso_id: cursoId
                        },
                        dataType: 'json',
                        success: function(data) {
                            // Habilitar el select de asignaturas
                            $('#asignatura_id').prop('disabled', false);

                            // Limpiar select actual
                            $('#asignatura_id').empty().append(
                                '<option value="">Seleccione una asignatura</option>');

                            // Verificar si hay datos
                            if (data.length > 0) {
                                // Llenar con nuevas opciones
                                $.each(data, function(key, asignatura) {
                                    $('#asignatura_id').append('<option value="' +
                                        asignatura.asignatura_id + '">' +
                                        asignatura.codigo + ' - ' + asignatura
                                        .nombre + '</option>');
                                });
                            } else {
                                $('#asignatura_id').html(
                                    '<option value="">No hay asignaturas para este curso</option>'
                                    );
                            }
                        },
                        error: function(error) {
                            console.error('Error al cargar asignaturas:', error);
                            $('#asignatura_id').html(
                                '<option value="">Error al cargar asignaturas</option>');
                        }
                    });
                } else {
                    // Si no hay curso seleccionado, deshabilitar y limpiar select de asignaturas
                    $('#asignatura_id').prop('disabled', true).html(
                        '<option value="">Seleccione primero un curso</option>');
                }
            });

            // Habilitar botón solo cuando se selecciona una asignatura
            $('#asignatura_id').change(function() {
                if ($(this).val()) {
                    $('#btnConsultar').prop('disabled', false);
                } else {
                    $('#btnConsultar').prop('disabled', true);
                }
            });
        });
    </script>
@endsection
