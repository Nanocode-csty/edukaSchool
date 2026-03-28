@extends('cplantilla.bprincipal')

@section('titulo','Registro de Asignaturas')

@section('contenidoplantilla')

<div class="container-fluid mt-4">
    <div class="card shadow-sm mr-3 ml-3">
        <div class="card-header d-flex justify-content-between align-items-center"
             style="background-color: #f39c12; color: white;">
            <h4 class="mb-0"><i class="fas fa-book"></i> Listado de Asignaturas</h4>
            <a href="{{ route('asignaturas.create') }}" class="btn btn-sm"
               style="background-color: #e67e22; color: white;">
                <i class="fas fa-plus-circle"></i> Nueva Asignatura
            </a>
        </div>

        <div class="card-body">
            {{-- Alertas --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                </div>
                <script>
                    setTimeout(() => document.querySelector('.alert-success')?.remove(), 3000);
                </script>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            @endif

            {{-- Filtro de búsqueda --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <form id="formBuscarAsignatura" method="GET" class="w-100">
                        <div class="input-group search-bar-asignatura">
                            <input type="search" name="buscarpor" id="buscarpor"
                                value="{{ $buscarpor }}"
                                class="form-control"
                                placeholder="Buscar por nombre o código"
                                autocomplete="off">
                            <button type="submit" class="btn">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Loader --}}
            <div id="loaderTabla" class="text-center my-3" style="display: none;">
                <div class="spinner-border" style="color: #e67e22;" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
            </div>

            {{-- Tabla --}}
            <div id="tabla-asignaturas">
                @include('ceinformacion.asignaturas.tabla', ['asignaturas' => $asignaturas])
            </div>
        </div>
    </div>
</div>

{{-- Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const buscarInput = document.getElementById('buscarpor');
        const form = document.getElementById('formBuscarAsignatura');

        function fetchAsignaturas() {
            const valorBuscar = buscarInput.value.trim();
            const loader = document.getElementById('loaderTabla');
            const contenedor = document.getElementById('tabla-asignaturas');

            loader.style.display = 'block';
            contenedor.style.opacity = '0.5';

            fetch(`{{ route('asignaturas.index') }}?buscarpor=${encodeURIComponent(valorBuscar)}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.text())
            .then(html => {
                contenedor.innerHTML = html;
                loader.style.display = 'none';
                contenedor.style.opacity = '1';
            });
        }



        form.addEventListener('submit', function (e) {
            e.preventDefault();

        });

        buscarInput.addEventListener('input', function () {
            if (this.value.trim() === '') {
                fetchAsignaturas();
            }
        });

        form.addEventListener('submit', function (e) {
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
                        title: 'Buscando asignaturas...',
                        showConfirmButton: false,
                        timer: 1200
                    });

                    fetchAsignaturas();
                });
        document.addEventListener('click', function (e) {
            if (e.target.closest('.pagination a')) {
                e.preventDefault();
                const url = e.target.closest('a').getAttribute('href');
                const valorBuscar = buscarInput.value.trim();

                fetch(`${url}&buscarpor=${encodeURIComponent(valorBuscar)}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(response => response.text())
                .then(html => {
                    document.getElementById('tabla-asignaturas').innerHTML = html;
                });
            }

            if (e.target.closest('.form-eliminar')) {
                e.preventDefault();
                const form = e.target.closest('.form-eliminar');

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
<style>
/* campo de búsqueda  */
.search-bar-asignatura {
    border: 2px solid #f5b041;
    border-radius: 8px;
    overflow: hidden;
}

.search-bar-asignatura input {
    border: none;
    border-radius: 0;
    box-shadow: none;
    padding: 0.45rem 0.75rem;
    font-size: 0.95rem;
}

.search-bar-asignatura input:focus {
    box-shadow: none;
    outline: none;
}

.search-bar-asignatura button {
    background-color: #f5b041;
    color: white;
    border: none;
    border-radius: 0;
    padding: 0.45rem 1rem;
    font-weight: bold;
}

.search-bar-asignatura button:hover {
    background-color: #f5b041;
}
</style>
@endsection

