<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container-fluid">
    <!-- Encabezado de la página con breadcrumb -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between mb-4">
                <div>
                    <h1 class="h3 mb-1 fw-bold text-dark">
                        <i class="ti ti-clock-hour-3 text-primary me-2"></i>
                        Solicitudes Pendientes
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="#">Sistema de Préstamos</a></li>
                            <li class="breadcrumb-item active">Solicitudes Pendientes</li>
                        </ol>
                    </nav>
                    <p class="text-muted mb-0 mt-1">Gestiona las solicitudes de préstamos pendientes de aprobación</p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <button type="button" class="btn btn-outline-secondary btn-sm">
                        <i class="ti ti-filter"></i> Filtrar
                    </button>
                    <button type="button" class="btn btn-success btn-sm" onclick="aprobarTodas()">
                        <i class="ti ti-check-all"></i> Aprobar Disponibles
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
                            <i class="ti ti-clock text-primary" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-primary mb-1"><?= isset($estadisticas['total_solicitudes']) ? number_format($estadisticas['total_solicitudes']) : 0 ?></h3>
                    <p class="text-muted mb-0 small">Total Solicitudes</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card info h-100 shadow-sm">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="rounded-circle bg-info bg-opacity-10 p-3">
                            <i class="ti ti-calendar-today text-info" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-info mb-1"><?= isset($estadisticas['hoy']) ? number_format($estadisticas['hoy']) : 0 ?></h3>
                    <p class="text-muted mb-0 small">Solicitudes Hoy</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card warning h-100 shadow-sm">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                            <i class="ti ti-calendar-week text-warning" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-warning mb-1"><?= isset($estadisticas['esta_semana']) ? number_format($estadisticas['esta_semana']) : 0 ?></h3>
                    <p class="text-muted mb-0 small">Esta Semana</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card success h-100 shadow-sm">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3">
                            <i class="ti ti-hourglass text-success" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-success mb-1"><?= isset($estadisticas['esperando_aprobacion']) ? number_format($estadisticas['esperando_aprobacion']) : 0 ?></h3>
                    <p class="text-muted mb-0 small">Esperando Aprobación</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de solicitudes con diseño mejorado -->
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title mb-0 fw-semibold">
                        <i class="ti ti-list text-primary me-2"></i>
                        Lista de Solicitudes Pendientes
                    </h5>
                    <p class="text-muted small mb-0 mt-1">Gestiona las solicitudes que requieren aprobación</p>
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
                <table class="table table-hover align-middle mb-0" id="tablaSolicitudes">
                    <thead class="table-light">
                        <tr class="text-uppercase small fw-semibold text-muted">
                            <th class="border-0 px-3 py-3">Usuario</th>
                            <th class="border-0 px-3 py-3">Recurso Solicitado</th>
                            <th class="border-0 px-3 py-3">Fecha Solicitud</th>
                            <th class="border-0 text-center px-3 py-3">Prioridad</th>
                            <th class="border-0 text-center px-3 py-3">Disponibilidad</th>
                            <th class="border-0 text-center px-3 py-3">Estado</th>
                            <th class="border-0 text-center px-3 py-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($solicitudes)): ?>
                            <?php foreach ($solicitudes as $solicitud): ?>
                                <tr class="border-bottom">
                                    <td class="px-3 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <div class="rounded-2 bg-primary bg-opacity-10 p-2">
                                                    <i class="ti ti-user text-primary fs-5"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <h6 class="mb-1 fw-semibold"><?= esc($solicitud['usuario']) ?></h6>
                                                <p class="text-muted mb-0 small">CC: <?= esc($solicitud['documento']) ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3">
                                        <div>
                                            <h6 class="mb-1 fw-medium"><?= esc($solicitud['recurso']) ?></h6>
                                            <p class="text-muted mb-0 small">Código: <?= esc($solicitud['codigo_ejemplar']) ?></p>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3">
                                        <div>
                                            <p class="mb-1 small">
                                                <i class="ti ti-calendar-event me-1"></i>
                                                <?= date('d/m/Y', strtotime($solicitud['fecha_solicitud'])) ?>
                                            </p>
                                            <p class="mb-0 small text-muted">
                                                <i class="ti ti-clock me-1"></i>
                                                <?= date('H:i', strtotime($solicitud['fecha_solicitud'])) ?>
                                            </p>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3 text-center">
                                        <?php if ($solicitud['prioridad'] == 'Alta'): ?>
                                            <span class="badge bg-danger-subtle text-danger">
                                                <i class="ti ti-alert-circle me-1"></i>Alta
                                            </span>
                                        <?php elseif ($solicitud['prioridad'] == 'Media'): ?>
                                            <span class="badge bg-warning-subtle text-warning">
                                                <i class="ti ti-alert-triangle me-1"></i>Media
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-info-subtle text-info">
                                                <i class="ti ti-info-circle me-1"></i>Normal
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-3 py-3 text-center">
                                        <?php if ($solicitud['disponible']): ?>
                                            <span class="badge bg-success-subtle text-success">
                                                <i class="ti ti-check-circle me-1"></i>Disponible
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary-subtle text-secondary">
                                                <i class="ti ti-x-circle me-1"></i>No Disponible
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-3 py-3 text-center">
                                        <span class="badge bg-warning-subtle text-warning">
                                            <i class="ti ti-clock me-1"></i><?= esc($solicitud['estado']) ?>
                                        </span>
                                    </td>
                                    <td class="px-3 py-3 text-center">
                                        <div class="d-flex gap-1 justify-content-center">
                                            <?php if ($solicitud['disponible']): ?>
                                                <button class="btn btn-sm btn-success" onclick="aprobarSolicitud(<?= $solicitud['id'] ?>)" title="Aprobar Solicitud">
                                                    <i class="ti ti-check"></i>
                                                </button>
                                            <?php endif; ?>
                                            <button class="btn btn-sm btn-danger" onclick="rechazarSolicitud(<?= $solicitud['id'] ?>)" title="Rechazar Solicitud">
                                                <i class="ti ti-x"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-secondary" onclick="verDetalleSolicitud(<?= $solicitud['id'] ?>)" title="Ver Detalles">
                                                <i class="ti ti-eye"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="empty-state">
                                        <div class="empty-state-icon mb-3">
                                            <i class="ti ti-clock-off" style="font-size: 3rem; color: #6c757d;"></i>
                                        </div>
                                        <h5 class="empty-state-title text-muted">No hay solicitudes pendientes</h5>
                                        <p class="empty-state-description text-muted mb-0">
                                            Actualmente no existen solicitudes pendientes de aprobación
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
        <?php if (!empty($solicitudes)): ?>
        <div class="card-footer bg-light border-top-0">
            <div class="d-flex justify-content-between align-items-center text-muted small">
                <span>
                    <i class="ti ti-info-circle me-1"></i>
                    Mostrando <?= count($solicitudes) ?> de <?= count($solicitudes) ?> solicitudes
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
    // Función para aprobar solicitud
    function aprobarSolicitud(solicitudId) {
        console.log('Aprobar solicitud:', solicitudId);
        
        Swal.fire({
            title: '¿Aprobar Solicitud?',
            text: '¿Estás seguro de que deseas aprobar esta solicitud de préstamo?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, aprobar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#28a745'
        }).then((result) => {
            if (result.isConfirmed) {
                // TODO: Implementar aprobación
                Swal.fire({
                    title: 'Solicitud Aprobada',
                    text: 'La solicitud ha sido aprobada y se ha generado el préstamo',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    // Recargar la tabla o remover la fila
                    location.reload();
                });
            }
        });
    }

    // Función para rechazar solicitud
    function rechazarSolicitud(solicitudId) {
        console.log('Rechazar solicitud:', solicitudId);
        
        Swal.fire({
            title: '¿Rechazar Solicitud?',
            input: 'textarea',
            inputLabel: 'Motivo del rechazo',
            inputPlaceholder: 'Escribe el motivo por el cual se rechaza la solicitud...',
            inputAttributes: {
                'aria-label': 'Motivo del rechazo'
            },
            showCancelButton: true,
            confirmButtonText: 'Rechazar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#dc3545',
            inputValidator: (value) => {
                if (!value) {
                    return 'Debes proporcionar un motivo para el rechazo'
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // TODO: Implementar rechazo con motivo
                Swal.fire({
                    title: 'Solicitud Rechazada',
                    text: 'La solicitud ha sido rechazada y se ha notificado al usuario',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    // Recargar la tabla o remover la fila
                    location.reload();
                });
            }
        });
    }

    // Función para ver detalles de la solicitud
    function verDetalleSolicitud(solicitudId) {
        console.log('Ver detalles de solicitud:', solicitudId);
        // TODO: Implementar modal de detalles
        Swal.fire({
            title: 'Detalles de la Solicitud',
            text: 'Funcionalidad en desarrollo',
            icon: 'info'
        });
    }

    // Función para aprobar todas las solicitudes disponibles
    function aprobarTodas() {
        // Contar solicitudes disponibles
        const disponibles = <?= json_encode(array_filter($solicitudes ?? [], function($s) { return $s['disponible']; })) ?>;
        
        if (disponibles.length === 0) {
            Swal.fire({
                title: 'Sin Solicitudes Disponibles',
                text: 'No hay solicitudes con recursos disponibles para aprobar',
                icon: 'info'
            });
            return;
        }

        Swal.fire({
            title: `¿Aprobar ${disponibles.length} solicitudes?`,
            text: 'Se aprobarán todas las solicitudes con recursos disponibles',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: `Sí, aprobar ${disponibles.length} solicitudes`,
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#28a745'
        }).then((result) => {
            if (result.isConfirmed) {
                // TODO: Implementar aprobación masiva
                Swal.fire({
                    title: 'Procesando...',
                    text: 'Aprobando solicitudes disponibles',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Simular procesamiento
                setTimeout(() => {
                    Swal.fire({
                        title: 'Solicitudes Aprobadas',
                        text: `Se han aprobado ${disponibles.length} solicitudes exitosamente`,
                        icon: 'success'
                    }).then(() => {
                        location.reload();
                    });
                }, 2000);
            }
        });
    }
</script>