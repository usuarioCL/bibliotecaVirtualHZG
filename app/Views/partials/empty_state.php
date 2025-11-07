<div class="card <?= $class ?? 'no-results-card' ?>">
    <div class="card-body">
        <i class="fas fa-<?= $icon ?? 'search' ?> <?= $iconClass ?? 'no-results-icon' ?>"></i>
        <h5 class="<?= $titleClass ?? 'no-results-title' ?>"><?= esc($title ?? 'No se encontraron resultados') ?></h5>
        <p class="<?= $messageClass ?? 'no-results-message' ?>"><?= esc($message ?? 'No hay datos disponibles.') ?></p>
    </div>
</div>