<!-- Estilos del modal -->
<link rel="stylesheet" href="<?= base_url('assets/css/modal_recursos.css') ?>">

<!-- Modal para nuevo recurso -->
<div class="modal fade" id="modalCrearRecurso" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Registrar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                <form id="formNuevoRecurso" enctype="multipart/form-data">
                    <!-- Información básica del recurso -->
                    <h6 class="text-primary mb-3">Información Básica</h6>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="titulo" class="form-label">Título</label>
                                <input type="text" class="form-control" id="titulo" name="titulo" required maxlength="150">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="idtiporecurso" class="form-label">Tipo de Recurso</label>
                                <select class="form-select" id="idtiporecurso" name="idtiporecurso" onchange="toggleCamposDigital()" required>
                                    <option value="">Seleccionar tipo</option>
                                    <?php foreach ($tiposrecurso as $tipo): ?>
                                        <option value="<?= esc($tipo['idtiporecurso']) ?>">
                                            <?= esc($tipo['tiporecurso']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="idautor" class="form-label">Autor</label>
                                <input type="text" class="form-control mb-2" id="buscadorAutores" placeholder="Buscar autor...">
                                <div class="border rounded p-2" id="listaAutores" style="max-height:180px;overflow-y:auto;">
                                    <?php foreach ($autores as $autor): ?>
                                        <div class="form-check mb-1 autor-item">
                                            <input class="form-check-input" type="checkbox" name="idautor[]" id="autor<?= esc($autor['idautor']) ?>" value="<?= esc($autor['idautor']) ?>">
                                            <label class="form-check-label" for="autor<?= esc($autor['idautor']) ?>">
                                                <i class="ti ti-user"></i> <?= esc($autor['apeautor']) ?>, <?= esc($autor['nomautor']) ?>
                                                <?php if (!empty($autor['nacionalidad'])): ?>
                                                    <span class="text-muted">(<?= esc($autor['nacionalidad']) ?>)</span>
                                                <?php endif; ?>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <small class="form-text text-muted">Marca uno o más autores para este recurso.</small>
                            </div>
                        </div>
                    </div>
                    
<!-- Script de búsqueda de autores -->
<script>
function activarBuscadorAutores() {
    var buscador = document.getElementById('buscadorAutores');
    if (buscador) {
        buscador.addEventListener('input', function() {
            var filtro = this.value.toLowerCase();
            document.querySelectorAll('#listaAutores .autor-item').forEach(function(item) {
                var texto = item.textContent.toLowerCase();
                item.style.display = texto.includes(filtro) ? '' : 'none';
            });
        });
    }
}
// Ejecutar al cargar el DOM y cada vez que se muestre el modal
document.addEventListener('DOMContentLoaded', activarBuscadorAutores);
var modal = document.getElementById('modalCrearRecurso');
if (modal) {
    modal.addEventListener('shown.bs.modal', function() {
        activarBuscadorAutores();
    });
}
</script>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="idcategoria" class="form-label">Categoría</label>
                                <select class="form-select" id="idcategoria" name="idcategoria" onchange="cargarSubcategorias()" required>
                                    <option value="">Seleccionar categoría</option>
                                    <?php foreach ($categorias as $categoria): ?>
                                        <option value="<?= esc($categoria['idcategoria']) ?>">
                                            <?= esc($categoria['categoria']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="idsubcategoria" class="form-label">Subcategoría</label>
                                <select class="form-select" id="idsubcategoria" name="idsubcategoria" required>
                                    <option value="">Primero seleccione una categoría</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="ideditorial" class="form-label">Editorial</label>
                                <select class="form-select" id="ideditorial" name="ideditorial" required>
                                    <option value="">Seleccionar editorial</option>
                                    <?php foreach ($editoriales as $editorial): ?>
                                        <option value="<?= esc($editorial['ideditorial']) ?>">
                                            <?= esc($editorial['editorial']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nivel" class="form-label">Nivel Educativo</label>
                                <select class="form-select" id="nivel" name="nivel">
                                    <option value="">Seleccionar nivel</option>
                                    <?php foreach ($niveles as $nivel): ?>
                                        <option value="<?= esc($nivel) ?>"><?= esc(ucfirst($nivel)) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6" id="campoEncuadernacion">
                            <div class="mb-3">
                                <label for="encuadernacion" class="form-label">Encuadernación <small class="text-muted" id="encuadernacionHelp">(No aplica para digitales)</small></label>
                                <select class="form-select" id="encuadernacion" name="encuadernacion">
                                    <option value="">Seleccionar opción</option>
                                    <option value="Tapa dura">Tapa dura</option>
                                    <option value="Tapa blanda">Tapa blanda</option>
                                    <option value="Rústica">Rústica</option>
                                    <option value="Espiral">Espiral</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Detalles del recurso -->
                    <hr>
                    <h6 class="text-primary mb-3">Detalles del Recurso</h6>
                    
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="anio" class="form-label">Año</label>
                                <input type="number" class="form-control" id="anio" name="anio" min="1000" max="<?= date('Y') ?>" value="<?= date('Y') ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="numpaginas" class="form-label">Número de Páginas</label>
                                <input type="number" class="form-control" id="numpaginas" name="numpaginas" required min="1">
                            </div>
                        </div>
                        <div class="col-md-3" id="campoStock">
                            <div class="mb-3">
                                <label for="stock" class="form-label">Stock <small class="text-muted" id="stockHelp">(No aplica para digitales)</small></label>
                                <input type="number" class="form-control" id="stock" name="stock" required min="0" value="1">
                            </div>
                        </div>
                        <div class="col-md-3" id="campoEstado">
                            <div class="mb-3">
                                <label for="estado" class="form-label">Estado <small class="text-muted" id="estadoHelp">(Por defecto)</small></label>
                                <select class="form-select" id="estado" name="estado" required disabled>
                                    <option value="disponible" selected>Disponible</option>
                                    <option value="prestado">Prestado</option>
                                    <option value="perdido">Perdido</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="isbn" class="form-label">ISBN</label>
                                <input type="text" class="form-control" id="isbn" name="isbn" maxlength="13" placeholder="978-XXXXXXXXX">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="numedicion" class="form-label">Número de Edición</label>
                                <input type="text" class="form-control" id="numedicion" name="numedicion" maxlength="50" placeholder="1ra edición">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="portada" class="form-label">Portada</label>
                                <input type="file" class="form-control" id="portada" name="portada" accept="image/jpeg,image/jpg,image/png,image/gif">
                                <div class="form-text">Formatos: JPG, JPEG, PNG, GIF. Máximo 2MB</div>
                            </div>
                        </div>
                    </div>

                    <!-- Campo archivo solo para recursos digitales -->
                    <div class="row" id="campoArchivoDigital" style="display: none;">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="archivo" class="form-label">Archivo Digital</label>
                                <input type="file" class="form-control" id="archivo" name="archivo" accept="application/pdf,.epub,.mobi">
                                <div class="form-text">Formatos: PDF, EPUB, MOBI. Tamaño máximo 10MB</div>
                            </div>
                        </div>
                    </div>

                    <div id="alertaValidacionRecurso" class="alert d-none"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="registrarRecurso()">Registrar Recurso</button>
            </div>
        </div>
    </div>
</div>

<script>
// Cargar subcategorías basadas en la categoría seleccionada
function cargarSubcategorias() 
{
    const categoriaId = document.getElementById('idcategoria').value;
    const subcategoriaSelect = document.getElementById('idsubcategoria');
    
    // Limpiar subcategorías
    subcategoriaSelect.innerHTML = '<option value="">Cargando...</option>';
    
    if (!categoriaId) {
        subcategoriaSelect.innerHTML = '<option value="">Primero seleccione una categoría</option>';
        return;
    }

    // Filtrar subcategorías (necesitarás pasar todas las subcategorías al frontend)
    const todasSubcategorias = <?= json_encode($subcategorias) ?>;
    const subcategoriasFiltradas = todasSubcategorias.filter(sub => sub.idcategoria == categoriaId);
    
    subcategoriaSelect.innerHTML = '<option value="">Seleccione una subcategoría</option>';
    subcategoriasFiltradas.forEach(sub => {
        subcategoriaSelect.innerHTML += `<option value="${sub.idsubcategoria}">${sub.subcategoria}</option>`;
    });
}

// Habilitar/deshabilitar campos según el tipo de recurso
function toggleCamposDigital() 
{
    const tipoSelect = document.getElementById('idtiporecurso');
    const tipoTexto = tipoSelect.options[tipoSelect.selectedIndex].text.toLowerCase();
    const campoArchivo = document.getElementById('campoArchivoDigital');
    const campoStock = document.getElementById('campoStock');
    const campoEstado = document.getElementById('campoEstado');
    const campoEncuadernacion = document.getElementById('campoEncuadernacion');
    const stockInput = document.getElementById('stock');
    const estadoSelect = document.getElementById('estado');
    const encuadernacionSelect = document.getElementById('encuadernacion');
    const archivoInput = document.getElementById('archivo');
    
    if (tipoTexto.includes('digital')) {
        // Para recursos digitales: mostrar archivo, deshabilitar stock, estado y encuadernación
        campoArchivo.style.display = 'block';
        campoStock.style.display = 'block';
        campoEstado.style.display = 'block';
        campoEncuadernacion.style.display = 'block';
        
        // Deshabilitar campos de stock, estado y encuadernación
        stockInput.disabled = true;
        estadoSelect.disabled = true;
        encuadernacionSelect.disabled = true;
        
        // Remover atributo required para evitar validación
        stockInput.removeAttribute('required');
        estadoSelect.removeAttribute('required');
        
        // Agregar clase para estilo visual
        stockInput.classList.add('form-control-disabled');
        estadoSelect.classList.add('form-select-disabled');
        encuadernacionSelect.classList.add('form-select-disabled');
        
        // Cambiar texto de ayuda para digitales
        const stockHelp = document.getElementById('stockHelp');
        const estadoHelp = document.getElementById('estadoHelp');
        const encuadernacionHelp = document.getElementById('encuadernacionHelp');
        if (stockHelp) {
            stockHelp.textContent = '(No aplica para digitales)';
            stockHelp.style.color = '#6c757d';
        }
        if (estadoHelp) {
            estadoHelp.textContent = '(No aplica para digitales)';
            estadoHelp.style.color = '#6c757d';
        }
        if (encuadernacionHelp) {
            encuadernacionHelp.textContent = '(No aplica para digitales)';
            encuadernacionHelp.style.color = '#6c757d';
        }
        
        // Limpiar valores de stock, estado y encuadernación para recursos digitales
        stockInput.value = '0';
        estadoSelect.value = 'disponible';
        encuadernacionSelect.value = '';
        
        // Habilitar archivo y hacerlo requerido para recursos digitales
        if (archivoInput) {
            archivoInput.disabled = false;
            archivoInput.setAttribute('required', 'required');
        }
    } else {
        // Para recursos físicos: ocultar archivo, habilitar stock y encuadernación
        campoArchivo.style.display = 'none';
        campoStock.style.display = 'block';
        campoEstado.style.display = 'block';
        campoEncuadernacion.style.display = 'block';
        
        // Habilitar campos de stock y encuadernación (estado siempre deshabilitado al crear)
        stockInput.disabled = false;
        estadoSelect.disabled = true; // Siempre deshabilitado - siempre será "disponible" al crear
        encuadernacionSelect.disabled = false;
        
        // Restaurar atributo required para recursos físicos
        stockInput.setAttribute('required', 'required');
        estadoSelect.setAttribute('required', 'required');
        
        // Remover clase de estilo solo para campos habilitados
        stockInput.classList.remove('form-control-disabled');
        estadoSelect.classList.add('form-select-disabled'); // Estado siempre deshabilitado
        encuadernacionSelect.classList.remove('form-select-disabled');
        
        // Actualizar texto de ayuda para físicos
        const stockHelp = document.getElementById('stockHelp');
        const estadoHelp = document.getElementById('estadoHelp');
        const encuadernacionHelp = document.getElementById('encuadernacionHelp');
        if (stockHelp) {
            stockHelp.textContent = '';
            stockHelp.style.color = '';
        }
        if (estadoHelp) {
            estadoHelp.textContent = '(Por defecto)';
            estadoHelp.style.color = '#6c757d';
        }
        if (encuadernacionHelp) {
            encuadernacionHelp.textContent = '';
            encuadernacionHelp.style.color = '';
        }
        
        // Limpiar archivo y restaurar valores por defecto
        if (archivoInput) {
            archivoInput.value = '';
            archivoInput.disabled = true;
            archivoInput.removeAttribute('required');
        }
        stockInput.value = '1';
        estadoSelect.value = 'disponible'; // Siempre disponible al crear
        encuadernacionSelect.value = '';
    }
}

// Validación de ISBN
document.getElementById('isbn').addEventListener('input', function() 
{
    let isbn = this.value.replace(/[^0-9X]/g, '');
    if (isbn.length > 13) {
        isbn = isbn.substring(0, 13);
    }
    this.value = isbn;
});

// Función para registrar recurso
function registrarRecurso() 
{
    // Verificar que Bootstrap esté disponible
    if (typeof bootstrap === 'undefined') {
        alert('Error: Bootstrap no está disponible. Por favor, recarga la página.');
        return;
    }
    
    const form = document.getElementById('formNuevoRecurso');
    const formData = new FormData(form);
    const alerta = document.getElementById('alertaValidacionRecurso');
    
    // Limpiar alertas previas
    alerta.classList.add('d-none');
    
    // Validar campos requeridos
    const titulo = document.getElementById('titulo').value.trim();
    // Verificar si al menos un autor está seleccionado
    const autoresSeleccionados = document.querySelectorAll('input[name="idautor[]"]:checked');
    const numpaginas = document.getElementById('numpaginas').value;
    const estado = document.getElementById('estado');
    const stock = document.getElementById('stock');
    const archivo = document.getElementById('archivo');
    
    // Verificar si es recurso digital
    const tipoSelect = document.getElementById('idtiporecurso');
    const tipoTexto = tipoSelect.options[tipoSelect.selectedIndex].text.toLowerCase();
    const esDigital = tipoTexto.includes('digital');
    
    // Validaciones básicas
    if (!titulo || autoresSeleccionados.length === 0 || !numpaginas) {
        alerta.className = 'alert alert-danger';
        alerta.textContent = 'Por favor complete todos los campos requeridos';
        alerta.classList.remove('d-none');
        return;
    }
    
    // Validaciones específicas según el tipo de recurso
    if (esDigital) {
        // Para recursos digitales: validar archivo
        if (!archivo.files || archivo.files.length === 0) {
            alerta.className = 'alert alert-danger';
            alerta.textContent = 'Por favor seleccione un archivo digital';
            alerta.classList.remove('d-none');
            return;
        }
    } else {
        // Para recursos físicos: validar stock y estado
        if (!estado.value || !stock.value) {
            alerta.className = 'alert alert-danger';
            alerta.textContent = 'Por favor complete todos los campos requeridos';
            alerta.classList.remove('d-none');
            return;
        }
    }
    
    // Mostrar loading con SweetAlert
    Swal.fire({
        title: 'Creando recurso...',
        text: 'Por favor espera mientras procesamos tu solicitud',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch('<?= base_url('recursos/guardar') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // Cerrar loading
            Swal.close();
            
            // Mostrar éxito con SweetAlert
            Swal.fire({
                icon: 'success',
                title: '¡Recurso creado exitosamente!',
                html: `
                    <div class="text-center">
                        <p><strong>${data.titulo || titulo}</strong></p>
                        <p class="text-muted">El recurso ha sido registrado correctamente en el sistema</p>
                    </div>
                `,
                confirmButtonText: 'Continuar',
                confirmButtonColor: '#198754',
                timer: 4000,
                timerProgressBar: true,
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            }).then(() => {
                // Limpiar el formulario
                form.reset();
                
                // Cerrar el modal
                const modalElement = document.getElementById('modalCrearRecurso');
                if (modalElement) {
                    let modalInstance = bootstrap.Modal.getInstance(modalElement);
                    if (!modalInstance) {
                        modalInstance = new bootstrap.Modal(modalElement);
                    }
                    modalInstance.hide();
                    
                    // Limpiar backdrop y recargar vista
                    setTimeout(() => {
                        limpiarBackdropModal();
                        
                        // Cargar la vista de recursos en el contenedor principal del dashboard
                        if (typeof $ !== 'undefined' && $('#contenedor-principal').length > 0) {
                            $.get('<?= base_url('recursos') ?>', function(html){ 
                                $('#contenedor-principal').html(html); 
                            }).fail(function() {
                                window.location.reload();
                            });
                        } else {
                            window.location.reload();
                        }
                    }, 300);
                } else {
                    window.location.reload();
                }
            });
        } else {
            // Cerrar loading
            Swal.close();
            
            // Mostrar error con SweetAlert
            Swal.fire({
                icon: 'error',
                title: 'Error al crear recurso',
                text: data.message || 'Ocurrió un error inesperado. Por favor, intenta nuevamente.',
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#dc3545',
                showClass: {
                    popup: 'animate__animated animate__shakeX'
                }
            });
        }
    })
    .catch(error => {
        // Cerrar loading
        Swal.close();
        
        // Mostrar error de conexión con SweetAlert
        Swal.fire({
            icon: 'error',
            title: 'Error de conexión',
            html: `
                <div class="text-center">
                    <p>No se pudo conectar con el servidor</p>
                    <p class="text-muted">Por favor, verifica tu conexión e intenta nuevamente</p>
                </div>
            `,
            confirmButtonText: 'Entendido',
            confirmButtonColor: '#dc3545',
            showClass: {
                popup: 'animate__animated animate__shakeX'
            }
        });
    });
}

// Función para limpiar completamente el backdrop del modal
function limpiarBackdropModal() {
    // Eliminar todos los backdrops
    const backdrops = document.querySelectorAll('.modal-backdrop');
    backdrops.forEach(backdrop => backdrop.remove());
    
    // Restaurar scroll del body completamente
    document.body.classList.remove('modal-open');
    document.body.style.overflow = '';
    document.body.style.paddingRight = '';
    document.body.style.overflowX = '';
    document.body.style.overflowY = '';
    
    // Forzar reflow para asegurar que los cambios se apliquen
    document.body.offsetHeight;
}

// Función para inicializar el modal de forma segura
function inicializarModalRecurso() {
    const modalElement = document.getElementById('modalCrearRecurso');
    if (modalElement) {
        // Limpiar formulario cuando se cierre el modal
        modalElement.addEventListener('hidden.bs.modal', function() {
            const form = document.getElementById('formNuevoRecurso');
            const alerta = document.getElementById('alertaValidacionRecurso');
            const subcategoriaSelect = document.getElementById('idsubcategoria');
            const campoArchivo = document.getElementById('campoArchivoDigital');
            
            if (form) form.reset();
            if (alerta) alerta.classList.add('d-none');
            if (subcategoriaSelect) {
                subcategoriaSelect.innerHTML = '<option value="">Primero seleccione una categoría</option>';
            }
            if (campoArchivo) campoArchivo.style.display = 'none';
            
            // Limpiar backdrop después de cerrar el modal
            setTimeout(limpiarBackdropModal, 100);
        });
        
        // Agregar evento para el botón de cerrar (X)
        const btnClose = modalElement.querySelector('.btn-close');
        if (btnClose) {
            btnClose.addEventListener('click', function() {
                setTimeout(limpiarBackdropModal, 300);
            });
        }
        
        // Agregar evento para el botón Cancelar
        const btnCancel = modalElement.querySelector('button[data-bs-dismiss="modal"]');
        if (btnCancel) {
            btnCancel.addEventListener('click', function() {
                setTimeout(limpiarBackdropModal, 300);
            });
        }
    }
}

// Función de emergencia para limpiar backdrop con tecla Escape
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        // Si hay un backdrop visible, limpiarlo
        const backdrop = document.querySelector('.modal-backdrop');
        if (backdrop) {
            limpiarBackdropModal();
        }
    }
});

// Inicializar modal cuando cargue el DOM
document.addEventListener('DOMContentLoaded', function() {
    inicializarModalRecurso();
});

// Validación de tipo de recurso al cargar
document.addEventListener('DOMContentLoaded', function() {
    // Establecer valores por defecto
    const anioInput = document.getElementById('anio');
    const stockInput = document.getElementById('stock');
    const estadoSelect = document.getElementById('estado');
    
    if (anioInput) anioInput.value = new Date().getFullYear();
    if (stockInput) stockInput.value = 1;  
    if (estadoSelect) estadoSelect.value = 'disponible';
});

</script>
