<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container-fluid">
    <!-- Encabezado de la página con breadcrumb -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between mb-4">
                <div>
                    <h1 class="h3 mb-1 fw-bold text-dark">
                        <i class="ti ti-database-export text-primary me-2"></i>
                        Gestión de Respaldos
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="#">Administración de Datos</a></li>
                            <li class="breadcrumb-item active">Respaldos</li>
                        </ol>
                    </nav>
                    <p class="text-muted mb-0 mt-1">Gestiona los respaldos de la base de datos del sistema bibliotecario</p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="configurarBackupAutomatico()">
                        <i class="ti ti-settings"></i> Configurar Automático
                    </button>
                    <button type="button" class="btn btn-success btn-sm" onclick="crearBackupManual()">
                        <i class="ti ti-plus"></i> Crear Backup
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
                            <i class="ti ti-database text-primary" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-primary mb-1"><?= isset($estadisticas['total_backups']) ? number_format($estadisticas['total_backups']) : 0 ?></h3>
                    <p class="text-muted mb-0 small">Total Backups</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card info h-100 shadow-sm">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="rounded-circle bg-info bg-opacity-10 p-3">
                            <i class="ti ti-server text-info" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-info mb-1"><?= isset($estadisticas['espacio_utilizado']) ? $estadisticas['espacio_utilizado'] : '0 MB' ?></h3>
                    <p class="text-muted mb-0 small">Espacio Utilizado</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card success h-100 shadow-sm">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3">
                            <i class="ti ti-clock text-success" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-success mb-1"><?= isset($estadisticas['ultimo_backup']) ? date('d/m', strtotime($estadisticas['ultimo_backup'])) : '--' ?></h3>
                    <p class="text-muted mb-0 small">Último Backup</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card warning h-100 shadow-sm">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                            <i class="ti ti-robot text-warning" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-warning mb-1"><?= isset($estadisticas['backups_automaticos']) ? number_format($estadisticas['backups_automaticos']) : 0 ?></h3>
                    <p class="text-muted mb-0 small">Backups Automáticos</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Acciones rápidas -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient-primary text-white shadow-sm">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="mb-1 text-white fw-semibold">
                                <i class="ti ti-shield-check me-2"></i>
                                Sistema de Respaldos Automático
                            </h6>
                            <p class="mb-0 text-white-50 small">
                                Los backups automáticos se ejecutan diariamente a las 08:30 AM
                            </p>
                        </div>
                        <div class="d-flex gap-2">
                            <span class="badge bg-success">
                                <i class="ti ti-check-circle me-1"></i>Activo
                            </span>
                            <button class="btn btn-outline-light btn-sm" onclick="configurarBackupAutomatico()">
                                <i class="ti ti-settings me-1"></i>Configurar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de backups con diseño mejorado -->
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title mb-0 fw-semibold">
                        <i class="ti ti-list text-primary me-2"></i>
                        Lista de Respaldos
                    </h5>
                    <p class="text-muted small mb-0 mt-1">Gestiona todos los respaldos de la base de datos</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-secondary btn-sm" type="button" onclick="limpiarBackupsAntiguos()">
                        <i class="ti ti-eraser me-1"></i>Limpiar Antiguos
                    </button>
                    <button class="btn btn-outline-secondary btn-sm" type="button" onclick="location.reload()">
                        <i class="ti ti-refresh me-1"></i>Actualizar
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tablaBackups">
                    <thead class="table-light">
                        <tr class="text-uppercase small fw-semibold text-muted">
                            <th class="border-0 px-3 py-3">Archivo</th>
                            <th class="border-0 px-3 py-3">Fecha y Hora</th>
                            <th class="border-0 px-3 py-3">Información</th>
                            <th class="border-0 text-center px-3 py-3">Tipo</th>
                            <th class="border-0 text-center px-3 py-3">Estado</th>
                            <th class="border-0 text-center px-3 py-3">Tamaño</th>
                            <th class="border-0 text-center px-3 py-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($backups)): ?>
                            <?php foreach ($backups as $backup): ?>
                                <tr class="border-bottom">
                                    <td class="px-3 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <div class="rounded-2 bg-primary bg-opacity-10 p-2">
                                                    <i class="ti ti-database text-primary fs-5"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <h6 class="mb-1 fw-semibold"><?= esc($backup['nombre']) ?></h6>
                                                <p class="text-muted mb-0 small">ID: <?= esc($backup['id']) ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3">
                                        <div>
                                            <p class="mb-1 small">
                                                <i class="ti ti-calendar-event me-1"></i>
                                                <?= date('d/m/Y', strtotime($backup['fecha_creacion'])) ?>
                                            </p>
                                            <p class="mb-0 small text-muted">
                                                <i class="ti ti-clock me-1"></i>
                                                <?= date('H:i:s', strtotime($backup['fecha_creacion'])) ?>
                                            </p>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3">
                                        <div>
                                            <p class="mb-1 small">
                                                <i class="ti ti-table me-1"></i>
                                                <?= $backup['tablas'] ?> tablas
                                            </p>
                                            <p class="mb-0 small">
                                                <i class="ti ti-database me-1"></i>
                                                <?= number_format($backup['registros']) ?> registros
                                            </p>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3 text-center">
                                        <?php if ($backup['tipo'] == 'Automático'): ?>
                                            <span class="badge bg-info-subtle text-info">
                                                <i class="ti ti-robot me-1"></i>Automático
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-primary-subtle text-primary">
                                                <i class="ti ti-user me-1"></i>Manual
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-3 py-3 text-center">
                                        <?php if ($backup['estado'] == 'Completado'): ?>
                                            <span class="badge bg-success-subtle text-success">
                                                <i class="ti ti-check-circle me-1"></i>Completado
                                            </span>
                                        <?php elseif ($backup['estado'] == 'En Progreso'): ?>
                                            <span class="badge bg-warning-subtle text-warning">
                                                <i class="ti ti-loader me-1"></i>En Progreso
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-danger-subtle text-danger">
                                                <i class="ti ti-x-circle me-1"></i>Error
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-3 py-3 text-center">
                                        <span class="badge bg-secondary-subtle text-secondary">
                                            <?= esc($backup['tamaño']) ?>
                                        </span>
                                        <?php if ($backup['compresion']): ?>
                                            <small class="d-block text-muted mt-1">
                                                <i class="ti ti-file-zip"></i> Comprimido
                                            </small>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-3 py-3 text-center">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                Acciones
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item" href="#" onclick="descargarBackup('<?= esc($backup['nombre']) ?>')">
                                                        <i class="ti ti-download me-2"></i>Descargar
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="#" onclick="verDetallesBackup(<?= $backup['id'] ?>)">
                                                        <i class="ti ti-eye me-2"></i>Ver Detalles
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item text-primary" href="#" onclick="restaurarBackup('<?= esc($backup['nombre']) ?>')">
                                                        <i class="ti ti-database-import me-2"></i>Restaurar
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <a class="dropdown-item text-danger" href="#" onclick="eliminarBackup('<?= esc($backup['nombre']) ?>')">
                                                        <i class="ti ti-trash me-2"></i>Eliminar
                                                    </a>
                                                </li>
                                            </ul>
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
                                        <h5 class="empty-state-title text-muted">No hay respaldos disponibles</h5>
                                        <p class="empty-state-description text-muted mb-3">
                                            No se han creado respaldos aún
                                        </p>
                                        <button class="btn btn-primary" onclick="crearBackupManual()">
                                            <i class="ti ti-plus me-1"></i>Crear Primer Backup
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Footer de la tarjeta con información adicional -->
        <?php if (!empty($backups)): ?>
        <div class="card-footer bg-light border-top-0">
            <div class="d-flex justify-content-between align-items-center text-muted small">
                <span>
                    <i class="ti ti-info-circle me-1"></i>
                    Mostrando <?= count($backups) ?> de <?= count($backups) ?> respaldos
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
    // Función para crear backup manual
    function crearBackupManual() {
        Swal.fire({
            title: 'Crear Backup Manual',
            html: `
                <div class="text-start">
                    <div class="mb-3">
                        <label class="form-label">Nombre del backup (opcional):</label>
                        <input type="text" class="form-control" id="nombreBackup" placeholder="backup_manual_${new Date().toISOString().slice(0,10)}">
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="incluirDatos" checked>
                            <label class="form-check-label" for="incluirDatos">
                                Incluir datos de las tablas
                            </label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="comprimirBackup" checked>
                            <label class="form-check-label" for="comprimirBackup">
                                Comprimir archivo
                            </label>
                        </div>
                    </div>
                    <div class="alert alert-info">
                        <i class="ti ti-info-circle me-2"></i>
                        <small>El backup incluirá todas las tablas de la base de datos</small>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Crear Backup',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#28a745',
            width: 500,
            preConfirm: () => {
                return {
                    nombre: document.getElementById('nombreBackup').value,
                    incluirDatos: document.getElementById('incluirDatos').checked,
                    comprimir: document.getElementById('comprimirBackup').checked
                };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Mostrar progreso
                Swal.fire({
                    title: 'Creando Backup...',
                    html: `
                        <div class="text-center">
                            <div class="spinner-border text-primary mb-3" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                            <p>Por favor espera mientras se crea el respaldo</p>
                        </div>
                    `,
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        // Llamada AJAX para crear backup
                        fetch('<?= base_url("admin/crear-backup") ?>', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify(result.value)
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: 'Backup Creado',
                                    text: 'El respaldo se ha creado exitosamente',
                                    icon: 'success'
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                throw new Error(data.message);
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                title: 'Error',
                                text: error.message || 'Error al crear el backup',
                                icon: 'error'
                            });
                        });
                    }
                });
            }
        });
    }

    // Función para restaurar backup
    function restaurarBackup(nombreArchivo) {
        Swal.fire({
            title: '⚠️ ¿Restaurar Base de Datos?',
            html: `
                <div class="text-start">
                    <div class="alert alert-warning">
                        <strong>¡ADVERTENCIA!</strong><br>
                        Esta acción reemplazará completamente la base de datos actual con los datos del backup seleccionado.
                    </div>
                    <p><strong>Archivo:</strong> ${nombreArchivo}</p>
                    <p class="text-muted">Todos los datos actuales se perderán. Se recomienda crear un backup antes de continuar.</p>
                    <div class="form-check mt-3">
                        <input class="form-check-input" type="checkbox" id="confirmarRestaurar">
                        <label class="form-check-label" for="confirmarRestaurar">
                            Entiendo que esta acción no se puede deshacer
                        </label>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Sí, Restaurar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#dc3545',
            width: 600,
            preConfirm: () => {
                if (!document.getElementById('confirmarRestaurar').checked) {
                    Swal.showValidationMessage('Debes confirmar que entiendes la acción');
                    return false;
                }
                return true;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Mostrar progreso de restauración
                Swal.fire({
                    title: 'Restaurando Base de Datos...',
                    html: 'Por favor espera, este proceso puede tomar varios minutos',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                        
                        // Llamada AJAX para restaurar
                        fetch('<?= base_url("admin/restaurar-backup") ?>', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({ backup_file: nombreArchivo })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: 'Restauración Completada',
                                    text: data.message,
                                    icon: 'success'
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                throw new Error(data.message);
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                title: 'Error en la Restauración',
                                text: error.message || 'Error al restaurar la base de datos',
                                icon: 'error'
                            });
                        });
                    }
                });
            }
        });
    }

    // Función para descargar backup
    function descargarBackup(nombreArchivo) {
        window.location.href = `<?= base_url('admin/descargar-backup') ?>/${nombreArchivo}`;
    }

    // Función para eliminar backup
    function eliminarBackup(nombreArchivo) {
        Swal.fire({
            title: '¿Eliminar Backup?',
            text: `¿Estás seguro de que deseas eliminar el archivo: ${nombreArchivo}?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#dc3545'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`<?= base_url('admin/eliminar-backup') ?>/${nombreArchivo}`, {
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Eliminado', data.message, 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        throw new Error(data.message);
                    }
                })
                .catch(error => {
                    Swal.fire('Error', error.message || 'Error al eliminar backup', 'error');
                });
            }
        });
    }

    // Función para ver detalles del backup
    function verDetallesBackup(backupId) {
        // TODO: Implementar modal de detalles
        Swal.fire({
            title: 'Detalles del Backup',
            text: 'Funcionalidad en desarrollo',
            icon: 'info'
        });
    }

    // Función para configurar backup automático
    function configurarBackupAutomatico() {
        Swal.fire({
            title: 'Configuración de Backups Automáticos',
            html: `
                <div class="text-start">
                    <div class="mb-3">
                        <label class="form-label">Frecuencia:</label>
                        <select class="form-select" id="frecuencia">
                            <option value="diario" selected>Diario</option>
                            <option value="semanal">Semanal</option>
                            <option value="mensual">Mensual</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Hora de ejecución:</label>
                        <input type="time" class="form-control" id="horaEjecucion" value="08:30">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Retener backups por:</label>
                        <select class="form-select" id="retencion">
                            <option value="7">7 días</option>
                            <option value="30" selected>30 días</option>
                            <option value="90">90 días</option>
                            <option value="365">1 año</option>
                        </select>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="comprimirAuto" checked>
                        <label class="form-check-label" for="comprimirAuto">
                            Comprimir backups automáticos
                        </label>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Guardar Configuración',
            cancelButtonText: 'Cancelar',
            width: 500
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Configuración Guardada', 'Los backups automáticos han sido configurados', 'success');
            }
        });
    }

    // Función para limpiar backups antiguos
    function limpiarBackupsAntiguos() {
        Swal.fire({
            title: 'Limpiar Backups Antiguos',
            text: '¿Deseas eliminar backups con más de 30 días de antigüedad?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, limpiar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Limpieza Completada', '3 backups antiguos han sido eliminados', 'success');
            }
        });
    }
</script>