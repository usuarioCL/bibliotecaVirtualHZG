<?= $header ?>
<?= $navbar; ?>

<!-- Estilos institucionales de la Biblioteca Virtual HZG -->
<link rel="stylesheet" href="<?= base_url('assets/css/biblioteca-hzg.css') ?>")>
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
                            <h2 class="text-primary mb-2">Nuestros Recursos</h2>
                            <p class="text-muted">
                                Todos los recursos disponibles en nuestra biblioteca
                                <?php if (!empty($librosPopulares)): ?>
                                    <span class="badge badge-contador-recursos ms-2"><?= count($librosPopulares) ?> recursos</span>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                    
                    <div class="row contenedor-recursos">
                        <?php if (!empty($librosPopulares)): ?>
                            <?php foreach ($librosPopulares as $libro): ?>
                                <?= view('partials/libro_card', [
                                    'libro' => $libro,
                                    'imagenPrefix' => base_url('public/'),
                                    'colClasses' => 'col-lg-2 col-md-4 col-sm-6'
                                ]) ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col-12">
                                <div class="alert alert-info text-center">
                                    <i class="fas fa-info-circle me-2"></i>
                                    No hay recursos disponibles en este momento.
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

<!-- Modal para detalles del libro -->
<div class="modal fade" id="libroModal" tabindex="-1" aria-labelledby="libroModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="libroModalLabel">Detalles del Recurso</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="libroModalBody">
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-2">Cargando detalles del recurso...</p>
                </div>
            </div>
        </div>
    </div>
</div>

</div>
<?= $footer ?>

<script>
// Función para cargar detalles del libro
function cargarDetallesLibro(idRecurso) {
    const modalBody = document.getElementById('libroModalBody');
    
    // Mostrar loading
    modalBody.innerHTML = `
        <div class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="mt-2">Cargando detalles del recurso...</p>
        </div>
    `;
    
    // Cargar detalles via AJAX
    fetch(`<?= base_url('recursos/detalles/') ?>${idRecurso}`)
        .then(response => response.text())
        .then(html => {
            modalBody.innerHTML = html;
        })
        .catch(error => {
            console.error('Error al cargar detalles:', error);
            modalBody.innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Error al cargar los detalles del recurso.
                </div>
            `;
        });
}

// Limpiar modal cuando se cierre
document.getElementById('libroModal').addEventListener('hidden.bs.modal', function() {
    document.getElementById('libroModalBody').innerHTML = '';
});
</script>
