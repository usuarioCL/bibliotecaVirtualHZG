<?= $header ?>
<?= $navbar ?>

<!-- Estilos específicos del catálogo -->
<link rel="stylesheet" href="<?= base_url('assets/css/pages/catalogo.css') ?>">

<div class="container mt-4">
    <!-- Header de la página -->
    <div class="row ">
        <div class="col-12">
            <div class="text-start">
                <h1 class="text-primary mb-2">
                    <i class="fas fa-book-open me-3"></i>Catálogo de Recursos
                </h1>
                <p class="text-muted">Explora nuestra colección de recursos educativos por categorías</p>
            </div>
        </div>
    </div>

    <!-- Filtros de categoría y nivel -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center mb-3">
                <h5 class="text-secondary mb-0 me-3">
                    <i class="fas fa-filter me-2"></i>Filtrar por:
                </h5>
            </div>
            
            <div class="categorias-filtros">
                <button class="btn-categoria active" data-id="0" data-tipo="categoria">
                    <i class="fas fa-th-large me-2"></i>Todos
                </button>
                <?php foreach ($categorias as $cat): ?>
                    <button class="btn-categoria" data-id="<?= $cat['idcategoria'] ?>" data-tipo="categoria">
                        <i class="fas fa-folder me-2"></i><?= $cat['categoria'] ?>
                    </button>
                <?php endforeach; ?>
                
                <!-- Botones de Nivel Educativo -->
                <?php 
                $iconosNivel = [
                    'Inicial' => 'fa-baby',
                    'Primaria' => 'fa-child',
                    'Secundaria' => 'fa-user-graduate'
                ];
                foreach ($niveles as $nivel): 
                ?>
                    <button class="btn-nivel" data-nivel="<?= $nivel ?>" data-tipo="nivel">
                        <i class="fas <?= $iconosNivel[$nivel] ?> me-2"></i><?= $nivel ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Contenedor de subcategorías y libros -->
    <div id="contenido" class="min-vh-50">
        <!-- Loading state -->
        <div id="loading" class="text-center py-5 d-none">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="text-muted mt-3">Cargando recursos...</p>
        </div>

        <!-- Contenido inicial -->
        <div id="contenido-inicial">
            <?php foreach ($subcategorias as $sub): ?>
                <div class="subcategoria-section mb-5">
                    <div class="d-flex align-items-center mb-3">
                        <h3 class="text-primary mb-0 me-3">
                            <i class="fas fa-layer-group me-2"></i><?= $sub['subcategoria'] ?>
                        </h3>
                        <div class="flex-grow-1">
                            <hr class="text-secondary">
                        </div>
                        <span class="badge bg-light text-dark ms-3">
                            <?= count($sub['libros']) ?> recursos
                        </span>
                    </div>
                    
                    <div class="row">
                        <?php if(count($sub['libros']) > 0): ?>
                            <?php foreach($sub['libros'] as $lib): ?>
                                <?= view('partials/libro_card', [
                                    'libro' => $lib,
                                    'colClasses' => 'col-lg-2 col-md-4 col-sm-6',
                                    'imagenPrefix' => base_url()
                                ]) ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col-12">
                                <div class="alert alert-info text-center border-0">
                                    <i class="fas fa-info-circle fa-2x text-muted mb-2"></i>
                                    <h5 class="text-muted">No hay recursos disponibles</h5>
                                    <p class="text-muted mb-0">Esta subcategoría no tiene recursos disponibles en este momento.</p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Configuración para JavaScript -->
<script>
// Configuración del catálogo (se pasa a catalogo.js)
window.catalogoConfig = {
    urls: {
        subcategorias: '<?= site_url('catalogo/subcategorias') ?>',
        nivel: '<?= site_url('catalogo/nivel') ?>',
        detallesRecurso: '<?= base_url('recursos/detalles/') ?>'
    }
};
</script>

<!-- Script del catálogo -->
<script src="<?= base_url('assets/js/pages/catalogo.js') ?>"></script>

<!-- Modal para ver detalles del libro -->
<div class="modal fade" id="libroModal" tabindex="-1" aria-labelledby="libroModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="libroModalLabel">Detalles del Recurso</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="libroModalBody">
                <!-- Contenido se carga dinámicamente -->
            </div>
        </div>
    </div>
</div>

<?= $footer ?>
