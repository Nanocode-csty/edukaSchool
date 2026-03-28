@extends('cplantilla.bprincipal')
@section('titulo', 'Años Lectivos')
@section('contenidoplantilla')

    <style>
        /* El detalle tendrá el mismo color que la fila anterior DETALLE DE IMPARES*/
        .collapse-row.odd {
            background-color: #f5f5f5;
        }

        /* El detalle tendrá el mismo color que la fila anterior DETALLE DE PARES*/
        .collapse-row.even {
            background-color: #e0e0e0;
        }
    </style>
    @include('ccomponentes.loader', ['id' => 'loaderPrincipalAnioLectivo']) {{-- Usa este ID --}}

    <div class="container-fluid margen-movil-2" id="contenido-principal" style="position: relative;">
        <div class="row mt-4 ml-1 mr-1">
            <div class="col-12">
                <div class="card">
                    <div class="estilo-info btn btn-block btn_header header_6">
                        <div class="row  d-flex justify-content-between align-items-center">
                            <div class="col-12 col-md-7"><i class="fas fa-file-signature m-1"></i>&nbsp;Registro y listado de
                                Años Lectivos</div>
                            <div class="col-12 col-md-5 mb-md-0 d-flex justify-content-start">
                                <a href="{{ route('aniolectivo.create') }}" id="nuevoRegistroBtns"
                                    class="btn w-100 btn-color-header d-flex justify-content-between align-items-center"
                                    type="button">
                                    <i class="ti ti-pencil-plus me-2 mx-1" style="font-size:24px"></i> REGISTRAR AÑO LECTIVO
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body" style="background-color: #fff">
                        <div class="alert alert-interactive" id="alertMatricula">
                            <i class="fas fa-info-circle me-2"></i>
                            <div class="alert-content">
                                <strong>En esta sección podrás registrar años lectivos y consultar sobre los ya
                                    registrados.</strong>
                                <br>

                                <span>
                                    <b>Estimado Usuario:</b> Asegúrate de revisar cuidadosamente los datos antes de
                                    guardarlos. Esta información será utilizada para la gestión académica y administrativa
                                    de la I.E. Cualquier modificación posterior debe realizarse con responsabilidad y
                                    siguiendo los protocolos establecidos por la institución.
                                </span>
                            </div>
                            <button class="btn-close" onclick="cerrarAlert('alertMatricula')">&times;</button>
                        </div>

                        <script>
                            function cerrarAlert(id) {
                                const alertBox = document.getElementById(id);
                                alertBox.style.animation = 'fadeOut 0.5s ease-out forwards';

                                setTimeout(() => {
                                    alertBox.style.display = 'none';
                                }, 500);
                            }
                        </script>

                        <div class="row mb-2 d-flex align-items-center">
                            <!-- Botón a la izquierda -->
                            <div class="col-12 col-md-6 mb-md-0 d-flex justify-content-start">
                                <a href="{{ route('aniolectivo.create') }}" id="nuevoRegistroBtns"
                                    class="btn w-100 mt-3 btn-color d-flex justify-content-between align-items-center"
                                    type="button">
                                    <i class="ti ti-user-plus me-2 mx-2" style="font-size:24px"></i> ORDENAR POR
                                </a>
                            </div>

                            <!-- Buscador a la derecha -->
                            <div class="col-12 col-md-6 d-flex justify-content-md-end justify-content-start estilo-info">
                                <form id="formBuscar" method="GET" class="w-100" style="max-width: 100%;">
                                    <div class="input-group">
                                        <input id="inputBuscar" name="buscarpor" class="form-control mt-3" type="search"
                                            placeholder="Ingrese Nombre del año lectivo" aria-label="Search"
                                            value="{{ $buscarpor }}" autocomplete="off"
                                            style="border-color: #F59617; font-size:16px !important;">
                                        <button class="btn btn-color mt-3" type="submit"
                                            style="border-top-left-radius: 0 !important; border-bottom-left-radius: 0 !important; ">
                                            <i class="ti ti-search" style="font-size:24px"></i>
                                        </button>

                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="row form-bordered align-items-center"></div>

                        <div id="tabla-wrapper" style="position: relative; min-height: 200px;">

                            @include('ccomponentes.loader', ['id' => 'loaderTablaAnioLectivo'])

                            <div id="tabla-aniolectivo" style="position: relative;">
                                @include('ceinformacion.añolectivo.tabla')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
        </div>
    </div>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const buscarInput = document.getElementById('buscarpor');
            const form = document.getElementById('formBuscarAnioLectivo');

            function fetchAnioLectivo(url = null) {
                const valorBuscar = buscarInput.value.trim();
                const loader = document.getElementById('loaderTabla');
                const contenedor = document.getElementById('tabla-anoslectivos');
                const fetchUrl = url ||
                    `{{ route('aniolectivo.index') }}?buscarpor=${encodeURIComponent(valorBuscar)}`;

                loader.classList.remove('d-none');
                contenedor.style.opacity = '0.5';

                fetch(fetchUrl, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        contenedor.innerHTML = html;
                        loader.classList.add('d-none');
                        contenedor.style.opacity = '1';
                    });
            }

            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const valorBuscar = buscarInput.value.trim();

                if (valorBuscar === '') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Campo vacío',
                        text: 'Por favor, ingresa un término de búsqueda.',
                        confirmButtonColor: '#0b5e80'
                    });
                    return;
                }

                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'info',
                    title: 'Buscando años lectivos...',
                    showConfirmButton: false,
                    timer: 1200
                });

                fetchAnioLectivo();
            });

            buscarInput.addEventListener('input', function() {
                if (this.value.trim() === '') {
                    fetchAnioLectivo();
                }
            });

            document.addEventListener('click', function(e) {
                if (e.target.closest('.pagination a')) {
                    e.preventDefault();
                    const url = e.target.closest('a').getAttribute('href');
                    fetchAnioLectivo(url);
                }

                if (e.target.closest('.form-eliminar')) {
                    e.preventDefault();
                    const form = e.target.closest('form');

                    Swal.fire({
                        title: '¿Está seguro?',
                        text: 'Esta acción no se puede deshacer.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                }
            });
        });
    </script>
@endsection
