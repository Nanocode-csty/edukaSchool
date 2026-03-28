@extends('cplantilla.bprincipal')
@section('titulo', 'Detalles del Feriado')
@section('contenidoplantilla')
    <style>
        .estilo-info {
            margin-bottom: 0px;
            font-family: "Quicksand", sans-serif;
            font-weight: 700;
            font-size: clamp(0.9rem, 2.0vw, 0.9rem) !important;
        }

        .detail-card {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .detail-label {
            font-weight: bold;
            color: #495057;
            margin-bottom: 5px;
        }

        .detail-value {
            color: #212529;
            font-size: 1.1rem;
        }

        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 0.85rem;
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
            <div class="col-12 mb-3">
                <div class="box_block">
                    <button style="background: #0A8CB3 !important; border:none"
                        class="btn btn-primary btn-block text-left rounded-0 btn_header header_6 estilo-info" type="button"
                        data-toggle="collapse" data-target="#collapseVerFeriado" aria-expanded="true"
                        aria-controls="collapseVerFeriado">
                        <i class="fas fa-eye"></i>&nbsp;Detalles del Feriado: {{ $item->nombre }}
                        <div class="float-right"><i class="fas fa-chevron-down"></i></div>
                    </button>
                </div>
                <div class="collapse show" id="collapseVerFeriado">
                    <div class="card card-body rounded-0 border-0 pt-0"
                        style="padding-left:0.966666666rem;padding-right:0.9033333333333333rem;">
                        <div class="row margen-movil" style="padding:20px;">
                            <div class="col-12">

                                <!-- Información Principal -->
                                <div class="detail-card">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <h4 class="mb-3">
                                                <i class="fas fa-calendar-times text-primary mr-2"></i>
                                                {{ $item->nombre }}
                                            </h4>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="detail-label">Fecha</div>
                                                    <div class="detail-value">
                                                        <i class="far fa-calendar mr-2"></i>
                                                        {{ \Carbon\Carbon::parse($item->fecha)->format('d/m/Y') }}
                                                        ({{ \Carbon\Carbon::parse($item->fecha)->locale('es')->dayName }})
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="detail-label">Tipo</div>
                                                    <div class="detail-value">
                                                        <span class="badge" style="background-color: {{ $item->tipo === 'Nacional' ? '#28a745' : ($item->tipo === 'Regional' ? '#ffc107' : '#6c757d') }}; color: white; padding: 5px 10px;">
                                                            {{ $item->tipo }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 text-center">
                                            <div class="mb-2">
                                                <span class="status-badge {{ $item->activo ? 'badge-success' : 'badge-secondary' }}">
                                                    {{ $item->activo ? 'Activo' : 'Inactivo' }}
                                                </span>
                                            </div>
                                            @if($item->recuperable)
                                                <div>
                                                    <span class="badge badge-info">
                                                        <i class="fas fa-recycle mr-1"></i> Recuperable
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Información Detallada -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="detail-card">
                                            <h5 class="mb-3">
                                                <i class="fas fa-info-circle text-info mr-2"></i>
                                                Información General
                                            </h5>

                                            @if($item->descripcion)
                                                <div class="mb-3">
                                                    <div class="detail-label">Descripción</div>
                                                    <div class="detail-value">{{ $item->descripcion }}</div>
                                                </div>
                                            @endif

                                            @if($item->ubicacion)
                                                <div class="mb-3">
                                                    <div class="detail-label">Ubicación</div>
                                                    <div class="detail-value">
                                                        <i class="fas fa-map-marker-alt mr-2"></i>
                                                        {{ $item->ubicacion }}
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="mb-3">
                                                <div class="detail-label">¿Se puede recuperar?</div>
                                                <div class="detail-value">
                                                    @if($item->recuperable)
                                                        <i class="fas fa-check text-success mr-2"></i>
                                                        Sí, las clases perdidas pueden recuperarse
                                                    @else
                                                        <i class="fas fa-times text-muted mr-2"></i>
                                                        No, las clases no se recuperan
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <div class="detail-label">Estado</div>
                                                <div class="detail-value">
                                                    @if($item->activo)
                                                        <i class="fas fa-toggle-on text-success mr-2"></i>
                                                        Activo (afecta la programación de clases)
                                                    @else
                                                        <i class="fas fa-toggle-off text-muted mr-2"></i>
                                                        Inactivo (no afecta la programación)
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="detail-card">
                                            <h5 class="mb-3">
                                                <i class="fas fa-history text-warning mr-2"></i>
                                                Información del Sistema
                                            </h5>

                                            <div class="mb-3">
                                                <div class="detail-label">ID del Registro</div>
                                                <div class="detail-value">#{{ $item->id }}</div>
                                            </div>

                                            <div class="mb-3">
                                                <div class="detail-label">Creado por</div>
                                                <div class="detail-value">
                                                    <i class="fas fa-user mr-2"></i>
                                                    {{ $item->creador->name ?? 'Sistema' }}
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <div class="detail-label">Fecha de Creación</div>
                                                <div class="detail-value">
                                                    <i class="far fa-clock mr-2"></i>
                                                    {{ $item->created_at->format('d/m/Y H:i:s') }}
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <div class="detail-label">Última Modificación</div>
                                                <div class="detail-value">
                                                    <i class="far fa-edit mr-2"></i>
                                                    {{ $item->updated_at->format('d/m/Y H:i:s') }}
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Estadísticas relacionadas -->
                                        <div class="detail-card">
                                            <h5 class="mb-3">
                                                <i class="fas fa-chart-bar text-primary mr-2"></i>
                                                Impacto en el Sistema
                                            </h5>

                                            <div class="row text-center">
                                                <div class="col-6">
                                                    <div class="h4 text-primary mb-1">
                                                        {{ \App\Models\SesionClase::whereDate('fecha', $item->fecha)->count() }}
                                                    </div>
                                                    <small class="text-muted">Sesiones de clase afectadas</small>
                                                </div>
                                                <div class="col-6">
                                                    <div class="h4 text-info mb-1">
                                                        {{ \App\Models\AsistenciaAsignatura::whereDate('fecha', $item->fecha)->count() }}
                                                    </div>
                                                    <small class="text-muted">Registros de asistencia</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Acciones -->
                                <div class="d-flex justify-content-center mt-4 gap-2">
                                    <a href="{{ route('feriados.edit', $item->id) }}"
                                        class="btn btn-warning">
                                        <i class="fas fa-edit"></i> Editar Feriado
                                    </a>
                                    <button type="button" class="btn btn-danger"
                                        onclick="confirmarEliminacion({{ $item->id }}, '{{ $item->nombre }}')">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                    <a href="{{ route('feriados.index') }}"
                                        class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Volver al Listado
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmarEliminacion(id, nombre) {
            Swal.fire({
                title: '¿Eliminar feriado?',
                text: `¿Está seguro de que desea eliminar permanentemente el feriado "${nombre}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Crear formulario para eliminación
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/feriados/${id}`;

                    // Agregar método DELETE
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    form.appendChild(methodInput);

                    // Agregar token CSRF
                    const tokenInput = document.createElement('input');
                    tokenInput.type = 'hidden';
                    tokenInput.name = '_token';
                    tokenInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    form.appendChild(tokenInput);



                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
@endsection
