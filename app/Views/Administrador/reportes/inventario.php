<?php
/**
 * Vista: Reportes - Inventario
 * Descripción: Estado actual del inventario con estadísticas de disponibilidad
 * Ubicación: app/Views/Administrador/reportes/inventario.php
 */
?>

<div class="container-fluid py-4">
    <!-- Header de la sección -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="ti ti-clipboard-list me-2 text-warning"></i>
                Estado del Inventario
            </h1>
            <p class="text-muted small mb-0">Control y monitoreo del inventario bibliográfico</p>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-warning" onclick="sincronizarInventario()">
                <i class="ti ti-refresh me-1"></i>
                Sincronizar
            </button>
            <button type="button" class="btn btn-warning" onclick="generarReporteInventario()">
                <i class="ti ti-report me-1"></i>
                Generar Reporte
            </button>
        </div>
    </div>

    <!-- Resumen ejecutivo del inventario -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card bg-gradient-primary text-white h-100">
                <div class="card-body text-center">
                    <i class="ti ti-books fs-1 mb-2"></i>
                    <h4 class="mb-1" id="totalRecursos">1,248</h4>
                    <p class="mb-0 small">Total Recursos</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-gradient-success text-white h-100">
                <div class="card-body text-center">
                    <i class="ti ti-check fs-1 mb-2"></i>
                    <h4 class="mb-1" id="recursosDisponibles">1,089</h4>
                    <p class="mb-0 small">Disponibles</p>
                    <small class="opacity-75">87.3%</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-gradient-info text-white h-100">
                <div class="card-body text-center">
                    <i class="ti ti-bookmark fs-1 mb-2"></i>
                    <h4 class="mb-1" id="recursosPrestados">142</h4>
                    <p class="mb-0 small">Prestados</p>
                    <small class="opacity-75">11.4%</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-gradient-danger text-white h-100">
                <div class="card-body text-center">
                    <i class="ti ti-alert-triangle fs-1 mb-2"></i>
                    <h4 class="mb-1" id="recursosPerdidos">17</h4>
                    <p class="mb-0 small">Perdidos/Dañados</p>
                    <small class="opacity-75">1.3%</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-gradient-secondary text-white h-100">
                <div class="card-body text-center">
                    <i class="ti ti-building-store fs-1 mb-2"></i>
                    <h4 class="mb-1" id="valorInventario">S/ 89,420</h4>
                    <p class="mb-0 small">Valor Total</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-gradient-dark text-white h-100">
                <div class="card-body text-center">
                    <i class="ti ti-calendar-event fs-1 mb-2"></i>
                    <h4 class="mb-1" id="ultimaActualizacion">Hoy</h4>
                    <p class="mb-0 small">Última Actualización</p>
                    <small class="opacity-75">10:30 AM</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros de inventario -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h6 class="mb-0">
                <i class="ti ti-filter me-1"></i>
                Filtros de Inventario
            </h6>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-2">
                    <label for="filtroEstadoInventario" class="form-label">Estado</label>
                    <select id="filtroEstadoInventario" class="form-select">
                        <option value="">Todos los estados</option>
                        <option value="disponible">Disponible</option>
                        <option value="prestado">Prestado</option>
                        <option value="perdido">Perdido</option>
                        <option value="danado">Dañado</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="filtroTipoInventario" class="form-label">Tipo</label>
                    <select id="filtroTipoInventario" class="form-select">
                        <option value="">Todos los tipos</option>
                        <option value="fisico">Físico</option>
                        <option value="digital">Digital</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="filtroCategoriaInventario" class="form-label">Categoría</label>
                    <select id="filtroCategoriaInventario" class="form-select">
                        <option value="">Todas</option>
                        <option value="literatura">Literatura</option>
                        <option value="ciencias">Ciencias</option>
                        <option value="matematicas">Matemáticas</option>
                        <option value="historia">Historia</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="filtroUbicacion" class="form-label">Ubicación</label>
                    <select id="filtroUbicacion" class="form-select">
                        <option value="">Todas</option>
                        <option value="estante-a">Estante A</option>
                        <option value="estante-b">Estante B</option>
                        <option value="estante-c">Estante C</option>
                        <option value="deposito">Depósito</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="filtroAnio" class="form-label">Año</label>
                    <select id="filtroAnio" class="form-select">
                        <option value="">Todos</option>
                        <option value="2024">2024</option>
                        <option value="2023">2023</option>
                        <option value="2022">2022</option>
                        <option value="anterior">Anteriores</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-primary w-100" onclick="aplicarFiltrosInventario()">
                        <i class="ti ti-search me-1"></i>
                        Filtrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Análisis por categorías -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-warning text-white">
                    <h6 class="mb-0 text-white">
                        <i class="ti ti-chart-donut me-1"></i>
                        Distribución por Estado
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <!-- Simulación de gráfico de donut -->
                            <div class="position-relative" style="height: 200px;">
                                <div class="position-absolute top-50 start-50 translate-middle text-center">
                                    <h4 class="text-primary">87.3%</h4>
                                    <small class="text-muted">Disponibles</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex flex-column justify-content-center h-100">
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="d-flex align-items-center">
                                            <div class="badge bg-success me-2" style="width: 12px; height: 12px;"></div>
                                            <span class="small">Disponibles</span>
                                        </div>
                                        <span class="fw-bold">1,089</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="d-flex align-items-center">
                                            <div class="badge bg-info me-2" style="width: 12px; height: 12px;"></div>
                                            <span class="small">Prestados</span>
                                        </div>
                                        <span class="fw-bold">142</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="d-flex align-items-center">
                                            <div class="badge bg-danger me-2" style="width: 12px; height: 12px;"></div>
                                            <span class="small">Perdidos</span>
                                        </div>
                                        <span class="fw-bold">17</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="ti ti-alert-octagon me-1"></i>
                        Alertas y Notificaciones
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning d-flex align-items-center mb-3" role="alert">
                        <i class="ti ti-alert-triangle me-2"></i>
                        <div>
                            <strong>17 recursos</strong> marcados como perdidos o dañados requieren revisión.
                        </div>
                    </div>
                    <div class="alert alert-info d-flex align-items-center mb-3" role="alert">
                        <i class="ti ti-info-circle me-2"></i>
                        <div>
                            <strong>8 recursos</strong> con préstamos vencidos pendientes de devolución.
                        </div>
                    </div>
                    <div class="alert alert-success d-flex align-items-center mb-0" role="alert">
                        <i class="ti ti-check-circle me-2"></i>
                        <div>
                            Inventario actualizado correctamente. <strong>No hay inconsistencias</strong> detectadas.
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-between">
                        <button class="btn btn-sm btn-outline-warning" onclick="revisarAlertas()">
                            <i class="ti ti-eye me-1"></i>
                            Revisar Alertas
                        </button>
                        <button class="btn btn-sm btn-warning" onclick="generarAcciones()">
                            <i class="ti ti-settings me-1"></i>
                            Generar Acciones
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Movimientos recientes -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                            <i class="ti ti-history me-1"></i>
                            Movimientos Recientes del Inventario
                        </h6>
                        <button class="btn btn-sm btn-outline-primary" onclick="verHistorialCompleto()">
                            Ver Historial Completo
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="timeline-inventory">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <h6 class="timeline-title mb-1">Recurso devuelto</h6>
                                    <small class="text-muted">Hace 2 horas</small>
                                </div>
                                <p class="timeline-text mb-1">"El Principito" - Devuelto por María González</p>
                                <span class="badge bg-success">Disponible</span>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <h6 class="timeline-title mb-1">Préstamo registrado</h6>
                                    <small class="text-muted">Hace 4 horas</small>
                                </div>
                                <p class="timeline-text mb-1">"Álgebra de Baldor" - Prestado a Carlos Mendoza</p>
                                <span class="badge bg-info">Prestado</span>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <h6 class="timeline-title mb-1">Nuevo recurso agregado</h6>
                                    <small class="text-muted">Ayer</small>
                                </div>
                                <p class="timeline-text mb-1">"Historia del Arte Peruano" - 3 ejemplares</p>
                                <span class="badge bg-primary">Catalogado</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla detallada del inventario -->
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Inventario Completo</h5>
                <div class="d-flex gap-2">
                    <input type="text" class="form-control form-control-sm" placeholder="Buscar por título, código..." 
                           style="width: 250px;" onkeyup="buscarInventario(this.value)">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-sm btn-outline-success" onclick="exportarInventarioExcel()">
                            <i class="ti ti-file-export me-1"></i>
                            Excel
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="exportarInventarioPDF()">
                            <i class="ti ti-file-type-pdf me-1"></i>
                            PDF
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="tablaInventario">
                    <thead class="table-dark">
                        <tr>
                            <th>Código</th>
                            <th>Recurso</th>
                            <th>Categoría</th>
                            <th>Tipo</th>
                            <th class="text-center">Ejemplares</th>
                            <th class="text-center">Disponibles</th>
                            <th class="text-center">Prestados</th>
                            <th>Ubicación</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="cuerpoTablaInventario">
                        <!-- Datos de ejemplo -->
                        <tr>
                            <td>
                                <code>LIT-001</code>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="/img/default-book.jpg" alt="Portada" class="me-2" 
                                         style="width: 32px; height: 32px; object-fit: cover; border-radius: 4px;">
                                    <div>
                                        <div class="fw-medium">El Principito</div>
                                        <small class="text-muted">Antoine de Saint-Exupéry</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-primary">Literatura</span>
                            </td>
                            <td>
                                <span class="badge bg-secondary">Físico</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info">15</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-success">12</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-warning text-dark">3</span>
                            </td>
                            <td>Estante A - Nivel 2</td>
                            <td class="text-center">
                                <span class="badge bg-success">Óptimo</span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-outline-primary" 
                                            onclick="verDetalleInventario(1)" title="Ver detalle">
                                        <i class="ti ti-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-warning" 
                                            onclick="editarInventario(1)" title="Editar">
                                        <i class="ti ti-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-info" 
                                            onclick="verMovimientos(1)" title="Historial">
                                        <i class="ti ti-history"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <!-- Más filas... -->
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted">Mostrando 1-25 de 1,248 recursos</small>
                </div>
                <nav aria-label="Navegación del inventario">
                    <ul class="pagination pagination-sm mb-0">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1">Anterior</a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Siguiente</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detalle de Inventario -->
<div class="modal fade" id="modalDetalleInventario" tabindex="-1" aria-labelledby="modalDetalleInventarioLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="modalDetalleInventarioLabel">
                    <i class="ti ti-clipboard-list me-2"></i>
                    Detalle del Inventario
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="contenidoDetalleInventario">
                <!-- Contenido cargado dinámicamente -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-warning" onclick="imprimirDetalle()">
                    <i class="ti ti-printer me-1"></i>
                    Imprimir
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Timeline específico para inventario */
.timeline-inventory {
    position: relative;
    padding-left: 30px;
}

.timeline-inventory::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline-inventory .timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-inventory .timeline-marker {
    position: absolute;
    left: -22px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #e9ecef;
}

.timeline-inventory .timeline-content {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}
</style>

<script>
// Funciones JavaScript para inventario
function aplicarFiltrosInventario() {
    console.log('Aplicando filtros de inventario...');
    // Implementar lógica de filtrado
}

function sincronizarInventario() {
    console.log('Sincronizando inventario...');
    // Implementar sincronización
}

function generarReporteInventario() {
    console.log('Generando reporte de inventario...');
    // Implementar generación de reporte
}

function revisarAlertas() {
    console.log('Revisando alertas del inventario...');
    // Implementar revisión de alertas
}

function generarAcciones() {
    console.log('Generando acciones para el inventario...');
    // Implementar generación de acciones
}

function verHistorialCompleto() {
    console.log('Mostrando historial completo...');
    // Implementar vista de historial
}

function buscarInventario(termino) {
    console.log('Buscando en inventario:', termino);
    // Implementar búsqueda
}

function exportarInventarioExcel() {
    console.log('Exportando inventario a Excel...');
    // Implementar exportación a Excel
}

function exportarInventarioPDF() {
    console.log('Exportando inventario a PDF...');
    // Implementar exportación a PDF
}

function verDetalleInventario(id) {
    $('#modalDetalleInventario').modal('show');
    // Cargar detalles del inventario
}

function editarInventario(id) {
    console.log('Editando inventario:', id);
    // Implementar edición
}

function verMovimientos(id) {
    console.log('Ver movimientos del recurso:', id);
    // Implementar vista de movimientos
}

function imprimirDetalle() {
    window.print();
}
</script>