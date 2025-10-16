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
                                            <p class="text-muted mb-0 small">Ejemplar: <?= esc($prestamo['codigo_ejemplar']) ?></p>
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
                                            
                                            <!-- Cancelar -->
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-danger" 
                                                    onclick="cancelarPrestamo(<?= $prestamo['idprestamo'] ?>)" 
                                                    title="Cancelar Préstamo"
                                                    data-bs-toggle="tooltip">
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
        
        // Mostrar modal con loading
        const modal = new bootstrap.Modal(document.getElementById('modalDetallePrestamo'));
        
        // Mostrar loading y ocultar contenido
        document.getElementById('loading-detalle-prestamo').style.display = 'block';
        document.getElementById('contenido-detalle-prestamo').style.display = 'none';
        
        // Mostrar modal
        modal.show();

        // Enviar solicitud AJAX
        fetch('<?= base_url('prestamos/detalle') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: 'idprestamo=' + encodeURIComponent(prestamoId)
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error('Error HTTP: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                const detalle = data.data;
                
                // Verificar que tenemos datos válidos
                if (!detalle) {
                    throw new Error('No se recibieron datos del préstamo');
                }
                
                // Llenar información del estado
                const alertEstado = document.getElementById('alert-estado');
                alertEstado.className = `alert alert-${detalle.color_estado} d-flex align-items-center`;
                document.getElementById('icono-estado').className = `ti ${detalle.icono_estado} me-2`;
                document.getElementById('detalle-estado-prestamo').textContent = detalle.estado_prestamo;
                document.getElementById('detalle-tiempo-restante').textContent = 
                    detalle.dias_restantes >= 0 ? `${detalle.dias_restantes} días restantes` : `${Math.abs(detalle.dias_restantes)} días de retraso`;

                // Llenar información del recurso
                document.getElementById('detalle-titulo').textContent = detalle.recurso_titulo || '-';
                
                let autoresText = '';
                if (detalle.autores && detalle.autores.length > 0) {
                    autoresText = detalle.autores.map(autor => autor.autor_completo || 'Autor desconocido').join(', ');
                } else {
                    autoresText = 'No especificado';
                }
                document.getElementById('detalle-autores').innerHTML = autoresText;
                
                document.getElementById('detalle-editorial').textContent = detalle.editorial || 'No especificada';
                document.getElementById('detalle-isbn').textContent = detalle.isbn || 'No disponible';
                document.getElementById('detalle-anio').textContent = detalle.anio_publicacion || 'No especificado';
                
                const categoriaText = detalle.categoria || 'Sin categoría';
                const subcategoriaText = detalle.subcategoria ? ` / ${detalle.subcategoria}` : '';
                document.getElementById('detalle-categoria').textContent = categoriaText + subcategoriaText;
                
                const tipoRecursoElement = document.getElementById('detalle-tipo-recurso');
                tipoRecursoElement.textContent = detalle.tipo_recurso || '-';
                tipoRecursoElement.className = 'badge bg-secondary';

                // Manejar portada
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

                // Llenar información del usuario
                document.getElementById('detalle-usuario-nombre').textContent = detalle.usuario_completo || '-';
                document.getElementById('detalle-documento').textContent = `${detalle.tipo_documento || ''} ${detalle.documento || ''}`.trim() || '-';
                document.getElementById('detalle-telefono').textContent = detalle.telefono || 'No registrado';
                document.getElementById('detalle-email').textContent = detalle.email || 'No registrado';
                document.getElementById('detalle-nombre-usuario').textContent = detalle.nombre_usuario || 'N/A';
                
                const nivelElement = document.getElementById('detalle-nivel-acceso');
                nivelElement.textContent = detalle.nivel_acceso || 'N/A';
                let nivelClass = 'badge ';
                if (detalle.nivel_acceso === 'admin') {
                    nivelClass += 'bg-danger';
                } else if (detalle.nivel_acceso === 'docente') {
                    nivelClass += 'bg-warning';
                } else {
                    nivelClass += 'bg-success';
                }
                nivelElement.className = nivelClass;

                // Mostrar/ocultar información adicional del usuario
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

                // Llenar información del préstamo
                document.getElementById('detalle-codigo-prestamo').textContent = detalle.idprestamo || '-';
                document.getElementById('detalle-fecha-prestamo').textContent = detalle.fecha_prestamo_formatted || '-';
                document.getElementById('detalle-fecha-vencimiento').textContent = detalle.fecha_vencimiento_formatted || '-';
                
                const fechaAprobacionContainer = document.getElementById('detalle-fecha-aprobacion-container');
                if (detalle.fecha_aprobacion_formatted) {
                    document.getElementById('detalle-fecha-aprobacion').textContent = detalle.fecha_aprobacion_formatted;
                    fechaAprobacionContainer.style.display = 'block';
                } else {
                    fechaAprobacionContainer.style.display = 'none';
                }
                
                document.getElementById('detalle-dias-transcurridos').textContent = detalle.dias_transcurridos || 0;
                
                const diasRestantesElement = document.getElementById('detalle-dias-restantes');
                const diasRestantes = detalle.dias_restantes || 0;
                diasRestantesElement.textContent = `${Math.abs(diasRestantes)} días`;
                diasRestantesElement.className = `badge bg-${diasRestantes >= 0 ? 'success' : 'danger'}`;
                
                const totalRenovacionesElement = document.getElementById('detalle-total-renovaciones');
                totalRenovacionesElement.textContent = detalle.total_renovaciones || 0;

                // Manejar historial de renovaciones
                const renovacionesSection = document.getElementById('detalle-renovaciones-section');
                const cantidadRenovaciones = document.getElementById('detalle-cantidad-renovaciones');
                const renovacionesBody = document.getElementById('detalle-renovaciones-body');
                
                if (detalle.renovaciones && detalle.renovaciones.length > 0) {
                    cantidadRenovaciones.textContent = detalle.renovaciones.length;
                    
                    // Limpiar tabla
                    renovacionesBody.innerHTML = '';
                    
                    // Llenar tabla de renovaciones
                    detalle.renovaciones.forEach(ren => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td><small>${ren.fecha_renovacion_formatted}</small></td>
                            <td><small>${ren.nueva_fecha_devolucion_formatted}</small></td>
                            <td><span class="badge bg-info">${ren.dias_extension} días</span></td>
                            <td><small>${ren.motivo || 'Sin motivo especificado'}</small></td>
                        `;
                        renovacionesBody.appendChild(row);
                    });
                    
                    renovacionesSection.style.display = 'block';
                } else {
                    renovacionesSection.style.display = 'none';
                }

                // Configurar botones del footer según el estado del préstamo
                const btnRenovar = document.getElementById('btn-renovar-prestamo');
                const btnDevolucion = document.getElementById('btn-procesar-devolucion');
                
                if (detalle.estado_prestamo === 'Activo' || detalle.estado_prestamo === 'Vencido') {
                    btnRenovar.style.display = 'inline-block';
                    btnDevolucion.style.display = 'inline-block';
                    
                    // Configurar eventos de los botones
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

                // Ocultar loading y mostrar contenido
                document.getElementById('loading-detalle-prestamo').style.display = 'none';
                document.getElementById('contenido-detalle-prestamo').style.display = 'block';
                
            } else {
                // Ocultar loading
                document.getElementById('loading-detalle-prestamo').style.display = 'none';
                modal.hide();
                
                Swal.fire({
                    title: 'Error',
                    text: data.message || 'No se pudieron obtener los detalles del préstamo',
                    icon: 'error',
                    confirmButtonText: 'Entendido'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            
            // Ocultar loading
            document.getElementById('loading-detalle-prestamo').style.display = 'none';
            modal.hide();
            
            Swal.fire({
                title: 'Error de Conexión',
                text: 'Ha ocurrido un error al obtener los detalles del préstamo',
                icon: 'error',
                confirmButtonText: 'Entendido'
            });
        });
    }

    // Función para renovar préstamo
    function renovarPrestamo(prestamoId) {
        console.log('Renovar préstamo:', prestamoId);
        
        Swal.fire({
            title: '¿Renovar Préstamo?',
            html: `
                <p class="mb-3">Selecciona la nueva fecha de devolución y proporciona un motivo:</p>
                <div class="mb-3">
                    <label for="nueva_fecha_devolucion" class="form-label fw-bold">Nueva fecha de devolución:</label>
                    <input type="date" 
                           id="nueva_fecha_devolucion" 
                           class="form-control" 
                           min="${new Date().toISOString().split('T')[0]}"
                           value="${new Date(Date.now() + 14*24*60*60*1000).toISOString().split('T')[0]}">
                    <small class="text-muted">La fecha debe ser posterior a hoy</small>
                </div>
                <div class="mb-3">
                    <label for="motivo_renovacion" class="form-label fw-bold">Motivo (opcional):</label>
                    <textarea id="motivo_renovacion" class="form-control" placeholder="Escribe el motivo de la renovación..." rows="3"></textarea>
                </div>
                <div class="alert alert-info">
                    <i class="ti ti-info-circle me-2"></i>
                    <small>Los préstamos pueden renovarse las veces que sea necesario.</small>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, renovar préstamo',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#ffc107',
            width: '500px',
            preConfirm: () => {
                const nuevaFechaDevolucion = document.getElementById('nueva_fecha_devolucion').value;
                const motivo = document.getElementById('motivo_renovacion').value;
                
                if (!nuevaFechaDevolucion) {
                    Swal.showValidationMessage('Debes seleccionar una fecha de devolución');
                    return false;
                }
                
                // Validar que la fecha sea posterior a hoy
                const hoy = new Date();
                const fechaSeleccionada = new Date(nuevaFechaDevolucion);
                
                if (fechaSeleccionada <= hoy) {
                    Swal.showValidationMessage('La fecha de devolución debe ser posterior a hoy');
                    return false;
                }
                
                return {
                    nueva_fecha_devolucion: nuevaFechaDevolucion,
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

                // Enviar solicitud AJAX
                fetch('<?= base_url('prestamos/renovar') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: 'idprestamo=' + encodeURIComponent(prestamoId) + 
                          '&nueva_fecha_devolucion=' + encodeURIComponent(result.value.nueva_fecha_devolucion) +
                          '&motivo=' + encodeURIComponent(result.value.motivo)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'Préstamo Renovado',
                            html: `
                                <div class="text-start">
                                    <p class="mb-2"><strong>✅ ${data.message}</strong></p>
                                    <hr>
                                    <p class="mb-1"><i class="ti ti-calendar-event me-2"></i><strong>Nueva fecha de devolución:</strong> ${data.nueva_fecha_devolucion}</p>
                                    <p class="mb-1"><i class="ti ti-refresh me-2"></i><strong>Total de renovaciones:</strong> ${data.renovaciones_totales}</p>
                                    <p class="mb-0"><i class="ti ti-calendar-plus me-2"></i><strong>Extensión:</strong> ${data.dias_extension} días adicionales</p>
                                </div>
                            `,
                            icon: 'success',
                            confirmButtonText: 'Entendido',
                            timer: 5000
                        }).then(() => {
                            location.reload();
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
        
        Swal.fire({
            title: '¿Procesar Devolución?',
            input: 'textarea',
            inputLabel: 'Observaciones (opcional)',
            inputPlaceholder: 'Escribe cualquier observación sobre el estado del recurso devuelto...',
            inputAttributes: {
                'aria-label': 'Observaciones de devolución'
            },
            text: 'Confirma que el recurso ha sido devuelto correctamente.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, procesar devolución',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#28a745'
        }).then((result) => {
            if (result.isConfirmed) {
                // Mostrar loading
                Swal.fire({
                    title: 'Procesando...',
                    text: 'Registrando devolución',
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
                          '&observaciones=' + encodeURIComponent(result.value || '')
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        let icon = 'success';
                        let title = 'Devolución Procesada';
                        
                        // Si hubo retraso, mostrar advertencia
                        if (data.con_retraso) {
                            icon = 'warning';
                            title = 'Devolución con Retraso';
                        }
                        
                        Swal.fire({
                            title: title,
                            text: data.message,
                            icon: icon,
                            timer: 3000,
                            showConfirmButton: true,
                            confirmButtonText: 'Entendido'
                        }).then(() => {
                            location.reload();
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
            input: 'textarea',
            inputLabel: 'Motivo de cancelación',
            inputPlaceholder: 'Escribe el motivo por el cual se cancela el préstamo...',
            inputAttributes: {
                'aria-label': 'Motivo de cancelación'
            },
            text: 'Esta acción no se puede deshacer. El recurso volverá a estar disponible.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, cancelar préstamo',
            cancelButtonText: 'No cancelar',
            confirmButtonColor: '#d33',
            inputValidator: (value) => {
                if (!value) {
                    return 'Debes proporcionar un motivo para la cancelación'
                }
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
                    body: 'idprestamo=' + encodeURIComponent(prestamoId) + '&motivo=' + encodeURIComponent(result.value)
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
                            location.reload();
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

    // Inicializar tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>

<!-- Incluir modal de detalles del préstamo -->
<?= $this->include('Administrador/modals/detalleprestamo') ?>