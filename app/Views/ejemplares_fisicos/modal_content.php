<?php if (!$recurso): ?>
    <div class="alert alert-danger">Recurso no encontrado.</div>
<?php else: ?>
    <!-- Estadísticas de Ejemplares -->
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Estadísticas de Ejemplares</h5>
                    <div class="d-flex justify-content-around flex-wrap">
                        <?php
                        $totalEjemplares = 0;
                        $estados = [
                            'disponible' => ['label' => 'Disponibles', 'class' => 'success', 'count' => 0],
                            'prestado' => ['label' => 'Prestados', 'class' => 'warning', 'count' => 0],
                            'dañado' => ['label' => 'Dañados', 'class' => 'danger', 'count' => 0],
                            'perdido' => ['label' => 'Perdidos', 'class' => 'dark', 'count' => 0],
                            'mantenimiento' => ['label' => 'Mantenimiento', 'class' => 'info', 'count' => 0],
                        ];

                        if (!empty($estadisticas)) {
                            foreach ($estadisticas as $est) {
                                if (isset($estados[$est->estado])) {
                                    $estados[$est->estado]['count'] = $est->cantidad;
                                    $totalEjemplares += $est->cantidad;
                                }
                            }
                        }
                        ?>
                        <?php foreach ($estados as $estado): ?>
                            <div class="text-center p-2">
                                <span class="badge bg-<?= $estado['class'] ?> fs-5"><?= $estado['count'] ?></span>
                                <p class="mb-0 text-muted"><?= $estado['label'] ?></p>
                            </div>
                        <?php endforeach; ?>
                        <div class="text-center p-2">
                            <span class="badge bg-primary fs-5"><?= $totalEjemplares ?></span>
                            <p class="mb-0 text-muted">Total</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Botón para crear más ejemplares -->
    <div class="row mb-3">
        <div class="col-md-12">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalCrearEjemplares">
                <i class="ti ti-plus"></i> Crear Más Ejemplares
            </button>
        </div>
    </div>

    <!-- Tabla de ejemplares -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Código</th>
                            <th>Estado</th>
                            <th>Ubicación</th>
                            <th>Observaciones</th>
                            <th>Fecha Ingreso</th>
                            <th>Última Revisión</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($ejemplares)): ?>
                            <?php foreach($ejemplares as $ejemplar): ?>
                            <tr>
                                <td><?= $ejemplar->idejemplar ?></td>
                                <td><strong><?= esc($ejemplar->codigo_ejemplar) ?></strong></td>
                                <td>
                                    <?php
                                    $estadoClass = '';
                                    switch($ejemplar->estado_ejemplar) {
                                        case 'disponible': $estadoClass = 'bg-success'; break;
                                        case 'prestado': $estadoClass = 'bg-warning'; break;
                                        case 'dañado': $estadoClass = 'bg-danger'; break;
                                        case 'perdido': $estadoClass = 'bg-dark'; break;
                                        case 'mantenimiento': $estadoClass = 'bg-info'; break;
                                        default: $estadoClass = 'bg-secondary';
                                    }
                                    ?>
                                    <span class="badge <?= $estadoClass ?>"><?= ucfirst($ejemplar->estado_ejemplar) ?></span>
                                </td>
                                <td><?= esc($ejemplar->ubicacion) ?: 'N/A' ?></td>
                                <td><?= esc($ejemplar->observaciones) ?: 'Ninguna' ?></td>
                                <td><?= date('d/m/Y', strtotime($ejemplar->fecha_ingreso)) ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($ejemplar->fecha_ultima_revision)) ?></td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-warning" 
                                                onclick="editarEjemplarModal(<?= $ejemplar->idejemplar ?>, '<?= esc($ejemplar->estado_ejemplar) ?>', '<?= esc($ejemplar->ubicacion) ?>', '<?= esc($ejemplar->observaciones) ?>')">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        <?php if ($ejemplar->activo): ?>
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    onclick="eliminarEjemplarModal(<?= $ejemplar->idejemplar ?>)">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        <?php else: ?>
                                            <button type="button" class="btn btn-sm btn-outline-success" 
                                                    onclick="restaurarEjemplarModal(<?= $ejemplar->idejemplar ?>)">
                                                <i class="ti ti-refresh"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="ti ti-inbox fs-1 d-block mb-2"></i>
                                    No hay ejemplares registrados para este recurso.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal para Crear Ejemplares -->
    <div class="modal fade" id="modalCrearEjemplares" tabindex="-1" aria-labelledby="modalCrearEjemplaresLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCrearEjemplaresLabel">Crear Nuevos Ejemplares</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formCrearEjemplares">
                    <div class="modal-body">
                        <input type="hidden" name="idrecurso" value="<?= $recurso['idrecurso'] ?>">
                        <div class="mb-3">
                            <label for="cantidadEjemplares" class="form-label">Cantidad de Ejemplares a Crear</label>
                            <input type="number" class="form-control" id="cantidadEjemplares" name="cantidad" min="1" value="1" required>
                        </div>
                        <div class="alert alert-info" role="alert">
                            Se generarán códigos únicos automáticamente (ej: "<?= esc(strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $recurso['titulo']), 0, 4))) ?>-001").
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Crear Ejemplares</button>
                    </div>
                </form>
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
                            <label for="editEstado" class="form-label">Estado</label>
                            <select class="form-select" id="editEstado" name="estado" required>
                                <option value="disponible">Disponible</option>
                                <option value="prestado">Prestado</option>
                                <option value="dañado">Dañado</option>
                                <option value="perdido">Perdido</option>
                                <option value="mantenimiento">Mantenimiento</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editUbicacion" class="form-label">Ubicación</label>
                            <input type="text" class="form-control" id="editUbicacion" name="ubicacion" maxlength="100">
                        </div>
                        <div class="mb-3">
                            <label for="editObservaciones" class="form-label">Observaciones</label>
                            <textarea class="form-control" id="editObservaciones" name="observaciones" rows="3" maxlength="500"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Función para abrir el modal de edición
        function editarEjemplarModal(idejemplar, estado, ubicacion, observaciones) {
            document.getElementById('editIdejemplar').value = idejemplar;
            document.getElementById('editEstado').value = estado;
            document.getElementById('editUbicacion').value = ubicacion;
            document.getElementById('editObservaciones').value = observaciones;
            var modal = new bootstrap.Modal(document.getElementById('modalEditarEjemplar'));
            modal.show();
        }

        // Manejar el envío del formulario de creación de ejemplares
        document.getElementById('formCrearEjemplares').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
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
                    Swal.fire('¡Éxito!', data.message, 'success').then(() => {
                        // Recargar el contenido del modal
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'Hubo un problema al crear los ejemplares.', 'error');
            });
        });

        // Manejar el envío del formulario de edición de ejemplares
        document.getElementById('formEditarEjemplar').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
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
                        // Recargar el contenido del modal
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'Hubo un problema al actualizar el ejemplar.', 'error');
            });
        });

        // Función para eliminar lógicamente un ejemplar
        function eliminarEjemplarModal(idejemplar) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "El ejemplar se marcará como inactivo y no estará disponible.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, inactivar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('<?= base_url('ejemplares-fisicos/eliminar/') ?>' + idejemplar, {
                        method: 'DELETE',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('¡Inactivado!', data.message, 'success').then(() => {
                                // Recargar el contenido del modal
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error', data.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Error', 'Hubo un problema al inactivar el ejemplar.', 'error');
                    });
                }
            });
        }

        // Función para restaurar un ejemplar
        function restaurarEjemplarModal(idejemplar) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "El ejemplar se marcará como activo y estará disponible nuevamente.",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, restaurar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('<?= base_url('ejemplares-fisicos/restaurar/') ?>' + idejemplar, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('¡Restaurado!', data.message, 'success').then(() => {
                                // Recargar el contenido del modal
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error', data.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Error', 'Hubo un problema al restaurar el ejemplar.', 'error');
                    });
                }
            });
        }
    </script>
<?php endif; ?>
