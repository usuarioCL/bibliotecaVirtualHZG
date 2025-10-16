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
                            <i class="ti ti-calendar text-info" style="font-size: 2.5rem;"></i>
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
                            <i class="ti ti-calendar text-warning" style="font-size: 2.5rem;"></i>
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
            </div>
        </div>
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tablaSolicitudes">
                    <thead class="table-light">
                        <tr class="text-uppercase small fw-semibold text-muted">
                            <th class="border-0 px-3 py-3">Usuario</th>
                            <th class="border-0 px-3 py-3">Recurso Solicitado</th>
                            <th class="border-0 px-3 py-3">Fecha y Horarios</th>
                            <th class="border-0 text-center px-3 py-3">Prioridad</th>
                            <th class="border-0 text-center px-3 py-3">Disponibilidad</th>
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
                                            <p class="mb-1 small text-muted">
                                                <i class="ti ti-clock me-1"></i>
                                                Inicio: <?= date('H:i', strtotime($solicitud['fecha_solicitud'])) ?>
                                            </p>
                                            <p class="mb-0 small text-muted">
                                                <i class="ti ti-clock-off me-1"></i>
                                                Fin: <?= date('H:i', strtotime($solicitud['fecha_devolucion'])) ?>
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
                // Mostrar loading
                Swal.fire({
                    title: 'Procesando...',
                    text: 'Aprobando solicitud de préstamo',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Enviar solicitud AJAX
                fetch('<?= base_url('prestamos/aprobar') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: 'idsolicitud=' + encodeURIComponent(solicitudId)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'Solicitud Aprobada',
                            text: data.message || 'La solicitud ha sido aprobada y se ha generado el préstamo',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            // Recargar solo el contenido de solicitudes sin perder el contexto del panel
                            recargarContenidoSolicitudes();
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: data.message || 'No se pudo aprobar la solicitud',
                            icon: 'error'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Error',
                        text: 'Ha ocurrido un error de conexión',
                        icon: 'error'
                    });
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
                // Mostrar loading
                Swal.fire({
                    title: 'Procesando...',
                    text: 'Rechazando solicitud de préstamo',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Enviar solicitud AJAX
                fetch('<?= base_url('prestamos/rechazar') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: 'idsolicitud=' + encodeURIComponent(solicitudId) + '&motivo=' + encodeURIComponent(result.value)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'Solicitud Rechazada',
                            text: data.message || 'La solicitud ha sido rechazada correctamente',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            // Recargar solo el contenido de solicitudes sin perder el contexto del panel
                            recargarContenidoSolicitudes();
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: data.message || 'No se pudo rechazar la solicitud',
                            icon: 'error'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Error',
                        text: 'Ha ocurrido un error de conexión',
                        icon: 'error'
                    });
                });
            }
        });
    }

    // Función para ver detalles de la solicitud
    function verDetalleSolicitud(solicitudId) {
        console.log('Ver detalles de solicitud:', solicitudId);
        
        // Mostrar loading
        Swal.fire({
            title: 'Cargando...',
            text: 'Obteniendo detalles de la solicitud',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Enviar solicitud AJAX para obtener detalles
        fetch('<?= base_url('prestamos/detalleSolicitud') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: 'idsolicitud=' + encodeURIComponent(solicitudId)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarModalDetalles(data.data);
            } else {
                Swal.fire({
                    title: 'Error',
                    text: data.message || 'No se pudieron cargar los detalles de la solicitud',
                    icon: 'error'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error',
                text: 'Ha ocurrido un error de conexión',
                icon: 'error'
            });
        });
    }

    // Función para mostrar el modal con los detalles
    function mostrarModalDetalles(detalle) {
        // Crear o actualizar el modal existente
        let modalExistente = document.getElementById('modalDetalleSolicitud');
        if (modalExistente) {
            modalExistente.remove();
        }

        // Formatear fechas
        const fechaSolicitud = new Date(detalle.fecha_solicitud);
        const fechaDevolucionEsperada = new Date(detalle.fecha_devolucion_esperada);
        const fechaDevolucion = detalle.fecha_devolucion ? new Date(detalle.fecha_devolucion) : null;
        
        // Determinar el color de la prioridad
        let prioridadClass = 'bg-info';
        let prioridadIcon = 'ti-info-circle';
        
        if (detalle.prioridad === 'Alta') {
            prioridadClass = 'bg-danger';
            prioridadIcon = 'ti-alert-circle';
        } else if (detalle.prioridad === 'Media') {
            prioridadClass = 'bg-warning';
            prioridadIcon = 'ti-alert-triangle';
        }
        
        // Determinar disponibilidad
        const disponibilidadBadge = detalle.disponible 
            ? '<span class="badge bg-success"><i class="ti ti-check-circle me-1"></i>Disponible</span>'
            : '<span class="badge bg-secondary"><i class="ti ti-x-circle me-1"></i>No Disponible</span>';
        
        // Crear lista de autores
        let autoresLista = 'No especificado';
        if (detalle.autores && detalle.autores.length > 0) {
            autoresLista = detalle.autores.map(autor => {
                let autorTexto = autor.nombre_completo.trim();
                if (autor.nacionalidad) {
                    autorTexto += ` (${autor.nacionalidad})`;
                }
                return autorTexto;
            }).join(', ');
        }
        
        // Crear el HTML del modal
        const modalHtml = `
            <!-- Modal para detalles de la solicitud -->
            <div class="modal fade" id="modalDetalleSolicitud" tabindex="-1" style="z-index: 99999;">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="ti ti-file-text me-2"></i>Detalles de Solicitud #${detalle.idsolicitud}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div id="contenido-detalle-solicitud">
                                <!-- Información del Solicitante -->
                                <div class="row">
                                    <div class="col-md-8">
                                        <h6 class="text-primary mb-3">
                                            <i class="ti ti-user me-2"></i>Información del Solicitante
                                        </h6>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>Nombre Completo:</strong> <span>${detalle.usuario_completo}</span></p>
                                                <p><strong>Documento:</strong> <span>${detalle.tipo_documento}: ${detalle.documento}</span></p>
                                                ${detalle.telefono ? `<p><strong>Teléfono:</strong> <span>${detalle.telefono}</span></p>` : ''}
                                                ${detalle.email ? `<p><strong>Email:</strong> <span>${detalle.email}</span></p>` : ''}
                                            </div>
                                            <div class="col-md-6">
                                                ${detalle.grado && detalle.seccion ? `<p><strong>Grado:</strong> <span>${detalle.grado}° "${detalle.seccion}" - ${detalle.nivel_estudiante}</span></p>` : ''}
                                                ${detalle.aniolectivo ? `<p><strong>Año Lectivo:</strong> <span>${detalle.aniolectivo}</span></p>` : ''}
                                                <p><strong>Fecha Solicitud:</strong> <span>${fechaSolicitud.toLocaleDateString('es-ES')}</span></p>
                                                <p><strong>Hora Inicio:</strong> <span>${fechaSolicitud.toLocaleTimeString('es-ES', {hour: '2-digit', minute: '2-digit'})}</span></p>
                                                ${fechaDevolucion ? `<p><strong>Hora Fin:</strong> <span>${fechaDevolucion.toLocaleTimeString('es-ES', {hour: '2-digit', minute: '2-digit'})}</span></p>` : ''}
                                                <p><strong>Tiempo Esperando:</strong> <span>${detalle.dias_desde_solicitud} día(s)</span></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" 
                                             style="width: 100px; height: 100px; font-size: 2rem; font-weight: 600;">
                                            ${detalle.usuario_nombres.charAt(0)}${detalle.usuario_apellidos.charAt(0)}
                                        </div>
                                        <div class="mb-2">
                                            <span class="badge ${prioridadClass} fs-6 px-3 py-2">
                                                <i class="ti ${prioridadIcon} me-1"></i>Prioridad ${detalle.prioridad}
                                            </span>
                                        </div>
                                        <div>
                                            ${disponibilidadBadge}
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <!-- Información del Recurso -->
                                <h6 class="text-primary mb-3">
                                    <i class="ti ti-book me-2"></i>Recurso Solicitado
                                </h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Título:</strong> <span>${detalle.recurso_titulo}</span></p>
                                        <p><strong>Autor(es):</strong> <span>${autoresLista}</span></p>
                                        <p><strong>Código:</strong> <span>${detalle.codigo_ejemplar}</span></p>
                                        <p><strong>Editorial:</strong> <span>${detalle.editorial || 'No especificado'}</span></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Año Publicación:</strong> <span>${detalle.anio_publicacion || 'No especificado'}</span></p>
                                        <p><strong>Categoría:</strong> <span>${detalle.categoria || 'No especificado'}</span></p>
                                        <p><strong>Stock Disponible:</strong> <span>${detalle.stock} unidades</span></p>
                                        ${detalle.otros_prestamos_activos > 0 ? `<p class="text-warning"><strong>Préstamos Activos:</strong> <span>${detalle.otros_prestamos_activos}</span></p>` : ''}
                                    </div>
                                </div>

                                <hr>

                                <!-- Detalles de la Solicitud -->
                                <h6 class="text-primary mb-3">
                                    <i class="ti ti-clock-hour-3 me-2"></i>Detalles de la Solicitud
                                </h6>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="text-center p-3 bg-primary bg-opacity-10 rounded">
                                            <h4 class="mb-1 text-primary">${fechaSolicitud.toLocaleDateString('es-ES')}</h4>
                                            <small class="text-muted">Fecha de Solicitud</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-center p-3 bg-success bg-opacity-10 rounded">
                                            <h4 class="mb-1 text-success">${fechaSolicitud.toLocaleTimeString('es-ES', {hour: '2-digit', minute: '2-digit'})}</h4>
                                            <small class="text-muted">Hora de Inicio</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-center p-3 bg-info bg-opacity-10 rounded">
                                            <h4 class="mb-1 text-info">${fechaDevolucion ? fechaDevolucion.toLocaleTimeString('es-ES', {hour: '2-digit', minute: '2-digit'}) : 'No especificada'}</h4>
                                            <small class="text-muted">Hora de Fin</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-center p-3 bg-warning bg-opacity-10 rounded">
                                            <h4 class="mb-1 text-warning">${detalle.dias_desde_solicitud}</h4>
                                            <small class="text-muted">Días Esperando</small>
                                        </div>
                                    </div>

                                </div>

                                <hr>

                                <!-- Historial del Usuario -->
                                ${detalle.historial_usuario ? `
                                <h6 class="text-primary mb-3">
                                    <i class="ti ti-chart-bar me-2"></i>Historial del Usuario
                                </h6>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="text-center p-3 bg-primary bg-opacity-10 rounded">
                                            <h4 class="mb-1 text-primary">${detalle.historial_usuario.total_prestamos}</h4>
                                            <small class="text-muted">Total Préstamos</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-center p-3 bg-success bg-opacity-10 rounded">
                                            <h4 class="mb-1 text-success">${detalle.historial_usuario.prestamos_devueltos}</h4>
                                            <small class="text-muted">Préstamos Devueltos</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-center p-3 bg-danger bg-opacity-10 rounded">
                                            <h4 class="mb-1 text-danger">${detalle.historial_usuario.prestamos_vencidos}</h4>
                                            <small class="text-muted">Préstamos Vencidos</small>
                                        </div>
                                    </div>
                                </div>
                                ` : ''}
                            </div>
                        </div>
                        <div class="modal-footer">
                            ${detalle.disponible ? `
                            <button type="button" class="btn btn-success" onclick="cerrarModalDetalle(); aprobarSolicitud(${detalle.idsolicitud})">
                                <i class="ti ti-check me-2"></i>Aprobar Solicitud
                            </button>
                            ` : ''}
                            <button type="button" class="btn btn-danger" onclick="cerrarModalDetalle(); rechazarSolicitud(${detalle.idsolicitud})">
                                <i class="ti ti-x me-2"></i>Rechazar Solicitud
                            </button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Agregar el modal al DOM
        document.body.insertAdjacentHTML('beforeend', modalHtml);
        
        // Mostrar el modal
        const modal = new bootstrap.Modal(document.getElementById('modalDetalleSolicitud'));
        modal.show();
        
        // Cerrar SweetAlert2
        Swal.close();
    }

    // Función para cerrar el modal de detalles
    function cerrarModalDetalle() {
        const modal = document.getElementById('modalDetalleSolicitud');
        if (modal) {
            const bootstrapModal = bootstrap.Modal.getInstance(modal);
            if (bootstrapModal) {
                bootstrapModal.hide();
            }
            // Remover el modal del DOM después de un breve delay
            setTimeout(() => {
                modal.remove();
            }, 300);
        }
    }

    // Función para aprobar todas las solicitudes disponibles
    function aprobarTodas() {
        // Obtener solicitudes disponibles desde los datos PHP
        const todasLasSolicitudes = <?= json_encode($solicitudes ?? []) ?>;
        
        console.log('Todas las solicitudes:', todasLasSolicitudes);
        
        // Filtrar solicitudes disponibles (MySQL devuelve 1/0 en lugar de true/false)
        const disponibles = todasLasSolicitudes.filter(s => {
            const esDisponible = s.disponible == 1 || s.disponible === true || s.disponible === 'true';
            console.log(`Solicitud ${s.id}: disponible=${s.disponible}, esDisponible=${esDisponible}`);
            return esDisponible;
        });
        
        console.log('Solicitudes disponibles encontradas:', disponibles.length);
        
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
                // Mostrar loading
                Swal.fire({
                    title: 'Procesando...',
                    text: 'Aprobando solicitudes disponibles',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Preparar IDs de solicitudes disponibles (asegurándonos de que sean números)
                const solicitudesIds = disponibles.map(s => parseInt(s.id)).filter(id => !isNaN(id));
                
                console.log('Solicitudes a aprobar:', solicitudesIds);

                // Validar que tenemos IDs para enviar
                if (solicitudesIds.length === 0) {
                    Swal.fire({
                        title: 'Error',
                        text: 'No se encontraron IDs válidos de solicitudes para aprobar',
                        icon: 'error'
                    });
                    return;
                }

                // Enviar solicitud AJAX
                fetch('<?= base_url('prestamos/aprobarTodas') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: 'solicitudes=' + encodeURIComponent(JSON.stringify(solicitudesIds))
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Respuesta del servidor:', data);
                    
                    if (data.success) {
                        const resultados = data.data;
                        let mensaje = `Se aprobaron ${resultados.aprobadas} solicitudes exitosamente`;
                        
                        if (resultados.rechazadas > 0) {
                            mensaje += `\n${resultados.rechazadas} solicitudes no pudieron ser procesadas`;
                            
                            // Mostrar errores específicos si existen
                            if (resultados.errores && resultados.errores.length > 0) {
                                mensaje += `\n\nErrores específicos:\n${resultados.errores.join('\n')}`;
                            }
                        }

                        Swal.fire({
                            title: 'Proceso Completado',
                            text: mensaje,
                            icon: resultados.rechazadas > 0 ? 'warning' : 'success',
                            confirmButtonText: 'Entendido'
                        }).then(() => {
                            // Recargar solo el contenido de solicitudes sin perder el contexto del panel
                            recargarContenidoSolicitudes();
                        });
                    } else {
                        Swal.fire({
                            title: 'Error en el Servidor',
                            text: data.message || 'No se pudieron aprobar las solicitudes',
                            icon: 'error'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Error',
                        text: 'Ha ocurrido un error de conexión',
                        icon: 'error'
                    });
                });
            }
        });
    }

    // Función para recargar solo el contenido de solicitudes manteniendo el contexto del panel
    function recargarContenidoSolicitudes() {
        // Verificar si estamos dentro del panel de administración (existe #contenedor-principal)
        const contenedorPrincipal = document.getElementById('contenedor-principal');
        
        if (contenedorPrincipal) {
            // Estamos en el panel de administración, usar AJAX para recargar
            console.log('Recargando solicitudes via AJAX en panel de administración');
            
            // Mostrar indicador de carga
            contenedorPrincipal.innerHTML = `
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-2 text-muted">Actualizando solicitudes...</p>
                </div>
            `;
            
            // Cargar el contenido de solicitudes via AJAX
            fetch('<?= base_url('solicitudes') ?>', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.text();
            })
            .then(data => {
                contenedorPrincipal.innerHTML = data;
                
                // Disparar evento para indicar que el contenido se ha cargado
                const event = new CustomEvent('content-loaded');
                document.dispatchEvent(event);
                
                console.log('Solicitudes recargadas exitosamente');
            })
            .catch(error => {
                console.error('Error al recargar solicitudes:', error);
                contenedorPrincipal.innerHTML = `
                    <div class="text-danger text-center py-5">
                        <i class="ti ti-alert-circle" style="font-size: 3rem;"></i>
                        <h5 class="mt-3">Error al cargar solicitudes</h5>
                        <p>Hubo un problema al actualizar la información.</p>
                        <button class="btn btn-primary" onclick="recargarContenidoSolicitudes()">
                            <i class="ti ti-refresh me-2"></i>Reintentar
                        </button>
                    </div>
                `;
            });
        } else {
            // No estamos en el panel de administración, usar recarga normal
            console.log('Recargando página completa - fuera del panel de administración');
            location.reload();
        }
    }
</script>