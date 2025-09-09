<?= $header ?>

<div class="container mt-4">
    <div class="card shadow-lg rounded-3">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Registrar Recurso</h4>
        </div>
        <div class="card-body">
            <form action="<?= base_url('recursos/guardar') ?>" method="post" enctype="multipart/form-data">
                <div class="row">
                    <!-- Columna izquierda -->
                    <div class="col-md-6">
                        <!-- Título -->
                        <div class="mb-3">
                            <label for="titulo" class="form-label">Título: <span class="text-danger">*</span></label>
                            <input type="text" id="titulo" name="titulo" class="form-control" required maxlength="150">
                        </div>

                        <!-- Autor -->
                        <div class="mb-3">
                            <label for="idautor" class="form-label">Autor: <span class="text-danger">*</span></label>
                            <select name="idautor" id="idautor" class="form-select" required>
                                <option value="">Seleccione un autor</option>
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

                        <!-- Categoría -->
                        <div class="mb-3">
                            <label for="idcategoria" class="form-label">Categoría:</label>
                            <select name="idcategoria" id="idcategoria" class="form-select" onchange="cargarSubcategorias()">
                                <option value="">Seleccione una categoría</option>
                                <?php foreach ($categorias as $categoria): ?>
                                    <option value="<?= esc($categoria['idcategoria']) ?>">
                                        <?= esc($categoria['categoria']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Subcategoría -->
                        <div class="mb-3">
                            <label for="idsubcategoria" class="form-label">Subcategoría:</label>
                            <select name="idsubcategoria" id="idsubcategoria" class="form-select">
                                <option value="">Primero seleccione una categoría</option>
                            </select>
                        </div>

                        <!-- Editorial -->
                        <div class="mb-3">
                            <label for="ideditorial" class="form-label">Editorial:</label>
                            <select name="ideditorial" id="ideditorial" class="form-select">
                                <option value="">Seleccione una editorial</option>
                                <?php foreach ($editoriales as $editorial): ?>
                                    <option value="<?= esc($editorial['ideditorial']) ?>">
                                        <?= esc($editorial['editorial']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Tipo de Recurso -->
                        <div class="mb-3">
                            <label for="idtiporecurso" class="form-label">Tipo de Recurso:</label>
                            <select name="idtiporecurso" id="idtiporecurso" class="form-select" onchange="toggleCamposDigital()">
                                <option value="">Seleccione un tipo</option>
                                <?php foreach ($tiposrecurso as $tipo): ?>
                                    <option value="<?= esc($tipo['idtiporecurso']) ?>">
                                        <?= esc($tipo['tiporecurso']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Nivel Educativo -->
                        <div class="mb-3">
                            <label for="nivel" class="form-label">Nivel Educativo:</label>
                            <select name="nivel" id="nivel" class="form-select">
                                <option value="">Seleccione un nivel</option>
                                <?php foreach ($niveles as $nivel): ?>
                                    <option value="<?= esc($nivel) ?>"><?= esc(ucfirst($nivel)) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Columna derecha -->
                    <div class="col-md-6">
                        <!-- Año -->
                        <div class="mb-3">
                            <label for="anio" class="form-label">Año:</label>
                            <input type="number" id="anio" name="anio" class="form-control" min="1000" max="<?= date('Y') ?>" value="<?= date('Y') ?>">
                        </div>

                        <!-- Número de Páginas -->
                        <div class="mb-3">
                            <label for="numpaginas" class="form-label">Número de Páginas: <span class="text-danger">*</span></label>
                            <input type="number" id="numpaginas" name="numpaginas" class="form-control" required min="1">
                        </div>

                        <!-- Encuadernación -->
                        <div class="mb-3">
                            <label for="encuadernacion" class="form-label">Encuadernación:</label>
                            <select name="encuadernacion" id="encuadernacion" class="form-select">
                                <option value="">Seleccione una opción</option>
                                <option value="Tapa dura">Tapa dura</option>
                                <option value="Tapa blanda">Tapa blanda</option>
                                <option value="Rústica">Rústica</option>
                                <option value="Espiral">Espiral</option>
                                <option value="Digital">Digital</option>
                            </select>
                        </div>

                        <!-- ISBN -->
                        <div class="mb-3">
                            <label for="isbn" class="form-label">ISBN:</label>
                            <input type="text" id="isbn" name="isbn" class="form-control" maxlength="13" placeholder="978-XXXXXXXXX">
                        </div>

                        <!-- Número de Edición -->
                        <div class="mb-3">
                            <label for="numedicion" class="form-label">Número de Edición:</label>
                            <input type="text" id="numedicion" name="numedicion" class="form-control" maxlength="50" placeholder="1ra edición">
                        </div>

                        <!-- Estado -->
                        <div class="mb-3">
                            <label for="estado" class="form-label">Estado: <span class="text-danger">*</span></label>
                            <select name="estado" id="estado" class="form-select" required>
                                <option value="">Seleccione una opción</option>
                                <?php foreach ($estados as $estado): ?>
                                    <option value="<?= esc($estado) ?>" <?= $estado === 'disponible' ? 'selected' : '' ?>>
                                        <?= esc(ucfirst($estado)) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Stock -->
                        <div class="mb-3">
                            <label for="stock" class="form-label">Stock: <span class="text-danger">*</span></label>
                            <input type="number" id="stock" name="stock" class="form-control" required min="0" value="1">
                        </div>
                    </div>
                </div>

                <!-- Fila adicional para campos especiales -->
                <div class="row">
                    <div class="col-md-6">
                        <!-- Portada -->
                        <div class="mb-3">
                            <label for="rutaportada" class="form-label">Portada:</label>
                            <input type="file" id="rutaportada" name="rutaportada" class="form-control" accept="image/*">
                            <small class="form-text text-muted">Formatos permitidos: JPG, PNG, GIF. Tamaño máximo: 2MB</small>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <!-- URL del Libro (para libros digitales) -->
                        <div class="mb-3" id="campoUrlLibro" style="display: none;">
                            <label for="urlLibro" class="form-label">URL del Libro Digital:</label>
                            <input type="url" id="urlLibro" name="urlLibro" class="form-control" maxlength="200" placeholder="https://ejemplo.com/libro.pdf">
                            <small class="form-text text-muted">Solo para libros digitales</small>
                        </div>
                    </div>
                </div>

                <!-- Botones -->
                <div class="row mt-4">
                    <div class="col-12">
                        <button type="submit" class="btn btn-success me-2">
                            <i class="fas fa-save"></i> Guardar Recurso
                        </button>
                        <a href="<?= base_url('recursos') ?>" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                    </div>
                </div>
            </form>
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
</script>

<?= $footer ?>