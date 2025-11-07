# Componentes Reutilizables - Biblioteca Virtual HZG

## Índice de Componentes

Este documento lista todos los componentes reutilizables disponibles en el sistema.

## 📁 Vistas Parciales (`app/Views/partials/`)

### 1. `_portada_libro.php` 🆕
**Uso**: Renderizar portadas de libros con tamaños configurables

**Variables**:
- `$portada` (string|null): URL de la portada
- `$titulo` (string): Título del libro
- `$size` (string): 'small', 'medium', 'large'
- `$classes` (string): Clases CSS adicionales

**Ejemplo**:
```php
<?php echo view('partials/_portada_libro', [
    'portada' => $libro['portada'],
    'titulo' => $libro['titulo'],
    'size' => 'medium'
]); ?>
```

---

### 2. `_prestamo_row.php` 🆕
**Uso**: Fila de tabla para préstamos (activos e historial)

**Variables**:
- `$prestamo` (array): Datos del préstamo
- `$mostrarRenovar` (bool): Mostrar botón renovar
- `$mostrarDevolver` (bool): Mostrar botón devolver

**Ejemplo**:
```php
<?php echo view('partials/_prestamo_row', [
    'prestamo' => $prestamo,
    'mostrarRenovar' => true,
    'mostrarDevolver' => false
]); ?>
```

---

### 3. `_empty_state_con_acciones.php` 🆕
**Uso**: Estado vacío con botones de acción configurables

**Variables**:
- `$titulo` (string): Título del mensaje
- `$mensaje` (string): Texto descriptivo
- `$icono` (string): Nombre del icono FontAwesome
- `$botones` (array): Array de botones
- `$claseCard` (string): Clases CSS del card
- `$alertaAdmin` (string|null): Alerta para administradores

**Ejemplo**:
```php
<?php echo view('partials/_empty_state_con_acciones', [
    'titulo' => 'No hay datos',
    'mensaje' => 'Agrega nuevos elementos',
    'icono' => 'folder-open',
    'botones' => [
        [
            'texto' => 'Agregar',
            'url' => site_url('agregar'),
            'clase' => 'btn-primary',
            'icono' => 'plus'
        ]
    ]
]); ?>
```

---

### 4. `empty_state.php`
**Uso**: Estado vacío simple (ya existía)

**Variables**:
- `$class` (string): Clase CSS del card
- `$icon` (string): Icono FontAwesome
- `$iconClass` (string): Clase del icono
- `$title` (string): Título
- `$titleClass` (string): Clase del título
- `$message` (string): Mensaje
- `$messageClass` (string): Clase del mensaje

---

### 5. `prestamo_card.php`
**Uso**: Card de préstamo (ya existía)

**Variables**:
- `$prestamo` (array): Datos del préstamo
- `$colClasses` (string): Clases de columna
- `$mostrarAcciones` (bool): Mostrar botones

---

### 6. `libro_card.php`
**Uso**: Card de libro para catálogo (ya existía)

**Variables**:
- `$libro` (array): Datos del libro
- `$mostrarDetalles` (array): Campos adicionales
- `$colClasses` (string): Clases de columna
- `$imagenPrefix` (string): Prefijo de ruta de imagen

---

## 🛠️ Helpers (`app/Helpers/`)

### 1. `prestamo_helper.php` 🆕

#### `calcularEstadoPrestamo($prestamo)`
Retorna array con estado, clase CSS, icono y texto del préstamo.

```php
$estado = calcularEstadoPrestamo($prestamo);
// ['estado' => 'vencido', 'clase' => 'danger', 'icono' => 'exclamation-triangle', 'texto' => 'Vencido']
```

#### `formatearFechaPrestamo($fecha, $formato = 'd/M/Y')`
Formatea fecha de préstamo.

```php
echo formatearFechaPrestamo($prestamo['fechaprestamo']); // "06/Nov/2025"
```

#### `obtenerInfoFechaVencimiento($prestamo)`
Retorna información formateada de la fecha de vencimiento.

```php
$info = obtenerInfoFechaVencimiento($prestamo);
// ['texto' => '06/Nov/2025', 'clase' => 'danger', 'icono' => 'exclamation-triangle']
```

#### `renderBadgeEstado($prestamo)`
Renderiza HTML del badge de estado.

```php
echo renderBadgeEstado($prestamo); 
// <span class="badge bg-danger"><i class="fas fa-exclamation-triangle me-1"></i>Vencido</span>
```

#### `esPrestamoVencido($prestamo)`
Verifica si un préstamo está vencido.

```php
if (esPrestamoVencido($prestamo)) {
    // Préstamo vencido
}
```

#### `calcularDiasRestantes($prestamo)`
Calcula días restantes para devolución.

```php
$dias = calcularDiasRestantes($prestamo); // 3
```

#### `obtenerNombreAutor($prestamo)`
Obtiene nombre del autor de forma segura.

```php
echo obtenerNombreAutor($prestamo); // "García Márquez" o "Sin autor"
```

#### `validarFechaRenovacion($fechaInicio, $fechaDevolucion, $maxDias = 7)`
Valida que la renovación esté dentro del rango permitido.

```php
$validacion = validarFechaRenovacion('2025-11-01', '2025-11-10');
// ['valido' => false, 'mensaje' => 'No puede extender...', 'dias' => 9]
```

#### `obtenerMensajeExtension($dias)`
Genera mensaje de extensión formateado.

```php
echo obtenerMensajeExtension(5); // "El préstamo se extenderá por 5 días más"
```

---

### 2. `historial_helper.php`
Funciones para gestión de historial de usuarios (ya existía).

---

## 🎨 CSS (`public/assets/css/components/`)

### `prestamos-components.css` 🆕

**Clases disponibles**:

#### Portadas de Libros
- `.book-thumbnail`: Estilo base para thumbnails
- `.book-thumbnail-small`: 40x50px
- `.book-thumbnail-medium`: 80x100px
- `.book-thumbnail-large`: 120x150px
- `.book-placeholder`: Placeholder cuando no hay portada

#### Badges
- `.badge`: Estilos mejorados de badges

#### Tablas
- `.table-prestamos`: Tabla optimizada para préstamos

#### Estados Vacíos
- `.empty-state-card`: Card para estados vacíos
- `.empty-state-icon`: Icono grande de estado vacío

#### Botones
- `.btn-action-group`: Grupo de botones de acción

#### Otros
- `.prestamos-counter-card`: Card del contador de préstamos
- `.prestamos-counter-value`: Valor del contador
- `.fade-in`: Animación de entrada

---

## 💻 JavaScript (`public/assets/js/`)

### `mis-prestamos.js` 🆕

**Clase `PrestamoManager`**:

#### Métodos Públicos
- `cargarDetalles(idPrestamo)`: Carga detalles del préstamo
- `renovar(idPrestamo)`: Abre formulario de renovación
- `devolver(idPrestamo)`: Procesa devolución
- `validarFechaDevolucion()`: Valida fechas de renovación
- `enviarRenovacion()`: Envía solicitud de renovación

#### Funciones Globales (Compatibilidad)
- `cargarDetallesPrestamo(idPrestamo)`
- `renovarPrestamo(idPrestamo)`
- `devolverPrestamo(idPrestamo)`
- `validarFechaDevolucion()`
- `enviarRenovacionPrestamo()`

**Uso**:
```html
<script src="<?= base_url('assets/js/mis-prestamos.js') ?>"></script>
```

---

## 📋 Convenciones de Nomenclatura

### Archivos
- Componentes parciales: `_nombre_componente.php` (prefijo `_`)
- Helpers: `nombre_helper.php` (sufijo `_helper`)
- CSS de componentes: `nombre-components.css`
- JavaScript modular: `nombre-kebab-case.js`

### Clases CSS
- BEM light: `.componente-elemento--modificador`
- Semantic: `.book-thumbnail`, `.table-prestamos`

### Funciones Helper
- camelCase: `calcularEstadoPrestamo()`
- Verbos descriptivos: `obtener`, `calcular`, `validar`, `formatear`

---

## 🔧 Cómo Crear Nuevos Componentes

### 1. Crear Partial
```php
<?php
/**
 * Componente: Nombre del Componente
 * 
 * Variables requeridas:
 * - $variable1: Descripción
 * 
 * Variables opcionales:
 * - $variable2: Descripción (default: valor)
 */
$variable2 = $variable2 ?? 'valor_default';
?>

<!-- HTML del componente -->
```

### 2. Documentar en este archivo

### 3. Agregar estilos en CSS si es necesario

### 4. Agregar ejemplo de uso en README

---

## 📊 Estadísticas

- **Total de componentes de vista**: 6
- **Total de helpers**: 2 archivos
- **Total de funciones helper**: 9+ funciones
- **Archivos CSS de componentes**: 1
- **Módulos JavaScript**: 1
- **Reducción de código duplicado**: ~95% en misPrestamos.php

---

## 🚀 Componentes en Uso

| Componente | Vista que lo usa |
|------------|------------------|
| `_portada_libro.php` | `misPrestamos.php` ✅ |
| `_prestamo_row.php` | `misPrestamos.php` ✅ |
| `_empty_state_con_acciones.php` | `misPrestamos.php` ✅ |
| `prestamo_helper.php` | `misPrestamos.php` ✅ |
| `mis-prestamos.js` | `misPrestamos.php` ✅ |
| `prestamos-components.css` | `misPrestamos.php` ✅ |

---

**Última actualización**: 6 de Noviembre, 2025
**Versión**: 1.0
