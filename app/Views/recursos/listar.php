<div class="container">
    <!-- Encabezado de la página -->
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Gestión de Recursos</h4>
            <p class="text-muted mb-0">Recursos bibliográficos del sistema</p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= base_url('/recursos/pdf') ?>" class="btn btn-outline-secondary">
                <i class="ti ti-file-type-pdf"></i> Exportar PDF
            </a>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrearRecurso">
                <i class="ti ti-plus"></i> Nuevo Recurso
            </button>
        </div>
    </div>

    <!-- Tabla de recursos -->
    <div class="card mt-1">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Año</th>
                            <th>Páginas</th>
                            <th>Encuadernación</th>
                            <th>ISBN</th>
                            <th>Edición</th>
                            <th>Estado</th>
                            <th>Stock</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($recursos)): ?>
                            <?php foreach($recursos as $recurso): ?>
                            <tr>
                                <td><?= $recurso['idrecurso'] ?></td>
                                <td>
                                    <div class="fw-bold"><?= esc($recurso['titulo']) ?></div>
                                    <?php if(!empty($recurso['subtitulo'])): ?>
                                        <small class="text-muted"><?= esc($recurso['subtitulo']) ?></small>
                                    <?php endif; ?>
                                </td>
                                <td><?= esc($recurso['anio']) ?></td>
                                <td><?= esc($recurso['numpaginas']) ?></td>
                                <td><?= esc($recurso['encuadernacion']) ?></td>
                                <td>
                                    <?php if(!empty($recurso['isbn'])): ?>
                                        <?= esc($recurso['isbn']) ?>
                                    <?php else: ?>
                                        <span class="text-muted">Sin ISBN</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= esc($recurso['numedicion']) ?></td>
                                <td>
                                    <?php if($recurso['estado'] === 'disponible'): ?>
                                        <span class="badge bg-success">Disponible</span>
                                    <?php elseif($recurso['estado'] === 'prestado'): ?>
                                        <span class="badge bg-warning text-dark">Prestado</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">No disponible</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($recurso['stock'] > 0): ?>
                                        <span class="badge bg-primary"><?= $recurso['stock'] ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">0</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="<?= base_url('recursos/editar/') ?><?= $recurso['idrecurso'] ?>" 
                                           class="btn btn-sm btn-warning" 
                                           title="Editar">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                        <a href="<?= base_url('recursos/eliminar/') ?><?= $recurso['idrecurso'] ?>" 
                                           class="btn btn-sm btn-danger"
                                           title="Eliminar"
                                           onclick="return confirm('¿Seguro que deseas eliminar este recurso?');">
                                            <i class="ti ti-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="10" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="ti ti-inbox fs-1 mb-3"></i>
                                        <h5>No hay recursos registrados</h5>
                                        <p>Comienza agregando tu primer recurso bibliográfico</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Paginación -->
    <?php if(!empty($recursos)): ?>
        <div class="d-flex justify-content-center mt-4">
            <?= $pager->links('recursos', 'paginacion') ?>
        </div>
    <?php endif; ?>
</div>

<!-- Incluir directamente el modal de crear recurso -->
<?= view('recursos/crear') ?>

<script>
$(document).ready(function() {
    // Interceptar los clics en los enlaces de paginación
    $('.pagination .page-link').on('click', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        
        // Hacer la petición AJAX
        $.get(url, function(response) {
            // Actualizar solo el contenido de la tabla y la paginación
            var newContent = $(response).find('.table-responsive').html();
            var newPagination = $(response).find('.pagination').html();
            
            $('.table-responsive').html(newContent);
            $('.pagination').html(newPagination);

            // Actualizar la URL sin recargar la página
            window.history.pushState({}, '', url);
            
            // Volver a bindear los eventos a los nuevos enlaces de paginación
            bindPaginationEvents();
        });
    });
});

function bindPaginationEvents() {
    $('.pagination .page-link').on('click', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        
        $.get(url, function(response) {
            var newContent = $(response).find('.table-responsive').html();
            var newPagination = $(response).find('.pagination').html();
            
            $('.table-responsive').html(newContent);
            $('.pagination').html(newPagination);
            
            window.history.pushState({}, '', url);
            bindPaginationEvents();
        });
    });
}
</script>

<?php
echo $footer;
?>
