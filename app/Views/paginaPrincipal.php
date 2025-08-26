<?= $header ?>
<!-- Hero section con buscador -->
<div class="hero-section m-5 bg-light p-4">
    <h1 class="display-4 d-flex justify-content-center align-items-center">Bienvenido a la Biblioteca Virtual</h1>
    <form action="/recurso" method="get" class="d-flex justify-content-center align-items-center mt-4">
        <div class="input-group input-group-lg w-50 ">
            <input 
                type="search" 
                name="q" 
                class="form-control rounded-start-pill border-primary" 
                placeholder="Buscar libros, autores o temas..." 
                aria-label="Buscar" 
                required>
            <button type="submit" class="btn btn-primary rounded-end-pill px-4">Buscar
            </button>
        </div>
    </form>
</div>
<!-- Sección de niveles -->
<div class="m-5 bg-light">
    <div class="row justify-content-center">
        <?php if (!empty($niveles)): ?>
            <?php foreach ($niveles as $nivel): ?>
                <div class="col-md-3">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title"><?= esc($nivel) ?></h5>
                            <p class="card-text">Descripción breve del nivel.</p>
                            <a href="#" class="btn btn-primary">Ver Detalles</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No se encontraron niveles.</p>
        <?php endif; ?>
    </div>
</div>
<!-- Sección de categorías -->
 <div class="m-5 bg-light">
    <div class="row justify-content-center">
        <?php if (!empty($categorias)): ?>
            <?php foreach ($categorias as $categoria): ?>
                <div class="col-md-3">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title"><?= esc($categoria['categoria']) ?></h5>
                            <p class="card-text">Descripción breve de la categoría.</p>
                            <a href="#" class="btn btn-primary">Ver Detalles</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No se encontraron categorías.</p>
        <?php endif; ?>
    </div>
 </div>
<?= $footer ?>
