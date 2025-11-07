<div class="card mb-3 shadow-sm border-0 libro-item" 
    data-bs-toggle="modal" 
    data-bs-target="#libroModal"
    data-libro-id="<?= $recurso['idrecurso'] ?>">
    <div class="card-body d-flex align-items-center p-3">
        <div class="libro-icon-container me-3">
            <div class="libro-icon-wrapper">
                <i class="fas fa-book fa-2x text-muted"></i>
            </div>
        </div>
        <div class="flex-grow-1">
            <h5 class="libro-titulo"><?= esc($recurso['titulo']) ?></h5>
            <div class="libro-metadata">
                <i class="fas fa-user"></i><span class="fw-bold">Autores:</span> <?= esc($recurso['nomautor'] ?? 'Sin autor') ?> |
                <i class="fas fa-folder"></i><span class="fw-bold">Categoría:</span> <?= esc($recurso['categoria'] ?? 'Sin categoría') ?> |
                <i class="fas fa-layer-group"></i><span class="fw-bold">Subcategoría:</span> <?= esc($recurso['subcategoria'] ?? 'Sin subcategoría') ?> |
                <i class="fas fa-calendar"></i><span class="fw-bold">Año:</span> <?= esc($recurso['anio']) ?>
            </div>
        </div>
    </div>
</div>