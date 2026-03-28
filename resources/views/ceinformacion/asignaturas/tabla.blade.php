@if ($asignaturas->count())
<table class="table table-hover align-middle table-bordered text-center">
    <thead style="background-color: #fdebd0; color: #a04000;">
        <tr>
            <th>Nombre</th>
            <th>Horas Semanales</th>
            <th>Opciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($asignaturas as $asignatura)
            <tr>
                <td>{{ $asignatura->nombre }}</td>
                <td>{{ $asignatura->horas_semanales }}</td>
                <td>
                    <!-- Botón desplegable -->
                    <button class="btn btn-sm toggle-detalle"
                            style="border: 1px solid #f5b041; color: #f39c12; background-color: transparent;"
                            title="Ver detalle"
                            data-id="{{ $asignatura->asignatura_id }}">
                        <i class="fas fa-chevron-down"></i>
                    </button>

                    <!-- Botón editar -->
                    <a href="{{ route('asignaturas.edit', $asignatura->asignatura_id) }}"
                       class="btn btn-sm"
                       style="background-color: #f8c471; color: white;">
                        <i class="fas fa-edit"></i>
                    </a>

                    <!-- Botón eliminar -->
                    <form action="{{ route('asignaturas.destroy', $asignatura->asignatura_id) }}"
                          method="POST"
                          class="d-inline-block form-eliminar">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm"
                                style="border: 1px solid #e74c3c; color: #e74c3c; background-color: transparent;">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                </td>
            </tr>

            <!-- Fila desplegable -->
            <tr class="detalle-fila" id="detalle-{{ $asignatura->asignatura_id }}" style="display: none;">
                <td colspan="6" class="bg-light text-start align-top" style="text-align: left !important;">
                    <div class="p-3">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <p class="mb-1"><i class="fas fa-id-badge me-2 text-warning"></i> <strong>ID:</strong> {{ $asignatura->asignatura_id }}</p>
                                <p class="mb-1"><i class="fas fa-barcode me-2 text-warning"></i> <strong>Código:</strong> {{ $asignatura->codigo }}</p>
                                <p class="mb-1"><i class="fas fa-book me-2 text-warning"></i> <strong>Nombre:</strong> {{ $asignatura->nombre }}</p>
                            </div>
                            <div class="col-md-6 mb-2">
                                <p class="mb-1"><i class="fas fa-align-left me-2 text-warning"></i> <strong>Descripción:</strong> {{ $asignatura->descripcion ?? 'No definida' }}</p>
                                <p class="mb-1"><i class="fas fa-clock me-2 text-warning"></i> <strong>Horas semanales:</strong> {{ $asignatura->horas_semanales }}</p>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<!-- Paginación -->
<div class="d-flex justify-content-center">
    {{ $asignaturas->links() }}
</div>
@else
    <div class="alert alert-info text-center">
        No se encontraron registros de asignaturas.
    </div>
@endif

<!-- Script para mostrar/ocultar detalles -->
<script>
    document.addEventListener('click', function (e) {
        const boton = e.target.closest('.toggle-detalle');
        if (!boton) return;

        const id = boton.getAttribute('data-id');
        const fila = document.getElementById('detalle-' + id);
        const icono = boton.querySelector('i');

        // Cerrar otras filas
        document.querySelectorAll('.detalle-fila').forEach(otraFila => {
            if (otraFila !== fila) {
                otraFila.style.display = 'none';
                const otroBoton = document.querySelector(`.toggle-detalle[data-id="${otraFila.id.replace('detalle-', '')}"] i`);
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
                fila.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }, 200);
        }
    });
</script>
<style>
    .pagination .page-item.active .page-link {
        background-color: #be651c;
        border-color: #d69a41;
    }
    .pagination .page-link {
        color: #c77c19;
    }
</style>