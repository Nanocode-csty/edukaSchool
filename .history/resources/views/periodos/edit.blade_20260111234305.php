@extends('cplantilla.bprincipal')

@section('titulo','Editar Período Académico')

@section('contenidoplantilla')
<x-breadcrumb :module="'periodos'" :section="'editar'" />
<div class="container-fluid" id="contenido-principal">
    <div class="row mt-4 ml-1 mr-1">
        <div class="col-12">
            <div class="box_block">
                <!-- Collapse header -->
                <button class="btn btn-block text-left rounded-0 btn_header header_6" type="button" data-toggle="collapse" data-target="#collapseEditarPeriodo" aria-expanded="true" aria-controls="collapseEditarPeriodo" style="background: #ffc107 !important; font-weight: bold; color: #333;">
                    <i class="fas fa-edit m-1"></i>&nbsp;Editar Período Académico
                    <div class="float-right"><i class="fas fa-chevron-down"></i></div>
                </button>
                <!-- Descripción -->
                <div class="card-body rounded-0 border-0 pt-3 pb-3" style="background: #f3f3f3; border-bottom: 1px solid rgba(0,0,0,.125); border-top: 1px solid rgba(0,0,0,.125); color: #F59D24;">
                    <div class="row justify-content-center align-items-center flex-wrap">
                        <div class="col-auto text-center mb-2 mb-md-0" style="min-width:48px;">
                            <i class="fas fa-exclamation-circle fa-2x"></i>
                        </div>
                        <div class="col px-2" style="text-align:justify;">
                            <p style="margin-bottom: 0px; font-family: 'Quicksand', sans-serif; font-weight: 600; color: #004a92;">
                                Modifica la información del período académico. Asegúrate de que las fechas y configuraciones sean correctas para evitar conflictos con otros períodos.
                            </p>
                        </div>
                    </div>
                </div>
                <!-- Collapse: formulario de edición -->
                <div class="collapse show" id="collapseEditarPeriodo">
                    <div class="card card-body rounded-0 border-0 pt-2 pb-2" style="background: transparent;">
                        <!-- Botones de acción -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <a href="{{ route('periodos.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Volver a la lista
                                </a>
                                <a href="{{ route('periodos.dashboard') }}" class="btn btn-primary">
                                    <i class="fas fa-tachometer-alt"></i> Dashboard
                                </a>
                            </div>
                        </div>

                        <!-- Formulario de edición -->
                        <form method="POST" action="{{ route('periodos.update', $periodo) }}">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold text-primary">
                                            <i class="fas fa-tag"></i> Nombre del Período
                                        </label>
                                        <input type="text" class="form-control" name="nombre" value="{{ old('nombre', $periodo->nombre) }}" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold text-primary">
                                            <i class="fas fa-code"></i> Código
                                        </label>
                                        <input type="text" class="form-control" name="codigo" value="{{ old('codigo', $periodo->codigo) }}" required>
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
                                            <option value="PREINSCRIPCION" {{ old('tipo_periodo', $periodo->tipo_periodo) === 'PREINSCRIPCION' ? 'selected' : '' }}>Pre-inscripción</option>
                                            <option value="INSCRIPCION" {{ old('tipo_periodo', $periodo->tipo_periodo) === 'INSCRIPCION' ? 'selected' : '' }}>Inscripción</option>
                                            <option value="MATRICULA" {{ old('tipo_periodo', $periodo->tipo_periodo) === 'MATRICULA' ? 'selected' : '' }}>Matrícula</option>
                                            <option value="ACADEMICO" {{ old('tipo_periodo', $periodo->tipo_periodo) === 'ACADEMICO' ? 'selected' : '' }}>Académico</option>
                                            <option value="CIERRE" {{ old('tipo_periodo', $periodo->tipo_periodo) === 'CIERRE' ? 'selected' : '' }}>Cierre</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold text-primary">
                                            <i class="fas fa-calendar"></i> Año Lectivo
                                        </label>
                                        <select class="form-control" name="ano_lectivo_id" required>
                                            @foreach($aniosLectivos as $anio)
                                                <option value="{{ $anio->ano_lectivo_id }}" {{ old('ano_lectivo_id', $periodo->ano_lectivo_id) == $anio->ano_lectivo_id ? 'selected' : '' }}>
                                                    {{ $anio->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold text-primary">
                                            <i class="fas fa-calendar-plus"></i> Fecha de Inicio
                                        </label>
                                        <input type="date" class="form-control" name="fecha_inicio" value="{{ old('fecha_inicio', $periodo->fecha_inicio->format('Y-m-d')) }}" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold text-primary">
                                            <i class="fas fa-calendar-minus"></i> Fecha de Fin
                                        </label>
                                        <input type="date" class="form-control" name="fecha_fin" value="{{ old('fecha_fin', $periodo->fecha_fin->format('Y-m-d')) }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold text-primary">
                                            <i class="fas fa-sort-numeric-up"></i> Orden
                                        </label>
                                        <input type="number" class="form-control" value="{{ $periodo->orden }}" readonly style="background-color: #f8f9fa;">
                                        <small class="text-muted">El orden se determina por el tipo de período</small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold text-primary">
                                            <i class="fas fa-info-circle"></i> Estado Actual
                                        </label>
                                        <input type="text" class="form-control" value="{{ $periodo->estado }}" readonly style="background-color: #f8f9fa;">
                                        <small class="text-muted">El estado se determina automáticamente según las fechas</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label fw-bold text-primary">
                                            <i class="fas fa-align-left"></i> Descripción
                                        </label>
                                        <textarea class="form-control" name="descripcion" rows="3">{{ old('descripcion', $periodo->descripcion) }}</textarea>
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
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="configuracion[permite_preinscripcion]" value="1" id="permite_preinscripcion"
                                                               {{ old('configuracion.permite_preinscripcion', $periodo->configuracion['permite_preinscripcion'] ?? false) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="permite_preinscripcion">
                                                            Permite Pre-inscripciones
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="configuracion[permite_inscripcion]" value="1" id="permite_inscripcion"
                                                               {{ old('configuracion.permite_inscripcion', $periodo->configuracion['permite_inscripcion'] ?? false) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="permite_inscripcion">
                                                            Permite Inscripciones
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="configuracion[permite_matricula]" value="1" id="permite_matricula"
                                                               {{ old('configuracion.permite_matricula', $periodo->configuracion['permite_matricula'] ?? false) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="permite_matricula">
                                                            Permite Matrículas
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="configuracion[clases_activas]" value="1" id="clases_activas"
                                                               {{ old('configuracion.clases_activas', $periodo->configuracion['clases_activas'] ?? false) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="clases_activas">
                                                            Clases Activas
                                                        </label>
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
                                        <i class="fas fa-save"></i> Guardar Cambios
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
                    const btn = document.querySelector('[data-target="#collapseEditarPeriodo"]');
                    const icon = btn.querySelector('.fas.fa-chevron-down');
                    const collapse = document.getElementById('collapseEditarPeriodo');
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

    /* Botón header estilo estudiantes */
    .btn_header.header_6 {
        margin-bottom: 0;
        border-radius: 0;
        font-size: 1.1rem;
        padding: 0.75rem 1rem;
        background: #ffc107 !important;
        color: #333;
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
            alert('La fecha de fin debe ser posterior a la fecha de inicio.');
            return false;
        }
    });
});
</script>
@endsection
