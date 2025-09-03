<div class="row">
    <div class="col-md-4">
        <?php if (!empty($recurso['rutaportada'])): ?>
            <img src="<?= base_url('public/' . $recurso['rutaportada']) ?>" class="img-fluid rounded" alt="Portada">
        <?php else: ?>
            <img src="<?= base_url('public/img/portada_default.png') ?>" class="img-fluid rounded" alt="Sin portada">
        <?php endif; ?>
    </div>
    <div class="col-md-8">
        <h4><?= esc($recurso['titulo']) ?></h4>
        <div class="mb-2">
            <strong>Autor:</strong> <?= esc($recurso['nomautor'] ?? 'Sin autor') ?>
        </div>
        <div class="mb-2">
            <strong>Editorial:</strong> <?= esc($recurso['editorial'] ?? 'Sin editorial') ?>
        </div>
        <div class="mb-2">
            <strong>Año:</strong> <?= esc($recurso['anio']) ?>
        </div>
        <div class="mb-2">
            <strong>ISBN:</strong> <?= esc($recurso['isbn'] ?? 'No disponible') ?>
        </div>
        <div class="mb-2">
            <strong>Categoría:</strong> <?= esc($recurso['categoria'] ?? 'Sin categoría') ?>
        </div>
        <div class="mb-2">
            <strong>Subcategoría:</strong> <?= esc($recurso['subcategoria'] ?? 'Sin subcategoría') ?>
        </div>
        <div class="mb-2">
            <strong>Tipo de Recurso:</strong> <?= esc($recurso['tiporecurso'] ?? 'Sin tipo') ?>
        </div>
        <div class="mb-2">
            <strong>Estado:</strong> 
            <span class="badge <?= $recurso['estado'] === 'disponible' ? 'bg-success' : ($recurso['estado'] === 'prestado' ? 'bg-warning' : 'bg-danger') ?>">
                <?= ucfirst(esc($recurso['estado'])) ?>
            </span>
        </div>
        <?php if (!empty($recurso['descripcion'])): ?>
        <div class="mb-2">
            <strong>Descripción:</strong>
            <p><?= esc($recurso['descripcion']) ?></p>
        </div>
        <?php endif; ?>
    </div>
</div>
