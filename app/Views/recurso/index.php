<?= $header ?>
<div class="m-5 mt-4 bg-light">
    <div class="container">
	    <h2 class="mb-4 text-center">Resultados de la búsqueda</h2>
        <div class="row ">
            <div class="col-md-2 mb-4 bg-secondary">
                <div class="card-body">
                    <h5 class="card-title">Título del Recurso</h5>
                    <p class="card-text">Descripción breve del recurso.</p>
                </div>
            </div>
            <div class="col-md-10 bg-primary">
                <?php if (isset($recursos) && count($recursos) > 0): ?>
                    <?php foreach ($recursos as $recurso): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <?php if (!empty($recurso['rutaPortada'])): ?>
                                    <img src="<?= esc($recurso['rutaPortada']) ?>" class="card-img-top" alt="Portada del recurso">
                                <?php endif; ?>
                                <div class="card-body">
                                    <h5 class="card-title"><?= esc($recurso['titulo']) ?></h5>
                                    <p class="card-text">
                                        <strong>Autor:</strong> <?= esc(($recurso['apeautor'] ?? '').' '.($recurso['nomautor'] ?? '')) ?><br>
                                        <strong>Categoría:</strong> <?= esc($recurso['categoria'] ?? '') ?><br>
                                        <strong>Año:</strong> <?= esc($recurso['año']) ?><br>
                                        <strong>Estado:</strong> <?= esc($recurso['estado']) ?><br>
                                        <strong>Stock:</strong> <?= esc($recurso['stock']) ?><br>
                                    </p>
                                </div>
                                <div class="card-footer text-center">
                                    <a href="<?= esc($recurso['urlLibro']) ?>" class="btn btn-primary" target="_blank">Ver recurso</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-info">No se encontraron recursos.</div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $footer ?>