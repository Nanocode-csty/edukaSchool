@extends('cplantilla.bprincipal')
@section('titulo', 'Editar Pago')
@section('contenidoplantilla')

    <div class="container-fluid">
        <div class="row mt-4 ml-1 mr-1">
            <div class="col-12 mb-3">
                <div class="box_block">
                    <button style="background: #0A8CB3 !important"
                        class="btn btn-primary btn-block text-left rounded-0 btn_header header_6" type="button"
                        data-toggle="collapse" data-target="#collapsePagoEdit" aria-expanded="true"
                        aria-controls="collapsePagoEdit">
                        <i class="fas fa-money-check-alt m-1"></i>&nbsp;Editar Pago
                        <div class="float-right"><i class="fas fa-chevron-down"></i></div>
                    </button>
                </div>
                <div class="collapse show" id="collapsePagoEdit">
                    <div class="card card-body rounded-0 border-0 pt-0"
                        style="padding-left:0.966666666rem;padding-right:0.9033333333333333rem;">
                        <div class="row" style="padding:20px;">
                            <div class="col-12">
                                <div class="card" style="border: none">
                                    <div
                                        style="background: #E0F7FA; color: #0A8CB3; font-weight: bold; border: 2px solid #86D2E3; border-bottom: 2px solid #86D2E3; padding: 6px 20px; border-radius:4px 4px 0px 0px;">
                                        Datos del Pago
                                    </div>
                                    <div class="card-body"
                                        style="border: 2px solid #86D2E3; border-top: none; border-radius: 0px 0px 4px 4px !important;">
                                        <form method="POST" action="{{ route('pagos.update', $pago->pago_id) }}"
                                            novalidate>
                                            @method('PUT')
                                            @csrf
                                            <div class="row form-group">
                                                <label class="col-md-2 col-form-label">Matrícula <span
                                                        style="color: #FF5A6A">(*)</span></label>
                                                <div class="col-md-4">
                                                    <select class="form-control @error('matriculaId') is-invalid @enderror"
                                                        name="matriculaId" id="matriculaId" required>
                                                        <option value="">Seleccione matrícula</option>
                                                        @foreach ($matriculas as $mat)
                                                            <option value="{{ $mat->matricula_id }}"
                                                                {{ old('matriculaId', $pago->matricula_id) == $mat->matricula_id ? 'selected' : '' }}>
                                                                {{ $mat->numero_matricula }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('matriculaId')
                                                        <div class="invalid-feedback d-block text-start">{{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                <label class="col-md-2 col-form-label">Concepto <span
                                                        style="color: #FF5A6A">(*)</span></label>
                                                <div class="col-md-4">
                                                    <select class="form-control @error('conceptoId') is-invalid @enderror"
                                                        name="conceptoId" id="conceptoId" required>
                                                        <option value="">Seleccione concepto</option>
                                                        @foreach ($conceptos as $concepto)
                                                            <option value="{{ $concepto->concepto_id }}"
                                                                data-monto="{{ $concepto->monto ?? 0 }}"
                                                                {{ old('conceptoId', $pago->concepto_id) == $concepto->concepto_id ? 'selected' : '' }}>
                                                                {{ $concepto->nombre }}
                                                                @if (isset($concepto->nivel) && $concepto->nivel)
                                                                    ({{ $concepto->nivel->nombre }})
                                                                @elseif(isset($concepto->nivel_nombre))
                                                                    ({{ $concepto->nivel_nombre }})
                                                                @endif
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('conceptoId')
                                                        <div class="invalid-feedback d-block text-start">{{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <label class="col-md-2 col-form-label">Monto <span
                                                        style="color: #FF5A6A">(*)</span></label>
                                                <div class="col-md-4">
                                                    <input type="number" step="0.01" min="0"
                                                        class="form-control @error('monto') is-invalid @enderror"
                                                        name="monto" id="monto"
                                                        value="{{ old('monto', $pago->monto) }}"
                                                        placeholder="Monto a pagar" required readonly>
                                                    @error('monto')
                                                        <div class="invalid-feedback d-block text-start">{{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                <label class="col-md-2 col-form-label">Estado <span
                                                        style="color: #FF5A6A">(*)</span></label>
                                                <div class="col-md-4">
                                                    <select class="form-control @error('estado') is-invalid @enderror"
                                                        name="estado" id="estado" required>
                                                        <option value="">Seleccione estado</option>
                                                        <option value="Pendiente"
                                                            {{ old('estado', $pago->estado) == 'Pendiente' ? 'selected' : '' }}>
                                                            Pendiente</option>
                                                        <option value="Pagado"
                                                            {{ old('estado', $pago->estado) == 'Pagado' ? 'selected' : '' }}>
                                                            Pagado</option>
                                                        <option value="Vencido"
                                                            {{ old('estado', $pago->estado) == 'Vencido' ? 'selected' : '' }}>
                                                            Vencido</option>
                                                        <option value="Anulado"
                                                            {{ old('estado', $pago->estado) == 'Anulado' ? 'selected' : '' }}>
                                                            Anulado</option>
                                                    </select>
                                                    @error('estado')
                                                        <div class="invalid-feedback d-block text-start">{{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <label class="col-md-2 col-form-label">Fecha Vencimiento <span
                                                        style="color: #FF5A6A">(*)</span></label>
                                                <div class="col-md-4">
                                                    <input type="date"
                                                        class="form-control @error('fechaVencimiento') is-invalid @enderror"
                                                        name="fechaVencimiento" id="fechaVencimiento"
                                                        value="{{ old('fechaVencimiento', $pago->fecha_vencimiento ? $pago->fecha_vencimiento->format('Y-m-d') : '') }}"
                                                        required>
                                                    @error('fechaVencimiento')
                                                        <div class="invalid-feedback d-block text-start">{{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                <label class="col-md-2 col-form-label">Fecha Pago</label>
                                                <div class="col-md-4">
                                                    <input type="date"
                                                        class="form-control @error('fechaPago') is-invalid @enderror"
                                                        name="fechaPago" id="fechaPago"
                                                        value="{{ old('fechaPago', $pago->fecha_pago ? $pago->fecha_pago->format('Y-m-d') : '') }}">
                                                    @error('fechaPago')
                                                        <div class="invalid-feedback d-block text-start">{{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <label class="col-md-2 col-form-label">Método de Pago</label>
                                                <div class="col-md-4">
                                                    <select class="form-control @error('metodoPago') is-invalid @enderror"
                                                        name="metodoPago" id="metodoPago">
                                                        <option value="">Seleccione método</option>
                                                        <option value="Efectivo"
                                                            {{ old('metodoPago', $pago->metodo_pago) == 'Efectivo' ? 'selected' : '' }}>
                                                            Efectivo</option>
                                                        <option value="Tarjeta de Crédito"
                                                            {{ old('metodoPago', $pago->metodo_pago) == 'Tarjeta de Crédito' ? 'selected' : '' }}>
                                                            Tarjeta de Crédito</option>
                                                        <option value="Transferencia"
                                                            {{ old('metodoPago', $pago->metodo_pago) == 'Transferencia' ? 'selected' : '' }}>
                                                            Transferencia</option>
                                                        <option value="Cheque"
                                                            {{ old('metodoPago', $pago->metodo_pago) == 'Cheque' ? 'selected' : '' }}>
                                                            Cheque</option>
                                                        <option value="Otro"
                                                            {{ old('metodoPago', $pago->metodo_pago) == 'Otro' ? 'selected' : '' }}>
                                                            Otro</option>
                                                    </select>
                                                    @error('metodoPago')
                                                        <div class="invalid-feedback d-block text-start">{{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                <label class="col-md-2 col-form-label">Comprobante URL</label>
                                                <div class="col-md-4">
                                                    <input type="text"
                                                        class="form-control @error('comprobanteUrl') is-invalid @enderror"
                                                        name="comprobanteUrl" id="comprobanteUrl"
                                                        value="{{ old('comprobanteUrl', $pago->comprobante_url) }}"
                                                        maxlength="255" placeholder="URL del comprobante">
                                                    @error('comprobanteUrl')
                                                        <div class="invalid-feedback d-block text-start">{{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <label class="col-md-2 col-form-label">Código Transacción</label>
                                                <div class="col-md-4">
                                                    <input type="text"
                                                        class="form-control @error('codigoTransaccion') is-invalid @enderror"
                                                        name="codigoTransaccion" id="codigoTransaccion"
                                                        value="{{ old('codigoTransaccion', $pago->codigo_transaccion) }}"
                                                        maxlength="100" placeholder="Código único de transacción">
                                                    @error('codigoTransaccion')
                                                        <div class="invalid-feedback d-block text-start">{{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                <label class="col-md-2 col-form-label">Observaciones</label>
                                                <div class="col-md-4">
                                                    <textarea class="form-control @error('observaciones') is-invalid @enderror" name="observaciones" id="observaciones"
                                                        rows="2" maxlength="500" placeholder="Observaciones">{{ old('observaciones', $pago->observaciones) }}</textarea>
                                                    @error('observaciones')
                                                        <div class="invalid-feedback d-block text-start">{{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mt-3">
                                                <a href="{{ route('pagos.index') }}" class="btn btn-secondary">
                                                    <i class="fas fa-arrow-left"></i> Volver al listado
                                                </a>
                                                <button type="submit" class="btn btn-primary"
                                                    style="background: #F59617 !important; border: none;">
                                                    <i class="fas fa-save"></i> Actualizar Pago
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const conceptoSelect = document.getElementById('conceptoId');
            const montoInput = document.getElementById('monto');
            if (conceptoSelect) {
                conceptoSelect.addEventListener('change', function() {
                    const selected = this.options[this.selectedIndex];
                    const monto = selected.getAttribute('data-monto');
                    if (monto) {
                        montoInput.value = parseFloat(monto).toFixed(2);
                    } else {
                        montoInput.value = '';
                    }
                });

                // Si hay un valor seleccionado al cargar, mostrar el monto
                if (conceptoSelect.value) {
                    const selected = conceptoSelect.options[conceptoSelect.selectedIndex];
                    const monto = selected.getAttribute('data-monto');
                    if (monto) {
                        montoInput.value = parseFloat(monto).toFixed(2);
                    }
                }
            }
        });
    </script>
@endsection
