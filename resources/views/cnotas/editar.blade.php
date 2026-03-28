@extends('cplantilla.bprincipal')
@section('titulo','Registro de Notas')
@section('contenidoplantilla')
@php
    $useCompetencies = isset($competencias) && $competencias->count() > 0;
@endphp
<style>
        .estilo-info {
            margin-bottom: 0px;
            font-family: "Quicksand", sans-serif;
            font-weight: 700;
            font-size: clamp(0.9rem, 2.0vw, 0.9rem) !important;

        }
        
        .th-competencia {
            cursor: help;
            position: relative;
            min-width: 60px;
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

<div class="container-fluid margen-movil-2">
    <div class="row mt-4 mr-1 ml-1">
        <div class="col-md-12">
            <div class="card">
                <!-- Cabecera de la tarjeta con el nombre de la asignatura y detalles del curso -->
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center" style="background-color: #1e5981 !important;">
                    <h4 class="mb-0">
                        <i class="fas fa-list-alt"></i> Registro de Notas: {{ $asignatura->nombre }}
                    </h4>
                    <span>{{ $curso->grado->nombre }} {{ $curso->grado->nivel->nombre }} "{{ $curso->seccion->nombre }}" - {{ $curso->anoLectivo->nombre }}</span>
                </div>
                <div class="card-body">
                    <!-- Información del período actual de evaluación -->
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Período actual de evaluación: <strong>{{ $periodoActual->nombre }}</strong> ({{ $periodoActual->fecha_inicio->format('d/m/Y') }} - {{ $periodoActual->fecha_fin->format('d/m/Y') }})
                        @if($useCompetencies)
                            <div class="mt-2 text-danger">
                                <small><strong><i class="fas fa-exclamation-triangle"></i> Modo Competencias Activo:</strong> Califique cada competencia y el logro final del periodo usando la escala literal (AD, A, B, C).</small>
                            </div>
                        @else
                            <div class="mt-2">
                                <small><strong><i class="fas fa-calculator"></i> Modo Numérico:</strong> Califique usando la escala vigesimal (0-20).</small>
                            </div>
                        @endif
                    </div>

                    <!-- Formulario para guardar las notas -->
                    <form action="{{ route('notas.actualizar') }}" method="POST" id="formNotas">
                        @csrf
                        <!-- IDs ocultos para identificar el curso y el período -->
                        <input type="hidden" name="curso_asignatura_id" value="{{ $cursoAsignatura->curso_asignatura_id }}">
                        <input type="hidden" name="periodo_id" value="{{ $periodoActual->periodo_id }}">
                        
                        <input type="hidden" id="use_competencies" value="{{ $useCompetencies ? '1' : '0' }}">

                        <div class="table-responsive">
                            <table class="table table-striped table-hover border table-bordered">
                                <thead class="bg-light align-middle text-center">
                                    <tr>
                                        <th rowspan="2">N° Matrícula</th>
                                        <th rowspan="2" style="min-width: 200px;">Estudiante</th>
                                        @foreach($periodos as $periodo)
                                            @php
                                                $esActual = $periodo->periodo_id == $periodoActual->periodo_id;
                                                $colspan = ($esActual && $useCompetencies) ? ($competencias->count() + 1) : 1;
                                            @endphp
                                            <th colspan="{{ $colspan }}" class="{{ $esActual ? 'bg-warning text-dark' : '' }}">
                                                {{ $periodo->nombre }}
                                            </th>
                                        @endforeach
                                        <th rowspan="2">Promedio</th>
                                        <th rowspan="2">Observaciones</th>
                                    </tr>
                                    @if($useCompetencies)
                                    <tr>
                                        @foreach($periodos as $periodo)
                                            @if($periodo->periodo_id == $periodoActual->periodo_id)
                                                @foreach($competencias as $index => $comp)
                                                    <th class="th-competencia bg-warning text-dark small" title="{{ $comp->descripcion }}">
                                                        C{{ $index + 1 }}
                                                    </th>
                                                @endforeach
                                                <th class="bg-warning text-dark small">Logro</th>
                                            @else
                                                <th class="small text-muted">Nota</th>
                                            @endif
                                        @endforeach
                                    </tr>
                                    @endif
                                </thead>
                                <tbody>
                                    @forelse($notasEstudiantes as $index => $item)
                                        <tr>
                                            <td>{{ $item['numero_matricula'] }}</td>
                                            <td>{{ $item['estudiante'] }}</td>

                                            @foreach($item['notas_periodos'] as $notaPeriodo)
                                                @php
                                                    $esEditable = $notaPeriodo['editable'];
                                                @endphp

                                                @if($useCompetencies && $esEditable)
                                                    <!-- COMPETENCIES INPUT MODE -->
                                                    <input type="hidden" name="notas[{{ $index }}][matricula_id]" value="{{ $item['matricula_id'] }}">
                                                    
                                                    @foreach($competencias as $comp)
                                                        <td class="bg-light-warning p-1">
                                                            <select name="notas[{{ $index }}][competencias][{{ $comp->competencia_id }}]" 
                                                                    class="form-control form-control-sm select-nota" style="min-width: 50px;">
                                                                <option value="">-</option>
                                                                @foreach(['AD', 'A', 'B', 'C'] as $letra)
                                                                    <option value="{{ $letra }}" 
                                                                        {{ (isset($notaPeriodo['competencias'][$comp->competencia_id]) && $notaPeriodo['competencias'][$comp->competencia_id] == $letra) ? 'selected' : '' }}>
                                                                        {{ $letra }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                    @endforeach
                                                    
                                                    <td class="bg-light-warning p-1">
                                                        <select name="notas[{{ $index }}][calificacion]" 
                                                                class="form-control form-control-sm font-weight-bold select-nota" style="min-width: 50px;">
                                                            <option value="">-</option>
                                                            @foreach(['AD', 'A', 'B', 'C'] as $letra)
                                                                <option value="{{ $letra }}" 
                                                                    {{ ($notaPeriodo['nota_letra'] == $letra || $notaPeriodo['nota'] == $letra) ? 'selected' : '' }}>
                                                                    {{ $letra }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>

                                                @elseif($useCompetencies && !$esEditable)
                                                     <td class="text-center font-weight-bold">
                                                        {{ $notaPeriodo['nota_letra'] ?? ($notaPeriodo['nota'] ?? '-') }}
                                                     </td>

                                                @elseif(!$useCompetencies && $esEditable)
                                                    <td class="bg-light-warning">
                                                        <input type="hidden" name="notas[{{ $index }}][matricula_id]" value="{{ $item['matricula_id'] }}">
                                                        <input type="number"
                                                               name="notas[{{ $index }}][calificacion]"
                                                               class="form-control form-control-sm input-nota-num"
                                                               min="0"
                                                               max="20"
                                                               step="0.01"
                                                               value="{{ $notaPeriodo['nota'] }}"
                                                               placeholder="0-20">
                                                    </td>
                                                @else
                                                    <td class="text-center">
                                                        {{ $notaPeriodo['nota'] ?? '-' }}
                                                    </td>
                                                @endif
                                            @endforeach

                                            <td class="fw-bold text-center">{{ $item['promedio'] ?? '-' }}</td>
                                            <td>
                                                @if($periodoActual)
                                                    <input type="text"
                                                           name="notas[{{ $index }}][observaciones]"
                                                           class="form-control form-control-sm"
                                                           maxlength="255"
                                                           placeholder="Observaciones"
                                                           value="{{ end($item['notas_periodos'])['observaciones'] ?? '' }}">
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center">No hay estudiantes matriculados en este curso</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-4 gap-4">
                            <a href="{{ route('notas.inicio') }}" class="btn btn-secondary me-2 mr-4 w-100"  style="font-weight:bold">
                                <i class="fas fa-arrow-left mx-2"></i> Regresar
                            </a>
                            <button type="button" id="btnGuardarNotas" class="btn btn-success w-100"  style="font-weight:bold">
                                <i class="fas fa-save mx-2"></i> Guardar Calificaciones
                            </button>
                        </div>
                        
                        <div class="mt-3">
                            <small class="text-muted">
                                @if($useCompetencies)
                                    <strong>Leyenda:</strong> C1, C2... = Competencias de la asignatura. AD = Logro Destacado, A = Logro Esperado, B = En Proceso, C = En Inicio.
                                @endif
                            </small>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-light-warning {
        background-color: rgba(255, 193, 7, 0.1);
    }
    .form-control-sm {
        height: 30px;
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
</style>

@endsection

@section('scripts')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const useCompetencies = document.getElementById('use_competencies').value === '1';

        @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: '{{ session('success') }}',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
        @endif

        @if(session('warning'))
        Swal.fire({
            icon: 'warning',
            title: 'Atención',
            text: '{{ session('warning') }}',
            confirmButtonColor: '#f39c12',
            confirmButtonText: 'Entendido'
        });
        @endif

        @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: '¡Error!',
            text: '{{ session('error') }}',
            confirmButtonColor: '#d33',
            confirmButtonText: 'Entendido'
        });
        @endif
        
        @if(session('info'))
        Swal.fire({
            icon: 'info',
            title: 'Información',
            text: '{{ session('info') }}',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Aceptar'
        });
        @endif

        @if(!session()->has('edicion_notas'))
            Swal.fire({
                icon: 'error',
                title: 'Acceso incorrecto',
                text: 'Por favor, acceda a través del formulario',
                confirmButtonColor: '#d33',
                allowOutsideClick: false
            }).then((result) => {
                window.location.href = '{{ route("notas.inicio") }}';
            });
        @endif

        if (!useCompetencies) {
            const notasInputs = document.querySelectorAll('.input-nota-num');
            notasInputs.forEach(input => {
                input.addEventListener('input', function() { validarNota(this); });
                input.addEventListener('blur', function() { validarNota(this, true); });
            });
        }

        function validarNota(input, showMessage = false) {
            const valor = parseFloat(input.value);
            if (input.value === '') {
                input.classList.remove('is-invalid');
                return true;
            }
            if (isNaN(valor) || valor < 0 || valor > 20) {
                input.classList.add('is-invalid');
                if (showMessage) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Valor incorrecto',
                        text: 'La nota debe ser 0-20',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                }
                return false;
            } else {
                input.classList.remove('is-invalid');
                return true;
            }
        }

        document.getElementById('btnGuardarNotas').addEventListener('click', function() {
            let formValido = true;
            
            if (!useCompetencies) {
                const notasInputs = document.querySelectorAll('.input-nota-num');
                notasInputs.forEach(input => {
                    if (!validarNota(input, false)) formValido = false;
                });
            }

            if (!formValido) {
                Swal.fire({
                    icon: 'error',
                    title: 'Errores',
                    text: 'Corrija los valores incorrectos.',
                });
                return;
            }

            Swal.fire({
                title: '¿Guardar calificaciones?',
                text: 'Se registrarán las notas ingresadas.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                confirmButtonText: 'Sí, guardar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('formNotas').submit();
                }
            });
        });
    });
</script>
@endsection
