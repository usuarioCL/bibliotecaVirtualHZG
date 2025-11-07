<?php 
/**
 * Partial: Fila de la Tabla de Historial
 * Renderiza una fila completa del historial de préstamos
 * 
 * @param array $registro - Registro del préstamo con toda su información
 */
?>
<tr class="border-bottom">
    <!-- Usuario -->
    <td class="px-3 py-3">
        <div>
            <h6 class="mb-1 fw-medium"><?= esc($registro['usuario']) ?></h6>
            <p class="text-muted mb-0 small">CC: <?= esc($registro['documento']) ?></p>
        </div>
    </td>

    <!-- Recurso -->
    <td class="px-3 py-3">
        <div>
            <h6 class="mb-1 fw-medium"><?= esc($registro['recurso']) ?></h6>
            <p class="text-muted mb-0 small">
                <i class="ti ti-book me-1"></i>
                Código: <?= esc($registro['codigo_ejemplar'] ?? 'N/A') ?>
            </p>
        </div>
    </td>

    <!-- Período del Préstamo -->
    <td class="px-3 py-3">
        <div>
            <p class="mb-1 small">
                <i class="ti ti-calendar-plus text-primary me-1"></i>
                <strong>Inicio:</strong> <?= date('d/m/Y', strtotime($registro['fecha_prestamo'] ?? $registro['fechaprestamo'] ?? date('Y-m-d'))) ?>
            </p>
            <?php if ($registro['estado_final'] !== 'Rechazado'): ?>
            <p class="mb-1 small">
                <i class="ti ti-calendar-check text-success me-1"></i>
                <strong>Devuelto:</strong> <?= $registro['fecha_devolucion'] ? date('d/m/Y', strtotime($registro['fecha_devolucion'])) : 'N/A' ?>
            </p>
            <p class="mb-0 small text-muted">
                <i class="ti ti-clock-hour-3 me-1"></i>
                Duración: 
                <?php
                    $fechaInicio = new DateTime($registro['fecha_prestamo'] ?? $registro['fechaprestamo'] ?? date('Y-m-d'));
                    $fechaFin = new DateTime($registro['fecha_devolucion']);
                    $diff = $fechaInicio->diff($fechaFin);
                    echo $diff->days . ' día' . ($diff->days != 1 ? 's' : '');
                ?>
            </p>
            <?php else: ?>
            <p class="mb-0 small text-danger">
                <i class="ti ti-x text-danger me-1"></i>
                <strong>Rechazado:</strong> <?= $registro['fecha_registro'] ? date('d/m/Y', strtotime($registro['fecha_registro'])) : 'N/A' ?>
            </p>
            <?php endif; ?>
        </div>
    </td>

    <!-- Cantidad -->
    <td class="px-3 py-3 text-center">
        <div class="d-flex flex-column align-items-center">
            <span class="badge bg-info-subtle text-info fs-6 px-3 py-2 mb-1">
                <?= isset($registro['cantidad']) ? $registro['cantidad'] : 1 ?>
            </span>
            <small class="text-muted">
                <?= (isset($registro['cantidad']) && $registro['cantidad'] == 1) ? 'ejemplar' : 'ejemplares' ?>
            </small>
        </div>
    </td>

    <!-- Estado Final -->
    <td class="px-3 py-3 text-center">
        <?= view('Administrador/prestamos/partials/_estado_historial', ['registro' => $registro]) ?>
    </td>

    <!-- Observaciones -->
    <td class="px-3 py-3" style="max-width: 200px;">
        <?= view('Administrador/prestamos/partials/_observaciones_historial', ['registro' => $registro]) ?>
    </td>

    <!-- Acciones -->
    <td class="px-3 py-3 text-center">
        <div class="d-flex gap-2 justify-content-center">
            <?php if ($registro['estado_final'] === 'Rechazado'): ?>
                <button type="button" class="btn btn-sm btn-outline-secondary" 
                        onclick="Historial.verDetalleRechazado(<?= $registro['id'] ?>)"
                        title="Ver Motivo de Rechazo">
                    <i class="ti ti-eye"></i>
                </button>
            <?php else: ?>
                <button type="button" class="btn btn-sm btn-outline-info" 
                        onclick="Historial.verDetalleHistorial(<?= $registro['id'] ?>)"
                        title="Ver Detalles">
                    <i class="ti ti-eye"></i>
                </button>

                <?php 
                $horasTotal = $registro['horas_retraso_total'] ?? 0;
                $diasRetraso = $registro['dias_retraso'] ?? 0;
                $tieneRetraso = ($horasTotal > 0 || $diasRetraso > 0);
                ?>
                
                <?php if ($tieneRetraso): ?>
                <button type="button" class="btn btn-sm btn-outline-warning" 
                        onclick="Historial.generarSancion(<?= $registro['id'] ?>, '<?= esc($registro['usuario']) ?>', <?= $horasTotal ?>)"
                        title="Generar Sanción por Retraso">
                    <i class="ti ti-alert-triangle"></i>
                </button>
                <?php endif; ?>
            <?php endif; ?>

            <button type="button" class="btn btn-sm btn-outline-danger" 
                    onclick="Historial.confirmarEliminacion(<?= $registro['id'] ?>, '<?= $registro['estado_final'] ?>')"
                    title="Eliminar">
                <i class="ti ti-x"></i>
            </button>
        </div>
    </td>
</tr>
