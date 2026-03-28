# 🔔 Notificaciones Proactivas - Chatbot Eduka

## 📋 Descripción General

El sistema de **Notificaciones Proactivas** permite al chatbot alertar automáticamente a los usuarios sobre eventos importantes, fechas cercanas y situaciones que requieren atención inmediata. Este sistema monitorea activamente la base de datos para detectar:

-   💰 Pagos próximos a vencer
-   🔴 Pagos vencidos
-   📊 Nuevas calificaciones publicadas
-   🔔 Alertas importantes

---

## 🎯 Características Principales

### 1. **Sistema de Prioridades**

Las notificaciones se clasifican por prioridad:

| Prioridad | Emoji | Descripción                 | Ejemplo                   |
| --------- | ----- | --------------------------- | ------------------------- |
| **Alta**  | 🔴    | Requiere atención inmediata | Pago vencido hace 3+ días |
| **Media** | 🟡    | Requiere atención próxima   | Pago vence en 3-7 días    |
| **Baja**  | 🟢    | Informativa                 | Pago vence en 7+ días     |

### 2. **Tipos de Notificaciones**

#### 💰 Pagos Próximos a Vencer

-   Detecta pagos pendientes según días restantes
-   Mensajes personalizados según urgencia
-   Incluye monto, fecha y estudiante

#### 🔴 Pagos Vencidos

-   Alertas de alta prioridad
-   Muestra días de retraso
-   Información de monto adeudado

#### 📊 Nuevas Calificaciones

-   Notifica cuando se publican nuevas notas
-   Muestra asignatura y periodo
-   Incluye calificación obtenida

---

## 🚀 Cómo Usar el Sistema

### Para Representantes

#### 1. **Ver Notificaciones**

```
Usuario: notificaciones
o
Usuario: alertas
o
Usuario: ver notificaciones
```

El chatbot mostrará:

-   Resumen por prioridad
-   Lista de notificaciones ordenadas
-   Información detallada de cada alerta

#### 2. **Desde el Menú Principal**

1. Escribir `menu`
2. Seleccionar: **🔔 Ver Notificaciones**
3. El chatbot mostrará todas las alertas activas

#### 3. **Alertas Automáticas al Inicio**

Al escribir `menu`, si hay notificaciones de **alta prioridad**, el chatbot mostrará:

```
🔔 Tienes 2 notificación(es) importante(s).
💡 Escribe 'notificaciones' para verlas.
```

---

## 💻 Implementación Técnica

### Arquitectura del Sistema

```
BotManController
    ↓
NotificacionProactivaService
    ↓
┌──────────────────────────────────┐
│  Verificación de Notificaciones  │
├──────────────────────────────────┤
│ • verificarPagosVencidos()       │
│ • verificarPagosProximosVencer() │
│ • verificarNuevasCalificaciones()│
└──────────────────────────────────┘
    ↓
┌──────────────────────────────────┐
│    Procesamiento y Formato       │
├──────────────────────────────────┤
│ • obtenerTodasNotificaciones()   │
│ • contarPorPrioridad()           │
│ • generarMensajePago()           │
└──────────────────────────────────┘
    ↓
Respuesta al Usuario con Botones
```

### Servicio: `NotificacionProactivaService`

Ubicación: `app/Services/NotificacionProactivaService.php`

#### Métodos Principales

##### 1. `verificarPagosProximosVencer($email, $diasAnticipacion = 7)`

Busca pagos que vencen en los próximos N días.

**Parámetros:**

-   `$email`: Email del representante
-   `$diasAnticipacion`: Días de anticipación (default: 7)

**Retorna:**

```php
[
    [
        'tipo' => 'pago_proximo_vencer',
        'prioridad' => 'alta|media|baja',
        'estudiante' => 'Juan Pérez',
        'monto' => 150.00,
        'fecha_vencimiento' => '2025-01-20',
        'dias_restantes' => 5,
        'mensaje' => '🟡 ATENCIÓN: Pago de $150.00 vence en 5 días (20/01/2025)'
    ],
    // ...
]
```

**Lógica de Prioridades:**

```php
- 0-2 días restantes: ALTA (🔴 URGENTE)
- 3-5 días restantes: ALTA (🟡 ATENCIÓN)
- 6-7 días restantes: MEDIA (🟢 RECORDATORIO)
- 8+ días: BAJA
```

##### 2. `verificarPagosVencidos($email)`

Busca pagos con fecha de vencimiento pasada.

**Retorna:**

```php
[
    [
        'tipo' => 'pago_vencido',
        'prioridad' => 'alta',
        'estudiante' => 'María García',
        'monto' => 200.00,
        'fecha_vencimiento' => '2025-01-10',
        'dias_vencido' => 5,
        'mensaje' => '🔴 URGENTE: Pago vencido hace 5 días - Monto: $200.00'
    ],
    // ...
]
```

##### 3. `verificarNuevasCalificaciones($email, $diasAtras = 7)`

Busca calificaciones publicadas en los últimos N días.

**Parámetros:**

-   `$email`: Email del representante
-   `$diasAtras`: Días hacia atrás a consultar (default: 7)

**Retorna:**

```php
[
    [
        'tipo' => 'nueva_calificacion',
        'prioridad' => 'media',
        'estudiante' => 'Pedro López',
        'asignatura' => 'Matemáticas',
        'calificacion' => 8.5,
        'periodo' => 'Primer Quimestre',
        'fecha_publicacion' => '2025-01-14',
        'mensaje' => '📊 Nueva calificación en Matemáticas: 8.5/10 (Primer Quimestre)'
    ],
    // ...
]
```

##### 4. `obtenerTodasNotificaciones($email)`

Combina todas las notificaciones y las ordena por prioridad.

**Retorna:** Array con todas las notificaciones ordenadas (alta → media → baja)

##### 5. `contarPorPrioridad($notificaciones)`

Cuenta notificaciones por nivel de prioridad.

**Retorna:**

```php
[
    'alta' => 2,
    'media' => 3,
    'baja' => 1,
    'total' => 6
]
```

---

## 🎨 Interfaz de Usuario

### Ejemplo de Respuesta del Chatbot

```
🔔 NOTIFICACIONES Y ALERTAS
━━━━━━━━━━━━━━━━━━━━

📊 Resumen de notificaciones:
   🔴 Alta prioridad: 2
   🟡 Media prioridad: 3
   ⚪ Total: 5

🔴 Notificación 1:
🔴 URGENTE: Pago vencido hace 3 días - Monto: $150.00
   👤 Estudiante: Juan Pérez

🔴 Notificación 2:
🟡 ATENCIÓN: Pago de $100.00 vence en 4 días (19/01/2025)
   👤 Estudiante: María García

🟡 Notificación 3:
📊 Nueva calificación en Matemáticas: 8.5/10 (Primer Quimestre)
   👤 Estudiante: Pedro López

━━━━━━━━━━━━━━━━━━━━
💡 Consejo: Revisa las notificaciones de alta prioridad primero.

¿Qué deseas hacer ahora?
[🏠 Menú Principal] [💰 Ver Pagos] [📊 Ver Calificaciones]
```

### Cuando No Hay Notificaciones

```
🔔 NOTIFICACIONES Y ALERTAS
━━━━━━━━━━━━━━━━━━━━

✅ ¡Excelente! No tienes notificaciones pendientes.
📭 Todo está al día.

¿Qué deseas hacer ahora?
[🏠 Menú Principal] [💰 Ver Pagos] [📊 Ver Calificaciones]
```

---

## 🔧 Configuración

### Parámetros Personalizables

En `NotificacionProactivaService.php`:

```php
// Días de anticipación para pagos
$diasAnticipacion = 7; // Alertar con 7 días de anticipación

// Días hacia atrás para calificaciones
$diasAtras = 7; // Buscar notas de los últimos 7 días

// Límite de notificaciones a mostrar
$limite = 10; // Mostrar máximo 10 notificaciones
```

### Lógica de Prioridad de Pagos

```php
// Alta prioridad
if ($diasRestantes <= 2 || $pago->dias_vencido > 0) {
    $prioridad = 'alta';
}

// Media prioridad
elseif ($diasRestantes <= 5) {
    $prioridad = 'alta';
}

// Baja prioridad
elseif ($diasRestantes <= 7) {
    $prioridad = 'media';
}
```

---

## 📊 Casos de Uso

### Caso 1: Representante con Pagos Vencidos

**Escenario:**

-   Representante tiene 2 pagos vencidos
-   1 pago próximo a vencer

**Flujo:**

1. Usuario escribe `menu`
2. Chatbot muestra: "🔔 Tienes 3 notificación(es) importante(s)."
3. Usuario selecciona **🔔 Ver Notificaciones**
4. Chatbot muestra las 3 notificaciones ordenadas por prioridad
5. Usuario puede navegar a **💰 Ver Pagos** para más detalles

### Caso 2: Representante con Nuevas Calificaciones

**Escenario:**

-   Se publicaron 3 nuevas calificaciones en los últimos 7 días

**Flujo:**

1. Usuario escribe `notificaciones`
2. Chatbot muestra resumen: "🟡 Media prioridad: 3"
3. Lista las 3 calificaciones con asignatura y nota
4. Usuario puede ir a **📊 Ver Calificaciones** para más detalles

### Caso 3: Sin Notificaciones

**Escenario:**

-   Todo está al día, sin alertas pendientes

**Flujo:**

1. Usuario escribe `menu`
2. NO aparece mensaje de notificaciones
3. Usuario selecciona **🔔 Ver Notificaciones**
4. Chatbot muestra: "✅ ¡Excelente! No tienes notificaciones pendientes."

---

## 🛠️ Integración con BotManController

### Comandos Habilitados

```php
// En handle() method
$botman->hears('notificaciones|alertas|ver notificaciones', function (BotMan $bot) {
    $this->handleNotificaciones($bot);
});
```

### Método Principal: `handleNotificaciones()`

```php
public function handleNotificaciones(BotMan $bot)
{
    // 1. Verificar autenticación
    // 2. Validar rol (solo representantes)
    // 3. Obtener notificaciones del servicio
    // 4. Mostrar resumen por prioridad
    // 5. Listar notificaciones (máx. 10)
    // 6. Mostrar botones de navegación
}
```

### Integración en Menú de Representantes

```php
public function showOpcionesRepresentante(BotMan $bot)
{
    // Verificar notificaciones de alta prioridad
    $notifService = new NotificacionProactivaService();
    $notificaciones = $notifService->obtenerTodasNotificaciones($user->email);

    // Mostrar alerta si hay notificaciones importantes
    if (count($notificacionesAltas) > 0) {
        $bot->reply("🔔 Tienes {$count} notificación(es) importante(s).");
    }

    // Agregar botón de notificaciones
    Button::create('🔔 Ver Notificaciones')->value('notificaciones')
}
```

---

## 📈 Beneficios del Sistema

### Para Representantes

✅ **Proactividad:** Reciben alertas antes de que sea tarde  
✅ **Organización:** Priorizan pagos y tareas importantes  
✅ **Tranquilidad:** No olvidan fechas críticas  
✅ **Información:** Están al tanto de nuevas calificaciones

### Para la Institución

✅ **Menos morosidad:** Alertas tempranas mejoran cobros  
✅ **Mejor comunicación:** Notificaciones automáticas  
✅ **Satisfacción:** Servicio proactivo mejora la experiencia  
✅ **Eficiencia:** Reduce consultas manuales

---

## 🔍 Troubleshooting

### Problema: No Aparecen Notificaciones

**Causa posible:** No hay datos en las últimas 7 días

**Solución:**

```php
// Aumentar el rango de días
$diasAtras = 30; // Buscar en últimos 30 días
```

### Problema: Demasiadas Notificaciones

**Causa posible:** Muchos pagos pendientes

**Solución:**

```php
// Ajustar el límite de visualización
$notificacionesMostrar = array_slice($notificaciones, 0, 5); // Mostrar solo 5
```

### Problema: Prioridades Incorrectas

**Causa posible:** Lógica de prioridad no ajustada

**Solución:**

```php
// Revisar y ajustar los rangos en generarMensajePago()
if ($diasRestantes <= 3) { // Cambiar de 2 a 3
    $prioridad = 'alta';
}
```

---

## 🎯 Mejoras Futuras

### Fase 2 (Próxima)

-   [ ] Notificaciones push al navegador
-   [ ] Envío de notificaciones por email
-   [ ] Configuración personalizada de alertas
-   [ ] Notificaciones de eventos escolares

### Fase 3 (Futuro)

-   [ ] Integración con calendario
-   [ ] Recordatorios programados
-   [ ] Notificaciones de asistencia
-   [ ] Alertas de comportamiento

---

## 📚 Referencias

-   Código: `app/Services/NotificacionProactivaService.php`
-   Controller: `app/Http/Controllers/BotManController.php`
-   Modelos: `InfPago`, `NotasFinalesPeriodo`, `Matricula`
-   Documentación relacionada: `CHATBOT_QUICK_REPLIES.md`, `CHATBOT_GESTION_CONTEXTO.md`

---

## ✨ Ejemplo Completo de Uso

```
Usuario: menu
Chatbot: 👋 Hola de nuevo, Carlos!
         🔔 Tienes 2 notificación(es) importante(s).

         📚 ¿Qué deseas consultar?
         [📊 Ver Calificaciones] [💰 Consultar Pagos]
         [📅 Ver Horarios] [🔔 Ver Notificaciones] [❓ Ayuda]

Usuario: [Presiona: 🔔 Ver Notificaciones]

Chatbot: 🔔 NOTIFICACIONES Y ALERTAS
         ━━━━━━━━━━━━━━━━━━━━

         📊 Resumen de notificaciones:
            🔴 Alta prioridad: 2
            🟡 Media prioridad: 1
            ⚪ Total: 3

         🔴 Notificación 1:
         🔴 URGENTE: Pago vencido hace 5 días - Monto: $150.00
            👤 Estudiante: Juan Pérez

         🔴 Notificación 2:
         🟡 ATENCIÓN: Pago de $100.00 vence en 3 días (18/01/2025)
            👤 Estudiante: María García

         🟡 Notificación 3:
         📊 Nueva calificación en Matemáticas: 9.0/10 (Primer Quimestre)
            👤 Estudiante: Juan Pérez

         ━━━━━━━━━━━━━━━━━━━━
         💡 Consejo: Revisa las notificaciones de alta prioridad primero.

         ¿Qué deseas hacer ahora?
         [🏠 Menú Principal] [💰 Ver Pagos] [📊 Ver Calificaciones]

Usuario: [Presiona: 💰 Ver Pagos]

Chatbot: [Muestra información detallada de pagos...]
```

---

**Fecha de creación:** Enero 2025  
**Versión:** 1.0  
**Autor:** Sistema Eduka
