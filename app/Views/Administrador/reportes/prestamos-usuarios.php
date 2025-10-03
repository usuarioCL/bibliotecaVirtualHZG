<?php
/**
 * Vista: Reportes - Préstamos por Usuario
 * Descripción: Estadísticas detalladas de préstamos agrupados por usuario
 * Ubicación: app/Views/Administrador/reportes/prestamos-usuarios.php
 */
?>

<div class="container-fluid py-4">
    <!-- Header de la sección -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="ti ti-chart-line me-2 text-primary"></i>
                Préstamos por Usuario
            </h1>
            <p class="text-muted small mb-0">Estadísticas y análisis de préstamos por estudiante</p>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-success" onclick="exportarReportePrestamos()">
                <i class="ti ti-file-export me-1"></i>
                Exportar Datos
            </button>
            <button type="button" class="btn btn-primary" onclick="generarGrafico()">
                <i class="ti ti-chart-bar me-1"></i>
                Ver Gráfico
            </button>
        </div>
    </div>

    <!-- Filtros de reporte -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h6 class="mb-0">
                <i class="ti ti-filter me-1"></i>
                Filtros de Análisis
            </h6>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="filtroFechaDesde" class="form-label">Fecha Desde</label>
                    <input type="date" id="filtroFechaDesde" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="filtroFechaHasta" class="form-label">Fecha Hasta</label>
                    <input type="date" id="filtroFechaHasta" class="form-control">
                </div>
                <div class="col-md-2">
                    <label for="filtroNivelUsuarios" class="form-label">Nivel</label>
                    <select id="filtroNivelUsuarios" class="form-select">
                        <option value="">Todos</option>
                        <option value="Inicial">Inicial</option>
                        <option value="Primaria">Primaria</option>
                        <option value="Secundaria">Secundaria</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="filtroGrado" class="form-label">Grado</label>
                    <select id="filtroGrado" class="form-select">
                        <option value="">Todos</option>
                        <option value="1">1°</option>
                        <option value="2">2°</option>
                        <option value="3">3°</option>
                        <option value="4">4°</option>
                        <option value="5">5°</option>
                        <option value="6">6°</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-primary w-100" onclick="aplicarFiltrosUsuarios()">
                        <i class="ti ti-search me-1"></i>
                        Aplicar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Métricas principales -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card bg-gradient-primary text-white h-100">
                <div class="card-body text-center">
                    <i class="ti ti-users fs-1 mb-2"></i>
                    <h4 class="mb-1" id="totalUsuarios">156</h4>
                    <p class="mb-0 small">Usuarios Activos</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-gradient-success text-white h-100">
                <div class="card-body text-center">
                    <i class="ti ti-book fs-1 mb-2"></i>
                    <h4 class="mb-1" id="totalPrestamos">2,847</h4>
                    <p class="mb-0 small">Total Préstamos</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-gradient-info text-white h-100">
                <div class="card-body text-center">
                    <i class="ti ti-clock fs-1 mb-2"></i>
                    <h4 class="mb-1" id="prestamosPendientes">23</h4>
                    <p class="mb-0 small">Pendientes</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-gradient-warning text-white h-100">
                <div class="card-body text-center">
                    <i class="ti ti-alert-circle fs-1 mb-2"></i>
                    <h4 class="mb-1" id="prestamosVencidos">8</h4>
                    <p class="mb-0 small">Vencidos</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-gradient-secondary text-white h-100">
                <div class="card-body text-center">
                    <i class="ti ti-calendar fs-1 mb-2"></i>
                    <h4 class="mb-1" id="promedioMensual">18.3</h4>
                    <p class="mb-0 small">Promedio/Usuario</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-gradient-dark text-white h-100">
                <div class="card-body text-center">
                    <i class="ti ti-trending-up fs-1 mb-2"></i>
                    <h4 class="mb-1" id="crecimientoMensual">+12%</h4>
                    <p class="mb-0 small">Crecimiento</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráfico de tendencias -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="ti ti-chart-line me-1"></i>
                        Tendencia de Préstamos Mensuales
                    </h6>
                </div>
                <div class="card-body">
                    <div id="chartPrestamos" style="height: 300px;">
                        <!-- Aquí se renderizará el gráfico con Chart.js -->
                        <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                            <div class="text-center">
                                <i class="ti ti-chart-line fs-1 mb-3"></i>
                                <p>Gráfico de tendencias</p>
                                <button class="btn btn-primary btn-sm" onclick="cargarGrafico()">Cargar Gráfico</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="ti ti-medal me-1"></i>
                        Top 5 Usuarios Más Activos
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex align-items-center">
                            <div class="badge bg-warning text-dark me-3">1°</div>
                            <div class="flex-grow-1">
                                <div class="fw-medium">María González</div>
                                <small class="text-muted">5° Secundaria A</small>
                            </div>
                            <div class="text-primary fw-bold">47 libros</div>
                        </div>
                        <div class="list-group-item d-flex align-items-center">
                            <div class="badge bg-secondary me-3">2°</div>
                            <div class="flex-grow-1">
                                <div class="fw-medium">Carlos Mendoza</div>
                                <small class="text-muted">4° Secundaria B</small>
                            </div>
                            <div class="text-primary fw-bold">42 libros</div>
                        </div>
                        <div class="list-group-item d-flex align-items-center">
                            <div class="badge bg-success me-3">3°</div>
                            <div class="flex-grow-1">
                                <div class="fw-medium">Ana Rodríguez</div>
                                <small class="text-muted">5° Secundaria C</small>
                            </div>
                            <div class="text-primary fw-bold">38 libros</div>
                        </div>
                        <div class="list-group-item d-flex align-items-center">
                            <div class="badge bg-info me-3">4°</div>
                            <div class="flex-grow-1">
                                <div class="fw-medium">Luis Herrera</div>
                                <small class="text-muted">3° Secundaria A</small>
                            </div>
                            <div class="text-primary fw-bold">35 libros</div>
                        </div>
                        <div class="list-group-item d-flex align-items-center">
                            <div class="badge bg-primary me-3">5°</div>
                            <div class="flex-grow-1">
                                <div class="fw-medium">Sofia López</div>
                                <small class="text-muted">4° Secundaria A</small>
                            </div>
                            <div class="text-primary fw-bold">33 libros</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla detallada de usuarios -->
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Detalle de Préstamos por Usuario</h5>
                <div class="d-flex gap-2">
                    <select class="form-select form-select-sm" style="width: auto;" onchange="cambiarVistaTabla(this.value)">
                        <option value="detallado">Vista Detallada</option>
                        <option value="resumen">Vista Resumen</option>
                        <option value="grafico">Vista Gráfica</option>
                    </select>
                    <button class="btn btn-sm btn-outline-primary" onclick="exportarTablaUsuarios()">
                        <i class="ti ti-download me-1"></i>
                        Exportar
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="tablaUsuarios">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center">#</th>
                            <th>Usuario</th>
                            <th>Nivel/Grado</th>
                            <th class="text-center">Total Préstamos</th>
                            <th class="text-center">Activos</th>
                            <th class="text-center">Completados</th>
                            <th class="text-center">Vencidos</th>
                            <th class="text-center">Último Préstamo</th>
                            <th class="text-center">Promedio/Mes</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="cuerpoTablaUsuarios">
                        <!-- Datos de ejemplo -->
                        <tr>
                            <td class="text-center">1</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                        <span class="small fw-bold">MG</span>
                                    </div>
                                    <div>
                                        <div class="fw-medium">María González</div>
                                        <small class="text-muted">maria.gonzalez@email.com</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-primary">5° Secundaria A</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-success fs-6">47</span>
                            </td>
                            <td class="text-center">3</td>
                            <td class="text-center">44</td>
                            <td class="text-center">
                                <span class="badge bg-danger">0</span>
                            </td>
                            <td class="text-center">15/03/2024</td>
                            <td class="text-center">
                                <span class="badge bg-info">3.9</span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-outline-primary" 
                                            onclick="verDetalleUsuario(1)" title="Ver detalle completo">
                                        <i class="ti ti-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-info" 
                                            onclick="verHistorialCompleto(1)" title="Ver historial">
                                        <i class="ti ti-history"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center">2</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                        <span class="small fw-bold">CM</span>
                                    </div>
                                    <div>
                                        <div class="fw-medium">Carlos Mendoza</div>
                                        <small class="text-muted">carlos.mendoza@email.com</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-primary">4° Secundaria B</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-success fs-6">42</span>
                            </td>
                            <td class="text-center">2</td>
                            <td class="text-center">39</td>
                            <td class="text-center">
                                <span class="badge bg-warning">1</span>
                            </td>
                            <td class="text-center">12/03/2024</td>
                            <td class="text-center">
                                <span class="badge bg-info">3.5</span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-outline-primary" 
                                            onclick="verDetalleUsuario(2)" title="Ver detalle completo">
                                        <i class="ti ti-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-info" 
                                            onclick="verHistorialCompleto(2)" title="Ver historial">
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
                    <small class="text-muted">Mostrando 1-25 de 156 usuarios</small>
                </div>
                <nav aria-label="Navegación de usuarios">
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

<!-- Modal Detalle Usuario -->
<div class="modal fade" id="modalDetalleUsuario" tabindex="-1" aria-labelledby="modalDetalleUsuarioLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalDetalleUsuarioLabel">
                    <i class="ti ti-user me-2"></i>
                    Detalle Completo del Usuario
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="contenidoDetalleUsuario">
                <!-- Contenido cargado dinámicamente -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="exportarDetalleUsuario()">
                    <i class="ti ti-download me-1"></i>
                    Exportar Detalle
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Funciones JavaScript para el reporte de préstamos por usuario
function aplicarFiltrosUsuarios() {
    console.log('Aplicando filtros de usuarios...');
    // Implementar lógica de filtrado
}

function exportarReportePrestamos() {
    console.log('Exportando reporte de préstamos...');
    // Implementar exportación
}

function generarGrafico() {
    console.log('Generando gráfico de tendencias...');
    // Implementar generación de gráfico
}

function cargarGrafico() {
    // Simular carga de gráfico
    document.getElementById('chartPrestamos').innerHTML = '<div class="alert alert-info">Gráfico cargado correctamente</div>';
}

function cambiarVistaTabla(vista) {
    console.log('Cambiando vista de tabla a:', vista);
    // Implementar cambio de vista
}

function exportarTablaUsuarios() {
    console.log('Exportando tabla de usuarios...');
    // Implementar exportación de tabla
}

function verDetalleUsuario(id) {
    $('#modalDetalleUsuario').modal('show');
    // Cargar datos del usuario
}

function verHistorialCompleto(id) {
    console.log('Ver historial completo del usuario:', id);
    // Implementar vista de historial
}

function exportarDetalleUsuario() {
    console.log('Exportando detalle de usuario...');
    // Implementar exportación de detalle
}

// Inicializar fechas por defecto (último mes)
document.addEventListener('DOMContentLoaded', function() {
    const today = new Date();
    const lastMonth = new Date(today.getFullYear(), today.getMonth() - 1, today.getDate());
    
    document.getElementById('filtroFechaDesde').valueAsDate = lastMonth;
    document.getElementById('filtroFechaHasta').valueAsDate = today;
});
</script>