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
                            <th>Estado Físico</th>
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
                                <td>
                                    <?php
                                    $estadoFisicoClass = '';
                                    $estadoFisicoText = '';
                                    switch($ejemplar->estado_fisico) {
                                        case 'excelente': 
                                            $estadoFisicoClass = 'bg-success'; 
                                            $estadoFisicoText = 'Excelente'; 
                                            break;
                                        case 'bueno': 
                                            $estadoFisicoClass = 'bg-primary'; 
                                            $estadoFisicoText = 'Bueno'; 
                                            break;
                                        case 'regular': 
                                            $estadoFisicoClass = 'bg-warning'; 
                                            $estadoFisicoText = 'Regular'; 
                                            break;
                                        case 'malo': 
                                            $estadoFisicoClass = 'bg-danger'; 
                                            $estadoFisicoText = 'Malo'; 
                                            break;
                                        case 'muy_malo': 
                                            $estadoFisicoClass = 'bg-dark'; 
                                            $estadoFisicoText = 'Muy Malo'; 
                                            break;
                                        default: 
                                            $estadoFisicoClass = 'bg-secondary'; 
                                            $estadoFisicoText = 'Sin evaluar';
                                    }
                                    ?>
                                    <span class="badge <?= $estadoFisicoClass ?>"><?= $estadoFisicoText ?></span>
                                </td>
                                <td><?= esc($ejemplar->ubicacion) ?: 'N/A' ?></td>
                                <td><?= esc($ejemplar->observaciones) ?: 'Ninguna' ?></td>
                                <td><?= date('d/m/Y', strtotime($ejemplar->fecha_ingreso)) ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($ejemplar->fecha_ultima_revision)) ?></td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-warning" 
                                                onclick="abrirModalEditar(<?= $ejemplar->idejemplar ?>, '<?= esc($ejemplar->estado_ejemplar) ?>', '<?= esc($ejemplar->estado_fisico) ?>', '<?= esc($ejemplar->ubicacion) ?>', '<?= esc($ejemplar->observaciones) ?>')">
                                            <i class="ti ti-settings"></i>
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
                                <td colspan="9" class="text-center text-muted py-4">
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

    <!-- Modal para Editar Ejemplar - Dentro del contenido del modal -->
    <div class="modal fade" id="modalEditarEjemplarInterno" tabindex="-1" aria-labelledby="modalEditarEjemplarInternoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditarEjemplarInternoLabel">Editar Ejemplar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formEditarEjemplarInterno">
                    <div class="modal-body">
                        <input type="hidden" name="idejemplar" id="editIdejemplarInterno">
                        <div class="mb-3">
                            <label for="editEstadoInterno" class="form-label">Estado Operativo</label>
                            <select class="form-select" id="editEstadoInterno" name="estado" required>
                                <option value="disponible">Disponible</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editEstadoFisicoInterno" class="form-label">Estado Físico</label>
                            <select class="form-select" id="editEstadoFisicoInterno" name="estado_fisico" required>
                                <option value="excelente">Excelente</option>
                                <option value="bueno">Bueno</option>
                                <option value="regular">Regular</option>
                                <option value="malo">Malo</option>
                                <option value="muy_malo">Muy Malo</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editUbicacionInterno" class="form-label">Ubicación</label>
                            <input type="text" class="form-control" id="editUbicacionInterno" name="ubicacion" maxlength="100" placeholder="Ej: Estante A-1, Sección Literatura">
                        </div>
                        <div class="mb-3">
                            <label for="editObservacionesInterno" class="form-label">Observaciones</label>
                            <textarea class="form-control" id="editObservacionesInterno" name="observaciones" rows="3" maxlength="500" placeholder="Detalles sobre el estado del ejemplar..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-warning" onclick="guardarEdicionEjemplar()">Actualizar Ejemplar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Función simple para abrir el modal de edición - Hacerla global
        window.abrirModalEditar = function(idejemplar, estado, estadoFisico, ubicacion, observaciones) {
            console.log('Abriendo modal de edición:', {idejemplar, estado, estadoFisico, ubicacion, observaciones});
            
            // Buscar el modal interno
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
                
                // Abrir el modal de edición
                const modal = new bootstrap.Modal(modalEditar);
                modal.show();
            } else {
                console.error('Modal de edición interno no encontrado o elementos faltantes');
                alert('Error: No se pudo encontrar el modal de edición. Por favor, recarga la página e intenta de nuevo.');
            }
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

        // Función para guardar la edición del ejemplar
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
                        // Cerrar todos los modales
                        const modalEditar = bootstrap.Modal.getInstance(document.getElementById('modalEditarEjemplarInterno'));
                        if (modalEditar) {
                            modalEditar.hide();
                        }
                        
                        const modalEjemplares = bootstrap.Modal.getInstance(document.getElementById('modalEjemplares'));
                        if (modalEjemplares) {
                            modalEjemplares.hide();
                        }
                        
                        // Recargar la página después de cerrar los modales
                        setTimeout(() => {
                            window.location.reload();
                        }, 500);
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
