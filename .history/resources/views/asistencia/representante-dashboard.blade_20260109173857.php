@extends('cplantilla.bprincipal')
@section('titulo','Dashboard de Asistencia - Representante')
@section('contenidoplantilla')

<x-breadcrumb :module="'asistencia'" :section="'representante-dashboard'" />

<div class="container-fluid" id="contenido-principal">
    <div class="row mt-4 ml-1 mr-1">
        <div class="col-12">
            <div class="box_block">
                <!-- Collapse header -->
                <button class="btn btn-block text-left rounded-0 btn_header header_6" type="button" data-toggle="collapse" data-target="#collapseDashboard" aria-expanded="true" aria-controls="collapseDashboard" style="background: #17a2b8 !important; font-weight: bold; color: white;">
                    <i class="fas fa-tachometer-alt m-1"></i>&nbsp;Dashboard de Asistencia
                    <div class="float-right"><i class="fas fa-chevron-down"></i></div>
                </button>
                <!-- Descripción (siempre visible, dentro del bloque) -->
                <div class="card-body rounded-0 border-0 pt-3 pb-3" style="background: #f3f3f3; border-bottom: 1px solid rgba(0,0,0,.125); border-top: 1px solid rgba(0,0,0,.125); color: #F59D24;">
                    <div class="row justify-content-center align-items-center flex-wrap">
                        <div class="col-auto text-center mb-2 mb-md-0" style="min-width:48px;">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                        <div class="col px-2" style="text-align:justify;">
                            <p style="margin-bottom: 0px; font-family: 'Quicksand', sans-serif; font-weight: 600; color: #004a92;">
                                Monitorea la asistencia de tus estudiantes. Revisa estadísticas generales, solicita justificaciones y accede a reportes detallados.
                            </p>
                        </div>
                    </div>
                </div>
                <!-- Collapse: contenido del dashboard -->
                <div class="collapse show" id="collapseDashboard">
                    <div class="card card-body rounded-0 border-0 pt-0 pb-2" style="background: transparent;">

                        @if(isset($error))
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle"></i> {{ $error }}
                            </div>
                        @else
                            <!-- Estadísticas Generales -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <h5 class="mb-3"><i class="fas fa-chart-bar"></i> Estadísticas Generales del Mes</h5>
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <div class="card border-primary">
                                                <div class="card-body text-center">
                                                    <div class="h3 text-primary">{{ $estadisticas['total_estudiantes'] ?? 0 }}</div>
                                                    <div class="text-muted">Estudiantes</div>
                                                    <small class="text-primary"><i class="fas fa-users"></i> Total</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <div class="card border-success">
                                                <div class="card-body text-center">
                                                    <div class="h3 text-success">{{ $estadisticas['promedio_asistencia'] ?? 0 }}%</div>
                                                    <div class="text-muted">Asistencia Promedio</div>
                                                    <small class="text-success"><i class="fas fa-check-circle"></i> Mensual</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <div class="card border-danger">
                                                <div class="card-body text-center">
                                                    <div class="h3 text-danger">{{ $estadisticas['total_inasistencias'] ?? 0 }}</div>
                                                    <div class="text-muted">Inasistencias</div>
                                                    <small class="text-danger"><i class="fas fa-times-circle"></i> Total</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <div class="card border-warning">
                                                <div class="card-body text-center">
                                                    <div class="h3 text-warning">{{ $estadisticas['justificaciones_pendientes'] ?? 0 }}</div>
                                                    <div class="text-muted">Justificaciones</div>
                                                    <small class="text-warning"><i class="fas fa-clock"></i> Pendientes</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Lista de Estudiantes -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="mb-0"><i class="fas fa-user-graduate"></i> Mis Estudiantes</h5>
                                        </div>
                                        <div class="card-body">
                                            @if($estadisticas['total_estudiantes'] > 0)
                                                <div class="table-responsive">
                                                    <table class="table table-hover" style="border: 1px solid #17a2b8; border-radius: 10px; overflow: hidden;">
                                                        <thead class="text-center" style="background-color: #f8f9fa; color: #17a2b8;">
                                                            <tr>
                                                                <th scope="col">Estudiante</th>
                                                                <th scope="col">Curso</th>
                                                                <th scope="col">Asistencia Hoy</th>
                                                                <th scope="col">% Asistencia</th>
                                                                <th scope="col">Inasistencias</th>
                                                                <th scope="col">Acciones</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php
                                                                $representante = Auth::user()->persona->representante;
                                                                $estudiantes = $representante->estudiantes()
                                                                    ->with(['persona', 'matricula.grado', 'matricula.seccion'])
                                                                    ->get()
                                                                    ->map(function($estudiante) {
                                                                        if (!$estudiante->matricula) {
                                                                            $estudiante->asistencia_hoy = null;
                                                                            $estudiante->porcentaje_asistencia = 0;
                                                                            $estudiante->inasistencias_mes = 0;
                                                                            return $estudiante;
                                                                        }

                                                                        // Calcular estadísticas del mes actual
                                                                        $mesActual = now()->month;
                                                                        $anioActual = now()->year;

                                                                        $asistenciasMes = \App\Models\AsistenciaAsignatura::where('matricula_id', $estudiante->matricula->matricula_id)
                                                                            ->whereMonth('fecha', $mesActual)
                                                                            ->whereYear('fecha', $anioActual)
                                                                            ->get();

                                                                        $totalAsistencias = $asistenciasMes->count();
                                                                        $inasistencias = $asistenciasMes->where('tipo_asistencia_id', \App\Models\TipoAsistencia::where('codigo', 'A')->first()->id ?? 2)->count();

                                                                        $estudiante->asistencia_hoy = \App\Models\AsistenciaAsignatura::where('matricula_id', $estudiante->matricula->matricula_id)
                                                                            ->whereDate('fecha', today())
                                                                            ->with('tipoAsistencia')
                                                                            ->first();

                                                                        $estudiante->porcentaje_asistencia = $totalAsistencias > 0 ?
                                                                            round((($totalAsistencias - $inasistencias) / $totalAsistencias) * 100, 1) : 0;

                                                                        $estudiante->inasistencias_mes = $inasistencias;

                                                                        return $estudiante;
                                                                    });
                                                            @endphp

                                                            @foreach($estudiantes as $estudiante)
                                                            <tr>
                                                                <td>
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="avatar-circle mr-2" style="width: 35px; height: 35px; border-radius: 50%; background: #17a2b8; color: white; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                                                                            {{ substr($estudiante->persona->nombres ?? 'N', 0, 1) }}{{ substr($estudiante->persona->apellidos ?? 'A', 0, 1) }}
                                                                        </div>
                                                                        <div>
                                                                            <div class="font-weight-bold">{{ $estudiante->persona->nombres ?? 'N/A' }}</div>
                                                                            <small class="text-muted">{{ $estudiante->persona->apellidos ?? '' }}</small>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td class="text-center">
                                                                    @if($estudiante->matricula)
                                                                        {{ $estudiante->matricula->grado->nombre ?? 'N/A' }}
                                                                        {{ $estudiante->matricula->seccion->nombre ?? '' }}
                                                                    @else
                                                                        <span class="text-muted">Sin matrícula</span>
                                                                    @endif
                                                                </td>
                                                                <td class="text-center">
                                                                    @if($estudiante->asistencia_hoy)
                                                                        <span class="badge badge-{{ $estudiante->asistencia_hoy->tipoAsistencia->codigo == 'P' ? 'success' : ($estudiante->asistencia_hoy->tipoAsistencia->codigo == 'A' ? 'danger' : ($estudiante->asistencia_hoy->tipoAsistencia->codigo == 'T' ? 'warning' : 'info')) }}">
                                                                            {{ $estudiante->asistencia_hoy->tipoAsistencia->nombre }}
                                                                        </span>
                                                                    @else
                                                                        <span class="badge badge-secondary">Sin registro</span>
                                                                    @endif
                                                                </td>
                                                                <td class="text-center">
                                                                    <span class="font-weight-bold {{ $estudiante->porcentaje_asistencia >= 90 ? 'text-success' : ($estudiante->porcentaje_asistencia >= 80 ? 'text-warning' : 'text-danger') }}">
                                                                        {{ $estudiante->porcentaje_asistencia }}%
                                                                    </span>
                                                                </td>
                                                                <td class="text-center">
                                                                    <span class="font-weight-bold text-danger">{{ $estudiante->inasistencias_mes }}</span>
                                                                </td>
                                                                <td class="text-center">
                                                                    <div class="btn-group" role="group">
                                                                        <a href="{{ route('asistencia.representante.detalle', $estudiante->estudiante_id) }}"
                                                                           class="btn btn-sm btn-outline-primary" title="Ver detalles">
                                                                            <i class="fas fa-eye"></i>
                                                                        </a>
                                                                        <button class="btn btn-sm btn-outline-success"
                                                                                onclick="solicitarJustificacion({{ $estudiante->estudiante_id }}, '{{ addslashes($estudiante->persona->nombres . ' ' . $estudiante->persona->apellidos) }}')"
                                                                                title="Solicitar justificación">
                                                                            <i class="fas fa-file-medical"></i>
                                                                        </button>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @else
                                                <div class="text-center py-5">
                                                    <i class="fas fa-users text-muted fa-4x mb-3"></i>
                                                    <h5 class="text-muted">No tienes estudiantes asignados</h5>
                                                    <p class="text-muted">Contacta al administrador si crees que esto es un error.</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Acciones Rápidas -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                                        <div class="d-flex gap-2 flex-wrap">
                                            <button class="btn btn-outline-primary" onclick="actualizarEstadisticas()">
                                                <i class="fas fa-sync-alt"></i> Actualizar Estadísticas
                                            </button>
                                            @if($estadisticas['justificaciones_pendientes'] > 0)
                                            <span class="badge badge-warning">
                                                <i class="fas fa-clock"></i> {{ $estadisticas['justificaciones_pendientes'] }} justificaciones pendientes
                                            </span>
                                            @endif
                                        </div>
                                        <div class="d-flex gap-2 flex-wrap">
                                            <a href="{{ route('asistencia.representante.index') }}" class="btn btn-primary">
                                                <i class="fas fa-list"></i> Ver Todos los Estudiantes
                                            </a>
                                            <a href="{{ route('rutarrr1') }}" class="btn btn-secondary">
                                                <i class="fas fa-arrow-left"></i> Volver al Inicio
                                            </a>
                                        </div>
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

<!-- Modal para solicitar justificación -->
<div class="modal fade" id="justificacionModal" tabindex="-1" role="dialog" aria-labelledby="justificacionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="justificacionModalLabel">
                    <i class="fas fa-file-medical"></i> Solicitar Justificación
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="justificacionForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="estudiante_id">Estudiante</label>
                        <input type="text" class="form-control" id="estudianteNombre" readonly>
                        <input type="hidden" id="estudiante_id" name="estudiante_id">
                    </div>
                    <div class="form-group">
                        <label for="fecha_falta">Fecha de la falta *</label>
                        <input type="date" class="form-control" id="fecha_falta" name="fecha_falta" required max="{{ date('Y-m-d') }}">
                    </div>
                    <div class="form-group">
                        <label for="motivo">Motivo de la justificación *</label>
                        <textarea class="form-control" id="motivo" name="motivo" rows="3" required
                                  placeholder="Describe el motivo de la inasistencia..."></textarea>
                    </div>
                    <div class="form-group">
                        <label for="documento_adjunto">Documento adjunto (opcional)</label>
                        <input type="file" class="form-control-file" id="documento_adjunto" name="documento_adjunto"
                               accept=".pdf,.jpg,.jpeg,.png">
                        <small class="form-text text-muted">Formatos permitidos: PDF, JPG, PNG. Máximo 2MB.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Enviar Solicitud
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js-extra')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Collapse icon toggle
    const btn = document.querySelector('[data-target="#collapseDashboard"]');
    const icon = btn.querySelector('.fas.fa-chevron-down');
    const collapse = document.getElementById('collapseDashboard');
    collapse.addEventListener('show.bs.collapse', function () {
        icon.classList.remove('fa-chevron-down');
        icon.classList.add('fa-chevron-up');
    });
    collapse.addEventListener('hide.bs.collapse', function () {
        icon.classList.remove('fa-chevron-up');
        icon.classList.add('fa-chevron-down');
    });
});

function solicitarJustificacion(estudianteId, estudianteNombre) {
    document.getElementById('estudiante_id').value = estudianteId;
    document.getElementById('estudianteNombre').value = estudianteNombre;
    document.getElementById('fecha_falta').value = '';
    document.getElementById('motivo').value = '';
    document.getElementById('documento_adjunto').value = '';

    $('#justificacionModal').modal('show');
}

function actualizarEstadisticas() {
    // Reload the page to refresh statistics
    location.reload();
}

// Handle justificación form submission
document.getElementById('justificacionForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch('{{ route("asistencia.representante.solicitar-justificacion") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            $('#justificacionModal').modal('hide');
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: data.message,
                timer: 3000,
                showConfirmButton: false
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Error', 'Error al enviar la solicitud', 'error');
    });
});
</script>
@endpush

@push('css-extra')
<style>
/* Weekly Calendar Styles */
.weekly-calendar {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 8px;
    padding: 15px;
}

.calendar-day {
    aspect-ratio: 0.8;
    border-radius: 6px;
    padding: 6px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid #e9ecef;
    background: white;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    position: relative;
    overflow: hidden;
    min-height: 70px;
    max-height: 80px;
}

/* Responsive calendar */
@media (max-width: 768px) {
    .weekly-calendar {
        grid-template-columns: repeat(7, 1fr);
        gap: 4px;
        padding: 10px;
    }

    .calendar-day {
        padding: 6px;
        min-height: 60px;
    }

    .day-number {
        font-size: 18px;
    }

    .day-name {
        font-size: 10px;
    }
}

@media (max-width: 576px) {
    .weekly-calendar {
        grid-template-columns: repeat(7, 1fr);
        gap: 2px;
        padding: 8px;
    }

    .calendar-day {
        padding: 4px;
        min-height: 50px;
    }

    .day-number {
        font-size: 16px;
    }

    .day-name {
        font-size: 9px;
    }
}

.calendar-day:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.calendar-day.today {
    border-color: #007bff;
    background: #f8f9ff;
}

.calendar-day.selected {
    border-color: #007bff;
    background: #f0f8ff;
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.3);
    transform: scale(1.02);
}

.calendar-day.selected .day-number,
.calendar-day.selected .day-name {
    color: #007bff;
    font-weight: bold;
}

.calendar-day.has-classes {
    border-color: #17a2b8;
}

.calendar-day.has-classes.completed {
    border-color: #28a745;
}

.calendar-day.has-classes.pending {
    border-color: #ffc107;
}

.day-number {
    font-size: 24px;
    font-weight: bold;
    color: #2c3e50;
    margin-bottom: 5px;
}

.day-name {
    font-size: 12px;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 10px;
}

.day-stats {
    display: flex;
    justify-content: space-around;
    align-items: center;
    margin-top: auto;
}

.day-stat {
    display: flex;
    flex-direction: column;
    align-items: center;
    font-size: 10px;
}

.day-stat.completed {
    color: #28a745;
}

.day-stat.pending {
    color: #ffc107;
}

.day-stat-number {
    font-weight: bold;
    font-size: 12px;
}

.day-stat-label {
    font-size: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.calendar-header {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 10px;
    margin-bottom: 10px;
    padding: 0 20px;
}

.calendar-header-day {
    text-align: center;
    font-weight: 600;
    color: #495057;
    font-size: 14px;
    padding: 10px 0;
}

.calendar-navigation {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 20px;
    background: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
}

.calendar-week-info {
    font-weight: 600;
    color: #2c3e50;
}

/* Calendar hidden class */
.calendar-hidden {
    display: none !important;
}

/* Gradient backgrounds */
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
</style>
@endpush

@push('js-extra')
<script>
// Function to toggle weekly calendar visibility
function toggleWeeklyCalendar() {
    const calendarSection = document.getElementById('weeklyCalendar');
    const toggleBtn = document.getElementById('toggleCalendarBtn');
    const icon = toggleBtn ? toggleBtn.querySelector('.fas') : null;

    if (calendarSection) {
        if (calendarSection.style.display === 'none' || calendarSection.style.display === '') {
            // Show calendar
            console.log('Showing calendar inline');

            // Get current date from the display
            const currentDateDisplay = document.getElementById('current-date-display');
            let currentDate = new Date();

            if (currentDateDisplay) {
                const currentDateText = currentDateDisplay.textContent;
                const dateMatch = currentDateText.match(/(\d{1,2})\/(\d{1,2})\/(\d{4})/);

                if (dateMatch) {
                    const day = parseInt(dateMatch[1]);
                    const month = parseInt(dateMatch[2]) - 1;
                    const year = parseInt(dateMatch[3]);
                    currentDate = new Date(year, month, day);
                }
            }

            // Calculate start of week (Monday)
            const startOfWeek = new Date(currentDate);
            const dayOfWeek = startOfWeek.getDay();
            const diff = startOfWeek.getDate() - dayOfWeek + (dayOfWeek === 0 ? -6 : 1);
            startOfWeek.setDate(diff);

            // Create calendar content
            let calendarHtml = `
                <div id="weekly-calendar-container">
                    <div class="calendar-header">
                        <div class="calendar-header-day">Lun</div>
                        <div class="calendar-header-day">Mar</div>
                        <div class="calendar-header-day">Mié</div>
                        <div class="calendar-header-day">Jue</div>
                        <div class="calendar-header-day">Vie</div>
                        <div class="calendar-header-day">Sáb</div>
                        <div class="calendar-header-day">Dom</div>
                    </div>
                    <div class="weekly-calendar">
            `;

            // Generate 7 days
            for (let i = 0; i < 7; i++) {
                const dayDate = new Date(startOfWeek);
                dayDate.setDate(startOfWeek.getDate() + i);

                const isToday = dayDate.toDateString() === new Date().toDateString();
                const isSelected = dayDate.toDateString() === currentDate.toDateString();
                const dayNumber = dayDate.getDate();
                const dayName = dayDate.toLocaleDateString('es-ES', { weekday: 'short' });
                const dateString = `${dayDate.getFullYear()}-${String(dayDate.getMonth() + 1).padStart(2, '0')}-${String(dayDate.getDate()).padStart(2, '0')}`;

                // Mock attendance data - in production this would come from server
                const hasClasses = Math.random() > 0.5; // Increased probability to show more stats
                const completedClasses = hasClasses ? Math.floor(Math.random() * 3) + 1 : 0; // At least 1 if has classes
                const pendingClasses = hasClasses ? Math.floor(Math.random() * 2) + 1 : 0; // At least 1 if has classes

                let dayClasses = '';
                if (hasClasses) {
                    dayClasses = 'has-classes';
                    if (completedClasses > pendingClasses) {
                        dayClasses += ' completed';
                    } else if (pendingClasses > 0) {
                        dayClasses += ' pending';
                    }
                }

                calendarHtml += `
                    <div class="calendar-day ${isToday ? 'today' : ''} ${isSelected ? 'selected' : ''} ${dayClasses}"
                         data-date="${dateString}">
                        <div class="day-number">${dayNumber}</div>
                        <div class="day-name">${dayName}</div>
                        ${hasClasses ? `
                            <div class="day-stats">
                                <div class="day-stat completed">
                                    <div class="day-stat-number">${completedClasses}</div>
                                    <div class="day-stat-label">OK</div>
                                </div>
                                <div class="day-stat pending">
                                    <div class="day-stat-number">${pendingClasses}</div>
                                    <div class="day-stat-label">PEN</div>
                                </div>
                            </div>
                        ` : ''}
                    </div>
                `;
            }

            calendarHtml += `
                    </div>
                    <div class="calendar-navigation">
                        <div class="calendar-week-info">
                            Semana del ${startOfWeek.getDate()}/${startOfWeek.getMonth() + 1} al ${new Date(startOfWeek.getTime() + 6 * 24 * 60 * 60 * 1000).getDate()}/${new Date(startOfWeek.getTime() + 6 * 24 * 60 * 60 * 1000).getMonth() + 1}
                        </div>
                        <div>
                            <small class="text-muted">
                                <i class="fas fa-circle text-success"></i> Completado
                                <i class="fas fa-circle text-warning"></i> Pendiente
                                <i class="fas fa-circle text-info"></i> Con clases
                            </small>
                        </div>
                    </div>
                </div>
            `;

            // Replace the content and show
            calendarSection.innerHTML = calendarHtml;
            calendarSection.style.display = 'block';
            calendarSection.style.visibility = 'visible';
            calendarSection.style.opacity = '1';

            // Assign event listeners to calendar days
            asignarEventListenersCalendario();

            // Update button icon
            if (icon) {
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
            }
        } else {
            // Hide calendar
            console.log('Hiding calendar inline');
            calendarSection.style.display = 'none';
            calendarSection.style.visibility = 'hidden';

            // Update button icon
            if (icon) {
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
            }
        }
    } else {
        console.log('ERROR: Calendar section not found');
    }
}

// Function to assign event listeners to calendar days
function asignarEventListenersCalendario() {
    console.log('=== ASIGNANDO EVENT LISTENERS AL CALENDARIO ===');

    const calendarDays = document.querySelectorAll('.calendar-day');
    console.log('Encontrados días del calendario:', calendarDays.length);

    calendarDays.forEach((day, index) => {
        const date = day.getAttribute('data-date');
        console.log(`Configurando listener para día ${index + 1}, fecha: ${date}`);

        // Remove existing listeners to avoid duplicates
        day.removeEventListener('click', day._calendarClickHandler);

        // Create new click handler
        day._calendarClickHandler = function() {
            console.log('=== CLICK EN DÍA DEL CALENDARIO ===');
            console.log('Fecha del día clickeado:', date);
            if (date) {
                seleccionarDia(date);
            } else {
                console.error('ERROR: Día clickeado no tiene data-date');
            }
        };

        // Add the event listener
        day.addEventListener('click', day._calendarClickHandler);
        day.style.cursor = 'pointer';

        console.log(`Listener asignado al día ${index + 1}`);
    });

    console.log('=== EVENT LISTENERS ASIGNADOS ===');
}

document.addEventListener('DOMContentLoaded', function () {
    console.log('=== DOM LOADED - INICIALIZANDO PÁGINA DE ASISTENCIA ===');
    console.log('✅ CAMBIOS APLICADOS:');
    console.log('  - Cards del calendario más pequeños (aspect-ratio: 0.8)');
    console.log('  - Estadísticas OK/PEN visibles en cada día');
    console.log('  - Calendario se mantiene abierto al cambiar semana');
    console.log('=== INICIALIZACIÓN COMPLETA ===');

    // Initialize date click handler
    const dateDisplay = document.querySelector('.text-center.mx-4 h4');
    if (dateDisplay) {
        dateDisplay.addEventListener('click', function() {
            console.log('Date clicked, toggling calendar...');
            toggleWeeklyCalendar();
        });
    }

    // Initialize calendar button
    const calendarBtn = document.getElementById('toggleCalendarBtn');
    if (calendarBtn) {
        calendarBtn.addEventListener('click', function() {
            console.log('Calendar button clicked...');
            toggleWeeklyCalendar();
        });
    }

    // Initialize attendance forms
    document.querySelectorAll('.attendance-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            guardarAsistenciaForm(this);
        });
    });

    // Initialize attendance options
    document.querySelectorAll('.attendance-option').forEach(option => {
        option.addEventListener('click', function() {
            const radio = this.querySelector('input[type="radio"]');
            if (radio) {
                radio.checked = true;
                const sesionId = radio.getAttribute('data-sesion-id');
                updateAttendanceOptionVisuals(sesionId);
            }
        });
    });

    // Load calendar initially since it's now visible by default
    console.log('Loading calendar initially...');
    // Since calendar is already visible (display: block), just load the content
    const calendarSection = document.getElementById('weeklyCalendar');
    if (calendarSection) {
        // Get current date from the display
        const currentDateDisplay = document.getElementById('current-date-display');
        let currentDate = new Date();

        if (currentDateDisplay) {
            const currentDateText = currentDateDisplay.textContent;
            const dateMatch = currentDateText.match(/(\d{1,2})\/(\d{1,2})\/(\d{4})/);

            if (dateMatch) {
                const day = parseInt(dateMatch[1]);
                const month = parseInt(dateMatch[2]) - 1;
                const year = parseInt(dateMatch[3]);
                currentDate = new Date(year, month, day);
            }
        }

        // Calculate start of week (Monday)
        const startOfWeek = new Date(currentDate);
        const dayOfWeek = startOfWeek.getDay();
        const diff = startOfWeek.getDate() - dayOfWeek + (dayOfWeek === 0 ? -6 : 1);
        startOfWeek.setDate(diff);

        // Create calendar content
        let calendarHtml = `
            <div id="weekly-calendar-container">
                <div class="calendar-header">
                    <div class="calendar-header-day">Lun</div>
                    <div class="calendar-header-day">Mar</div>
                    <div class="calendar-header-day">Mié</div>
                    <div class="calendar-header-day">Jue</div>
                    <div class="calendar-header-day">Vie</div>
                    <div class="calendar-header-day">Sáb</div>
                    <div class="calendar-header-day">Dom</div>
                </div>
                <div class="weekly-calendar">
        `;

        // Generate 7 days
        for (let i = 0; i < 7; i++) {
            const dayDate = new Date(startOfWeek);
            dayDate.setDate(startOfWeek.getDate() + i);

            const isToday = dayDate.toDateString() === new Date().toDateString();
            const isSelected = dayDate.toDateString() === currentDate.toDateString();
            const dayNumber = dayDate.getDate();
            const dayName = dayDate.toLocaleDateString('es-ES', { weekday: 'short' });
            const dateString = `${dayDate.getFullYear()}-${String(dayDate.getMonth() + 1).padStart(2, '0')}-${String(dayDate.getDate()).padStart(2, '0')}`;

            // Mock attendance data - in production this would come from server
            const hasClasses = Math.random() > 0.5; // Increased probability to show more stats
            const completedClasses = hasClasses ? Math.floor(Math.random() * 3) + 1 : 0; // At least 1 if has classes
            const pendingClasses = hasClasses ? Math.floor(Math.random() * 2) + 1 : 0; // At least 1 if has classes

            let dayClasses = '';
            if (hasClasses) {
                dayClasses = 'has-classes';
                if (completedClasses > pendingClasses) {
                    dayClasses += ' completed';
                } else if (pendingClasses > 0) {
                    dayClasses += ' pending';
                }
            }

            calendarHtml += `
                <div class="calendar-day ${isToday ? 'today' : ''} ${isSelected ? 'selected' : ''} ${dayClasses}"
                     data-date="${dateString}">
                    <div class="day-number">${dayNumber}</div>
                    <div class="day-name">${dayName}</div>
                    ${hasClasses ? `
                        <div class="day-stats">
                            <div class="day-stat completed">
                                <div class="day-stat-number">${completedClasses}</div>
                                <div class="day-stat-label">OK</div>
                            </div>
                            <div class="day-stat pending">
                                <div class="day-stat-number">${pendingClasses}</div>
                                <div class="day-stat-label">PEN</div>
                            </div>
                        </div>
                    ` : ''}
                </div>
            `;
        }

        calendarHtml += `
                </div>
                <div class="calendar-navigation">
                    <div class="calendar-week-info">
                        Semana del ${startOfWeek.getDate()}/${startOfWeek.getMonth() + 1} al ${new Date(startOfWeek.getTime() + 6 * 24 * 60 * 60 * 1000).getDate()}/${new Date(startOfWeek.getTime() + 6 * 24 * 60 * 60 * 1000).getMonth() + 1}
                    </div>
                    <div>
                        <small class="text-muted">
                            <i class="fas fa-circle text-success"></i> Completado
                            <i class="fas fa-circle text-warning"></i> Pendiente
                            <i class="fas fa-circle text-info"></i> Con clases
                        </small>
                    </div>
                </div>
            </div>
        `;

        // Replace the content and show
        calendarSection.innerHTML = calendarHtml;
        calendarSection.style.display = 'block';
        calendarSection.style.visibility = 'visible';
        calendarSection.style.opacity = '1';

        // Assign event listeners to calendar days
        asignarEventListenersCalendario();

        // Update button icon to show it's expanded
        const toggleBtn = document.getElementById('toggleCalendarBtn');
        const icon = toggleBtn ? toggleBtn.querySelector('.fas') : null;
        if (icon) {
            icon.classList.remove('fa-chevron-down');
            icon.classList.add('fa-chevron-up');
        }

        console.log('Calendar loaded and visible with stats!');
    }
});

// Function to toggle weekly calendar visibility
function toggleWeeklyCalendar() {
    const calendarSection = document.getElementById('weeklyCalendar');
    const toggleBtn = document.getElementById('toggleCalendarBtn');
    const icon = toggleBtn ? toggleBtn.querySelector('.fas') : null;

    if (calendarSection) {
        if (calendarSection.style.display === 'none' || calendarSection.style.display === '') {
            // Show calendar
            console.log('Showing calendar inline');

            // Get current date from the display
            const currentDateDisplay = document.getElementById('current-date-display');
            let currentDate = new Date();

            if (currentDateDisplay) {
                const currentDateText = currentDateDisplay.textContent;
                const dateMatch = currentDateText.match(/(\d{1,2})\/(\d{1,2})\/(\d{4})/);

                if (dateMatch) {
                    const day = parseInt(dateMatch[1]);
                    const month = parseInt(dateMatch[2]) - 1;
                    const year = parseInt(dateMatch[3]);
                    currentDate = new Date(year, month, day);
                }
            }

            // Calculate start of week (Monday)
            const startOfWeek = new Date(currentDate);
            const dayOfWeek = startOfWeek.getDay();
            const diff = startOfWeek.getDate() - dayOfWeek + (dayOfWeek === 0 ? -6 : 1);
            startOfWeek.setDate(diff);

            // Create calendar content
            let calendarHtml = `
                <div id="weekly-calendar-container">
                    <div class="calendar-header">
                        <div class="calendar-header-day">Lun</div>
                        <div class="calendar-header-day">Mar</div>
                        <div class="calendar-header-day">Mié</div>
                        <div class="calendar-header-day">Jue</div>
                        <div class="calendar-header-day">Vie</div>
                        <div class="calendar-header-day">Sáb</div>
                        <div class="calendar-header-day">Dom</div>
                    </div>
                    <div class="weekly-calendar">
            `;

            // Generate 7 days
            for (let i = 0; i < 7; i++) {
                const dayDate = new Date(startOfWeek);
                dayDate.setDate(startOfWeek.getDate() + i);

                const isToday = dayDate.toDateString() === new Date().toDateString();
                const isSelected = dayDate.toDateString() === currentDate.toDateString();
                const dayNumber = dayDate.getDate();
                const dayName = dayDate.toLocaleDateString('es-ES', { weekday: 'short' });
                const dateString = `${dayDate.getFullYear()}-${String(dayDate.getMonth() + 1).padStart(2, '0')}-${String(dayDate.getDate()).padStart(2, '0')}`;

                // Mock attendance data - in production this would come from server
                const hasClasses = Math.random() > 0.5; // Increased probability to show more stats
                const completedClasses = hasClasses ? Math.floor(Math.random() * 3) + 1 : 0; // At least 1 if has classes
                const pendingClasses = hasClasses ? Math.floor(Math.random() * 2) + 1 : 0; // At least 1 if has classes

                let dayClasses = '';
                if (hasClasses) {
                    dayClasses = 'has-classes';
                    if (completedClasses > pendingClasses) {
                        dayClasses += ' completed';
                    } else if (pendingClasses > 0) {
                        dayClasses += ' pending';
                    }
                }

                calendarHtml += `
                    <div class="calendar-day ${isToday ? 'today' : ''} ${isSelected ? 'selected' : ''} ${dayClasses}"
                         data-date="${dateString}">
                        <div class="day-number">${dayNumber}</div>
                        <div class="day-name">${dayName}</div>
                        ${hasClasses ? `
                            <div class="day-stats">
                                <div class="day-stat completed">
                                    <div class="day-stat-number">${completedClasses}</div>
                                    <div class="day-stat-label">OK</div>
                                </div>
                                <div class="day-stat pending">
                                    <div class="day-stat-number">${pendingClasses}</div>
                                    <div class="day-stat-label">PEN</div>
                                </div>
                            </div>
                        ` : ''}
                    </div>
                `;
            }

            calendarHtml += `
                    </div>
                    <div class="calendar-navigation">
                        <div class="calendar-week-info">
                            Semana del ${startOfWeek.getDate()}/${startOfWeek.getMonth() + 1} al ${new Date(startOfWeek.getTime() + 6 * 24 * 60 * 60 * 1000).getDate()}/${new Date(startOfWeek.getTime() + 6 * 24 * 60 * 60 * 1000).getMonth() + 1}
                        </div>
                        <div>
                            <small class="text-muted">
                                <i class="fas fa-circle text-success"></i> Completado
                                <i class="fas fa-circle text-warning"></i> Pendiente
                                <i class="fas fa-circle text-info"></i> Con clases
                            </small>
                        </div>
                    </div>
                </div>
            `;

            // Replace the content and show
            calendarSection.innerHTML = calendarHtml;
            calendarSection.style.display = 'block';
            calendarSection.style.visibility = 'visible';
            calendarSection.style.opacity = '1';

            // Assign event listeners to calendar days
            asignarEventListenersCalendario();

            // Update button icon
            if (icon) {
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
            }
        } else {
            // Hide calendar
            console.log('Hiding calendar inline');
            calendarSection.style.display = 'none';
            calendarSection.style.visibility = 'hidden';

            // Update button icon
            if (icon) {
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
            }
        }
    } else {
        console.log('ERROR: Calendar section not found');
    }
}

// Function to close calendar modal
function cerrarCalendarioModal() {
    const modal = document.getElementById('calendar-modal');
    if (modal) {
        modal.remove();
    }
}

// Function to toggle class details panel
function toggleClassDetails(sesionId) {
    const panel = document.getElementById(`attendance-panel-${sesionId}`);
    if (panel) {
        if (panel.style.display === 'none' || panel.style.display === '') {
            panel.style.display = 'block';
        } else {
            panel.style.display = 'none';
        }
    }
}

// Function for quick attendance marking - redirects to specific attendance view
function marcarAsistenciaRapida(sesionId) {
    console.log('=== REDIRIGIENDO A VISTA DE TOMA DE ASISTENCIA ===');
    console.log('Sesión ID:', sesionId);

    // Redirect to the specific attendance taking view for this session
    const url = `{{ route('asistencia.docente.tomar-asistencia') }}?sesion=${sesionId}`;
    console.log('Redirecting to:', url);
    window.location.href = url;
}

// Function to update attendance option visuals
function updateAttendanceOptionVisuals(sesionId) {
    // Remove active class from all options in this session
    const allOptions = document.querySelectorAll(`input[data-sesion-id="${sesionId}"]`);
    allOptions.forEach(input => {
        const option = input.closest('.attendance-option');
        if (option) {
            option.classList.remove('active', 'present', 'absent', 'late', 'justified');
        }
    });

    // Add active class to checked options
    const checkedRadios = document.querySelectorAll(`input[data-sesion-id="${sesionId}"]:checked`);
    checkedRadios.forEach(radio => {
        const option = radio.closest('.attendance-option');
        if (option) {
            const type = radio.value.toLowerCase();
            option.classList.add('active', type);
        }
    });
}

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

// Function to apply filters to student list
function aplicarFiltros(sesionId) {
    const searchInput = document.getElementById(`search_${sesionId}`);
    const filterSelect = document.getElementById(`filter_${sesionId}`);
    const attendanceGrid = document.getElementById(`attendance-grid-${sesionId}`);

    if (!attendanceGrid) return;

    const searchTerm = searchInput ? searchInput.value.toLowerCase().trim() : '';
    const filterValue = filterSelect ? filterSelect.value : 'all';

    const studentCards = attendanceGrid.querySelectorAll('.student-attendance-card');
    let visibleCount = 0;
    let totalCount = studentCards.length;

    studentCards.forEach((card, index) => {
        const studentName = card.getAttribute('data-student-name') || '';
        const attendanceType = card.getAttribute('data-attendance-type') || 'present';

        // Apply search filter
        const matchesSearch = searchTerm === '' ||
            studentName.includes(searchTerm) ||
            studentName.replace(/\s+/g, '').includes(searchTerm.replace(/\s+/g, ''));

        // Apply attendance filter
        let matchesFilter = true;
        switch(filterValue) {
            case 'present':
                matchesFilter = attendanceType === 'present';
                break;
            case 'absent':
                matchesFilter = attendanceType === 'absent';
                break;
            case 'late':
                matchesFilter = attendanceType === 'late';
                break;
            case 'justified':
                matchesFilter = attendanceType === 'justified';
                break;
            case 'unmarked':
                // This would require checking if attendance was actually saved
                // For now, show all since we're in the marking phase
                matchesFilter = true;
                break;
            case 'all':
            default:
                matchesFilter = true;
                break;
        }

        // Show/hide card based on filters
        if (matchesSearch && matchesFilter) {
            card.style.display = '';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });

    // Update student count display
    actualizarConteoEstudiantes(sesionId, visibleCount, totalCount);
}

// Function to clear all filters for a specific class
function limpiarFiltros(sesionId) {
    const searchInput = document.getElementById(`search_${sesionId}`);
    const filterSelect = document.getElementById(`filter_${sesionId}`);

    if (searchInput) {
        searchInput.value = '';
    }

    if (filterSelect) {
        filterSelect.value = 'all';
    }

    // Re-apply filters (which will show all students)
    aplicarFiltros(sesionId);
}

// Function to update student count display
function actualizarConteoEstudiantes(sesionId, visibleCount = null, totalCount = null) {
    const countElement = document.getElementById(`student-count-${sesionId}`);

    if (!countElement) return;

    if (visibleCount === null || totalCount === null) {
        // Calculate counts if not provided
        const attendanceGrid = document.getElementById(`attendance-grid-${sesionId}`);
        if (attendanceGrid) {
            const cards = attendanceGrid.querySelectorAll('.student-attendance-card');
            totalCount = cards.length;
            visibleCount = Array.from(cards).filter(card => card.style.display !== 'none').length;
        } else {
            visibleCount = 0;
            totalCount = 0;
        }
    }

    if (visibleCount === totalCount) {
        countElement.textContent = `Mostrando ${totalCount} estudiante${totalCount !== 1 ? 's' : ''}`;
    } else {
        countElement.textContent = `Mostrando ${visibleCount} de ${totalCount} estudiante${totalCount !== 1 ? 's' : ''}`;
    }
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

// Function to change day (previous/next)
function cambiarDia(dias) {
    // Get current date from the display
    const currentDateDisplay = document.getElementById('current-date-display');
    if (!currentDateDisplay) return;

    // Parse current date (assuming format: "día, dd/mm/yyyy")
    const currentDateText = currentDateDisplay.textContent;
    const dateMatch = currentDateText.match(/(\d{1,2})\/(\d{1,2})\/(\d{4})/);

    if (dateMatch) {
        const day = parseInt(dateMatch[1]);
        const month = parseInt(dateMatch[2]) - 1; // JavaScript months are 0-based
        const year = parseInt(dateMatch[3]);

        const currentDate = new Date(year, month, day);
        currentDate.setDate(currentDate.getDate() + dias);

        // Format new date
        const newDay = currentDate.getDate();
        const newMonth = currentDate.getMonth() + 1;
        const newYear = currentDate.getFullYear();

        // Navigate to new date
        const newDateStr = `${newYear}-${String(newMonth).padStart(2, '0')}-${String(newDay).padStart(2, '0')}`;
        window.location.href = `{{ route('asistencia.docente.tomar-asistencia') }}?fecha=${newDateStr}`;
    }
}



// Function to load weekly calendar
function cargarCalendarioSemanal() {
    const container = document.getElementById('weekly-calendar-container');
    if (!container) return;

    // Get current date
    const currentDateDisplay = document.getElementById('current-date-display');
    let currentDate = new Date();

    if (currentDateDisplay) {
        const currentDateText = currentDateDisplay.textContent;
        const dateMatch = currentDateText.match(/(\d{1,2})\/(\d{1,2})\/(\d{4})/);

        if (dateMatch) {
            const day = parseInt(dateMatch[1]);
            const month = parseInt(dateMatch[2]) - 1;
            const year = parseInt(dateMatch[3]);
            currentDate = new Date(year, month, day);
        }
    }

    // Calculate start of week (Monday)
    const startOfWeek = new Date(currentDate);
    const dayOfWeek = startOfWeek.getDay();
    const diff = startOfWeek.getDate() - dayOfWeek + (dayOfWeek === 0 ? -6 : 1); // Adjust for Sunday
    startOfWeek.setDate(diff);

    // Create calendar HTML
    let calendarHtml = `
        <div class="calendar-header">
            <div class="calendar-header-day">Lun</div>
            <div class="calendar-header-day">Mar</div>
            <div class="calendar-header-day">Mié</div>
            <div class="calendar-header-day">Jue</div>
            <div class="calendar-header-day">Vie</div>
            <div class="calendar-header-day">Sáb</div>
            <div class="calendar-header-day">Dom</div>
        </div>
        <div class="weekly-calendar">
    `;

    // Generate 7 days
    for (let i = 0; i < 7; i++) {
        const dayDate = new Date(startOfWeek);
        dayDate.setDate(startOfWeek.getDate() + i);

        const isToday = dayDate.toDateString() === new Date().toDateString();
        const isSelected = dayDate.toDateString() === currentDate.toDateString();
        const dayNumber = dayDate.getDate();
        const dayName = dayDate.toLocaleDateString('es-ES', { weekday: 'short' });

        // Mock attendance data (in real implementation, this would come from server)
        const hasClasses = Math.random() > 0.3; // Random for demo
        const completedClasses = hasClasses ? Math.floor(Math.random() * 3) : 0;
        const pendingClasses = hasClasses ? Math.floor(Math.random() * 2) : 0;

        let dayClasses = '';
        if (hasClasses) {
            dayClasses = 'has-classes';
            if (completedClasses > pendingClasses) {
                dayClasses += ' completed';
            } else if (pendingClasses > 0) {
                dayClasses += ' pending';
            }
        }

        calendarHtml += `
            <div class="calendar-day ${isToday ? 'today' : ''} ${isSelected ? 'selected' : ''} ${dayClasses}"
                 data-date="${dayDate.getFullYear()}-${String(dayDate.getMonth() + 1).padStart(2, '0')}-${String(dayDate.getDate()).padStart(2, '0')}">
                <div class="day-number">${dayNumber}</div>
                <div class="day-name">${dayName}</div>
                ${hasClasses ? `
                    <div class="day-stats">
                        <div class="day-stat completed">
                            <div class="day-stat-number">${completedClasses}</div>
                            <div class="day-stat-label">OK</div>
                        </div>
                        <div class="day-stat pending">
                            <div class="day-stat-number">${pendingClasses}</div>
                            <div class="day-stat-label">PEN</div>
                        </div>
                    </div>
                ` : ''}
            </div>
        `;
    }

    calendarHtml += `
        </div>
        <div class="calendar-navigation">
            <div class="calendar-week-info">
                Semana del ${startOfWeek.getDate()}/${startOfWeek.getMonth() + 1} al ${new Date(startOfWeek.getTime() + 6 * 24 * 60 * 60 * 1000).getDate()}/${new Date(startOfWeek.getTime() + 6 * 24 * 60 * 60 * 1000).getMonth() + 1}
            </div>
            <div>
                <small class="text-muted">
                    <i class="fas fa-circle text-success"></i> Completado
                    <i class="fas fa-circle text-warning"></i> Pendiente
                    <i class="fas fa-circle text-info"></i> Con clases
                </small>
            </div>
        </div>
    `;

    container.innerHTML = calendarHtml;

    // Add click event listeners to calendar days
    const calendarDays = container.querySelectorAll('.calendar-day');
    console.log('=== ASIGNANDO EVENT LISTENERS ===');
    console.log('Found calendar days for event listeners:', calendarDays.length);
    calendarDays.forEach((day, index) => {
        const date = day.getAttribute('data-date');
        console.log(`Setting up click listener for day ${index + 1}, date: ${date}`);
        day.addEventListener('click', function() {
            console.log('=== DÍA DEL CALENDARIO CLICKEADO ===');
            console.log('Clicked day date:', date);
            if (date) {
                seleccionarDia(date);
            } else {
                console.error('ERROR: Clicked day has no data-date attribute');
            }
        });
        // Add visual feedback
        day.style.cursor = 'pointer';
        console.log(`Event listener assigned to day ${index + 1}`);
    });
    console.log('=== EVENT LISTENERS ASIGNADOS ===');
}

// Function to select a day from calendar
function seleccionarDia(fecha) {
    console.log('=== SELECCIONANDO FECHA ===');
    console.log('Fecha seleccionada:', fecha);

    // Update calendar visual selection immediately
    actualizarSeleccionCalendario(fecha);

    // Show loading state
    const attendanceSection = document.getElementById('collapseTomarAsistencia');
    if (attendanceSection) {
        attendanceSection.innerHTML = `
            <div class="text-center py-5">
                <i class="fas fa-spinner fa-spin fa-3x text-primary mb-3"></i>
                <h4>Cargando horario de clases...</h4>
                <p class="text-muted">Por favor espera mientras se carga el horario para la fecha seleccionada.</p>
            </div>
        `;
    }

    // Make AJAX request to get attendance data for selected date
    const url = `{{ route('asistencia.docente.tomar-asistencia') }}?fecha=${fecha}`;
    console.log('Making AJAX request to:', url);

    fetch(url, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.text();
    })
    .then(html => {
        console.log('HTML response received, parsing...');

        // Parse the HTML to extract the content we need to update
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');

        // Update the date display in the header
        const newDateDisplay = doc.querySelector('#current-date-display');
        if (newDateDisplay) {
            const currentDateDisplay = document.getElementById('current-date-display');
            if (currentDateDisplay) {
                currentDateDisplay.textContent = newDateDisplay.textContent;
            }
        }

        // Update the main date display in the navigation section
        const newMainDateDisplay = doc.querySelector('.text-center.mx-4 h4');
        if (newMainDateDisplay) {
            const currentMainDateDisplay = document.querySelector('.text-center.mx-4 h4');
            if (currentMainDateDisplay) {
                // Keep the click functionality and icon, just update the text
                const icon = currentMainDateDisplay.querySelector('i');
                const small = currentMainDateDisplay.querySelector('small');
                currentMainDateDisplay.innerHTML = '';
                currentMainDateDisplay.textContent = newMainDateDisplay.textContent;
                if (icon) currentMainDateDisplay.appendChild(icon);
                if (small) currentMainDateDisplay.appendChild(small);
            }
        }

        // Extract and update the attendance content
        const newAttendanceContent = doc.querySelector('#collapseTomarAsistencia');
        if (newAttendanceContent && attendanceSection) {
            attendanceSection.innerHTML = newAttendanceContent.innerHTML;

            // Re-initialize event listeners for the new content
            initializeAttendanceEvents();

            // Scroll to the attendance section
            setTimeout(() => {
                attendanceSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }, 300);
        }

        console.log('Horario de clases actualizado dinámicamente para fecha:', fecha);
    })
    .catch(error => {
        console.error('Error al cargar horario de clases:', error);
        if (attendanceSection) {
            attendanceSection.innerHTML = `
                <div class="alert alert-danger text-center">
                    <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                    <h5>Error al cargar el horario de clases</h5>
                    <p>Error: ${error.message}</p>
                    <button class="btn btn-primary" onclick="location.reload()">Recargar página</button>
                </div>
            `;
        }
    });
}

// Function to assign event listeners to calendar days
function asignarEventListenersCalendario() {
    console.log('=== ASIGNANDO EVENT LISTENERS AL CALENDARIO ===');

    const calendarDays = document.querySelectorAll('.calendar-day');
    console.log('Encontrados días del calendario:', calendarDays.length);

    calendarDays.forEach((day, index) => {
        const date = day.getAttribute('data-date');
        console.log(`Configurando listener para día ${index + 1}, fecha: ${date}`);

        // Remove existing listeners to avoid duplicates
        day.removeEventListener('click', day._calendarClickHandler);

        // Create new click handler
        day._calendarClickHandler = function() {
            console.log('=== CLICK EN DÍA DEL CALENDARIO ===');
            console.log('Fecha del día clickeado:', date);
            if (date) {
                seleccionarDia(date);
            } else {
                console.error('ERROR: Día clickeado no tiene data-date');
            }
        };

        // Add the event listener
        day.addEventListener('click', day._calendarClickHandler);
        day.style.cursor = 'pointer';

        console.log(`Listener asignado al día ${index + 1}`);
    });

    console.log('=== EVENT LISTENERS ASIGNADOS ===');
}

// Function to update calendar visual selection
function actualizarSeleccionCalendario(fechaSeleccionada) {
    console.log('Actualizando selección visual del calendario para fecha:', fechaSeleccionada);

    // Remove selected class from all calendar days
    const allCalendarDays = document.querySelectorAll('.calendar-day');
    allCalendarDays.forEach(day => {
        day.classList.remove('selected');
    });

    // Add selected class to the clicked day
    const selectedDay = document.querySelector(`.calendar-day[data-date="${fechaSeleccionada}"]`);
    if (selectedDay) {
        selectedDay.classList.add('selected');
        console.log('Día seleccionado visualmente:', fechaSeleccionada);
    } else {
        console.log('No se encontró el día en el calendario:', fechaSeleccionada);
    }
}

// Function to initialize event listeners for attendance section
function initializeAttendanceEvents() {
    // Re-initialize form submissions
    document.querySelectorAll('.attendance-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            guardarAsistenciaForm(this);
        });
    });

    // Re-initialize filters
    document.querySelectorAll('.attendance-filter').forEach(filter => {
        const sesionId = filter.getAttribute('data-sesion-id');
        filter.addEventListener('change', function() {
            aplicarFiltros(sesionId);
        });
    });

    // Re-initialize search
    document.querySelectorAll('.student-search').forEach(search => {
        const sesionId = search.getAttribute('data-sesion-id');
        search.addEventListener('input', function() {
            aplicarFiltros(sesionId);
        });
    });

    // Re-initialize attendance radio buttons
    document.querySelectorAll('.attendance-radio').forEach(radio => {
        radio.addEventListener('change', function() {
            const sesionId = this.getAttribute('data-sesion-id');
            const matriculaId = this.getAttribute('data-matricula-id');
            const attendanceType = this.value.toLowerCase();

            const row = this.closest('tr');
            row.setAttribute('data-attendance-type', attendanceType);

            const filterSelect = document.getElementById(`filter_${sesionId}`);
            if (filterSelect && filterSelect.value !== 'all') {
                aplicarFiltros(sesionId);
            }
        });
    });

    // Re-initialize student counts
    document.querySelectorAll('.attendance-grid').forEach(grid => {
        const sesionId = grid.id.replace('attendance-grid-', '');
        actualizarConteoEstudiantes(sesionId);
    });

    // Re-initialize attendance option click handlers
    document.querySelectorAll('.attendance-option').forEach(option => {
        option.addEventListener('click', function() {
            const radio = this.querySelector('input[type="radio"]');
            if (radio) {
                radio.checked = true;

                const sesionId = radio.getAttribute('data-sesion-id');
                updateAttendanceOptionVisuals(sesionId);

                radio.dispatchEvent(new Event('change'));
            }
        });
    });

    console.log('Eventos de asistencia re-inicializados');
}

// Function to change week (previous/next) with AJAX
function cambiarSemana(direccion) {
    console.log('=== CAMBIANDO SEMANA CON AJAX ===');
    console.log('Dirección:', direccion);

    // Get current date from the display
    const currentDateDisplay = document.getElementById('current-date-display');
    if (!currentDateDisplay) return;

    // Parse current date (assuming format: "día, dd/mm/yyyy")
    const currentDateText = currentDateDisplay.textContent;
    const dateMatch = currentDateText.match(/(\d{1,2})\/(\d{1,2})\/(\d{4})/);

    if (dateMatch) {
        const day = parseInt(dateMatch[1]);
        const month = parseInt(dateMatch[2]) - 1; // JavaScript months are 0-based
        const year = parseInt(dateMatch[3]);

        const currentDate = new Date(year, month, day);

        // Calculate start of current week (Monday)
        const dayOfWeek = currentDate.getDay();
        const diff = currentDate.getDate() - dayOfWeek + (dayOfWeek === 0 ? -6 : 1);
        const startOfCurrentWeek = new Date(currentDate);
        startOfCurrentWeek.setDate(diff);

        // Calculate new week start
        const newWeekStart = new Date(startOfCurrentWeek);
        newWeekStart.setDate(startOfCurrentWeek.getDate() + (direccion * 7));

        // Navigate to the first day of the new week
        const newDay = newWeekStart.getDate();
        const newMonth = newWeekStart.getMonth() + 1;
        const newYear = newWeekStart.getFullYear();

        const newDateStr = `${newYear}-${String(newMonth).padStart(2, '0')}-${String(newDay).padStart(2, '0')}`;
        console.log('Cambiando a nueva semana, fecha:', newDateStr);

        // Show loading state for calendar
        const calendarContainer = document.getElementById('weekly-calendar-container');
        if (calendarContainer) {
            calendarContainer.innerHTML = `
                <div class="text-center py-4">
                    <i class="fas fa-spinner fa-spin fa-2x text-primary mb-2"></i>
                    <p class="text-muted">Cargando nueva semana...</p>
                </div>
            `;
        }

        // Make AJAX request to get data for new week
        const url = `{{ route('asistencia.docente.tomar-asistencia') }}?fecha=${newDateStr}`;

        fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.text();
        })
        .then(html => {
            console.log('HTML response received for new week, parsing...');

            // Parse the HTML to extract the content we need to update
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');

            // Update the date display in the header
            const newDateDisplay = doc.querySelector('#current-date-display');
            if (newDateDisplay) {
                const currentDateDisplay = document.getElementById('current-date-display');
                if (currentDateDisplay) {
                    currentDateDisplay.textContent = newDateDisplay.textContent;
                }
            }

            // Update the main date display in the navigation section
            const newMainDateDisplay = doc.querySelector('.text-center.mx-4 h4');
            if (newMainDateDisplay) {
                const currentMainDateDisplay = document.querySelector('.text-center.mx-4 h4');
                if (currentMainDateDisplay) {
                    // Keep the click functionality and icon, just update the text
                    const icon = currentMainDateDisplay.querySelector('i');
                    const small = currentMainDateDisplay.querySelector('small');
                    currentMainDateDisplay.innerHTML = '';
                    currentMainDateDisplay.textContent = newMainDateDisplay.textContent;
                    if (icon) currentMainDateDisplay.appendChild(icon);
                    if (small) currentMainDateDisplay.appendChild(small);
                }
            }

            // Update the attendance content
            const newAttendanceContent = doc.querySelector('#collapseTomarAsistencia');
            if (newAttendanceContent) {
                const attendanceSection = document.getElementById('collapseTomarAsistencia');
                if (attendanceSection) {
                    attendanceSection.innerHTML = newAttendanceContent.innerHTML;

                    // Re-initialize event listeners for the new content
                    initializeAttendanceEvents();
                }
            }

            // Update the calendar content
            const newCalendarContent = doc.querySelector('#weekly-calendar-container');
            if (newCalendarContent && calendarContainer) {
                calendarContainer.innerHTML = newCalendarContent.innerHTML;

                // Re-assign event listeners to calendar days
                asignarEventListenersCalendario();

                // Automatically show the calendar since we're changing weeks
                const calendarSection = document.getElementById('weeklyCalendar');
                if (calendarSection && calendarSection.style.display === 'none') {
                    calendarSection.style.display = 'block';
                    calendarSection.style.visibility = 'visible';
                    calendarSection.style.opacity = '1';

                    // Update button icon
                    const toggleBtn = document.getElementById('toggleCalendarBtn');
                    const icon = toggleBtn ? toggleBtn.querySelector('.fas') : null;
                    if (icon) {
                        icon.classList.remove('fa-chevron-down');
                        icon.classList.add('fa-chevron-up');
                    }
                }
            }

            console.log('Semana cambiada dinámicamente a fecha:', newDateStr);
        })
        .catch(error => {
            console.error('Error al cambiar semana:', error);
            if (calendarContainer) {
                calendarContainer.innerHTML = `
                    <div class="alert alert-danger text-center">
                        <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                        <h6>Error al cargar la nueva semana</h6>
                        <p class="small">${error.message}</p>
                        <button class="btn btn-sm btn-primary" onclick="location.reload()">Recargar</button>
                    </div>
                `;
            }
        });
    } else {
        console.error('No se pudo parsear la fecha actual:', currentDateText);
    }
}
</script>
@endpush
@endsection</content>
            if (calendarContainer) {
                calendarContainer.innerHTML = `
                    <div class="alert alert-danger text-center">
                        <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                        <h6>Error al cargar la nueva semana</h6>
                        <p class="small">${error.message}</p>
                        <button class="btn btn-sm btn-primary" onclick="location.reload()">Recargar</button>
                    </div>
                `;
            }
        });
    } else {
        console.error('No se pudo parsear la fecha actual:', currentDateText);
    }
}
</script>
@endpush
@endsection</content>
