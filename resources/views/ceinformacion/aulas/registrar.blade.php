@extends('cplantilla.bprincipal')
@section('titulo','Registro de Aulas')

@section('contenidoplantilla')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center"
             style="background-color: #218c74; color: white;">
            <h4 class="mb-0"><i class="fas fa-school"></i> Listado de Aulas</h4>
            <a href="{{ route('aulas.create') }}" class="btn btn-sm"
               style="background-color: #38ada9; color: white;">
                <i class="fas fa-plus-circle"></i> Nueva Aula
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

            {{-- Búsqueda --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <form id="formBuscarAula" method="GET" class="d-flex">
                        <input type="search" name="buscarpor" id="buscarpor" value="{{ $buscarpor }}"
                            class="form-control"
                            placeholder="Buscar por nombre, ubicación, tipo o capacidad"
                            autocomplete="off">
                        <button type="submit" class="btn">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Loader --}}
            <div id="loaderTabla" class="text-center my-3" style="display: none;">
                <div class="spinner-border text-success" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
            </div>

            {{-- Tabla --}}
            <div id="tabla-aulas">
                @include('ceinformacion.aulas.tabla', ['aulas' => $aulas])
            </div>
        </div>
    </div>
</div>
<style>
    /* campo de búsqueda */
#formBuscarAula input[type="search"] {
    font-size: 0.95rem;
    padding: 0.45rem 0.75rem;
    border-radius: 6px 0 0 6px;
    border: 1px solid #38ada9;
    border-right: none;
    transition: box-shadow 0.2s ease;
}

#formBuscarAula input[type="search"]:focus {
    box-shadow: 0 0 6px rgba(51, 217, 178, 0.4);
    border-color: #33d9b2;
    outline: none;
}

#formBuscarAula button {
    border-radius: 0 6px 6px 0;
    border: 1px solid #1f6361;
    border-left: none;
    border-right: none;
    background-color: #2b7463;
    color: white;
    font-weight: bold;
}

</style>
@endsection
@section('scripts')
{{-- Script --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const buscarInput = document.getElementById('buscarpor');
        const form = document.getElementById('formBuscarAula');

        function fetchAulas() {
            const valorBuscar = buscarInput.value.trim();
            const loader = document.getElementById('loaderTabla');
            const contenedor = document.getElementById('tabla-aulas');

            loader.style.display = 'block';
            contenedor.style.opacity = '0.5';

            fetch(`{{ route('aulas.index') }}?buscarpor=${encodeURIComponent(valorBuscar)}`, {
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
                title: 'Buscando Aulas...',
                showConfirmButton: false,
                timer: 1200
            });

            fetchAulas();
        });
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'info',
                title: 'Buscando aulas...',
                showConfirmButton: false,
                timer: 1200
            });

            fetchAulas();
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
                title: 'Buscando aulas...',
                showConfirmButton: false,
                timer: 1200
            });

            fetchAulas();
        });
        buscarInput.addEventListener('input', function () {
            if (this.value.trim() === '') {
                fetchAulas();
            }
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
                    document.getElementById('tabla-aulas').innerHTML = html;
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

@endsection

