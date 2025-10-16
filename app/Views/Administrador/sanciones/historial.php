<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Historial de Sanciones' ?> - Biblioteca Virtual HZG</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        .sanction-card {
            transition: all 0.3s ease;
            border-left: 4px solid #dc3545;
        }
        .sanction-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .sanction-status {
            font-size: 0.8rem;
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
        }
        .status-activa {
            background-color: #dc3545;
            color: white;
        }
        .status-cumplida {
            background-color: #198754;
            color: white;
        }
        .status-cancelada {
            background-color: #6c757d;
            color: white;
        }
        .filter-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 0.5rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        .stats-card {
            background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
            border-radius: 0.75rem;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-1 text-dark">
                            <i class="ri-history-line text-primary me-2"></i>
                            Historial de Sanciones
                        </h1>
                        <p class="text-muted mb-0">Registro completo de todas las sanciones disciplinarias</p>
                    </div>
                    <a href="<?= base_url('sanciones') ?>" class="btn btn-outline-primary">
                        <i class="ri-arrow-left-line me-1"></i>
                        Volver a Activas
                    </a>
                </div>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stats-card text-center">
                    <h3 class="text-primary mb-1"><?= $estadisticas['total'] ?? 0 ?></h3>
                    <small class="text-muted">Total Sanciones</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card text-center">
                    <h3 class="text-danger mb-1"><?= $estadisticas['activas'] ?? 0 ?></h3>
                    <small class="text-muted">Activas</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card text-center">
                    <h3 class="text-success mb-1"><?= $estadisticas['cumplidas'] ?? 0 ?></h3>
                    <small class="text-muted">Cumplidas</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card text-center">
                    <h3 class="text-secondary mb-1"><?= $estadisticas['canceladas'] ?? 0 ?></h3>
                    <small class="text-muted">Canceladas</small>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="filter-section">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Estado</label>
                    <select name="estado" class="form-select">
                        <option value="">Todos los estados</option>
                        <option value="activa" <?= (($filtros['estado'] ?? '') == 'activa') ? 'selected' : '' ?>>Activa</option>
                        <option value="cumplida" <?= (($filtros['estado'] ?? '') == 'cumplida') ? 'selected' : '' ?>>Cumplida</option>
                        <option value="cancelada" <?= (($filtros['estado'] ?? '') == 'cancelada') ? 'selected' : '' ?>>Cancelada</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Fecha Desde</label>
                    <input type="date" name="fecha_desde" class="form-control" 
                           value="<?= $filtros['fecha_desde'] ?? '' ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Fecha Hasta</label>
                    <input type="date" name="fecha_hasta" class="form-control" 
                           value="<?= $filtros['fecha_hasta'] ?? '' ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Buscar</label>
                    <input type="text" name="buscar" class="form-control" 
                           placeholder="Nombre, apellido o DNI..." 
                           value="<?= $filtros['buscar'] ?? '' ?>">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-search-line me-1"></i>
                        Filtrar
                    </button>
                    <a href="<?= base_url('sanciones/historial') ?>" class="btn btn-outline-secondary ms-2">
                        <i class="ri-refresh-line me-1"></i>
                        Limpiar
                    </a>
                </div>
            </form>
        </div>

        <!-- Lista de Sanciones -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Historial Completo</h5>
                        <div>
                            <button class="btn btn-success btn-sm me-2" onclick="exportarExcel()">
                                <i class="ri-file-excel-line me-1"></i>
                                Excel
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="exportarPDF()">
                                <i class="ri-file-pdf-line me-1"></i>
                                PDF
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <?php if (empty($sanciones)): ?>
                            <div class="text-center py-5">
                                <i class="ri-history-line text-muted" style="font-size: 3rem;"></i>
                                <h5 class="text-muted mt-3">No hay sanciones registradas</h5>
                                <p class="text-muted">No se encontraron sanciones con los filtros aplicados.</p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Estudiante</th>
                                            <th>Documento</th>
                                            <th>Tipo de Sanción</th>
                                            <th>Detalle</th>
                                            <th>Fecha Sanción</th>
                                            <th>Fecha Vencimiento</th>
                                            <th>Estado</th>
                                            <th>Registrado por</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($sanciones as $index => $sancion): ?>
                                            <tr>
                                                <td><?= $index + 1 ?></td>
                                                <td>
                                                    <div class="fw-semibold"><?= $sancion['nombre_completo'] ?></div>
                                                </td>
                                                <td><?= $sancion['numerodoc'] ?></td>
                                                <td>
                                                    <span class="badge bg-secondary"><?= $sancion['tiposancion'] ?></span>
                                                </td>
                                                <td>
                                                    <div class="text-truncate" style="max-width: 200px;" 
                                                         title="<?= $sancion['detallesancion'] ?>">
                                                        <?= $sancion['detallesancion'] ?>
                                                    </div>
                                                </td>
                                                <td><?= date('d/m/Y', strtotime($sancion['fecha_sancion'])) ?></td>
                                                <td>
                                                    <?php if ($sancion['fecha_vencimiento']): ?>
                                                        <?= date('d/m/Y', strtotime($sancion['fecha_vencimiento'])) ?>
                                                    <?php else: ?>
                                                        <span class="text-muted">Sin vencimiento</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <span class="sanction-status status-<?= $sancion['estado_sancion'] ?>">
                                                        <?= ucfirst($sancion['estado_sancion']) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?= $sancion['usuario_registra_nombre'] ?? 'Sistema' ?>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <button class="btn btn-outline-info" 
                                                                onclick="verDetalles(<?= $sancion['idsancion'] ?>)"
                                                                title="Ver detalles">
                                                            <i class="ri-eye-line"></i>
                                                        </button>
                                                        <?php if ($sancion['estado_sancion'] == 'activa'): ?>
                                                            <button class="btn btn-outline-warning" 
                                                                    onclick="editarSancion(<?= $sancion['idsancion'] ?>)"
                                                                    title="Editar">
                                                                <i class="ri-edit-line"></i>
                                                            </button>
                                                            <button class="btn btn-outline-success" 
                                                                    onclick="cambiarEstado(<?= $sancion['idsancion'] ?>, 'cumplida')"
                                                                    title="Marcar como cumplida">
                                                                <i class="ri-check-line"></i>
                                                            </button>
                                                        <?php endif; ?>
                                                        <button class="btn btn-outline-danger" 
                                                                onclick="eliminarSancion(<?= $sancion['idsancion'] ?>)"
                                                                title="Eliminar">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </button>
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
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Ver detalles de sanción
        function verDetalles(id) {
            fetch(`<?= base_url('sanciones/ver') ?>/${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const sancion = data.sancion;
                        Swal.fire({
                            title: 'Detalles de la Sanción',
                            html: `
                                <div class="text-start">
                                    <p><strong>Estudiante:</strong> ${sancion.nombre_completo}</p>
                                    <p><strong>Documento:</strong> ${sancion.numerodoc}</p>
                                    <p><strong>Tipo:</strong> ${sancion.tiposancion}</p>
                                    <p><strong>Detalle:</strong> ${sancion.detallesancion}</p>
                                    <p><strong>Fecha:</strong> ${new Date(sancion.fecha_sancion).toLocaleDateString()}</p>
                                    <p><strong>Estado:</strong> ${sancion.estado_sancion}</p>
                                    ${sancion.observaciones ? `<p><strong>Observaciones:</strong> ${sancion.observaciones}</p>` : ''}
                                </div>
                            `,
                            confirmButtonColor: '#dc3545'
                        });
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                });
        }

        // Editar sanción
        function editarSancion(id) {
            Swal.fire('Info', 'Función de edición en desarrollo', 'info');
        }

        // Cambiar estado de sanción
        function cambiarEstado(id, estado) {
            const estadoTexto = estado === 'cumplida' ? 'cumplida' : 'cancelada';
            
            Swal.fire({
                title: `¿Marcar como ${estadoTexto}?`,
                text: 'Esta acción cambiará el estado de la sanción',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#dc3545',
                confirmButtonText: 'Sí, cambiar estado'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('<?= base_url('sanciones/cambiar-estado') ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `id=${id}&estado=${estado}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Éxito', data.message, 'success').then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error', data.message, 'error');
                        }
                    });
                }
            });
        }

        // Eliminar sanción
        function eliminarSancion(id) {
            Swal.fire({
                title: '¿Eliminar sanción?',
                text: 'Esta acción no se puede deshacer',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`<?= base_url('sanciones/eliminar') ?>/${id}`, {
                        method: 'POST'
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Éxito', data.message, 'success').then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error', data.message, 'error');
                        }
                    });
                }
            });
        }

        // Exportar Excel
        function exportarExcel() {
            Swal.fire('Info', 'Función de exportación en desarrollo', 'info');
        }

        // Exportar PDF
        function exportarPDF() {
            Swal.fire('Info', 'Función de exportación en desarrollo', 'info');
        }
    </script>
</body>
</html>
