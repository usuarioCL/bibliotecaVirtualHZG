<?php if (isset($header)): ?>
<?= $header ?>
<?php endif; ?>

<div class="container">
    <!-- Encabezado de la página -->
    <div class="d-flex justify-content-between align-items-center">
    <div>
        <h4 class="mb-0">Recursos Físicos</h4>
        <p class="text-muted mb-0">Lista de recursos físicos disponibles en la biblioteca</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= base_url('/recurso-fisico/pdf') ?>" class="btn btn-outline-secondary">
            <i class="ti ti-file-type-pdf"></i> Exportar PDF
        </a>
    </div>
</div>

<!-- Tabla de recursos físicos -->
<div class="card mt-1">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Portada</th>
                        <th>Título</th>
                        <th>Año</th>
                        <th>Páginas</th>
                        <th>ISBN</th>
                        <th>Edición</th>
                        <th>Editorial</th>
                        <th>Categoría</th>
                        <th>Subcategoría</th>
                        <th>Encuadernación</th>
                        <th>Estado</th>
                        <th>Stock</th>
                        <th>Ejemplares</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($recursos_fisicos)): ?>
                        <?php foreach($recursos_fisicos as $recurso): ?>
                        <tr>
                            <td><?= $recurso->idrecurso ?></td>
                            <td>
                                <?php if (!empty($recurso->portada)): ?>
                                    <img src="<?= base_url(esc($recurso->portada)) ?>" 
                                         alt="Portada" 
                                         style="height:60px;width:auto;border-radius:4px;border:1px solid #e5e5e5;object-fit:cover;"
                                         onerror="this.onerror=null;this.src='<?= base_url('img/portada_default.png') ?>';">
                                <?php else: ?>
                                    <img src="<?= base_url('img/portada_default.png') ?>" 
                                         alt="Sin portada" 
                                         style="height:60px;width:auto;border-radius:4px;border:1px solid #e5e5e5;object-fit:cover;">
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><?= esc($recurso->titulo) ?></strong>
                                <?php if (!empty($recurso->nivel)): ?>
                                    <br><small class="text-muted">Nivel: <?= esc($recurso->nivel) ?></small>
                                <?php endif; ?>
                            </td>
                            <td><?= $recurso->anio ?></td>
                            <td><?= $recurso->numpaginas ?></td>
                            <td>
                                <?php if (!empty($recurso->isbn)): ?>
                                    <code><?= esc($recurso->isbn) ?></code>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td><?= esc($recurso->numedicion) ?></td>
                            <td><?= esc($recurso->editorial) ?></td>
                            <td>
                                <span class="badge bg-primary"><?= esc($recurso->categoria) ?></span>
                            </td>
                            <td>
                                <span class="badge bg-secondary"><?= esc($recurso->subcategoria) ?></span>
                            </td>
                            <td>
                                <?php if (!empty($recurso->encuadernacion)): ?>
                                    <span class="badge bg-info"><?= esc($recurso->encuadernacion) ?></span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
                                $estadoClass = '';
                                switch($recurso->estado) {
                                    case 'disponible':
                                        $estadoClass = 'bg-success';
                                        break;
                                    case 'prestado':
                                        $estadoClass = 'bg-warning';
                                        break;
                                    case 'perdido':
                                        $estadoClass = 'bg-danger';
                                        break;
                                    default:
                                        $estadoClass = 'bg-secondary';
                                }
                                ?>
                                <span class="badge <?= $estadoClass ?>"><?= ucfirst($recurso->estado) ?></span>
                            </td>
                            <td>
                                <span class="badge bg-<?= $recurso->stock > 0 ? 'success' : 'danger' ?>">
                                    <?= $recurso->stock ?>
                                </span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-primary" 
                                        onclick="verEjemplares(<?= $recurso->idrecurso ?>, '<?= esc($recurso->titulo) ?>')">
                                    <i class="ti ti-list"></i> Ver Ejemplares
                                </button>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-warning" 
                                            onclick="editarRecurso(<?= $recurso->idrecurso ?>)">
                                        <i class="ti ti-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                            onclick="eliminarRecurso(<?= $recurso->idrecurso ?>)">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="15" class="text-center text-muted py-4">
                                <i class="ti ti-inbox fs-1 d-block mb-2"></i>
                                No hay recursos físicos registrados
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'modales_ejemplares.php'; ?>

<script>
const baseUrl = '<?= base_url() ?>';
</script>
<script src="<?= base_url('assets/js/ejemplares.js') ?>"></script>

</div>

<?php if (isset($footer)): ?>
<?= $footer ?>
<?php endif; ?>
