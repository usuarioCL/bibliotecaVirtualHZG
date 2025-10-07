<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container-fluid">
    <!-- Encabezado de la página con breadcrumb -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between mb-4">
                <div>
                    <h1 class="h3 mb-1 fw-bold text-dark">
                        <i class="ti ti-book-upload text-primary me-2"></i>
                        Devoluciones
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="#">Sistema de Préstamos</a></li>
                            <li class="breadcrumb-item active">Devoluciones</li>
                        </ol>
                    </nav>
                    <p class="text-muted mb-0 mt-1">Gestiona las devoluciones de libros y procesa multas por retrasos</p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <button type="button" class="btn btn-outline-secondary btn-sm">
                        <i class="ti ti-filter"></i> Filtrar
                    </button>
                    <button type="button" class="btn btn-primary btn-sm">
                        <i class="ti ti-book-upload"></i> Nueva Devolución
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas rápidas -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card success h-100 shadow-sm">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3">
                            <i class="ti ti-book-upload text-success" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-success mb-1"><?= isset($estadisticas['devoluciones_hoy']) ? number_format($estadisticas['devoluciones_hoy']) : 0 ?></h3>
                    <p class="text-muted mb-0 small">Devoluciones Hoy</p>
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
                    <h3 class="fw-bold text-danger mb-1"><?= isset($estadisticas['con_retraso']) ? number_format($estadisticas['con_retraso']) : 0 ?></h3>
                    <p class="text-muted mb-0 small">Con Retraso</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card warning h-100 shadow-sm">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                            <i class="ti ti-exclamation-circle text-warning" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-warning mb-1"><?= isset($estadisticas['danos_reportados']) ? number_format($estadisticas['danos_reportados']) : 0 ?></h3>
                    <p class="text-muted mb-0 small">Daños Reportados</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card info h-100 shadow-sm">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="rounded-circle bg-info bg-opacity-10 p-3">
                            <i class="ti ti-currency-dollar text-info" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-info mb-1"><?= isset($estadisticas['multas_generadas']) ? number_format($estadisticas['multas_generadas']) : 0 ?></h3>
                    <p class="text-muted mb-0 small">Multas Generadas</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de devoluciones con diseño mejorado -->
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title mb-0 fw-semibold">
                        <i class="ti ti-list text-primary me-2"></i>
                        Registro de Devoluciones
                    </h5>
                    <p class="text-muted small mb-0 mt-1">Historial de devoluciones procesadas en el sistema</p>
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
                <table class="table table-hover align-middle mb-0" id="tablaDevoluciones">
                    <thead class="table-light">
                        <tr class="text-uppercase small fw-semibold text-muted">
                            <th class="border-0 px-3 py-3">Préstamo</th>
                            <th class="border-0 px-3 py-3">Usuario</th>
                            <th class="border-0 px-3 py-3">Recurso</th>
                            <th class="border-0 px-3 py-3">Fechas</th>
                            <th class="border-0 text-center px-3 py-3">Estado</th>
                            <th class="border-0 text-center px-3 py-3">Multa</th>
                            <th class="border-0 text-center px-3 py-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($devoluciones)): ?>
                            <?php foreach ($devoluciones as $devolucion): ?>
                                <tr class="border-bottom">
                                    <td class="px-3 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <div class="rounded-2 bg-success bg-opacity-10 p-2">
                                                    <i class="ti ti-book-upload text-success fs-5"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <h6 class="mb-1 fw-semibold"><?= esc($devolucion['codigo_prestamo']) ?></h6>
                                                <p class="text-muted mb-0 small">ID: <?= esc($devolucion['id']) ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3">
                                        <div>
                                            <h6 class="mb-1 fw-medium"><?= esc($devolucion['usuario']) ?></h6>
                                            <p class="text-muted mb-0 small">CC: <?= esc($devolucion['documento']) ?></p>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3">
                                        <div>
                                            <h6 class="mb-1 fw-medium"><?= esc($devolucion['recurso']) ?></h6>
                                            <p class="text-muted mb-0 small">Estado: <?= esc($devolucion['estado_ejemplar']) ?></p>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3">
                                        <div>
                                            <p class="mb-1 small">
                                                <i class="ti ti-calendar-check me-1"></i>
                                                Devuelto: <?= date('d/m/Y H:i', strtotime($devolucion['fecha_devolucion'])) ?>
                                            </p>
                                            <p class="mb-0 small">
                                                <i class="ti ti-calendar-due me-1"></i>
                                                Vencía: <?= date('d/m/Y', strtotime($devolucion['fecha_vencimiento'])) ?>
                                            </p>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3 text-center">
                                        <?php if ($devolucion['dias_retraso'] == 0): ?>
                                            <span class="badge bg-success-subtle text-success">
                                                <i class="ti ti-check-circle me-1"></i>A Tiempo
                                            </span>
                                        <?php elseif ($devolucion['dias_retraso'] > 0): ?>
                                            <span class="badge bg-danger-subtle text-danger">
                                                <i class="ti ti-alert-circle me-1"></i>Con Retraso
                                            </span>
                                            <small class="d-block text-muted mt-1"><?= $devolucion['dias_retraso'] ?> día(s)</small>
                                        <?php else: ?>
                                            <span class="badge bg-info-subtle text-info">
                                                <i class="ti ti-clock me-1"></i>Temprana
                                            </span>
                                            <small class="d-block text-muted mt-1"><?= abs($devolucion['dias_retraso']) ?> día(s)</small>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-3 py-3 text-center">
                                        <?php if ($devolucion['multa'] > 0): ?>
                                            <span class="badge bg-warning-subtle text-warning">
                                                $<?= number_format($devolucion['multa']) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-success-subtle text-success">
                                                Sin multa
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-3 py-3 text-center">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                Acciones
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item" href="#" onclick="verDetalleDevolucion(<?= $devolucion['id'] ?>)">
                                                        <i class="ti ti-eye me-2"></i>Ver Detalles
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="#" onclick="imprimirRecibo(<?= $devolucion['id'] ?>)">
                                                        <i class="ti ti-printer me-2"></i>Imprimir Recibo
                                                    </a>
                                                </li>
                                                <?php if ($devolucion['multa'] > 0): ?>
                                                    <li>
                                                        <a class="dropdown-item" href="#" onclick="gestionarMulta(<?= $devolucion['id'] ?>)">
                                                            <i class="ti ti-currency-dollar me-2"></i>Gestionar Multa
                                                        </a>
                                                    </li>
                                                <?php endif; ?>
                                                <?php if (!empty($devolucion['observaciones'])): ?>
                                                    <li>
                                                        <a class="dropdown-item" href="#" onclick="verObservaciones(<?= $devolucion['id'] ?>)">
                                                            <i class="ti ti-note me-2"></i>Ver Observaciones
                                                        </a>
                                                    </li>
                                                <?php endif; ?>
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
                                            <i class="ti ti-book-off" style="font-size: 3rem; color: #6c757d;"></i>
                                        </div>
                                        <h5 class="empty-state-title text-muted">No hay devoluciones registradas</h5>
                                        <p class="empty-state-description text-muted mb-0">
                                            No se han procesado devoluciones recientemente
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
        <?php if (!empty($devoluciones)): ?>
        <div class="card-footer bg-light border-top-0">
            <div class="d-flex justify-content-between align-items-center text-muted small">
                <span>
                    <i class="ti ti-info-circle me-1"></i>
                    Mostrando <?= count($devoluciones) ?> de <?= count($devoluciones) ?> devoluciones
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
    // Función para ver detalles de la devolución
    function verDetalleDevolucion(devolucionId) {
        console.log('Ver detalles de devolución:', devolucionId);
        // TODO: Implementar modal de detalles
        Swal.fire({
            title: 'Detalles de la Devolución',
            text: 'Funcionalidad en desarrollo',
            icon: 'info'
        });
    }

    // Función para imprimir recibo
    function imprimirRecibo(devolucionId) {
        console.log('Imprimir recibo:', devolucionId);
        // TODO: Implementar generación de recibo
        Swal.fire({
            title: 'Generando Recibo',
            text: 'Se está generando el recibo de devolución...',
            icon: 'info',
            timer: 2000,
            showConfirmButton: false
        });
    }

    // Función para gestionar multa
    function gestionarMulta(devolucionId) {
        console.log('Gestionar multa:', devolucionId);
        
        Swal.fire({
            title: 'Gestión de Multa',
            html: `
                <div class="text-start">
                    <div class="mb-3">
                        <label class="form-label">Estado de la multa:</label>
                        <select class="form-select" id="estadoMulta">
                            <option value="pendiente">Pendiente</option>
                            <option value="pagada">Pagada</option>
                            <option value="condonada">Condonada</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Observaciones:</label>
                        <textarea class="form-control" id="observacionesMulta" rows="3" placeholder="Ingrese observaciones sobre la multa..."></textarea>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Guardar',
            cancelButtonText: 'Cancelar',
            preConfirm: () => {
                const estado = document.getElementById('estadoMulta').value;
                const observaciones = document.getElementById('observacionesMulta').value;
                
                return { estado, observaciones };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // TODO: Implementar actualización de multa
                Swal.fire({
                    title: 'Multa Actualizada',
                    text: 'El estado de la multa ha sido actualizado exitosamente',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        });
    }

    // Función para ver observaciones
    function verObservaciones(devolucionId) {
        console.log('Ver observaciones:', devolucionId);
        
        // Buscar las observaciones en los datos (simulado)
        const observaciones = "Devolución realizada en perfecto estado. Usuario muy responsable."; // TODO: obtener de datos reales
        
        Swal.fire({
            title: 'Observaciones de la Devolución',
            html: `
                <div class="text-start">
                    <div class="alert alert-info">
                        <i class="ti ti-info-circle me-2"></i>
                        ${observaciones}
                    </div>
                </div>
            `,
            confirmButtonText: 'Cerrar'
        });
    }

    // Función para procesar nueva devolución (modal)
    function nuevaDevolucion() {
        Swal.fire({
            title: 'Nueva Devolución',
            html: `
                <div class="text-start">
                    <div class="mb-3">
                        <label class="form-label">Código del Préstamo:</label>
                        <input type="text" class="form-control" id="codigoPrestamo" placeholder="Ingrese el código del préstamo">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Estado del Ejemplar:</label>
                        <select class="form-select" id="estadoEjemplar">
                            <option value="excelente">Excelente</option>
                            <option value="bueno">Bueno</option>
                            <option value="regular">Regular</option>
                            <option value="malo">Malo</option>
                            <option value="dañado">Dañado</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Observaciones:</label>
                        <textarea class="form-control" id="observaciones" rows="3" placeholder="Observaciones adicionales..."></textarea>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Procesar Devolución',
            cancelButtonText: 'Cancelar',
            width: 600,
            preConfirm: () => {
                const codigo = document.getElementById('codigoPrestamo').value;
                const estado = document.getElementById('estadoEjemplar').value;
                const observaciones = document.getElementById('observaciones').value;
                
                if (!codigo) {
                    Swal.showValidationMessage('Debe ingresar el código del préstamo');
                    return false;
                }
                
                return { codigo, estado, observaciones };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // TODO: Implementar procesamiento de nueva devolución
                Swal.fire({
                    title: 'Devolución Procesada',
                    text: 'La devolución ha sido procesada exitosamente',
                    icon: 'success'
                }).then(() => {
                    location.reload();
                });
            }
        });
    }
</script>