<?php $__env->startSection('titulo', 'Registro de Grados'); ?>
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

    <?php echo $__env->make('ccomponentes.loader', ['id' => 'loaderPrincipalGrados'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?> 
    <div class="container-fluid margen-movil-2" id="contenido-principal" style="position: relative;">
        <div class="row mt-4 ml-1 mr-1">
            <div class="col-12">
                <div class="card">
                    <div class="estilo-info btn btn-block btn_header header_6">
                        <div class="row  d-flex justify-content-between align-items-center">
                            <div class="col-12 col-md-7"><i class="fas fa-file-signature m-1"></i>&nbsp;Registro y listado de
                                Grados</div>
                            <div class="col-12 col-md-5 mb-md-0 d-flex justify-content-start">
                                <a href="<?php echo e(route('grados.create')); ?>" id="nuevoRegistroBtns"
                                    class="btn w-100 btn-color-header d-flex justify-content-between align-items-center"
                                    type="button">
                                    <i class="ti ti-pencil-plus me-2 mx-1" style="font-size:24px"></i> REGISTRAR NUEVO GRADO
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body" style="background-color: #fff">
                        <div class="alert alert-interactive" id="alertMatricula">
                            <i class="fas fa-info-circle me-2"></i>
                            <div class="alert-content">
                                <strong>En esta sección podrás registrar grados y consultar sobre los ya
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

                            <!-- Dropdown de filtros -->
                            <div class="col-md-6 mb-md-0 d-flex justify-content-start">
                                <div class="dropdown w-100">

                                    <button id="dropdownFiltro"
                                        class="btn btn-color w-100 fw-bold d-flex justify-content-between align-items-center"
                                        type="button" data-bs-toggle="dropdown" aria-expanded="false">

                                        <span id="textoFiltro" class="d-flex justify-content-center align-items-center">
                                            <i class="ti ti-adjustments-horizontal mr-2" style="font-size:23px"></i> FILTRAR
                                            POR NIVEL EDUCATIVO
                                        </span>

                                        <i class="ti ti-chevron-down fw-fold" style="font-size: 24px;"></i>
                                    </button>

                                    <ul class="dropdown-menu shadow w-100">
                                        <li>
                                            <a class="dropdown-item filtro-opcion" data-id="">
                                                TODOS LOS NIVELES
                                            </a>
                                        </li>

                                        <?php $__currentLoopData = $niveles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nivel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li>
                                                <a class="dropdown-item filtro-opcion" data-id="<?php echo e($nivel->nivel_id); ?>">
                                                    <?php echo e($nivel->nombre); ?>

                                                </a>
                                            </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                    </ul>
                                </div>
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

                            <?php echo $__env->make('ccomponentes.loader', ['id' => 'loaderTablaGrados'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                            <div id="tabla-grados" style="position: relative;">
                                <?php echo $__env->make('ceinformacion.grados.tabla', ['grados' => $grados], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <br>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const inputBuscar = document.getElementById('inputBuscar');
            const formBuscar = document.getElementById('formBuscar');
            const opciones = document.querySelectorAll('.filtro-opcion');
            const textoFiltro = document.getElementById('textoFiltro');
            const loader = document.getElementById('loaderTablaGrados');
            const tabla = document.getElementById('tabla-grados');

            let nivelId = '';
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

                mostrarLoader();

                const url =
                    `<?php echo e(route('grados.index')); ?>?buscarpor=${encodeURIComponent(buscar)}&nivel_id=${nivelId}&page=${page}`;

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

                    ocultarLoader();

                }
            }
            // FILTRO DROPDOWN
            opciones.forEach(opcion => {

                opcion.addEventListener('click', function(e) {

                    e.preventDefault();

                    // Actualizar el nivelId con el valor del filtro seleccionado
                    nivelId = this.dataset.id;

                    textoFiltro.innerHTML =
                        `<i class="ti ti-adjustments-horizontal mr-2" style="font-size:23px"></i> ${this.textContent}`;

                    // Reiniciar a la página 1 y cargar la tabla con el nuevo filtro (IMPORTA MAS EL ID)
                    cargarTabla(inputBuscar.value.trim(), 1);

                });

            });

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

<?php echo $__env->make('cplantilla.bprincipal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Ronaldo Robles\Music\eduka_11012026_Final_V6\resources\views/ceinformacion/grados/registrar.blade.php ENDPATH**/ ?>