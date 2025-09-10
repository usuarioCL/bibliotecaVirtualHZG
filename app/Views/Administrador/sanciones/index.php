<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="ti ti-ban me-2"></i>Gestión de Sanciones
                    </h4>
                    <div class="d-flex gap-2">
                        <a href="<?= base_url('sanciones/tipos') ?>" class="btn btn-outline-secondary">
                            <i class="ti ti-settings me-1"></i>Tipos de Sanción
                        </a>
                        <a href="<?= base_url('sanciones/crear') ?>" class="btn btn-primary ajax-link">
                            <i class="ti ti-plus me-1"></i>Nueva Sanción
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Buscador -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" id="buscarSancion" class="form-control" 
                                       placeholder="Buscar por nombre, documento, tipo de sanción...">
                                <button class="btn btn-outline-secondary" type="button" id="btnBuscar">
                                    <i class="ti ti-search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            <span class="text-muted">Total: <strong><?= count($sanciones) ?></strong> sanciones</span>
                        </div>
                    </div>

                    <!-- Tabla de sanciones -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="tablaSanciones">
                            <thead class="table-dark">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="25%">Persona</th>
                                    <th width="15%">Documento</th>
                                    <th width="20%">Tipo de Sanción</th>
                                    <th width="25%">Detalle</th>
                                    <th width="10%">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($sanciones)): ?>
                                    <?php foreach ($sanciones as $index => $sancion): ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td>
                                                <strong><?= esc($sancion['apellidos']) ?>, <?= esc($sancion['nombres']) ?></strong>
                                            </td>
                                            <td><?= esc($sancion['numerodoc']) ?></td>
                                            <td>
                                                <span class="badge bg-warning text-dark">
                                                    <?= esc($sancion['tiposancion']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if (!empty($sancion['detallesancion'])): ?>
                                                    <span class="text-truncate d-inline-block" style="max-width: 200px;" 
                                                          title="<?= esc($sancion['detallesancion']) ?>">
                                                        <?= esc($sancion['detallesancion']) ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-muted">Sin detalles</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <button type="button" class="btn btn-outline-info" 
                                                            onclick="verSancion(<?= $sancion['idsancion'] ?>)" 
                                                            title="Ver detalles">
                                                        <i class="ti ti-eye"></i>
                                                    </button>
                                                    <a href="<?= base_url('sanciones/editar/' . $sancion['idsancion']) ?>" 
                                                       class="btn btn-outline-warning ajax-link" title="Editar">
                                                        <i class="ti ti-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-outline-danger" 
                                                            onclick="eliminarSancion(<?= $sancion['idsancion'] ?>)" 
                                                            title="Eliminar">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="ti ti-ban fs-1 d-block mb-2"></i>
                                                No hay sanciones registradas
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para ver detalles -->
<div class="modal fade" id="modalVerSancion" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ti ti-eye me-2"></i>Detalles de la Sanción
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="contenidoModalVer">
                <!-- Contenido cargado via AJAX -->
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Búsqueda en tiempo real
    const inputBuscar = document.getElementById('buscarSancion');
    const btnBuscar = document.getElementById('btnBuscar');
    
    let timeoutBusqueda;
    
    inputBuscar.addEventListener('input', function() {
        clearTimeout(timeoutBusqueda);
        timeoutBusqueda = setTimeout(() => {
            buscarSanciones();
        }, 500);
    });
    
    btnBuscar.addEventListener('click', buscarSanciones);
    
    inputBuscar.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            buscarSanciones();
        }
    });
});

function buscarSanciones() {
    const criterio = document.getElementById('buscarSancion').value;
    const url = `<?= base_url('sanciones/buscar') ?>?q=${encodeURIComponent(criterio)}`;
    
    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            actualizarTablaSanciones(data.data);
        }
    })
    .catch(error => {
        console.error('Error en la búsqueda:', error);
    });
}

function actualizarTablaSanciones(sanciones) {
    const tbody = document.querySelector('#tablaSanciones tbody');
    
    if (sanciones.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center py-4">
                    <div class="text-muted">
                        <i class="ti ti-search fs-1 d-block mb-2"></i>
                        No se encontraron sanciones
                    </div>
                </td>
            </tr>
        `;
        return;
    }
    
    let html = '';
    sanciones.forEach((sancion, index) => {
        const detalle = sancion.detallesancion ? 
            `<span class="text-truncate d-inline-block" style="max-width: 200px;" title="${sancion.detallesancion}">${sancion.detallesancion}</span>` :
            '<span class="text-muted">Sin detalles</span>';
            
        html += `
            <tr>
                <td>${index + 1}</td>
                <td><strong>${sancion.apellidos}, ${sancion.nombres}</strong></td>
                <td>${sancion.numerodoc}</td>
                <td><span class="badge bg-warning text-dark">${sancion.tiposancion}</span></td>
                <td>${detalle}</td>
                <td>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-info" onclick="verSancion(${sancion.idsancion})" title="Ver detalles">
                            <i class="ti ti-eye"></i>
                        </button>
                        <a href="<?= base_url('sanciones/editar/') ?>${sancion.idsancion}" class="btn btn-outline-warning ajax-link" title="Editar">
                            <i class="ti ti-edit"></i>
                        </a>
                        <button type="button" class="btn btn-outline-danger" onclick="eliminarSancion(${sancion.idsancion})" title="Eliminar">
                            <i class="ti ti-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });
    
    tbody.innerHTML = html;
}

function verSancion(idsancion) {
    fetch(`<?= base_url('sanciones/ver/') ?>${idsancion}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(html => {
        document.getElementById('contenidoModalVer').innerHTML = html;
        new bootstrap.Modal(document.getElementById('modalVerSancion')).show();
    })
    .catch(error => {
        console.error('Error al cargar detalles:', error);
        alert('Error al cargar los detalles de la sanción');
    });
}

function eliminarSancion(idsancion) {
    if (confirm('¿Está seguro de que desea eliminar esta sanción?')) {
        fetch(`<?= base_url('sanciones/eliminar/') ?>${idsancion}`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '<?= csrf_token() ?>'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert(data.message || 'Error al eliminar la sanción');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al eliminar la sanción');
        });
    }
}
</script>

<?= $footer ?>
