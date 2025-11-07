<?php
/**
 * Vista de tarjetas móviles para favoritos
 * @param array $favoritos Lista de favoritos
 */
?>

<div class="favoritos-mobile-cards">
    <?php foreach ($favoritos as $favorito): ?>
        <div class="favorito-mobile-card" data-favorito-id="<?= $favorito['idfavorito'] ?>">
            <!-- Header con portada y título -->
            <div class="favorito-mobile-header">
                <div class="favorito-mobile-portada">
                    <?= renderPortadaRecurso($favorito['portada'] ?? null, $favorito['titulo'] ?? 'Portada', 'medium') ?>
                </div>
                <div class="favorito-mobile-info flex-grow-1">
                    <h6 class="mb-1"><?= esc($favorito['titulo']) ?></h6>
                    <?= renderISBN($favorito['isbn'] ?? null) ?>
                </div>
            </div>
            
            <!-- Detalles -->
            <div class="favorito-mobile-details">
                <div class="favorito-mobile-detail">
                    <span class="favorito-mobile-detail-label">
                        <i class="fas fa-user me-1" aria-hidden="true"></i>Autor:
                    </span>
                    <span class="favorito-mobile-detail-value text-muted">
                        <?= formatearNombreAutor($favorito['nomautor'] ?? null) ?>
                    </span>
                </div>
                
                <div class="favorito-mobile-detail">
                    <span class="favorito-mobile-detail-label">
                        <i class="fas fa-tag me-1" aria-hidden="true"></i>Categoría:
                    </span>
                    <span class="favorito-mobile-detail-value">
                        <?= renderBadgeCategorias($favorito['categoria'] ?? null, $favorito['subcategoria'] ?? null) ?>
                    </span>
                </div>
                
                <div class="favorito-mobile-detail">
                    <span class="favorito-mobile-detail-label">
                        <i class="fas fa-info-circle me-1" aria-hidden="true"></i>Estado:
                    </span>
                    <span class="favorito-mobile-detail-value">
                        <?= renderBadgeEstadoRecurso($favorito['estado']) ?>
                    </span>
                </div>
            </div>
            
            <!-- Acciones -->
            <div class="favorito-mobile-actions">
                <?php
                $botones = [
                    [
                        'tipo' => 'primary',
                        'icono' => 'eye',
                        'titulo' => 'Ver detalles',
                        'dataAttrs' => [
                            'bs-toggle' => 'modal',
                            'bs-target' => '#libroModal',
                            'recurso-id' => $favorito['idrecurso']
                        ],
                        'onclick' => "cargarDetallesLibro({$favorito['idrecurso']})"
                    ]
                ];
                
                if ($favorito['estado'] === 'disponible') {
                    $botones[] = [
                        'tipo' => 'success',
                        'icono' => 'book',
                        'titulo' => 'Solicitar préstamo',
                        'onclick' => "solicitarPrestamo({$favorito['idrecurso']})"
                    ];
                } else {
                    $botones[] = [
                        'tipo' => 'secondary',
                        'icono' => 'ban',
                        'titulo' => 'No disponible',
                        'disabled' => true
                    ];
                }
                
                $botones[] = [
                    'tipo' => 'danger',
                    'icono' => 'heart-broken',
                    'titulo' => 'Quitar de favoritos',
                    'onclick' => "quitarFavorito({$favorito['idfavorito']}, {$favorito['idrecurso']})"
                ];
                
                foreach ($botones as $boton):
                    $tipo = $boton['tipo'] ?? 'primary';
                    $icono = $boton['icono'] ?? 'circle';
                    $titulo = $boton['titulo'] ?? '';
                    $onclick = $boton['onclick'] ?? '';
                    $disabled = $boton['disabled'] ?? false;
                    $dataAttrs = $boton['dataAttrs'] ?? [];
                    
                    $dataString = '';
                    foreach ($dataAttrs as $key => $value) {
                        $dataString .= sprintf(' data-%s="%s"', esc($key), esc($value));
                    }
                    
                    $disabledAttr = $disabled ? ' disabled' : '';
                    $onclickAttr = !empty($onclick) ? sprintf(' onclick="%s"', esc($onclick)) : '';
                ?>
                    <button class="btn btn-<?= esc($tipo) ?>"<?= $disabledAttr ?><?= $onclickAttr ?> title="<?= esc($titulo) ?>" aria-label="<?= esc($titulo) ?>"<?= $dataString ?>>
                        <i class="fas fa-<?= esc($icono) ?> me-2" aria-hidden="true"></i><?= esc($titulo) ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
