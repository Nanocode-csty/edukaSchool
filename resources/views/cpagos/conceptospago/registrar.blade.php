@extends('cplantilla.bprincipal')
@section('titulo','Tasas de Pago')
@section('contenidoplantilla')
<div class="container-fluid" id="contenido-principal">
    <div class="row mt-4 ml-1 mr-1">
        <div class="col-12">
            <div class="box_block">
                <!-- Collapse header -->
                <button class="btn btn-block text-left rounded-0 btn_header header_6" type="button" data-toggle="collapse" data-target="#collapseTablaConceptos" aria-expanded="true" aria-controls="collapseTablaConceptos" style="background: #0A8CB3 !important; font-weight: bold; color: white;">
                    <i class="fas fa-list-alt m-1"></i>&nbsp;Listado de Conceptos de Pago Registrados
                    <div class="float-right"><i class="fas fa-chevron-down"></i></div>
                </button>
                <!-- Descripción (siempre visible, dentro del bloque) -->
                <div class="card-body rounded-0 border-0 pt-3 pb-3" style="background: #f3f3f3; border-bottom: 1px solid rgba(0,0,0,.125); border-top: 1px solid rgba(0,0,0,.125); color: #F59D24;">
                    <div class="row justify-content-center align-items-center flex-wrap">
                        <div class="col-auto text-center mb-2 mb-md-0" style="min-width:48px;">
                            <i class="fas fa-exclamation-circle fa-2x"></i>
                        </div>
                        <div class="col px-2" style="text-align:justify;">
                            <p style="margin-bottom: 0px; font-family: 'Quicksand', sans-serif; font-weight: 600; color: #004a92;">
                                En esta sección puedes consultar todos los conceptos de pago registrados en el sistema, así como buscar por nombre. Los conceptos de pago son utilizados para gestionar cobros recurrentes o únicos según el periodo y nivel educativo.
                            </p>
                            <p style="font-family: 'Quicksand', sans-serif; font-weight: 600; color: #004a92;">
                                Recuerda revisar cuidadosamente cada concepto de pago antes de continuar, ya que es fundamental para asegurar la correcta gestión de tus obligaciones. Si detectas algún error o necesitas hacer cambios, comunícate con el área administrativa correspondiente.
                            </p>
                        </div>
                    </div>
                </div>
                <!-- Collapse: solo la tabla y buscador -->
                <div class="collapse show" id="collapseTablaConceptos">
                    <div class="card card-body rounded-0 border-0 pt-0 pb-2" style="background: transparent;">
                        <!-- Buscador -->
                        <div class="row align-items-center mb-3">
                            <div class="col-md-6"></div>
                            <div class="col-md-6 d-flex justify-content-md-end justify-content-start">
                                <form id="formBuscar" method="GET" class="w-100" style="max-width: 100%;">
                                    <div class="input-group">
                                        <input id="inputBuscar" name="buscarpor" class="form-control mt-3" type="search" placeholder="Buscar Nombre" aria-label="Search" value="{{ $buscarpor }}" autocomplete="off" style="border-color: #F59617">
                                        <button class="btn btn-primary nuevo-boton" type="submit" style="border-top-left-radius: 0 !important; border-bottom-left-radius: 0 !important;">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- Tabla -->
                        <div class="table-responsive mt-2">
                            <table id="add-row" class="table-hover table" style="border: 1px solid #0A8CB3; border-radius: 10px; overflow: hidden;">
                                <thead class="text-center table-hover" style="background-color: #f8f9fa; color: #0A8CB3; border:#0A8CB3 !important">
                                    <tr>

                                        <th scope="col">Nivel Educativo</th>
                                        <th scope="col">Nombre</th>
                                        <th scope="col">Descripción</th>
                                        <th scope="col">Monto</th>
                                        <th scope="col">Recurrente</th>
                                        <th scope="col">Periodo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($conceptos as $item)
                                        <tr>

                                            <td>{{ $item->nivel_nombre }}</td>
                                            <td>{{ $item->nombre }}</td>
                                            <td>{{ $item->descripcion }}</td>
                                            <td class="text-center">{{ number_format($item->monto, 2) }}</td>
                                            <td class="text-center">
                                                @if($item->recurrente)
                                                    <span class="badge badge-success">Sí</span>
                                                @else
                                                    <span class="badge badge-secondary">No</span>
                                                @endif
                                            </td>
                                            <td class="text-center">{{ $item->periodo }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $conceptos->links() }}
                        </div>
                    </div>
                </div>
                <script>
                document.addEventListener('DOMContentLoaded', function () {
                    // Collapse icon toggle
                    const btn = document.querySelector('[data-target="#collapseTablaConceptos"]');
                    const icon = btn.querySelector('.fas.fa-chevron-down');
                    const collapse = document.getElementById('collapseTablaConceptos');
                    collapse.addEventListener('show.bs.collapse', function () {
                        icon.classList.remove('fa-chevron-down');
                        icon.classList.add('fa-chevron-up');
                    });
                    collapse.addEventListener('hide.bs.collapse', function () {
                        icon.classList.remove('fa-chevron-up');
                        icon.classList.add('fa-chevron-down');
                    });
                });
                </script>
            </div>
        </div>
    </div>
    <style>
        /* Animación de entrada */
        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-50px);}
            to { opacity: 1; transform: translateX(0);}
        }
        .animate-slide-in { animation: slideInLeft 0.8s ease-out; }

        /* Tabla y paginación */
        #add-row td, #add-row th {
            padding: 4px 8px;
            font-size: 14px;
            vertical-align: middle;
            height: 52px;
        }
        .table-hover tbody tr:hover {
            background-color: #FFF4E7 !important;
        }
        .badge-success {
            background-color: #28a745;
            color: #fff;
        }
        .badge-secondary {
            background-color: #6c757d;
            color: #fff;
        }
        /* Paginación */
        .pagination {
            display: flex;
            justify-content: left;
            padding: 1rem 0;
            list-style: none;
            gap: 0.3rem;
        }
        .pagination li a, .pagination li span {
            color: var(--color-principal, #0A8CB3);
            border: 1px solid var(--color-principal, #0A8CB3);
            padding: 6px 12px;
            border-radius: 4px;
            text-decoration: none;
            transition: all 0.2s ease;
            font-size: 0.9rem;
        }
        .pagination li a:hover, .pagination li span:hover {
            background-color: #f1f1f1;
            color: #333;
        }
        .pagination .page-item.active .page-link {
            background-color: #0A8CB3 !important;
            color: white !important;
            border-color: #0A8CB3 !important;
        }
        .pagination .disabled .page-link {
            color: #ccc;
            border-color: #ccc;
        }
        /* Botón primario */
        .btn-primary {
            margin-top: 1rem;
            background: #F59617 !important;
            border: none;
            transition: background-color 0.2s ease, transform 0.1s ease;
        }
        .btn-primary:hover {
            background-color: #F59619 !important;
            transform: scale(1.01);
        }
        /* Botón header estilo estudiantes */
        .btn_header.header_6 {
            margin-bottom: 0;
            border-radius: 0;
            font-size: 1.1rem;
            padding: 0.75rem 1rem;
            background: #0A8CB3 !important;
            color: white;
            border: none;
            box-shadow: none;
        }
        .btn_header .float-right {
            float: right;
        }
        .btn_header i.fas.fa-chevron-down,
        .btn_header i.fas.fa-chevron-up {
            transition: transform 0.2s;
        }
    </style>
    <script>
        // Validación de búsqueda
        document.addEventListener('DOMContentLoaded', function () {
            const formBuscar = document.getElementById('formBuscar');
            const inputBuscar = document.getElementById('inputBuscar');
            formBuscar.addEventListener('submit', function (e) {
                if (inputBuscar.value.trim() === '') {
                    e.preventDefault();
                    alert('Por favor, ingresa texto para buscar.');
                }
            });
        });
    </script>
@endsection
