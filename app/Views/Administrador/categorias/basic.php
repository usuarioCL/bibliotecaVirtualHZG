<!-- Vista Básica de Categorías -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
.table-responsive {
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

#tablaCategorias {
    margin-bottom: 0;
}

#tablaCategorias th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
    color: #495057;
}

#tablaCategorias td {
    vertical-align: middle;
    border-bottom: 1px solid #dee2e6;
}

.subcategorias-container {
    max-height: 200px;
    overflow-y: auto;
}

.subcategorias-container::-webkit-scrollbar {
    width: 4px;
}

.subcategorias-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 2px;
}

.subcategorias-container::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 2px;
}

.subcategorias-container::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

.btn-group-vertical .btn {
    margin-bottom: 2px;
}

.btn-group-vertical .btn:last-child {
    margin-bottom: 0;
}

.subcategoria-item {
    border-bottom: 1px solid #f0f0f0;
    padding: 8px 0;
}

.subcategoria-item:last-child {
    border-bottom: none;
}

/* Alineación específica para columnas */
#tablaCategorias th:nth-child(3), /* Header Subcategorías */
#tablaCategorias th:nth-child(4), /* Header Agregar */
#tablaCategorias th:nth-child(5) { /* Header Acciones */
    text-align: center !important;
}

#tablaCategorias td:nth-child(3), /* Columna Subcategorías */
#tablaCategorias td:nth-child(4), /* Columna Agregar */
#tablaCategorias td:nth-child(5) { /* Columna Acciones */
    text-align: center !important;
}

/* Estilos personalizados para botón de subcategorías */
.btn-subcategorias {
    background-color: rgba(13, 202, 240, 0.1) !important; /* Celeste transparente */
    border-color: rgba(13, 202, 240, 0.3) !important;
    color: #000000 !important; /* Texto negro */
    transition: all 0.3s ease !important;
}

.btn-subcategorias i {
    color: #0dcaf0 !important; /* Ícono celeste */
    display: inline-block !important;
    visibility: visible !important;
    opacity: 1 !important;
    font-size: 14px !important;
    margin-right: 4px !important;
}

.btn-subcategorias span {
    color: #000000 !important; /* Números negros */
}

.btn-subcategorias:hover {
    background-color: #0dcaf0 !important; /* Celeste sólido al hover */
    border-color: #0dcaf0 !important;
    color: white !important; /* Texto blanco al hover */
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(13, 202, 240, 0.3);
}

.btn-subcategorias:hover i {
    color: white !important; /* Ícono blanco al hover */
}

.btn-subcategorias:hover span {
    color: white !important; /* Números blancos al hover */
}

.btn-subcategorias:active {
    transform: translateY(0);
    box-shadow: 0 2px 4px rgba(13, 202, 240, 0.3);
}

/* Estilos para tarjetas de estadísticas */
.stats-card {
    border: none !important;
    border-radius: 12px !important;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08) !important;
    transition: all 0.3s ease !important;
    overflow: hidden !important;
    position: relative !important;
}

.stats-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
    opacity: 0;
    transition: opacity 0.3s ease;
}

.stats-card:hover {
    transform: translateY(-5px) !important;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
}

.stats-card:hover::before {
    opacity: 1;
}

.stats-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: white;
    transition: all 0.3s ease;
}

.stats-primary .stats-icon {
    background: linear-gradient(135deg, #0d6efd, #6610f2);
}

.stats-info .stats-icon {
    background: linear-gradient(135deg, #0dcaf0, #20c997);
}

.stats-success .stats-icon {
    background: linear-gradient(135deg, #198754, #20c997);
}

.stats-warning .stats-icon {
    background: linear-gradient(135deg, #ffc107, #fd7e14);
}

.stats-card:hover .stats-icon {
    transform: scale(1.1) rotate(5deg);
}

.stats-number {
    font-size: 1.5rem !important;
    font-weight: 700 !important;
    background: linear-gradient(135deg, #2c3e50, #34495e);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 0.25rem !important;
}

.stats-label {
    font-size: 0.875rem !important;
    font-weight: 500 !important;
    color: #6c757d !important;
    margin-bottom: 0 !important;
}

/* Efectos adicionales para cada tipo de tarjeta */
.stats-primary {
    background: linear-gradient(135deg, rgba(13, 110, 253, 0.05), rgba(102, 16, 242, 0.05));
}

.stats-info {
    background: linear-gradient(135deg, rgba(13, 202, 240, 0.05), rgba(32, 201, 151, 0.05));
}

.stats-success {
    background: linear-gradient(135deg, rgba(25, 135, 84, 0.05), rgba(32, 201, 151, 0.05));
}

.stats-warning {
    background: linear-gradient(135deg, rgba(255, 193, 7, 0.05), rgba(253, 126, 20, 0.05));
}

@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    
    .subcategoria-nombre {
        max-width: 150px !important;
    }
}
</style>
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="card-title fw-semibold mb-0">Gestión de Categorías</h5>
                <button type="button" class="btn btn-primary" onclick="abrirModalCrear()">
                    <i class="ti ti-plus me-1"></i> Nueva Categoría
                </button>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <i class="ti ti-alert-circle me-2"></i>
                    Error: <?= esc($error) ?>
                </div>
            <?php endif; ?>

            <!-- Estadísticas -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card stats-card stats-primary">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="stats-icon">
                                    <i class="ti ti-category"></i>
                                </div>
                                <div class="ms-2">
                                    <h4 class="mb-0 fw-bold stats-number"><?= esc($estadisticas['total_categorias'] ?? 0) ?></h4>
                                    <p class="mb-0 text-muted stats-label">Total Categorías</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stats-card stats-info">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="stats-icon">
                                    <i class="ti ti-sitemap"></i>
                                </div>
                                <div class="ms-2">
                                    <h4 class="mb-0 fw-bold stats-number"><?= esc($estadisticas['total_subcategorias'] ?? 0) ?></h4>
                                    <p class="mb-0 text-muted stats-label">Total Subcategorías</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stats-card stats-success">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="stats-icon">
                                    <i class="ti ti-check"></i>
                                </div>
                                <div class="ms-2">
                                    <h4 class="mb-0 fw-bold stats-number"><?= esc($estadisticas['categorias_con_subcategorias'] ?? 0) ?></h4>
                                    <p class="mb-0 text-muted stats-label">Con Subcategorías</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stats-card stats-warning">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="stats-icon">
                                    <i class="ti ti-alert-circle"></i>
                                </div>
                                <div class="ms-2">
                                    <h4 class="mb-0 fw-bold stats-number"><?= esc($estadisticas['categorias_sin_subcategorias'] ?? 0) ?></h4>
                                    <p class="mb-0 text-muted stats-label">Sin Subcategorías</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de Categorías -->
            <div class="table-responsive">
                <table class="table table-hover" id="tablaCategorias">
                    <thead>
                        <tr>
                            <th style="width: 60px;">ID</th>
                            <th style="width: 200px;">Categoría</th>
                            <th style="width: 120px;">Subcategorías</th>
                            <th style="width: 120px;">Agregar</th>
                            <th style="width: 150px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyCategorias">
                        <?php if (empty($categorias)): ?>
                            <tr>
                                <td colspan="5" class="text-center">No hay categorías registradas.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($categorias as $categoria): ?>
                                <tr data-id="<?= esc($categoria['idcategoria']) ?>">
                                    <td><?= esc($categoria['idcategoria']) ?></td>
                                    <td>
                                        <strong class="categoria-nombre"><?= esc($categoria['categoria']) ?></strong>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-outline-info btn-sm btn-subcategorias" 
                                                onclick="verSubcategorias(<?= esc($categoria['idcategoria']) ?>, '<?= esc($categoria['categoria']) ?>', <?= count($categoria['subcategorias'] ?? []) ?>)"
                                                title="Ver subcategorías">
                                            <i class="ti ti-list me-1"></i>
                                            <span><?= count($categoria['subcategorias'] ?? []) ?></span>
                                        </button>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-success btn-sm" 
                                                onclick="agregarSubcategoria(<?= esc($categoria['idcategoria']) ?>, '<?= esc($categoria['categoria']) ?>')"
                                                title="Agregar subcategoría">
                                            <i class="ti ti-plus"></i>
                                        </button>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex gap-1 justify-content-center">
                                            <button class="btn btn-info btn-sm" 
                                                    onclick="editarCategoria(<?= esc($categoria['idcategoria']) ?>, '<?= esc($categoria['categoria']) ?>')"
                                                    title="Editar categoría">
                                                <i class="ti ti-edit"></i>
                                            </button>
                                            <button class="btn btn-danger btn-sm" 
                                                    onclick="eliminarCategoria(<?= esc($categoria['idcategoria']) ?>)"
                                                    title="Eliminar categoría">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </div>
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

<!-- Modal para Categorías -->
<div class="modal fade" id="modalCategoria" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitulo">Crear Categoría</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formCategoria">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombreInput" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombreInput" name="categoria" required>
                        <input type="hidden" id="categoriaId" name="idcategoria">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Subcategorías -->
<div class="modal fade" id="modalSubcategorias" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalSubcategoriasTitulo">Subcategorías</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <p class="mb-0 text-muted" id="modalSubcategoriasDescripcion">Gestiona las subcategorías de esta categoría</p>
                </div>
                
                <div id="listaSubcategorias" class="list-group">
                    <!-- Las subcategorías se cargarán aquí dinámicamente -->
                </div>
                
                <div id="sinSubcategorias" class="text-center py-4" style="display: none;">
                    <i class="ti ti-inbox fs-1 text-muted mb-3"></i>
                    <p class="text-muted">No hay subcategorías registradas</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
// Funciones básicas sin jQuery
function abrirModalCrear() {
    document.getElementById('modalTitulo').textContent = 'Crear Nueva Categoría';
    document.getElementById('formCategoria').reset();
    document.getElementById('categoriaId').value = '';
    document.getElementById('nombreInput').value = '';
    
    const modal = new bootstrap.Modal(document.getElementById('modalCategoria'));
    modal.show();
}

function editarCategoria(id, nombre) {
    document.getElementById('modalTitulo').textContent = 'Editar Categoría';
    document.getElementById('nombreInput').value = nombre;
    document.getElementById('categoriaId').value = id;
    
    // Asegurar que el input tenga el valor correcto
    setTimeout(() => {
        document.getElementById('nombreInput').value = nombre;
    }, 100);
    
    const modal = new bootstrap.Modal(document.getElementById('modalCategoria'));
    modal.show();
}

// Variable global para almacenar la categoría actual
let categoriaActual = null;

function verSubcategorias(idcategoria, nombreCategoria, totalSubcategorias) {
    categoriaActual = { id: idcategoria, nombre: nombreCategoria };
    
    // Actualizar el título del modal
    document.getElementById('modalSubcategoriasTitulo').textContent = `Subcategorías de "${nombreCategoria}"`;
    document.getElementById('modalSubcategoriasDescripcion').textContent = `Gestiona las ${totalSubcategorias} subcategorías de "${nombreCategoria}"`;
    
    // Cargar las subcategorías
    cargarSubcategoriasEnModal(idcategoria);
    
    // Mostrar el modal
    const modal = new bootstrap.Modal(document.getElementById('modalSubcategorias'));
    modal.show();
}

function cargarSubcategoriasEnModal(idcategoria) {
    // Obtener las subcategorías desde los datos actuales de la página
    const fila = document.querySelector(`tr[data-id="${idcategoria}"]`);
    const listaSubcategorias = document.getElementById('listaSubcategorias');
    const sinSubcategorias = document.getElementById('sinSubcategorias');
    
    // Limpiar la lista
    listaSubcategorias.innerHTML = '';
    
    // Buscar las subcategorías en los datos de la página
    // Por ahora, vamos a obtenerlas del servidor para tener datos actualizados
    fetch(`<?= base_url('admin/obtener-subcategorias/') ?>${idcategoria}`)
    .then(response => response.json())
    .then(data => {
        if (data.success && data.subcategorias.length > 0) {
            sinSubcategorias.style.display = 'none';
            listaSubcategorias.innerHTML = '';
            
            data.subcategorias.forEach(subcategoria => {
                const item = document.createElement('div');
                item.className = 'list-group-item d-flex justify-content-between align-items-center';
                item.innerHTML = `
                    <div class="d-flex align-items-center">
                        <i class="ti ti-tag me-2 text-primary"></i>
                        <span class="subcategoria-nombre">${subcategoria.subcategoria}</span>
                    </div>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-info" 
                                onclick="editarSubcategoriaDesdeModal(${subcategoria.idsubcategoria}, '${subcategoria.subcategoria}', ${idcategoria})"
                                title="Editar">
                            <i class="ti ti-edit"></i>
                        </button>
                        <button class="btn btn-outline-danger" 
                                onclick="eliminarSubcategoriaDesdeModal(${subcategoria.idsubcategoria}, '${subcategoria.subcategoria}')"
                                title="Eliminar">
                            <i class="ti ti-trash"></i>
                        </button>
                    </div>
                `;
                listaSubcategorias.appendChild(item);
            });
        } else {
            listaSubcategorias.innerHTML = '';
            sinSubcategorias.style.display = 'block';
        }
    })
    .catch(error => {
        console.log('Error al cargar subcategorías:', error);
        listaSubcategorias.innerHTML = '';
        sinSubcategorias.style.display = 'block';
    });
}

function agregarSubcategoria(idcategoria, nombreCategoria) {
    Swal.fire({
        title: 'Agregar Subcategoría',
        html: `
            <div class="text-start">
                <p class="mb-3">Categoría: <strong>${nombreCategoria}</strong></p>
                <input type="text" id="nombreSubcategoria" class="form-control" placeholder="Nombre de la subcategoría" required>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Guardar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#198754',
        cancelButtonColor: '#6c757d',
        preConfirm: () => {
            const nombre = document.getElementById('nombreSubcategoria').value.trim();
            if (!nombre) {
                Swal.showValidationMessage('El nombre de la subcategoría es requerido');
                return false;
            }
            return { nombre: nombre, idcategoria: idcategoria };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            crearSubcategoria(result.value.idcategoria, result.value.nombre);
        }
    });
}

function eliminarCategoria(id) {
    Swal.fire({
        title: '¿Eliminar categoría?',
        text: 'Esta acción no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            eliminarCategoriaConfirmado(id);
        }
    });
}

function eliminarCategoriaConfirmado(id) {
    fetch(`<?= base_url('admin/eliminar-categoria/') ?>${id}`, {
        method: 'DELETE'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire('Eliminado', data.message, 'success');
            // Remover la fila de la tabla en lugar de recargar
            const fila = document.querySelector(`tr[data-id="${id}"]`);
            if (fila) {
                fila.remove();
                actualizarEstadisticas();
            }
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    })
    .catch(error => {
        Swal.fire('Error', 'Error de conexión', 'error');
    });
}

function crearSubcategoria(idcategoria, nombre) {
    const formData = new FormData();
    formData.append('subcategoria', nombre);
    formData.append('idcategoria', idcategoria);
    
    fetch('<?= base_url('admin/crear-subcategoria') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire('Éxito', data.message, 'success');
            // Actualizar el contador en la tabla
            actualizarContadorSubcategorias(idcategoria);
            // Si el modal está abierto, recargar la lista
            if (categoriaActual && categoriaActual.id == idcategoria) {
                cargarSubcategoriasEnModal(idcategoria);
            }
            actualizarEstadisticas();
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    })
    .catch(error => {
        Swal.fire('Error', 'Error de conexión', 'error');
    });
}

function editarSubcategoriaDesdeModal(idsubcategoria, nombreActual, idcategoria) {
    Swal.fire({
        title: 'Editar Subcategoría',
        html: `
            <div class="text-start">
                <input type="text" id="nombreSubcategoriaEdit" class="form-control" value="${nombreActual}" required>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Actualizar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#0d6efd',
        cancelButtonColor: '#6c757d',
        preConfirm: () => {
            const nombre = document.getElementById('nombreSubcategoriaEdit').value.trim();
            if (!nombre) {
                Swal.showValidationMessage('El nombre de la subcategoría es requerido');
                return false;
            }
            return { nombre: nombre, idsubcategoria: idsubcategoria, idcategoria: idcategoria };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            actualizarSubcategoriaDesdeModal(result.value.idsubcategoria, result.value.nombre, result.value.idcategoria);
        }
    });
}

function actualizarSubcategoriaDesdeModal(idsubcategoria, nombre, idcategoria) {
    const formData = new FormData();
    formData.append('subcategoria', nombre);
    formData.append('idcategoria', idcategoria);
    
    fetch(`<?= base_url('admin/editar-subcategoria/') ?>${idsubcategoria}`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire('Éxito', data.message, 'success');
            // Recargar la lista en el modal
            cargarSubcategoriasEnModal(idcategoria);
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    })
    .catch(error => {
        Swal.fire('Error', 'Error de conexión', 'error');
    });
}

function eliminarSubcategoriaDesdeModal(idsubcategoria, nombre) {
    Swal.fire({
        title: '¿Eliminar subcategoría?',
        html: `¿Estás seguro de eliminar la subcategoría <strong>"${nombre}"</strong>?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            eliminarSubcategoriaConfirmadoDesdeModal(idsubcategoria, categoriaActual.id);
        }
    });
}

function eliminarSubcategoriaConfirmadoDesdeModal(idsubcategoria, idcategoria) {
    fetch(`<?= base_url('admin/eliminar-subcategoria/') ?>${idsubcategoria}`, {
        method: 'DELETE'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire('Eliminado', data.message, 'success');
            // Actualizar el contador en la tabla
            actualizarContadorSubcategorias(idcategoria);
            // Recargar la lista en el modal
            cargarSubcategoriasEnModal(idcategoria);
            actualizarEstadisticas();
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    })
    .catch(error => {
        Swal.fire('Error', 'Error de conexión', 'error');
    });
}

function actualizarContadorSubcategorias(idcategoria) {
    // Actualizar el contador de subcategorías
    fetch(`<?= base_url('admin/obtener-subcategorias/') ?>${idcategoria}`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const fila = document.querySelector(`tr[data-id="${idcategoria}"]`);
            if (fila) {
                const span = fila.querySelector('.btn-subcategorias span');
                if (span) {
                    span.textContent = data.subcategorias.length;
                }
            }
        }
    })
    .catch(error => {
        console.log('Error al actualizar contador:', error);
    });
}

function editarSubcategoria(idsubcategoria, nombreActual, idcategoria) {
    Swal.fire({
        title: 'Editar Subcategoría',
        html: `
            <div class="text-start">
                <input type="text" id="nombreSubcategoriaEdit" class="form-control" value="${nombreActual}" required>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Actualizar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#0d6efd',
        cancelButtonColor: '#6c757d',
        preConfirm: () => {
            const nombre = document.getElementById('nombreSubcategoriaEdit').value.trim();
            if (!nombre) {
                Swal.showValidationMessage('El nombre de la subcategoría es requerido');
                return false;
            }
            return { nombre: nombre, idsubcategoria: idsubcategoria, idcategoria: idcategoria };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            actualizarSubcategoria(result.value.idsubcategoria, result.value.nombre, result.value.idcategoria);
        }
    });
}

function actualizarSubcategoria(idsubcategoria, nombre, idcategoria) {
    const formData = new FormData();
    formData.append('subcategoria', nombre);
    formData.append('idcategoria', idcategoria);
    
    fetch(`<?= base_url('admin/editar-subcategoria/') ?>${idsubcategoria}`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire('Éxito', data.message, 'success');
            // Actualizar el nombre en la tabla
            const subcategoriaElement = document.querySelector(`li.subcategoria-item[data-id="${idsubcategoria}"] .subcategoria-nombre`);
            if (subcategoriaElement) {
                subcategoriaElement.innerHTML = `<i class="ti ti-tag me-1 text-muted"></i>${nombre}`;
            }
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    })
    .catch(error => {
        Swal.fire('Error', 'Error de conexión', 'error');
    });
}

function eliminarSubcategoria(idsubcategoria, nombre) {
    Swal.fire({
        title: '¿Eliminar subcategoría?',
        html: `¿Estás seguro de eliminar la subcategoría <strong>"${nombre}"</strong>?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            eliminarSubcategoriaConfirmado(idsubcategoria);
        }
    });
}

function eliminarSubcategoriaConfirmado(idsubcategoria) {
    fetch(`<?= base_url('admin/eliminar-subcategoria/') ?>${idsubcategoria}`, {
        method: 'DELETE'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire('Eliminado', data.message, 'success');
            // Remover la subcategoría de la tabla
            const subcategoriaElement = document.querySelector(`li.subcategoria-item[data-id="${idsubcategoria}"]`);
            if (subcategoriaElement) {
                subcategoriaElement.remove();
                actualizarEstadisticas();
            }
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    })
    .catch(error => {
        Swal.fire('Error', 'Error de conexión', 'error');
    });
}

// Manejar envío del formulario
document.getElementById('formCategoria').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Capturar los datos ANTES de enviar el formulario
    const categoriaId = document.getElementById('categoriaId').value;
    const nombreInput = document.getElementById('nombreInput').value;
    const isEdit = categoriaId ? true : false;
    
    const formData = new FormData(this);
    const url = categoriaId ? 
        '<?= base_url('admin/editar-categoria/') ?>' + categoriaId :
        '<?= base_url('admin/crear-categoria') ?>';
    
    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Primero actualizar la tabla ANTES de cerrar el modal
            if (isEdit) {
                // Es una edición - actualizar el nombre en la tabla usando los datos capturados
                const fila = document.querySelector(`tr[data-id="${categoriaId}"]`);
                if (fila) {
                    const nombreElement = fila.querySelector('.categoria-nombre');
                    if (nombreElement) {
                        nombreElement.textContent = nombreInput;
                    }
                    // Actualizar también el atributo data-nombre del botón de editar
                    const editButton = fila.querySelector('button[onclick*="editarCategoria"]');
                    if (editButton) {
                        editButton.setAttribute('data-nombre', nombreInput);
                        editButton.setAttribute('onclick', `editarCategoria(${categoriaId}, '${nombreInput}')`);
                    }
                }
            } else {
                // Es una creación nueva
                recargarTablaCompleta();
            }
            
            // Mostrar mensaje de éxito
            Swal.fire('Éxito', data.message, 'success');
            
            // Cerrar el modal DESPUÉS de actualizar la tabla
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalCategoria'));
            if (modal) {
                modal.hide();
            }
            
            // Limpiar el formulario DESPUÉS de cerrar el modal
            setTimeout(() => {
                document.getElementById('formCategoria').reset();
            }, 300);
            
            actualizarEstadisticas();
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    })
    .catch(error => {
        Swal.fire('Error', 'Error de conexión', 'error');
    });
});

// Funciones auxiliares para actualizar la tabla sin recargar
function agregarSubcategoriaATabla(idcategoria, subcategoria) {
    const fila = document.querySelector(`tr[data-id="${idcategoria}"]`);
    if (!fila) return;
    
    const subcategoriasContainer = fila.querySelector('.subcategorias-container');
    let ul = subcategoriasContainer.querySelector('ul');
    
    if (!ul) {
        // Si no hay subcategorías, crear el UL
        subcategoriasContainer.innerHTML = `
            <ul class="list-unstyled mb-0">
            </ul>
        `;
        ul = subcategoriasContainer.querySelector('ul');
    }
    
    const nuevoItem = document.createElement('li');
    nuevoItem.className = 'py-1 mb-1 d-flex justify-content-between align-items-center subcategoria-item';
    nuevoItem.setAttribute('data-id', subcategoria.idsubcategoria);
    nuevoItem.innerHTML = `
        <span class="subcategoria-nombre flex-grow-1 me-2" style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
            <i class="ti ti-tag me-1 text-muted"></i>
            ${subcategoria.subcategoria}
        </span>
        <div class="btn-group btn-group-sm flex-shrink-0">
            <button class="btn btn-outline-info btn-sm" 
                    onclick="editarSubcategoria(${subcategoria.idsubcategoria}, '${subcategoria.subcategoria}', ${idcategoria})"
                    title="Editar">
                <i class="ti ti-edit"></i>
            </button>
            <button class="btn btn-outline-danger btn-sm" 
                    onclick="eliminarSubcategoria(${subcategoria.idsubcategoria}, '${subcategoria.subcategoria}')"
                    title="Eliminar">
                <i class="ti ti-trash"></i>
            </button>
        </div>
    `;
    
    ul.appendChild(nuevoItem);
}

function actualizarEstadisticas() {
    // Actualizar las estadísticas sin recargar la página
    fetch('<?= base_url('admin/categorias') ?>')
    .then(response => response.text())
    .then(html => {
        // Extraer las estadísticas del HTML
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const statsCards = doc.querySelectorAll('.card-body h6');
        
        if (statsCards.length >= 4) {
            // Actualizar las estadísticas en la página actual
            const currentStats = document.querySelectorAll('.card-body h6');
            if (currentStats.length >= 4) {
                currentStats[0].textContent = statsCards[0].textContent; // Total Categorías
                currentStats[1].textContent = statsCards[1].textContent; // Total Subcategorías
                currentStats[2].textContent = statsCards[2].textContent; // Con Subcategorías
                currentStats[3].textContent = statsCards[3].textContent; // Sin Subcategorías
            }
        }
    })
    .catch(error => {
        console.log('Error al actualizar estadísticas:', error);
    });
}

function recargarTablaCompleta() {
    // Recargar solo el contenido de categorías
    fetch('<?= base_url('admin/categorias') ?>')
    .then(response => response.text())
    .then(html => {
        // Extraer el tbody del HTML
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const nuevoTbody = doc.querySelector('#tbodyCategorias');
        
        if (nuevoTbody) {
            document.getElementById('tbodyCategorias').innerHTML = nuevoTbody.innerHTML;
        }
    })
    .catch(error => {
        console.log('Error al recargar tabla:', error);
        // Si falla, recargar la página completa como respaldo
        location.reload();
    });
}

// Event listeners para los modales
document.addEventListener('DOMContentLoaded', function() {
    // Event listener para limpiar el formulario cuando se cierra el modal de categorías
    const modalCategoria = document.getElementById('modalCategoria');
    if (modalCategoria) {
        modalCategoria.addEventListener('hidden.bs.modal', function() {
            document.getElementById('formCategoria').reset();
            document.getElementById('categoriaId').value = '';
            document.getElementById('nombreInput').value = '';
        });
    }
});
</script>
