<?= $header ?>
<?= $navbar ?>

<!-- Estilos institucionales de la Biblioteca Virtual HZG -->
<link rel="stylesheet" href="<?= base_url('assets/css/biblioteca-hzg.css') ?>">

<div class="container mt-4">
    <!-- Header de la página -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h1 class="text-primary mb-2">
                        <i class="fas fa-book me-3"></i>Mis Préstamos
                    </h1>
                    <p class="text-muted">Gestiona tus libros prestados y consulta el historial</p>
                </div>
                <div class="d-none d-md-block">
                    <div class="card bg-light border-0">
                        <div class="card-body text-center py-2">
                            <small class="text-muted">Préstamos Activos</small>
                            <h4 class="text-primary mb-0" id="contadorActivos">3</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros y búsqueda -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Buscar en mis préstamos..." id="buscarPrestamos">
                <button class="btn btn-outline-primary" type="button">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
        <div class="col-md-4">
            <select class="form-select" id="filtroEstado">
                <option value="">Todos los estados</option>
                <option value="activo">Préstamos Activos</option>
                <option value="vencido">Vencidos</option>
                <option value="devuelto">Devueltos</option>
                <option value="renovado">Renovados</option>
            </select>
        </div>
    </div>

    <!-- Pestañas de navegación -->
    <ul class="nav nav-tabs mb-4" id="prestamosTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="activos-tab" data-bs-toggle="tab" data-bs-target="#activos" type="button" role="tab">
                <i class="fas fa-book-open me-2"></i>Préstamos Activos
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="historial-tab" data-bs-toggle="tab" data-bs-target="#historial" type="button" role="tab">
                <i class="fas fa-history me-2"></i>Historial
            </button>
        </li>
    </ul>

    <!-- Contenido de pestañas -->
    <div class="tab-content" id="prestamosTabContent">
        <!-- Préstamos Activos -->
        <div class="tab-pane fade show active" id="activos" role="tabpanel">
            <div class="row" id="prestamosActivos">
                <!-- Ejemplo de préstamo activo -->
                <div class="col-lg-6 col-xl-4 mb-4">
                    <div class="card h-100 border-start border-primary border-3">
                        <div class="card-header bg-light border-0 d-flex justify-content-between align-items-center">
                            <span class="badge bg-success">Activo</span>
                            <small class="text-muted">Vence: 15/Oct/2025</small>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-4">
                                    <img src="<?= base_url('img/portada_1.png') ?>" class="img-fluid rounded" alt="Portada" style="height: 80px; object-fit: cover;">
                                </div>
                                <div class="col-8">
                                    <h6 class="card-title mb-2">Matemáticas Básicas</h6>
                                    <p class="card-text small text-muted mb-2">
                                        <strong>Autor:</strong> Juan Pérez
                                    </p>
                                    <p class="card-text small text-muted mb-2">
                                        <strong>Prestado:</strong> 01/Oct/2025
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0">
                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-primary btn-sm flex-fill">
                                    <i class="fas fa-redo-alt me-1"></i>Renovar
                                </button>
                                <button class="btn btn-primary btn-sm flex-fill">
                                    <i class="fas fa-check me-1"></i>Devolver
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ejemplo de préstamo vencido -->
                <div class="col-lg-6 col-xl-4 mb-4">
                    <div class="card h-100 border-start border-danger border-3">
                        <div class="card-header bg-light border-0 d-flex justify-content-between align-items-center">
                            <span class="badge bg-danger">Vencido</span>
                            <small class="text-danger">Vencido: 25/Sep/2025</small>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-4">
                                    <img src="<?= base_url('img/portada_2.png') ?>" class="img-fluid rounded" alt="Portada" style="height: 80px; object-fit: cover;">
                                </div>
                                <div class="col-8">
                                    <h6 class="card-title mb-2">Historia Universal</h6>
                                    <p class="card-text small text-muted mb-2">
                                        <strong>Autor:</strong> María García
                                    </p>
                                    <p class="card-text small text-muted mb-2">
                                        <strong>Prestado:</strong> 10/Sep/2025
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0">
                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-warning btn-sm flex-fill">
                                    <i class="fas fa-exclamation-triangle me-1"></i>Renovar
                                </button>
                                <button class="btn btn-danger btn-sm flex-fill">
                                    <i class="fas fa-check me-1"></i>Devolver
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ejemplo de préstamo próximo a vencer -->
                <div class="col-lg-6 col-xl-4 mb-4">
                    <div class="card h-100 border-start border-warning border-3">
                        <div class="card-header bg-light border-0 d-flex justify-content-between align-items-center">
                            <span class="badge bg-warning text-dark">Por Vencer</span>
                            <small class="text-warning">Vence: 05/Oct/2025</small>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-4">
                                    <img src="<?= base_url('img/inicial.jpg') ?>" class="img-fluid rounded" alt="Portada" style="height: 80px; object-fit: cover;">
                                </div>
                                <div class="col-8">
                                    <h6 class="card-title mb-2">Ciencias Naturales</h6>
                                    <p class="card-text small text-muted mb-2">
                                        <strong>Autor:</strong> Carlos López
                                    </p>
                                    <p class="card-text small text-muted mb-2">
                                        <strong>Prestado:</strong> 20/Sep/2025
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0">
                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-primary btn-sm flex-fill">
                                    <i class="fas fa-redo-alt me-1"></i>Renovar
                                </button>
                                <button class="btn btn-primary btn-sm flex-fill">
                                    <i class="fas fa-check me-1"></i>Devolver
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mensaje cuando no hay préstamos activos -->
            <div class="row d-none" id="sinPrestamosActivos">
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">No tienes préstamos activos</h4>
                        <p class="text-muted mb-4">¡Explora nuestro catálogo y encuentra tu próximo libro!</p>
                        <a href="<?= site_url('catalogo') ?>" class="btn btn-primary">
                            <i class="fas fa-search me-2"></i>Explorar Catálogo
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Historial -->
        <div class="tab-pane fade" id="historial" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Libro</th>
                            <th>Autor</th>
                            <th>Fecha Préstamo</th>
                            <th>Fecha Devolución</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="historialPrestamos">
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="<?= base_url('img/primaria.jpg') ?>" class="rounded me-3" style="width: 40px; height: 50px; object-fit: cover;" alt="Portada">
                                    <div>
                                        <h6 class="mb-0">Álgebra Avanzada</h6>
                                        <small class="text-muted">ISBN: 978-123456789</small>
                                    </div>
                                </div>
                            </td>
                            <td>Ana Martínez</td>
                            <td>15/Sep/2025</td>
                            <td>28/Sep/2025</td>
                            <td><span class="badge bg-success">Devuelto</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" title="Volver a prestar">
                                    <i class="fas fa-redo-alt"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="<?= base_url('img/secundaria.jpg') ?>" class="rounded me-3" style="width: 40px; height: 50px; object-fit: cover;" alt="Portada">
                                    <div>
                                        <h6 class="mb-0">Física Moderna</h6>
                                        <small class="text-muted">ISBN: 978-987654321</small>
                                    </div>
                                </div>
                            </td>
                            <td>Roberto Silva</td>
                            <td>01/Sep/2025</td>
                            <td>20/Sep/2025</td>
                            <td><span class="badge bg-success">Devuelto</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" title="Volver a prestar">
                                    <i class="fas fa-redo-alt"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Paginación del historial -->
            <nav aria-label="Paginación del historial">
                <ul class="pagination justify-content-center">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1">Anterior</a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">Siguiente</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Funcionalidad de búsqueda
    const buscarInput = document.getElementById('buscarPrestamos');
    const filtroEstado = document.getElementById('filtroEstado');

    buscarInput.addEventListener('input', filtrarPrestamos);
    filtroEstado.addEventListener('change', filtrarPrestamos);

    function filtrarPrestamos() {
        // Aquí iría la lógica de filtrado
        console.log('Filtrando préstamos...');
    }

    // Funcionalidad de botones
    document.querySelectorAll('.btn-outline-primary').forEach(btn => {
        if (btn.textContent.includes('Renovar')) {
            btn.addEventListener('click', function() {
                if (confirm('¿Deseas renovar este préstamo?')) {
                    // Lógica de renovación
                    alert('Préstamo renovado exitosamente');
                }
            });
        }
    });

    document.querySelectorAll('.btn-primary, .btn-danger').forEach(btn => {
        if (btn.textContent.includes('Devolver')) {
            btn.addEventListener('click', function() {
                if (confirm('¿Confirmas la devolución de este libro?')) {
                    // Lógica de devolución
                    alert('Libro devuelto exitosamente');
                }
            });
        }
    });

    // Actualizar contador
    function actualizarContador() {
        const prestamosActivos = document.querySelectorAll('#prestamosActivos .card').length;
        document.getElementById('contadorActivos').textContent = prestamosActivos;
    }

    actualizarContador();
});
</script>

<?= $footer ?>