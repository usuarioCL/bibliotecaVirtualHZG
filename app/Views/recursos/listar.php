<link rel="stylesheet" href="<?= base_url('assets/css/recursos-digitales-styles.css') ?>">

<div class="">
    <!-- Encabezado de la página -->
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Gestión de Recursos</h4>
            <p class="text-muted mb-0">Recursos bibliográficos del sistema</p>
        </div>
        <div class="d-flex gap-2">
            <button type="button" id="btnExportarPdf" class="btn btn-outline-secondary">
                <i class="ti ti-file-type-pdf"></i> Exportar PDF
            </button>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrearRecurso">
                <i class="ti ti-plus"></i> Nuevo Recurso
            </button>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mt-3">
        <div class="card-body">
            <form id="formFiltros">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Estado</label>
                        <select name="estado" id="filtroEstado" class="form-select">
                            <option value="">Todos los estados</option>
                            <option value="disponible" <?= ($filtros['estado'] ?? '') === 'disponible' ? 'selected' : '' ?>>Disponible</option>
                            <option value="prestado" <?= ($filtros['estado'] ?? '') === 'prestado' ? 'selected' : '' ?>>Prestado</option>
                            <option value="perdido" <?= ($filtros['estado'] ?? '') === 'perdido' ? 'selected' : '' ?>>No disponible</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bold">Año Desde</label>
                        <input type="text" 
                               name="anio_desde" 
                               id="filtroAnioDesde" 
                               class="form-control" 
                               placeholder="Ej: 1986"
                               maxlength="4"
                               pattern="\d{4}"
                               value="<?= esc($filtros['anio_desde'] ?? '') ?>">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bold">Año Hasta</label>
                        <input type="text" 
                               name="anio_hasta" 
                               id="filtroAnioHasta" 
                               class="form-control" 
                               placeholder="Ej: 2006"
                               maxlength="4"
                               pattern="\d{4}"
                               value="<?= esc($filtros['anio_hasta'] ?? '') ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Tipo</label>
                        <select name="tipo" id="filtroTipo" class="form-select">
                            <option value="">Todos los tipos</option>
                            <?php foreach($tiposrecurso as $tipo): ?>
                                <option value="<?= $tipo['idtiporecurso'] ?>" <?= ($filtros['tipo'] ?? '') == $tipo['idtiporecurso'] ? 'selected' : '' ?>>
                                    <?= esc($tipo['tiporecurso']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="button" id="btnLimpiar" class="btn btn-outline-secondary w-100">
                            <i class="ti ti-x"></i> Limpiar Filtros
                        </button>
                    </div>
                </div>
            </form>
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
                            <th>Tipo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($recursos)): ?>
                            <?php foreach($recursos as $recurso): ?>
                            <tr>
                                <td><?= $recurso['idrecurso'] ?></td>
                                <td>
                                    <?php if (!empty($recurso['portada'])): ?>
                                        <img src="<?= base_url(esc($recurso['portada'])) ?>" alt="Portada" style="height:60px;width:auto;border-radius:4px;border:1px solid #e5e5e5;object-fit:cover;"
                                             data-recurso-id="<?= $recurso['idrecurso'] ?>"
                                             onerror="console.error('Error cargando imagen:', this.src); this.onerror=null;this.src='<?= base_url('img/portada_default.png') ?>';">
                                    <?php else: ?>
                                        <img src="<?= base_url('img/portada_default.png') ?>" alt="Sin portada" style="height:60px;width:auto;border-radius:4px;border:1px solid #e5e5e5;object-fit:cover;"
                                             data-recurso-id="<?= $recurso['idrecurso'] ?>">
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
                                <td>
                                    <?php if(!empty($recurso['encuadernacion'])): ?>
                                        <?= esc($recurso['encuadernacion']) ?>
                                    <?php else: ?>
                                        <span class="text-muted">N/A</span>
                                    <?php endif; ?>
                                </td>
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
                                    <?php if(isset($recurso['tiporecurso']) && stripos($recurso['tiporecurso'], 'digital') !== false): ?>
                                        <span class="badge bg-info" title="Recurso digital">
                                            <i class="ti ti-device-desktop me-1"></i>Digital
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-dark" title="Recurso físico">
                                            <i class="ti ti-book me-1"></i>Físico
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="#" 
                                           class="btn btn-sm btn-warning btn-edit" 
                                           data-url="<?= base_url('recursos/modal-editar/' . $recurso['idrecurso']) ?>"
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
                                <td colspan="12" class="text-center py-4">
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
        <?php
            $itemsPorPagina = max(1, $per_page ?? 15);
            $paginaActual = max(1, $pagina_actual ?? 1);
            $totalRegistros = $total_recursos ?? 0;
            $inicio = $totalRegistros > 0 ? ($paginaActual - 1) * $itemsPorPagina + 1 : 0;
            $fin = $totalRegistros > 0 ? min($totalRegistros, $paginaActual * $itemsPorPagina) : 0;
            $totalPaginas = (int) ceil($totalRegistros / $itemsPorPagina);
        ?>
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 mt-3">
            <div class="text-muted small">
                <?php if ($totalRegistros > 0): ?>
                    Mostrando <?= $inicio ?>-<?= $fin ?> de <?= $totalRegistros ?> recursos
                <?php else: ?>
                    No hay registros para paginar
                <?php endif; ?>
            </div>
            <?php if ($totalPaginas > 1): ?>
                <?php
                    $request = service('request');
                    $basePath = service('uri')->getPath();
                    $baseUrl = base_url($basePath);
                    $queryParams = $request->getGet();
                    unset($queryParams['page']);
                    $buildUrl = static function (string $base, array $params): string {
                        return $params ? $base . '?' . http_build_query($params) : $base;
                    };
                ?>
                <nav aria-label="Paginación de recursos" class="pagination-wrapper recursos-digitales-pagination-container">
                    <ul class="pagination recursos-digitales-pagination mb-0">
                        <?php
                            $prevDisabled = $paginaActual <= 1;
                            $prevParams = $queryParams;
                            if (!$prevDisabled) {
                                $prevParams['page'] = $paginaActual - 1;
                            }
                            $prevUrl = $prevDisabled ? 'javascript:void(0);' : $buildUrl($baseUrl, $prevParams);
                        ?>
                        <li class="page-item <?= $prevDisabled ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= $prevUrl ?>" data-page="<?= max(1, $paginaActual - 1) ?>" aria-label="Anterior">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        <?php for ($page = 1; $page <= $totalPaginas; $page++): ?>
                            <?php
                                $pageParams = $queryParams;
                                $pageParams['page'] = $page;
                                $pageUrl = $buildUrl($baseUrl, $pageParams);
                            ?>
                            <li class="page-item <?= $page === $paginaActual ? 'active' : '' ?>">
                                <a class="page-link" href="<?= $pageUrl ?>" data-page="<?= $page ?>" aria-current="<?= $page === $paginaActual ? 'page' : 'false' ?>">
                                    <?= $page ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                        <?php
                            $nextDisabled = $paginaActual >= $totalPaginas;
                            $nextParams = $queryParams;
                            if (!$nextDisabled) {
                                $nextParams['page'] = $paginaActual + 1;
                            }
                            $nextUrl = $nextDisabled ? 'javascript:void(0);' : $buildUrl($baseUrl, $nextParams);
                        ?>
                        <li class="page-item <?= $nextDisabled ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= $nextUrl ?>" data-page="<?= min($totalPaginas, $paginaActual + 1) ?>" aria-label="Siguiente">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
        </div>
    </div>
</div>

<!-- Incluir directamente el modal de crear recurso -->
<?= view('recursos/formulario_crear') ?>

<script>
$(document).ready(function() {
    // Función para recargar solo la lista de recursos
    function recargarListaRecursos() {
        // Mostrar indicador de carga en el contenedor principal
        $('#contenedor-principal').html(`
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="mt-3">Actualizando lista de recursos...</p>
            </div>
        `);
        
        // Hacer petición AJAX para recargar solo la lista de recursos
        $.get('<?= base_url("recursos") ?>', function(data) {
            $('#contenedor-principal').html(data);
            
            // Re-inicializar cualquier funcionalidad específica si es necesaria
            if (typeof window.initRecursosList === 'function') {
                window.initRecursosList();
            }
        }).fail(function() {
            $('#contenedor-principal').html(`
                <div class="text-danger text-center py-5">
                    <i class="ti ti-alert-circle fs-1 mb-3"></i>
                    <h5>Error al cargar la lista de recursos</h5>
                    <p>Por favor, inténtalo de nuevo.</p>
                    <button class="btn btn-primary" onclick="recargarListaRecursos()">
                        <i class="ti ti-refresh"></i> Reintentar
                    </button>
                </div>
            `);
        });
    }
    
    // Hacer la función disponible globalmente
    window.recargarListaRecursos = recargarListaRecursos;

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

    // Delegar click para botón Editar: cargar modal por AJAX directamente
    $(document).on('click', '.btn-edit', function(e) {
        e.preventDefault();
        var url = $(this).data('url');
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
                // Definir y exponer globalmente el toggle completo del modal (los <script> internos no se ejecutan al inyectar HTML)
                window.toggleCamposDigitalEditar = function(){
                    try {
                        var sel = modalEl.querySelector('#idtiporecurso');
                        if (!sel) return;
                        
                        var val = String(sel.value || '');
                        var selectedOpt = sel.selectedIndex >= 0 ? sel.options[sel.selectedIndex] : null;
                        var isDigital = false;
                        
                        // Detectar si es digital por data-digital attribute
                        if (selectedOpt && selectedOpt.getAttribute('data-digital') === '1') {
                            isDigital = true;
                        }
                        
                        // Elementos del modal
                        var campoPdf = modalEl.querySelector('#campoPdfLibroEditar');
                        var campoStock = modalEl.querySelector('#campoStockEditar');
                        var campoEstado = modalEl.querySelector('#campoEstadoEditar');
                        var campoEncuadernacion = modalEl.querySelector('#campoEncuadernacionEditar');
                        var stockInput = modalEl.querySelector('#stock');
                        var estadoSelect = modalEl.querySelector('#estado');
                        var encuadernacionSelect = modalEl.querySelector('#encuadernacion');
                        var pdfInput = modalEl.querySelector('#archivo_pdf');
                        
                        if (isDigital) {
                            // Para recursos digitales: mostrar PDF, deshabilitar stock, estado y encuadernación
                            if (campoPdf) campoPdf.style.display = 'block';
                            if (campoStock) campoStock.style.display = 'block';
                            if (campoEstado) campoEstado.style.display = 'block';
                            if (campoEncuadernacion) campoEncuadernacion.style.display = 'block';
                            
                            // Deshabilitar campos de stock, estado y encuadernación
                            if (stockInput) {
                                stockInput.disabled = true;
                                stockInput.classList.add('form-control-disabled');
                                stockInput.removeAttribute('required');
                            }
                            if (estadoSelect) {
                                estadoSelect.disabled = true;
                                estadoSelect.classList.add('form-select-disabled');
                                estadoSelect.removeAttribute('required');
                            }
                            if (encuadernacionSelect) {
                                encuadernacionSelect.disabled = true;
                                encuadernacionSelect.classList.add('form-select-disabled');
                            }
                            
                            // Habilitar PDF y hacerlo requerido para recursos digitales
                            if (pdfInput) {
                                pdfInput.disabled = false;
                                pdfInput.setAttribute('required', 'required');
                            }
                            
                            // Cambiar color del texto de ayuda
                            var stockHelp = modalEl.querySelector('#stockHelpEditar');
                            var estadoHelp = modalEl.querySelector('#estadoHelpEditar');
                            var encuadernacionHelp = modalEl.querySelector('#encuadernacionHelpEditar');
                            if (stockHelp) stockHelp.style.color = '#6c757d';
                            if (estadoHelp) estadoHelp.style.color = '#6c757d';
                            if (encuadernacionHelp) encuadernacionHelp.style.color = '#6c757d';
                        } else {
                            // Para recursos físicos: ocultar PDF, habilitar stock, estado y encuadernación
                            if (campoPdf) {
                                campoPdf.style.display = 'none';
                                if (pdfInput) {
                                    pdfInput.value = '';
                                    pdfInput.disabled = true;
                                    pdfInput.removeAttribute('required');
                                }
                            }
                            if (campoStock) campoStock.style.display = 'block';
                            if (campoEstado) campoEstado.style.display = 'block';
                            if (campoEncuadernacion) campoEncuadernacion.style.display = 'block';
                            
                            // Habilitar campos de stock, estado y encuadernación
                            if (stockInput) {
                                stockInput.disabled = false;
                                stockInput.classList.remove('form-control-disabled');
                                stockInput.setAttribute('required', 'required');
                            }
                            if (estadoSelect) {
                                estadoSelect.disabled = false;
                                estadoSelect.classList.remove('form-select-disabled');
                                estadoSelect.setAttribute('required', 'required');
                            }
                            if (encuadernacionSelect) {
                                encuadernacionSelect.disabled = false;
                                encuadernacionSelect.classList.remove('form-select-disabled');
                            }
                            
                            // Restaurar color del texto de ayuda
                            var stockHelp = modalEl.querySelector('#stockHelpEditar');
                            var estadoHelp = modalEl.querySelector('#estadoHelpEditar');
                            var encuadernacionHelp = modalEl.querySelector('#encuadernacionHelpEditar');
                            if (stockHelp) stockHelp.style.color = '';
                            if (estadoHelp) estadoHelp.style.color = '';
                            if (encuadernacionHelp) encuadernacionHelp.style.color = '';
                        }
                    } catch(e) { console.warn('toggleCamposDigitalEditar error:', e); }
                };
                // Enlazar change y disparar una vez
                var selTipo = modalEl.querySelector('#idtiporecurso');
                if (selTipo) {
                    selTipo.addEventListener('change', window.toggleCamposDigitalEditar);
                }
                // Llamada inicial para estado actual
                window.toggleCamposDigitalEditar();

                // Exponer globalmente actualizarRecurso para el botón onclick dentro del modal
                window.actualizarRecurso = function() {
                    try {
                        var form = modalEl.querySelector('#formEditarRecurso');
                        var alerta = modalEl.querySelector('#alertaValidacionRecursoEditar');
                        var idrecurso = modalEl.querySelector('#idrecurso').value;
                        var formData = new FormData(form);

                        // Validaciones mínimas
                        var titulo = (modalEl.querySelector('#titulo')?.value || '').trim();
                        var numpaginas = modalEl.querySelector('#numpaginas')?.value;
                        var estado = modalEl.querySelector('#estado')?.value;
                        var stock = modalEl.querySelector('#stock')?.value;
                        var autoresMarcados = Array.from(modalEl.querySelectorAll('input[name="idautor[]"]:checked')).map(function(el){return el.value;});

                        alerta.classList.add('d-none');
                        if (!titulo || autoresMarcados.length === 0 || !numpaginas || !estado || !stock) {
                            alerta.className = 'alert alert-danger';
                            alerta.textContent = 'Por favor complete todos los campos requeridos y seleccione al menos un autor';
                            alerta.classList.remove('d-none');
                            return;
                        }

                        // Compatibilidad backend: enviar un idautor simple
                        formData.append('idautor', autoresMarcados[0]);

                        fetch('<?= base_url('recursos/actualizar') ?>/' + idrecurso, {
                            method: 'POST',
                            body: formData
                        })
                        .then(function(r){ return r.json(); })
                        .then(function(data){
                            if (data && data.status === 'success') {
                                alerta.className = 'alert alert-success';
                                alerta.innerHTML = '<strong>¡Actualización exitosa!</strong><br>Recurso actualizado: <strong>' + (data.titulo || titulo) + '</strong>';
                                alerta.classList.remove('d-none');
                                setTimeout(function(){
                                    var bsModal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                                    bsModal.hide();
                                    setTimeout(function(){
                                        var backdrop = document.querySelector('.modal-backdrop');
                                        if (backdrop) backdrop.remove();
                                        document.body.classList.remove('modal-open');
                                        document.body.style.overflow = '';
                                        document.body.style.paddingRight = '';
                                        // refrescar lista si existe helper
                                        if (typeof window.recargarListaRecursos === 'function') {
                                            window.recargarListaRecursos();
                                        } else {
                                            // fallback: recargar toda la página
                                            location.reload();
                                        }
                                    }, 300);
                                }, 1200);
                            } else {
                                alerta.className = 'alert alert-danger';
                                alerta.textContent = (data && data.message) ? data.message : 'Error al actualizar recurso';
                                alerta.classList.remove('d-none');
                            }
                        })
                        .catch(function(){
                            alerta.className = 'alert alert-danger';
                            alerta.textContent = 'Error de conexión';
                            alerta.classList.remove('d-none');
                        });
                    } catch (e) {
                        console.warn('actualizarRecurso error:', e);
                    }
                };
                var modal = new bootstrap.Modal(modalEl, { backdrop: 'static' });
                modal.show();
                $(modalEl).on('hidden.bs.modal', function() { $(this).remove(); });
            } else {
                alert('No se encontró el contenido del modal.');
            }
        }).fail(function() {
            alert('No se pudo cargar el formulario de edición.');
        });
    });

    // Interceptar paginación para cargar contenido vía AJAX en el contenedor del dashboard
    $(document).on('click', '.pagination.recursos-digitales-pagination .page-link', function(e) {
        var href = $(this).attr('href');
        if (!href) return; // sin URL, no hacemos nada
        var $contenedor = $('#contenedor-principal');
        if ($contenedor.length === 0) {
            // Si no existe el contenedor (acceso directo), dejar navegación normal
            return;
        }
        e.preventDefault();
        // Spinner de carga
        $contenedor.html(`
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="mt-3">Cargando página...</p>
            </div>
        `);
        // Cargar por AJAX solo el cuerpo de recursos
        $.ajax({
            url: href,
            method: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .done(function(html) {
            $contenedor.html(html);
            // Actualizar URL sin recargar (mantener estado de navegación)
            if (window.history && history.pushState) {
                history.pushState({}, '', href);
            }
            // Ir al inicio del contenedor
            window.scrollTo({ top: 0, behavior: 'smooth' });
        })
        .fail(function() {
            $contenedor.html('<div class="text-danger text-center py-5">No se pudo cargar la página solicitada.</div>');
        });
    });

    // Delegar click en Editar para cargar la vista de edición sin recargar la página
    $(document).on('click', '.ajax-edit', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        // Cargar la vista de edición y renderizarla en el contenedor principal del dashboard
        $.get(url, function(html) {
            $('#contenedor-principal').html(html);
        }).fail(function() {
            // Fallback: navegar normal si falla AJAX
            window.location.href = url;
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
                                    timer: 1500,
                                    showConfirmButton: false,
                                    timerProgressBar: true
                                }).then(function() {
                                    // Recargar solo el contenido de recursos manteniendo el diseño
                                    recargarListaRecursos();
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

    // ========================================
    // FILTROS CON AJAX (sin recargar página)
    // ========================================
    
    // Función para aplicar filtros
    function aplicarFiltros() {
        var estado = $('#filtroEstado').val();
        var anioDesde = $('#filtroAnioDesde').val();
        var anioHasta = $('#filtroAnioHasta').val();
        var tipo = $('#filtroTipo').val();
        
        // Construir URL con parámetros
        var params = [];
        if (estado) params.push('estado=' + encodeURIComponent(estado));
        if (anioDesde) params.push('anio_desde=' + encodeURIComponent(anioDesde));
        if (anioHasta) params.push('anio_hasta=' + encodeURIComponent(anioHasta));
        if (tipo) params.push('tipo=' + encodeURIComponent(tipo));
        
        var url = '<?= base_url("recursos") ?>' + (params.length > 0 ? '?' + params.join('&') : '');
        
        // Mostrar indicador de carga solo en la tabla
        $('.table-responsive').html(`
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="mt-3">Filtrando recursos...</p>
            </div>
        `);
        
        // Hacer petición AJAX
        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                // Extraer solo la tabla del HTML recibido
                var tempDiv = $('<div>').html(response);
                var nuevaTabla = tempDiv.find('.table-responsive').html();
                
                if (nuevaTabla) {
                    $('.table-responsive').html(nuevaTabla);
                } else {
                    $('.table-responsive').html(`
                        <div class="text-center py-4">
                            <i class="ti ti-inbox fs-1 mb-3"></i>
                            <h5>No se encontraron recursos</h5>
                            <p>Intenta con otros filtros</p>
                        </div>
                    `);
                }
            },
            error: function() {
                $('.table-responsive').html(`
                    <div class="text-danger text-center py-4">
                        <i class="ti ti-alert-circle fs-1 mb-3"></i>
                        <h5>Error al filtrar recursos</h5>
                        <button class="btn btn-primary" onclick="aplicarFiltros()">
                            <i class="ti ti-refresh"></i> Reintentar
                        </button>
                    </div>
                `);
            }
        });
    }
    
    // Evento click en botón Limpiar
    $('#btnLimpiar').on('click', function() {
        // Limpiar selects
        $('#filtroEstado').val('');
        $('#filtroAnioDesde').val('');
        $('#filtroAnioHasta').val('');
        $('#filtroTipo').val('');
        
        // Aplicar filtros (sin filtros = mostrar todos)
        aplicarFiltros();
    });
    
    // Aplicar filtros al cambiar cualquier select
    $('#filtroEstado, #filtroTipo').on('change', function() {
        aplicarFiltros();
    });
    
    // ========================================
    // VALIDACIÓN Y FILTRADO PARA INPUTS DE AÑO
    // ========================================
    
    // Validar que solo se ingresen números en los campos de año
    $('#filtroAnioDesde, #filtroAnioHasta').on('keypress', function(e) {
        // Permitir solo números (0-9)
        var charCode = (e.which) ? e.which : e.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            e.preventDefault();
            return false;
        }
        return true;
    });
    
    // Aplicar filtros al presionar Enter en los campos de año
    $('#filtroAnioDesde, #filtroAnioHasta').on('keyup', function(e) {
        if (e.key === 'Enter') {
            aplicarFiltros();
        }
    });
    
    // Aplicar filtros al perder el foco (blur) si hay 4 dígitos o está vacío
    $('#filtroAnioDesde, #filtroAnioHasta').on('blur', function() {
        var valor = $(this).val().trim();
        // Solo aplicar si está vacío o tiene exactamente 4 dígitos
        if (valor === '' || valor.length === 4) {
            aplicarFiltros();
        } else if (valor.length > 0 && valor.length < 4) {
            // Mostrar advertencia si tiene menos de 4 dígitos
            $(this).addClass('is-invalid');
            setTimeout(() => {
                $(this).removeClass('is-invalid');
            }, 2000);
        }
    });
    
    // Limpiar validación al escribir
    $('#filtroAnioDesde, #filtroAnioHasta').on('input', function() {
        $(this).removeClass('is-invalid');
    });
    
    // ========================================
    // EXPORTAR PDF CON FILTROS
    // ========================================
    
    $('#btnExportarPdf').on('click', function() {
        // Obtener valores de filtros activos
        var estado = $('#filtroEstado').val();
        var anioDesde = $('#filtroAnioDesde').val();
        var anioHasta = $('#filtroAnioHasta').val();
        var tipo = $('#filtroTipo').val();
        
        // Construir URL con parámetros
        var params = [];
        if (estado) params.push('estado=' + encodeURIComponent(estado));
        if (anioDesde) params.push('anio_desde=' + encodeURIComponent(anioDesde));
        if (anioHasta) params.push('anio_hasta=' + encodeURIComponent(anioHasta));
        if (tipo) params.push('tipo=' + encodeURIComponent(tipo));
        
        var url = '<?= base_url("recursos/pdf") ?>' + (params.length > 0 ? '?' + params.join('&') : '');
        
        // Abrir en nueva ventana para descargar
        window.open(url, '_blank');
    });
    
    // Hacer función disponible globalmente
    window.aplicarFiltros = aplicarFiltros;
});
</script>
