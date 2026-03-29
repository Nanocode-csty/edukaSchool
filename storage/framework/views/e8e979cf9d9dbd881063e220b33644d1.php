<div id="tabla-pagos" class="table-responsive" style="overflow:visible">
    
    <?php echo $__env->make('ccomponentes.loader', ['id' => 'loaderTablaPagos'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <table id="add-row" class="table table-hover align-middle table-hover text-center"
            style=" border-radius: 10px; ">
            <thead class="table-hover text-center estilo-info" style="background-color: #f9faf4; color:#6a410c;">
                <tr>
                    <th class="text-center">N.° Operación</th>
                    <th class="text-center">N.° Matrícula</th>
                    <th class="text-center">Fecha Vencimiento</th>
                    <th class="text-center">Fecha Pago</th>
                    <th class="text-center">Opciones</th>
                </tr>
            </thead>

            <tbody style="font-family: 'Quicksand', sans-serif !important;">

                <?php $__currentLoopData = $pagos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $pago): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="text-center"><?php echo e($pago->codigo_transaccion ?? '00000'); ?></td>
                        <td class="text-center"><?php echo e($pago->matricula->numero_matricula ?? 'N/A'); ?></td>

                        <!--<td class="text-center"><?php echo e(number_format($pago->monto, 2)); ?></td>-->
                        <td class="text-center">
                            <?php
                                $fechaVencimiento = \Carbon\Carbon::parse($pago->fecha_vencimiento);
                                $hoy = now();
                                $estaVencido = $hoy->greaterThan($fechaVencimiento);
                            ?>
                            <span class="<?php echo e($estaVencido && $pago->estado !== 'Pagado' ? 'text-danger font-weight-bold' : ''); ?>">
                                <?php echo e($fechaVencimiento->format('d/m/Y')); ?>

                                <?php if($estaVencido && $pago->estado !== 'Pagado'): ?>
                                    <i class="fas fa-exclamation-triangle text-danger" title="Pago vencido"></i>
                                <?php endif; ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <?php if($pago->fecha_pago): ?>
                                <?php echo e(\Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y')); ?>

                            <?php else: ?>
                                <span class="text-muted">Pendiente</span>
                            <?php endif; ?>
                        </td>

                        <!-- Script SDK Mercado Pago -->
                        <script src="https://sdk.mercadopago.com/js/v2"></script>

                        <!--  Botones de acción -->
                        <td class="text-center">
                            <?php if($pago->estado !== 'Pagado'): ?>
                                <div class="dropdown">
                                    <button class="btn eduka-btn btn-sm dropdown-toggle px-3 w-100" type="button"
                                        id="accionesMenu<?php echo e($pago->matricula_id); ?>" data-bs-toggle="dropdown"
                                        aria-expanded="false" style="border-radius: 0.56rem">
                                        <i class="fas fa-cogs mx-1"></i> Acciones
                                    </button>

                                    <ul class="dropdown-menu shadow border-0 rounded-3"
                                        aria-labelledby="accionesMenu<?php echo e($pago->matricula_id); ?>">

                                        <!-- Botón Pagar -->
                                        <li >
                                            <a class="btn btn-pagar dropdown-item d-flex align-items-center"
                                                data-id="<?php echo e($pago->pago_id); ?>" data-monto="<?php echo e($pago->monto); ?>"
                                                 data-email="<?php echo e($pago->matricula->estudiante->email ?? 'correo@ejemplo.com'); ?>"
                                                <?php if($pago->estado == 'Pagado'): ?> hidden <?php endif; ?>>
                                                <i class="fas fa-credit-card mx-2" style="color: forestgreen"></i> Pagar
                                            </a>
                                        </li>



                                        <script>
                                            // Initialize MercadoPago SDK only once
                                            if (typeof window.mercadoPagoInitialized === 'undefined') {
                                                window.mercadoPagoInitialized = true;
                                                try {
                                                    window.mp = new MercadoPago("<?php echo e(config('services.mercadopago.public_key')); ?>", {
                                                        locale: 'es-PE' // 🇵🇪 Perú
                                                    });
                                                    console.log('MercadoPago SDK initialized successfully');
                                                } catch (error) {
                                                    console.error('Error initializing MercadoPago SDK:', error);
                                                    window.mp = null;
                                                }
                                            }

                                            // Function to handle payment button clicks
                                            function handlePaymentClick(boton) {
                                                console.log('Payment button clicked');
                                                const pagoId = boton.dataset.id;
                                                const monto = boton.dataset.monto;
                                                const email = boton.dataset.email;

                                                console.log('Payment data:', { pagoId, monto, email });

                                                if (!pagoId || !monto) {
                                                    Swal.fire("Error", "Datos de pago incompletos", "error");
                                                    return;
                                                }

                                                Swal.fire({
                                                    title: 'Selecciona una opción',
                                                    showDenyButton: true,
                                                    confirmButtonText: 'Pagar con Mercado Pago',
                                                    denyButtonText: 'Validar pago manualmente',
                                                    icon: 'question'
                                                }).then(async (result) => {
                                                    if (result.isConfirmed) {
                                                        console.log('User selected MercadoPago payment');

                                                        if (!window.mp) {
                                                            Swal.fire("Error", "MercadoPago no está disponible", "error");
                                                            return;
                                                        }

                                                        try {
                                                            console.log('Creating payment preference...');
                                                            // 👉 Crear preferencia desde tu backend Laravel
                                                            const res = await fetch(
                                                                "<?php echo e(route('pagos.crearPreferencia')); ?>", {
                                                                    method: "POST",
                                                                    headers: {
                                                                        "Content-Type": "application/json",
                                                                        "X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>"
                                                                    },
                                                                    body: JSON.stringify({
                                                                        pago_id: pagoId,
                                                                        monto: monto,
                                                                        email: email
                                                                    })
                                                                });

                                                            console.log('Preference creation response status:', res.status);
                                                            const data = await res.json();
                                                            console.log('Preference data:', data);

                                                            if (data.id) {
                                                                console.log('Opening MercadoPago checkout with ID:', data.id);
                                                                // 👉 Abre el Checkout de Mercado Pago
                                                                window.mp.checkout({
                                                                    preference: {
                                                                        id: data.id
                                                                    },
                                                                    autoOpen: true, // abre automáticamente
                                                                    theme: {
                                                                        elementsColor: '#0A8CB3',
                                                                        headerColor: '#0A8CB3'
                                                                    }
                                                                });
                                                            } else {
                                                                console.error('Error creating preference:', data);
                                                                Swal.fire("Error", data.message ||
                                                                    "No se pudo crear la preferencia",
                                                                    "error");
                                                            }

                                                        } catch (error) {
                                                            console.error('Error in MercadoPago flow:', error);
                                                            Swal.fire("Error",
                                                                "No se pudo conectar con Mercado Pago: " + error.message,
                                                                "error");
                                                        }

                                                    } else if (result.isDenied) {
                                                        console.log('User selected manual validation');
                                                        // Cuando el usuario haga clic en "Validar"
                                                        try {
                                                            const validateRes = await fetch("<?php echo e(route('pagos.validar')); ?>", {
                                                                method: "POST",
                                                                headers: {
                                                                    "Content-Type": "application/json",
                                                                    "X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>"
                                                                },
                                                                body: JSON.stringify({
                                                                    matricula_id: <?php echo json_encode($pago->matricula_id, 15, 512) ?>,
                                                                    metodo_pago: "Validación Manual"
                                                                })
                                                            });

                                                            const validateData = await validateRes.json();
                                                            console.log('Validation response:', validateData);

                                                            if (validateData.success) {
                                                                Swal.fire({
                                                                    title: "Validación exitosa",
                                                                    text: validateData.message,
                                                                    icon: "success",
                                                                    confirmButtonText: "Aceptar"
                                                                }).then(() => location.reload());
                                                            } else {
                                                                Swal.fire("Error", validateData.message ||
                                                                    "Error en validación", "error");
                                                            }
                                                        } catch (error) {
                                                            console.error('Error in manual validation:', error);
                                                            Swal.fire("Error",
                                                                "No se pudo validar el pago: " + error.message,
                                                                "error");
                                                        }
                                                    }
                                                });
                                            }

                                            // Attach event listeners when DOM is ready (only once)
                                            if (typeof window.paymentListenersAttached === 'undefined') {
                                                window.paymentListenersAttached = true;

                                                document.addEventListener("DOMContentLoaded", function() {
                                                    console.log('DOM loaded, attaching payment button listeners');
                                                    attachPaymentListeners();
                                                });

                                                // Also try to attach immediately in case DOM is already loaded
                                                if (document.readyState !== 'loading') {
                                                    console.log('DOM already loaded, attaching payment button listeners immediately');
                                                    attachPaymentListeners();
                                                }
                                            }

                                            function attachPaymentListeners() {
                                                document.querySelectorAll('.btn-pagar').forEach(boton => {
                                                    if (!boton.hasPaymentListener) {
                                                        console.log('Attaching listener to payment button');
                                                        boton.addEventListener('click', function(e) {
                                                            e.preventDefault();
                                                            handlePaymentClick(this);
                                                        });
                                                        boton.hasPaymentListener = true;
                                                    }
                                                });
                                            }
                                        </script>


                                        <!-- Ver detalle -->
                                        <li>
                                            <a href="<?php echo e(route('pagos.show', $pago->pago_id)); ?>"
                                                class="dropdown-item d-flex align-items-center">
                                                <i class="fas fa-eye mx-2" style="color: #004a92 !important"></i> Ver
                                                detalle
                                            </a>
                                        </li>

                                        <!-- Anular -->
                                        <li>
                                            <form method="POST" action="<?php echo e(route('pagos.destroy', $pago->pago_id)); ?>"
                                                style="display: inline-block;"
                                                onsubmit="return confirmarEliminacionPago('<?php echo e($pago->codigo_transaccion); ?>')">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="dropdown-item d-flex align-items-center">
                                                    <i class="fas fa-trash mx-2" style="color: rgb(160, 32, 32)"></i>
                                                    Anular
                                                </button>
                                            </form>
                                        </li>
                                    </ul>



                                </div>
                            <?php else: ?>
                                <a id="btn-nuevo" class="text-center dropdown-item fw-bold"
                                    href="<?php echo e(route('pagos.show', $pago->pago_id)); ?>"
                                    style="border: 1px solid #114a6b; border-radius: 0.56rem; transition: background 0.3s ease, transform 0.2s ease; font-size:11px">
                                    <i id="icono" class="fas fa-eye mx-1 me-1 ms-1 text-primary"
                                        style="color: #114a6b !important"></i> Ver detalle
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>

        <!-- Paginación -->
        <div class="d-flex justify-content-center mt-4">
            <?php echo e($pagos->onEachSide(1)->links()); ?>

        </div>
</div>

<!-- 🎨 Estilos -->
<style>
    #add-row td,
    #add-row th {
        padding: 4px 8px;
        font-size: 15.5px !important;
        vertical-align: middle;
        height: 49px;
        font-family: 'Quicksand', sans-serif !important;
    }

    .eduka-btn {
        background: #114a6b;
        color: #fff;
        border-radius: 8px;
        border: none;
        font-size: 11px !important;
        font-weight: bold;
        transition: background 0.3s ease, transform 0.2s ease;
    }

    .eduka-btn:hover {
        background: #005f8a;
        transform: scale(1.035);
    }

    .dropdown-menu {
        z-index: 7000;
        font-family: 'Quicksand', sans-serif;
        animation: fadeIn 0.2s ease-in-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(5px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
<?php /**PATH /Users/ronaldoroblesromero/Herd/edukaSchool/resources/views/cpagos/pagos/pagos.blade.php ENDPATH**/ ?>