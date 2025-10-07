<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container-fluid">
    <!-- Encabezado de la página con breadcrumb -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between mb-4">
                <div>
                    <h1 class="h3 mb-1 fw-bold text-dark">
                        <i class="ti ti-bookmark text-primary me-2"></i>
                        Préstamos Activos
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="#">Sistema de Préstamos</a></li>
                            <li class="breadcrumb-item active">Préstamos Activos</li>
                        </ol>
                    </nav>
                    <p class="text-muted mb-0 mt-1">Gestiona todos los préstamos activos del sistema bibliotecario</p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <button type="button" class="btn btn-outline-secondary btn-sm">
                        <i class="ti ti-filter"></i> Filtrar
                    </button>
                    <button type="button" class="btn btn-primary btn-sm">
                        <i class="ti ti-plus"></i> Nuevo Préstamo
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas rápidas -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card primary h-100 shadow-sm">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                            <i class="ti ti-bookmark text-primary" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-primary mb-1"><?= isset($estadisticas['total_prestamos']) ? number_format($estadisticas['total_prestamos']) : 0 ?></h3>
                    <p class="text-muted mb-0 small">Total Préstamos</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card danger h-100 shadow-sm">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="rounded-circle bg-danger bg-opacity-10 p-3">
                            <i class="ti ti-alert-circle text-danger" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-danger mb-1"><?= isset($estadisticas['vencidos_hoy']) ? number_format($estadisticas['vencidos_hoy']) : 0 ?></h3>
                    <p class="text-muted mb-0 small">Vencidos Hoy</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card warning h-100 shadow-sm">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                            <i class="ti ti-clock text-warning" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-warning mb-1"><?= isset($estadisticas['proximos_vencer']) ? number_format($estadisticas['proximos_vencer']) : 0 ?></h3>
                    <p class="text-muted mb-0 small">Próximos a Vencer</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card info h-100 shadow-sm">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="rounded-circle bg-info bg-opacity-10 p-3">
                            <i class="ti ti-refresh text-info" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-info mb-1"><?= isset($estadisticas['renovaciones_pendientes']) ? number_format($estadisticas['renovaciones_pendientes']) : 0 ?></h3>
                    <p class="text-muted mb-0 small">Renovaciones Pendientes</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de préstamos con diseño mejorado -->
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title mb-0 fw-semibold">
                        <i class="ti ti-list text-primary me-2"></i>
                        Lista de Préstamos Activos
                    </h5>
                    <p class="text-muted small mb-0 mt-1">Gestiona todos los préstamos activos del sistema</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-secondary btn-sm" type="button">
                        <i class="ti ti-download me-1"></i>Exportar
                    </button>
                    <button class="btn btn-outline-secondary btn-sm" type="button" onclick="location.reload()">
                        <i class="ti ti-refresh me-1"></i>Actualizar
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tablaPrestamos">
                    <thead class="table-light">
                        <tr class="text-uppercase small fw-semibold text-muted">
                            <th class="border-0 px-3 py-3">Código</th>
                            <th class="border-0 px-3 py-3">Usuario</th>
                            <th class="border-0 px-3 py-3">Recurso</th>
                            <th class="border-0 px-3 py-3">Fechas</th>
                            <th class="border-0 text-center px-3 py-3">Estado</th>
                            <th class="border-0 text-center px-3 py-3">Renovaciones</th>
                            <th class="border-0 text-center px-3 py-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($prestamos)): ?>
                            <?php foreach ($prestamos as $prestamo): ?>
                                <tr class="border-bottom">
                                    <td class="px-3 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <div class="rounded-2 bg-primary bg-opacity-10 p-2">
                                                    <i class="ti ti-bookmark text-primary fs-5"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <h6 class="mb-1 fw-semibold"><?= esc($prestamo['codigo_prestamo']) ?></h6>
                                                <p class="text-muted mb-0 small"><?= esc($prestamo['codigo_ejemplar']) ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3">
                                        <div>
                                            <h6 class="mb-1 fw-medium"><?= esc($prestamo['usuario']) ?></h6>
                                            <p class="text-muted mb-0 small">CC: <?= esc($prestamo['documento']) ?></p>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3">
                                        <div>
                                            <h6 class="mb-1 fw-medium"><?= esc($prestamo['recurso']) ?></h6>
                                            <p class="text-muted mb-0 small">Código: <?= esc($prestamo['codigo_ejemplar']) ?></p>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3">
                                        <div>
                                            <p class="mb-1 small">
                                                <i class="ti ti-calendar-event me-1"></i>
                                                Préstamo: <?= date('d/m/Y', strtotime($prestamo['fecha_prestamo'])) ?>
                                            </p>
                                            <p class="mb-0 small">
                                                <i class="ti ti-calendar-due me-1"></i>
                                                Vence: <?= date('d/m/Y', strtotime($prestamo['fecha_vencimiento'])) ?>
                                            </p>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3 text-center">
                                        <?php if ($prestamo['estado'] == 'Activo'): ?>
                                            <?php if ($prestamo['dias_restantes'] > 3): ?>
                                                <span class="badge bg-success-subtle text-success">
                                                    <i class="ti ti-check-circle me-1"></i><?= esc($prestamo['estado']) ?>
                                                </span>
                                                <small class="d-block text-muted mt-1"><?= $prestamo['dias_restantes'] ?> días</small>
                                            <?php elseif ($prestamo['dias_restantes'] > 0): ?>
                                                <span class="badge bg-warning-subtle text-warning">
                                                    <i class="ti ti-alert-triangle me-1"></i>Por Vencer
                                                </span>
                                                <small class="d-block text-muted mt-1"><?= $prestamo['dias_restantes'] ?> días</small>
                                            <?php else: ?>
                                                <span class="badge bg-danger-subtle text-danger">
                                                    <i class="ti ti-x-circle me-1"></i>Vencido
                                                </span>
                                                <small class="d-block text-muted mt-1"><?= abs($prestamo['dias_restantes']) ?> días</small>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="badge bg-danger-subtle text-danger">
                                                <i class="ti ti-x-circle me-1"></i><?= esc($prestamo['estado']) ?>
                                            </span>
                                            <small class="d-block text-muted mt-1"><?= abs($prestamo['dias_restantes']) ?> días</small>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-3 py-3 text-center">
                                        <span class="badge bg-info-subtle text-info">
                                            <?= $prestamo['renovaciones'] ?> renovaciones
                                        </span>
                                    </td>
                                    <td class="px-3 py-3 text-center">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                Acciones
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item" href="#" onclick="verDetallePrestamo(<?= $prestamo['id'] ?>)">
                                                        <i class="ti ti-eye me-2"></i>Ver Detalles
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="#" onclick="renovarPrestamo(<?= $prestamo['id'] ?>)">
                                                        <i class="ti ti-refresh me-2"></i>Renovar
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="#" onclick="procesarDevolucion(<?= $prestamo['id'] ?>)">
                                                        <i class="ti ti-book-upload me-2"></i>Procesar Devolución
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <a class="dropdown-item text-danger" href="#" onclick="cancelarPrestamo(<?= $prestamo['id'] ?>)">
                                                        <i class="ti ti-x me-2"></i>Cancelar Préstamo
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="empty-state">
                                        <div class="empty-state-icon mb-3">
                                            <i class="ti ti-bookmark-off" style="font-size: 3rem; color: #6c757d;"></i>
                                        </div>
                                        <h5 class="empty-state-title text-muted">No hay préstamos activos</h5>
                                        <p class="empty-state-description text-muted mb-0">
                                            Actualmente no existen préstamos activos en el sistema
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Footer de la tarjeta con información adicional -->
        <?php if (!empty($prestamos)): ?>
        <div class="card-footer bg-light border-top-0">
            <div class="d-flex justify-content-between align-items-center text-muted small">
                <span>
                    <i class="ti ti-info-circle me-1"></i>
                    Mostrando <?= count($prestamos) ?> de <?= count($prestamos) ?> préstamos
                </span>
                <span>
                    <i class="ti ti-clock me-1"></i>
                    Actualizado: <?= date('d/m/Y H:i') ?>
                </span>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
    // Función para ver detalles del préstamo
    function verDetallePrestamo(prestamoId) {
        console.log('Ver detalles del préstamo:', prestamoId);
        // TODO: Implementar modal de detalles
        Swal.fire({
            title: 'Detalles del Préstamo',
            text: 'Funcionalidad en desarrollo',
            icon: 'info'
        });
    }

    // Función para renovar préstamo
    function renovarPrestamo(prestamoId) {
        console.log('Renovar préstamo:', prestamoId);
        // TODO: Implementar renovación
        Swal.fire({
            title: '¿Renovar Préstamo?',
            text: '¿Estás seguro de que deseas renovar este préstamo?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, renovar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Renovado', 'El préstamo ha sido renovado exitosamente', 'success');
            }
        });
    }

    // Función para procesar devolución
    function procesarDevolucion(prestamoId) {
        console.log('Procesar devolución:', prestamoId);
        // TODO: Implementar procesamiento de devolución
        Swal.fire({
            title: 'Procesar Devolución',
            text: 'Funcionalidad en desarrollo',
            icon: 'info'
        });
    }

    // Función para cancelar préstamo
    function cancelarPrestamo(prestamoId) {
        console.log('Cancelar préstamo:', prestamoId);
        Swal.fire({
            title: '¿Cancelar Préstamo?',
            text: 'Esta acción no se puede deshacer',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, cancelar',
            cancelButtonText: 'No cancelar',
            confirmButtonColor: '#d33'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Cancelado', 'El préstamo ha sido cancelado', 'success');
            }
        });
    }
</script>