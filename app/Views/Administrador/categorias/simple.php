<!-- Vista Simple de Categorías para AJAX -->
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="card-title fw-semibold mb-0">Gestión de Categorías</h5>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrearCategoria">
                    <i class="ti ti-plus me-1"></i> Nueva Categoría
                </button>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <i class="ti ti-alert-circle me-2"></i>
                    Error: <?= esc($error) ?>
                </div>
            <?php endif; ?>

            <!-- Estadísticas de Categorías -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-light-primary shadow-none">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="round-40 text-white d-flex align-items-center justify-content-center rounded-circle bg-primary me-3">
                                    <i class="ti ti-category fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-semibold"><?= esc($estadisticas['total_categorias'] ?? 0) ?></h6>
                                    <span class="fs-2 text-muted">Total Categorías</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-light-info shadow-none">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="round-40 text-white d-flex align-items-center justify-content-center rounded-circle bg-info me-3">
                                    <i class="ti ti-sitemap fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-semibold"><?= esc($estadisticas['total_subcategorias'] ?? 0) ?></h6>
                                    <span class="fs-2 text-muted">Total Subcategorías</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-light-success shadow-none">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="round-40 text-white d-flex align-items-center justify-content-center rounded-circle bg-success me-3">
                                    <i class="ti ti-check fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-semibold"><?= esc($estadisticas['categorias_con_subcategorias'] ?? 0) ?></h6>
                                    <span class="fs-2 text-muted">Con Subcategorías</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-light-warning shadow-none">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="round-40 text-white d-flex align-items-center justify-content-center rounded-circle bg-warning me-3">
                                    <i class="ti ti-alert-circle fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-semibold"><?= esc($estadisticas['categorias_sin_subcategorias'] ?? 0) ?></h6>
                                    <span class="fs-2 text-muted">Sin Subcategorías</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle text-nowrap mb-0" id="tablaCategorias">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Categoría</th>
                            <th scope="col">Subcategorías</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($categorias)): ?>
                            <tr>
                                <td colspan="4" class="text-center">No hay categorías registradas.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($categorias as $categoria): ?>
                                <tr data-id="<?= esc($categoria['idcategoria']) ?>">
                                    <td><?= esc($categoria['idcategoria']) ?></td>
                                    <td>
                                        <span class="fw-semibold categoria-nombre"><?= esc($categoria['categoria']) ?></span>
                                    </td>
                                    <td>
                                        <?php if (!empty($categoria['subcategorias'])): ?>
                                            <ul class="list-unstyled mb-0">
                                                <?php foreach ($categoria['subcategorias'] as $subcategoria): ?>
                                                    <li class="d-flex justify-content-between align-items-center py-1 subcategoria-item" data-id="<?= esc($subcategoria['idsubcategoria']) ?>">
                                                        <span class="subcategoria-nombre"><i class="ti ti-tag me-1 text-muted"></i><?= esc($subcategoria['subcategoria']) ?></span>
                                                        <div>
                                                            <button type="button" class="btn btn-sm btn-outline-info editar-subcategoria-btn"
                                                                    data-id="<?= esc($subcategoria['idsubcategoria']) ?>"
                                                                    data-nombre="<?= esc($subcategoria['subcategoria']) ?>"
                                                                    data-idcategoria="<?= esc($categoria['idcategoria']) ?>"
                                                                    title="Editar Subcategoría">
                                                                <i class="ti ti-edit"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-sm btn-outline-danger eliminar-subcategoria-btn"
                                                                    data-id="<?= esc($subcategoria['idsubcategoria']) ?>"
                                                                    title="Eliminar Subcategoría">
                                                                <i class="ti ti-trash"></i>
                                                            </button>
                                                        </div>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php else: ?>
                                            <span class="text-muted">Sin subcategorías</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-success agregar-subcategoria-btn"
                                                data-id="<?= esc($categoria['idcategoria']) ?>"
                                                data-nombre-categoria="<?= esc($categoria['categoria']) ?>"
                                                title="Agregar Subcategoría">
                                            <i class="ti ti-plus"></i> Subcategoría
                                        </button>
                                        <button type="button" class="btn btn-sm btn-info editar-categoria-btn"
                                                data-id="<?= esc($categoria['idcategoria']) ?>"
                                                data-nombre="<?= esc($categoria['categoria']) ?>"
                                                title="Editar Categoría">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger eliminar-categoria-btn"
                                                data-id="<?= esc($categoria['idcategoria']) ?>"
                                                title="Eliminar Categoría">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Crear Categoría -->
<div class="modal fade" id="modalCrearCategoria" tabindex="-1" aria-labelledby="modalCrearCategoriaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCrearCategoriaLabel">Crear Nueva Categoría</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formCrearCategoria">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombreCategoria" class="form-label">Nombre de la Categoría</label>
                        <input type="text" class="form-control" id="nombreCategoria" name="categoria" required maxlength="100">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Categoría</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Inicializar DataTables si está disponible
    if ($.fn.DataTable) {
        $('#tablaCategorias').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
            },
            "columnDefs": [
                { "orderable": false, "targets": [2, 3] }
            ]
        });
    }

    // Funciones básicas para categorías
    $('#formCrearCategoria').submit(function(e) {
        e.preventDefault();
        const nombreCategoria = $('#nombreCategoria').val().trim();

        if (!nombreCategoria) {
            if (typeof Swal !== 'undefined') {
                Swal.fire('Error', 'El nombre de la categoría no puede estar vacío.', 'error');
            } else {
                alert('El nombre de la categoría no puede estar vacío.');
            }
            return;
        }

        $.ajax({
            url: '<?= base_url('admin/crear-categoria') ?>',
            method: 'POST',
            data: { categoria: nombreCategoria },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire('Éxito', response.message, 'success');
                    } else {
                        alert('Categoría creada exitosamente.');
                    }
                    $('#modalCrearCategoria').modal('hide');
                    $('#formCrearCategoria')[0].reset();
                    // Recargar la página para mostrar los cambios
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire('Error', response.message, 'error');
                    } else {
                        alert('Error: ' + response.message);
                    }
                }
            },
            error: function(xhr, status, error) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire('Error', 'No se pudo conectar con el servidor.', 'error');
                } else {
                    alert('Error de conexión con el servidor.');
                }
            }
        });
    });
});
</script>
