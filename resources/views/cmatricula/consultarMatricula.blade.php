<div class="container-fluid margen-movil-2">
    <div class="row mt-4 ml-1 mr-1">
        <div class="col-md-12">
            <div class="card">

                <div class="card-body">
                    <div class="alert alert-info color">
                        <i class="fas fa-info-circle"></i>
                        <strong>En esta sección, podrás matricular nuevos estudiantes y consultar la información de las
                            matrículas que ya están registradas.</strong>
                        <br>
                        <h5>
                            Estimado Usuario: Asegúrate de revisar cuidadosamente los datos antes de guardarlos, ya que
                            esta
                            información será utilizada para la gestión académica y administrativa del estudiante.
                            Cualquier
                            modificación posterior debe ser realizada con responsabilidad y siguiendo los protocolos
                            establecidos por la institución.
                        </h5>

                    </div>

                    <!-- Sección de búsqueda y botón de registro -->
                    <div class="row mb-4 align-items-center ">

                        <div class="col-md-6 d-flex justify-content-md-end justify-content-start estilo-info">
                            <form method="GET" action="{{ route('matriculas.index') }}" class="w-100"
                                style="max-width: 100%;">
                                <div class="input-group">
                                    <input id="buscarpor" type="text" class="form-control mt-3" name="buscarpor"
                                        autocomplete="off" placeholder="Buscar por número de matrícula"
                                        value="{{ $buscarpor }}" aria-label="Buscar por número de matrícula"
                                        style=" border-color: #f18e0e">
                                    <button class="btn btn-primary" type="submit"
                                        style="border:none; background-color: #f18e0e !important; border-top-left-radius: 0 !important; border-bottom-left-radius: 0 !important;">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <style>
                        .btn-primary {
                            margin-top: 1rem;
                            background: #F6820E !important;
                            border: none;
                            transition: background-color 0.2s ease, transform 0.1s ease;
                            margin-bottom: 0px;
                            font-family: "Quicksand", sans-serif;
                            font-weight: 700;
                            font-size: clamp(0.9rem, 2.0vw, 0.9rem) !important;

                        }

                        .btn-primary:hover {
                            background-color: #F59619 !important;
                            transform: scale(1.01);
                        }
                    </style>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            // Función para prevenir espacios
                            function evitarEspacios(input) {
                                input.addEventListener('keydown', function(e) {
                                    if (e.key === ' ') {
                                        e.preventDefault();
                                    }
                                });
                            }

                            // Lista de IDs a los que se les aplicará la validación
                            const sinEspacios = [
                                'buscarpor',
                            ];

                            // Aplicamos la función a todos los elementos por ID
                            sinEspacios.forEach(id => {
                                const input = document.getElementById(id);
                                if (input) {
                                    evitarEspacios(input);
                                }
                            });


                        });
                    </script>

                    <div class="row form-bordered align-items-center"></div>

                    <!-- Tabla de matrículas -->
                    @if ($matricula->count() > 0)
                        <div class="table-responsive mt-2">
                            <table class="table-hover table text-center"
                                style="border: 1px solid #0A8CB3; border-radius: 10px; overflow: hidden;">
                                <thead class="table-hover text-center estilo-info">
                                    <tr>
                                        <th>N.° Mat</th>
                                        <th>N.° DNI</th>
                                        <th>Nombre Completo</th>

                                        <th>Fecha</th>
                                        <th>Estado</th>
                                        <th>Opciones</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>

                        <div id="tabla-matriculas" style="position: relative;">
                            @include('cmatricula.matricula', [
                                'matricula' => $matricula,
                            ])
                        </div>
                    @else
                        <div class="alert alert-warning text-center">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>No se encontraron matrículas registradas.</strong>
                            @if ($buscarpor)
                                <br>No hay resultados para la búsqueda "{{ $buscarpor }}".
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mensajes de notificación -->
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show position-fixed"
        style="top: 20px; right: 20px; z-index: 1050;" role="alert">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show position-fixed"
        style="top: 20px; right: 20px; z-index: 1050;" role="alert">
        <i class="fas fa-exclamation-circle"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif


<style>
    @media (max-width: 576px) {
        .margen-movil {
            margin-left: -29px !important;
            margin-right: -29px !important;
        }

        .margen-movil-2 {
            margin: 0 !important;
            padding: 0 !important;
        }
    }

    .estilo-info {
        margin-bottom: 0px;
        font-family: "Quicksand", sans-serif;
        font-weight: 700;
        font-size: clamp(0.9rem, 2.0vw, 0.9rem) !important;

    }
</style>

<script>
    // Auto-hide success and error messages
    setTimeout(function() {
        $('.alert-success, .alert-danger').fadeOut();
    }, 5000);

    // Función para confirmar anulación
    function confirmarAnulacion(numeroMatricula) {
        return confirm('¿Estás seguro de que deseas ANULAR la matrícula N° ' + numeroMatricula +
            '?\n\nUna matrícula anulada no se puede reactivar.');
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        // Detecta el clic en cualquier link de paginación
        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault(); // Evita recargar toda la página

            let url = $(this).attr('href');

            // Si la página está en HTTPS, corregimos los enlaces HTTP -> HTTPS
            if (window.location.protocol === "https:") {
                url = url.replace("http://", "https://");
            }

            let buscarpor = $('#inputBuscar').val();

            const loader = document.getElementById('loaderTablaMatriculas');
            const contenedor = document.getElementById('tabla-matriculas');

            $.ajax({
                url: url,
                type: 'GET',
                data: {
                    buscarpor: buscarpor
                },

                beforeSend: function() {
                    // Muestra el loader y atenúa la tabla
                    loader.style.display = 'flex';
                    contenedor.style.opacity = '0.5';
                },

                success: function(data) {
                    // Actualiza el contenido de la tabla con los nuevos datos
                    $('#tabla-matriculas').html(data);
                },

                complete: function() {
                    // Oculta el loader y restaura opacidad
                    loader.style.display = 'none';
                    contenedor.style.opacity = '1';
                },

                error: function() {
                    alert('Ocurrió un error al cargar los datos.');
                }
            });
        });
    });
</script>
