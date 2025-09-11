<div class="container">
    <!-- Encabezado de la página -->
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Gestión de Recursos</h4>
            <p class="text-muted mb-0">Recursos bibliográficos del sistema</p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= base_url('/recursos/pdf') ?>" class="btn btn-outline-secondary">
                <i class="ti ti-file-type-pdf"></i> Exportar PDF
            </a>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrearRecurso">
                <i class="ti ti-plus"></i> Nuevo Recurso
            </button>
        </div>
    </div>

    <!-- Tabla de recursos -->
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
                            <th>Encuadernación</th>
                            <th>ISBN</th>
                            <th>Edición</th>
                            <th>Estado</th>
                            <th>Stock</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($recursos)): ?>
                            <?php foreach($recursos as $recurso): ?>
                            <tr>
                                <td><?= $recurso['idrecurso'] ?></td>
                                <td>
                                    <?php if (!empty($recurso['rutaportada'])): ?>
                                        <img src="<?= base_url(esc($recurso['rutaportada'])) ?>" alt="Portada" style="height:60px;width:auto;border-radius:4px;border:1px solid #e5e5e5;object-fit:cover;"
                                             onerror="this.onerror=null;this.src='<?= base_url('img/portada_default.png') ?>';">
                                    <?php else: ?>
                                        <img src="<?= base_url('img/portada_default.png') ?>" alt="Sin portada" style="height:60px;width:auto;border-radius:4px;border:1px solid #e5e5e5;object-fit:cover;">
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="fw-bold"><?= esc($recurso['titulo']) ?></div>
                                    <?php if(!empty($recurso['subtitulo'])): ?>
                                        <small class="text-muted"><?= esc($recurso['subtitulo']) ?></small>
                                    <?php endif; ?>
                                </td>
                                <td><?= esc($recurso['anio']) ?></td>
                                <td><?= esc($recurso['numpaginas']) ?></td>
                                <td><?= esc($recurso['encuadernacion']) ?></td>
                                <td>
                                    <?php if(!empty($recurso['isbn'])): ?>
                                        <?= esc($recurso['isbn']) ?>
                                    <?php else: ?>
                                        <span class="text-muted">Sin ISBN</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= esc($recurso['numedicion']) ?></td>
                                <td>
                                    <?php if($recurso['estado'] === 'disponible'): ?>
                                        <span class="badge bg-success">Disponible</span>
                                    <?php elseif($recurso['estado'] === 'prestado'): ?>
                                        <span class="badge bg-warning text-dark">Prestado</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">No disponible</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($recurso['stock'] > 0): ?>
                                        <span class="badge bg-primary"><?= $recurso['stock'] ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">0</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="#" 
                                           data-url="<?= base_url('recursos/editar/') ?><?= $recurso['idrecurso'] ?>"
                                           class="btn btn-sm btn-warning btn-edit" 
                                           title="Editar">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                        <a href="#" 
                                           data-url="<?= base_url('recursos/eliminar/') ?><?= $recurso['idrecurso'] ?>" 
                                           class="btn btn-sm btn-danger btn-delete"
                                           title="Eliminar">
                                            <i class="ti ti-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="11" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="ti ti-inbox fs-1 mb-3"></i>
                                        <h5>No hay recursos registrados</h5>
                                        <p>Comienza agregando tu primer recurso bibliográfico</p>
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

<!-- Incluir directamente el modal de crear recurso -->
<?= view('recursos/crear') ?>

<script>
$(document).ready(function() {
    // Cargar SweetAlert2 si no existe
    function loadSweetAlert2(callback) {
        if (window.Swal) {
            if (typeof callback === 'function') callback();
            return;
        }
        var script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
        script.onload = function() { if (typeof callback === 'function') callback(); };
        document.head.appendChild(script);
    }

    // Delegar click para botón Editar: confirmar con SweetAlert2 y cargar modal por AJAX (como Crear)
    $(document).on('click', '.btn-edit', function(e) {
        e.preventDefault();
        var url = $(this).data('url');
        // Asegurar SweetAlert2 disponible
        loadSweetAlert2(function() {
            Swal.fire({
                title: 'Editar recurso',
                text: 'Vas a editar este recurso. ¿Deseas continuar?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, editar',
                cancelButtonText: 'Cancelar'
            }).then(function(result) {
                if (!result.isConfirmed) return;
                $.get(url, function(response) {
                    // Eliminar instancia previa del modal si existe para evitar duplicados
                    $('#modalEditarRecurso').remove();
                    // Extraer solo el nodo del modal en caso de que la vista tenga otros contenidos
                    var temp = document.createElement('div');
                    temp.innerHTML = response;
                    var modalNode = temp.querySelector('#modalEditarRecurso');
                    if (modalNode) {
                        document.body.appendChild(modalNode);
                        var modalEl = document.getElementById('modalEditarRecurso');
                        var modal = new bootstrap.Modal(modalEl, { backdrop: 'static' });
                        modal.show();
                        $(modalEl).on('hidden.bs.modal', function() { $(this).remove(); });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'No se pudo abrir',
                            text: 'No se encontró el contenido del modal.'
                        });
                    }
                }).fail(function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de conexión',
                        text: 'No se pudo cargar el formulario de edición.'
                    });
                });
            });
        });
    });

    // Delegar click para botón Eliminar: confirmar con SweetAlert2
    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        var url = $(this).data('url');
        // Asegurar SweetAlert2 disponible
        loadSweetAlert2(function() {
            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Esta acción eliminará permanentemente este recurso y no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then(function(result) {
                if (result.isConfirmed) {
                    // Mostrar loading mientras se procesa
                    Swal.fire({
                        title: 'Eliminando...',
                        text: 'Por favor espera mientras se elimina el recurso',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: function() {
                            Swal.showLoading();
                        }
                    });
                    
                    // Hacer petición AJAX para eliminar
                    $.ajax({
                        url: url,
                        type: 'POST',
                        dataType: 'json',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        success: function(response) {
                            console.log('Respuesta del servidor:', response);
                            
                            // Verificar si la respuesta es exitosa
                            if (response && response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: '¡Eliminado!',
                                    text: response.message || 'El recurso ha sido eliminado correctamente.',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(function() {
                                    // Recargar la página para actualizar la lista
                                    window.location.reload();
                                });
                            } else {
                                // Si la respuesta indica error
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error al eliminar',
                                    text: response.message || 'No se pudo eliminar el recurso.'
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log('Error AJAX:', xhr, status, error);
                            console.log('Response Text:', xhr.responseText);
                            
                            var errorMessage = 'No se pudo eliminar el recurso. Por favor, inténtalo de nuevo.';
                            
                            // Si hay respuesta JSON con error
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }
                            
                            Swal.fire({
                                icon: 'error',
                                title: 'Error al eliminar',
                                text: errorMessage
                            });
                        }
                    });
                }
            });
        });
    });
});
</script>

