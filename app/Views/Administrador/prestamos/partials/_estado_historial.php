<?php 
/**
 * Partial: Estado del Préstamo en Historial
 * Muestra badges y detalles del estado final del préstamo
 * 
 * @param array $registro - Registro del préstamo con toda su información
 */

// Variables para lógica de estados
$horasTotal = $registro['horas_retraso_total'] ?? 0;
$diasRetraso = $registro['dias_retraso'] ?? 0;
$multa = $registro['multa'] ?? 0;
$tieneIncidencia = isset($registro['tiene_incidencia']) && $registro['tiene_incidencia'] == 1;
$renovaciones = $registro['renovaciones'] ?? 0;
$fechaDevolucion = $registro['fecha_devolucion'] ?? null;
$fechaVencimiento = $registro['fecha_vencimiento'] ?? null;
$estadoFinal = $registro['estado_final'] ?? '';

// Determinar el estado del préstamo
?>

<?php if ($estadoFinal === 'Rechazado'): ?>
    <!-- Estado: Rechazado -->
    <span class="badge bg-secondary-subtle text-secondary">
        <i class="ti ti-ban me-1"></i>Rechazado
    </span>
    <small class="d-block text-muted mt-1">No aprobado</small>

<?php elseif ($estadoFinal === 'Activo' || ($fechaDevolucion === null && $estadoFinal !== 'Rechazado')): ?>
    <?php 
        // Para préstamos activos, verificar si está vencido
        $fechaActual = new DateTime();
        $fechaVenc = $fechaVencimiento ? new DateTime($fechaVencimiento) : null;
        $estaVencido = $fechaVenc && $fechaActual > $fechaVenc;
    ?>
    
    <?php if ($estaVencido): ?>
        <!-- Estado: Vencido/En Mora -->
        <span class="badge bg-danger-subtle text-danger">
            <i class="ti ti-clock-exclamation me-1"></i>Vencido
        </span>
        <?php 
            $diasVencido = $fechaActual->diff($fechaVenc)->days;
        ?>
        <small class="d-block text-danger fw-semibold mt-1">
            <?= $diasVencido ?> día<?= $diasVencido != 1 ? 's' : '' ?> de mora
        </small>
    <?php else: ?>
        <!-- Estado: Activo -->
        <span class="badge bg-primary-subtle text-primary">
            <i class="ti ti-book me-1"></i>En Préstamo
        </span>
        <?php if ($fechaVenc): ?>
            <?php 
                $diasRestantes = $fechaActual->diff($fechaVenc)->days;
                $horasRestantes = $fechaActual->diff($fechaVenc)->h;
            ?>
            <small class="d-block text-muted mt-1">
                <?php if ($diasRestantes > 0): ?>
                    Vence en <?= $diasRestantes ?> día<?= $diasRestantes != 1 ? 's' : '' ?>
                <?php else: ?>
                    Vence hoy
                <?php endif; ?>
            </small>
        <?php endif; ?>
    <?php endif; ?>
    
    <?php if ($renovaciones > 0): ?>
        <small class="d-block text-info mt-1">
            <i class="ti ti-refresh me-1"></i>Renovado <?= $renovaciones ?> vez<?= $renovaciones != 1 ? 'es' : '' ?>
        </small>
    <?php endif; ?>

<?php elseif ($estadoFinal === 'Pendiente' || $estadoFinal === 'Solicitud'): ?>
    <!-- Estado: Pendiente de Aprobación -->
    <span class="badge bg-warning-subtle text-warning">
        <i class="ti ti-clock-hour-3 me-1"></i>Pendiente
    </span>
    <small class="d-block text-muted mt-1">Esperando aprobación</small>

<?php elseif ($estadoFinal === 'Aprobado'): ?>
    <!-- Estado: Aprobado pero no iniciado -->
    <span class="badge bg-success-subtle text-success">
        <i class="ti ti-check me-1"></i>Aprobado
    </span>
    <small class="d-block text-muted mt-1">Listo para entregar</small>

<?php elseif ($estadoFinal === 'Cancelado'): ?>
    <!-- Estado: Cancelado -->
    <span class="badge bg-dark-subtle text-dark">
        <i class="ti ti-x me-1"></i>Cancelado
    </span>
    <small class="d-block text-muted mt-1">Préstamo cancelado</small>

<?php else: ?>
    <!-- Estados de préstamos devueltos -->
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
    
    <?php if ($renovaciones > 0): ?>
        <small class="d-block text-info mt-1">
            <i class="ti ti-refresh me-1"></i>Renovado <?= $renovaciones ?> vez<?= $renovaciones != 1 ? 'es' : '' ?>
        </small>
    <?php endif; ?>
<?php endif; ?>
