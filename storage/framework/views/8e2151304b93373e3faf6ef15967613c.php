<?php $__env->startSection('titulo', 'Consulta de Calificaciones'); ?>
<?php $__env->startSection('contenidoplantilla'); ?>
    <style>
        .estilo-info {
            margin-bottom: 0px;
            font-family: "Quicksand", sans-serif;
            font-weight: 700;
            font-size: clamp(0.9rem, 2.0vw, 0.9rem) !important;

        }

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
    </style>
    <div class="container-fluid margen-movil-2">
        <div class="row mt-4 mr-1 ml-1">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-primary text-white" style="background-color: #1e5981 !important;">
                        <h4 class="mb-0">
                            <i class="fas fa-search"></i> Consulta de Calificaciones
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row mt-2">
                            <div class="col-md-11 mx-auto">
                                <div class="form-group mb-4">
                                    <label for="busqueda" class="form-label fw-bold text-primary">
                                        <i class="fas fa-user-graduate"></i> Buscar Estudiante:
                                    </label>
                                    <div class="input-group">
                                        <input type="text" id="busqueda" class="form-control form-control-lg"
                                            placeholder="Ingrese nombre, apellido o DNI*" autocomplete="off"
                                            style="border: 1px solid #DAA520; font-size:medium !important ">
                                        <div class="input-group-append">
                                            <button id="btnBuscar"
                                                class="btn btn-primary btn-lg"style="border: 1px solid #DAA520 !important; font-size:medium !important; font-weight: bold; background-color: #DAA520 !important">
                                                <i class="fas fa-search mx-2"></i> Buscar
                                            </button>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted"
                                        style="color:#f33813 !important; font-weight:bold">(*) Al menos 3 caracteres para
                                        iniciar la
                                        búsqueda.</small>
                                </div>

                                <div id="resultados" class="mt-4 mb-4" style="display: none;">
                                    <!-- Loader oculto -->

                                    <?php echo $__env->make('ccomponentes.loader', ['id' => 'loaderTemplate'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                                    <h5 class="border-bottom pb-2 mb-3">Resultados de la búsqueda:</h5>

                                    <div class="list-group" id="listaEstudiantes">
                                        <!-- Aquí se cargarán los resultados dinámicamente -->
                                    </div>

                                </div>

                                <div id="sinResultados" class="alert alert-info mt-4" style="display: none;">
                                    <i class="fas fa-info-circle"></i> No se encontraron estudiantes con los criterios de
                                    búsqueda.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .list-group-item {
            transition: all 0.2s ease;
        }

        .list-group-item:hover {
            background-color: #f8f9fa;
            transform: translateX(5px);
        }

        .estudiante-item {
            display: flex;
            align-items: center;
        }

        .estudiante-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #0A8CB3;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-right: 15px;
        }

        .estudiante-info {
            flex-grow: 1;
        }

        .estudiante-nombre {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 2px;
        }

        .estudiante-dni {
            color: #6c757d;
            font-size: 14px;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Script para mensajes globales con SweetAlert2 -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mensajes de éxito
            <?php if(session('success')): ?>
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: '<?php echo e(session('success')); ?>',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            <?php endif; ?>

            // Mensajes de error
            <?php if(session('error')): ?>
                Swal.fire({
                    icon: 'error',
                    title: '¡Error!',
                    text: '<?php echo e(session('error')); ?>',
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Entendido'
                });
            <?php endif; ?>

            // Mensajes de información
            <?php if(session('info')): ?>
                Swal.fire({
                    icon: 'info',
                    title: 'Información',
                    text: '<?php echo e(session('info')); ?>',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Aceptar'
                });
            <?php endif; ?>
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const busquedaInput = document.getElementById('busqueda');
            const btnBuscar = document.getElementById('btnBuscar');
            const resultadosDiv = document.getElementById('resultados');
            const sinResultadosDiv = document.getElementById('sinResultados');
            const listaEstudiantes = document.getElementById('listaEstudiantes');
            const loader = document.getElementById('loaderTemplate'); // apunta al loader de tabla
            // Función para realizar la búsqueda
            const buscarEstudiantes = () => {
                const query = busquedaInput.value.trim();

                if (query.length < 3) {
                    resultadosDiv.style.display = 'none';
                    sinResultadosDiv.style.display = 'none';
                    return;
                }

                // Mostrar indicador de carga
                // Obtener loader desde plantilla oculta
                const loaderHTML = document.getElementById('loaderTemplate').innerHTML;

                listaEstudiantes.innerHTML = loaderHTML;
                resultadosDiv.style.display = 'block';
                sinResultadosDiv.style.display = 'none';

                // Realizar petición AJAX PARA QUE NO RECARGUE EL NAVEGADO
                fetch(`<?php echo e(route('notas.buscarEstudiante')); ?>?query=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        listaEstudiantes.innerHTML = '';
                        loader.style.display = 'none';

                        if (data.error) {
                             console.error('Error del servidor:', data.error);
                             listaEstudiantes.innerHTML = '<div class="alert alert-danger">Error: ' + data.error + '</div>';
                             return;
                        }

                        if (data.length === 0) {
                            resultadosDiv.style.display = 'none';
                            sinResultadosDiv.style.display = 'block';
                            return;
                        }

                        data.forEach(estudiante => {
                            const iniciales = obtenerIniciales(estudiante.nombres, estudiante
                                .apellidos);
                            const item = document.createElement('div');
                            item.className = 'list-group-item list-group-item-action';
                            item.style.cursor = 'pointer';
                            item.innerHTML = `
                            <div class="estudiante-item">
                                <div class="estudiante-avatar d-flex justify-content-center align-items-center rounded-circle text-white me-3"
                                    style="width: 50px; height: 50px; background-color: #f9af44; font-weight: bold; font-size: 18px;">
                                    ${iniciales}
                                </div>
                                <div class="estudiante-info">
                                    <div class="estudiante-nombre">${estudiante.apellidos}, ${estudiante.nombres}</div>
                                    <div class="estudiante-dni">DNI: ${estudiante.dni}</div>
                                </div>
                                <div>
                                    <i class="fas fa-chevron-right text-primary mx-4"></i>
                                </div>
                            </div>
                        `;

                            // Agregar evento click para autorizar y ver notas
                            item.addEventListener('click', function() {
                                autorizarYVerEstudiante(estudiante.estudiante_id);
                            });

                            listaEstudiantes.appendChild(item);
                        });

                        resultadosDiv.style.display = 'block';
                    })
                    .catch(error => {
                        console.error('Error al buscar estudiantes:', error);
                        listaEstudiantes.innerHTML =
                            '<div class="alert alert-danger">Error al buscar estudiantes. Intente nuevamente.</div>';
                    });
            };

            // Función para autorizar y ver estudiante
            const autorizarYVerEstudiante = (estudianteId) => {
                // Crear formulario para enviar POST
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '<?php echo e(route('notas.autorizarEstudiante')); ?>';
                form.style.display = 'none';

                // Token CSRF
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '<?php echo e(csrf_token()); ?>';
                form.appendChild(csrfToken);

                // ID del estudiante
                const estudianteInput = document.createElement('input');
                estudianteInput.type = 'hidden';
                estudianteInput.name = 'estudiante_id';
                estudianteInput.value = estudianteId;
                form.appendChild(estudianteInput);

                // Agregar al DOM y enviar
                document.body.appendChild(form);
                form.submit();
            };

            // Función para obtener iniciales
            const obtenerIniciales = (nombres, apellidos) => {
                let iniciales = '';

                if (nombres && nombres.length > 0) {
                    iniciales += nombres.charAt(0);
                }

                if (apellidos && apellidos.length > 0) {
                    iniciales += apellidos.charAt(0);
                }

                return iniciales.toUpperCase();
            };

            // Evento para botón de búsqueda
            btnBuscar.addEventListener('click', buscarEstudiantes);

            // Evento para buscar al presionar Enter
            busquedaInput.addEventListener('keyup', function(event) {
                if (event.key === 'Enter') {
                    buscarEstudiantes();
                }
            });

            // Evento para buscar mientras se escribe (con delay)
            let typingTimer;
            busquedaInput.addEventListener('input', function() {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(buscarEstudiantes, 500);
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('cplantilla.bprincipal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/ronaldoroblesromero/Herd/edukaSchool/resources/views/cnotas/consulta.blade.php ENDPATH**/ ?>