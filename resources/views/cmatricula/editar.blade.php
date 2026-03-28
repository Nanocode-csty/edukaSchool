@extends('cplantilla.bprincipal')
@section('titulo', 'Editar Matrícula')
@section('contenidoplantilla')

    <style>
        .form-bordered {
            margin: 0;
            border: none;
            padding-top: 15px;
            padding-bottom: 15px;
            border-bottom: 1px dashed #eaedf1;
        }

        .card-body.info {
            background: #f3f3f3;
            border-bottom: 1px solid rgba(0, 0, 0, .125);
            border-top: 1px solid rgba(0, 0, 0, .125);
            color: #F59D24;
        }

        .card-body.info p {
            margin-bottom: 0px;
            font-family: "Quicksand", sans-serif;
            font-weight: 600;
            color: #004a92;
        }

        .info-readonly {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .loading-spinner {
            display: none;
            text-align: center;
            color: #666;
        }
    </style>
    <style>
        .estilo-info {
            margin-bottom: 0px;
            font-family: "Quicksand", sans-serif;
            font-weight: 700;
            font-size: clamp(0.9rem, 2.0vw, 0.9rem) !important;

        }
    </style>

    <div class="container-fluid estilo-info" id="contenido-principal">
        <div class="row mt-4 ml-1 mr-1">
            <div class="col-12 col-md-12 col-sm-12 col-lg-12 col-xl-12">
                <div class="box_block">
                    <button class="btn btn-block text-left rounded-0 btn_header header_6" type="button" data-toggle="collapse"
                        data-target="#collapseExample0" aria-expanded="true" aria-controls="collapseExample"
                        style="background: #F59617 !important; font-weight: bold; color: white;">
                        <i class="fas fa-edit m-1"></i>&nbsp;Editar Matrícula N° {{ $matricula->numero_matricula }}
                        <div class="float-right"><i class="fas fa-chevron-down"></i></div>
                    </button>

                    <div class="card-body info">
                        <div class="d-flex">
                            <div class="@flex-fill align-content-le@">
                                <i class="fas fa-exclamation-circle fa-2x"></i>
                            </div>
                            <div class="p-2 flex-fill">
                                <p>
                                    Modifique los campos necesarios para actualizar la información de la matrícula.
                                </p>
                                <p>
                                    Estimado Usuario: Revise cuidadosamente los cambios antes de guardar. Algunos campos no
                                    son editables por seguridad del sistema.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="collapse show" id="collapseExample0">
                        <div class="card card-body rounded-0 border-0 pt-0 pb-2">

                            <form method="POST" action="{{ route('matriculas.update', $matricula->matricula_id) }}"
                                novalidate id="formularioEdicion">
                                @csrf
                                @method('PUT')

                                <div class="row" style="padding:20px;">
                                    <div class="col-12">

                                        <!-- Información del Estudiante (Solo Lectura) -->
                                        <div class="card" style="border: none">
                                            <div
                                                style="background: #E8F5E8; color: #2E7D32; font-weight: bold; border: 2px solid #A5D6A7; border-bottom: 2px solid #A5D6A7; padding: 6px 20px; border-radius:4px 4px 0px 0px;">
                                                Información del Estudiante
                                            </div>

                                            <div class="card-body"
                                                style="border: 2px solid #A5D6A7; border-top: none; border-radius: 0px 0px 4px 4px !important;">
                                                <div class="info-readonly">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <h6><strong>Información Personal:</strong></h6>
                                                            <ul class="list-unstyled">
                                                                <li><strong>DNI:</strong>
                                                                    {{ $matricula->estudiante->dni ?? 'N/A' }}</li>
                                                                <li><strong>Apellidos:</strong>
                                                                    {{ $matricula->estudiante->apellidos ?? 'N/A' }}</li>
                                                                <li><strong>Nombres:</strong>
                                                                    {{ $matricula->estudiante->nombres ?? 'N/A' }}</li>
                                                            </ul>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <h6><strong>Información de Contacto:</strong></h6>
                                                            <ul class="list-unstyled">
                                                                <li><strong>Teléfono:</strong>
                                                                    {{ $matricula->estudiante->telefono ?? 'N/A' }}</li>
                                                                <li><strong>Email:</strong>
                                                                    {{ $matricula->estudiante->email ?? 'N/A' }}</li>
                                                                <li><strong>Estado:</strong>
                                                                    <span class="badge bg-success"
                                                                        style="border: none; color:white; font-weight:bold">{{ Str::upper($matricula->estudiante->estado ?? 'N/A') }}</span>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="form-text text-info">
                                                        <i class="fas fa-info-circle"></i>
                                                        La información del estudiante no se puede modificar desde aquí. Para
                                                        cambios de estudiante, debe crear una nueva matrícula.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Información Académica -->
                                        <div class="card" style="border: none">
                                            <div
                                                style="background: #E0F7FA; color: #0A8CB3; font-weight: bold; border: 2px solid #86D2E3; border-bottom: 2px solid #86D2E3; padding: 6px 20px; border-radius:4px 4px 0px 0px;">
                                                Información Académica
                                            </div>

                                            <div class="card-body"
                                                style="border: 2px solid #86D2E3; border-top: none; border-radius: 0px 0px 4px 4px !important;">

                                                <div class="row form-group">
                                                    <label class="col-md-2 col-form-label">Grado <span
                                                            style="color: #FF5A6A">(*)</span></label>
                                                    <div class="col-md-4">
                                                        <select class="form-control @error('idGrado') is-invalid @enderror"
                                                            id="idGrado" name="idGrado" required>
                                                            <option value="" disabled>Seleccione un grado</option>
                                                            @foreach ($grados as $grado)
                                                                <option value="{{ $grado->grado_id }}"
                                                                    {{ old('idGrado', $matricula->idGrado) == $grado->grado_id ? 'selected' : '' }}>
                                                                    {{ $grado->nombre }} -
                                                                    {{ $grado->nivel->nombre ?? 'Nivel' }}

                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('idGrado')
                                                            <div class="invalid-feedback d-block text-start">
                                                                {{ $message }}</div>
                                                        @enderror
                                                        <span style="color:#FF5A6A; font-size:0.8rem">Seleccione el nuevo
                                                            grado.</span>
                                                    </div>

                                                    <label class="col-md-2 col-form-label">Sección <span
                                                            style="color: #FF5A6A">(*)</span></label>
                                                    <div class="col-md-4">
                                                        <select
                                                            class="form-control @error('idSeccion') is-invalid @enderror"
                                                            name="idSeccion" required>
                                                            <option value="" disabled>Seleccione una sección</option>
                                                            @foreach ($seccionesDisponibles as $seccionData)
                                                                <option value="{{ $seccionData['seccion']->seccion_id }}"
                                                                    {{ old('idSeccion', $matricula->idSeccion) == $seccionData['seccion']->seccion_id ? 'selected' : '' }}>
                                                                    {{ $seccionData['seccion']->nombre }}
                                                                    ({{ $seccionData['disponibles'] }} cupos disponibles)
                                                                </option>
                                                            @endforeach
                                                        </select>

                                                        @error('idSeccion')
                                                            <div class="invalid-feedback d-block text-start">
                                                                {{ $message }}</div>
                                                        @enderror
                                                        <span style="color:#FF5A6A; font-size:0.8rem">Seleccione la nueva
                                                            sección.</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Información de la Matrícula -->
                                        <div class="card" style="border: none">
                                            <div
                                                style="background: #FFF3E0; color: #F57C00; font-weight: bold; border: 2px solid #FFCC80; border-bottom: 2px solid #FFCC80; padding: 6px 20px; border-radius:4px 4px 0px 0px;">
                                                Información de la Matrícula
                                            </div>

                                            <div class="card-body"
                                                style="border: 2px solid #FFCC80; border-top: none; border-radius: 0px 0px 4px 4px !important;">

                                                <div class="row form-group">
                                                    <label class="col-md-2 col-form-label">N° de Matrícula</label>
                                                    <div class="col-md-4">
                                                        <input type="text" class="form-control fw-bold"
                                                            value="{{ $matricula->numero_matricula }}" readonly
                                                            style="background-color: #f8f9fa; cursor: not-allowed; ">

                                                    </div>

                                                    <label class="col-md-2 col-form-label">Fecha de Matrícula</label>
                                                    <div class="col-md-4">
                                                        <input type="text" class="form-control"
                                                            value="{{ \Carbon\Carbon::parse($matricula->fecha_matricula)->format('d/m/Y H:i') }}"
                                                            readonly
                                                            style="background-color: #f8f9fa; cursor: not-allowed;">

                                                    </div>
                                                </div>

                                                <div class="row form-group">
                                                    <label class="col-md-2 col-form-label">Estado <span
                                                            style="color: #FF5A6A">(*)</span></label>
                                                    <div class="col-md-4">
                                                        <select class="form-control @error('estado') is-invalid @enderror"
                                                            id="estado" name="estado" required>
                                                            <option value="" disabled>Seleccione el estado</option>
                                                            <option value="Pre-inscrito"
                                                                {{ old('estado', $matricula->estado) == 'Pre-inscrito' ? 'selected' : '' }}>
                                                                Pre-inscrito
                                                            </option>
                                                            <option value="Matriculado"
                                                                {{ old('estado', $matricula->estado) == 'Matriculado' ? 'selected' : '' }}>
                                                                Matriculado
                                                            </option>
                                                            <option value="Anulado"
                                                                {{ old('estado', $matricula->estado) == 'Anulado' ? 'selected' : '' }}>
                                                                Anulado
                                                            </option>
                                                        </select>
                                                        @error('estado')
                                                            <div class="invalid-feedback d-block text-start">
                                                                {{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <label class="col-md-2 col-form-label">Año Académico</label>
                                                    <div class="col-md-4">
                                                        <input type="text" class="form-control"
                                                            value="{{ $matricula->anio_academico ?? date('Y') }}" readonly
                                                            style="background-color: #f8f9fa; cursor: not-allowed;">

                                                    </div>
                                                </div>

                                                <div class="row form-group">
                                                    <label class="col-md-2 col-form-label">Observaciones</label>
                                                    <div class="col-md-10">
                                                        <textarea class="form-control @error('observaciones') is-invalid @enderror" id="observaciones" name="observaciones"
                                                            rows="3" placeholder="Ingrese observaciones adicionales (opcional)" maxlength="500">{{ old('observaciones', $matricula->observaciones) }}</textarea>
                                                        @error('observaciones')
                                                            <div class="invalid-feedback d-block text-start">
                                                                {{ $message }}</div>
                                                        @enderror

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Información Actual (Referencia) -->
                                        <div class="card" style="border: none">
                                            <div
                                                style="background: #F3E5F5; color: #7B1FA2; font-weight: bold; border: 2px solid #CE93D8; border-bottom: 2px solid #CE93D8; padding: 6px 20px; border-radius:4px 4px 0px 0px;">
                                                <i class="fas fa-info-circle"></i> Información Actual (Referencia)
                                            </div>

                                            <div class="card-body"
                                                style="border: 2px solid #CE93D8; border-top: none; border-radius: 0px 0px 4px 4px !important;">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <h6><strong>Datos Actuales:</strong></h6>
                                                        <ul class="list-unstyled">
                                                            <li><strong>Matrícula:</strong>
                                                                {{ $matricula->numero_matricula }}</li>
                                                            <li><strong>Estado actual:</strong>
                                                                @if ($matricula->estado == 'Matriculado')
                                                                    <span class="badge bg-success"
                                                                        style="border: none">{{ $matricula->estado }}</span>
                                                                @elseif($matricula->estado == 'Pre-inscrito')
                                                                    <span class="badge bg-warning"
                                                                        style="border: none">{{ $matricula->estado }}</span>
                                                                @elseif($matricula->estado == 'Anulado')
                                                                    <span class="badge bg-danger"
                                                                        style="border: none">{{ $matricula->estado }}</span>
                                                                @else
                                                                    <span class="badge bg-secondary"
                                                                        style="border: none">{{ $matricula->estado }}</span>
                                                                @endif
                                                            </li>
                                                            <li><strong>Grado actual:</strong>
                                                                {{ $matricula->grado->nombre ?? 'No asignado' }}</li>
                                                            <li><strong>Sección actual:</strong>
                                                                {{ $matricula->seccion->nombre ?? 'No asignada' }}</li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h6><strong>Fechas:</strong></h6>
                                                        <ul class="list-unstyled">
                                                            <li><strong>Fecha de matrícula:</strong>
                                                                {{ \Carbon\Carbon::parse($matricula->fecha_matricula)->format('d/m/Y H:i') }}
                                                            </li>
                                                            <li><strong>Última actualización:</strong>
                                                                {{ $matricula->updated_at ? \Carbon\Carbon::parse($matricula->updated_at)->format('d/m/Y H:i') : 'Sin actualizaciones' }}
                                                            </li>
                                                            <li><strong>Usuario registro:</strong>
                                                                {{ $matricula->usuarioRegistro->name ?? 'Sistema' }}</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Botones de acción -->
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <a href="{{ route('matriculas.index') }}" class="btn btn-secondary me-2">
                                                    <i class="fas fa-arrow-left"></i> Volver al listado
                                                </a>
                                            </div>
                                            <div>
                                                <button type="submit" class="btn btn-warning"
                                                    style="background: #F59617 !important; border: none;">
                                                    <i class="fas fa-save"></i> Actualizar Matrícula
                                                </button>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <br>
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

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show position-fixed"
            style="top: 20px; right: 20px; z-index: 1050;" role="alert">
            <i class="fas fa-exclamation-circle"></i>
            <strong>Error en el formulario:</strong>
            <ul class="mb-0 mt-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <style>
        .btn-warning {
            background: #F59617 !important;
            border: none;
            transition: background-color 0.2s ease, transform 0.1s ease;
        }

        .btn-warning:hover {
            background-color: #F59619 !important;
            transform: scale(1.01);
        }
    </style>

@endsection

@section('scripts')
    <script>
        // Script simple para auto-hide mensajes
        setTimeout(function() {
            document.querySelectorAll('.alert').forEach(alert => {
                if (alert.classList.contains('fade')) {
                    alert.style.display = 'none';
                }
            });
        }, 5000);

        // Función para restaurar cambios
        function restaurarCambios() {
            if (confirm('¿Está seguro de que desea restaurar todos los campos a sus valores originales?')) {
                try {
                    location.reload();
                } catch (error) {
                    console.error('Error al restaurar:', error);
                    alert('Ups. Error al restaurar los cambios. Estamos trabajando en ello.');
                }
            }
        }

        // Validación simple antes de enviar
        document.getElementById('formularioEdicion').addEventListener('submit', function(e) {
            const estado = document.getElementById('estado').value;

            // Confirmación para cambios importantes
            if (estado === 'Matriculado') {
                if (!confirm(
                        '¿Está seguro de cambiar el estado a "Matriculado"? Esta acción limitará futuras ediciones.'
                    )) {
                    e.preventDefault();
                    return false;
                }
            }

            if (estado === 'Anulado') {
                if (!confirm('¿Está seguro de anular esta matrícula? Esta acción es irreversible.')) {
                    e.preventDefault();
                    return false;
                }
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Carga dinámica de secciones según el grado seleccionado
            const gradoSelect = document.getElementById('idGrado');
            const seccionSelect = document.getElementById('idSeccion');

            if (gradoSelect && seccionSelect) {
                gradoSelect.addEventListener('change', function() {
                    const gradoId = this.value;

                    if (!gradoId) {
                        seccionSelect.innerHTML = '<option value="">Seleccione una sección</option>';
                        document.getElementById('infoSecciones').textContent =
                            'Primero seleccione un grado.';
                        return;
                    }

                    // Mostrar loading
                    seccionSelect.innerHTML = '<option value="">Cargando secciones...</option>';
                    seccionSelect.disabled = true;

                    // Fetch secciones disponibles
                    fetch({{ route('matriculas.secciones.disponibles') }} ? grado_id = $ {
                            gradoId
                        })
                        .then(response => response.json())
                        .then(data => {
                            seccionSelect.disabled = false;

                            if (data.success && data.secciones.length > 0) {
                                seccionSelect.innerHTML =
                                    '<option value="">Seleccione una sección</option>';

                                data.secciones.forEach(seccionData => {
                                    const seccion = seccionData.seccion;
                                    const disponibles = seccionData.disponibles;
                                    const option = document.createElement('option');
                                    option.value = seccion.seccion_id;
                                    option.textContent = $ {
                                        seccion.nombre
                                    }($ {
                                            disponibles
                                        }
                                        cupos disponibles);

                                    // Mantener selección actual si existe
                                    if (seccion.seccion_id ==
                                        {{ $matricula->idSeccion ?? 'null' }}) {
                                        option.selected = true;
                                    }

                                    seccionSelect.appendChild(option);
                                });

                                document.getElementById('infoSecciones').textContent = $ {
                                    data.secciones.length
                                }
                                secciones con cupos disponibles.;
                            } else {
                                seccionSelect.innerHTML =
                                    '<option value="">No hay secciones disponibles</option>';
                                document.getElementById('infoSecciones').textContent =
                                    'No hay secciones disponibles para este grado.';
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            seccionSelect.disabled = false;
                            seccionSelect.innerHTML =
                                '<option value="">Error al cargar secciones</option>';
                            document.getElementById('infoSecciones').textContent =
                                'Ups. Error al cargar las secciones. Estamos trabajando en ello.';
                        });
                });
            }

            // Auto-hide mensajes después de 5 segundos
            setTimeout(function() {
                document.querySelectorAll('.alert').forEach(alert => {
                    if (alert.classList.contains('fade')) {
                        alert.style.display = 'none';
                    }
                });
            }, 5000);
        });

        // Función para restaurar cambios
        function restaurarCambios() {
            if (confirm('¿Está seguro de que desea restaurar todos los campos a sus valores originales?')) {
                try {
                    location.reload();
                } catch (error) {
                    console.error('Error al restaurar:', error);
                    alert('Ups. Error al restaurar los cambios. Estamos trabajando en ello.');
                }
            }
        }

        // Validación antes de enviar el formulario
        document.getElementById('formularioEdicion').addEventListener('submit', function(e) {
            try {
                const grado = document.getElementById('idGrado').value;
                const seccion = document.getElementById('idSeccion').value;
                const estado = document.getElementById('estado').value;

                if (!grado) {
                    e.preventDefault();
                    alert('Por favor seleccione un grado.');
                    return false;
                }

                if (!seccion) {
                    e.preventDefault();
                    alert('Por favor seleccione una sección.');
                    return false;
                }

                if (!estado) {
                    e.preventDefault();
                    alert('Por favor seleccione un estado.');
                    return false;
                }

                // Confirmación para cambios importantes
                if (estado === 'Matriculado') {
                    if (!confirm(
                            '¿Está seguro de cambiar el estado a "Matriculado"? Esta acción limitará futuras ediciones.'
                        )) {
                        e.preventDefault();
                        return false;
                    }
                }

                if (estado === 'Anulado') {
                    if (!confirm('¿Está seguro de anular esta matrícula? Esta acción es irreversible.')) {
                        e.preventDefault();
                        return false;
                    }
                }

            } catch (error) {
                e.preventDefault();
                console.error('Error en validación:', error);
                alert('Ups. Error al procesar el formulario. Estamos trabajando en ello.');
                return false;
            }
        });
    </script>

@endsection
