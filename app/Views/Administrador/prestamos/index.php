<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    /**
     * Función inteligente para recargar el contenido de préstamos
     * Detecta si está en el panel (AJAX) o como página independiente
     */
    function recargarContenidoPrestamos() {
        const contenedorPrincipal = document.getElementById('contenedor-principal');
        
        if (contenedorPrincipal) {
            // Estamos en el panel de administración (contexto AJAX)
            console.log('Recargando contenido via AJAX...');
            
            // Mostrar indicador de carga
            contenedorPrincipal.innerHTML = `
                <div class="d-flex justify-content-center align-items-center" style="min-height: 400px;">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-3 text-muted">Actualizando préstamos...</p>
                    </div>
                </div>
            `;
            
            // Recargar el contenido via AJAX
            fetch('<?= base_url('prestamos') ?>', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                contenedorPrincipal.innerHTML = html;
                console.log('Contenido recargado exitosamente');
            })
            .catch(error => {
                console.error('Error al recargar contenido:', error);
                contenedorPrincipal.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="ti ti-alert-circle me-2"></i>
                        Error al recargar el contenido. Por favor, intenta nuevamente.
                    </div>
                `;
            });
        } else {
            // Estamos fuera del panel (página independiente)
            console.log('Recargando página completa...');
            window.location.href = '<?= base_url('prestamos') ?>';
        }
    }
</script>

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
                    <button type="button" class="btn btn-primary btn-sm" onclick="mostrarModalNuevoPrestamo()">
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

            </div>
        </div>
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tablaPrestamos">
                    <thead class="table-light">
                        <tr class="text-uppercase small fw-semibold text-muted">
                            <th class="border-0 px-3 py-3">Usuario</th>
                            <th class="border-0 px-3 py-3">Recurso</th>
                            <th class="border-0 px-3 py-3">Fechas</th>
                            <th class="border-0 text-center px-3 py-3">Cantidad</th>
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
                                                    <i class="ti ti-user text-primary fs-5"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <h6 class="mb-1 fw-semibold"><?= esc($prestamo['usuario']) ?></h6>
                                                <p class="text-muted mb-0 small">CC: <?= esc($prestamo['documento']) ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3">
                                        <div>
                                            <h6 class="mb-1 fw-medium"><?= esc($prestamo['recurso']) ?></h6>
                                            <p class="text-muted mb-0 small">Ejemplar: <?= esc($prestamo['codigo_ejemplar']) ?></p>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3">
                                        <div>
                                            <p class="mb-1 small">
                                                <i class="ti ti-calendar-plus text-primary me-1"></i>
                                                <strong>Inicio:</strong> <?= date('d/m/Y', strtotime($prestamo['fecha_prestamo'])) ?>
                                            </p>
                                            <p class="mb-1 small">
                                                <i class="ti ti-calendar text-success me-1"></i>
                                                <strong>Entrega:</strong> <?= !empty($prestamo['fecha_devolucion']) ? date('d/m/Y', strtotime($prestamo['fecha_devolucion'])) : 'No especificada' ?>
                                            </p>
                                            <p class="mb-0 small text-muted">
                                                <i class="ti ti-clock-hour-3 me-1"></i>
                                                Duración: 
                                                <?php
                                                    if (!empty($prestamo['fecha_devolucion'])) {
                                                        $inicio = new DateTime($prestamo['fecha_prestamo']);
                                                        $entrega = new DateTime($prestamo['fecha_devolucion']);
                                                        $diff = $inicio->diff($entrega);
                                                        echo $diff->days . ' día' . ($diff->days != 1 ? 's' : '');
                                                    } else {
                                                        echo 'No especificada';
                                                    }
                                                ?>
                                            </p>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3 text-center">
                                        <div class="d-flex flex-column align-items-center">
                                            <span class="badge bg-primary-subtle text-primary fs-6 px-3 py-2 mb-1">
                                                <?= isset($prestamo['cantidad']) ? $prestamo['cantidad'] : 1 ?>
                                            </span>
                                            <small class="text-muted">
                                                <?= (isset($prestamo['cantidad']) && $prestamo['cantidad'] == 1) ? 'ejemplar' : 'ejemplares' ?>
                                            </small>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3 text-center">
                                        <?php if ($prestamo['estado'] == 'Activo'): ?>
                                            <?php if ($prestamo['dias_restantes'] > 3): ?>
                                                <span class="badge bg-success-subtle text-success">
                                                    <i class="ti ti-check-circle me-1"></i><?= esc($prestamo['estado']) ?>
                                                </span>
                                                <small class="d-block text-muted mt-1">
                                                    <?php 
                                                        $diasRestantesDecimal = abs($prestamo['dias_restantes']);
                                                        $dias = floor($diasRestantesDecimal);
                                                        $horasRestantes = round(($diasRestantesDecimal - $dias) * 24);
                                                        if ($dias > 0) {
                                                            echo $dias . ' día' . ($dias != 1 ? 's' : '');
                                                        } else {
                                                            echo $horasRestantes . ' hora' . ($horasRestantes != 1 ? 's' : '');
                                                        }
                                                    ?>
                                                </small>
                                            <?php elseif ($prestamo['dias_restantes'] >= 0): ?>
                                                <span class="badge bg-warning-subtle text-warning">
                                                    <i class="ti ti-alert-triangle me-1"></i>Por Vencer
                                                </span>
                                                <small class="d-block text-muted mt-1">
                                                    <?php 
                                                        $diasRestantesDecimal = abs($prestamo['dias_restantes']);
                                                        $dias = floor($diasRestantesDecimal);
                                                        $horasRestantes = round(($diasRestantesDecimal - $dias) * 24);
                                                        if ($dias > 0) {
                                                            echo $dias . ' día' . ($dias != 1 ? 's' : '');
                                                        } else {
                                                            echo $horasRestantes . ' hora' . ($horasRestantes != 1 ? 's' : '');
                                                        }
                                                    ?>
                                                </small>
                                            <?php else: ?>
                                                <span class="badge bg-danger-subtle text-danger">
                                                    <i class="ti ti-x-circle me-1"></i>Vencido
                                                </span>
                                                <small class="d-block text-muted mt-1">
                                                    <?php 
                                                        $diasRestantesDecimal = abs($prestamo['dias_restantes']);
                                                        $dias = floor($diasRestantesDecimal);
                                                        $horasRestantes = round(($diasRestantesDecimal - $dias) * 24);
                                                        if ($dias > 0) {
                                                            echo $dias . ' día' . ($dias != 1 ? 's' : '');
                                                        } else {
                                                            echo $horasRestantes . ' hora' . ($horasRestantes != 1 ? 's' : '');
                                                        }
                                                    ?>
                                                </small>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="badge bg-danger-subtle text-danger">
                                                <i class="ti ti-x-circle me-1"></i><?= esc($prestamo['estado']) ?>
                                            </span>
                                            <small class="d-block text-muted mt-1">
                                                <?php 
                                                    $diasRestantesDecimal = abs($prestamo['dias_restantes']);
                                                    $dias = floor($diasRestantesDecimal);
                                                    $horasRestantes = round(($diasRestantesDecimal - $dias) * 24);
                                                    if ($dias > 0) {
                                                        echo $dias . ' día' . ($dias != 1 ? 's' : '');
                                                    } else {
                                                        echo $horasRestantes . ' hora' . ($horasRestantes != 1 ? 's' : '');
                                                    }
                                                ?>
                                            </small>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-3 py-3 text-center">
                                        <span class="badge bg-info-subtle text-info">
                                            <?= $prestamo['renovaciones'] ?> renovaciones
                                        </span>
                                    </td>
                                    <td class="px-3 py-3 text-center">
                                        <div class="d-flex gap-1 justify-content-center align-items-center flex-wrap">
                                            <!-- Ver Detalles -->
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-info" 
                                                    onclick="verDetallePrestamo(<?= $prestamo['idprestamo'] ?>)" 
                                                    title="Ver Detalles"
                                                    data-bs-toggle="tooltip">
                                                <i class="ti ti-eye"></i>
                                            </button>
                                            
                                            <!-- Renovar -->
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-warning" 
                                                    onclick="renovarPrestamo(<?= $prestamo['idprestamo'] ?>)" 
                                                    title="Renovar Préstamo"
                                                    data-bs-toggle="tooltip">
                                                <i class="ti ti-refresh"></i>
                                            </button>
                                            
                                            <!-- Procesar Devolución -->
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-success" 
                                                    onclick="procesarDevolucion(<?= $prestamo['idprestamo'] ?>)" 
                                                    title="Procesar Devolución"
                                                    data-bs-toggle="tooltip">
                                                <i class="ti ti-book-upload"></i>
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
                    Fecha/Hora de comparación: <?= date('d/m/Y H:i:s') ?>
                </span>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
    // Función auxiliar para crear el HTML del modal
    function crearModalHTML() {
        return `
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="ti ti-bookmark text-primary me-2"></i>
                                Detalles del Préstamo
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div id="contenido-detalle-prestamo">
                                <!-- Estado del préstamo -->
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div id="alert-estado" class="alert d-flex align-items-center">
                                            <i id="icono-estado" class="me-2"></i>
                                            <strong>Estado: <span id="detalle-estado-prestamo">-</span></strong>
                                            <span id="detalle-tiempo-restante" class="ms-auto">-</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Información del recurso -->
                                <div class="row">
                                    <div class="col-md-8">
                                        <h6 class="text-primary mb-3">Información del Recurso</h6>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>Título:</strong> <span id="detalle-titulo">-</span></p>
                                                <p><strong>Autor(es):</strong> <span id="detalle-autores">-</span></p>
                                                <p><strong>Editorial:</strong> <span id="detalle-editorial">-</span></p>
                                                <p><strong>ISBN:</strong> <span id="detalle-isbn">-</span></p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Año:</strong> <span id="detalle-anio">-</span></p>
                                                <p><strong>Categoría:</strong> <span id="detalle-categoria">-</span></p>
                                                <p><strong>Tipo:</strong> <span id="detalle-tipo-recurso" class="badge bg-secondary">-</span></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <div id="detalle-portada-container">
                                            <div id="detalle-portada-placeholder" class="bg-light rounded d-flex align-items-center justify-content-center mx-auto mb-2" 
                                                 style="width: 120px; height: 120px;">
                                                <i class="ti ti-book-off text-muted" style="font-size: 2rem;"></i>
                                            </div>
                                            <img id="detalle-portada" src="" alt="Portada" class="img-fluid rounded mx-auto mb-2" 
                                                 style="max-width: 120px; max-height: 120px; display: none;">
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <!-- Información del usuario -->
                                <h6 class="text-primary mb-3">Información del Usuario</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Nombre:</strong> <span id="detalle-usuario-nombre">-</span></p>
                                        <p><strong>Documento:</strong> <span id="detalle-documento">-</span></p>
                                        <p><strong>Teléfono:</strong> <span id="detalle-telefono">-</span></p>
                                        <p><strong>Email:</strong> <span id="detalle-email">-</span></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Usuario:</strong> <span id="detalle-nombre-usuario">-</span></p>
                                        <p><strong>Nivel:</strong> <span id="detalle-nivel-acceso" class="badge">-</span></p>
                                        <p id="detalle-matricula-container" style="display: none;"><strong>ID Matrícula:</strong> <span id="detalle-matricula">-</span></p>
                                        <p id="detalle-grado-container" style="display: none;"><strong>Grado:</strong> <span id="detalle-grado">-</span></p>
                                    </div>
                                </div>

                                <hr>

                                <!-- Información del préstamo -->
                                <h6 class="text-primary mb-3">Información del Préstamo</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Código préstamo:</strong> <span id="detalle-codigo-prestamo">-</span></p>
                                        <p><strong>Fecha préstamo:</strong> <span id="detalle-fecha-prestamo-solo">-</span></p>
                                        <p><strong>Hora inicio:</strong> <span id="detalle-hora-inicio">-</span></p>
                                        <p><strong>Hora fin:</strong> <span id="detalle-hora-fin">-</span></p>
                                        <p><strong>Fecha vencimiento:</strong> <span id="detalle-fecha-vencimiento">-</span></p>
                                        <p id="detalle-fecha-aprobacion-container" style="display: none;"><strong>Fecha aprobación:</strong> <span id="detalle-fecha-aprobacion">-</span></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Días transcurridos:</strong> <span id="detalle-dias-transcurridos">-</span> días</p>
                                        <p><strong>Días restantes:</strong> <span id="detalle-dias-restantes" class="badge">-</span></p>
                                        <p><strong>Total renovaciones:</strong> <span id="detalle-total-renovaciones" class="badge bg-info">-</span></p>
                                    </div>
                                </div>

                                <hr>

                                <!-- Historial de renovaciones -->
                                <div id="detalle-renovaciones-section" style="display: none;">
                                    <h6 class="text-primary mb-3">
                                        Historial de Renovaciones 
                                        <span id="detalle-cantidad-renovaciones" class="badge bg-success">0</span>
                                    </h6>
                                    <div id="detalle-renovaciones-tabla">
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Fecha</th>
                                                        <th>Nueva fecha devolución</th>
                                                        <th>Extensión</th>
                                                        <th>Motivo</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="detalle-renovaciones-body">
                                                    <!-- Se llenará dinámicamente -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div id="loading-detalle-prestamo" class="text-center py-4" style="display: none;">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Cargando...</span>
                                </div>
                                <p class="mt-2 text-muted">Cargando información del préstamo...</p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btn-renovar-prestamo" class="btn btn-outline-warning" style="display: none;">
                                <i class="ti ti-refresh me-2"></i>Renovar
                            </button>
                            <button type="button" id="btn-procesar-devolucion" class="btn btn-outline-success" style="display: none;">
                                <i class="ti ti-check me-2"></i>Procesar Devolución
                            </button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
        `;
    }

    // Función auxiliar para inicializar el modal
    function inicializarModal() {
        let modalElement = document.getElementById('modalDetallePrestamo');
        if (!modalElement) {
            modalElement = document.createElement('div');
            modalElement.id = 'modalDetallePrestamo';
            modalElement.className = 'modal fade';
            modalElement.tabIndex = -1;
            modalElement.innerHTML = crearModalHTML();
            document.body.appendChild(modalElement);
        }
        return new bootstrap.Modal(modalElement);
    }

    // Función auxiliar para actualizar el estado del préstamo en el modal
    function actualizarEstadoPrestamo(detalle) {
        const alertEstado = document.getElementById('alert-estado');
        alertEstado.className = `alert alert-${detalle.color_estado} d-flex align-items-center`;
        document.getElementById('icono-estado').className = `ti ${detalle.icono_estado} me-2`;
        document.getElementById('detalle-estado-prestamo').textContent = detalle.estado_prestamo;
        
        // Formatear días/horas restantes
        const diasRestantesDecimal = parseFloat(detalle.dias_restantes) || 0;
        const diasRestantes = Math.floor(Math.abs(diasRestantesDecimal));
        const horasRestantes = Math.round((Math.abs(diasRestantesDecimal) - diasRestantes) * 24);
        
        let textoTiempo;
        if (Math.abs(diasRestantesDecimal) >= 1) {
            textoTiempo = diasRestantesDecimal >= 0 
                ? `${diasRestantes} día${diasRestantes !== 1 ? 's' : ''} restantes`
                : `${diasRestantes} día${diasRestantes !== 1 ? 's' : ''} de retraso`;
        } else {
            textoTiempo = diasRestantesDecimal >= 0
                ? `${horasRestantes} hora${horasRestantes !== 1 ? 's' : ''} restantes`
                : `${horasRestantes} hora${horasRestantes !== 1 ? 's' : ''} de retraso`;
        }
        
        document.getElementById('detalle-tiempo-restante').textContent = textoTiempo;
    }

    // Función auxiliar para actualizar la información del recurso
    function actualizarInfoRecurso(detalle) {
        document.getElementById('detalle-titulo').textContent = detalle.recurso_titulo || '-';
        
        const autoresText = detalle.autores?.length > 0 
            ? detalle.autores.map(a => a.autor_completo || 'Autor desconocido').join(', ')
            : 'No especificado';
        document.getElementById('detalle-autores').innerHTML = autoresText;
        
        document.getElementById('detalle-editorial').textContent = detalle.editorial || 'No especificada';
        document.getElementById('detalle-isbn').textContent = detalle.isbn || 'No disponible';
        document.getElementById('detalle-anio').textContent = detalle.anio_publicacion || 'No especificado';
        
        const categoriaCompleta = (detalle.categoria || 'Sin categoría') + (detalle.subcategoria ? ` / ${detalle.subcategoria}` : '');
        document.getElementById('detalle-categoria').textContent = categoriaCompleta;
        
        const tipoRecursoElement = document.getElementById('detalle-tipo-recurso');
        tipoRecursoElement.textContent = detalle.tipo_recurso || '-';
        tipoRecursoElement.className = 'badge bg-secondary';

        // Portada
        const portadaImg = document.getElementById('detalle-portada');
        const portadaPlaceholder = document.getElementById('detalle-portada-placeholder');
        if (detalle.portada) {
            portadaImg.src = detalle.portada;
            portadaImg.style.display = 'block';
            portadaPlaceholder.style.display = 'none';
        } else {
            portadaImg.style.display = 'none';
            portadaPlaceholder.style.display = 'flex';
        }
    }

    // Función auxiliar para actualizar la información del usuario
    function actualizarInfoUsuario(detalle) {
        document.getElementById('detalle-usuario-nombre').textContent = detalle.usuario_completo || '-';
        document.getElementById('detalle-documento').textContent = `${detalle.tipo_documento || ''} ${detalle.documento || ''}`.trim() || '-';
        document.getElementById('detalle-telefono').textContent = detalle.telefono || 'No registrado';
        document.getElementById('detalle-email').textContent = detalle.email || 'No registrado';
        document.getElementById('detalle-nombre-usuario').textContent = detalle.nombre_usuario || 'N/A';
        
        const nivelElement = document.getElementById('detalle-nivel-acceso');
        nivelElement.textContent = detalle.nivel_acceso || 'N/A';
        const nivelClasses = {
            'admin': 'badge bg-danger',
            'docente': 'badge bg-warning',
            'default': 'badge bg-success'
        };
        nivelElement.className = nivelClasses[detalle.nivel_acceso] || nivelClasses.default;

        // Información adicional
        const matriculaContainer = document.getElementById('detalle-matricula-container');
        const gradoContainer = document.getElementById('detalle-grado-container');
        
        if (detalle.idmatricula) {
            document.getElementById('detalle-matricula').textContent = detalle.idmatricula;
            matriculaContainer.style.display = 'block';
        } else {
            matriculaContainer.style.display = 'none';
        }
        
        if (detalle.grado && detalle.seccion) {
            document.getElementById('detalle-grado').textContent = `${detalle.grado} - ${detalle.seccion}`;
            gradoContainer.style.display = 'block';
        } else {
            gradoContainer.style.display = 'none';
        }
    }

    // Función auxiliar para actualizar la información del préstamo
    function actualizarInfoPrestamo(detalle) {
        document.getElementById('detalle-codigo-prestamo').textContent = detalle.idprestamo || '-';
        document.getElementById('detalle-fecha-prestamo-solo').textContent = detalle.fecha_prestamo_solo || '-';
        document.getElementById('detalle-hora-inicio').textContent = detalle.hora_inicio || '-';
        document.getElementById('detalle-hora-fin').textContent = detalle.hora_fin || 'No especificada';
        document.getElementById('detalle-fecha-vencimiento').textContent = detalle.fecha_vencimiento_formatted || '-';
        
        const fechaAprobacionContainer = document.getElementById('detalle-fecha-aprobacion-container');
        if (detalle.fecha_aprobacion_formatted) {
            document.getElementById('detalle-fecha-aprobacion').textContent = detalle.fecha_aprobacion_formatted;
            fechaAprobacionContainer.style.display = 'block';
        } else {
            fechaAprobacionContainer.style.display = 'none';
        }
        
        document.getElementById('detalle-dias-transcurridos').textContent = Math.floor(detalle.dias_transcurridos) || 0;
        
        const diasRestantesElement = document.getElementById('detalle-dias-restantes');
        const diasRestantesDecimal = parseFloat(detalle.dias_restantes) || 0;
        const diasRestantes = Math.floor(Math.abs(diasRestantesDecimal));
        const horasRestantes = Math.round((Math.abs(diasRestantesDecimal) - diasRestantes) * 24);
        
        // Formatear texto según días u horas
        if (Math.abs(diasRestantesDecimal) >= 1) {
            diasRestantesElement.textContent = `${diasRestantes} día${diasRestantes !== 1 ? 's' : ''}`;
        } else {
            diasRestantesElement.textContent = `${horasRestantes} hora${horasRestantes !== 1 ? 's' : ''}`;
        }
        diasRestantesElement.className = `badge bg-${diasRestantesDecimal >= 0 ? 'success' : 'danger'}`;
        
        document.getElementById('detalle-total-renovaciones').textContent = detalle.total_renovaciones || 0;
    }

    // Función auxiliar para actualizar el historial de renovaciones
    function actualizarHistorialRenovaciones(detalle) {
        const renovacionesSection = document.getElementById('detalle-renovaciones-section');
        const cantidadRenovaciones = document.getElementById('detalle-cantidad-renovaciones');
        const renovacionesBody = document.getElementById('detalle-renovaciones-body');
        
        if (detalle.renovaciones?.length > 0) {
            cantidadRenovaciones.textContent = detalle.renovaciones.length;
            renovacionesBody.innerHTML = '';
            
            detalle.renovaciones.forEach(ren => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td><small>${ren.fecha_renovacion_formatted}</small></td>
                    <td><small>${ren.fecha_vencimiento_nueva_formatted}</small></td>
                    <td><span class="badge bg-info">${ren.dias_extension || 0} días</span></td>
                    <td><small>${ren.motivo || 'Sin motivo especificado'}</small></td>
                `;
                renovacionesBody.appendChild(row);
            });
            
            renovacionesSection.style.display = 'block';
        } else {
            renovacionesSection.style.display = 'none';
        }
    }

    // Función auxiliar para configurar los botones del modal
    function configurarBotonesModal(modal, detalle, prestamoId) {
        const btnRenovar = document.getElementById('btn-renovar-prestamo');
        const btnDevolucion = document.getElementById('btn-procesar-devolucion');
        
        if (detalle.estado_prestamo === 'Activo' || detalle.estado_prestamo === 'Vencido') {
            btnRenovar.style.display = 'inline-block';
            btnDevolucion.style.display = 'inline-block';
            
            btnRenovar.onclick = () => {
                modal.hide();
                renovarPrestamo(prestamoId);
            };
            btnDevolucion.onclick = () => {
                modal.hide();
                procesarDevolucion(prestamoId);
            };
        } else {
            btnRenovar.style.display = 'none';
            btnDevolucion.style.display = 'none';
        }
    }

    // Función principal para ver detalles del préstamo 
    function verDetallePrestamo(prestamoId) {
        console.log('Ver detalles del préstamo:', prestamoId);
        
        const modal = inicializarModal();
        
        // Mostrar loading
        document.getElementById('loading-detalle-prestamo').style.display = 'block';
        document.getElementById('contenido-detalle-prestamo').style.display = 'none';
        modal.show();

        // Solicitud AJAX
        fetch('<?= base_url('prestamos/detalle') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: 'idprestamo=' + encodeURIComponent(prestamoId)
        })
        .then(response => {
            if (!response.ok) throw new Error('Error HTTP: ' + response.status);
            return response.json();
        })
        .then(data => {
            if (data.success && data.data) {
                const detalle = data.data;
                
                // Actualizar todas las secciones del modal
                actualizarEstadoPrestamo(detalle);
                actualizarInfoRecurso(detalle);
                actualizarInfoUsuario(detalle);
                actualizarInfoPrestamo(detalle);
                actualizarHistorialRenovaciones(detalle);
                configurarBotonesModal(modal, detalle, prestamoId);

                // Mostrar contenido
                document.getElementById('loading-detalle-prestamo').style.display = 'none';
                document.getElementById('contenido-detalle-prestamo').style.display = 'block';
            } else {
                throw new Error(data.message || 'No se pudieron obtener los detalles');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('loading-detalle-prestamo').style.display = 'none';
            modal.hide();
            
            Swal.fire({
                title: 'Error',
                text: error.message || 'Ha ocurrido un error al obtener los detalles del préstamo',
                icon: 'error',
                confirmButtonText: 'Entendido'
            });
        });
    }

    // Funciones auxiliares de validación para renovación
    function validarFechaRenovacion(input) {
        const fechaValor = input.value;
        let feedback = input.nextElementSibling;
        
        // Buscar el elemento de feedback si no es el siguiente hermano directo
        if (!feedback || !feedback.classList.contains('invalid-feedback')) {
            feedback = input.parentElement.querySelector('.invalid-feedback');
        }
        
        if (!fechaValor) {
            input.classList.add('is-invalid');
            if (feedback) {
                feedback.textContent = 'La fecha es obligatoria.';
                feedback.style.display = 'block';
            }
            return false;
        }
        
        // Validar que sea un día laboral
        const fechaPartes = fechaValor.split('-');
        const fechaSeleccionada = new Date(fechaPartes[0], fechaPartes[1] - 1, fechaPartes[2]);
        const dia = fechaSeleccionada.getDay();
        
        if (dia === 0 || dia === 6) {
            input.classList.add('is-invalid');
            if (feedback) {
                feedback.textContent = 'Solo se pueden programar devoluciones de lunes a viernes.';
                feedback.style.display = 'block';
            }
            
            // Auto-corrección: mover al siguiente lunes
            setTimeout(() => {
                const diasHastaLunes = dia === 0 ? 1 : (8 - dia);
                fechaSeleccionada.setDate(fechaSeleccionada.getDate() + diasHastaLunes);
                input.value = fechaSeleccionada.toISOString().split('T')[0];
                input.classList.remove('is-invalid');
                if (feedback) feedback.style.display = 'none';
            }, 2000);
            
            return false;
        }
        
        // Validar que no sea una fecha pasada
        const hoy = new Date();
        hoy.setHours(0, 0, 0, 0);
        fechaSeleccionada.setHours(0, 0, 0, 0);
        
        if (fechaSeleccionada < hoy) {
            input.classList.add('is-invalid');
            if (feedback) {
                feedback.textContent = 'No se puede seleccionar una fecha pasada.';
                feedback.style.display = 'block';
            }
            return false;
        }
        
        // Si todo está bien, limpiar errores
        input.classList.remove('is-invalid');
        if (feedback) {
            feedback.style.display = 'none';
        }
        
        return true;
    }
    
    function validarHoraRenovacion(input, tipo) {
        const horaValor = input.value;
        let feedback = input.nextElementSibling;
        
        // Buscar el elemento de feedback si no es el siguiente hermano directo
        if (!feedback || !feedback.classList.contains('invalid-feedback')) {
            feedback = input.parentElement.querySelector('.invalid-feedback');
        }
        
        if (!horaValor) {
            input.classList.add('is-invalid');
            if (feedback) {
                feedback.textContent = 'La hora es obligatoria.';
                feedback.style.display = 'block';
            }
            return false;
        }
        
        // Validar rango de horario (8:00 AM - 1:00 PM)
        const [horas, minutos] = horaValor.split(':');
        const horaMinutos = parseInt(horas) * 60 + parseInt(minutos);
        const HORA_MIN = 8 * 60;  // 8:00 AM
        const HORA_MAX = 13 * 60; // 1:00 PM
        
        if (tipo === 'inicio') {
            // Hora de inicio: 8:00 AM - 12:59 PM
            if (horaMinutos < HORA_MIN || horaMinutos >= HORA_MAX) {
                input.classList.add('is-invalid');
                if (feedback) {
                    feedback.textContent = 'La hora de inicio debe estar entre 8:00 AM y 12:59 PM.';
                    feedback.style.display = 'block';
                }
                
                // Auto-corrección
                setTimeout(() => {
                    input.value = '08:00';
                    input.classList.remove('is-invalid');
                    if (feedback) feedback.style.display = 'none';
                }, 2000);
                
                return false;
            }
        } else if (tipo === 'fin') {
            // Hora de fin: 8:01 AM - 1:00 PM
            if (horaMinutos <= HORA_MIN || horaMinutos > HORA_MAX) {
                input.classList.add('is-invalid');
                if (feedback) {
                    feedback.textContent = 'La hora de fin debe estar entre 8:01 AM y 1:00 PM.';
                    feedback.style.display = 'block';
                }
                
                // Auto-corrección
                setTimeout(() => {
                    input.value = '13:00';
                    input.classList.remove('is-invalid');
                    if (feedback) feedback.style.display = 'none';
                }, 2000);
                
                return false;
            }
            
            // Validar que hora fin sea posterior a hora inicio
            const horaInicioInput = document.getElementById('nueva_hora_inicio');
            if (horaInicioInput && horaInicioInput.value) {
                const inicioMinutos = horaInicioInput.value.split(':').reduce((h, m) => h * 60 + parseInt(m), 0);
                if (horaMinutos <= inicioMinutos) {
                    input.classList.add('is-invalid');
                    if (feedback) {
                        feedback.textContent = 'La hora de fin debe ser posterior a la hora de inicio.';
                        feedback.style.display = 'block';
                    }
                    
                    // Auto-corrección
                    setTimeout(() => {
                        const nuevaHora = Math.min(inicioMinutos + 60, HORA_MAX);
                        const h = Math.floor(nuevaHora / 60).toString().padStart(2, '0');
                        const m = (nuevaHora % 60).toString().padStart(2, '0');
                        input.value = `${h}:${m}`;
                        input.classList.remove('is-invalid');
                        if (feedback) feedback.style.display = 'none';
                        
                        // Actualizar duración
                        const duracionElement = document.getElementById('duracion_renovacion');
                        if (duracionElement) {
                            const diferencia = nuevaHora - inicioMinutos;
                            const horas = Math.floor(diferencia / 60);
                            const mins = diferencia % 60;
                            if (horas === 0) duracionElement.textContent = `${mins} minutos`;
                            else if (mins === 0) duracionElement.textContent = `${horas} hora${horas > 1 ? 's' : ''}`;
                            else duracionElement.textContent = `${horas} hora${horas > 1 ? 's' : ''} y ${mins} minutos`;
                        }
                    }, 2000);
                    
                    return false;
                }
            }
        }
        
        // Si todo está bien, limpiar errores
        input.classList.remove('is-invalid');
        if (feedback) {
            feedback.style.display = 'none';
        }
        
        return true;
    }

    // Función para renovar préstamo
    function renovarPrestamo(prestamoId) {
        console.log('Renovar préstamo:', prestamoId);
        
        // Mostrar loading mientras obtenemos los datos del préstamo
        Swal.fire({
            title: 'Cargando...',
            text: 'Obteniendo información del préstamo',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Obtener los detalles del préstamo primero
        fetch('<?= base_url('prestamos/detalle') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: 'idprestamo=' + encodeURIComponent(prestamoId)
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success || !data.data) {
                throw new Error('No se pudieron obtener los detalles del préstamo');
            }
            
            const detalle = data.data;
            
            // Obtener fecha, hora de inicio y hora de fin del préstamo
            let fechaDefecto, horaInicioDefecto, horaFinDefecto;
            
            // Obtener hora de inicio del préstamo actual
            if (detalle.fecha_prestamo) {
                const fechaPrestamo = new Date(detalle.fecha_prestamo);
                const horasInicio = fechaPrestamo.getHours().toString().padStart(2, '0');
                const minutosInicio = fechaPrestamo.getMinutes().toString().padStart(2, '0');
                horaInicioDefecto = `${horasInicio}:${minutosInicio}`;
            } else {
                horaInicioDefecto = '08:00';
            }
            
            // Obtener fecha y hora de fin (vencimiento) del préstamo
            if (detalle.fecha_vencimiento) {
                // Parsear la fecha de vencimiento (formato: YYYY-MM-DD HH:MM:SS)
                const fechaVencimiento = new Date(detalle.fecha_vencimiento);
                fechaDefecto = fechaVencimiento.toISOString().split('T')[0];
                const horasFin = fechaVencimiento.getHours().toString().padStart(2, '0');
                const minutosFin = fechaVencimiento.getMinutes().toString().padStart(2, '0');
                horaFinDefecto = `${horasFin}:${minutosFin}`;
            } else {
                // Si no hay fecha de vencimiento, usar valores por defecto
                const hoy = new Date();
                fechaDefecto = hoy.toISOString().split('T')[0];
                horaFinDefecto = '13:00';
            }
            
            // Obtener fecha mínima (hoy)
            const hoy = new Date();
            const fechaHoy = hoy.toISOString().split('T')[0];
            
            // Mostrar el modal de renovación con los datos obtenidos
            mostrarModalRenovacion(prestamoId, fechaDefecto, horaInicioDefecto, horaFinDefecto, fechaHoy);
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error',
                text: 'No se pudo obtener la información del préstamo',
                icon: 'error',
                confirmButtonText: 'Entendido'
            });
        });
    }
    
    // Función auxiliar para mostrar el modal de renovación
    function mostrarModalRenovacion(prestamoId, fechaDefecto, horaInicioDefecto, horaFinDefecto, fechaHoy) {
        Swal.fire({
            title: '¿Renovar Préstamo?',
            html: `
                <p class="mb-3 text-start">Selecciona la nueva fecha y horarios del préstamo renovado:</p>
                
                <div class="mb-3 text-start">
                    <label for="nueva_fecha_devolucion" class="form-label fw-bold">
                        <i class="ti ti-calendar me-1"></i>Fecha:
                    </label>
                    <input type="date" 
                           id="nueva_fecha_devolucion" 
                           class="form-control" 
                           min="${fechaHoy}"
                           value="${fechaDefecto}">
                    <div class="invalid-feedback" style="display: none;"></div>
                    <small class="text-muted">Solo días de lunes a viernes</small>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="nueva_hora_inicio" class="form-label fw-bold">
                            <i class="ti ti-clock me-1"></i>Hora de inicio:
                        </label>
                        <input type="time" 
                               id="nueva_hora_inicio" 
                               class="form-control" 
                               min="08:00"
                               max="12:59"
                               value="${horaInicioDefecto}">
                        <div class="invalid-feedback" style="display: none;"></div>
                        <small class="text-muted">8:00 AM - 12:59 PM</small>
                    </div>
                    <div class="col-md-6">
                        <label for="nueva_hora_devolucion" class="form-label fw-bold">
                            <i class="ti ti-clock-off me-1"></i>Hora de fin:
                        </label>
                        <input type="time" 
                               id="nueva_hora_devolucion" 
                               class="form-control" 
                               min="08:01"
                               max="13:00"
                               value="${horaFinDefecto}">
                        <div class="invalid-feedback" style="display: none;"></div>
                        <small class="text-muted">8:01 AM - 1:00 PM</small>
                    </div>
                </div>
                
                <div class="mb-3 text-start">
                    <div class="alert alert-light border">
                        <strong><i class="ti ti-hourglass me-1"></i>Duración del préstamo:</strong>
                        <span id="duracion_renovacion" class="text-primary fw-bold">-</span>
                    </div>
                </div>
                
                <div class="mb-3 text-start">
                    <label for="motivo_renovacion" class="form-label fw-bold">
                        <i class="ti ti-message me-1"></i>Motivo (opcional):
                    </label>
                    <textarea id="motivo_renovacion" 
                              class="form-control" 
                              placeholder="Describe el motivo de la renovación..." 
                              rows="2"></textarea>
                </div>
                
                <div class="alert alert-info text-start mb-0">
                    <i class="ti ti-info-circle me-2"></i>
                    <small>Los préstamos se pueden renovar múltiples veces según sea necesario.</small>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '<i class="ti ti-refresh me-1"></i>Renovar préstamo',
            cancelButtonText: '<i class="ti ti-x me-1"></i>Cancelar',
            confirmButtonColor: '#ffc107',
            cancelButtonColor: '#6c757d',
            width: '550px',
            didOpen: () => {
                // Obtener elementos del formulario
                const fechaInput = document.getElementById('nueva_fecha_devolucion');
                const horaInicioInput = document.getElementById('nueva_hora_inicio');
                const horaFinInput = document.getElementById('nueva_hora_devolucion');
                const duracionElement = document.getElementById('duracion_renovacion');
                
                // Función auxiliar para calcular y mostrar duración
                const actualizarDuracionRenovacion = () => {
                    const horaInicio = horaInicioInput?.value;
                    const horaFin = horaFinInput?.value;
                    
                    if (horaInicio && horaFin && duracionElement) {
                        const inicioMinutos = horaInicio.split(':').reduce((h, m) => h * 60 + parseInt(m), 0);
                        const finMinutos = horaFin.split(':').reduce((h, m) => h * 60 + parseInt(m), 0);
                        const diferencia = finMinutos - inicioMinutos;
                        
                        if (diferencia <= 0) {
                            duracionElement.textContent = '0 minutos';
                        } else {
                            const horas = Math.floor(diferencia / 60);
                            const minutos = diferencia % 60;
                            
                            if (horas === 0) {
                                duracionElement.textContent = `${minutos} minutos`;
                            } else if (minutos === 0) {
                                duracionElement.textContent = `${horas} hora${horas > 1 ? 's' : ''}`;
                            } else {
                                duracionElement.textContent = `${horas} hora${horas > 1 ? 's' : ''} y ${minutos} minutos`;
                            }
                        }
                    }
                };
                
                // Validar fecha al cambiar
                fechaInput.addEventListener('change', function() {
                    validarFechaRenovacion(this);
                });
                
                // Validar fecha al escribir (input event)
                fechaInput.addEventListener('input', function() {
                    if (this.value) {
                        validarFechaRenovacion(this);
                    }
                });
                
                // Validar hora de inicio al cambiar
                horaInicioInput.addEventListener('change', function() {
                    validarHoraRenovacion(this, 'inicio');
                    actualizarDuracionRenovacion();
                });
                
                // Validar hora de inicio al escribir
                horaInicioInput.addEventListener('input', function() {
                    if (this.value) {
                        validarHoraRenovacion(this, 'inicio');
                        actualizarDuracionRenovacion();
                    }
                });
                
                // Validar hora de fin al cambiar
                horaFinInput.addEventListener('change', function() {
                    validarHoraRenovacion(this, 'fin');
                    actualizarDuracionRenovacion();
                });
                
                // Validar hora de fin al escribir
                horaFinInput.addEventListener('input', function() {
                    if (this.value) {
                        validarHoraRenovacion(this, 'fin');
                        actualizarDuracionRenovacion();
                    }
                });
                
                // Validar inicialmente y calcular duración
                setTimeout(() => {
                    if (fechaInput.value) validarFechaRenovacion(fechaInput);
                    if (horaInicioInput.value) validarHoraRenovacion(horaInicioInput, 'inicio');
                    if (horaFinInput.value) validarHoraRenovacion(horaFinInput, 'fin');
                    actualizarDuracionRenovacion();
                }, 100);
            },
            preConfirm: () => {
                const nuevaFechaDevolucion = document.getElementById('nueva_fecha_devolucion').value;
                const nuevaHoraInicio = document.getElementById('nueva_hora_inicio').value;
                const nuevaHoraFin = document.getElementById('nueva_hora_devolucion').value;
                const motivo = document.getElementById('motivo_renovacion').value;
                
                // Validar campos requeridos
                if (!nuevaFechaDevolucion) {
                    Swal.showValidationMessage('Debes seleccionar una fecha');
                    return false;
                }
                
                if (!nuevaHoraInicio) {
                    Swal.showValidationMessage('Debes seleccionar una hora de inicio');
                    return false;
                }
                
                if (!nuevaHoraFin) {
                    Swal.showValidationMessage('Debes seleccionar una hora de fin');
                    return false;
                }
                
                // Validar día laboral
                const fechaPartes = nuevaFechaDevolucion.split('-');
                const fechaSeleccionada = new Date(fechaPartes[0], fechaPartes[1] - 1, fechaPartes[2]);
                const dia = fechaSeleccionada.getDay();
                
                if (dia === 0 || dia === 6) {
                    Swal.showValidationMessage('Solo se pueden programar renovaciones de lunes a viernes');
                    return false;
                }
                
                // Validar que la fecha sea hoy o posterior
                const hoy = new Date();
                hoy.setHours(0, 0, 0, 0);
                fechaSeleccionada.setHours(0, 0, 0, 0);
                
                if (fechaSeleccionada < hoy) {
                    Swal.showValidationMessage('La fecha debe ser hoy o posterior');
                    return false;
                }
                
                // Validar horario de inicio (8:00 AM - 12:59 PM)
                const [horasInicio, minutosInicio] = nuevaHoraInicio.split(':');
                const inicioMinutos = parseInt(horasInicio) * 60 + parseInt(minutosInicio);
                const HORA_MIN = 8 * 60; // 8:00 AM
                const HORA_MAX = 13 * 60; // 1:00 PM
                
                if (inicioMinutos < HORA_MIN || inicioMinutos >= HORA_MAX) {
                    Swal.showValidationMessage('La hora de inicio debe estar entre 8:00 AM y 12:59 PM');
                    return false;
                }
                
                // Validar horario de fin (8:01 AM - 1:00 PM)
                const [horasFin, minutosFin] = nuevaHoraFin.split(':');
                const finMinutos = parseInt(horasFin) * 60 + parseInt(minutosFin);
                
                if (finMinutos <= HORA_MIN || finMinutos > HORA_MAX) {
                    Swal.showValidationMessage('La hora de fin debe estar entre 8:01 AM y 1:00 PM');
                    return false;
                }
                
                // Validar que hora de fin sea posterior a hora de inicio
                if (finMinutos <= inicioMinutos) {
                    Swal.showValidationMessage('La hora de fin debe ser posterior a la hora de inicio');
                    return false;
                }
                
                return {
                    nueva_fecha_prestamo: `${nuevaFechaDevolucion} ${nuevaHoraInicio}:00`,
                    nueva_fecha_devolucion: `${nuevaFechaDevolucion} ${nuevaHoraFin}:00`,
                    motivo: motivo
                };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Mostrar loading
                Swal.fire({
                    title: 'Procesando...',
                    text: 'Renovando préstamo',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Preparar datos a enviar
                const datosEnviar = {
                    idprestamo: prestamoId,
                    nueva_fecha_prestamo: result.value.nueva_fecha_prestamo,
                    nueva_fecha_devolucion: result.value.nueva_fecha_devolucion,
                    motivo: result.value.motivo || ''
                };
                
                console.log('Datos a enviar para renovación:', datosEnviar);
                
                // Enviar solicitud AJAX
                fetch('<?= base_url('prestamos/renovar') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: 'idprestamo=' + encodeURIComponent(datosEnviar.idprestamo) + 
                          '&nueva_fecha_prestamo=' + encodeURIComponent(datosEnviar.nueva_fecha_prestamo) +
                          '&nueva_fecha_devolucion=' + encodeURIComponent(datosEnviar.nueva_fecha_devolucion) +
                          '&motivo=' + encodeURIComponent(datosEnviar.motivo)
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Response data:', data);
                    if (data.success) {
                        Swal.fire({
                            title: 'Préstamo Renovado',
                            html: `
                                <div class="text-start">
                                    <p class="mb-2"><strong>✅ ${data.message}</strong></p>
                                    <hr>
                                    <p class="mb-1"><i class="ti ti-calendar-event me-2"></i><strong>Nueva fecha de devolución:</strong> ${data.nueva_fecha_devolucion}</p>
                                    <p class="mb-1"><i class="ti ti-refresh me-2"></i><strong>Total de renovaciones:</strong> ${data.renovaciones_totales}</p>
                                    ${data.dias_extension ? `<p class="mb-0"><i class="ti ti-calendar-plus me-2"></i><strong>Extensión:</strong> ${data.dias_extension} días adicionales</p>` : ''}
                                </div>
                            `,
                            icon: 'success',
                            confirmButtonText: 'Entendido',
                            timer: 5000
                        }).then(() => {
                            // Recargar usando función inteligente
                            recargarContenidoPrestamos();
                        });
                    } else {
                        Swal.fire({
                            title: 'Error al Renovar',
                            text: data.message || 'No se pudo renovar el préstamo',
                            icon: 'error',
                            confirmButtonText: 'Entendido'
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

    // Función para procesar devolución
    function procesarDevolucion(prestamoId) {
        console.log('Procesar devolución:', prestamoId);
        
        // Mostrar loading mientras cargamos los tipos de sanción
        Swal.fire({
            title: 'Cargando...',
            text: 'Obteniendo tipos de sanción',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Cargar tipos de sanción desde la base de datos
        fetch('<?= base_url('prestamos/obtener-tipos-sancion') ?>', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(tiposSancion => {
            mostrarModalDevolucion(prestamoId, tiposSancion.data || []);
        })
        .catch(error => {
            console.error('Error al cargar tipos de sanción:', error);
            // Si falla, mostrar modal con opciones predeterminadas
            mostrarModalDevolucion(prestamoId, []);
        });
    }
    
    // Función auxiliar para mostrar el modal de devolución
    function mostrarModalDevolucion(prestamoId, tiposSancion) {
        // Construir opciones de tipo de sanción dinámicamente
        let opcionesTipoSancion = '<option value="">Seleccionar tipo de incidencia...</option>';
        
        if (tiposSancion && tiposSancion.length > 0) {
            tiposSancion.forEach(tipo => {
                // Mostrar TODOS los tipos de sanción disponibles en la base de datos
                opcionesTipoSancion += `<option value="${tipo.idtiposancion}">${tipo.tiposancion}</option>`;
            });
        } else {
            // Opciones por defecto si no se pudieron cargar
            opcionesTipoSancion += `
                <option value="1">Retraso en devolución</option>
                <option value="2">Pérdida de material</option>
                <option value="3">Daño al material</option>
                <option value="4">Incumplimiento de normas</option>
                <option value="5">Comportamiento inadecuado</option>
            `;
        }
        
        Swal.fire({
            title: 'Procesar Devolución',
            html: `
                <p class="mb-3 text-start">Selecciona el estado del material devuelto:</p>
                
                <div class="mb-3 text-start">
                    <label class="form-label fw-bold">
                        <i class="ti ti-clipboard-check me-1"></i>Estado del Material:
                    </label>
                    <select id="estado_devolucion" class="form-select form-select-lg">
                        <option value="bueno" selected>✅ Devuelto en Buen Estado</option>
                        <option value="con_incidencia">⚠️ Devuelto con Incidencia (Daño/Pérdida)</option>
                    </select>
                    <small class="text-muted d-block mt-1">
                        <i class="ti ti-info-circle me-1"></i>Haz clic para ver las opciones disponibles
                    </small>
                </div>
                
                <div id="seccion_incidencia" class="mb-3 text-start" style="display: none;">
                    <label for="tipo_sancion" class="form-label fw-bold">
                        <i class="ti ti-alert-triangle me-1"></i>Tipo de Incidencia<span class="text-danger">*</span>:
                    </label>
                    <select id="tipo_sancion" class="form-select mb-2">
                        ${opcionesTipoSancion}
                    </select>
                    
                    <div id="detalle_incidencia_container" class="mt-2" style="display: none;">
                        <label for="detalle_incidencia" class="form-label fw-bold">
                            <i class="ti ti-file-description me-1"></i>Detalle Específico<span class="text-danger">*</span>:
                        </label>
                        <select id="detalle_incidencia" class="form-select mb-2">
                            <option value="">Seleccionar detalle...</option>
                        </select>
                    </div>
                    
                    <div class="mt-3">
                        <label for="observaciones_devolucion" class="form-label fw-bold">
                            <i class="ti ti-message me-1"></i>Observaciones (opcional):
                        </label>
                        <textarea id="observaciones_devolucion" 
                                  class="form-control" 
                                  placeholder="Puedes agregar detalles adicionales sobre la incidencia, si lo consideras necesario..." 
                                  rows="4"></textarea>
                        <small class="text-muted">Este campo es opcional</small>
                    </div>
                    
                    <div class="alert alert-warning mb-0 mt-3">
                        <small><i class="ti ti-info-circle me-1"></i><strong>Importante:</strong> Se aplicará una sanción según el tipo de incidencia registrada</small>
                    </div>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '<i class="ti ti-check me-1"></i>Procesar Devolución',
            cancelButtonText: '<i class="ti ti-x me-1"></i>Cancelar',
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            width: '600px',
            didOpen: () => {
                const estadoSelect = document.getElementById('estado_devolucion');
                const seccionIncidencia = document.getElementById('seccion_incidencia');
                const tipoSancionSelect = document.getElementById('tipo_sancion');
                const detalleIncidenciaContainer = document.getElementById('detalle_incidencia_container');
                const detalleIncidenciaSelect = document.getElementById('detalle_incidencia');
                
                // Definir detalles específicos según el tipo de incidencia
                const detallesPorTipo = {
                    'daño': [
                        { value: 'paginas_rasgadas', text: 'Páginas rasgadas' },
                        { value: 'paginas_faltantes', text: 'Páginas faltantes' },
                        { value: 'portada_danada', text: 'Portada dañada' },
                        { value: 'manchas_humedad', text: 'Manchas o humedad' },
                        { value: 'lomo_roto', text: 'Lomo roto o despegado' },
                        { value: 'rayones_escritura', text: 'Rayones o escritura' },
                        { value: 'encuadernacion_dañada', text: 'Encuadernación dañada' },
                        { value: 'otro_daño', text: 'Otro tipo de daño' }
                    ],
                    'pérdida': [
                        { value: 'extraviado', text: 'Material extraviado' },
                        { value: 'no_devuelto', text: 'No devuelto en plazo' },
                        { value: 'robado', text: 'Reportado como robado' },
                        { value: 'otro_perdida', text: 'Otra causa de pérdida' }
                    ],
                    'retraso': [
                        { value: 'olvido', text: 'Olvido de fecha de devolución' },
                        { value: 'imposibilidad', text: 'Imposibilidad de asistir' },
                        { value: 'enfermedad', text: 'Enfermedad o emergencia' },
                        { value: 'otro_retraso', text: 'Otro motivo de retraso' }
                    ],
                    'incumplimiento': [
                        { value: 'no_respetar_horarios', text: 'No respetar horarios' },
                        { value: 'uso_inadecuado', text: 'Uso inadecuado del material' },
                        { value: 'prestamo_terceros', text: 'Préstamo a terceros sin autorización' },
                        { value: 'otro_incumplimiento', text: 'Otro incumplimiento' }
                    ],
                    'comportamiento': [
                        { value: 'desorden', text: 'Generar desorden en biblioteca' },
                        { value: 'ruido_excesivo', text: 'Ruido excesivo' },
                        { value: 'falta_respeto', text: 'Falta de respeto al personal' },
                        { value: 'otro_comportamiento', text: 'Otro comportamiento inadecuado' }
                    ]
                };
                
                // Manejar cambio de estado del material
                estadoSelect.addEventListener('change', function() {
                    const estado = this.value;
                    
                    if (estado === 'con_incidencia') {
                        seccionIncidencia.style.display = 'block';
                    } else {
                        seccionIncidencia.style.display = 'none';
                        detalleIncidenciaContainer.style.display = 'none';
                    }
                });
                
                // Manejar cambio de tipo de sanción para mostrar detalles específicos
                tipoSancionSelect.addEventListener('change', function() {
                    const tipoTexto = this.options[this.selectedIndex]?.text.toLowerCase() || '';
                    
                    // Limpiar opciones anteriores
                    detalleIncidenciaSelect.innerHTML = '<option value="">Seleccionar detalle...</option>';
                    
                    // Determinar qué detalles mostrar según el tipo de sanción
                    let detalles = [];
                    if (tipoTexto.includes('daño')) {
                        detalles = detallesPorTipo['daño'];
                    } else if (tipoTexto.includes('pérdida') || tipoTexto.includes('perdida')) {
                        detalles = detallesPorTipo['pérdida'];
                    } else if (tipoTexto.includes('retraso')) {
                        detalles = detallesPorTipo['retraso'];
                    } else if (tipoTexto.includes('incumplimiento') || tipoTexto.includes('norma')) {
                        detalles = detallesPorTipo['incumplimiento'];
                    } else if (tipoTexto.includes('comportamiento')) {
                        detalles = detallesPorTipo['comportamiento'];
                    }
                    
                    // Agregar opciones de detalle
                    if (detalles.length > 0) {
                        detalles.forEach(detalle => {
                            const option = document.createElement('option');
                            option.value = detalle.value;
                            option.textContent = detalle.text;
                            detalleIncidenciaSelect.appendChild(option);
                        });
                        detalleIncidenciaContainer.style.display = 'block';
                    } else {
                        detalleIncidenciaContainer.style.display = 'none';
                    }
                });
            },
            preConfirm: () => {
                const estadoDevolucion = document.getElementById('estado_devolucion').value;
                const observaciones = document.getElementById('observaciones_devolucion').value.trim();
                const tipoSancion = document.getElementById('tipo_sancion')?.value || '';
                const detalleIncidencia = document.getElementById('detalle_incidencia')?.value || '';
                
                
                // Validar tipo de sanción si hay incidencia
                if (estadoDevolucion === 'con_incidencia' && !tipoSancion) {
                    Swal.showValidationMessage('Debes seleccionar el tipo de incidencia');
                    return false;
                }
                
                // Validar detalle de incidencia si está visible
                const detalleContainer = document.getElementById('detalle_incidencia_container');
                if (estadoDevolucion === 'con_incidencia' && detalleContainer.style.display !== 'none' && !detalleIncidencia) {
                    Swal.showValidationMessage('Debes seleccionar el detalle específico de la incidencia');
                    return false;
                }
                
                return {
                    estado_devolucion: estadoDevolucion,
                    idtiposancion: tipoSancion,
                    detalle_incidencia: detalleIncidencia,
                    observaciones: observaciones || ''
                };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const { estado_devolucion, idtiposancion, detalle_incidencia, observaciones } = result.value;
                
                // Determinar el mensaje de loading según el estado
                let loadingText = 'Registrando devolución';
                if (estado_devolucion === 'con_incidencia') {
                    loadingText = 'Registrando devolución con incidencia';
                }
                
                // Mostrar loading
                Swal.fire({
                    title: 'Procesando...',
                    text: loadingText,
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Enviar solicitud AJAX
                fetch('<?= base_url('prestamos/procesar-devolucion') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: 'idprestamo=' + encodeURIComponent(prestamoId) + 
                          '&estado_devolucion=' + encodeURIComponent(estado_devolucion) +
                          '&idtiposancion=' + encodeURIComponent(idtiposancion) +
                          '&detalle_incidencia=' + encodeURIComponent(detalle_incidencia) +
                          '&observaciones=' + encodeURIComponent(observaciones)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        let icon = 'success';
                        let title = 'Devolución Procesada';
                        let htmlContent = data.message;
                        
                        // Personalizar mensaje según el estado
                        if (estado_devolucion === 'con_incidencia') {
                            icon = 'warning';
                            title = 'Devolución con Incidencia Registrada';
                            if (data.sancion_aplicada) {
                                htmlContent += '<br><br><div class="alert alert-warning mt-2 mb-0"><i class="ti ti-alert-triangle me-2"></i>Se ha generado una sanción: <strong>' + (data.tipo_sancion || 'Sanción aplicada') + '</strong></div>';
                            }
                        } else {
                            // Si hubo retraso en devolución normal
                            if (data.con_retraso) {
                                icon = 'warning';
                                title = 'Devolución con Retraso';
                                if (data.sancion_aplicada) {
                                    htmlContent += '<br><br><div class="alert alert-warning mt-2 mb-0"><i class="ti ti-clock me-2"></i>Se ha generado una sanción por retraso en la devolución</div>';
                                }
                            }
                        }
                        
                        Swal.fire({
                            title: title,
                            html: htmlContent,
                            icon: icon,
                            timer: (estado_devolucion === 'bueno' && !data.con_retraso) ? 3000 : null,
                            showConfirmButton: true,
                            confirmButtonText: 'Entendido'
                        }).then(() => {
                            recargarContenidoPrestamos();
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: data.message || 'No se pudo procesar la devolución',
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

    // Función para cancelar préstamo
    function cancelarPrestamo(prestamoId) {
        console.log('Cancelar préstamo:', prestamoId);
        
        Swal.fire({
            title: '¿Cancelar Préstamo?',
            html: `
                <p class="mb-3 text-start">Esta acción no se puede deshacer. El recurso volverá a estar disponible.</p>
                <div class="mb-3 text-start">
                    <label for="motivo_cancelacion" class="form-label fw-bold">
                        <i class="ti ti-message me-1"></i>Motivo de cancelación (opcional):
                    </label>
                    <textarea id="motivo_cancelacion" 
                              class="form-control" 
                              placeholder="Escribe el motivo por el cual se cancela el préstamo..." 
                              rows="3"></textarea>
                    <small class="text-muted">Puedes dejar este campo vacío si no deseas especificar un motivo.</small>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '<i class="ti ti-x me-1"></i>Sí, cancelar préstamo',
            cancelButtonText: '<i class="ti ti-arrow-back me-1"></i>No cancelar',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            width: '550px',
            preConfirm: () => {
                const motivo = document.getElementById('motivo_cancelacion').value.trim();
                return {
                    motivo: motivo || ''
                };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Mostrar loading
                Swal.fire({
                    title: 'Procesando...',
                    text: 'Cancelando préstamo',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Enviar solicitud AJAX
                fetch('<?= base_url('prestamos/cancelar') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: 'idprestamo=' + encodeURIComponent(prestamoId) + '&motivo=' + encodeURIComponent(result.value.motivo || '')
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'Préstamo Cancelado',
                            text: data.message || 'El préstamo ha sido cancelado correctamente',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            recargarContenidoPrestamos();
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: data.message || 'No se pudo cancelar el préstamo',
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

    // Función para mostrar modal de nuevo préstamo
    function mostrarModalNuevoPrestamo() {
        Swal.fire({
            title: '<i class="ti ti-bookmark-plus me-2"></i>Nuevo Préstamo',
            html: `
                <div class="text-start">
                    <p class="text-muted mb-4">Completa la información para registrar un nuevo préstamo</p>
                    
                    <!-- Búsqueda de Usuario -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="ti ti-user-search me-2"></i>Buscar Usuario
                        </h6>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="ti ti-search"></i></span>
                            <input type="text" 
                                   id="buscar_usuario" 
                                   class="form-control" 
                                   placeholder="Buscar por nombre, documento o usuario...">
                            <button class="btn btn-outline-primary" type="button" onclick="buscarUsuario()">
                                <i class="ti ti-search me-1"></i>Buscar
                            </button>
                        </div>
                        <div id="resultado_busqueda_usuario" class="mt-2" style="display: none;">
                            <!-- Resultados de búsqueda -->
                        </div>
                        <div id="usuario_seleccionado" class="alert alert-info mt-2" style="display: none;">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>Usuario seleccionado:</strong><br>
                                    <span id="nombre_usuario_sel"></span><br>
                                    <small id="doc_usuario_sel" class="text-muted"></small>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="limpiarUsuarioSeleccionado()">
                                    <i class="ti ti-x"></i>
                                </button>
                            </div>
                        </div>
                        <input type="hidden" id="idusuario_prestamo" value="">
                    </div>

                    <hr>

                    <!-- Búsqueda de Recurso -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="ti ti-book-2 me-2"></i>Buscar Recurso
                        </h6>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="ti ti-search"></i></span>
                            <input type="text" 
                                   id="buscar_recurso" 
                                   class="form-control" 
                                   placeholder="Buscar por título, ISBN, código...">
                            <button class="btn btn-outline-primary" type="button" onclick="buscarRecurso()">
                                <i class="ti ti-search me-1"></i>Buscar
                            </button>
                        </div>
                        <div id="resultado_busqueda_recurso" class="mt-2" style="display: none;">
                            <!-- Resultados de búsqueda -->
                        </div>
                        <div id="recurso_seleccionado" class="alert alert-success mt-2" style="display: none;">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>Recurso seleccionado:</strong><br>
                                    <span id="nombre_recurso_sel"></span><br>
                                    <small id="codigo_recurso_sel" class="text-muted"></small>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="limpiarRecursoSeleccionado()">
                                    <i class="ti ti-x"></i>
                                </button>
                            </div>
                        </div>
                        <input type="hidden" id="idejemplar_prestamo" value="">
                    </div>

                    <hr>

                    <!-- Fecha y Horarios de Préstamo -->
                    <div class="mb-3">
                        <h6 class="text-primary mb-3">
                            <i class="ti ti-calendar-time me-2"></i>Fecha y Horarios de Préstamo
                        </h6>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="fecha_prestamo" class="form-label fw-bold">
                                    <i class="ti ti-calendar me-1"></i>Fecha de uso:
                                </label>
                                <input type="date" 
                                       id="fecha_prestamo" 
                                       class="form-control">
                                <div class="invalid-feedback" style="display: none;"></div>
                                <small class="text-muted">Solo días de lunes a viernes</small>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="hora_inicio_prestamo" class="form-label fw-bold">
                                    <i class="ti ti-clock me-1"></i>Hora de inicio:
                                </label>
                                <input type="time" 
                                       id="hora_inicio_prestamo" 
                                       class="form-control"
                                       min="08:00"
                                       max="12:59"
                                       value="08:00">
                                <div class="invalid-feedback" style="display: none;"></div>
                                <small class="text-muted">Entre 8:00 AM y 12:59 PM</small>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="hora_fin_prestamo" class="form-label fw-bold">
                                    <i class="ti ti-clock me-1"></i>Hora de fin:
                                </label>
                                <input type="time" 
                                       id="hora_fin_prestamo" 
                                       class="form-control"
                                       min="08:01"
                                       max="13:00"
                                       value="13:00">
                                <div class="invalid-feedback" style="display: none;"></div>
                                <small class="text-muted">Entre 8:01 AM y 1:00 PM</small>
                            </div>
                        </div>
                        
                        <!-- Duración del préstamo -->
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-light border mb-0">
                                    <div class="d-flex align-items-center">
                                        <i class="ti ti-clock text-primary me-2"></i>
                                        <div>
                                            <strong>Duración del préstamo:</strong> 
                                            <span id="duracion_prestamo" class="text-success fw-bold">5 horas</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Observaciones -->
                    <div class="mb-3">
                        <label for="observaciones_prestamo" class="form-label fw-bold">
                            <i class="ti ti-message me-1"></i>Observaciones (opcional):
                        </label>
                        <textarea id="observaciones_prestamo" 
                                  class="form-control" 
                                  placeholder="Escribe cualquier observación sobre el préstamo..." 
                                  rows="2"></textarea>
                    </div>

                </div>
            `,
            width: '700px',
            showCancelButton: true,
            confirmButtonText: '<i class="ti ti-check me-1"></i>Crear Préstamo',
            cancelButtonText: '<i class="ti ti-x me-1"></i>Cancelar',
            confirmButtonColor: '#0d6efd',
            cancelButtonColor: '#6c757d',
            showLoaderOnConfirm: true,
            didOpen: () => {
                // Configurar fecha mínima (hoy)
                const hoy = new Date();
                const fechaHoy = hoy.toISOString().split('T')[0];
                document.getElementById('fecha_prestamo').min = fechaHoy;
                document.getElementById('fecha_prestamo').value = fechaHoy;
                
                // Función auxiliar para calcular y mostrar duración
                const actualizarDuracionPrestamo = () => {
                    const horaInicio = document.getElementById('hora_inicio_prestamo')?.value;
                    const horaFin = document.getElementById('hora_fin_prestamo')?.value;
                    const duracionElement = document.getElementById('duracion_prestamo');
                    
                    if (horaInicio && horaFin && duracionElement) {
                        const inicioMinutos = horaInicio.split(':').reduce((h, m) => h * 60 + parseInt(m), 0);
                        const finMinutos = horaFin.split(':').reduce((h, m) => h * 60 + parseInt(m), 0);
                        const diferencia = finMinutos - inicioMinutos;
                        
                        if (diferencia <= 0) {
                            duracionElement.textContent = '0 minutos';
                        } else {
                            const horas = Math.floor(diferencia / 60);
                            const minutos = diferencia % 60;
                            
                            if (horas === 0) {
                                duracionElement.textContent = `${minutos} minutos`;
                            } else if (minutos === 0) {
                                duracionElement.textContent = `${horas} hora${horas > 1 ? 's' : ''}`;
                            } else {
                                duracionElement.textContent = `${horas} hora${horas > 1 ? 's' : ''} y ${minutos} minutos`;
                            }
                        }
                    }
                };
                
                // Agregar validación en tiempo real
                const fechaInput = document.getElementById('fecha_prestamo');
                const horaInicioInput = document.getElementById('hora_inicio_prestamo');
                const horaFinInput = document.getElementById('hora_fin_prestamo');
                
                fechaInput.addEventListener('change', function() {
                    validarFechaPrestamo(this);
                });
                
                horaInicioInput.addEventListener('change', function() {
                    validarHoraPrestamo(this, 'inicio');
                    actualizarDuracionPrestamo();
                });
                
                horaFinInput.addEventListener('change', function() {
                    validarHoraPrestamo(this, 'fin');
                    actualizarDuracionPrestamo();
                });
                
                // Calcular duración inicial
                actualizarDuracionPrestamo();
            },
            preConfirm: () => {
                const idusuario = document.getElementById('idusuario_prestamo').value;
                const idejemplar = document.getElementById('idejemplar_prestamo').value;
                const fechaPrestamo = document.getElementById('fecha_prestamo').value;
                const horaInicio = document.getElementById('hora_inicio_prestamo').value;
                const horaFin = document.getElementById('hora_fin_prestamo').value;
                const observaciones = document.getElementById('observaciones_prestamo').value;
                
                // Validaciones
                if (!idusuario) {
                    Swal.showValidationMessage('Debes seleccionar un usuario');
                    return false;
                }
                
                if (!idejemplar) {
                    Swal.showValidationMessage('Debes seleccionar un recurso disponible');
                    return false;
                }
                
                if (!fechaPrestamo) {
                    Swal.showValidationMessage('Debes seleccionar una fecha de préstamo');
                    return false;
                }
                
                if (!horaInicio) {
                    Swal.showValidationMessage('Debes seleccionar una hora de inicio');
                    return false;
                }
                
                if (!horaFin) {
                    Swal.showValidationMessage('Debes seleccionar una hora de fin');
                    return false;
                }
                
                // Validar día laboral
                const fechaPartes = fechaPrestamo.split('-');
                const fechaSeleccionada = new Date(fechaPartes[0], fechaPartes[1] - 1, fechaPartes[2]);
                const dia = fechaSeleccionada.getDay();
                
                if (dia === 0 || dia === 6) {
                    Swal.showValidationMessage('Solo se pueden programar préstamos de lunes a viernes');
                    return false;
                }
                
                // Validar horarios
                const inicioMinutos = horaInicio.split(':').reduce((h, m) => h * 60 + parseInt(m), 0);
                const finMinutos = horaFin.split(':').reduce((h, m) => h * 60 + parseInt(m), 0);
                const HORA_MIN = 8 * 60;
                const HORA_MAX = 13 * 60;
                
                // Validar hora de inicio
                if (inicioMinutos < HORA_MIN || inicioMinutos >= HORA_MAX) {
                    Swal.showValidationMessage('La hora de inicio debe estar entre 8:00 AM y 12:59 PM');
                    return false;
                }
                
                // Validar hora de fin
                if (finMinutos <= HORA_MIN || finMinutos > HORA_MAX) {
                    Swal.showValidationMessage('La hora de fin debe estar entre 8:01 AM y 1:00 PM');
                    return false;
                }
                
                // Validar que hora de fin sea posterior a hora de inicio
                if (finMinutos <= inicioMinutos) {
                    Swal.showValidationMessage('La hora de fin debe ser posterior a la hora de inicio');
                    return false;
                }
                
                // Crear el préstamo
                return fetch('<?= base_url('prestamos/crear') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: 'idusuario=' + encodeURIComponent(idusuario) +
                          '&idejemplar=' + encodeURIComponent(idejemplar) +
                          '&fechaPrestamo=' + encodeURIComponent(fechaPrestamo) +
                          '&horaInicio=' + encodeURIComponent(horaInicio) +
                          '&horaFin=' + encodeURIComponent(horaFin) +
                          '&observaciones=' + encodeURIComponent(observaciones || '')
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        throw new Error(data.message || 'No se pudo crear el préstamo');
                    }
                    return data;
                })
                .catch(error => {
                    Swal.showValidationMessage(error.message);
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                Swal.fire({
                    title: '¡Préstamo Creado!',
                    html: `
                        <div class="text-start">
                            <p class="mb-2"><strong>✅ ${result.value.message}</strong></p>
                            <hr>
                            <p class="mb-1"><i class="ti ti-bookmark me-2"></i><strong>Código:</strong> ${result.value.codigo_prestamo || 'N/A'}</p>
                            <p class="mb-1"><i class="ti ti-calendar-event me-2"></i><strong>Fecha de devolución:</strong> ${result.value.fecha_devolucion || 'N/A'}</p>
                            <p class="mb-0"><i class="ti ti-user me-2"></i><strong>Usuario:</strong> ${result.value.usuario || 'N/A'}</p>
                        </div>
                    `,
                    icon: 'success',
                    confirmButtonText: 'Entendido',
                    timer: 5000
                }).then(() => {
                    recargarContenidoPrestamos();
                });
            }
        });
    }

    // Función auxiliar para buscar usuario
    function buscarUsuario() {
        const termino = document.getElementById('buscar_usuario').value.trim();
        const resultadoDiv = document.getElementById('resultado_busqueda_usuario');
        
        if (!termino) {
            Swal.showValidationMessage('Ingresa un término de búsqueda');
            return;
        }
        
        resultadoDiv.innerHTML = '<div class="text-center py-2"><div class="spinner-border spinner-border-sm" role="status"></div> Buscando...</div>';
        resultadoDiv.style.display = 'block';
        
        fetch('<?= base_url('usuarios/buscar-ajax') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: 'termino=' + encodeURIComponent(termino)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.usuarios && data.usuarios.length > 0) {
                let html = '<div class="list-group">';
                data.usuarios.forEach(usuario => {
                    html += `
                        <a href="#" class="list-group-item list-group-item-action" onclick="seleccionarUsuario(${usuario.idusuario}, '${usuario.nombre_completo}', '${usuario.documento}'); return false;">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">${usuario.nombre_completo}</h6>
                                <small class="badge bg-primary">${usuario.nivel_acceso || 'Usuario'}</small>
                            </div>
                            <small class="text-muted">${usuario.tipo_documento || 'Doc'}: ${usuario.documento}</small>
                        </a>
                    `;
                });
                html += '</div>';
                resultadoDiv.innerHTML = html;
            } else {
                resultadoDiv.innerHTML = '<div class="alert alert-warning mb-0"><i class="ti ti-alert-circle me-2"></i>No se encontraron usuarios</div>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            resultadoDiv.innerHTML = '<div class="alert alert-danger mb-0">Error al buscar usuarios</div>';
        });
    }

    // Función auxiliar para seleccionar usuario
    function seleccionarUsuario(idusuario, nombre, documento) {
        document.getElementById('idusuario_prestamo').value = idusuario;
        document.getElementById('nombre_usuario_sel').textContent = nombre;
        document.getElementById('doc_usuario_sel').textContent = 'Documento: ' + documento;
        document.getElementById('usuario_seleccionado').style.display = 'block';
        document.getElementById('resultado_busqueda_usuario').style.display = 'none';
        document.getElementById('buscar_usuario').value = '';
    }

    // Función auxiliar para limpiar usuario seleccionado
    function limpiarUsuarioSeleccionado() {
        document.getElementById('idusuario_prestamo').value = '';
        document.getElementById('usuario_seleccionado').style.display = 'none';
    }

    // Función auxiliar para buscar recurso
    function buscarRecurso() {
        const termino = document.getElementById('buscar_recurso').value.trim();
        const resultadoDiv = document.getElementById('resultado_busqueda_recurso');
        
        if (!termino) {
            Swal.showValidationMessage('Ingresa un término de búsqueda');
            return;
        }
        
        resultadoDiv.innerHTML = '<div class="text-center py-2"><div class="spinner-border spinner-border-sm" role="status"></div> Buscando...</div>';
        resultadoDiv.style.display = 'block';
        
        fetch('<?= base_url('recursos/buscar-disponibles-ajax') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: 'termino=' + encodeURIComponent(termino)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.recursos && data.recursos.length > 0) {
                let html = '<div class="list-group">';
                data.recursos.forEach(recurso => {
                    html += `
                        <a href="#" class="list-group-item list-group-item-action" onclick="seleccionarRecurso(${recurso.idejemplar}, '${recurso.titulo}', '${recurso.codigo_ejemplar}'); return false;">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">${recurso.titulo}</h6>
                                <small class="badge bg-success">Disponible</small>
                            </div>
                            <small class="text-muted">Código: ${recurso.codigo_ejemplar} | ${recurso.tipo_recurso || 'Físico'}</small>
                        </a>
                    `;
                });
                html += '</div>';
                resultadoDiv.innerHTML = html;
            } else {
                resultadoDiv.innerHTML = '<div class="alert alert-warning mb-0"><i class="ti ti-alert-circle me-2"></i>No se encontraron recursos disponibles</div>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            resultadoDiv.innerHTML = '<div class="alert alert-danger mb-0">Error al buscar recursos</div>';
        });
    }

    // Función auxiliar para seleccionar recurso
    function seleccionarRecurso(idejemplar, titulo, codigo) {
        document.getElementById('idejemplar_prestamo').value = idejemplar;
        document.getElementById('nombre_recurso_sel').textContent = titulo;
        document.getElementById('codigo_recurso_sel').textContent = 'Código: ' + codigo;
        document.getElementById('recurso_seleccionado').style.display = 'block';
        document.getElementById('resultado_busqueda_recurso').style.display = 'none';
        document.getElementById('buscar_recurso').value = '';
    }

    // Función auxiliar para limpiar recurso seleccionado
    function limpiarRecursoSeleccionado() {
        document.getElementById('idejemplar_prestamo').value = '';
        document.getElementById('recurso_seleccionado').style.display = 'none';
    }

    // ===== FUNCIONES DE VALIDACIÓN COMPLETAS (como paginaPrincipal.php) =====
    
    /**
     * Validar fecha de préstamo - solo días laborales (lunes a viernes)
     */
    function validarFechaPrestamo(input) {
        const fechaValor = input.value;
        const feedback = input.nextElementSibling;
        
        if (!fechaValor) {
            input.classList.add('is-invalid');
            if (feedback && feedback.classList.contains('invalid-feedback')) {
                feedback.textContent = 'La fecha es obligatoria.';
                feedback.style.display = 'block';
            }
            return false;
        }
        
        // Validar que sea un día laboral
        const fechaPartes = fechaValor.split('-');
        const fechaSeleccionada = new Date(fechaPartes[0], fechaPartes[1] - 1, fechaPartes[2]);
        const dia = fechaSeleccionada.getDay();
        
        if (dia === 0 || dia === 6) {
            input.classList.add('is-invalid');
            if (feedback && feedback.classList.contains('invalid-feedback')) {
                feedback.textContent = 'Solo se pueden programar préstamos de lunes a viernes.';
                feedback.style.display = 'block';
            }
            
            // Auto-corrección: mover al siguiente lunes
            setTimeout(() => {
                const diasHastaLunes = dia === 0 ? 1 : (8 - dia);
                fechaSeleccionada.setDate(fechaSeleccionada.getDate() + diasHastaLunes);
                input.value = fechaSeleccionada.toISOString().split('T')[0];
                input.classList.remove('is-invalid');
                if (feedback) feedback.style.display = 'none';
            }, 2000);
            
            return false;
        }
        
        // Validar que no sea una fecha pasada
        const hoy = new Date();
        hoy.setHours(0, 0, 0, 0);
        fechaSeleccionada.setHours(0, 0, 0, 0);
        
        if (fechaSeleccionada < hoy) {
            input.classList.add('is-invalid');
            if (feedback && feedback.classList.contains('invalid-feedback')) {
                feedback.textContent = 'No se puede seleccionar una fecha pasada.';
                feedback.style.display = 'block';
            }
            return false;
        }
        
        // Si todo está bien, limpiar errores
        input.classList.remove('is-invalid');
        if (feedback && feedback.classList.contains('invalid-feedback')) {
            feedback.style.display = 'none';
        }
        
        return true;
    }
    
    /**
     * Validar hora de préstamo - entre 8:00 AM y 1:00 PM
     * @param {HTMLElement} input - El input de hora a validar
     * @param {string} tipo - 'inicio' o 'fin'
     */
    function validarHoraPrestamo(input, tipo) {
        const horaValor = input.value;
        const feedback = input.nextElementSibling;
        
        if (!horaValor) {
            input.classList.add('is-invalid');
            if (feedback && feedback.classList.contains('invalid-feedback')) {
                feedback.textContent = 'La hora es obligatoria.';
                feedback.style.display = 'block';
            }
            return false;
        }
        
        // Validar rango de horario (8:00 AM - 1:00 PM)
        const [horas, minutos] = horaValor.split(':');
        const horaMinutos = parseInt(horas) * 60 + parseInt(minutos);
        const HORA_MIN = 8 * 60;  // 8:00 AM
        const HORA_MAX = 13 * 60; // 1:00 PM
        
        if (tipo === 'inicio') {
            // Hora de inicio: 8:00 AM - 12:59 PM
            if (horaMinutos < HORA_MIN || horaMinutos >= HORA_MAX) {
                input.classList.add('is-invalid');
                if (feedback && feedback.classList.contains('invalid-feedback')) {
                    feedback.textContent = 'La hora de inicio debe estar entre 8:00 AM y 12:59 PM.';
                    feedback.style.display = 'block';
                }
                
                // Auto-corrección
                setTimeout(() => {
                    input.value = '08:00';
                    input.classList.remove('is-invalid');
                    if (feedback) feedback.style.display = 'none';
                }, 2000);
                
                return false;
            }
        } else if (tipo === 'fin') {
            // Hora de fin: 8:01 AM - 1:00 PM
            if (horaMinutos <= HORA_MIN || horaMinutos > HORA_MAX) {
                input.classList.add('is-invalid');
                if (feedback && feedback.classList.contains('invalid-feedback')) {
                    feedback.textContent = 'La hora de fin debe estar entre 8:01 AM y 1:00 PM.';
                    feedback.style.display = 'block';
                }
                
                // Auto-corrección
                setTimeout(() => {
                    input.value = '13:00';
                    input.classList.remove('is-invalid');
                    if (feedback) feedback.style.display = 'none';
                }, 2000);
                
                return false;
            }
            
            // Validar que hora fin sea posterior a hora inicio
            const horaInicioInput = document.getElementById('hora_inicio_prestamo');
            if (horaInicioInput && horaInicioInput.value) {
                const inicioMinutos = horaInicioInput.value.split(':').reduce((h, m) => h * 60 + parseInt(m), 0);
                if (horaMinutos <= inicioMinutos) {
                    input.classList.add('is-invalid');
                    if (feedback && feedback.classList.contains('invalid-feedback')) {
                        feedback.textContent = 'La hora de fin debe ser posterior a la hora de inicio.';
                        feedback.style.display = 'block';
                    }
                    
                    // Auto-corrección
                    setTimeout(() => {
                        const nuevaHora = Math.min(inicioMinutos + 60, HORA_MAX);
                        const horas = Math.floor(nuevaHora / 60).toString().padStart(2, '0');
                        const mins = (nuevaHora % 60).toString().padStart(2, '0');
                        input.value = `${horas}:${mins}`;
                        input.classList.remove('is-invalid');
                        if (feedback) feedback.style.display = 'none';
                        
                        // Actualizar duración
                        const duracionElement = document.getElementById('duracion_prestamo');
                        if (duracionElement) {
                            const diferencia = nuevaHora - inicioMinutos;
                            const h = Math.floor(diferencia / 60);
                            const m = diferencia % 60;
                            if (h === 0) duracionElement.textContent = `${m} minutos`;
                            else if (m === 0) duracionElement.textContent = `${h} hora${h > 1 ? 's' : ''}`;
                            else duracionElement.textContent = `${h} hora${h > 1 ? 's' : ''} y ${m} minutos`;
                        }
                    }, 2000);
                    
                    return false;
                }
            }
        }
        
        // Si todo está bien, limpiar errores
        input.classList.remove('is-invalid');
        if (feedback && feedback.classList.contains('invalid-feedback')) {
            feedback.style.display = 'none';
        }
        
        return true;
    }
    
    /**
     * Función auxiliar para calcular duración entre horas
     */
    function calcularDuracion(horaInicio, horaFin) {
        if (!horaInicio || !horaFin) return '0 minutos';
        
        const inicioMinutos = horaInicio.split(':').reduce((h, m) => h * 60 + parseInt(m), 0);
        const finMinutos = horaFin.split(':').reduce((h, m) => h * 60 + parseInt(m), 0);
        const diferencia = finMinutos - inicioMinutos;
        
        if (diferencia <= 0) return '0 minutos';
        
        const horas = Math.floor(diferencia / 60);
        const minutos = diferencia % 60;
        
        if (horas === 0) return `${minutos} minutos`;
        if (minutos === 0) return `${horas} hora${horas > 1 ? 's' : ''}`;
        return `${horas} hora${horas > 1 ? 's' : ''} y ${minutos} minutos`;
    }
    
    /**
     * Validación unificada del formulario de préstamo
     */
    function validarFormularioPrestamo(esValidacionFinal = false) {
        const fechaPrestamo = document.getElementById('fecha_prestamo')?.value;
        const horaInicio = document.getElementById('hora_inicio_prestamo')?.value;
        const horaFin = document.getElementById('hora_fin_prestamo')?.value;
        let hasErrors = false;
        
        // Limpiar errores anteriores
        if (esValidacionFinal) {
            const inputs = document.querySelectorAll('#fecha_prestamo, #hora_inicio_prestamo, #hora_fin_prestamo');
            inputs.forEach(el => {
                el.classList.remove('is-invalid');
                const feedback = el.nextElementSibling;
                if (feedback && feedback.classList.contains('invalid-feedback')) {
                    feedback.style.display = 'none';
                }
            });
        }
        
        // Función auxiliar para mostrar error
        const mostrarError = (inputId, mensaje) => {
            const input = document.getElementById(inputId);
            const feedback = input?.nextElementSibling;
            if (input && feedback && feedback.classList.contains('invalid-feedback')) {
                input.classList.add('is-invalid');
                feedback.textContent = mensaje;
                feedback.style.display = 'block';
                hasErrors = true;
            }
        };
        
        // Validar campos requeridos
        if (!fechaPrestamo) mostrarError('fecha_prestamo', 'La fecha es obligatoria.');
        if (!horaInicio) mostrarError('hora_inicio_prestamo', 'La hora de inicio es obligatoria.');
        if (!horaFin) mostrarError('hora_fin_prestamo', 'La hora de fin es obligatoria.');
        
        // Validar día laboral
        if (fechaPrestamo) {
            const fechaPartes = fechaPrestamo.split('-');
            const fechaSeleccionada = new Date(fechaPartes[0], fechaPartes[1] - 1, fechaPartes[2]);
            const dia = fechaSeleccionada.getDay();
            
            if (dia === 0 || dia === 6) {
                mostrarError('fecha_prestamo', 'Solo se pueden programar préstamos de lunes a viernes.');
                
                // Auto-corrección
                if (!esValidacionFinal) {
                    setTimeout(() => {
                        const diasHastaLunes = dia === 0 ? 1 : (8 - dia);
                        fechaSeleccionada.setDate(fechaSeleccionada.getDate() + diasHastaLunes);
                        
                        const fechaInput = document.getElementById('fecha_prestamo');
                        const feedback = fechaInput?.nextElementSibling;
                        fechaInput.value = fechaSeleccionada.toISOString().split('T')[0];
                        fechaInput.classList.remove('is-invalid');
                        if (feedback) feedback.style.display = 'none';
                    }, 2000);
                }
            }
        }
        
        // Validar horarios
        if (horaInicio && horaFin) {
            const inicioMinutos = horaInicio.split(':').reduce((h, m) => h * 60 + parseInt(m), 0);
            const finMinutos = horaFin.split(':').reduce((h, m) => h * 60 + parseInt(m), 0);
            const HORA_MIN = 8 * 60;
            const HORA_MAX = 13 * 60;
            
            // Validar hora de inicio
            if (inicioMinutos < HORA_MIN || inicioMinutos >= HORA_MAX) {
                mostrarError('hora_inicio_prestamo', 'La hora de inicio debe estar entre 8:00 AM y 12:59 PM.');
                
                // Auto-corrección
                if (!esValidacionFinal) {
                    setTimeout(() => {
                        const horaInput = document.getElementById('hora_inicio_prestamo');
                        const feedback = horaInput?.nextElementSibling;
                        horaInput.value = '08:00';
                        horaInput.classList.remove('is-invalid');
                        if (feedback) feedback.style.display = 'none';
                    }, 2000);
                }
            }
            
            // Validar hora de fin
            if (finMinutos <= HORA_MIN || finMinutos > HORA_MAX) {
                mostrarError('hora_fin_prestamo', 'La hora de fin debe estar entre 8:01 AM y 1:00 PM.');
                
                // Auto-corrección
                if (!esValidacionFinal) {
                    setTimeout(() => {
                        const horaInput = document.getElementById('hora_fin_prestamo');
                        const feedback = horaInput?.nextElementSibling;
                        horaInput.value = '13:00';
                        horaInput.classList.remove('is-invalid');
                        if (feedback) feedback.style.display = 'none';
                    }, 2000);
                }
            }
            
            // Validar secuencia de horarios
            if (finMinutos <= inicioMinutos) {
                mostrarError('hora_fin_prestamo', 'La hora de fin debe ser posterior a la hora de inicio.');
                
                // Auto-corrección
                if (!esValidacionFinal) {
                    setTimeout(() => {
                        const nuevaHora = Math.min(inicioMinutos + 60, HORA_MAX);
                        const horas = Math.floor(nuevaHora / 60).toString().padStart(2, '0');
                        const minutos = (nuevaHora % 60).toString().padStart(2, '0');
                        
                        const horaInput = document.getElementById('hora_fin_prestamo');
                        const feedback = horaInput?.nextElementSibling;
                        horaInput.value = `${horas}:${minutos}`;
                        horaInput.classList.remove('is-invalid');
                        if (feedback) feedback.style.display = 'none';
                    }, 2000);
                }
            }
        }
        
        return !hasErrors;
    }

    // Inicializar tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });

</script>