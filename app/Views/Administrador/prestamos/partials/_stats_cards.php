<?php
/**
 * Vista parcial: Tarjetas de Estadísticas de Préstamos
 * Muestra métricas rápidas de préstamos activos
 */
?>

<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stats-card primary h-100 shadow-sm">
            <div class="card-body text-center">
                <div class="d-flex align-items-center justify-content-center mb-2">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                        <i class="ti ti-bookmark text-primary" style="font-size: 2.5rem;"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-primary mb-1"><?= isset($estadisticas['total_prestamos']) ? number_format($estadisticas['total_prestamos']) : 0 ?></h3>
                <p class="text-muted mb-0 small">Total Préstamos</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stats-card danger h-100 shadow-sm">
            <div class="card-body text-center">
                <div class="d-flex align-items-center justify-content-center mb-2">
                    <div class="rounded-circle bg-danger bg-opacity-10 p-3">
                        <i class="ti ti-alert-circle text-danger" style="font-size: 2.5rem;"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-danger mb-1"><?= isset($estadisticas['vencidos_hoy']) ? number_format($estadisticas['vencidos_hoy']) : 0 ?></h3>
                <p class="text-muted mb-0 small">Vencidos Hoy</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stats-card warning h-100 shadow-sm">
            <div class="card-body text-center">
                <div class="d-flex align-items-center justify-content-center mb-2">
                    <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                        <i class="ti ti-clock text-warning" style="font-size: 2.5rem;"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-warning mb-1"><?= isset($estadisticas['proximos_vencer']) ? number_format($estadisticas['proximos_vencer']) : 0 ?></h3>
                <p class="text-muted mb-0 small">Próximos a Vencer</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stats-card info h-100 shadow-sm">
            <div class="card-body text-center">
                <div class="d-flex align-items-center justify-content-center mb-2">
                    <div class="rounded-circle bg-info bg-opacity-10 p-3">
                        <i class="ti ti-refresh text-info" style="font-size: 2.5rem;"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-info mb-1"><?= isset($estadisticas['renovaciones_pendientes']) ? number_format($estadisticas['renovaciones_pendientes']) : 0 ?></h3>
                <p class="text-muted mb-0 small">Renovaciones Pendientes</p>
            </div>
        </div>
    </div>
</div>
