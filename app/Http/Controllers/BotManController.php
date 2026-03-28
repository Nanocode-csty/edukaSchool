<?php

namespace App\Http\Controllers;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\InfEstudiante;
use App\Models\InfRepresentante;
use App\Models\InfEstudianteRepresentante;
use App\Models\Matricula;
use App\Models\NotasFinalesPeriodo;
use App\Models\InfPago;
use App\Models\InfCurso;
use App\Services\NotificacionProactivaService;
use App\Services\ChatbotAnalyticsService;
use Illuminate\Http\Request as HttpRequest;

class BotManController extends Controller
{
    public function handle(HttpRequest $request)
    {
        if ($request->isMethod('get')) {
            return view('botman-web::chat');
        }

        $botman = app('botman');

        // Comando de inicio
        $botman->hears('hola|inicio|start|menu', function (BotMan $bot) {
            // Registrar interacción
            $this->registrarInteraccion($bot, 'menu');
            $this->showMainMenu($bot);
        });

        // Comandos para representantes
        $botman->hears('calificaciones|notas|ver notas', function (BotMan $bot) {
            $this->registrarInteraccion($bot, 'calificaciones');
            $this->handleCalificaciones($bot);
        });

        $botman->hears('pagos|ver pagos|pagos pendientes', function (BotMan $bot) {
            $this->registrarInteraccion($bot, 'pagos');
            $this->handlePagos($bot);
        });

        $botman->hears('horarios|ver horarios|horario', function (BotMan $bot) {
            $this->registrarInteraccion($bot, 'horarios');
            $this->handleHorarios($bot);
        });

        // Comandos para notificaciones (representantes)
        $botman->hears('notificaciones|alertas|ver notificaciones', function (BotMan $bot) {
            $this->registrarInteraccion($bot, 'notificaciones');

            $this->handleNotificaciones($bot);
        });

        // Comandos para docentes
        $botman->hears('estudiantes|ver estudiantes|info estudiantes', function (BotMan $bot) {
            $this->registrarInteraccion($bot, 'estudiantes');
            $this->handleEstudiantes($bot);
        });

        $botman->hears('cursos|ver cursos|info cursos', function (BotMan $bot) {
            $this->registrarInteraccion($bot, 'cursos');
            $this->handleCursos($bot);
        });

        $botman->hears('recordatorios|notificaciones|avisos', function (BotMan $bot) {
            $this->registrarInteraccion($bot, 'recordatorios');
            $this->handleRecordatorios($bot);
        });

        // Comandos para estadísticas (docentes/admin)
        $botman->hears('estadisticas|analytics|dashboard|metricas', function (BotMan $bot) {
            $this->registrarInteraccion($bot, 'estadisticas');

            $this->handleEstadisticas($bot);
        });

        // Comandos de ayuda y navegación
        $botman->hears('ayuda|help', function (BotMan $bot) {
            $this->showHelp($bot);
        });

        $botman->hears('volver|atras|regresar', function (BotMan $bot) {
            $this->showMainMenu($bot);
        });

        // Fallback para comandos no reconocidos
        $botman->fallback(function (BotMan $bot) {
            $bot->reply("🤔 Lo siento, no entendí ese comando.");
            $bot->reply("Escribe 'menu' para ver las opciones disponibles o 'ayuda' para obtener más información.");
        });

        $botman->listen();
    }

    /**
     * Guardar contexto del usuario
     */
    public function saveContext(BotMan $bot, $key, $value)
    {
        $userId = $bot->getUser()->getId();
        cache()->put("botman_context_{$userId}_{$key}", $value, now()->addMinutes(30));
    }

    /**
     * Obtener contexto del usuario
     */
    public function getContext(BotMan $bot, $key, $default = null)
    {
        $userId = $bot->getUser()->getId();
        return cache()->get("botman_context_{$userId}_{$key}", $default);
    }

    /**
     * Limpiar contexto del usuario
     */
    public function clearContext(BotMan $bot, $key = null)
    {
        $userId = $bot->getUser()->getId();
        if ($key) {
            cache()->forget("botman_context_{$userId}_{$key}");
        } else {
            // Limpiar todo el contexto del usuario
            $keys = ['last_action', 'selected_student', 'current_periodo', 'last_query_type'];
            foreach ($keys as $k) {
                cache()->forget("botman_context_{$userId}_{$k}");
            }
        }
    }

    /**
     * Mostrar menú principal según el rol del usuario
     */
    public function showMainMenu(BotMan $bot)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                $bot->reply('⚠️ Debes iniciar sesión para usar el chatbot.');
                return;
            }

            // Limpiar contexto al volver al menú principal
            $this->clearContext($bot);

            $rol = strtolower($user->rol);
            $nombre = $user->nombres ?? 'Usuario';

            // Verificar si es la primera interacción del día
            $lastInteraction = $this->getContext($bot, 'last_interaction_date');
            $today = now()->format('Y-m-d');
            
            if ($lastInteraction !== $today) {
                $bot->reply("👋 ¡Hola {$nombre}! Bienvenido al Asistente Eduka.");
                $this->saveContext($bot, 'last_interaction_date', $today);
            } else {
                $bot->reply("👋 Hola de nuevo, {$nombre}!");
            }


            if (in_array($rol, ['representante'])) {
                $this->showOpcionesRepresentante($bot);
            } elseif (in_array($rol, ['docente', 'profesor', 'admin', 'administrador'])) {
                $this->showOpcionesDocente($bot);
            } else {
                $bot->reply("⚠️ Tu rol ({$user->rol}) no tiene opciones configuradas en el chatbot.");
                $bot->reply("Por favor, contacta al administrador del sistema.");
            }


            $bot->reply("💡 Escribe 'ayuda' para ver todos los comandos disponibles.");
        } catch (\Exception $e) {
            Log::error('Error en showMainMenu: ' . $e->getMessage());
            $bot->reply('❌ Ocurrió un error. Por favor, intenta nuevamente.');
        }
    }

    /**
     * Opciones para representantes
     */
    public function showOpcionesRepresentante(BotMan $bot)
    {
        $controller = $this;
        
        // Verificar si hay notificaciones de alta prioridad
        try {
            $user = Auth::user();
            if ($user) {
                $notifService = new NotificacionProactivaService();
                $notificaciones = $notifService->obtenerTodasNotificaciones($user->email);
                
                $notificacionesAltas = array_filter($notificaciones, function ($n) {
                    return $n['prioridad'] === 'alta';
                });

                if (!empty($notificacionesAltas)) {
                    $count = count($notificacionesAltas);
                    $bot->reply("🔔 Tienes {$count} notificación(es) importante(s).");
                }
            }
        } catch (\Exception $e) {
            Log::error('Error al verificar notificaciones: ' . $e->getMessage());
        }
        
        $question = Question::create('📚 ¿Qué deseas consultar?')
            ->callbackId('menu_representante')
            ->addButtons([
                Button::create('📊 Ver Calificaciones')->value('calificaciones'),
                Button::create('💰 Consultar Pagos')->value('pagos'),
                Button::create('📅 Ver Horarios')->value('horarios'),
                Button::create('🔔 Ver Notificaciones')->value('notificaciones'),
                Button::create('❓ Ayuda')->value('ayuda'),
            ]);

        $bot->reply('Selecciona una opción:');
        $bot->ask($question, function ($answer) use ($controller, $bot) {
            if ($answer->isInteractiveMessageReply()) {
                $value = $answer->getValue();
                
                // Guardar contexto de la última acción
                $controller->saveContext($bot, 'last_action', $value);
                $controller->saveContext($bot, 'last_menu', 'representante');
                
                switch ($value) {
                    case 'calificaciones':
                    case 'notas':
                        $controller->saveContext($bot, 'last_query_type', 'calificaciones');
                        $controller->handleCalificaciones($bot);
                        break;
                    case 'pagos':
                        $controller->saveContext($bot, 'last_query_type', 'pagos');
                        $controller->handlePagos($bot);
                        break;
                    case 'horarios':
                        $controller->saveContext($bot, 'last_query_type', 'horarios');
                        $controller->handleHorarios($bot);
                        break;
                    case 'notificaciones':
                        $controller->saveContext($bot, 'last_query_type', 'notificaciones');
                        $controller->handleNotificaciones($bot);
                        break;
                    case 'ayuda':
                    case 'help':
                        $controller->showHelp($bot);
                        break;
                    default:
                        $bot->reply('⚠️ Opción no reconocida.');
                        $controller->showMainMenu($bot);
                }
            }
        });
    }

    /**
     * Opciones para docentes
     */
    public function showOpcionesDocente(BotMan $bot)
    {
        $controller = $this;
        
        $question = Question::create('👨‍🏫 ¿Qué deseas consultar?')
            ->callbackId('menu_docente')
            ->addButtons([
                Button::create('👥 Ver Estudiantes')->value('estudiantes'),
                Button::create('📚 Ver Cursos')->value('cursos'),
                Button::create('🔔 Ver Recordatorios')->value('recordatorios'),
                Button::create('📊 Ver Estadísticas')->value('estadisticas'),
                Button::create('❓ Ayuda')->value('ayuda'),
            ]);

        $bot->reply('Selecciona una opción:');
        $bot->ask($question, function ($answer) use ($controller, $bot) {
            if ($answer->isInteractiveMessageReply()) {
                $value = $answer->getValue();
                
                // Guardar contexto de la última acción
                $controller->saveContext($bot, 'last_action', $value);
                $controller->saveContext($bot, 'last_menu', 'docente');
                
                switch ($value) {
                    case 'estudiantes':
                        $controller->saveContext($bot, 'last_query_type', 'estudiantes');
                        $controller->handleEstudiantes($bot);
                        break;
                    case 'cursos':
                        $controller->saveContext($bot, 'last_query_type', 'cursos');
                        $controller->handleCursos($bot);
                        break;
                    case 'recordatorios':
                        $controller->saveContext($bot, 'last_query_type', 'recordatorios');
                        $controller->handleRecordatorios($bot);
                        break;
                    case 'estadisticas':
                    case 'analytics':
                        $controller->saveContext($bot, 'last_query_type', 'estadisticas');
                        $controller->handleEstadisticas($bot);
                        break;
                    case 'ayuda':
                    case 'help':
                        $controller->showHelp($bot);
                        break;
                    default:
                        $bot->reply('⚠️ Opción no reconocida.');
                        $controller->showMainMenu($bot);
                }
            }
        });
    }


    /**
     * Manejar consulta de calificaciones (REPRESENTANTES)
     */
    public function handleCalificaciones(BotMan $bot)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                $bot->reply('⚠️ Debes iniciar sesión.');
                return;
            }

            $rol = strtolower($user->rol);

            if (!in_array($rol, ['representante'])) {
                $bot->reply('⚠️ Esta opción es solo para representantes.');
                $this->showMainMenu($bot);
                return;
            }

            $bot->reply("📊 **CONSULTANDO CALIFICACIONES...**");
            $bot->reply("━━━━━━━━━━━━━━━━━━━━");

            // Buscar representante por email
            $representante = InfRepresentante::where('email', $user->email)->first();

            if (!$representante) {
                $bot->reply('⚠️ No se encontró información de representante asociada a tu cuenta.');
                $bot->reply('📧 Por favor, contacta al administrador para verificar tus datos.');
                return;
            }

            // Obtener estudiantes del representante (corregido)
            $relacionesEstudiantes = InfEstudianteRepresentante::where('representante_id', $representante->representante_id)
                ->with(['estudiante.matriculas' => function ($query) {
                    $query->where('estado', '!=', 'Anulado')
                        ->orderBy('anio_academico', 'desc')
                        ->limit(1);
                }])
                ->get();

            if ($relacionesEstudiantes->isEmpty()) {
                $bot->reply('📭 No tienes estudiantes asignados en el sistema.');
                $bot->reply('📞 Por favor, contacta al administrador para verificar esta información.');
                return;
            }
            $bot->reply("━━━━━━━━━━━━━━━━━━━━");            $bot->reply("👨‍🎓 **Tus estudiantes:**\n");
            $contador = 0;
            $estudiantesSinMatricula = 0;
            $estudiantesSinNotas = 0;

            foreach ($relacionesEstudiantes as $relacion) {
                $estudiante = $relacion->estudiante;

                if (!$estudiante) continue;

                $contador++;
                $matricula = $estudiante->matriculas->first();

                $bot->reply("**{$contador}. {$estudiante->nombres} {$estudiante->apellidos}**");

                if ($matricula) {
                    // Obtener promedio de notas del período actual
                    $notasPeriodo = NotasFinalesPeriodo::where('matricula_id', $matricula->matricula_id)
                        ->where('estado', 'Publicado')
                        ->get();

                    if ($notasPeriodo->isNotEmpty()) {
                        $promedio = $notasPeriodo->avg('promedio');
                        $aprobadas = $notasPeriodo->where('promedio', '>=', 11)->count();
                        $total = $notasPeriodo->count();

                        $emoji = $promedio >= 14 ? '🌟' : ($promedio >= 11 ? '✅' : '⚠️');

                        $bot->reply("   {$emoji} Promedio: " . number_format($promedio, 1));
                        $bot->reply("   📝 Asignaturas aprobadas: {$aprobadas}/{$total}");

                        if ($promedio < 11) {
                            $bot->reply("   ⚡ Requiere apoyo académico");
                        }
                    } else {
                        $estudiantesSinNotas++;
                        $bot->reply("   📋 Sin notas publicadas aún");
                        $bot->reply("   ℹ️ Las notas aparecerán cuando el docente las registre");
                    }

                    $bot->reply("   📚 Grado: " . ($matricula->grado->nombre ?? 'N/A'));
                    $bot->reply("   📊 Estado: " . $matricula->estado);
                } else {
                    $estudiantesSinMatricula++;
                    $bot->reply("   ⚠️ Sin matrícula activa este año");
                    $bot->reply("   📝 Debe matricularse para ver calificaciones");
                }

                $bot->reply("");  // Línea en blanco
            }

            // Resumen al final
            if ($estudiantesSinMatricula > 0 || $estudiantesSinNotas > 0) {
                $bot->reply("━━━━━━━━━━━━━━━━━━━━");
                $bot->reply("📌 **Resumen:**");

                if ($estudiantesSinMatricula > 0) {
                    $bot->reply("   ⚠️ {$estudiantesSinMatricula} estudiante(s) sin matrícula activa");
                    $bot->reply("   👉 Contacta a la institución para matricularlos");
                }

                if ($estudiantesSinNotas > 0) {
                    $bot->reply("   📋 {$estudiantesSinNotas} estudiante(s) sin notas publicadas");
                    $bot->reply("   👉 Las notas se mostrarán cuando sean registradas");
                }
            }

            $bot->reply("━━━━━━━━━━━━━━━━━━━━");
            $bot->reply("📄 **Ver más detalles:**");
            $bot->reply("🔗 " . url('/notas/consulta'));
            
            // Botones de navegación
            $controller = $this;
            $question = Question::create('¿Qué deseas hacer ahora?')
                ->callbackId('navigation')
                ->addButtons([
                    Button::create('🏠 Menú Principal')->value('menu'),
                    Button::create('💰 Ver Pagos')->value('pagos'),
                    Button::create('📅 Ver Horarios')->value('horarios'),
                ]);
            
            $bot->ask($question, function ($answer) use ($controller, $bot) {
                if ($answer->isInteractiveMessageReply()) {
                    $value = $answer->getValue();
                    switch ($value) {
                        case 'menu':
                        case 'inicio':
                            $controller->showMainMenu($bot);
                            break;
                        case 'pagos':
                            $controller->handlePagos($bot);
                            break;
                        case 'horarios':
                            $controller->handleHorarios($bot);
                            break;
                    }
                }
            });
        } catch (\Exception $e) {
            Log::error('Error en handleCalificaciones: ' . $e->getMessage());
            $bot->reply('❌ Ocurrió un error al consultar las calificaciones.');
            $bot->reply('Por favor, intenta nuevamente o contacta al administrador.');
        }
    }

    /**
     * Manejar consulta de pagos (REPRESENTANTES)
     */
    public function handlePagos(BotMan $bot)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                $bot->reply('⚠️ Debes iniciar sesión.');
                return;
            }

            $rol = strtolower($user->rol);

            if (!in_array($rol, ['representante'])) {
                $bot->reply('⚠️ Esta opción es solo para representantes.');
                $this->showMainMenu($bot);
                return;
            }

            $bot->reply("💰 **CONSULTANDO PAGOS...**");
            $bot->reply("━━━━━━━━━━━━━━━━━━━━");

            // Buscar representante
            $representante = InfRepresentante::where('email', $user->email)->first();

            if (!$representante) {
                $bot->reply('⚠️ No se encontró información de representante.');
                return;
            }

            // Obtener IDs de estudiantes
            $estudiantesIds = InfEstudianteRepresentante::where('representante_id', $representante->representante_id)
                ->pluck('estudiante_id');

            if ($estudiantesIds->isEmpty()) {
                $bot->reply('📭 No tienes estudiantes asignados.');
                return;
            }

            // Buscar pagos pendientes
            $pagosPendientes = InfPago::whereHas('matricula', function ($q) use ($estudiantesIds) {
                $q->whereIn('estudiante_id', $estudiantesIds)
                    ->where('estado', '!=', 'Anulado');
            })
                ->where('estado', 'pendiente')
                ->with(['matricula.estudiante', 'concepto'])
                ->orderBy('fecha_vencimiento', 'asc')
                ->limit(5)
                ->get();

            if ($pagosPendientes->isEmpty()) {
                $bot->reply('✅ ¡Excelente! No tienes pagos pendientes.');
                $bot->reply('📊 Todos tus pagos están al día.');
            } else {
                $bot->reply("⚠️ **Tienes {$pagosPendientes->count()} pago(s) pendiente(s):**\n");

                $totalDeuda = 0;

                foreach ($pagosPendientes as $index => $pago) {
                    $estudiante = $pago->matricula->estudiante ?? null;
                    $concepto = $pago->concepto->nombre ?? 'Pago';
                    $monto = $pago->monto;
                    $vencimiento = $pago->fecha_vencimiento;

                    $totalDeuda += $monto;

                    $estudianteNombre = $estudiante ? "{$estudiante->nombres} {$estudiante->apellidos}" : 'Estudiante';

                    // Verificar si está vencido
                    $esVencido = $vencimiento < now();
                    $emoji = $esVencido ? '🔴' : '🟡';

                    $bot->reply("{$emoji} **Pago " . ($index + 1) . "**");
                    $bot->reply("   👤 {$estudianteNombre}");
                    $bot->reply("   📝 Concepto: {$concepto}");
                    $bot->reply("   💵 Monto: S/ " . number_format($monto, 2));
                    $bot->reply("   📅 Vencimiento: " . $vencimiento->format('d/m/Y'));

                    if ($esVencido) {
                        $diasVencidos = now()->diffInDays($vencimiento);
                        $bot->reply("   ⏰ Vencido hace {$diasVencidos} día(s)");
                    }

                    $bot->reply("");
                }

                $bot->reply("━━━━━━━━━━━━━━━━━━━━");
                $bot->reply("💰 **Total pendiente: S/ " . number_format($totalDeuda, 2) . "**");
            }

            $bot->reply("━━━━━━━━━━━━━━━━━━━━");
            $bot->reply("📄 **Ver detalles completos y realizar pagos:**");
            $bot->reply("🔗 " . url('/pagos'));
            
            // Botones de navegación
            $controller = $this;
            $question = Question::create('¿Qué deseas hacer ahora?')
                ->callbackId('navigation')
                ->addButtons([
                    Button::create('🏠 Menú Principal')->value('menu'),
                    Button::create('📊 Ver Calificaciones')->value('calificaciones'),
                    Button::create('📅 Ver Horarios')->value('horarios'),
                ]);
            
            $bot->ask($question, function ($answer) use ($controller, $bot) {
                if ($answer->isInteractiveMessageReply()) {
                    $value = $answer->getValue();
                    switch ($value) {
                        case 'menu':
                        case 'inicio':
                            $controller->showMainMenu($bot);
                            break;
                        case 'calificaciones':
                        case 'notas':
                            $controller->handleCalificaciones($bot);
                            break;
                        case 'horarios':
                            $controller->handleHorarios($bot);
                            break;
                    }
                }
            });
        } catch (\Exception $e) {
            Log::error('Error en handlePagos: ' . $e->getMessage());
            $bot->reply('❌ Ocurrió un error al consultar los pagos.');
            $bot->reply('Por favor, intenta nuevamente.');
        }
    }

    /**
     * Manejar consulta de horarios (REPRESENTANTES)
     */
    public function handleHorarios(BotMan $bot)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                $bot->reply('⚠️ Debes iniciar sesión.');
                return;
            }

            $bot->reply("📅 **CONSULTA DE HORARIOS**");
            $bot->reply("━━━━━━━━━━━━━━━━━━━━");

            $bot->reply("📚 Los horarios completos de tus estudiantes están disponibles en la plataforma.");
            $bot->reply("\n🔗 **Accede aquí:**");
            $bot->reply(url('/home'));

            $bot->reply("\n📋 **Allí encontrarás:**");
            $bot->reply("   • Horarios por curso y sección");
            $bot->reply("   • Horarios de cada asignatura");
            $bot->reply("   • Horarios de los docentes");

            $bot->reply("\n💬 Escribe 'menu' para volver al menú principal");
        } catch (\Exception $e) {
            Log::error('Error en handleHorarios: ' . $e->getMessage());
            $bot->reply('❌ Ocurrió un error.');
        }
    }

    /**
     * Manejar información de estudiantes (DOCENTES)
     */
    public function handleEstudiantes(BotMan $bot)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                $bot->reply('⚠️ Debes iniciar sesión.');
                return;
            }

            $rol = strtolower($user->rol);

            if (!in_array($rol, ['docente', 'profesor', 'admin', 'administrador'])) {
                $bot->reply('⚠️ Esta opción es solo para docentes y administradores.');
                $this->showMainMenu($bot);
                return;
            }

            $bot->reply("👥 **INFORMACIÓN DE ESTUDIANTES**");
            $bot->reply("━━━━━━━━━━━━━━━━━━━━");

            // Estadísticas generales
            $totalEstudiantes = InfEstudiante::where('situacion_academica', 'Regular')->count();
            $totalMatriculados = Matricula::where('estado', 'Matriculado')
                ->where('anio_academico', date('Y'))
                ->distinct('estudiante_id')
                ->count('estudiante_id');

            $bot->reply("📊 **Estadísticas Generales:**");
            $bot->reply("   👨‍🎓 Total estudiantes activos: {$totalEstudiantes}");
            $bot->reply("   📝 Matriculados este año: {$totalMatriculados}");

            // Promedio general si hay notas
            $promedioGeneral = NotasFinalesPeriodo::where('estado', 'Publicado')->avg('promedio');

            if ($promedioGeneral) {
                $emoji = $promedioGeneral >= 14 ? '🌟' : ($promedioGeneral >= 11 ? '✅' : '⚠️');
                $bot->reply("   {$emoji} Promedio general: " . number_format($promedioGeneral, 2));
            } else {
                $bot->reply("   📋 Sin promedios calculados aún");
            }

            // Distribución por estado académico
            $aprobados = NotasFinalesPeriodo::where('estado', 'Publicado')
                ->where('promedio', '>=', 11)
                ->distinct('matricula_id')
                ->count('matricula_id');

            $desaprobados = NotasFinalesPeriodo::where('estado', 'Publicado')
                ->where('promedio', '<', 11)
                ->distinct('matricula_id')
                ->count('matricula_id');

            if ($aprobados + $desaprobados > 0) {
                $bot->reply("\n📈 **Rendimiento Académico:**");
                $bot->reply("   ✅ Aprobados: {$aprobados}");
                $bot->reply("   ⚠️ Requieren apoyo: {$desaprobados}");
            }

            $bot->reply("━━━━━━━━━━━━━━━━━━━━");
            $bot->reply("📄 **Ver información detallada:**");
            $bot->reply("🔗 " . url('/estudiante'));
            
            // Botones de navegación
            $controller = $this;
            $question = Question::create('¿Qué deseas hacer ahora?')
                ->callbackId('navigation')
                ->addButtons([
                    Button::create('🏠 Menú Principal')->value('menu'),
                    Button::create('📚 Ver Cursos')->value('cursos'),
                    Button::create('🔔 Ver Recordatorios')->value('recordatorios'),
                ]);
            
            $bot->ask($question, function ($answer) use ($controller, $bot) {
                if ($answer->isInteractiveMessageReply()) {
                    $value = $answer->getValue();
                    switch ($value) {
                        case 'menu':
                        case 'inicio':
                            $controller->showMainMenu($bot);
                            break;
                        case 'cursos':
                            $controller->handleCursos($bot);
                            break;
                        case 'recordatorios':
                            $controller->handleRecordatorios($bot);
                            break;
                    }
                }
            });
        } catch (\Exception $e) {
            Log::error('Error en handleEstudiantes: ' . $e->getMessage());
            $bot->reply('❌ Ocurrió un error al consultar la información.');
        }
    }

    /**
     * Manejar información de cursos (DOCENTES)
     */
    public function handleCursos(BotMan $bot)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                $bot->reply('⚠️ Debes iniciar sesión.');
                return;
            }

            $rol = strtolower($user->rol);

            if (!in_array($rol, ['docente', 'profesor', 'admin', 'administrador'])) {
                $bot->reply('⚠️ Esta opción es solo para docentes y administradores.');
                $this->showMainMenu($bot);
                return;
            }

            $bot->reply("📖 **INFORMACIÓN DE CURSOS**");

            $bot->reply("━━━━━━━━━━━━━━━━━━━━");

            // Obtener cursos activos (limitado)
            $cursos = InfCurso::with(['grado', 'seccion', 'anoLectivo'])
                ->whereIn('estado', ['Disponible', 'En Curso'])
                ->limit(5)
                ->get();

            if ($cursos->isEmpty()) {
                $bot->reply('📭 No hay cursos activos en este momento.');
            } else {
                $bot->reply("📚 **Cursos Activos:**\n");

                foreach ($cursos as $index => $curso) {
                    $gradoNombre = $curso->grado->nombre ?? 'N/A';
                    $seccionNombre = $curso->seccion->nombre ?? 'N/A';
                    $estado = $curso->estado;
                    $cupoMaximo = $curso->cupo_maximo ?? 'N/A';

                    // Contar matriculados en el curso
                    $matriculados = Matricula::where('idGrado', $curso->grado_id)
                        ->where('idSeccion', $curso->seccion_id)
                        ->where('estado', 'Matriculado')
                        ->count();

                    $emoji = $estado == 'En Curso' ? '📘' : '📙';

                    $bot->reply("{$emoji} **Curso " . ($index + 1) . "**");
                    $bot->reply("   🎓 {$gradoNombre} - Sección {$seccionNombre}");
                    $bot->reply("   📊 Estado: {$estado}");
                    $bot->reply("   👥 Matriculados: {$matriculados}/{$cupoMaximo}");
                    $bot->reply("");
                }
            }

            $bot->reply("━━━━━━━━━━━━━━━━━━━━");
            $bot->reply("📄 **Ver todos los cursos:**");
            $bot->reply("🔗 " . url('/registrarcurso'));
            
            // Botones de navegación
            $controller = $this;
            $question = Question::create('¿Qué deseas hacer ahora?')
                ->callbackId('navigation')
                ->addButtons([
                    Button::create('🏠 Menú Principal')->value('menu'),
                    Button::create('👥 Ver Estudiantes')->value('estudiantes'),
                    Button::create('🔔 Ver Recordatorios')->value('recordatorios'),
                ]);
            
            $bot->ask($question, function ($answer) use ($controller, $bot) {
                if ($answer->isInteractiveMessageReply()) {
                    $value = $answer->getValue();
                    switch ($value) {
                        case 'menu':
                        case 'inicio':
                            $controller->showMainMenu($bot);
                            break;
                        case 'estudiantes':
                            $controller->handleEstudiantes($bot);
                            break;
                        case 'recordatorios':
                            $controller->handleRecordatorios($bot);
                            break;
                    }
                }
            });
        } catch (\Exception $e) {
            Log::error('Error en handleCursos: ' . $e->getMessage());
            $bot->reply('❌ Ocurrió un error al consultar los cursos.');
        }
    }

    /**
     * Manejar recordatorios (DOCENTES)
     */
    public function handleRecordatorios(BotMan $bot)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                $bot->reply('⚠️ Debes iniciar sesión.');
                return;
            }

            $rol = strtolower($user->rol);

            if (!in_array($rol, ['docente', 'profesor', 'admin', 'administrador'])) {
                $bot->reply('⚠️ Esta opción es solo para docentes y administradores.');
                $this->showMainMenu($bot);
                return;
            }

            $bot->reply("🔔 **RECORDATORIOS Y NOTIFICACIONES**");
            $bot->reply("━━━━━━━━━━━━━━━━━━━━");

            // Contar pagos pendientes
            $pagosPendientes = InfPago::where('estado', 'pendiente')
                ->where('fecha_vencimiento', '>=', now())
                ->count();

            $pagosVencidos = InfPago::where('estado', 'pendiente')
                ->where('fecha_vencimiento', '<', now())
                ->count();

            // Contar matrículas pendientes
            $matriculasPendientes = Matricula::whereIn('estado', ['Pendiente', 'Pre-inscrito'])
                ->count();

            $matriculasActivas = Matricula::where('estado', 'Matriculado')
                ->where('anio_academico', date('Y'))
                ->count();

            $bot->reply("💰 **Pagos:**");
            $bot->reply("   🟡 Pendientes: {$pagosPendientes}");
            $bot->reply("   🔴 Vencidos: {$pagosVencidos}");

            $bot->reply("\n📝 **Matrículas:**");
            $bot->reply("   ⏳ Pendientes de completar: {$matriculasPendientes}");
            $bot->reply("   ✅ Activas este año: {$matriculasActivas}");

            if ($pagosVencidos > 0 || $matriculasPendientes > 0) {
                $bot->reply("\n⚠️ **Atención requerida:**");

                if ($pagosVencidos > 0) {
                    $bot->reply("   • Hay {$pagosVencidos} pago(s) vencido(s) que requieren seguimiento");
                }

                if ($matriculasPendientes > 0) {
                    $bot->reply("   • {$matriculasPendientes} matrícula(s) pendiente(s) de completar");
                }
            } else {
                $bot->reply("\n✅ **Todo en orden!**");
            }

            $bot->reply("━━━━━━━━━━━━━━━━━━━━");
            $bot->reply("📄 **Ver detalles completos:**");
            $bot->reply("🔗 Pagos: " . url('/pagos'));
            $bot->reply("🔗 Matrículas: " . url('/matriculas'));
            
            // Botones de navegación
            $controller = $this;
            $question = Question::create('¿Qué deseas hacer ahora?')
                ->callbackId('navigation')
                ->addButtons([
                    Button::create('🏠 Menú Principal')->value('menu'),
                    Button::create('👥 Ver Estudiantes')->value('estudiantes'),
                    Button::create('📚 Ver Cursos')->value('cursos'),
                ]);
            
            $bot->ask($question, function ($answer) use ($controller, $bot) {
                if ($answer->isInteractiveMessageReply()) {
                    $value = $answer->getValue();
                    switch ($value) {
                        case 'menu':
                        case 'inicio':
                            $controller->showMainMenu($bot);
                            break;
                        case 'estudiantes':
                            $controller->handleEstudiantes($bot);
                            break;
                        case 'cursos':
                            $controller->handleCursos($bot);
                            break;
                    }
                }
            });
        } catch (\Exception $e) {
            Log::error('Error en handleRecordatorios: ' . $e->getMessage());
            $bot->reply('❌ Ocurrió un error al consultar los recordatorios.');
        }
    }

    /**
     * Mostrar ayuda
     */
    public function showHelp(BotMan $bot)
    {
        $user = Auth::user();
        $rol = $user ? strtolower($user->rol) : '';

        $bot->reply("COMANDOS DISPONIBLES");

        $bot->reply("🔹 **Comandos generales:**");
        $bot->reply("   • menu, inicio, hola - Menú principal");
        $bot->reply("   • ayuda, help - Esta ayuda");
        $bot->reply("   • volver, atras - Volver al menú");

        if (in_array($rol, ['representante'])) {
            $bot->reply("\n🔹 **Para Representantes:**");
            $bot->reply("   • calificaciones, notas - Ver notas");
            $bot->reply("   • pagos - Consultar pagos");
            $bot->reply("   • horarios - Ver horarios");
            $bot->reply("   • notificaciones - Ver alertas y notificaciones");
        }

        if (in_array($rol, ['docente', 'profesor', 'admin', 'administrador'])) {
            $bot->reply("\n🔹 **Para Docentes:**");
            $bot->reply("   • estudiantes - Info de estudiantes");
            $bot->reply("   • cursos - Info de cursos");
            $bot->reply("   • recordatorios - Avisos y notificaciones");
            $bot->reply("   • estadisticas - Ver métricas del chatbot");
        }

        $bot->reply("━━━━━━━━━━━━━━━━━━━━");
        $bot->reply("💡 **Tip:** Puedes escribir los comandos en cualquier momento.");
    }

    /**
     * Mostrar notificaciones proactivas (REPRESENTANTES)
     */
    public function handleNotificaciones(BotMan $bot)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                $bot->reply('⚠️ Debes iniciar sesión.');
                return;
            }

            $rol = strtolower($user->rol);

            if (!in_array($rol, ['representante'])) {
                $bot->reply('⚠️ Esta opción es solo para representantes.');
                $this->showMainMenu($bot);
                return;
            }

            $bot->reply("🔔 **NOTIFICACIONES Y ALERTAS**");
            $bot->reply("━━━━━━━━━━━━━━━━━━━━");

            // Obtener servicio de notificaciones
            $notifService = new NotificacionProactivaService();
            $notificaciones = $notifService->obtenerTodasNotificaciones($user->email);

            if (empty($notificaciones)) {
                $bot->reply("✅ ¡Excelente! No tienes notificaciones pendientes.");
                $bot->reply("📭 Todo está al día.");
                
                // Botones de navegación
                $controller = $this;
                $question = Question::create('¿Qué deseas hacer ahora?')
                    ->callbackId('navigation')
                    ->addButtons([
                        Button::create('🏠 Menú Principal')->value('menu'),
                        Button::create('💰 Ver Pagos')->value('pagos'),
                        Button::create('📊 Ver Calificaciones')->value('calificaciones'),
                    ]);
                
                $bot->ask($question, function ($answer) use ($controller, $bot) {
                    if ($answer->isInteractiveMessageReply()) {
                        $value = $answer->getValue();
                        switch ($value) {
                            case 'menu':
                                $controller->showMainMenu($bot);
                                break;
                            case 'pagos':
                                $controller->handlePagos($bot);
                                break;
                            case 'calificaciones':
                                $controller->handleCalificaciones($bot);
                                break;
                        }
                    }
                });
                
                return;
            }

            // Contar notificaciones por prioridad
            $contadores = $notifService->contarPorPrioridad($notificaciones);

            $bot->reply("📊 **Resumen de notificaciones:**");
            $bot->reply("   🔴 Alta prioridad: {$contadores['alta']}");
            $bot->reply("   🟡 Media prioridad: {$contadores['media']}");
            $bot->reply("   ⚪ Total: {$contadores['total']}\n");

            // Mostrar notificaciones (máximo 10)
            $notificacionesMostrar = array_slice($notificaciones, 0, 10);
            
            foreach ($notificacionesMostrar as $index => $notif) {
                $emoji = $this->getEmojiPrioridad($notif['prioridad']);
                $numero = $index + 1;
                
                $bot->reply("{$emoji} **Notificación {$numero}:**");
                $bot->reply($notif['mensaje']);
                
                if (isset($notif['estudiante'])) {
                    $bot->reply("   👤 Estudiante: {$notif['estudiante']}");
                }
                
                $bot->reply("");
            }

            if (count($notificaciones) > 10) {
                $restantes = count($notificaciones) - 10;
                $bot->reply("... y {$restantes} notificación(es) más.");
            }

            $bot->reply("━━━━━━━━━━━━━━━━━━━━");
            $bot->reply("💡 **Consejo:** Revisa las notificaciones de alta prioridad primero.");

            // Botones de navegación
            $controller = $this;
            $question = Question::create('¿Qué deseas hacer ahora?')
                ->callbackId('navigation')
                ->addButtons([
                    Button::create('🏠 Menú Principal')->value('menu'),
                    Button::create('💰 Ver Pagos')->value('pagos'),
                    Button::create('📊 Ver Calificaciones')->value('calificaciones'),
                ]);
            
            $bot->ask($question, function ($answer) use ($controller, $bot) {
                if ($answer->isInteractiveMessageReply()) {
                    $value = $answer->getValue();
                    switch ($value) {
                        case 'menu':
                            $controller->showMainMenu($bot);
                            break;
                        case 'pagos':
                            $controller->handlePagos($bot);
                            break;
                        case 'calificaciones':
                            $controller->handleCalificaciones($bot);
                            break;
                    }
                }
            });

        } catch (\Exception $e) {
            Log::error('Error en handleNotificaciones: ' . $e->getMessage());
            $bot->reply('❌ Ocurrió un error al consultar las notificaciones.');
        }
    }

    /**
     * Obtener emoji según prioridad
     */
    private function getEmojiPrioridad($prioridad)
    {
        switch ($prioridad) {
            case 'alta':
                return '🔴';
            case 'media':
                return '🟡';
            case 'baja':
                return '🟢';
            default:
                return '⚪';
        }
    }

    /**
     * Mostrar notificaciones al inicio de sesión (si las hay)
     */
    private function mostrarNotificacionesInicio(BotMan $bot, $email)
    {
        try {
            $notifService = new NotificacionProactivaService();
            $notificaciones = $notifService->obtenerTodasNotificaciones($email);
            
            // Solo mostrar si hay notificaciones de alta prioridad
            $notificacionesAltas = array_filter($notificaciones, function ($n) {
                return $n['prioridad'] === 'alta';
            });

            if (!empty($notificacionesAltas)) {
                $count = count($notificacionesAltas);
                $bot->reply("🔔 Tienes {$count} notificación(es) importante(s).");
                $bot->reply("💡 Escribe 'notificaciones' para verlas.");
            }
        } catch (\Exception $e) {
            Log::error('Error en mostrarNotificacionesInicio: ' . $e->getMessage());
        }
    }

    /**
     * Registrar interacción para analytics
     */
    private function registrarInteraccion(BotMan $bot, $comando)
    {
        try {
            $user = Auth::user();
            if ($user) {
                $analytics = new ChatbotAnalyticsService();
                $userId = $bot->getUser()->getId();
                $analytics->registrarInteraccion($userId, $comando, strtolower($user->rol));
            }
        } catch (\Exception $e) {
            Log::error('Error al registrar interacción: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar estadísticas del chatbot (DOCENTES/ADMIN)
     */
    public function handleEstadisticas(BotMan $bot)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                $bot->reply('⚠️ Debes iniciar sesión.');
                return;
            }

            $rol = strtolower($user->rol);

            if (!in_array($rol, ['docente', 'profesor', 'admin', 'administrador'])) {
                $bot->reply('⚠️ Esta opción es solo para docentes y administradores.');
                $this->showMainMenu($bot);
                return;
            }

            $bot->reply("📊 **ESTADÍSTICAS DEL CHATBOT**");
            $bot->reply("━━━━━━━━━━━━━━━━━━━━");

            // Opciones de periodo
            $controller = $this;
            $question = Question::create('📅 Selecciona el periodo a consultar:')
                ->callbackId('periodo_stats')
                ->addButtons([
                    Button::create('📅 Últimos 7 días')->value('7'),
                    Button::create('📅 Últimos 30 días')->value('30'),
                    Button::create('👤 Mis estadísticas')->value('personal'),
                    Button::create('🏠 Volver al menú')->value('menu'),
                ]);

            $bot->ask($question, function ($answer) use ($controller, $bot) {
                if ($answer->isInteractiveMessageReply()) {
                    $value = $answer->getValue();
                    
                    switch ($value) {
                        case '7':
                            $controller->mostrarEstadisticasGenerales($bot, 7);
                            break;
                        case '30':
                            $controller->mostrarEstadisticasGenerales($bot, 30);
                            break;
                        case 'personal':
                            $controller->mostrarEstadisticasPersonales($bot);
                            break;
                        case 'menu':
                            $controller->showMainMenu($bot);
                            break;
                        default:
                            $bot->reply('⚠️ Opción no reconocida.');
                            $controller->handleEstadisticas($bot);
                            break;
                    }
                } else {
                    $bot->reply('⚠️ Por favor selecciona una opción usando los botones.');
                    $controller->handleEstadisticas($bot);
                }
            });

        } catch (\Exception $e) {
            Log::error('Error en handleEstadisticas: ' . $e->getMessage());
            $bot->reply('❌ Ocurrió un error al consultar las estadísticas.');
        }
    }

    /**
     * Mostrar estadísticas generales
     */
    public function mostrarEstadisticasGenerales(BotMan $bot, $dias = 7)
    {
        try {
            $analytics = new ChatbotAnalyticsService();
            $reporte = $analytics->generarReporte($dias);

            if (!$reporte || !$reporte['estadisticas_generales'] || $reporte['estadisticas_generales']['total_interacciones'] == 0) {
                $bot->reply("ℹ️ **No hay datos disponibles para este periodo**");
                $bot->reply("━━━━━━━━━━━━━━━━━━━━");
                $bot->reply("El sistema de estadísticas acaba de iniciar.");
                $bot->reply("Las métricas se generarán a medida que los usuarios interactúen con el chatbot.");
                $bot->reply("\n💡 **Consejo:** Usa el chatbot y vuelve a consultar más tarde.");
                $bot->reply("\n💬 Escribe 'menu' para volver al menú principal.");
                return;
            }

            $stats = $reporte['estadisticas_generales'];
            $tendencias = $reporte['tendencias'];
            $comandosPopulares = $reporte['comandos_populares'];
            $horarioPico = $reporte['horario_pico'];

            $bot->reply("📊 **REPORTE DE {$dias} DÍAS**");
            $bot->reply("━━━━━━━━━━━━━━━━━━━━\n");

            // Resumen general
            $bot->reply("📈 **Resumen General:**");
            $bot->reply("   • Total de interacciones: {$stats['total_interacciones']}");
            $bot->reply("   • Usuarios únicos: {$stats['total_usuarios_unicos']}");
            $bot->reply("   • Promedio diario: {$tendencias['promedio_diario']} interacciones\n");

            // Tendencias
            if ($tendencias['crecimiento']) {
                $emoji = $tendencias['crecimiento']['direccion'] === 'aumento' ? '📈' : ($tendencias['crecimiento']['direccion'] === 'disminución' ? '📉' : '➡️');
                $bot->reply("📊 **Tendencia:**");
                $bot->reply("   {$emoji} {$tendencias['crecimiento']['direccion']} del {$tendencias['crecimiento']['porcentaje']}% respecto al periodo anterior\n");
            }

            // Comandos más usados
            if (!empty($comandosPopulares)) {
                $bot->reply("🏆 **Comandos Más Usados:**");
                foreach ($comandosPopulares as $index => $cmd) {
                    $numero = $index + 1;
                    $bot->reply("   {$numero}. {$cmd['comando']}: {$cmd['usos']} usos ({$cmd['porcentaje']}%)");
                }
                $bot->reply("");
            }

            // Horario pico
            if (!empty($horarioPico)) {
                $bot->reply("⏰ **Horarios con Más Actividad:**");
                foreach ($horarioPico as $index => $horario) {
                    $numero = $index + 1;
                    $bot->reply("   {$numero}. {$horario['hora']}: {$horario['interacciones']} interacciones");
                }
                $bot->reply("");
            }

            // Uso por rol
            if (!empty($tendencias['uso_por_rol'])) {
                $bot->reply("👥 **Uso por Rol:**");
                foreach ($tendencias['uso_por_rol'] as $uso) {
                    $bot->reply("   • {$uso['rol']}: {$uso['interacciones']} ({$uso['porcentaje']}%)");
                }
                $bot->reply("");
            }

            // Día más activo
            if ($tendencias['dia_mas_activo']) {
                $bot->reply("🌟 **Día Más Activo:**");
                $bot->reply("   {$tendencias['dia_mas_activo']['fecha']}: {$tendencias['dia_mas_activo']['interacciones']} interacciones\n");
            }

            $bot->reply("━━━━━━━━━━━━━━━━━━━━");
            $bot->reply("📅 Reporte generado: {$reporte['fecha_generacion']}");
            $bot->reply("\n💬 Escribe 'menu' para volver o 'estadisticas' para consultar otro periodo.");

        } catch (\Exception $e) {
            Log::error('Error en mostrarEstadisticasGenerales: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            $bot->reply('❌ Ocurrió un error al mostrar las estadísticas.');
            $bot->reply('💬 Escribe "menu" para volver al menú principal.');
        }
    }

    /**
     * Mostrar estadísticas personales del usuario
     */
    public function mostrarEstadisticasPersonales(BotMan $bot)
    {
        try {
            $analytics = new ChatbotAnalyticsService();
            $userId = $bot->getUser()->getId();
            $stats = $analytics->obtenerEstadisticasUsuario($userId, 30);

            if (!$stats || $stats['total_interacciones'] === 0) {
                $bot->reply("ℹ️ **No tienes interacciones registradas**");
                $bot->reply("━━━━━━━━━━━━━━━━━━━━");
                $bot->reply("Aún no tienes suficiente actividad en los últimos 30 días.");
                $bot->reply("\n💡 **Consejo:** Usa más el chatbot para generar tus estadísticas personales.");
                $bot->reply("\n💬 Escribe 'menu' para volver al menú principal.");
                return;
            }

            $bot->reply("👤 **TUS ESTADÍSTICAS**");
            $bot->reply("━━━━━━━━━━━━━━━━━━━━\n");

            // Resumen personal
            $bot->reply("📊 **Resumen de Actividad:**");
            $bot->reply("   • Total de interacciones: {$stats['total_interacciones']}");
            $bot->reply("   • Días activos: {$stats['total_dias_activos']} de 30");
            
            if ($stats['ultimo_acceso_formatted']) {
                $bot->reply("   • Último acceso: {$stats['ultimo_acceso_formatted']}");
            }
            if ($stats['primer_acceso_formatted']) {
                $bot->reply("   • Primer acceso: {$stats['primer_acceso_formatted']}");
            }
            $bot->reply("");

            // Comando favorito
            if ($stats['comando_favorito']) {
                $bot->reply("⭐ **Tu Comando Favorito:**");
                $bot->reply("   {$stats['comando_favorito']} ({$stats['comandos_usados'][$stats['comando_favorito']]} veces)\n");
            }

            // Comandos usados
            if (!empty($stats['comandos_usados'])) {
                $bot->reply("📋 **Tus Comandos Usados:**");
                $count = 0;
                foreach ($stats['comandos_usados'] as $comando => $usos) {
                    if ($count >= 5) break; // Máximo 5
                    $count++;
                    $porcentaje = round(($usos / $stats['total_interacciones']) * 100, 1);
                    $bot->reply("   • {$comando}: {$usos} veces ({$porcentaje}%)");
                }
                $bot->reply("");
            }

            $bot->reply("━━━━━━━━━━━━━━━━━━━━");
            $bot->reply("💡 ¡Sigue usando el chatbot para mejorar tus estadísticas!");
            $bot->reply("\n💬 Escribe 'menu' para volver o 'estadisticas' para consultar otro periodo.");

        } catch (\Exception $e) {
            Log::error('Error en mostrarEstadisticasPersonales: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            $bot->reply('❌ Ocurrió un error al mostrar tus estadísticas.');
            $bot->reply('💬 Escribe "menu" para volver al menú principal.');
        }
    }
}
