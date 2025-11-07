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
                    <a href="<?= base_url('historial-prestamos/exportar-excel') ?>" class="btn btn-success btn-sm">
                        <i class="ti ti-file-excel"></i> Exportar Excel
                    </a>
                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmarEliminarTodoHistorial()">
                        <i class="ti ti-trash"></i> Limpiar Historial
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
                                <option value="rechazado">Rechazado</option>
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
            </div>
        </div>
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tablaHistorial">
                    <thead class="table-light">
                        <tr class="text-uppercase small fw-semibold text-muted">
                            <th class="border-0 px-3 py-3">Usuario</th>
                            <th class="border-0 px-3 py-3">Recurso</th>
                            <th class="border-0 px-3 py-3">Período del Préstamo</th>
                            <th class="border-0 text-center px-3 py-3">Cantidad</th>
                            <th class="border-0 text-center px-3 py-3">Estado Final</th>
                            <th class="border-0 text-center px-3 py-3">Observaciones</th>
                            <th class="border-0 text-center px-3 py-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($historial)): ?>
                            <?php foreach ($historial as $registro): ?>
                                <tr class="border-bottom">
                                    <td class="px-3 py-3">
                                        <div>
                                            <h6 class="mb-1 fw-medium"><?= esc($registro['usuario']) ?></h6>
                                            <p class="text-muted mb-0 small">CC: <?= esc($registro['documento']) ?></p>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3">
                                        <div>
                                            <h6 class="mb-1 fw-medium"><?= esc($registro['recurso']) ?></h6>
                                            <p class="text-muted mb-0 small">
                                                <i class="ti ti-book me-1"></i>
                                                Código: <?= esc($registro['codigo_ejemplar'] ?? 'N/A') ?>
                                            </p>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3">
                                        <div>
                                            <p class="mb-1 small">
                                                <i class="ti ti-calendar-plus text-primary me-1"></i>
                                                <strong>Inicio:</strong> <?= date('d/m/Y', strtotime($registro['fecha_prestamo'] ?? $registro['fechaprestamo'] ?? date('Y-m-d'))) ?>
                                            </p>
                                            <?php if ($registro['estado_final'] !== 'Rechazado'): ?>
                                            <p class="mb-1 small">
                                                <i class="ti ti-calendar-check text-success me-1"></i>
                                                <strong>Devuelto:</strong> <?= $registro['fecha_devolucion'] ? date('d/m/Y', strtotime($registro['fecha_devolucion'])) : 'N/A' ?>
                                            </p>
                                            <p class="mb-0 small text-muted">
                                                <i class="ti ti-clock-hour-3 me-1"></i>
                                                Duración: 
                                                <?php
                                                    $fechaInicio = new DateTime($registro['fecha_prestamo'] ?? $registro['fechaprestamo'] ?? date('Y-m-d'));
                                                    $fechaFin = new DateTime($registro['fecha_devolucion']);
                                                    $diff = $fechaInicio->diff($fechaFin);
                                                    echo $diff->days . ' día' . ($diff->days != 1 ? 's' : '');
                                                ?>
                                            </p>
                                            <?php else: ?>
                                            <p class="mb-0 small text-danger">
                                                <i class="ti ti-x text-danger me-1"></i>
                                                <strong>Rechazado:</strong> <?= $registro['fecha_registro'] ? date('d/m/Y', strtotime($registro['fecha_registro'])) : 'N/A' ?>
                                            </p>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3 text-center">
                                        <div class="d-flex flex-column align-items-center">
                                            <span class="badge bg-info-subtle text-info fs-6 px-3 py-2 mb-1">
                                                <?= isset($registro['cantidad']) ? $registro['cantidad'] : 1 ?>
                                            </span>
                                            <small class="text-muted">
                                                <?= (isset($registro['cantidad']) && $registro['cantidad'] == 1) ? 'ejemplar' : 'ejemplares' ?>
                                            </small>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3 text-center">
                                        <?php if ($registro['estado_final'] === 'Rechazado'): ?>
                                            <span class="badge bg-secondary-subtle text-secondary">
                                                <i class="ti ti-ban me-1"></i>Rechazado
                                            </span>
                                            <small class="d-block text-muted mt-1">No aprobado</small>
                                        <?php else: ?>
                                            <?php 
                                                $horasTotal = $registro['horas_retraso_total'] ?? 0;
                                                $diasRetraso = $registro['dias_retraso'] ?? 0;
                                                $multa = $registro['multa'] ?? 0;
                                                $tieneIncidencia = isset($registro['tiene_incidencia']) && $registro['tiene_incidencia'] == 1;
                                            ?>
                                            
                                            <?php if ($tieneIncidencia): ?>
                                                <!-- Devuelto con incidencia (daño/pérdida) -->
                                                <span class="badge bg-danger-subtle text-danger">
                                                    <i class="ti ti-alert-triangle me-1"></i>Con Incidencia
                                                </span>
                                                <small class="d-block text-danger fw-semibold mt-1">
                                                    <?= esc($registro['tipo_incidencia'] ?? 'Incidencia') ?>
                                                </small>
                                            <?php elseif ($horasTotal <= 0): ?>
                                                <span class="badge bg-success-subtle text-success">
                                                    <i class="ti ti-check-circle me-1"></i>Devuelto a Tiempo
                                                </span>
                                                <small class="d-block text-muted mt-1">Sin penalización</small>
                                            <?php elseif ($horasTotal > 0): ?>
                                                <span class="badge bg-warning-subtle text-warning">
                                                    <i class="ti ti-clock-exclamation me-1"></i>Con Retraso
                                                </span>
                                                <?php if ($horasTotal < 24): ?>
                                                    <small class="d-block text-warning fw-semibold mt-1"><?= $horasTotal ?> hora<?= $horasTotal != 1 ? 's' : '' ?></small>
                                                <?php else: ?>
                                                    <small class="d-block text-warning fw-semibold mt-1"><?= $diasRetraso ?> día<?= $diasRetraso != 1 ? 's' : '' ?></small>
                                                <?php endif; ?>
                                                <?php if ($multa > 0): ?>
                                                    <small class="d-block text-danger mt-1">
                                                        <i class="ti ti-cash me-1"></i>Multa: $<?= number_format($multa) ?>
                                                    </small>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="badge bg-info-subtle text-info">
                                                    <i class="ti ti-clock me-1"></i>Anticipado
                                                </span>
                                                <small class="d-block text-muted mt-1"><?= abs($diasRetraso) ?> día<?= abs($diasRetraso) != 1 ? 's' : '' ?> antes</small>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-3 py-3" style="max-width: 200px;">
                                        <?php 
                                        // Verificar si hay incidencia
                                        $tieneIncidencia = isset($registro['tiene_incidencia']) && $registro['tiene_incidencia'] == 1;
                                        
                                        // Obtener y limpiar las observaciones
                                        $observaciones = $registro['observaciones'] ?? null;
                                        
                                        // Si es una solicitud rechazada, limpiar la parte de "Cantidad solicitada:"
                                        if ($registro['estado_final'] === 'Rechazado' && !empty($observaciones)) {
                                            // Remover "Cantidad solicitada: X ejemplares. " del inicio
                                            $observaciones = preg_replace('/^Cantidad solicitada:\s*\d+\s*ejemplares?\.\s*/', '', $observaciones);
                                        }
                                        
                                        $tieneObservaciones = !empty($observaciones) && trim($observaciones) !== '' && $observaciones !== 'NULL';
                                        $longitudMaxima = 80; // Caracteres a mostrar antes de truncar
                                        ?>
                                        
                                        <?php if ($tieneIncidencia): ?>
                                            <!-- Mostrar información de incidencia -->
                                            <div class="text-start">
                                                <div class="alert alert-danger alert-sm mb-2 py-2 px-2">
                                                    <div class="d-flex align-items-start">
                                                        <i class="ti ti-alert-triangle text-danger me-2 mt-1"></i>
                                                        <div class="flex-grow-1">
                                                            <strong class="d-block small"><?= esc($registro['tipo_incidencia'] ?? 'Incidencia') ?></strong>
                                                            <?php if (!empty($registro['detalle_incidencia'])): ?>
                                                                <small class="text-muted"><?= esc($registro['detalle_incidencia']) ?></small>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="button" 
                                                        class="btn btn-link btn-sm p-0 text-decoration-none text-danger" 
                                                        onclick='mostrarDetalleIncidencia(<?= json_encode([
                                                            'tipo' => $registro['tipo_incidencia'] ?? 'Incidencia',
                                                            'detalle' => $registro['detalle_incidencia'] ?? '',
                                                            'observaciones' => $registro['observaciones_incidencia'] ?? '',
                                                            'fecha' => $registro['fecha_sancion'] ?? '',
                                                            'usuario' => $registro['usuario'] ?? ''
                                                        ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)'>
                                                    <small><i class="ti ti-eye me-1"></i>Ver detalles de incidencia</small>
                                                </button>
                                            </div>
                                        <?php elseif ($tieneObservaciones): ?>
                                            <div class="text-start">
                                                <?php if (strlen($observaciones) > $longitudMaxima): ?>
                                                    <!-- Observación larga - mostrar resumen -->
                                                    <p class="mb-1 small text-muted">
                                                        <i class="ti ti-message-circle me-1"></i>
                                                        <?= esc(substr($observaciones, 0, $longitudMaxima)) ?>...
                                                    </p>
                                                    <button type="button" 
                                                            class="btn btn-link btn-sm p-0 text-decoration-none" 
                                                            onclick="mostrarObservaciones(<?= json_encode($observaciones, JSON_HEX_APOS | JSON_HEX_QUOT) ?>, <?= json_encode($registro['usuario'], JSON_HEX_APOS | JSON_HEX_QUOT) ?>)">
                                                        <small><i class="ti ti-eye me-1"></i>Ver completo</small>
                                                    </button>
                                                <?php else: ?>
                                                    <!-- Observación corta - mostrar completa -->
                                                    <p class="mb-0 small text-muted">
                                                        <i class="ti ti-message-circle me-1"></i>
                                                        <?= esc($observaciones) ?>
                                                    </p>
                                                <?php endif; ?>
                                            </div>
                                        <?php else: ?>
                                            <div class="text-center">
                                                <span class="text-muted small">
                                                    <i class="ti ti-minus"></i> Sin observaciones
                                                </span>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-3 py-3 text-center">
                                        <div class="d-flex gap-2 justify-content-center">
                                            <?php if ($registro['estado_final'] === 'Rechazado'): ?>
                                                <button type="button" class="btn btn-sm btn-outline-secondary" 
                                                        onclick="verDetalleRechazado(<?= $registro['id'] ?>)"
                                                        title="Ver Motivo de Rechazo">
                                                    <i class="ti ti-eye"></i>
                                                </button>
                                            <?php else: ?>
                                                <button type="button" class="btn btn-sm btn-outline-info" 
                                                        onclick="verDetalleHistorial(<?= $registro['id'] ?>)"
                                                        title="Ver Detalles">
                                                    <i class="ti ti-eye"></i>
                                                </button>

                                                <?php 
                                                $horasTotal = $registro['horas_retraso_total'] ?? 0;
                                                $diasRetraso = $registro['dias_retraso'] ?? 0;
                                                $tieneRetraso = ($horasTotal > 0 || $diasRetraso > 0);
                                                ?>
                                                
                                                <?php if ($tieneRetraso): ?>
                                                <button type="button" class="btn btn-sm btn-outline-warning" 
                                                        onclick="generarSancion(<?= $registro['id'] ?>, '<?= esc($registro['usuario']) ?>', <?= $horasTotal ?>)"
                                                        title="Generar Sanción por Retraso">
                                                    <i class="ti ti-alert-triangle"></i>
                                                </button>
                                                <?php endif; ?>
                                            <?php endif; ?>

                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    onclick="confirmarEliminacion(<?= $registro['id'] ?>, '<?= $registro['estado_final'] ?>')"
                                                    title="Eliminar">
                                                <i class="ti ti-x"></i>
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
    // Función para aplicar filtros rápidos
    function aplicarFiltros() {
        const periodo = document.getElementById('periodoFiltro').value;
        const estado = document.getElementById('estadoFiltro').value;
        const busqueda = document.getElementById('busquedaRapida').value;
        
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

    // Función para mostrar observaciones de devolución
    function mostrarObservaciones(observaciones, usuario) {
        const observacionesLimpias = observaciones || 'No hay observaciones disponibles';
        const usuarioLimpio = usuario || 'Usuario desconocido';
        
        const observacionesHTML = observacionesLimpias.toString().replace(/</g, '&lt;').replace(/>/g, '&gt;');
        const usuarioHTML = usuarioLimpio.toString().replace(/</g, '&lt;').replace(/>/g, '&gt;');
        
        Swal.fire({
            title: 'Observaciones de Devolución',
            html: `
                <div class="text-start">
                    <div class="mb-3">
                        <h6 class="text-primary mb-2">
                            <i class="ti ti-user me-2"></i>Usuario: ${usuarioHTML}
                        </h6>
                    </div>
                    <div class="alert alert-light border">
                        <div class="d-flex align-items-start">
                            <i class="ti ti-quote text-muted me-2 mt-1"></i>
                            <div class="flex-grow-1">
                                <p class="mb-0 fst-italic">${observacionesHTML}</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="ti ti-info-circle me-1"></i>
                            Observaciones registradas al momento de la devolución
                        </small>
                    </div>
                </div>
            `,
            icon: 'info',
            width: '500px',
            confirmButtonText: 'Cerrar',
            confirmButtonColor: '#6c757d'
        });
    }

    // Función para mostrar detalles de incidencia
    function mostrarDetalleIncidencia(incidencia) {
        if (!incidencia || typeof incidencia !== 'object') {
            Swal.fire({
                title: 'Error',
                text: 'No se pudieron cargar los detalles de la incidencia',
                icon: 'error'
            });
            return;
        }
        
        const tipoHTML = String(incidencia.tipo || 'Incidencia').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        const detalleHTML = String(incidencia.detalle || 'Sin detalles específicos').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        const observacionesHTML = String(incidencia.observaciones || 'Sin observaciones adicionales').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        const usuarioHTML = String(incidencia.usuario || 'Usuario desconocido').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        
        let fechaHTML = 'Fecha no disponible';
        if (incidencia.fecha) {
            try {
                const fecha = new Date(incidencia.fecha);
                if (!isNaN(fecha.getTime())) {
                    fechaHTML = fecha.toLocaleDateString('es-ES', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                }
            } catch (e) {
                
            }
        }
        
        Swal.fire({
            title: '⚠️ Detalles de Incidencia',
            html: `
                <div class="text-start">
                    <div class="mb-3">
                        <h6 class="text-danger mb-2">
                            <i class="ti ti-user me-2"></i>Usuario: ${usuarioHTML}
                        </h6>
                    </div>
                    
                    <div class="alert alert-danger">
                        <div class="mb-3">
                            <strong><i class="ti ti-alert-triangle me-2"></i>Tipo de Incidencia:</strong>
                            <p class="mb-0 mt-1">${tipoHTML}</p>
                        </div>
                        
                        <div class="mb-3">
                            <strong><i class="ti ti-file-text me-2"></i>Detalle:</strong>
                            <p class="mb-0 mt-1">${detalleHTML}</p>
                        </div>
                        
                        <div class="mb-3">
                            <strong><i class="ti ti-message-circle me-2"></i>Observaciones:</strong>
                            <p class="mb-0 mt-1 fst-italic">${observacionesHTML}</p>
                        </div>
                        
                        <div>
                            <strong><i class="ti ti-calendar me-2"></i>Fecha de Registro:</strong>
                            <p class="mb-0 mt-1">${fechaHTML}</p>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="ti ti-info-circle me-1"></i>
                            Incidencia registrada al momento de la devolución del material
                        </small>
                    </div>
                </div>
            `,
            icon: 'warning',
            width: '600px',
            confirmButtonText: 'Cerrar',
            confirmButtonColor: '#dc3545',
            customClass: {
                popup: 'swal-incidencia-popup'
            }
        });
    }

    // Función para ver detalles completos del historial
    function verDetalleHistorial(registroId) {
        if (!registroId || registroId === undefined || registroId === null) {
            Swal.fire({
                title: 'Error',
                text: 'ID de préstamo no válido',
                icon: 'error'
            });
            return;
        }
        
        // Mostrar loading
        Swal.fire({
            title: 'Cargando...',
            text: 'Obteniendo detalles del préstamo',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Crear la URL de la solicitud
        const url = '<?= base_url('prestamos/obtenerDetalleDevolucion') ?>';
        const formData = new FormData();
        formData.append('idprestamo', registroId);

        // Enviar solicitud AJAX para obtener detalles
        fetch(url, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status} - ${response.statusText}`);
            }
            
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                return response.text().then(text => {
                    throw new Error('La respuesta del servidor no es JSON válido');
                });
            }
            
            return response.json();
        })
        .then(data => {
            if (data && data.success) {
                mostrarModalDetallesHistorial(data.data, registroId);
            } else {
                const errorMsg = data?.message || 'No se pudieron cargar los detalles del préstamo';
                Swal.fire({
                    title: 'Error del Servidor',
                    text: errorMsg,
                    icon: 'error',
                    footer: 'Revise la consola del navegador para más detalles'
                });
            }
        })
        .catch(error => {
            let errorMessage = 'Ha ocurrido un error de conexión';
            
            if (error.message.includes('HTTP error')) {
                errorMessage = `Error del servidor: ${error.message}`;
            } else if (error.message.includes('JSON')) {
                errorMessage = 'Error en el formato de respuesta del servidor';
            } else if (error.name === 'TypeError') {
                errorMessage = 'Error de red o servidor no disponible';
            }
            
            Swal.fire({
                title: 'Error de Conexión',
                html: `
                    <p>${errorMessage}</p>
                    <hr>
                    <small class="text-muted">
                        <strong>Detalles técnicos:</strong><br>
                        ${error.message}<br>
                        <strong>ID Préstamo:</strong> ${registroId}<br>
                        <strong>URL:</strong> ${url}
                    </small>
                `,
                icon: 'error',
                confirmButtonText: 'Entendido',
                footer: 'Consulte con el administrador si el problema persiste'
            });
        });
    }

    // Función para mostrar el modal con los detalles del historial
    function mostrarModalDetallesHistorial(detalle, registroId) {
        // Validar que tenemos los datos necesarios
        if (!detalle) {
            Swal.fire({
                title: 'Error',
                text: 'No se pudieron obtener los datos del préstamo',
                icon: 'error'
            });
            return;
        }

        // Asegurar que tenemos un ID válido (usar el del detalle si existe, sino el parámetro)
        const idPrestamo = detalle.id || detalle.idprestamo || registroId;

        // Crear o actualizar el modal existente
        let modalExistente = document.getElementById('modalDetalleHistorial');
        if (modalExistente) {
            modalExistente.remove();
        }

        // Formatear fechas con validación
        const fechaPrestamo = detalle.fechaprestamo ? new Date(detalle.fechaprestamo) : new Date();
        const fechaLimite = detalle.fecha_limite ? new Date(detalle.fecha_limite) : new Date();
        const fechaDevolucionReal = detalle.fecha_devolucion_real ? new Date(detalle.fecha_devolucion_real) : new Date();
        
        // Usar datos calculados del servidor para consistencia
        let diasRetraso = parseInt(detalle.dias_retraso) || 0;
        let horasRetrasoTotal = parseInt(detalle.horas_retraso_total) || 0;
        let mostrarHoras = false;
        let horasRetraso = 0;
        
        // Determinar si mostrar horas o días basado en el cálculo del servidor
        if (horasRetrasoTotal > 0 && horasRetrasoTotal < 24) {
            mostrarHoras = true;
            horasRetraso = horasRetrasoTotal;
            diasRetraso = 0; // No mostrar días si son menos de 24 horas
        } else if (horasRetrasoTotal >= 24) {
            mostrarHoras = false;
            diasRetraso = Math.floor(horasRetrasoTotal / 24);
        }
        
        // Determinar el estado del préstamo
        let estadoBadge = '';
        let estadoClass = '';
        let estadoIcon = '';
        
        if (diasRetraso > 0 || horasRetraso > 0) {
            estadoBadge = 'Con Retraso';
            estadoClass = 'bg-danger';
            estadoIcon = 'ti-alert-circle';
        } else if (diasRetraso === 0 && horasRetraso === 0) {
            estadoBadge = 'Devuelto a Tiempo';
            estadoClass = 'bg-success';
            estadoIcon = 'ti-check-circle';
        } else {
            estadoBadge = 'Devuelto Anticipadamente';
            estadoClass = 'bg-info';
            estadoIcon = 'ti-clock';
        }
        
        // Crear el HTML del modal
        const modalHtml = `
            <!-- Modal para detalles del historial -->
            <div class="modal fade" id="modalDetalleHistorial" tabindex="-1" style="z-index: 99999;">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="ti ti-history me-2"></i>Detalles del Préstamo - ${detalle.codigo_prestamo}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div id="contenido-detalle-historial">
                                <!-- Información del Usuario -->
                                <div class="row">
                                    <div class="col-md-8">
                                        <h6 class="text-primary mb-3">
                                            <i class="ti ti-user me-2"></i>Información del Usuario
                                        </h6>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>Nombre Completo:</strong> <span>${detalle.usuario}</span></p>
                                                <p><strong>Documento:</strong> <span>${detalle.documento}</span></p>
                                                ${detalle.telefono ? `<p><strong>Teléfono:</strong> <span>${detalle.telefono}</span></p>` : ''}
                                                ${detalle.email ? `<p><strong>Email:</strong> <span>${detalle.email}</span></p>` : ''}
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Código Préstamo:</strong> <span>${detalle.codigo_prestamo}</span></p>
                                                <p><strong>ID Préstamo:</strong> <span>#${idPrestamo}</span></p>
                                                <p><strong>Fecha de Registro:</strong> <span>${fechaPrestamo.toLocaleDateString('es-ES')}</span></p>
                                                <p><strong>Hora de Inicio:</strong> <span>${fechaPrestamo.toLocaleTimeString('es-ES', {hour: '2-digit', minute: '2-digit'})}</span></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" 
                                             style="width: 100px; height: 100px; font-size: 2rem; font-weight: 600;">
                                            ${(() => {
                                                const partes = (detalle.usuario || '').split(' ');
                                                const primera = partes[0] ? partes[0].charAt(0).toUpperCase() : 'U';
                                                const segunda = partes[1] ? partes[1].charAt(0).toUpperCase() : '';
                                                return primera + segunda;
                                            })()}
                                        </div>
                                        <div class="mb-2">
                                            <span class="badge ${estadoClass} fs-6 px-3 py-2">
                                                <i class="ti ${estadoIcon} me-1"></i>${estadoBadge}
                                            </span>
                                        </div>
                                        ${(diasRetraso > 0 || horasRetraso > 0) ? `
                                        <div>
                                            <span class="badge bg-warning fs-6 px-3 py-2">
                                                <i class="ti ti-clock-exclamation me-1"></i>
                                                ${mostrarHoras ? 
                                                    `${horasRetraso} hora${horasRetraso !== 1 ? 's' : ''} de retraso` : 
                                                    `${diasRetraso} día${diasRetraso !== 1 ? 's' : ''} de retraso`
                                                }
                                            </span>
                                        </div>
                                        ` : ''}
                                    </div>
                                </div>

                                <hr>

                                <!-- Información del Recurso -->
                                <h6 class="text-primary mb-3">
                                    <i class="ti ti-book me-2"></i>Recurso Prestado
                                </h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Título:</strong> <span>${detalle.recurso}</span></p>
                                        ${detalle.autor ? `<p><strong>Autor(es):</strong> <span>${detalle.autor}</span></p>` : ''}
                                        ${detalle.codigo_ejemplar ? `<p><strong>Código:</strong> <span>${detalle.codigo_ejemplar}</span></p>` : ''}
                                        ${detalle.editorial ? `<p><strong>Editorial:</strong> <span>${detalle.editorial}</span></p>` : ''}
                                    </div>
                                    <div class="col-md-6">
                                        ${detalle.anio_publicacion ? `<p><strong>Año Publicación:</strong> <span>${detalle.anio_publicacion}</span></p>` : ''}
                                        ${detalle.categoria ? `<p><strong>Categoría:</strong> <span>${detalle.categoria}</span></p>` : ''}
                                        ${detalle.estado_ejemplar ? `<p><strong>Estado del Ejemplar:</strong> <span>${detalle.estado_ejemplar}</span></p>` : ''}
                                        ${detalle.ubicacion ? `<p><strong>Ubicación:</strong> <span>${detalle.ubicacion}</span></p>` : ''}
                                    </div>
                                </div>
                                
                                ${detalle.observaciones ? `
                                <hr>
                                <h6 class="text-primary mb-3">
                                    <i class="ti ti-message-circle me-2"></i>Observaciones de Devolución
                                </h6>
                                <div class="alert alert-light border">
                                    <div class="d-flex align-items-start">
                                        <i class="ti ti-quote text-muted me-2 mt-1"></i>
                                        <p class="mb-0 fst-italic">${detalle.observaciones}</p>
                                    </div>
                                </div>
                                ` : ''}
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        ${detalle.ubicacion ? `<p><strong>Ubicación:</strong> <span>${detalle.ubicacion}</span></p>` : ''}
                                    </div>
                                </div>

                                <hr>

                                <!-- Timeline del Préstamo -->
                                <h6 class="text-primary mb-3">
                                    <i class="ti ti-clock-hour-3 me-2"></i>Timeline del Préstamo
                                </h6>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="text-center p-3 bg-primary bg-opacity-10 rounded">
                                            <h5 class="mb-1 text-primary">${fechaPrestamo.toLocaleDateString('es-ES')}</h5>
                                            <small class="text-muted">Fecha de Préstamo</small>
                                            <p class="mb-0 mt-1 small">${fechaPrestamo.toLocaleTimeString('es-ES', {hour: '2-digit', minute: '2-digit'})}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-center p-3 bg-warning bg-opacity-10 rounded">
                                            <h5 class="mb-1 text-warning">${fechaLimite.toLocaleDateString('es-ES')}</h5>
                                            <small class="text-muted">Fecha Límite</small>
                                            <p class="mb-0 mt-1 small">${fechaLimite.toLocaleTimeString('es-ES', {hour: '2-digit', minute: '2-digit'})}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-center p-3 ${diasRetraso > 0 ? 'bg-danger' : 'bg-success'} bg-opacity-10 rounded">
                                            <h5 class="mb-1 ${diasRetraso > 0 ? 'text-danger' : 'text-success'}">${fechaDevolucionReal.toLocaleDateString('es-ES')}</h5>
                                            <small class="text-muted">Fecha de Devolución</small>
                                            <p class="mb-0 mt-1 small">${fechaDevolucionReal.toLocaleTimeString('es-ES', {hour: '2-digit', minute: '2-digit'})}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <div class="text-center p-3 bg-info bg-opacity-10 rounded">
                                            <h4 class="mb-1 text-info">${Math.abs(parseInt(detalle.dias_prestamo) || 0)}</h4>
                                            <small class="text-muted">Días de Duración</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="text-center p-3 ${(diasRetraso > 0 || horasRetraso > 0) ? 'bg-danger' : (diasRetraso === 0 && horasRetraso === 0) ? 'bg-success' : 'bg-info'} bg-opacity-10 rounded">
                                            <h4 class="mb-1 ${(diasRetraso > 0 || horasRetraso > 0) ? 'text-danger' : (diasRetraso === 0 && horasRetraso === 0) ? 'text-success' : 'text-info'}">
                                                ${(diasRetraso === 0 && horasRetraso === 0) ? 'A Tiempo' : 
                                                  mostrarHoras ? `+${horasRetraso}h` : 
                                                  diasRetraso > 0 ? `+${diasRetraso}d` : diasRetraso}
                                            </h4>
                                            <small class="text-muted">
                                                ${(diasRetraso === 0 && horasRetraso === 0) ? 'Estado' : 
                                                  mostrarHoras ? 'Horas de Retraso' :
                                                  diasRetraso > 0 ? 'Días de Retraso' : 'Días de Anticipación'}
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Observaciones y Sanciones -->
                                ${(diasRetraso > 0 || horasRetraso > 0) || detalle.sanciones || detalle.observaciones || detalle.observaciones_devolucion ? `
                                <hr>
                                <h6 class="text-primary mb-3">
                                    <i class="ti ti-alert-triangle me-2"></i>Observaciones y Sanciones
                                </h6>
                                ` : ''}
                                
                                ${(diasRetraso > 0 || horasRetraso > 0) ? `
                                <div class="alert alert-warning">
                                    <div class="d-flex align-items-center">
                                        <i class="ti ti-alert-triangle me-3" style="font-size: 1.5rem;"></i>
                                        <div>
                                            <strong>Retraso Detectado</strong><br>
                                            <small>Se registró un retraso de ${mostrarHoras ? 
                                                `${horasRetraso} hora${horasRetraso !== 1 ? 's' : ''}` : 
                                                `${diasRetraso} día${diasRetraso !== 1 ? 's' : ''}`
                                            } en la devolución del recurso.</small>
                                            ${detalle.multa && parseInt(detalle.multa) > 0 ? `<br><small class="text-danger"><strong>Multa aplicada:</strong> $${parseInt(detalle.multa).toLocaleString()}</small>` : ''}
                                        </div>
                                    </div>
                                </div>
                                ` : ''}
                                
                                ${detalle.sanciones && detalle.sanciones.trim() ? `
                                <div class="alert alert-danger">
                                    <div class="d-flex align-items-center">
                                        <i class="ti ti-ban me-3" style="font-size: 1.5rem;"></i>
                                        <div>
                                            <strong>Sanciones del Usuario</strong>
                                            ${detalle.total_sanciones ? ` (${detalle.total_sanciones} registrada(s))` : ''}<br>
                                            <small>${detalle.sanciones}</small>
                                            <br><small class="text-muted"><em>Se muestran las sanciones más recientes del usuario</em></small>
                                        </div>
                                    </div>
                                </div>
                                ` : ''}
                                
                                ${detalle.observaciones && detalle.observaciones.trim() ? `
                                <div class="alert alert-info">
                                    <div class="d-flex align-items-center">
                                        <i class="ti ti-note me-3" style="font-size: 1.5rem;"></i>
                                        <div>
                                            <strong>Observaciones del Préstamo</strong><br>
                                            <small>${detalle.observaciones}</small>
                                        </div>
                                    </div>
                                </div>
                                ` : ''}
                                
                                ${detalle.observaciones_ejemplar && detalle.observaciones_ejemplar.trim() && detalle.observaciones_ejemplar !== detalle.observaciones ? `
                                <div class="alert alert-secondary">
                                    <div class="d-flex align-items-center">
                                        <i class="ti ti-book me-3" style="font-size: 1.5rem;"></i>
                                        <div>
                                            <strong>Observaciones del Ejemplar</strong><br>
                                            <small>${detalle.observaciones_ejemplar}</small>
                                        </div>
                                    </div>
                                </div>
                                ` : ''}
                                
                                ${detalle.observaciones_devolucion && detalle.observaciones_devolucion.trim() ? `
                                <div class="alert alert-info">
                                    <div class="d-flex align-items-center">
                                        <i class="ti ti-clipboard-check me-3" style="font-size: 1.5rem;"></i>
                                        <div>
                                            <strong>Observaciones de Devolución</strong><br>
                                            <small>${detalle.observaciones_devolucion}</small>
                                            ${detalle.fecha_observaciones_devolucion ? `<br><em class="text-muted">Registrado: ${new Date(detalle.fecha_observaciones_devolucion).toLocaleString('es-ES')}</em>` : ''}
                                        </div>
                                    </div>
                                </div>
                                ` : ''}
                            </div>
                        </div>
                        <div class="modal-footer">

                            <button type="button" class="btn btn-outline-info" onclick="generarReporte(${idPrestamo})">
                                <i class="ti ti-file-download me-2"></i>Generar Reporte
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
        const modal = new bootstrap.Modal(document.getElementById('modalDetalleHistorial'));
        modal.show();
        
        // Cerrar SweetAlert2
        Swal.close();
    }

    // Función para cerrar el modal de detalles del historial
    function cerrarModalDetalleHistorial() {
        const modal = document.getElementById('modalDetalleHistorial');
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
    // Función para confirmar eliminación
    function confirmarEliminacion(registroId, estadoFinal) {
        const esRechazado = estadoFinal === 'Rechazado';
        const tipoRegistro = esRechazado ? 'solicitud rechazada' : 'registro de préstamo';
        
        Swal.fire({
            title: '¿Estás seguro?',
            html: `
                <p>Se eliminará esta ${tipoRegistro} del historial.</p>
                <p class="text-danger"><strong>Esta acción no se puede deshacer</strong></p>
                ${!esRechazado ? '<p class="text-warning"><small><i class="ti ti-alert-circle"></i> Nota: Esto NO eliminará el préstamo original, solo lo ocultará del historial.</small></p>' : ''}
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                eliminarRegistroHistorial(registroId, estadoFinal);
            }
        });
    }

    // Función para eliminar registro del historial
    function eliminarRegistroHistorial(registroId, estadoFinal) {
        const esRechazado = estadoFinal === 'Rechazado';
        const tipoRegistro = esRechazado ? 'solicitud' : 'prestamo';
        
        // Mostrar loading
        Swal.fire({
            title: 'Eliminando...',
            text: 'Por favor espere',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Enviar solicitud al servidor
        fetch('<?= base_url('prestamos/eliminarHistorial') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: new URLSearchParams({
                id: registroId,
                tipo: tipoRegistro
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: '¡Eliminado!',
                    text: data.message || 'El registro ha sido eliminado del historial',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    // Recargar el contenido de forma inteligente
                    recargarContenidoHistorial();
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: data.message || 'No se pudo eliminar el registro',
                    icon: 'error'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error de Conexión',
                text: 'No se pudo conectar con el servidor: ' + error.message,
                icon: 'error'
            });
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

    // Función para generar sanción por retraso
    function generarSancion(prestamoId, nombreUsuario, horasRetraso) {
        console.log('Generar sanción para préstamo:', prestamoId, 'Usuario:', nombreUsuario, 'Horas:', horasRetraso);
        
        // Determinar el tipo y monto de sanción basado en las horas de retraso
        let tipoSancion = '';
        let montoSancion = 0;
        let descripcionSancion = '';
        
        if (horasRetraso <= 24) {
            tipoSancion = 'Leve';
            montoSancion = horasRetraso * 2500; // $2,500 por hora
            descripcionSancion = `Retraso de ${horasRetraso} hora${horasRetraso !== 1 ? 's' : ''} en devolución`;
        } else {
            const dias = Math.floor(horasRetraso / 24);
            tipoSancion = dias <= 3 ? 'Moderada' : 'Grave';
            montoSancion = dias * 5000; // $5,000 por día
            descripcionSancion = `Retraso de ${dias} día${dias !== 1 ? 's' : ''} en devolución`;
        }
        
        Swal.fire({
            title: '⚠️ Generar Sanción',
            html: `
                <div class="text-start">
                    <p><strong>Usuario:</strong> ${nombreUsuario}</p>
                    <p><strong>Préstamo ID:</strong> PREST-${String(prestamoId).padStart(6, '0')}</p>
                    <p><strong>Retraso:</strong> ${horasRetraso < 24 ? horasRetraso + ' horas' : Math.floor(horasRetraso/24) + ' días'}</p>
                    <hr>
                    <div class="alert alert-warning">
                        <p class="mb-2"><strong>Tipo de Sanción:</strong> <span class="badge bg-warning">${tipoSancion}</span></p>
                        <p class="mb-2"><strong>Monto:</strong> <span class="text-danger fw-bold">$${montoSancion.toLocaleString()}</span></p>
                        <p class="mb-0"><strong>Descripción:</strong> ${descripcionSancion}</p>
                    </div>
                </div>
                <div class="mt-3">
                    <label for="observacionesSancion" class="form-label"><strong>Observaciones adicionales:</strong></label>
                    <textarea id="observacionesSancion" class="form-control" rows="3" 
                              placeholder="Ingrese observaciones adicionales sobre esta sanción..."></textarea>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Generar Sanción',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            width: '500px',
            preConfirm: () => {
                const observaciones = document.getElementById('observacionesSancion').value;
                return {
                    prestamoId: prestamoId,
                    tipoSancion: tipoSancion,
                    monto: montoSancion,
                    descripcion: descripcionSancion,
                    observaciones: observaciones.trim()
                };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                procesarSancion(result.value);
            }
        });
    }

    // Función para ver detalle de solicitud rechazada
    function verDetalleRechazado(solicitudId) {
        console.log('Ver detalle de solicitud rechazada:', solicitudId);
        
        Swal.fire({
            title: 'Cargando información...',
            text: 'Obteniendo detalles de la solicitud rechazada',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Obtener detalles de la solicitud rechazada
        fetch('<?= base_url('prestamos/detalleSolicitud') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: new URLSearchParams({
                idsolicitud: solicitudId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const detalle = data.data;
                Swal.fire({
                    title: '📋 Solicitud Rechazada',
                    html: `
                        <div class="text-start">
                            <div class="mb-3">
                                <h6 class="fw-bold">Información del Usuario</h6>
                                <p class="mb-1"><strong>Usuario:</strong> ${detalle.usuario_completo || 'N/A'}</p>
                                <p class="mb-1"><strong>Documento:</strong> ${detalle.documento || 'N/A'}</p>
                            </div>
                            <div class="mb-3">
                                <h6 class="fw-bold">Información del Recurso</h6>
                                <p class="mb-1"><strong>Título:</strong> ${detalle.recurso_titulo || 'N/A'}</p>
                                <p class="mb-1"><strong>Código:</strong> ${detalle.codigo_ejemplar || 'N/A'}</p>
                            </div>
                            <div class="mb-3">
                                <h6 class="fw-bold">Fechas Solicitadas</h6>
                                <p class="mb-1"><strong>Fecha inicio:</strong> ${detalle.fecha_solicitud ? new Date(detalle.fecha_solicitud).toLocaleDateString('es-CO') : 'N/A'}</p>
                                <p class="mb-1"><strong>Fecha devolución:</strong> ${detalle.fecha_devolucion ? new Date(detalle.fecha_devolucion).toLocaleDateString('es-CO') : 'N/A'}</p>
                            </div>
                            <div class="alert alert-danger">
                                <h6 class="fw-bold mb-2">Motivo de Rechazo</h6>
                                <p class="mb-0">${detalle.motivo_rechazo || 'No se especificó motivo'}</p>
                            </div>
                            ${detalle.fecha_procesado ? `
                                <p class="text-muted small mb-0">
                                    <i class="ti ti-calendar"></i> 
                                    Rechazado el: ${new Date(detalle.fecha_procesado).toLocaleDateString('es-CO')} a las ${new Date(detalle.fecha_procesado).toLocaleTimeString('es-CO')}
                                </p>
                            ` : ''}
                        </div>
                    `,
                    icon: 'info',
                    confirmButtonText: 'Cerrar',
                    width: '600px'
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: data.message || 'No se pudo obtener la información',
                    icon: 'error'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error de Conexión',
                text: 'No se pudo obtener la información de la solicitud',
                icon: 'error'
            });
        });
    }

    // Función para procesar la sanción
    function procesarSancion(datosancion) {
        // Mostrar loading
        Swal.fire({
            title: 'Procesando Sanción...',
            text: 'Registrando la sanción en el sistema',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Simular procesamiento (aquí iría la llamada AJAX al servidor)
        setTimeout(() => {
            Swal.fire({
                title: '✅ Sanción Registrada',
                html: `
                    <div class="text-start">
                        <p>La sanción ha sido registrada exitosamente:</p>
                        <hr>
                        <p><strong>Tipo:</strong> ${datosancion.tipoSancion}</p>
                        <p><strong>Monto:</strong> $${datosancion.monto.toLocaleString()}</p>
                        <p><strong>Descripción:</strong> ${datosancion.descripcion}</p>
                        ${datosancion.observaciones ? `<p><strong>Observaciones:</strong> ${datosancion.observaciones}</p>` : ''}
                    </div>
                `,
                icon: 'success',
                confirmButtonText: 'Entendido'
            }).then(() => {
                // Opcional: Recargar la página para mostrar cambios
                // location.reload();
            });
        }, 2000);

        // TODO: Implementar llamada AJAX real al controlador
        /*
        fetch('<?= base_url('sanciones/crear') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(datosancion)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: '✅ Sanción Registrada',
                    text: data.message,
                    icon: 'success'
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: data.message || 'No se pudo registrar la sanción',
                    icon: 'error'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error de Conexión',
                text: 'No se pudo conectar con el servidor',
                icon: 'error'
            });
        });
        */
    }

    // Función de diagnóstico para verificar conectividad
    function diagnosticarConexion() {
        console.log('Iniciando diagnóstico de conexión...');
        
        // Verificar conectividad básica
        fetch('<?= base_url() ?>', {
            method: 'HEAD',
            cache: 'no-cache'
        })
        .then(response => {
            console.log('✅ Conectividad básica OK:', response.status);
            
            // Verificar endpoint específico
            return fetch('<?= base_url('prestamos') ?>', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
        })
        .then(response => {
            console.log('✅ Endpoint de préstamos OK:', response.status);
            
            Swal.fire({
                title: 'Diagnóstico de Conexión',
                text: 'La conexión al servidor está funcionando correctamente',
                icon: 'success'
            });
        })
        .catch(error => {
            console.error('❌ Error en diagnóstico:', error);
            
            Swal.fire({
                title: 'Problema de Conectividad',
                html: `
                    <p>Se detectaron problemas de conexión:</p>
                    <ul class="text-start">
                        <li>Verifique su conexión a internet</li>
                        <li>Asegúrese de que el servidor esté funcionando</li>
                        <li>Revise la configuración de red</li>
                    </ul>
                    <hr>
                    <small class="text-muted">Error técnico: ${error.message}</small>
                `,
                icon: 'error'
            });
        });
    }

    // Función para recargar la página si hay problemas
    function recargarPagina() {
        Swal.fire({
            title: '¿Recargar página?',
            text: 'Esto puede solucionar problemas temporales de conexión',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, recargar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                location.reload();
            }
        });
    }

    /**
     * Confirmar y eliminar todo el historial
     */
    function confirmarEliminarTodoHistorial() {
        Swal.fire({
            title: '⚠️ ¿Eliminar TODO el Historial?',
            html: `
                <div class="text-start">
                    <p class="text-danger fw-bold mb-3">
                        <i class="ti ti-alert-triangle me-2"></i>
                        Esta es una acción EXTREMADAMENTE PELIGROSA
                    </p>
                    <div class="alert alert-danger">
                        <h6 class="fw-bold mb-2">Se eliminarán:</h6>
                        <ul class="mb-0">
                            <li>Todos los préstamos devueltos del historial</li>
                            <li>Todas las solicitudes rechazadas</li>
                            <li>Todos los registros de renovaciones</li>
                        </ul>
                    </div>
                    <div class="alert alert-warning">
                        <h6 class="fw-bold mb-2">Se CONSERVARÁN:</h6>
                        <ul class="mb-0">
                            <li>Todas las sanciones de los usuarios</li>
                            <li>Los préstamos activos actuales</li>
                            <li>Las solicitudes pendientes</li>
                        </ul>
                    </div>
                    <p class="text-danger fw-bold mb-2">
                        <i class="ti ti-lock me-2"></i>
                        Esta acción NO se puede deshacer
                    </p>
                    <div class="form-group mt-3">
                        <label class="form-label fw-bold">
                            Para confirmar, escriba: <span class="text-danger">ELIMINAR HISTORIAL</span>
                        </label>
                        <input type="text" id="confirmacionTexto" class="form-control" 
                               placeholder="Escriba aquí para confirmar">
                    </div>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="ti ti-trash me-2"></i>Sí, eliminar TODO',
            cancelButtonText: 'Cancelar',
            width: '600px',
            preConfirm: () => {
                const confirmacion = document.getElementById('confirmacionTexto').value;
                if (confirmacion !== 'ELIMINAR HISTORIAL') {
                    Swal.showValidationMessage('Debe escribir exactamente: ELIMINAR HISTORIAL');
                    return false;
                }
                return true;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                eliminarTodoHistorial();
            }
        });
    }

    /**
     * Ejecutar la eliminación completa del historial
     */
    function eliminarTodoHistorial() {
        // Mostrar loading
        Swal.fire({
            title: 'Eliminando Historial Completo...',
            html: `
                <div class="text-center">
                    <div class="spinner-border text-danger mb-3" role="status">
                        <span class="visually-hidden">Eliminando...</span>
                    </div>
                    <p class="text-muted">Por favor espere, esto puede tardar unos momentos...</p>
                </div>
            `,
            allowOutsideClick: false,
            showConfirmButton: false
        });

        // Enviar solicitud al servidor
        fetch('<?= base_url('prestamos/eliminarTodoHistorial') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: '✅ Historial Eliminado',
                    html: `
                        <div class="text-start">
                            <p class="mb-2">${data.message}</p>
                            ${data.detalles ? `
                                <div class="alert alert-info mt-3">
                                    <h6 class="fw-bold mb-2">Detalles:</h6>
                                    <ul class="mb-0">
                                        <li>Préstamos eliminados: ${data.detalles.prestamos || 0}</li>
                                        <li>Solicitudes eliminadas: ${data.detalles.solicitudes || 0}</li>
                                        <li>Renovaciones eliminadas: ${data.detalles.renovaciones || 0}</li>
                                    </ul>
                                </div>
                            ` : ''}
                        </div>
                    `,
                    icon: 'success',
                    confirmButtonText: 'Entendido'
                }).then(() => {
                    // Recargar el contenido
                    recargarContenidoHistorial();
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: data.message || 'No se pudo eliminar el historial',
                    icon: 'error'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error de Conexión',
                text: 'No se pudo conectar con el servidor: ' + error.message,
                icon: 'error'
            });
        });
    }

    /**
     * Función inteligente para recargar el contenido del historial
     * Detecta si está en el panel de administración y recarga solo el contenido
     * o hace una recarga completa si está en página independiente
     */
    function recargarContenidoHistorial() {
        // Detectar si estamos dentro del panel de administración
        const contenedorPrincipal = document.getElementById('contenedor-principal');
        
        if (contenedorPrincipal) {
            // Estamos en el panel de administración - Recargar solo el contenido via AJAX
            console.log('🔄 Recargando contenido del historial via AJAX...');
            
            // Mostrar indicador de carga
            Swal.fire({
                title: 'Actualizando...',
                text: 'Recargando el historial',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Hacer petición AJAX para recargar el contenido
            fetch('<?= base_url('historial-prestamos') ?>', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                // Reemplazar solo el contenido del contenedor principal
                contenedorPrincipal.innerHTML = html;
                
                // Cerrar el indicador de carga
                Swal.close();
                
                console.log('✅ Contenido del historial actualizado correctamente');
                
                // Reinicializar eventos si es necesario
                // (Los scripts inline de la vista se ejecutarán automáticamente)
            })
            .catch(error => {
                console.error('❌ Error al recargar el contenido:', error);
                
                // Si falla AJAX, hacer recarga completa
                Swal.fire({
                    title: 'Error al actualizar',
                    text: 'Se recargará la página completa',
                    icon: 'warning',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            });
        } else {
            // No estamos en el panel - Hacer recarga normal de página
            console.log('🔄 Recargando página completa...');
            location.reload();
        }
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

        // Añadir botones de diagnóstico si estamos en modo de desarrollo
        if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
            console.log('🔧 Modo de desarrollo detectado - funciones de diagnóstico disponibles');
            console.log('💡 Use diagnosticarConexion() para verificar conectividad');
            console.log('💡 Use recargarPagina() para recargar si hay problemas');
        }
    });
</script>