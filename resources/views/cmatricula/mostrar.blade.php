@extends('cplantilla.bprincipal')
@section('titulo', 'Detalles de Matrícula')
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

    <div class="container-fluid estilo-info margen-movil-2">
        <div class="row mt-4 ml-1 mr-1">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="background-color: #0A8CB3 !important; color: white">
                        <h4 class="mb-0">
                            <i class="fas fa-eye"></i> Detalles de la Matrícula N° {{ $matricula->numero_matricula }}
                        </h4>
                    </div>
                    <div class="card-body">

                        <!-- Información principal en tarjetas -->
                        <div class="row">
                            <!-- Información de la Matrícula -->
                            <div class="col-md-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-header" style="background-color: #F59617 !important; color: white">
                                        <h5 class="mb-0">
                                            <i class="fas fa-clipboard-list"></i> Información de Matrícula
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-borderless">
                                            <tbody>
                                                <tr>
                                                    <td><strong><i class="fas fa-id-card"></i> N° Matrícula:</strong></td>
                                                    <td><span class="badge bg-primary text-white fw-bold"
                                                            style="border: none; font-weight:bold !important;">{{ $matricula->numero_matricula }}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong><i class="fas fa-calendar"></i> Fecha de Matrícula:</strong>
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($matricula->fecha_matricula)->format('d/m/Y H:i') }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong><i class="fas fa-calendar-alt"></i> Año Académico:</strong>
                                                    </td>
                                                    <td>{{ $matricula->anio_academico ?? date('Y') }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong><i class="fas fa-toggle-on"></i> Estado:</strong></td>
                                                    <td>
                                                        @if ($matricula->estado == 'Matriculado')
                                                            <span class="badge bg-success fs-6 text-white fw-bold"
                                                                style="border: none; font-weight:bold !important;">{{ $matricula->estado }}</span>
                                                        @elseif($matricula->estado == 'Pre-inscrito')
                                                            <span class="badge bg-warning fs-6 text-white fw-bold"
                                                                style="border: none; font-weight:bold !important;">{{ $matricula->estado }}</span>
                                                        @elseif($matricula->estado == 'Anulado')
                                                            <span class="badge bg-danger fs-6 text-white fw-bold"
                                                                style="border: none; font-weight:bold !important;">{{ $matricula->estado }}</span>
                                                        @else
                                                            <span class="badge bg-secondary fs-6 text-white fw-bold"
                                                                style="border: none; font-weight:bold !important;">{{ $matricula->estado }}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong><i class="fas fa-user-cog"></i> Usuario Registro:</strong>
                                                    </td>
                                                    <td>{{ $matricula->usuarioRegistro->name ?? 'Sistema' }}</td>
                                                </tr>
                                                @if ($matricula->observaciones)
                                                    <tr>
                                                        <td><strong><i class="fas fa-comment"></i> Observaciones:</strong>
                                                        </td>
                                                        <td class="text-muted">{{ $matricula->observaciones }}</td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Información del Estudiante -->
                            <div class="col-md-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="mb-0">
                                            <i class="fas fa-user-graduate"></i> Información del Estudiante
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        @if ($matricula->estudiante)
                                            <table class="table table-borderless table-responsive">
                                                <tbody>
                                                    <tr>
                                                        <td><strong><i class="fas fa-id-card"></i> DNI:</strong></td>
                                                        <td>{{ $matricula->estudiante->dni }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong><i class="fas fa-user"></i> Nombres:</strong></td>
                                                        <td>{{ $matricula->estudiante->nombres }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong><i class="fas fa-users"></i> Apellidos:</strong></td>
                                                        <td>{{ $matricula->estudiante->apellidos }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong><i class="fas fa-phone"></i> Teléfono:</strong></td>
                                                        <td>{{ $matricula->estudiante->telefono ?? 'No registrado' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong><i class="fas fa-envelope"></i> Email:</strong></td>
                                                        <td>{{ $matricula->estudiante->email ?? 'No registrado' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong><i class="fas fa-birthday-cake"></i> Fecha
                                                                Nacimiento:</strong></td>
                                                        <td>
                                                            @if ($matricula->estudiante->fecha_nacimiento)
                                                                {{ \Carbon\Carbon::parse($matricula->estudiante->fecha_nacimiento)->format('d/m/Y') }}
                                                            @else
                                                                No registrado
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong><i class="fas fa-venus-mars"></i> Género:</strong></td>
                                                        <td>
                                                            @if ($matricula->estudiante->genero == 'M')
                                                                Masculino
                                                            @elseif($matricula->estudiante->genero == 'F')
                                                                Femenino
                                                            @else
                                                                No especificado
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @if ($matricula->estudiante->direccion)
                                                        <tr>
                                                            <td><strong><i class="fas fa-map-marker-alt"></i>
                                                                    Dirección:</strong></td>
                                                            <td>{{ $matricula->estudiante->direccion }}</td>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        @else
                                            <div class="alert alert-warning">
                                                <i class="fas fa-exclamation-triangle"></i>
                                                No se encontró información del estudiante asociado.
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Segunda fila: Información Académica -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header bg-success text-white">
                                        <h5 class="mb-0">
                                            <i class="fas fa-graduation-cap"></i> Información Académica
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <table class="table table-borderless">
                                                    <tbody>
                                                        <tr>
                                                            <td><strong><i class="fas fa-layer-group"></i> Nivel:</strong>
                                                            </td>
                                                            <td>
                                                                @if ($matricula->grado && $matricula->grado->nivel)
                                                                    <span class="badge bg-info text-white fw-bold"
                                                                        style="border: none; font-weight:bold !important;">{{ $matricula->grado->nivel->nombre }}</span>
                                                                @else
                                                                    <span class="text-muted">No disponible</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong><i class="fas fa-stairs"></i> Grado:</strong></td>
                                                            <td>
                                                                @if ($matricula->grado)
                                                                    <span class="badge bg-warning text-white fw-bold"
                                                                        style="border: none; font-weight:bold !important;">{{ $matricula->grado->nombre }}</span>
                                                                @else
                                                                    <span class="text-muted">No disponible</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="col-md-6">
                                                <table class="table table-borderless">
                                                    <tbody>
                                                        <tr>
                                                            <td><strong><i class="fas fa-users-class"></i> Sección:</strong>
                                                            </td>
                                                            <td>
                                                                @if ($matricula->seccion)
                                                                    <span class="badge bg-secondary text-white fw-bold"
                                                                        style="border: none; font-weight:bold !important;">{{ $matricula->seccion->nombre }}</span>
                                                                @else
                                                                    <span class="text-muted">No disponible</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong><i class="fas fa-users"></i> Capacidad
                                                                    Sección:</strong></td>
                                                            <td>
                                                                @if ($matricula->seccion)
                                                                    {{ $matricula->seccion->capacidad_maxima }} estudiantes
                                                                @else
                                                                    <span class="text-muted">No disponible</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        @if ($matricula->grado && $matricula->grado->descripcion)
                                            <div class="mt-3">
                                                <strong><i class="fas fa-info-circle"></i> Descripción del Grado:</strong>
                                                <p class="text-muted mt-2">{{ $matricula->grado->descripcion }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botones de acción -->
                        <div class="row">
                            <div class="col-md-12">
                                <hr>
                                <div class="d-flex justify-content-end align-items-center w-100">
                                    <a href="{{ route('matriculas.index') }}" class="btn btn-secondary w-100" style="font-weight: bold">
                                        <i class="fas fa-arrow-left mx-2"></i> Volver al Listado
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación de anulación -->
    <div class="modal fade" id="modalAnulacion" tabindex="-1" aria-labelledby="modalAnulacionLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="modalAnulacionLabel">
                        <i class="fas fa-exclamation-triangle"></i> Confirmar Anulación
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>¿Está seguro de que desea anular la matrícula N° <span
                                id="numeroMatriculaModal"></span>?</strong></p>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Advertencia:</strong> Una matrícula anulada no se puede reactivar.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <form id="formAnulacion" method="POST" style="display: inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-ban"></i> Confirmar Anulación
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Mensajes de notificación -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show position-fixed"
            style="top: 20px; right: 20px; z-index: 1050;" role="alert">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show position-fixed"
            style="top: 20px; right: 20px; z-index: 1050;" role="alert">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

@endsection

@section('scripts')
    <script>
        // Función para confirmar anulación
        function confirmarAnulacion(numeroMatricula, matriculaId) {
            try {
                document.getElementById('numeroMatriculaModal').textContent = numeroMatricula;
                document.getElementById('formAnulacion').action = /matriculas/$ {
                    matriculaId
                }
                /anular;

                const modal = new bootstrap.Modal(document.getElementById('modalAnulacion'));
                modal.show();
            } catch (error) {
                console.error('Error al mostrar modal:', error);
                alert('Ups. Error al procesar la solicitud. Estamos trabajando en ello.');
            }
        }

        // Auto-hide mensajes después de 5 segundos
        setTimeout(function() {
            document.querySelectorAll('.alert').forEach(alert => {
                if (alert.classList.contains('fade')) {
                    alert.style.display = 'none';
                }
            });
        }, 5000);

        // Validar que existan las relaciones al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            try {
                // Verificar si hay información faltante y mostrar alertas amigables
                const grado = document.querySelector('[data-grado]');
                const seccion = document.querySelector('[data-seccion]');

                if (!grado || !seccion) {
                    console.warn('Algunas relaciones académicas no están disponibles');
                }
            } catch (error) {
                console.error('Error al validar datos:', error);
            }
        });
    </script>
@endsection
