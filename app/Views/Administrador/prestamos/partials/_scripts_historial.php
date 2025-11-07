<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Módulos de Historial Refactorizados -->
<script>
    // Inyectar baseURL para que esté disponible en los módulos
    window.baseURL = '<?= base_url() ?>';
</script>
<script src="<?= base_url('assets/js/historial/config.js') ?>"></script>
<script src="<?= base_url('assets/js/historial/utils.js') ?>"></script>
<script src="<?= base_url('assets/js/historial/api.js') ?>"></script>
<script src="<?= base_url('assets/js/historial/modals.js') ?>"></script>
<script src="<?= base_url('assets/js/historial/historial.js') ?>"></script>
