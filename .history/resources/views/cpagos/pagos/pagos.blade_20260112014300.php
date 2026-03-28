<div id="tabla-pagos" class="table-responsive" style="overflow:visible">
    {{-- Loader único --}}
    @include('ccomponentes.loader', ['id' => 'loaderTablaPagos'])

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

                @foreach ($pagos as $index => $pago)
                    <tr>
                        <td class="text-center">{{ $pago->codigo_transaccion ?? '00000' }}</td>
                        <td class="text-center">{{ $pago->matricula->numero_matricula ?? 'N/A' }}</td>

                        <!--<td class="text-center">{{ number_format($pago->monto, 2) }}</td>-->
                        <td class="text-center">
                            {{ \Carbon\Carbon::parse($pago->fecha_vencimiento)->format('d/m/Y') }}
                        </td>
                        <td class="text-center">
                            @if ($pago->fecha_pago)
                                {{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}
                            @else
                                <span class="text-muted">Pendiente</span>
                            @endif
                        </td>

                        <!-- Script SDK Mercado Pago -->
                        <script src="https://sdk.mercadopago.com/js/v2"></script>

                        <!--  Botones de acción -->
                        <td class="text-center">
                            @if ($pago->estado !== 'Pagado')
                                <div class="dropdown">
                                    <button class="btn eduka-btn btn-sm dropdown-toggle px-3 w-100" type="button"
                                        id="accionesMenu{{ $pago->matricula_id }}" data-bs-toggle="dropdown"
                                        aria-expanded="false" style="border-radius: 0.56rem">
                                        <i class="fas fa-cogs mx-1"></i> Acciones
                                    </button>

                                    <ul class="dropdown-menu shadow border-0 rounded-3"
                                        aria-labelledby="accionesMenu{{ $pago->matricula_id }}">

                                        <!-- Botón Pagar -->
                                        <li >
                                            <a class="btn btn-pagar dropdown-item d-flex align-items-center"
                                                data-id="{{ $pago->pago_id }}" data-monto="{{ $pago->monto }}"
                                                 data-email="{{ $pago->matricula->estudiante->email ?? 'correo@ejemplo.com' }}"
                                                @if ($pago->estado == 'Pagado') hidden @endif>
                                                <i class="fas fa-credit-card mx-2" style="color: forestgreen"></i> Pagar
                                            </a>
                                        </li>



                                        <script>
                                            // Initialize MercadoPago SDK
                                            let mp;
                                            try {
                                                mp = new MercadoPago("{{ config('services.mercadopago.public_key') }}", {
                                                    locale: 'es-PE' // 🇵🇪 Perú
                                                });
                                                console.log('MercadoPago SDK initialized successfully');
                                            } catch (error) {
                                                console.error('Error initializing MercadoPago SDK:', error);
                                                mp = null;
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

                                                        if (!mp) {
                                                            Swal.fire("Error", "MercadoPago no está disponible", "error");
                                                            return;
                                                        }

                                                        try {
                                                            console.log('Creating payment preference...');
                                                            // 👉 Crear preferencia desde tu backend Laravel
                                                            const res = await fetch(
                                                                "{{ route('pagos.crearPreferencia') }}", {
                                                                    method: "POST",
                                                                    headers: {
                                                                        "Content-Type": "application/json",
                                                                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
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
                                                                mp.checkout({
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
                                                            const validateRes = await fetch("{{ route('pagos.validar') }}", {
                                                                method: "POST",
                                                                headers: {
                                                                    "Content-Type": "application/json",
                                                                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                                                },
                                                                body: JSON.stringify({
                                                                    matricula_id: @json($pago->matricula_id)
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

                                            // Attach event listeners when DOM is ready
                                            document.addEventListener("DOMContentLoaded", function() {
                                                console.log('DOM loaded, attaching payment button listeners');
                                                document.querySelectorAll('.btn-pagar').forEach(boton => {
                                                    console.log('Attaching listener to payment button');
                                                    boton.addEventListener('click', function(e) {
                                                        e.preventDefault();
                                                        handlePaymentClick(this);
                                                    });
                                                });
                                            });

                                            // Also try to attach immediately in case DOM is already loaded
                                            if (document.readyState === 'loading') {
                                                // DOM not yet loaded
                                            } else {
                                                // DOM already loaded
                                                console.log('DOM already loaded, attaching payment button listeners immediately');
                                                document.querySelectorAll('.btn-pagar').forEach(boton => {
                                                    console.log('Attaching listener to payment button (immediate)');
                                                    boton.addEventListener('click', function(e) {
                                                        e.preventDefault();
                                                        handlePaymentClick(this);
                                                    });
                                                });
                                            }
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
