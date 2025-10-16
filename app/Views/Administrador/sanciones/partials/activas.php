<style>
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
}
.list-group-item {
    border: none;
    border-bottom: 1px solid #dee2e6;
}
.list-group-item:last-child {
    border-bottom: none;
}
.list-group-item:hover {
    background-color: #f8f9fa;
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
                <div class="stats-icon bg-secondary text-white">
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
                    <option value="Inicial" <?= (($filtros['nivel'] ?? '') == 'Inicial') ? 'selected' : '' ?>>Inicial</option>
                    <option value="Primaria" <?= (($filtros['nivel'] ?? '') == 'Primaria') ? 'selected' : '' ?>>Primaria</option>
                    <option value="Secundaria" <?= (($filtros['nivel'] ?? '') == 'Secundaria') ? 'selected' : '' ?>>Secundaria</option>
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
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-search me-1"></i>Filtrar
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Tabla de Sanciones -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
            <i class="ti ti-shield-x me-2"></i>Sanciones Activas
        </h5>
        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalNuevaSancion">
            <i class="ti ti-plus me-1"></i>Nueva Sanción
        </button>
    </div>
    <div class="card-body">
        <?php if (empty($sanciones)): ?>
            <div class="text-center py-5">
                <i class="ti ti-shield-check text-muted" style="font-size: 3rem;"></i>
                <h5 class="text-muted mt-3">No hay sanciones activas</h5>
                <p class="text-muted">Todas las sanciones están cumplidas o no hay registros.</p>
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
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary" 
                                                onclick="verSancion(<?= $sancion['idsancion'] ?>)"
                                                title="Ver detalles">
                                            <i class="ti ti-eye"></i>
                                        </button>
                                        <button class="btn btn-outline-warning" 
                                                onclick="editarSancion(<?= $sancion['idsancion'] ?>)"
                                                title="Editar">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        <button class="btn btn-outline-success" 
                                                onclick="cambiarEstado(<?= $sancion['idsancion'] ?>, 'cumplida')"
                                                title="Marcar como cumplida">
                                            <i class="ti ti-check"></i>
                                        </button>
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
