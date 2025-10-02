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
                            <h4 class="text-primary mb-0" id="contadorActivos"><?= $contadorActivos ?></h4>
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
                <?php if (!empty($prestamosActivos)): ?>
                    <?php foreach ($prestamosActivos as $prestamo): ?>
                        <?= view('partials/prestamo_card', ['prestamo' => $prestamo]) ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Mensaje cuando no hay préstamos activos -->
            <div class="row <?= empty($prestamosActivos) ? '' : 'd-none' ?>" id="sinPrestamosActivos">
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">No tienes préstamos activos</h4>
                        <p class="text-muted mb-4">¡Explora nuestro catálogo y encuentra tu próximo libro!</p>
                        <?php if (session()->get('nivel') === 'admin'): ?>
                            <div class="alert alert-info mb-3">
                                <strong>Modo Admin:</strong> Para probar la funcionalidad, puedes 
                                <a href="<?= site_url('catalogo/insertar-datos-prueba') ?>" class="alert-link">insertar datos de prueba</a>
                            </div>
                        <?php endif; ?>
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
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="historialPrestamos">
                        <?php if (!empty($historialPrestamos)): ?>
                            <?php foreach ($historialPrestamos as $prestamo): ?>
                                <tr>
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
                                                <h6 class="mb-0"><?= esc($prestamo['titulo']) ?></h6>
                                                <?php if (!empty($prestamo['isbn'])): ?>
                                                    <small class="text-muted">ISBN: <?= esc($prestamo['isbn']) ?></small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= esc($prestamo['nomautor'] ?: 'Sin autor') ?></td>
                                    <td><?= date('d/M/Y', strtotime($prestamo['fechaprestamo'])) ?></td>
                                    <td>
                                        <?php if (!empty($prestamo['fechahoraretorno'])): ?>
                                            <?= date('d/M/Y', strtotime($prestamo['fechahoraretorno'])) ?>
                                        <?php elseif (!empty($prestamo['fechadevolucion'])): ?>
                                            <small class="text-muted">Vence: <?= date('d/M/Y', strtotime($prestamo['fechadevolucion'])) ?></small>
                                        <?php else: ?>
                                            <small class="text-muted">Sin fecha</small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($prestamo['fechahoraretorno'])): ?>
                                            <span class="badge bg-success">Devuelto</span>
                                        <?php elseif (!empty($prestamo['fechadevolucion']) && strtotime($prestamo['fechadevolucion']) < time()): ?>
                                            <span class="badge bg-danger">Vencido</span>
                                        <?php else: ?>
                                            <span class="badge bg-primary">Activo</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="fas fa-history fa-2x mb-2"></i>
                                    <br>No hay historial de préstamos
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Paginación del historial -->
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
// Funciones globales para los botones de préstamo
function renovarPrestamo(idPrestamo) {
    if (confirm('¿Deseas renovar este préstamo?')) {
        // Aquí iría la lógica AJAX para renovar
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
                alert('Préstamo renovado exitosamente');
                location.reload();
            } else {
                alert('Error al renovar: ' + (data.message || 'Error desconocido'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error de conexión');
        });
    }
}

function devolverPrestamo(idPrestamo) {
    if (confirm('¿Confirmas la devolución de este libro?')) {
        // Aquí iría la lógica AJAX para devolver
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
                alert('Libro devuelto exitosamente');
                location.reload();
            } else {
                alert('Error al devolver: ' + (data.message || 'Error desconocido'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error de conexión');
        });
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Funcionalidad de búsqueda
    const buscarInput = document.getElementById('buscarPrestamos');
    const filtroEstado = document.getElementById('filtroEstado');

    if (buscarInput) {
        buscarInput.addEventListener('input', filtrarPrestamos);
    }
    if (filtroEstado) {
        filtroEstado.addEventListener('change', filtrarPrestamos);
    }

    function filtrarPrestamos() {
        const busqueda = buscarInput.value.toLowerCase();
        const estado = filtroEstado.value;
        
        // Filtrar cards de préstamos activos
        document.querySelectorAll('#prestamosActivos .card').forEach(card => {
            const titulo = card.querySelector('.card-title').textContent.toLowerCase();
            const autor = card.querySelector('.card-text').textContent.toLowerCase();
            const badge = card.querySelector('.badge');
            const estadoCard = badge ? badge.textContent.toLowerCase() : '';
            
            const coincideBusqueda = titulo.includes(busqueda) || autor.includes(busqueda);
            const coincidenEstado = !estado || estadoCard.includes(estado.toLowerCase());
            
            card.closest('.col-lg-6').style.display = (coincideBusqueda && coincidenEstado) ? 'block' : 'none';
        });
        
        // Filtrar filas del historial
        document.querySelectorAll('#historialPrestamos tr').forEach(row => {
            if (row.querySelector('td')) {
                const titulo = row.querySelector('h6').textContent.toLowerCase();
                const autor = row.cells[1].textContent.toLowerCase();
                const badge = row.querySelector('.badge');
                const estadoRow = badge ? badge.textContent.toLowerCase() : '';
                
                const coincideBusqueda = titulo.includes(busqueda) || autor.includes(busqueda);
                const coincidenEstado = !estado || estadoRow.includes(estado.toLowerCase());
                
                row.style.display = (coincideBusqueda && coincidenEstado) ? 'table-row' : 'none';
            }
        });
    }
});
</script>

<?= $footer ?>