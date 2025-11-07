<?php
/**
 * Vista parcial: Tabla de Préstamos
 * Muestra la tabla completa con todos los préstamos activos
 */
?>

<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="card-title mb-0 fw-semibold">
                    <i class="ti ti-list text-primary me-2"></i>
                    Lista de Préstamos Activos
                </h5>
                <p class="text-muted small mb-0 mt-1">Gestiona todos los préstamos activos del sistema</p>
            </div>
        </div>
    </div>
    <div class="card-body p-3">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tablaPrestamos">
                <thead class="table-light">
                    <tr class="text-uppercase small fw-semibold text-muted">
                        <th class="border-0 px-3 py-3">Usuario</th>
                        <th class="border-0 px-3 py-3">Recurso</th>
                        <th class="border-0 px-3 py-3">Fechas</th>
                        <th class="border-0 text-center px-3 py-3">Cantidad</th>
                        <th class="border-0 text-center px-3 py-3">Estado</th>
                        <th class="border-0 text-center px-3 py-3">Renovaciones</th>
                        <th class="border-0 text-center px-3 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($prestamos)): ?>
                        <?php foreach ($prestamos as $prestamo): ?>
                            <?= view('Administrador/prestamos/partials/_fila_prestamo', ['prestamo' => $prestamo]) ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="empty-state">
                                    <div class="empty-state-icon mb-3">
                                        <i class="ti ti-bookmark-off" style="font-size: 3rem; color: #6c757d;"></i>
                                    </div>
                                    <h5 class="empty-state-title text-muted">No hay préstamos activos</h5>
                                    <p class="empty-state-description text-muted mb-0">
                                        Actualmente no existen préstamos activos en el sistema
                                    </p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Footer de la tarjeta con información adicional -->
    <?php if (!empty($prestamos)): ?>
    <div class="card-footer bg-light border-top-0">
        <div class="d-flex justify-content-between align-items-center text-muted small">
            <span>
                <i class="ti ti-info-circle me-1"></i>
                Mostrando <?= count($prestamos) ?> de <?= count($prestamos) ?> préstamos
            </span>
            <span>
                <i class="ti ti-clock me-1"></i>
                Fecha/Hora de comparación: <?= date('d/m/Y H:i:s') ?>
            </span>
        </div>
    </div>
    <?php endif; ?>
</div>
