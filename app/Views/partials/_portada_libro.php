<?php
/**
 * Componente: Portada de Libro
 * 
 * Variables requeridas:
 * - $portada: URL de la portada (puede ser null/vacía)
 * 
 * Variables opcionales:
 * - $titulo: Título del libro (default: '')
 * - $size: Tamaño ('small', 'medium', 'large') (default: 'small')
 * - $classes: Clases CSS adicionales (default: '')
 */

$titulo = $titulo ?? '';
$size = $size ?? 'small';
$classes = $classes ?? '';

$dimensiones = [
    'small' => ['width' => '40px', 'height' => '50px'],
    'medium' => ['width' => '80px', 'height' => '100px'],
    'large' => ['width' => '120px', 'height' => '150px']
];

$dim = $dimensiones[$size] ?? $dimensiones['small'];
?>

<?php if (!empty($portada)): ?>
    <img src="<?= base_url($portada) ?>" 
         class="book-thumbnail book-thumbnail-<?= esc($size) ?> rounded <?= esc($classes) ?>" 
         alt="Portada de <?= esc($titulo) ?>"
         title="<?= esc($titulo) ?>">
<?php else: ?>
    <div class="book-thumbnail book-thumbnail-<?= esc($size) ?> book-placeholder bg-light rounded d-flex align-items-center justify-content-center <?= esc($classes) ?>">
        <i class="fas fa-book text-muted"></i>
    </div>
<?php endif; ?>
