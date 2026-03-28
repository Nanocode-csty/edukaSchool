@extends('cplantilla.bprincipal')
@section('titulo','Tomar Asistencia - Docente')
@section('contenidoplantilla')
<x-breadcrumb :module="'asistencia'" :section="'docente-tomar'" />
<div class="container-fluid" id="contenido-principal">
    <div class="row mt-4 ml-1 mr-1">
        <div class="col-12">
            <div class="box_block">
                <!-- Collapse header -->
                <button class="btn btn-block text-left rounded-0 btn_header header_6" type="button" data-toggle="collapse" data-target="#collapseTomarAsistencia" aria-expanded="true" aria-controls="collapseTomarAsistencia" style="background: #007bff !important; font-weight: bold; color: white;">
                    <i class="fas fa-clipboard-check m-1"></i>&nbsp;Tomar Asistencia - {{ date('d/m/Y') }}
                    <div class="float-right"><i class="fas fa-chevron-down"></i></div>
                </button>
                <!-- Descripción (siempre visible, dentro del bloque) -->
                <div class="card-body rounded-0 border-0 pt-3 pb-3" style="background: #f3f3f3; border-bottom: 1px solid rgba(0,0,0,.125); border-top: 1px solid rgba(0,0,0,.125); color: #F59D24;">
                    <div class="row justify-content-center align-items-center flex-wrap">
                        <div class="col-auto text-center mb-2 mb-md-0" style="min-width:48px;">
                            <i class="fas fa-calendar-day fa-2x"></i>
                        </div>
                        <div class="col px-2" style="text-align:justify;">
                            <p style="margin-bottom: 0px; font-family: 'Quicksand', sans-serif; font-weight: 600; color: #004a92;">
                                Registra la asistencia de tus estudiantes directamente en esta vista. Selecciona el tipo de asistencia para cada estudiante y guarda los cambios.
                            </p>
                        </div>
                    </div>
                </div>
                <!-- Collapse: contenido del dashboard -->
                <div class="collapse show" id="collapseTomarAsistencia">
                    <div class="card card-body rounded-0 border-0 pt-0 pb-2" style="background: transparent;">

                        @if(isset($error))
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle"></i> {{ $error }}
                            </div>
                        @else
                            @if($clases_hoy->count() > 0)
                                @foreach($clases_hoy as $clase)
                                <div class="card mb-4 {{ $clase->tiene_asistencia_hoy ? 'border-success' : 'border-primary' }}">
                                    <div class="card-header {{ $clase->tiene_asistencia_hoy ? 'bg-success text-white' : 'bg-primary text-white' }}">
                                        <div class="row align-items-center">
                                            <div class="col-md-8">
                                                <h6 class="mb-1">
                                                    <i class="fas fa-chalkboard-teacher"></i>
                                                    {{ $clase->cursoAsignatura->asignatura->nombre }}
                                                </h6>
                                                <small>
                                                    <i class="fas fa-users"></i> {{ $clase->cursoAsignatura->curso->grado->nombre }} {{ $clase->cursoAsignatura->curso->seccion->nombre }} |
                                                    <i class="fas fa-clock"></i> {{ $clase->hora_inicio }} - {{ $clase->hora_fin }} |
                                                    <i class="fas fa-map-marker-alt"></i> {{ $clase->aula ? $clase->aula->nombre : 'Sin aula' }}
                                                </small>
                                            </div>
                                            <div class="col-md-4 text-right">
                                                @if($clase->tiene_asistencia_hoy)
                                                    <span class="badge badge-light">
                                                        <i class="fas fa-check-circle"></i> Asistencia Completada
                                                    </span>
                                                @else
                                                    <span class="badge badge-light">
                                                        <i class="fas fa-clock"></i> Pendiente
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        @if($clase->tiene_asistencia_hoy)
                                            <div class="alert alert-success">
                                                <i class="fas fa-check-circle"></i> La asistencia para esta clase ya ha sido registrada.
                                                <a href="{{ route('asistencia.docente.ver', $clase->sesion_id) }}" class="alert-link">Ver detalles</a>
                                            </div>
                                        @else
                                            <form id="form-asistencia-{{ $clase->sesion_id }}" class="attendance-form" data-sesion-id="{{ $clase->sesion_id }}">
                                                <div class="table-responsive">
                                                    <table class="table table-hover table-sm">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th style="width: 5%;">#</th>
                                                                <th style="width: 35%;">Estudiante</th>
                                                                <th style="width: 15%;" class="text-center">
                                                                    <i class="fas fa-check-circle text-success"></i><br>
                                                                    <small>Presente</small>
                                                                </th>
                                                                <th style="width: 15%;" class="text-center">
                                                                    <i class="fas fa-times-circle text-danger"></i><br>
                                                                    <small>Ausente</small>
                                                                </th>
                                                                <th style="width: 15%;" class="text-center">
                                                                    <i class="fas fa-clock text-warning"></i><br>
                                                                    <small>Tarde</small>
                                                                </th>
                                                                <th style="width: 15%;" class="text-center">
                                                                    <i class="fas fa-file-medical text-info"></i><br>
                                                                    <small>Justificado</small>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php
                                                                $estudiantes = $clase->cursoAsignatura->curso->matriculas()
                                                                    ->with(['estudiante.persona'])
                                                                    ->where('estado', 'Activo')
                                                                    ->orderBy('matriculas.matricula_id')
                                                                    ->get();
                                                            @endphp
                                                            @foreach($estudiantes as $index => $matricula)
                                                            <tr>
                                                                <td class="text-center font-weight-bold">{{ $index + 1 }}</td>
                                                                <td>
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="avatar-circle bg-primary text-white mr-2" style="width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: bold;">
                                                                            {{ substr($matricula->estudiante->persona->nombres, 0, 1) }}{{ substr($matricula->estudiante->persona->apellidos, 0, 1) }}
                                                                        </div>
                                                                        <div>
                                                                            <strong>{{ $matricula->estudiante->persona->nombres }}</strong><br>
                                                                            <small class="text-muted">{{ $matricula->estudiante->persona->apellidos }}</small>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td class="text-center">
                                                                    <input type="radio" name="asistencia_{{ $clase->sesion_id }}_{{ $matricula->matricula_id }}" value="P" checked
                                                                           class="attendance-radio" data-matricula-id="{{ $matricula->matricula_id }}">
                                                                </td>
                                                                <td class="text-center">
                                                                    <input type="radio" name="asistencia_{{ $clase->sesion_id }}_{{ $matricula->matricula_id }}" value="A"
                                                                           class="attendance-radio" data-matricula-id="{{ $matricula->matricula_id }}">
                                                                </td>
                                                                <td class="text-center">
                                                                    <input type="radio" name="asistencia_{{ $clase->sesion_id }}_{{ $matricula->matricula_id }}" value="T"
                                                                           class="attendance-radio" data-matricula-id="{{ $matricula->matricula_id }}">
                                                                </td>
                                                                <td class="text-center">
                                                                    <input type="radio" name="asistencia_{{ $clase->sesion_id }}_{{ $matricula->matricula_id }}" value="J"
                                                                           class="attendance-radio" data-matricula-id="{{ $matricula->matricula_id }}">
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div class="row mt-3">
                                                    <div class="col-12">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div class="form-group mb-0">
                                                                <label for="observaciones_{{ $clase->sesion_id }}">
                                                                    <i class="fas fa-comment"></i> Observaciones generales (opcional):
                                                                </label>
                                                                <textarea class="form-control" id="observaciones_{{ $clase->sesion_id }}" name="observaciones_generales"
                                                                          rows="2" placeholder="Observaciones para toda la clase..."></textarea>
                                                            </div>
                                                            <div class="d-flex gap-2">
                                                                <button type="button" class="btn btn-outline-secondary"
                                                                        onclick="marcarTodosPresentes({{ $clase->sesion_id }})">
                                                                    <i class="fas fa-check-double"></i> Todos Presentes
                                                                </button>
                                                                <button type="submit" class="btn btn-success btn-lg">
                                                                    <i class="fas fa-save"></i> Guardar Asistencia
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                                @endforeach

                                <!-- Resumen General -->
                                <div class="card border-info">
                                    <div class="card-header bg-info text-white">
                                        <h6 class="mb-0">
                                            <i class="fas fa-chart-bar"></i> Resumen del Día
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row text-center">
                                            <div class="col-md-3">
                                                <div class="p-3 bg-light rounded">
                                                    <h4 class="text-primary">{{ $clases_hoy->count() }}</h4>
                                                    <small class="text-muted">Clases Totales</small>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="p-3 bg-success rounded text-white">
                                                    <h4>{{ $clases_hoy->where('tiene_asistencia_hoy', true)->count() }}</h4>
                                                    <small>Completadas</small>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="p-3 bg-warning rounded text-white">
                                                    <h4>{{ $clases_hoy->where('tiene_asistencia_hoy', false)->count() }}</h4>
                                                    <small>Pendientes</small>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="p-3 bg-info rounded text-white">
                                                    <h4>{{ $clases_hoy->sum(function($c) { return $c->cursoAsignatura->curso->matriculas()->where('estado', 'Activo')->count(); }) }}</h4>
                                                    <small>Estudiantes Totales</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-calendar-times text-muted fa-4x mb-4"></i>
                                    <h4 class="text-muted mb-3">No hay clases programadas para hoy</h4>
                                    <p class="text-muted mb-4">No tienes sesiones de clase programadas para el día de hoy.</p>
                                    <a href="{{ route('asistencia.docente.dashboard') }}" class="btn btn-primary">
                                        <i class="fas fa-arrow-left"></i> Volver al Dashboard
                                    </a>
                                </div>
                            @endif

                            <!-- Acciones Rápidas -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="d-flex justify-content-end gap-2 flex-wrap">
                                        <a href="{{ route('asistencia.docente.ver-asistencias') }}" class="btn btn-sm" style="background-color: #28a745 !important; color: white !important; border: none !important;">
                                            <i class="fas fa-eye mr-1"></i>Ver Todas las Asistencias
                                        </a>
                                        <a href="{{ route('asistencia.docente.dashboard') }}" class="btn btn-sm" style="background-color: #6c757d !important; color: white !important; border: none !important;">
                                            <i class="fas fa-arrow-left mr-1"></i>Volver al Dashboard
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js-extra')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Collapse icon toggle
    const btn = document.querySelector('[data-target="#collapseTomarAsistencia"]');
    const icon = btn.querySelector('.fas.fa-chevron-down');
    const collapse = document.getElementById('collapseTomarAsistencia');
    collapse.addEventListener('show.bs.collapse', function () {
        icon.classList.remove('fa-chevron-down');
        icon.classList.add('fa-chevron-up');
    });
    collapse.addEventListener('hide.bs.collapse', function () {
        icon.classList.remove('fa-chevron-up');
        icon.classList.add('fa-chevron-down');
    });

    // Handle form submissions
    document.querySelectorAll('.attendance-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            guardarAsistenciaForm(this);
        });
    });
});

// Function to mark all students as present for a specific class
function marcarTodosPresentes(sesionId) {
    const radios = document.querySelectorAll(`input[name^="asistencia_${sesionId}_"][value="P"]`);
    radios.forEach(radio => {
        radio.checked = true;
    });

    // Show success message
    Swal.fire({
        icon: 'success',
        title: '¡Listo!',
        text: 'Todos los estudiantes marcados como presentes',
        timer: 1500,
        showConfirmButton: false
    });
}

// Function to save attendance for a specific form
function guardarAsistenciaForm(form) {
    const sesionId = form.getAttribute('data-sesion-id');
    const asistencias = [];
    let hasSelections = false;

    // Collect attendance data
    const radios = form.querySelectorAll('input[type="radio"]:checked');
    radios.forEach(radio => {
        hasSelections = true;
        const matriculaId = radio.getAttribute('data-matricula-id');
        const tipoAsistencia = radio.value;

        asistencias.push({
            matricula_id: matriculaId,
            tipo_asistencia: tipoAsistencia,
            observaciones: null
        });
    });

    if (!hasSelections) {
        Swal.fire({
            icon: 'warning',
            title: 'Atención',
            text: 'Debes seleccionar al menos un tipo de asistencia para un estudiante.'
        });
        return;
    }

    // Get general observations
    const observacionesGenerales = form.querySelector(`#observaciones_${sesionId}`).value;

    // Show loading state
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalHtml = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
    submitBtn.disabled = true;

    // Send data to server
    fetch('{{ route("asistencia.docente.guardar") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            sesion_clase_id: sesionId,
            asistencias: asistencias,
            observaciones_generales: observacionesGenerales
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: data.message,
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                // Reload the page to show updated status
                location.reload();
            });
        } else {
            // Show error message
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'Error al guardar la asistencia'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error al guardar la asistencia. Inténtalo de nuevo.'
        });
    })
    .finally(() => {
        // Restore button state
        submitBtn.innerHTML = originalHtml;
        submitBtn.disabled = false;
    });
}

// Function to view attendance details
function verAsistencia(sesionId) {
    window.location.href = '{{ route("asistencia.docente.ver", ":id") }}'.replace(':id', sesionId);
}

// Function to show quick stats for a class
function mostrarEstadisticasRapidas(sesionId) {
    const form = document.querySelector(`form[data-sesion-id="${sesionId}"]`);
    const radios = form.querySelectorAll('input[type="radio"]:checked');

    let presentes = 0, ausentes = 0, tardes = 0, justificados = 0;

    radios.forEach(radio => {
        switch(radio.value) {
            case 'P': presentes++; break;
            case 'A': ausentes++; break;
            case 'T': tardes++; break;
            case 'J': justificados++; break;
        }
    });

    const total = radios.length;
    const porcentajeAsistencia = total > 0 ? Math.round(((presentes + justificados) / total) * 100) : 0;

    Swal.fire({
        title: 'Estadísticas Rápidas',
        html: `
            <div class="text-center">
                <div class="row">
                    <div class="col-6">
                        <div class="p-2 bg-success text-white rounded mb-2">
                            <div class="h4 mb-0">${presentes}</div>
                            <small>Presentes</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 bg-danger text-white rounded mb-2">
                            <div class="h4 mb-0">${ausentes}</div>
                            <small>Ausentes</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 bg-warning text-white rounded mb-2">
                            <div class="h4 mb-0">${tardes}</div>
                            <small>Tardes</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 bg-info text-white rounded mb-2">
                            <div class="h4 mb-0">${justificados}</div>
                            <small>Justificados</small>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="h5 text-primary">${porcentajeAsistencia}% Asistencia</div>
                <small class="text-muted">Total estudiantes: ${total}</small>
            </div>
        `,
        showConfirmButton: false,
        showCloseButton: true
    });
}
</script>
@endpush
