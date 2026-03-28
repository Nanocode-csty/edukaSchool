@extends('cplantilla.bprincipal')
@section('titulo', 'Crear Asignación de Curso-Asignatura')
@section('contenidoplantilla')
    <style>
        .estilo-info {
            margin-bottom: 0px;
            font-family: "Quicksand", sans-serif;
            font-weight: 700;
            font-size: clamp(0.9rem, 2.0vw, 0.9rem) !important;

        }

        @media (max-width: 576px) {
            .margen-movil {
                margin-left: -29px !important;
                margin-right: -29px !important;
            }

            .margen-movil-2 {
                margin: 0 !important;
                padding: 0 !important;
            }
        }
    </style>

    <div class="container-fluid estilo-info margen-movil-2">
        <div class="row mt-4 ml-1 mr-1">
            <div class="col-12 mb-3">
                <div class="box_block">
                    <button style="background: #0A8CB3 !important; border:none"
                        class="btn btn-primary btn-block text-left rounded-0 btn_header header_6 estilo-info" type="button"
                        data-toggle="collapse" data-target="#collapseCrearAsignacion" aria-expanded="true"
                        aria-controls="collapseCrearAsignacion">
                        <i class="fas fa-plus-circle"></i>&nbsp;Ficha de Asignación
                        <div class="float-right"><i class="fas fa-chevron-down"></i></div>
                    </button>
                </div>
                <div class="collapse show" id="collapseCrearAsignacion">
                    <div class="card card-body rounded-0 border-0 pt-0"
                        style="padding-left:0.966666666rem;padding-right:0.9033333333333333rem;">
                        <div class="row margen-movil" style="padding:20px;">
                            <div class="col-12">
                                @if (session('error'))
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        {{ session('error') }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif

                                @if (session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <i class="fas fa-check-circle"></i>
                                        {{ session('success') }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif

                                <div class="card" style="border: none">
                                    <div
                                        style="background: #E0F7FA; color: #0A8CB3; font-weight: bold; border: 2px solid #86D2E3; border-bottom: 2px solid #86D2E3; padding: 6px 20px; border-radius:4px 4px 0px 0px;">
                                        <i class="fas fa-graduation-cap mr-2"></i>
                                        Información Académica
                                    </div>

                                    <div class="card-body"
                                        style="border: 2px solid #86D2E3; border-top: none; border-radius: 0px 0px 4px 4px !important;">
                                        <form id="formularioAsignacion" method="POST"
                                            action="{{ route('cursoasignatura.store') }}" autocomplete="off">
                                            @csrf
                                            <div class="row form-group">
                                                <label class="col-md-2 col-form-label">Curso <span
                                                        style="color: #FF5A6A">(*)</span></label>
                                                <div class="col-md-10">
                                                    <select name="curso_id" id="curso_id"
                                                        class="form-control @error('curso_id') is-invalid @enderror"
                                                        required>
                                                        <option value="">Seleccione un curso</option>
                                                        @foreach ($cursos as $curso)
                                                            <option value="{{ $curso->curso_id }}"
                                                                {{ old('curso_id') == $curso->curso_id ? 'selected' : '' }}>
                                                                {{ $curso->grado->nombre ?? 'Grado' }} -
                                                                {{ $curso->seccion->nombre ?? 'Sección' }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('curso_id')
                                                        <div class="invalid-feedback d-block text-start">{{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="row form-group">
                                                <label class="col-md-2 col-form-label">Asignatura <span
                                                        style="color: #FF5A6A">(*)</span></label>
                                                <div class="col-md-10">
                                                    <select name="asignatura_id" id="asignatura_id"
                                                        class="form-control @error('asignatura_id') is-invalid @enderror"
                                                        required>
                                                        <option value="">Seleccione una asignatura</option>
                                                        @foreach ($asignaturas as $asignatura)
                                                            <option value="{{ $asignatura->asignatura_id }}"
                                                                {{ old('asignatura_id') == $asignatura->asignatura_id ? 'selected' : '' }}>
                                                                {{ $asignatura->nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('asignatura_id')
                                                        <div class="invalid-feedback d-block text-start">{{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="row form-group">
                                                <label class="col-md-2 col-form-label">Profesor <span
                                                        style="color: #FF5A6A">(*)</span></label>
                                                <div class="col-md-10">
                                                    <select name="profesor_id" id="profesor_id"
                                                        class="form-control @error('profesor_id') is-invalid @enderror"
                                                        required>
                                                        <option value="">Seleccione un profesor</option>
                                                        @foreach ($profesores as $profesor)
                                                            <option value="{{ $profesor->profesor_id }}"
                                                                {{ old('profesor_id') == $profesor->profesor_id ? 'selected' : '' }}>
                                                                {{ $profesor->nombres }} {{ $profesor->apellidos }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('profesor_id')
                                                        <div class="invalid-feedback d-block text-start">{{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="row form-group">
                                                <label class="col-md-2 col-form-label">Horas Semanales <span
                                                        style="color: #FF5A6A">(*)</span></label>
                                                <div class="col-md-4">
                                                    <input type="number" name="horas_semanales" id="horas_semanales"
                                                        class="form-control @error('horas_semanales') is-invalid @enderror"
                                                        value="{{ old('horas_semanales') }}" min="1" max="24"
                                                        required>
                                                    @error('horas_semanales')
                                                        <div class="invalid-feedback d-block text-start">{{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>

                                    </div>
                                </div>

                                <div class="card mt-4" style="border: none">
                                    <div
                                        style="background: #E0F7FA; color: #0A8CB3; font-weight: bold; border: 2px solid #86D2E3; border-bottom: 2px solid #86D2E3; padding: 6px 20px; border-radius:4px 4px 0px 0px;">
                                        <i class="fas fa-calendar-alt mr-2"></i>
                                        Programación de Clases
                                    </div>
                                    <div class="card-body"
                                        style="border: 2px solid #86D2E3; border-top: none; border-radius: 0px 0px 4px 4px !important;">

                                        <div class="row form-group">
                                            <label class="col-md-2 col-form-label">Día de la Semana <span
                                                    style="color: #FF5A6A">(*)</span></label>
                                            <div class="col-md-10">
                                                <select name="dia_semana" id="dia_semana"
                                                    class="form-control @error('dia_semana') is-invalid @enderror" required>
                                                    <option value="">Seleccione un día</option>
                                                    @php
                                                        $dias = [
                                                            'Lunes',
                                                            'Martes',
                                                            'Miércoles',
                                                            'Jueves',
                                                            'Viernes',
                                                            'Sábado',
                                                        ];
                                                    @endphp
                                                    @foreach ($dias as $dia)
                                                        <option value="{{ $dia }}"
                                                            {{ old('dia_semana') == $dia ? 'selected' : '' }}>
                                                            {{ $dia }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('dia_semana')
                                                    <div class="invalid-feedback d-block text-start">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Mostrar feriados del año -->
                                        @if(isset($feriados) && $feriados->count() > 0)
                                        <div class="row form-group">
                                            <label class="col-md-2 col-form-label">Feriados {{ date('Y') }}</label>
                                            <div class="col-md-10">
                                                <div class="alert alert-warning">
                                                    <strong><i class="fas fa-exclamation-triangle"></i> Feriados registrados:</strong>
                                                    <ul class="mb-0 mt-2">
                                                        @foreach($feriados as $feriado)
                                                            <li>
                                                                <strong>{{ $feriado['dia_semana'] }}</strong> -
                                                                {{ \Carbon\Carbon::parse($feriado['fecha'])->format('d/m/Y') }}:
                                                                {{ $feriado['nombre'] }}
                                                                @if($feriado['tipo'])
                                                                    <small class="text-muted">({{ $feriado['tipo'] }})</small>
                                                                @endif
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                    <small class="text-muted">
                                                        <i class="fas fa-info-circle"></i>
                                                        No se pueden programar clases en días feriados.
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                        @endif

                                        <div class="row form-group">
                                            <label class="col-md-2 col-form-label">Aula <span
                                                    style="color: #FF5A6A">(*)</span></label>
                                            <div class="col-md-10">
                                                <select name="aula_id" id="aula_id"
                                                    class="form-control @error('aula_id') is-invalid @enderror" required>
                                                    <option value="">Seleccione un aula</option>
                                                    @foreach ($aulas as $aula)
                                                        <option value="{{ $aula->aula_id }}"
                                                            {{ old('aula_id') == $aula->aula_id ? 'selected' : '' }}>
                                                            {{ $aula->nombre }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('aula_id')
                                                    <div class="invalid-feedback d-block text-start">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row form-group">
                                            <label class="col-md-2 col-form-label">Hora Inicio <span
                                                    style="color: #FF5A6A">(*)</span></label>
                                            <div class="col-md-4">
                                                <input type="time" name="hora_inicio" id="hora_inicio"
                                                    class="form-control @error('hora_inicio') is-invalid @enderror"
                                                    value="{{ old('hora_inicio') }}" required>
                                                @error('hora_inicio')
                                                    <div class="invalid-feedback d-block text-start">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <label class="col-md-2 col-form-label">Hora Fin <span
                                                    style="color: #FF5A6A">(*)</span></label>
                                            <div class="col-md-4">
                                                <input type="time" name="hora_fin" id="hora_fin"
                                                    class="form-control @error('hora_fin') is-invalid @enderror"
                                                    value="{{ old('hora_fin') }}" required>
                                                @error('hora_fin')
                                                    <div class="invalid-feedback d-block text-start">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="d-flex justify-content-center mt-4">
                                    <button type="submit" class="btn btn-primary btn-block"
                                        style="background: #FF3F3F !important; border: none; font: bold !important">
                                        <span style="font:bold">Crear Asignación</span>
                                    </button>
                                    <a href="{{ route('cursoasignatura.index') }}"
                                        class="btn btn-secondary ml-2">Cancelar</a>
                                </div>

                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .form-control {
            border: 1px solid #DAA520;
        }
    </style>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Validación básica del formulario
            const form = document.getElementById('formularioAsignacion');

            form.addEventListener('submit', function(e) {
                const requiredFields = form.querySelectorAll('[required]');
                let isValid = true;

                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        field.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        field.classList.remove('is-invalid');
                        field.classList.add('is-valid');
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    alert('Por favor, complete todos los campos obligatorios.');
                }
            });

            // Validación de horas
            const horaInicio = document.getElementById('hora_inicio');
            const horaFin = document.getElementById('hora_fin');

            function validarHoras() {
                if (horaInicio.value && horaFin.value) {
                    if (horaInicio.value >= horaFin.value) {
                        horaFin.setCustomValidity('La hora de fin debe ser posterior a la hora de inicio');
                        horaFin.classList.add('is-invalid');
                    } else {
                        horaFin.setCustomValidity('');
                        horaFin.classList.remove('is-invalid');
                        horaFin.classList.add('is-valid');
                    }
                }
            }

            horaInicio.addEventListener('change', validarHoras);
            horaFin.addEventListener('change', validarHoras);
        });
    </script>
@endsection
