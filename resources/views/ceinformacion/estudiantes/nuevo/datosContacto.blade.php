   
    <div class="margen-movil-2">
        <div class="card" style="border: none">
            <div class="card-header-custom">
                <i class="icon-phone mr-2"></i>
                Información de Contacto
            </div>
            <div class="card-body"
                style="border: 2px solid #86D2E3; border-top: none; border-radius: 0px 0px 6px 6px !important;">

                <div class="row mb-3 align-items-center">
                    <label class="col-12 col-md-2 col-form-label">
                        Celular actual <span style="color: #FF5A6A">(*)</span>
                    </label>
                    <div class="col-12 col-md-10">

                        <input type="text"
                            class="form-control @error('numeroCelularEstudiante') is-invalid @enderror"
                            id="numeroCelularEstudiante" name="numeroCelularEstudiante" placeholder="N.° celular"
                            value="{{ old('numeroCelularEstudiante') }}" inputmode="numeric">

                        @error('numeroCelularEstudiante')
                            <div class="invalid-feedback d-block text-start">{{ $message }}
                            </div>
                        @enderror

                    </div>
                </div>

                <div class="row mb-3 align-items-center">
                    <label class="col-12 col-md-2 col-form-label">
                        Correo electrónico <span style="color: #FF5A6A">(*)</span>
                    </label>
                    <div class="col-12 col-md-10">

                        <input type="email" class="form-control @error('correoEstudiante') is-invalid @enderror"
                            id="correoEstudiante" name="correoEstudiante" placeholder="correo@estudiante.com"
                            maxlength="100" value="{{ old('correoEstudiante') }}">
                        @error('correoEstudiante')
                            <div class="invalid-feedback d-block text-start">{{ $message }}
                            </div>
                        @enderror

                    </div>
                </div>

                <div class="mt-4">
                    <div class="alert alert-info d-flex align-items-center" role="alert">
                        <i class="fas fa-info-circle me-2 mr-2"></i>
                        <span>Puedes registrar el número de celular y el correo electrónico de los padres o
                            apoderados.</span>
                    </div>
                </div>
                <!-- Mensaje informativo -->
            </div>
        </div>
    </div>
