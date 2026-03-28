@extends('cplantilla.bprincipal')
@section('titulo','Configuración de Asistencia')
@section('contenidoplantilla')
<x-breadcrumb :module="'asistencia'" :section="'configuracion'" />
<div class="container-fluid" id="contenido-principal">
    <div class="row mt-4 ml-1 mr-1">
        <div class="col-12">
            <div class="box_block">
                <!-- Collapse header -->
                <button class="btn btn-block text-left rounded-0 btn_header header_6" type="button" data-toggle="collapse" data-target="#collapseConfiguracion" aria-expanded="true" aria-controls="collapseConfiguracion" style="background: #0A8CB3 !important; font-weight: bold; color: white;">
                    <i class="fas fa-cogs m-1"></i>&nbsp;Configuración de Asistencia
                    <div class="float-right"><i class="fas fa-chevron-down"></i></div>
                </button>
                <!-- Descripción -->
                <div class="card-body rounded-0 border-0 pt-3 pb-3" style="background: #f3f3f3; border-bottom: 1px solid rgba(0,0,0,.125); border-top: 1px solid rgba(0,0,0,.125); color: #F59D24;">
                    <div class="row justify-content-center align-items-center flex-wrap">
                        <div class="col-auto text-center mb-2 mb-md-0" style="min-width:48px;">
                            <i class="fas fa-sliders-h fa-2x"></i>
                        </div>
                        <div class="col px-2" style="text-align:justify;">
                            <p style="margin-bottom: 0px; font-family: 'Quicksand', sans-serif; font-weight: 600; color: #004a92;">
                                Configura los parámetros del sistema de asistencia: tipos de asistencia, reglas de justificación, alertas automáticas y permisos por rol.
                            </p>
                            <p style="font-family: 'Quicksand', sans-serif; font-weight: 600; color: #004a92;">
                                Estas configuraciones afectan directamente al comportamiento del sistema y deben ser manejadas con cuidado por administradores autorizados.
                            </p>
                        </div>
                    </div>
                </div>
                <!-- Collapse: configuración -->
                <div class="collapse show" id="collapseConfiguracion">
                    <div class="card card-body rounded-0 border-0 pt-0 pb-2" style="background: transparent;">
                        <!-- Configuración General -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header" style="background: #0e4067; color: white;">
                                        <h5 class="mb-0"><i class="fas fa-cog mr-2"></i>Configuración General</h5>
                                    </div>
                                    <div class="card-body">
                                        <form id="configGeneralForm">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="hora_limite_asistencia">Hora Límite para Marcar Asistencia</label>
                                                        <input type="time" class="form-control" id="hora_limite_asistencia" value="08:30">
                                                        <small class="form-text text-muted">Después de esta hora, las asistencias se marcarán como "Tarde"</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="dias_anticipacion_justificacion">Días de Anticipación para Justificaciones</label>
                                                        <input type="number" class="form-control" id="dias_anticipacion_justificacion" value="3" min="0" max="30">
                                                        <small class="form-text text-muted">Días antes de la falta que se puede solicitar justificación</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="umbral_alerta_asistencia">Umbral de Alerta de Asistencia (%)</label>
                                                        <input type="number" class="form-control" id="umbral_alerta_asistencia" value="85" min="0" max="100">
                                                        <small class="form-text text-muted">Porcentaje mínimo para activar alertas de baja asistencia</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="periodo_revision_justificaciones">Período de Revisión de Justificaciones (horas)</label>
                                                        <input type="number" class="form-control" id="periodo_revision_justificaciones" value="48" min="1" max="168">
                                                        <small class="form-text text-muted">Tiempo máximo para que un administrador revise una justificación</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="notificaciones_automaticas" checked>
                                                        <label class="form-check-label" for="notificaciones_automaticas">
                                                            <strong>Activar Notificaciones Automáticas</strong>
                                                        </label>
                                                        <small class="form-text text-muted d-block">Enviar alertas por email cuando se detecten problemas de asistencia</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-12 text-center">
                                                    <button type="submit" class="btn btn-primary btn-lg">
                                                        <i class="fas fa-save mr-2"></i>Guardar Configuración General
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tipos de Asistencia -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header" style="background: #17a2b8; color: white;">
                                        <h5 class="mb-0"><i class="fas fa-list mr-2"></i>Tipos de Asistencia</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th>Código</th>
                                                        <th>Nombre</th>
                                                        <th>Descripción</th>
                                                        <th>Factor Asistencia</th>
                                                        <th>Computa Falta</th>
                                                        <th>Activo</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td><span class="badge badge-success">P</span></td>
                                                        <td>Presente</td>
                                                        <td>Estudiante presente en clase</td>
                                                        <td>100%</td>
                                                        <td><i class="fas fa-times text-danger"></i></td>
                                                        <td><i class="fas fa-check text-success"></i></td>
                                                        <td>
                                                            <button class="btn btn-sm btn-outline-primary">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><span class="badge badge-danger">A</span></td>
                                                        <td>Ausente</td>
                                                        <td>Estudiante ausente sin justificación</td>
                                                        <td>0%</td>
                                                        <td><i class="fas fa-check text-success"></i></td>
                                                        <td><i class="fas fa-check text-success"></i></td>
                                                        <td>
                                                            <button class="btn btn-sm btn-outline-primary">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><span class="badge badge-warning">T</span></td>
                                                        <td>Tarde</td>
                                                        <td>Estudiante llegó tarde a clase</td>
                                                        <td>75%</td>
                                                        <td><i class="fas fa-times text-danger"></i></td>
                                                        <td><i class="fas fa-check text-success"></i></td>
                                                        <td>
                                                            <button class="btn btn-sm btn-outline-primary">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><span class="badge badge-info">J</span></td>
                                                        <td>Justificado</td>
                                                        <td>Ausencia justificada con documento</td>
                                                        <td>100%</td>
                                                        <td><i class="fas fa-times text-danger"></i></td>
                                                        <td><i class="fas fa-check text-success"></i></td>
                                                        <td>
                                                            <button class="btn btn-sm btn-outline-primary">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="text-center mt-3">
                                            <button class="btn btn-success" onclick="agregarTipoAsistencia()">
                                                <i class="fas fa-plus mr-2"></i>Agregar Nuevo Tipo
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Reglas de Justificación -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header" style="background: #dc3545; color: white;">
                                        <h5 class="mb-0"><i class="fas fa-file-contract mr-2"></i>Reglas de Justificación</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6>Documentos Requeridos</h6>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="requiere_certificado_medico" checked>
                                                    <label class="form-check-label" for="requiere_certificado_medico">
                                                        Certificado Médico
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="requiere_comunicado_familiar" checked>
                                                    <label class="form-check-label" for="requiere_comunicado_familiar">
                                                        Comunicado Familiar
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="requiere_otro_documento">
                                                    <label class="form-check-label" for="requiere_otro_documento">
                                                        Otro Documento
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <h6>Límites de Justificación</h6>
                                                <div class="form-group">
                                                    <label for="max_justificaciones_mes">Máximo de Justificaciones por Mes</label>
                                                    <input type="number" class="form-control" id="max_justificaciones_mes" value="3" min="1" max="10">
                                                </div>
                                                <div class="form-group">
                                                    <label for="max_dias_consecutivos">Máximo de Días Consecutivos</label>
                                                    <input type="number" class="form-control" id="max_dias_consecutivos" value="3" min="1" max="7">
                                                </div>
                                                <div class="form-group">
                                                    <label for="dias_no_justificables">Días No Justificables</label>
                                                    <select class="form-control" id="dias_no_justificables" multiple>
                                                        <option value="lunes">Lunes</option>
                                                        <option value="viernes">Viernes</option>
                                                        <option value="fin_semana">Fin de Semana</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-12 text-center">
                                                <button type="button" class="btn btn-primary btn-lg" onclick="guardarReglasJustificacion()">
                                                    <i class="fas fa-save mr-2"></i>Guardar Reglas de Justificación
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Alertas y Notificaciones -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white;">
                                        <h5 class="mb-0"><i class="fas fa-bell mr-2"></i>Alertas y Notificaciones</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <h6>Alertas para Administradores</h6>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="alerta_justificaciones_pendientes" checked>
                                                    <label class="form-check-label" for="alerta_justificaciones_pendientes">
                                                        Justificaciones Pendientes
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="alerta_baja_asistencia" checked>
                                                    <label class="form-check-label" for="alerta_baja_asistencia">
                                                        Baja Asistencia en Cursos
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="alerta_retraso_revision" checked>
                                                    <label class="form-check-label" for="alerta_retraso_revision">
                                                        Retraso en Revisión de Justificaciones
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <h6>Alertas para Docentes</h6>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="alerta_asistencia_pendiente" checked>
                                                    <label class="form-check-label" for="alerta_asistencia_pendiente">
                                                        Asistencia No Tomada
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="alerta_cambios_programacion">
                                                    <label class="form-check-label" for="alerta_cambios_programacion">
                                                        Cambios en Programación
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <h6>Alertas para Representantes</h6>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="alerta_justificacion_procesada" checked>
                                                    <label class="form-check-label" for="alerta_justificacion_procesada">
                                                        Justificación Procesada
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="alerta_baja_asistencia_estudiante" checked>
                                                    <label class="form-check-label" for="alerta_baja_asistencia_estudiante">
                                                        Baja Asistencia de Estudiante
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="alerta_recordatorio_reportes">
                                                    <label class="form-check-label" for="alerta_recordatorio_reportes">
                                                        Recordatorio de Reportes
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-12 text-center">
                                                <button type="button" class="btn btn-primary btn-lg" onclick="guardarConfiguracionAlertas()">
                                                    <i class="fas fa-save mr-2"></i>Guardar Configuración de Alertas
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Backup y Restauración -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card border-warning">
                                    <div class="card-header bg-warning text-white">
                                        <h5 class="mb-0"><i class="fas fa-database mr-2"></i>Backup y Restauración</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6>Crear Backup</h6>
                                                <p class="text-muted">Genera una copia de seguridad de toda la configuración de asistencia.</p>
                                                <button class="btn btn-warning" onclick="crearBackup()">
                                                    <i class="fas fa-download mr-2"></i>Crear Backup
                                                </button>
                                            </div>
                                            <div class="col-md-6">
                                                <h6>Restaurar Configuración</h6>
                                                <p class="text-muted">Restaura la configuración desde un archivo de backup.</p>
                                                <input type="file" class="form-control-file d-none" id="backupFile" accept=".json,.bak">
                                                <button class="btn btn-secondary" onclick="document.getElementById('backupFile').click()">
                                                    <i class="fas fa-upload mr-2"></i>Seleccionar Archivo
                                                </button>
                                                <button class="btn btn-danger ml-2" onclick="restaurarBackup()">
                                                    <i class="fas fa-undo mr-2"></i>Restaurar
                                                </button>
                                            </div>
                                        </div>
                                        <div class="alert alert-info mt-3">
                                            <i class="fas fa-info-circle mr-2"></i>
                                            <strong>Recomendación:</strong> Realiza backups regulares antes de realizar cambios importantes en la configuración.
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
                    const btn = document.querySelector('[data-target="#collapseConfiguracion"]');
                    const icon = btn.querySelector('.fas.fa-chevron-down');
                    const collapse = document.getElementById('collapseConfiguracion');
                    collapse.addEventListener('show.bs.collapse', function () {
                        icon.classList.remove('fa-chevron-down');
                        icon.classList.add('fa-chevron-up');
                    });
                    collapse.addEventListener('hide.bs.collapse', function () {
                        icon.classList.remove('fa-chevron-up');
                        icon.classList.add('fa-chevron-down');
                    });
                });

                function guardarConfiguracionGeneral() {
                    // Simular guardado
                    Swal.fire({
                        icon: 'success',
                        title: '¡Configuración Guardada!',
                        text: 'Los cambios en la configuración general han sido aplicados.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }

                function agregarTipoAsistencia() {
                    Swal.fire({
                        title: 'Agregar Nuevo Tipo de Asistencia',
                        html: `
                            <div class="form-group text-left">
                                <label for="codigo">Código</label>
                                <input type="text" id="codigo" class="form-control" maxlength="5" placeholder="Ej: E">
                            </div>
                            <div class="form-group text-left">
                                <label for="nombre">Nombre</label>
                                <input type="text" id="nombre" class="form-control" placeholder="Ej: Excusa">
                            </div>
                            <div class="form-group text-left">
                                <label for="descripcion">Descripción</label>
                                <textarea id="descripcion" class="form-control" rows="3" placeholder="Descripción del tipo de asistencia"></textarea>
                            </div>
                        `,
                        showCancelButton: true,
                        confirmButtonText: 'Agregar',
                        cancelButtonText: 'Cancelar',
                        preConfirm: () => {
                            const codigo = document.getElementById('codigo').value;
                            const nombre = document.getElementById('nombre').value;
                            const descripcion = document.getElementById('descripcion').value;

                            if (!codigo || !nombre) {
                                Swal.showValidationMessage('Código y nombre son obligatorios');
                                return false;
                            }

                            return { codigo, nombre, descripcion };
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Tipo Agregado!',
                                text: `El tipo de asistencia "${result.value.nombre}" ha sido agregado exitosamente.`,
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                    });
                }

                function guardarReglasJustificacion() {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Reglas Guardadas!',
                        text: 'Las reglas de justificación han sido actualizadas.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }

                function guardarConfiguracionAlertas() {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Alertas Configuradas!',
                        text: 'La configuración de alertas y notificaciones ha sido guardada.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }

                function crearBackup() {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Backup Creado!',
                        text: 'El archivo de backup ha sido generado y descargado.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }

                function restaurarBackup() {
                    const fileInput = document.getElementById('backupFile');
                    if (!fileInput.files[0]) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Archivo Requerido',
                            text: 'Por favor selecciona un archivo de backup primero.'
                        });
                        return;
                    }

                    Swal.fire({
                        title: '¿Restaurar Backup?',
                        text: 'Esta acción sobrescribirá la configuración actual. ¿Estás seguro?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, restaurar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Backup Restaurado!',
                                text: 'La configuración ha sido restaurada exitosamente.',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                    });
                }

                // Interceptar envío del formulario
                document.getElementById('configGeneralForm').addEventListener('submit', function(e) {
                    e.preventDefault();
                    guardarConfiguracionGeneral();
                });
                </script>
            </div>
        </div>
    </div>
</div>
@endsection
