    <footer class="text-center p-3 mt-auto border-top">
    </footer>    
    <!-- jQuery (requerido por Select2 y otros plugins) -->
    <script src="<?= base_url('assets/libs/jquery/dist/jquery.min.js') ?>"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <!-- Select2 JS global -->
    <script src="<?= base_url('assets/js/select2.min.js') ?>"></script>
    
    <!-- Scripts modulares de la página principal -->
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
    
    // Inicializar controlador de página principal
    document.addEventListener('DOMContentLoaded', function() {
        if (!window.paginaPrincipal && typeof PaginaPrincipalController !== 'undefined') {
            window.paginaPrincipal = new PaginaPrincipalController();
        }
    });
    </script>
    </body>
</html>