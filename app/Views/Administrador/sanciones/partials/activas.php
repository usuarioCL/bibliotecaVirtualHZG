<!-- CSS Profesional para Sanciones -->
<link rel="stylesheet" href="<?= base_url('assets/css/sanciones-professional.css') ?>">

<?php
/**
 * Función helper para formatear tiempo relativo
 * Formatea la diferencia entre una fecha y hoy de forma legible
 */
function formatearTiempoRelativo($fecha) {
    if (empty($fecha)) {
        return '<span class="badge" style="background: #6c757d; color: white; padding: 0.5rem 0.75rem; border-radius: 8px;">Sin fecha</span>';
    }
    
    $ahora = new DateTime();
    $fechaComparar = new DateTime($fecha);
    $diferencia = $ahora->diff($fechaComparar);
    
    // Si es hoy
    if ($diferencia->days == 0) {
        return '<span class="badge" style="background: linear-gradient(135deg, #10b981, #059669); color: white; padding: 0.5rem 0.75rem; border-radius: 8px; font-weight: 600;">
                    <i class="ti ti-clock"></i> HOY
                </span>';
    }
    
    // Si fue ayer
    if ($diferencia->days == 1) {
        return '<span class="badge" style="background: linear-gradient(135deg, #3b82f6, #2563eb); color: white; padding: 0.5rem 0.75rem; border-radius: 8px; font-weight: 600;">
                    <i class="ti ti-calendar"></i> AYER
                </span>';
    }
    
    // Hace X días (2-6 días)
    if ($diferencia->days >= 2 && $diferencia->days <= 6) {
        return '<span class="badge" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed); color: white; padding: 0.5rem 0.75rem; border-radius: 8px; font-weight: 600;">
                    <i class="ti ti-calendar-time"></i> Hace ' . $diferencia->days . ' días
                </span>';
    }
    
    // Hace X semanas (7-29 días)
    if ($diferencia->days >= 7 && $diferencia->days <= 29) {
        $semanas = floor($diferencia->days / 7);
        $texto = $semanas == 1 ? '1 semana' : $semanas . ' semanas';
        return '<span class="badge" style="background: linear-gradient(135deg, #f59e0b, #d97706); color: white; padding: 0.5rem 0.75rem; border-radius: 8px; font-weight: 600;">
                    <i class="ti ti-calendar-event"></i> Hace ' . $texto . '
                </span>';
    }
    
    // Hace X meses (30-364 días)
    if ($diferencia->days >= 30 && $diferencia->days < 365) {
        $meses = floor($diferencia->days / 30);
        $texto = $meses == 1 ? '1 mes' : $meses . ' meses';
        return '<span class="badge" style="background: linear-gradient(135deg, #ef4444, #dc2626); color: white; padding: 0.5rem 0.75rem; border-radius: 8px; font-weight: 600;">
                    <i class="ti ti-calendar-stats"></i> Hace ' . $texto . '
                </span>';
    }
    
    // Hace más de un año
    $años = floor($diferencia->days / 365);
    $texto = $años == 1 ? '1 año' : $años . ' años';
    return '<span class="badge" style="background: linear-gradient(135deg, #64748b, #475569); color: white; padding: 0.5rem 0.75rem; border-radius: 8px; font-weight: 600;">
                <i class="ti ti-calendar-off"></i> Hace ' . $texto . '
            </span>';
}
?>

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

.cursor-pointer {
    cursor: pointer;
}
#resultados-busqueda {
    max-height: 200px;
    overflow-y: auto;
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    background: white;
    z-index: 1000;
    position: relative;
    box-shadow: var(--shadow-md);
}
.list-group-item {
    border: none;
    border-bottom: 1px solid #dee2e6;
    transition: all 0.3s ease;
}
.list-group-item:last-child {
    border-bottom: none;
}
.list-group-item:hover {
    background: linear-gradient(135deg, #f8fafc, #ffffff);
    transform: translateX(4px);
}
</style>

<?php if (isset($error)): ?>
    <div class="alert alert-danger" role="alert">
        <i class="ti ti-alert-circle me-2"></i>
        <?= $error ?>
    </div>
<?php endif; ?>

<!-- Estadísticas -->
<div class="row mb-4 fade-in-up">
    <div class="col-md-3">
        <div class="stats-card total">
            <div class="stats-icon">
                <i class="ti ti-shield-x"></i>
            </div>
            <div class="stats-number"><?= $estadisticas['total'] ?? 0 ?></div>
            <div class="stats-label">Total Sanciones</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card activas">
            <div class="stats-icon">
                <i class="ti ti-clock"></i>
            </div>
            <div class="stats-number"><?= $estadisticas['activas'] ?? 0 ?></div>
            <div class="stats-label">Activas</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card cumplidas">
            <div class="stats-icon">
                <i class="ti ti-check"></i>
            </div>
            <div class="stats-number"><?= $estadisticas['cumplidas'] ?? 0 ?></div>
            <div class="stats-label">Cumplidas</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card canceladas">
            <div class="stats-icon">
                <i class="ti ti-x"></i>
            </div>
            <div class="stats-number"><?= $estadisticas['canceladas'] ?? 0 ?></div>
            <div class="stats-label">Canceladas</div>
        </div>
    </div>
</div>

<!-- Filtros -->
<div class="filter-section">
    <?php 
    $filtrosActivos = !empty($filtros['tipo_sancion']) || !empty($filtros['nivel']) || !empty($filtros['buscar']);
    ?>
    <?php if ($filtrosActivos): ?>
        <div class="alert alert-info mb-3" style="border-radius: 15px; border: none; background: linear-gradient(135deg, #dbeafe, #bfdbfe);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="ti ti-filter me-2"></i>
                    <strong>Filtros activos:</strong>
                    <?php if (!empty($filtros['tipo_sancion'])): ?>
                        <span class="badge-professional badge-info-professional me-1">
                            <?= $tipos_sancion[array_search($filtros['tipo_sancion'], array_column($tipos_sancion, 'idtiposancion'))]['tiposancion'] ?? 'Tipo' ?>
                        </span>
                    <?php endif; ?>
                    <?php if (!empty($filtros['nivel'])): ?>
                        <span class="badge-professional badge-info-professional me-1">
                            <?= ucfirst($filtros['nivel']) ?>
                        </span>
                    <?php endif; ?>
                    <?php if (!empty($filtros['buscar'])): ?>
                        <span class="badge-professional badge-info-professional me-1">
                            "<?= htmlspecialchars($filtros['buscar']) ?>"
                        </span>
                    <?php endif; ?>
                </div>
                <button type="button" onclick="limpiarFiltros()" class="btn btn-sm btn-outline-secondary">
                    <i class="ti ti-x me-1"></i>Limpiar filtros
                </button>
            </div>
        </div>
    <?php endif; ?>
    
    <form method="GET" id="filtros-form">
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Tipo de Sanción</label>
                <select name="tipo_sancion" class="form-select">
                    <option value="">Todos los tipos</option>
                    <?php foreach ($tipos_sancion as $tipo): ?>
                        <option value="<?= $tipo['idtiposancion'] ?>" 
                                <?= (($filtros['tipo_sancion'] ?? '') == $tipo['idtiposancion']) ? 'selected' : '' ?>>
                            <?= $tipo['tiposancion'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Nivel Educativo</label>
                <select name="nivel" class="form-select">
                    <option value="">Todos los niveles</option>
                    <?php if (isset($niveles_educativos) && !empty($niveles_educativos)): ?>
                        <?php foreach ($niveles_educativos as $nivel): ?>
                            <option value="<?= $nivel ?>" 
                                    <?= (($filtros['nivel'] ?? '') == $nivel) ? 'selected' : '' ?>>
                                <?= ucfirst($nivel) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="Inicial" <?= (($filtros['nivel'] ?? '') == 'Inicial') ? 'selected' : '' ?>>Inicial</option>
                        <option value="Primaria" <?= (($filtros['nivel'] ?? '') == 'Primaria') ? 'selected' : '' ?>>Primaria</option>
                        <option value="Secundaria" <?= (($filtros['nivel'] ?? '') == 'Secundaria') ? 'selected' : '' ?>>Secundaria</option>
                    <?php endif; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Buscar Estudiante</label>
                <input type="text" name="buscar" class="form-control" 
                       placeholder="Nombre, apellido o DNI..." 
                       value="<?= $filtros['buscar'] ?? '' ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <div class="d-grid gap-2">
                    <button type="button" onclick="aplicarFiltros()" class="btn btn-primary-professional btn-professional">
                        <i class="ti ti-search me-1"></i>Filtrar
                    </button>
                    <button type="button" onclick="limpiarFiltros()" class="btn btn-secondary btn-professional">
                        <i class="ti ti-x me-1"></i>Limpiar
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Tabla de Sanciones -->
<div class="card" style="border: none; box-shadow: var(--shadow-lg); border-radius: 20px;">
    <div class="card-header-professional d-flex justify-content-between align-items-center">
        <h5 class="card-title-professional">
            <i class="ti ti-shield-x"></i>Sanciones Activas
        </h5>
        <button class="btn btn-success-professional btn-professional" data-bs-toggle="modal" data-bs-target="#modalNuevaSancion">
            <i class="ti ti-plus me-1"></i>Nueva Sanción
        </button>
    </div>
    <div class="card-body" style="padding: 0;">
        <?php if (empty($sanciones)): ?>
            <div class="text-center py-5">
                <i class="ti ti-shield-check text-muted" style="font-size: 3rem;"></i>
                <h5 class="text-muted mt-3">No hay sanciones activas</h5>
                <p class="text-muted">Todas las sanciones están cumplidas o no hay registros.</p>
            </div>
        <?php else: ?>
            <div class="table-container-professional">
                <div class="table-responsive">
                    <table class="table-professional">
                    <thead>
                            <tr>
                                <th>Persona</th>
                                <th>Última Acción</th>
                                <th>Fecha Más Reciente</th>
                                <th>Vencimiento Próximo</th>
                                <th>Estado</th>
                                <th>Total Sanciones</th>
                                <th>Detalles</th>
                                <th>Acciones</th>
                            </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sanciones as $sancion): ?>
                            <tr>
                                <td>
                                    <div class="person-info">
                                        <div class="person-name"><?= $sancion['nombre_completo'] ?? 'N/A' ?></div>
                                        <div class="person-details">
                                            <span><?= $sancion['tipodoc'] ?? 'Doc' ?>: <?= $sancion['numerodoc'] ?? 'N/A' ?></span>
                                            <?php if (!empty($sancion['email'])): ?>
                                                <span><i class="ti ti-mail"></i><?= $sancion['email'] ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <?= formatearTiempoRelativo($sancion['ultima_actualizacion'] ?? $sancion['fecha_sancion_reciente']) ?>
                                </td>
                                <td><?= $sancion['fecha_sancion_reciente'] ?? 'N/A' ?></td>
                                <td><?= $sancion['fecha_vencimiento_proxima'] ?? 'Sin fecha' ?></td>
                                <td>
                                    <span class="sanction-status status-activa">
                                        Activa
                                    </span>
                                </td>
                                <td>
                                    <div class="text-center">
                                        <span class="badge-professional badge-danger-professional" style="font-size: 1rem; padding: 0.5rem 0.75rem; font-weight: bold;">
                                            <?= $sancion['total_sanciones_persona'] ?? 0 ?>
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-center">
                                        <button class="btn-action view" 
                                                onclick="verDetallesPersona(<?= $sancion['idpersona'] ?>)"
                                                title="Ver todas las sanciones de esta persona">
                                            <i class="ti ti-list-details"></i>
                                        </button>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group-professional">
                                        <button class="btn-action view" 
                                                onclick="verDetallesPersona(<?= $sancion['idpersona'] ?>)"
                                                title="Ver detalles individuales">
                                            <i class="ti ti-eye"></i>
                                        </button>
                                        <button class="btn-action complete" 
                                                onclick="levantarTodasSanciones(<?= $sancion['idpersona'] ?>, '<?= addslashes($sancion['nombre_completo']) ?>', <?= $sancion['total_sanciones_persona'] ?>)"
                                                title="Levantar todas las sanciones (<?= $sancion['total_sanciones_persona'] ?>)">
                                            <i class="ti ti-check"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Levantamiento de Sanción -->
<div class="modal fade" id="modalLevantamiento" tabindex="-1" aria-labelledby="modalLevantamientoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: var(--shadow-xl);">
            <div class="modal-header" style="background: linear-gradient(135deg, var(--success-color), #047857); color: white; border-radius: 20px 20px 0 0;">
                <h5 class="modal-title" id="modalLevantamientoLabel">
                    <i class="ti ti-check-circle me-2"></i>Levantar Sanción
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 2rem;">
                <div id="levantamiento-content">
                    <div class="text-center py-4">
                        <div class="spinner-professional"></div>
                        <p class="mt-2">Cargando detalles de la sanción...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detalles de Persona -->
<div class="modal fade" id="modalDetallesPersona" tabindex="-1" aria-labelledby="modalDetallesPersonaLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" style="max-width: 1400px; margin: 1.75rem 3rem 1.75rem auto;">
        <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: var(--shadow-xl);">
            <div class="modal-header" style="background: linear-gradient(135deg, var(--primary-color), #1e40af); color: white; border-radius: 20px 20px 0 0;">
                <h5 class="modal-title" id="modalDetallesPersonaLabel">
                    <i class="ti ti-user me-2"></i>Detalles de Sanciones
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 1.5rem; max-height: 70vh; overflow-y: auto;">
                <div id="detalles-persona-content">
                    <div class="text-center py-4">
                        <div class="spinner-professional"></div>
                        <p class="mt-2">Cargando detalles...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border: none; padding: 1rem 2rem 2rem;">
                <button type="button" class="btn btn-secondary btn-professional" data-bs-dismiss="modal">
                    <i class="ti ti-x me-1"></i>Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nueva Sanción -->
<div class="modal fade" id="modalNuevaSancion" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ti ti-plus me-2"></i>Nueva Sanción
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formNuevaSancion">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tipo de Sanción *</label>
                            <select name="idtiposancion" class="form-select" required>
                                <option value="">Seleccionar tipo</option>
                                <?php foreach ($tipos_sancion as $tipo): ?>
                                    <option value="<?= $tipo['idtiposancion'] ?>">
                                        <?= $tipo['tiposancion'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Persona *</label>
                            <input type="text" name="persona_buscar" class="form-control" 
                                   placeholder="Buscar por nombre, apellido o documento..." 
                                   autocomplete="off">
                            <input type="hidden" name="idpersona" required>
                            <div id="resultados-busqueda" class="mt-2"></div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Detalles de la Sanción *</label>
                            <textarea name="detallesancion" class="form-control" rows="3" 
                                      placeholder="Describe los detalles de la sanción..." required></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fecha de Sanción</label>
                            <input type="date" name="fecha_sancion" class="form-control" 
                                   value="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fecha de Vencimiento</label>
                            <input type="date" name="fecha_vencimiento" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Observaciones</label>
                            <textarea name="observaciones" class="form-control" rows="2" 
                                      placeholder="Observaciones adicionales..."></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardarSancion()">
                    <i class="ti ti-device-floppy me-1"></i>Guardar Sanción
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Funciones JavaScript para manejar las acciones
function verSancion(id) {
    // Implementar vista de detalles
    console.log('Ver sanción:', id);
}

function editarSancion(id) {
    // Implementar edición
    console.log('Editar sanción:', id);
}

function cambiarEstado(id, estado) {
    // Implementar cambio de estado
    console.log('Cambiar estado:', id, estado);
}

function guardarSancion() {
    // Verificar que jQuery esté disponible
    if (typeof $ === 'undefined') {
        console.error('jQuery no está disponible');
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'jQuery no está cargado. Recarga la página.',
            confirmButtonColor: '#dc3545'
        });
        return;
    }
    
    const form = document.getElementById('formNuevaSancion');
    const formData = new FormData(form);
    
    // Debug: mostrar datos que se van a enviar
    console.log('Datos del formulario:');
    for (let [key, value] of formData.entries()) {
        console.log(key + ': ' + value);
    }
    
    // Validar campos requeridos
    const idtiposancion = formData.get('idtiposancion');
    const idpersona = formData.get('idpersona');
    const detallesancion = formData.get('detallesancion');
    
    console.log('Validación - Tipo:', idtiposancion);
    console.log('Validación - Persona:', idpersona);
    console.log('Validación - Detalles:', detallesancion);
    
    if (!idtiposancion || !idpersona || !detallesancion) {
        Swal.fire({
            icon: 'warning',
            title: 'Campos Requeridos',
            text: 'Por favor completa todos los campos obligatorios',
            confirmButtonColor: '#dc3545'
        });
        return;
    }
    
    // Mostrar loading
    Swal.fire({
        title: 'Guardando...',
        text: 'Por favor espera',
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Enviar datos usando jQuery (más compatible)
    const url = '<?= base_url('sanciones/guardar') ?>';
    console.log('Enviando a URL:', url);
    console.log('Método: POST');
    
    // Convertir FormData a objeto para jQuery
    const formObject = {};
    for (let [key, value] of formData.entries()) {
        formObject[key] = value;
    }
    console.log('Objeto a enviar:', formObject);
    
    $.ajax({
        url: url,
        type: 'POST',
        data: formObject,
        dataType: 'json',
        beforeSend: function(xhr) {
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        },
        success: function(data) {
            Swal.close();
            console.log('Respuesta exitosa:', data);
            
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: data.message,
                    confirmButtonColor: '#198754'
                }).then(() => {
                    // Cerrar modal y recargar contenido
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalNuevaSancion'));
                    modal.hide();
                    
                    // Limpiar formulario
                    form.reset();
                    document.getElementById('resultados-busqueda').innerHTML = '';
                    
                    // Recargar solo el contenido de sanciones
                    recargarContenidoSanciones();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message,
                    confirmButtonColor: '#dc3545'
                });
            }
        },
        error: function(xhr, status, error) {
            Swal.close();
            console.error('Error AJAX:', xhr, status, error);
            console.log('Respuesta del servidor:', xhr.responseText);
            
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al comunicarse con el servidor: ' + error,
                confirmButtonColor: '#dc3545'
            });
        }
    });
}

// Función para recargar solo el contenido de sanciones
function recargarContenidoSanciones() {
    // Buscar el contenedor principal del contenido
    const contenedorPrincipal = document.querySelector('#contenedor-principal') || 
                                document.querySelector('.body-wrapper-inner') ||
                                document.querySelector('#main-wrapper') || 
                                document.querySelector('#main-content') || 
                                document.querySelector('.main-content') || 
                                document.querySelector('#content') ||
                                document.querySelector('.content');
    
    if (contenedorPrincipal) {
        // Mostrar loading
        contenedorPrincipal.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando...</span></div><p class="mt-2">Actualizando sanciones...</p></div>';
        
        // Recargar contenido vía AJAX
        fetch('<?= base_url('sanciones') ?>', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            contenedorPrincipal.innerHTML = html;
        })
        .catch(error => {
            console.error('Error al recargar contenido:', error);
            contenedorPrincipal.innerHTML = '<div class="alert alert-danger">Error al actualizar el contenido. <a href="javascript:location.reload()">Recargar página</a></div>';
        });
    } else {
        // Si no encuentra el contenedor, recargar toda la página como fallback
        console.warn('No se encontró el contenedor principal, recargando toda la página');
        location.reload();
    }
}

// Función para ver detalles de todas las sanciones de una persona
function verDetallesPersona(idpersona) {
    const modal = new bootstrap.Modal(document.getElementById('modalDetallesPersona'));
    modal.show();
    
    // Mostrar loading
    document.getElementById('detalles-persona-content').innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-professional"></div>
            <p class="mt-2">Cargando detalles...</p>
        </div>
    `;
    
    // Obtener datos de la persona (solo sanciones activas)
    fetch(`<?= base_url('sanciones/persona') ?>/${idpersona}?estado=activa`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.sanciones.length > 0) {
            const persona = data.sanciones[0];
            let html = `
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="card" style="border: none; box-shadow: var(--shadow-md); border-radius: 12px; border-left: 4px solid #dc3545;">
                            <div class="card-body" style="padding: 1.25rem;">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0"><i class="ti ti-user-circle me-2" style="color: #dc3545;"></i>${persona.nombre_completo}</h5>
                                    <span class="badge bg-danger" style="font-size: 0.9rem; padding: 0.5rem 0.75rem;">${data.sanciones.length} sanción${data.sanciones.length !== 1 ? 'es' : ''} activa${data.sanciones.length !== 1 ? 's' : ''}</span>
                                </div>
                                <div class="row g-3" style="font-size: 0.85rem;">
                                    <div class="col-md-4">
                                        <div class="d-flex align-items-center">
                                            <i class="ti ti-id me-2 text-primary"></i>
                                            <div>
                                                <small class="text-muted d-block">Documento</small>
                                                <strong>${persona.tipodoc}: ${persona.numerodoc}</strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="d-flex align-items-center">
                                            <i class="ti ti-mail me-2 text-primary"></i>
                                            <div>
                                                <small class="text-muted d-block">Email</small>
                                                <strong>${persona.email || 'No disponible'}</strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="d-flex align-items-center">
                                            <i class="ti ti-phone me-2 text-primary"></i>
                                            <div>
                                                <small class="text-muted d-block">Teléfono</small>
                                                <strong>${persona.telefono || 'No disponible'}</strong>
                                            </div>
                                        </div>
                                    </div>
                                    ${persona.grado || persona.nivel ? `
                                    <div class="col-md-12">
                                        <div class="alert alert-info mb-0" style="padding: 0.5rem 1rem; background: #e3f2fd; border: none; border-radius: 8px;">
                                            <i class="ti ti-school me-2"></i>
                                            <strong>Nivel Educativo:</strong> ${persona.nivel ? persona.nivel.toUpperCase() : ''} 
                                            ${persona.grado ? '- Grado: ' + persona.grado : ''} 
                                            ${persona.seccion ? 'Sección: ' + persona.seccion : ''}
                                        </div>
                                    </div>
                                    ` : ''}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h6 class="mb-3"><i class="ti ti-shield-x me-2"></i>Detalles de las sanciones (${data.sanciones.length})</h6>
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" style="border-radius: 15px; overflow: hidden; box-shadow: var(--shadow-sm); font-size: 0.85rem; table-layout: fixed; width: 100%;">
                                <thead style="background: linear-gradient(135deg, var(--secondary-color), #6b7280); color: white;">
                                    <tr>
                                        <th style="width: 20%;">Tipo</th>
                                        <th style="width: 28%;">Detalles</th>
                                        <th style="width: 12%;">Fecha Sanción</th>
                                        <th style="width: 12%;">Vencimiento</th>
                                        <th style="width: 10%;">Estado</th>
                                        <th style="width: 18%; text-align: center;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
            `;
            
            data.sanciones.forEach(sancion => {
                const estadoClass = sancion.estado_sancion === 'activa' ? 'status-activa' : 
                                  sancion.estado_sancion === 'cumplida' ? 'status-cumplida' : 'status-cancelada';
                
                const accionesHtml = sancion.estado_sancion === 'activa' ? `
                    <button class="btn btn-sm btn-warning" onclick="abrirModalLevantamiento(${sancion.idsancion})" title="Levantar sanción">
                        <i class="ti ti-check"></i>
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="eliminarSancion(${sancion.idsancion})" title="Eliminar">
                        <i class="ti ti-trash"></i>
                    </button>
                ` : `
                    <span class="text-muted">-</span>
                `;
                
                html += `
                    <tr>
                        <td style="vertical-align: middle; padding: 0.5rem;">
                            <span class="badge-professional badge-info-professional" style="font-size: 0.75rem; white-space: normal; display: inline-block; max-width: 100%; line-height: 1.3;">
                                ${sancion.tiposancion}
                            </span>
                        </td>
                        <td style="vertical-align: middle; word-wrap: break-word; padding: 0.5rem; overflow: hidden;">${sancion.detallesancion}</td>
                        <td style="vertical-align: middle; white-space: nowrap; padding: 0.5rem;">${sancion.fecha_sancion}</td>
                        <td style="vertical-align: middle; white-space: nowrap; padding: 0.5rem;">${sancion.fecha_vencimiento || 'Sin fecha'}</td>
                        <td style="vertical-align: middle; padding: 0.5rem;"><span class="sanction-status ${estadoClass}" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">${sancion.estado_sancion.toUpperCase()}</span></td>
                        <td style="vertical-align: middle; text-align: center; padding: 0.5rem;">${accionesHtml}</td>
                    </tr>
                `;
            });
            
            html += `
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            `;
            
            document.getElementById('detalles-persona-content').innerHTML = html;
        } else {
            document.getElementById('detalles-persona-content').innerHTML = `
                <div class="text-center py-4">
                    <i class="ti ti-shield-check text-success" style="font-size: 3rem;"></i>
                    <h5 class="text-success mt-3">Sin Sanciones Activas</h5>
                    <p class="text-muted">Esta persona no tiene sanciones activas en este momento.</p>
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Error al cargar detalles:', error);
        document.getElementById('detalles-persona-content').innerHTML = `
            <div class="text-center py-4">
                <i class="ti ti-alert-circle text-danger" style="font-size: 3rem;"></i>
                <h5 class="text-danger mt-3">Error al cargar detalles</h5>
                <p class="text-muted">No se pudieron cargar las sanciones de esta persona.</p>
            </div>
        `;
    });
}

// Función para abrir modal de levantamiento
function abrirModalLevantamiento(idsancion) {
    // Cerrar el modal de detalles de persona si está abierto
    const modalDetallesPersona = bootstrap.Modal.getInstance(document.getElementById('modalDetallesPersona'));
    if (modalDetallesPersona) {
        modalDetallesPersona.hide();
    }
    
    // Esperar un momento para que se cierre el modal anterior
    setTimeout(() => {
        const modal = new bootstrap.Modal(document.getElementById('modalLevantamiento'));
        modal.show();
        
        // Mostrar loading
        document.getElementById('levantamiento-content').innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-professional"></div>
                <p class="mt-2">Cargando detalles de la sanción...</p>
            </div>
        `;
        
        // Obtener detalles de la sanción
        fetch(`<?= base_url('sanciones/detalles-levantamiento') ?>/${idsancion}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
            const sancion = data.sancion;
            const fechaActual = new Date().toLocaleDateString('es-ES');
            
            document.getElementById('levantamiento-content').innerHTML = `
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="alert alert-info" style="border-radius: 15px; border: none;">
                            <h6 class="mb-2"><i class="ti ti-info-circle me-2"></i>Información de la Sanción</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Persona:</strong> ${sancion.nombre_completo}</p>
                                    <p class="mb-1"><strong>Tipo:</strong> ${sancion.tiposancion}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Fecha Sanción:</strong> ${sancion.fecha_sancion}</p>
                                    <p class="mb-1"><strong>Vencimiento:</strong> ${sancion.fecha_vencimiento || 'Sin fecha'}</p>
                                </div>
                            </div>
                            <p class="mb-0"><strong>Detalles:</strong> ${sancion.detallesancion}</p>
                        </div>
                    </div>
                </div>
                
                <form id="formLevantamiento">
                    <input type="hidden" name="idsancion" value="${idsancion}">
                    
                    <div class="mb-4">
                        <label for="motivo_levantamiento" class="form-label">
                            <i class="ti ti-edit me-2"></i>Motivo del Levantamiento
                        </label>
                        <textarea class="form-control" 
                                  id="motivo_levantamiento" 
                                  name="motivo_levantamiento" 
                                  rows="4" 
                                  placeholder="Describe el motivo por el cual se levanta esta sanción antes de tiempo..."
                                  style="border-radius: 15px; border: 2px solid var(--border-color);"
                                  required></textarea>
                        <div class="form-text">Este motivo quedará registrado en el historial de la sanción.</div>
                    </div>
                    
                    <div class="mb-4">
                        <div class="alert alert-warning" style="border-radius: 15px; border: none;">
                            <h6 class="mb-2"><i class="ti ti-alert-triangle me-2"></i>Confirmación</h6>
                            <p class="mb-0">Al levantar esta sanción, se marcará como <strong>"cumplida"</strong> y la fecha de vencimiento se establecerá como <strong>${fechaActual}</strong>.</p>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary btn-professional" data-bs-dismiss="modal">
                            <i class="ti ti-x me-1"></i>Cancelar
                        </button>
                        <button type="submit" class="btn btn-success-professional btn-professional">
                            <i class="ti ti-check me-1"></i>Levantar Sanción
                        </button>
                    </div>
                </form>
            `;
            
            // Agregar evento al formulario
            document.getElementById('formLevantamiento').addEventListener('submit', function(e) {
                e.preventDefault();
                levantarSancion(idsancion);
            });
        } else {
            document.getElementById('levantamiento-content').innerHTML = `
                <div class="text-center py-4">
                    <i class="ti ti-alert-circle text-danger" style="font-size: 3rem;"></i>
                    <h5 class="text-danger mt-3">Error</h5>
                    <p class="text-muted">${data.message}</p>
                    <button type="button" class="btn btn-secondary btn-professional" data-bs-dismiss="modal">
                        <i class="ti ti-x me-1"></i>Cerrar
                    </button>
                </div>
            `;
        }
    })
        .catch(error => {
            console.error('Error al cargar detalles:', error);
            document.getElementById('levantamiento-content').innerHTML = `
                <div class="text-center py-4">
                    <i class="ti ti-alert-circle text-danger" style="font-size: 3rem;"></i>
                    <h5 class="text-danger mt-3">Error al cargar detalles</h5>
                    <p class="text-muted">No se pudieron cargar los detalles de la sanción.</p>
                    <button type="button" class="btn btn-secondary btn-professional" data-bs-dismiss="modal">
                        <i class="ti ti-x me-1"></i>Cerrar
                    </button>
                </div>
            `;
        });
    }, 300);
}

// Función para levantar TODAS las sanciones de una persona
function levantarTodasSanciones(idpersona, nombreCompleto, totalSanciones) {
    Swal.fire({
        title: '⚠️ Levantar Todas las Sanciones',
        html: `
            <div class="text-start">
                <p><strong>Persona:</strong> ${nombreCompleto}</p>
                <p><strong>Total de sanciones activas:</strong> <span class="badge bg-danger">${totalSanciones}</span></p>
                <hr>
                <p class="text-muted">Esta acción levantará TODAS las sanciones activas de esta persona.</p>
                <label for="motivo-todas" class="form-label mt-3">
                    <i class="ti ti-edit me-2"></i><strong>Motivo del levantamiento:</strong>
                </label>
                <textarea id="motivo-todas" class="form-control" rows="4" 
                          placeholder="Describe el motivo por el cual se levantan todas las sanciones..."
                          style="border-radius: 10px; border: 2px solid #e5e7eb;"></textarea>
            </div>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: `<i class="ti ti-check me-1"></i>Sí, levantar todas (${totalSanciones})`,
        cancelButtonText: '<i class="ti ti-x me-1"></i>Cancelar',
        confirmButtonColor: '#059669',
        cancelButtonColor: '#6b7280',
        width: '600px',
        preConfirm: () => {
            const motivo = document.getElementById('motivo-todas').value;
            if (!motivo.trim()) {
                Swal.showValidationMessage('Por favor, describe el motivo del levantamiento');
                return false;
            }
            return motivo;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const motivo = result.value;
            
            // Mostrar loading
            Swal.fire({
                title: 'Levantando sanciones...',
                html: 'Por favor espera mientras se procesan todas las sanciones.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Hacer petición al servidor
            fetch('<?= base_url('sanciones/levantar-todas') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    idpersona: idpersona,
                    motivo_levantamiento: motivo
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Sanciones levantadas!',
                        html: `
                            <p>Se han levantado exitosamente <strong>${data.total_levantadas || totalSanciones}</strong> sanción(es).</p>
                            <p class="text-muted">Las sanciones han sido marcadas como canceladas.</p>
                        `,
                        confirmButtonColor: '#059669',
                        confirmButtonText: 'Entendido'
                    }).then(() => {
                        // Recargar solo el contenido de sanciones activas sin salir de la vista
                        const contenedorPrincipal = document.querySelector('#contenedor-principal') || 
                                                    document.querySelector('.body-wrapper-inner') ||
                                                    document.querySelector('#main-wrapper');
                        
                        if (contenedorPrincipal) {
                            contenedorPrincipal.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando...</span></div></div>';
                            
                            fetch('<?= base_url('sanciones') ?>', {
                                method: 'GET',
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            })
                            .then(response => response.text())
                            .then(html => {
                                contenedorPrincipal.innerHTML = html;
                            })
                            .catch(error => {
                                console.error('Error al recargar:', error);
                                location.reload(); // Fallback si falla la recarga AJAX
                            });
                        } else {
                            location.reload(); // Fallback si no encuentra el contenedor
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'No se pudieron levantar las sanciones',
                        confirmButtonColor: '#dc2626'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error de conexión',
                    text: 'No se pudo conectar con el servidor',
                    confirmButtonColor: '#dc2626'
                });
            });
        }
    });
}

// Función para levantar la sanción
function levantarSancion(idsancion) {
    const form = document.getElementById('formLevantamiento');
    const formData = new FormData(form);
    
    const motivoLevantamiento = formData.get('motivo_levantamiento');
    
    if (!motivoLevantamiento.trim()) {
        Swal.fire({
            icon: 'warning',
            title: 'Motivo requerido',
            text: 'Por favor, describe el motivo del levantamiento de la sanción.',
            confirmButtonColor: '#d97706'
        });
        return;
    }
    
    Swal.fire({
        title: '¿Confirmar levantamiento?',
        text: 'Esta acción marcará la sanción como cumplida y no se puede deshacer.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#059669',
        cancelButtonColor: '#dc2626',
        confirmButtonText: 'Sí, levantar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Mostrar loading
            Swal.fire({
                title: 'Procesando...',
                text: 'Levantando la sanción',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Enviar petición
            fetch('<?= base_url('sanciones/levantar') ?>', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams(formData)
            })
            .then(response => response.json())
            .then(data => {
                Swal.close();
                
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Sanción levantada!',
                        text: data.message,
                        confirmButtonColor: '#059669'
                    }).then(() => {
                        // Cerrar modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('modalLevantamiento'));
                        modal.hide();
                        
                        // Recargar contenido
                        recargarContenidoSanciones();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message,
                        confirmButtonColor: '#dc2626'
                    });
                }
            })
            .catch(error => {
                Swal.close();
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al comunicarse con el servidor',
                    confirmButtonColor: '#dc2626'
                });
            });
        }
    });
}

// Función para aplicar filtros dinámicamente
function aplicarFiltros() {
    const form = document.getElementById('filtros-form');
    const formData = new FormData(form);
    
    // Construir URL con parámetros
    const params = new URLSearchParams();
    for (let [key, value] of formData.entries()) {
        if (value.trim() !== '') {
            params.append(key, value);
        }
    }
    
    const url = `<?= base_url('sanciones') ?>?${params.toString()}`;
    
    // Mostrar loading
    const contenedorPrincipal = document.querySelector('#contenedor-principal') || 
                                document.querySelector('.body-wrapper-inner') ||
                                document.querySelector('#main-wrapper');
    
    if (contenedorPrincipal) {
        contenedorPrincipal.innerHTML = '<div class="text-center py-5"><div class="spinner-professional"></div><p class="mt-2">Aplicando filtros...</p></div>';
        
        // Recargar contenido con filtros
        fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            contenedorPrincipal.innerHTML = html;
        })
        .catch(error => {
            console.error('Error al aplicar filtros:', error);
            contenedorPrincipal.innerHTML = '<div class="alert alert-danger">Error al aplicar filtros. <a href="javascript:location.reload()">Recargar página</a></div>';
        });
    }
}

// Función para limpiar filtros
function limpiarFiltros() {
    const form = document.getElementById('filtros-form');
    form.reset();
    
    // Recargar sin filtros
    const url = `<?= base_url('sanciones') ?>`;
    
    const contenedorPrincipal = document.querySelector('#contenedor-principal') || 
                                document.querySelector('.body-wrapper-inner') ||
                                document.querySelector('#main-wrapper');
    
    if (contenedorPrincipal) {
        contenedorPrincipal.innerHTML = '<div class="text-center py-5"><div class="spinner-professional"></div><p class="mt-2">Limpiando filtros...</p></div>';
        
        fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            contenedorPrincipal.innerHTML = html;
        })
        .catch(error => {
            console.error('Error al limpiar filtros:', error);
            contenedorPrincipal.innerHTML = '<div class="alert alert-danger">Error al limpiar filtros. <a href="javascript:location.reload()">Recargar página</a></div>';
        });
    }
}

// Eventos para filtros automáticos
document.addEventListener('DOMContentLoaded', function() {
    // Aplicar filtros automáticamente cuando cambien los selects
    const selects = document.querySelectorAll('select[name="tipo_sancion"], select[name="nivel"]');
    selects.forEach(select => {
        select.addEventListener('change', function() {
            // Pequeño delay para mejor UX
            setTimeout(() => {
                aplicarFiltros();
            }, 300);
        });
    });
    
    // Aplicar filtros cuando se escriba en el campo de búsqueda (con debounce)
    let searchTimeout;
    const searchInput = document.querySelector('input[name="buscar"]');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                if (this.value.length >= 2 || this.value.length === 0) {
                    aplicarFiltros();
                }
            }, 500);
        });
    }
});

// Búsqueda de personas
document.querySelector('input[name="persona_buscar"]').addEventListener('input', function(e) {
    const query = e.target.value;
    if (query.length >= 2) {
        // Implementar búsqueda AJAX de personas
        fetch(`<?= base_url('sanciones/buscar-personas') ?>?q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                const resultados = document.getElementById('resultados-busqueda');
                resultados.innerHTML = '';
                
                if (data.length > 0) {
                    data.forEach(persona => {
                        const div = document.createElement('div');
                        div.className = 'list-group-item list-group-item-action cursor-pointer';
                        div.innerHTML = `
                            <strong>${persona.text}</strong>
                            <small class="text-muted d-block">${persona.documento || ''}</small>
                        `;
                        div.onclick = () => {
                            document.querySelector('input[name="idpersona"]').value = persona.id;
                            document.querySelector('input[name="persona_buscar"]').value = persona.text;
                            resultados.innerHTML = '';
                        };
                        resultados.appendChild(div);
                    });
                } else {
                    resultados.innerHTML = '<div class="text-muted">No se encontraron personas</div>';
                }
            })
            .catch(error => {
                console.error('Error en búsqueda:', error);
                document.getElementById('resultados-busqueda').innerHTML = '<div class="text-danger">Error en la búsqueda</div>';
            });
    } else {
        document.getElementById('resultados-busqueda').innerHTML = '';
    }
});
</script>
