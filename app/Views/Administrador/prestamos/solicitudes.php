<?php
/**
 * Vista Principal: Solicitudes Pendientes
 * Gestión de solicitudes de préstamos y renovaciones
 */
helper('solicitudes');
?>

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Estilos del módulo -->
<link rel="stylesheet" href="<?= base_url('assets/css/prestamos/solicitudes.css') ?>">

<div class="container-fluid">
    <!-- Encabezado de la página con breadcrumb -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between mb-4">
                <div>
                    <h1 class="h3 mb-1 fw-bold text-dark">
                        <i class="ti ti-clock-hour-3 text-primary me-2"></i>
                        Solicitudes Pendientes
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="#">Sistema de Préstamos</a></li>
                            <li class="breadcrumb-item active">Solicitudes Pendientes</li>
                        </ol>
                    </nav>
                    <p class="text-muted mb-0 mt-1">Gestiona las solicitudes de préstamos pendientes de aprobación</p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <button type="button" class="btn btn-success btn-sm" onclick="aprobarTodas()">
                        <i class="ti ti-check-all"></i> Aprobar Disponibles
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" onclick="rechazarTodas()">
                        <i class="ti ti-x-all"></i> Rechazar Todas
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas rápidas -->
    <?= view('Administrador/prestamos/partials/_estadisticas', [
        'estadisticas' => $estadisticas ?? []
    ]) ?>

    <!-- Tabla de solicitudes -->
    <?= view('Administrador/prestamos/partials/_tabla_solicitudes', [
        'solicitudes' => $solicitudes ?? [],
        'estadisticas' => $estadisticas ?? []
    ]) ?>
</div>

<!-- Variable global BASE_URL para JavaScript -->
<script>
    const BASE_URL = '<?= base_url() ?>';
</script>

<!-- Módulos JavaScript -->
<script src="<?= base_url('assets/js/prestamos/solicitudes.utils.js') ?>"></script>
<script src="<?= base_url('assets/js/prestamos/solicitudes.api.js') ?>"></script>
<script src="<?= base_url('assets/js/prestamos/solicitudes.ui.js') ?>"></script>
<script src="<?= base_url('assets/js/prestamos/solicitudes.js') ?>"></script>

<!-- Inicialización del módulo -->
<script>
    // Inicializar el controlador de solicitudes con los datos del servidor
    const solicitudesData = <?= json_encode($solicitudes ?? []) ?>;
    SolicitudesController.init(solicitudesData);
</script>
