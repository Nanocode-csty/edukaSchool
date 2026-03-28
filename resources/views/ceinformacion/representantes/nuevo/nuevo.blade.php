@extends('cplantilla.bprincipal')
@section('titulo', 'Registrar Representante')
@section('contenidoplantilla')

    <div class="container-fluid estilo-info margen-movil-2">
        <div class="row mt-4 ml-1 mr-1">
            <div class="col-12 mb-3">
                <div class="box_block">
                    <button class="btn btn-block estilo-info btn_header" type="button" data-toggle="collapse">
                        <i class="fas fa-file-signature"></i>&nbsp;Ficha del Representante
                    </button>
                </div>
                <div class="collapse show">
                    <div class="card card-body rounded-0 border-0 pt-0">
                        <div class="row margen-movil" style="padding:20px;">
                            <div class="col-12">

                                <form id="formularioRepresentante" method="POST"
                                    action="{{ route('representante.store') }}" autocomplete="off"
                                    enctype="multipart/form-data">
                                    @csrf

                                    <x-persona.formularioDatosPersonales />

                                    <x-persona.formularioFotoIdentificacion />

                                    <x-persona.formularioDatosDireccion />

                                    <x-persona.formularioDatosContacto />

                                    <div class="card" style="border: none">
                                        <div class="card-header-custom">
                                            <i class="icon-user-following mr-2"></i>
                                            Datos del Representante
                                        </div>

                                        <div class="card-body"
                                            style="border: 2px solid #86D2E3; border-top: none; border-radius: 0px 0px 6px 6px !important;">
                                            <x-form.field name="ocupacion" label="Ocupación" />
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-center mt-4">
                                        <button id="btnAsignar" type="submit" class="btn btn-color btn-lg w-100">
                                            REGISTRAR REPRESENTANTE
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

@section('scripts')

    <!--PARA ENVIAR SOLO 9 DIGITOS (SIN SUS ESPACIOS)-->
    <script>
        document.getElementById('formularioRepresentante').addEventListener('submit', function() {
            const celularInput = document.getElementById('telefono');
            celularInput.value = celularInput.value.replace(/\s+/g, '');
        });
    </script>

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error al registrar',
                text: "{{ session('error') }}",
                confirmButtonColor: '#3085d6'
            });
        </script>
    @endif

@endsection
