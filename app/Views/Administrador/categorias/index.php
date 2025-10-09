<!-- Vista de Categorías - Integrada con el Dashboard -->
<link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #dc3545;
            --secondary-color: #6c757d;
            --success-color: #198754;
            --info-color: #0dcaf0;
            --warning-color: #ffc107;
            --light-bg: #f8f9fa;
            --border-color: #dee2e6;
            --text-muted: #6c757d;
        }

        body {
            background-color: var(--light-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .main-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }

        .page-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, #e74c3c 100%);
            color: white;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            box-shadow: 0 8px 25px rgba(220, 53, 69, 0.3);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            border-left: 4px solid var(--primary-color);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary-color), #e74c3c);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .categories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 1.5rem;
        }

        .category-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border: 1px solid var(--border-color);
            position: relative;
            overflow: hidden;
        }

        .category-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), #e74c3c);
        }

        .category-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
        }

        .category-header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .category-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #2c3e50;
            margin: 0;
            flex-grow: 1;
        }

        .category-actions {
            display: flex;
            gap: 0.5rem;
        }

        .btn-action {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.875rem;
            transition: all 0.2s ease;
        }

        .btn-edit {
            background-color: var(--info-color);
            color: white;
        }

        .btn-edit:hover {
            background-color: #0aa2c0;
            transform: scale(1.05);
        }

        .btn-delete {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-delete:hover {
            background-color: #c82333;
            transform: scale(1.05);
        }

        .subcategorias-list {
            margin-top: 1rem;
        }

        .subcategoria-item {
            display: flex;
            justify-content: between;
            align-items: center;
            padding: 0.75rem;
            background: var(--light-bg);
            border-radius: 8px;
            margin-bottom: 0.5rem;
            border-left: 3px solid var(--info-color);
        }

        .subcategoria-name {
            font-weight: 500;
            color: #495057;
            flex-grow: 1;
        }

        .add-subcategoria {
            background: none;
            border: 2px dashed var(--border-color);
            color: var(--text-muted);
            padding: 0.75rem;
            border-radius: 8px;
            width: 100%;
            margin-top: 1rem;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .add-subcategoria:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
            background: rgba(220, 53, 69, 0.05);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), #e74c3c);
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4);
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            color: var(--text-muted);
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        /* Modal Styles */
        .modal-content {
            border-radius: 15px;
            border: none;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary-color), #e74c3c);
            color: white;
            border-radius: 15px 15px 0 0;
            border: none;
        }

        .modal-header .btn-close {
            filter: invert(1);
        }

        .form-control, .form-select {
            border-radius: 10px;
            border: 1px solid var(--border-color);
            padding: 0.75rem 1rem;
            transition: all 0.2s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .category-card {
            animation: fadeInUp 0.6s ease-out;
        }

        .category-card:nth-child(2) { animation-delay: 0.1s; }
        .category-card:nth-child(3) { animation-delay: 0.2s; }
        .category-card:nth-child(4) { animation-delay: 0.3s; }
        .category-card:nth-child(5) { animation-delay: 0.4s; }
        .category-card:nth-child(6) { animation-delay: 0.5s; }

        /* Responsive */
        @media (max-width: 768px) {
            .main-container {
                padding: 1rem;
            }
            
            .categories-grid {
                grid-template-columns: 1fr;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

<div class="main-container">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 mb-2">
                        <i class="ri-folder-2-line me-2"></i>
                        Gestión de Categorías
                    </h1>
                    <p class="mb-0 opacity-75">Organiza y gestiona las categorías y subcategorías de tu biblioteca</p>
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger mt-3">
                            <i class="ri-error-warning-line me-2"></i>
                            Error: <?= esc($error) ?>
                        </div>
                    <?php endif; ?>
                </div>
                <button class="btn btn-light btn-lg" onclick="abrirModalCrearCategoria()">
                    <i class="ri-add-line me-2"></i>
                    Nueva Categoría
                </button>
            </div>
        </div>

        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="ri-folder-line"></i>
                </div>
                <h3 class="h4 mb-1"><?= $estadisticas['total_categorias'] ?></h3>
                <p class="text-muted mb-0">Total Categorías</p>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="ri-folder-open-line"></i>
                </div>
                <h3 class="h4 mb-1"><?= $estadisticas['total_subcategorias'] ?></h3>
                <p class="text-muted mb-0">Total Subcategorías</p>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="ri-folder-add-line"></i>
                </div>
                <h3 class="h4 mb-1"><?= $estadisticas['categorias_con_subcategorias'] ?></h3>
                <p class="text-muted mb-0">Con Subcategorías</p>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="ri-folder-reduce-line"></i>
                </div>
                <h3 class="h4 mb-1"><?= $estadisticas['categorias_sin_subcategorias'] ?></h3>
                <p class="text-muted mb-0">Sin Subcategorías</p>
            </div>
        </div>

        <!-- Categories Grid -->
        <div class="categories-grid">
            <?php if (empty($categorias)): ?>
                <div class="col-12">
                    <div class="empty-state">
                        <i class="ri-folder-open-line"></i>
                        <h3>No hay categorías registradas</h3>
                        <p>Crea tu primera categoría para comenzar a organizar tu biblioteca</p>
                        <button class="btn btn-primary" onclick="abrirModalCrearCategoria()">
                            <i class="ri-add-line me-2"></i>
                            Crear Primera Categoría
                        </button>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($categorias as $categoria): ?>
                    <div class="category-card" data-categoria-id="<?= $categoria['idcategoria'] ?>">
                        <div class="category-header">
                            <h3 class="category-title"><?= esc($categoria['categoria']) ?></h3>
                            <div class="category-actions">
                                <button class="btn-action btn-edit" onclick="editarCategoria(<?= $categoria['idcategoria'] ?>, '<?= esc($categoria['categoria']) ?>')" title="Editar categoría">
                                    <i class="ri-edit-line"></i>
                                </button>
                                <button class="btn-action btn-delete" onclick="eliminarCategoria(<?= $categoria['idcategoria'] ?>, '<?= esc($categoria['categoria']) ?>')" title="Eliminar categoría">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="subcategorias-list">
                            <h6 class="text-muted mb-3">
                                <i class="ri-folder-open-line me-1"></i>
                                Subcategorías (<?= $categoria['total_subcategorias'] ?>)
                            </h6>
                            
                            <?php if (empty($categoria['subcategorias'])): ?>
                                <div class="text-center text-muted py-3">
                                    <i class="ri-folder-reduce-line d-block mb-2" style="font-size: 2rem; opacity: 0.5;"></i>
                                    <small>No hay subcategorías</small>
                                </div>
                            <?php else: ?>
                                <?php foreach ($categoria['subcategorias'] as $subcategoria): ?>
                                    <div class="subcategoria-item">
                                        <span class="subcategoria-name"><?= esc($subcategoria['subcategoria']) ?></span>
                                        <div class="d-flex gap-1">
                                            <button class="btn-action btn-edit" onclick="editarSubcategoria(<?= $subcategoria['idsubcategoria'] ?>, '<?= esc($subcategoria['subcategoria']) ?>', <?= $categoria['idcategoria'] ?>)" title="Editar subcategoría">
                                                <i class="ri-edit-line"></i>
                                            </button>
                                            <button class="btn-action btn-delete" onclick="eliminarSubcategoria(<?= $subcategoria['idsubcategoria'] ?>, '<?= esc($subcategoria['subcategoria']) ?>')" title="Eliminar subcategoría">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            
                            <button class="add-subcategoria" onclick="abrirModalCrearSubcategoria(<?= $categoria['idcategoria'] ?>, '<?= esc($categoria['categoria']) ?>')">
                                <i class="ri-add-line me-2"></i>
                                Agregar Subcategoría
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal Crear Categoría -->
    <div class="modal fade" id="modalCrearCategoria" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="ri-add-line me-2"></i>
                        Nueva Categoría
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formCrearCategoria">
                        <div class="mb-3">
                            <label for="categoria" class="form-label">Nombre de la Categoría</label>
                            <input type="text" class="form-control" id="categoria" name="categoria" required placeholder="Ej: Literatura, Ciencias, Historia...">
                            <div class="form-text">Ingresa un nombre descriptivo para la categoría</div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="crearCategoria()">
                        <i class="ri-save-line me-2"></i>
                        Crear Categoría
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Editar Categoría -->
    <div class="modal fade" id="modalEditarCategoria" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="ri-edit-line me-2"></i>
                        Editar Categoría
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditarCategoria">
                        <input type="hidden" id="editar_categoria_id">
                        <div class="mb-3">
                            <label for="editar_categoria" class="form-label">Nombre de la Categoría</label>
                            <input type="text" class="form-control" id="editar_categoria" name="categoria" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="guardarEdicionCategoria()">
                        <i class="ri-save-line me-2"></i>
                        Guardar Cambios
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Crear Subcategoría -->
    <div class="modal fade" id="modalCrearSubcategoria" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="ri-add-line me-2"></i>
                        Nueva Subcategoría
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formCrearSubcategoria">
                        <input type="hidden" id="subcategoria_categoria_id">
                        <div class="mb-3">
                            <label class="form-label">Categoría Padre</label>
                            <input type="text" class="form-control" id="subcategoria_categoria_nombre" readonly style="background-color: var(--light-bg);">
                        </div>
                        <div class="mb-3">
                            <label for="subcategoria" class="form-label">Nombre de la Subcategoría</label>
                            <input type="text" class="form-control" id="subcategoria" name="subcategoria" required placeholder="Ej: Novela, Ensayo, Poesía...">
                            <div class="form-text">Ingresa un nombre específico para la subcategoría</div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="crearSubcategoria()">
                        <i class="ri-save-line me-2"></i>
                        Crear Subcategoría
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Editar Subcategoría -->
    <div class="modal fade" id="modalEditarSubcategoria" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="ri-edit-line me-2"></i>
                        Editar Subcategoría
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditarSubcategoria">
                        <input type="hidden" id="editar_subcategoria_id">
                        <div class="mb-3">
                            <label for="editar_subcategoria_categoria" class="form-label">Categoría Padre</label>
                            <select class="form-select" id="editar_subcategoria_categoria" name="idcategoria" required>
                                <option value="">Seleccionar categoría...</option>
                                <?php foreach ($categorias as $categoria): ?>
                                    <option value="<?= $categoria['idcategoria'] ?>"><?= esc($categoria['categoria']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editar_subcategoria" class="form-label">Nombre de la Subcategoría</label>
                            <input type="text" class="form-control" id="editar_subcategoria" name="subcategoria" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="guardarEdicionSubcategoria()">
                        <i class="ri-save-line me-2"></i>
                        Guardar Cambios
                    </button>
                </div>
            </div>
        </div>
    </div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
        // Variables globales
        let categorias = <?= json_encode($categorias ?? []) ?>;

        // Función para mostrar notificaciones
        function mostrarNotificacion(titulo, mensaje, tipo = 'success') {
            Swal.fire({
                icon: tipo,
                title: titulo,
                text: mensaje,
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        }

        // Función para mostrar loading
        function mostrarLoading(mensaje = 'Procesando...') {
            Swal.fire({
                title: mensaje,
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }

        // Funciones para abrir modales
        function abrirModalCrearCategoria() {
            const modal = new bootstrap.Modal(document.getElementById('modalCrearCategoria'));
            document.getElementById('categoria').value = '';
            modal.show();
        }

        function abrirModalCrearSubcategoria(idcategoria, nombreCategoria) {
            const modal = new bootstrap.Modal(document.getElementById('modalCrearSubcategoria'));
            document.getElementById('subcategoria_categoria_id').value = idcategoria;
            document.getElementById('subcategoria_categoria_nombre').value = nombreCategoria;
            document.getElementById('subcategoria').value = '';
            modal.show();
        }

        function editarCategoria(id, nombre) {
            const modal = new bootstrap.Modal(document.getElementById('modalEditarCategoria'));
            document.getElementById('editar_categoria_id').value = id;
            document.getElementById('editar_categoria').value = nombre;
            modal.show();
        }

        function editarSubcategoria(id, nombre, idcategoria) {
            const modal = new bootstrap.Modal(document.getElementById('modalEditarSubcategoria'));
            document.getElementById('editar_subcategoria_id').value = id;
            document.getElementById('editar_subcategoria').value = nombre;
            document.getElementById('editar_subcategoria_categoria').value = idcategoria;
            modal.show();
        }

        // Funciones CRUD para categorías
        function crearCategoria() {
            const categoria = document.getElementById('categoria').value.trim();
            
            if (!categoria) {
                mostrarNotificacion('Error', 'El nombre de la categoría es requerido', 'error');
                return;
            }

            mostrarLoading('Creando categoría...');

            fetch('<?= base_url('admin/crear-categoria') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `categoria=${encodeURIComponent(categoria)}`
            })
            .then(response => response.json())
            .then(data => {
                Swal.close();
                if (data.success) {
                    mostrarNotificacion('Éxito', data.message);
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    mostrarNotificacion('Error', data.message, 'error');
                }
            })
            .catch(error => {
                Swal.close();
                mostrarNotificacion('Error', 'Error de conexión', 'error');
                console.error('Error:', error);
            });
        }

        function guardarEdicionCategoria() {
            const id = document.getElementById('editar_categoria_id').value;
            const categoria = document.getElementById('editar_categoria').value.trim();
            
            if (!categoria) {
                mostrarNotificacion('Error', 'El nombre de la categoría es requerido', 'error');
                return;
            }

            mostrarLoading('Actualizando categoría...');

            fetch(`<?= base_url('admin/editar-categoria') ?>/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `categoria=${encodeURIComponent(categoria)}`
            })
            .then(response => response.json())
            .then(data => {
                Swal.close();
                if (data.success) {
                    mostrarNotificacion('Éxito', data.message);
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    mostrarNotificacion('Error', data.message, 'error');
                }
            })
            .catch(error => {
                Swal.close();
                mostrarNotificacion('Error', 'Error de conexión', 'error');
                console.error('Error:', error);
            });
        }

        function eliminarCategoria(id, nombre) {
            Swal.fire({
                title: '¿Eliminar categoría?',
                html: `¿Estás seguro de que deseas eliminar la categoría <strong>"${nombre}"</strong>?<br><br><small class="text-muted">Esta acción no se puede deshacer.</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    mostrarLoading('Eliminando categoría...');

                    fetch(`<?= base_url('admin/eliminar-categoria') ?>/${id}`, {
                        method: 'DELETE'
                    })
                    .then(response => response.json())
                    .then(data => {
                        Swal.close();
                        if (data.success) {
                            mostrarNotificacion('Éxito', data.message);
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } else {
                            mostrarNotificacion('Error', data.message, 'error');
                        }
                    })
                    .catch(error => {
                        Swal.close();
                        mostrarNotificacion('Error', 'Error de conexión', 'error');
                        console.error('Error:', error);
                    });
                }
            });
        }

        // Funciones CRUD para subcategorías
        function crearSubcategoria() {
            const idcategoria = document.getElementById('subcategoria_categoria_id').value;
            const subcategoria = document.getElementById('subcategoria').value.trim();
            
            if (!subcategoria) {
                mostrarNotificacion('Error', 'El nombre de la subcategoría es requerido', 'error');
                return;
            }

            mostrarLoading('Creando subcategoría...');

            fetch('<?= base_url('admin/crear-subcategoria') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `subcategoria=${encodeURIComponent(subcategoria)}&idcategoria=${idcategoria}`
            })
            .then(response => response.json())
            .then(data => {
                Swal.close();
                if (data.success) {
                    mostrarNotificacion('Éxito', data.message);
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    mostrarNotificacion('Error', data.message, 'error');
                }
            })
            .catch(error => {
                Swal.close();
                mostrarNotificacion('Error', 'Error de conexión', 'error');
                console.error('Error:', error);
            });
        }

        function guardarEdicionSubcategoria() {
            const id = document.getElementById('editar_subcategoria_id').value;
            const subcategoria = document.getElementById('editar_subcategoria').value.trim();
            const idcategoria = document.getElementById('editar_subcategoria_categoria').value;
            
            if (!subcategoria || !idcategoria) {
                mostrarNotificacion('Error', 'Todos los campos son requeridos', 'error');
                return;
            }

            mostrarLoading('Actualizando subcategoría...');

            fetch(`<?= base_url('admin/editar-subcategoria') ?>/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `subcategoria=${encodeURIComponent(subcategoria)}&idcategoria=${idcategoria}`
            })
            .then(response => response.json())
            .then(data => {
                Swal.close();
                if (data.success) {
                    mostrarNotificacion('Éxito', data.message);
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    mostrarNotificacion('Error', data.message, 'error');
                }
            })
            .catch(error => {
                Swal.close();
                mostrarNotificacion('Error', 'Error de conexión', 'error');
                console.error('Error:', error);
            });
        }

        function eliminarSubcategoria(id, nombre) {
            Swal.fire({
                title: '¿Eliminar subcategoría?',
                html: `¿Estás seguro de que deseas eliminar la subcategoría <strong>"${nombre}"</strong>?<br><br><small class="text-muted">Esta acción no se puede deshacer.</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    mostrarLoading('Eliminando subcategoría...');

                    fetch(`<?= base_url('admin/eliminar-subcategoria') ?>/${id}`, {
                        method: 'DELETE'
                    })
                    .then(response => response.json())
                    .then(data => {
                        Swal.close();
                        if (data.success) {
                            mostrarNotificacion('Éxito', data.message);
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } else {
                            mostrarNotificacion('Error', data.message, 'error');
                        }
                    })
                    .catch(error => {
                        Swal.close();
                        mostrarNotificacion('Error', 'Error de conexión', 'error');
                        console.error('Error:', error);
                    });
                }
            });
        }

        // Limpiar formularios al cerrar modales
        document.addEventListener('DOMContentLoaded', function() {
            const modals = ['modalCrearCategoria', 'modalEditarCategoria', 'modalCrearSubcategoria', 'modalEditarSubcategoria'];
            
            modals.forEach(modalId => {
                const modal = document.getElementById(modalId);
                modal.addEventListener('hidden.bs.modal', function() {
                    const form = modal.querySelector('form');
                    if (form) {
                        form.reset();
                    }
                });
            });
        });
</script>
