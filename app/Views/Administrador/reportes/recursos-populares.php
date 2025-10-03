<?php
/**
 * Vista: Reportes - Recursos Populares
 * Descripción: Ranking y estadísticas de los recursos más solicitados
 * Ubicación: app/Views/Administrador/reportes/recursos-populares.php
 */
?>

<div class="container-fluid py-4">
    <!-- Header de la sección -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="ti ti-trending-up me-2 text-success"></i>
                Recursos Populares
            </h1>
            <p class="text-muted small mb-0">Análisis de popularidad y demanda de recursos bibliográficos</p>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-info" onclick="actualizarRanking()">
                <i class="ti ti-refresh me-1"></i>
                Actualizar Datos
            </button>
            <button type="button" class="btn btn-success" onclick="exportarRanking()">
                <i class="ti ti-file-export me-1"></i>
                Exportar Ranking
            </button>
        </div>
    </div>

    <!-- Filtros de análisis -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h6 class="mb-0">
                <i class="ti ti-adjustments me-1"></i>
                Parámetros de Análisis
            </h6>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="periodoAnalisis" class="form-label">Período de Análisis</label>
                    <select id="periodoAnalisis" class="form-select">
                        <option value="7">Últimos 7 días</option>
                        <option value="30" selected>Último mes</option>
                        <option value="90">Últimos 3 meses</option>
                        <option value="180">Últimos 6 meses</option>
                        <option value="365">Último año</option>
                        <option value="all">Todo el tiempo</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="categoriaFiltro" class="form-label">Categoría</label>
                    <select id="categoriaFiltro" class="form-select">
                        <option value="">Todas las categorías</option>
                        <option value="1">Literatura</option>
                        <option value="2">Ciencias</option>
                        <option value="3">Matemáticas</option>
                        <option value="4">Historia</option>
                        <option value="5">Geografía</option>
                        <option value="6">Arte</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="tipoRecurso" class="form-label">Tipo de Recurso</label>
                    <select id="tipoRecurso" class="form-select">
                        <option value="">Todos los tipos</option>
                        <option value="fisico">Recursos Físicos</option>
                        <option value="digital">Recursos Digitales</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="button" class="btn btn-primary w-100" onclick="aplicarFiltrosPopulares()">
                        <i class="ti ti-search me-1"></i>
                        Analizar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Métricas de popularidad -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-gradient-success text-white h-100">
                <div class="card-body text-center">
                    <i class="ti ti-trophy fs-1 mb-2"></i>
                    <h4 class="mb-1" id="recursoMasPopular">"El Principito"</h4>
                    <p class="mb-0 small">Recurso Más Popular</p>
                    <small class="opacity-75">127 préstamos</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-gradient-primary text-white h-100">
                <div class="card-body text-center">
                    <i class="ti ti-books fs-1 mb-2"></i>
                    <h4 class="mb-1" id="totalRecursosPrestados">1,456</h4>
                    <p class="mb-0 small">Total Préstamos</p>
                    <small class="opacity-75">en el período</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-gradient-warning text-white h-100">
                <div class="card-body text-center">
                    <i class="ti ti-star fs-1 mb-2"></i>
                    <h4 class="mb-1" id="promedioPopularidad">8.5</h4>
                    <p class="mb-0 small">Puntuación Promedio</p>
                    <small class="opacity-75">satisfacción</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-gradient-info text-white h-100">
                <div class="card-body text-center">
                    <i class="ti ti-chart-arrows-vertical fs-1 mb-2"></i>
                    <h4 class="mb-1" id="crecimientoPopularidad">+15%</h4>
                    <p class="mb-0 small">Crecimiento</p>
                    <small class="opacity-75">vs período anterior</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Top 10 y análisis visual -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0 text-white">
                        <i class="ti ti-medal me-1"></i>
                        Top 10 Recursos Más Populares
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush" id="topRecursos">
                        <div class="list-group-item d-flex align-items-center">
                            <div class="ranking-badge ranking-1 me-3">1</div>
                            <div class="flex-grow-1">
                                <div class="fw-medium text-truncate">El Principito</div>
                                <small class="text-muted">Antoine de Saint-Exupéry • Literatura</small>
                                <div class="progress mt-1" style="height: 4px;">
                                    <div class="progress-bar bg-success" style="width: 100%"></div>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="text-success fw-bold">127</div>
                                <small class="text-muted">préstamos</small>
                            </div>
                        </div>
                        <div class="list-group-item d-flex align-items-center">
                            <div class="ranking-badge ranking-2 me-3">2</div>
                            <div class="flex-grow-1">
                                <div class="fw-medium text-truncate">Cien años de soledad</div>
                                <small class="text-muted">Gabriel García Márquez • Literatura</small>
                                <div class="progress mt-1" style="height: 4px;">
                                    <div class="progress-bar bg-success" style="width: 95%"></div>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="text-success fw-bold">121</div>
                                <small class="text-muted">préstamos</small>
                            </div>
                        </div>
                        <div class="list-group-item d-flex align-items-center">
                            <div class="ranking-badge ranking-3 me-3">3</div>
                            <div class="flex-grow-1">
                                <div class="fw-medium text-truncate">Álgebra de Baldor</div>
                                <small class="text-muted">Aurelio Baldor • Matemáticas</small>
                                <div class="progress mt-1" style="height: 4px;">
                                    <div class="progress-bar bg-info" style="width: 88%"></div>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="text-info fw-bold">112</div>
                                <small class="text-muted">préstamos</small>
                            </div>
                        </div>
                        <div class="list-group-item d-flex align-items-center">
                            <div class="ranking-badge ranking-other me-3">4</div>
                            <div class="flex-grow-1">
                                <div class="fw-medium text-truncate">Don Quijote de la Mancha</div>
                                <small class="text-muted">Miguel de Cervantes • Literatura</small>
                                <div class="progress mt-1" style="height: 4px;">
                                    <div class="progress-bar bg-warning" style="width: 78%"></div>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="text-warning fw-bold">99</div>
                                <small class="text-muted">préstamos</small>
                            </div>
                        </div>
                        <div class="list-group-item d-flex align-items-center">
                            <div class="ranking-badge ranking-other me-3">5</div>
                            <div class="flex-grow-1">
                                <div class="fw-medium text-truncate">Historia del Perú Contemporáneo</div>
                                <small class="text-muted">Carlos Contreras • Historia</small>
                                <div class="progress mt-1" style="height: 4px;">
                                    <div class="progress-bar bg-secondary" style="width: 70%"></div>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="text-secondary fw-bold">89</div>
                                <small class="text-muted">préstamos</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light text-center">
                    <button class="btn btn-sm btn-outline-success" onclick="verRankingCompleto()">
                        Ver Ranking Completo
                    </button>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="ti ti-chart-pie me-1"></i>
                        Distribución por Categorías
                    </h6>
                </div>
                <div class="card-body">
                    <div id="chartCategorias" style="height: 250px;">
                        <!-- Gráfico de donut -->
                        <div class="row h-100">
                            <div class="col-6 d-flex flex-column justify-content-center">
                                <div class="mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="badge bg-primary me-2" style="width: 12px; height: 12px;"></div>
                                        <span class="small">Literatura (35%)</span>
                                    </div>
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="badge bg-success me-2" style="width: 12px; height: 12px;"></div>
                                        <span class="small">Matemáticas (22%)</span>
                                    </div>
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="badge bg-warning me-2" style="width: 12px; height: 12px;"></div>
                                        <span class="small">Ciencias (18%)</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 d-flex flex-column justify-content-center">
                                <div class="mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="badge bg-info me-2" style="width: 12px; height: 12px;"></div>
                                        <span class="small">Historia (15%)</span>
                                    </div>
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="badge bg-secondary me-2" style="width: 12px; height: 12px;"></div>
                                        <span class="small">Arte (6%)</span>
                                    </div>
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="badge bg-dark me-2" style="width: 12px; height: 12px;"></div>
                                        <span class="small">Otros (4%)</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light text-center">
                    <button class="btn btn-sm btn-outline-primary" onclick="verAnalisisDetallado()">
                        Análisis Detallado
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tendencias y comparativas -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                            <i class="ti ti-trending-up me-1"></i>
                            Tendencias de Popularidad (Últimos 6 Meses)
                        </h6>
                        <div class="btn-group btn-group-sm" role="group">
                            <input type="radio" class="btn-check" name="tipoGrafico" id="lineal" checked>
                            <label class="btn btn-outline-primary" for="lineal">Líneas</label>
                            <input type="radio" class="btn-check" name="tipoGrafico" id="barras">
                            <label class="btn btn-outline-primary" for="barras">Barras</label>
                            <input type="radio" class="btn-check" name="tipoGrafico" id="area">
                            <label class="btn btn-outline-primary" for="area">Área</label>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="chartTendencias" style="height: 350px;">
                        <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                            <div class="text-center">
                                <i class="ti ti-chart-line fs-1 mb-3"></i>
                                <p class="mb-3">Gráfico de tendencias de popularidad</p>
                                <button class="btn btn-primary" onclick="cargarTendencias()">Cargar Tendencias</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla detallada -->
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Catálogo Completo con Métricas de Popularidad</h5>
                <div class="d-flex gap-2">
                    <input type="text" class="form-control form-control-sm" placeholder="Buscar recurso..." 
                           style="width: 200px;" onkeyup="buscarEnTabla(this.value)">
                    <button class="btn btn-sm btn-outline-primary" onclick="exportarCatalogo()">
                        <i class="ti ti-download me-1"></i>
                        Exportar
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="tablaRecursosPopulares">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center">#</th>
                            <th>Recurso</th>
                            <th>Categoría</th>
                            <th class="text-center">Total Préstamos</th>
                            <th class="text-center">Activos</th>
                            <th class="text-center">Puntuación</th>
                            <th class="text-center">Favoritos</th>
                            <th class="text-center">Tendencia</th>
                            <th class="text-center">Disponibilidad</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="cuerpoTablaRecursos">
                        <!-- Datos de ejemplo -->
                        <tr>
                            <td class="text-center">
                                <div class="ranking-badge ranking-1">1</div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="/img/default-book.jpg" alt="Portada" class="me-2" 
                                         style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                                    <div>
                                        <div class="fw-medium">El Principito</div>
                                        <small class="text-muted">Antoine de Saint-Exupéry</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-primary">Literatura</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-success fs-6">127</span>
                            </td>
                            <td class="text-center">8</td>
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center">
                                    <span class="me-1">4.8</span>
                                    <i class="ti ti-star-filled text-warning"></i>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-danger">94</span>
                            </td>
                            <td class="text-center">
                                <i class="ti ti-trending-up text-success fs-5" title="Tendencia al alza"></i>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-success">Disponible</span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-outline-info" 
                                            onclick="verAnalisisRecurso(1)" title="Análisis detallado">
                                        <i class="ti ti-chart-dots"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-primary" 
                                            onclick="verDetalleRecurso(1)" title="Ver detalles">
                                        <i class="ti ti-eye"></i>
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
                    <small class="text-muted">Mostrando 1-20 de 245 recursos</small>
                </div>
                <nav aria-label="Navegación de recursos">
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

<!-- Modal Análisis de Recurso -->
<div class="modal fade" id="modalAnalisisRecurso" tabindex="-1" aria-labelledby="modalAnalisisRecursoLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="modalAnalisisRecursoLabel">
                    <i class="ti ti-chart-dots me-2"></i>
                    Análisis Detallado del Recurso
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="contenidoAnalisisRecurso">
                <!-- Contenido cargado dinámicamente -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-info" onclick="exportarAnalisis()">
                    <i class="ti ti-download me-1"></i>
                    Exportar Análisis
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Estilos personalizados para rankings */
.ranking-badge {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 14px;
}

.ranking-1 {
    background: linear-gradient(135deg, #FFD700, #FFA500);
    box-shadow: 0 2px 8px rgba(255, 215, 0, 0.3);
}

.ranking-2 {
    background: linear-gradient(135deg, #C0C0C0, #A0A0A0);
    box-shadow: 0 2px 8px rgba(192, 192, 192, 0.3);
}

.ranking-3 {
    background: linear-gradient(135deg, #CD7F32, #8B4513);
    box-shadow: 0 2px 8px rgba(205, 127, 50, 0.3);
}

.ranking-other {
    background: linear-gradient(135deg, #6c757d, #495057);
}
</style>

<script>
// Funciones JavaScript para recursos populares
function aplicarFiltrosPopulares() {
    console.log('Aplicando filtros de popularidad...');
    // Implementar lógica de filtrado
}

function actualizarRanking() {
    console.log('Actualizando datos del ranking...');
    // Implementar actualización de datos
}

function exportarRanking() {
    console.log('Exportando ranking de recursos...');
    // Implementar exportación
}

function verRankingCompleto() {
    console.log('Mostrando ranking completo...');
    // Implementar vista completa del ranking
}

function verAnalisisDetallado() {
    console.log('Mostrando análisis detallado...');
    // Implementar análisis detallado
}

function cargarTendencias() {
    document.getElementById('chartTendencias').innerHTML = '<div class="alert alert-success">Gráfico de tendencias cargado correctamente</div>';
}

function buscarEnTabla(termino) {
    console.log('Buscando:', termino);
    // Implementar búsqueda en tabla
}

function exportarCatalogo() {
    console.log('Exportando catálogo completo...');
    // Implementar exportación del catálogo
}

function verAnalisisRecurso(id) {
    $('#modalAnalisisRecurso').modal('show');
    // Cargar análisis del recurso
}

function verDetalleRecurso(id) {
    console.log('Ver detalle del recurso:', id);
    // Implementar vista de detalle
}

function exportarAnalisis() {
    console.log('Exportando análisis detallado...');
    // Implementar exportación de análisis
}
</script>