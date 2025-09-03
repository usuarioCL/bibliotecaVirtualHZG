<?= $header ?>
<?= $navbar; ?>
<div class="container">
<!-- Hero section con buscador -->
<div class="hero-section mt-5 mb-2  py-5">
    <h1 class="display-4 d-flex justify-content-center align-items-center mb-4">Bienvenido a la Biblioteca Virtual</h1>
    <form action="<?= base_url('recursos/buscarRecursos') ?>" method="get" class="d-flex justify-content-center align-items-center">
        <div class="input-group input-group-lg w-50 ">
            <input 
                type="search" 
                name="query" 
                class="form-control rounded-start-pill border-primary" 
                placeholder="Buscar libros, autores o temas..." 
                aria-label="Buscar" 
                required>
            <button type="submit" class="btn btn-primary rounded-end-pill px-4">Buscar
            </button>
        </div>
    </form>
</div>
<!-- Fin Hero  -->
<!-- Pestañas para alternar entre Niveles y Categorías -->
    <div class="py-5">
        <ul class="nav nav-tabs justify-content-center" id="exploreTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="niveles-tab" data-bs-toggle="tab" data-bs-target="#niveles" type="button" role="tab">
                    Niveles
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="categorias-tab" data-bs-toggle="tab" data-bs-target="#categorias" type="button" role="tab">
                    Categorías
                </button>
            </li>
        </ul>
        
        <div class="tab-content mt-4" id="exploreTabContent">
            <!-- Tab de Niveles -->
            <div class="tab-pane fade show active" id="niveles" role="tabpanel">
                <div class="row">
                    <?php if (!empty($niveles)): ?>
                        <?php foreach ($niveles as $nivel): ?>
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-body text-center d-flex flex-column">
                                        <h5 class="card-title fw-bold"><?= esc($nivel) ?></h5>
                                        <p class="card-text text-muted flex-grow-1">
                                            <?php 
                                            switch($nivel) {
                                                case 'Inicial':
                                                    echo 'Recursos educativos para los más pequeños.';
                                                    break;
                                                case 'Primaria':
                                                    echo 'Material didáctico para estudiantes de primaria.';
                                                    break;
                                                case 'Secundaria':
                                                    echo 'Recursos avanzados para estudiantes de secundaria.';
                                                    break;
                                                default:
                                                    echo 'Recursos educativos especializados.';
                                            }
                                            ?>
                                        </p>
                                        <a href="#" class="btn btn-outline-primary mt-2">
                                            Explorar <?= esc($nivel) ?>
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
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-body text-center d-flex flex-column">
                                        <h6 class="card-title fw-bold flex-grow-1"><?= esc($categoria['categoria']) ?></h6>
                                        <a href="#" class="btn btn-sm btn-outline-primary mt-2">
                                            Ver Recursos
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
<!-- Fin de pestañas para alternar-->
<!-- Sección de Libros Populares -->
    <div class="container mt-1 mb-5">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 text-center mb-4 border-bottom">
                        <h2 class="fw-bold text-primary">Libros Más Populares</h2>
                        <p class="text-muted">Descubre los libros más solicitados de nuestra biblioteca</p>
                    </div>
                </div>
                
                <div class="row">
                    <?php if (!empty($librosPopulares)): ?>
                        <?php foreach ($librosPopulares as $libro): ?>
                            <div class="col-lg-2 col-md-4 col-sm-6 mb-4">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-img-top-container" style="height: 250px; overflow: hidden;">
                                        <?php if (!empty($libro['rutaportada'])): ?>
                                            <img src="<?= base_url('public/' . $libro['rutaportada']) ?>" 
                                                    class="card-img-top h-100 w-100" 
                                                    style="object-fit: cover;" 
                                                    alt="<?= esc($libro['titulo']) ?>">
                                        <?php else: ?>
                                            <div class="bg-light h-100 d-flex align-items-center justify-content-center">
                                                <div class="text-center text-muted">
                                                    <i class="fas fa-book fa-2x mb-2"></i>
                                                    <small>Sin portada</small>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="card-body p-3">
                                        <h6 class="card-title fw-bold mb-2" style="font-size: 0.9rem; line-height: 1.2;">
                                            <?= esc(strlen($libro['titulo']) > 40 ? substr($libro['titulo'], 0, 40) . '...' : $libro['titulo']) ?>
                                        </h6>
                                        <p class="card-text text-muted small mb-2">
                                            <strong>Autor:</strong> <?= esc($libro['nomautor'] ?? 'Sin autor') ?>
                                        </p>
                                        <p class="card-text text-muted small">
                                            <strong>Año:</strong> <?= esc($libro['anio']) ?>
                                        </p>
                                        
                                    </div>
                                    <div class="card-footer bg-transparent border-top-0">
                                        <a href="#" class="btn btn-sm btn-outline-primary">
                                            Ver detalles
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="alert alert-info text-center">
                                <i class="fas fa-info-circle me-2"></i>
                                No hay libros populares disponibles en este momento.
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="row mt-4">
                    <div class="col-12 text-center">
                        <a href="#" class="btn btn-outline-primary btn-lg">
                            <i class="fas fa-search"></i> Explorar Todo el Catálogo
                        </a>
                    </div>
                </div>
            </div>
    </div>
<!-- Fin de sección -->
</div>
<?= $footer ?>
