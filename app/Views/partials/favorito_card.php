<?php
/**
 * Partial: Card de Favorito Reutilizable
 * 
 * Variables requeridas:
 * - $favorito: Array con información del libro favorito
 * 
 * Variables opcionales:
 * - $colClasses: Clases CSS para las columnas (default: 'col-lg-6 col-xl-4')
 * - $mostrarAcciones: Mostrar botones de acción (default: true)
 */

$colClasses = $colClasses ?? 'col-lg-6 col-xl-4';
$mostrarAcciones = $mostrarAcciones ?? true;

// Determinar estado del libro
$estadoClass = 'success';
$estadoTexto = 'Disponible';

if (isset($favorito['estado'])) {
    switch ($favorito['estado']) {
        case 'disponible':
            $estadoClass = 'success';
            $estadoTexto = 'Disponible';
            break;
        case 'prestado':
            $estadoClass = 'warning';
            $estadoTexto = 'Prestado';
            break;
        case 'mantenimiento':
            $estadoClass = 'info';
            $estadoTexto = 'Mantenimiento';
            break;
        default:
            $estadoClass = 'secondary';
            $estadoTexto = 'No disponible';
    }
}
?>

<div class="<?= $colClasses ?> mb-4">
    <div class="card h-100 border-start border-warning border-3">
        <div class="card-header bg-light border-0 d-flex justify-content-between align-items-center">
            <span class="badge bg-<?= $estadoClass ?>"><?= $estadoTexto ?></span>
            <small class="text-warning">
                <i class="fas fa-heart"></i> Favorito
            </small>
        </div>
        
        <div class="card-body">
            <div class="row">
                <div class="col-4">
                    <?php if (!empty($favorito['portada'])): ?>
                        <img src="<?= base_url($favorito['portada']) ?>" 
                             class="img-fluid rounded" 
                             alt="Portada" 
                             style="height: 100px; object-fit: cover;">
                    <?php else: ?>
                        <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                             style="height: 100px;">
                            <i class="fas fa-book text-muted fa-2x"></i>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-8">
                    <h6 class="card-title mb-2">
                        <?= esc($favorito['titulo']) ?>
                    </h6>
                    <p class="card-text small text-muted mb-1">
                        <strong>Autores:</strong> <?= esc($favorito['nomautor'] ?? 'Sin autor') ?>
                    </p>
                    <p class="card-text small text-muted mb-1">
                        <strong>Año:</strong> <?= esc($favorito['anio'] ?? 'N/A') ?>
                    </p>
                    <?php if (!empty($favorito['categoria'])): ?>
                        <p class="card-text small text-muted mb-1">
                            <strong>Categoría:</strong> <?= esc($favorito['categoria']) ?>
                        </p>
                    <?php endif; ?>
                    <?php if (!empty($favorito['editorial'])): ?>
                        <p class="card-text small text-muted mb-0">
                            <strong>Editorial:</strong> <?= esc($favorito['editorial']) ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <?php if ($mostrarAcciones): ?>
            <div class="card-footer bg-transparent border-0">
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary btn-sm flex-fill"
                            onclick="verDetalles(<?= $favorito['idrecurso'] ?>)">
                        <i class="fas fa-eye me-1"></i>Ver Detalles
                    </button>
                    <button class="btn btn-outline-danger btn-sm flex-fill"
                            onclick="quitarFavorito(<?= $favorito['idfavorito'] ?>, <?= $favorito['idrecurso'] ?>)">
                        <i class="fas fa-heart-broken me-1"></i>Quitar
                    </button>
                    <?php if ($favorito['estado'] === 'disponible'): ?>
                        <button class="btn btn-primary btn-sm flex-fill"
                                onclick="solicitarPrestamo(<?= $favorito['idrecurso'] ?>)">
                            <i class="fas fa-book-reader me-1"></i>Prestar
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
