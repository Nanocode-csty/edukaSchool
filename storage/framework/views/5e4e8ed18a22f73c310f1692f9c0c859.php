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
        color: #F59D24 !important;
    }

    .card-body.info p {
        margin-bottom: 0px;
        font-family: "Quicksand", sans-serif;
        font-weight: 600;
        color: #004a92;
    }
</style>


<style type="text/css" data-glamor=""></style>
<meta name="react-film" content="version=1.2.1-master.db29968">
<meta name="botframework-webchat:bundle:variant" content="full">
<meta name="botframework-webchat:bundle:version" content="4.3.1-master.98c662f">
<meta name="botframework-webchat:core:version" content="4.3.1-master.98c662f">
<meta name="botframework-webchat:ui:version" content="4.3.1-master.98c662f">
<style type="text/css">
    .fancybox-margin {
        margin-right: 10px;
    }
</style>

<div class="margen-movil-2">
    <div class="card" style="border: none">
        <div class="card-header-custom">
            Datos del Primer Representante <span style="color: #FF5A6A">(*)</span>
        </div>
        <div>

            <div class="card-body collapse show" id="collapseExample4"
                style="border: 2px solid #86D2E3; border-top: none; border-radius: 0px 0px 6px 6px !important;">

                <div class="row mb-3 align-items-center">
                    <label class="col-12 col-md-2 col-form-label">N.° de DNI</label>
                    <div class="col-12 col-md-10">
                        <form id="formBuscar" autocomplete="off">
                            <div class="input-group">
                                <input maxlength="8" id="inputBuscar" name="inputBuscar"
                                    class="form-control disabled-format <?php $__errorArgs = ['inputBuscar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    type="search" style="font-weight: bold; border-color: #9d886a !important;"
                                    placeholder="Ingresar N.º de DNI" inputmode="numeric" value="<?php echo e(old('inputBuscar')); ?>">

                                <button id="btnBuscar" class="btn nuevo-boton" type="button">
                                    <i class="fas fa-search"></i> Validar
                                </button>
                                <?php if($errors->has('inputBuscar')): ?>
                                    <span
                                        class="invalid-feedback d-block text-start"><?php echo e($errors->first('inputBuscar')); ?></span>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>

                <input type="hidden" id="idEstudiante" name="idEstudiante" value="<?php echo e(session('idEstudiante')); ?>">

                <div class="row mb-3 align-items-center">
                    <label class="col-12 col-md-2 col-form-label">Nombres y Apellidos</label>
                    <div class="col-12 col-md-10">
                        <input type="text"
                            class="form-control disabled-format <?php $__errorArgs = ['apellidoPaternoRepresentante1'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            id="apellidoPaternoRepresentante1" name="apellidoPaternoRepresentante1"
                            placeholder="Nombres y Apellidos" maxlength="100"
                            value="<?php echo e(old('apellidoPaternoRepresentante1')); ?>" readonly>
                        <?php if($errors->has('apellidoPaternoRepresentante1')): ?>
                            <span
                                class="invalid-feedback d-block text-start"><?php echo e($errors->first('apellidoPaternoRepresentante1')); ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="row mb-3 align-items-center">
                    <label class="col-12 col-md-2 col-form-label">Parentesco</label>
                    <div class="col-12 col-md-4">
                        <input type="text" class="form-control disabled-format" id="parentescoRepresentante1"
                            name="parentescoRepresentante1" placeholder="Parentesco"
                            value="<?php echo e(old('parentescoRepresentante1')); ?>" maxlength="100" readonly>
                        <?php $__errorArgs = ['parentescoRepresentante1'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback d-block text-start">
                                <?php echo e($message); ?>

                            </div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <label class="col-12 col-md-2 col-form-label">Teléfono/Celular</label>
                    <div class="col-12 col-md-4">
                        <input type="text" class="form-control disabled-format" id="celularRepresentante1"
                            name="celularRepresentante1" placeholder="Telefono/Celular"
                            value="<?php echo e(old('celularRepresentante1')); ?>" maxlength="100" readonly>
                        <?php $__errorArgs = ['celularRepresentante1'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback d-block text-start">
                                <?php echo e($message); ?>

                            </div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="row mb-3 align-items-center">
                    <label class="col-12 col-md-2 col-form-label">
                        Correo Electrónico
                    </label>
                    <div class="col-12 col-md-10">
                        <input type="email" class="form-control disabled-format" id="correoRepresentante1"
                            name="correoRepresentante1" placeholder="Correo electrónico" maxlength="100"
                            value="<?php echo e(old('correoRepresentante1')); ?>" readonly>
                        <?php $__errorArgs = ['correoRepresentante1'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback d-block text-start">
                                <?php echo e($message); ?>

                            </div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                        <small class="form-text text-muted" style="color:#FF5A6A !important; font-weight: bold;">
                            El sistema enviará a este correo las credenciales oficiales para el acceso a la intranet
                            educativa.
                        </small>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<div>
    <div class="card" style="border: none">
        <div class="card-header-custom">
            Datos del Segundo Representante <span style="color: #FF5A6A">(*)</span>
        </div>
        <div>

            <div class="card-body collapse show" id="collapseExample2"
                style="border: 2px solid #86D2E3; border-top: none; border-radius: 0px 0px 6px 6px !important;">

                <div class="row mb-3 align-items-center ">
                    <!-- Formulario de búsqueda a la derecha -->
                    <label class="col-12 col-md-2 col-form-label">
                        N.° de DNI
                    </label>
                    <div class="col-12 col-md-10 d-flex justify-content-md-end justify-content-center">
                        <form id="formBuscar2" class=" w-100" style="max-width: 100%;" autocomplete="off">
                            <div class="input-group">
                                <input maxlength="8" id="inputBuscar2" name="buscarpor"
                                    class="form-control disabled-format" type="search"
                                    placeholder="Ingresar N.º de DNI" aria-label="Search"
                                    style="font-weight: bold; border-color: #9d886a !important;" inputmode="numeric">
                                <button id="btnBuscar2" class="btn nuevo-boton" type="button">
                                    <i class="fas fa-search"></i> Validar
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
                <style>
                    .nuevo-boton {
                        border-top-left-radius: 0 !important;
                        border-bottom-left-radius: 0 !important;
                        background-color: #937f63;
                        color: white;
                        font-weight: bold !important;
                    }

                    .nuevo-boton:hover {
                        background-color: #5e4825 !important;
                        color: white !important;
                        font-weight: bold !important;
                    }
                </style>

                <div class="row mb-3 align-items-center">
                    <label class="col-12 col-md-2 col-form-label">Nombres y Apellidos</label>
                    <div class="col-12 col-md-10">
                        <input type="email"
                            class="form-control disabled-format <?php $__errorArgs = ['apellidoPaternoRepresentante2'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            id="apellidoPaternoRepresentante2" name="apellidoPaternoRepresentante2"
                            placeholder="Nombres y Apellidos" maxlength="100"
                            value="<?php echo e(old('apellidoPaternoRepresentante2')); ?>" readonly>
                        <?php if($errors->has('apellidoPaternoRepresentante2')): ?>
                            <span
                                class="invalid-feedback d-block text-start"><?php echo e($errors->first('apellidoPaternoRepresentante2')); ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="row mb-3 align-items-center">
                    <label class="col-12 col-md-2 col-form-label">Parentesco</label>
                    <div class="col-12 col-md-4">
                        <input type="text" class="form-control disabled-format" id="parentescoRepresentante2"
                            name="parentescoRepresentante2" placeholder="Parentesco"
                            value="<?php echo e(old('parentescoRepresentante2')); ?>" maxlength="100" readonly>
                        <?php $__errorArgs = ['parentescoRepresentante2'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback d-block text-start">
                                <?php echo e($message); ?>

                            </div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <label class="col-12 col-md-2 col-form-label">Teléfono/Celular</label>
                    <div class="col-12 col-md-4">
                        <input type="text" class="form-control disabled-format" id="celularRepresentante2"
                            name="celularRepresentante2" placeholder="Telefono/Celular"
                            value="<?php echo e(old('celularRepresentante2')); ?>" maxlength="100" readonly>
                        <?php $__errorArgs = ['celularRepresentante2'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback d-block text-start">
                                <?php echo e($message); ?>

                            </div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                </div>

                <div class="row mb-3 align-items-center">
                    <label class="col-12 col-md-2 col-form-label">
                        Correo Electrónico
                    </label>
                    <div class="col-12 col-md-10">
                        <input type="text" class="form-control disabled-format" id="correoRepresentante2"
                            name="correoRepresentante2" placeholder="Correo electrónico" maxlength="100"
                            value="<?php echo e(old('correoRepresentante2')); ?>" readonly>
                        <?php $__errorArgs = ['correoRepresentante2'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback d-block text-start">
                                <?php echo e($message); ?>

                            </div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                </div>

                <?php if($errors->has('error_general')): ?>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Estimado Usuario',
                                text: <?php echo json_encode($errors->first('error_general'), 15, 512) ?>,
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Entendido'
                            });
                        });
                    </script>
                <?php endif; ?>

                <div class="col-md-10" hidden>
                    <input type="text" class="form-control disabled-format" id="idRepresentante1"
                        name="idRepresentante1" placeholder="Correo electrónico" maxlength="100"
                        value="<?php echo e(old('idRepresentante1')); ?>" readonly>
                    <?php $__errorArgs = ['idRepresentante1'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback d-block text-start">
                            <?php echo e($message); ?>

                        </div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-10" hidden>
                    <input type="text" class="form-control disabled-format" id="idRepresentante2"
                        name="idRepresentante2" placeholder="Correo electrónico" maxlength="100"
                        value="<?php echo e(old('idRepresentante2')); ?>" readonly>
                    <?php $__errorArgs = ['idRepresentante2'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback d-block text-start">
                            <?php echo e($message); ?>

                        </div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('btnBuscar').addEventListener('click', function(e) {
        e.preventDefault();

        const dni = document.getElementById('inputBuscar').value.trim();

        if (dni.length === 8 && /^\d+$/.test(dni)) {
            fetch("<?php echo e(route('buscar.representante')); ?>", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                    },
                    body: JSON.stringify({
                        dni: dni
                    })
                })
                .then(response => response.json())
                .then(data => {
                    const inputBuscar = document.getElementById('inputBuscar');
                    const btnBuscar = document.getElementById('btnBuscar');

                    const formPadre = document.getElementById('formPadreVer');

                    if (data.success) {
                        if (data.representante.dni == document.getElementById('inputBuscar2').value || data
                            .representante.parentesco == document.getElementById('parentescoRepresentante2')
                            .value) {
                            {
                                e.preventDefault();
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Representante ya asignado o duplicado',
                                    text: 'El representante ya ha sido asignado o el tipo de parentesco ya existe. Por favor, ingrese otro representante.',
                                    showConfirmButton: false,
                                    timer: 3200
                                });

                                inputBuscar.value = "";

                                $('#inputBuscar').focus();
                                return;
                            }
                        }
                        e.preventDefault();
                        Swal.fire({
                            icon: 'success',
                            title: 'Representante registrado',
                            text: 'El Representante se encuentra registrado correctamente.',
                            showConfirmButton: false,
                            timer: 1200
                        });

                        //formPadre.hidden = false;

                        // Habilitar/deshabilitar botones y campos
                        inputBuscar.readOnly = true;
                        btnBuscar.disabled = true;

                        // Asignar valores a inputs
                        document.getElementById('idRepresentante1').value = data.representante
                            .representante_id || "";
                        document.getElementById('apellidoPaternoRepresentante1').value =
                            data.representante.persona.nombre_completo;
                        document.getElementById('parentescoRepresentante1').value = data.representante
                            .parentesco || "";
                        document.getElementById('celularRepresentante1').value = data.representante.persona
                            .telefono_formato || "";
                        document.getElementById('correoRepresentante1').value = data.representante.persona
                            .email ||
                            "";

                    } else {

                        e.preventDefault();
                        Swal.fire({
                            icon: 'warning',
                            title: 'Representante NO registrado',
                            text: 'Debe registra al representante en "Registrar al padre/madre de familia" para continuar',
                            showConfirmButton: false,
                            timer: 3200
                        });

                        inputBuscar.value = "";

                        $('#inputBuscar').focus();
                    }
                })
                .catch(error => {
                    console.error(error);
                    alert('Ocurrió un error al buscar el representante.');
                });
        } else {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Caracteres Inválidos',
                text: 'Ingrese un N.° de DNI válido para realizar la búsqueda.',
                showConfirmButton: false,
                timer: 2200
            });
            document.getElementById('inputBuscar').value = "";
        }

    });
</script>

<script>
    document.getElementById('btnBuscar2').addEventListener('click', function(e) {
        e.preventDefault();

        const dni = document.getElementById('inputBuscar2').value.trim();

        if (dni.length === 8 && /^\d+$/.test(dni)) {
            fetch("<?php echo e(route('buscar.representante')); ?>", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                    },
                    body: JSON.stringify({
                        dni: dni
                    })
                })
                .then(response => response.json())
                .then(data2 => {
                    const inputBuscar2 = document.getElementById('inputBuscar2');
                    const btnBuscar2 = document.getElementById('btnBuscar2');

                    if (data2.success) {
                        if (data2.representante.dni == document.getElementById('inputBuscar').value || data2
                            .representante.parentesco == document.getElementById('parentescoRepresentante1')
                            .value) {
                            {
                                e.preventDefault();
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Representante ya asignado o duplicado',
                                    text: 'El representante ya ha sido asignado o el tipo de parentesco ya existe. Por favor, ingrese otro representante.',
                                    showConfirmButton: false,
                                    timer: 3200
                                });

                                inputBuscar2.value = "";

                                $('#inputBuscar2').focus();
                                return;
                            }
                        }

                        e.preventDefault();
                        Swal.fire({
                            icon: 'success',
                            title: 'Representante registrado',
                            text: 'El Representante se encuentra registrado correctamente.',
                            showConfirmButton: false,
                            timer: 1200
                        });

                        //formPadre.hidden = false;

                        // Habilitar/deshabilitar botones y campos
                        inputBuscar2.readOnly = true;
                        btnBuscar2.disabled = true;

                        // Asignar valores a inputs
                        // Asignar valores a inputs
                        document.getElementById('idRepresentante2').value = data2.representante
                            .representante_id || "";
                        document.getElementById('apellidoPaternoRepresentante2').value =
                            data2.representante.persona.nombre_completo;
                        document.getElementById('parentescoRepresentante2').value = data2.representante
                            .parentesco || "";
                        document.getElementById('celularRepresentante2').value = data2.representante.persona
                            .telefono_formato || "";
                        document.getElementById('correoRepresentante2').value = data2.representante.persona
                            .email ||
                            "";

                    } else {

                        e.preventDefault();
                        Swal.fire({
                            icon: 'warning',
                            title: 'Representante NO registrado',
                            text: 'Debe registra al representante en "Registrar al padre/madre de familia" para continuar',
                            showConfirmButton: false,
                            timer: 2200
                        });

                        inputBuscar2.value = "";

                        $('#inputBuscar2').focus();
                    }
                })
                .catch(error => {
                    console.error(error);
                    alert('Ocurrió un error al buscar el representante.');
                });
        } else {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Caracteres Inválidos',
                text: 'Ingrese un N.° de DNI válido para realizar la búsqueda.',
                showConfirmButton: false,
                timer: 1200
            });
            document.getElementById('inputBuscar2').value = "";
        }

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
                timer: 2200,
                showConfirmButton: false
            });
        });
    </script>
<?php endif; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const formularios = [{
                formId: 'formBuscar',
                inputId: 'inputBuscar'
            },
            {
                formId: 'formBuscar2',
                inputId: 'inputBuscar2'
            }
        ];

        formularios.forEach(({
            formId,
            inputId
        }) => {
            const form = document.getElementById(formId);
            const input = document.getElementById(inputId);

            if (form && input) {
                form.addEventListener('submit', function(e) {
                    if (input.value.trim() === '') {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'warning',
                            title: 'Campo vacío',
                            text: 'Ingrese el N.° de DNI para realizar la búsqueda.',
                            showConfirmButton: false,
                            timer: 1000
                        });
                    }
                });
            }
        });
    });
</script>
<?php /**PATH /Users/ronaldoroblesromero/Herd/edukaSchool/resources/views/ceinformacion/estudiantes/nuevo/datosRepresentante.blade.php ENDPATH**/ ?>