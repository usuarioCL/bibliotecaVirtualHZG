<?= $header ?>
<div class="container m-5">
    <h2>Resultados para: <em><?= esc($query) ?></em></h2>

    <?php if (!empty($resultados['categorias'])): ?>
        <h4>Categorías</h4>
        <ul>
            <?php foreach ($resultados['categorias'] as $cat): ?>
                <li><?= esc($cat['categoria']) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <?php if (!empty($resultados['subcategorias'])): ?>
        <h4>Subcategorías</h4>
        <ul>
            <?php foreach ($resultados['subcategorias'] as $sub): ?>
                <li><?= esc($sub['subcategoria']) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <?php if (!empty($resultados['editoriales'])): ?>
        <h4>Editoriales</h4>
        <ul>
            <?php foreach ($resultados['editoriales'] as $ed): ?>
                <li><?= esc($ed['editorial']) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <?php if (!empty($resultados['tiporecursos'])): ?>
        <h4>Tipos de Recurso</h4>
        <ul>
            <?php foreach ($resultados['tiporecursos'] as $tipo): ?>
                <li><?= esc($tipo['tiporecurso']) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <?php if (!empty($resultados['recursos'])): ?>
        <h4>Recursos</h4>
        <ul>
            <?php foreach ($resultados['recursos'] as $recurso): ?>
                <li><?= esc($recurso['titulo']) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <?php if (empty(array_filter($resultados))): ?>
        <div class="alert alert-warning mt-4">No se encontraron resultados.</div>
    <?php endif; ?>
</div>
<?= $footer ?>