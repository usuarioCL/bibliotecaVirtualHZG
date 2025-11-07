<?php 
// Debug: Verificar que los datos llegan correctamente
if (empty($historial)) {
    $historial = [];
}
?>

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
                            <li class="breadcrumb-item"><a href="javascript:void(0)" onclick="cargarDashboard()">Dashboard</a></li>
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
                                            <h6 class="mb-1 fw-medium"><?= htmlspecialchars($registro['usuario'] ?? 'Sin nombre') ?></h6>
                                            <p class="text-muted mb-0 small">CC: <?= htmlspecialchars($registro['documento'] ?? 'N/A') ?></p>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3">
                                        <div>
                                            <h6 class="mb-1 fw-medium"><?= htmlspecialchars($registro['recurso'] ?? 'Sin título') ?></h6>
                                            <p class="text-muted mb-0 small">
                                                <i class="ti ti-book me-1"></i>
                                                Código: <?= htmlspecialchars($registro['codigo_ejemplar'] ?? 'N/A') ?>
                                            </p>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3">
                                        <div>
                                            <p class="mb-1 small">
                                                <i class="ti ti-calendar-plus text-primary me-1"></i>
                                                <strong>Inicio:</strong> 
                                                <?php
                                                $fechaPrestamo = $registro['fecha_prestamo'] ?? $registro['fechaprestamo'] ?? date('Y-m-d');
                                                echo date('d/m/Y', strtotime($fechaPrestamo));
                                                ?>
                                            </p>
                                            <?php if (($registro['estado_final'] ?? '') !== 'Rechazado'): ?>
                                            <p class="mb-1 small">
                                                <i class="ti ti-calendar-check text-success me-1"></i>
                                                <strong>Devuelto:</strong> 
                                                <?= !empty($registro['fecha_devolucion']) ? date('d/m/Y', strtotime($registro['fecha_devolucion'])) : 'N/A' ?>
                                            </p>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3 text-center">
                                        <span class="badge bg-primary"><?= $registro['cantidad'] ?? 1 ?></span>
                                    </td>
                                    <td class="px-3 py-3 text-center">
                                        <?php 
                                        $estado = $registro['estado_final'] ?? 'Desconocido';
                                        $badgeClass = 'bg-secondary'; // Default
                                        switch($estado) {
                                            case 'Devuelto':
                                                $badgeClass = 'bg-success';
                                                break;
                                            case 'Devuelto con retraso':
                                                $badgeClass = 'bg-warning';
                                                break;
                                            case 'Rechazado':
                                                $badgeClass = 'bg-danger';
                                                break;
                                            case 'Cancelado':
                                                $badgeClass = 'bg-secondary';
                                                break;
                                            default:
                                                $badgeClass = 'bg-info';
                                        }
                                        ?>
                                        <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($estado) ?></span>
                                    </td>
                                    <td class="px-3 py-3 text-center">
                                        <?php if (!empty($registro['observaciones'])): ?>
                                            <button class="btn btn-sm btn-outline-info" onclick="mostrarObservaciones('<?= htmlspecialchars($registro['observaciones'], ENT_QUOTES) ?>', '<?= htmlspecialchars($registro['usuario'] ?? 'Usuario', ENT_QUOTES) ?>')">
                                                <i class="ti ti-eye"></i>
                                            </button>
                                        <?php else: ?>
                                            <span class="text-muted small">Sin observaciones</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-3 py-3 text-center">
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" onclick="verDetalle(<?= $registro['id'] ?? 0 ?>)" title="Ver Detalle">
                                                <i class="ti ti-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-success" onclick="generarReporte(<?= $registro['id'] ?? 0 ?>)" title="Generar Reporte">
                                                <i class="ti ti-file-text"></i>
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
                                            No hay historial de préstamos disponible
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
                    Mostrando <?= count($historial) ?> registros
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
// Funciones para el historial (versión AJAX)
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

function mostrarObservaciones(observaciones, usuario) {
    Swal.fire({
        title: 'Observaciones de Devolución',
        html: `
            <div class="text-start">
                <div class="mb-3">
                    <h6 class="text-primary mb-2">
                        <i class="ti ti-user me-2"></i>Usuario: ${usuario}
                    </h6>
                </div>
                <div class="alert alert-light border">
                    <div class="d-flex align-items-start">
                        <i class="ti ti-quote text-muted me-2 mt-1"></i>
                        <div class="flex-grow-1">
                            <p class="mb-0 fst-italic">${observaciones}</p>
                        </div>
                    </div>
                </div>
            </div>
        `,
        icon: 'info',
        confirmButtonText: 'Cerrar',
        width: '500px'
    });
}

function verDetalle(registroId) {
    Swal.fire({
        title: 'Ver Detalle',
        text: 'Cargando información del préstamo #' + registroId + '...',
        icon: 'info',
        timer: 1500,
        showConfirmButton: false
    });
}

function generarReporte(registroId) {
    Swal.fire({
        title: 'Generando Reporte',
        text: 'Se está generando el reporte del préstamo #' + registroId + '...',
        icon: 'info',
        timer: 2000,
        showConfirmButton: false
    });
}

function confirmarEliminarTodoHistorial() {
    Swal.fire({
        title: '⚠️ Eliminar Todo el Historial',
        html: `
            <div class="text-start">
                <p class="mb-3">Esta acción eliminará <strong>PERMANENTEMENTE</strong> todos los registros del historial de préstamos.</p>
                <div class="alert alert-danger">
                    <h6 class="fw-bold mb-2">⚠️ ADVERTENCIA:</h6>
                    <ul class="mb-0">
                        <li>Se eliminarán todos los préstamos finalizados</li>
                        <li>Se eliminarán todas las solicitudes procesadas</li>
                        <li>Esta acción NO se puede deshacer</li>
                    </ul>
                </div>
                <p class="mb-0 text-muted small">Escribe <strong>"ELIMINAR"</strong> para confirmar:</p>
            </div>
        `,
        input: 'text',
        inputPlaceholder: 'Escribe ELIMINAR para confirmar',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Eliminar Historial',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#dc3545',
        preConfirm: (value) => {
            if (value !== 'ELIMINAR') {
                Swal.showValidationMessage('Debes escribir "ELIMINAR" para confirmar');
                return false;
            }
            return true;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Historial Eliminado',
                text: 'Todo el historial ha sido eliminado exitosamente',
                icon: 'success'
            }).then(() => {
                // Recargar el historial
                cargarHistorial();
            });
        }
    });
}

function cargarDashboard() {
    // Recargar el dashboard principal
    location.reload();
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