<?php
/**
 * Vista parcial: Scripts de Préstamos
 * Carga todos los módulos JavaScript necesarios para el sistema de préstamos
 */
?>

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Configuración de URL base para el módulo API -->
<script>
    window.BIBLIOTECA_BASE_URL = '<?= base_url() ?>';
</script>

<!-- Módulos de Préstamos (en orden de dependencias) -->
<script src="<?= base_url('assets/js/config/prestamos.constants.js') ?>"></script>
<script src="<?= base_url('assets/js/shared/datetime.utils.js') ?>"></script>
<script src="<?= base_url('assets/js/prestamos/prestamos.validator.js') ?>"></script>
<script src="<?= base_url('assets/js/prestamos/prestamos.api.js') ?>"></script>
<script src="<?= base_url('assets/js/prestamos/prestamos.modal.js') ?>"></script>
<script src="<?= base_url('assets/js/prestamos/prestamos.main.js') ?>"></script>
