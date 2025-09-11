<?= $header ?>

<!-- Modal para editar recurso -->
<div class="modal fade" id="modalEditarRecurso" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Recurso</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditarRecurso" action="<?= base_url('recursos/actualizar/'.$recurso['idrecurso']) ?>" method="post" enctype="multipart/form-data">
                    <!-- Información básica del recurso -->
                    <h6 class="text-primary mb-3">Información Básica</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="titulo" class="form-label">Título</label>
                                <input type="text" class="form-control" id="titulo" name="titulo" value="<?= esc($recurso['titulo']) ?>" required maxlength="150">
                            </div>
                        </div>
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
                    </div>

                    <!-- Detalles del Recurso -->
                    <hr>
                    <h6 class="text-primary mb-3">Detalles del Recurso</h6>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="encuadernacion" class="form-label">Encuadernación</label>
                                <select class="form-select" id="encuadernacion" name="encuadernacion">
                                    <option value="">Seleccionar opción</option>
                                    <option value="Tapa dura" <?= (isset($recurso['encuadernacion']) && $recurso['encuadernacion']==='Tapa dura') ? 'selected' : '' ?>>Tapa dura</option>
                                    <option value="Tapa blanda" <?= (isset($recurso['encuadernacion']) && $recurso['encuadernacion']==='Tapa blanda') ? 'selected' : '' ?>>Tapa blanda</option>
                                    <option value="Rústica" <?= (isset($recurso['encuadernacion']) && $recurso['encuadernacion']==='Rústica') ? 'selected' : '' ?>>Rústica</option>
                                    <option value="Espiral" <?= (isset($recurso['encuadernacion']) && $recurso['encuadernacion']==='Espiral') ? 'selected' : '' ?>>Espiral</option>
                                    <option value="Digital" <?= (isset($recurso['encuadernacion']) && $recurso['encuadernacion']==='Digital') ? 'selected' : '' ?>>Digital</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="numedicion" class="form-label">Número de Edición</label>
                                <input type="text" class="form-control" id="numedicion" name="numedicion" maxlength="50" value="<?= esc($recurso['numedicion']) ?>" placeholder="1ra edición">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="idtiporecurso" class="form-label">Tipo de Recurso</label>
                                <select class="form-select" id="idtiporecurso" name="idtiporecurso" onchange="(function(sel){var t=(sel.options[sel.selectedIndex]&&sel.options[sel.selectedIndex].text||'').toLowerCase();var row=document.getElementById('campoPdfLibroEdit');if(row){row.style.display=t.indexOf('digital')!==-1?'block':'none';if(t.indexOf('digital')===-1){var f=document.getElementById('archivo_pdf');if(f) f.value='';}}})(this)">
                                    <option value="">Seleccionar tipo</option>
                                    <?php if (!empty($tiposrecurso)): ?>
                                        <?php foreach ($tiposrecurso as $tipo): ?>
                                            <option value="<?= esc($tipo['idtiporecurso']) ?>" <?= (isset($recurso['idtiporecurso']) && (int)$recurso['idtiporecurso'] === (int)$tipo['idtiporecurso']) ? 'selected' : '' ?>>
                                                <?= esc($tipo['tiporecurso']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="isbn" class="form-label">ISBN</label>
                                <input type="text" class="form-control" id="isbn" name="isbn" maxlength="13" value="<?= esc($recurso['isbn'] ?? '') ?>" placeholder="978-XXXXXXXXX">
                            </div>
                        </div>
                    </div>

                    <?php 
                        $esDigitalEdit = false; 
                        if (!empty($recurso['idtiporecurso']) && !empty($tiposrecurso)) {
                            foreach ($tiposrecurso as $t) {
                                if ((int)$t['idtiporecurso'] === (int)$recurso['idtiporecurso']) {
                                    $esDigitalEdit = (stripos($t['tiporecurso'], 'digital') !== false);
                                    break;
                                }
                            }
                        }
                    ?>
                    <!-- Campo PDF solo para libros digitales -->
                    <div class="row" id="campoPdfLibroEdit" style="display: <?= $esDigitalEdit ? 'block' : 'none' ?>;">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="archivo_pdf" class="form-label">Archivo PDF del Libro (opcional)</label>
                                <input type="file" class="form-control" id="archivo_pdf" name="archivo_pdf" accept="application/pdf">
                                <div class="form-text">Formatos: PDF. Tamaño máximo <= 10MB</div>
                                <?php if (!empty($recurso['urlLibro'])): ?>
                                    <div class="mt-2">
                                        <small>Actual: <a href="<?= (stripos($recurso['urlLibro'], 'http://') === 0 || stripos($recurso['urlLibro'], 'https://') === 0) ? esc($recurso['urlLibro']) : base_url(esc($recurso['urlLibro'])) ?>" target="_blank" rel="noopener">Ver PDF</a></small>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Portada -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="rutaportada" class="form-label">Portada</label>
                                <input type="file" class="form-control" id="rutaportada" name="rutaportada" accept="image/*">
                                <div class="form-text">Formatos: JPG, PNG, GIF. Máx 2MB</div>
                                <?php if (!empty($recurso['rutaportada'])): ?>
                                    <div class="mt-2">
                                        <small>Actual:</small><br>
                                        <img src="<?= base_url(esc($recurso['rutaportada'])) ?>" alt="Portada" style="max-height:120px;width:auto;border:1px solid #ddd;padding:2px;border-radius:4px;object-fit:cover;">
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="estado" class="form-label">Estado</label>
                                <select class="form-select" id="estado" name="estado" required>
                                    <option value="">Seleccionar estado</option>
                                    <?php foreach ($estados as $estado): ?>
                                        <option value="<?= esc($estado) ?>" <?= ($estado === $recurso['estado']) ? 'selected' : '' ?>><?= esc(ucfirst($estado)) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="stock" class="form-label">Stock</label>
                                <input type="number" class="form-control" id="stock" name="stock" required min="0" value="<?= esc($recurso['stock']) ?>">
                            </div>
                        </div>
                    </div>

                    <div id="alertaValidacionEditar" class="alert d-none"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="formEditarRecurso" class="btn btn-success">Actualizar</button>
            </div>
        </div>
    </div>
</div>

<script>
// Envío por fetch similar al crear: feedback, cerrar y recargar
(function(){
    var form = document.getElementById('formEditarRecurso');
    if (!form) return;
    form.addEventListener('submit', function(e){
        e.preventDefault();
        var formData = new FormData(form);
        var alerta = document.getElementById('alertaValidacionEditar');
        if (alerta) { alerta.classList.add('d-none'); }
        fetch(form.action, { method: 'POST', body: formData })
            .then(function(res){ return res.text(); })
            .then(function(){
                if (alerta) {
                    alerta.className = 'alert alert-success';
                    alerta.textContent = 'Actualizado correctamente';
                    alerta.classList.remove('d-none');
                }
                setTimeout(function(){
                    var modalEl = document.getElementById('modalEditarRecurso');
                    if (modalEl) {
                        var instance = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                        instance.hide();
                    }
                    window.location.reload();
                }, 1200);
            })
            .catch(function(){
                if (alerta) {
                    alerta.className = 'alert alert-danger';
                    alerta.textContent = 'Error al actualizar';
                    alerta.classList.remove('d-none');
                }
            });
    });
})();

// No se requiere script adicional para el toggle, se maneja inline en el onchange
</script>

<?= $footer ?>