<?php

use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\BotManController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ContactoController;
use App\Http\Controllers\ComunicadosController;
use App\Http\Controllers\DireccionController;
use App\Http\Controllers\EstudianteController;
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
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\PrincipalController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LandingController;

Route::get('/regiones', [DireccionController::class, 'regiones']);
Route::get('/provincias/{idRegion}', [DireccionController::class, 'provincias']);
Route::get('/distritos/{idProvincia}', [DireccionController::class, 'distritos']);

// Landing page route
Route::get('/', [LandingController::class, 'index'])->name('landing');

Route::middleware(['auth'])->group(function () {
    Route::post('/pagos/preferencia', [InfPagoController::class, 'crearPreferencia'])->name('pagos.crearPreferencia');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/pagos/success', [InfPagoController::class, 'success'])->name('pagos.success');
    Route::get('/pagos/failure', [InfPagoController::class, 'failure'])->name('pagos.failure');
    Route::get('/pagos/pending', [InfPagoController::class, 'pending'])->name('pagos.pending');
});

// Validación manual de pagos desde frontend
Route::middleware(['auth'])->group(function () {
    Route::post('/pagos/validar', [InfPagoController::class, 'validar'])->name('pagos.validar');
});

Route::get('auth/google', [SocialAuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback']);

Route::post('/logout', [UsuarioController::class, 'salir'])->name('logout');

// API públicas para filtros avanzados (no requieren autenticación)
Route::prefix('asistencia/api')->name('asistencia.api.')->group(function () {
    Route::get('/niveles', [InfNivelController::class, 'apiIndex'])->name('niveles');
    Route::get('/grados', [InfGradoController::class, 'apiIndex'])->name('grados');
    Route::get('/cursos', [InfCursoController::class, 'apiIndex'])->name('cursos');
    Route::get('/secciones', [InfSeccionController::class, 'apiIndex'])->name('secciones');
    Route::get('/estudiantes', [InfEstudianteController::class, 'apiIndex'])->name('estudiantes');
    Route::get('/docentes', [InfDocenteController::class, 'apiIndex'])->name('docentes');
    Route::get('/asignaturas', [InfAsignaturaController::class, 'apiIndex'])->name('asignaturas');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    //SECCION SISTEMA
    Route::get('/comunicados', [ComunicadosController::class, 'index'])->name('comunicado.index');
    Route::post('/create', [ComunicadosController::class, 'store'])->name('comunicado.store');

    Route::match(['get', 'post'], '/botman', [BotManController::class, 'handle'])->name('botman');

    Route::get('rutarrr1', [PrincipalController::class, 'index'])->name('rutarrr1');

    // Profile Management Routes
    Route::prefix('perfil')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::get('/editar', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::post('/foto', [ProfileController::class, 'updatePhoto'])->name('photo.update');
    });

    Route::get('/rutarrr2', [ClienteController::class, 'index'])->name('rutarrr2');
    Route::get('/rutarrr3', [UsuarioController::class, 'index'])->name('rutarrr3');
    Route::get('/rutarrr4', [ProductosController::class, 'index'])->name('rutarrr4');

    Route::post('/registrorepresentanteestudiante', [InfRepresentanteController::class, 'store'])->name('registrorepresentanteestudiante.store');

    Route::resource('/estudiantes', InfEstudianteController::class);
    Route::resource('/representante', InfRepresentanteController::class);
    Route::post('/buscar-representante', [InfRepresentanteController::class, 'buscarPorDni'])->name('buscar.representante');
    Route::post('/asignar-representante', [InfRepresentanteController::class, 'asignarRepresentante'])->name('asignar.representante');

    Route::get('/verificar-dni', [InfEstudianteController::class, 'verificarDni'])->name('verificar.dni');
    Route::get('/verificar-dni-representante', [InfRepresentanteController::class, 'verificarDniRepresentante'])->name('verificar.dni.representante');
    Route::get('/verificar-correo-representante', [InfRepresentanteController::class, 'verificarCorreoRepresentante'])->name('verificar.email.representante');

    Route::resource('/docente', InfDocenteController::class);
    Route::get('/verificar-dni-docente', [InfDocenteController::class, 'verificarDniDocente'])->name('verificar.dni.docente');
    Route::get('/verificar-correo-docente', [InfDocenteController::class, 'verificarCorreoDocente'])->name('verificar.email.docente');

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
    Route::get('/matriculas/periodos', [MatriculaController::class, 'periodos'])->name('matriculas.periodos');
    Route::get('/matriculas/api/periodo-actual', [MatriculaController::class, 'getPeriodoActual'])->name('matriculas.periodo.actual');

    // Rutas para gestión de períodos académicos
    Route::prefix('periodos')->name('periodos.')->group(function () {
        Route::get('/', [App\Http\Controllers\PeriodosController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\PeriodosController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\PeriodosController::class, 'store'])->name('store');
        Route::get('/{periodo}/edit', [App\Http\Controllers\PeriodosController::class, 'edit'])->name('edit');
        Route::put('/{periodo}', [App\Http\Controllers\PeriodosController::class, 'update'])->name('update');
        Route::delete('/{periodo}', [App\Http\Controllers\PeriodosController::class, 'destroy'])->name('destroy');

        // Notificaciones
        Route::get('/notificaciones', [App\Http\Controllers\PeriodosController::class, 'notificaciones'])->name('notificaciones');
        Route::post('/notificaciones/crear', [App\Http\Controllers\PeriodosController::class, 'crearNotificacion'])->name('notificaciones.crear');

        // Historial de matrículas
        Route::get('/historial-matriculas', [App\Http\Controllers\PeriodosController::class, 'historialMatriculas'])->name('historial-matriculas');

        // Verificación manual
        Route::post('/verificar', [App\Http\Controllers\PeriodosController::class, 'verificarPeriodos'])->name('verificar');
    });

    Route::resource('/registrarcurso', InfCursoController::class);
    Route::get('/registrarcurso/cancelar', function () {
        return redirect()->route('registrarcurso.index')->with('datos', 'Acción Cancelada !!!');
    })->name('registrarcurso.cancelar');
    Route::get('registrarcurso/{curso_id}/confirmar', [InfCursoController::class, 'confirmar'])->name('registrarcurso.confirmar');

    // Rutas para Conceptos de Pago
    Route::prefix('registroconceptopago')->name('conceptospago.')->group(function () {
        Route::get('/', [InfConceptoPagoController::class, 'index'])->name('index');
        Route::get('/create', [InfConceptoPagoController::class, 'create'])->name('create');
        Route::post('/', [InfConceptoPagoController::class, 'store'])->name('store');
        Route::get('/{id}', [InfConceptoPagoController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [InfConceptoPagoController::class, 'edit'])->name('edit');
        Route::put('/{id}', [InfConceptoPagoController::class, 'update'])->name('update');
        Route::delete('/{id}', [InfConceptoPagoController::class, 'destroy'])->name('destroy');
    });

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
    Route::get('/notas/estudiante/{id}', [NotasController::class, 'verNotasEstudiante'])->name('notas.estudiante')->withoutMiddleware('auth');
    Route::get('/notas/buscar-ajax', [NotasController::class, 'buscarEstudianteAjax'])->name('notas.buscarEstudiante');
    // Nueva ruta para representantes (ver sus estudiantes)
    Route::get('/mis-estudiantes', [NotasController::class, 'misEstudiantes'])->name('notas.misEstudiantes');
    Route::get('/calificaciones/representante', [NotasController::class, 'representanteCalificaciones'])->name('calificaciones.representante');

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
        // Rutas administrativas
        Route::get('/admin', [AsistenciaController::class, 'adminIndex'])
            ->name('admin-index');
        Route::get('/reportes', [AsistenciaController::class, 'reportes'])
            ->name('reportes');

        Route::get('/verificar', [AsistenciaController::class, 'verificar'])
            ->name('verificar');
        Route::post('/procesar-verificacion', [AsistenciaController::class, 'procesarVerificacion'])
            ->name('procesar-verificacion');
        Route::get('/exportar/excel/admin', [AsistenciaController::class, 'exportarExcelAdmin'])
            ->name('exportar-excel-admin');
        Route::get('/exportar/pdf/admin', [AsistenciaController::class, 'exportarPDFAdmin'])
            ->name('exportar-pdf-admin');
        Route::post('/guardar-reporte-exportado/{formato}', [AsistenciaController::class, 'guardarReporteExportado'])
            ->name('guardar-reporte-exportado');
        Route::get('/descargar-reporte/{reporteId}', [AsistenciaController::class, 'descargarReporteHistorial'])
            ->name('descargar-reporte-historial');

        // Ruta adicional para compatibilidad con la vista docente-dashboard
        Route::get('/reportes/descargar/{reporteId}', [AsistenciaController::class, 'descargarReporteHistorial'])
            ->name('reportes.descargar');

        // Rutas para docentes
        Route::prefix('docente')->name('docente.')->group(function () {
            Route::get('/', [AsistenciaController::class, 'docenteIndex'])
                ->name('index');
            Route::get('/dashboard', [AsistenciaController::class, 'docenteDashboard'])
                ->name('dashboard');
            Route::get('/tomar-asistencia', [AsistenciaController::class, 'docenteTomarAsistencia'])
                ->name('tomar-asistencia');
            Route::get('/ver-asistencias', [AsistenciaController::class, 'docenteVerAsistencias'])
                ->name('ver-asistencias');
            Route::get('/estadisticas', [AsistenciaController::class, 'docenteEstadisticas'])
                ->name('estadisticas');
            Route::get('/ver/{sesionClase}', [AsistenciaController::class, 'docenteVerAsistencia'])
                ->name('ver');
            Route::get('/editar/{sesionClase}', [AsistenciaController::class, 'docenteEditarAsistencia'])
                ->name('editar');
            Route::get('/obtener-estudiantes', [AsistenciaController::class, 'docenteObtenerEstudiantes'])
                ->name('obtener-estudiantes');
            Route::post('/guardar', [AsistenciaController::class, 'docenteGuardarAsistencia'])
                ->name('guardar');
            Route::get('/exportar-pdf/{sesionClase}', [AsistenciaController::class, 'docenteExportarPDF'])
                ->name('exportar-pdf');
        });

        // Ruta para exportar asistencias filtradas
        Route::get('/exportar-asistencias', [AsistenciaController::class, 'exportarAsistenciasFiltradas'])
            ->name('exportar-asistencias');

        // Rutas para representantes
        Route::prefix('representante')->name('representante.')->group(function () {
            Route::get('/dashboard', [AsistenciaController::class, 'representanteDashboard'])
                ->name('dashboard');
            Route::get('/', [AsistenciaController::class, 'representanteIndex'])
                ->name('index');
            Route::get('/detalle/{estudiante_id}', [AsistenciaController::class, 'representanteDetalle'])
                ->name('detalle');
            Route::post('/solicitar-justificacion', [AsistenciaController::class, 'representanteSolicitarJustificacion'])
                ->name('solicitar-justificacion');
            Route::get('/exportar-reporte/{estudiante}', [AsistenciaController::class, 'representanteExportarReporte'])
                ->name('exportar-reporte');
        });

        // API para tabla AJAX (requiere autenticación)
        Route::get('/api/tabla-asistencias', [AsistenciaController::class, 'getTablaAsistencias'])
            ->name('api.tabla-asistencias');
        Route::get('/api/estadisticas-filtradas', [AsistenciaController::class, 'getEstadisticasFiltradas'])
            ->name('api.estadisticas-filtradas');
        Route::get('/api/filtros-dinamicos', [AsistenciaController::class, 'getFiltrosDinamicos'])
            ->name('api.filtros-dinamicos');
        Route::get('/api/ranking-estudiantes', [AsistenciaController::class, 'getRankingEstudiantes'])
            ->name('api.ranking-estudiantes');
        Route::get('/api/buscar-estudiantes', [AsistenciaController::class, 'buscarEstudiantes'])
            ->name('api.buscar-estudiantes');
        Route::post('/api/guardar-reporte', [AsistenciaController::class, 'guardarReporteGenerado'])
            ->name('api.guardar-reporte');
        Route::post('/api/guardar-reporte-excel', [AsistenciaController::class, 'guardarReporteExcel'])
            ->name('api.guardar-reporte-excel');
        Route::get('/api/ultimos-reportes', [AsistenciaController::class, 'getUltimosReportes'])
            ->name('api.ultimos-reportes');
        Route::get('/api/sesiones-por-dia/{fecha}', [AsistenciaController::class, 'getSesionesPorDia'])
            ->name('api.sesiones-por-dia');
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
    Route::resource('/usuarios', UsuarioController::class);
    Route::get('/usuarios/{usuario}/confirmar', [UsuarioController::class, 'confirmar'])->name('usuarios.confirmar');

    // Notificaciones
    Route::prefix('notificaciones')->name('notificaciones.')->group(function () {
        Route::get('/', [NotificacionController::class, 'index'])->name('index');
        Route::get('/contar-no-leidas', [NotificacionController::class, 'countUnread'])->name('count-unread');
        Route::get('/recientes', [NotificacionController::class, 'recent'])->name('recent');
        Route::patch('/{notificacion}/marcar-leida', [NotificacionController::class, 'markAsRead'])->name('mark-read');
        Route::patch('/marcar-todas-leidas', [NotificacionController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::delete('/{notificacion}', [NotificacionController::class, 'destroy'])->name('destroy');
    });

    // Rutas para estudiantes
    Route::prefix('estudiante')->name('estudiante.')->group(function () {
        Route::get('/dashboard', [EstudianteController::class, 'dashboard'])->name('dashboard');
        Route::get('/asistencia', [EstudianteController::class, 'verAsistencia'])->name('asistencia');
        Route::get('/calificaciones', [EstudianteController::class, 'verCalificaciones'])->name('calificaciones');
    });
});

Route::get('/login.index', [UsuarioController::class, 'showLogin'])->name('login.index');

// TEMPORAL: Ruta de prueba para diagnosticar
Route::get('/test', function () {
    return 'Test route works!';
})->name('test');
Route::get('/pass', [UsuarioController::class, 'showLoginPassword'])->name('pass');
Route::get('/forgotPassword', [UsuarioController::class, 'showForgotPassword'])->name('forgot');

// Ruta para servir archivos desde storage durante desarrollo
Route::get('/storage/{path}', function ($path) {
    $path = storage_path('app/public/' . $path);
    if (!file_exists($path)) {
        abort(404);
    }
    return response()->file($path);
})->where('path', '.*');
Route::post('/identificacion', [UsuarioController::class, 'verificalogin'])->name('identificacion');
Route::post('/password', [UsuarioController::class, 'verificapassword'])->name('password');
Route::post('/forgotPass', [UsuarioController::class, 'enviarContrasenia'])->name('sendpassword');

Route::post('/send-email', [ContactoController::class, 'send'])->name('send.email');

Route::get('/estudiantes/{id}/ficha', [InfEstudianteController::class, 'generarFicha'])->name('estudiantes.ficha');
