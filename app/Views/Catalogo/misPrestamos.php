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

    <!-- Contenido de pestañas -->
    <div class="tab-content" id="prestamosTabContent">
        <!-- Préstamos Activos -->
        <div class="tab-pane fade show active" id="activos" role="tabpanel">
            <?php if (!empty($prestamosActivos)): ?>
                <!-- Vista Lista de préstamos activos -->
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
                                <tbody id="prestamosActivosLista">
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
                                                <div class="d-flex gap-2 justify-content-center">
                                                    <button class="btn btn-sm btn-primary" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#prestamoModal"
                                                            onclick="cargarDetallesPrestamo(<?= $prestamo['idprestamo'] ?>)" 
                                                            title="Ver detalles">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-info text-white" 
                                                            onclick="renovarPrestamo(<?= $prestamo['idprestamo'] ?>)" 
                                                            title="Renovar">
                                                        <i class="fas fa-redo"></i>
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
                                                <button class="btn btn-sm btn-primary" 
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
    // Cargar el formulario de renovación vía AJAX
    fetch(`<?= base_url('prestamo/formulario-renovacion/') ?>${idPrestamo}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al cargar el formulario');
            }
            return response.text();
        })
        .then(html => {
            // Mostrar el formulario en un modal de SweetAlert
            Swal.fire({
                title: '<i class="fas fa-redo me-2"></i>Renovar Préstamo',
                html: html,
                width: '800px',
                showConfirmButton: false,
                showCloseButton: true,
                customClass: {
                    popup: 'swal-wide',
                    htmlContainer: 'swal-html-container-custom'
                },
                didOpen: () => {
                    // El formulario ya tiene sus propios botones de acción
                }
            });
        })
        .catch(error => {
            console.error('Error al cargar formulario de renovación:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo cargar el formulario de renovación. Por favor, intente nuevamente.',
                confirmButtonColor: '#dc3545'
            });
        });
}

/**
 * Validar que la fecha de devolución esté dentro del rango permitido
 */
function validarFechaDevolucion() {
    const fechaInicio = document.getElementById('nuevaFechaPrestamo');
    const fechaDevolucion = document.getElementById('nuevaFechaDevolucion');
    
    if (!fechaInicio || !fechaDevolucion || !fechaInicio.value || !fechaDevolucion.value) {
        return;
    }
    
    const inicio = new Date(fechaInicio.value + 'T00:00:00');
    const devolucion = new Date(fechaDevolucion.value + 'T00:00:00');
    
    // Calcular la diferencia en días
    const diffTime = devolucion - inicio;
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    
    // Validar que esté entre 0 y 7 días
    if (diffDays < 0) {
        fechaDevolucion.setCustomValidity('La fecha de devolución no puede ser anterior a la fecha de inicio');
        Swal.fire({
            icon: 'warning',
            title: 'Fecha inválida',
            text: 'La fecha de devolución no puede ser anterior a la fecha de inicio',
            confirmButtonColor: '#f39c12'
        });
        // Resetear a la fecha máxima permitida
        const maxDate = new Date(inicio);
        maxDate.setDate(maxDate.getDate() + 7);
        fechaDevolucion.value = maxDate.toISOString().split('T')[0];
    } else if (diffDays > 7) {
        fechaDevolucion.setCustomValidity('No puede extender el préstamo por más de 7 días');
        Swal.fire({
            icon: 'warning',
            title: 'Período máximo excedido',
            text: 'La renovación no puede extender el préstamo por más de 7 días',
            confirmButtonColor: '#f39c12'
        });
        // Resetear a la fecha máxima permitida
        const maxDate = new Date(inicio);
        maxDate.setDate(maxDate.getDate() + 7);
        fechaDevolucion.value = maxDate.toISOString().split('T')[0];
    } else {
        fechaDevolucion.setCustomValidity('');
    }
}

/**
 * Función para enviar la renovación del préstamo
 */
function enviarRenovacionPrestamo() {
    const form = document.getElementById('formRenovacionPrestamo');
    if (!form) {
        console.error('No se encontró el formulario de renovación');
        return;
    }
    
    const formData = new FormData(form);
    const data = {};
    formData.forEach((value, key) => {
        data[key] = value;
    });
    
    // Debug: Verificar datos recopilados
    console.log('Datos del formulario:', data);
    
    // Validar que tenemos el idprestamo
    if (!data.idprestamo) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se encontró el ID del préstamo',
            confirmButtonColor: '#dc3545'
        });
        return;
    }
    
    // Calcular días de extensión reales
    const fechaInicio = document.getElementById('nuevaFechaPrestamo');
    const fechaDevolucion = document.getElementById('nuevaFechaDevolucion');
    
    let diasExtension = 7; // valor por defecto
    let mensajeExtension = 'El préstamo se extenderá por 7 días más';
    
    if (fechaInicio && fechaDevolucion && fechaInicio.value && fechaDevolucion.value) {
        const inicio = new Date(fechaInicio.value + 'T00:00:00');
        const fin = new Date(fechaDevolucion.value + 'T00:00:00');
        const diffTime = fin - inicio;
        diasExtension = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        
        if (diasExtension === 1) {
            mensajeExtension = 'El préstamo se extenderá por 1 día más';
        } else {
            mensajeExtension = `El préstamo se extenderá por ${diasExtension} días más`;
        }
    }
    
    // Determinar la URL según el nivel de acceso del usuario
    const nivelAcceso = '<?= session()->get("nivelacceso") ?>';
    const urlRenovacion = (nivelAcceso === 'admin' || nivelAcceso === 'docente') 
        ? '<?= base_url('prestamo/renovar') ?>'
        : '<?= base_url('prestamo/solicitar-renovacion') ?>';
    
    // Mensaje de confirmación según el tipo de acción
    const tituloConfirmacion = (nivelAcceso === 'admin' || nivelAcceso === 'docente')
        ? '¿Confirmar renovación?'
        : '¿Enviar solicitud de renovación?';
    
    const textoConfirmacion = (nivelAcceso === 'admin' || nivelAcceso === 'docente')
        ? mensajeExtension
        : mensajeExtension + '. La solicitud será revisada por un administrador.';
    
    const botonConfirmar = (nivelAcceso === 'admin' || nivelAcceso === 'docente')
        ? 'Sí, renovar'
        : 'Sí, enviar solicitud';
    
    Swal.fire({
        title: tituloConfirmacion,
        text: textoConfirmacion,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: botonConfirmar,
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        showLoaderOnConfirm: true,
        preConfirm: () => {
            return fetch(urlRenovacion, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    throw new Error(data.message || 'Error desconocido');
                }
                return data;
            })
            .catch(error => {
                Swal.showValidationMessage(`Error: ${error.message}`);
            });
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed && result.value) {
            const esSolicitud = result.value.tipo === 'solicitud';
            const titulo = esSolicitud ? '¡Solicitud Enviada!' : '¡Renovado!';
            const icono = esSolicitud ? 'info' : 'success';
            
            Swal.fire({
                icon: icono,
                title: titulo,
                text: result.value.message,
                confirmButtonColor: '#28a745',
                timer: esSolicitud ? 3000 : 2000
            }).then(() => {
                window.location.reload();
            });
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
    // No se necesitan filtros ni búsqueda para solo 2 préstamos activos máximo
    console.log('Vista de préstamos cargada correctamente');
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