<?php helper('recurso'); ?>
<?= $header ?>
<?= $navbar ?>

<!-- CSS específico de favoritos -->
<link rel="stylesheet" href="<?= base_url('assets/css/favoritos.css') ?>">

<div class="container mt-4">
    <!-- Header de la página -->
    <div class="row mb-4">
        <div class="col-lg-8 col-md-7">
            <div class="d-flex align-items-center h-100">
                <div>
                    <h1 class="text-primary mb-2 favoritos-header-title">
                        <i class="fas fa-heart me-3" aria-hidden="true"></i>Mis Favoritos
                    </h1>
                    <p class="text-muted mb-0">Tu biblioteca personal de libros favoritos</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-5">
            <div class="d-flex justify-content-end align-items-center h-100">
                <div class="card bg-danger bg-gradient text-white border-0 shadow-sm favoritos-stats-card">
                    <div class="card-body text-center py-3 px-4">
                        <div class="d-flex align-items-center justify-content-center">
                            <i class="fas fa-heart fa-2x me-3" aria-hidden="true"></i>
                            <div>
                                <small class="text-white-50 d-block">Total Favoritos</small>
                                <h3 class="text-white mb-0 fw-bold" id="contadorFavoritos" aria-live="polite"><?= $contadorFavoritos ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido de favoritos -->
    <?php if (!empty($favoritos)): ?>
        <!-- Vista Desktop: Tabla -->
        <div class="card border-0 shadow-sm favoritos-table">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" aria-label="Lista de libros favoritos">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" class="border-0 fw-semibold">Libro</th>
                                <th scope="col" class="border-0 fw-semibold">Autor</th>
                                <th scope="col" class="border-0 fw-semibold">Categoría</th>
                                <th scope="col" class="border-0 fw-semibold">Estado</th>
                                <th scope="col" class="border-0 fw-semibold text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="favoritosLista">
                            <?php foreach ($favoritos as $favorito): ?>
                                <tr class="align-middle" data-favorito-id="<?= $favorito['idfavorito'] ?>">
                                    <td>
                                        <?= renderInfoRecurso($favorito, 'small') ?>
                                    </td>
                                    <td class="text-muted"><?= formatearNombreAutor($favorito['nomautor']) ?></td>
                                    <td>
                                        <?= renderBadgeCategorias($favorito['categoria'] ?? null, $favorito['subcategoria'] ?? null) ?>
                                    </td>
                                    <td>
                                        <?= renderBadgeEstadoRecurso($favorito['estado']) ?>
                                    </td>
                                    <td>
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
                                        
                                        echo renderGrupoAcciones($botones);
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Vista Móvil: Tarjetas -->
        <?= view('partials/favoritos_mobile_cards', ['favoritos' => $favoritos]) ?>
        
        
    <?php else: ?>
        <!-- Mensaje cuando no hay favoritos -->
        <div class="favoritos-empty-state">
            <?= renderEstadoVacio([
                'icono' => 'heart',
                'titulo' => 'No tienes libros favoritos',
                'mensaje' => '¡Explora nuestro catálogo y marca tus libros favoritos!',
                'botones' => [
                    [
                        'url' => site_url('catalogo'),
                        'texto' => 'Explorar Catálogo',
                        'icono' => 'search',
                        'tipo' => 'primary'
                    ],
                    [
                        'url' => site_url('catalogo') . '?categoria=populares',
                        'texto' => 'Libros Populares',
                        'icono' => 'star',
                        'tipo' => 'primary',
                        'outline' => true
                    ]
                ]
            ]) ?>
        </div>
    <?php endif; ?>
</div>

<!-- Modal para detalles del libro -->
<?= view('partials/modals/libro_modal') ?>

<!-- JavaScript de favoritos -->
<script src="<?= base_url('assets/js/favoritos.js') ?>"></script>
<script>
// Configurar URLs para JavaScript
initFavoritosConfig({
    detallesRecurso: '<?= base_url('recurso/detalles/') ?>',
    quitarFavorito: '<?= base_url('catalogo/quitar-favorito') ?>',
    verificarSanciones: '<?= base_url('prestamo/verificar-sanciones') ?>',
    solicitarPrestamo: '<?= base_url('catalogo/solicitar-prestamo/') ?>',
    catalogo: '<?= site_url('catalogo') ?>'
});
</script>

<?= $footer ?>