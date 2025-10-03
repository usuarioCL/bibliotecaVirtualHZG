<?= $header ?>

<div class="container">
    <!-- Encabezado de la página -->
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Ejemplares Físicos</h4>
            <p class="text-muted mb-0">
                <?= esc($recurso['titulo']) ?> - 
                <span class="badge bg-primary"><?= $estadisticas['total'] ?> ejemplares</span>
            </p>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-success" onclick="mostrarModalCrearEjemplares()">
                <i class="ti ti-plus"></i> Crear Ejemplares
            </button>
            <a href="<?= base_url('/recursos-fisicos') ?>" class="btn btn-outline-secondary">
                <i class="ti ti-arrow-left"></i> Volver a Recursos
            </a>
        </div>
    </div>

    <!-- Estadísticas de ejemplares -->
    <div class="row mt-3">
        <div class="col-md-2">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-success"><?= $estadisticas['disponible'] ?></h5>
                    <p class="card-text small">Disponibles</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-warning"><?= $estadisticas['prestado'] ?></h5>
                    <p class="card-text small">Prestados</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-danger"><?= $estadisticas['dañado'] ?></h5>
                    <p class="card-text small">Dañados</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-dark"><?= $estadisticas['perdido'] ?></h5>
                    <p class="card-text small">Perdidos</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-info"><?= $estadisticas['mantenimiento'] ?></h5>
                    <p class="card-text small">Mantenimiento</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-primary"><?= $estadisticas['total'] ?></h5>
                    <p class="card-text small">Total</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de ejemplares -->
    <div class="card mt-3">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Código</th>
                            <th>Estado</th>
                            <th>Ubicación</th>
                            <th>Fecha Ingreso</th>
                            <th>Última Revisión</th>
                            <th>Observaciones</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($ejemplares)): ?>
                            <?php foreach($ejemplares as $ejemplar): ?>
                            <tr>
                                <td>
                                    <strong class="text-primary"><?= esc($ejemplar['codigo_ejemplar']) ?></strong>
                                </td>
                                <td>
                                    <?php
                                    $estadoClass = '';
                                    $estadoIcon = '';
                                    switch($ejemplar['estado_ejemplar']) {
                                        case 'disponible':
                                            $estadoClass = 'bg-success';
                                            $estadoIcon = 'ti ti-check';
                                            break;
                                        case 'prestado':
                                            $estadoClass = 'bg-warning';
                                            $estadoIcon = 'ti ti-book';
                                            break;
                                        case 'dañado':
                                            $estadoClass = 'bg-danger';
                                            $estadoIcon = 'ti ti-alert-triangle';
                                            break;
                                        case 'perdido':
                                            $estadoClass = 'bg-dark';
                                            $estadoIcon = 'ti ti-x';
                                            break;
                                        case 'mantenimiento':
                                            $estadoClass = 'bg-info';
                                            $estadoIcon = 'ti ti-tools';
                                            break;
                                    }
                                    ?>
                                    <span class="badge <?= $estadoClass ?>">
                                        <i class="<?= $estadoIcon ?>"></i> <?= ucfirst($ejemplar['estado_ejemplar']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?= !empty($ejemplar['ubicacion']) ? esc($ejemplar['ubicacion']) : '<span class="text-muted">Sin ubicación</span>' ?>
                                </td>
                                <td>
                                    <?= date('d/m/Y', strtotime($ejemplar['fecha_ingreso'])) ?>
                                </td>
                                <td>
                                    <?= !empty($ejemplar['fecha_ultima_revision']) ? date('d/m/Y', strtotime($ejemplar['fecha_ultima_revision'])) : '<span class="text-muted">Nunca</span>' ?>
                                </td>
                                <td>
                                    <?= !empty($ejemplar['observaciones']) ? esc($ejemplar['observaciones']) : '<span class="text-muted">Sin observaciones</span>' ?>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-warning" 
                                                onclick="mostrarModalEditarEstado(<?= $ejemplar['idejemplar'] ?>, '<?= $ejemplar['estado_ejemplar'] ?>', '<?= esc($ejemplar['observaciones']) ?>')">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                onclick="eliminarEjemplar(<?= $ejemplar['idejemplar'] ?>, '<?= esc($ejemplar['codigo_ejemplar']) ?>')">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="ti ti-inbox fs-1 d-block mb-2"></i>
                                    No hay ejemplares registrados para este recurso
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal para crear ejemplares -->
<div class="modal fade" id="modalCrearEjemplares" tabindex="-1" aria-labelledby="modalCrearEjemplaresLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCrearEjemplaresLabel">Crear Nuevos Ejemplares</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formCrearEjemplares">
                    <input type="hidden" name="idrecurso" value="<?= $recurso['idrecurso'] ?>">
                    <div class="mb-3">
                        <label for="cantidad" class="form-label">Cantidad de ejemplares a crear</label>
                        <input type="number" class="form-control" id="cantidad" name="cantidad" min="1" max="100" required>
                        <div class="form-text">Se generarán códigos automáticamente basados en el título del recurso</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" onclick="crearEjemplares()">Crear Ejemplares</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para editar estado -->
<div class="modal fade" id="modalEditarEstado" tabindex="-1" aria-labelledby="modalEditarEstadoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarEstadoLabel">Editar Estado del Ejemplar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditarEstado">
                    <input type="hidden" id="idejemplar" name="idejemplar">
                    <div class="mb-3">
                        <label for="estado" class="form-label">Estado del ejemplar</label>
                        <select class="form-select" id="estado" name="estado" required>
                            <option value="disponible">Disponible</option>
                            <option value="prestado">Prestado</option>
                            <option value="dañado">Dañado</option>
                            <option value="perdido">Perdido</option>
                            <option value="mantenimiento">Mantenimiento</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea class="form-control" id="observaciones" name="observaciones" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="actualizarEstado()">Actualizar Estado</button>
            </div>
        </div>
    </div>
</div>

<script>
function mostrarModalCrearEjemplares() {
    var modal = new bootstrap.Modal(document.getElementById('modalCrearEjemplares'));
    modal.show();
}

function mostrarModalEditarEstado(idejemplar, estadoActual, observaciones) {
    document.getElementById('idejemplar').value = idejemplar;
    document.getElementById('estado').value = estadoActual;
    document.getElementById('observaciones').value = observaciones;
    
    var modal = new bootstrap.Modal(document.getElementById('modalEditarEstado'));
    modal.show();
}

function crearEjemplares() {
    var formData = new FormData(document.getElementById('formCrearEjemplares'));
    
    fetch('<?= base_url('ejemplares-fisicos/crear') ?>', {
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
                icon: 'success',
                title: '¡Éxito!',
                text: data.message,
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error al crear ejemplares'
        });
    });
}

function actualizarEstado() {
    var formData = new FormData(document.getElementById('formEditarEstado'));
    
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
                icon: 'success',
                title: '¡Éxito!',
                text: data.message,
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error al actualizar estado'
        });
    });
}

function eliminarEjemplar(idejemplar, codigo) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: `¿Deseas eliminar el ejemplar ${codigo}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`<?= base_url('ejemplares-fisicos/eliminar') ?>/${idejemplar}`, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Eliminado!',
                        text: data.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al eliminar ejemplar'
                });
            });
        }
    });
}
</script>

<?= $footer ?>
