<!-- Modal para editar recurso -->
<div class="modal fade" id="modalEditarRecurso" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Recurso</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                <form id="formEditarRecurso" enctype="multipart/form-data">
                    <input type="hidden" id="idrecurso" name="idrecurso" value="<?= $recurso['idrecurso'] ?>">
                    
                    <!-- Información básica del recurso -->
                    <h6 class="text-primary mb-3">Información Básica</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="titulo" class="form-label">Título</label>
                                <input type="text" class="form-control" id="titulo" name="titulo" required maxlength="150" value="<?= esc($recurso['titulo']) ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="idautor" class="form-label">Autor</label>
                                <div class="border rounded p-2" style="max-height:180px;overflow-y:auto;">
                                    <?php foreach ($autores as $autor): ?>
                                        <div class="form-check mb-1">
                                            <?php $checked = (isset($autorActual) && (string)$autorActual === (string)$autor['idautor']) ? 'checked' : ''; ?>
                                            <input class="form-check-input" type="checkbox" name="idautor[]" id="autor<?= esc($autor['idautor']) ?>" value="<?= esc($autor['idautor']) ?>" <?= $checked ?>>
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
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="idcategoria" class="form-label">Categoría</label>
                                <select class="form-select" id="idcategoria" name="idcategoria" onchange="cargarSubcategoriasEditar()">
                                    <option value="">Seleccionar categoría</option>
                                    <?php foreach ($categorias as $categoria): ?>
                                        <option value="<?= esc($categoria['idcategoria']) ?>" <?= $categoriaActual == $categoria['idcategoria'] ? 'selected' : '' ?>>
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
                                    <option value="">Seleccionar subcategoría</option>
                                    <?php foreach ($subcategorias as $subcategoria): ?>
                                        <option value="<?= esc($subcategoria['idsubcategoria']) ?>" <?= $recurso['idsubcategoria'] == $subcategoria['idsubcategoria'] ? 'selected' : '' ?>>
                                            <?= esc($subcategoria['subcategoria']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="ideditorial" class="form-label">Editorial</label>
                                <select class="form-select" id="ideditorial" name="ideditorial">
                                    <option value="">Seleccionar editorial</option>
                                    <?php foreach ($editoriales as $editorial): ?>
                                        <option value="<?= esc($editorial['ideditorial']) ?>" <?= $recurso['ideditorial'] == $editorial['ideditorial'] ? 'selected' : '' ?>>
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
                                <select class="form-select" id="idtiporecurso" name="idtiporecurso" onchange="toggleCamposDigitalEditar()">
                                    <option value="">Seleccionar tipo</option>
                                    <?php foreach ($tiposrecurso as $tipo): ?>
                                        <option value="<?= esc($tipo['idtiporecurso']) ?>" data-digital="<?= (stripos($tipo['tiporecurso'] ?? '', 'digital') !== false) ? '1' : '0' ?>" <?= $recurso['idtiporecurso'] == $tipo['idtiporecurso'] ? 'selected' : '' ?>>
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
                                        <?php $isSelectedNivel = isset($recurso['nivel']) && strcasecmp(trim((string)$recurso['nivel']), trim((string)$nivel)) === 0; ?>
                                        <option value="<?= esc($nivel) ?>" <?= $isSelectedNivel ? 'selected' : '' ?>><?= esc(ucfirst($nivel)) ?></option>
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
                                <input type="number" class="form-control" id="anio" name="anio" min="1000" max="<?= date('Y') ?>" value="<?= esc($recurso['anio']) ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="numpaginas" class="form-label">Número de Páginas</label>
                                <input type="number" class="form-control" id="numpaginas" name="numpaginas" required min="1" value="<?= esc($recurso['numpaginas']) ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="stock" class="form-label">Stock</label>
                                <input type="number" class="form-control" id="stock" name="stock" required min="0" value="<?= esc($recurso['stock']) ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="estado" class="form-label">Estado</label>
                                <select class="form-select" id="estado" name="estado" required>
                                    <?php foreach ($estados as $estado): ?>
                                        <option value="<?= esc($estado) ?>" <?= $recurso['estado'] == $estado ? 'selected' : '' ?>>
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
                                    <option value="Tapa dura" <?= $recurso['encuadernacion'] == 'Tapa dura' ? 'selected' : '' ?>>Tapa dura</option>
                                    <option value="Tapa blanda" <?= $recurso['encuadernacion'] == 'Tapa blanda' ? 'selected' : '' ?>>Tapa blanda</option>
                                    <option value="Rústica" <?= $recurso['encuadernacion'] == 'Rústica' ? 'selected' : '' ?>>Rústica</option>
                                    <option value="Espiral" <?= $recurso['encuadernacion'] == 'Espiral' ? 'selected' : '' ?>>Espiral</option>
                                    <option value="Digital" <?= $recurso['encuadernacion'] == 'Digital' ? 'selected' : '' ?>>Digital</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="isbn" class="form-label">ISBN</label>
                                <input type="text" class="form-control" id="isbn" name="isbn" maxlength="13" placeholder="978-XXXXXXXXX" value="<?= esc($recurso['isbn']) ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="numedicion" class="form-label">Número de Edición</label>
                                <input type="text" class="form-control" id="numedicion" name="numedicion" maxlength="50" placeholder="1ra edición" value="<?= esc($recurso['numedicion']) ?>">
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
                    <div class="row" id="campoPdfLibroEditar" style="display: none;">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="archivo_pdf" class="form-label">Archivo PDF del Libro (opcional)</label>
                                <input type="file" class="form-control" id="archivo_pdf" name="archivo_pdf" accept="application/pdf">
                                <div class="form-text">Formatos: PDF. Tamaño máximo <= 5MB a 10MB</div>
                            </div>
                        </div>
                    </div>

                    <div id="alertaValidacionRecursoEditar" class="alert d-none"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="actualizarRecurso()">Actualizar Recurso</button>
            </div>
        </div>
    </div>
</div>

<script>
// Cargar subcategorías basadas en la categoría seleccionada (para editar)
function cargarSubcategoriasEditar() 
{
    const categoriaId = document.getElementById('idcategoria').value;
    const subcategoriaSelect = document.getElementById('idsubcategoria');
    
    // Limpiar subcategorías
    subcategoriaSelect.innerHTML = '<option value="">Cargando...</option>';
    
    if (!categoriaId) {
        subcategoriaSelect.innerHTML = '<option value="">Primero seleccione una categoría</option>';
        return;
    }

    // Filtrar subcategorías
    const todasSubcategorias = <?= json_encode($subcategorias) ?>;
    const subcategoriasFiltradas = todasSubcategorias.filter(sub => sub.idcategoria == categoriaId);
    
    subcategoriaSelect.innerHTML = '<option value="">Seleccione una subcategoría</option>';
    subcategoriasFiltradas.forEach(sub => {
        const selected = sub.idsubcategoria == <?= $recurso['idsubcategoria'] ?? 0 ?> ? 'selected' : '';
        subcategoriaSelect.innerHTML += `<option value="${sub.idsubcategoria}" ${selected}>${sub.subcategoria}</option>`;
    });
}

// Mostrar/ocultar campo PDF según tipo de recurso
function toggleCamposDigitalEditar() 
{
    // Detección por ID del tipo digital (calculado en servidor) y respaldo por data-digital
    const digitalTypeIds = <?= json_encode(array_values(array_map(function($t){ return (string)$t['idtiporecurso']; }, array_filter($tiposrecurso ?? [], function($t){ return stripos($t['tiporecurso'] ?? '', 'digital') !== false; })))) ?>;
    const tipoSelect = document.getElementById('idtiporecurso');
    const selectedVal = tipoSelect ? String(tipoSelect.value) : '';
    const selectedOpt = tipoSelect && tipoSelect.selectedIndex >= 0 ? tipoSelect.options[tipoSelect.selectedIndex] : null;
    const campoPdf = document.getElementById('campoPdfLibroEditar');

    const isDigitalById = digitalTypeIds.includes(selectedVal);
    const isDigitalByData = selectedOpt && selectedOpt.getAttribute('data-digital') === '1';

    if (isDigitalById || isDigitalByData) {
        if (campoPdf) campoPdf.style.display = 'block';
    } else {
        if (campoPdf) {
            campoPdf.style.display = 'none';
            const pdfInput = document.getElementById('archivo_pdf');
            if (pdfInput) pdfInput.value = '';
        }
    }
}

// Función para actualizar recurso
function actualizarRecurso() 
{
    const form = document.getElementById('formEditarRecurso');
    const formData = new FormData(form);
    const alerta = document.getElementById('alertaValidacionRecursoEditar');
    const idrecurso = document.getElementById('idrecurso').value;
    
    // Limpiar alertas previas
    alerta.classList.add('d-none');
    
    // Validar campos requeridos
    const titulo = document.getElementById('titulo').value.trim();
    const autoresMarcados = Array.from(document.querySelectorAll('input[name="idautor[]"]:checked')).map(el => el.value);
    const numpaginas = document.getElementById('numpaginas').value;
    const estado = document.getElementById('estado').value;
    const stock = document.getElementById('stock').value;
    
    if (!titulo || autoresMarcados.length === 0 || !numpaginas || !estado || !stock) {
        alerta.className = 'alert alert-danger';
        alerta.textContent = 'Por favor complete todos los campos requeridos y seleccione al menos un autor';
        alerta.classList.remove('d-none');
        return;
    }
    // Enviar primer autor como 'idautor' por compatibilidad con el backend
    formData.append('idautor', autoresMarcados[0]);
    
    fetch(`<?= base_url('recursos/actualizar') ?>/${idrecurso}`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alerta.className = 'alert alert-success';
            alerta.innerHTML = `
                <strong>¡Actualización exitosa!</strong><br>
                Recurso actualizado: <strong>${data.titulo || titulo}</strong>
            `;
            alerta.classList.remove('d-none');
            
            // Cerrar modal después de 2 segundos y recargar vista de recursos en el dashboard
            setTimeout(() => {
                const modalElement = document.getElementById('modalEditarRecurso');
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
            alerta.textContent = data.message || 'Error al actualizar recurso';
            alerta.classList.remove('d-none');
        }
    })
    .catch(error => {
        alerta.className = 'alert alert-danger';
        alerta.textContent = 'Error de conexión';
        alerta.classList.remove('d-none');
    });
}

// Cargar subcategorías al inicializar si ya hay una categoría seleccionada
document.addEventListener('DOMContentLoaded', function() {
    const categoriaSelect = document.getElementById('idcategoria');
    if (categoriaSelect.value) {
        cargarSubcategoriasEditar();
    }
    // Ajustar campos de digital/físico al abrir el modal
    toggleCamposDigitalEditar();
});

// Mostrar el modal automáticamente cuando se carga la vista (Bootstrap 5 nativo)
document.addEventListener('DOMContentLoaded', function() {
    var modalEl = document.getElementById('modalEditarRecurso');
    if (modalEl) {
        var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();
    }
});
</script>
