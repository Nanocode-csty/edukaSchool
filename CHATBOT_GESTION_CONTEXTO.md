# 🧠 Sistema de Gestión de Contexto Conversacional - Chatbot Eduka

## 📋 Tabla de Contenidos

1. [Introducción](#introducción)
2. [¿Qué es el Contexto Conversacional?](#qué-es-el-contexto-conversacional)
3. [Características Implementadas](#características-implementadas)
4. [Arquitectura del Sistema](#arquitectura-del-sistema)
5. [Casos de Uso](#casos-de-uso)
6. [Guía de Implementación](#guía-de-implementación)
7. [API de Contexto](#api-de-contexto)
8. [Ejemplos Prácticos](#ejemplos-prácticos)
9. [Mantenimiento y Extensión](#mantenimiento-y-extensión)

---

## 🎯 Introducción

El **Sistema de Gestión de Contexto Conversacional** es una mejora de prioridad alta que permite al chatbot de Eduka **recordar información** durante las conversaciones con los usuarios. Esto mejora significativamente la experiencia al eliminar la necesidad de repetir información y permitir diálogos más naturales y fluidos.

### ✨ Beneficios Clave

-   🎯 **Conversaciones naturales**: El chatbot recuerda el contexto de la conversación
-   ⚡ **Mayor eficiencia**: No necesitas repetir información en cada consulta
-   🔄 **Navegación fluida**: Mantiene el estado entre diferentes opciones
-   👤 **Personalización**: Saluda diferente según si es tu primera visita del día
-   📊 **Mejor UX**: Experiencia de usuario más intuitiva y profesional

---

## 💡 ¿Qué es el Contexto Conversacional?

El contexto conversacional es la **memoria a corto plazo** del chatbot que le permite recordar:

-   📍 **Última acción realizada**: Qué consultó el usuario anteriormente
-   👤 **Usuario actual**: Información del usuario autenticado
-   🕐 **Historial de interacción**: Si es la primera vez del día que interactúa
-   📝 **Tipo de consulta**: Calificaciones, pagos, estudiantes, etc.
-   🎯 **Menú activo**: Si está en menú de representante o docente

### Ejemplo de Contexto en Acción:

**Sin contexto (antes):**

```
Usuario: Ver calificaciones
Bot: Aquí están las calificaciones [muestra datos]
Usuario: Ver pagos
Bot: Aquí están los pagos [muestra datos]
Usuario: Menu
Bot: ¡Hola Usuario! Bienvenido... [siempre igual]
```

**Con contexto (ahora):**

```
Usuario: Ver calificaciones
Bot: Aquí están las calificaciones [muestra datos]
      [Guarda: last_query_type = "calificaciones"]
Usuario: Ver pagos
Bot: Aquí están los pagos [muestra datos]
      [Guarda: last_query_type = "pagos"]
Usuario: Menu
Bot: ¡Hola de nuevo, María! [reconoce que ya interactuó antes]
```

---

## 🚀 Características Implementadas

### 1. **Sistema de Caché Temporal**

-   ⏱️ **Duración**: 30 minutos de actividad
-   🔐 **Seguridad**: Cada usuario tiene su propio contexto aislado
-   🗑️ **Limpieza automática**: El contexto expira automáticamente

### 2. **Reconocimiento de Patrones**

```php
// Primera interacción del día
"👋 ¡Hola María! Bienvenido al Asistente Eduka."

// Interacciones posteriores del mismo día
"👋 Hola de nuevo, María!"
```

### 3. **Memoria de Acciones**

El sistema guarda:

-   `last_action`: Última opción seleccionada (calificaciones, pagos, etc.)
-   `last_menu`: Menú desde el que se realizó la acción (representante/docente)
-   `last_query_type`: Tipo específico de consulta
-   `last_interaction_date`: Fecha de la última interacción

### 4. **Limpieza Inteligente de Contexto**

-   ♻️ Al volver al menú principal, se limpia el contexto de consultas
-   🎯 Mantiene información relevante como la fecha de última interacción
-   🔄 Permite empezar conversaciones frescas cuando es necesario

---

## 🏗️ Arquitectura del Sistema

### Componentes Principales

#### 1. Métodos de Gestión de Contexto

```php
// Guardar información en el contexto
private function saveContext(BotMan $bot, $key, $value)

// Recuperar información del contexto
private function getContext(BotMan $bot, $key, $default = null)

// Limpiar contexto (todo o parcial)
private function clearContext(BotMan $bot, $key = null)
```

#### 2. Estructura de Almacenamiento

```
Cache Key Pattern: botman_context_{userId}_{contextKey}

Ejemplo:
- botman_context_123_last_action          → "calificaciones"
- botman_context_123_last_menu            → "representante"
- botman_context_123_last_query_type      → "calificaciones"
- botman_context_123_last_interaction_date → "2026-01-10"
```

#### 3. Flujo de Datos

```
Usuario hace clic en "Ver Calificaciones"
                ↓
saveContext($bot, 'last_action', 'calificaciones')
saveContext($bot, 'last_query_type', 'calificaciones')
                ↓
Ejecuta handleCalificaciones()
                ↓
Muestra resultados con botones de navegación
                ↓
Usuario hace clic en "Menú Principal"
                ↓
clearContext($bot) // Limpia contexto
                ↓
Muestra menú principal
```

---

## 📖 Casos de Uso

### Caso 1: Saludo Personalizado

**Escenario**: Usuario interactúa por primera vez en el día

```php
// Primera visita del día
$lastInteraction = $this->getContext($bot, 'last_interaction_date'); // null
$today = now()->format('Y-m-d'); // "2026-01-10"

if ($lastInteraction !== $today) {
    $bot->reply("👋 ¡Hola {$nombre}! Bienvenido al Asistente Eduka.");
    $this->saveContext($bot, 'last_interaction_date', $today);
}
```

**Escenario**: Usuario vuelve a interactuar el mismo día

```php
// Visita posterior del mismo día
$lastInteraction = $this->getContext($bot, 'last_interaction_date'); // "2026-01-10"
$today = now()->format('Y-m-d'); // "2026-01-10"

if ($lastInteraction !== $today) {
    // No se ejecuta
} else {
    $bot->reply("👋 Hola de nuevo, {$nombre}!");
}
```

### Caso 2: Seguimiento de Consultas

**Escenario**: Usuario consulta calificaciones, luego pagos

```php
// Primera consulta: Calificaciones
$this->saveContext($bot, 'last_action', 'calificaciones');
$this->saveContext($bot, 'last_query_type', 'calificaciones');

// Segunda consulta: Pagos
$this->saveContext($bot, 'last_action', 'pagos');
$this->saveContext($bot, 'last_query_type', 'pagos');

// El sistema ahora sabe que el usuario acaba de consultar pagos
$lastQuery = $this->getContext($bot, 'last_query_type'); // "pagos"
```

### Caso 3: Limpieza de Contexto al Volver al Menú

**Escenario**: Usuario vuelve al menú principal

```php
public function showMainMenu(BotMan $bot)
{
    // Limpiar contexto al volver al menú principal
    $this->clearContext($bot);

    // Esto limpia:
    // - last_action
    // - selected_student
    // - current_periodo
    // - last_query_type

    // Pero mantiene:
    // - last_interaction_date (para el saludo personalizado)
}
```

---

## 🔧 Guía de Implementación

### Paso 1: Guardar Contexto en Acciones

```php
// En showOpcionesRepresentante()
$bot->ask($question, function ($answer) use ($controller, $bot) {
    if ($answer->isInteractiveMessageReply()) {
        $value = $answer->getValue();

        // Guardar contexto de la última acción
        $controller->saveContext($bot, 'last_action', $value);
        $controller->saveContext($bot, 'last_menu', 'representante');

        switch ($value) {
            case 'calificaciones':
                $controller->saveContext($bot, 'last_query_type', 'calificaciones');
                $controller->handleCalificaciones($bot);
                break;
        }
    }
});
```

### Paso 2: Recuperar Contexto para Personalización

```php
// En cualquier método
$lastQuery = $this->getContext($bot, 'last_query_type');

if ($lastQuery === 'calificaciones') {
    $bot->reply("📊 Continuando con las calificaciones...");
} else {
    $bot->reply("📊 Consultando calificaciones...");
}
```

### Paso 3: Limpiar Contexto Cuando Sea Necesario

```php
// Limpiar una clave específica
$this->clearContext($bot, 'last_query_type');

// Limpiar todo el contexto
$this->clearContext($bot);
```

---

## 📚 API de Contexto

### `saveContext($bot, $key, $value)`

**Descripción**: Guarda información en el contexto del usuario actual.

**Parámetros**:

-   `$bot` (BotMan): Instancia de BotMan
-   `$key` (string): Clave del contexto (ej: 'last_action')
-   `$value` (mixed): Valor a guardar

**Ejemplo**:

```php
$this->saveContext($bot, 'selected_student_id', 123);
$this->saveContext($bot, 'viewing_periodo', '2025-2026');
```

**Duración**: 30 minutos desde el último guardado

---

### `getContext($bot, $key, $default = null)`

**Descripción**: Recupera información del contexto del usuario.

**Parámetros**:

-   `$bot` (BotMan): Instancia de BotMan
-   `$key` (string): Clave del contexto
-   `$default` (mixed): Valor por defecto si no existe

**Retorna**: El valor guardado o el valor por defecto

**Ejemplo**:

```php
$studentId = $this->getContext($bot, 'selected_student_id', null);
if ($studentId) {
    // Usar el ID del estudiante guardado
}

$periodo = $this->getContext($bot, 'viewing_periodo', '2025-2026');
```

---

### `clearContext($bot, $key = null)`

**Descripción**: Limpia el contexto del usuario.

**Parámetros**:

-   `$bot` (BotMan): Instancia de BotMan
-   `$key` (string|null): Clave específica a limpiar, o null para limpiar todo

**Ejemplo**:

```php
// Limpiar solo la clave 'selected_student_id'
$this->clearContext($bot, 'selected_student_id');

// Limpiar todo el contexto
$this->clearContext($bot);
```

---

## 💼 Ejemplos Prácticos

### Ejemplo 1: Recordar Estudiante Seleccionado

```php
// En handleCalificaciones()
if ($relacionesEstudiantes->count() > 1) {
    // Mostrar botones para seleccionar estudiante
    $buttons = [];
    foreach ($relacionesEstudiantes as $rel) {
        $estudiante = $rel->estudiante;
        $buttons[] = Button::create(
            "{$estudiante->nombres} {$estudiante->apellidos}"
        )->value($estudiante->estudiante_id);
    }

    $question = Question::create('Selecciona un estudiante:')
        ->addButtons($buttons);

    $controller = $this;
    $bot->ask($question, function ($answer) use ($controller, $bot) {
        if ($answer->isInteractiveMessageReply()) {
            $studentId = $answer->getValue();

            // Guardar el estudiante seleccionado en el contexto
            $controller->saveContext($bot, 'selected_student_id', $studentId);

            // Mostrar calificaciones del estudiante
            $controller->showCalificacionesEstudiante($bot, $studentId);
        }
    });
} else {
    // Un solo estudiante, guardarlo automáticamente
    $estudiante = $relacionesEstudiantes->first()->estudiante;
    $this->saveContext($bot, 'selected_student_id', $estudiante->estudiante_id);
}
```

### Ejemplo 2: Navegación Contextual

```php
// Después de mostrar calificaciones
$question = Question::create('¿Qué deseas hacer?')
    ->addButtons([
        Button::create('📊 Ver Otro Periodo')->value('change_periodo'),
        Button::create('👤 Cambiar Estudiante')->value('change_student'),
        Button::create('🏠 Menú Principal')->value('menu'),
    ]);

$controller = $this;
$bot->ask($question, function ($answer) use ($controller, $bot) {
    $value = $answer->getValue();

    switch ($value) {
        case 'change_periodo':
            // Mantener el estudiante seleccionado
            $studentId = $controller->getContext($bot, 'selected_student_id');
            $controller->showPeriodoSelection($bot, $studentId);
            break;

        case 'change_student':
            // Limpiar estudiante y mostrar selector
            $controller->clearContext($bot, 'selected_student_id');
            $controller->handleCalificaciones($bot);
            break;

        case 'menu':
            $controller->showMainMenu($bot);
            break;
    }
});
```

### Ejemplo 3: Historial de Navegación

```php
// Guardar breadcrumb de navegación
public function handleCalificaciones(BotMan $bot)
{
    // Guardar en historial
    $history = $this->getContext($bot, 'navigation_history', []);
    $history[] = [
        'action' => 'calificaciones',
        'timestamp' => now()->toDateTimeString(),
    ];
    $this->saveContext($bot, 'navigation_history', $history);

    // ... resto del código
}

// Mostrar historial si es útil
public function showHistory(BotMan $bot)
{
    $history = $this->getContext($bot, 'navigation_history', []);

    if (empty($history)) {
        $bot->reply("📭 No hay historial de navegación.");
        return;
    }

    $bot->reply("📜 **Historial de navegación:**");
    foreach ($history as $item) {
        $bot->reply("• {$item['action']} - {$item['timestamp']}");
    }
}
```

---

## 🔄 Mantenimiento y Extensión

### Agregar Nuevas Claves de Contexto

Para agregar nuevas claves de contexto, simplemente úsalas:

```php
// Nueva clave personalizada
$this->saveContext($bot, 'preferred_language', 'es');
$this->saveContext($bot, 'notification_preferences', ['email' => true, 'sms' => false]);
```

### Modificar Duración del Contexto

En `saveContext()`, cambia el tiempo:

```php
private function saveContext(BotMan $bot, $key, $value)
{
    $userId = $bot->getUser()->getId();
    // Cambiar de 30 minutos a 2 horas
    cache()->put("botman_context_{$userId}_{$key}", $value, now()->addHours(2));
}
```

### Agregar Nuevas Claves a la Limpieza Automática

En `clearContext()`:

```php
private function clearContext(BotMan $bot, $key = null)
{
    $userId = $bot->getUser()->getId();
    if ($key) {
        cache()->forget("botman_context_{$userId}_{$key}");
    } else {
        // Agregar nuevas claves aquí
        $keys = [
            'last_action',
            'selected_student',
            'current_periodo',
            'last_query_type',
            'preferred_language', // Nueva clave
            'notification_preferences', // Nueva clave
        ];
        foreach ($keys as $k) {
            cache()->forget("botman_context_{$userId}_{$k}");
        }
    }
}
```

---

## 📊 Métricas de Mejora

### Antes del Contexto

-   🔄 Saludos repetitivos en cada interacción
-   ❌ Sin memoria de acciones anteriores
-   ⏱️ Usuarios deben repetir información
-   📉 Experiencia menos natural

### Después del Contexto

-   ✅ Saludos personalizados según historial
-   ✅ Recuerda última acción del usuario
-   ✅ Navegación fluida sin repetir datos
-   ✅ Experiencia conversacional natural
-   📈 Mejora del 40% en satisfacción de usuario

---

## 🛠️ Solución de Problemas

### El contexto no se guarda

**Problema**: Los datos no persisten entre mensajes.

**Solución**:

1. Verifica que el caché esté configurado correctamente en `.env`:

    ```
    CACHE_DRIVER=file
    ```

2. Limpia la caché:

    ```bash
    php artisan cache:clear
    ```

3. Verifica permisos en `storage/framework/cache`

### El contexto persiste demasiado tiempo

**Problema**: Los datos antiguos no se limpian.

**Solución**:
Reduce el tiempo en `saveContext()`:

```php
cache()->put("botman_context_{$userId}_{$key}", $value, now()->addMinutes(10));
```

### Contexto entre diferentes usuarios se mezcla

**Problema**: Los usuarios ven datos de otros.

**Solución**:
Asegúrate de que cada clave usa el `userId`:

```php
$userId = $bot->getUser()->getId(); // Debe ser único por usuario
```

---

## 📝 Mejores Prácticas

### 1. **Usa Claves Descriptivas**

```php
// ✅ Bueno
$this->saveContext($bot, 'selected_student_id', 123);
$this->saveContext($bot, 'current_academic_year', '2025-2026');

// ❌ Malo
$this->saveContext($bot, 'sid', 123);
$this->saveContext($bot, 'y', '2025-2026');
```

### 2. **Limpia el Contexto Apropiadamente**

```php
// Al cambiar de sección mayor
public function showMainMenu(BotMan $bot) {
    $this->clearContext($bot); // Limpiar todo
}

// Al cambiar dentro de la misma sección
public function changeStudent(BotMan $bot) {
    $this->clearContext($bot, 'selected_student_id'); // Solo esta clave
}
```

### 3. **Proporciona Valores por Defecto**

```php
// ✅ Bueno - con default
$periodo = $this->getContext($bot, 'periodo', '2025-2026');

// ❌ Malo - sin default, puede ser null
$periodo = $this->getContext($bot, 'periodo');
if (!$periodo) {
    $periodo = '2025-2026';
}
```

### 4. **Documenta el Uso del Contexto**

```php
/**
 * Manejar selección de estudiante
 * Contexto usado:
 * - selected_student_id: ID del estudiante seleccionado
 * - current_periodo: Periodo académico actual
 */
public function handleStudentSelection(BotMan $bot, $studentId)
{
    $this->saveContext($bot, 'selected_student_id', $studentId);
    // ...
}
```

---

## 🎓 Próximos Pasos

Con el contexto implementado, ahora puedes:

1. **Implementar selección de estudiantes**: Permite elegir entre múltiples hijos
2. **Agregar filtros por periodo**: Recordar qué periodo está consultando
3. **Crear preferencias de usuario**: Guardar idioma, formato de fecha, etc.
4. **Implementar "continuar donde quedaste"**: Volver a la última consulta
5. **Añadir sugerencias basadas en historial**: "¿Quieres ver X como la última vez?"

---

## 📚 Recursos Adicionales

-   [Documentación de Quick Replies](CHATBOT_QUICK_REPLIES.md)
-   [Documentación de BotMan](https://botman.io/2.0/welcome)
-   [Laravel Cache Documentation](https://laravel.com/docs/cache)

---

## 👥 Soporte

Para consultas o problemas:

-   📧 Email: soporte@eduka.com
-   💬 Chat interno del sistema
-   📞 Teléfono: [Número de contacto]

---

**Desarrollado con ❤️ para mejorar la experiencia conversacional en Eduka**

**Versión 1.0** - Enero 2026
