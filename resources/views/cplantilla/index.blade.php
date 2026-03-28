@extends('cplantilla.bprincipal') 
@section('titulo','Clientes Fijos')
@section('contenidoplantilla') 

<div class="container">     
    <div class="text-xl ms-4 ms-md-5 me-4 me-md-5 fs-4 fw-bold border-bottom mb-4 animate-slide-in">
    </div>
    <!-- Título -->
    <div class="fw-bold border-bottom mb-4 animate-slide-in">
        <h2>Lista de Clientes.</h2>
    </div>

    <style>
        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .animate-slide-in {
            animation: slideInLeft 0.8s ease-out;
        }
    </style>
         
    <style>
        .btn-primary {
            margin-top: 1rem;
            background-color: #FF4343 !important;
            border: none;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        .btn-primary:hover {
                background-color: #FF4343 !important;
                transform: scale(1.02);
        }
    </style>
    
    <nav class="navbar float-right">         
        <form class="form-inline my-2 my-lg-0" method="GET">         
            <input name="buscarpor" class="form-control mr-sm2" type="search" placeholder="Buscar por DNI" arialabel="Search" value="{{ $buscarpor }}">             
            <button class="ml-2 btn btn-primary my-2 my-sm0" type="submit"><i class="fa fa-search"></i></button>         
        </form>     
    </nav>
    <div class="table-responsive">
    <table id="add-row" class="display table table-striped table-hover" >
        <thead class="thead-dark text-center">         
            <tr>             
                <th scope="col">DNI Cliente</th>             
                <th scope="col">Apellido Paterno</th>
                <th scope="col">Apellido Materno</th>                               
                <th scope="col">Nombres</th>
                <th scope="col">Acciones</th>

            </tr>         
        </thead>

        <tbody>
                @foreach($cliente as $itemcliente)                                            
                    <tr>                                         
                        <td class="text-center">{{ $itemcliente->dniCliente }}</td>
                        <td>{{ $itemcliente->apellido_paterno }}</td>
                        <td class="text-center">{{ $itemcliente->apellido_materno }}</td>
                        <td class="text-center">{{ $itemcliente->nombres }}</td>
                        <td class="text-center">
                            <div class="form-button-action">
                                <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-lg" data-original-title="Editar">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Eliminar">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                        </td>                                                    
                    </tr>                  
                @endforeach         
                 
        </tbody>     
    </table>
     {{ $cliente->links()}}  
</div>

<style> 
/* Paginación */
    .pagination {
        display: flex;
        justify-content: left;
        padding: 1rem 0;
        list-style: none;
        gap: 0.3rem;
    }

    .pagination li a, .pagination li span {
        color: var(--color-principal);
        border: 1px solid var(--color-principal);
        padding: 6px 12px;
        border-radius: 4px;
        text-decoration: none;
        transition: all 0.2s ease;
        font-size: 0.9rem;
    }

    .pagination li a:hover, .pagination li span:hover {
        background-color: #f1f1f1; /* Color gris cuando el cursor pasa por encima */
        color: #333;
    }

    /* Páginas activas con fondo negro */
    .pagination .page-item.active .page-link {
        background-color: #000000 !important; /* Fondo negro para la página activa */
        color: white !important; /* Texto blanco en la página activa */
        border-color: #000000 !important; /* Borde negro */
    }

    /* Páginas deshabilitadas */
    .pagination .disabled .page-link {
        color: #ccc;
        border-color: #ccc;
    }
</style>
@endsection 