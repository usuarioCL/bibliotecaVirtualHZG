<?= $header ?>

<div class="container mt-4">
    <h2 class="text-center mb-4">Catálogo de Libros</h2>

    <div class="row">
        <?php foreach ($categorias as $cat): ?>
            <div class="col-md-3 mb-3">
                <button class="btn btn-primary w-100"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#subcat<?= $cat['idcategoria'] ?>"
                        aria-expanded="false"
                        aria-controls="subcat<?= $cat['idcategoria'] ?>">
                    <?= $cat['categoria'] ?>
                </button>

                <div class="collapse mt-2" id="subcat<?= $cat['idcategoria'] ?>">
                    <div class="list-group">
                        <?php if (isset($subcategorias[$cat['idcategoria']])): ?>
                            <?php foreach ($subcategorias[$cat['idcategoria']] as $sub): ?>
                                <a href="<?= base_url('catalogo/subcategoria/'.$sub['idsubcategoria']) ?>"
                                   class="list-group-item list-group-item-action">
                                    <?= $sub['subcategoria'] ?>
                                </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted text-center">Sin subcategorías</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?= $footer ?>
