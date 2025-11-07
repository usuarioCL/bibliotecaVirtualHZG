<?php if (!empty($recursos)): ?>
    <?php foreach($recursos as $recurso): ?>
        <?= view('partials/recurso_card', ['recurso' => $recurso]) ?>
    <?php endforeach; ?>
<?php else: ?>
    <?= view('partials/empty_state', [
        'icon' => 'search',
        'title' => 'No se encontraron recursos',
        'message' => 'No hay recursos que coincidan con los criterios de búsqueda.'
    ]) ?>
<?php endif; ?>