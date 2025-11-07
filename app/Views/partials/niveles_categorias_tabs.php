<!-- Pestañas para alternar entre Niveles y Categorías -->
<div class="py-4">
    <ul class="nav nav-tabs" id="exploreTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="niveles-tab" data-bs-toggle="tab" data-bs-target="#niveles" type="button" role="tab">
                <i class="fas fa-graduation-cap me-2"></i>Niveles Educativos
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="categorias-tab" data-bs-toggle="tab" data-bs-target="#categorias" type="button" role="tab">
                <i class="fas fa-books me-2"></i>Categorías
            </button>
        </li>
    </ul>
    
    <div class="tab-content mt-4" id="exploreTabContent">
        <!-- Tab de Niveles -->
        <div class="tab-pane fade show active" id="niveles" role="tabpanel">
            <div class="row">
                <?php if (!empty($niveles)): ?>
                    <?php foreach ($niveles as $nivel): ?>
                        <div class="col-lg-4 col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center d-flex flex-column p-4">
                                    <?php 
                                    $icon = '';
                                    $descripcion = '';
                                    switch($nivel) {
                                        case 'Inicial':
                                            $icon = 'fas fa-baby';
                                            $descripcion = 'Recursos para los más pequeños';
                                            break;
                                        case 'Primaria':
                                            $icon = 'fas fa-child';
                                            $descripcion = 'Material didáctico primario';
                                            break;
                                        case 'Secundaria':
                                            $icon = 'fas fa-user-graduate';
                                            $descripcion = 'Recursos avanzados';
                                            break;
                                        default:
                                            $icon = 'fas fa-book';
                                            $descripcion = 'Recursos especializados';
                                    }
                                    ?>
                                    <i class="<?= $icon ?> fa-2x mb-3"></i>
                                    <h5 class="card-title"><?= esc($nivel) ?></h5>
                                    <p class="card-text flex-grow-1"><?= $descripcion ?></p>
                                    <a href="<?= base_url('catalogo?nivel=' . urlencode($nivel)) ?>" class="btn btn-outline-primary">
                                        Explorar
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle me-2"></i>
                            No se encontraron niveles educativos disponibles.
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Tab de Categorías -->
        <div class="tab-pane fade" id="categorias" role="tabpanel">
            <div class="row">
                <?php if (!empty($categorias)): ?>
                    <?php foreach ($categorias as $categoria): ?>
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center d-flex flex-column p-3">
                                    <i class="fas fa-bookmark fa-lg mb-2"></i>
                                    <h6 class="card-title flex-grow-1"><?= esc($categoria['categoria']) ?></h6>
                                    <a href="<?= base_url('catalogo?categoria=' . urlencode($categoria['categoria'])) ?>" class="btn btn-sm btn-outline-primary">
                                        Ver
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle me-2"></i>
                            No se encontraron categorías disponibles.
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
