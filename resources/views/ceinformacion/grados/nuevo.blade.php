@extends('cplantilla.bprincipal')
@section('titulo', 'Nuevo Grado')
@section('contenidoplantilla')

    <div class="container-fluid estilo-info margen-movil-2">
        <div class="row mt-4 ml-1 mr-1">
            <div class="col-12 mb-3">
                <div class="box_block">
                    <button class="btn btn-block estilo-info btn_header" type="button" data-toggle="collapse">
                        <i class="fas fa-file-signature"></i>&nbsp;Registrar Nuevo Grado
                    </button>
                </div>
                <div class="collapse show">
                    <div class="card card-body rounded-0 border-0 pt-0">
                        <div class="row margen-movil" style="padding:20px;">
                            <div class="col-12">

                                <form id="form-seccion" method="POST" action="{{ route('grados.store') }}"
                                    autocomplete="off" enctype="multipart/form-data">
                                    @csrf
                                    <div class="card" style="border: none">
                                        <div class="card-header-custom">
                                            <i class="icon-user-following mr-2"></i>
                                            Datos para el Nuevo Grado
                                        </div>

                                        <div class="card-body"
                                            style="border: 2px solid #86D2E3; border-top: none; border-radius: 0px 0px 6px 6px !important;">


                                            <x-form.field name="nivel_id" label="Nivel Educativo" type="select"
                                                :options="$niveles->pluck('nombre', 'nivel_id')->toArray()" />

                                            {{-- Grado --}}

                                            <x-form.field name="nombre" label="Grado Académico" type="select"
                                                :options="[]" />

                                            <x-form.field name="descripcion" label="Descripción" class="disabled-format"
                                                readonly>
                                                <small class="text-muted">
                                                    Campo generado automáticamente por el sistema.
                                                </small>
                                            </x-form.field>

                                        </div>
                                    </div>

                                    <div class="row  d-flex justify-content-between align-items-center gap-4">

                                        <a href="{{ route('grados.index') }}" class="col-md-5 btn btn-color btn-lg ">
                                            <i class="fas fa-arrow-left"></i> Cancelar
                                        </a>
                                        <button id="btnAsignar" type="submit" class=" col-md-6 btn btn-color btn-lg ">
                                            REGISTRAR GRADO
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

    {{-- Script para actualizar grados y descripción --}}
    <script>
        const niveles = @json($niveles);

        document.addEventListener('DOMContentLoaded', function() {
            const nivelSelect = document.getElementById('nivel_id');
            const gradoSelect = document.getElementById('nombre');
            const descripcionInput = document.getElementById('descripcion');

            const gradosPrimaria = [1, 2, 3, 4, 5, 6];
            const gradosSecundaria = [1, 2, 3, 4, 5];

            function actualizarGrados() {
                gradoSelect.innerHTML = '<option value="">-- Seleccione un grado --</option>';
                descripcionInput.value = '';

                const nivelId = nivelSelect.value;
                const nivelNombre = niveles.find(n => n.nivel_id == nivelId)?.nombre;

                if (!nivelId || !nivelNombre) return;

                const grados = nivelNombre.toLowerCase().includes('primaria') ? gradosPrimaria : gradosSecundaria;

                grados.forEach(num => {
                    const opt = document.createElement('option');
                    opt.value = num;
                    opt.text = `${num}°`;
                    gradoSelect.appendChild(opt);
                });
            }

            function actualizarDescripcion() {
                const grado = gradoSelect.value;
                const nivelNombre = niveles.find(n => n.nivel_id == nivelSelect.value)?.nombre || '';
                descripcionInput.value = grado ? `${grado}° de ${nivelNombre.toLowerCase()}` : '';
            }

            nivelSelect.addEventListener('change', () => {
                actualizarGrados();
                actualizarDescripcion();
            });

            gradoSelect.addEventListener('change', actualizarDescripcion);

            // Inicializar si ya hay valores seleccionados (por ejemplo, al regresar con errores)
            if (nivelSelect.value) {
                actualizarGrados();
                if (gradoSelect.value) {
                    actualizarDescripcion();
                }
            }
        });
    </script>

@endsection
