@extends('cplantilla.bprincipal')
@section('titulo','Crear Nuevo Período Académico')
@section('contenidoplantilla')
<x-breadcrumb :module="'periodos'" :section="'crear'" />
<div class="container-fluid" id="contenido-principal">
    <div class="row mt-4 ml-1 mr-1">
        <div class="col-12">
            <div class="box_block">
                <!-- Collapse header -->
                <button class="btn btn-block text-left rounded-0 btn_header header_6" type="button" data-toggle="collapse" data-target="#collapseCrearPeriodo" aria-expanded="true" aria-controls="collapseCrearPeriodo" style="background: #28a745 !important; font-weight: bold; color: white;">
                    <i class="fas fa-plus m-1"></i>&nbsp;Crear Nuevo Período Académico
                    <div class="float-right"><i class="fas fa-chevron-down"></i></div>
                </button>
                <!-- Descripción (siempre visible, dentro del bloque) -->
                <div class="card-body rounded-0 border-0 pt-3 pb-3" style="background: #f3f3f3; border-bottom: 1px solid rgba(0,0,0,.125); border-top: 1px solid rgba(0,0,0,.125); color: #F59D24;">
                    <div class="row justify-content-center align-items-center flex-wrap">
                        <div class="col-auto text-center mb-2 mb-md-0" style="min-width:48px;">
                            <i class="fas fa-exclamation-circle fa-2x"></i>
                        </div>
                        <div class="col px-2" style="text-align:justify;">
                            <p style="margin-bottom: 0px; font-family: 'Quicksand', sans-serif; font-weight: 600; color: #004a92;">
                                Crea un nuevo período académico. Asegúrate de que las fechas y configuraciones sean correctas para evitar conflictos con otros períodos existentes.
                            </p>
                        </div>
                    </div>
                </div>
                <!-- Collapse: formulario de creación -->
                <div class="collapse show" id="collapseCrearPeriodo">
                    <div class="card card-body rounded-0 border-0 pt-0 pb-2" style="background: transparent;">
                        <!-- Acciones Rápidas -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2 flex-wrap">
                                    <a href="{{ route('periodos.index') }}" class="btn btn-outline-primary btn-sm" title="Ver lista de períodos">
                                        <i class="fas fa-list mr-1"></i>Lista de Períodos
                                    </a>
                                    <a href="{{ route('periodos.dashboard') }}" class="btn btn-outline-success btn-sm" title="Ver dashboard">
                                        <i class="fas fa-tachometer-alt mr-1"></i>Dashboard
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Formulario de creación -->
                        <form method="POST" action="{{ route('periodos.store') }}">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold text-primary">
                                            <i class="fas fa-tag"></i> Nombre del Período
                                        </label>
                                        <input type="text" class="form-control" name="nombre" value="{{ old('nombre') }}" required>
                                        @error('nombre')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold text-primary">
                                            <i class="fas fa-code"></i> Código
                                            <small class="text-muted">
                                                <i class="fas fa-info-circle"></i> Se genera automáticamente
                                            </small>
                                        </label>
                                        <input type="text" class="form-control" name="codigo" value="{{ old('codigo') }}" readonly style="background-color: #f8f9fa;" placeholder="Selecciona un tipo de período">
                                        @error('codigo')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold text-primary">
                                            <i class="fas fa-calendar-alt"></i> Tipo de Período
                                        </label>
                                        <select class="form-control" name="tipo_periodo" required>
                                            <option value="">Seleccionar tipo</option>
                                            <option value="PREINSCRIPCION" {{ old('tipo_periodo') === 'PREINSCRIPCION' ? 'selected' : '' }}>Pre-inscripción</option>
                                            <option value="INSCRIPCION" {{ old('tipo_periodo') === 'INSCRIPCION' ? 'selected' : '' }}>Inscripción</option>
                                            <option value="MATRICULA" {{ old('tipo_periodo') === 'MATRICULA' ? 'selected' : '' }}>Matrícula</option>
                                            <option value="ACADEMICO" {{ old('tipo_periodo') === 'ACADEMICO' ? 'selected' : '' }}>Académico</option>
                                            <option value="CIERRE" {{ old('tipo_periodo') === 'CIERRE' ? 'selected' : '' }}>Cierre</option>
                                        </select>
                                        @error('tipo_periodo')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold text-primary">
                                            <i class="fas fa-calendar"></i> Año Lectivo
                                        </label>
                                        <select class="form-control" name="ano_lectivo_id" required>
                                            <option value="">Seleccionar año lectivo</option>
                                            @foreach($aniosLectivos as $anio)
                                                <option value="{{ $anio->ano_lectivo_id }}" {{ old('ano_lectivo_id') == $anio->ano_lectivo_id ? 'selected' : '' }}>
                                                    {{ $anio->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('ano_lectivo_id')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold text-primary">
                                            <i class="fas fa-calendar-plus"></i> Fecha de Inicio
                                        </label>
                                        <input type="date" class="form-control" name="fecha_inicio" value="{{ old('fecha_inicio') }}" required>
                                        @error('fecha_inicio')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold text-primary">
                                            <i class="fas fa-calendar-minus"></i> Fecha de Fin
                                        </label>
                                        <input type="date" class="form-control" name="fecha_fin" value="{{ old('fecha_fin') }}" required>
                                        @error('fecha_fin')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label fw-bold text-primary">
                                            <i class="fas fa-align-left"></i> Descripción
                                        </label>
                                        <textarea class="form-control" name="descripcion" rows="3">{{ old('descripcion') }}</textarea>
                                        @error('descripcion')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Configuración avanzada -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="mb-0">
                                                <i class="fas fa-cogs"></i> Configuración Avanzada
                                                <small class="text-muted ms-2">
                                                    <i class="fas fa-info-circle"></i> Controla qué acciones se permiten durante este período
                                                </small>
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="alert alert-info" style="font-size: 0.9rem;">
                                                <strong>💡 ¿Para qué sirve?</strong> Estas configuraciones determinan qué operaciones pueden realizar los usuarios durante este período específico. Por ejemplo, si "Permite Pre-inscripciones" está activado, los estudiantes podrán hacer pre-inscripciones solo durante las fechas de este período.
                                            </div>

                                            <div id="configuracion-avanzada" class="row">
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input config-checkbox" type="checkbox" name="configuracion[permite_preinscripcion]" value="1" id="permite_preinscripcion"
                                                               {{ old('configuracion.permite_preinscripcion') ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="permite_preinscripcion">
                                                            <i class="fas fa-user-plus text-info me-1"></i>
                                                            Permite Pre-inscripciones
                                                        </label>
                                                        <small class="text-muted d-block ms-3">
                                                            Los estudiantes pueden hacer pre-inscripciones anticipadas
                                                        </small>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input config-checkbox" type="checkbox" name="configuracion[permite_inscripcion]" value="1" id="permite_inscripcion"
                                                               {{ old('configuracion.permite_inscripcion') ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="permite_inscripcion">
                                                            <i class="fas fa-clipboard-check text-success me-1"></i>
                                                            Permite Inscripciones
                                                        </label>
                                                        <small class="text-muted d-block ms-3">
                                                            Los estudiantes pueden formalizar su inscripción
                                                        </small>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input config-checkbox" type="checkbox" name="configuracion[permite_matricula]" value="1" id="permite_matricula"
                                                               {{ old('configuracion.permite_matricula') ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="permite_matricula">
                                                            <i class="fas fa-graduation-cap text-primary me-1"></i>
                                                            Permite Matrículas
                                                        </label>
                                                        <small class="text-muted d-block ms-3">
                                                            Los estudiantes pueden completar su matrícula oficial
                                                        </small>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input config-checkbox" type="checkbox" name="configuracion[clases_activas]" value="1" id="clases_activas"
                                                               {{ old('configuracion.clases_activas') ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="clases_activas">
                                                            <i class="fas fa-chalkboard-teacher text-warning me-1"></i>
                                                            Clases Activas
                                                        </label>
                                                        <small class="text-muted d-block ms-3">
                                                            El período académico tiene clases en curso
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Botones de acción -->
                            <div class="row mt-3">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save"></i> Crear Período
                                    </button>
                                    <a href="{{ route('periodos.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Cancelar
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <script>
                document.addEventListener('DOMContentLoaded', function () {
                    // Collapse icon toggle
                    const btn = document.querySelector('[data-target="#collapseCrearPeriodo"]');
                    const icon = btn.querySelector('.fas.fa-chevron-down');
                    const collapse = document.getElementById('collapseCrearPeriodo');
                    collapse.addEventListener('show.bs.collapse', function () {
                        icon.classList.remove('fa-chevron-down');
                        icon.classList.add('fa-chevron-up');
                    });
                    collapse.addEventListener('hide.bs.collapse', function () {
                        icon.classList.remove('fa-chevron-up');
                        icon.classList.add('fa-chevron-down');
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>

<style>
    /* Animación de entrada */
    @keyframes slideInLeft {
        from { opacity: 0; transform: translateX(-50px);}
        to { opacity: 1; transform: translateX(0);}
    }
    .animate-slide-in { animation: slideInLeft 0.8s ease-out; }

    /* Formulario */
    .form-label {
        font-weight: 600 !important;
        color: #0A8CB3 !important;
        margin-bottom: 0.5rem;
    }

    .form-control {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        transition: border-color 0.2s ease;
    }

    .form-control:focus {
        border-color: #0A8CB3;
        box-shadow: 0 0 0 0.2rem rgba(10, 139, 179, 0.25);
    }

    .invalid-feedback {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    /* Card de configuración */
    .card {
        border: 2px solid #dee2e6;
        border-radius: 10px;
    }

    .card-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-bottom: 1px solid #dee2e6;
        font-weight: bold;
        color: #0A8CB3;
    }

    /* Checkboxes */
    .form-check-input:checked {
        background-color: #0A8CB3;
        border-color: #0A8CB3;
    }

    .form-check-label {
        font-weight: 500;
        color: #495057;
    }

    /* Botón header estilo estudiantes */
    .btn_header.header_6 {
        margin-bottom: 0;
        border-radius: 0;
        font-size: 1.1rem;
        padding: 0.75rem 1rem;
        background: #28a745 !important;
        color: white;
        border: none;
        box-shadow: none;
    }
    .btn_header .float-right {
        float: right;
    }
    .btn_header i.fas.fa-chevron-down,
    .btn_header i.fas.fa-chevron-up {
        transition: transform 0.2s;
    }
</style>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Validación básica del formulario
    $('form').on('submit', function(e) {
        const fechaInicio = new Date($('input[name="fecha_inicio"]').val());
        const fechaFin = new Date($('input[name="fecha_fin"]').val());

        if (fechaInicio >= fechaFin) {
            e.preventDefault();
            Swal.fire({
                title: 'Fechas inválidas',
                text: 'La fecha de fin debe ser posterior a la fecha de inicio.',
                icon: 'warning',
                confirmButtonColor: '#ffc107'
            });
            return false;
        }
    });

    // Auto-llenado del código basado en el tipo de período
    $('select[name="tipo_periodo"]').on('change', function() {
        const tipo = $(this).val();
        const anio = new Date().getFullYear();

        if (tipo) {
            let codigoBase = '';
            switch(tipo) {
                case 'PREINSCRIPCION':
                    codigoBase = 'PREINSCRIPCION';
                    break;
                case 'INSCRIPCION':
                    codigoBase = 'INSCRIPCION';
                    break;
                case 'MATRICULA':
                    codigoBase = 'MATRICULA';
                    break;
                case 'ACADEMICO':
                    codigoBase = 'ACADEMICO';
                    break;
                case 'CIERRE':
                    codigoBase = 'CIERRE';
                    break;
            }

            const codigoInput = $('input[name="codigo"]');
            if (!codigoInput.val() || codigoInput.val().startsWith('PREINSCRIPCION_') ||
                codigoInput.val().startsWith('INSCRIPCION_') || codigoInput.val().startsWith('MATRICULA_') ||
                codigoInput.val().startsWith('ACADEMICO_') || codigoInput.val().startsWith('CIERRE_')) {
                codigoInput.val(codigoBase + '_' + anio);
            }
        }
    });
});
</script>
@endsection
