<?php 
/**
 * Partial: Estado del Préstamo en Historial
 * Muestra badges y detalles del estado final del préstamo
 * 
 * @param array $registro - Registro del préstamo con toda su información
 */
?>
<?php if ($registro['estado_final'] === 'Rechazado'): ?>
    <span class="badge bg-secondary-subtle text-secondary">
        <i class="ti ti-ban me-1"></i>Rechazado
    </span>
    <small class="d-block text-muted mt-1">No aprobado</small>
<?php else: ?>
    <?php 
        $horasTotal = $registro['horas_retraso_total'] ?? 0;
        $diasRetraso = $registro['dias_retraso'] ?? 0;
        $multa = $registro['multa'] ?? 0;
        $tieneIncidencia = isset($registro['tiene_incidencia']) && $registro['tiene_incidencia'] == 1;
    ?>
    
    <?php if ($tieneIncidencia): ?>
        <!-- Devuelto con incidencia (daño/pérdida) -->
        <span class="badge bg-danger-subtle text-danger">
            <i class="ti ti-alert-triangle me-1"></i>Con Incidencia
        </span>
        <small class="d-block text-danger fw-semibold mt-1">
            <?= esc($registro['tipo_incidencia'] ?? 'Incidencia') ?>
        </small>
    <?php elseif ($horasTotal <= 0): ?>
        <span class="badge bg-success-subtle text-success">
            <i class="ti ti-check-circle me-1"></i>Devuelto a Tiempo
        </span>
        <small class="d-block text-muted mt-1">Sin penalización</small>
    <?php elseif ($horasTotal > 0): ?>
        <span class="badge bg-warning-subtle text-warning">
            <i class="ti ti-clock-exclamation me-1"></i>Con Retraso
        </span>
        <?php if ($horasTotal < 24): ?>
            <small class="d-block text-warning fw-semibold mt-1"><?= $horasTotal ?> hora<?= $horasTotal != 1 ? 's' : '' ?></small>
        <?php else: ?>
            <small class="d-block text-warning fw-semibold mt-1"><?= $diasRetraso ?> día<?= $diasRetraso != 1 ? 's' : '' ?></small>
        <?php endif; ?>
        <?php if ($multa > 0): ?>
            <small class="d-block text-danger mt-1">
                <i class="ti ti-cash me-1"></i>Multa: $<?= number_format($multa) ?>
            </small>
        <?php endif; ?>
    <?php else: ?>
        <span class="badge bg-info-subtle text-info">
            <i class="ti ti-clock me-1"></i>Anticipado
        </span>
        <small class="d-block text-muted mt-1"><?= abs($diasRetraso) ?> día<?= abs($diasRetraso) != 1 ? 's' : '' ?> antes</small>
    <?php endif; ?>
<?php endif; ?>
