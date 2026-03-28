@extends('cplantilla.bprincipal')
@section('titulo', 'Registro y listado de cursos')
@section('contenidoplantilla')
<style>
    .form-bordered {
        margin: 0;
        border: none;
        padding-top: 15px;
        padding-bottom: 15px;
        border-bottom: 1px dashed #eaedf1;
    }
    .card-body.info {
        background: #f3f3f3;
        border-bottom: 1px solid rgba(0, 0, 0, .125);
        border-top: 1px solid rgba(0, 0, 0, .125);
        color: #F59D24;
    }
    .card-body.info p {
        margin-bottom: 0px;
        font-family: "Quicksand", sans-serif;
        font-weight: 600;
        color: #004a92;
    }
    .estilo-info {
        margin-bottom: 0px;
        font-family: "Quicksand", sans-serif;
        font-weight: 700;
        font-size: clamp(0.9rem, 2.0vw, 0.9rem) !important;
    }
    .btn-primary {
        margin-top: 1rem;
        background: #007bff !important;
        border: none;
        transition: background-color 0.2s ease, transform 0.1s ease;
        margin-bottom: 0px;
        font-family: "Quicksand", sans-serif;
        font-weight: 700;
        font-size: clamp(0.9rem, 2.0vw, 0.9rem) !important;
    }
    .btn-primary:hover {
        background-color: #0056b3 !important;
        transform: scale(1.01);
    }
    .btn-action-group button {
        margin-right: 5px;
    }
    .table-custom tbody tr:nth-of-type(odd) {
        background-color: #f5f5f5;
    }
    .table-custom tbody tr:nth-of-type(even) {
        background-color: #e0e0e0;
    }
    .table-hover tbody tr:hover {
        background-color: #eeffe7 !important;
    }
    .pagination {
        display: flex;
        justify-content: left;
        padding: 1rem 0;
        list-style: none;
        gap: 0.3rem;
    }
    .pagination li a,
    .pagination li span {
        color: var(--color-principal);
        border: 1px solid var(--color-principal);
        padding: 6px 12px;
        border-radius: 4px;
        text-decoration: none;
        transition: all 0.2s ease;
        font-size: 0.9rem;
    }
    .pagination li a:hover,
    .pagination li span:hover {
        background-color: #f1f1f1;
        color: #333;
    }
    .pagination .page-item.active .page-link {
        background-color: #0A8CB3 !important;
        color: white !important;
        border-color: #000000 !important;
    }
    .pagination .disabled .page-link {
        color: #ccc;
        border-color: #ccc;
    }
    .btn-action-group .btn-link {
        margin-right: 8px;
        padding: 0 6px;
        border: none;
        background: none;
        box-shadow: none;
    }
    .btn-action-group .btn-link:focus {
        outline: none;
        box-shadow: none;
    }
    #loaderPrincipal[style*="display: flex"] {
        display: flex !important;
        justify-content: center;
        align-items: center;
        position: absolute !important;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        width: 100%;
        height: 100%;
        z-index: 2000;
    }
</style>
<div class="container-fluid" id="contenido-principal" style="position: relative;">
    @include('ccomponentes.loader', ['id' => 'loaderPrincipal'])
    <div class="row mt-4 ml-1 mr-1">
        <div class="col-12">
            <div class="box_block">
                <button class="estilo-info btn btn-block text-left rounded-0 btn_header header_6" type="button"
                    data-toggle="collapse" data-target="#collapseExample0" aria-expanded="true"
                    aria-controls="collapseExample"
                    style="background: #0A8CB3 !important; font-weight: bold; color: white;">
                    <i class="fas fa-file-signature m-1"></i>&nbsp;Registro y listado de cursos
                    <div class="float-right"><i class="fas fa-chevron-down"></i></div>
                </button>
                <div class="card-body info">
                    <div class="d-flex ">
                        <div>
                            <i class="fas fa-exclamation-circle fa-2x"></i>
                        </div>
                        <div class="p-2 flex-fill">
                            <p>
                                En esta sección, podrás añadir nuevos cursos y consultar la información de los que ya están registrados.
                            </p>
                            <p>
                                Estimado Usuario: Asegúrate de revisar cuidadosamente los datos antes de guardarlos, ya que esta información será utilizada para la gestión académica y administrativa del curso.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="collapse show" id="collapseExample0">
                    <div class="card card-body rounded-0 border-0 pt-0 pb-2" style="background-color: #fcfffc !important">
                        <div class="row align-items-center">
                            <div class="col-md-6 mb-md-0 d-flex justify-content-start">
                                <a href="{{ route('registrarcurso.create') }}" class="btn btn-primary w-100" id="nuevoRegistroBtn" style="background: #007bff !important; border: none;">
                                    <i class="fa fa-plus mx-2"></i> Nuevo Curso
                                </a>
                            </div>
                            <div class="col-md-6 d-flex justify-content-md-end justify-content-start estilo-info">
                                <form id="formBuscar" method="GET" class="w-100" style="max-width: 100%;">
                                    <div class="input-group">
                                        <input name="buscarpor" id="inputBuscar" class="form-control mt-3" type="search" placeholder="Buscar curso" aria-label="Search" autocomplete="off" style="border-color: #007bff;">
                                        <button class="btn btn-primary nuevo-boton" type="submit" style="border-top-left-radius: 0 !important; border-bottom-left-radius: 0 !important; background: #007bff !important; border: none;">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @if (session('datos'))
                            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                                {{ session('datos') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        <div class="row form-bordered align-items-center"></div>
                        <div class="table-responsive mt-2">
                            <table class="table-hover table table-custom text-center" style="border: 1px solid #0A8CB3; border-radius: 10px; overflow: hidden;">
                                <thead class="table-hover estilo-info">
                                    <tr>
                                        <th scope="col">Código</th>
                                        <th scope="col">Grado</th>
                                        <th scope="col">Sección</th>
                                        <th scope="col">Año Lectivo</th>
                                        <th scope="col">Aula</th>
                                        <th scope="col">Profesor Principal</th>
                                        <th scope="col">Cupo Máximo</th>
                                        <th scope="col">Estado</th>
                                        <th scope="col">Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cursos as $curso)
                                        <tr>
                                            <td>{{ $curso->curso_id }}</td>
                                            <td>{{ $curso->grado->nombre ?? 'No asignado' }}</td>
                                            <td>{{ $curso->seccion->nombre ?? 'No asignado' }}</td>
                                            <td>{{ $curso->anoLectivo->nombre ?? 'No asignado' }}</td>
                                            <td>{{ $curso->aula->nombre ?? 'No asignado' }}</td>
                                            <td>{{ $curso->profesor->nombres ?? 'No asignado' }}</td>
                                            <td>{{ $curso->cupo_maximo }}</td>
                                            <td>{{ $curso->estado }}</td>
                                            <td class="btn-action-group">
                                                <a href="{{ route('registrarcurso.edit', $curso->curso_id) }}" class="btn btn-link btn-sm p-0 btn-editar-curso" title="Editar">
                                                    <i class="fas fa-pen" style="color: #007bff; font-size: 1.2rem;"></i>
                                                </a>
                                                <a href="{{ route('registrarcurso.confirmar', $curso->curso_id) }}" class="btn btn-link btn-sm p-0 btn-eliminar-curso" title="Eliminar">
                                                    <i class="fas fa-times" style="color: #dc3545; font-size: 1.3rem;"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div id="tabla-cursos">
                            {{ $cursos->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const loader = document.getElementById('loaderPrincipal');
        const contenido = document.getElementById('contenido-principal');
        if (loader) loader.style.display = 'none';
        if (contenido) contenido.style.opacity = '1';
        // Loader para ir a create (Nuevo Curso)
        const nuevoRegistroBtn = document.getElementById('nuevoRegistroBtn');
        if (nuevoRegistroBtn) {
            nuevoRegistroBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (loader) {
                    loader.style.display = 'flex';
                }
                setTimeout(() => {
                    window.location.href = this.href;
                }, 800);
            });
        }
        document.getElementById('formBuscar').addEventListener('submit', function(e) {
            e.preventDefault();
            if (loader) {
                loader.style.display = 'flex';
            }
            const valor = document.getElementById('inputBuscar').value.trim();
            fetch(`{{ route('registrarcurso.index') }}?buscarpor=${encodeURIComponent(valor)}`)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('tabla-cursos').innerHTML = data;
                    if (loader) loader.style.display = 'none';
                });
        });
        // Loader para editar
        document.querySelectorAll('.btn-editar-curso').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                if (loader) {
                    loader.style.display = 'flex';
                }
                setTimeout(() => {
                    window.location.href = this.href;
                }, 800);
            });
        });
        // Loader para eliminar (confirmar)
        document.querySelectorAll('.btn-eliminar-curso').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                if (loader) {
                    loader.style.display = 'flex';
                }
                setTimeout(() => {
                    window.location.href = this.href;
                }, 800);
            });
        });
        window.addEventListener('pageshow', function(event) {
            if (loader) loader.style.display = 'none';
            if (contenido) contenido.style.opacity = '1';
        });
    });
</script>
@endsection
