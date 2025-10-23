<?= $header ?>
<?= $navbar ?>

<!-- Estilos institucionales de la Biblioteca Virtual HZG -->
<link rel="stylesheet" href="<?= base_url('assets/css/biblioteca-hzg.css') ?>">

<div class="container mt-4">
    <!-- Header de la página -->
    <div class="row mb-4">
        <div class="col-lg-8 col-md-7">
            <div class="d-flex align-items-center h-100">
                <div>
                    <h1 class="text-primary mb-2">
                        <i class="fas fa-book me-3"></i>Mis Préstamos
                    </h1>
                    <p class="text-muted mb-0">Gestiona tus libros prestados y consulta el historial</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-5">
            <div class="d-flex justify-content-end align-items-center h-100">
                <div class="card bg-primary bg-gradient text-white border-0 shadow-sm">
                    <div class="card-body text-center py-3 px-4">
                        <div class="d-flex align-items-center justify-content-center">
                            <i class="fas fa-bookmark fa-2x me-3"></i>
                            <div>
                                <small class="text-white-50 d-block">Préstamos Activos</small>
                                <h3 class="text-white mb-0 fw-bold" id="contadorActivos"><?= $contadorActivos ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pestañas de navegación -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <ul class="nav nav-tabs nav-fill border-0 mb-0" id="prestamosTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active border-0 py-3" id="activos-tab" data-bs-toggle="tab" data-bs-target="#activos" type="button" role="tab" aria-controls="activos" aria-selected="true">
                                <i class="fas fa-book-open me-2"></i>
                                <span class="d-none d-sm-inline">Préstamos </span>Activos
                                <span class="badge bg-primary ms-2"><?= $contadorActivos ?></span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link border-0 py-3" id="historial-tab" data-bs-toggle="tab" data-bs-target="#historial" type="button" role="tab" aria-controls="historial" aria-selected="false">
                                <i class="fas fa-history me-2"></i>
                                <span class="d-none d-sm-inline">Historial</span>
                                <span class="d-sm-none">Hist.</span>
                                <span class="badge bg-secondary ms-2"><?= count($historialPrestamos) ?></span>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros y búsqueda - Solo mostrar si hay préstamos -->
    <?php if (!empty($prestamosActivos) || !empty($historialPrestamos)): ?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-lg-5 col-md-6">
                            <label for="buscarPrestamos" class="form-label fw-semibold text-muted small mb-1">
                                <i class="fas fa-search me-1"></i>Buscar préstamos
                            </label>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Buscar por título o autor..." id="buscarPrestamos">
                                <button class="btn btn-outline-primary" type="button">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <label for="filtroEstado" class="form-label fw-semibold text-muted small mb-1">
                                <i class="fas fa-filter me-1"></i>Estado
                            </label>
                            <select class="form-select" id="filtroEstado">
                                <option value="">Todos los estados</option>
                                <option value="activo">Activos</option>
                                <option value="vencido">Vencidos</option>
                                <option value="devuelto">Devueltos</option>
                                <option value="renovado">Renovados</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <label for="ordenarPor" class="form-label fw-semibold text-muted small mb-1">
                                <i class="fas fa-sort me-1"></i>Ordenar por
                            </label>
                            <select class="form-select" id="ordenarPor">
                                <option value="reciente">Más recientes</option>
                                <option value="alfabetico">Orden alfabético</option>
                                <option value="autor">Por autor</option>
                                <option value="estado">Por estado</option>
                            </select>
                        </div>
                        <div class="col-lg-1 col-md-12 d-flex align-items-end justify-content-center">
                            <button type="button" class="btn btn-outline-secondary" onclick="limpiarFiltros()" title="Limpiar filtros">
                                <i class="fas fa-times"></i>
                                <span class="d-none d-lg-inline ms-1">Limpiar</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Controles de vista y resultados - Solo mostrar si hay préstamos -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
                <div class="d-flex align-items-center">
                    <span class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Mostrando <strong><span id="resultadosCount"><?= $contadorActivos ?></span></strong> préstamos
                    </span>
                </div>
                <div class="btn-group shadow-sm" role="group">
                    <button type="button" class="btn btn-outline-primary active" id="vistaGrilla" title="Vista en grilla">
                        <i class="fas fa-th me-1"></i><span class="d-none d-md-inline">Grilla</span>
                    </button>
                    <button type="button" class="btn btn-outline-primary" id="vistaLista" title="Vista en lista">
                        <i class="fas fa-list me-1"></i><span class="d-none d-md-inline">Lista</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Contenido de pestañas -->
    <div class="tab-content" id="prestamosTabContent">
        <!-- Préstamos Activos -->
        <div class="tab-pane fade show active" id="activos" role="tabpanel">
            <?php if (!empty($prestamosActivos)): ?>
                <!-- Contenido de préstamos activos - Vista Grilla -->
                <div class="row" id="prestamosActivosGrilla">
                    <?php foreach ($prestamosActivos as $prestamo): ?>
                        <?= view('partials/prestamo_card', ['prestamo' => $prestamo]) ?>
                    <?php endforeach; ?>
                </div>

                <!-- Contenido de préstamos activos - Vista Lista (oculta por defecto) -->
                <div class="d-none" id="prestamosActivosLista">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="border-0 fw-semibold">Libro</th>
                                            <th class="border-0 fw-semibold">Autor</th>
                                            <th class="border-0 fw-semibold">Fecha Préstamo</th>
                                            <th class="border-0 fw-semibold">Fecha Vencimiento</th>
                                            <th class="border-0 fw-semibold">Estado</th>
                                            <th class="border-0 fw-semibold text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($prestamosActivos as $prestamo): ?>
                                            <tr class="align-middle">
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <?php if (!empty($prestamo['portada'])): ?>
                                                            <img src="<?= base_url($prestamo['portada']) ?>" class="rounded me-3" style="width: 40px; height: 50px; object-fit: cover;" alt="Portada">
                                                        <?php else: ?>
                                                            <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 50px;">
                                                                <i class="fas fa-book text-muted"></i>
                                                            </div>
                                                        <?php endif; ?>
                                                        <div>
                                                            <h6 class="mb-0 fw-semibold"><?= esc($prestamo['titulo']) ?></h6>
                                                            <?php if (!empty($prestamo['isbn'])): ?>
                                                                <small class="text-muted">ISBN: <?= esc($prestamo['isbn']) ?></small>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-muted"><?= esc($prestamo['nomautor'] ?: 'Sin autor') ?></td>
                                                <td>
                                                    <small class="text-muted">
                                                        <i class="fas fa-calendar-alt me-1"></i>
                                                        <?= date('d/M/Y', strtotime($prestamo['fechaprestamo'])) ?>
                                                    </small>
                                                </td>
                                                <td>
                                                    <?php if (!empty($prestamo['fechadevolucion'])): ?>
                                                        <?php 
                                                        $fechaVencimiento = strtotime($prestamo['fechadevolucion']);
                                                        $hoy = time();
                                                        $esVencido = $fechaVencimiento < $hoy;
                                                        ?>
                                                        <small class="<?= $esVencido ? 'text-danger' : 'text-success' ?>">
                                                            <i class="fas fa-clock me-1"></i>
                                                            <?= date('d/M/Y', $fechaVencimiento) ?>
                                                        </small>
                                                    <?php else: ?>
                                                        <small class="text-muted">Sin fecha</small>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if (!empty($prestamo['fechadevolucion']) && strtotime($prestamo['fechadevolucion']) < time()): ?>
                                                        <span class="badge bg-danger">Vencido</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-success">Activo</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <button class="btn btn-sm btn-outline-primary" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#prestamoModal"
                                                                onclick="cargarDetallesPrestamo(<?= $prestamo['idprestamo'] ?>)" 
                                                                title="Ver detalles">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-warning" 
                                                                onclick="renovarPrestamo(<?= $prestamo['idprestamo'] ?>)" 
                                                                title="Renovar">
                                                            <i class="fas fa-redo"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-success" 
                                                                onclick="devolverPrestamo(<?= $prestamo['idprestamo'] ?>)" 
                                                                title="Devolver">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <!-- Mensaje cuando no hay préstamos activos -->
                <div class="row" id="sinPrestamosActivos">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center py-5">
                                <div class="mb-4">
                                    <i class="fas fa-book-open fa-4x text-primary opacity-50"></i>
                                </div>
                                <h4 class="text-muted mb-3">No tienes préstamos activos</h4>
                                <p class="text-muted mb-4 lead">¡Explora nuestro catálogo y encuentra tu próximo libro!</p>
                                
                                <?php if (session()->get('nivel') === 'admin'): ?>
                                    <div class="alert alert-info border-0 mb-4">
                                        <i class="fas fa-user-shield me-2"></i>
                                        <strong>Modo Admin:</strong> Para probar la funcionalidad, puedes 
                                        <a href="<?= site_url('catalogo/insertar-datos-prueba') ?>" class="alert-link text-decoration-none">insertar datos de prueba</a>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                                    <a href="<?= site_url('catalogo') ?>" class="btn btn-primary btn-lg">
                                        <i class="fas fa-search me-2"></i>Explorar Catálogo
                                    </a>
                                    <a href="<?= site_url('catalogo') ?>?categoria=populares" class="btn btn-outline-primary btn-lg">
                                        <i class="fas fa-star me-2"></i>Libros Populares
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Historial -->
        <div class="tab-pane fade" id="historial" role="tabpanel">
            <?php if (!empty($historialPrestamos)): ?>
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0 fw-semibold">Libro</th>
                                        <th class="border-0 fw-semibold">Autor</th>
                                        <th class="border-0 fw-semibold">Fecha Préstamo</th>
                                        <th class="border-0 fw-semibold">Fecha Devolución</th>
                                        <th class="border-0 fw-semibold">Estado</th>
                                        <th class="border-0 fw-semibold text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="historialPrestamos">
                                    <?php foreach ($historialPrestamos as $prestamo): ?>
                                        <tr class="align-middle">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <?php if (!empty($prestamo['portada'])): ?>
                                                        <img src="<?= base_url($prestamo['portada']) ?>" class="rounded me-3" style="width: 40px; height: 50px; object-fit: cover;" alt="Portada">
                                                    <?php else: ?>
                                                        <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 50px;">
                                                            <i class="fas fa-book text-muted"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div>
                                                        <h6 class="mb-0 fw-semibold"><?= esc($prestamo['titulo']) ?></h6>
                                                        <?php if (!empty($prestamo['isbn'])): ?>
                                                            <small class="text-muted">ISBN: <?= esc($prestamo['isbn']) ?></small>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-muted"><?= esc($prestamo['nomautor'] ?: 'Sin autor') ?></td>
                                            <td>
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar-alt me-1"></i>
                                                    <?= date('d/M/Y', strtotime($prestamo['fechaprestamo'])) ?>
                                                </small>
                                            </td>
                                            <td>
                                                <?php if (!empty($prestamo['fechahoraretorno'])): ?>
                                                    <small class="text-success">
                                                        <i class="fas fa-calendar-check me-1"></i>
                                                        <?= date('d/M/Y', strtotime($prestamo['fechahoraretorno'])) ?>
                                                    </small>
                                                <?php elseif (!empty($prestamo['fechadevolucion'])): ?>
                                                    <small class="text-warning">
                                                        <i class="fas fa-clock me-1"></i>
                                                        Vence: <?= date('d/M/Y', strtotime($prestamo['fechadevolucion'])) ?>
                                                    </small>
                                                <?php else: ?>
                                                    <small class="text-muted">Sin fecha</small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if (!empty($prestamo['fechahoraretorno'])): ?>
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check me-1"></i>Devuelto
                                                    </span>
                                                <?php elseif (!empty($prestamo['fechadevolucion']) && strtotime($prestamo['fechadevolucion']) < time()): ?>
                                                    <span class="badge bg-danger">
                                                        <i class="fas fa-exclamation-triangle me-1"></i>Vencido
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-primary">
                                                        <i class="fas fa-clock me-1"></i>Activo
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-outline-primary" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#prestamoModal"
                                                        onclick="cargarDetallesPrestamo(<?= $prestamo['idprestamo'] ?>)" 
                                                        title="Ver detalles">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Paginación del historial -->
                <?php if (count($historialPrestamos) > 10): ?>
                <div class="d-flex justify-content-center mt-4">
                    <nav aria-label="Paginación del historial">
                        <ul class="pagination shadow-sm">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                                    <i class="fas fa-chevron-left"></i>
                                    <span class="d-none d-sm-inline ms-1">Anterior</span>
                                </a>
                            </li>
                            <li class="page-item active">
                                <a class="page-link" href="#">1</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">2</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">3</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">
                                    <span class="d-none d-sm-inline me-1">Siguiente</span>
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <?php endif; ?>
            <?php else: ?>
                <!-- Mensaje cuando no hay historial -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-history fa-4x text-secondary opacity-50"></i>
                        </div>
                        <h4 class="text-muted mb-3">No hay historial de préstamos</h4>
                        <p class="text-muted mb-4 lead">Cuando realices préstamos aparecerán aquí</p>
                        <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                            <a href="<?= site_url('catalogo') ?>" class="btn btn-primary btn-lg">
                                <i class="fas fa-search me-2"></i>Explorar Catálogo
                            </a>
                            <a href="<?= site_url('catalogo') ?>?categoria=novedades" class="btn btn-outline-primary btn-lg">
                                <i class="fas fa-sparkles me-2"></i>Ver Novedades
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// Función para cargar detalles del préstamo (debe estar fuera del DOMContentLoaded para ser accesible globalmente)
function cargarDetallesPrestamo(idPrestamo) {
    const modalBody = document.getElementById('prestamoModalBody');
    
    // Mostrar loading
    modalBody.innerHTML = `
        <div class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="mt-2">Cargando detalles del préstamo...</p>
        </div>
    `;
    
    // Cargar detalles via AJAX
    fetch(`<?= base_url('prestamo/detalles/') ?>${idPrestamo}`)
        .then(response => response.text())
        .then(html => {
            modalBody.innerHTML = html;
        })
        .catch(error => {
            console.error('Error al cargar detalles:', error);
            modalBody.innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Error al cargar los detalles del préstamo.
                </div>
            `;
        });
}

// Funciones globales para los botones de préstamo
function renovarPrestamo(idPrestamo) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: '¿Renovar préstamo?',
            text: 'Se extenderá el período de préstamo por 15 días más.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, renovar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#28a745'
        }).then((result) => {
            if (result.isConfirmed) {
                procesarRenovacion(idPrestamo);
            }
        });
    } else {
        if (confirm('¿Deseas renovar este préstamo?')) {
            procesarRenovacion(idPrestamo);
        }
    }
}

function procesarRenovacion(idPrestamo) {
    fetch('<?= base_url('catalogo/renovar-prestamo') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({idprestamo: idPrestamo})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Préstamo Renovado',
                    text: data.message,
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            } else {
                alert('Préstamo renovado exitosamente');
                location.reload();
            }
        } else {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Error',
                    text: data.message || 'Error al renovar el préstamo',
                    icon: 'error'
                });
            } else {
                alert('Error al renovar: ' + (data.message || 'Error desconocido'));
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Error',
                text: 'Error de conexión',
                icon: 'error'
            });
        } else {
            alert('Error de conexión');
        }
    });
}

function devolverPrestamo(idPrestamo) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: '¿Devolver libro?',
            text: 'Confirma que vas a devolver este libro.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, devolver',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#28a745'
        }).then((result) => {
            if (result.isConfirmed) {
                procesarDevolucion(idPrestamo);
            }
        });
    } else {
        if (confirm('¿Confirmas la devolución de este libro?')) {
            procesarDevolucion(idPrestamo);
        }
    }
}

function procesarDevolucion(idPrestamo) {
    fetch('<?= base_url('catalogo/devolver-prestamo') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({idprestamo: idPrestamo})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Libro Devuelto',
                    text: data.message,
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            } else {
                alert('Libro devuelto exitosamente');
                location.reload();
            }
        } else {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Error',
                    text: data.message || 'Error al devolver el libro',
                    icon: 'error'
                });
            } else {
                alert('Error al devolver: ' + (data.message || 'Error desconocido'));
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Error',
                text: 'Error de conexión',
                icon: 'error'
            });
        } else {
            alert('Error de conexión');
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    // Funcionalidad de cambio de vista - Solo si existen los elementos
    const vistaGrilla = document.getElementById('vistaGrilla');
    const vistaLista = document.getElementById('vistaLista');
    const prestamosActivosGrilla = document.getElementById('prestamosActivosGrilla');
    const prestamosActivosLista = document.getElementById('prestamosActivosLista');

    if (vistaGrilla && vistaLista && prestamosActivosGrilla && prestamosActivosLista) {
        vistaGrilla.addEventListener('click', function() {
            this.classList.add('active');
            vistaLista.classList.remove('active');
            prestamosActivosGrilla.classList.remove('d-none');
            prestamosActivosLista.classList.add('d-none');
            // Reaplicar filtros después del cambio de vista
            filtrarPrestamos();
        });

        vistaLista.addEventListener('click', function() {
            this.classList.add('active');
            vistaGrilla.classList.remove('active');
            prestamosActivosLista.classList.remove('d-none');
            prestamosActivosGrilla.classList.add('d-none');
            // Reaplicar filtros después del cambio de vista
            filtrarPrestamos();
        });
    }

    // Funcionalidad de búsqueda y filtros
    const buscarInput = document.getElementById('buscarPrestamos');
    const filtroEstado = document.getElementById('filtroEstado');
    const ordenarPor = document.getElementById('ordenarPor');

    if (buscarInput && filtroEstado && ordenarPor) {
        buscarInput.addEventListener('input', filtrarPrestamos);
        filtroEstado.addEventListener('change', filtrarPrestamos);
        ordenarPor.addEventListener('change', filtrarPrestamos);
    }

    function filtrarPrestamos() {
        // Verificar que los elementos existan antes de usarlos
        if (!buscarInput || !filtroEstado || !ordenarPor) {
            return;
        }
        
        const busqueda = buscarInput.value.toLowerCase().trim();
        const estadoSeleccionado = filtroEstado.value.toLowerCase();
        const ordenSeleccionado = ordenarPor.value;
        
        console.log('Filtros aplicados:', { busqueda, estadoSeleccionado, ordenSeleccionado });
        
        // Obtener todas las cards de préstamos activos (grilla)
        const cards = Array.from(document.querySelectorAll('#prestamosActivosGrilla .col-lg-6'));
        let cardsVisibles = [];
        
        // Obtener todas las filas de la tabla de préstamos activos (lista)
        const filas = Array.from(document.querySelectorAll('#prestamosActivosLista tbody tr'));
        let filasVisibles = [];
        
        // Obtener todas las filas del historial
        const filasHistorial = Array.from(document.querySelectorAll('#historialPrestamos tr'));
        let filasHistorialVisibles = [];
        
        // Filtrar cards de grilla (préstamos activos)
        cards.forEach(card => {
            const titulo = card.querySelector('h5.card-title, .card-title h5')?.textContent.toLowerCase() || '';
            const autor = card.querySelector('.card-text')?.textContent.toLowerCase() || '';
            const badge = card.querySelector('.badge');
            const estado = badge?.textContent.toLowerCase() || '';
            
            const coincideBusqueda = !busqueda || 
                titulo.includes(busqueda) || 
                autor.includes(busqueda);
            
            const coincidenEstado = !estadoSeleccionado || 
                estado.includes(estadoSeleccionado);
            
            if (coincideBusqueda && coincidenEstado) {
                card.style.display = 'block';
                cardsVisibles.push(card);
            } else {
                card.style.display = 'none';
            }
        });
        
        // Filtrar filas de préstamos activos (lista)
        filas.forEach(fila => {
            if (fila.querySelector('td[colspan]')) {
                return; // Evitar filtrar la fila de "no hay préstamos"
            }
            
            const titulo = fila.querySelector('h6')?.textContent.toLowerCase() || '';
            const autor = fila.cells[1]?.textContent.toLowerCase() || '';
            const badge = fila.querySelector('.badge');
            const estado = badge?.textContent.toLowerCase() || '';
            
            const coincideBusqueda = !busqueda || 
                titulo.includes(busqueda) || 
                autor.includes(busqueda);
            
            const coincidenEstado = !estadoSeleccionado || 
                estado.includes(estadoSeleccionado);
            
            if (coincideBusqueda && coincidenEstado) {
                fila.style.display = 'table-row';
                filasVisibles.push(fila);
            } else {
                fila.style.display = 'none';
            }
        });
        
        // Filtrar filas del historial
        filasHistorial.forEach(fila => {
            if (fila.querySelector('td[colspan]')) {
                return; // Evitar filtrar la fila de "no hay historial"
            }
            
            const titulo = fila.querySelector('h6')?.textContent.toLowerCase() || '';
            const autor = fila.cells[1]?.textContent.toLowerCase() || '';
            const badge = fila.querySelector('.badge');
            const estado = badge?.textContent.toLowerCase() || '';
            
            const coincideBusqueda = !busqueda || 
                titulo.includes(busqueda) || 
                autor.includes(busqueda);
            
            const coincidenEstado = !estadoSeleccionado || 
                estado.includes(estadoSeleccionado);
            
            if (coincideBusqueda && coincidenEstado) {
                fila.style.display = 'table-row';
                filasHistorialVisibles.push(fila);
            } else {
                fila.style.display = 'none';
            }
        });
        
        // Ordenar elementos visibles
        if (cardsVisibles.length > 0) {
            ordenarCards(cardsVisibles, ordenSeleccionado);
        }
        if (filasVisibles.length > 0) {
            ordenarFilas(filasVisibles, ordenSeleccionado, '#prestamosActivosLista tbody');
        }
        if (filasHistorialVisibles.length > 0) {
            ordenarFilas(filasHistorialVisibles, ordenSeleccionado, '#historialPrestamos');
        }
        
        // Actualizar contador
        const totalVisibles = Math.max(cardsVisibles.length, filasVisibles.length);
        const contadorElement = document.getElementById('resultadosCount');
        if (contadorElement) {
            contadorElement.textContent = totalVisibles;
        }
    }
    
    function ordenarCards(cards, criterio) {
        const container = document.getElementById('prestamosActivosGrilla');
        
        cards.sort((a, b) => {
            switch (criterio) {
                case 'alfabetico':
                    const tituloA = a.querySelector('.card-title h5, h5.card-title')?.textContent || '';
                    const tituloB = b.querySelector('.card-title h5, h5.card-title')?.textContent || '';
                    return tituloA.localeCompare(tituloB);
                    
                case 'autor':
                    const autorA = a.querySelector('.card-text')?.textContent || '';
                    const autorB = b.querySelector('.card-text')?.textContent || '';
                    return autorA.localeCompare(autorB);
                    
                case 'estado':
                    const estadoA = a.querySelector('.badge')?.textContent || '';
                    const estadoB = b.querySelector('.badge')?.textContent || '';
                    return estadoA.localeCompare(estadoB);
                    
                case 'reciente':
                default:
                    return 0; // Mantener orden original
            }
        });
        
        cards.forEach(card => {
            container.appendChild(card);
        });
    }
    
    function ordenarFilas(filas, criterio, contenedor) {
        const tbody = document.querySelector(contenedor);
        
        filas.sort((a, b) => {
            switch (criterio) {
                case 'alfabetico':
                    const tituloA = a.querySelector('h6')?.textContent || '';
                    const tituloB = b.querySelector('h6')?.textContent || '';
                    return tituloA.localeCompare(tituloB);
                    
                case 'autor':
                    const autorA = a.cells[1]?.textContent || '';
                    const autorB = b.cells[1]?.textContent || '';
                    return autorA.localeCompare(autorB);
                    
                case 'estado':
                    const estadoA = a.querySelector('.badge')?.textContent || '';
                    const estadoB = b.querySelector('.badge')?.textContent || '';
                    return estadoA.localeCompare(estadoB);
                    
                case 'reciente':
                default:
                    return 0; // Mantener orden original
            }
        });
        
        filas.forEach(fila => {
            tbody.appendChild(fila);
        });
    }

    // Limpiar filtros función global
    window.limpiarFiltros = function() {
        if (buscarInput) buscarInput.value = '';
        if (filtroEstado) filtroEstado.value = '';
        if (ordenarPor) ordenarPor.value = 'reciente';
        
        if (buscarInput) {
            buscarInput.dispatchEvent(new Event('input'));
        }
    };
});
</script>

<!-- Modal para detalles del préstamo -->
<div class="modal fade" id="prestamoModal" tabindex="-1" aria-labelledby="prestamoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="prestamoModalLabel">
                    <i class="fas fa-book-open me-2"></i>Detalles del Préstamo
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="prestamoModalBody">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-3 text-muted">Cargando detalles del préstamo...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Limpiar modal cuando se cierre -->
<script>
document.getElementById('prestamoModal').addEventListener('hidden.bs.modal', function() {
    const modalBody = document.getElementById('prestamoModalBody');
    modalBody.innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="mt-3 text-muted">Cargando detalles del préstamo...</p>
        </div>
    `;
});
</script>

<?= $footer ?>