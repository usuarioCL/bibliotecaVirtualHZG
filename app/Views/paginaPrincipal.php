<?= $header ?>
<?= $navbar; ?>

<style>
:root {
    --cream-bg: #FDF8F0;
    --institutional-red: #B91C1C;
    --institutional-gold: #D4AF37;
    --dark-red: #7F1D1D;
    --soft-white: #FEFEFE;
    --text-dark: #374151;
    --border-light: #E5E7EB;
}

body {
    background-color: var(--cream-bg) !important;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
}

.hero-section {
    position: relative;
    border-radius: 20px;
    box-shadow: 0 4px 20px rgba(185, 28, 28, 0.08);
    overflow: hidden;
    min-height: 450px;
    height: 450px;
}

.hero-carousel {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
}

.hero-carousel .carousel-item {
    height: 100%;
}

.hero-carousel .carousel-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    filter: brightness(0.7) blur(0.5px);
    display: block;
}

.hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(185, 28, 28, 0.4) 0%, rgba(212, 175, 55, 0.3) 50%, rgba(185, 28, 28, 0.5) 100%);
    z-index: 2;
}

.hero-content {
    position: relative;
    z-index: 3;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
    padding: 2rem;
}

.hero-section h1 {
    color: white;
    font-weight: 300;
    letter-spacing: -0.02em;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
}

.hero-section p {
    color: var(--institutional-gold);
    font-weight: 500;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
}

.form-control {
    border: 2px solid var(--border-light);
    padding: 0.875rem 1.25rem;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: var(--institutional-gold);
    box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
}

.btn-danger {
    background: linear-gradient(135deg, var(--institutional-red) 0%, var(--dark-red) 100%);
    border: none;
    padding: 0.875rem 1.5rem;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(185, 28, 28, 0.2);
}

.btn-danger:hover {
    background: linear-gradient(135deg, var(--dark-red) 0%, var(--institutional-red) 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(185, 28, 28, 0.3);
}

.nav-tabs {
    border: none;
    justify-content: center;
}

.nav-tabs .nav-link {
    border: none;
    color: var(--text-dark);
    font-weight: 500;
    padding: 0.75rem 2rem;
    margin: 0 0.5rem;
    border-radius: 25px;
    background: var(--soft-white);
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.nav-tabs .nav-link.active {
    background: linear-gradient(135deg, var(--institutional-gold) 0%, #B8860B 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
}

.nav-tabs .nav-link:hover:not(.active) {
    background: var(--institutional-red);
    color: white;
    transform: translateY(-2px);
}

.card {
    border: none;
    border-radius: 16px;
    background: var(--soft-white);
    transition: all 0.3s ease;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
}

.card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(185, 28, 28, 0.15);
}

.card-title {
    color: var(--institutional-red);
    font-weight: 600;
    margin-bottom: 1rem;
}

.card-text {
    color: var(--text-dark);
    font-size: 0.9rem;
    line-height: 1.6;
}

.btn-outline-primary {
    border: 2px solid var(--institutional-gold);
    color: var(--institutional-gold);
    font-weight: 500;
    padding: 0.5rem 1.5rem;
    transition: all 0.3s ease;
    border-radius: 25px;
}

.btn-outline-primary:hover {
    background: var(--institutional-gold);
    border-color: var(--institutional-gold);
    color: white;
    transform: translateY(-1px);
}

.btn-outline-primary.btn-lg {
    padding: 0.875rem 2.5rem;
    font-size: 1.1rem;
}

.text-primary {
    color: var(--institutional-red) !important;
}

.border-bottom {
    border-color: var(--institutional-gold) !important;
    border-width: 2px !important;
}

.alert-info {
    background: linear-gradient(135deg, #F0F9FF 0%, var(--cream-bg) 100%);
    border: 1px solid var(--institutional-gold);
    color: var(--text-dark);
    border-radius: 12px;
}

.fas {
    color: var(--institutional-gold);
}

.container {
    max-width: 1200px;
}

/* Espaciado minimalista */
.py-5 {
    padding-top: 2.5rem !important;
    padding-bottom: 2.5rem !important;
}

.mb-4 {
    margin-bottom: 1.5rem !important;
}

.mt-4 {
    margin-top: 1.5rem !important;
}
</style>
<div class="container">
    <!-- Hero section con buscador -->
    <div class="hero-section mt-4 mb-4">
        <!-- Carrusel de fondo -->
        <div id="heroCarousel" class="hero-carousel carousel slide" data-bs-ride="carousel" data-bs-interval="4000">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="<?= base_url('img/portada_1.png') ?>" alt="Biblioteca 1">
                </div>
                <div class="carousel-item">
                    <img src="<?= base_url('img/portada_2.png') ?>" alt="Biblioteca 2">
                </div>
            </div>
        </div>
        
        <!-- Overlay con gradiente -->
        <div class="hero-overlay"></div>
        
        <!-- Contenido del hero -->
        <div class="hero-content">
            <h1 class="display-4 mb-3">Biblioteca Virtual</h1>
            <p class="lead mb-4">Horacio Zeballos Gámez</p>
            <form action="<?= base_url('recursos/buscarRecursos') ?>" method="get" class="w-100 d-flex justify-content-center">
                <div class="input-group" style="max-width: 500px;">
                    <input 
                        type="search" 
                        name="query" 
                        class="form-control form-control-lg rounded-start-pill" 
                        placeholder="Buscar recursos educativos..." 
                        aria-label="Buscar" 
                        required>
                    <button type="submit" class="btn btn-danger btn-lg rounded-end-pill">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!-- Fin Hero  -->
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
                                        <a href="#" class="btn btn-outline-primary">
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
                                        <a href="#" class="btn btn-sm btn-outline-primary">
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
    <!-- Fin de pestañas para alternar-->
    <!-- Sección de Libros Populares -->
    <div class="container mt-4 mb-5">
                <div class="card-body px-0">
                    <div class="row">
                        <div class="col-12 text-center mb-4 border-bottom pb-3">
                            <h2 class="text-primary mb-2">Recursos Destacados</h2>
                            <p class="text-muted">Los más solicitados en nuestra biblioteca</p>
                        </div>
                    </div>
                    
                    <div class="row">
                        <?php if (!empty($librosPopulares)): ?>
                            <?php foreach ($librosPopulares as $libro): ?>
                                <?= view('partials/libro_card', [
                                    'libro' => $libro,
                                    'imagenPrefix' => base_url('public/')
                                ]) ?>
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
                            <a href="<?= site_url('catalogo') ?>" class="btn btn-outline-primary btn-lg">
                                <i class="fas fa-search me-2"></i>Explorar Catálogo
                            </a>
                        </div>
                    </div>
                </div>
    </div>
<!-- Fin de sección -->
</div>
<?= $footer ?>
