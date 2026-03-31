<div id="tabla-aulas" class="table-responsive mt-2">
    <table id="add-row" class="table-hover table" style="border-radius: 8px; overflow: hidden;">
        <thead class="table-hover estilo-info" style="background-color: #f8f9fa; color: #0A8CB3;">
            <tr class="text-center">
                <th>Nro.</th>
                <th>Ubicación</th>
                <th>Capacidad</th>
                <th>Tipo</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($aulas as $index => $aula)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $aula->ubicacion ?? 'No definida' }}</td>
                    <td class="text-center">{{ $aula->capacidad }}</td>
                    <td class="text-center">
                        <span class="badge fw-bold px-3 py-1 rounded-pill"
                            style="background-color:#f6fbde; border:none; color:#195004;">
                            {{ strtoupper($aula->tipo) }}
                        </span>
                    </td>
                    <td class="text-center">
                        <!-- Botón para desplegar detalles -->
                        <button class="btn btn-outline-primary btn-sm toggle-detalle" data-id="{{ $aula->aula_id }}">
                            <i class="ti ti-text-plus"></i>
                        </button>

                        <!-- Botón editar -->
                        <a href="{{ route('aulas.edit', $aula->aula_id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="ti ti-edit"></i>
                        </a>

                        <!-- Botón eliminar -->
                        <form action="{{ route('aulas.destroy', $aula->aula_id) }}" method="POST"
                            class="d-inline-block form-eliminar">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-primary btn-sm">
                                <i class="ti ti-trash-x-filled"></i>
                            </button>
                        </form>
                    </td>
                </tr>

                <!-- Fila desplegable -->
                <tr class="detalle-fila" id="detalle-{{ $aula->aula_id }}" style="display: none;">
                    <td colspan="5" class="bg-light text-start align-top" style="text-align: left !important;">
                        <div class="p-3 text-start">
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <p class="mb-1"><i class="fas fa-hashtag me-2"></i> <strong>ID:</strong>
                                        {{ $aula->aula_id }}</p>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <p class="mb-1"><i class="fas fa-door-open me-2"></i> <strong>Nombre:</strong>
                                        {{ $aula->nombre }}</p>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <p class="mb-1"><i class="fas fa-users me-2"></i> <strong>Capacidad:</strong>
                                        {{ $aula->capacidad }}</p>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <p class="mb-1"><i class="fas fa-map-marker-alt me-2"></i>
                                        <strong>Ubicación:</strong>
                                        {{ $aula->ubicacion ?? 'No definida' }}
                                    </p>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <p class="mb-1"><i class="fas fa-tag me-2"></i> <strong>Tipo:</strong>
                                        {{ strtoupper($aula->tipo) }}</p>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Paginación -->
    <div id="tabla-aulas" class="d-flex justify-content-center mt-3">
        {{ $aulas->onEachSide(1)->links() }}
    </div>

</div>
<!-- Script para detalles -->
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
