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
                            <label for="tipoSancion" class="form-label">Tipo de Sanción <span class="text-danger">*</span></label>
                            <select id="tipoSancion" name="idtiposancion" class="form-select" required>
                                <option value="">Seleccionar tipo...</option>
                                <?php if (isset($tipos_sancion) && is_array($tipos_sancion)) : ?>
                                    <?php foreach ($tipos_sancion as $tipo) : ?>
                                        <option value="<?= esc($tipo['idtiposancion']) ?>"><?= esc($tipo['tiposancion']) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="estudiante" class="form-label">Estudiante <span class="text-danger">*</span></label>
                            <select id="estudiante" name="idpersona" class="form-select" required>
                                <option value="">Buscar estudiante...</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="detalleSancion" class="form-label">Detalle de la Sanción</label>
                            <textarea id="detalleSancion" name="detallesancion" class="form-control" rows="4" 
                                      placeholder="Describe los motivos y detalles de la sanción..."></textarea>
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

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Variables globales
let sancionesData = [];
let tiposSancionData = [];
let personasData = [];

// Inicializar la página
document.addEventListener('DOMContentLoaded', function() {
    cargarTiposSancion();
    cargarPersonas();
    cargarSanciones();
    cargarEstadisticas();
    
    // Event listeners
    const formNueva = document.getElementById('formNuevaSancion');
    if (formNueva) formNueva.addEventListener('submit', guardarSancion);
    
    // Inicializar fecha actual en el formulario
    if (document.getElementById('fechaSancion')) {
        document.getElementById('fechaSancion').valueAsDate = new Date();
    }
});

// Cargar tipos de sanción
async function cargarTiposSancion() {
    try {
        const response = await fetch('<?= base_url('sanciones/tipos') ?>', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            tiposSancionData = data.tipos || [];
            actualizarSelectTipos();
        }
    } catch (error) {
        console.error('Error al cargar tipos de sanción:', error);
        Swal.fire('Error', 'No se pudieron cargar los tipos de sanción', 'error');
    }
}

// Cargar personas (estudiantes)
async function cargarPersonas() {
    try {
        const response = await fetch('<?= base_url('usuarios/estudiantes') ?>', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            personasData = data.estudiantes || [];
            actualizarSelectPersonas();
        }
    } catch (error) {
        console.error('Error al cargar estudiantes:', error);
        Swal.fire('Error', 'No se pudieron cargar los estudiantes', 'error');
    }
}

// Cargar sanciones
async function cargarSanciones() {
    try {
        const response = await fetch('<?= base_url('sanciones/lista') ?>', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            sancionesData = data.sanciones || [];
            mostrarSanciones();
        }
    } catch (error) {
        console.error('Error al cargar sanciones:', error);
        Swal.fire('Error', 'No se pudieron cargar las sanciones', 'error');
    }
}

// Cargar estadísticas
async function cargarEstadisticas() {
    try {
        const response = await fetch('<?= base_url('sanciones/estadisticas') ?>', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            mostrarEstadisticas(data.estadisticas);
        }
    } catch (error) {
        console.error('Error al cargar estadísticas:', error);
    }
}

// Mostrar estadísticas
function mostrarEstadisticas(estadisticas) {
    document.getElementById('totalSanciones').textContent = estadisticas.total_sanciones || 0;
    document.getElementById('sancionesGraves').textContent = estadisticas.sanciones_activas || 0;
    document.getElementById('sancionesLeves').textContent = estadisticas.sanciones_levantadas || 0;
    document.getElementById('estudiantesSancionados').textContent = estadisticas.estudiantes_afectados || 0;
}

// Mostrar sanciones en la tabla
function mostrarSanciones() {
    const tbody = document.getElementById('cuerpoTablaSanciones');
    let html = '';
    
    if (sancionesData.length === 0) {
        html = `
            <tr>
                <td colspan="9" class="text-center py-4">
                    <div class="text-muted">
                        <i class="ti ti-inbox fs-1 d-block mb-2"></i>
                        No hay sanciones registradas
                    </div>
                </td>
            </tr>
        `;
    } else {
        sancionesData.forEach((sancion, index) => {
            const tipoSancion = tiposSancionData.find(t => t.idtiposancion == sancion.idtiposancion);
            const persona = personasData.find(p => p.idpersona == sancion.idpersona);
            
            html += `
                <tr>
                    <td class="text-center">${index + 1}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm bg-danger text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                <span class="small fw-bold">${(persona?.nombres?.charAt(0) || '')}${(persona?.apellidos?.charAt(0) || '')}</span>
                            </div>
                            <div>
                                <div class="fw-medium">${persona?.apellidos || ''} ${persona?.nombres || ''}</div>
                                <small class="text-muted">${persona?.email || ''}</small>
                            </div>
                        </div>
                    </td>
                    <td>${persona?.numerodoc || ''}</td>
                    <td>
                        <span class="badge bg-primary">${sancion.nivel ? `${sancion.grado}° ${sancion.nivel} ${sancion.seccion || ''}` : '—'}</span>
                    </td>
                    <td>
                        <span class="badge bg-warning text-dark">${tipoSancion?.tiposancion || 'N/A'}</span>
                    </td>
                    <td>
                        <span class="text-truncate d-inline-block" style="max-width: 200px;" 
                              title="${sancion.detallesancion || 'Sin detalle'}">
                            ${sancion.detallesancion || 'Sin detalle'}
                        </span>
                    </td>
                    <td class="text-center">${formatearFecha(sancion.created_at)}</td>
                    <td class="text-center">
                        <span class="badge bg-danger">Activa</span>
                    </td>
                    <td class="text-center">
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-outline-primary" 
                                    onclick="verDetalle(${sancion.idsancion})" title="Ver detalles">
                                <i class="ti ti-eye"></i>
                            </button>
                            <button type="button" class="btn btn-outline-warning" 
                                    onclick="editarSancion(${sancion.idsancion})" title="Editar sanción">
                                <i class="ti ti-edit"></i>
                            </button>
                            <button type="button" class="btn btn-outline-success" 
                                    onclick="levantarSancion(${sancion.idsancion})" title="Levantar sanción">
                                <i class="ti ti-check"></i>
                            </button>
                            <button type="button" class="btn btn-outline-danger" 
                                    onclick="eliminarSancion(${sancion.idsancion})" title="Eliminar sanción">
                                <i class="ti ti-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });
    }
    
    tbody.innerHTML = html;
}

// Actualizar select de tipos de sanción
function actualizarSelectTipos() {
    const select = document.getElementById('tipoSancion');
    if (select) {
        const currentValue = select.value;
        select.innerHTML = '<option value="">Seleccionar tipo...</option>';
        tiposSancionData.forEach(tipo => {
            select.innerHTML += `<option value="${tipo.idtiposancion}">${tipo.tiposancion}</option>`;
        });
        select.value = currentValue;
    }
}

// Actualizar select de personas
function actualizarSelectPersonas() {
    const select = document.getElementById('estudiante');
    if (select) {
        const currentValue = select.value;
        select.innerHTML = '<option value="">Buscar estudiante...</option>';
        personasData.forEach(persona => {
            select.innerHTML += `<option value="${persona.idpersona}">${persona.apellidos} ${persona.nombres} - ${persona.numerodoc}</option>`;
        });
        select.value = currentValue;
    }
}

// Guardar nueva sanción
async function guardarSancion(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    
    try {
        const response = await fetch('<?= base_url('sanciones/guardar') ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': getCookie('csrf_cookie_name')
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            Swal.fire({
                title: '¡Éxito!',
                text: data.message,
                icon: 'success',
                confirmButtonText: 'Aceptar'
            });
            ocultarModal('modalNuevaSancion');
            cargarSanciones();
            cargarEstadisticas();
        } else {
            Swal.fire('Error', 'Por favor, corrige los errores indicados', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        Swal.fire('Error', 'Ocurrió un error al guardar la sanción', 'error');
    }
}

// Ver detalle de sanción
async function verDetalle(id) {
    try {
        const response = await fetch(`<?= base_url('sanciones/ver/') ?>${id}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (response.ok) {
            const html = await response.text();
            document.getElementById('contenidoDetalleSancion').innerHTML = html;
            mostrarModal('modalDetalleSancion');
        } else {
            Swal.fire('Error', 'No se pudo cargar el detalle', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        Swal.fire('Error', 'Ocurrió un error al cargar los detalles', 'error');
    }
}

// Mostrar detalle de sanción
function mostrarDetalleSancion(sancion) {
    const tipoSancion = tiposSancionData.find(t => t.idtiposancion == sancion.idtiposancion);
    const persona = personasData.find(p => p.idpersona == sancion.idpersona);
    
    const html = `
        <div class="row">
            <div class="col-md-6">
                <h6 class="text-primary">Información del Estudiante</h6>
                <p><strong>Nombre:</strong> ${persona?.apellidos || ''} ${persona?.nombres || ''}</p>
                <p><strong>Documento:</strong> ${persona?.numerodoc || ''}</p>
                <p><strong>Email:</strong> ${persona?.email || 'No disponible'}</p>
            </div>
            <div class="col-md-6">
                <h6 class="text-primary">Información de la Sanción</h6>
                <p><strong>Tipo:</strong> ${tipoSancion?.tiposancion || 'N/A'}</p>
                <p><strong>Fecha:</strong> ${formatearFecha(sancion.created_at)}</p>
                <p><strong>Estado:</strong> <span class="badge bg-danger">Activa</span></p>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <h6 class="text-primary">Detalle de la Sanción</h6>
                <div class="alert alert-light">
                    ${sancion.detallesancion || 'Sin detalle disponible'}
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('contenidoDetalleSancion').innerHTML = html;
}

// Editar sanción
async function editarSancion(id) {
    Swal.fire('Info', 'Función de edición en desarrollo', 'info');
}

// Levantar sanción
async function levantarSancion(id) {
    const result = await Swal.fire({
        title: '¿Está seguro?',
        text: '¿Desea levantar esta sanción? Esta acción no se puede deshacer.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, levantar',
        cancelButtonText: 'Cancelar'
    });
    
    if (result.isConfirmed) {
        try {
            const response = await fetch(`<?= base_url('sanciones/levantar/') ?>${id}`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': getCookie('csrf_cookie_name')
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                Swal.fire({
                    title: '¡Éxito!',
                    text: data.message,
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                });
                cargarSanciones();
                cargarEstadisticas();
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            Swal.fire('Error', 'Ocurrió un error al levantar la sanción', 'error');
        }
    }
}

// Eliminar sanción
async function eliminarSancion(id) {
    const result = await Swal.fire({
        title: '¿Está seguro?',
        text: '¿Desea eliminar esta sanción? Esta acción no se puede deshacer.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    });
    
    if (result.isConfirmed) {
        try {
            const response = await fetch(`<?= base_url('sanciones/eliminar/') ?>${id}`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': getCookie('csrf_cookie_name')
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                Swal.fire({
                    title: '¡Éxito!',
                    text: data.message,
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                });
                cargarSanciones();
                cargarEstadisticas();
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            Swal.fire('Error', 'Ocurrió un error al eliminar la sanción', 'error');
        }
    }
}

// Funciones existentes actualizadas
function aplicarFiltros() {
    // Implementar filtrado de la tabla
    console.log('Aplicando filtros...');
    cargarSanciones(); // Recargar datos con filtros
}

function exportarExcel() {
    Swal.fire('Info', 'Función de exportación a Excel en desarrollo', 'info');
}

function exportarPDF() {
    Swal.fire('Info', 'Función de exportación a PDF en desarrollo', 'info');
}

// Funciones auxiliares
function formatearFecha(fecha) {
    if (!fecha) return 'N/A';
    const date = new Date(fecha);
    return date.toLocaleDateString('es-ES');
}

function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
    return '';
}

function mostrarModal(id) {
    const el = document.getElementById(id);
    if (!el) return;
    if (window.bootstrap) {
        const modal = new bootstrap.Modal(el);
        modal.show();
    } else if (window.$) {
        $(`#${id}`).modal('show');
    } else {
        el.classList.add('show');
        el.style.display = 'block';
    }
}

function ocultarModal(id) {
    const el = document.getElementById(id);
    if (!el) return;
    if (window.bootstrap) {
        const modal = bootstrap.Modal.getInstance(el) || new bootstrap.Modal(el);
        modal.hide();
    } else if (window.$) {
        $(`#${id}`).modal('hide');
    } else {
        el.classList.remove('show');
        el.style.display = 'none';
    }
}
</script>