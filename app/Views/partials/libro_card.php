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

$colClasses = $colClasses ?? 'col-lg-2 col-md-4 col-sm-6';
$mostrarDetalles = $mostrarDetalles ?? [];
$imagenPrefix = $imagenPrefix ?? '';
?>

<div class="<?= $colClasses ?> mb-4">
    <div class="card h-100 border-0 shadow-sm position-relative card-recurso-destacado">
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
                <span class="badge badge-tipo-recurso badge-digital text-white" title="Recurso Digital - <?= esc($debugText) ?>">
                    <i class="fas fa-file-pdf"></i>
                </span>
            <?php else: ?>
                <span class="badge badge-tipo-recurso badge-fisico text-white" title="Recurso Físico - <?= esc($debugText) ?>">
                    <i class="fas fa-book"></i>
                </span>
            <?php endif; ?>
        </div>
        
        <!-- Imagen del libro -->
        <div class="card-img-top-container" style="height: 250px; overflow: hidden;">
            <?php if (!empty($libro['portada'])): ?>
                <?php 
                $rutaCompleta = $imagenPrefix . $libro['portada'];
                ?>
                <img src="<?= $rutaCompleta ?>" 
                     class="card-img-top h-100 w-100" 
                     style="object-fit: cover;" 
                     alt="<?= esc($libro['titulo']) ?>"
                     data-recurso-id="<?= $libro['idrecurso'] ?>"
                     title="Ruta: <?= esc($rutaCompleta) ?>">
            <?php else: ?>
                <div class="bg-light h-100 d-flex align-items-center justify-content-center">
                    <div class="text-center text-muted">
                        <?php if ($esDigital): ?>
                            <i class="fas fa-file-pdf fa-2x mb-2 text-info icono-sin-portada"></i>
                        <?php else: ?>
                            <i class="fas fa-book fa-2x mb-2 icono-sin-portada"></i>
                        <?php endif; ?>
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
            
            <!-- Autores -->
            <p class="card-text text-muted small mb-2">
                <strong>Autores:</strong> 
                <?php 
                $autorTexto = 'Sin autor';
                if (isset($libro['autores']) && !empty($libro['autores'])) {
                    $autorTexto = $libro['autores'];
                } elseif (isset($libro['nomautor']) && !empty($libro['nomautor'])) {
                    $autorTexto = $libro['nomautor'];
                }
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
        
        <!-- Footer de la card -->
        <div class="card-footer bg-transparent border-top-0">
            <?php if ($esDigital && isset($libro['archivo']) && !empty($libro['archivo'])): ?>
                <!-- Botones para recursos digitales -->
                <div class="d-flex gap-1">
                    <button type="button" 
                            class="btn btn-sm btn-success flex-fill btn-leer-pdf" 
                            onclick="leerPDFDirecto('<?= base_url($libro['archivo']) ?>', '<?= esc($libro['titulo']) ?>')">
                        <i class="fas fa-book-open me-1"></i>
                        Leer
                    </button>
                    <button type="button" 
                            class="btn btn-sm btn-outline-primary flex-fill btn-detalles-recurso" 
                            data-bs-toggle="modal" 
                            data-bs-target="#libroModal"
                            data-libro-id="<?= $libro['idrecurso'] ?>"
                            onclick="cargarDetallesLibro(<?= $libro['idrecurso'] ?>)">
                        <i class="fas fa-info-circle me-1"></i>
                        Detalles
                    </button>
                </div>
            <?php else: ?>
                <!-- Botón para recursos físicos -->
                <button type="button" 
                        class="btn btn-sm btn-outline-primary w-100 btn-detalles-recurso" 
                        data-bs-toggle="modal" 
                        data-bs-target="#libroModal"
                        data-libro-id="<?= $libro['idrecurso'] ?>"
                        onclick="cargarDetallesLibro(<?= $libro['idrecurso'] ?>)">
                    <i class="fas fa-info-circle me-1"></i>
                    Ver detalles
                </button>
            <?php endif; ?>
        </div>
    </div>
</div>
