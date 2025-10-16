<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Sanciones Activas' ?> - Biblioteca Virtual HZG</title>
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
                            <i class="ri-shield-cross-line text-danger me-2"></i>
                            Sanciones Activas
                        </h1>
                        <p class="text-muted mb-0">Gestión de sanciones disciplinarias vigentes</p>
                    </div>
                    <button class="btn btn-danger" onclick="mostrarModalNuevaSancion()">
                        <i class="ri-add-line me-1"></i>
                        Nueva Sanción
                    </button>
                </div>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stats-card text-center">
                    <h3 class="text-danger mb-1"><?= $estadisticas['total'] ?? 0 ?></h3>
                    <small class="text-muted">Total Sanciones</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card text-center">
                    <h3 class="text-danger mb-1"><?= $estadisticas['activas'] ?? 0 ?></h3>
                    <small class="text-muted">Suspensiones</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card text-center">
                    <h3 class="text-warning mb-1"><?= $estadisticas['cumplidas'] ?? 0 ?></h3>
                    <small class="text-muted">Amonestaciones</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card text-center">
                    <h3 class="text-info mb-1"><?= count($sanciones) ?></h3>
                    <small class="text-muted">Estudiantes Afectados</small>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="filter-section">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Tipo de Sanción</label>
                    <select name="tipo_sancion" class="form-select">
                        <option value="">Todos los tipos</option>
                        <?php foreach ($tipos_sancion as $tipo): ?>
                            <option value="<?= $tipo['idtiposancion'] ?>" 
                                    <?= (($filtros['tipo_sancion'] ?? '') == $tipo['idtiposancion']) ? 'selected' : '' ?>>
                                <?= $tipo['tiposancion'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Nivel Educativo</label>
                    <select name="nivel" class="form-select">
                        <option value="">Todos los niveles</option>
                        <option value="Inicial" <?= (($filtros['nivel'] ?? '') == 'Inicial') ? 'selected' : '' ?>>Inicial</option>
                        <option value="Primaria" <?= (($filtros['nivel'] ?? '') == 'Primaria') ? 'selected' : '' ?>>Primaria</option>
                        <option value="Secundaria" <?= (($filtros['nivel'] ?? '') == 'Secundaria') ? 'selected' : '' ?>>Secundaria</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Buscar Estudiante</label>
                    <input type="text" name="buscar" class="form-control" 
                           placeholder="Nombre, apellido o DNI..." 
                           value="<?= $filtros['buscar'] ?? '' ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="ri-search-line me-1"></i>
                        Filtrar
                    </button>
                </div>
            </form>
        </div>

        <!-- Lista de Sanciones -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Sanciones Activas</h5>
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
                                <i class="ri-shield-check-line text-muted" style="font-size: 3rem;"></i>
                                <h5 class="text-muted mt-3">No hay sanciones activas</h5>
                                <p class="text-muted">Todas las sanciones han sido cumplidas o canceladas.</p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Estudiante</th>
                                            <th>Documento</th>
                                            <th>Nivel/Grado</th>
                                            <th>Tipo de Sanción</th>
                                            <th>Detalle</th>
                                            <th>Fecha Sanción</th>
                                            <th>Estado</th>
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
                                                    <?php if ($sancion['grado'] && $sancion['seccion']): ?>
                                                        <?= $sancion['grado'] ?>° <?= $sancion['nivel'] ?> <?= $sancion['seccion'] ?>
                                                    <?php else: ?>
                                                        <span class="text-muted">Sin matrícula</span>
                                                    <?php endif; ?>
                                                </td>
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
                                                    <span class="sanction-status status-<?= $sancion['estado_sancion'] ?>">
                                                        <?= ucfirst($sancion['estado_sancion']) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <button class="btn btn-outline-info" 
                                                                onclick="verDetalles(<?= $sancion['idsancion'] ?>)"
                                                                title="Ver detalles">
                                                            <i class="ri-eye-line"></i>
                                                        </button>
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

    <!-- Modal Nueva Sanción -->
    <div class="modal fade" id="modalNuevaSancion" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="ri-shield-cross-line me-2"></i>
                        Nueva Sanción Disciplinaria
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="formNuevaSancion">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Tipo de Sanción <span class="text-danger">*</span></label>
                                <select name="idtiposancion" class="form-select" required>
                                    <option value="">Seleccionar tipo...</option>
                                    <?php foreach ($tipos_sancion as $tipo): ?>
                                        <option value="<?= $tipo['idtiposancion'] ?>"><?= $tipo['tiposancion'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Estudiante <span class="text-danger">*</span></label>
                                <select name="idpersona" class="form-select" required>
                                    <option value="">Buscar estudiante...</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Detalle de la Sanción</label>
                                <textarea name="detallesancion" class="form-control" rows="3" 
                                          placeholder="Describe los motivos y detalles de la sanción..." required></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Fecha de Sanción <span class="text-danger">*</span></label>
                                <input type="date" name="fecha_sancion" class="form-control" 
                                       value="<?= date('Y-m-d') ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Fecha de Vencimiento (Opcional)</label>
                                <input type="date" name="fecha_vencimiento" class="form-control">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Observaciones</label>
                                <textarea name="observaciones" class="form-control" rows="2" 
                                          placeholder="Observaciones adicionales..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="ri-close-line me-1"></i>
                            Cancelar
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <i class="ri-save-line me-1"></i>
                            Registrar Sanción
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Mostrar modal nueva sanción
        function mostrarModalNuevaSancion() {
            const modal = new bootstrap.Modal(document.getElementById('modalNuevaSancion'));
            modal.show();
        }

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

        // Formulario nueva sanción
        document.getElementById('formNuevaSancion').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('<?= base_url('sanciones/crear') ?>', {
                method: 'POST',
                body: formData
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
        });
    </script>
</body>
</html>
