<!-- Sección de Recursos -->
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
        
        <div class="row g-3">
            <?php if (!empty($librosPopulares)): ?>
                <?php foreach ($librosPopulares as $libro): ?>
                    <?= view('partials/libro_card', [
                        'libro' => $libro,
                        'imagenPrefix' => base_url(),
                        'colClasses' => 'col-xl-2 col-lg-3 col-md-4 col-sm-6'
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
