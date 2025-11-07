<?= $header ?>
<?= $navbar ?>

<!-- Estilos institucionales de la Biblioteca Virtual HZG -->
<link rel="stylesheet" href="<?= base_url('assets/css/biblioteca-hzg.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/css/components/pdf-viewer-modal.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/css/components/voice-controls.css') ?>">

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
        'librosPopulares' => $librosPopulares
    ]) ?>
</div>

<!-- Modales -->
<?= view('partials/modals/libro_modal') ?>
<?= view('partials/modals/pdf_viewer_modal') ?>

<?= $footer ?>

<!-- Scripts modulares -->
<script src="<?= base_url('assets/js/shared/pdfjs-loader.js') ?>"></script>
<script src="<?= base_url('assets/js/shared/voice-utils.js') ?>"></script>
<script src="<?= base_url('assets/js/modules/pdfViewer.js') ?>"></script>
<script src="<?= base_url('assets/js/modules/voiceReader.js') ?>"></script>
<script src="<?= base_url('assets/js/modules/prestamoForm.js') ?>"></script>
<script src="<?= base_url('assets/js/modules/favoritosHandler.js') ?>"></script>
<script src="<?= base_url('assets/js/paginaPrincipal.js') ?>"></script>

<!-- Configuración global -->
<script>
// Configuración global de la aplicación
window.APP_CONFIG = {
    baseUrl: '<?= base_url() ?>',
    routes: {
        detallesRecurso: '<?= base_url('recurso/detalles/') ?>',
        toggleFavorito: '<?= base_url('catalogo/toggle-favorito') ?>',
        verificarSanciones: '<?= base_url('prestamo/verificar-sanciones') ?>',
        formularioPrestamo: '<?= base_url('prestamo/formulario/') ?>',
        solicitarPrestamo: '<?= base_url('prestamo/solicitar') ?>'
    }
};
</script>
