<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container-fluid">
    <?= view('Administrador/prestamos/partials/_header_historial') ?>
    
    <?= view('Administrador/prestamos/partials/_filtros_historial') ?>
    
    <!-- Tabla de historial con diseño mejorado -->
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title mb-0 fw-semibold">
                        <i class="ti ti-list text-primary me-2"></i>
                        Historial de Préstamos
                    </h5>
                    <p class="text-muted small mb-0 mt-1">Registro completo de todos los préstamos procesados</p>
                </div>
            </div>
        </div>
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tablaHistorial">
                    <thead class="table-light">
                        <tr class="text-uppercase small fw-semibold text-muted">
                            <th class="border-0 px-3 py-3">Usuario</th>
                            <th class="border-0 px-3 py-3">Recurso</th>
                            <th class="border-0 px-3 py-3">Período del Préstamo</th>
                            <th class="border-0 text-center px-3 py-3">Cantidad</th>
                            <th class="border-0 text-center px-3 py-3">Estado Final</th>
                            <th class="border-0 text-center px-3 py-3">Observaciones</th>
                            <th class="border-0 text-center px-3 py-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($historial)): ?>
                            <?php foreach ($historial as $registro): ?>
                                <?= view('Administrador/prestamos/partials/_fila_historial', ['registro' => $registro]) ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="empty-state">
                                        <div class="empty-state-icon mb-3">
                                            <i class="ti ti-database-off" style="font-size: 3rem; color: #6c757d;"></i>
                                        </div>
                                        <h5 class="empty-state-title text-muted">No se encontraron registros</h5>
                                        <p class="empty-state-description text-muted mb-0">
                                            No hay historial de préstamos con los filtros aplicados
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
        <?php if (!empty($historial)): ?>
        <div class="card-footer bg-light border-top-0">
            <div class="d-flex justify-content-between align-items-center text-muted small">
                <span>
                    <i class="ti ti-info-circle me-1"></i>
                    Mostrando <?= count($historial) ?> de <?= isset($estadisticas['total_registros']) ? $estadisticas['total_registros'] : count($historial) ?> registros
                </span>
                <span>
                    <i class="ti ti-clock me-1"></i>
                    Actualizado: <?= date('d/m/Y H:i') ?>
                </span>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?= view('Administrador/prestamos/partials/_scripts_historial') ?>
