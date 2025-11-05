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
                        <i class="fas fa-heart me-3"></i>Mis Favoritos
                    </h1>
                    <p class="text-muted mb-0">Tu biblioteca personal de libros favoritos</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-5">
            <div class="d-flex justify-content-end align-items-center h-100">
                <div class="card bg-danger bg-gradient text-white border-0 shadow-sm">
                    <div class="card-body text-center py-3 px-4">
                        <div class="d-flex align-items-center justify-content-center">
                            <i class="fas fa-heart fa-2x me-3"></i>
                            <div>
                                <small class="text-white-50 d-block">Total Favoritos</small>
                                <h3 class="text-white mb-0 fw-bold" id="contadorFavoritos"><?= $contadorFavoritos ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido de favoritos -->
    <?php if (!empty($favoritos)): ?>
        <!-- Vista Lista de favoritos -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0 fw-semibold">Libro</th>
                                <th class="border-0 fw-semibold">Autor</th>
                                <th class="border-0 fw-semibold">Categoría</th>
                                <th class="border-0 fw-semibold">Estado</th>
                                <th class="border-0 fw-semibold text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="favoritosLista">
                            <?php foreach ($favoritos as $favorito): ?>
                                <tr class="align-middle">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php if (!empty($favorito['portada'])): ?>
                                                <img src="<?= base_url($favorito['portada']) ?>" class="rounded me-3" style="width: 40px; height: 50px; object-fit: cover;" alt="Portada">
                                            <?php else: ?>
                                                <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 50px;">
                                                    <i class="fas fa-book text-muted"></i>
                                                </div>
                                            <?php endif; ?>
                                            <div>
                                                <h6 class="mb-0 fw-semibold"><?= esc($favorito['titulo']) ?></h6>
                                                <?php if (!empty($favorito['isbn'])): ?>
                                                    <small class="text-muted">ISBN: <?= esc($favorito['isbn']) ?></small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-muted"><?= esc($favorito['nomautor'] ?: 'Sin autor') ?></td>
                                    <td>
                                        <?php if (!empty($favorito['categoria'])): ?>
                                            <span class="badge bg-primary"><?= esc($favorito['categoria']) ?></span>
                                            <?php if (!empty($favorito['subcategoria'])): ?>
                                                <br><small class="text-muted"><?= esc($favorito['subcategoria']) ?></small>
                                            <?php endif; ?>
                                        <?php elseif (!empty($favorito['subcategoria'])): ?>
                                            <span class="badge bg-info"><?= esc($favorito['subcategoria']) ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Sin categoría</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($favorito['estado'] === 'disponible'): ?>
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle me-1"></i>Disponible
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">
                                                <i class="fas fa-ban me-1"></i>No disponible
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2 justify-content-center">
                                            <button class="btn btn-sm btn-primary" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#libroModal"
                                                    onclick="cargarDetallesLibro(<?= $favorito['idrecurso'] ?>)" 
                                                    title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <?php if ($favorito['estado'] === 'disponible'): ?>
                                                <button class="btn btn-sm btn-success" 
                                                        onclick="solicitarPrestamo(<?= $favorito['idrecurso'] ?>)" 
                                                        title="Solicitar préstamo">
                                                    <i class="fas fa-book"></i>
                                                </button>
                                            <?php else: ?>
                                                <button class="btn btn-sm btn-secondary" disabled title="No disponible">
                                                    <i class="fas fa-ban"></i>
                                                </button>
                                            <?php endif; ?>
                                            <button class="btn btn-sm btn-danger" 
                                                    onclick="quitarFavorito(<?= $favorito['idfavorito'] ?>, <?= $favorito['idrecurso'] ?>)" 
                                                    title="Quitar de favoritos">
                                                <i class="fas fa-heart-broken"></i>
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
    <?php else: ?>
        <!-- Mensaje cuando no hay favoritos -->
        <div class="row" id="sinFavoritos">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-heart fa-4x text-danger opacity-50"></i>
                        </div>
                        <h4 class="text-muted mb-3">No tienes libros favoritos</h4>
                        <p class="text-muted mb-4 lead">¡Explora nuestro catálogo y marca tus libros favoritos!</p>
                        
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

<script>
// Función para cargar detalles del libro
function cargarDetallesLibro(idRecurso) {
    const modalBody = document.getElementById('libroModalBody');
    
    // Mostrar loading
    modalBody.innerHTML = `
        <div class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="mt-2">Cargando detalles del recurso...</p>
        </div>
    `;
    
    // Cargar detalles via AJAX
    fetch(`<?= base_url('recurso/detalles/') ?>${idRecurso}`)
        .then(response => response.text())
        .then(html => {
            modalBody.innerHTML = html;
        })
        .catch(error => {
            console.error('Error al cargar detalles:', error);
            modalBody.innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Error al cargar los detalles del recurso.
                </div>
            `;
        });
}

// Función para quitar favorito
function quitarFavorito(idfavorito, idrecurso) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: '¿Quitar de favoritos?',
            text: '¿Estás seguro de que quieres quitar este libro de tus favoritos?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, quitar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d'
        }).then((result) => {
            if (result.isConfirmed) {
                procesarQuitarFavorito(idfavorito);
            }
        });
    } else {
        if (confirm('¿Estás seguro de que quieres quitar este libro de favoritos?')) {
            procesarQuitarFavorito(idfavorito);
        }
    }
}

function procesarQuitarFavorito(idfavorito) {
    fetch('<?= base_url('catalogo/quitar-favorito') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({idfavorito: idfavorito})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: '¡Eliminado!',
                    text: data.message,
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            } else {
                alert(data.message);
                location.reload();
            }
        } else {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Error',
                    text: data.message || 'Error al quitar de favoritos',
                    icon: 'error'
                });
            } else {
                alert('Error: ' + (data.message || 'Error desconocido'));
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

function solicitarPrestamo(idrecurso) {
    // Verificar sanciones antes de solicitar préstamo
    fetch('<?= base_url('prestamo/verificar-sanciones') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.sancionado) {
            // Usuario con sanciones
            let sancionesHtml = '<div class="alert alert-danger mb-0"><strong>Sanciones activas:</strong><ul class="mb-0 mt-2">';
            data.sanciones.forEach(sancion => {
                sancionesHtml += `<li><strong>${sancion.tipo}:</strong> ${sancion.detalle}`;
                if (sancion.fecha_vencimiento) {
                    const fechaVenc = new Date(sancion.fecha_vencimiento);
                    sancionesHtml += `<br><small>Vence: ${fechaVenc.toLocaleDateString('es-ES')}</small>`;
                }
                sancionesHtml += '</li>';
            });
            sancionesHtml += '</ul></div>';
            
            Swal.fire({
                title: 'No puede solicitar préstamos',
                html: sancionesHtml + '<p class="mt-3 mb-0">Tiene sanciones activas y no puede solicitar préstamos hasta que se resuelvan.</p>',
                icon: 'warning',
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#dc3545'
            });
        } else if (data.success && !data.sancionado) {
            // Sin sanciones, redirigir al formulario de préstamo
            window.location.href = `<?= base_url('catalogo/solicitar-prestamo/') ?>${idrecurso}`;
        } else {
            Swal.fire({
                title: 'Error',
                text: data.message || 'No se pudo verificar su estado',
                icon: 'error'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            title: 'Error',
            text: 'Error de conexión. Intente nuevamente.',
            icon: 'error'
        });
    });
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('Vista de favoritos cargada correctamente');
});
</script>

<!-- Modal para detalles del libro -->
<div class="modal fade" id="libroModal" tabindex="-1" aria-labelledby="libroModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="libroModalLabel">
                    <i class="fas fa-book-open me-2"></i>Detalles del Recurso
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="libroModalBody">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-3 text-muted">Cargando detalles del recurso...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Limpiar modal cuando se cierre -->
<script>
document.getElementById('libroModal').addEventListener('hidden.bs.modal', function() {
    const modalBody = document.getElementById('libroModalBody');
    modalBody.innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="mt-3 text-muted">Cargando detalles del recurso...</p>
        </div>
    `;
});
</script>

<?= $footer ?>