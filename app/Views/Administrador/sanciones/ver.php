<?php
/**
 * Vista parcial: Detalle de Sanción
 * Se inyecta dentro del modal "modalDetalleSancion"
 * Variables esperadas:
 * - $sancion: array con claves
 *   idsancion, idtiposancion, idpersona, detallesancion,
 *   apellidos, nombres, numerodoc, email, tiposancion
 */
?>

<div class="row">
    <div class="col-md-6">
        <h6 class="text-primary">Información del Estudiante</h6>
        <p class="mb-1"><strong>Nombre:</strong> <?= esc(($sancion['apellidos'] ?? '') . ' ' . ($sancion['nombres'] ?? '')) ?></p>
        <p class="mb-1"><strong>Documento:</strong> <?= esc($sancion['numerodoc'] ?? '—') ?></p>
        <p class="mb-1"><strong>Email:</strong> <?= esc($sancion['email'] ?? 'No disponible') ?></p>
    </div>
    <div class="col-md-6">
        <h6 class="text-primary">Información de la Sanción</h6>
        <p class="mb-1"><strong>Tipo:</strong> <?= esc($sancion['tiposancion'] ?? 'N/A') ?></p>
        <p class="mb-1"><strong>Estado:</strong> <span class="badge bg-danger">Activa</span></p>
    </div>
</div>

<div class="row mt-3">
    <div class="col-12">
        <h6 class="text-primary">Detalle</h6>
        <div class="alert alert-light mb-0">
            <?= esc($sancion['detallesancion'] ?? 'Sin detalle disponible') ?>
        </div>
    </div>
    
    <div class="col-12 mt-3">
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-success btn-sm" onclick="levantarSancion(<?= (int)($sancion['idsancion'] ?? 0) ?>)">
                <i class="ti ti-check me-1"></i> Levantar sanción
            </button>
        </div>
    </div>
</div>


