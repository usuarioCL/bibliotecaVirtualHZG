<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Principal</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Funciones globales para JS -->
    <script>
        // Definir URLs globales para JavaScript
        window.base_url_login = '<?= base_url('login') ?>';
        window.base_url = '<?= base_url() ?>';
    </script>
    <script src="<?= base_url('assets/js/funciones-globales.js') ?>"></script>
    <!-- Sistema de Notificaciones -->
    <?php if (session()->get('logged_in')): ?>
    <script src="<?= base_url('assets/js/notificaciones.js') ?>"></script>
    <?php endif; ?>
    <!-- Sistema de Compartir por WhatsApp -->
    <script src="<?= base_url('assets/js/compartir-whatsapp.js') ?>"></script>
    <!-- Animate.css para animaciones -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/recursos-destacados.css') ?>">
    <?php if (session()->get('logged_in')): ?>
    <link rel="stylesheet" href="<?= base_url('assets/css/notificaciones.css') ?>">
    <?php endif; ?>
    <!-- Estilos institucionales de la Biblioteca Virtual HZG -->
    <link rel="stylesheet" href="<?= base_url('assets/css/biblioteca-hzg.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/libro-card.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/components/pdf-viewer-modal.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/components/voice-controls.css') ?>">
</head>
<body class="d-flex flex-column min-vh-100">

<!-- Modal de PDF (disponible globalmente) -->
<?php include(APPPATH . 'Views/partials/modals/pdf_viewer_modal.php'); ?>

<!-- Modal para detalles del libro (disponible globalmente) -->
<div class="modal fade" id="libroModal" tabindex="-1" aria-labelledby="libroModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="libroModalLabel">Detalles del Libro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="libroModalBody">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="text-muted mt-2">Cargando detalles del libro...</p>
                </div>
            </div>
        </div>
    </div>
</div>