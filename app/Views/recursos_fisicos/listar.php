<?php if (isset($header)): ?>
<?= $header ?>
<?php endif; ?>

<!-- Estilos compartidos con recursos digitales (paginación, etc.) -->
<link rel="stylesheet" href="<?= base_url('assets/css/recursos-digitales-styles.css') ?>">

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
        <?php
            $itemsPorPagina = max(1, $per_page ?? 8);
            $paginaActual = max(1, $pagina_actual ?? 1);
            $totalRegistros = $total_recursos ?? 0;
            $inicio = $totalRegistros > 0 ? ($paginaActual - 1) * $itemsPorPagina + 1 : 0;
            $fin = $totalRegistros > 0 ? min($totalRegistros, $paginaActual * $itemsPorPagina) : 0;
            $totalPaginas = (int) ceil($totalRegistros / $itemsPorPagina);
        ?>
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 mt-3">
            <div class="text-muted small">
                <?php if ($totalRegistros > 0): ?>
                    Mostrando <?= $inicio ?>-<?= $fin ?> de <?= $totalRegistros ?> recursos físicos
                <?php else: ?>
                    No hay registros para paginar
                <?php endif; ?>
            </div>
            <?php if ($totalPaginas > 1): ?>
                <?php
                    $request = service('request');
                    $basePath = service('uri')->getPath();
                    $baseUrl = base_url($basePath);
                    $queryParams = $request->getGet();
                    unset($queryParams['page']);
                    $buildUrl = static function (string $base, array $params): string {
                        return $params ? $base . '?' . http_build_query($params) : $base;
                    };
                ?>
                <nav aria-label="Paginación de recursos físicos" class="pagination-wrapper recursos-digitales-pagination-container">
                    <ul class="pagination recursos-digitales-pagination mb-0">
                        <?php
                            $prevDisabled = $paginaActual <= 1;
                            $prevParams = $queryParams;
                            if (!$prevDisabled) {
                                $prevParams['page'] = $paginaActual - 1;
                            }
                            $prevUrl = $prevDisabled ? 'javascript:void(0);' : $buildUrl($baseUrl, $prevParams);
                        ?>
                        <li class="page-item <?= $prevDisabled ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= $prevUrl ?>" data-page="<?= max(1, $paginaActual - 1) ?>" aria-label="Anterior">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        <?php for ($page = 1; $page <= $totalPaginas; $page++): ?>
                            <?php
                                $pageParams = $queryParams;
                                $pageParams['page'] = $page;
                                $pageUrl = $buildUrl($baseUrl, $pageParams);
                            ?>
                            <li class="page-item <?= $page === $paginaActual ? 'active' : '' ?>">
                                <a class="page-link" href="<?= $pageUrl ?>" data-page="<?= $page ?>" aria-current="<?= $page === $paginaActual ? 'page' : 'false' ?>">
                                    <?= $page ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                        <?php
                            $nextDisabled = $paginaActual >= $totalPaginas;
                            $nextParams = $queryParams;
                            if (!$nextDisabled) {
                                $nextParams['page'] = $paginaActual + 1;
                            }
                            $nextUrl = $nextDisabled ? 'javascript:void(0);' : $buildUrl($baseUrl, $nextParams);
                        ?>
                        <li class="page-item <?= $nextDisabled ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= $nextUrl ?>" data-page="<?= min($totalPaginas, $paginaActual + 1) ?>" aria-label="Siguiente">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
        </div>
    </div>
</div>

<?php include 'modales_ejemplares.php'; ?>

<script>
const baseUrl = '<?= base_url() ?>';
</script>
<script src="<?= base_url('assets/js/ejemplares.js') ?>"></script>

<script>
(function(){
    if (window.__recursosFisicosPaginationBound) {
        return;
    }
    window.__recursosFisicosPaginationBound = true;

    document.addEventListener('click', function(event) {
        var target = event.target;
        while (target && target !== document && !(target instanceof HTMLAnchorElement)) {
            target = target.parentElement;
        }
        if (!target || !(target instanceof HTMLAnchorElement)) {
            return;
        }
        if (!target.closest('.pagination')) {
            return;
        }

        var contenedor = document.getElementById('contenedor-principal');
        if (!contenedor) {
            return;
        }

        event.preventDefault();
        var url = target.getAttribute('href');
        if (!url) {
            return;
        }

        contenedor.innerHTML = '<div class="text-center py-5">Cargando recursos...</div>';

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(function(response) {
            if (!response.ok) {
                throw new Error('Error al cargar la página');
            }
            return response.text();
        })
        .then(function(html) {
            contenedor.innerHTML = html;
        })
        .catch(function() {
            contenedor.innerHTML = '<div class="text-danger text-center py-5">No se pudo cargar la página solicitada.</div>';
        });
    });
})();
</script>

</div>

<?php if (isset($footer)): ?>
<?= $footer ?>
<?php endif; ?>
