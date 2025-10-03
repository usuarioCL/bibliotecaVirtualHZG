<?php
/**
 * Vista: Sanciones Activas
 * Descripción: Muestra todas las sanciones activas del sistema
 * Ubicación: app/Views/Administrador/sanciones/activas.php
 */
?>

<div class="container-fluid py-4">
    <!-- Header de la sección -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="ti ti-shield-x me-2 text-danger"></i>
                Sanciones Activas
            </h1>
            <p class="text-muted small mb-0">Gestión de sanciones disciplinarias vigentes</p>
        </div>
        <div>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalNuevaSancion">
                <i class="ti ti-plus me-1"></i>
                Nueva Sanción
            </button>
        </div>
    </div>

    <!-- Filtros y búsqueda -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="filtroTipo" class="form-label">Tipo de Sanción</label>
                    <select id="filtroTipo" class="form-select">
                        <option value="">Todos los tipos</option>
                        <option value="1">Amonestación Verbal</option>
                        <option value="2">Amonestación Escrita</option>
                        <option value="3">Suspensión de Préstamos</option>
                        <option value="4">Suspensión Temporal</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filtroNivel" class="form-label">Nivel Educativo</label>
                    <select id="filtroNivel" class="form-select">
                        <option value="">Todos los niveles</option>
                        <option value="Inicial">Inicial</option>
                        <option value="Primaria">Primaria</option>
                        <option value="Secundaria">Secundaria</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="buscarEstudiante" class="form-label">Buscar Estudiante</label>
                    <input type="text" id="buscarEstudiante" class="form-control" 
                           placeholder="Nombre, apellido o DNI...">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-primary w-100" onclick="aplicarFiltros()">
                        <i class="ti ti-search me-1"></i>
                        Filtrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas rápidas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <h4 class="mb-0" id="totalSanciones">12</h4>
                            <p class="mb-0 small">Total Sanciones</p>
                        </div>
                        <div class="ms-auto">
                            <i class="ti ti-alert-triangle fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <h4 class="mb-0" id="sancionesGraves">3</h4>
                            <p class="mb-0 small">Suspensiones</p>
                        </div>
                        <div class="ms-auto">
                            <i class="ti ti-shield-off fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <h4 class="mb-0" id="sancionesLeves">9</h4>
                            <p class="mb-0 small">Amonestaciones</p>
                        </div>
                        <div class="ms-auto">
                            <i class="ti ti-message-exclamation fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <h4 class="mb-0" id="estudiantesSancionados">8</h4>
                            <p class="mb-0 small">Estudiantes Afectados</p>
                        </div>
                        <div class="ms-auto">
                            <i class="ti ti-users fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de sanciones -->
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Lista de Sanciones Activas</h5>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-outline-success" onclick="exportarExcel()">
                        <i class="ti ti-file-export me-1"></i>
                        Excel
                    </button>
                    <button class="btn btn-sm btn-outline-danger" onclick="exportarPDF()">
                        <i class="ti ti-file-type-pdf me-1"></i>
                        PDF
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="tablaSanciones">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center">#</th>
                            <th>Estudiante</th>
                            <th>Documento</th>
                            <th>Nivel/Grado</th>
                            <th>Tipo de Sanción</th>
                            <th>Detalle</th>
                            <th class="text-center">Fecha Sanción</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="cuerpoTablaSanciones">
                        <!-- Datos cargados dinámicamente -->
                        <tr>
                            <td class="text-center">1</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-danger text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                        <span class="small fw-bold">JP</span>
                                    </div>
                                    <div>
                                        <div class="fw-medium">Juan Pérez López</div>
                                        <small class="text-muted">juan.perez@email.com</small>
                                    </div>
                                </div>
                            </td>
                            <td>12345678</td>
                            <td>
                                <span class="badge bg-primary">5° Secundaria A</span>
                            </td>
                            <td>
                                <span class="badge bg-warning text-dark">Suspensión de Préstamos</span>
                            </td>
                            <td>
                                <span class="text-truncate d-inline-block" style="max-width: 200px;" 
                                      title="Daño intencional a libro de texto de Matemáticas">
                                    Daño intencional a libro...
                                </span>
                            </td>
                            <td class="text-center">15/03/2024</td>
                            <td class="text-center">
                                <span class="badge bg-danger">Activa</span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-outline-primary" 
                                            onclick="verDetalle(1)" title="Ver detalles">
                                        <i class="ti ti-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-warning" 
                                            onclick="editarSancion(1)" title="Editar sanción">
                                        <i class="ti ti-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-success" 
                                            onclick="levantarSancion(1)" title="Levantar sanción">
                                        <i class="ti ti-check"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <!-- Más filas de ejemplo... -->
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-light">
            <!-- Paginación -->
            <nav aria-label="Navegación de sanciones">
                <ul class="pagination pagination-sm justify-content-center mb-0">
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

<!-- Modal Nueva Sanción -->
<div class="modal fade" id="modalNuevaSancion" tabindex="-1" aria-labelledby="modalNuevaSancionLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="modalNuevaSancionLabel">
                    <i class="ti ti-shield-x me-2"></i>
                    Nueva Sanción Disciplinaria
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formNuevaSancion">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="tipoSancion" class="form-label">Tipo de Sanción</label>
                            <select id="tipoSancion" class="form-select" required>
                                <option value="">Seleccionar tipo...</option>
                                <option value="1">Amonestación Verbal</option>
                                <option value="2">Amonestación Escrita</option>
                                <option value="3">Suspensión de Préstamos</option>
                                <option value="4">Suspensión Temporal</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="estudiante" class="form-label">Estudiante</label>
                            <select id="estudiante" class="form-select" required>
                                <option value="">Buscar estudiante...</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="detalleSancion" class="form-label">Detalle de la Sanción</label>
                            <textarea id="detalleSancion" class="form-control" rows="4" 
                                      placeholder="Describe los motivos y detalles de la sanción..." required></textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="fechaSancion" class="form-label">Fecha de Sanción</label>
                            <input type="date" id="fechaSancion" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="fechaVencimiento" class="form-label">Fecha de Vencimiento (Opcional)</label>
                            <input type="date" id="fechaVencimiento" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ti ti-x me-1"></i>
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="ti ti-device-floppy me-1"></i>
                        Registrar Sanción
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Ver Detalle -->
<div class="modal fade" id="modalDetalleSancion" tabindex="-1" aria-labelledby="modalDetalleSancionLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="modalDetalleSancionLabel">
                    <i class="ti ti-info-circle me-2"></i>
                    Detalle de la Sanción
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="contenidoDetalleSancion">
                <!-- Contenido cargado dinámicamente -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
// Funciones JavaScript para la gestión de sanciones
function aplicarFiltros() {
    // Implementar filtrado de la tabla
    console.log('Aplicando filtros...');
}

function exportarExcel() {
    // Implementar exportación a Excel
    console.log('Exportando a Excel...');
}

function exportarPDF() {
    // Implementar exportación a PDF
    console.log('Exportando a PDF...');
}

function verDetalle(id) {
    // Mostrar modal con detalles de la sanción
    $('#modalDetalleSancion').modal('show');
}

function editarSancion(id) {
    // Abrir modal de edición
    console.log('Editando sanción:', id);
}

function levantarSancion(id) {
    if (confirm('¿Está seguro de que desea levantar esta sanción?')) {
        console.log('Levantando sanción:', id);
    }
}

// Inicializar fecha actual en el formulario
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('fechaSancion').valueAsDate = new Date();
});
</script>