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
                            <th class="border-0 px-3 py-3">Préstamo</th>
                            <th class="border-0 px-3 py-3">Usuario</th>
                            <th class="border-0 px-3 py-3">Recurso</th>
                            <th class="border-0 px-3 py-3">Fechas</th>
                            <th class="border-0 text-center px-3 py-3">Estado</th>
                            <th class="border-0 px-3 py-3">Observaciones</th>
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
                                        <?php 
                                            $horasTotal = $registro['horas_retraso_total'] ?? 0;
                                            $diasRetraso = $registro['dias_retraso'] ?? 0;
                                            $horasRestantes = $horasTotal % 24;
                                        ?>
                                        
                                        <?php if ($horasTotal <= 0): ?>
                                            <span class="badge bg-success-subtle text-success">
                                                <i class="ti ti-check-circle me-1"></i>A Tiempo
                                            </span>
                                        <?php elseif ($horasTotal > 0): ?>
                                            <span class="badge bg-danger-subtle text-danger">
                                                <i class="ti ti-alert-circle me-1"></i>Con Retraso
                                            </span>
                                            <?php if ($horasTotal < 24): ?>
                                                <small class="d-block text-danger fw-semibold mt-1"><?= $horasTotal ?> hora<?= $horasTotal != 1 ? 's' : '' ?></small>
                                            <?php else: ?>
                                                <small class="d-block text-danger fw-semibold mt-1"><?= $diasRetraso ?> día<?= $diasRetraso != 1 ? 's' : '' ?></small>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="badge bg-info-subtle text-info">
                                                <i class="ti ti-clock me-1"></i>Temprana
                                            </span>
                                            <small class="d-block text-muted mt-1"><?= abs($diasRetraso) ?> día<?= abs($diasRetraso) != 1 ? 's' : '' ?></small>
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
                                        <div class="d-flex gap-2 justify-content-center">
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
        console.log('Ver detalles de préstamo:', registroId);
        
        // Validar el ID del préstamo
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
        
        console.log('Enviando solicitud a:', url);
        console.log('Con ID de préstamo:', registroId);

        // Enviar solicitud AJAX para obtener detalles
        fetch(url, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => {
            console.log('Respuesta recibida:', response);
            console.log('Status:', response.status);
            console.log('Status Text:', response.statusText);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status} - ${response.statusText}`);
            }
            
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                console.warn('Respuesta no es JSON:', contentType);
                return response.text().then(text => {
                    console.log('Contenido de la respuesta:', text);
                    throw new Error('La respuesta del servidor no es JSON válido');
                });
            }
            
            return response.json();
        })
        .then(data => {
            console.log('Datos recibidos:', data);
            console.log('Tipo de data:', typeof data);
            console.log('data.success:', data?.success);
            console.log('data.data:', data?.data);
            
            if (data && data.success) {
                mostrarModalDetallesHistorial(data.data, registroId);
            } else {
                const errorMsg = data?.message || 'No se pudieron cargar los detalles del préstamo';
                console.error('Error en respuesta:', errorMsg);
                console.error('Datos completos:', data);
                Swal.fire({
                    title: 'Error del Servidor',
                    text: errorMsg,
                    icon: 'error',
                    footer: 'Revise la consola del navegador para más detalles'
                });
            }
        })
        .catch(error => {
            console.error('Error completo:', error);
            console.error('Stack trace:', error.stack);
            
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
            console.error('No se recibieron datos del préstamo');
            Swal.fire({
                title: 'Error',
                text: 'No se pudieron obtener los datos del préstamo',
                icon: 'error'
            });
            return;
        }

        // Asegurar que tenemos un ID válido (usar el del detalle si existe, sino el parámetro)
        const idPrestamo = detalle.id || detalle.idprestamo || registroId;
        
        console.log('Mostrando modal para préstamo ID:', idPrestamo);
        console.log('Datos del préstamo:', detalle);

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
        
        console.log('Datos de retraso del servidor:', {
            fechaDevolucion: detalle.fecha_devolucion_real,
            fechaLimite: detalle.fecha_limite,
            horasRetrasoTotal: horasRetrasoTotal,
            diasRetrasoOriginal: detalle.dias_retraso,
            mostrarHoras: mostrarHoras,
            horasRetraso: horasRetraso,
            diasRetrasoFinal: diasRetraso
        });
        
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