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
                    <option value="cumplida" <?= (($filtros['estado'] ?? '') == 'cumplida') ? 'selected' : '' ?>>Cumplida</option>
                    <option value="cancelada" <?= (($filtros['estado'] ?? '') == 'cancelada') ? 'selected' : '' ?>>Cancelada</option>
                    <option value="suspendida" <?= (($filtros['estado'] ?? '') == 'suspendida') ? 'selected' : '' ?>>Suspendida</option>
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
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <button type="submit" class="btn btn-primary">
                    <i class="ti ti-search me-1"></i>Filtrar
                </button>
                <a href="<?= base_url('sanciones/historial') ?>" class="btn btn-outline-secondary ms-2">
                    <i class="ti ti-refresh me-1"></i>Limpiar
                </a>
            </div>
        </div>
    </form>
</div>

<!-- Tabla de Historial -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="ti ti-history me-2"></i>Historial de Sanciones
        </h5>
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
                                                onclick="verSancion(<?= $sancion['idsancion'] ?>)"
                                                title="Ver detalles">
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

<script>
function verSancion(id) {
    // Aquí iría la lógica para ver detalles de la sanción
    console.log('Ver sanción:', id);
    // Por ejemplo: window.location.href = '<?= base_url('sanciones/ver/') ?>' + id;
}

function cambiarEstado(id, estado) {
    // Aquí iría la lógica para cambiar el estado
    console.log('Cambiar estado:', id, estado);
}
</script>
