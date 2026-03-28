<?php $__env->startSection('titulo', 'Registro de Secciones'); ?>
<?php $__env->startSection('contenidoplantilla'); ?>

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
    <?php echo $__env->make('ccomponentes.loader', ['id' => 'loaderPrincipalSecciones'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?> 

    <div class="container-fluid margen-movil-2" id="contenido-principal" style="position: relative;">
        <div class="row mt-4 ml-1 mr-1">
            <div class="col-12">
                <div class="card">
                    <div class="estilo-info btn btn-block btn_header header_6">
                        <div class="row  d-flex justify-content-between align-items-center">
                            <div class="col-12 col-md-7"><i class="fas fa-file-signature m-1"></i>&nbsp;Registro y listado de
                                Secciones</div>
                            <div class="col-12 col-md-5 mb-md-0 d-flex justify-content-start">
                                <a href="<?php echo e(route('secciones.create')); ?>" id="nuevoRegistroBtns"
                                    class="btn w-100 btn-color-header d-flex justify-content-between align-items-center"
                                    type="button">
                                    <i class="ti ti-pencil-plus me-2 mx-1" style="font-size:24px"></i> REGISTRAR NUEVA
                                    SECCIÓN
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body" style="background-color: #fff">
                        <div class="alert alert-interactive" id="alertMatricula">
                            <i class="fas fa-info-circle me-2"></i>
                            <div class="alert-content">
                                <strong>En esta sección podrás registrar secciones y consultar sobre las ya
                                    registradas.</strong>
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
                                <a href="<?php echo e(route('docente.create')); ?>" id="nuevoRegistroBtns"
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
                                            placeholder="Ingrese Nombre de la Sección" aria-label="Search"
                                            value="<?php echo e($buscarpor); ?>" autocomplete="off"
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

                            <?php echo $__env->make('ccomponentes.loader', ['id' => 'loaderTablaSecciones'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                            <div id="tabla-secciones" style="position: relative;">
                                <?php echo $__env->make('ceinformacion.secciones.tabla', ['secciones' => $secciones], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
        </div>
    </div>


    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const buscarInput = document.getElementById('buscarpor');
            const form = document.getElementById('formBuscarSeccion');


            buscarInput.addEventListener('input', function() {
                if (this.value.trim() === '') {
                    fetchSecciones();
                }
            });

            document.addEventListener('click', function(e) {

                // Confirmación eliminación
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
    <?php if(session('success')): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: '<?php echo e(session('success')); ?>',
                    confirmButtonColor: '#28a745',
                    timer: 3200,
                    showConfirmButton: false
                });
            });
        </script>
    <?php endif; ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            const inputBuscar = document.getElementById('inputBuscar');
            const formBuscar = document.getElementById('formBuscar');
            const tabla = document.getElementById('tabla-secciones');
            const loader = document.getElementById('loaderTablaSecciones');

            let debounceTimer = null;

            function mostrarLoader() {
                loader.style.display = 'flex';
                tabla.style.opacity = '0.5'; // feedback visual extra
            }

            function ocultarLoader() {
                loader.style.display = 'none';
                tabla.style.opacity = '1';
            }

            async function cargarTabla(buscar = '', page = 1) {
                // 🔥 SIEMPRE se ejecuta
                mostrarLoader();

                const url =
                    `<?php echo e(route('secciones.index')); ?>?buscarpor=${encodeURIComponent(buscar)}&page=${page}`;

                try {
                    const response = await fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (!response.ok) throw new Error('Error en la respuesta');

                    const html = await response.text();
                    tabla.innerHTML = html;

                } catch (error) {
                    console.error(error);
                    alert('Error al cargar los datos');
                } finally {
                    // 🔥 SOLO se oculta cuando TODO terminó
                    ocultarLoader();
                }
            }

            // 🔎 Buscar (debounce)
            inputBuscar.addEventListener('input', () => {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    cargarTabla(inputBuscar.value.trim(), 1);
                }, 400);
            });

            // 📝 Submit
            formBuscar.addEventListener('submit', (e) => {
                e.preventDefault();
                cargarTabla(inputBuscar.value.trim(), 1);
            });

            // 📄 Paginación
            document.addEventListener('click', (e) => {
                const link = e.target.closest('.pagination a');
                if (!link) return;

                e.preventDefault();

                const url = new URL(link.href);
                const page = url.searchParams.get('page');

                cargarTabla(inputBuscar.value.trim(), page);
            });

        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('cplantilla.bprincipal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/ronaldoroblesromero/Herd/edukaSchool/resources/views/ceinformacion/secciones/registrar.blade.php ENDPATH**/ ?>