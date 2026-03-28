@extends('cplantilla.bprincipal')
@section('titulo', 'Añadir Docente')
@section('contenidoplantilla')

    <div class="container-fluid estilo-info margen-movil-2">
        <div class="row mt-4 ml-1 mr-1">
            <div class="col-12 mb-3">
                <div class="box_block">
                    <button class="btn btn-primary btn-block btn_header header_6 estilo-info" type="button"
                        data-toggle="collapse" aria-expanded="true">
                        <i class="fas fa-file-signature"></i>&nbsp;Ficha del docente
                        <div class="float-right"><i class="fas fa-chevron-down"></i></div>
                    </button>
                </div>
                <div class="collapse show">
                    <div class="card card-body rounded-0 border-0 pt-0"
                        style="padding-left:0.966666666rem;padding-right:0.9033333333333333rem;">
                        <div class="row margen-movil" style="padding:20px;">
                            <div class="col-12">
                                <!-- Formulario -->
                                <form id="formularioDocente" method="POST" action="{{ route('docente.store') }}"
                                    enctype="multipart/form-data" autocomplete="off">
                                    @csrf

                                    <x-persona.formularioDatosPersonales />
                                    <x-persona.formularioFotoIdentificacion />
                                    <x-persona.formularioDatosDireccion />
                                    <x-persona.formularioDatosContacto />


                                    <div class="card mt-4" style="border: none">
                                        <div class="card-header-custom">
                                            <i class="icon-briefcase mr-2"></i>
                                            Información para el puesto Laboral
                                        </div>
                                        <div class="card-body"
                                            style="border: 2px solid #86D2E3; border-top: none; border-radius: 0px 0px 6px 6px !important;">

                                            <x-form.field name="especialidad" label="Especialidad" type="text" />

                                            <!-- Flatpickr CSS -->
                                            <div class="row mb-3 align-items-center">
                                                <label class="col-12 col-md-2 col-form-label">Fecha de Contrato <span
                                                        style="color: #FF5A6A">(*)</span></label>
                                                <div class="col-12 col-md-10">
                                                    <input type="date"
                                                        class="form-control custom-gold @error('fecha_contratacion') is-invalid @enderror"
                                                        id="fecha_contratacion" name="fecha_contratacion"
                                                        placeholder="YYYY-MM-DD" value="{{ old('fecha_contratacion') }}">
                                                    @if ($errors->has('fecha_contratacion'))
                                                        <div class="invalid-feedback d-block text-start feedback-message">
                                                            {{ $errors->first('fecha_contratacion') }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <script>
                                                flatpickr("#fecha_contratacion", {
                                                    dateFormat: "Y-m-d",
                                                    maxDate: "today",
                                                    locale: "es",
                                                    defaultDate: "today",
                                                    disableMobile: true,
                                                    onChange: function(selectedDates, dateStr, instance) {
                                                        const input = document.getElementById('fecha_contratacion');
                                                        const feedback = input.parentElement.querySelector('.feedback-message');

                                                        if (dateStr) {
                                                            input.classList.remove('is-invalid');
                                                            if (feedback) feedback.remove(); // Borra el mensaje si ya había uno
                                                            input.classList.add('is-valid');
                                                        }
                                                    }
                                                });
                                            </script>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-center mt-4">
                                        <button id="btnAsignar" type="submit" class="btn btn-primary btn-block"
                                            style="background: #FF3F3F !important; border: none; font-weight: bold !important">
                                            REGISTRAR DOCENTE
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
@endsection
<!--PARA ENVIAR SOLO 9 DIGITOS (SIN SUS ESPACIOS)-->
<script>
    document.getElementById('formularioDocente').addEventListener('submit', function() {
        const celularInput = document.getElementById('telefono');
        celularInput.value = celularInput.value.replace(/\s+/g, '');
    });
</script>
