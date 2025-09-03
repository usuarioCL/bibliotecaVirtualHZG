<?php
/**
 * Partial: Card de Libro Reutilizable
 * 
 * Variables requeridas:
 * - $libro: Array con información del libro
 * 
 * Variables opcionales:
 * - $mostrarDetalles: Array con campos adicionales a mostrar ['isbn', 'edicion', 'estado', 'stock']
 * - $colClasses: Clases CSS para las columnas (default: 'col-lg-2 col-md-4 col-sm-6')
 * - $imagenPrefix: Prefijo para la ruta de imagen (default: '')
 */

$colClasses = $colClasses ?? 'col-lg-2 col-md-4 col-sm-6';
$mostrarDetalles = $mostrarDetalles ?? [];
$imagenPrefix = $imagenPrefix ?? '';
?>

<div class="<?= $colClasses ?> mb-4">
    <div class="card h-100 border-0 shadow-sm">
        <!-- Imagen del libro -->
        <div class="card-img-top-container" style="height: 250px; overflow: hidden;">
            <?php if (!empty($libro['rutaportada'])): ?>
                <img src="<?= $imagenPrefix . $libro['rutaportada'] ?>" 
                     class="card-img-top h-100 w-100" 
                     style="object-fit: cover;" 
                     alt="<?= esc($libro['titulo']) ?>">
            <?php else: ?>
                <div class="bg-light h-100 d-flex align-items-center justify-content-center">
                    <div class="text-center text-muted">
                        <i class="fas fa-book fa-2x mb-2"></i>
                        <small>Sin portada</small>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Contenido de la card -->
        <div class="card-body p-3">
            <!-- Título -->
            <h6 class="card-title fw-bold mb-2" style="font-size: 0.9rem; line-height: 1.2;">
                <?= esc(strlen($libro['titulo']) > 40 ? substr($libro['titulo'], 0, 40) . '...' : $libro['titulo']) ?>
            </h6>
            
            <!-- Autor -->
            <p class="card-text text-muted small mb-2">
                <strong>Autor:</strong> 
                <?php 
                $autorTexto = $libro['autores'] ?? $libro['nomautor'] ?? 'Sin autor';
                echo esc($autorTexto);
                ?>
            </p>
            
            <!-- Año -->
            <p class="card-text text-muted small <?= !empty($mostrarDetalles) ? 'mb-1' : '' ?>">
                <strong>Año:</strong> <?= esc($libro['anio']) ?>
            </p>
            
            <!-- Detalles adicionales opcionales -->
            <?php if (in_array('isbn', $mostrarDetalles) && !empty($libro['isbn'])): ?>
                <p class="card-text text-muted small mb-1">
                    <strong>ISBN:</strong> <?= esc($libro['isbn']) ?>
                </p>
            <?php endif; ?>
            
            <?php if (in_array('edicion', $mostrarDetalles) && !empty($libro['numedicion'])): ?>
                <p class="card-text text-muted small mb-1">
                    <strong>Edición:</strong> <?= esc($libro['numedicion']) ?>
                </p>
            <?php endif; ?>
            
            <?php if (in_array('estado', $mostrarDetalles) && !empty($libro['estado'])): ?>
                <p class="card-text text-muted small mb-1">
                    <strong>Estado:</strong> <?= esc($libro['estado']) ?>
                </p>
            <?php endif; ?>
            
            <?php if (in_array('stock', $mostrarDetalles) && isset($libro['stock'])): ?>
                <p class="card-text text-muted small">
                    <strong>Stock:</strong> <?= esc($libro['stock']) ?>
                </p>
            <?php endif; ?>
        </div>
        
        <!-- Footer de la card -->
        <div class="card-footer bg-transparent border-top-0">
            <a href="<?= isset($libro['detalle_url']) ? $libro['detalle_url'] : '#' ?>" 
               class="btn btn-sm btn-outline-primary">
                Ver detalles
            </a>
        </div>
    </div>
</div>
