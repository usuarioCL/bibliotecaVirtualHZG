<?php
/**
 * Vista parcial: Fila de Solicitud
 * Renderiza una fila individual de la tabla de solicitudes
 * Variables esperadas: $solicitud
 */
helper('solicitudes');

$esRenovacion = ($solicitud['tipo_solicitud'] ?? 'prestamo') === 'renovacion';
$diasEspera = calcular_dias_espera($solicitud['fecha_creacion'] ?? $solicitud['fecha_solicitud'] ?? null);
?>

<tr class="border-bottom">
    <!-- Tipo de Solicitud -->
    <td class="px-3 py-3">
        <?= badge_tipo_solicitud($esRenovacion ? 'Renovación' : 'Nuevo') ?>
    </td>
    
    <!-- Usuario -->
    <td class="px-3 py-3">
        <div class="d-flex align-items-center">
            <div class="me-3">
                <div class="rounded-2 <?= $esRenovacion ? 'bg-warning' : 'bg-primary' ?> bg-opacity-10 p-2">
                    <i class="ti ti-user <?= $esRenovacion ? 'text-warning' : 'text-primary' ?> fs-5"></i>
                </div>
            </div>
            <div>
                <h6 class="mb-1 fw-semibold"><?= esc($solicitud['usuario']) ?></h6>
                <p class="text-muted mb-0 small">CC: <?= esc($solicitud['documento']) ?></p>
            </div>
        </div>
    </td>
    
    <!-- Recurso -->
    <td class="px-3 py-3">
        <div>
            <h6 class="mb-1 fw-medium"><?= truncar_texto($solicitud['recurso'], 50) ?></h6>
            <p class="text-muted mb-0 small">
                Código: <?= esc($solicitud['codigo_ejemplar']) ?>
                <?php if ($esRenovacion): ?>
                    | Préstamo #<?= $solicitud['idprestamo'] ?>
                <?php endif; ?>
            </p>
            <?php if ($esRenovacion && !empty($solicitud['motivo'])): ?>
                <p class="text-muted mb-0 small">
                    <i class="ti ti-message text-info me-1"></i>
                    <?= truncar_texto($solicitud['motivo'], 60) ?>
                </p>
            <?php endif; ?>
        </div>
    </td>
    
    <!-- Fechas -->
    <td class="px-3 py-3">
        <?php if ($esRenovacion): ?>
            <!-- Fechas para renovación -->
            <div>
                <p class="mb-1 small text-danger">
                    <strong>Vence:</strong> 
                    <?= formatear_fecha($solicitud['fecha_vencimiento_actual']) ?>
                </p>
                <p class="mb-0 small text-success">
                    <strong>Nueva:</strong> 
                    <?= formatear_fecha($solicitud['nueva_fecha_devolucion']) ?>
                </p>
            </div>
        <?php else: ?>
            <!-- Fechas para préstamo nuevo -->
            <div>
                <p class="mb-1 small">
                    <i class="ti ti-calendar-plus text-primary me-1"></i>
                    <strong>Inicio:</strong> <?= formatear_fecha($solicitud['fecha_solicitud']) ?>
                </p>
                <p class="mb-0 small">
                    <i class="ti ti-calendar text-success me-1"></i>
                    <strong>Entrega:</strong> <?= formatear_fecha($solicitud['fecha_devolucion']) ?>
                </p>
            </div>
        <?php endif; ?>
    </td>
    
    <!-- Extensión / Cantidad -->
    <td class="px-3 py-3 text-center">
        <?php if ($esRenovacion): ?>
            <div class="d-flex flex-column align-items-center">
                <span class="badge bg-warning-subtle text-warning fs-6 px-3 py-2 mb-1">
                    +<?= $solicitud['dias_extension'] ?? 0 ?>
                </span>
                <small class="text-muted">
                    <?= ($solicitud['dias_extension'] ?? 0) == 1 ? 'día' : 'días' ?>
                </small>
            </div>
        <?php else: ?>
            <div class="d-flex flex-column align-items-center">
                <span class="badge bg-primary-subtle text-primary fs-6 px-3 py-2 mb-1">
                    <?= $solicitud['cantidad_solicitada'] ?? 1 ?>
                </span>
                <small class="text-muted">
                    <?= ($solicitud['cantidad_solicitada'] ?? 1) == 1 ? 'ejemplar' : 'ejemplares' ?>
                </small>
            </div>
        <?php endif; ?>
    </td>
    
    <!-- Prioridad -->
    <td class="px-3 py-3 text-center">
        <?= badge_prioridad($solicitud['prioridad']) ?>
        <small class="d-block text-muted mt-1">
            <?= badge_dias_espera($diasEspera) ?>
        </small>
    </td>
    
    <!-- Estado / Disponibilidad -->
    <td class="px-3 py-3 text-center">
        <?php if ($esRenovacion): ?>
            <!-- Para renovaciones, mostrar estado del vencimiento -->
            <?php 
            $hoy = new DateTime();
            $vencimiento = new DateTime($solicitud['fecha_vencimiento_actual']);
            $diff = $hoy->diff($vencimiento);
            $diasRestantes = $diff->invert ? -$diff->days : $diff->days;
            ?>
            <?php if ($diasRestantes < 0): ?>
                <span class="badge bg-danger-subtle text-danger">
                    <i class="ti ti-alert-circle me-1"></i>Vencido
                </span>
            <?php elseif ($diasRestantes <= 2): ?>
                <span class="badge bg-warning-subtle text-warning">
                    <i class="ti ti-alert-triangle me-1"></i>Por vencer
                </span>
            <?php else: ?>
                <span class="badge bg-success-subtle text-success">
                    <i class="ti ti-check-circle me-1"></i>Vigente
                </span>
            <?php endif; ?>
        <?php else: ?>
            <!-- Para préstamos nuevos, mostrar disponibilidad -->
            <?= badge_disponibilidad($solicitud['disponible']) ?>
        <?php endif; ?>
    </td>
    
    <!-- Acciones -->
    <td class="px-3 py-3 text-center">
        <div class="d-flex gap-1 justify-content-center">
            <?php if ($esRenovacion): ?>
                <!-- Botones para renovación -->
                <?= boton_aprobar($solicitud['id'], 'renovacion', $solicitud['idprestamo']) ?>
                <?= boton_rechazar($solicitud['id'], 'renovacion') ?>
            <?php else: ?>
                <!-- Botones para préstamo nuevo -->
                <?php if ($solicitud['disponible']): ?>
                    <?= boton_aprobar($solicitud['id']) ?>
                <?php endif; ?>
                <?= boton_rechazar($solicitud['id']) ?>
                <?= boton_ver_detalle($solicitud['id']) ?>
            <?php endif; ?>
        </div>
    </td>
</tr>
