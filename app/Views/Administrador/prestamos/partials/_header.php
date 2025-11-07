<?php
/**
 * Vista parcial: Encabezado de Préstamos
 * Muestra el título, breadcrumb y botones de acción
 */
?>

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="h3 mb-1 fw-bold text-dark">
                    <i class="ti ti-bookmark text-primary me-2"></i>
                    Préstamos Activos
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="#">Sistema de Préstamos</a></li>
                        <li class="breadcrumb-item active">Préstamos Activos</li>
                    </ol>
                </nav>
                <p class="text-muted mb-0 mt-1">Gestiona todos los préstamos activos del sistema bibliotecario</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <button type="button" class="btn btn-primary btn-sm" onclick="mostrarModalNuevoPrestamo()">
                    <i class="ti ti-plus"></i> Nuevo Préstamo
                </button>
            </div>
        </div>
    </div>
</div>
