<?php if (!empty($recursos)): ?>
    <div class="mb-4">
        <?php foreach($recursos as $recurso): ?>
        <div class="card mb-3 shadow-sm border-0 libro-item" 
            style="cursor: pointer; transition: all 0.3s ease;" 
            data-bs-toggle="modal" 
            data-bs-target="#libroModal"
            data-libro-id="<?= $recurso['idrecurso'] ?>"
            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 15px rgba(0,0,0,0.1)'"
            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'">
            <div class="card-body d-flex align-items-center p-3">
                <div class="me-3" style="width: 80px;">
                    <div class="bg-light rounded shadow-sm d-flex align-items-center justify-content-center" 
                         style="width: 70px; height: 100px;">
                        <i class="fas fa-book fa-2x text-muted"></i>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <h5 class="mb-2 text-primary fw-bold"><?= esc($recurso['titulo']) ?></h5>
                    <div class="mb-1 text-muted">
                        <i class="fas fa-user me-1"></i><span class="fw-bold">Autores:</span> <?= esc($recurso['nomautor'] ?? 'Sin autor') ?> |
                        <i class="fas fa-folder me-1"></i><span class="fw-bold">Categoría:</span> <?= esc($recurso['categoria'] ?? 'Sin categoría') ?> |
                        <i class="fas fa-layer-group me-1"></i><span class="fw-bold">Subcategoría:</span> <?= esc($recurso['subcategoria'] ?? 'Sin subcategoría') ?> |
                        <i class="fas fa-calendar me-1"></i><span class="fw-bold">Año:</span> <?= esc($recurso['anio']) ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Modal para detalles del libro -->
    <div class="modal fade" id="libroModal" tabindex="-1" aria-labelledby="libroModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="libroModalLabel">
                        <i class="fas fa-book me-2"></i>Detalles del Libro
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalContent">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="text-muted mt-2">Cargando detalles del libro...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <i class="fas fa-search fa-3x text-muted mb-3"></i>
            <h5 class="text-primary mb-2">No se encontraron recursos</h5>
            <p class="text-muted mb-0">No hay recursos que coincidan con los criterios de búsqueda.</p>
        </div>
    </div>
<?php endif; ?>