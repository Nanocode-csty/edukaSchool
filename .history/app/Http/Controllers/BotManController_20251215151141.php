<?php

namespace App\Http\Controllers;

use BotMan\BotMan\BotMan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\InfEstudiante;
use App\Models\InfRepresentante;
use App\Models\InfEstudianteRepresentante;
use App\Models\Matricula;
use App\Models\NotasFinalesPeriodo;
use App\Models\InfPago;
use App\Models\InfCurso;
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
            $this->showMainMenu($bot);
        });

        // Comandos para representantes
        $botman->hears('calificaciones|notas|ver notas', function (BotMan $bot) {
            $this->handleCalificaciones($bot);
        });

        $botman->hears('pagos|ver pagos|pagos pendientes', function (BotMan $bot) {
            $this->handlePagos($bot);
        });

        $botman->hears('horarios|ver horarios|horario', function (BotMan $bot) {
            $this->handleHorarios($bot);
        });

        // Comandos para docentes
        $botman->hears('estudiantes|ver estudiantes|info estudiantes', function (BotMan $bot) {
            $this->handleEstudiantes($bot);
        });

        $botman->hears('cursos|ver cursos|info cursos', function (BotMan $bot) {
            $this->handleCursos($bot);
        });

        $botman->hears('recordatorios|notificaciones|avisos', function (BotMan $bot) {
            $this->handleRecordatorios($bot);
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
     * Mostrar menú principal según el rol del usuario
     */
    private function showMainMenu(BotMan $bot)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                $bot->reply('⚠️ Debes iniciar sesión para usar el chatbot.');
                return;
            }

            $rol = strtolower($user->rol);
            $nombre = $user->nombres ?? 'Usuario';

            $bot->reply("👋 ¡Hola {$nombre}! Bienvenido al Asistente Eduka.");


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
    private function showOpcionesRepresentante(BotMan $bot)
    {
        $bot->reply("📚 **Opciones disponibles para Representantes:**\n");
        $bot->reply("📊 **Calificaciones** - Ver notas de tus estudiantes\n   Escribe: 'calificaciones' o 'notas'");
        $bot->reply("💰 **Pagos** - Consultar pagos pendientes\n   Escribe: 'pagos'");
        $bot->reply("📅 **Horarios** - Ver horarios de clase\n   Escribe: 'horarios'");
    }

    /**
     * Opciones para docentes
     */
    private function showOpcionesDocente(BotMan $bot)
    {
        $bot->reply("Aquí tienes tus opciones disponibles, escribe la palabra en <b>negrita</b> para realizar tus consultas:");

        $bot->reply("
- Ver y gestionar información de tus estudiantes.
👉 Escribe: <b>estudiantes</b> <br>

- Consulta la información de los cursos asignados.
👉 Escribe: <b>cursos</b> <br>

- Recibe avisos sobre pagos o matrículas pendientes.
👉 Escribe: <b>recordatorios</b>
    ");
    }


    /**
     * Manejar consulta de calificaciones (REPRESENTANTES)
     */
    private function handleCalificaciones(BotMan $bot)
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

            $bot->reply("👨‍🎓 **Tus estudiantes:**\n");
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
            $bot->reply("\n💬 Escribe 'menu' para volver al menú principal");
        } catch (\Exception $e) {
            Log::error('Error en handleCalificaciones: ' . $e->getMessage());
            $bot->reply('❌ Ocurrió un error al consultar las calificaciones.');
            $bot->reply('Por favor, intenta nuevamente o contacta al administrador.');
        }
    }

    /**
     * Manejar consulta de pagos (REPRESENTANTES)
     */
    private function handlePagos(BotMan $bot)
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
            $bot->reply("\n💬 Escribe 'menu' para volver al menú principal");
        } catch (\Exception $e) {
            Log::error('Error en handlePagos: ' . $e->getMessage());
            $bot->reply('❌ Ocurrió un error al consultar los pagos.');
            $bot->reply('Por favor, intenta nuevamente.');
        }
    }

    /**
     * Manejar consulta de horarios (REPRESENTANTES)
     */
    private function handleHorarios(BotMan $bot)
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
    private function handleEstudiantes(BotMan $bot)
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
            $totalEstudiantes = InfEstudiante::where('estado', 'Activo')->count();
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
            $bot->reply("\n💬 Escribe 'menu' para volver al menú principal");
        } catch (\Exception $e) {
            Log::error('Error en handleEstudiantes: ' . $e->getMessage());
            $bot->reply('❌ Ocurrió un error al consultar la información.');
        }
    }

    /**
     * Manejar información de cursos (DOCENTES)
     */
    private function handleCursos(BotMan $bot)
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
            $bot->reply("\n💬 Escribe 'menu' para volver al menú principal");
        } catch (\Exception $e) {
            Log::error('Error en handleCursos: ' . $e->getMessage());
            $bot->reply('❌ Ocurrió un error al consultar los cursos.');
        }
    }

    /**
     * Manejar recordatorios (DOCENTES)
     */
    private function handleRecordatorios(BotMan $bot)
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
            $bot->reply("\n💬 Escribe 'menu' para volver al menú principal");
        } catch (\Exception $e) {
            Log::error('Error en handleRecordatorios: ' . $e->getMessage());
            $bot->reply('❌ Ocurrió un error al consultar los recordatorios.');
        }
    }

    /**
     * Mostrar ayuda
     */
    private function showHelp(BotMan $bot)
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
        }

        if (in_array($rol, ['docente', 'profesor', 'admin', 'administrador'])) {
            $bot->reply("\n🔹 **Para Docentes:**");
            $bot->reply("   • estudiantes - Info de estudiantes");
            $bot->reply("   • cursos - Info de cursos");
            $bot->reply("   • recordatorios - Avisos y notificaciones");
        }

        $bot->reply("━━━━━━━━━━━━━━━━━━━━");
        $bot->reply("💡 **Tip:** Puedes escribir los comandos en cualquier momento.");
    }
}
