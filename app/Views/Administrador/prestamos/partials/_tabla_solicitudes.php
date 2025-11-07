<?php
/**
 * Vista parcial: Tabla de Solicitudes
 * Muestra la tabla completa con todas las solicitudes
 */
helper('solicitudes');
?>

<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <div>
            <h5 class="card-title mb-0 fw-semibold">
                <i class="ti ti-list text-primary me-2"></i>
                Todas las Solicitudes Pendientes
            </h5>
            <p class="text-muted small mb-0 mt-1">
                Solicitudes de préstamos nuevos y renovaciones
            </p>
        </div>
    </div>
    
    <div class="card-body p-3">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tablaSolicitudes">
                <thead class="table-light">
                    <tr class="text-uppercase small fw-semibold text-muted">
                        <th class="border-0 px-3 py-3">Tipo</th>
                        <th class="border-0 px-3 py-3">Usuario</th>
                        <th class="border-0 px-3 py-3">Recurso</th>
                        <th class="border-0 px-3 py-3">Fechas</th>
                        <th class="border-0 text-center px-3 py-3">Extensión</th>
                        <th class="border-0 text-center px-3 py-3">Prioridad</th>
                        <th class="border-0 text-center px-3 py-3">Estado</th>
                        <th class="border-0 text-center px-3 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($solicitudes)): ?>
                        <?php foreach ($solicitudes as $solicitud): ?>
                            <?= view('Administrador/prestamos/partials/_fila_solicitud', ['solicitud' => $solicitud]) ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="empty-state">
                                    <div class="empty-state-icon mb-3">
                                        <i class="ti ti-clock-off" style="font-size: 3rem; color: #6c757d;"></i>
                                    </div>
                                    <h5 class="empty-state-title text-muted">No hay solicitudes pendientes</h5>
                                    <p class="empty-state-description text-muted mb-0">
                                        Actualmente no existen solicitudes pendientes de aprobación
                                    </p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Footer con información -->
    <?php if (!empty($solicitudes)): ?>
    <div class="card-footer bg-light border-top-0">
        <div class="d-flex justify-content-between align-items-center text-muted small">
            <span>
                <i class="ti ti-info-circle me-1"></i>
                Mostrando <?= count($solicitudes) ?> solicitud(es) |
                <span class="badge bg-success-subtle text-success ms-1">
                    <?= $estadisticas['solicitudes_prestamo'] ?? 0 ?> préstamos
                </span>
                <span class="badge bg-warning-subtle text-warning ms-1">
                    <?= $estadisticas['solicitudes_renovacion'] ?? 0 ?> renovaciones
                </span>
            </span>
            <span>
                <i class="ti ti-clock me-1"></i>
                Actualizado: <?= date('d/m/Y H:i') ?>
            </span>
        </div>
    </div>
    <?php endif; ?>
</div>
