<?php
/**
 * Vista parcial: Fila de Préstamo
 * Renderiza una fila individual de la tabla de préstamos
 * Variables esperadas: $prestamo
 */
?>
<tr class="border-bottom">
    <td class="px-3 py-3">
        <div class="d-flex align-items-center">
            <div class="me-3">
                <div class="rounded-2 bg-primary bg-opacity-10 p-2">
                    <i class="ti ti-user text-primary fs-5"></i>
                </div>
            </div>
            <div>
                <h6 class="mb-1 fw-semibold"><?= esc($prestamo['usuario']) ?></h6>
                <p class="text-muted mb-0 small">CC: <?= esc($prestamo['documento']) ?></p>
            </div>
        </div>
    </td>
    <td class="px-3 py-3">
        <div>
            <h6 class="mb-1 fw-medium"><?= esc($prestamo['recurso']) ?></h6>
            <p class="text-muted mb-0 small">Ejemplar: <?= esc($prestamo['codigo_ejemplar']) ?></p>
        </div>
    </td>
    <td class="px-3 py-3">
        <div>
            <p class="mb-1 small">
                <i class="ti ti-calendar-plus text-primary me-1"></i>
                <strong>Inicio:</strong> <?= date('d/m/Y', strtotime($prestamo['fecha_prestamo'])) ?>
            </p>
            <p class="mb-1 small">
                <i class="ti ti-calendar text-success me-1"></i>
                <strong>Entrega:</strong> <?= !empty($prestamo['fecha_devolucion']) ? date('d/m/Y', strtotime($prestamo['fecha_devolucion'])) : 'No especificada' ?>
            </p>
            <p class="mb-0 small text-muted">
                <i class="ti ti-clock-hour-3 me-1"></i>
                Duración: 
                <?php
                    if (!empty($prestamo['fecha_devolucion'])) {
                        $inicio = new DateTime($prestamo['fecha_prestamo']);
                        $entrega = new DateTime($prestamo['fecha_devolucion']);
                        $diff = $inicio->diff($entrega);
                        echo $diff->days . ' día' . ($diff->days != 1 ? 's' : '');
                    } else {
                        echo 'No especificada';
                    }
                ?>
            </p>
        </div>
    </td>
    <td class="px-3 py-3 text-center">
        <div class="d-flex flex-column align-items-center">
            <span class="badge bg-primary-subtle text-primary fs-6 px-3 py-2 mb-1">
                <?= isset($prestamo['cantidad']) ? $prestamo['cantidad'] : 1 ?>
            </span>
            <small class="text-muted">
                <?= (isset($prestamo['cantidad']) && $prestamo['cantidad'] == 1) ? 'ejemplar' : 'ejemplares' ?>
            </small>
        </div>
    </td>
    <td class="px-3 py-3 text-center">
        <?php if ($prestamo['estado'] == 'Activo'): ?>
            <?php if ($prestamo['dias_restantes'] > 3): ?>
                <span class="badge bg-success-subtle text-success">
                    <i class="ti ti-check-circle me-1"></i><?= esc($prestamo['estado']) ?>
                </span>
                <small class="d-block text-muted mt-1">
                    <?php 
                        $diasRestantesDecimal = abs($prestamo['dias_restantes']);
                        $dias = floor($diasRestantesDecimal);
                        $horasRestantes = round(($diasRestantesDecimal - $dias) * 24);
                        if ($dias > 0) {
                            echo $dias . ' día' . ($dias != 1 ? 's' : '');
                        } else {
                            echo $horasRestantes . ' hora' . ($horasRestantes != 1 ? 's' : '');
                        }
                    ?>
                </small>
            <?php elseif ($prestamo['dias_restantes'] >= 0): ?>
                <span class="badge bg-warning-subtle text-warning">
                    <i class="ti ti-alert-triangle me-1"></i>Por Vencer
                </span>
                <small class="d-block text-muted mt-1">
                    <?php 
                        $diasRestantesDecimal = abs($prestamo['dias_restantes']);
                        $dias = floor($diasRestantesDecimal);
                        $horasRestantes = round(($diasRestantesDecimal - $dias) * 24);
                        if ($dias > 0) {
                            echo $dias . ' día' . ($dias != 1 ? 's' : '');
                        } else {
                            echo $horasRestantes . ' hora' . ($horasRestantes != 1 ? 's' : '');
                        }
                    ?>
                </small>
            <?php else: ?>
                <span class="badge bg-danger-subtle text-danger">
                    <i class="ti ti-x-circle me-1"></i>Vencido
                </span>
                <small class="d-block text-muted mt-1">
                    <?php 
                        $diasRestantesDecimal = abs($prestamo['dias_restantes']);
                        $dias = floor($diasRestantesDecimal);
                        $horasRestantes = round(($diasRestantesDecimal - $dias) * 24);
                        if ($dias > 0) {
                            echo $dias . ' día' . ($dias != 1 ? 's' : '');
                        } else {
                            echo $horasRestantes . ' hora' . ($horasRestantes != 1 ? 's' : '');
                        }
                    ?>
                </small>
            <?php endif; ?>
        <?php else: ?>
            <span class="badge bg-danger-subtle text-danger">
                <i class="ti ti-x-circle me-1"></i><?= esc($prestamo['estado']) ?>
            </span>
            <small class="d-block text-muted mt-1">
                <?php 
                    $diasRestantesDecimal = abs($prestamo['dias_restantes']);
                    $dias = floor($diasRestantesDecimal);
                    $horasRestantes = round(($diasRestantesDecimal - $dias) * 24);
                    if ($dias > 0) {
                        echo $dias . ' día' . ($dias != 1 ? 's' : '');
                    } else {
                        echo $horasRestantes . ' hora' . ($horasRestantes != 1 ? 's' : '');
                    }
                ?>
            </small>
        <?php endif; ?>
    </td>
    <td class="px-3 py-3 text-center">
        <span class="badge bg-info-subtle text-info">
            <?= $prestamo['renovaciones'] ?> renovaciones
        </span>
    </td>
    <td class="px-3 py-3 text-center">
        <div class="d-flex gap-1 justify-content-center align-items-center flex-wrap">
            <!-- Ver Detalles -->
            <button type="button" 
                    class="btn btn-sm btn-outline-info" 
                    onclick="verDetallePrestamo(<?= $prestamo['idprestamo'] ?>)" 
                    title="Ver Detalles"
                    data-bs-toggle="tooltip">
                <i class="ti ti-eye"></i>
            </button>
            
            <!-- Renovar -->
            <button type="button" 
                    class="btn btn-sm btn-outline-warning" 
                    onclick="renovarPrestamo(<?= $prestamo['idprestamo'] ?>)" 
                    title="Renovar Préstamo"
                    data-bs-toggle="tooltip">
                <i class="ti ti-refresh"></i>
            </button>
            
            <!-- Procesar Devolución -->
            <button type="button" 
                    class="btn btn-sm btn-outline-success" 
                    onclick="procesarDevolucion(<?= $prestamo['idprestamo'] ?>)" 
                    title="Procesar Devolución"
                    data-bs-toggle="tooltip">
                <i class="ti ti-book-upload"></i>
            </button>
            
            <!-- Cancelar Préstamo -->
            <button type="button" 
                    class="btn btn-sm btn-outline-danger" 
                    onclick="cancelarPrestamo(<?= $prestamo['idprestamo'] ?>)" 
                    title="Cancelar Préstamo"
                    data-bs-toggle="tooltip">
                <i class="ti ti-x"></i>
            </button>
        </div>
    </td>
</tr>
