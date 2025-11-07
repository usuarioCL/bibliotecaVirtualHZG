<!-- Modal para Mis Tareas -->
<div class="modal fade" id="modalMisTareas" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ti ti-list-check text-primary me-2"></i>
                    Mis Tareas y Actividades
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Estadísticas rápidas -->
                <div class="row mb-4">
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="card bg-primary text-white h-100">
                            <div class="card-body text-center">
                                <i class="ti ti-clock-hour-4" style="font-size: 2.5rem;"></i>
                                <h3 class="mt-2 mb-1">8</h3>
                                <p class="mb-0 small">Tareas Pendientes</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="card bg-success text-white h-100">
                            <div class="card-body text-center">
                                <i class="ti ti-check-circle" style="font-size: 2.5rem;"></i>
                                <h3 class="mt-2 mb-1">25</h3>
                                <p class="mb-0 small">Completadas</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="card bg-warning text-white h-100">
                            <div class="card-body text-center">
                                <i class="ti ti-alert-triangle" style="font-size: 2.5rem;"></i>
                                <h3 class="mt-2 mb-1">3</h3>
                                <p class="mb-0 small">Vencidas</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="card bg-info text-white h-100">
                            <div class="card-body text-center">
                                <i class="ti ti-progress" style="font-size: 2.5rem;"></i>
                                <h3 class="mt-2 mb-1">5</h3>
                                <p class="mb-0 small">En Progreso</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabs de navegación -->
                <ul class="nav nav-tabs" id="tareasTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pendientes-tab" data-bs-toggle="tab" data-bs-target="#pendientes" type="button" role="tab">
                            <i class="ti ti-clock me-2"></i>Pendientes
                            <span class="badge bg-primary ms-2">8</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="progreso-tab" data-bs-toggle="tab" data-bs-target="#progreso" type="button" role="tab">
                            <i class="ti ti-progress me-2"></i>En Progreso
                            <span class="badge bg-info ms-2">5</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="completadas-tab" data-bs-toggle="tab" data-bs-target="#completadas" type="button" role="tab">
                            <i class="ti ti-check me-2"></i>Completadas
                            <span class="badge bg-success ms-2">25</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="vencidas-tab" data-bs-toggle="tab" data-bs-target="#vencidas" type="button" role="tab">
                            <i class="ti ti-alert-triangle me-2"></i>Vencidas
                            <span class="badge bg-warning ms-2">3</span>
                        </button>
                    </li>
                </ul>

                <!-- Contenido de los tabs -->
                <div class="tab-content pt-4" id="tareasTabContent">
                    <!-- Tab Tareas Pendientes -->
                    <div class="tab-pane fade show active" id="pendientes" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0">Tareas Pendientes</h6>
                            <button class="btn btn-primary btn-sm" onclick="agregarTarea()">
                                <i class="ti ti-plus me-1"></i>Nueva Tarea
                            </button>
                        </div>
                        
                        <div class="list-group">
                            <div class="list-group-item d-flex justify-content-between align-items-start">
                                <div class="ms-2 me-auto">
                                    <div class="d-flex align-items-center mb-1">
                                        <input type="checkbox" class="form-check-input me-3" onchange="completarTarea(this, 1)">
                                        <h6 class="mb-0">Revisar reportes mensuales de préstamos</h6>
                                        <span class="badge bg-danger ms-2">Alta</span>
                                    </div>
                                    <p class="mb-1 text-muted small">Análisis de estadísticas y tendencias de préstamos del mes actual</p>
                                    <small class="text-muted">
                                        <i class="ti ti-calendar me-1"></i>Vence: 15/10/2025
                                        <i class="ti ti-user ms-3 me-1"></i>Asignado por: Director
                                    </small>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                                        <i class="ti ti-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#" onclick="editarTarea(1)"><i class="ti ti-edit me-2"></i>Editar</a></li>
                                        <li><a class="dropdown-item" href="#" onclick="verDetalles(1)"><i class="ti ti-eye me-2"></i>Ver Detalles</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="#" onclick="eliminarTarea(1)"><i class="ti ti-trash me-2"></i>Eliminar</a></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="list-group-item d-flex justify-content-between align-items-start">
                                <div class="ms-2 me-auto">
                                    <div class="d-flex align-items-center mb-1">
                                        <input type="checkbox" class="form-check-input me-3" onchange="completarTarea(this, 2)">
                                        <h6 class="mb-0">Actualizar catálogo de nuevos libros</h6>
                                        <span class="badge bg-warning ms-2">Media</span>
                                    </div>
                                    <p class="mb-1 text-muted small">Catalogar e ingresar al sistema los 45 libros recibidos esta semana</p>
                                    <small class="text-muted">
                                        <i class="ti ti-calendar me-1"></i>Vence: 20/10/2025
                                        <i class="ti ti-user ms-3 me-1"></i>Asignado por: Coordinador
                                    </small>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                                        <i class="ti ti-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#" onclick="editarTarea(2)"><i class="ti ti-edit me-2"></i>Editar</a></li>
                                        <li><a class="dropdown-item" href="#" onclick="verDetalles(2)"><i class="ti ti-eye me-2"></i>Ver Detalles</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="#" onclick="eliminarTarea(2)"><i class="ti ti-trash me-2"></i>Eliminar</a></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="list-group-item d-flex justify-content-between align-items-start">
                                <div class="ms-2 me-auto">
                                    <div class="d-flex align-items-center mb-1">
                                        <input type="checkbox" class="form-check-input me-3" onchange="completarTarea(this, 3)">
                                        <h6 class="mb-0">Organizar evento "Semana del Libro"</h6>
                                        <span class="badge bg-info ms-2">Baja</span>
                                    </div>
                                    <p class="mb-1 text-muted small">Coordinar actividades y logística para la semana cultural</p>
                                    <small class="text-muted">
                                        <i class="ti ti-calendar me-1"></i>Vence: 30/10/2025
                                        <i class="ti ti-user ms-3 me-1"></i>Auto-asignada
                                    </small>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                                        <i class="ti ti-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#" onclick="editarTarea(3)"><i class="ti ti-edit me-2"></i>Editar</a></li>
                                        <li><a class="dropdown-item" href="#" onclick="verDetalles(3)"><i class="ti ti-eye me-2"></i>Ver Detalles</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="#" onclick="eliminarTarea(3)"><i class="ti ti-trash me-2"></i>Eliminar</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab En Progreso -->
                    <div class="tab-pane fade" id="progreso" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0">Tareas en Progreso</h6>
                            <small class="text-muted">Tareas que estás trabajando actualmente</small>
                        </div>
                        
                        <div class="list-group">
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="mb-1">Migración de datos históricos</h6>
                                        <p class="mb-1 text-muted small">Transferir registros del sistema anterior al nuevo</p>
                                    </div>
                                    <span class="badge bg-info">En Progreso</span>
                                </div>
                                <div class="progress mb-2" style="height: 8px;">
                                    <div class="progress-bar bg-info" style="width: 65%"></div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">65% completado</small>
                                    <small class="text-muted">Iniciado: 01/10/2025</small>
                                </div>
                            </div>

                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="mb-1">Capacitación del personal</h6>
                                        <p class="mb-1 text-muted small">Entrenar al equipo en el uso del nuevo sistema</p>
                                    </div>
                                    <span class="badge bg-info">En Progreso</span>
                                </div>
                                <div class="progress mb-2" style="height: 8px;">
                                    <div class="progress-bar bg-info" style="width: 40%"></div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">40% completado</small>
                                    <small class="text-muted">Iniciado: 05/10/2025</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab Completadas -->
                    <div class="tab-pane fade" id="completadas" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0">Tareas Completadas</h6>
                            <small class="text-muted">Últimas 10 tareas completadas</small>
                        </div>
                        
                        <div class="list-group">
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">
                                        <i class="ti ti-check-circle text-success me-2"></i>
                                        Configuración inicial del sistema
                                    </h6>
                                    <p class="mb-0 text-muted small">Completado el 06/10/2025</p>
                                </div>
                                <span class="badge bg-success">Completada</span>
                            </div>

                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">
                                        <i class="ti ti-check-circle text-success me-2"></i>
                                        Backup de datos mensuales
                                    </h6>
                                    <p class="mb-0 text-muted small">Completado el 05/10/2025</p>
                                </div>
                                <span class="badge bg-success">Completada</span>
                            </div>

                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">
                                        <i class="ti ti-check-circle text-success me-2"></i>
                                        Inventario de libros de octubre
                                    </h6>
                                    <p class="mb-0 text-muted small">Completado el 04/10/2025</p>
                                </div>
                                <span class="badge bg-success">Completada</span>
                            </div>
                        </div>
                    </div>

                    <!-- Tab Vencidas -->
                    <div class="tab-pane fade" id="vencidas" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0 text-danger">Tareas Vencidas</h6>
                            <small class="text-muted">Requieren atención inmediata</small>
                        </div>
                        
                        <div class="alert alert-warning">
                            <i class="ti ti-alert-triangle me-2"></i>
                            <strong>¡Atención!</strong> Tienes tareas vencidas que requieren acción inmediata.
                        </div>
                        
                        <div class="list-group">
                            <div class="list-group-item border-danger">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1 text-danger">Revisión de sanciones pendientes</h6>
                                        <p class="mb-1 text-muted small">Revisar y gestionar las sanciones acumuladas del mes</p>
                                        <small class="text-danger">
                                            <i class="ti ti-alert-circle me-1"></i>Venció el: 03/10/2025 (4 días de retraso)
                                        </small>
                                    </div>
                                    <div>
                                        <button class="btn btn-sm btn-danger me-2" onclick="marcarUrgente(1)">
                                            <i class="ti ti-clock"></i> Urgente
                                        </button>
                                        <button class="btn btn-sm btn-outline-success" onclick="completarTareaVencida(1)">
                                            <i class="ti ti-check"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="list-group-item border-warning">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1 text-warning">Actualización de políticas</h6>
                                        <p class="mb-1 text-muted small">Revisar y actualizar las políticas de préstamo</p>
                                        <small class="text-warning">
                                            <i class="ti ti-alert-circle me-1"></i>Venció el: 05/10/2025 (2 días de retraso)
                                        </small>
                                    </div>
                                    <div>
                                        <button class="btn btn-sm btn-warning me-2" onclick="marcarUrgente(2)">
                                            <i class="ti ti-clock"></i> Urgente
                                        </button>
                                        <button class="btn btn-sm btn-outline-success" onclick="completarTareaVencida(2)">
                                            <i class="ti ti-check"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="ti ti-x me-2"></i>Cerrar
                </button>
                <button type="button" class="btn btn-info" onclick="exportarTareas()">
                    <i class="ti ti-download me-2"></i>Exportar Tareas
                </button>
                <button type="button" class="btn btn-primary" onclick="crearReporte()">
                    <i class="ti ti-file-report me-2"></i>Generar Reporte
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para agregar nueva tarea -->
<div class="modal fade" id="modalNuevaTarea" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ti ti-plus text-primary me-2"></i>
                    Nueva Tarea
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formNuevaTarea">
                    <div class="mb-3">
                        <label for="titulo_tarea" class="form-label">Título de la Tarea</label>
                        <input type="text" class="form-control" id="titulo_tarea" name="titulo" required>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion_tarea" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion_tarea" name="descripcion" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="prioridad_tarea" class="form-label">Prioridad</label>
                                <select class="form-select" id="prioridad_tarea" name="prioridad">
                                    <option value="baja">Baja</option>
                                    <option value="media" selected>Media</option>
                                    <option value="alta">Alta</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="fecha_vencimiento" class="form-label">Fecha de Vencimiento</label>
                                <input type="date" class="form-control" id="fecha_vencimiento" name="fecha_vencimiento">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="categoria_tarea" class="form-label">Categoría</label>
                        <select class="form-select" id="categoria_tarea" name="categoria">
                            <option value="administracion">Administración</option>
                            <option value="catalogacion">Catalogación</option>
                            <option value="atencion_usuario">Atención al Usuario</option>
                            <option value="mantenimiento">Mantenimiento</option>
                            <option value="reportes">Reportes</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardarNuevaTarea()">
                    <i class="ti ti-device-floppy me-2"></i>Crear Tarea
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Función para completar tarea
function completarTarea(checkbox, tareaId) {
    if (checkbox.checked) {
        Swal.fire({
            title: '¿Marcar como completada?',
            text: '¿Estás seguro de que has completado esta tarea?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, completar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#28a745'
        }).then((result) => {
            if (result.isConfirmed) {
                // Simular completar tarea
                const item = checkbox.closest('.list-group-item');
                item.style.opacity = '0.6';
                item.style.textDecoration = 'line-through';
                
                Swal.fire({
                    title: '¡Tarea Completada!',
                    text: 'La tarea ha sido marcada como completada',
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                });
                
                // Remover después de 2 segundos
                setTimeout(() => {
                    item.remove();
                    actualizarContadores();
                }, 2000);
            } else {
                checkbox.checked = false;
            }
        });
    }
}

// Función para agregar nueva tarea
function agregarTarea() {
    const modalNuevaTarea = new bootstrap.Modal(document.getElementById('modalNuevaTarea'));
    modalNuevaTarea.show();
}

// Función para guardar nueva tarea
function guardarNuevaTarea() {
    const form = document.getElementById('formNuevaTarea');
    if (form.checkValidity()) {
        Swal.fire({
            title: 'Tarea Creada',
            text: 'La nueva tarea ha sido agregada a tu lista',
            icon: 'success',
            timer: 1500,
            showConfirmButton: false
        });
        
        // Cerrar modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('modalNuevaTarea'));
        modal.hide();
        
        // Limpiar formulario
        form.reset();
        
        // Actualizar contadores
        actualizarContadores();
    } else {
        form.reportValidity();
    }
}

// Función para editar tarea
function editarTarea(tareaId) {
    Swal.fire({
        title: 'Editar Tarea',
        html: `
            <div class="text-start">
                <div class="mb-3">
                    <label class="form-label">Título:</label>
                    <input type="text" class="form-control" value="Tarea de ejemplo" id="swal-titulo">
                </div>
                <div class="mb-3">
                    <label class="form-label">Prioridad:</label>
                    <select class="form-select" id="swal-prioridad">
                        <option value="baja">Baja</option>
                        <option value="media" selected>Media</option>
                        <option value="alta">Alta</option>
                    </select>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Guardar Cambios',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Guardado', 'Los cambios han sido guardados', 'success');
        }
    });
}

// Función para ver detalles
function verDetalles(tareaId) {
    Swal.fire({
        title: 'Detalles de la Tarea',
        html: `
            <div class="text-start">
                <h6>Revisar reportes mensuales de préstamos</h6>
                <p class="text-muted">Análisis de estadísticas y tendencias de préstamos del mes actual</p>
                <hr>
                <p><strong>Prioridad:</strong> <span class="badge bg-danger">Alta</span></p>
                <p><strong>Fecha de vencimiento:</strong> 15/10/2025</p>
                <p><strong>Asignado por:</strong> Director</p>
                <p><strong>Fecha de creación:</strong> 01/10/2025</p>
                <p><strong>Estado:</strong> Pendiente</p>
            </div>
        `,
        confirmButtonText: 'Cerrar'
    });
}

// Función para eliminar tarea
function eliminarTarea(tareaId) {
    Swal.fire({
        title: '¿Eliminar Tarea?',
        text: 'Esta acción no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#dc3545'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Eliminada', 'La tarea ha sido eliminada', 'success');
            actualizarContadores();
        }
    });
}

// Función para marcar como urgente
function marcarUrgente(tareaId) {
    Swal.fire({
        title: 'Tarea Marcada como Urgente',
        text: 'Esta tarea será priorizada en tu lista',
        icon: 'warning',
        timer: 1500,
        showConfirmButton: false
    });
}

// Función para completar tarea vencida
function completarTareaVencida(tareaId) {
    Swal.fire({
        title: '¿Completar Tarea Vencida?',
        text: 'Marcar esta tarea vencida como completada',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Completar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Completada', 'La tarea vencida ha sido marcada como completada', 'success');
            actualizarContadores();
        }
    });
}

// Función para exportar tareas
function exportarTareas() {
    Swal.fire({
        title: 'Exportar Tareas',
        html: `
            <div class="text-start">
                <p>Selecciona el formato de exportación:</p>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="formato" id="excel" value="excel" checked>
                    <label class="form-check-label" for="excel">Excel (.xlsx)</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="formato" id="pdf" value="pdf">
                    <label class="form-check-label" for="pdf">PDF</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="formato" id="csv" value="csv">
                    <label class="form-check-label" for="csv">CSV</label>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Exportar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Exportando...', 'Tu archivo se descargará en breve', 'info');
        }
    });
}

// Función para crear reporte
function crearReporte() {
    Swal.fire({
        title: 'Generar Reporte de Tareas',
        text: 'Se generará un reporte completo de tu actividad',
        icon: 'info',
        showCancelButton: true,
        confirmButtonText: 'Generar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Generando Reporte...', 'El reporte se enviará a tu correo', 'success');
        }
    });
}

// Función para actualizar contadores
function actualizarContadores() {
    // Esta función actualizaría los números en las tarjetas de estadísticas
    console.log('Actualizando contadores de tareas...');
}

// Inicializar modal al cargar
document.addEventListener('DOMContentLoaded', function() {
    // Configurar fecha mínima para nuevas tareas
    const fechaInput = document.getElementById('fecha_vencimiento');
    if (fechaInput) {
        const hoy = new Date().toISOString().split('T')[0];
        fechaInput.min = hoy;
    }
});
</script>

<style>
/* Estilos específicos para el modal de tareas */
#modalMisTareas .nav-tabs {
    border-bottom: 2px solid #e9ecef;
}

#modalMisTareas .nav-tabs .nav-link {
    border: none;
    color: #6c757d;
    padding: 12px 20px;
    font-weight: 500;
}

#modalMisTareas .nav-tabs .nav-link.active {
    color: #0d6efd;
    border-bottom: 2px solid #0d6efd;
    background: none;
}

#modalMisTareas .list-group-item {
    border: 1px solid #e9ecef;
    margin-bottom: 8px;
    border-radius: 8px;
}

#modalMisTareas .list-group-item:hover {
    background-color: #f8f9fa;
}

#modalMisTareas .badge {
    font-size: 0.75rem;
}

#modalMisTareas .progress {
    background-color: #e9ecef;
}

#modalMisTareas .border-danger {
    border-color: #dc3545 !important;
    border-width: 2px;
}

#modalMisTareas .border-warning {
    border-color: #ffc107 !important;
    border-width: 2px;
}

/* Z-index fixes */
#modalMisTareas,
#modalNuevaTarea {
    z-index: 99999 !important;
}

#modalMisTareas .modal-backdrop,
#modalNuevaTarea .modal-backdrop {
    z-index: 99998 !important;
}

#modalMisTareas .modal-content,
#modalNuevaTarea .modal-content {
    z-index: 100001 !important;
    position: relative !important;
}

#modalMisTareas .modal-header,
#modalMisTareas .modal-body,
#modalMisTareas .modal-footer,
#modalNuevaTarea .modal-header,
#modalNuevaTarea .modal-body,
#modalNuevaTarea .modal-footer {
    z-index: 100002 !important;
    position: relative !important;
}

/* Reglas específicas con máxima especificidad */
body .modal#modalMisTareas,
body .modal#modalNuevaTarea {
    z-index: 99999 !important;
}

body .modal#modalMisTareas.show,
body .modal#modalNuevaTarea.show {
    z-index: 99999 !important;
    display: block !important;
}

html body .modal#modalMisTareas,
html body .modal#modalNuevaTarea {
    z-index: 99999 !important;
}

/* Fix específico para el contenedor principal */
#contenedor-principal .modal#modalMisTareas,
#contenedor-principal .modal#modalNuevaTarea {
    z-index: 99999 !important;
}

/* Asegurar que funcione en el contexto del dashboard */
.page-wrapper .modal#modalMisTareas,
.body-wrapper .modal#modalMisTareas,
.page-wrapper .modal#modalNuevaTarea,
.body-wrapper .modal#modalNuevaTarea {
    z-index: 99999 !important;
}
</style>