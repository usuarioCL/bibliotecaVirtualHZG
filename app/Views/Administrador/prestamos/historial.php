<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container-fluid">
    <!-- Encabezado de la página con breadcrumb -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between mb-4">
                <div>
                    <h1 class="h3 mb-1 fw-bold text-dark">
                        <i class="ti ti-history text-primary me-2"></i>
                        Historial Completo de Préstamos
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="#">Sistema de Préstamos</a></li>
                            <li class="breadcrumb-item active">Historial Completo</li>
                        </ol>
                    </nav>
                    <p class="text-muted mb-0 mt-1">Consulta el historial completo de todos los préstamos del sistema</p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="mostrarFiltrosAvanzados()">
                        <i class="ti ti-filter-search"></i> Filtros Avanzados
                    </button>
                    <button type="button" class="btn btn-success btn-sm">
                        <i class="ti ti-file-excel"></i> Exportar Excel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros rápidos -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body py-3">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold text-muted">Período:</label>
                            <select class="form-select form-select-sm" id="periodoFiltro">
                                <option value="">Todos los períodos</option>
                                <option value="hoy">Hoy</option>
                                <option value="semana">Esta semana</option>
                                <option value="mes" selected>Este mes</option>
                                <option value="trimestre">Este trimestre</option>
                                <option value="ano">Este año</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold text-muted">Estado:</label>
                            <select class="form-select form-select-sm" id="estadoFiltro">
                                <option value="">Todos los estados</option>
                                <option value="devuelto">Devuelto</option>
                                <option value="devuelto_retraso">Devuelto con retraso</option>
                                <option value="cancelado">Cancelado</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-muted">Buscar:</label>
                            <input type="text" class="form-control form-control-sm" id="busquedaRapida" placeholder="Usuario, documento, recurso...">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button class="btn btn-primary btn-sm w-100" onclick="aplicarFiltros()">
                                <i class="ti ti-search me-1"></i>Buscar
                            </button>
                        </div>
                    </div>
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
                            <i class="ti ti-database text-primary" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-primary mb-1"><?= isset($estadisticas['total_registros']) ? number_format($estadisticas['total_registros']) : 0 ?></h3>
                    <p class="text-muted mb-0 small">Total Registros</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card info h-100 shadow-sm">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="rounded-circle bg-info bg-opacity-10 p-3">
                            <i class="ti ti-calendar-month text-info" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-info mb-1"><?= isset($estadisticas['este_mes']) ? number_format($estadisticas['este_mes']) : 0 ?></h3>
                    <p class="text-muted mb-0 small">Este Mes</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card warning h-100 shadow-sm">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                            <i class="ti ti-trending-up text-warning" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-warning mb-1"><?= isset($estadisticas['promedio_mensual']) ? number_format($estadisticas['promedio_mensual']) : 0 ?></h3>
                    <p class="text-muted mb-0 small">Promedio Mensual</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card success h-100 shadow-sm">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3">
                            <i class="ti ti-percentage text-success" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-success mb-1"><?= isset($estadisticas['tasa_devolucion']) ? $estadisticas['tasa_devolucion'] : 0 ?>%</h3>
                    <p class="text-muted mb-0 small">Tasa de Devolución</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de historial con diseño mejorado -->
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title mb-0 fw-semibold">
                        <i class="ti ti-list text-primary me-2"></i>
                        Historial de Préstamos
                    </h5>
                    <p class="text-muted small mb-0 mt-1">Registro completo de todos los préstamos procesados</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-secondary btn-sm" type="button">
                        <i class="ti ti-download me-1"></i>Exportar PDF
                    </button>
                    <button class="btn btn-outline-secondary btn-sm" type="button" onclick="location.reload()">
                        <i class="ti ti-refresh me-1"></i>Actualizar
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tablaHistorial">
                    <thead class="table-light">
                        <tr class="text-uppercase small fw-semibold text-muted">
                            <th class="border-0 px-3 py-3">Préstamo</th>
                            <th class="border-0 px-3 py-3">Usuario</th>
                            <th class="border-0 px-3 py-3">Recurso</th>
                            <th class="border-0 px-3 py-3">Fechas</th>
                            <th class="border-0 text-center px-3 py-3">Estado</th>
                            <th class="border-0 px-3 py-3">Observaciones</th>
                            <th class="border-0 text-center px-3 py-3">Duración</th>
                            <th class="border-0 text-center px-3 py-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($historial)): ?>
                            <?php foreach ($historial as $registro): ?>
                                <tr class="border-bottom">
                                    <td class="px-3 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <div class="rounded-2 bg-success bg-opacity-10 p-2">
                                                    <i class="ti ti-book-upload text-success fs-5"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <h6 class="mb-1 fw-semibold"><?= esc($registro['codigo_prestamo']) ?></h6>
                                                <p class="text-muted mb-0 small">ID: <?= esc($registro['id']) ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3">
                                        <div>
                                            <h6 class="mb-1 fw-medium"><?= esc($registro['usuario']) ?></h6>
                                            <p class="text-muted mb-0 small">CC: <?= esc($registro['documento']) ?></p>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3">
                                        <div>
                                            <h6 class="mb-1 fw-medium"><?= esc($registro['recurso']) ?></h6>
                                            <p class="text-muted mb-0 small">Estado: <?= esc($registro['estado_ejemplar']) ?></p>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3">
                                        <div>
                                            <p class="mb-1 small">
                                                <i class="ti ti-calendar-check me-1"></i>
                                                Devuelto: <?= date('d/m/Y H:i', strtotime($registro['fecha_devolucion'])) ?>
                                            </p>
                                            <p class="mb-0 small">
                                                <i class="ti ti-calendar-due me-1"></i>
                                                Vencía: <?= date('d/m/Y H:i', strtotime($registro['fecha_vencimiento'])) ?>
                                            </p>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3 text-center">
                                        <?php if ($registro['dias_retraso'] == 0): ?>
                                            <span class="badge bg-success-subtle text-success">
                                                <i class="ti ti-check-circle me-1"></i>A Tiempo
                                            </span>
                                        <?php elseif ($registro['dias_retraso'] > 0): ?>
                                            <span class="badge bg-danger-subtle text-danger">
                                                <i class="ti ti-alert-circle me-1"></i>Con Retraso
                                            </span>
                                            <?php if ($registro['horas_retraso'] < 24): ?>
                                                <small class="d-block text-danger fw-semibold mt-1"><?= $registro['horas_retraso'] ?> hora(s)</small>
                                            <?php else: ?>
                                                <small class="d-block text-danger fw-semibold mt-1"><?= $registro['dias_retraso'] ?> día(s)</small>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="badge bg-info-subtle text-info">
                                                <i class="ti ti-clock me-1"></i>Temprana
                                            </span>
                                            <small class="d-block text-muted mt-1"><?= abs($registro['dias_retraso']) ?> día(s)</small>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-3 py-3">
                                        <div class="d-flex align-items-center">
                                            <?php if (isset($registro['tiene_observaciones']) && $registro['tiene_observaciones']): ?>
                                                <div>
                                                    <i class="ti ti-note text-primary me-2"></i>
                                                    <span class="text-muted small"><?= esc($registro['observaciones']) ?></span>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-muted fst-italic small">Sin observaciones</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3 text-center">
                                        <span class="badge bg-info-subtle text-info">
                                            <?= $registro['dias_prestamo'] ?> días
                                        </span>
                                    </td>
                                    <td class="px-3 py-3 text-center">
                                        <div class="d-flex gap-2 justify-content-center">
                                            <button type="button" class="btn btn-sm btn-outline-info" 
                                                    onclick="verDetalleHistorial(<?= $registro['id'] ?>)"
                                                    title="Ver Detalles">
                                                <i class="ti ti-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" 
                                                    onclick="imprimirRecibo(<?= $registro['id'] ?>)"
                                                    title="Imprimir Recibo">
                                                <i class="ti ti-printer"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    onclick="confirmarEliminacion(<?= $registro['id'] ?>)"
                                                    title="Eliminar">
                                                <i class="ti ti-x"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="empty-state">
                                        <div class="empty-state-icon mb-3">
                                            <i class="ti ti-database-off" style="font-size: 3rem; color: #6c757d;"></i>
                                        </div>
                                        <h5 class="empty-state-title text-muted">No se encontraron registros</h5>
                                        <p class="empty-state-description text-muted mb-0">
                                            No hay historial de préstamos con los filtros aplicados
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
        <?php if (!empty($historial)): ?>
        <div class="card-footer bg-light border-top-0">
            <div class="d-flex justify-content-between align-items-center text-muted small">
                <span>
                    <i class="ti ti-info-circle me-1"></i>
                    Mostrando <?= count($historial) ?> de <?= isset($estadisticas['total_registros']) ? $estadisticas['total_registros'] : count($historial) ?> registros
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
    // Función para mostrar filtros avanzados
    function mostrarFiltrosAvanzados() {
        Swal.fire({
            title: 'Filtros Avanzados',
            html: `
                <div class="text-start">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Fecha desde:</label>
                            <input type="date" class="form-control" id="fechaDesde">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fecha hasta:</label>
                            <input type="date" class="form-control" id="fechaHasta">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Usuario específico:</label>
                            <input type="text" class="form-control" id="usuarioFiltro" placeholder="Nombre de usuario">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tipo de recurso:</label>
                            <select class="form-select" id="tipoRecurso">
                                <option value="">Todos los tipos</option>
                                <option value="libro">Libro</option>
                                <option value="revista">Revista</option>
                                <option value="tesis">Tesis</option>
                                <option value="digital">Recurso Digital</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="soloMultas">
                                <label class="form-check-label" for="soloMultas">
                                    Solo registros con multas
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Aplicar Filtros',
            cancelButtonText: 'Cancelar',
            width: 600,
            preConfirm: () => {
                // TODO: Implementar lógica de filtros avanzados
                return {
                    fechaDesde: document.getElementById('fechaDesde').value,
                    fechaHasta: document.getElementById('fechaHasta').value,
                    usuario: document.getElementById('usuarioFiltro').value,
                    tipoRecurso: document.getElementById('tipoRecurso').value,
                    soloMultas: document.getElementById('soloMultas').checked
                };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Filtros Aplicados',
                    text: 'Los filtros avanzados han sido aplicados exitosamente',
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                });
            }
        });
    }

    // Función para aplicar filtros rápidos
    function aplicarFiltros() {
        const periodo = document.getElementById('periodoFiltro').value;
        const estado = document.getElementById('estadoFiltro').value;
        const busqueda = document.getElementById('busquedaRapida').value;
        
        console.log('Aplicando filtros:', { periodo, estado, busqueda });
        
        // TODO: Implementar filtrado en tiempo real
        if (busqueda || periodo || estado) {
            Swal.fire({
                title: 'Filtros Aplicados',
                text: 'Se han aplicado los filtros seleccionados',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
            });
        }
    }

    // Función para ver detalles completos del historial
    function verDetalleHistorial(registroId) {
        fetch('<?= base_url('prestamos/obtenerDetalleDevolucion') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: 'idprestamo=' + registroId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const detalle = data.data;
                Swal.fire({
                    title: '<i class="ti ti-info-circle me-2"></i>Detalles del Préstamo',
                    html: `
                        <div class="text-start">
                            <div class="row mb-3">
                                <div class="col-6">
                                    <strong>Código:</strong><br>
                                    ${detalle.codigo_prestamo}
                                </div>
                                <div class="col-6">
                                    <strong>Usuario:</strong><br>
                                    ${detalle.usuario}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-6">
                                    <strong>Documento:</strong><br>
                                    ${detalle.documento}
                                </div>
                                <div class="col-6">
                                    <strong>Teléfono:</strong><br>
                                    ${detalle.telefono || 'N/A'}
                                </div>
                            </div>
                            <hr>
                            <div class="row mb-3">
                                <div class="col-12">
                                    <strong>Recurso:</strong><br>
                                    ${detalle.recurso}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-6">
                                    <strong>Fecha de préstamo:</strong><br>
                                    ${new Date(detalle.fechaprestamo).toLocaleString('es-ES')}
                                </div>
                                <div class="col-6">
                                    <strong>Fecha límite:</strong><br>
                                    ${new Date(detalle.fecha_limite).toLocaleString('es-ES')}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-6">
                                    <strong>Fecha de devolución:</strong><br>
                                    ${new Date(detalle.fecha_devolucion_real).toLocaleString('es-ES')}
                                </div>
                                <div class="col-6">
                                    <strong>Días de retraso:</strong><br>
                                    <span class="${detalle.dias_retraso > 0 ? 'text-danger' : 'text-success'}">
                                        ${detalle.dias_retraso > 0 ? detalle.dias_retraso + ' días' : 'A tiempo'}
                                    </span>
                                </div>
                            </div>
                            ${detalle.dias_retraso > 0 ? `
                            <div class="alert alert-warning">
                                <strong><i class="ti ti-alert-triangle me-2"></i>Retraso detectado</strong><br>
                                Se generó una sanción por ${detalle.dias_retraso} día(s) de retraso en la devolución.
                            </div>
                            ` : ''}
                            ${detalle.sanciones ? `
                            <div class="alert alert-danger">
                                <strong>Sanciones registradas:</strong><br>
                                ${detalle.sanciones}
                            </div>
                            ` : ''}
                        </div>
                    `,
                    width: 700,
                    confirmButtonText: 'Cerrar'
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: data.message,
                    icon: 'error'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error',
                text: 'Ocurrió un error al obtener los detalles',
                icon: 'error'
            });
        });
    }

    // Función para imprimir recibo
    function imprimirRecibo(registroId) {
        console.log('Imprimir recibo:', registroId);
        Swal.fire({
            title: 'Generando Recibo',
            text: 'Funcionalidad en desarrollo - Se generará un PDF con el recibo de devolución',
            icon: 'info',
            timer: 2000,
            showConfirmButton: false
        });
    }

    // Función para confirmar eliminación
    function confirmarEliminacion(registroId) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta acción no se puede deshacer",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Funcionalidad en desarrollo',
                    text: 'La eliminación de registros estará disponible próximamente',
                    icon: 'info',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        });
    }

    // Función para generar reporte individual
    function generarReporte(registroId) {
        console.log('Generar reporte:', registroId);
        Swal.fire({
            title: 'Generando Reporte',
            text: 'Se está generando el reporte del préstamo...',
            icon: 'info',
            timer: 2000,
            showConfirmButton: false
        });
    }

    // Función para ver línea de tiempo
    function verLineaTiempo(registroId) {
        console.log('Ver línea de tiempo:', registroId);
        // TODO: Implementar vista de línea de tiempo
        Swal.fire({
            title: 'Línea de Tiempo del Préstamo',
            text: 'Funcionalidad en desarrollo',
            icon: 'info'
        });
    }

    // Aplicar filtros al presionar Enter en el campo de búsqueda
    document.addEventListener('DOMContentLoaded', function() {
        const busquedaInput = document.getElementById('busquedaRapida');
        if (busquedaInput) {
            busquedaInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    aplicarFiltros();
                }
            });
        }
    });
</script>