@extends('cplantilla.bprincipal')
@section('titulo', 'Editar Concepto de Pago')
@section('contenidoplantilla')

<style>
    .form-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }

    .form-header {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        border-radius: 15px 15px 0 0;
    }

    .form-control:focus {
        border-color: #f5576c;
        box-shadow: 0 0 0 0.2rem rgba(245, 87, 108, 0.25);
    }

    .custom-switch .custom-control-label::before {
        background-color: #e9ecef;
        border-color: #adb5bd;
    }

    .custom-switch .custom-control-input:checked~.custom-control-label::before {
        background-color: #28a745;
        border-color: #28a745;
    }
</style>

<div class="container-fluid">
    <div class="row mt-4 ml-1 mr-1">
        <div class="col-md-8 offset-md-2">
            <div class="card form-card">
                <div class="card-header form-header">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-edit mr-3 fa-lg"></i>
                        <div>
                            <h4 class="mb-0 font-weight-bold">Editar Concepto de Pago</h4>
                            <p class="mb-0 opacity-75">Modifique la información del concepto #{{ $concepto->concepto_id }}</p>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('conceptospago.update', $concepto->concepto_id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group mb-4">
                                    <label for="nombre" class="form-label font-weight-bold">
                                        <i class="fas fa-tag mr-2 text-primary"></i>
                                        Nombre del Concepto *
                                    </label>
                                    <input type="text" class="form-control form-control-lg @error('nombre') is-invalid @enderror"
                                           id="nombre" name="nombre" value="{{ old('nombre', $concepto->nombre) }}"
                                           placeholder="Ej: Matrícula Primaria, Mensualidad, etc." required>
                                    @error('nombre')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group mb-4">
                                    <label for="monto" class="form-label font-weight-bold">
                                        <i class="fas fa-dollar-sign mr-2 text-success"></i>
                                        Monto (S/) *
                                    </label>
                                    <input type="number" step="0.01" class="form-control form-control-lg @error('monto') is-invalid @enderror"
                                           id="monto" name="monto" value="{{ old('monto', $concepto->monto) }}"
                                           placeholder="0.00" min="0" required>
                                    @error('monto')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label for="descripcion" class="form-label font-weight-bold">
                                <i class="fas fa-align-left mr-2 text-info"></i>
                                Descripción
                            </label>
                            <textarea class="form-control @error('descripcion') is-invalid @enderror"
                                      id="descripcion" name="descripcion" rows="3"
                                      placeholder="Descripción detallada del concepto de pago...">{{ old('descripcion', $concepto->descripcion) }}</textarea>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="periodo" class="form-label font-weight-bold">
                                        <i class="fas fa-calendar-alt mr-2 text-warning"></i>
                                        Período
                                    </label>
                                    <select class="form-control @error('periodo') is-invalid @enderror"
                                            id="periodo" name="periodo">
                                        <option value="">Seleccionar período</option>
                                        <option value="Anual" {{ old('periodo', $concepto->periodo) == 'Anual' ? 'selected' : '' }}>Anual</option>
                                        <option value="Semestral" {{ old('periodo', $concepto->periodo) == 'Semestral' ? 'selected' : '' }}>Semestral</option>
                                        <option value="Trimestral" {{ old('periodo', $concepto->periodo) == 'Trimestral' ? 'selected' : '' }}>Trimestral</option>
                                        <option value="Mensual" {{ old('periodo', $concepto->periodo) == 'Mensual' ? 'selected' : '' }}>Mensual</option>
                                        <option value="Único" {{ old('periodo', $concepto->periodo) == 'Único' ? 'selected' : '' }}>Único</option>
                                    </select>
                                    @error('periodo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="form-label font-weight-bold">
                                        <i class="fas fa-sync mr-2 text-secondary"></i>
                                        ¿Es recurrente?
                                    </label>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="recurrente" name="recurrente" value="1"
                                               {{ old('recurrente', $concepto->recurrente) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="recurrente">
                                            <span class="switch-text">Pago único / Recurrente</span>
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">Marque si este concepto se cobra periódicamente</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="ano_lectivo_id" class="form-label font-weight-bold">
                                        <i class="fas fa-calendar mr-2 text-primary"></i>
                                        Año Lectivo
                                    </label>
                                    <select class="form-control @error('ano_lectivo_id') is-invalid @enderror"
                                            id="ano_lectivo_id" name="ano_lectivo_id">
                                        <option value="">Seleccionar año lectivo</option>
                                        @foreach($aniosLectivos as $anio)
                                            <option value="{{ $anio->ano_lectivo_id }}"
                                                    {{ old('ano_lectivo_id', $concepto->ano_lectivo_id) == $anio->ano_lectivo_id ? 'selected' : '' }}>
                                                {{ $anio->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('ano_lectivo_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="nivel_id" class="form-label font-weight-bold">
                                        <i class="fas fa-graduation-cap mr-2 text-success"></i>
                                        Nivel Educativo
                                    </label>
                                    <select class="form-control @error('nivel_id') is-invalid @enderror"
                                            id="nivel_id" name="nivel_id">
                                        <option value="">Seleccionar nivel</option>
                                        @foreach($niveles as $nivel)
                                            <option value="{{ $nivel->nivel_id }}"
                                                    {{ old('nivel_id', $concepto->nivel_id) == $nivel->nivel_id ? 'selected' : '' }}>
                                                {{ $nivel->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('nivel_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <strong>Advertencia:</strong> Si modifica el monto de un concepto recurrente,
                                esto podría afectar a futuros pagos. Considere crear un nuevo concepto en su lugar.
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-white border-0">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('conceptospago.show', $concepto->concepto_id) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save mr-2"></i>
                                Actualizar Concepto
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
