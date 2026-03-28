# 📊 Analytics y Dashboard - Chatbot Eduka

## 📋 Descripción General

El sistema de **Analytics y Dashboard** proporciona métricas detalladas sobre el uso del chatbot, permitiendo a docentes y administradores monitorear la actividad, identificar patrones de uso y optimizar la experiencia del usuario. El sistema recopila y analiza datos en tiempo real sobre interacciones, comandos utilizados, usuarios activos y tendencias de uso.

---

## 🎯 Características Principales

### 1. **Recopilación Automática de Datos**

El sistema registra automáticamente cada interacción:

| Dato Recopilado | Descripción                             | Uso                     |
| --------------- | --------------------------------------- | ----------------------- |
| **User ID**     | Identificador único del usuario         | Tracking individual     |
| **Comando**     | Comando ejecutado                       | Análisis de popularidad |
| **Rol**         | Rol del usuario (representante/docente) | Segmentación            |
| **Timestamp**   | Fecha y hora exacta                     | Análisis temporal       |
| **Fecha**       | Día de la interacción                   | Tendencias diarias      |
| **Hora**        | Hora de la interacción                  | Horarios pico           |

### 2. **Tipos de Estadísticas**

#### 📊 Estadísticas Generales

-   Total de interacciones en el periodo
-   Usuarios únicos activos
-   Promedio diario de interacciones
-   Tendencia de crecimiento/decrecimiento
-   Comandos más utilizados
-   Horarios pico de actividad
-   Uso por rol (representantes vs docentes)
-   Día más activo del periodo

#### 👤 Estadísticas Personales

-   Total de interacciones del usuario
-   Días activos en el periodo
-   Último y primer acceso
-   Comando favorito
-   Distribución de comandos usados
-   Porcentaje de uso por comando

---

## 🚀 Cómo Usar el Sistema

### Para Docentes y Administradores

#### 1. **Acceder a Estadísticas**

```
Usuario: estadisticas
o
Usuario: analytics
o
Usuario: dashboard
o
Usuario: metricas
```

#### 2. **Desde el Menú de Docentes**

1. Escribir `menu`
2. Seleccionar: **📊 Ver Estadísticas**
3. Elegir el periodo a consultar:
    - 📅 Últimos 7 días
    - 📅 Últimos 30 días
    - 👤 Mis estadísticas
    - 🏠 Volver al menú

#### 3. **Visualizar Reportes**

El sistema muestra reportes completos con:

-   Resumen general
-   Tendencias de uso
-   Top 5 comandos más usados
-   Horarios con más actividad
-   Distribución por roles
-   Día más activo

---

## 💻 Implementación Técnica

### Arquitectura del Sistema

```
BotManController
    ↓
[Interceptor de Comandos]
    ↓
registrarInteraccion()
    ↓
ChatbotAnalyticsService
    ↓
┌──────────────────────────────┐
│    Almacenamiento Cache      │
├──────────────────────────────┤
│ • chatbot_analytics_daily_*  │
│ • Retención: 7 días          │
│ • Formato: JSON arrays       │
└──────────────────────────────┘
    ↓
┌──────────────────────────────┐
│   Procesamiento Analytics    │
├──────────────────────────────┤
│ • obtenerEstadisticasGenerales() │
│ • obtenerEstadisticasUsuario()   │
│ • obtenerComandosPopulares()     │
│ • obtenerHorarioPico()           │
│ • obtenerTendencias()            │
│ • generarReporte()               │
└──────────────────────────────┘
```

### Servicio: `ChatbotAnalyticsService`

Ubicación: `app/Services/ChatbotAnalyticsService.php`

#### Métodos Principales

##### 1. `registrarInteraccion($userId, $comando, $rol = null)`

Registra cada interacción del usuario con el chatbot.

**Parámetros:**

-   `$userId`: ID único del usuario (de BotMan)
-   `$comando`: Comando ejecutado
-   `$rol`: Rol del usuario (opcional)

**Almacenamiento:**

```php
// Cache individual
"chatbot_analytics_interaccion_{userId}_{timestamp}"

// Cache diario (agregado)
"chatbot_analytics_daily_{YYYYMMDD}"
```

**Estructura de datos:**

```php
[
    'user_id' => '123456',
    'comando' => 'calificaciones',
    'rol' => 'representante',
    'timestamp' => '2026-01-10 14:30:45',
    'fecha' => '2026-01-10',
    'hora' => '14:30:45',
]
```

**Retención:** 7 días en cache

##### 2. `obtenerEstadisticasGenerales($dias = 7)`

Genera estadísticas agregadas del chatbot.

**Parámetros:**

-   `$dias`: Número de días hacia atrás (default: 7)

**Retorna:**

```php
[
    'periodo' => '7 días',
    'total_interacciones' => 150,
    'total_usuarios_unicos' => 25,
    'comandos_mas_usados' => [
        'calificaciones' => 45,
        'pagos' => 38,
        'menu' => 30,
        // ...
    ],
    'interacciones_por_dia' => [
        '2026-01-10' => 25,
        '2026-01-09' => 22,
        // ...
    ],
    'interacciones_por_rol' => [
        'representante' => 100,
        'docente' => 50,
    ],
    'horario_pico' => [
        '14' => 30,  // 14:00 - 14:59
        '15' => 25,  // 15:00 - 15:59
        // ...
    ]
]
```

##### 3. `obtenerEstadisticasUsuario($userId, $dias = 30)`

Obtiene estadísticas de un usuario específico.

**Parámetros:**

-   `$userId`: ID del usuario
-   `$dias`: Días hacia atrás (default: 30)

**Retorna:**

```php
[
    'user_id' => '123456',
    'periodo' => '30 días',
    'total_interacciones' => 45,
    'comandos_usados' => [
        'calificaciones' => 15,
        'pagos' => 12,
        'menu' => 10,
        // ...
    ],
    'ultimo_acceso' => Carbon('2026-01-10 14:30:45'),
    'primer_acceso' => Carbon('2025-12-15 10:20:30'),
    'total_dias_activos' => 12,
    'comando_favorito' => 'calificaciones',
    'ultimo_acceso_formatted' => '10/01/2026 14:30',
    'primer_acceso_formatted' => '15/12/2025 10:20',
]
```

##### 4. `obtenerComandosPopulares($limite = 5, $dias = 7)`

Top N comandos más utilizados.

**Retorna:**

```php
[
    [
        'comando' => 'calificaciones',
        'usos' => 45,
        'porcentaje' => 30.0
    ],
    [
        'comando' => 'pagos',
        'usos' => 38,
        'porcentaje' => 25.3
    ],
    // ...
]
```

##### 5. `obtenerHorarioPico($dias = 7)`

Top 3 horarios con más actividad.

**Retorna:**

```php
[
    [
        'hora' => '14:00 - 14:59',
        'interacciones' => 30
    ],
    [
        'hora' => '15:00 - 15:59',
        'interacciones' => 25
    ],
    [
        'hora' => '10:00 - 10:59',
        'interacciones' => 22
    ]
]
```

##### 6. `obtenerTendencias($dias = 7)`

Análisis de tendencias de uso.

**Retorna:**

```php
[
    'crecimiento' => [
        'porcentaje' => 15.5,
        'direccion' => 'aumento'  // o 'disminución', 'estable'
    ],
    'promedio_diario' => 21.4,
    'dia_mas_activo' => [
        'fecha' => '10/01/2026',
        'interacciones' => 35
    ],
    'uso_por_rol' => [
        [
            'rol' => 'Representante',
            'interacciones' => 100,
            'porcentaje' => 66.7
        ],
        [
            'rol' => 'Docente',
            'interacciones' => 50,
            'porcentaje' => 33.3
        ]
    ]
]
```

**Cálculo de crecimiento:**

```php
// Compara primera mitad vs segunda mitad del periodo
$mitad = floor($dias / 2);
$cambio = (($segundaMitad - $primeraMitad) / $primeraMitad) * 100;

// Ejemplo con 7 días:
// Días 4-7 (primeros): 50 interacciones
// Días 1-3 (últimos): 65 interacciones
// Crecimiento: ((65-50)/50) * 100 = +30%
```

##### 7. `generarReporte($dias = 7)`

Genera un reporte completo combinando todas las métricas.

**Retorna:**

```php
[
    'periodo' => 7,
    'fecha_generacion' => '10/01/2026 14:30',
    'estadisticas_generales' => [...],
    'comandos_populares' => [...],
    'horario_pico' => [...],
    'tendencias' => [...],
]
```

##### 8. `limpiarDatosAntiguos($diasMantener = 30)`

Limpia datos de cache antiguos para optimizar rendimiento.

**Uso:**

```php
// Ejecutar periódicamente (ej: via cron job)
$analytics = new ChatbotAnalyticsService();
$eliminados = $analytics->limpiarDatosAntiguos(30);
// Retorna: número de claves eliminadas
```

---

## 🎨 Interfaz de Usuario

### Ejemplo: Estadísticas Generales (7 días)

```
📊 REPORTE DE 7 DÍAS
━━━━━━━━━━━━━━━━━━━━

📈 Resumen General:
   • Total de interacciones: 150
   • Usuarios únicos: 25
   • Promedio diario: 21.4 interacciones

📊 Tendencia:
   📈 aumento del 15.5% respecto al periodo anterior

🏆 Comandos Más Usados:
   1. calificaciones: 45 usos (30.0%)
   2. pagos: 38 usos (25.3%)
   3. menu: 30 usos (20.0%)
   4. horarios: 20 usos (13.3%)
   5. notificaciones: 17 usos (11.3%)

⏰ Horarios con Más Actividad:
   1. 14:00 - 14:59: 30 interacciones
   2. 15:00 - 15:59: 25 interacciones
   3. 10:00 - 10:59: 22 interacciones

👥 Uso por Rol:
   • Representante: 100 (66.7%)
   • Docente: 50 (33.3%)

🌟 Día Más Activo:
   10/01/2026: 35 interacciones

━━━━━━━━━━━━━━━━━━━━
📅 Reporte generado: 10/01/2026 14:30

¿Qué deseas hacer ahora?
[🏠 Menú Principal] [👤 Ver Mis Estadísticas] [📊 Otro Periodo]
```

### Ejemplo: Estadísticas Personales

```
👤 TUS ESTADÍSTICAS
━━━━━━━━━━━━━━━━━━━━

📊 Resumen de Actividad:
   • Total de interacciones: 45
   • Días activos: 12 de 30
   • Último acceso: 10/01/2026 14:30
   • Primer acceso: 15/12/2025 10:20

⭐ Tu Comando Favorito:
   calificaciones (15 veces)

📋 Tus Comandos Usados:
   • calificaciones: 15 veces (33.3%)
   • pagos: 12 veces (26.7%)
   • menu: 10 veces (22.2%)
   • horarios: 5 veces (11.1%)
   • notificaciones: 3 veces (6.7%)

━━━━━━━━━━━━━━━━━━━━
💡 ¡Sigue usando el chatbot para mejorar tus estadísticas!

¿Qué deseas hacer ahora?
[🏠 Menú Principal] [📊 Ver Estadísticas Generales]
```

---

## 🔧 Configuración

### Retención de Datos

En `ChatbotAnalyticsService.php`:

```php
// Tiempo de retención en cache
Cache::put($cacheKey, $data, now()->addDays(7)); // 7 días

// Limpieza automática
$analytics->limpiarDatosAntiguos(30); // Mantener 30 días
```

### Límites de Visualización

```php
// Top comandos a mostrar
$limite = 5;  // Top 5 comandos

// Estadísticas personales - máximo comandos
if ($count >= 5) break;  // Mostrar máximo 5 comandos

// Horarios pico
array_slice($stats['horario_pico'], 0, 3, true);  // Top 3 horas
```

### Periodos Disponibles

```php
// En handleEstadisticas()
Button::create('📅 Últimos 7 días')->value('7'),
Button::create('📅 Últimos 30 días')->value('30'),

// Personalizable:
$this->mostrarEstadisticasGenerales($bot, 14);  // 14 días
```

---

## 📊 Integración con BotManController

### 1. Registro Automático de Interacciones

```php
// Método privado para tracking
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
```

### 2. Interceptación en Comandos

```php
// Cada comando registra su ejecución
$botman->hears('calificaciones|notas|ver notas', function (BotMan $bot) {
    $this->registrarInteraccion($bot, 'calificaciones');
    $this->handleCalificaciones($bot);
});

$botman->hears('pagos|ver pagos|pagos pendientes', function (BotMan $bot) {
    $this->registrarInteraccion($bot, 'pagos');
    $this->handlePagos($bot);
});

// ... todos los comandos tienen tracking
```

### 3. Método Principal: `handleEstadisticas()`

```php
public function handleEstadisticas(BotMan $bot)
{
    // 1. Verificar autenticación
    // 2. Validar rol (solo docentes/admin)
    // 3. Mostrar opciones de periodo
    // 4. Procesar selección del usuario
}
```

### 4. Métodos de Visualización

```php
// Estadísticas generales por periodo
public function mostrarEstadisticasGenerales(BotMan $bot, $dias = 7)

// Estadísticas del usuario actual
public function mostrarEstadisticasPersonales(BotMan $bot)
```

---

## 📈 Casos de Uso

### Caso 1: Docente Verifica Actividad Semanal

**Escenario:**

-   Docente quiere saber cómo se usa el chatbot

**Flujo:**

1. Usuario escribe `estadisticas`
2. Selecciona: **📅 Últimos 7 días**
3. Ve reporte completo con:
    - 150 interacciones totales
    - 25 usuarios únicos
    - Crecimiento del 15.5%
    - Comandos más populares
    - Horarios pico

**Insights:**

-   Identifica que el horario 14:00-14:59 es el más activo
-   Ve que "calificaciones" es el comando más usado
-   Detecta tendencia positiva de crecimiento

### Caso 2: Administrador Monitorea Uso Mensual

**Escenario:**

-   Admin necesita métricas para informe mensual

**Flujo:**

1. Usuario escribe `dashboard`
2. Selecciona: **📅 Últimos 30 días**
3. Obtiene reporte detallado con:
    - Distribución por roles
    - Día más activo del mes
    - Tendencias de crecimiento
    - Top 5 comandos

**Uso del reporte:**

-   Planificar mejoras según comandos populares
-   Optimizar horarios de soporte técnico
-   Evaluar adopción del chatbot

### Caso 3: Usuario Consulta Sus Propias Estadísticas

**Escenario:**

-   Docente quiere ver su actividad personal

**Flujo:**

1. Usuario escribe `estadisticas`
2. Selecciona: **👤 Mis estadísticas**
3. Ve su resumen personal:
    - 45 interacciones en 30 días
    - 12 días activos
    - Comando favorito: "calificaciones"

**Beneficio:**

-   Auto-conocimiento del uso
-   Gamificación (aumentar días activos)
-   Descubrir funciones poco usadas

---

## 🛠️ Mantenimiento

### Limpieza de Datos Antiguos

```php
// Ejecutar periódicamente (ej: scheduler de Laravel)
// En app/Console/Kernel.php

protected function schedule(Schedule $schedule)
{
    $schedule->call(function () {
        $analytics = new ChatbotAnalyticsService();
        $eliminados = $analytics->limpiarDatosAntiguos(30);
        \Log::info("Analytics: {$eliminados} claves antiguas eliminadas");
    })->daily();
}
```

### Exportar Datos (Futuro)

```php
// Método sugerido para exportación
public function exportarDatos($desde, $hasta, $formato = 'csv')
{
    // Implementar exportación a CSV, Excel, etc.
}
```

---

## 🔍 Troubleshooting

### Problema: No Aparecen Estadísticas

**Causa:** No hay datos en cache

**Solución:**

```php
// Verificar que las interacciones se registran
\Log::info('Interacción registrada', ['userId' => $userId, 'comando' => $comando]);

// Revisar cache manualmente
Cache::get('chatbot_analytics_daily_' . now()->format('Ymd'));
```

### Problema: Datos Inconsistentes

**Causa:** Cache expiró o fue limpiado

**Solución:**

```php
// Aumentar tiempo de retención
Cache::put($dailyKey, $interactions, now()->addDays(30)); // En vez de 7
```

### Problema: Performance Lenta

**Causa:** Muchos días de datos acumulados

**Solución:**

```php
// Reducir periodo de consulta
$this->mostrarEstadisticasGenerales($bot, 7); // En vez de 30

// O ejecutar limpieza
$analytics->limpiarDatosAntiguos(7); // Solo 7 días
```

---

## 🎯 Mejoras Futuras

### Fase 2 (Próxima)

-   [ ] Dashboard visual con gráficos (Chart.js)
-   [ ] Exportación de reportes a PDF/Excel
-   [ ] Alertas automáticas de métricas anómalas
-   [ ] Comparación entre periodos
-   [ ] Métricas de satisfacción del usuario

### Fase 3 (Futuro)

-   [ ] Almacenamiento en base de datos (tabla `chatbot_analytics`)
-   [ ] Analytics en tiempo real con WebSockets
-   [ ] Segmentación avanzada por cursos/niveles
-   [ ] Heatmaps de uso horario
-   [ ] Predicción de carga con ML

---

## 📚 Referencias

-   Código: `app/Services/ChatbotAnalyticsService.php`
-   Controller: `app/Http/Controllers/BotManController.php`
-   Métodos:
    -   `registrarInteraccion()`
    -   `handleEstadisticas()`
    -   `mostrarEstadisticasGenerales()`
    -   `mostrarEstadisticasPersonales()`
-   Cache Keys:
    -   `chatbot_analytics_interaccion_{userId}_{timestamp}`
    -   `chatbot_analytics_daily_{YYYYMMDD}`

---

## 🔐 Seguridad y Privacidad

### Consideraciones

✅ **Anonimización:** User IDs son de BotMan, no emails directos  
✅ **Acceso Restringido:** Solo docentes y admins ven estadísticas generales  
✅ **Datos Personales:** Usuarios solo ven sus propias estadísticas  
✅ **Retención Limitada:** Datos se eliminan después de 7 días  
✅ **No Persistente:** Datos en cache, no en DB permanente

### Cumplimiento

-   Los datos son agregados y anónimos para reportes generales
-   Usuarios pueden solicitar eliminación de sus datos (implementar en futuro)
-   No se almacenan contenidos de mensajes, solo comandos

---

## ✨ Ejemplo Completo de Uso

```
Usuario: menu
Chatbot: 👋 Hola de nuevo, Profesor García!

         👨‍🏫 ¿Qué deseas consultar?
         [👥 Ver Estudiantes] [📚 Ver Cursos]
         [🔔 Ver Recordatorios] [📊 Ver Estadísticas] [❓ Ayuda]

Usuario: [Presiona: 📊 Ver Estadísticas]

Chatbot: 📊 ESTADÍSTICAS DEL CHATBOT
         ━━━━━━━━━━━━━━━━━━━━

         📅 Selecciona el periodo a consultar:
         [📅 Últimos 7 días] [📅 Últimos 30 días]
         [👤 Mis estadísticas] [🏠 Volver al menú]

Usuario: [Presiona: 📅 Últimos 7 días]

Chatbot: 📊 REPORTE DE 7 DÍAS
         ━━━━━━━━━━━━━━━━━━━━

         📈 Resumen General:
            • Total de interacciones: 150
            • Usuarios únicos: 25
            • Promedio diario: 21.4 interacciones

         📊 Tendencia:
            📈 aumento del 15.5% respecto al periodo anterior

         🏆 Comandos Más Usados:
            1. calificaciones: 45 usos (30.0%)
            2. pagos: 38 usos (25.3%)
            3. menu: 30 usos (20.0%)
            4. horarios: 20 usos (13.3%)
            5. notificaciones: 17 usos (11.3%)

         ⏰ Horarios con Más Actividad:
            1. 14:00 - 14:59: 30 interacciones
            2. 15:00 - 15:59: 25 interacciones
            3. 10:00 - 10:59: 22 interacciones

         👥 Uso por Rol:
            • Representante: 100 (66.7%)
            • Docente: 50 (33.3%)

         🌟 Día Más Activo:
            10/01/2026: 35 interacciones

         ━━━━━━━━━━━━━━━━━━━━
         📅 Reporte generado: 10/01/2026 14:30

         ¿Qué deseas hacer ahora?
         [🏠 Menú Principal] [👤 Ver Mis Estadísticas] [📊 Otro Periodo]

Usuario: [Presiona: 👤 Ver Mis Estadísticas]

Chatbot: 👤 TUS ESTADÍSTICAS
         ━━━━━━━━━━━━━━━━━━━━

         📊 Resumen de Actividad:
            • Total de interacciones: 45
            • Días activos: 12 de 30
            • Último acceso: 10/01/2026 14:30
            • Primer acceso: 15/12/2025 10:20

         ⭐ Tu Comando Favorito:
            estudiantes (18 veces)

         📋 Tus Comandos Usados:
            • estudiantes: 18 veces (40.0%)
            • cursos: 12 veces (26.7%)
            • menu: 10 veces (22.2%)
            • estadisticas: 3 veces (6.7%)
            • recordatorios: 2 veces (4.4%)

         ━━━━━━━━━━━━━━━━━━━━
         💡 ¡Sigue usando el chatbot para mejorar tus estadísticas!

         ¿Qué deseas hacer ahora?
         [🏠 Menú Principal] [📊 Ver Estadísticas Generales]
```

---

**Fecha de creación:** Enero 2026  
**Versión:** 1.0  
**Autor:** Sistema Eduka
