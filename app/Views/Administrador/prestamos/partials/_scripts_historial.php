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

<!-- Script para Exportar Excel -->
<script>
$(document).ready(function() {
    // Evento para exportar historial a Excel
    $('#btnExportarExcel').on('click', function() {
        // Mostrar indicador de carga
        Swal.fire({
            title: 'Generando archivo Excel...',
            text: 'Por favor espera mientras se genera el archivo',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Abrir en nueva ventana para descargar
        window.location.href = '<?= base_url('historial-prestamos/exportar-excel') ?>';
        
        // Cerrar el loading después de un momento
        setTimeout(() => {
            Swal.close();
        }, 1500);
    });
});
</script>
