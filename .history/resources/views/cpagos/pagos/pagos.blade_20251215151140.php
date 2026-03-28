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
                                            document.addEventListener("DOMContentLoaded", function() {
                                                const mp = new MercadoPago("{{ config('services.mercadopago.public_key') }}", {
                                                    locale: 'es-PE' // 🇵🇪 Perú
                                                });

                                                document.querySelectorAll('.btn-pagar').forEach(boton => {
                                                    boton.addEventListener('click', async function() {
                                                        const pagoId = this.dataset.id;
                                                        const monto = this.dataset.monto;
                                                        const email = this.dataset.email;

                                                        Swal.fire({
                                                            title: 'Selecciona una opción',
                                                            showDenyButton: true,
                                                            confirmButtonText: 'Pagar con Mercado Pago',
                                                            denyButtonText: 'Validar pago',
                                                            icon: 'question'
                                                        }).then(async (result) => {
                                                            if (result.isConfirmed) {
                                                                try {
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

                                                                    const data = await res.json();

                                                                    if (data.id) {
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
                                                                        Swal.fire("Error", data.message ||
                                                                            "No se pudo crear la preferencia",
                                                                            "error");
                                                                    }

                                                                } catch (error) {
                                                                    console.error(error);
                                                                    Swal.fire("Error",
                                                                        "No se pudo conectar con Mercado Pago",
                                                                        "error");
                                                                }

                                                            } else if (result.isDenied) {
                                                                // Cuando el usuario haga clic en "Validar"
                                                                fetch("{{ route('pagos.validar') }}", {
                                                                        method: "POST",
                                                                        headers: {
                                                                            "Content-Type": "application/json",
                                                                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                                                        },
                                                                        body: JSON.stringify({
                                                                            matricula_id: @json($pago->matricula_id)
                                                                        })
                                                                    })
                                                                    .then(res => res.json())
                                                                    .then(data => {
                                                                        if (data.success) {
                                                                            Swal.fire({
                                                                                title: "Validación exitosa",
                                                                                text: data.message,
                                                                                icon: "success",
                                                                                confirmButtonText: "Aceptar"
                                                                            }).then(() => location.reload());
                                                                        } else {
                                                                            Swal.fire("Error", data.message ||
                                                                                "Error en validación", "error");
                                                                        }
                                                                    })
                                                                    .catch(() => {
                                                                        Swal.fire("Error",
                                                                            "No se pudo validar la matrícula.",
                                                                            "warning");
                                                                    });

                                                            }
                                                        });
                                                    });
                                                });
                                            });
                                        </script>


                                        <!-- Ver detalle -->
                                        <li>
                                            <a href="{{ route('pagos.show', $pago->pago_id) }}"
                                                class="dropdown-item d-flex align-items-center">
                                                <i class="fas fa-eye mx-2" style="color: #004a92 !important"></i> Ver
                                                detalle
                                            </a>
                                        </li>

                                        <!-- Anular -->
                                        <li>
                                            <form method="POST" action="{{ route('pagos.destroy', $pago->pago_id) }}"
                                                style="display: inline-block;"
                                                onsubmit="return confirmarEliminacionPago('{{ $pago->codigo_transaccion }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item d-flex align-items-center">
                                                    <i class="fas fa-trash mx-2" style="color: rgb(160, 32, 32)"></i>
                                                    Anular
                                                </button>
                                            </form>
                                        </li>
                                    </ul>



                                </div>
                            @else
                                <a id="btn-nuevo" class="text-center dropdown-item fw-bold"
                                    href="{{ route('pagos.show', $pago->matricula_id) }}"
                                    style="border: 1px solid #114a6b; border-radius: 0.56rem; transition: background 0.3s ease, transform 0.2s ease; font-size:11px">
                                    <i id="icono" class="fas fa-eye mx-1 me-1 ms-1 text-primary"
                                        style="color: #114a6b !important"></i> Ver detalle
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Paginación -->
        <div class="d-flex justify-content-center mt-4">
            {{ $pagos->onEachSide(1)->links() }}
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
