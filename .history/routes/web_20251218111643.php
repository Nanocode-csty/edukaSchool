<?php

use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\BotManController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ContactoController;
use App\Http\Controllers\ComunicadosController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InfAnioLectivoController;
use App\Http\Controllers\InfAsignaturaController;
use App\Http\Controllers\InfAulaController;
use App\Http\Controllers\InfConceptoPagoController;
use App\Http\Controllers\InfCursoController;
use App\Http\Controllers\InfDocenteController;
use App\Http\Controllers\InfEstudianteController;
use App\Http\Controllers\InfEstudianteRepresentanteController;
use App\Http\Controllers\InfGradoController;
use App\Http\Controllers\InfNivelController;
use App\Http\Controllers\InfPagoController;
use App\Http\Controllers\InfPeriodosEvaluacionController;
use App\Http\Controllers\InfRepresentanteController;
use App\Http\Controllers\InfSeccionController;
use App\Http\Controllers\MatriculaController;
use App\Http\Controllers\NotasController;
use App\Http\Controllers\PrincipalController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UsuariosController;
use Illuminate\Support\Facades\Route;

Route::post('/pagos/preferencia', [InfPagoController::class, 'crearPreferencia'])->name('pagos.crearPreferencia');

Route::get('/pagos/success', [InfPagoController::class, 'success'])->name('pagos.success');
Route::get('/pagos/failure', [InfPagoController::class, 'failure'])->name('pagos.failure');
Route::get('/pagos/pending', [InfPagoController::class, 'pending'])->name('pagos.pending');

// Validación manual de pagos desde frontend
Route::post('/pagos/validar', [InfPagoController::class, 'validar'])->name('pagos.validar');

Route::get('auth/google', [SocialAuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback']);

Route::post('/logout', [UserController::class, 'salir'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    //SECCION SISTEMA
    Route::get('/comunicados', [ComunicadosController::class, 'index'])->name('comunicado.index');
    Route::post('/create', [ComunicadosController::class, 'store'])->name('comunicado.store');

    Route::match(['get', 'post'], '/botman', [BotManController::class, 'handle'])->name('botman');

    Route::get('/rutarrr1', [PrincipalController::class, 'index'])->name('rutarrr1');
    Route::get('/perfil', [PrincipalController::class, 'verPerfil'])->name('perfil.index');
    Route::get('/rutarrr2', [ClienteController::class, 'index'])->name('rutarrr2');
    Route::get('/rutarrr3', [UsuariosController::class, 'index'])->name('rutarrr3');
    Route::get('/rutarrr4', [ProductosController::class, 'index'])->name('rutarrr4');

    Route::post('/registrorepresentanteestudiante', [InfRepresentanteController::class, 'store'])->name('registrorepresentanteestudiante.store');

    Route::resource('/estudiante', InfEstudianteController::class);
    Route::resource('/representante', InfRepresentanteController::class);
    Route::post('/buscar-representante', [InfRepresentanteController::class, 'buscarPorDni'])->name('buscar.representante');
    Route::post('/asignar-representante', [InfRepresentanteController::class, 'asignarRepresentante'])->name('asignar.representante');

    Route::get('/verificar-dni', [InfEstudianteController::class, 'verificarDni'])->name('verificar.dni');
    Route::get('/verificar-dni-representante', [InfRepresentanteController::class, 'verificarDniRepresentante'])->name('verificar.dnirepresentante');

    Route::resource('/docente', InfDocenteController::class);
    Route::get('/verificar-dni-docente', [InfDocenteController::class, 'verificarDniDocente'])->name('verificar.dni.docente');

    Route::get('/registrodocente', [InfDocenteController::class, 'index'])->name('registrardocente.index');
    Route::get('/registrorepresentante', [InfRepresentanteController::class, 'index'])->name('registrarrepresentante.index');
    Route::get('/representantes/pdf', [InfRepresentanteController::class, 'exportarPDF'])->name('representantes.pdf');
    // Route::get('/registroseccion', [InfSeccionController::class, 'index'])->name('registrarseccion.index');
    // Route::get('/registroaniolectivo', [InfAnioLectivoController::class, 'index'])->name('registraraniolectivo.index');

    Route::post('/registrardocente/{id}/actualizar', [InfDocenteController::class, 'update'])->name('registrardocente.update');
    Route::post('/registrardocente/{id}/eliminar', [InfDocenteController::class, 'destroy'])->name('registrardocente.destroy');

    Route::get('/registrorepresentanteestudiante', [InfEstudianteRepresentanteController::class, 'index'])->name('registrorepresentanteestudiante.index');
    // Route::get('/registrogrado', [InfAulaController::class, 'index'])->name('registraraula.index');
    // Route::get('/registroasignatura', [InfAsignaturaController::class, 'index'])->name('registrarasignatura.index');
    // Route::get('/registroperiodoevaluacion', [InfPeriodosEvaluacionController::class, 'index'])->name('registrarPeriodosEvaluacion.index');
    // Route::get('/registrogrados', [InfGradoController::class, 'index'])->name('grados.index');

    // Niveles Educativos
    Route::get('/registronivel', [InfNivelController::class, 'index'])->name('registrarnivel.index');
    Route::get('/registrarnivel/create', [InfNivelController::class, 'create'])->name('registrarnivel.create');
    Route::post('/registrarnivel', [InfNivelController::class, 'store'])->name('registrarnivel.store');
    Route::get('/registrarnivel/{nivel}/edit', [InfNivelController::class, 'edit'])->name('registrarnivel.edit');
    Route::put('/registrarnivel/{nivel}', [InfNivelController::class, 'update'])->name('registrarnivel.update');
    Route::delete('/registrarnivel/{nivel}', [InfNivelController::class, 'destroy'])->name('registrarnivel.destroy');
    Route::get('/registrarnivel/{nivel}/confirmar', [InfNivelController::class, 'confirmar'])->name('registrarnivel.confirmar');
    Route::get('/registrarnivel/cancelar', function () {
        return redirect()->route('registrarnivel.index')->with('datos', 'Acción Cancelada !!!');
    })->name('registrarnivel.cancelar');

    Route::get('/matriculas', [MatriculaController::class, 'index'])->name('matriculas.index');
    Route::get('/matriculas/create', [MatriculaController::class, 'create'])->name('matriculas.create');
    Route::post('/matriculas', [MatriculaController::class, 'store'])->name('matriculas.store');
    Route::get('/matriculas/{id}', [MatriculaController::class, 'show'])->name('matriculas.show');
    Route::get('/matriculas/{id}/editar', [MatriculaController::class, 'edit'])->name('matriculas.edit');
    Route::put('/matriculas/{id}', [MatriculaController::class, 'update'])->name('matriculas.update');
    Route::patch('/matriculas/{id}/anular', [MatriculaController::class, 'anular'])->name('matriculas.anular');
    Route::get('/matriculas/secciones-disponibles', [MatriculaController::class, 'getSeccionesDisponibles'])->name('matriculas.secciones.disponibles');

    Route::resource('/registrarcurso', InfCursoController::class);
    Route::get('/registrarcurso/cancelar', function () {
        return redirect()->route('registrarcurso.index')->with('datos', 'Acción Cancelada !!!');
    })->name('registrarcurso.cancelar');
    Route::get('registrarcurso/{curso_id}/confirmar', [InfCursoController::class, 'confirmar'])->name('registrarcurso.confirmar');

    Route::get('/registroconceptopago', [InfConceptoPagoController::class, 'index'])->name('conceptospago.index');

    Route::get('/pagos', [InfPagoController::class, 'index'])->name('pagos.index');
    Route::get('/pagos/create', [InfPagoController::class, 'create'])->name('pagos.create');
    Route::post('/pagos', [InfPagoController::class, 'store'])->name('pagos.store');
    Route::get('/pagos/{id}', [InfPagoController::class, 'show'])->name('pagos.show');
    Route::get('/pagos/{id}/editar', [InfPagoController::class, 'edit'])->name('pagos.edit');
    Route::put('/pagos/{id}', [InfPagoController::class, 'update'])->name('pagos.update');
    Route::delete('/pagos/{id}', [InfPagoController::class, 'destroy'])->name('pagos.destroy');

    // Grados
    Route::get('/grados', [InfGradoController::class, 'index'])->name('grados.index');
    Route::get('/grados/crear', [InfGradoController::class, 'create'])->name('grados.create');
    Route::post('/grados', [InfGradoController::class, 'store'])->name('grados.store');
    Route::delete('/grados/{id}', [InfGradoController::class, 'destroy'])->name('grados.destroy');

    // Rutas para el registro de notas
    Route::get('/notas', [NotasController::class, 'index'])->name('notas.inicio');
    Route::post('/notas/editar', [NotasController::class, 'listado'])->name('notas.editar');
    Route::post('/notas/actualizar', [NotasController::class, 'guardar'])->name('notas.actualizar');
    Route::get('/notas/asignaturas-por-curso', [NotasController::class, 'getAsignaturasPorCurso'])->name('notas.asignaturas');
    Route::get('/notas/editar', [NotasController::class, 'redireccionarEditar'])->name('notas.redireccionarEditar');

    // Rutas para consultar notas por estudiante
    Route::get('/notas/consulta', [NotasController::class, 'buscarEstudiante'])->name('notas.consulta');
    Route::post('/notas/autorizar-estudiante', [NotasController::class, 'autorizarVerEstudiante'])->name('notas.autorizarEstudiante');
    Route::get('/notas/estudiante/{id}', [NotasController::class, 'verNotasEstudiante'])->name('notas.estudiante');
    Route::get('/notas/buscar-ajax', [NotasController::class, 'buscarEstudianteAjax'])->name('notas.buscarEstudiante');
    // Nueva ruta para representantes (ver sus estudiantes)
    Route::get('/mis-estudiantes', [NotasController::class, 'misEstudiantes'])->name('notas.misEstudiantes');

    // aulas
    Route::get('aulas', [InfAulaController::class, 'index'])->name('aulas.index');
    Route::get('aulas/create', [InfAulaController::class, 'create'])->name('aulas.create');
    Route::post('aulas', [InfAulaController::class, 'store'])->name('aulas.store');
    Route::get('aulas/{aula}/edit', [InfAulaController::class, 'edit'])->name('aulas.edit');
    Route::put('aulas/{aula}', [InfAulaController::class, 'update'])->name('aulas.update');
    Route::delete('aulas/{aula}', [InfAulaController::class, 'destroy'])->name('aulas.destroy');

    Route::get('secciones', [InfSeccionController::class, 'index'])->name('secciones.index');
    Route::get('secciones/create', [InfSeccionController::class, 'create'])->name('secciones.create');
    Route::post('secciones', [InfSeccionController::class, 'store'])->name('secciones.store');
    Route::get('secciones/{seccion}/edit', [InfSeccionController::class, 'edit'])->name('secciones.edit');
    Route::put('secciones/{seccion}', [InfSeccionController::class, 'update'])->name('secciones.update');
    Route::delete('secciones/{seccion}', [InfSeccionController::class, 'destroy'])->name('secciones.destroy');

    // Año Lectivo
    // Año Lectivo
    Route::get('/aniolectivo', [InfAnioLectivoController::class, 'index'])->name('aniolectivo.index');
    Route::get('/aniolectivo/create', [InfAnioLectivoController::class, 'create'])->name('aniolectivo.create');
    Route::post('/aniolectivo', [InfAnioLectivoController::class, 'store'])->name('aniolectivo.store');
    Route::get('/aniolectivo/{id}/edit', [InfAnioLectivoController::class, 'edit'])->name('aniolectivo.edit');
    Route::put('/aniolectivo/{id}', [InfAnioLectivoController::class, 'update'])->name('aniolectivo.update');
    Route::delete('/aniolectivo/{id}', [InfAnioLectivoController::class, 'destroy'])->name('aniolectivo.destroy');

    // Periodos de Evaluación

    // === Periodos de Evaluación ===
    Route::get('/registro-periodos-evaluacion', [InfPeriodosEvaluacionController::class, 'index'])->name('periodos-evaluacion.index');
    Route::get('/periodos-evaluacion/crear', [InfPeriodosEvaluacionController::class, 'create'])->name('periodos-evaluacion.create');
    Route::post('/periodos-evaluacion', [InfPeriodosEvaluacionController::class, 'store'])->name('periodos-evaluacion.store');
    Route::get('/periodos-evaluacion/{id}/editar', [InfPeriodosEvaluacionController::class, 'edit'])->name('periodos-evaluacion.edit');
    Route::put('/periodos-evaluacion/{id}', [InfPeriodosEvaluacionController::class, 'update'])->name('periodos-evaluacion.update');
    Route::delete('/periodos-evaluacion/{id}', [InfPeriodosEvaluacionController::class, 'destroy'])->name('periodos-evaluacion.destroy');

    Route::get('/asignaturas', [InfAsignaturaController::class, 'index'])->name('asignaturas.index');
    Route::get('/asignaturas/create', [InfAsignaturaController::class, 'create'])->name('asignaturas.create');
    Route::post('/asignaturas', [InfAsignaturaController::class, 'store'])->name('asignaturas.store');
    Route::get('/asignaturas/{asignatura}/edit', [InfAsignaturaController::class, 'edit'])->name('asignaturas.edit');
    Route::put('/asignaturas/{asignatura}', [InfAsignaturaController::class, 'update'])->name('asignaturas.update');
    Route::delete('/asignaturas/{asignatura}', [InfAsignaturaController::class, 'destroy'])->name('asignaturas.destroy');

    // Rutas de Asistencia
    Route::prefix('asistencia')->name('asistencia.')->group(function () {

        // Vista administrativa (solo administradores)
        Route::get('/admin', [AsistenciaController::class, 'adminIndex'])
            ->name('admin-index');

        // Registro de asistencia
        Route::get('/registrar/{cursoAsignatura}/{fecha?}', [AsistenciaController::class, 'registrarAsignatura'])
            ->name('registrar-asignatura');

        Route::post('/guardar', [AsistenciaController::class, 'guardarAsignatura'])
            ->name('guardar-asignatura');

        // Reportes
        Route::get('/reporte/estudiante/{matricula}', [AsistenciaController::class, 'detalleEstudiante'])
            ->name('detalle-estudiante');

        Route::get('/reporte/curso/{cursoAsignatura}', [AsistenciaController::class, 'reporteCurso'])
            ->name('reporte-curso');
        Route::get('/reporte/curso/{cursoAsignatura}/pdf', [AsistenciaController::class, 'exportarPDFCurso'])
            ->name('exportar-pdf-curso');

        Route::get('/reporte/general', [AsistenciaController::class, 'reporteGeneral'])
            ->name('reporte-general');

        // Justificaciones
        Route::get('/justificar', [AsistenciaController::class, 'justificar'])
            ->name('justificar');

        Route::post('/justificar', [AsistenciaController::class, 'guardarJustificacion'])
            ->name('guardar-justificacion');

        Route::get('/mis-justificaciones', [AsistenciaController::class, 'misJustificaciones'])
            ->name('mis-justificaciones');

        Route::get('/verificar', [AsistenciaController::class, 'verificar'])
            ->name('verificar');

        Route::post('/procesar-verificacion', [AsistenciaController::class, 'procesarVerificacion'])
            ->name('procesar-verificacion');

        Route::get('/exportar/pdf/admin', [AsistenciaController::class, 'exportarPDFAdmin'])
            ->name('exportar-pdf-admin');

        Route::get('/exportar/pdf/{matricula}', [AsistenciaController::class, 'exportarPDF'])
            ->name('exportar-pdf');

        // API para funcionalidades AJAX
        Route::prefix('api')->name('api.')->group(function () {
            Route::get('/estadisticas/{profesor}/{fecha}', [AsistenciaController::class, 'obtenerEstadisticas']);
            Route::get('/historial/{matricula}', [AsistenciaController::class, 'obtenerHistorial']);
            Route::post('/reconocimiento-facial', [AsistenciaController::class, 'reconocimientoFacial']);
            Route::get('/alertas/{curso}', [AsistenciaController::class, 'obtenerAlertas']);
        });

        // Notificaciones
        Route::get('/notificaciones', [AsistenciaController::class, 'notificaciones'])
            ->name('notificaciones');

        Route::post('/marcar-notificacion-leida', [AsistenciaController::class, 'marcarNotificacionLeida'])
            ->name('marcar-notificacion-leida');

        Route::post('/marcar-todas-notificaciones-leidas', [AsistenciaController::class, 'marcarTodasNotificacionesLeidas'])
            ->name('marcar-todas-notificaciones-leidas');

        // Configuración
        Route::get('/configuracion', [AsistenciaController::class, 'configuracion'])
            ->name('configuracion');

        Route::post('/configuracion', [AsistenciaController::class, 'guardarConfiguracion'])
            ->name('guardar-configuracion');

        // Nueva ruta para representantes (ver sus estudiantes)
        Route::get('/mis-estudiantes', [AsistenciaController::class, 'misEstudiantes'])->name('misEstudiantes');

        // APIs para filtros dependientes
        Route::get('/api/cursos-por-profesor/{profesorId}', [AsistenciaController::class, 'getCursosPorProfesor']);
        Route::get('/api/asignaturas-por-profesor/{profesorId}', [AsistenciaController::class, 'getAsignaturasPorProfesor']);
        Route::get('/api/estudiantes-por-filtros', [AsistenciaController::class, 'getEstudiantesPorFiltros']);

        // API para tabla AJAX
        Route::get('/api/tabla-asistencias', [AsistenciaController::class, 'getTablaAsistencias']);
        Route::get('/api/buscar-estudiantes', [AsistenciaController::class, 'buscarEstudiantes']);
    });

    // Rutas de Operaciones Masivas - COMENTADO: Controlador no existe
    // Route::prefix('operaciones-masivas')->name('operaciones-masivas.')->group(function () {
    //     Route::get('/', [OperacionesMasivasController::class, 'index'])->name('index');
    //     Route::post('/programar-recuperaciones', [OperacionesMasivasController::class, 'programarRecuperaciones'])->name('programar-recuperaciones');
    //     Route::post('/justificar-por-feriado', [OperacionesMasivasController::class, 'justificarPorFeriado'])->name('justificar-por-feriado');
    //     Route::post('/marcar-feriado', [OperacionesMasivasController::class, 'marcarFeriado'])->name('marcar-feriado');
    //
    //     // APIs para calendario y dashboard
    //     Route::get('/datos-calendario', [OperacionesMasivasController::class, 'datosCalendario'])->name('datos-calendario');
    //     Route::get('/estadisticas-dashboard', [OperacionesMasivasController::class, 'estadisticasDashboard'])->name('estadisticas-dashboard');
    // });

    // CRUD CursoAsignatura (asignación de asignaturas a cursos)
    // Rutas protegidas para gestionar asignaturas por curso
    Route::prefix('curso-asignatura')->name('cursoasignatura.')->group(function () {
        Route::get('/', [App\Http\Controllers\CursoAsignaturaController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\CursoAsignaturaController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\CursoAsignaturaController::class, 'store'])->name('store');
        Route::get('/{id}', [App\Http\Controllers\CursoAsignaturaController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [App\Http\Controllers\CursoAsignaturaController::class, 'edit'])->name('edit');
        Route::put('/{id}', [App\Http\Controllers\CursoAsignaturaController::class, 'update'])->name('update');

        Route::delete('/{id}', [App\Http\Controllers\CursoAsignaturaController::class, 'destroy'])->name('destroy');

        // Rutas auxiliares AJAX
        Route::get('/por-curso/{curso}', [App\Http\Controllers\CursoAsignaturaController::class, 'getByCurso'])->name('porcurso');
        Route::get('/horario/profesor/{profesor}', [App\Http\Controllers\CursoAsignaturaController::class, 'horarioProfesor'])->name('horario.profesor');
    });

    // CRUD Feriados (solo para administradores)
    Route::middleware(['auth'])->prefix('feriados')->name('feriados.')->group(function () {
        Route::get('/', [App\Http\Controllers\FeriadoController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\FeriadoController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\FeriadoController::class, 'store'])->name('store');
        Route::get('/{id}', [App\Http\Controllers\FeriadoController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [App\Http\Controllers\FeriadoController::class, 'edit'])->name('edit');
        Route::put('/{id}', [App\Http\Controllers\FeriadoController::class, 'update'])->name('update');
        Route::delete('/{id}', [App\Http\Controllers\FeriadoController::class, 'destroy'])->name('destroy');

        // Funciones adicionales
        Route::get('/api/anio/{anio}', [App\Http\Controllers\FeriadoController::class, 'getByAnio'])->name('api.anio');
    });

    // Usuarios
    Route::resource('/usuarios', UsuariosController::class);
    Route::get('/usuarios/{usuario}/confirmar', [UsuariosController::class, 'confirmar'])->name('usuarios.confirmar');
});

Route::get('/', [UserController::class, 'showLogin'])->name('login');
Route::get('/pass', [UserController::class, 'showLoginPassword'])->name('pass');
Route::get('/forgotPassword', [UserController::class, 'showForgotPassword'])->name('forgot');

// Ruta para servir archivos desde storage durante desarrollo
Route::get('/storage/{path}', function ($path) {
    $path = storage_path('app/public/' . $path);
    if (!file_exists($path)) {
        abort(404);
    }
    return response()->file($path);
})->where('path', '.*');
Route::post('/identificacion', [UserController::class, 'verificalogin'])->name('identificacion');
Route::post('/password', [UserController::class, 'verificapassword'])->name('password');
Route::post('/forgotPass', [UserController::class, 'enviarContrasenia'])->name('sendpassword');

Route::post('/send-email', [ContactoController::class, 'send'])->name('send.email');

Route::get('/estudiantes/{id}/ficha', [InfEstudianteController::class, 'generarFicha'])->name('estudiantes.ficha');
