<?php
/**
 * Vista parcial: Estadísticas de Solicitudes
 * Muestra tarjetas con métricas rápidas
 */
helper('solicitudes');
?>

<div class="row mb-3">
    <!-- Total Solicitudes -->
    <div class="col-lg-3 col-md-6 mb-2">
        <div class="card stats-card primary h-100 shadow-sm border-0">
            <div class="card-body py-2 px-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="fw-bold text-primary mb-0">
                            <?= number_format($estadisticas['total_solicitudes'] ?? 0) ?>
                        </h4>
                        <p class="text-muted mb-0 small">Total Solicitudes</p>
                    </div>
                    <div class="rounded-circle bg-primary bg-opacity-10 p-2">
                        <i class="ti ti-clock text-primary" style="font-size: 1.5rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Préstamos Nuevos -->
    <div class="col-lg-3 col-md-6 mb-2">
        <div class="card stats-card success h-100 shadow-sm border-0">
            <div class="card-body py-2 px-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="fw-bold text-success mb-0">
                            <?= number_format($estadisticas['solicitudes_prestamo'] ?? 0) ?>
                        </h4>
                        <p class="text-muted mb-0 small">Préstamos Nuevos</p>
                    </div>
                    <div class="rounded-circle bg-success bg-opacity-10 p-2">
                        <i class="ti ti-book-plus text-success" style="font-size: 1.5rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Renovaciones -->
    <div class="col-lg-3 col-md-6 mb-2">
        <div class="card stats-card warning h-100 shadow-sm border-0">
            <div class="card-body py-2 px-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="fw-bold text-warning mb-0">
                            <?= number_format($estadisticas['solicitudes_renovacion'] ?? 0) ?>
                        </h4>
                        <p class="text-muted mb-0 small">Renovaciones</p>
                    </div>
                    <div class="rounded-circle bg-warning bg-opacity-10 p-2">
                        <i class="ti ti-refresh text-warning" style="font-size: 1.5rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Solicitudes Hoy -->
    <div class="col-lg-3 col-md-6 mb-2">
        <div class="card stats-card info h-100 shadow-sm border-0">
            <div class="card-body py-2 px-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="fw-bold text-info mb-0">
                            <?= number_format($estadisticas['hoy'] ?? 0) ?>
                        </h4>
                        <p class="text-muted mb-0 small">Solicitudes Hoy</p>
                    </div>
                    <div class="rounded-circle bg-info bg-opacity-10 p-2">
                        <i class="ti ti-calendar text-info" style="font-size: 1.5rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
