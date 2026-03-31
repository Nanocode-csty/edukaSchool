<div id="tabla-representantes" class="table-responsive mt-2">
    <table id="add-row" class="table-hover table" style="border-radius: 8px; overflow: hidden;">
        <thead class="table-hover estilo-info" style="background-color: #f8f9fa; color: #0A8CB3;">
            <tr class="text-center">
                <th>Nombre de la Sección</th>
                <th>Capacidad</th>
                <th>Estado</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($secciones as $seccion)
                <tr class="{{ $seccion->estado === 'Inactivo' ? 'table-light' : '' }}">

                    <td>{{ $seccion->descripcion }}</td>
                    <td class="text-center">{{ $seccion->capacidad_maxima }}</td>
                    <td class="text-center">
                        <span class="badge px-3 py-1 rounded-pill fw-bold"
                            style="background-color: {{ $seccion->estado === 'Activo' ? '#b3f0ff' : '#ffd6d6' }};
                               color: {{ $seccion->estado === 'Activo' ? '#0b5e80' : '#841c26' }};
                               border:none">
                            {{ \Str::upper($seccion->estado) }}
                        </span>
                    </td>
                    <td class="text-center gap-4">
                        <!-- Botón para desplegar detalles -->
                        <button class="btn btn-outline-primary btn-sm toggle-detalle"
                            data-id="{{ $seccion->seccion_id }}">
                            <i class="ti ti-text-plus "></i>
                        </button>

                        <!-- Botón editar -->
                        <a href="{{ route('secciones.edit', $seccion->seccion_id) }}"
                            class="btn btn-sm btn-outline-primary">
                            <i class="ti ti-edit "></i>
                        </a>

                        <!-- Botón eliminar -->
                        <form action="{{ route('secciones.destroy', $seccion->seccion_id) }}" method="POST"
                            class="d-inline-block form-eliminar">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-primary btn-sm">
                                <i class="ti ti-trash-x-filled"></i>
                            </button>
                        </form>
                    </td>
                </tr>

                <!-- Fila desplegable en dos columnas -->
                <tr class="detalle-fila" id="detalle-{{ $seccion->seccion_id }}" style="display: none;">
                    <td colspan="5" class="bg-light align-top" style="text-align: left !important;">
                        <div class="p-3 text-start">
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <p class="mb-1">
                                        <i class="fas fa-id-badge me-2"></i>
                                        <strong>ID:</strong> {{ $seccion->seccion_id }}
                                    </p>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <p class="mb-1">
                                        <i class="fas fa-door-open me-2"></i>
                                        <strong>Nombre:</strong> {{ $seccion->nombre }}
                                    </p>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <p class="mb-1">
                                        <i class="fas fa-users me-2"></i>
                                        <strong>Capacidad Máxima:</strong> {{ $seccion->capacidad_maxima }}
                                    </p>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <p class="mb-1">
                                        <i class="fas fa-toggle-on me-2"></i>
                                        <strong>Estado:</strong>
                                        <span class="badge {{ $seccion->estado }}">
                                            {{ $seccion->estado }}
                                        </span>
                                    </p>
                                </div>
                                <div class="col-md-12 mb-1">
                                    <p class="mb-1">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>Descripción:</strong> {{ $seccion->descripcion }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">
                        <span>No se encontraron secciones registradas.</span>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Paginación -->
    <div id="tabla-secciones" class="d-flex justify-content-center mt-3">
        {{ $secciones->onEachSide(1)->links() }}
    </div>
</div>

<!-- Script para mostrar/ocultar detalles -->
<script>
    document.addEventListener('click', function(e) {
        const boton = e.target.closest('.toggle-detalle');
        if (!boton) return;

        const id = boton.getAttribute('data-id');
        const fila = document.getElementById('detalle-' + id);
        const icono = boton.querySelector('i');

        // Cerrar otras filas
        document.querySelectorAll('.detalle-fila').forEach(otraFila => {
            if (otraFila !== fila) {
                otraFila.style.display = 'none';
                const otroBoton = document.querySelector(
                    `.toggle-detalle[data-id="${otraFila.id.replace('detalle-', '')}"] i`);
                if (otroBoton) {
                    otroBoton.classList.remove('fa-chevron-up');
                    otroBoton.classList.add('fa-chevron-down');
                }
            }
        });

        // Alternar fila seleccionada
        const visible = fila.style.display !== 'none';
        fila.style.display = visible ? 'none' : '';
        icono.classList.toggle('fa-chevron-down', visible);
        icono.classList.toggle('fa-chevron-up', !visible);

        if (!visible) {
            setTimeout(() => {
                fila.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }, 200);
        }
    });
</script>
