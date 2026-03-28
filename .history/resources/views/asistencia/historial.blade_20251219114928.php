@extends('cplantilla.bprincipal')
@section('titulo','Historial de Asistencia')
@section('contenidoplantilla')
<x-breadcrumb :module="'asistencia'" :section="'historial'" />
<div class="container-fluid" id="contenido-principal">
    <div class="row mt-4 ml-1 mr-1">
        <div class="col-12">
            <div class="box_block">
                <!-- Collapse header -->
                <button class="btn btn-block text-left rounded-0 btn_header header_6" type="button" data-toggle="collapse" data-target="#collapseHistorial" aria-expanded="true" aria-controls="collapseHistorial" style="background: #0A8CB3 !important; font-weight: bold; color: white;">
                    <i class="fas fa-history m-1"></i>&nbsp;Historial de Asistencia
                    <div class="float-right"><i class="fas fa-chevron-down"></i></div>
                </button>
                <!-- Descripción -->
                <div class="card-body rounded-0 border-0 pt-3 pb-3" style="background: #f3f3f3; border-bottom: 1px solid rgba(0,0,0,.125); border-top: 1px solid rgba(0,0,0,.125); color: #F59D24;">
                    <div class="row justify-content-center align-items-center flex-wrap">
                        <div class="col-auto text-center mb-2 mb-md-0" style="min-width:48px;">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                        <div class="col px-2" style="text-align:justify;">
                            <p style="margin-bottom: 0px; font-family: 'Quicksand', sans-serif; font-weight: 600; color: #004a92;">
                                Revisa el historial completo de todas las actividades relacionadas con asistencia: registros, modificaciones, justificaciones procesadas y eventos del sistema.
                            </p>
                            <p style="font-family: 'Quicksand', sans-serif; font-weight: 600; color: #004a92;">
                                Utiliza los filtros para encontrar eventos específicos y mantén un registro detallado de todas las operaciones realizadas en el sistema.
                            </p>
                        </div>
                    </div>
                </div>
                <!-- Collapse: filtros y contenido -->
                <div class="collapse show" id="collapseHistorial">
                    <div class="card card-body rounded-0 border-0 pt-0 pb-2" style="background: transparent;">
                        <!-- Filtros de Historial -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header" style="background: #0e4067; color: white;">
                                        <h5 class="mb-0"><i class="fas fa-filter mr-2"></i>Filtros de Historial</h5>
                                    </div>
                                    <div class="card-body">
                                        <form id="historialForm">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="fecha_inicio_historial">Fecha Inicio</label>
                                                        <input type="date" class="form-control" id="fecha_inicio_historial">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="fecha_fin_historial">Fecha Fin</label>
                                                        <input type="date" class="form-control" id="fecha_fin_historial">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="tipo_evento">Tipo de Evento</label>
                                                        <select class="form-control" id="tipo_evento">
                                                            <option value="">Todos los eventos</option>
                                                            <option value="asistencia_registrada">Asistencia Registrada</option>
                                                            <option value="asistencia_modificada">Asistencia Modificada</option>
                                                            <option value="justificacion_solicitada">Justificación Solicitada</option>
                                                            <option value="justificacion_aprobada">Justificación Aprobada</option>
                                                            <option value="justificacion_rechazada">Justificación Rechazada</option>
                                                            <option value="sistema">Evento del Sistema</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="usuario_historial">Usuario</label>
                                                        <select class="form-control" id="usuario_historial">
                                                            <option value="">Todos los usuarios</option>
                                                            <option value="admin">Administradores</option>
                                                            <option value="docente">Docentes</option>
                                                            <option value="representante">Representantes</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12 text-center">
                                                    <button type="button" class="btn btn-primary btn-lg" onclick="filtrarHistorial()">
                                                        <i class="fas fa-search mr-2"></i>Buscar en Historial
                                                    </button>
                                                    <button type="button" class="btn btn-secondary btn-lg ml-2" onclick="limpiarFiltros()">
                                                        <i class="fas fa-eraser mr-2"></i>Limpiar Filtros
                                                    </button>
                                                    <button type="button" class="btn btn-success btn-lg ml-2" onclick="exportarHistorial()">
                                                        <i class="fas fa-download mr-2"></i>Exportar
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Timeline del Historial -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header" style="background: #17a2b8; color: white;">
                                        <h5 class="mb-0"><i class="fas fa-stream mr-2"></i>Línea de Tiempo de Eventos</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="timeline" id="timelineContainer">
                                            <!-- Evento 1 -->
                                            <div class="timeline-item">
                                                <div class="timeline-marker bg-success">
                                                    <i class="fas fa-check-circle"></i>
                                                </div>
                                                <div class="timeline-content">
                                                    <h6 class="timeline-title">Justificación Aprobada</h6>
                                                    <p class="timeline-text">
                                                        La justificación de <strong>María González</strong> para el día 15/12/2025 ha sido aprobada por el administrador.
                                                    </p>
                                                    <small class="text-muted">
                                                        <i class="fas fa-clock mr-1"></i>Hace 2 horas
                                                        <i class="fas fa-user mr-1 ml-3"></i>Admin Sistema
                                                    </small>
                                                </div>
                                            </div>

                                            <!-- Evento 2 -->
                                            <div class="timeline-item">
                                                <div class="timeline-marker bg-warning">
                                                    <i class="fas fa-clock"></i>
                                                </div>
                                                <div class="timeline-content">
                                                    <h6 class="timeline-title">Asistencia Registrada con Retraso</h6>
                                                    <p class="timeline-text">
                                                        El docente <strong>Carlos Rodríguez</strong> registró la asistencia de Matemáticas 3ro A con 15 minutos de retraso.
                                                    </p>
                                                    <small class="text-muted">
                                                        <i class="fas fa-clock mr-1"></i>Hace 4 horas
                                                        <i class="fas fa-user mr-1 ml-3"></i>Prof. Carlos Rodríguez
                                                    </small>
                                                </div>
                                            </div>

                                            <!-- Evento 3 -->
                                            <div class="timeline-item">
                                                <div class="timeline-marker bg-info">
                                                    <i class="fas fa-file-upload"></i>
                                                </div>
                                                <div class="timeline-content">
                                                    <h6 class="timeline-title">Nueva Justificación Solicitada</h6>
                                                    <p class="timeline-text">
                                                        <strong>Ana López</strong> (representante) solicitó justificación para <strong>Juan Pérez</strong> por enfermedad.
                                                    </p>
                                                    <small class="text-muted">
                                                        <i class="fas fa-clock mr-1"></i>Hace 6 horas
                                                        <i class="fas fa-user mr-1 ml-3"></i>Ana López
                                                    </small>
                                                </div>
                                            </div>

                                            <!-- Evento 4 -->
                                            <div class="timeline-item">
                                                <div class="timeline-marker bg-primary">
                                                    <i class="fas fa-user-graduate"></i>
                                                </div>
                                                <div class="timeline-content">
                                                    <h6 class="timeline-title">Asistencia Registrada</h6>
                                                    <p class="timeline-text">
                                                        Se registró asistencia completa para la clase de Lenguaje 2do B con 28 estudiantes presentes.
                                                    </p>
                                                    <small class="text-muted">
                                                        <i class="fas fa-clock mr-1"></i>Hace 8 horas
                                                        <i class="fas fa-user mr-1 ml-3"></i>Prof. María Sánchez
                                                    </small>
                                                </div>
                                            </div>

                                            <!-- Evento 5 -->
                                            <div class="timeline-item">
                                                <div class="timeline-marker bg-danger">
                                                    <i class="fas fa-exclamation-triangle"></i>
                                                </div>
                                                <div class="timeline-content">
                                                    <h6 class="timeline-title">Alerta: Baja Asistencia Detectada</h6>
                                                    <p class="timeline-text">
                                                        El curso de Ciencias Naturales 4to A tiene un promedio de asistencia del 72% en las últimas 2 semanas.
                                                    </p>
                                                    <small class="text-muted">
                                                        <i class="fas fa-clock mr-1"></i>Hace 1 día
                                                        <i class="fas fa-cog mr-1 ml-3"></i>Sistema Automático
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tabla Detallada -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header" style="background: #dc3545; color: white;">
                                        <h5 class="mb-0"><i class="fas fa-table mr-2"></i>Registro Detallado de Eventos</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-striped">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th>Fecha/Hora</th>
                                                        <th>Tipo de Evento</th>
                                                        <th>Descripción</th>
                                                        <th>Usuario</th>
                                                        <th>Entidad Afectada</th>
                                                        <th>Detalles</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>{{ now()->format('d/m/Y H:i:s') }}</td>
                                                        <td><span class="badge badge-success">Justificación Aprobada</span></td>
                                                        <td>Justificación médica aprobada</td>
                                                        <td>Admin Sistema</td>
                                                        <td>María González</td>
                                                        <td><button class="btn btn-sm btn-outline-info" onclick="verDetallesEvento(1)">Ver</button></td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{ now()->subHours(2)->format('d/m/Y H:i:s') }}</td>
                                                        <td><span class="badge badge-primary">Asistencia Registrada</span></td>
                                                        <td>Asistencia completa registrada</td>
                                                        <td>Prof. Carlos Rodríguez</td>
                                                        <td>Matemáticas 3ro A</td>
                                                        <td><button class="btn btn-sm btn-outline-info" onclick="verDetallesEvento(2)">Ver</button></td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{ now()->subHours(4)->format('d/m/Y H:i:s') }}</td>
                                                        <td><span class="badge badge-info">Justificación Solicitada</span></td>
                                                        <td>Nueva solicitud de justificación</td>
                                                        <td>Ana López</td>
                                                        <td>Juan Pérez</td>
                                                        <td><button class="btn btn-sm btn-outline-info" onclick="verDetallesEvento(3)">Ver</button></td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{ now()->subHours(6)->format('d/m/Y H:i:s') }}</td>
                                                        <td><span class="badge badge-warning">Modificación</span></td>
                                                        <td>Asistencia modificada manualmente</td>
                                                        <td>Admin Sistema</td>
                                                        <td>Historia 5to B</td>
                                                        <td><button class="btn btn-sm btn-outline-info" onclick="verDetallesEvento(4)">Ver</button></td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{ now()->subHours(8)->format('d/m/Y H:i:s') }}</td>
                                                        <td><span class="badge badge-danger">Alerta del Sistema</span></td>
                                                        <td>Baja asistencia detectada</td>
                                                        <td>Sistema Automático</td>
                                                        <td>Ciencias 4to A</td>
                                                        <td><button class="btn btn-sm btn-outline-info" onclick="verDetallesEvento(5)">Ver</button></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- Paginación -->
                                        <nav aria-label="Paginación del historial">
                                            <ul class="pagination justify-content-center">
                                                <li class="page-item disabled">
                                                    <a class="page-link" href="#" tabindex="-1">Anterior</a>
                                                </li>
                                                <li class="page-item active">
                                                    <a class="page-link" href="#">1</a>
                                                </li>
                                                <li class="page-item">
                                                    <a class="page-link" href="#">2</a>
                                                </li>
                                                <li class="page-item">
                                                    <a class="page-link" href="#">3</a>
                                                </li>
                                                <li class="page-item">
                                                    <a class="page-link" href="#">Siguiente</a>
                                                </li>
                                            </ul>
                                        </nav>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Estadísticas del Historial -->
                        <div class="row">
                            <div class="col-md-3">
                                <div class="card text-center border-primary">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-center mb-2">
                                            <i class="fas fa-calendar-check fa-2x text-primary mr-3"></i>
                                            <div>
                                                <h3 class="mb-0 text-primary">1,247</h3>
                                                <small class="text-muted">Eventos Totales</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-center border-success">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-center mb-2">
                                            <i class="fas fa-check-circle fa-2x text-success mr-3"></i>
                                            <div>
                                                <h3 class="mb-0 text-success">892</h3>
                                                <small class="text-muted">Asistencias Registradas</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-center border-info">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-center mb-2">
                                            <i class="fas fa-file-signature fa-2x text-info mr-3"></i>
                                            <div>
                                                <h3 class="mb-0 text-info">156</h3>
                                                <small class="text-muted">Justificaciones Procesadas</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-center border-warning">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-center mb-2">
                                            <i class="fas fa-exclamation-triangle fa-2x text-warning mr-3"></i>
                                            <div>
                                                <h3 class="mb-0 text-warning">23</h3>
                                                <small class="text-muted">Alertas Generadas</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                document.addEventListener('DOMContentLoaded', function () {
                    // Collapse icon toggle
                    const btn = document.querySelector('[data-target="#collapseHistorial"]');
                    const icon = btn.querySelector('.fas.fa-chevron-down');
                    const collapse = document.getElementById('collapseHistorial');
                    collapse.addEventListener('show.bs.collapse', function () {
                        icon.classList.remove('fa-chevron-down');
                        icon.classList.add('fa-chevron-up');
                    });
                    collapse.addEventListener('hide.bs.collapse', function () {
                        icon.classList.remove('fa-chevron-up');
                        icon.classList.add('fa-chevron-down');
                    });
                });

                function filtrarHistorial() {
                    const fechaInicio = document.getElementById('fecha_inicio_historial').value;
                    const fechaFin = document.getElementById('fecha_fin_historial').value;
                    const tipoEvento = document.getElementById('tipo_evento').value;
                    const usuario = document.getElementById('usuario_historial').value;

                    // Simular filtrado
                    Swal.fire({
                        icon: 'success',
                        title: '¡Filtro Aplicado!',
                        text: `Mostrando resultados filtrados: ${tipoEvento || 'Todos los eventos'} ${usuario ? 'para ' + usuario : ''}`,
                        timer: 2000,
                        showConfirmButton: false
                    });
                }

                function limpiarFiltros() {
                    document.getElementById('historialForm').reset();

                    Swal.fire({
                        icon: 'info',
                        title: 'Filtros Limpiados',
                        text: 'Se muestran todos los eventos del historial.',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }

                function exportarHistorial() {
                    const formato = 'excel'; // Podría ser dinámico

                    Swal.fire({
                        icon: 'success',
                        title: '¡Exportación Exitosa!',
                        text: `El historial ha sido exportado en formato ${formato.toUpperCase()}`,
                        timer: 2000,
                        showConfirmButton: false
                    });
                }

                function verDetallesEvento(eventoId) {
                    const detallesEventos = {
                        1: {
                            titulo: 'Justificación Aprobada - María González',
                            contenido: `
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Información del Evento</h6>
                                        <ul class="list-group">
                                            <li class="list-group-item"><strong>Fecha:</strong> 15/12/2025</li>
                                            <li class="list-group-item"><strong>Hora:</strong> 09:30 AM</li>
                                            <li class="list-group-item"><strong>Tipo:</strong> Justificación Médica</li>
                                            <li class="list-group-item"><strong>Estado:</strong> <span class="badge badge-success">Aprobada</span></li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Información del Estudiante</h6>
                                        <ul class="list-group">
                                            <li class="list-group-item"><strong>Nombre:</strong> María González</li>
                                            <li class="list-group-item"><strong>Curso:</strong> 3ro A</li>
                                            <li class="list-group-item"><strong>Representante:</strong> Ana González</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <h6>Observaciones del Administrador</h6>
                                    <p class="text-muted">Documento médico válido. Justificación aprobada según política institucional.</p>
                                </div>
                            `
                        },
                        2: {
                            titulo: 'Asistencia Registrada - Matemáticas 3ro A',
                            contenido: `
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Información de la Clase</h6>
                                        <ul class="list-group">
                                            <li class="list-group-item"><strong>Asignatura:</strong> Matemáticas</li>
                                            <li class="list-group-item"><strong>Curso:</strong> 3ro A</li>
                                            <li class="list-group-item"><strong>Docente:</strong> Carlos Rodríguez</li>
                                            <li class="list-group-item"><strong>Hora:</strong> 10:00 - 11:00</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Estadísticas de Asistencia</h6>
                                        <ul class="list-group">
                                            <li class="list-group-item"><strong>Total Estudiantes:</strong> 32</li>
                                            <li class="list-group-item"><strong>Presentes:</strong> 28</li>
                                            <li class="list-group-item"><strong>Ausentes:</strong> 3</li>
                                            <li class="list-group-item"><strong>Tarde:</strong> 1</li>
                                        </ul>
                                    </div>
                                </div>
                            `
                        },
                        3: {
                            titulo: 'Nueva Justificación Solicitada - Juan Pérez',
                            contenido: `
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Información de la Solicitud</h6>
                                        <ul class="list-group">
                                            <li class="list-group-item"><strong>Fecha de Falta:</strong> 14/12/2025</li>
                                            <li class="list-group-item"><strong>Motivo:</strong> Enfermedad</li>
                                            <li class="list-group-item"><strong>Solicitante:</strong> Ana López (Representante)</li>
                                            <li class="list-group-item"><strong>Estado:</strong> <span class="badge badge-warning">Pendiente</span></li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Documentación Adjunta</h6>
                                        <div class="alert alert-info">
                                            <i class="fas fa-file-pdf mr-2"></i>
                                            Certificado médico adjunto: <strong>certificado_medico_001.pdf</strong>
                                            <br><small class="text-muted">Archivo válido - Pendiente de revisión</small>
                                        </div>
                                    </div>
                                </div>
                            `
                        },
                        4: {
                            titulo: 'Modificación de Asistencia - Historia 5to B',
                            contenido: `
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Cambios Realizados</h6>
                                        <ul class="list-group">
                                            <li class="list-group-item"><strong>Estudiante:</strong> Pedro Martínez</li>
                                            <li class="list-group-item"><strong>Cambio:</strong> Ausente → Presente</li>
                                            <li class="list-group-item"><strong>Fecha:</strong> 13/12/2025</li>
                                            <li class="list-group-item"><strong>Modificado por:</strong> Admin Sistema</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Motivo de la Modificación</h6>
                                        <div class="alert alert-light">
                                            <p>Corrección de error en el registro original. El estudiante estuvo presente en clase según confirmación del docente.</p>
                                        </div>
                                    </div>
                                </div>
                            `
                        },
                        5: {
                            titulo: 'Alerta de Baja Asistencia - Ciencias 4to A',
                            contenido: `
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Detalles de la Alerta</h6>
                                        <ul class="list-group">
                                            <li class="list-group-item"><strong>Curso:</strong> Ciencias Naturales 4to A</li>
                                            <li class="list-group-item"><strong>Período:</strong> Últimas 2 semanas</li>
                                            <li class="list-group-item"><strong>Asistencia Promedio:</strong> 72%</li>
                                            <li class="list-group-item"><strong>Umbral de Alerta:</strong> 85%</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Recomendaciones</h6>
                                        <div class="alert alert-warning">
                                            <strong>Acciones sugeridas:</strong>
                                            <ul class="mb-0 mt-2">
                                                <li>Contactar con representantes</li>
                                                <li>Revisar causas de inasistencia</li>
                                                <li>Implementar medidas correctivas</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            `
                        }
                    };

                    const evento = detallesEventos[eventoId];
                    if (evento) {
                        Swal.fire({
                            title: evento.titulo,
                            html: evento.contenido,
                            width: '800px',
                            showCloseButton: true,
                            showConfirmButton: false
                        });
                    }
                }
                </script>

                <style>
                /* Timeline Styles */
                .timeline {
                    position: relative;
                    padding-left: 30px;
                }

                .timeline::before {
                    content: '';
                    position: absolute;
                    left: 15px;
                    top: 0;
                    bottom: 0;
                    width: 2px;
                    background: #e9ecef;
                }

                .timeline-item {
                    position: relative;
                    margin-bottom: 30px;
                }

                .timeline-marker {
                    position: absolute;
                    left: -22px;
                    top: 0;
                    width: 30px;
                    height: 30px;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    color: white;
                    border: 3px solid white;
                    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
                }

                .timeline-content {
                    background: white;
                    padding: 15px;
                    border-radius: 8px;
                    border: 1px solid #e9ecef;
                    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
                }

                .timeline-title {
                    color: #495057;
                    margin-bottom: 8px;
                    font-weight: 600;
                }

                .timeline-text {
                    color: #6c757d;
                    margin-bottom: 8px;
                    line-height: 1.5;
                }
                </style>
            </div>
        </div>
    </div>
</div>
@endsection
