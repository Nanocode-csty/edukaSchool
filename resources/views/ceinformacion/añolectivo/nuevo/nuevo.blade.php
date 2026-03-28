@extends('cplantilla.bprincipal')
@section('titulo', 'Nuevo Año Lectivo')
@section('contenidoplantilla')

    @include('ccomponentes.loader', ['id' => 'loaderPrincipal'])
    <div class="container-fluid  estilo-info margen-movil-2" id="contenido-principal" style="position: relative;">
        <div class="row mt-4 ml-1 mr-1">
            <div class="col-12 mb-3">
                <div class="box_block">
                    <button class="btn btn-block estilo-info btn_header" type="button" data-toggle="collapse">
                        <i class="fas fa-file-signature"></i>&nbsp;Registrar Nuevo Año Lectivo
                    </button>
                </div>
                <div class="collapse show">
                    <div class="card card-body rounded-0 border-0 pt-0">
                        <div class="row margen-movil" style="padding:20px;">
                            <div class="col-12">

                                <form id="formAnioLectivo" method="POST" action="{{ route('aniolectivo.store') }}"
                                    autocomplete="off" enctype="multipart/form-data">
                                    @csrf
                                    <div class="card" style="border: none">
                                        <div class="card-header-custom">
                                            <i class="icon-user-following mr-2"></i>
                                            Datos para el Nuevo Año Lectivo
                                        </div>

                                        <div class="card-body"
                                            style="border: 2px solid #86D2E3; border-top: none; border-radius: 0px 0px 6px 6px !important;">

                                            <x-form.field name="nombre" label="Nombre del Año Lectivo" />

                                            <x-form.field name="fecha_inicio" label="Fecha de Inicio" />

                                            <x-form.field name="fecha_fin" label="Fecha de Fin" />

                                            <x-form.field name="estado" label="Estado" type="select" :options="[
                                                'Activo' => 'Activo',
                                                'Planificación' => 'Planificación',
                                                'Finalizado' => 'Finalizado',
                                            ]" />

                                            <x-form.field name="descripcion" label="Descripción" />

                                        </div>
                                    </div>

                                    <div class="row  d-flex justify-content-between align-items-center gap-4">

                                        <a id="cancelar" href="{{ route('aniolectivo.index') }}"
                                            class="col-md-5 btn btn-color btn-lg ">
                                            <i class="fas fa-arrow-left"></i> Cancelar
                                        </a>
                                        <button id="btnAsignar" type="submit" class=" col-md-6 btn btn-color btn-lg">
                                            REGISTRAR AÑO LECTIVO
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
