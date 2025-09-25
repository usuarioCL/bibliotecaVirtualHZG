<!-- Encabezado de la página -->
<div class="d-flex justify-content-between align-items-center">
    <div>
        <h4 class="mb-0">Recursos Digitales</h4>
        <p class="text-muted mb-0">Lista de recursos digitales disponibles en la biblioteca</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= base_url('/recurso-digital/pdf') ?>" class="btn btn-outline-secondary">
            <i class="ti ti-file-type-pdf"></i> Exportar PDF
        </a>
    </div>
</div>

<!-- Tabla de recursos digitales -->
<div class="card mt-1">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Año</th>
                        <th>Editorial</th>
                        <th>Categoría</th>
                        <th>Subcategoría</th>
                        <th>Tipo de Recurso</th>
                        <th>Archivo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($recursos_digitales)): ?>
                        <?php foreach($recursos_digitales as $recurso): ?>
                        <tr>
                            <td><?= $recurso->idrecurso ?></td>
                            <td>
                                <strong><?= esc($recurso->titulo) ?></strong>
                            </td>
                            <td><?= $recurso->anio ?></td>
                            <td><?= esc($recurso->editorial) ?></td>
                            <td>
                                <span class="badge bg-primary"><?= esc($recurso->categoria) ?></span>
                            </td>
                            <td>
                                <span class="badge bg-secondary"><?= esc($recurso->subcategoria) ?></span>
                            </td>
                            <td>
                                <span class="badge bg-info"><?= esc($recurso->tiporecurso) ?></span>
                            </td>
                            <td>
                                <?php if (!empty($recurso->archivo)): ?>
                                    <a href="<?= base_url('uploads/digitales/' . esc($recurso->archivo)) ?>" 
                                       target="_blank" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="ti ti-download"></i> Descargar
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">Sin archivo</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-info" 
                                            onclick="verDetalles(<?= $recurso->idrecurso ?>)">
                                        <i class="ti ti-eye"></i>
                                    </button>
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
                            <td colspan="9" class="text-center text-muted py-4">
                                <i class="ti ti-inbox fs-1 d-block mb-2"></i>
                                No hay recursos digitales registrados
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal para ver detalles -->
<div class="modal fade" id="modalDetalles" tabindex="-1" aria-labelledby="modalDetallesLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetallesLabel">Detalles del Recurso Digital</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="contenidoDetalles">
                <!-- Contenido se carga dinámicamente -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
function verDetalles(id) {
    // Aquí puedes implementar la lógica para cargar los detalles
    document.getElementById('contenidoDetalles').innerHTML = '<p>Cargando detalles del recurso #' + id + '...</p>';
    var modal = new bootstrap.Modal(document.getElementById('modalDetalles'));
    modal.show();
}

function editarRecurso(id) {
    // Aquí puedes implementar la lógica para editar
    alert('Editar recurso #' + id);
}

function eliminarRecurso(id) {
    if (confirm('¿Estás seguro de que deseas eliminar este recurso digital?')) {
        // Aquí puedes implementar la lógica para eliminar
        alert('Eliminar recurso #' + id);
    }
}
</script>
