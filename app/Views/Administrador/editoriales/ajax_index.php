<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="<?= base_url('assets/css/editoriales.css') ?>">

<div class="container-fluid">
    <!-- Encabezado de la página con breadcrumb -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between mb-4">
                <div>
                    <h1 class="h3 mb-1 fw-bold text-dark">
                        <i class="ti ti-building-store text-primary me-2"></i>
                        Gestión de Editoriales
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="javascript:void(0)" onclick="cargarContenidoDefault()">Dashboard</a></li>
                            <li class="breadcrumb-item active">Editoriales</li>
                        </ol>
                    </nav>
                    <p class="text-muted mb-0 mt-1">Administra las editoriales de recursos bibliográficos</p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="refreshBtn">
                        <i class="ti ti-refresh"></i> Actualizar
                    </button>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditorial">
                        <i class="ti ti-plus"></i> Nueva Editorial
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas rápidas -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card editorial-stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="editorial-stats-icon bg-primary text-white">
                                <i class="ti ti-building-store"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-medium text-muted font-size-12 mb-0">Total Editoriales</p>
                            <h4 class="mb-0" id="totalEditoriales">-</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card editorial-stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="editorial-stats-icon bg-success text-white">
                                <i class="ti ti-check"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-medium text-muted font-size-12 mb-0">Con Recursos</p>
                            <h4 class="mb-0" id="editorialesConRecursos">-</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card editorial-stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="editorial-stats-icon bg-warning text-white">
                                <i class="ti ti-alert-circle"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-medium text-muted font-size-12 mb-0">Sin Recursos</p>
                            <h4 class="mb-0" id="editorialesSinRecursos">-</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card editorial-stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="editorial-stats-icon bg-info text-white">
                                <i class="ti ti-trending-up"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-medium text-muted font-size-12 mb-0">Más Popular</p>
                            <h6 class="mb-0 text-truncate" id="editorialPopular">-</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros y búsqueda -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="editorial-search-container">
                                <input type="text" 
                                       class="form-control editorial-search-input" 
                                       id="searchInput" 
                                       placeholder="Buscar editoriales...">
                                <i class="ti ti-search editorial-search-icon"></i>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select editorial-filter-select" id="sortSelect">
                                <option value="nombre_asc">Nombre A-Z</option>
                                <option value="nombre_desc">Nombre Z-A</option>
                                <option value="recursos_desc">Más Recursos</option>
                                <option value="recursos_asc">Menos Recursos</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-outline-secondary btn-sm w-100" id="refreshBtn2">
                                <i class="ti ti-refresh me-1"></i>
                                Actualizar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de editoriales -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0">
                        <i class="ti ti-list me-2"></i>
                        Lista de Editoriales
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table editorial-table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Editorial</th>
                                    <th class="text-center">Recursos</th>
                                    <th class="text-center">Estado</th>
                                    <th class="text-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="editorialesTableBody">
                                <!-- Contenido cargado via AJAX -->
                                <tr>
                                    <td colspan="4" class="text-center py-4">
                                        <div class="editorial-loading">
                                            <div class="editorial-spinner"></div>
                                            <span>Cargando editoriales...</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <p class="text-muted mb-0">
                                Mostrando <span id="showingCount">0</span> de <span id="totalCount">0</span> editoriales
                            </p>
                        </div>
                        <div class="col-md-6">
                            <nav aria-label="Paginación">
                                <ul class="pagination pagination-sm justify-content-end mb-0" id="pagination">
                                    <!-- Paginación generada dinámicamente -->
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Modal para crear/editar editorial -->
<div class="modal fade editorial-modal" id="modalEditorial" tabindex="-1" aria-labelledby="modalEditorialLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditorialLabel">
                    <i class="ti ti-plus me-2"></i>
                    Nueva Editorial
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="formEditorial">
                <div class="modal-body">
                    <div class="editorial-form-group">
                        <label for="editorial" class="editorial-form-label">
                            Nombre de la Editorial <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="editorial-form-control" 
                               id="editorial" 
                               name="editorial" 
                               placeholder="Ej: Penguin Random House"
                               required>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        <i class="ti ti-x me-1"></i>
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="ti ti-check me-1"></i>
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de confirmación para eliminar -->
<div class="modal fade" id="modalEliminar" tabindex="-1" aria-labelledby="modalEliminarLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white border-0">
                <h5 class="modal-title" id="modalEliminarLabel">
                    <i class="ti ti-alert-triangle me-2"></i>
                    Confirmar Eliminación
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center">
                    <div class="avatar-lg mx-auto mb-3">
                        <div class="avatar-title rounded-circle bg-danger-subtle text-danger font-size-24">
                            <i class="ti ti-trash"></i>
                        </div>
                    </div>
                    <h5 class="mb-3">¿Está seguro?</h5>
                    <p class="text-muted mb-0" id="eliminarMensaje">
                        Esta acción no se puede deshacer.
                    </p>
                </div>
            </div>
            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <i class="ti ti-x me-1"></i>
                    Cancelar
                </button>
                <button type="button" class="btn btn-danger" id="confirmarEliminar">
                    <i class="ti ti-trash me-1"></i>
                    Eliminar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    let editorialActual = null;
    let editorialesData = [];

    // Cargar datos iniciales
    cargarEditoriales();
    cargarEstadisticas();

    // Event listeners
    $('#formEditorial').on('submit', function(e) {
        e.preventDefault();
        guardarEditorial();
    });

    $('#searchInput').on('input', function() {
        filtrarEditoriales();
    });

    $('#sortSelect').on('change', function() {
        ordenarEditoriales();
    });

    $('#refreshBtn, #refreshBtn2').on('click', function() {
        cargarEditoriales();
        cargarEstadisticas();
    });

    $('#confirmarEliminar').on('click', function() {
        eliminarEditorial();
    });

    // Funciones
    function cargarEditoriales() {
        $.ajax({
            url: '<?= base_url('editoriales/getEditorialesAjax') ?>',
            type: 'GET',
            dataType: 'json',
            beforeSend: function() {
                $('#editorialesTableBody').html(`
                    <tr>
                        <td colspan="4" class="text-center py-4">
                            <div class="editorial-loading">
                                <div class="editorial-spinner"></div>
                                <span>Cargando editoriales...</span>
                            </div>
                        </td>
                    </tr>
                `);
            },
            success: function(response) {
                if (response.success) {
                    editorialesData = response.data;
                    mostrarEditoriales(editorialesData);
                } else {
                    mostrarError('Error al cargar las editoriales');
                }
            },
            error: function() {
                mostrarError('Error de conexión');
            }
        });
    }

    function cargarEstadisticas() {
        $.ajax({
            url: '<?= base_url('editoriales/estadisticas') ?>',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                console.log('Respuesta de estadísticas:', response);
                if (response.success) {
                    const stats = response.data;
                    $('#totalEditoriales').text(stats.total_editoriales || 0);
                    $('#editorialesConRecursos').text(stats.editoriales_con_recursos || 0);
                    $('#editorialesSinRecursos').text(stats.editoriales_sin_recursos || 0);
                    
                    if (stats.editoriales_populares && stats.editoriales_populares.length > 0) {
                        $('#editorialPopular').text(stats.editoriales_populares[0].editorial);
                    } else {
                        $('#editorialPopular').text('N/A');
                    }
                } else {
                    console.error('Error en estadísticas:', response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error AJAX en estadísticas:', error);
                console.error('Status:', status);
                console.error('Response:', xhr.responseText);
            }
        });
    }

    function mostrarEditoriales(editoriales) {
        if (editoriales.length === 0) {
            $('#editorialesTableBody').html(`
                <tr>
                    <td colspan="4" class="text-center py-5">
                        <div class="editorial-empty-state">
                            <i class="ti ti-building-store empty-icon"></i>
                            <h5>No hay editoriales registradas</h5>
                            <p>Haga clic en "Nueva Editorial" para agregar una.</p>
                        </div>
                    </td>
                </tr>
            `);
            return;
        }

        let html = '';
        editoriales.forEach(function(editorial) {
            const estado = editorial.total_recursos > 0 ? 
                '<span class="editorial-badge editorial-badge-success">Activa</span>' : 
                '<span class="editorial-badge editorial-badge-warning">Sin recursos</span>';

            html += `
                <tr class="editorial-slide-in">
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="editorial-avatar me-3">
                                <i class="ti ti-building-store"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">${editorial.editorial}</h6>
                                <small class="text-muted">ID: ${editorial.ideditorial}</small>
                            </div>
                        </div>
                    </td>
                    <td class="text-center">
                        <span class="editorial-badge editorial-badge-info">
                            ${editorial.total_recursos} recursos
                        </span>
                    </td>
                    <td class="text-center">
                        ${estado}
                    </td>
                    <td class="text-end">
                        <div class="editorial-btn-group" role="group">
                            <button type="button" class="editorial-btn editorial-btn-info editorial-tooltip" onclick="verDetallesEditorial(${editorial.ideditorial})" data-tooltip="Ver detalles">
                                <i class="ti ti-eye"></i>
                            </button>
                            <button type="button" class="editorial-btn editorial-btn-primary editorial-tooltip" onclick="editarEditorial(${editorial.ideditorial})" data-tooltip="Editar">
                                <i class="ti ti-edit"></i>
                            </button>
                            <button type="button" class="editorial-btn editorial-btn-danger editorial-tooltip" onclick="confirmarEliminacion(${editorial.ideditorial}, '${editorial.editorial}', ${editorial.total_recursos})" data-tooltip="Eliminar">
                                <i class="ti ti-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });

        $('#editorialesTableBody').html(html);
        $('#showingCount').text(editoriales.length);
        $('#totalCount').text(editoriales.length);
    }

    function filtrarEditoriales() {
        const termino = $('#searchInput').val().toLowerCase();
        const editorialesFiltradas = editorialesData.filter(editorial => 
            editorial.editorial.toLowerCase().includes(termino)
        );
        mostrarEditoriales(editorialesFiltradas);
    }

    function ordenarEditoriales() {
        const orden = $('#sortSelect').val();
        let editorialesOrdenadas = [...editorialesData];

        switch(orden) {
            case 'nombre_asc':
                editorialesOrdenadas.sort((a, b) => a.editorial.localeCompare(b.editorial));
                break;
            case 'nombre_desc':
                editorialesOrdenadas.sort((a, b) => b.editorial.localeCompare(a.editorial));
                break;
            case 'recursos_desc':
                editorialesOrdenadas.sort((a, b) => b.total_recursos - a.total_recursos);
                break;
            case 'recursos_asc':
                editorialesOrdenadas.sort((a, b) => a.total_recursos - b.total_recursos);
                break;
        }

        mostrarEditoriales(editorialesOrdenadas);
    }

    function guardarEditorial() {
        const formData = new FormData($('#formEditorial')[0]);
        const url = editorialActual ? 
            `<?= base_url('editoriales/editar') ?>/${editorialActual}` : 
            '<?= base_url('editoriales/crear') ?>';

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('#submitBtn').prop('disabled', true).html('<i class="ti ti-loader me-1"></i>Guardando...');
            },
            success: function(response) {
                if (response.success) {
                    $('#modalEditorial').modal('hide');
                    mostrarExito(response.message);
                    cargarEditoriales();
                    cargarEstadisticas();
                    resetearFormulario();
                } else {
                    mostrarErrores(response.errors);
                }
            },
            error: function() {
                mostrarError('Error de conexión');
            },
            complete: function() {
                $('#submitBtn').prop('disabled', false).html('<i class="ti ti-check me-1"></i>Guardar');
            }
        });
    }

    function editarEditorial(id) {
        editorialActual = id;
        const editorial = editorialesData.find(e => e.ideditorial == id);
        
        $('#modalEditorialLabel').html('<i class="ti ti-edit me-2"></i>Editar Editorial');
        $('#editorial').val(editorial.editorial);
        $('#modalEditorial').modal('show');
    }

    function confirmarEliminacion(id, nombre, recursos) {
        editorialActual = id;
        const mensaje = recursos > 0 ? 
            `La editorial "${nombre}" tiene ${recursos} recurso(s) asociado(s) y no puede ser eliminada.` :
            `¿Está seguro de eliminar la editorial "${nombre}"?`;
        
        $('#eliminarMensaje').text(mensaje);
        $('#confirmarEliminar').prop('disabled', recursos > 0);
        $('#modalEliminar').modal('show');
    }

    function eliminarEditorial() {
        $.ajax({
            url: `<?= base_url('editoriales/eliminar') ?>/${editorialActual}`,
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                $('#modalEliminar').modal('hide');
                if (response.success) {
                    mostrarExito(response.message);
                    cargarEditoriales();
                    cargarEstadisticas();
                } else {
                    mostrarError(response.message);
                }
            },
            error: function() {
                mostrarError('Error de conexión');
            }
        });
    }

    function verDetallesEditorial(id) {
        // Cargar vista de detalles via AJAX
        $.get('<?= base_url('editoriales/ajax_detalles') ?>/' + id, function(data) {
            $('#contenedor-principal').html(data);
        }).fail(function() {
            mostrarError('Error al cargar los detalles');
        });
    }

    function resetearFormulario() {
        editorialActual = null;
        $('#formEditorial')[0].reset();
        $('#modalEditorialLabel').html('<i class="ti ti-plus me-2"></i>Nueva Editorial');
        $('#editorial').removeClass('is-invalid');
    }

    function mostrarErrores(errors) {
        Object.keys(errors).forEach(function(key) {
            $(`#${key}`).addClass('is-invalid');
            $(`#${key}`).siblings('.invalid-feedback').text(errors[key]);
        });
    }

    function mostrarExito(mensaje) {
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: mensaje,
            timer: 3000,
            showConfirmButton: false
        });
    }

    function mostrarError(mensaje) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: mensaje,
            confirmButtonText: 'Aceptar'
        });
    }

    // Funciones globales
    window.editarEditorial = editarEditorial;
    window.confirmarEliminacion = confirmarEliminacion;
    window.verDetallesEditorial = verDetallesEditorial;
});
</script>
