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
                        Detalles de Editorial
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="javascript:void(0)" onclick="cargarContenidoDefault()">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="javascript:void(0)" onclick="cargarEditoriales()">Editoriales</a></li>
                            <li class="breadcrumb-item active"><?= esc($editorial['editorial']) ?></li>
                        </ol>
                    </nav>
                    <p class="text-muted mb-0 mt-1">Información detallada y recursos asociados</p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <button type="button" class="btn btn-light btn-sm" onclick="cargarEditoriales()">
                        <i class="ti ti-arrow-left me-1"></i>
                        Volver
                    </button>
                    <button type="button" class="btn btn-primary btn-sm" onclick="editarEditorial(<?= $editorial['ideditorial'] ?>)">
                        <i class="ti ti-edit me-1"></i>
                        Editar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Información de la editorial -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        <div class="editorial-avatar editorial-avatar-lg me-4">
                            <i class="ti ti-building-store"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="mb-2"><?= esc($editorial['editorial']) ?></h3>
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <div class="d-flex align-items-center text-muted">
                                        <i class="ti ti-hash me-2"></i>
                                        <span>ID: <?= $editorial['ideditorial'] ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="d-flex align-items-center text-muted">
                                        <i class="ti ti-books me-2"></i>
                                        <span><?= count($recursos) ?> recurso(s) asociado(s)</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="card-title mb-3">
                        <i class="ti ti-chart-bar me-2"></i>
                        Estadísticas
                    </h6>
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Total Recursos</span>
                                <span class="editorial-badge editorial-badge-info"><?= count($recursos) ?></span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Recursos Disponibles</span>
                                <span class="editorial-badge editorial-badge-success"><?= count(array_filter($recursos, fn($r) => $r['estado'] === 'disponible')) ?></span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Recursos Prestados</span>
                                <span class="editorial-badge editorial-badge-warning"><?= count(array_filter($recursos, fn($r) => $r['estado'] === 'prestado')) ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recursos asociados -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0">
                        <i class="ti ti-books me-2"></i>
                        Recursos Asociados
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (empty($recursos)): ?>
                        <div class="editorial-empty-state">
                            <i class="ti ti-book-off empty-icon"></i>
                            <h5>No hay recursos asociados</h5>
                            <p>Esta editorial aún no tiene recursos registrados.</p>
                            <button type="button" class="btn btn-primary" onclick="cargarRecursos()">
                                <i class="ti ti-plus me-1"></i>
                                Ir a Recursos
                            </button>
                        </div>
                    <?php else: ?>
                        <!-- Filtros para recursos -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="editorial-search-container">
                                    <input type="text" 
                                           class="form-control editorial-search-input" 
                                           id="searchRecursos" 
                                           placeholder="Buscar recursos...">
                                    <i class="ti ti-search editorial-search-icon"></i>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select editorial-filter-select" id="filterEstado">
                                    <option value="">Todos los estados</option>
                                    <option value="disponible">Disponible</option>
                                    <option value="prestado">Prestado</option>
                                    <option value="perdido">Perdido</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select editorial-filter-select" id="sortRecursos">
                                    <option value="titulo_asc">Título A-Z</option>
                                    <option value="titulo_desc">Título Z-A</option>
                                    <option value="anio_desc">Año (Más reciente)</option>
                                    <option value="anio_asc">Año (Más antiguo)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Tabla de recursos -->
                        <div class="table-responsive">
                            <table class="table editorial-table align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Recurso</th>
                                        <th>Categoría</th>
                                        <th class="text-center">Año</th>
                                        <th class="text-center">Estado</th>
                                        <th class="text-center">Stock</th>
                                        <th class="text-end">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="recursosTableBody">
                                    <?php foreach ($recursos as $recurso): ?>
                                        <tr data-titulo="<?= strtolower(esc($recurso['titulo'])) ?>" 
                                            data-estado="<?= $recurso['estado'] ?>" 
                                            data-anio="<?= $recurso['anio'] ?>">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm me-3">
                                                        <div class="avatar-title rounded bg-secondary-subtle text-secondary">
                                                            <i class="ti ti-book"></i>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0"><?= esc($recurso['titulo']) ?></h6>
                                                        <small class="text-muted">
                                                            ISBN: <?= $recurso['isbn'] ?: 'No disponible' ?>
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <span class="editorial-badge editorial-badge-info">
                                                        <?= esc($recurso['categoria'] ?? 'Sin categoría') ?>
                                                    </span>
                                                    <?php if ($recurso['subcategoria']): ?>
                                                        <br>
                                                        <small class="text-muted"><?= esc($recurso['subcategoria']) ?></small>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <?php if ($recurso['anio']): ?>
                                                    <span class="editorial-badge editorial-badge-info"><?= $recurso['anio'] ?></span>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <?php
                                                $estadoClass = match($recurso['estado']) {
                                                    'disponible' => 'editorial-badge-success',
                                                    'prestado' => 'editorial-badge-warning',
                                                    'perdido' => 'editorial-badge-danger',
                                                    default => 'editorial-badge-info'
                                                };
                                                ?>
                                                <span class="editorial-badge <?= $estadoClass ?>">
                                                    <?= ucfirst($recurso['estado']) ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="editorial-badge editorial-badge-info">
                                                    <?= $recurso['stock'] ?>
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <div class="editorial-btn-group" role="group">
                                                    <button type="button" class="editorial-btn editorial-btn-info editorial-tooltip" onclick="verDetalleRecurso(<?= $recurso['idrecurso'] ?>)" data-tooltip="Ver detalles">
                                                        <i class="ti ti-eye"></i>
                                                    </button>
                                                    <button type="button" class="editorial-btn editorial-btn-primary editorial-tooltip" onclick="editarRecurso(<?= $recurso['idrecurso'] ?>)" data-tooltip="Editar">
                                                        <i class="ti ti-edit"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Información de paginación -->
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <p class="text-muted mb-0">
                                    Mostrando <span id="showingRecursos"><?= count($recursos) ?></span> de 
                                    <span id="totalRecursos"><?= count($recursos) ?></span> recursos
                                </p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Modal para editar editorial -->
<div class="modal fade editorial-modal" id="modalEditorial" tabindex="-1" aria-labelledby="modalEditorialLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditorialLabel">
                    <i class="ti ti-edit me-2"></i>
                    Editar Editorial
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
                               value="<?= esc($editorial['editorial']) ?>"
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
                        Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    let recursosData = <?= json_encode($recursos) ?>;
    let recursosFiltrados = [...recursosData];

    // Event listeners para filtros
    $('#searchRecursos').on('input', function() {
        filtrarRecursos();
    });

    $('#filterEstado').on('change', function() {
        filtrarRecursos();
    });

    $('#sortRecursos').on('change', function() {
        ordenarRecursos();
    });

    // Formulario de edición
    $('#formEditorial').on('submit', function(e) {
        e.preventDefault();
        guardarEditorial();
    });

    // Funciones
    function filtrarRecursos() {
        const termino = $('#searchRecursos').val().toLowerCase();
        const estado = $('#filterEstado').val();
        
        recursosFiltrados = recursosData.filter(recurso => {
            const coincideTitulo = recurso.titulo.toLowerCase().includes(termino);
            const coincideEstado = !estado || recurso.estado === estado;
            return coincideTitulo && coincideEstado;
        });

        mostrarRecursos();
    }

    function ordenarRecursos() {
        const orden = $('#sortRecursos').val();
        
        switch(orden) {
            case 'titulo_asc':
                recursosFiltrados.sort((a, b) => a.titulo.localeCompare(b.titulo));
                break;
            case 'titulo_desc':
                recursosFiltrados.sort((a, b) => b.titulo.localeCompare(a.titulo));
                break;
            case 'anio_desc':
                recursosFiltrados.sort((a, b) => (b.anio || 0) - (a.anio || 0));
                break;
            case 'anio_asc':
                recursosFiltrados.sort((a, b) => (a.anio || 0) - (b.anio || 0));
                break;
        }

        mostrarRecursos();
    }

    function mostrarRecursos() {
        const tbody = $('#recursosTableBody');
        
        if (recursosFiltrados.length === 0) {
            tbody.html(`
                <tr>
                    <td colspan="6" class="text-center py-4">
                        <div class="editorial-empty-state">
                            <i class="ti ti-search empty-icon"></i>
                            <h5>No se encontraron recursos</h5>
                            <p>Intente ajustar los filtros de búsqueda.</p>
                        </div>
                    </td>
                </tr>
            `);
            return;
        }

        let html = '';
        recursosFiltrados.forEach(function(recurso) {
            const estadoClass = {
                'disponible': 'editorial-badge-success',
                'prestado': 'editorial-badge-warning',
                'perdido': 'editorial-badge-danger'
            }[recurso.estado] || 'editorial-badge-info';

            html += `
                <tr class="editorial-slide-in">
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm me-3">
                                <div class="avatar-title rounded bg-secondary-subtle text-secondary">
                                    <i class="ti ti-book"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="mb-0">${recurso.titulo}</h6>
                                <small class="text-muted">
                                    ISBN: ${recurso.isbn || 'No disponible'}
                                </small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div>
                            <span class="editorial-badge editorial-badge-info">
                                ${recurso.categoria || 'Sin categoría'}
                            </span>
                            ${recurso.subcategoria ? `<br><small class="text-muted">${recurso.subcategoria}</small>` : ''}
                        </div>
                    </td>
                    <td class="text-center">
                        ${recurso.anio ? `<span class="editorial-badge editorial-badge-info">${recurso.anio}</span>` : '<span class="text-muted">-</span>'}
                    </td>
                    <td class="text-center">
                        <span class="editorial-badge ${estadoClass}">
                            ${recurso.estado.charAt(0).toUpperCase() + recurso.estado.slice(1)}
                        </span>
                    </td>
                    <td class="text-center">
                        <span class="editorial-badge editorial-badge-info">
                            ${recurso.stock}
                        </span>
                    </td>
                    <td class="text-end">
                        <div class="editorial-btn-group" role="group">
                            <button type="button" class="editorial-btn editorial-btn-info editorial-tooltip" onclick="verDetalleRecurso(${recurso.idrecurso})" data-tooltip="Ver detalles">
                                <i class="ti ti-eye"></i>
                            </button>
                            <button type="button" class="editorial-btn editorial-btn-primary editorial-tooltip" onclick="editarRecurso(${recurso.idrecurso})" data-tooltip="Editar">
                                <i class="ti ti-edit"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });

        tbody.html(html);
        $('#showingRecursos').text(recursosFiltrados.length);
    }

    function guardarEditorial() {
        const formData = new FormData($('#formEditorial')[0]);

        $.ajax({
            url: '<?= base_url('editoriales/editar') ?>/<?= $editorial['ideditorial'] ?>',
            type: 'POST',
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('#submitBtn').prop('disabled', true).html('<i class="ti ti-loader me-1"></i>Actualizando...');
            },
            success: function(response) {
                if (response.success) {
                    $('#modalEditorial').modal('hide');
                    mostrarExito(response.message);
                    setTimeout(() => {
                        cargarEditoriales();
                    }, 1500);
                } else {
                    mostrarErrores(response.errors);
                }
            },
            error: function() {
                mostrarError('Error de conexión');
            },
            complete: function() {
                $('#submitBtn').prop('disabled', false).html('<i class="ti ti-check me-1"></i>Actualizar');
            }
        });
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
    window.editarEditorial = function(id) {
        $('#modalEditorial').modal('show');
    };

    window.verDetalleRecurso = function(id) {
        // Cargar vista de detalles del recurso via AJAX
        $.get('<?= base_url('recursos') ?>/' + id, function(data) {
            $('#contenedor-principal').html(data);
        }).fail(function() {
            mostrarError('Error al cargar los detalles del recurso');
        });
    };

    window.editarRecurso = function(id) {
        // Cargar vista de edición del recurso via AJAX
        $.get('<?= base_url('recursos') ?>/editar/' + id, function(data) {
            $('#contenedor-principal').html(data);
        }).fail(function() {
            mostrarError('Error al cargar la edición del recurso');
        });
    };

    window.cargarRecursos = function() {
        // Cargar vista de recursos via AJAX
        $.get('<?= base_url('recursos') ?>', function(data) {
            $('#contenedor-principal').html(data);
        }).fail(function() {
            mostrarError('Error al cargar los recursos');
        });
    };
});
</script>
