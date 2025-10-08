<?php
/**
 * Partial: Card de Libro Reutilizable
 * 
 * Variables requeridas:
 * - $libro: Array con información del libro
 * 
 * Variables opcionales:
 * - $mostrarDetalles: Array con campos adicionales a mostrar ['isbn', 'edicion', 'estado', 'stock', 'tipo']
 * - $colClasses: Clases CSS para las columnas (default: 'col-lg-2 col-md-4 col-sm-6')
 * - $imagenPrefix: Prefijo para la ruta de imagen (default: '')
 */

$colClasses = $colClasses ?? 'col-xl-2 col-lg-3 col-md-4 col-sm-6';
$mostrarDetalles = $mostrarDetalles ?? [];
$imagenPrefix = $imagenPrefix ?? '';
?>

<div class="<?= $colClasses ?>">
    <div class="card h-100 shadow-sm rounded" 
         style="cursor: pointer;" 
         data-bs-toggle="modal" 
         data-bs-target="#libroModal"
         data-libro-id="<?= $libro['idrecurso'] ?>"
         onclick="cargarDetallesLibro(<?= $libro['idrecurso'] ?>)">
        <!-- Icono de tipo de recurso -->
        <?php 
        $esDigital = false;
        $debugInfo = [];
        
        // Verificar por el tipo de recurso (más confiable)
        if (isset($libro['tiporecurso'])) {
            $esDigital = (stripos($libro['tiporecurso'], 'digital') !== false);
            $debugInfo[] = "Tipo: " . $libro['tiporecurso'];
        }
        
        // Verificar por el ID del tipo de recurso (ID 2 = Libro Digital)
        if (!$esDigital && isset($libro['idtiporecurso'])) {
            $esDigital = ($libro['idtiporecurso'] == 2);
            $debugInfo[] = "ID Tipo: " . $libro['idtiporecurso'];
        }
        
        // Verificar por la existencia de archivo digital
        if (!$esDigital && isset($libro['archivo']) && !empty($libro['archivo'])) {
            $esDigital = true;
            $debugInfo[] = "Archivo: " . $libro['archivo'];
        }
        
        $debugText = implode(', ', $debugInfo);
        ?>
        <div class="position-absolute top-0 start-0 m-2" style="z-index: 10;">
            <?php if ($esDigital): ?>
                <span class="badge bg-info text-white" title="Recurso Digital - <?= esc($debugText) ?>">
                    <i class="fas fa-file-pdf me-1"></i>
                    Digital
                </span>
            <?php else: ?>
                <span class="badge bg-primary text-white" title="Recurso Físico - <?= esc($debugText) ?>">
                    <i class="fas fa-book me-1"></i>
                    Físico
                </span>
            <?php endif; ?>
        </div>
        
        <!-- Imagen del libro con texto overlay -->
        <div class="position-relative card" style="height: 300px; overflow: hidden;">
            <?php if (!empty($libro['portada'])): ?>
                <?php 
                $rutaCompleta = $imagenPrefix . $libro['portada'];
                ?>
                <img src="<?= $rutaCompleta ?>" 
                     class="card-img-top h-100 w-100" 
                     style="object-fit: cover; border-top-left-radius: 0.375rem; border-top-right-radius: 0.375rem;" 
                     alt="<?= esc($libro['titulo']) ?>"
                     data-recurso-id="<?= $libro['idrecurso'] ?>">
            <?php else: ?>
                <div class="bg-light h-100 d-flex align-items-center justify-content-center" style="border-top-left-radius: 0.375rem; border-top-right-radius: 0.375rem;">
                    <div class="text-center text-muted">
                        <?php if ($esDigital): ?>
                            <i class="fas fa-file-pdf fa-2x mb-2 text-info"></i>
                        <?php else: ?>
                            <i class="fas fa-book fa-2x mb-2"></i>
                        <?php endif; ?>
                        <small>Sin portada</small>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Overlay con información del libro -->
            <div class="position-absolute bottom-0 start-0 end-0 p-3" style="background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.7) 80%, transparent 100%); text-shadow: 1px 1px 2px rgba(0,0,0,0.8);">
                <!-- Título -->
                <h6 class="text-white fw-bold mb-1 text-truncate" style="font-size: 0.95rem; line-height: 1.3; text-shadow: 2px 2px 4px rgba(0,0,0,0.9);" title="<?= esc($libro['titulo']) ?>">
                    <?= esc($libro['titulo']) ?>
                </h6>
                
                <!-- Autores -->
                <p class="text-white small mb-0 text-truncate" style="text-shadow: 1px 1px 3px rgba(0,0,0,0.8);" title="<?php 
                    $autorTexto = 'Sin autor';
                    if (isset($libro['autores']) && !empty($libro['autores'])) {
                        $autorTexto = $libro['autores'];
                    } elseif (isset($libro['nomautor']) && !empty($libro['nomautor'])) {
                        $autorTexto = $libro['nomautor'];
                    }
                    echo esc($autorTexto);
                    ?>">
                    <?php echo esc($autorTexto); ?>
                </p>
                
                <!-- Año -->
                <p class="text-white small mb-0" style="opacity: 0.9; text-shadow: 1px 1px 2px rgba(0,0,0,0.7);">
                    <?= esc($libro['anio'] ?: 'N/A') ?>
                </p>
            </div>
        </div>
        
        <!-- Detalles adicionales opcionales (solo si se solicitan) -->
        <?php if (!empty($mostrarDetalles)): ?>
        <div class="card-body p-3">
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
            
            <?php if (in_array('tipo', $mostrarDetalles) && isset($libro['tiporecurso'])): ?>
                <p class="card-text text-muted small">
                    <strong>Tipo:</strong> 
                    <?php if(stripos($libro['tiporecurso'], 'digital') !== false): ?>
                        <span class="text-info">Digital</span>
                    <?php else: ?>
                        <span class="text-dark">Físico</span>
                    <?php endif; ?>
                </p>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</div>
