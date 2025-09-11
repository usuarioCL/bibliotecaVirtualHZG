<!-- Modal para nuevo recurso -->
<div class="modal fade" id="modalCrearRecurso" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Registrar Nuevo Recurso</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                <form id="formNuevoRecurso" enctype="multipart/form-data">
                    <!-- Información básica del recurso -->
                    <h6 class="text-primary mb-3">Información Básica</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="titulo" class="form-label">Título</label>
                                <input type="text" class="form-control" id="titulo" name="titulo" required maxlength="150">
                            </div>
                        </div>
                        <div class="col-md-6">
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
                                <small class="form-text text-muted">Marca uno o más autores para este recurso.</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="idcategoria" class="form-label">Categoría</label>
                                <select class="form-select" id="idcategoria" name="idcategoria" onchange="cargarSubcategorias()">
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
                                <select class="form-select" id="idsubcategoria" name="idsubcategoria">
                                    <option value="">Primero seleccione una categoría</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="ideditorial" class="form-label">Editorial</label>
                                <select class="form-select" id="ideditorial" name="ideditorial">
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
                                <label for="idtiporecurso" class="form-label">Tipo de Recurso</label>
                                <select class="form-select" id="idtiporecurso" name="idtiporecurso" onchange="toggleCamposDigital()">
                                    <option value="">Seleccionar tipo</option>
                                    <?php foreach ($tiposrecurso as $tipo): ?>
                                        <option value="<?= esc($tipo['idtiporecurso']) ?>">
                                            <?= esc($tipo['tiporecurso']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
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
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="stock" class="form-label">Stock</label>
                                <input type="number" class="form-control" id="stock" name="stock" required min="0" value="1">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="estado" class="form-label">Estado</label>
                                <select class="form-select" id="estado" name="estado" required>
                                    <option value="disponible" selected>Disponible</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="encuadernacion" class="form-label">Encuadernación</label>
                                <select class="form-select" id="encuadernacion" name="encuadernacion">
                                    <option value="">Seleccionar opción</option>
                                    <option value="Tapa dura">Tapa dura</option>
                                    <option value="Tapa blanda">Tapa blanda</option>
                                    <option value="Rústica">Rústica</option>
                                    <option value="Espiral">Espiral</option>
                                    <option value="Digital">Digital</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="isbn" class="form-label">ISBN</label>
                                <input type="text" class="form-control" id="isbn" name="isbn" maxlength="13" placeholder="978-XXXXXXXXX">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="numedicion" class="form-label">Número de Edición</label>
                                <input type="text" class="form-control" id="numedicion" name="numedicion" maxlength="50" placeholder="1ra edición">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="rutaportada" class="form-label">Portada</label>
                                <input type="file" class="form-control" id="rutaportada" name="rutaportada" accept="image/*">
                                <div class="form-text">Formatos: JPG, PNG, GIF. Máximo 2MB</div>
                            </div>
                        </div>
                    </div>

                    <!-- Campo PDF solo para libros digitales -->
                    <div class="row" id="campoPdfLibro" style="display: none;">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="archivo_pdf" class="form-label">Archivo PDF del Libro (opcional)</label>
                                <input type="file" class="form-control" id="archivo_pdf" name="archivo_pdf" accept="application/pdf">
                                <div class="form-text">Formatos: PDF. Tamaño máximo &lt;= 5MB a 10MB</div>
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

// Mostrar/ocultar campo URL para libros digitales
function toggleCamposDigital() 
{
    const tipoSelect = document.getElementById('idtiporecurso');
    const tipoTexto = tipoSelect.options[tipoSelect.selectedIndex].text.toLowerCase();
    const campoPdf = document.getElementById('campoPdfLibro');
    
    if (tipoTexto.includes('digital')) {
        campoPdf.style.display = 'block';
    } else {
        campoPdf.style.display = 'none';
        const pdfInput = document.getElementById('archivo_pdf');
        if (pdfInput) pdfInput.value = '';
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
    const form = document.getElementById('formNuevoRecurso');
    const formData = new FormData(form);
    const alerta = document.getElementById('alertaValidacionRecurso');
    
    // Limpiar alertas previas
    alerta.classList.add('d-none');
    
    // Validar campos requeridos
    const titulo = document.getElementById('titulo').value.trim();
    const autor = document.getElementById('idautor').value;
    const numpaginas = document.getElementById('numpaginas').value;
    const estado = document.getElementById('estado').value;
    const stock = document.getElementById('stock').value;
    
    if (!titulo || !autor || !numpaginas || !estado || !stock) {
        alerta.className = 'alert alert-danger';
        alerta.textContent = 'Por favor complete todos los campos requeridos';
        alerta.classList.remove('d-none');
        return;
    }
    
    fetch('<?= base_url('recursos/guardar') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alerta.className = 'alert alert-success';
            alerta.innerHTML = `
                <strong>¡Registro exitoso!</strong><br>
                Recurso creado: <strong>${data.titulo || titulo}</strong>
            `;
            alerta.classList.remove('d-none');
            
            // Cerrar modal después de 2 segundos y recargar vista de recursos en el dashboard
            setTimeout(() => {
                const modalElement = document.getElementById('modalCrearRecurso');
                const modalInstance = bootstrap.Modal.getInstance(modalElement);
                
                // Cerrar modal correctamente
                modalInstance.hide();
                
                // Limpiar backdrop y overlays manualmente
                setTimeout(() => {
                    // Eliminar backdrop si existe
                    const backdrop = document.querySelector('.modal-backdrop');
                    if (backdrop) {
                        backdrop.remove();
                    }
                    
                    // Restaurar scroll del body
                    document.body.classList.remove('modal-open');
                    document.body.style.overflow = '';
                    document.body.style.paddingRight = '';
                    
                    // Cargar la vista de recursos en el contenedor principal del dashboard
                    $.get('<?= base_url('recursos') ?>', function(html){ 
                        $('#contenedor-principal').html(html); 
                    });
                }, 300);
            }, 2000);
        } else {
            alerta.className = 'alert alert-danger';
            alerta.textContent = data.message || 'Error al registrar recurso';
            alerta.classList.remove('d-none');
        }
    })
    .catch(error => {
        alerta.className = 'alert alert-danger';
        alerta.textContent = 'Error de conexión';
        alerta.classList.remove('d-none');
    });
}

// Limpiar formulario cuando se cierre el modal
document.getElementById('modalCrearRecurso').addEventListener('hidden.bs.modal', function() {
    document.getElementById('formNuevoRecurso').reset();
    document.getElementById('idsubcategoria').innerHTML = '<option value="">Primero seleccione una categoría</option>';
    document.getElementById('campoUrlLibro').style.display = 'none';
    document.getElementById('alertaValidacionRecurso').classList.add('d-none');
});

// Validación de tipo de recurso al cargar
document.addEventListener('DOMContentLoaded', function() {
    // Establecer valores por defecto
    document.getElementById('anio').value = new Date().getFullYear();
    document.getElementById('stock').value = 1;
    document.getElementById('estado').value = 'disponible';
});
</script>
