
<div class="container-fluid">
    <div class="row">
        <!-- Contenido principal -->
        <main class="col-md-10 ms-sm-auto px-md-4 main-content">
            <div class="container-fluid">
                <!-- Título -->
                <div class="mb-3">
                    <h3 class="fw-bold text-primary text-center mb-4 border-bottom pb-2">
                        Lista de Recursos
                    </h3>
                </div>

                <!-- Tabla -->
                <div class="table-responsive shadow-sm rounded">
                    <table class="table table-striped table-hover align-middle">
                        <colgroup>
                            <col width="5%"><!--Id-->
                            <col width="13%"><!--Titulo-->
                            <col width="10%"><!--Año-->
                            <col width="5%"><!--Paginas-->
                            <col width="10%"><!--Encuadernación-->
                            <col width="10%"><!--ISBN-->
                            <col width="7%"><!--Edición-->
                            <col width="10%"><!--Estado-->
                            <col width="10%"><!--Stock-->
                            <col width="15%"><!--Acciones-->
                        </colgroup>
                        <thead class="table-primary text-center">
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
                        <tbody class="text-center">
                            <?php if (!empty($recursos)): ?>
                                <?php foreach($recursos as $recurso): ?>
                                <tr>
                                    <td><?= esc($recurso['idrecurso']) ?></td>
                                    <td><?= esc($recurso['titulo']) ?></td>
                                    <td><?= esc($recurso['anio']) ?></td>
                                    <td><?= esc($recurso['numpaginas']) ?></td>
                                    <td><?= esc($recurso['encuadernacion']) ?></td>
                                    <td><?= esc($recurso['isbn']) ?></td>
                                    <td><?= esc($recurso['numedicion']) ?></td>
                                    <td>
                                        <span class="badge 
                                            <?php if($recurso['estado'] === 'disponible') echo 'bg-success';
                                                  elseif($recurso['estado'] === 'prestado') echo 'bg-warning text-dark';
                                                  else echo 'bg-danger'; ?>">
                                            <?= ucfirst(esc($recurso['estado'])) ?>
                                        </span>
                                    </td>
                                    <td><?= esc($recurso['stock']) ?></td>
                                    <td>
                                        <a href="<?= base_url('recursos/editar/' . $recurso['idrecurso']) ?>"
                                           class="btn btn-sm btn-warning me-1">
                                            Editar
                                        </a>
                                        <a href="<?= base_url('recursos/eliminar/' . $recurso['idrecurso']) ?>"
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('¿Seguro que deseas eliminar este recurso?');">
                                            Eliminar
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="10" class="text-center text-muted">
                                        No hay recursos disponibles
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div class="d-flex justify-content-center mt-4">
                    <?= $pager->links('recursos', 'paginacion') ?>
                </div>

                <!-- Botón registrar -->
                <div class="mt-3 text-center">
                    <a href="<?= base_url("recursos/crear"); ?>" class="btn btn-success">
                        Registrar recurso
                    </a>
                </div>
            </div>
        </main>
    </div>
</div>

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
