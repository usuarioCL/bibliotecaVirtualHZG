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
                                <select class="form-select" id="idautor" name="idautor" required>
                                    <option value="">Seleccionar autor</option>
                                    <?php foreach ($autores as $autor): ?>
                                        <option value="<?= esc($autor['idautor']) ?>">
                                            <?= esc($autor['apeautor']) ?>, <?= esc($autor['nomautor']) ?> 
                                            <?php if (!empty($autor['nacionalidad'])): ?>
                                                (<?= esc($autor['nacionalidad']) ?>)
                                            <?php endif; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
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
                                    <option value="">Seleccionar estado</option>
                                    <?php foreach ($estados as $estado): ?>
                                        <option value="<?= esc($estado) ?>" <?= $estado === 'disponible' ? 'selected' : '' ?>>
                                            <?= esc(ucfirst($estado)) ?>
                                        </option>
                                    <?php endforeach; ?>
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

                    <!-- Campo URL para libros digitales -->
                    <div class="row" id="campoUrlLibro" style="display: none;">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="urlLibro" class="form-label">URL del Libro Digital</label>
                                <input type="url" class="form-control" id="urlLibro" name="urlLibro" maxlength="200" placeholder="https://ejemplo.com/libro.pdf">
                                <div class="form-text">Solo para libros digitales</div>
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
function cargarSubcategorias() {
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
function toggleCamposDigital() {
    const tipoSelect = document.getElementById('idtiporecurso');
    const tipoTexto = tipoSelect.options[tipoSelect.selectedIndex].text.toLowerCase();
    const campoUrl = document.getElementById('campoUrlLibro');
    
    if (tipoTexto.includes('digital')) {
        campoUrl.style.display = 'block';
    } else {
        campoUrl.style.display = 'none';
        document.getElementById('urlLibro').value = '';
    }
}

// Validación de ISBN
document.getElementById('isbn').addEventListener('input', function() {
    let isbn = this.value.replace(/[^0-9X]/g, '');
    if (isbn.length > 13) {
        isbn = isbn.substring(0, 13);
    }
    this.value = isbn;
});

// Función para registrar recurso
function registrarRecurso() {
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
            
            // Cerrar modal después de 3 segundos y recargar
            setTimeout(() => {
                bootstrap.Modal.getInstance(document.getElementById('modalCrearRecurso')).hide();
                location.reload();
            }, 3000);
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
