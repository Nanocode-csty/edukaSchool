@extends('cplantilla.bprincipal')
@section('titulo','Reportes de Asistencia')
@section('contenidoplantilla')
<x-breadcrumb :module="'asistencia'" :section="'reportes'" />


<div class="container-fluid" id="contenido-principal">
    <div class="row mt-4 ml-1 mr-1">
        <div class="col-12">
            <div class="box_block">
                <!-- Collapse header -->
                <button class="btn btn-block text-left rounded-0 btn_header header_6" type="button" data-toggle="collapse" data-target="#collapseReportes" aria-expanded="true" aria-controls="collapseReportes" style="background: #0A8CB3 !important; font-weight: bold; color: white;">
                    <i class="fas fa-chart-line m-1"></i>&nbsp;Reportes de Asistencia
                    <div class="float-right"><i class="fas fa-chevron-down"></i></div>
                </button>
                <!-- Descripción -->
                <div class="card-body rounded-0 border-0 pt-3 pb-3" style="background: #f3f3f3; border-bottom: 1px solid rgba(0,0,0,.125); border-top: 1px solid rgba(0,0,0,.125); color: #F59D24;">
                    <div class="row justify-content-center align-items-center flex-wrap">
                        <div class="col-auto text-center mb-2 mb-md-0" style="min-width:48px;">
                            <i class="fas fa-chart-bar fa-2x"></i>
                        </div>
                        <div class="col px-2" style="text-align:justify;">
                            <p style="margin-bottom: 0px; font-family: 'Quicksand', sans-serif; font-weight: 600; color: #004a92;">
                                Genera reportes detallados de asistencia por período, curso, estudiante o docente. Analiza tendencias y patrones de asistencia para tomar decisiones informadas.
                            </p>
                            <p style="font-family: 'Quicksand', sans-serif; font-weight: 600; color: #004a92;">
                                Utiliza los filtros avanzados para obtener información específica y exporta los reportes en diferentes formatos según tus necesidades.
                            </p>
                        </div>
                    </div>
                </div>
                <!-- Collapse: filtros y contenido -->
                <div class="collapse show" id="collapseReportes">
                    <div class="card card-body rounded-0 border-0 pt-0 pb-2" style="background: transparent;">
                        <!-- Filtros de Reporte -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header" style="background: #0e4067; color: white;">
                                        <h5 class="mb-0"><i class="fas fa-filter mr-2"></i>Filtros de Reporte</h5>
                                    </div>
                                    <div class="card-body">
                                        <form id="reportForm">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="fecha_inicio">Fecha Inicio</label>
                                                        <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="fecha_fin">Fecha Fin</label>
                                                        <input type="date" class="form-control" id="fecha_fin" name="fecha_fin">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="tipo_reporte">Tipo de Reporte</label>
                                                        <select class="form-control" id="tipo_reporte" name="tipo_reporte">
                                                            <option value="general">General</option>
                                                            <option value="por_curso">Por Curso</option>
                                                            <option value="por_estudiante">Por Estudiante</option>
                                                            <option value="por_docente">Por Docente</option>
                                                            <option value="comparativo">Comparativo</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="formato">Formato</label>
                                                        <select class="form-control" id="formato" name="formato">
                                                            <option value="pdf">PDF</option>
                                                            <option value="excel">Excel</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Botones de Períodos Rápidos -->
                                            <div class="row mb-3">
                                                <div class="col-12">
                                                    <label class="form-label fw-bold text-primary">Períodos Rápidos:</label>
                                                    <div class="btn-group-sm d-flex flex-wrap gap-1" role="group">
                                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="setPeriodo('hoy')">Hoy</button>
                                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="setPeriodo('ayer')">Ayer</button>
                                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="setPeriodo('ultimos7')">Últimos 7 días</button>
                                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="setPeriodo('ultimos30')">Últimos 30 días</button>
                                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="setPeriodo('ultimos90')">Últimos 90 días</button>
                                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="setPeriodo('esteMes')">Este mes</button>
                                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="setPeriodo('mesAnterior')">Mes anterior</button>
                                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="setPeriodo('esteAnio')">Este año</button>
                                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="setPeriodo('anioAnterior')">Año anterior</button>
                                                        <button type="button" class="btn btn-outline-secondary btn-sm ml-2" onclick="limpiarFechas()">
                                                            <i class="fas fa-eraser"></i> Limpiar
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row" id="filtrosAdicionales" style="display: none;">
                                                <div class="col-md-12" id="filtroContainer">
                                                    <!-- Los filtros se cargarán dinámicamente aquí -->
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12 text-center">
                                                    <button type="button" class="btn btn-primary btn-lg" onclick="generarReporte()">
                                                        <i class="fas fa-chart-line mr-2"></i>Generar Reporte
                                                    </button>
                                                    <a id="exportLink" href="#" class="btn btn-success btn-lg ml-2" onclick="exportarReporte(event)">
                                                        <i class="fas fa-download mr-2"></i>Exportar
                                                    </a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Vista Previa del Reporte (Collapsible) -->
                        <div class="row mb-4" id="reportePreview" style="display: none;">
                            <div class="col-12">
                                <div class="card">
                                    <!-- Collapse header -->
                                    <button class="btn btn-block text-left rounded-0 btn_header header_5" type="button" data-toggle="collapse" data-target="#collapsePreview" aria-expanded="false" aria-controls="collapsePreview" style="background: #17a2b8 !important; font-weight: bold; color: white;">
                                        <i class="fas fa-eye m-1"></i>&nbsp;Vista Previa del Reporte
                                        <div class="float-right"><i class="fas fa-chevron-down"></i></div>
                                    </button>
                                    <!-- Collapse content -->
                                    <div class="collapse" id="collapsePreview">
                                        <div class="card-body">
                                            <div id="reporteContent">
                                                <!-- Contenido dinámico del reporte -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Estadísticas Rápidas -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body p-3">
                                        <div class="row text-center">
                                            <div class="col-md-2 col-6 mb-3 mb-md-0">
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <div class="stats-icon mr-2" style="background: rgba(40, 167, 69, 0.1); color: #28a745; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                        <i class="fas fa-user-check"></i>
                                                    </div>
                                                    <div class="text-left">
                                                        <div class="h5 mb-0 text-success font-weight-bold">{{ $estadisticasRapidas['porcentaje_asistencia'] }}%</div>
                                                        <small class="text-muted" style="font-size: 0.75rem;">Asistencia</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-6 mb-3 mb-md-0">
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <div class="stats-icon mr-2" style="background: rgba(220, 53, 69, 0.1); color: #dc3545; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                        <i class="fas fa-user-times"></i>
                                                    </div>
                                                    <div class="text-left">
                                                        <div class="h5 mb-0 text-danger font-weight-bold">{{ number_format($estadisticasRapidas['total_inasistencias']) }}</div>
                                                        <small class="text-muted" style="font-size: 0.75rem;">Ausencias</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-6">
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <div class="stats-icon mr-2" style="background: rgba(255, 193, 7, 0.1); color: #ffc107; width: 40px; height: 40px; border-radius: 50%; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                        <i class="fas fa-clock"></i>
                                                    </div>
                                                    <div class="text-left">
                                                        <div class="h5 mb-0 text-warning font-weight-bold">{{ number_format($estadisticasRapidas['total_tardanzas']) }}</div>
                                                        <small class="text-muted" style="font-size: 0.75rem;">Tardanzas</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-6">
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <div class="stats-icon mr-2" style="background: rgba(23, 162, 184, 0.1); color: #17a2b8; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                        <i class="fas fa-check-circle"></i>
                                                    </div>
                                                    <div class="text-left">
                                                        <div class="h5 mb-0 text-info font-weight-bold">{{ number_format($estadisticasRapidas['justificaciones_aprobadas']) }}</div>
                                                        <small class="text-muted" style="font-size: 0.75rem;">Justificadas</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-6">
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <div class="stats-icon mr-2" style="background: rgba(108, 117, 125, 0.1); color: #6c757d; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                        <i class="fas fa-users"></i>
                                                    </div>
                                                    <div class="text-left">
                                                        <div class="h5 mb-0 text-secondary font-weight-bold">{{ number_format($estadisticasRapidas['total_estudiantes'] ?? 0) }}</div>
                                                        <small class="text-muted" style="font-size: 0.75rem;">Estudiantes</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-6">
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <div class="stats-icon mr-2" style="background: rgba(52, 58, 64, 0.1); color: #343a40; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                        <i class="fas fa-calendar-alt"></i>
                                                    </div>
                                                    <div class="text-left">
                                                        <div class="h5 mb-0 text-dark font-weight-bold">{{ number_format($estadisticasRapidas['dias_analizados'] ?? 0) }}</div>
                                                        <small class="text-muted" style="font-size: 0.75rem;">Días</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Indicadores de Estado de Asistencia (5 puntos de colores) -->
                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <div class="text-center">
                                                    <small class="text-muted mb-3 d-block">Indicadores de Estado</small>
                                                    <div class="d-flex justify-content-center align-items-center flex-wrap gap-4">
                                                        <div class="d-flex align-items-center">
                                                            <span style="font-size: 18px; margin-right: 8px;">🟢</span>
                                                            <small class="text-muted font-weight-bold">Presente</small>
                                                        </div>
                                                        <div class="d-flex align-items-center">
                                                            <span style="font-size: 18px; margin-right: 8px;">🔴</span>
                                                            <small class="text-muted font-weight-bold">Ausente</small>
                                                        </div>
                                                        <div class="d-flex align-items-center">
                                                            <span style="font-size: 18px; margin-right: 8px;">🟡</span>
                                                            <small class="text-muted font-weight-bold">Tarde</small>
                                                        </div>
                                                        <div class="d-flex align-items-center">
                                                            <span style="font-size: 18px; margin-right: 8px;">🔵</span>
                                                            <small class="text-muted font-weight-bold">Justificado</small>
                                                        </div>
                                                        <div class="d-flex align-items-center">
                                                            <span style="font-size: 18px; margin-right: 8px;">⚫</span>
                                                            <small class="text-muted font-weight-bold">Sin Registro</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Gráficos de Tendencias -->
                        <div class="row">
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header" style="background: #28a745; color: white;">
                                        <h5 class="mb-0"><i class="fas fa-chart-area mr-2"></i>Tendencia de Asistencia</h5>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="tendenciaChart" height="300"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header" style="background: #dc3545; color: white;">
                                        <h5 class="mb-0"><i class="fas fa-chart-pie mr-2"></i>Distribución por Tipo</h5>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="distribucionChart" height="300"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Análisis Comparativo y Rankings -->
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header" style="background: #17a2b8; color: white;">
                                        <h5 class="mb-0"><i class="fas fa-chart-bar mr-2"></i>Asistencia por Día de la Semana</h5>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="semanalChart" height="250"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header" style="background: #ffc107; color: white;">
                                        <h5 class="mb-0"><i class="fas fa-trophy mr-2"></i>Top 5 Estudiantes con Mejor Asistencia</h5>
                                    </div>
                                    <div class="card-body">
                                        <div id="rankingEstudiantes" class="text-center">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="sr-only">Cargando...</span>
                                            </div>
                                            <p>Cargando ranking...</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <!-- KPIs Avanzados -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header" style="background: #20c997; color: white;">
                                        <h5 class="mb-0"><i class="fas fa-tachometer-alt mr-2"></i>KPIs de Rendimiento Educativo</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row text-center">
                                            <div class="col-md-3 col-6 mb-3">
                                                <div class="kpi-card p-3 rounded" style="background: #007bff; color: white;">
                                                    <div class="kpi-icon mb-2">
                                                        <i class="fas fa-users fa-2x"></i>
                                                    </div>
                                                    <div class="kpi-value">
                                                        <h3 class="mb-1">{{ number_format($estadisticasRapidas['total_estudiantes'] ?? 0) }}</h3>
                                                        <small>Estudiantes Activos</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-6 mb-3">
                                                <div class="kpi-card p-3 rounded" style="background: #6c757d; color: white;">
                                                    <div class="kpi-icon mb-2">
                                                        <i class="fas fa-clock fa-2x"></i>
                                                    </div>
                                                    <div class="kpi-value">
                                                        <h3 class="mb-1">{{ number_format($estadisticasRapidas['dias_analizados'] ?? 0) }}</h3>
                                                        <small>Días Analizados</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-6 mb-3">
                                                <div class="kpi-card p-3 rounded" style="background: #28a745; color: white;">
                                                    <div class="kpi-icon mb-2">
                                                        <i class="fas fa-chart-line fa-2x"></i>
                                                    </div>
                                                    <div class="kpi-value">
                                                        <h3 class="mb-1">{{ $estadisticasRapidas['porcentaje_asistencia'] }}%</h3>
                                                        <small>Tasa Promedio</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-6 mb-3">
                                                <div class="kpi-card p-3 rounded" style="background: #17a2b8; color: white;">
                                                    <div class="kpi-icon mb-2">
                                                        <i class="fas fa-shield-alt fa-2x"></i>
                                                    </div>
                                                    <div class="kpi-value">
                                                        <h3 class="mb-1">{{ number_format($estadisticasRapidas['justificaciones_aprobadas'] ?? 0) }}</h3>
                                                        <small>Justificaciones</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Métricas Adicionales -->
                                        <div class="row mt-4">
                                            <div class="col-md-6">
                                                <div class="card border-primary">
                                                    <div class="card-body">
                                                        <h6 class="card-title text-primary">
                                                            <i class="fas fa-calculator mr-2"></i>Métricas Calculadas
                                                        </h6>
                                                        <ul class="list-unstyled">
                                                            <li><strong>Registros por Estudiante:</strong> {{ $estadisticasRapidas['dias_analizados'] && $estadisticasRapidas['total_estudiantes'] ? number_format(($estadisticasRapidas['dias_analizados'] ?? 0) / max(1, $estadisticasRapidas['total_estudiantes'] ?? 1), 1) : '0.0' }}</li>
                                                            <li><strong>Tasa de Ausentismo:</strong> {{ $estadisticasRapidas['total_inasistencias'] && $estadisticasRapidas['dias_analizados'] ? number_format(($estadisticasRapidas['total_inasistencias'] / max(1, $estadisticasRapidas['dias_analizados'])) * 100, 1) : '0.0' }}%</li>
                                                            <li><strong>Justificaciones por Ausencia:</strong> {{ $estadisticasRapidas['total_inasistencias'] ? number_format(($estadisticasRapidas['justificaciones_aprobadas'] ?? 0) / max(1, $estadisticasRapidas['total_inasistencias']) * 100, 1) : '0.0' }}%</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card border-success">
                                                    <div class="card-body">
                                                        <h6 class="card-title text-success">
                                                            <i class="fas fa-lightbulb mr-2"></i>Insights Automáticos
                                                        </h6>
                                                        <div id="insightsContainer">
                                                            <div class="alert alert-light">
                                                                <small>
                                                                    @if(($estadisticasRapidas['porcentaje_asistencia'] ?? 0) >= 90)
                                                                        <i class="fas fa-star text-warning mr-1"></i>Excelente rendimiento general de asistencia.
                                                                    @elseif(($estadisticasRapidas['porcentaje_asistencia'] ?? 0) >= 80)
                                                                        <i class="fas fa-thumbs-up text-success mr-1"></i>Buen nivel de asistencia institucional.
                                                                    @else
                                                                        <i class="fas fa-exclamation-triangle text-warning mr-1"></i>Se recomienda implementar estrategias de mejora.
                                                                    @endif

                                                                    @if(($estadisticasRapidas['total_tardanzas'] ?? 0) > ($estadisticasRapidas['total_inasistencias'] ?? 0) * 0.5)
                                                                        <br><i class="fas fa-clock text-info mr-1"></i>Las tardanzas representan un área de oportunidad.
                                                                    @endif
                                                                </small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Reportes Recientes -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header" style="background: #ffc107; color: white;">
                                        <h5 class="mb-0"><i class="fas fa-history mr-2"></i>Reportes Generados Recientemente</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead class="table-primary">
                                                    <tr>
                                                        <th>Fecha Generación</th>
                                                        <th>Tipo de Reporte</th>
                                                        <th>Período</th>
                                                        <th>Formato</th>
                                                        <th>Registros</th>
                                                        <th>Generado por</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($reportesRecientes as $reporte)
                                                    <tr>
                                                        <td>{{ $reporte->fecha_generacion->format('d/m/Y H:i') }}</td>
                                                        <td><span class="badge badge-primary">{{ $reporte->tipo_reporte_nombre }}</span></td>
                                                        <td>{{ $reporte->periodo }}</td>
                                                        <td><span class="badge badge-success">{{ $reporte->formato_nombre }}</span></td>
                                                        <td><span class="badge badge-info">{{ number_format($reporte->registros_totales) }}</span></td>
                                                        <td>{{ $reporte->generado_por }}</td>
                                                        <td>
                                                            @if($reporte->archivoExiste())
                                                            <a href="{{ $reporte->archivo_url }}" target="_blank" class="btn btn-sm btn-outline-primary" title="Descargar reporte">
                                                                <i class="fas fa-download"></i> Descargar
                                                            </a>
                                                            @else
                                                            <button class="btn btn-sm btn-outline-secondary" disabled title="Archivo no disponible">
                                                                <i class="fas fa-file-alt"></i> No disponible
                                                            </button>
                                                            @endif
                                                            <button class="btn btn-sm btn-outline-info" onclick="verDetallesReporte({{ $reporte->id }})" title="Ver detalles">
                                                                <i class="fas fa-eye"></i> Ver
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    @empty
                                                    <tr>
                                                        <td colspan="7" class="text-center text-muted">
                                                            <i class="fas fa-info-circle mr-2"></i>No hay reportes generados recientemente
                                                            <br><small class="text-muted">Los reportes generados aparecerán aquí automáticamente</small>
                                                        </td>
                                                    </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>

                                        @if($reportesRecientes->count() > 0)
                                        <div class="mt-3 text-center">
                                            <small class="text-muted">
                                                <i class="fas fa-info-circle mr-1"></i>
                                                Mostrando los {{ $reportesRecientes->count() }} reportes más recientes (últimos 30 días)
                                            </small>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                $(document).ready(function() {
                    // Inicializar Select2 para todos los selects
                    $('.form-control').each(function() {
                        if ($(this).attr('id') !== 'tipo_reporte' && $(this).attr('id') !== 'formato' && $(this).attr('id') !== 'fecha_inicio' && $(this).attr('id') !== 'fecha_fin') {
                            $(this).select2({
                                theme: 'bootstrap-5',
                                width: '100%',
                                placeholder: function() {
                                    return $(this).find('option:first').text() || 'Seleccionar...';
                                },
                                allowClear: true,
                                minimumResultsForSearch: 0  // Always show search box
                            });
                        }
                    });
                });

                // Función global para configurar event listeners de filtros dinámicos
                function agregarEventListenersFiltrosDinamicos() {
                    // Escuchar cambios en las fechas para actualizar filtros dinámicos
                    const fechaInicioInput = document.getElementById('fecha_inicio');
                    const fechaFinInput = document.getElementById('fecha_fin');

                    // Función para actualizar filtros cuando cambien las fechas
                    function actualizarFiltrosDinamicos() {
                        const fechaInicio = fechaInicioInput.value;
                        const fechaFin = fechaFinInput.value;

                        console.log('=== actualizarFiltrosDinamicos LLAMADA ===');
                        console.log('fechaInicioInput exists:', !!fechaInicioInput);
                        console.log('fechaFinInput exists:', !!fechaFinInput);
                        console.log('fechaInicio value:', fechaInicio, 'length:', fechaInicio.length);
                        console.log('fechaFin value:', fechaFin, 'length:', fechaFin.length);

                        // Solo actualizar si ambas fechas están seleccionadas
                        if (!fechaInicio || !fechaFin) {
                            console.log('Fechas no completas, no se actualizan filtros');
                            return;
                        }

                        console.log('Actualizando filtros dinámicos para fechas:', fechaInicio, 'a', fechaFin);

                        // Mostrar loading en los filtros (sin reemplazar el contenido completo)
                        const filtroContainer = document.getElementById('filtroContainer');
                        if (filtroContainer) {
                            // Agregar overlay de loading sin reemplazar los filtros
                            const loadingOverlay = document.createElement('div');
                            loadingOverlay.id = 'loading-overlay';
                            loadingOverlay.className = 'position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-white bg-opacity-75';
                            loadingOverlay.style.cssText = 'z-index: 10;';
                            loadingOverlay.innerHTML = '<div class="text-center"><div class="spinner-border spinner-border-sm text-primary" role="status"></div><small class="text-muted ml-2">Actualizando filtros...</small></div>';

                            filtroContainer.style.position = 'relative';
                            filtroContainer.appendChild(loadingOverlay);
                        }

                        // Llamar a la API para obtener filtros dinámicos
                        const filtrosUrl = `/asistencia/api/filtros-dinamicos?fecha_inicio=${encodeURIComponent(fechaInicio)}&fecha_fin=${encodeURIComponent(fechaFin)}`;

                        fetch(filtrosUrl, {
                            method: 'GET',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                console.log('Filtros dinámicos obtenidos:', data.data);
                                console.log('Niveles:', data.data.niveles?.length || 0, 'Cursos:', data.data.cursos?.length || 0, 'Estudiantes:', data.data.estudiantes?.length || 0, 'Docentes:', data.data.docentes?.length || 0);
                                actualizarOpcionesFiltros(data.data);
                            } else {
                                console.error('Error al obtener filtros dinámicos:', data.message);
                                // Mostrar mensaje de error al usuario
                                const filtroContainer = document.getElementById('filtroContainer');
                                if (filtroContainer) {
                                    filtroContainer.innerHTML = '<div class="alert alert-warning"><i class="fas fa-exclamation-triangle mr-2"></i>No se pudieron cargar los filtros dinámicos. Verifica que hayas seleccionado fechas válidas y que existan registros de asistencia en ese período.</div>';
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Error al actualizar filtros dinámicos:', error);
                        });
                    }

                    console.log('Configurando event listeners para fechas...');
                    console.log('fechaInicioInput:', fechaInicioInput);
                    console.log('fechaFinInput:', fechaFinInput);

                    // Agregar event listeners para cambios en fechas
                    fechaInicioInput.addEventListener('change', function(e) {
                        console.log('🎯 Evento change en fechaInicio:', e.target.value);
                        actualizarFiltrosDinamicos();
                    });

                    fechaFinInput.addEventListener('change', function(e) {
                        console.log('🎯 Evento change en fechaFin:', e.target.value);
                        actualizarFiltrosDinamicos();
                    });

                    console.log('Event listeners configurados exitosamente');

                    // También escuchar input para actualizaciones en tiempo real
                    fechaInicioInput.addEventListener('input', function(e) {
                        console.log('Evento input en fechaInicio:', e.target.value);
                        // Solo actualizar si la fecha fin también está seleccionada
                        if (fechaFinInput.value) {
                            actualizarFiltrosDinamicos();
                        }
                    });

                    fechaFinInput.addEventListener('input', function(e) {
                        console.log('Evento input en fechaFin:', e.target.value);
                        // Solo actualizar si la fecha inicio también está seleccionada
                        if (fechaInicioInput.value) {
                            actualizarFiltrosDinamicos();
                        }
                    });

                    console.log('Event listeners configurados. Verificando fechas actuales...');

                    // Verificar si ya hay fechas seleccionadas y llamar inmediatamente
                    const fechaInicioActual = fechaInicioInput.value;
                    const fechaFinActual = fechaFinInput.value;
                    console.log('Fechas actuales - Inicio:', fechaInicioActual, 'Fin:', fechaFinActual);

                    if (fechaInicioActual && fechaFinActual) {
                        console.log('Fechas ya seleccionadas, llamando actualizarFiltrosDinamicos inmediatamente');
                        actualizarFiltrosDinamicos();
                    }
                }

                // Función global para actualizar opciones de filtros
                function actualizarOpcionesFiltros(data) {
                    console.log('Actualizando opciones de filtros con data:', data);

                    // Actualizar opciones de niveles
                    if (data.niveles) {
                        const nivelSelect = document.getElementById('nivel_id');
                        if (nivelSelect) {
                            console.log('Actualizando select de niveles, opciones actuales:', nivelSelect.options.length);
                            // Limpiar opciones existentes (excepto la primera)
                            while (nivelSelect.options.length > 1) {
                                nivelSelect.remove(1);
                            }

                            // Agregar nuevas opciones
                            data.niveles.forEach(nivel => {
                                const option = document.createElement('option');
                                option.value = nivel.nivel_id;
                                option.textContent = nivel.nombre;
                                nivelSelect.appendChild(option);
                            });

                            // Re-inicializar Select2
                            $(nivelSelect).select2('destroy').select2({
                                theme: 'bootstrap-5',
                                width: '100%',
                                placeholder: 'Seleccionar nivel...',
                                allowClear: true,
                                minimumResultsForSearch: 0
                            });
                            console.log('Niveles actualizados correctamente');
                        } else {
                            console.warn('Select de niveles no encontrado');
                        }
                    }

                    // Actualizar opciones de cursos
                    if (data.cursos) {
                        const cursoSelect = document.getElementById('curso_id');
                        if (cursoSelect) {
                            console.log('Actualizando select de cursos, opciones actuales:', cursoSelect.options.length);
                            // Limpiar opciones existentes (excepto la primera)
                            while (cursoSelect.options.length > 1) {
                                cursoSelect.remove(1);
                            }

                            // Agregar nuevas opciones
                            data.cursos.forEach(curso => {
                                const option = document.createElement('option');
                                option.value = curso.curso_id;
                                option.setAttribute('data-nivel', curso.grado?.nivel_id || '');
                                option.textContent = curso.nombre_completo || (curso.grado?.nombre + ' ' + curso.seccion?.nombre);
                                cursoSelect.appendChild(option);
                            });

                            // Re-inicializar Select2
                            $(cursoSelect).select2('destroy').select2({
                                theme: 'bootstrap-5',
                                width: '100%',
                                placeholder: 'Seleccionar curso...',
                                allowClear: true,
                                minimumResultsForSearch: 0
                            });
                            console.log('Cursos actualizados correctamente');
                        } else {
                            console.warn('Select de cursos no encontrado');
                        }
                    }

                    // Actualizar opciones de estudiantes
                    if (data.estudiantes) {
                        const estudianteSelect = document.getElementById('estudiante_id');
                        if (estudianteSelect) {
                            console.log('Actualizando select de estudiantes, opciones actuales:', estudianteSelect.options.length);
                            // Limpiar opciones existentes (excepto la primera)
                            while (estudianteSelect.options.length > 1) {
                                estudianteSelect.remove(1);
                            }

                            // Agregar nuevas opciones
                            data.estudiantes.forEach(estudiante => {
                                const option = document.createElement('option');
                                option.value = estudiante.estudiante_id;
                                option.setAttribute('data-curso', estudiante.matricula?.curso_id || '');
                                option.setAttribute('data-nivel', estudiante.matricula?.nivel_id || '');
                                option.textContent = estudiante.persona?.apellidos + ', ' + estudiante.persona?.nombres;
                                estudianteSelect.appendChild(option);
                            });

                            // Re-inicializar Select2
                            $(estudianteSelect).select2('destroy').select2({
                                theme: 'bootstrap-5',
                                width: '100%',
                                placeholder: 'Seleccionar estudiante...',
                                allowClear: true,
                                minimumResultsForSearch: 0
                            });
                            console.log('Estudiantes actualizados correctamente');
                        } else {
                            console.warn('Select de estudiantes no encontrado');
                        }
                    }

                    // Actualizar opciones de docentes
                    if (data.docentes) {
                        const docenteSelect = document.getElementById('docente_id');
                        if (docenteSelect) {
                            console.log('Actualizando select de docentes, opciones actuales:', docenteSelect.options.length);
                            // Limpiar opciones existentes (excepto la primera)
                            while (docenteSelect.options.length > 1) {
                                docenteSelect.remove(1);
                            }

                            // Agregar nuevas opciones
                            data.docentes.forEach(docente => {
                                const option = document.createElement('option');
                                option.value = docente.profesor_id;
                                option.textContent = docente.persona?.apellidos + ', ' + docente.persona?.nombres;
                                docenteSelect.appendChild(option);
                            });

                            // Re-inicializar Select2
                            $(docenteSelect).select2('destroy').select2({
                                theme: 'bootstrap-5',
                                width: '100%',
                                placeholder: 'Seleccionar docente...',
                                allowClear: true,
                                minimumResultsForSearch: 0
                            });
                            console.log('Docentes actualizados correctamente');
                        } else {
                            console.warn('Select de docentes no encontrado');
                        }
                    }

                    // Re-inicializar filtros en cascada después de actualizar las opciones
                    if (document.getElementById('tipo_reporte').value === 'comparativo') {
                        console.log('Re-inicializando filtros en cascada después de actualización dinámica');
                        setTimeout(() => {
                            configurarFiltrosCascada();
                        }, 100);
                    }

                    console.log('Filtros dinámicos actualizados exitosamente');
                }

                // Función global para configurar filtros en cascada
                function configurarFiltrosCascada() {
                    console.log('Configurando filtros en cascada...');

                    const nivelSelect = document.getElementById('nivel_id');
                    const cursoSelect = document.getElementById('curso_id');
                    const estudianteSelect = document.getElementById('estudiante_id');
                    const docenteSelect = document.getElementById('docente_id');

                    console.log('Elementos encontrados:', {
                        nivel: !!nivelSelect,
                        curso: !!cursoSelect,
                        estudiante: !!estudianteSelect,
                        docente: !!docenteSelect
                    });

                    // Si no se encontraron los elementos, salir
                    if (!nivelSelect || !cursoSelect || !estudianteSelect || !docenteSelect) {
                        console.warn('No se encontraron todos los elementos select para filtros en cascada');
                        return;
                    }

                    // Función para obtener opciones actuales (dinámicas)
                    function getOpcionesActuales(selectElement) {
                        return Array.from(selectElement.options).filter(option => option.value !== '');
                    }

                    // Función para filtrar cursos por nivel
                    function filtrarCursosPorNivel() {
                        console.log('Ejecutando filtrarCursosPorNivel');
                        if (!nivelSelect || !cursoSelect) {
                            console.warn('Elementos select no disponibles para filtrar cursos');
                            return;
                        }

                        const nivelId = nivelSelect.value;
                        console.log('Filtrando cursos por nivel:', nivelId);

                        // Limpiar opciones actuales (excepto la primera)
                        while (cursoSelect.options.length > 1) {
                            cursoSelect.remove(1);
                        }

                        // Obtener opciones actuales de cursos (filtradas por fecha)
                        const cursosDisponibles = getOpcionesActuales(cursoSelect);

                        // Agregar opciones filtradas por nivel
                        cursosDisponibles.forEach(option => {
                            if (option.value === '') return; // Mantener opción vacía

                            const cursoNivel = option.getAttribute('data-nivel');
                            console.log('Curso:', option.text, 'nivel:', cursoNivel, 'coincide:', (!nivelId || cursoNivel === nivelId));

                            if (!nivelId || cursoNivel === nivelId) {
                                const nuevaOption = option.cloneNode(true);
                                cursoSelect.appendChild(nuevaOption);
                                console.log('Agregado curso:', option.text);
                            }
                        });

                        // Limpiar estudiante si el curso seleccionado ya no está disponible
                        const cursoSeleccionado = cursoSelect.value;
                        if (cursoSeleccionado) {
                            const cursoExiste = Array.from(cursoSelect.options).some(opt => opt.value === cursoSeleccionado);
                            if (!cursoExiste) {
                                cursoSelect.value = '';
                                filtrarEstudiantesPorCurso();
                            }
                        }

                        reinicializarSelect2(cursoSelect, 'Seleccionar curso...');
                    }

                    // Función para filtrar estudiantes por curso y nivel
                    function filtrarEstudiantesPorCurso() {
                        console.log('Ejecutando filtrarEstudiantesPorCurso');
                        if (!cursoSelect || !estudianteSelect || !nivelSelect) {
                            console.warn('Elementos select no disponibles para filtrar estudiantes');
                            return;
                        }

                        const cursoId = cursoSelect.value;
                        const nivelId = nivelSelect.value;

                        console.log('Filtrando estudiantes - cursoId:', cursoId, 'nivelId:', nivelId);

                        // Limpiar opciones actuales (excepto la primera)
                        while (estudianteSelect.options.length > 1) {
                            estudianteSelect.remove(1);
                        }

                        // Obtener opciones actuales de estudiantes (filtradas por fecha)
                        const estudiantesDisponibles = getOpcionesActuales(estudianteSelect);

                        // Agregar opciones filtradas
                        estudiantesDisponibles.forEach(option => {
                            if (option.value === '') return; // Mantener opción vacía

                            const estudianteCurso = option.getAttribute('data-curso');
                            const estudianteNivel = option.getAttribute('data-nivel');
                            let incluir = true;
                            let razonExclusión = '';

                            // Si hay curso seleccionado, filtrar por curso directo
                            if (cursoId && estudianteCurso !== cursoId) {
                                incluir = false;
                                razonExclusión = `curso no coincide (${estudianteCurso} !== ${cursoId})`;
                            }
                            // Si no hay curso seleccionado pero hay nivel, filtrar por nivel
                            else if (!cursoId && nivelId && estudianteNivel !== nivelId) {
                                incluir = false;
                                razonExclusión = `nivel no coincide (${estudianteNivel} !== ${nivelId})`;
                            }
                            // Si hay curso Y nivel seleccionados, verificar que coincidan ambos
                            else if (cursoId && nivelId) {
                                if (estudianteCurso !== cursoId || estudianteNivel !== nivelId) {
                                    incluir = false;
                                    razonExclusión = `curso o nivel no coinciden`;
                                }
                            }

                            console.log(`Estudiante ${option.text}: curso=${estudianteCurso}, nivel=${estudianteNivel}, incluir=${incluir}${razonExclusión ? ', razón: ' + razonExclusión : ''}`);

                            if (incluir) {
                                const nuevaOption = option.cloneNode(true);
                                estudianteSelect.appendChild(nuevaOption);
                            }
                        });

                        reinicializarSelect2(estudianteSelect, 'Seleccionar estudiante...');
                    }

                    // Función para filtrar docentes por curso y nivel
                    function filtrarDocentesPorCursoYNivel() {
                        console.log('Ejecutando filtrarDocentesPorCursoYNivel');
                        if (!docenteSelect) {
                            console.warn('Elemento select de docentes no disponible');
                            return;
                        }

                        const cursoId = cursoSelect?.value || '';
                        const nivelId = nivelSelect?.value || '';

                        console.log('Filtrando docentes - cursoId:', cursoId, 'nivelId:', nivelId);

                        // Limpiar opciones actuales (excepto la primera)
                        while (docenteSelect.options.length > 1) {
                            docenteSelect.remove(1);
                        }

                        // Obtener opciones actuales de docentes (filtradas por fecha)
                        const docentesDisponibles = getOpcionesActuales(docenteSelect);

                        // Agregar opciones filtradas (todos los docentes disponibles pueden enseñar en cualquier curso/nivel)
                        docentesDisponibles.forEach(option => {
                            if (option.value === '') return; // Mantener opción vacía

                            // En un sistema real, aquí se filtraría por docentes que enseñan en cursos específicos
                            // Por ahora, todos los docentes disponibles pueden aparecer en cualquier combinación
                            const nuevaOption = option.cloneNode(true);
                            docenteSelect.appendChild(nuevaOption);
                        });

                        reinicializarSelect2(docenteSelect, 'Seleccionar docente...');
                    }

                    // Función para reinicializar Select2
                    function reinicializarSelect2(selectElement, placeholder) {
                        if ($(selectElement).hasClass('select2-hidden-accessible')) {
                            $(selectElement).select2('destroy');
                        }
                        $(selectElement).select2({
                            theme: 'bootstrap-5',
                            width: '100%',
                            placeholder: placeholder,
                            allowClear: true,
                            minimumResultsForSearch: 0
                        });
                    }

                    // Event listeners - Usar jQuery para Select2
                    $(nivelSelect).on('change', function() {
                        console.log('Nivel changed, value:', $(this).val());
                        filtrarCursosPorNivel();
                        filtrarEstudiantesPorCurso(); // Re-filtrar estudiantes considerando el nuevo nivel
                        filtrarDocentesPorCursoYNivel(); // Re-filtrar docentes considerando el nuevo nivel
                    });

                    $(cursoSelect).on('change', function() {
                        console.log('Curso changed, value:', $(this).val());
                        filtrarEstudiantesPorCurso();
                        filtrarDocentesPorCursoYNivel(); // Re-filtrar docentes considerando el nuevo curso
                    });

                    console.log('Filtros en cascada configurados correctamente');
                }

                document.addEventListener('DOMContentLoaded', function () {
                    // Collapse icon toggle
                    const btn = document.querySelector('[data-target="#collapseReportes"]');
                    const icon = btn.querySelector('.fas.fa-chevron-down');
                    const collapse = document.getElementById('collapseReportes');
                    collapse.addEventListener('show.bs.collapse', function () {
                        icon.classList.remove('fa-chevron-down');
                        icon.classList.add('fa-chevron-up');
                    });
                    collapse.addEventListener('hide.bs.collapse', function () {
                        icon.classList.remove('fa-chevron-up');
                        icon.classList.add('fa-chevron-down');
                    });

                    // Toggle filtros adicionales basados en tipo de reporte
                    document.getElementById('tipo_reporte').addEventListener('change', function() {
                        const filtrosAdicionales = document.getElementById('filtrosAdicionales');
                        const filtroContainer = document.getElementById('filtroContainer');
                        const tipoReporte = this.value;

                        // Limpiar el contenedor
                        filtroContainer.innerHTML = '';

                        // Mostrar filtros según el tipo de reporte seleccionado
                        if (tipoReporte === 'general') {
                            filtrosAdicionales.style.display = 'none';
                        } else {
                            filtrosAdicionales.style.display = 'flex';

                            let filterHtml = '';

                            switch(tipoReporte) {
                                case 'por_curso':
                                    filterHtml = `
                                        <div class="form-group">
                                            <label for="curso_id">Curso</label>
                                            <select class="form-control" id="curso_id" name="curso_id">
                                                <option value="">Todos los cursos</option>
                                                @foreach($cursos as $curso)
                                                <option value="{{ $curso->curso_id }}">{{ $curso->nombre_completo ?? $curso->grado->nombre . ' ' . $curso->seccion->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    `;
                                    break;
                                case 'por_estudiante':
                                    filterHtml = `
                                        <div class="form-group">
                                            <label for="estudiante_id">Estudiante</label>
                                            <select class="form-control" id="estudiante_id" name="estudiante_id">
                                                <option value="">Todos los estudiantes</option>
                                                @foreach($estudiantes as $estudiante)
                                                <option value="{{ $estudiante->estudiante_id }}">{{ $estudiante->persona->apellidos }}, {{ $estudiante->persona->nombres }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    `;
                                    break;
                                case 'por_docente':
                                    filterHtml = `
                                        <div class="form-group">
                                            <label for="docente_id">Docente</label>
                                            <select class="form-control" id="docente_id" name="docente_id">
                                                <option value="">Todos los docentes</option>
                                                @foreach($docentes as $docente)
                                                <option value="{{ $docente->profesor_id }}">{{ $docente->persona->apellidos }}, {{ $docente->persona->nombres }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    `;
                                    break;
                case 'comparativo':
                                    filterHtml = `
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="nivel_id">Nivel Educativo</label>
                                                    <select class="form-control" id="nivel_id" name="nivel_id">
                                                        <option value="">Seleccionar nivel...</option>
                                                        @foreach($niveles as $nivel)
                                                        <option value="{{ $nivel->nivel_id }}">{{ $nivel->nombre }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="curso_id">Curso</label>
                                                    <select class="form-control" id="curso_id" name="curso_id">
                                                        <option value="">Seleccionar curso...</option>
                                                @foreach($cursos as $curso)
                                                <option value="{{ $curso->curso_id }}" data-nivel="{{ $curso->grado->nivel_id }}">{{ $curso->nombre_completo ?? $curso->grado->nombre . ' ' . $curso->seccion->nombre }}</option>
                                                @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="estudiante_id">Estudiante</label>
                                                    <select class="form-control" id="estudiante_id" name="estudiante_id">
                                                        <option value="">Seleccionar estudiante...</option>
                                                        @foreach($estudiantes as $estudiante)
                                                        <option value="{{ $estudiante->estudiante_id }}" data-curso="{{ $estudiante->matricula?->curso_id ?? '' }}" data-nivel="{{ (isset($estudiante->matricula) && is_object($estudiante->matricula) && isset($estudiante->matricula->grado) && is_object($estudiante->matricula->grado)) ? $estudiante->matricula->grado->nivel_id : '' }}">{{ $estudiante->persona->apellidos }}, {{ $estudiante->persona->nombres }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="docente_id">Docente</label>
                                                    <select class="form-control" id="docente_id" name="docente_id">
                                                        <option value="">Seleccionar docente...</option>
                                                @foreach($docentes as $docente)
                                                <option value="{{ $docente->profesor_id }}">{{ $docente->persona->apellidos }}, {{ $docente->persona->nombres }}</option>
                                                @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    `;
                                    break;
                            }

                            filtroContainer.innerHTML = filterHtml;

                            // Re-inicializar select2 para los nuevos elementos
                            setTimeout(() => {
                                $('.form-control').each(function() {
                                    if ($(this).attr('id') !== 'tipo_reporte' && $(this).attr('id') !== 'formato' && $(this).attr('id') !== 'fecha_inicio' && $(this).attr('id') !== 'fecha_fin') {
                                        $(this).select2({
                                            theme: 'bootstrap-5',
                                            width: '100%',
                                            placeholder: function() {
                                                return $(this).find('option:first').text() || 'Seleccionar...';
                                            },
                                            allowClear: true,
                                            minimumResultsForSearch: 0  // Always show search box
                                        });
                                    }
                                });

                                // Configurar filtros en cascada para reporte comparativo
                                if (tipoReporte === 'comparativo') {
                                    // Mostrar mensaje inicial esperando fechas
                                    filtroContainer.innerHTML = '<div class="alert alert-info"><i class="fas fa-calendar-alt mr-2"></i>Selecciona las fechas de inicio y fin para cargar las opciones de filtro disponibles.</div>';

                                    // Asegurar que los event listeners de fechas estén activos
                                    setTimeout(() => {
                                        console.log('Configurando event listeners para reporte comparativo...');
                                        agregarEventListenersFiltrosDinamicos();
                                    }, 200);

                                    // Agregar botón para probar filtros
                                    setTimeout(() => {
                                        const filtroContainer = document.getElementById('filtroContainer');
                                        if (filtroContainer) {
                                            const testButton = document.createElement('button');
                                            testButton.type = 'button';
                                            testButton.className = 'btn btn

                function initCharts() {
                    // Datos de tendencia mensual desde PHP (ya filtrados)
                    const tendenciaData = @json($tendenciaMensual);
                    const labels = tendenciaData.map(item => item.mes);
                    const porcentajes = tendenciaData.map(item => item.porcentaje);

                    // Tendencia mensual
                    const ctxTendencia = document.getElementById('tendenciaChart').getContext('2d');
                    window.tendenciaChartInstance = new Chart(ctxTendencia, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Asistencia Promedio (%)',
                                data: porcentajes,
                                borderColor: '#667eea',
                                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                                tension: 0.4,
                                fill: true
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'top',
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: false,
                                    max: 100
                                }
                            }
                        }
                    });

                    // Datos de distribución desde PHP (ya filtrados)
                    const distribucionData = @json($distribucionTipos);

                    // Distribución por tipo
                    const ctxDistribucion = document.getElementById('distribucionChart').getContext('2d');
                    window.distribucionChartInstance = new Chart(ctxDistribucion, {
                        type: 'doughnut',
                        data: {
                            labels: ['Presente', 'Ausente', 'Tarde', 'Justificado'],
                            datasets: [{
                                data: [distribucionData.presente, distribucionData.ausente, distribucionData.tarde, distribucionData.justificado],
                                backgroundColor: [
                                    '#28a745',
                                    '#dc3545',
                                    '#ffc107',
                                    '#17a2b8'
                                ],
                                borderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                }
                            }
                        }
                    });

                    // Inicializar gráficos adicionales
                    initGraficoSemanal();
                    initRankingEstudiantes();

                // Agregar event listeners para actualizar gráficos y filtros dinámicos
                agregarEventListenersFiltros();
                agregarEventListenersFiltrosDinamicos();

                function agregarEventListenersFiltrosDinamicos() {
                    // Escuchar cambios en las fechas para actualizar filtros dinámicos
                    const fechaInicioInput = document.getElementById('fecha_inicio');
                    const fechaFinInput = document.getElementById('fecha_fin');

                    // Función para actualizar filtros cuando cambien las fechas
                    function actualizarFiltrosDinamicos() {
                        const fechaInicio = fechaInicioInput.value;
                        const fechaFin = fechaFinInput.value;

                        console.log('=== actualizarFiltrosDinamicos LLAMADA ===');
                        console.log('fechaInicioInput exists:', !!fechaInicioInput);
                        console.log('fechaFinInput exists:', !!fechaFinInput);
                        console.log('fechaInicio value:', fechaInicio, 'length:', fechaInicio.length);
                        console.log('fechaFin value:', fechaFin, 'length:', fechaFin.length);

                        // Solo actualizar si ambas fechas están seleccionadas
                        if (!fechaInicio || !fechaFin) {
                            console.log('Fechas no completas, no se actualizan filtros');
                            return;
                        }

                        console.log('Actualizando filtros dinámicos para fechas:', fechaInicio, 'a', fechaFin);

                        // Mostrar loading en los filtros
                        const filtroContainer = document.getElementById('filtroContainer');
                        if (filtroContainer) {
                            filtroContainer.innerHTML = '<div class="text-center py-3"><div class="spinner-border spinner-border-sm text-primary" role="status"></div><small class="text-muted ml-2">Actualizando filtros...</small></div>';
                        }

                        // Llamar a la API para obtener filtros dinámicos
                        const filtrosUrl = `/asistencia/api/filtros-dinamicos?fecha_inicio=${encodeURIComponent(fechaInicio)}&fecha_fin=${encodeURIComponent(fechaFin)}`;

                        fetch(filtrosUrl, {
                            method: 'GET',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                console.log('Filtros dinámicos obtenidos:', data.data);
                                console.log('Niveles:', data.data.niveles?.length || 0, 'Cursos:', data.data.cursos?.length || 0, 'Estudiantes:', data.data.estudiantes?.length || 0, 'Docentes:', data.data.docentes?.length || 0);
                                actualizarOpcionesFiltros(data.data);
                            } else {
                                console.error('Error al obtener filtros dinámicos:', data.message);
                                // Mostrar mensaje de error al usuario
                                const filtroContainer = document.getElementById('filtroContainer');
                                if (filtroContainer) {
                                    filtroContainer.innerHTML = '<div class="alert alert-warning"><i class="fas fa-exclamation-triangle mr-2"></i>No se pudieron cargar los filtros dinámicos. Verifica que hayas seleccionado fechas válidas y que existan registros de asistencia en ese período.</div>';
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Error al actualizar filtros dinámicos:', error);
                        });
                    }

                    console.log('Configurando event listeners para fechas...');
                    console.log('fechaInicioInput:', fechaInicioInput);
                    console.log('fechaFinInput:', fechaFinInput);

                    // Agregar event listeners para cambios en fechas
                    fechaInicioInput.addEventListener('change', function(e) {
                        console.log('🎯 Evento change en fechaInicio:', e.target.value);
                        actualizarFiltrosDinamicos();
                    });

                    fechaFinInput.addEventListener('change', function(e) {
                        console.log('🎯 Evento change en fechaFin:', e.target.value);
                        actualizarFiltrosDinamicos();
                    });

                    console.log('Event listeners configurados exitosamente');

                    // También escuchar input para actualizaciones en tiempo real
                    fechaInicioInput.addEventListener('input', function(e) {
                        console.log('Evento input en fechaInicio:', e.target.value);
                        // Solo actualizar si la fecha fin también está seleccionada
                        if (fechaFinInput.value) {
                            actualizarFiltrosDinamicos();
                        }
                    });

                    fechaFinInput.addEventListener('input', function(e) {
                        console.log('Evento input en fechaFin:', e.target.value);
                        // Solo actualizar si la fecha inicio también está seleccionada
                        if (fechaInicioInput.value) {
                            actualizarFiltrosDinamicos();
                        }
                    });

                    console.log('Event listeners configurados. Verificando fechas actuales...');

                    // Verificar si ya hay fechas seleccionadas y llamar inmediatamente
                    const fechaInicioActual = fechaInicioInput.value;
                    const fechaFinActual = fechaFinInput.value;
                    console.log('Fechas actuales - Inicio:', fechaInicioActual, 'Fin:', fechaFinActual);

                    if (fechaInicioActual && fechaFinActual) {
                        console.log('Fechas ya seleccionadas, llamando actualizarFiltrosDinamicos inmediatamente');
                        actualizarFiltrosDinamicos();
                    }
                }

                function actualizarOpcionesFiltros(data) {
                    // Actualizar opciones de niveles
                    if (data.niveles) {
                        const nivelSelect = document.getElementById('nivel_id');
                        if (nivelSelect) {
                            // Limpiar opciones existentes (excepto la primera)
                            while (nivelSelect.options.length > 1) {
                                nivelSelect.remove(1);
                            }

                            // Agregar nuevas opciones
                            data.niveles.forEach(nivel => {
                                const option = document.createElement('option');
                                option.value = nivel.nivel_id;
                                option.textContent = nivel.nombre;
                                nivelSelect.appendChild(option);
                            });

                            // Re-inicializar Select2
                            $(nivelSelect).select2('destroy').select2({
                                theme: 'bootstrap-5',
                                width: '100%',
                                placeholder: 'Seleccionar nivel...',
                                allowClear: true,
                                minimumResultsForSearch: 0
                            });
                        }
                    }

                    // Actualizar opciones de cursos
                    if (data.cursos) {
                        const cursoSelect = document.getElementById('curso_id');
                        if (cursoSelect) {
                            // Limpiar opciones existentes (excepto la primera)
                            while (cursoSelect.options.length > 1) {
                                cursoSelect.remove(1);
                            }

                            // Agregar nuevas opciones
                            data.cursos.forEach(curso => {
                                const option = document.createElement('option');
                                option.value = curso.curso_id;
                                option.setAttribute('data-nivel', curso.grado?.nivel_id || '');
                                option.textContent = curso.nombre_completo || (curso.grado?.nombre + ' ' + curso.seccion?.nombre);
                                cursoSelect.appendChild(option);
                            });

                            // Re-inicializar Select2
                            $(cursoSelect).select2('destroy').select2({
                                theme: 'bootstrap-5',
                                width: '100%',
                                placeholder: 'Seleccionar curso...',
                                allowClear: true,
                                minimumResultsForSearch: 0
                            });
                        }
                    }

                    // Actualizar opciones de estudiantes
                    if (data.estudiantes) {
                        const estudianteSelect = document.getElementById('estudiante_id');
                        if (estudianteSelect) {
                            // Limpiar opciones existentes (excepto la primera)
                            while (estudianteSelect.options.length > 1) {
                                estudianteSelect.remove(1);
                            }

                            // Agregar nuevas opciones
                            data.estudiantes.forEach(estudiante => {
                                const option = document.createElement('option');
                                option.value = estudiante.estudiante_id;
                                option.setAttribute('data-curso', estudiante.matricula?.curso_id || '');
                                option.setAttribute('data-nivel', estudiante.matricula?.nivel_id || '');
                                option.textContent = estudiante.persona?.apellidos + ', ' + estudiante.persona?.nombres;
                                estudianteSelect.appendChild(option);
                            });

                            // Re-inicializar Select2
                            $(estudianteSelect).select2('destroy').select2({
                                theme: 'bootstrap-5',
                                width: '100%',
                                placeholder: 'Seleccionar estudiante...',
                                allowClear: true,
                                minimumResultsForSearch: 0
                            });
                        }
                    }

                    // Actualizar opciones de docentes
                    if (data.docentes) {
                        const docenteSelect = document.getElementById('docente_id');
                        if (docenteSelect) {
                            // Limpiar opciones existentes (excepto la primera)
                            while (docenteSelect.options.length > 1) {
                                docenteSelect.remove(1);
                            }

                            // Agregar nuevas opciones
                            data.docentes.forEach(docente => {
                                const option = document.createElement('option');
                                option.value = docente.profesor_id;
                                option.textContent = docente.persona?.apellidos + ', ' + docente.persona?.nombres;
                                docenteSelect.appendChild(option);
                            });

                            // Re-inicializar Select2
                            $(docenteSelect).select2('destroy').select2({
                                theme: 'bootstrap-5',
                                width: '100%',
                                placeholder: 'Seleccionar docente...',
                                allowClear: true,
                                minimumResultsForSearch: 0
                            });
                        }
                    }

                    // Re-inicializar filtros en cascada después de actualizar las opciones
                    if (document.getElementById('tipo_reporte').value === 'comparativo') {
                        console.log('Re-inicializando filtros en cascada después de actualización dinámica');
                        setTimeout(() => {
                            configurarFiltrosCascada();
                        }, 100);
                    }

                    console.log('Filtros dinámicos actualizados exitosamente');
                }
                }

                function initGraficoSemanal() {
                    // Datos simulados para asistencia por día de la semana
                    // En un sistema real, estos datos vendrían de la API
                    const diasSemana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];
                    const asistenciaSemanal = [92, 88, 95, 90, 87]; // Datos de ejemplo

                    const ctxSemanal = document.getElementById('semanalChart').getContext('2d');
                    window.semanalChartInstance = new Chart(ctxSemanal, {
                        type: 'bar',
                        data: {
                            labels: diasSemana,
                            datasets: [{
                                label: 'Asistencia (%)',
                                data: asistenciaSemanal,
                                backgroundColor: [
                                    'rgba(54, 162, 235, 0.8)',
                                    'rgba(255, 206, 86, 0.8)',
                                    'rgba(75, 192, 192, 0.8)',
                                    'rgba(153, 102, 255, 0.8)',
                                    'rgba(255, 159, 64, 0.8)'
                                ],
                                borderColor: [
                                    'rgba(54, 162, 235, 1)',
                                    'rgba(255, 206, 86, 1)',
                                    'rgba(75, 192, 192, 1)',
                                    'rgba(153, 102, 255, 1)',
                                    'rgba(255, 159, 64, 1)'
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'top',
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    max: 100
                                }
                            }
                        }
                    });
                }

                function initRankingEstudiantes() {
                    // Simular ranking de estudiantes (en un sistema real vendría de la API)
                    const rankingContainer = document.getElementById('rankingEstudiantes');

                    // Datos de ejemplo para ranking
                    const estudiantesRanking = [
                        { nombre: 'Ana García', curso: '1° Básico A', porcentaje: 98.5 },
                        { nombre: 'Carlos López', curso: '2° Básico B', porcentaje: 97.2 },
                        { nombre: 'María Rodríguez', curso: '3° Básico A', porcentaje: 96.8 },
                        { nombre: 'Juan Pérez', curso: '1° Básico B', porcentaje: 95.9 },
                        { nombre: 'Laura Martínez', curso: '2° Básico A', porcentaje: 95.1 }
                    ];

                    let rankingHtml = '<div class="list-group">';

                    estudiantesRanking.forEach((estudiante, index) => {
                        const medalClass = index === 0 ? 'text-warning' :
                                         index === 1 ? 'text-secondary' :
                                         index === 2 ? 'text-warning' : 'text-muted';
                        const medalIcon = index === 0 ? 'fa-trophy' :
                                        index === 1 ? 'fa-medal' :
                                        index === 2 ? 'fa-award' : 'fa-star';

                        rankingHtml += `
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <span class="badge badge-pill badge-primary mr-3">${index + 1}</span>
                                    <i class="fas ${medalIcon} ${medalClass} mr-2"></i>
                                    <div>
                                        <div class="font-weight-bold">${estudiante.nombre}</div>
                                        <small class="text-muted">${estudiante.curso}</small>
                                    </div>
                                </div>
                                <span class="badge badge-success">${estudiante.porcentaje}%</span>
                            </div>
                        `;
                    });

                    rankingHtml += '</div>';
                    rankingContainer.innerHTML = rankingHtml;
                }



                function agregarEventListenersFiltros() {
                    // Elementos de filtro que deben actualizar los gráficos
                    const elementosFiltro = [
                        'fecha_inicio',
                        'fecha_fin',
                        'tipo_reporte',
                        'nivel_id',
                        'curso_id',
                        'estudiante_id',
                        'docente_id'
                    ];

                    elementosFiltro.forEach(id => {
                        const elemento = document.getElementById(id);
                        if (elemento) {
                            elemento.addEventListener('change', function() {
                                actualizarGraficosDesdeFiltros();
                            });

                            // Para inputs de fecha también escuchar input
                            if (elemento.type === 'date') {
                                elemento.addEventListener('input', function() {
                                    actualizarGraficosDesdeFiltros();
                                });
                            }
                        }
                    });
                }

                function actualizarGraficosDesdeFiltros() {
                    // Recopilar valores actuales de filtros
                    const fechaInicio = document.getElementById('fecha_inicio').value;
                    const fechaFin = document.getElementById('fecha_fin').value;
                    const tipoReporte = document.getElementById('tipo_reporte').value;
                    const nivelId = document.getElementById('nivel_id')?.value || '';
                    const cursoId = document.getElementById('curso_id')?.value || '';
                    const estudianteId = document.getElementById('estudiante_id')?.value || '';
                    const docenteId = document.getElementById('docente_id')?.value || '';

                    // Solo actualizar si tenemos fechas válidas
                    if (!fechaInicio || !fechaFin) {
                        return;
                    }

                    // Llamar a la API para obtener estadísticas filtradas
                    const estadisticasUrl = `/asistencia/api/estadisticas-filtradas?fecha_inicio=${encodeURIComponent(fechaInicio)}&fecha_fin=${encodeURIComponent(fechaFin)}&tipo_reporte=${encodeURIComponent(tipoReporte)}&nivel_id=${encodeURIComponent(nivelId)}&curso_id=${encodeURIComponent(cursoId)}&estudiante_id=${encodeURIComponent(estudianteId)}&docente_id=${encodeURIComponent(docenteId)}`;

                    fetch(estadisticasUrl, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                        }
                    })
                    .then(response => response.json())
                    .then(estadisticasData => {
                        if (estadisticasData.success) {
                            actualizarGraficos(estadisticasData.tendencia_mensual, estadisticasData.distribucion_tipos);
                        }
                    })
                    .catch(error => {
                        console.error('Error al actualizar gráficos:', error);
                    });
                }

                function generarReporte() {
                    // Validar que se haya seleccionado un período
                    const fechaInicio = document.getElementById('fecha_inicio').value;
                    const fechaFin = document.getElementById('fecha_fin').value;

                    if (!fechaInicio || !fechaFin) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Período requerido',
                            text: 'Por favor selecciona las fechas de inicio y fin para generar el reporte.',
                            confirmButtonText: 'Entendido'
                        });
                        return;
                    }

                    // Recopilar todos los filtros adicionales
                    const tipoReporte = document.getElementById('tipo_reporte').value;
                    const nivelId = document.getElementById('nivel_id')?.value || '';
                    const cursoId = document.getElementById('curso_id')?.value || '';
                    const estudianteId = document.getElementById('estudiante_id')?.value || '';
                    const docenteId = document.getElementById('docente_id')?.value || '';

                    // Mostrar loading en la vista previa y expandir la sección
                    document.getElementById('reportePreview').style.display = 'block';
                    const collapsePreview = document.getElementById('collapsePreview');
                    if (collapsePreview && !collapsePreview.classList.contains('show')) {
                        const previewCollapse = new bootstrap.Collapse(collapsePreview, {
                            show: true
                        });
                    }
                    const reporteContent = document.getElementById('reporteContent');
                    reporteContent.innerHTML = `
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Cargando...</span>
                            </div>
                            <p class="mt-2 mb-3">Generando vista previa del reporte...</p>

                            <!-- Indicadores de Carga (5 puntos de colores animados) -->
                            <div class="mt-3">
                                <div class="d-flex justify-content-center align-items-center">
                                    <div class="loading-dots">
                                        <span class="dot dot-1"></span>
                                        <span class="dot dot-2"></span>
                                        <span class="dot dot-3"></span>
                                        <span class="dot dot-4"></span>
                                        <span class="dot dot-5"></span>
                                    </div>
                                </div>
                            </div>

                            <style>
                            .loading-dots {
                                display: flex;
                                gap: 8px;
                                align-items: center;
                            }

                            .dot {
                                width: 12px;
                                height: 12px;
                                border-radius: 50%;
                                background-color: #e9ecef;
                                animation: loadingPulse 1.5s ease-in-out infinite;
                            }

                            .dot-1 { animation-delay: 0s; }
                            .dot-2 { animation-delay: 0.2s; }
                            .dot-3 { animation-delay: 0.4s; }
                            .dot-4 { animation-delay: 0.6s; }
                            .dot-5 { animation-delay: 0.8s; }

                            @keyframes loadingPulse {
                                0%, 80%, 100% {
                                    transform: scale(0.8);
                                    background-color: #e9ecef;
                                }
                                40% {
                                    transform: scale(1.2);
                                    background-color: #007bff;
                                }
                            }
                            </style>
                        </div>
                    `;

                    // Hacer petición AJAX para obtener estadísticas filtradas
                    const estadisticasUrl = `/asistencia/api/estadisticas-filtradas?fecha_inicio=${encodeURIComponent(fechaInicio)}&fecha_fin=${encodeURIComponent(fechaFin)}&tipo_reporte=${encodeURIComponent(tipoReporte)}&nivel_id=${encodeURIComponent(nivelId)}&curso_id=${encodeURIComponent(cursoId)}&estudiante_id=${encodeURIComponent(estudianteId)}&docente_id=${encodeURIComponent(docenteId)}`;

                    fetch(estadisticasUrl, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                        }
                    })
                    .then(response => response.json())
                    .then(estadisticasData => {
                        if (estadisticasData.success) {
                            // Actualizar gráficos con datos filtrados
                            actualizarGraficos(estadisticasData.tendencia_mensual, estadisticasData.distribucion_tipos);

                            // Construir URL para obtener datos de tabla
                            let tablaUrl = `/asistencia/api/tabla-asistencias?fecha_inicio=${encodeURIComponent(fechaInicio)}&fecha_fin=${encodeURIComponent(fechaFin)}&per_page=1000&tipo_reporte=${encodeURIComponent(tipoReporte)}&nivel_id=${encodeURIComponent(nivelId)}&curso_id=${encodeURIComponent(cursoId)}&estudiante_id=${encodeURIComponent(estudianteId)}&docente_id=${encodeURIComponent(docenteId)}`;

                            // Obtener datos de tabla para estadísticas detalladas
                            return fetch(tablaUrl, {
                                method: 'GET',
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                                }
                            });
                        } else {
                            throw new Error(estadisticasData.message || 'Error al obtener estadísticas');
                        }
                    })
                    .then(response => response.json())
                    .then(tablaData => {
                        if (tablaData.success) {
                            const estadisticas = tablaData.estadisticas;
                            const estadisticasAdicionales = tablaData.estadisticas_adicionales || {};
                            const estudiantesRiesgo = estadisticasAdicionales.estudiantes_riesgo || [];

                            const totalRegistros = estadisticas.total_registros;
                            const totalEstudiantesUnicos = estadisticas.total_estudiantes_unicos || 0;
                            const diasAnalizados = estadisticas.dias_analizados || 0;

                            // Check if no records exist
                            if (totalRegistros === 0) {
                                reporteContent.innerHTML = `
                                    <div class="alert alert-warning">
                                        <i class="fas fa-info-circle mr-2"></i>
                                        No hay registros de asistencia para el período seleccionado (${fechaInicio} - ${fechaFin}).
                                    </div>
                                    <div class="text-center py-4">
                                        <div class="card">
                                            <div class="card-body">
                                                <i class="fas fa-database fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">Sin Registros</h5>
                                                <p class="text-muted">No se encontraron registros de asistencia en el período especificado.</p>
                                                <p class="text-muted small">Intenta seleccionar un período diferente o verifica que existan registros de asistencia.</p>
                                            </div>
                                        </div>
                                    </div>
                                `;

                                // Disable export button when no records
                                document.getElementById('exportLink').classList.add('disabled');
                                document.getElementById('exportLink').style.pointerEvents = 'none';
                                document.getElementById('exportLink').style.opacity = '0.5';
                                return;
                            }

                            // Enable export button when records exist
                            document.getElementById('exportLink').classList.remove('disabled');
                            document.getElementById('exportLink').style.pointerEvents = '';
                            document.getElementById('exportLink').style.opacity = '';

                            const presentes = estadisticas.total_presentes;
                            const ausentes = estadisticas.total_ausentes;
                            const tardanzas = estadisticas.total_tardanzas || 0;
                            const justificados = estadisticas.total_justificados || 0;

                            // Build filter information
                            let filtroInfo = '';
                            if (tipoReporte !== 'general') {
                                filtroInfo = '<div class="alert alert-primary mt-2"><strong>Filtros aplicados:</strong><br>';

                                switch(tipoReporte) {
                                    case 'por_estudiante':
                                        if (estudianteId) {
                                            const estudianteOption = document.querySelector('#estudiante_id option[value="' + estudianteId + '"]');
                                            if (estudianteOption) {
                                                filtroInfo += 'Estudiante: ' + estudianteOption.textContent;
                                            }
                                        }
                                        break;
                                    case 'por_curso':
                                        if (cursoId) {
                                            const cursoOption = document.querySelector('#curso_id option[value="' + cursoId + '"]');
                                            if (cursoOption) {
                                                filtroInfo += 'Curso: ' + cursoOption.textContent;
                                            }
                                        }
                                        break;
                                    case 'por_docente':
                                        if (docenteId) {
                                            const docenteOption = document.querySelector('#docente_id option[value="' + docenteId + '"]');
                                            if (docenteOption) {
                                                filtroInfo += 'Docente: ' + docenteOption.textContent;
                                            }
                                        }
                                        break;
                                    case 'comparativo':
                                        let filtrosComparativo = [];
                                        if (nivelId) {
                                            const nivelOption = document.querySelector('#nivel_id option[value="' + nivelId + '"]');
                                            if (nivelOption) {
                                                filtrosComparativo.push('Nivel: ' + nivelOption.textContent);
                                            }
                                        }
                                        if (cursoId) {
                                            const cursoOption = document.querySelector('#curso_id option[value="' + cursoId + '"]');
                                            if (cursoOption) {
                                                filtrosComparativo.push('Curso: ' + cursoOption.textContent);
                                            }
                                        }
                                        if (estudianteId) {
                                            const estudianteOption = document.querySelector('#estudiante_id option[value="' + estudianteId + '"]');
                                            if (estudianteOption) {
                                                filtrosComparativo.push('Estudiante: ' + estudianteOption.textContent);
                                            }
                                        }
                                        if (docenteId) {
                                            const docenteOption = document.querySelector('#docente_id option[value="' + docenteId + '"]');
                                            if (docenteOption) {
                                                filtrosComparativo.push('Docente: ' + docenteOption.textContent);
                                            }
                                        }
                                        filtroInfo += filtrosComparativo.join('<br>');
                                        break;
                                }

                                filtroInfo += '</div>';
                            }

                            reporteContent.innerHTML = `
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Vista previa generada exitosamente para el período ${fechaInicio} - ${fechaFin}. Utiliza el botón "Exportar" para descargar el archivo completo.
                                </div>
                                ${filtroInfo}
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5><i class="fas fa-chart-bar mr-2"></i>Resumen Ejecutivo</h5>
                                        <ul class="list-group">
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                Total de Registros Analizados
                                                <span class="badge badge-primary badge-pill">${totalRegistros}</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                Estudiantes Únicos
                                                <span class="badge badge-secondary badge-pill">${estadisticasAdicionales.total_estudiantes_unicos || 0}</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                Promedio de Asistencia General
                                                <span class="badge badge-success badge-pill">${estadisticas.porcentaje_asistencia}</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                Asistencia Diaria Promedio
                                                <span class="badge badge-info badge-pill">${estadisticasAdicionales.promedio_asistencia_diaria || 0}%</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                Total de Presentes
                                                <span class="badge badge-success badge-pill">${presentes}</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                Total de Ausentes
                                                <span class="badge badge-danger badge-pill">${ausentes}</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                Total de Tardanzas
                                                <span class="badge badge-warning badge-pill">${tardanzas}</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                Total de Justificados
                                                <span class="badge badge-info badge-pill">${justificados}</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                Días Analizados
                                                <span class="badge badge-light badge-pill">${estadisticasAdicionales.dias_analizados || 0}</span>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <h5><i class="fas fa-exclamation-triangle mr-2 text-warning"></i>Alertas de Riesgo</h5>
                                        ${estudiantesRiesgo.length > 0 ?
                                            `<div class="alert alert-warning">
                                                <strong>${estudiantesRiesgo.length} estudiante(s) en riesgo</strong> (asistencia < 70%)
                                                <ul class="mb-0 mt-2">
                                                    ${estudiantesRiesgo.slice(0, 5).map(est =>
                                                        `<li><small>${est.nombre} (${est.curso}) - ${est.porcentaje}%</small></li>`
                                                    ).join('')}
                                                    ${estudiantesRiesgo.length > 5 ? `<li><small>...y ${estudiantesRiesgo.length - 5} más</small></li>` : ''}
                                                </ul>
                                            </div>` :
                                            `<div class="alert alert-success">
                                                <i class="fas fa-check-circle mr-2"></i>
                                                No hay estudiantes en riesgo de deserción por asistencia baja.
                                            </div>`
                                        }

                                        <h6 class="mt-3"><i class="fas fa-chart-pie mr-2"></i>Distribución por Tipo:</h6>
                                        <div class="row text-center">
                                            <div class="col-3">
                                                <div class="card border-success">
                                                    <div class="card-body p-2">
                                                        <h6 class="text-success">${presentes}</h6>
                                                        <small class="text-muted">Presentes</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="card border-danger">
                                                    <div class="card-body p-2">
                                                        <h6 class="text-danger">${ausentes}</h6>
                                                        <small class="text-muted">Ausentes</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="card border-warning">
                                                    <div class="card-body p-2">
                                                        <h6 class="text-warning">${tardanzas}</h6>
                                                        <small class="text-muted">Tardanzas</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="card border-info">
                                                    <div class="card-body p-2">
                                                        <h6 class="text-info">${justificados}</h6>
                                                        <small class="text-muted">Justif.</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-3">
                                            <h6><i class="fas fa-lightbulb mr-2 text-primary"></i>Insights Inteligentes:</h6>
                                            <div class="alert alert-light">
                                                <small>
                                                    ${generarInsights(totalRegistros, estadisticasAdicionales, estudiantesRiesgo.length)}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                        } else {
                            throw new Error(tablaData.message || 'Error al obtener datos de tabla');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        reporteContent.innerHTML = `
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                Error al generar la vista previa: ${error.message || 'Error desconocido'}
                            </div>
                        `;
                    });

                    // Scroll suave hacia la vista previa
                    document.getElementById('reportePreview').scrollIntoView({ behavior: 'smooth' });
                }

                function actualizarGraficos(tendenciaMensual, distribucionTipos) {
                    // Actualizar gráfico de tendencia mensual
                    const ctxTendencia = document.getElementById('tendenciaChart').getContext('2d');
                    const labels = tendenciaMensual.map(item => item.mes);
                    const porcentajes = tendenciaMensual.map(item => item.porcentaje);

                    if (window.tendenciaChartInstance) {
                        window.tendenciaChartInstance.destroy();
                    }

                    window.tendenciaChartInstance = new Chart(ctxTendencia, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Asistencia Promedio (%)',
                                data: porcentajes,
                                borderColor: '#667eea',
                                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                                tension: 0.4,
                                fill: true
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'top',
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: false,
                                    max: 100
                                }
                            }
                        }
                    });

                    // Actualizar gráfico de distribución por tipo
                    const ctxDistribucion = document.getElementById('distribucionChart').getContext('2d');

                    if (window.distribucionChartInstance) {
                        window.distribucionChartInstance.destroy();
                    }

                    window.distribucionChartInstance = new Chart(ctxDistribucion, {
                        type: 'doughnut',
                        data: {
                            labels: ['Presente', 'Ausente', 'Tarde', 'Justificado'],
                            datasets: [{
                                data: [
                                    distribucionTipos.presente || 0,
                                    distribucionTipos.ausente || 0,
                                    distribucionTipos.tarde || 0,
                                    distribucionTipos.justificado || 0
                                ],
                                backgroundColor: [
                                    '#28a745',
                                    '#dc3545',
                                    '#ffc107',
                                    '#17a2b8'
                                ],
                                borderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                }
                            }
                        }
                    });
                }

                function generarInsights(totalRegistros, estadisticasAdicionales, estudiantesRiesgo) {
                    let insights = [];

                    if (totalRegistros === 0) {
                        return "No hay suficientes datos para generar insights.";
                    }

                    const promedioDiario = estadisticasAdicionales.promedio_asistencia_diaria || 0;
                    const estudiantesUnicos = estadisticasAdicionales.total_estudiantes_unicos || 0;
                    const diasAnalizados = estadisticasAdicionales.dias_analizados || 0;

                    if (promedioDiario >= 90) {
                        insights.push("Excelente tasa de asistencia general (≥90%).");
                    } else if (promedioDiario >= 80) {
                        insights.push("Buena tasa de asistencia general (80-89%).");
                    } else if (promedioDiario >= 70) {
                        insights.push("Tasa de asistencia aceptable (70-79%), se recomienda monitoreo.");
                    } else {
                        insights.push("Tasa de asistencia baja (<70%), requiere atención inmediata.");
                    }

                    if (estudiantesRiesgo > 0) {
                        insights.push(`${estudiantesRiesgo} estudiante(s) identificado(s) con riesgo de deserción.`);
                    }

                    if (diasAnalizados > 30) {
                        insights.push("Análisis a largo plazo permite identificar tendencias estacionales.");
                    }

                    if (estudiantesUnicos > 0 && diasAnalizados > 0) {
                        const registrosPromedio = totalRegistros / estudiantesUnicos;
                        if (registrosPromedio > diasAnalizados * 0.8) {
                            insights.push("Cobertura de registro muy buena (>80% de días analizados).");
                        }
                    }

                    return insights.length > 0 ? insights.join(" ") : "Análisis completado exitosamente.";
                }

                function setPeriodo(tipo) {
                    const hoy = new Date();
                    let fechaInicio, fechaFin;

                    switch(tipo) {
                        case 'hoy':
                            fechaInicio = fechaFin = hoy;
                            break;
                        case 'ayer':
                            const ayer = new Date(hoy);
                            ayer.setDate(hoy.getDate() - 1);
                            fechaInicio = fechaFin = ayer;
                            break;
                        case 'ultimos7':
                            fechaInicio = new Date(hoy);
                            fechaInicio.setDate(hoy.getDate() - 6); // 7 días incluyendo hoy
                            fechaFin = hoy;
                            break;
                        case 'ultimos30':
                            fechaInicio = new Date(hoy);
                            fechaInicio.setDate(hoy.getDate() - 29); // 30 días incluyendo hoy
                            fechaFin = hoy;
                            break;
                        case 'ultimos90':
                            fechaInicio = new Date(hoy);
                            fechaInicio.setDate(hoy.getDate() - 89); // 90 días incluyendo hoy
                            fechaFin = hoy;
                            break;
                        case 'esteMes':
                            fechaInicio = new Date(hoy.getFullYear(), hoy.getMonth(), 1);
                            fechaFin = new Date(hoy.getFullYear(), hoy.getMonth() + 1, 0);
                            break;
                        case 'mesAnterior':
                            fechaInicio = new Date(hoy.getFullYear(), hoy.getMonth() - 1, 1);
                            fechaFin = new Date(hoy.getFullYear(), hoy.getMonth(), 0);
                            break;
                        case 'esteAnio':
                            fechaInicio = new Date(hoy.getFullYear(), 0, 1);
                            fechaFin = new Date(hoy.getFullYear(), 11, 31);
                            break;
                        case 'anioAnterior':
                            fechaInicio = new Date(hoy.getFullYear() - 1, 0, 1);
                            fechaFin = new Date(hoy.getFullYear() - 1, 11, 31);
                            break;
                    }

                    // Formatear fechas para inputs HTML (YYYY-MM-DD)
                    const fechaInicioStr = fechaInicio.toISOString().split('T')[0];
                    const fechaFinStr = fechaFin.toISOString().split('T')[0];

                    document.getElementById('fecha_inicio').value = fechaInicioStr;
                    document.getElementById('fecha_fin').value = fechaFinStr;
                }

                function limpiarFechas() {
                    document.getElementById('fecha_inicio').value = '';
                    document.getElementById('fecha_fin').value = '';
                }

                function configurarFiltrosCascada() {
                    const nivelSelect = document.getElementById('nivel_id');
                    const cursoSelect = document.getElementById('curso_id');
                    const estudianteSelect = document.getElementById('estudiante_id');
                    const docenteSelect = document.getElementById('docente_id');

                    // Función para obtener opciones actuales (dinámicas)
                    function getOpcionesActuales(selectElement) {
                        return Array.from(selectElement.options).filter(option => option.value !== '');
                    }

                    // Capturar las opciones originales para cascada
                    let cursosOriginales = [];
                    let estudiantesOriginales = [];
                    let docentesOriginales = [];

                    function actualizarOpcionesOriginales() {
                        cursosOriginales = getOpcionesActuales(cursoSelect);
                        estudiantesOriginales = getOpcionesActuales(estudianteSelect);
                        docentesOriginales = getOpcionesActuales(docenteSelect);
                        console.log('Opciones originales actualizadas:', {
                            cursos: cursosOriginales.length,
                            estudiantes: estudiantesOriginales.length,
                            docentes: docentesOriginales.length
                        });
                    }

                    // Función para filtrar cursos por nivel
                    function filtrarCursosPorNivel() {
                        const nivelId = nivelSelect.value;
                        console.log('Filtrando cursos por nivel:', nivelId);

                        // Limpiar opciones actuales (excepto la primera)
                        while (cursoSelect.options.length > 1) {
                            cursoSelect.remove(1);
                        }

                        // Obtener opciones actuales de cursos (filtradas por fecha)
                        const cursosDisponibles = getOpcionesActuales(cursoSelect);

                        // Agregar opciones filtradas por nivel
                        cursosDisponibles.forEach(option => {
                            if (option.value === '') return; // Mantener opción vacía

                            const cursoNivel = option.getAttribute('data-nivel');
                            console.log('Curso:', option.text, 'nivel:', cursoNivel, 'coincide:', (!nivelId || cursoNivel === nivelId));

                            if (!nivelId || cursoNivel === nivelId) {
                                const nuevaOption = option.cloneNode(true);
                                cursoSelect.appendChild(nuevaOption);
                                console.log('Agregado curso:', option.text);
                            }
                        });

                        // Limpiar estudiante si el curso seleccionado ya no está disponible
                        const cursoSeleccionado = cursoSelect.value;
                        if (cursoSeleccionado) {
                            const cursoExiste = Array.from(cursoSelect.options).some(opt => opt.value === cursoSeleccionado);
                            if (!cursoExiste) {
                                cursoSelect.value = '';
                                filtrarEstudiantesPorCurso();
                            }
                        }

                        reinicializarSelect2(cursoSelect, 'Seleccionar curso...');
                    }

                    // Función para filtrar estudiantes por curso y nivel
                    function filtrarEstudiantesPorCurso() {
                        const cursoId = cursoSelect.value;
                        const nivelId = nivelSelect.value;

                        console.log('Filtrando estudiantes - cursoId:', cursoId, 'nivelId:', nivelId);

                        // Limpiar opciones actuales (excepto la primera)
                        while (estudianteSelect.options.length > 1) {
                            estudianteSelect.remove(1);
                        }

                        // Obtener opciones actuales de estudiantes (filtradas por fecha)
                        const estudiantesDisponibles = getOpcionesActuales(estudianteSelect);

                        // Agregar opciones filtradas
                        estudiantesDisponibles.forEach(option => {
                            if (option.value === '') return; // Mantener opción vacía

                            const estudianteCurso = option.getAttribute('data-curso');
                            const estudianteNivel = option.getAttribute('data-nivel');
                            let incluir = true;
                            let razonExclusión = '';

                            // Si hay curso seleccionado, filtrar por curso directo
                            if (cursoId && estudianteCurso !== cursoId) {
                                incluir = false;
                                razonExclusión = `curso no coincide (${estudianteCurso} !== ${cursoId})`;
                            }
                            // Si no hay curso seleccionado pero hay nivel, filtrar por nivel
                            else if (!cursoId && nivelId && estudianteNivel !== nivelId) {
                                incluir = false;
                                razonExclusión = `nivel no coincide (${estudianteNivel} !== ${nivelId})`;
                            }
                            // Si hay curso Y nivel seleccionados, verificar que coincidan ambos
                            else if (cursoId && nivelId) {
                                if (estudianteCurso !== cursoId || estudianteNivel !== nivelId) {
                                    incluir = false;
                                    razonExclusión = `curso o nivel no coinciden`;
                                }
                            }

                            console.log(`Estudiante ${option.text}: curso=${estudianteCurso}, nivel=${estudianteNivel}, incluir=${incluir}${razonExclusión ? ', razón: ' + razonExclusión : ''}`);

                            if (incluir) {
                                const nuevaOption = option.cloneNode(true);
                                estudianteSelect.appendChild(nuevaOption);
                            }
                        });

                        reinicializarSelect2(estudianteSelect, 'Seleccionar estudiante...');
                    }

                    // Función para filtrar docentes por curso y nivel
                    function filtrarDocentesPorCursoYNivel() {
                        const cursoId = cursoSelect.value;
                        const nivelId = nivelSelect.value;

                        console.log('Filtrando docentes - cursoId:', cursoId, 'nivelId:', nivelId);

                        // Limpiar opciones actuales (excepto la primera)
                        while (docenteSelect.options.length > 1) {
                            docenteSelect.remove(1);
                        }

                        // Obtener opciones actuales de docentes (filtradas por fecha)
                        const docentesDisponibles = getOpcionesActuales(docenteSelect);

                        // Agregar opciones filtradas (todos los docentes disponibles pueden enseñar en cualquier curso/nivel)
                        docentesDisponibles.forEach(option => {
                            if (option.value === '') return; // Mantener opción vacía

                            // En un sistema real, aquí se filtraría por docentes que enseñan en cursos específicos
                            // Por ahora, todos los docentes disponibles pueden aparecer en cualquier combinación
                            const nuevaOption = option.cloneNode(true);
                            docenteSelect.appendChild(nuevaOption);
                        });

                        reinicializarSelect2(docenteSelect, 'Seleccionar docente...');
                    }

                    // Función para reinicializar Select2
                    function reinicializarSelect2(selectElement, placeholder) {
                        $(selectElement).select2('destroy').select2({
                            theme: 'bootstrap-5',
                            width: '100%',
                            placeholder: placeholder,
                            allowClear: true,
                            minimumResultsForSearch: 0
                        });
                    }

                    // Event listeners - Usar jQuery para Select2
                    $(nivelSelect).on('change', function() {
                        console.log('Nivel changed, value:', $(this).val());
                        filtrarCursosPorNivel();
                        filtrarEstudiantesPorCurso(); // Re-filtrar estudiantes considerando el nuevo nivel
                        filtrarDocentesPorCursoYNivel(); // Re-filtrar docentes considerando el nuevo nivel
                    });

                    $(cursoSelect).on('change', function() {
                        console.log('Curso changed, value:', $(this).val());
                        filtrarEstudiantesPorCurso();
                        filtrarDocentesPorCursoYNivel(); // Re-filtrar docentes considerando el nuevo curso
                    });

                    // Inicializar filtros
                    filtrarCursosPorNivel();
                    filtrarEstudiantesPorCurso();
                    filtrarDocentesPorCursoYNivel();
                }

                function exportarReporte() {
                    const formato = document.getElementById('formato').value;
                    const tipoReporte = document.getElementById('tipo_reporte').value;
                    const fechaInicio = document.getElementById('fecha_inicio').value;
                    const fechaFin = document.getElementById('fecha_fin').value;

                    if (!fechaInicio || !fechaFin) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Fechas requeridas',
                            text: 'Por favor selecciona las fechas de inicio y fin para exportar el reporte.',
                            confirmButtonText: 'Entendido'
                        });
                        return;
                    }

                    // Recopilar todos los filtros adicionales
                    const nivelId = document.getElementById('nivel_id')?.value || '';
                    const cursoId = document.getElementById('curso_id')?.value || '';
                    const estudianteId = document.getElementById('estudiante_id')?.value || '';
                    const docenteId = document.getElementById('docente_id')?.value || '';

                    if (formato === 'pdf') {
                        // Mostrar loading
                        Swal.fire({
                            title: 'Generando reporte PDF...',
                            text: 'Por favor espera mientras se genera el PDF',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            willOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Construir URL con todos los parámetros
                        let url = `/asistencia/exportar/pdf/admin?fecha_inicio=${encodeURIComponent(fechaInicio)}&fecha_fin=${encodeURIComponent(fechaFin)}&formato=${encodeURIComponent(formato)}&tipo_reporte=${encodeURIComponent(tipoReporte)}`;

                        if (nivelId) url += `&nivel_id=${encodeURIComponent(nivelId)}`;
                        if (cursoId) url += `&curso_id=${encodeURIComponent(cursoId)}`;
                        if (estudianteId) url += `&estudiante_id=${encodeURIComponent(estudianteId)}`;
                        if (docenteId) url += `&docente_id=${encodeURIComponent(docenteId)}`;

                        // Hacer petición AJAX para generar el reporte
                        fetch(url, {
                            method: 'GET',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                            }
                        })
                        .then(response => {
                            if (response.redirected) {
                                // Si fue redirigido, verificar si hay mensaje de éxito
                                return response.text().then(text => {
                                    if (text.includes('success')) {
                                        throw new Error('Respuesta inesperada del servidor');
                                    } else {
                                        throw new Error('Error desconocido en la respuesta');
                                    }
                                });
                            } else {
                                return response.json();
                            }
                        })
                        .then(data => {
                            if (data.success && data.archivo_url) {
                                // Abrir el PDF en una nueva ventana/pestaña usando la URL del servidor
                                window.open(data.archivo_url, '_blank');

                                // Mostrar mensaje de éxito
                                Swal.fire({
                                    icon: 'success',
                                    title: '¡Reporte PDF generado!',
                                    text: 'El reporte se ha generado exitosamente y se abrirá en una nueva pestaña.',
                                    confirmButtonText: 'Entendido'
                                });
                            } else {
                                throw new Error(data.message || 'Error desconocido en la respuesta');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error al generar reporte PDF',
                                text: 'Ocurrió un error al generar el reporte. Por favor intenta nuevamente.',
                                confirmButtonText: 'Entendido'
                            });
                        });
                    } else if (formato === 'excel') {
                        // Mostrar loading para Excel
                        Swal.fire({
                            title: 'Generando reporte Excel...',
                            text: 'Por favor espera mientras se genera el archivo Excel',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            willOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Construir URL con todos los parámetros para Excel
                        let url = `/asistencia/exportar/excel/admin?fecha_inicio=${encodeURIComponent(fechaInicio)}&fecha_fin=${encodeURIComponent(fechaFin)}&formato=${encodeURIComponent(formato)}&tipo_reporte=${encodeURIComponent(tipoReporte)}`;

                        if (nivelId) url += `&nivel_id=${encodeURIComponent(nivelId)}`;
                        if (cursoId) url += `&curso_id=${encodeURIComponent(cursoId)}`;
                        if (estudianteId) url += `&estudiante_id=${encodeURIComponent(estudianteId)}`;
                        if (docenteId) url += `&docente_id=${encodeURIComponent(docenteId)}`;

                        // Para Excel, usamos una descarga directa ya que Laravel Excel maneja la respuesta automáticamente
                        window.location.href = url;

                        // Cerrar el loading después de un breve delay
                        setTimeout(() => {
                            Swal.close();
                        }, 1000);
                    } else {
                        // Para otros formatos, mostrar mensaje
                        Swal.fire({
                            icon: 'info',
                            title: 'Formato no soportado',
                            text: `El formato ${formato.toUpperCase()} no está disponible actualmente.`,
                            confirmButtonText: 'Entendido'
                        });
                    }
                }

                // Función para ver detalles de un reporte generado
                function verDetallesReporte(reporteId) {
                    // Mostrar loading
                    Swal.fire({
                        title: 'Cargando detalles...',
                        text: 'Obteniendo información del reporte',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        willOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // En un sistema real, aquí haríamos una petición AJAX para obtener los detalles
                    // Por ahora, simulamos con un mensaje informativo
                    setTimeout(() => {
                        Swal.fire({
                            title: 'Detalles del Reporte',
                            html: `
                                <div class="text-left">
                                    <p><strong>ID del Reporte:</strong> ${reporteId}</p>
                                    <p><strong>Estado:</strong> <span class="badge badge-success">Completado</span></p>
                                    <p><strong>Información:</strong> Los detalles completos del reporte están disponibles en el archivo descargado.</p>
                                    <hr>
                                    <p class="text-muted small">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Para ver información detallada del reporte, descarga el archivo usando el botón correspondiente.
                                    </p>
                                </div>
                            `,
                            icon: 'info',
                            confirmButtonText: 'Entendido'
                        });
                    }, 1000);
                }

                </script>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endsection
@endsection
<!-- Select2 CSS -->
                </script>
