<!-- Sección de Recursos Recientes -->
<div class="container mt-4 mb-5">
    <div class="card-body px-0">
        <div class="row">
            <div class="col-12 text-center mb-4 border-bottom pb-3">
                <h2 class="text-primary mb-2">Recursos Agregados Recientemente</h2>
                <p class="text-muted">
                    Descubre los últimos recursos añadidos a nuestra biblioteca
                    <?php if (!empty($recursosRecientes)): ?>
                        <span class="badge badge-contador-recursos ms-2"><?= count($recursosRecientes) ?> recursos nuevos</span>
                    <?php endif; ?>
                </p>
            </div>
        </div>
        
        <?php if (!empty($recursosRecientes)): ?>
            <!-- Carrusel de recursos recientes -->
            <div id="recursosRecientesCarousel" class="carousel slide recursos-carousel" data-bs-ride="carousel">
                <!-- Contenido del carrusel -->
                <div class="carousel-inner">
                    <?php 
                    $chunks = array_chunk($recursosRecientes, 6); // Dividir en grupos de 6
                    $totalSlidesRecientes = count($chunks);
                    foreach ($chunks as $index => $chunk): 
                    ?>
                        <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                            <div class="row g-3">
                                <?php foreach ($chunk as $libro): ?>
                                    <?= view('partials/libro_card', [
                                        'libro' => $libro,
                                        'imagenPrefix' => base_url(),
                                        'colClasses' => 'col-xl-2 col-lg-3 col-md-4 col-sm-6'
                                    ]) ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
            </div>
            
            <!-- Controles del carrusel fuera del contenedor -->
            <?php if ($totalSlidesRecientes > 1): ?>
                <button class="carousel-control-prev recursos-carousel-control" type="button" data-bs-target="#recursosRecientesCarousel" data-bs-slide="prev">
                    <i class="fas fa-chevron-left fa-2x"></i>
                    <span class="visually-hidden">Anterior</span>
                </button>
                <button class="carousel-control-next recursos-carousel-control" type="button" data-bs-target="#recursosRecientesCarousel" data-bs-slide="next">
                    <i class="fas fa-chevron-right fa-2x"></i>
                    <span class="visually-hidden">Siguiente</span>
                </button>
            <?php endif; ?>
        <?php else: ?>
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle me-2"></i>
                        No hay recursos recientes disponibles en este momento.
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Sección de Recursos Populares -->
<div class="container mt-4 mb-5">
    <div class="card-body px-0">
        <div class="row">
            <div class="col-12 text-center mb-4 border-bottom pb-3">
                <h2 class="text-primary mb-2">Recursos Populares</h2>
                <p class="text-muted">
                    Los recursos más destacados de nuestra biblioteca
                    <?php if (!empty($recursosPopulares)): ?>
                        <span class="badge badge-contador-recursos ms-2"><?= count($recursosPopulares) ?> recursos</span>
                    <?php endif; ?>
                </p>
            </div>
        </div>
        
        <?php if (!empty($recursosPopulares)): ?>
            <!-- Carrusel de recursos populares -->
            <div id="recursosPopularesCarousel" class="carousel slide recursos-carousel" data-bs-ride="carousel">
                <!-- Contenido del carrusel -->
                <div class="carousel-inner">
                    <?php 
                    $chunksPopulares = array_chunk($recursosPopulares, 6); // Dividir en grupos de 6
                    $totalSlidesPopulares = count($chunksPopulares);
                    foreach ($chunksPopulares as $index => $chunk): 
                    ?>
                        <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                            <div class="row g-3">
                                <?php foreach ($chunk as $libro): ?>
                                    <?= view('partials/libro_card', [
                                        'libro' => $libro,
                                        'imagenPrefix' => base_url(),
                                        'colClasses' => 'col-xl-2 col-lg-3 col-md-4 col-sm-6'
                                    ]) ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
            </div>
            
            <!-- Controles del carrusel fuera del contenedor -->
            <?php if ($totalSlidesPopulares > 1): ?>
                <button class="carousel-control-prev recursos-carousel-control" type="button" data-bs-target="#recursosPopularesCarousel" data-bs-slide="prev">
                    <i class="fas fa-chevron-left fa-2x"></i>
                    <span class="visually-hidden">Anterior</span>
                </button>
                <button class="carousel-control-next recursos-carousel-control" type="button" data-bs-target="#recursosPopularesCarousel" data-bs-slide="next">
                    <i class="fas fa-chevron-right fa-2x"></i>
                    <span class="visually-hidden">Siguiente</span>
                </button>
            <?php endif; ?>
        <?php else: ?>
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle me-2"></i>
                        No hay recursos populares disponibles en este momento.
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="row mt-4">
            <div class="col-12 text-center">
                <a href="<?= site_url('catalogo') ?>" class="btn btn-outline-primary btn-lg">
                    <i class="fas fa-search me-2"></i>Explorar Catálogo Completo
                </a>
            </div>
        </div>
    </div>
</div>

<style>
/* Estilos para el carrusel de recursos */
.recursos-carousel {
    position: relative;
    padding: 0 60px; /* Espacio para los botones laterales */
}

.recursos-carousel-control {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 50px;
    height: 50px;
    background-color: transparent;
    border: none;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #c1272d;
    transition: all 0.3s ease;
    z-index: 10;
    opacity: 0.8;
}

.recursos-carousel-control:hover {
    background-color: rgba(193, 39, 45, 0.1);
    color: #c1272d;
    opacity: 1;
    transform: translateY(-50%) scale(1.1);
}

.recursos-carousel-control.carousel-control-prev {
    left: 0;
}

.recursos-carousel-control.carousel-control-next {
    right: 0;
}

.recursos-carousel-control i {
    margin: 0;
}

.recursos-carousel .carousel-item {
    transition: transform 0.6s ease-in-out;
}

/* Responsive */
@media (max-width: 768px) {
    .recursos-carousel {
        padding: 0 50px;
    }
    
    .recursos-carousel-control {
        width: 40px;
        height: 40px;
    }
    
    .recursos-carousel-control i {
        font-size: 1.5rem;
    }
}
</style>
