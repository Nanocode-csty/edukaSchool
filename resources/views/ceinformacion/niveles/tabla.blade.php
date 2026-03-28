<div id="tabla-niveles" class="table-responsive mt-2">
    <table id="add-row" class="table-hover table" style="border-radius: 8px; overflow: hidden;">
        <thead class="table-hover estilo-info" style="background-color: #f8f9fa; color: #0A8CB3;">
            <tr class="text-center">
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($niveles as $nivel)
                <tr>
                    <td>{{ $nivel->nombre }}</td>
                    <td>{{ $nivel->descripcion }}</td>

                    <td class="text-center">

                        <a href="{{ route('registrarnivel.edit', $nivel->nivel_id) }}"
                            class="btn btn-outline-primary btn-sm  btn-editar-nivel" title="Editar">
                            <i class="ti ti-edit" style=" font-size: 1.2rem;"></i>
                        </a>
                        <a href="{{ route('registrarnivel.confirmar', $nivel->nivel_id) }}"
                            class="btn btn-outline-primary btn-sm  btn-eliminar-nivel " title="Eliminar">
                            <i class="ti ti-trash-x" style=" font-size: 1.3rem;"></i>
                        </a>

                    </td>
                </tr>

            @empty
                <tr>
                    <td colspan="3" class="text-center text-muted">
                        No se encontraron niveles registrados.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Paginación -->
    <div class="tabla-niveles d-flex justify-content-center">
        {{ $niveles->links() }}
    </div>
</div>
