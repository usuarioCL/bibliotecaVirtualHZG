<?php
/**
 * Vista: Historial de Sanciones
 * Descripción: Muestra el historial completo de sanciones (activas y levantadas)
 * Ubicación: app/Views/Administrador/sanciones/historial.php
 */
?>

<div class="container-fluid py-4">
    <!-- Header de la sección -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="ti ti-clock-record me-2 text-info"></i>
                Historial de Sanciones
            </h1>
            <p class="text-muted small mb-0">Registro completo de todas las sanciones disciplinarias</p>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-primary" onclick="generarReporte()">
                <i class="ti ti-report me-1"></i>
                Generar Reporte
            </button>
            <a href="<?= base_url('sanciones') ?>" class="btn btn-danger">
                <i class="ti ti-shield-x me-1"></i>
                Sanciones Activas
            </a>
        </div>
    </div>

    <!-- Filtros avanzados -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h6 class="mb-0">
                <i class="ti ti-filter me-1"></i>
                Filtros de Búsqueda
            </h6>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-2">
                    <label for="filtroEstado" class="form-label">Estado</label>
                    <select id="filtroEstado" class="form-select">
                        <option value="">Todos</option>
                        <option value="activa">Activa</option>
                        <option value="levantada">Levantada</option>
                        <option value="vencida">Vencida</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="filtroTipoHist" class="form-label">Tipo</label>
                    <select id="filtroTipoHist" class="form-select">
                        <option value="">Todos</option>
                        <option value="1">Amonestación Verbal</option>
                        <option value="2">Amonestación Escrita</option>
                        <option value="3">Suspensión de Préstamos</option>
                        <option value="4">Suspensión Temporal</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="filtroNivelHist" class="form-label">Nivel</label>
                    <select id="filtroNivelHist" class="form-select">
                        <option value="">Todos</option>
                        <option value="Inicial">Inicial</option>
                        <option value="Primaria">Primaria</option>
                        <option value="Secundaria">Secundaria</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="fechaDesde" class="form-label">Desde</label>
                    <input type="date" id="fechaDesde" class="form-control">
                </div>
                <div class="col-md-2">
                    <label for="fechaHasta" class="form-label">Hasta</label>
                    <input type="date" id="fechaHasta" class="form-control">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-primary w-100" onclick="buscarHistorial()">
                        <i class="ti ti-search me-1"></i>
                        Buscar
                    </button>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-6">
                    <label for="buscarEstudianteHist" class="form-label">Buscar Estudiante</label>
                    <input type="text" id="buscarEstudianteHist" class="form-control" 
                           placeholder="Nombre, apellido o documento...">
                </div>
                <div class="col-md-4">
                    <label for="buscarPalabras" class="form-label">Palabras en Detalle</label>
                    <input type="text" id="buscarPalabras" class="form-control" 
                           placeholder="Buscar en descripción de sanciones...">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-secondary w-100" onclick="limpiarFiltros()">
                        <i class="ti ti-refresh me-1"></i>
                        Limpiar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Resumen estadístico -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card bg-gradient-primary text-white">
                <div class="card-body text-center">
                    <h4 class="mb-1" id="totalHistorial">48</h4>
                    <p class="mb-0 small">Total Registros</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-gradient-danger text-white">
                <div class="card-body text-center">
                    <h4 class="mb-1" id="sancionesActivasHist">12</h4>
                    <p class="mb-0 small">Activas</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-gradient-success text-white">
                <div class="card-body text-center">
                    <h4 class="mb-1" id="sancionesLevantadas">28</h4>
                    <p class="mb-0 small">Levantadas</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-gradient-warning text-white">
                <div class="card-body text-center">
                    <h4 class="mb-1" id="sancionesVencidas">8</h4>
                    <p class="mb-0 small">Vencidas</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-gradient-info text-white">
                <div class="card-body text-center">
                    <h4 class="mb-1" id="estudiantesHistorial">35</h4>
                    <p class="mb-0 small">Estudiantes</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-gradient-secondary text-white">
                <div class="card-body text-center">
                    <h4 class="mb-1" id="promedioMensual">4.2</h4>
                    <p class="mb-0 small">Promedio/Mes</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Timeline de sanciones recientes -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h6 class="mb-0">
                <i class="ti ti-timeline me-1"></i>
                Actividad Reciente (Últimos 7 días)
            </h6>
        </div>
        <div class="card-body">
            <div class="timeline">
                <div class="timeline-item">
                    <div class="timeline-marker bg-danger"></div>
                    <div class="timeline-content">
                        <h6 class="timeline-title mb-1">Nueva sanción registrada</h6>
                        <p class="timeline-text mb-1">María García - Suspensión de préstamos</p>
                        <small class="text-muted">Hace 2 horas</small>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-marker bg-success"></div>
                    <div class="timeline-content">
                        <h6 class="timeline-title mb-1">Sanción levantada</h6>
                        <p class="timeline-text mb-1">Carlos Rodríguez - Amonestación escrita</p>
                        <small class="text-muted">Ayer a las 14:30</small>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-marker bg-warning"></div>
                    <div class="timeline-content">
                        <h6 class="timeline-title mb-1">Sanción modificada</h6>
                        <p class="timeline-text mb-1">Ana Morales - Actualizado detalle de sanción</p>
                        <small class="text-muted">Hace 2 días</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla del historial -->
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Historial Completo de Sanciones</h5>
                <div class="d-flex gap-2">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" 
                                data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ti ti-download me-1"></i>
                            Exportar
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" onclick="exportarHistorialExcel()">
                                <i class="ti ti-file-export me-2"></i>Excel Completo</a></li>
                            <li><a class="dropdown-item" href="#" onclick="exportarHistorialPDF()">
                                <i class="ti ti-file-type-pdf me-2"></i>PDF Detallado</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#" onclick="exportarResumen()">
                                <i class="ti ti-chart-bar me-2"></i>Resumen Estadístico</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="tablaHistorial">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center">#</th>
                            <th>Estudiante</th>
                            <th>Nivel/Grado</th>
                            <th>Tipo de Sanción</th>
                            <th>Detalle</th>
                            <th class="text-center">Fecha Sanción</th>
                            <th class="text-center">Fecha Levantada</th>
                            <th class="text-center">Duración</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="cuerpoTablaHistorial">
                        <!-- Datos de ejemplo -->
                        <tr>
                            <td class="text-center">1</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                        <span class="small fw-bold">MG</span>
                                    </div>
                                    <div>
                                        <div class="fw-medium">María García López</div>
                                        <small class="text-muted">4° Secundaria B</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-secondary">Secundaria</span>
                            </td>
                            <td>
                                <span class="badge bg-info text-dark">Amonestación Escrita</span>
                            </td>
                            <td>
                                <span class="text-truncate d-inline-block" style="max-width: 250px;" 
                                      title="Entrega tardía reiterada de material bibliográfico">
                                    Entrega tardía reiterada...
                                </span>
                            </td>
                            <td class="text-center">10/03/2024</td>
                            <td class="text-center">20/03/2024</td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark">10 días</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-success">Levantada</span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-outline-info" 
                                            onclick="verHistorialDetalle(1)" title="Ver historial completo">
                                        <i class="ti ti-history"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-primary" 
                                            onclick="verDetalleCompleto(1)" title="Ver detalles">
                                        <i class="ti ti-eye"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center">2</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-danger text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                        <span class="small fw-bold">JP</span>
                                    </div>
                                    <div>
                                        <div class="fw-medium">Juan Pérez Santos</div>
                                        <small class="text-muted">5° Secundaria A</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-secondary">Secundaria</span>
                            </td>
                            <td>
                                <span class="badge bg-warning text-dark">Suspensión de Préstamos</span>
                            </td>
                            <td>
                                <span class="text-truncate d-inline-block" style="max-width: 250px;" 
                                      title="Daño intencional a material bibliográfico">
                                    Daño intencional a material...
                                </span>
                            </td>
                            <td class="text-center">15/03/2024</td>
                            <td class="text-center">-</td>
                            <td class="text-center">
                                <span class="badge bg-warning text-dark">19 días</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-danger">Activa</span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-outline-info" 
                                            onclick="verHistorialDetalle(2)" title="Ver historial completo">
                                        <i class="ti ti-history"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-primary" 
                                            onclick="verDetalleCompleto(2)" title="Ver detalles">
                                        <i class="ti ti-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-success" 
                                            onclick="levantarSancionHistorial(2)" title="Levantar sanción">
                                        <i class="ti ti-check"></i>
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
                    <small class="text-muted">Mostrando 1-20 de 48 registros</small>
                </div>
                <nav aria-label="Navegación del historial">
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

<!-- Modal Historial Detalle -->
<div class="modal fade" id="modalHistorialDetalle" tabindex="-1" aria-labelledby="modalHistorialDetalleLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="modalHistorialDetalleLabel">
                    <i class="ti ti-history me-2"></i>
                    Historial Completo del Estudiante
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="contenidoHistorialDetalle">
                <!-- Contenido cargado dinámicamente -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="imprimirHistorial()">
                    <i class="ti ti-printer me-1"></i>
                    Imprimir
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Estilos para el timeline */
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -22px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #e9ecef;
}

.timeline-content {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}

.timeline-title {
    color: #495057;
    font-size: 14px;
}

.timeline-text {
    color: #6c757d;
    font-size: 13px;
}
</style>

<script>
// Funciones JavaScript para la gestión del historial
function buscarHistorial() {
    console.log('Buscando en historial...');
    // Implementar lógica de búsqueda
}

function limpiarFiltros() {
    document.getElementById('filtroEstado').value = '';
    document.getElementById('filtroTipoHist').value = '';
    document.getElementById('filtroNivelHist').value = '';
    document.getElementById('fechaDesde').value = '';
    document.getElementById('fechaHasta').value = '';
    document.getElementById('buscarEstudianteHist').value = '';
    document.getElementById('buscarPalabras').value = '';
    buscarHistorial();
}

function exportarHistorialExcel() {
    console.log('Exportando historial completo a Excel...');
}

function exportarHistorialPDF() {
    console.log('Exportando historial completo a PDF...');
}

function exportarResumen() {
    console.log('Exportando resumen estadístico...');
}

function verHistorialDetalle(idEstudiante) {
    $('#modalHistorialDetalle').modal('show');
    // Cargar historial completo del estudiante
}

function verDetalleCompleto(id) {
    console.log('Ver detalle completo de sanción:', id);
}

function levantarSancionHistorial(id) {
    if (confirm('¿Está seguro de que desea levantar esta sanción?')) {
        console.log('Levantando sanción desde historial:', id);
    }
}

function generarReporte() {
    console.log('Generando reporte de sanciones...');
}

function imprimirHistorial() {
    window.print();
}

// Inicializar fechas por defecto (último mes)
document.addEventListener('DOMContentLoaded', function() {
    const today = new Date();
    const lastMonth = new Date(today.getFullYear(), today.getMonth() - 1, today.getDate());
    
    document.getElementById('fechaDesde').valueAsDate = lastMonth;
    document.getElementById('fechaHasta').valueAsDate = today;
});
</script>