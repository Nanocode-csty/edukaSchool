@extends('cplantilla.bprincipal')
@section('titulo','Editar Concepto de Pago')
@section('contenidoplantilla')
<x-breadcrumb :module="'conceptospago'" :section="'editar'" />
<div class="container-fluid" id="contenido-principal">
    <div class="row mt-4 ml-1 mr-1">
        <div class="col-12">
            <div class="box_block">
                <!-- Collapse header -->
                <button class="btn btn-block text-left rounded-0 btn_header header_6" type="button" data-toggle="collapse" data-target="#collapseEditarConcepto" aria-expanded="true" aria-controls="collapseEditarConcepto" style="background: #28a745 !important; font-weight: bold; color: white;">
                    <i class="fas fa-edit m-1"></i>&nbsp;Editar Concepto de Pago
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
                                Edita la información del concepto de pago. Modifique los campos necesarios y guarde los cambios.
                            </p>
                        </div>
                    </div>
                </div>
                <!-- Collapse: formulario de edición -->
                <div class="collapse show" id="collapseEditarConcepto">
                    <div class="card card-body rounded-0 border-0 pt-0 pb-2" style="background: transparent;">
                        <!-- Acciones Rápidas -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2 flex-wrap">
                                    <a href="{{ route('conceptospago.index') }}" class="btn btn-outline-primary btn-sm" title="Ver lista de conceptos de pago">
                                        <i class="fas fa-list mr-1"></i>Lista de Conceptos
                                    </a>
                                    <a href="{{ route('conceptospago.show', $concepto->concepto_id) }}" class="btn btn-outline-info btn-sm" title="Ver detalles del concepto">
                                        <i class="fas fa-eye mr-1"></i>Ver Concepto
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Formulario de edición -->
                        <form method="POST" action="{{ route('conceptospago.update', $concepto->concepto_id) }}">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label class="form-label fw-bold text-primary">
                                            <i class="fas fa-tag"></i> Nombre del Concepto
                                        </label>
                                        <input type="text" class="form-control" name="nombre" value="{{ old('nombre', $concepto->nombre) }}" placeholder="Ej: Matrícula Primaria, Mensualidad, etc." required>
                                        @error('nombre')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label fw-bold text-primary">
                                            <i class="fas fa-dollar-sign"></i> Monto (S/)
                                        </label>
                                        <input type="number" step="0.01" class="form-control" name="monto" value="{{ old('monto', $concepto->monto) }}" placeholder="0.00" min="0" required>
                                        @error('monto')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label fw-bold text-primary">
                                            <i class="fas fa-align-left"></i> Descripción
                                        </label>
                                        <textarea class="form-control" name="descripcion" rows="3" placeholder="Descripción detallada del concepto de pago...">{{ old('descripcion', $concepto->descripcion) }}</textarea>
                                        @error('descripcion')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold text-primary">
                                            <i class="fas fa-calendar-alt"></i> Período
                                        </label>
                                        <select class="form-control" name="periodo">
                                            <option value="">Seleccionar período</option>
                                            <option value="Anual" {{ old('periodo', $concepto->periodo) == 'Anual' ? 'selected' : '' }}>Anual</option>
                                            <option value="Semestral" {{ old('periodo', $concepto->periodo) == 'Semestral' ? 'selected' : '' }}>Semestral</option>
                                            <option value="Trimestral" {{ old('periodo', $concepto->periodo) == 'Trimestral' ? 'selected' : '' }}>Trimestral</option>
                                            <option value="Mensual" {{ old('periodo', $concepto->periodo) == 'Mensual' ? 'selected' : '' }}>Mensual</option>
                                            <option value="Único" {{ old('periodo', $concepto->periodo) == 'Único' ? 'selected' : '' }}>Único</option>
                                        </select>
                                        @error('periodo')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold text-primary">
                                            <i class="fas fa-sync"></i> ¿Es recurrente?
                                        </label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="recurrente" value="1" id="recurrente" {{ old('recurrente', $concepto->recurrente) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="recurrente">
                                                Pago recurrente
                                            </label>
                                        </div>
                                        <small class="form-text text-muted">Marque si este concepto se cobra periódicamente</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold text-primary">
                                            <i class="fas fa-calendar"></i> Año Lectivo
                                        </label>
                                        <select class="form-control" name="ano_lectivo_id">
                                            <option value="">Seleccionar año lectivo</option>
                                            @foreach($aniosLectivos as $anio)
                                                <option value="{{ $anio->ano_lectivo_id }}" {{ old('ano_lectivo_id', $concepto->ano_lectivo_id) == $anio->ano_lectivo_id ? 'selected' : '' }}>
                                                    {{ $anio->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('ano_lectivo_id')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold text-primary">
                                            <i class="fas fa-graduation-cap"></i> Nivel Educativo
                                        </label>
                                        <select class="form-control" name="nivel_id">
                                            <option value="">Seleccionar nivel</option>
                                            @foreach($niveles as $nivel)
                                                <option value="{{ $nivel->nivel_id }}" {{ old('nivel_id', $concepto->nivel_id) == $nivel->nivel_id ? 'selected' : '' }}>
                                                    {{ $nivel->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('nivel_id')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="alert alert-warning" style="font-size: 0.9rem;">
                                        <i class="fas fa-exclamation-triangle mr-2"></i>
                                        <strong>Advertencia:</strong> Si modifica el monto de un concepto recurrente,
                                        esto podría afectar a futuros pagos. Considere crear un nuevo concepto en su lugar.
                                    </div>
                                </div>
                            </div>

                            <!-- Botones de acción -->
                            <div class="row mt-3">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save"></i> Actualizar Concepto
                                    </button>
                                    <a href="{{ route('conceptospago.show', $concepto->concepto_id) }}" class="btn btn-secondary">
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
                    const btn = document.querySelector('[data-target="#collapseEditarConcepto"]');
                    const icon = btn.querySelector('.fas.fa-chevron-down');
                    const collapse = document.getElementById('collapseEditarConcepto');
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
