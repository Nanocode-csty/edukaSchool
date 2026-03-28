@csrf

@php
    $feriadosActuales = \App\Models\Feriado::activos()
        ->whereYear('fecha', date('Y'))
        ->orderBy('fecha')
        ->get();
@endphp

{{-- Debug: {{ $feriadosActuales->count() }} feriados encontrados --}}
@if($feriadosActuales->count() > 0)
<div class="row mb-3">
    <div class="col-md-12">
        <div class="alert alert-info" style="border: 2px solid #17a2b8;">
            <h6><i class="fas fa-calendar-times"></i> Feriados del Año {{ date('Y') }} ({{ $feriadosActuales->count() }} feriados)</h6>
            <div class="row">
                @foreach($feriadosActuales as $feriado)
                <div class="col-md-3 mb-2">
                    <small class="text-muted">
                        <strong>{{ $feriado->fecha->format('d/m') }}:</strong> {{ $feriado->nombre }}
                        @if($feriado->recuperable)
                            <span class="badge badge-warning badge-sm">Recuperable</span>
                        @endif
                    </small>
                </div>
                @endforeach
            </div>
            <small class="text-muted mt-2 d-block">
                <i class="fas fa-info-circle"></i> No se programan clases en feriados ni fines de semana. Los días laborables son de Lunes a Sábado.
            </small>
        </div>
    </div>
</div>
@endif

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="curso_id">Curso *</label>
            <select name="curso_id" id="curso_id" class="form-control @error('curso_id') is-invalid @enderror" required>
                <option value="">Seleccione un curso</option>
                @foreach($cursos as $curso)
                    <option value="{{ $curso->curso_id }}" {{ (old('curso_id') == $curso->curso_id) || (isset($item) && $item->curso_id == $curso->curso_id && !old('curso_id')) ? 'selected' : '' }}>
                        {{ $curso->grado->nombre ?? 'Grado' }} - {{ $curso->seccion->nombre ?? 'Sección' }}
                    </option>
                @endforeach
            </select>
            @error('curso_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="asignatura_id">Asignatura *</label>
            <select name="asignatura_id" id="asignatura_id" class="form-control @error('asignatura_id') is-invalid @enderror" required>
                <option value="">Seleccione una asignatura</option>
                @foreach($asignaturas as $asignatura)
                    <option value="{{ $asignatura->asignatura_id }}" {{ (old('asignatura_id') == $asignatura->asignatura_id) || (isset($item) && $item->asignatura_id == $asignatura->asignatura_id && !old('asignatura_id')) ? 'selected' : '' }}>
                        {{ $asignatura->nombre }}
                    </option>
                @endforeach
            </select>
            @error('asignatura_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="profesor_id">Profesor *</label>
            <select name="profesor_id" id="profesor_id" class="form-control @error('profesor_id') is-invalid @enderror" required>
                <option value="">Seleccione un profesor</option>
                @foreach($profesores as $profesor)
                    <option value="{{ $profesor->profesor_id }}" {{ (old('profesor_id') == $profesor->profesor_id) || (isset($item) && $item->profesor_id == $profesor->profesor_id && !old('profesor_id')) ? 'selected' : '' }}>
                        {{ $profesor->nombres }} {{ $profesor->apellidos }}
                    </option>
                @endforeach
            </select>
            @error('profesor_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="horas_semanales">Horas Semanales *</label>
            <input type="number" name="horas_semanales" id="horas_semanales"
                   class="form-control @error('horas_semanales') is-invalid @enderror"
                   value="{{ isset($item) ? $item->horas_semanales : old('horas_semanales') }}"
                   min="1" max="24" required>
            @error('horas_semanales')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="dia_semana">Día de la Semana *</label>
            <select name="dia_semana" id="dia_semana" class="form-control @error('dia_semana') is-invalid @enderror" required>
                <option value="">Seleccione un día</option>
                @php
                    $diasMap = [
                        'Lunes' => 1, 'Martes' => 2, 'Miércoles' => 3, 'Miercoles' => 3,
                        'Jueves' => 4, 'Viernes' => 5, 'Sábado' => 6, 'Sabado' => 6, 'Domingo' => 7
                    ];
                    $feriadosPorDia = [];
                    $feriadosActuales = \App\Models\Feriado::activos()->whereYear('fecha', date('Y'))->get();

                    foreach($feriadosActuales as $feriado) {
                        $diaNumero = $feriado->fecha->dayOfWeekIso;
                        $diaNombre = array_search($diaNumero, $diasMap);
                        if ($diaNombre) {
                            if (!isset($feriadosPorDia[$diaNombre])) {
                                $feriadosPorDia[$diaNombre] = collect();
                            }
                            $feriadosPorDia[$diaNombre]->push($feriado);
                        }
                    }
                @endphp
                @foreach($dias as $dia)
                    @php
                        $tieneFeriado = isset($feriadosPorDia[$dia]) && $feriadosPorDia[$dia]->count() > 0;
                        $feriadoInfo = $tieneFeriado ? $feriadosPorDia[$dia]->first() : null;
                    @endphp
                    <option value="{{ $dia }}"
                            {{ (old('dia_semana') == $dia) || (isset($item) && $item->dia_semana == $dia && !old('dia_semana')) ? 'selected' : '' }}
                            {{ $tieneFeriado ? 'style="color: #dc3545; font-weight: bold;"' : '' }}>
                        {{ $dia }} {{ $tieneFeriado ? '(FERIADO: ' . $feriadoInfo->nombre . ')' : '' }}
                    </option>
                @endforeach
            </select>
            @error('dia_semana')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="aula_id">Aula *</label>
            <select name="aula_id" id="aula_id" class="form-control @error('aula_id') is-invalid @enderror" required>
                <option value="">Seleccione un aula</option>
                @foreach($aulas as $aula)
                    <option value="{{ $aula->aula_id }}" {{ (old('aula_id') == $aula->aula_id) || (isset($item) && $item->aula_id == $aula->aula_id && !old('aula_id')) ? 'selected' : '' }}>
                        {{ $aula->nombre }}
                    </option>
                @endforeach
            </select>
            @error('aula_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="hora_inicio">Hora Inicio *</label>
            <input type="time" name="hora_inicio" id="hora_inicio"
                   class="form-control @error('hora_inicio') is-invalid @enderror"
                   value="{{ isset($item) ? $item->hora_inicio : old('hora_inicio') }}"
                   required>
            @error('hora_inicio')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="hora_fin">Hora Fin *</label>
            <input type="time" name="hora_fin" id="hora_fin"
                   class="form-control @error('hora_fin') is-invalid @enderror"
                   value="{{ isset($item) ? $item->hora_fin : old('hora_fin') }}"
                   required>
            @error('hora_fin')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <button type="submit" class="btn btn-primary">
            {{ isset($item) ? 'Actualizar' : 'Crear' }} Asignación
        </button>
        <a href="{{ route('cursoasignatura.index') }}" class="btn btn-secondary">Cancelar</a>
    </div>
</div>
