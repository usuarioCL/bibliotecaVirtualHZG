# Refactorización de paginaPrincipal.php

## 📋 Resumen

Este documento describe la refactorización completa del archivo `paginaPrincipal.php` para mejorar la mantenibilidad, escalabilidad y organización del código.

## 🎯 Objetivos Cumplidos

✅ **Separación de responsabilidades** - HTML, CSS, JavaScript y lógica PHP ahora están separados  
✅ **Código modular** - Funcionalidades organizadas en módulos independientes  
✅ **Reutilización** - Componentes compartidos entre diferentes partes de la aplicación  
✅ **Mantenibilidad** - Código más fácil de entender, modificar y extender  
✅ **Escalabilidad** - Estructura que facilita agregar nuevas funcionalidades  

## 📁 Nueva Estructura de Archivos

```
app/Views/
├── paginaPrincipal.php (REFACTORIZADO - solo 56 líneas)
├── paginaPrincipal.php.backup (archivo original - 1690 líneas)
└── partials/
    ├── hero_section.php
    ├── niveles_categorias_tabs.php
    ├── recursos_grid.php
    └── modals/
        ├── libro_modal.php
        └── pdf_viewer_modal.php

public/assets/
├── css/components/
│   ├── pdf-viewer-modal.css
│   └── voice-controls.css
└── js/
    ├── paginaPrincipal.js (orquestador principal)
    ├── modules/
    │   ├── pdfViewer.js (clase PDFViewer)
    │   ├── voiceReader.js (clase VoiceReader)
    │   ├── prestamoForm.js (clase PrestamoForm)
    │   └── favoritosHandler.js (clase FavoritosHandler)
    └── shared/
        ├── pdfjs-loader.js (carga de PDF.js)
        └── voice-utils.js (utilidades de voz)
```

## 🔧 Módulos Creados

### 1. **PDFViewer** (`modules/pdfViewer.js`)
- Gestiona la visualización de PDFs en modal personalizado
- Carga PDFs en iframe con manejo de errores
- Extrae texto del PDF usando PDF.js
- Proporciona texto para lectura de voz

**Métodos principales:**
- `open(url, title)` - Abre el visor con un PDF
- `close()` - Cierra el visor
- `getText()` - Obtiene el texto extraído del PDF
- `extractText()` - Extrae texto usando PDF.js

### 2. **VoiceReader** (`modules/voiceReader.js`)
- Lectura de voz con síntesis de texto a voz
- Configuración amigable para niños (pitch alto, velocidad ajustada)
- Controles de reproducción, pausa y detención
- Control de velocidad de lectura

**Métodos principales:**
- `start()` - Inicia la lectura de voz
- `pause()` - Pausa/reanuda la lectura
- `stop()` - Detiene la lectura
- `changeSpeed(speed)` - Cambia la velocidad

### 3. **PrestamoForm** (`modules/prestamoForm.js`)
- Gestión del formulario de solicitud de préstamos
- Verificación de sanciones del usuario
- Validación y envío del formulario
- Mensajes de éxito/error

**Métodos principales:**
- `open(recursoId)` - Abre el formulario de préstamo
- `verificarSanciones()` - Verifica sanciones activas
- `submitForm()` - Envía la solicitud de préstamo

### 4. **FavoritosHandler** (`modules/favoritosHandler.js`)
- Gestión de favoritos del usuario
- Toggle de favoritos con feedback visual
- Sincronización con el servidor
- Notificaciones toast

**Métodos principales:**
- `toggle(recursoId)` - Agrega/remueve de favoritos
- `isFavorite(recursoId)` - Verifica si es favorito
- `showToast(message, type)` - Muestra notificaciones

### 5. **PaginaPrincipalController** (`paginaPrincipal.js`)
- Orquestador principal que coordina todos los módulos
- Inicializa componentes al cargar la página
- Expone funciones globales para las vistas
- Gestiona eventos globales

**Funciones expuestas globalmente:**
- `cargarDetallesLibro(id)`
- `leerPDFDirecto(url, title)`
- `cerrarModalPDF()`
- `toggleVoiceReading()`
- `pauseVoiceReading()`
- `stopVoiceReading()`
- `changeVoiceSpeed(speed)`
- `toggleFavorito(id)`
- `solicitarPrestamo(id)`

## 🎨 Archivos CSS Extraídos

### **pdf-viewer-modal.css**
Estilos del modal personalizado para visualización de PDFs:
- Layout del modal responsivo
- Controles del visor
- Estados de carga y error

### **voice-controls.css**
Estilos para los controles de lectura de voz:
- Botones de control (play, pause, stop)
- Slider de velocidad
- Diseño amigable para niños
- Responsive design

## 📄 Partials de Vistas

### **hero_section.php**
- Carrusel de imágenes de fondo
- Título y subtítulo de la biblioteca
- Buscador de recursos

### **niveles_categorias_tabs.php**
- Pestañas de navegación (Niveles/Categorías)
- Grid de niveles educativos con iconos
- Grid de categorías
- Enlaces funcionales con filtros

### **recursos_grid.php**
- Grid responsivo de recursos
- Contador de recursos
- Mensaje cuando no hay recursos
- Botón para explorar catálogo completo

### **modals/libro_modal.php**
- Modal de detalles del libro
- Loading state
- Estructura Bootstrap

### **modals/pdf_viewer_modal.php**
- Modal personalizado del visor de PDF
- Controles de voz integrados
- Botones de descarga y cierre
- Estados de carga y error

## 🔄 Comparación Antes/Después

### Antes:
```
paginaPrincipal.php: 1,690 líneas
- HTML mezclado con PHP
- ~800 líneas de JavaScript inline
- ~200 líneas de CSS inline
- Lógica duplicada
- Difícil de mantener
```

### Después:
```
paginaPrincipal.php: 56 líneas
- Solo estructura y carga de módulos
- JavaScript modular en archivos separados
- CSS en archivos de componentes
- Código reutilizable
- Fácil de mantener y extender
```

## 📊 Reducción de Código

- **Archivo principal:** 1,690 → 56 líneas (97% reducción)
- **Líneas de código inline:** 0 (todo externalizado)
- **Módulos creados:** 9 archivos organizados
- **Responsabilidades separadas:** 5 módulos JavaScript

## 🚀 Beneficios de la Refactorización

### 1. **Mantenibilidad**
- Código organizado y fácil de encontrar
- Cada módulo tiene una responsabilidad clara
- Cambios aislados no afectan otros componentes

### 2. **Reutilización**
- Módulos compartidos (shared/) usables en toda la app
- Componentes de vistas (partials/) reutilizables
- Estilos de componentes aplicables en múltiples páginas

### 3. **Testeable**
- Clases independientes fáciles de probar
- Inyección de dependencias (ej: VoiceReader recibe PDFViewer)
- Lógica aislada facilita unit testing

### 4. **Escalabilidad**
- Fácil agregar nuevos módulos
- Estructura clara para nuevas funcionalidades
- Configuración centralizada (APP_CONFIG)

### 5. **Performance**
- Posibilidad de lazy loading
- Carga modular permite optimizaciones
- CSS y JS en archivos separados (cacheables)

### 6. **Desarrollo en Equipo**
- Módulos independientes = menos conflictos
- Estructura clara = onboarding más rápido
- Código autodocumentado con clases y métodos

## 🔗 Integración con Vistas Existentes

El código es compatible con las vistas existentes como `libro_card.php` que utilizan funciones como:
- `onclick="cargarDetallesLibro(<?= $libro['id_recurso'] ?>)"`
- `onclick="toggleFavorito(<?= $libro['id_recurso'] ?>)"`
- `onclick="solicitarPrestamo(<?= $libro['id_recurso'] ?>)"`

Todas estas funciones ahora están gestionadas por los módulos correspondientes.

## 📝 Notas de Migración

### Archivo de Respaldo
El archivo original se conserva como `paginaPrincipal.php.backup` por seguridad.

### Configuración Global
Se agregó `window.APP_CONFIG` con rutas centralizadas para facilitar cambios en el enrutamiento.

### Dependencias
Los módulos mantienen las mismas dependencias externas:
- Bootstrap 5
- Font Awesome
- PDF.js (cargado dinámicamente)

## 🛠️ Próximos Pasos Sugeridos

1. **Testing**
   - Crear tests unitarios para cada módulo
   - Testing de integración entre módulos
   - Tests E2E de flujos completos

2. **Optimización**
   - Implementar lazy loading de módulos
   - Minificación de JS y CSS
   - Bundle de archivos para producción

3. **Documentación**
   - JSDoc para todos los métodos
   - Ejemplos de uso de cada módulo
   - Guía de desarrollo

4. **Mejoras Adicionales**
   - Implementar sistema de eventos entre módulos
   - Cache de favoritos y configuraciones
   - Offline support para PWA

## 📚 Referencias

- **Patrón MVC**: Separación de vistas, lógica y presentación
- **Módulos ES6**: Clases y encapsulación
- **Component-Based Architecture**: Componentes reutilizables
- **Separation of Concerns**: Cada archivo tiene una responsabilidad

---

**Fecha de Refactorización:** 6 de Noviembre, 2025  
**Versión:** 2.0  
**Estado:** ✅ Completado
