<!-- CSS Profesional para Sanciones -->
<link rel="stylesheet" href="<?= base_url('assets/css/sanciones-professional.css') ?>">

<style>
    /* Estilos de estado - Forzar colores */
    .sanction-status.status-cancelada,
    .status-cancelada {
        background-color: #ffc107 !important;
        color: #000 !important;
        font-weight: 600 !important;
    }

    .sanction-status.status-cumplida,
    .status-cumplida {
        background-color: #198754 !important;
        color: white !important;
        font-weight: 600 !important;
    }

    .sanction-status.status-activa,
    .status-activa {
        background-color: #dc3545 !important;
        color: white !important;
        font-weight: 600 !important;
    }
</style>

<?php if (isset($error)): ?>
    <div class="alert alert-danger" role="alert">
        <i class="ti ti-alert-circle me-2"></i>
        <?= $error ?>
    </div>
<?php endif; ?>

<!-- Estadísticas -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stats-card">
            <div class="d-flex align-items-center">
                <div class="stats-icon bg-danger text-white">
                    <i class="ti ti-shield-x"></i>
                </div>
                <div class="ms-3">
                    <h6 class="mb-0">Total Sanciones</h6>
                    <h4 class="mb-0"><?= $estadisticas['total'] ?? 0 ?></h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <div class="d-flex align-items-center">
                <div class="stats-icon bg-warning text-white">
                    <i class="ti ti-clock"></i>
                </div>
                <div class="ms-3">
                    <h6 class="mb-0">Activas</h6>
                    <h4 class="mb-0"><?= $estadisticas['activas'] ?? 0 ?></h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <div class="d-flex align-items-center">
                <div class="stats-icon bg-success text-white">
                    <i class="ti ti-check"></i>
                </div>
                <div class="ms-3">
                    <h6 class="mb-0">Cumplidas</h6>
                    <h4 class="mb-0"><?= $estadisticas['cumplidas'] ?? 0 ?></h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <div class="d-flex align-items-center">
                <div class="stats-icon bg-warning text-dark">
                    <i class="ti ti-x"></i>
                </div>
                <div class="ms-3">
                    <h6 class="mb-0">Canceladas</h6>
                    <h4 class="mb-0"><?= $estadisticas['canceladas'] ?? 0 ?></h4>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filtros -->
<div class="filter-section">
    <form method="GET" action="<?= base_url('sanciones/historial') ?>" id="filtros-form">
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Estado</label>
                <select name="estado" class="form-select">
                    <option value="">Todos los estados</option>
                    <option value="cumplida" <?= (($filtros['estado'] ?? '') == 'cumplida') ? 'selected' : '' ?>>Cumplida
                    </option>
                    <option value="cancelada" <?= (($filtros['estado'] ?? '') == 'cancelada') ? 'selected' : '' ?>>
                        Cancelada</option>
                    <option value="suspendida" <?= (($filtros['estado'] ?? '') == 'suspendida') ? 'selected' : '' ?>>
                        Suspendida</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Fecha Desde</label>
                <input type="date" name="fecha_desde" class="form-control" value="<?= $filtros['fecha_desde'] ?? '' ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Fecha Hasta</label>
                <input type="date" name="fecha_hasta" class="form-control" value="<?= $filtros['fecha_hasta'] ?? '' ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Buscar</label>
                <input type="text" name="buscar" class="form-control" placeholder="Nombre, apellido o DNI..."
                    value="<?= $filtros['buscar'] ?? '' ?>">
            </div>
        </div>
        <div class="row mt-5 pt-2">
            <div class="col-12">
                <button type="submit" class="btn btn-primary">
                    <i class="ti ti-search me-1"></i>Filtrar
                </button>
                <button type="button" class="btn btn-outline-secondary ms-3" id="btn-limpiar-filtros">
                    <i class="ti ti-refresh me-1"></i>Limpiar
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Tabla de Historial -->
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="ti ti-history me-2"></i>Historial de Sanciones
            </h5>
            <button type="button" id="btnExportarExcelSanciones" class="btn btn-success btn-sm">
                <i class="ti ti-file-excel"></i> Exportar Excel
            </button>
        </div>
    </div>
    <div class="card-body">
        <?php if (empty($sanciones)): ?>
            <div class="text-center py-5">
                <i class="ti ti-shield-check text-muted" style="font-size: 3rem;"></i>
                <h5 class="text-muted mt-3">No hay sanciones registradas</h5>
                <p class="text-muted">No se encontraron sanciones con los filtros aplicados.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Persona</th>
                            <th>Tipo</th>
                            <th>Detalles</th>
                            <th>Fecha Sanción</th>
                            <th>Vencimiento</th>
                            <th>Estado</th>
                            <th>Registrado por</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sanciones as $sancion): ?>
                            <tr>
                                <td>
                                    <div>
                                        <strong><?= $sancion['nombre_completo'] ?? 'N/A' ?></strong>
                                        <br>
                                        <small class="text-muted">
                                            <?= $sancion['tipodoc'] ?? 'Doc' ?>: <?= $sancion['numerodoc'] ?? 'N/A' ?>
                                        </small>
                                        <?php if (!empty($sancion['email'])): ?>
                                            <br>
                                            <small class="text-muted">
                                                <i class="ti ti-mail"></i> <?= $sancion['email'] ?>
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info">
                                        <?= $sancion['tiposancion'] ?? 'N/A' ?>
                                    </span>
                                </td>
                                <td><?= $sancion['detallesancion'] ?? 'N/A' ?></td>
                                <td><?= $sancion['fecha_sancion'] ?? 'N/A' ?></td>
                                <td><?= $sancion['fecha_vencimiento'] ?? 'Sin fecha' ?></td>
                                <td>
                                    <span class="sanction-status status-<?= $sancion['estado_sancion'] ?? 'activa' ?>">
                                        <?= ucfirst($sancion['estado_sancion'] ?? 'activa') ?>
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <?= $sancion['usuario_registra_nombre'] ?? 'Sistema' ?>
                                    </small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary"
                                            onclick="verSancion(<?= $sancion['idsancion'] ?>)" title="Ver detalles">
                                            <i class="ti ti-eye"></i>
                                        </button>
                                        <?php if (($sancion['estado_sancion'] ?? '') == 'activa'): ?>
                                            <button class="btn btn-outline-success"
                                                onclick="cambiarEstado(<?= $sancion['idsancion'] ?>, 'cumplida')"
                                                title="Marcar como cumplida">
                                                <i class="ti ti-check"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Detalles de Sanción -->
<div class="modal fade" id="modalDetallesSancion" tabindex="-1" aria-labelledby="modalDetallesSancionLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalDetallesSancionLabel">
                    <i class="ti ti-file-description me-2"></i>Detalles de la Sanción
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Cerrar"></button>
            </div>
            <div class="modal-body" id="detalles-sancion-content">
                <!-- El contenido se cargará dinámicamente -->
            </div>
        </div>
    </div>
</div>

<script>
    // Interceptar el envío del formulario de filtros
    $(document).ready(function () {
        // Manejar envío del formulario
        $('#filtros-form').on('submit', function (e) {
            e.preventDefault(); // Prevenir el envío normal del formulario

            // Obtener la URL con los parámetros del formulario
            const formData = $(this).serialize();
            const url = '<?= base_url('sanciones/historial') ?>?' + formData;

            // Mostrar indicador de carga
            $('#contenedor-principal').html('<div class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando...</span></div></div>');

            // Hacer la petición AJAX
            $.ajax({
                url: url,
                type: 'GET',
                success: function (data) {
                    $('#contenedor-principal').html(data);
                },
                error: function () {
                    $('#contenedor-principal').html('<div class="alert alert-danger">Error al cargar los datos. Por favor, intenta nuevamente.</div>');
                }
            });
        });

        // Manejar botón limpiar filtros
        $('#btn-limpiar-filtros').on('click', function (e) {
            e.preventDefault();

            // Mostrar indicador de carga
            $('#contenedor-principal').html('<div class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando...</span></div></div>');

            // Cargar la vista sin filtros
            $.ajax({
                url: '<?= base_url('sanciones/historial') ?>',
                type: 'GET',
                success: function (data) {
                    $('#contenedor-principal').html(data);
                },
                error: function () {
                    $('#contenedor-principal').html('<div class="alert alert-danger">Error al cargar los datos. Por favor, intenta nuevamente.</div>');
                }
            });
        });
    });

    function verSancion(id) {
        // Abrir el modal
        const modal = new bootstrap.Modal(document.getElementById('modalDetallesSancion'));
        modal.show();

        // Mostrar loading
        document.getElementById('detalles-sancion-content').innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="mt-2">Cargando detalles...</p>
        </div>
    `;

        // Obtener detalles de la sanción
        fetch('<?= base_url('sanciones/ver') ?>/' + id, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.sancion) {
                    const sancion = data.sancion;
                    const estadoClass = sancion.estado_sancion === 'activa' ? 'bg-danger' :
                        sancion.estado_sancion === 'cumplida' ? 'bg-success' :
                            sancion.estado_sancion === 'cancelada' ? 'bg-warning' : 'bg-secondary';

                    let html = `
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted mb-2"><i class="ti ti-user me-1"></i>Persona Sancionada</h6>
                        <p class="mb-1"><strong>${sancion.nombre_completo || 'N/A'}</strong></p>
                        <p class="mb-1 text-muted">${sancion.tipodoc || 'Doc'}: ${sancion.numerodoc || 'N/A'}</p>
                        ${sancion.email ? `<p class="mb-1 text-muted"><i class="ti ti-mail"></i> ${sancion.email}</p>` : ''}
                        ${sancion.telefono ? `<p class="mb-1 text-muted"><i class="ti ti-phone"></i> ${sancion.telefono}</p>` : ''}
                    </div>
                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted mb-2"><i class="ti ti-info-circle me-1"></i>Tipo de Sanción</h6>
                        <p class="mb-1"><span class="badge bg-info">${sancion.tiposancion || 'N/A'}</span></p>
                        <h6 class="text-muted mb-2 mt-3"><i class="ti ti-clock me-1"></i>Estado</h6>
                        <p class="mb-1"><span class="badge ${estadoClass}">${sancion.estado_sancion ? sancion.estado_sancion.toUpperCase() : 'N/A'}</span></p>
                    </div>
                </div>
                
                <hr class="my-3">
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted mb-2"><i class="ti ti-calendar me-1"></i>Fecha de Sanción</h6>
                        <p class="mb-1">${sancion.fecha_sancion || 'N/A'}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted mb-2"><i class="ti ti-calendar-event me-1"></i>Fecha de Inicio</h6>
                        <p class="mb-1">${sancion.fecha_inicio || 'N/A'}</p>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted mb-2"><i class="ti ti-calendar-time me-1"></i>Fecha de Vencimiento</h6>
                        <p class="mb-1">${sancion.fecha_vencimiento || 'Sin fecha'}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted mb-2"><i class="ti ti-hourglass me-1"></i>Duración</h6>
                        <p class="mb-1">${sancion.duracion_dias ? sancion.duracion_dias + ' días' : 'N/A'}</p>
                    </div>
                </div>
                
                <hr class="my-3">
                
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <h6 class="text-muted mb-2"><i class="ti ti-file-text me-1"></i>Detalles de la Sanción</h6>
                        <p class="mb-1">${sancion.detallesancion || 'Sin detalles'}</p>
                    </div>
                </div>
                
                ${sancion.observaciones ? `
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <h6 class="text-muted mb-2"><i class="ti ti-notes me-1"></i>Observaciones</h6>
                        <p class="mb-1">${sancion.observaciones}</p>
                    </div>
                </div>
                ` : ''}
                
                <hr class="my-3">
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted mb-2"><i class="ti ti-user-check me-1"></i>Registrado por</h6>
                        <p class="mb-1">${sancion.usuario_registra_nombre || 'Sistema'}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted mb-2"><i class="ti ti-calendar-plus me-1"></i>Fecha de Registro</h6>
                        <p class="mb-1">${sancion.created_at || 'N/A'}</p>
                    </div>
                </div>
                
                ${sancion.estado_sancion === 'cancelada' && sancion.usuario_levanta_nombre ? `
                    <hr class="my-3">
                    <div class="alert alert-warning">
                        <h6 class="mb-2"><i class="ti ti-alert-triangle me-2"></i>Sanción Cancelada/Levantada</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Levantada por:</strong> ${sancion.usuario_levanta_nombre}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Fecha de levantamiento:</strong> ${sancion.fecha_levantamiento || 'N/A'}</p>
                            </div>
                            ${sancion.motivo_levantamiento ? `
                            <div class="col-12 mt-2">
                                <p class="mb-1"><strong>Motivo:</strong> ${sancion.motivo_levantamiento}</p>
                            </div>
                            ` : ''}
                        </div>
                    </div>
                ` : ''}
                
                <div class="d-flex justify-content-end gap-2 mt-3">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ti ti-x me-1"></i>Cerrar
                    </button>
                </div>
            `;

                    document.getElementById('detalles-sancion-content').innerHTML = html;
                } else {
                    document.getElementById('detalles-sancion-content').innerHTML = `
                <div class="text-center py-4">
                    <i class="ti ti-alert-circle text-danger" style="font-size: 3rem;"></i>
                    <h5 class="text-danger mt-3">Error</h5>
                    <p class="text-muted">${data.message || 'No se pudieron cargar los detalles de la sanción'}</p>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ti ti-x me-1"></i>Cerrar
                    </button>
                </div>
            `;
                }
            })
            .catch(error => {
                console.error('Error al cargar detalles:', error);
                document.getElementById('detalles-sancion-content').innerHTML = `
            <div class="text-center py-4">
                <i class="ti ti-alert-circle text-danger" style="font-size: 3rem;"></i>
                <h5 class="text-danger mt-3">Error al cargar detalles</h5>
                <p class="text-muted">No se pudieron cargar los detalles de la sanción.</p>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="ti ti-x me-1"></i>Cerrar
                </button>
            </div>
        `;
            });
    }

    function cambiarEstado(id, estado) {
        // Aquí iría la lógica para cambiar el estado
        console.log('Cambiar estado:', id, estado);
    }
</script>

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Script para Exportar Excel con SweetAlert2 -->
<script>
    $(document).ready(function () {
        // Evento para exportar historial de sanciones a Excel
        $('#btnExportarExcelSanciones').on('click', function () {
            // Mostrar indicador de carga
            Swal.fire({
                title: 'Generando archivo Excel...',
                text: 'Por favor espera mientras se genera el archivo',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Abrir en nueva ventana para descargar
            window.location.href = '<?= base_url('/sanciones/historial/exportar-excel') ?>';

            // Cerrar el loading después de un momento
            setTimeout(() => {
                Swal.close();
            }, 1500);
        });
    });
</script>