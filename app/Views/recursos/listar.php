<div class="">
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
        </div>
    </div>
</div>

<!-- Incluir directamente el modal de crear recurso -->
<?= view('recursos/crear') ?>

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
});
</script>
