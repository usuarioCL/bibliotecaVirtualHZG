<?php if (!empty($recursos)): ?>
    <ul class="list-group mb-4">
        <?php foreach($recursos as $recurso): ?>
        <li class="list-group-item d-flex align-items-center libro-item" 
            style="cursor: pointer;" 
            data-bs-toggle="modal" 
            data-bs-target="#libroModal"
            data-libro-id="<?= $recurso['idrecurso'] ?>">
            <div class="me-3" style="width: 80px;">
                <?php if (!empty($recurso['rutaportada'])): ?>
                    <img src="<?= base_url('public/' . $recurso['rutaportada']) ?>" class="img-fluid rounded" alt="Portada" style="max-height: 100px;">
                <?php else: ?>
                    <img src="<?= base_url('public/img/portada_default.png') ?>" class="img-fluid rounded" alt="Sin portada" style="max-height: 100px;">
                <?php endif; ?>
            </div>
            <div class="flex-grow-1">
                <h5 class="mb-1"><?= esc($recurso['titulo']) ?></h5>
                <div class="mb-1">
                    <span class="fw-bold">Autor:</span> <?= esc($recurso['nomautor'] ?? 'Sin autor') ?> |
                    <span class="fw-bold">Categoría:</span> <?= esc($recurso['categoria'] ?? 'Sin categoría') ?> |
                    <span class="fw-bold">Subcategoría:</span> <?= esc($recurso['subcategoria'] ?? 'Sin subcategoría') ?> |
                    <span class="fw-bold">Año:</span> <?= esc($recurso['anio']) ?>
                </div>
            </div>
        </li>
        <?php endforeach; ?>
    </ul>

    <!-- Modal para detalles del libro -->
    <div class="modal fade" id="libroModal" tabindex="-1" aria-labelledby="libroModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="libroModalLabel">Detalles del Libro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalContent">
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="alert alert-info text-center">
        No se encontraron recursos que coincidan con la búsqueda.
    </div>
<?php endif; ?>