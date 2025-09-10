<div class="row">
    <div class="col-12">
        <div class="card border-0">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-1">Persona Sancionada</h6>
                        <p class="h5 mb-0">
                            <i class="ti ti-user me-2 text-primary"></i>
                            <?= esc($sancion['apellidos']) ?>, <?= esc($sancion['nombres']) ?>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-1">Documento</h6>
                        <p class="h6 mb-0">
                            <i class="ti ti-id me-2 text-secondary"></i>
                            <?= esc($sancion['numerodoc']) ?>
                        </p>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-1">Tipo de Sanción</h6>
                        <span class="badge bg-warning text-dark fs-6 px-3 py-2">
                            <i class="ti ti-ban me-1"></i>
                            <?= esc($sancion['tiposancion']) ?>
                        </span>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-1">Email de Contacto</h6>
                        <p class="mb-0">
                            <?php if (!empty($sancion['email'])): ?>
                                <i class="ti ti-mail me-2 text-info"></i>
                                <a href="mailto:<?= esc($sancion['email']) ?>" class="text-decoration-none">
                                    <?= esc($sancion['email']) ?>
                                </a>
                            <?php else: ?>
                                <span class="text-muted">
                                    <i class="ti ti-mail-off me-2"></i>
                                    Sin email registrado
                                </span>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-12">
                        <h6 class="text-muted mb-2">Detalles de la Sanción</h6>
                        <div class="border rounded p-3 bg-light">
                            <?php if (!empty($sancion['detallesancion'])): ?>
                                <p class="mb-0" style="white-space: pre-wrap;"><?= esc($sancion['detallesancion']) ?></p>
                            <?php else: ?>
                                <p class="text-muted mb-0 fst-italic">
                                    <i class="ti ti-info-circle me-1"></i>
                                    No se han registrado detalles específicos para esta sanción.
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
        <i class="ti ti-x me-1"></i>Cerrar
    </button>
    <a href="<?= base_url('sanciones/editar/' . $sancion['idsancion']) ?>" 
       class="btn btn-warning ajax-link" data-bs-dismiss="modal">
        <i class="ti ti-edit me-1"></i>Editar Sanción
    </a>
</div>
