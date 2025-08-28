<?= $header; ?>

<div class="container mt-4">
    <h3 class="fw-bold text-primary text-center mb-4 border-bottom pb-2">
        Resultados de la búsqueda
    </h3>

    <?php if (!empty($recursos)): ?>
    <div class="table-responsive shadow-sm rounded">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-primary text-center">
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Autor</th>
                    <th>Categoría</th>
                </tr>
            </thead>
            <tbody class="text-center">
                <?php foreach($recursos as $recurso): ?>
                <tr>
                    <td><?= esc($recurso['idrecurso']) ?></td>
                    <td><?= esc($recurso['titulo']) ?></td>
                    <td><?= esc($recurso['nomautor'] ?? 'Sin autor') ?></td>
                    <td><?= esc($recurso['subcategoria'] ?? 'Sin categoría') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
        <div class="alert alert-info text-center">
            No se encontraron recursos que coincidan con la búsqueda.
        </div>
    <?php endif; ?>

    <div class="mt-3 text-center">
        <a href="<?= base_url('/'); ?>" class="btn btn-secondary">
            Volver
        </a>
    </div>
</div>

<?= $footer; ?>