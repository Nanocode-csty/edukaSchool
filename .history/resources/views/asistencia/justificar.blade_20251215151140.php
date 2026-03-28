@extends('cplantilla.bprincipal')

@section('titulo', 'Justificar Inasistencia')

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
            <div class="col-12 mb-3">
                <div class="box_block">
                    <button style="background: #0A8CB3 !important; border:none"
                        class="btn btn-primary btn-block text-left rounded-0 btn_header header_6 estilo-info" type="button"
                        data-toggle="collapse" data-target="#collapseJustificar" aria-expanded="true"
                        aria-controls="collapseJustificar">
                        <i class="fas fa-file-medical"></i>&nbsp;Justificar Inasistencia
                        <div class="float-right"><i class="fas fa-chevron-down"></i></div>
                    </button>
                </div>
                <div class="collapse show" id="collapseJustificar">
                    <div class="card card-body rounded-0 border-0 pt-0"
                        style="padding-left:0.966666666rem;padding-right:0.9033333333333333rem;">
                        <div class="row margen-movil" style="padding:20px;">
                            <div class="col-12">
                                <div class="card" style="border: none">
                                    <div
                                        style="background: #E0F7FA; color: #0A8CB3; font-weight: bold; border: 2px solid #86D2E3; border-bottom: 2px solid #86D2E3; padding: 6px 20px; border-radius:4px 4px 0px 0px;">
                                        <i class="fas fa-user-graduate mr-2"></i>
                                        Seleccionar Estudiante a Justificar
                                    </div>

                                    <div class="card-body"
                                        style="border: 2px solid #86D2E3; border-top: none; border-radius: 0px 0px 4px 4px !important;">
                                        <form id="formularioJustificacion" method="POST" action="{{ route('asistencia.guardar-justificacion') }}"
                                            autocomplete="off" enctype="multipart/form-data">
                                            @csrf

                                            <div class="row form-group">
                                                <label class="col-md-2 col-form-label">Estudiante <span
                                                        style="color: #FF5A6A">(*)</span></label>
                                                <div class="col-md-10">
                                                    <select name="matricula_id" id="matricula_id" class="form-control @error('matricula_id') is-invalid @enderror" required>
                                                        <option value="">Seleccione un estudiante</option>
                                                        @foreach($matriculas as $matricula)
                                                            <option value="{{ $matricula->matricula_id }}" {{ old('matricula_id') == $matricula->matricula_id ? 'selected' : '' }}>
                                                                {{ $matricula->estudiante->nombres }} {{ $matricula->estudiante->apellidos }}
                                                                - {{ $matricula->grado->nombre }} {{ $matricula->seccion->nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('matricula_id')
                                                        <div class="invalid-feedback d-block text-start">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="row form-group">
                                                <label class="col-md-2 col-form-label">Fecha de Ausencia <span
                                                        style="color: #FF5A6A">(*)</span></label>
                                                <div class="col-md-4">
                                                    <input type="date"
                                                        class="form-control @error('fecha') is-invalid @enderror"
                                                        id="fecha" name="fecha"
                                                        value="{{ old('fecha') }}"
                                                        min="{{ date('Y-m-d') }}"
                                                        max="{{ date('Y-m-d', strtotime('+30 days')) }}" required>
                                                    @error('fecha')
                                                        <div class="invalid-feedback d-block text-start">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                    </div>
                                </div>

                                <div class="card mt-4" style="border: none">
                                    <div
                                        style="background: #E0F7FA; color: #0A8CB3; font-weight: bold; border: 2px solid #86D2E3; border-bottom: 2px solid #86D2E3; padding: 6px 20px; border-radius:4px 4px 0px 0px;">
                                        <i class="fas fa-file-alt mr-2"></i>
                                        Detalles de la Justificación
                                    </div>
                                    <div class="card-body"
                                        style="border: 2px solid #86D2E3; border-top: none; border-radius: 0px 0px 4px 4px !important;">

                                        <div class="row form-group">
                                            <label class="col-md-2 col-form-label">Motivo <span
                                                    style="color: #FF5A6A">(*)</span></label>
                                            <div class="col-md-10">
                                                <select name="motivo" id="motivo" class="form-control @error('motivo') is-invalid @enderror" required>
                                                    <option value="">Seleccione un motivo</option>
                                                    <option value="Enfermedad" {{ old('motivo') == 'Enfermedad' ? 'selected' : '' }}>Enfermedad</option>
                                                    <option value="Cita médica" {{ old('motivo') == 'Cita médica' ? 'selected' : '' }}>Cita médica</option>
                                                    <option value="Problemas familiares" {{ old('motivo') == 'Problemas familiares' ? 'selected' : '' }}>Problemas familiares</option>
                                                    <option value="Transporte" {{ old('motivo') == 'Transporte' ? 'selected' : '' }}>Problemas de transporte</option>
                                                    <option value="Otro" {{ old('motivo') == 'Otro' ? 'selected' : '' }}>Otro</option>
                                                </select>
                                                @error('motivo')
                                                    <div class="invalid-feedback d-block text-start">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row form-group">
                                            <label class="col-md-2 col-form-label">Descripción <span
                                                    style="color: #FF5A6A">(*)</span></label>
                                            <div class="col-md-10">
                                                <textarea name="descripcion" id="descripcion"
                                                    class="form-control @error('descripcion') is-invalid @enderror"
                                                    rows="4" placeholder="Describa detalladamente el motivo de la ausencia..."
                                                    required>{{ old('descripcion') }}</textarea>
                                                @error('descripcion')
                                                    <div class="invalid-feedback d-block text-start">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row form-group">
                                            <label class="col-md-2 col-form-label">Documento de Soporte</label>
                                            <div class="col-md-10">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" name="documento_justificacion"
                                                        id="documento_justificacion" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                                                        onchange="updateFileName()">
                                                    <label class="custom-file-label" for="documento_justificacion" id="file-label">
                                                        Seleccionar documento (PDF, imágenes o documentos de Word)
                                                    </label>
                                                </div>
                                                <small class="text-muted mt-1 d-block">
                                                    <i class="fas fa-info-circle"></i>
                                                    Opcional: Adjunte un documento que respalde la justificación (certificado médico, etc.)
                                                </small>
                                                @error('documento_justificacion')
                                                    <div class="invalid-feedback d-block text-start">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="d-flex justify-content-center mt-4">
                                    <button type="submit" class="btn btn-primary btn-block"
                                        style="background: #FF3F3F !important; border: none; font: bold !important">
                                        <span style="font:bold"><i class="fas fa-paper-plane"></i> Enviar Justificación</span>
                                    </button>
                                    <a href="{{ route('asistencia.index') }}" class="btn btn-secondary ml-2">Cancelar</a>
                                </div>

                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .custom-file-label::after {
            content: "Seleccionar" !important;
            background-color: #DAA520;
            color: white;
            border-left: none;
        }

        .form-control {
            border: 1px solid #DAA520;
        }
    </style>
@endsection

@section('scripts')
    <script>
        function updateFileName() {
            const input = document.getElementById('documento_justificacion');
            const label = document.getElementById('file-label');

            if (input.files && input.files[0]) {
                label.textContent = input.files[0].name;
            } else {
                label.textContent = 'Seleccionar documento (PDF, imágenes o documentos de Word)';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('formularioJustificacion');

            form.addEventListener('submit', function(e) {
                const requiredFields = form.querySelectorAll('[required]');
                let isValid = true;

                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        field.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        field.classList.remove('is-invalid');
                        field.classList.add('is-valid');
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    alert('Por favor, complete todos los campos obligatorios.');
                }
            });

            // Validación de fecha (debe ser desde hoy hasta 30 días adelante)
            const fechaInput = document.getElementById('fecha');
            fechaInput.addEventListener('change', function() {
                const selectedDate = new Date(this.value);
                const today = new Date();
                const maxDate = new Date();
                maxDate.setDate(today.getDate() + 30);

                if (selectedDate < today.setHours(0,0,0,0)) {
                    this.setCustomValidity('La fecha no puede ser anterior a hoy');
                    this.classList.add('is-invalid');
                } else if (selectedDate > maxDate) {
                    this.setCustomValidity('La fecha no puede ser más de 30 días en el futuro');
                    this.classList.add('is-invalid');
                } else {
                    this.setCustomValidity('');
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                }
            });
        });
    </script>
@endsection
