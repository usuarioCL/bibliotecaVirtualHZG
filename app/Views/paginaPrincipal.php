<?= $header ?>
<?= $navbar ?>
<div class="container">
    <!-- Hero section con buscador -->
    <?= view('partials/hero_section') ?>
    
    <!-- Pestañas para alternar entre Niveles y Categorías -->
    <?= view('partials/niveles_categorias_tabs', [
        'niveles' => $niveles, 
        'categorias' => $categorias
    ]) ?>
    
    <!-- Sección de Recursos -->
    <?= view('partials/recursos_grid', [
        'recursosRecientes' => $recursosRecientes,
        'recursosPopulares' => $recursosPopulares
    ]) ?>
</div>

<!-- Modales -->
<?= view('partials/modals/libro_modal') ?>
<?= view('partials/modals/pdf_viewer_modal') ?>

<?= $footer ?>
