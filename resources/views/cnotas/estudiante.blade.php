@extends('cplantilla.bprincipal')
@section('titulo', 'Calificaciones del Estudiante')
@section('contenidoplantilla')
<x-breadcrumb :module="'asistencia'" :section="'representante-estudiante'" />
<div class="container-fluid" id="contenido-principal">
    <div class="row mt-4 ml-1 mr-1">
        <div class="col-12">
            <div class="box_block">
                <!-- Collapse header -->
                <button class="btn btn-block text-left rounded-0 btn_header header_6" type="button" data-toggle="collapse" data-target="#collapseCalificacionesEstudiante" aria-expanded="true" aria-controls="collapseCalificacionesEstudiante" style="background: #0A8CB3 !important; font-weight: bold; color: white;">
                    <i class="fas fa-chart-bar m-1"></i>&nbsp;Calificaciones del Estudiante
                    <div class="float-right"><i class="fas fa-chevron-down"></i></div>
                </button>
                <!-- Descripción (siempre visible, dentro del bloque) -->
                <div class="card-body rounded-0 border-0 pt-3 pb-3" style="background: #f3f3f3; border-bottom: 1px solid rgba(0,0,0,.125); border-top: 1px solid rgba(0,0,0,.125); color: #F59D24;">
                    <div class="row justify-content-center align-items-center flex-wrap">
                        <div class="col-auto text-center mb-2 mb-md-0" style="min-width:48px;">
                            <i class="fas fa-user-graduate fa-2x"></i>
                        </div>
                        <div class="col px-2" style="text-align:justify;">
                            <p style="margin-bottom: 0px; font-family: 'Quicksand', sans-serif; font-weight: 600; color: #004a92;">
                                Consulta detallada de las calificaciones de {{ $estudiante->nombres }} {{ $estudiante->apellidos }}. Se muestran las notas por período y el promedio final de cada asignatura.
                            </p>
                            <p style="font-family: 'Quicksand', sans-serif; font-weight: 600; color: #004a92;">
                                Utiliza los filtros y opciones disponibles para navegar entre diferentes vistas y obtener reportes detallados.
                            </p>
                        </div>
                    </div>
                </div>
                <!-- Collapse: contenido de calificaciones -->
                <div class="collapse show" id="collapseCalificacionesEstudiante">
                    <div class="card card-body rounded-0 border-0 pt-0 pb-2" style="background: transparent;">
                        <!-- Información del estudiante -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <!-- Avatar con iniciales del estudiante -->
                                    <div class="avatar-lg mr-3"
                                        style="width: 80px; height: 80px; border-radius: 50%; background-color: #0A8CB3; color: white; display: flex; align-items: center; justify-content: center; font-size: 36px;">
                                        <!-- Extrae la primera letra del nombre y apellido para formar las iniciales -->
                                        {{ substr($estudiante->nombres, 0, 1) }}{{ substr($estudiante->apellidos, 0, 1) }}
                                    </div>
                                    <!-- Información personal del estudiante -->
                                    <div class="ml-3">
                                        <h4 class="text-primary mb-1" style="color:#0a7c9e !important; font-weight:bold">{{ $estudiante->apellidos }},
                                            {{ $estudiante->nombres }}</h4>
                                        <p class="mb-0"><strong>N.° de DNI:</strong> {{ $estudiante->dni }}</p>
                                        <p class="mb-0"><strong>N.° de Matrícula:</strong>
                                            {{ $matriculaActual->numero_matricula }}</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Información académica del estudiante: grado, sección y año lectivo -->
                            <div class="col-md-6 text-md-end ">
                                <div class="mb-1"><strong>Grado y Sección:</strong> {{ $curso->grado->nombre }}
                                    {{ $curso->grado->nivel->nombre }} "{{ $curso->seccion->nombre }}"</div>
                                <div class="mb-1"><strong>Año Lectivo:</strong> {{ $curso->anoLectivo->nombre }}</div>
                                <div><strong>Estado:</strong>
                                    <span
                                        class="badge {{ $matriculaActual->estado == 'Matriculado' ? 'bg-success' : 'bg-warning' }}" style="border: none; color:#fff; font-weight:bold">
                                        {{ $matriculaActual->estado }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Mensaje informativo sobre las calificaciones -->
                        <div class="alert alert-info d-print-none">
                            <i class="fas fa-info-circle"></i> Se muestran las calificaciones del año lectivo actual. Los
                            bimestres sin calificación aparecen como "-". Pase el mouse sobre las notas para ver
                            observaciones.
                        </div>

                        <!-- Tabla de calificaciones -->
                        <div class="table-responsive" id="imprimir">
                        <!-- Cabecera solo para impresión -->
                        <div class="d-none d-print-block mb-4">
                             <div class="text-center mb-3">
                                 <h3 style="font-weight: bold; margin: 0;">BOLETA DE NOTAS - {{ $curso->anoLectivo->nombre }}</h3>
                                 <h4 style="margin: 5px 0;">{{ $curso->grado->nombre }} {{ $curso->grado->nivel->nombre }} "{{ $curso->seccion->nombre }}"</h4>
                             </div>
                             <div class="row mb-3" style="border-bottom: 2px solid #ccc; padding-bottom: 10px;">
                                 <div class="col-8">
                                     <strong>Estudiante:</strong> {{ $estudiante->apellidos }}, {{ $estudiante->nombres }}<br>
                                     <strong>DNI:</strong> {{ $estudiante->dni }}<br>
                                     <strong>Matrícula:</strong> {{ $matriculaActual->numero_matricula }}
                                 </div>
                                 <div class="col-4 text-end">
                                     <strong>Fecha de Emisión:</strong> {{ date('d/m/Y') }}
                                 </div>
                             </div>
                        </div>

                            <table class="table table-bordered table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th style="width: 30%">Asignatura</th>
                                        <!-- Genera dinámicamente una columna para cada período académico (bimestres) -->
                                        @foreach ($periodos as $periodo)
                                            <th class="text-center">
                                                {{ $periodo->nombre }}
                                            </th>
                                        @endforeach
                                        <th class="text-center bg-light">Promedio</th>
                                        <th class="text-center bg-light">Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Itera sobre cada asignatura con sus notas -->
                                    @forelse($asignaturasNotas as $item)
                                        <tr>
                                            <!-- Nombre y código de la asignatura -->
                                            <td>
                                                <strong>{{ $item['asignatura']->nombre }}</strong>
                                                <div class="small text-muted">{{ $item['asignatura']->codigo }}</div>
                                            </td>

                                            <!-- Muestra las notas de cada período para esta asignatura -->
                                            @foreach ($item['notas_periodos'] as $notaPeriodo)
                                                <td class="text-center">
                                                    @php
                                                        $val = $notaPeriodo['nota'];
                                                        $letra = $notaPeriodo['nota_letra'];
                                                        
                                                        // Helper para convertir a letra si es necesario
                                                        if (empty($letra) && is_numeric($val)) {
                                                            if ($val >= 18) $letra = 'AD';
                                                            elseif ($val >= 14) $letra = 'A';
                                                            elseif ($val >= 11) $letra = 'B';
                                                            else $letra = 'C';
                                                        }
                                                        
                                                        // Decidir qué mostrar (Prioridad Letra para esta vista)
                                                        $displayVal = $letra ?: $val;
                                                        
                                                        // Determine color/status
                                                        $isPassing = true;
                                                        if($letra) {
                                                            $isPassing = $letra != 'C'; 
                                                        } else {
                                                            $isPassing = $val >= 11;
                                                        }
                                                        
                                                        // Get obs
                                                        $obs = $notaPeriodo['observaciones'] ?? 'Sin observaciones';
                                                    @endphp
                                                    
                                                    <!-- Si existe nota, la muestra con color según aprobado o reprobado -->
                                                    @if ($displayVal !== null)
                                                        <span
                                                            class="nota-valor {{ $isPassing ? 'text-success' : 'text-danger' }} fw-bold nota-tooltip"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            data-bs-html="true"
                                                            title="{{ htmlspecialchars($obs) }}">
                                                            {{ $displayVal }}
                                                        </span>
                                                        
                                                        @if(!empty($notaPeriodo['competencias']))
                                                          <div class="small text-muted mt-1" style="font-size:0.7rem">
                                                            @foreach($notaPeriodo['competencias'] as $cid => $cval)
                                                              @if($cval) C{{$cid}}:{{$cval}} @endif
                                                            @endforeach
                                                          </div>
                                                        @endif
                                                    @else
                                                        <!-- Si no hay nota, muestra un guión -->
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            @endforeach

                                            <!-- Muestra el promedio o "Pendiente" si no hay promedio calculado -->
                                            <td
                                                class="text-center fw-bold {{ $item['promedio'] >= 11 ? 'bg-success bg-opacity-10' : ($item['promedio'] ? 'bg-danger bg-opacity-10' : '') }}">
                                                @if ($item['promedio'] !== null)
                                                    @php
                                                        // Convertir promedio a letra también
                                                        $promVal = $item['promedio'];
                                                        $promLetra = '';
                                                        if ($promVal >= 18) $promLetra = 'AD';
                                                        elseif ($promVal >= 14) $promLetra = 'A';
                                                        elseif ($promVal >= 11) $promLetra = 'B';
                                                        else $promLetra = 'C';

                                                        // Buscar observaciones de la nota final anual para esta asignatura
                                                        $notaAnual = isset(
                                                            $notasFinalesAnuales[$item['asignatura']->asignatura_id],
                                                        )
                                                            ? $notasFinalesAnuales[$item['asignatura']->asignatura_id]
                                                            : null;
                                                        $observacionAnual =
                                                            $notaAnual && $notaAnual->observaciones
                                                                ? $notaAnual->observaciones
                                                                : 'Promedio calculado automáticamente';
                                                    @endphp
                                                    <span class="promedio-tooltip" data-bs-toggle="tooltip"
                                                        data-bs-placement="top" data-bs-html="true"
                                                        title="{{ htmlspecialchars($observacionAnual) }}">
                                                        {{ $promLetra }}
                                                    </span>
                                                @else
                                                    Pendiente
                                                @endif
                                            </td>

                                            <!-- Lógica para mostrar el estado de aprobación -->
                                            <td class="text-center">
                                                @php
                                                    // Cuenta cuántos períodos tienen calificación para esta asignatura
                                                    $periodosCalificados = collect($item['notas_periodos'])
                                                        ->filter(function ($periodo) {
                                                            return $periodo['nota'] !== null;
                                                        })
                                                        ->count();

                                                    // Determina si se han completado todos los períodos (4 bimestres)
                                                    // Solo se muestra "Aprobado" o "Reprobado" cuando todos los bimestres tienen nota
                                                    $todosLosPeriodosCalificados =
                                                        $periodosCalificados === count($periodos) &&
                                                        count($periodos) === 4;
                                                @endphp

                                                <!-- Muestra estado según si todos los períodos están calificados -->
                                                @if ($todosLosPeriodosCalificados && $item['promedio'] !== null)
                                                    <!-- Si existe un registro oficial en notasFinalesAnuales, usa ese estado -->
                                                    @if (isset($notasFinalesAnuales[$item['asignatura']->asignatura_id]))
                                                        @php $estado = $notasFinalesAnuales[$item['asignatura']->asignatura_id]->estado; @endphp
                                                        <span
                                                            class="badge {{ $estado == 'Aprobado' ? 'bg-success' : ($estado == 'Reprobado' ? 'bg-danger' : 'bg-warning') }}" style="font-weight: bold; color:#fff; border:none">
                                                            {{ $estado }}
                                                        </span>
                                                    @else
                                                        <!-- Si no hay registro oficial, calcula el estado basado en el promedio -->
                                                        <span
                                                            class="badge {{ $item['promedio'] >= 11 ? 'bg-success' : 'bg-danger' }}"  style="font-weight: bold; color:#fff; border:none">
                                                            {{ $item['promedio'] >= 11 ? 'Aprobado' : 'Reprobado' }}
                                                        </span>
                                                    @endif
                                                @else
                                                    <!-- Si faltan calificaciones, muestra "Pendiente" -->
                                                    <span class="badge bg-secondary"  style="font-weight: bold; color:#fff; border:none">Pendiente</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <!-- Mensaje cuando no hay asignaturas -->
                                        <tr>
                                            <td colspan="{{ count($periodos) + 3 }}" class="text-center">No hay asignaturas
                                                registradas para este estudiante</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Sección de resumen y estadísticas -->
                        <div class="mt-4">
                            <div class="card">
                                <div class="card-header" style="background-color: #e5c44d6f">
                                    <h5 class="mb-0" style="font-weight: bold">Resumen de progreso del Estudiante</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <!-- Gráfico visual de notas -->
                                        <div class="col-md-6 d-print-none">
                                            <div class="canvas-container" style="position: relative; height: 300px;">
                                                <canvas id="graficoNotas"></canvas>
                                            </div>
                                        </div>
                                        <!-- Estadísticas numéricas -->
                                        <div class="col-md-6">
                                            <h6 class="mb-3">Estadísticas</h6>
                                            <div class="table-responsive">
                                                <table class="table table-sm table-bordered">
                                                    <tr>
                                                        <td class="bg-light">Asignaturas Totales:</td>
                                                        <td>{{ count($asignaturasNotas) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <!-- Cuenta asignaturas aprobadas: promedio existe y es >= 11 -->
                                                        <td class="bg-light">Asignaturas Aprobadas:</td>
                                                        <td>{{ collect($asignaturasNotas)->filter(function ($item) {return $item['promedio'] !== null && $item['promedio'] >= 11;})->count() }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <!-- Cuenta asignaturas reprobadas: promedio existe y es < 11 -->
                                                        <td class="bg-light">Asignaturas Reprobadas:</td>
                                                        <td>{{ collect($asignaturasNotas)->filter(function ($item) {return $item['promedio'] !== null && $item['promedio'] < 11;})->count() }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <!-- Calcula el promedio general de todas las asignaturas -->
                                                        <td class="bg-light">Promedio General:</td>
                                                        <td>{{ number_format(collect($asignaturasNotas)->avg('promedio') ?? 0, 2) }}
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Botones de acción al pie de la tarjeta -->
                    <div class="card-footer bg-light d-print-none">
                        <div class="row">
                            <div class="col-md-6 w-100 mb-2 mt-1">
                                @if (auth()->user()->rol == 'Administrador')
                                    <a href="{{ route('notas.consulta') }}" class="btn btn-secondary w-100" style="font-weight: bold">
                                        <i class="fas fa-arrow-left mx-2"></i> Volver a la búsqueda
                                    </a>
                                @elseif (auth()->user()->rol == 'Representante')
                                    <a href="{{ route('notas.misEstudiantes') }}" class="btn btn-secondary w-100 " style="font-weight: bold">
                                        <i class="fas fa-arrow-left mx-2"></i> Volver a mis estudiantes
                                    </a>
                                @else
                                    <a href="{{ route('rutarrr1') }}" class="btn btn-secondary w-100" style="font-weight: bold">
                                        <i class="fas fa-arrow-left mx-2"></i> Volver al inicio
                                    </a>
                                @endif
                            </div>
                            <div class="col-md-6 text-md-end w-100 mt-1">
                                <button class="btn btn-primary w-100" onclick="imprimirCalificaciones()" style="font-weight: bold">
                                    <i class="fas fa-print mx-2"></i> Imprimir Calificaciones
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                $(document).ready(function() {
                    // Collapse icon toggle
                    const btn = $('[data-target="#collapseCalificacionesEstudiante"]');
                    const icon = btn.find('.fas.fa-chevron-down, .fas.fa-chevron-up');

                    $('#collapseCalificacionesEstudiante').on('show.bs.collapse', function () {
                        icon.removeClass('fa-chevron-down').addClass('fa-chevron-up');
                    });

                    $('#collapseCalificacionesEstudiante').on('hide.bs.collapse', function () {
                        icon.removeClass('fa-chevron-up').addClass('fa-chevron-down');
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>
    <iframe id="iframe" width="0" height="0"></iframe>
    <style>
        .nota-valor {
            font-size: 16px;
        }

        /* Estilos para tooltips personalizados */
        .nota-tooltip,
        .promedio-tooltip {
            cursor: help;
            position: relative;
        }

        /* Estilos para tooltips de Bootstrap */
        .tooltip {
            font-size: 13px;
        }

        .tooltip-inner {
            max-width: 300px;
            text-align: left;
            background-color: #333;
            color: #fff;
            padding: 8px 12px;
            border-radius: 4px;
        }

        /* Ocultar tooltips en impresión */
        @media print {
            .tooltip {
                display: none !important;
            }
        }
    </style>

@endsection

@section('scripts')
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Script para mensajes globales con SweetAlert2 -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mensajes de éxito
            @if (session('success'))
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

            // Mensajes de error
            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: '¡Error!',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Entendido'
                });
            @endif

            // Mensajes de información
            @if (session('info'))
                Swal.fire({
                    icon: 'info',
                    title: 'Información',
                    text: '{{ session('info') }}',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Aceptar'
                });
            @endif
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar tooltips de Bootstrap
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl, {
                    trigger: 'hover focus',
                    delay: {
                        show: 300,
                        hide: 100
                    }
                });
            });

            @php
                $chartLabels = [];
                $chartData = [];
                $systemHasLetters = false;
                
                // Función helper para convertir valores a escala 0-4
                if (!function_exists('valToNum')) {
                    function valToNum($val) {
                        if (is_numeric($val)) {
                            // Si es escala vigesimal (0-20), convertir a 4 aprox para uniformizar si se mezclan
                            if ($val > 4) {
                                if ($val >= 18) return 4; // AD
                                if ($val >= 14) return 3; // A
                                if ($val >= 11) return 2; // B
                                return 1; // C
                            }
                            return $val;
                        }
                        $v = strtoupper(trim($val));
                        if ($v === 'AD') return 4;
                        if ($v === 'A') return 3;
                        if ($v === 'B') return 2;
                        if ($v === 'C') return 1;
                        return 0;
                    }
                }

                foreach($asignaturasNotas as $item) {
                     $chartLabels[] = $item['asignatura']->nombre;
                     
                     // 1. Intentar obtener el promedio final calculado (Guardado en BD)
                     $val = $item['promedio'];
                     
                     // 2. Si no hay promedio anual, calcularlo basado en los Periodos
                     if ($val === null || $val === '') {
                         $sumPeriodos = 0;
                         $countPeriodos = 0;
                         
                         foreach($item['notas_periodos'] as $per) {
                             $notaPeriodo = null;

                             // A. Si existe nota de periodo guardada
                             if(isset($per['nota']) && $per['nota'] !== null) {
                                  $notaPeriodo = $per['nota'];
                             } 
                             // B. Si NO existe nota de periodo, calcularla desde las COMPETENCIAS
                             else if (isset($per['competencias']) && is_array($per['competencias']) && count($per['competencias']) > 0) {
                                 $sumComp = 0;
                                 $countComp = 0;
                                 foreach($per['competencias'] as $compId => $compNota) {
                                     if ($compNota !== null) {
                                         $sumComp += valToNum($compNota);
                                         $countComp++;
                                         
                                         // Detectar si usamos letras en las competencias
                                         if (!is_numeric($compNota)) $systemHasLetters = true;
                                     }
                                 }
                                 if ($countComp > 0) {
                                     $notaPeriodo = $sumComp / $countComp; // Promedio simple de competencias
                                 }
                             }

                             // Si detectamos letras explícitas en el periodo
                             if(isset($per['nota_letra']) && $per['nota_letra']) $systemHasLetters = true;

                             // Agregar al acumulado anual
                             if ($notaPeriodo !== null) {
                                 $sumPeriodos += valToNum($notaPeriodo);
                                 $countPeriodos++;
                             }
                         }
                         
                         // Promedio Anual (Simple)
                         $val = ($countPeriodos > 0) ? round($sumPeriodos / $countPeriodos, 2) : 0;
                     } 
                     // Si el promedio venía de BD pero era numérico y queremos saber si hay letras escondidas
                     else {
                        foreach($item['notas_periodos'] as $p) {
                            if(isset($p['nota_letra']) && $p['nota_letra']) $systemHasLetters = true;
                        }
                     }

                     $chartData[] = $val;
                }
            @endphp

            // Prepara los datos para el gráfico de manera robusta
            const asignaturasRaw = @json($chartLabels);
            const promediosRaw = @json($chartData);
            // Pasamos flag de PHP a JS
            const phpDetectedLetters = @json($systemHasLetters);

            // Asegurarse de que sean arrays (manejo de objetos PHP convertidos a JSON)
            const asignaturas = Array.isArray(asignaturasRaw) ? asignaturasRaw : Object.values(asignaturasRaw);
            let rawPromedios = Array.isArray(promediosRaw) ? promediosRaw : Object.values(promediosRaw);

            console.log('Datos procesados para gráfico:', { asignaturas, rawPromedios, phpDetectedLetters });

            const canvas = document.getElementById('graficoNotas');
            if (!canvas) {
                console.error('Elemento canvas no encontrado');
                return;
            }

            const ctx = canvas.getContext('2d');

            // Función de mapeo de Letra a Valor Numérico para el Gráfico
            const letterToVal = (val) => {
                if (!val) return 0;
                // Si es string directo
                if (typeof val === 'string') {
                    const v = val.trim().toUpperCase();
                    if (v === 'AD') return 4;
                    if (v === 'A') return 3;
                    if (v === 'B') return 2;
                    if (v === 'C') return 1;
                }
                const num = parseFloat(val);
                if (isNaN(num)) return 0;
                
                // Si viene como numero 0-20 (ej: 16) y estamos en modo letras, lo convertimos a 1-4
                if (num > 4) {
                    if (num >= 18) return 4; // AD
                    if (num >= 14) return 3; // A
                    if (num >= 11) return 2; // B
                    return 1; // C
                }
                return num;
            };

            // Detectar si usamos escala de letras
            // 1. Si PHP lo detectó explícitamente en las notas parciales
            // 2. Si encontramos strings 'A', 'B'... en los datos
            const hasLetters = phpDetectedLetters || rawPromedios.some(p => {
                 if (typeof p === 'string') {
                     return ['A', 'B', 'C', 'AD'].includes(p.toUpperCase());
                 }
                 return false;
            });

            console.log("Modo Letras activo:", hasLetters);

            let chartData = [];
            let isLetterScale = hasLetters;

            if (isLetterScale) {
                chartData = rawPromedios.map(p => letterToVal(p));
            } else {
                 chartData = rawPromedios.map(p => parseFloat(p) || 0);
            }

            console.log('Datos numéricos finales para chart:', chartData);

            // Crear gradiente bonito
            let gradientPass = ctx.createLinearGradient(0, 0, 0, 300);
            gradientPass.addColorStop(0, 'rgba(75, 192, 192, 0.7)');
            gradientPass.addColorStop(1, 'rgba(75, 192, 192, 0.1)');

            let gradientFail = ctx.createLinearGradient(0, 0, 0, 300);
            gradientFail.addColorStop(0, 'rgba(255, 99, 132, 0.7)');
            gradientFail.addColorStop(1, 'rgba(255, 99, 132, 0.1)');

            const getBackground = (val) => {
                const isPassing = isLetterScale ? (val >= 2) : (val >= 11);
                return isPassing ? gradientPass : gradientFail;
            };

            const getBorder = (val) => {
                const isPassing = isLetterScale ? (val >= 2) : (val >= 11);
                return isPassing ? 'rgba(75, 192, 192, 1)' : 'rgba(255, 99, 132, 1)';
            };

            // Configuración del eje Y
            const yScales = isLetterScale ? {
                beginAtZero: true,
                max: 4.5,
                grid: {
                    color: '#f0f0f0'
                },
                ticks: {
                    stepSize: 1,
                    callback: function(value) {
                        const map = {4: 'AD (Logro Destacado)', 3: 'A (Logro Esperado)', 2: 'B (En Proceso)', 1: 'C (En Inicio)', 0: ''};
                        return map[value] || '';
                    },
                    font: {
                        weight: 'bold'
                    }
                }
            } : {
                beginAtZero: true,
                max: 20,
                grid: {
                    color: '#f0f0f0'
                },
                ticks: { stepSize: 2 }
            };

            // Destruir gráfico previo si existe (aunque en recarga completa no hace falta)
            if (window.myChartInstance) {
                window.myChartInstance.destroy();
            }

            // Crea el gráfico de barras bonito
            window.myChartInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: asignaturas,
                    datasets: [{
                        label: 'Rendimiento Académico',
                        data: chartData,
                        backgroundColor: chartData.map(v => getBackground(v)),
                        borderColor: chartData.map(v => getBorder(v)),
                        borderWidth: 2,
                        borderRadius: 5, // Bordes redondeados
                        barPercentage: 0.6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 1500,
                        easing: 'easeOutQuart'
                    },
                    scales: {
                        y: yScales,
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        title: {
                            display: true,
                            text: 'Resumen de Rendimiento Escolar',
                            font: {
                                size: 16
                            },
                            padding: {
                                bottom: 20
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            padding: 10,
                            titleFont: { size: 14 },
                            bodyFont: { size: 13 },
                            callbacks: {
                                label: context => {
                                    const val = context.parsed.y;
                                    let label = '';
                                    if (isLetterScale) {
                                        const map = {4: 'AD', 3: 'A', 2: 'B', 1: 'C'};
                                        label = map[val] || '';
                                    } else {
                                        label = val;
                                    }
                                    const status = (isLetterScale ? val > 1 : val >= 11) ? 'Aprobado' : 'Reprobado';
                                    return `Nota: ${label} - ${status}`;
                                }
                            }
                        }
                    }
                }
            });
        });



        // Función para imprimir
        function imprimirCalificaciones() {
            const imprimir = document.getElementById('imprimir').innerHTML;
            // Incluye los estilos principales del sistema y los de vernotas
            const printHtml = `
			<html>
				<head>
					<meta charset="utf-8">
					<title>Boleta de Notas - ${new Date().getFullYear()}</title>
					<link rel="stylesheet" href="{{ asset('adminlte/assets/css/bootstrap.min.css') }}">
                    <style>
                        body { background: white !important; padding: 20px; font-family: sans-serif; }
                        h3, h4 { text-align: center; }
                        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                        .table th, .table td { border: 1px solid #000; padding: 8px; text-align: center; font-size: 14px; }
                        .table th { background-color: #f2f2f2; }
                        .text-start { text-align: left !important; }
                        .text-end { text-align: right !important; }
                        
                        /* Ocultar elementos no deseados en impresión */
                        .nota-tooltip, .promedio-tooltip { pointer-events: none; }
                        .d-print-none { display: none !important; }
                        .d-print-block { display: block !important; }
                    </style>
				</head>
				<body>${imprimir}
                    <div style="margin-top: 50px; text-align: center;">
                        <br><br><br>
                        __________________________________________<br>
                        Firma del Director / Tutor
                    </div>
				</body>
			</html>`;
            const iframe = document.getElementById('iframe');
            iframe.srcdoc = printHtml;
            iframe.onload = function() {
                iframe.contentWindow.focus();
                iframe.contentWindow.print();
            };
        }
    </script>
@endsection

