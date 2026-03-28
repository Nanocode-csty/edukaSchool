@if ($periodosEvaluacion->count())
<table class="table table-hover align-middle table-bordered text-center">
    <thead style="background-color: #1e293b; color: white;">
        <tr>
            <th>Nombre</th>
            <th>Fecha Inicio</th>
            <th>Fecha Fin</th>
            <th>Estado</th>
            <th>Es Final</th>
            <th>Opciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($periodosEvaluacion as $periodo)
            <tr>
                <td>{{ $periodo->nombre }}</td>
                <td>{{ \Carbon\Carbon::parse($periodo->fecha_inicio)->format('d/m/Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($periodo->fecha_fin)->format('d/m/Y') }}</td>
                <td>
                    <span class="badge text-white fw-semibold px-3 py-1 rounded-pill" style="background-color: #334155;">
                        {{ $periodo->estado }}
                    </span>
                </td>
                <td>
                    @if ($periodo->es_final)
                        <span class="badge bg-success text-white fw-semibold px-3 py-1 rounded-pill">Sí</span>
                    @else
                        <span class="badge text-white fw-semibold px-3 py-1 rounded-pill" style="background-color: #1d48ac;">
                            No
                        </span>
                    @endif
                </td>
                <td class="text-center">
                    <button class="btn btn-outline-primary btn-sm toggle-detalle" data-id="{{ $periodo->periodo_id }}">
                        <i class="fas fa-chevron-down"></i>
                    </button>

                    <a href="{{ route('periodos-evaluacion.edit', $periodo->periodo_id) }}" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit"></i>
                    </a>

                    <form action="{{ route('periodos-evaluacion.destroy', $periodo->periodo_id) }}" method="POST" class="d-inline-block form-eliminar">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                </td>
            </tr>

            <!-- Fila desplegable oculta -->
            <tr class="detalle-fila" id="detalle-{{ $periodo->periodo_id }}" style="display: none;">
                <td colspan="6" class="bg-light text-start align-top" style="text-align: left !important;">
                    <div class="p-3 text-start">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <p class="mb-1"><i class="fas fa-id-badge me-2"></i> <strong>ID:</strong> {{ $periodo->periodo_id }}</p>
                            </div>
                            <div class="col-md-6 mb-2">
                                <p class="mb-1"><i class="fas fa-hashtag me-2"></i> <strong>Año Lectivo:</strong> {{ $periodo->ano_lectivo_id }}</p>
                            </div>
                            <div class="col-md-6 mb-2">
                                <p class="mb-1"><i class="fas fa-calendar me-2"></i> <strong>Nombre:</strong> {{ $periodo->nombre }}</p>
                            </div>
                            <div class="col-md-6 mb-2">
                                <p class="mb-1"><i class="fas fa-clock me-2"></i> <strong>Fecha Inicio:</strong> {{ \Carbon\Carbon::parse($periodo->fecha_inicio)->format('d/m/Y') }}</p>
                            </div>
                            <div class="col-md-6 mb-2">
                                <p class="mb-1"><i class="fas fa-clock me-2"></i> <strong>Fecha Fin:</strong> {{ \Carbon\Carbon::parse($periodo->fecha_fin)->format('d/m/Y') }}</p>
                            </div>
                            <div class="col-md-6 mb-2">
                                <p class="mb-1"><i class="fas fa-info-circle me-2"></i> <strong>Estado:</strong> {{ $periodo->estado }}</p>
                            </div>
                            <div class="col-md-6 mb-2">
                                <p class="mb-1"><i class="fas fa-check me-2"></i> <strong>¿Es Final?</strong> {{ $periodo->es_final ? 'Sí' : 'No' }}</p>
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
    {{ $periodosEvaluacion->links() }}
</div>
@else
    <div class="alert alert-info text-center">
        No se encontraron registros de periodos de evaluación.
    </div>
@endif

<!-- Script para detalles -->
<!-- Script de acordeón -->
<script>
    document.querySelectorAll('.toggle-detalle').forEach(boton => {
        boton.addEventListener('click', () => {
            const id = boton.getAttribute('data-id');
            const fila = document.getElementById('detalle-' + id);
            const icono = boton.querySelector('i');

            // Cerrar todas las demás filas (comportamiento acordeón)
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

            // Alternar visibilidad de la fila seleccionada
            const estaVisible = fila.style.display !== 'none';
            fila.style.display = estaVisible ? 'none' : '';
            icono.classList.toggle('fa-chevron-down', estaVisible);
            icono.classList.toggle('fa-chevron-up', !estaVisible);

            // Si se está abriendo, hacer scroll suave hacia esa fila
            if (!estaVisible) {
                setTimeout(() => {
                    fila.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }, 200); // Espera breve para asegurar que el DOM se actualice
            }
        });
    });
</script>
