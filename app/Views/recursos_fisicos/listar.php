<?php if (isset($header)): ?>
<?= $header ?>
<?php endif; ?>

<div class="container">
    <!-- Encabezado de la página -->
    <div class="d-flex justify-content-between align-items-center">
    <div>
        <h4 class="mb-0">Recursos Físicos</h4>
        <p class="text-muted mb-0">Lista de recursos físicos disponibles en la biblioteca</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= base_url('/recurso-fisico/pdf') ?>" class="btn btn-outline-secondary">
            <i class="ti ti-file-type-pdf"></i> Exportar PDF
        </a>
    </div>
</div>

<!-- Tabla de recursos físicos -->
<div class="card mt-1">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Portada</th>
                        <th>Título</th>
                        <th>Año</th>
                        <th>Páginas</th>
                        <th>ISBN</th>
                        <th>Edición</th>
                        <th>Editorial</th>
                        <th>Categoría</th>
                        <th>Subcategoría</th>
                        <th>Encuadernación</th>
                        <th>Estado</th>
                        <th>Stock</th>
                        <th>Ejemplares</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($recursos_fisicos)): ?>
                        <?php foreach($recursos_fisicos as $recurso): ?>
                        <tr>
                            <td><?= $recurso->idrecurso ?></td>
                            <td>
                                <?php if (!empty($recurso->portada)): ?>
                                    <img src="<?= base_url(esc($recurso->portada)) ?>" 
                                         alt="Portada" 
                                         style="height:60px;width:auto;border-radius:4px;border:1px solid #e5e5e5;object-fit:cover;"
                                         onerror="this.onerror=null;this.src='<?= base_url('img/portada_default.png') ?>';">
                                <?php else: ?>
                                    <img src="<?= base_url('img/portada_default.png') ?>" 
                                         alt="Sin portada" 
                                         style="height:60px;width:auto;border-radius:4px;border:1px solid #e5e5e5;object-fit:cover;">
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><?= esc($recurso->titulo) ?></strong>
                                <?php if (!empty($recurso->nivel)): ?>
                                    <br><small class="text-muted">Nivel: <?= esc($recurso->nivel) ?></small>
                                <?php endif; ?>
                            </td>
                            <td><?= $recurso->anio ?></td>
                            <td><?= $recurso->numpaginas ?></td>
                            <td>
                                <?php if (!empty($recurso->isbn)): ?>
                                    <code><?= esc($recurso->isbn) ?></code>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td><?= esc($recurso->numedicion) ?></td>
                            <td><?= esc($recurso->editorial) ?></td>
                            <td>
                                <span class="badge bg-primary"><?= esc($recurso->categoria) ?></span>
                            </td>
                            <td>
                                <span class="badge bg-secondary"><?= esc($recurso->subcategoria) ?></span>
                            </td>
                            <td>
                                <?php if (!empty($recurso->encuadernacion)): ?>
                                    <span class="badge bg-info"><?= esc($recurso->encuadernacion) ?></span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
                                $estadoClass = '';
                                switch($recurso->estado) {
                                    case 'disponible':
                                        $estadoClass = 'bg-success';
                                        break;
                                    case 'prestado':
                                        $estadoClass = 'bg-warning';
                                        break;
                                    case 'perdido':
                                        $estadoClass = 'bg-danger';
                                        break;
                                    default:
                                        $estadoClass = 'bg-secondary';
                                }
                                ?>
                                <span class="badge <?= $estadoClass ?>"><?= ucfirst($recurso->estado) ?></span>
                            </td>
                            <td>
                                <span class="badge bg-<?= $recurso->stock > 0 ? 'success' : 'danger' ?>">
                                    <?= $recurso->stock ?>
                                </span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-primary" 
                                        onclick="verEjemplares(<?= $recurso->idrecurso ?>, '<?= esc($recurso->titulo) ?>')">
                                    <i class="ti ti-list"></i> Ver Ejemplares
                                </button>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-warning" 
                                            onclick="editarRecurso(<?= $recurso->idrecurso ?>)">
                                        <i class="ti ti-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                            onclick="eliminarRecurso(<?= $recurso->idrecurso ?>)">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="15" class="text-center text-muted py-4">
                                <i class="ti ti-inbox fs-1 d-block mb-2"></i>
                                No hay recursos físicos registrados
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal para ver detalles -->
<div class="modal fade" id="modalDetalles" tabindex="-1" aria-labelledby="modalDetallesLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetallesLabel">Detalles del Recurso Físico</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="contenidoDetalles">
                <!-- Contenido se carga dinámicamente -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para ver ejemplares -->
<div class="modal fade" id="modalEjemplares" tabindex="-1" aria-labelledby="modalEjemplaresLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEjemplaresLabel">Ejemplares Físicos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="contenidoEjemplares">
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-2">Cargando ejemplares...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Editar Ejemplar -->
<div class="modal fade" id="modalEditarEjemplar" tabindex="-1" aria-labelledby="modalEditarEjemplarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarEjemplarLabel">Editar Ejemplar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditarEjemplar">
                <div class="modal-body">
                    <input type="hidden" name="idejemplar" id="editIdejemplar">
                        <div class="mb-3">
                            <label for="editEstado" class="form-label">Estado Operativo</label>
                            <select class="form-select" id="editEstado" name="estado" required>
                                <option value="disponible">Disponible</option>
                            </select>
                        </div>
                    <div class="mb-3">
                        <label for="editEstadoFisico" class="form-label">Estado Físico</label>
                        <select class="form-select" id="editEstadoFisico" name="estado_fisico" required>
                            <option value="excelente">Excelente</option>
                            <option value="bueno">Bueno</option>
                            <option value="regular">Regular</option>
                            <option value="malo">Malo</option>
                            <option value="muy_malo">Muy Malo</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editUbicacion" class="form-label">Ubicación</label>
                        <input type="text" class="form-control" id="editUbicacion" name="ubicacion" maxlength="100" placeholder="Ej: Estante A-1, Sección Literatura">
                    </div>
                    <div class="mb-3">
                        <label for="editObservaciones" class="form-label">Observaciones</label>
                        <textarea class="form-control" id="editObservaciones" name="observaciones" rows="3" maxlength="500" placeholder="Detalles sobre el estado del ejemplar..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning">Actualizar Ejemplar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function verDetalles(id) {
    // Aquí puedes implementar la lógica para cargar los detalles
    document.getElementById('contenidoDetalles').innerHTML = '<p>Cargando detalles del recurso #' + id + '...</p>';
    var modal = new bootstrap.Modal(document.getElementById('modalDetalles'));
    modal.show();
}

function verEjemplares(idrecurso, titulo) {
    // Actualizar el título del modal
    document.getElementById('modalEjemplaresLabel').textContent = 'Ejemplares de: ' + titulo;
    
    // Mostrar el modal
    var modal = new bootstrap.Modal(document.getElementById('modalEjemplares'));
    modal.show();
    
    // Cargar los ejemplares via AJAX
    fetch('<?= base_url('ejemplares-fisicos/modal/') ?>' + idrecurso)
        .then(response => response.text())
        .then(html => {
            document.getElementById('contenidoEjemplares').innerHTML = html;
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('contenidoEjemplares').innerHTML = 
                '<div class="alert alert-danger">Error al cargar los ejemplares. Por favor, inténtalo de nuevo.</div>';
        });
}

function editarRecurso(id) {
    // Aquí puedes implementar la lógica para editar
    alert('Editar recurso #' + id);
}

function eliminarRecurso(id) {
    if (confirm('¿Estás seguro de que deseas eliminar este recurso físico?')) {
        // Aquí puedes implementar la lógica para eliminar
        alert('Eliminar recurso #' + id);
    }
}

// Función global para abrir el modal de edición de ejemplares
window.abrirModalEditar = function(idejemplar, estado, estadoFisico, ubicacion, observaciones) {
    console.log('Abriendo modal de edición:', {idejemplar, estado, estadoFisico, ubicacion, observaciones});
    
    // Buscar el modal interno en el contenido cargado por AJAX
    const modalEditar = document.getElementById('modalEditarEjemplarInterno');
    const editIdejemplar = document.getElementById('editIdejemplarInterno');
    const editEstado = document.getElementById('editEstadoInterno');
    const editEstadoFisico = document.getElementById('editEstadoFisicoInterno');
    const editUbicacion = document.getElementById('editUbicacionInterno');
    const editObservaciones = document.getElementById('editObservacionesInterno');
    
    // Verificar que todos los elementos existen
    if (modalEditar && editIdejemplar && editEstado && editEstadoFisico && editUbicacion && editObservaciones) {
        // Llenar los campos del modal de edición
        editIdejemplar.value = idejemplar;
        editEstado.value = estado;
        editEstadoFisico.value = estadoFisico;
        editUbicacion.value = ubicacion || '';
        editObservaciones.value = observaciones || '';
        
        // Configurar z-index alto para que aparezca encima del modal de ejemplares
        modalEditar.style.zIndex = '1060'; // Bootstrap modal default es 1055
        
        // Abrir el modal de edición
        const modal = new bootstrap.Modal(modalEditar);
        modal.show();
    } else {
        console.error('Modal de edición interno no encontrado o elementos faltantes');
        alert('Error: No se pudo encontrar el modal de edición. Por favor, recarga la página e intenta de nuevo.');
    }
}

// Función global para guardar la edición del ejemplar
window.guardarEdicionEjemplar = function() {
    console.log('Guardando edición del ejemplar...');
    
    // Obtener los valores del formulario
    const idejemplar = document.getElementById('editIdejemplarInterno').value;
    const estado = document.getElementById('editEstadoInterno').value;
    const estadoFisico = document.getElementById('editEstadoFisicoInterno').value;
    const ubicacion = document.getElementById('editUbicacionInterno').value;
    const observaciones = document.getElementById('editObservacionesInterno').value;
    
    // Validar que el ID del ejemplar existe
    if (!idejemplar) {
        Swal.fire({
            title: 'Error',
            text: 'ID del ejemplar no encontrado',
            icon: 'error',
            confirmButtonText: 'Aceptar'
        });
        return;
    }
    
    // Crear FormData
    const formData = new FormData();
    formData.append('idejemplar', idejemplar);
    formData.append('estado', estado);
    formData.append('estado_fisico', estadoFisico);
    formData.append('ubicacion', ubicacion);
    formData.append('observaciones', observaciones);
    
    // Enviar por AJAX
    fetch('<?= base_url('ejemplares-fisicos/actualizar-estado') ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                title: '¡Éxito!',
                text: data.message,
                icon: 'success',
                confirmButtonText: 'Aceptar',
                timer: 3000,
                timerProgressBar: true,
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then(() => {
                // Solo cerrar el modal de edición
                const modalEditar = bootstrap.Modal.getInstance(document.getElementById('modalEditarEjemplarInterno'));
                if (modalEditar) {
                    modalEditar.hide();
                }
                
                // Recargar el contenido del modal de ejemplares SIN cerrarlo
                // Buscar el botón "Ver Ejemplares" para obtener el ID del recurso
                const verEjemplaresBtn = document.querySelector('[onclick*="verEjemplares"]');
                if (verEjemplaresBtn) {
                    const onclickAttr = verEjemplaresBtn.getAttribute('onclick');
                    const match = onclickAttr.match(/verEjemplares\((\d+)/);
                    if (match) {
                        const idrecurso = match[1];
                        // Recargar el contenido del modal manteniéndolo abierto
                        fetch('<?= base_url('ejemplares-fisicos/modal/') ?>' + idrecurso)
                            .then(response => response.text())
                            .then(html => {
                                document.getElementById('contenidoEjemplares').innerHTML = html;
                                // El modal de ejemplares permanece abierto
                            })
                            .catch(error => {
                                console.error('Error al recargar ejemplares:', error);
                                // Fallback: recargar solo la página actual
                                window.location.href = window.location.href;
                            });
                    }
                }
            });
        } else {
            Swal.fire({
                title: 'Error',
                text: data.message,
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            title: 'Error',
            text: 'Hubo un problema al actualizar el ejemplar. Por favor, inténtalo de nuevo.',
            icon: 'error',
            confirmButtonText: 'Aceptar'
        });
    });
};

// Escuchar el evento personalizado para abrir el modal de edición
document.addEventListener('abrirModalEditarEjemplar', function(e) {
    const data = e.detail;
    console.log('Recibido evento de edición:', data);
    
    // Cerrar el modal actual de ejemplares
    const modalEjemplares = bootstrap.Modal.getInstance(document.getElementById('modalEjemplares'));
    if (modalEjemplares) {
        modalEjemplares.hide();
    }
    
    // Esperar un momento y abrir el modal de edición con los datos
    setTimeout(function() {
        // Llenar los campos del formulario de edición
        document.getElementById('editIdejemplar').value = data.idejemplar;
        document.getElementById('editEstado').value = data.estado;
        document.getElementById('editEstadoFisico').value = data.estadoFisico;
        document.getElementById('editUbicacion').value = data.ubicacion;
        document.getElementById('editObservaciones').value = data.observaciones;
        
        // Abrir el modal de edición
        const modalEditar = new bootstrap.Modal(document.getElementById('modalEditarEjemplar'));
        modalEditar.show();
    }, 300);
});

// Manejar el envío del formulario de edición de ejemplares con delegación de eventos
document.addEventListener('submit', function(e) {
    if (e.target.id === 'formEditarEjemplar') {
        e.preventDefault();
        const formData = new FormData(e.target);
        fetch('<?= base_url('ejemplares-fisicos/actualizar-estado') ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire('¡Éxito!', data.message, 'success').then(() => {
                    // Cerrar el modal de edición
                    var modalEditar = bootstrap.Modal.getInstance(document.getElementById('modalEditarEjemplar'));
                    if (modalEditar) {
                        modalEditar.hide();
                    }
                    
                    // Recargar la página para mostrar los cambios actualizados
                    setTimeout(function() {
                        location.reload();
                    }, 300);
                });
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', 'Hubo un problema al actualizar el ejemplar.', 'error');
        });
    }
});
</script>

</div>

<?php if (isset($footer)): ?>
<?= $footer ?>
<?php endif; ?>
