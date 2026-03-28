    <div class="margen-movil-2">
        <div class="card" style="border: none">
            <div class="card-header-custom">
                <i class="icon-location-pin mr-2"></i>
                Información de Residencia
            </div>
            <div class="card-body"
                style="border: 2px solid #86D2E3; border-top: none; border-radius: 0px 0px 6px 6px !important;">

                <div class="row mb-3 align-items-center">
                    <label class="col-12 col-md-2 col-form-label">
                        Región <span style="color: #FF5A6A">(*)</span>
                    </label>
                    <div class="col-12 col-md-10">
                        <select id="region" name="region"
                            class="form-control @error('region') is-invalid @enderror">
                            <option value="" disabled {{ old('region') == '' ? 'selected' : '' }}>
                                Seleccionar
                                Región</option>
                        </select>
                        @error('region')
                            <div class="invalid-feedback d-block text-start">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3 align-items-center">
                    <label class="col-12 col-md-2 col-form-label">
                        Provincia <span style="color: #FF5A6A">(*)</span>
                    </label>
                    <div class="col-12 col-md-4">
                        <select id="provincia" name="provincia"
                            class="form-control @error('provincia') is-invalid @enderror" disabled>
                            <option value="" disabled {{ old('provincia') == '' ? 'selected' : '' }}>
                                Seleccionar Provincia</option>
                        </select>
                        @error('provincia')
                            <div class="invalid-feedback d-block text-start">{{ $message }}</div>
                        @enderror
                    </div>

                    <label class="col-12 col-md-2 col-form-label">
                        Distrito <span style="color: #FF5A6A">(*)</span>
                    </label>
                    <div class="col-12 col-md-4">
                        <select id="distrito" name="distrito"
                            class="form-control @error('distrito') is-invalid @enderror" disabled>
                            <option value="" disabled {{ old('distrito') == '' ? 'selected' : '' }}>
                                Seleccionar
                                Distrito</option>
                        </select>
                        @error('distrito')
                            <div class="invalid-feedback d-block text-start">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3 align-items-center">
                    <label class="col-12 col-md-2 col-form-label">Avenida o calle <span
                            style="color: #FF5A6A">(*)</span></label>
                    <div class="col-12 col-md-4">
                        <input type="text" class="form-control @error('calleEstudiante') is-invalid @enderror"
                            id="calleEstudiante" name="calleEstudiante" placeholder="Avenida o calle" maxlength="20"
                            value="{{ old('calleEstudiante') }}">
                        @error('calleEstudiante')
                            <div class="invalid-feedback d-block text-start">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <label class="col-12 col-md-2 col-form-label">Número</label>
                    <div class="col-12 col-md-4">
                        <input type="text" class="form-control" id="numeroEstudiante" name="numeroEstudiante"
                            placeholder="149" maxlength="5" value="{{ old('numeroEstudiante') }}" inputmode="numeric">
                    </div>
                </div>

                <div class="row mb-3 align-items-center">
                    <label class="col-12 col-md-2 col-form-label">Urbanización</label>
                    <div class="col-12 col-md-10">
                        <input type="text" class="form-control" id="urbanizacionEstudiante"
                            name="urbanizacionEstudiante" placeholder="Urbanización" maxlength="20"
                            value="{{ old('urbanizacionEstudiante') }}">
                    </div>
                </div>
                <div class="row mb-3 align-items-center">
                    <label class="col-12 col-md-2 col-form-label">Referencia</label>
                    <div class="col-12 col-md-10">
                        <input type="text" class="form-control" id="referenciaEstudiante" name="referenciaEstudiante"
                            placeholder="Referencia" maxlength="20" value="{{ old('referenciaEstudiante') }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
