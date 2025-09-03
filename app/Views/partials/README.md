# Libro Card Partial - Documentación de Uso

Este partial reutilizable permite mostrar cards de libros de manera consistente en toda la aplicación.

## Archivos creados:
- `app/Views/partials/libro_card.php` - Partial de PHP
- `public/assets/js/libro-card-helper.js` - Helper de JavaScript

## Uso en PHP

### Uso básico:
```php
<?= view('partials/libro_card', ['libro' => $libro]) ?>
```

### Uso con opciones:
```php
<?= view('partials/libro_card', [
    'libro' => $libro,
    'colClasses' => 'col-lg-3 col-md-6',
    'mostrarDetalles' => ['isbn', 'edicion', 'estado', 'stock'],
    'imagenPrefix' => base_url('public/')
]) ?>
```

### Uso en bucles:
```php
<?php foreach ($libros as $libro): ?>
    <?= view('partials/libro_card', [
        'libro' => $libro,
        'mostrarDetalles' => ['isbn', 'stock']
    ]) ?>
<?php endforeach; ?>
```

## Uso en JavaScript

### Uso básico:
```javascript
const cardHtml = generarLibroCard(libro);
document.getElementById('contenedor').innerHTML = cardHtml;
```

### Uso con opciones:
```javascript
const cardHtml = generarLibroCard(libro, {
    colClasses: 'col-lg-3 col-md-6',
    mostrarDetalles: ['isbn', 'edicion', 'estado', 'stock'],
    imagenPrefix: '/public/'
});
```

### Uso en bucles:
```javascript
let html = '';
libros.forEach(libro => {
    html += generarLibroCard(libro, {
        mostrarDetalles: ['stock']
    });
});
document.getElementById('contenedor').innerHTML = html;
```

## Parámetros

### libro (requerido)
Objeto o array con la información del libro:
- `titulo`: Título del libro
- `rutaportada`: Ruta de la imagen de portada
- `anio`: Año de publicación
- `autores` o `nomautor`: Nombre del autor
- `isbn`: ISBN (opcional)
- `numedicion`: Número de edición (opcional)
- `estado`: Estado del libro (opcional)
- `stock`: Stock disponible (opcional)
- `detalle_url`: URL para ver detalles (opcional)

### colClasses (opcional)
Clases CSS para las columnas de Bootstrap.
- Default: `'col-lg-2 col-md-4 col-sm-6'`
- Ejemplos: `'col-lg-3 col-md-6'`, `'col-12'`

### mostrarDetalles (opcional)
Array con detalles adicionales a mostrar.
- Opciones: `['isbn', 'edicion', 'estado', 'stock']`
- Default: `[]` (no mostrar detalles adicionales)

### imagenPrefix (opcional)
Prefijo para las rutas de imagen.
- Default: `''`
- Ejemplo: `base_url('public/')`, `'/uploads/'`

## Ejemplos de implementación

### Página Principal (solo autor y año):
```php
<?= view('partials/libro_card', [
    'libro' => $libro,
    'imagenPrefix' => base_url('public/')
]) ?>
```

### Catálogo (con detalles completos):
```php
<?= view('partials/libro_card', [
    'libro' => $libro,
    'mostrarDetalles' => ['isbn', 'edicion', 'estado', 'stock']
]) ?>
```

### Búsqueda (columnas más anchas):
```php
<?= view('partials/libro_card', [
    'libro' => $libro,
    'colClasses' => 'col-lg-3 col-md-6',
    'mostrarDetalles' => ['isbn', 'stock']
]) ?>
```

### Lista de favoritos (una columna):
```php
<?= view('partials/libro_card', [
    'libro' => $libro,
    'colClasses' => 'col-12',
    'mostrarDetalles' => ['estado', 'stock']
]) ?>
```

## Beneficios

✅ **Código DRY**: No repetir código HTML
✅ **Mantenimiento fácil**: Cambios en un solo lugar
✅ **Consistencia visual**: Mismo diseño en toda la app
✅ **Flexibilidad**: Configurable según necesidades
✅ **Responsive**: Adaptable a diferentes dispositivos
✅ **Accesibilidad**: Alt tags y structure semántica
